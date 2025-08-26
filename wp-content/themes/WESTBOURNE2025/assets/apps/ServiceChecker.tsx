import {
  GoogleAutoComplete,
  GoogleAutoCompletePlace,
} from '@apps/components/GoogleAutoComplete';
import { useCallback, useMemo, useState } from 'react';

import { useServiceArea, ServiceArea } from '@/assets/apps/useServiceAreas';

import { ExpandableCard } from '@apps/components/ExpandableCard';

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
    <>
      <div className="service-checker-search-container">
        <GoogleAutoComplete
          placeholder="Search"
          ariaLabel="Search"
          onPlaceChanged={handlePlaceChanged}
          onUserInputChange={setSearchUserInput}
          userInput={searchUserInput}
          inputLabelFormat="suburbPostcode"
        />
      </div>

      <main className="service-checker-container">
        <ErrorBoundary fallbackRender={fallbackRender}>
          <ServiceCheckerContent filterState={filterState} />
        </ErrorBoundary>
      </main>
    </>
  );
}

function ServiceCheckerContent({ filterState }: { filterState: FilterState }) {
  const [expandedServiceId, setExpandedServiceId] = useState<string | null>(
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
    isLoading: isServiceAreaLoading,
    data: serviceAreaData,
    refetch: fetchServiceArea,
    error: serviceAreaError,
  } = useServiceArea({
    queryParams: {
      search: filterState?.place?.postcode ?? undefined,
    },
  });

  useEffect(() => {
    console.log(filterState.place);

    if (
      filterState.place === null
      || !(/^(TAS|VIC)$/.test(filterState.place.state))
    ) {
      console.log("failed");

      return;
    }
    fetchServiceArea();
  }, [fetchServiceArea, filterState.place]);

  const error = serviceAreaError ? 'Error fetching data' : null;

  const isLoading = isServiceAreaLoading;

  const serviceArea = useMemo(() => {
    return serviceAreaData?.posts && serviceAreaData.posts.length > 0
      ? serviceAreaData.posts[0]
      : null;
  }, [serviceAreaData]);

  const appRoot = document.getElementById('service-checker');

  useEffect(() => {
    if (
      filterState?.place?.postcode 
      && !isLoading
      && /^(TAS|VIC)$/.test(filterState.place.state)
    ) {
      appRoot?.classList.remove('show-service-area-message');

      if (!serviceArea || !serviceArea.associated_services) {
        appRoot?.classList.add('show-feedback-form');
      } else {
        appRoot?.classList.remove('show-feedback-form');
      }
    }
    else if (
      filterState?.place?.postcode 
      && !isLoading
    ) {
      appRoot?.classList.remove('show-feedback-form');
      appRoot?.classList.add('show-service-area-message');
    }
  }, [appRoot, filterState, isLoading, serviceArea]);

  const renderContent = () => {
    if (error) return <p>Error: {error}</p>;
    if (isLoading) return SkeletonCards;

    return (
      <>
        {serviceArea && (
          <section className="service-checker-home-care">
            <div className="panels">
              {serviceArea.associated_services?.map((item) => (
                <ExpandableCard
                  key={item.title}
                  id={item.title}
                  title={item.title}
                  servicePage={item.servicePage}
                  content={item.description ?? ''}
                  isExpanded={expandedServiceId === item.title}
                  icon={item.icon ?? null}
                  onToggle={(id) => {
                    setExpandedServiceId(expandedServiceId === id ? null : id);
                  }}
                />
              ))}
            </div>
          </section>
        )}
      </>
    );
  };

  return renderContent();
}
