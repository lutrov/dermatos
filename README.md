# Dermatos

A modern looking admin backend &amp; login theme. It's even pretty responsive, as far as CSS can manage with the core. Please note that this plugin completely ignores the admin color schemes. Why this plugin name? Dermatos means "skin" in Greek.

## Professional Support

If you need professional plugin support from me, the plugin author, contact me via my website at http://lutrov.com

## Copyright and License

This project is licensed under the [GNU GPL](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html), version 2 or later.

## Documentation

This plugin provides an API to to customise the default constant values. See these examples:

	// ---- Change the Dermatos plugin keep lost password link value to false.
	add_filter('dermatos_keep_lost_password_link_filter', '__return_false');

	// ---- Change the Dermatos plugin keep quiet about login errors value to false.
	add_filter('dermatos_keep_quiet_about_login_errors_filter', '__return_false');

	// ---- Change the Dermatos plugin login redirect non admins value to false.
	add_filter('dermatos_login_redirect_non_admins_filter', '__return_false');

	// ---- Change the Dermatos plugin remove WP adminbar quicklinks value to false.
	add_filter('dermatos_remove_wordpress_adminbar_quicklinks_filter', '__return_false');

	// ---- Change the Dermatos plugin freplace howdy greeting value to false.
	add_filter('dermatos_replace_admin_howdy_greeting_filter', '__return_false');

	// ---- Change the Dermatos plugin theme can override admin styles value to false.
	add_filter('dermatos_theme_can_override_admin_styles_filter', '__return_false');

	// ---- Change the Dermatos plugin login background path.
	add_filter('dermatos_login_background_path_filter', 'lutrov_login_background_path_filter');
	function lutrov_login_background_path_filter($path) {
		$path = sprintf('%s/wp-content/uploads/2017/04/login-background.jpg', ABSPATH);
		return $path;
	}

	// ---- Change the Dermatos plugin login logo path.
	add_filter('dermatos_login_logo_path_filter', 'lutrov_login_logo_path_filter');
	function lutrov_login_logo_path_filter($path) {
		$path = sprintf('%s/wp-content/uploads/2017/04/login-logo.png', ABSPATH);
		return $path;
	}

	// ---- Change the Dermatos plugin meta favicon path.
	add_filter('dermatos_meta_favicon_path_filter', 'lutrov_meta_favicon_path_filter');
	function lutrov_meta_favicon_path_filter($path) {
		$path = sprintf('%s/wp-content/uploads/2017/04/meta-favicon.png', ABSPATH);
		return $path;
	}



dermatos_meta_favicon_path_filter

Or if you're using a custom site plugin (you should be), do it via the `plugins_loaded` hook instead:

	// ---- Change the Dermatos plugin constant values.
	add_action('plugins_loaded', 'custom_dermatos_filters');
	function custom_dermatos_filters() {
		// Change the keep lost password link value to false.
		add_filter('dermatos_keep_lost_password_link_filter', '__return_false');
		// Change the keep quiet about login errors value to false.
		add_filter('dermatos_keep_quiet_about_login_errors_filter', '__return_false');
		// Change the login redirect non admins value to false.
		add_filter('dermatos_login_redirect_non_admins_filter', '__return_false');
		// Change the remove WP adminbar quicklinks value to false.
		add_filter('dermatos_remove_wordpress_adminbar_quicklinks_filter', '__return_false');
		// Change the freplace howdy greeting value to false.
		add_filter('dermatos_replace_admin_howdy_greeting_filter', '__return_false');
		// Change the theme can override admin styles value to false.
		add_filter('dermatos_theme_can_override_admin_styles_filter', '__return_false');
		// ---- Change the Dermatos plugin login background path.
		add_filter('dermatos_login_background_path_filter', 'lutrov_login_background_path_filter');
		function lutrov_login_background_path_filter($path) {
			$path = sprintf('%s/wp-content/uploads/2017/04/login-background.jpg', ABSPATH);
			return $path;
		}
		// ---- Change the Dermatos plugin login logo path.
		add_filter('dermatos_login_logo_path_filter', 'lutrov_login_logo_path_filter');
		function lutrov_login_logo_path_filter($path) {
			$path = sprintf('%s/wp-content/uploads/2017/04/login-logo.png', ABSPATH);
			return $path;
		}
		// ---- Change the Dermatos plugin meta favicon path.
		add_filter('dermatos_meta_favicon_path_filter', 'lutrov_meta_favicon_path_filter');
		function lutrov_meta_favicon_path_filter($path) {
			$path = sprintf('%s/wp-content/uploads/2017/04/meta-favicon.png', ABSPATH);
			return $path;
		}
	}


Note, this second approach will _not_ work from your theme's `functions.php` file.
