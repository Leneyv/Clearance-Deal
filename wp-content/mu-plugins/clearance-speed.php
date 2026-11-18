<?php
/**
 * Plugin Name: Clearance Store Performance
 * Description: Scoped asset and media optimizations for the Clearance storefront.
 */
defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', function () {
    if (is_admin()) return;
    // This gateway separately enqueues its block adapter on the classic checkout.
    // Keep the adapter when a real checkout block is present, including nested blocks.
    if (function_exists('is_checkout') && is_checkout()) {
        $page = get_post(wc_get_page_id('checkout'));
        if ($page && has_shortcode($page->post_content, 'woocommerce_checkout') && !has_block('woocommerce/checkout', $page)) {
            wp_dequeue_script('pesapal-blocks-integration');
        }
    }
    $styles = wp_styles();
    $icons = get_stylesheet_directory() . '/assets/css/store-icons.css';
    if (isset($styles->registered['font-awesome']) && file_exists($icons)) {
        wp_add_inline_style('font-awesome', file_get_contents($icons));
    }
    wp_add_inline_style('clearance-deal-style', 'body.single-product .mpesa-trust-logo{max-width:90px!important;width:auto!important;height:28px!important;object-fit:contain}body.single-product .mpesa-trust-badge{flex-wrap:wrap}');
    $css = get_stylesheet_directory() . '/assets/css/store-speed.min.css';
    if (isset($styles->registered['clearance-deal-style']) && file_exists($css) && filemtime($css) >= filemtime(get_stylesheet_directory() . '/style.css')) {
        $styles->registered['clearance-deal-style']->src = get_stylesheet_directory_uri() . '/assets/css/store-speed.min.css';
        $styles->registered['clearance-deal-style']->ver = (string) filemtime($css);
    }
    $scripts = wp_scripts();
    // Touch screens use the existing tap-to-enlarge lightbox. Hover zoom otherwise
    // preloads every full-resolution gallery image even when no mouse is present.
    if (function_exists('is_product') && is_product() && wp_script_is('wc-single-product', 'enqueued')) {
        wp_add_inline_script('wc-single-product', 'if(window.matchMedia("(max-width: 768px), (hover: none), (pointer: coarse)").matches && typeof wc_single_product_params!=="undefined"){wc_single_product_params.zoom_enabled=false;}', 'before');
    }
    if (defined('CPL_PLUGIN_DIR') && isset($scripts->registered['cpl-cart-popup'])) {
        $source = CPL_PLUGIN_DIR . 'assets/js/cart-popup.js';
        $min = CPL_PLUGIN_DIR . 'assets/js/cart-popup.speed.min.js';
        if (file_exists($min) && filemtime($min) >= filemtime($source)) {
            $scripts->registered['cpl-cart-popup']->src = CPL_PLUGIN_URL . 'assets/js/cart-popup.speed.min.js';
            $scripts->registered['cpl-cart-popup']->ver = (string) filemtime($min);
        }
    }
}, 999);

// Keep the first product photo crawlable; fetch other card slides only when selected.
add_filter('wp_get_attachment_image_attributes', function ($attr) {
    $class = $attr['class'] ?? '';
    if (is_admin() || !str_contains($class, 'deal-carousel-slide') || str_contains($class, 'is-active')) return $attr;
    $attr['data-cd-src'] = $attr['src'];
    $attr['src'] = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
    if (isset($attr['srcset'])) {
        $attr['data-cd-srcset'] = $attr['srcset'];
        unset($attr['srcset']);
    }
    unset($attr['fetchpriority']);
    $attr['loading'] = 'lazy';
    return $attr;
}, 100);

// Native gallery: prioritize the actual first photo, lazy-load secondary photos.
add_filter('woocommerce_gallery_image_html_attachment_image_params', function ($params, $id, $size, $main) {
    if (!$main) {
        $params['loading'] = 'lazy';
        $params['decoding'] = 'async';
        unset($params['fetchpriority']);
    }
    return $params;
}, 20, 4);

add_action('wp_head', function () {
    if (is_front_page()) {
        echo '<link rel="preload" as="image" href="' . esc_url(content_url('/uploads/videos/hero-banner-poster.jpg')) . '" fetchpriority="high">' . "\n";
    }
}, 2);

add_action('wp_footer', function () {
    if (is_admin() || (function_exists('is_checkout') && is_checkout())) return;
    ?>
    <script id="cd-speed-carousel">
    (()=>{
      function hydrate(img){
        if(!img || !img.dataset.cdSrc) return;
        if(img.dataset.cdSrcset){img.srcset=img.dataset.cdSrcset;delete img.dataset.cdSrcset;}
        img.src=img.dataset.cdSrc;delete img.dataset.cdSrc;img.loading='eager';
      }
      const slides=document.querySelectorAll('.deal-carousel-slide[data-cd-src]');
      const observer=new MutationObserver(records=>{for(const r of records){if(r.target.classList.contains('is-active')) hydrate(r.target);}});
      slides.forEach(img=>observer.observe(img,{attributes:true,attributeFilter:['class']}));
    })();
    </script>
    <?php
}, 29);
