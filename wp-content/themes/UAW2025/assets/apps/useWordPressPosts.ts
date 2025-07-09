import { useContext } from 'react';
import { WordPressContext } from '@apps/WordPressProvider';
import { useQuery } from '@tanstack/react-query';

export interface WPQueryParams {
  search?: string;
  per_page?: number;
  page?: number;
  include?: number[];
}

export type WPPostData<
  ACF extends Record<string, unknown> = Record<string, unknown>,
> = {
  id: number;
  guid: string;
  slug: string;
  link: string;
  title: string;
} & ACF;

export type WPRawPost<
  ACF extends Record<string, unknown> = Record<string, unknown>,
> = {
  id: number;
  guid: {
    rendered: string;
  };
  slug: string;
  link: string;
  title: {
    rendered: string;
  };
  // When there is no field groups in post type, acf is an empty array
  acf: ACF | [];
};

type WpPostResponse<
  ACF extends Record<string, unknown> = Record<string, unknown>,
> = {
  posts: WPPostData<ACF>[];
  totalPages: number;
  totalPosts: number;
};

type useWordPressPostsParams = {
  postType: 'services' | 'home-care-packages' | 'locations' | 'service-area';
  queryParams?: WPQueryParams;
};

// Get posts or custom post types from WordPress
export function useWordPressPosts<Post extends WPPostData>({
  postType,
  queryParams,
}: useWordPressPostsParams) {
  const { url: baseUrl, headers } = useContext(WordPressContext);

  type ACF = Post extends WPPostData<infer T> ? T : Record<string, unknown>;

  const fetchData = async (): Promise<WpPostResponse<ACF>> => {
    try {
      const isRequestingAllPosts = queryParams?.per_page === -1;
      const initialParams = new URLSearchParams();

      // Set initial query params
      if (queryParams) {
        Object.entries(queryParams).forEach(([key, value]) => {
          if (key === 'per_page' && isRequestingAllPosts) {
            initialParams.set(key, '100'); // Set max allowed per_page
          } else if (Array.isArray(value)) {
            initialParams.set(key, value.join(','));
          } else if (value !== undefined) {
            initialParams.set(key, String(value));
          }
        });
      }

      // Make initial request to get total posts
      const initialUrl = `/wp-json/wp/v2/${postType}${initialParams.toString() ? `?${initialParams.toString()}` : ''}`;
      // const initialUrl = `${baseUrl}/wp-json/wp/v2/${postType}${initialParams.toString() ? `?${initialParams.toString()}` : ''}`;
      const initialResponse = await fetch(initialUrl, { headers });

      if (!initialResponse.ok) {
        throw new Error(`HTTP error! status: ${initialResponse.status}`);
      }

      const totalPosts = Number(initialResponse.headers.get('X-WP-Total') || 0);

      const totalPages = Number(
        initialResponse.headers.get('X-WP-TotalPages') || 0
      );

      let allPosts: WPRawPost<ACF>[] = await initialResponse.json();

      // If requesting all posts and there are more pages, fetch them
      if (isRequestingAllPosts && totalPages > 1) {
        const additionalRequests = Array.from(
          { length: totalPages - 1 },
          (_, i) => {
            const pageParams = new URLSearchParams(initialParams);
            pageParams.set('page', String(i + 2)); // Start from page 2
            const pageUrl = `/wp-json/wp/v2/${postType}?${pageParams.toString()}`;
            // const pageUrl = `${baseUrl}/wp-json/wp/v2/${postType}?${pageParams.toString()}`;

            return fetch(pageUrl, { headers }).then((res) => res.json());
          }
        );

        const additionalPosts = await Promise.all(additionalRequests);
        allPosts = [...allPosts, ...additionalPosts.flat()];
      }

      // Flatten and expand acf fields
      const flattenedPosts: WPPostData<ACF>[] = allPosts.map((post) => {
        const sanitisedACF = Array.isArray(post.acf) ? ({} as ACF) : post.acf;

        return {
          ...post,
          id: post.id,
          title: post.title.rendered,
          guid: post.guid.rendered,
          slug: post.slug,
          link: post.link,
          ...sanitisedACF,
        };
      });

      return { posts: flattenedPosts, totalPages, totalPosts };
    } catch (err) {
      throw err instanceof Error ? err : new Error('An error occurred');
    }
  };

  return useQuery({
    queryKey: ['posts', postType, queryParams],
    queryFn: fetchData,
    enabled: false,
  });
}
