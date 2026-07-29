<?php
/**
 * Template Name: Next Conversation Sector Template
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
					<a class="all filter-button" href="/tnc/">All</a>
					<a href="/tnc/persona/" class="filter-button ">Persona</a>
					<a href="/tnc/sector/" class="filter-button selected">Sector</a>					
				</div>
			</div>           
		</div>            
	</section>
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
						array (
							'taxonomy' => 'sector-analysis',
							'field' => 'id', 
							'terms' => get_terms('sector-analysis', array('fields' => 'ids'))
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
											<img class="desktop" src="<?php echo $image; ?>" />
									<?php } elseif ( get_field( 'video_image' )){ ?>
										<?php $video_image = get_field( 'video_image' ); ?>
										<img class="desktop" src="<?php echo $video_image; ?>"/>
									<?php } else { ?>
										<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
											<?php $image = get_field( 'video_poster'); ?>
										<?php } else { ?>
											<?php $image = get_field( 'featured_image'); ?>
										<?php } ?>
										<img class="desktop" src="<?php echo $image; ?>" />
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
									
									<?php if($postSector){?>
											<a href="/data-insights/sector-analysis/<?php echo $postSector->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postSector->name; ?></a>
									<?php } ?>                                
									<?php if($postTopic){?>
										<a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text text-black black-text">/ <?php echo $postTopic->name; ?></a>
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
</main>

<?php get_footer(); ?>
