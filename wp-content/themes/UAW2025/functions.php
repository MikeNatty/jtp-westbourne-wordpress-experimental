<?php

namespace Flynt;

use Flynt\Utils\Asset;
use Flynt\ComponentManager;
use Flynt\Utils\FileLoader;

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('WP_ENV')) {
    define('WP_ENV', function_exists('wp_get_environment_type') ? wp_get_environment_type() : 'production');
} elseif (!defined('WP_ENVIRONMENT_TYPE')) {
    define('WP_ENVIRONMENT_TYPE', WP_ENV);
}

// Check if the required plugins are installed and activated.
// If they aren't, this function redirects the template rendering to use
// plugin-inactive.php instead and shows a warning in the admin backend.
if (Init::checkRequiredPlugins()) {
    FileLoader::loadPhpFiles('inc');
    add_action('after_setup_theme', [\Flynt\Init::class, 'initTheme']);
    add_action('after_setup_theme', [\Flynt\Init::class, 'loadComponents'], 101);

}

// Remove the admin-bar inline-CSS as it isn't compatible with the sticky footer CSS.
// This prevents unintended scrolling on pages with few content, when logged in.
add_theme_support('admin-bar', ['callback' => '__return_false']);

add_action('after_setup_theme', function (): void {
    // Make theme available for translation.
    // Translations can be filed in the /languages/ directory.
    load_theme_textdomain('flynt', get_template_directory() . '/languages');
});


/**
 * Populate existing ACF select field with taxonomy terms
 */
add_filter(
    'acf/load_field/key=field_pageComponents_pageComponents_blockCardCarousel_contenthub_taxonomy',
    function ($field) {

            // Get all terms from your custom taxonomy
        $terms = get_terms([
            'taxonomy' => 'contenthub-type', // Replace with your taxonomy name
            'hide_empty' => false,
        ]);

        // Format terms for ACF field
        $field['choices'] = [];
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $field['choices'][$term->term_id] = $term->name;
            }
        }

        return $field;
    }
);


add_filter('acf/fields/google_map/api', function ($api) {
    $api['key'] = 'AIzaSyC4Qm86apT4DHupwh5pn9O97dhIbcpEqug';
    return $api;
});



function import_locations_from_json()
{
    // Check if import has already been completed
    if (get_option('locations_import_completed')) {
        error_log('Locations import already completed. Skipping...');
        return;
    }

    // Check for temporary lock to prevent duplicate imports
    if (get_transient('importing_locations')) {
        error_log('Import already in progress. Skipping duplicate request...');
        return;
    }

    // Set a temporary lock
    set_transient('importing_locations', true, 5 * MINUTE_IN_SECONDS);

    // Read the JSON file
    $json_data = file_get_contents(get_template_directory() . '/assets/data/services.json');
    $locations = json_decode($json_data, true);

    if (empty($locations)) {
        error_log('No locations found in JSON file or file could not be read.');
        delete_transient('importing_locations');
        return;
    }

    // Track number of imported locations
    $imported_count = 0;

    foreach ($locations as $location) {
        // Create post
        $post_data = [
            'post_title'    => $location['name'],
            'post_status'   => 'publish',
            'post_type'     => 'location'
        ];

        $post_id = wp_insert_post($post_data);

        if (!is_wp_error($post_id)) {
            // Get detailed address data from Google Maps API
            $address_data = get_google_maps_data($location['address']);

            if ($address_data) {
                // Update ACF fields with detailed address data
                update_field('address', $address_data, $post_id);

                // Add postcode to service areas
                if (!empty($address_data['post_code'])) {
                    update_field('service_areas', [$address_data['post_code']], $post_id);
                }
            }

            // Update other fields
            update_field('phone', $location['phone'], $post_id);
            update_field('website', $location['link'], $post_id);

            // Add random services
            $possible_services = [2020, 2018, 2016, 2014, 2012, 2010, 2008, 2006, 2004];
            shuffle($possible_services);
            $num_services = rand(2, 3);
            $selected_service_ids = array_slice($possible_services, 0, $num_services);

            // For a Post Object field, we need to ensure these are valid post IDs
            // Verify these IDs exist
            $valid_service_ids = [];
            foreach ($selected_service_ids as $service_id) {
                // Check if this is a valid post ID
                if (get_post($service_id)) {
                    $valid_service_ids[] = $service_id;
                }
            }
            $is_multiple = true; // Set to true if your field allows multiple selections
            if ($is_multiple) {
                update_field('services', $valid_service_ids, $post_id);
            } else {
                // For single selection, use the first valid ID
                update_field('services', !empty($valid_service_ids) ? $valid_service_ids[0] : '', $post_id);
            }

            // Set the location category based on property category
            $category_slug = 'service-location'; // default category

            switch ($location['category']) {
                case 'residential-care':
                    $category_slug = 'residential-care';
                    break;
                case 'home-care':
                    $category_slug = 'home-care-provider';
                    break;
                case 'independent-retirement-living':
                    $category_slug = 'independent-living';
                    break;
            }

            // Assign the taxonomy term
            wp_set_object_terms($post_id, $category_slug, 'location-category');

            $imported_count++;
        }
    }

    // Mark import as completed, remove lock, and log success
    update_option('locations_import_completed', true);
    delete_transient('importing_locations');
    error_log("Successfully imported {$imported_count} locations.");
}

function get_google_maps_data($address)
{
    $api_key = 'AIzaSyC4Qm86apT4DHupwh5pn9O97dhIbcpEqug'; // Your Google Maps API key
    $address = urlencode($address);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$api_key}";

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if ($data['status'] !== 'OK') {
        return false;
    }

    $result = $data['results'][0];
    $address_components = $result['address_components'];

    // Initialize variables
    $formatted_data = [
        'address' => $result['formatted_address'],
        'lat' => $result['geometry']['location']['lat'],
        'lng' => $result['geometry']['location']['lng'],
        'zoom' => 12,
        'place_id' => $result['place_id']
    ];

    // Map address components
    foreach ($address_components as $component) {
        if (in_array('street_number', $component['types'])) {
            $formatted_data['street_number'] = $component['long_name'];
        }
        if (in_array('route', $component['types'])) {
            $formatted_data['street_name'] = $component['long_name'];
            $formatted_data['street_name_short'] = $component['short_name'];
        }
        if (in_array('locality', $component['types'])) {
            $formatted_data['city'] = $component['long_name'];
        }
        if (in_array('administrative_area_level_1', $component['types'])) {
            $formatted_data['state'] = $component['long_name'];
            $formatted_data['state_short'] = $component['short_name'];
        }
        if (in_array('postal_code', $component['types'])) {
            $formatted_data['post_code'] = $component['long_name'];
        }
        if (in_array('country', $component['types'])) {
            $formatted_data['country'] = $component['long_name'];
            $formatted_data['country_short'] = $component['short_name'];
        }
    }

    return $formatted_data;
}

// Modify the hook to include a way to reset the import
add_action('init', function () {
    // To reset the import, uncomment these lines:
    // delete_option('locations_import_completed');
    // remove_all_locations();

    // Uncomment this line to run a fresh import
    // import_locations_from_json();
});


function remove_all_locations()
{
    // Get all location posts
    $locations = get_posts([
        'post_type' => 'location',
        'posts_per_page' => -1,
        'post_status' => 'any' // This will get all posts regardless of status
    ]);

    foreach ($locations as $location) {
        wp_delete_post($location->ID, true); // true means force delete (bypass trash)
    }
}

// dynamically update book a tour form with locations
// add_filter('frm_setup_edit_fields_vars', 'frm_populate_posts', 20, 10); //use this function on edit too
add_filter('frm_setup_new_fields_vars', function ($values, $field)
{
    if ($field->field_key == 'gif8i'){
        $posts = get_posts( array('post_type' => 'location', 'post_status' => array('publish'), 'numberposts' => 999, 'orderby' => 'title', 'order' => 'ASC'));
        unset($values['options']);
        $values['options'] = array(''); //remove this line if you are using a checkbox or radio button field
        $values['options'][''] = ''; //remove this line if you are using a checkbox or radio button field
        foreach($posts as $p){
            $values['options'][$p->post_name] = $p->post_title;
        }
        $values['use_key'] = true; //this will set the field to save the post ID instead of post title
        unset($values['options'][0]);
    }

    return $values;
} , 20, 2);


// Usage:
// [the-location-name][ID][/the-location-name]
// Insert Formidable field ID into the enclosed shortcode [ID]
add_shortcode('the-location-name',function( $content = null ) {
	// if(!$content) return;

  $locationSlug = $content;
  $args = array(
    'name'           => $locationSlug,
    'post_type'      => 'location',
    'post_status'    => 'publish',
    'posts_per_page' => 1
  );

  $posts = get_posts($args);

  if (!empty($posts)) {
      return $posts[0]->post_title;
  }

  return;
});


// Google Analytics - Mike: Unsure best place to add this. Putting here for now...
// TODO :: Should retrieve GTM ID from WP or .env
add_action('wp_body_open', function() {
    ?>
    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer', 'GTM-XXXXXXX' );
    </script>
    <!-- End Google Tag Manager -->
    <?php
});

