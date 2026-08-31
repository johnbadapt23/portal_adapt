<?php
/**
 * Featured Article card template
 */

?>
<?php
    // Not every call site sets these before including this template -
    // default them so an unset value degrades gracefully instead of
    // throwing an undefined-variable notice.
    $eventtype = $eventtype ?? 'no';
    $post_slug = $post_slug ?? '';

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
?>

<div class="featured-article-card plus-<?php echo esc_attr( $advantagePlus ); ?>">
    <a class="article-link" href="<?php echo esc_url(get_permalink($post_id)); ?>" id="<?php echo esc_attr($post_slug); ?>">
        <span class="article-column-container">
            <span class="article-text-column">
                <span class="text-inner">   
                    <?php if (yoast_get_primary_term_id('persona-mapping')) {
                        $primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
                        $postTopic = get_term( $primary_term_topic_id );
                    } else {
                        if(get_the_terms( $post->ID, 'persona-mapping' )){
                            $terms = get_the_terms( $post->ID, 'persona-mapping' );
                            foreach($terms as $term) {
                                $postTopic = $term;
                            }
                        }
                    }?>             
                    <span class="article-title headerXsmall text-bold text-black"><?php echo esc_html( $postTopic->name ); ?> Buyer Persona Profile</span>
                    <span class="excerpt text-black">
                        <?php echo esc_html(get_the_excerpt($post_id)); ?>
                    </span>
                    <span class="link-container">
                        <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                    </span>
                </span>
            </span>
            <span class="article-image-column">
                <span class="image-container">
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
                    <?php if ($image) : ?>
                        <span class="bg-container">
                            <?php
					$inline_img_153_src = $image;
					$inline_img_153_attach_id = $inline_img_153_src ? attachment_url_to_postid( $inline_img_153_src ) : 0;
					if ( $inline_img_153_attach_id ) {
						echo wp_get_attachment_image( $inline_img_153_attach_id, 'article-card', false, array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' ) );
					} elseif ( $inline_img_153_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_153_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                            <?php if($video == 'yes'){ ?>
                                <span class="video-icon"></span>
                            <?php } ?>
                        </span>
                        <?php if($advantagePlus != 'yes'){ ?>
                            <span class="advantage-overlay">
                            </span>
                        <?php } ?>
                    <?php endif; ?>
                </span>               
            </span>
        </span>
    </a>
</div>

