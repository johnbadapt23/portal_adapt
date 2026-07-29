<?php
/**
 * Template Name: TNC Register Template
 */

get_header();
?>

<main id="main" role="main" class="home login register-form tnc-registration">
	<?php if ( have_rows( 'tnc_registration_banner', 'options' ) ) : ?>
		<section class="tnc-registration-banner">
			<span class="grey-background"></span>
			<div class="container">
				<?php while ( have_rows( 'tnc_registration_banner', 'options' ) ) : the_row(); ?>
					<div class="column-container">
						<div class="column text-column">
							<span class="title-container"><?php echo get_sub_field( 'title' ); ?></span>
							<span class="text-container black-text">
								<?php echo get_sub_field( 'text' ); ?>
							</span>
							<span class="button-container">
								<a class="formPopupRegister red-button red stdBtn" href="#loginForm"><?php echo get_sub_field( 'scroll_to_button_text' ); ?></a>
							</span>							
						</div>
						<div class="column image-column">
							<span class="centered-image-container">
								<span class="image-container">
									<span class="bg-container">
										<?php $image = get_sub_field( 'image' ); ?>
										<?php if ( $image ) { ?>
											<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
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
				<h3 class="form-title"><?php echo get_field( 'tnc_registration_form_title', 'options' ); ?></h3>
				<span class="form">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<?php the_content(); ?>
					<?php endwhile; endif;  ?>
				</span>
			</div>
		</div>
	</div>
	<?php if ( have_rows( 'tnc_registration_video_module', 'options' ) ) : ?>
		<?php while ( have_rows( 'tnc_registration_video_module', 'options' ) ) : the_row(); ?>
			<section class="registration-video-block">
				<div class="container">
					<div class="video-inner">
						<div class="image-container video-container">                            
							<div class="bg-container">
								<?php $poster_image = get_sub_field( 'poster_image' ); ?>
								<?php if ( $poster_image ) { ?>
									<img src="<?php echo $poster_image['url']; ?>" alt="<?php echo $poster_image['alt']; ?>" />
								<?php } ?>                                                                
								<?php if( get_sub_field( 'vimeo_code' )) { ?>
									<span class="opacity-overlay"></span>
									<a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
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
	<?php if ( have_rows( 'tnc_registration_icon_text_block', 'options' ) ) : ?>
		<?php while ( have_rows( 'tnc_registration_icon_text_block', 'options' ) ) : the_row(); ?>
			<section class="text-icon-module">
				<div class="container">
					<div class="inner">
						<span class="icon-container">
							<?php $icon = get_sub_field( 'icon' ); ?>
							<?php if ( $icon ) { ?>
								<img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
							<?php } ?>
						</span>
						<span class="text-container">
							<?php echo get_sub_field( 'text' ); ?>
						</span>
					</div>
				</div>
			</section>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	<?php if ( have_rows( 'tnc_registration_two_column_list', 'options' ) ) : ?>
		<?php while ( have_rows( 'tnc_registration_two_column_list', 'options' ) ) : the_row(); ?>
			<section class="two-column-list">
				<div class="container">
					<div class="column-container">
						<div class="column text-list-column">
							<h2 class="black-text"><?php echo get_sub_field( 'title' ); ?></h2>							
							<div class="mobile-image">
								<?php $image = get_sub_field( 'image' ); ?>
								<?php if ( $image ) { ?>
									<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
								<?php } ?>
							</div>
							<?php if ( have_rows( 'list_items' ) ) : ?>
								<span class="list-container">
									<?php while ( have_rows( 'list_items' ) ) : the_row(); ?>
										<span class="list-item text-black labelXLarge">
											<?php echo get_sub_field( 'list_text' ); ?>
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
								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							<?php } ?>
						</div>
					</div>
				</div>			
			</section>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	<?php if ( have_rows( 'tnc_registration_image_and_text_block', 'options' ) ) : ?>
		<?php while ( have_rows( 'tnc_registration_image_and_text_block', 'options' ) ) : the_row(); ?>
			<section class="customer-kit-image-text <?php echo get_sub_field( 'background_colour' ); ?>">
				<div class="container">
					<div class="title-container">
						<h3><?php echo get_sub_field( 'title' ); ?></h3>
					</div>
					<div class="column-container">
						<?php if ( have_rows( 'column' ) ) : ?>
							<?php while ( have_rows( 'column' ) ) : the_row(); ?>
								<div class="column one-half">
									<div class="image-column one-third">
										<?php $image = get_sub_field( 'image' ); ?>
										<?php if ( $image ) { ?>
											<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
										<?php } ?>
									</div>
									<div class="text-column two-thirds">
										<span class="title"><?php echo get_sub_field( 'title' ); ?></span>
										<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
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
	<?php if ( have_rows( 'tnr_registration_testimonial_slider', 'options' ) ) : ?>
		<?php while ( have_rows( 'tnr_registration_testimonial_slider', 'options' ) ) : the_row(); ?>
			<section class="ecosystem-quote-slider">
				<div class="container">
					<div class="quote-slider-module">
						<?php if ( have_rows( 'slide' ) ) : ?>
							<?php while ( have_rows( 'slide' ) ) : the_row(); ?>
								<div class="quote-slide">
									<div class="quote-slider-inner">
										<h4 class="quote text-black"><?php echo get_sub_field( 'quote' ); ?></h4>
										<span class="quote-title text-black"><?php echo get_sub_field( 'quoter' ); ?></span>
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
