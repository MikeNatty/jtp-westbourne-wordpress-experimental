// import { buildRefs } from '@/assets/scripts/helpers.js';

export default function (el) {
  const stickyHeaders = el.querySelectorAll('.sticky-header');

  // Add click event listener to each matching element
  stickyHeaders.forEach((sticky) => {
    const observer = new IntersectionObserver(
      ([e]) => e.target.classList.toggle('is-pinned', e.intersectionRatio < 1),
      { threshold: [1] }
    );
    observer.observe(sticky);
  });

  const modal = el.querySelector('#formModal');
  const allForms = el.querySelectorAll('.form');
  const ctaButtons = document.querySelectorAll('[data-form-target]'); // TODO
  // const closeButton = el.querySelector('.close-button button');

  ctaButtons.forEach((button) => {
    const formId = button.getAttribute('data-form-target');

    button.addEventListener('click', function () {
      openModal(formId);
    });
  });

  // // Close the modal when clicking the close button
  // closeButton.addEventListener('click', closeModal);

  const handleDataActionClick = function (event) {
    const clickedElement = event.target.closest('[data-action="close-modal"]');

    if (clickedElement) {
      closeModal();
    }
  };

  // Function to open modal
  function openModal(formId) {
    el.addEventListener('click', handleDataActionClick);

    // Add visible class to start transitions
    modal.classList.add('active');
    // Force reflow to ensure transitions work
    void modal.offsetWidth;

    // Hide all forms first
    allForms.forEach((form) => {
      // form.style.display = 'block';
      form.classList.remove('active');
    });

    // Show the selected form
    const selectedForm = el.querySelector(`#${formId}`);

    if (selectedForm) {
      selectedForm.classList.add('active');
    }

    // // Show modal content with animation
    // setTimeout(() => {
    //   modalContent.classList.add('active');
    // }, 50);

    // Update ARIA attributes
    modal.setAttribute('aria-hidden', 'false');

    // Prevent page scrolling
    document.body.style.overflow = 'hidden';

    // // Set focus to the modal content for accessibility
    // setTimeout(() => {
    //   modalContent.focus();
    // }, 100);
  }

  // Function to close modal
  function closeModal() {
    el.removeEventListener('click', handleDataActionClick);

    // Start fade out animations
    modal.classList.remove('active');
    // modalContent.classList.remove('active');

    // Hide all forms
    allForms.forEach((form) => {
      form.classList.remove('active');
    });

    // // After animations complete, hide the modal
    // setTimeout(() => {
    //   modal.style.display = 'none';

    //   // Reset forms display state
    //   allForms.forEach((form) => {
    //     form.style.display = 'none';
    //   });
    // }, 300); // Match the transition duration from CSS

    // Update ARIA attributes
    modal.setAttribute('aria-hidden', 'true');

    // Re-enable page scrolling
    document.body.style.overflow = 'auto';
  }
}
