# 05. ARCHITECTURE

## 1. System Overview & Technology Topology

Clearance Kenya (`clearance.co.ke`) runs on an optimized LEMP stack (Linux, Nginx, MySQL, PHP-FPM) tailored for fast mobile shopping and high-conversion e-commerce in Kenya.

```text
                               +----------------------------------+
                               |   Kenyan Shoppers & AI Crawlers  |
                               +-----------------+----------------+
                                                 |
                                     (HTTPS / HTTP/2 - 443)
                                                 v
                               +-----------------+----------------+
                               |        Nginx 1.24.0 Proxy        |
                               |    (SSL, Gzip, Microcaching)     |
                               +-----------------+----------------+
                                                 |
                                                 v
                       +-------------------------+-------------------------+
                       |                                                   |
            [Static Assets / FastCGI]                                 [PHP Engine]
                       |                                                   |
                       v                                                   v
          +------------+------------+                             +--------+---------+
          |  /var/www/clearance     |                             |   PHP 8.3-FPM    |
          |  (Nginx Direct Serving) |                             +--------+---------+
          +-------------------------+                                      |
                                                                           v
                                                         +-----------------+-----------------+
                                                         |      WordPress 6.x Core           |
                                                         |  - WooCommerce 11.x Catalog       |
                                                         |  - WP Super Cache Static Engine   |
                                                         +-----------------+-----------------+
                                                                           |
                                  +----------------------------------------+----------------------------------------+
                                  |                                                                                 |
                                  v                                                                                 v
                      +-----------+------------+                                                        +-----------+------------+
                      | clearance-deal Theme   |                                                        | Custom / 3rd Plugins   |
                      | - front-page.php       |                                                        | - clearance-pay-later  |
                      | - inc/seo-aeo.php      |                                                        | - pesapal-woocommerce  |
                      | - inc/bnpl-widget.php  |                                                        | - independent-analytic |
                      +-----------+------------+                                                        +-----------+------------+
                                  |                                                                                 |
                                  +----------------------------------------+----------------------------------------+
                                                                           |
                                                                           v
                                                                +----------+----------+
                                                                |   MySQL 8.0 Database|
                                                                | (wp_posts, options) |
                                                                +---------------------+
```

---

## 2. Server & Web Directory Layout

```text
/var/www/clearance/
├── .env.example                               # Sample environment keys
├── .git/                                      # Git version repository (branch: trust-fixes)
├── 3feb6da6b6f0410e8c14141e6ab14be6.txt       # Search engine site verification token
├── generate_100_blogs.py                      # Standalone CLI script generating 100 SEO posts
├── llms.txt                                   # AI Search summary file for LLMs (Perplexity/ChatGPT)
├── llms-full.txt                              # Full context markdown for AI agents
├── project-memory/                            # Permanent project operational logs (THIS REPO - Blocked via Nginx 403)
│   ├── 00_README_FOR_AI.md
│   ├── 01_PROJECT_OVERVIEW.md
│   ├── 02_COMMAND_LOG.md
│   ├── 03_CHANGELOG.md
│   ├── 04_FEATURES_STATUS.md
│   ├── 05_ARCHITECTURE.md
│   ├── 06_DATABASE_AND_APIS.md
│   ├── 07_ENV_AND_SETUP.md
│   ├── 08_DECISIONS.md
│   ├── 09_KNOWN_ISSUES_AND_TODO.md
│   ├── 10_QC_CHECKLIST.md
│   └── 11_AGENT_AND_PROMPTS.md
├── robots.txt                                 # Search engine robot directives
├── index.php                                  # WordPress entry point
├── wp-config.php                              # Core DB credentials & security keys
├── wp-content/
│   ├── mu-plugins/
│   │   ├── clearance-organic-growth.php       # Autoloaded optimization rules
│   │   └── clearance-speed.php                # Autoloaded asset minification & script deferral
│   ├── plugins/
│   │   ├── clearance-pay-later/               # Custom Lipa Polepole plugin (50% down / 12 mo)
│   │   │   ├── clearance-pay-later.php        # Plugin bootstrap
│   │   │   ├── assets/js/cart-popup.speed.min.js
│   │   │   └── includes/                      # Calculation logic and checkout hooks
│   │   ├── thebunch-ke-pesapal-woocommerce-3.1.3/ # PesaPal v3 payment gateway
│   │   ├── woocommerce/                       # WooCommerce core
│   │   └── wp-super-cache/                    # Static cache generator
│   └── themes/
│       └── clearance-deal/                    # Custom bespoke theme
│           ├── style.css                      # Global styles & brand CSS variables
│           ├── functions.php                  # Theme hooks, payment overrides, enqueue logic
│           ├── header.php                     # Announcement banner, navigation, meta head
│           ├── footer.php                     # Mobile bottom bar, footer links, copyright
│           ├── front-page.php                 # Homepage liquidation deal sections & hero
│           ├── single.php                     # Single blog post template (no publish date)
│           ├── page-blog.php                  # Blog hub listing all 100 SEO articles
│           ├── page-jobs.php                  # Careers page (strictly excludes BNPL widget)
│           ├── archive.php                    # Category/tag archive template
│           └── inc/
│               ├── bnpl-floating-widget.php   # Sticky vertical Lipa Polepole simulator
│               └── seo-aeo.php                # JSON-LD Schema, meta tags, "Price in Kenya"
```

---

## 3. Core Component Roles & Connections

### 1. `clearance-deal` Theme
- **`style.css`**: Defines CSS tokens for brand colors (`--cd-brand-aubergine`, `--cd-brand-marigold`, `--cd-brand-ice-silver`), mobile layout grids (2 products per row on mobile), and micro-interactions.
- **`functions.php`**: Registers theme supports, enqueues minified assets, binds `inc/seo-aeo.php` to `wp_head`, binds `inc/bnpl-floating-widget.php` to `wp_footer`, and applies the `woocommerce_available_payment_gateways` filter to block Cash on Delivery.
- **`inc/seo-aeo.php`**: Evaluates the current page context (`is_front_page()`, `is_product()`, `is_single()`, `is_category()`) and prints optimized title tags, meta tags, and structured JSON-LD schemas (`WebSite`, `OnlineStore`, `Product`, `FAQPage`, `BreadcrumbList`).
- **`inc/bnpl-floating-widget.php`**: Renders a vertical tab on the right viewport border. Suppresses execution if `is_page('jobs')` is true. Contains an inline lightweight JavaScript state engine simulating mobile phone eligibility for the 50% downpayment plan.

### 2. `clearance-pay-later` Plugin
- Integrates directly with WooCommerce cart and checkout sessions.
- Calculates 50% initial payment required today vs 12 monthly instalments (plus standard processing interest).
- Injects a summary row into WooCommerce order review tables: *"Payment due today: KSh X"*.

### 3. PesaPal Gateway (`thebunch-ke-pesapal-woocommerce-3.1.3`)
- Direct integration with PesaPal v3 API (`https://pay.pesapal.com/v3`).
- Handles instant M-Pesa STK push requests and Card verification.
- Implements Instant Payment Notification (IPN) listener at `https://clearance.co.ke/?wc-api=WC_Pesapal_Gateway` to automatically advance order status from `wc-pending` to `wc-processing`.

---

## 4. Primary Data Flows

### A. Shopping & Checkout Flow
```text
1. Shopper browses Catalog / Blog on mobile.
2. Shopper adds 100% Human Hair Wig (e.g. KSh 14,500) to Cart.
3. Cart calculates total + displays Lipa Polepole breakdown (50% today = KSh 7,250).
4. Shopper clicks "Proceed to Checkout".
5. WooCommerce blocks guest checkout:
   - If guest has existing account -> prompts login.
   - If new customer -> requires name, phone, email, and password to provision account.
6. Checkout forces Paid Doorstep Delivery (flat rate across Nairobi/Kenya).
7. Available Payment Gateways evaluated:
   - COD (`cod`) is stripped out by functions.php filter.
   - Only PesaPal (`pesapal`) remains.
8. Customer places order -> redirected to PesaPal v3 secure gateway.
9. Customer inputs M-Pesa phone number -> receives STK Push prompt on handset.
10. M-Pesa PIN confirmed -> PesaPal sends IPN to `https://clearance.co.ke/?wc-api=WC_Pesapal_Gateway`.
11. Order marked "Processing" -> Store team receives order for packaging & dispatch.
```

### B. SEO / AEO Crawl Flow
```text
1. Search engine bot (Googlebot) or AI Crawler (Perplexity / GPTBot) requests URL.
2. Nginx checks WP Super Cache for static HTML representation.
3. If fresh cache exists -> served instantly (<100ms).
4. If cache miss -> PHP-FPM executes WordPress core:
   - `inc/seo-aeo.php` builds contextual JSON-LD schema with "Price in Kenya".
   - `single.php` renders article with "Verified Guide" badge (no publish date).
5. Output cached to disk for subsequent crawler hits.
```
