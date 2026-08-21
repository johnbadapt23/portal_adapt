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
 * they've actually submitted the form. If the configured shortcode happens
 * to be Contact Form 7's, submission is detected via its vanilla-JS
 * wpcf7mailsent success event (see below) - other form plugins don't fire
 * an equivalent event, so for those the survey has no way to know it was
 * submitted and keeps reappearing until an admin disables it.
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

	$shown_if_enabled = array(
		array(
			array(
				'field'    => 'field_adapt_feedback_survey_enabled',
				'operator' => '==',
				'value'    => '1',
			),
		),
	);

	acf_add_local_field_group( array(
		'key'    => 'group_adapt_feedback_survey',
		'title'  => 'Feedback Survey',
		'fields' => array(
			array(
				'key'           => 'field_adapt_feedback_survey_enabled',
				'label'         => 'Enabled',
				'name'          => 'feedback_survey_enabled',
				'type'          => 'true_false',
				'instructions'  => 'Show a one-time feedback survey popup to logged-in users, starting from the date below.',
				'default_value' => 0,
				'ui'            => 1,
			),
			array(
				'key'               => 'field_adapt_feedback_survey_start_date',
				'label'             => 'Start showing from',
				'name'              => 'feedback_survey_start_date',
				'type'              => 'date_picker',
				'instructions'      => 'The popup will not appear before this date, even if enabled. Defaults to 2 weeks out from when this feature was built.',
				'display_format'    => 'd/m/Y',
				'return_format'     => 'Ymd',
				'first_day'         => 1,
				'default_value'     => '20260827',
				'conditional_logic' => $shown_if_enabled,
			),
			array(
				'key'               => 'field_adapt_feedback_survey_shortcode',
				'label'             => 'Form shortcode',
				'name'              => 'feedback_survey_shortcode',
				'type'              => 'text',
				'instructions'      => 'The shortcode for the form to show - works with any form plugin\'s shortcode, e.g. [contact-form-7 id="123" title="Feedback"] or [gravityform id="4"]. Build/edit the actual survey questions in that plugin, not here.',
				'conditional_logic' => $shown_if_enabled,
			),
			array(
				'key'               => 'field_adapt_feedback_survey_heading',
				'label'             => 'Heading',
				'name'              => 'feedback_survey_heading',
				'type'              => 'text',
				'default_value'     => "We'd love your feedback",
				'conditional_logic' => $shown_if_enabled,
			),
			array(
				'key'               => 'field_adapt_feedback_survey_intro',
				'label'             => 'Intro text',
				'name'              => 'feedback_survey_intro',
				'type'              => 'textarea',
				'rows'              => 3,
				'instructions'      => 'Optional - shown above the form itself.',
				'conditional_logic' => $shown_if_enabled,
			),
			array(
				'key'               => 'field_adapt_feedback_survey_force_redisplay',
				'label'             => 'Show again to everyone',
				'name'              => 'feedback_survey_force_redisplay',
				'type'              => 'true_false',
				'instructions'      => 'Same idea as the welcome popup\'s equivalent toggle - turn this ON to bring the survey back for everyone who already submitted it, without losing that history (their submission record is kept, just ignored while this is ON). Turn it back OFF to resume once-per-user behavior. Note simply closing the survey without submitting never suppresses it long-term either way - it always comes back on the next page load until submitted.',
				'default_value'     => 0,
				'ui'                => 1,
				'conditional_logic' => $shown_if_enabled,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'acf-options',
				),
			),
		),
	) );
} );

/**
 * Whether the current request should even attempt to render the survey:
 * logged in, feature enabled, a form shortcode is configured, today is
 * on/after the configured start date, this user has already dismissed the
 * welcome popup (i.e. actually encountered the AI Assistant box, not just
 * logged in), and this user hasn't already submitted the survey itself
 * (unless exempted - see below). Note there's no "already dismissed the
 * survey" check here on purpose - closing it without submitting is not
 * persisted anywhere, so it's simply asked again on the next page load.
 *
 * Administrators always see it regardless of the welcome-popup-seen
 * requirement or a past submission (debugging/QA convenience, same
 * exemption already used for the welcome popup). The "Show again to
 * everyone" field does the same for the survey's own submitted check, for
 * every logged-in user - an admin-controlled, non-destructive override for
 * bringing the survey back without bulk-deleting submitted user meta.
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
	$start_date = get_field( 'feedback_survey_start_date', 'option' ); // Ymd string.
	if ( $start_date && current_time( 'Ymd' ) < $start_date ) {
		return false;
	}
	// Only ask people who actually closed the welcome popup - i.e. actually
	// encountered the AI Assistant box it points at - not everyone who is
	// merely logged in. Uses the same adapt_welcome_popup_seen meta the
	// welcome popup already sets on dismissal (see includes/_welcome-popup.php),
	// rather than a second flag, so this stays accurate even if that popup
	// gets disabled or re-enabled later - it directly reflects what actually
	// happened, not a separate tracked copy of it.
	if ( ! current_user_can( 'administrator' ) && ! get_user_meta( get_current_user_id(), 'adapt_welcome_popup_seen', true ) ) {
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

	$shortcode = get_field( 'feedback_survey_shortcode', 'option' );
	$heading   = get_field( 'feedback_survey_heading', 'option' );
	$intro     = get_field( 'feedback_survey_intro', 'option' );
	$nonce     = wp_create_nonce( 'adapt_feedback_survey' );
	?>
	<div id="adapt-feedback-survey" class="feedbackSurvey" style="display:none;" role="dialog" aria-modal="true" <?php echo $heading ? 'aria-labelledby="adapt-feedback-survey-heading"' : ''; ?>>
		<div class="feedbackSurvey-overlay"></div>
		<div class="feedbackSurvey-dialog">
			<button type="button" class="feedbackSurvey-close" aria-label="Close">&times;</button>
			<?php if ( $heading ) : ?>
				<h2 id="adapt-feedback-survey-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $intro ) : ?>
				<p><?php echo nl2br( esc_html( $intro ) ); ?></p>
			<?php endif; ?>
			<div class="feedbackSurvey-form">
				<?php echo do_shortcode( $shortcode ); ?>
			</div>
		</div>
	</div>
	<script>
	(function() {
		var popup = document.getElementById('adapt-feedback-survey');
		if (!popup) return;

		var overlay  = popup.querySelector('.feedbackSurvey-overlay');
		var closeBtn = popup.querySelector('.feedbackSurvey-close');
		var formEl   = popup.querySelector('.feedbackSurvey-form form, .feedbackSurvey-form .wpcf7');

		var submittedMarked = false;

		// Only ever called on an actual successful submission (see the
		// wpcf7mailsent listener below) - never on a plain close, so
		// closing without submitting is not persisted anywhere and the
		// survey simply comes back on the visitor's next page load.
		function markSubmitted() {
			if (submittedMarked) return;
			submittedMarked = true;
			var xhr = new XMLHttpRequest();
			xhr.open('POST', '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send('action=adapt_feedback_survey_submitted&nonce=<?php echo esc_js( $nonce ); ?>');
		}

		function dismiss() {
			document.body.classList.remove('fixed');
			popup.remove();
			document.removeEventListener('keydown', onKeydown);
		}

		function onKeydown(e) {
			if (e.key === 'Escape') dismiss();
		}

		popup.style.display = '';
		document.body.classList.add('fixed');
		overlay.addEventListener('click', dismiss);
		closeBtn.addEventListener('click', dismiss);
		document.addEventListener('keydown', onKeydown);
		if (closeBtn) closeBtn.focus();

		// If the configured shortcode happens to be a Contact Form 7 form,
		// it dispatches this native event on the form element once an AJAX
		// submission succeeds - plain DOM event, no jQuery/load-order
		// dependency. Marks it submitted right away so a refresh won't show
		// it again, but leaves the popup open so the confirmation message
		// stays visible until the visitor closes it themselves. Other form
		// plugins don't fire this event, so for those there's no way to
		// detect a successful submission and the survey keeps reappearing
		// on every page load until an admin disables it.
		if (formEl) {
			formEl.addEventListener('wpcf7mailsent', function() {
				markSubmitted();
			}, false);
		}
	})();
	</script>
	<?php
} );

/**
 * AJAX: mark the survey as permanently submitted for this user, so it stops
 * showing. Only ever called by the wpcf7mailsent success handler above, not
 * by a plain close - logged-in only, same reasoning as the welcome popup's
 * equivalent endpoint - guests never see this in the first place.
 */
add_action( 'wp_ajax_adapt_feedback_survey_submitted', function() {
	check_ajax_referer( 'adapt_feedback_survey', 'nonce' );
	update_user_meta( get_current_user_id(), 'adapt_feedback_survey_submitted', 1 );
	wp_send_json_success();
} );
