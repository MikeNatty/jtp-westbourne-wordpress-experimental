<?php

namespace Flynt\Components\BlockServiceChecker;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=ServiceChecker', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockServiceChecker',
        'label' => __('Service Checker', 'flynt'),
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
                'type' => 'text',
                'required' => 1,
                'default_value' => 'Find services near you',
                
            ],
            [
                'label' => __('Description', 'flynt'),
                'name' => 'description',
                'type' => 'textarea',
                'required' => 0,
                'default_value' => 'Search by suburb, postcode or state',
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            FieldVariables\getAnchorOptions(),
            FieldVariables\getPersonalisation(),
        ]
    ];
}

// Register Options
Options::addGlobal('BlockServiceChecker', [
    [
        'label' => __('Error Message: No results heading.', 'flynt'),
        'name' => 'errorNoResultsHeading',
        'type' => 'text',
        'default_value' => 'Sorry, no results found',
        'required' => 1,
    ],
    [
        'label' => __('Error Message: No results description.', 'flynt'),
        'name' => 'errorNoResultsSubHeading',
        'type' => 'text',
        'default_value' => 'Please provide us some details about your locale and required services.',
    ],
    [
        'label' => __('Feedback form ID', 'flynt'),
        'name' => 'feedbackFormID',
        'type' => 'number',
    ],
    [
        'label' => __('Error Message: Search outside current service areas (Vic, Tas).', 'flynt'),
        'name' => 'errorOutsideServiceArea',
        'type' => 'text',
        'default_value' => 'Sorry, Uniting AgeWell only services Victoria and Tasmania.',
        'required' => 1,
    ],
]);