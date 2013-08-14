<?php
/**
 * Functions to handle fonts
 *
 * WARNING: This file is part of the core PrimaThemes framework.
 * DO NOT edit this file under any circumstances. 
 *
 * @category   PrimaThemes
 * @package    Framework
 * @subpackage Functions
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Return fonts lists 
 *
 * @since PrimaThemes 2.0
 */
function prima_fonts() {
$fonts = array(
		'arial' => array(
			'id' =>	'arial',
			'type' => 'webfont',
			'name' => 'Arial',
			'fontfamily' =>	'Arial, sans-serif'
		),
		'arialblack' => array(
			'id' =>	'arialblack',
			'type' => 'webfont',
			'name' => 'Arial Black',
			'fontfamily' =>	'&quot;Arial Black&quot;, sans-serif'
		),
		'calibri' => array(
			'id' =>	'calibri',
			'type' => 'webfont',
			'name' => 'Calibri*',
			'fontfamily' =>	'Calibri, Candara, Segoe, Optima, sans-serif'
		),
		'geneva' => array(
			'id' =>	'geneva',
			'type' => 'webfont',
			'name' => 'Geneva*',
			'fontfamily' =>	'Geneva, Tahoma, Verdana, sans-serif'
		),
		'georgia' => array(
			'id' =>	'georgia',
			'type' => 'webfont',
			'name' => 'Georgia',
			'fontfamily' =>	'Georgia, serif'
		),
		'gillsans' => array(
			'id' =>	'gillsans',
			'type' => 'webfont',
			'name' => 'Gill Sans*',
			'fontfamily' =>	'"Gill Sans", "Gill Sans MT", Calibri, sans-serif'
		),
		'helvetica' => array(
			'id' =>	'helvetica',
			'type' => 'webfont',
			'name' => 'Helvetica*',
			'fontfamily' =>	'"Helvetica Neue", Helvetica, sans-serif'
		),
		'impact' => array(
			'id' =>	'impact',
			'type' => 'webfont',
			'name' => 'Impact',
			'fontfamily' =>	'Impact, Charcoal, sans-serif'
		),
		'lucida' => array(
			'id' =>	'lucida',
			'type' => 'webfont',
			'name' => 'Lucida',
			'fontfamily' =>	'"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", sans-serif'
		),
		'myriadpro' => array(
			'id' =>	'myriadpro',
			'type' => 'webfont',
			'name' => 'Myriad Pro*',
			'fontfamily' =>	'"Myriad Pro", Myriad, sans-serif'
		),
		'palatino' => array(
			'id' =>	'palatino',
			'type' => 'webfont',
			'name' => 'Palatino',
			'fontfamily' =>	'Palatino, "Palatino Linotype", serif'
		),
		'sans-serif' => array(
			'id' =>	'sans-serif',
			'type' => 'webfont',
			'name' => 'Sans-Serif',
			'fontfamily' =>	'sans-serif'
		),
		'serif' => array(
			'id' =>	'serif',
			'type' => 'webfont',
			'name' => 'Serif',
			'fontfamily' =>	'serif'
		),
		'tahoma' => array(
			'id' =>	'tahoma',
			'type' => 'webfont',
			'name' => 'Tahoma',
			'fontfamily' =>	'Tahoma, Geneva, Verdana, sans-serif'
		),
		'timesnewroman' => array(
			'id' =>	'timesnewroman',
			'type' => 'webfont',
			'name' => 'Times New Roman',
			'fontfamily' =>	'"Times New Roman", serif'
		),
		'trebuchet' => array(
			'id' =>	'trebuchet',
			'type' => 'webfont',
			'name' => 'Trebuchet',
			'fontfamily' =>	'"Trebuchet MS", Helvetica, sans-serif'
		),
		'verdana' => array(
			'id' =>	'verdana',
			'type' => 'webfont',
			'name' => 'Verdana',
			'fontfamily' =>	'Verdana, Geneva, sans-serif'
		)
	);
	return apply_filters('prima_fonts', $fonts);
}
