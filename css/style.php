<?php

//////////////////////////////////////////////////////////////////////////////
//
//	Script: style.php
// Description: Dermatos stylesheet controller.
//	Author: Ivan Lutrov <ivan@lutrov.com>
//
//////////////////////////////////////////////////////////////////////////////

//
// Include constants used by this script.
//
define('DERMATOS_FONT_FAMILY', 'Roboto Condensed');
define('DERMATOS_FONT_FAMILY_ESCAPED', 'Roboto+Condensed');
define('DERMATOS_FONT_WEIGHT_NORMAL', '300');
define('DERMATOS_FONT_WEIGHT_BOLD', '400');
define('DERMATOS_FONT_WEIGHT_EXTRA_BOLD', '700');
define('DERMATOS_PRIMARY_HIGHLIGHT_COLOUR', 'f45246');
define('DERMATOS_SECONDARY_HIGHLIGHT_COLOUR', '18b3dc');

//
// Get the requested CSS filename.
//
$file = isset($_GET['file']) ? $_GET['file'] : null;

//
// Read the CSS from a template.
//
$css = null;
if (strlen($file) > 0) {
	$path = sprintf('%s/templates/%s.css', dirname(__FILE__), $file);
	if (file_exists($path)) {
		$css = file_get_contents($path);
	}
}

//
// Parse and compress the CSS code.
//
$css = str_replace(
	array(
		'[[DERMATOS_FONT_FAMILY]]',
		'[[DERMATOS_FONT_FAMILY_ESCAPED]]',
		'[[DERMATOS_FONT_WEIGHT_NORMAL]]',
		'[[DERMATOS_FONT_WEIGHT_BOLD]]',
		'[[DERMATOS_FONT_WEIGHT_EXTRA_BOLD]]',
		'[[DERMATOS_PRIMARY_HIGHLIGHT_COLOUR]]',
		'[[DERMATOS_SECONDARY_HIGHLIGHT_COLOUR]]'
	),
	array(
		DERMATOS_FONT_FAMILY,
		DERMATOS_FONT_FAMILY_ESCAPED,
		DERMATOS_FONT_WEIGHT_NORMAL,
		DERMATOS_FONT_WEIGHT_BOLD,
		DERMATOS_FONT_WEIGHT_EXTRA_BOLD,
		DERMATOS_PRIMARY_HIGHLIGHT_COLOUR,
		DERMATOS_SECONDARY_HIGHLIGHT_COLOUR
	),
	str_replace(array("\x09", "\x0A", "\x0D", ': ', ', ', '{ ', ' {', '; ', ';}', ' }', '} ', ' !'), array(null, null, null, ':', ',', '{', '{', ';', '}', '}', '}', '!'), preg_replace(array('#/\*[^*]*\*+([^/][^*]*\*+)*/#', '#\s[\s]+#'), array(null, ' '), $css))
);

//
// Output the correct mime type and set expiry header to 1 hour.
//
header('Content-type: text/css; charset: UTF-8');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

//
// Output the compressed CSS code.
//
echo trim($css);

?>