# Dermatos

A clean and simple admin theme which requires no configuration and allows basic branding. This plugin will only override the Wordpress styles on devices wider than 960 pixels. Customisation is possible via the Wordpress hooks API. Why this plugin name? Dermatos means "skin" in Greek.

## Plugins

Dermatos supports a wide range of populra Wordpress plugins, including:

* Admin Menu Editor
* Advanced Custom Fields
* Code Snippets
* Custom Permalinks
* Display Widgets
* Easy Social Sharing
* Email Plugin
* Formidable Forms
* Genesis
* Genesis Responsive Slider
* Genesis Simple Share
* Groups
* Generatepress
* Lifter LMS
* Memberpress
* Members
* Nextgen Gallery
* P3 Plugin Profiler
* Paid Memberships Pro
* Replace Media
* Restrict Content Pro
* Revolution Slider
* Sensei
* Simple History
* Simple Social Icons
* Sucuri
* Widget Context Plugin
* Woocommerce
* WP All Export
* WP Crontrol
* WP Document Revisions
* WP Migrate DB
* WP Rocket
* WP Updates Notifier
* Yoast SEO

## Professional Support

If you need professional plugin support from me, the plugin author, contact me via my website at http://lutrov.com

## Copyright and License

This project is licensed under the [GNU GPL](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html), version 2 or later.

## Documentation

__Please note that the default Wordpress admin styles will only get overriden on devices which are 960 pixels wide, or more.__

This plugin provides an API to to customise the default constant values. See these examples:

	// ---- Change the Dermatos plugin show login errors value to false.
	add_filter('dermatos_login_show_errors_filter', '__return_false');

	// ---- Change the Dermatos plugin allow logins via username value to false.
	add_filter('dermatos_login_allow_username_filter', '__return_false');

	// ---- Change the Dermatos plugin login lost password link value to false.
	add_filter('dermatos_login_hide_lost_password_link_filter', '__return_false');

	// ---- Change the Dermatos plugin login redirect non admins value to false.
	add_filter('dermatos_login_redirect_non_admins_filter', '__return_false');

	// ---- Change the Dermatos plugin remove WP adminbar quicklinks value to false.
	add_filter('dermatos_remove_adminbar_wordpress_quicklinks_filter', '__return_false');

	// ---- Change the Dermatos plugin change WP styles value to false.
	add_filter('dermatos_admin_change_wordpress_styles_filter', '__return_false');

	// ---- Change the Dermatos plugin lower WP heartbeat value to false.
	add_filter('dermatos_lower_heartbeat_interval_filter', '__return_false');

	// ---- Change the Dermatos plugin admin replace strings value to false.
	add_filter('dermatos_admin_replace_strings_filter', '__return_false');

	// ---- Change the Dermatos plugin login background path.
	add_filter('dermatos_login_background_path_filter', 'lutrov_dermatos_login_background_path_filter');
	function lutrov_dermatos_login_background_path_filter($path) {
		$path = sprintf('%s/wp-content/uploads/2017/04/login-background.jpg', ABSPATH);
		return $path;
	}

	// ---- Change the Dermatos plugin login logo path.
	add_filter('dermatos_login_logo_path_filter', 'lutrov_dermatos_login_logo_path_filter');
	function lutrov_dermatos_login_logo_path_filter($path) {
		$path = sprintf('%s/wp-content/uploads/2017/04/login-logo.png', ABSPATH);
		return $path;
	}

	// ---- Change the Dermatos plugin meta favicon path.
	add_filter('dermatos_meta_favicon_path_filter', 'lutrov_dermatos_meta_favicon_path_filter');
	function lutrov_dermatos_meta_favicon_path_filter($path) {
		$path = sprintf('%s/wp-content/uploads/2017/04/meta-favicon.png', ABSPATH);
		return $path;
	}

	// ---- Change the Dermatos plugin highlight primary colour.
	add_filter('dermatos_colour_highlight_primary_filter', 'lutrov_dermatos_colour_highlight_primary_filter')
	function lutrov_dermatos_colour_highlight_primary_filter($value) {
		return 'green';
	}

	// ---- Change the Dermatos plugin highlight accent colour.
	add_filter('dermatos_colour_highlight_accent_filter', 'lutrov_dermatos_colour_highlight_accent_filter')
	function lutrov_dermatos_colour_highlight_accent_filter($value) {
		return 'blue';
	}

	// ---- Change the Dermatos plugin delete action colour.
	add_filter('dermatos_colour_delete_action_filter', 'lutrov_dermatos_colour_delete_action_filter')
	function lutrov_dermatos_colour_delete_action_filter($value) {
		return 'red';
	}

	// ---- Change the Dermatos plugin font family.
	add_filter('dermatos_font_family_filter', 'lutrov_dermatos_font_family_filter');
	function lutrov_dermatos_font_family_filter($value) {
		return 'Open Sans';
	}

	// ---- Change the Dermatos plugin escaped font family.
	add_filter('dermatos_font_family_escaped_filter', 'lutrov_dermatos_font_family_escaped_filter')
	function lutrov_dermatos_font_family_escaped_filter($value) {
		return 'Open+Sans';
	}

	// ---- Change the Dermatos plugin normal font weight.	
	add_filter('dermatos_font_weight_normal_filter', 'lutrov_dermatos_font_weight_normal_filter')
	function lutrov_dermatos_font_weight_normal_filter($value) {
		return '400';
	}

	// ---- Change the Dermatos plugin bold font weight.	
	add_filter('dermatos_font_weight_bold_filter', 'lutrov_dermatos_font_weight_bold_filter')
	function lutrov_dermatos_font_weight_bold_filter($value) {
		return '800';
	}

	// ---- Change the Dermatos plugin extra bold font weight.	
	add_filter('dermatos_font_weight_extra_bold_filter', 'lutrov_dermatos_font_weight_extra_bold_filter')
	function lutrov_dermatos_font_weight_extra_bold_filter($value) {
		return '900';
	}

	// ---- Change the Dermatos plugin cache expiry seconds.
	add_filter('dermatos_cache_expiry_seconds_filter', 'lutrov_dermatos_cache_expiry_seconds_filter')
	function lutrov_dermatos_cache_expiry_seconds_filter($value) {
		return '86400';
	}

	// ---- Change the Dermatos plugin replacement strings array.
	add_filter('dermatos_admin_replacement_strings_array_filter', 'lutrov_dermatos_admin_replacement_strings_array_filter');
	function lutrov_dermatos_admin_replacement_strings_array_filter($array) {
		return array(
			'WooCommerce' => 'Woocommerce',
			'WordPress' => 'Wordpress',
			'Howdy' => 'Hello',
			'AdWords' => 'Adwords'
		);
	}

Or if you're using a custom site plugin (as you should be), do it via the `plugins_loaded` hook instead:

	// ---- Change the Dermatos plugin constant values.
	add_action('plugins_loaded', 'lutrov_custom_dermatos_filters');
	function lutrov_custom_dermatos_filters() {
		// ---- Change the show login errors value to false.
		add_filter('dermatos_login_show_errors_filter', '__return_false');
		// ---- Change the Dermatos plugin allow logins via username value to false.
		add_filter('dermatos_login_allow_username_filter', '__return_false');
		// ---- Change the Dermatos plugin login lost password link value to false.
		add_filter('dermatos_login_hide_lost_password_link_filter', '__return_false');
		// ---- Change the Dermatos plugin login redirect non admins value to false.
		add_filter('dermatos_login_redirect_non_admins_filter', '__return_false');
		// ---- Change the Dermatos plugin remove WP adminbar quicklinks value to false.
		add_filter('dermatos_remove_adminbar_wordpress_quicklinks_filter', '__return_false');
		// ---- Change the Dermatos plugin change WP styles value to false.
		add_filter('dermatos_admin_change_wordpress_styles_filter', '__return_false');
		// ---- Change the Dermatos plugin lower WP heartbeat value to false.
		add_filter('dermatos_lower_heartbeat_interval_filter', '__return_false');
		// ---- Change the Dermatos plugin admin replace strings value to false.
		add_filter('dermatos_admin_replace_strings_filter', '__return_false');
		// ---- Change the Dermatos plugin login background path.
		add_filter('dermatos_login_background_path_filter', 'lutrov_dermatos_login_background_path_filter');
		function lutrov_dermatos_login_background_path_filter($path) {
			$path = sprintf('%s/wp-content/uploads/2017/04/login-background.jpg', ABSPATH);
			return $path;
		}
		// ---- Change the Dermatos plugin login logo path.
		add_filter('dermatos_login_logo_path_filter', 'lutrov_dermatos_login_logo_path_filter');
		function lutrov_dermatos_login_logo_path_filter($path) {
			$path = sprintf('%s/wp-content/uploads/2017/04/login-logo.png', ABSPATH);
			return $path;
		}
		// ---- Change the Dermatos plugin meta favicon path.
		add_filter('dermatos_meta_favicon_path_filter', 'lutrov_dermatos_meta_favicon_path_filter');
		function lutrov_dermatos_meta_favicon_path_filter($path) {
			$path = sprintf('%s/wp-content/uploads/2017/04/meta-favicon.png', ABSPATH);
			return $path;
		}
		// ---- Change the Dermatos plugin highlight primary colour.
		add_filter('dermatos_colour_highlight_primary_filter', 'lutrov_dermatos_colour_highlight_primary_filter');
		function lutrov_dermatos_colour_highlight_primary_filter($value) {
			return '#44ff44';
		}
		// ---- Change the Dermatos plugin highlight accent colour.
		add_filter('dermatos_colour_highlight_accent_filter', 'lutrov_dermatos_colour_highlight_accent_filter');
		function lutrov_dermatos_colour_highlight_accent_filter($value) {
			return '#4444ff';
		}
		// ---- Change the Dermatos plugin delete action colour.
		add_filter('dermatos_colour_delete_action_filter', 'lutrov_dermatos_colour_delete_action_filter')
		function lutrov_dermatos_colour_delete_action_filter($value) {
			return 'red';
		}
		// ---- Change the Dermatos plugin font family.
		add_filter('dermatos_font_family_filter', 'lutrov_dermatos_font_family_filter');
		function lutrov_dermatos_font_family_filter($value) {
			return 'Open Sans';
		}
		// ---- Change the Dermatos plugin escaped font family.
		add_filter('dermatos_font_family_escaped_filter', 'lutrov_dermatos_font_family_escaped_filter');
		function lutrov_dermatos_font_family_escaped_filter($value) {
			return 'Open+Sans';
		}
		// ---- Change the Dermatos plugin normal font weight.	
		add_filter('dermatos_font_weight_normal_filter', 'lutrov_dermatos_font_weight_normal_filter');
		function lutrov_dermatos_font_weight_normal_filter($value) {
			return '400';
		}
		// ---- Change the Dermatos plugin bold font weight.	
		add_filter('dermatos_font_weight_bold_filter', 'lutrov_dermatos_font_weight_bold_filter');
		function lutrov_dermatos_font_weight_bold_filter($value) {
			return '800';
		}
		// ---- Change the Dermatos plugin extra bold font weight.	
		add_filter('dermatos_font_weight_extra_bold_filter', 'lutrov_dermatos_font_weight_extra_bold_filter');
		function lutrov_dermatos_font_weight_extra_bold_filter($value) {
			return '900';
		}
		// ---- Change the Dermatos plugin cache expiry seconds.
		add_filter('dermatos_cache_expiry_seconds_filter', 'lutrov_dermatos_cache_expiry_seconds_filter');
		function lutrov_dermatos_cache_expiry_seconds_filter($value) {
			return '86400';
		}

		// ---- Change the Dermatos plugin replacement strings array.
		add_filter('dermatos_admin_replacement_strings_array_filter', 'lutrov_dermatos_admin_replacement_strings_array_filter');
		function lutrov_dermatos_admin_replacement_strings_array_filter($array) {
			return array(
				'WooCommerce' => 'Woocommerce',
				'WordPress' => 'Wordpress',
				'Howdy' => 'Hello',
				'AdWords' => 'Adwords'
			);
		}
	}


Note, this second approach will _not_ work from your theme's `functions.php` file.

Dermatos also supports additional custom styling via a `hacks.css` file. This file will get loaded after `admin.css` gets loaded, so it's a handy way of overriding default Dermatos admin styles.

For instance, you could put the following CSS code into `css/templates/hacks.css', and the admin buttons, form labels & legends, subheadings and tabs would be capitalised:

	#custom-background .form-table th[scope=row] + td .button-secondary,
	#screen-options-wrap label,
	#screen-options-wrap legend,
	#ws_menu_editor .ws_main_button,
	.misc-pub-section #post-status-display,
	.misc-pub-section #post-visibility-display,
	.misc-pub-section #timestamp,
	.wp-core-ui .button-secondary,
	.wp-core-ui .button,
	.wp-core-ui .notification-dialog .button,
	.wrap .add-new-h2,
	.wrap .page-title-action,
	form .form-table th label,
	form .form-table th[scope=row] + td .button-secondary,
	h1,
	h2,
	h3,
	h4,
	th label,
	ul.subsubsub li a {
		text-transform: capitalize !important;
	}

If you need professional plugin support from me, the plugin author, contact me via my website at http://lutrov.com
