<?php
/**
 * Setup theme specific functions
 *
 * WARNING: This file is part of the PrimaShop parent theme.
 * Please do all modifications in the form of a child theme.
 *
 * @category   PrimaShop
 * @package    Setup
 * @author     PrimaThemes
 * @link       http://www.primathemes.com
 */

require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'theme/setup.php' );
if (  is_admin() ) require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'theme/settings.php' );
if (  is_admin() ) require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'theme/meta.php' );
if ( !is_admin() ) require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'theme/frontend.php' );
require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'theme/designs.php' );

if ( class_exists( 'woocommerce' ) ) { 
	require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'woocommerce/functions.php' );
	require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'woocommerce/widgets.php' );
	if (  is_admin() ) require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'woocommerce/admin.php' );
	if ( !is_admin() ) require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'woocommerce/frontend.php' );
	if ( !is_admin() ) require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'woocommerce/shortcodes.php' );
}

/**
 * Register Envato WordPress Auto Update.
 *
 * @since PrimaShop 1.0
 */
add_action('admin_init', 'prima_envato_autoupdate');
function prima_envato_autoupdate() {
    require_once( trailingslashit(PRIMA_CUSTOM_DIR) . 'envato-wordpress-toolkit-library/class-envato-wordpress-theme-upgrader.php' );
	
    $upgrader = new Envato_WordPress_Theme_Upgrader( prima_get_setting('envato_username'), prima_get_setting('envato_apikey') );
    
    /*
     *  Uncomment to check if the current theme has been updated
     */
    // $upgrader->check_for_theme_update(); 

    /*
     *  Uncomment to update the current theme
     */
    // $upgrader->upgrade_theme();
}