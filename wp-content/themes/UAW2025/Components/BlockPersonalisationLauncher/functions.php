<?php

namespace Flynt\Components\BlockPersonalisationLauncher;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=BlockPersonalisationLauncher', function ($data) {
    $data['buttons'] = ["I'm looking for myself", "I'm looking for someone", "I'm looking for a job", "I'm looking to volunteer"];

    $question_entry = get_field('question_entry', 'option');
    $data['question_entry'] = $question_entry;

    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockPersonalisationLauncher',
        'label' => __('Personalisation Journey Launcher', 'flynt'),
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
                'message' => 'Content is pulled from the first question in the <a href="http://localhost:10054/wp-admin/admin.php?page=personalisation">Questionnaire</a> seciton, within the Question 1 options.',
                'required' => 0,
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
