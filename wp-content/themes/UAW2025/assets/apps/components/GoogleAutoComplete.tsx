import React, { useEffect, useRef } from 'react';
import { useMapsLibrary } from '@vis.gl/react-google-maps';

export type GoogleAutoCompletePlace = {
  suburb: string;
  postcode: string | null;
  state: string;
  country: string;
  label: string;
  lat: number | null;
  lng: number | null;
};

type GoogleAutoCompleteProps = {
  onPlaceChanged: (value: GoogleAutoCompletePlace | null) => void;
  placeholder: string;
  ariaLabel: string;
  userInput: string;
  onUserInputChange: (value: string) => void;
  inputLabelFormat?: 'formattedAddress' | 'suburbPostcode';
};

export function GoogleAutoComplete({
  onPlaceChanged,
  placeholder,
  ariaLabel,
  userInput,
  onUserInputChange,
  inputLabelFormat = 'formattedAddress',
}: GoogleAutoCompleteProps) {
  const inputRef = useRef<HTMLInputElement>(null);
  const placesLibrary = useMapsLibrary('places');
  const lastSelectedPlace = useRef<GoogleAutoCompletePlace | null>(null);

  useEffect(() => {
    if (!placesLibrary || !inputRef.current) return;

    const input = inputRef.current;

    const autocomplete = new google.maps.places.Autocomplete(inputRef.current, {
      componentRestrictions: { country: 'au' },
      types: ['locality', 'postal_code'],
      fields: ['address_components', 'formatted_address', 'geometry'],
    });

    // Prevent form submission on enter
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'Enter') {
        e.preventDefault();
      }
    };
    inputRef.current.addEventListener('keydown', handleKeyDown);

    // Listen for place selection
    google.maps.event.addListener(autocomplete, 'place_changed', () => {
      const place = autocomplete.getPlace();

      let suburb = '';
      let postcode: string | null = null;
      let state = '';
      let country = '';

      if (place.address_components) {
        for (const component of place.address_components) {
          const types = component.types;

          // Get suburb (can be either locality or sublocality)
          if (types.includes('locality') || types.includes('sublocality')) {
            suburb = component.long_name;
          }

          // Get postcode
          if (types.includes('postal_code')) {
            postcode = component.long_name;
          }

          // Get state
          if (types.includes('administrative_area_level_1')) {
            state = component.short_name;
          }

          // Get country
          if (types.includes('country')) {
            country = component.long_name;
          }
        }
      }

      const result = {
        suburb,
        postcode,
        state,
        country,
        lat: place.geometry?.location?.lat() ?? null,
        lng: place.geometry?.location?.lng() ?? null,
        label: (() => {
          switch (inputLabelFormat) {
            case 'formattedAddress':
              if (place.formatted_address) {
                return place.formatted_address;
              }

              return `${suburb}${postcode ? ` ${postcode}` : ''}`;

            case 'suburbPostcode':
              return `${suburb}${postcode ? ` ${postcode}` : ''}`;
          }
        })(),
      };

      lastSelectedPlace.current = result;
      onUserInputChange(result.label);

      onPlaceChanged(result);
    });

    // Cleanup
    return () => {
      input.removeEventListener('keydown', handleKeyDown);

      google.maps.event.clearListeners(autocomplete, 'place_changed');
    };
  }, [placesLibrary, onPlaceChanged, onUserInputChange, inputLabelFormat]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    e.preventDefault();
    const newValue = e.target.value;

    onUserInputChange(newValue);
  };

  const handleBlur = (e: React.FocusEvent<HTMLInputElement>) => {
    const newValue = e.target.value;

    if (!newValue) {
      onPlaceChanged(null);

      return;
    }
  };

  return (
    <input
      ref={inputRef}
      type={'search'}
      placeholder={placeholder}
      value={userInput}
      onChange={handleChange}
      onBlur={handleBlur}
      aria-label={ariaLabel}
      autoComplete="off"
    />
  );
}
