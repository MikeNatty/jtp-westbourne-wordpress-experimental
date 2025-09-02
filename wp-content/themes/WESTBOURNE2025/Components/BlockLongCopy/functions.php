<?php

namespace Flynt\Components\BlockLongCopy;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockLongCopy', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockLongCopy',
        'label' => 'Long Copy',
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'label' => 'Heading',
                'name' => 'heading',
                'type' => 'textarea',
                'new_lines' => 'br',
                'required' => 1,
                'rows' => 3,
                // TODO remove for prod
                'default_value' => 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam',
            ],
            [
                'label' => __('Intro', 'flynt'),
                'name' => 'intro',
                'type' => 'text',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'variant',
                            'operator' => '==',
                            'value' => '2',
                        ],
                    ],
                ],
                'required' => 0,
                // TODO remove for prod
                'default_value' => 676
            ],

            [
                'label' => 'Copy',
                'name' => 'copy',
                'type' => 'textarea',
                'required' => 0,
                'new_lines' => 'br',
                'rows' => 16,
                // TODO remove for prod
                'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas elementum mollis magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tellus urna, aliquet eget posuere vel, finibus sagittis ex. Mauris eu sodales leo. Maecenas ullamcorper sagittis neque, et euismod nisl vestibulum eu. Cras pretium sapien metus, sagittis vehicula eros sollicitudin id. Praesent tempor erat ac ipsum molestie, sed faucibus magna accumsan. Nulla lacinia molestie sapien. Donec porttitor, erat vel imperdiet sagittis, tellus magna facilisis dolor, id tempor dolor augue eu libero.\nMaecenas auctor bibendum neque, at fermentum eros placerat sit amet. Etiam a auctor tortor. Morbi non venenatis quam. Aliquam sodales ultricies tempus. Duis placerat dictum molestie. Aenean tincidunt nec diam eget ultricies. Phasellus vehicula pellentesque mollis. Phasellus et elit ac diam tempor pharetra. Donec at velit mattis, aliquam neque vel, ultrices felis. Mauris condimentum massa vel interdum iaculis. Nam tellus dui, aliquam quis consequat sollicitudin, condimentum a quam. Nunc consequat lacus tellus, vel pretium augue commodo eu. Fusce bibendum odio at cursus volutpat. Nullam mollis dapibus ipsum, a porta quam euismod vel.\nFusce eros turpis, dignissim sed ultrices vel, aliquet sed tortor. Duis condimentum condimentum sem, nec feugiat nibh facilisis eu. Duis commodo orci nec orci sollicitudin, vel elementum eros tincidunt. Nullam porta lorem felis, et condimentum orci tempus id. Aenean at mattis ligula. Curabitur eleifend iaculis sapien, auctor maximus tellus convallis ut. Mauris libero eros, bibendum ut venenatis eget, ullamcorper ut dolor.',
            ],
            FieldVariables\getCTA(
                'cta',
                [
                    'fieldPath' => 'variant',
                    'operator' => '==',
                    'value' => '2',
                ],
            ),
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
                'type' => 'button_group',
                'instructions' => 'Variant 1: One column - Heading and long copy, optional CTA.<br>Variant 2: Two columns on larger screens - Larger Heading, intro, long copy.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '1' => __('Variant 1', 'flynt'),
                    '2' => __('Variant 2', 'flynt'),
                ],
                'default_value' => '1'
            ],
            FieldVariables\getPersonalisation(),
        ]
    ];
}
