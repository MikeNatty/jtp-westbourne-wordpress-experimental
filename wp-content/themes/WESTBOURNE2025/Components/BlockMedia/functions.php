<?php

namespace Flynt\Components\BlockMedia;

use Flynt\FieldVariables;
use Flynt\Utils\Oembed;

add_filter('Flynt/addComponentData?name=BlockMedia', function ($data) {
    $data['uuid'] = $data['uuid'] ?? wp_generate_uuid4();

    if ($data['variant'] == 'videoPlayer') {
        $data['oembed'] = Oembed::setSrcAsDataAttribute(
            $data['oembed'],
            [
                'autoplay' => 'true',
                'rel' => 0
            ]
        );
    }

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockMedia',
        'label' => __('Media Full Width', 'flynt'),
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Heading', 'flynt'),
                'name' => 'heading',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                // TODO remove for prod
                'default_value' => 'Sed perspicatsum unde omnis',
            ],
            [
                'label' => __('Video Player URL (YouTube / Vimeo)', 'flynt'),
                'name' => 'oembed',
                'type' => 'oembed',
                'required' => 1,
                'conditional_logic' => [
                  [
                    [
                      'fieldPath' => 'variant',
                      'operator' => '==',
                      'value' => 'videoPlayer',
                    ],
                  ],
                ],
            ],
            [
                'label' => __('Video Background File', 'flynt'),
                'name' => 'videoFile',
                'type' => 'file',
                'return_format' => 'array',
                // 'type' => '',
                'required' => 0,
                'conditional_logic' => [
                    [
                      [
                        'fieldPath' => 'variant',
                        'operator' => '==',
                        'value' => 'videoBackground',
                      ],
                    ],
                ],
            ],
            [
                'label' => __('Gallery', 'flynt'),
                'name' => 'gallery',
                'type' => 'gallery',
                'min' => 1,
                // 'preview_size' => 'medium',
                // 'mime_types' => 'image',
                'instructions' => __('Add one image for single image display, multiple images for carousel', 'flynt'),
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => 'carousel',
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
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => 'videoPlayer',
                        ],
                    ],
                ],
                // TODO remove for prod
                'default_value' => 91
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
                'label' => __('Image/Video captions', 'flynt'),
                'name' => 'showCaptions',
                'instructions' => 'If a caption for the image or video exists, it will be displayed below the media.',
                'type' => 'true_false',
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => __('Show', 'flynt'),
                'ui_off_text' => __('Hide', 'flynt'),
            ],
            [
                'label' => __('Variant', 'flynt'),
                'name' => 'variant',
                'type' => 'radio',
                // 'instructions' => 'Image/Carousel: <br>Video: ',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    'carousel' => __('Image/Carousel', 'flynt'),
                    'videoPlayer' => __('Video Player', 'flynt'),
                    'videoBackground' => __('Video Background', 'flynt'),
                ],
                'default_value' => 'carousel'
            ],
            [
                'label' => 'Carousel Options',
                'name' => 'carouselOptions',
                'type' => 'group',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => 'carousel'
                        ]
                    ]
                ],
                'sub_fields' => [
                    // [
                    //     'label' => __('Carousel Buttons', 'flynt'),
                    //     'name' => 'showButtons',
                    //     'type' => 'true_false',
                    //     'default_value' => 1,
                    //     'ui' => 1,
                    //     'ui_on_text' => __('Show', 'flynt'),
                    //     'ui_off_text' => __('Hide', 'flynt'),
                    // ],
                    [
                        'label' => __('Thumbnail Navigation', 'flynt'),
                        'name' => 'showThumbnails',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'ui_on_text' => __('Show', 'flynt'),
                        'ui_off_text' => __('Hide', 'flynt'),
                    ],
                    [
                        'label' => __('Enable Autoplay', 'flynt'),
                        'name' => 'autoplay',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1
                    ],
                    [
                        'label' => __('Autoplay Speed (in milliseconds)', 'flynt'),
                        'name' => 'autoplaySpeed',
                        'type' => 'number',
                        'min' => 1000,
                        'step' => 1,
                        'default_value' => 4000,
                        'required' => 1,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'autoplay',
                                    'operator' => '==',
                                    'value' => 1
                                ]
                            ]
                        ],
                    ],
                ]
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
