<?php

namespace Flynt\Components\BlockLogoList;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockLogoList', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockLogoList',
        'label' => __('Logo List', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Logos', 'flynt'),
                'name' => 'logos',
                'type' => 'repeater',
                'min' => 1,
                'max' => 4,
//                'collapsed' => 'field_pageComponents_pageComponents_blockLogoList_logos_title',
//                'conditional_logic' => [
//                    [
//                        [
//                            'fieldPath' => 'contentSource',
//                            'operator' => '==',
//                            'value' => 'custom',
//                        ],
//                    ],
//                ],
                'layout' => 'row',
                'button_label' => __('Add Logo', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended size: 240px x 240px', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'required' => 0,
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
        ]
    ];
}
