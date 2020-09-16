<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Get an image for a post.
 *
 * @since 1.0
 */
function wpappninja_get_image($id, $onlyFeat = '0', $force = false, $page = false) {

	if (get_wpappninja_option('disablefeat', '0') == '1' && !$force && !$page) {
		return '';
	}

	if (get_wpappninja_option('hideimgonlypage', '0') == '1' && $page && get_wpappninja_option('speed') != '1') {
		return '';
	}

	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
	if ($image[0] != '') {
		return wpappninja_urlencode_accent($image[0]);
	}
	
	if ($onlyFeat == '1' || (get_wpappninja_option('anyfeat') == '0' && !$force)) {
		return '';
	}
	
	$images = get_attached_media('image', $id);
	foreach($images as $img) {
		$i = wp_get_attachment_image_src($img->ID, 'medium');
		if ($i[0] != '') {
			return wpappninja_urlencode_accent($i[0]);
		}
	}
	
	$return = get_wpappninja_option('defautimg', '');

	return wpappninja_urlencode_accent($return);
}

/**
 * Test if image exist.
 *
 * @since 3.5
 */
function wpappninja_get_http_response($image_url) {
    $response = wp_remote_get( $image_url );
	
	if( is_array($response) ) {
		return $response['response']['code'];
	}
	
	return '0';
}

/**
 * Encode accent on filename.
 *
 * @since 4.3
 */
function wpappninja_urlencode_accent($url) {
	return str_replace('%3F', '?', str_replace('%253', '?', str_replace('%3A', ':', implode('/', array_map('rawurlencode', explode('/', $url))))));
}
