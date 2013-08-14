<?php
/**
 * The template for displaying featured header (image/slider/custom).
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

$header = array();
$header['type'] = '';
$header['image']['src'] = '';
$header['image']['url'] = '';

if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product_attribute() || is_product() ) ) {
	$page_id = woocommerce_get_page_id( 'shop' );
	$header = prima_header_featured_from_page( $page_id, $header );
}
if ( is_front_page() && get_option('show_on_front') == 'page' && get_option('page_on_front') > 0 ) {
	$page_id = get_option('page_on_front');
	$header = prima_header_featured_from_page( $page_id, $header );
}
elseif ( is_home() && get_option('show_on_front') == 'page' && get_option('page_for_posts') > 0 ) {
	$page_id = get_option('page_for_posts');
	$header = prima_header_featured_from_page( $page_id, $header );
}
elseif ( is_singular() ) {
	$page_id = $wp_query->post->ID;
	$header = prima_header_featured_from_page( $page_id, $header );
}

if ( $header['type'] == 'disable' ) return;

if ( !$header['type'] ) {
	if ( get_header_image() ) {
		$header['type'] = 'image-default';
		$header['image']['src'] = get_header_image();
		$header['image']['url'] = home_url( '/' );
		$header['nopadding'] = prima_get_setting ( "header_featured_nopadding" );
	}
	else {
		return;
	}
}

?>

  <header id="header-featured" class="header-<?php echo $header['type']; ?> <?php echo ($header['nopadding'] == "true") ? 'header-nopadding' : ''; ?>">
	<div class="margin group">
	  <?php if ( $header['type'] == 'image' || $header['type'] == 'image-default' ) : ?>
	  
	    <?php if ( $header['image']['url'] ) : ?>
		  <a href="<?php echo esc_url( $header['image']['url'] ); ?>">
		    <img src="<?php echo esc_url( $header['image']['src'] ); ?>" alt="" />
		  </a>
	    <?php else : ?>
		  <img src="<?php echo esc_url( $header['image']['src'] ); ?>" alt="" />
	    <?php endif; ?>
		
	  <?php elseif ( $header['type'] == 'slider' ) : ?>
	  
		<div class="flexslider">
		  <ul class="slides">
			<?php foreach ( $header['slider'] as $slider_src ) : ?>
			  <li>
				<?php if ( $slider_src['url'] ) : ?>
				  <a href="<?php echo esc_url( $slider_src['url'] ); ?>">
					<img src="<?php echo esc_url( $slider_src['src'] ); ?>" alt="" />
				  </a>
				<?php else : ?>
				  <img src="<?php echo esc_url( $slider_src['src'] ); ?>" alt="" />
				<?php endif; ?>
			  </li>
			<?php endforeach; ?>
		  </ul>
		</div>
	    <?php 
		add_action('prima_custom_scripts', 'prima_scripts_header_slider');
		function prima_scripts_header_slider() {
		  echo 'jQuery(window).load(function() {';
		  echo 'jQuery("#header-featured .flexslider").flexslider({';
		  echo 'pauseOnHover: "true", ';
		  $animation = prima_get_post_meta( "_prima_header_slider_animation", $page_id );
		  if ( !$animation ) $animation = 'slide';
		  echo 'animation: "'.$animation.'", ';
		  $slideshowspeed = prima_get_post_meta( "_prima_header_slider_slideshowspeed", $page_id );
		  if ( is_numeric($slideshowspeed) )
		    echo 'slideshowSpeed: '.$slideshowspeed.', ';
		  $animationspeed = prima_get_post_meta( "_prima_header_slider_animationspeed", $page_id );
		  if ( is_numeric($animationspeed) )
		    echo 'animationSpeed: '.$animationspeed.', ';
		  echo 'slideshow: true';
		  echo '});';
		  echo '});';
		  echo "\n";
		}
		?>
		
	  <?php elseif ( $header['type'] == 'custom' ) : ?>
	  
	    <?php echo do_shortcode( shortcode_unautop( wpautop( $header['custom'] ) ) ); ?>
		
	  <?php endif; ?>
	</div>
  </header>
