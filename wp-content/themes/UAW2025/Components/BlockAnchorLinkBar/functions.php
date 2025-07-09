<?php

namespace Flynt\Components\BlockAnchorLinkBar;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockAnchorLinkBar', function ($data) {
    $data['uuid'] = $data['uuid'] ?? wp_generate_uuid4();


    // $data['components'] = ['Locations', 'Our residents', 'Sharing the care', 'Respite', 'FAQs'];

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockAnchorLinkBar',
        'label' => __('Anchor link Bar', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ], 
            // [
            //     'label' => __('Enable Bar', 'flynt'),
            //     'name' => 'enable',
            //     'type' => 'true_false',
            //     'default_value' => 1,
            //     'ui' => 1,
            //     'ui_on_text' => __('Enable', 'flynt'),
            //     'ui_off_text' => __('Disable', 'flynt'),
            // ],
            // [
            //     'label' => __('Globally managed content', 'flynt'),
            //     'instructions' => '',
            //     'name' => 'message',
            //     'type' => 'message',
            //     'message' => 'Content is pulled from the first question in the <a href="http://localhost:10054/wp-admin/admin.php?page=personalisation">Questionnaire</a> seciton, within the Question 1 options.',
            //     'required' => 0,
            //     'conditional_logic' => [
            //         [
            //             [
            //                 'field' => 'field_pageComponents_pageComponents_blockAnchorLinkBar_enable',
            //                 'operator' => '==',
            //                 'value' => 1
            //             ],
            //         ],
            //     ],
            // ], 
            // [
            //     'label' => __('CTA Button', 'flynt'),
            //     'name' => 'cta',
            //     'type' => 'link',
            //     'return_format' => 'array',
            //     'required' => 0,
            //     // TODO remove for prod
            //     'default_value' => [
            //         'url' => '/',
            //         'title' => 'Label',
            //         'target' => 'target'
            //     ]
            // ],
            // [
            //     'label' => __('Dowloads', 'flynt'),
            //     'name' => 'downloads',
            //     'type' => 'repeater',
            //     'min' => 0,
            //     'layout' => 'table',
            //     'button_label' => __('Add File', 'flynt'),
            //     'sub_fields' => [
            //         [
            //             'label' => __('Downloadable File', 'flynt'),
            //             'name' => 'file',
            //             'type' => 'file',
            //             'return_format' => 'array',             
            //             'required' => 0,
            //         ],  
            //     ]
            // ], 
        ]
    ];
}
