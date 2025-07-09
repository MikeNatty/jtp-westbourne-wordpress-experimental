<?php

/**
 * Extract alt_text_accessibility metadata from an image file
 * 
 * @param string $file_path Path to the image file
 * @return string|null The alt_text_accessibility value or null if not found
 */
function return_alt_text_accessibility($attachment_id) {
    if (!wp_attachment_is_image($attachment_id)) return null;
    
    $file_path = wp_get_attachment_url( $attachment_id );
    $file_contents = file_get_contents( $file_path );

    // Find the start and end positions of the XMP metadata
    $xmp_start = strpos( $file_contents, '<x:xmpmeta' );
    $xmp_end = strpos( $file_contents, '</x:xmpmeta>');
    if(!$xmp_start && !$xmp_end) return null;

    // Extract the XMP metadata from the JPEG contents
    $xmp_data = substr( $file_contents, $xmp_start, $xmp_end - $xmp_start + 12 );
    if(!$xmp_data) return null;

    // Parse the XMP metadata using DOMDocument
    $doc = new DOMDocument();
    $doc->loadXML( $xmp_data );

    // Instantiate an XPath object, used to extract portions of the XMP.
    $xpath = new DOMXPath( $doc );

    // Register the relevant XML namespaces.
    $xpath->registerNamespace( 'x', 'adobe:ns:meta/' );
    $xpath->registerNamespace( 'rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#' );
    $xpath->registerNamespace( 'Iptc4xmpCore', 'http://iptc.org/std/Iptc4xmpCore/1.0/xmlns/' );

    $node_list = $xpath->query( '/x:xmpmeta/rdf:RDF/rdf:Description/Iptc4xmpCore:AltTextAccessibility' );
    if ( $node_list && $node_list->count() ) {
        // Get the alt text accessibility alternative most appropriate for the site language.

        $node = $node_list->item( 0 );

        // Get the site's locale.
        $locale = get_locale();

        // There are 3 possibilities:
        //
        // 1. there is an rdf:li with an exact match on the site locale
        // 2. there is an rdf:li with a partial match on the site locale (e.g., site locale is en_US and rdf:li has @xml:lang="en")
        // 3. there is an rdf:li with an "x-default" lang.
        //
        // we evaluate them in that order, stopping when we have a match.
        $value = $xpath->evaluate( "string( rdf:Alt/rdf:li[ @xml:lang = '{$locale}' ] )", $node );
        if ( ! $value ) {
                $value = $xpath->evaluate( 'string( rdf:Alt/rdf:li[ @xml:lang = "' . substr( $locale, 0, 2 ) . '" ] )', $node );
                if ( ! $value ) {
                        $value = $xpath->evaluate( 'string( rdf:Alt/rdf:li[ @xml:lang = "x-default" ] )', $node );
                }
        }
        return $value;
    }

    return null;
}

/**
 * Automatically set alt text when an image is uploaded
 *
 * @param int $attachment_id The ID of the newly uploaded attachment, passed automatically via 'add_attachment' hook
 */
function auto_set_image_alt_text($attachment_id) {
    if (!wp_attachment_is_image($attachment_id)) return;
    
    $alt_text = return_alt_text_accessibility($attachment_id);
    if($alt_text) update_post_meta($attachment_id, '_wp_attachment_image_alt', sanitize_text_field($alt_text));
    
}
add_action('add_attachment', 'auto_set_image_alt_text');