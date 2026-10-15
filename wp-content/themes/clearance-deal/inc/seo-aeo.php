<?php
/**
 * SEO & AEO (Answer Engine Optimization) Engine for Clearance Deal Kenya
 *
 * Implements:
 * 1. Dynamic Meta Titles, Descriptions, Canonicals
 * 2. OpenGraph & Twitter Cards
 * 3. JSON-LD Schemas: WebSite (SearchAction), OnlineStore, FAQPage (AEO), BreadcrumbList, Product
 * 4. Sitemap routing & 200 HTTP response enforcement
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ── 1. Sitemap 200 Status & Rewrite Enforcement ─────────────────────────────
add_action( 'template_redirect', function() {
    if ( get_query_var( 'sitemap' ) || get_query_var( 'sitemap-stylesheet' ) ) {
        status_header( 200 );
        global $wp_query;
        if ( $wp_query ) {
            $wp_query->is_404 = false;
        }
    }
}, 0 );

add_action( 'init', function() {
    add_rewrite_rule( '^sitemap\.xml$', 'index.php?sitemap=index', 'top' );
} );

// ── 2. Dynamic Title Tag Optimization ───────────────────────────────────────
add_filter( 'pre_get_document_title', function( $title ) {
    if ( is_front_page() || is_home() ) {
        return 'Clearance Deal Kenya | Raw Indian Hair Bundles, HD Lace Wigs & Beauty Clearance';
    }
    if ( is_product() ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product ) {
            $price = $product->get_price();
            return esc_html( $product->get_name() ) . ' — KSh ' . number_format( $price ) . ' | Clearance Deal Kenya';
        }
    }
    if ( is_product_category() ) {
        $term = get_queried_object();
        if ( $term && ! is_wp_error( $term ) ) {
            if ( $term->slug === 'hair' ) {
                return 'Raw Indian Human Hair Bundles Clearance Kenya | Clearance Deal';
            }
            if ( $term->slug === 'wigs' ) {
                return 'HD Transparent Lace Wigs Clearance | Glueless Wigs Kenya';
            }
            if ( $term->slug === 'makeup' ) {
                return 'Cosmetics & Makeup Kits Clearance Nairobi | Clearance Deal Kenya';
            }
            return esc_html( $term->name ) . ' Deals | Clearance Deal Kenya';
        }
    }
    return $title;
}, 20 );

// ── 3. Meta Description, Canonical, OpenGraph & Twitter Cards ──────────────
add_action( 'wp_head', function() {
    $canonical_url = home_url( '/' );
    $meta_desc     = 'Shop certified authentic 100% raw Indian human hair bundles, HD transparent lace closure wigs & cosmetics in Kenya. Direct warehouse dispatch in Nairobi. Lipa Polepole via M-Pesa.';
    $og_title      = 'Clearance Deal Kenya | Raw Indian Hair Bundles, HD Lace Wigs & Beauty Clearance';
    $og_type       = 'website';
    $og_image      = 'https://clearance.co.ke/wp-content/uploads/2026/10/p2_img1.jpg'; // high-res wavy hair banner

    if ( is_front_page() || is_home() ) {
        $canonical_url = home_url( '/' );
        $og_title      = 'Clearance Deal Kenya | Raw Indian Hair Bundles, HD Lace Wigs & Beauty Clearance';
        $meta_desc     = 'Shop certified authentic 100% raw Indian human hair bundles, HD transparent lace closure wigs & cosmetics in Kenya. Direct warehouse dispatch in Nairobi. Lipa Polepole via M-Pesa.';
    } elseif ( is_product() ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product ) {
            $canonical_url = get_permalink( $product->get_id() );
            $og_type       = 'product';
            $price         = $product->get_price();
            $og_title      = $product->get_name() . ' — Only KSh ' . number_format( $price );

            $excerpt = wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() );
            $excerpt = preg_replace( '/\s+/', ' ', $excerpt );
            $meta_desc = mb_substr( $excerpt, 0, 155 );
            if ( mb_strlen( $excerpt ) > 155 ) {
                $meta_desc .= '...';
            }

            $img_id = $product->get_image_id();
            if ( $img_id ) {
                $og_image = wp_get_attachment_image_url( $img_id, 'full' );
            }
        }
    } elseif ( is_product_category() ) {
        $term = get_queried_object();
        if ( $term && ! is_wp_error( $term ) ) {
            $canonical_url = get_term_link( $term );
            $og_title      = $term->name . ' Clearance Deals | Clearance Deal Kenya';
            if ( $term->slug === 'hair' ) {
                $meta_desc = 'Shop 100% single-donor raw Indian virgin hair bundles in Kenya. Aligned cuticles, chemical-free, protein burn-tested. Same-day dispatch in Nairobi & nationwide delivery.';
            } elseif ( $term->slug === 'wigs' ) {
                $meta_desc = 'Buy premium glueless HD lace front wigs and 5x5 closure wigs in Kenya. Pure raw human hair, pre-plucked hairlines, bleached knots. Lipa Polepole BNPL via M-Pesa.';
            } else {
                $meta_desc = 'Discover wholesale clearance markdowns on ' . esc_html( $term->name ) . ' at Clearance Deal Kenya. Verified stock with direct warehouse dispatch.';
            }
        }
    } elseif ( is_page() ) {
        global $post;
        $canonical_url = get_permalink( $post->ID );
        $og_title      = get_the_title( $post->ID ) . ' | Clearance Deal Kenya';
        $excerpt       = wp_strip_all_tags( $post->post_excerpt ?: $post->post_content );
        $excerpt       = preg_replace( '/\s+/', ' ', $excerpt );
        if ( ! empty( $excerpt ) ) {
            $meta_desc = mb_substr( $excerpt, 0, 155 );
        }
    }

    echo "\n<!-- Technical & On-Page SEO -->\n";
    echo '<meta name="description" content="' . esc_attr( $meta_desc ) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '">' . "\n";

    echo "\n<!-- OpenGraph Social Tags -->\n";
    echo '<meta property="og:locale" content="en_KE">' . "\n";
    echo '<meta property="og:site_name" content="Clearance Deal Kenya">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $meta_desc ) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $canonical_url ) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url( $og_image ) . '">' . "\n";

    echo "\n<!-- Twitter Cards -->\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $og_title ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $meta_desc ) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url( $og_image ) . '">' . "\n";
}, 5 );

// ── 4. Structured Data (JSON-LD) for AEO & Rich Results ───────────────────────
add_action( 'wp_head', function() {
    // A. WebSite Schema with SearchAction
    $website_schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        '@id'             => 'https://clearance.co.ke/#website',
        'url'             => 'https://clearance.co.ke/',
        'name'            => 'Clearance Deal Kenya',
        'description'     => 'Authentic Raw Indian Human Hair Bundles, HD Lace Wigs & Beauty Liquidation Deals in Kenya',
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'       => 'EntryPoint',
                'urlTemplate' => 'https://clearance.co.ke/?s={search_term_string}',
            ),
            'query-input' => 'required name=search_term_string',
        ),
        'inLanguage'      => 'en-KE',
    );

    // B. OnlineStore / Organization Schema
    $store_schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'OnlineStore',
        '@id'             => 'https://clearance.co.ke/#store',
        'name'            => 'Clearance Deal Kenya',
        'url'             => 'https://clearance.co.ke/',
        'logo'            => 'https://clearance.co.ke/wp-content/themes/clearance-deal/assets/images/logo.svg',
        'image'           => 'https://clearance.co.ke/wp-content/uploads/2026/10/p2_img1.jpg',
        'description'     => 'Kenya\'s premier verified liquidation distributor specializing in 100% authentic single-donor raw Indian human hair bundles, HD transparent lace closure wigs, and beauty overstock.',
        'telephone'       => '+254751338975',
        'email'           => 'info@clearance.co.ke',
        'priceRange'      => 'KSh 799 - KSh 69,000',
        'currenciesAccepted' => 'KES',
        'paymentAccepted' => 'M-Pesa, Cash on Delivery, Lipa Polepole (BNPL)',
        'address'         => array(
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Nairobi',
            'addressRegion'   => 'Nairobi County',
            'addressCountry'  => 'KE',
        ),
        'areaServed'      => array(
            '@type' => 'Country',
            'name'  => 'Kenya',
        ),
        'hasMerchantReturnPolicy' => array(
            '@type'              => 'MerchantReturnPolicy',
            'applicableCountry'  => 'KE',
            'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
            'merchantReturnDays' => 7,
            'returnMethod'       => 'https://schema.org/ReturnByMail',
            'returnFees'         => 'https://schema.org/FreeReturn',
        ),
    );

    // C. AEO FAQPage Schema (Answers for AI Search Engines & Google AI Overviews)
    $faq_schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        '@id'        => 'https://clearance.co.ke/#faq',
        'mainEntity' => array(
            array(
                '@type'          => 'Question',
                'name'           => 'Is the hair sold at Clearance Deal Kenya 100% authentic human hair?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Yes. Every lot sold by Clearance Deal Kenya is 100% authentic, single-donor raw virgin Indian human hair with unidirectional running cuticles. Every batch passes the protein burn test — it smells like natural protein and crumbles completely into fine ash, with zero synthetic plastic melting or beading.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'How does Lipa Polepole (Buy Now Pay Later) work at Clearance Deal Kenya?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Lipa Polepole allows customers across Kenya to purchase high-quality hair bundles, wigs, and electronics with a 50% deposit upon delivery. The remaining 50% balance is settled in convenient monthly instalments via Safaricom M-Pesa.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'How long does delivery take within Nairobi and outside Nairobi?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'All orders are dispatched directly from our central warehouse in Nairobi. Deliveries within Nairobi County are completed same-day or within 24 hours via dedicated dispatch riders. Deliveries to upcountry locations (Mombasa, Kisumu, Nakuru, Eldoret, Thika) take 24 to 48 hours via Wells Fargo or G4S Courier.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'Can I dye, bleach, and heat-style the raw Indian hair bundles?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Yes. Because our hair is 100% unprocessed raw human hair with healthy aligned cuticles, it can safely be bleached to level 10 blonde (#613), dyed any shade, flat-ironed silky straight, or curled with curling wands. It reverts to its natural pattern upon washing.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'What is the return and exchange policy?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Clearance Deal Kenya offers an Easy 7-Day Return and Exchange policy. If you receive an item and the sealed packaging is unopened, you can return or exchange it within 7 days of delivery.',
                ),
            ),
        ),
    );

    echo "\n<!-- AEO & Traditional Structured Data (JSON-LD) -->\n";
    echo '<script type="application/ld+json">' . wp_json_encode( $website_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    echo '<script type="application/ld+json">' . wp_json_encode( $store_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

    if ( is_front_page() || is_home() ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }

    // D. BreadcrumbList Schema on Single Products
    if ( is_product() ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product ) {
            $cat_terms = get_the_terms( $post->ID, 'product_cat' );
            $primary_cat = ! empty( $cat_terms ) && ! is_wp_error( $cat_terms ) ? $cat_terms[0] : null;

            $breadcrumbs = array(
                '@context'        => 'https://schema.org',
                '@type'           => 'BreadcrumbList',
                'itemListElement' => array(
                    array(
                        '@type'    => 'ListItem',
                        'position' => 1,
                        'name'     => 'Home',
                        'item'     => home_url( '/' ),
                    ),
                ),
            );

            if ( $primary_cat ) {
                $breadcrumbs['itemListElement'][] = array(
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => $primary_cat->name,
                    'item'     => get_term_link( $primary_cat ),
                );
                $breadcrumbs['itemListElement'][] = array(
                    '@type'    => 'ListItem',
                    'position' => 3,
                    'name'     => $product->get_name(),
                    'item'     => get_permalink( $product->get_id() ),
                );
            } else {
                $breadcrumbs['itemListElement'][] = array(
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => $product->get_name(),
                    'item'     => get_permalink( $product->get_id() ),
                );
            }

            echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
        }
    }
}, 10 );
