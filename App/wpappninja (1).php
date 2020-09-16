<?php
/*
Plugin Name: WPMobile.App — Android and iOS Mobile Application
Plugin URI: https://wpmobile.app/
Description: Android and iOS mobile application for any WordPress wesbite. Easy setup, free test.
Version: 9.0.107
Author: WPMobile.App
Author URI: https://wpmobile.app/
Licence: GPLv2
Text Domain: wpappninja
Domain Path: /languages/
*/

defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

// common
define( 'WPAPPNINJA_VERSION'	 		, '9.0.107' );
define( 'WPAPPNINJA_VERSION_APP'        , '100' );

if (defined('WPAPPNINJA_WHITE_LABEL')) {
    define( 'WPAPPNINJA_SLUG'               , WPAPPNINJA_WHITE_LABEL_SLUG );
    define( 'WPAPPNINJA_NAME'               , WPAPPNINJA_WHITE_LABEL );
} else {
    define( 'WPAPPNINJA_SLUG'               , 'wpappninja' );
    define( 'WPAPPNINJA_NAME'               , 'WPMobile.App' );
}

// url
define( 'WPAPPNINJA_SETTINGS_SLUG'		, WPAPPNINJA_SLUG . '_settings' );
define( 'WPAPPNINJA_PUSH_SLUG'          , WPAPPNINJA_SLUG . '_push' );
define( 'WPAPPNINJA_QRCODE_SLUG'        , WPAPPNINJA_SLUG . '_qrcode' );
define( 'WPAPPNINJA_CERT_SLUG'			, WPAPPNINJA_SLUG . '_cert' );
define( 'WPAPPNINJA_STATS_SLUG'			, WPAPPNINJA_SLUG . '_stats' );
define( 'WPAPPNINJA_PUBLISH_SLUG'		, WPAPPNINJA_SLUG . '_publish' );
define( 'WPAPPNINJA_PROMOTE_SLUG'       , WPAPPNINJA_SLUG . '_promote' );
define( 'WPAPPNINJA_ADSERVER_SLUG'      , WPAPPNINJA_SLUG . '_adserver' );
define( 'WPAPPNINJA_AUTO_SLUG'          , WPAPPNINJA_SLUG . '_auto' );
define( 'WPAPPNINJA_HOME_SLUG'          , WPAPPNINJA_SLUG . '_home' );
define( 'WPAPPNINJA_UPDATE_SLUG'        , WPAPPNINJA_SLUG . '_update' );
define( 'WPAPPNINJA_PWA_SLUG'           , WPAPPNINJA_SLUG . '_pwa' );
define( 'WPAPPNINJA_THEME_SLUG'         , WPAPPNINJA_SLUG . '_theme' );
define( 'WPAPPNINJA_PREVIEW_SLUG'       , WPAPPNINJA_SLUG . '_preview' );
define( 'WPAPPNINJA_WEB_MAIN'	 		, 'https://wpmobile.app' );

// file path
define( 'WPAPPNINJA_FILE'            	, __FILE__ );
define( 'WPAPPNINJA_PATH'       		, realpath( plugin_dir_path( WPAPPNINJA_FILE ) ) . '/' );
define( 'WPAPPNINJA_ICONS_PATH'         , realpath( WPAPPNINJA_PATH . 'assets/images/icons/' ) . '/' );
define( 'WPAPPNINJA_FLAGS_PATH'         , realpath( WPAPPNINJA_PATH . 'assets/images/flags/' ) . '/' );
define( 'WPAPPNINJA_SVG_PATH'           , realpath( WPAPPNINJA_PATH . 'assets/svg/' ) . '/' );
define( 'WPAPPNINJA_INC_PATH'   		, realpath( WPAPPNINJA_PATH . 'inc/' ) . '/' );
define( 'WPAPPNINJA_ADMIN_PATH' 		, realpath( WPAPPNINJA_INC_PATH . 'admin' ) . '/' );
define( 'WPAPPNINJA_ADMIN_UI_PATH'      , realpath( WPAPPNINJA_ADMIN_PATH . 'ui' ) . '/' );
define( 'WPAPPNINJA_API_PATH'   		, realpath( WPAPPNINJA_INC_PATH . 'api' ) . '/' );
define( 'WPAPPNINJA_COMMON_PATH'    	, realpath( WPAPPNINJA_INC_PATH . 'common' ) . '/' );
define( 'WPAPPNINJA_DEBUG_PATH'   		, realpath( WPAPPNINJA_PATH . 'debug' ) . '/' );
define( 'WPAPPNINJA_FUNCTIONS_PATH'     , realpath( WPAPPNINJA_INC_PATH . 'functions' ) . '/' );
define( 'WPAPPNINJA_3RDPARTY_PATH'      , realpath( WPAPPNINJA_INC_PATH . '3rd-party' ) . '/' );
define( 'WPAPPNINJA_STATS_PATH'  	    , realpath( WPAPPNINJA_INC_PATH . 'stats' ) . '/' );

// assets url
define( 'WPAPPNINJA_URL'                , plugin_dir_url( WPAPPNINJA_FILE ) );
define( 'WPAPPNINJA_ASSETS_URL'         , WPAPPNINJA_URL . 'assets/' );
define( 'WPAPPNINJA_SVG_URL'           , WPAPPNINJA_URL . 'assets/svg/' );
define( 'WPAPPNINJA_ASSETS_3RD_URL'     , WPAPPNINJA_ASSETS_URL . '3rd-party/' );
define( 'WPAPPNINJA_ASSETS_JS_URL'      , WPAPPNINJA_ASSETS_URL . 'js/' );
define( 'WPAPPNINJA_ASSETS_CSS_URL'     , WPAPPNINJA_ASSETS_URL . 'css/' );
define( 'WPAPPNINJA_ASSETS_IMG_URL'     , WPAPPNINJA_ASSETS_URL . 'images/' );

/*
 * Tell WP what to do when plugin is loaded
 *
 * @since 1.0
 */
add_action( 'plugins_loaded', '_wpappninja_init' );
function _wpappninja_init() {

    // Nothing to do if autosave
    if ( defined( 'DOING_AUTOSAVE' ) ) {
        return;
    }
	
    require( WPAPPNINJA_3RDPARTY_PATH   . 'noappforweb.php' );
    require( WPAPPNINJA_ADMIN_PATH      . 'right.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'detect_os.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'options.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH 	. 'lang.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'wpml.php' );
    require( WPAPPNINJA_3RDPARTY_PATH 	. 'wp-rocket.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'mobile.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'wpserveur.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'wptouch.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'autooptimize.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'wp-spam-shield.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'ait.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'hide-errors.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'select2.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'elementor.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'contact7.php' );
    require( WPAPPNINJA_3RDPARTY_PATH   . 'adsense.php' );

	// if it's an api call, force the language
	$get_pagename = isset($_GET['pagename']) ? sanitize_text_field($_GET['pagename']) : "";
	if ( $get_pagename == 'wpappninja' ) {
		
		define('DOING_WPAPPNINJA_API', true);
		wpappninja_wpml_fix();
        wpappninja_wprocket_fix();
		
		// set http header 200 and avoid 404
		add_filter( 'wp', 'wpappninja_set200', 0 );
		function wpappninja_set200() {
			global $wp_query;
			status_header(200);
			$wp_query->is_404 = false;
		}
		
		// default locale
		add_filter( 'locale', 'wpappninja_api_localize' );
		function wpappninja_api_localize( $locale ) {
			return wpappninja_get_lang('long');
		}
	} else if (isset($_SERVER['HTTP_X_WPAPPNINJA'])) {
        wpappninja_wprocket_fix();
    }

    // remove accents
    add_filter('sanitize_file_name', 'remove_accents' );
	
	if (isset($_SERVER['HTTP_X_WPAPPNINJA'])) {

        /*remove_filter('template_redirect', 'redirect_canonical');

        if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {

            $s = (isset($_SERVER['HTTPS']) ? 's' : '');
            if (home_url( '', 'http' . $s ) . $_SERVER['REQUEST_URI'] != 'http' . $s . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) {
            
                $url = home_url( '', 'http' . $s ) . $_SERVER['REQUEST_URI'];
                wp_redirect( $url, 301 );
                exit();
            }

        }*/

        // wprocket
        wpappninja_wprocket_fix();
        

	} else if (isset($_GET['WPAPPNINJA']) && $_GET['WPAPPNINJA'] == 'PREVENT_CACHE') {
		if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
			$url = remove_query_arg('WPAPPNINJA', 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			wp_redirect( $url, 301 );
			exit();
		}
	}
	
	// load translations
    load_plugin_textdomain( 'wpappninja', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'package.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'healme.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH 	. 'check.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'format.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH 	. 'colors.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH 	. 'seo.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH 	. 'image.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH 	. 'menu.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH 	. 'banner.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'apple_reviewer.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'sdk2019.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'webview.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'shortcodes.php' );
    require( WPAPPNINJA_FUNCTIONS_PATH  . 'appify.php' );
    require( WPAPPNINJA_API_PATH 		. 'rewrite.php' );
    require( WPAPPNINJA_API_PATH 		. 'push.php' );
    require( WPAPPNINJA_API_PATH 		. 'json.php' );
    require( WPAPPNINJA_API_PATH        . 'read_enhanced.php' );
    require( WPAPPNINJA_API_PATH        . 'ads.php' );
    require( WPAPPNINJA_API_PATH        . 'theme.php' );
    require( WPAPPNINJA_COMMON_PATH 	. 'admin-bar.php' );
    require( WPAPPNINJA_COMMON_PATH 	. 'cron.php' );
    require( WPAPPNINJA_COMMON_PATH 	. 'enqueue.php' );
    require( WPAPPNINJA_COMMON_PATH 	. 'cache.php' );
    require( WPAPPNINJA_COMMON_PATH 	. 'deeplinking.php' );
    require( WPAPPNINJA_COMMON_PATH     . 'premium.php' );
    require( WPAPPNINJA_STATS_PATH 		. 'boot.php' );
    require( WPAPPNINJA_DEBUG_PATH 		. 'diff.php' );
	
	// if we want to dismiss something
	if (isset($_GET['wpappninja_dismiss_update'])) {
		wpappninja_dismiss_update();
	}

    if ( is_admin() ) {
        require( WPAPPNINJA_ADMIN_PATH 		. 'upgrader.php' );
        require( WPAPPNINJA_ADMIN_PATH 		. 'options.php' );
        require( WPAPPNINJA_ADMIN_PATH 		. 'metabox.php' );
        require( WPAPPNINJA_ADMIN_PATH  	. 'menu.php' );
        require( WPAPPNINJA_ADMIN_PATH  	. 'plugins.php' );
        require( WPAPPNINJA_ADMIN_PATH  	. 'enqueue.php' );
        require( WPAPPNINJA_ADMIN_PATH  	. 'talkus.php' );
        require( WPAPPNINJA_ADMIN_PATH      . 'sdkupdate.php' );
        require( WPAPPNINJA_ADMIN_PATH      . 'import.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'options.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'push.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'cert.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'stats.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'publish.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'promote.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'adserver.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'qrcode.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'auto.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'home.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'update.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'theme.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'pwa.php' );
        require( WPAPPNINJA_ADMIN_UI_PATH   . 'preview.php' );
		require( WPAPPNINJA_STATS_PATH 		. 'render.php' );
		require( WPAPPNINJA_STATS_PATH 		. 'display.php' );
    }

    if (is_wpappninja() && get_wpappninja_option('trad') == 'manual') {
        add_filter( 'locale', 'wpappninja_theme_localize' );
        function wpappninja_theme_localize( $locale ) {
            return wpappninja_get_lang('long');
        }
    }

    if (is_wpappninja()) {
        $wpappninja_popup = "";
    }

    /**
	 * Fires when WPMobile.App is correctly loaded
	 *
	 * @since 1.0
	*/
	do_action( 'wpappninja_loaded' );

    if (isset($_POST['wpappnewdashboard'])) {

        $options            = get_option( WPAPPNINJA_SLUG );
        
        if ($_POST['newdashboard'] == 'on') {
            $options['nomoreqrcode'] = '1';
        } else {
            $options['nomoreqrcode'] = '0';
        }

        update_option( WPAPPNINJA_SLUG, $options );
    }
}

// antispam
require( WPAPPNINJA_3RDPARTY_PATH 	. 'antispam.php' );
require( WPAPPNINJA_3RDPARTY_PATH   . 'wpmobilepack.php' );
require( WPAPPNINJA_3RDPARTY_PATH   . 'secupress.php' );
require( WPAPPNINJA_3RDPARTY_PATH   . 'ampforwp.php' );
