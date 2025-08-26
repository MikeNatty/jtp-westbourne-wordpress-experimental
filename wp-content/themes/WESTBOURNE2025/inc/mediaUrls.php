<?php

/**
 * Add the featured media url in addition to the media id
 */
add_filter( 'rest_prepare_location', function( $data, $post, $context ){

    $featured_image_id = $data->data['featured_media']; // get featured image id
    $featured_image_url = wp_get_attachment_image_src( $featured_image_id, 'original' ); // get url of the original size
    $gallery =  $data->data['acf']['gallery'];

    if( $featured_image_url ) {
        $data->data['featured_image_url'] = $featured_image_url[0];
    }
    error_log('TRANSFORM GALLERY: ' . $data->data['acf']['gallery']);
    if( $gallery ) {
        $urls = [];
        foreach( $gallery as $id ) {
            error_log('TRANSFORM GALLERY');
            $urls[] = wp_get_attachment_image_src( $id, 'medium_large' );

        }
        $data->data['acf']['gallery'] = $urls;
    }

    return $data;

}, 10, 3 );


//add_filter('acf/rest_api/locations/get_fields', function ($data, $request, $type) {
//
//    error_log('acf transform test...');
//
//    if (isset($data['gallery']) && is_array($data['gallery'])) {
//        $data['gallery'] = array_map(function ($id) {
//            return wp_get_attachment_url($id);
//        }, $data['gallery']);
//    }
//    return $data;
//}, 10, 3);
