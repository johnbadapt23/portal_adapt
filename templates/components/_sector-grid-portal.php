<?php $sector_term = get_sub_field( 'sector' ); ?>

<section class="topicGrid portal sector-grid">
    <div class="container">
        <div class="blockTitle">
            <h2><?php echo get_sub_field( 'sector_title' ); ?></h2>
            <a href="/data-insights/sector-analysis/<?php echo $sector_term->slug; ?>" class="viewAll">View All</a>
        </div>
        <div class="gridWrapper">
            <?php
                $args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                    'tax_query'      => array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'filter-types',
                            'field' => 'slug',
                            'terms'    => 'data-insights'
                        ),
                        array(
                            'taxonomy' => 'sector-analysis',
                            'field'    => 'slug',
                            'terms'    => $sector_term->slug,
                            'operator' => 'IN'
                        )
                    )
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
                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => '', 'class' => 'desktop' ) ); ?>
                                        <span class="hover-container">
                                            <?php if ($imageCounter) { ?>
                                                <span class="slide-counter">1 OF <?php echo $imageCounter; ?></span>
                                            <?php } ?>
                                        <span>
                                    <?php else : ?>
                                        <img class="desktop" src="<?php echo $image; ?>" />
                                        <span class="hover-container">
                                        <span>
                                    <?php endif; ?>

                                </div>
                            </a>
                            <div class="textContainer">
                                <span class="topicFilter">
                                    <a href="/data-insights/sector-analysis/" class="topicFilterText">Sector Analysis</a>
                                    <a href="/data-insights/sector-analysis/<?php echo $sector_term->slug; ?>" class="topicFilterText"><?php echo $sector_term->name; ?></a>
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
