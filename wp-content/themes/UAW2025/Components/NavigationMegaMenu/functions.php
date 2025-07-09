<?php

namespace Flynt\Components\NavigationMegaMenu;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

add_action('init', function (): void {
    register_nav_menus([
        'navigation_mega_menu' => __('Navigation Mega Menu', 'flynt')
    ]);
});

add_filter('Flynt/addComponentData?name=NavigationMegaMenu', function (array $data): array {
    $data['menu'] = Timber::get_menu('navigation_mega_menu') ?? Timber::get_pages_menu();
    $data['logo'] = [
        // 'src' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : Asset::requireUrl('assets/images/logo.svg'),
        'src' => Asset::requireUrl('assets/images/logo.svg'),
        'alt' => get_bloginfo('name')
    ];

    return $data;
});

Options::addTranslatable('NavigationMegaMenu', [
    [
        'label' => __('Content', 'flynt'),
        'name' => 'contentTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0
    ],
    [
        'label' => __('Gallery', 'flynt'),
        'name' => 'gallery',
        'type' => 'gallery',
        'min' => 1,
        // 'preview_size' => 'medium',
        // 'mime_types' => 'image',
        'instructions' => __('Images to display with the corresponding submenu item', 'flynt'),
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
                'label' => __('Aria Label', 'flynt'),
                'name' => 'ariaLabel',
                'type' => 'text',
                'default_value' => __('Main Navigation', 'flynt'),
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
        ],
    ],
]);
