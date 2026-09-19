document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.querySelector('.mobile-menu-toggle');
    const nav = document.querySelector('.mobile-nav');

    if (!toggle || !nav) return;

    function closeNav() {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        nav.setAttribute('aria-hidden', 'true');
        toggle.setAttribute('aria-label', toggle.getAttribute('data-label-open') || 'Open menu');
    }

    function openNav() {
        nav.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        nav.setAttribute('aria-hidden', 'false');
        toggle.setAttribute('aria-label', toggle.getAttribute('data-label-close') || 'Close menu');
    }

    // Store labels from initial HTML
    toggle.dataset.labelOpen  = toggle.getAttribute('aria-label') || 'Open menu';
    toggle.dataset.labelClose = 'Close menu';

    toggle.addEventListener('click', function(e) {
        e.stopPropagation();
        if (nav.classList.contains('is-open')) {
            closeNav();
        } else {
            openNav();
        }
    });

    // Close on outside click
    document.addEventListener('click', function(e) {
        if (nav.classList.contains('is-open') && !nav.contains(e.target) && !toggle.contains(e.target)) {
            closeNav();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && nav.classList.contains('is-open')) {
            closeNav();
            toggle.focus();
        }
    });
});
