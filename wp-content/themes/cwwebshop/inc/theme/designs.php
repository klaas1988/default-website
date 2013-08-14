<?php
/**
 * Setup theme specific design settings
 *
 * WARNING: This file is part of the PrimaShop parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category   PrimaShop
 * @package    Setup
 * @subpackage Design
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add Style Layout section for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_style' );
function prima_design_settings_args_style( $settings ) {
	$settings[] = array( 
		"name" => __('Style Layout', 'primathemes'),
		"id" => "style_layout",
		"type" => "section",
		"priority" => 121,
		);
	$settings[] = array( 
		"name" => __('Style Layout', 'primathemes'),
		"id" => "style",
		"section" => "style_layout",
		"database" => PRIMA_THEME_SETTINGS,
		"type" => "select",
		"default" => "boxed",
		"options" => array(
			'full' => __('Full', 'primathemes'),
			'boxed' => __('Boxed', 'primathemes'),
			),
		);
	return $settings;
}

/**
 * Add Responsive Layout section for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_responsive' );
function prima_design_settings_args_responsive( $settings ) {
	$settings[] = array( 
		"name" => __('Responsive Layout', 'primathemes'),
		"id" => "responsive",
		"type" => "section",
		"priority" => 122,
		);
	$settings[] = array( 
		"name" => __('Responsive Layout', 'primathemes'),
		"id" => "responsive",
		"section" => "responsive",
		"database" => PRIMA_THEME_SETTINGS,
		"type" => "select",
		"default" => "yes",
		"options" => array(
			'yes' => __('Yes', 'primathemes'),
			'no' => __('No', 'primathemes')
			),
		);
	return $settings;
}

/**
 * Define Google Fonts from Theme Settings.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_fonts', 'prima_fonts_setup_googlefonts' );
function prima_fonts_setup_googlefonts( $fonts ) {
	if ( prima_get_setting('fonts_1_name') || prima_get_setting('fonts_1_url') || prima_get_setting('fonts_1_family') ) {
		$fonts['googlefont1'] = array(
				'id' =>	'googlefont1',
				'type' => 'google',
				'name' => prima_get_setting('fonts_1_name'),
				'fontfamily' => prima_get_setting('fonts_1_family'),
				'googlefonturl' => prima_get_setting('fonts_1_url'),
			);
	}
	if ( prima_get_setting('fonts_2_name') || prima_get_setting('fonts_2_url') || prima_get_setting('fonts_2_family') ) {
		$fonts['googlefont2'] = array(
				'id' =>	'googlefont2',
				'type' => 'google',
				'name' => prima_get_setting('fonts_2_name'),
				'fontfamily' => prima_get_setting('fonts_2_family'),
				'googlefonturl' => prima_get_setting('fonts_2_url'),
			);
	}
	if ( prima_get_setting('fonts_3_name') || prima_get_setting('fonts_3_url') || prima_get_setting('fonts_3_family') ) {
		$fonts['googlefont3'] = array(
				'id' =>	'googlefont3',
				'type' => 'google',
				'name' => prima_get_setting('fonts_3_name'),
				'fontfamily' => prima_get_setting('fonts_3_family'),
				'googlefonturl' => prima_get_setting('fonts_3_url'),
			);
	}
	if ( prima_get_setting('fonts_4_name') || prima_get_setting('fonts_4_url') || prima_get_setting('fonts_4_family') ) {
		$fonts['googlefont4'] = array(
				'id' =>	'googlefont4',
				'type' => 'google',
				'name' => prima_get_setting('fonts_4_name'),
				'fontfamily' => prima_get_setting('fonts_4_family'),
				'googlefonturl' => prima_get_setting('fonts_4_url'),
			);
	}
	return $fonts;
}

/**
 * Add Fonts controls for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_fonts' );
function prima_design_settings_args_fonts( $settings ) {
	$options = array( '' => '' );
	$fonts = prima_fonts();
	if ( $fonts ) {
		foreach ($fonts as $key => $font ) {
			$options[$key] = $font['name'];
		}
	}
	$settings[] = array( 
		"name" => __('Body Font', 'primathemes'),
		"id" => "body_font",
		"type" => "select",
		"default" => "yes",
		"options" => $options,
		);
	$settings[] = array( 
		"name" => __('Heading Font', 'primathemes'),
		"id" => "heading_font",
		"type" => "select",
		"default" => "yes",
		"options" => $options,
		);
	return $settings;
}

/**
 * Echo Fonts css output.
 *
 * @since PrimaShop 1.0
 */
add_action( 'wp_head', 'prima_design_settings_fonts' );
function prima_design_settings_fonts() {
	if ( !current_theme_supports('prima-design-settings') ) return;
	$fonts = prima_fonts();
	$body_font = prima_get_setting('body_font',PRIMA_DESIGN_SETTINGS);
	if ( $body_font && isset($fonts[$body_font]) && isset($fonts[$body_font]['googlefonturl'] ) ) {
		echo "<link href='".$fonts[$body_font]['googlefonturl']."' rel='stylesheet' type='text/css'>\n";
	}	
	$heading_font = prima_get_setting('heading_font',PRIMA_DESIGN_SETTINGS);
	if ( $heading_font && $heading_font!=$body_font && isset($fonts[$heading_font]) && isset($fonts[$heading_font]['googlefonturl'] ) ) {
		echo "<link href='".$fonts[$heading_font]['googlefonturl']."' rel='stylesheet' type='text/css'>\n";
	}	
}


/**
 * Echo Google Fonts stylesheet.
 *
 * @since PrimaShop 1.0
 */
add_action( 'prima_custom_styles', 'prima_design_settings_fonts_stylesheet' );
function prima_design_settings_fonts_stylesheet() {
	if ( !current_theme_supports('prima-design-settings') ) return;
	$fonts = prima_fonts();
	$body_font = prima_get_setting('body_font',PRIMA_DESIGN_SETTINGS);
	if ( $body_font && isset($fonts[$body_font]) && isset($fonts[$body_font]['fontfamily'] ) ) {
		echo 'body { font-family:'.$fonts[$body_font]['fontfamily'].' }';
	}	
	$heading_font = prima_get_setting('heading_font',PRIMA_DESIGN_SETTINGS);
	if ( $heading_font && isset($fonts[$heading_font]) && isset($fonts[$heading_font]['fontfamily'] ) ) {
		echo 'h1,h2,h3,h4,h5,h6 { font-family:'.$fonts[$heading_font]['fontfamily'].' }';
	}	
}

/**
 * Add Basic Typography controls for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_basic' );
function prima_design_settings_args_basic( $settings ) {
	$settings[] = array( 
		"name" => __('Default Text (Paragraph) Color', 'primathemes'),
		"id" => "body_color",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "body",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Default Heading Color', 'primathemes'),
		"id" => "body_heading",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "h1,h2,h3,h4,h5",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Default Link Color', 'primathemes'),
		"id" => "body_link",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "a,a:visited",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Default Link Color (Hover)', 'primathemes'),
		"id" => "body_linkhover",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "a:hover",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Default Form Input Text Color', 'primathemes'),
		"id" => "body_input_color",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "input, select, textarea",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Default Form Input Background Color', 'primathemes'),
		"id" => "body_input_bg",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "input, select, textarea",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Default Form Input Border Color', 'primathemes'),
		"id" => "body_input_border",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "input, select, textarea",
		"css_property" => "border-color",
		);
	$settings[] = array( 
		"name" => __('Default Button Text Color', 'primathemes'),
		"id" => "body_button_color",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => 'button, input[type="button"], input[type="reset"], input[type="submit"]',
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Default Button Background Color', 'primathemes'),
		"id" => "body_button_bg",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => 'button, input[type="button"], input[type="reset"], input[type="submit"]',
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Default Button Background Color (Hover)', 'primathemes'),
		"id" => "body_button_bg_hover",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => 'button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover',
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Default Horizontal Line Color', 'primathemes'),
		"id" => "body_line_color",
		"default" => "",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => 'hr, .ps-hr',
		"css_property" => "border-color",
		);
	return $settings;
}


/**
 * Add Ecommerce controls for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_ecommerce' );
function prima_design_settings_args_ecommerce( $settings ) {
	$settings[] = array( 
		"name" => __('Ecommerce', 'primathemes'),
		"id" => "ecommerce",
		"type" => "section",
		"priority" => 131,
		);
	$settings[] = array( 
		"name" => __('Star Rating Color', 'primathemes'),
		"id" => "ecommerce_rating_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce .star-rating span, .woocommerce-page .star-rating span",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Sale Flash Background Color', 'primathemes'),
		"id" => "ecommerce_saleflash_bg_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale, .woocommerce span.onsale, .woocommerce-page span.onsale",
		"css_property" => "background",
		);
	$settings[] = array( 
		"name" => __('Sale Flash Text Color', 'primathemes'),
		"id" => "ecommerce_saleflash_text_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce ul.products li.product .onsale, .woocommerce-page ul.products li.product .onsale, .woocommerce span.onsale, .woocommerce-page span.onsale",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Price Text Color (Default)', 'primathemes'),
		"id" => "ecommerce_price_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price, .woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Price Text Color (From Text)', 'primathemes'),
		"id" => "ecommerce_price_from_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce ul.products li.product .price .from, .woocommerce-page ul.products li.product .price .from, .woocommerce div.product span.price .from, .woocommerce div.product p.price .from, .woocommerce #content div.product span.price .from, .woocommerce #content div.product p.price .from, .woocommerce-page div.product span.price .from, .woocommerce-page div.product p.price .from, .woocommerce-page #content div.product span.price .from, .woocommerce-page #content div.product p.price .from",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Price Text Color (Regular Price)', 'primathemes'),
		"id" => "ecommerce_price_regular_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce div.product span.price del, .woocommerce div.product p.price del, .woocommerce #content div.product span.price del, .woocommerce #content div.product p.price del, .woocommerce-page div.product span.price del, .woocommerce-page div.product p.price del, .woocommerce-page #content div.product span.price del, .woocommerce-page #content div.product p.price del, .woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Price Text Color (Sale Price)', 'primathemes'),
		"id" => "ecommerce_price_sale_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce ul.products li.product .price ins, .woocommerce-page ul.products li.product .price ins, .woocommerce div.product span.price ins, .woocommerce div.product p.price ins, .woocommerce #content div.product span.price ins, .woocommerce #content div.product p.price ins, .woocommerce-page div.product span.price ins, .woocommerce-page div.product p.price ins, .woocommerce-page #content div.product span.price ins, .woocommerce-page #content div.product p.price ins",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Button Text Color', 'primathemes'),
		"id" => "ecommerce_button_text_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Button Background Color', 'primathemes'),
		"id" => "ecommerce_button_bg_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button",
		"css_property" => "background",
		);
	$settings[] = array( 
		"name" => __('Button Background Color (Hover)', 'primathemes'),
		"id" => "ecommerce_button_bg_color_hover",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover",
		"css_property" => "background",
		);
	$settings[] = array( 
		"name" => __('Button Border Color', 'primathemes'),
		"id" => "ecommerce_button_border_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button",
		"css_property" => "border-color",
		);
	$settings[] = array( 
		"name" => __('Alternate Button Text Color', 'primathemes'),
		"id" => "ecommerce_buttonalt_text_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce div.product form.cart .button, .woocommerce #content div.product form.cart .button, .woocommerce-page div.product form.cart .button, .woocommerce-page #content div.product form.cart .button, .woocommerce table.cart td.actions .button.alt, .woocommerce #content table.cart td.actions .button.alt, .woocommerce-page table.cart td.actions .button.alt, .woocommerce-page #content table.cart td.actions .button.alt",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Alternate Button Background Color', 'primathemes'),
		"id" => "ecommerce_buttonalt_bg_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce div.product form.cart .button, .woocommerce #content div.product form.cart .button, .woocommerce-page div.product form.cart .button, .woocommerce-page #content div.product form.cart .button, .woocommerce table.cart td.actions .button.alt, .woocommerce #content table.cart td.actions .button.alt, .woocommerce-page table.cart td.actions .button.alt, .woocommerce-page #content table.cart td.actions .button.alt",
		"css_property" => "background",
		);
	$settings[] = array( 
		"name" => __('Alternate Button Background Color (Hover)', 'primathemes'),
		"id" => "ecommerce_buttonalt_bg_color_hover",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #content div.product form.cart .button:hover, .woocommerce-page div.product form.cart .button:hover, .woocommerce-page #content div.product form.cart .button:hover, .woocommerce table.cart td.actions .button.alt:hover, .woocommerce #content table.cart td.actions .button.alt:hover, .woocommerce-page table.cart td.actions .button.alt:hover, .woocommerce-page #content table.cart td.actions .button.alt:hover",
		"css_property" => "background",
		);
	$settings[] = array( 
		"name" => __('Alternate Button Border Color', 'primathemes'),
		"id" => "ecommerce_buttonalt_border_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce div.product form.cart .button, .woocommerce #content div.product form.cart .button, .woocommerce-page div.product form.cart .button, .woocommerce-page #content div.product form.cart .button, .woocommerce table.cart td.actions .button.alt, .woocommerce #content table.cart td.actions .button.alt, .woocommerce-page table.cart td.actions .button.alt, .woocommerce-page #content table.cart td.actions .button.alt",
		"css_property" => "border-color",
		);
	$settings[] = array( 
		"name" => __('Product Tabs Background Color', 'primathemes'),
		"id" => "ecommerce_tabs_bg_color",
		"section" => "ecommerce",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => ".woocommerce div.product .woocommerce-tabs ul.tabs li,.woocommerce #content div.product .woocommerce-tabs ul.tabs li,.woocommerce-page div.product .woocommerce-tabs ul.tabs li,.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li",
		"css_property" => "background",
		);
	return $settings;
}

/**
 * Add Boxed Content Background section for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_boxed' );
function prima_design_settings_args_boxed( $settings ) {
	$settings[] = array( 
		"name" => __('Background (Boxed Content)', 'primathemes'),
		"id" => "boxed_content",
		"type" => "section",
		);
	$settings[] = array( 
		"name" => __('Background Color (Only for Boxed Layout)', 'primathemes'),
		"id" => "boxed_background_color",
		"section" => "boxed_content",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "body.stylelayout-boxed #main .margin",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Background Image (Only for Boxed Layout)', 'primathemes'),
		"id" => "boxed_background_image",
		"section" => "boxed_content",
		"type" => "image",
		"live_preview" => true,
		"css_selector" => "body.stylelayout-boxed #main .margin",
		"css_property" => "background-image",
		);
	return $settings;
}

/**
 * Add Top Navigation section for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_topnav' );
function prima_design_settings_args_topnav( $settings ) {
	$settings[] = array( 
		"name" => __('Top Navigation', 'primathemes'),
		"id" => "topnav",
		"type" => "section",
		);
	$settings[] = array( 
		"name" => __('Background Color', 'primathemes'),
		"id" => "topnav_bg_color",
		"section" => "topnav",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#topnav",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Background Image', 'primathemes'),
		"id" => "topnav_bg_image",
		"section" => "topnav",
		"type" => "image",
		"live_preview" => true,
		"css_selector" => "#topnav",
		"css_property" => "background-image",
		);
	$settings[] = array( 
		"name" => __('Text Color', 'primathemes'),
		"id" => "topnav_text_color",
		"section" => "topnav",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#topnav",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Link Color', 'primathemes'),
		"id" => "topnav_link_color",
		"section" => "topnav",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#topnav a, #topnav a:visited, #topnav a:hover",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Cart Count Background Color', 'primathemes'),
		"id" => "topnav_cartcount_bg_color",
		"section" => "topnav",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#topnav ul.topnav-menu li a.topnav-cart-count, #topnav ul.topnav-menu li a.topnav-cart-count:visited",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Cart Count Background Color (Hover)', 'primathemes'),
		"id" => "topnav_cartcount_bg_color_hover",
		"section" => "topnav",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#topnav ul.topnav-menu li a.topnav-cart-count:hover",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Cart Count Text Color', 'primathemes'),
		"id" => "topnav_cartcount_text_color",
		"section" => "topnav",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#topnav ul.topnav-menu li a.topnav-cart-count, #topnav ul.topnav-menu li a.topnav-cart-count:visited",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Search Form Background Color', 'primathemes'),
		"id" => "topnav_searchform_bg_color",
		"section" => "topnav",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#topnav ul.topnav-menu input.searchinput",
		"css_property" => "background-color",
		);
	return $settings;
}

/**
 * Add Header Content section for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_headercontent' );
function prima_design_settings_args_headercontent( $settings ) {
	$settings[] = array( 
		"name" => __('Header Content (Logo&Menu)', 'primathemes'),
		"id" => "headercontent",
		"type" => "section",
		);
	$settings[] = array( 
		"name" => __('Background Color', 'primathemes'),
		"id" => "headercontent_bg_color",
		"section" => "headercontent",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Background Image', 'primathemes'),
		"id" => "headercontent_bg_image",
		"section" => "headercontent",
		"type" => "image",
		"live_preview" => true,
		"css_selector" => "#header",
		"css_property" => "background-image",
		);
	$settings[] = array( 
		"name" => __('Primary Color', 'primathemes'),
		"id" => "headercontent_primary_color",
		"section" => "headercontent",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-title a, #header-title a:visited, #header-menu .menu-primary a, #header-menu .menu-primary a:visited, #header-menu .menu-primary li li a, #header-menu .menu-primary li li a:visited, #header-menu .menu-primary li li a:hover",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Secondary Color', 'primathemes'),
		"id" => "headercontent_secondary_color",
		"section" => "headercontent",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-title a:hover, #header-menu .menu-primary a:hover",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Submenu Background Color', 'primathemes'),
		"id" => "headercontent_submenu_bg_color",
		"section" => "headercontent",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-menu .menu-primary li li a, #header-menu .menu-primary li li a:visited",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Submenu Background Color (Hover)', 'primathemes'),
		"id" => "headercontent_submenu_bg_color_hover",
		"section" => "headercontent",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-menu .menu-primary li li a:hover",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Submenu Border Color', 'primathemes'),
		"id" => "headercontent_submenu_border_color",
		"section" => "headercontent",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-menu .menu-primary li li a, #header-menu .menu-primary li li a:visited",
		"css_property" => "border-color",
		);
	return $settings;
}

/**
 * Add Header Featured section for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_headerfeatured' );
function prima_design_settings_args_headerfeatured( $settings ) {
	$settings[] = array( 
		"name" => __('Header Featured', 'primathemes'),
		"id" => "headerfeatured",
		"type" => "section",
		);
	$settings[] = array( 
		"name" => __('Background Color', 'primathemes'),
		"id" => "headerfeatured_bg_color",
		"section" => "headerfeatured",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-featured",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Background Image', 'primathemes'),
		"id" => "headerfeatured_bg_image",
		"section" => "headerfeatured",
		"type" => "image",
		"live_preview" => true,
		"css_selector" => "#header-featured",
		"css_property" => "background-image",
		);
	return $settings;
}

/**
 * Add Header Call To Action section for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_headeraction' );
function prima_design_settings_args_headeraction( $settings ) {
	$settings[] = array( 
		"name" => __('Header Call To Action', 'primathemes'),
		"id" => "headeraction",
		"type" => "section",
		);
	$settings[] = array( 
		"name" => __('Background Color', 'primathemes'),
		"id" => "headeraction_bg_color",
		"section" => "headeraction",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-action",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Background Image', 'primathemes'),
		"id" => "headeraction_bg_image",
		"section" => "headeraction",
		"type" => "image",
		"live_preview" => true,
		"css_selector" => "#header-action",
		"css_property" => "background-image",
		);
	$settings[] = array( 
		"name" => __('Text Color', 'primathemes'),
		"id" => "headeraction_text_color",
		"section" => "headeraction",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-action",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Button Text Color', 'primathemes'),
		"id" => "headeraction_button_text_color",
		"section" => "headeraction",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-action a.header-action-button, #header-action a.header-action-button:visited",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Button Background Color', 'primathemes'),
		"id" => "headeraction_button_bg_color",
		"section" => "headeraction",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-action a.header-action-button, #header-action a.header-action-button:visited",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Button Background Color (Hover)', 'primathemes'),
		"id" => "headeraction_button_bg_color_hover",
		"section" => "headeraction",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#header-action a.header-action-button:hover",
		"css_property" => "background-color",
		);
	return $settings;
}

/**
 * Add Footer Widgets section for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_footerwidgets' );
function prima_design_settings_args_footerwidgets( $settings ) {
	$settings[] = array( 
		"name" => __('Footer Widgets', 'primathemes'),
		"id" => "footerwidgets",
		"type" => "section",
		);
	$settings[] = array( 
		"name" => __('Background Color', 'primathemes'),
		"id" => "footerwidgets_bg_color",
		"section" => "footerwidgets",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#footer-widgets",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Background Image', 'primathemes'),
		"id" => "footerwidgets_bg_image",
		"section" => "footerwidgets",
		"type" => "image",
		"live_preview" => true,
		"css_selector" => "#footer-widgets",
		"css_property" => "background-image",
		);
	$settings[] = array( 
		"name" => __('Border Color', 'primathemes'),
		"id" => "footerwidgets_border_color",
		"section" => "footerwidgets",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#footer-widgets, #footer-widgets .widget h3.widget-title",
		"css_property" => "border-color",
		);
	return $settings;
}

/**
 * Add Footer Content section for Design Settings (Customizer) page.
 *
 * @since PrimaShop 1.0
 */
add_filter( 'prima_design_settings_args', 'prima_design_settings_args_footercontent' );
function prima_design_settings_args_footercontent( $settings ) {
	$settings[] = array( 
		"name" => __('Footer Content (Credits&Menu)', 'primathemes'),
		"id" => "footercontent",
		"type" => "section",
		);
	$settings[] = array( 
		"name" => __('Background Color', 'primathemes'),
		"id" => "footercontent_bg_color",
		"section" => "footercontent",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#footer",
		"css_property" => "background-color",
		);
	$settings[] = array( 
		"name" => __('Background Image', 'primathemes'),
		"id" => "footercontent_bg_image",
		"section" => "footercontent",
		"type" => "image",
		"live_preview" => true,
		"css_selector" => "#footer",
		"css_property" => "background-image",
		);
	$settings[] = array( 
		"name" => __('Primary Color', 'primathemes'),
		"id" => "footercontent_primary_color",
		"section" => "footercontent",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#footer, #footer .footer-right ul.footer-menu a, #footer .footer-right ul.footer-menu a:visited",
		"css_property" => "color",
		);
	$settings[] = array( 
		"name" => __('Secondary Color', 'primathemes'),
		"id" => "footercontent_secondary_color",
		"section" => "footercontent",
		"type" => "color",
		"live_preview" => true,
		"css_selector" => "#footer a, #footer a:visited, #footer a:hover, #footer .footer-right ul.footer-menu a:hover", 
		"css_property" => "color",
		);
	return $settings;
}
