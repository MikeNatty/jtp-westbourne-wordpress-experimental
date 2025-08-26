<?php

use ACFComposer\ACFComposer;
use Flynt\Components;

add_action('Flynt/afterRegisterComponents', function (): void {
    ACFComposer::registerFieldGroup([
        'name' => 'personalisationComponents',
        'title' => __('Page Components', 'flynt'),
        'style' => 'seamless',
        'fields' => [
            [
                'name' => 'pageComponents',
                'label' => __('Page Components', 'flynt'),
                'type' => 'flexible_content',
                'button_label' => __('Add Component', 'flynt'),
                'layouts' => [
                    Components\BlockAccordion\getACFLayout(),
                    Components\BlockCardCarousel\getACFLayout(),
                    Components\BlockChecklist\getACFLayout(),
                    Components\BlockContentCarousel\getACFLayout(),
                    Components\blockContentFullWidth\getACFLayout(),
                    Components\blockSavePage\getACFLayout(),
                    Components\BlockContentMedia\getACFLayout(),
                    Components\BlockHeading\getACFLayout(),
                    Components\BlockHero\getACFLayout(),
                    Components\BlockLocationMap\getACFLayout(),
                    Components\BlockMedia\getACFLayout(),
                    Components\BlockSectionIntro\getACFLayout(),
                    Components\BlockWysiwyg\getACFLayout(),
                    Components\BlockQuote\getACFLayout(),
                    Components\BlockContactCard\getACFLayout(),
                    Components\BlockForm\getACFLayout(),
                    Components\BlockServiceChecker\getACFLayout(),
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'personalisation'
                ]
            ],
        ],
    ]);
});
