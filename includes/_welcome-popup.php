<?php
/**
 * Welcome popup - shown once per logged-in user, on their first page view
 * after this feature is enabled (or after their account is created, if
 * enabled from the start). Dismissing it (X, "Got it", overlay click, or
 * Escape) permanently marks it seen for that user via user meta - it never
 * shows again for them, even if the admin later edits the heading/message.
 *
 * Content is editable from wp-admin > Options (the same ACF options page
 * the rest of the theme's global settings already live on) without touching
 * code - see the field group registered below.
 */

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

	acf_add_local_field_group( array(
		'key'      => 'group_adapt_welcome_popup',
		'title'    => 'Welcome Popup',
		'fields'   => array(
			array(
				'key'           => 'field_adapt_welcome_popup_enabled',
				'label'         => 'Enabled',
				'name'          => 'welcome_popup_enabled',
				'type'          => 'true_false',
				'instructions'  => 'Show the welcome popup to logged-in users who have not seen it yet.',
				'default_value' => 0,
				'ui'            => 1,
			),
			array(
				'key'               => 'field_adapt_welcome_popup_heading',
				'label'             => 'Heading',
				'name'              => 'welcome_popup_heading',
				'type'              => 'text',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_adapt_welcome_popup_enabled',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
			),
			array(
				'key'               => 'field_adapt_welcome_popup_message',
				'label'             => 'Message',
				'name'              => 'welcome_popup_message',
				'type'              => 'textarea',
				'rows'              => 4,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_adapt_welcome_popup_enabled',
							'operator' => '==',
							'value'    => '1',
						),
					),
				),
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
 * Whether the current request should render the popup: logged in, feature
 * enabled, there's actually something configured to show, and this user
 * hasn't dismissed it before.
 */
function adapt_should_show_welcome_popup() {
	if ( ! is_user_logged_in() ) {
		return false;
	}
	if ( ! get_field( 'welcome_popup_enabled', 'option' ) ) {
		return false;
	}
	if ( get_user_meta( get_current_user_id(), 'adapt_welcome_popup_seen', true ) ) {
		return false;
	}
	$heading = get_field( 'welcome_popup_heading', 'option' );
	$message = get_field( 'welcome_popup_message', 'option' );
	return ( $heading || $message );
}

/**
 * Render the popup markup + its own small inline script in the footer.
 * wp_footer (not a template edit) so this feature stays self-contained and
 * shows on every front-end page a logged-in user might land on first,
 * regardless of template.
 */
add_action( 'wp_footer', function() {
	if ( is_admin() || ! adapt_should_show_welcome_popup() ) {
		return;
	}

	$heading = get_field( 'welcome_popup_heading', 'option' );
	$message = get_field( 'welcome_popup_message', 'option' );
	$nonce   = wp_create_nonce( 'adapt_welcome_popup' );
	?>
	<div id="adapt-welcome-popup" class="welcomePopup" role="dialog" aria-modal="true" <?php echo $heading ? 'aria-labelledby="adapt-welcome-popup-heading"' : ''; ?>>
		<div class="welcomePopup-overlay"></div>
		<div class="welcomePopup-box">
			<button type="button" class="welcomePopup-close" aria-label="Close">&times;</button>
			<?php if ( $heading ) : ?>
				<h2 id="adapt-welcome-popup-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $message ) : ?>
				<p><?php echo nl2br( esc_html( $message ) ); ?></p>
			<?php endif; ?>
			<button type="button" class="welcomePopup-ok std-button">Got it</button>
		</div>
	</div>
	<script>
	(function() {
		var popup = document.getElementById('adapt-welcome-popup');
		if (!popup) return;

		document.body.classList.add('fixed');
		var closeBtn = popup.querySelector('.welcomePopup-close');
		if (closeBtn) closeBtn.focus();

		function dismiss() {
			document.body.classList.remove('fixed');
			popup.remove();
			document.removeEventListener('keydown', onKeydown);

			var xhr = new XMLHttpRequest();
			xhr.open('POST', '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send('action=adapt_dismiss_welcome_popup&nonce=<?php echo esc_js( $nonce ); ?>');
		}

		function onKeydown(e) {
			if (e.key === 'Escape') dismiss();
		}

		popup.querySelector('.welcomePopup-overlay').addEventListener('click', dismiss);
		popup.querySelector('.welcomePopup-ok').addEventListener('click', dismiss);
		if (closeBtn) closeBtn.addEventListener('click', dismiss);
		document.addEventListener('keydown', onKeydown);
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
