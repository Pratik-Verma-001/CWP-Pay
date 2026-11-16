(function () {
  'use strict';
  var side = document.getElementById('p-side');
  if (!side) return;
  var scrim = document.querySelector('.p-scrim');
  var openBtn = document.querySelector('.p-menu-btn');
  function set(open) {
    side.classList.toggle('is-open', open);
    scrim.hidden = !open;
    if (openBtn) openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.style.overflow = open ? 'hidden' : '';
  }
  if (openBtn) openBtn.addEventListener('click', function () { set(true); });
  scrim.addEventListener('click', function () { set(false); });
  var closeBtn = document.querySelector('.p-side-close');
  if (closeBtn) closeBtn.addEventListener('click', function () { set(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') set(false); });
  /* swipe from the left edge to open, swipe left to close (phones) */
  var x0 = null, y0 = null;
  document.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; y0 = e.touches[0].clientY; }, { passive: true });
  document.addEventListener('touchend', function (e) {
    if (x0 === null) return;
    var dx = e.changedTouches[0].clientX - x0, dy = Math.abs(e.changedTouches[0].clientY - y0);
    if (dy < 50 && window.innerWidth < 992) {
      if (dx > 70 && x0 < 28) set(true);
      else if (dx < -70 && side.classList.contains('is-open')) set(false);
    }
    x0 = null;
  }, { passive: true });

  /* theme: light / dark, remembered in this browser */
  var theme = document.querySelector('.p-theme');
  if (theme) theme.addEventListener('click', function () {
    var root = document.documentElement;
    var dark = root.getAttribute('data-theme') === 'dark' ||
      (!root.getAttribute('data-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
    var next = dark ? 'light' : 'dark';
    root.setAttribute('data-theme', next);
    try { localStorage.setItem('cwp-theme', next); } catch (e) {}
  });
})();
