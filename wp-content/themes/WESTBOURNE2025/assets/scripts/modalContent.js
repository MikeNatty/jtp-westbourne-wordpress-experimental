document.addEventListener('DOMContentLoaded', function () {
  const modal = document.querySelector('#contentModal');
  const modalInner = document.querySelector('.content-modal-inner');
  // const allForms = el.querySelectorAll('.form');
  const ctaButtons = document.querySelectorAll('[data-modal-id]'); // TODO
  // const closeButton = el.querySelector('.close-button button');

  ctaButtons.forEach((button) => {
    const modalId = button.getAttribute('data-modal-id');

    button.addEventListener('click', function () {
      openModal(modalId);
    });
  });

  const handleDataActionClick = function (event) {
    const clickedElement = event.target.closest('[data-action="close-modal"]');

    if (clickedElement) {
      closeModal();
    }
  };

  // Function to open modal
  function openModal(modalId) {
    document.addEventListener('click', handleDataActionClick);

    // Add visible class to start transitions
    modal.classList.add('active');

    // modalInner.innerHTML = `<h2>${modalId}</h2>`;
    modalInner.innerHTML = '';
    fetchFilteredPosts(modalId);

    // Update ARIA attributes
    modal.setAttribute('aria-hidden', 'false');

    // Prevent page scrolling
    document.body.style.overflow = 'hidden';
  }

  // Function to close modal
  function closeModal() {
    document.removeEventListener('click', handleDataActionClick);

    // Start fade out animations
    modal.classList.remove('active');
    // modalContent.classList.remove('active');

    // Update ARIA attributes
    modal.setAttribute('aria-hidden', 'true');

    // Re-enable page scrolling
    document.body.style.overflow = 'auto';
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////

  // Function to fetch posts via AJAX
  function fetchFilteredPosts(modalId) {
    const params = new URLSearchParams();
    params.set('modal_id', modalId);

    // Add WordPress AJAX action
    const ajaxParams = new URLSearchParams(params);
    ajaxParams.set('action', 'get_modal');
    ajaxParams.set('nonce', flyntModalData.nonce);

    fetch(flyntModalData.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: ajaxParams.toString(),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        return response.json();
      })
      .then((data) => {
        if (data.success) {
          // console.log(data.data);

          modalInner.innerHTML = data.data.html;
        } else {
          modalInner.innerHTML = '<div class="error">Error loading posts</div>';
        }
      })
      .catch((error) => {
        console.error('Error fetching posts:', error);

        modalInner.innerHTML = '<div class="error">Error loading posts</div>';
      });
  }
});
