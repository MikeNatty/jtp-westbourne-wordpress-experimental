<?php

namespace Flynt\CustomPostTypes;

add_action('init', function (): void {
    $labels = [
        'name'                  => _x('Location', 'Post Type General Name', 'flynt'),
        'singular_name'         => _x('Location', 'Post Type Singular Name', 'flynt'),
        'menu_name'             => __('Location', 'flynt'),
        'name_admin_bar'        => __('Location', 'flynt'),
        'archives'              => __('Item Archives', 'flynt'),
        'attributes'            => __('Item Attributes', 'flynt'),
        'parent_item_colon'     => __('Parent Item:', 'flynt'),
        'all_items'             => __('All Locations', 'flynt'),
        'add_new_item'          => __('Add New Location', 'flynt'),
        'add_new'               => __('Add Location', 'flynt'),
        'new_item'              => __('New Location', 'flynt'),
        'edit_item'             => __('Edit Location', 'flynt'),
        'update_item'           => __('Update Location', 'flynt'),
        'view_item'             => __('View Location', 'flynt'),
        'view_items'            => __('View Locations', 'flynt'),
        'search_items'          => __('Search Location', 'flynt'),
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
        'label'                 => __('Location', 'flynt'),
        'description'           => __('Location', 'flynt'),
        'labels'                => $labels,
        'supports'              => ['title', 'thumbnail', 'revisions'],
        'taxonomies'            => ['location-category'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-location',
        'menu_position'         => 20,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
        'rest_base'             => 'locations',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'rewrite'               => [ 'slug' => 'location', 'with_front' => false ],

        // TODO - this is causing regular pages to not load
        // 'rewrite'               => [ 'slug' => '%location-category%', 'with_front' => false ],
    ];

    register_post_type('location', $args);
});

// // change slug to the custom taxonomy slug if it exists
// add_filter( 'post_type_link', function ( $post_link, $id = 0 ){
//     $post = get_post($id);
//     if ( is_object( $post ) ){
//         $terms = wp_get_object_terms( $post->ID, 'location-category' );
//         if( $terms ){
//             $post_link = str_replace( '%location-category%' , $terms[0]->slug , $post_link );
//         } else {
//             $post_link = str_replace( '%location-category%' , 'location' , $post_link );
//         }
//     }
//     return $post_link;  
// }, 1, 2 );