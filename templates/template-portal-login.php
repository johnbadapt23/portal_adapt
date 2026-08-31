<?php
/**
 * Template Name: Login Template
 */

get_header();
?>
<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/8336221.js"></script>
<!-- End of HubSpot Embed Code -->
<?php
// Check if the user is logged in and a redirect_to parameter exists
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET redirect target, same pattern WP core's own login form uses; validated by wp_safe_redirect() (only allows same-site/allowed-host URLs), no state change.
if ( is_user_logged_in() && isset($_GET['redirect_to']) ) {

    // Sanitize and redirect
    $redirect_to = esc_url($_GET['redirect_to']);
    wp_safe_redirect($redirect_to);
    exit;
}
// phpcs:enable WordPress.Security.NonceVerification.Recommended
?>
<header class="login-header">
	<div class="top">
		<div class="container">
			<div class="login-header-left">
				<a href="https://adapt.com.au">Go to <strong>adapt.com.au</strong></a>
			</div>
			<div class="login-header-logo">
				<?php $header_logo = get_field( 'header_logo' ); ?>
				<?php if ( $header_logo ) { ?>
					<?php echo wp_get_attachment_image( $header_logo['ID'], 'full', false, array( 'alt' => $header_logo['alt'], 'width' => '360', 'height' => '20' ) ); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</header>
<main id="main" role="main" class="home login">
	<section class="login">
		<div class="container">
			<div class="login-container-inner">
				<div class="introduction-text">
					<h2><?php echo wp_kses_post( get_field( 'introduction_text' ) ); ?></h2>
				</div>
				<div class="login-form-container">
					<span class="form-title"><?php echo esc_html( get_field( 'login_form_title' ) ); ?></span>
					<span class="form">
						<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored form-embed markup requires raw HTML/script output; wp_kses_post() would strip the tags the embed needs to function. ?>
						<?php echo get_field( 'login_form' ); ?>
					</span>
					<span class="get-in-touch">
						Not a member? <a class="get-in-touch-button help" onclick="window.HubSpotConversations.widget.open();" id="chat" href="#">Get in touch</a>
					</span>
				</div>
			</div>
		</div>
	</section>
	<script type=”text/javascript”>

		jQuery(window).on("load", function() {
			jQuery('#user_login').attr('placeholder', 'Username or e-mail address');
			jQuery('#user_pass').attr('placeholder', 'Password');


		});
	</script>
</main>

<?php
// get_footer();
?>
