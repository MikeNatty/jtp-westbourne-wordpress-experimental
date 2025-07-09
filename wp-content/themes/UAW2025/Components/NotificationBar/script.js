import { buildRefs } from '@/assets/scripts/helpers.js';

export default function (el) {
  const refs = buildRefs(el);

  const notificationId = el.dataset.notificationId;
  // console.log(notificationId);

  refs.closeButton.addEventListener('click', (e) => {
    el.remove();

    if (notificationId) {
      sessionStorage.setItem(notificationId, 'closed');
    }
    el.remove();
  });

  // checkNotificationState();

  function checkNotificationState() {
    console.log(sessionStorage.getItem(notificationId));

    // Check if this notification has been closed in this session
    if (notificationId && sessionStorage.getItem(notificationId) === 'closed') {
      el.remove();
    }
  }
}
