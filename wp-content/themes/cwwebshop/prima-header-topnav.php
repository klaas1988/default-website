<?php
/**
 * The template for displaying top navigation.
 *
 * WARNING: This file is part of the PrimaShop parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category PrimaShop
 * @package  Templates
 * @author   PrimaThemes
 * @link     http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

  <header id="topnav" class="group">
	<div class="margin group">
	  <div class="topnav-left">
		<?php echo do_shortcode( shortcode_unautop( wpautop( prima_get_setting( 'topnav_message' ) ) ) ); ?>
	  </div>
	  <div class="topnav-right">
	    <ul class="topnav-menu">
		
		<?php if ( prima_get_setting( 'topnav_myaccount' ) && function_exists( 'prima_wc_myaccount_url' ) ) : ?>
		  <li><a href="<?php prima_wc_myaccount_url(); ?>"><?php _e( 'My Account', 'primathemes' ); ?></a></li>
		<?php endif; ?>
		
		<?php if ( prima_get_setting( 'topnav_login' ) && function_exists( 'prima_wc_myaccount_url' ) ) : ?>
		  <?php if ( is_user_logged_in() ) : ?>
		    <li><a href="<?php echo wp_logout_url(home_url('/')); ?>"><?php _e( 'Logout', 'primathemes' ); ?></a></li>
		  <?php else : ?>
		    <li><a href="<?php prima_wc_myaccount_url(); ?>"><?php _e( 'Login', 'primathemes' ); ?></a></li>
		  <?php endif; ?>
		<?php endif; ?>
		
		<?php if ( prima_get_setting( 'topnav_cartcount' ) && function_exists( 'prima_wc_cart_url' ) ) : ?>
		  <li class="topnav-cart">
		    <a class="topnav-cart-count" href="<?php prima_wc_cart_url(); ?>">
			  <?php printf( __('My Cart (%d)', 'primathemes'), prima_get_wc_cart_count() ); ?>
		    </a>
		  </li>
		<?php endif; ?>
		
		<?php if ( prima_get_setting( 'topnav_productsearch' ) && function_exists( 'prima_product_search' ) ) : ?>
		  <li class="topnav-search"><?php prima_product_search(); ?></li>
		<?php endif; ?>
		
		</ul>
	  </div>
	</div>
  </header>
