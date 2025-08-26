import 'vite/modulepreload-polyfill';
import FlyntComponent from './scripts/FlyntComponent';
import './scripts/UawPagination.js';

import 'lazysizes';

import './scripts/global.js';

if (import.meta.env.DEV) {
  import('@vite/client');
}

import.meta.glob([
  '../Components/**',
  '../assets/**',
  '!**/*.js',
  '!**/*.scss',
  '!**/*.php',
  '!**/*.twig',
  '!**/screenshot.png',
  '!**/*.md',
]);

window.customElements.define('flynt-component', FlyntComponent);
