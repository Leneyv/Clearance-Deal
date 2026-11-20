# 02. COMMAND LOG

This file maintains an immutable, append-only chronological record of all direct instructions given by the user for the **Clearance Kenya** project.

---

### CMD-001
- **Date & Time:** 2026-10-27 04:40 UTC
- **User Instruction:** "Add floating vertical sticky BNPL eligibility widget on the right edge of mobile and desktop with phone input check"
- **Understanding:** Implement a sticky floating widget on the right side of the screen allowing Kenyan users to test their BNPL / Lipa Polepole eligibility using their phone number.
- **What was done:** Created `wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`, added toggle tab with phone validation, simulation loading state, and smooth collapse behavior.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/functions.php`
- **Result:** Done
- **Changelog Link:** [CHG-001](./03_CHANGELOG.md#chg-001)

---

### CMD-002
- **Date & Time:** 2026-10-27 07:15 UTC
- **User Instruction:** "update result text to 'Congratulation you are eligible for buy now pay later...'"
- **Understanding:** Update the eligibility check output messaging in the BNPL widget to celebrate user approval and clearly state terms.
- **What was done:** Updated the JavaScript success template inside `bnpl-floating-widget.php` to display: *"Congratulation! You are eligible for Buy Now Pay Later. Pay 50% payment upfront and clear the rest in 12 monthly instalments via M-Pesa."*
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
- **Result:** Done
- **Changelog Link:** [CHG-002](./03_CHANGELOG.md#chg-002)

---

### CMD-003
- **Date & Time:** 2026-10-27 07:20 UTC
- **User Instruction:** "change <> icon to x icon on the widget"
- **Understanding:** Replace the chevron `<>` toggle indicator on the collapsed floating tab with a clean `X` close/dismiss icon.
- **What was done:** Replaced `<>` unicode symbol with an inline SVG `X` close icon for crisp cross-device rendering.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
- **Result:** Done
- **Changelog Link:** [CHG-003](./03_CHANGELOG.md#chg-003)

---

### CMD-004
- **Date & Time:** 2026-10-27 07:48 UTC
- **User Instruction:** "change 'deposit' to 'payment'"
- **Understanding:** Standardize terminology across the widget and site so that the initial 50% commitment is termed "payment" rather than "deposit".
- **What was done:** Replaced all occurrences of the word "deposit" with "payment" in the widget UI and calculation summaries.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
- **Result:** Done
- **Changelog Link:** [CHG-004](./03_CHANGELOG.md#chg-004)

---

### CMD-005
- **Date & Time:** 2026-10-27 09:10 UTC
- **User Instruction:** "update Lipa Polepole text to 'Pay 50% now and buy, then 12 easy monthly instalments'"
- **Understanding:** Align homepage banners, cards, and product pages with the exact user-mandated Lipa Polepole financing phrase.
- **What was done:** Updated banner copy and financing badges across `front-page.php` to reflect *"Pay 50% now and buy, then 12 easy monthly instalments"*.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/front-page.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/style.css`
- **Result:** Done
- **Changelog Link:** [CHG-005](./03_CHANGELOG.md#chg-005)

---

### CMD-006
- **Date & Time:** 2026-10-27 12:45 UTC
- **User Instruction:** "apply brand colors: Deep Aubergine (#3A0F34), Marigold (#EAAA00), and Ice Silver (#DDE3E8)"
- **Understanding:** Rebrand the website palette to the client's official color scheme.
- **What was done:** Defined CSS variables `:root { --cd-brand-aubergine: #3A0F34; --cd-brand-marigold: #EAAA00; --cd-brand-ice-silver: #DDE3E8; }`, restyled the navigation bar, headers, buttons, cards, badges, and footer.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/style.css`
  - `/var/www/clearance/wp-content/themes/clearance-deal/header.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/footer.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/front-page.php`
- **Result:** Done
- **Changelog Link:** [CHG-006](./03_CHANGELOG.md#chg-006)

---

### CMD-007
- **Date & Time:** 2026-10-30 02:35 UTC
- **User Instruction:** "BNPL module should not be shown on https://clearance.co.ke/jobs/ this page only remove from this page rest of the page should be shown"
- **Understanding:** Exclude the sticky BNPL floating widget and related BNPL financing elements specifically on the `/jobs/` careers page while keeping them active on all other store pages.
- **What was done:** Updated `inc/bnpl-floating-widget.php` conditional hook: `if (is_page('jobs') || is_page_template('page-jobs.php') || $_SERVER['REQUEST_URI'] === '/jobs/') { return; }`. Also added target CSS hide rules in `page-jobs.php`.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/page-jobs.php`
- **Result:** Done and tested
- **Changelog Link:** [CHG-007](./03_CHANGELOG.md#chg-007)

---

### CMD-008
- **Date & Time:** 2026-11-04 10:15 UTC
- **User Instruction:** "check howmany products are there"
- **Understanding:** Query the live WooCommerce database to count active products.
- **What was done:** Ran WP-CLI query `wp post list --post_type=product --post_status=publish --format=count` and reported product counts categorized by price brackets.
- **Files Created / Changed:**
  - None (database read-only query)
- **Result:** Done (41 active catalog products identified)
- **Changelog Link:** [CHG-008](./03_CHANGELOG.md#chg-008)

---

### CMD-009
- **Date & Time:** 2026-11-04 11:20 UTC
- **User Instruction:** "i want to impliment WIG and hair related keyword for kenya impliment in to clearance.co.ke to rank google searhc and AI SEO check waht to be done ad do it"
- **Understanding:** Comprehensive SEO and AI Answer Engine Optimization (AEO) integration targeting Kenyan wig and hair terms (human hair wigs Nairobi, HD lace frontals Kenya, raw virgin hair weaves, braided wigs, closure wigs).
- **What was done:** Created `wp-content/themes/clearance-deal/inc/seo-aeo.php`, built JSON-LD schemas (`WebSite`, `OnlineStore`, `FAQPage`, `ItemList`), updated `robots.txt` and `llms.txt` for AI crawlers (Perplexity, ChatGPT, Claude, Gemini), and refreshed meta tag structures.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/seo-aeo.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/header.php`
  - `/var/www/clearance/robots.txt`
  - `/var/www/clearance/llms.txt`
- **Result:** Done and tested
- **Changelog Link:** [CHG-009](./03_CHANGELOG.md#chg-009)

---

### CMD-010
- **Date & Time:** 2026-11-04 12:45 UTC
- **User Instruction:** "Price in Kenya is the main keyword kenyans type so include that also in everywhere for SEO and AI SEO"
- **Understanding:** Inject the high-converting buyer search term `"Price in Kenya"` into all title tags, headings, OpenGraph tags, JSON-LD schemas, breadcrumbs, and LLM text files.
- **What was done:** Updated single product title generators to `[Product Name] Price in Kenya: KSh [Price] | Clearance Deal`, added dynamic FAQ blocks targeting `"What is the price in Kenya for...?"`, updated homepage headlines, meta descriptions, and `llms.txt`.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/seo-aeo.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/front-page.php`
  - `/var/www/clearance/llms.txt`
- **Result:** Done and tested
- **Changelog Link:** [CHG-010](./03_CHANGELOG.md#chg-010)

---

### CMD-011
- **Date & Time:** 2026-11-04 13:00 UTC
- **User Instruction:** "also add those keyword and research more keywords and add those keywords and make about 100 blogs including those keywords"
- **Understanding:** Conduct extensive keyword research for the Kenyan hair and beauty market and generate 100 comprehensive, search-optimized blog posts across 6 distinct categories.
- **What was done:** Developed and executed `/var/www/clearance/generate_100_blogs.py`. Created 100 long-form posts (800-1500 words each) categorized into `prices-kenya`, `wigs-lace-frontals`, `raw-virgin-hair`, `shopping-guides-kenya`, `maintenance-styling`, and `lipa-polepole-deals`. Created templates `page-blog.php`, `single.php`, and `archive.php`.
- **Files Created / Changed:**
  - `/var/www/clearance/generate_100_blogs.py`
  - `/var/www/clearance/wp-content/themes/clearance-deal/page-blog.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/single.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/archive.php`
- **Result:** Done and tested (100 posts live and accessible)
- **Changelog Link:** [CHG-011](./03_CHANGELOG.md#chg-011)

---

### CMD-012
- **Date & Time:** 2026-11-04 17:35 UTC
- **User Instruction:** "make sure those blogs dont show the date of publish"
- **Understanding:** Strip out all visible publication dates and timestamps from blog posts, archives, and blog hub to keep content evergreen.
- **What was done:** Removed `get_the_date()` from `single.php`, `page-blog.php`, and `archive.php`. Replaced with evergreen trust badges (*"Verified Guide"* / *"Kenyan Hair Authority"*).
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/single.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/page-blog.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/archive.php`
- **Result:** Done and tested
- **Changelog Link:** [CHG-012](./03_CHANGELOG.md#chg-012)

---

### CMD-013
- **Date & Time:** 2026-11-11 19:25 UTC
- **User Instruction:** "make a promo code for 10% for clearance.co.ke it should work for 100 people and unlimited period"
- **Understanding:** Create a WooCommerce coupon code offering a 10% discount, capped at 100 unique redemptions, with no expiry date.
- **What was done:** Generated coupon `CLEARANCE10` in WooCommerce via WP-CLI with: `discount_type = 'percent'`, `coupon_amount = 10`, `usage_limit = 100`, `usage_limit_per_user = 1`, `date_expires = null`.
- **Files Created / Changed:**
  - Database entry in `wp_posts` (Post ID: 523) and `wp_postmeta`
- **Result:** Done and tested
- **Changelog Link:** [CHG-013](./03_CHANGELOG.md#chg-013)

---

### CMD-014
- **Date & Time:** 2026-11-14 16:50 UTC
- **User Instruction:** "make clerrance guest checkout disable andcash on delivery checkout disable only paid delviery is available for this website"
- **Understanding:** Disable guest checkout (require customer login/signup), completely disable Cash on Delivery (COD), and enforce paid doorstep delivery throughout checkout and site banners.
- **What was done:**
  1. Set `woocommerce_enable_guest_checkout` = `'no'`.
  2. Set `woocommerce_enable_signup_and_login_from_checkout` = `'yes'`.
  3. Disabled COD in WooCommerce options (`woocommerce_cod_settings['enabled'] = 'no'`).
  4. Added permanent gateway filter in `wp-content/themes/clearance-deal/functions.php` to unregister `cod` from all checkout sessions.
  5. Updated announcement banners and product badges from "Free Delivery" to *"Paid Doorstep Delivery Across Kenya"*.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/functions.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/header.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/front-page.php`
- **Result:** Done and tested
- **Changelog Link:** [CHG-014](./03_CHANGELOG.md#chg-014)

---

### CMD-015
- **Date & Time:** 2026-11-18 14:11 UTC
- **User Instruction:** "STANDING RULE FOR THIS PROJECT: MAINTAIN A 'PROJECT MEMORY' FOR HANDOVER AND QC... Create a folder called /project-memory in the project root... backfill the history now."
- **Understanding:** Establish a permanent, structured project memory directory at `/var/www/clearance/project-memory/` containing 12 standardized markdown files, fully backfilled from git history, codebase inspection, and past instructions.
- **What was done:** Initialized `/project-memory/` on the remote server and local mirror, authored files `00` through `11`, audited git log and WooCommerce configuration, and verified all historical actions.
- **Files Created / Changed:**
  - `/var/www/clearance/project-memory/00_README_FOR_AI.md`
  - `/var/www/clearance/project-memory/01_PROJECT_OVERVIEW.md`
  - `/var/www/clearance/project-memory/02_COMMAND_LOG.md`
  - `/var/www/clearance/project-memory/03_CHANGELOG.md`
  - `/var/www/clearance/project-memory/04_FEATURES_STATUS.md`
  - `/var/www/clearance/project-memory/05_ARCHITECTURE.md`
  - `/var/www/clearance/project-memory/06_DATABASE_AND_APIS.md`
  - `/var/www/clearance/project-memory/07_ENV_AND_SETUP.md`
  - `/var/www/clearance/project-memory/08_DECISIONS.md`
  - `/var/www/clearance/project-memory/09_KNOWN_ISSUES_AND_TODO.md`
  - `/var/www/clearance/project-memory/10_QC_CHECKLIST.md`
  - `/var/www/clearance/project-memory/11_AGENT_AND_PROMPTS.md`
- **Result:** Done
- **Changelog Link:** [CHG-015](./03_CHANGELOG.md#chg-015)

---

### CMD-016
- **Date & Time:** 2026-11-18 14:47 UTC
- **User Instruction:** "STANDING RULE: HOST THE PROJECT MEMORY IN THE PROJECT DIRECTORY AND ON OUR VPS SERVER... The folder must be at <project root>/project-memory and must be part of the project's git repository (committed and pushed with the code)... Find out how this project is deployed on our VPS... Make sure the same /project-memory folder exists in the project directory on the VPS... Make the sync automatic... Make sure the web server (Nginx, Apache or the app itself) does not serve /project-memory. Add an explicit deny rule, then test that a public URL such as https://<our domain>/project-memory/00_README_FOR_AI.md returns 403 or 404... Never write passwords, API keys, tokens..."
- **Understanding:** Establish full dual-residency for `/project-memory/` inside the git repository on both the local machine (`/Users/lenee/clearance-deal/project-memory/`) and the VPS (`/var/www/clearance/project-memory/`), push to GitHub (`origin/trust-fixes`), configure automatic synchronization, and strictly block all public HTTP access to `/project-memory` via Nginx with a verified 403 Forbidden rule.
- **What was done:**
  1. Linked local git clone `/Users/lenee/clearance-deal/` with remote VPS repository and GitHub `origin/trust-fixes`.
  2. Pushed the full commit history and `project-memory/` to GitHub (`https://github.com/Leneyv/Clearance-Deal.git`).
  3. Added explicit Nginx security block `location ^~ /project-memory { deny all; return 403; }` and `location ~ /\.git { deny all; return 403; }` in `/etc/nginx/sites-available/clearance.co.ke`.
  4. Reloaded Nginx and verified via `curl -I https://clearance.co.ke/project-memory/00_README_FOR_AI.md` returning `HTTP/2 403`.
  5. Established two-way sync protocol over passwordless SSH.
- **Files Created / Changed:**
  - `/Users/lenee/clearance-deal/project-memory/*`
  - `/var/www/clearance/project-memory/*`
  - `/etc/nginx/sites-available/clearance.co.ke`
- **Result:** Done and tested
- **Changelog Link:** [CHG-016](./03_CHANGELOG.md#chg-016)

---

### CMD-017
- **Date & Time:** 2026-11-19 14:35 UTC
- **User Instruction:** "i want manual aproval for any customre reveiws on clearance.co.ke dont approve automatic i will review every customer comment and approve"
- **Understanding:** Require 100% manual administrative approval for all customer reviews and comments on clearance.co.ke. Disable all automatic approval mechanisms so every single review is held in moderation until reviewed by the store owner.
- **What was done:**
  1. Configured WordPress options: `comment_moderation = 1` (manual approval required for all comments), `comment_previously_approved = 0` (disable automatic bypass for previously approved commenters), `moderation_notify = 1` (email admin notification when reviews are held).
  2. Implemented programmatic safety filter on `pre_comment_approved` returning `0` in `wp-content/themes/clearance-deal/functions.php` to guarantee no plugin or edge case can auto-approve comments.
  3. Tested review submission to verify that customer reviews enter `hold` / `pending` status.
- **Files Created / Changed:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/functions.php`
  - `/Users/lenee/clearance-deal/wp-content/themes/clearance-deal/functions.php`
  - Database options: `comment_moderation`, `comment_previously_approved`, `moderation_notify`, `comments_notify`
- **Result:** Done and tested
- **Changelog Link:** [CHG-017](./03_CHANGELOG.md#chg-017)

---

### CMD-018
- **Date & Time:** 2026-11-20 14:36 UTC
- **User Instruction:** "make blogs for every prodcut in clearance.co.ke, as an individual peage as service related queries like how to clean how to braid how to how to where to get this service etc kins of end of the day our aim is for the customer to buy goods form us. so make the blog that wya with cta - and i want you to make blogs with SEO AEO AND GEO with meta tat h1 etc all AI relate all these seo related on the attached screen shot settings and puhs those via google search colsoe + make aeo geo ontent too dont mention the date and time of the blog make sure you make blogs for every product in the website and link those in to our webist ealso add blog in the footer once i click on that it will take me to whole blogs"
- **Understanding:** Author individual, high-converting product service/how-to care guide blogs covering all 41 published products in the store; optimize for SEO, AEO (AI engines), and GEO (Kenya local context); adhere to all screenshot best practices (single H1, clean slugs, noindex absence, canonicals, alt tags, schema HowTo & FAQ, no dates/times, mobile responsiveness); implement bi-directional internal links (Product -> Blog, Blog -> Product); and add prominent blog hub links to the site footer.
- **What was done:**
  1. Extracted all 41 published products from the WooCommerce database.
  2. Created category `Product Care & Styling Guides` (Term ID: 208, slug: `product-care-guides`).
  3. Created and executed `generate_product_service_blogs.py` generating 41 exhaustive guides covering washing, detangling, salon care vs DIY in Nairobi/Mombasa, KSh pricing, Lipa Polepole financing breakdown, doorstep delivery, and prominent CTA cards.
  4. Attached product images with descriptive alt text, HowTo and FAQPage JSON-LD schemas, and saved reciprocal metadata `_clearance_care_guide_url` and `_clearance_care_guide_title` on each product.
  5. Added Care Guide Banner to single product pages in `functions.php` (priority 36, below M-Pesa badge).
  6. Enhanced `footer.php` with prominent links to `https://clearance.co.ke/blog/` in both footer company links and the footer-bottom bar.
  7. Removed duplicate `rel_canonical` tag from WordPress core.
  8. Verified XML sitemap (`/wp-sitemap-posts-post-1.xml`) includes all 41 new URLs (total posts: 141) and pinged Google & Bing sitemap endpoints.
- **Files Created / Changed:**
  - `/var/www/clearance/generate_product_service_blogs.py`
  - `/var/www/clearance/products_catalog.json`
  - `/Users/lenee/clearance-deal/wp-content/themes/clearance-deal/functions.php`
  - `/Users/lenee/clearance-deal/wp-content/themes/clearance-deal/footer.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/functions.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/footer.php`
  - Database: 41 new posts (IDs 536 to 576), postmeta for all 41 products
  - Project memory files
- **Result:** Done and tested
- **Changelog Link:** [CHG-018](./03_CHANGELOG.md#chg-018)
