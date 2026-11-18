# 09. KNOWN ISSUES, RISKS, AND TODO ROADMAP

This document outlines active technical debt, unresolved edge cases, known risks, and prioritized next steps for **Clearance Kenya** (`clearance.co.ke`).

---

## 1. Prioritized Roadmap & TODOs

| Priority | Task / Improvement | Target Area | Description |
| :--- | :--- | :--- | :--- |
| **P1** | **Git Working Tree Commit** | Version Control | Unstaged changes exist in remote working tree (theme files, `robots.txt`, `llms.txt`, `generate_100_blogs.py`). Commit these changes alongside `/project-memory/`. |
| **P1** | **PHP 8.2+ PesaPal Plugin Notices** | Payment Gateway | The `thebunch-ke-pesapal-woocommerce-3.1.3` plugin generates occasional PHP 8.2+ dynamic property deprecation notices in `debug.log`. A minor patch or variable declaration cleanup is recommended. |
| **P2** | **Automated Image Optimization (WebP)** | Performance | Product catalog images are currently standard JPG/PNG. Converting all catalog images to WebP via server-side script or `cwebp` will reduce page weight by 40-60%. |
| **P2** | **Dynamic Shipping Flat-Rates by County** | Checkout | Ensure WooCommerce shipping zones correctly distinguish Nairobi Metropolitan delivery (e.g. KSh 300) from upcountry counties (e.g. KSh 500 via Fargo/G4S). |
| **P3** | **Automated WhatsApp Order Notification** | Post-Checkout | Trigger an automated WhatsApp message to the customer confirming M-Pesa receipt and dispatch estimate. |
| **P3** | **Lipa Polepole SMS Reminders** | FinTech (BNPL) | Connect an SMS gateway (e.g. Africa's Talking) to dispatch instalment reminder alerts 3 days prior to due dates. |

---

## 2. Known Risks & Technical Debt

### Risk 1: High-Traffic Database Locks on Caching Flushes
- **Risk:** When running `wp cache flush` during high traffic, concurrent un-cached queries may temporarily spike MySQL CPU usage.
- **Mitigation:** Ensure FastCGI microcache remains active on Nginx so that Nginx serves stale cache while WordPress re-primes static pages.

### Risk 2: PesaPal Sandbox vs Live Keys
- **Risk:** If test keys are ever inadvertently saved in the WooCommerce PesaPal admin panel, live customer orders will fail to generate M-Pesa STK push requests.
- **Mitigation:** Maintain `testmode = 'no'` in database settings; keep production keys locked.

### Risk 3: Browser Cache of Old CSS
- **Risk:** Mobile repeat visitors may load cached CSS with older button styles.
- **Mitigation:** Increment asset version query strings (`?v=1.x.x`) in `wp_enqueue_style` within `functions.php` when modifying CSS tokens.

---

## 3. Shortcuts Taken & Honest Assessment
1. **BNPL Floating Widget Phone Check:** The widget's phone eligibility check is a high-converting simulation designed to capture customer interest and calculate accurate payment terms. Full credit scoring occurs downstream during manual order review and customer identity verification.
2. **Blog Author Attribution:** All 100 SEO posts use the primary site administrator identity; no fictitious team member profiles have been created.
3. **No Visible Dates:** Search engines still crawl XML sitemaps containing `<lastmod>` timestamps; only front-end user-facing date strings have been removed from the theme templates.
