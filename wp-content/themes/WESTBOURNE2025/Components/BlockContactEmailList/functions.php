<?php

namespace Flynt\Components\BlockContactEmailList;

use Flynt\FieldVariables;
use Timber\Timber;
use Flynt\Utils\Options;


add_filter('Flynt/addComponentData?name=BlockContactEmailList', function (array $data): array {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockContactEmailList',
        'label' => __('Contact Email List', 'flynt'),
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
            ],
            [
                'label' => __('Phone / Subtitle', 'flynt'),
                'name' => 'subtitle',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'label' => __('Section', 'flynt'),
                'name' => 'sections',
                'type' => 'repeater',
                'min' => 1,
                'max' => 10,
                'collapsed' => 'field_pageComponents_pageComponents_blockContactEmailList_sections_title',

                'layout' => 'row',
                'button_label' => __('Add Section', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Section name', 'flynt'),
                        'name' => 'sectionName',
                        'type' => 'text',
                        'required' => 0,
                    ],
                    [
                        'label' => __('Section', 'flynt'),
                        'name' => 'contacts',
                        'type' => 'repeater',
                        'min' => 1,
                        'max' => 20,
                        'collapsed' => 'field_pageComponents_pageComponents_blockContactEmailList_sections_contacts_sectionName',

                        'layout' => 'row',
                        'button_label' => __('Add Contact', 'flynt'),

                        'sub_fields' => [
                            [
                                'label' => __('Contact name', 'flynt'),
                                'name' => 'contactName',
                                'type' => 'text',
                                'placeholder' => '',
                                'required' => 0,
                            ],
                            [
                                'label' => __('Contact title', 'flynt'),
                                'name' => 'contactTitle',
                                'type' => 'text',
                                'placeholder' => '',
                                'required' => 0,
                            ],
                            [
                                'label' => __('Contact email', 'flynt'),
                                'name' => 'contactEmail',
                                'type' => 'text',
                                'placeholder' => '',
                                'required' => 0,
                            ],
                        ]
                    ]
                ]
            ],

            FieldVariables\getAnchorOptions(),

        ]
    ];
}
