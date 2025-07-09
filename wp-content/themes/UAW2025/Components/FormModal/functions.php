<?php

namespace Flynt\Components\FormModal;

use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=FormModal', function (array $data): array {
    return $data;
});

// Register Options
Options::addGlobal('FormModal', [
    [
        'label' => __('Forms', 'flynt'),
        'name' => 'formIds',
        'instructions' => __('Add the corresponding Formidable Forms ID', 'flynt'),
        'type' => 'group',
        'layout' => 'table',
        'sub_fields' => [
            [
                'label' => __('Enquiry', 'flynt'),
                'name' => 'enquiry',
                'type' => 'number',
            ],
            [
                'label' => __('Book a call back', 'flynt'),
                'name' => 'bookCall',
                'type' => 'number',
            ],
            [
                'label' => __('Talk to our Recruitment Team', 'flynt'),
                'name' => 'talkRecruitment',
                'type' => 'number',
            ],
            [
                'label' => __('Book a tour', 'flynt'),
                'name' => 'bookTour',
                'type' => 'number',
            ],
            [
                'label' => __('Donation Form', 'flynt'),
                'name' => 'donation',
                'type' => 'number',
            ],
        ]
    ],
]);