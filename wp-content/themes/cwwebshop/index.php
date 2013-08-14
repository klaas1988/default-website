<?php
/**
 * The main template file.
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

get_header(); ?>

<section id="main" role="main" class="group">
  <div class="margin group">
  
    <div class="content-wrap group">
  
	<div id="content" class="group">
	<?php if (have_posts()) : ?>
	
	  <?php if ( !prima_get_setting( 'breadcrumb_hide_archives' ) ) : ?>
	    <?php prima_breadcrumb(); ?>
	  <?php endif; ?>
	  
	<?php while (have_posts()) : the_post(); ?>
	  
	  <?php get_template_part( 'content', prima_get_setting( 'content_layout' ) ); ?>

	<?php endwhile; ?>
	
	  <?php get_template_part( 'navigation', prima_get_setting( 'content_navigation' ) ); ?>
	  
	<?php else: ?>
	
	  <?php get_template_part( 'content', '404' ); ?>
	  
	<?php endif; ?>
	</div>
	
	<?php prima_sidebar( 'sidebar' ); ?>
	
	</div>
	
	<?php prima_sidebar( 'sidebarmini' ); ?>
	
  </div>
</section>

<?php get_footer(); ?>