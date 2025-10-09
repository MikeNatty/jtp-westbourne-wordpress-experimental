<?php

namespace Flynt\Components\BlockForm;

use Flynt\FieldVariables;

function getACFLayout(): array
{
    return [
        'name' => 'blockForm',
        'label' => __('Form', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('GravityForm ID', 'flynt'),
                'name' => 'gravityFormId',
                'type' => 'text',
                'delay' => 0,
                'required' => 1,
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
