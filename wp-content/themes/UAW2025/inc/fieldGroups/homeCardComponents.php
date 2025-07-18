<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup(
        [
            'name' => 'homeCardComponents',
            'title' => 'Home Card Components',
            'style' => 'seamless',
            'fields' => [
                [
                    'name' => 'pageComponents',
                    'label' => __('Page Components', 'flynt'),
                    'type' => 'flexible_content',
                    'button_label' => __('Add Component', 'flynt'),
                    'layouts' => [
                        Components\BlockWysiwyg\getACFLayout(),
                        Components\BlockImage\getACFLayout(),
                        Components\BlockTable\getACFLayout(),
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'homecard'
                    ]
                ]
            ]
        ]
    );
});
