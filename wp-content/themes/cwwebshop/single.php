<?php
/**
 * The template for displaying all single posts.
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
	
	  <?php if ( !prima_get_setting( 'breadcrumb_hide_post' ) ) : ?>
	    <?php prima_breadcrumb(); ?>
	  <?php endif; ?>
	  
	<?php while (have_posts()) : the_post(); ?>
	  
	  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
		<div class="entry">

		  <h1 class="post-title"><?php the_title(); ?></h1>
	  
		  <div class="post-content group">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-link"><span>' . __( 'Pages:', 'primathemes' ) . '</span>', 'after' => '</p>' ) ); ?>
		  </div>

		  <?php echo do_shortcode( prima_get_setting( 'meta_single_'.get_post_type(), null, '<p class="post-meta"><small>%setting%</small></p>' ) ); ?>

		</div>
	  </article>

	  <nav id="nav-single" class="navigation">
		<h3 class="assistive-text"><?php _e( 'Post navigation', 'primathemes' ); ?></h3>
		<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'primathemes' ) ); ?></span>
		<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'primathemes' ) ); ?></span>
	  </nav>
	  
	  <?php comments_template( '', true ); ?>
	
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