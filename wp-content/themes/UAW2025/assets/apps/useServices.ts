import {
  useWordPressPosts,
  WPQueryParams,
  WPPostData,
} from '@apps/useWordPressPosts';

export type Service = WPPostData<{
  description: string;
}>;

export function useServices({ queryParams }: { queryParams: WPQueryParams }) {
  return useWordPressPosts<Service>({
    postType: 'services',
    queryParams: {
      page: queryParams.page,
      per_page: queryParams.per_page,
    },
  });
}
