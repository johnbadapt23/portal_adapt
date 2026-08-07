<?php
/**
 * Template Name: Portal Flexible Template
 */

get_header();
?>

<!-- The only <h1> this page previously had came from the CustomGPT chat
widget below ("ADAPT IntelligenceBETA"), which describes the chat widget,
not this page. Screen readers and search engines had no heading that
actually identifies the page. Visually hidden so the design is unaffected. -->
<h1 class="sr-only"><?php echo esc_html( get_the_title() ?: 'Portal Home' ); ?></h1>

<style>
.cgptcb-chat-bubble, .cgptcb-tooltip{
	display: none !important;
}
</style>
<!-- <div class="custom-gpt-wrapper-header">
	<h2><span>ADAPT</span> Intelligence</h2>
	<p>Find the insight. Frame your message. All in one place.</p>
</div>
<div class="custom-gpt-wrapper">
	<div class="custom-gpt-main-wrapper">
		<div id="customgpt_chat"></div>
	</div>
</div> -->



<?= do_shortcode('[customgpt_chat mode="embedded"]'); ?>
<!-- <script src="https://cdn.customgpt.ai/js/embed.js" defer div_id="customgpt_chat" p_id="98043" p_key="8c7e9ac540d9dd825d6cf4eab0ade038"></script>  -->
<!-- <script src="https://cdn.customgpt.ai/js/embed.js" defer div_id="customgpt_chat" p_id="98865" p_key="f12d51cc482847f28a6333cf7f6a5c9d"></script>  -->

<?php if ($membershipType == 'free-trial') { ?>
	<main id="main" role="main" class="home freeTrial">
		<?php if ( have_rows( 'trial_membership_content_blocks' ) ) : ?>
			<?php $flexibleCounter = 1; ?>
			<?php while ( have_rows( 'trial_membership_content_blocks' ) ) : the_row(); ?>
				<?php $trialMembership = get_sub_field( 'membership_id' ); ?>
				<?php if(current_user_can('mepr-active','memberships:' . $trialMembership)){ ?>
					<?php if ( have_rows( 'membership_content' ) ): ?>
    					<?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
    						<?php if ( get_row_layout() == 'featured_presentation' ) : ?>
								<?php $presentation = get_sub_field( 'presentation' ); ?>
								<?php if ( $presentation ): ?>
									<?php foreach ( $presentation as $post ):  ?>
										<?php setup_postdata ( $post ); ?>
											<section class="expertPresentationFeatured trial-featured bg-dark">
												<div class="container">
													<h2><?php echo get_sub_field( 'title' ); ?></h2>
													<div class="imageSizeContainer">
														<span class="overlayGradient"></span>
														<a href="<?php the_permalink(); ?>" target="_self" class="bgContainer">
															<?php if ( get_field( 'listing_image') ) { ?>
																<?php $image = get_field( 'listing_image'); ?>
																 <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
															<?php } elseif ( get_field( 'video_image' )){  ?>
																<?php $video_image = get_field( 'video_image' ); ?>
																<?php echo wp_get_attachment_image( $video_image['ID'], 'full', false, array( 'alt' => $video_image['alt'], 'class' => 'desktop' ) ); ?>
															<?php } else { ?>
																<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
																	<?php $image = get_field( 'video_poster'); ?>
																<?php } else { ?>
																	<?php $image = get_field( 'featured_image'); ?>
																<?php } ?>
																<?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
															<?php } ?>
														</a>
														<span class="watchIcon"></span>
														<span class="textContainer">
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

																<?php if (yoast_get_primary_term_id('filter-types')) {
																	$primary_term_type_id = yoast_get_primary_term_id('filter-types');
																	$postType = get_term( $primary_term_type_id );
																} else {
																	if(get_the_terms( $post->ID, 'filter-types' )){
																		$termsType = get_the_terms( $post->ID, 'filter-types' );
																		foreach($termsType as $type) {
																			$postType = $type;
																		}
																	}
																}?>
																<?php if($postTopic){?>
																	<a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
																<?php } ?>

																	<a href="/filter-types/expert-presentations/" class="topicFilterText">Expert Presentations</a>
															</span>
															<a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
														</span>
													</div>
												</div>
											</section>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php wp_reset_postdata(); ?>
								<?php wp_reset_query(); ?>
							<?php elseif ( get_row_layout() == 'banner' ) : ?>
								<?php if ( $flexibleCounter == 1 ) { ?>
									<section class="topicBanner">
							        <div class="imageSizeContainer">
							            <div class="bgContainer">
							    			<?php $banner_image =  get_sub_field( 'background_image' ); ?>
							                <?php echo wp_get_attachment_image( $banner_image['ID'], 'full', false, array( 'alt' => $banner_image['alt'], 'class' => 'desktop' ) ); ?>
							            </div>
							            <div class="container">
							                <h1><?php echo get_sub_field( 'title' ); ?></h1>
							                <p><?php echo get_sub_field( 'description' ); ?></p>
							            </div>
							        </div>
							    </section>
								<?php } ?>
    						<?php elseif ( get_row_layout() == 'featured_grid_module' ) : ?>
    							<?php $subscription_term = get_sub_field( 'subscription' ); ?>
    							<?php if ( $subscription_term ): ?>
									<section class="portal topicGrid bg-dark trial-grid">
		                                <div class="container">
		                                    <div class="blockTitle">
		                                        <h2><?php echo get_sub_field( 'title' ); ?></h2>
		                                    </div>
		                                    <div class="gridWrapper">
		                                        <?php
		                                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		                                            $args = array(
		                                                'post_type'      => 'post',
		                                                'posts_per_page' => -1,
		                                                'paged'=> $paged,
		                                                'tax_query'      => array(
		                                                    array(
		                                                        'taxonomy' => 'subscription',
		                                                        'field'    => 'slug',
		                                                        'terms'    => $subscription_term->slug
		                                                    )
		                                                ),
		                                            );

		                                            $posts = new WP_Query( $args );
		                                            if( $posts->have_posts() ): ?>
		                                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
															<div class="item">
 		                                                       <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
 		                                                           <div class="bgContainer">
 		                                                               <?php if ( get_field( 'listing_image') ) { ?>
 		                                                                   <?php $image = get_field( 'listing_image'); ?>
 		                                                                    <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
 		                                                               <?php } elseif ( get_field( 'video_image' )){  ?>
 		                                                                   <?php $video_image = get_field( 'video_image' ); ?>
 		                                                                   <?php echo wp_get_attachment_image( $video_image['ID'], 'full', false, array( 'alt' => $video_image['alt'], 'class' => 'desktop' ) ); ?>
 		                                                               <?php } else { ?>
 		                                                                   <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
 		                                                                       <?php $image = get_field( 'video_poster'); ?>
 		                                                                   <?php } else { ?>
 		                                                                       <?php $image = get_field( 'featured_image'); ?>
 		                                                                   <?php } ?>
 		                                                                   <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
 		                                                               <?php } ?>
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

 		                                                               <?php if (yoast_get_primary_term_id('filter-types')) {
 		                                                                   $primary_term_type_id = yoast_get_primary_term_id('filter-types');
 		                                                                   $postType = get_term( $primary_term_type_id );
 		                                                               } else {
 		                                                                   if(get_the_terms( $post->ID, 'filter-types' )){
 		                                                                       $termsType = get_the_terms( $post->ID, 'filter-types' );
 		                                                                       foreach($termsType as $type) {
 		                                                                           $postType = $type;
 		                                                                       }
 		                                                                   }
 		                                                               }?>
 		                                                               <?php if($postTopic){?>
 		                                                                   <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
 		                                                               <?php } ?>
 		                                                               <a href="/filter-types/expert-presentations/" class="topicFilterText">Expert Presentations</a>

 		                                                           </span>
 		                                                           <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
 		                                                           <span class="dateReadTime"><span class="dateRead"><?php echo get_the_date('M j, Y'); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
 		                                                           <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
 		                                                       </div>
 		                                                   </div>
		                                                <?php endwhile; ?>
		                                            <?php endif;?>
		                                            <?php wp_reset_postdata(); ?>
		                                            <?php wp_reset_query(); ?>
		                                    </div>
		                                </div>
		                            </section>
    							<?php endif; ?>
    						<?php elseif ( get_row_layout() == 'cta_block' ) : ?>
								<?php if ( $flexibleCounter == 1 ) { ?>
									<section class="resources-cta-block">
								    <div class="container">
								        <div class="cta-content">
								            <div class="column text-column one-half">
								        		<span class="cta-title"><?php echo get_sub_field( 'title' ); ?></span>
								        		<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
								        		<?php if ( have_rows( 'button' ) ) : ?>
								                    <span class="button-container">
								            			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
								                            <a class="std-button arrow-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
								            			<?php endwhile; ?>
								                    </span>
								        		<?php else : ?>
								        			<?php // no rows found ?>
								        		<?php endif; ?>
								            </div>
								            <div class="column image-column one-half">
								                <div class="bottom-image-container full-width-image">
								            		<?php $image = get_sub_field( 'image' ); ?>
								                    <div class="main-image-container">
								                		<?php if ( $image ) { ?>
								                			<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
								                		<?php } ?>
								                    </div>
								                    <span class="overlay-image-container">
								                        <?php $overlay_image = get_sub_field( 'overlay_image' ); ?>
								            			<?php if ( $overlay_image ) { ?>
								            				<?php echo wp_get_attachment_image( $overlay_image['ID'], 'full', false, array( 'alt' => $overlay_image['alt'] ) ); ?>
								            			<?php } ?>
								                    </span>
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
					<?php $flexibleCounter++; ?>
			    <?php } ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
	</main>
<?php } else { ?>
	<?php if ($membershipType == 'advantage') { ?>
		<main id="main" role="main" class="home noBanner advantageHome">
			<?php if ( have_rows( 'advantage_home_content_blocks' ) ): ?>
				<?php while ( have_rows( 'advantage_home_content_blocks' ) ) : the_row(); ?>
					<?php if ( get_row_layout() == 'featured_posts' ) : ?>
						<?php get_template_part( 'templates/post-components/_resources-featured-block' ); ?>
					<?php elseif ( get_row_layout() == 'slider_block' ) : ?>
						<?php get_template_part( 'templates/post-components/_keynote-slider' ); ?>
					<?php elseif ( get_row_layout() == 'post_slider' ) : ?>
						<?php get_template_part( 'templates/post-components/_post-slider' ); ?>
					<?php elseif ( get_row_layout() == 'upcoming_events' ) : ?>
						<?php get_template_part( 'templates/post-components/_events-slider' ); ?>
					<?php elseif ( get_row_layout() == 'two_column_accordion' ) : ?>
						<?php get_template_part( 'templates/post-components/_two-column-accordion' ); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php else: ?>
				<?php // no layouts found ?>
			<?php endif; ?>
		</main>
	<?php } else { ?>
		<main id="main" role="main" class="home noBanner advantageHome professionalHome">
			<?php if ( have_rows( 'it_pro_home_content_blocks' ) ): ?>
				<?php while ( have_rows( 'it_pro_home_content_blocks' ) ) : the_row(); ?>
					<?php if ( get_row_layout() == 'featured_posts' ) : ?>
						<?php get_template_part( 'templates/post-components/_resources-featured-block' ); ?>
					<?php elseif ( get_row_layout() == 'slider_block' ) : ?>
						<?php get_template_part( 'templates/post-components/_keynote-slider' ); ?>
					<?php elseif ( get_row_layout() == 'post_slider' ) : ?>
						<?php get_template_part( 'templates/post-components/_post-slider' ); ?>
					<?php elseif ( get_row_layout() == 'advisors_carousel' ) : ?>
						<?php get_template_part( 'templates/post-components/_advisors-carousel' ); ?>
					<?php elseif ( get_row_layout() == 'upcoming_events' ) : ?>
						<?php get_template_part( 'templates/post-components/_events-slider' ); ?>
					<?php elseif ( get_row_layout() == 'benchmarks_module' ) : ?>
						<?php get_template_part( 'templates/post-components/_benchmark-two-column' ); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php else: ?>
				<?php // no layouts found ?>
			<?php endif; ?>
		</main>
	<?php } ?>
<?php } ?>

<?php get_footer(); ?>