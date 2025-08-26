import { buildRefs } from '@/assets/scripts/helpers';

export default function (el) {
  const refs = buildRefs(el, false, {
    iframe: 'iframe',
  });

  if (refs.playButton) {
    refs.playButton.addEventListener('click', loadVideo, { once: true });

    function loadVideo() {
      refs.iframe.addEventListener('load', videoIsLoaded, { once: true });
      refs.iframe.setAttribute('src', refs.iframe.getAttribute('data-src'));
      refs.videoPlayer.dataset.state = 'isLoading';
    }

    function videoIsLoaded() {
      refs.videoPlayer.dataset.state = 'isLoaded';
      refs.coverImage.dataset.state = 'isHidden';
    }

    return () => {
      console.log('return');
      refs.playButton.removeEventListener('click', loadVideo);
      refs.iframe.removeEventListener('load', videoIsLoaded);
    };
  }

  if (refs.videoBackground) {
    const src = refs.videoBackground.getAttribute('data-src');
    refs.videoBackground.setAttribute('src', src);

    refs.videoBackground.addEventListener('loadeddata', (e) => {
      refs.videoBackground.classList.add('loadeddata');
    });

    refs.videoBackground.addEventListener('load', (e) => {
      refs.videoBackground.classList.add('load');
    });
  }
}
