<?php

/**
 * Register custom REST API endpoint for questionnaire data
 */

add_action('rest_api_init', function () {
    register_rest_route('questionnaire/v1', '/questions', [
        'methods' => 'GET',
        'callback' => 'get_questionnaire_data',
        'permission_callback' => '__return_true',
    ]);
});

/**
 * Create a URL-safe slug from a string
 */
function create_slug(string $string): string
{
    // Convert to lowercase and transliterate
    $string = strtolower(trim($string));

    // Replace non-alphanumeric characters with hyphens
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);

    // Remove consecutive hyphens
    $string = preg_replace('/-+/', '-', $string);

    // Remove leading and trailing hyphens
    return trim($string, '-');
}

/**
 * Generate a unique question ID based on level, title and parent ID
 */
function generate_question_id(int $level, string $title, ?string $parentId = null): string
{
    $slug = create_slug($title);
    $baseId = empty($slug) ? "q{$level}" : "q{$level}-{$slug}";

    // If there's a parent ID, append a hash of it to make the ID unique
    if ($parentId) {
        $shortHash = substr(md5($parentId), 0, 6);
        return "{$baseId}-{$shortHash}";
    }

    return $baseId;
}

/**
 * Transform ACF data into a flat question map
 */
function transform_to_question_map($data, $previousId = null, $level = 1, $max_depth = MAX_QUESTION_DEPTH): array
{
    if (empty($data)) {
        return [];
    }

    $questionMap = [];
    $questionTitle = $data["question_title"] ?? '';
    $questionDescription = $data["question_description"] ?? '';
    $currentQuestionId = $level == 1 ? 'entry_question' : generate_question_id($level, $questionTitle, $previousId);
    $isMultiSelect = $data["question_is_multi_select"] ?? false;
    $options = $data["options"] ?? [];

    // Create current question structure
    $question = [
        'id' => $currentQuestionId,
        'title' => $questionTitle ,
        'description' => $questionDescription,
        'previous' => $previousId,
        'level' => $level,
    ];


    if ($isMultiSelect) {
        // Handle multi-select questions
        $currentLevel = $level; // Create a copy for use in closure
        $items = array_map(function ($option) use ($currentLevel, $currentQuestionId) {
            return [
                'value' => $option["question_option"] ?? '',
            ];
        }, $options);

        $bulkAction = $data['question_bulk_action'] ?? null;
        $next = null;


        // question_2_bulk_action_page
        if ($bulkAction === 'end') {
            $next = [
                'type' => 'end',
                'value' => $data["question_bulk_action_page"] ?? '',
            ];
        } elseif ($bulkAction === 'continue' && $level < $max_depth) {
            // Get the next question's data first
            $nextQuestionData = $data['question_bulk_action_next'] ?? [];
            if (!empty($nextQuestionData)) {
                $next = [
                    'type' => 'continue',
                    'value' => generate_question_id($currentLevel + 1, $nextQuestionData["question_title"], $currentQuestionId),
                ];
            }
        }

        $question['options'] = [
            'type' => 'multiSelect',
            'items' => $items,
        ];


        if ($next) {
            $question['options']['next'] = $next;
        }

        // Add current question to map
        $questionMap[$currentQuestionId] = $question;

        // If there's a next question, process it
        if ($next && $next['type'] === 'continue') {
            $nextQuestionMap = transform_to_question_map(
                $data['question_bulk_action_next'] ?? [],
                $currentQuestionId,
                $level + 1,
                $max_depth
            );
            $questionMap = array_merge($questionMap, $nextQuestionMap);
        }
    } else {
        // Handle single-select questions
        $currentLevel = $level; // Create a copy for use in closure
        $items = array_map(function ($option) use ($currentLevel, $max_depth, $data, $currentQuestionId) {
            $item = [
                'value' => $option["question_option"] ?? '',
            ];

            $action = $option['question_action'] 
                ? $option['question_action'] 
                : ($currentLevel === $max_depth ? 'end' : null);

            if ($action === 'end') {
                $item['next'] = [
                    'type' => 'end',
                    'value' => $option["question_action_page"] ?? '',
                ];
            } elseif ($action === 'continue' && $currentLevel < $max_depth) {
                // Get the next question's data first
                $nextQuestionData = $option['question_action_next'] ?? [];
                if (!empty($nextQuestionData)) {
                    $item['next'] = [
                        'type' => 'continue',
                        'value' => generate_question_id($currentLevel + 1, $nextQuestionData["question_title"], $currentQuestionId),
                    ];
                }
            }

            return $item;
        }, $options);

        $question['options'] = [
            'type' => 'singleSelect',
            'items' => $items,
        ];

        // Add current question to map
        $questionMap[$currentQuestionId] = $question;

        // Process next questions for each option that continues
        foreach ($options as $index => $option) {
            $action = $option['question_action'] ?? null;
            if ($action === 'continue' && $level < $max_depth) {
                $nextQuestionMap = transform_to_question_map(
                    $option['question_action_next'] ?? [],
                    $currentQuestionId,
                    $level + 1,
                    $max_depth
                );
                $questionMap = array_merge($questionMap, $nextQuestionMap);
            }
        }
    }

    return $questionMap;
}

/**
 * REST API endpoint callback to get transformed questionnaire data
 */
function get_questionnaire_data(WP_REST_Request $request): WP_REST_Response
{
    // Get ACF data from options page
    $acf_data = get_field('question_entry', 'option');

    if (empty($acf_data)) {
        return new WP_REST_Response([
            'error' => 'No questionnaire data found',
        ], 404);
    }

    try {
        // Transform the data into a flat map
        $question_map = transform_to_question_map($acf_data);

        if (empty($question_map)) {
            throw new Exception('Failed to transform questionnaire data');
        }

        // Return the question map
        return new WP_REST_Response($question_map, 200);
    } catch (Exception $e) {
        return new WP_REST_Response([
            'error' => $e->getMessage(),
        ], 500);
    }
}


// Edit featured image REST API value
function post_featured_image_json($data, $post, $context)
{
    $featured_image_id = $data->data['featured_media'];
    $featured_image_url = wp_get_attachment_image_src($featured_image_id, 'original');

    if ($featured_image_url) {
        $data->data['featured_image_url'] = $featured_image_url[0];
    }

    return $data;
}
add_filter('rest_prepare_post', 'post_featured_image_json', 10, 3);
add_filter('rest_prepare_home-care-package', 'post_featured_image_json', 10, 3);

// Allows ACF to return image field as a URL instead of attachment ID
function custom_acf_rest_api_image_format($value, $post_id, $field)
{
    // Get the full image object from the ID using wp_get_attachment_image_src()
    $image = wp_get_attachment_image_src($value, 'full');
    // Return an array with the image URL
    return $image ? $image[0] : null;
}
// Hook the filter function to the acf/rest/format_value_for_rest/type=image filter
add_filter('acf/rest/format_value_for_rest/type=image', 'custom_acf_rest_api_image_format', 10, 3);


// Format post object for REST API
function format_post_object_for_rest($value, $post_id, $field)
{
    // Only modify for REST API requests
    if (!defined('REST_REQUEST') || !REST_REQUEST) {
        return $value;
    }

    // If value is empty, return early
    if (empty($value)) {
        return $value;
    }

    // Handle multiple post IDs
    if (is_array($value)) {
        return array_map(function ($post_id) {
            $post = get_post($post_id);
            return $post ? [
                'id' => $post->ID,
                'title' => $post->post_title
            ] : null;
        }, $value);
    }

    // Handle single post ID
    if (is_numeric($value)) {
        $post = get_post($value);
        return $post ? [
            'id' => $post->ID,
            'title' => $post->post_title
        ] : null;
    }

    return $value;
}
add_filter('acf/rest/format_value_for_rest/type=post_object', 'format_post_object_for_rest', 10, 3);
