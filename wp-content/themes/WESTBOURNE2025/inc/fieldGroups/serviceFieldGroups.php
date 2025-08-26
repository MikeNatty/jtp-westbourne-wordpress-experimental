<?php

use ACFComposer\ACFComposer;
use Flynt\Utils\Options;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'serviceFields',
        'title' => 'Service Fields',
        'style' => 'seamless',
        'show_in_rest' => true,
        'fields' => [
            [
                'label' => 'Service Icon',
                'name' => 'icon',
                'type' => 'image',
                'required' => 0,
                'wrapper' => [
                    'width' => '20',
                    'class' => '',
                    'id' => '',
                ],
                'relevanssi_exclude' => 1,
                'return_format' => 'url',
                'library' => 'all',
                'max_width' => 300,
                'max_height' => 300,
                'max_size' => 1,
                'mime_types' => 'svg, png',
            ],
            [
                'label' => 'Description',
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 4,
                'required' => 1,
                'wrapper' => [
                    'width' => '80',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'name' => 'servicePage',
                'label' => 'Service Page',
                'instructions' => 'Select the content page that describes this service. Leave blank if this service has no corresponding page.',
                'type' => 'post_object',
                'post_type' => 'page',
                'multiple' => 0,
                'return_format' => 'id',
                'required' => 0,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'service',
                ],
            ],
        ],
    ]);
});
add_filter('acf/load_field/name=associated_hcp_display', function ($field) {
    global $post;
    if (!$post || $post->post_type !== 'service') {
        return $field;
    }

    $message = '';
    $query = new WP_Query([
        'post_type' => 'home-care-package',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'services',
                'value' => '"' . $post->ID . '"',
                'compare' => 'LIKE'
            ]
        ]
    ]);

    if ($query->have_posts()) {
        $message = '<ul>';
        while ($query->have_posts()) {
            $query->the_post();
            $message .= '<li>- ' . get_the_title() . '</li>';
        }
        $message .= '</ul>';
    } else {
        $message = 'No Home Care Packages are currently associated with this service.';
    }

    wp_reset_postdata();
    $field['message'] = $message;

    return $field;
});
