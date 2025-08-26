<?php

namespace Flynt\CustomPostTypes;

add_action('init', function (): void {
    $labels = [
        'name'                  => _x('Home Care Package', 'Post Type General Name', 'flynt'),
        'singular_name'         => _x('Home Care Package', 'Post Type Singular Name', 'flynt'),
        'menu_name'             => __('Home Care Package', 'flynt'),
        'name_admin_bar'        => __('Home Care Package', 'flynt'),
        'archives'              => __('Item Archives', 'flynt'),
        'attributes'            => __('Item Attributes', 'flynt'),
        'parent_item_colon'     => __('Parent Item:', 'flynt'),
        'all_items'             => __('All Home Care Packages', 'flynt'),
        'add_new_item'          => __('Add New Home Care Package', 'flynt'),
        'add_new'               => __('Add Home Care Package', 'flynt'),
        'new_item'              => __('New Home Care Package', 'flynt'),
        'edit_item'             => __('Edit Home Care Package', 'flynt'),
        'update_item'           => __('Update Home Care Package', 'flynt'),
        'view_item'             => __('View Home Care Package', 'flynt'),
        'view_items'            => __('View Home Care Packages', 'flynt'),
        'search_items'          => __('Search Home Care Package', 'flynt'),
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
        'label'                 => __('Home Care Package', 'flynt'),
        'description'           => __('Home Care Package', 'flynt'),
        'labels'                => $labels,
        'supports'              => ['title', 'thumbnail', 'revisions'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-admin-home',
        'menu_position'         => 22,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
        'rest_base'             => 'home-care-packages',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rewrite'               => [ 'slug' => 'home-care-package', 'with_front' => false ],
    ];

    register_post_type('home-care-package', $args);
});
