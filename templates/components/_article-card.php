<?php
/**
 * Article card template
 */

?>
<?php
    // Not every call site sets these before including this template -
    // default them so an unset value degrades gracefully instead of
    // throwing an undefined-variable notice.
    $eventtype     = $eventtype ?? 'no';
    $post_slug     = $post_slug ?? '';
    $extra_classes = $extra_classes ?? '';

    global $articleCounter;

    $articleCounter++;


    $post_id = get_the_ID();
    $is_favourites = get_query_var('is_favourites', false);
    $event = 'no';
    if($eventtype == 'yes'){
        $event = 'yes';
    } 

    global $membershipType;
    $advantagePlus = "no";
    $current_user = wp_get_current_user();
    $member = new MeprUser($current_user->ID);

    // Get the active subscriptions for this user
    $active_subscriptions = $member->active_product_subscriptions('ids');

    if (
        current_user_can('administrator') ||
        ( current_user_can('mepr-active') && (
            in_array(49140, $active_subscriptions) 
        ))
    ) {
        $advantagePlus = "yes";
    }

    $advantageType = "no";

    if (
    current_user_can('mepr-active') && (
            in_array(49140, $active_subscriptions) ||
            in_array(3829, $active_subscriptions) ||
            in_array(36884, $active_subscriptions) ||
            in_array(41272, $active_subscriptions)
        )
    ) {
        $advantageType = "yes";
    }

$tax_map = [
    'topic'            => 'topic',
    'filter-types'     => 'type',
    'persona-mapping'  => 'persona',
    'sector-analysis'  => 'sector',
    'insights-event'   => 'event',
    'trending-themes'  => 'trending-themes',
];
$filtered_topic = $filtered_topic ?? null;
?>

<div
    class="article-column <?php echo esc_attr($extra_classes); ?> article-card"
    <?php foreach ($tax_map as $taxonomy => $attr) :
        $terms = wp_get_post_terms($post_id, $taxonomy, ['fields' => 'slugs']);
        if (!empty($terms)) :
    ?>
        data-<?php echo esc_attr($attr); ?>="<?php echo esc_attr(implode(',', $terms)); ?>"
    <?php endif; endforeach; ?>
>
    <?php if ($is_favourites && function_exists('get_favorites_button')) : ?>
        <span class="removePostButton">
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_favorites_button() is a third-party plugin function that returns its own pre-built HTML markup. ?>
            <?php echo get_favorites_button(get_the_ID()); ?>
        </span>
    <?php endif; ?>
    <?php
    $terms = get_the_terms($post->ID, 'featured-post');
    $featured = false;
    if ($terms && ! is_wp_error($terms)) {
        foreach ($terms as $term) {
            if ($term->slug === 'featured') { // <-- fixed
                $featured = true;
                break;
            }
        }
    }

    if ($featured) {
        echo '<span class="featured-label">Featured</span>';
    }
    ?>
    <?php if ( has_term( 'yes-featured', 'featured-post', $post_id ) ) : ?>
        <span class="featured-label">Featured</span>
    <?php endif; ?>
    <a class="article-link" href="<?php echo esc_url(get_permalink($post_id)); ?>" id="<?php echo esc_attr($post_slug); ?>">
        <span class="article-inner">
            <span class="article-top">
                <span class="topic cat-tag-text">
                    <?php if($event == 'yes'){ ?>
                        <?php
                            $postTopic = null;

                            // Get primary term first
                            if ( yoast_get_primary_term_id( 'insights-event' ) ) {

                                $term = get_term( yoast_get_primary_term_id( 'insights-event' ), 'insights-event' );

                            } else {

                                $terms = get_the_terms( $post_id, 'insights-event' );
                                if ( $terms && ! is_wp_error( $terms ) ) {
                                    $term = $terms[0];
                                }
                            }

                            if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
                                while ( $term->parent != 0 ) {
                                    $term = get_term( $term->parent, 'insights-event' );
                                }
                                $postTopic = $term;
                            }

                            if ( $postTopic ) :
                                echo esc_html( $postTopic->name );
                            endif;
                        ?> 
                    <?php } else { ?> 
                        <?php
                        if (!empty($filtered_topic) && $filtered_topic !== '') {
                            // Convert slug to term object
                            $term_obj = get_term_by('slug', $filtered_topic, 'topic');
                            if ($term_obj && !is_wp_error($term_obj)) {
                                echo esc_html($term_obj->name);
                            }
                        } else {
                            $postTopic = null;

                            // Get primary term first
                            if ( yoast_get_primary_term_id( 'topic' ) ) {

                                $term = get_term( yoast_get_primary_term_id( 'topic' ), 'topic' );

                            } else {

                                $terms = get_the_terms( $post_id, 'topic' );
                                if ( $terms && ! is_wp_error( $terms ) ) {
                                    $term = $terms[0];
                                }
                            }

                            if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
                                while ( $term->parent != 0 ) {
                                    $term = get_term( $term->parent, 'topic' );
                                }
                                $postTopic = $term;
                            }

                            if ( $postTopic ) :
                                echo esc_html( $postTopic->name );
                            endif;
                        }
                        ?>
                        
                        <?php } ?>
                    
                </span>
                <span class="article-title"><?php echo esc_html(get_the_title($post_id)); ?></span>
            </span>
            <span class="article-bottom">
                <span class="image-container">
                    <?php
                    $image = null;
                    $video = 'no';
                    
                    if ( has_term('replay-post', 'replay', $post_id) ) { 
                        if (get_field('video_image', $post_id)) {
                            $image = get_field('video_image', $post_id);
                            $video = 'yes';
                        } 
                        else if (get_field('video_poster', $post_id)) {
                            $image = get_field('video_poster', $post_id);
                            $video = 'yes';
                        } 
                        else if (get_field('featured_image', $post_id)) {
                            $image = get_field('featured_image', $post_id);
                        } 
                        else if (get_field('listing_image', $post_id)) {
                            $image = get_field('listing_image', $post_id); 
                        }
                        else {
                            $image = ''; 
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
                    <?php if ($image) : ?>
                        <span class="bg-container <?= esc_attr( $articleCounter ); ?>">
                            <?php
                            $attachment_id = attachment_url_to_postid( $image );

                            if ( $attachment_id ) {
                                // adapt_is_first_hero_image() is shared across every
                                // homepage component (highlights/resources featured
                                // blocks, this card grid) so only the single image
                                // that actually renders first on the page gets the
                                // fetchpriority hint, instead of each component
                                // independently marking its own first card.
                                if( adapt_is_first_hero_image() ){
                                    echo wp_get_attachment_image(
                                        $attachment_id,
                                        'article-card',
                                        false,
                                        array(
                                            'class' => 'article-image',
                                            'alt'   => get_the_title( $post_id ),
                                            'sizes' => '(max-width: 354px) 100vw, 354px',
                                            'fetchpriority' => 'high',
                                            'loading'       => 'eager',
                                        )
                                    );
                                }else{
                                    echo wp_get_attachment_image(
                                        $attachment_id,
                                        'article-card',
                                        false,
                                        array(
                                            'class' => 'article-image',
                                            'alt'   => get_the_title( $post_id ),
                                            'sizes' => '(max-width: 354px) 100vw, 354px',
                                        )
                                    );
                                }
                                
                            } else {
                                // Fallback if the URL is not found in the media library.
                                ?>
                                <?php
					$inline_img_149_src = $image;
					$inline_img_149_attach_id = $inline_img_149_src ? attachment_url_to_postid( $inline_img_149_src ) : 0;
					if ( $inline_img_149_attach_id ) {
						echo wp_get_attachment_image( $inline_img_149_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title( $post_id ) ), 'class' => 'article-image' ) );
					} elseif ( $inline_img_149_src ) {
						echo '<img width="360" height="200" class="article-image" src="' . esc_url( $inline_img_149_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr( get_the_title( $post_id ) ) ) . '" />';
					}
				?>
                                <?php
                            }
                            ?>
                            <span class="date"><?php echo esc_html( get_the_date('M j, Y', $post_id) ); ?></span>
                            <?php if($video == 'yes'){ ?>
                                <span class="video-icon"></span>
                            <?php } ?>
                 

                        </span>
                    <?php endif; ?>
                    <?php
                        if ( has_term( ['sector-outlooks', 'cxo-buyer-persona-profiles',  ], 'filter-types',
        $post_id ) && $advantageType == 'yes' && $advantagePlus != 'yes' ) { ?>
                            <span class="advantage-overlay"></span>
                        
                        <?php } ?>

                </span>
                <span class="excerpt-link-container">
                    <span class="excerpt">
                        <?php echo esc_html(get_the_excerpt($post_id)); ?>
                    </span>
                    <?php
                        $today = wp_date('Ymd');
                        $replay_date = get_field('replay_event_date', $post_id);

                        $button_text = 'Read More';

                        if ($replay_date) {
                            if ($replay_date <= $today) {
                                // Past replay
                                $button_text = 'Watch';
                            } else {
                                // Future replay (optional, can stay Read More or say "Register")
                                $button_text = 'Register';
                            }
                        }
                        ?>
                    <span class="text-link red-text-link uppercase arrow-link"><?php echo esc_html( $button_text ); ?></span>
                </span>
            </span>
        </span>
    </a>
</div>

