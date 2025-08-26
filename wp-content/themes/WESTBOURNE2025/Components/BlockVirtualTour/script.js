export default function (el) {
  const modal = el.querySelector('#iframeModal');
  const modalInner = el.querySelector('.content-modal-inner');
  const iframeLauncher = el.querySelector('.iframe-launcher');

  if (iframeLauncher) {
    iframeLauncher.addEventListener('click', function () {
      openModal();
    });
  }

  const handleIframeDataActionClick = function (event) {
    const clickedElement = event.target.closest(
      '[data-action="close-iframe-modal"]'
    );

    if (clickedElement) {
      closeModal();
    }
  };

  // Function to open modal
  function openModal() {
    document.addEventListener('click', handleIframeDataActionClick);

    // Add visible class to start transitions
    modal.classList.add('active');

    // Update ARIA attributes
    modal.setAttribute('aria-hidden', 'false');

    // Prevent page scrolling
    document.body.style.overflow = 'hidden';
  }

  // Function to close modal
  function closeModal() {
    document.removeEventListener('click', handleIframeDataActionClick);
    // Start fade out animations
    modal.classList.remove('active');

    // Update ARIA attributes
    modal.setAttribute('aria-hidden', 'true');

    // Re-enable page scrolling
    document.body.style.overflow = 'auto';
  }
}
