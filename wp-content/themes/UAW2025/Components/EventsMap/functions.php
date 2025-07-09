<?php

namespace Flynt\Components\EventsMap;

use Flynt\FieldVariables;

add_filter('Flynt/addComponentData?name=EventsMap', function ($data) {

    // Load Google Maps API key from theme options
    // $data['apiKey'] = get_field('googleMapsApiKey', 'option');
    
    // TODO - make upadateable field in the CMS with client's api key 
    $data['apiKey'] = 'AIzaSyC4Qm86apT4DHupwh5pn9O97dhIbcpEqug';
    
    // Format location data for frontend use
    if (!empty($data['mapLocation'])) {
        // Ensure we have lat and lng values
        if (isset($data['mapLocation']['lat']) && isset($data['mapLocation']['lng'])) {
            $data['locationJson'] = json_encode([
                'lat' => (float) $data['mapLocation']['lat'],
                'lng' => (float) $data['mapLocation']['lng']
            ]);

            // Create Google Maps directions URL
            $data['directionsUrl'] = sprintf(
                'https://www.google.com/maps/dir/?api=1&destination=%f,%f',
                $data['mapLocation']['lat'],
                $data['mapLocation']['lng']
            );
        }
    }

    return $data;
});
