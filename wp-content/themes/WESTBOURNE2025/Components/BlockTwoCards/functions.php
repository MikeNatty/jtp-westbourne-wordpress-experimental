<?php

namespace Flynt\Components\BlockTwoCards;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockTwoCards', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockTwoCards',
        'label' => __('Two Cards Layout', 'flynt'),
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
            ],
            [
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 'Lorem dolorisum',
            ],
            [
                'label' => __('Sub Title', 'flynt'),
                'name' => 'subtitle',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],

            FieldVariables\getCTA(),
            [
                'label' => 'Cards',
                'name' => 'cardItems',
                'type' => 'group',
                'sub_fields' => [
                    ...FieldVariables\getCardFields('card1', 'Card 1',
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ]),
                    ...FieldVariables\getCardFields('card2', 'Card 2',
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ]),
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
                'label' => __('Breadcrumbs', 'flynt'),
                'name' => 'showBreadcrumbs',
                'instructions' => 'Show breadcrumbs above title. Only displays when a parent page exists.',
                'type' => 'true_false',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => __('Show', 'flynt'),
                'ui_off_text' => __('Hide', 'flynt'),
            ],
            [
                'label' => __('Variant', 'flynt'),
                'name' => 'variant',
                'type' => 'radio',
                'instructions' => 'Variant 1: Text-only cards.<br>Variant 2: Text and Image cards. ',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('Variant 1', 'flynt'),
                    '2' => __('Variant 2', 'flynt'),
                ],
                'default_value' => '1'
            ],
//            FieldVariables\getPersonalisation(),
        ]
    ];
}
