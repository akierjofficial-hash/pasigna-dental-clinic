(function () {
  'use strict';

  var toggle = document.getElementById('pd-menu-toggle');
  var links = document.getElementById('pd-nav-links');
  if (!toggle || !links) return;

  function setOpen(open) {
    links.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
  }

  toggle.addEventListener('click', function () {
    setOpen(toggle.getAttribute('aria-expanded') !== 'true');
  });
  links.addEventListener('click', function (event) {
    if (event.target.closest('a')) setOpen(false);
  });
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') setOpen(false);
  });
  document.addEventListener('click', function (event) {
    if (!event.target.closest('.pd-header')) setOpen(false);
  });
  window.addEventListener('resize', function () {
    if (window.innerWidth > 880) setOpen(false);
  });
})();
