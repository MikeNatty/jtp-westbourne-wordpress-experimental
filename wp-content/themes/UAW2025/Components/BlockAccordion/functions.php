<?php

namespace Flynt\Components\BlockAccordion;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockAccordion', function ($data) {
    $data['uuid'] = $data['uuid'] ?? wp_generate_uuid4();
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockAccordion',
        'label' => __('Accordion', 'flynt'),
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
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
                // // TODO remove for prod
                // 'default_value' => 'Lorem sed unde omnis',
            ],
            [
                'label' => __('Section Heading', 'flynt'),
                'name' => 'sectionHeading',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
                // // TODO remove for prod
                // 'default_value' => 'Lorem sed unde omnis',
            ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
                // // TODO remove for prod
                // 'default_value' => 'Lorem sed unde omnis',
            ],
            FieldVariables\getCTA(),
            [
                'label' => __('Panels', 'flynt'),
                'name' => 'panels',
                'type' => 'repeater',
                'min' => 1,
                'max' => 20,
                'collapsed' => 'field_pageComponents_pageComponents_blockAccordion_panels_heading',
                'layout' => 'row',
                'button_label' => __('Add Item', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Heading', 'flynt'),
                        'name' => 'heading',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem ipsum',
                    ],
                    [
                        'label' => __('Icon Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended size: 240px x 240px', 'flynt'),
                        'name' => 'iconImage',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => '../variant',
                                    'operator' => '==',
                                    'value' => '1',
                                ],
                            ],
                        ],
                        'required' => 0,
                        // TODO remove for prod
                        'default_value' => 676
                    ],
                    [
                        'label' => __('Content', 'flynt'),
                        'name' => 'contentHtml',
                        'type' => 'wysiwyg',
                        'delay' => 0,
                        'media_upload' => 0,
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'AgeWell connect recognises the importance of maintaining these social connections, or helping to establish new ones, offering trusted services, information and transport.',
                    ],
                    FieldVariables\getCTA(
                        'cta',
                        [
                            'fieldPath' => '../variant',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ),
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended size: 1674px x 942px', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => '../variant',
                                    'operator' => '==',
                                    'value' => '1',
                                ],
                            ],
                        ],
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 91
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
            FieldVariables\getAnchorOptions(),
            [
                'label' => __('Variant', 'flynt'),
                'name' => 'variant',
                'type' => 'button_group',
                'instructions' => 'Variant 1: Larger cards with the option for an icon and image within the accordion. Shows 6 cards before a load more button.<br>Variant 2: Smaller cards with only a heading and content. Shows 10 cards before a load more button.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('Variant 1', 'flynt'),
                    '2' => __('Variant 2', 'flynt'),
                ],
                'default_value' => '1'
            ],
            [
                'label' => 'Load More Button Text',
                'name' => 'loadMoreText',
                'type' => 'text',
                'default_value' => 'Load More',
                'required' => 1,
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
