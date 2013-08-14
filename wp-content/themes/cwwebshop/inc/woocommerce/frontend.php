<?php
/**
 * Setup WooCommerce specific frontend functions
 *
 * WARNING: This file is part of the PrimaShop parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category   PrimaShop
 * @package    WooCommerce
 * @subpackage Frontend
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Output WooCommerce start wrapper.
 *
 * @since PrimaShop 1.0
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
add_action( 'woocommerce_before_main_content', 'primashop_wrapper_start', 10);
function primashop_wrapper_start() {
	get_template_part( 'woocommerce-wrapper-start' );
}

/**
 * Output WooCommerce end wrapper.
 *
 * @since PrimaShop 1.0
 */
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action( 'woocommerce_after_main_content', 'primashop_wrapper_end', 10);
function primashop_wrapper_end() {
	get_template_part( 'woocommerce-wrapper-end' );
}

/**
 * Remove WooCommerce sidebar, replaced by PrimaThemes sidebar management.
 *
 * @since PrimaShop 1.0
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/**
 * Return true when viewing a product attribute.
 *
 * @since PrimaShop 1.0
 */
if ( ! function_exists( 'is_product_attribute' ) ) {
	function is_product_attribute() {
		if ( is_tax() ) {
			$term = get_queried_object();
			if ( strpos($term->taxonomy, 'pa_') === 0 ) 
				return true;
		}
		return false;
	}
}

/**
 * Add additional WooCommerce body classes.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'body_class', 'prima_wc_body_class' );
function prima_wc_body_class( $classes ) {
	if ( get_option( 'woocommerce_demo_store' ) == 'yes' )
		$classes[] = 'prima-demo-store-active';
	if ( is_product_attribute() ) {
		$classes[] = 'woocommerce woocommerce-page';
	}
	return $classes;
}

/**
 * Setup product attribute archive page template.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'taxonomy_template', 'prima_wc_attributes_template' );
function prima_wc_attributes_template( $template ) {
	$term = get_queried_object();
	if ( strpos($term->taxonomy, 'pa_') === 0 ) 
		return locate_template( array( "product-attribute.php" ) );
	return $template;
}

/**
 * Setup content layout of Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
add_action( 'get_header', 'prima_wc_shop_layout' );
function prima_wc_shop_layout() {
	global $prima_layout;
	$layout = prima_get_setting( 'layout_shop' );
	if ( !$layout ) return;
	if ( is_shop() || is_product_category() || is_product_tag() || is_product_attribute() ) {
		$prima_layout = $layout;
	}
}

/**
 * Setup custom sidebar of Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
add_action( 'get_header', 'prima_wc_shop_sidebars' );
function prima_wc_shop_sidebars() {
	if ( !current_theme_supports('prima-sidebar-settings') ) return;
	global $prima_registered_sidebar_areas, $prima_sidebar;
	if ( empty( $prima_registered_sidebar_areas ) ) return;
	foreach ( $prima_registered_sidebar_areas as $sidebar_area ) {
		$id = $sidebar_area['id'];
		$sidebar = prima_get_setting( "{$id}_shop" );
		if ( $sidebar ) {
			if ( is_shop() || is_product_category() || is_product_tag() || is_product_attribute() ) {
				$prima_sidebar[$id] = $sidebar;
			}
		}
	}
}

/**
 * Setup show/hide breadcrumb of Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
add_action( 'get_header', 'prima_wc_hide_breadcrumb_shop' );
function prima_wc_hide_breadcrumb_shop() {
	if ( is_shop() || is_product_category() || is_product_tag() || is_product_attribute() ) {
		if ( prima_get_setting( 'breadcrumb_hide_shop' ) ) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
		}
	}
}

/**
 * Show/hide result count on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_resultcount_hide') )
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

/**
 * Show/hide catalog ordering on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_catalogordering_hide') )
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/**
 * Setup custom products per page on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
add_filter('loop_shop_per_page', 'prima_wc_shop_perpage');
function prima_wc_shop_perpage() {
	$perpage = prima_get_setting( 'shop_perpage' );
	if ( !$perpage ) $perpage = 12;
    return $perpage;
}

/**
 * Setup custom columns on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
add_filter('loop_shop_columns', 'prima_wc_shop_columns');
function prima_wc_shop_columns() {
	$columns = prima_get_setting( 'shop_columns' );
	if ( !$columns ) $columns = 4;
    return $columns;
}

/**
 * Add products columns in shop page body class.
 *
 * @since PrimaShop 1.0.3
 */
add_filter( 'body_class', 'prima_wc_shop_body_class' );
function prima_wc_shop_body_class( $classes ) {
	if ( is_shop() || is_product_category() || is_product_tag() || is_product_attribute() ) {
		$columns = prima_get_setting('shop_columns');
		if ( !$columns ) $columns = 4; 
		$classes[] = 'woocommerce-products-col-'.$columns;
	}
	return $classes;
}

/**
 * Show/hide product sale flash on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_sale_hide') )
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);

/**
 * Show/hide product title on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_title_hide') ) {
	add_action( 'prima_custom_styles', 'prima_wc_hide_shop_title' );
	function prima_wc_hide_shop_title() {
		if ( is_cart() || is_product() || is_shop() || is_product_category() || is_product_tag() || is_product_attribute() ) {
			echo '.woocommerce ul.products li.product h3, .woocommerce-page ul.products li.product h3 {display:none;}';
		}
	}
}

/**
 * Show/hide product rating on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_rating_hide') )
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

/**
 * Show/hide product price on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_price_hide') ) 
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

/**
 * Show/hide product excerpt on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_excerpt_show') )
	add_action( 'woocommerce_after_shop_loop_item', 'prima_wc_product_excerpt', 5);

/**
 * Output product excerpt on Shop page.
 *
 * @since PrimaShop 1.0.3
 */
function prima_wc_product_excerpt() {
	global $post;
	if ( ! $post->post_excerpt ) return;
	echo '<div class="ps-product-excerpt">';
	echo apply_filters( 'woocommerce_short_description', $post->post_excerpt );
	echo '</div>';
}

/**
 * Show/hide product add to cart button on Shop page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_addtocart_hide') )
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

/**
 * Setup show/hide breadcrumb of single product page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
add_action( 'get_header', 'prima_wc_hide_breadcrumb_product' );
function prima_wc_hide_breadcrumb_product() {
	if ( is_product() ) {
		if ( prima_get_setting( 'breadcrumb_hide_product' ) ) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
		}
	}
}

/**
 * Show/hide product sale flash on single product page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('product_sale_hide') )
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

/**
 * Show/hide product price on single product page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('product_price_hide') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

/**
 * Show/hide product summaries (excerpt) on single product page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('product_excerpt_hide') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

/**
 * Show/hide product add to cart button on single product page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('product_addtocart_hide') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

/**
 * Show/hide product meta on single product page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('product_meta_hide') )
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

/**
 * Setup product data tabs position on single product page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('product_datatabs') == 'right' ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
	add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60);
}

/**
 * Show/hide related products on single product page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_related_hide') ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}

/**
 * Output related products on single product page.
 *
 * @since PrimaShop 1.0
 */
if ( ! function_exists( 'woocommerce_output_related_products' ) ) {
	function woocommerce_output_related_products() {
		$posts_per_page = prima_get_setting('shop_related_perpage');
		if ( !$posts_per_page ) $posts_per_page = 3; 
		$columns = prima_get_setting('shop_related_columns');
		if ( !$columns ) $columns = 3; 
		woocommerce_related_products( $posts_per_page, $columns );
	}
}

/**
 * Add related products column body class.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'body_class', 'prima_wc_related_body_class' );
function prima_wc_related_body_class( $classes ) {
	if ( !is_product() ) return $classes;
	$columns = prima_get_setting('shop_related_columns');
	if ( !$columns ) $columns = 3; 
	$classes[] = 'woocommerce-related-col-'.$columns;
	return $classes;
}

/**
 * Show/hide up-sells on single product page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_upsells_hide') ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
}

/**
 * Output up-selss on single product page.
 *
 * @since PrimaShop 1.0
 */
if ( ! function_exists( 'woocommerce_upsell_display' ) ) {
	function woocommerce_upsell_display( $posts_per_page = 3, $columns = 3, $orderby = 'rand' ) {
		$posts_per_page = prima_get_setting('shop_upsells_perpage');
		if ( !$posts_per_page ) $posts_per_page = 3; 
		$columns = prima_get_setting('shop_upsells_columns');
		if ( !$columns ) $columns = 3; 
		woocommerce_get_template( 'single-product/up-sells.php', array(
				'posts_per_page'  => $posts_per_page,
				'orderby'    => $orderby,
				'columns'    => $columns
			) );
	}
}

/**
 * Add up-sells column body class.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'body_class', 'prima_wc_upsells_body_class' );
function prima_wc_upsells_body_class( $classes ) {
	if ( !is_product() ) return $classes;
	$columns = prima_get_setting('shop_upsells_columns');
	if ( !$columns ) $columns = 3; 
	$classes[] = 'woocommerce-upsells-col-'.$columns;
	return $classes;
}

/**
 * Show/hide cross-sells on cart page, based on theme settings.
 *
 * @since PrimaShop 1.0
 */
if ( prima_get_setting('shop_crosssells_hide') ) {
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
}
