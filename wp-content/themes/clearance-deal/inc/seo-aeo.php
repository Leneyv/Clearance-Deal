<?php
/**
 * SEO & AEO (Answer Engine Optimization) Engine for Clearance Deal Kenya
 *
 * Implements:
 * 1. Dynamic Meta Titles, Descriptions, Canonicals & Targeted "Price in Kenya" Keywords
 * 2. OpenGraph & Twitter Cards
 * 3. JSON-LD Schemas: WebSite (SearchAction), OnlineStore/Store, FAQPage (AEO), ItemList, BreadcrumbList, Product
 * 4. Sitemap routing & 200 HTTP response enforcement
 *
 * @package Clearance_Deal
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

// ── 2. Dynamic Title Tag Optimization (Prioritizing "Price in Kenya") ────────
add_filter( 'pre_get_document_title', function( $title ) {
    if ( is_front_page() || is_home() ) {
        return 'Human Hair Wigs & Bundles Price in Kenya | Best Clearance Deals Nairobi | Clearance Deal';
    }
    if ( is_product() ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product ) {
            $price = $product->get_price();
            return esc_html( $product->get_name() ) . ' Price in Kenya: KSh ' . number_format( $price ) . ' | Clearance Deal';
        }
    }
    if ( is_product_category() ) {
        $term = get_queried_object();
        if ( $term && ! is_wp_error( $term ) ) {
            if ( $term->slug === 'wigs' ) {
                return 'Human Hair Wigs Price in Kenya | HD Lace Front & Glueless Wigs Deals Nairobi';
            }
            if ( $term->slug === 'hair' || $term->slug === 'hair-bundles' ) {
                return 'Human Hair Bundles Price in Kenya | Raw Indian Virgin Hair Deals Nairobi';
            }
            if ( $term->slug === 'deals-3000-10000' ) {
                return 'Human Hair Price in Kenya Under KSh 10,000 | Wigs & Bundles Clearance Deals';
            }
            if ( $term->slug === 'deals-11000-15000' ) {
                return 'Human Hair Price in Kenya KSh 11,000 to 15,000 | 18" HD Lace Wigs Deals';
            }
            if ( $term->slug === 'deals-15000-20000' ) {
                return 'Human Hair Wigs Price in Kenya KSh 15,000 to 20,000 | Virgin Hair Deals';
            }
            if ( $term->slug === 'deals-premium' ) {
                return 'Premium Human Hair Wigs Price in Kenya (KSh 20,000+) | VIP Wigs Nairobi';
            }
            if ( $term->slug === 'makeup' ) {
                return 'Cosmetics & Makeup Kits Price in Kenya | Authentic Beauty Clearance Nairobi';
            }
            return esc_html( $term->name ) . ' Price in Kenya | Clearance Deals Nairobi';
        }
    }
    if ( is_single() ) {
        return esc_html( get_the_title() ) . ' | Clearance Deal Kenya';
    }
    if ( is_category() ) {
        $term = get_queried_object();
        if ( $term && ! is_wp_error( $term ) ) {
            return esc_html( $term->name ) . ' Price in Kenya | Clearance Deal Guides';
        }
    }
    return $title;
}, 20 );

// ── 3. Meta Description, Keywords, Canonical, OpenGraph & Twitter Cards ───────
add_action( 'wp_head', function() {
    $canonical_url = home_url( '/' );
    $meta_desc     = 'Find the best human hair wigs and bundles price in Kenya. Shop 100% raw Indian virgin hair wigs & bundles in Nairobi from KSh 3,500. HD lace front, glueless closure wigs. Pay 50% now via Lipa Polepole M-Pesa.';
    $meta_keywords = 'human hair price in Kenya, human hair wigs price in Kenya, wigs price in Kenya, hair bundles price in Kenya, glueless wigs price in Kenya, HD lace wig price in Kenya, raw Indian hair price in Kenya, human hair wigs in Kenya, human hair Nairobi, cheap human hair wigs Kenya, human hair clearance price in Kenya, lipa polepole wigs Kenya';
    $og_title      = 'Human Hair Wigs & Bundles Price in Kenya | Best Clearance Deals Nairobi | Clearance Deal';
    $og_type       = 'website';
    $og_image      = 'https://clearance.co.ke/wp-content/uploads/2026/10/p2_img1.jpg'; // high-res hair banner

    if ( is_front_page() || is_home() ) {
        $canonical_url = home_url( '/' );
        $og_title      = 'Human Hair Wigs & Bundles Price in Kenya | Best Clearance Deals Nairobi | Clearance Deal';
        $meta_desc     = 'Find the best human hair wigs and bundles price in Kenya. Shop 100% raw Indian virgin hair wigs & bundles in Nairobi from KSh 3,500. HD lace front, glueless closure wigs. Pay 50% now via Lipa Polepole M-Pesa.';
    } elseif ( is_product() ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product ) {
            $canonical_url = get_permalink( $product->get_id() );
            $og_type       = 'product';
            $price         = $product->get_price();
            $og_title      = $product->get_name() . ' Price in Kenya: KSh ' . number_format( $price ) . ' | Clearance Deal';

            $excerpt = wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() );
            $excerpt = preg_replace( '/\s+/', ' ', $excerpt );
            $clean_excerpt = mb_substr( $excerpt, 0, 130 );
            $meta_desc = 'Best ' . esc_attr( $product->get_name() ) . ' price in Kenya is KSh ' . number_format( $price ) . '. ' . $clean_excerpt . '... 100% human hair with Lipa Polepole (50% payment) in Nairobi.';

            $img_id = $product->get_image_id();
            if ( $img_id ) {
                $og_image = wp_get_attachment_image_url( $img_id, 'full' );
            }
        }
    } elseif ( is_product_category() ) {
        $term = get_queried_object();
        if ( $term && ! is_wp_error( $term ) ) {
            $canonical_url = get_term_link( $term );
            $og_title      = $term->name . ' Price in Kenya | Clearance Deals Nairobi';
            if ( $term->slug === 'wigs' ) {
                $meta_desc = 'Check 100% human hair wigs price in Kenya. HD lace front wigs, glueless closure wigs, deep curly & straight wigs from KSh 7,400 to KSh 22,000+. Lipa Polepole 50% payment in Nairobi.';
            } elseif ( $term->slug === 'hair' || $term->slug === 'hair-bundles' ) {
                $meta_desc = 'Discover the lowest raw Indian virgin hair bundles price in Kenya. Single-donor wavy, curly & straight bundles with 5x5 HD closures from KSh 4,499. Direct dispatch in Nairobi.';
            } elseif ( $term->slug === 'deals-3000-10000' ) {
                $meta_desc = 'Shop affordable human hair price in Kenya under KSh 10,000. Real human hair bundles, crochet hair, and closures with 50% Lipa Polepole payment via M-Pesa.';
            } elseif ( $term->slug === 'deals-11000-15000' ) {
                $meta_desc = 'Best value human hair price in Kenya between KSh 11,000 and 15,000. 18-inch HD lace closure wigs and 3-bundle sets shipped door-to-door in Kenya.';
            } elseif ( $term->slug === 'deals-15000-20000' ) {
                $meta_desc = 'Luxury virgin human hair wigs price in Kenya from KSh 15,000 to 20,000. Pre-plucked HD lace front wigs, rich natural black and body wave styles.';
            } elseif ( $term->slug === 'deals-premium' ) {
                $meta_desc = 'Explore premium human hair wigs price in Kenya (KSh 20,000+). Single-donor Indian temple hair, blonde balayage, honey ombre, and multi-styler devices.';
            } else {
                $meta_desc = 'Compare wholesale ' . esc_html( $term->name ) . ' price in Kenya at Clearance Deal. Verified authentic stock with direct warehouse dispatch in Nairobi.';
            }
        }
    } elseif ( is_single() ) {
        global $post;
        $canonical_url = get_permalink( $post->ID );
        $og_type       = 'article';
        $og_title      = get_the_title( $post->ID ) . ' | Clearance Deal Kenya';
        $thumb_id      = get_post_thumbnail_id( $post->ID );
        if ( $thumb_id ) {
            $og_image = wp_get_attachment_image_url( $thumb_id, 'large' );
        }
        $excerpt = wp_strip_all_tags( $post->post_excerpt ?: $post->post_content );
        $excerpt = preg_replace( '/\s+/', ' ', $excerpt );
        if ( ! empty( $excerpt ) ) {
            $meta_desc = mb_substr( $excerpt, 0, 155 );
        }
        $post_tags = wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) );
        if ( ! empty( $post_tags ) ) {
            $meta_keywords = implode( ', ', $post_tags ) . ', human hair price in Kenya, wigs price in Kenya';
        }
    } elseif ( is_category() ) {
        $term = get_queried_object();
        if ( $term && ! is_wp_error( $term ) ) {
            $canonical_url = get_category_link( $term->term_id );
            $og_title      = esc_html( $term->name ) . ' Price in Kenya | Clearance Deal Guides';
            $meta_desc     = 'Complete guides and verified price lists for ' . esc_html( $term->name ) . ' in Kenya. Authentic raw hair, glueless HD wigs, Lipa Polepole M-Pesa.';
            $meta_keywords = esc_html( $term->name ) . ', human hair price in Kenya, wigs price in Kenya, Nairobi hair clearance';
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

    echo "\n<!-- Technical & On-Page SEO (Price in Kenya Keywords) -->\n";
    echo '<meta name="description" content="' . esc_attr( $meta_desc ) . '">' . "\n";
    echo '<meta name="keywords" content="' . esc_attr( $meta_keywords ) . '">' . "\n";
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

// ── 4. Structured Data (JSON-LD) for Google Search, AI Overviews & AEO ─────────
add_action( 'wp_head', function() {
    // A. WebSite Schema with SearchAction
    $website_schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        '@id'             => 'https://clearance.co.ke/#website',
        'url'             => 'https://clearance.co.ke/',
        'name'            => 'Clearance Deal Kenya',
        'alternateName'   => 'Clearance Deal Human Hair & Wigs Price in Kenya',
        'description'     => 'Authentic 100% Raw Indian Human Hair Bundles & Glueless HD Lace Front Wigs Price in Kenya — Direct Warehouse Clearance Deals in Nairobi',
        'keywords'        => 'human hair price in Kenya, human hair wigs price in Kenya, wigs price in Kenya, hair bundles price in Kenya, HD lace wig price in Kenya, glueless wigs price in Kenya, raw Indian hair price in Kenya, lipa polepole wigs Kenya',
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

    // B. OnlineStore / LocalBusiness Schema
    $store_schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => array( 'OnlineStore', 'Store' ),
        '@id'             => 'https://clearance.co.ke/#store',
        'name'            => 'Clearance Deal Kenya',
        'alternateName'   => 'Clearance Deal Human Hair & Wigs Price in Kenya',
        'url'             => 'https://clearance.co.ke/',
        'logo'            => 'https://clearance.co.ke/wp-content/themes/clearance-deal/assets/images/logo.svg',
        'image'           => 'https://clearance.co.ke/wp-content/uploads/2026/10/p2_img1.jpg',
        'description'     => 'Kenya\'s premier verified liquidation distributor offering the best human hair wigs and bundles price in Kenya. 100% authentic single-donor raw Indian bundles, glueless HD lace front wigs, and beauty clearance in Nairobi.',
        'telephone'       => '+254751338975',
        'email'           => 'info@clearance.co.ke',
        'priceRange'      => 'KSh 3,000 - KSh 35,000',
        'currenciesAccepted' => 'KES',
        'paymentAccepted' => 'M-Pesa, Lipa Polepole (50% Payment BNPL), PesaPal, Debit/Credit Card',
        'keywords'        => 'human hair price in Kenya, human hair wigs price in Kenya, wigs price in Kenya, hair bundles price in Kenya, glueless lace front wigs price in Kenya, raw Indian hair price in Kenya, lipa polepole hair',
        'knowsAbout'      => array(
            'Human Hair Price in Kenya',
            'Human Hair Wigs Price in Kenya',
            'Wigs Price in Kenya',
            'Hair Bundles Price in Kenya',
            'Glueless HD Lace Wigs Price in Kenya',
            'Raw Indian Virgin Hair Bundles',
            'Lace Front Wigs in Nairobi',
            'Lipa Polepole Buy Now Pay Later Hair',
            'Single-Donor Human Hair Burn Testing',
            'Hair Extensions and Lace Closures',
        ),
        'address'         => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Office 303, 1st Floor, Highlight Business Park, 4th Parklands',
            'addressLocality' => 'Nairobi',
            'addressRegion'   => 'Nairobi County',
            'addressCountry'  => 'KE',
        ),
        'areaServed'      => array(
            array( '@type' => 'City', 'name' => 'Nairobi' ),
            array( '@type' => 'City', 'name' => 'Mombasa' ),
            array( '@type' => 'City', 'name' => 'Kisumu' ),
            array( '@type' => 'City', 'name' => 'Nakuru' ),
            array( '@type' => 'City', 'name' => 'Eldoret' ),
            array( '@type' => 'City', 'name' => 'Thika' ),
            array( '@type' => 'Country', 'name' => 'Kenya' ),
        ),
        'hasOfferCatalog' => array(
            '@type'         => 'OfferCatalog',
            'name'          => 'Human Hair Wigs & Bundles Price in Kenya Catalog',
            'itemListElement' => array(
                array(
                    '@type' => 'OfferCatalog',
                    'name'  => '100% Human Hair Wigs Price in Kenya',
                ),
                array(
                    '@type' => 'OfferCatalog',
                    'name'  => 'Raw Indian Virgin Hair Bundles Price in Kenya',
                ),
                array(
                    '@type' => 'OfferCatalog',
                    'name'  => 'HD Transparent Lace Closures & Frontals Price in Kenya',
                ),
            ),
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

    // C. Comprehensive AEO FAQPage Schema (Targeting "Price in Kenya" Queries for AI Search & Google Overviews)
    $faq_schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        '@id'        => 'https://clearance.co.ke/#faq',
        'mainEntity' => array(
            array(
                '@type'          => 'Question',
                'name'           => 'What is the human hair wigs price in Kenya?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'The human hair wigs price in Kenya at Clearance Deal ranges from KSh 7,400 to KSh 14,995 for 18-inch glueless HD lace closure and lace front wigs, and from KSh 15,000 to KSh 25,000+ for luxury virgin blonde balayage, honey ombre, and long bone straight styles. Starter crochet hair and single closures are available from KSh 3,500.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'What is the raw Indian hair bundles price in Kenya?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'The raw Indian hair bundles price in Kenya starts from KSh 4,499 for single unprocessed bundles, and from KSh 7,990 to KSh 14,995 for complete 3-bundle sets with 5x5 HD lace closures in Body Wave, Deep Wave, and Natural Curly textures.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'Where can I find the best human hair price in Kenya online?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'You can find the best authentic human hair price in Kenya at Clearance Deal Kenya (clearance.co.ke). Because we liquidate directly from central warehouse overstock in Highlight Business Park, 4th Parklands, Nairobi, our prices are 40% to 70% below regular retail salon prices, complete with Lipa Polepole (50% payment) via M-Pesa.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'Why is the human hair wigs price in Kenya lower at Clearance Deal?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Our human hair wigs price in Kenya is substantially lower because we operate as a direct liquidation and warehouse clearance hub. We bypass expensive salon rent, middleman importers, and retail markups, passing wholesale discounts directly to Kenyan consumers.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'What is a glueless HD lace front wig and its price in Kenya?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'A glueless HD lace front wig uses invisible Swiss HD lace that melts seamlessly into African and melanin-rich skin tones without glue or adhesives. In Kenya, our glueless HD lace front wig price ranges from KSh 10,999 to KSh 14,995 for 18-inch virgin human hair, featuring adjustable elastic bands for 3-minute installation.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'Can I buy human hair in Kenya on Lipa Polepole (Buy Now Pay Later)?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Yes! Clearance Deal Kenya offers Lipa Polepole (BNPL) across Kenya. You only pay a 50% payment now. The remaining 50% balance is comfortably cleared over easy monthly instalments via Safaricom M-Pesa.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'How can I verify if human hair in Kenya is 100% genuine before paying?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Perform the protein burn test on a strand: genuine 100% human hair burns slowly with white smoke, smells like burnt natural protein (feathers), and crumbles into fine ash. Synthetic mixes burn quickly with toxic black smoke and melt into hard plastic beads. All Clearance Deal Kenya hair is 100% burn-test verified.',
                ),
            ),
            array(
                '@type'          => 'Question',
                'name'           => 'How long does delivery take across Kenya?',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => 'Orders within Nairobi County are delivered same-day or within 24 hours via dedicated warehouse dispatch riders. Deliveries to Mombasa, Kisumu, Nakuru, Eldoret, Thika, and upcountry locations arrive within 24 to 48 hours via Wells Fargo or Fargo Courier with secure door-to-door tracking.',
                ),
            ),
        ),
    );

    echo "\n<!-- AEO & Traditional Structured Data (JSON-LD) -->\n";
    echo '<script type="application/ld+json">' . wp_json_encode( $website_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    echo '<script type="application/ld+json">' . wp_json_encode( $store_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

    if ( is_front_page() || is_home() ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

        // D. ItemList Schema for AI & Google Search Shopping Feeds (Price in Kenya Catalog)
        if ( function_exists( 'wc_get_products' ) ) {
            $featured_deals = wc_get_products( array(
                'status'   => 'publish',
                'limit'    => 16,
                'orderby'  => 'menu_order',
                'order'    => 'ASC',
            ) );

            if ( ! empty( $featured_deals ) ) {
                $item_list_elements = array();
                $pos = 1;
                foreach ( $featured_deals as $p ) {
                    $img_id = $p->get_image_id();
                    $img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'medium_large' ) : '';
                    $item_list_elements[] = array(
                        '@type'    => 'ListItem',
                        'position' => $pos++,
                        'item'     => array(
                            '@type'       => 'Product',
                            'name'        => $p->get_name() . ' — Price in Kenya',
                            'url'         => get_permalink( $p->get_id() ),
                            'image'       => $img_url,
                            'description' => wp_strip_all_tags( $p->get_short_description() ?: $p->get_name() ),
                            'offers'      => array(
                                '@type'         => 'Offer',
                                'price'         => $p->get_price(),
                                'priceCurrency' => 'KES',
                                'availability'  => $p->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                                'seller'        => array(
                                    '@type' => 'Organization',
                                    'name'  => 'Clearance Deal Kenya',
                                ),
                            ),
                        ),
                    );
                }

                $item_list_schema = array(
                    '@context'        => 'https://schema.org',
                    '@type'           => 'ItemList',
                    'name'            => 'Human Hair Wigs & Bundles Price in Kenya Deals Catalog',
                    'description'     => 'Featured 100% human hair wigs and raw Indian bundles price in Kenya, available in Nairobi with 50% Lipa Polepole payment.',
                    'numberOfItems'   => count( $item_list_elements ),
                    'itemListElement' => $item_list_elements,
                );

                echo '<script type="application/ld+json">' . wp_json_encode( $item_list_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
            }
        }
    }

    // E. BreadcrumbList Schema on Single Products
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
                    'name'     => $primary_cat->name . ' Price in Kenya',
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

    // F. BlogPosting & BreadcrumbList Schema on Single Posts
    if ( is_single() && 'post' === get_post_type() ) {
        global $post;
        $thumb_id  = get_post_thumbnail_id( $post->ID );
        $image_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'large' ) : 'https://clearance.co.ke/wp-content/uploads/2026/10/model-front-view-straight-raw-indian-hair-bundles-768x1024.jpg';
        $cats      = get_the_category( $post->ID );
        $cat_name  = ! empty( $cats ) ? $cats[0]->name : 'Hair & Wigs';

        $article_schema = array(
            '@context'         => 'https://schema.org',
            '@type'            => 'BlogPosting',
            'headline'         => get_the_title( $post->ID ),
            'description'      => wp_strip_all_tags( $post->post_excerpt ?: mb_substr( strip_tags( $post->post_content ), 0, 160 ) ),
            'image'            => $image_url,
            'datePublished'    => get_the_date( 'c', $post->ID ),
            'dateModified'     => get_the_modified_date( 'c', $post->ID ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => get_permalink( $post->ID ),
            ),
            'author'           => array(
                '@type' => 'Organization',
                'name'  => 'Clearance Deal Kenya Hair Specialists',
                'url'   => home_url( '/' ),
            ),
            'publisher'        => array(
                '@type' => 'Organization',
                'name'  => 'Clearance Deal Kenya',
                'logo'  => array(
                    '@type' => 'ImageObject',
                    'url'   => home_url( '/wp-content/themes/clearance-deal/assets/images/logo.svg' ),
                ),
            ),
            'articleSection'   => $cat_name,
            'inLanguage'       => 'en-KE',
        );

        $article_breadcrumbs = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array(
                array(
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => 'Home',
                    'item'     => home_url( '/' ),
                ),
                array(
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => 'Hair & Wigs Blog',
                    'item'     => home_url( '/blog/' ),
                ),
                array(
                    '@type'    => 'ListItem',
                    'position' => 3,
                    'name'     => get_the_title( $post->ID ),
                    'item'     => get_permalink( $post->ID ),
                ),
            ),
        );

        echo '<script type="application/ld+json">' . wp_json_encode( $article_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
        echo '<script type="application/ld+json">' . wp_json_encode( $article_breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}, 10 );
