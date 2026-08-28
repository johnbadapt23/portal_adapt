<?php $q = get_queried_object(); ?>
<?php if ( have_rows( 'introduction', $q ) ) : ?>
	<?php while ( have_rows( 'introduction', $q ) ) : the_row(); ?>
        <section class="two-column-services landing-video-intro kyc-video-introduction background-white">
            <div class="container">
                <div class="landing-video-intro-columns">
                    <div class="column one-half text-column">
                        <div class="text-content-inner">   
							<a class="kit-back-button" href="/know-your-customer/" target="_self">Back</a>
                            <span class="subtitle text-red"><a href="/know-your-customer/" target="_self"><?php echo esc_html( get_sub_field( 'sub_title' ) ); ?></a></span>              
                            <h2 class="title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                            <span class="text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                            <span class="links-container">
                                <!-- Logic for purchased vs non purchased -->
                                <?php if($purchased == 'yes'){ ?>
                                        <a class="scroll-to-button button red-button" href="#kycContent">Get Started</a>
                                <?php } else { ?> 
                                    <?php $vimeoCode = get_sub_field( 'vimeo_code' ); ?>
                                      <?php if ( have_rows( 'buttons' ) ) : ?>
                                        <?php $buttonCounter = 1; ?>
                                        <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                            <?php if(get_sub_field( 'button_type' ) == 'video-button') { ?>
                                                <a class="video-popup popup-vimeo video-link stdBtn red red-button" href="https://vimeo.com/<?php echo esc_attr( $vimeoCode ) ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                            <?php } else { ?>
                                                <a class="link stdBtn red-outline-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                            <?php } ?>
                                            <?php $buttonCounter++; ?>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                <?php } ?>                              
                            </span>
                        </div>
                    </div>
                    <div class="column one-half video-column">
                        <div class="image-container video-container">                            
                            <div class="bg-container">
                                <?php $poster_image = get_sub_field( 'poster_image' ); ?>
                                <?php if ( $poster_image ) { ?>
                                    <?php echo wp_get_attachment_image( $poster_image['ID'], 'full', false, array( 'alt' => $poster_image['alt'] ) ); ?>
                                <?php } ?>                                                                
                                <?php if( get_sub_field( 'vimeo_code' )) { ?>
                                    <span class="opacity-overlay"></span>
                                    <a class="popup-vimeo" href="https://vimeo.com/<?php echo esc_attr( get_sub_field('vimeo_code') ); ?>"></a>
                                <?php } ?>                                
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </section>
	<?php endwhile; ?>
<?php else : ?>
	<?php // no rows found ?>
<?php endif; ?>

<section class="kits-listing-filter kit-type-listing-filter">
	<div class="container">
		<div class="filters-container">
			<div class="filter-sidebar">
				<span class="filter-group-listing button-group">
					<a class="kit-filter is-checked all-filter" data-filter="*"><span class="kit-filter-label">All Kits</span></a>
				</span>
				<span class="filter-group-listing button-group">
					<a class="kit-filter my-filter" data-filter=".my-kits"><span class="kit-filter-label">My Kits</span></a>
				</span>
			</div>
			<div class="kits-listing grid">
				<?php 
				$purchasedargs = array(
					'posts_per_page' => -1,
					'post_type' => 'kyc',
					'tax_query' => array(
						'relation' => 'AND',
						array (
							'taxonomy' => 'kit-type',
							'field' => 'slug',
							'terms'    => $q->slug
						)
					)
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
								<span class="one-third kit-item my-kits <?php echo esc_attr( $kitType ); ?>">
									<span class="kit-inner background-white ">
										<span class="listing-title"><?php echo esc_html( get_field( 'listing_title' ) ); ?></span>
										<span class="icon-container">
											<?php $listing_icon = get_field( 'listing_icon' ); ?>
											<?php if ( $listing_icon ) { ?>
												<?php echo wp_get_attachment_image( $listing_icon['ID'], 'full', false, array( 'alt' => $listing_icon['alt'] ) ); ?>
											<?php } ?>
										</span>
										<span class="excerpt-container">
											<?php echo esc_html( get_field( 'listing_excerpt' ) ); ?>
										</span>
										<span class="button-container">
											<a class="stdBtn black-outline-button" href="<?php the_permalink(); ?>" target="_self">Access Now</a>
										</span>
									</span>
								</span>
							<?php } else { ?> 
								<span class="one-third kit-item my-kits <?php echo esc_attr( $kitType ); ?> kit-slider-container">
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
												<?php echo esc_html( get_field( 'listing_excerpt' ) ); ?>
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
															<?php echo esc_html( get_field( 'listing_excerpt' ) ); ?>
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
					'post_type' => 'kyc',
					'tax_query' => array(
						'relation' => 'AND',
						array (
							'taxonomy' => 'kit-type',
							'field' => 'slug',
							'terms'    => $q->slug
						)
					)
				);	
				$nonpurchasedLoop = new WP_Query( $nonpurchasedargs  );	
				if ( $nonpurchasedLoop->have_posts() ) :
					$counter = 0;
					while ( $nonpurchasedLoop->have_posts() ) : $nonpurchasedLoop->the_post(); ?>
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
                        <?php if(current_user_can('mepr_auth')) {?>
                        <?php } else { ?>
                            <span class="one-third kit-item <?php echo esc_attr( $kitType ); ?>">
                                <span class="kit-inner background-pink">
                                    <span class="listing-title"><?php echo esc_html( get_field( 'listing_title' ) ); ?></span>
                                    <span class="icon-container">
                                        <?php $listing_icon = get_field( 'listing_icon' ); ?>
                                        <?php if ( $listing_icon ) { ?>
                                            <?php echo wp_get_attachment_image( $listing_icon['ID'], 'full', false, array( 'alt' => $listing_icon['alt'] ) ); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="excerpt-container">
                                        <?php echo esc_html( get_field( 'listing_excerpt' ) ); ?>
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