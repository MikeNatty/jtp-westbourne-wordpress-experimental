<?php

namespace Flynt\CustomPostTypes;

add_action('init', function (): void {
    $labels = [
        'name'                  => _x('Service Areas', 'Post Type General Name', 'flynt'),
        'singular_name'         => _x('Service Area', 'Post Type Singular Name', 'flynt'),
        'menu_name'             => __('Service Area', 'flynt'),
        'name_admin_bar'        => __('Service Area', 'flynt'),
        'archives'              => __('Item Archives', 'flynt'),
        'attributes'            => __('Item Attributes', 'flynt'),
        'parent_item_colon'     => __('Parent Item:', 'flynt'),
        'all_items'             => __('All Service Areas', 'flynt'),
        'add_new_item'          => __('Add New Service Area', 'flynt'),
        'add_new'               => __('Add Service Area', 'flynt'),
        'new_item'              => __('New Service Area', 'flynt'),
        'edit_item'             => __('Edit Service Area', 'flynt'),
        'update_item'           => __('Update Service Area', 'flynt'),
        'view_item'             => __('View Service Area', 'flynt'),
        'view_items'            => __('View Service Areas', 'flynt'),
        'search_items'          => __('Search Service Area', 'flynt'),
        'not_found'             => __('Not found', 'flynt'),
        'not_found_in_trash'    => __('Not found in Trash', 'flynt'),
        'featured_image'        => __('Featured Image', 'flynt'),
        'set_featured_image'    => __('Set featured image', 'flynt'),
        'remove_featured_image' => __('Remove featured image', 'flynt'),
        'use_featured_image'    => __('Use as featured image', 'flynt'),
        'insert_into_item'      => __('Insert into item', 'flynt'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'flynt'),
        'items_list'            => __('Items list', 'flynt'),
        'items_list_navigation' => __('Items list navigation', 'flynt'),
        'filter_items_list'     => __('Filter items list', 'flynt'),
    ];

    $args = [
        'label'                 => __('Service Area', 'flynt'),
        'description'           => __('Service Area', 'flynt'),
        'labels'                => $labels,
        'supports'              => ['title'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-networking',
        'menu_position'         => 21,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
        'rest_base'             => 'service-area',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rewrite'               => [ 'slug' => 'service-area', 'with_front' => false ],
    ];

    register_post_type('service-area', $args);
});


add_action('rest_api_init', function() {
    register_rest_field('service-area', 'associated_services', [
        'get_callback' => function($post) {
            $services = get_field('services', $post['id']);
            if (!$services) return null;
            
            $enriched_services = [];
            foreach ($services as $service) {
                $service_page_id = get_field('servicePage',  $service->ID);
                $service_page_url = $service_page_id 
                    ? get_the_permalink($service_page_id)
                    : null;

                $service_data = array();
                $service_data['title'] = $service->post_title;
                $service_data['description'] = get_field('description',  $service->ID);
                $service_data['icon'] = get_field('icon',  $service->ID);
                $service_data['servicePage'] = $service_page_url;
                $enriched_services[] = $service_data;
            }
            return $enriched_services;
        }
    ]);
});