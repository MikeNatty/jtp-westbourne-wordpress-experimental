<?php

namespace Flynt\Components\BlockDownloadList;

use Flynt\FieldVariables;
use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockDownloadList', function (array $data): array {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockDownloadList',
        'label' => __('Download List', 'flynt'),
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
                // TODO remove for prod
//                'default_value' => 'Small heading',
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
                'label' => __('Title', 'flynt'),
                'name' => 'title',
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 1,
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'delay' => 0,
                'media_upload' => 0,
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
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
            FieldVariables\getCTA(
                'cta',
                    [
                        'fieldPath' => 'variant',
                        'operator' => '==',
                        'value' => '2'
                    ],
            ),
            [
                'label' => __('CTA Prompt', 'flynt'),
                'name' => 'ctaPrompt',
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
            [
                'label' => __('Files', 'flynt'),
                'name' => 'files',
                'type' => 'repeater',
                'min' => 1,
                'max' => 20,
                'collapsed' => 'field_pageComponents_pageComponents_blockDownloadList_files_title',
                'layout' => 'row',
                'button_label' => __('Add File', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('File', 'flynt'),
                        'instructions' => __('Allowed formats: PDF, DOCX, ZIP, etc.', 'flynt'),
                        'name' => 'file',
                        'type' => 'file',
                        'required' => 0,
                        'mime_types' => 'pdf,doc,docx,zip'
                    ],
                    [
                        'label' => __('Label', 'flynt'),
                        'name' => 'label',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
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
                'instructions' => 'Variant 1: Blue background, Section heading, no CTA. <br>Variant 2: White background with CTA',
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


// Options::addTranslatable('blockHeading', [
//     [
//         'label' => __('Labels', 'flynt'),
//         'name' => 'labelsTab',
//         'type' => 'tab',
//         'placement' => 'top',
//         'endpoint' => 0
//     ],
// ]);
