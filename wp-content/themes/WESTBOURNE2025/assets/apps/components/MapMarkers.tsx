import { AdvancedMarker, useMap, InfoWindow } from '@vis.gl/react-google-maps';
import { MarkerClusterer } from '@googlemaps/markerclusterer';
import type { Marker } from '@googlemaps/markerclusterer';
import { useState, useRef, useEffect, useCallback } from 'react';
import React from 'react';
import { Location } from '@apps/useLocations';

type Poi = {
  key: string;
  coordinates: google.maps.LatLngLiteral;
  location: Location;
};

type MapMarkersProps = {
  pois: Poi[];
  renderInfoWindow?: (poi: Poi) => React.ReactNode;
  infoWindowShownStateActions: [
    boolean,
    React.Dispatch<React.SetStateAction<boolean>>,
  ];
};

// Clustered Google Map markers
function MapMarkers({
  pois,
  renderInfoWindow,
  infoWindowShownStateActions,
}: MapMarkersProps) {
  const map = useMap();
  const [markers, setMarkers] = useState<{ [key: string]: Marker }>({});
  const clusterer = useRef<MarkerClusterer | null>(null);
  const [selectedPoi, setSelectedPoi] = useState<Poi | null>(null);
  const [infoWindowShown, setInfoWindowShown] = infoWindowShownStateActions;

  // Initialize MarkerClusterer, if the map has changed
  useEffect(() => {
    if (!map) {
      return;
    }

    if (!clusterer.current) {
      clusterer.current = new MarkerClusterer({
        map,
        renderer: {
          render: ({ count, position }) => {
            const pin = document.createElement('svg');

            pin.innerHTML = `<svg width="40" height="40" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
              <circle cx="50" cy="50" r="45" fill="#7A6BF7"/>
              <text x="50" y="50" text-anchor="middle" dy=".3em" fill="white" font-size="40">${count}</text>`;

            return new google.maps.marker.AdvancedMarkerElement({
              position,
              content: pin,
            });
          },
        },
      });
    }
  }, [map]);

  // Update markers, if the markers array has changed
  useEffect(() => {
    if (!clusterer.current) return;

    clusterer.current.clearMarkers();
    clusterer.current.addMarkers(Object.values(markers));
  }, [markers]);

  const setMarkerRef = useCallback((marker: Marker | null, key: string) => {
    setMarkers((prev) => {
      // If marker exists and isn't already in state, add it
      if (marker && !prev[key]) {
        return { ...prev, [key]: marker };
      }

      // If marker is null and exists in state, remove it
      if (!marker && prev[key]) {
        const { [key]: _, ...rest } = prev;

        return rest;
      }

      // No change needed
      return prev;
    });
  }, []);

  const _onMarkerClick = useCallback(
    (poi: Poi) => {
      setSelectedPoi(poi);

      if (poi.key !== selectedPoi?.key) {
        setInfoWindowShown(true);
      } else {
        setInfoWindowShown((isShown) => !isShown);
      }
    },
    [selectedPoi?.key, setInfoWindowShown]
  );

  const handleInfowindowCloseClick = useCallback(
    () => setInfoWindowShown(false),
    [setInfoWindowShown]
  );

  return (
    <>
      {pois.map((poi: Poi) => (
        <MapMarker
          key={poi.key}
          poi={poi}
          onClick={_onMarkerClick}
          setMarkerRef={setMarkerRef}
        />
      ))}
      {infoWindowShown &&
        selectedPoi &&
        renderInfoWindow &&
        pois.some((poi) => poi.key === selectedPoi.key) && (
          <InfoWindow
            anchor={markers[selectedPoi.key]}
            pixelOffset={[0, -2]}
            onCloseClick={handleInfowindowCloseClick}
          >
            {renderInfoWindow(selectedPoi)}
          </InfoWindow>
        )}
    </>
  );
}

export type MapMarkerProps = {
  poi: Poi;
  onClick: (poi: Poi) => void;
  setMarkerRef: (marker: Marker | null, key: string) => void;
};

function MapMarker({ poi, onClick, setMarkerRef }: MapMarkerProps) {
  const handleClick = useCallback(() => onClick(poi), [onClick, poi]);

  const ref = useCallback(
    (marker: google.maps.marker.AdvancedMarkerElement) =>
      setMarkerRef(marker, poi.key),
    [setMarkerRef, poi.key]
  );

  return (
    <AdvancedMarker
      key={poi.key}
      position={poi.coordinates}
      ref={ref}
      onClick={handleClick}
    >
      <div className="map-pin"></div>
    </AdvancedMarker>
  );
}

export default MapMarkers;
