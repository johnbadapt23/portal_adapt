<?php
/**
 * Feedback survey - a Contact Form 7 form (built and edited entirely in
 * wp-admin > Contact > Contact Forms, whatever questions are wanted - not
 * hardcoded here) shown once per logged-in user inside a popup styled to
 * match the welcome spotlight's centered dialog, starting from a
 * configurable date. Chosen over Gravity Forms since there's no GF license
 * available - CF7 is free and already fires a plain vanilla-JS event on
 * successful submission (wpcf7mailsent), so this needs no jQuery timing
 * dependency at all.
 *
 * Entirely self-contained, same pattern as includes/_welcome-popup.php: its
 * own ACF field group on the existing options page, its own once-per-user
 * "seen" user meta flag, its own nonce-verified AJAX dismiss endpoint -
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
				'key'               => 'field_adapt_feedback_survey_form_id',
				'label'             => 'Contact Form 7 form ID',
				'name'              => 'feedback_survey_form_id',
				'type'              => 'number',
				'instructions'      => 'The numeric ID of the Contact Form 7 form to show (Contact > Contact Forms - the ID is shown next to each form\'s title, and in its shortcode). Build/edit the actual survey questions there, not here.',
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
				'instructions'      => 'Same idea as the welcome popup\'s equivalent toggle - turn this ON to bring the survey back for everyone who already closed it, without losing that history (their dismissal record is kept, just ignored while this is ON). Turn it back OFF to resume once-per-user behavior.',
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
 * logged in, feature enabled, Contact Form 7 is actually active, a form ID
 * is configured, today is on/after the configured start date, and this user
 * hasn't dismissed it before (unless exempted - see below).
 *
 * Administrators always see it regardless of past dismissals (debugging/QA
 * convenience, same exemption already used for the welcome popup). The
 * "Show again to everyone" field does the same for every logged-in user -
 * an admin-controlled, non-destructive override for bringing the survey
 * back without bulk-deleting seen user meta.
 */
function adapt_should_show_feedback_survey() {
	if ( ! is_user_logged_in() ) {
		return false;
	}
	if ( ! get_field( 'feedback_survey_enabled', 'option' ) ) {
		return false;
	}
	if ( ! class_exists( 'WPCF7' ) ) {
		return false; // Contact Form 7 isn't active - nothing to embed.
	}
	$form_id = (int) get_field( 'feedback_survey_form_id', 'option' );
	if ( ! $form_id ) {
		return false;
	}
	$start_date = get_field( 'feedback_survey_start_date', 'option' ); // Ymd string.
	if ( $start_date && current_time( 'Ymd' ) < $start_date ) {
		return false;
	}
	$bypass_seen_check = get_field( 'feedback_survey_force_redisplay', 'option' ) || current_user_can( 'administrator' );
	if ( ! $bypass_seen_check && get_user_meta( get_current_user_id(), 'adapt_feedback_survey_seen', true ) ) {
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

	$form_id = (int) get_field( 'feedback_survey_form_id', 'option' );
	$heading = get_field( 'feedback_survey_heading', 'option' );
	$intro   = get_field( 'feedback_survey_intro', 'option' );
	$nonce   = wp_create_nonce( 'adapt_feedback_survey' );
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
				<?php echo do_shortcode( '[contact-form-7 id="' . $form_id . '"]' ); ?>
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

		var seenMarked = false;

		function markSeen() {
			if (seenMarked) return;
			seenMarked = true;
			var xhr = new XMLHttpRequest();
			xhr.open('POST', '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send('action=adapt_dismiss_feedback_survey&nonce=<?php echo esc_js( $nonce ); ?>');
		}

		function dismiss() {
			document.body.classList.remove('fixed');
			popup.remove();
			document.removeEventListener('keydown', onKeydown);
			markSeen();
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

		// Contact Form 7 dispatches this native event on the form element
		// once an AJAX submission succeeds and its confirmation message is
		// shown - plain DOM event, no jQuery/load-order dependency. Marks
		// it seen right away so a refresh won't show it again, but leaves
		// the popup open so the confirmation message stays visible until
		// the visitor closes it themselves.
		if (formEl) {
			formEl.addEventListener('wpcf7mailsent', function(event) {
				if (!event.detail || Number(event.detail.contactFormId) === <?php echo (int) $form_id; ?>) {
					markSeen();
				}
			}, false);
		}
	})();
	</script>
	<?php
} );

/**
 * AJAX: mark the survey as permanently seen for this user. Logged-in only,
 * same reasoning as the welcome popup's equivalent endpoint - guests never
 * see this in the first place.
 */
add_action( 'wp_ajax_adapt_dismiss_feedback_survey', function() {
	check_ajax_referer( 'adapt_feedback_survey', 'nonce' );
	update_user_meta( get_current_user_id(), 'adapt_feedback_survey_seen', 1 );
	wp_send_json_success();
} );
