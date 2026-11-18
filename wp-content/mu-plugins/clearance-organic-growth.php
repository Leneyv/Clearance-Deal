<?php
/**
 * Plugin Name: Clearance Organic Growth
 * Description: Store-specific collections, live comparisons and public product discovery.
 * Version: 1.0.0
 */
if (!defined('ABSPATH')) exit;
function cd_growth_term_url($slug) {
    $term=get_term_by('slug',$slug,'product_cat');
    if (!$term || is_wp_error($term)) return home_url('/shop/');
    $url=get_term_link($term); return is_wp_error($url) ? home_url('/shop/') : $url;
}
function cd_growth_collection_nav() {
    $labels=['wigs'=>'All wigs','human-hair-wigs'=>'Human hair wigs','glueless-wigs'=>'Glueless wigs','synthetic-wigs'=>'Synthetic wigs','hair'=>'Hair bundles'];
    $out='<nav class="cd-growth-links" aria-label="Shop by hair type">';
    foreach($labels as $slug=>$label) $out.='<a href="'.esc_url(cd_growth_term_url($slug)).'">'.esc_html($label).'</a>';
    return $out.'</nav>';
}
function cd_growth_discovery() {
    echo '<section class="cd-growth-discovery" aria-labelledby="cd-growth-choose"><h2 id="cd-growth-choose">Find the right wig or hair bundle</h2><p>Shop complete wigs separately from loose bundles. Compare materials, current prices and fitting needs before choosing.</p>'.cd_growth_collection_nav().'<p><a href="'.esc_url(home_url('/human-hair-wigs-price-in-kenya-guide/')).'">Compare wig prices</a> · <a href="'.esc_url(home_url('/glueless-hd-lace-wigs-price-in-kenya/')).'">Glueless wig fit guide</a> · <a href="'.esc_url(home_url('/raw-indian-hair-bundles-price-in-kenya/')).'">Bundle buying guide</a></p></section>';
}
function cd_growth_product_label($p) {
    $material=$p->get_meta('_cd_growth_material');
    $kind=$p->get_meta('_cd_growth_item_type');
    return implode(' · ',array_filter([$material,$kind]));
}
add_shortcode('cd_live_comparison',function($atts) {
    if(!function_exists('wc_get_product')) return '';
    $atts=shortcode_atts(['ids'=>'','label'=>'Compare current catalogue prices'],$atts,'cd_live_comparison');
    $ids=array_slice(array_filter(array_map('absint',explode(',',$atts['ids']))),0,12);
    $out='<section class="cd-growth-comparison"><h2>'.esc_html($atts['label']).'</h2><p>Live catalogue prices in Kenyan shillings. Check the product page for available options, stock, delivery and fitting costs.</p><div class="cd-growth-table"><table><thead><tr><th scope="col">Product</th><th scope="col">Material / item</th><th scope="col">Current price</th><th scope="col">Availability</th></tr></thead><tbody>';
    $count=0;
    foreach($ids as $id) {
        $p=wc_get_product($id); if(!$p || $p->get_status()!=='publish' || !$p->is_visible()) continue;
        $out.='<tr><td><a href="'.esc_url(get_permalink($id)).'">'.esc_html($p->get_name()).'</a></td><td>'.esc_html(cd_growth_product_label($p)).'</td><td>'.wp_kses_post($p->get_price_html()).'</td><td>'.($p->is_in_stock()?'In stock':'Out of stock').'</td></tr>'; $count++;
    }
    return $count ? $out.'</tbody></table></div></section>' : '';
});
add_action('woocommerce_archive_description',function(){
    if(!is_product_category() || is_paged()) return;
    $html=get_term_meta(get_queried_object_id(),'_cd_growth_description_html',true);
    if(!$html) return;
    remove_action('woocommerce_archive_description','woocommerce_taxonomy_archive_description',10);
    $safe=wp_kses_post($html);
    if(preg_match('/^(<p>.*?<\/p>)(.*)$/s',$safe,$parts)) {
        echo '<div class="term-description cd-growth-term-description">'.$parts[1].'<details class="cd-growth-order-info"><summary>Buying guide &amp; order information</summary>'.$parts[2].'</details></div>';
    } else { echo '<div class="term-description cd-growth-term-description">'.$safe.'</div>'; }

},9);
add_action('woocommerce_archive_description',function(){
    if(!is_product_category()) return;
    echo cd_growth_collection_nav();
},25);
add_action('woocommerce_single_product_summary',function(){
    global $product; if(!$product) return;
    $label=cd_growth_product_label($product); if(!$label) return;
    echo '<p class="cd-growth-product-facts"><strong>Product details:</strong> '.esc_html($label).'. See specifications and care instructions below.</p>';
},8);
add_action('woocommerce_after_single_product_summary',function(){
    if(!is_product()) return;
    echo '<section class="cd-growth-discovery"><h2>Compare before you choose</h2>'.cd_growth_collection_nav().'<p><a href="'.esc_url(home_url('/human-hair-wigs-price-in-kenya-guide/')).'">Wig price guide</a> · <a href="'.esc_url(home_url('/raw-indian-hair-bundles-price-in-kenya/')).'">Bundle buying checklist</a> · <a href="'.esc_url(home_url('/refund-policy/')).'">Return eligibility</a></p></section>';
},25);
add_filter('cd_seo_context',function($c){
    $map=[
      'wigs'=>['Wigs in Kenya | Human Hair & Synthetic | Clearance Deal','Compare complete wigs in Kenya by material, cap, length and current KSh price. Shop human hair or synthetic styles and get help choosing from our Nairobi team.'],
      'human-hair-wigs'=>['Human Hair Wigs in Kenya | Prices & Styles','Shop human hair wigs in Kenya. Compare current KSh prices, lengths, colours and cap construction. Read each listing and ask our Nairobi team about fit.'],
      'glueless-wigs'=>['Glueless Wigs in Kenya | Prices, Lace & Fit','Compare glueless wigs in Kenya by current price, lace area, length and cap fit. Read product details and our fitting guide before ordering.'],
      'synthetic-wigs'=>['Synthetic Wigs in Kenya | Bob & Lace Front Styles','Shop synthetic wigs in Kenya with clear material labels. Compare bob, lace-front and monofilament styles, current prices and care instructions.'],
      'hair'=>['Hair Bundles in Kenya | Raw Indian Bundles & Sets','Compare raw Indian hair bundles and bundle-and-closure sets in Kenya. Check current KSh prices, lengths, pack contents and material on every listing.'],
      'hairtools'=>['Hair Styling Tools in Kenya | Airwrap & Multi-Stylers','Compare hair multi-stylers in Kenya. Check each device’s current price, attachments, specifications and care instructions before buying.']
    ];
    if(is_product_category()) {
        $term=get_queried_object();
        if(isset($map[$term->slug])) {[$c['title'],$c['desc']]=$map[$term->slug]; if(is_paged()) $c['title'].=' – Page '.max(1,(int)get_query_var('paged'));}
    }
    if(function_exists('is_product') && is_product()) {
        $p=wc_get_product(get_queried_object_id());
        if($p) {
            $title=$p->get_meta('_cd_growth_seo_title');if($title) $c['title']=$title;
            if($title) {
                $name=preg_replace('/\s*\|\s*Kenya$/u','',$title);
                $price=$p->get_price()!=='' ? ($p->is_type('variable')?'From KSh ':'KSh ').number_format((float)wc_get_price_to_display($p)).'. ' : '';
                $text=$name.' in Kenya. '.$price.cd_growth_product_label($p).'. Check options, delivery and return conditions.';
                $c['desc']=function_exists('cd_seo_text')?cd_seo_text($text):wp_html_excerpt($text,155,'…');
            }
        }
    }
    return $c;
});
add_filter('woocommerce_structured_data_product',function($data,$p){
    $material=$p->get_meta('_cd_growth_material');
    if(in_array($material,['Human hair','Synthetic fibre'],true)) $data['material']=$material;
    return $data;
},20,2);
add_action('wp_enqueue_scripts',function(){
    wp_register_style('cd-organic-growth',false,[], '1.0.0');wp_enqueue_style('cd-organic-growth');
    wp_add_inline_style('cd-organic-growth','.woocommerce ul.products::before,.woocommerce ul.products::after{content:none!important;display:none!important}.woocommerce ul.products li.product .deal-image-wrapper img{height:100%!important;aspect-ratio:auto;object-fit:cover}.woocommerce ul.products li.product .woocommerce-loop-product__title{height:auto!important;min-height:3.1em;line-height:1.4}.woocommerce ul.products li.product .star-rating{float:none;margin:8px 12px}.woocommerce ul.products li.product .price{margin-top:8px}.cd-growth-discovery{max-width:1120px;margin:32px auto;padding:24px;color:#27212b;line-height:1.65;border:1px solid #dde3e8;border-radius:12px;background:#fff}.cd-growth-discovery h2{font-size:clamp(22px,3vw,30px);line-height:1.25;margin:0 0 12px}.cd-growth-links{display:flex;flex-wrap:wrap;gap:8px;margin:18px 0}.cd-growth-links a{display:inline-block;padding:10px 14px;border:1px solid #3a0f34;border-radius:24px;color:#3a0f34;background:#fff;font-size:14px;font-weight:600;text-decoration:none}.cd-growth-links a:hover,.cd-growth-links a:focus-visible{background:#3a0f34;color:#fff}.bnpl-floating-widget:not(.is-open) .bnpl-card-panel{display:none!important;visibility:hidden;pointer-events:none}.cd-growth-order-info{margin:12px 0}.cd-growth-order-info summary{cursor:pointer;font-weight:600;text-decoration:underline;color:#3a0f34}.cd-growth-term-description h2{font-size:21px;line-height:1.3;margin:20px 0 10px}.cd-growth-term-description p{margin:0 0 16px;line-height:1.65}.cd-growth-table{max-width:100%;overflow-x:auto}.cd-growth-table table{width:100%;border-collapse:collapse;min-width:540px}.cd-growth-table th,.cd-growth-table td{padding:12px;border:1px solid #dde3e8;text-align:left;vertical-align:top}.cd-growth-table th{background:#f7f3f7}.cd-growth-product-facts{font-size:14px;line-height:1.6;padding:12px;background:#f7f3f7;border-radius:6px}.mobile-nav-links .cd-growth-links{display:grid;gap:6px}.mobile-nav-links .cd-growth-links a{border-radius:6px}.cd-growth-discovery a:not(.cd-growth-links a){text-decoration:underline}@media(max-width:600px){.cd-growth-discovery{margin:20px 12px;padding:18px}.cd-growth-links a{padding:9px 12px}}');
});
// Public catalogue feed: no customer/order data and no invented product identifiers.
add_action('template_redirect',function(){
    if(trim((string)wp_parse_url($_SERVER['REQUEST_URI'] ?? '',PHP_URL_PATH),'/')!=='product-feed.xml') return;
    if(!function_exists('wc_get_products')) return;
    status_header(200);header('Content-Type: application/xml; charset=UTF-8');header('X-Robots-Tag: noindex');
    $x=new XMLWriter();$x->openMemory();$x->startDocument('1.0','UTF-8');$x->startElement('rss');$x->writeAttribute('version','2.0');$x->writeAttribute('xmlns:g','http://base.google.com/ns/1.0');$x->startElement('channel');$x->writeElement('title','Clearance Deal Kenya');$x->writeElement('link',home_url('/'));$x->writeElement('description','Current public product catalogue.');
    $products=wc_get_products(['status'=>'publish','limit'=>-1]);
    foreach($products as $parent) {
        if($parent->is_virtual() || !$parent->is_visible()) continue;
        $variants=$parent->is_type('variable') ? array_filter(array_map('wc_get_product',$parent->get_children())) : [$parent];
        foreach($variants as $p) {
            if($p->is_type('variation')) {
                if(!$p->variation_is_visible()) continue;
                $allowed=$parent->get_variation_attributes();
                foreach($p->get_attributes() as $key=>$value) {
                    if($value!=='' && isset($allowed[$key]) && !in_array($value,$allowed[$key],true)) continue 2;
                }
            }
            $img=$p->get_image_id() ?: $parent->get_image_id();$image=$img ? wp_get_attachment_image_url($img,'full') : '';if(!$image || $p->get_price()==='' || !$p->is_purchasable()) continue;
            $x->startElement('item');$x->writeElement('g:id',(string)$p->get_id());$x->writeElement('g:title',html_entity_decode(wp_strip_all_tags($p->get_name()),ENT_QUOTES,'UTF-8'));
            $description=$p->get_short_description() ?: ($parent->get_short_description() ?: $parent->get_description());
            $x->writeElement('g:description',wp_strip_all_tags(strip_shortcodes($description)) ?: $parent->get_name());$x->writeElement('g:link',$p->get_permalink());$x->writeElement('g:image_link',$image);$x->writeElement('g:price',number_format((float)wc_get_price_to_display($p),2,'.','').' KES');$x->writeElement('g:availability',$p->is_in_stock()?'in_stock':'out_of_stock');
            $kind=$parent->get_meta('_cd_growth_item_type');if($kind)$x->writeElement('g:product_type','Hair & Beauty > '.$kind);
            $material=$parent->get_meta('_cd_growth_material');if($material)$x->writeElement('g:material',$material);
            if($p->is_type('variation'))$x->writeElement('g:item_group_id',(string)$parent->get_id());
            $gtin=$p->get_meta('_global_unique_id');if(preg_match('/^(\d{8}|\d{12}|\d{13}|\d{14})$/',(string)$gtin))$x->writeElement('g:gtin',$gtin);
            $brands=wp_get_post_terms($parent->get_id(),'product_brand',['fields'=>'names']);if(!is_wp_error($brands) && $brands)$x->writeElement('g:brand',$brands[0]);
            $x->endElement();
        }
    }
    $x->endElement();$x->endElement();$x->endDocument();echo $x->outputMemory();exit;
},-20);
