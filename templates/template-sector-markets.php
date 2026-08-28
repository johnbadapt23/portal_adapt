<?php
/**
 * Template Name: Sector Analysis Market Narratives Template
 */

get_header();
?>


<main id="main" role="main" class="home sector-analysis-listing">
	<?php if ( have_rows( 'banner' ) ) : ?>
		<?php while ( have_rows( 'banner' ) ) : the_row(); ?>
			<?php $banner_image = get_sub_field( 'banner_image' ); ?>
			<section class="topicBanner sector-topic-banner" style="background-image:url(<?php echo $banner_image['url']; ?>);">
				<div class="container">
					<span class="breadcrumb-container">
						<a class="home-link" href="/" target="_self">Home</a>
						<span class="divider">/</span>
						<a class="home-link" href="/market-narratives" target="_self">Market Narratives</a>
						<span class="divider">/</span>
						<span class="title"><?php the_title();?></span>
					</span>
					<span class="title-container">
						<h1 clas="h2-style"><?php echo get_sub_field( 'title' ); ?></h1>
						<span class="subtitle"><?php echo get_sub_field( 'sub_title' ); ?></span>
					</span>
				</div>
			</section>
		<?php endwhile; ?>
	<?php else : ?>
	<?php // no rows found ?>
	<?php endif; ?>
	<?php if ( have_rows( 'filter_buttons' ) ) : ?>
		<section class="explore-section">
			<div class="container">
				<?php while ( have_rows( 'filter_buttons' ) ) : the_row(); ?>
					<h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
					<div class="button-container">
						<?php $sectors_terms = get_sub_field( 'sectors' ); ?>
						<?php if ( $sectors_terms ): ?>
							<?php foreach ( $sectors_terms as $sectors_term ): ?>
								<a class="sector-button button grey-button" href="<?php echo esc_url( get_term_link($sectors_term) ); ?>" target="_self"><?php echo $sectors_term->name; ?></a>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		</section>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>

	<?php if ( have_rows( 'featured_article_slider' ) ) : ?>
		<section class="featured-article-slider-data">
			<div class="container">
				<?php while ( have_rows( 'featured_article_slider' ) ) : the_row(); ?>
					<div class="data-article-slider">
						<?php if ( have_rows( 'article' ) ) : ?>
							<?php while ( have_rows( 'article' ) ) : the_row(); ?>
								<?php $post_object = get_sub_field( 'post' ); ?>
								<?php if ( $post_object ): ?>
									<?php $post = $post_object; ?>
										<?php setup_postdata( $post ); ?>
										<div class="data-slide">
											<div class="slide-container">
												<span class="image-column right-column">
													<span class="v-wrap">
														<span class="v-box">
															<span class="slide-image-container">
																<span class="image-container">
																	<?php if ( have_rows( 'preview_module', $post ) ) : ?>
													                   <?php while ( have_rows( 'preview_module', $post ) ) : the_row(); ?>
													                       <?php if ( have_rows( 'slider_images') ) : ?>
													                           <?php $imageCounter = 1; ?>
													                           <?php while ( have_rows( 'slider_images') ) : the_row(); ?>
																				  	<?php if($imageCounter == 2){ ?>
																						<span class="bg-container offset-image-container">
  																						  <?php $offsetimage = get_sub_field( 'image'); ?>
  																						  <?php if ( $offsetimage ) { ?>
  																							  <?php echo wp_get_attachment_image( $offsetimage['ID'], 'full', false, array( 'alt' => $offsetimage['alt'] ) ); ?>
  																						  <?php } ?>
  																					  </span>
																					<?php } else if ($imageCounter == 1){ ?>
																						<span class="bg-container">
																						<?php $imageSlideOne = get_sub_field( 'image'); ?>
																						<?php if (  $imageSlideOne ) { ?>
																							<?php echo wp_get_attachment_image( $imageSlideOne['ID'], 'full', false, array( 'alt' => '' ) ); ?>
																						<?php } ?>
																					</span>
																					<?php } $imageCounter++; ?>
													                           <?php endwhile; ?>
													                       <?php else : ?>
													                       <?php endif; ?>
													                    <?php endwhile; ?>
													                <?php else : ?>
																		<?php if ( get_field( 'listing_image', $post) ) { ?>
										                                    <?php $image = get_field( 'listing_image', $post); ?>
										                                <?php } else { ?>
										                                    <?php if ( get_field ( 'featured_image_or_video', $post ) == 'video' ) { ?>
										                                        <?php $image = get_field( 'video_poster', $post); ?>
										                                    <?php } else { ?>
										                                        <?php $image = get_field( 'featured_image', $post); ?>
										                                    <?php } ?>
										                                <?php } ?>
																		<span class="bg-container">
																			<?php if (  $image ) { ?>
																				<?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '' ) );
								} else {
									echo '<img src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
																			<?php } ?>
																		</span>
													                <?php endif; ?>
																</span>
															</span>
														</span>
													</span>
												</span>
												<div class="textContainer">
													<span class="v-wrap">
														<span class="v-box">
							                                <span class="topicFilter">
								                                    <?php if (yoast_get_primary_term_id('sector-analysis', $post)) {
							                                        $primary_term_type_id = yoast_get_primary_term_id('sector-analysis', $post);
							                                        $postType = get_term( $primary_term_type_id );
							                                    } else {
							                                        if(get_the_terms( $post->ID, 'sector-analysis' )){
							                                            $termsType = get_the_terms( $post->ID, 'sector-analysis' );
							                                            foreach($termsType as $type) {
							                                                $postType = $type;
							                                            }
							                                        }
							                                    }?>
							                                    <a href="/market-narratives/sector-analysis/" class="topicFilterText">Sector Analysis</a>
							                                    <?php if($postType){?>
							                                        <a href="/market-narratives/sector-analysis/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
							                                    <?php } ?>
							                                </span>
							                                <a href="<?php the_permalink(); ?>" class="title"><?php echo get_the_title($post->ID); ?></a>
							                                <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?></span>
															<span class="excerpt">
																<?php if ( have_rows( 'preview_module', $post ) ) : ?>
												                   <?php while ( have_rows( 'preview_module', $post ) ) : the_row(); ?>
																	   <?php echo get_sub_field( 'overview_text' ); ?>
												                    <?php endwhile; ?>
												                <?php else : ?>
												                    <?php echo wp_trim_words( get_the_excerpt($post->ID), 25, '...' );?>
												                <?php endif; ?>
															</span>
															<a href="<?php echo esc_url( get_permalink() ); ?>" class="button red-button">View Dataset</a>
														</span>
													</span>
					                            </div>
											</div>
										</div>
									<?php wp_reset_postdata(); ?>
								<?php endif; ?>
							<?php endwhile; ?>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		</section>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	<?php if ( have_rows( 'sector' ) ) : ?>
		<?php while ( have_rows( 'sector' ) ) : the_row(); ?>
			<?php get_template_part( 'templates/components/_sector-grid-portal' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<section class="portal postListing topicGrid sector-grid subTopic sector-container">
	        <div class="container">
				<?php $term_m = 'sector-analysis';
                $terms = get_terms( $term_m, array(
                    'hide_empty' => false,
                ) );

                $sectors = array();
                foreach( $terms as $term){
                    $sectors[] = $term->slug;
                } ?>
	            <div id="loop" class="gridWrapper">
					<?php $args = array(
	                    'post_type'      => 'post',
	                    'posts_per_page' => -1,
	                    'tax_query'      => array(
							'relation' => 'AND',
							array (
							 'taxonomy' => 'filter-types',
							 'field' => 'slug',
							 'terms'    => 'market-narratives'
						 	),
							array (
								'taxonomy' => 'market-narratives-subcategories',
								'field' => 'slug',
								'terms'    => 'sector-analysis'
							)	                        
	                        // array(
	                        //     'taxonomy' => 'sector-analysis',
	                        //     'field'    => 'slug',
	                        //     'terms' => $sectors,
		                    //     'operator' => 'IN'
	                        // )
	                    ),
	                );

	                $posts = new WP_Query( $args );
	                if( $posts->have_posts() ): ?>
	                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>

	                    <?php if(current_user_can('mepr_auth')) {?>
	                        <div class="item">
	                            <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
	                                <div class="bgContainer">
	                                    <?php if ( get_field( 'listing_image') ) { ?>
	                                        <?php $image = get_field( 'listing_image'); ?>
	                                    <?php } else { ?>
	                                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
	                                            <?php $image = get_field( 'video_poster'); ?>
	                                        <?php } else { ?>
	                                            <?php $image = get_field( 'featured_image'); ?>
	                                        <?php } ?>
	                                    <?php } ?>
	                                    <?php if ( have_rows( 'preview_module' ) ) : ?>
	                                       <?php while ( have_rows( 'preview_module' ) ) : the_row(); ?>
	                                           <?php if ( have_rows( 'slider_images' ) ) : ?>
	                                               <?php $imageCounter = 1; ?>
	                                               <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
	                                                   <?php if($imageCounter == 1){
	                                                       $image = get_sub_field( 'image' );
	                                                   }
	                                                   $imageCounter++; ?>
	                                               <?php endwhile; ?>
	                                           <?php else : ?>
	                                               <?php // no rows found ?>
	                                           <?php endif; ?>
	                                        <?php endwhile; ?>
	                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => '', 'class' => 'desktop' ) ); ?>
	                                        <span class="hover-container">
	                                            <?php if ($imageCounter) { ?>
	                                                <span class="slide-counter">1 OF <?php echo $imageCounter; ?></span>
	                                            <?php } ?>
	                                        <span>
	                                    <?php else : ?>
	                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
	                                        <span class="hover-container">

	                                        <span>
	                                    <?php endif; ?>
	                                </div>
	                            </a>
	                            <div class="textContainer">
	                                <span class="topicFilter">
	                                    <?php if (yoast_get_primary_term_id('topic')) {
	                                        $primary_term_topic_id = yoast_get_primary_term_id('topic');
	                                        $postTopic = get_term( $primary_term_topic_id );
	                                    } else {
	                                        if(get_the_terms( $post->ID, 'topic' )){
	                                            $terms = get_the_terms( $post->ID, 'topic' );
	                                            foreach($terms as $term) {
	                                                $postTopic = $term;
	                                            }
	                                        }
	                                    }?>

	                                    <?php if (yoast_get_primary_term_id('sector-analysis')) {
	                                        $primary_term_type_id = yoast_get_primary_term_id('sector-analysis');
	                                        $postType = get_term( $primary_term_type_id );
	                                    } else {
	                                        if(get_the_terms( $post->ID, 'sector-analysis' )){
	                                            $termsType = get_the_terms( $post->ID, 'sector-analysis' );
	                                            foreach($termsType as $type) {
	                                                $postType = $type;
	                                            }
	                                        }
	                                    }?>
	                                    <a href="/market-narratives/sector-analysis/" class="topicFilterText">Sector Analysis</a>
	                                    <?php if($postType){?>
	                                        <a href="/market-narratives/sector-analysis/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
	                                    <?php } ?>
	                                </span>
	                                <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
	                                <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?></span>
									<span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
	                                <a href="<?php the_permalink(); ?>" class="button data-set-button">View Dataset</a>
	                            </div>
	                        </div>

	                        <?php $counter++; ?>
	                    <?php } ?>

	                <?php endwhile; else : ?>
	                	<h3><?php esc_html_e( 'Sorry, no results found.' ); ?></h3>
	                <?php endif; ?>

	                <?php wp_reset_postdata(); wp_reset_query();?>

	            </div>
	        </div>
	    </section>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
