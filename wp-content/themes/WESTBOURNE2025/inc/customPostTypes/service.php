<?php

namespace Flynt\CustomPostTypes;

add_action('init', function (): void {
    $labels = [
        'name'                  => _x('Service', 'Post Type General Name', 'flynt'),
        'singular_name'         => _x('Service', 'Post Type Singular Name', 'flynt'),
        'menu_name'             => __('Service', 'flynt'),
        'name_admin_bar'        => __('Service', 'flynt'),
        'archives'              => __('Item Archives', 'flynt'),
        'attributes'            => __('Item Attributes', 'flynt'),
        'parent_item_colon'     => __('Parent Item:', 'flynt'),
        'all_items'             => __('All Services', 'flynt'),
        'add_new_item'          => __('Add New Service', 'flynt'),
        'add_new'               => __('Add Service', 'flynt'),
        'new_item'              => __('New Service', 'flynt'),
        'edit_item'             => __('Edit Service', 'flynt'),
        'update_item'           => __('Update Service', 'flynt'),
        'view_item'             => __('View Service', 'flynt'),
        'view_items'            => __('View Services', 'flynt'),
        'search_items'          => __('Search Service', 'flynt'),
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
        'label'                 => __('Service', 'flynt'),
        'description'           => __('Service', 'flynt'),
        'labels'                => $labels,
        'supports'              => ['title', 'revisions'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-networking',
        'menu_position'         => 21,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
        'rest_base'             => 'services',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rewrite'               => [ 'slug' => 'service', 'with_front' => false ],
    ];

    register_post_type('service', $args);
});
