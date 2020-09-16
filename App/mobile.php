<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Disable WPTouch on API
 *
 * @since 4.0.3
 */
function wpappninja_disable_wptouch( $array ) {
	if (defined("DOING_WPAPPNINJA_API")) {
		$array = array($_SERVER['HTTP_USER_AGENT']);
	}
	
	return $array;
}
add_filter( 'wptouch_exclusion_list', 'wpappninja_disable_wptouch', PHP_INT_MAX, 1 );

/**
 * Disable AMP Redirection
 *
 * @since 7.0.9
 */
add_action( 'init', 'wpappninja_no_amp' );
function wpappninja_no_amp() {

	if (is_wpappninja()) {
		remove_action( 'template_redirect', 'ampforwp_check_amp_page_status' );
		remove_action( 'template_redirect', 'ampforwp_page_template_redirect' );
		remove_action( 'template_redirect', 'ampforwp_page_template_redirect_archive' );
	}
}