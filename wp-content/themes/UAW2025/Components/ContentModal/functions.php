<?php

namespace Flynt\Components\ContentModal;

use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=ContentModal', function (array $data): array {
    return $data;
});


// AJAX handler
function getModal()
{
    // Verify nonce
    check_ajax_referer('get_modal_nonce', 'nonce');

    $modal_id = isset($_POST['modal_id']) ? sanitize_text_field($_POST['modal_id']) : '';
    $post = Timber::get_post( $modal_id );

    $context = [
        'post' => $post
    ];
    
    $posts_html = Timber::compile('templates/modal-page.twig', $context);
    
    // Return response
    wp_send_json_success([
        'html' => $posts_html,
    ]);
    
    wp_die();
}

// Register AJAX handlers
add_action('wp_ajax_get_modal', 'Flynt\Components\ContentModal\getModal');
add_action('wp_ajax_nopriv_get_modal', 'Flynt\Components\ContentModal\getModal');

// Register scripts
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'flynt-modal-content',
        get_template_directory_uri() . '/assets/scripts/modalContent.js',
        [],
        '1.0.0',
        true
    );
    
    wp_localize_script('flynt-modal-content', 'flyntModalData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('get_modal_nonce')
    ]);
});