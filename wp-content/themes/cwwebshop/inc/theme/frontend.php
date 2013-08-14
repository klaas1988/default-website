<?php
/**
 * Setup theme specific frontend functions
 *
 * WARNING: This file is part of the PrimaShop parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category   PrimaShop
 * @package    Setup
 * @subpackage Frontend
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add main stylesheet file to <head> section.
 *
 * @since PrimaShop 1.0
 */
add_action( 'wp_enqueue_scripts', 'prima_styles_theme', 15 );
function prima_styles_theme() {
	wp_enqueue_style('style-theme', get_bloginfo('stylesheet_url'), false, '1.0', 'screen, projection');
}

/**
 * Add responsive stylesheet file to <head> section.
 *
 * @since PrimaShop 1.0
 */
add_action( 'wp_enqueue_scripts', 'prima_styles_responsive', 15);
function prima_styles_responsive() {
	$responsive = prima_get_setting( 'responsive' );
	if ( $responsive == 'no' ) return;
	wp_enqueue_style('style-responsive', prima_childtheme_file('style-responsive.css'), false, '1.0', 'screen, projection');
}

/**
 * Add responsive meta tag to <head> section.
 *
 * @since PrimaShop 1.0
 */
add_action( 'wp_head', 'prima_meta_responsive' );
function prima_meta_responsive() {
	$responsive = prima_get_setting( 'responsive' );
	if ( $responsive == 'no' ) return;
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">'."\n";
}

/**
 * Add IE-compatible meta tag to <head> section.
 *
 * @since PrimaShop 1.0
 */
add_action( 'wp_head', 'prima_meta_iecompatible' );
function prima_meta_iecompatible() {
	echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />'."\n";
}

/**
 * Add html5shiv-printshiv.js file to support old IE browser to <head> section.
 *
 * @since PrimaShop 1.0
 */
add_action( 'wp_head', 'prima_ie_js_html5shiv' );
function prima_ie_js_html5shiv() {
?>
<!--[if lt IE 9]>
<script src="<?php echo PRIMA_URI; ?>/js/html5shiv-printshiv.js"></script>
<![endif]-->
<?php
}

/**
 * Add respond.min.js file to support media queries for old IE browser to <head> section.
 *
 * @since PrimaShop 1.0
 */
add_action( 'wp_head', 'prima_ie_js_responsive' );
function prima_ie_js_responsive() {
	$responsive = prima_get_setting( 'responsive' );
	if ( $responsive == 'no' ) return;
?>
<!--[if lt IE 9]>
<script src="<?php echo PRIMA_URI; ?>/js/respond.min.js"></script>
<![endif]-->
<?php
}

/**
 * Add responsive status class to <body> class.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'body_class', 'prima_responsive_class' );
function prima_responsive_class( $classes ) {
	$responsive = prima_get_setting( 'responsive' );
	if ( $responsive == 'yes' ) 
		$classes[] = 'responsive-yes';
	else
		$classes[] = 'responsive-no';
	return $classes;
}

/**
 * Add style layout class (full/boxed/custom) to <body> class.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'body_class', 'prima_style_layout_class' );
function prima_style_layout_class( $classes ) {
	$style = prima_get_setting( 'style' );
	if (!$style) $style = 'boxed';
	$classes[] = 'stylelayout-'.$style;
	return $classes;
}

/**
 * Add header logo layout class to <body> class.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'body_class', 'prima_header_logo_class' );
function prima_header_logo_class( $classes ) {
	$logo = prima_get_setting( 'header_logo' );
	if ($logo) $classes[] = 'header-logo-active';
	return $classes;
}

/**
 * Add footer layout class to <body> class.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'body_class', 'prima_footer_body_classes' );
function prima_footer_body_classes($classes) {
	if( (int)prima_get_setting('footer_widgets') > 0 )
		$classes[] = 'footer-widgets-'.prima_get_setting('footer_widgets');
	return $classes; 
}

/**
 * Output header top navigation template file.
 *
 * @since PrimaShop 1.0
 */
add_action( 'prima_header', 'prima_header_topnav_output' );
function prima_header_topnav_output() {
	if ( !prima_get_setting( 'topnav' ) )
		return;
	get_template_part( 'prima-header-topnav' );
}

/**
 * Output header content (menu+logo) template file.
 *
 * @since PrimaShop 1.0
 */
add_action( 'prima_header', 'prima_header_content_output' );
function prima_header_content_output() {
	get_template_part( 'prima-header-content' );
}

/**
 * Output header logo stylesheet to "custom styles" area on <head> section.
 *
 * @since PrimaShop 1.0
 */
add_action( 'prima_custom_styles', 'prima_custom_styles_header_logo' );
function prima_custom_styles_header_logo() {
	$logo = prima_get_setting( 'header_logo' );
	if (!$logo) return;
	echo '.header-logo-active #header-title { background: url('.$logo.') no-repeat left center; }';
}

/**
 * Function to detect supported featured image types (image/slider/custom/disable) for any post type.
 *
 * @since PrimaShop 1.0
 */
function prima_header_featured_from_page( $page_id, $header = array() ) {
	$header_type = prima_get_post_meta( "_prima_header_featured", $page_id );
	if ( $header_type == 'disable' ) {
		$header['type'] = 'disable';
	}
	elseif ( $header_type == 'custom' && prima_get_post_meta( "_prima_header_custom", $page_id ) ) {
		$header['type'] = 'custom';
		$header['custom'] = prima_get_post_meta( "_prima_header_custom", $page_id );
		$header['nopadding'] = prima_get_post_meta( "_prima_header_nopadding", $page_id );
	}
	elseif ( $header_type == 'slider' && prima_get_post_meta( "_prima_header_image_1", $page_id ) ) {
		$header['type'] = 'slider';
		$header['slider'] = array();
		for ($i = 1; $i <= 5; $i++) {
			$slider_src = prima_get_post_meta( "_prima_header_image_$i", $page_id );
			if ($slider_src) {
				$header['slider'][$i]['src'] = $slider_src;
				$header['slider'][$i]['url'] = prima_get_post_meta( "_prima_header_image_url_$i", $page_id );
			}	
		}
		$header['nopadding'] = prima_get_post_meta( "_prima_header_nopadding", $page_id );
	}
	elseif ( prima_get_post_meta( "_prima_header_image_1", $page_id ) ) {
		$header['type'] = 'image';
		$header['image']['src'] = prima_get_post_meta( "_prima_header_image_1", $page_id );
		$header['image']['url'] = prima_get_post_meta( "_prima_header_image_url_1", $page_id );
		$header['nopadding'] = prima_get_post_meta( "_prima_header_nopadding", $page_id );
	}
	return $header;
}

/**
 * Output featured header template file.
 *
 * @since PrimaShop 1.0
 */
add_action( 'prima_header', 'prima_header_featured_output' );
function prima_header_featured_output() {
	get_template_part( 'prima-header-featured' );
}

/**
 * Output call-to-action template file.
 *
 * @since PrimaShop 1.0
 */
add_action( 'prima_header', 'prima_header_action_output' );
function prima_header_action_output() {
	if ( !prima_get_setting( 'calltoaction' ) ) 
		return;
	get_template_part( 'prima-header-action' );
}

/**
 * Output footer widgets area template file.
 *
 * @since PrimaShop 1.0
 */
add_action( 'prima_footer', 'prima_footer_widgets_output' );
function prima_footer_widgets_output() {
	if( (int)prima_get_setting('footer_widgets') > 0 )
		get_template_part( 'prima-footer-widgets' );
}

/**
 * Output footer content template file.
 *
 * @since PrimaShop 1.0
 */
add_action( 'prima_footer', 'prima_footer_content_output' );
function prima_footer_content_output() {
	get_template_part( 'prima-footer-content' );
}

/**
 * Output footer query counter template file.
 *
 * @since PrimaShop 1.0
 */
add_action( 'prima_after', 'prima_footer_query_output' );
function prima_footer_query_output() {
	if ( prima_get_setting('footer_query') && current_user_can( 'edit_themes' ) )
		get_template_part( 'prima-footer-query' );
}

/**
 * Add simple custom script to remove "no-js" class when page is completely loaded.
 *
 * @since PrimaShop 1.0
 */
add_action('prima_custom_scripts', 'prima_scripts_remove_nojs');
function prima_scripts_remove_nojs() {
	echo 'jQuery(window).load(function() {';
	echo 'jQuery("body").removeClass("no-js")';
	echo '});';
	echo "\n";
}

/**
 * Filter function to count comment type only (trackback excluded).
 *
 * @since PrimaShop 1.0
 */
add_filter('get_comments_number', 'prima_comment_count', 0);
function prima_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
		return count($comments_by_type['comment']);
	} else {
		return $count;
	}
}

/**
 * Filter function for better search form output.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'get_search_form', 'prima_custom_search_form' );
function prima_custom_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div>
    <input type="text" value="'.__( 'Search...', 'primathemes' ).'" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr(__( 'Search', 'primathemes' )) .'" />
    </div>
    </form>';
    return $form;
}

/**
 * Change default excerpt words length.
 *
 * @since PrimaThemes 2.0
 */
add_filter( 'excerpt_length', 'prima_excerpt_length' );
function prima_excerpt_length( $length ) {
	return 70;
}

/**
 * Return continue reading link.
 *
 * @since PrimaThemes 2.0
 */
function prima_continue_reading_link() {
	return ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' . __( 'Read More &rarr;', 'primathemes' ) . '</a>';
}

/**
 * Add continue reading link to excerpt.
 *
 * @since PrimaThemes 2.0
 */
add_filter( 'get_the_excerpt', 'prima_custom_excerpt_more' );
function prima_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= prima_continue_reading_link();
	}
	return $output;
}

/**
 * Add continue reading link to auto excerpt.
 *
 * @since PrimaThemes 2.0
 */
add_filter( 'excerpt_more', 'prima_auto_excerpt_more' );
function prima_auto_excerpt_more( $more ) {
	return ' &hellip;' . prima_continue_reading_link();
}
