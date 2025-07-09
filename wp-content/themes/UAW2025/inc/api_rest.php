<?php

add_action('rest_api_init', function () {
    register_rest_route('shortcode', '/render', [
    'methods' => 'GET',
    'callback' => 'shortcode_render_callback',
    'permission_callback' => '__return_true',
    ]);
});


/**
 * Using GET
 * @param $request
 * @return array|WP_Error
 */
function shortcode_render_callback( $request ) {
    $shortcode = $request->get_param('shortcode');

    if ( empty( $shortcode ) ) {
        return new WP_Error( 'no_shortcode', 'No shortcode provided', [ 'status' => 400 ] );
    }

    $html = do_shortcode( $shortcode );
    return [ 'html' => $html ];
}

/**
 * Using POST
 */
//function shortcode_render_callback( $request ) {
//    $params = $request->get_json_params();
//    $shortcode = isset($params['shortcode']) ? $params['shortcode'] : '';
//
//    if ( empty( $shortcode ) ) {
//        return new WP_Error( 'no_shortcode', 'No shortcode provided', [ 'status' => 400 ] );
//    }
//
//    $html = do_shortcode( $shortcode );
//    return [ 'html' => $html ];
//}
