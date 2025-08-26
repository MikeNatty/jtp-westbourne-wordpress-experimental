// Superseded by ServiceChecker.tsx
// Old logic for Service Checker based upon 2024 assumptions and logic. Preserved in case part of this functionality is required in future.
import {
  GoogleAutoComplete,
  GoogleAutoCompletePlace,
} from '@apps/components/GoogleAutoComplete';
import { useCallback, useMemo, useState } from 'react';
import { useServices, Service } from '@/assets/apps/useServices';
import {
  useHomeCarePackages,
  HomeCarePackage,
} from '@apps/useHomeCarePackages';
import Select from '@apps/components/Select';
import { ExpandableCard } from '@apps/components/ExpandableCard';
import { useLocaitons } from '@apps/useLocations';
import { HomeCarePackageCard } from '@apps/components/HomeCarePackageCard';
import { SkeletonCard } from '@apps/components/Skeletons';
import { FeedbackForm } from './components/FeedbackForm';
import { useEffect } from 'react';
import { ErrorBoundary, type FallbackProps } from 'react-error-boundary';

function fallbackRender({ error }: FallbackProps) {
  console.warn(error);

  return (
    <>
      <p>Sorry, something went wrong:</p>
      <FeedbackForm />
    </>
  );
}

type FilterState = {
  radius: number;
  place: GoogleAutoCompletePlace | null;
};

const filterStateInitialState: FilterState = {
  radius: 10,
  place: null,
};

export function ServiceChecker() {
  const [searchUserInput, setSearchUserInput] = useState('');

  const [filterState, setFilterState] = useState<FilterState>(
    filterStateInitialState
  );

  const handlePlaceChanged = useCallback(
    (place: GoogleAutoCompletePlace | null) => {
      if (!place) {
        setFilterState({
          ...filterState,
          place: null,
        });

        return;
      }

      setFilterState({
        ...filterState,
        place,
      });
    },
    [filterState]
  );

  return (
    <div className="service-checker">
      <header className="service-checker-header">
        <h3 className="title">Find locations & services near you</h3>
        <p className="subtitle">
          We believe older people, like people of all ages, want to live in an
          environment of choice, empowerment and wellness.
        </p>
        <div className="service-checker-search-container">
          <GoogleAutoComplete
            placeholder="Search"
            ariaLabel="Search"
            onPlaceChanged={handlePlaceChanged}
            onUserInputChange={setSearchUserInput}
            userInput={searchUserInput}
            inputLabelFormat="suburbPostcode"
          />
          <Select
            value={filterState.radius.toString()}
            id="service-checker-radius"
            ariaLabel="Select radius"
            label="Select radius"
            options={[
              { value: '10', label: '10km' },
              { value: '20', label: '20km' },
              { value: '50', label: '50km' },
              { value: '100', label: '100km' },
              { value: '200', label: '200km' },
            ]}
            onChange={(value) => {
              setFilterState({
                ...filterState,
                radius: parseInt(value),
              });
            }}
          />
          {/* 
          Hide for now as it triggers data loads on select
          <button
            type="button"
            data-size="small"
            className="button"
            data-icon-gap="medium"
            data-icon="search"
          >
            Search
          </button> */}
        </div>
      </header>

      <main className="service-checker-container">
        <ErrorBoundary fallbackRender={fallbackRender}>
          <ServiceCheckerContent filterState={filterState} />
        </ErrorBoundary>
      </main>
    </div>
  );
}

function ServiceCheckerContent({ filterState }: { filterState: FilterState }) {
  const [serviceType, setServiceType] = useState<
    'all' | 'at-home-care' | 'in-your-area'
  >('all');

  const [expandedServiceId, setExpandedServiceId] = useState<string | null>(
    null
  );

  const [expandedLocationId, setExpandedLocationId] = useState<string | null>(
    null
  );

  const SkeletonCards = useMemo(() => {
    const cards = [];

    for (let i = 0; i < 4; i++) {
      cards.push(<SkeletonCard direction="row" />);
    }

    return <div className="service-checker-skeleton-cards">{cards}</div>;
  }, []);

  const {
    isLoading: isServicesLoading,
    data: servicesData,
    refetch: fetchServices,
    error: servicesError,
  } = useServices({
    queryParams: {
      per_page: -1,
    },
  });

  const {
    isLoading: isLocationsLoading,
    data: locationsData,
    refetch: fetchLocations,
    error: locationsError,
  } = useLocaitons({
    queryParams: {
      per_page: -1,
    },
  });

  const {
    data: homeCarePackagesData,
    refetch: fetchHomeCarePackages,
    error: homeCarePackagesError,
  } = useHomeCarePackages({
    queryParams: { page: 1, per_page: 10 },
  });

  useEffect(() => {
    if (filterState.place === null) {
      return;
    }
    fetchServices();
    fetchLocations();
    fetchHomeCarePackages();
  }, [fetchHomeCarePackages, fetchLocations, fetchServices, filterState.place]);

  const error =
    servicesError || locationsError || homeCarePackagesError
      ? 'Error fetching data'
      : null;

  const isLoading = isServicesLoading || isLocationsLoading;

  const services = useMemo(() => servicesData?.posts ?? null, [servicesData]);

  const locations = useMemo(
    () => locationsData?.posts ?? null,
    [locationsData]
  );

  // servicesFitleredByPlaceRadius
  const inYourAreaServices: Service[] | null = useMemo(() => {
    const filterStatePlaceLat = filterState.place?.lat;
    const filterStatePlaceLng = filterState.place?.lng;

    if (
      !filterState.place ||
      !locations ||
      !filterStatePlaceLat ||
      !filterStatePlaceLng ||
      !services
    ) {
      return null;
    }

    const locationsFilteredByPlaceRadius = locations.filter((location) => {
      const distance = google.maps.geometry.spherical.computeDistanceBetween(
        new google.maps.LatLng(filterStatePlaceLat, filterStatePlaceLng),
        new google.maps.LatLng(location.address.lat, location.address.lng)
      );

      return distance <= filterState.radius * 1000; // converting to meters
    });

    const servicesIds = new Set<number>(
      locationsFilteredByPlaceRadius.flatMap((location) => {
        return location.services.map((service) => service.id);
      })
    );

    return services.filter((service) => {
      return servicesIds.has(service.id);
    });
  }, [filterState, locations, services]);

  // servicesFilteredByPlace
  const atHomeCareServices = useMemo(() => {
    if (!filterState.place || !services || !locations) {
      return null;
    }

    const serviceIdsFilteredByPlace = new Set(
      locations
        .filter((location) => {
          return location.service_areas.some((area) => {
            return area === filterState.place?.postcode;
          });
        })
        .flatMap((location) => {
          return location.services.map((service) => service.id);
        })
    );

    return services.filter((service) => {
      return serviceIdsFilteredByPlace.has(service.id);
    });
  }, [filterState, services, locations]);

  // homeCarePackagesFitleredByPlace
  const homeCarePackages = useMemo(() => {
    if (!homeCarePackagesData || !atHomeCareServices) {
      return null;
    }

    const filteredHomeCarePackages = homeCarePackagesData.posts.filter(
      (item) => {
        // NOTE: should be OK to remove the type check logic if we trust the integrity the API data
        // that item is not nullish
        if (item.services) {
          return item.services.every((service) =>
            atHomeCareServices.some((s) => s.id === service.id)
          );
        } else {
          return false;
        }
      }
    );

    return filteredHomeCarePackages;
  }, [atHomeCareServices, homeCarePackagesData]);

  const renderContent = () => {
    if (error) {
      return <p>Error: {error}</p>;
    }

    if (isLoading) {
      return SkeletonCards;
    }

    const hasNoResults =
      homeCarePackages?.length === 0 &&
      inYourAreaServices?.length === 0 &&
      atHomeCareServices?.length === 0;

    if (hasNoResults) {
      return <FeedbackForm />;
    }

    return (
      <>
        <div className="service-checker-service-type">
          <p>Service type:</p>
          <button
            type="button"
            data-size="small"
            className="button"
            data-button-theme={serviceType === 'all' ? 'primary' : 'secondary'}
            onClick={() => setServiceType('all')}
          >
            All
          </button>
          <button
            type="button"
            data-size="small"
            className="button"
            data-button-theme={
              serviceType === 'at-home-care' ? 'primary' : 'secondary'
            }
            onClick={() => setServiceType('at-home-care')}
            disabled={
              (!homeCarePackages || homeCarePackages.length === 0) &&
              (!atHomeCareServices || atHomeCareServices.length === 0)
            }
          >
            At Home Care
          </button>
          <button
            type="button"
            data-size="small"
            className="button"
            data-button-theme={
              serviceType === 'in-your-area' ? 'primary' : 'secondary'
            }
            onClick={() => setServiceType('in-your-area')}
            disabled={!inYourAreaServices || inYourAreaServices.length === 0}
          >
            In Your Area
          </button>
        </div>

        {homeCarePackages?.length || atHomeCareServices?.length
          ? (serviceType === 'at-home-care' || serviceType === 'all') && (
              <section className="service-checker-home-care">
                <h4>At Home Care</h4>
                {homeCarePackages?.length ? (
                  <div className="service-checker-home-care-packages">
                    {homeCarePackages?.map((item) => (
                      <HomeCarePackageCard
                        key={item.id}
                        homeCarePackage={item}
                      />
                    ))}
                  </div>
                ) : null}

                {atHomeCareServices?.length ? (
                  <div className="service-checker-home-care-services">
                    {atHomeCareServices?.map((item) => (
                      <ExpandableCard
                        key={item.id}
                        id={item.id.toString()}
                        title={item.title}
                        description={item.description}
                        content={
                          'lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.'
                        }
                        isExpanded={expandedServiceId === item.id.toString()}
                        onToggle={(id) => {
                          setExpandedServiceId(
                            expandedServiceId === id ? null : id
                          );
                        }}
                      />
                    ))}
                  </div>
                ) : null}
              </section>
            )
          : null}

        {inYourAreaServices?.length &&
          (serviceType === 'in-your-area' || serviceType === 'all') && (
            <section className="service-checker-in-your-area">
              <h4>In your area</h4>
              <div className="service-checker-in-your-area-locations">
                {inYourAreaServices?.map((item) => (
                  <ExpandableCard
                    key={item.id}
                    id={item.id.toString()}
                    title={item.title}
                    description={item.description}
                    content={
                      'lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.'
                    }
                    isExpanded={expandedLocationId === item.id.toString()}
                    onToggle={(id) => {
                      setExpandedLocationId(
                        expandedLocationId === id ? null : id
                      );
                    }}
                  />
                ))}
              </div>
            </section>
          )}
      </>
    );
  };

  if (
    !homeCarePackages &&
    !atHomeCareServices &&
    !inYourAreaServices &&
    !isLoading
  ) {
    return null;
  }

  return renderContent();
}
