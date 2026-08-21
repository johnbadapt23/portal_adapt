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
				<?php echo adapt_get_feedback_survey_form_html(); ?>
			</div>
		</div>
	</div>
	<script>
	(function() {
		var popup = document.getElementById('adapt-feedback-survey');
		if (!popup) return;

		var overlay  = popup.querySelector('.feedbackSurvey-overlay');
		var closeBtn = popup.querySelector('.feedbackSurvey-close');
		var formWrap = popup.querySelector('.feedbackSurvey-form');
		var formEl   = formWrap ? formWrap.querySelector('form, .wpcf7') : null;

		var submittedMarked = false;

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
			document.removeEventListener('keydown', onKeydown);
			detachSubmissionWatchers();
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
