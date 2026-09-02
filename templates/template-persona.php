<?php
/**
 * Template Name: Persona Mapping Template
 */

get_header();
?>


<main id="main" role="main" class="home sector-analysis-listing person-mapping">
	<?php if ( have_rows( 'banner' ) ) : ?>
		<?php while ( have_rows( 'banner' ) ) : the_row(); ?>
			<?php $banner_image = get_sub_field( 'banner_image' ); ?>
			<section class="topicBanner persona-mapping-banner sector-topic-banner" style="background-image:url(<?php echo esc_url( $banner_image['url'] ); ?>);">
				<div class="container">
					<span class="breadcrumb-container">
						<a class="home-link" href="/" target="_self">Home</a>
						<span class="divider">/</span>
						<span class="title"><?php echo esc_html( get_the_title() ); ?></span>
					</span>
					<span class="title-container">
						<h1 clas="h2-style"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h1>
						<span class="subtitle"><?php echo esc_html( get_sub_field( 'sub_title' ) ); ?></span>
					</span>
				</div>
			</section>
		<?php endwhile; ?>
	<?php else : ?>
	<?php // no rows found ?>
	<?php endif; ?>
	<section class="explore-section persona-explore">
		<div class="container">
			<?php if ( have_rows( 'filter_buttons' ) ) : ?>
				<?php while ( have_rows( 'filter_buttons' ) ) : the_row(); ?>
					<h2 class="title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
					<div class="persona-button-container">
						<?php $persona_terms = get_sub_field( 'personas' ); ?>
						<?php if ( $persona_terms ): ?>
							<?php foreach ( $persona_terms as $persona_term ):
								$persona_term_link = get_term_link( $persona_term );
								if ( is_wp_error( $persona_term_link ) ) {
									continue;
								}
							?>
								<a class="persona-button" href="<?php echo esc_url( $persona_term_link ); ?>" target="_self">
									<span class="persona-button-inner">
										<span class="persona-text-container">
											<span class="v-wrap">
												<span class="v-box">
													<span class="persona-name"><?php echo esc_html( $persona_term->name ); ?></span>
													<span class="persona-full-name"><?php echo esc_html( get_field( 'persona_title', $persona_term ) ); ?></span>
												</span>
											</span>
										</span>
										<span class="persona-image-container">
											<?php $persona_icon = get_field( 'persona_icon', $persona_term ); ?>
											<?php if ( $persona_icon ) { ?>
												<?php echo wp_get_attachment_image( $persona_icon['ID'], 'full', false, [ 'alt' => $persona_icon['alt'] ] ); ?>
											<?php } ?>
										</span>
									</span>

								</a>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( have_rows( 'featured_article' ) ) : ?>
		<section class="persona-featured-article">
			<div class="container">
				<?php while ( have_rows( 'featured_article' ) ) : the_row(); ?>
					<?php $post_object = get_sub_field( 'article' ); ?>
					<?php if ( $post_object ): ?>
						<?php $post = $post_object; ?>
						<?php setup_postdata( $post ); ?>
						<div class="featured-article">
							<div class="image-container">
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
										<?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => '', 'class' => 'desktop' ] ); ?>
									<?php else : ?>
										<?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => '', 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
								}
							?>
									<?php endif; ?>
								</div>
							</div>
							<div class="textContainer">
								<span class="topicFilter">
									<?php if (yoast_get_primary_term_id('persona-mapping')) {
										$primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
										$postTopic = get_term( $primary_term_topic_id );
									} else {
										if(get_the_terms( $post->ID, 'persona-mapping' )){
											$terms = get_the_terms( $post->ID, 'persona-mapping' );
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
										<?php $postTopic_link = get_term_link( $postTopic ); ?>
										<?php if ( ! is_wp_error( $postTopic_link ) ) : ?>
										<a href="<?php echo esc_url( $postTopic_link ); ?>" class="topicFilterText"><?php echo esc_html( $postTopic->name ); ?></a>
										<?php endif; ?>
									<?php } ?>
									<?php if($postType){?>
											<a href="/filter-types/<?php echo esc_attr( $postType->slug ); ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
									<?php } ?>
								</span>
								<a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
								<span class="dateReadTime"><span class="dateRead"><?php echo esc_html( get_the_date('M j, Y') ); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo esc_html( get_field('read_time') ); ?><?php } ?></span>
								<span class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) );?></span>
								<a href="<?php the_permalink(); ?>" class="read-more">Read more</a>
							</div>
						</div>

						<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		</section>
	<?php else : ?>
		<?php // no rows found ?>
<?php endif; ?>
	<section class="portal postListing topicGrid subTopic sector-container list-style-list">
        <div class="container">
			<div class="blockTitle">
				<h2>All <?php echo esc_html( get_the_title() ); ?></h2>
			</div>
            <div id="loop" class="gridWrapper listWrapper list">
				<?php
					// Get all term ID's in a given taxonomy
					$taxonomy = 'persona-mapping';
					$taxonomy_terms = get_terms( [ 'taxonomy' => $taxonomy,
					    'hide_empty' => 0,
					    'fields' => 'ids'
					] );
				?>
				<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
	            <?php $args = [
	                'post_type' => 'post',
	                'posts_per_page' => -1,
	                'no_found_rows' => true,
					'tax_query' => [
				        [
				            'taxonomy' => $taxonomy,
				            'field' => 'id',
				            'terms' => $taxonomy_terms,
				        ],
				    ],

	            ]; ?>
				<?php $posts = new WP_Query( $args );
				if( $posts->have_posts() ): ?>
					<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                    <?php if(current_user_can('mepr_auth')) {?>
                        <div class="item list-view">
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
                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => '', 'class' => 'desktop' ] ); ?>
                                    <?php else : ?>
                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => '', 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
								}
							?>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="textContainer">
                                <span class="topicFilter">
                                    <?php if (yoast_get_primary_term_id('persona-mapping')) {
                                        $primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
                                        $postTopic = get_term( $primary_term_topic_id );
                                    } else {
                                        if(get_the_terms( $post->ID, 'persona-mapping' )){
                                            $terms = get_the_terms( $post->ID, 'persona-mapping' );
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
                                        <?php $postTopic_link = get_term_link( $postTopic ); ?>
                                        <?php if ( ! is_wp_error( $postTopic_link ) ) : ?>
                                        <a href="<?php echo esc_url( $postTopic_link ); ?>" class="topicFilterText"><?php echo esc_html( $postTopic->name ); ?></a>
                                        <?php endif; ?>
                                    <?php } ?>
                                    <?php if($postType){?>
                                            <a href="/filter-types/<?php echo esc_attr( $postType->slug ); ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
                                    <?php } ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
								<span class="dateReadTime"><span class="dateRead"><?php echo esc_html( get_the_date('M j, Y') ); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo esc_html( get_field('read_time') ); ?><?php } ?></span>
                                <span class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) );?></span>
                            </div>
                        </div>

                        <?php $counter++; ?>
                    <?php } ?>

                <?php endwhile; else : ?>
                	<h3><?php esc_html_e( 'Sorry, no results found.', 'portal' ); ?></h3>
                <?php endif; ?>

                <?php wp_reset_postdata(); wp_reset_query();?>

            </div>

        </div>
    </section>
</main>

<?php get_footer(); ?>
