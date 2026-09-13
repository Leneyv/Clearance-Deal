<?php
/**
 * Clearance Deal functions and definitions
 *
 * @package Clearance_Deal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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
	// Enqueue main stylesheet
	wp_enqueue_style( 'clearance-deal-style', get_stylesheet_uri(), array(), '1.0.0' );

	// Enqueue FontAwesome for icons (useful for stars, search, stores)
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
}
add_action( 'wp_enqueue_scripts', 'clearance_deal_scripts' );

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
		<div class="trust-badge">
			<i class="fa-solid fa-shield-halved"></i>
			<span><?php esc_html_e( 'Secure Payment', 'clearance-deal' ); ?></span>
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
		'desc'    => __( 'Customers are prompted to send their order details to this number on WhatsApp after checkout. Include country code, digits only (e.g. 254737535353).', 'clearance-deal' ),
		'id'      => 'clearance_deal_store_whatsapp_number',
		'type'    => 'text',
		'css'     => 'min-width:300px;',
		'default' => '254737535353',
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

	$store_number = clearance_deal_normalize_wa_phone( get_option( 'clearance_deal_store_whatsapp_number', '254737535353' ) );
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
					<div class="qty-editor-item-image"><?php echo $product->get_image( 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div class="qty-editor-item-name"><?php echo esc_html( $product->get_name() ); ?></div>
					<div class="qty-editor-item-controls">
						<button type="button" class="qty-editor-btn qty-editor-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'clearance-deal' ); ?>">&#8722;</button>
						<input type="number" class="qty-editor-input" min="1" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>">
						<button type="button" class="qty-editor-btn qty-editor-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'clearance-deal' ); ?>">&#43;</button>
					</div>
					<div class="qty-editor-spinner" hidden></div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="qty-editor-hint"><?php esc_html_e( 'Totals below update automatically after you change a quantity.', 'clearance-deal' ); ?></p>
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

		// Prime the Store API nonce before any update request needs it.
		storeApiFetch('/wc/store/v1/cart').catch(function () {});

		document.querySelectorAll('.qty-editor-item').forEach(function (row) {
			var key = row.getAttribute('data-cart-item-key');
			var input = row.querySelector('.qty-editor-input');
			var minus = row.querySelector('.qty-editor-minus');
			var plus = row.querySelector('.qty-editor-plus');
			var spinner = row.querySelector('.qty-editor-spinner');

			function updateQty(newQty) {
				newQty = Math.max(1, parseInt(newQty, 10) || 1);
				input.value = newQty;
				input.disabled = true;
				minus.disabled = true;
				plus.disabled = true;
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
					spinner.hidden = true;
					alert('<?php echo esc_js( __( 'Could not update quantity. Please try again.', 'clearance-deal' ) ); ?>');
				});
			}

			minus.addEventListener('click', function () { updateQty(parseInt(input.value, 10) - 1); });
			plus.addEventListener('click', function () { updateQty(parseInt(input.value, 10) + 1); });
			input.addEventListener('change', function () { updateQty(input.value); });
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
	return wc_get_checkout_url();
}
add_filter( 'woocommerce_add_to_cart_redirect', 'clearance_deal_buy_now_redirect' );

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
			'posts_per_page' => 4,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
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
			<h2 class="section-title featured-band-title"><?php esc_html_e( 'We Have Something For Everyone', 'clearance-deal' ); ?></h2>

			<div class="featured-band-grid">
				<?php
				while ( $featured_query->have_posts() ) :
					$featured_query->the_post();
					$product = wc_get_product( get_the_ID() );

					if ( ! $product ) {
						continue;
					}

					$in_stock     = $product->is_in_stock();
					$discount_pct = 0;

					if ( $product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price() ) {
						$discount_pct = clearance_deal_calculate_discount( $product->get_regular_price(), $product->get_sale_price() );
					}

					$buy_now_url = add_query_arg( 'add-to-cart', $product->get_id(), home_url( '/' ) );
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

						<div class="deal-details">
							<h3 class="deal-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

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
								<a href="<?php echo esc_url( $buy_now_url ); ?>" class="btn-get-deal btn-rolling">
										<span class="btn-rolling-track">
											<span class="btn-rolling-text is-limited"><?php esc_html_e( 'Limited Stock', 'clearance-deal' ); ?></span>
											<span class="btn-rolling-text is-buy"><?php esc_html_e( 'Buy Now', 'clearance-deal' ); ?></span>
										</span>
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
	<script>
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.deal-carousel').forEach(function (carousel) {
			var slides = carousel.querySelectorAll('.deal-carousel-slide');
			if (slides.length <= 1) {
				return;
			}

			var index = 0;
			var intervalTime = parseInt(carousel.getAttribute('data-autoplay'), 10) || 4000;
			var timer;

			function show(newIndex) {
				slides[index].classList.remove('is-active');
				index = (newIndex + slides.length) % slides.length;
				slides[index].classList.add('is-active');
			}

			function startAutoplay() {
				clearInterval(timer);
				timer = setInterval(function () { show(index + 1); }, intervalTime);
			}

			var prevBtn = carousel.querySelector('.deal-carousel-prev');
			var nextBtn = carousel.querySelector('.deal-carousel-next');

			if (prevBtn) {
				prevBtn.addEventListener('click', function (e) {
					e.preventDefault();
					show(index - 1);
					startAutoplay();
				});
			}
			if (nextBtn) {
				nextBtn.addEventListener('click', function (e) {
					e.preventDefault();
					show(index + 1);
					startAutoplay();
				});
			}

			startAutoplay();
		});
	});
	</script>
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
		echo '<div class="images">';
		clearance_deal_render_media( $product->get_id(), 'large' );
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

