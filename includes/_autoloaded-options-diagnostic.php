<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}
/**
 * Temporary, read-only diagnostic for the Site Health "Autoloaded options
 * could affect performance" critical issue (Tools > Site Health > Status
 * reported 763 autoloaded options, 914 KB, on 2026-09-03 - over WordPress's
 * own recommended threshold). Site Health reports the total but not which
 * options are responsible, and this project has no WP-CLI or direct
 * database access to run `wp option list --autoload=on --orderby=size` -
 * only this theme's own wp-admin session. This adds a Tools submenu page
 * that runs the same kind of read-only SELECT against $wpdb->options
 * directly, gated to manage_options like every other admin-only screen
 * in this theme, so the actual offending options can be identified and
 * cleaned up (or reported to whichever plugin owns them).
 *
 * Delete this file (and its require() line in functions.php) once the
 * autoloaded-options finding has been resolved - it exists to make that
 * one Site Health finding actionable, not as a permanent feature.
 */

add_action( 'admin_menu', 'adapt_add_autoloaded_options_diagnostic_page' );
function adapt_add_autoloaded_options_diagnostic_page() {
	add_management_page(
		'Autoloaded Options',
		'Autoloaded Options',
		'manage_options',
		'adapt-autoloaded-options',
		'adapt_render_autoloaded_options_diagnostic_page'
	);
}

function adapt_render_autoloaded_options_diagnostic_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	global $wpdb;

	// phpcs:disable WordPress.DB.DirectDatabaseQuery -- read-only diagnostic against wp_options with no equivalent WP API; not cached since this page is only ever loaded on demand by an admin.
	$totals = $wpdb->get_row(
		"SELECT COUNT(*) AS option_count, SUM(LENGTH(option_value)) AS total_bytes FROM {$wpdb->options} WHERE autoload = 'yes'"
	);

	$largest = $wpdb->get_results(
		"SELECT option_name, LENGTH(option_value) AS size_bytes FROM {$wpdb->options} WHERE autoload = 'yes' ORDER BY size_bytes DESC LIMIT 50"
	);
	// phpcs:enable WordPress.DB.DirectDatabaseQuery

	echo '<div class="wrap">';
	echo '<h1>Autoloaded Options</h1>';
	echo '<p>' . esc_html( number_format_i18n( (int) $totals->option_count ) ) . ' autoloaded options, ' . esc_html( size_format( (int) $totals->total_bytes ) ) . ' total. WordPress Site Health flags this as a performance concern above roughly 800 KB.</p>';
	echo '<table class="widefat striped"><thead><tr><th>Option name</th><th>Size</th></tr></thead><tbody>';

	foreach ( $largest as $row ) {
		echo '<tr><td><code>' . esc_html( $row->option_name ) . '</code></td><td>' . esc_html( size_format( (int) $row->size_bytes ) ) . '</td></tr>';
	}

	echo '</tbody></table>';
	echo '</div>';
}
