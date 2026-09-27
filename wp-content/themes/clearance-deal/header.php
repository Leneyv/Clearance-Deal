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
	.header-container{max-width:1200px;margin:0 auto;padding:.75rem 1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1rem}
	.site-logo{height:40px;width:auto}
	.hero{background:#fff;color:var(--secondary-color);border-bottom:1px solid var(--border-color)}
	.hero-container{max-width:900px;margin:0 auto;padding:3.5rem 1.5rem 0}
	.hero h1{font-size:clamp(1.75rem,5vw,2.75rem);font-weight:800;line-height:1.15;margin-bottom:1rem;color:#0f172a}
	.hero p{font-size:1rem;color:var(--text-muted);margin-bottom:1.5rem}
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

			<div class="header-actions">
				<a href="https://wa.me/254751338975" class="header-whatsapp" target="_blank" rel="noopener">
					<i class="fa-brands fa-whatsapp"></i>
					<span>WhatsApp Us</span>
				</a>
				<button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
					<span></span><span></span><span></span>
				</button>
			</div>
		</div>

		<!-- Mobile drawer -->
		<div class="mobile-nav-drawer" id="mobileNavDrawer" aria-hidden="true">
			<nav class="mobile-nav-links">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<a href="<?php echo esc_url( home_url( '/shop' ) ); ?>">Shop</a>
				<a href="<?php echo esc_url( home_url( '/#featured-deals' ) ); ?>">Featured Deals</a>
				<a href="<?php echo esc_url( home_url( '/#about' ) ); ?>">About Us</a>
			</nav>
		</div>
	</header><!-- #masthead -->

	<script>
	(function(){
		var btn = document.getElementById('navToggle');
		var drawer = document.getElementById('mobileNavDrawer');
		if(!btn||!drawer) return;
		btn.addEventListener('click', function(){
			var open = drawer.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open);
			drawer.setAttribute('aria-hidden', !open);
			document.body.classList.toggle('nav-open', open);
		});
		drawer.addEventListener('click', function(e){
			if(e.target.tagName==='A'){
				drawer.classList.remove('is-open');
				btn.setAttribute('aria-expanded','false');
				drawer.setAttribute('aria-hidden','true');
				document.body.classList.remove('nav-open');
			}
		});
	})();
	</script>

