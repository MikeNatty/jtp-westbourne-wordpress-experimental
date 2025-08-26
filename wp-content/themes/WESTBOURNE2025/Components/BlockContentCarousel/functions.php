<?php

namespace Flynt\Components\BlockContentCarousel;

use Flynt\FieldVariables;
use Timber\Timber;

const POST_TYPE = 'contenthub';

add_filter('Flynt/addComponentData?name=BlockContentCarousel', function ($data) {
    $data['uuid'] = $data['uuid'] ?? wp_generate_uuid4();
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockContentCarousel',
        'label' => 'Content + Media (Carousel)',
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
                // // TODO remove for prod
                // 'default_value' => 'Lorem sed unde omnis',
            ],
            FieldVariables\getCTA(),
            [
                'label' => __('Carousel', 'flynt'),
                'name' => 'cards',
                'type' => 'repeater',
                'min' => 1,
                'max' => 20,
                'collapsed' => 'field_pageComponents_pageComponents_blockContentCarousel_cards_title',
                'layout' => 'row',
                'button_label' => __('Add Item', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended size: 240px x 240px', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'required' => 0,
                        // TODO remove for prod
                        'default_value' => 91
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem ipsum',
                    ],
                    [
                        'label' => __('Description', 'flynt'),
                        'name' => 'description',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem ipsum',
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended size: 1674px x 942px', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'required' => 0,
                        'required' => 0,
                        // TODO remove for prod
                        'default_value' => 91
                    ],
                ]
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
                'name' => 'variant',
                'type' => 'radio',
                // 'instructions' => 'Variant 1: Large single image and bottom aligned content. <br>Variant 2: Additional small image and top aligned content.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('50/50', 'flynt'),
                    '2' => __('30/70 - Wider Image', 'flynt'),
                ],
                'default_value' => '1'
            ],
            [
                'label' => __('Layout', 'flynt'),
                'name' => 'variant',
                'type' => 'radio',
                // 'instructions' => 'Variant 1: Large single image and bottom aligned content. <br>Variant 2: Additional small image and top aligned content.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('50/50', 'flynt'),
                    '2' => __('30/70 - Wider Image', 'flynt'),
                ],
                'default_value' => '1'
            ],
            [
                'label' => __('Text Card Colour', 'flynt'),
                'name' => 'bgColor',
                'type' => 'radio',
                // 'instructions' => 'Variant 1: Large single image and bottom aligned content. <br>Variant 2: Additional small image and top aligned content.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    'white' => __('White', 'flynt'),
                    'pink' => __('Light Pink', 'flynt'),
                ],
                'default_value' => 'white'
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
