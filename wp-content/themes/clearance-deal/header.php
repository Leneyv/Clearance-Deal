<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<style>
	/* Critical above-fold CSS */
	*,::before,::after{box-sizing:border-box;margin:0;padding:0}
	:root{--primary-color:#ef4444;--secondary-color:#0f172a;--accent-color:#f59e0b;--background-color:#f8fafc;--card-background:#fff;--text-main:#334155;--text-muted:#64748b;--border-color:#e2e8f0;--font-sans:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;--radius-md:12px;--transition:all .2s cubic-bezier(.4,0,.2,1)}
	body{font-family:var(--font-sans);background-color:var(--background-color);color:var(--text-main);line-height:1.5;overflow-x:hidden;-webkit-font-smoothing:antialiased}
	img{max-width:100%;height:auto;display:block}
	a{color:inherit;text-decoration:none;transition:var(--transition)}
	.site-header{background:#fff;border-bottom:1px solid var(--border-color);position:sticky;top:0;z-index:200;box-shadow:0 1px 4px rgba(0,0,0,.06)}
	.header-container{max-width:1200px;margin:0 auto;padding:.75rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1.5rem}
	.site-logo{height:40px;width:auto}
	.hero{background:#fff;color:var(--secondary-color);border-bottom:1px solid var(--border-color)}
	.hero-container{max-width:900px;margin:0 auto;padding:3.5rem 1.5rem 0}
	.hero h1{font-size:clamp(1.75rem,5vw,2.75rem);font-weight:800;line-height:1.15;margin-bottom:1rem;color:#0f172a}
	.hero p{font-size:1rem;color:var(--text-muted);margin-bottom:1.5rem}

	/* Desktop Header Navigation */
	.desktop-nav{display:none;align-items:center;gap:1.5rem}
	@media (min-width:900px){.desktop-nav{display:flex}}
	.desktop-nav-link{font-size:.95rem;font-weight:700;color:#334155;text-decoration:none;padding:.35rem 0;transition:color .2s ease}
	.desktop-nav-link:hover{color:#ef4444}

	/* Navigation Toggle Button */
	.nav-toggle{display:flex;flex-direction:column;justify-content:center;align-items:center;gap:5px;width:40px;height:40px;background:#f8fafc;border:1.5px solid var(--border-color,#e2e8f0);border-radius:8px;cursor:pointer;padding:0;flex-shrink:0;transition:all .2s ease}
	.nav-toggle:hover{background:#f1f5f9;border-color:#cbd5e1}
	.nav-toggle span{display:block;width:20px;height:2px;background:#0f172a;border-radius:2px;transition:all .25s ease-in-out;pointer-events:none}
	.nav-toggle.is-active span:nth-child(1),.nav-toggle[aria-expanded="true"] span:nth-child(1){transform:translateY(7px) rotate(45deg)}
	.nav-toggle.is-active span:nth-child(2),.nav-toggle[aria-expanded="true"] span:nth-child(2){opacity:0}
	.nav-toggle.is-active span:nth-child(3),.nav-toggle[aria-expanded="true"] span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}

	/* Navigation Drawer (Mobile & Desktop) */
	.mobile-nav-drawer{display:none !important;position:absolute;top:100%;left:0;right:0;background:#fff;border-top:1px solid #e2e8f0;box-shadow:0 12px 28px rgba(0,0,0,.15);z-index:999;max-height:85vh;overflow-y:auto;-webkit-overflow-scrolling:touch}
	.mobile-nav-drawer.is-open{display:block !important}
	.mobile-nav-links{display:flex;flex-direction:column;padding:.5rem 0;max-width:1200px;margin:0 auto}
	.mobile-nav-group-title{padding:.75rem 1.5rem .35rem;font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;background:#f8fafc;border-bottom:1px solid #e2e8f0}
	.mobile-nav-links a{display:flex;align-items:center;gap:.75rem;padding:.85rem 1.5rem;font-size:.95rem;font-weight:600;color:#1e293b;border-bottom:1px solid #f1f5f9;text-decoration:none;transition:all .15s ease}
	.mobile-nav-links a i{width:22px;color:#64748b;font-size:1.05rem;text-align:center}
	.mobile-nav-links a:hover{background:#f8fafc;color:#ef4444}
	.mobile-nav-links a:hover i{color:#ef4444}
	.mobile-nav-links a.is-active{background:#fef2f2;color:#ef4444;font-weight:700}
	.mobile-nav-links a:last-child{border-bottom:none}
	.mobile-nav-links a.mobile-nav-whatsapp{color:#16a34a}
	.mobile-nav-links a.mobile-nav-whatsapp i{color:#16a34a}

	/* Critical Product Media Layout (prevents cached padding-top / black boxes) */
	.deal-card .deal-media-wrapper,
	.deal-media-wrapper {
		position: relative !important;
		width: 100% !important;
		overflow: hidden !important;
		background: transparent !important;
		background-color: transparent !important;
		padding-top: 0 !important;
		padding-bottom: 0 !important;
		height: auto !important;
		min-height: 0 !important;
	}
	.deal-card .deal-image-wrapper {
		position: relative !important;
		width: 100% !important;
		padding-top: 100% !important;
		overflow: hidden !important;
		background-color: #f8fafc !important;
	}
	.deal-card .deal-image-wrapper img {
		position: absolute !important;
		top: 0 !important;
		left: 0 !important;
		width: 100% !important;
		height: 100% !important;
		object-fit: contain !important;
		padding: 0.35rem !important;
	}
	.deal-card .deal-media-container,
	.deal-card .deal-media-container.has-local-video,
	.deal-card .deal-media-container.has-instagram,
	.deal-card .deal-media-container.has-facebook {
		position: relative !important;
		width: 100% !important;
		padding-top: 100% !important;
		overflow: hidden !important;
		background-color: #000 !important;
	}
	.deal-card .deal-media-container video,
	.deal-card .deal-local-video {
		position: absolute !important;
		top: 0 !important;
		left: 0 !important;
		width: 100% !important;
		height: 100% !important;
		object-fit: cover !important;
	}
	.deal-short-description {
		display: none !important;
	}

	</style>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
		<div class="header-container">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo-link">
						<img
							src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>"
							alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
							class="site-logo"
							width="340"
							height="64">
					</a>
					<?php
				}
				?>
			</div><!-- .site-branding -->

			<!-- Desktop Primary Nav -->
			<nav class="desktop-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'clearance-deal' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="desktop-nav-link"><?php esc_html_e( 'Home', 'clearance-deal' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="desktop-nav-link"><?php esc_html_e( 'Shop All', 'clearance-deal' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/#deals-section' ) ); ?>" class="desktop-nav-link"><?php esc_html_e( 'Today\'s Deals', 'clearance-deal' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/#about' ) ); ?>" class="desktop-nav-link"><?php esc_html_e( 'About Us', 'clearance-deal' ); ?></a>
			</nav>

			<div class="header-actions">
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart" title="<?php esc_attr_e( 'View Cart', 'clearance-deal' ); ?>">
					<i class="fa-solid fa-cart-shopping"></i>
					<span class="header-cart-count"><?php echo ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0; ?></span>
				</a>
				<a href="https://wa.me/254751338975" class="header-whatsapp" target="_blank" rel="noopener">
					<i class="fa-brands fa-whatsapp"></i>
					<span>WhatsApp Us</span>
				</a>
				<button class="nav-toggle" id="navToggle" aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'clearance-deal' ); ?>" aria-expanded="false">
					<span></span><span></span><span></span>
				</button>
			</div>
		</div>



		<!-- Navigation Drawer (Mobile & Desktop) with All 9 Tabs -->
		<div class="mobile-nav-drawer" id="mobileNavDrawer" aria-hidden="true">
			<nav class="mobile-nav-links">
				<div class="mobile-nav-group-title"><?php esc_html_e( '🔥 Price Category Deals', 'clearance-deal' ); ?></div>
				<a href="<?php echo esc_url( home_url( '/#deals-section' ) ); ?>" class="mobile-nav-deal-tab is-active" data-cat="all">
					<i class="fa-solid fa-border-all"></i>
					<span><?php esc_html_e( 'All Deals', 'clearance-deal' ); ?></span>
				</a>
				<a href="<?php echo esc_url( home_url( '/#price-2' ) ); ?>" class="mobile-nav-deal-tab" data-cat="price-2">
					<i class="fa-solid fa-tags"></i>
					<span><?php esc_html_e( 'From KSh 11,000 to 15,000 Deals', 'clearance-deal' ); ?></span>
				</a>
				<a href="<?php echo esc_url( home_url( '/#price-1' ) ); ?>" class="mobile-nav-deal-tab" data-cat="price-1">
					<i class="fa-solid fa-tag"></i>
					<span><?php esc_html_e( 'From KSh 3,000 to 10,000 Deals', 'clearance-deal' ); ?></span>
				</a>
				<a href="<?php echo esc_url( home_url( '/#price-3' ) ); ?>" class="mobile-nav-deal-tab" data-cat="price-3">
					<i class="fa-solid fa-fire" style="color:#ea580c;"></i>
					<span><?php esc_html_e( 'From KSh 15,000 to 20,000 Deals', 'clearance-deal' ); ?></span>
				</a>
				<a href="<?php echo esc_url( home_url( '/#premium' ) ); ?>" class="mobile-nav-deal-tab" data-cat="premium">
					<i class="fa-solid fa-crown" style="color:#9333ea;"></i>
					<span><?php esc_html_e( 'Premium Deals', 'clearance-deal' ); ?></span>
				</a>

				<div class="mobile-nav-group-title"><?php esc_html_e( '🛍️ Shop by Product Category', 'clearance-deal' ); ?></div>
				<a href="<?php echo esc_url( home_url( '/#hair' ) ); ?>" class="mobile-nav-deal-tab" data-cat="hair">
					<i class="fa-solid fa-scissors" style="color:#0284c7;"></i>
					<span><?php esc_html_e( 'Shop by hair Deals', 'clearance-deal' ); ?></span>
				</a>
				<a href="<?php echo esc_url( home_url( '/#makeup' ) ); ?>" class="mobile-nav-deal-tab" data-cat="makeup">
					<i class="fa-solid fa-wand-magic-sparkles" style="color:#db2777;"></i>
					<span><?php esc_html_e( 'Shop by Makup Deals', 'clearance-deal' ); ?></span>
				</a>
				<a href="<?php echo esc_url( home_url( '/#wigs' ) ); ?>" class="mobile-nav-deal-tab" data-cat="wigs">
					<i class="fa-solid fa-feather" style="color:#d97706;"></i>
					<span><?php esc_html_e( 'Wigs', 'clearance-deal' ); ?></span>
				</a>
				<a href="<?php echo esc_url( home_url( '/#bundles' ) ); ?>" class="mobile-nav-deal-tab" data-cat="bundles">
					<i class="fa-solid fa-box-open" style="color:#059669;"></i>
					<span><?php esc_html_e( 'Bundles', 'clearance-deal' ); ?></span>
				</a>

				<div class="mobile-nav-group-title"><?php esc_html_e( '🛒 Quick Links', 'clearance-deal' ); ?></div>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					<i class="fa-solid fa-cart-shopping"></i>
					<span><?php esc_html_e( 'Shopping Cart / Checkout', 'clearance-deal' ); ?></span>
				</a>
				<a href="<?php echo esc_url( home_url( '/#about' ) ); ?>">
					<i class="fa-solid fa-shield-halved"></i>
					<span><?php esc_html_e( 'About Clearance Deal Kenya', 'clearance-deal' ); ?></span>
				</a>
				<a href="https://wa.me/254751338975" target="_blank" rel="noopener" class="mobile-nav-whatsapp">
					<i class="fa-brands fa-whatsapp"></i>
					<span><?php esc_html_e( 'WhatsApp Support (+254 751 338 975)', 'clearance-deal' ); ?></span>
				</a>
			</nav>
		</div>
	</header><!-- #masthead -->

	<script>
	(function () {
		function initNav() {
			var btn = document.getElementById('navToggle');
			var drawer = document.getElementById('mobileNavDrawer');
			if (!btn || !drawer) return;

			function toggleMenu(e) {
				if (e) {
					e.preventDefault();
					e.stopPropagation();
				}
				var isOpen = drawer.classList.toggle('is-open');
				btn.classList.toggle('is-active', isOpen);
				btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
				drawer.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
				document.body.classList.toggle('nav-open', isOpen);
			}

			function closeMenu() {
				drawer.classList.remove('is-open');
				btn.classList.remove('is-active');
				btn.setAttribute('aria-expanded', 'false');
				drawer.setAttribute('aria-hidden', 'true');
				document.body.classList.remove('nav-open');
			}

			btn.addEventListener('click', toggleMenu);

			// Intercept clicks on category tabs in header bar or drawer
			document.querySelectorAll('.cat-bar-item, .mobile-nav-deal-tab').forEach(function (tabLink) {
				tabLink.addEventListener('click', function (e) {
					var cat = tabLink.getAttribute('data-cat');
					if (!cat) return;

					// If on home page, filter and smooth-scroll without reloading
					var isHome = (window.location.pathname === '/' || window.location.pathname === '');
					if (isHome && typeof window.filterCategoryTab === 'function') {
						e.preventDefault();
						window.filterCategoryTab(cat);
						window.location.hash = (cat === 'all') ? 'deals-section' : cat;
						var target = document.getElementById(cat) || document.getElementById('deals-section');
						if (target) {
							var topPos = target.getBoundingClientRect().top + window.pageYOffset - 110;
							window.scrollTo({ top: topPos, behavior: 'smooth' });
						}
						closeMenu();
					} else {
						// On other pages, close drawer and let default link to /#hash proceed
						closeMenu();
					}
				});
			});

			drawer.addEventListener('click', function (e) {
				if (e.target.closest('a')) {
					closeMenu();
				}
			});

			document.addEventListener('click', function (e) {
				if (!drawer.contains(e.target) && !btn.contains(e.target)) {
					closeMenu();
				}
			});

			document.addEventListener('keydown', function (e) {
				if (e.key === 'Escape') {
					closeMenu();
				}
			});
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initNav);
		} else {
			initNav();
		}
	})();
	</script>

