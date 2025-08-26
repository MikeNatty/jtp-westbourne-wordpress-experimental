<?php

namespace Flynt\Components\PostFilter;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;


add_filter('Flynt/addComponentData?name=PostFilter', function ($data) {

    if ($data['postType'] == 'events') {
        $taxonomyPrimary = 'events-location';
        $taxonomySecondary = 'events-category';
        $data['taxonomyPrimary'] = 'Location';
    } elseif ($data['postType'] == 'publications') {
        $taxonomyPrimary = 'publications-tag';
        $taxonomySecondary = 'publications-category';
        $data['taxonomyPrimary'] = 'Tag';
    } else {
        $taxonomyPrimary = 'contenthub-type';
        $taxonomySecondary = 'contenthub-category';
        $data['taxonomyPrimary'] = 'Content Type';
    }

    $taxonomyPrimaryTerms = get_terms(array(
        'taxonomy' => $taxonomyPrimary,
        'hide_empty' => false,
    ));
    $data['taxonomyPrimaryTerms'] = $taxonomyPrimaryTerms;

    $taxonomySecondaryTerms = get_terms(array(
        'taxonomy' => $taxonomySecondary,
        'hide_empty' => false,
    ));
    $data['taxonomySecondaryTerms'] = $taxonomySecondaryTerms;

    return $data;
});



// AJAX handler
function filterPosts()
{
    // Verify nonce
    check_ajax_referer('filter_posts_nonce', 'nonce');

    // Get filter parameters
    $content_type = isset($_POST['content_type']) ? sanitize_text_field($_POST['content_type']) : '';
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $location = isset($_POST['location']) ? sanitize_text_field($_POST['location']) : '';
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'newest';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    // NEW: Get post type from JavaScript, with fallback to 'contenthub'
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'contenthub';

    // Build query args
    $args = [
        'post_status' => 'publish',
        'post_type' => $post_type,
        'posts_per_page' => 6,
        'paged' => $paged
    ];

    // Add content type filter
    if (!empty($content_type)) {
        $args['tax_query'][] = [
            'taxonomy' => 'contenthub-type',
            'field' => 'slug',
            'terms' => $content_type
        ];
    }
    if (!empty($tag)) {
        $args['tax_query'][] = [
            'taxonomy' => 'publications-tag',
            'field' => 'slug',
            'terms' => $tag
        ];
    }
    if (!empty($location)) {
        $args['tax_query'][] = [
            'taxonomy' => 'events-location',
            'field' => 'slug',
            'terms' => $location
        ];
    }

    // Add category filter
    if (!empty($category)) {
        if ($post_type == 'events') {
            $taxonomySecondarySlug = 'events-category';
        } elseif ($post_type == 'publications') {
            $taxonomySecondarySlug = 'publications-category';
        } else {
            $taxonomySecondarySlug = 'contenthub-category';
        }

        $args['tax_query'][] = [
            'taxonomy' => $taxonomySecondarySlug,
            'field' => 'slug',
            'terms' => $category
        ];
    }

    // Add sorting
    if ($sort === 'oldest') {
        $args['order'] = 'ASC';
        $args['orderby'] = 'date';
    } else {
        $args['order'] = 'DESC';
        $args['orderby'] = 'date';
    }


    // Execute query with Timber
    $timber_posts = Timber::get_posts($args);
    $imageOptions = Options::getTranslatable('ImageOptions');
    $placeholderImage = $imageOptions['placeholderImage'];

    // Prepare context for Twig
    $context = [
        'posts' => $timber_posts,
        'placeholderImage' => $placeholderImage,
        'postType' => $post_type,
        // 'pagination' => $timber_posts->pagination([
        //     'mid_size' => 2,
        //     'prev_text' => '&laquo; Previous',
        //     'next_text' => 'Next &raquo;',
        //     'show_all' => false,
        // ])
    ];

    // // Render posts and pagination with Timber
    $posts_html = Timber::compile('templates/_cardGrid.twig', $context);
    $pagination_html = Timber::compile('templates/_pagination.twig', $context);

    // Return response
    wp_send_json_success([
        'html' => $posts_html,
        'pagination' => $pagination_html,
        'query' => $args,
    ]);
    // // Return response
    // wp_send_json_success([
    //     'html' => $posts_html,
    //     'pagination' => $pagination_html,
    //     'count' => $timber_posts->found_posts,
    //     'max_pages' => $timber_posts->max_num_pages
    // ]);

    wp_die();
}



// MIKE :: Unable to import Choices.js in postFilter.js as it's a module
// Unsure how to best approah this so for now, trying to load it as a script
wp_enqueue_script(
    'choices-utils',
    get_template_directory_uri() . '/assets/scripts/ChoicesUtils.js',
    [],
    null,
    true
);
wp_enqueue_style(
    'choices-js-css',
    'https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/styles/choices.min.css',
    [],
    null
);
wp_enqueue_script(
    'choices-js',
    'https://cdn.jsdelivr.net/npm/choices.js@9.0.1/public/assets/scripts/choices.min.js',
    [],
    null,
    true
);



// TODO uncomment below to make filters work, need to disable when on other pages

// Register AJAX handlers
add_action('wp_ajax_filter_posts', 'Flynt\Components\PostFilter\filterPosts');
add_action('wp_ajax_nopriv_filter_posts', 'Flynt\Components\PostFilter\filterPosts');

// Register scripts
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'flynt-filterable-posts',
        get_template_directory_uri() . '/assets/scripts/postFilter.js',
        [],
        '1.0.0',
        true
    );

    wp_localize_script('flynt-filterable-posts', 'flyntFilterData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('filter_posts_nonce')
    ]);

//    wp_enqueue_script(
//        'sheet-content',
//        get_template_directory_uri() . '/assets/scripts/sheetContent.js',
//        [],
//        '1.0.0',
//        true
//    );

    wp_localize_script('flynt-filterable-posts', 'flyntFilterData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('filter_posts_nonce')
    ]);
});
//add_action('wp_enqueue_scripts', function () {
//    wp_enqueue_script(
//        'sheet-content',
//        get_template_directory_uri() . '/assets/scripts/sheetContent.js',
//        [],
//        '1.0.0',
//        true
//    );
//
//    wp_localize_script('flynt-filterable-posts', 'flyntFilterData', [
//        'ajaxUrl' => admin_url('admin-ajax.php'),
//        'nonce' => wp_create_nonce('filter_posts_nonce')
//    ]);
//});
