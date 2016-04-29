<?php

/*
Plugin Name: Dermatos
Description: A user friendly admin backend &amp; login skin. It's even pretty responsive, as far as CSS can manage with the core. Please note that this plugin completely ignores the admin color schemes. Why this plugin name? Dermatos means "skin" in Greek.
Author: Ivan Lutrov.
Author URI: http://lutrov.com/
Version: 2.11
*/

defined('ABSPATH') || die('Ahem.');

//
// Constants used by this plugin.
//
define('DERMATOS_EDITORS_CAN_EDIT_THEME_OPTIONS', true);
define('DERMATOS_KEEP_LOST_PASSWORD_LINK', true);
define('DERMATOS_KEEP_QUIET_ABOUT_LOGIN_ERRORS', true);
define('DERMATOS_LOGIN_REDIRECT_NON_ADMINS', false);
define('DERMATOS_REMOVE_WORDPRESS_ADMINBAR_QUICKLINKS', true);
define('DERMATOS_REORDER_ADMIN_MENU', true);
define('DERMATOS_REPLACE_ADMIN_HOWDY_GREETING', true);
define('DERMATOS_THEME_CAN_OVERRIDE_ADMIN_STYLES', true);

//
// Don't touch these unless you want the sky to fall.
//
define('DERMATOS_BASE_PLUGIN_URL', trim(plugin_dir_url(__FILE__), '/'));
define('DERMATOS_BASE_PLUGIN_PATH', dirname(__FILE__));

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
// Custom admin menu order.
//
if (DERMATOS_REORDER_ADMIN_MENU) {
	add_filter('custom_menu_order', '__return_true');
	add_filter('menu_order', 'dermatos_custom_admin_menu_order', 999);
	function dermatos_custom_admin_menu_order($order) {
		$todo = array();
		foreach ($order as $item) {
			switch ($item) {
				case 'edit-comments.php':
				case 'edit.php':
				case 'edit.php?post_type=page':
				case 'index.php':
				case 'options-general.php':
				case 'plugins.php':
				case 'separator':
				case 'separator-last':
				case 'separator1':
				case 'separator2':
				case 'separator3':
				case 'themes.php':
				case 'tools.php':
				case 'upload.php':
				case 'users.php':
					break;
				default:
					$todo[$item] = 1;
			}
		}
	        $result = array();
		array_push($result, 'index.php'); // Dashboard
		array_push($result, 'separator1'); // First separator
		array_push($result, 'edit.php?post_type=page'); // Pages
		array_push($result, 'edit.php'); // Posts
		array_push($result, 'edit-comments.php'); // Comments
		array_push($result, 'upload.php'); // Media
		if (isset($todo['revslider'])) {
			array_push($result, 'revslider'); // Revolution Slider
			unset($todo['revslider']);
		}
		if (isset($todo['edit.php?post_type=gp_slide'])) {
			array_push($result, 'edit.php?post_type=gp_slide'); // Donkey Slider
			unset($todo['edit.php?post_type=gp_slide']);
		}
		array_push($result, 'separator2'); // Second separator
		if (isset($todo['woocommerce'])) {
			array_push($result, 'woocommerce'); // Woocommerce
			unset($todo['woocommerce']);
			if (isset($todo['edit.php?post_type=product'])) {
				array_push($result, 'edit.php?post_type=product');
				unset($todo['edit.php?post_type=product']);
			}
		}
		$types = get_post_types();
		foreach ($types as $type) {
			switch ($type) {
				case 'post':
				case 'page':
				case 'attachment':
				case 'revision':
				case 'nav_menu_item':
					break;
				default:
					array_push($result, 'edit.php?post_type=' . $type);
					break;
			}
		}
		array_push($result, 'separator3'); // Third separator
		if (isset($todo['genesis'])) {
			array_push($result, 'genesis'); // Genesis
			unset($todo['genesis']);
		}
		array_push($result, 'themes.php'); // Appearance
		array_push($result, 'plugins.php'); // Plugins
		if (isset($todo['edit.php?post_type=acf-field-group'])) {
			array_push($result, 'edit.php?post_type=acf-field-group'); // Advanced Custom Fields
			array_push($result, 'admin.php?page=acf-options'); // Advanced Custom Fields Options Page
			unset($todo['edit.php?post_type=acf-field-group']);
		}
		if (isset($todo['ninja-forms'])) {
			array_push($result, 'ninja-forms'); // Ninja Forms
			unset($todo['ninja-forms']);
		}
		if (isset($todo['gf_edit_forms'])) {
			array_push($result, 'gf_edit_forms'); // Gravity Forms
			unset($todo['gf_edit_forms']);
		}
		if (isset($todo['formidable'])) {
			array_push($result, 'formidable'); // Formidable
			unset($todo['formidable']);
		}
		if (isset($todo['wpcf7'])) {
			array_push($result, 'wpcf7'); // Contact Form 7
			unset($todo['wpcf7']);
		}
		if (isset($todo['wpseo_dashboard'])) {
			array_push($result, 'wpseo_dashboard'); // Yoast SEO
			unset($todo['wpseo_dashboard']);
		}
		if (isset($todo['yst_ga_dashboard'])) {
			array_push($result, 'yst_ga_dashboard'); // Yoast Analytics
			unset($todo['yst_ga_dashboard']);
		}
		// Any others not catered for
		if (count($todo) > 0) {
			foreach ($todo as $item) {
				array_push($result, $item);
			}
		}
		array_push($result, 'separator-last'); // Last separator
		array_push($result, 'users.php'); // Users
		array_push($result, 'tools.php'); // Tools
		array_push($result, 'options-general.php'); // Settings
		return $result;
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
add_filter('login_head', 'dermatos_login_css');
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
	echo sprintf('<link href="%s/css/login.css" rel="stylesheet" type="text/css" media="all" />', DERMATOS_BASE_PLUGIN_URL);
	echo sprintf('<style type="text/css">%s</style>', $style);
}

//
// Filter login errors.
//
if (DERMATOS_KEEP_QUIET_ABOUT_LOGIN_ERRORS) {
	add_filter('login_errors', create_function('$a', 'return null;'));
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
// Redirect non admins to homepage instead of the dashboard.
//
if (DERMATOS_LOGIN_REDIRECT_NON_ADMINS) {
	add_filter('login_redirect', 'dermatos_login_redirect_non_admins', 10, 3);
	function dermatos_login_redirect_non_admins($redirect_to, $request, $user) {
		global $user;
		if (isset($user->roles) && is_array($user->roles)) {
			if (in_array('administrator', $user->roles)) {
				return $redirect_to;
			} else {
				return home_url();
			}
		} else {
			return $redirect_to;
		}
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
		echo sprintf('<link href="%s/images/icon.png" rel="icon" type="image/png" />', DERMATOS_BASE_PLUGIN_URL);
	}
}

//
// Custom admin stylesheet.
//
add_action('admin_head', 'dermatos_admin_css', 99);
function dermatos_admin_css() {
	echo sprintf('<link href="%s/css/admin.css" rel="stylesheet" type="text/css" />', DERMATOS_BASE_PLUGIN_URL);
}

//
// Custom public stylesheet.
//
add_action('wp_head', 'dermatos_public_css', 99);
function dermatos_public_css() {
	echo sprintf('<link href="%s/css/public.css" rel="stylesheet" type="text/css" />', DERMATOS_BASE_PLUGIN_URL);
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

//
// Allow editors to edit theme options.
//
if (DERMATOS_EDITORS_CAN_EDIT_THEME_OPTIONS) {
	get_role('editor')->add_cap('edit_theme_options');
}

?>