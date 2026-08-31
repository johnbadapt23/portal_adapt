
<?php $q = get_queried_object(); ?>
    <?php if(get_field( 'banner_image', $q )){ ?>
        <?php $banner_image = get_field( 'banner_image', $q ); ?>
    <?php } else { ?>
        <?php $banner_image = get_field( 'sector_analysis_banner_image', 'options' ); ?>
    <?php }?>
    <section class="eventsBanner topicBanner sectorBanner" style="background-image:url(<?php echo esc_url( $banner_image['url'] ); ?>); background-size: cover; background-position: center;">
        <div class="container">
            <span class="back-to-sectors topicFilter">
                <a href="/data-insights/sector-analysis/" target="_self">Sector Analysis</a>
            </span>
            <h1><?php echo esc_html( $q->name );?></h1>
        </div>
    </section>
    <section class="portal postListing topicGrid sector-grid subTopic sector-container">
        <div class="container">
            <div id="loop" class="gridWrapper">

                <?php $args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => -1,
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
                            'terms' => $q->slug,
                            'operator' => 'IN'
                        )
                    ),
                );

                $posts = new WP_Query( $args ); ?>

                <?php if( $posts->have_posts() ): ?>
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
                                                <span class="slide-counter">1 OF <?php echo esc_html( $imageCounter ); ?></span>
                                            <?php } ?>
                                        <span>
                                    <?php else : ?>
                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
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
                                    <a href="/data-insights/sector-analysis/" class="topicFilterText">Sector Analysis</a>
                                    <?php if($postType){?>
                                        <a href="/data-insights/sector-analysis/<?php echo esc_attr( $postType->slug ); ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
                                    <?php } ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
                                <span class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) );?></span>
                                <a href="<?php the_permalink(); ?>" class="button data-set-button">View Dataset</a>
                            </div>
                        </div>

                        <?php $counter++; ?>
                    <?php } ?>

                <?php endwhile; else : ?>
                	<h2 class="h3"><?php esc_html_e( 'Sorry, no results found.', 'portal' ); ?></h2>
                <?php endif; ?>

                <?php wp_reset_postdata(); wp_reset_query();?>

            </div>

        </div>
    </section>
