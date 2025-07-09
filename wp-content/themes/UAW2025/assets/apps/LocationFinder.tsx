// import Input from '@apps/components/Input';
import Select from '@apps/components/Select';
import { SwiperCard } from '@/assets/apps/components/SwiperCard';
import { LocationsMap } from '@/assets/apps/components/LocationsMap';
import React, { useCallback, useEffect, useRef } from 'react';
import Pagination from '@apps/components/Pagination';
import {
  GoogleAutoComplete,
  GoogleAutoCompletePlace,
} from '@apps/components/GoogleAutoComplete';
import { useFilteredLocations, LocationFilter } from './useFilteredLocations';
import { SkeletonCard } from '@apps/components/Skeletons';
import { MapCameraChangedEvent } from '@vis.gl/react-google-maps';
import { useViewport } from '@apps/ViewportProvider';
import {
  LocationCategory,
  LocationState,
  useLocaitons,
} from '@apps/useLocations';

import { useState, RefObject } from 'react';
import { create } from 'ansi-colors';

const locationFilterDefaultState: LocationFilter = {
  state: null,
  category: null,
  googleMaps: {
    bounds: null,
    nearBy: null,
  },
};

function getStateLatLng(state: LocationState) {
  switch (state) {
    case 'VIC':
      return { lat: -37.8136, lng: 144.9631 };
    case 'TAS':
      return { lat: -41.4545, lng: 145.9704 };
  }
}

const SEARCH_RADIUS = 10000; // 10km radius
const ITEMS_PER_PAGE = 6;

const locationCategorieOptions: {
  value: LocationCategory;
  label: string;
}[] = [
  {
    value: 'health-wellness',
    label: 'Health and Wellness',
  },
  {
    value: 'retirement-living',
    label: 'Retirement Living',
  },
  {
    value: 'residential-care',
    label: 'Residential Care',
  },
  {
    value: 'home-community',
    label: 'Home and Community',
  },
];

const DEFAULT_MAP_CENTER = {
  lat: -25.2744,
  lng: 133.7751,
};
const DEFAULT_MAP_ZOOM = 4;

type LocationFinderProps = {
  defaultLocationCategory?: LocationCategory;
  defaultState?: LocationState;
};

export function LocationFinder({
  defaultLocationCategory,
  defaultState,
}: LocationFinderProps) {
  const contentSectionRef = React.useRef<HTMLDivElement>(null);
  const [infoWindowShown, setInfoWindowShown] = useState(false);

  const locationFilterInitialState: LocationFilter = React.useMemo(() => {
    return {
      ...locationFilterDefaultState,
      category: defaultLocationCategory ?? null,
      state: defaultState ?? null,
    };
  }, [defaultLocationCategory, defaultState]);

  const [locationFilter, setLocationFilter] = React.useState<LocationFilter>(
    locationFilterInitialState
  );
  const [userInput, setUserInput] = React.useState('');

  const [mapCenter, setMapCenter] =
    React.useState<google.maps.LatLngLiteral | null>(DEFAULT_MAP_CENTER);
  const [mapZoom, setMapZoom] = React.useState<number | null>(DEFAULT_MAP_ZOOM);

  const {
    data: locationsData,
    isLoading: isLocationsLoading,
    error: locationsError,
    refetch: refetchLocations,
  } = useLocaitons({ queryParams: { per_page: -1 } });

  const [currentPage, setCurrentPage] = React.useState(1);
  const [isMapExpanded, setIsMapExpanded] = React.useState(false);
  const { isMinimumTabletHorizontal } = useViewport();

  const locations = React.useMemo(
    () => locationsData?.posts || null,
    [locationsData]
  );

  React.useEffect(() => {
    if (
      !isMinimumTabletHorizontal &&
      locationFilter.googleMaps.bounds !== null
    ) {
      setLocationFilter({
        ...locationFilter,
        googleMaps: {
          ...locationFilter.googleMaps,
          bounds: null,
        },
      });
      setCurrentPage(1);
    }
  }, [
    isMinimumTabletHorizontal,
    locationFilter,
    setLocationFilter,
    setCurrentPage,
    currentPage,
  ]);

  React.useEffect(() => {
    refetchLocations();
  }, [refetchLocations]);

  const {
    filteredLocations,
    visibleLocations,
    isLoading: isFilteredLocationsLoading,
    error: filteredLocationsError,
  } = useFilteredLocations({
    locations,
    filter: locationFilter,
  });

  const filteredCardPaginatedData = React.useMemo(() => {
    if (!visibleLocations) return [];

    return visibleLocations.slice(
      (currentPage - 1) * ITEMS_PER_PAGE,
      currentPage * ITEMS_PER_PAGE
    );
  }, [visibleLocations, currentPage]);

  const handleCameraChanged = (event: MapCameraChangedEvent) => {
    // Map is not available on mobile
    if (!isMinimumTabletHorizontal) {
      return;
    }

    const bounds = event.map.getBounds();

    if (bounds) {
      setLocationFilter({
        ...locationFilter,
        googleMaps: {
          ...locationFilter.googleMaps,
          bounds,
        },
      });
    }

    setMapCenter(event.detail.center);
    setMapZoom(event.detail.zoom);
    setCurrentPage(1);
  };

  const handlePlaceChanged = useCallback(
    async (value: GoogleAutoCompletePlace | null) => {
      setInfoWindowShown(false);

      if (value === null) {
        setLocationFilter({
          ...locationFilter,
          googleMaps: {
            ...locationFilter.googleMaps,
            nearBy: null,
          },
        });

        return;
      }

      const { suburb, postcode, country } = value;
      const searchTerm = `${suburb}${postcode ? ` ${postcode}` : ''}, ${country}`;

      const geocoder = new google.maps.Geocoder();

      try {
        const result = await geocoder.geocode({
          address: searchTerm,
        });

        if (result.results[0]?.geometry?.location) {
          const location = result.results[0].geometry.location;

          setLocationFilter({
            ...locationFilter,
            // Using nearby filter resets state filter due to conflicting logics
            state: null,
            googleMaps: {
              ...locationFilter.googleMaps,
              nearBy: {
                coordinates: {
                  lat: location.lat(),
                  lng: location.lng(),
                },
                radius: SEARCH_RADIUS,
              },
            },
          });

          setMapCenter({
            lat: location.lat(),
            lng: location.lng(),
          });
          setMapZoom(10);
          setCurrentPage(1);
        }
      } catch (error) {
        console.error('Geocoding error:', error);
      }
    },
    [locationFilter]
  );

  const isLoading = isFilteredLocationsLoading || isLocationsLoading;
  const error = filteredLocationsError || locationsError;

  const hasEmptyFilteredLocations =
    !isLoading && !error && filteredLocations && filteredLocations.length === 0;

  const hasEmptyVisibleServices =
    !isLoading &&
    !error &&
    !hasEmptyFilteredLocations &&
    visibleLocations &&
    visibleLocations.length === 0;

  const SkeletonCards = React.useMemo(() => {
    return Array.from({ length: 6 }).map((_, index) => (
      <SkeletonCard direction="column" key={index} />
    ));
  }, []);

  /**
   * Bottom sheet UI for filters and minimized filters bar mode
   */
  // :: HACK :: useState is not persisting minimized state across resize events
  // as a workaround, we use useRef to store the state, which is persisted
  const _isFilterBarMinimized = useRef(null);
  const _isBottomSheetOpen = useRef(null);

  const [isFilterBarMinimized, setIsFilterBarMinimized] = useState(
    _isFilterBarMinimized.current
  );
  const [isBottomSheetOpen, setIsBottomSheetOpen] = useState(
    _isBottomSheetOpen.current
  );
  const refBottomSheet = useRef<HTMLDivElement>(null);
  const refFilterBar = useRef<HTMLDivElement>(null);
  const refResetButton = useRef<HTMLDivElement>(null);
  // Delay interval to run window resize handler to avoid multiple calls
  const resizeDelay: RefObject<number | undefined> = useRef(undefined);
  const selectRefState: RefObject<Choices> = useRef<Choices>(null);
  const selectRefType: RefObject<Choices> = useRef<Choices>(null);

  useEffect(() => {
    checkFilterBarMinimize();
  }, []);

  function handleShowFiltersClick(event: React.MouseEvent<HTMLButtonElement>) {
    openFiltersSheet();
  }

  function handleCloseBottomSheetClick(
    event: React.MouseEvent<HTMLButtonElement>
  ) {
    closeFiltersSheet();
  }

  function layoutChoices() {
    // :: NOTE :: Not very extensible code, but works for now
    // There are only 2 possible positions for the filter bar
    // Below for desktop as per designs and above for the minimized
    // bottom sheet on narrow viewports
    // const choices = [selectRefState?.current, selectRefType?.current];
    const selectRefs = [
      selectRefState?.current?.select?.current,
      selectRefType?.current?.select?.current,
    ];

    selectRefs.forEach((selectEl) => {
      // if( isBottomSheetOpen ) {
      if (_isBottomSheetOpen.current) {
        window.ChoicesUtils.positionAbove(selectEl);
      } else {
        window.ChoicesUtils.positionBelow(selectEl);
      }
    });
  }

  useEffect(() => {
    // :: HACK :: useState is not persisting minimized state across resize events
    if (isFilterBarMinimized !== null) {
      _isFilterBarMinimized.current = isFilterBarMinimized;
    }
    if (isFilterBarMinimized === false) {
      checkFilterBarMinimize();
    }
  }, [isFilterBarMinimized]);

  useEffect(() => {
    // :: HACK :: useState is not persisting minimized state across resize events
    if (isBottomSheetOpen !== null) {
      _isBottomSheetOpen.current = isBottomSheetOpen;
    }
    layoutChoices();
  }, [isBottomSheetOpen]);

  // If filter bar buttons can't fit in one row, set minimized mode
  const checkFilterBarMinimize = useCallback(() => {
    // No need to check if bottom sheet is open
    if (isBottomSheetOpen) {
      return;
    }

    // We need to remove minimized mode first to check if max mode fits
    const breakpointTablet = 768;
    const viewWidth = window.innerWidth;
    console.log(
      `checkFilterBarMinimize: viewWidth: ${viewWidth} isFilterBarMinimized: ${isFilterBarMinimized}`
    );

    if (viewWidth > breakpointTablet && _isFilterBarMinimized.current) {
      setIsFilterBarMinimized(false);
      return;
    }

    setTimeout(() => {
      // Check if content flows onto two rows
      // :: HACK :: Filter bar height is not reliable so for now, checking y of first and last buttons
      // If not same, then we assume the filter bar flowing onto two rows
      const stateSel = selectRefState.current?.select?.current;
      const selectWrapper =
        stateSel?.parentElement?.parentElement?.parentElement;

      const stateRect = selectWrapper?.getBoundingClientRect();
      const resetRect = refResetButton.current?.getBoundingClientRect();
      const isMultiLine = resetRect.y - stateRect.y > 10;

      if (viewWidth >= breakpointTablet && isMultiLine) {
        //&& !filterBarMinimized
        setIsFilterBarMinimized(true);
      } else if (viewWidth < breakpointTablet) {
        setIsFilterBarMinimized(true);
      } else if (!isMultiLine) {
        setIsFilterBarMinimized(false);
      } else {
        setIsFilterBarMinimized(true);
      }
    }, 200);
  });
  // }

  // Window resize handler - check all dropdown positions to ensure within view
  React.useEffect(() => {
    function handleResize() {
      clearTimeout(resizeDelay.current);

      resizeDelay.current = setTimeout(() => {
        checkFilterBarMinimize();
        layoutChoices();
      }, 300);
    }

    window.addEventListener('resize', handleResize);

    return () => window.removeEventListener('resize', handleResize);
  }, []);

  function openFiltersSheet() {
    setIsBottomSheetOpen(true);

    // layoutChoices()
  }

  function closeFiltersSheet() {
    // console.log('close sheet');
    setIsBottomSheetOpen(false);
  }

  function clearSelects() {
    const choiceState = selectRefState.current; //?.choice?.current;
    const choiceType = selectRefType.current; // ?.choice?.current;
    if (choiceState) {
      choiceState.reset();
    }
    if (choiceType) {
      choiceType.reset();
    }
    layoutChoices();
  }

  /**
   *  End Bottom sheet UI for filters and minimized filters bar mode
   */

  const getImages = (location) => {
    let images = [];

    if (location.gallery && location.gallery.length) {
      images = location.gallery.map((item) => {
        return item[0];
      });
    } else if (location.featured_image_url) {
      images = [location.featured_image_url];
    } else {
      // Return a placeholder image from assets
      images = ['/wp-content/themes/UAW2025/assets/images/placeholder.jpg'];
    }

    return images;
  };

  const dropdownState = (
    <div className="select-wrapper">
      <label htmlFor="state-select">States:</label>
      <Select
        id="state-select"
        value={locationFilter.state ?? ''}
        ariaLabel="Select state"
        label="Select state"
        ref={selectRefState}
        onChange={(state) => {
          setLocationFilter({
            ...locationFilter,
            state,
            googleMaps: {
              ...locationFilter.googleMaps,
              // Using state filter resets nearby filter due to conflicting logics
              nearBy: null,
            },
          });

          setMapCenter(getStateLatLng(state));
          setMapZoom(7);
          setInfoWindowShown(false);
          // Reset user input to clear the search term
          setUserInput('');
          setCurrentPage(1);
        }}
        options={[
          {
            value: 'VIC',
            label: 'Victoria',
          },
          {
            value: 'TAS',
            label: 'Tasmania',
          },
        ]}
      />
    </div>
  );

  const dropdownType = (
    <div className="select-wrapper">
      <label htmlFor="type-select">Type:</label>
      <Select
        id="type-select"
        value={locationFilter.category ?? ''}
        ariaLabel="Select location type"
        label="Select location type"
        ref={selectRefType}
        onChange={(locationType) => {
          setLocationFilter({
            ...locationFilter,
            category: locationType,
          });
          setCurrentPage(1);
          setInfoWindowShown(false);
        }}
        options={locationCategorieOptions}
      />
    </div>
  );

  return (
    <>
      <div
        ref={refFilterBar}
        className={`filter-bar ${isFilterBarMinimized ? 'minimized' : ''}`}
      >
        <div className="search-wrapper">
          <div className="search-wrapper-icon" data-icon="search"></div>
          <GoogleAutoComplete
            placeholder="Search"
            ariaLabel="Search"
            onPlaceChanged={handlePlaceChanged}
            onUserInputChange={setUserInput}
            userInput={userInput}
          />
        </div>

        {/* Show dropdown filters here if bottom sheet isn't open */}
        {!isBottomSheetOpen ? dropdownState : null}
        {!isBottomSheetOpen ? dropdownType : null}

        <button
          type="button"
          id={'reset-button'}
          className="button--icon button--circle"
          data-icon="reset-01"
          data-button-theme="white"
          aria-label="Reset filters"
          ref={refResetButton}
          onClick={() => {
            setLocationFilter(locationFilterDefaultState);
            setCurrentPage(1);
            setMapCenter(DEFAULT_MAP_CENTER);
            setMapZoom(DEFAULT_MAP_ZOOM);
            setInfoWindowShown(false);
            clearSelects();
          }}
        >
          Reset filters
        </button>

        <button
          type="button"
          id="filter-button"
          className="button--icon button--circle"
          data-icon="filters-02"
          aria-label="Show filters"
          data-button-theme="white"
          onClick={handleShowFiltersClick}
        >
          Show filters
        </button>
      </div>
      <div
        className="content-section"
        style={{ scrollMarginTop: '150px' }}
        ref={contentSectionRef}
        aria-expanded={!isMapExpanded}
      >
        <div
          className="content-section-locations"
          aria-expanded={!isMapExpanded}
          aria-hidden={isMapExpanded}
        >
          {error && <div className="error-message">{error.message}</div>}
          {hasEmptyFilteredLocations && (
            <div className="error-message">Sorry, no results is found</div>
          )}
          {hasEmptyVisibleServices && (
            <div className="error-message">Sorry, no results is visible</div>
          )}
          <div className="content-section-locations-cards">
            {isLoading
              ? SkeletonCards
              : filteredCardPaginatedData.map((location) => {
                  return (
                    <SwiperCard
                      key={location.id}
                      images={getImages(location)}
                      labels={location['location-categories'].map(
                        (category) => category.name
                      )}
                      title={location.title}
                      pills={location.services
                        .filter((service) => service != null)
                        .map((service) => service.title)}
                      link={location.link}
                    />
                  );
                })}
          </div>
          {visibleLocations && visibleLocations.length > ITEMS_PER_PAGE && (
            <Pagination
              totalItems={visibleLocations?.length ?? 0}
              itemsPerPage={ITEMS_PER_PAGE}
              currentPage={currentPage}
              onPageChange={(page) => {
                setCurrentPage(page);

                if (contentSectionRef.current) {
                  contentSectionRef.current.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                  });
                }
              }}
            />
          )}
        </div>

        <div className="content-section-map">
          <div className="map-container">
            <div className="map-controls-expand">
              <button
                id="map-toggle"
                type="button"
                className="button--icon"
                data-icon={isMapExpanded ? 'chevron-right' : 'chevron-left'}
                data-button-theme="white"
                title="Toggle map"
                onClick={() => setIsMapExpanded(!isMapExpanded)}
              >
                Toggle map
              </button>
            </div>
            <LocationsMap
              locations={filteredLocations ?? []}
              onCameraChanged={handleCameraChanged}
              center={mapCenter}
              zoom={mapZoom}
              infoWindowShownStateActions={[
                infoWindowShown,
                setInfoWindowShown,
              ]}
            />
          </div>
        </div>
      </div>

      <div
        id="bottomSheet"
        className={`bottom-sheet ${isBottomSheetOpen ? ' active' : ''}`}
        ref={refBottomSheet}
      >
        <div className={'backing'}></div>
        <div className={'sheet-container'}>
          <div className={'title-bar'}>
            <h1>Filters</h1>
            <button
              className="button--icon close-button"
              data-icon="close-large"
              data-button-theme="white"
              onClick={handleCloseBottomSheetClick}
            ></button>
          </div>
          <div className={'sheet-content'}>
            <div className={'filter-bar'}>
              {/* Show dropdown filters here if bottom sheet is open */}
              {isBottomSheetOpen ? dropdownState : null}
              {isBottomSheetOpen ? dropdownType : null}
              <button
                type="button"
                id="clear-filters-button"
                className="button"
                aria-label="Clear"
                data-button-theme="white"
                onClick={() => {
                  setLocationFilter(locationFilterDefaultState);
                  setCurrentPage(1);
                  setMapCenter(DEFAULT_MAP_CENTER);
                  setMapZoom(DEFAULT_MAP_ZOOM);
                  setInfoWindowShown(false);
                  clearSelects();
                }}
              >
                Reset filters
              </button>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
