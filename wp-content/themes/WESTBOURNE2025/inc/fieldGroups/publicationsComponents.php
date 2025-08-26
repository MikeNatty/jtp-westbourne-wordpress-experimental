<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup(
        [
            'name' => 'publicationsComponents',
            'title' => 'Publications Components',
            'style' => 'seamless',
            'fields' => [
                [
                    'name' => 'pageComponents',
                    'label' => __('Page Components', 'flynt'),
                    'type' => 'flexible_content',
                    'button_label' => __('Add Component', 'flynt'),
                    'layouts' => [
                        Components\BlockWysiwyg\getACFLayout(),
                        Components\BlockImage\getACFLayout(),
                        Components\BlockEmbeddedContent\getACFLayout(),
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'publications'
                    ]
                ]
            ]
        ]
    );
});

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'publicationsOptions',
        'title' => 'publications Options',
        'style' => 'block',
        'fields' => [
            [
                'label' => 'Main Image',
                'name' => 'mainImage',
                'type' => 'image',
                'return_format' => 'array',
                // 'min_width' => 2880,
                // 'min_height' => 1600,
                'mime_types' => 'jpg,jpeg,png',
                'instructions' => 'Image-Format: JPG. Featured image will be used as a fallback.',
                'required' => 0
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
                'required' => 0
            ],
            // [
            //     'label' => __('CTA Button', 'flynt'),
            //     'name' => 'cta',
            //     'type' => 'link',
            //     'return_format' => 'array',
            //     'required' => 0,
            //     // TODO remove for prod
            //     'default_value' => [
            //         'url' => '/',
            //         'title' => 'Label',
            //         'target' => 'target'
            //     ]
            // ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'publications'
                ]
            ]
        ]
    ]);
});
