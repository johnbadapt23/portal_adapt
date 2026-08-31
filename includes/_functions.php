<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

//current url
function current_url() {
	// Was hardcoded to http:// with the is_ssl()-equivalent check
	// commented out - on an HTTPS site (i.e. every site today) that made
	// this always return the wrong protocol, which broke
	// custom_add_parent_url_menu_class()'s strstr() comparison against
	// menu item URLs (those are saved as https:// via home_url()), so
	// the "active" class never matched absolute-URL menu items.
	$is_standard_port = in_array( $_SERVER['SERVER_PORT'], [ '80', '443' ], true );
	$url  = is_ssl() ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= $is_standard_port ? '' : ':' . $_SERVER['SERVER_PORT'];
	$url .= $_SERVER['REQUEST_URI'];
	return trailingslashit( $url );
}


// get id from slug
function get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} else {
		return null;
	}
}

// get excerpt by id
function get_excerpt_by_id($post_id){
    $the_post = get_post($post_id);
    $the_excerpt = $the_post->post_excerpt;
    return $the_excerpt;
}

// check from pagination
function is_paginated() {
    global $wp_query;
    if ( $wp_query->max_num_pages > 1 ) {
        return true;
    } else {
        return false;
    }
}

// create slug
function slugify ($string) {
    // utf8_encode() is deprecated as of PHP 8.2 - mb_convert_encoding() with
    // the same ISO-8859-1 -> UTF-8 direction is the direct replacement.
    // Also guard against null (e.g. an empty ACF field passed straight in)
    // since PHP 8.1+ deprecates passing null to a non-nullable string param,
    // which both this and iconv() have.
    $string = (string) $string;
    $string = mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $string = preg_replace('/[^a-z0-9- ]/i', '', $string);
    $string = str_replace(' ', '-', $string);
    $string = trim($string, '-');
    $string = strtolower($string);

    if (empty($string)) {
        return '';
    }

    return $string;
}

// update query string
function updateQueryString($key, $value){
	parse_str($_SERVER['QUERY_STRING'], $query_string);

	if ($value == '') {
		unset($query_string[$key]);
	} else {
		$query_string[$key] = $value;
	}

	return http_build_query($query_string);
}

// redirect page
function redirectPage($url){
	echo '<script type="text/javascript">window.location = "' . esc_js( esc_url( $url ) ) . '"</script>';
}

// Shared by the persona/sector/topic/post filter templates: returns the
// slugs a filter dropdown should restrict to, or an empty array when
// "all" is allowed for that field. Was previously declared, identically,
// as an unguarded top-level function inside each of those four template
// files - harmless today since only one Template Name page loads per
// request, but a fatal redeclare waiting to happen the moment two of them
// are ever included in the same request.
if ( ! function_exists( 'get_allowed_slugs' ) ) {
	function get_allowed_slugs($field_name, $all_field_name, $taxonomy = null) {
		if ( get_field($field_name) == 1 && $taxonomy ) {
			return []; // all allowed
		}
		$terms = get_field($all_field_name) ?: [];
		if ($taxonomy && get_field($field_name) != 1) {
			return array_map(fn($term) => $term->slug, is_array($terms) ? $terms : []);
		}
		return [];
	}
}

?>
