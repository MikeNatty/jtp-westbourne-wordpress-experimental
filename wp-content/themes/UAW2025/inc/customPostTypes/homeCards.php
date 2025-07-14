<?php

/**
 * This is an example file showcasing how you can add custom post types to your Flynt theme.
 *
 * For a full list of parameters see https://developer.wordpress.org/reference/functions/register_post_type/ or use https://generatewp.com/post-type/ to generate the code for you.
 */

namespace Flynt\CustomPostTypes;
use Flynt\Utils\Options;

add_action('init', function (): void {
    $labels = [
        'name'                  => _x('Home Cards', 'Post Type General Name', 'flynt'),
        'singular_name'         => _x('Home Card', 'Post Type Singular Name', 'flynt'),
        'menu_name'             => __('Home Cards', 'flynt'),
        'name_admin_bar'        => __('Home Cards', 'flynt'),
//        'archives'              => __('Item Archives', 'flynt'),
//        'attributes'            => __('Item Attributes', 'flynt'),
        'parent_item_colon'     => __('Parent Item:', 'flynt'),
        'all_items'             => __('All Home Cards Content', 'flynt'),
        'add_new_item'          => __('Add New Home Card Content', 'flynt'),
        'add_new'               => __('Add Home Card Content', 'flynt'),
        'new_item'              => __('New Home Card Content', 'flynt'),
        'edit_item'             => __('Edit Home Card Content', 'flynt'),
        'update_item'           => __('Update Home Card Content', 'flynt'),
        'view_item'             => __('View Article', 'flynt'),
        'view_items'            => __('View Articles', 'flynt'),
        'search_items'          => __('Search Article', 'flynt'),
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
        'label'                 => __('Post Type', 'flynt'),
        'description'           => __('Post Type Description', 'flynt'),
        'labels'                => $labels,
        // 'supports'              => ['title', 'editor', 'thumbnail', 'revisions'],
        'supports'              => ['title', 'thumbnail', 'revisions'],
        // 'taxonomies'            => ['category', 'post_tag'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-media-video',
        // 'menu_icon'             => 'dashicons-book',
        'menu_position'         => 21,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite'               => array( 'slug' => 'home-card', 'with_front' => false ),
    ];

    register_post_type('homecard', $args);
});
