<?php

use ACFComposer\ACFComposer;
use Flynt\Utils\Options;

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'serviceAreaFields',
        'title' => 'Service Area Fields',
        'style' => 'seamless',
        'show_in_rest' => true,
        'fields' => [
            [
                'name' => 'provider',
                'label' => 'Service Provider Name',
                'type' => 'post_object',
                'post_type' => 'location',
                'multiple' => 0,
                'return_format' => 'object',
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
                    'value' => 'service-area',
                ],
            ],
        ],
    ]);
});
