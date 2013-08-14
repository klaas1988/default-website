<?php
/**
 * The template for displaying header content (logo and menu).
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

  <header id="header" class="group">
	<div class="margin group">
	  <div id="header-title" class="group">
		<h1 class="site-title">
		  <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>
		</h1>
	  </div>
	  <div id="header-menu" class="group">
		<?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'fallback_cb' => '', 'echo' => true, 'container' => false, 'menu_id' => 'menu-primary', 'menu_class' => 'sf-menu menu-primary' ) ); ?>	
	  </div>
	</div>
  </header>