// import function to register Swiper custom elements
import { register } from 'swiper/element/bundle';

// register Swiper custom elements
register();

// GSAP scripts
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

document.addEventListener('DOMContentLoaded', () => {
  const stickyNavs = document.querySelectorAll('[data-sticky-nav]');

  stickyNavs.forEach((stickyNav, index, array) => {
    const pinnedContent = stickyNav.querySelector('[data-pinned-content]');

    if (pinnedContent) {
      let endElem = '#page';
      let endPoint = 'bottom top';
      const stickyNavEnd = document.querySelector('[data-sticky-nav-end]');

      // if there is another sticky nav then use that as the endpoint
      if (index < array.length - 1 || stickyNavEnd) {
        endPoint = `top ${pinnedContent.getBoundingClientRect().height}`;
        endElem = index < array.length - 1 ? array[index + 1] : stickyNavEnd;
      }

      const st = ScrollTrigger.create({
        trigger: pinnedContent,
        endTrigger: endElem,
        pin: true,
        start: 'top top',
        end: endPoint,
        anticipatePin: true,
        // markers: true,
        pinSpacing: false,
      });
    }
  });

  const testAnimationContainers = document.querySelectorAll(
    '.test-animation-container'
  );

  testAnimationContainers.forEach((animContainer, index, array) => {
    const animButton = animContainer.querySelector('.play-button');

    animButton.addEventListener('click', function (e) {
      updateElement(animContainer);
    });
  });

  const timeouts = new Map();

  function updateElement(elementId) {
    const element = elementId.querySelector('flynt-component');

    // Clear existing timeout for this element
    if (timeouts.has(elementId)) {
      clearTimeout(timeouts.get(elementId));
    }

    // Remove data attribute
    element.removeAttribute('data-animated');
    element.classList.remove('active');
    element.setAttribute('data-animated', '');

    // Set new timeout and store it
    const newTimeoutId = setTimeout(() => {
      element.classList.add('active');
    }, 500);

    // Save the new timeout ID
    timeouts.set(elementId, newTimeoutId);
  }

  // Generic Scrolltrigger animation classes
  gsap.utils.toArray('[data-animated]').forEach((item) => {
    ScrollTrigger.create({
      trigger: item,
      once: true,
      start: 'top bottom-=150px',
      // markers: true,
      onEnter: () => {
        item.classList.add('active');
      },
    });
  });

  new SplitText('[animation="text-reveal"]', {
    type: 'words',
    wordsClass: 'line-parent',
  });

  new SplitText('[animation="text-reveal"]', {
    type: 'words',
    wordsClass: 'line-child',
  });
});

// document.fonts.ready.then(() => {
//   // split lines
//   new SplitText('[animation="text-reveal"]', {
//     type: 'lines',
//     linesClass: 'line-child',
//   });

//   new SplitText('[animation="text-reveal"]', {
//     type: 'lines',
//     linesClass: 'line-parent',
//   });
// });
