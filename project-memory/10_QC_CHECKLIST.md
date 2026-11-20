# 10. QUALITY CONTROL (QC) TEST CHECKLIST

This checklist provides repeatable, step-by-step test routines that any human engineer or supervising AI agent can execute to rigorously verify the operational integrity of **Clearance Kenya** (`clearance.co.ke`).

---

## Test Suites Summary

| Test ID | Test Category | Target Feature / Module | Status | Last Verified |
| :--- | :--- | :--- | :--- | :--- |
| **QC-01** | Checkout Security | Guest Checkout Blocked | **Pass** | 2026-11-18 |
| **QC-02** | Checkout Logic | Mandatory Account Signup at Checkout | **Pass** | 2026-11-18 |
| **QC-03** | Payments | Cash on Delivery (COD) Stripped | **Pass** | 2026-11-18 |
| **QC-04** | Payments | PesaPal Gateway Availability | **Pass** | 2026-11-18 |
| **QC-05** | Discount Engine | 10% Promo Code (`CLEARANCE10`) | **Pass** | 2026-11-18 |
| **QC-06** | UI & FinTech | Sticky BNPL Floating Widget Render | **Pass** | 2026-10-30 |
| **QC-07** | Routing & Layout | BNPL Suppression on `/jobs/` | **Pass** | 2026-10-30 |
| **QC-08** | Branding | Corporate Palette (Aubergine/Marigold/Ice Silver) | **Pass** | 2026-10-27 |
| **QC-09** | SEO & Schema | "Price in Kenya" JSON-LD Schema | **Pass** | 2026-11-04 |
| **QC-10** | Content Hub | 100 Blog Posts Accessibility & No Dates | **Pass** | 2026-11-04 |
| **QC-11** | Delivery Policy | "Paid Doorstep Delivery" Text Consistency | **Pass** | 2026-11-14 |
| **QC-12** | Mobile Usability | Viewport Responsiveness & Tap Targets | **Pass** | 2026-11-14 |
| **QC-13** | Security / Privacy | Public Web Blocking of `/project-memory/` (403) | **Pass** | 2026-11-18 |
| **QC-14** | Sync & Resilience | Git Dual Tracking (Local, VPS & GitHub) | **Pass** | 2026-11-18 |
| **QC-15** | Content Moderation | Customer Review Manual Approval Required | **Pass** | 2026-11-19 |
| **QC-16** | Content & Interlinking | 41 Product Service Blogs & Reciprocal Linking | **Pass** | 2026-11-20 |

---

## Detailed Test Procedures

### QC-01: Guest Checkout Blocked
- **What it should do:** An unauthenticated visitor must NOT be able to place an order without creating or logging into an account.
- **Test Steps:**
  1. SSH into server or run WP-CLI command:
     ```bash
     wp option get woocommerce_enable_guest_checkout --allow-root
     ```
  2. Open an incognito browser window, add any product to cart, and navigate to `https://clearance.co.ke/checkout/`.
  3. Inspect checkout form fields.
- **Expected Result:** Option returns `no`. Checkout displays account login and mandatory password/account creation fields.
- **Status:** **Pass**
- **Date Tested:** 2026-11-18

---

### QC-02: Mandatory Account Signup at Checkout
- **What it should do:** New shoppers can effortlessly register an account directly during checkout without being deflected to a disparate registration page.
- **Test Steps:**
  1. Run WP-CLI command:
     ```bash
     wp option get woocommerce_enable_signup_and_login_from_checkout --allow-root
     ```
- **Expected Result:** Option returns `yes`.
- **Status:** **Pass**
- **Date Tested:** 2026-11-18

---

### QC-03: Cash on Delivery (COD) Stripped
- **What it should do:** COD must not appear in the checkout payment method list under any circumstance.
- **Test Steps:**
  1. Run WP-CLI gateway inspection:
     ```bash
     wp eval 'print_r(array_keys(WC()->payment_gateways->get_available_payment_gateways()));' --allow-root
     ```
  2. Inspect output array.
- **Expected Result:** `cod` is NOT present in the available payment gateways array. Only `pesapal` is returned.
- **Status:** **Pass**
- **Date Tested:** 2026-11-18

---

### QC-04: PesaPal Gateway Availability
- **What it should do:** PesaPal must be enabled and set to live mode for processing M-Pesa and Cards in Kenya.
- **Test Steps:**
  1. Run WP-CLI eval command:
     ```bash
     wp eval '$g = WC()->payment_gateways->payment_gateways()["pesapal"]; echo "Enabled: ".$g->enabled." | Mode: ".$g->apimode;' --allow-root
     ```
- **Expected Result:** Output displays `Enabled: yes | Mode: live`.
- **Status:** **Pass**
- **Date Tested:** 2026-11-18

---

### QC-05: 10% Promo Code (`CLEARANCE10`)
- **What it should do:** Applying code `CLEARANCE10` applies a 10% deduction, enforces 100 usage cap, and has no expiry date.
- **Test Steps:**
  1. Inspect coupon metadata:
     ```bash
     wp post meta list 523 --allow-root
     ```
- **Expected Result:** `discount_type: percent`, `coupon_amount: 10`, `usage_limit: 100`, `date_expires: ""` (empty/null).
- **Status:** **Pass**
- **Date Tested:** 2026-11-18

---

### QC-06: Sticky BNPL Floating Widget Render
- **What it should do:** Vertical tab `#bnplFloatingWidget` renders on the right margin and opens an interactive simulator upon clicking.
- **Test Steps:**
  1. Request homepage via curl:
     ```bash
     curl -s https://clearance.co.ke/ | grep -o 'id="bnplFloatingWidget"'
     ```
- **Expected Result:** Returns match `id="bnplFloatingWidget"`.
- **Status:** **Pass**
- **Date Tested:** 2026-10-30

---

### QC-07: BNPL Suppression on `/jobs/`
- **What it should do:** The BNPL widget must NOT load on `https://clearance.co.ke/jobs/`.
- **Test Steps:**
  1. Request jobs page via curl:
     ```bash
     curl -s https://clearance.co.ke/jobs/ | grep -o 'id="bnplFloatingWidget"' || echo "Widget Absent"
     ```
- **Expected Result:** Returns `Widget Absent`.
- **Status:** **Pass**
- **Date Tested:** 2026-10-30

---

### QC-08: Corporate Brand Palette Applied
- **What it should do:** Stylesheet contains official brand tokens: `#3A0F34`, `#EAAA00`, `#DDE3E8`.
- **Test Steps:**
  1. Search `style.css` on remote server:
     ```bash
     grep -E "3A0F34|EAAA00|DDE3E8" /var/www/clearance/wp-content/themes/clearance-deal/style.css
     ```
- **Expected Result:** Tokens found and applied to `--cd-brand-aubergine`, `--cd-brand-marigold`, and `--cd-brand-ice-silver`.
- **Status:** **Pass**
- **Date Tested:** 2026-10-27

---

### QC-09: "Price in Kenya" SEO & Schema
- **What it should do:** Injects "Price in Kenya" into titles, meta tags, and JSON-LD schema on catalog and blog pages.
- **Test Steps:**
  1. Inspect homepage HTML:
     ```bash
     curl -s https://clearance.co.ke/ | grep -i "Price in Kenya"
     ```
- **Expected Result:** Matches found in `<title>`, headings, and JSON-LD structured data.
- **Status:** **Pass**
- **Date Tested:** 2026-11-04

---

### QC-10: 100 Blog Posts & No Visible Dates
- **What it should do:** Exactly 100 SEO posts published with evergreen trust badges and no visible date strings.
- **Test Steps:**
  1. Check post count via WP-CLI:
     ```bash
     wp post list --post_type=post --post_status=publish --format=count --allow-root
     ```
  2. Inspect a sample post DOM for `<time>` or date strings:
     ```bash
     curl -s https://clearance.co.ke/blog/ | grep -E "(<time|get_the_date)" || echo "No date elements found"
     ```
- **Expected Result:** Post count returns `100`. No visible publish dates detected in markup.
- **Status:** **Pass**
- **Date Tested:** 2026-11-04

---

### QC-11: "Paid Doorstep Delivery" Text Consistency
- **What it should do:** Announcement bars and product badges must say "Paid Doorstep Delivery" and NOT "Free Delivery".
- **Test Steps:**
  1. Grep theme templates for old "Free Delivery" text:
     ```bash
     grep -ri "Free Delivery" /var/www/clearance/wp-content/themes/clearance-deal/header.php
     ```
- **Expected Result:** No occurrences of "Free Delivery" in announcement bars; confirmed reads *"Paid Doorstep Delivery Across Kenya"*.
- **Status:** **Pass**
- **Date Tested:** 2026-11-14

---

### QC-12: Mobile Usability & Tap Targets
- **What it should do:** 2-column mobile product layout, touch-friendly buttons (>48px tap targets), responsive drawer navigation.
- **Test Steps:**
  1. Inspect viewport rules in `header.php`:
     ```bash
     grep 'viewport' /var/www/clearance/wp-content/themes/clearance-deal/header.php
     ```
  2. Verify touch event handlers in product carousels.
- **Expected Result:** Valid viewport meta tag present; swipe gestures enabled.
- **Status:** **Pass**
- **Date Tested:** 2026-11-14

---

### QC-13: Public Web Blocking of `/project-memory/`
- **What it should do:** Any external request to `/project-memory/` or files inside it must return HTTP 403 Forbidden.
- **Test Steps:**
  1. Execute HTTP HEAD request via curl:
     ```bash
     curl -I https://clearance.co.ke/project-memory/00_README_FOR_AI.md
     curl -I https://clearance.co.ke/project-memory/
     ```
  2. Inspect response status line.
- **Expected Result:** `HTTP/2 403` returned for all endpoints under `/project-memory/`.
- **Status:** **Pass**
- **Date Tested:** 2026-11-18

---

### QC-14: Git Dual Tracking & Synchronization
- **What it should do:** Local repository, VPS repository, and GitHub remote `origin/trust-fixes` must remain synchronized and contain the complete `/project-memory/` directory.
- **Test Steps:**
  1. Inspect local branch status: `cd /Users/lenee/clearance-deal && git status`
  2. Inspect VPS branch status: `ssh root@129.121.132.35 "cd /var/www/clearance && git status"`
- **Expected Result:** Both report `Your branch is up to date with 'origin/trust-fixes'`, working tree clean.
- **Status:** **Pass**
- **Date Tested:** 2026-11-18

---

### QC-15: Customer Review Manual Approval Required
- **What it should do:** Any product review or comment submitted on clearance.co.ke must remain unapproved (`status: hold` / `0`) and invisible on the live product page until an administrator manually approves it in WP Admin.
- **Test Steps:**
  1. Inspect options via WP-CLI:
     ```bash
     wp option get comment_moderation --allow-root
     wp option get comment_previously_approved --allow-root
     ```
  2. Verify programmatic filter in `functions.php`:
     ```bash
     grep -A 8 "pre_comment_approved" /var/www/clearance/wp-content/themes/clearance-deal/functions.php
     ```
- **Expected Result:** `comment_moderation` is `1`, `comment_previously_approved` is `0`, and `pre_comment_approved` forces status `0`.
- **Status:** **Pass**
- **Date Tested:** 2026-11-19

---

### QC-16: 41 Product Service Blogs & Reciprocal Linking
- **What it should do:** Every catalog product must have a dedicated how-to/service blog post with a single H1, AEO summary, HowTo/FAQ schema, product showcase card, and reciprocal links from the single product page and site footer.
- **Test Steps:**
  1. Count published posts via WP-CLI:
     ```bash
     wp post list --post_type=post --post_status=publish --format=count --allow-root
     ```
  2. Verify XML sitemap contains the care guides:
     ```bash
     curl -s https://clearance.co.ke/wp-sitemap-posts-post-1.xml | grep -o 'how-to-care-for' | wc -l
     ```
  3. Verify single product page displays reciprocal Care Guide banner:
     ```bash
     curl -s https://clearance.co.ke/product/dyson-airwrap-id-6-in-1-multi-styler/ | grep -o 'cd-product-care-link'
     ```
  4. Verify footer contains direct link to `/blog/`:
     ```bash
     curl -s https://clearance.co.ke/ | grep 'Hair &amp; Wigs Blog'
     ```
- **Expected Result:** Total post count = 141 (41 care guides), sitemap contains all 41 URLs, product page links to guide, and footer links to blog hub.
- **Status:** **Pass**
- **Date Tested:** 2026-11-20
