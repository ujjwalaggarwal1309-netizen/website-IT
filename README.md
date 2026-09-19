# Infinity IT Solutions WordPress Theme

A custom, enterprise-grade WordPress theme developed for Infinity IT Solutions. Designed to generate high-quality B2B hardware enquiries across India, focusing on performance, accessibility, and modern aesthetics.

## Features

- **Custom Product Catalog:** Built around a `products` Custom Post Type (CPT) with custom taxonomies (Brands, Form Factors, Server Types) and custom metadata (Specs, EOL Status, PDF Datasheets).
- **Enquiry Flow:** A friction-free, PRG-compliant (Post-Redirect-Get) enquiry system. Seamlessly passes product context via URLs (`?product=...`) into a secure server-side validated contact form.
- **Enterprise Aesthetics:** Clean, trustworthy design utilizing Inter typography, strict color tokens (Deep Navy, Infinity Orange), and smooth, accessible micro-interactions.
- **Performance Optimized:** No jQuery. Vanilla JS deferred. Native `loading="lazy"` on images. Preconnect hints for external assets.
- **Fully Accessible:** WCAG 2.1 AA compliant. Semantic HTML5 landmarks, ARIA labels, focus management, and `prefers-reduced-motion` support.
- **SEO & Structured Data:** Automatically generates JSON-LD schema (Organization, WebSite, WebPage, Product, BreadcrumbList) based on verified company facts. Auto-defers to Yoast/RankMath when installed.

## Directory Structure

```text
/
├── style.css           # Theme metadata and main stylesheet (imports from /assets/css)
├── functions.php       # Theme setup and bootstrap
├── 404.php             # Custom 404 error page
├── index.php           # Fallback template
├── front-page.php      # Homepage template
├── page-about.php      # About Us template
├── page-contact.php    # Contact template
├── single-products.php # Product details template
├── archive-products.php# Products catalog / search results template
├── taxonomy-*.php      # Category-specific templates
│
├── /assets/            # Static assets
│   ├── /css/           # Modular CSS files (variables, utilities, header, etc.)
│   ├── /js/            # Vanilla JS modules (animations, navigation, contact)
│   ├── /images/        # Default graphics / placeholders
│   └── /icons/         # SVG icons
│
├── /inc/               # PHP logic and hooks
│   ├── setup.php       # Theme supports & image sizes
│   ├── enqueue.php     # Asset loading
│   ├── customizer.php  # Theme settings (Phone, Email, URLs)
│   ├── products.php    # CPT & Taxonomy registration
│   ├── contact.php     # Form processing (PRG pattern)
│   ├── schema.php      # JSON-LD generation
│   └── seo.php         # Meta tag generation
│
└── /template-parts/    # Reusable UI components
    ├── hero.php        # Homepage hero
    ├── contact-form.php# Form markup
    ├── product-card.php# Product grid item
    └── ...
```

## Setup Instructions

1. **Install Theme:** Upload the theme folder to `wp-content/themes/infinity-it-solutions`.
2. **Activate:** Go to Appearance > Themes and activate "Infinity IT Solutions".
3. **Configure Customizer:** Go to Appearance > Customize > Infinity Settings to configure the top bar phone number, email address, WhatsApp number, and social links.
4. **Set Permalinks:** Go to Settings > Permalinks and select "Post name" (required for the PRG form redirect and custom post type routing).
5. **Assign Menus:** Go to Appearance > Menus and assign a menu to the "Primary Menu" and "Mobile Menu" locations.
6. **Assign Pages:** Go to Settings > Reading and set "Your homepage displays" to "A static page", selecting your Homepage and Blog pages respectively.

## Recommended Plugins

While the theme is fully functional out of the box, the following plugins are recommended for a production environment:

- **WP Mail SMTP:** To ensure reliable delivery of the contact form enquiries.
- **Yoast SEO or Rank Math:** For XML sitemaps and advanced on-page SEO overrides. (The theme's built-in SEO gracefully yields to these plugins).
- **Classic Editor (Optional):** If you prefer standard meta boxes over the block editor for the Products CPT.

## License

GPL-2.0-or-later
