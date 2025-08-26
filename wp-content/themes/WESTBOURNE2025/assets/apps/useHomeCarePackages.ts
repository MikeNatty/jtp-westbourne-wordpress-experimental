import {
  useWordPressPosts,
  WPQueryParams,
  WPPostData,
} from '@apps/useWordPressPosts';

export type HomeCarePackage = WPPostData<{
  description: string;
  'call-to-action': string;
  'call-to-action-url': string;
  featured_image_url: string;
  services: {
    id: number;
    title: string;
  }[];
}>;

export function useHomeCarePackages({
  queryParams,
}: {
  queryParams: WPQueryParams;
}) {
  return useWordPressPosts<HomeCarePackage>({
    postType: 'home-care-packages',
    queryParams: {
      page: queryParams.page,
      per_page: queryParams.per_page,
    },
  });
}
