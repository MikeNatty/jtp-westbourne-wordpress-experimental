<?php

use ACFComposer\ACFComposer;
use Flynt\Utils\Options;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'homeCarePackageFields',
        'title' => 'Home Care Package Fields',
        'style' => 'seamless',
        'show_in_rest' => true,
        'fields' => [
            [
                'label' => 'Description',
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 4,
            ],
            [
                'name' => 'call-to-action',
                'label' => 'Call to Action',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'name' => 'call-to-action-url',
                'label' => 'Call to Action URL',
                'type' => 'url',
                'required' => 1,
            ],
            [
                'name' => 'services',
                'label' => 'Services',
                'type' => 'post_object',
                'post_type' => 'service',
                'multiple' => 1,
                'return_format' => 'object',
                'required' => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'home-care-package',
                ],
            ],
        ],
    ]);
});
