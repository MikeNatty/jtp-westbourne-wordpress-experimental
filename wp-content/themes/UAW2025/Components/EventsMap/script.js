export default function (el) {
  const mapElement = el.querySelector('.map');

  let apiKey = mapElement.dataset.apiKey;
  let locationJson = mapElement.dataset.location;
  let zoom = 14;

  loadGoogleMapsScript();

  function loadGoogleMapsScript() {
    if (!apiKey) {
      console.error('Google Maps API key is missing');
      return;
    }

    // Create script element
    const script = document.createElement('script');

    // https://developers.google.com/maps/documentation/javascript/add-google-map
    script.src = ((g) => {
      var h,
        a,
        k,
        p = 'The Google Maps JavaScript API',
        c = 'google',
        l = 'importLibrary',
        q = '__ib__',
        m = document,
        b = window;
      b = b[c] || (b[c] = {});
      var d = b.maps || (b.maps = {}),
        r = new Set(),
        e = new URLSearchParams(),
        u = () =>
          h ||
          (h = new Promise(async (f, n) => {
            await (a = m.createElement('script'));
            e.set('libraries', [...r] + '');
            for (k in g)
              e.set(
                k.replace(/[A-Z]/g, (t) => '_' + t[0].toLowerCase()),
                g[k]
              );
            e.set('callback', c + '.maps.' + q);
            a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
            d[q] = f;
            a.onerror = () => (h = n(Error(p + ' could not load.')));
            a.nonce = m.querySelector('script[nonce]')?.nonce || '';
            m.head.append(a);
          }));
      d[l]
        ? console.warn(p + ' only loads once. Ignoring:', g)
        : (d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)));
    })({
      key: apiKey,
      v: 'weekly',
      // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
      // Add other bootstrap parameters as needed, using camel case.
    });

    document.head.appendChild(script);
  }

  async function initMap() {
    const { Map } = await google.maps.importLibrary('maps');
    const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');

    const location = JSON.parse(locationJson);

    const map = new Map(mapElement, {
      center: location,
      zoom: zoom,
      disableDefaultUI: true,
      // zoomControl: true,
      zoomControl: false,
      scrollwheel: false,
      // gestureHandling: 'cooperative',
      gestureHandling: 'none',
      mapId: '1234',
    });

    const mapPin = document.createElement('div');
    mapPin.className = 'map-pin map-pin--static';

    const marker = new AdvancedMarkerElement({
      map,
      position: location,
      content: mapPin,
    });

    // Init zoom controls within this component
    const zoomInButton = el.querySelector('[data-zoom="in"]');
    const zoomOutButton = el.querySelector('[data-zoom="out"]');

    if (zoomInButton) {
      zoomInButton.addEventListener('click', () => {
        console.log('zoom');
        const currentZoom = map.getZoom();
        map.setZoom(currentZoom + 1);
      });
    }

    if (zoomOutButton) {
      zoomOutButton.addEventListener('click', () => {
        console.log('zoom out');
        const currentZoom = map.getZoom();
        map.setZoom(Math.max(1, currentZoom - 1));
      });
    }
  }

  initMap();
}
