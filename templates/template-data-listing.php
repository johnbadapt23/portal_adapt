<?php
/**
 * Template Name: Data & Insights Listing Template
 */

get_header();
?>


<main id="main" role="main" class="home">
	<section class="data-links">
		<div class="container">
			<span class="breadcrumb-container">
				<a class="home-link" href="/" target="_self">Home</a>
				<span class="divider">/</span>
				<span class="title"><?php echo esc_html( get_the_title() ); ?></span>
			</span>
			<span class="title-container">
				<h1 clas="h2-style"><?php echo esc_html( get_the_title() ); ?></h1>
			</span>
			<?php if ($membershipType == 'advantage') { ?>
				<div class="links-container advantage">
					<?php if ( have_rows( 'link_modules' ) ) : ?>
						<?php while ( have_rows( 'link_modules' ) ) : the_row(); ?>
							<?php if ( have_rows( 'button' ) ) : ?>
								<?php while ( have_rows( 'button' ) ) : the_row(); ?>
									<?php $buttonLink = get_sub_field( 'link' );
										$target =  get_sub_field( 'link_target' );  ?>
								<?php endwhile; ?>
							<?php else : ?>
								<?php // no rows found ?>
							<?php endif; ?>
							<div class="data-link one-third column">
								<div class="slide-container">
									<?php if($buttonLink){?>
										<a class="imagecontainer-link" href="<?php echo esc_url( $buttonLink ); ?>" target="<?php echo $target; ?>">
									<?php } ?>
										<span class="image-container">
											<?php $image = get_sub_field( 'image_one' ); ?>
											<?php $offsetimage = get_sub_field( 'image_two' ); ?>
											<span class="bg-container offset-image-container">
												<?php if ( $offsetimage ) { ?>
													<?php echo wp_get_attachment_image( $offsetimage['ID'], 'full', false, array( 'alt' => $offsetimage['alt'] ) ); ?>
												<?php } ?>
											</span>
											<span class="bg-container">
												<?php if ( $image ) { ?>
													<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
												<?php } ?>
											</span>
										</span>
									<?php if($buttonLink){?>
										</a>
									<?php } ?>
								</div>
								<div class="information-container">
									<h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
									<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
									<span class="button-container">
										<?php if ( have_rows( 'button' ) ) : ?>
											<?php while ( have_rows( 'button' ) ) : the_row(); ?>
											<?php if (get_sub_field('coming_soon') == 1 ){ ?> 
												<span class="button pink-button no-click coming-soon"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></span>
											<?php } else { ?>
												<a class="button black-outline-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
											<?php } ?>
											<?php endwhile; ?>
										<?php else : ?>
											<?php // no rows found ?>
										<?php endif; ?>
									</span>
								</div>
							</div>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</div>
			<?php } else { ?> 
				<div class="links-container it-pro">
					<?php if ( have_rows( 'link_modules_it_pro' ) ) : ?>
						<?php while ( have_rows( 'link_modules_it_pro' ) ) : the_row(); ?>
							<?php if ( have_rows( 'button' ) ) : ?>
								<?php while ( have_rows( 'button' ) ) : the_row(); ?>
									<?php $buttonLink = get_sub_field( 'link' );
										$target =  get_sub_field( 'link_target' );  ?>
								<?php endwhile; ?>
							<?php else : ?>
								<?php // no rows found ?>
							<?php endif; ?>
							<div class="data-link one-third column">
								<div class="slide-container">
									<?php if($buttonLink){?>
										<a class="imagecontainer-link" href="<?php echo esc_url( $buttonLink ); ?>" target="<?php echo $target; ?>">
									<?php } ?>
										<span class="image-container">
											<?php $image = get_sub_field( 'image_one' ); ?>
											<?php $offsetimage = get_sub_field( 'image_two' ); ?>
											<span class="bg-container offset-image-container">
												<?php if ( $offsetimage ) { ?>
													<?php echo wp_get_attachment_image( $offsetimage['ID'], 'full', false, array( 'alt' => $offsetimage['alt'] ) ); ?>
												<?php } ?>
											</span>
											<span class="bg-container">
												<?php if ( $image ) { ?>
													<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
												<?php } ?>
											</span>
										</span>
									<?php if($buttonLink){?>
										</a>
									<?php } ?>
								</div>
								<div class="information-container">
									<h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
									<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
									<span class="button-container">
										<?php if ( have_rows( 'button' ) ) : ?>
											<?php while ( have_rows( 'button' ) ) : the_row(); ?>
											<?php if (get_sub_field('coming_soon') == 1 ){ ?> 
												<span class="button pink-button no-click coming-soon"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></span>
											<?php } else { ?>
												<a class="button black-outline-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
											<?php } ?>
											<?php endwhile; ?>
										<?php else : ?>
											<?php // no rows found ?>
										<?php endif; ?>
									</span>
								</div>
							</div>
						<?php endwhile; ?>
					<?php else : ?>
						<?php // no rows found ?>
					<?php endif; ?>
				</div>
			<?php } ?>
			
		</div>
	</section>

</main>

<?php get_footer(); ?>
