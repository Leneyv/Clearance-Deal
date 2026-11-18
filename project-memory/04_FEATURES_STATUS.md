# 04. FEATURES STATUS

This document provides a live, verified status matrix of every major feature and subsystem on **Clearance Kenya** (`clearance.co.ke`).

---

## Feature Matrix

| Feature / Subsystem | Description | Status | Linked CMD / CHG | Last Tested | Notes |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Sticky BNPL Widget** | Vertical sticky tab on right edge; expands to allow phone eligibility check for 50% down / 12-instalment plan. | **Done and tested** | [CMD-001](./02_COMMAND_LOG.md#cmd-001) / [CHG-001](./03_CHANGELOG.md#chg-001) | 2026-10-30 | Excluded on `/jobs/` page. Crisp SVG `X` close icon. |
| **BNPL Exclude on /jobs/** | Strict suppression of `#bnplFloatingWidget` on the careers / jobs page. | **Done and tested** | [CMD-007](./02_COMMAND_LOG.md#cmd-007) / [CHG-007](./03_CHANGELOG.md#chg-007) | 2026-10-30 | Verified via DOM inspection and HTTP GET curl. |
| **Brand Identity System** | Corporate palette applied: Deep Aubergine (`#3A0F34`), Marigold (`#EAAA00`), Ice Silver (`#DDE3E8`). | **Done and tested** | [CMD-006](./02_COMMAND_LOG.md#cmd-006) / [CHG-006](./03_CHANGELOG.md#chg-006) | 2026-10-27 | Applied across headers, buttons, cards, footers. |
| **"Price in Kenya" SEO** | Injection of high-intent search phrase across `<title>`, `<h1>`, meta descriptions, schemas, and on-page copy. | **Done and tested** | [CMD-010](./02_COMMAND_LOG.md#cmd-010) / [CHG-010](./03_CHANGELOG.md#chg-010) | 2026-11-04 | Implemented in `inc/seo-aeo.php`. |
| **AEO & LLM Crawlers** | Dedicated `llms.txt` and `robots.txt` optimizations for Perplexity, ChatGPT, Claude, and Gemini bots. | **Done and tested** | [CMD-009](./02_COMMAND_LOG.md#cmd-009) / [CHG-009](./03_CHANGELOG.md#chg-009) | 2026-11-14 | Verified reachable at `https://clearance.co.ke/llms.txt`. |
| **100 Kenyan SEO Blogs** | High-authority topical cluster posts targeting Kenyan hair buyer queries across 6 categories. | **Done and tested** | [CMD-011](./02_COMMAND_LOG.md#cmd-011) / [CHG-011](./03_CHANGELOG.md#chg-011) | 2026-11-04 | Generated via `generate_100_blogs.py`. All 100 posts live. |
| **Evergreen Blog Dates** | Elimination of publish dates from `single.php`, `page-blog.php`, and `archive.php`; replaced with "Verified Guide" badge. | **Done and tested** | [CMD-012](./02_COMMAND_LOG.md#cmd-012) / [CHG-012](./03_CHANGELOG.md#chg-012) | 2026-11-04 | No visible dates in DOM. |
| **10% Promo Code** | WooCommerce coupon `CLEARANCE10` (10% off, 100 redemptions, no expiry). | **Done and tested** | [CMD-013](./02_COMMAND_LOG.md#cmd-013) / [CHG-013](./03_CHANGELOG.md#chg-013) | 2026-11-18 | Post ID: 523; verified active in WooCommerce database. |
| **Mandatory Account Signup** | Guest checkout disabled; customer accounts required or auto-provisioned at checkout. | **Done and tested** | [CMD-014](./02_COMMAND_LOG.md#cmd-014) / [CHG-014](./03_CHANGELOG.md#chg-014) | 2026-11-18 | `woocommerce_enable_guest_checkout = 'no'`. |
| **Cash on Delivery (COD) Block** | Permanent disabling and filter suppression of Cash on Delivery payment gateway. | **Done and tested** | [CMD-014](./02_COMMAND_LOG.md#cmd-014) / [CHG-014](./03_CHANGELOG.md#chg-014) | 2026-11-18 | Filtered via `functions.php`; only PesaPal is available. |
| **Paid Doorstep Delivery Messaging** | Eradication of "Free Delivery" badges; replaced with "Paid Doorstep Delivery Across Kenya". | **Done and tested** | [CMD-014](./02_COMMAND_LOG.md#cmd-014) / [CHG-014](./03_CHANGELOG.md#chg-014) | 2026-11-14 | Verified in announcement header and product pages. |
| **PesaPal v3 Payment Gateway** | Mobile Money (M-Pesa STK, Airtel Money) and Card processing in KSh. | **Done and tested** | Legacy Setup | 2026-11-18 | Active in WooCommerce; configured with IPN listener. |
| **Proprietary Lipa Polepole Plugin** | Backend plugin `clearance-pay-later` calculating 50% deposit and instalment breakdowns in cart/checkout. | **Done and tested** | Legacy Setup | 2026-10-27 | Active plugin: `clearance-pay-later`. |
| **Project Memory System** | Standardized 12-file operational log and handover bridge inside `/project-memory/`. | **Done and tested** | [CMD-015](./02_COMMAND_LOG.md#cmd-015) / [CHG-015](./03_CHANGELOG.md#chg-015) | 2026-11-18 | Full backfill completed. |
| **VPS Memory Sync & 403 Security** | Bidirectional git tracking to GitHub and VPS, with explicit Nginx deny rule (403 Forbidden). | **Done and tested** | [CMD-016](./02_COMMAND_LOG.md#cmd-016) / [CHG-016](./03_CHANGELOG.md#chg-016) | 2026-11-18 | Tested via curl (HTTP 403) and git push/pull sync. |
| **Automated SMS Reminders** | SMS alerts for upcoming monthly instalment payments via Africa's Talking or Advanta SMS. | **Planned** | Backlog | N/A | Slated for Phase 2. |
| **Automated Courier Tracking (Speedaf / Fargo)** | Real-time webhook integration with local Kenyan parcel couriers. | **Planned** | Backlog | N/A | Slated for Phase 2. |

---

## Status Legend
- **Done and tested:** Fully implemented, verified via CLI / browser, and live in production.
- **Done but not tested:** Code exists but lacks formal automated or manual verification.
- **In progress:** Currently being authored or refactored.
- **Planned:** In product backlog for future sprints.
- **Broken:** Known defect causing degradation or failure.
- **Removed:** Previously active feature that was intentionally stripped out.
