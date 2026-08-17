<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

// theme_setup
function theme_setup() {

	add_editor_style();
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );

	if ( function_exists('acf_add_options_page') ) {
    	acf_add_options_page();
    }
}

?>
