// import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock';

export default function (el) {
  // const isDesktopMediaQuery = window.matchMedia('(min-width: 1024px)');
  // isDesktopMediaQuery.addEventListener('change', onBreakpointChange);

  // onBreakpointChange();

  // function onBreakpointChange() {
  //   if (isDesktopMediaQuery.matches) {
  //     setScrollPaddingTop();
  //   }
  // }

  // function setScrollPaddingTop() {
  //   const scrollPaddingTop = document.getElementById('wpadminbar')
  //     ? document.getElementById('wpadminbar').offsetHeight
  //     : 0;
  //   document.documentElement.style.scrollPaddingTop = `${scrollPaddingTop}px`;
  // }

  const searchToggle = el.querySelector('.search-toggle');

  // Get references to the form and input elements
  const form = el.querySelector('.searchBar');
  const input = el.querySelector('.searchBar-input');
  const search = el.querySelector('.search');
  const inputWrapper = el.querySelector('.search-wrapper');
  const pageOverlay = el.querySelector('.page-overlay');
  const closeButton = el.querySelector('.close-button');

  if (form) {
    // Handle form submission
    form.addEventListener('submit', (e) => {
      // Prevent empty searches
      if (!input.value.trim()) {
        e.preventDefault();

        return;
      }
    });
  }

  if (closeButton) {
    closeButton.addEventListener('click', (e) => {
      closeSearch();
    });
  }

  // Background clicked
  if (pageOverlay) {
    pageOverlay.addEventListener('click', (e) => {
      closeSearch();
    });
  }

  if (searchToggle) {
    searchToggle.addEventListener('click', () => {
      // Handle menu toggle

      if (el.classList.contains('search-active')) {
        closeSearch();
      } else {
        el.classList.add('search-active');
        search.setAttribute('aria-expanded', true);

        // Prevent page scrolling
        document.body.style.overflow = 'hidden';

        // Focus the input field
        setTimeout(() => {
          if (input) {
            input.focus();
          }
        }, 100);
      }
    });
  }

  function closeSearch() {
    el.classList.remove('search-active');
    search.setAttribute('aria-expanded', false);

    // Re-enable page scrolling
    document.body.style.overflow = 'auto';
  }

  if (input) {
    // Add focus/blur event handlers to change border color
    input.addEventListener('focus', () => {
      inputWrapper.classList.add('is-focused');
    });

    input.addEventListener('blur', () => {
      inputWrapper.classList.remove('is-focused');
    });
  }
}
