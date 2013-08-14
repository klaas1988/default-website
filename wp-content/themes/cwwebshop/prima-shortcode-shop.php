<?php
/**
 * The template for displaying products shortcode.
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

global $woocommerce, $prima_products_atts;
$atts = $prima_products_atts;

$prima_catalog_loop = 0;
$prima_catalog_column = $atts['columns'];
?>

<?php if (have_posts()) : ?>

<div class="woocommerce ps-products">

<?php if ( trim( $atts['title'] ) ) echo '<h2 class="ps-products-title"><span>' . $atts['title'] . '</span></h2>'; ?>

<?php 
if ( trim( $atts['title'] ) && $atts['link_to_shop'] == 'yes' ) 
	echo '<a class="ps-products-link" href="'.prima_get_wc_shop_url().'">'.__('Visit Shop &rarr;', 'primathemes').'</a>';
?>

<ul class="products products-col-<?php echo $prima_catalog_column ?> ">

	<?php 
	while (have_posts()) : the_post(); 
	
		global $product;
		if (!$product->is_visible()) continue; 
		
		$prima_catalog_loop++;
		$product_class = $prima_catalog_loop%2 ? ' odd' : ' even';
		if ($prima_catalog_loop%$prima_catalog_column==0) $product_class .= ' last';
		if (($prima_catalog_loop-1)%$prima_catalog_column==0) $product_class .= ' first';
		?>
		
		<li class="product<?php echo $product_class; ?>">
			
			<?php if ( $atts['product_image'] == 'yes' ) : ?>
			<div class="product-image-box">
			<a href="<?php the_permalink() ?>">
			<?php
				$img_args = array();
				$img_args['width'] = $atts['image_width'];
				$img_args['height'] = $atts['image_height'];
				$img_args['crop'] = ( $atts['image_crop'] == 'yes' ? true : false );
				$img_args['default_image'] = woocommerce_placeholder_img_src();
				$img_args['image_scan'] = false;
				$img_args['link_to_post'] = false;
				prima_image($img_args);
			?>
			</a>
			</div>
			<?php endif; ?>
			
			<?php if ( $atts['product_saleflash'] == 'yes' ) woocommerce_show_product_loop_sale_flash(); ?>
			
			<?php if ( $atts['product_title'] == 'yes' ) echo '<a href="'.get_permalink().'"><h3>'.get_the_title().'</h3></a>'; ?>
	
			<?php if ( $atts['product_rating'] == 'yes' ) woocommerce_template_loop_rating(); ?>
			
			<?php if ( $atts['product_price'] == 'yes' ) woocommerce_template_loop_price(); ?>
			
			<?php if ( $atts['product_excerpt'] == 'yes' ) prima_wc_product_excerpt(); ?>
			
			<?php if ( $atts['product_button'] == 'yes' ) woocommerce_template_loop_add_to_cart(); ?>
			
		</li><?php 
		
	endwhile;
	
	?>

</ul>

</div>

<?php else : ?>

	<?php echo '<p class="info">'.__('No products found which match your selection.', 'primathemes').'</p>'; ?>

<?php endif; ?>