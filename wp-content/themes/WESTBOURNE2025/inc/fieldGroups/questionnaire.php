<?php

const MAX_QUESTION_DEPTH = 5;


function generateQuestionFields(int $maxQuestionDepth = MAX_QUESTION_DEPTH): array
{
    // Start with the final level question fields
    $currentFields = [
        [
            'key' => 'question_option_final',
            'label' => __('Option', 'flynt'),
            'name' => 'question_option_final',
            'type' => 'text',
            'required' => 1,
            'wrapper' => ['width' => '68'],
        ],
        [
            'key' => 'field_final_page',
            'label' => __('Personalisation Page', 'flynt'),
            'name' => 'question_page_final',
            'type' => 'page_link',
            'required' => 1,
            'post_type' => ['personalisation'],
            'post_status' => ['publish'],
            'allow_archives' => 0,
        ],
    ];



    // Build nested structure from bottom up
    for ($level = $maxQuestionDepth; $level >= 1; $level--) {
        $isFirstQuestion = $level === 1;
        $isLastQuestion = $level === $maxQuestionDepth;

        // Add answers repeater with appropriate sub-fields
        $optionFields = [
            [
                'key' => "question_option_{$level}",
                'label' => __('Option', 'flynt'),
                'name' => "question_option",
                'type' => 'text',
                'required' => 1,
                'wrapper' => ['width' => '70'],
            ],
        ];


        if (!$isLastQuestion) {
            $singleSelectActionFields = [
                [
                    'key' => "question_{$level}_action",
                    'label' => 'Action',
                    'name' => "question_action",
                    'type' => 'button_group',
                    'required' => 1,
                    'wrapper' => ['width' => '30'],
                    'choices' => [
                        'continue' => 'Continue',
                        'end' => 'End',
                    ],
                    'conditional_logic' => [
                        [
                            [
                                'field' => "question_{$level}_is_multi_select",
                                'operator' => '==',
                                'value' => '0',
                            ],
                        ],
                    ],
                ],
                [
                    'key' => "question_{$level}_action_page",
                    'label' => __('Personalisation Page', 'flynt'),
                    'name' => "question_action_page",
                    'type' => 'page_link',
                    'required' => 1,
                    'conditional_logic' => [
                        [
                            [
                                'field' => "question_{$level}_action",
                                'operator' => '==',
                                'value' => 'end',
                            ],
                        ],
                    ],
                    'post_type' => ['personalisation'],
                    'post_status' => ['publish'],
                    'allow_archives' => 0,
                ],
                [
                    'key' => "question_{$level}_action_next",
                    'label' => __("", 'flynt'),
                    'name' => "question_action_next",
                    'type' => 'group',
                    'conditional_logic' => [
                        [
                            [
                                'field' => "question_{$level}_action",
                                'operator' => '==',
                                'value' => 'continue',
                            ],
                        ],
                    ],
                    'sub_fields' => $currentFields,
                ],
            ];
            $optionFields = array_merge($optionFields, $singleSelectActionFields);
        }

        // Create question block group
        $questionFields = [
            [
                'key' => "question_{$level}_title",
                'label' => __("Question {$level} - Title", 'flynt'),
                'name' => "question_title",
                'type' => 'text',
                'required' => 1,
                'wrapper' => [
                    'width' => '70',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'key' => "question_{$level}_is_multi_select",
                'label' => __('Is Multi-Select?', 'flynt'),
                'name' => "question_is_multi_select",
                'type' => 'true_false',
                'ui' => 1,
                'wrapper' => [
                    'width' => '30',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'key' => "question_{$level}_description",
                'label' => __("Question {$level} - Description", 'flynt'),
                'name' => "question_description",
                'type' => 'text',
                'required' => 0,
                'instructions' => '(Optional)',
                'placeholder' => 'Question description or instructions',
            ],
            // Move options repeater into sub_fields
            [
                'key' => "question_{$level}_options",
                'label' => __("Question {$level} Options ", 'flynt'),
                'name' => "options",
                'type' => 'repeater',
                'layout' => 'block',
                'min' => 1,
                'max' => 6,
                'collapsed' => "question_option_{$level}",
                'button_label' => __("Add Q{$level} Option", 'flynt'),
                'sub_fields' => $optionFields,
            ],
        ];

        $optionBulkActionFields = [];

        if (!$isLastQuestion) {
            $optionBulkActionFields = [
                [
                    'key' => "question_{$level}_bulk_action",
                    'label' => "Question {$level} Action",
                    'name' => "question_bulk_action",
                    'type' => 'button_group',
                    'required' => 1,
                    'wrapper' => ['width' => '100'],
                    'choices' => [
                        'continue' => 'Continue',
                        'end' => 'End',
                    ],
                    'conditional_logic' => [
                        [
                            [
                                'field' => "question_{$level}_is_multi_select",
                                'operator' => '==',
                                'value' => '1',
                            ],
                        ],
                    ]
                ],
                [
                    'key' => "question_{$level}_bulk_action_page",
                    'label' => __('Personalisation Page', 'flynt'),
                    'name' => "question_bulk_action_page",
                    'type' => 'page_link',
                    'required' => 1,
                    'conditional_logic' => [
                        [
                            [
                                'field' => "question_{$level}_is_multi_select",
                                'operator' => '==',
                                'value' => '1',
                            ],
                            [
                                'field' => "question_{$level}_bulk_action",
                                'operator' => '==',
                                'value' => 'end',
                            ],
                        ],
                    ],
                    'post_type' => ['personalisation'],
                    'post_status' => ['publish'],
                    'allow_archives' => 0,
                ],
                [
                    'key' => "question_{$level}_bulk_action_next",
                    'label' => __("", 'flynt'),
                    'name' => "question_bulk_action_next",
                    'type' => 'group',
                    'conditional_logic' => [
                        [
                            [
                                'field' => "question_{$level}_is_multi_select",
                                'operator' => '==',
                                'value' => '1',
                            ],
                            [
                                'field' => "question_{$level}_bulk_action",
                                'operator' => '==',
                                'value' => 'continue',
                            ],
                        ],
                    ],
                    'sub_fields' => $currentFields,
                ]
            ];
        } else {
            $optionBulkActionFields = [
                [
                    'key' => "question_{$level}_action_page_multi_select",
                    'label' => __('Personalisation Page', 'flynt'),
                    'name' => "question_bulk_action_page",
                    'type' => 'page_link',
                    'required' => 1,
                    'post_type' => ['personalisation'],
                    'post_status' => ['publish'],
                    'allow_archives' => 0,
                ],
            ];
        }
        $questionFields = array_merge($questionFields, $optionBulkActionFields);


        // Store current level fields for next iteration
        $currentFields = $questionFields;
    }

    return $currentFields;
}

add_action('acf/include_fields', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_67a3fa369e05f',
        'title' => 'Questionnaire',
        'fields' => [
            $questionBlock = [
                'key' => 'question_entry',
                'label' => __("Question 1", 'flynt'),
                'name' => 'question_entry',
                'type' => 'group',
                'style' => 'seamless',
                'sub_fields' =>  generateQuestionFields(MAX_QUESTION_DEPTH),
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'personalisation',
                ]
            ]
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 1,
    ]);
});

add_action('acf/init', function () {
    acf_add_options_page([
        'page_title' => 'Questionnaire',
        'menu_slug' => 'personalisation',
        'menu_title' => 'Questionnaire',
        'position' => '',
        'redirect' => false,
        'icon_url' => 'dashicons-forms',
        'updated_message' => 'Questionnaire Updated',
        'parent_slug' => 'edit.php?post_type=personalisation',
    ]);
});
