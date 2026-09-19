document.addEventListener('DOMContentLoaded', function() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const counters = document.querySelectorAll('.stat-card strong, .about-stat-highlight, .about-stat-title');

    function animateCounter(counter) {
        const target = counter.dataset.target || counter.textContent;
        if (!counter.dataset.target) counter.dataset.target = target;
        
        const match = target.match(/(\d+)/);
        if (match && !prefersReducedMotion) {
            const endValue = parseInt(match[1], 10);
            const duration = 1500; // 1.5 seconds
            const startTime = performance.now();
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Use linear progression for a steady, even count-up speed
                // This prevents small numbers (like 6) from hanging on N-1 due to harsh easing
                const easeProgress = progress;
                const currentVal = Math.floor(easeProgress * endValue);
                
                counter.textContent = target.replace(match[1], currentVal);
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    counter.textContent = target; // ensure final exact match
                }
            }
            requestAnimationFrame(update);
        } else {
            counter.textContent = target;
        }
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                obs.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        threshold: 0.5
    });

    counters.forEach(counter => {
        observer.observe(counter);
    });
});
