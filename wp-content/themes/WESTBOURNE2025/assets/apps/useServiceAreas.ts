import {
  useWordPressPosts,
  WPQueryParams,
  WPPostData,
} from '@apps/useWordPressPosts';

export type ServiceArea = WPPostData<{
  provider: {
    id: number;
    title: string;
  };
  associated_services: {
    title: string;
    description: string | null;
    icon: string | null;
    servicePage: string | null;
  }[];
}>;

export function useServiceArea({ queryParams }: { queryParams: WPQueryParams }) {
  return useWordPressPosts<ServiceArea>({
    postType: 'service-area',
    queryParams: {
      search: queryParams.search,
    },
  });
}