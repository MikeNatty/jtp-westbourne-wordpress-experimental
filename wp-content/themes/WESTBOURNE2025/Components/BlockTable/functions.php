<?php

namespace Flynt\Components\BlockTable;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockTable', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockTable',
        'label' => __('Table', 'flynt'),
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
             'type' => 'textarea',
             'rows' => 1,
             'new_lines' => 'br',
             'required' => 0,
             'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '!=',
                            'value' => '3',
                        ],
                    ],
                ],
            ],
            [
             'label' => __('Heading', 'flynt'),
             'name' => 'heading',
             'type' => 'textarea',
             'rows' => 1,
             'new_lines' => 'br',
             'required' => 0,
             // TODO remove for prod
             'default_value' => 'Lorem ipsum',
            ],
            [
             'label' => __('Description', 'flynt'),
             'name' => 'contentHtml',
             'type' => 'wysiwyg',
             'delay' => 0,
             'media_upload' => 0,
             'required' => 0,
//             // TODO remove for prod
//             'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],
            FieldVariables\getCTA(
                'cta',
                [
                    'fieldPath' => 'variant',
                    'operator' => '==',
                    'value' => '1',
                ]

            ),
            [
                 'label' => __('Table Heading', 'flynt'),
                 'name' => 'tableHeading',
                 'type' => 'textarea',
                 'rows' => 1,
                 'new_lines' => 'br',
                 'required' => 0,
                 'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Table', 'flynt'),
                'name' => 'table',
                'type' => 'table',
                'instructions' => '',
                'required' => 0,
                'use_header' => 1,
                'use_caption' => 0,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '4',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Table Sections', 'flynt'),
                'name' => 'tableSections',
                'type' => 'repeater',
                'min' => 1,
                'max' => 50,
                'collapsed' => 'field_pageComponents_pageComponents_blockTable_tableListItems_heading',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ],
                ],
                'layout' => 'row',
                'button_label' => __('Add Section', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Heading', 'flynt'),
                        'name' => 'heading',
                        'type' => 'text',
                        'placeholder' => '',
                        'required' => 0,
                    ],
                    [
                        'label' => __('Table Items', 'flynt'),
                        'name' => 'tableSectionItems',
                        'type' => 'repeater',
                        'min' => 1,
                        'max' => 50,
//                        'collapsed' => 'field_pageComponents_pageComponents_blockTable_tableListItems_left',

                        'layout' => 'row',
                        'button_label' => __('Add Item', 'flynt'),
                        'sub_fields' => [
                            [
                                'label' => __('Left', 'flynt'),
                                'name' => 'left',
                                'type' => 'text',
                                'placeholder' => '',
                                'required' => 0,
                                'wrapper' => [
                                    'width' => '20',
                                ],
                            ],
                            [
                                'label' => __('Right', 'flynt'),
                                'name' => 'right',
                                'type' => 'text',
                                'placeholder' => '',
                                'required' => 0,
                                'wrapper' => [
                                    'width' => '50',
                                ],
                            ]
                        ]
                    ],
                ]
            ],
            [
                'label' => __('List Items', 'flynt'),
                'name' => 'listItems',
                'type' => 'repeater',
                'min' => 1,
                'max' => 50,
                'collapsed' => 'field_pageComponents_pageComponents_blockTable_listItems_left',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '3',
                        ],
                    ],
                ],
                'layout' => 'row',
                'button_label' => __('Add Item', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Left', 'flynt'),
                        'name' => 'left',
                        'type' => 'text',
                        'placeholder' => '',
                        'required' => 0,
                        'wrapper' => [
                            'width' => '20',
                        ],
                    ],
                    [
                        'label' => __('Right', 'flynt'),
                        'name' => 'right',
                        'type' => 'textarea',
                        'rows' => 2,
                        'new_lines' => 'br',
                        'placeholder' => '',
                        'required' => 0,
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ]
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
                'type' => 'radio',
                'instructions' => 'Variant 1: Full width simple horizontally scrolling table, CTA<br>Variant 2: Full width, sectioned, 3 column horizontally scrolling table, CTA.<br>Variant 3: Fixed two-section list.<br>Variant 4: Copy left, simple horizontally scrolling table right.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('Variant 1', 'flynt'),
                    '2' => __('Variant 2', 'flynt'),
                    '3' => __('Variant 3', 'flynt'),
                    '4' => __('Variant 4', 'flynt'),
                ],
                'default_value' => '1'
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
