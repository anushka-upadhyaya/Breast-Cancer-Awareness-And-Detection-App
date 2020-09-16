<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

/**
 * Return the main color of the current theme.
 *
 * @since 1.0
 */
function wpappninja_get_hex_color($secondary = false) {

	if (get_wpappninja_option('speed') == '1') {

		$app_data = get_wpappninja_option('app');
		$app_theme_primary = isset($app_data['theme']['primary']) ? $app_data['theme']['primary'] : "#0f53a6";
		$app_theme_accent = isset($app_data['theme']['accent']) ? $app_data['theme']['accent'] : "#dd9933";

		if ($secondary) {
			return $app_theme_accent;
		}

		return get_wpappninja_option('css_' . md5('body.wpappninja .navbar,body.wpappninja .tabbar,body.wpappninja .toolbarbackground'), $app_theme_primary);
	}
	
	switch (get_wpappninja_option('theme', '')) {
		case 'black':
			$color = '#818286';
			if ($secondary) {
				$color = '#000000';
			}
			break;

		case 'grisrouge':
			$color = '#607D8B';
			if ($secondary) {
				$color = '#ea5758';
			}
			break;
			
		case 'blueyellow':
			$color = '#2196f3';
			if ($secondary) {
				$color = '#ffeb3b';
			}
			break;

		case 'vert':
			$color = '#4CAF50';
			if ($secondary) {
				$color = '#A62A54';
			}
			break;
			
		case 'rouge':
			$color = '#F44336';
			if ($secondary) {
				$color = '#8BC34A';
			}
			break;
		
		case 'redblack':
			$color = '#f44336';
			if ($secondary) {
				$color = '#000000';
			}
			break;
			
		case 'lime':
			$color = '#cddc39';
			if ($secondary) {
				$color = '#ff4081';
			}
			break;
			
		case 'orangeblue':
			$color = '#ff9800';
			if ($secondary) {
				$color = '#536dfe';
			}
			break;

		case 'premium':

			$app_data = get_wpappninja_option('app');

			$app_theme_primary = isset($app_data['theme']['primary']) ? $app_data['theme']['primary'] : "#0f53a6";
			$app_theme_accent = isset($app_data['theme']['accent']) ? $app_data['theme']['accent'] : "#dd9933";

			$color = $app_theme_primary;
			if ($secondary) {
				$color = $app_theme_accent;
			}
			break;
			
		default:
			$color = '#03A9F4';
			if ($secondary) {
				$color = '#FF5252';
			}
		}
	
	return $color;
}

/**
 * Adjust the brightness.
 *
 * @since 5.1.3
 */
function wpappninja_adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}

/**
 * Get CSS default.
 *
 * @since 6.1
 */
function wpappninja_get_default($md5) {

	$classes = wpappninja_get_css_rules(true);

	foreach($classes as $c) {
		if (md5($c['class'] . $c['zone']) == $md5) {
			return $c['color'];
		}
	}
}

/**
 * Render the widget for the mobile theme.
 *
 * @since 6.2
 */
function wpappninja_widget($id) {

	if (get_wpappninja_option('widget_' . md5($id)) !== false) {

		$widget = get_wpappninja_option('widget_' . md5($id));
		if ($widget != '' && str_replace('&nbsp;', '', htmlentities($widget)) == '') {
			$widget = "";
		}

		if ($widget != "" && $widget != "&nbsp;") {
			return '<div class="wpmobile-widget-'.$id.'">'.do_shortcode($widget).'</div>';
		} else {
			return;
		}
	}

	$widgets = wpappninja_get_widgets();

	foreach($widgets as $w) {
		if ($w['id'] == $id && $w['default'] != "" && $w['default'] != "&nbsp;") {
			return '<div class="wpmobile-widget-'.$id.'">'.do_shortcode($w['default']).'</div>';
		}
	}

}

/**
 * Widget for the mobile theme.
 *
 * @since 6.2
 */
function wpappninja_get_widgets() {

	$app_data = get_wpappninja_option('app');
	$app_name = isset($app_data['name']) ? $app_data['name'] : wpappninja_get_appname();
	$app_logo = isset($app_data['logo']) ? $app_data['logo'] : "";

	if ($app_logo != "") {
		$navbar_middle = '<img src="' . $app_logo . '" />';
	} else {
		$navbar_middle = $app_name;
	}

	$widgets = array(
					array('help' => __('Before the left menu', 'wpappninja'), 'id' => 'menu-top', 'default' => "[wpapp_login]", 'section' => __('Menu', 'wpappninja')),
					array('help' => __('After the left menu', 'wpappninja'), 'id' => 'menu-bottom', 'default' => "[wpapp_push]", 'section' => __('Menu', 'wpappninja')),
					array('help' => __('Middle of the navbar', 'wpappninja'), 'id' => 'navbar-middle', 'default' => "[wpapp_title_main]", 'section' => __('Toolbar', 'wpappninja')),
					array('help' => __('Right of the navbar', 'wpappninja'), 'id' => 'navbar-right', 'default' => "[wpmobile_notification_badge]", 'section' => __('Toolbar', 'wpappninja')),
					//array('help' => __('Before the page', 'wpappninja'), 'id' => 'content-top', 'default' => "", 'section' => __('Content', 'wpappninja')),
					//array('help' => __('After the page', 'wpappninja'), 'id' => 'content-bottom', 'default' => "", 'section' => __('Content', 'wpappninja')),

					array('help' => __('Before a page', 'wpappninja'), 'id' => 'page-top', 'default' => "[wpapp_title]", 'section' => __('Content', 'wpappninja')),
					array('help' => __('After a page', 'wpappninja'), 'id' => 'page-bottom', 'default' => "", 'section' => __('Content', 'wpappninja')),

					array('help' => __('Before a WooCommerce page', 'wpappninja'), 'id' => 'woocommerce-top', 'default' => "", 'section' => __('Content', 'wpappninja')),
					array('help' => __('After a WooCommerce page', 'wpappninja'), 'id' => 'woocommerce-bottom', 'default' => "", 'section' => __('Content', 'wpappninja')),

					array('help' => __('Before a BuddyPress page', 'wpappninja'), 'id' => 'buddypress-top', 'default' => "", 'section' => __('Content', 'wpappninja')),
					array('help' => __('After a BuddyPress page', 'wpappninja'), 'id' => 'buddypress-bottom', 'default' => "", 'section' => __('Content', 'wpappninja')),

					array('help' => __('Before other post type', 'wpappninja'), 'id' => 'post-top', 'default' => "[wpapp_image][wpapp_title][wpapp_author]", 'section' => __('Content', 'wpappninja')),
					array('help' => __('After other post type', 'wpappninja'), 'id' => 'post-bottom', 'default' => "[wpapp_social][wpapp_comment]", 'section' => __('Content', 'wpappninja')),



					array('help' => __('Before lists', 'wpappninja'), 'id' => 'list-top', 'default' => "", 'section' => __('List', 'wpappninja')),
					array('help' => __('After lists', 'wpappninja'), 'id' => 'list-bottom', 'default' => "", 'section' => __('List', 'wpappninja')),

					array('help' => __('Card content on lists', 'wpappninja'), 'id' => 'card-content', 'default' => "[wpapp_image] [wpapp_title] [wpapp_excerpt]", 'section' => __('List', 'wpappninja')),
				);


	return $widgets;
}

/**
 * CSS Rules for the mobile theme.
 *
 * @since 6.1
 */
function wpappninja_get_css_rules($default = false) {

	$app_theme_primary = "#0f53a6";
	$app_theme_accent = "#dd9933";
	$show_img_post = "block";
	$show_img_list = "block";
	$show_commentaire = "block";
	$show_search = "flex";
	$show_title = "block";
	$show_avatar = "flex";

	if (get_wpappninja_option('fullspeed', '0') == '0' && !$default) {
		$app_data = get_wpappninja_option('app');
		$app_theme_primary = isset($app_data['theme']['primary']) ? $app_data['theme']['primary'] : "#0f53a6";
		$app_theme_accent = isset($app_data['theme']['accent']) ? $app_data['theme']['accent'] : "#dd9933";
		$show_img_post = (get_wpappninja_option('hideimgonlypage') == "1") ? "block" : "none";
		$show_img_list = (get_wpappninja_option('disablefeat') == "0") ? "block" : "none";
		$show_commentaire = (get_wpappninja_option('commentaire') == "1") ? "block" : "none";
		$show_search = (get_wpappninja_option('show_search') == "1") ? "block" : "none";
		$show_title = (get_wpappninja_option('remove_title') == "0") ? "block" : "none";
		$show_avatar = (get_wpappninja_option('show_avatar') == "1") ? "flex" : "none";
	}


	$classes = array(

					array('help' => __('App background', 'wpappninja'), 'class' => 'html,body,.page-content.pull-to-refresh-content,.page.navbar-fixed,.div.page.navbar-fixed,.content-block', 'zone' => 'background', 'color' => '#eee', 'section' => __('Background', 'wpappninja')),

					array('help' => __('Main content background', 'wpappninja'), 'class' => '.post', 'zone' => 'background', 'color' => '#fff', 'section' => __('Background', 'wpappninja')),


					array('help' => __('Status bar background', 'wpappninja'), 'class' => '.progressbar,.progressbar-infinite,.statusbar-overlay', 'zone' => 'background', 'color' => $app_theme_primary, 'section' => __('Toolbar', 'wpappninja')),

					array('help' => __('Navigation bar background', 'wpappninja'), 'class' => 'body.wpappninja .navbar,body.wpappninja .tabbar,body.wpappninja .toolbar', 'zone' => 'background', 'color' => $app_theme_primary, 'section' => __('Toolbar', 'wpappninja')),

					array('help' => __('Navigation bar icon color', 'wpappninja'), 'class' => 'html.wpappninja body.theme-wpappninja .toolbar-inner a, .navbar i.icon, .navbar .center', 'zone' => 'color', 'color' => '#fff', 'section' => __('Toolbar', 'wpappninja')),

					array('help' => __('Bottom menu background', 'wpappninja'), 'class' => 'html.wpappninja body.wpapp-ios .toolbar', 'zone' => 'background', 'color' => $app_theme_primary, 'section' => __('Menu', 'wpappninja')),

					array('help' => __('Bottom menu icon color', 'wpappninja'), 'class' => 'html.ios body.wpapp-ios .tabbar a', 'zone' => 'color', 'color' => "#fff", 'section' => __('Menu', 'wpappninja')),

					array('help' => __('Left menu background', 'wpappninja'), 'class' => '.panel.panel-left', 'zone' => 'background', 'color' => '#fff', 'section' => __('Menu', 'wpappninja')),
					array('help' => __('Left menu item background', 'wpappninja'), 'class' => '.panel .list-panel-all', 'zone' => 'background', 'color' => '#fff', 'section' => __('Menu', 'wpappninja')),
					array('help' => __('Left menu icon color', 'wpappninja'), 'class' => '.panel i.icon', 'zone' => 'color', 'color' => $app_theme_accent, 'section' => __('Menu', 'wpappninja')),
					array('help' => __('Left menu text and button color', 'wpappninja'), 'class' => '.panel .item-title', 'zone' => 'color', 'color' => "#333333", 'section' => __('Menu', 'wpappninja')),
					array('help' => __('Left menu icon', 'wpappninja'), 'class' => '.panel .item-media', 'zone' => 'display', 'color' => 'block', 'section' => __('Menu', 'wpappninja')),

					array('help' => __('Floating menu item background', 'wpappninja'), 'class' => 'html.wpappninja body.theme-wpappninja .speed-dial a', 'zone' => 'background', 'color' => $app_theme_accent, 'section' => __('Menu', 'wpappninja')),
					array('help' => __('Floating round menu icon color', 'wpappninja'), 'class' => 'html.wpappninja body.theme-wpappninja .speed-dial a', 'zone' => 'color', 'color' => "#fff", 'section' => __('Menu', 'wpappninja')),


					array('help' => __('Title color', 'wpappninja'), 'class' => 'h1, h2, h3, h4, h5, h6', 'zone' => 'color', 'color' => "#333", 'section' => __('Content', 'wpappninja')),
					array('help' => __('Content text color', 'wpappninja'), 'class' => '.wpapp-post-content', 'zone' => 'color', 'color' => "#333", 'section' => __('Content', 'wpappninja')),



					array('help' => __('Links color', 'wpappninja'), 'class' => 'html.wpappninja body.theme-wpappninja a', 'zone' => 'color', 'color' => $app_theme_accent, 'section' => __('Content', 'wpappninja')),
					//array('help' => __('Button border color', 'wpappninja'), 'class' => '.button,.woocommerce input.button.alt,.woocommerce a.button', 'zone' => 'border-color', 'color' => $app_theme_accent, 'section' => __('Content', 'wpappninja')),
					array('help' => __('Button text - border - icon color', 'wpappninja'), 'class' => '.button,.woocommerce input.button.alt,.woocommerce a.button', 'zone' => 'color', 'color' => $app_theme_accent, 'section' => __('Content', 'wpappninja')),

					array('help' => __('Badge background', 'wpappninja'), 'class' => '.chip, .badge', 'zone' => 'background-color', 'color' => $app_theme_accent, 'section' => __('Content', 'wpappninja')),

					array('help' => __('List items background', 'wpappninja'), 'class' => '.posts .card.wpappninja-card-header-pic', 'zone' => 'background-color', 'color' => "#ffffff", 'section' => __('List', 'wpappninja')),


					array('help' => __('Loading bar color', 'wpappninja'), 'class' => '.progressbar-infinite:after,.progressbar-infinite:before', 'zone' => 'background', 'color' => $app_theme_accent, 'section' => __('Extra', 'wpappninja')),

					array('help' => __('Search bar background', 'wpappninja'), 'class' => '.searchbar', 'zone' => 'background', 'color' => $app_theme_primary, 'section' => __('Extra', 'wpappninja')),

					array('help' => __('Base text size', 'wpappninja'), 'class' => 'body', 'zone' => 'font-size', 'color' => '16px', 'section' => __('Extra', 'wpappninja')),

					array('help' => __('Font family', 'wpappninja'), 'class' => 'body', 'zone' => 'font-family', 'color' => '-apple-system,SF UI Text,Helvetica Neue,Helvetica,Arial,sans-serif', 'section' => __('Extra', 'wpappninja')),




					//array('help' => __('Excerpt in lists', 'wpappninja'), 'class' => '.wpappninja-card-header-pic .wpapp-post-content', 'zone' => 'display', 'color' => 'block'),



					/*array('help' => __('Author name in page', 'wpappninja'), 'class' => '.main-post .card-header', 'zone' => 'display', 'color' => $show_avatar),
					array('help' => __('Image in page', 'wpappninja'), 'class' => '.main-post img.hero', 'zone' => 'display', 'color' => $show_img_post),
					array('help' => __('Title in page', 'wpappninja'), 'class' => '.main-post h2', 'zone' => 'display', 'color' => $show_title),
					array('help' => __('Comments box', 'wpappninja'), 'class' => '.comments-area', 'zone' => 'display', 'color' => $show_commentaire),*/



					/*array('help' => __('Author name in lists', 'wpappninja'), 'class' => '.wpappninja-card-header-pic .card-header', 'zone' => 'display', 'color' => $show_avatar),
					array('help' => __('Image in lists', 'wpappninja'), 'class' => '.wpappninja-card-header-pic img.wpapp-main-img', 'zone' => 'display', 'color' => $show_img_list),
					array('help' => __('Title in lists', 'wpappninja'), 'class' => '.wpappninja-card-header-pic h2', 'zone' => 'display', 'color' => 'block'),
					
					array('help' => __('Read more link in lists', 'wpappninja'), 'class' => '.wpappninja-card-header-pic .card-footer', 'zone' => 'display', 'color' => 'flex'),


					array('help' => __('Search bar in menu', 'wpappninja'), 'class' => '.panel .searchbar', 'zone' => 'display', 'color' => $show_search),*/

					
					//array('help' => __('System UI (navigation bar, menu, ...)', 'wpappninja'), 'class' => '.pull-to-refresh-layer, .statusbar-overlay, .navbar, .tabbar', 'zone' => 'display', 'color' => "default", 'section' => __('Extra', 'wpappninja')),

					
					

				);

	return $classes;

}

/**
 * Get primary color based on the logo.
 *
 * @since 7.2.3
 */
function wpappninja_get_dominant($url) {

	$img = wp_remote_get($url);
	$image = imagecreatefromstring($img['body']);

    $thumb = imagecreatetruecolor(1,1);
    imagecopyresampled($thumb,$image,0,0,0,0,1,1,imagesx($image),imagesy($image));
    $mainColor=strtoupper(dechex(imagecolorat($thumb,0,0)));

	$hex = wpappninja_check_hex("#" . $mainColor);

	return $hex;
}

/**
 * Get accent color based on the primary color.
 *
 * @since 7.2.3
 */
function wpappninja_get_accent($hex) {

	list($_r, $_g, $_b) = sscanf($hex, "#%02x%02x%02x");

	$color = str_replace("#", "", $hex);
	$rgb = "";
    for ($x=0;$x<3;$x++){
        $c = 255 - hexdec(substr($color,(2*$x),2));
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
    }
    return "#" . $rgb;

	$r = max($_r,$_b,$_g) + min($_r,$_b,$_g) - $_r; 
	$g = max($_r,$_b,$_g) + min($_r,$_b,$_g) - $_g;
	$b = max($_r,$_b,$_g) + min($_r,$_b,$_g) - $_b;

	$hex = wpappninja_check_hex(sprintf("#%02x%02x%02x", $r, $g, $b), true);

	return $hex;
}

/**
 * Check hex color.
 *
 * @since 7.2.5
 */
function wpappninja_check_hex($hex, $accent = false) {

	if (!preg_match('/#([a-f0-9]{3}){2}\b/i', $hex)) {

		if ($accent) {
			$hex = "#0f73ad";
		} else {
			$hex = "#0a0247";
		}
	}

	return $hex;
}

/**
 * Check if the text on status bar need to be white.
 *
 * @since 8.0
 */
function wpappninja_need_light_status($hex) {

	$hex = str_replace('#', '', $hex);

    $r = hexdec($hex[0] . $hex[1]);
    $g = hexdec($hex[2] . $hex[3]);
    $b = hexdec($hex[4] . $hex[5]);

    $RGB = $b + ($g << 0x8) + ($r << 0x10);

    $r = 0xFF & ($RGB >> 0x10);
    $g = 0xFF & ($RGB >> 0x8);
    $b = 0xFF & $RGB;

    $r = ((float)$r) / 255.0;
    $g = ((float)$g) / 255.0;
    $b = ((float)$b) / 255.0;

    $maxC = max($r, $g, $b);
    $minC = min($r, $g, $b);

    $l = ($maxC + $minC) / 2.0;

    if($maxC == $minC) {
      $s = 0;
      $h = 0;
    } else {
      if($l < .5)
      {
        $s = ($maxC - $minC) / ($maxC + $minC);
      }
      else
      {
        $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
      }
      if($r == $maxC)
        $h = ($g - $b) / ($maxC - $minC);
      if($g == $maxC)
        $h = 2.0 + ($b - $r) / ($maxC - $minC);
      if($b == $maxC)
        $h = 4.0 + ($r - $g) / ($maxC - $minC);

      $h = $h / 6.0; 
    }

    $h = (int)round(255.0 * $h);
    $s = (int)round(255.0 * $s);
    $l = (int)round(255.0 * $l);

    if ($l > 200) {
    	return true;
    }

    return false;
}

/**
 * Verify color format.
 *
 * @since 8.2.1
 */
function wpappninja_verify_color($hex, $default = '#dedede') {

	$hex = strtolower($hex);
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    if (!preg_match('/^([a-f0-9]{6})$/', $hex)) {
    	return $default;
    }

    return "#" . $hex;
}
