<?php

namespace Flynt\Components\BlockImageCarousel;

use Flynt\FieldVariables;
use Timber\Timber;
use Flynt\Utils\Options;

// filter: related | recent | upcoming | past | taxonomy
// taxonomies - an array that shows the most relevant first

add_filter('Flynt/addComponentData?name=BlockImageCarousel', function ($data) {
    $data['uuid'] = $data['uuid'] ?? wp_generate_uuid4();
    $data['imageOptions'] = Options::getTranslatable('ImageOptions');

    if (!isset($data['slidesPerView'])) {
        $data['slidesPerView'] = 3;
    }

    error_log( 'BlockImageCarousel data: ' . print_r($data, true) );

    $posts_per_page = 9;

    // POST TYPE
    if (isset($data['contentSource'])) {
        // called from page builder component
        if ($data['contentSource'] == 'postType') {
            $post_type = $data['sourcePostType'];
            $post_type_group = $data[$post_type];

            $filter = $post_type_group['filter'];
            if ($filter && $filter == 'taxonomy') {
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
            } else if ($data['filter'] == 'taxonomy' && $taxonomy) {
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

                $postTypeTaxonomies = array_map(function ($obj) {
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

                $taxonomy_post_ids = array_map(function ($obj) {
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

                $mergedPosts = array_merge($taxonomyPosts, $recentPosts->to_array());
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
    } else {
        $data['posts'] = Timber::get_posts([
            'post_status' => 'publish',
            'post_type' => $data['sourcePostType'] ?: 'contenthub',
            'posts_per_page' => $posts_per_page,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
    }


    return $data;
});

function getACFLayout()
{
    return [
        'name' => 'BlockImageCarousel',
        'label' => 'Image Carousel',
        'sub_fields' => [
            [
                'label' => __('Content', 'flynt'),
                'name' => 'contentTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
//            [
//                'label' => __('Content Source', 'flynt'),
//                'name' => 'contentSource',
//                'type' => 'button_group',
//                // 'instructions' => 'Variant 1: Large single image and bottom aligned content. <br>Variant 2: Additional small image and top aligned content.',
//                'other_choice' => 0,
//                'save_other_choice' => 0,
//                'layout' => 'horizontal',
//                'wrapper' => [
//                    'width' => '30',
//                ],
//                'choices' => [
//                    'custom' => __('Custom', 'flynt'),
//                    'postType' => __('Post Type', 'flynt'),
//                ],
//                'default_value' => 'postType'
//            ],
//            [
//                'label' => __('Source Post Type', 'flynt'),
//                'name' => 'sourcePostType',
//                'type' => 'button_group',
//                // 'instructions' => 'Variant 1: Large single image and bottom aligned content. <br>Variant 2: Additional small image and top aligned content.',
//                'other_choice' => 0,
//                'save_other_choice' => 0,
//                'layout' => 'horizontal',
//                'choices' => [
////                    'contenthub' => __('Content Hub', 'flynt'),
//                    'events' => __('Events', 'flynt'),
//                    'publications' => __('Publications', 'flynt'),
////                    'location' => __('Locations', 'flynt'),
//                ],
//                'default_value' => 'events',
//                'conditional_logic' => [
//                    [
//                        [
//                            'fieldPath' => 'contentSource',
//                            'operator' => '==',
//                            'value' => 'postType',
//                        ],
//                    ],
//                ],
//            ],
            [
                'label' => __('Images', 'flynt'),
                'name' => 'cards',
                'type' => 'repeater',
                'min' => 1,
                'max' => 20,
                'collapsed' => 'field_pageComponents_pageComponents_blockImageCarousel_cards_title',
//                'conditional_logic' => [
//                    [
//                        [
//                            'fieldPath' => 'contentSource',
//                            'operator' => '==',
//                            'value' => 'custom',
//                        ],
//                    ],
//                ],
                'layout' => 'row',
                'button_label' => __('Add Image', 'flynt'),
                'sub_fields' => [
                    [
                        'label' => __('Image', 'flynt'),
                        'instructions' => __('Image-Format: JPG, PNG, WebP. <br>Recommended size: 240px x 240px', 'flynt'),
                        'name' => 'image',
                        'type' => 'image',
                        'preview_size' => 'medium',
                        'mime_types' => 'jpg,jpeg,png,svg,webp',
                        'required' => 0,
                        // TODO remove for prod
//                        'default_value' => 91
                    ],
                    [
                        'label' => __('Title', 'flynt'),
                        'name' => 'title',
                        'type' => 'textarea',
                        'rows' => 1,
                        'placeholder' => '',
                        'new_lines' => 'br',
                        'required' => 0,
                        // // TODO remove for prod
                        // 'default_value' => 'Lorem ipsum',
                    ],
//                    [
//                        'label' => __('Description', 'flynt'),
//                        'name' => 'description',
//                        'type' => 'textarea',
//                        'rows' => 1,
//                        'placeholder' => '',
//                        'new_lines' => 'br',
//                        'required' => 0,
//                        // // TODO remove for prod
//                        // 'default_value' => 'Lorem ipsum',
//                    ],
                    [
                        'label' => __('Link', 'flynt'),
                        'name' => 'link',
                        'type' => 'link',
                        'return_format' => 'array',
                        // // TODO remove for prod
                        // 'default_value' => [
                        //     'url' => '/',
                        //     'title' => 'Label',
                        //     'target' => 'target'
                        // ]
                    ],
                ]
            ],
            [
                'label' => __('Options', 'flynt'),
                'name' => 'optionsTab',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0
            ],
            FieldVariables\getAnchorOptions(),
//            [
//                'label' => __('Heading size', 'flynt'),
//                'name' => 'titleSize',
//                'type' => 'button_group',
//                // 'instructions' => 'Size of the title on desktop.',
//                'other_choice' => 0,
//                'save_other_choice' => 0,
//                'layout' => 'horizontal',
//                'choices' => [
//                    'small' => __('Small (Step 3)', 'flynt'),
//                    'medium' => __('Medium (Step 6)', 'flynt'),
//                    'large' => __('Large (Step 8)', 'flynt'),
//                ],
//                'default_value' => 'medium',
//            ],
//            [
//                'label' => __('Carousel Columns', 'flynt'),
//                'name' => 'slidesPerView',
//                'type' => 'button_group',
//                'instructions' => 'How many cards appear per view on desktop.',
//                'other_choice' => 0,
//                'save_other_choice' => 0,
//                'layout' => 'horizontal',
//                'choices' => [
//                    '2' => __('2', 'flynt'),
//                    '3' => __('3', 'flynt'),
//                    '4' => __('4', 'flynt'),
//                    // '5' => __('5', 'flynt'),
//                ],
//                'default_value' => '3'
//            ],
//            FieldVariables\getPersonalisation(),
        ],
    ];
}
