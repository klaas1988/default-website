<?php
/**
 * The template for displaying all pages.
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
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	  
	  <?php if ( !prima_get_setting( 'breadcrumb_hide_page' ) && !prima_get_post_meta( '_page_breadcrumb_hide' ) ) : ?>
	    <?php prima_breadcrumb(); ?>
	  <?php endif; ?>
	
	  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
		<div class="entry">

		  <?php if ( !prima_get_post_meta( '_page_title_hide' ) ) : ?>
		    <h1 class="post-title"><?php the_title(); ?></h1>
		  <?php endif; ?>
		  
		  <div class="post-content group">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-link"><span>' . __( 'Pages:', 'primathemes' ) . '</span>', 'after' => '</p>' ) ); ?>
			<?php edit_post_link( __( 'Edit', 'primathemes' ), '<p>', '</p>' ); ?>
		  </div>

		</div>
	  </article>

	<?php endwhile; ?>

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