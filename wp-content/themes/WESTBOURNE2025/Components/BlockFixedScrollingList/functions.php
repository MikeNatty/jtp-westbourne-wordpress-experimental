<?php

namespace Flynt\Components\BlockFixedScrollingList;

use Flynt\FieldVariables;

// Add UUID to GraphQL schema
add_action('graphql_register_types', function () {
    register_graphql_field('PageComponentsPageComponentsBlockAccordionLayout', 'uuid', [
        'type' => 'String',
        'description' => 'Unique identifier for the accordion block.',
        'resolve' => function($root) {
            return $root['uuid'] ?? null;
        }
    ]);
});

add_filter('Flynt/addComponentData?name=BlockFixedScrollingList', function ($data) {
    $data['uuid'] = $data['uuid'] ?? wp_generate_uuid4();
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockFixedScrollingList',
        'label' => __('Fixed Scrolling List', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Section Heading', 'flynt'),
                'name' => 'sectionHeading',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ],
                ]
            ],
            [
                'label' => __('Heading', 'flynt'),
                'name' => 'heading',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
            ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 4,
                'new_lines' => 'br',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ],
                ]
            ],
            FieldVariables\getCTA(
                'cta',
                [
                    'fieldPath' => 'variant',
                    'operator' => '==',
                    'value' => '2'
                ],
            ),
            [
                'label' => __('Image position', 'flynt'),
                'name' => 'imageAlign',
                'type' => 'button_group',
                'layout' => 'horizontal',
//                'wrapper' => [
//                    'width' => '50',
//                ],
                'choices' => [
                    'left' => __('Image left', 'flynt'),
                    'right' => __('Image right', 'flynt'),
                ],
                'default_value' => 'right',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ]
            ],
            [
                'label' => __('Panels', 'flynt'),
                'name' => 'listPanels',
                'type' => 'repeater',
                'min' => 1,
                'max' => 8,
                'collapsed' => 'field_pageComponents_pageComponents_blockFixedScrollingList_panels_heading',
                'layout' => 'row',
                'button_label' => __('Add Item', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Panel heading', 'flynt'),
                        'name' => 'heading',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem ipsum',
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem ipsum',
                    ],
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'content',
                        'type' => 'textarea',
                        'rows' => 3,
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'AgeWell connect recognises the importance of maintaining these social connections, or helping to establish new ones, offering trusted services, information and transport.',
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended size: 240px x 240px', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'required' => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_pageComponents_pageComponents_blockFixedScrollingList_variant',
                                    'operator' => '==',
                                    'value' => '1',
                                ],
                            ],
                        ]
                    ],
                    FieldVariables\getCTA(
                        'cta1',
                        [
                            'field' => 'field_pageComponents_pageComponents_blockFixedScrollingList_variant',
                            'operator' => '==',
                            'value' => '2'
                        ],
                    ),
                    FieldVariables\getCTA(
                        'cta2',
                        [
                            'field' => 'field_pageComponents_pageComponents_blockFixedScrollingList_variant',
                            'operator' => '==',
                            'value' => '2'
                        ],
                    ),
                    FieldVariables\getCTA(
                        'cta3',
                        [
                            'field' => 'field_pageComponents_pageComponents_blockFixedScrollingList_variant',
                            'operator' => '==',
                            'value' => '2'
                        ],
                    ),
//
                ]
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
                'label' => __('Variant', 'flynt'),
                'name' => 'variant',
                'type' => 'button_group',
                'instructions' => 'Variant 1: Image variant.<br>Variant 2: Copy and CTAs - no image.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('Variant 1', 'flynt'),
                    '2' => __('Variant 2', 'flynt'),
                ],
                'default_value' => '1'
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
