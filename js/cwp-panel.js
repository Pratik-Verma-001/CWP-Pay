(function () {
  'use strict';
  /* empty tables: say so instead of showing only the headings */
  document.querySelectorAll('.panel table.table').forEach(function (t) {
    var body = t.tBodies[0];
    if (!body || body.querySelector('tr')) return;
    var cols = 0;
    var head = t.querySelector('thead:last-of-type tr') || t.querySelector('thead tr');
    if (head) cols = head.children.length;
    var tr = document.createElement('tr'); tr.className = 'p-empty';
    var td = document.createElement('td'); td.colSpan = cols || 1; td.textContent = 'Nothing to show yet.';
    tr.appendChild(td); body.appendChild(tr);
  });

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
