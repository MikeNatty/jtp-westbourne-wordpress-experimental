<?php

namespace Flynt\Components\BlockSearchResults;

use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockSearchResults', function ($data) {
    $data['uuid'] ??= wp_generate_uuid4();

    if (isset($_GET['contenthub-type'])) {
        $contenthub_type = $_GET['contenthub-type'];
        $data['filter'] = $contenthub_type[0];
    } else if (isset($_GET['post_type'])) {
        $post_type = $_GET['post_type'];
        $data['filter'] = $post_type[0];
    } else {
        $data['filter'] = 'all';
    }

    
    if (isset($_GET['s'])) {
        $url_query = $_GET['s'];
        $data['title'] = $url_query;

        $data['searchQuery'] = get_search_query();
    }

    $data['filters'] = [
        [ 
            'label' => 'All',
            'url' => '',
        ],
        [ 
            'label' => 'Videos',
            'url' => '&post_type%5B%5D=contenthub&contenthub-type%5B%5D=',
            'param' => 'video'
        ],
        [ 
            'label' => 'Podcasts',
            'url' => '&post_type%5B%5D=contenthub&contenthub-type%5B%5D=',
            'param' => 'podcast'
        ],
        [ 
            'label' => 'News',
            'url' => '&post_type%5B%5D=contenthub&contenthub-type%5B%5D=',
            'param' => 'news'
        ],
        [ 
            'label' => 'Publications',
            'url' => '&post_type%5B%5D=',
            'param' => 'publications'
        ],
    ];

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockSearchResults',
        'label' => 'Search Results',
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            [
                'label' => __('Globally managed content', 'flynt'),
                'instructions' => '',
                'name' => 'message',
                'type' => 'message',
                'message' => 'Search results are auto generated',
                'required' => 0,
            ], 
        ]
    ];
}