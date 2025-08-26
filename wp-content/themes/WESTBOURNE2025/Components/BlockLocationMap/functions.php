<?php

namespace Flynt\Components\BlockLocationMap;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockLocationMap', function ($data) {
    if (isset($_GET['state'])) {
        $state = $_GET['state'];
        $data['mapState'] = $state;
    }
    if (isset($_GET['category'])) {
        $category = $_GET['category'];
        $data['mapCategory'] = $category;
    }

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockLocationMap',
        'label' => __('Location Map', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Filter Settings', 'flynt'),
                'name' => 'filterSettings',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'label' => __('Default Location Category', 'flynt'),
                        'name' => 'defaultLocationCategory',
                        'type' => 'taxonomy',
                        'taxonomy' => 'location-category',
                        'field_type' => 'select',
                        'allow_null' => 1,
                        'add_term' => 0,
                        'save_terms' => 0,
                        'load_terms' => 0,
                        'return_format' => 'object',
                        'multiple' => 0
                    ],
                    [
                        'label' => __('Default State', 'flynt'),
                        'name' => 'defaultState',
                        'type' => 'select',
                        'choices' => [
                            '' => __('Select a state', 'flynt'),
                            'VIC' => __('Victoria', 'flynt'),
                            'TAS' => __('Tasmania', 'flynt')
                        ],
                        'default_value' => '',
                        'allow_null' => 1,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value'
                    ]
                ]
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
