import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/dist/ScrollTrigger.js';
import { ScrollToPlugin } from 'gsap/ScrollToPlugin';

gsap.registerPlugin(ScrollToPlugin, ScrollTrigger);

export default function (el) {
  // add show bar class to page wrapper
  ScrollTrigger.create({
    trigger: '#mainContent',
    start: 'top+=1000px 50%',
    endTrigger: '.mainFooter',
    end: 'top bottom',
    toggleClass: {
      targets: '.pageWrapper',
      className: 'showAnchorLinkBar',
    },
  });

  const sections = document.querySelectorAll('flynt-component');
  const anchorNavItems = el.querySelectorAll('a[data-anchor-nav-item]');

  // Add click event listener to each matching element
  anchorNavItems.forEach((anchor) => {
    const anchorName = anchor.getAttribute('href');

    if (anchorName) {
      anchor.addEventListener('click', (event) => {
        // Prevent default anchor behavior (optional)
        event.preventDefault();
        window.history.pushState({}, '', anchorName);

        gsap.to(window, {
          duration: 1.5,
          scrollTo: { y: anchorName, offsetY: 160 },
          ease: 'power2.inOut',
        });
      });
    }
  });

  // Function to update active nav link
  function updateActiveLink(sectionId) {
    // Remove active class from all links
    anchorNavItems.forEach((link) => {
      link.classList.remove('active');
    });

    // Add active class to the corresponding link
    const activeLink = el.querySelector(
      `a[data-anchor-nav-item="${sectionId}"]`
    );

    if (activeLink) {
      activeLink.classList.add('active');
    }
  }

  // Create ScrollTrigger for each section
  sections.forEach((section) => {
    const sectionAnchor = section.querySelector('[data-anchor]');

    if (sectionAnchor !== null) {
      const anchorID = sectionAnchor.getAttribute('data-anchor');

      ScrollTrigger.create({
        trigger: section,
        start: 'top center',
        end: 'bottom center',
        onEnter: () => updateActiveLink(anchorID),
        onEnterBack: () => updateActiveLink(anchorID),
        // markers: true,
      });
    }
  });
}
