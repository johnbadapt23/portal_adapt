<?php
/**
 * Template Name: Home Template
 */

get_header();
?>
<?php
	$bannerSlides = get_field('banner_slides');
	$bannerButtons = get_field('banner_buttons');
?>

<?php if ( get_field ( 'show_loader' ) == 'yes' ) { ?>
	<span class="loading">
	    <img class="spinner" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logo-white-red.svg" width="86" height="13" loading="lazy" alt="Adapt Logo" />
	</span>
<?php } ?>



<main id="main" role="main" class="home<?php if ( get_field ( 'does_the_page_have_a_banner' ) == 'no' ) { ?> noBanner<?php } ?>">
	<h1 class="pageTitleLine"><?php echo esc_html( get_the_title() ); ?></h1>
	<?php if ( get_field ( 'back_button' ) == 'yes' ) { ?>
		<section class="backContainer">
			<div class="container">
				<a class="back-button" href="<?php echo esc_url( get_field('back_button_link') ); ?>">Back</a>
			</div>
		</section>
	<?php } ?>
	<?php if($bannerSlides) { ?>
		<section class="banner">
			<ul class="slides">
				<?php foreach($bannerSlides as $slide) { ?>
					<li style="background-image:url(<?php echo $slide['image']; ?>);">
						<?php if( $slide['dark_overlay'] == 'yes') { ?>
							<span class="dark-overlay"></span>
						<?php } ?>
						<?php
					$slide_image_attach_id = attachment_url_to_postid( $slide['image'] );
					if ( $slide_image_attach_id ) {
						echo wp_get_attachment_image( $slide_image_attach_id, 'full', false, array(
							'alt'   => 'Adapt - ' . get_the_title(),
							'style' => 'visibility:hidden; position:absolute; top:-10000px; left:-10000px;',
						) );
					} else {
						echo '<img src="' . esc_url( $slide['image'] ) . '" style="visibility:hidden; position:absolute; top:-10000px; left:-10000px;" loading="lazy" alt="Adapt - ' . esc_attr( get_the_title() ) . '" />';
					}
				?>
						<div class="container">
							<div class="content <?php echo $slide['text_layout']; ?>">
								<?php if($slide['title']) { ?>
									<div class="column title">
										<span class="title"><?php echo $slide['title']; ?></span>
									</div>
									<hr>
								<?php } ?>
								<?php if($slide['text']) { ?>
									<div class="column text">
										<span class="text"><?php echo $slide['text']; ?></span>
									</div>
								<?php } ?>
								<?php if($slide['buttons']) { ?>
									<span class="buttonBlock">
										<?php foreach($slide['buttons'] as $button) { ?>
											<a href="<?php echo esc_url( $button['link'] ); ?>" target="<?php echo $button['target']; ?>" class="stdBtn red"><?php echo $button['title']; ?></a>
										<?php } ?>
									</span>
								<?php } ?>
								<?php if($slide['video']) { ?>
									<span class="videoLink">
										<?php if ($slide['video'][0]['vimeo_code_popup']){ ?>
											<a href="https://vimeo.com/<?php echo $slide['video'][0]['vimeo_code_popup']; ?>" class="image popup-vimeo">
										<?php } else { ?>
											<a href="#" class="playBtn">
										<?php }?>
											<span class="icon">
												<img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" width="51" height="51" loading="lazy" alt="Play Icon" />
											</span>
											<span class="text">
												<span><?php if($slide['video'][0]['video_button_text']) { ?><?php echo $slide['video'][0]['video_button_text']; ?><?php } else { ?>Watch Video<?php } ?></span>
												<span><?php echo $slide['video'][0]['duration']; ?></span>
											</span>
										</a>
									</span>
								<?php } ?>


							</div>
							<?php if($slide['banner_buttons']) { ?>
								<span class="baseButtons">
									<span>
									<?php foreach($slide['banner_buttons'] as $button) { ?>
										<a href="<?php echo esc_url( $button['link'] ); ?>" target="<?php echo $button['target']; ?>">
											<span class="text"><?php echo $button['text']; ?></span>
											<span class="title"><?php echo $button['title']; ?></span>
										</a>
									<?php } ?>
									</span>
								</span>
							<?php } ?>
						</div>
					</li>
				<?php } ?>
				<?php foreach($bannerSlides as $slide) { ?>
					<div class="videoPlayerContainer">
						<?php if($slide['video']) { ?>
							<span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" width="25" height="25" loading="lazy" alt="Close" /></span>
							<div class="videoWrapper">
								<video width="100%" id="popupVideo" controls controlsList="nodownload">
									<source type="video/mp4" src="<?php echo esc_url( $slide['video'][0]['vimeo_code'] ); ?>" />
								</video>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</ul>
		</section>
	<?php } ?>
	<?php if ( get_field ( 'hidden_vimeo_embed_for_yoast_home' )) { ?>
		<span class="hiddenEmbed" style="display: none;"><?php echo get_field ( 'hidden_vimeo_embed_for_yoast_home' );?></span>
	<?php } ?>

	<?php if ( have_rows( 'content_blocks' ) ): ?>
		<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>

			<?php if ( get_row_layout() == 'logo_grid' ) : ?>

				<section class="logoGrid <?php echo get_sub_field( 'background_colour' ); ?>">
					<div class="container">
						<div class="titleBlock">
							<span class="title">
								<h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
							</span>

							<span class="description <?php echo get_sub_field( 'top_right_text_position' ); ?>">
								<h3><?php echo esc_html( get_sub_field( 'top_right_text' ) ); ?></h3>
							</span>
						</div>

						<?php if ( have_rows( 'logos' ) ) : ?>
							<div class="logoBlock">
								<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
									<div class="logo">
										<span class="logoContainer">
											<div class="image" style="background-image: url(<?php echo get_sub_field( 'logo' ); ?>);">
											</div>
										</span>
										<span class="logoTitle">
											<?php echo get_sub_field( 'title' ); ?>
										</span>
									</div>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>

						<?php if ( get_sub_field ( 'link_url' ) ) { ?>
							<a class="logoBlockLink <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
						<?php } ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'article_content' ) : ?>
				<section class="scrollPos repeatableSingle singlePost">
					<div class="container">
						<div class="post-inner">
							<div class="fullWidth article-content">
								<div class="articleWrapper">
									<?php echo get_sub_field( 'article_content' ); ?>
								</div>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'download_block_single' ) : ?>
				<?php get_template_part( 'templates/components/_download-block' ); ?>
			<?php elseif ( get_row_layout() == 'download_block_double' ) : ?>
				<?php get_template_part( 'templates/components/_download-block-two-columns' ); ?>
			<?php elseif ( get_row_layout() == 'download_block_triple' ) : ?>
				<?php get_template_part( 'templates/components/_download-block-three-columns' ); ?>
			<?php elseif ( get_row_layout() == 'video_grid_block_two_column' ) : ?>
				<?php get_template_part( 'templates/components/_video-block-two-columns' ); ?>
			<?php elseif ( get_row_layout() == 'video_grid_block_three_column' ) : ?>
				<?php get_template_part( 'templates/components/_video-block-three-columns' ); ?>
			<?php elseif ( get_row_layout() == 'two_column_card_block' ) : ?>
				<?php get_template_part( 'templates/components/_two-column-card' ); ?>

			<?php elseif ( get_row_layout() == 'feature_image_or_infogram' ) : ?>
				<section class="scrollPos fullImageInfogram">
					<div class="container">
						 <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
							 <div class="featureBlock">
								 <?php
					$inline_img_148_src = get_sub_field( 'image' );
					$inline_img_148_attach_id = $inline_img_148_src ? attachment_url_to_postid( $inline_img_148_src ) : 0;
					if ( $inline_img_148_attach_id ) {
						echo wp_get_attachment_image( $inline_img_148_attach_id, 'full', false, array( 'alt' => '', 'class' => 'featureImage' ) );
					} elseif ( $inline_img_148_src ) {
						echo '<img class="featureImage" src="' . esc_url( $inline_img_148_src ) . '" loading="lazy" alt="' . esc_attr( '' ) . '" />';
					}
				?>
							 </div>
						 <?php } else { ?>
							 <div class="infogram-container">
								 <?php echo get_sub_field( 'infogram' ); ?>
							 </div>
						 <?php } ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'counter_block' ) : ?>
				<?php get_template_part( 'templates/components/_counter-block' ); ?>

			<?php elseif ( get_row_layout() == 'repeatable_counter_block' ) : ?>
				<?php get_template_part( 'templates/components/_repeatable-counter-block' ); ?>

			<?php elseif ( get_row_layout() == 'related_articles_grid_block' ) : ?>
				<?php get_template_part( 'templates/components/_related-articles-grid-block' ); ?>

			<?php elseif ( get_row_layout() == 'related_articles' ) : ?>

	            <section class="relatedArticlesCarousel">
	                <div class="container">
	                    <div class="inner">
	            			<h2 class="relatedTitle"><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
	            			<?php if ( have_rows( 'related_articles' ) ) : ?>
	                            <div class="owl-carousel articlesCarousel">
	                                <?php while ( have_rows( 'related_articles' ) ) : the_row(); ?>
	                                    <?php $post_object = get_sub_field( 'article' ); ?>
	                                    <?php if ( $post_object ): ?>
	                                        <?php $post = $post_object; ?>
											  <?php setup_postdata( $post ); ?>
	                                        <a class="relatedArticle item" href="<?php the_permalink(); ?>">


	                                            <div class="imageContainer">
	                                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
	                                                    <div class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
	                                                        <?php if ( get_field ( 'podcast_file' ) ) { ?>
	                                                            <span class="podcast">
	                                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
	                                                            </span>
	                                                        <?php } ?>
	                                                    </div>
	                                                <?php } else { ?>
														<div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php echo get_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');"<?php } ?>>
	                                                        <?php if ( get_field ( 'podcast_file' ) ) { ?>
	                                                            <span class="podcast">
	                                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
	                                                            </span>
	                                                        <?php } ?>
	                                                    </div>
	                                                <?php } ?>
	                                            </div>

	                                            <span class="postDetails">
	                                                <span class="info">
														<?php
                                                        $term_m = 'topic';
                                                        ?>
                                                        <?php
                                                        $terms = get_the_terms( $post, 'topic' );
                                                        ?>
                                                        <?php if ( $terms ) { ?>
                                                            <?php $counterTopic = 0; ?>
                                                            <?php $len = count($terms); ?>
                                                            <?php foreach($terms as $term) { ?>
                                                                <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                                     <?php echo $term -> name; ?>
                                                                </span>
                                                                <?php $counterTopic++; ?>
                                                            <?php } ?>

                                                        <?php } else { ?>
														<span class="date">
														   <?php if( get_field('event_date')) { ?>
															  <?php echo esc_html( get_field('event_date') ); ?>
														  <?php } else { ?>
															  <?php echo esc_html( get_the_date('d.m.Y') ); ?>
														  <?php } ?>
													   </span>
													   <span class="readTime">
														   <?php echo esc_html( get_field( 'read_time' ) ); ?>
													   </span>
												   	<?php } ?>
	                                                </span>

	                                                <span class="articleLink"><?php echo esc_html( get_the_title() ); ?></span>

													<?php
	                                                    $post_tags = get_the_tags();
														$count=0;
	                                                ?>
	                                                <?php if ( $post_tags ) { ?>
	                                                    <div class="tags">
	                                                        <?php foreach( $post_tags as $tag ) { $count++; ?>
																<?php if ( $count <= 3 ) { ?>
																	<span>
		                                                                <?php echo esc_html( '#' . $tag->name ); ?>
		                                                            </span>
																<?php } ?>
	                                                        <?php } ?>
	                                                    </div>
	                                                <?php } ?>
	                                            </span>

	                                        </a>
	                                        <?php wp_reset_postdata(); ?>
	                                    <?php endif; ?>
	                                <?php endwhile; ?>
	                            </div>
	                        </div>
							<?php if ( have_rows( 'button_block' ) ) : ?>
								<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
									<div class="buttonBlock <?php echo get_sub_field('link_orientation'); ?>">
										<a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
									</div>
								<?php endwhile; ?>
							<?php endif; ?>
	                    </div>
	    			<?php endif; ?>
	            </section>

			<?php elseif ( get_row_layout() == 'related_articles_three_column_block' ) : ?>

				<section class="relatedArticlesThreeColumn">
					<div class="container">
						<div class="inner">
							<div class="column first">
								<h2 class="relatedTitle"><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
								<?php if ( get_sub_field ( 'see_more_link' ) ) { ?>
									<a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'see_more_link' ) ); ?>" target="_self"><?php echo get_sub_field( 'see_more_link_text' ); ?></a>
								<?php } ?>
							</div>
							<div class="column two">
			                    <?php if ( have_rows( 'related_articles_column_two' ) ) : ?>
			                        <div class="wrapper">
			                        	<?php while ( have_rows( 'related_articles_column_two' ) ) : the_row(); ?>
			                        		<?php $post_object = get_sub_field( 'article' ); ?>
			                        		<?php if ( $post_object ): ?>
			                        			<?php $post = $post_object; ?>

			                                    <div class="relatedArticle">
			                            			<?php setup_postdata( $post ); ?>
			                                        <span class="postDetails<?php if( get_field('read_time')) { ?><?php } else { ?> no-read-time<?php } ?>">
			                                            <span>
															<?php if( get_field('event_date')) { ?>
																<?php echo esc_html( get_field('event_date') ); ?>
															<?php } else { ?>
																<?php echo esc_html( get_the_date('d.m.Y') ); ?>
															<?php } ?>
			                                            </span>
														<?php if( get_field('read_time')) { ?>
				                                            <span>
				                                                <?php echo esc_html( get_field( 'read_time' ) ); ?>
				                                            </span>
														<?php } ?>
			                                        </span>
			                            			<a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_title() ); ?></a>

													<?php
	                                                    $post_tags = get_the_tags();
														$count=0;
	                                                ?>
	                                                <?php if ( $post_tags ) { ?>
	                                                    <div class="tags">
	                                                        <?php foreach( $post_tags as $tag ) { $count++; ?>
																<?php if ( $count <= 3 ) { ?>
																	<span>
		                                                                <?php echo esc_html( '#' . $tag->name ); ?>
		                                                            </span>
																<?php } ?>
	                                                        <?php } ?>
	                                                    </div>
	                                                <?php } ?>
			                                    </div>
			                                    <?php wp_reset_postdata(); ?>
			                        		<?php endif; ?>
			                        	<?php endwhile; ?>
			                        </div>
			                    <?php endif; ?>
			                </div>
							<?php if ( get_sub_field( 'featured_article_column_three' ) ) { ?>
								<div class="column three">
									<?php $post_object = get_sub_field( 'featured_article_column_three' ); ?>
									<?php if ( $post_object ): ?>
										<?php $post = $post_object; ?>
										<?php setup_postdata( $post ); ?>
										<a class="relatedArticle item" href="<?php the_permalink(); ?>">


											<div class="imageContainer">
												<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
													<div class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
														<?php if ( get_field ( 'podcast_file' ) ) { ?>
															<span class="podcast">
																<img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
															</span>
														<?php } ?>
													</div>
												<?php } else { ?>
													<div class="image" style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');">
														<?php if ( get_field ( 'podcast_file' ) ) { ?>
															<span class="podcast">
																<img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
															</span>
														<?php } ?>
													</div>
												<?php } ?>
											</div>

											<span class="postDetails">
												<span class="info">
													<span class="date">
														<?php echo esc_html( get_the_date('d.m.Y') ); ?>
													</span>
													<span class="readTime">
														<?php echo esc_html( get_field( 'read_time' ) ); ?>
													</span>
												</span>

												<span class="articleLink"><?php echo esc_html( get_the_title() ); ?></span>

												<?php
													$post_tags = get_the_tags();
												?>
												<?php if ( $post_tags ) { ?>
													<div class="tags">
														<?php foreach( $post_tags as $tag ) { ?>
															<span>
																<?php echo esc_html( '#' . $tag->name ); ?>
															</span>
														<?php } ?>
													</div>
												<?php } ?>
											</span>
										</a>
										<?php wp_reset_postdata(); ?>
									<?php endif; ?>
								</div>
							<?php } ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'two_column_text_block' ) : ?>
				<section class="twoColumnTextBlock <?php echo get_sub_field( 'background_colour' ); ?>">
					<div class="container">
						<div class="inner">
							<div class="titleBlock">
								<h2><?php echo get_sub_field( 'title' ); ?></h2>
								<hr>

							</div>
							<div class="textBlock">
								<?php echo get_sub_field( 'text_block' ); ?>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'text_image_block' ) : ?>
				<section class="halfHalfBlock <?php echo get_sub_field( 'background_colour' ); ?>">
					<div class="textBlock <?php echo get_sub_field( 'image_position' ); ?>">
						<div class="v-wrap">
							<div class="v-box">
								<h2><?php echo get_sub_field( 'title' ); ?></h2>
								<hr>
								<?php if ( get_sub_field ( 'text_block' ) ) { ?>
									<span class="desktopText"><?php echo get_sub_field( 'text_block' ); ?></span>
								<?php } ?>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink desktop <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="imageBlock <?php echo get_sub_field( 'image_position' ); ?>">
						<div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
						</div>
					</div>
					<div class="textBlock mobile">
						<div class="container">
							<div class="inner">
								<?php if ( get_sub_field ( 'text_block' ) ) { ?>
									<span class="mobileText"><?php echo get_sub_field( 'text_block' ); ?></span>
								<?php } ?>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
				<?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
			<?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>

				<section id="<?php echo esc_attr( get_sub_field( 'id' ) ); ?>" class="imageGridBlock standard logos">
					<div class="container">
						<div class="inner">

							<div class="titleBlock">
								<?php if ( get_sub_field ( 'block_title' ) ) { ?>
									<h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
									<span class="hrWrapper">
										<hr>
									</span>
								<?php } ?>
								<?php if ( get_sub_field ( 'description' ) ) { ?>
									<h3><?php echo get_sub_field( 'description' ); ?></h3>
								<?php } ?>
							</div>

							<?php if ( have_rows( 'logos' ) ) : ?>
								<div class="gridWrapper">
									<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
										<div class="item">
											<div class="imageContainer">
												<div class="image" style="background-image: url(<?php echo get_sub_field( 'logo' ); ?>);">
												</div>
											</div>
										</div>
									<?php endwhile; ?>
									<div class="item">
										<div class="v-wrap">
											<div class="v-box">
												<span class="yourLogoHere">Your Company Here</span>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'speaker_quote_carousel' ) : ?>
				<section class="speakerQuoteCarousel">
					<div class="container">
						<div class="inner">
							<div class="titleBlock">
								<h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
								<?php } ?>
							</div>

							<?php if ( have_rows( 'item' ) ) : ?>
								<div class="owl-carousel speaker-gallery">
									<?php while ( have_rows( 'item' ) ) : the_row(); ?>
										<div class="item">
											<div class="imageContainer">
												<div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
												</div>
											</div>
											<div class="textBlock">
												<div class="v-wrap">
													<div class="v-box">
														<span class="quoteBlock">
															<?php echo esc_html( get_sub_field( 'quote' ) ); ?>
														</span>
														<span class="quoteAuthor">
															<?php echo esc_html( get_sub_field( 'quote_author' ) ); ?>
														</span>
													</div>
												</div>
											</div>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>

						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
				<?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>

			<?php elseif ( get_row_layout() == 'quote_block_with_no_image' ) : ?>
				<section class="quoteBlockNoImage <?php echo get_sub_field( 'background_colour' ); ?>">
					<div class="container">
						<div class="inner">

							<?php if ( have_rows( 'quotes' ) ) : ?>
								<div class="owl-carousel quote">
									<?php while ( have_rows( 'quotes' ) ) : the_row(); ?>
										<div class="item">
											<div class="v-wrap">
												<div class="v-box">
													<span class="quoteBlock">
														<?php echo esc_html( get_sub_field( 'quote' ) ); ?>
													</span>
													<span class="quoteAuthor">
														<?php echo esc_html( get_sub_field( 'quote_author' ) ); ?>
													</span>
												</div>
											</div>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>
			<?php elseif ( get_row_layout() == 'related_articles_taxonomies' ) : ?>
				<?php get_template_part( 'templates/components/_related-articles-taxonomies' ); ?>
			<?php elseif ( get_row_layout() == 'related_articles_taxonomies_grid_block' ) : ?>
				<?php get_template_part( 'templates/components/_related-articles-taxonomies-grid' ); ?>

			<?php elseif ( get_row_layout() == 'video_block' ) : ?>
				<section class="videoBlock" style="background-image: url(<?php echo get_sub_field('video_poster_image'); ?>);">
					<?php if( get_sub_field('dark_overlay') == 'yes') { ?>
						<span class="dark-overlay"></span>
					<?php } ?>
					<div class="container">
						<div class="content">
							<?php if( get_sub_field ( 'video_title' ) ) { ?>
								<div class="column title">
									<span class="title"><?php echo get_sub_field('video_title'); ?></span>
								</div>
								<hr>
							<?php } ?>
							<?php if( get_sub_field ( 'video_description' ) ) { ?>
								<div class="column text">
									<span class="text"><?php echo get_sub_field('video_description'); ?></span>
								</div>
							<?php } ?>
							<span class="videoLink">
								<?php if( get_field('vimeo_code_popup')){ ?>
				                    <a href="https://vimeo.com/<?php echo get_field('vimeo_code_popup'); ?>" class="image popup-vimeo">
				                <?php } else { ?>
									<a href="#" class="playBtnVideoBlock">
				                <?php } ?>
									<span class="icon">
										<img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" width="51" height="51" loading="lazy" alt="Play Icon" />
									</span>
									<span class="text">
										<span><?php if( get_sub_field('video_button_text')) { ?><?php echo get_sub_field('video_button_text') ?><?php } else { ?>Watch Video<?php } ?></span>
										<span><?php echo get_sub_field('video_duration') ?></span>
									</span>
								</a>
							</span>
						</div>
					</div>
					<div class="videoPlayerContainer videoBlock">
						<span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" width="25" height="25" loading="lazy" alt="Close" /></span>
						<div class="videoWrapper">
							<video width="100%" id="popupVideo" controls controlsList="nodownload">
								<source type="video/mp4" src="<?php echo esc_url( get_sub_field('vimeo_code') ); ?>" />
							</video>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
				 <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>

			<?php elseif ( get_row_layout() == 'two_column_block_with_text_and_featured_quote' ) : ?>
				<section class="twoColumnWithTextAndFeaturedQuote">
					<div class="container">
						<div class="inner">
							<div class="column first">
								<h2>
									<?php echo get_sub_field( 'title' ); ?>
								</h2>
								<div class="textBlock">
									<?php echo get_sub_field( 'text_block' ); ?>
								</div>
								<?php if ( get_sub_field ( 'link_url' ) ) { ?>
									<a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
								<?php } ?>
							</div>

							<div class="column last">
								<div class="item">
									<div class="v-wrap">
										<div class="v-box">
											<span class="quoteBlock">
												<?php echo esc_html( get_sub_field( 'quote' ) ); ?>
											</span>
											<span class="quoteAuthor">
												<?php echo esc_html( get_sub_field( 'quote_author' ) ); ?>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
                            <div class="buttonBlock">
                				<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                    <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'form_block' ) : ?>
				<section class="formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
					<div class="container">
						<div class="inner">
							<div class="formWrapper register">
								<?php if ( get_sub_field ( 'block_title' ) ) { ?>
									<h2><?php echo esc_html( get_sub_field('block_title') ); ?></h2>
									<?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
										<hr>
									<?php } ?>
								<?php } ?>
								<?php if ( get_sub_field ( 'block_description' ) ) { ?>
									<h3><?php echo get_sub_field('block_description'); ?></h3>
								<?php } ?>
								<?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
									<?php echo get_sub_field('form_shortcode'); ?>
								<?php }?>
								<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?>
									<a class="button popup-modal" href="#<?php echo get_sub_field('form_id'); ?>"><?php echo esc_html( get_sub_field('button_text') ); ?></a>
									<div class="formPopup mfp-hide" id="<?php echo esc_attr( get_sub_field('form_id') ); ?>">
										<a class="popup-modal-dismiss"></a>
										<?php if ( get_sub_field ( 'block_title' ) ) { ?>
											<h2><h2><?php echo esc_html( get_sub_field('block_title') ); ?></h2></h2>
										<?php } ?>
										<?php if ( get_sub_field ( 'block_description' ) ) { ?>
											<h3><?php echo get_sub_field('block_description'); ?></h3>
										<?php } ?>
											<div class="formWrapper register"><?php echo get_sub_field('form_shortcode'); ?></div>
									</div>
								<?php }?>
							</div>
						</div>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>

				<section class="textImageBlock <?php echo get_sub_field( 'background_colour' ); ?>">
					<div class="container">
						<div class="inner">
							<div class="title">
								<h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
								<hr>
							</div>
							<?php if ( have_rows( 'item' ) ) : ?>
								<div class="itemsWrapper">
									<?php while ( have_rows( 'item' ) ) : the_row(); ?>
										<div class="item">
											<?php if ( get_sub_field( 'image') ) { ?>
												<a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" target="<?php echo get_sub_field('link_target'); ?>" class="imageContainer">
													<div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
													</div>
												</a>
											<?php } ?>
											<span class="title"><?php echo get_sub_field( 'title' ); ?></span>
											<span class="description">
												<?php echo get_sub_field( 'text' ); ?>
											</span>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( have_rows( 'button_block' ) ) : ?>
							<div class="buttonBlock">
								<?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
									<a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>
					</div>
				</section>

			<?php elseif ( get_row_layout() == 'membership_block' ) : ?>
				<?php if ( get_sub_field ( 'display_membership_block' ) == 'yes' ) { ?>
					<section class="pricingBlock">
						<div class="container">
							<h2>Membership</h2>
							<?php if ( have_rows( 'first_pricing_block', 'option' ) ) : ?>
								<div class="pricingBlockItem first">
									<div class="innerWrapper">
										<?php while ( have_rows( 'first_pricing_block', 'option' ) ) : the_row(); ?>
											<span class="title">
												<?php echo get_sub_field( 'title', 'option' ); ?>
												<span class="hrWrapper">
													<hr>
												</span>
											</span>
											<span class="priceBlockWrapper">
												<span class="priceBlock">
													<span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
												</span>
											</span>
											<?php if ( have_rows( 'features', 'option' ) ) : ?>
												<div class="features">
													<?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
														<span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
													<?php endwhile; ?>
												</div>
											<?php endif; ?>
										</div>
										<span class="pricingButtonWrapper">
											<a class="small" href="<?php echo esc_url( get_sub_field( 'button_link', 'option' ) ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo esc_html( get_sub_field( 'button_text', 'option' ) ); ?></a>
										</span>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>

							<?php if ( have_rows( 'featured_pricing_block', 'option' ) ) : ?>
								<div class="pricingBlockItem featured">

									<?php while ( have_rows( 'featured_pricing_block', 'option' ) ) : the_row(); ?>
										<div class="innerWrapper">
											<div class="featuredWrapper">
												<span class="title">
													<?php echo get_sub_field( 'title', 'option' ); ?>
													<span class="hrWrapper">
														<hr>
													</span>
												</span>
												<span class="priceBlockWrapper">
													<span class="priceBlock">
														<span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
													</span>
												</span>
												<?php if ( have_rows( 'features', 'option' ) ) : ?>
													<div class="features">
														<?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
															<span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
														<?php endwhile; ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<span class="pricingButtonWrapper">
											<a class="small" href="<?php echo esc_url( get_sub_field( 'button_link', 'option' ) ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo esc_html( get_sub_field( 'button_text', 'option' ) ); ?></a>
										</span>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>

							<?php if ( have_rows( 'last_pricing_block', 'option' ) ) : ?>
								<div class="pricingBlockItem last">
									<?php while ( have_rows( 'last_pricing_block', 'option' ) ) : the_row(); ?>
										<div class="innerWrapper">
											<span class="title">
												<?php echo get_sub_field( 'title', 'option' ); ?>
												<span class="hrWrapper">
													<hr>
												</span>
											</span>
											<span class="priceBlockWrapper">
												<span class="priceBlock">
													<span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
												</span>
											</span>
											<?php if ( have_rows( 'features', 'option' ) ) : ?>
												<div class="features">
													<?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
														<span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
													<?php endwhile; ?>
												</div>
											<?php endif; ?>
										</div>
										<span class="pricingButtonWrapper">
											<a class="small" href="<?php echo esc_url( get_sub_field( 'button_link', 'option' ) ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo esc_html( get_sub_field( 'button_text', 'option' ) ); ?></a>
										</span>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
					</section>
				<?php } ?>

			<?php endif; ?>
		<?php endwhile; ?>

	<?php endif; ?>

</main>

<?php get_footer(); ?>
