<?php

namespace Flynt\CustomTaxonomies;

use ACFComposer\ACFComposer;
use Flynt\Components;
use Flynt\FieldVariables;

add_action('init', function (): void {
    $labels = [
        'name'                       => _x('Location Categories', 'Taxonomy General Name', 'flynt'),
        'singular_name'              => _x('Location Category', 'Taxonomy Singular Name', 'flynt'),
        'menu_name'                  => __('Location Category', 'flynt'),
        'all_items'                  => __('All Types', 'flynt'),
        'parent_item'                => __('Parent Item', 'flynt'),
        'parent_item_colon'          => __('Parent Item:', 'flynt'),
        'new_item_name'              => __('New Item Name', 'flynt'),
        'add_new_item'               => __('Add New Item', 'flynt'),
        'edit_item'                  => __('Edit Item', 'flynt'),
        'update_item'                => __('Update Item', 'flynt'),
        'view_item'                  => __('View Item', 'flynt'),
        'separate_items_with_commas' => __('Separate items with commas', 'flynt'),
        'add_or_remove_items'        => __('Add or remove items', 'flynt'),
        'choose_from_most_used'      => __('Choose from the most used', 'flynt'),
        'popular_items'              => __('Popular Items', 'flynt'),
        'search_items'               => __('Search Items', 'flynt'),
        'not_found'                  => __('Not Found', 'flynt'),
        'no_terms'                   => __('No items', 'flynt'),
        'items_list'                 => __('Items list', 'flynt'),
        'items_list_navigation'      => __('Items list navigation', 'flynt'),
        'show_in_rest'               => true,
        'rest_base'                  => 'location-categories',
    ];
    $rewrite = [
        'slug'                       => 'location/category',
        'with_front'                 => false,
    ];
    $args = [
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => $rewrite,
    ];

    register_taxonomy('location-category', ['location'], $args);
});

// Add location categories to the location post type
add_action('rest_api_init', function () {
    register_rest_field('location', 'location-categories', [
        'get_callback' => function ($post) {
            return wp_get_post_terms($post['id'], 'location-category');
        },
        'schema' => [
            'description' => 'Location Categories',
            'type'       => 'array'
        ],
    ]);
});


add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'locationCategoryOptions',
        'title' => 'Location Category Options',
        'style' => 'seamless',
        'menu_order' => 2,
        'fields' => [
            [
                'label' => __('Category Page', 'flynt'),
                'name' => 'locationCategoryPage',
                'type' => 'post_object',
                'post_type' => [
                    'page',
                ],
                'return_format' => 'object',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'location-category'
                ]
            ],
        ]
    ]);
});
