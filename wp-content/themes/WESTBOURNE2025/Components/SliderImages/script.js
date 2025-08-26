import { buildRefs, getJSON } from '@/assets/scripts/helpers.js';
import Swiper from 'swiper';
import { Autoplay, A11y, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/autoplay';
import 'swiper/css/a11y';
import 'swiper/css/navigation';
// import 'swiper/css/pagination'

export default function (el) {
  const refs = buildRefs(el);
  const data = getJSON(el);

  const swiper = initSlider(refs, data);

  return () => swiper.destroy();
}

// animate transition to 0.2s, ease out

function initSlider(refs, data) {
  console.log(refs.pagination);
  const { options } = data;

  const config = {
    modules: [Autoplay, A11y, Navigation, Pagination],
    a11y: options.a11y,
    roundLengths: true,
    navigation: {
      nextEl: refs.next,
      prevEl: refs.prev,
    },
    pagination: {
      el: refs.pagination,
      clickable: true,
    },
  };

  if (options.autoplay && options.autoplaySpeed) {
    config.autoplay = {
      delay: options.autoplaySpeed,
    };
  }

  return new Swiper(refs.slider, config);
}
