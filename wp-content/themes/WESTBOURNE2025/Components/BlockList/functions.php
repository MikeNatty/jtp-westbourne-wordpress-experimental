<?php

namespace Flynt\Components\BlockList;

use Flynt\FieldVariables;
use Timber\Timber;
use Flynt\Utils\Options;


add_filter('Flynt/addComponentData?name=BlockList', function (array $data): array {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockList',
        'label' => __('List', 'flynt'),
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
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ],
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
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 3,
                'new_lines' => 'br',
                'required' => 0,
//                'wrapper' => [
//                    'width' => '50',
//                ],
            ],
            [
                'label' => __('Button Link', 'flynt'),
                'name' => 'link',
                'type' => 'link',
                'return_format' => 'array',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
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
//          FieldVariables\getCTA(),
            [
                'label' => __('List Items', 'flynt'),
                'name' => 'listItems',
                'type' => 'repeater',
                'min' => 1,
                'max' => 50,
                'collapsed' => 'field_pageComponents_pageComponents_blockList_listItems_left',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '1',
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
                        'type' => 'text',
                        'placeholder' => '',
                        'required' => 0,
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ]
                ]
            ],
            [
                'label' => 'List Content',
                'name' => 'listContent',
                'type' => 'group',
                'sub_fields' => [
                    [
                        'label' => 'List header',
                        'name' => 'listHeader',
                        'type' => 'text',
//                        'default_value' => 'Privacy Policy',
                    ],
                     [
                        'label' => 'List Content',
                        'instructions' => 'Use a Bulleted or Numbered list.',
                        'name' => 'listContent',
                        'type' => 'wysiwyg',
//                        'default_value' => '#',
                    ],
                ],
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('List Dates', 'flynt'),
                'name' => 'listDates',
                'type' => 'repeater',
                'min' => 1,
                'max' => 50,
                'collapsed' => 'field_pageComponents_pageComponents_blockList_listDates_left',
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
                        'label' => __('Date', 'flynt'),
                        'name' => 'date',
                        'type' => 'text',
//                        'placeholder' => '',
                        'required' => 0,
                    ],
                    [
                        'label' => __('Copy', 'flynt'),
                        'name' => 'copy',
                        'type' => 'text',
                        'placeholder' => '',
                        'required' => 0,
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
                'instructions' => 'Variant 1: Large list items. <br>Variant 2: Long content.<br>Variant 3: Dates.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('Variant 1', 'flynt'),
                    '2' => __('Variant 2', 'flynt'),
                    '3' => __('Variant 3', 'flynt'),
                 ],
                'default_value' => '1'
            ],
        ]
    ];
}
