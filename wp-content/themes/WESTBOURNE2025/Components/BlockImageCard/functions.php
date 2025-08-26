<?php

namespace Flynt\Components\BlockImageCard;

use Flynt\FieldVariables;

function getACFLayout(): array
{
    return [
        'name' => 'blockImageCard',
        'label' => __('Image Card', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Image', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, WebP.', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg,png,webp',
                'required' => 1,
            ],
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
