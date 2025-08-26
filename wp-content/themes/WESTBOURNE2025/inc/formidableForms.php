<?php
/**
 * Register custom donation shortcodes
 */
function register_donation_shortcodes() {
    add_shortcode('donation-amount', 'donation_amount_shortcode');
    add_shortcode('donation-ref', 'donation_reference_shortcode');
}
add_action('init', 'register_donation_shortcodes');

/**
 * Shortcode to display donation amount from a Formidable Forms entry
 * 
 * @param array $atts Shortcode attributes
 * @return string The formatted donation amount
 */
function donation_amount_shortcode($atts) {
    // Extract and sanitize attributes
    $atts = shortcode_atts(
        array(
            'formid' => '',
        ),
        $atts,
        'donation-amount'
    );
    
    // Get the entry ID from URL parameter
    $entry_id = isset($_GET['eid']) ? sanitize_text_field($_GET['eid']) : '';
    
    // If no entry ID found, return empty
    if (empty($entry_id)) {
        return '<span hidden>Donation reference not found.</span>';
    }
    
    // Get form ID from attributes
    $form_id = intval($atts['formid']);
    if (empty($form_id)) {
        return '<span hidden>Form ID is required.</span>';
    }
    
    // Get the form entry
    $entry = FrmEntry::getOne($entry_id);
    
    // Verify entry exists and belongs to the specified form
    if (!$entry || $entry->form_id != $form_id) {
        return '<span hidden>Form engine or entry not found.</span>';
    }
    
    // Get field value for donation amount (field key: t3sft)
    $donation_amount = '';
    if (function_exists('FrmProEntriesController::get_field_value_shortcode')) {
        $donation_amount = FrmProEntriesController::get_field_value_shortcode(array(
            'field_key' => 't3sft',
            'entry' => $entry
        ));
    }
    
    // Format the amount if it's a number
    if (is_numeric($donation_amount)) {
        // Format with two decimal places and a thousand separator
        $donation_amount = number_format((float)$donation_amount, 2, '.', ',');
        return '$' . $donation_amount;
    }
    
    return $donation_amount;
}

/**
 * Shortcode to display donation reference (entry ID)
 * 
 * @return string The donation reference ID
 */
function donation_reference_shortcode() {
    // Get the entry ID from URL parameter
    $entry_id = isset($_GET['eid']) ? sanitize_text_field($_GET['eid']) : '';
    
    // Return the entry ID as the reference
    return $entry_id;
}