<?php

namespace Flynt\Components\BlockCardGrid;

use Flynt\FieldVariables;
use Timber\Timber;
use Flynt\Utils\Options;

// filter: related | recent | upcoming | past | taxonomy
// taxonomies - an array that shows the most relevant first

add_filter('Flynt/addComponentData?name=BlockCardGrid', function ($data) {
    $data['uuid'] = $data['uuid'] ?? wp_generate_uuid4();
    $data['imageOptions'] = Options::getTranslatable('ImageOptions');

    if (!isset($data['slidesPerView'])) {
        $data['slidesPerView'] = 3;
    }

    $posts_per_page = 9;

    // POST TYPE
    if (isset($data['contentSource'])) {

        // called from page builder component
        if ($data['contentSource'] == 'postType') {
            $post_type = $data['sourcePostType'];
            $post_type_group = $data[$post_type];

            $filter = $post_type_group['filter'];
            if ($filter && $filter == 'taxonomy' ) {
                $taxonomy = $post_type_group['taxonomy'];
            }

            $data['filter'] = $filter;

        // called from article
        } else if ($data['contentSource'] == 'post') {
            $post_type = $data['post']->post_type;
        }


        // HAS FILTER
        if (isset($data['filter'])) {

            if ($data['filter'] == 'select') {

                $data['posts'] = $post_type_group['selectedPosts'];

            // PAST OR FUTURE - just for events
            } else if ($data['filter'] == 'upcoming' || $data['filter'] == 'past') {
                $today = current_time('Y-m-d'); // Get current date in WordPress

                $data['posts'] = Timber::get_posts([
                    'post_status' => 'publish',
                    'post_type' => $post_type,
                    'posts_per_page' => $posts_per_page,
                    'post__not_in'   => [get_the_ID()], // don't include current page
                    'meta_query' => [
                        [
                            'key' => 'eventEndDate',
                            'value' => $today,
                            'compare' => $data['filter'] == 'upcoming' ? '>=' : '<',
                            // 'compare' => '<', // Before today
                            // 'compare' => '>=', // Greater than or equal to today
                            'type' => 'DATE'
                        ]
                    ],
                    'orderby' => 'meta_value',
                    'meta_key' => 'eventEndDate',
                    'order' => $data['filter'] == 'upcoming' ? 'ASC' : 'DESC',
                    // 'order' => 'ASC' // Sort from nearest to furthest date
                ]);

            // TAXONOMY - currently only for content hub post type
            } else  if ($data['filter'] == 'taxonomy' && $taxonomy) {

                $data['posts'] = Timber::get_posts([
                    'post_status' => 'publish',
                    'post_type' => $post_type,
                    'posts_per_page' => $posts_per_page,
                    // 'post__not_in'   => [get_the_ID()], // don't include current page
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'tax_query' => [
                        [
                            'taxonomy' => 'contenthub-type',
                            'field' => 'id',
                            'terms' => $taxonomy
                        ]
                    ]
                ]);

            } else if ($data['filter'] == 'related') {

            // RECENT - catch all
            } else {
                $data['posts'] = Timber::get_posts([
                    'post_status' => 'publish',
                    'post_type' => $post_type,
                    'posts_per_page' => $posts_per_page,
                    // 'post__not_in'   => [get_the_ID()], // don't include current page
                    'orderby' => 'date',
                    'order' => 'DESC',
                ]);
            }
        }

    // RELATED - used at the end of articles
    } else if (isset($data['filter']) && $data['filter'] == 'related') {
        $post_type = $data['post']->post_type;
        $post_id = $data['post']->id;

        if (isset($data['relatedTaxonomies'])) {
            $taxonomyQuery = [
                'relation' => 'OR',
            ];

            foreach ($data['relatedTaxonomies'] as $relatedTaxonomy) {
                $terms = get_the_terms($post_id, $relatedTaxonomy);

                $postTypeTaxonomies = array_map(function($obj) {
                    return $obj->slug;
                }, $terms);

                array_push($taxonomyQuery, [
                    'taxonomy' => $relatedTaxonomy,
                    'field' => 'slug',
                    'terms' => $postTypeTaxonomies
                ]);
            }

            // get all relative posts with the same taxonomies
            $data['posts'] = Timber::get_posts([
                'post_status' => 'publish',
                'post_type' => $post_type,
                'posts_per_page' => $posts_per_page,
                'post__not_in'   => [$post_id],
                'orderby' => 'none',
                'tax_query' => $taxonomyQuery,
            ]);

            // after getting all relative taxonomy posts, get most recent articles as a catch all
            if (!isset($data['posts']) || count($data['posts']) < $posts_per_page) {
                $taxonomyPosts = $data['posts']->to_array();

                $taxonomy_post_ids = array_map(function($obj) {
                    return $obj->id;
                }, $taxonomyPosts);

                $taxonomy_post_ids[] = $post_id;

                $recentPosts = Timber::get_posts([
                    'post_status' => 'publish',
                    'post_type' => $post_type,
                    'posts_per_page' =>  $posts_per_page - count($data['posts']),
                    'post__not_in'   => $taxonomy_post_ids,
                    'orderby' => 'date',
                    'order' => 'DESC',
                ]);

                $mergedPosts = array_merge($taxonomyPosts , $recentPosts->to_array() );
                $data['posts'] = $mergedPosts;
            }

        // if taxonomies aren't defined then load most recent
        } else {
            $data['posts'] = Timber::get_posts([
                'post_status' => 'publish',
                'post_type' => $post_type,
                'posts_per_page' => $posts_per_page,
                'post__not_in'   => [$post_id],
                'orderby' => 'date',
                'order' => 'DESC',
            ]);
        }
    }


    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'blockCardGrid',
        'label' => 'Card Grid',
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
                'type' => 'textarea',
                'rows' => 1,
                'new_lines' => 'br',
                'required' => 0,
                // 'wrapper' => [
                //     'width' => '50',
                // ],
            ],
            // [
            //     'label' => __('Subheading', 'flynt'),
            //     'name' => 'subheading',
            //     'type' => 'textarea',
            //     'rows' => 1,
            //     'new_lines' => 'br',
            //     'required' => 0,
            //     'wrapper' => [
            //         'width' => '50',
            //     ],
            // ],
            [
                'label' => 'Intro Card',
                'name' => 'introCard',
                'instructions' => __('Displayed as the first card in the grid with a pink background.', 'flynt'),
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'default',
                        ],
                    ],
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'contact',
                        ],
                    ],
                ],
                'type' => 'group',
                'sub_fields' => [
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ],
                    [
                        'label' => __('Description', 'flynt'),
                        'name' => 'description',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ],
                ],
            ],

            [
                'label' => __('Cards', 'flynt'),
                'name' => 'cards',
                'type' => 'repeater',
                'min' => 1,
                'max' => 20,
                'collapsed' => 'field_pageComponents_pageComponents_blockCardGrid_cards_title',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'default',
                        ],
                    ],
                ],
                'layout' => 'row',
                'button_label' => __('Add Card', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Card type', 'flynt'),
                        'name' => 'type',
                        'type' => 'button_group',
                        'choices' => [
                            'text' => 'Text',
                            'image' => 'Image',
                        ],
                        'default_value' => 'text',
                    ],
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Minimum size 500x500px. <br>Maximum size 1200x1200px.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'required' => 1,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'type',
                                    'operator' => '==',
                                    'value' => 'image',
                                ],
                            ],
                        ],
                        // // TODO remove for prod
                        // 'default_value' => 91
                    ],

                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'type',
                                    'operator' => '==',
                                    'value' => 'text',
                                ],
                            ],
                        ],
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem ipsum',
                    ],
                    [
                        'label' => __('Description', 'flynt'),
                        'name' => 'description',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'fieldPath' => 'type',
                                    'operator' => '==',
                                    'value' => 'text',
                                ],
                            ],
                        ],
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem ipsum',
                    ],
                ]
            ],
            [
                'label' => __('Team Cards', 'flynt'),
                'name' => 'teamCards',
                'type' => 'repeater',
                'min' => 1,
                'max' => 20,
                'collapsed' => 'field_pageComponents_pageComponents_blockCardGrid_teamCards_title',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'team',
                        ],
                    ],
                ],
                'layout' => 'table',
                'button_label' => __('Add Card', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Minimum size 500x500px. <br>Maximum size 1200x1200px.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'required' => 1,
                        'wrapper' => [
                            'width' => '20',
                        ],
                        // // TODO remove for prod
                        // 'default_value' => 91
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'First Name Last Name',
                    ],
                    [
                        'label' => __('Position', 'flynt'),
                        'name' => 'position',
                        'type' => 'text',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Position',
                    ],
                    [
                        'label' => __('Bio', 'flynt'),
                        'name' => 'bioLink',
                        'type' => 'post_object',
                        'return_format' => 'object',
                        'post_type' => ['modal'],
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Position',
                    ],
                ]
            ],
            [
                'label' => __('Contact Cards', 'flynt'),
                'name' => 'contactCards',
                'type' => 'repeater',
                'min' => 1,
                'max' => 20,
                'collapsed' => 'field_pageComponents_pageComponents_blockCardGrid_contactCards_title',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'contact',
                        ],
                    ],
                ],
                'layout' => 'table',
                'button_label' => __('Add Card', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 0,
                    ],
                    [
                        'label' => __('Phone', 'flynt'),
                        'name' => 'phone',
                        'type' => 'text',
                    ],
                    [
                        'label' => __('Location', 'flynt'),
                        'name' => 'location',
                        'type' => 'link',
                        'return_format' => 'array',
                    ],
                ]
            ],
            [
                'label' => __('Icon Cards', 'flynt'),
                'name' => 'iconCards',
                'type' => 'repeater',
                'min' => 1,
                'max' => 5,
                'collapsed' => 'field_pageComponents_pageComponents_blockCardGrid_iconCards_title',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'icon',
                        ],
                    ],
                ],
                'layout' => 'table',
                'button_label' => __('Add Card', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Icon Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Minimum size 152x152px.', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'thumbnail',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'required' => 1,
                        'wrapper' => [
                            'width' => '20',
                        ],
                        // TODO remove for prod
                        'default_value' => 4555
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem sed unde omnis',
                    ],
                    [
                        'label' => __('Description', 'flynt'),
                        'name' => 'description',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem sed unde omnis',
                    ],
                ]
            ],
            [
                'label' => 'Cta Card',
                'name' => 'ctaCard',
                'instructions' => __('Displayed as the last card in the grid with a pink background.', 'flynt'),
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'default',
                        ],
                    ],
                ],
                'type' => 'group',
                'sub_fields' => [
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        'wrapper' => [
                            'width' => '50',
                        ],
                    ],
                    FieldVariables\getCTA(),
                ],
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            FieldVariables\getAnchorOptions(),
            [
                'label' => __('Card Theme', 'flynt'),
                'name' => 'theme',
                'type' => 'radio',
                // 'instructions' => 'Variant 1: Light pink background and additional space under the image. <br>Variant 2: White background.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                // 'layout' => 'horizontal',
                'choices' => [
                    'default' => __('Default - contains image and text options, with an intro and CTA card', 'flynt'),
                    'icon' => __('Icon - contains text with an icon for each card.', 'flynt'),
                    'team' => __('Team - used to display the team grid.', 'flynt'),
                    'contact' => __('Contact', 'flynt'),
                ],
                'default_value' => 'default'
            ],
            [
                'label' => __('Grid Columns', 'flynt'),
                'name' => 'desktopColumns',
                'type' => 'button_group',
                'instructions' => 'How many cards appear per view on desktop.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '2' => __('2', 'flynt'),
                    '3' => __('3', 'flynt'),
                    '4' => __('4', 'flynt'),
                    // '5' => __('5', 'flynt'),
                ],
                'default_value' => '3',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'default',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Grid Columns', 'flynt'),
                'name' => 'desktopIconColumns',
                'type' => 'button_group',
                'instructions' => 'How many cards appear per view on desktop.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    '2' => __('2', 'flynt'),
                    '3' => __('3', 'flynt'),
                ],
                'default_value' => '3',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'icon',
                        ],
                    ],
                ],
            ],
            [
                'label' => __('Heading size', 'flynt'),
                'name' => 'titleSize',
                'type' => 'radio',
                // 'instructions' => 'Size of the title on desktop.',
                'other_choice' => 0,
                'save_other_choice' => 0,
                'layout' => 'horizontal',
                'choices' => [
                    'small' => __('Small (Step 3)', 'flynt'),
                    'medium' => __('Medium (Step 6)', 'flynt'),
                    'large' => __('Large (Step 8)', 'flynt'),
                ],
                'default_value' => 'medium',
                'conditional_logic' => [
                    [
                        [
                            'fieldPath' => 'theme',
                            'operator' => '==',
                            'value' => 'team',
                        ],
                    ],
                ],
            ],
            FieldVariables\getPersonalisation(),
        ],
    ];
}
