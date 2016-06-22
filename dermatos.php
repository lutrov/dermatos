<?php

/*
Plugin Name: Dermatos
Description: A user friendly admin backend &amp; login skin. It's even pretty responsive, as far as CSS can manage with the core. Please note that this plugin completely ignores the admin color schemes. Why this plugin name? Dermatos means "skin" in Greek.
Author: Ivan Lutrov.
Author URI: http://lutrov.com/
Version: 3.00
*/

defined('ABSPATH') || die('Ahem.');

//
// Constants used by this plugin.
//
define('DERMATOS_KEEP_LOST_PASSWORD_LINK', true);
define('DERMATOS_KEEP_QUIET_ABOUT_LOGIN_ERRORS', true);
define('DERMATOS_LOGIN_REDIRECT_NON_ADMINS', true);
define('DERMATOS_REMOVE_WORDPRESS_ADMINBAR_QUICKLINKS', true);
define('DERMATOS_REPLACE_ADMIN_HOWDY_GREETING', true);
define('DERMATOS_THEME_CAN_OVERRIDE_ADMIN_STYLES', true);

//
// Don't touch these unless you want the sky to fall.
//
define('DERMATOS_BASE_PLUGIN_URL', trim(plugin_dir_url(__FILE__), '/'));
define('DERMATOS_BASE_PLUGIN_PATH', dirname(__FILE__));

//
// Filter login errors.
//
if (DERMATOS_KEEP_QUIET_ABOUT_LOGIN_ERRORS) {
	add_filter('login_errors', create_function('$a', 'return null;'));
}

//
// Redirect non admins to homepage instead of the dashboard.
//
if (DERMATOS_LOGIN_REDIRECT_NON_ADMINS) {
	add_filter('login_redirect', 'dermatos_login_redirect_non_admins', 10, 3);
	function dermatos_login_redirect_non_admins($location, $request, $user) {
		global $user;
		if (isset($user->roles) && is_array($user->roles)) {
			if (!in_array('administrator', $user->roles)) {
				$location = home_url('/');
			}
		}
		return $location;
	}
}

//
// Remove WP submenu from the adminbar, add custom admin logo instead
// and remove "visit site" submenu under sitename.
//
if (DERMATOS_REMOVE_WORDPRESS_ADMINBAR_QUICKLINKS) {
	add_filter('wp_before_admin_bar_render', 'dermatos_remove_wordpress_adminbar_quicklinks');
	function dermatos_remove_wordpress_adminbar_quicklinks() {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu(
			array('id' => 'wp-logo', 'title' => null, 'href' => null, 'meta' => array('title' => null))
		);
		$wp_admin_bar->remove_menu('header');
		$wp_admin_bar->remove_menu('about');
		$wp_admin_bar->remove_menu('wporg');
		$wp_admin_bar->remove_menu('documentation');
		$wp_admin_bar->remove_menu('support-forums');
		$wp_admin_bar->remove_menu('feedback');
	}
}

//
// Replace the howdy greeting.
//
if (DERMATOS_REPLACE_ADMIN_HOWDY_GREETING) {
	add_filter('gettext', 'dermatos_replace_howdy', 10, 3);
	function dermatos_replace_howdy($translated, $text, $domain) {
		if (is_admin() && $domain == 'default') {
			if (strpos($translated, 'Howdy') !== false) {
				$translated = str_replace('Howdy', "G'day", $translated);
			}
		}
		return $translated;
	}
}

//
// Filter login form URL to point to homepage.
//
add_filter('login_headerurl', 'dermatos_login_url');
function dermatos_login_url() {
	return home_url('/');
}

//
// Filter login form title.
//
add_filter('login_headertitle', 'dermatos_login_title');
function dermatos_login_title() {
	return get_bloginfo('sitename');
}

//
// Change login form text elements.
//
add_filter('gettext', 'dermatos_change_loginform_text');
function dermatos_change_loginform_text($text) {
	$temp = strtoupper(trim(strip_tags($text), '.?:'));
	switch (true) {
		case ($temp == 'LOST YOUR PASSWORD'):
			$text = DERMATOS_KEEP_LOST_PASSWORD_LINK == true ? __('Lost password?') : null;
			break;
		case ($temp == 'A PASSWORD WILL BE E-MAILED TO YOU'):
			$text = null;
			break;
		case ($temp == 'YOU ARE NOW LOGGED OUT'):
			$text = __('You have successfully logged out.');
			break;
		case ($temp == 'REGISTER FOR THIS SITE'):
			$text = __('Once your registration is approved, a password will be emailed to you.');
			break;
		case ($temp == 'E-MAIL'):
			$text = __('Email');
			break;
		case ($temp == 'USERNAME OR EMAIL'):
			$text = __('Username');
			break;
		case ($temp == 'REMEMBER ME'):
			$text = __('Remember me on this device.');
			break;
		case (substr($temp, 0, 5) == 'ERROR'):
			$text = __('Authentication failed.');
			break;
	}
	return $text;
}

//
// Add login CSS.
//
add_filter('login_head', 'dermatos_login_css', 999);
function dermatos_login_css() {
	$path = DERMATOS_BASE_PLUGIN_PATH . '/css/images/logo.png';
	if (file_exists($path)) {
		$width = 0;
		$height = 0;
		$size = getimagesize($path);
		if (count($size) > 2) {
			$width = $size[0];
			$height = $size[1];
			if ($width > 480) {
				$size = ' background-size: ' . intval($width * 0.75) . 'px ' . intval($height * 0.75) . 'px';
			} else {
				$size = ' background-size: ' . $width . 'px ' . $height . 'px';
			}
		} else {
			$size = null;
		}
		$style = sprintf('#login h1 a {width: 100%% !important; height: %spx !important; margin: 0 auto !important; background-image: url(%s/css/images/logo.png); background-repeat: no-repeat; background-position: center; %s } ', $height, DERMATOS_BASE_PLUGIN_URL, $size);
	} else {
		$style = ' background-image: none ';
	}
	echo sprintf('<link href="%s/css/style.php?file=login" rel="stylesheet" type="text/css" media="all">', DERMATOS_BASE_PLUGIN_URL);
	echo sprintf('<style type="text/css">%s</style>', $style);
}

//
// Set login "remember me" to checked.
//
add_action('init', 'dermatos_login_rememberme_checked');
function dermatos_login_rememberme_checked() {
	add_filter('login_footer', 'dermatos_login_rememberme_checked_js');
	function dermatos_login_rememberme_checked_js() {
		echo "<script>document.getElementById('rememberme').checked=true;</script>";
	}
}

//
// Custom admin and login favicon.
//
add_filter('admin_head', 'dermatos_meta_favicon');
add_filter('login_head', 'dermatos_meta_favicon');
function dermatos_meta_favicon() {
	$path = sprintf('%s/images/icon.png', DERMATOS_BASE_PLUGIN_PATH);
	if (file_exists($path)) {
		echo sprintf('<link href="%s/images/icon.png" rel="icon" type="image/png">', DERMATOS_BASE_PLUGIN_URL);
	}
}

//
// Custom admin stylesheet.
//
add_action('admin_head', 'dermatos_admin_css', 999);
function dermatos_admin_css() {
	echo sprintf('<link href="%s/css/style.php?file=admin" rel="stylesheet" type="text/css">', DERMATOS_BASE_PLUGIN_URL);
}

//
// Custom public stylesheet.
//
add_action('wp_head', 'dermatos_public_css', 999);
function dermatos_public_css() {
	echo sprintf('<link href="%s/css/style.php?file=public" rel="stylesheet" type="text/css">', DERMATOS_BASE_PLUGIN_URL);
}

//
// Disable colour scheme selector for users.
//
add_action('admin_head', 'dermatos_disable_admin_color_schemes');
function dermatos_disable_admin_color_schemes() {
	global $_wp_admin_css_colors;
	$_wp_admin_css_colors = 0;
}

//
// Force default solour scheme.
//
add_filter('get_user_option_admin_color', 'dermatos_change_admin_color');
function dermatos_change_admin_color($result) {
	return 'fresh';
}

?>