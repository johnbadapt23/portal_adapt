<?php

// Load field value.
$date_string = get_field('event_date');

// Create DateTime object from value (formats must match).
$date = DateTime::createFromFormat('Ymd', $date_string);

?>

<section class="topicBanner webinarBanner">
	<div class="imageSizeContainer">
		<div class="bgContainer">
			<?php $banner_image = get_field( 'webinar_banner_image', 'option' ); ?>
			<?php echo wp_get_attachment_image( $banner_image['ID'], 'full', false, [ 'alt' => $banner_image['alt'], 'class' => 'desktop' ] ); ?>
		</div>
		<div class="container">
			<div class="column webinar-column first-column">
				<?php $current_user = wp_get_current_user();
				if ( 0 == $current_user->ID ) { ?>
					<!-- Not logged in. -->
					<a href="/login" class="text-red banner-sub-title">Analyst Market Briefings</a>
				<?php } else { ?>
					<a href="/events/analyst-market-briefings" class="text-red banner-sub-title">Analyst Market Briefings</a>
				<?php } ?>
				<h1 class="text-white"><?php echo esc_html( get_the_title() ); ?></h1>
				<p class="text-white"><?php echo esc_html( $date->format('l, j F, Y') ); ?> @<?php echo esc_html( get_field( 'event_start_time' ) ); ?></p>
			</div>
		</div>
	</div>
</section>
<section class="webinar-article bg-white">
	<div class="container">
		<div class="column-container">
			<div class="column webinar-column second-column right-column">
				<span class="register-container">
					<span class="sticky-container">
						<span class="upper-container">
							<img class="calendar-icon" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/calendar.svg" width="26" height="26" loading="lazy" decoding="async" alt="Calendar" />
							<span class="date-title small-text-grey">Date</span>
							<span class="date text-black"><?php echo esc_html( $date->format('l, j F, Y') ); ?></span>
							<span class="time-title small-text-grey">Time</span>
							<span class="time text-black"><?php echo esc_html( get_field( 'event_start_time' ) ); ?> -<?php echo esc_html( get_field( 'event_end_time' ) ); ?></span>
							<span class="location-title small-text-grey">Location</span>
							<span class="location text-black">Zoom link will be available upon registration.</span>
							<span class="upper-bar"></span>
						</span>
						<span class="bottom-container">
							<span class="bottom-bar"></span>
							<?php if(get_field('pre_button_text')){ ?>
								<?php $preText =  get_field('pre_button_text'); ?>
							<?php } ?>
							<?php if(get_field('button_text')){ ?>
								<?php $buttonText =  get_field('button_text'); ?>
							<?php } ?>
							<?php if( get_field( 'button' ) =='register' ) { ?>
								<span class="title"><?php if($preText){ ?><?php echo esc_html( $preText ); ?><?php } else { ?>Register to Attend<?php } ?></span>
								<a class="registerButton register-scroll-button background-red" href="#registerForm"><?php if($buttonText){ ?><?php echo esc_html( $buttonText ); ?><?php } else { ?>Register<?php } ?></a>
							<?php } else { ?>
								<?php if($preText){ ?><span class="title"><?php echo esc_html( $preText ); ?></span><?php } ?>
								<span class="registerButton upcoming background-grey"><?php if($buttonText){ ?><?php echo esc_html( $buttonText ); ?><?php } else { ?>Upcoming<?php } ?></span>
							<?php } ?>
						</span>
					</span>
				</span>
			</div>
			<div class="column webinar-column first-column">
				<span class="webinar-subtitle"><?php echo esc_html( get_field( 'sub_title' ) ); ?></span>
				<span class="webinar-content content">
					<?php echo wp_kses_post( get_field( 'content' ) ); ?>
				</span>
				<?php if ( have_rows( 'takeaways' ) ) : ?>
					<span class="takeaways-container">
						<?php while ( have_rows( 'takeaways' ) ) : the_row(); ?>
							<span class="webinar-subtitle"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
							<?php if ( have_rows( 'key_takeaways' ) ) : ?>
								<?php while ( have_rows( 'key_takeaways' ) ) : the_row(); ?>
									<span class="takeaway"><?php echo esc_html( get_sub_field( 'takeaway' ) ); ?></span>
								<?php endwhile; ?>
							<?php else : ?>
								<?php // no rows found ?>
							<?php endif; ?>
						<?php endwhile; ?>
					</span>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php if(get_field( 'registration_form_embed' )) { ?>
	<div style="display: none;">
		<div class="hidden-fields" style="display: none;">
			<span class="hidden-name"><?php echo esc_html( get_field( 'registration_form_event_name_sf' ) ); ?></span>
			<span class="hidden-event"><?php echo esc_html( get_the_title() ); ?></span>
			<span class="hidden-date"><?php echo esc_html( $date->format('l, j F, Y') ); ?></span>
			<span class="hidden-id"><?php echo esc_html( get_field( 'registration_form_sf_id' ) ); ?></span>
		</div>
		<div class="webinar-register-form" id="registerForm">
			<div class="container">
				<span class="webinar-subtitle"><?php echo esc_html( get_field( 'registration_form_title' ) ); ?></span>
				<span class="form-container"><?php echo get_field( 'registration_form_embed' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored registration-form embed markup requires raw HTML/script output; wp_kses_post() would strip the tags the embed needs to function. ?></span>
			</div>
		</div>
		<?php if ( have_rows( 'registration_form_fields' ) ) : ?>
			<?php while ( have_rows( 'registration_form_fields' ) ) : the_row(); ?>
				<?php if ( get_sub_field( 'country' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					<style>
						.hs_country {
							display: none;
						}
					</style>
				<?php } ?>
				<?php if ( get_sub_field( 'client_communication_method' ) == 1 ) {
				 // echo 'true';
				} else { ?>
	   				<style>
	   					.hs_client_communication_method {
	   						display: none;
	   					}
	   				</style>
	   			<?php } ?>
				<?php if ( get_sub_field( 'attendance_preference' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					 <style>
						 .hs_attendance_preference {
							 display: none;
						 }
					 </style>
				 <?php } ?>
				<?php if ( get_sub_field( 'beverage_choice' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					 <style>
						 .hs_wine_choice {
							 display: none;
						 }
					 </style>
				 <?php } ?>
				<?php if ( get_sub_field( 'gift_opt_in_to_the_session' ) == 1 ) { ?>
					<span class="gift-opt-in-text"><?php echo esc_html( get_sub_field( 'gift_opt_in_text' ) ); ?></span>
				<?php } else { ?>
	   				<style>
	   					.hs_gift_opt_in {
	   						display: none;
	   					}
	   				</style>
	   			<?php } ?>
				<?php if ( get_sub_field( 'homeoffice_delivery_address' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					 <style>
						 .hs_home_office_delivery_address {
							 display: none;
						 }
					 </style>
				 <?php } ?>
				<?php if ( get_sub_field( 'dietary_requirements' ) == 1 ) {
				 // echo 'true';
				 } else { ?>
					 <style>
						 .hs_dietary_requirements_,
						 .hs_dietary_requirements {
							 display: none;
						 }
					 </style>
				 <?php } ?>
				<?php if ( get_sub_field( 'marketing' ) == 1 ) { ?>
					<span class="marketing-text"><?php echo esc_html( get_sub_field( 'marketing_text' ) ); ?></span>
				<?php } else { ?>
					<style>
						.hs_single_client_opt_in {
							display: none;
						}
					</style>
				<?php } ?>
				<?php if ( get_sub_field( 'help_text_opt_in' ) == 1 ) {
					?>
						<span class="umbrella-text"><?php echo esc_html( get_sub_field( 'umbrella_opt_in_text' ) ); ?></span>
						<span class="umbrella-help-text"><?php echo esc_html( get_sub_field( 'help_text' ) ); ?></span>
					<?php
				} else { ?>
					<style>
						.hs_client_communication_opt_in {
							display: none;
						}
					</style>
				<?php } ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
	</div>
<?php } ?>


<?php if ( have_rows( 'content_blocks' ) ): ?>
	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'speaker_block' ) : ?>
			<section class="webinar-speaker-block bg-lightest">
				<div class="container">
					<span class="webinar-subtitle"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
					<?php $count = count(get_sub_field('speaker')); ?>
					<?php if ( have_rows( 'speaker' ) ) : ?>
						<div class="speaker-container<?php if ($count > 1){ ?> flex-speaker multiple-speakers<?php } ?>">
						<?php while ( have_rows( 'speaker' ) ) : the_row(); ?>
							<div class="column<?php if ($count > 1){ ?> speaker-column-flex one-half<?php } else { ?> webinar-column first-column<?php }?>">
							<?php $post_object = get_sub_field( 'speaker' ); ?>
							<?php if ( $post_object ): ?>
								<?php $post = $post_object; ?>
								<?php setup_postdata( $post ); ?>
									<div class="speaker-container-inner">
										<span class="speaker-image">
											<?php
					$inline_img_136_src = get_field( 'speaker_image' );
					$inline_img_136_attach_id = $inline_img_136_src ? attachment_url_to_postid( $inline_img_136_src ) : 0;
					if ( $inline_img_136_attach_id ) {
						echo wp_get_attachment_image( $inline_img_136_attach_id, 'full', false, [ 'alt' => get_the_title() ] );
					} elseif ( $inline_img_136_src ) {
						echo '<img src="' . esc_url( $inline_img_136_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
										</span>
										<span class="description">
											<span class="speaker-name"><?php echo esc_html( get_the_title() ); ?></span>
											<span class="speaker-role"><?php echo esc_html( get_field('speaker_description') ); ?></span>
										</span>
										<div class="textBlock">
											<?php
												 $text = get_field('speaker_details');
												 $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
											?>
											<span class="speaker-details-excerpt registration-excerpt"><?php echo esc_html( $trimmed_content ); ?></span>
											<span class="speaker-details">
												<?php echo wp_kses_post( get_field('speaker_details') ); ?>
											</span>
										</div>
									</div>
								<?php wp_reset_postdata(); ?>
							<?php endif; ?>
							</div>
						<?php endwhile; ?>
						</div>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</div>
            </section>
		<?php elseif ( get_row_layout() == 'faq_block' ) : ?>
            <section class="webinar-faq bg-dark">
				<div class="container">
					<div class="column second-column right-column">
						<?php $image = get_sub_field( 'image' ); ?>
						<div class="overlay-image-container desktop">
			    			<?php if ( $image ) { ?>
			    				<?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
			    			<?php } ?>
						</div>
						<?php $mobileImage = get_sub_field( 'mobile_image' ); ?>
						<div class="overlay-image-container mobile">
			    			<?php if ( $mobileImage ) { ?>
			    				<?php echo wp_get_attachment_image( $mobileImage['ID'], 'full', false, [ 'alt' => $mobileImage['alt'] ] ); ?>
			    			<?php } ?>
						</div>
					</div>
					<div class="column first-column">
		    			<span class="webinar-subtitle text-white"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
		    			<?php if ( have_rows( 'faq_item' ) ) : ?>
		    				<?php while ( have_rows( 'faq_item' ) ) : the_row(); ?>
								<span class="faq-item">
									<span class="faq-title text-white"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
			    					<span class="faq-content text-white"><?php echo wp_kses_post( get_sub_field( 'content' ) ); ?></span>
								</span>
		    				<?php endwhile; ?>
		    			<?php else : ?>
		    				<?php // no rows found ?>
		    			<?php endif; ?>
					</div>
            </section>

		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
<div class="webinar-mobile-sticky-footer">
	<span class="title">Register to Attend</span>
	<a class="registerButton register-scroll-button background-red" href="#registerForm">Register</a>
</div>
