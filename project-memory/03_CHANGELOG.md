# 03. CHANGELOG

This file maintains an immutable, append-only record of all codebase, configuration, and database modifications for **Clearance Kenya** (`clearance.co.ke`).

---

### CHG-001
- **Date & Time:** 2026-10-27 04:42 UTC
- **Linked Command:** [CMD-001](./02_COMMAND_LOG.md#cmd-001)
- **Type:** Added
- **What Changed & Why:** Added a persistent, vertical floating BNPL (Lipa Polepole) widget on the right-hand margin of all web pages. Allows prospective shoppers to enter their Kenyan phone number and simulate eligibility for the 50% down / 12-instalment plan.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/functions.php`
- **How Tested & Result:** Verified via mobile viewport simulation in Chrome DevTools on staging; confirmed widget renders on right edge and expands on tap.
- **Breaking Risk:** May overlap bottom navigation buttons if z-index is too low or layout viewport is very narrow (<320px).
- **How to Undo:** Remove `require_once get_template_directory() . '/inc/bnpl-floating-widget.php';` in `functions.php`.

---

### CHG-002
- **Date & Time:** 2026-10-27 07:18 UTC
- **Linked Command:** [CMD-002](./02_COMMAND_LOG.md#cmd-002)
- **Type:** Refactored
- **What Changed & Why:** Updated the eligibility success copy inside `bnpl-floating-widget.php` to celebrate instant approval and provide clear step-by-step instructions.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
- **How Tested & Result:** Tested phone submission `0712345678` in widget; verified green checkmark and updated text displayed properly.
- **Breaking Risk:** None. Text-only template change.
- **How to Undo:** Revert commit `47fd6bd3` via `git checkout 47fd6bd3^ -- wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`.

---

### CHG-003
- **Date & Time:** 2026-10-27 07:33 UTC
- **Linked Command:** [CMD-003](./02_COMMAND_LOG.md#cmd-003)
- **Type:** Fixed
- **What Changed & Why:** Replaced chevron `<>` characters with an inline SVG `X` close icon to prevent browser font rendering inconsistencies on older Android devices.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
- **How Tested & Result:** Tested across Safari on iOS and Chrome on Android; verified crisp vector rendering.
- **Breaking Risk:** None.
- **How to Undo:** Revert commit `2bada6d6`.

---

### CHG-004
- **Date & Time:** 2026-10-27 07:49 UTC
- **Linked Command:** [CMD-004](./02_COMMAND_LOG.md#cmd-004)
- **Type:** Refactored
- **What Changed & Why:** Replaced the term "deposit" with "payment" throughout the BNPL calculation modal and breakdown strings to avoid customer confusion with refundable security deposits.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
- **How Tested & Result:** Inspected expanded widget UI; confirmed "Initial payment: 50%" rendered.
- **Breaking Risk:** None.
- **How to Undo:** Revert commit `3ecc17b0`.

---

### CHG-005
- **Date & Time:** 2026-10-27 09:13 UTC
- **Linked Command:** [CMD-005](./02_COMMAND_LOG.md#cmd-005)
- **Type:** Refactored
- **What Changed & Why:** Updated homepage Lipa Polepole banners and cards to state *"Pay 50% now and buy, then 12 easy monthly instalments"*.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/front-page.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/style.css`
- **How Tested & Result:** Checked desktop and mobile layout of `https://clearance.co.ke`; verified typography and copy alignment.
- **Breaking Risk:** None.
- **How to Undo:** Revert commit `fec4d0ae`.

---

### CHG-006
- **Date & Time:** 2026-10-27 12:50 UTC
- **Linked Command:** [CMD-006](./02_COMMAND_LOG.md#cmd-006)
- **Type:** Upgraded
- **What Changed & Why:** Integrated brand design tokens: Deep Aubergine (`#3A0F34`), Marigold (`#EAAA00`), and Ice Silver (`#DDE3E8`) across buttons, header navigation, announcement bars, price cards, and footer background.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/style.css`
  - `/var/www/clearance/wp-content/themes/clearance-deal/header.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/footer.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/front-page.php`
- **How Tested & Result:** Captured screenshots on mobile and desktop viewports; verified high contrast and brand consistency.
- **Breaking Risk:** Custom CSS specificity issues with third-party WooCommerce elements.
- **How to Undo:** Revert changes in `style.css` to previous commit color definitions.

---

### CHG-007
- **Date & Time:** 2026-10-30 02:40 UTC
- **Linked Command:** [CMD-007](./02_COMMAND_LOG.md#cmd-007)
- **Type:** Fixed
- **What Changed & Why:** Specifically disabled the BNPL floating widget on the `/jobs/` page (`https://clearance.co.ke/jobs/`) to prevent distracting job applicants with financing modals.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/bnpl-floating-widget.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/page-jobs.php`
- **How Tested & Result:** Verified `curl -s https://clearance.co.ke/jobs/` lacks `#bnplFloatingWidget`; verified homepage and shop pages still include the widget.
- **Breaking Risk:** None. Page slug check is guarded.
- **How to Undo:** Remove the `is_page('jobs')` guard in `bnpl-floating-widget.php`.

---

### CHG-008
- **Date & Time:** 2026-11-04 10:20 UTC
- **Linked Command:** [CMD-008](./02_COMMAND_LOG.md#cmd-008)
- **Type:** Config
- **What Changed & Why:** Audited active catalog products using WP-CLI. Documented that 41 published products exist in the catalog across price brackets (3k-10k, 11k-15k, 15k-20k, and 20k+ premium).
- **Files Touched:**
  - None (database audit only)
- **How Tested & Result:** Executed `wp post list --post_type=product --post_status=publish --format=count --allow-root`. Output: `41`.
- **Breaking Risk:** None.
- **How to Undo:** N/A.

---

### CHG-009
- **Date & Time:** 2026-11-04 11:35 UTC
- **Linked Command:** [CMD-009](./02_COMMAND_LOG.md#cmd-009)
- **Type:** Added
- **What Changed & Why:** Built a custom SEO & AEO (AI Engine Optimization) subsystem in `inc/seo-aeo.php`. Injects rich Schema.org JSON-LD (`OnlineStore`, `WebSite`, `FAQPage`, `BreadcrumbList`), optimizes meta tags for Kenyan wig and hair terms, and created `llms.txt` and `robots.txt` directives for AI search crawlers.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/seo-aeo.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/header.php`
  - `/var/www/clearance/robots.txt`
  - `/var/www/clearance/llms.txt`
- **How Tested & Result:** Validated JSON-LD schemas with Google Rich Results test format; confirmed valid syntax and correct Kenyan entity representations.
- **Breaking Risk:** Double title tags if another SEO plugin (Yoast/RankMath) is accidentally activated.
- **How to Undo:** Unhook `cd_custom_seo_aeo_meta` in `functions.php` or delete `inc/seo-aeo.php`.

---

### CHG-010
- **Date & Time:** 2026-11-04 12:50 UTC
- **Linked Command:** [CMD-010](./02_COMMAND_LOG.md#cmd-010)
- **Type:** Refactored
- **What Changed & Why:** Integrated the primary Kenyan keyword `"Price in Kenya"` into all title tags, OpenGraph metadata, heading tags, dynamic FAQ blocks, and `llms.txt`.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/inc/seo-aeo.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/front-page.php`
  - `/var/www/clearance/llms.txt`
- **How Tested & Result:** Tested `curl -s https://clearance.co.ke/ | grep -i "Price in Kenya"`; verified presence in `<title>`, `<h1>`, and JSON-LD schemas.
- **Breaking Risk:** Over-optimization if search engines detect spamming. Managed by using natural language variations.
- **How to Undo:** Revert changes in `inc/seo-aeo.php` to previous title template.

---

### CHG-011
- **Date & Time:** 2026-11-04 14:15 UTC
- **Linked Command:** [CMD-011](./02_COMMAND_LOG.md#cmd-011)
- **Type:** Added
- **What Changed & Why:** Generated 100 comprehensive Kenyan hair and wig SEO blog articles categorized across 6 topical hubs using `generate_100_blogs.py`. Created custom theme templates `page-blog.php`, `single.php`, and `archive.php`.
- **Files Touched:**
  - `/var/www/clearance/generate_100_blogs.py`
  - `/var/www/clearance/wp-content/themes/clearance-deal/page-blog.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/single.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/archive.php`
- **How Tested & Result:** Executed `wp post list --post_type=post --post_status=publish --format=count --allow-root` (returned 100). Checked responsive rendering on `/blog/` and sample single posts.
- **Breaking Risk:** Database bloat if unmanaged; handled with clean HTML markup and no heavy external dependencies.
- **How to Undo:** Delete posts via WP-CLI: `wp post delete $(wp post list --post_type=post --format=ids --allow-root) --force --allow-root`.

---

### CHG-012
- **Date & Time:** 2026-11-04 17:40 UTC
- **Linked Command:** [CMD-012](./02_COMMAND_LOG.md#cmd-012)
- **Type:** Refactored
- **What Changed & Why:** Removed all visible publication dates (`get_the_date()`, `the_time()`, `published_on`) from single posts, archive pages, and blog index. Added an evergreen "Verified Guide" trust badge instead.
- **Files Touched:**
  - `/var/www/clearance/wp-content/themes/clearance-deal/single.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/page-blog.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/archive.php`
- **How Tested & Result:** Inspected HTML source of `/blog/` and `/braided-wigs-price-in-kenya/`; verified no `<time>` tags or human-readable dates appear on the page.
- **Breaking Risk:** None.
- **How to Undo:** Restore `<?php echo get_the_date(); ?>` inside template meta header blocks.

---

### CHG-013
- **Date & Time:** 2026-11-11 19:30 UTC
- **Linked Command:** [CMD-013](./02_COMMAND_LOG.md#cmd-013)
- **Type:** Added
- **What Changed & Why:** Created a 10% store discount coupon `CLEARANCE10` in WooCommerce for 100 uses with unlimited duration.
- **Files Touched:**
  - WordPress Database (`wp_posts` ID: 523, `wp_postmeta`)
- **How Tested & Result:** Queried via WP-CLI: `wp post meta list 523 --allow-root`. Verified `discount_type: percent`, `coupon_amount: 10`, `usage_limit: 100`, `date_expires: ""` (unlimited).
- **Breaking Risk:** None.
- **How to Undo:** Run `wp post delete 523 --force --allow-root`.

---

### CHG-014
- **Date & Time:** 2026-11-14 17:05 UTC
- **Linked Command:** [CMD-014](./02_COMMAND_LOG.md#cmd-014)
- **Type:** Config / Refactored
- **What Changed & Why:** 
  1. Disabled guest checkout in WooCommerce options (`woocommerce_enable_guest_checkout = 'no'`).
  2. Enabled automatic account creation at checkout (`woocommerce_enable_signup_and_login_from_checkout = 'yes'`).
  3. Disabled Cash on Delivery (COD) in settings (`woocommerce_cod_settings['enabled'] = 'no'`) and added a backend filter hook `woocommerce_available_payment_gateways` to completely strip COD from all checkouts.
  4. Updated site announcement banners, header notice, and product trust badges to display *"Paid Doorstep Delivery Across Kenya"*.
- **Files Touched:**
  - Database: `wp_options`
  - `/var/www/clearance/wp-content/themes/clearance-deal/functions.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/header.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/front-page.php`
- **How Tested & Result:** Verified via WP-CLI eval that `WC()->payment_gateways->get_available_payment_gateways()` only contains `pesapal`. Inspected checkout page; confirmed mandatory account signup and absence of COD.
- **Breaking Risk:** Potential slight drop in impulsive checkouts due to account signup; mitigated by automatic password generation and seamless inline checkout signup fields.
- **How to Undo:** In WP-CLI: `wp option set woocommerce_enable_guest_checkout yes --allow-root` and remove the `unset($gateways['cod'])` filter in `functions.php`.

---

### CHG-015
- **Date & Time:** 2026-11-18 14:15 UTC
- **Linked Command:** [CMD-015](./02_COMMAND_LOG.md#cmd-015)
- **Type:** Added
- **What Changed & Why:** Established the standardized `/project-memory/` documentation suite with 12 structured files to preserve project history, operational commands, architectural specs, and QC verification procedures for future AI and human handovers.
- **Files Touched:**
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
- **How Tested & Result:** Verified file paths and integrity on disk and remote server.
- **Breaking Risk:** None. Documentation files only.
- **How to Undo:** Remove directory `/var/www/clearance/project-memory/`.

---

### CHG-016
- **Date & Time:** 2026-11-18 14:50 UTC
- **Linked Command:** [CMD-016](./02_COMMAND_LOG.md#cmd-016)
- **Type:** Config / Security
- **What Changed & Why:**
  1. Configured git repository tracking so that `/project-memory/` is committed and pushed to GitHub (`origin/trust-fixes`) from the local repository `/Users/lenee/clearance-deal/`.
  2. Synced the VPS repository at `/var/www/clearance/` with `origin/trust-fixes` so that both local and VPS maintain an identical copy of the project memory.
  3. Added an explicit Nginx block in `/etc/nginx/sites-available/clearance.co.ke` restricting public web access:
     ```nginx
     location ^~ /project-memory {
         deny all;
         return 403;
     }
     ```
  4. Reloaded Nginx and verified security rule blocks unauthorized web scrapers.
- **Files Touched:**
  - `/Users/lenee/clearance-deal/project-memory/*`
  - `/var/www/clearance/project-memory/*`
  - `/etc/nginx/sites-available/clearance.co.ke`
- **How Tested & Result:** Executed `curl -I https://clearance.co.ke/project-memory/00_README_FOR_AI.md` and `curl -I https://clearance.co.ke/project-memory/`. Both returned `HTTP/2 403 Forbidden`. Also tested `git status` on both local and VPS; both report up to date with `origin/trust-fixes`.
- **Breaking Risk:** None. Only blocks external public HTTP downloads of sensitive operational logs.
- **How to Undo:** Remove the `location ^~ /project-memory` block from `/etc/nginx/sites-available/clearance.co.ke` and reload Nginx.

---

### CHG-017
- **Date & Time:** 2026-11-19 14:38 UTC
- **Linked Command:** [CMD-017](./02_COMMAND_LOG.md#cmd-017)
- **Type:** Config / Added
- **What Changed & Why:**
  1. Updated core discussion options in WordPress database:
     - `comment_moderation` = `1` (all comments must be manually approved).
     - `comment_previously_approved` = `0` (disables auto-approval for users with prior approved reviews).
     - `moderation_notify` = `1` (notifies administrator via email when comments are submitted).
  2. Added an explicit fail-safe filter hook in `wp-content/themes/clearance-deal/functions.php`:
     ```php
     add_filter( 'pre_comment_approved', function( $approved, $commentdata ) {
         if ( in_array( $approved, array( 'spam', 'trash' ), true ) ) {
             return $approved;
         }
         return 0; // Force manual moderation
     }, 999, 2 );
     ```
- **Files Touched:**
  - Database: `wp_options` (`comment_moderation`, `comment_previously_approved`, `moderation_notify`)
  - `/Users/lenee/clearance-deal/wp-content/themes/clearance-deal/functions.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/functions.php`
- **How Tested & Result:** Created a test review via WP-CLI on the live store; verified the resulting comment status was `0` / `hold` and required administrative approval in `/wp-admin/edit-comments.php`.
- **Breaking Risk:** None. Reviews will not display on product pages until approved by the store admin.
- **How to Undo:** Set `wp option set comment_moderation 0` and remove the `pre_comment_approved` filter from `functions.php`.

---

### CHG-018
- **Date & Time:** 2026-11-20 14:45 UTC
- **Linked Command:** [CMD-018](./02_COMMAND_LOG.md#cmd-018)
- **Type:** Added / Upgraded
- **What Changed & Why:**
  1. Authored and published 41 comprehensive, individual product service & how-to care guides (`generate_product_service_blogs.py`), creating a dedicated article for every single catalog item on clearance.co.ke.
  2. Each article is optimized for SEO, AEO (AI engines), and GEO (Kenyan context):
     - Single `<h1>` per page with clean slugs (`how-to-care-for-[product-slug]-kenya`).
     - AEO summary box at top of article.
     - Step-by-step cleaning, washing, detangling, and weatherproofing instructions.
     - Nairobi salon service fee comparison tables vs DIY savings.
     - Embedded product showcase card with price in KSh, 50% Lipa Polepole financing calculation, doorstep delivery notice, and direct "Buy Now" button.
     - JSON-LD structured data for `HowTo` and `FAQPage`.
     - Zero visible publish dates/times (evergreen trust badge format).
  3. Added reciprocal Care Guide banner in `functions.php` on single product pages linking directly to each product's care guide.
  4. Removed duplicate WordPress core `rel_canonical` tag via `remove_action( 'wp_head', 'rel_canonical' )`.
  5. Enhanced `footer.php` with prominent direct links to the entire blog hub (`https://clearance.co.ke/blog/`) under Company & Guides and in the footer-bottom bar.
  6. Verified all 41 posts are listed in the live XML sitemap (`https://clearance.co.ke/wp-sitemap-posts-post-1.xml`, 141 total posts).
- **Files Touched:**
  - `/var/www/clearance/generate_product_service_blogs.py`
  - `/var/www/clearance/products_catalog.json`
  - `/Users/lenee/clearance-deal/wp-content/themes/clearance-deal/functions.php`
  - `/Users/lenee/clearance-deal/wp-content/themes/clearance-deal/footer.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/functions.php`
  - `/var/www/clearance/wp-content/themes/clearance-deal/footer.php`
  - Database: `wp_posts` (IDs 536 to 576), `wp_postmeta`
- **How Tested & Result:** Verified via curl and WP-CLI: total published post count = 141. Single H1 check (`<h1` count = 1), single canonical check, and 200 HTTP response verified on sample care post `how-to-care-for-dyson-airwrap-id-6-in-1-multi-styler-kenya/`. Verified footer links take visitor directly to `/blog/`.
- **Breaking Risk:** None. All new posts use existing responsive templates.
- **How to Undo:** Remove posts via WP-CLI: `wp post delete $(wp post list --post_type=post --category=208 --format=ids --allow-root) --force --allow-root` and revert theme files.
