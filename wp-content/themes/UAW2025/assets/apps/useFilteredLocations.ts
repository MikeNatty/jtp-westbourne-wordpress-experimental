import { useState, useEffect, useMemo } from 'react';
import { Location, LocationCategory, LocationState } from '@apps/useLocations';

type RequireAtLeastOne<T> = {
  [K in keyof T]-?: Required<Pick<T, K>> &
    Partial<Pick<T, Exclude<keyof T, K>>>;
}[keyof T];

export type LocationFilter = {
  state: LocationState | null;
  category: LocationCategory | null;
  googleMaps: RequireAtLeastOne<{
    bounds: google.maps.LatLngBounds | null;
    nearBy: {
      coordinates: Coordinate;
      radius: number;
    } | null;
  }>;
};

interface Coordinate {
  lat: number;
  lng: number;
}

type useFilteredLocationsReturn = {
  filteredLocations: Location[] | null;
  visibleLocations: Location[] | null;
  isLoading: boolean;
  error: Error | null;
};

export function useFilteredLocations({
  locations,
  filter: {
    state: filterState,
    category: fitlerCategory,
    googleMaps: {
      bounds: googleMapsFilterBounds,
      nearBy: googleMapsFilterNearBy,
    },
  },
}: {
  locations: Location[] | null;
  filter: LocationFilter;
}): useFilteredLocationsReturn {
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<Error | null>(null);

  const [locationsInStateMap, setLocationsInStateMap] = useState<
    Map<number, boolean>
  >(new Map());

  useEffect(() => {
    if (!locations) return;

    const newMap = new Map<number, boolean>();

    if (filterState) {
      setIsLoading(true);

      Promise.all(
        locations.map(async (location) => {
          const key = location.id;

          // avoid duplication
          if (newMap.has(key)) {
            return newMap.get(key)!;
          }

          try {
            const isInState = location.address.state_short === filterState;

            newMap.set(key, isInState);
          } catch (error: unknown) {
            if (error instanceof Error) {
              setError(error);
            }
          }
        })
      ).then(() => {
        setIsLoading(false);
        setLocationsInStateMap(newMap);
      });
    } else {
      setLocationsInStateMap(new Map());
    }
  }, [locations, filterState]);

  const filteredLocations = useMemo(() => {
    if (!locations) return null;
    let result = locations;

    if (filterState) {
      result = result.filter((location) => {
        const value = locationsInStateMap.get(location.id);

        return value ?? false;
      });
    }

    if (fitlerCategory) {
      result = result.filter((location) =>
        location['location-categories'].some(
          (category) => category.slug === fitlerCategory
        )
      );
    }

    if (googleMapsFilterNearBy) {
      result = result.filter((service) => {
        const distance = google.maps.geometry.spherical.computeDistanceBetween(
          new google.maps.LatLng(
            googleMapsFilterNearBy.coordinates.lat,
            googleMapsFilterNearBy.coordinates.lng
          ),
          new google.maps.LatLng(service.address.lat, service.address.lng)
        );

        return distance <= googleMapsFilterNearBy.radius;
      });
    }

    return result;
  }, [
    filterState,
    fitlerCategory,
    locationsInStateMap,
    googleMapsFilterNearBy,
  ]);

  const visibleLocations = useMemo(() => {
    if (!filteredLocations) return null;
    if (!googleMapsFilterBounds) return filteredLocations;

    return filteredLocations.filter((location) =>
      googleMapsFilterBounds.contains({
        lat: location.address.lat,
        lng: location.address.lng,
      })
    );
  }, [googleMapsFilterBounds, filteredLocations]);

  return {
    filteredLocations,
    visibleLocations,
    isLoading,
    error,
  };
}
