document.addEventListener('DOMContentLoaded', function() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) {
        document.querySelectorAll('.hero-section, .card-premium, .stat-card, .cta-card, .site-footer, .product-card, .motion-fade, .motion-slide-up, .motion-scale').forEach(function(el) {
            el.classList.add('is-visible');
        });
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                // Remove the CSS stagger delay after the entrance animation completes
                // so that hover interactions become instantly smooth (no lag on hover out)
                setTimeout(() => {
                    entry.target.style.transitionDelay = '0ms';
                }, 1200);
                obs.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.hero-section, .card-premium, .stat-card, .cta-card, .site-footer, .product-card, .motion-fade, .motion-slide-up, .motion-scale').forEach(function(el) {
        observer.observe(el);
    });

    // Expose a global helper so AJAX-injected product cards can be made visible
    window.ithsRevealCards = function(container) {
        (container || document).querySelectorAll('.product-card').forEach(function(card) {
            card.classList.add('is-visible');
            card.style.transitionDelay = '0ms';
        });
    };

    // Interactive Feature Cards Glow Effect
    const glowCards = document.querySelectorAll('.card-premium');
    glowCards.forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty('--x', `${x}px`);
            card.style.setProperty('--y', `${y}px`);
        });
    });
});
