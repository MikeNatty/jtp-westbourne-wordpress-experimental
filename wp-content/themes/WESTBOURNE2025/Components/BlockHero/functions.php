<?php

namespace Flynt\Components\BlockHero;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockHero', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockHero',
        'label' => __('Hero', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 'Lorem dolorisum',
            ],
            [
                'label' => __('Sub Title', 'flynt'),
                'name' => 'subtitle',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],

            // primary
            FieldVariables\getCTA(),
            // secondary
            FieldVariables\getCTA(
                'cta2',
                [
                    'fieldPath' => 'variant',
                    'operator' => '==',
                    'value' => '1'
                ],
            ),
            [
                'label' => __('Image Main', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Min size: 1104px for RHS image. 2880px for bg image.', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg,png,svg,webp',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 91
            ],
            [
                'label' => __('Image', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Min size: 1104px for RHS image. 2880px for bg image.', 'flynt'),
                'name' => 'image2',
                'type' => 'image',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg,png,svg,webp',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ],
                ],
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 91
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            FieldVariables\getAnchorOptions(),  
            [
                'label' => __('Breadcrumbs', 'flynt'),
                'name' => 'showBreadcrumbs',
                'instructions' => 'Show breadcrumbs above title. Only displays when a parent page exists.',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => __('Show', 'flynt'),
                'ui_off_text' => __('Hide', 'flynt'),
            ],
            [
                'label' => __('Variant', 'flynt'),
                'name' => 'variant',
                'type' => 'radio',
                'instructions' => 'Variant 1: Large single image and bottom aligned content. <br>Variant 2: Additional small image and top aligned content.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('Variant 1', 'flynt'),
                    '2' => __('Variant 2', 'flynt'),
                ],
                'default_value' => '1'
            ],
            [
                'label' => __('Title size', 'flynt'),
                'name' => 'titleSize',
                'type' => 'radio',
                'instructions' => 'Size of the title on desktop. <br> Use the smaller size for longer titles.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    'medium' => __('Medium (Step 7)', 'flynt'),
                    'large' => __('Large (Step 8)', 'flynt'),
                ],
                'default_value' => 'large',
                // 'conditional_logic' => [
                //     [
                //         [
                //             'fieldPath' => 'variant',
                //             'operator' => '==',
                //             'value' => '1',
                //         ],
                //     ],
                // ],
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
