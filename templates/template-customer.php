<?php
/**
 * Template Name: Customer Kits Landing Template
 */

get_header();
?>

<main id="main" role="main" class="evr customer-kits">

<?php if ( have_rows( 'content' ) ): ?>
	<?php while ( have_rows( 'content' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'video_introduction' ) : ?>
			<?php get_template_part( 'templates/components/_customer-video-landing' ); ?>
		<?php elseif ( get_row_layout() == 'types' ) : ?>
            <?php get_template_part( 'templates/components/_customer-types' ); ?>			
		<?php elseif ( get_row_layout() == 'image_and_text_block' ) : ?>
            <?php get_template_part( 'templates/components/_customer-image-text' ); ?>						
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>
<section class="kits-listing-filter">
	<div class="container">
		<div class="filters-container">
			<div class="filter-sidebar">
				<?php 
					$terms = get_terms( array(
						'post_type' => 'kyc',
						'taxonomy' => 'kit-type',
						'parent' => '0',
						'hide_empty' => true
					) ); 
				?>
				<span class="filter-group-listing button-group">
					<a class="kit-filter is-checked all-filter" data-filter="*"><span class="kit-filter-label">All</span></a>
				</span>
				<?php foreach($terms as $term) { ?>
					<span class="kit-filter-group">
						<?php
							$child_terms = get_terms(array(
								'post_type' => 'kyc',
								'taxonomy' => 'kit-type',
								'parent' => $term->term_id, // Get child terms of the current parent term
								'hide_empty' => false // Set hide_empty to false to include empty terms
							));
						?>
						<span class="filter-group-toggle<?php if (count($child_terms) > 0) { ?> with-buttons<?php } ?>"><span class="toggle-text"><?php echo esc_html( $term -> name ); ?></span></span>						
						<span class="filter-group-listing button-group<?php if (count($child_terms) > 0) { ?> with-buttons<?php } ?>">
							<?php 
								foreach ($child_terms as $child_term) { ?>
									<a class="kit-filter" data-filter=".<?php echo $child_term->slug; ?>"><span class="kit-filter-checkbox"></span><span class="kit-filter-label"><?php echo esc_html( $child_term->name ); ?></span></a>
								<?php }
							?>
							<span class="opacity-layer"></span>
						</span>
					</span>				
				<?php } ?>
			</div>
			<div class="kits-listing grid">
				<?php 
				$purchasedargs = array(
					'posts_per_page' => -1,
					'post_type' => 'kyc'
				);	
				$purchasedLoop = new WP_Query( $purchasedargs  );	
				if ( $purchasedLoop->have_posts() ) :
					$counter = 0;
					while ( $purchasedLoop->have_posts() ) : $purchasedLoop->the_post(); ?>
					<?php if(current_user_can('mepr_auth')) {?>
						<?php if ( get_field( 'this_older_version' ) == 0 ) { ?>
							<?php if (get_the_terms($post->ID, 'kit-type')) {
								$termsType = get_the_terms($post->ID, 'kit-type');
								$kitType = ''; 
								foreach ($termsType as $index => $type) {
									if ($type->parent !== 0) {
										if ($index > 0) {
											$kitType .= ' ';
										}
										$kitType .= $type->slug;
									}
								}							
							}
							?>
							<?php if (get_field( 'older_version_question' ) == 'no') { ?> 
								<span class="one-third kit-item <?php echo $kitType; ?>">
									<span class="kit-inner background-white ">
										<span class="listing-title"><?php echo esc_html( get_field( 'listing_title' ) ); ?></span>
										<span class="icon-container">
											<?php $listing_icon = get_field( 'listing_icon' ); ?>
											<?php if ( $listing_icon ) { ?>
												<?php echo wp_get_attachment_image( $listing_icon['ID'], 'full', false, array( 'alt' => $listing_icon['alt'] ) ); ?>
											<?php } ?>
										</span>
										<span class="excerpt-container">
											<?php echo get_field( 'listing_excerpt' ); ?>
										</span>
										<span class="button-container">
											<a class="stdBtn black-outline-button" href="<?php the_permalink(); ?>" target="_self">Access Now</a>
										</span>
									</span>
								</span>
							<?php } else { ?> 
								<span class="one-third kit-item <?php echo $kitType; ?> kit-slider-container">
									<span class="kit-slider">
										<span class="kit-inner background-white">
											<?php if ( get_field( 'show_new_tag' ) == 1 ) { ?>
												<span class="new-flag">New</span>
											<?php } ?>
											<span class="listing-title"><?php echo esc_html( get_field( 'listing_title' ) ); ?></span>
											<span class="icon-container">
												<?php $listing_icon = get_field( 'listing_icon' ); ?>
												<?php if ( $listing_icon ) { ?>
													<?php echo wp_get_attachment_image( $listing_icon['ID'], 'full', false, array( 'alt' => $listing_icon['alt'] ) ); ?>
												<?php } ?>
											</span>
											<span class="excerpt-container">
												<?php echo get_field( 'listing_excerpt' ); ?>
											</span>
											<span class="button-container">
												<a class="stdBtn black-outline-button" href="<?php the_permalink(); ?>" target="_self">Access Now</a>
											</span>
										</span>
										<?php $older_version = get_field( 'older_version' ); ?>
										<?php if ( $older_version ): ?>
											<?php foreach ( $older_version as $post ):  ?>
												<?php setup_postdata ( $post ); ?>
													<span class="kit-inner background-white">												
														<span class="listing-title"><?php echo esc_html( get_field( 'listing_title' ) ); ?></span>
														<span class="icon-container">
															<?php $listing_icon = get_field( 'listing_icon' ); ?>
															<?php if ( $listing_icon ) { ?>
																<?php echo wp_get_attachment_image( $listing_icon['ID'], 'full', false, array( 'alt' => $listing_icon['alt'] ) ); ?>
															<?php } ?>
														</span>
														<span class="excerpt-container">
															<?php echo get_field( 'listing_excerpt' ); ?>
														</span>
														<span class="button-container">
															<a class="stdBtn black-outline-button" href="<?php the_permalink(); ?>" target="_self">Access Now</a>
														</span>
													</span>
											<?php endforeach; ?>
										<?php wp_reset_postdata(); ?>
										<?php endif; ?>
									</span>
								</span>														
							<?php } ?>
						<?php } ?>
					<?php } else { ?>
					<?php } ?>
					<?php 
					endwhile; else : ?>
				<?php 
				endif; ?>
				<?php wp_reset_postdata(); ?> 	
				<?php 
				$nonpurchasedargs = array(
					'posts_per_page' => -1,
					'post_type' => 'kyc'
				);	
				$nonpurchasedLoop = new WP_Query( $nonpurchasedargs  );	
				if ( $nonpurchasedLoop->have_posts() ) :
					$counter = 0;
					while ( $nonpurchasedLoop->have_posts() ) : $nonpurchasedLoop->the_post(); ?>
					<?php if (get_the_terms($post->ID, 'kit-type')) {
						$termsType = get_the_terms($post->ID, 'kit-type');
						$kitType = ''; 
						foreach ($termsType as $index => $type) {
							if ($type->parent !== 0) {
								if ($index > 0) {
									$kitType .= ' ';
								}
								$kitType .= $type->slug;
							}
						}							
					}
					?>
					<?php if(current_user_can('mepr_auth')) {?>
					<?php } else { ?>
						<?php if ( get_field( 'this_older_version' ) == 0 ) { ?>
							<span class="one-third kit-item <?php echo $kitType; ?>">
								<span class="kit-inner background-pink">
									<span class="listing-title"><?php echo esc_html( get_field( 'listing_title' ) ); ?></span>
									<span class="icon-container">
										<?php $listing_icon = get_field( 'listing_icon' ); ?>
										<?php if ( $listing_icon ) { ?>
											<?php echo wp_get_attachment_image( $listing_icon['ID'], 'full', false, array( 'alt' => $listing_icon['alt'] ) ); ?>
										<?php } ?>
									</span>
									<span class="excerpt-container">
										<?php echo get_field( 'listing_excerpt' ); ?>
									</span>
									<span class="button-container">
										<a class="stdBtn black-outline-button" href="<?php the_permalink(); ?>" target="_self">Preview</a>
									</span>
								</span>
							</span>
						<?php } ?>
					<?php } ?>
					<?php 
					endwhile; else : ?>
				<?php 
				endif; ?>
				<?php wp_reset_postdata(); ?> 	
			</div>
		</div>
	</div>
</section>
</main>

<?php get_footer(); ?>
