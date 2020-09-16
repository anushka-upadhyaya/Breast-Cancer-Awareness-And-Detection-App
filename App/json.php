<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Return menu and parameters
 *
 * @since 1.0
 */

function wpappninja_category() {
	
	//$wpappninja_locale = wpappninja_get_lang();
	//if (get_wpappninja_option('speed') == '1') {
		$wpappninja_locale = 'speed';
	//}

	$json = array();
	
	/*******************************
	* CAT FOR SUBSCRIBE
	*******************************/
	$json['subscribe'] = array();
	$category = get_terms(array('category'), array(
										'orderby' => 'count', 'order' => 'DESC', 'number' => 40,
										'parent' => 0,
										'hide_empty' => true
									));

	$silent = array();
	if (is_array(get_wpappninja_option( 'silent' , ''))) {
		$silent = get_wpappninja_option( 'silent' , '');
	}

	foreach ( $category as $cat ) {
		if (!in_array($cat->term_id, $silent)) {
			/*$json['subscribe'][] = array(
						'name' => html_entity_decode(trim($cat->name)),
						'id' => strval($cat->term_id)
					);*/
		}
	}
	
	/*******************************
	* MENU
	******************************/
	if (get_wpappninja_option('speed') == '1') {
		$json['menu'] = array();
	} else {
		$json['menu'] = wpappninja_get_menu_reloaded($wpappninja_locale, true);
	}

	/*******************************
	* THEME
	*******************************/
	$app_data = get_wpappninja_option('app');

	$app_theme_primary = isset($app_data['theme']['primary']) ? $app_data['theme']['primary'] : "#0f53a6";
	$app_theme_accent = isset($app_data['theme']['accent']) ? $app_data['theme']['accent'] : "#dd9933";
	$app_theme_status = wpappninja_adjustBrightness($app_theme_primary, -50);

	if (get_wpappninja_option('speed', '0') == '1') {
		$app_theme_primary = get_wpappninja_option('css_' . md5('body.wpappninja .navbar,body.wpappninja .tabbar,body.wpappninja .toolbarbackground'), $app_theme_primary);
		$app_theme_status = get_wpappninja_option('css_' . md5('.progressbar,.progressbar-infinite,.statusbar-overlaybackground'), $app_theme_status);

	} 

	$lightstatus = "0";
	if (wpappninja_need_light_status($app_theme_status)) {
		$lightstatus = "1";
	}
	$app_name = isset($app_data['name']) ? $app_data['name'] : wpappninja_get_appname();

	/*******************************
	* OPTIONS
	 ******************************/

	// Récupération du timestamp de l'article le plus récent pour la gestion des non lus

	$has_password = null;
	if (get_wpappninja_option('has_password', "0") == "0") {
		$has_password = false;
	}

	$maxID = 0;
	$maxID_q = wp_get_recent_posts(array(
										'orderby' => get_wpappninja_option('orderby_list', 'post_date'),
										'order' => get_wpappninja_option('order_list', 'DESC'),
										'has_password' => $has_password,
										'date_query' => array(
														'column'  => 'post_date',
														'after'   => '- '.get_wpappninja_option('maxage', '365000').' days'
													),
										'tag__not_in' => get_wpappninja_option('excluded', ''),
										'posts_per_page' => 1,
										'ignore_sticky_posts' => 1,
										'post_type' => get_post_types(array('public'=>true)),
										'post_status' => 'publish',
										'numberposts' => 1,
										'offset'=>0
									));

	if (isset($maxID_q[0]['post_date'])) {
		$maxID = strtotime($maxID_q[0]['post_date']);
	}

	$spamBee = '';
	
	// dummy admob to check
	$admob_float = '';
	if (wpappninja_is_apple_reviewer()) {
		$admob_float = 'ca-app-pub-4670434097606105/3232908161';
	}

	$default_homepage = "";
	if (get_wpappninja_option('speed') == '1') {
		$default_homepage = wpappninja_get_home();
	}

	$homepage_wpapp = get_wpappninja_option('pageashome_' . $wpappninja_locale, $default_homepage);

	if (get_wpappninja_option('speed', '1') == '1' && !preg_match('#^http#', $homepage_wpapp)) {

		if (preg_match('#^cat_#', $homepage_wpapp)) {

			$homepage_wpapp = preg_replace('#^cat_#', '', $homepage_wpapp);
			$taxonomy = wpappninja_get_all_taxonomy();

			foreach ($taxonomy as $tax) {
				$obj = get_term_by('id', $homepage_wpapp, $tax);
				if (is_object($obj)) {

					$homepage_wpapp = get_term_link($obj);
					break;
				}
			}
		} else {

			if (get_permalink(intval($homepage_wpapp))) {
				$homepage_wpapp = get_permalink(intval($homepage_wpapp));
			}
		}

		if (!preg_match('#^http#', $homepage_wpapp)) {
			$homepage_wpapp = wpappninja_get_home();
		}
	}

	if (!$homepage_wpapp) {
		$homepage_wpapp = "";
		if (get_wpappninja_option('speed') == '1') {
			$homepage_wpapp = wpappninja_get_home();

			if (!$homepage_wpapp) {
				$homepage_wpapp = get_home_url();
			}
		}
	}

	$homepage_title = get_wpappninja_option('pageashometitle_' . $wpappninja_locale, __('Recent posts', 'wpappninja'));


	if (get_wpappninja_option('speed_trad') == 'manual') {

		$homepage_wpapp = wpappninja_translate($homepage_wpapp);
		$homepage_title = wpappninja_translate($homepage_title);
	} else {
		$homepage_wpapp = wpappninja_cache_friendly($homepage_wpapp);
	}

	if (get_wpappninja_option('speed') == '1' && get_wpappninja_option('speed_trad') == 'weglot') {

		if (wpappninja_get_lang() != get_wpappninja_option('weglot_original', '')) {
			$homepage_wpapp = wpmobile_weglot($homepage_wpapp);
		}
	}

	$homepage_icon = get_wpappninja_option('pageashomeicon_' . $wpappninja_locale, 'arrowlight');

	if (get_wpappninja_option('speed') != '1' && $homepage_icon == 'chevron_right') {
		$homepage_icon = 'arrow';
	}















		// fix id or url
		if (get_wpappninja_option('speed') == '1') {

			if (!preg_match('#^http#', $homepage_wpapp)) {

				if (preg_match('#^cat_#', $homepage_wpapp)) {

					$taxonomy = wpappninja_get_all_taxonomy();

				    foreach ($taxonomy as $tax) {
    				  $obj = get_term_by('id', preg_replace('#cat_#', '', $homepage_wpapp), $tax);
   					  if (is_object($obj)) {
						$homepage_wpapp = get_term_link($obj);
						break;
    				  }
	    			}

				} else {
					
				    if (get_permalink(intval($homepage_wpapp))) {
    					$homepage_wpapp = get_permalink(intval($homepage_wpapp));
    				}

				}

			}

		} else {

			if (preg_match('#^http#', $homepage_wpapp)) {

				$id = wpappninja_url_to_catid($homepage_wpapp);

				if ($id !== 0) {
					$homepage_wpapp = "cat_" . strval($id);
				} else {

					$id = wpappninja_url_to_postid($homepage_wpapp);

					if ($id !== 0) {
						$homepage_wpapp = strval($id);
					}
				}

			}

		}





	$homepage_wpapp = wpappninja_cache_friendly($homepage_wpapp);


	$app_theme_primary = wpappninja_verify_color($app_theme_primary, "#dedede");
	$app_theme_accent = wpappninja_verify_color($app_theme_accent, "#333333");
	$app_theme_status = wpappninja_verify_color($app_theme_status, "#333333");


	$api = array(
							'version' => WPAPPNINJA_VERSION,
							'version_app' => get_wpappninja_option('version_app', "1"),
							'domain' => home_url( '' ),

							'appname' => $app_name,

							'theme_primary' => str_replace('#', '', $app_theme_primary),
							'theme_accent' => str_replace('#', '', $app_theme_accent),
							'theme_status' => str_replace('#', '', $app_theme_status),

							'lightstatus' => $lightstatus,
								
							'webview' => get_wpappninja_option('webview', '0'),
							
							'theme' => get_wpappninja_option('theme', 'blue'),
								
							'home_icon' => $homepage_icon,
							'home_title' => $homepage_title,
							'home_page' => $homepage_wpapp,

							'list_default_size' => get_wpappninja_option('typedevue', 'big'),
							
							'speed' => get_wpappninja_option('speed', '0'),

							'show_search' => get_wpappninja_option('show_search', '1'),
							'show_bio' => get_wpappninja_option('bio', '1'),
							'show_similar' => get_wpappninja_option('similaire', '1'),
							'show_share' => get_wpappninja_option('share', '1'),
							'show_comments' => get_wpappninja_option('commentaire', '1'),
							'show_abonnement' => get_wpappninja_option('show_abonnement', '0'),
							'show_favori' => get_wpappninja_option('show_favori', '1'),
							'show_avatar' => get_wpappninja_option('show_avatar', '1'),
							'show_date' => get_wpappninja_option('show_date', '1'),
							'show_browser' => get_wpappninja_option('show_browser', '1'),
							'remove_title' => get_wpappninja_option('remove_title', '0'),

							'iosmenulabel' => get_wpappninja_option('iosmenulabel_' . $wpappninja_locale, 'Menu'),

							'launchscreen' => get_wpappninja_option('launchscreen', '0'),

							'hideimgonlypage' => get_wpappninja_option('hideimgonlypage', '0'),

							'errormsg' => nl2br(get_wpappninja_option('errormsg', '<center><h1>Error.</h1></center>')),

							'all_link_browser' => get_wpappninja_option('all_link_browser', '0'),

							'readmarginbottom' => "1",
								
							'rating_title' => __('You like this app?', 'wpappninja'),
							'rating_texte' => preg_replace('/ Play/', '', ucfirst(__('Take 5 seconds to rate it on the Play Store', 'wpappninja'))),
							'rating_seuil' => '999999999',

							'page_welcome' => nl2br(get_wpappninja_option('bienvenue_' . $wpappninja_locale, '')),
							'page_about' => nl2br(get_wpappninja_option('mentions_' . $wpappninja_locale, '')),
								
							'third_party_push' => get_wpappninja_option('project', '352104594960'),
							'third_party_analytics' => get_wpappninja_option('ga', ''),
							'third_party_admob_float' => wpappninja_is_ios() ? get_wpappninja_option('admob_float_ios', $admob_float) : get_wpappninja_option('admob_float', ''),
							'third_party_admob_splash' => wpappninja_is_ios() ? get_wpappninja_option('admob_splash_ios', '') : get_wpappninja_option('admob_splash', ''),
							'third_party_admob_top' => wpappninja_is_ios() ? get_wpappninja_option('admob_t_ios', '') : get_wpappninja_option('admob_t', ''),
							'third_party_admob_bottom' => wpappninja_is_ios() ? get_wpappninja_option('admob_b_ios', '') : get_wpappninja_option('admob_b', ''),

							'third_party_adbuddiz' => wpappninja_is_ios() ? get_wpappninja_option('adbuddiz_ios', '') : get_wpappninja_option('adbuddiz', ''),
								
							'spambee' => $spamBee,
							
							'maxID' => strval(abs($maxID)),
							
							'force_locale' => wpappninja_get_lang(),
						);


	if (wpappninja_webview_mode(0) == '4' || get_wpappninja_option('speed', '0') == '1') {

		if (get_wpappninja_option('nospeed_notheme') == '1') {
		 $api['remove_title'] = "1";
		 $api['show_avatar'] = "0";
		 $api['show_date'] = "0";
		}
		if (get_wpappninja_option('speed', '0') == '1') {

			$api['webview'] = "4";

			$api['hideimgonlypage'] = "1";
			$api['show_bio'] = "0";
			//$api['similarnb'] = "0";

			$api['show_comments'] = "0";

			//$api['all_link_browser'] = "1";
		}
	}

	$api['show_avatar'] = "0";

	/*if (get_wpappninja_option('speed', '0') == '1') {
		$api['home_page'] = wpappninja_get_home();
	}*/
						
	$deprecated = array();
	if ( preg_match( '#/android_json/(.*)$#', $_SERVER['REQUEST_URI'], $match )) {
		$deprecated = array(
						'pageashomeicon' => get_wpappninja_option('pageashomeicon', ''),
						'pageashometitle' => get_wpappninja_option('pageashometitle_' . $wpappninja_locale, __('Recent posts', 'wpappninja')),
						'pageashome' => get_wpappninja_option('pageashome', ''),
						'titrerate' => get_wpappninja_option('rating_titre_' . $wpappninja_locale, __('You like this app?', 'wpappninja')),
						'texterate' => get_wpappninja_option('rating_texte_' . $wpappninja_locale, __('Take 5 seconds to rate it on the Play Store', 'wpappninja')),
						'seuilrate' => get_wpappninja_option('rating_seuil', '10'),
						'iconmenu' => 'arrow',
						'header_image' => '',
						'titreform' => 'Formulaires',
						'pagetitre' => 'Pages',
						'featformicon' => get_wpappninja_option('pageashomeicon', ''),
						'featform' => get_wpappninja_option('pageashome', ''),
						'type_de_vue' => get_wpappninja_option('typedevue', 'big'),
						'bio' => get_wpappninja_option('bio', '1'),
						'similaire' => get_wpappninja_option('similaire', '1'),
						'share' => get_wpappninja_option('share', '1'),
						'commentaire' => get_wpappninja_option('commentaire', '1'),
						'bienvenue' => nl2br(get_wpappninja_option('bienvenue_' . $wpappninja_locale, '')),
						'mentions' => nl2br(get_wpappninja_option('mentions_' . $wpappninja_locale, '')),
						'projectid' => get_wpappninja_option('project', ''),
						'ga' => get_wpappninja_option('ga', ''),
						'admob_float' => get_wpappninja_option('admob_float', ''),
						'admob_splash' => get_wpappninja_option('admob_splash', ''),
						'admob_t' => get_wpappninja_option('admob_t', ''),
						'admob_b' => get_wpappninja_option('admob_b', ''),
						'bee' => $spamBee,
				);
				
		$json['forms'] = array();
		$json['data'][] = array(
							'name' => '_NB_',
							'id' => '',
							'nb' => '',
							'icon' => ''
						);
		$json['data'][] = array(
							'name' => '_divider_',
							'id' => '0',
							'nb' => '0',
							'icon' => ''
						);
		
		$category = get_terms('category', array(
											'parent' => 0,
											'hide_empty' => true,
											'exclude' => get_wpappninja_option('menu')
										)
								);

		foreach ( $category as $cat ) {
			$json['data'][] = array(
						'name' => html_entity_decode(trim($cat->name)),
						'id' => strval($cat->term_id),
						'nb' => '',
						'icon' => 'folder'
					);
		}
	}
	
	$json['config'][] = array_merge($api, $deprecated);

	return json_encode($json);
}

/**
 * Get recent posts
 *
 * @since 1.0
 */
function wpappninja_recent($offset = 0, $isCat = 0, $isSearch = false, $silenced = '', $similaire = false) {

	$json = array();
	$json['data'] = array();

	// notifications archive
	if ($isCat == '-100') {

		global $wpdb;
		$query = $wpdb->get_results($wpdb->prepare("SELECT `titre`, `message`, `image`, `id`, `send_date` FROM {$wpdb->prefix}wpappninja_push WHERE `sended` = %s AND (lang = %s OR lang = 'all') ORDER BY `send_date` DESC LIMIT %d,%d", '1', wpappninja_get_lang(), $offset, 10));
		foreach($query as $obj) {

			$excerpt = wpappninja_human_time(current_time('timestamp') - $obj->send_date);

			$json['data'][] = array(
								'maxID' => "0",
								'sticky' => "0",
								'cat' => "",
								'titre' => wp_strip_all_tags(stripslashes(html_entity_decode($obj->titre))),
								'texte' => stripslashes($excerpt),
								'image' => $obj->image,
								'id' => "-" . strval($obj->id),
								'backgroundColor' => "FFFFFF"
							);
		}

		return json_encode($json);
	}
	
	$search = '';
	if($isSearch) {
		$search = $isSearch;
		$isCat = get_wpappninja_option('search_cat', '');
	}
	
	$stickys = array();
	if (is_array(get_option( 'sticky_posts' ))) {
		$stickys = get_option( 'sticky_posts' );
	}
	$catIsSticky = false;

	$silent = array();
	if ($isCat == 0 && !$isSearch) {
		if ($silenced != '') {
			if (is_array(get_wpappninja_option( 'silent' , ''))) {
				$silent = get_wpappninja_option( 'silent' , '');
			}
			$silents_custom = explode('!', $silenced);
			foreach($silents_custom as $silent_custom) {
				if ($silent_custom != '') {
					if (!in_array($silent_custom, $silent)) {
						$silent[] = $silent_custom;
					}
				}
			}
		}

		// add child
		foreach ($silent as $s) {
			$silent = array_merge( $silent, get_terms('category', array( 'child_of' => $s, 'fields'=>'ids' ) ) );
		}
	}

	if (isset($stickys[0]) && !$isSearch) {
		$catIsSticky = true;
	}

	$has_password = null;
	if (get_wpappninja_option('has_password', "0") == "0") {
		$has_password = false;
	}

	$get_recent_post = array('orderby' => get_wpappninja_option('orderby_list', 'post_date'),'order' => get_wpappninja_option('order_list', 'DESC'), 'lang' => wpappninja_get_lang(), 'has_password' => $has_password,'category__not_in' => $silent, 'date_query' => array('column'  => 'post_date', 'after'   => '- '.get_wpappninja_option('maxage', '365000').' days'),'tag__not_in' => get_wpappninja_option('excluded',''),'s' => $search, 'post_status' => 'publish','post_type' => get_post_types(array('public'=>true)),'offset'=>$offset);

	if ($search != "") {
		$get_recent_post['orderby'] = get_wpappninja_option('orderby_search', 'relevance');
		$get_recent_post['order'] = get_wpappninja_option('order_search', 'DESC');
	}
	
	if (defined('WPAPPNINJA_API_DEBUG')) {echo $isCat;}
	if ($isCat != 0 || $search != "") {

		$taxonomy = wpappninja_get_all_taxonomy();
		foreach ($taxonomy as $tax) {
			$obj = get_term_by('id', round($isCat), $tax);
			if (is_object($obj)) {
				$the_taxo = $obj;
				break;
			}
		}

		if (defined('WPAPPNINJA_API_DEBUG')) {print_r($the_taxo);}

		if ($the_taxo === false OR $the_taxo->taxonomy == "") {

			$get_recent_post['category'] = $isCat;

		} else {

			$get_recent_post['tax_query'] = array(
				array(
					'taxonomy' => $the_taxo->taxonomy,
					'terms' => $the_taxo->term_id
				)
			);

		}
	} else {

		$get_recent_post['post_type'] = 'post';

	}
	if (defined('WPAPPNINJA_API_DEBUG')) {print_r($data);print_r($get_recent_post);}


	// STICKY POST
	$numberposts = 10;

	$get_sticky_post = $get_recent_post;
	$get_sticky_post['posts_per_page'] = 1;
	$get_sticky_post['post__in'] = $stickys;
	$get_sticky_post['ignore_sticky_posts'] = 1;
	$get_sticky_post['numberposts'] = 1;
	$get_sticky_post['offset'] = 0;

	$firstSticky = wp_get_recent_posts($get_sticky_post);

	if (count($firstSticky) > 1) {
		$firstSticky = array_slice($firstSticky, 0, 1);
	}

	$postExclude = '';
	if (isset($firstSticky[0]['ID'])) {
		$postExclude = $firstSticky[0]['ID'];
	}

	if ($offset == 0) {

		$sticky = $firstSticky;
		if ($postExclude != '') {
			$numberposts = 9;
		}

	} else {

		$sticky = array();
		if ($postExclude != '') {
			$get_recent_post['offset'] = ($offset - 1);
		}

	}

	$get_recent_post['post__not_in'] = array( $postExclude );
	$get_recent_post['numberposts'] = $numberposts;
	$get_recent_post['posts_per_page'] = $numberposts;
	
	$normal = wp_get_recent_posts($get_recent_post);

	$data = array_merge($sticky, $normal);

	if (defined('WPAPPNINJA_API_DEBUG')) {print_r($data);print_r($normal);print_r($get_recent_post);}

	foreach($data as $d) {
			
		if (in_array($d['ID'], $stickys)) {
			$hessticky = '1';
		} else {
			$hessticky = '0';
		}

		$categories = get_the_category($d['ID']);
		$separator = ' ';
		$output = '';
		$cat = '';
		if($categories){
			if ($categories[0]->parent == 0) {
				$cat = $categories[0]->name;
				$catID = $categories[0]->term_id;
			} else {
				$parent = get_term_by( 'id', $categories[0]->parent, 'category' );
				$cat = $parent->name;
				$catID = $parent->term_id;
			}
		}

		$content_post = get_post($d['ID']);

		$excerpt = wpappninja_human_time(current_time('timestamp') - strtotime($content_post->post_date));

		$image = wpappninja_get_image($d['ID']);

		if (defined("WPAPPNINJA_FORCE_IMG")) {
			$image = wpappninja_get_image($d['ID'], '0', true);
		}
		
		$titre = $d['post_title'];
		
		$json['data'][] = array(
								'maxID' => strval(strtotime($content_post->post_date)),
								'sticky' => $hessticky,
								'cat' => html_entity_decode($cat),
								'titre' => wp_strip_all_tags(html_entity_decode($titre)),
								'texte' => $excerpt,
								'image' => $image,
								'id' => strval($d['ID']),
								'backgroundColor' => "FFFFFF"
							);
	}
	
	return json_encode($json);
}

/**
 * Read post
 */
function wpappninja_read($id) {

	$json = array();
	$json['data'] = array();

	// push notification
	if (substr($id, 0, 4) == "-999") {

		if (1>0) {

			$date = "";

			$bio = array();
			$bio[] = array(
				'avatar' => "",
				'name' => "",
				'description' => "",
				'url' => ""
			);

			$api = array(
					'catID' => "0",
					'isPage' => "1",
					'cat' => "",
					'info_name' => "",
					'info_avatar' => "",
					'info' => $date,
					'share_permalink' => '',
					'permalink' => home_url( '/' ) . "?wpappninja_read_enhanced=" . $id,
					'titre' => "",
					'texte' => "",
					'image' => "",
					'id' => -999,
					'bio' => array('data' => $bio),
					'similaire' => array('data' => array()),
					'commentaire_count' => "0",
					'commentopen' => "0"
				);
		}

		if (isset($api)) {
			$json['data'][] = $api;
		} else {
			$json['data'][] = array('id' => '0');
		}
		return json_encode($json);

	} elseif (substr($id, 0, 1) == "-") {

		global $wpdb;

		$id = str_replace("-", "", $id);

		$query = $wpdb->get_results($wpdb->prepare("SELECT `titre`, `message`, `image`, `id`, `id_post`, `send_date` FROM {$wpdb->prefix}wpappninja_push WHERE `sended` = %s AND (lang = %s OR lang = 'all') AND id = %s", '1', wpappninja_get_lang(), $id));
		foreach($query as $obj) {

			$date = wpappninja_human_time(current_time('timestamp') - $obj->send_date);

			$bio = array();
			$bio[] = array(
				'avatar' => "",
				'name' => "",
				'description' => "",
				'url' => ""
			);

			if ($obj->id_post > 0) {
				$read_link = '<br/><br/><a href="' . get_permalink( $obj->id_post ) . '">' . __('Read', 'wpappninja') . '</a>';
				$read_link = wpappninja_deeplink_process($read_link);
			}

			$api = array(
					'catID' => "0",
					'isPage' => "1",
					'cat' => "",
					'info_name' => "",
					'info_avatar' => "",
					'info' => $date,
					'share_permalink' => '',
					'permalink' => home_url( '/' ) . "?wpappninja_read_enhanced=-" . $id,
					'titre' => wp_strip_all_tags(stripslashes($obj->titre)),
					'texte' => stripslashes($obj->message) . " " . $read_link,
					'image' => $obj->image,
					'id' => strval($id),
					'bio' => array('data' => $bio),
					'similaire' => array('data' => array()),
					'commentaire_count' => "0",
					'commentopen' => "0"
				);
		}

		if (isset($api)) {
			$json['data'][] = $api;
		} else {
			$json['data'][] = array('id' => '0');
		}
		return json_encode($json);

	}

	$url = "";
	if (preg_match('#^http#', $id)) {
		$url = preg_replace('#__sla_sh__#', '/', preg_replace('#__dot__#', ':', $id));

		$scheme = parse_url( home_url(), PHP_URL_SCHEME );
    	$url = set_url_scheme( $url, $scheme );

		$id = url_to_postid($url);
		if ($id === 0) {
			$id = wpappninja_url_to_catid($url);
			if ($id != 0) {
				$json['data'][] = array('id' => 'cat', 'id_cat' => strval($id));
				return json_encode($json);
			} else {
				$id = wpappninja_url_to_postid($url);
			}
		}
	}
	
	if ($id === 0) {
			
		$bio = array();
		$bio[] = array(
			'avatar' => "",
			'name' => "",
			'description' => "",
			'url' => ""
		);

		$api = array(
			'catID' => "0",
			'isPage' => "1",
			'cat' => "",
			'info_name' => "",
			'info_avatar' => "",
			'info' => "",
			'share_permalink' => '',
			'permalink' => $url,
			'titre' => "",
			'texte' => "",
			'image' => "",
			'id' => "0",
			'bio' => array('data' => $bio),
			'similaire' => array('data' => array()),
			'commentaire_count' => "0",
			'commentopen' => "0"
		);

		$json['data'][] = $api;

		return json_encode($json);
	}
	
	$content_post = get_post($id);
	
	if ($content_post == null) {
		return '{"data":[]}';
	}

	$categories = get_the_category($id);
	$separator = ' ';
	$output = '';
	$cat = '';
	$catID = '';
	$nbcomment = '';
	if($categories){
		if ($categories[0]->parent == 0) {
			$cat = $categories[0]->name;
			$catID = $categories[0]->term_id;
		} else {
			$parent = get_term_by( 'id', $categories[0]->parent, 'category' );
			$cat = $parent->name;
			$catID = $parent->term_id;
		}
	}
	
	$info = wpappninja_human_time(current_time('timestamp') - strtotime($content_post->post_date));
	
	$content = '';
	if (wpappninja_webview_mode($id) == '0') {
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($content_post->post_content, $content, 'filter the content');$wpappninja_diff = $content;}
		$content = apply_filters('appandroid_content', $content, array('id' => $id));
		$content = get_wpappninja_option('beforepost', '') . $content . get_wpappninja_option('afterpost', '');
		$content = strip_shortcodes( $content );
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'strip shortcode');$wpappninja_diff = $content;}
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = str_replace("&nbsp;", " ", $content);
		$content = html_entity_decode($content);
		$content = wpappninja_pre_tags($content);
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'pre tags');$wpappninja_diff = $content;}
		$content = str_replace(array("\r", "\n"),"", $content);
	
		// youtube
		$content = preg_replace('/<iframe.*?src=[\'"](https?:)?(\/\/)?(www\.)?(youtu\.be\/|youtube(-nocookie)?\.[a-z]{2,4}(?:\/embed\/|\/v\/|\/watch\?.*?v=))([\w\-]{10,12})([\?|&]?.*?)?[\'"]([^>]+|)><\/iframe>/', '$1$2$3$4$5$6', $content);
		$content = preg_replace('/>(https?:)?(\/\/)?(www\.)?(youtu\.be\/|youtube(-nocookie)?\.[a-z]{2,4}(?:\/embed\/|\/v\/|\/watch\?.*?v=))([\w\-]{10,12})([\?|&]?.*?)?</', '><a href="https://www.youtube.com/watch?v=$6"><img src="https://api.wpmobile.app/youtube/$6.png" /></a><br/><', $content);
		$content = preg_replace('/>(https?:)?(\/\/)?(www\.)?(youtu\.be\/|youtube(-nocookie)?\.[a-z]{2,4}(?:\/embed\/|\/v\/|\/watch\?.*?v=))([\w\-]{10,12})([\?|&]?.*?)?/', '><a href="https://www.youtube.com/watch?v=$6"><img src="https://api.wpmobile.app/youtube/$6.png" /></a><br/>', $content);
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'youtube');$wpappninja_diff = $content;}
		
		// dailymotion
		$content = preg_replace('/<iframe.*?src=[\'"](https?:)?(\/\/)?(www\.)?(dailymotion\.[a-z]{2,4}(?:\/embed\/video\/))([\w\-]{5,20})([\?|&]?.*?)?[\'"]([^>]+|)><\/iframe>/', '<a href="https://www.dailymotion.com/video/$5"><img src="https://api.wpmobile.app/dailymotion/$5.png" /></a>', $content);
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'dailymotion');$wpappninja_diff = $content;}


		// vimeo
		$content = preg_replace('/<iframe.*?src=[\'"](https?:)?(\/\/)?(player\.)(vimeo\.[a-z]{2,4}(?:\/video\/))([\w\-]{5,20})([\?|&]?.*?)?[\'"]([^>]+|)><\/iframe>/', '<a href="https://vimeo.com/$5"><img src="https://api.wpmobile.app/vimeo/$5.png" /></a>', $content);
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'vimeo');$wpappninja_diff = $content;}


	
		// convert table to list
		$content = preg_replace('#<tr([^>]+|)>#', '<ul>', $content);
		$content = preg_replace('#</tr>#', '</ul><br/>', $content);

		$content = preg_replace('#<thead([^>]+|)>#', '<span>', $content);
		$content = preg_replace('#</thead>#', '</span>', $content);

		$content = preg_replace('#<th([^>]+|)>#', '<li><b>', $content);
		$content = preg_replace('#</th>#', '</b></li>', $content);

		$content = preg_replace('#<td([^>]+|)>#', '<li>', $content);
		$content = preg_replace('#</td>#', '</li>', $content);
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'table');$wpappninja_diff = $content;}
	
		// suppression des shortcodes recalcitrants
		$content = preg_replace('/\[[^\]]+\]/', '', $content);
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'shortcodes');$wpappninja_diff = $content;}
	
		// tweet
		$content = preg_replace_callback('/p>https?:\/\/twitter\.com\/(?:#!\/)?(\w+)\/status(es)?\/(\d+)</', function ($matches) {
			$jsonTweet = json_decode(file_get_contents('https://api.twitter.com/1/statuses/oembed.json?url=https://twitter.com/'.$matches[1].'/status/'.$matches[3]), TRUE);
			return 'p>'.$jsonTweet['html'].'<';
		}, $content);
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'tweet');$wpappninja_diff = $content;}

		$content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
		$content = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $content);
		$content = preg_replace('#	#is', '', $content);
		$content = preg_replace('# style="[^"]+"#is', '', $content);
		$content = preg_replace('# class="(?!wp-smiley)[^"]+"#is', '', $content);
		if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'class css js');$wpappninja_diff = $content;}
	
		// remove content by regex
		$regex = get_wpappninja_option('regex');
		if ($regex !== FALSE) {
			$rules = explode("\n", $regex);
		
			foreach($rules as $rule) {
				$rule = trim(preg_replace('/^\s+|\n|\r|\s+$/m', '', $rule));
				$content = preg_replace($rule, '', $content);
			}
			
			if (defined('WPAPPNINJA_API_DEBUG')) {echo wpappninja_html_diff($wpappninja_diff, $content, 'regex');$wpappninja_diff = $content;}
		}
	}

	if (defined('WPAPPNINJA_API_DEBUG')) {echo '<br/><br/><hr/><br/>' . $content;}
	
	$titre = html_entity_decode(get_the_title($id));
	
	//$data = get_terms('category', array('hide_empty' => 1));
	$has_password = null;
	if (get_wpappninja_option('has_password', "0") == "0") {
		$has_password = false;
	}

	// similar articles
	$terms = wp_get_post_terms( $id, get_wpappninja_option('similartype', 'category'), array('fields' => 'ids') );
	$args = array(
		'has_password' => $has_password,
		'date_query' => array('column'  => 'post_date', 'after'   => '- '.get_wpappninja_option('maxage', '365000').' days'),
		'tag__not_in' => get_wpappninja_option('excluded',''),
		'post__not_in' => array( $id ),
		'numberposts' => get_wpappninja_option('similarnb', 10),
		'offset' => 0,
		'orderby' => get_wpappninja_option('orderby_list', 'post_date'),
		'order' => get_wpappninja_option('order_list', 'DESC'),
		'post_type' => get_post_types(array('public'=>true)),
		'post_status' => 'publish',
		        'tax_query' => array(
					array(
						'taxonomy' => get_wpappninja_option('similartype', 'category'),
						'terms' => $terms,
						'include_children' => false
					)
				)
			);
	
	$similaire = array();
	if (get_wpappninja_option('similarnb', 10) > 0) {
		if ($content_post->post_type != 'page') {
			$recent_posts = wp_get_recent_posts( $args, OBJECT );
			if (is_array($recent_posts)) {
				foreach($recent_posts as $p) {
					$title_similaire = wp_strip_all_tags(html_entity_decode($p->post_title));
					$content_post_simil = get_post($p->ID);
					$excerpt = wpappninja_human_time(current_time('timestamp') - strtotime($content_post_simil->post_date));
					$similaire[] = array(
										'excerpt' => $excerpt,
										'postID' => strval($p->ID),
										'titre' => $title_similaire,
										'image' => wpappninja_get_image($p->ID)
									);
				}
			}
		}
	}
	
	$bio = array();

	if (wpappninja_webview_mode(0) == '4') {

		$bio[] = array(
				'avatar' => "",
				'name' => "",
				'description' => "",
				'url' => ""
			);

	} else {

		$bio[] = array(
				'avatar' => wpappninja_get_gravatar(get_the_author_meta('user_email', $content_post->post_author)),
				'name' => get_the_author_meta('display_name', $content_post->post_author),
				'description' => nl2br(get_the_author_meta('description', $content_post->post_author)),
				'url' => get_the_author_meta('user_url', $content_post->post_author)
			);

	}
	
	$commentaire = array();
	$commentisopen = '0';
	if (comments_open( $id )) {
		$commentisopen = '1';
	}
	$nbcomment = $content_post->comment_count;
	
	$isPage = '0';
	if ($content_post->post_type == 'page') {
		$isPage = '1';
	}
	$image = wpappninja_get_image($id, $isPage, false, true);
	$permalink = get_permalink($id);
	if ($id == get_option('page_on_front')) {
		$permalink = get_home_url();
	}

	if (function_exists('icl_get_home_url') && $permalink == get_home_url().'/home/') {
		$permalink = get_home_url();
	}

	$share_permalink  = $permalink;
	
	if ($content == "") {
		//$permalink .= (preg_match('#\?#', $permalink) ? '&' : '?') . 'WPAPPNINJA=PREVENT_CACHE';

		if (wpappninja_webview_mode($id) == '1') {
			$permalink = home_url( '/' ) . "?wpappninja_read_enhanced=" . $id;
		}
	} else {
		$content .= '<p><br/></p>';
	}

	$content = wpappninja_deeplink_process($content);

	$api = array(
						'catID' => strval($catID),
						'isPage' => $isPage,
						'cat' => html_entity_decode($cat),
						'info_name' => get_the_author_meta('display_name', $content_post->post_author),
						'info_avatar' => wpappninja_get_gravatar(get_the_author_meta('user_email', $content_post->post_author)),
						'info' => $info,
						'permalink' => wpappninja_translate($permalink),
						'share_permalink' => $share_permalink,
						'titre' => html_entity_decode(wp_strip_all_tags($titre)),
						'texte' => $content,
						'image' => $image,
						'id' => strval($id),
						'bio' => array('data' => $bio),
						'similaire' => array('data' => $similaire),
						'commentaire_count' => $nbcomment,
						'commentopen' => $commentisopen
					);
					
	$deprecated = array();
	if ( preg_match( '#/android_json/(.*)$#', $_SERVER['REQUEST_URI'], $match )) {
		$deprecated['commentaire']['data'] = array();
	}
	
	$json['data'][] = array_merge($api, $deprecated);

	return json_encode($json);
}

/**
 * Get comments
 *
 * int $id Post ID
 * int $offset Pagination
 * @since 1.0
 */
function wpappninja_comment($id, $offset = 0) {

	$json = array();
	$json['data'] = array();
	//if (comments_open( $id )) {
		$comments = get_comments('status=approve&offset=' . $offset . '&number=10&post_id=' . $id);
		foreach($comments as $comment) {
			if ($comment->comment_approved == 1) {
				
				$titre = strtoupper($comment->comment_author);
				$texte = wpappninja_add_links(nl2br($comment->comment_content));

				// is a child
				if ($comment->comment_parent != 0) {
					$parent = get_comment( $comment->comment_parent );
					if (is_object($parent)) {
						$titre .= ' @ ' . strtoupper($parent->comment_author);
						$texte = '<blockquote><i>' . wpappninja_add_links(wp_strip_all_tags($parent->comment_content)) . '</i></blockquote>' . $texte;
					}
				}

				$texte = wp_strip_all_tags(wpappninja_deeplink_process($texte));
				
				$json['data'][] = array(
									'maxID'		=> '1',
									'sticky'	=> 'nope',
									'id'		=> '0',
									'image'		=> wpappninja_get_gravatar($comment->comment_author_email),
									'titre'		=> wp_strip_all_tags($titre),
									'texte'		=> $texte,
									'cat'		=> wpappninja_human_time(current_time('timestamp') - strtotime($comment->comment_date)),
									'backgroundColor' => "FFFFFF"
								);
			}
		}
	//}

	return json_encode($json);
}

/**
 * Redirect post to the web version
 *
 * int $id Post ID
 * @since 1.0
 */
function wpappninja_redirection($id) {
	$url = get_permalink($id);
	header('Location: ' . $url, TRUE, 301);
	exit(0);
}

/**
 * Render gravity form in html
 *
 * int $id Form ID
 * @since 1.0
 */
function wpappninja_form($id) {
	header("HTTP/1.1 200 OK");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

	preg_match('/type=form\/([^&]+)&/', $_SERVER['REQUEST_URI'], $m);

	if (preg_match('/^http/', $m[1])) {
		header('Location: ' . $m[1]);
		exit();
	}

	if ( ! class_exists( 'RGForms' ) ) {
		for ( $i = 0; $i < $depth = 10; $i ++ ) {
			$wp_root_path = str_repeat( '../', $i );
			if ( file_exists( "{$wp_root_path}wp-load.php" ) ) {
				require_once( "{$wp_root_path}wp-load.php" );
				break;
			}
		}
	}
	
	if (!class_exists('GFCommon')) {
		echo 'ERROR';
		exit(0);
	}

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width"> 
		<link rel='stylesheet' href='<?php echo GFCommon::get_base_url() ?>/css/reset.min.css' type='text/css' />
		<link rel='stylesheet' href='<?php echo GFCommon::get_base_url() ?>/css/formreset.min.css' type='text/css' />
		<link rel='stylesheet' href='<?php echo GFCommon::get_base_url() ?>/css/datepicker.min.css' type='text/css' />
		<link rel='stylesheet' href='<?php echo GFCommon::get_base_url() ?>/css/formsmain.min.css' type='text/css' />
		<link rel='stylesheet' href='<?php echo GFCommon::get_base_url() ?>/css/readyclass.min.css' type='text/css' />
		<link rel='stylesheet' href='<?php echo GFCommon::get_base_url() ?>/css/browsers.min.css' type='text/css' />
		<link rel='stylesheet' href='<?php echo GFCommon::get_base_url() ?>/css/preview.min.css' type='text/css' />
		<?php
		require_once( GFCommon::get_base_path() . '/form_display.php' );
		$form = RGFormsModel::get_form_meta( $id );
		GFFormDisplay::enqueue_form_scripts( $form );
		wp_print_scripts();
		$styles = apply_filters( 'gform_preview_styles', array(), $form );
		if ( ! empty( $styles ) ) {
			wp_print_styles( $styles );
		}
		?>
		<style type="text/css">
		body {background:#fff !important;}
		input {width: 97.5% !important;}
		<?php
		if (isset($_GET['night'])) {
			?>
			form,#preview_form_container,body {background:#000 !important;color:#fff}
			input, textarea {background: #333;border-color: #666;color: #fff;}
			<?php
		}
		?>
		body input.button {background-color: #<?php echo substr(sanitize_text_field($_GET['c']), 0, 6);?> !important;box-shadow: 0 0px 0 #000 !important;}
		</style>
	</head>
	<body style="font-size: 1.2em;">
		<div id="preview_form_container" style="margin:0">
		<?php
		echo RGForms::get_form( $id, true, true, true);
		?>
		</div>
		<?php
		do_action( 'gform_preview_footer', $id );
		?>
	</body>
	</html>
	<?php
	exit(0);
}

/**
 * Show favorited posts
 */
function wpappninja_favoris($offset = 0, $favoris = '') {

	$json = array();
	

	$stickys = array();
	if (is_array(get_option( 'sticky_posts' ))) {
		$stickys = get_option( 'sticky_posts' );
	}

	$fav = array();
	if ($favoris != '') {
		$silents_custom = explode('!', $favoris);
		foreach($silents_custom as $silent_custom) {
			if ($silent_custom != '') {
				if (!in_array($silent_custom, $fav)) {
					$fav[] = $silent_custom;
				}
			}
		}
	}

	if (count($fav) == 0) {
		return '{"data":[]}';
	}
	
	$has_password = null;
	if (get_wpappninja_option('has_password', "0") == "0") {
		$has_password = false;
	}

	$data = wp_get_recent_posts(array(
										'has_password' => $has_password,
										'post__in' => $fav, 
										'post_type' => get_post_types(array('public'=>true)),
										'post_status' => 'publish',
										'numberposts' => 10,
										'offset'=>$offset
									)
								);
	
	foreach($data as $d) {
			
		if (in_array($d['ID'], $stickys)) {
			$hessticky = '1';
		} else {
			$hessticky = '0';
		}
	
		$categories = get_the_category($d['ID']);
		$separator = ' ';
		$output = '';
		$cat = '';
		if($categories){
			if ($categories[0]->parent == 0) {
				$cat = $categories[0]->name;
				$catID = $categories[0]->term_id;
			} else {
				$parent = get_term_by( 'id', $categories[0]->parent, 'category' );
				$cat = $parent->name;
				$catID = $parent->term_id;
			}
		}

		$content_post = get_post($d['ID']);

		$excerpt = wpappninja_human_time(current_time('timestamp') - strtotime($content_post->post_date));
		$image = wpappninja_get_image($d['ID']);

		$titre = $d['post_title'];

		$json['data'][] = array(
								'maxID' => strval(strtotime($content_post->post_date)),
								'sticky' => $hessticky,
								'cat' => html_entity_decode($cat),
								'titre' => wp_strip_all_tags(html_entity_decode($titre)),
								'texte' => $excerpt,
								'image' => $image,
								'id' => strval($d['ID']),
								'backgroundColor' => "FFFFFF"
							);
	}
	
	return json_encode($json);
}
