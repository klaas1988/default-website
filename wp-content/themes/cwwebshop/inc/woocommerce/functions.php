<?php
/**
 * Setup WooCommerce specific functions
 *
 * WARNING: This file is part of the PrimaShop parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category   PrimaShop
 * @package    WooCommerce
 * @subpackage Function
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Declare theme support for WooCommerce.
 *
 * @since PrimaShop 1.0
 */
add_theme_support( 'woocommerce' );

/**
 * Return WooCommerce shop page url.
 *
 * @since PrimaShop 1.0
 */
function prima_get_wc_shop_url() {
	return get_permalink(woocommerce_get_page_id('shop'));
}

/**
 * Echo WooCommerce shop page url.
 *
 * @since PrimaShop 1.0
 */
function prima_wc_shop_url() {
	echo prima_get_wc_shop_url();
}

/**
 * Return WooCommerce my account page url.
 *
 * @since PrimaShop 1.0
 */
function prima_get_wc_myaccount_url() {
	return get_permalink(woocommerce_get_page_id('myaccount'));
}

/**
 * Echo WooCommerce my account page url.
 *
 * @since PrimaShop 1.0
 */
function prima_wc_myaccount_url() {
	echo prima_get_wc_myaccount_url();
}

/**
 * Return WooCommerce cart page url.
 *
 * @since PrimaShop 1.0
 */
function prima_get_wc_cart_url() {
	global $woocommerce;
	return $woocommerce->cart->get_cart_url();
}

/**
 * Echo WooCommerce cart page url.
 *
 * @since PrimaShop 1.0
 */
function prima_wc_cart_url() {
	echo prima_get_wc_cart_url();
}

/**
 * Return WooCommerce checkout page url.
 *
 * @since PrimaShop 1.0
 */
function prima_get_wc_checkout_url() {
	global $woocommerce;
	return $woocommerce->cart->get_checkout_url();
}

/**
 * Echo WooCommerce checkout page url.
 *
 * @since PrimaShop 1.0
 */
function prima_wc_checkout_url() {
	echo prima_get_wc_checkout_url();
}

/**
 * Return WooCommerce cart total.
 *
 * @since PrimaShop 1.0
 */
function prima_get_wc_cart_total(){
	global $woocommerce;
	return $woocommerce->cart->get_total();
}

/**
 * Echo WooCommerce cart total.
 *
 * @since PrimaShop 1.0
 */
function prima_wc_cart_total() {
	echo prima_get_wc_cart_total();
}

/**
 * Return WooCommerce cart count.
 *
 * @since PrimaShop 1.0
 */
function prima_get_wc_cart_count(){
	global $woocommerce;
	return $woocommerce->cart->cart_contents_count;
}

/**
 * Echo WooCommerce cart count.
 *
 * @since PrimaShop 1.0
 */
function prima_wc_cart_count() {
	echo prima_get_wc_cart_count();
}

/**
 * Custom WooCommerce product search.
 *
 * @since PrimaShop 1.0
 */
function prima_product_search( $id = 'searchform', $post_type = 'product' ) {
?>
	<form role="search" method="get" id="<?php echo $id ?>" action="<?php echo esc_url( home_url() ); ?>">
		<div>
			<label class="screen-reader-text" for="s"><?php _e('Search for:', 'primathemes'); ?></label>
			<input type="text" value="<?php the_search_query(); ?>" name="s" class="searchinput" placeholder="<?php _e('Search for products', 'primathemes'); ?>" />
			<input type="submit" class="searchsubmit" value="<?php _e('Search', 'primathemes'); ?>" />
			<input type="hidden" name="post_type" value="<?php echo $post_type ?>" />
		</div>
	</form>
<?php
}

/**
 * Return WooCommerce product category image.
 *
 * @since PrimaShop 1.0
 */
function prima_get_productcat_image( $args = array() ) {
	$defaults = array(
		'term_id' => false,
		'link_to' => false,
		'size' => false,
		'width' => 150,
		'height' => 150,
		'crop' => true,
		'default_image' => false,
		'image_class' => false,
		'output' => 'image',
		'before' => '',
		'after' => '',
		'link_to_post' => false,
		'meta_key' => false,
		'attachment' => false,
		'the_post_thumbnail' => false,
	);
	$args = wp_parse_args( $args, $defaults );
	if ( !$args['term_id'] )
		return false;
	$args['image_id'] = get_woocommerce_term_meta( $args['term_id'], 'thumbnail_id', true );
	if ( !$args['image_id'] )
		return false;
	return prima_get_image( $args );
}

/**
 * Echo WooCommerce product category image.
 *
 * @since PrimaShop 1.0
 */
function prima_productcat_image( $args = array() ) {
	echo prima_get_productcat_image( $args );
}

/**
 * Product Attribute Query class.
 *
 * @since PrimaShop 1.0
 */
if ( !is_admin() || defined('DOING_AJAX') ) :
if ( class_exists( 'WC_Query' ) ) :
class Prima_Attr_Query extends WC_Query {
	function pre_get_posts( $q ) {
	    if ( !is_main_query() || !$q->is_tax ) 
			return;
		$term = get_queried_object();
		if ( !isset($term->taxonomy) ) 
			return;
		if ( strpos($term->taxonomy, 'pa_') !== 0 ) 
			return;
	    $this->product_query( $q );
	    add_action('wp', array( &$this, 'get_products_in_view' ), 2);
	    remove_filter( 'pre_get_posts', array( &$this, 'pre_get_posts') );
	}
}
$Prima_Attr_Query = new Prima_Attr_Query();
endif;
endif;

/**
 * Add products shortcode generator button to visual editor.
 *
 * @since PrimaShop 1.0
 */
add_action( 'init', 'prima_wc_shortcode_add_button' );
function prima_wc_shortcode_add_button() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
	if ( get_user_option('rich_editing') == 'true') :
		add_filter('mce_external_plugins', 'prima_wc_shortcode_add_tinymce_plugin');
		add_filter('mce_buttons', 'prima_wc_shortcode_register_button');
	endif;
}

/**
 * Register products shortcode generator button.
 *
 * @since PrimaShop 1.0
 */
function prima_wc_shortcode_register_button($buttons) {
	array_push($buttons, "prima_shortcodes_products_button");
	return $buttons;
}

/**
 * Register products shortcode generator javascript.
 *
 * @since PrimaShop 1.0
 */
function prima_wc_shortcode_add_tinymce_plugin($plugin_array) {
	global $prima;
	$plugin_array['PrimaShortcodesProducts'] = PRIMA_CUSTOM_URI . '/woocommerce/products.sg.js';
	return $plugin_array;
}

/**
 * Add .
 *
 * @since PrimaShop 1.0
 */
add_filter('add_to_cart_fragments', 'prima_wc_top_nav_cartcount_fragment');
function prima_wc_top_nav_cartcount_fragment( $fragments ) {
	$fragments['#topnav ul.topnav-menu li a.topnav-cart-count'] = '<a class="topnav-cart-count" href="'.prima_get_wc_cart_url().'">'.sprintf( __('My Cart (%d)', 'primathemes'), prima_get_wc_cart_count() ).'</a>';
	return $fragments;
}
