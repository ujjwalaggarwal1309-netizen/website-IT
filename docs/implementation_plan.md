# Infinity IT Solutions — Complete Implementation Roadmap

## Documentation Summary

### WEBSITE_DESIGN_SPEC.md
- Defines the visual design system: Blue (#1B4DDB / #0D2665) + Orange (#F58220) brand palette, Inter typeface throughout, 1200px container, 80px section padding, 12px card radius.
- Specifies all page layouts and components: sticky top-bar + navbar, 4-card product category grid, stats bar, CTA banner, sidebar+grid product catalog, contact form + info cards + map.
- Recommends Contact Form 7 / WPForms for enquiry form and floating WhatsApp button.

### WEBSITE_CONTENT.md
- **Single source of truth for all copy.** Company name is **Infinity IT Solutions** (not "IT Hardware Supply"). Phone: +91 83988 39899. Email: Vivekkumar@infinityitsolutions.co.in. Hours: Mon–Sat 9:00 AM–6:00 PM.
- Product categories (5 confirmed): Enterprise Servers, Storage Solutions, Professional Workstations, Business Desktops, Server Spare Parts. Hero headline: "Powering Businesses with Reliable Enterprise IT Hardware Since 2018". CTAs: "Explore Products" (primary) and "Request a Quote" (secondary).
- Strict content rules: no pricing, no authorized partner claims, no invented statistics, professional B2B tone throughout.

### VERIFIED_COMPANY_FACTS.md
- Verified facts: Established 2018, Independent Distributor & Supplier, B2B, PAN India, Warranty 90 days–1 year. Supported platforms: HP, Dell, Lenovo, Cisco, Fujitsu, Oracle, IBM, NetApp (not authorized partners).
- Detailed product taxonomy: 19 product types including server memory, SSDs, NVMe, SAS/SATA drives, GPU Cards, RAID/HBA/FC Cards, motherboards, power supplies.
- Industries served: IT Companies, System Integrators, Data Centers, Corporate Enterprises, SMEs, IT-Based Industries.

### LOGO_ASSET_PACK.md
- Requires 8 logo SVG/PNG variants: primary, light, dark, icon-only — none of which exist yet in `/assets/logo/`. Wordmark "INFINITY" bold geometric + "IT SOLUTIONS" accent orange.
- Requires full favicon set (6 files + site.webmanifest) and OG social image (1200×630). All assets must reside in `/assets/logo/`, `/assets/favicon/`, `/assets/social/`.
- Logo: max 48px height in header (SVG preferred), light variant on dark backgrounds, icon-only for favicons.

---

## ⚠️ Conflicts & Issues Identified

> [!IMPORTANT]
> The following conflicts exist between documentation and the existing code. Documentation wins in all cases.

| # | Conflict | Code Says | Documentation Says | Resolution |
|---|----------|-----------|-------------------|------------|
| 1 | **Company name** | `IT Hardware Supply` (in `style.css`, `customizer.php` defaults, `functions.php` text domain) | `Infinity IT Solutions` | Update Theme Name, all defaults, text domain slug |
| 2 | **Hero headline** | `Your Trusted IT Hardware Supply Partner` | `Powering Businesses with Reliable Enterprise IT Hardware Since 2018` | Update default in customizer |
| 3 | **Hero CTA buttons** | `Browse Products` / `Get In Touch` | `Explore Products` / `Request a Quote` | Update customizer defaults |
| 4 | **Hero subtext** | `Serving businesses across India since 2018` (one line) | Full paragraph from WEBSITE_CONTENT.md | Update default + hero.php |
| 5 | **Phone number** | `+91 98765 43210` (placeholder) | `+91 83988 39899` | Update customizer defaults |
| 6 | **Email** | `info@ithardwaresupply.com` | `Vivekkumar@infinityitsolutions.co.in` | Update defaults |
| 7 | **Working hours** | `Mon-Fri 9:00-18:00` | `Monday–Saturday 9:00 AM–6:00 PM` | Update defaults |
| 8 | **Address** | `Mumbai, India` (placeholder) | Delhi, India (from DESIGN_SPEC "Delhi-based") | Confirm with user OR leave as Customizer field |
| 9 | **WhatsApp number** | `919876543210` (placeholder) | `918398839899` (derived from +91 83988 39899) | Update defaults |
| 10 | **Footer description** | `B2B hardware supply for modern business environments.` | Full text from WEBSITE_CONTENT.md | Update default |
| 11 | **Stats bar** | `7+ Years / 500+ Products / 1000+ Happy Clients / Pan India` | VERIFIED_COMPANY_FACTS says no invented stats. "1000+ Happy Clients" is **unverified**. Stats should be: Established 2018 / PAN India Supply / 90 Days–1 Year Warranty / Enterprise Hardware Specialists | Replace stats content |
| 12 | **Product categories (taxonomy)** | 6 categories: Servers & Workstations, Networking Equipment, Storage Solutions, Desktops & Laptops, Printers & Scanners, Cables & Accessories | VERIFIED docs show 5 primary categories: Enterprise Servers, Storage Solutions, Professional Workstations, Business Desktops, Server Spare Parts. DESIGN_SPEC also lists a slightly different 6-category set. | **Clarification requested below** |
| 13 | **Category card titles** | 4 cards: Servers & Workstations / Networking / Storage / Computing & Peripherals | WEBSITE_CONTENT.md shows 5 categories: Enterprise Servers / Storage Solutions / Professional Workstations / Business Desktops / Server Spare Parts | Update to match content doc |
| 14 | **Contact form submit button** | `Submit` | `Send Enquiry` | Update button text |
| 15 | **CTA heading** | `Looking for IT Hardware?` | `Looking for Reliable Enterprise IT Hardware?` | Update default |
| 16 | **Company description footer** | Short placeholder | Full text from WEBSITE_CONTENT.md footer section | Update |
| 17 | **Copyright** | Dynamic year only | `© 2018–2026 Infinity IT Solutions. All Rights Reserved.` | Update footer.php |
| 18 | **Header CTA** | `Contact Us` | `Enquire Now` (per DESIGN_SPEC) | Update default |
| 19 | **components.css** | Conflicting CSS: defines separate design system (different radius, different button styles, `btn-secondary` = orange not outline) overriding the main system | Should not exist / conflicts with all other CSS | Remove or clear `components.css` — it is dead weight and a duplicate |
| 20 | **social.php** | File is completely empty (only PHP header) | `it_hardware_social_links()` is defined in `company-settings.php`, making `social.php` redundant | Remove require in functions.php or delete file |
| 21 | **Logo assets** | Zero logo files exist in `/assets/` | Requires 8 logo variants + full favicon set + OG image | Create all logo/favicon assets |
| 22 | **Category icons** | `server.svg`, `network.svg`, `storage.svg`, `computer.svg` referenced but do not exist | Icons required for category cards | Create SVG icons |
| 23 | **contact.php form** | No CSRF nonce on contact form — security vulnerability | WordPress best practices require `wp_nonce_field()` | Add nonce verification |
| 24 | **contact.php email** | Sends to `admin_email`, not to `Vivekkumar@infinityitsolutions.co.in` | Should go to company email | Update |
| 25 | **enqueue.php** | Enqueues `style.css` (root) but root `style.css` already @imports `assets/css/style.css` which is a SECOND stylesheet — potential double-load | Root `style.css` should be the single entry point | Audit and fix |
| 26 | **`assets/css/style.css`** | Self-contained file importing all partials — this mirrors what `style.css` (root) already does | Redundant. Root `style.css` is the WordPress entry point | `assets/css/style.css` should be removed / not loaded separately |
| 27 | **Homepage: About Preview section** | Missing entirely from `front-page.php` | WEBSITE_CONTENT.md specifies an "About Preview" section + "Why Choose Us" section on the homepage | Must add |
| 28 | **Homepage: Brands section** | Missing entirely | WEBSITE_CONTENT.md specifies "Brands We Support" section | Must add |
| 29 | **About page: page header** | `page-about.php` has no styled page header section | All inner pages should have a blue gradient page header per DESIGN_SPEC | Must add |
| 30 | **SEO / Schema** | No meta description, no Open Graph tags, no JSON-LD schema markup | Required per TECHNICAL REQUIREMENTS | Must add |
| 31 | **Google Fonts** | Inter font is referenced in CSS but never loaded via `wp_enqueue_style` or `<link>` in header | Will fall back to system font | Must enqueue Inter from Google Fonts |
| 32 | **Favicon** | Zero favicon files exist | Full favicon set required per LOGO_ASSET_PACK.md | Must create and register |
| 33 | **Mobile nav styling** | `.mobile-nav.is-open` has no CSS — mobile menu toggle works in JS but menu is invisible when open | Critical broken feature | Must add CSS |
| 34 | **Scroll-triggered animations** | `animations.js` adds `is-visible` immediately on DOMContentLoaded, not on scroll — defeats the purpose of scroll animations | Should use IntersectionObserver | Fix |
| 35 | **Counter animation** | Runs on DOMContentLoaded regardless of scroll position — counters fire before user sees stats bar | Should trigger on IntersectionObserver | Fix |
| 36 | **LazyLoad** | `lazyload.js` immediately sets `src` from `data-src` — not truly lazy-loading, defeats purpose | Should use IntersectionObserver | Fix |
| 37 | **Back-to-top button** | Hidden by default but no JS to show/hide on scroll | Non-functional | Fix |
| 38 | **Product search** | Search form uses `action` pointing to products archive but standard WordPress search uses `?s=` on root. Products CPT search is not customized to filter products only | Broken search | Fix |
| 39 | **taxonomy-product-category.php** | Missing pagination (unlike archive-products.php) | Inconsistency | Fix |
| 40 | **`page-about.php`** | Missing page header section with blue gradient background | All inner pages need it per DESIGN_SPEC | Fix |
| 41 | **`page-contact.php`** | Missing page header section | Same issue | Fix |
| 42 | **`hero.php`** | Hero subtext is one line; content doc requires full multi-line paragraph | Fix |

> [!CAUTION]
> **Open Question — Product Categories:** The taxonomy currently seeds 6 categories (Design Spec list). VERIFIED_COMPANY_FACTS.md lists 5 primary categories. WEBSITE_CONTENT.md confirms 5. The 6-category DESIGN_SPEC list predates the finalized content doc. I will follow **VERIFIED_COMPANY_FACTS.md + WEBSITE_CONTENT.md** (5 categories) unless you instruct otherwise before Phase 2 begins.

> [!CAUTION]
> **Open Question — Physical Address:** The DESIGN_SPEC says "Delhi-based" but no street address is confirmed in VERIFIED_COMPANY_FACTS.md. The address field will remain as a Customizer-editable field with placeholder "Delhi, India" until confirmed.

> [!CAUTION]
> **Open Question — WhatsApp Number:** The verified phone is +91 83988 39899. Shall I use this same number for the WhatsApp link (`wa.me/918398839899`)?

---

## Theme Audit Summary

### ✅ Completed Features (working scaffolding)
- Custom Post Type `products` registered with REST API, archive, rewrite rules
- Custom taxonomy `product-category` registered and hierarchical
- Product spec meta fields (processor, memory, storage, ports, connectivity, power, dimensions, warranty)
- CSS variable system (correct colors, correct design tokens)
- All page templates present: front-page, about, contact, archive-products, taxonomy, single-product, 404
- All CSS partials: variables, utilities, animations, header, homepage, products, about, contact, footer, responsive
- All JS files: main, navigation, animations, counter, lazyload, filter, search, modal, contact
- Breadcrumbs (Home > Page > Sub) with taxonomy support
- Pagination with `paginate_links()`
- Related products query using taxonomy
- Contact form with PHP sanitization
- Footer: 3-column layout, social links, WhatsApp button, back-to-top
- Sticky header with hamburger toggle
- Top info bar
- Customizer panel with company settings
- Performance: `defer` on all scripts
- Security: emoji stripping
- WordPress theme supports: title-tag, post-thumbnails, custom-logo, html5, responsive-embeds
- Image sizes registered: hero, product-card, product-detail, about-image, og-image
- Menus: primary, footer, mobile, top-bar
- Widget areas: sidebar + 3 footer columns

### ❌ Missing Features (must build)
- All logo and brand assets (SVGs, PNGs, favicons, OG image)
- Category icon SVGs (server, network, storage, computer, workstation, spare-parts)
- Homepage: About Preview section
- Homepage: "Why Choose Us" section (quick version)
- Homepage: Brands We Support section
- Scroll-triggered IntersectionObserver animations (proper)
- SEO: meta description output, Open Graph tags, JSON-LD Organization schema
- Google Fonts enqueue (Inter)
- Favicon registration in `wp_head`
- Page header section (blue gradient) for About and Contact pages
- Contact page heading: "Let's Discuss Your IT Infrastructure Requirements"
- `site.webmanifest` file
- `og-image.png` social image

### 🔴 Broken Features (must fix)
- Mobile nav open state has no CSS (menu invisible when toggled)
- Back-to-top button non-functional (no show/hide JS)
- Animations: fire immediately, not on scroll
- Counters: fire immediately, not on scroll
- LazyLoad: not actually lazy
- Contact form: no CSRF nonce (security)
- Contact form email: goes to `admin_email` not company email
- Contact form button text says "Submit" not "Send Enquiry"
- Product search: not filtering CPT correctly
- `components.css`: conflicting duplicate CSS system

### ⚠️ Technical Debt (must resolve)
- All customizer defaults use wrong company name, phone, email, hours
- Hero content does not match WEBSITE_CONTENT.md
- Stats bar shows invented "1000+ Happy Clients" — violates content rules
- Category cards reference non-existent SVG icons
- `social.php` is empty but included
- `assets/css/style.css` is a redundant stylesheet entry point
- `components.css` is a conflicting dead stylesheet
- Footer copyright doesn't include "2018–" prefix
- Theme Name in `style.css` says "IT Hardware Supply" not "Infinity IT Solutions"

### 🔁 Duplicate Features
- `it_hardware_social_links()` defined in `company-settings.php`, `social.php` is empty duplicate include
- CSS entry point duplicated: root `style.css` and `assets/css/style.css` both define the same @import chain

---

## Implementation Roadmap

---

### Phase 1 — Branding, Data Corrections & Critical Fixes
**Objective:** Establish the correct identity, fix all incorrect data, eliminate broken/conflicting code, and make the theme deployable without errors.

**Files Affected:**
| File | Action |
|------|--------|
| `style.css` (root) | Update Theme Name to "Infinity IT Solutions" |
| `inc/customizer.php` | Update ALL defaults: company name, phone, email, hours, address, WhatsApp, hero text, CTAs, footer copy, CTA text |
| `inc/contact.php` | Fix: add wp_nonce_field + nonce verification, change recipient to company email |
| `assets/css/components.css` | **Delete/empty** — conflicting duplicate CSS system |
| `assets/css/style.css` | **Remove** — redundant entry point (root `style.css` already handles this) |
| `inc/social.php` | Move `it_hardware_social_links()` here from `company-settings.php`, clean up `company-settings.php` |
| `template-parts/stats.php` | Replace invented stats with verified facts: Established 2018 / PAN India Supply / 90 Days–1 Year Warranty / Enterprise Hardware Specialists |
| `template-parts/hero.php` | Update to use multi-line subtext (hero_description field), correct CTA labels |
| `template-parts/contact-form.php` | Button text "Submit" → "Send Enquiry", add nonce field |
| `template-parts/cta.php` | CTA button "Request a Quote" |
| `inc/product-taxonomy.php` | Update seeded categories to match verified 5-category list |
| `template-parts/category-card.php` | Update to 5 categories matching content doc |
| `footer.php` | Fix copyright: `© 2018–{year} Infinity IT Solutions. All Rights Reserved.` |
| `inc/enqueue.php` | Add Google Fonts (Inter) enqueue, fix script loading |

**Estimated Work:** ~6–8 hours
**Dependencies:** None — foundational
**Acceptance Criteria:**
- Theme Name = "Infinity IT Solutions" in `style.css`
- All contact details match VERIFIED_COMPANY_FACTS.md
- No invented statistics visible on any page
- Contact form has nonce protection and sends to company email
- No duplicate or conflicting CSS entry points
- Copyright string correct in footer

---

### Phase 2 — Logo, Favicon & Brand Assets
**Objective:** Create all required brand assets per LOGO_ASSET_PACK.md: SVG logos (4 variants), PNG exports, favicon set, OG image, category icons.

**Files Affected:**
| File | Action |
|------|--------|
| `assets/logo/logo-primary.svg` | [NEW] Navy wordmark on white |
| `assets/logo/logo-primary.png` | [NEW] PNG export |
| `assets/logo/logo-light.svg` | [NEW] White wordmark for dark backgrounds |
| `assets/logo/logo-light.png` | [NEW] PNG export |
| `assets/logo/logo-dark.svg` | [NEW] Same as primary (for documents) |
| `assets/logo/logo-dark.png` | [NEW] PNG export |
| `assets/logo/logo-icon.svg` | [NEW] Icon-only variant |
| `assets/logo/logo-icon.png` | [NEW] PNG export |
| `assets/favicon/favicon.ico` | [NEW] |
| `assets/favicon/favicon-16x16.png` | [NEW] |
| `assets/favicon/favicon-32x32.png` | [NEW] |
| `assets/favicon/apple-touch-icon.png` | [NEW] |
| `assets/favicon/android-chrome-192x192.png` | [NEW] |
| `assets/favicon/android-chrome-512x512.png` | [NEW] |
| `assets/favicon/site.webmanifest` | [NEW] |
| `assets/social/og-image.png` | [NEW] 1200×630 brand social card |
| `assets/icons/server.svg` | [NEW] Category icon |
| `assets/icons/storage.svg` | [NEW] Category icon |
| `assets/icons/workstation.svg` | [NEW] Category icon |
| `assets/icons/desktop.svg` | [NEW] Category icon |
| `assets/icons/spare-parts.svg` | [NEW] Category icon |
| `inc/setup.php` | Register favicon, add `add_action('wp_head', ...)` for favicon links |
| `header.php` | Switch logo to use `assets/logo/logo-primary.svg` when no custom logo set |
| `footer.php` | Switch to `assets/logo/logo-light.svg` in footer |

**Estimated Work:** ~4–5 hours
**Dependencies:** Phase 1 (company name finalized)
**Acceptance Criteria:**
- Logo renders correctly in header (dark) and footer (light variant)
- Favicon appears in browser tab
- OG image meta tag present in `<head>`
- No broken image references anywhere

---

### Phase 3 — Homepage Completion
**Objective:** Complete all missing homepage sections per WEBSITE_CONTENT.md: About Preview, Why Choose Us (6 cards), Brands We Support. Fix hero content to match content doc exactly.

**Files Affected:**
| File | Action |
|------|--------|
| `front-page.php` | Add About Preview, Why Choose Us, Brands sections |
| `template-parts/hero.php` | Full content doc headline + multi-paragraph subtext, correct CTAs |
| `template-parts/about-preview.php` | [NEW] "Your Trusted Enterprise IT Hardware Partner" section |
| `template-parts/why-choose-us.php` | [NEW] 6 feature cards per WEBSITE_CONTENT.md (Established 2018, Cost-effective IT Infrastructure Solutions Specialists, PAN India, Installation Support, Warranty Support, Customer-First) |
| `template-parts/brands.php` | [NEW] Brands We Support grid (HP, Dell, Lenovo, Cisco, Fujitsu, Oracle, IBM, NetApp) with disclaimer |
| `assets/css/homepage.css` | Add styles for new sections |
| `inc/customizer.php` | Add hero_description (full para), about_preview fields, CTA settings per content doc |

**Estimated Work:** ~5–6 hours
**Dependencies:** Phase 1 + Phase 2 (logo for about-preview section)
**Acceptance Criteria:**
- Homepage has: Hero → Product Categories → About Preview → Why Choose Us → Stats Bar → Brands → CTA
- All copy matches WEBSITE_CONTENT.md exactly
- No invented facts visible
- CTA buttons: "Explore Products" and "Request a Quote"

---

### Phase 4 — About Page Completion
**Objective:** Complete the About Us page per DESIGN_SPEC: page header, full Who We Are content, Mission/Vision/Values, Why Choose Us (6 cards), About CTA — all with correct content from content doc.

**Files Affected:**
| File | Action |
|------|--------|
| `page-about.php` | Add page-header section (blue gradient), complete template structure |
| `template-parts/about-content.php` | Replace placeholder text with full WEBSITE_CONTENT.md copy; add Core Values list; add Industries Served |
| `template-parts/mission-card.php` | Update Mission/Vision/Values text to match content doc |
| `assets/images/about-image.svg` | Replace with proper about-page illustration |
| `assets/css/about.css` | Add page header, core values, industries styles |
| `inc/customizer.php` | Add full about body copy fields |

**Estimated Work:** ~4–5 hours
**Dependencies:** Phase 1 (correct data), Phase 2 (logo)
**Acceptance Criteria:**
- Page header renders with blue gradient + "About Infinity IT Solutions" title + breadcrumb
- Full company narrative per content doc
- Correct Mission, Vision, Values text
- CTA: "Contact Our Experts" linking to `/contact/`

---

### Phase 5 — Contact Page Completion
**Objective:** Complete contact page with all required elements: page header, working enquiry form with correct fields, contact info cards with verified data, Google Map, CTA.

**Files Affected:**
| File | Action |
|------|--------|
| `page-contact.php` | Add page header, complete layout with intro heading from content doc |
| `template-parts/contact-form.php` | Add all fields per content doc (Full Name, Email, Phone, Company Name, Subject dropdown, Message), "Send Enquiry" button, nonce, ARIA labels |
| `template-parts/contact-card.php` | Add icons, verified data, link mailto/tel; add Working Hours card |
| `template-parts/google-map.php` | Ensure map embed is sanitized and responsive |
| `inc/contact.php` | Full form handler: nonce verify, sanitize, `wp_mail()` to company email, redirect with success/error query var |
| `assets/css/contact.css` | Page header, contact info card icons, form field focus states, success/error messages |

**Estimated Work:** ~4–5 hours
**Dependencies:** Phase 1
**Acceptance Criteria:**
- Contact page intro: "Let's Discuss Your IT Infrastructure Requirements"
- Form submits, sends email to `Vivekkumar@infinityitsolutions.co.in`
- CSRF nonce protection active
- All 4 contact info cards show verified data
- Success message shown after submission
- Google map renders and is responsive

---

### Phase 6 — Products Page & Single Product Completion
**Objective:** Polish the products archive, taxonomy page, single product template; fix product search; add product page header; fix taxonomy pagination; ensure enquiry flow is seamless.

**Files Affected:**
| File | Action |
|------|--------|
| `archive-products.php` | Update page heading to "Enterprise IT Hardware Solutions" per content doc, add intro paragraph |
| `taxonomy-product-category.php` | Add pagination (currently missing), add product count |
| `single-products.php` | Add "Request a Quote" secondary action in addition to "Enquire Now"; add schema markup |
| `template-parts/product-card.php` | Add missing product thumbnail fallback (placeholder SVG if no image), add aria-label on enquiry button |
| `template-parts/product-filter.php` | Highlight active category with CSS class, add "All Products" link at top |
| `template-parts/product-search.php` | Fix action URL, add `post_type=products` hidden input |
| `inc/products.php` | Fix search to filter by CPT, add meta box registration for admin |
| `assets/css/products.css` | Active filter state, product card thumbnail placeholder, search bar styling |

**Estimated Work:** ~4–5 hours
**Dependencies:** Phase 1, Phase 2 (icons)
**Acceptance Criteria:**
- Products page heading matches content doc
- Product search returns only `products` CPT results
- Category filter highlights active term
- Pagination works on taxonomy pages
- Product cards show placeholder image if no featured image set
- "Enquire Now" links to contact with product name pre-filled

---

### Phase 7 — SEO, Schema & Meta Tags
**Objective:** Implement all SEO requirements: dynamic meta descriptions, Open Graph tags, JSON-LD Organization schema, canonical URLs, semantic HTML review.

**Files Affected:**
| File | Action |
|------|--------|
| `inc/seo.php` | [NEW] Output `<meta name="description">`, Open Graph (og:title, og:description, og:image, og:url, og:type), Twitter card, canonical URL |
| `inc/schema.php` | [NEW] JSON-LD: Organization schema (name, URL, phone, email, address, sameAs), WebSite schema, BreadcrumbList per page |
| `header.php` | Hook seo.php and schema.php output before `wp_head()` |
| `functions.php` | Include seo.php and schema.php |
| `inc/customizer.php` | Add meta_description field per page, og_description field |
| `single-products.php` | Add Product JSON-LD schema (name, description, brand disclaimer) |
| All page templates | Ensure single `<h1>` per page, semantic landmark elements |

**Estimated Work:** ~4–5 hours
**Dependencies:** Phase 1, Phase 2 (OG image)
**Acceptance Criteria:**
- `<meta name="description">` present on all pages (unique per page)
- Open Graph tags present with correct image reference
- Organization JSON-LD valid (test with Google Rich Results Test)
- BreadcrumbList JSON-LD on all inner pages
- All pages have single H1, proper heading hierarchy
- Canonical tags present

---

### Phase 8 — JavaScript, Animations & Interactions
**Objective:** Fix all broken JS features: IntersectionObserver for animations and counters, proper lazy-loading, back-to-top visibility, mobile nav polish, enquiry button pre-fill.

**Files Affected:**
| File | Action |
|------|--------|
| `assets/js/animations.js` | Rewrite: use IntersectionObserver to trigger `is-visible` on scroll |
| `assets/js/counter.js` | Rewrite: trigger counter animation only when stats section enters viewport |
| `assets/js/lazyload.js` | Rewrite: use IntersectionObserver (native lazy load + `loading="lazy"` on `<img>`) |
| `assets/js/main.js` | Add: back-to-top show/hide on scroll (threshold 300px), smooth scroll |
| `assets/js/navigation.js` | Add: close mobile nav on outside click, ARIA expanded attribute toggle, trap focus |
| `assets/js/contact.js` | Add: read `?product=` URL param and pre-fill subject field |
| `assets/js/modal.js` | Remove or implement properly — currently just sets body dataset with no effect |
| `assets/css/animations.css` | Update: add `opacity: 0` initial state, `is-visible` class transition |
| `assets/css/header.css` | Add: `.mobile-nav.is-open` display styles |

**Estimated Work:** ~4–5 hours
**Dependencies:** Phase 1 (no conflicting CSS)
**Acceptance Criteria:**
- Elements animate on scroll into view (not on page load)
- Counters animate when stats section is visible
- Images lazy-load correctly
- Back-to-top appears after scrolling 300px
- Mobile nav opens/closes correctly with ARIA states
- Enquiry buttons correctly pre-fill contact form subject

---

### Phase 9 — Accessibility, Performance & Code Quality
**Objective:** Final accessibility sweep, Core Web Vitals optimization, WordPress coding standards compliance, remove all remaining technical debt.

**Files Affected:**
| File | Action |
|------|--------|
| `header.php` | Add `role="banner"`, `aria-label` on nav, skip-link properly positioned |
| `footer.php` | Add `role="contentinfo"` |
| All templates | Audit: alt text on all `<img>` tags, form labels, button text, ARIA roles |
| `inc/performance.php` | Add: preconnect hint for Google Fonts, prefetch for critical resources |
| `inc/enqueue.php` | Add `media="print" onload` trick for non-critical CSS; verify `defer` on all JS |
| `assets/css/*.css` | Audit: remove any remaining duplicate declarations, fix missing `components.css` import if cleared |
| `functions.php` | Add: `add_theme_support('wc-product-gallery-zoom')` if needed; final cleanup of unused requires |
| All PHP files | Verify: all output escaped with correct escape functions, no raw `echo` on user data |
| `inc/contact.php` | Add: redirect after POST to prevent form re-submission on refresh |
| `404.php` | Review and ensure it follows theme styling |

**Estimated Work:** ~4–5 hours
**Dependencies:** All preceding phases
**Acceptance Criteria:**
- 0 accessibility errors in WAVE or axe-core
- All images have meaningful alt text or `alt=""`  decoratively correct
- All form fields have labels
- Skip-to-content link functional
- Google PageSpeed Insights score ≥ 85 mobile (target)
- 0 PHP warnings or deprecated notices
- 0 duplicate CSS declarations
- Contact form uses POST-redirect-GET pattern

---

### Phase 10 — Final Integration, Testing & Production Readiness
**Objective:** Full end-to-end QA, cross-browser testing, WordPress admin UX verification, setup guide for client.

**Files Affected:**
| File | Action |
|------|--------|
| `README.md` | Update with full installation guide, Customizer fields reference, menu setup instructions |
| `languages/theme.pot` | Regenerate `.pot` file with all translatable strings |
| `inc/setup.php` | Verify all `add_theme_support()` declarations are correct |
| `screenshot.png` | Replace placeholder with actual theme screenshot (1200×900) |

**Estimated Work:** ~3–4 hours
**Dependencies:** All phases complete
**Acceptance Criteria:**
- Theme activates without PHP errors
- All Customizer fields pre-populated with correct verified data
- Menus: Primary, Footer, Mobile all work correctly
- Contact form sends email successfully in staging
- All pages render correctly at 1200px, 1024px, 768px, 375px
- No console errors in browser
- README.md provides clear client setup instructions
- Git tagged with `v1.0.0-production`

---

## Phase Summary

| Phase | Focus | Est. Hours | Priority |
|-------|-------|-----------|----------|
| 1 | Branding, data, critical bug fixes | 6–8h | 🔴 Critical |
| 2 | Logo, favicon, brand assets | 4–5h | 🔴 Critical |
| 3 | Homepage completion | 5–6h | 🟠 High |
| 4 | About page completion | 4–5h | 🟠 High |
| 5 | Contact page completion | 4–5h | 🟠 High |
| 6 | Products pages | 4–5h | 🟠 High |
| 7 | SEO & Schema | 4–5h | 🟡 Medium |
| 8 | JS, animations, interactions | 4–5h | 🟡 Medium |
| 9 | Accessibility & performance | 4–5h | 🟡 Medium |
| 10 | Final QA & production | 3–4h | 🟢 Final |
| **Total** | | **42–53h** | |

---

## Verification Plan

### After Each Phase
- PHP: `php -l` on all modified files (no syntax errors)
- WordPress: activate theme, check for admin notices
- Manual: open each affected page, verify visual output and content accuracy

### Final Verification
- Google Rich Results Test (schema)
- WAVE Accessibility Checker
- Google PageSpeed Insights
- Cross-browser: Chrome, Firefox, Edge, Safari (mobile)
- Responsive: 375px, 768px, 1024px, 1440px
- Contact form end-to-end email test

---

> [!IMPORTANT]
> **STOP. Awaiting your approval.**
> Please review the conflicts table and open questions above before I begin Phase 1.
> Specifically confirm:
> 1. **Product categories** — use the 5 from VERIFIED_COMPANY_FACTS.md? Or keep the 6 from DESIGN_SPEC?
> 2. **Physical address** — what is the Delhi address to use in contact cards and schema?
> 3. **WhatsApp** — confirm +91 83988 39899 is the correct WhatsApp number.
> 4. Anything else you want to change before implementation begins.
