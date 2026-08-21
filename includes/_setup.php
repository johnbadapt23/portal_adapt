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

/**
 * ACF Local JSON - point both the save and load paths at a single
 * version-controlled acf-json/ folder in the theme, instead of ACF's
 * default (wp-content/uploads/acf-json, which isn't tracked in git and
 * doesn't travel between dev/staging/production on its own).
 *
 * Once this is in place, every field group save from wp-admin
 * (Custom Fields > Field Groups) also writes/updates a matching JSON file
 * here automatically - commit and push those files like any other theme
 * file, and the next deploy picks them up with no manual re-entry of
 * fields on the other environment. ACF compares each file's "modified"
 * timestamp against the DB record and uses whichever is newer, so this is
 * safe to add without disturbing whatever's currently saved in the DB on
 * any environment - existing field groups keep working exactly as they do
 * now until the next real edit.
 */
add_filter( 'acf/settings/save_json', function() {
	return get_stylesheet_directory() . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function( $paths ) {
	// Replace ACF's default load path rather than adding to it, so the
	// theme's acf-json/ folder is the single source of truth instead of
	// two paths that could silently drift out of sync with each other.
	unset( $paths[0] );
	$paths[] = get_stylesheet_directory() . '/acf-json';
	return $paths;
} );

?>
