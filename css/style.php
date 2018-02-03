<?php

//////////////////////////////////////////////////////////////////////////////
//
//      Script: style.php
// Description: Dermatos stylesheet controller.
//      Author: Ivan Lutrov <ivan@lutrov.com>
//
//////////////////////////////////////////////////////////////////////////////

//
// Constants used by this script.
//
define('DERMATOS_COLOUR_HIGHLIGHT_PRIMARY', '#0078d7');
define('DERMATOS_COLOUR_HIGHLIGHT_ACCENT', '#49b345');
define('DERMATOS_COLOUR_DELETE_ACTION', '#d71900');
define('DERMATOS_FONT_FAMILY', 'Roboto Condensed');
define('DERMATOS_FONT_FAMILY_ESCAPED', 'Roboto+Condensed');
define('DERMATOS_FONT_WEIGHT_NORMAL', '300');
define('DERMATOS_FONT_WEIGHT_BOLD', '400');
define('DERMATOS_FONT_WEIGHT_EXTRA_BOLD', '700');
define('DERMATOS_CACHE_EXPIRY_SECONDS', 604800);

//
// Include main Wordpress config file.
//
$path = sprintf('%s/wp-config.php', dirname(dirname(dirname(dirname(dirname(__FILE__))))));
if (file_exists($path) == true) {
	include($path);
}

//
// Create fake apply_filters() function in case we can't find the wp-config.php file.
//
if (function_exists('apply_filters') == false) {
	function apply_filters($id, $value) {
		return $value;
	}
}

//
// Get the requested CSS filename.
//
$file = isset($_GET['file']) ? $_GET['file'] : null;

//
// Read the CSS from a template and output.
//
if ($file == 'admin' || $file == 'hacks' || $file == 'login' || $file == 'customizer' || $file == 'public') {
	$path = sprintf('%s/templates/%s.css', dirname(__FILE__), $file);
	if (file_exists($path)) {
		$css = file_get_contents($path);
		$modified = filemtime($path);
		// Parse and compress the CSS code
		$css = str_replace(
			array(
				'[[COLOUR-HIGHLIGHT-PRIMARY]]',
				'[[COLOUR-HIGHLIGHT-ACCENT]]',
				'[[COLOUR-DELETE-ACTION]]',
				'[[FONT-FAMILY]]',
				'[[FONT-FAMILY-ESCAPED]]',
				'[[FONT-WEIGHT-NORMAL]]',
				'[[FONT-WEIGHT-BOLD]]',
				'[[FONT-WEIGHT-EXTRA-BOLD]]'
			),
			array(
				apply_filters('dermatos_colour_highlight_primary_filter', DERMATOS_COLOUR_HIGHLIGHT_PRIMARY),
				apply_filters('dermatos_colour_highlight_accent_filter', DERMATOS_COLOUR_HIGHLIGHT_ACCENT),
				apply_filters('dermatos_colour_delete_action_filter', DERMATOS_COLOUR_DELETE_ACTION),
				apply_filters('dermatos_font_family_filter', DERMATOS_FONT_FAMILY),
				apply_filters('dermatos_font_family_escaped_filter', DERMATOS_FONT_FAMILY_ESCAPED),
				apply_filters('dermatos_font_weight_normal_filter', DERMATOS_FONT_WEIGHT_NORMAL),
				apply_filters('dermatos_font_weight_bold_filter', DERMATOS_FONT_WEIGHT_BOLD),
				apply_filters('dermatos_font_weight_extra_bold_filter', DERMATOS_FONT_WEIGHT_EXTRA_BOLD),
			),
			str_replace(array("\x09", "\x0A", "\x0D", ': ', ', ', '{ ', ' {', '; ', ';}', ' }', '} ', ' !', ' + ', ' > '), array(null, null, null, ':', ',', '{', '{', ';', '}', '}', '}', '!', '+', '>'), preg_replace(array('#/\*[^*]*\*+([^/][^*]*\*+)*/#', '#\s[\s]+#'), array(null, ' '), $css))
		);
		// Output the correct mime type and set expiry header to 1 hour
		header('Content-Type: text/css; charset: UTF-8');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $modified) . ' GMT');
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + apply_filters('dermatos_cache_expiry_seconds_filter', DERMATOS_CACHE_EXPIRY_SECONDS)) . ' GMT');
		header('Cache-Control: public');
		// Output the compressed CSS code
		echo trim($css);
	}
}

?>
