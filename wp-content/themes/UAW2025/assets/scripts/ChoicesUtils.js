class ChoicesUtils {
  /*
   Utiling class to provide utility functions for the Choices.js library across
   both React and vanilla JS
   */

  constructor() {}

  /*
   * Function to position the dropdown to ensure it's visible in view
   * Uses a delay to ensure component drawn
   */
  static positionBelow = function (select) {
    // console.log(`positionBelow select: `, select );

    setTimeout(() => {
      const wrapper = select.closest('.select-wrapper');
      const parent = wrapper.parentElement;
      const dropDown = wrapper.querySelector('.choices__list--dropdown');
      const choice = wrapper.querySelector('.choices');
      const filterBar = document.querySelector('.filter-bar');

      const parentRect = parent.getBoundingClientRect();
      const wrapperRect = wrapper.getBoundingClientRect();
      const choiceRect = choice.getBoundingClientRect();
      const dropdownRect = dropDown.getBoundingClientRect();
      // const filterBarRect = filterBar.getBoundingClientRect();

      // console.log(`parentRect:`, parentRect )
      // console.log(`wrapperRect:`, wrapperRect )
      // console.log(`choiceRect`, choiceRect )
      // console.log(`dropdownRect`, dropdownRect )
      // console.log(`filterBarRect`, filterBarRect )

      const deltaX = choiceRect.x - wrapperRect.x;
      // Check if a right align will obscure the left of the dropdown, force left align
      const leftAlign = wrapperRect.right - dropdownRect.width < 0;
      const viewWidth = window.innerWidth;

      // Left align options to the left of wrapper
      // If options go off the right of screen, right-align options to the wrapper right.
      if (
        viewWidth > 480 &&
        parentRect.right - (choiceRect.x + dropdownRect.width) < 0 &&
        !leftAlign
      ) {
        dropDown.style.left = 'unset';
        dropDown.style.right = 0;
        dropDown.style.bottom = 'unset';
        dropDown.style.top = '140%';
      } else {
        dropDown.style.left = `-${deltaX}px`;
        dropDown.style.right = 'unset';
        dropDown.style.bottom = 'unset';
        dropDown.style.top = '140%';
      }
    }, 1);
  };

  static positionAbove = function (select) {
    setTimeout(() => {
      const wrapper = select.closest('.select-wrapper');
      const parent = wrapper.parentElement;
      const dropDown = wrapper.querySelector('.choices__list--dropdown');
      const choice = wrapper.querySelector('.choices');
      const filterBar = document.querySelector('.filter-bar');

      const parentRect = parent.getBoundingClientRect();
      const wrapperRect = wrapper.getBoundingClientRect();
      const choiceRect = choice.getBoundingClientRect();
      const dropdownRect = dropDown.getBoundingClientRect();
      // const filterBarRect = filterBar.getBoundingClientRect();

      // console.log(`parentRect:`, parentRect )
      // console.log(`wrapperRect:`, wrapperRect )
      // console.log(`choiceRect`, choiceRect )
      // console.log(`dropdownRect`, dropdownRect )
      // console.log(`filterBarRect`, filterBarRect )

      const deltaX = choiceRect.x - wrapperRect.x;
      // Check if a right align will obscure the left of the dropdown, force left align
      const leftAlign = wrapperRect.right - dropdownRect.width < 0;
      const viewWidth = window.innerWidth;

      // Left align options to the left of wrapper
      // If options go off the right of screen, right-align options to the wrapper right.
      if (
        viewWidth > 480 &&
        parentRect.right - (choiceRect.x + dropdownRect.width) < 0 &&
        !leftAlign
      ) {
        let y = wrapperRect.height;
        let x = 0;

        dropDown.style.top = 'unset';
        dropDown.style.bottom = 'unset';
        dropDown.style.bottom = `${y}px`;
        dropDown.style.left = `${x}px`;
      } else {
        // dropDown.style.left = `-${deltaX}px`;
        // dropDown.style.right = 'unset';
        let y = wrapperRect.height;
        let x = -deltaX;

        dropDown.style.top = 'unset';
        dropDown.style.bottom = 'unset';
        dropDown.style.bottom = `${y}px`;
        dropDown.style.left = `${x}px`;
      }
    }, 1);
  };

  /*
   * Function to return a default config for Choices
   */
  static getDefaultConfig = function (select) {
    const config = {
      searchEnabled: false,
      itemSelectText: '',
      // position: 'bottom',
      position: 'top',
      callbackOnInit: function () {
        // console.log(`callbackOnInit: `, select);
        // ChoicesUtils.positionDropdown( select );
        ChoicesUtils.positionBelow(select);
      },
    };

    return config;
  };
}

/*
 Export the class as a module or as a global variable for plain JS
  This allows the class to be used in both Node.js and browser environments.
 */
if (typeof module !== 'undefined' && module.exports) {
  // console.log(`Exporting ChoicesUtils as a module`);
  module.exports = ChoicesUtils;
  // export ChoicesUtils;
} else {
  // console.log(`Exporting ChoicesUtils as a global variable`);
  window.ChoicesUtils = ChoicesUtils;
}
