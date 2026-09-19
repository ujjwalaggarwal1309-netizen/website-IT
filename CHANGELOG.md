# Changelog

All notable changes to the Infinity IT Solutions WordPress theme will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-07-20

### Added
- **Core Theme Setup:** Initialized foundational WordPress theme structure (v1.0.0).
- **Customizer Integration:** Registered custom settings for Phone, Email, Location, Business Hours, WhatsApp Number, and Social Media Links (`inc/customizer.php`).
- **Custom Post Type (Products):** Registered `products` CPT with custom taxonomies (`product-brand`, `product-form-factor`, `product-server-type`) and custom meta boxes (`inc/products.php`).
- **Enquiry System:** Created a robust, server-side validated PRG (Post-Redirect-Get) contact form (`inc/contact.php`).
- **Dynamic Enquiry Pre-fill:** Added JavaScript to read `?product=` URL parameters and automatically pre-fill the enquiry form subject field (`contact.js`).
- **SEO & Schema:** Integrated automated JSON-LD structured data (Organization, WebSite, WebPage, Product, BreadcrumbList) matching Google's latest specifications (`inc/schema.php`, `inc/seo.php`).
- **Performance Optimizations:** Deferred non-critical JavaScript, added `loading="lazy"` to below-fold images, and added `preconnect` hints for Google Fonts (`inc/performance.php`).
- **Accessibility:** Implemented WCAG 2.1 AA features including `role="banner"`, `role="main"`, `role="contentinfo"`, semantic `<nav>` elements, `.screen-reader-text` skip links, full keyboard `:focus-visible` states, and `prefers-reduced-motion` CSS/JS toggles.
- **Brand Assets:** Inlined SVG logo directly into DOM via `file_get_contents()` for perfect font rendering and styling control.
- **UI Components:** Built fully responsive, grid-based layouts for Homepage, About, Contact, Product Archive, and Single Product templates.
- **Micro-interactions:** Integrated IntersectionObserver-driven scroll reveals and counter animations (`animations.js`, `counter.js`).
- **Security:** Hardened all templates with strict escaping (`esc_html`, `esc_attr`, `esc_url`) and nonce validation on forms.

### Changed
- Removed redundant `modal.js` logic and integrated UI state management into `contact.js`.
- Refactored all CSS into modular files (variables, utilities, header, footer, responsive) for easier maintenance.

### Fixed
- Fixed mobile navigation menu closing behavior (now closes on Escape key and outside click).
- Fixed ARIA label toggling on the mobile menu button for screen readers.
- Fixed the back-to-top button which was previously visible on page load; now accurately fades in based on scroll position.
- Fixed missing `is_singular('products')` check so product pages correctly enqueue enquiry scripts.
