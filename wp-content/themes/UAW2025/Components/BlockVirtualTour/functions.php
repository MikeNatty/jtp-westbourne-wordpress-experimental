<?php

namespace Flynt\Components\BlockVirtualTour;

use Flynt\FieldVariables;
use Timber\Timber;

const POST_TYPE = 'contenthub';

add_filter('Flynt/addComponentData?name=BlockVirtualTour', function ($data) {
    // $data['uuid'] = $data['uuid'] ?? wp_generate_uuid4();
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockVirtualTour',
        'label' => 'Virtual Tour',
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
                // 'default_value' => 'Lorem sed unde omnis',
            ],
            [
                'label' => __('Subheading', 'flynt'),
                'name' => 'subheading',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 'Lorem sed unde omnis',
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 'Lorem sed unde omnis',
                // 'wrapper' => [
                //     'width' => '50',
                // ],
            ],
            [
                'label' => __('Button Text', 'flynt'),
                'name' => 'btnText',
                'type' => 'text',
                'required' => 0,
                'default_value' => 'Take a virtual tour',
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Virtual Tour iframe URL', 'flynt'),
                'name' => 'iframeUrl',
                'type' => 'text',
                'required' => 1,
                'default_value' => 'https://storage.net-fs.com/hosting/6404630/22/',
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Image', 'flynt'),
                'name' => 'coverImage',
                'type' => 'image',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg,png,svg,webp',
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
            FieldVariables\getPersonalisation(),
        ]
    ];
}
