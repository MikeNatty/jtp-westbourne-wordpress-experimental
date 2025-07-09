<?php

namespace Flynt\Components\NotificationBar;

use Flynt\Utils\Asset;
use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=NotificationBar', function (array $data): array {
    // Check if the notification bar should be displayed based on scheduling
    $data['showNotification'] = shouldShowNotification($data);
    
    // Set unique ID for the notification bar (used in localStorage)
    if (!empty($data['notificationContent']) && $data['showNotification']) {
        $data['notificationId'] = 'notification_' . md5($data['notificationContent'] . (isset($data['endDate']) ? $data['endDate'] : ''));
    }

    return $data;
});

// Function to check if notification should be displayed based on date constraints
function shouldShowNotification($data) {
    // Default to showing if enabled
    if (empty($data['isEnabled'])) {
        return false;
    }
    
    $showNotification = true;
    $currentDate = current_time('timestamp');
    
    // Check start date if set
    if (!empty($data['startDate'])) {
        $startDate = strtotime($data['startDate']);
        if ($currentDate < $startDate) {
            $showNotification = false;
        }
    }
    
    // Check end date if set
    if (!empty($data['endDate'])) {
        $endDate = strtotime($data['endDate']);
        if ($currentDate > $endDate) {
            $showNotification = false;
        }
    }
    
    return $showNotification;
}


Options::addTranslatable('NotificationBar', [
    [
        'label' => __('General', 'flynt'),
        'name' => 'generalTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
    ],
    [
        'label' => __('Enable Notification Bar', 'flynt'),
        'name' => 'isEnabled',
        'type' => 'true_false',
        'default_value' => 0,
        'ui' => 1,
    ],
    [
        'label' => __('Notification Content', 'flynt'),
        'name' => 'notificationContent',
        'type' => 'wysiwyg',
        'tabs' => 'visual,text',
        'toolbar' => 'basic',
        'media_upload' => 0,
        'delay' => 1,
        'instructions' => __('Add your notification message here. You can include links to pages.', 'flynt'),
        'conditional_logic' => [
            [
                [
                    'fieldPath' => 'isEnabled',
                    'operator' => '==',
                    'value' => 1
                ]
            ]
        ]
    ],
    [
        'label' => __('Scheduling', 'flynt'),
        'name' => 'schedulingTab',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
        'conditional_logic' => [
            [
                [
                    'fieldPath' => 'isEnabled',
                    'operator' => '==',
                    'value' => 1
                ]
            ]
        ]
    ],
    [
        'label' => __('Start Date', 'flynt'),
        'name' => 'startDate',
        'type' => 'date_picker',
        'instructions' => __('Leave empty for no start date restriction.', 'flynt'),
        'display_format' => 'd/m/Y',
        'return_format' => 'Y-m-d',
        'first_day' => 1,
        'conditional_logic' => [
            [
                [
                    'fieldPath' => 'isEnabled',
                    'operator' => '==',
                    'value' => 1
                ]
            ]
        ]
    ],
    [
        'label' => __('End Date', 'flynt'),
        'name' => 'endDate',
        'type' => 'date_picker',
        'instructions' => __('Leave empty for no end date restriction.', 'flynt'),
        'display_format' => 'd/m/Y',
        'return_format' => 'Y-m-d',
        'first_day' => 1,
        'conditional_logic' => [
            [
                [
                    'fieldPath' => 'isEnabled',
                    'operator' => '==',
                    'value' => 1
                ]
            ]
        ]
    ],
]);
