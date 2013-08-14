<?php
/**
 * Setup metaboxes settings
 *
 * WARNING: This file is part of the PrimaShop parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category   PrimaShop
 * @package    Setup
 * @subpackage Setting
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add Title and Breadcrumb settings of Content metabox (page type) for post editing screen.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_metabox_page_content_args', 'prima_metabox_page_content_args_title' );
function prima_metabox_page_content_args_title( $meta ) {
	$meta[] = array ( 
		"name" => __("Hide Breadcrumb", 'primathemes'),
		"id" => "_page_breadcrumb_hide",
		"type" => "checkbox",
		"default" => "false",
		"desc" => __("This will hide breadcrumb on this page.", 'primathemes'),
		);
	$meta[] = array ( 
		"name" => __("Hide Page Title", 'primathemes'),
		"id" => "_page_title_hide",
		"type" => "checkbox",
		"default" => "false",
		"desc" => __("This will hide page title on this page. NOTE: Some page template is designed with no page title", 'primathemes'),
		);
	return $meta;
}


/**
 * Add Featured Header settings of Header metabox for post editing screen.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_metabox_alltype_header_args', 'prima_metabox_alltype_header_args_featured' );
function prima_metabox_alltype_header_args_featured( $meta ) {
	$meta['header_featured'] = array ( 
		"name" => __("Featured Header Type", 'primathemes'),
		"id" => "_prima_header_featured",
		"type" => "select",
		"default" => "image",
		"options" => array ( 
			'image' => __('Image', 'primathemes'),
			'slider' => __('Slider', 'primathemes'),
			'custom' => __('Custom', 'primathemes'),
			'disable' => __('Disable', 'primathemes'),
			),
		);
	$meta[] = array ( 
		"name" => __("Remove Header Padding", 'primathemes'),
		"id" => "_prima_header_nopadding",
		"type" => "checkbox",
		"default" => "false",
		"desc" => __("This will remove padding (top,left,bottom,right space) on the featured header", 'primathemes'),
		);
	$meta['header_custom'] = array ( 
		"name" => __("Custom Header", 'primathemes'),
		"id" => "_prima_header_custom",
		"type" => "wysiwyg",
		"desc" => __("You can add any content (HTML, shortcodes) for your custom header.", 'primathemes'),
		"class" => "meta_header_featured meta_header_custom",
		);
	$meta['header_slider_animation'] = array ( 
		"name" => __("Slider Animation Type", 'primathemes'),
		"id" => "_prima_header_slider_animation",
		"type" => "select",
		"default" => "slide",
		"options" => array ( 
			'slide' => __('Slide', 'primathemes'),
			'fade' => __('Fade', 'primathemes'),
			),
		);
	$meta["header_slider_slideshowspeed"] = array ( 
		"name" => __("Slider Slideshow Speed", 'primathemes'),
		"id" => "_prima_header_slider_slideshowspeed",
		"type" => "text",
		"desc" =>  __("Set the speed of the slideshow cycling, in milliseconds", 'primathemes'),
		"class" => "meta_header_featured meta_header_slider",
		);
	$meta["header_slider_animationspeed"] = array ( 
		"name" => __("Slider Animation Speed", 'primathemes'),
		"id" => "_prima_header_slider_animationspeed",
		"type" => "text",
		"desc" =>  __("Set the speed of animations, in milliseconds", 'primathemes'),
		"class" => "meta_header_featured meta_header_slider",
		);
	for ($i = 1; $i <= 5; $i++) {
		$class = $i == 1 ? " meta_header_image" : '';
		$meta["header_image_$i"] = array ( 
			"name" => sprintf ( __("Header Image #%d", 'primathemes'), $i ),
			"id" => "_prima_header_image_$i",
			"type" => "upload",
			"desc" => '',
			"class" => "meta_header_featured meta_header_slider".$class,
			);
		$meta["header_image_url_$i"] = array ( 
			"name" => sprintf ( __("Header Image #%d Link", 'primathemes'), $i ),
			"id" => "_prima_header_image_url_$i",
			"type" => "text",
			"desc" => '',
			"class" => "meta_header_featured meta_header_slider".$class,
			);
	}
	return $meta;
}

/**
 * Add Blog Page Template settings of Page Template metabox for post editing screen.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_metabox_page_template_args', 'prima_metabox_page_template_args_blog' );
function prima_metabox_page_template_args_blog( $meta ) {
	$meta['page-blog-contentlayout'] = array ( 
		"name" => __("Content Layout", 'primathemes'),
		"id" => "content_layout",
		"template" => "page_blog",
		"type" => "select",
		"default" => "",
		"options" => array ( 
			'default' => __('Full Text', 'primathemes'),
			'excerpt' => __('Summary', 'primathemes'),
			'featured' => __('Featured Image + Summary', 'primathemes'),
			'thumbnailleft' => __('Left Thumbnail + Summary', 'primathemes'),
			'thumbnailright' => __('Right Thumbnail + Summary', 'primathemes'),
			),
		);
	$meta['page-blog-contentnavigation'] = array ( 
		"name" => __("Content Navigation", 'primathemes'),
		"id" => "content_navigation",
		"template" => "page_blog",
		"type" => "select",
		"default" => "",
		"options" => array ( 
			'prevnext' => __('Previous Page - Next Page', 'primathemes'),
			'oldernewer' => __('Older Posts - Newer Posts', 'primathemes'),
			'numeric' => __('Numeric Navigation', 'primathemes'),
			),
		);
	$meta['page-blog-postsperpage'] = array ( 
		"name" => __("Posts Per Page", 'primathemes'),
		"id" => "postsperpage",
		"template" => "page_blog",
		"type" => "text_small",
		"default" => '',
		"desc" => '',
		);
	return $meta;
}

/**
 * Add custom javascript for Featured Header metaboxes, for better user experience.
 *
 * @since PrimaShop 1.0
 */
add_action( 'admin_head-post.php', 'prima_metabox_header_featured_scripts' );
add_action( 'admin_head-post-new.php', 'prima_metabox_header_featured_scripts' );
function prima_metabox_header_featured_scripts() {
    ?>
	<script type="text/javascript">
	/*<![CDATA[*/
	jQuery(document).ready(function($){
		/* Check if featured header dropdown exists */
		if ( $('#prima-_prima_header_featured').length ) { 
			var header_featured = '';
			header_featured = $('#prima-_prima_header_featured').val();
			
			$('.meta_header_featured').hide();
			if ( header_featured == '' || header_featured == 'image' ) {
				$('.meta_header_image').show();
			}
			else if ( header_featured == 'slider' ) {
				$('.meta_header_slider').show();
			}
			else if ( header_featured == 'custom' ) {
				$('.meta_header_custom').show();
			}
			
			$('#prima-_prima_header_featured').change( function () {
				header_featured = $(this).val();
				$('.meta_header_featured').hide();
				if ( header_featured == '' || header_featured == 'image' ) {
					$('.meta_header_image').show();
				}
				else if ( header_featured == 'slider' ) {
					$('.meta_header_slider').show();
				}
				else if ( header_featured == 'custom' ) {
					$('.meta_header_custom').show();
				}
			});
		}
	});
	/*]]>*/
	</script>
	<?php 
}

/**
 * Add custom javascript for Page Template metaboxes, for better user experience.
 *
 * @since PrimaShop 1.0
 */
add_action( 'admin_head-post.php', 'prima_metabox_page_template_scripts' );
add_action( 'admin_head-post-new.php', 'prima_metabox_page_template_scripts' );
function prima_metabox_page_template_scripts() {
    ?>
	<script type="text/javascript">
	/*<![CDATA[*/
	jQuery(document).ready(function($){
		/* Check if page template dropdown exists */
		if ( $('#page_template').length ) { 
			var template = '';
			template = $('#page_template').val();
			template = template.replace(".php", "");
			
			if ( template == '' || template == 'default' ) $('#prima-metabox-page-template').hide();
			else { 
				$('#prima-metabox-page-template').show();
				$('#prima-metabox-page-template tr.meta_template').hide();
				$('#prima-metabox-page-template tr.meta_template_'+template).show();
			}
			
			if ( template == 'page_blog' ) $('#postdivrich, #postexcerpt').hide();
			else $('#postdivrich, #postexcerpt').show();

			$('#page_template').change( function () {
				template = $(this).val();
				template = template.replace(".php", "");
				if ( template == '' || template == 'default' ) $('#prima-metabox-page-template').hide();
				else { 
					$('#prima-metabox-page-template').show();
					$('#prima-metabox-page-template tr.meta_template').hide();
					$('#prima-metabox-page-template tr.meta_template_'+template).show();
				}

				if ( template == 'page_blog' ) $('#postdivrich, #postexcerpt').hide();
				else $('#postdivrich, #postexcerpt').show();
			});
		}
	});
	/*]]>*/
	</script>
	<?php 
}
