<?php

namespace Flynt\Components\BlockCTACard;

use Flynt\FieldVariables;

function getACFLayout(): array
{
    return [
        'name' => 'blockCTACard',
        'label' => __('CTA Card', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
//             [
//                'label' => __('Title', 'flynt'),
//                'name' => 'title',
//                'type' => 'textarea',
//                'rows' => 1,
//                'new_lines' => 'br',
//                'required' => 1,
//            ],
//             [
//                'label' => __('Heading', 'flynt'),
//                'name' => 'heading',
//                'type' => 'textarea',
//                'rows' => 1,
//                'new_lines' => 'br',
//                'required' => 1,
//            ],
            [
                'label' => 'Cta',
                'name' => 'cta',
                'instructions' => __('Call to action', 'flynt'),
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'default',
                        ],
                    ],
                ],
                'type' => 'group',
                'sub_fields' => [
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ],
                    FieldVariables\getCTA(),
                ],
            ],
//            [
//                'label' => __('Image', 'flynt'),
//                'instructions' => __('Image-Format: JPG, PNG, WebP.', 'flynt'),
//                'name' => 'image',
//                'type' => 'image',
//                'preview_size' => 'medium',
//                'mime_types' => 'jpg,jpeg,png,webp',
//                'required' => 1,
//            ],
            // [
            //     'label' => __('Options', 'flynt'),
            //     'name' => 'optionsTab',
            //     'type' => 'tab',
            //     'placement' => 'top',
            //     'endpoint' => 0
            // ],
            // [
            //     'label' => '',
            //     'name' => 'options',
            //     'type' => 'group',
            //     'layout' => 'row',
            //     'sub_fields' => [
            //         FieldVariables\getTheme(),
            //         FieldVariables\getSize()
            //     ]
            // ]
        ]
    ];
}
