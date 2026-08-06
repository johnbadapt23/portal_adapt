<?php
/**
 * Template Name: Register Thanks Template
 */

get_header();

$user_info = wp_get_current_user();

$first_name = $user_info->first_name;
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
					<?php echo wp_get_attachment_image( $header_logo['ID'], 'full', false, array( 'alt' => $header_logo['alt'] ) ); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</header>

<main id="main" role="main" class="home login register-thanks register-form">
	<span class="opacity-overlay"></span>
	<section class="login">
		<div class="container">
			<div class="login-container-inner register-container-inner">
				<div class="login-form-container">
					<span class="form">
						<h3>Thanks <?php echo $first_name; ?>, you're all set</h3>
						<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			                <?php the_content(); ?>
			            <?php endwhile; endif;  ?>
						<span class="button-container">
							<a class="tour-button button" href="/welcome">Take a tour</a>
						</span>
					</span>
				</div>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
?>
