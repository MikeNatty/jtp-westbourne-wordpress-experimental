<?php

use ACFComposer\ACFComposer;

add_action('Flynt/afterRegisterComponents', function() {
     ACFComposer::registerFieldGroup([
        'name' => 'leadershipDetails',
        'title' => 'Leadership Details',
        'style' => 'seamless',
        'show_in_rest' => true,
        'fields' => [
            [
                'name' => 'leadershipName',
                'label' => 'Name',
                'name' => 'name',
                'type' => 'text',
            ],
            [
                'name' => 'leadershipTitle',
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
            ],
            [
                'name' => 'leadershipImage',
                'label' => 'Image',
                'name' => 'image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'leadership',
                ],
            ],
        ],
    ]);
});
