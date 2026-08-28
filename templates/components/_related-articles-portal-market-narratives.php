

<section class="topicGrid portal related data-insights-related">
    <div class="container">
        <div class="blockTitle">
            <h2>You may also like</h2>
        </div>

        <div class="gridWrapper">
            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
            <?php $args = array(
                'post_type' => 'post',
                'posts_per_page' => 4,
                'paged'=> $paged,
                'tax_query' => array(
                    'relation' => 'AND',
                    array (
                        'taxonomy' => 'filter-types',
                        'field' => 'slug',
                        'terms'    => 'market-narratives'
                    ),
                )
            );

            $posts = new WP_Query( $args );
            if( $posts->have_posts() ): ?>
                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                    <div href="<?php the_permalink(); ?>" class="item">
                        <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                            <div class="bgContainer">
                                <?php if ( have_rows( 'preview_module' ) ) : ?>
                                   <?php while ( have_rows( 'preview_module' ) ) : the_row(); ?>
                                       <?php if ( have_rows( 'slider_images' ) ) : ?>
                                           <?php $imageCounter = 1; ?>
                                           <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                               <?php if($imageCounter == 1){
                                                   $image = get_sub_field( 'image' );
                                               } else if ($imageCounter == 2){
                                                   $offsetimage = get_sub_field( 'image' );
                                               }
                                               $imageCounter++; ?>
                                           <?php endwhile; ?>
                                       <?php else : ?>
                                           <?php // no rows found ?>
                                       <?php endif; ?>
                                    <?php endwhile; ?>
                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'], 'class' => 'desktop slide-preview' ) ); ?>
                                <?php else : ?>
                                    <?php if ( get_field( 'listing_image') ) { ?>
                                        <?php $image = get_field( 'listing_image'); ?>
                                    <?php } else { ?>
                                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                            <?php $image = get_field( 'video_poster'); ?>
                                        <?php } else { ?>
                                            <?php $image = get_field( 'featured_image'); ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
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
                                    <a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topicFilterText"><?php echo esc_html( $postTopic->name ); ?></a>
                                <?php } ?>
                                <?php if($postType){?>
                                    <?php if($postType->slug == 'market-insights'){?>
                                        <a href="/market-insights/" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
                                    <?php } else { ?>
                                        <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
                                    <?php } ?>
                                <?php } ?>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif;?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>
