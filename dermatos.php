<?php

/*
Plugin Name: Dermatos
Description: A modern looking admin backend &amp; login theme. It's even pretty responsive, as far as CSS can manage with the core. Please note that this plugin completely ignores the admin color schemes. Why this plugin name? Dermatos means "skin" in Greek.
Author: Ivan Lutrov.
Author URI: http://lutrov.com/
Version: 6.3
Notes: This plugin provides an API to customise the default constant values. See the "readme.md" file for more.
*/

defined('ABSPATH') || die('Ahem.');

//
// Constants used by this plugin.
//
define('DERMATOS_SHOW_LOGIN_ERRORS', true);
define('DERMATOS_LOGIN_REDIRECT_NON_ADMINS', true);
define('DERMATOS_REMOVE_WORDPRESS_ADMINBAR_QUICKLINKS', true);
define('DERMATOS_REPLACE_ADMIN_HOWDY_GREETING', true);
define('DERMATOS_THEME_CAN_OVERRIDE_ADMIN_STYLES', true);
define('DERMATOS_KEEP_LOST_PASSWORD_LINK', true);

//
// Don't touch these unless you want the sky to fall.
//
define('DERMATOS_BASE_PLUGIN_URL', trim(plugin_dir_url(__FILE__), '/'));
define('DERMATOS_BASE_PLUGIN_PATH', dirname(__FILE__));

//
// Convert absolute file path to qualified URL.
//
function dermatos_url_from_abspath($path = null) {
	$url = str_replace(wp_normalize_path(untrailingslashit(ABSPATH)), site_url(), wp_normalize_path($path));
	return esc_url_raw($url);
}

//
// Filter login errors.
//
add_filter('login_errors', 'dermatos_login_supress_errors');
function dermatos_login_supress_errors($error) {
	if (apply_filters('dermatos_show_login_errors_filter', DERMATOS_SHOW_LOGIN_ERRORS) == false) {
		$error = null;
	}
	return $error;
}

//
// Remove the login form wobble effect.
//
add_action('login_head', 'dermatos_login_remove_wobble');
function dermatos_login_remove_wobble() {
	remove_action('login_head', 'wp_shake_js', 12);
}

//
// Redirect non admins to homepage instead of the dashboard.
//
add_filter('login_redirect', 'dermatos_login_redirect_non_admins', 10, 3);
function dermatos_login_redirect_non_admins($location, $request, $user) {
	global $user;
	if (apply_filters('dermatos_login_redirect_non_admins_filter', DERMATOS_LOGIN_REDIRECT_NON_ADMINS) == true) {
		if (isset($user->roles) == true && is_array($user->roles) == true) {
			if (in_array('administrator', $user->roles) == false) {
				$location = home_url('/');
			}
		}
	}
	return $location;
}

//
// Remove WP submenu from the adminbar, add custom admin logo instead
// and remove "visit site" submenu under sitename.
//
add_filter('wp_before_admin_bar_render', 'dermatos_remove_wordpress_adminbar_quicklinks');
function dermatos_remove_wordpress_adminbar_quicklinks() {
	global $wp_admin_bar;
	if (apply_filters('dermatos_remove_wordpress_adminbar_quicklinks_filter', DERMATOS_REMOVE_WORDPRESS_ADMINBAR_QUICKLINKS) == true) {
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
add_filter('gettext', 'dermatos_replace_howdy', 10, 3);
function dermatos_replace_howdy($translated, $text, $domain) {
	if (apply_filters('dermatos_replace_admin_howdy_greeting_filter', DERMATOS_REPLACE_ADMIN_HOWDY_GREETING) == true) {
		if (is_admin() == true && $domain == 'default') {
			if (strpos($translated, 'Howdy') <> false) {
				$translated = str_replace('Howdy', "G'day", $translated);
			}
		}
	}
	return $translated;
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
	global $pagenow;
	if ($pagenow == 'wp-login.php') {
		$temp = strtoupper(trim(strip_tags($text), '.?:'));
		switch (true) {
			case ($temp == 'LOST YOUR PASSWORD'):
				$text = null;
				if (apply_filters('dermatos_keep_lost_password_link_filter', DERMATOS_KEEP_LOST_PASSWORD_LINK) == true) {
					$text = sprintf('<span class="reminder">%s</span>', _x('Lost your password?', 'Login form'));
				}
				break;
			case ($temp == 'A PASSWORD WILL BE E-MAILED TO YOU'):
				$text = null;
				break;
			case ($temp == 'YOU ARE NOW LOGGED OUT'):
				$text = _x('You have successfully logged out.', 'Login form');
				break;
				case ($temp == 'REGISTER FOR THIS SITE'):
				$text = _x('Once your registration is approved, a password will be emailed to you.', 'Login form');
				break;
			case ($temp == 'E-MAIL'):
				$text = _x('Email', 'Login form');
				break;
			case ($temp == 'USERNAME OR EMAIL ADDRESS'):
				$text = _x('Username', 'Login form');
				break;
			case ($temp == 'REMEMBER ME'):
				$text = _x('Remember me', 'Login form');
				break;
			case (substr($temp, 0, 5) == 'ERROR'):
				$text = _x('Authentication failed.', 'Login form');
				break;
		}
	}
	return $text;
}

//
// Add login CSS.
//
add_filter('login_head', 'dermatos_login_css', 999);
function dermatos_login_css() {
	$path = apply_filters('dermatos_login_logo_path_filter', sprintf('%s/css/images/logo.png', DERMATOS_BASE_PLUGIN_PATH));
	if (file_exists($path) == true) {
		$w = 0; $h = 0; $s = getimagesize($path);
		if (count($s) > 2) {
			$w = $s[0]; $h = $s[1];
			if ($w > 199) {
				$s = sprintf(' background-size: %spx %spx', intval($w * (200 / $w)), intval($h * (200 / $w)));
			} else {
				$s = sprintf(' background-size: %spx %spx', $w, $h);
			}
		} else {
			$s = null;
		}
		$style = sprintf('#login h1 a {width: 100%% !important; height: %spx !important; margin: 0 auto !important; background-image: url(%s) !important; background-repeat: no-repeat; background-position: center;%s } ', $h, dermatos_url_from_abspath($path), $s);
	} else {
		$style = ' background-image: none ';
	}
	$path = apply_filters('dermatos_login_background_path_filter', null);
	if (strlen($path) > 0) {
		$style = sprintf('html {background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url(%s); background-size: cover; background-position: center top; background-color: #222} %s', dermatos_url_from_abspath($path), $style);
	}
	echo sprintf('<link href="%s/css/style.php?file=login" rel="stylesheet" type="text/css" media="all">', DERMATOS_BASE_PLUGIN_URL);
	echo sprintf('<style type="text/css">%s</style>', $style);
}

//
// Set login "remember me" to checked.
//
add_action('init', 'dermatos_login_rememberme_checked');
function dermatos_login_rememberme_checked() {
	global $pagenow;
	if ($pagenow == 'wp-login.php') {
		add_filter('login_footer', 'dermatos_login_rememberme_checked_js');
		function dermatos_login_rememberme_checked_js() {
			echo "<script>document.getElementById('rememberme').checked=true;</script>";
		}
	}
}

//
// Custom admin and login favicon.
//
add_filter('admin_head', 'dermatos_meta_favicon');
add_filter('login_head', 'dermatos_meta_favicon');
function dermatos_meta_favicon() {
	$path = apply_filters('dermatos_meta_favicon_path_filter', sprintf('%s/images/icon.png', DERMATOS_BASE_PLUGIN_PATH));
	if (file_exists($path) == true) {
		echo sprintf('<link href="%s" rel="icon" type="image/png">', dermatos_url_from_abspath($path));
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
// Custom admin customizer stylesheet.
//
add_action('customize_controls_enqueue_scripts', 'dermatos_customizer_enqueue');
function dermatos_customizer_enqueue() {
	wp_enqueue_style('dermatos-customizer', sprintf('%s/css/style.php?file=customizer', DERMATOS_BASE_PLUGIN_URL));
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
// Force default colour scheme.
//
add_filter('get_user_option_admin_color', 'dermatos_change_admin_color');
function dermatos_change_admin_color($result) {
	return 'fresh';
}

?>
