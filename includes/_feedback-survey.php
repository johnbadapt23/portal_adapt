<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}
/**
 * Feedback survey - a form rendered from an admin-configured shortcode
 * (built and edited entirely in whichever form plugin is in use - Contact
 * Form 7, WPForms, Gravity Forms, whatever - not hardcoded here) shown once
 * per logged-in user inside a popup styled to match the welcome spotlight's
 * centered dialog, starting from a configurable date. Not tied to any one
 * form plugin: the admin pastes the shortcode for whatever form they've
 * built, and do_shortcode() renders it as-is.
 *
 * Closing it (X, overlay click, or Escape) WITHOUT submitting does not
 * suppress it permanently - it's only a "not right now", so it comes back
 * on the visitor's next page load. It only stops showing for good once
 * they've actually submitted the form. Since the shortcode can be any form
 * plugin's, submission is detected via named integrations for the common
 * ones (Contact Form 7, Gravity Forms, WPForms, HubSpot) plus a
 * plugin-agnostic DOM fallback for anything else not explicitly wired up
 * (see the inline script below) - so this doesn't just wait around for CF7
 * specifically.
 *
 * Entirely self-contained, same pattern as includes/_welcome-popup.php: its
 * own ACF field group on the existing options page, its own once-per-user
 * "submitted" user meta flag, its own nonce-verified AJAX endpoint -
 * independent of the welcome popup, so either can be edited, toggled, or
 * removed without touching the other.
 */

/**
 * Same WP Rocket Delay JS Execution issue already found and fixed for the
 * welcome popup applies to any inline script in wp_footer - exempt this
 * one too, matched against its container id.
 */
add_filter( 'rocket_delay_js_exclusions', function( $exclusions ) {
	$exclusions[] = 'adapt-feedback-survey';
	return $exclusions;
} );

/**
 * Register the "Feedback Survey" field group on the existing ACF options
 * page - same local field group pattern as the welcome popup's.
 */
add_action( 'acf/init', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$shown_if_enabled = [
		[
			[
				'field'    => 'field_adapt_feedback_survey_enabled',
				'operator' => '==',
				'value'    => '1',
			],
		],
	];

	acf_add_local_field_group( [
		'key'    => 'group_adapt_feedback_survey',
		'title'  => 'Feedback Survey',
		'fields' => [
			[
				'key'           => 'field_adapt_feedback_survey_enabled',
				'label'         => 'Enabled',
				'name'          => 'feedback_survey_enabled',
				'type'          => 'true_false',
				'instructions'  => 'Show a one-time feedback survey popup to logged-in users, starting from the date below.',
				'default_value' => 0,
				'ui'            => 1,
			],
			[
				'key'               => 'field_adapt_feedback_survey_start_date',
				'label'             => 'Start showing from',
				'name'              => 'feedback_survey_start_date',
				'type'              => 'date_picker',
				'instructions'      => 'The default start date, used for any role with no override in "Per-role start dates" below. The popup will not appear before this date, even if enabled. Defaults to 2 weeks out from when this feature was built.',
				'display_format'    => 'd/m/Y',
				'return_format'     => 'Ymd',
				'first_day'         => 1,
				'default_value'     => '20260827',
				'conditional_logic' => $shown_if_enabled,
			],
			[
				'key'               => 'field_adapt_feedback_survey_role_start_dates',
				'label'             => 'Per-role start dates',
				'name'              => 'feedback_survey_role_start_dates',
				'type'              => 'textarea',
				'rows'              => 4,
				// Plain "role: date" lines rather than a repeater field -
				// repeater/flexible-content are ACF PRO-only and nothing
				// else in this codebase uses them (grepped every ACF field
				// group here: only text/textarea/true_false/date_picker
				// appear), so this stays usable regardless of which ACF
				// tier is actually licensed on this install. See
				// adapt_parse_feedback_survey_role_start_dates() for the
				// parser and adapt_get_feedback_survey_start_date_for_user()
				// for how it's resolved per user.
				'instructions'      => 'Optional per-role overrides for the start date above - e.g. show it to subscribers today but hold off on agent_tester for two more weeks. Use "+ Add role override" below to pick a role and a date per row. A role not listed here still uses the start date field above. If a user holds more than one role listed here, the earliest of their matching dates applies. Under the hood this is stored as one "role_slug: YYYY-MM-DD" pair per line - "Edit as plain text" below the rows exposes that directly, handy for pasting several at once; lines that don\'t match that exact format are silently ignored there too, so a typo just falls back to the default above rather than breaking the popup for everyone. Currently registered role slugs: ' . implode( ', ', array_keys( wp_roles()->get_names() ) ) . '.',
				'conditional_logic' => $shown_if_enabled,
			],
			[
				'key'               => 'field_adapt_feedback_survey_shortcode',
				'label'             => 'Form shortcode',
				'name'              => 'feedback_survey_shortcode',
				'type'              => 'text',
				'instructions'      => 'The shortcode for the form to show - works with any form plugin\'s shortcode, e.g. [contact-form-7 id="123" title="Feedback"] or [gravityform id="4"]. Build/edit the actual survey questions in that plugin, not here.',
				'conditional_logic' => $shown_if_enabled,
			],
			[
				'key'               => 'field_adapt_feedback_survey_target_selector',
				'label'             => 'Target element (CSS selector)',
				'name'              => 'feedback_survey_target_selector',
				'type'              => 'text',
				'instructions'      => 'Same idea as the welcome popup\'s equivalent field - the element to highlight behind the survey dialog. Defaults to the homepage AI Assistant box, matching the welcome popup it follows on from. If the element is not found on a given page (not loaded yet, or this page does not have it), the survey silently does not show and the user is not counted as having seen it - they will still get it on a page where the element does appear. Leave blank to always show the survey without any target check or highlight.',
				'default_value'     => '.cgpt-hero-card',
				'conditional_logic' => $shown_if_enabled,
			],
			[
				'key'               => 'field_adapt_feedback_survey_heading',
				'label'             => 'Heading',
				'name'              => 'feedback_survey_heading',
				'type'              => 'text',
				'default_value'     => "We'd love your feedback",
				'conditional_logic' => $shown_if_enabled,
			],
			[
				'key'               => 'field_adapt_feedback_survey_intro',
				'label'             => 'Intro text',
				'name'              => 'feedback_survey_intro',
				'type'              => 'textarea',
				'rows'              => 3,
				'instructions'      => 'Optional - shown above the form itself.',
				'conditional_logic' => $shown_if_enabled,
			],
			[
				'key'               => 'field_adapt_feedback_survey_force_redisplay',
				'label'             => 'Show again to everyone',
				'name'              => 'feedback_survey_force_redisplay',
				'type'              => 'true_false',
				'instructions'      => 'Same idea as the welcome popup\'s equivalent toggle - turn this ON to bring the survey back for everyone who already submitted it, without losing that history (their submission record is kept, just ignored while this is ON). Turn it back OFF to resume once-per-user behavior. Note simply closing the survey without submitting never suppresses it long-term either way - it always comes back on the next page load until submitted.',
				'default_value'     => 0,
				'ui'                => 1,
				'conditional_logic' => $shown_if_enabled,
			],
		],
		'location' => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'acf-options',
				],
			],
		],
	] );
} );

/**
 * Friendlier admin UI for "Per-role start dates" - a role dropdown + native
 * date picker per row, with add/remove buttons, laid out under the plain
 * textarea ACF actually renders (see the field's own comment above for why
 * it's a textarea and not a real ACF repeater: no ACF PRO confirmed on this
 * install). This is presentation only - the textarea stays the field ACF
 * saves, so adapt_parse_feedback_survey_role_start_dates() needed no
 * changes at all; the rows UI just reads/writes that same "role: YYYY-MM-DD"
 * text underneath it, keeping the low-risk plain-text format as the actual
 * source of truth.
 *
 * The raw textarea itself is moved (not removed - moving it, rather than
 * hiding it in place, keeps it right where someone expanding "Edit as plain
 * text" below would expect to find it) into a collapsible details/summary
 * under the row UI, so bulk edits (pasting several lines at once) are still
 * possible without clicking "+ Add role override" repeatedly - editing it
 * there re-parses back into rows automatically, same as loading the page
 * with existing values already in it.
 */
add_action( 'acf/render_field/key=field_adapt_feedback_survey_role_start_dates', 'adapt_render_feedback_survey_role_dates_ui' );
function adapt_render_feedback_survey_role_dates_ui( $field ) {
	$roles = wp_roles()->get_names(); // role_slug => Display Name.
	?>
	<div class="adapt-frs-role-dates" data-roles="<?php echo esc_attr( wp_json_encode( $roles ) ); ?>">
		<table class="adapt-frs-role-dates-table widefat">
			<tbody></tbody>
		</table>
		<button type="button" class="button adapt-frs-add-row"><?php esc_html_e( '+ Add role override', 'adapt' ); ?></button>
	</div>
	<?php
	// Print the shared CSS/JS once no matter how many times this specific
	// field renders on the page (ACF options pages only render each field
	// once, but this guards against that changing without anyone noticing).
	static $printed_assets = false;
	if ( $printed_assets ) {
		return;
	}
	$printed_assets = true;
	?>
	<style>
		.adapt-frs-role-dates-table { max-width: 480px; margin-bottom: 8px; border-collapse: collapse; }
		.adapt-frs-role-dates-table td { padding: 4px 8px 4px 0; vertical-align: top; }
		.adapt-frs-role-dates-table select { max-width: 220px; }
		.adapt-frs-dupe-note { font-size: 11px; color: #b32d2e; margin-top: 2px; max-width: 200px; }
		.adapt-frs-raw-toggle { margin-top: 10px; }
		.adapt-frs-raw-toggle summary { cursor: pointer; color: #2271b1; font-size: 12px; }
	</style>
	<script>
	( function() {
		// One-line role/date pairs, tolerant of the same malformed input the
		// PHP-side parser silently skips - kept in sync with
		// adapt_parse_feedback_survey_role_start_dates() on purpose so a row
		// this UI would show is exactly a row the PHP side will actually use.
		function parseLines( raw ) {
			var rows = [];
			( raw || '' ).split( /\r\n|\r|\n/ ).forEach( function( line ) {
				line = line.trim();
				var sep = line.indexOf( ':' );
				if ( ! line || sep === -1 ) {
					return;
				}
				var role = line.slice( 0, sep ).trim();
				var date = line.slice( sep + 1 ).trim();
				if ( ! role || ! /^\d{4}-\d{2}-\d{2}$/.test( date ) ) {
					return;
				}
				rows.push( { role: role, date: date } );
			} );
			return rows;
		}

		function serializeRows( rows ) {
			return rows
				.filter( function( r ) { return r.role && r.date; } )
				.map( function( r ) { return r.role + ': ' + r.date; } )
				.join( '\n' );
		}

		function initOne( container ) {
			var acfInput = container.closest( '.acf-input' );
			var textarea = acfInput ? acfInput.querySelector( 'textarea' ) : null;
			if ( ! textarea ) {
				return; // Nothing to enhance - leave the field as plain ACF renders it.
			}

			var roles = {};
			try {
				roles = JSON.parse( container.getAttribute( 'data-roles' ) || '{}' );
			} catch ( e ) {}

			var tbody   = container.querySelector( '.adapt-frs-role-dates-table tbody' );
			var addBtn  = container.querySelector( '.adapt-frs-add-row' );
			var syncing = false; // Guards against our own sync() re-triggering rebuildRowsFromTextarea() below.

			function roleOptionsHtml( selected ) {
				var html = '';
				Object.keys( roles ).forEach( function( slug ) {
					html += '<option value="' + slug + '"' + ( slug === selected ? ' selected' : '' ) + '>' + roles[ slug ] + '</option>';
				} );
				return html;
			}

			function markDuplicates() {
				var counts = {};
				tbody.querySelectorAll( 'select' ).forEach( function( s ) {
					counts[ s.value ] = ( counts[ s.value ] || 0 ) + 1;
				} );
				tbody.querySelectorAll( 'tr' ).forEach( function( tr ) {
					var select = tr.querySelector( 'select' );
					var note   = tr.querySelector( '.adapt-frs-dupe-note' );
					note.style.display = ( select.value && counts[ select.value ] > 1 ) ? '' : 'none';
				} );
			}

			function sync() {
				syncing = true;
				var rows = [];
				tbody.querySelectorAll( 'tr' ).forEach( function( tr ) {
					rows.push( {
						role: tr.querySelector( 'select' ).value,
						date: tr.querySelector( 'input[type="date"]' ).value
					} );
				} );
				textarea.value = serializeRows( rows );
				// Real events, not just a value assignment - so ACF's own
				// "unsaved changes" tracking (bound to this textarea like any
				// other field) still notices the edit, same as if someone had
				// typed directly into the box.
				textarea.dispatchEvent( new Event( 'input', { bubbles: true } ) );
				textarea.dispatchEvent( new Event( 'change', { bubbles: true } ) );
				syncing = false;
				markDuplicates();
			}

			function addRow( role, date ) {
				var tr = document.createElement( 'tr' );
				var tdRole = document.createElement( 'td' );
				tdRole.innerHTML = '<select>' + roleOptionsHtml( role ) + '</select>';
				var tdDate = document.createElement( 'td' );
				var dateInput = document.createElement( 'input' );
				dateInput.type = 'date';
				dateInput.value = date || '';
				tdDate.appendChild( dateInput );
				var tdRemove = document.createElement( 'td' );
				var removeBtn = document.createElement( 'button' );
				removeBtn.type = 'button';
				removeBtn.className = 'button-link';
				removeBtn.setAttribute( 'aria-label', <?php echo wp_json_encode( __( 'Remove' ) ); ?> );
				removeBtn.innerHTML = '<span class="dashicons dashicons-no-alt"></span>';
				var dupeNote = document.createElement( 'div' );
				dupeNote.className = 'adapt-frs-dupe-note';
				dupeNote.style.display = 'none';
				dupeNote.textContent = <?php echo wp_json_encode( __( 'Another row already overrides this role - the last one wins.' ) ); ?>;
				tdRemove.appendChild( removeBtn );
				tdRemove.appendChild( dupeNote );
				tr.appendChild( tdRole );
				tr.appendChild( tdDate );
				tr.appendChild( tdRemove );
				tbody.appendChild( tr );

				tdRole.querySelector( 'select' ).addEventListener( 'change', sync );
				dateInput.addEventListener( 'change', sync );
				removeBtn.addEventListener( 'click', function() {
					tr.remove();
					sync();
				} );
			}

			function rebuildRowsFromTextarea() {
				tbody.innerHTML = '';
				parseLines( textarea.value ).forEach( function( r ) { addRow( r.role, r.date ); } );
				markDuplicates();
			}

			textarea.addEventListener( 'input', function() {
				if ( syncing ) {
					return;
				}
				rebuildRowsFromTextarea();
			} );

			rebuildRowsFromTextarea();

			addBtn.addEventListener( 'click', function() {
				addRow( Object.keys( roles )[ 0 ] || '', '' );
				sync();
			} );

			// Move (not hide-in-place) the actual field ACF saves into a
			// collapsed "edit as text" section under the rows, so bulk-pasting
			// several lines at once is still possible without fighting the
			// row UI - collapsed by default since the rows above are the
			// normal path.
			var details = document.createElement( 'details' );
			details.className = 'adapt-frs-raw-toggle';
			var summary = document.createElement( 'summary' );
			summary.textContent = <?php echo wp_json_encode( __( 'Edit as plain text' ) ); ?>;
			details.appendChild( summary );
			details.appendChild( textarea );
			container.appendChild( details );
		}

		function init() {
			document.querySelectorAll( '.adapt-frs-role-dates' ).forEach( initOne );
		}

		if ( document.readyState === 'loading' ) {
			document.addEventListener( 'DOMContentLoaded', init );
		} else {
			init();
		}
	} )();
	</script>
	<?php
}

/**
 * Renders (once, memoized) and returns the survey's configured shortcode
 * output. Deliberately given its first call from the wp hook below - not
 * just before wp_head, but before wp_enqueue_scripts even fires - rather
 * than from wp_footer where the markup actually gets echoed.
 *
 * Several form plugins (WPForms among them) don't enqueue their CSS/JS
 * directly as an immediate side effect of the shortcode running - instead
 * their own wp_enqueue_scripts callback checks an internal "was a form
 * displayed on this request" flag and enqueues only if that's true. That
 * callback is registered by the plugin itself, which loads well before the
 * theme does, so it runs BEFORE any wp_enqueue_scripts callback this theme
 * registers - meaning triggering the shortcode from our own
 * wp_enqueue_scripts hook is already too late; the plugin's own asset
 * decision has already been made and won't be reconsidered. Priming from
 * wp - which fires before wp_enqueue_scripts altogether, regardless of
 * hook registration order - guarantees the flag is set before any plugin's
 * asset-enqueue logic runs, so its styles land in time for wp_head to
 * print them normally. wp_footer then reuses this same cached HTML via a
 * second call below, so no plugin ever has its shortcode executed twice.
 *
 * Contact Form 7 masked this entirely, since it unconditionally enqueues
 * its own CSS on every front-end request regardless of shortcode timing -
 * but it's not safe to assume every plugin behaves that way.
 */
function adapt_get_feedback_survey_form_html() {
	static $html = null;
	if ( null === $html ) {
		$shortcode = get_field( 'feedback_survey_shortcode', 'option' );
		$html      = $shortcode ? do_shortcode( $shortcode ) : '';
	}
	return $html;
}

/**
 * Primes the shortcode render (and therefore each plugin's asset-detection
 * flags) as early in the request as possible - see the doc comment on
 * adapt_get_feedback_survey_form_html() above for why this has to happen
 * on wp, not wp_enqueue_scripts, and not inline in the wp_footer render
 * callback below.
 */
add_action( 'wp', function() {
	if ( is_admin() || ! adapt_should_show_feedback_survey() ) {
		return;
	}
	adapt_get_feedback_survey_form_html();

	// Belt-and-suspenders for WPForms specifically: confirmed via live
	// testing that priming the shortcode render alone isn't enough for
	// this plugin - even with its own "Load Assets Globally" setting
	// turned on, that only reliably forces its JS, not its CSS, for a
	// form rendered outside normal post content (a plain [wpforms ...]
	// pasted directly into a page's content editor works fine; this
	// footer-injected one didn't). wpforms()->frontend->assets_css() is
	// WPForms' own documented escape hatch for exactly this situation -
	// call it directly rather than continuing to rely on its internal
	// detection.
	if ( function_exists( 'wpforms' ) ) {
		wpforms()->frontend->assets_css();
	}
} );

/**
 * Parses the "Per-role start dates" textarea (field_adapt_feedback_survey_role_start_dates)
 * into a [ role_slug => Ymd ] map, memoized per request since it's read on
 * every adapt_should_show_feedback_survey() call. Deliberately tolerant of
 * bad input - a line that isn't exactly "role: YYYY-MM-DD" (typo'd role,
 * wrong date shape, stray blank line) is skipped rather than fataling, so a
 * mistake in one line only costs that one role its override, never breaks
 * the field for everyone else or the global fallback date.
 */
function adapt_parse_feedback_survey_role_start_dates() {
	static $parsed = null;
	if ( null !== $parsed ) {
		return $parsed;
	}
	$parsed = [];
	$raw = (string) get_field( 'feedback_survey_role_start_dates', 'option' );
	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line || false === strpos( $line, ':' ) ) {
			continue;
		}
		list( $role, $date ) = array_map( 'trim', explode( ':', $line, 2 ) );
		$role = sanitize_key( $role );
		if ( '' === $role || ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $date, $m ) ) {
			continue;
		}
		$parsed[ $role ] = $m[1] . $m[2] . $m[3]; // Ymd, matching current_time('Ymd') comparisons below.
	}
	return $parsed;
}

/**
 * Resolves the effective survey start date for one specific user: the
 * earliest per-role override (see adapt_parse_feedback_survey_role_start_dates())
 * among the roles this user actually holds, or the global
 * feedback_survey_start_date field when none of their roles has an
 * override (including when the textarea is empty entirely - the original,
 * global-only behavior). "Earliest of their roles" rather than "first role
 * matched" or "latest": a user who holds both an already-open role and a
 * still-scheduled one should get the survey now via the role that's
 * already open, not be held back by the other one they also happen to
 * hold.
 */
function adapt_get_feedback_survey_start_date_for_user( $user_id ) {
	$global_date = get_field( 'feedback_survey_start_date', 'option' ); // Ymd string, or falsy.
	$user = get_userdata( $user_id );
	if ( ! $user ) {
		return $global_date;
	}
	$role_dates = adapt_parse_feedback_survey_role_start_dates();
	if ( empty( $role_dates ) ) {
		return $global_date;
	}
	$matches = [];
	foreach ( (array) $user->roles as $role ) {
		if ( isset( $role_dates[ $role ] ) ) {
			$matches[] = $role_dates[ $role ];
		}
	}
	if ( empty( $matches ) ) {
		return $global_date;
	}
	sort( $matches ); // Ymd strings sort chronologically as plain strings.
	return $matches[0];
}

/**
 * Whether the current request should even attempt to render the survey:
 * logged in, feature enabled, a form shortcode is configured, today is
 * on/after this user's resolved start date (global, or a per-role override
 * - see adapt_get_feedback_survey_start_date_for_user()), and this user
 * hasn't already submitted the survey itself (unless exempted - see below).
 * Note there's no "already dismissed the survey" check here on purpose -
 * closing it without submitting is not persisted anywhere, so it's simply
 * asked again on the next page load.
 *
 * Deliberately independent of the welcome popup - this used to also require
 * adapt_welcome_popup_seen user meta (i.e. the user must have already
 * dismissed the welcome popup first), but that meant a user could go
 * without ever seeing the survey simply by leaving the welcome popup open/
 * unclosed, or if the welcome popup was disabled entirely. Once this
 * feature is enabled it should show for every valid user regardless of
 * whether they've seen or closed the welcome popup - the two popups no
 * longer gate each other; see adapt_should_show_welcome_popup()'s own
 * early-return for the other half of that relationship.
 *
 * Administrators always see it regardless of a past submission
 * (debugging/QA convenience, same exemption already used for the welcome
 * popup). The "Show again to everyone" field does the same for the
 * survey's own submitted check, for every logged-in user - an
 * admin-controlled, non-destructive override for bringing the survey back
 * without bulk-deleting submitted user meta.
 */
function adapt_should_show_feedback_survey() {
	if ( ! is_user_logged_in() ) {
		return false;
	}
	if ( ! get_field( 'feedback_survey_enabled', 'option' ) ) {
		return false;
	}
	$shortcode = trim( (string) get_field( 'feedback_survey_shortcode', 'option' ) );
	if ( ! $shortcode ) {
		return false; // Nothing configured to embed.
	}
	$start_date = adapt_get_feedback_survey_start_date_for_user( get_current_user_id() );
	if ( $start_date && current_time( 'Ymd' ) < $start_date ) {
		return false;
	}
	$bypass_submitted_check = get_field( 'feedback_survey_force_redisplay', 'option' ) || current_user_can( 'administrator' );
	if ( ! $bypass_submitted_check && get_user_meta( get_current_user_id(), 'adapt_feedback_survey_submitted', true ) ) {
		return false;
	}
	return true;
}

/**
 * Render the survey markup + its own small inline script in the footer,
 * same placement as the welcome popup and for the same reason - works on
 * any page a logged-in user might be on, not just one specific template.
 */
add_action( 'wp_footer', function() {
	if ( is_admin() || ! adapt_should_show_feedback_survey() ) {
		return;
	}

	$heading = get_field( 'feedback_survey_heading', 'option' );
	$intro   = get_field( 'feedback_survey_intro', 'option' );
	$target  = get_field( 'feedback_survey_target_selector', 'option' );
	$nonce   = wp_create_nonce( 'adapt_feedback_survey' );
	?>
	<div id="adapt-feedback-survey" class="feedbackSurvey" style="display:none;" role="dialog" aria-modal="true" <?php echo $heading ? 'aria-labelledby="adapt-feedback-survey-heading"' : ''; ?>>
		<div class="feedbackSurvey-overlay"></div>
		<div class="feedbackSurvey-highlight"></div>
		<div class="feedbackSurvey-dialog">
			<button type="button" class="feedbackSurvey-close" aria-label="Close">&times;</button>
			<?php if ( $heading ) : ?>
				<h2 id="adapt-feedback-survey-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $intro ) : ?>
				<p><?php echo nl2br( esc_html( $intro ) ); ?></p>
			<?php endif; ?>
			<div class="feedbackSurvey-form">
				<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- do_shortcode() output of an admin-authored form-plugin shortcode (CF7/WPForms/Gravity Forms/etc.); wp_kses_post() would strip required form markup. ?>
				<?php echo adapt_get_feedback_survey_form_html(); ?>
			</div>
		</div>
	</div>
	<script>
	(function() {
		var popup = document.getElementById('adapt-feedback-survey');
		if (!popup) return;

		var overlay   = popup.querySelector('.feedbackSurvey-overlay');
		var highlight = popup.querySelector('.feedbackSurvey-highlight');
		var dialog    = popup.querySelector('.feedbackSurvey-dialog');
		var closeBtn  = popup.querySelector('.feedbackSurvey-close');
		var formWrap  = popup.querySelector('.feedbackSurvey-form');
		var formEl    = formWrap ? formWrap.querySelector('form, .wpcf7') : null;

		var targetSelector = <?php echo wp_json_encode( $target ); ?>;
		var submittedMarked = false;
		var rangeLabelRepositionFns = [];
		var rangeLabelResizeObserver = null;

		// Only ever called on a detected successful submission (see the
		// plugin integrations below) - never on a plain close, so closing
		// without submitting is not persisted anywhere and the survey
		// simply comes back on the visitor's next page load.
		function markSubmitted() {
			if (submittedMarked) return;
			submittedMarked = true;
			detachSubmissionWatchers();
			var xhr = new XMLHttpRequest();
			xhr.open('POST', '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send('action=adapt_feedback_survey_submitted&nonce=<?php echo esc_js( $nonce ); ?>');
		}

		function dismiss() {
			document.body.classList.remove('fixed');
			popup.remove();
			window.removeEventListener('resize', reposition);
			window.removeEventListener('scroll', reposition);
			for (var r = 0; r < rangeLabelRepositionFns.length; r++) {
				window.removeEventListener('resize', rangeLabelRepositionFns[r]);
			}
			if (rangeLabelResizeObserver) rangeLabelResizeObserver.disconnect();
			document.removeEventListener('keydown', onKeydown);
			detachSubmissionWatchers();
		}

		function onKeydown(e) {
			if (e.key === 'Escape') dismiss();
		}

		var currentTarget = null;

		// Sizes the highlight box to the live target element - same
		// re-resolve-if-detached and skip-on-zero-rect defensiveness as the
		// welcome popup's own reposition(). Also tries to anchor the dialog
		// directly below the highlight (rather than centered on top of it,
		// which just covers up the thing being highlighted) - falls back to
		// this component's original centered layout if there genuinely
		// isn't enough room below the target to fit it.
		function reposition() {
			if (!currentTarget || !highlight) return;

			if (!document.body.contains(currentTarget)) {
				var stillThere = document.querySelector(targetSelector);
				if (!stillThere) return;
				currentTarget = stillThere;
			}

			var rect = currentTarget.getBoundingClientRect();
			if (rect.width === 0 && rect.height === 0) return;

			var pad = 8;
			highlight.style.top    = (rect.top - pad) + 'px';
			highlight.style.left   = (rect.left - pad) + 'px';
			highlight.style.width  = (rect.width + pad * 2) + 'px';
			highlight.style.height = (rect.height + pad * 2) + 'px';

			if (!dialog) return;

			var margin = 16;
			var gap = 16;
			var top = rect.bottom + pad + gap;
			var spaceBelow = window.innerHeight - top - margin;

			// Only worth anchoring below if there's a reasonable minimum of
			// room to actually show something useful there - otherwise fall
			// back to the centered layout rather than pinning the dialog
			// into a sliver of space at the bottom of the screen.
			if (spaceBelow >= 200) {
				var dRect = dialog.getBoundingClientRect();
				var left = rect.left + (rect.width / 2) - (dRect.width / 2);
				left = Math.max(margin, Math.min(left, window.innerWidth - dRect.width - margin));

				dialog.classList.add('is-anchored');
				dialog.style.top       = top + 'px';
				dialog.style.left      = left + 'px';
				dialog.style.maxHeight = spaceBelow + 'px';
			} else {
				dialog.classList.remove('is-anchored');
				dialog.style.top = dialog.style.left = dialog.style.maxHeight = '';
			}
		}

		var settled = false; // true once we've either shown it or given up

		function showFor(target) {
			if (settled) return;
			settled = true;
			currentTarget = target;

			if (target) {
				target.scrollIntoView({ behavior: 'smooth', block: 'center' });
			} else {
				// No target configured - the highlight box never gets sized,
				// so it provides no dimming on its own. Fall back to a flat
				// dim on the overlay itself, same as this popup's original
				// always-centered behavior.
				overlay.classList.add('feedbackSurvey-overlay--dim');
			}

			// Same settle delay as the welcome popup, so the highlight box
			// measures the target after any smooth-scroll has finished
			// rather than mid-scroll.
			setTimeout(function() {
				popup.style.display = '';
				reposition();
				// Range label geometry (see rangeLabelRepositionFns below)
				// can only be measured once the popup is actually visible -
				// display:none up to this point means getBoundingClientRect()
				// would return an all-zero rect, same reasoning as why
				// reposition() itself waits for this point too.
				for (var r = 0; r < rangeLabelRepositionFns.length; r++) {
					rangeLabelRepositionFns[r]();
				}
				document.body.classList.add('fixed');
				if (closeBtn) closeBtn.focus();
			}, target ? 400 : 0);

			if (target) {
				window.addEventListener('resize', reposition);
				window.addEventListener('scroll', reposition);
			}
			overlay.addEventListener('click', dismiss);
			closeBtn.addEventListener('click', dismiss);
			document.addEventListener('keydown', onKeydown);
		}

		function giveUp() {
			if (settled) return;
			settled = true;
			// Target never showed up on this page load - remove quietly,
			// same as the welcome popup's equivalent giveUp(): nothing is
			// marked submitted or otherwise persisted, so this user still
			// gets the survey on a page where the target actually renders.
			popup.remove();
			for (var r = 0; r < rangeLabelRepositionFns.length; r++) {
				window.removeEventListener('resize', rangeLabelRepositionFns[r]);
			}
			if (rangeLabelResizeObserver) rangeLabelResizeObserver.disconnect();
			detachSubmissionWatchers();
		}

		if (!targetSelector) {
			// No target configured - always show, centered, with no
			// highlight, matching this popup's original behavior.
			showFor(null);
		} else {
			var existingTarget = document.querySelector(targetSelector);
			if (existingTarget) {
				showFor(existingTarget);
			} else {
				// Same reasoning and timeout as the welcome popup: the
				// CustomGPT widget this defaults to can take several
				// seconds to render.
				var targetObserver = new MutationObserver(function() {
					var found = document.querySelector(targetSelector);
					if (found) {
						targetObserver.disconnect();
						showFor(found);
					}
				});
				targetObserver.observe(document.body, { childList: true, subtree: true });
				setTimeout(function() {
					targetObserver.disconnect();
					giveUp();
				}, 45000);
			}
		}

		// Cosmetic: native range inputs don't fill their own track to show
		// progress consistently cross-browser (Chrome/Safari's
		// -webkit-slider-runnable-track has no equivalent of Firefox's
		// ::-moz-range-progress) - paint the "already selected" portion via
		// a JS-computed --feedbackSurveyRangeFill percentage instead (see
		// the matching CSS in _feedback-survey.scss), updated live as the
		// visitor drags. Also shows a label under every step value (not
		// just the two ends), read straight off the input's own
		// min/max/step attributes (whatever the field is configured to in
		// WPForms, etc.) rather than hardcoded - can't be done in pure CSS
		// since browsers don't render ::before/::after on <input> elements
		// at all.
		//
		// The labels are appended to formWrap itself and positioned via
		// measured geometry, deliberately NOT inserted into the slider's
		// own immediate DOM neighborhood - an earlier version wrapped the
		// input in a new parent div, which moved it and broke WPForms' own
		// "Selected Value: X" hint updater: that code reads its hint text
		// off input.nextElementSibling, assumed to always be its own hint
		// div, and threw once that was our new label span instead. Scoped
		// to range inputs inside this popup's own form (e.g. WPForms'
		// Number Slider field) rather than changing range input styling
		// anywhere else on the site.
		if (formWrap) {
			var sliders = formWrap.querySelectorAll('input[type="range"]');
			for (var s = 0; s < sliders.length; s++) {
				(function(slider) {
					var min = slider.getAttribute('min');
					var max = slider.getAttribute('max');

					if (min !== null && max !== null && min !== max) {
						if (getComputedStyle(formWrap).position === 'static') {
							formWrap.style.position = 'relative';
						}

						var minNum = parseFloat(min);
						var maxNum = parseFloat(max);
						var stepAttr = slider.getAttribute('step');
						var stepNum = (stepAttr && stepAttr !== 'any') ? parseFloat(stepAttr) : 1;
						if (!stepNum || isNaN(stepNum) || stepNum <= 0) stepNum = 1;

						// One label per step value (1, 2, 3, 4, 5 - not just
						// the two ends) so the visitor can see where the
						// thumb sits relative to every option, not just how
						// far it is from the extremes. Capped so a finely-
						// stepped range (e.g. step="0.1") can't cram dozens
						// of overlapping labels under the track - falls back
						// to just the two end labels past that point, same
						// as this used to always do.
						var values = [minNum, maxNum];
						if (!isNaN(minNum) && !isNaN(maxNum) && maxNum > minNum) {
							var maxLabels = 11;
							var stepCount = Math.round((maxNum - minNum) / stepNum);
							if (stepCount > 1 && stepCount <= maxLabels - 1) {
								// Built by index (minNum + i*stepNum), not by
								// accumulating stepNum in a loop condition -
								// floating-point drift there (e.g. repeated
								// += 0.1) can land just under maxNum on the
								// final lap and emit a duplicate end label.
								// First/last are the input's own min/max
								// attribute values verbatim either way, so a
								// non-evenly-divisible step (e.g. 0-1 by 0.3)
								// still ends exactly on the real max rather
								// than the last grid point short of it.
								values = [minNum];
								for (var i = 1; i < stepCount; i++) {
									values.push(Math.round((minNum + i * stepNum) * 1000) / 1000);
								}
								values.push(maxNum);
							}
						}

						var rangeLabels = [];
						for (var vi = 0; vi < values.length; vi++) {
							var modifier = vi === 0
								? 'feedbackSurvey-rangeLabel--min'
								: (vi === values.length - 1 ? 'feedbackSurvey-rangeLabel--max' : 'feedbackSurvey-rangeLabel--mid');
							var labelEl = document.createElement('span');
							labelEl.className = 'feedbackSurvey-rangeLabel ' + modifier;
							labelEl.textContent = values[vi];
							formWrap.appendChild(labelEl);
							rangeLabels.push({ el: labelEl, value: values[vi] });
						}

						var positionLabels = function() {
							var sliderRect = slider.getBoundingClientRect();
							var wrapRect = formWrap.getBoundingClientRect();
							var top = sliderRect.bottom - wrapRect.top + 4;
							var span = maxNum - minNum;
							for (var pi = 0; pi < rangeLabels.length; pi++) {
								var fraction = span > 0 ? (rangeLabels[pi].value - minNum) / span : 0;
								rangeLabels[pi].el.style.top  = top + 'px';
								rangeLabels[pi].el.style.left = (sliderRect.left - wrapRect.left + sliderRect.width * fraction) + 'px';
							}
						};
						// Not called immediately here - the popup is still
						// display:none at this point (shown later, from
						// showFor()'s setTimeout, which is what actually
						// calls this the first time), so
						// getBoundingClientRect() would only ever measure
						// an all-zero rect and misplace every label.
						window.addEventListener('resize', positionLabels);
						rangeLabelRepositionFns.push(positionLabels);
					}

					function paintFill() {
						var minVal = parseFloat(slider.min) || 0;
						var maxVal = parseFloat(slider.max) || 100;
						var val = parseFloat(slider.value);
						if (isNaN(val)) val = minVal;
						var pct = maxVal > minVal ? ((val - minVal) / (maxVal - minVal)) * 100 : 0;
						slider.style.setProperty('--feedbackSurveyRangeFill', pct + '%');
					}
					slider.addEventListener('input', paintFill);
					paintFill();
				})(sliders[s]);
			}

			// positionLabels() above is only re-run on window resize, but the
			// very first run (triggered by showFor()'s setTimeout, ~400ms
			// after the popup unhides) can land before formWrap has actually
			// finished settling - e.g. a form field the plugin hides via its
			// own conditional-logic JS is still visibly taking up space at
			// that point, pushing formWrap taller/shorter than its final
			// layout. That shifts formWrap's own getBoundingClientRect(),
			// which throws off the top/left math above, and nothing was
			// re-measuring it afterward since no window resize necessarily
			// follows. Observing formWrap directly catches that (and any
			// other later reflow inside it - webfont swap, etc.) regardless
			// of whether the viewport itself ever resizes.
			if (formWrap && rangeLabelRepositionFns.length && window.ResizeObserver) {
				rangeLabelResizeObserver = new ResizeObserver(function() {
					for (var r = 0; r < rangeLabelRepositionFns.length; r++) {
						rangeLabelRepositionFns[r]();
					}
				});
				rangeLabelResizeObserver.observe(formWrap);
			}
		}

		/**
		 * Submission detection: every form plugin signals a successful AJAX
		 * submit its own way, so rather than only reacting to whichever one
		 * happens to be configured, wire up named integrations for the
		 * common ones plus a plugin-agnostic DOM fallback for everything
		 * else - "expect and be prepared" rather than only handling CF7 and
		 * leaving every other plugin to never mark the survey submitted.
		 */
		var detachFns = [];
		function detachSubmissionWatchers() {
			for (var i = 0; i < detachFns.length; i++) detachFns[i]();
			detachFns = [];
		}

		var recognizedPlugin = false;

		// Contact Form 7: dispatches this native DOM event directly on the
		// form element once an AJAX submission succeeds - no jQuery
		// dependency for this one.
		if (formEl && formEl.classList && formEl.classList.contains('wpcf7')) {
			recognizedPlugin = true;
			var cf7Handler = function() { markSubmitted(); };
			formEl.addEventListener('wpcf7mailsent', cf7Handler, false);
			detachFns.push(function() { formEl.removeEventListener('wpcf7mailsent', cf7Handler, false); });
		}

		// Gravity Forms: fires this jQuery event on document once the AJAX
		// confirmation has loaded, passing the numeric form ID - read off
		// the rendered form's own id="gform_{ID}" attribute so this only
		// reacts to this specific form, not some other GF form on the page.
		var gfMatch = formEl && formEl.id && formEl.id.match(/^gform_(\d+)$/);
		if (window.jQuery && gfMatch) {
			recognizedPlugin = true;
			var gfFormId = gfMatch[1];
			var gfHandler = function(event, formId) {
				if (String(formId) === gfFormId) markSubmitted();
			};
			jQuery(document).on('gform_confirmation_loaded', gfHandler);
			detachFns.push(function() { jQuery(document).off('gform_confirmation_loaded', gfHandler); });
		}

		// WPForms: fires this jQuery event on document on AJAX submit
		// success, with the form ID in the response payload - same
		// per-form scoping idea as Gravity Forms above, read off
		// id="wpforms-form-{ID}".
		var wpformsMatch = formEl && formEl.id && formEl.id.match(/^wpforms-form-(\d+)$/);
		if (window.jQuery && wpformsMatch) {
			recognizedPlugin = true;
			var wpformsFormId = wpformsMatch[1];
			var wpformsHandler = function(event, response) {
				var respFormId = response && response.data ? String(response.data.form_id) : null;
				if (!respFormId || respFormId === wpformsFormId) markSubmitted();
			};
			jQuery(document).on('wpformsAjaxSubmitSuccess', wpformsHandler);
			detachFns.push(function() { jQuery(document).off('wpformsAjaxSubmitSuccess', wpformsHandler); });
		}

		// HubSpot forms: rendered inside a cross-origin iframe, so the only
		// way to hear about a submission is the postMessage its embed
		// script sends to the parent window - scoped to this popup's own
		// iframe(s) by checking the message's source window, since other
		// HubSpot forms could exist elsewhere on the same page.
		if (formWrap && formWrap.querySelector('.hbspt-form, iframe[id^="hs-form-iframe"]')) {
			recognizedPlugin = true;
			var hsHandler = function(event) {
				if (!event.data || event.data.type !== 'hsFormCallback' || event.data.eventName !== 'onFormSubmitted') return;
				var iframes = formWrap.querySelectorAll('iframe');
				for (var i = 0; i < iframes.length; i++) {
					if (event.source === iframes[i].contentWindow) {
						markSubmitted();
						return;
					}
				}
			};
			window.addEventListener('message', hsHandler, false);
			detachFns.push(function() { window.removeEventListener('message', hsHandler, false); });
		}

		// Anything else: no named integration, so fall back to watching the
		// DOM for the tell-tale signs most plugins leave behind on success -
		// either the original form disappearing (swapped for a confirmation
		// message, e.g. Formidable, Ninja Forms) or a newly inserted element
		// whose class/id reads like a success message. Skips wording that
		// also shows up in failure states (error/invalid/fail/-ng) so a
		// validation error isn't mistaken for a successful submission. Only
		// runs when none of the named integrations above matched, so a
		// recognized plugin's own precise event is always what decides.
		if (formWrap && !recognizedPlugin) {
			var successPattern = /\b(success|thank\s*you|thanks|confirmation|complete)\b/i;
			var failurePattern = /error|invalid|fail|denied|-ng\b/i;

			var observer = new MutationObserver(function(mutations) {
				if (formEl && !formWrap.contains(formEl)) {
					markSubmitted();
					return;
				}
				for (var m = 0; m < mutations.length; m++) {
					var added = mutations[m].addedNodes;
					for (var n = 0; n < added.length; n++) {
						var node = added[n];
						if (node.nodeType !== 1) continue;
						var haystack = (node.className || '') + ' ' + (node.id || '');
						if (successPattern.test(haystack) && !failurePattern.test(haystack)) {
							markSubmitted();
							return;
						}
					}
				}
			});
			observer.observe(formWrap, { childList: true, subtree: true });
			detachFns.push(function() { observer.disconnect(); });
		}
	})();
	</script>
	<?php
} );

/**
 * AJAX: mark the survey as permanently submitted for this user, so it stops
 * showing. Only ever called by one of the submission-detection integrations
 * above (never by a plain close) - logged-in only, same reasoning as the
 * welcome popup's equivalent endpoint - guests never see this in the first
 * place.
 */
add_action( 'wp_ajax_adapt_feedback_survey_submitted', function() {
	check_ajax_referer( 'adapt_feedback_survey', 'nonce' );
	update_user_meta( get_current_user_id(), 'adapt_feedback_survey_submitted', 1 );
	wp_send_json_success();
} );
