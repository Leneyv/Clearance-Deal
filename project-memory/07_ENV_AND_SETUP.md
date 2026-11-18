# 07. ENVIRONMENT AND SETUP GUIDE

> **SECURITY NOTICE:** NEVER write plaintext passwords, API keys, tokens, or private secrets in this document. Only document variable names and their functional roles.

---

## 1. Server Environment Details

- **Operating System:** Ubuntu 24.04 LTS (x86_64)
- **Primary Server IP:** `129.121.132.35`
- **SSH Access:** `ssh root@129.121.132.35`
- **Web Server:** Nginx 1.24.0
- **PHP Version:** PHP 8.3-FPM (`php8.3-fpm.service`)
- **Database:** MySQL 8.0 / MariaDB (`mysql.service`)
- **Document Root:** `/var/www/clearance`
- **Theme Root:** `/var/www/clearance/wp-content/themes/clearance-deal`
- **WP-CLI Path:** `/usr/local/bin/wp` (executable with `--allow-root`)

---

## 2. Environment Variables & Configuration Keys

These configuration parameters reside in `/var/www/clearance/wp-config.php` or `.env`:

| Key Name | Type | Description |
| :--- | :--- | :--- |
| `DB_NAME` | String | MySQL database name for WordPress. |
| `DB_USER` | String | MySQL database username. |
| `DB_PASSWORD` | Secret | MySQL database user password. |
| `DB_HOST` | String | Database host (typically `localhost` or `127.0.0.1`). |
| `DB_CHARSET` | String | Database character set (`utf8mb4`). |
| `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY` | Secret | Cryptographic salts for user authentication and session cookies. |
| `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT` | Secret | Cryptographic salts for nonces and token hashing. |
| `PESAPAL_CONSUMER_KEY` | String | PesaPal v3 API consumer key (configured in WooCommerce gateway settings). |
| `PESAPAL_CONSUMER_SECRET` | Secret | PesaPal v3 API consumer secret (configured in WooCommerce gateway settings). |
| `WP_CACHE` | Boolean | Enables WP Super Cache drop-in execution (`true`). |
| `WP_DEBUG` | Boolean | Set to `false` in production to prevent PHP error leaking on public endpoints. |

---

## 3. How to Deploy & Synchronize Changes

### A. Deploying Theme Changes
1. Local edits to `clearance-deal` can be synchronized via SCP/RSYNC or git:
   ```bash
   scp -r ./wp-content/themes/clearance-deal/* root@129.121.132.35:/var/www/clearance/wp-content/themes/clearance-deal/
   ```
2. Set correct file ownership on the remote host:
   ```bash
   chown -R www-data:www-data /var/www/clearance/wp-content/themes/clearance-deal/
   find /var/www/clearance/wp-content/themes/clearance-deal/ -type f -exec chmod 644 {} +
   find /var/www/clearance/wp-content/themes/clearance-deal/ -type d -exec chmod 755 {} +
   ```

### B. Purging Caches After Updates
Whenever theme files, CSS, or WooCommerce options change, flush the caches:
```bash
# Flush WP Super Cache & Object Cache via WP-CLI
cd /var/www/clearance
wp cache flush --allow-root

# Reload PHP-FPM opcode cache
systemctl reload php8.3-fpm

# Reload Nginx web server
systemctl reload nginx
```

### C. Synchronizing Project Memory (Dual Local & VPS Sync)
After every task, once the memory files are updated:
```bash
# 1. Commit and push from local repository:
cd /Users/lenee/clearance-deal
git add project-memory/
git commit -m "docs(memory): update project memory [CMD-xxx/CHG-xxx]"
git push origin trust-fixes

# 2. Synchronize VPS copy via SSH:
ssh root@129.121.132.35 "cd /var/www/clearance && git pull origin trust-fixes && chown -R www-data:www-data /var/www/clearance/project-memory"
```

---

## 4. Useful Operational WP-CLI Commands

### Catalog & Options Verification
```bash
# Check product count
wp post list --post_type=product --post_status=publish --format=count --allow-root

# Check blog post count
wp post list --post_type=post --post_status=publish --format=count --allow-root

# Verify Guest Checkout is disabled
wp option get woocommerce_enable_guest_checkout --allow-root
# (Expected output: no)

# Verify Signup at Checkout is enabled
wp option get woocommerce_enable_signup_and_login_from_checkout --allow-root
# (Expected output: yes)

# List active payment gateways
wp eval 'print_r(array_keys(WC()->payment_gateways->get_available_payment_gateways()));' --allow-root
# (Expected output: Array ( [0] => pesapal ))
```

### Coupon Management
```bash
# Inspect CLEARANCE10 coupon configuration
wp post list --post_type=shop_coupon --allow-root
wp post meta list 523 --allow-root
```

---

## 5. System Service Management

| Service | Status Command | Restart Command |
| :--- | :--- | :--- |
| **Nginx** | `systemctl status nginx` | `systemctl restart nginx` |
| **PHP 8.3-FPM** | `systemctl status php8.3-fpm` | `systemctl restart php8.3-fpm` |
| **MySQL** | `systemctl status mysql` | `systemctl restart mysql` |
