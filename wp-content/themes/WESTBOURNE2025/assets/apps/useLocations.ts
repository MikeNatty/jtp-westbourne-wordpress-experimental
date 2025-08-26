import {
  useWordPressPosts,
  WPQueryParams,
  WPPostData,
} from '@apps/useWordPressPosts';

export type LocationCategory =
  | 'home-community'
  | 'retirement-living'
  | 'residential-care'
  | 'health-wellness';

export type LocationState = 'VIC' | 'TAS';

export function isLocationState(value: string): value is LocationState {
  const match: LocationState[] = ['VIC', 'TAS'];

  return match.includes(value as LocationState);
}

export function isLocationCategory(value: string): value is LocationCategory {
  const match: LocationCategory[] = [
    'home-community',
    'retirement-living',
    'residential-care',
    'health-wellness',
  ];

  return match.includes(value as LocationCategory);
}

export type Location = WPPostData<{
  address: {
    address: string;
    lat: number;
    lng: number;
    zoom: number;
    place_id: string;
    street_number: number;
    street_name: string;
    street_name_short: string;
    city: string;
    state: string;
    state_short: string;
    country: string;
    country_short: string;
    post_code: number;
  };
  service_areas: string[];
  phone: string;
  email?: string;
  website?: string;
  services: {
    id: number;
    title: string;
  }[];
  gallery?:
    | {
        image: string;
        caption: string;
      }[]
    | null;
}> & {
  'location-categories': Array<{
    term_id: number;
    name: string;
    slug: LocationCategory;
    term_group: number;
    term_taxonomy_id: number;
    taxonomy: string;
    description: string;
    parent: number;
    count: number;
    filter: string;
  }>;
};

export function useLocaitons({ queryParams }: { queryParams: WPQueryParams }) {
  return useWordPressPosts<Location>({
    postType: 'locations',
    queryParams: {
      page: queryParams.page,
      per_page: queryParams.per_page,
    },
  });
}
