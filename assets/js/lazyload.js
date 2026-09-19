document.addEventListener('DOMContentLoaded', function() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const image = entry.target;
                    image.src = image.getAttribute('data-src');
                    image.removeAttribute('data-src');
                    imageObserver.unobserve(image);
                }
            });
        }, {
            rootMargin: '0px 0px 50px 0px'
        });

        lazyImages.forEach(function(image) {
            imageObserver.observe(image);
        });
    } else {
        // Fallback for older browsers
        lazyImages.forEach(function(image) {
            image.src = image.getAttribute('data-src');
            image.removeAttribute('data-src');
        });
    }
});
