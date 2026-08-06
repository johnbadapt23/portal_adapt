<?php
    if (yoast_get_primary_term_id('topic')) {
        $primary_term_topic_id = yoast_get_primary_term_id('topic');
        $postTopic = get_term($primary_term_topic_id);
        
        // Check if the term has ancestors
        $ancestors = get_ancestors($postTopic->term_id, 'topic');
        if (!empty($ancestors)) {
            // If the term has ancestors, get the top-level parent
            $top_parent_id = end($ancestors); // Get the last item in the ancestors array
            $postTopic = get_term($top_parent_id, 'topic');
        }
    } else {
        if (get_the_terms($post->ID, 'topic')) {
            $terms = get_the_terms($post->ID, 'topic');
            foreach ($terms as $term) {
                $ancestors = get_ancestors($term->term_id, 'topic');
                if (empty($ancestors)) {
                    // If the term has no parent, it is already the top-level parent
                    $postTopic = $term;
                } else {
                    // If the term has parent(s), get the top-level parent
                    $top_parent_id = end($ancestors); // Get the last item in the ancestors array
                    $postTopic = get_term($top_parent_id, 'topic');
                }
            }
        }
    }
?>
<?php 
    if (is_user_logged_in() || (function_exists('adapt_content_unlocked') && adapt_content_unlocked())) {
        $user_id = get_current_user_id(); // Get the current user ID
        if(current_user_can('mepr-active','memberships:9811')) {
        } else {
            $current_user = wp_get_current_user();
            // Get the current post count from the meta field 'mepr_post_views'
            $post_count = (int) get_user_meta($user_id, 'mepr_post_views', true);
            
            // If the meta field doesn't exist or it's not a number, set it to 1
            if (!is_numeric($post_count)) {
                $post_count = 1;
            } else {
                // Increment the post count by one
                $post_count++;
            }

            // Update user meta with the new post count
            update_user_meta($user_id, 'mepr_post_views', $post_count);

            // 30 day counter

             // Get the current post count array from the meta field 'mepr_post_views_30_days'
            $post_views_array = get_user_meta($user_id, 'mepr_post_views_30_days_array', true);

            // If the meta field doesn't exist or it's not an array, initialize an empty array
            if (!is_array($post_views_array)) {
                $post_views_array = array();
            }

            // Get the current timestamp
            $current_timestamp = date('Ymd');

            // Remove views older than 30 days
            // $thirty_days_ago_date = date('Ymd', strtotime('-30 days'));
            // test it with one day
            $thirty_days_ago_date = date('Ymd', strtotime('-30 days'));

            $new_post_views_array = array();
            foreach ($post_views_array as $view) {
                if ($view['date'] >= $thirty_days_ago_date) {
                    $new_post_views_array[] = $view;
                }
            }

            if (!isset($post_id)) {
                $post_id = get_the_ID(); // Replace with the appropriate method to get the current post ID
            }

            // Increment the post count by one and add the current view with timestamp
            $new_post_views_array[] = array('post_id' => $post_id, 'date' => $current_timestamp);

            // Update the meta field 'mepr_post_views_30_days' with the new array
            update_user_meta($user_id, 'mepr_post_views_30_days_array', $new_post_views_array);

            // Update the meta field 'mepr_post_views_thirty' with the count
            $post_count_thirty = count($new_post_views_array);
            update_user_meta($user_id, 'mepr_post_views_thirty_days', $post_count_thirty);

            mepr_update_monthly_count(
                $user_id,
                'mepr_post_views_12_months_array',  // Array of monthly counts
                'mepr_post_views_twelve_months'     // Total for last 12 months
            );

            if($postTopic){ 
                if($postTopic -> slug == 'applications'){
                    $post_count_applications = (int) get_user_meta($user_id, 'mepr_applications_views', true);                
                    if (!is_numeric($post_count_applications)) {
                        $post_count_applications = 1;
                    } else {
                        $post_count_applications++;
                    }
                    update_user_meta($user_id, 'mepr_applications_views', $post_count_applications);
                }
                if($postTopic -> slug == 'cloud-infrastructure'){
                    // Get the current post count from the meta field 'mepr_cloud_infrastructure_views'
                    $post_count_cloud_infrastructure = (int) get_user_meta($user_id, 'mepr_cloud_infrastructure_views', true);
                    
                    // If the meta field doesn't exist or it's not a number, set it to 1
                    if (!is_numeric($post_count_cloud_infrastructure)) {
                        $post_count_cloud_infrastructure = 1;
                    } else {
                        // Increment the post count by one
                        $post_count_cloud_infrastructure++;
                    }

                    // Update user meta with the new post count
                    update_user_meta($user_id, 'mepr_cloud_infrastructure_views', $post_count_cloud_infrastructure);
                }
                if($postTopic -> slug == 'data-ai'){
                    $post_count_dataAI = (int) get_user_meta($user_id, 'mepr_data_ai_views', true);                
                    if (!is_numeric($post_count_dataAI)) {
                        $post_count_dataAI = 1;
                    } else {
                        $post_count_dataAI++;
                    }
                    update_user_meta($user_id, 'mepr_data_ai_views', $post_count_dataAI);
                }
                if($postTopic -> slug == 'digital-transformation'){
                    $post_count_digitalTransformation = (int) get_user_meta($user_id, 'mepr_digital_transformation_views', true);                
                    if (!is_numeric($post_count_digitalTransformation)) {
                        $post_count_digitalTransformation = 1;
                    } else {
                        $post_count_digitalTransformation++;
                    }
                    update_user_meta($user_id, 'mepr_digital_transformation_views', $post_count_digitalTransformation);
                }
                if($postTopic -> slug == 'finance'){
                    // Get the current post count from the meta field 'mepr_finance_views'
                    $post_count_finance = (int) get_user_meta($user_id, 'mepr_finance_views', true);
                    
                    // If the meta field doesn't exist or it's not a number, set it to 1
                    if (!is_numeric($post_count_finance)) {
                        $post_count_finance = 1;
                    } else {
                        // Increment the post count by one
                        $post_count_finance++;
                    }

                    // Update user meta with the new post count
                    update_user_meta($user_id, 'mepr_finance_views', $post_count_finance);
                }
                if($postTopic -> slug == 'people'){
                    $post_count_people = (int) get_user_meta($user_id, 'mepr_people_views', true);                
                    if (!is_numeric($post_count_people)) {
                        $post_count_people = 1;
                    } else {
                        $post_count_people++;
                    }
                    update_user_meta($user_id, 'mepr_people_views', $post_count_people);
                }
                if($postTopic -> slug == 'security'){
                    // Get the current post count from the meta field 'mepr_security_view'
                    $post_count_security = (int) get_user_meta($user_id, 'mepr_security_views', true);
                    
                    // If the meta field doesn't exist or it's not a number, set it to 1
                    if (!is_numeric($post_count_security)) {
                        $post_count_security = 1;
                    } else {
                        // Increment the post count by one
                        $post_count_security++;
                    }

                    // Update user meta with the new post count
                    update_user_meta($user_id, 'mepr_security_views', $post_count_security);
                }
                if($postTopic -> slug == 'strategic-business-initiatives'){
                    $post_count_strategicBusiness = (int) get_user_meta($user_id, 'mepr_strategic_business_initiatives_views', true);                
                    if (!is_numeric($post_count_strategicBusiness)) {
                        $post_count_strategicBusiness = 1;
                    } else {
                        $post_count_strategicBusiness++;
                    }
                    update_user_meta($user_id, 'mepr_strategic_business_initiatives_views', $post_count_strategicBusiness);
                } 
                if($postTopic -> slug == 'it-executive-strategy'){
                    $post_count_executiveStrategy = (int) get_user_meta($user_id, 'mepr_it_executive_strategy_views', true);                
                    if (!is_numeric($post_count_executiveStrategy)) {
                        $post_count_executiveStrategy = 1;
                    } else {
                        $post_count_executiveStrategy++;
                    }
                    update_user_meta($user_id, 'mepr_it_executive_strategy_views', $post_count_executiveStrategy);
                } 
                if($postTopic -> slug == 'all-domains'){
                    $post_count_allDomains = (int) get_user_meta($user_id, 'mepr_all_domains_views', true);                
                    if (!is_numeric($post_count_allDomains)) {
                        $post_count_allDomains = 1;
                    } else {
                        $post_count_allDomains++;
                    }
                    update_user_meta($user_id, 'mepr_all_domains_views', $post_count_allDomains);
                }                        
            } 
            do_action('profile_update', $user_id, $current_user);
        }        
    }
$advantagePlus = "no";

// Get current user
$current_user = wp_get_current_user();
$member = new MeprUser($current_user->ID);

// Get the active subscriptions for this user
$active_subscriptions = $member->active_product_subscriptions('ids');

if (
    current_user_can('administrator') ||
    ( current_user_can('mepr-active') && (
        in_array(49140, $active_subscriptions) ||
        in_array(41272, $active_subscriptions)
    ))
) {
    $advantagePlus = "yes";
}
$advantageType = "no";
// Get the active subscriptions for this user
$active_subscriptions = $member->active_product_subscriptions('ids');

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
?>

<?php if( has_term( 'replay-post', 'replay' ) ) {  ?>
    <?php
    $date_string = get_field('replay_event_date');
    $date = DateTime::createFromFormat('Ymd', $date_string);
    ?>
    <section class="researchArticleTextHeader replayArticleHeader">
        <div class="container">
            <div class="item">
                <div class="replayArticleHeaderContainer">
                    <div class="textContainer">
                        <span class="topicFilter">
                            <a href="/events/analyst-market-briefings/" class="topicFilterText">Analyst Market Briefings</a>
                        </span>
                        <span class="title"><?php the_title(); ?></span>
                        <span class="dateReadTime"><?php echo $date->format('j F, Y'); ?></span>
                        <?php
                             $text = get_the_excerpt();
                             $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                        ?>
                        <span class="text-dark item-excerpt excerpt-scroll-to-content"><?php echo $trimmed_content; ?></span>
                        <span class="replay-button-container">
                            <span class="replay-button popup-vimeo" href="https://vimeo.com/<?php echo get_field('replay_vimeo_code'); ?>">Watch Replay</span>
                        </span>
                    </div>
                    <div class="imageSizeContainer">
                        <div class="bgContainer">
                            <?php $video_image = get_field( 'video_image' ); ?>
                            <?php if ( $video_image ) { ?>
                                <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_field('replay_vimeo_code'); ?>">
                        	       <?php echo wp_get_attachment_image( $video_image['ID'], 'full', false, array( 'alt' => $video_image['alt'] ) ); ?>
                                   <span class="opacity-overlay play-button-overlay"></span>
                               </a>
                            <?php } ?>
                        </div>
                        <span class="replay-button-container mobile">
                            <span class="replay-button popup-vimeo" href="https://vimeo.com/<?php echo get_field('replay_vimeo_code'); ?>">Watch Replay</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="webinar-article bg-white">
    	<div class="container">
    		<div class="column webinar-column first-column">
    			<span class="webinar-subtitle"><?php echo get_field( 'sub_title' ); ?></span>
    			<span class="webinar-content content">
    				<?php echo get_field( 'content' ); ?>
    			</span>
            </div>
            <div class="column webinar-column second-column<?php if( get_field( 'number_of_speakers' ) == 'one') { ?> speaker-column<?php } ?>">
                <?php if( get_field( 'number_of_speakers' ) == 'one') { ?>
                    <?php $post_object = get_field( 'speaker' ); ?>
                    <?php if ( $post_object ): ?>
                        <?php $post = $post_object; ?>
                        <?php setup_postdata( $post ); ?>
                            <div class="speaker-container-inner  bg-lightest">
                                <span class="webinar-subtitle speaker-subtitle">Meet the Speaker</span>
                                <span class="speaker-image">
                                    <img src="<?php echo get_field('speaker_image'); ?>" alt="<?php echo the_title(); ?>"/>
                                </span>
                                <span class="description">
                                    <span class="speaker-name"><?php echo the_title(); ?></span>
                                    <span class="speaker-role"><?php echo get_field('speaker_description'); ?></span>
                                </span>
                                <div class="textBlock">
                                    <?php
                                         $text = get_field('speaker_details');
                                         $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                    ?>
                                    <span class="speaker-details-excerpt"><?php echo $trimmed_content; ?></span>
                                    <span class="speaker-details"><?php echo get_field('speaker_details'); ?></span>
                                </div>
                            </div>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                <?php } else { ?>
                    <div class="column-image-container desktop">
                        <?php $side_image = get_field( 'side_image' ); ?>
                        <?php if ( $side_image ) { ?>
                        	<?php echo wp_get_attachment_image( $side_image['ID'], 'full', false, array( 'alt' => $side_image['alt'] ) ); ?>
                        <?php } ?>
                    </div>
                    <div class="column-image-container mobile">
                        <?php $side_image = get_field( 'side_image' ); ?>
                        <?php if ( $side_image ) { ?>
                        	<?php echo wp_get_attachment_image( $side_image['ID'], 'full', false, array( 'alt' => $side_image['alt'] ) ); ?>
                        <?php } ?>
                    </div>
                <?php }?>
            </div>
        </div>
    </section>
    <?php if( get_field( 'number_of_speakers' ) == 'more-than-one') { ?>
        <section class="webinar-speaker-block bg-lightest">
            <div class="container">
                <?php if ( have_rows( 'webinar_speakers' ) ) : ?>
                    <?php while ( have_rows( 'webinar_speakers' ) ) : the_row(); ?>
                        <span class="webinar-subtitle"><?php echo get_sub_field( 'title' ); ?></span>
                        <?php if ( have_rows( 'speaker' ) ) : ?>
    						<div class="speaker-container flex-speaker multiple-speakers">
    						<?php while ( have_rows( 'speaker' ) ) : the_row(); ?>
    							<div class="speaker-column-flex one-half">
    							<?php $post_object = get_sub_field( 'speaker' ); ?>
    							<?php if ( $post_object ): ?>
    								<?php $post = $post_object; ?>
    								<?php setup_postdata( $post ); ?>
    									<div class="speaker-container-inner">
    										<span class="speaker-image">
    											<img src="<?php echo get_field('speaker_image'); ?>" alt="<?php echo the_title(); ?>"/>
    										</span>
    										<span class="description">
    											<span class="speaker-name"><?php echo the_title(); ?></span>
    											<span class="speaker-role"><?php echo get_field('speaker_description'); ?></span>
    										</span>
    										<div class="textBlock">
                                                <?php
                                                     $text = get_field('speaker_details');
                                                     $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                ?>
                                                <span class="speaker-details-excerpt"><?php echo $trimmed_content; ?></span>
                                                <span class="speaker-details"><?php echo get_field('speaker_details'); ?></span>
    										</div>
    									</div>
    								<?php wp_reset_postdata(); ?>
    							<?php endif; ?>
    							</div>
    						<?php endwhile; ?>
    						</div>
    					<?php else : ?>
    						<?php // no rows found ?>
    					<?php endif; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </section>
    <?php } ?>
<?php } else { ?>
    <?php if ( get_field( 'post_layout_type' ) == 'slide-layout') { ?>
        <?php if ( have_rows( 'slide_preview_module' ) ) : ?>
            <?php while ( have_rows( 'slide_preview_module' ) ) : the_row(); ?>
                <section class="researchArticleTextHeader preview-module bg-white">
                    <div class="container">
                        <?php
                        $allowed_host = 'research.adapt.com.au';
                        $host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
                        if(substr($host, 0 - strlen($allowed_host)) == $allowed_host) { ?>
                            <script>

                            function goBack() {
                                window.history.back()
                            }
                            </script>
                            <a class="back-button" onclick="goBack()">Back</a>
                        <?php } else { ?>
                            <a class="back-button" href="<?php echo esc_url( home_url( '/' ) ); ?>/">Back</a>
                            <?php } ?>
                        <div class="item">
                                <?php if(current_user_can('memberpress_authorized')) { ?>
                                    <?php if ( has_term( ['sector-outlooks', 'persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                                        <?php if( $advantagePlus == 'yes') { ?> 
                                            <div class="slide-preview-container advantagePlus-<?php echo $advantagePlus; ?>">
                                                <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                    <div class="preview-main-slider">
                                                        <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                            <span class="main-slide">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                    <div class="preview-thumbnail-slider">
                                                        <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                            <span class="thumbnail-slide">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php } else { ?> 
                                            <?php if ( has_term( ['persona-profiles'], 'filter-types' )){ ?>
                                                <div class="slide-preview-container persona">
                                                    <div class="preview-main-slider">                                        
                                                        <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                                            <?php $slideMainCounter = 1; ?>                                                  
                                                            <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                <?php if($slideMainCounter == 1){ ?>
                                                                    <span class="main-slide non-member">
                                                                        <span class="bg-container">
                                                                            <?php $image = get_sub_field( 'image' ); ?>
                                                                            <?php if ( $image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </span>
                                                                <?php } else { } ?>
                                                            <?php $slideMainCounter++;?>
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                        <?php if ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : ?>                                       
                                                            <?php while ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : the_row(); ?>
                                                                <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                                    <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                        <span class="main-slide">
                                                                            <span class="bg-container">
                                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                                <?php if ( $image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    <?php endwhile; ?>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?>                                              
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="preview-thumbnail-slider">
                                                        <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                            <?php $slideThumbCounter = 1; ?>   
                                                            <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                <?php if($slideThumbCounter == 1){ ?>
                                                                    <span class="thumbnail-slide">
                                                                        <span class="bg-container">
                                                                            <?php $image = get_sub_field( 'image' ); ?>
                                                                            <?php if ( $image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </span>
                                                                <?php } else { } ?>
                                                                <?php $slideThumbCounter++;?>
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                        <?php if ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : ?>                                       
                                                            <?php while ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : the_row(); ?>
                                                                <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                                    <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                        <span class="thumbnail-slide">
                                                                            <span class="bg-container">
                                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                                <?php if ( $image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    <?php endwhile; ?>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?>                                              
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </div>                                   
                                                </div>
                                                <script>
                                                    jQuery(document).ready(function() {
                                                        jQuery("img").on("contextmenu", function(e) {
                                                            return false;
                                                        });
                                                    });
                                                </script>
                                            <?php } else { ?> 
                                                <div class="slide-preview-container sector-preview">
                                                    <div class="preview-main-slider">                                        
                                                        <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                                            <?php $slideMainCounter = 1; ?>                                                  
                                                            <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                <?php if($slideMainCounter == 1){ ?>
                                                                    <span class="main-slide non-member">
                                                                        <span class="bg-container">
                                                                            <?php $image = get_sub_field( 'image' ); ?>
                                                                            <?php if ( $image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </span>
                                                                <?php } else { } ?>
                                                            <?php $slideMainCounter++;?>
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                        <?php if ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : ?>                                       
                                                            <?php while ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : the_row(); ?>
                                                                <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                                    <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                        <span class="main-slide">
                                                                            <span class="bg-container">
                                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                                <?php if ( $image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    <?php endwhile; ?>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?>                                              
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="preview-thumbnail-slider">
                                                        <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                            <?php $slideThumbCounter = 1; ?>   
                                                            <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                <?php if($slideThumbCounter == 1){ ?>
                                                                    <span class="thumbnail-slide">
                                                                        <span class="bg-container">
                                                                            <?php $image = get_sub_field( 'image' ); ?>
                                                                            <?php if ( $image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </span>
                                                                <?php } else { } ?>
                                                                <?php $slideThumbCounter++;?>
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                        <?php if ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : ?>                                       
                                                            <?php while ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : the_row(); ?>
                                                                <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                                    <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                        <span class="thumbnail-slide">
                                                                            <span class="bg-container">
                                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                                <?php if ( $image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    <?php endwhile; ?>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?>                                              
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </div>                                   
                                                </div>
                                                <script>
                                                    jQuery(document).ready(function() {
                                                        jQuery("img").on("contextmenu", function(e) {
                                                            return false;
                                                        });
                                                    });
                                                </script>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="slide-preview-container">
                                            <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                <div class="preview-main-slider">
                                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                        <span class="main-slide">
                                                            <span class="bg-container">
                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                <?php if ( $image ) { ?>
                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </span>
                                                    <?php endwhile; ?>
                                                </div>
                                                <div class="preview-thumbnail-slider">
                                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                        <span class="thumbnail-slide">
                                                            <span class="bg-container">
                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                <?php if ( $image ) { ?>
                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </span>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php } ?>                                    
                                <?php } else { ?> 
                                    <div class="slide-preview-container">
                                        <div class="preview-main-slider">                                        
                                            <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                                <?php $slideMainCounter = 1; ?>                                                  
                                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                    <?php if($slideMainCounter == 1){ ?>
                                                        <span class="main-slide non-member">
                                                            <span class="bg-container">
                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                <?php if ( $image ) { ?>
                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </span>
                                                    <?php } else { } ?>
                                                <?php $slideMainCounter++;?>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                            <?php if ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : ?>                                       
                                                <?php while ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                        <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                            <span class="main-slide">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>                                              
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="preview-thumbnail-slider">
                                            <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                <?php $slideThumbCounter = 1; ?>   
                                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                    <?php if($slideThumbCounter == 1){ ?>
                                                        <span class="thumbnail-slide">
                                                            <span class="bg-container">
                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                <?php if ( $image ) { ?>
                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </span>
                                                    <?php } else { } ?>
                                                    <?php $slideThumbCounter++;?>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                            <?php if ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : ?>                                       
                                                <?php while ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                        <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                            <span class="thumbnail-slide">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>                                              
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        </div>                                   
                                    </div>
                                    <script>
                                        jQuery(document).ready(function() {
                                                jQuery("img").on("contextmenu", function(e) {
                                                    return false;
                                                });
                                        });
                                    </script>
                                <?php } ?>
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
                                        }
                                        if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                            $postType->name = 'Voice of Customers';
                                        }
                                        ?>
                                        <?php if($postTopic){?>
                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                        <?php } ?>
                                        <?php if($postType){?>
                                            <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                        <?php } ?>
                                    </span>
                                    <span class="title"><?php the_title(); ?></span>
                                    <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?></span>
                                    <span class="text"><?php echo get_sub_field( 'overview_text' ); ?></span>
                                    <?php if(current_user_can('memberpress_authorized')) { ?>
                                        <?php if ( has_term( ['sector-outlooks', 'persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                                            <?php if( $advantagePlus == 'yes') { ?> 
                                                <a class="scroll-to-overview" href="">Read overview</a>
                                                <?php $download = get_sub_field( 'download' ); ?>
                                                <?php if ( $download ) { ?>
                                                    <a class="download button red-button" target="_blank" href="<?php echo $download['url']; ?>">Download</a>                                             
                                                <?php } ?>
                                            <?php } else { ?> 
                                                <?php if ( has_term( ['persona-profiles' ], 'filter-types' )){ ?>
                                                     <?php $download = get_sub_field( 'download' ); ?>
                                                    <?php if ( $download ) { ?>    
                                                        <a class="download button red-button disabled-button locked-request" href="#requestdownloadPersona">Request Access</a>                                       
                                                    <?php } ?>
                                                <?php } else { ?> 
                                                     <?php $download = get_sub_field( 'download' ); ?>
                                                    <?php if ( $download ) { ?>    
                                                        <a class="download button red-button disabled-button locked-request" href="#requestdownloadSector">Request Access</a>                                       
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } else { ?> 
                                            <a class="scroll-to-overview" href="">Read overview</a>
                                            <?php $download = get_sub_field( 'download' ); ?>
                                            <?php if ( $download ) { ?>
                                                <a class="download button red-button" target="_blank" href="<?php echo $download['url']; ?>">Download</a>                                             
                                            <?php } ?>
                                        <?php } ?>                                        
                                    <?php } else { ?>
                                        <?php $download = get_sub_field( 'download' ); ?>
                                        <?php if ( $download ) { ?>  
                                            <?php $downloadButtonText = get_field('request_download_button_text'); ?>  
                                            <a class="download button red-button disabled-button locked-request" href="#requestdownload"><?php if($downloadButtonText){ ?><?php echo $downloadButtonText; ?><?php } else { ?>Request to Download<?php } ?></a>                                       
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </section>
            <?php endwhile; ?>
        <?php else : ?>
        <?php endif; ?>
    <?php } else { ?>
        <?php if ( has_term( ['data-insights', 'market-narratives', 'persona-profiles' ], 'filter-types' ) && have_rows( 'preview_module' ) ) { ?>
            <?php if ( have_rows( 'preview_module' ) ) : ?>
                <?php while ( have_rows( 'preview_module' ) ) : the_row(); ?>
                    <section class="researchArticleTextHeader preview-module bg-white">
                        <div class="container">
                            <?php
                            $allowed_host = 'research.adapt.com.au';
                            $host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
                            if(substr($host, 0 - strlen($allowed_host)) == $allowed_host) { ?>
                                <script>

                                function goBack() {
                                    window.history.back()
                                }
                                </script>
                                <a class="back-button" onclick="goBack()">Back</a>
                            <?php } else { ?>
                                <a class="back-button" href="<?php echo esc_url( home_url( '/' ) ); ?>/">Back</a>
                                <?php } ?>
                            <div class="item">
                                    <?php if(current_user_can('memberpress_authorized')) { ?>
                                        <?php if ( has_term( ['sector-outlooks', 'persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                                            <?php if( $advantagePlus == 'yes') { ?> 
                                                <div class="slide-preview-container advantagePlus-<?php echo $advantagePlus; ?>">
                                                    <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                        <div class="preview-main-slider">
                                                            <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                <span class="main-slide">
                                                                    <span class="bg-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                        <div class="preview-thumbnail-slider">
                                                            <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                <span class="thumbnail-slide">
                                                                    <span class="bg-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php } else { ?> 
                                                <?php if ( has_term( ['persona-profiles'], 'filter-types' )){ ?>
                                                    <div class="slide-preview-container persona">
                                                        <div class="preview-main-slider">                                        
                                                            <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                                                <?php $slideMainCounter = 1; ?>                                                  
                                                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                    <?php if($slideMainCounter == 1){ ?>
                                                                        <span class="main-slide non-member">
                                                                            <span class="bg-container">
                                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                                <?php if ( $image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    <?php } else { } ?>
                                                                <?php $slideMainCounter++;?>
                                                                <?php endwhile; ?>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                            <?php if ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : ?>                                       
                                                                <?php while ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : the_row(); ?>
                                                                    <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                                        <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                            <span class="main-slide">
                                                                                <span class="bg-container">
                                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                                    <?php if ( $image ) { ?>
                                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                    <?php } ?>
                                                                                </span>
                                                                            </span>
                                                                        <?php endwhile; ?>
                                                                    <?php else : ?>
                                                                        <?php // no rows found ?>
                                                                    <?php endif; ?>                                              
                                                                <?php endwhile; ?>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="preview-thumbnail-slider">
                                                            <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                                <?php $slideThumbCounter = 1; ?>   
                                                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                    <?php if($slideThumbCounter == 1){ ?>
                                                                        <span class="thumbnail-slide">
                                                                            <span class="bg-container">
                                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                                <?php if ( $image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    <?php } else { } ?>
                                                                    <?php $slideThumbCounter++;?>
                                                                <?php endwhile; ?>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                            <?php if ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : ?>                                       
                                                                <?php while ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : the_row(); ?>
                                                                    <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                                        <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                            <span class="thumbnail-slide">
                                                                                <span class="bg-container">
                                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                                    <?php if ( $image ) { ?>
                                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                    <?php } ?>
                                                                                </span>
                                                                            </span>
                                                                        <?php endwhile; ?>
                                                                    <?php else : ?>
                                                                        <?php // no rows found ?>
                                                                    <?php endif; ?>                                              
                                                                <?php endwhile; ?>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        </div>                                   
                                                    </div>
                                                    <script>
                                                        jQuery(document).ready(function() {
                                                            jQuery("img").on("contextmenu", function(e) {
                                                                return false;
                                                            });
                                                        });
                                                    </script>
                                                <?php } else { ?> 
                                                    <div class="slide-preview-container">
                                                        <div class="preview-main-slider">                                        
                                                            <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                                                <?php $slideMainCounter = 1; ?>                                                  
                                                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                    <?php if($slideMainCounter == 1){ ?>
                                                                        <span class="main-slide non-member">
                                                                            <span class="bg-container">
                                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                                <?php if ( $image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    <?php } else { } ?>
                                                                <?php $slideMainCounter++;?>
                                                                <?php endwhile; ?>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                            <?php if ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : ?>                                       
                                                                <?php while ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : the_row(); ?>
                                                                    <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                                        <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                            <span class="main-slide">
                                                                                <span class="bg-container">
                                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                                    <?php if ( $image ) { ?>
                                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                    <?php } ?>
                                                                                </span>
                                                                            </span>
                                                                        <?php endwhile; ?>
                                                                    <?php else : ?>
                                                                        <?php // no rows found ?>
                                                                    <?php endif; ?>                                              
                                                                <?php endwhile; ?>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="preview-thumbnail-slider">
                                                            <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                                <?php $slideThumbCounter = 1; ?>   
                                                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                                    <?php if($slideThumbCounter == 1){ ?>
                                                                        <span class="thumbnail-slide">
                                                                            <span class="bg-container">
                                                                                <?php $image = get_sub_field( 'image' ); ?>
                                                                                <?php if ( $image ) { ?>
                                                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                        </span>
                                                                    <?php } else { } ?>
                                                                    <?php $slideThumbCounter++;?>
                                                                <?php endwhile; ?>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                            <?php if ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : ?>                                       
                                                                <?php while ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : the_row(); ?>
                                                                    <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                                        <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                            <span class="thumbnail-slide">
                                                                                <span class="bg-container">
                                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                                    <?php if ( $image ) { ?>
                                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                                    <?php } ?>
                                                                                </span>
                                                                            </span>
                                                                        <?php endwhile; ?>
                                                                    <?php else : ?>
                                                                        <?php // no rows found ?>
                                                                    <?php endif; ?>                                              
                                                                <?php endwhile; ?>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        </div>                                   
                                                    </div>
                                                    <script>
                                                        jQuery(document).ready(function() {
                                                            jQuery("img").on("contextmenu", function(e) {
                                                                return false;
                                                            });
                                                        });
                                                    </script>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="slide-preview-container">
                                                <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                    <div class="preview-main-slider">
                                                        <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                            <span class="main-slide">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                    <div class="preview-thumbnail-slider">
                                                        <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                            <span class="thumbnail-slide">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php } ?>
                                        
                                    <?php } else { ?> 
                                        <div class="slide-preview-container">
                                            <div class="preview-main-slider">                                        
                                                <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                                    <?php $slideMainCounter = 1; ?>                                                  
                                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                        <?php if($slideMainCounter == 1){ ?>
                                                            <span class="main-slide non-member">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>
                                                        <?php } else { } ?>
                                                    <?php $slideMainCounter++;?>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                                <?php if ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : ?>                                       
                                                    <?php while ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : the_row(); ?>
                                                        <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                            <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                <span class="main-slide">
                                                                    <span class="bg-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>                                              
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="preview-thumbnail-slider">
                                                <?php if ( have_rows( 'slider_images' ) ) : ?>
                                                    <?php $slideThumbCounter = 1; ?>   
                                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                        <?php if($slideThumbCounter == 1){ ?>
                                                            <span class="thumbnail-slide">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>
                                                        <?php } else { } ?>
                                                        <?php $slideThumbCounter++;?>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                                <?php if ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : ?>                                       
                                                    <?php while ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : the_row(); ?>
                                                        <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                            <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                                <span class="thumbnail-slide">
                                                                    <span class="bg-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>                                              
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>                                   
                                        </div>
                                        <script>
                                            jQuery(document).ready(function() {
                                                    jQuery("img").on("contextmenu", function(e) {
                                                        return false;
                                                    });
                                            });
                                        </script>
                                    <?php } ?>
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
                                            }
                                            if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                                $postType->name = 'Voice of Customers';
                                            }
                                            ?>
                                            <?php if($postTopic){?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                            <?php if($postType){?>
                                                <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <span class="title"><?php the_title(); ?></span>
                                        <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?></span>
                                        <span class="text"><?php echo get_sub_field( 'overview_text' ); ?></span>
                                        <?php if(current_user_can('memberpress_authorized')) { ?>
                                            <?php if ( has_term( ['sector-outlooks', 'persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                                                <?php if( $advantagePlus == 'yes') { ?> 
                                                    <a class="scroll-to-overview" href="">Read overview</a>
                                                    <?php $download = get_sub_field( 'download' ); ?>
                                                    <?php if ( $download ) { ?>
                                                        <a class="download button red-button" target="_blank" href="<?php echo $download['url']; ?>">Download</a>                                             
                                                    <?php } ?>
                                                <?php } else { ?> 
                                                    <?php if ( has_term( ['persona-profiles' ], 'filter-types' )){ ?>
                                                        <?php $download = get_sub_field( 'download' ); ?>
                                                        <?php if ( $download ) { ?>    
                                                            <a class="download button red-button disabled-button locked-request" href="#requestdownloadPersona">Request Access</a>                                       
                                                        <?php } ?>
                                                    <?php } else { ?> 
                                                        <?php $download = get_sub_field( 'download' ); ?>
                                                        <?php if ( $download ) { ?>    
                                                            <a class="download button red-button disabled-button locked-request" href="#requestdownloadSector">Request Access</a>                                       
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?> 
                                                <a class="scroll-to-overview" href="">Read overview</a>
                                                <?php $download = get_sub_field( 'download' ); ?>
                                                <?php if ( $download ) { ?>
                                                    <a class="download button red-button" target="_blank" href="<?php echo $download['url']; ?>">Download</a>                                             
                                                <?php } ?>
                                            <?php } ?>                                        
                                        <?php } else { ?>
                                            <?php $download = get_sub_field( 'download' ); ?>
                                            <?php if ( $download ) { ?>    
                                                <?php $downloadButtonText = get_field('request_download_button_text'); ?>  
                                                <a class="download button red-button disabled-button locked-request" href="#requestdownload"><?php if($downloadButtonText){ ?><?php echo $downloadButtonText; ?><?php } else { ?>Request to Download<?php } ?></a>                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                <?php endwhile; ?>
            <?php else : ?>
            <?php endif; ?>
        <?php } else { ?>
            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                <section class="expertPresentationFeatured bg-dark singleResearch">
                    <div class="container">
                        <?php
                        $allowed_host = 'research.adapt.com.au';
                        $host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
                        if(substr($host, 0 - strlen($allowed_host)) == $allowed_host) { ?>
                            <script>

                            function goBack() {
                                window.history.back()
                            }
                            </script>
                            <a class="back-button" onclick="goBack()">Back</a>
                        <?php } else { ?>
                            <a class="back-button" href="<?php echo esc_url( home_url( '/' ) ); ?>/">Back</a>
                        <?php } ?>
                        <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php if( get_field('vimeo_code')){ ?>
                                <a href="https://vimeo.com/<?php echo get_field('vimeo_code'); ?>" class="image popup-vimeo">
                            <?php } else { ?>
                                <a href="" class="image postPlayBtn">
                            <?php }?>
                        <?php } else { ?>

                        <?php }?>
                            <div class="imageSizeContainer">
                                <span class="overlayGradient"></span>
                                <div class="bgContainer">
                                    <?php $image = get_field('video_poster'); ?>
                                    <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
                                </div>
                                <?php if(current_user_can('memberpress_authorized')) { ?>
                                    <span class="watchIcon"></span>
                                <?php } else { ?>
                                    <span class="lockedwatchIcon"></span>
                                <?php } ?>
                                <span class="textContainer">
                                    <span class="title"><?php the_title(); ?></span>
                                </span>
                            </div>
                        <?php if(current_user_can('memberpress_authorized')) { ?>
                            </a>
                        <?php } ?>
                        <span class="nextSection">
                            <span class="nextSectionText"><?php if(get_field('read_summary_text')){ ?><?php echo get_field('read_summary_text');?><?php } else { ?>Read Summary<?php } ?></span>
                        </span>
                    </div>
                    <div class="videoPlayerContainer print-no">
                        <span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                        <div class="videoWrapper">
                            <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                <source type="video/mp4" src="<?php echo get_field('featured_video_vimeo_code'); ?>" />
                            </video>
                        </div>
                    </div>
                </section>
            <?php } else { ?>
                <section class="researchArticleTextHeader bg-white">
                    <div class="container">
                        <?php
                        $allowed_host = 'research.adapt.com.au';
                        $host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
                        if(substr($host, 0 - strlen($allowed_host)) == $allowed_host) { ?>
                            <script>

                            function goBack() {
                                window.history.back()
                            }
                            </script>
                            <a class="back-button" onclick="goBack()">Back</a>
                        <?php } else { ?>
                            <a class="back-button" href="<?php echo esc_url( home_url( '/' ) ); ?>/">Back</a>
                        <?php } ?>
                        <div class="item">
                            <div class="imageSizeContainer">
                                <div class="bgContainer">
                                    <?php if ( get_field( 'listing_image') ) { ?>
                                        <?php $image = get_field( 'listing_image'); ?>
                                    <?php } else { ?>
                                        <?php $image = get_field( 'featured_image'); ?>
                                    <?php } ?>
                                    <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
                                </div>
                                <?php if ( get_field ( 'image_caption' )) { ?>
                                    <div class="caption"><?php echo get_field ( 'image_caption' ); ?></div>
                                <?php } ?>
                            </div>
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
                                    }
                                    if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                        $postType->name = 'Voice of Customers';
                                    }
                                    ?>
                                    <?php if($postTopic){?>
                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                    <?php } ?>
                                    <?php if($postType){?>
                                        <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                    <?php } ?>
                                </span>
                                <span class="title"><?php the_title(); ?></span>                        
                                <?php if (get_field('contributors')) { ?>
                                    <?php $totalCount = count(get_field('contributors')); ?>
                                <?php } ?>
                                <?php if ( have_rows( 'contributors' ) ) : ?>
                                    <span class="author">by
                                        <?php $count = 0; ?>
                                        <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                            <?php $post_object = get_sub_field( 'contributor_name' ); ?>
                                            <?php if ( $post_object ): ?>
                                                <?php $post = $post_object; ?>
                                                <?php setup_postdata( $post ); ?>
                                                    <span class="authorName"><?php the_title(); ?><?php if ($count == $totalCount - 1){?> <?php } else { ?><span class="comma"><?php if ($count == $totalCount - 2){?> and <?php } else { ?>, <?php } ?></span><?php } ?></span>
                                                <?php endif; ?>
                                            <?php wp_reset_postdata(); ?>
                                            <?php $count++; ?>
                                        <?php endwhile; ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($postType->slug == 'workshop-recordings' || $postType->slug == 'case-studies' || $postType->slug == 'best-practices' || $postType->slug == 'market-narratives'){ ?>
                                    <span class="dateReadTime"><?php if (get_field( 'read_time' )) { ?><?php echo get_field('read_time'); ?><?php } ?></span>
                                <?php } else { ?>
                                    <span class="dateReadTime"><span class="dateRead"><?php echo get_the_date('M j, Y'); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>
        <?php } ?> 
    <?php } ?>


    <?php if( has_term( 'expert-presentations', 'filter-types' ) || has_term( 'community-interviews', 'filter-types' ) || has_term( 'workshop-recordings', 'filter-types' )) {  ?>
        <article class="articleWrapper bg-white">
            <div class="container">
                <div class="column first">
                    <span class="saveInsight">
                        <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php echo do_shortcode('[favorite_button]'); ?>
                        <?php } ?>
                    </span>
                </div>
                <div class="column second">
                    <div class="article">
                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
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
                                }
                                if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                    $postType->name = 'Voice of Customers';
                                }
                                ?>
                                <?php if($postTopic){?>
                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                <?php } ?>
                                <?php if($postType){?>
                                    <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                <?php } ?>
                            </span>
                            <h1 class="title"><?php the_title(); ?></h1>
                             <?php if ($postType->slug == 'workshop-recordings' || $q->slug == 'case-studies' || $q->slug == 'best-practices' || $postType->slug == 'market-narratives'){ ?>
                                <span class="dateReadTime"><?php if (get_field( 'read_time' )) { ?><?php echo get_field('read_time'); ?><?php } ?></span>

                            <?php } else { ?>
                                <span class="dateReadTime"><span class="dateRead"><?php echo get_the_date('M j, Y'); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                            <?php } ?>
                        <?php } ?>
                        <?php if(current_user_can('memberpress_authorized')) { ?>
                            <span class="saveInsight mobile">
                                <?php echo do_shortcode('[favorite_button]'); ?>
                            </span>
                        <?php } ?>                       
                        <?php if (get_field('article_content')){ ?>
                            <div class="article-content">
                                <?php if(current_user_can('memberpress_authorized')) { ?>
                                 <?php echo get_field('article_content'); ?>
                                <?php } else { ?>
                                    <?php if ($previewContent == false){ ?>
                                        <div class="content-trimmed">
                                            <?php
                                                echo force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( wpautop( get_field( 'article_content' )) ), 150, $more ) ) );
                                            ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <!--  -->
                            </div>
                        <?php } else { ?>
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php } else { ?>
                                <?php if ($previewContent == false){ ?>
                                    <div class="content-trimmed">
                                        <?php
                                        $text = get_the_excerpt();
                                        if($text){?>
                                            <p><?php echo $text; ?></p>
                                            <?php
                                        } else {
                                        } ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>                        
                        <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php if ( have_rows( 'content_blocks' ) ): ?>
                            <?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
                                <?php if ( get_row_layout() == 'article_content' ) : ?>
                                   <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos singlePost repeatableSingle">
                                       <div class="container">
                                           <div class="post-inner">
                                               <div class="fullWidth article-content">
                                                   <div class="articleWrapper">
                                                       <?php echo get_sub_field( 'article_content' ); ?>
                                                       <?php if( get_sub_field( 'infogram_image' )) { ?>
                                                           <img class="delete-no" style="display: none;" src="<?php echo get_sub_field( 'infogram_image' ); ?>"/>
                                                       <?php } ?>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </section>
                                <?php elseif ( get_row_layout() == 'feature_image_or_infogram' ) : ?>
                                   <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullImageInfogram members-logged-in">
                                       <div class="container">
                                            <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
                                                <div class="featureBlock">
                                                    <img class="featureImage" src="<?php echo get_sub_field( 'image' ); ?>"/>
                                                </div>
                                            <?php } else { ?>
                                                <div class="infogram-container">
                                                    <?php echo get_sub_field( 'infogram' ); ?>
                                                </div>
                                                <img class="delete-no" style="display: none;" src="<?php echo get_sub_field( 'infogram_image' ); ?>"/>
                                            <?php } ?>
                                       </div>
                                   </section>
                                <?php elseif ( get_row_layout() == 'image_grid_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only imageGridBlock standard <?php echo get_sub_field( 'background_colour' ); ?>">
                                        <div class="container">
                                            <div class="inner">
                                                <h2><?php echo get_sub_field( 'block_title' ); ?></h2>

                                    			<?php if ( have_rows( 'item' ) ) : ?>
                                                    <div class="gridWrapper">
                                        				<?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <?php if ( get_sub_field( 'image') ) { ?>
                                                                    <div class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <hr>
                                            					<span class="title">
                                                                    <?php echo get_sub_field( 'title' ); ?>
                                                                </span>
                                                                <span class="description">
                                                                    <?php echo get_sub_field( 'description' ); ?>
                                                                </span>
                                                                <?php if ( get_sub_field( 'logo') ) { ?>
                                                                    <div class="logoContainer">
                                                                        <img src="<?php echo get_sub_field( 'logo' ); ?>" alt="Adapt" />
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                        				<?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ( have_rows( 'button_block' ) ) : ?>
                                                <div class="buttonBlock">
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'logo_grid' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos logoGrid <?php echo get_sub_field( 'background_colour' ); ?>">
                                		<div class="container">
                                			<div class="titleBlock">
                                				<span class="title">
                                					<h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                				</span>

                                				<span class="description <?php echo get_sub_field( 'top_right_text_position' ); ?>">
                                					<h3><?php echo get_sub_field( 'top_right_text' ); ?></h3>
                                				</span>
                                			</div>

                                			<?php if ( have_rows( 'logos' ) ) : ?>
                                				<div class="logoBlock">
                                					<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                						<div class="logo">
                                							<span class="logoContainer">
                                								<div class="image" style="background-image: url(<?php echo get_sub_field( 'logo' ); ?>);">
                                								</div>
                                							</span>
                                							<span class="logoTitle">
                                								<?php echo get_sub_field( 'title' ); ?>
                                							</span>
                                						</div>
                                					<?php endwhile; ?>
                                				</div>
                                			<?php endif; ?>

                                			<?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                				<a class="logoBlockLink <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                			<?php } ?>
                                		</div>
                                	</section>
                                <?php elseif ( get_row_layout() == 'download_block_single' ) : ?>
                                    <?php get_template_part( 'templates/components/_download-block' ); ?>
                                <?php elseif ( get_row_layout() == 'download_block_double' ) : ?>
                                    <?php get_template_part( 'templates/components/_download-block-two-columns' ); ?>
                                <?php elseif ( get_row_layout() == 'download_block_triple' ) : ?>
                                    <?php get_template_part( 'templates/components/_download-block-three-columns' ); ?>
                                <?php elseif ( get_row_layout() == 'video_grid_block_two_column' ) : ?>
                                    <?php get_template_part( 'templates/components/_video-block-two-columns' ); ?>
                                <?php elseif ( get_row_layout() == 'video_grid_block_three_column' ) : ?>
                                    <?php get_template_part( 'templates/components/_video-block-three-columns' ); ?>
                                <?php elseif ( get_row_layout() == 'two_column_card_block' ) : ?>
                                    <?php get_template_part( 'templates/components/_two-column-card' ); ?>
                                <?php elseif ( get_row_layout() == 'speaker_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only imageGridBlock speakerBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                        <div class="container">
                                            <div class="inner">
                                                <h2><?php echo get_sub_field( 'block_title' ); ?></h2>

                                    			<?php if ( have_rows( 'speakers' ) ) : ?>
                                                    <div class="gridWrapper">
                                        				<?php while ( have_rows( 'speakers' ) ) : the_row(); ?>

                                                            <?php $post_object = get_sub_field( 'speaker' ); ?>
                                        					<?php if ( $post_object ): ?>
                                                                <a href="<?php the_permalink(); ?>" class="item">
                                            						<?php $post = $post_object; ?>
                                            						<?php setup_postdata( $post ); ?>
                                                                        <?php if ( get_field( 'speaker_image') ) { ?>
                                                                            <div class="imageContainer">
                                                                                <div class="image" style="background-image: url(<?php echo get_field( 'speaker_image' ); ?>);">
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <hr>
                                            							<span class="title"><?php the_title(); ?></span>
                                                                        <span class="description">
                                                                            <?php echo get_field( 'speaker_description' ); ?>
                                                                        </span>
                                                                        <?php if ( get_field( 'logo') ) { ?>
                                                                            <div class="logoContainer">
                                                                                <img src="<?php echo get_field( 'logo' ); ?>" alt="Adapt" />
                                                                            </div>
                                                                        <?php } ?>
                                            						<?php wp_reset_postdata(); ?>
                                                                </a>
                                        					<?php endif; ?>

                                        				<?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ( have_rows( 'button_block' ) ) : ?>
                                                <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                    <div class="buttonBlock <?php echo get_sub_field('link_orientation'); ?>">
                                                        <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
                                        <?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
                                <?php elseif ( get_row_layout() == 'two_column_text_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos twoColumnTextBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="titleBlock">
                                                    <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                                    <hr>

                                                </div>
                                                <div class="textBlock">
                                                    <?php echo get_sub_field( 'text_block' ); ?>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink text" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only textImageBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="title">
                                                    <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                    <hr>
                                                </div>
                                                <?php if ( have_rows( 'item' ) ) : ?>
                                                    <div class="itemsWrapper">
                                                        <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <?php if ( get_sub_field( 'image') ) { ?>
                                                                    <a href="<?php echo get_sub_field('link_url'); ?>" target="<?php echo get_sub_field('link_target'); ?>" class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                                        </div>
                                                                    </a>
                                                                <?php } ?>
                                                                <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                                                <span class="description">
                                                                    <?php echo get_sub_field( 'text' ); ?>
                                                                </span>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ( have_rows( 'button_block' ) ) : ?>
                                                <div class="buttonBlock">
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'half_text_half_image' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only halfHalfBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                        <div class="textBlock <?php echo get_sub_field( 'image_position' ); ?>">
                                            <div class="v-wrap">
                                                <div class="v-box">
                                                    <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                                    <hr>
                                                    <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                        <span class="desktopText"><?php echo get_sub_field( 'text_block' ); ?></span>
                                                    <?php } ?>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink desktop <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="imageBlock <?php echo get_sub_field( 'image_position' ); ?>">
                                            <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                            </div>
                                        </div>
                                        <div class="textBlock mobile">
                                            <div class="container">
                                                <div class="inner">
                                                    <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                        <span class="mobileText"><?php echo get_sub_field( 'text_block' ); ?></span>
                                                    <?php } ?>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
                                    <?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
                                <?php elseif ( get_row_layout() == 'speaker_quote_carousel' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no speakerQuoteCarousel">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="titleBlock">
                                                    <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink text" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                    <?php } ?>
                                                </div>

                                                <?php if ( have_rows( 'item' ) ) : ?>
                                                    <div class="owl-carousel speaker-gallery">
                                                        <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <div class="imageContainer">
                                                                    <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                                    </div>
                                                                </div>
                                                                <div class="textBlock">
                                                                    <div class="v-wrap">
                                                                        <div class="v-box">
                                                                            <span class="quoteBlock">
                                                                                <?php echo get_sub_field( 'quote' ); ?>
                                                                            </span>
                                                                            <span class="quoteAuthor">
                                                                                <?php echo get_sub_field( 'quote_author' ); ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'quote_block_with_no_image' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no quoteBlockNoImage">
                                        <div class="container">
                                            <div class="inner">
                                                <?php if ( have_rows( 'quotes' ) ) : ?>
                                                    <div class="owl-carousel quote">
                                                        <?php while ( have_rows( 'quotes' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <div class="v-wrap">
                                                                    <div class="v-box">
                                                                        <span class="quoteBlock">
                                                                            <?php echo get_sub_field( 'quote' ); ?>
                                                                        </span>
                                                                        <span class="quoteAuthor">
                                                                            <?php echo get_sub_field( 'quote_author' ); ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ( have_rows( 'button_block' ) ) : ?>
                                                <div class="buttonBlock">
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>
                                    <section id="<?php echo get_sub_field( 'id' ); ?>" class="scrollPos imageGridBlock standard logos">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="titleBlock">
                                                    <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                        <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                        <span class="hrWrapper">
                                                            <hr>
                                                        </span>
                                                    <?php } ?>
                                                    <?php if ( get_sub_field ( 'description' ) ) { ?>
                                                        <h3><?php echo get_sub_field( 'description' ); ?></h3>
                                                    <?php } ?>
                                                </div>

                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                    <div class="gridWrapper">
                                                        <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <div class="imageContainer">
                                                                    <div class="image" style="background-image: url(<?php echo get_sub_field( 'logo' ); ?>);">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endwhile; ?>
                                                        <div class="item">
                                                            <div class="v-wrap">
                                                                <div class="v-box">
                                                                    <span class="yourLogoHere">Your Company Here</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ( have_rows( 'button_block' ) ) : ?>
                                                <div class="buttonBlock">
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'counter_block' ) : ?>
                                    <?php get_template_part( 'templates/components/_counter-block' ); ?>
                                <?php elseif ( get_row_layout() == 'repeatable_counter_block' ) : ?>
                                    <?php get_template_part( 'templates/components/_repeatable-counter-block' ); ?>
                                <?php elseif ( get_row_layout() == 'membership_block' ) : ?>
                                    <?php if ( get_sub_field ( 'display_membership_block' ) == 'yes' ) { ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no pricingBlock">
                                            <div class="container">
                                                <h2>Membership</h2>
                                                <?php if ( have_rows( 'first_pricing_block', 'option' ) ) : ?>
                                                    <div class="pricingBlockItem first">
                                                        <div class="innerWrapper">
                                                            <?php while ( have_rows( 'first_pricing_block', 'option' ) ) : the_row(); ?>
                                                                <span class="title">
                                                                    <?php echo get_sub_field( 'title', 'option' ); ?>
                                                                    <span class="hrWrapper">
                                                                        <hr>
                                                                    </span>
                                                                </span>
                                                                <span class="priceBlockWrapper">
                                                                    <span class="priceBlock">
                                                                        <span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
                                                                    </span>
                                                                </span>
                                                                <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                    <div class="features">
                                                                        <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                            <span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
                                                                        <?php endwhile; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <span class="pricingButtonWrapper">
                                                                <a class="small" href="<?php echo get_sub_field( 'button_link', 'option' ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo get_sub_field( 'button_text', 'option' ); ?></a>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ( have_rows( 'featured_pricing_block', 'option' ) ) : ?>
                                                    <div class="pricingBlockItem featured">

                                                        <?php while ( have_rows( 'featured_pricing_block', 'option' ) ) : the_row(); ?>
                                                            <div class="innerWrapper">
                                                                <div class="featuredWrapper">
                                                                    <span class="title">
                                                                        <?php echo get_sub_field( 'title', 'option' ); ?>
                                                                        <span class="hrWrapper">
                                                                            <hr>
                                                                        </span>
                                                                    </span>
                                                                    <span class="priceBlockWrapper">
                                                                        <span class="priceBlock">
                                                                            <span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
                                                                        </span>
                                                                    </span>
                                                                    <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                        <div class="features">
                                                                            <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                <span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
                                                                            <?php endwhile; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <span class="pricingButtonWrapper">
                                                                <a class="small" href="<?php echo get_sub_field( 'button_link', 'option' ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo get_sub_field( 'button_text', 'option' ); ?></a>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ( have_rows( 'last_pricing_block', 'option' ) ) : ?>
                                                    <div class="pricingBlockItem last">
                                                        <?php while ( have_rows( 'last_pricing_block', 'option' ) ) : the_row(); ?>
                                                            <div class="innerWrapper">
                                                                <span class="title">
                                                                    <?php echo get_sub_field( 'title', 'option' ); ?>
                                                                    <span class="hrWrapper">
                                                                        <hr>
                                                                    </span>
                                                                </span>
                                                                <span class="priceBlockWrapper">
                                                                    <span class="priceBlock">
                                                                        <span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
                                                                    </span>
                                                                </span>
                                                                <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                    <div class="features">
                                                                        <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                            <span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
                                                                        <?php endwhile; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <span class="pricingButtonWrapper">
                                                                <a class="small" href="<?php echo get_sub_field( 'button_link', 'option' ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo get_sub_field( 'button_text', 'option' ); ?></a>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php } ?>
                                <?php elseif ( get_row_layout() == 'two_column_block_with_text_and_featured_quote' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only twoColumnWithTextAndFeaturedQuote">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="column first">
                                                    <h2>
                                                        <?php echo get_sub_field( 'title' ); ?>
                                                    </h2>
                                                    <div class="textBlock">
                                                        <?php echo get_sub_field( 'text_block' ); ?>
                                                    </div>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink text" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                    <?php } ?>
                                                </div>

                                                <div class="column last">
                                                    <div class="item">
                                                        <div class="v-wrap">
                                                            <div class="v-box">
                                                                <span class="quoteBlock">
                                                                    <?php echo get_sub_field( 'quote' ); ?>
                                                                </span>
                                                                <span class="quoteAuthor">
                                                                    <?php echo get_sub_field( 'quote_author' ); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'video_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no videoBlock postVideoBlock">
                                        <div class="container">
                                            <?php if( get_sub_field('vimeo_code_popup')){ ?>
                                                <a href="https://vimeo.com/<?php echo get_sub_field('vimeo_code_popup'); ?>" class="image popup-vimeo">
                                            <?php } else { ?>
                                                <a href="" class="image postPlayBtn">
                                            <?php } ?>
                                                <div class="imageSizeContainer">
                                                    <span class="overlayGradient"></span>
                                                    <div class="bgContainer">
                                                        <img class="desktop" src="<?php echo get_sub_field('video_poster_image'); ?>" alt="" />
                                                    </div>
                                                    <span class="watchIcon"></span>
                                                    <span class="textContainer">
                                                        <span class="title"><?php the_title(); ?></span>
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="videoPlayerContainer videoBlock">
                                            <span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                                            <div class="videoWrapper">
                                                <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                                    <source type="video/mp4" src="<?php echo get_sub_field('vimeo_code'); ?>" />
                                                </video>
                                            </div>
                                        </div>

                                    </section>
                                <?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
                                    <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>
                                <?php elseif ( get_row_layout() == 'full_width_text_editor' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullWidthTextEditor<?php if ( get_sub_field( 'font') ) { ?> <?php echo get_sub_field( 'font' );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php echo get_sub_field( 'font_colour' ); ?><?php } ?>">
                                        <div class="container">
                                            <?php echo get_sub_field( 'text_editor' ); ?>
                                            <?php if ( have_rows( 'button_block' ) ) : ?>
                                                <div class="buttonBlock">
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'form_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
                                		<div class="container">
                                			<div class="inner">
                                				<div class="formWrapper register">
                                					<?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                						<h2><?php echo get_sub_field('block_title'); ?></h2>
                                						<?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                							<hr>
                                						<?php } ?>
                                					<?php } ?>
                                					<?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                						<h3><?php echo get_sub_field('block_description'); ?></h3>
                                					<?php } ?>
                                					<?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                						<?php echo get_sub_field('form_shortcode'); ?>
                                					<?php }?>
                                					<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?>
                                						<a class="button popup-modal" href="#<?php echo get_sub_field('form_id'); ?>"><?php echo get_sub_field('button_text'); ?></a>
                                						<div class="formPopup mfp-hide" id="<?php echo get_sub_field('form_id'); ?>">
                                							<a class="popup-modal-dismiss"></a>
                                							<?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                								<h2><h2><?php echo get_sub_field('block_title'); ?></h2></h2>
                                							<?php } ?>
                                							<?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                								<h3><?php echo get_sub_field('block_description'); ?></h3>
                                							<?php } ?>
                                								<div class="formWrapper register"><?php echo get_sub_field('form_shortcode'); ?></div>
                                						</div>
                                					<?php }?>
                                				</div>
                                			</div>
                                		</div>
                                	</section>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php endif; ?>
                        <?php } ?>                           
                    </div>
                    <?php if(current_user_can('memberpress_authorized')) { ?>
                        <?php if ( have_rows( 'contributors' ) ) : ?>
                            <div class="authors">
                                <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                    <?php $post_object = get_sub_field( 'contributor_name' ); ?>
        							<?php if ( $post_object ): ?>
        								<?php $post = $post_object; ?>
        								<?php setup_postdata( $post ); ?>
        									<div class="speaker-container-inner">
        										<span class="speaker-image">
        											<img src="<?php echo get_field('speaker_image'); ?>" alt="<?php echo the_title(); ?>"/>
        										</span>
        										<span class="description">
                                                    <span class="title"><?php if(get_sub_field('contributors_pre_heading')){ ?><?php echo get_sub_field('contributors_pre_heading'); ?><?php } else { ?>Contributor<?php } ?></span>
        											<span class="speaker-name"><?php echo the_title(); ?></span>
        											<span class="speaker-role"><?php echo get_field('speaker_description'); ?></span>
        										</span>
                                                <div class="textBlock">
                                                    <?php
                                                         $text = get_field('speaker_details');
                                                         $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                    ?>
                                                    <span class="speaker-details-excerpt"><?php echo $trimmed_content; ?></span>
                                                    <span class="speaker-details">
                                                        <?php echo get_field('speaker_details'); ?>
                                                        <span class="speaker-details-less">Less</span>
                                                    </span>
                                                </div>
        									</div>
        								<?php wp_reset_postdata(); ?>
        							<?php endif; ?>
        					<?php endwhile; ?>
        					</div>
        				<?php else : ?>
        					<?php // no rows found ?>
        				<?php endif; ?>
                    <?php } ?>
                </div>
                <div class="column third">
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
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                    <?php if(current_user_can('memberpress_authorized')) { ?>
                        <?php if (get_field( 'download' ) == 'yes'){ ?>
                            <?php if ( have_rows( 'membership_ids_for_download', 'options' ) ) : ?>
                                <?php $counter = 0; ?>
                                    <?php while ( have_rows( 'membership_ids_for_download', 'options' ) ) : the_row(); ?>
                                        <?php if ( $counter == 0 ) {
                                           $members = $members . get_sub_field( 'membership_id' );
                                        } else {
                                           $members = $members . ',' . get_sub_field( 'membership_id' );
                                        } ?>
                                        <?php $counter++; ?>
                                    <?php endwhile; ?>
                                    <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                        <?php if ( has_term( ['sector-outlooks', 'persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                                            <?php if( $advantagePlus == 'yes') { ?> 
                                                <?php if ( have_rows( 'download_link' ) ) : ?>
                                                    <div class="articleShare downloadShareContainer">
                                                        <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText download desktop"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php } ?>
                                                            <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                            <?php if ( $preview_image ) { ?>
                                                                <span class="download-image-container <?php echo get_sub_field( 'image_orientation' ); ?>">
                                                                    <span class="bg-container">
                                                                        <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, array( 'alt' => $preview_image['alt'] ) ); ?>
                                                                    </span>
                                                                </span>
                                                            <?php } ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText download mobile"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php } ?>
                                                            <a id="downloadButton" href="<?php echo get_sub_field( 'download_url' ); ?>" target="_blank" class="button redOutline"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            <?php } ?> 
                                        <?php } else { ?> 
                                            <?php if ( have_rows( 'download_link' ) ) : ?>
                                                <div class="articleShare downloadShareContainer">
                                                    <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                                        <?php if (get_sub_field( 'text' )) { ?>
                                                            <span class="shareText download desktop"><?php echo get_sub_field( 'text' ); ?></span>
                                                        <?php } ?>
                                                        <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                        <?php if ( $preview_image ) { ?>
                                                            <span class="download-image-container <?php echo get_sub_field( 'image_orientation' ); ?>">
                                                                <span class="bg-container">
                                                                    <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, array( 'alt' => $preview_image['alt'] ) ); ?>
                                                                </span>
                                                            </span>
                                                        <?php } ?>
                                                        <?php if (get_sub_field( 'text' )) { ?>
                                                            <span class="shareText download mobile"><?php echo get_sub_field( 'text' ); ?></span>
                                                        <?php } ?>
                                                        <a id="downloadButton" href="<?php echo get_sub_field( 'download_url' ); ?>" target="_blank" class="button redOutline"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        <?php } ?>                                         
                                     <?php } ?>
                             <?php else : ?>
                                 <?php // no rows found ?>
                             <?php endif; ?>
                        <?php } ?>
                        <?php if ( have_rows( 'dataset_share' ) ) : ?>
                            <?php while ( have_rows( 'dataset_share' ) ) : the_row(); ?>
                                <?php $current_user = wp_get_current_user();
                                $first_name = $current_user->first_name;
                                $last_name = $current_user->last_name;
                                $shareTitle = get_the_title();
                                $sharedDescription = get_the_excerpt();
                                $formIntro = get_sub_field('form_introduction_title');
                                ?>
                                <div class="articleShare shareLinkContainer datasetShare">
                                    <?php if (get_sub_field( 'text' )) { ?>
                                        <span class="shareText share"><?php echo get_sub_field( 'text' ); ?></span>
                                    <?php } ?>
                                    <a href="#datasetsharepopupcontainer" class="button redOutline datasharepopup"><?php echo get_sub_field( 'button_text' ); ?></a>
                                    <span class="hidden-share-link" style="display: none;"><?php echo get_sub_field( 'share_download_url' ); ?></span>
                                    <span class="hidden-share-name" style="display: none;"><?php echo $first_name; ?> <?php echo $last_name; ?></span>
                                    <span class="hidden-share-title" style="display: none;"><?php echo $shareTitle; ?></span>
                                    <span class="hidden-share-excerpt" style="display: none;"><?php echo $sharedDescription; ?></span>
                                    <div style="display: none;">
                                        <div class="webinar-register-form datasetShare" id="datasetsharepopupcontainer">
                                            <div class="container">
                                                <span class="data-column-container">
                                                    <span class="image-column">
                                                        <span class="content-popup-container">
                                                            <h2><?php echo get_sub_field( 'text' ); ?></h2>
                                                            <span class="slide-container">
                                                                <span class="image-container">
                                                                    <span class="bg-container offset-image-container">
                                                                        <?php if ( $offsetimage ) { ?>
                                                                            <?php echo wp_get_attachment_image( $offsetimage['ID'], 'full', false, array( 'alt' => $offsetimage['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="bg-container">
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                            <span class="page-title"><?php the_title(); ?></span>
                                                        </span>
                                                    </span>
                                                    <span class="form-column">
                                                        <span class="form-container">
                                                            <h2 class="form-title"><?php echo $formIntro; ?></h2>
                                                            <?php echo get_field( 'post_share_form', 'option' ); ?>
                                                        </span>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php if (get_field( 'download' ) == 'yes'){ ?>
                                <?php $current_user = wp_get_current_user();
                                $first_name = $current_user->first_name;
                                $last_name = $current_user->last_name;
                                $shareTitle = get_the_title();
                                $sharedDescription = get_the_excerpt();
                                ?>
                                <?php if ( have_rows( 'membership_ids_for_share', 'options' ) ) : ?>
                                    <?php $counter = 0; ?>
                                        <?php while ( have_rows( 'membership_ids_for_share', 'options' ) ) : the_row(); ?>
                                            <?php if ( $counter == 0 ) {
                                            $members = $members . get_sub_field( 'membership_id' );
                                            } else {
                                            $members = $members . ',' . get_sub_field( 'membership_id' );
                                            } ?>
                                            <?php $counter++; ?>
                                        <?php endwhile; ?>
                                        <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                            <?php if ( have_rows( 'share' ) ) : ?>
                                                <div class="articleShare shareLinkContainer">
                                                    <?php while ( have_rows( 'share' ) ) : the_row(); ?>
                                                        <?php if (get_sub_field( 'text' )) { ?>
                                                            <span class="shareText share"><?php echo get_sub_field( 'text' ); ?></span>
                                                        <?php } ?>
                                                        <a href="#sharepopupcontainer" class="button redOutline sharepopup"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                        <span class="hidden-share-link" style="display: none;"><?php echo get_sub_field( 'share_download_url' ); ?></span>
                                                        <span class="hidden-share-name" style="display: none;"><?php echo $first_name; ?> <?php echo $last_name; ?></span>
                                                        <span class="hidden-share-title" style="display: none;"><?php echo $shareTitle; ?></span>
                                                        <span class="hidden-share-excerpt" style="display: none;"><?php echo $sharedDescription; ?></span>
                                                        <div style="display: none;">
                                                            <div class="webinar-register-form" id="sharepopupcontainer">
                                                                <div class="container">
                                                                    <span class="webinar-subtitle"><?php echo get_field( 'post_share_form_title', 'option' ); ?></span>
                                                                    <span class="form-container"><?php echo get_field( 'post_share_form', 'option' ); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        <?php } ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?> 
                        <?php endif; ?>
                    <?php } ?>
                    <div class="relatedArticles<?php if(current_user_can('memberpress_authorized')) { ?><?php } else { ?> mobile-hide<?php } ?>">
                        <h2 class="related">You may also like</h2>
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
                        <?php $type_terms = get_field( 'you_may_also_like' ); ?>
                        <?php $types = array(); ?>
                        <?php if ( $type_terms ){ ?>
                            <?php foreach ( $type_terms as $type_term ): ?>
                                <?php $types[] =  $type_term->slug; ?>
                            <?php endforeach; ?>
                        <?php
                            $args = array(
                                'post_type'      => 'post',
                                'posts_per_page' => 3,
                                'post__not_in' => array( $post->ID ),
                                'tax_query'      => array(
                                    'relation' => 'AND',
                                    array (
                                        'taxonomy' => 'topic',
                                        'field' => 'slug',
                                        'terms'    => $postTopic->slug
                                    ),
                                    array(
                                        'taxonomy' => 'filter-types',
                                        'field'    => 'slug',
                                        'terms' => $types,
                                        'operator' => 'IN',
                                    )
                                )
                            );?>
                        <?php } else {
                            $args = array(
                                'post_type'      => 'post',
                                'posts_per_page' => 3,
                                'post__not_in' => array( $post->ID ),
                                'tax_query'      => array(
                                    'relation' => 'AND',
                                    array (
                                        'taxonomy' => 'topic',
                                        'field' => 'slug',
                                        'terms'    => $postTopic->slug
                                    )
                                )
                            );?>
                        <?php }

                            $posts = new WP_Query( $args );
                            if( $posts->have_posts() ): ?>
                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                    <div class="item">
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
                                                }
                                                if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                                    $postType->name = 'Voice of Customers';
                                                }
                                                ?>
                                                <?php if($postTopic){?>
                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                                <?php } ?>
                                                <?php if($postType){?>
                                                    <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                                <?php } ?>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="title"><?php the_title();?></a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif;?>
                            <?php wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </article>
    <?php } else { ?> 
        <?php if ( have_rows( 'preview_module' ) ) { ?>
            <article class="articleWrapper bg-white">
                <div class="container">
                    <div class="column first">
                        <span class="saveInsight">
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                                <?php echo do_shortcode('[favorite_button]'); ?>
                            <?php } ?>
                        </span>
                    </div>
                    <div class="column second">
                        <div class="article">
                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
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
                                    }
                                    if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                        $postType->name = 'Voice of Customers';
                                    }
                                    ?>
                                    <?php if($postTopic){?>
                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                    <?php } ?>
                                    <?php if($postType){?>
                                        <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                    <?php } ?>
                                </span>
                                <h1 class="title"><?php the_title(); ?></h1>
                                <?php if ($postType->slug == 'workshop-recordings' || $postType->slug == 'case-studies' || $postType->slug == 'best-practices' || $postType->slug == 'market-narratives'){ ?>
                                    <span class="dateReadTime"><?php if (get_field( 'read_time' )) { ?><?php echo get_field('read_time'); ?><?php } ?></span>

                                <?php } else { ?>
                                    <span class="dateReadTime"><span class="dateRead"><?php echo get_the_date('M j, Y'); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                <?php } ?>
                            <?php } ?>
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                                <span class="saveInsight mobile">                            
                                    <?php echo do_shortcode('[favorite_button]'); ?>                                
                                </span>
                            <?php } ?>
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php } else { ?> 
                                <?php $previewContent = false; ?>
                                <?php if ( have_rows( 'members_only_preview_content' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_preview_content' ) ) : the_row(); ?>
                                        <?php if( get_sub_field( 'preview_text' )){ ?>
                                            <?php $previewContent = true; ?>
                                            <?php $previewText = get_sub_field( 'preview_text' ); ?>
                                        <?php } ?>                                                                             
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                            <?php if (get_field('article_content')){ ?>
                                <div class="article-content">
                                    <?php if(current_user_can('memberpress_authorized')) { ?>
                                    <?php echo get_field('article_content'); ?>
                                    <?php } else { ?>
                                        <?php if ($previewContent == false){ ?>
                                            <div class="content-trimmed">
                                                <?php
                                                    echo force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( wpautop( get_field( 'article_content' )) ), 150, $more ) ) );
                                                ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <!--  -->
                                </div>
                            <?php } else { ?>
                                <?php if(current_user_can('memberpress_authorized')) { ?>
                                <?php } else { ?>
                                <?php if ($previewContent == false){ ?>
                                        <div class="content-trimmed">
                                            <?php
                                            $text = get_the_excerpt();
                                            if($text){?>
                                                <p><?php echo $text; ?></p>
                                                <?php
                                            } else {
                                            } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php } else { ?>
                                <?php if ( have_rows( 'members_only_preview_content' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_preview_content' ) ) : the_row(); ?>
                                        <div class="content-trimmed">
                                            <?php echo $previewText; ?>
                                        </div>                                                                      
                                        <?php $image = get_sub_field( 'image' ); ?>                                
                                        <?php if ( $image ) { ?>
                                            <div class="preview-image-container">
                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                            </div>
                                        <?php } ?>
                                        <?php if ( have_rows( 'cta' ) ) : ?>
                                            <?php while ( have_rows( 'cta' ) ) : the_row(); ?>
                                                <div class="preview-cta-container background-pink">
                                                    <div class="preview-cta-inner">
                                                        <div class="preview-cta-image-column desktop">
                                                            <span class="image-container">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>                                                    
                                                        </div>
                                                        <div class="preview-cta-content">
                                                            <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                                            <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <div class="preview-cta-image mobile">
                                                                <span class="image-container">
                                                                    <span class="bg-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                </span>                                                    
                                                            </div>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                                <?php $buttonCounter = 1; ?>
                                                                <span class="button-container">                                                                                                                   
                                                                    <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                        <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                                                                            <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                                        <?php } else { ?> 
                                                                            <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#previewCTA<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                                            <div style="display: none;">         
                                                                                <div class="preview-cta-form login-form-container" id="previewCTA<?php echo $buttonCounter; ?>">
                                                                                    <span class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                                                        <?php $buttonCounter++; ?>
                                                                    <?php endwhile; ?>
                                                                </span>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        $postID = get_the_ID();
                                                        $postURL = get_permalink();
                                                    ?>
                                                    <span class="preview-cta-bottom-module"><?php echo get_sub_field( 'login_text' ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo $postID;?>&redirect_to=<?php echo $postURL;?>" target="_self">Login here</a></span>
                                                </div>                                        
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                                <?php if ( have_rows( 'content_blocks' ) ): ?>
                                <?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
                                    <?php if ( get_row_layout() == 'article_content' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos singlePost repeatableSingle">
                                        <div class="container">
                                            <div class="post-inner">
                                                <div class="fullWidth article-content">
                                                    <div class="articleWrapper">
                                                        <?php echo get_sub_field( 'article_content' ); ?>
                                                        <?php if( get_sub_field( 'infogram_image' )) { ?>
                                                            <img class="delete-no" style="display: none;" src="<?php echo get_sub_field( 'infogram_image' ); ?>"/>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <?php elseif ( get_row_layout() == 'feature_image_or_infogram' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullImageInfogram members-logged-in">
                                        <div class="container">
                                                <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
                                                    <div class="featureBlock">
                                                        <img class="featureImage" src="<?php echo get_sub_field( 'image' ); ?>"/>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="infogram-container">
                                                        <?php echo get_sub_field( 'infogram' ); ?>
                                                    </div>
                                                    <img class="delete-no" style="display: none;" src="<?php echo get_sub_field( 'infogram_image' ); ?>"/>
                                                <?php } ?>
                                        </div>
                                    </section>
                                    <?php elseif ( get_row_layout() == 'image_grid_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only imageGridBlock standard <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <h2><?php echo get_sub_field( 'block_title' ); ?></h2>

                                                    <?php if ( have_rows( 'item' ) ) : ?>
                                                        <div class="gridWrapper">
                                                            <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <?php if ( get_sub_field( 'image') ) { ?>
                                                                        <div class="imageContainer">
                                                                            <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <hr>
                                                                    <span class="title">
                                                                        <?php echo get_sub_field( 'title' ); ?>
                                                                    </span>
                                                                    <span class="description">
                                                                        <?php echo get_sub_field( 'description' ); ?>
                                                                    </span>
                                                                    <?php if ( get_sub_field( 'logo') ) { ?>
                                                                        <div class="logoContainer">
                                                                            <img src="<?php echo get_sub_field( 'logo' ); ?>" alt="Adapt" />
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'logo_grid' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos logoGrid <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="titleBlock">
                                                    <span class="title">
                                                        <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                    </span>

                                                    <span class="description <?php echo get_sub_field( 'top_right_text_position' ); ?>">
                                                        <h3><?php echo get_sub_field( 'top_right_text' ); ?></h3>
                                                    </span>
                                                </div>

                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                    <div class="logoBlock">
                                                        <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                            <div class="logo">
                                                                <span class="logoContainer">
                                                                    <div class="image" style="background-image: url(<?php echo get_sub_field( 'logo' ); ?>);">
                                                                    </div>
                                                                </span>
                                                                <span class="logoTitle">
                                                                    <?php echo get_sub_field( 'title' ); ?>
                                                                </span>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                    <a class="logoBlockLink <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                <?php } ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'download_block_single' ) : ?>
                                        <?php get_template_part( 'templates/components/_download-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'download_block_double' ) : ?>
                                        <?php get_template_part( 'templates/components/_download-block-two-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'download_block_triple' ) : ?>
                                        <?php get_template_part( 'templates/components/_download-block-three-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'video_grid_block_two_column' ) : ?>
                                        <?php get_template_part( 'templates/components/_video-block-two-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'video_grid_block_three_column' ) : ?>
                                        <?php get_template_part( 'templates/components/_video-block-three-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'two_column_card_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_two-column-card' ); ?>
                                    <?php elseif ( get_row_layout() == 'speaker_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only imageGridBlock speakerBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <h2><?php echo get_sub_field( 'block_title' ); ?></h2>

                                                    <?php if ( have_rows( 'speakers' ) ) : ?>
                                                        <div class="gridWrapper">
                                                            <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>

                                                                <?php $post_object = get_sub_field( 'speaker' ); ?>
                                                                <?php if ( $post_object ): ?>
                                                                    <a href="<?php the_permalink(); ?>" class="item">
                                                                        <?php $post = $post_object; ?>
                                                                        <?php setup_postdata( $post ); ?>
                                                                            <?php if ( get_field( 'speaker_image') ) { ?>
                                                                                <div class="imageContainer">
                                                                                    <div class="image" style="background-image: url(<?php echo get_field( 'speaker_image' ); ?>);">
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <hr>
                                                                            <span class="title"><?php the_title(); ?></span>
                                                                            <span class="description">
                                                                                <?php echo get_field( 'speaker_description' ); ?>
                                                                            </span>
                                                                            <?php if ( get_field( 'logo') ) { ?>
                                                                                <div class="logoContainer">
                                                                                    <img src="<?php echo get_field( 'logo' ); ?>" alt="Adapt" />
                                                                                </div>
                                                                            <?php } ?>
                                                                        <?php wp_reset_postdata(); ?>
                                                                    </a>
                                                                <?php endif; ?>

                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <div class="buttonBlock <?php echo get_sub_field('link_orientation'); ?>">
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        </div>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
                                            <?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'two_column_text_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos twoColumnTextBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="titleBlock">
                                                        <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                                        <hr>

                                                    </div>
                                                    <div class="textBlock">
                                                        <?php echo get_sub_field( 'text_block' ); ?>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink text" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only textImageBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="title">
                                                        <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                        <hr>
                                                    </div>
                                                    <?php if ( have_rows( 'item' ) ) : ?>
                                                        <div class="itemsWrapper">
                                                            <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <?php if ( get_sub_field( 'image') ) { ?>
                                                                        <a href="<?php echo get_sub_field('link_url'); ?>" target="<?php echo get_sub_field('link_target'); ?>" class="imageContainer">
                                                                            <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                                            </div>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                                                    <span class="description">
                                                                        <?php echo get_sub_field( 'text' ); ?>
                                                                    </span>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'half_text_half_image' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only halfHalfBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="textBlock <?php echo get_sub_field( 'image_position' ); ?>">
                                                <div class="v-wrap">
                                                    <div class="v-box">
                                                        <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                                        <hr>
                                                        <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                            <span class="desktopText"><?php echo get_sub_field( 'text_block' ); ?></span>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink desktop <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="imageBlock <?php echo get_sub_field( 'image_position' ); ?>">
                                                <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                </div>
                                            </div>
                                            <div class="textBlock mobile">
                                                <div class="container">
                                                    <div class="inner">
                                                        <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                            <span class="mobileText"><?php echo get_sub_field( 'text_block' ); ?></span>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'speaker_quote_carousel' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no speakerQuoteCarousel">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="titleBlock">
                                                        <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink text" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>

                                                    <?php if ( have_rows( 'item' ) ) : ?>
                                                        <div class="owl-carousel speaker-gallery">
                                                            <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <div class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                                        </div>
                                                                    </div>
                                                                    <div class="textBlock">
                                                                        <div class="v-wrap">
                                                                            <div class="v-box">
                                                                                <span class="quoteBlock">
                                                                                    <?php echo get_sub_field( 'quote' ); ?>
                                                                                </span>
                                                                                <span class="quoteAuthor">
                                                                                    <?php echo get_sub_field( 'quote_author' ); ?>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'quote_block_with_no_image' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no quoteBlockNoImage">
                                            <div class="container">
                                                <div class="inner">
                                                    <?php if ( have_rows( 'quotes' ) ) : ?>
                                                        <div class="owl-carousel quote">
                                                            <?php while ( have_rows( 'quotes' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <div class="v-wrap">
                                                                        <div class="v-box">
                                                                            <span class="quoteBlock">
                                                                                <?php echo get_sub_field( 'quote' ); ?>
                                                                            </span>
                                                                            <span class="quoteAuthor">
                                                                                <?php echo get_sub_field( 'quote_author' ); ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>
                                        <section id="<?php echo get_sub_field( 'id' ); ?>" class="scrollPos imageGridBlock standard logos">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="titleBlock">
                                                        <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                            <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                            <span class="hrWrapper">
                                                                <hr>
                                                            </span>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'description' ) ) { ?>
                                                            <h3><?php echo get_sub_field( 'description' ); ?></h3>
                                                        <?php } ?>
                                                    </div>

                                                    <?php if ( have_rows( 'logos' ) ) : ?>
                                                        <div class="gridWrapper">
                                                            <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <div class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo get_sub_field( 'logo' ); ?>);">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                            <div class="item">
                                                                <div class="v-wrap">
                                                                    <div class="v-box">
                                                                        <span class="yourLogoHere">Your Company Here</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'counter_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_counter-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'repeatable_counter_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_repeatable-counter-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'membership_block' ) : ?>
                                        <?php if ( get_sub_field ( 'display_membership_block' ) == 'yes' ) { ?>
                                            <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no pricingBlock">
                                                <div class="container">
                                                    <h2>Membership</h2>
                                                    <?php if ( have_rows( 'first_pricing_block', 'option' ) ) : ?>
                                                        <div class="pricingBlockItem first">
                                                            <div class="innerWrapper">
                                                                <?php while ( have_rows( 'first_pricing_block', 'option' ) ) : the_row(); ?>
                                                                    <span class="title">
                                                                        <?php echo get_sub_field( 'title', 'option' ); ?>
                                                                        <span class="hrWrapper">
                                                                            <hr>
                                                                        </span>
                                                                    </span>
                                                                    <span class="priceBlockWrapper">
                                                                        <span class="priceBlock">
                                                                            <span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
                                                                        </span>
                                                                    </span>
                                                                    <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                        <div class="features">
                                                                            <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                <span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
                                                                            <?php endwhile; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <span class="pricingButtonWrapper">
                                                                    <a class="small" href="<?php echo get_sub_field( 'button_link', 'option' ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo get_sub_field( 'button_text', 'option' ); ?></a>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ( have_rows( 'featured_pricing_block', 'option' ) ) : ?>
                                                        <div class="pricingBlockItem featured">

                                                            <?php while ( have_rows( 'featured_pricing_block', 'option' ) ) : the_row(); ?>
                                                                <div class="innerWrapper">
                                                                    <div class="featuredWrapper">
                                                                        <span class="title">
                                                                            <?php echo get_sub_field( 'title', 'option' ); ?>
                                                                            <span class="hrWrapper">
                                                                                <hr>
                                                                            </span>
                                                                        </span>
                                                                        <span class="priceBlockWrapper">
                                                                            <span class="priceBlock">
                                                                                <span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
                                                                            </span>
                                                                        </span>
                                                                        <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                            <div class="features">
                                                                                <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                    <span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
                                                                                <?php endwhile; ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <span class="pricingButtonWrapper">
                                                                    <a class="small" href="<?php echo get_sub_field( 'button_link', 'option' ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo get_sub_field( 'button_text', 'option' ); ?></a>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ( have_rows( 'last_pricing_block', 'option' ) ) : ?>
                                                        <div class="pricingBlockItem last">
                                                            <?php while ( have_rows( 'last_pricing_block', 'option' ) ) : the_row(); ?>
                                                                <div class="innerWrapper">
                                                                    <span class="title">
                                                                        <?php echo get_sub_field( 'title', 'option' ); ?>
                                                                        <span class="hrWrapper">
                                                                            <hr>
                                                                        </span>
                                                                    </span>
                                                                    <span class="priceBlockWrapper">
                                                                        <span class="priceBlock">
                                                                            <span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
                                                                        </span>
                                                                    </span>
                                                                    <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                        <div class="features">
                                                                            <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                <span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
                                                                            <?php endwhile; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <span class="pricingButtonWrapper">
                                                                    <a class="small" href="<?php echo get_sub_field( 'button_link', 'option' ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo get_sub_field( 'button_text', 'option' ); ?></a>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </section>
                                        <?php } ?>
                                    <?php elseif ( get_row_layout() == 'two_column_block_with_text_and_featured_quote' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only twoColumnWithTextAndFeaturedQuote">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="column first">
                                                        <h2>
                                                            <?php echo get_sub_field( 'title' ); ?>
                                                        </h2>
                                                        <div class="textBlock">
                                                            <?php echo get_sub_field( 'text_block' ); ?>
                                                        </div>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink text" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="column last">
                                                        <div class="item">
                                                            <div class="v-wrap">
                                                                <div class="v-box">
                                                                    <span class="quoteBlock">
                                                                        <?php echo get_sub_field( 'quote' ); ?>
                                                                    </span>
                                                                    <span class="quoteAuthor">
                                                                        <?php echo get_sub_field( 'quote_author' ); ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'video_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no videoBlock postVideoBlock">
                                            <div class="container">
                                                <?php if( get_sub_field('vimeo_code_popup')){ ?>
                                                    <a href="https://vimeo.com/<?php echo get_sub_field('vimeo_code_popup'); ?>" class="image popup-vimeo">
                                                <?php } else { ?>
                                                    <a href="" class="image postPlayBtn">
                                                <?php } ?>
                                                    <div class="imageSizeContainer">
                                                        <span class="overlayGradient"></span>
                                                        <div class="bgContainer">
                                                            <img class="desktop" src="<?php echo get_sub_field('video_poster_image'); ?>" alt="" />
                                                        </div>
                                                        <span class="watchIcon"></span>
                                                        <span class="textContainer">
                                                            <span class="title"><?php the_title(); ?></span>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="videoPlayerContainer videoBlock">
                                                <span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                                                <div class="videoWrapper">
                                                    <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                                        <source type="video/mp4" src="<?php echo get_sub_field('vimeo_code'); ?>" />
                                                    </video>
                                                </div>
                                            </div>

                                        </section>
                                    <?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'full_width_text_editor' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullWidthTextEditor<?php if ( get_sub_field( 'font') ) { ?> <?php echo get_sub_field( 'font' );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php echo get_sub_field( 'font_colour' ); ?><?php } ?>">
                                            <div class="container">
                                                <?php echo get_sub_field( 'text_editor' ); ?>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'form_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="formWrapper register">
                                                        <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                            <h2><?php echo get_sub_field('block_title'); ?></h2>
                                                            <?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                                                <hr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                                            <h3><?php echo get_sub_field('block_description'); ?></h3>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                                            <?php echo get_sub_field('form_shortcode'); ?>
                                                        <?php }?>
                                                        <?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?>
                                                            <a class="button popup-modal" href="#<?php echo get_sub_field('form_id'); ?>"><?php echo get_sub_field('button_text'); ?></a>
                                                            <div class="formPopup mfp-hide" id="<?php echo get_sub_field('form_id'); ?>">
                                                                <a class="popup-modal-dismiss"></a>
                                                                <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                                    <h2><h2><?php echo get_sub_field('block_title'); ?></h2></h2>
                                                                <?php } ?>
                                                                <?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                                                    <h3><?php echo get_sub_field('block_description'); ?></h3>
                                                                <?php } ?>
                                                                    <div class="formWrapper register"><?php echo get_sub_field('form_shortcode'); ?></div>
                                                            </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php endif; ?>
                            <?php } else { ?>
                                <?php if ( have_rows( 'members_only_overlay_cta', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_overlay_cta', 'options' ) ) : the_row(); ?>
                                        <div class="blurred-image-cta-container">
                                            <span class="blur-image-container">
                                                <span class="bg-container"> 
                                                    <p>                                                
                                                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.
                                                    </p>
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>
                                                    <ul>
                                                        <li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>
                                                        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</li>
                                                    </ul>  
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>                                             
                                                </span>
                                            </span>
                                            <?php $background_image_overlay = get_sub_field( 'background_image_overlay' ); ?>
                                            <div class="global-preview-cta-container background-black" style="background-image: url(<?php echo $background_image_overlay['url']; ?>)">                                            
                                                <div class="preview-cta-inner">   
                                                    <div class="preview-cta-content">
                                                        <span class="title"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                            <?php $buttonCounter = 1; ?>
                                                            <span class="button-container">                                                                                                                   
                                                                <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                    <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                                                                        <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                                    <?php } else { ?> 
                                                                        <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#globalpreviewCTA<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                                        <div style="display: none;">         
                                                                            <div class="preview-cta-form login-form-container" id="globalpreviewCTA<?php echo $buttonCounter; ?>">
                                                                                <span class="form-container-inner"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                                                    <?php $buttonCounter++; ?>
                                                                <?php endwhile; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                    $postID = get_the_ID();
                                                    $postURL = get_permalink();
                                                ?>
                                                <span class="preview-cta-bottom-module"><?php echo get_sub_field( 'login_text' ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo $postID;?>&redirect_to=<?php echo $postURL;?>" target="_self">Login here</a></span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                        </div>
                        <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php if ( have_rows( 'contributors' ) ) : ?>
                                <div class="authors">
                                    <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                        <?php $post_object = get_sub_field( 'contributor_name' ); ?>
                                        <?php if ( $post_object ): ?>
                                            <?php $post = $post_object; ?>
                                            <?php setup_postdata( $post ); ?>
                                                <div class="speaker-container-inner">
                                                    <span class="speaker-image">
                                                        <img src="<?php echo get_field('speaker_image'); ?>" alt="<?php echo the_title(); ?>"/>
                                                    </span>
                                                    <span class="description">
                                                        <span class="title"><?php if(get_sub_field('contributors_pre_heading')){ ?><?php echo get_sub_field('contributors_pre_heading'); ?><?php } else { ?>Contributor<?php } ?></span>
                                                        <span class="speaker-name"><?php echo the_title(); ?></span>
                                                        <span class="speaker-role"><?php echo get_field('speaker_description'); ?></span>
                                                    </span>
                                                    <div class="textBlock">
                                                        <?php
                                                            $text = get_field('speaker_details');
                                                            $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                        ?>
                                                        <span class="speaker-details-excerpt"><?php echo $trimmed_content; ?></span>
                                                        <span class="speaker-details">
                                                            <?php echo get_field('speaker_details'); ?>
                                                            <span class="speaker-details-less">Less</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php wp_reset_postdata(); ?>
                                        <?php endif; ?>
                                <?php endwhile; ?>
                                </div>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        <?php } ?>
                    </div>
                    <div class="column third">
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
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                        <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php if ( has_term( ['sector-outlooks', 'persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                                <?php if( $advantagePlus == 'yes') { ?> 
                                    <?php if (get_field( 'download' ) == 'yes'){ ?>
                                        <?php if ( have_rows( 'membership_ids_for_download', 'options' ) ) : ?>
                                            <?php $counter = 0; ?>
                                                <?php while ( have_rows( 'membership_ids_for_download', 'options' ) ) : the_row(); ?>
                                                    <?php if ( $counter == 0 ) {
                                                    $members = $members . get_sub_field( 'membership_id' );
                                                    } else {
                                                    $members = $members . ',' . get_sub_field( 'membership_id' );
                                                    } ?>
                                                    <?php $counter++; ?>
                                                <?php endwhile; ?>
                                                <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                                    <?php if ( have_rows( 'download_link' ) ) : ?>
                                                        <div class="articleShare downloadShareContainer">
                                                            <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                                                <?php if (get_sub_field( 'text' )) { ?>
                                                                    <span class="shareText download desktop"><?php echo get_sub_field( 'text' ); ?></span>
                                                                <?php } ?>
                                                                <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                                <?php if ( $preview_image ) { ?>
                                                                    <span class="download-image-container <?php echo get_sub_field( 'image_orientation' ); ?>">
                                                                        <span class="bg-container">
                                                                            <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, array( 'alt' => $preview_image['alt'] ) ); ?>
                                                                        </span>
                                                                    </span>
                                                                <?php } ?>
                                                                <?php if (get_sub_field( 'text' )) { ?>
                                                                    <span class="shareText download mobile"><?php echo get_sub_field( 'text' ); ?></span>
                                                                <?php } ?>
                                                                <a id="downloadButton" href="<?php echo get_sub_field( 'download_url' ); ?>" target="_blank" class="button redOutline"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php } ?>

                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if (get_field( 'download' ) == 'yes'){ ?>
                                    <?php if ( have_rows( 'membership_ids_for_download', 'options' ) ) : ?>
                                        <?php $counter = 0; ?>
                                            <?php while ( have_rows( 'membership_ids_for_download', 'options' ) ) : the_row(); ?>
                                                <?php if ( $counter == 0 ) {
                                                $members = $members . get_sub_field( 'membership_id' );
                                                } else {
                                                $members = $members . ',' . get_sub_field( 'membership_id' );
                                                } ?>
                                                <?php $counter++; ?>
                                            <?php endwhile; ?>
                                            <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                                <?php if ( have_rows( 'download_link' ) ) : ?>
                                                    <div class="articleShare downloadShareContainer">
                                                        <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText download desktop"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php } ?>
                                                            <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                            <?php if ( $preview_image ) { ?>
                                                                <span class="download-image-container <?php echo get_sub_field( 'image_orientation' ); ?>">
                                                                    <span class="bg-container">
                                                                        <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, array( 'alt' => $preview_image['alt'] ) ); ?>
                                                                    </span>
                                                                </span>
                                                            <?php } ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText download mobile"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php } ?>
                                                            <a id="downloadButton" href="<?php echo get_sub_field( 'download_url' ); ?>" target="_blank" class="button redOutline"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            <?php } ?>

                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                <?php } ?>
                            <?php } ?>                            
                            <?php if ( have_rows( 'dataset_share' ) ) : ?>
                                <?php while ( have_rows( 'dataset_share' ) ) : the_row(); ?>
                                    <?php $current_user = wp_get_current_user();
                                    $first_name = $current_user->first_name;
                                    $last_name = $current_user->last_name;
                                    $shareTitle = get_the_title();
                                    $sharedDescription = get_the_excerpt();
                                    $formIntro = get_sub_field('form_introduction_title');
                                    ?>
                                    <div class="articleShare shareLinkContainer datasetShare">
                                        <?php if (get_sub_field( 'text' )) { ?>
                                            <span class="shareText share"><?php echo get_sub_field( 'text' ); ?></span>
                                        <?php } ?>
                                        <a href="#datasetsharepopupcontainer" class="button redOutline datasharepopup"><?php echo get_sub_field( 'button_text' ); ?></a>
                                        <span class="hidden-share-link" style="display: none;"><?php echo get_sub_field( 'share_download_url' ); ?></span>
                                        <span class="hidden-share-name" style="display: none;"><?php echo $first_name; ?> <?php echo $last_name; ?></span>
                                        <span class="hidden-share-title" style="display: none;"><?php echo $shareTitle; ?></span>
                                        <span class="hidden-share-excerpt" style="display: none;"><?php echo $sharedDescription; ?></span>
                                        <div style="display: none;">
                                            <div class="webinar-register-form datasetShare" id="datasetsharepopupcontainer">
                                                <div class="container">
                                                    <span class="data-column-container">
                                                        <span class="image-column">
                                                            <span class="content-popup-container">
                                                                <h2><?php echo get_sub_field( 'text' ); ?></h2>
                                                                <span class="slide-container">
                                                                    <span class="image-container">
                                                                        <span class="bg-container offset-image-container">
                                                                            <?php if ( $offsetimage ) { ?>
                                                                                <?php echo wp_get_attachment_image( $offsetimage['ID'], 'full', false, array( 'alt' => $offsetimage['alt'] ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                        <span class="bg-container">
                                                                            <?php if ( $image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                                <span class="page-title"><?php the_title(); ?></span>
                                                            </span>
                                                        </span>
                                                        <span class="form-column">
                                                            <span class="form-container">
                                                                <h2 class="form-title"><?php echo $formIntro; ?></h2>
                                                                <?php echo get_field( 'post_share_form', 'option' ); ?>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php if (get_field( 'download' ) == 'yes'){ ?>
                                    <?php $current_user = wp_get_current_user();
                                    $first_name = $current_user->first_name;
                                    $last_name = $current_user->last_name;
                                    $shareTitle = get_the_title();
                                    $sharedDescription = get_the_excerpt();
                                    ?>
                                    <?php if ( have_rows( 'membership_ids_for_share', 'options' ) ) : ?>
                                        <?php $counter = 0; ?>
                                            <?php while ( have_rows( 'membership_ids_for_share', 'options' ) ) : the_row(); ?>
                                                <?php if ( $counter == 0 ) {
                                                $members = $members . get_sub_field( 'membership_id' );
                                                } else {
                                                $members = $members . ',' . get_sub_field( 'membership_id' );
                                                } ?>
                                                <?php $counter++; ?>
                                            <?php endwhile; ?>
                                            <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                                <?php if ( have_rows( 'share' ) ) : ?>
                                                    <div class="articleShare shareLinkContainer">
                                                        <?php while ( have_rows( 'share' ) ) : the_row(); ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText share"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php } ?>
                                                            <a href="#sharepopupcontainer" class="button redOutline sharepopup"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                            <span class="hidden-share-link" style="display: none;"><?php echo get_sub_field( 'share_download_url' ); ?></span>
                                                            <span class="hidden-share-name" style="display: none;"><?php echo $first_name; ?> <?php echo $last_name; ?></span>
                                                            <span class="hidden-share-title" style="display: none;"><?php echo $shareTitle; ?></span>
                                                            <span class="hidden-share-excerpt" style="display: none;"><?php echo $sharedDescription; ?></span>
                                                            <div style="display: none;">
                                                                <div class="webinar-register-form" id="sharepopupcontainer">
                                                                    <div class="container">
                                                                        <span class="webinar-subtitle"><?php echo get_field( 'post_share_form_title', 'option' ); ?></span>
                                                                        <span class="form-container"><?php echo get_field( 'post_share_form', 'option' ); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            <?php } ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                <?php } ?> 
                            <?php endif; ?>
                        <?php }?>
                        <div class="relatedArticles<?php if(current_user_can('memberpress_authorized')) { ?><?php } else { ?> mobile-hide<?php } ?>">
                            <h2 class="related">You may also like</h2>
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
                            <?php $type_terms = get_field( 'you_may_also_like' ); ?>
                            <?php $types = array(); ?>
                            <?php if ( $type_terms ){ ?>
                                <?php foreach ( $type_terms as $type_term ): ?>
                                    <?php $types[] =  $type_term->slug; ?>
                                <?php endforeach; ?>
                            <?php
                                $args = array(
                                    'post_type'      => 'post',
                                    'posts_per_page' => 3,
                                    'post__not_in' => array( $post->ID ),
                                    'tax_query'      => array(
                                        'relation' => 'AND',
                                        array (
                                            'taxonomy' => 'topic',
                                            'field' => 'slug',
                                            'terms'    => $postTopic->slug
                                        ),
                                        array(
                                            'taxonomy' => 'filter-types',
                                            'field'    => 'slug',
                                            'terms' => $types,
                                            'operator' => 'IN',
                                        )
                                    )
                                );?>
                            <?php } else {
                                $args = array(
                                    'post_type'      => 'post',
                                    'posts_per_page' => 3,
                                    'post__not_in' => array( $post->ID ),
                                    'tax_query'      => array(
                                        'relation' => 'AND',
                                        array (
                                            'taxonomy' => 'topic',
                                            'field' => 'slug',
                                            'terms'    => $postTopic->slug
                                        )
                                    )
                                );?>
                            <?php }

                            $posts = new WP_Query( $args );
                            if( $posts->have_posts() ): ?>
                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                    <div class="item">
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
                                                }
                                                if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                                    $postType->name = 'Voice of Customers';
                                                }
                                                ?>
                                                <?php if($postTopic){?>
                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                                <?php } ?>
                                                <?php if($postType){?>
                                                    <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                                <?php } ?>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="title"><?php the_title();?></a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif;?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </article>            
        <?php } else { ?>
            <article class="articleWrapper bg-white">
                <div class="container">
                    <div class="column first">
                        <span class="saveInsight">
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                                <?php echo do_shortcode('[favorite_button]'); ?>
                            <?php } ?>
                        </span>
                    </div>
                    <div class="column second">
                        <div class="article">
                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
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
                                    }
                                    if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                        $postType->name = 'Voice of Customers';
                                    }
                                    ?>
                                    <?php if($postTopic){?>
                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                    <?php } ?>
                                    <?php if($postType){?>
                                        <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                    <?php } ?>
                                </span>
                                <h1 class="title"><?php the_title(); ?></h1>
                                <?php if ($postType->slug == 'workshop-recordings' || $postType->slug == 'case-studies' || $postType->slug == 'best-practices' || $postType->slug == 'market-narratives'){ ?>
                                    <span class="dateReadTime"><?php if (get_field( 'read_time' )) { ?><?php echo get_field('read_time'); ?><?php } ?></span>

                                <?php } else { ?>
                                    <span class="dateReadTime"><span class="dateRead"><?php echo get_the_date('M j, Y'); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                <?php } ?>
                            <?php } ?>
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                                <span class="saveInsight mobile">
                                    <?php echo do_shortcode('[favorite_button]'); ?>
                                </span>
                            <?php } ?>
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php } else { ?> 
                                <?php $previewContent = false; ?>
                                <?php if ( have_rows( 'members_only_preview_content' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_preview_content' ) ) : the_row(); ?>
                                        <?php if( get_sub_field( 'preview_text' )){ ?>
                                            <?php $previewContent = true; ?>
                                            <?php $previewText = get_sub_field( 'preview_text' ); ?>
                                        <?php } ?>                                                                             
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                            <?php if (get_field('article_content')){ ?>
                                <div class="article-content">
                                    <?php if(current_user_can('memberpress_authorized')) { ?>
                                    <?php echo get_field('article_content'); ?>
                                    <?php } else { ?>
                                        <?php if ($previewContent == false){ ?>
                                            <div class="content-trimmed">
                                                <?php
                                                    echo force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( wpautop( get_field( 'article_content' )) ), 150, $more ) ) );
                                                ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <!--  -->
                                </div>
                            <?php } else { ?>
                                <?php if(current_user_can('memberpress_authorized')) { ?>
                                <?php } else { ?>
                                    <?php if ($previewContent == false){ ?>
                                        <div class="content-trimmed">
                                            <?php
                                            $text = get_the_excerpt();
                                            if($text){?>
                                                <p><?php echo $text; ?></p>
                                                <?php
                                            } else {
                                            } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php } else { ?> 
                                <?php if ( have_rows( 'members_only_preview_content' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_preview_content' ) ) : the_row(); ?>
                                        <div class="content-trimmed">
                                            <?php echo $previewText; ?>
                                        </div>                                                                      
                                        <?php $image = get_sub_field( 'image' ); ?>                                
                                        <?php if ( $image ) { ?>
                                            <div class="preview-image-container">
                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                            </div>
                                        <?php } ?>
                                        <?php if ( have_rows( 'cta' ) ) : ?>
                                            <?php while ( have_rows( 'cta' ) ) : the_row(); ?>
                                                <div class="preview-cta-container background-pink">
                                                    <div class="preview-cta-inner">
                                                        <div class="preview-cta-image-column desktop">
                                                            <span class="image-container">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>                                                    
                                                        </div>
                                                        <div class="preview-cta-content">
                                                            <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                                            <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <div class="preview-cta-image mobile">
                                                                <span class="image-container">
                                                                    <span class="bg-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                </span>                                                    
                                                            </div>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                                <?php $buttonCounter = 1; ?>
                                                                <span class="button-container">                                                                                                                   
                                                                    <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                        <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                                                                            <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                                        <?php } else { ?> 
                                                                            <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#previewCTA<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                                            <div style="display: none;">         
                                                                                <div class="preview-cta-form login-form-container" id="previewCTA<?php echo $buttonCounter; ?>">                                                                            
                                                                                    <span class="form-container-inner"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                                                        <?php $buttonCounter++; ?>
                                                                    <?php endwhile; ?>
                                                                </span>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        $postID = get_the_ID();
                                                        $postURL = get_permalink();
                                                    ?>
                                                    <span class="preview-cta-bottom-module"><?php echo get_sub_field( 'login_text' ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo $postID;?>&redirect_to=<?php echo $postURL;?>" target="_self">Login here</a></span>
                                                </div>                                        
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                            
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                                <?php if ( have_rows( 'content_blocks' ) ): ?>
                                <?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
                                    <?php if ( get_row_layout() == 'article_content' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos singlePost repeatableSingle">
                                        <div class="container">
                                            <div class="post-inner">
                                                <div class="fullWidth article-content">
                                                    <div class="articleWrapper">
                                                        <?php echo get_sub_field( 'article_content' ); ?>
                                                        <?php if( get_sub_field( 'infogram_image' )) { ?>
                                                            <img class="delete-no" style="display: none;" src="<?php echo get_sub_field( 'infogram_image' ); ?>"/>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <?php elseif ( get_row_layout() == 'feature_image_or_infogram' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullImageInfogram members-logged-in">
                                        <div class="container">
                                                <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
                                                    <div class="featureBlock">
                                                        <img class="featureImage" src="<?php echo get_sub_field( 'image' ); ?>"/>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="infogram-container">
                                                        <?php echo get_sub_field( 'infogram' ); ?>
                                                    </div>
                                                    <img class="delete-no" style="display: none;" src="<?php echo get_sub_field( 'infogram_image' ); ?>"/>
                                                <?php } ?>
                                        </div>
                                    </section>
                                    <?php elseif ( get_row_layout() == 'image_grid_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only imageGridBlock standard <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <h2><?php echo get_sub_field( 'block_title' ); ?></h2>

                                                    <?php if ( have_rows( 'item' ) ) : ?>
                                                        <div class="gridWrapper">
                                                            <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <?php if ( get_sub_field( 'image') ) { ?>
                                                                        <div class="imageContainer">
                                                                            <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <hr>
                                                                    <span class="title">
                                                                        <?php echo get_sub_field( 'title' ); ?>
                                                                    </span>
                                                                    <span class="description">
                                                                        <?php echo get_sub_field( 'description' ); ?>
                                                                    </span>
                                                                    <?php if ( get_sub_field( 'logo') ) { ?>
                                                                        <div class="logoContainer">
                                                                            <img src="<?php echo get_sub_field( 'logo' ); ?>" alt="Adapt" />
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'logo_grid' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos logoGrid <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="titleBlock">
                                                    <span class="title">
                                                        <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                    </span>

                                                    <span class="description <?php echo get_sub_field( 'top_right_text_position' ); ?>">
                                                        <h3><?php echo get_sub_field( 'top_right_text' ); ?></h3>
                                                    </span>
                                                </div>

                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                    <div class="logoBlock">
                                                        <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                            <div class="logo">
                                                                <span class="logoContainer">
                                                                    <div class="image" style="background-image: url(<?php echo get_sub_field( 'logo' ); ?>);">
                                                                    </div>
                                                                </span>
                                                                <span class="logoTitle">
                                                                    <?php echo get_sub_field( 'title' ); ?>
                                                                </span>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                    <a class="logoBlockLink <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                <?php } ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'download_block_single' ) : ?>
                                        <?php get_template_part( 'templates/components/_download-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'download_block_double' ) : ?>
                                        <?php get_template_part( 'templates/components/_download-block-two-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'download_block_triple' ) : ?>
                                        <?php get_template_part( 'templates/components/_download-block-three-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'video_grid_block_two_column' ) : ?>
                                        <?php get_template_part( 'templates/components/_video-block-two-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'video_grid_block_three_column' ) : ?>
                                        <?php get_template_part( 'templates/components/_video-block-three-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'two_column_card_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_two-column-card' ); ?>
                                    <?php elseif ( get_row_layout() == 'speaker_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only imageGridBlock speakerBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <h2><?php echo get_sub_field( 'block_title' ); ?></h2>

                                                    <?php if ( have_rows( 'speakers' ) ) : ?>
                                                        <div class="gridWrapper">
                                                            <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>

                                                                <?php $post_object = get_sub_field( 'speaker' ); ?>
                                                                <?php if ( $post_object ): ?>
                                                                    <a href="<?php the_permalink(); ?>" class="item">
                                                                        <?php $post = $post_object; ?>
                                                                        <?php setup_postdata( $post ); ?>
                                                                            <?php if ( get_field( 'speaker_image') ) { ?>
                                                                                <div class="imageContainer">
                                                                                    <div class="image" style="background-image: url(<?php echo get_field( 'speaker_image' ); ?>);">
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <hr>
                                                                            <span class="title"><?php the_title(); ?></span>
                                                                            <span class="description">
                                                                                <?php echo get_field( 'speaker_description' ); ?>
                                                                            </span>
                                                                            <?php if ( get_field( 'logo') ) { ?>
                                                                                <div class="logoContainer">
                                                                                    <img src="<?php echo get_field( 'logo' ); ?>" alt="Adapt" />
                                                                                </div>
                                                                            <?php } ?>
                                                                        <?php wp_reset_postdata(); ?>
                                                                    </a>
                                                                <?php endif; ?>

                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <div class="buttonBlock <?php echo get_sub_field('link_orientation'); ?>">
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        </div>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
                                            <?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'two_column_text_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="print-only scrollPos twoColumnTextBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="titleBlock">
                                                        <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                                        <hr>

                                                    </div>
                                                    <div class="textBlock">
                                                        <?php echo get_sub_field( 'text_block' ); ?>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink text" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only textImageBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="title">
                                                        <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                        <hr>
                                                    </div>
                                                    <?php if ( have_rows( 'item' ) ) : ?>
                                                        <div class="itemsWrapper">
                                                            <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <?php if ( get_sub_field( 'image') ) { ?>
                                                                        <a href="<?php echo get_sub_field('link_url'); ?>" target="<?php echo get_sub_field('link_target'); ?>" class="imageContainer">
                                                                            <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                                            </div>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                                                    <span class="description">
                                                                        <?php echo get_sub_field( 'text' ); ?>
                                                                    </span>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'half_text_half_image' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only halfHalfBlock <?php echo get_sub_field( 'background_colour' ); ?>">
                                            <div class="textBlock <?php echo get_sub_field( 'image_position' ); ?>">
                                                <div class="v-wrap">
                                                    <div class="v-box">
                                                        <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                                        <hr>
                                                        <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                            <span class="desktopText"><?php echo get_sub_field( 'text_block' ); ?></span>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink desktop <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="imageBlock <?php echo get_sub_field( 'image_position' ); ?>">
                                                <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                </div>
                                            </div>
                                            <div class="textBlock mobile">
                                                <div class="container">
                                                    <div class="inner">
                                                        <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                            <span class="mobileText"><?php echo get_sub_field( 'text_block' ); ?></span>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'speaker_quote_carousel' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no speakerQuoteCarousel">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="titleBlock">
                                                        <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink text" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>

                                                    <?php if ( have_rows( 'item' ) ) : ?>
                                                        <div class="owl-carousel speaker-gallery">
                                                            <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <div class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo get_sub_field( 'image' ); ?>);">
                                                                        </div>
                                                                    </div>
                                                                    <div class="textBlock">
                                                                        <div class="v-wrap">
                                                                            <div class="v-box">
                                                                                <span class="quoteBlock">
                                                                                    <?php echo get_sub_field( 'quote' ); ?>
                                                                                </span>
                                                                                <span class="quoteAuthor">
                                                                                    <?php echo get_sub_field( 'quote_author' ); ?>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'quote_block_with_no_image' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no quoteBlockNoImage">
                                            <div class="container">
                                                <div class="inner">
                                                    <?php if ( have_rows( 'quotes' ) ) : ?>
                                                        <div class="owl-carousel quote">
                                                            <?php while ( have_rows( 'quotes' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <div class="v-wrap">
                                                                        <div class="v-box">
                                                                            <span class="quoteBlock">
                                                                                <?php echo get_sub_field( 'quote' ); ?>
                                                                            </span>
                                                                            <span class="quoteAuthor">
                                                                                <?php echo get_sub_field( 'quote_author' ); ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>
                                        <section id="<?php echo get_sub_field( 'id' ); ?>" class="scrollPos imageGridBlock standard logos">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="titleBlock">
                                                        <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                            <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
                                                            <span class="hrWrapper">
                                                                <hr>
                                                            </span>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'description' ) ) { ?>
                                                            <h3><?php echo get_sub_field( 'description' ); ?></h3>
                                                        <?php } ?>
                                                    </div>

                                                    <?php if ( have_rows( 'logos' ) ) : ?>
                                                        <div class="gridWrapper">
                                                            <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <div class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo get_sub_field( 'logo' ); ?>);">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                            <div class="item">
                                                                <div class="v-wrap">
                                                                    <div class="v-box">
                                                                        <span class="yourLogoHere">Your Company Here</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'counter_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_counter-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'repeatable_counter_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_repeatable-counter-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'membership_block' ) : ?>
                                        <?php if ( get_sub_field ( 'display_membership_block' ) == 'yes' ) { ?>
                                            <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no pricingBlock">
                                                <div class="container">
                                                    <h2>Membership</h2>
                                                    <?php if ( have_rows( 'first_pricing_block', 'option' ) ) : ?>
                                                        <div class="pricingBlockItem first">
                                                            <div class="innerWrapper">
                                                                <?php while ( have_rows( 'first_pricing_block', 'option' ) ) : the_row(); ?>
                                                                    <span class="title">
                                                                        <?php echo get_sub_field( 'title', 'option' ); ?>
                                                                        <span class="hrWrapper">
                                                                            <hr>
                                                                        </span>
                                                                    </span>
                                                                    <span class="priceBlockWrapper">
                                                                        <span class="priceBlock">
                                                                            <span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
                                                                        </span>
                                                                    </span>
                                                                    <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                        <div class="features">
                                                                            <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                <span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
                                                                            <?php endwhile; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <span class="pricingButtonWrapper">
                                                                    <a class="small" href="<?php echo get_sub_field( 'button_link', 'option' ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo get_sub_field( 'button_text', 'option' ); ?></a>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ( have_rows( 'featured_pricing_block', 'option' ) ) : ?>
                                                        <div class="pricingBlockItem featured">

                                                            <?php while ( have_rows( 'featured_pricing_block', 'option' ) ) : the_row(); ?>
                                                                <div class="innerWrapper">
                                                                    <div class="featuredWrapper">
                                                                        <span class="title">
                                                                            <?php echo get_sub_field( 'title', 'option' ); ?>
                                                                            <span class="hrWrapper">
                                                                                <hr>
                                                                            </span>
                                                                        </span>
                                                                        <span class="priceBlockWrapper">
                                                                            <span class="priceBlock">
                                                                                <span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
                                                                            </span>
                                                                        </span>
                                                                        <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                            <div class="features">
                                                                                <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                    <span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
                                                                                <?php endwhile; ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <span class="pricingButtonWrapper">
                                                                    <a class="small" href="<?php echo get_sub_field( 'button_link', 'option' ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo get_sub_field( 'button_text', 'option' ); ?></a>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ( have_rows( 'last_pricing_block', 'option' ) ) : ?>
                                                        <div class="pricingBlockItem last">
                                                            <?php while ( have_rows( 'last_pricing_block', 'option' ) ) : the_row(); ?>
                                                                <div class="innerWrapper">
                                                                    <span class="title">
                                                                        <?php echo get_sub_field( 'title', 'option' ); ?>
                                                                        <span class="hrWrapper">
                                                                            <hr>
                                                                        </span>
                                                                    </span>
                                                                    <span class="priceBlockWrapper">
                                                                        <span class="priceBlock">
                                                                            <span class="dollar">$</span><?php echo get_sub_field( 'price_block', 'option' ); ?><span class="month">/month</span>
                                                                        </span>
                                                                    </span>
                                                                    <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                        <div class="features">
                                                                            <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                <span class="feature"><?php echo get_sub_field( 'feature', 'option' ); ?></span>
                                                                            <?php endwhile; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <span class="pricingButtonWrapper">
                                                                    <a class="small" href="<?php echo get_sub_field( 'button_link', 'option' ); ?>" target="<?php echo get_sub_field( 'button_target', 'option' ); ?>"><?php echo get_sub_field( 'button_text', 'option' ); ?></a>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </section>
                                        <?php } ?>
                                    <?php elseif ( get_row_layout() == 'two_column_block_with_text_and_featured_quote' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only twoColumnWithTextAndFeaturedQuote">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="column first">
                                                        <h2>
                                                            <?php echo get_sub_field( 'title' ); ?>
                                                        </h2>
                                                        <div class="textBlock">
                                                            <?php echo get_sub_field( 'text_block' ); ?>
                                                        </div>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink text" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="column last">
                                                        <div class="item">
                                                            <div class="v-wrap">
                                                                <div class="v-box">
                                                                    <span class="quoteBlock">
                                                                        <?php echo get_sub_field( 'quote' ); ?>
                                                                    </span>
                                                                    <span class="quoteAuthor">
                                                                        <?php echo get_sub_field( 'quote_author' ); ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'video_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no videoBlock postVideoBlock">
                                            <div class="container">
                                                <?php if( get_sub_field('vimeo_code_popup')){ ?>
                                                    <a href="https://vimeo.com/<?php echo get_sub_field('vimeo_code_popup'); ?>" class="image popup-vimeo">
                                                <?php } else { ?>
                                                    <a href="" class="image postPlayBtn">
                                                <?php } ?>
                                                    <div class="imageSizeContainer">
                                                        <span class="overlayGradient"></span>
                                                        <div class="bgContainer">
                                                            <img class="desktop" src="<?php echo get_sub_field('video_poster_image'); ?>" alt="" />
                                                        </div>
                                                        <span class="watchIcon"></span>
                                                        <span class="textContainer">
                                                            <span class="title"><?php the_title(); ?></span>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="videoPlayerContainer videoBlock">
                                                <span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                                                <div class="videoWrapper">
                                                    <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                                        <source type="video/mp4" src="<?php echo get_sub_field('vimeo_code'); ?>" />
                                                    </video>
                                                </div>
                                            </div>

                                        </section>
                                    <?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'full_width_text_editor' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-only fullWidthTextEditor<?php if ( get_sub_field( 'font') ) { ?> <?php echo get_sub_field( 'font' );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php echo get_sub_field( 'font_colour' ); ?><?php } ?>">
                                            <div class="container">
                                                <?php echo get_sub_field( 'text_editor' ); ?>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo get_sub_field('link_url'); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'form_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?> class="scrollPos print-no formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="formWrapper register">
                                                        <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                            <h2><?php echo get_sub_field('block_title'); ?></h2>
                                                            <?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                                                <hr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                                            <h3><?php echo get_sub_field('block_description'); ?></h3>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                                            <?php echo get_sub_field('form_shortcode'); ?>
                                                        <?php }?>
                                                        <?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?>
                                                            <a class="button popup-modal" href="#<?php echo get_sub_field('form_id'); ?>"><?php echo get_sub_field('button_text'); ?></a>
                                                            <div class="formPopup mfp-hide" id="<?php echo get_sub_field('form_id'); ?>">
                                                                <a class="popup-modal-dismiss"></a>
                                                                <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                                    <h2><h2><?php echo get_sub_field('block_title'); ?></h2></h2>
                                                                <?php } ?>
                                                                <?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                                                    <h3><?php echo get_sub_field('block_description'); ?></h3>
                                                                <?php } ?>
                                                                    <div class="formWrapper register"><?php echo get_sub_field('form_shortcode'); ?></div>
                                                            </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php endif; ?>
                            <?php } else { ?>
                                <?php $members_only_blurred_text_image = get_field( 'members_only_blurred_text_image', 'options' ); ?>
                                <?php if ( have_rows( 'members_only_overlay_cta', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_overlay_cta', 'options' ) ) : the_row(); ?>
                                        <div class="blurred-image-cta-container">
                                            <span class="blur-image-container">
                                                <span class="bg-container"> 
                                                    <p>                                                
                                                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.
                                                    </p>
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>
                                                    <ul>
                                                        <li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>
                                                        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</li>
                                                    </ul>  
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>                                             
                                                </span>
                                            </span>
                                            <?php $background_image_overlay = get_sub_field( 'background_image_overlay' ); ?>
                                            <div class="global-preview-cta-container background-black" style="background-image: url(<?php echo $background_image_overlay['url']; ?>)">                                            
                                                <div class="preview-cta-inner">   
                                                    <div class="preview-cta-content">
                                                        <span class="title"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                            <?php $buttonCounter = 1; ?>
                                                            <span class="button-container">                                                                                                                   
                                                                <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                    <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                                                                        <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'button_link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                                    <?php } else { ?> 
                                                                        <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#globalpreviewCTA<?php echo $buttonCounter; ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                                        <div style="display: none;">         
                                                                            <div class="preview-cta-form login-form-container" id="globalpreviewCTA<?php echo $buttonCounter; ?>">
                                                                                <span class="form-container-inner"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                            </div>                                                                        
                                                                        </div>
                                                                    <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                                                    <?php $buttonCounter++; ?>
                                                                <?php endwhile; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                    $postID = get_the_ID();
                                                    $postURL = get_permalink();
                                                ?>
                                                <span class="preview-cta-bottom-module"><?php echo get_sub_field( 'login_text' ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo $postID;?>&redirect_to=<?php echo $postURL;?>" target="_self">Login here</a></span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                        </div>
                        <?php if(current_user_can('memberpress_authorized')) { ?>
                            <?php if ( have_rows( 'contributors' ) ) : ?>
                                <div class="authors">
                                    <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                        <?php $post_object = get_sub_field( 'contributor_name' ); ?>
                                        <?php if ( $post_object ): ?>
                                            <?php $post = $post_object; ?>
                                            <?php setup_postdata( $post ); ?>
                                                <div class="speaker-container-inner">
                                                    <span class="speaker-image">
                                                        <img src="<?php echo get_field('speaker_image'); ?>" alt="<?php echo the_title(); ?>"/>
                                                    </span>
                                                    <span class="description">
                                                        <span class="title"><?php if(get_sub_field('contributors_pre_heading')){ ?><?php echo get_sub_field('contributors_pre_heading'); ?><?php } else { ?>Contributor<?php } ?></span>
                                                        <span class="speaker-name"><?php echo the_title(); ?></span>
                                                        <span class="speaker-role"><?php echo get_field('speaker_description'); ?></span>
                                                    </span>
                                                    <div class="textBlock">
                                                        <?php
                                                            $text = get_field('speaker_details');
                                                            $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                        ?>
                                                        <span class="speaker-details-excerpt"><?php echo $trimmed_content; ?></span>
                                                        <span class="speaker-details">
                                                            <?php echo get_field('speaker_details'); ?>
                                                            <span class="speaker-details-less">Less</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php wp_reset_postdata(); ?>
                                        <?php endif; ?>
                                <?php endwhile; ?>
                                </div>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        <?php } ?>
                    </div>
                    <div class="column third">
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
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                        <?php if(current_user_can('memberpress_authorized')) { ?>
                           <?php if ( has_term( ['sector-outlooks', 'persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                                <?php if( $advantagePlus == 'yes') { ?> 
                                    <?php if (get_field( 'download' ) == 'yes'){ ?>
                                        <?php if ( have_rows( 'membership_ids_for_download', 'options' ) ) : ?>
                                            <?php $counter = 0; ?>
                                                <?php while ( have_rows( 'membership_ids_for_download', 'options' ) ) : the_row(); ?>
                                                    <?php if ( $counter == 0 ) {
                                                    $members = $members . get_sub_field( 'membership_id' );
                                                    } else {
                                                    $members = $members . ',' . get_sub_field( 'membership_id' );
                                                    } ?>
                                                    <?php $counter++; ?>
                                                <?php endwhile; ?>
                                                <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                                    <?php if ( have_rows( 'download_link' ) ) : ?>
                                                        <div class="articleShare downloadShareContainer">
                                                            <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                                                <?php if (get_sub_field( 'text' )) { ?>
                                                                    <span class="shareText download desktop"><?php echo get_sub_field( 'text' ); ?></span>
                                                                <?php } ?>
                                                                <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                                <?php if ( $preview_image ) { ?>
                                                                    <span class="download-image-container <?php echo get_sub_field( 'image_orientation' ); ?>">
                                                                        <span class="bg-container">
                                                                            <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, array( 'alt' => $preview_image['alt'] ) ); ?>
                                                                        </span>
                                                                    </span>
                                                                <?php } ?>
                                                                <?php if (get_sub_field( 'text' )) { ?>
                                                                    <span class="shareText download mobile"><?php echo get_sub_field( 'text' ); ?></span>
                                                                <?php } ?>
                                                                <a id="downloadButton" href="<?php echo get_sub_field( 'download_url' ); ?>" target="_blank" class="button redOutline"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php } ?>

                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if (get_field( 'download' ) == 'yes'){ ?>
                                    <?php if ( have_rows( 'membership_ids_for_download', 'options' ) ) : ?>
                                        <?php $counter = 0; ?>
                                            <?php while ( have_rows( 'membership_ids_for_download', 'options' ) ) : the_row(); ?>
                                                <?php if ( $counter == 0 ) {
                                                $members = $members . get_sub_field( 'membership_id' );
                                                } else {
                                                $members = $members . ',' . get_sub_field( 'membership_id' );
                                                } ?>
                                                <?php $counter++; ?>
                                            <?php endwhile; ?>
                                            <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                                <?php if ( have_rows( 'download_link' ) ) : ?>
                                                    <div class="articleShare downloadShareContainer">
                                                        <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText download desktop"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php } ?>
                                                            <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                            <?php if ( $preview_image ) { ?>
                                                                <span class="download-image-container <?php echo get_sub_field( 'image_orientation' ); ?>">
                                                                    <span class="bg-container">
                                                                        <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, array( 'alt' => $preview_image['alt'] ) ); ?>
                                                                    </span>
                                                                </span>
                                                            <?php } ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText download mobile"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php } ?>
                                                            <a id="downloadButton" href="<?php echo get_sub_field( 'download_url' ); ?>" target="_blank" class="button redOutline"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            <?php } ?>

                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                <?php } ?>
                            <?php } ?> 
                           <?php if ( have_rows( 'dataset_share' ) ) : ?>
                                <?php while ( have_rows( 'dataset_share' ) ) : the_row(); ?>
                                    <?php $current_user = wp_get_current_user();
                                    $first_name = $current_user->first_name;
                                    $last_name = $current_user->last_name;
                                    $shareTitle = get_the_title();
                                    $sharedDescription = get_the_excerpt();
                                    $formIntro = get_sub_field('form_introduction_title');
                                    ?>
                                    <div class="articleShare shareLinkContainer datasetShare">
                                        <?php if (get_sub_field( 'text' )) { ?>
                                            <span class="shareText share"><?php echo get_sub_field( 'text' ); ?></span>
                                        <?php } ?>
                                        <a href="#datasetsharepopupcontainer" class="button redOutline datasharepopup"><?php echo get_sub_field( 'button_text' ); ?></a>
                                        <span class="hidden-share-link" style="display: none;"><?php echo get_sub_field( 'share_download_url' ); ?></span>
                                        <span class="hidden-share-name" style="display: none;"><?php echo $first_name; ?> <?php echo $last_name; ?></span>
                                        <span class="hidden-share-title" style="display: none;"><?php echo $shareTitle; ?></span>
                                        <span class="hidden-share-excerpt" style="display: none;"><?php echo $sharedDescription; ?></span>
                                        <div style="display: none;">
                                            <div class="webinar-register-form datasetShare" id="datasetsharepopupcontainer">
                                                <div class="container">
                                                    <span class="data-column-container">
                                                        <span class="image-column">
                                                            <span class="content-popup-container">
                                                                <h2><?php echo get_sub_field( 'text' ); ?></h2>
                                                                <span class="slide-container">
                                                                    <span class="image-container">
                                                                        <span class="bg-container offset-image-container">
                                                                            <?php if ( $offsetimage ) { ?>
                                                                                <?php echo wp_get_attachment_image( $offsetimage['ID'], 'full', false, array( 'alt' => $offsetimage['alt'] ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                        <span class="bg-container">
                                                                            <?php if ( $image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                                <span class="page-title"><?php the_title(); ?></span>
                                                            </span>
                                                        </span>
                                                        <span class="form-column">
                                                            <span class="form-container">
                                                                <h2 class="form-title"><?php echo $formIntro; ?></h2>
                                                                <?php echo get_field( 'post_share_form', 'option' ); ?>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php if (get_field( 'download' ) == 'yes'){ ?>
                                    <?php $current_user = wp_get_current_user();
                                    $first_name = $current_user->first_name;
                                    $last_name = $current_user->last_name;
                                    $shareTitle = get_the_title();
                                    $sharedDescription = get_the_excerpt();
                                    ?>
                                    <?php if ( have_rows( 'membership_ids_for_share', 'options' ) ) : ?>
                                        <?php $counter = 0; ?>
                                            <?php while ( have_rows( 'membership_ids_for_share', 'options' ) ) : the_row(); ?>
                                                <?php if ( $counter == 0 ) {
                                                $members = $members . get_sub_field( 'membership_id' );
                                                } else {
                                                $members = $members . ',' . get_sub_field( 'membership_id' );
                                                } ?>
                                                <?php $counter++; ?>
                                            <?php endwhile; ?>
                                            <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                                <?php if ( have_rows( 'share' ) ) : ?>
                                                    <div class="articleShare shareLinkContainer">
                                                        <?php while ( have_rows( 'share' ) ) : the_row(); ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText share"><?php echo get_sub_field( 'text' ); ?></span>
                                                            <?php } ?>
                                                            <a href="#sharepopupcontainer" class="button redOutline sharepopup"><?php echo get_sub_field( 'button_text' ); ?></a>
                                                            <span class="hidden-share-link" style="display: none;"><?php echo get_sub_field( 'share_download_url' ); ?></span>
                                                            <span class="hidden-share-name" style="display: none;"><?php echo $first_name; ?> <?php echo $last_name; ?></span>
                                                            <span class="hidden-share-title" style="display: none;"><?php echo $shareTitle; ?></span>
                                                            <span class="hidden-share-excerpt" style="display: none;"><?php echo $sharedDescription; ?></span>
                                                            <div style="display: none;">
                                                                <div class="webinar-register-form" id="sharepopupcontainer">
                                                                    <div class="container">
                                                                        <span class="webinar-subtitle"><?php echo get_field( 'post_share_form_title', 'option' ); ?></span>
                                                                        <span class="form-container"><?php echo get_field( 'post_share_form', 'option' ); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            <?php } ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                <?php } ?> 
                            <?php endif; ?>
                        <?php }?>
                        <div class="relatedArticles<?php if(current_user_can('memberpress_authorized')) { ?><?php } else { ?> mobile-hide<?php } ?>">
                            <h2 class="related">You may also like</h2>
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
                            <?php $type_terms = get_field( 'you_may_also_like' ); ?>
                            <?php $types = array(); ?>
                            <?php if ( $type_terms ){ ?>
                                <?php foreach ( $type_terms as $type_term ): ?>
                                    <?php $types[] =  $type_term->slug; ?>
                                <?php endforeach; ?>
                            <?php
                                $args = array(
                                    'post_type'      => 'post',
                                    'posts_per_page' => 3,
                                    'post__not_in' => array( $post->ID ),
                                    'tax_query'      => array(
                                        'relation' => 'AND',
                                        array (
                                            'taxonomy' => 'topic',
                                            'field' => 'slug',
                                            'terms'    => $postTopic->slug
                                        ),
                                        array(
                                            'taxonomy' => 'filter-types',
                                            'field'    => 'slug',
                                            'terms' => $types,
                                            'operator' => 'IN',
                                        )
                                    )
                                );?>
                            <?php } else {
                                $args = array(
                                    'post_type'      => 'post',
                                    'posts_per_page' => 3,
                                    'post__not_in' => array( $post->ID ),
                                    'tax_query'      => array(
                                        'relation' => 'AND',
                                        array (
                                            'taxonomy' => 'topic',
                                            'field' => 'slug',
                                            'terms'    => $postTopic->slug
                                        )
                                    )
                                );?>
                            <?php }

                                $posts = new WP_Query( $args );
                                if( $posts->have_posts() ): ?>
                                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                        <div class="item">
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
                                                    }
                                                    if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                                        $postType->name = 'Voice of Customers';
                                                    }
                                                    ?>
                                                    <?php if($postTopic){?>
                                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                                    <?php } ?>
                                                    <?php if($postType){?>
                                                        <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                                    <?php } ?>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="title"><?php the_title();?></a>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif;?>
                                <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </article>
        <?php } ?>
    <?php } ?>
   

    <?php if(current_user_can('memberpress_authorized')) { ?>
        <?php if( has_term( 'expert-presentations', 'filter-types' ) ) {  ?>
            <?php get_template_part( 'templates/components/_keep-watching-slider-portal' ); ?>
        <?php } else if( has_term( 'community-interviews', 'filter-types' )) { ?>
            <?php get_template_part( 'templates/components/_keep-watching-slider-portal' ); ?>
        <?php } else if( has_term( 'customer', 'filter-types' )) { ?>
            <?php get_template_part( 'templates/components/_keep-watching-slider-portal' ); ?>
        <?php } else if( has_term( 'workshop-recordings', 'filter-types' )) { ?>
            <?php if( has_term( 'replay-post', 'replay' ) ) {  ?>
            <?php } else { ?>
                <?php get_template_part( 'templates/components/_keep-watching-slider-portal' ); ?>
             <?php }?>
        <?php } else if( has_term( 'data-insights', 'filter-types' )) { ?>
            <?php if ( have_rows( 'dataset_share' ) ) { ?>
                <?php get_template_part( 'templates/components/_related-articles-portal-data-insights' ); ?>
            <?php } else { ?>
                <?php get_template_part( 'templates/components/_related-articles-portal' ); ?>
            <?php }?>
            <?php } else if( has_term( 'data-insights', 'filter-types' )) { ?>
            <?php if ( have_rows( 'dataset_share' ) ) { ?>
                <?php get_template_part( 'templates/components/_related-articles-portal-data-insights' ); ?>
            <?php } else { ?>
                <?php get_template_part( 'templates/components/_related-articles-portal' ); ?>
            <?php }?>
        <?php } else { ?>
            <?php get_template_part( 'templates/components/_related-articles-portal' ); ?>
        <?php }?>
    <?php } ?>
<?php } ?>
<!-- Locked content for certain conntent types  -->

<?php if(get_field('members_only_request_download_form')){ ?> 
    <div class="preview-cta-form login-form-container mfp-hide" id="requestdownload">
        <div class="form-container-inner">
            <?php echo get_field('members_only_request_download_form'); ?>
        </div>
    </div>
<?php } else { ?>
    <div class="preview-cta-form login-form-container mfp-hide" id="requestdownload">
        <div class="form-container-inner">
            <?php echo get_field('members_only_request_download_form', 'options'); ?>
        </div>
    </div>
<?php } ?>

<div class="preview-cta-form login-form-container mfp-hide" id="requestdownloadPersona">
    <div class="form-container-inner">
        <?php echo get_field('members_only_request_download_form_persona', 'options'); ?>
    </div>
</div>
<div class="preview-cta-form login-form-container mfp-hide" id="requestdownloadSector">
    <div class="form-container-inner">
        <?php echo get_field('members_only_request_download_form_sector', 'options'); ?>
    </div>
</div>

<?php if(current_user_can('memberpress_authorized')) { ?>
<?php } else {?>
    <?php if( has_term( 'expert-presentations', 'filter-types' ) || has_term( 'community-interviews', 'filter-types' ) || has_term( 'workshop-recordings', 'filter-types' )) {  ?>
        <?php get_template_part( 'templates/components/_locked-content' ); ?>
    <?php } ?>
<?php } ?>


