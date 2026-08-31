<?php
/**
 * Template Name: Register Template
 */

// template-register-kyck.php (Template Name: KYC Register Template) is a
// near-identical page template that only adds one extra body class - rather
// than maintain two full copies of this markup, it sets $is_kyc_register
// and includes this file directly instead of calling get_header() itself.
$is_kyc_register = $is_kyc_register ?? false;

get_header();
?>
<header class="login-header">
	<div class="top">
		<div class="container">
			<div class="login-header-left">
				<a href="https://adapt.com.au">Go to <strong>adapt.com.au</strong></a>
			</div>
			<div class="login-header-logo">
				<?php $header_logo = get_field( 'registration_header_logo', 'option' ); ?>
				<?php if ( $header_logo ) { ?>
					<?php echo wp_get_attachment_image( $header_logo['ID'], 'full', false, array( 'alt' => $header_logo['alt'], 'width' => '360', 'height' => '20' ) ); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</header>
<main id="main" role="main" class="home login register-form<?php if ( $is_kyc_register ) { ?> kyc-register-form<?php } ?>">
	<section class="login">
		<div class="container">
			<div class="login-container-inner register-container-inner">
				<div class="login-form-container">
					<span class="form-title"><?php echo esc_html( get_field( 'login_form_title' ) ); ?></span>
					<span class="form">
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			                <?php the_content(); ?>
			            <?php endwhile; endif;  ?>
					</span>
				</div>
			</div>
		</div>
	</section>

</main>

<?php
//get_footer();
?>
