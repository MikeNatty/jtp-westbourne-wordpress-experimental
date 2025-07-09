document.addEventListener('DOMContentLoaded', function () {
  const postFilterComponent = document.querySelector('[name="PostFilter"]');

  const durationSheetOpen = 300; // Duration in milliseconds for the bottom sheet open animation
  const durationSheetClose = 300; // Duration in milliseconds for the bottom sheet open animation

  if (!postFilterComponent) {
    return;
  }
  console.log('post filter loaded');

  let choices = [];

  // Get the filter elements
  const postType = postFilterComponent.getAttribute('data-post-type');
  const contentTypeSelect = document.getElementById('content-type-filter');
  const categorySelect = document.getElementById('category-filter');
  const sortBySelect = document.getElementById('sort-by-filter');
  const resetFilter = document.getElementById('reset-filter');
  const clearFilter = document.getElementById('clear-filters-button');
  const blockCardGrid = document.querySelector('[name="BlockCardGrid"]');
  const postsContainer = document.querySelector('[name="BlockCardGrid"] .grid');
  // const filterBar = document.getElementById('filter-bar');
  const filterButton = document.getElementById('filter-button');
  // const mainContent = document.getElementById('mainContent');

  const bottomSheet = document.createElement('div');
  const sheetContainer = document.createElement('div');
  const backing = document.createElement('div');
  const titleBar = document.createElement('div');
  const titleH1 = document.createElement('h1');
  const sheetContent = document.createElement('div');
  const closeButton = document.createElement('button');

  const paginationContainer = document.querySelector(
    '[name="BlockCardGrid"] .pagination-container'
  );

  let isCardCarouselsHidden = false;

  // Current page tracking
  let currentPage = 1;
  // Add event listeners to all select dropdowns
  const filterSelects = [contentTypeSelect, categorySelect, sortBySelect];

  let bottomSheetOpen = false;
  let filterBarMinimized = false;

  // Delay to run window resize handler to avoid multiple calls
  let resizeDelay = null;

  createBottomSheet();
  checkFilterBarMinimize();

  // Window resize handler - check all dropdown positions to ensure within view
  // Set a threshold timeout to avoid multiple calls
  // Check for filter bar minimized mode
  window.addEventListener('resize', function () {
    clearTimeout(resizeDelay);

    resizeDelay = setTimeout(() => {
      layoutChoices();
      checkFilterBarMinimize();
    }, 500);
  });

  // Creat the bottom sheet UI for filterbar minimized mode
  function createBottomSheet() {
    bottomSheet.id = 'bottomSheet';
    bottomSheet.classList.add('bottom-sheet');
    backing.classList.add('backing');
    sheetContainer.classList.add('sheet-container');
    sheetContainer.setAttribute('data-duration', '300');
    titleBar.classList.add('title-bar');
    closeButton.classList.add('button--icon', 'close-button');
    closeButton.setAttribute('data-icon', 'close-large');
    closeButton.setAttribute('data-button-theme', 'white');
    closeButton.addEventListener('click', handleBottomSheetCloseClick);
    titleBar.appendChild(closeButton);
    sheetContent.classList.add('sheet-content');
    titleH1.textContent = 'Filter';
    titleBar.appendChild(titleH1);

    sheetContainer.append(titleBar, sheetContent);
    bottomSheet.append(backing, sheetContainer);
  }

  // If filter bar buttons can't fit in one row, set minimized mode
  function checkFilterBarMinimize() {
    // No need to check if botom sheet is open
    if (bottomSheetOpen) {
      return;
    }

    // We need to remove minimized mode first to check if max mode fits
    const breakpointTablet = 768;
    const viewWidth = window.innerWidth;
    if (viewWidth > breakpointTablet && filterBarMinimized) {
      setFilterBarMinimizedMode(false);
    }

    setTimeout(() => {
      // Check if content flows onto two rows
      // Unable to compare scrollHeight to clientHeight as the filter bar contains the dropdowns which overflow
      // For now, use the height of a select-wrapper to determine an approximation
      const filterBar = postFilterComponent.querySelector('.filter-bar');
      // const isMultiLine = filterBar.scrollHeight > filterBar.clientHeight;
      const selectWrapper = filterBar.querySelector('.select-wrapper');
      const selectRect = selectWrapper.getBoundingClientRect();
      const viewWidth = window.innerWidth;
      // const wrapperHeight = selectWrapper ? selectWrapper.clientHeight : 0;
      const isMultiLine = filterBar.clientHeight > selectRect.height * 2;

      // console.log(`checkFilterBarMinimize: filterBar.scrollHeight: ${filterBar.scrollHeight} > filterBar.clientHeight: ${filterBar.clientHeight}`);
      // console.log(`checkFilterBarMinimize: filterBar: ${filterBar.clientHeight} > selectRect.height: ${selectRect.height}`);
      // console.log(`checkFilterBarMinimize: isMultiLine: ${isMultiLine}`);
      // console.log(`checkFilterBarMinimize: filterBarMinimized: ${filterBarMinimized}`);
      // console.log(`checkFilterBarMinimize: viewWidth: ${viewWidth}`);

      if (viewWidth >= breakpointTablet && isMultiLine) {
        //&& !filterBarMinimized
        setFilterBarMinimizedMode(true);
      } else if (viewWidth < breakpointTablet) {
        setFilterBarMinimizedMode(true);
      } else if (!isMultiLine) {
        setFilterBarMinimizedMode(false);
      } else {
        setFilterBarMinimizedMode(true);
      }

      // If bottom sheet open and we're larger than breakpoint, slose sheet if not multiline
      // if( bottomSheetOpen && viewWidth >= breakpointTablet ) {
      if (bottomSheetOpen && viewWidth >= breakpointTablet && !isMultiLine) {
        closeSheet();
      }
    }, 100);
  }

  function setFilterBarMinimizedMode(isMinimized) {
    filterBarMinimized = isMinimized;

    // console.log(`setFilterBarMinimizedMode: isMinimized: ${isMinimized}`);
    // console.log(`bottomSheet: ${bottomSheet}`);

    const filterBar = postFilterComponent.querySelector('.filter-bar');
    //const filterBar = postFilterComponent.querySelector('.filter-bar');
    if (isMinimized) {
      filterBar.classList.add('minimized');
      bottomSheet.classList.add('active');
    } else {
      filterBar.classList.remove('minimized');
      bottomSheet.classList.remove('active');
    }
  }

  /*
  // :: TODO :: THe designs specify a breakpoint of 768px for minimized mode, but this is not
  // enough as depending on the labels' width the filter buttons can exist on two rows
  // before we hit that breakpoint. Instead, We could check for 2 rows on resize instead

  // Set up an observer to watch for media query changes
  // If the bottom sheet is open, and we resize to larger window, we need to
  // re-parent the filter bar
  // const breakpointTablet = getComputedStyle(element).getPropertyValue('--breakpoint-tablet').trim();
  const breakpointTablet = 768;
  // console.log(`breakpointTablet: ${breakpointTablet}`);
  const mq = window.matchMedia(`(max-width: ${breakpointTablet}px)`);

  function handleBreakpointChange(e) {
    console.log(`handleBreakpointChange...`)
    if (e.matches) {
      // Viewport is breakpointTablet or less
      console.log('Breakpoint hit: <= 768px');
    } else {
      // Viewport is greater than breakpointTablet
      console.log('Breakpoint left: > 768px');
      if( bottomSheetOpen ){
        handleBottomSheetCloseClick( null );
      }
    }
  }
  // Initial check
  handleBreakpointChange(mq);
  // Listen for changes
  mq.addEventListener('change', handleBreakpointChange);
  */

  function handleBottomSheetCloseClick(event) {
    closeSheet();
  }

  function layoutChoices() {
    // clearTimeout( resizeDelay );

    // :: NOTE :: Not very extensible code, but works for now
    // There are only 2 possible positions for the filter bar
    // Below for desktop as per designs and above for the minimized
    // bottom sheet on narrow viewports
    // resizeDelay = setTimeout(() => {
    choices.forEach((choice) => {
      const selectEl = choice.passedElement.element;
      // console.log(`layoutChoices: `, selectEl );
      if (bottomSheetOpen) {
        ChoicesUtils.positionAbove(selectEl);
      } else {
        ChoicesUtils.positionBelow(selectEl);
      }
    });
    // }, 500 );
  }

  const contentTypeChoiceObj = initializeChoice(contentTypeSelect);
  const categoryChoiceObj = initializeChoice(categorySelect);
  const sortByChoiceObj = initializeChoice(sortBySelect);

  // Convert the select elements to Choices so we can style and cusomize
  function initializeChoice(selectEl) {
    if (selectEl) {
      // Convert selectEl to Choices
      const config = ChoicesUtils.getDefaultConfig(selectEl);

      // Create the Choice instance from the selectEl element
      const choice = new Choices(selectEl, config);
      choices.push(choice);

      // Add event listeners for change events (choice event in Choices.js)
      selectEl.addEventListener('change', function (event) {
        // Reset to page 1 when filters change
        currentPage = 1;
        handleFilterChange();
        checkFilterBarMinimize();

        // Hide recent content carousel when filtering
        if (!isCardCarouselsHidden) {
          isCardCarouselsHidden = true;
          hideCardCarousels();
        }
      });

      return choice;
    }
    return null;
  }

  // Original code - Modified by Mike to use Choices.js
  // filterSelects.forEach((select) => {
  //   if (select) {
  //     select.addEventListener('change', function () {
  //       // Reset to page 1 when filters change
  //       currentPage = 1;
  //       handleFilterChange();
  //
  //       // Hide recent content carousel when filtering
  //       if (!isCardCarouselsHidden) {
  //         isCardCarouselsHidden = true;
  //         hideCardCarousels();
  //       }
  //     });
  //   }
  // });

  // Add click handler for filter button to open bottom sheet
  if (filterButton) {
    filterButton.addEventListener('click', function (e) {
      e.preventDefault();

      console.log('filter button clicked - show bottom sheet');

      bottomSheetOpen = true;

      openSheet();
    });
  }

  // Function to clear all filters and reset to default state
  function clearFilters() {
    // Build query parameters
    const params = new URLSearchParams();

    // Update URL without reloading the page
    const newUrl = `${window.location.pathname}`;
    history.pushState({}, '', newUrl);

    // Fetch the filtered posts via AJAX
    fetchFilteredPosts(params);

    // reset choices to their default value - currently all ("") or newest
    choices.forEach((choice) => {
      choice.setChoiceByValue(['', 'newest']);
    });
  }

  // Function to handle filter changes
  function handleFilterChange() {
    // Build query parameters
    const params = buildQueryParams();

    // Update URL without reloading the page
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    history.pushState({}, '', newUrl);

    // Fetch the filtered posts via AJAX
    fetchFilteredPosts(params);
  }

  // Add click handlers for reset and clear buttons
  if (resetFilter) {
    resetFilter.addEventListener('click', function () {
      clearFilters();
    });
  }
  if (clearFilter) {
    clearFilter.addEventListener('click', function () {
      clearFilters();
    });
  }

  // Helper function to build query parameters
  function buildQueryParams() {
    const params = new URLSearchParams();

    // Add content type if selected
    if (contentTypeSelect && contentTypeSelect.value) {
      if (postType == 'publications') {
        params.set('tag', contentTypeSelect.value);
      } else if (postType == 'events') {
        params.set('location', contentTypeSelect.value);
      } else {
        params.set('content_type', contentTypeSelect.value);
      }
    }

    // Add category if selected
    if (categorySelect && categorySelect.value) {
      params.set('category', categorySelect.value);
    }

    // Add sort order if selected
    if (sortBySelect && sortBySelect.value) {
      params.set('sort', sortBySelect.value);
    }

    // Add current page
    params.set('paged', currentPage.toString());

    return params;
  }

  // Function to fetch posts via AJAX
  function fetchFilteredPosts(params) {
    // Show loading indicator
    // postsContainer.innerHTML = '<div class="loading">Loading...</div>';

    // Add WordPress AJAX action
    const ajaxParams = new URLSearchParams(params);
    ajaxParams.set('post_type', postType);
    ajaxParams.set('action', 'filter_posts');
    ajaxParams.set('nonce', flyntFilterData.nonce);

    fetch(flyntFilterData.ajaxUrl, {
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
          // Update the posts container with new posts
          postsContainer.innerHTML = data.data.html;

          // Update pagination
          if (paginationContainer) {
            paginationContainer.innerHTML = data.data.pagination;
            attachPaginationHandlers();
          }
        } else {
          postsContainer.innerHTML =
            '<div class="error">Error loading posts</div>';

          if (paginationContainer) {
            paginationContainer.innerHTML = '';
          }
        }
      })
      .catch((error) => {
        console.error('Error fetching posts:', error);

        postsContainer.innerHTML =
          '<div class="error">Error loading posts</div>';

        if (paginationContainer) {
          paginationContainer.innerHTML = '';
        }
      });
  }

  // TODO
  // Function to attach click handlers to pagination links
  function attachPaginationHandlers() {
    if (!paginationContainer) return;

    const paginationLinks = paginationContainer.querySelectorAll(
      'a.button--pagination'
    );
    // console.log(paginationLinks);

    paginationLinks.forEach((link) => {
      // console.log(link.dataset.paged);

      link.addEventListener('click', function (e) {
        e.preventDefault();

        // Extract page number from href
        // const hrefUrl = new URL(link.href, window.location.origin);
        // const pageParam = hrefUrl.searchParams.get('paged');
        const pageParam = link.dataset.paged;

        if (pageParam) {
          currentPage = parseInt(pageParam, 10);
          handleFilterChange();

          // TODO Scroll to top of results
        }
      });
    });
  }

  // Initialize from URL parameters on page load
  function initializeFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);

    // Set dropdown values based on URL parameters
    if (contentTypeSelect && urlParams.has('content_type')) {
      // contentTypeSelect.value = urlParams.get('content_type');
      contentTypeChoiceObj.setChoiceByValue(urlParams.get('content_type'));
    }

    if (categorySelect && urlParams.has('category')) {
      // categorySelect.value = urlParams.get('category');
      categoryChoiceObj.setChoiceByValue(urlParams.get('category'));
    }

    if (sortBySelect && urlParams.has('sort')) {
      // sortBySelect.value = urlParams.get('sort');
      sortByChoiceObj.setChoiceByValue(urlParams.get('sort'));
    }

    // Set current page if in URL
    if (urlParams.has('paged')) {
      currentPage = parseInt(urlParams.get('paged'), 10);
    }

    // Initial pagination handlers
    attachPaginationHandlers();

    // Fetch posts from url
    handleFilterChange();
  }

  function hideCardCarousels() {
    // console.log('hide');
    if (blockCardGrid) {
      blockCardGrid.classList.add('active');
    }

    const carousels = document.querySelectorAll('[name="BlockCardCarousel"]');

    carousels.forEach((e) => {
      e.style.display = 'none';
    });
  }

  function closeSheet() {
    bottomSheetOpen = false;
    sheetContainer.classList.remove('active');

    setTimeout(() => {
      const container = postFilterComponent.querySelector('.container');
      const filterBar = bottomSheet.querySelector('.filter-bar');

      container.append(filterBar);
      // mainContent.append( bottomSheet );
      bottomSheet.remove();

      layoutChoices();
      checkFilterBarMinimize();
    }, durationSheetClose);
  }

  function openSheet() {
    // console.log('open sheet');

    // :: HACK :: in the interest of time, remove and re-parent the filter bar
    // into the bottom sheet, restyle, and just use the existing dropdown functionality
    // If the filters get more complex, we can revisit this
    const filterBar = document.querySelector('.filter-bar');
    // console.log('Element filterBar:', filterBar );

    sheetContent.append(filterBar);
    postFilterComponent.append(bottomSheet);

    void postFilterComponent.offsetHeight;

    layoutChoices();
  }

  // Initialize
  initializeFromUrl();
});
