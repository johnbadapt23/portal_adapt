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

<section class="resources-featured highlights-featured featured-module <?php echo esc_attr( $membershipType ); ?>">
    <div class="container">
         <div class="blockTitle">
            <h2 class="headerXsmall text-bold"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
            <?php if(get_sub_field( 'view_all_link' )){ ?> 
                <a href="<?php echo esc_url( get_sub_field( 'view_all_link' ) ); ?>" class="text-link red-text-link uppercase arrow-link">View All</a>
            <?php } ?>            
        </div>
    </div>
    <div class="container">
       
        <div class="slider-column one-half">

            <?php if ( have_rows( 'post_slider' ) ) : ?>
                <div class="resources-featured-slider">

                    <?php while ( have_rows( 'post_slider' ) ) : the_row(); ?>

                        <?php if ( get_sub_field( 'select_or_most_recent' ) === 'most-recent' ) : ?>

                            <?php
                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                            $args = [
                                'post_type'      => 'post',
                                'posts_per_page' => 3,
                                'paged'          => $paged,
                            ];
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
					$inline_img_172_src = $image;
					$inline_img_172_attach_id = $inline_img_172_src ? attachment_url_to_postid( $inline_img_172_src ) : 0;
					if ( $inline_img_172_attach_id ) {
						$inline_img_172_is_lcp = adapt_is_first_hero_image();
						$inline_img_172_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_172_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_172_is_lcp ) {
							$inline_img_172_attrs['fetchpriority'] = 'high';
							$inline_img_172_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_172_attach_id, 'article-hero', false, $inline_img_172_attrs );
					} elseif ( $inline_img_172_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_172_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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
					$inline_img_173_src = $image;
					$inline_img_173_attach_id = $inline_img_173_src ? attachment_url_to_postid( $inline_img_173_src ) : 0;
					if ( $inline_img_173_attach_id ) {
						$inline_img_173_is_lcp = adapt_is_first_hero_image();
						$inline_img_173_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_173_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_173_is_lcp ) {
							$inline_img_173_attrs['fetchpriority'] = 'high';
							$inline_img_173_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_173_attach_id, 'article-card', false, $inline_img_173_attrs );
					} elseif ( $inline_img_173_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_173_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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

                                                    <?php $postTopic_link = $postTopic ? get_term_link( $postTopic ) : null; ?>
                                                    <?php if ( $postTopic && ! is_wp_error( $postTopic_link ) ) : ?>
                                                        <a href="<?php echo esc_url( $postTopic_link ); ?>" class="topic-filter-text text-dark-grey labelXXsmall">
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
					$inline_img_174_src = $image;
					$inline_img_174_attach_id = $inline_img_174_src ? attachment_url_to_postid( $inline_img_174_src ) : 0;
					if ( $inline_img_174_attach_id ) {
						$inline_img_174_is_lcp = adapt_is_first_hero_image();
						$inline_img_174_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_174_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_174_is_lcp ) {
							$inline_img_174_attrs['fetchpriority'] = 'high';
							$inline_img_174_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_174_attach_id, 'article-hero', false, $inline_img_174_attrs );
					} elseif ( $inline_img_174_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_174_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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
					$inline_img_175_src = $image;
					$inline_img_175_attach_id = $inline_img_175_src ? attachment_url_to_postid( $inline_img_175_src ) : 0;
					if ( $inline_img_175_attach_id ) {
						$inline_img_175_is_lcp = adapt_is_first_hero_image();
						$inline_img_175_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_175_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_175_is_lcp ) {
							$inline_img_175_attrs['fetchpriority'] = 'high';
							$inline_img_175_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_175_attach_id, 'article-card', false, $inline_img_175_attrs );
					} elseif ( $inline_img_175_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_175_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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

                                                        <?php $postTopic_link = $postTopic ? get_term_link( $postTopic ) : null; ?>
                                                        <?php if ( $postTopic && ! is_wp_error( $postTopic_link ) ) : ?>
                                                            <a href="<?php echo esc_url( $postTopic_link ); ?>" class="topic-filter-text text-dark-grey labelXXsmall">
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

                    <div class="recent-sidebar no-border">
                        <span class="headerXsmall text-bold text-black"><?php the_sub_field( 'title' ); ?></span>

                        <?php if ( get_sub_field( 'most_recent_or_most_popular' ) === 'most-recent' ) : ?>

                            <?php
                            $args = [
                                'post_type'      => 'post',
                                'posts_per_page' => 3,
                                'paged'          => $paged,
                            ];
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
					$inline_img_176_src = $image;
					$inline_img_176_attach_id = $inline_img_176_src ? attachment_url_to_postid( $inline_img_176_src ) : 0;
					if ( $inline_img_176_attach_id ) {
						$inline_img_176_is_lcp = adapt_is_first_hero_image();
						$inline_img_176_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_176_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_176_is_lcp ) {
							$inline_img_176_attrs['fetchpriority'] = 'high';
							$inline_img_176_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_176_attach_id, 'article-hero', false, $inline_img_176_attrs );
					} elseif ( $inline_img_176_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_176_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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
					$inline_img_177_src = $image;
					$inline_img_177_attach_id = $inline_img_177_src ? attachment_url_to_postid( $inline_img_177_src ) : 0;
					if ( $inline_img_177_attach_id ) {
						$inline_img_177_is_lcp = adapt_is_first_hero_image();
						$inline_img_177_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_177_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_177_is_lcp ) {
							$inline_img_177_attrs['fetchpriority'] = 'high';
							$inline_img_177_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_177_attach_id, 'article-card', false, $inline_img_177_attrs );
					} elseif ( $inline_img_177_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_177_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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
                                                        <?php $postTopic_link = get_term_link( $postTopic ); ?>
                                                        <?php if ( ! is_wp_error( $postTopic_link ) ) : ?>
                                                        <a href="<?php echo esc_url( $postTopic_link ); ?>" class="topic-filter-text text-dark-grey labelXXsmall"><?php echo esc_html( $postTopic->name ); ?></a>
                                                        <?php endif; ?>
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

                                    $tax_query = [
                                        [
                                            'taxonomy' => 'filter-types',
                                            'field'    => 'slug',
                                            'terms'    => $type_term->slug,
                                        ]
                                    ];

                                    // Merge membership query properly
                                    if ( ! empty( $membership_tax_query ) ) {
                                        $tax_query = array_merge( $tax_query, $membership_tax_query );
                                    }

                                    $args = [
                                        'post_type'      => 'post',
                                        'posts_per_page' => 3,
                                        'tax_query'      => $tax_query,
                                    ];

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
					$inline_img_178_src = $image;
					$inline_img_178_attach_id = $inline_img_178_src ? attachment_url_to_postid( $inline_img_178_src ) : 0;
					if ( $inline_img_178_attach_id ) {
						$inline_img_178_is_lcp = adapt_is_first_hero_image();
						$inline_img_178_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_178_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_178_is_lcp ) {
							$inline_img_178_attrs['fetchpriority'] = 'high';
							$inline_img_178_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_178_attach_id, 'article-hero', false, $inline_img_178_attrs );
					} elseif ( $inline_img_178_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_178_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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
					$inline_img_179_src = $image;
					$inline_img_179_attach_id = $inline_img_179_src ? attachment_url_to_postid( $inline_img_179_src ) : 0;
					if ( $inline_img_179_attach_id ) {
						$inline_img_179_is_lcp = adapt_is_first_hero_image();
						$inline_img_179_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_179_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_179_is_lcp ) {
							$inline_img_179_attrs['fetchpriority'] = 'high';
							$inline_img_179_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_179_attach_id, 'article-card', false, $inline_img_179_attrs );
					} elseif ( $inline_img_179_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_179_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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
                                                        <?php $postTopic_link = get_term_link( $postTopic ); ?>
                                                        <?php if ( ! is_wp_error( $postTopic_link ) ) : ?>
                                                        <a href="<?php echo esc_url( $postTopic_link ); ?>" class="topic-filter-text text-dark-grey labelXXsmall"><?php echo esc_html( $postTopic->name ); ?></a>
                                                        <?php endif; ?>
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
                            $args = [
                                'post_type'      => 'post',
                                'posts_per_page' => 3,
                                'meta_key'       => 'post_views_count',
                                'orderby'        => 'meta_value_num',
                                'order'          => 'DESC',
                            ];
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
					$inline_img_180_src = $image;
					$inline_img_180_attach_id = $inline_img_180_src ? attachment_url_to_postid( $inline_img_180_src ) : 0;
					if ( $inline_img_180_attach_id ) {
						$inline_img_180_is_lcp = adapt_is_first_hero_image();
						$inline_img_180_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_180_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_180_is_lcp ) {
							$inline_img_180_attrs['fetchpriority'] = 'high';
							$inline_img_180_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_180_attach_id, 'article-hero', false, $inline_img_180_attrs );
					} elseif ( $inline_img_180_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_180_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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
					$inline_img_181_src = $image;
					$inline_img_181_attach_id = $inline_img_181_src ? attachment_url_to_postid( $inline_img_181_src ) : 0;
					if ( $inline_img_181_attach_id ) {
						$inline_img_181_is_lcp = adapt_is_first_hero_image();
						$inline_img_181_attrs = [ 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' . ( $inline_img_181_is_lcp ? ' skip-lazy' : '' ) ];
						if ( $inline_img_181_is_lcp ) {
							$inline_img_181_attrs['fetchpriority'] = 'high';
							$inline_img_181_attrs['loading'] = 'eager';
						}
						echo wp_get_attachment_image( $inline_img_181_attach_id, 'article-card', false, $inline_img_181_attrs );
					} elseif ( $inline_img_181_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_181_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
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
                                                        <?php $postTopic_link = get_term_link( $postTopic ); ?>
                                                        <?php if ( ! is_wp_error( $postTopic_link ) ) : ?>
                                                        <a href="<?php echo esc_url( $postTopic_link ); ?>" class="topic-filter-text labelXXsmall text-dark-grey"><?php echo esc_html( $postTopic->name ); ?></a>
                                                        <?php endif; ?>
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
