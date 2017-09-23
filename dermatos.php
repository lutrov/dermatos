<?php

/*
Plugin Name: Dermatos
Description: A clean and simple admin theme which requires no configuration. Customisation is possible via the Wordpress hooks API. Why this plugin name? Dermatos means "skin" in Greek.
Author: Ivan Lutrov
Author URI: http://lutrov.com/
Version: 9.0
Notes: This plugin provides an API to customise the default constant values. See the "readme.md" file for more.
*/

defined('ABSPATH') || die('Ahem.');

//
// Constants used by this plugin.
//
define('DERMATOS_ADMIN_CHANGE_WORDPRESS_STYLES', true);
define('DERMATOS_ADMIN_REMOVE_DUBYA_DUBYA_DUBYA_FROM_URLS', true);
define('DERMATOS_ADMIN_REMOVE_SCHEME_FROM_URLS', true);
define('DERMATOS_ADMIN_REMOVE_SERVER_NAME_FROM_URLS', true);
define('DERMATOS_ADMIN_REPLACE_STRINGS', true);
define('DERMATOS_LOGIN_ALLOW_USERNAME', true);
define('DERMATOS_LOGIN_HIDE_LOST_PASSWORD_LINK', true);
define('DERMATOS_LOGIN_REDIRECT_NON_ADMINS', true);
define('DERMATOS_LOGIN_SHOW_ERRORS', true);
define('DERMATOS_LOWER_HEARTBEAT_INTERVAL', true);
define('DERMATOS_REMOVE_ADMINBAR_WORDPRESS_QUICKLINKS', true);

//
// Don't touch these unless you want the sky to fall.
//
define('DERMATOS_BASE_PLUGIN_URL', trim(plugin_dir_url(__FILE__), '/'));
define('DERMATOS_BASE_PLUGIN_PATH', dirname(__FILE__));
define('DERMATOS_SITENAME', get_bloginfo('name'));

//
// Default replacement strings.
//
function dermatos_replacement_strings() {
	$strings = array(
		"WordPress" => "Wordpress",
		"WordCamp" => "Wordcamp",
		"WooThemes" => "Woothemes",
		"WooSwipe" => "Wooswipe",
		"WooCommerce" => "Woocommerce",
		"StudioPress" => "Studiopress",
		"SearchWP" => "Search WP",
		"PayPal" => "Paypal",
		"NextGEN" => "Nextgen",
		"MarketPress" => "Marketpress",
		"MailPoet" => "Mailpoet",
		"MailChimp" => "Mailchimp",
		"LifterLMS" => "Lifter",
		"LearnPress" => "Learnpress",
		"LearnDash" => "Learndash",
		"LayerSlider" => "Layerslider",
		"Howdy" => "G'day",
		"GeneratePress" => "Generatepress",
		"eWAY" => "Eway",
		"eNews" => "Enews",
		"eCommerce" => "Ecommerce",
		"eChecks" => "Echecks",
		"eBay" => "Ebay",
		"EasyCart" => "Easycart",
		"E-mail" => "Email",
		"e-mail" => "email",
		"E-commerce" => "Ecommerce",
		"e-commerce" => "ecommerce",
		"CustomPress" => "Custompress",
		"cPanel" => "Cpanel",
		"BuddyPress" => "Buddypress",
		"bbPress" => "Bbpress",
		"BackUpWordPress" => "Backup Wordpress",
		"AdWords" => "Adwords",
	);
	return $strings;
}

//
// Reliably test if a plugin is active.
//
function dermatos_is_plugin_active($plugin) {
	if (is_multisite() == true) {
		$plugins = get_site_option('active_sitewide_plugins');
		if (isset($plugins[$plugin]) == true) {
			return true;
		}
	}
	return (in_array($plugin, get_option('active_plugins')) == true);
}

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
	if (apply_filters('dermatos_login_show_errors_filter', DERMATOS_LOGIN_SHOW_ERRORS) == false) {
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
// Remove WP submenu from the adminbar, add custom admin logo instead and remove "visit site" submenu under sitename.
//
add_filter('wp_before_admin_bar_render', 'dermatos_remove_adminbar_wordpress_quicklinks');
function dermatos_remove_adminbar_wordpress_quicklinks() {
	global $wp_admin_bar;
	if (apply_filters('dermatos_remove_adminbar_wordpress_quicklinks_filter', DERMATOS_REMOVE_ADMINBAR_WORDPRESS_QUICKLINKS) == true) {
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
				if (apply_filters('dermatos_login_hide_lost_password_link_filter', DERMATOS_LOGIN_HIDE_LOST_PASSWORD_LINK) == true) {
					$text = null;
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
			case ($temp == 'ACCOUNT DISABLED'):
				// Cater for Disable Users plugin by Jared Atchison
				$text = _x('Your account has been disabled.', 'Login form');
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
add_action('login_head', 'dermatos_login_css', 8888);
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
	echo sprintf('<link href="%s/css/style.php?file=login" media="screen and (min-width:960px)" rel="stylesheet" type="text/css">', DERMATOS_BASE_PLUGIN_URL);
	echo sprintf('<style media="screen and (min-width:960px)" type="text/css">%s</style>', $style);
}

//
// Maybe allow login via usernames instead of email addresses only.
//
add_action('init', 'dermatos_maybe_allow_logins_via_username_action');
function dermatos_maybe_allow_logins_via_username_action() {
	if (apply_filters('dermatos_login_allow_username_filter', DERMATOS_LOGIN_ALLOW_USERNAME) == false) {
		// Remove the default WP login authentication filter
		remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
		// Add the custom WP login authentication filter
		add_filter('authenticate', 'dermatos_authenticate_username_password_filter', 20, 3);
		function dermatos_authenticate_username_password_filter($user, $username, $password) {
			if (filter_var($username, FILTER_VALIDATE_EMAIL) == $username) {
				$user = get_user_by('email', sanitize_email($username));
				if (isset($user->user_login) == true) {
					$username = $user->user_login;
				}
			} else {
				$username = null;
			}
			$user = wp_authenticate_username_password(null, $username, $password);
			return $user;
		}
	}
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
add_action('admin_head', 'dermatos_meta_favicon');
add_action('login_head', 'dermatos_meta_favicon');
function dermatos_meta_favicon() {
	$path = apply_filters('dermatos_meta_favicon_path_filter', sprintf('%s/images/icon.png', DERMATOS_BASE_PLUGIN_PATH));
	if (file_exists($path) == true) {
		echo sprintf('<link href="%s" rel="icon" type="image/png">', dermatos_url_from_abspath($path));
	}
}

//
// Custom admin stylesheet.
//
add_action('admin_head', 'dermatos_admin_css', 8888);
function dermatos_admin_css() {
	if (apply_filters('dermatos_admin_change_wordpress_styles_filter', DERMATOS_ADMIN_CHANGE_WORDPRESS_STYLES) == true) {
		echo sprintf('<link href="%s/css/style.php?file=admin" media="screen and (min-width:960px)" rel="stylesheet" type="text/css">', DERMATOS_BASE_PLUGIN_URL);
	}
}

//
// Custom public stylesheet.
//
add_action('wp_head', 'dermatos_public_css', 8888);
function dermatos_public_css() {
	echo sprintf('<link href="%s/css/style.php?file=public" media="screen and (min-width:960px)" rel="stylesheet" type="text/css">', DERMATOS_BASE_PLUGIN_URL);
}

//
// Custom admin customizer stylesheet.
//
add_action('customize_controls_enqueue_scripts', 'dermatos_customizer_enqueue');
function dermatos_customizer_enqueue() {
	wp_enqueue_style('dermatos-customizer', sprintf('%s/css/style.php?file=customizer', DERMATOS_BASE_PLUGIN_URL));
}

//
// Change admin screen page titles.
//
add_filter('admin_title', 'dermatos_admin_title_filter', 10, 2);
function dermatos_admin_title_filter($title, $heading) {
	$title = sprintf('%s &ndash; %s', ucwords($heading), DERMATOS_SITENAME);
	return $title;
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

//
// Change the Wordpress Heartbeat interval to the highest possible value.
// http://markomedia.com.au/admin-ajax-php-high-cpu-problem-solved/
//
add_filter('heartbeat_settings', 'dermatos_heartbeat_interval_filter', 16);
function dermatos_heartbeat_interval_filter($settings) {
	if (apply_filters('dermatos_lower_heartbeat_interval_filter', DERMATOS_LOWER_HEARTBEAT_INTERVAL) == true) {
		$settings = array_merge($settings, array('interval' => 60));
	}
	return $settings;
}

//
//  Output buffering functions.
//
function dermatos_buffer_callback($html) {
	$html = dermatos_hack_woocommerce_admin_headers($html);
	$html = dermatos_hack_lifterlms_admin_headers($html);
	if (apply_filters('dermatos_admin_replace_strings_filter', DERMATOS_ADMIN_REPLACE_STRINGS) == true) {
		$html = dermatos_replace_strings($html);
	}
	return trim($html);
}

//
// Replace all global strings.
//
function dermatos_replace_strings($html) {
	$strings = apply_filters('dermatos_admin_replacement_strings_array_filter', dermatos_replacement_strings());
	if (count($strings) > 0) {
		$temp = array();
		$i = 0;
		foreach ($strings as $key => $value) {
			$temp['from'][$i] = sprintf('#\b(%s)\b#', $key);
			$temp['to'][$i] = $value;
			$i++;
		}
		$html = preg_replace($temp['from'], $temp['to'], $html, -1, $count);
		$html = str_replace(array(':</label>', ':</th>'), array('</label>', '</th>'), $html);
	}
	return $html;
}
//
// Replace strings based on configuration settings.
//
function dermatos_replace_config_strings($html) {
	if (apply_filters('dermatos_admin_remove_server_name_from_urls_filter', DERMATOS_ADMIN_REMOVE_SERVER_NAME_FROM_URLS) == true) {
		$scheme = 'http';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$scheme = 'https';
		}
		$html = str_replace($scheme . '://' . $_SERVER['SERVER_NAME'], null, $html);
	}
	if (apply_filters('dermatos_admin_remove_scheme_from_urls_filter', DERMATOS_ADMIN_REMOVE_SCHEME_FROM_URLS) == true) {
		$html = preg_replace('#https?://#', '//', $html);
	}
	if (apply_filters('dermatos_admin_remove_dubya_dubya_dubya_from_urls_filter', DERMATOS_ADMIN_REMOVE_DUBYA_DUBYA_DUBYA_FROM_URLS) == true) {
		$html = str_replace('//www.', '//', $html);
	}
	return trim($html);
}

//
// Woocommerce hack to show correct page titles for reports, settings, status and addons admin pages.
//
function dermatos_hack_woocommerce_admin_headers($html) {
	if (strpos($html, '<div class="wrap woocommerce') > 0) {
		$page = isset($_GET['page']) ? $_GET['page'] : null;
		if ($page == 'wc-reports' || $page == 'wc-settings' || $page == 'wc-status' || $page == 'wc-addons') {
			$title = ucwords(get_admin_page_title());
			if (substr(strtolower($title), 0, 11) <> 'woocommerce') {
				$title = sprintf('WooCommerce %s', $title);
			}
			switch ($page) {
				case 'wc-reports':
				case 'wc-settings':
				case 'wc-status':
					$html = str_replace('<div class="wrap woocommerce">', sprintf('<div class="wrap woocommerce"><h1>%s</h1>', $title), $html);
					break;
				case 'wc-addons':
					$html = str_replace('<div class="wrap woocommerce wc_addons_wrap">', sprintf('<div class="wrap woocommerce wc_addons_wrap"><h1>%s</h1>', $title), $html);
					break;
			}
		}
	}
	return trim($html);
}

//
// Lifter LMS hack to show correct page titles for settings, reporting and status admin pages.
//
function dermatos_hack_lifterlms_admin_headers($html) {
	$page = isset($_GET['page']) ? $_GET['page'] : null;
	if ($page == 'llms-settings' || $page == 'lifterlms' || $page == 'llms-reporting' || $page == 'llms-status') {
		$title = ucwords(get_admin_page_title());
		switch ($page) {
			case 'lifterlms':
				$html = str_replace('<div class="wrap lifterlms lifterlms-settings">', sprintf('<div class="wrap lifterlms lifterlms-settings"><h1>%s</h1>', 'LifterLMS Dashboard'), $html);
				break;
			case 'llms-settings':
				$html = str_replace('<div class="wrap lifterlms lifterlms-settings">', sprintf('<div class="wrap lifterlms lifterlms-settings"><h1>%s</h1>', $title), $html);
				break;
			case 'llms-reporting':
				if (strpos($html, '<div class="wrap lifterlms llms-reporting tab--students') > 0) {
					$html = str_replace('<div class="wrap lifterlms llms-reporting tab--students">', sprintf('<div class="wrap lifterlms llms-reporting tab--students"><h1>%s</h1>', $title), $html);
				} elseif (strpos($html, '<div class="wrap lifterlms llms-reporting tab--sales') > 0) {
					$html = str_replace('<div class="wrap lifterlms llms-reporting tab--sales">', sprintf('<div class="wrap lifterlms llms-reporting tab--students"><h1>%s</h1>', $title), $html);
				} elseif (strpos($html, '<div class="wrap lifterlms llms-reporting tab--enrollments') > 0) {
					$html = str_replace('<div class="wrap lifterlms llms-reporting tab--enrollments">', sprintf('<div class="wrap lifterlms llms-reporting tab--enrollments"><h1>%s</h1>', $title), $html);
				}
				break;
			case 'llms-status':
				if (strpos($html, '<div class="wrap lifterlms llms-status llms-status--report') > 0) {
					$html = str_replace('<div class="wrap lifterlms llms-status llms-status--report">', sprintf('<div class="wrap lifterlms llms-status llms-status--report"><h1>%s</h1>', $title), $html);
				} elseif (strpos($html, '<div class="wrap lifterlms llms-status llms-status--tools') > 0) {
					$html = str_replace('<div class="wrap lifterlms llms-status llms-status--tools">', sprintf('<div class="wrap lifterlms llms-status llms-status--tools"><h1>%s</h1>', $title), $html);
				} elseif (strpos($html, '<div class="wrap lifterlms llms-status llms-status--logs') > 0) {
					$html = str_replace('<div class="wrap lifterlms llms-status llms-status--logs">', sprintf('<div class="wrap lifterlms llms-status llms-status--logs"><h1>%s</h1>', $title), $html);
				}
				break;
		}
	}
	return trim($html);
}

//
// Change Lifter LMS admin screen dashboard page title.
// TODO: This is a hack and should be removed once the plugin author fixes the bug.
//
add_filter('admin_title', 'dermatos_hack_lifterlms_title_filter', 11, 2);
function dermatos_hack_lifterlms_title_filter($title, $heading) {
	if (substr($title, 0, 9) == 'Lifterlms') {
		$title = str_replace('Lifterlms', 'LifterLMS', $title);
	}
	return $title;
}

//
// Start output buffering.
//
add_action('init', 'dermatos_buffer_start_action', 0);
function dermatos_buffer_start_action() {
	if (is_admin() == true) {
		ob_start('dermatos_buffer_callback');
	}
}

//
// Start output buffering.
//
add_action('shutdown', 'dermatos_buffer_stop_action', 8888);
function dermatos_buffer_stop_action() {
	if (is_admin() == true) {
		while (ob_get_level() > 0) {
			ob_end_flush();
		}
	}
}

?>
