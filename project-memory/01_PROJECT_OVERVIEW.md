# 01. PROJECT OVERVIEW

## 1. Project Information
- **Project Name:** Clearance Kenya
- **Live URL:** [https://clearance.co.ke](https://clearance.co.ke)
- **Staging / Admin URL:** `https://clearance.co.ke/wp-admin/`
- **Project Type:** E-commerce Web Application with AI Search Engine Optimization (AEO/SEO) and Integrated FinTech (BNPL)
- **Primary Market:** Nairobi, Mombasa, Kisumu, Nakuru, Eldoret, and all 47 counties of Kenya.

---

## 2. Business Goal & Purpose
Clearance Kenya serves as an authentic liquidation and inventory-clearance store in Kenya. 
Traditional retailers in East Africa carry high margins and long stock cycles; Clearance Kenya sources certified overstock, liquidated imports, and salon-grade human hair wigs to sell directly to consumers at steep clearance discounts (often 30% to 60% below standard Nairobi CBD beauty boutiques).

Key business objectives:
1. **Dominate Kenyan Search Intent:** Capture consumers searching for `"Price in Kenya"` across hair categories (bone straight, lace frontals, braided wigs, closure wigs, Brazilian/Peruvian bundles).
2. **FinTech Conversion Booster (Lipa Polepole):** Offer Buy Now Pay Later (BNPL) to make high-value human hair wigs (KSh 10,000 – KSh 35,000) accessible via a 50% upfront payment and 12 flexible monthly instalments.
3. **High-Trust, Zero-Fraud Checkout:** Eliminate high courier rejection rates associated with Cash on Delivery (COD) by strictly requiring upfront mobile money / card settlement via PesaPal, combined with mandatory account signups for seamless courier dispatch tracking.

---

## 3. Target Audience & Personas
1. **The Kenyan Hair Connoisseur & Everyday Shopper:** Women and stylists in Kenya seeking genuine 100% human hair wigs, lace wigs, and extensions without paying exorbitant prices at high-end salons. They demand transparency on "Price in Kenya", density, hair origin, and lace type.
2. **Value Shoppers & Liquidation Deal Hunters:** Tech and household bargain hunters looking for genuine liquidated electronics and accessories.
3. **Store Administrators & Order Dispatchers:** Operational team in Nairobi managing inventory, confirming PesaPal payment receipts, issuing waybills, and dispatching parcel deliveries via local couriers (Fargo Courier, Wells Fargo, G4S, Speedaf).

---

## 4. Tech Stack & Software Versions

| Layer | Component | Version | Notes |
| :--- | :--- | :--- | :--- |
| **Operating System** | Ubuntu Linux | 24.04 LTS (Noble Numbat) | Running on Contabo KVM VPS |
| **Web Server** | Nginx | 1.24.0 | Configured with HTTP/2, FastCGI microcache, gzip/brotli |
| **Backend Runtime** | PHP | 8.3-FPM | OPcache enabled; handles WordPress execution |
| **Database** | MySQL | 8.0.x / MariaDB | InnoDB engine, utf8mb4 collation |
| **Core CMS** | WordPress | 6.x | Rooted at `/var/www/clearance/` |
| **E-Commerce** | WooCommerce | 11.x | Headless-ready catalog & checkout engine |
| **Active Theme** | `clearance-deal` | 1.0.0+ | Custom-built high-performance mobile-first theme |
| **Payments Gateway** | PesaPal WooCommerce | 3.1.3 | Supports M-Pesa STK push, Airtel Money, Visa, MasterCard |
| **BNPL Engine** | `clearance-pay-later` | 1.1.0 | Custom proprietary plugin for 50% payment + 12 instalments |
| **Caching Engine** | WP Super Cache | 3.1.3+ | Static HTML disk caching with advanced-cache drop-in |
| **Analytics & Pixel** | Independent Analytics & FB Pixel | 2.15+ / 5.2+ | Privacy-first analytics and Meta conversion tracking |

---

## 5. Hosting & Infrastructure
- **Server IP:** `129.121.132.35`
- **SSH User:** `root`
- **Application Web Root:** `/var/www/clearance`
- **Theme Path:** `/var/www/clearance/wp-content/themes/clearance-deal`
- **Custom Plugins Path:** `/var/www/clearance/wp-content/plugins/clearance-pay-later`
- **Domain Name:** `clearance.co.ke` (DNS managed via external nameservers with A-record pointing to `129.121.132.35`)
- **SSL Certificate:** Let's Encrypt TLS certificate managed via Certbot auto-renewal.

---

## 6. User Roles & Permission Model
1. **Administrator (`administrator`):** Full control over WordPress settings, WooCommerce catalog, orders, coupons, customer databases, theme modifications, and server-side files.
2. **Shop Manager (`shop_manager`):** Access to WooCommerce orders, refunds, inventory stock levels, and product descriptions without core file editing privileges.
3. **Customer (`customer`):** Registered shopper account. Allowed to view own order history, save billing/delivery addresses in Kenya, track courier status, and initiate BNPL applications.
4. **Guest (Unauthenticated):** Can browse catalog, read blog posts, simulate BNPL eligibility, and add items to cart. However, **guest checkout is disabled**—upon clicking proceed to checkout, WooCommerce automatically provisions an account during checkout or requires an existing login.
