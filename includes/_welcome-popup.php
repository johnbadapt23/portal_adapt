<?php
/**
 * Welcome popup - a spotlight/tour-style tooltip that highlights a specific
 * element on the page (by default, the homepage's ADAPT Intelligence /
 * CustomGPT box) and points an explanatory callout at it, shown once per
 * logged-in user. Dismissing it (X, "Got it", clicking the dimmed overlay,
 * or Escape) permanently marks it seen for that user via user meta - it
 * never shows again for them, even if the admin later edits the text.
 *
 * Content (and the target element) is editable from wp-admin > Options (the
 * same ACF options page the rest of the theme's global settings already
 * live on) without touching code - see the field group registered below.
 */

/**
 * WP Rocket's Delay JS Execution defers <script> tags (inline ones included)
 * until the first user interaction (click/scroll/keydown/touch) - great for
 * performance scores, but it means this popup's own inline script wouldn't
 * run until the visitor interacted with the page, well after "as soon as
 * possible after login" stops being true. Same rocket_delay_js_exclusions
 * filter already used in functions.php for the homepage's core scripts,
 * matched against a string unique to this inline script (its container id)
 * so only this one is exempted, sitewide - the popup can appear on any page
 * a logged-in user lands on first, not just the homepage.
 */
add_filter( 'rocket_delay_js_exclusions', function( $exclusions ) {
	$exclusions[] = 'adapt-welcome-popup';
	return $exclusions;
} );

/**
 * Register the "Welcome Popup" field group on the existing ACF options page.
 * Local field group (defined in code, not exported/imported via the ACF UI),
 * same pattern as acf_add_options_page() itself in includes/_setup.php - it
 * still shows up as a normal editable box in wp-admin, nothing extra to set
 * up there.
 */
add_action( 'acf/init', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$shown_if_enabled = array(
		array(
			array(
				'field'    => 'field_adapt_welcome_popup_enabled',
				'operator' => '==',
				'value'    => '1',
			),
		),
	);

	acf_add_local_field_group( array(
		'key'    => 'group_adapt_welcome_popup',
		'title'  => 'Welcome Popup',
		'fields' => array(
			array(
				'key'           => 'field_adapt_welcome_popup_enabled',
				'label'         => 'Enabled',
				'name'          => 'welcome_popup_enabled',
				'type'          => 'true_false',
				'instructions'  => 'Show a one-time spotlight tooltip pointing at a specific element, to logged-in users who have not seen it yet.',
				'default_value' => 0,
				'ui'            => 1,
			),
			array(
				'key'               => 'field_adapt_welcome_popup_target_selector',
				'label'             => 'Target element (CSS selector)',
				'name'              => 'welcome_popup_target_selector',
				'type'              => 'text',
				'instructions'      => 'The element to highlight and point the tooltip at. Defaults to the homepage AI Assistant box - only change this if you want to spotlight something else instead. Use a selector that matches the visible card itself, not an outer wrapper - #customgpt-chat-1 is the CustomGPT widget\'s full container and can be much taller than the visible box, which makes the spotlight cover far more than intended. If the element is not found on a given page (e.g. it has not finished loading, or this page does not have it), the popup silently does not show and the user is not counted as having seen it yet - they will still get it on a page where the element does appear.',
				'default_value'     => '.cgpt-hero-card',
				'conditional_logic' => $shown_if_enabled,
			),
			array(
				'key'               => 'field_adapt_welcome_popup_heading',
				'label'             => 'Heading',
				'name'              => 'welcome_popup_heading',
				'type'              => 'text',
				'conditional_logic' => $shown_if_enabled,
			),
			array(
				'key'               => 'field_adapt_welcome_popup_message',
				'label'             => 'Message',
				'name'              => 'welcome_popup_message',
				'type'              => 'textarea',
				'rows'              => 4,
				'conditional_logic' => $shown_if_enabled,
			),
			array(
				'key'               => 'field_adapt_welcome_popup_force_redisplay',
				'label'             => 'Show again to everyone',
				'name'              => 'welcome_popup_force_redisplay',
				'type'              => 'true_false',
				'instructions'      => 'Normally this popup only shows once per user, ever - dismissing it (X, "Got it", clicking outside, or Escape) marks it seen for good. Turn this ON to bring it back for everyone who already dismissed it, without losing that history: their old dismissal stays recorded, it is just ignored while this is ON. Turn it back OFF once you are done and the once-per-user behavior resumes from wherever each user\'s dismissal record already stands (including any new dismissals made while this was ON).',
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
 * Whether the current request should even attempt to render the popup:
 * logged in, feature enabled, there's actually something configured to
 * show, a target selector is set, and this user hasn't dismissed it before.
 * Doesn't (can't) check whether the target element actually exists on this
 * page - that's a runtime DOM question, handled in JS below.
 *
 * Administrators are exempt from the "already dismissed" check specifically
 * (debugging/QA convenience, requested explicitly) - the popup keeps showing
 * for them every qualifying page load regardless of past dismissals. Their
 * dismiss clicks still fire the same AJAX call and set the seen meta as
 * normal, it's just ignored for this role - so the moment an admin account
 * ever gets demoted from administrator, the last dismissal (if any) applies
 * immediately rather than needing a separate cleanup step.
 *
 * "Show again to everyone" (welcome_popup_force_redisplay) does the same
 * thing but for every logged-in user, not just admins - an admin-controlled
 * override for bringing the popup back for people who already dismissed it,
 * without bulk-deleting their seen user meta. Non-destructive: existing
 * dismissal records are left alone and simply ignored while this is ON, so
 * turning it back OFF resumes the once-per-user behavior exactly where each
 * user's own dismissal history already stands.
 */
function adapt_should_show_welcome_popup() {
	if ( ! is_user_logged_in() ) {
		return false;
	}
	if ( ! get_field( 'welcome_popup_enabled', 'option' ) ) {
		return false;
	}
	$bypass_seen_check = get_field( 'welcome_popup_force_redisplay', 'option' ) || current_user_can( 'administrator' );
	if ( ! $bypass_seen_check && get_user_meta( get_current_user_id(), 'adapt_welcome_popup_seen', true ) ) {
		return false;
	}
	$target  = get_field( 'welcome_popup_target_selector', 'option' );
	$heading = get_field( 'welcome_popup_heading', 'option' );
	$message = get_field( 'welcome_popup_message', 'option' );
	return ( $target && ( $heading || $message ) );
}

/**
 * Render the popup markup + its own small inline script in the footer.
 * wp_footer (not a template edit) so this feature stays self-contained and
 * works on any page a logged-in user might land on first, not just the
 * homepage - the JS below is what actually decides whether there's
 * something on THIS page to point at.
 */
add_action( 'wp_footer', function() {
	if ( is_admin() || ! adapt_should_show_welcome_popup() ) {
		return;
	}

	$target  = get_field( 'welcome_popup_target_selector', 'option' );
	$heading = get_field( 'welcome_popup_heading', 'option' );
	$message = get_field( 'welcome_popup_message', 'option' );
	$nonce   = wp_create_nonce( 'adapt_welcome_popup' );

	?>
	<div id="adapt-welcome-popup" class="welcomeSpotlight" style="display:none;" role="dialog" aria-modal="true" <?php echo $heading ? 'aria-labelledby="adapt-welcome-popup-heading"' : ''; ?>>
		<div class="welcomeSpotlight-overlay"></div>
		<div class="welcomeSpotlight-highlight"></div>
		<div class="welcomeSpotlight-tooltip">
			<span class="welcomeSpotlight-arrow"></span>
			<button type="button" class="welcomeSpotlight-close" aria-label="Close">&times;</button>
			<?php if ( $heading ) : ?>
				<h2 id="adapt-welcome-popup-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $message ) : ?>
				<p><?php echo nl2br( esc_html( $message ) ); ?></p>
			<?php endif; ?>
			<button type="button" class="welcomeSpotlight-ok std-button red-button">Got it</button>
		</div>
	</div>
	<script>
	(function() {
		var popup = document.getElementById('adapt-welcome-popup');
		if (!popup) return;

		var targetSelector = <?php echo wp_json_encode( $target ); ?>;
		var overlay   = popup.querySelector('.welcomeSpotlight-overlay');
		var highlight = popup.querySelector('.welcomeSpotlight-highlight');
		var tooltip   = popup.querySelector('.welcomeSpotlight-tooltip');
		var arrow     = popup.querySelector('.welcomeSpotlight-arrow');
		var closeBtn  = popup.querySelector('.welcomeSpotlight-close');
		var okBtn     = popup.querySelector('.welcomeSpotlight-ok');

		var settled = false; // true once we've either shown it or given up

		function markSeen() {
			var xhr = new XMLHttpRequest();
			xhr.open('POST', '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send('action=adapt_dismiss_welcome_popup&nonce=<?php echo esc_js( $nonce ); ?>');
		}

		function dismiss() {
			document.body.classList.remove('fixed');
			popup.remove();
			window.removeEventListener('resize', reposition);
			window.removeEventListener('scroll', reposition);
			document.removeEventListener('keydown', onKeydown);
			markSeen();
		}

		function onKeydown(e) {
			if (e.key === 'Escape') dismiss();
		}

		var currentTarget = null;

		function reposition() {
			if (!currentTarget) return;

			// Some widgets (e.g. CustomGPT) re-render their own DOM on
			// resize rather than just resizing the existing node - that can
			// leave currentTarget pointing at a now-detached element, whose
			// getBoundingClientRect() comes back all zeros and collapses
			// the highlight down to just its own padding (0 + pad*2 =
			// 16px). Re-resolve against the live selector whenever the
			// cached reference has fallen out of the document.
			if (!document.body.contains(currentTarget)) {
				var stillThere = document.querySelector(targetSelector);
				if (!stillThere) return;
				currentTarget = stillThere;
			}

			var rect = currentTarget.getBoundingClientRect();

			// A momentarily 0-size rect (mid-reflow, or the widget briefly
			// hiding itself while it re-renders) would otherwise still
			// collapse the highlight the same way - skip this update and
			// leave the highlight where it last was instead.
			if (rect.width === 0 && rect.height === 0) return;

			var pad = 8;

			highlight.style.top    = (rect.top - pad) + 'px';
			highlight.style.left   = (rect.left - pad) + 'px';
			highlight.style.width  = (rect.width + pad * 2) + 'px';
			highlight.style.height = (rect.height + pad * 2) + 'px';

			// Measure the tooltip's natural size first (it's already visible
			// at this point, just not positioned), then decide above vs.
			// below based on available space, falling back to whichever side
			// has more room if neither fits comfortably.
			var tRect = tooltip.getBoundingClientRect();
			var spaceBelow = window.innerHeight - rect.bottom;
			var spaceAbove = rect.top;
			var gap = 16;
			var placeBelow = spaceBelow >= (tRect.height + gap + pad) || spaceBelow >= spaceAbove;

			var top = placeBelow
				? (rect.bottom + pad + gap)
				: (rect.top - pad - gap - tRect.height);

			var left = rect.left + (rect.width / 2) - (tRect.width / 2);
			left = Math.max(16, Math.min(left, window.innerWidth - tRect.width - 16));

			tooltip.style.top  = top + 'px';
			tooltip.style.left = left + 'px';
			tooltip.classList.toggle('is-below', placeBelow);
			tooltip.classList.toggle('is-above', !placeBelow);

			// Arrow points at the horizontal center of the target, clamped to
			// stay within the tooltip's own width so it doesn't overhang.
			var arrowLeft = (rect.left + rect.width / 2) - left;
			arrowLeft = Math.max(20, Math.min(arrowLeft, tRect.width - 20));
			arrow.style.left = arrowLeft + 'px';
		}

		function showFor(target) {
			if (settled) return;
			settled = true;
			currentTarget = target;

			target.scrollIntoView({ behavior: 'smooth', block: 'center' });

			// Let the scroll settle before measuring/positioning - matches
			// the smooth-scroll duration closely enough for this purpose.
			setTimeout(function() {
				popup.style.display = '';
				reposition();
				document.body.classList.add('fixed');
				if (closeBtn) closeBtn.focus();
			}, 400);

			window.addEventListener('resize', reposition);
			window.addEventListener('scroll', reposition);
			overlay.addEventListener('click', dismiss);
			if (okBtn) okBtn.addEventListener('click', dismiss);
			if (closeBtn) closeBtn.addEventListener('click', dismiss);
			document.addEventListener('keydown', onKeydown);
		}

		function giveUp() {
			if (settled) return;
			settled = true;
			// Target never showed up on this page load - remove quietly and
			// do NOT mark as seen, so this user still gets it on a page
			// where the target actually renders.
			popup.remove();
		}

		var existing = document.querySelector(targetSelector);
		if (existing) {
			showFor(existing);
		} else {
			// Wait for it - the CustomGPT widget this defaults to has been
			// measured taking several seconds to render (chained
			// admin-ajax.php calls, see footer.php's labelCgptInputs
			// comment for the same timing story). Same behavior for
			// everyone, including admins/agent testers - the popup should
			// only ever show when the target selector is genuinely present
			// on this page, never as a no-target fallback.
			var observer = new MutationObserver(function() {
				var found = document.querySelector(targetSelector);
				if (found) {
					observer.disconnect();
					showFor(found);
				}
			});
			observer.observe(document.body, { childList: true, subtree: true });
			setTimeout(function() {
				observer.disconnect();
				giveUp();
			}, 45000);
		}
	})();
	</script>
	<?php
} );

/**
 * AJAX: mark the popup as permanently seen for this user. Logged-in only
 * (wp_ajax_, no wp_ajax_nopriv_ counterpart - guests never see the popup in
 * the first place, so there's nothing for a logged-out request to dismiss).
 */
add_action( 'wp_ajax_adapt_dismiss_welcome_popup', function() {
	check_ajax_referer( 'adapt_welcome_popup', 'nonce' );
	update_user_meta( get_current_user_id(), 'adapt_welcome_popup_seen', 1 );
	wp_send_json_success();
} );
