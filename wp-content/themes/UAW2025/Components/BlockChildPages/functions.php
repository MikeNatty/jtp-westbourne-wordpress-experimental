<?php

namespace Flynt\Components\BlockChildPages;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockChildPages', function ($data) {
    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockChildPages',
        'label' => 'Child Page List',
        // 'sub_fields' => [
        //     [
        //         'label' => 'Quote Text',
        //         'name' => 'quote',
        //         'type' => 'textarea',
        //         'new_lines' => 'br',
        //         'required' => 0,
        //         'rows' => 4,
        //     ],
        //     [
        //         'label' => 'Author Name',
        //         'name' => 'authorName',
        //         'type' => 'text',
        //         'required' => 0,
        //     ],
        //     [
        //         'label' => 'Author Position',
        //         'name' => 'authorPosition',
        //         'type' => 'text',
        //         'required' => 0,
        //     ],
        // ]
    ];
}