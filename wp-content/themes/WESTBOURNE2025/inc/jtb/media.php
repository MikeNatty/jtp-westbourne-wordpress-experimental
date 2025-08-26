<?php

// TODO

if (is_admin()) {
    // Image Quality
    add_filter('jpeg_quality', function ($arg) {
        return 100;
    });
    add_filter('wp_editor_set_quality', function ($arg) {
        return 100;
    });

    // Image upload max dimensions 4000px
    add_filter('big_image_size_threshold', '__return_false');
    add_filter("big_image_size_threshold", function () {
        return 4000;
    }, 999, 1);

    // Upload max size - 5mb
    add_filter("upload_size_limit", function ($size) {
        $size = 1024 * 15000;
        return $size;
    });

    // // Featured Image Instructions
    // add_filter('admin_post_thumbnail_html', function ($content, $post_id, $thumbnail_id) {
    //     $help_text = '<p>Suggested minimum image width: 1872px</p>';
    //     return $help_text . $content;
    // }, 10, 3);

    // // Remove default thumbnails to save space (we are only using Timber generated images)
    // add_action("intermediate_image_sizes", function ($sizes) {
    //     return array_diff($sizes, ['medium_large']);
    // });
    // add_action("intermediate_image_sizes_advanced", function ($sizes) {
    //     unset($sizes['thumbnail']);
    //     unset($sizes['medium']);
    //     unset($sizes['medium_large']);
    //     unset($sizes['large']);
    //     unset($sizes['1536x1536']);
    //     unset($sizes['2048x2048']);
    //     return $sizes;
    // });
    // add_action("init", function ($sizes) {
    //     // Remove 1536x1536 and 2048x2048
    //     remove_image_size("post-thumbnail");
    //     remove_image_size("1536x1536");
    //     remove_image_size("2048x2048");

    //     // Change existing sizes
    //     update_option("thumbnail_size_w", 150);
    //     update_option("thumbnail_size_h", 0);
    //     update_option("thumbnail_crop", 0);
    //     update_option("medium_size_w", 150);
    //     update_option("medium_size_h", 0);
    //     update_option("large_size_w", 150);
    //     update_option("large_size_h", 0);

    //     // Add new sizes
    //     add_image_size("cms_thumb", 150, 0);
    // });

    // Allow SVG upload
    add_filter('upload_mimes', function ($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    });

    // // Resize SVG in media library
    // add_action("admin_head", function () {
    //     echo '<style type="text/css">.attachment-60x60[src$=".svg"], .attachment-266x266[src$=".svg"], .thumbnail[src$=".svg"] { width: 100%; height: auto; max-width: 300px !important; }</style>';
    // });
}
