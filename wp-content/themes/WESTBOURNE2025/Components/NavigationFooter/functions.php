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
        'label' => 'Information Card',
        'name' => 'infoCard',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'default_value' => 'Need some more information?',
            ],
            FieldVariables\getCTA(),
        ]
    ],
    [
        'label' => 'Contact Card',
        'name' => 'contactCard',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'default_value' => 'Want to speak with someone?',
            ],
            FieldVariables\getCTA(),
            [
                'label' => 'Phone Number',
                'name' => 'phoneNumber',
                'type' => 'text',
                'default_value' => '1300 783 435',
            ],
        ]
    ],
    [
        'label' => 'Acknowledgement Text',
        'name' => 'contentHtml',
        'type' => 'wysiwyg',
        'media_upload' => 1,
        'required' => 1,
        'toolbar' => 'basic',
        'default_value' => 'Uniting AgeWell acknowledges the Traditional Custodians of the lands on which we live, work and provide services. We pay our respects to their Elders, past, present and emerging.',
    ],
    [
        'label' => 'Newsletter',
        'name' => 'newsletter',
        'type' => 'group',
        'sub_fields' => [
            [
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'default_value' => 'Subscribe now and stay updated with Uniting AgeWell',
            ],
            [
                'label' => 'Form Shortcode',
                'name' => 'formShortcode',
                'type' => 'text',
            ],
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
        'default_value' => 'Copyright © 2025 Uniting AgeWell Limited ABN xxxxxxxxxxxxxxx All rights reserved.',
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
