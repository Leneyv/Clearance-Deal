<?php
/**
 * Clearance Deal functions and definitions
 *
 * @package Clearance_Deal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// ── Meta Pixel ────────────────────────────────────────────────────────────────
add_action( 'wp_head', function () { ?>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2181679409046850');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=2181679409046850&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
<?php }, 1 ); // priority 1 — fires near the top of <head>

if ( ! function_exists( 'clearance_deal_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function clearance_deal_setup() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and WordPress will
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Register Navigation Menus
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'clearance-deal' ),
				'footer'  => esc_html__( 'Footer Menu', 'clearance-deal' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, comments, gallery,
		 * caption, and script/style to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add support for core custom logo.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'clearance_deal_setup' );

/**
 * Declare WooCommerce support.
 *
 * Without this, WooCommerce skips its own template routing entirely
 * (see WC_Template_Loader::init()) and every shop/product page falls
 * back to the theme's generic index.php.
 */
function clearance_deal_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'clearance_deal_woocommerce_support' );

/**
 * The theme has no sidebar anywhere (full-width grid design), so drop
 * WooCommerce's default shop sidebar instead of building one out.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function clearance_deal_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Deals Sidebar', 'clearance-deal' ),
			'id'            => 'deals-sidebar',
			'description'   => esc_html__( 'Add widgets here to appear in the deals feed sidebar.', 'clearance-deal' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'clearance_deal_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function clearance_deal_scripts() {
	wp_enqueue_style( 'clearance-deal-style', get_stylesheet_uri(), array(), '1.0.8' );
	// Self-hosted Font Awesome — eliminates external CDN round-trip
	wp_enqueue_style(
		'font-awesome',
		get_template_directory_uri() . '/assets/css/font-awesome.local.min.css',
		array(),
		'6.4.0'
	);
}
add_action( 'wp_enqueue_scripts', 'clearance_deal_scripts' );

/**
 * Load Font Awesome non-render-blocking via media swap trick.
 * Swap media from "print" → "all" once the stylesheet is parsed,
 * so it never blocks first paint.
 */
function clearance_deal_fa_nonblocking( $html, $handle ) {
	if ( 'font-awesome' !== $handle ) {
		return $html;
	}
	$html = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $html );
	// Noscript fallback so icons still appear without JS
	$html .= "<noscript><link rel='stylesheet' href='" . esc_url( get_template_directory_uri() . '/assets/css/font-awesome.local.min.css' ) . "'></noscript>";
	return $html;
}
add_filter( 'style_loader_tag', 'clearance_deal_fa_nonblocking', 10, 2 );

/**
 * Defer jQuery core (render-blocking by default in WP head).
 * WooCommerce defers its own scripts; jQuery itself does not.
 */
function clearance_deal_defer_jquery( $tag, $handle ) {
	if ( in_array( $handle, array( 'jquery-core', 'jquery-migrate' ), true ) && ! is_admin() ) {
		// Don't defer jQuery on checkout/order-pay, product, shop or category pages.
		// Checkout: payment gateway inline scripts need it immediately.
		// Product pages: variation form JS relies on jQuery before form submit.
		// Shop/category: CPL's added_to_cart handler requires jQuery to be available.
		if ( function_exists( 'is_checkout' ) && ( is_checkout() || is_wc_endpoint_url( 'order-pay' ) || is_product() || is_shop() || is_product_category() || is_product_tag() ) ) {
			return $tag;
		}
		return str_replace( '<script ', '<script defer ', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'clearance_deal_defer_jquery', 10, 2 );

/**
 * Disable wp-emoji — saves ~10KB JS + an extra DNS lookup.
 */
function clearance_deal_disable_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'clearance_deal_disable_emoji' );

/**
 * Remove RSD and wlwmanifest link tags — not needed for a public store.
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'wp_generator' );


/**
 * Defer all non-critical WooCommerce and third-party scripts that
 * aren't already deferred, including order-attribution and Facebook Pixel.
 */
function clearance_deal_defer_scripts( $tag, $handle ) {
	if ( is_admin() ) {
		return $tag;
	}

	$defer_handles = array(
		'sourcebuster-js',
		'wc-order-attribution',
		'official-facebook-pixel',
		'facebook-for-woocommerce',
	);

	foreach ( $defer_handles as $h ) {
		if ( $h === $handle && false === strpos( $tag, 'defer' ) ) {
			return str_replace( '<script ', '<script defer ', $tag );
		}
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'clearance_deal_defer_scripts', 10, 2 );

/**
 * Dequeue WooCommerce CSS that the homepage doesn't use.
 * Also dequeue WooCommerce general CSS on non-WC pages — it's
 * large and most of it is for cart/checkout/account only.
 */
function clearance_deal_dequeue_unused_wc() {
	if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
		wp_dequeue_style( 'woocommerce-general' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
	}
}
add_action( 'wp_enqueue_scripts', 'clearance_deal_dequeue_unused_wc', 99 );

/**
 * Add preconnect/dns-prefetch hints for performance.
 */
function clearance_deal_resource_hints() {
	echo '<link rel="dns-prefetch" href="//www.googletagmanager.com">' . "\n";
	echo '<link rel="dns-prefetch" href="//connect.facebook.net">' . "\n";
}
add_action( 'wp_head', 'clearance_deal_resource_hints', 1 );

/**
 * Show a "Sold Out" badge for out-of-stock products.
 *
 * WooCommerce only ships a "Sale!" flash by default; there's nothing
 * built in for stock status.
 */
function clearance_deal_stock_badge() {
	global $product;

	if ( $product && ! $product->is_in_stock() ) {
		echo '<span class="out-of-stock-flash">' . esc_html__( 'Sold Out', 'clearance-deal' ) . '</span>';
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'clearance_deal_stock_badge', 15 );
add_action( 'woocommerce_before_single_product_summary', 'clearance_deal_stock_badge', 15 );

/**
 * Trust badges (returns / delivery / payment) shown above the Add to
 * Cart button on the single product page.
 */
function clearance_deal_trust_badges() {
	?>
	<div class="trust-badges">
		<div class="trust-badge">
			<i class="fa-solid fa-rotate-left"></i>
			<span><?php esc_html_e( 'Easy Return in 7 Days', 'clearance-deal' ); ?></span>
		</div>
		<div class="trust-badge">
			<i class="fa-solid fa-truck-fast"></i>
			<span><?php esc_html_e( 'Free Delivery', 'clearance-deal' ); ?></span>
		</div>
		<div class="trust-badge trust-badge--mpesa">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/mpesa-logo.svg' ); ?>" alt="M-Pesa" class="mpesa-logo-img" />
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'clearance_deal_trust_badges', 29 );

/**
 * Custom sequential order numbers starting at 99819932 -- a brand new
 * store shouldn't show its first customer "Order #1".
 */
function clearance_deal_assign_order_number( $order_id ) {
	$order = wc_get_order( $order_id );

	if ( ! $order || $order->get_meta( '_clearance_deal_order_number' ) ) {
		return;
	}

	$next = (int) get_option( 'clearance_deal_next_order_number', 99819932 );

	$order->update_meta_data( '_clearance_deal_order_number', $next );
	$order->save();

	update_option( 'clearance_deal_next_order_number', $next + 1 );
}
add_action( 'woocommerce_new_order', 'clearance_deal_assign_order_number' );

function clearance_deal_order_number( $order_number, $order ) {
	$custom = $order->get_meta( '_clearance_deal_order_number' );
	return $custom ? $custom : $order_number;
}
add_filter( 'woocommerce_order_number', 'clearance_deal_order_number', 10, 2 );

/**
 * Normalize a phone number to WhatsApp's wa.me format: digits only, with
 * the Kenyan country code, no leading 0/+.
 */
function clearance_deal_normalize_wa_phone( $phone ) {
	$digits = preg_replace( '/\D+/', '', (string) $phone );

	if ( empty( $digits ) ) {
		return '';
	}

	if ( '0' === substr( $digits, 0, 1 ) ) {
		$digits = '254' . substr( $digits, 1 );
	} elseif ( '254' !== substr( $digits, 0, 3 ) && strlen( $digits ) <= 9 ) {
		$digits = '254' . $digits;
	}

	return $digits;
}

/**
 * Build the pre-filled WhatsApp order message for a given order.
 */
function clearance_deal_whatsapp_order_message( $order ) {
	$lines = array();
	foreach ( $order->get_items() as $item ) {
		$lines[] = $item->get_quantity() . 'x ' . $item->get_name();
	}

	return sprintf(
		/* translators: 1: customer first name, 2: order number, 3: order items, 4: order total */
		__( "Hi %1\$s! Thank you for your order #%2\$s from Clearance Deal Kenya.\n\nItems:\n%3\$s\n\nTotal: %4\$s\n\nWe'll notify you once it's on the way. Reply here if you have any questions!", 'clearance-deal' ),
		$order->get_billing_first_name(),
		$order->get_order_number(),
		implode( "\n", $lines ),
		str_replace( "\xc2\xa0", ' ', html_entity_decode( wp_strip_all_tags( $order->get_formatted_order_total() ), ENT_QUOTES ) )
	);
}

/**
 * "Send WhatsApp Message" button on the order edit screen -- opens
 * WhatsApp (Web or App, whichever the logged-in admin is using) with the
 * customer's number and the order message pre-filled. No API/account
 * needed; the admin just has to hit send from the store's WhatsApp.
 */
function clearance_deal_order_whatsapp_button( $order ) {
	$phone = clearance_deal_normalize_wa_phone( $order->get_billing_phone() );

	if ( ! $phone ) {
		echo '<p><em>' . esc_html__( 'No phone number on this order -- cannot send WhatsApp message.', 'clearance-deal' ) . '</em></p>';
		return;
	}

	$wa_url = 'https://wa.me/' . $phone . '?text=' . rawurlencode( clearance_deal_whatsapp_order_message( $order ) );
	?>
	<p class="form-field form-field-wide">
		<a href="<?php echo esc_attr( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary" style="background:#25D366; border-color:#1DA851; color:#fff;">
			<?php esc_html_e( 'Send WhatsApp Message', 'clearance-deal' ); ?>
		</a>
	</p>
	<?php
}
add_action( 'woocommerce_admin_order_data_after_order_details', 'clearance_deal_order_whatsapp_button' );

/**
 * Add a "Store WhatsApp Number" field to WooCommerce > Settings > General.
 * This is the number customers are prompted to message after checkout.
 */
function clearance_deal_whatsapp_number_setting( $settings ) {
	$new_field = array(
		'title'   => __( 'Store WhatsApp Number', 'clearance-deal' ),
		'desc'    => __( 'Customers are prompted to send their order details to this number on WhatsApp after checkout. Include country code, digits only (e.g. 254751338975).', 'clearance-deal' ),
		'id'      => 'clearance_deal_store_whatsapp_number',
		'type'    => 'text',
		'css'     => 'min-width:300px;',
		'default' => '254751338975',
	);

	$inserted     = false;
	$new_settings = array();

	foreach ( $settings as $setting ) {
		if ( ! $inserted && isset( $setting['type'] ) && 'sectionend' === $setting['type'] ) {
			$new_settings[] = $new_field;
			$inserted       = true;
		}
		$new_settings[] = $setting;
	}

	if ( ! $inserted ) {
		$new_settings[] = $new_field;
	}

	return $new_settings;
}
add_filter( 'woocommerce_general_settings', 'clearance_deal_whatsapp_number_setting' );

/**
 * Build the message a customer sends to the store on WhatsApp after checkout.
 */
function clearance_deal_customer_whatsapp_message( $order ) {
	$lines = array();
	foreach ( $order->get_items() as $item ) {
		$lines[] = $item->get_quantity() . 'x ' . $item->get_name();
	}

	return sprintf(
		/* translators: 1: order number, 2: customer name, 3: phone, 4: items, 5: total */
		__( "Hi Clearance Deal Kenya! I just placed an order.\n\nOrder #%1\$s\nName: %2\$s\nPhone: %3\$s\n\nItems:\n%4\$s\n\nTotal: %5\$s\n\nPlease confirm my order. Thank you!", 'clearance-deal' ),
		$order->get_order_number(),
		$order->get_formatted_billing_full_name(),
		$order->get_billing_phone(),
		implode( "\n", $lines ),
		str_replace( "\xc2\xa0", ' ', html_entity_decode( wp_strip_all_tags( $order->get_formatted_order_total() ), ENT_QUOTES ) )
	);
}

/**
 * Popup shown on the order-received (thank you) page prompting the
 * customer to send their order details to the store's WhatsApp number.
 */
function clearance_deal_thankyou_whatsapp_popup( $order_id ) {
	if ( ! $order_id ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	$store_number = clearance_deal_normalize_wa_phone( get_option( 'clearance_deal_store_whatsapp_number', '254751338975' ) );
	if ( ! $store_number ) {
		return;
	}

	$wa_url = 'https://wa.me/' . $store_number . '?text=' . rawurlencode( clearance_deal_customer_whatsapp_message( $order ) );
	?>
	<div id="whatsapp-order-popup" class="whatsapp-popup-overlay">
		<div class="whatsapp-popup-box">
			<button type="button" class="whatsapp-popup-close" aria-label="<?php esc_attr_e( 'Close', 'clearance-deal' ); ?>">&times;</button>
			<div class="whatsapp-popup-icon"><i class="fa-brands fa-whatsapp"></i></div>
			<h3><?php esc_html_e( 'Order Confirmed!', 'clearance-deal' ); ?></h3>
			<p><?php esc_html_e( 'Send us your order details on WhatsApp so we can confirm and process it faster.', 'clearance-deal' ); ?></p>
			<a href="<?php echo esc_attr( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="whatsapp-popup-btn" id="whatsapp-popup-send">
				<i class="fa-brands fa-whatsapp"></i> <?php esc_html_e( 'Send via WhatsApp', 'clearance-deal' ); ?>
			</a>
			<button type="button" class="whatsapp-popup-later"><?php esc_html_e( 'Maybe later', 'clearance-deal' ); ?></button>
		</div>
	</div>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
		var popup = document.getElementById('whatsapp-order-popup');
		if (!popup) {
			return;
		}

		setTimeout(function () { popup.classList.add('is-visible'); }, 600);

		function closePopup() {
			popup.classList.remove('is-visible');
		}

		popup.querySelector('.whatsapp-popup-close').addEventListener('click', closePopup);
		popup.querySelector('.whatsapp-popup-later').addEventListener('click', closePopup);
		popup.addEventListener('click', function (e) {
			if (e.target === popup) {
				closePopup();
			}
		});
		document.getElementById('whatsapp-popup-send').addEventListener('click', function () {
			setTimeout(closePopup, 300);
		});
	});
	</script>
	<?php
}
add_action( 'woocommerce_thankyou', 'clearance_deal_thankyou_whatsapp_popup' );

/**
 * A server-rendered "Review Quantities" panel shown above the checkout
 * block. The block-based Checkout page has no built-in way to change
 * item quantities (only the Cart page does), and this store's "Buy Now"
 * flow skips the Cart page entirely -- so without this, a customer can't
 * adjust quantity at all during purchase. Updates go through the Store
 * API and reload the page so the checkout block's totals stay correct.
 */
function clearance_deal_checkout_quantity_editor() {
        if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) {
                return;
        }
        ?>
        <div class="qty-editor">
                <h2 class="qty-editor-title"><?php esc_html_e( 'Review Quantities', 'clearance-deal' ); ?></h2>
                <div class="qty-editor-items">
                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                                $product = $cart_item['data'];
                                if ( ! $product ) {
                                        continue;
                                }
                                ?>
                                <div class="qty-editor-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                        <div class="qty-editor-item-image"><?php echo $product->get_image( 'thumbnail' ); ?></div>
                                        <div class="qty-editor-item-name"><?php echo esc_html( $product->get_name() ); ?></div>
                                        <div class="qty-editor-item-controls">
                                                <button type="button" class="qty-editor-btn qty-editor-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'clearance-deal' ); ?>">&#8722;</button>
                                                <input type="number" class="qty-editor-input" min="1" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>">
                                                <button type="button" class="qty-editor-btn qty-editor-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'clearance-deal' ); ?>">&#43;</button>
                                                <button type="button" class="qty-editor-btn qty-editor-remove" title="<?php esc_attr_e( 'Remove item', 'clearance-deal' ); ?>"><i class="fa-solid fa-trash-can"></i></button>
                                        </div>
                                        <div class="qty-editor-spinner" hidden></div>
                                </div>
                        <?php endforeach; ?>
                </div>
                <p class="qty-editor-hint"><?php esc_html_e( 'Totals below update automatically after you change a quantity or remove an item.', 'clearance-deal' ); ?></p>
        </div>
        <script>
        (function () {
                var storeNonce = '';

                function storeApiFetch(path, options) {
                        options = options || {};
                        options.credentials = 'same-origin';
                        options.headers = Object.assign({ 'Content-Type': 'application/json' }, options.headers || {});
                        return fetch('/?rest_route=' + path, options).then(function (res) {
                                var nonce = res.headers.get('Nonce');
                                if (nonce) {
                                        storeNonce = nonce;
                                }
                                return res.json().then(function (data) {
                                        if (!res.ok) {
                                                throw data;
                                        }
                                        return data;
                                });
                        });
                }

                storeApiFetch('/wc/store/v1/cart').catch(function () {});

                document.querySelectorAll('.qty-editor-item').forEach(function (row) {
                        var key = row.getAttribute('data-cart-item-key');
                        var input = row.querySelector('.qty-editor-input');
                        var minus = row.querySelector('.qty-editor-minus');
                        var plus = row.querySelector('.qty-editor-plus');
                        var removeBtn = row.querySelector('.qty-editor-remove');
                        var spinner = row.querySelector('.qty-editor-spinner');

                        function updateQty(newQty) {
                                newQty = Math.max(0, parseInt(newQty, 10) || 0);
                                input.value = newQty;
                                input.disabled = true;
                                minus.disabled = true;
                                plus.disabled = true;
                                if (removeBtn) removeBtn.disabled = true;
                                spinner.hidden = false;

                                storeApiFetch('/wc/store/v1/cart/update-item', {
                                        method: 'POST',
                                        headers: { 'Nonce': storeNonce },
                                        body: JSON.stringify({ key: key, quantity: newQty })
                                }).then(function () {
                                        window.location.reload();
                                }).catch(function () {
                                        input.disabled = false;
                                        minus.disabled = false;
                                        plus.disabled = false;
                                        if (removeBtn) removeBtn.disabled = false;
                                        spinner.hidden = true;
                                        alert('<?php echo esc_js( __( 'Could not update quantity. Please try again.', 'clearance-deal' ) ); ?>');
                                });
                        }

                        minus.addEventListener('click', function () { updateQty(parseInt(input.value, 10) - 1); });
                        plus.addEventListener('click', function () { updateQty(parseInt(input.value, 10) + 1); });
                        input.addEventListener('change', function () { updateQty(input.value); });
                        if (removeBtn) {
                                removeBtn.addEventListener('click', function () {
                                        if (confirm('<?php echo esc_js( __( 'Remove this item from your order?', 'clearance-deal' ) ); ?>')) {
                                                updateQty(0);
                                        }
                                });
                        }
                });
        })();
        </script>
        <?php
}

/**
 * Every "Add to cart" action site-wide skips the cart page and goes
 * straight to checkout (site works like a single "Buy Now" flow).
 */
function clearance_deal_buy_now_redirect( $url ) {
        // Only set default to 'deposit' if no plan choice is already set.
        if ( empty( $_COOKIE['cpl_plan_choice'] ) && ( ! function_exists( 'WC' ) || ! WC()->session || ! WC()->session->get( 'cpl_plan_choice' ) ) ) {
                if ( function_exists( 'WC' ) && WC()->session ) {
                        WC()->session->set( 'cpl_plan_choice', 'deposit' );
                }
                wc_setcookie( 'cpl_plan_choice', 'deposit' );
        }
        return wc_get_checkout_url();
}
add_filter( 'woocommerce_add_to_cart_redirect', 'clearance_deal_buy_now_redirect' );

// ── Checkout UX Improvements ─────────────────────────────────────────────────

/**
 * 1. Single Name field (remove billing_last_name, rename first_name → Name, full-width)
 * 2. Phone mandatory, email optional
 * 4. Remove postcode (not used in Kenya)
 */
add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
	// Single full-name field.
	if ( isset( $fields['billing']['billing_first_name'] ) ) {
		$fields['billing']['billing_first_name']['label']       = __( 'Name', 'clearance-deal' );
		$fields['billing']['billing_first_name']['placeholder'] = __( 'Your full name', 'clearance-deal' );
		$fields['billing']['billing_first_name']['class']       = array( 'form-row-wide' );
	}
	unset( $fields['billing']['billing_last_name'] );

	// Phone required.
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['required'] = true;
		$fields['billing']['billing_phone']['class']    = array( 'form-row-wide' );
	}

	// Email optional.
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['required'] = false;
		$fields['billing']['billing_email']['label']   .= ' ' . __( '(optional)', 'clearance-deal' );
		$fields['billing']['billing_email']['class']    = array( 'form-row-wide' );
	}

	// Remove postcode.
	unset( $fields['billing']['billing_postcode'] );

	return $fields;
} );

// 7. Remove "Have a coupon? Click here" toggle on checkout.
add_action( 'init', function () {
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
} );

// 6. Remove "Continue shopping" button from the add-to-cart notice.
add_filter( 'wc_add_to_cart_message_html', function ( $message ) {
	return preg_replace( '/<a\b[^>]*\bwc-forward\b[^>]*>.*?<\/a>/s', '', $message );
}, 20 );

/**
 * 3, 5, 8. Checkout mobile/layout CSS.
 *   3 – full width (remove excess left/right padding on mobile)
 *   5 – truncate product name in Review Quantities panel (one line + ellipsis)
 *   8 – truncate product name in "Your order" review table (one line + ellipsis)
 */
add_action( 'wp_head', function () {
	if ( ! function_exists( 'is_checkout' ) || ( ! is_checkout() && ! is_wc_endpoint_url( 'order-pay' ) ) ) {
		return;
	}
	?>
	<style id="clearance-checkout-ux">
	/* 3 – full width, minimal side padding on mobile */
	@media (max-width: 768px) {
		.woocommerce-page .woocommerce,
		.woocommerce-checkout .woocommerce {
			padding-left: 8px !important;
			padding-right: 8px !important;
		}
		.wp-block-group,
		.entry-content,
		.site-main > .wp-block-group__inner-container {
			padding-left: 8px !important;
			padding-right: 8px !important;
		}
		.woocommerce-checkout-review-order-table,
		.woocommerce-checkout-review-order-table th,
		.woocommerce-checkout-review-order-table td {
			font-size: 0.93em;
		}
	}

	/* 5 – Review Quantities: product name one line, ellipsis */
	.qty-editor-item-name {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
		max-width: 160px;
		flex: 1 1 0;
		min-width: 0;
	}

	/* 8 – Your order table: product name one line, ellipsis */
	.woocommerce-checkout-review-order-table .product-name {
		max-width: 170px;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.shop_table.woocommerce-checkout-review-order-table tbody .product-name {
		max-width: 170px;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	</style>
	<?php
}, 99 );

// ── End Checkout UX Improvements ─────────────────────────────────────────────

// ── Floating WhatsApp Chat Button ─────────────────────────────────────────────

/**
 * Floating WhatsApp chat button shown on the homepage and all product pages.
 * Uses the store's WhatsApp number (same one used for order messages).
 */
add_action( 'wp_footer', function () {
	if ( ! is_front_page() && ! is_product() ) {
		return;
	}

	$store_number = clearance_deal_normalize_wa_phone(
		get_option( 'clearance_deal_store_whatsapp_number', '254751338975' )
	);

	if ( ! $store_number ) {
		return;
	}

	$wa_url = 'https://wa.me/' . $store_number . '?text=' . rawurlencode( 'Hi! I\'d like to know more about your deals.' );
	?>
	<style id="clearance-wa-float">
	.wa-float-btn {
		position: fixed;
		bottom: 22px;
		right: 20px;
		z-index: 9999;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 58px;
		height: 58px;
		background: #25D366;
		border-radius: 50%;
		box-shadow: 0 4px 16px rgba(37,211,102,0.45), 0 2px 6px rgba(0,0,0,0.18);
		text-decoration: none;
		transition: transform 0.2s ease, box-shadow 0.2s ease;
		animation: wa-pulse 2.4s ease-in-out infinite;
	}
	.wa-float-btn:hover,
	.wa-float-btn:focus {
		transform: scale(1.1);
		box-shadow: 0 6px 24px rgba(37,211,102,0.55), 0 3px 10px rgba(0,0,0,0.22);
		animation: none;
		outline: none;
	}
	.wa-float-btn .fa-whatsapp {
		color: #fff;
		font-size: 32px;
		line-height: 1;
	}
	/* Ripple pulse */
	.wa-float-btn::before {
		content: '';
		position: absolute;
		inset: 0;
		border-radius: 50%;
		background: rgba(37,211,102,0.4);
		animation: wa-ripple 2.4s ease-out infinite;
		pointer-events: none;
	}
	@keyframes wa-ripple {
		0%   { transform: scale(1);   opacity: 0.7; }
		70%  { transform: scale(1.7); opacity: 0;   }
		100% { transform: scale(1.7); opacity: 0;   }
	}
	@keyframes wa-pulse {
		0%, 100% { transform: scale(1);    }
		50%       { transform: scale(1.05); }
	}
	/* Tooltip label */
	.wa-float-label {
		position: fixed;
		bottom: 34px;
		right: 88px;
		z-index: 9998;
		background: #fff;
		color: #1a1a1a;
		font-size: 13px;
		font-weight: 600;
		padding: 6px 12px;
		border-radius: 20px;
		box-shadow: 0 2px 10px rgba(0,0,0,0.15);
		white-space: nowrap;
		opacity: 0;
		transform: translateX(8px);
		transition: opacity 0.25s, transform 0.25s;
		pointer-events: none;
	}
	.wa-float-btn:hover ~ .wa-float-label,
	.wa-float-btn:focus ~ .wa-float-label {
		opacity: 1;
		transform: translateX(0);
	}
	/* On very small screens make it slightly smaller */
	@media (max-width: 400px) {
		.wa-float-btn { width: 52px; height: 52px; bottom: 16px; right: 14px; }
		.wa-float-btn .fa-whatsapp { font-size: 28px; }
		.wa-float-label { bottom: 26px; right: 74px; font-size: 12px; }
	}
	</style>

	<a href="<?php echo esc_url( $wa_url ); ?>"
	   class="wa-float-btn"
	   target="_blank"
	   rel="noopener noreferrer"
	   aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'clearance-deal' ); ?>">
		<i class="fa-brands fa-whatsapp"></i>
	</a>
	<span class="wa-float-label"><?php esc_html_e( 'Chat with us', 'clearance-deal' ); ?></span>
	<?php
} );

// ── End Floating WhatsApp Chat Button ─────────────────────────────────────────

/**
 * Render the "We Have Something For Everyone" featured products band.
 *
 * Pulls up to 4 WooCommerce "Featured" products. Out-of-stock items show
 * a disabled "Sold Out" state instead of a working Buy Now button.
 */
function clearance_deal_featured_products_section() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	$featured_query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				),
			),
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_stock_status',
					'value' => 'instock',
				),
			),
		)
	);

	if ( ! $featured_query->have_posts() ) {
		return;
	}
	?>
	<section class="featured-band">
		<div class="featured-band-container">

			<div class="featured-band-grid">
				<?php
				while ( $featured_query->have_posts() ) :
					$featured_query->the_post();
					$product = wc_get_product( get_the_ID() );

					if ( ! $product ) {
						continue;
					}

					$in_stock       = $product->is_in_stock();
					$discount_pct   = 0;
					$urgency_label  = get_post_meta( get_the_ID(), 'deal_urgency_label', true );

					if ( $product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price() ) {
						$discount_pct = clearance_deal_calculate_discount( $product->get_regular_price(), $product->get_sale_price() );
					}

					// Variable products get a JS variation selector; simple products go straight to add-to-cart.
					$data_type_attr = '';
					$data_vars_attr = '';
					if ( $product->is_type( 'variable' ) ) {
						$data_type_attr = 'data-product-type="variable"';
						$vars_data      = [];
						foreach ( $product->get_available_variations() as $var_data ) {
							$variation = wc_get_product( $var_data['variation_id'] );
							if ( ! $variation || ! $variation->is_purchasable() ) {
								continue;
							}
							$label_parts = [];
							foreach ( $variation->get_variation_attributes() as $attr_name => $attr_value ) {
								$label         = wc_attribute_label( str_replace( 'attribute_', '', $attr_name ) );
								$label_parts[] = $label . ': ' . ucfirst( $attr_value );
							}
							$v_price    = (float) ( $variation->get_sale_price() ?: $variation->get_price() );
							$vars_data[] = [
								'id'    => (int) $var_data['variation_id'],
								'label' => implode( ' / ', $label_parts ) ?: ( 'Option ' . ( count( $vars_data ) + 1 ) ),
								'price' => $v_price,
							];
						}
						if ( ! empty( $vars_data ) ) {
							$data_vars_attr = 'data-variations="' . esc_attr( wp_json_encode( $vars_data ) ) . '"';
						}
						$buy_now_url = get_permalink( $product->get_id() );
					} else {
						$data_type_attr = 'data-product-type="simple"';
						$buy_now_url    = add_query_arg( 'add-to-cart', $product->get_id(), home_url( '/' ) );
					}
					?>
					<div class="deal-card">
						<div class="deal-media-wrapper" style="position: relative; overflow: hidden;">
							<?php clearance_deal_render_featured_media( $product ); ?>

							<?php if ( $discount_pct > 0 ) : ?>
								<div class="badge-discount"><?php echo esc_html( $discount_pct ); ?>% OFF</div>
							<?php endif; ?>
							<?php if ( ! $in_stock ) : ?>
								<div class="badge-clearance"><?php esc_html_e( 'Sold Out', 'clearance-deal' ); ?></div>
							<?php endif; ?>
						</div>

						<span class="bnpl-badge">Buy Now Pay Later</span>

						<div class="deal-details">
							<h3 class="deal-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

							<?php if ( $urgency_label ) : ?>
								<p class="deal-urgency-label"><?php echo esc_html( $urgency_label ); ?></p>
							<?php endif; ?>

							<?php if ( $product->get_short_description() ) : ?>
								<p class="deal-short-description"><?php echo wp_kses_post( $product->get_short_description() ); ?></p>
							<?php endif; ?>

							<div class="deal-pricing">
								<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php if ( $discount_pct > 0 ) : ?>
									<span class="deal-savings"><?php
										/* translators: %s: discount percentage */
										echo esc_html( sprintf( __( 'Save %s%%', 'clearance-deal' ), $discount_pct ) );
									?></span>
								<?php endif; ?>
							</div>

							<?php if ( $in_stock ) : ?>
								<a href="<?php echo esc_url( $buy_now_url ); ?>" class="btn-get-deal" <?php echo $data_type_attr; // phpcs:ignore ?> <?php echo $data_vars_attr; // phpcs:ignore ?>>
								<?php esc_html_e( 'Buy Now', 'clearance-deal' ); ?>
							</a>
							<?php else : ?>
								<span class="btn-get-deal is-disabled"><?php esc_html_e( 'Sold Out', 'clearance-deal' ); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Render a product's media for the featured band: an auto-looping image
 * carousel (with prev/next arrows) built from the featured image + gallery,
 * or the product's video if one is set (videos don't get a carousel).
 */
function clearance_deal_render_featured_media( $product ) {
	$post_id    = $product->get_id();
	$video_url  = get_post_meta( $post_id, 'deal_video_url', true );
	$video_file = get_post_meta( $post_id, 'deal_video_file', true );

	if ( $video_url || $video_file ) {
		clearance_deal_render_media( $post_id, 'medium_large' );
		return;
	}

	$image_ids = array();
	if ( $product->get_image_id() ) {
		$image_ids[] = $product->get_image_id();
	}
	$image_ids = array_unique( array_filter( array_merge( $image_ids, $product->get_gallery_image_ids() ) ) );

	if ( empty( $image_ids ) ) {
		?>
		<div class="deal-image-wrapper">
			<?php echo wc_placeholder_img( 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php
		return;
	}
	?>
	<div class="deal-image-wrapper deal-carousel" data-autoplay="4000">
		<?php foreach ( array_values( $image_ids ) as $i => $image_id ) : ?>
			<img
				src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'medium_large' ) ); ?>"
				alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"
				loading="<?php echo 0 === $i ? 'eager' : 'lazy'; ?>"
				class="deal-carousel-slide<?php echo 0 === $i ? ' is-active' : ''; ?>">
		<?php endforeach; ?>

		<?php if ( count( $image_ids ) > 1 ) : ?>
			<button type="button" class="deal-carousel-arrow deal-carousel-prev" aria-label="<?php esc_attr_e( 'Previous image', 'clearance-deal' ); ?>"><i class="fa-solid fa-chevron-left"></i></button>
			<button type="button" class="deal-carousel-arrow deal-carousel-next" aria-label="<?php esc_attr_e( 'Next image', 'clearance-deal' ); ?>"><i class="fa-solid fa-chevron-right"></i></button>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Reject video uploads over 50MB (site-wide) -- comfortably fits a
 * downloaded Instagram reel -- independent of the general PHP
 * upload_max_filesize which is raised higher for headroom.
 */
function clearance_deal_limit_video_upload_size( $file ) {
	$max_bytes = 50 * 1024 * 1024;

	if ( isset( $file['type'] ) && 'video/mp4' === $file['type'] && $file['size'] > $max_bytes ) {
		$file['error'] = __( 'Video files must be 50MB or smaller.', 'clearance-deal' );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'clearance_deal_limit_video_upload_size' );

/**
 * Add "Instagram / Facebook Video URL" and "Upload MP4" fields to the
 * Product Data > General tab, so a product can show a video instead of
 * its image everywhere on the site (shop loop, single product, featured band).
 */
function clearance_deal_product_video_fields() {
	global $post;

	echo '<div class="options_group">';

	woocommerce_wp_text_input(
		array(
			'id'          => 'deal_video_url',
			'label'       => __( 'Instagram / Facebook Video URL', 'clearance-deal' ),
			'placeholder' => 'https://www.instagram.com/reel/... or https://www.facebook.com/.../videos/...',
			'desc_tip'    => true,
			'description' => __( 'If set, this video replaces the product image everywhere on the site.', 'clearance-deal' ),
		)
	);

	$video_file = get_post_meta( $post->ID, 'deal_video_file', true );
	?>
	<p class="form-field">
		<label for="deal_video_file"><?php esc_html_e( 'Or Upload MP4 Video (max 50MB)', 'clearance-deal' ); ?></label>
		<input type="text" class="short" name="deal_video_file" id="deal_video_file" value="<?php echo esc_attr( $video_file ); ?>" readonly style="width:50%; cursor:default; background:#f7f7f7;">
		<button type="button" class="button" id="deal_video_file_button"><?php esc_html_e( 'Choose Video', 'clearance-deal' ); ?></button>
		<button type="button" class="button" id="deal_video_file_clear"><?php esc_html_e( 'Remove', 'clearance-deal' ); ?></button>
	</p>
	<?php
	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'clearance_deal_product_video_fields' );

/**
 * Save the video fields added above.
 */
function clearance_deal_save_product_video_fields( $post_id ) {
	if ( isset( $_POST['deal_video_url'] ) ) {
		update_post_meta( $post_id, 'deal_video_url', esc_url_raw( wp_unslash( $_POST['deal_video_url'] ) ) );
	}
	if ( isset( $_POST['deal_video_file'] ) ) {
		update_post_meta( $post_id, 'deal_video_file', esc_url_raw( wp_unslash( $_POST['deal_video_file'] ) ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'clearance_deal_save_product_video_fields' );

/**
 * Media-uploader JS for the "Choose Video" button above, restricted to
 * the video library and warning client-side over the 5MB cap (the real
 * enforcement is server-side via clearance_deal_limit_video_upload_size()).
 */
function clearance_deal_admin_video_uploader_script( $hook ) {
	global $post_type;

	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || 'product' !== $post_type ) {
		return;
	}

	wp_enqueue_media();

	$js = <<<JS
jQuery(function ($) {
	var frame;
	$('#deal_video_file_button').on('click', function (e) {
		e.preventDefault();
		if (frame) {
			frame.open();
			return;
		}
		frame = wp.media({
			title: 'Select or Upload Video',
			library: { type: 'video' },
			button: { text: 'Use this video' },
			multiple: false
		});
		frame.on('select', function () {
			var attachment = frame.state().get('selection').first().toJSON();
			var maxBytes = 50 * 1024 * 1024;
			if (attachment.filesizeInBytes && attachment.filesizeInBytes > maxBytes) {
				alert('That video is ' + (attachment.filesizeInBytes / (1024 * 1024)).toFixed(1) + 'MB. Please choose a file 50MB or smaller.');
				return;
			}
			$('#deal_video_file').val(attachment.url);
		});
		frame.open();
	});
	$('#deal_video_file_clear').on('click', function (e) {
		e.preventDefault();
		$('#deal_video_file').val('');
	});
});
JS;

	wp_add_inline_script( 'media-editor', $js );
}
add_action( 'admin_enqueue_scripts', 'clearance_deal_admin_video_uploader_script' );

/**
 * Replace the shop-loop thumbnail with clearance_deal_render_media() so a
 * product's Instagram/Facebook/MP4 video shows instead of its image.
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
function clearance_deal_loop_product_media() {
	global $product;

	if ( $product ) {
		clearance_deal_render_media( $product->get_id(), 'woocommerce_thumbnail' );
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'clearance_deal_loop_product_media', 10 );

/**
 * Replace the single-product gallery with clearance_deal_render_media() only
 * when a video is actually set; otherwise keep WooCommerce's normal gallery
 * (zoom/lightbox/slider) for products that just use a plain image.
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
function clearance_deal_single_product_media() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$has_video = get_post_meta( $product->get_id(), 'deal_video_url', true ) || get_post_meta( $product->get_id(), 'deal_video_file', true );

	if ( $has_video ) {
		echo '<div class="images deal-has-video-gallery">';
		clearance_deal_render_media( $product->get_id(), 'large' );

		$image_ids = array();
		if ( $product->get_image_id() ) {
			$image_ids[] = $product->get_image_id();
		}
		$image_ids = array_unique( array_filter( array_merge( $image_ids, $product->get_gallery_image_ids() ) ) );
		if ( ! empty( $image_ids ) ) {
			echo '<div class="deal-video-gallery-thumbnails" style="display:flex; gap:10px; margin-top:14px; flex-wrap:wrap;">';
			foreach ( $image_ids as $img_id ) {
				$full_url  = wp_get_attachment_image_url( $img_id, 'full' );
				$thumb_url = wp_get_attachment_image_url( $img_id, 'woocommerce_thumbnail' );
				echo '<a href="' . esc_url( $full_url ) . '" class="deal-video-gallery-thumb" style="flex:0 0 calc(25% - 8px); border-radius:8px; overflow:hidden; border:1px solid #e2e8f0; display:block;" target="_blank">';
				echo '<img src="' . esc_url( $thumb_url ) . '" alt="' . esc_attr( get_the_title( $product->get_id() ) ) . '" style="width:100%; height:auto; display:block; object-fit:cover; aspect-ratio:1;">';
				echo '</a>';
			}
			echo '</div>';
		}
		echo '</div>';
	} else {
		woocommerce_show_product_images();
	}
}
add_action( 'woocommerce_before_single_product_summary', 'clearance_deal_single_product_media', 20 );

/**
 * Helper function to calculate and display the discount percentage.
 *
 * If original price and current price are provided, it calculates the saving percentage.
 */
function clearance_deal_calculate_discount( $original_price, $current_price ) {
	if ( ! $original_price || ! $current_price ) {
		return 0;
	}

	$original = (float) filter_var( $original_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	$current  = (float) filter_var( $current_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

	if ( $original <= 0 || $current >= $original ) {
		return 0;
	}

	$discount = ( ( $original - $current ) / $original ) * 100;
	return round( $discount );
}

/**
 * Get Instagram Reel embed URL from standard URL.
 */
function clearance_deal_get_instagram_embed_url( $url ) {
	if ( empty( $url ) ) {
		return '';
	}

	// Match standard Instagram reel or post patterns
	// e.g., https://www.instagram.com/reel/C8X_z9PJ8fH/ or https://www.instagram.com/p/C8X_z9PJ8fH/
	if ( preg_match( '/instagram\.com\/(?:p|reel)\/([A-Za-z0-9_-]+)/i', $url, $matches ) ) {
		$shortcode = $matches[1];
		return 'https://www.instagram.com/reel/' . $shortcode . '/embed/';
	}

	return '';
}

/**
 * Get Facebook video embed URL from a standard Facebook video/watch/reel URL.
 */
function clearance_deal_get_facebook_embed_url( $url ) {
	if ( empty( $url ) || false === strpos( $url, 'facebook.com' ) ) {
		return '';
	}

	return 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode( $url ) . '&show_text=false';
}

/**
 * Render the media container (Instagram Reel, Facebook video, uploaded MP4,
 * or a fallback image) for a post or WooCommerce product.
 *
 * @param int    $post_id    The post/product ID.
 * @param string $image_size Fallback image size when no video is set.

 */
function clearance_deal_render_media( $post_id, $image_size = 'medium_large' ) {
	$video_url  = get_post_meta( $post_id, 'deal_video_url', true );
	$video_file = get_post_meta( $post_id, 'deal_video_file', true );

	$instagram_embed = clearance_deal_get_instagram_embed_url( $video_url );
	$facebook_embed  = clearance_deal_get_facebook_embed_url( $video_url );

	if ( ! empty( $instagram_embed ) ) {
		// Output Instagram Reel iframe
		?>
		<div class="deal-media-container deal-media-video has-instagram">
			<iframe
				class="deal-instagram-iframe"
				src="<?php echo esc_url( $instagram_embed ); ?>"
				frameborder="0"
				scrolling="no"
				allowtransparency="true"
				allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
				loading="lazy">
			</iframe>
		</div>
		<?php
	} elseif ( ! empty( $facebook_embed ) ) {
		// Output Facebook video iframe
		?>
		<div class="deal-media-container deal-media-video has-facebook">
			<iframe
				class="deal-facebook-iframe"
				src="<?php echo esc_url( $facebook_embed ); ?>"
				frameborder="0"
				scrolling="no"
				allowtransparency="true"
				allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
				loading="lazy">
			</iframe>
		</div>
		<?php
	} elseif ( ! empty( $video_file ) ) {
		// Output HTML5 Video
		?>
		<div class="deal-media-container deal-media-video has-local-video">
			<video class="deal-local-video" controls autoplay muted playsinline preload="auto">
				<source src="<?php echo esc_url( $video_file ); ?>" type="video/mp4">
				<?php esc_html_e( 'Your browser does not support the video tag.', 'clearance-deal' ); ?>
			</video>
		</div>
		<?php
	} elseif ( function_exists( 'wc_get_product' ) && 'product' === get_post_type( $post_id ) && ( $product = wc_get_product( $post_id ) ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition
		// WooCommerce product fallback image
		?>
		<div class="deal-image-wrapper">
			<?php echo $product->get_image( $image_size ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php
	} else {
		// Standard Post Thumbnail fallback
		?>
		<div class="deal-image-wrapper">
			<?php if ( has_post_thumbnail( $post_id ) ) : ?>
				<?php echo get_the_post_thumbnail( $post_id, $image_size ); ?>
			<?php else : ?>
				<img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
			<?php endif; ?>
		</div>
		<?php
	}
}

// Hide stock quantity everywhere — never show "1000 in stock" or any number.
add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
add_filter( 'woocommerce_stock_html', '__return_empty_string', 10, 3 );


// ── Variable Product Price Display ───────────────────────────────────────────

/**
 * Replace the default "KES 2,800 – KES 11,200" range on variable products
 * with a "WAS / NOW" format using the lowest-priced variation.
 * WooCommerce's built-in JS then swaps in the selected variation's own
 * regular/sale prices once the customer picks a bundle option.
 */
add_filter( 'woocommerce_variable_price_html', function ( $price, $product ) {
	$prices = $product->get_variation_prices( true );
	if ( empty( $prices['price'] ) ) {
		return $price;
	}
	// First entry = lowest-priced variation.
	$min_id = key( $prices['price'] );
	if ( ! $min_id ) {
		return $price;
	}
	$variation = wc_get_product( $min_id );
	if ( ! $variation ) {
		return $price;
	}
	$regular = $variation->get_regular_price();
	$sale    = $variation->get_sale_price();
	if ( ! $regular || ! $sale ) {
		return $price;
	}
	return '<del aria-hidden="true">' . wc_price( $regular ) . '</del> <ins>' . wc_price( $sale ) . '</ins>';
}, 10, 2 );

// ── End Variable Product Price Display ───────────────────────────────────────

/**
 * Synchronize the single product top price with the selected variation.
 * 1. Updates top price dynamically to the selected variation's price (WAS / NOW).
 * 2. Hides the duplicate .woocommerce-variation-price above the Buy Now button
 *    so customers do not see two conflicting or redundant prices.
 * 3. Restores default price when selection is cleared.
 */
add_action( 'wp_footer', function () {
	if ( ! is_product() ) {
		return;
	}
	?>
	<script>
	jQuery(function($) {
		var $price = $('.summary.entry-summary p.price, .summary.entry-summary .price').first();
		if (!$price.length) return;
		var defaultPriceHtml = $price.html();

		function updateToVariation(variation) {
			if (variation && variation.price_html && variation.price_html.trim() !== '') {
				$price.html(variation.price_html);
			}
		}

		$('.variations_form').on('show_variation', function(event, variation) {
			updateToVariation(variation);
		});

		$('.variations_form').on('hide_variation reset_data', function() {
			$price.html(defaultPriceHtml);
		});

		// Check if a variation is already active on load
		var initialVarId = parseInt($('input[name="variation_id"]').val(), 10);
		if (initialVarId > 0) {
			var variationData = $('.variations_form').data('product_variations');
			if (Array.isArray(variationData)) {
				var activeVar = variationData.find(function(v) { return v.variation_id === initialVarId; });
				if (activeVar) {
					updateToVariation(activeVar);
				}
			}
		}
	});
	</script>
	<style>
	/* Hide duplicate variation price above the Buy Now button to keep a single clean price */
	.single-product .woocommerce-variation-price {
		display: none !important;
	}
	</style>
	<?php
}, 99 );

// ── Buy Now Pay Later Badge ───────────────────────────────────────────────────

add_action( 'woocommerce_before_shop_loop_item_title', function () {
	echo '<span class="bnpl-badge">Buy Now Pay Later</span>';
}, 15 );

// Single product summary — above the add-to-cart button (priority 39, which is just before add-to-cart at 40).
add_action( 'woocommerce_single_product_summary', function () {
	echo '<span class="bnpl-badge bnpl-badge--single">Buy Now Pay Later</span>';
}, 39 );

add_action( 'wp_enqueue_scripts', function () {
	$css = '
.bnpl-badge {
	display: block;
	background-color: #16a34a;
	color: #ffffff;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: 0.06em;
	text-transform: uppercase;
	padding: 5px 12px;
	line-height: 1.4;
	text-align: center;
}
.bnpl-badge--single {
	margin: 0 0 10px;
	border-radius: 4px;
}';
	wp_add_inline_style( 'clearance-deal-style', $css );
} );

// ── End Buy Now Pay Later Badge ───────────────────────────────────────────────

// ── Buy Now Button + Checkout Redirect ───────────────────────────────────────

// Rename "Add to cart" → "Buy Now" on shop/archive loop.
add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $product ) {
	return __( 'Buy Now', 'clearance-deal' );
}, 10, 2 );

// Rename on single product page.
add_filter( 'woocommerce_product_single_add_to_cart_text', function ( $text, $product ) {
	return __( 'Buy Now', 'clearance-deal' );
}, 10, 2 );

// ── End Buy Now Button + Checkout Redirect ────────────────────────────────────

// ── M-Pesa Trust Badge ────────────────────────────────────────────────────────

// Show M-Pesa logo + payment note on single product pages, below Add to Cart.
add_action( 'woocommerce_single_product_summary', function () {
	$logo_url = esc_url( get_template_directory_uri() . '/assets/images/mpesa-logo.svg' );
	echo '<div class="mpesa-trust-badge">'
		. '<img src="' . $logo_url . '" alt="M-Pesa" class="mpesa-trust-logo">'
		. '<span>' . esc_html__( 'Pay securely via M-Pesa', 'clearance-deal' ) . '</span>'
		. '</div>';
}, 35 );

// ── End M-Pesa Trust Badge ────────────────────────────────────────────────────

// ── SEO & AEO (Answer Engine Optimization) Engine ─────────────────────────
require_once get_template_directory() . '/inc/seo-aeo.php';



// ── Force Cart to Checkout Redirect ──────────────────────────────────────────
add_filter( "woocommerce_get_cart_url", function( $url ) {
    if ( function_exists( "wc_get_checkout_url" ) ) {
        return wc_get_checkout_url();
    }
    return $url;
} );

add_action( "template_redirect", function() {
    if ( function_exists( "is_cart" ) && is_cart() ) {
        wp_safe_redirect( wc_get_checkout_url() );
        exit;
    }
} );

// ── Hair Consultation Booking WhatsApp Flow ───────────────────────────────
add_action( 'woocommerce_thankyou', function( $order_id ) {
    if ( ! $order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }
    $has_consultation = false;
    foreach ( $order->get_items() as $item ) {
        if ( $item->get_product_id() == 250 || stripos( $item->get_name(), 'Consultation' ) !== false ) {
            $has_consultation = true;
            break;
        }
    }
    if ( ! $has_consultation ) {
        return;
    }
    $store_number = clearance_deal_normalize_wa_phone( get_option( 'clearance_deal_store_whatsapp_number', '254751338975' ) );
    if ( ! $store_number ) {
        return;
    }
    $client_name = $order->get_formatted_billing_full_name();
    $phone = $order->get_billing_phone();
    $msg = "Hi Clearance Deal Kenya! I have paid 100% (KSh 1,000) for the Certified Hair Consultation Session.\n\nOrder #" . $order->get_order_number() . "\nName: " . $client_name . "\nPhone: " . $phone . "\nStatus: Paid (100% Fee)\n\nPlease share available consultation time slots. Thank you!";
    $wa_url = 'https://wa.me/' . $store_number . '?text=' . rawurlencode( $msg );
    ?>
    <div style="background:#fff0f5; border:2px solid #e11d48; border-radius:12px; padding:24px; margin:24px 0; text-align:center;">
        <h3 style="color:#e11d48; font-size:20px; font-weight:800; margin-bottom:8px;">Consultation Fee Received (100% Paid)</h3>
        <p style="font-size:15px; color:#334155; margin-bottom:18px;">Thank you <?php echo esc_html( $client_name ); ?>! Click the button below to message our styling team on WhatsApp with your booking details so we can assign your consultation slot.</p>
        <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" class="btn-get-deal" style="display:inline-block; max-width:360px; padding:14px 24px; font-size:16px; font-weight:700; background:#25d366; color:#fff;">
            <i class="fa-brands fa-whatsapp" style="margin-right:8px;"></i> Connect on WhatsApp Now
        </a>
    </div>
    <script>
    setTimeout(function() {
        window.open('<?php echo esc_url( $wa_url ); ?>', '_blank');
    }, 1500);
    </script>
    <?php
}, 5 );

// ── Job Application CV Upload AJAX Handler ──────────────────────────────────
add_action( 'wp_ajax_cd_upload_job_cv', 'cd_handle_job_cv_upload' );
add_action( 'wp_ajax_nopriv_cd_upload_job_cv', 'cd_handle_job_cv_upload' );
function cd_handle_job_cv_upload() {
    check_ajax_referer( 'cd_job_application_nonce', 'security' );

    $full_name  = isset( $_POST['applicant_name'] ) ? sanitize_text_field( wp_unslash( $_POST['applicant_name'] ) ) : '';
    $phone      = isset( $_POST['applicant_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['applicant_phone'] ) ) : '';
    $job_title  = isset( $_POST['job_title'] ) ? sanitize_text_field( wp_unslash( $_POST['job_title'] ) ) : '';
    $job_ref    = isset( $_POST['job_ref'] ) ? sanitize_text_field( wp_unslash( $_POST['job_ref'] ) ) : '';
    $notes      = isset( $_POST['applicant_notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['applicant_notes'] ) ) : '';

    if ( empty( $full_name ) || empty( $phone ) || empty( $job_ref ) ) {
        wp_send_json_error( array( 'message' => 'Please fill in your name, phone number, and selected job.' ) );
    }

    if ( empty( $_FILES['cv_file']['name'] ) ) {
        wp_send_json_error( array( 'message' => 'Please select and attach your CV (PDF or Word document).' ) );
    }

    $file = $_FILES['cv_file'];
    $allowed_extensions = array( 'pdf', 'doc', 'docx', 'rtf', 'txt' );
    $file_ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

    if ( ! in_array( $file_ext, $allowed_extensions, true ) ) {
        wp_send_json_error( array( 'message' => 'Invalid file format. Please upload your CV as a PDF or Word document.' ) );
    }

    if ( $file['size'] > 15 * 1024 * 1024 ) {
        wp_send_json_error( array( 'message' => 'File size exceeds 15MB. Please upload a smaller file.' ) );
    }

    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }

    $upload_overrides = array(
        'test_form' => false,
        'mimes'     => array(
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'rtf'  => 'application/rtf',
            'txt'  => 'text/plain',
        ),
    );

    $movefile = wp_handle_upload( $file, $upload_overrides );

    if ( ! $movefile || isset( $movefile['error'] ) ) {
        $error_msg = isset( $movefile['error'] ) ? $movefile['error'] : 'Failed to save uploaded CV.';
        wp_send_json_error( array( 'message' => $error_msg ) );
    }

    $cv_url = $movefile['url'];

    // Build WhatsApp Message for +254 751 338 975
    $store_number = '254751338975';
    $wa_msg = "Hello Clearance Kenya HR Team!\n\n" .
              "I am submitting my job application via clearance.co.ke/jobs:\n\n" .
              "• Position: " . $job_title . "\n" .
              "• Job Reference: " . $job_ref . "\n" .
              "• Full Name: " . $full_name . "\n" .
              "• Phone: " . $phone . "\n";
    if ( ! empty( $notes ) ) {
        $wa_msg .= "• Notes: " . $notes . "\n";
    }
    $wa_msg .= "\n📄 CV Attached / Download Link:\n" . $cv_url . "\n\n" .
               "Looking forward to your response. Thank you!";

    $wa_url = 'https://wa.me/' . $store_number . '?text=' . rawurlencode( $wa_msg );

    wp_send_json_success( array(
        'message' => 'CV uploaded successfully!',
        'cv_url'  => $cv_url,
        'wa_url'  => $wa_url,
    ) );
}

// ── Ensure Clearance Pay Later Mini-Cart Styles Always Load ──────────────────
add_action( 'wp_enqueue_scripts', function() {
    if ( ! is_admin() && defined( 'CPL_PLUGIN_URL' ) && defined( 'CPL_VERSION' ) ) {
        wp_enqueue_style(
            'cpl-frontend',
            CPL_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            CPL_VERSION
        );
    }
}, 20 );

// Fail-safe styling: ensure mini-cart drawer never bleeds into page flow
add_action( 'wp_head', function() {
    ?>
    <style id="cpl-mini-cart-failsafe">
    #cpl-cart-icon-btn { display: none !important; }
    .cpl-mini-cart-panel:not(.cpl-mc-open) {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
    .cpl-mini-cart-backdrop:not(.cpl-mc-open) {
        display: none !important;
    }
    </style>
    <?php
}, 99 );

/**
 * Global image carousel script for deal cards (mobile and desktop).
 * Supports click/tap on left/right arrows and touch swiping on mobile devices.
 */
function clearance_deal_carousel_script() {
    ?>
    <script id="clearance-deal-carousel-js">
    (function () {
        var lastArrowTap = 0;

        function handleArrowAction(btn) {
            if (!btn) return;
            var now = Date.now();
            if (now - lastArrowTap < 120) return;
            lastArrowTap = now;

            var carousel = btn.closest('.deal-carousel');
            if (!carousel) return;

            var slides = carousel.querySelectorAll('.deal-carousel-slide');
            if (slides.length <= 1) return;

            var currentIndex = 0;
            for (var i = 0; i < slides.length; i++) {
                if (slides[i].classList.contains('is-active')) {
                    currentIndex = i;
                    break;
                }
            }

            slides[currentIndex].classList.remove('is-active');

            var isNext = btn.classList.contains('deal-carousel-next');
            var nextIndex = isNext
                ? (currentIndex + 1) % slides.length
                : (currentIndex - 1 + slides.length) % slides.length;

            var targetSlide = slides[nextIndex];
            if (targetSlide.getAttribute('loading') === 'lazy') {
                targetSlide.setAttribute('loading', 'eager');
            }
            targetSlide.classList.add('is-active');
        }

        // Capture phase click handler ensures buttons always respond even if cards or links try to capture events
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.deal-carousel-arrow');
            if (btn) {
                e.preventDefault();
                e.stopPropagation();
                handleArrowAction(btn);
            }
        }, true);

        // Touch handler for instant mobile response without delay
        document.addEventListener('touchend', function (e) {
            var btn = e.target.closest('.deal-carousel-arrow');
            if (btn) {
                e.preventDefault();
                e.stopPropagation();
                handleArrowAction(btn);
            }
        }, { passive: false, capture: true });

        // Swipe gesture support for mobile browsing
        function bindSwipeToCarousel(carousel) {
            if (!carousel || carousel.dataset.swipeInit === 'true') return;
            carousel.dataset.swipeInit = 'true';

            var startX = 0;
            var startY = 0;
            var startTime = 0;

            carousel.addEventListener('touchstart', function (e) {
                if (e.target.closest('.deal-carousel-arrow')) return;
                if (!e.changedTouches || !e.changedTouches[0]) return;
                startX = e.changedTouches[0].clientX;
                startY = e.changedTouches[0].clientY;
                startTime = Date.now();
            }, { passive: true });

            carousel.addEventListener('touchend', function (e) {
                if (e.target.closest('.deal-carousel-arrow')) return;
                if (!e.changedTouches || !e.changedTouches[0]) return;

                var endX = e.changedTouches[0].clientX;
                var endY = e.changedTouches[0].clientY;
                var diffX = endX - startX;
                var diffY = endY - startY;
                var elapsed = Date.now() - startTime;

                // Thresholds: horizontal swipe > 35px, more horizontal than vertical, within 600ms
                if (Math.abs(diffX) > 35 && Math.abs(diffX) > Math.abs(diffY) && elapsed < 600) {
                    var slides = carousel.querySelectorAll('.deal-carousel-slide');
                    if (slides.length <= 1) return;

                    var currentIndex = 0;
                    for (var i = 0; i < slides.length; i++) {
                        if (slides[i].classList.contains('is-active')) {
                            currentIndex = i;
                            break;
                        }
                    }

                    slides[currentIndex].classList.remove('is-active');

                    var nextIndex = diffX < 0
                        ? (currentIndex + 1) % slides.length
                        : (currentIndex - 1 + slides.length) % slides.length;

                    var targetSlide = slides[nextIndex];
                    if (targetSlide.getAttribute('loading') === 'lazy') {
                        targetSlide.setAttribute('loading', 'eager');
                    }
                    targetSlide.classList.add('is-active');
                }
            }, { passive: true });
        }

        function initSwipeGestures() {
            var carousels = document.querySelectorAll('.deal-carousel');
            carousels.forEach(bindSwipeToCarousel);
        }

        // Lazy-bind swipe on touchstart on document for any dynamically loaded or rendered cards
        document.addEventListener('touchstart', function (e) {
            var carousel = e.target.closest('.deal-carousel');
            if (carousel && carousel.dataset.swipeInit !== 'true') {
                bindSwipeToCarousel(carousel);
            }
        }, { passive: true });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSwipeGestures);
        } else {
            initSwipeGestures();
        }
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'clearance_deal_carousel_script', 30 );





require_once get_template_directory() . '/inc/seo-aeo.php';
