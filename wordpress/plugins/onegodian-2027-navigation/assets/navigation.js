/* Native <details> handles touch, pointer and keyboard activation. Close peer menus and support Escape. */
(function () {
  'use strict';
  document.addEventListener('toggle', function (event) {
    var target = event.target;
    if (!target || !target.matches || !target.matches('.og27-item[open]')) return;
    var parent = target.closest('.og27-menu');
    if (!parent) return;
    parent.querySelectorAll('.og27-item[open]').forEach(function (peer) {
      if (peer !== target) peer.open = false;
    });
  }, true);
  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') return;
    document.querySelectorAll('.og27-item[open], .og27-shell[open]').forEach(function (item) {
      item.open = false;
      var summary = item.querySelector(':scope > summary');
      if (summary && item.classList.contains('og27-item')) summary.focus();
    });
  });
  document.addEventListener('click', function (event) {
    document.querySelectorAll('.og27-nav .og27-item[open]').forEach(function (item) {
      if (!item.contains(event.target)) item.open = false;
    });
  });
})();
