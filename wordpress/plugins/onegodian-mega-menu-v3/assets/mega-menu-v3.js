/**
 * OneGodian Mega Menu v3 — keyboard, pointer, touch and reduced-motion aware controls.
 * No jQuery, tracking, external animation service or third-party dependencies.
 */
(() => {
  'use strict';

  function enhance(nav) {
    if (nav.dataset.ogmmReady === '1') return;
    nav.dataset.ogmmReady = '1';

    const mobileQuery = window.matchMedia('(max-width: 1040px)');
    const hoverQuery = window.matchMedia('(hover: hover) and (pointer: fine) and (min-width: 1041px)');
    const toggle = nav.querySelector('.ogmm-v3__mobile-toggle');
    const roots = Array.from(nav.querySelectorAll('[data-ogmm-item]'));
    const triggers = roots.map(item => item.querySelector('.ogmm-v3__trigger'));
    let active = null;
    let suppressFocusOpen = false;
    let closeDelay = null;

    const clearDelay = () => {
      if (closeDelay !== null) {
        window.clearTimeout(closeDelay);
        closeDelay = null;
      }
    };

    const closeActive = () => {
      clearDelay();
      if (!active) return;
      active.classList.remove('is-open');
      const button = active.querySelector('.ogmm-v3__trigger');
      if (button) button.setAttribute('aria-expanded', 'false');
      active = null;
    };

    const openItem = item => {
      clearDelay();
      if (active === item) return;
      closeActive();
      if (!item) return;
      active = item;
      item.classList.add('is-open');
      const button = item.querySelector('.ogmm-v3__trigger');
      if (button) button.setAttribute('aria-expanded', 'true');
    };

    const closeMobile = () => {
      if (!toggle) return;
      nav.classList.remove('is-mobile-open');
      toggle.setAttribute('aria-expanded', 'false');
      closeActive();
    };

    if (toggle) {
      toggle.addEventListener('click', () => {
        const open = !nav.classList.contains('is-mobile-open');
        nav.classList.toggle('is-mobile-open', open);
        toggle.setAttribute('aria-expanded', String(open));
        if (!open) closeActive();
      });
    }

    roots.forEach((item, index) => {
      const button = triggers[index];
      if (!button) return;
      button.addEventListener('click', () => {
        const alreadyOpen = active === item;
        if (alreadyOpen) closeActive();
        else openItem(item);
      });
      item.addEventListener('pointerenter', event => {
        if (hoverQuery.matches && event.pointerType !== 'touch') openItem(item);
      });
      item.addEventListener('pointerleave', event => {
        if (!hoverQuery.matches || event.pointerType === 'touch') return;
        clearDelay();
        closeDelay = window.setTimeout(() => {
          if (active === item && !item.contains(document.activeElement)) closeActive();
        }, 170);
      });
      item.addEventListener('focusin', () => {
        if (!mobileQuery.matches && !suppressFocusOpen) openItem(item);
      });
    });

    nav.addEventListener('keydown', event => {
      if (event.key === 'Escape') {
        if (active) {
          const old = active;
          closeActive();
          const button = old.querySelector('.ogmm-v3__trigger');
          if (button) {
            suppressFocusOpen = true;
            button.focus();
            suppressFocusOpen = false;
          }
          event.preventDefault();
        } else if (mobileQuery.matches && nav.classList.contains('is-mobile-open')) {
          closeMobile();
          if (toggle) toggle.focus();
          event.preventDefault();
        }
        return;
      }
      if (mobileQuery.matches || !['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
      const index = triggers.indexOf(document.activeElement);
      if (index === -1) return;
      let next = index;
      if (event.key === 'ArrowRight') next = (index + 1) % triggers.length;
      if (event.key === 'ArrowLeft') next = (index - 1 + triggers.length) % triggers.length;
      if (event.key === 'Home') next = 0;
      if (event.key === 'End') next = triggers.length - 1;
      if (triggers[next]) {
        triggers[next].focus();
        event.preventDefault();
      }
    });

    // Dismiss when tabbing completely out, without collapsing between submenu links.
    nav.addEventListener('focusout', () => {
      window.requestAnimationFrame(() => {
        if (!nav.contains(document.activeElement)) closeActive();
      });
    });

    document.addEventListener('pointerdown', event => {
      if (!nav.contains(event.target)) {
        if (mobileQuery.matches) closeMobile();
        else closeActive();
      }
    });

    mobileQuery.addEventListener('change', () => {
      clearDelay();
      closeMobile();
    });

    window.addEventListener('pagehide', clearDelay);
  }

  const initialize = () => document.querySelectorAll('[data-ogmm-v3]').forEach(enhance);
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initialize);
  else initialize();
})();
