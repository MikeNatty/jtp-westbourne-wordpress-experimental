<?php

namespace Flynt\Components\NavigationFooter;

use Flynt\Utils\Options;
use Timber\Timber;
use Flynt\Utils\Asset;
use Flynt\FieldVariables;

add_action('init', function (): void {
    register_nav_menus([
        'navigation_footer' => __('Navigation Footer', 'flynt'),
        'navigation_footer_legal' => __('Navigation Footer Legal', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationFooter', function (array $data): array {
    $data['menu'] = Timber::get_menu('navigation_footer') ?? Timber::get_pages_menu();
    $data['menuLegal'] = Timber::get_menu('navigation_footer_legal') ?? Timber::get_pages_menu();
    $data['logo'] = [
        'src' => Asset::requireUrl('assets/images/logo.svg'),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});

Options::addTranslatable('NavigationFooter', [
    [
        'label' => __('Content', 'flynt'),
        'name' => 'contentTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => 'Questions Card',
        'name' => 'questionsCard',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Text',
                'name' => 'text',
                'type' => 'text',
                'default_value' => 'Have questions? We’ve gathered answers to the most common queries to help you find the information you need quickly and easily.',
            ],
            FieldVariables\getCTA(),
        ]
    ],
    [
        'label' => 'Card A',
        'name' => 'cardA',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'default_value' => 'Begin Your Journey at Westbourne',
            ],
             [
                'label' => 'Text',
                'name' => 'text',
                'type' => 'text',
                'default_value' => 'We are excited to announce we have been awarded the Educator‘s 5-Star Employer of Choice Award 2024.',
            ],
            FieldVariables\getCTA(),
        ]
    ],
    [
        'label' => 'Card B',
        'name' => 'cardB',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'default_value' => 'Book a tour and see Westbourne in action',
            ],
             [
                'label' => 'Text',
                'name' => 'text',
                'type' => 'text',
                'default_value' => 'We are excited to announce we have been awarded the Educator‘s 5-Star Employer of Choice Award 2024.',
            ],
            FieldVariables\getCTA(),
        ]
    ],
    [
        'label' => 'Social Links',
        'name' => 'socialLinks',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => __('LinkedIn', 'flynt'),
                'name' => 'linkedin',
                'type' => 'url',
            ],
            [
                'label' => __('Facebook', 'flynt'),
                'name' => 'facebook',
                'type' => 'url',
            ],
            [
                'label' => __('Instagram', 'flynt'),
                'name' => 'instagram',
                'type' => 'url',
            ],
        ]
    ],
    [
        'label' => 'Copyright Text',
        'name' => 'copyright',
        'type' => 'text',
        'default_value' => 'All rights reserved. © 2021 Westbourne Grammar School',
    ],
    [
        'label' => 'Link 1',
        'name' => 'link1',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Link Text',
                'name' => 'linkText',
                'type' => 'text',
                'default_value' => 'CRICOS Provider No.00355F',
            ],
             [
                'label' => 'URL',
                'name' => 'link',
                'type' => 'text',
                'default_value' => '#',
            ],
        ]
    ],
    [
        'label' => 'Link 2',
        'name' => 'link2',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Link Text',
                'name' => 'linkText',
                'type' => 'text',
                'default_value' => 'Privacy Policy',
            ],
             [
                'label' => 'URL',
                'name' => 'link',
                'type' => 'text',
                'default_value' => '#',
            ],
        ]
    ],
    [
        'label' => 'Link 3',
        'name' => 'link3',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Link Text',
                'name' => 'linkText',
                'type' => 'text',
                'default_value' => 'Child Safe School',
            ],
             [
                'label' => 'URL',
                'name' => 'link',
                'type' => 'text',
                'default_value' => '#',
            ],
        ]
    ],
     [
        'label' => 'Link 4',
        'name' => 'link4',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Link Text',
                'name' => 'linkText',
                'type' => 'text',
                'default_value' => 'National Redress Scheme',
            ],
             [
                'label' => 'URL',
                'name' => 'link',
                'type' => 'text',
                'default_value' => '#',
            ],
        ]
    ],
    [
        'label' => __('Labels', 'flynt'),
        'name' => 'labelsTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => '',
        'name' => 'labels',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => __('Navigation Aria Label', 'flynt'),
                'name' => 'ariaLabel',
                'type' => 'text',
                'default_value' => __('Footer Navigation', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Social Links Aria Label', 'flynt'),
                'name' => 'socialAriaLabel',
                'type' => 'text',
                'default_value' => __('Social Links', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'label' => __('Legal Link Aria Label', 'flynt'),
                'name' => 'legalAriaLabel',
                'type' => 'text',
                'default_value' => __('Legal Links', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
        ],
    ],

]);

add_action('graphql_register_types', function() {
    // CTA object type (used in multiple cards)
    register_graphql_object_type('CTA', [
        'description' => __('Call to Action', 'flynt'),
        'fields' => [
            'title' => [
                'type' => 'String',
                'description' => __('CTA label', 'flynt'),
            ],
            'url' => [
                'type' => 'String',
                'description' => __('CTA URL', 'flynt'),
            ],
            'target' => [
                'type' => 'String',
                'description' => __('CTA target', 'flynt'),
            ],
        ],
    ]);

    // QuestionsCard group
    register_graphql_object_type('QuestionsCard', [
        'description' => __('Questions Card group', 'flynt'),
        'fields' => [
            'text' => [
                'type' => 'String',
                'description' => __('Questions Card text', 'flynt'),
            ],
            'cta' => [
                'type' => 'CTA',
                'description' => __('CTA', 'flynt'),
            ],
        ],
    ]);

    // CardA and CardB group
    register_graphql_object_type('Card', [
        'description' => __('Card group', 'flynt'),
        'fields' => [
            'title' => [
                'type' => 'String',
                'description' => __('Card title', 'flynt'),
            ],
            'text' => [
                'type' => 'String',
                'description' => __('Card text', 'flynt'),
            ],
            'cta' => [
                'type' => 'CTA',
                'description' => __('CTA', 'flynt'),
            ],
        ],
    ]);

    // SocialLinks group
    register_graphql_object_type('SocialLinks', [
        'description' => __('Social Links group', 'flynt'),
        'fields' => [
            'linkedin' => [
                'type' => 'String',
                'description' => __('LinkedIn URL', 'flynt'),
            ],
            'facebook' => [
                'type' => 'String',
                'description' => __('Facebook URL', 'flynt'),
            ],
            'instagram' => [
                'type' => 'String',
                'description' => __('Instagram URL', 'flynt'),
            ],
        ],
    ]);

    // Link group (used for link1-link4)
    register_graphql_object_type('FooterLink', [
        'description' => __('Footer Link group', 'flynt'),
        'fields' => [
            'linkText' => [
                'type' => 'String',
                'description' => __('Link text', 'flynt'),
            ],
            'link' => [
                'type' => 'String',
                'description' => __('Link URL', 'flynt'),
            ],
        ],
    ]);

    // Labels group
    register_graphql_object_type('FooterLabels', [
        'description' => __('Footer Labels group', 'flynt'),
        'fields' => [
            'ariaLabel' => [
                'type' => 'String',
                'description' => __('Navigation Aria Label', 'flynt'),
            ],
            'socialAriaLabel' => [
                'type' => 'String',
                'description' => __('Social Links Aria Label', 'flynt'),
            ],
            'legalAriaLabel' => [
                'type' => 'String',
                'description' => __('Legal Link Aria Label', 'flynt'),
            ],
        ],
    ]);

    // Main NavigationFooter type
    register_graphql_object_type('NavigationFooter', [
        'description' => __('Navigation Footer options', 'flynt'),
        'fields' => [
            'questionsCard' => [
                'type' => 'QuestionsCard',
                'description' => __('Questions Card group', 'flynt'),
            ],
            'cardA' => [
                'type' => 'Card',
                'description' => __('Card A group', 'flynt'),
            ],
            'cardB' => [
                'type' => 'Card',
                'description' => __('Card B group', 'flynt'),
            ],
            'copyright' => [
                'type' => 'String',
                'description' => __('Copyright text', 'flynt'),
            ],
            'link1' => [
                'type' => 'FooterLink',
                'description' => __('Link 1 group', 'flynt'),
            ],
            'link2' => [
                'type' => 'FooterLink',
                'description' => __('Link 2 group', 'flynt'),
            ],
            'link3' => [
                'type' => 'FooterLink',
                'description' => __('Link 3 group', 'flynt'),
            ],
            'link4' => [
                'type' => 'FooterLink',
                'description' => __('Link 4 group', 'flynt'),
            ],
            'socialLinks' => [
                'type' => 'SocialLinks',
                'description' => __('Social links group', 'flynt'),
            ],
            'labels' => [
                'type' => 'FooterLabels',
                'description' => __('Labels group', 'flynt'),
            ],
        ],
    ]);

    register_graphql_field('RootQuery', 'navigationFooter', [
        'type' => 'NavigationFooter',
        'description' => __('Navigation Footer options', 'flynt'),
        'resolve' => function() {
    return [
        'questionsCard' => [
            'text' => get_option('options_translatable_NavigationFooter_questionsCard_text'),
            'cta' => maybe_unserialize(get_option('options_translatable_NavigationFooter_questionsCard_cta_link')),
//            'cta' => [
//                'label' => get_option('options_translatable_NavigationFooter_questionsCard_cta_label'),
//                'url' => maybe_unserialize( get_option('options_translatable_NavigationFooter_questionsCard_cta_link') ),
//                'target' => get_option('options_translatable_NavigationFooter_questionsCard_cta_target'),
//            ],
        ],
        'cardA' => [
            'title' => get_option('options_translatable_NavigationFooter_cardA_title'),
            'text' => get_option('options_translatable_NavigationFooter_cardA_text'),
            'cta' => maybe_unserialize(get_option('options_translatable_NavigationFooter_cardA_cta_link')),
//            'cta' => [
//                'label' => get_option('options_translatable_NavigationFooter_cardA_cta_label'),
//                'url' => get_option('options_translatable_NavigationFooter_cardA_cta_url'),
//                'target' => get_option('options_translatable_NavigationFooter_cardA_cta_target'),
//            ],
        ],
        'cardB' => [
            'title' => get_option('options_translatable_NavigationFooter_cardB_title'),
            'text' => get_option('options_translatable_NavigationFooter_cardB_text'),
            'cta' => maybe_unserialize(get_option('options_translatable_NavigationFooter_cardB_cta_link')),
//            'cta' => [
//                'label' => get_option('options_translatable_NavigationFooter_cardB_cta_label'),
//                'url' => get_option('options_translatable_NavigationFooter_cardB_cta_url'),
//                'target' => get_option('options_translatable_NavigationFooter_cardB_cta_target'),
//            ],
        ],
        'link1' => [
            'linkText' => get_option('options_translatable_NavigationFooter_link1_linkText'),
            'link' => get_option('options_translatable_NavigationFooter_link1_link'),
        ],
        'link2' => [
            'linkText' => get_option('options_translatable_NavigationFooter_link2_linkText'),
            'link' => get_option('options_translatable_NavigationFooter_link2_link'),
        ],
        'link3' => [
            'linkText' => get_option('options_translatable_NavigationFooter_link3_linkText'),
            'link' => get_option('options_translatable_NavigationFooter_link3_link'),
        ],
        'link4' => [
            'linkText' => get_option('options_translatable_NavigationFooter_link4_linkText'),
            'link' => get_option('options_translatable_NavigationFooter_link4_link'),
        ],
        'socialLinks' => [
            'linkedin' => get_option('options_translatable_NavigationFooter_socialLinks_linkedin'),
            'facebook' => get_option('options_translatable_NavigationFooter_socialLinks_facebook'),
            'instagram' => get_option('options_translatable_NavigationFooter_socialLinks_instagram'),
        ],
        'labels' => [
            'ariaLabel' => get_option('options_translatable_NavigationFooter_labels_ariaLabel'),
            'socialAriaLabel' => get_option('options_translatable_NavigationFooter_labels_socialAriaLabel'),
            'legalAriaLabel' => get_option('options_translatable_NavigationFooter_labels_legalAriaLabel'),
        ],
        'copyright' => get_option('options_translatable_NavigationFooter_copyright'),
    ];
}
    ]);

    error_log(print_r(get_option('options_translatable_NavigationFooter_cardA'), true));
});
