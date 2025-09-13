<?php

namespace Flynt\Components\BlockCTABanner;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockCTABanner', function (array $data): array {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockCTABanner',
        'label' => __('CTA Banner', 'flynt'),
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
                'required' => 1,
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
            FieldVariables\getCTA(),
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


// Options::addTranslatable('blockHeading', [
//     [
//         'label' => __('Labels', 'flynt'),
//         'name' => 'labelsTab',
//         'type' => 'tab',
//         'placement' => 'top',
//         'endpoint' => 0
//     ],
// ]);
