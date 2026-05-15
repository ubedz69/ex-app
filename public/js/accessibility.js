document.addEventListener('DOMContentLoaded', function () {
  const menuButton = document.getElementById('menu-button');
  const mainNav = document.getElementById('main-nav');

  if (menuButton && mainNav) {
    menuButton.addEventListener('click', function () {
      const open = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', String(!open));
      mainNav.classList.toggle('open', !open);
      if (!open) {
        // move focus to first link
        const first = mainNav.querySelector('a');
        if (first) first.focus();
      } else {
        menuButton.focus();
      }
    });

    // Close menu on Escape
    document.addEventListener('keyup', function (e) {
      if (e.key === 'Escape' || e.key === 'Esc') {
        if (mainNav.classList.contains('open')) {
          mainNav.classList.remove('open');
          menuButton.setAttribute('aria-expanded', 'false');
          menuButton.focus();
        }
      }
    });
  }

  // Enhance skip link behavior for browsers that don't focus anchors
  const skip = document.querySelector('.skip-link');
  if (skip) {
    skip.addEventListener('click', function (e) {
      const id = this.getAttribute('href')?.slice(1);
      const el = document.getElementById(id);
      if (el) {
        el.setAttribute('tabindex', '-1');
        el.focus();
      }
    });
  }
});
