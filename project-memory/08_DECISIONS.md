# 08. ARCHITECTURAL & BUSINESS DECISIONS

This document logs significant technical, architectural, and business decisions made for **Clearance Kenya**, detailing the context, reasons, and rejected alternatives.

---

### DEC-001: Bespoke In-Theme BNPL Simulator vs External FinTech iframe
- **Date:** 2026-10-27
- **Decision:** Build a lightweight, native JavaScript and PHP floating eligibility simulator (`inc/bnpl-floating-widget.php`) directly into the custom theme rather than embedding third-party SaaS widgets (e.g. Lipa Later iframe or Aspira script).
- **Why:** 
  1. Instant page loading without external JS blocking (zero third-party latency).
  2. Complete control over styling, branding (Aubergine/Marigold), and responsive mobile drawer animations.
  3. Seamless integration with WooCommerce cart subtotals and custom product badges.
- **Alternatives Rejected:**
  - *Lipa Later SaaS Embed:* Rejected due to heavy script footprints (>400KB), external cookie tracking, and inability to customize copy and brand colors.
  - *Static Banner Only:* Rejected because interactive simulation increases shopper conversion on high-value human hair products.

---

### DEC-002: PesaPal v3 for Kenyan Mobile Money & Card Payments
- **Date:** 2026-09-13
- **Decision:** Standardize on PesaPal v3 as the sole active payment processor for online transactions in Kenya.
- **Why:**
  1. Native support for M-Pesa STK Push (the dominant payment method in Kenya, accounting for >85% of retail digital transactions).
  2. Handles Airtel Money and Visa/Mastercard processing under a unified merchant wallet and settlement account.
  3. Reliable IPN (Instant Payment Notification) listener supporting automatic order state transitions in WooCommerce.
- **Alternatives Rejected:**
  - *Direct Daraja M-Pesa API:* Rejected due to high development maintenance overhead (handling TLS certs, token rotations, and separate card gateways).
  - *Stripe / PayPal:* Rejected because Kenyan card acceptance is low compared to M-Pesa and PayPal accounts are rarely used by everyday shoppers in Nairobi.

---

### DEC-003: Disabling Guest Checkout
- **Date:** 2026-11-14
- **Decision:** Set `woocommerce_enable_guest_checkout = 'no'` and `woocommerce_enable_signup_and_login_from_checkout = 'yes'`.
- **Why:**
  1. Enforces account binding so customers can track their parcel delivery across Kenya.
  2. Required for managing recurring instalment agreements under the Lipa Polepole (BNPL) program.
  3. Eliminates phantom orders and enables targeted customer support via WhatsApp or email.
- **Alternatives Rejected:**
  - *Allowing Guest Checkout:* Rejected because unauthenticated orders make instalment tracking and courier dispute resolution impossible.

---

### DEC-004: Permanently Disabling Cash on Delivery (COD)
- **Date:** 2026-11-14
- **Decision:** Turn off COD in WooCommerce settings AND enforce an unregister filter (`unset($gateways['cod'])`) in `functions.php`.
- **Why:**
  1. COD in the Kenyan e-commerce landscape suffers from high refusal rates (often 30% to 45% of packages rejected upon arrival at the courier stage), resulting in wasted courier dispatch fees.
  2. Upfront payment via M-Pesa ensures 100% committed buyers and zero inventory lock-up.
- **Alternatives Rejected:**
  - *High Delivery Deposit with COD Balance:* Rejected to maintain a clean, frictionless single-step checkout experience via PesaPal.

---

### DEC-005: Eliminating Visible Publish Dates from Blog Articles
- **Date:** 2026-11-04
- **Decision:** Strip `get_the_date()` from `single.php`, `page-blog.php`, and `archive.php`, substituting an evergreen trust badge (*"Verified Guide"*).
- **Why:**
  1. Hair and wig buying advice in Kenya (e.g., HD lace care, Brazilian vs Peruvian hair comparison) is evergreen educational content.
  2. Visible dates cause content to appear dated to searchers and algorithmic rankers after 6-12 months, unnecessarily reducing click-through rates (CTR).
- **Alternatives Rejected:**
  - *Showing "Last Updated" Date:* Rejected because updating 100 posts periodically creates unnecessary operational maintenance without significant SEO upside over evergreen static authority.

---

### DEC-006: Bespoke SEO/AEO Module (`inc/seo-aeo.php`) vs Heavy Plugins (Yoast/RankMath)
- **Date:** 2026-11-04
- **Decision:** Implement a lightweight, custom PHP schema and meta tag injector in `inc/seo-aeo.php`.
- **Why:**
  1. Eliminates database bloat, tracking pings, and dashboard nag screens caused by commercial SEO plugins.
  2. Allows precise, programmatic control over Kenyan entity structures (`"Price in Kenya"`, Kenyan Shilling `KSh`, Nairobi store location).
  3. Guarantees 100% valid JSON-LD schemas matching Google Rich Results and modern LLM retrieval standards.
- **Alternatives Rejected:**
  - *Yoast SEO:* Rejected due to slow database queries on catalog pages and lack of native Kenyan AEO / LLM optimizations.
  - *RankMath:* Rejected for excessive background pings and aggressive upsells.
