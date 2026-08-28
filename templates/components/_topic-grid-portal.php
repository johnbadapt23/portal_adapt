<?php $topic_term = get_sub_field( 'topic' );?>

<section class="topicGrid portal">
    <div class="container">
        <div class="blockTitle">
            <h2><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
            <a href="/topic/<?php echo $topic_term->slug; ?>" class="viewAll">View All</a>
        </div>
        <div class="gridWrapper">
            <?php
                $args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 6,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'topic',
                            'field'    => 'slug',
                            'terms'    => $topic_term->slug
                        ),
                         array(
                            'taxonomy' => 'filter-types',
                            'field' => 'slug',
                            'terms' => array('workshop-recordings', 'expert-presentations', 'community-interviews', 'customer', 'tnc'),
                            'operator' => 'NOT IN'
                        )
                    ),
                    'meta_query' => [
                        [
                            'key' => '_yoast_wpseo_primary_topic',
                            'value' => $topic_term->term_id
                        ]
                    ],
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
                                    <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
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
                                        <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
                                    <?php } ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
                                <span class="dateReadTime"><span class="dateRead"><?php echo esc_html( get_the_date('M j, Y') ); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif;?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>
