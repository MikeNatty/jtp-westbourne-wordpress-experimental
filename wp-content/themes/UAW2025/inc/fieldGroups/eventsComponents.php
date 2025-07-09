<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

// add_action('Flynt/afterRegisterComponents', function () {
//     ACFComposer::registerFieldGroup(
//         [
//             'name' => 'eventComponents',
//             'title' => 'Event Components',
//             'style' => 'seamless',
//             'fields' => [
//                 [
//                     'name' => 'pageComponents',
//                     'label' => __('Page Components', 'flynt'),
//                     'type' => 'flexible_content',
//                     'button_label' => __('Add Component', 'flynt'),
//                     'layouts' => [
//                         Components\BlockWysiwyg\getACFLayout(),
//                         Components\BlockImage\getACFLayout(),
//                     ],
//                 ],
//             ],
//             'location' => [
//                 [
//                     [
//                         'param' => 'post_type',
//                         'operator' => '==',
//                         'value' => 'events'
//                     ]
//                 ]
//             ]
//         ]
//     );
// });

add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'eventOptions',
        'title' => 'Event Options',
        'style' => 'block',
        'fields' => [
            [
                'label' => 'Main Image',
                'name' => 'mainImage',
                'type' => 'image',
                'return_format' => 'array',
                // 'min_width' => 2880,
                // 'min_height' => 1600,
                'mime_types' => 'jpg,jpeg,png',
                'instructions' => 'Image-Format: JPG. Featured image will be used as a fallback.',
                'required' => 0
            ],
            [
                'label' => 'Card Image',
                'name' => 'cardImage',
                'type' => 'image',
                'return_format' => 'array',
                // 'min_width' => 440, // 220
                // 'min_height' => 640, // 320
                // 'mime_types' => 'jpg,jpeg,png',
                'instructions' => 'Image-Format: JPG. Featured image will be used as a fallback.',
                'required' => 0
            ],
            [
                'label' => __('Subheading', 'flynt'),
                'name' => 'subheading',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                // TODO remove for prod
                'default_value' => 'Enabling communities to age well and individuals to live to their potential.',
            ], 
            [
                'label' => __('CTA Button', 'flynt'),
                'name' => 'cta',
                'type' => 'link',
                'return_format' => 'array',             
                'required' => 0,
                // TODO remove for prod
                'default_value' => [
                    'url' => '/',
                    'title' => 'Register',
                    'target' => 'target'
                ]
            ], 
            [
                'label' => __('Event Details', 'flynt'),
                'name' => 'detailsText',
                'type' => 'textarea',
                'rows' => 3,
                'new_lines' => 'br',
                'required' => 0,
                // TODO remove for prod
                'default_value' => 'At Uniting AgeWell we believe older people, like people of all ages, want to live in an environment of choice, empowerment and wellness. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. ',
            ], 
            [
                'label' => 'Event start date',
                'name' => 'eventStartDate',
                'type' => 'date_picker',
                'required' => 0,
                // 'display_format' => 'd/m/Y g:i a',
                'return_format' => 'F d, Y',
                'first_day' => 1,
            ],
            [
                'label' => 'Event end date',
                'name' => 'eventEndDate',
                'type' => 'date_picker',
                'required' => 0,
                // 'display_format' => 'd/m/Y g:i a',
                'return_format' => 'F d, Y',
                'first_day' => 1,
            ],
            [
                'label' => 'Event time',
                'name' => 'timeText',
                'type' => 'text',
                'default_value' => '9am - 4pm',
                'required' => 0,
            ],
            [
                'label' => 'Location Title',
                'name' => 'locationText',
                'type' => 'text',
                'default_value' => 'Newnham Community, Aldersgate Village',
                'required' => 0,
            ],
            [
                'label' => __('Map Location', 'flynt'),
                'name' => 'mapLocation',
                'type' => 'google_map',
                'center_lat' => '51.5073509', // Default to London
                'center_lng' => '-0.12775829999',
                'instructions' => __('Select a point on the map.', 'flynt'),
                'required' => 0
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'events'
                ]
            ]
        ]
    ]);
});