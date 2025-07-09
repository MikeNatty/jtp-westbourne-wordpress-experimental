<?php

namespace Flynt\Components\BlockEmbeddedContent;

use Flynt\FieldVariables;

function getACFLayout(): array
{
    return [
        'name' => 'blockEmbeddedContent',
        'label' => __('Embedded Content', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => __('Source url', 'flynt'),
                'name' => 'srcUrl',
                'type' => 'text',
                'required' => 1,
                'instructions' => 'Podcast or pdf source url.<br> e.g. "https://player.flipsnack.com?hash=QTk3NjVFQkE5RjcrdGkyY282YWppcQ==&p=8"',
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],     
            FieldVariables\getAnchorOptions(),    
            [
                'label' => __('Height of the embedded content (px)', 'flynt'),
                'name' => 'iframeHeight',
                'type' => 'number',
                'required' => 1,
                'default_value' => 480,
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
