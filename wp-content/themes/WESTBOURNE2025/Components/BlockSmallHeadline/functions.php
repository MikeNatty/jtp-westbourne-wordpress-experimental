<?php

namespace Flynt\Components\BlockSmallHeadline;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockSmallHeadline', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockSmallHeadline',
        'label' => __('Small Headline', 'flynt'),
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
                // // TODO remove for prod
                // 'default_value' => 'Lorem ipsum dolor',
            ],
            [
                'label' => __('Heading', 'flynt'),
                'name' => 'heading',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => 'br',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 'Lorem ipsum dolor sit amet, elipse adipiscing elit',
            ],
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentHtml',
                'type' => 'wysiwyg',
                'delay' => 0,
                'media_upload' => 0,
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            ],
            FieldVariables\getCTA(),
            [
                'label' => __('Device Image (shows in tablet and mobile)', 'flynt'),
                'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Min size: 1104px for RHS image. 2880px for bg image.', 'flynt'),
                'name' => 'image',
                'type' => 'image',
                'preview_size' => 'medium',
                'mime_types' => 'jpg,jpeg,png,svg,webp',
                'required' => 0,
                // // TODO remove for prod
                // 'default_value' => 91
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            FieldVariables\getAnchorOptions(),
//            FieldVariables\getPersonalisation(),
            [
                'label' => __('Variant', 'flynt'),
                'name' => 'variant',
                'type' => 'radio',
                'instructions' => 'Variant 1: CTA Line. <br>Variant 2: CTA Button.',
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
