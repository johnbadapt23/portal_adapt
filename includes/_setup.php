<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

// theme_setup
function theme_setup() {

	// Loads languages/portal-{locale}.mo if one is ever added - matches the
	// "Text Domain: portal" / "Domain Path: /languages" header in style.css.
	// No .mo files exist yet, so this is currently a no-op, but every
	// __()/_e() call in the theme already uses the 'portal' domain (or
	// should - see includes/_menu.php fix), so translations will just work
	// the moment one is dropped in, with no further code changes needed.
	load_theme_textdomain( 'portal', get_template_directory() . '/languages' );

	add_editor_style();
	// Note: menu support comes from register_nav_menus() in
	// includes/_menu.php, not add_theme_support() - 'menus' isn't a real
	// WP theme-support feature (WP core only recognizes a fixed set like
	// post-thumbnails/title-tag/html5/custom-header/etc.), so the previous
	// add_theme_support('menus') call here was silently doing nothing.
	add_theme_support( 'post-thumbnails' );
	// Lets WP (and Yoast SEO, which is active on this site) manage the
	// <title> tag via wp_head() instead of the theme hardcoding one with
	// wp_title() - the standard approach since WP 4.1, and what Yoast's
	// own "hardcoded title tag" admin notice asks every theme to do.
	add_theme_support( 'title-tag' );

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
