// import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock';
// import delegate from 'delegate-event-listener';
import { buildRefs } from '@/assets/scripts/helpers.js';

import Mmenu from 'mmenu-js';

export default function (el) {
  const refs = buildRefs(el);

  const menu = new Mmenu('#navigationBurger-menu', {
    offCanvas: {
      page: {
        selector: '.pageWrapper',
      },
      position: 'right-front',
    },
    // navbar: {
    //   add: false
    // },
    hooks: {
      'openPanel:after': (panel) => {
        const menuElem = document.querySelector('#navigationBurger-menu');

        if (panel.classList.contains('mainPanel')) {
          menuElem.classList.add('main-panel-active');
        } else {
          menuElem.classList.remove('main-panel-active');
        }
      },
    },
  });

  refs.menuButton.addEventListener('click', (e) => {
    e.preventDefault();
    menu.open();
  });

  const backButton = document.querySelector('.mmenu-back');

  if (backButton) {
    backButton.addEventListener('click', function (e) {
      const openPanel = document.querySelector('.mm-panel--opened');
      if (openPanel) {
        menu.closePanel(openPanel);
      }
    });
  }

  const menuClose = document.querySelector('#menu-close');

  if (menuClose) {
    menuClose.addEventListener('click', (e) => {
      e.preventDefault();
      menu.close();
    });
  }

  // Adding event listeners to the search bar and close button for overlay nav only
  const overlayNav = document.querySelector('.overlay-nav');

  if (overlayNav) {
    const searchBar = overlayNav.querySelector('.button-searchBar');
    const searchBarClose = overlayNav.querySelector('.button-searchBar-close');

    if (searchBar) {
      searchBar.addEventListener('click', (e) => {
        e.preventDefault();
        overlayNav.classList.add('search-bar-active');
      });
    }

    if (searchBarClose) {
      searchBarClose.addEventListener('click', (e) => {
        e.preventDefault();
        overlayNav.classList.remove('search-bar-active');
      });
    }
  }
}
