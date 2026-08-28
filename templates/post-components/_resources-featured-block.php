<?php 
global $membershipType;

$membershipType = trim($membershipType);

$it_pro_types_ids    = get_field('it_pro_types', 'options') ?: [];
$advantage_types_ids = get_field('advantage_types', 'options') ?: [];

$membership_allowed_ids = [];

if ($membershipType === 'it-pro') {
    $membership_allowed_ids = $it_pro_types_ids;
} elseif ($membershipType === 'advantage') {
    $membership_allowed_ids = $advantage_types_ids;
}

/**
 * REUSABLE: Membership tax_query
 * Use this ONLY for automatic WP_Query loops
 */
$membership_tax_query = [];

// Only the very first hero image on the page should be fetchpriority=high and
// excluded from lazy-load - it's the one Lighthouse identifies as the LCP
// element. This block is an ACF flexible-content module that can be (and
// is) included more than once on the same page, so the "first" check is
// delegated to adapt_is_first_hero_image() (functions.php), which is
// scoped to the whole request instead of resetting on every inclusion.

if ( ! empty( $membership_allowed_ids ) ) {
    $membership_tax_query[] = [
        'taxonomy' => 'filter-types',
        'field'    => 'term_id',
        'terms'    => $membership_allowed_ids,
        'operator' => 'IN',
    ];
}
?>

<section class="resources-featured featured-module <?php echo $membershipType; ?>">
    <div class="container">
        <div class="slider-column one-half">

            <?php if ( have_rows( 'post_slider' ) ) : ?>
                <div class="resources-featured-slider">

                    <?php while ( have_rows( 'post_slider' ) ) : the_row(); ?>

                        <?php if ( get_sub_field( 'select_or_most_recent' ) === 'most-recent' ) : ?>

                            <?php
                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                            $args = array(
                                'post_type'      => 'post',
                                'posts_per_page' => 3,
                                'paged'          => $paged,
                            );
                            if ( ! empty( $membership_tax_query ) ) {
                                $args['tax_query'] = $membership_tax_query;
                            }
                            $posts = new WP_Query( $args );
                            ?>

                            <?php if ( $posts->have_posts() ) : ?>
                                <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>

                                    <div class="resources-featured-slide">
                                        <div class="resources-slide-inner">
                                            <?php
                                                $image = null;
                                                $video = 'no';
                                                if ( has_term('replay-post', 'replay', $post_id) ) { 
                                                    if(get_field('video_image', $post_id)) {
                                                        $image = get_field('video_image', $post_id);
                                                        $video = 'yes';
                                                    } else {
                                                        $image = get_field('featured_image', $post_id);
                                                    } 
                                                } else {
                                                    if (get_field('listing_image', $post_id)) {
                                                        $image = get_field('listing_image', $post_id);                        
                                                    } else {
                                                        if (get_field('featured_image_or_video', $post_id) === 'video') {
                                                            $video = 'yes';
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                            }                                                      
                                                        } else {
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                                
                                                            }  
                                                        }
                                                    }
                                                }
                                                
                                            ?>

                                            <?php if ( $video == 'yes' ) : ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <span class="video-container">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_182_src = $image;
					$inline_img_182_attach_id = $inline_img_182_src ? attachment_url_to_postid( $inline_img_182_src ) : 0;
					if ( $inline_img_182_attach_id ) {
						$inline_img_182_is_lcp = adapt_is_first_hero_image();
						$inline_img_182_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_182_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_182_is_lcp ) {
							$inline_img_182_attrs['fetchpriority'] = 'high';
							$inline_img_182_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_182_attach_id, 'article-hero', false, $inline_img_182_attrs );
					} elseif ( $inline_img_182_src ) {
						echo '<img width="530" height="300" class="article-image" src="' . esc_url( $inline_img_182_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>
                                                            <span class="video-button"></span>
                                                        </span>
                                                    </span>
                                                </a>
                                            <?php else : ?>
                                                <span class="image-container">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_183_src = $image;
					$inline_img_183_attach_id = $inline_img_183_src ? attachment_url_to_postid( $inline_img_183_src ) : 0;
					if ( $inline_img_183_attach_id ) {
						$inline_img_183_is_lcp = adapt_is_first_hero_image();
						$inline_img_183_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_183_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_183_is_lcp ) {
							$inline_img_183_attrs['fetchpriority'] = 'high';
							$inline_img_183_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_183_attach_id, 'article-card', false, $inline_img_183_attrs );
					} elseif ( $inline_img_183_src ) {
						echo '<img width="360" height="200" class="article-image" src="' . esc_url( $inline_img_183_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </a>
                                                </span>
                                            <?php endif; ?>

                                            <div class="post-content-container">
                                                <span class="topic-filter">
                                                    <?php
                                                    $postTopic = null;

                                                    if ( yoast_get_primary_term_id( 'topic' ) ) {
                                                        $postTopic = get_term( yoast_get_primary_term_id( 'topic' ) );
                                                    } elseif ( $terms = get_the_terms( get_the_ID(), 'topic' ) ) {
                                                        $postTopic = $terms[0];
                                                    }
                                                    ?>

                                                    <?php if ( $postTopic ) : ?>
                                                        <a href="<?php echo esc_url( get_term_link( $postTopic ) ); ?>" class="topic-filter-text text-dark-grey labelXXsmall">
                                                            <?php echo esc_html( $postTopic->name ); ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </span>

                                                <a href="<?php the_permalink(); ?>" class="title text-black">
                                                    <h2 class="title text-black headerXsmall text-bold"><?php echo esc_html( get_the_title() ); ?></h2>
                                                </a>
                                            </div>

                                        </div>
                                    </div>

                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>

                        <?php else : ?>

                            <?php if ( have_rows( 'posts' ) ) : ?>
                                <?php while ( have_rows( 'posts' ) ) : the_row(); ?>

                                    <?php if ( $post_object = get_sub_field( 'post' ) ) : ?>
                                        <?php
                                        $post = $post_object;
                                        setup_postdata( $post );
                                        ?>

                                        <div class="resources-featured-slide">
                                            <div class="resources-slide-inner">
                                                <?php
                                                    $image = null;
                                                    $video = 'no';
                                                    if ( has_term('replay-post', 'replay', $post_id) ) { 
                                                        if(get_field('video_image', $post_id)) {
                                                            $image = get_field('video_image', $post_id);
                                                            $video = 'yes';
                                                        } else {
                                                            $image = get_field('featured_image', $post_id);
                                                        } 
                                                    } else {
                                                        if (get_field('listing_image', $post_id)) {
                                                            $image = get_field('listing_image', $post_id);                        
                                                        } else {
                                                            if (get_field('featured_image_or_video', $post_id) === 'video') {
                                                                $video = 'yes';
                                                                if(get_field('video_poster', $post_id)){
                                                                    $image = get_field('video_poster', $post_id);
                                                                } else if(get_field('video_image', $post_id)) {
                                                                    $image = get_field('video_image', $post_id);
                                                                } else {
                                                                    $image = get_field('featured_image', $post_id);
                                                                }                                                      
                                                            } else {
                                                                if(get_field('video_poster', $post_id)){
                                                                    $image = get_field('video_poster', $post_id);
                                                                } else if(get_field('video_image', $post_id)) {
                                                                    $image = get_field('video_image', $post_id);
                                                                
                                                                } else {
                                                                    $image = get_field('featured_image', $post_id);
                                                                    
                                                                }  
                                                            }
                                                        }
                                                    }
                                                    
                                                    ?>
                                                <?php
                                                ?>

                                                <?php if ( $video == 'yes' ) : ?>
                                                    <a href="<?php the_permalink(); ?>">
                                                        <span class="video-container">
                                                            <span class="bg-container">
                                                                <?php if ($image) : ?>  
                                                                    <?php
					$inline_img_184_src = $image;
					$inline_img_184_attach_id = $inline_img_184_src ? attachment_url_to_postid( $inline_img_184_src ) : 0;
					if ( $inline_img_184_attach_id ) {
						$inline_img_184_is_lcp = adapt_is_first_hero_image();
						$inline_img_184_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_184_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_184_is_lcp ) {
							$inline_img_184_attrs['fetchpriority'] = 'high';
							$inline_img_184_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_184_attach_id, 'article-hero', false, $inline_img_184_attrs );
					} elseif ( $inline_img_184_src ) {
						echo '<img width="530" height="300" class="article-image" src="' . esc_url( $inline_img_184_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                <?php endif; ?>
                                                                <span class="video-button"></span>
                                                            </span>
                                                        </span>
                                                    </a>
                                                <?php else : ?>
                                                    <span class="image-container">
                                                        <a href="<?php the_permalink(); ?>">
                                                            <span class="bg-container">
                                                                <?php if ($image) : ?>  
                                                                    <?php
					$inline_img_185_src = $image;
					$inline_img_185_attach_id = $inline_img_185_src ? attachment_url_to_postid( $inline_img_185_src ) : 0;
					if ( $inline_img_185_attach_id ) {
						$inline_img_185_is_lcp = adapt_is_first_hero_image();
						$inline_img_185_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_185_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_185_is_lcp ) {
							$inline_img_185_attrs['fetchpriority'] = 'high';
							$inline_img_185_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_185_attach_id, 'article-card', false, $inline_img_185_attrs );
					} elseif ( $inline_img_185_src ) {
						echo '<img width="360" height="200" class="article-image" src="' . esc_url( $inline_img_185_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                <?php endif; ?>
                                                            </span>
                                                        </a>
                                                    </span>
                                                <?php endif; ?>

                                                <div class="post-content-container">
                                                    <span class="topic-filter">
                                                        <?php
                                                        $postTopic = null;

                                                        if ( yoast_get_primary_term_id( 'topic' ) ) {
                                                            $postTopic = get_term( yoast_get_primary_term_id( 'topic' ) );
                                                        } elseif ( $terms = get_the_terms( get_the_ID(), 'topic' ) ) {
                                                            $postTopic = $terms[0];
                                                        }
                                                        ?>

                                                        <?php if ( $postTopic ) : ?>
                                                            <a href="<?php echo esc_url( get_term_link( $postTopic ) ); ?>" class="topic-filter-text text-dark-grey labelXXsmall">
                                                                <?php echo esc_html( $postTopic->name ); ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    </span>

                                                    <a href="<?php the_permalink(); ?>" class="title text-black">
                                                        <h2 class="title text-black headerXsmall text-bold"><?php echo esc_html( get_the_title() ); ?></h2>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>

                                        <?php wp_reset_postdata(); ?>
                                    <?php endif; ?>

                                <?php endwhile; ?>
                            <?php endif; ?>

                        <?php endif; ?>

                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="side-bar-column one-half">

            <?php if ( have_rows( 'side_posts' ) ) : ?>
                <?php while ( have_rows( 'side_posts' ) ) : the_row(); ?>

                    <div class="recent-sidebar">
                        <span class="headerXsmall text-bold text-black"><?php the_sub_field( 'title' ); ?></span>

                        <?php if ( get_sub_field( 'most_recent_or_most_popular' ) === 'most-recent' ) : ?>

                            <?php
                            $args = array(
                                'post_type'      => 'post',
                                'posts_per_page' => 3,
                                'paged'          => $paged,
                            );
                            if ( ! empty( $membership_tax_query ) ) {
                                $args['tax_query'] = $membership_tax_query;
                            }
                            $sidebar_posts = new WP_Query( $args );
                            ?>

                            <?php if ( $sidebar_posts->have_posts() ) : ?>
                                <?php while ( $sidebar_posts->have_posts() ) : $sidebar_posts->the_post(); ?>

                                    <div class="resources-side-posts">
                                        <div class="resources-side-posts-inner">
                                            <?php
                                                $image = null;
                                                $video = 'no';
                                                if ( has_term('replay-post', 'replay', $post_id) ) { 
                                                    if(get_field('video_image', $post_id)) {
                                                        $image = get_field('video_image', $post_id);
                                                        $video = 'yes';
                                                    } else {
                                                        $image = get_field('featured_image', $post_id);
                                                    } 
                                                } else {
                                                    if (get_field('listing_image', $post_id)) {
                                                        $image = get_field('listing_image', $post_id);                        
                                                    } else {
                                                        if (get_field('featured_image_or_video', $post_id) === 'video') {
                                                            $video = 'yes';
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                            }                                                      
                                                        } else {
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                                
                                                            }  
                                                        }
                                                    }
                                                }
                                                
                                            ?>
                                            <?php if ($video == 'yes'){ ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <span class="video-container">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_186_src = $image;
					$inline_img_186_attach_id = $inline_img_186_src ? attachment_url_to_postid( $inline_img_186_src ) : 0;
					if ( $inline_img_186_attach_id ) {
						$inline_img_186_is_lcp = adapt_is_first_hero_image();
						$inline_img_186_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_186_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_186_is_lcp ) {
							$inline_img_186_attrs['fetchpriority'] = 'high';
							$inline_img_186_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_186_attach_id, 'article-hero', false, $inline_img_186_attrs );
					} elseif ( $inline_img_186_src ) {
						echo '<img width="530" height="300" class="article-image" src="' . esc_url( $inline_img_186_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>                                                           
                                                            <span class="video-button">
                                                            </span>                                                            
                                                        </span>
                                                    </span>
                                                </a>
                                            <?php } else { ?>
                                                <span class="image-container">
                                                     <a href="<?php the_permalink(); ?>">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_187_src = $image;
					$inline_img_187_attach_id = $inline_img_187_src ? attachment_url_to_postid( $inline_img_187_src ) : 0;
					if ( $inline_img_187_attach_id ) {
						$inline_img_187_is_lcp = adapt_is_first_hero_image();
						$inline_img_187_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_187_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_187_is_lcp ) {
							$inline_img_187_attrs['fetchpriority'] = 'high';
							$inline_img_187_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_187_attach_id, 'article-card', false, $inline_img_187_attrs );
					} elseif ( $inline_img_187_src ) {
						echo '<img width="360" height="200" class="article-image" src="' . esc_url( $inline_img_187_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </a>
                                                </span>
                                            <?php } ?>
                                            <div class="post-content-container">
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
                                                    <?php if ( !empty( $postTopic ) ) { ?>
                                                        <a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topic-filter-text text-dark-grey labelXXsmall"><?php echo esc_html( $postTopic->name ); ?></a>
                                                    <?php } ?>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="title text-black"><h3 class="title text-black labelMedium"><?php echo esc_html( get_the_title() ); ?></h3></a>
                                            </div>
                                        </div>
                                    </div>

                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>
                        <?php elseif ( get_sub_field( 'most_recent_or_most_popular' ) === 'types' ) : ?>
                            <?php
                            $type_term = get_sub_field( 'type' );                            
                                if ( $type_term ) {

                                    $tax_query = array(
                                        array(
                                            'taxonomy' => 'filter-types',
                                            'field'    => 'slug',
                                            'terms'    => $type_term->slug,
                                        )
                                    );

                                    // Merge membership query properly
                                    if ( ! empty( $membership_tax_query ) ) {
                                        $tax_query = array_merge( $tax_query, $membership_tax_query );
                                    }

                                    $args = array(
                                        'post_type'      => 'post',
                                        'posts_per_page' => 3,
                                        'tax_query'      => $tax_query,
                                    );

                                    $sidebar_posts = new WP_Query( $args );
                                }
                            $sidebar_posts = new WP_Query( $args );
                            ?>

                            <?php if ( $sidebar_posts->have_posts() ) : ?>
                                <?php while ( $sidebar_posts->have_posts() ) : $sidebar_posts->the_post(); ?>

                                    <div class="resources-side-posts">
                                        <div class="resources-side-posts-inner">
                                            <?php
                                                $image = null;
                                                $video = 'no';
                                                if ( has_term('replay-post', 'replay', $post_id) ) { 
                                                    if(get_field('video_image', $post_id)) {
                                                        $image = get_field('video_image', $post_id);
                                                        $video = 'yes';
                                                    } else {
                                                        $image = get_field('featured_image', $post_id);
                                                    } 
                                                } else {
                                                    if (get_field('listing_image', $post_id)) {
                                                        $image = get_field('listing_image', $post_id);                        
                                                    } else {
                                                        if (get_field('featured_image_or_video', $post_id) === 'video') {
                                                            $video = 'yes';
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                            }                                                      
                                                        } else {
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                                
                                                            }  
                                                        }
                                                    }
                                                }
                                                
                                            ?>
                                            <?php if ($video == 'yes'){ ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <span class="video-container">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_188_src = $image;
					$inline_img_188_attach_id = $inline_img_188_src ? attachment_url_to_postid( $inline_img_188_src ) : 0;
					if ( $inline_img_188_attach_id ) {
						$inline_img_188_is_lcp = adapt_is_first_hero_image();
						$inline_img_188_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_188_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_188_is_lcp ) {
							$inline_img_188_attrs['fetchpriority'] = 'high';
							$inline_img_188_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_188_attach_id, 'article-hero', false, $inline_img_188_attrs );
					} elseif ( $inline_img_188_src ) {
						echo '<img width="530" height="300" class="article-image" src="' . esc_url( $inline_img_188_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>                                                           
                                                            <span class="video-button">
                                                            </span>                                                            
                                                        </span>
                                                    </span>
                                                </a>
                                            <?php } else { ?>
                                                <span class="image-container">
                                                     <a href="<?php the_permalink(); ?>">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_189_src = $image;
					$inline_img_189_attach_id = $inline_img_189_src ? attachment_url_to_postid( $inline_img_189_src ) : 0;
					if ( $inline_img_189_attach_id ) {
						$inline_img_189_is_lcp = adapt_is_first_hero_image();
						$inline_img_189_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_189_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_189_is_lcp ) {
							$inline_img_189_attrs['fetchpriority'] = 'high';
							$inline_img_189_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_189_attach_id, 'article-card', false, $inline_img_189_attrs );
					} elseif ( $inline_img_189_src ) {
						echo '<img width="360" height="200" class="article-image" src="' . esc_url( $inline_img_189_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </a>
                                                </span>
                                            <?php } ?>
                                            <div class="post-content-container">
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
                                                    <?php if ( !empty( $postTopic ) ) { ?>
                                                        <a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topic-filter-text text-dark-grey labelXXsmall"><?php echo esc_html( $postTopic->name ); ?></a>
                                                    <?php } ?>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="title text-black"><h3 class="title text-black labelMedium"><?php echo esc_html( get_the_title() ); ?></h3></a>
                                            </div>
                                        </div>
                                    </div>

                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>
                        
                        <?php elseif ( get_sub_field( 'most_recent_or_most_popular' ) === 'tagged-popular' ) : ?>
                            <?php
                                $tax_query = array(
                                    array(
                                        'taxonomy' => 'most-popular',
                                        'field'    => 'slug',
                                        'terms'    => 'most-popular',
                                    )
                                );

                                // Merge membership query properly
                                if ( ! empty( $membership_tax_query ) ) {
                                    $tax_query = array_merge( $tax_query, $membership_tax_query );
                                }

                                $args = array(
                                    'post_type'      => 'post',
                                    'posts_per_page' => 3,
                                    'tax_query'      => $tax_query,
                                );

                                $sidebar_posts = new WP_Query( $args );                                                            
                            ?>

                            <?php if ( $sidebar_posts->have_posts() ) : ?>
                                <?php while ( $sidebar_posts->have_posts() ) : $sidebar_posts->the_post(); ?>

                                    <div class="resources-side-posts">
                                        <div class="resources-side-posts-inner">
                                            <?php
                                                $image = null;
                                                $video = 'no';
                                                if ( has_term('replay-post', 'replay', $post_id) ) { 
                                                    if(get_field('video_image', $post_id)) {
                                                        $image = get_field('video_image', $post_id);
                                                        $video = 'yes';
                                                    } else {
                                                        $image = get_field('featured_image', $post_id);
                                                    } 
                                                } else {
                                                    if (get_field('listing_image', $post_id)) {
                                                        $image = get_field('listing_image', $post_id);                        
                                                    } else {
                                                        if (get_field('featured_image_or_video', $post_id) === 'video') {
                                                            $video = 'yes';
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                            }                                                      
                                                        } else {
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                                
                                                            }  
                                                        }
                                                    }
                                                }
                                                
                                            ?>
                                            <?php if ($video == 'yes'){ ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <span class="video-container">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_190_src = $image;
					$inline_img_190_attach_id = $inline_img_190_src ? attachment_url_to_postid( $inline_img_190_src ) : 0;
					if ( $inline_img_190_attach_id ) {
						$inline_img_190_is_lcp = adapt_is_first_hero_image();
						$inline_img_190_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_190_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_190_is_lcp ) {
							$inline_img_190_attrs['fetchpriority'] = 'high';
							$inline_img_190_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_190_attach_id, 'article-hero', false, $inline_img_190_attrs );
					} elseif ( $inline_img_190_src ) {
						echo '<img width="530" height="300" class="article-image" src="' . esc_url( $inline_img_190_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>                                                           
                                                            <span class="video-button">
                                                            </span>                                                            
                                                        </span>
                                                    </span>
                                                </a>
                                            <?php } else { ?>
                                                <span class="image-container">
                                                     <a href="<?php the_permalink(); ?>">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_191_src = $image;
					$inline_img_191_attach_id = $inline_img_191_src ? attachment_url_to_postid( $inline_img_191_src ) : 0;
					if ( $inline_img_191_attach_id ) {
						$inline_img_191_is_lcp = adapt_is_first_hero_image();
						$inline_img_191_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_191_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_191_is_lcp ) {
							$inline_img_191_attrs['fetchpriority'] = 'high';
							$inline_img_191_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_191_attach_id, 'article-card', false, $inline_img_191_attrs );
					} elseif ( $inline_img_191_src ) {
						echo '<img width="360" height="200" class="article-image" src="' . esc_url( $inline_img_191_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </a>
                                                </span>
                                            <?php } ?>
                                            <div class="post-content-container">
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
                                                    <?php if ( !empty( $postTopic ) ) { ?>
                                                        <a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topic-filter-text text-dark-grey labelXXsmall"><?php echo esc_html( $postTopic->name ); ?></a>
                                                    <?php } ?>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="title text-black"><h3 class="title text-black labelMedium"><?php echo esc_html( get_the_title() ); ?></h3></a>
                                            </div>
                                        </div>
                                    </div>

                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>
                        <?php else : ?>

                            <?php
                            $args = array(
                                'post_type'      => 'post',
                                'posts_per_page' => 3,
                                'meta_key'       => 'post_views_count',
                                'orderby'        => 'meta_value_num',
                                'order'          => 'DESC',
                            );
                            if ( ! empty( $membership_tax_query ) ) {
                                $args['tax_query'] = $membership_tax_query;
                            }

                            $popular_posts = new WP_Query( $args );
                            ?>

                            <?php if ( $popular_posts->have_posts() ) : ?>
                                <?php while ( $popular_posts->have_posts() ) : $popular_posts->the_post(); ?>

                                    <div class="resources-side-posts">
                                        <div class="resources-side-posts-inner">
                                            <?php
                                                $image = null;
                                                $video = 'no';
                                                if ( has_term('replay-post', 'replay', $post_id) ) { 
                                                    if(get_field('video_image', $post_id)) {
                                                        $image = get_field('video_image', $post_id);
                                                        $video = 'yes';
                                                    } else {
                                                        $image = get_field('featured_image', $post_id);
                                                    } 
                                                } else {
                                                    if (get_field('listing_image', $post_id)) {
                                                        $image = get_field('listing_image', $post_id);                        
                                                    } else {
                                                        if (get_field('featured_image_or_video', $post_id) === 'video') {
                                                            $video = 'yes';
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                            }                                                      
                                                        } else {
                                                            if(get_field('video_poster', $post_id)){
                                                                $image = get_field('video_poster', $post_id);
                                                            } else if(get_field('video_image', $post_id)) {
                                                                $image = get_field('video_image', $post_id);
                                                            
                                                            } else {
                                                                $image = get_field('featured_image', $post_id);
                                                                
                                                            }  
                                                        }
                                                    }
                                                }
                                                
                                            ?>
                                            <?php if ($video == 'yes'){ ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <span class="video-container">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_192_src = $image;
					$inline_img_192_attach_id = $inline_img_192_src ? attachment_url_to_postid( $inline_img_192_src ) : 0;
					if ( $inline_img_192_attach_id ) {
						$inline_img_192_is_lcp = adapt_is_first_hero_image();
						$inline_img_192_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_192_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_192_is_lcp ) {
							$inline_img_192_attrs['fetchpriority'] = 'high';
							$inline_img_192_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_192_attach_id, 'article-hero', false, $inline_img_192_attrs );
					} elseif ( $inline_img_192_src ) {
						echo '<img width="530" height="300" class="article-image" src="' . esc_url( $inline_img_192_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>                                                           
                                                            <span class="video-button">
                                                            </span>                                                            
                                                        </span>
                                                    </span>
                                                </a>
                                            <?php } else { ?>
                                                <span class="image-container">
                                                     <a href="<?php the_permalink(); ?>">
                                                        <span class="bg-container">
                                                            <?php if ($image) : ?>  
                                                                <?php
					$inline_img_193_src = $image;
					$inline_img_193_attach_id = $inline_img_193_src ? attachment_url_to_postid( $inline_img_193_src ) : 0;
					if ( $inline_img_193_attach_id ) {
						$inline_img_193_is_lcp = adapt_is_first_hero_image();
						$inline_img_193_attrs = array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_193_is_lcp ? ' skip-lazy' : '' ) );
						if ( $inline_img_193_is_lcp ) {
							$inline_img_193_attrs['fetchpriority'] = 'high';
							$inline_img_193_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_193_attach_id, 'article-card', false, $inline_img_193_attrs );
					} elseif ( $inline_img_193_src ) {
						echo '<img width="360" height="200" class="article-image" src="' . esc_url( $inline_img_193_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </a>
                                                </span>
                                            <?php } ?>
                                            <div class="post-content-container">
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
                                                    <?php if ( !empty( $postTopic ) ) { ?>
                                                        <a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topic-filter-text labelXXsmall text-dark-grey"><?php echo esc_html( $postTopic->name ); ?></a>
                                                    <?php } ?>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="title text-black"><h3 class="title text-black labelMedium"><?php echo esc_html( get_the_title() ); ?></h3></a>
                                            </div>
                                        </div>
                                    </div>

                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>

                        <?php endif; ?>
                    </div>

                <?php endwhile; ?>
            <?php endif; ?>

        </div>
    </div>
</section>
