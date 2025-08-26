/*
 * @class UawPagination
 * @description A custom pagination component for the UAW website.
 * @extends HTMLElement
 */
class UawPagination extends HTMLElement {
  #nav;

  constructor() {
    super();
    this.itemsPerPage = 5;
    this.currentPage = 1;

    // Create base structure
    // NOTE: Decided not to use shadow DOM for easier styling, if encapsulation doesn't become a problem,
    // we will consider using shadow DOM if needed.
    this.#nav = document.createElement('nav');
    this.#nav.className = 'uaw-pagination pagination';
    this.#nav.setAttribute('role', 'navigation');
    this.#nav.setAttribute('aria-label', 'pagination');
  }

  static get observedAttributes() {
    return ['total-items', 'items-per-page', 'current-page'];
  }

  attributeChangedCallback(name, oldValue, newValue) {
    switch (name) {
      case 'items-per-page':
        this.itemsPerPage = parseInt(newValue) || 5;
        break;

      case 'total-items':
        this.setupPagination(parseInt(newValue) || 0);

        if (newValue > 0) {
          if (!this.contains(this.#nav)) {
            this.appendChild(this.#nav);
          }
        }
        break;

      case 'current-page':
        this.currentPage = parseInt(newValue) || 1;

        this.setupPagination(parseInt(this.getAttribute('total-items')) || 0);

        break;
    }
  }

  setupPagination(totalItems) {
    const pageCount = Math.ceil(totalItems / this.itemsPerPage);
    const isFirstPage = this.currentPage === 1;
    const isLastPage = this.currentPage === pageCount;

    // Clear existing content
    this.#nav.innerHTML = '';

    // Create elements
    const prevButton = document.createElement('button');

    prevButton.className =
      'uaw-pagination__previous-button button--icon button--pagination';
    prevButton.setAttribute('aria-label', 'Previous page');
    prevButton.setAttribute('data-icon', 'chevron-left');
    prevButton.disabled = isFirstPage;
    prevButton.textContent = 'Previous page';

    const paginationList = document.createElement('ul');
    paginationList.className = 'pagination-list';

    const nextButton = document.createElement('button');

    nextButton.className =
      'uaw-pagination__next-button button--icon button--pagination';
    nextButton.setAttribute('aria-label', 'Next page');
    nextButton.setAttribute('data-icon', 'chevron-right');
    nextButton.textContent = 'Next page';
    nextButton.disabled = isLastPage;

    // Setup prev button
    prevButton.addEventListener('click', () => {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.setupPagination(totalItems);

        this.dispatchEvent(
          new CustomEvent('pageChange', {
            detail: { page: this.currentPage },
          })
        );
      }
    });

    // Add page numbers
    const pagesToShow = this.getVisiblePageNumbers(this.currentPage, pageCount);

    pagesToShow.forEach((pageNum) => {
      const li = document.createElement('li');
      const isActive = pageNum === this.currentPage;
      const pageButtonClass = 'button--pagination page-number page-numbers';

      if (pageNum === '...') {
        const span = document.createElement('span');
        span.className = 'ellipsis--pagination';
        span.textContent = '…';
        li.appendChild(span);
      } else {
        if (isActive) {
          const span = document.createElement('span');
          span.className = `${pageButtonClass} current`;
          span.setAttribute('aria-current', 'page');
          span.textContent = pageNum;
          li.appendChild(span);
        } else {
          const button = document.createElement('button');
          button.className = pageButtonClass;
          button.setAttribute('title', `Go to page ${pageNum}`);
          button.setAttribute('aria-label', `Go to page ${pageNum}`);
          button.textContent = pageNum;

          button.addEventListener('click', () => {
            this.currentPage = pageNum;
            this.setupPagination(totalItems);

            this.dispatchEvent(
              new CustomEvent('pageChange', {
                detail: { page: this.currentPage },
              })
            );
          });

          li.appendChild(button);
        }
      }
      paginationList.appendChild(li);
    });

    // Setup next button
    nextButton.addEventListener('click', () => {
      if (this.currentPage < pageCount) {
        this.currentPage++;
        this.setupPagination(totalItems);

        this.dispatchEvent(
          new CustomEvent('pageChange', {
            detail: { page: this.currentPage },
          })
        );
      }
    });

    // Append all elements
    this.#nav.appendChild(prevButton);
    this.#nav.appendChild(paginationList);
    this.#nav.appendChild(nextButton);
  }

  getVisiblePageNumbers(current, total) {
    if (total <= 8) {
      return Array.from({ length: total }, (_, i) => i + 1);
    }

    if (current <= 5) {
      return [1, 2, 3, 4, 5, '...', total];
    }

    if (current >= total - 3) {
      return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
    }

    return [1, '...', current - 1, current, current + 1, '...', total];
  }
}

customElements.define('uaw-pagination', UawPagination);
export default UawPagination;
