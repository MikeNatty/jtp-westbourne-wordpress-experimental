
export default class GATracking {
  constructor() {
    // super();
  }

  static sendEvent({ event, category, action, label, value }) {
    window.dataLayer = window.dataLayer || [];

    window.dataLayer.push({
      event: event,
      category: category,
      action: action,
      label: label,
      value: value,
    });
  }
}
