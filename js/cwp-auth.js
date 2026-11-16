(function () {
  'use strict';
  document.querySelectorAll('.pw button').forEach(function (b) {
    b.addEventListener('click', function () {
      var i = b.parentNode.querySelector('input');
      var show = i.type === 'password';
      i.type = show ? 'text' : 'password';
      b.textContent = show ? 'Hide' : 'Show';
      b.setAttribute('aria-pressed', show ? 'true' : 'false');
    });
  });
  var pan = document.getElementById('pan_no');
  if (pan) pan.addEventListener('input', function () { pan.value = pan.value.toUpperCase().replace(/[^A-Z0-9]/g, ''); });
  document.querySelectorAll('input[data-digits]').forEach(function (i) {
    i.addEventListener('input', function () { i.value = i.value.replace(/\D/g, ''); });
  });
  /* the submit button name must still be posted, so do not disable it */
  document.querySelectorAll('.auth-form').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      var btn = e.submitter;
      if (btn && btn.name === 'login' || btn && btn.name === 'create_account') btn.classList.add('is-loading');
    });
  });
})();
