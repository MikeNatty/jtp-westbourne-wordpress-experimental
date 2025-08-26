import { Map, MapCameraChangedEvent } from '@vis.gl/react-google-maps';
import { Location } from '@/assets/apps/useLocations';
import MapMarkers from './MapMarkers';
import { useMemo } from 'react';
import { SwiperCard } from '@apps/components/SwiperCard';
import React from 'react';

export function LocationsMap({
  locations,
  onCameraChanged,
  center: mapCenter,
  zoom: mapZoom,
  gestureHandling,
  infoWindowShownStateActions,
}: {
  locations: Location[];
  onCameraChanged?: (event: MapCameraChangedEvent) => void;
  center?: google.maps.LatLngLiteral | null;
  zoom?: number | null;
  gestureHandling?: 'greedy' | 'cooperative' | 'none';
  infoWindowShownStateActions: [
    boolean,
    React.Dispatch<React.SetStateAction<boolean>>,
  ];
}) {
  const pois = useMemo(() => {
    return locations.map((location) => ({
      key: location.id.toString(),
      coordinates: {
        lat: location.address.lat,
        lng: location.address.lng,
      },
      location,
    }));
  }, [locations]);

  const getImages = ( location ) => {
    let images = [];

    if( location.gallery && location.gallery.length ){
      images = location.gallery.map(( item) => {
        return item[0];
      })
    }
    else if( location.featured_image_url ) {
      images = [location.featured_image_url];
    }
    else {
      // Return a placeholder image from assets
      images = ["/wp-content/themes/UAW2025/assets/images/placeholder.jpg"]
    }

    return images;
  }

  return (
    <Map
      className="map-google"
      mapId={'d364faa73a242124'}
      defaultZoom={4}
      defaultCenter={{ lat: -41.0, lng: 146.5 }}
      onCameraChanged={onCameraChanged}
      center={mapCenter}
      zoom={mapZoom}
      disableDefaultUI
      zoomControl
      gestureHandling={gestureHandling}
    >
      <MapMarkers
        pois={pois}
        renderInfoWindow={({ location }) => (
          <div className="map-info-window">x
            <SwiperCard
              key={location.id}
              images={getImages( location )}
              labels={location['location-categories'].map(
                (category) => category.name
              )}
              title={location.title}
              pills={location.services
                .filter(service => service != null)
                .map((service) => service.title)
              }
              link={location.link}
            />
          </div>
        )}
        infoWindowShownStateActions={infoWindowShownStateActions}
      />
    </Map>
  );
}
