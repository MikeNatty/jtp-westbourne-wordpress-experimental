<?php

namespace Flynt\Components\BlockCallout;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockCallout', function ($data) {
    return $data;
});

/**
* Custom validation to limit the number of quotes based on the selected variant.
*/
//add_filter('acf/validate_value/name=quotes', function ($valid, $value, $field, $input) {
//    $variant = isset($_POST['acf']['field_pageComponents_pageComponents_blockCallout_variant']) ? $_POST['acf']['field_pageComponents_pageComponents_blockCallout_variant'] : null;
//    $max = ($variant === '2') ? 10 : 1;
//    if (is_array($value) && count($value) > $max) {
//        return sprintf('You can only add up to %d quotes for this variant.', $max);
//    }
//    return $valid;
//}, 10, 4);

function getQuoteFields( $additionalFields = [])
{
    return array_merge( [
        [
            'label' => 'Quote Text',
            'name' => 'quote',
            'type' => 'textarea',
            'new_lines' => 'br',
            'required' => 0,
            'rows' => 4,
            // TODO remove for prod
            'default_value' => 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam',
        ],
        [
            'label' => 'Author Name',
            'name' => 'authorName',
            'type' => 'text',
            'required' => 0,
            // TODO remove for prod
            'default_value' => 'Helen Maning',
        ],
        [
            'label' => 'Author Position',
            'name' => 'authorPosition',
            'type' => 'text',
            'required' => 0,
            // TODO remove for prod
            'default_value' => '',
        ]
    ],
    $additionalFields );
}

function getACFLayout()
{
    return [
        'name' => 'blockCallout',
        'label' => 'Callout / Quote',
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Section Heading', 'flynt'),
                'name' => 'sectionHeading',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ],
                ]

            ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'rows' => 3,
                'new_lines' => 'br',
                'required' => 0,
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ],
                ]
            ],
            // TODO :: group for single quote
            [
                'label' => 'Quote',
                'name' => 'quoteGroup',
                'type' => 'group',
                'sub_fields' => getQuoteFields(),
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ]
            ],

            [
                'label' => __('Quotes', 'flynt'),
                'name' => 'quotes',
                'type' => 'repeater',
                'min' => 1,
                'max' => 10,
                'collapsed' => 'field_pageComponents_pageComponents_blockCallout_quotes_title',
                'layout' => 'row',
                'button_label' => __('Add Quote', 'flynt'),
                'sub_fields' => getQuoteFields([
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended size: 240px x 240px', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'required' => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'variant',
                                    'operator' => '==',
                                    'value' => '2',
                                ],
                            ],
                        ]
                    ],
                ]),
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
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
                'label' => __('Variant', 'flynt'),
                'name' => 'variant',
                'type' => 'radio',
                'instructions' => 'Variant 1: Full-width single quote.<br>Variant 2: Heading, description and Quote Carousel. ',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('Variant 1', 'flynt'),
                    '2' => __('Variant 2', 'flynt'),
                ],
                'default_value' => '1'
            ],
        ]
    ];

}
