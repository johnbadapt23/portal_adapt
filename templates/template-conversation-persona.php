<?php
/**
 * Template Name: Next Conversation Persona Template
 */

get_header();
?>


<main id="main" role="main" class="home next-conversation">
	<section class="filter-title-block ">
		<div class="container">
			<div class="title-container">
				<h1 class="type-title text-black"><?php echo esc_html( get_field( 'next_conversation_title', 'options' ) ); ?></h1>
				<span class="type-description text-black"><?php echo esc_html( get_field( 'next_conversation_text', 'options' ) ); ?></span>
			</div>
			<div class="topic-button-container-outer">
				<div class="topic-button-container filter-button-container">
					<a class="all filter-button" href="/tnc/">All</a>
					<a href="/tnc/persona/" class="filter-button selected">Persona</a>
					<a href="/tnc/sector/" class="filter-button">Sector</a>					
				</div>
			</div>           
		</div>            
	</section>
	<section class="filter-listing conversation-listing">
		<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
		<div class="container">
			<div class="grid-wrapper" id="loop">
				<?php $args = [
					'post_type' => 'post',
					'posts_per_page' => 9,
					'paged'=> $paged,
					'tax_query' => [
						'relation' => 'AND',
						 [
							'taxonomy' => 'filter-types',
							'field' => 'slug',
							'terms' => 'tnc'
						],
						 [
							'taxonomy' => 'persona-mapping',
							'field' => 'id', 
							'terms' => get_terms(['taxonomy' => 'persona-mapping', 'fields' => 'ids'])
						],
					]
				];
				$posts = new WP_Query( $args ); ?>
				<?php if( $posts->have_posts() ): ?>
					<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
						<div class="item one-third peer-insights-item">
							<a href="<?php the_permalink(); ?>" class="imageSizeContainer">
								<div class="bgContainer">
									<?php if ( get_field( 'listing_image') ) { ?>
										<?php $image = get_field( 'listing_image'); ?>
											<?php
								$image_attach_id = adapt_attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => '', 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
								}
							?>
									<?php } elseif ( get_field( 'video_image' )){ ?>
										<?php $video_image = get_field( 'video_image' ); ?>
										<?php
								$video_image_attach_id = adapt_attachment_url_to_postid( $video_image );
								if ( $video_image_attach_id ) {
									echo wp_get_attachment_image( $video_image_attach_id, 'full', false, [ 'alt' => '', 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $video_image ) . '" loading="lazy" decoding="async" alt="" />';
								}
							?>
									<?php } else { ?>
										<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
											<?php $image = get_field( 'video_poster'); ?>
										<?php } else { ?>
											<?php $image = get_field( 'featured_image'); ?>
										<?php } ?>
										<?php
								$image_attach_id = adapt_attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => '', 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
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
									<?php if($postType){?>
											<a href="/persona-mapping/<?php echo esc_attr( $postType->slug ); ?>" class="topic-filter-text text-black black-tex"><?php echo esc_html( $postType->name ); ?></a>
									<?php } ?>
									<?php if($postTopic){?>
										<?php $postTopic_link = get_term_link( $postTopic ); ?>
										<?php if ( ! is_wp_error( $postTopic_link ) ) : ?>
										<a href="<?php echo esc_url( $postTopic_link ); ?>" class="topic-filter-text text-black black-text">/ <?php echo esc_html( $postTopic->name ); ?></a>
										<?php endif; ?>
									<?php } ?>
								</span>
								<a href="<?php the_permalink(); ?>" class="title labelXLarge text-black"><?php echo esc_html( get_the_title() ); ?></a>
							</span>
						</div>                               
					<?php endwhile; ?>                        
				<?php endif;?>
			</div>
			<div class="page-navi-container">
				<?php wp_pagenavi( [ 'query' => $posts ] ); ?>
					<?php wp_reset_postdata(); ?>
				<?php wp_reset_query(); ?>
			</div>
		</div>
		
	</section>
</main>

<?php get_footer(); ?>
