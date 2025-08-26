import { buildRefs } from '@/assets/scripts/helpers.js';

export default function (el) {
  const multiRefs = buildRefs(el, true);

  // Button listener
  multiRefs.toggle.forEach((toggle) =>
    toggle.addEventListener('click', (e) => {
      const isChecked = toggle.getAttribute('data-is-checked') === 'true';
      toggle.setAttribute('data-is-checked', !isChecked);
    })
  );
}
