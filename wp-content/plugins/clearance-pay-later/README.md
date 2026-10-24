# Clearance Pay Later

A WooCommerce plugin for clearance.co.ke that lets customers pay a 50% deposit today and the rest in 12 monthly instalments.

---

## Installation

1. Upload the `clearance-pay-later` folder to `/wp-content/plugins/`.
2. Activate via **Plugins > Installed Plugins**.
3. The database table (`wp_cpl_phones`) is created automatically on activation.
4. Go to **WooCommerce > Settings > Pay Later** to configure the plugin.

---

## Settings (WooCommerce > Settings > Pay Later)

| Setting | Default | Description |
|---|---|---|
| Deposit percentage | 50 | % of cart total charged at checkout |
| Interest rate | 12% | Flat interest on the balance |
| Number of instalments | 12 | Monthly payments |
| Eligibility check delay | 8 s | Duration of the "Checking…" animation |
| Show popup | Once per browser session | Once per visit (cleared on browser close) or every page |
| Gate popup title | *Configurable* | Heading text of the eligibility gate |
| Gate popup subtitle | *Configurable* | Subtitle text |
| Eligibility message | *Configurable* | Message shown after successful check |
| Delivery notice | *Configurable* | Shown at checkout |
| Terms URL | – | Link in the popup notice |
| Privacy URL | – | Link in the popup notice |

---

## How WooCommerce default order emails work with this plugin

This plugin does **not** change WooCommerce's default transactional emails.

To **disable** a default email (e.g. "New Order" for admin):

1. Go to **WooCommerce > Settings > Emails**.
2. Click the email you want to disable.
3. Toggle the **Enable/Disable** setting to off.

---

## Running Unit Tests

Requirements: PHP 7.4+, Composer, PHPUnit 9+.

```bash
cd wp-content/plugins/clearance-pay-later
composer require --dev phpunit/phpunit
vendor/bin/phpunit tests/test-calculator.php
```

---

## Mobile-First Test Checklist

Test on **Android Chrome** (360 × 800) and **iPhone Safari** (390 × 844).

### Site Gate Popup

- [ ] Popup appears as a bottom sheet on first visit
- [ ] No close button, ESC, or tap-outside dismisses it
- [ ] Numeric keyboard opens automatically for the phone field
- [ ] Valid phone (e.g. `0712345678`) passes, invalid shows inline error
- [ ] "Checking…" animation runs for the configured delay (default 8 s)
- [ ] Progress bar fills smoothly over the delay period
- [ ] "You're eligible!" result screen appears
- [ ] "Shop Now" closes popup and navigates to home page
- [ ] Popup does **not** reappear on subsequent pages in the same session
- [ ] Popup reappears after closing and reopening the browser
- [ ] `<noscript>` fallback: site content accessible without JavaScript
- [ ] Popup is **not** shown when logged in as admin
- [ ] Popup is **not** shown on `/wc-api/`, `/ipn/`, or payment callback URLs

### Add-to-Cart Modal

- [ ] Modal appears after tapping "Add to Cart" (shop and product pages)
- [ ] Radio options are large (min 48 px tap target)
- [ ] Full-price option shows correct KES amount
- [ ] Deposit option shows 50% of product price + monthly instalment
- [ ] "Continue" button saves choice and closes modal
- [ ] Plan choice persists when navigating to cart

### Cart Page

- [ ] Breakdown card shows: total, deposit, balance, interest, monthly × 12, total repayable
- [ ] "Change number" link re-opens the gate popup
- [ ] Negative fee line in cart reduces total to deposit amount

### Checkout

- [ ] Order total = deposit only (half of products + shipping + tax)
- [ ] Breakdown card visible above order total
- [ ] Delivery notice visible
- [ ] Required checkbox visible; submission blocked if unchecked
- [ ] After payment, order status = "Awaiting Agreement Signing"

### Admin

- [ ] **Orders list**: "Plan" column shows "50% + 12 inst." or "Full"
- [ ] **Order edit**: meta box shows all plan details and schedule
- [ ] Agreement status dropdown saves correctly
- [ ] "Mark Paid" button updates instalment row
- [ ] "Send Email" sends and logs to order notes
- [ ] **WooCommerce > Phone Log** page lists captured phones
- [ ] CSV export downloads all rows

### My Account

- [ ] Order view shows plan breakdown and instalment schedule

### Accessibility

- [ ] Focus stays inside popup while it is open
- [ ] Screen reader labels present on all form controls
- [ ] `aria-live` regions announce dynamic content (error, checking, result)
- [ ] `prefers-reduced-motion`: spinner and progress bar animations suppressed
- [ ] Focus returns to page content after popup closes

### Performance

- [ ] No Google PageSpeed regression (check Lighthouse mobile score)
- [ ] Plugin CSS/JS only loaded on frontend (excluded from WP-admin pages where not needed)
- [ ] Gate JS is lightweight — no jQuery or heavy library dependency

---

## Security Notes

- All AJAX handlers verify `wp_nonce` (`check_ajax_referer`).
- All user input is sanitised before use and escaped on output.
- Bot detection blocks gate from Googlebot, Bingbot, and other known crawlers.
- Payment callback URLs (`wc-api`, `ipn`, `notify`, `callback`, `webhook`) are excluded from the gate.
- The negative fee applied at checkout is non-taxable so M-Pesa/PesaPal only charges the deposit.
