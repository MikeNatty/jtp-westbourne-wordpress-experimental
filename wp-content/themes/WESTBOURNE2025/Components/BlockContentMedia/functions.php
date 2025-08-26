<?php

namespace Flynt\Components\BlockContentMedia;

use Flynt\FieldVariables;
use Flynt\Utils\Oembed;

add_filter('Flynt/addComponentData?name=BlockContentMedia', function ($data) {


//    if ($data['mediaType'] == 'videoPlayer') {
    if ($data['mediaType'] == 'videoPlayer' || $data['mediaTypeAlt'] == 'videoPlayer') {
        $data['oembed'] = Oembed::setSrcAsDataAttribute(
            $data['oembed'],
            [
                'autoplay' => 'true',
                'rel' => 0
            ]
        );
        error_log('addComponentData BlockContentMedia oembed: ' . $data['oembed'] );

    }

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockContentMedia',
        'label' => __('Content + Media', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
                // // TODO remove for prod
                // 'default_value' => 'Sed perspicatsum unde omnis',
            ],
            [
                'label' => __('Subtitle', 'flynt'),
                'name' => 'label',
                'type' => 'text',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            // [
            //     'label' => __('Description', 'flynt'),
            //     'name' => 'subtitle',
            //     'type' => 'textarea',
            //     'rows' => 3,
            //     'new_lines' => 'br',
            //     'required' => 0,
            //     // TODO remove for prod
            //     'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            // ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'subtitle',
                'type' => 'wysiwyg',
                'delay' => 0,
                'media_upload' => 0,
                'required' => 0,
            ],
            [
                'label' => __('CTA Button Primary', 'flynt'),
                'name' => 'cta',
                'type' => 'link',
                'return_format' => 'array',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
                // // TODO remove for prod
                // 'default_value' => [
                //     'url' => '/',
                //     'title' => 'Label',
                //     'target' => 'target'
                // ]
            ],

            // primary
            FieldVariables\getCTA(),
            // secondary
            FieldVariables\getCTA(
                'cta2',
                    [
                        'fieldPath' => 'layout',
                        'operator' => '==',
                        'value' => '1'
                    ],
            ),
            // Media type selector for Layout 2
            // Quick bit of research says name can be used multiple times as long as
            // conditional logic only shows one at a time - this doesn't work
            [
                'label' => __('Media Type', 'flynt'),
                'name' => 'mediaType',
                'type' => 'button_group',
                'layout' => 'horizontal',
                'choices' => [
                    'carousel' => __('Image/Carousel', 'flynt'),
                    'videoPlayer' => __('Video Player', 'flynt'),
                    'videoBackground' => __('Video Background', 'flynt'),
                ],
                'conditional_logic' => [
                    [
                      [
                        'fieldPath' => 'layout',
                        'operator' => '==',
                        'value' => '2',
                      ],
                    ],
                ],
                'default_value' => 'carousel'
//                'default_value' => 'image'
            ],
            // Media type selector for Layout 1
            // Mike - Client wants video for layout 1 also
            // Appears to be issues with reusing the mediaType name so as a hack
            // im renaming to mediaTypeAlt
            [
                'label' => __('Media Type', 'flynt'),
                'name' => 'mediaTypeAlt',
                'type' => 'button_group',
                'layout' => 'horizontal',
                'choices' => [
                    'image' => __('Image', 'flynt'),
                    'videoPlayer' => __('Video Player', 'flynt'),
                    'videoBackground' => __('Video Background', 'flynt'),
                ],
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'layout',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ],
                'default_value' => 'image'
            ],
            // Video
            [
                'label' => __('Video Background File', 'flynt'),
                'name' => 'videoFile',
                'type' => 'file',
                'return_format' => 'array',
                'required' => 0,
                'conditional_logic' => [
                    [
                      [
                        'fieldPath' => 'mediaType',
                        'operator' => '==',
                        'value' => 'videoBackground',
                      ],
                    ],
                    [
                        [
                            'fieldPath' => 'mediaTypeAlt',
                            'operator' => '==',
                            'value' => 'videoBackground'
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Video Cover Image', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended min size: 1272px x 1272px', 'flynt'),
                'name' => 'coverImage',
                'type' => 'image',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg,png,svg,webp',
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'mediaType',
                            'operator' => '==',
                            'value' => 'videoPlayer',
                        ],
                    ],
                    [
                        [
                            'fieldPath' => 'mediaTypeAlt',
                            'operator' => '==',
                            'value' => 'videoPlayer'
                        ],
                    ],
                ],
                // // TODO remove for prod
                // 'default_value' => 91
            ],
            [
                'label' => __('Play Button Text', 'flynt'),
                'name' => 'btnText',
                'instructions' => __('Optional text for the play button. If left empty then a play button with an icon will display.', 'flynt'),
                'type' => 'text',
                'default_value' => 'Take a virtual tour',
                'required' => 0,
                'conditional_logic' => [
                    [
                      [
                        'fieldPath' => 'mediaType',
                        'operator' => '==',
                        'value' => 'videoPlayer',
                      ],
                    ],
                    [
                        [
                            'fieldPath' => 'mediaTypeAlt',
                            'operator' => '==',
                            'value' => 'videoPlayer',
                        ],
                    ],
                  ],
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Video Player URL (YouTube / Vimeo)', 'flynt'),
                'name' => 'oembed',
                'type' => 'oembed',
                'required' => 1,
                'conditional_logic' => [
                  [
                    [
                      'fieldPath' => 'mediaType',
                      'operator' => '==',
                      'value' => 'videoPlayer',
                    ],
                  ],
                [
                    [
                        'fieldPath' => 'mediaTypeAlt',
                        'operator' => '==',
                        'value' => 'videoPlayer',
                    ],
                ],
                ],
            ],
            [
                'label' => __('Image Main', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended min size: 1272px x 1272px', 'flynt'),
                'name' => 'image',
                'type' => 'gallery',
                'min' => 1,
                'mime_types' => 'jpg,jpeg,png,webp',
                'required' => 1,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'mediaType',
                            'operator' => '==',
                            'value' => 'carousel'
                        ],

                    ],
                    [
                        [
                            'fieldPath' => 'layout',
                            'operator' => '==',
                            'value' => '1'
                        ],
                        // Mike
                        [
                            'fieldPath' => 'mediaTypeAlt',
                            'operator' => '==',
                            'value' => 'image'
                        ],
                    ],
                ],
                // TODO remove for prod
                // 'default_value' => 91
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
                'label' => __('Layout', 'flynt'),
                'name' => 'layout',
                'type' => 'button_group',
                // 'instructions' => 'Variant 1: Additional space around the content and image.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('Layout 1', 'flynt'),
                    '2' => __('Layout 2', 'flynt'),
                ],
                'default_value' => '1',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Background Colour', 'flynt'),
                'name' => 'bgColor',
                'type' => 'button_group',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    'white' => __('White', 'flynt'),
                    'pink' => __('Light Pink', 'flynt'),
                ],
                'default_value' => 'white',
                'wrapper' => [
                    'width' => 50
                ],
            ],
            [
                'label' => __('Padding', 'flynt'),
                'name' => 'padding',
                'type' => 'button_group',
                'instructions' => 'Optional extra space around the content and image.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    'default' => __('Default', 'flynt'),
                    'extra' => __('Extra', 'flynt'),
                ],
                'default_value' => 'default',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'layout',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ],
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
