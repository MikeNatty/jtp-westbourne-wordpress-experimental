import { useLayoutEffect, useRef } from 'react';

type PaginationProps = {
  totalItems: number;
  itemsPerPage: number;
  currentPage: number;
  onPageChange: (page: number) => void;
};

// uaw-pagination wrapper component serving as a React component for easier usage
function Pagination({
  totalItems,
  itemsPerPage,
  currentPage,
  onPageChange,
}: PaginationProps) {
  const paginationRef = useRef<HTMLElement>(null);

  useLayoutEffect(() => {
    if (paginationRef.current) {
      paginationRef.current.addEventListener(
        'pageChange',
        ({ detail: { page } }: CustomEvent<{ page: number }>) => {
          onPageChange(page);
        }
      );
    }
  }, [paginationRef, onPageChange]);

  return (
    <uaw-pagination
      total-items={totalItems}
      items-per-page={itemsPerPage}
      current-page={currentPage}
      ref={paginationRef}
    ></uaw-pagination>
  );
}

export default Pagination;
