<?php

namespace Flynt\Components\BlockChecklist;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockChecklist', function ($data) {
    $data['uuid'] = $data['uuid'] ?? wp_generate_uuid4();
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockChecklist',
        'label' => __('List / Checklist', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Heading', 'flynt'),
                'name' => 'heading',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 'Sed perspicatsum unde omnis',
            ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
                // // TODO remove for prod
                // 'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],
            FieldVariables\getCTA(),
            // [
            //     'label' => __('Downloadable File', 'flynt'),
            //     'name' => 'file',
            //     'type' => 'file',
            //     'return_format' => 'array',
            //     'required' => 0,
            // ],
            [
                'label' => __('List', 'flynt'),
                'name' => 'items',
                'type' => 'repeater',
                'min' => 1,
                'collapsed' => 'field_pageComponents_pageComponents_blockChecklist_panels_heading',
                'layout' => 'table',
                'button_label' => __('Add Item', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Heading', 'flynt'),
                        'name' => 'heading',
//                        'type' => 'textarea',
                        'type' => 'wysiwyg',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        'delay' => 1,
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem sed unde omnis',
                    ],
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
                // 'instructions' => 'Variant 1: Light pink background and additional space under the image. <br>Variant 2: White background.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    'list' => __('List', 'flynt'),
                    'checklist' => __('Checklist', 'flynt'),
                ],
                'default_value' => 'list'
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
