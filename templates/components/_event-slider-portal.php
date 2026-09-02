<section class="eventSlider portal bg-dark">
    <div class="container">
        <div class="blockTitle">
            <h2><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
            <a href="/filter-types/expert-presentations/" class="viewAll">View All</a>
        </div>
    </div>

    <div class="slideContainer">
        <span class="leftslideCover"></span>
        <span class="rightslideCover"></span>
        <div class="slider">
            <?php if( get_sub_field( 'choose_posts' ) == 'choose'){ ?>
                <?php if ( have_rows( 'posts' ) ) : ?>
	                <?php while ( have_rows( 'posts' ) ) : the_row(); ?>
                        <?php $post_object = get_sub_field( 'post' ); ?>
    					<?php if ( $post_object ): ?>
    						<?php $post = $post_object; ?>
    						<?php setup_postdata( $post ); ?>
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
                                        <?php
								$image_attach_id = adapt_attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
                                    </div>
                                    <span class="watchIcon"></span>
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
                                </div>
                            </div>
    						<?php wp_reset_postdata(); ?>
    					<?php endif; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            <?php } else { ?>
                <?php
                    $args = [
                        'no_found_rows'  => true,
                        'post_type'      => 'post',
                        'posts_per_page' => 6,
                        'tax_query'      => [
                            [
                                'taxonomy' => 'filter-types',
                                'field'    => 'slug',
                                'terms'    => 'expert-presentations'
                            ]
                        ]
                    ];

                    $posts = new WP_Query( $args );
                    if( $posts->have_posts() ): ?>
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
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
                                        <?php
								$image_attach_id = adapt_attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
                                    </div>
                                    <span class="watchIcon"></span>
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
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif;?>
                <?php wp_reset_postdata(); ?>
            <?php }?>
        </div>
    </div>
</section>
