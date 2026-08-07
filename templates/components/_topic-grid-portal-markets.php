<?php $sector_term = get_sub_field( 'topic' ); ?>

<section class="topicGrid portal sector-grid">
    <div class="container">
        <div class="blockTitle">
            <h2><?php echo get_sub_field( 'topic_title' ); ?></h2>
            <a href="/market-narratives/technology-trends/?topic=<?php echo $sector_term->slug; ?>" class="viewAll">View All</a>
        </div>
        <div class="gridWrapper">
            <?php
                $args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                    'tax_query'      => array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'market-narratives-subcategories',
                            'field' => 'slug',
                            'terms'    => 'technology-trends'
                        ),
                        array(
                            'taxonomy' => 'topic',
                            'field'    => 'slug',
                            'terms'    => $sector_term->slug
                        )
                    ),
                );

                $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <div href="<?php the_permalink(); ?>" class="item">
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
                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) ); ?>
                                        <span class="hover-container">
                                            <?php if ($imageCounter) { ?>
                                                <span class="slide-counter">1 OF <?php echo $imageCounter; ?></span>
                                            <?php } ?>
                                        <span>
                                    <?php else : ?>
                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
                                        <span class="hover-container">
                                        <span>
                                    <?php endif; ?>

                                </div>
                            </a>
                            <div class="textContainer">
                                <span class="topicFilter">
                                    <a href="/filter-types/data-insights" class="topicFilterText">Technology Trends</a>
                                    <a href="/topic/<?php echo $sector_term->slug; ?>" class="topicFilterText"><?php echo $sector_term->name; ?></a>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title"><?php the_title();?></a>
                                <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?></span>
                                <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                <a href="<?php the_permalink(); ?>" class="button data-set-button">View Dataset</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif;?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>
