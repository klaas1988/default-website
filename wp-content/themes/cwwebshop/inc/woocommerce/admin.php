<?php
/**
 * Setup WooCommerce specific admin functions
 *
 * WARNING: This file is part of the PrimaShop parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category   PrimaShop
 * @package    WooCommerce
 * @subpackage Admin
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Show product attributes on menus management page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'woocommerce_attribute_show_in_nav_menus', 'prima_attribute_show_in_nav_menus' );
function prima_attribute_show_in_nav_menus( $show ) {
	return true;
}

/**
 * Hide some metaboxes on Shop page for the shake of consistency.
 *
 * @since PrimaShop 1.0.3
 */
add_action( 'admin_head-post.php', 'prima_wc_hide_metabox_shop' );
function prima_wc_hide_metabox_shop() {
	if ( isset( $_GET['post'] ) )
		$post_id = $post_ID = (int) $_GET['post'];
	elseif ( isset( $_POST['post_ID'] ) )
		$post_id = $post_ID = (int) $_POST['post_ID'];
	else
		$post_id = $post_ID = 0;
	$shop_page_id 	= woocommerce_get_page_id( 'shop' );

	if ( $shop_page_id != $post_id )
		return;
    ?>
	<style type="text/css">
	.prima_meta_id_prima_layout, .prima_meta_id_page_breadcrumb_hide, .prima_meta_id_page_title_hide, #prima_sidebar_settings_metabox_form { display: none; }
	</style>
	<?php 
}
