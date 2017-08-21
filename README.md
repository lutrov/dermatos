# Dermatos

A clean and simple admin theme which requires no configuration. Customisation is possible via the Wordpress hooks API. Why this plugin name? Dermatos means "skin" in Greek.

## Professional Support

If you need professional plugin support from me, the plugin author, contact me via my website at http://lutrov.com

## Copyright and License

This project is licensed under the [GNU GPL](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html), version 2 or later.

## Documentation

This plugin provides an API to to customise the default constant values. See these examples:

	// ---- Change the Dermatos plugin show login errors value to false.
	add_filter('dermatos_show_login_errors_filter', '__return_false');

	// ---- Change the Dermatos plugin login redirect non admins value to false.
	add_filter('dermatos_login_redirect_non_admins_filter', '__return_false');

	// ---- Change the Dermatos plugin remove WP adminbar quicklinks value to false.
	add_filter('dermatos_remove_wordpress_adminbar_quicklinks_filter', '__return_false');

	// ---- Change the Dermatos plugin replace howdy greeting value to false.
	add_filter('dermatos_replace_admin_howdy_greeting_filter', '__return_false');

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

Or if you're using a custom site plugin (you should be), do it via the `plugins_loaded` hook instead:

	// ---- Change the Dermatos plugin constant values.
	add_action('plugins_loaded', 'lutrov_custom_dermatos_filters');
	function lutrov_custom_dermatos_filters() {
		// Change the show login errors value to false.
		add_filter('dermatos_show_login_errors_filter', '__return_false');
		// Change the login redirect non admins value to false.
		add_filter('dermatos_login_redirect_non_admins_filter', '__return_false');
		// Change the remove WP adminbar quicklinks value to false.
		add_filter('dermatos_remove_wordpress_adminbar_quicklinks_filter', '__return_false');
		// Change the freplace howdy greeting value to false.
		add_filter('dermatos_replace_admin_howdy_greeting_filter', '__return_false');
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
	}


Note, this second approach will _not_ work from your theme's `functions.php` file.
