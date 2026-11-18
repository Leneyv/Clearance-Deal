# 06. DATABASE AND APIS

> **SECURITY NOTICE:** NEVER write plaintext passwords, API secret keys, consumer keys, or customer PII into this document or any other file in `/project-memory/`. Refer to keys only by their environment variable or config option names.

---

## 1. Database Architecture & Schema

The database runs on **MySQL 8.0 / MariaDB** using the standard WordPress table prefix `wp_`.

### A. Core WordPress & WooCommerce Tables

| Table Name | Purpose | Key Columns / Relations |
| :--- | :--- | :--- |
| `wp_posts` | Holds products (`post_type=product`), coupons (`post_type=shop_coupon`), blog articles (`post_type=post`), and orders (`post_type=shop_order`). | `ID`, `post_author`, `post_date`, `post_content`, `post_title`, `post_status`, `post_name`, `post_type`. |
| `wp_postmeta` | Extended metadata for catalog items, orders, and coupons. | `meta_id`, `post_id` (FK to `wp_posts.ID`), `meta_key`, `meta_value`. |
| `wp_options` | Global site configuration, WooCommerce settings, and active gateway flags. | `option_id`, `option_name`, `option_value`, `autoload`. |
| `wp_users` | Customer and administrator user records. Mandatory for all checkout transactions. | `ID`, `user_login`, `user_pass`, `user_email`, `user_registered`. |
| `wp_usermeta` | Customer profile attributes, billing addresses, and shipping details. | `umeta_id`, `user_id` (FK to `wp_users.ID`), `meta_key`, `meta_value`. |
| `wp_woocommerce_order_items` | Line items for completed or pending orders. | `order_item_id`, `order_item_name`, `order_item_type`, `order_id`. |
| `wp_woocommerce_order_itemmeta` | Item pricing, quantities, tax, and variation data. | `meta_id`, `order_item_id`, `meta_key`, `meta_value`. |

### B. Custom Plugin Tables (`clearance-pay-later`)

| Table Name | Purpose | Key Columns |
| :--- | :--- | :--- |
| `wp_cpl_applications` | Records customer Buy Now Pay Later applications and phone verifications. | `id`, `user_id`, `phone_number`, `order_id`, `total_amount`, `deposit_amount`, `status`, `created_at`. |
| `wp_cpl_installments` | Tracks scheduled monthly instalments (1 through 12) for active BNPL contracts. | `id`, `application_id`, `installment_number`, `amount_due`, `due_date`, `paid_at`, `status`. |
| `wp_cpl_logs` | Audit trail of customer interactions and simulation events. | `id`, `event_type`, `payload`, `timestamp`. |

---

## 2. Key `wp_options` Settings Configured

| Option Key | Current Value | Functional Effect |
| :--- | :--- | :--- |
| `woocommerce_enable_guest_checkout` | `no` | Prevents checkout without account creation or login. |
| `woocommerce_enable_signup_and_login_from_checkout` | `yes` | Allows seamless account creation directly during the checkout flow. |
| `woocommerce_enable_myaccount_registration` | `yes` | Displays registration form on My Account page. |
| `woocommerce_cod_settings` | `a:1:{s:7:"enabled";s:2:"no";}` | Disables Cash on Delivery in WooCommerce gateway manager. |
| `woocommerce_pesapal_settings` | Serialized Array | Stores PesaPal v3 API configuration (keys referenced securely below). |
| `active_plugins` | Serialized Array | Controls active plugin roster (`woocommerce`, `clearance-pay-later`, `pesapal`, etc.). |

---

## 3. Active Coupon Records (`wp_posts` ID: 523)

- **Coupon Code:** `CLEARANCE10` (case-insensitive in WooCommerce)
- **Discount Type:** `percent` (10% discount applied to eligible cart items)
- **Amount:** `10`
- **Usage Limit:** `100` total redemptions across all users
- **Usage Limit Per User:** `1` redemption per registered customer
- **Date Expires:** None (`null` — permanent validity until limit reached)
- **Individual Use:** `no`

---

## 4. External APIs & Third-Party Integrations

### A. PesaPal Payment Gateway v3
- **Provider:** PesaPal Kenya (`https://www.pesapal.com`)
- **API Endpoint:** `https://pay.pesapal.com/v3`
- **Authentication:** Consumer Key and Consumer Secret generating short-lived Bearer tokens.
- **Used For:** Real-time checkout transactions, M-Pesa STK push generation, Airtel Money, Visa/Mastercard processing.
- **Inbound Webhook / IPN Endpoint:**
  - `https://clearance.co.ke/?wc-api=WC_Pesapal_Gateway`
  - When a customer enters their PIN on M-Pesa, PesaPal issues a signed POST webhook to this URL containing `OrderTrackingId` and `OrderMerchantReference`.
- **Scheduled Cron Endpoint:**
  - `https://clearance.co.ke/?wc-api=WC_Pesapal_Cron` (used to query pending transactions and synchronize order statuses).

### B. Meta (Facebook) Conversion API & Pixel
- **Plugin:** `official-facebook-pixel` & `facebook-for-woocommerce`
- **Purpose:** Tracks e-commerce funnel events (`PageView`, `ViewContent`, `AddToCart`, `InitiateCheckout`, `Purchase`) for retargeting Kenyan hair shoppers on Instagram & Facebook.

### C. Microsoft Clarity
- **Plugin:** `microsoft-clarity`
- **Purpose:** Heatmaps and session recordings to monitor mobile checkout friction and BNPL widget engagement.

### D. Independent Analytics
- **Plugin:** `independent-analytics`
- **Purpose:** Lightweight, server-side GDPR-compliant analytics tracking visits, page views, and search terms inside WordPress admin without external cookies.

---

## 5. Security & Secret Key Storage Policy
All credentials for external APIs (e.g., `PESAPAL_CONSUMER_KEY`, `PESAPAL_CONSUMER_SECRET`) are stored strictly inside the WordPress database or environment files (`wp-config.php`). They are never exposed in client-facing JavaScript, never echoed in API error responses, and never recorded in this repository.
