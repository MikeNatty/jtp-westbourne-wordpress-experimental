import React from 'react';
import { SwiperContainer, SwiperSlideProps } from 'swiper/react';

declare module 'react' {
  namespace JSX {
    interface IntrinsicElements {
      'swiper-container': React.DetailedHTMLProps<
        React.HTMLAttributes<HTMLElement>,
        HTMLElement
      > &
        SwiperContainer;
      'swiper-slide': React.DetailedHTMLProps<
        React.HTMLAttributes<HTMLElement>,
        HTMLElement
      > &
        SwiperSlideProps;
      'uaw-pagination': React.DetailedHTMLProps<
        React.HTMLAttributes<HTMLElement>,
        HTMLElement
      >;
    }
  }
}

// Add these type definitions at the top of the file
interface PageChangeEvent extends CustomEvent {
  detail: {
    page: number;
  };
}

declare global {
  interface HTMLElementEventMap {
    pageChange: PageChangeEvent;
  }
}
