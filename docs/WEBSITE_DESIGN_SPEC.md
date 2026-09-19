# 🖥️ IT Hardware Supply — Complete Website Theme Design

> **Company:** IT Hardware Supply Company (Delhi-based, since 2018)
> **Purpose:** Static product catalog + CTA-driven enquiry website
> **Platform:** WordPress (custom theme built in VS Code)

---

## Page Mockups

### 1. Homepage
![Homepage Design](C:\Users\HP\.gemini\antigravity\brain\964a389b-21bf-4fcf-a8a5-d0bf26d116af\homepage_full_design_1783660392007.jpg)

### 2. Product Catalog (Core Page)
![Product Catalog Page](C:\Users\HP\.gemini\antigravity\brain\964a389b-21bf-4fcf-a8a5-d0bf26d116af\product_catalog_page_1783660403524.jpg)

### 3. About Us
![About Us Page](C:\Users\HP\.gemini\antigravity\brain\964a389b-21bf-4fcf-a8a5-d0bf26d116af\about_us_page_design_1783660436459.jpg)

### 4. Contact Us
![Contact Us Page](C:\Users\HP\.gemini\antigravity\brain\964a389b-21bf-4fcf-a8a5-d0bf26d116af\contact_page_design_1783660413745.jpg)

---

## 📋 Design System

### Color Palette

| Token | Hex | Usage |
|-------|-----|-------|
| `--primary` | `#1B4DDB` | Buttons, links, headings, active states |
| `--primary-dark` | `#0D2665` | Top bar, footer, stats bar, dark sections |
| `--primary-light` | `#E8F0FE` | Hover backgrounds, light tints |
| `--accent` | `#F58220` | CTA buttons ("Enquire Now"), highlights |
| `--accent-hover` | `#E06D10` | CTA hover state |
| `--bg-white` | `#FFFFFF` | Primary page background |
| `--bg-light` | `#F5F7FA` | Alternating section backgrounds |
| `--text-heading` | `#0D2665` | All headings (H1-H6) |
| `--text-body` | `#4A4A68` | Body paragraph text |
| `--text-light` | `#FFFFFF` | Text on dark backgrounds |
| `--border` | `#E0E4EC` | Card borders, dividers |
| `--whatsapp` | `#25D366` | WhatsApp floating button |

### Typography

| Element | Font | Weight | Size |
|---------|------|--------|------|
| H1 (Hero) | Inter | 800 | 48px |
| H2 (Section titles) | Inter | 700 | 36px |
| H3 (Card titles) | Inter | 600 | 22px |
| H4 (Subtitles) | Inter | 600 | 18px |
| Body text | Inter | 400 | 16px |
| Small / Captions | Inter | 400 | 14px |
| Nav links | Inter | 500 | 15px |
| CTA buttons | Inter | 600 | 16px |

### Spacing & Layout

| Property | Value |
|----------|-------|
| Container max-width | 1200px |
| Section padding | 80px vertical |
| Card border-radius | 12px |
| Card shadow | `0 4px 20px rgba(0,0,0,0.08)` |
| Button border-radius | 8px |
| Button padding | 14px 32px |
| Grid gap | 24px |

---

## 🗺️ Sitemap & Page Structure

```
Home
├── Hero Section (headline + CTA)
├── Product Categories Grid (4 cards)
├── Stats Bar (experience, products, clients)
├── CTA Banner ("Call us")
│
Products
├── Page Header + Breadcrumb
├── Sidebar Category Filter
├── Product Grid (3-col, cards with Enquire Now)
│   ├── Servers & Workstations
│   ├── Networking Equipment
│   ├── Storage Solutions
│   ├── Desktops & Laptops
│   ├── Printers & Scanners
│   └── Cables & Accessories
│
About Us
├── Page Header + Breadcrumb
├── Who We Are (image + text)
├── Mission / Vision / Values
├── Why Choose Us (4 feature cards)
├── CTA Banner
│
Contact Us
├── Page Header + Breadcrumb
├── Contact Form + Info Cards
├── Google Map Embed
```

---

## 🧩 Component Specifications

### Global Components (Present on Every Page)

#### Top Info Bar
- **BG:** `--primary-dark` (#0D2665)
- **Content:** 📞 Phone | 📧 Email | 📍 Delhi, India
- **Text:** White, 13px, Inter 400
- **Height:** ~40px

#### Main Navbar
- **BG:** White with subtle bottom shadow
- **Logo:** Left-aligned
- **Nav Links:** Home | Products | About Us | Contact — dark blue text
- **CTA:** Orange "Enquire Now" button, right-aligned
- **Behavior:** Sticky on scroll
- **Mobile:** Hamburger menu

#### Footer
- **BG:** `--primary-dark` (#0D2665)
- **3 Columns:**
  - Col 1: Company name, brief tagline, social icons
  - Col 2: Quick Links (Home, Products, About, Contact)
  - Col 3: Contact Info (address, phone, email)
- **Bottom bar:** Copyright © 2018–2026

#### Floating WhatsApp Button
- **Position:** Fixed, bottom-right, 20px from edges
- **Size:** 56px circle
- **Color:** `--whatsapp` (#25D366) with white icon
- **Animation:** Gentle pulse/ripple effect
- **Links to:** `https://wa.me/91XXXXXXXXXX`

---

### Homepage-Specific Components

#### Hero Section
- **BG:** Linear gradient from `#1B4DDB` → `#0D2665`
- **Headline:** "Your Trusted IT Hardware Supply Partner" — White, H1
- **Subtext:** "Serving businesses across India since 2018" — White/80% opacity
- **CTA 1:** "Browse Products" — Orange filled button
- **CTA 2:** "Contact Us" — White outline button
- **Right side:** Hardware imagery (servers, networking gear)

#### Product Categories Grid
- **BG:** `--bg-light` (#F5F7FA)
- **Title:** "Our Product Range" — H2, centered
- **4 Cards:** White bg, rounded, shadow, icon on top
  - Servers & Workstations
  - Networking Equipment
  - Storage Solutions
  - Computing & Peripherals
- **Each card:** Icon → Title → 1-line description → "View Products →" link

#### Stats Counter Bar
- **BG:** `--primary-dark` (#0D2665)
- **4 Stats in a row:**
  - 7+ Years Experience
  - 500+ Products
  - 1000+ Happy Clients
  - Pan India Supply
- **Text:** White, numbers large (36px bold), labels smaller

#### CTA Banner
- **BG:** Blue gradient
- **Text:** "Looking for IT Hardware? We're Just a Call Away!"
- **Button:** Large orange "Get in Touch"

---

### Product Catalog Page Components

#### Product Card
- **BG:** White
- **Border-radius:** 12px
- **Shadow:** Subtle, increases on hover
- **Structure:**
  - Product image (top, covers full card width, 200px height)
  - Product name — H3, dark blue, bold
  - Description — 2-3 lines, gray body text
  - ❌ **NO price, NO vendor/brand name**
  - Orange "Enquire Now" button (full-width at bottom of card)
- **Hover:** Card lifts up 4px with deeper shadow
- **Grid:** 3 columns on desktop, 2 on tablet, 1 on mobile

#### Category Sidebar
- **Width:** 260px fixed
- **BG:** White with border-right
- **Items:** Category names as list, active item has blue left border + blue text
- **Mobile:** Collapses into a dropdown

---

### Contact Page Components

#### Enquiry Form
- **Fields:** Name, Email, Phone, Subject (dropdown), Message (textarea)
- **Button:** Orange "Send Enquiry" — full width
- **Validation:** Basic required field validation
- **WordPress:** Use Contact Form 7 or WPForms plugin

#### Contact Info Cards
- **Style:** White cards with left blue icon accent
- **Cards:** Address, Phone, Email, Working Hours

---

## 📱 Responsive Breakpoints

| Breakpoint | Width | Behavior |
|-----------|-------|----------|
| Desktop | ≥1200px | Full layout, 3-4 column grids |
| Tablet | 768–1199px | 2-column grids, sidebar collapses |
| Mobile | ≤767px | 1-column, hamburger menu, stacked layout |

---

## 🔌 WordPress Plugin Recommendations

| Plugin | Purpose |
|--------|---------|
| Contact Form 7 / WPForms | Enquiry form |
| Join.chat (WhatsApp) | Floating WhatsApp button |
| Yoast SEO | SEO optimization |
| WP Super Cache | Performance |
| Elementor (optional) | Page building if needed |

---

## ✅ Review Checklist

> [!IMPORTANT]
> Please review the following and let me know your feedback:

- [ ] **Color palette** — Are you happy with Blue + White + Orange? Any adjustments?
- [ ] **Homepage layout** — Does the hero + categories + stats + CTA structure work?
- [ ] **Product catalog** — Is the sidebar + 3-column card grid the right approach for showcasing hardware?
- [ ] **"Enquire Now" as primary CTA** — Should this open a contact form, WhatsApp, or a popup?
- [ ] **Company name** — What's the actual company name for the logo and branding?
- [ ] **Product categories** — Are these 6 categories correct, or do you need to add/remove any?
  - Servers & Workstations
  - Networking Equipment
  - Storage Solutions
  - Desktops & Laptops
  - Printers & Scanners
  - Cables & Accessories

> [!TIP]
> Once you approve this design (with any changes), I'll generate the **complete WordPress-ready HTML/CSS/JS theme code** that you can import directly into WordPress.
