<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup(
        [

            'name' => 'contenthubComponents',
            'title' => 'Article Components',
            'style' => 'seamless',
            // 'menu_order' => 1,
            'fields' => [
                [
                    'name' => 'pageComponents',
                    'label' => __('Page Components', 'flynt'),
                    'type' => 'flexible_content',
                    'button_label' => __('Add Component', 'flynt'),
                    'layouts' => [
                        Components\BlockWysiwyg\getACFLayout(),
                        Components\BlockImage\getACFLayout(),
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'contenthub'
                    ]
                ]
            ]
        ]
    );
});

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'contenthubOpitons',
        'title' => 'Article Options',
        'style' => 'block',
        // 'menu_order' => 0,
        'fields' => [
            [
                'label' => 'Article Type',
                'name' => 'articleType',
                'aria-label' => '',
                'type' => 'taxonomy',
                'instructions' => '',
                'required' => 1,
                'taxonomy' => 'contenthub-type',
                'add_term' => 0,
                'save_terms' => 1,
                'load_terms' => 1,
                'return_format' => 'id',
                'field_type' => 'radio',
                'allow_null' => 0,
                'allow_in_bindings' => 0,
                'bidirectional' => 0,
                'multiple' => 0,
            ],
            [
                'label' => 'Main Image',
                'name' => 'mainImage',
                'type' => 'image',
                'return_format' => 'array',
                // 'min_width' => 2880,
                // 'min_height' => 1600,
                'mime_types' => 'jpg,jpeg,png',
                'instructions' => 'Image-Format: JPG. Featured image will be used as a fallback.',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => 'Card Image',
                'name' => 'cardImage',
                'type' => 'image',
                'return_format' => 'array',
                // 'min_width' => 440, // 220
                // 'min_height' => 640, // 320
                // 'mime_types' => 'jpg,jpeg,png',
                'instructions' => 'Image-Format: JPG. Featured image will be used as a fallback.',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Card Excerpt', 'flynt'),
                'name' => 'cardExcerpt',
                'type' => 'text',
                'maxlength' => 180,
                'required' => 0,
                // TODO remove for prod
                'default_value' => '',
            ],
            [
                'label' => __('Video Player URL (YouTube / Vimeo)', 'flynt'),
                'name' => 'oembed',
                'type' => 'oembed',
                'required' => 1,
                'instructions' => '',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'articleType',
                            'operator' => '==',
                            'value' => '8',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Podcast URL', 'flynt'),
                'name' => 'podcastURL',
                'type' => 'url',
                'required' => 1,
                'instructions' => '',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'articleType',
                            'operator' => '==',
                            'value' => '9',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('CTA Button', 'flynt'),
                'name' => 'ctaLink',
                'type' => 'link',
                'return_format' => 'array',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => [
                //     'url' => '/',
                //     'title' => 'Label',
                //     'target' => 'target'
                // ]
            ],
            // [
            //     'label' => __('Related Posts', 'flynt'),
            //     'name' => 'relatedPosts',
            //     'instructions' => 'Select up to 3 related posts here, or 3 random posts will be chosen automatically.',
            //     'type' => 'post_object',
            //     'post_type' => array(
            //         0 => 'contenthub',
            //     ),
            //     'taxonomy' => '',
            //     'allow_null' => 0,
            //     'multiple' => 1,
            //     'return_format' => 'object',
            //     'ui' => 1,
            // ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'contenthub'
                ]
            ]
        ]
    ]);
});
