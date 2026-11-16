(function () {
  'use strict';
  var inr = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 });
  var money = function (n) { return '₹' + inr.format(Math.round(n)); };
  var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* header: shadow on scroll + mobile nav */
  var header = document.querySelector('.site-header');
  var toggle = document.querySelector('.nav-toggle');
  function stuck() { header.classList.toggle('is-stuck', window.scrollY > 8); }
  stuck(); window.addEventListener('scroll', stuck, { passive: true });
  function setNav(open) {
    header.classList.toggle('nav-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  toggle.addEventListener('click', function () { setNav(!header.classList.contains('nav-open')); });
  document.querySelectorAll('.nav a').forEach(function (a) { a.addEventListener('click', function () { setNav(false); }); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setNav(false); });

  /* ledger: payments arrive, get matched */
  var rows = document.getElementById('ledger-rows');
  var totalEl = document.getElementById('ledger-total');
  var data = [
    { amt: 499, ref: 'Order #1042', utr: '4162 8831 0927' },
    { amt: 1250, ref: 'Order #1043', utr: '4162 8835 7710' },
    { amt: 199, ref: 'Order #1044', utr: '4162 8842 1156' }
  ];
  var timers = [];
  var total = 0;
  function later(fn, ms) { timers.push(setTimeout(fn, ms)); }
  function clearAll() { timers.forEach(clearTimeout); timers = []; }
  var check = '<svg viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
  function render(i, state) {
    var d = data[i];
    var li = document.createElement('li');
    li.className = 'l-row ' + (state === 'matched' ? 'is-matched' : 'is-waiting');
    li.innerHTML = '<span class="l-amt">' + money(d.amt) + '</span><span class="l-meta">' + d.ref + ' · UTR ' + d.utr + '</span>' +
      '<span class="l-chip"><i></i>' + check + '<b>' + (state === 'matched' ? 'Matched' : 'Waiting') + '</b></span>';
    return li;
  }
  function setTotal(v) { totalEl.textContent = money(v); }
  function match(li, i) {
    li.className = 'l-row is-matched is-flash';
    li.querySelector('b').textContent = 'Matched';
    total += data[i].amt; setTotal(total);
    later(function () { li.classList.remove('is-flash'); }, 700);
  }
  function play() {
    clearAll(); rows.innerHTML = ''; total = 0; setTotal(0);
    if (reduce) {
      data.forEach(function (d, i) { rows.appendChild(render(i, 'matched')); total += d.amt; });
      setTotal(total); return;
    }
    data.forEach(function (d, i) {
      later(function () {
        var li = render(i, 'waiting');
        li.classList.add('is-hidden');
        rows.insertBefore(li, rows.firstChild);
        requestAnimationFrame(function () { requestAnimationFrame(function () { li.classList.remove('is-hidden'); }); });
        later(function () { match(li, i); }, 1100);
      }, 500 + i * 1900);
    });
  }
  document.querySelector('.ledger-replay').addEventListener('click', play);
  play();

  /* savings calculator */
  var sales = document.getElementById('calc-sales');
  var out = document.getElementById('calc-sales-out');
  var feeOut = document.getElementById('calc-fee-out');
  var feeBar = document.getElementById('calc-fee-bar');
  var saveOut = document.getElementById('calc-save-out');
  var keep = document.getElementById('calc-keep');
  var note = document.getElementById('calc-save-note');
  var PLAN = 199;
  function calc() {
    var s = +sales.value;
    var r = +document.querySelector('input[name=rate]:checked').value;
    var fee = s * r / 100;
    var save = fee - PLAN;
    out.textContent = money(s);
    feeOut.textContent = money(fee);
    feeBar.style.width = Math.max(4, Math.min(100, fee / (2000000 * 0.025) * 100)) + '%';
    sales.style.setProperty('--p', ((s - sales.min) / (sales.max - sales.min) * 100) + '%');
    keep.classList.toggle('is-neg', save < 0);
    if (save >= 0) {
      saveOut.textContent = money(save);
      note.textContent = 'more stays with you each month, after the ₹' + PLAN + ' plan.';
    } else {
      saveOut.textContent = money(-save);
      note.textContent = 'short of breaking even. At this volume your current fee is cheaper than the ₹' + PLAN + ' plan.';
    }
  }
  sales.addEventListener('input', calc);
  document.querySelectorAll('input[name=rate]').forEach(function (r) { r.addEventListener('change', calc); });
  calc();
})();
