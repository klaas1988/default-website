<?php
/**
 * The template for displaying footer area.
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

  <footer id="footer" class="group">
	<div class="margin group">
	  <div class="footer-left">
		<?php echo do_shortcode( shortcode_unautop( wpautop( prima_get_setting( 'footer_content' ) ) ) ); ?>
	  </div>
	  <div class="footer-right">
		<?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'fallback_cb' => '', 'echo' => true, 'depth' => 1, 'container' => false, 'menu_id' => 'footer-menu', 'menu_class' => 'footer-menu group' ) ); ?>
	  </div>
	</div>
  </footer>
