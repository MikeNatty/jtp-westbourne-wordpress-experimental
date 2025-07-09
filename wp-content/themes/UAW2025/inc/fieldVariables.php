<?php

/**
 * Defines field variables to be used across multiple components.
 */

namespace Flynt\FieldVariables;

function getPersonalisation(): array
{
    return [
        [
            'label' => __('Personalisation', 'flynt'),
            'name' => 'personalisationTab',
            'type' => 'tab',
            'placement' => 'top',
            'endpoint' => 0,
            'conditional_logic' => [
                [
                    // only shows on personalisation
                    // looks for field and checks if condition is met, it's not so it is hidden
                    // if it can't find the field like on personalisation, it won't add the conditional and it will be displayed
                    [
                        // hide on page components
                        'field' => 'field_pageComponents_pageComponents',
                        'operator' => '==',
                    ],
                    [
                        // hide on content hub
                        'field' => 'field_contenthubComponents_pageComponents',
                        'operator' => '==',
                    ],
                    [
                        // hide on publications
                        'field' => 'field_publicationsComponents_pageComponents',
                        'operator' => '==',
                    ],
                ],
            ],
        ],
        [
            'label' => __('Admin Label', 'flynt'),
            'name' => 'adminLabel',
            'type' => 'text',
            'required' => 0,
        ],
        [
            'label' => __('Selected Answers', 'flynt'),
            'name' => 'selectedAnswers',
            'type' => 'checkbox',
            'required' => 0,
            'choices' => [
                1 => 'Answer 1',
                2 => 'Answer 2',
                3 => 'Answer 3',
                4 => 'Answer 4',
                5 => 'Answer 5',
                6 => 'Answer 6',
            ],
            'return_format' => 'value',
            'layout' => 'vertical',
            'toggle' => 1,
        ],
    ];
}

function getCTA($name = 'cta', $conditional = null): array
{
    $fields = [
        'label' => 'CTA',
        'name' => $name,
        'type' => 'group',
        'wrapper' => [
            'width' => '50',
        ],
        'sub_fields' => [
            [
                'label' => __('CTA type', 'flynt'),
                'name' => 'type',
                'type' => 'button_group',
                'layout' => 'horizontal',
                'choices' => [
                    'none' => __('None', 'flynt'),
                    'link' => __('Link', 'flynt'),
                    'modal' => __('Modal content', 'flynt'),
                    'form' => __('Form', 'flynt'),
                    'download' => __('Download', 'flynt'),
                    'questionnaire' => __('Questionnaire', 'flynt'),
                ],
                'default_value' => 'none'
            ],
            [
                'label' => __('Link', 'flynt'),
                'name' => 'link',
                'type' => 'link',
                'return_format' => 'array',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'type',
                            'operator' => '==',
                            'value' => 'link',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Modal Content', 'flynt'),
                'name' => 'modal',
                'type' => 'post_object',
                'post_type' => [
                    'modal',
                ],
                'taxonomy' => '',
                'allow_null' => 0,
                'multiple' => 0,
                // 'return_format' => 'object',
                'return_format' => 'id',
                'ui' => 1,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'type',
                            'operator' => '==',
                            'value' => 'modal',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Form', 'flynt'),
                'name' => 'form',
                'type' => 'select',
                'layout' => 'horizontal',
                'choices' => [
                    'enquiry' => __('Enquiry - "Enquire now"', 'flynt'),
                    'bookCall' => __('Book a call back - "Book a call"', 'flynt'),
                    'talkRecruitment' => __('Talk to our recruitment team - "View Openings"', 'flynt'),
                    'bookTour' => __('Book a tour - "Book a tour"', 'flynt'),
                    'donation' => __('Make a donation - "Make a donation"', 'flynt'),
                ],
                'default_value' => 'enquiry',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'type',
                            'operator' => '==',
                            'value' => 'form',
                        ],
                    ],
                ],
            ],
            // Mike - Video option
//            [
//                'label' => __('Video Type', 'flynt'),
//                'name' => 'mediaType',
//                'type' => 'button_group',
//                'layout' => 'horizontal',
//                'choices' => [
//                    'videoPlayer' => __('Video Player', 'flynt'),
//                    'videoBackground' => __('Video Background', 'flynt'),
//                ],
//                'conditional_logic' => [
//                    [
//                        [
//                            'fieldPath' => 'type',
//                            'operator' => '==',
//                            'value' => 'video',
//                        ],
//                    ],
//                ],
//                'default_value' => 'videoPlayer'
//            ],
            [
                'label' => __('Downloadable File', 'flynt'),
                'name' => 'file',
                'type' => 'file',
                'return_format' => 'array',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'type',
                            'operator' => '==',
                            'value' => 'download'
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Button text', 'flynt'),
                'name' => 'buttonText',
                'instructions' => 'Optional override for button text.',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'type',
                            'operator' => '==',
                            'value' => 'modal',
                        ],
                    ],
                    [
                        [
                            'fieldPath' => 'type',
                            'operator' => '==',
                            'value' => 'form',
                        ],
                    ],
                    [
                        [
                            'fieldPath' => 'type',
                            'operator' => '==',
                            'value' => 'download',
                        ],
                    ],
                    [
                        [
                            'fieldPath' => 'type',
                            'operator' => '==',
                            'value' => 'questionnaire',
                        ],
                    ],
                ],
            ],

        ],
    ];


    if ($conditional) {
        $fields['conditional_logic'] = [
            [
                $conditional
            ],
        ];
    }


    return [
        $fields
    ];
}

function getAnchorOptions(): array
{
    return [
        [
            'label' => __('Block ID', 'flynt'),
            'name' => 'anchorId',
            'type' => 'text',
            'instructions' => 'Must contain lowercase letters with no spaces. <br>Hyphens "-" are allowed. <br>e.g. "customer-stories"',
            'required' => 0,
            'wrapper' => [
				'width' => '40',
            ],
        ],
        [
            'label' => __('Add to Anchor Link Bar', 'flynt'),
            'name' => 'addToAnchor',
            'type' => 'true_false',
            'default_value' => 0,
            'ui' => 1,
            'ui_on_text' => __('True', 'flynt'),
            'ui_off_text' => __('False', 'flynt'),
            // 'conditional_logic' => [
            //     [
            //         [
            //             'field' => 'field_pageOpitons_anchorLink_enable',
            //             'operator' => '==',
            //             'value' => 1
            //         ],
            //     ],
            // ],
            'wrapper' => [
				'width' => '20',
            ],
        ],
        [
            'label' => __('Anchor Title', 'flynt'),
            'name' => 'anchorTitle',
            'type' => 'text',
            'instructions' => 'The text that appears on the Anchor Bar, spaces and capitals are allowed.',
            'required' => 1,
            'conditional_logic' => [
                [
                    [
                        'fieldPath' => 'addToAnchor',
                        'operator' => '==',
                        'value' => 1
                    ],
                ],
            ],
            'wrapper' => [
				'width' => '40',
            ],
        ],
    ];
}

function getTheme($default = ''): array
{
    return [
        'label' => __('Theme', 'flynt'),
        'name' => 'theme',
        'type' => 'select',
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'ajax' => 0,
        'choices' => [
            '' => __('(none)', 'flynt'),
            'light' => __('Light', 'flynt'),
            'dark' => __('Dark', 'flynt'),
        ],
        'default_value' => $default,
    ];
}

function getSize($default = 'medium'): array
{
    return [
        'label' => __('Size', 'flynt'),
        'name' => 'size',
        'type' => 'radio',
        'other_choice' => 0,
        'save_other_choice' => 0,
        'layout' => 'horizontal',
        'choices' => [
            'medium' => __('Medium', 'flynt'),
            'wide' => __('Wide', 'flynt'),
            'full' => __('Full', 'flynt'),
        ],
        'default_value' => $default
    ];
}

function getAlignment($args = []): array
{
    $options = wp_parse_args($args, [
        'label' => __('Align', 'flynt'),
        'name' => 'align',
        'default' => 'center',
    ]);

    return [
        'label' => $options['label'],
        'name' => $options['name'],
        'type' => 'radio',
        'other_choice' => 0,
        'save_other_choice' => 0,
        'layout' => 'horizontal',
        'choices' => [
            'left' => __('Left', 'flynt'),
            'center' => __('Center', 'flynt'),
        ],
        'default_value' => $options['default']
    ];
}

function getTextAlignment($args = []): array
{
    $options = wp_parse_args($args, [
        'label' => __('Align text', 'flynt'),
        'name' => 'textAlign',
        'default' => 'left',
    ]);

    return [
        'label' => $options['label'],
        'name' => $options['name'],
        'type' => 'button_group',
        'choices' => [
            'left' => sprintf('<i class="dashicons dashicons-editor-alignleft" title="%1$s"></i>', __('Align text left', 'flynt')),
            'center' => sprintf('<i class="dashicons dashicons-editor-aligncenter" title="%1$s"></i>', __('Align text center', 'flynt'))
        ],
        'default_value' => $options['default']
    ];
}
