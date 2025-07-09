<?php

namespace Flynt\Components\BlockQuote;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockQuote', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockQuote',
        'label' => 'Quote',
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Quote Text',
                'name' => 'quote',
                'type' => 'textarea',
                'new_lines' => 'br',
                'required' => 0,
                'rows' => 4,
                // TODO remove for prod
                'default_value' => 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam',
            ],
            [
                'label' => 'Author Name',
                'name' => 'authorName',
                'type' => 'text',
                'required' => 0,
                // TODO remove for prod
                'default_value' => 'Helen Maning',
            ],
            [
                'label' => 'Author Position',
                'name' => 'authorPosition',
                'type' => 'text',
                'required' => 0,
                // TODO remove for prod
                'default_value' => 'Position',
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
