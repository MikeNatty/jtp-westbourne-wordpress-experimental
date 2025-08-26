export default function (el) {
  const panels = el.querySelectorAll('[data-refs="toggle"]');

  // Button listener
  panels.forEach((toggle) =>
    toggle.addEventListener('click', (e) => {
      accordionClicked(e, panels);
    })
  );

  const loadMore = el.querySelector('[data-ref="loadMore"]');

  if (loadMore) {
    loadMore.addEventListener('click', (e) => {
      handleLoadMore(e, el);
    });
  }
}

function accordionClicked(e, allPanels) {
  console.log('clicked');
  const trigger = e.target;
  const isAriaExpanded = trigger.getAttribute('aria-expanded') === 'true';

  // close all panels
  allPanels.forEach((pannel) => toggleAccordion(pannel, false));

  toggleAccordion(trigger, !isAriaExpanded);
}

function toggleAccordion(panel, expand = false) {
  const parent = panel.closest('.panels-row');
  const content = document.getElementById(panel.getAttribute('aria-controls'));

  panel.setAttribute('aria-expanded', expand);
  parent.setAttribute('aria-expanded', expand);
  content.setAttribute('aria-hidden', !expand);
}

function handleLoadMore(e, el) {
  const loadInt = el.dataset.loadInt ? el.dataset.loadInt : 10;
  const hiddenPannels = el.querySelectorAll('.panels-row.is-hidden');

  for (let i = 0; i < loadInt; i++) {
    if (hiddenPannels[i]) {
      hiddenPannels[i].classList.remove('is-hidden');
    }
  }

  if (hiddenPannels.length <= loadInt) {
    const container = e.target.closest('.load-more');

    if (container) {
      container.classList.add('is-hidden');
    }
  }
}
