<?php

namespace Flynt\Components\BlockContactCard;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockContactCard', function ($data) {
    // TODO - make upadateable field in the CMS with client's api key 
    $data['apiKey'] = 'AIzaSyC4Qm86apT4DHupwh5pn9O97dhIbcpEqug';

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockContactCard',
        'label' => 'Contact Card',
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Title',
                'name' => 'title',
                'type' => 'textarea',
                'new_lines' => 'br',
                'required' => 0,
                'rows' => 1,
                'default_value' => 'Get directions or ask a question',
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            // [
            //     'label' => __('Image', 'flynt'),
            //     'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Min size: 480px x 480px', 'flynt'),
            //     'name' => 'image',
            //     'type' => 'image',
            //     'preview_size' => 'medium',
            //     'mime_types' => 'jpg,jpeg,png,svg,webp',
            //     'required' => 0,
            //     'wrapper' => [
            //         'width' => '50',
            //     ],
            // ],
            [
                'label' => 'Phone Number',
                'name' => 'phoneNumber',
                'type' => 'text',
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            // [
            //     'label' => __('Location Link', 'flynt'),
            //     'name' => 'locationLink',
            //     'type' => 'link',
            //     'return_format' => 'array',
            //     'required' => 0,
            // ],
            [
                'label' => __('Map Location', 'flynt'),
                'name' => 'mapLocation',
                'type' => 'google_map',
                'center_lat' => '-37.8136', // Default to London
                'center_lng' => '144.9631',
                'instructions' => __('Select a point on the map.', 'flynt'),
                'required' => 0,
                'zoom' => 12,
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
