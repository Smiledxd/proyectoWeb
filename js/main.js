
document.addEventListener('DOMContentLoaded', () => {
  const carousel = document.querySelector('.hero__bg-carousel');
  if (!carousel) return;

  const slides = Array.from(carousel.querySelectorAll('.carousel-slide'));
  const dotsContainer = carousel.querySelector('.carousel-dots');
  if (!slides.length || !dotsContainer) return;

  let current = 0;
  const AUTOPLAY = 5000;
  let timer = null;

  // build dots
  slides.forEach((_, i) => {
    const btn = document.createElement('button');
    btn.className = 'carousel-dot';
    btn.setAttribute('aria-label', `Ir al slide ${i + 1}`);
    btn.addEventListener('click', () => {
      goTo(i);
      restartAutoplay();
    });
    dotsContainer.appendChild(btn);
  });

  function update(index) {
    slides.forEach((s, i) => {
      s.classList.toggle('active', i === index);
      const v = s.querySelector('video');
      if (v) {
        try {
          if (i === index) {
            v.currentTime = 0;
            v.play().catch(() => {});
          } else {
            v.pause();
          }
        } catch  {}
      }
    });

    const dots = Array.from(dotsContainer.children);
    dots.forEach((d, i) => d.classList.toggle('active', i === index));
  }

  function goTo(index) {
    if (index < 0) index = slides.length - 1;
    if (index >= slides.length) index = 0;
    current = index;
    update(current);
  }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }

  function startAutoplay() {
    stopAutoplay();
    timer = setInterval(next, AUTOPLAY);
  }
  function stopAutoplay() {
    if (timer) { clearInterval(timer); timer = null; }
  }
  function restartAutoplay() { startAutoplay(); }

  // touch support
  let startX = 0;
  let endX = 0;
  const threshold = 50;
  carousel.addEventListener('touchstart', (e) => {
    stopAutoplay();
    startX = e.changedTouches[0].screenX;
  }, {passive:true});
  carousel.addEventListener('touchend', (e) => {
    endX = e.changedTouches[0].screenX;
    const diff = startX - endX;
    if (Math.abs(diff) > threshold) {
      if (diff > 0) next(); else prev();
    }
    restartAutoplay();
  });

  // init
  goTo(0);
  startAutoplay();

  // pause autoplay when page/tab not visible
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) stopAutoplay(); else startAutoplay();
  });
});