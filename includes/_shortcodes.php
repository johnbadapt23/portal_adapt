<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}


// custom
function shortcode_custom( $atts ) {
	return "<p class='{$atts['class']}'>$content</p>";
}
// add_shortcode( 'custom', 'shortcode_custom' );

?>
