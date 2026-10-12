<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Permalinks Customizer bulk-edit fix
 * ------------------------------------
 * Permalinks Customizer (its save_post_permalink(), hooked to save_post at
 * priority 10) stores its working value in $_REQUEST['permalinks_customizer'].
 * During a bulk edit, WordPress saves every selected post in a single
 * request (bulk_edit_posts() in wp-admin/includes/post.php), so the value
 * set while processing the first post is still sitting in $_REQUEST for
 * every subsequent post in that same batch, and the plugin treats it as a
 * manually entered permalink for each of them in turn - which is why
 * bulk-editing a group of posts leaves every post in that batch with the
 * first post's permalink.
 *
 * Hooked at priority 9, ahead of the plugin's own priority-10 handler, so
 * these stale values are cleared before the plugin reads them for each
 * post, letting it fall back to that post's own stored permalink instead.
 *
 * To disable this fix, remove the require() for this file in functions.php
 * (or delete this file) - nothing else in the theme depends on it.
 */
function adapt_fix_permalinks_customizer_bulk_edit() {
	// Only act on the core bulk edit request (wp-admin/edit.php, bulk_edit_posts()).
	if ( ! is_admin() || ! isset( $_REQUEST['bulk_edit'] ) ) {
		return;
	}

	unset(
		$_REQUEST['permalinks_customizer'],
		$_REQUEST['permalinks_customizer_regenerate_permalink']
	);
}
add_action( 'save_post', 'adapt_fix_permalinks_customizer_bulk_edit', 9 );
