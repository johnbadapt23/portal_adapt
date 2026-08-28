<?php
/**
 * Template Name: Next Conversation Template
 */

get_header();
?>


<main id="main" role="main" class="home next-conversation">
	<section class="filter-title-block ">
		<div class="container">
			<div class="title-container">
				<h1 class="type-title text-black"><?php echo get_field( 'next_conversation_title', 'options' ); ?></h1>
				<span class="type-description text-black"><?php echo get_field( 'next_conversation_text', 'options' ); ?></span>
			</div>
			<div class="topic-button-container-outer">
				<div class="topic-button-container filter-button-container">
					<a class="all filter-button selected" href="/tnc/">All</a>					
					<a href="/tnc/persona/" class="filter-button">Persona</a>
					<a href="/tnc/sector/" class="filter-button">Sector</a>
				</div>
			</div>           
		</div>            
	</section>
	<?php if ( have_rows( 'content' ) ): ?>
		<?php while ( have_rows( 'content' ) ) : the_row(); ?>
			<?php if ( get_row_layout() == 'featured' ) : ?>
				<?php if (!is_paged()) { ?>
					<section class="resources-featured featured-module filter-featured-post">
						<div class="container">
							<?php $post_object = get_sub_field( 'featured_post' ); ?>
							<?php if (get_sub_field( 'featured_or_most_recent' ) == 'featured') { ?>
								<div class="column one-half insights-featured-column">
									<?php if ( $post_object ): ?>
										<?php $post = $post_object; ?>
										<?php setup_postdata( $post ); ?>
										<?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
										<?php if ($video_link){ ?>
										<?php } else { ?>
											<?php $video_link = get_field( 'vimeo_code' ); ?>
										<?php } ?>
										<a href="<?php the_permalink(); ?>">
											<span class="video-container">										
												<div class="bg-container">
													<?php if ( get_field( 'listing_image') ) { ?>
														<?php $image = get_field( 'listing_image'); ?>
															<?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
													<?php } elseif ( get_field( 'video_image' )){ ?>
														<?php $video_image = get_field( 'video_image' ); ?>
														<?php
								$video_image_attach_id = attachment_url_to_postid( $video_image );
								if ( $video_image_attach_id ) {
									echo wp_get_attachment_image( $video_image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $video_image ) . '" loading="lazy" alt="" />';
								}
							?>
													<?php } else { ?>
														<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
															<?php $image = get_field( 'video_poster'); ?>
														<?php } else { ?>
															<?php $image = get_field( 'featured_image'); ?>
														<?php } ?>
														<?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
													<?php } ?>
												</div>										
											</span>
										</a>
										<span class="item-content-container">
											<span class="topic-filter">
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
												
												<?php if (yoast_get_primary_term_id('persona-mapping')) {
													$primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
													$postType = get_term( $primary_term_topic_id );
												} else {
													if(get_the_terms( $post->ID, 'persona-mapping' )){
														$terms = get_the_terms( $post->ID, 'persona-mapping' );
														foreach($terms as $term) {
															$postType = $term;
														}
													}
												}?>
												
												<?php if (yoast_get_primary_term_id('sector-analysis')) {
													$primary_term_topic_id = yoast_get_primary_term_id('sector-analysis');
													$postSector = get_term( $primary_term_topic_id );
												} else {
													if(get_the_terms( $post->ID, 'sector-analysis' )){
														$terms = get_the_terms( $post->ID, 'sector-analysis' );
														foreach($terms as $term) {
															$postSector = $term;
														}
													}
												}?>
												
												<?php if($postType){?>
														<a href="/persona-mapping/<?php echo $postType->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postType->name; ?></a>
												<?php } ?>
												<?php if($postSector){?>
														<a href="/data-insights/sector-analysis/<?php echo $postSector->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postSector->name; ?></a>
												<?php } ?>                                
												<?php if($postTopic){?>
													<a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topic-filter-text text-black black-text">/ <?php echo $postTopic->name; ?></a>
												<?php } ?>
											</span>
											<a href="<?php the_permalink(); ?>" class="title labelXLarge text-black"><?php the_title(); ?></a>
										</span>
										<?php wp_reset_postdata(); ?>
									<?php endif; ?>
								</div>
								<div class="side-bar-column one-half">
									<div class="recent-sidebar">
										<?php if ( have_rows( 'sidebar_posts' ) ) : ?>
											<?php while ( have_rows( 'sidebar_posts' ) ) : the_row(); ?>
												<?php if ( have_rows( 'posts' ) ) : ?>
													<?php while ( have_rows( 'posts' ) ) : the_row(); ?>
														<?php $post_object = get_sub_field( 'post' ); ?>
														<?php if ( $post_object ): ?>
															<?php $post = $post_object; ?>
															<?php setup_postdata( $post ); ?>
															<div class="resources-side-posts">
																<div class="resources-side-posts-inner">
																	<?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
																	<?php if ($video_link){ ?>
																	<?php } else { ?>
																		<?php $video_link = get_field( 'vimeo_code' ); ?>
																	<?php } ?>
																	<?php if ($video_link){ ?>
																		<a href="<?php the_permalink(); ?>">
																			<span class="video-container">
																				<span class="bg-container">
																					<?php $video_poster_image = get_field( 'video_poster' ); ?>
																					<?php if ( $video_poster_image ) { ?>
																						<?php
								$video_poster_image_attach_id = attachment_url_to_postid( $video_poster_image );
								if ( $video_poster_image_attach_id ) {
									echo wp_get_attachment_image( $video_poster_image_attach_id, 'full', false, array( 'alt' => '' ) );
								} else {
									echo '<img src="' . esc_url( $video_poster_image ) . '" loading="lazy" alt="" />';
								}
							?>
																					<?php } ?>
																					<?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
																						<span class="opacity-overlay"></span>
																					<?php } ?>
																					<span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
																					<?php if ($video_link){ ?>
																						<span class="video-button">
																						</span>
																					<?php } ?>
																				</span>
																			</span>
																		</a>
																	<?php } else { ?>
																		<span class="image-container">
																			<a href="<?php the_permalink(); ?>">
																				<span class="bg-container">
																					<?php $featured_image = get_field( 'featured_image' ); ?>
																					<?php if ( $featured_image ) { ?>
																						<?php
								$featured_image_attach_id = attachment_url_to_postid( $featured_image );
								if ( $featured_image_attach_id ) {
									echo wp_get_attachment_image( $featured_image_attach_id, 'full', false, array( 'alt' => '' ) );
								} else {
									echo '<img src="' . esc_url( $featured_image ) . '" loading="lazy" alt="" />';
								}
							?>
																					<?php } ?>
																				</span>
																			</a>
																		</span>
																	<?php } ?>
																	<div class="post-content-container">
																		<span class="topic-filter">
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
																			<?php if (yoast_get_primary_term_id('persona-mapping')) {
																				$primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
																				$postType = get_term( $primary_term_topic_id );
																			} else {
																				if(get_the_terms( $post->ID, 'persona-mapping' )){
																					$terms = get_the_terms( $post->ID, 'persona-mapping' );
																					foreach($terms as $term) {
																						$postType = $term;
																					}
																				}
																			}?>
																			
																			<?php if (yoast_get_primary_term_id('sector-analysis')) {
																				$primary_term_topic_id = yoast_get_primary_term_id('sector-analysis');
																				$postSector = get_term( $primary_term_topic_id );
																			} else {
																				if(get_the_terms( $post->ID, 'sector-analysis' )){
																					$terms = get_the_terms( $post->ID, 'sector-analysis' );
																					foreach($terms as $term) {
																						$postSector = $term;
																					}
																				}
																			}?>
																			<?php if($postType){?>
																					<a href="/persona-mapping/<?php echo $postType->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postType->name; ?></a>
																			<?php } ?>
																			<?php if($postSector){?>
																					<a href="/data-insights/sector-analysis/<?php echo $postSector->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postSector->name; ?></a>
																			<?php } ?>                                
																			<?php if($postTopic){?>
																				<a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topic-filter-text text-black black-text">/ <?php echo $postTopic->name; ?></a>
																			<?php } ?>
																		</span>
																		<a href="<?php the_permalink(); ?>" class="title text-black"><h2 class="title text-black labelLarge"><?php the_title(); ?></h2></a>
																	</div>
																</div>
															</div>
															<?php wp_reset_postdata(); ?>
														<?php endif; ?>
													<?php endwhile; ?>
												<?php else : ?>
													<?php // no rows found ?>
												<?php endif; ?>
											<?php endwhile; ?>
										<?php else : ?>
											<?php // no rows found ?>
										<?php endif; ?>
									</div>
								</div>
							<?php } else { ?>
								<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
								<?php
									$args = array(
										'post_type' => 'post',
										'posts_per_page' => 4,
										'paged'=> $paged,
										'tax_query' => array(
											'relation' => 'AND',
											array (
												'taxonomy' => 'filter-types',
												'field' => 'slug',
												'terms' => 'tnc',
											)
										)
									);
									$posts = new WP_Query( $args ); ?>
									<?php if( $posts->have_posts() ): ?>
										<?php $peerCounter = 1; ?>
										<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
											<?php if($peerCounter == 1){ ?>
												<div class="column one-half insights-featured-column">
													<?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
													<?php if ($video_link){ ?>
													<?php } else { ?>
														<?php $video_link = get_field( 'vimeo_code' ); ?>
													<?php } ?>
													<a href="<?php the_permalink(); ?>">
														<span class="video-container">										
															<div class="bg-container">
																<?php if ( get_field( 'listing_image') ) { ?>
																	<?php $image = get_field( 'listing_image'); ?>
																		<?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
																<?php } elseif ( get_field( 'video_image' )){ ?>
																	<?php $video_image = get_field( 'video_image' ); ?>
																	<?php
								$video_image_attach_id = attachment_url_to_postid( $video_image );
								if ( $video_image_attach_id ) {
									echo wp_get_attachment_image( $video_image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $video_image ) . '" loading="lazy" alt="" />';
								}
							?>
																<?php } else { ?>
																	<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
																		<?php $image = get_field( 'video_poster'); ?>
																	<?php } else { ?>
																		<?php $image = get_field( 'featured_image'); ?>
																	<?php } ?>
																	<?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
																<?php } ?>
															</div>										
														</span>
													</a>
													<span class="item-content-container">
														<span class="topic-filter">
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
															<?php if (yoast_get_primary_term_id('persona-mapping')) {
																$primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
																$postType = get_term( $primary_term_topic_id );
															} else {
																if(get_the_terms( $post->ID, 'persona-mapping' )){
																	$terms = get_the_terms( $post->ID, 'persona-mapping' );
																	foreach($terms as $term) {
																		$postType = $term;
																	}
																}
															}?>
															
															<?php if (yoast_get_primary_term_id('sector-analysis')) {
																$primary_term_topic_id = yoast_get_primary_term_id('sector-analysis');
																$postSector = get_term( $primary_term_topic_id );
															} else {
																if(get_the_terms( $post->ID, 'sector-analysis' )){
																	$terms = get_the_terms( $post->ID, 'sector-analysis' );
																	foreach($terms as $term) {
																		$postSector = $term;
																	}
																}
															}?>
															<?php if($postType){?>
																	<a href="/persona-mapping/<?php echo $postType->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postType->name; ?></a>
															<?php } ?>
															<?php if($postSector){?>
																	<a href="/data-insights/sector-analysis/<?php echo $postSector->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postSector->name; ?></a>
															<?php } ?>                                
															<?php if($postTopic){?>
																<a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topic-filter-text text-black black-text">/ <?php echo $postTopic->name; ?></a>
															<?php } ?>
														</span>
														<a href="<?php the_permalink(); ?>" class="title labelXLarge text-black"><?php the_title(); ?></a>
													</span>

												</div>
												<div class="side-bar-column one-half">
													<div class="recent-sidebar">
											<?php } else { ?>
												<div class="resources-side-posts">
													<div class="resources-side-posts-inner">
														<?php $video_link = get_field( 'featured_video_vimeo_code' ); ?>
														<?php if ($video_link){ ?>
														<?php } else { ?>
															<?php $video_link = get_field( 'vimeo_code' ); ?>
														<?php } ?>
														<?php if ($video_link){ ?>
															<a href="<?php the_permalink(); ?>">
																<span class="video-container">
																	<span class="bg-container">
																		<?php $video_poster_image = get_field( 'video_poster' ); ?>
																		<?php if ( $video_poster_image ) { ?>
																			<?php
								$video_poster_image_attach_id = attachment_url_to_postid( $video_poster_image );
								if ( $video_poster_image_attach_id ) {
									echo wp_get_attachment_image( $video_poster_image_attach_id, 'full', false, array( 'alt' => '' ) );
								} else {
									echo '<img src="' . esc_url( $video_poster_image ) . '" loading="lazy" alt="" />';
								}
							?>
																		<?php } ?>
																		<?php if ( get_field( 'video_opacity_overlay' ) == 'overlay-opacity') { ?>
																			<span class="opacity-overlay"></span>
																		<?php } ?>
																		<span class="video-play-time"><?php echo get_field( 'video_time' ); ?></span>
																		<?php if ($video_link){ ?>
																			<span class="video-button">
																			</span>
																		<?php } ?>
																	</span>
																</span>
															</a>
														<?php } else { ?>
															<span class="image-container">
																<a href="<?php the_permalink(); ?>">
																	<span class="bg-container">
																		<?php $featured_image = get_field( 'featured_image' ); ?>
																		<?php if ( $featured_image ) { ?>
																			<?php
								$featured_image_attach_id = attachment_url_to_postid( $featured_image );
								if ( $featured_image_attach_id ) {
									echo wp_get_attachment_image( $featured_image_attach_id, 'full', false, array( 'alt' => '' ) );
								} else {
									echo '<img src="' . esc_url( $featured_image ) . '" loading="lazy" alt="" />';
								}
							?>
																		<?php } ?>
																	</span>
																</a>
															</span>
														<?php } ?>
														<div class="post-content-container">
															<span class="topic-filter">
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
																<?php if (yoast_get_primary_term_id('persona-mapping')) {
																	$primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
																	$postType = get_term( $primary_term_topic_id );
																} else {
																	if(get_the_terms( $post->ID, 'persona-mapping' )){
																		$terms = get_the_terms( $post->ID, 'persona-mapping' );
																		foreach($terms as $term) {
																			$postType = $term;
																		}
																	}
																}?>
																
																<?php if (yoast_get_primary_term_id('sector-analysis')) {
																	$primary_term_topic_id = yoast_get_primary_term_id('sector-analysis');
																	$postSector = get_term( $primary_term_topic_id );
																} else {
																	if(get_the_terms( $post->ID, 'sector-analysis' )){
																		$terms = get_the_terms( $post->ID, 'sector-analysis' );
																		foreach($terms as $term) {
																			$postSector = $term;
																		}
																	}
																}?>
																<?php if($postType){?>
																		<a href="/persona-mapping/<?php echo $postType->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postType->name; ?></a>
																<?php } ?>
																<?php if($postSector){?>
																		<a href="/data-insights/sector-analysis/<?php echo $postSector->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postSector->name; ?></a>
																<?php } ?>                                
																<?php if($postTopic){?>
																	<a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topic-filter-text text-black black-text">/ <?php echo $postTopic->name; ?></a>
																<?php } ?>
															</span>
															<a href="<?php the_permalink(); ?>" class="title text-black"><h2 class="title text-black labelLarge"><?php the_title(); ?></h2></a>
														</div>
													</div>
												</div>
											<?php }?>
										<?php $peerCounter++; ?>
										<?php endwhile; ?>
												</div>
											</div>
										<?php wp_reset_postdata(); ?>
									<?php endif; ?>
							<?php } ?>
						</div>
					</section>
				<?php } ?>
			<?php elseif ( get_row_layout() == 'post_list' ) : ?>
				<section class="filter-listing conversation-listing">
					<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
					<div class="container">
						<div class="grid-wrapper" id="loop">
							<?php $args = array(
								'post_type' => 'post',
								'posts_per_page' => 9,
								'paged'=> $paged,
								'tax_query' => array(
									'relation' => 'AND',
									array (
										'taxonomy' => 'filter-types',
										'field' => 'slug',
										'terms' => 'tnc'
									),
								)
							);
							$posts = new WP_Query( $args ); ?>
							<?php if( $posts->have_posts() ): ?>
								<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
									<div class="item one-third peer-insights-item">
										<a href="<?php the_permalink(); ?>" class="imageSizeContainer">
											<div class="bgContainer">
												<?php if ( get_field( 'listing_image') ) { ?>
													<?php $image = get_field( 'listing_image'); ?>
														<?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
												<?php } elseif ( get_field( 'video_image' )){ ?>
													<?php $video_image = get_field( 'video_image' ); ?>
													<?php
								$video_image_attach_id = attachment_url_to_postid( $video_image );
								if ( $video_image_attach_id ) {
									echo wp_get_attachment_image( $video_image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $video_image ) . '" loading="lazy" alt="" />';
								}
							?>
												<?php } else { ?>
													<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
														<?php $image = get_field( 'video_poster'); ?>
													<?php } else { ?>
														<?php $image = get_field( 'featured_image'); ?>
													<?php } ?>
													<?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
												<?php } ?>
											</div>
										</a>
										<span class="item-content-container">
											<span class="topic-filter">
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
												<?php if($q->slug == 'persona'){ ?> 
													<?php if (yoast_get_primary_term_id('persona-mapping')) {
														$primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
														$postType = get_term( $primary_term_topic_id );
													} else {
														if(get_the_terms( $post->ID, 'persona-mapping' )){
															$terms = get_the_terms( $post->ID, 'persona-mapping' );
															foreach($terms as $term) {
																$postType = $term;
															}
														}
													}?>
												<?php } ?>
												<?php if($q->slug == 'sector'){ ?> 
													<?php if (yoast_get_primary_term_id('sector-analysis')) {
														$primary_term_topic_id = yoast_get_primary_term_id('sector-analysis');
														$postSector = get_term( $primary_term_topic_id );
													} else {
														if(get_the_terms( $post->ID, 'sector-analysis' )){
															$terms = get_the_terms( $post->ID, 'sector-analysis' );
															foreach($terms as $term) {
																$postSector = $term;
															}
														}
													}?>
												<?php } ?>
												<?php if($postType){?>
														<a href="/persona-mapping/<?php echo $postType->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postType->name; ?></a>
												<?php } ?>
												<?php if($postSector){?>
														<a href="/data-insights/sector-analysis/<?php echo $postSector->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postSector->name; ?></a>
												<?php } ?>                                
												<?php if($postTopic){?>
													<a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topic-filter-text text-black black-text">/ <?php echo $postTopic->name; ?></a>
												<?php } ?>
											</span>
											<a href="<?php the_permalink(); ?>" class="title labelXLarge text-black"><?php the_title(); ?></a>
										</span>
									</div>                               
								<?php endwhile; ?>                        
							<?php endif;?>
						</div>
						<div class="page-navi-container">
							<?php wp_pagenavi( array( 'query' => $posts ) ); ?>
								<?php wp_reset_postdata(); ?>
							<?php wp_reset_query(); ?>
						</div>
					</div>
					
				</section>
			<?php elseif ( get_row_layout() == 'cta_banner' ) : ?>
				<?php if (!is_paged()) { ?>
					<section class="conversation-cta <?php echo get_sub_field( 'background_colour' ); ?>">
						<?php $textcolour = 'text-white'; ?>
						<?php if (get_sub_field( 'background_colour' ) == 'background-white') {
							$textcolour = 'text-black';
						} else { 
							$textcolour = 'text-white';
						}
						?>
						<div class="container">
							<div class="column-container">
								<div class="column text-column one-half">
									<h3 class="<?php echo $textcolour; ?> cta-title"><?php echo get_sub_field( 'title' ); ?></h3>
									<span class="text labelLarge <?php echo $textcolour; ?>"><?php echo get_sub_field( 'text' ); ?></span>
									<?php if ( have_rows( 'link' ) ) : ?>
										<?php while ( have_rows( 'link' ) ) : the_row(); ?>
											<span class="button-container">
												<span class="pre-button-text labelLarge <?php echo $textcolour; ?>"><?php echo get_sub_field( 'pre_button_text' ); ?></span>
												<a class="cta-button button stdBtn red red-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
											</span>
										<?php endwhile; ?>
									<?php else : ?>
										<?php // no rows found ?>
									<?php endif; ?>
								</div>
								<div class="column image-column">
									<div class="image-container">
										<div class="bg-container">
											<?php $image = get_sub_field( 'image' ); ?>
											<?php if ( $image ) { ?>
												<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => '' ) ); ?>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				<?php } ?>
			<?php endif; ?>
		<?php endwhile; ?>
	<?php else: ?>
		<?php // no layouts found ?>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
