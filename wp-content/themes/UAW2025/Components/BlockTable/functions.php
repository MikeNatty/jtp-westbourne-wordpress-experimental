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
            // [
            //     'label' => __('Section Heading', 'flynt'),
            //     'name' => 'sectionHeading',
            //     'type' => 'textarea',
            //     'rows' => 1,
            //     'new_lines' => 'br',
            //     'required' => 0,
            //     // TODO remove for prod
            //     'default_value' => 'Small heading',
            // ],
            // [
            //     'label' => __('Heading', 'flynt'),
            //     'name' => 'heading',
            //     'type' => 'textarea',
            //     'rows' => 1,
            //     'new_lines' => 'br',
            //     'required' => 0,
            //     // TODO remove for prod
            //     'default_value' => 'Lorem ipsum',
            // ],
            // [
            //     'label' => __('Description', 'flynt'),
            //     'name' => 'contentHtml',
            //     'type' => 'wysiwyg',
            //     'delay' => 0,
            //     'media_upload' => 0,
            //     'required' => 0,
            //     // TODO remove for prod
            //     'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            // ],  
            [
                'label' => __('Table', 'flynt'),
                'name' => 'table',
                'type' => 'table',
                'instructions' => '',
                'required' => 0,
                'use_header' => 2,
                'use_caption' => 2,
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
