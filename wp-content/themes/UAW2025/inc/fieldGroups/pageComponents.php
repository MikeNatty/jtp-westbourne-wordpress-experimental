<?php

use ACFComposer\ACFComposer;
use Flynt\Components;
use Flynt\FieldVariables;

add_action('Flynt/afterRegisterComponents', function (): void {
    ACFComposer::registerFieldGroup([
        'name' => 'pageComponents',
        'title' => __('Page Components', 'flynt'),
        'style' => 'seamless',
        'menu_order' => 1,
        'fields' => [
            [
                'name' => 'pageComponents',
                'label' => __('Page Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAccordion\getACFLayout(),
                    Components\BlockCardCarousel\getACFLayout(),
                    Components\BlockCardGrid\getACFLayout(),
                    Components\BlockChecklist\getACFLayout(),
                    Components\BlockContactCard\getACFLayout(),
                    Components\BlockContentCarousel\getACFLayout(),
                    Components\BlockContentMedia\getACFLayout(),
                    Components\blockContentFullWidth\getACFLayout(),
                    Components\BlockForm\getACFLayout(),
                    Components\BlockHeading\getACFLayout(),
                    Components\BlockHero\getACFLayout(),
                    Components\BlockLocationMap\getACFLayout(),
                    Components\BlockServiceChecker\getACFLayout(),
                    Components\BlockMedia\getACFLayout(),
                    Components\BlockPersonalisationLauncher\getACFLayout(),
                    Components\BlockSectionIntro\getACFLayout(),
                    Components\BlockSearchResults\getACFLayout(),
                    Components\BlockTable\getACFLayout(),
                    Components\BlockVirtualTour\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\BlockQuote\getACFLayout(),

                    // Mike Test Components
//                    Components\BlockTestComponent\getACFLayout(),

                    // remove for prod
                    Components\BlockChildPages\getACFLayout(),
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page'
                ]
            ],
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'location'
                ]
            ],
        ],
    ]);
});



add_action('Flynt/afterRegisterComponents', function () {
    ACFComposer::registerFieldGroup([
        'name' => 'AnchorOpitons',
        'title' => 'Anchor Options',
        'style' => 'seamless',
        'menu_order' => 2,
        'fields' => [
            // [
            //     'label' => __('Enable Bar', 'flynt'),
            //     'name' => 'testEnable',
            //     'type' => 'true_false',
            //     'default_value' => 0,
            //     'ui' => 1,
            //     'ui_on_text' => __('Enable', 'flynt'),
            //     'ui_off_text' => __('Disable', 'flynt'),
            // ],
            [
                'name' => 'accordionStart',
                'label' => 'Options',
                'type' => 'accordion',
                'open' => 0,
                'multi_expand' => 0,
                'endpoint' => 0,
            ],
            [
                'label' => 'Anchor Link Bar',
                'name' => 'anchorLink',
                'type' => 'group',
                'sub_fields' => [
                    [
                        'label' => __('Enable Bar', 'flynt'),
                        'name' => 'enable',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => __('Enable', 'flynt'),
                        'ui_off_text' => __('Disable', 'flynt'),
                    ],
                    FieldVariables\getCTA(
                        'cta',
                        [
                            'fieldPath' => 'enable',
                            'operator' => '==',
                            'value' => 1
                        ],
                    ),
                    // [
                    //     'label' => __('CTA Button', 'flynt'),
                    //     'name' => 'cta',
                    //     'type' => 'link',
                    //     'return_format' => 'array',
                    //     'required' => 0,
                    //     // TODO remove for prod
                    //     'default_value' => [
                    //         'url' => '/',
                    //         'title' => 'Enquire now',
                    //         'target' => 'target'
                    //     ],
                    //     'conditional_logic' => [
                    //         [
                    //             [
                    //                 'fieldPath' => 'enable',
                    //                 'operator' => '==',
                    //                 'value' => 1
                    //             ],
                    //         ],
                    //     ],
                    //     'wrapper' => [
                    //         'width' => '50',
                    //     ],
                    // ],
                    [
                        'label' => __('Downloads', 'flynt'),
                        'name' => 'downloads',
                        'type' => 'repeater',
                        'min' => 0,
                        'layout' => 'table',
                        'button_label' => __('Add File', 'flynt'),
                        'sub_fields' => [
                            [
                                'label' => __('Downloadable File', 'flynt'),
                                'name' => 'file',
                                'type' => 'file',
                                'return_format' => 'array',
                                'required' => 0,
                            ],
                        ],
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'enable',
                                    'operator' => '==',
                                    'value' => 1
                                ],
                            ],
                        ],
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'accordionEnd',
                'label' => '',
                'type' => 'accordion',
                'endpoint' => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page'
                ]
            ],
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'location'
                ]
            ],
        ]
    ]);
});
