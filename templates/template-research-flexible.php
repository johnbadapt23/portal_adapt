<?php
/**
 * Template Name: Research Template
 */

get_header();
?>
<main id="main" role="main" class="home research-flexible">
	<section class="research-top-navigation">
		<div class="container">
			<span class="breadcrumb-container">
				<a class="home-link" href="/" target="_self">Home</a>
				<span class="divider">/</span>
				<span class="title"><?php echo esc_html( get_the_title() ); ?></span>
			</span>
			<span class="title-container">
				<h1 clas="h2-style"><?php echo esc_html( get_the_title() ); ?></h1>
			</span>
			<div class="navigation-container">
				<?php if ( have_rows( 'topics_column_one', 'option' ) ) : ?>
					<?php while ( have_rows( 'topics_column_one', 'option' ) ) : the_row(); ?>
						<?php if ( have_rows( 'group' ) ) : ?>
							<?php while ( have_rows( 'group' ) ) : the_row(); ?>
								<div class="column active">
									<span class="dropDownSection">
										<?php $icon = get_sub_field( 'icon' ); ?>
										<span class="columnTitle">
											<?php if ( $icon ) { ?>
												<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, [ 'alt' => $icon['alt'], 'class' => 'topic-icon' ] ); ?>
											<?php } ?>
											<?php echo esc_html( get_sub_field( 'title' ) ); ?>
										</span>
										<?php if ( have_rows( 'link' ) ) : ?>
											<ul>
												<?php while ( have_rows( 'link' ) ) : the_row(); ?>
													<?php $topic_link_term = get_sub_field( 'topic_link' ); ?>
													<?php if ( $topic_link_term ): ?>
														<li>
															<?php $topic_link_term_link = get_term_link( $topic_link_term ); ?>
															<?php if ( ! is_wp_error( $topic_link_term_link ) ) : ?>
															<a href="<?php echo esc_url( $topic_link_term_link ); ?>"><?php echo esc_html( $topic_link_term->name ); ?></a>
															<?php endif; ?>
														</li>
													<?php endif; ?>
												<?php endwhile; ?>
											</ul>
										<?php else : ?>
											<?php // no rows found ?>
										<?php endif; ?>
									</span>
								</div>
							<?php endwhile; ?>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
				<?php if ( have_rows( 'topics_column_two', 'option' ) ) : ?>
					<?php while ( have_rows( 'topics_column_two', 'option' ) ) : the_row(); ?>
						<?php if ( have_rows( 'group' ) ) : ?>
							<?php while ( have_rows( 'group' ) ) : the_row(); ?>
								<div class="column">
									<span class="dropDownSection">
										<?php $icon = get_sub_field( 'icon' ); ?>
										<span class="columnTitle">
											<?php if ( $icon ) { ?>
												<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, [ 'alt' => $icon['alt'], 'class' => 'topic-icon' ] ); ?>
											<?php } ?>
											<?php echo esc_html( get_sub_field( 'title' ) ); ?>
										</span>
										<?php if ( have_rows( 'link' ) ) : ?>
											<ul>
												<?php while ( have_rows( 'link' ) ) : the_row(); ?>
													<?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
														<?php $type_link_term = get_sub_field( 'type_link' ); ?>
														<?php if ( $type_link_term ): ?>
															<li>
																<?php $type_link_term_link = get_term_link( $type_link_term ); ?>
																<?php if ( ! is_wp_error( $type_link_term_link ) ) : ?>
																<a href="<?php echo esc_url( $type_link_term_link ); ?>" ><?php echo esc_html( $type_link_term->name ); ?></a>
																<?php endif; ?>
															</li>
														<?php endif; ?>
													<?php } else { ?>
														<?php $other_link = get_sub_field( 'other_link_text' ); ?>
														<?php if ( $other_link ): ?>
															<li>
																<a href="<?php echo esc_url( get_sub_field( 'other_link' ) ); ?>" ><?php echo esc_html( $other_link ); ?></a>
															</li>
														<?php endif; ?>
													<?php } ?>
												<?php endwhile; ?>
											</ul>
										<?php else : ?>
											<?php // no rows found ?>
										<?php endif; ?>
									</span>
								</div>
							<?php endwhile; ?>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
				<?php if ( have_rows( 'topics_column_three', 'option' ) ) : ?>
					<?php while ( have_rows( 'topics_column_three', 'option' ) ) : the_row(); ?>
						<?php if ( have_rows( 'group' ) ) : ?>
							<?php while ( have_rows( 'group' ) ) : the_row(); ?>
								<div class="column watch-column" style="display: none;">
									<span class="dropDownSection">
										<?php $icon = get_sub_field( 'icon' ); ?>
										<span class="columnTitle">
											<?php if ( $icon ) { ?>
												<?php echo wp_get_attachment_image( $icon['ID'], 'full', false, [ 'alt' => $icon['alt'], 'class' => 'topic-icon' ] ); ?>
											<?php } ?>
											<?php echo esc_html( get_sub_field( 'title' ) ); ?>
										</span>
										<?php if ( have_rows( 'link' ) ) : ?>
											<ul>
												<?php while ( have_rows( 'link' ) ) : the_row(); ?>
													<?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
														<?php $type_link_term = get_sub_field( 'type_link' ); ?>
														<?php if ( $type_link_term ): ?>
															<li>
																<?php $type_link_term_link = get_term_link( $type_link_term ); ?>
																<?php if ( ! is_wp_error( $type_link_term_link ) ) : ?>
																<a href="<?php echo esc_url( $type_link_term_link ); ?>" ><?php echo esc_html( $type_link_term->name ); ?></a>
																<?php endif; ?>
															</li>
														<?php endif; ?>
													<?php } else { ?>
														<?php $other_link = get_sub_field( 'other_link_text' ); ?>
														<?php if ( $other_link ): ?>
															<li>
																<a href="<?php echo esc_url( get_sub_field( 'other_link' ) ); ?>" ><?php echo esc_html( $other_link ); ?></a>
															</li>
														<?php endif; ?>
													<?php } ?>
												<?php endwhile; ?>
											</ul>
										<?php else : ?>
											<?php // no rows found ?>
										<?php endif; ?>
									</span>
								</div>
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
	</section>
	<?php if ( have_rows( 'content_blocks' ) ): ?>
        <div class="contentBlocks">
        	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
        		<?php if ( get_row_layout() == 'featured_slider_portal' ) : ?>
                    <?php get_template_part( 'templates/components/_featured-slider-portal' ); ?>
                <?php elseif ( get_row_layout() == 'featured_grid_portal' ) : ?>
                	<?php get_template_part( 'templates/components/_featured-grid-portal' ); ?>
				<?php elseif ( get_row_layout() == 'featured_topic' ) : ?>
					<?php get_template_part( 'templates/components/_topic-grid-portal' ); ?>
				<?php elseif ( get_row_layout() == 'case_study_highlight' ) : ?>
					<?php get_template_part( 'templates/components/_case-studies-featured-article-text-portal' ); ?>
				<?php elseif ( get_row_layout() == 'case_study_highlight_with_video' ) : ?>
					<?php get_template_part( 'templates/components/_case-studies-featured-article-video-portal' ); ?>
				<?php elseif ( get_row_layout() == 'expert_presentations_slider' ) : ?>
					<?php get_template_part( 'templates/components/_event-slider-portal' ); ?>
				<?php elseif ( get_row_layout() == 'contact_block' ) : ?>
					<?php get_template_part( 'templates/components/_contact-block' ); ?>
        		<?php endif; ?>
        	<?php endwhile; ?>
        </div>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
