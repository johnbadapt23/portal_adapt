<?php
/**
 * Template Name: Advantage Register Template
 */

// template-register-tnc.php (Template Name: TNC Register Template) is a
// near-identical page template that only differs in the advantage_ vs tnc_
// ACF field-name prefix used for every options-page lookup below - rather
// than maintain two full copies of this markup, it sets $reg_prefix and
// includes this file directly instead of calling get_header() itself.
$reg_prefix = $reg_prefix ?? 'advantage';

get_header();
?>

<main id="main" role="main" class="home login register-form <?php echo esc_attr( $reg_prefix ); ?>-registration">
	<?php if ( have_rows( $reg_prefix . '_registration_banner', 'options' ) ) : ?>
		<section class="tnc-registration-banner">
			<span class="grey-background"></span>
			<div class="container">
				<?php while ( have_rows( $reg_prefix . '_registration_banner', 'options' ) ) : the_row(); ?>
					<div class="column-container">
						<div class="column text-column">
							<span class="title-container"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
							<span class="text-container black-text">
								<?php echo esc_html( get_sub_field( 'text' ) ); ?>
							</span>
							<span class="button-container">
								<a class="formPopupRegister red-button red stdBtn" href="#loginForm"><?php echo esc_html( get_sub_field( 'scroll_to_button_text' ) ); ?></a>
							</span>							
						</div>
						<div class="column image-column">
							<span class="centered-image-container">
								<span class="image-container">
									<span class="bg-container">
										<?php $image = get_sub_field( 'image' ); ?>
										<?php if ( $image ) { ?>
											<?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
										<?php } ?>
									</span>
								</span>
							</span>
						</div>
					</div>									
				<?php endwhile; ?>
			</div>
		</section>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	<div style="display: none;">
		<div class="login-form-popup" id="loginForm">
			<div class="login-form-container">
				<h3 class="form-title"><?php echo esc_html( get_field( $reg_prefix . '_registration_form_title', 'options' ) ); ?></h3>
				<span class="form">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<?php the_content(); ?>
					<?php endwhile; endif;  ?>
				</span>
			</div>
		</div>
	</div>
	<?php if ( have_rows( $reg_prefix . '_registration_video_module', 'options' ) ) : ?>
		<?php while ( have_rows( $reg_prefix . '_registration_video_module', 'options' ) ) : the_row(); ?>
			<section class="registration-video-block">
				<div class="container">
					<div class="video-inner">
						<div class="image-container video-container">                            
							<div class="bg-container">
								<?php $poster_image = get_sub_field( 'poster_image' ); ?>
								<?php if ( $poster_image ) { ?>
									<?php echo wp_get_attachment_image( $poster_image['ID'], 'full', false, [ 'alt' => $poster_image['alt'] ] ); ?>
								<?php } ?>                                                                
								<?php if( get_sub_field( 'vimeo_code' )) { ?>
									<span class="opacity-overlay"></span>
									<a class="popup-vimeo" href="https://vimeo.com/<?php echo esc_attr( get_sub_field('vimeo_code') ); ?>"></a>
								<?php } ?>                                
							</div>
						</div> 
					</div>
				</div>
			</section>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	<?php if ( have_rows( $reg_prefix . '_registration_icon_text_block', 'options' ) ) : ?>
		<?php while ( have_rows( $reg_prefix . '_registration_icon_text_block', 'options' ) ) : the_row(); ?>
			<section class="text-icon-module">
				<div class="container">
					<div class="inner">
						<span class="icon-container">
							<?php $icon = get_sub_field( 'icon' ); ?>
							<?php if ( $icon ) { ?>
								<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, [ 'alt' => $icon['alt'] ] ); ?>
							<?php } ?>
						</span>
						<span class="text-container">
							<?php echo esc_html( get_sub_field( 'text' ) ); ?>
						</span>
					</div>
				</div>
			</section>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	<?php if ( have_rows( $reg_prefix . '_registration_two_column_list', 'options' ) ) : ?>
		<?php while ( have_rows( $reg_prefix . '_registration_two_column_list', 'options' ) ) : the_row(); ?>
			<section class="two-column-list">
				<div class="container">
					<div class="column-container">
						<div class="column text-list-column">
							<h2 class="black-text"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>							
							<div class="mobile-image">
								<?php $image = get_sub_field( 'image' ); ?>
								<?php if ( $image ) { ?>
									<?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
								<?php } ?>
							</div>
							<?php if ( have_rows( 'list_items' ) ) : ?>
								<span class="list-container">
									<?php while ( have_rows( 'list_items' ) ) : the_row(); ?>
										<span class="list-item text-black labelXLarge">
											<?php echo esc_html( get_sub_field( 'list_text' ) ); ?>
										</span>
									<?php endwhile; ?>
								</span>
							<?php else : ?>
								<?php // no rows found ?>
							<?php endif; ?>						
						</div>
						<div class="column image-column">
							<?php $image = get_sub_field( 'image' ); ?>
							<?php if ( $image ) { ?>
								<?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
							<?php } ?>
						</div>
					</div>
				</div>			
			</section>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	<?php if ( have_rows( $reg_prefix . '_registration_image_and_text_block', 'options' ) ) : ?>
		<?php while ( have_rows( $reg_prefix . '_registration_image_and_text_block', 'options' ) ) : the_row(); ?>
			<section class="customer-kit-image-text <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
				<div class="container">
					<div class="title-container">
						<h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
					</div>
					<div class="column-container">
						<?php if ( have_rows( 'column' ) ) : ?>
							<?php while ( have_rows( 'column' ) ) : the_row(); ?>
								<div class="column one-half">
									<div class="image-column one-third">
										<?php $image = get_sub_field( 'image' ); ?>
										<?php if ( $image ) { ?>
											<?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
										<?php } ?>
									</div>
									<div class="text-column two-thirds">
										<span class="title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
										<span class="text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
									</div>
								</div>
							<?php endwhile; ?>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					</div>
				</div>
			</section>			
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	<?php if ( have_rows( $reg_prefix . '_registration_testimonial_slider', 'options' ) ) : ?>
		<?php while ( have_rows( $reg_prefix . '_registration_testimonial_slider', 'options' ) ) : the_row(); ?>
			<section class="ecosystem-quote-slider">
				<div class="container">
					<div class="quote-slider-module">
						<?php if ( have_rows( 'slide' ) ) : ?>
							<?php while ( have_rows( 'slide' ) ) : the_row(); ?>
								<div class="quote-slide">
									<div class="quote-slider-inner">
										<h4 class="quote text-black"><?php echo esc_html( get_sub_field( 'quote' ) ); ?></h4>
										<span class="quote-title text-black"><?php echo esc_html( get_sub_field( 'quoter' ) ); ?></span>
									</div>
								</div>
							<?php endwhile; ?>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					</div>
				</div>
			</section>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
</main>

<?php
get_footer();
?>
