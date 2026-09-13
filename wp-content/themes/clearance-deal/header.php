<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
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

			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => 'clearance_deal_fallback_menu',
					)
				);
				?>
			</nav><!-- #site-navigation -->
		</div>
	</header><!-- #masthead -->

	<?php
	// Navigation fallback if no menu is set
	if ( ! function_exists( 'clearance_deal_fallback_menu' ) ) {
		function clearance_deal_fallback_menu() {
			echo '<ul>';
			echo '<li class="current-menu-item"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'clearance-deal' ) . '</a></li>';
			echo '<li><a href="#featured-deals">' . esc_html__( 'Featured Deals', 'clearance-deal' ) . '</a></li>';
			echo '<li><a href="#stores">' . esc_html__( 'Stores', 'clearance-deal' ) . '</a></li>';
			echo '<li><a href="#about">' . esc_html__( 'About Us', 'clearance-deal' ) . '</a></li>';
			echo '</ul>';
		}
	}
	?>
