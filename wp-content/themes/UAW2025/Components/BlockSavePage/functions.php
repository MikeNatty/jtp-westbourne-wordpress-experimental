<?php

namespace Flynt\Components\BlockSavePage;

use Flynt\FieldVariables;

function getACFLayout(): array
{
    return [
        'name' => 'blockSavePage',
        'label' => __('Save Page for Later', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Heading', 'flynt'),
                'name' => 'heading',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Formidable Forms shortcode', 'flynt'),
                'name' => 'formShortcode',
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
