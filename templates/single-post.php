<?php
global $membershipType;
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

    if (yoast_get_primary_term_id('filter-types')) {
        $primary_term_filter_id = yoast_get_primary_term_id('filter-types');
        $postFilterType = get_term($primary_term_filter_id, 'filter-types');
        
        $ancestors = get_ancestors($postFilterType->term_id, 'filter-types');
        if (!empty($ancestors)) {
            $top_parent_id = end($ancestors);
            $postFilterType = get_term($top_parent_id, 'filter-types');
        }
    } else {
        if (get_the_terms($post->ID, 'filter-types')) {
            $terms = get_the_terms($post->ID, 'filter-types');
            foreach ($terms as $term) {
                $ancestors = get_ancestors($term->term_id, 'filter-types');
                if (empty($ancestors)) {
                    $postFilterType = $term;
                } else {
                    $top_parent_id = end($ancestors);
                    $postFilterType = get_term($top_parent_id, 'filter-types');
                }
            }
        }
    }
?>
<?php 
    if (is_user_logged_in() || (function_exists('adapt_content_unlocked') && adapt_content_unlocked())) {
        $user_id = get_current_user_id(); // Get the current user ID
        if ( current_user_can('mepr-active', 'memberships:9811') || current_user_can('mepr-active', 'memberships:41272')) {
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
                if($postTopic -> slug == 'data-platforms-strategy'){
                    $post_count_data_platforms = (int) get_user_meta($user_id, 'mepr_data_platforms_strategy_views', true);                
                    if (!is_numeric($post_count_data_platforms)) {
                        $post_count_data_platforms = 1;
                    } else {
                        $post_count_data_platforms++;
                    }
                    update_user_meta($user_id, 'mepr_data_platforms_strategy_views', $post_count_data_platforms);
                }

                if($postTopic -> slug == 'cloud-infrastructure'){
                    // Get the current post count from the meta field 'mepr_cloud_infrastructure_views'
                    $post_count_cloud_infrastructure = (int) get_user_meta($user_id, 'mepr_cloud_infrastructure_views_new', true);
                    
                    // If the meta field doesn't exist or it's not a number, set it to 1
                    if (!is_numeric($post_count_cloud_infrastructure)) {
                        $post_count_cloud_infrastructure = 1;
                    } else {
                        // Increment the post count by one
                        $post_count_cloud_infrastructure++;
                    }

                    // Update user meta with the new post count
                    update_user_meta($user_id, 'mepr_cloud_infrastructure_views_new', $post_count_cloud_infrastructure);
                }

                if($postTopic -> slug == 'ai-innovation'){
                    $post_count_ai_innovation = (int) get_user_meta($user_id, 'mepr_ai_innovation_views', true);                
                    if (!is_numeric($post_count_ai_innovation)) {
                        $post_count_ai_innovation = 1;
                    } else {
                        $post_count_ai_innovation++;
                    }
                    update_user_meta($user_id, 'mepr_ai_innovation_views', $post_count_ai_innovation);
                }


                if($postTopic -> slug == 'customer-experience'){
                    $post_count_customer_experience = (int) get_user_meta($user_id, 'mepr_customer_experience_views', true);                
                    if (!is_numeric($post_count_customer_experience)) {
                        $post_count_customer_experience = 1;
                    } else {
                        $post_count_customer_experience++;
                    }
                    update_user_meta($user_id, 'mepr_customer_experience_views', $post_count_customer_experience);
                }

                if($postTopic -> slug == 'finance-transformation'){
                    // Get the current post count from the meta field 'mepr_finance_transformation_views'
                    $post_count_finance = (int) get_user_meta($user_id, 'mepr_finance_transformation_views', true);
                    
                    // If the meta field doesn't exist or it's not a number, set it to 1
                    if (!is_numeric($post_count_finance)) {
                        $post_count_finance = 1;
                    } else {
                        // Increment the post count by one
                        $post_count_finance++;
                    }

                    // Update user meta with the new post count
                    update_user_meta($user_id, 'mepr_finance_transformation_views', $post_count_finance);
                }

                if($postTopic -> slug == 'technology-modernisation'){
                    $post_count_technology_modernisation = (int) get_user_meta($user_id, 'mepr_technology_modernisation_views', true);                
                    if (!is_numeric($post_count_technology_modernisation)) {
                        $post_count_technology_modernisation = 1;
                    } else {
                        $post_count_technology_modernisation++;
                    }
                    update_user_meta($user_id, 'mepr_technology_modernisation_views', $post_count_technology_modernisation);
                }

                if($postTopic -> slug == 'security-risk'){
                    // Get the current post count from the meta field 'mepr_security_risk_views'
                    $post_count_security = (int) get_user_meta($user_id, 'mepr_security_risk_views', true);
                    
                    // If the meta field doesn't exist or it's not a number, set it to 1
                    if (!is_numeric($post_count_security)) {
                        $post_count_security = 1;
                    } else {
                        // Increment the post count by one
                        $post_count_security++;
                    }

                    // Update user meta with the new post count
                    update_user_meta($user_id, 'mepr_security_risk_views', $post_count_security);
                }

                if($postTopic -> slug == 'executive-leadership-strategy'){
                    $post_count_executive_leadership = (int) get_user_meta($user_id, 'mepr_executive_leadership_strategy_views', true);                
                    if (!is_numeric($post_count_executive_leadership)) {
                        $post_count_executive_leadership = 1;
                    } else {
                        $post_count_executive_leadership++;
                    }
                    update_user_meta($user_id, 'mepr_executive_leadership_strategy_views', $post_count_executive_leadership);
                }                   
            } 

            if($postFilterType){
                if($postFilterType -> slug == 'market-trend-reports'){
                    $post_count_market_trend = (int) get_user_meta($user_id, 'mepr_market_trend_reports_views', true);                
                    if (!is_numeric($post_count_market_trend)) {
                        $post_count_market_trend = 1;
                    } else {
                        $post_count_market_trend++;
                    }
                    update_user_meta($user_id, 'mepr_market_trend_reports_views', $post_count_market_trend);
                }
                if($postFilterType -> slug == 'community-intelligence-reports'){
                    $post_count_community_intelligence = (int) get_user_meta($user_id, 'mepr_community_intelligence_reports_views', true);                
                    if (!is_numeric($post_count_community_intelligence)) {
                        $post_count_community_intelligence = 1;
                    } else {
                        $post_count_community_intelligence++;
                    }
                    update_user_meta($user_id, 'mepr_community_intelligence_reports_views', $post_count_community_intelligence);
                }

                if($postFilterType -> slug == 'sector-outlooks'){
                    $post_count_sector_outlooks = (int) get_user_meta($user_id, 'mepr_sector_outlooks_views', true);                
                    if (!is_numeric($post_count_sector_outlooks)) {
                        $post_count_sector_outlooks = 1;
                    } else {
                        $post_count_sector_outlooks++;
                    }
                    update_user_meta($user_id, 'mepr_sector_outlooks_views', $post_count_sector_outlooks);
                }

                if($postFilterType -> slug == 'data-insights'){
                    $post_count_data_insight = (int) get_user_meta($user_id, 'mepr_data_insights_views', true);                
                    if (!is_numeric($post_count_data_insight)) {
                        $post_count_data_insight = 1;
                    } else {
                        $post_count_data_insight++;
                    }
                    update_user_meta($user_id, 'mepr_data_insights_views', $post_count_data_insight);
                }

                if($postFilterType -> slug == 'case-studies'){
                    $post_count_case_studies = (int) get_user_meta($user_id, 'mepr_case_studies_views', true);                
                    if (!is_numeric($post_count_case_studies)) {
                        $post_count_case_studies = 1;
                    } else {
                        $post_count_case_studies++;
                    }
                    update_user_meta($user_id, 'mepr_case_studies_views', $post_count_case_studies);
                }

                 if($postFilterType -> slug == 'community-interviews'){
                    if ($membershipType === 'advantage') {
                        $post_count_voice_of_customer = (int) get_user_meta($user_id, 'mepr_voice_of_customer_views', true);                
                        if (!is_numeric($post_count_voice_of_customer)) {
                            $post_count_voice_of_customer = 1;
                        } else {
                            $post_count_voice_of_customer++;
                        }
                        update_user_meta($user_id, 'mepr_voice_of_customer_views', $post_count_voice_of_customer);
                    } else {
                        $post_count_community_interviews = (int) get_user_meta($user_id, 'mepr_community_interviews_views', true);                
                        if (!is_numeric($post_count_community_interviews)) {
                            $post_count_community_interviews = 1;
                        } else {
                            $post_count_community_interviews++;
                        }
                        update_user_meta($user_id, 'mepr_community_interviews_views', $post_count_community_interviews);
                    }

                }

                if($postFilterType -> slug == 'market-narratives'){
                    $post_count_market_narratives = (int) get_user_meta($user_id, 'mepr_market_narratives_views', true);                
                    if (!is_numeric($post_count_market_narratives)) {
                        $post_count_market_narratives = 1;
                    } else {
                        $post_count_market_narratives++;
                    }
                    update_user_meta($user_id, 'mepr_market_narratives_views', $post_count_market_narratives);
                }

                if($postFilterType -> slug == 'cxo-buyer-persona-profiles'){
                    $post_count_buyer_persona_profiles = (int) get_user_meta($user_id, 'mepr_buyer_persona_profiles_views', true);                
                    if (!is_numeric($post_count_buyer_persona_profiles)) {
                        $post_count_buyer_persona_profiles = 1;
                    } else {
                        $post_count_buyer_persona_profiles++;
                    }
                    update_user_meta($user_id, 'mepr_buyer_persona_profiles_views', $post_count_buyer_persona_profiles);
                }

            }
            do_action('profile_update', $user_id, $current_user);
        }        
    }
$advantagePlus = "no";
$videoType = 'no';
if ( get_field ( 'featured_image_or_video' ) == 'video' ) { 
    $videoType = 'yes';
}

if(get_field('video_image')){ 
    $videoType = 'yes';
}

// Get current user
$current_user = wp_get_current_user();
$member = new MeprUser($current_user->ID);

// Get the active subscriptions for this user
$active_subscriptions = $member->active_product_subscriptions('ids');

if (
    ( current_user_can('administrator') && ( current_user_can('mepr-active') && (
        !in_array(60335, $active_subscriptions)
    )) ) ||
    ( current_user_can('mepr-active') && (
        in_array(49140, $active_subscriptions) ||
        in_array(9811, $active_subscriptions) ||
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
        in_array(41272, $active_subscriptions) || (
            current_user_can('administrator') && (
                in_array(49140, $active_subscriptions) ||
                in_array(41272, $active_subscriptions)
            )
        )
    )
) {
    $advantageType = "yes";
}


$hasTransformationSubs = false;
$transformationCTALink = 'https://adapt.com.au/services/it-research-advisory';

if (
    current_user_can('administrator') ||
    ( current_user_can('mepr-active') && (
        in_array(60335, $active_subscriptions)
    ))
) {
    $hasTransformationSubs = true;
}
?>
<section class="<?= esc_attr( $advantageType ); ?> <?= esc_attr( $advantagePlus ); ?> post-title-block <?php if($videoType == 'yes'){ ?>bg-black<?php } else { ?>bg-white<?php } ?>">
    <div class="container">
        <span class="back-container">
        <?php if ( has_term('replay-post', 'replay') ) { ?>
            <a class="back-button" href="/events/analyst-market-briefings/">Analyst Market Briefings</a>
        <?php } else { ?> 
            <?php
            $allowed_host = 'researchstaging1.adapt.com.au';
            $host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            if(substr($host, 0 - strlen($allowed_host)) == $allowed_host) { ?>
                <script>

                function goBack() {
                    window.history.back()
                }
                </script>
                <a class="back-button" onclick="goBack()">Research</a>
            <?php } else { ?>
                <a class="back-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">Research</a>
            <?php } ?>
        <?php } ?>
        
        </span>
        <div class="introduction-title">
            <h1 class="post-title header-large mobile-header-medium"><?php echo esc_html( get_the_title() ); ?></h1>
        </div>
    </div>
</section>
<?php if( has_term( 'replay-post', 'replay' ) ) {  ?>
    <?php
    $date_string = get_field('replay_event_date');
    $date = DateTime::createFromFormat('Ymd', $date_string);
    ?>
    <?php get_template_part( 'templates/post-components/_replay-preview' ); ?>
    <article class="webinar-article bg-white articleWrapper bg-white">
    	<div class="container">
    		<div class="column webinar-column first-column first">
    			<span class="webinar-subtitle"><?php echo esc_html( get_field( 'sub_title' ) ); ?></span>
    			<span class="webinar-content content">
    				<?php echo wp_kses_post( get_field( 'content' ) ); ?>
    			</span>
            </div>
            <div class="column webinar-column second second-column<?php if( get_field( 'number_of_speakers' ) == 'one') { ?> speaker-column<?php } ?>">
                <?php if(current_user_can('memberpress_authorized')) { ?>
                    <span class="share-save-container desktop">
                        <span class="saveInsight">
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                                <?php echo do_shortcode('[favorite_button]'); ?>
                            <?php } ?>
                        </span>
                        <?php if($advantagePlus == "no"){ ?>
                            <span class="shareArticle">
                                <a class="emailShare" href="mailto:?&subject=<?php echo esc_html( get_the_title() ); ?>&body=<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php if($advantageType == 'yes'){ ?>SHARE WITH A COLLEAGUE<?php } else { ?>SHARE THIS ARTICLE<?php } ?>	
                                </a>
                            </span>  
                        <?php } ?>
                                           
                    </span>
                <?php } ?>
                <?php if( get_field( 'number_of_speakers' ) == 'one') { ?>
                    <?php $post_object = get_field( 'speaker' ); ?>
                    <?php if ( $post_object ): ?>
                        <?php $post = $post_object; ?>
                        <?php setup_postdata( $post ); ?>
                            <div class="speaker-container-inner  bg-lightest">
                                <span class="webinar-subtitle speaker-subtitle">Meet the Speaker</span>
                                <span class="speaker-image">
                                    <?php
					$inline_img_110_src = get_field( 'speaker_image' );
					$inline_img_110_attach_id = $inline_img_110_src ? attachment_url_to_postid( $inline_img_110_src ) : 0;
					if ( $inline_img_110_attach_id ) {
						echo wp_get_attachment_image( $inline_img_110_attach_id, 'full', false, array( 'alt' => get_the_title() ) );
					} elseif ( $inline_img_110_src ) {
						echo '<img src="' . esc_url( $inline_img_110_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
                                </span>
                                <span class="description">
                                    <a class="author-link text-black" href="<?php the_permalink(); ?>" target="_self"><span class="speaker-name"><?php echo esc_html( get_the_title() ); ?></span></a>
                                    <span class="speaker-role"><?php echo esc_html( get_field('speaker_description') ); ?></span>
                                </span>
                                <div class="textBlock">
                                    <?php
                                         $text = get_field('speaker_details');
                                         $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                    ?>
                                    <span class="speaker-details-excerpt"><?php echo esc_html( $trimmed_content ); ?></span>
                                    <span class="speaker-details"><?php echo wp_kses_post( get_field('speaker_details') ); ?></span>
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
                <?php }?>
            </div>
        </div>
    </article>
    <?php if( get_field( 'number_of_speakers' ) == 'more-than-one') { ?>
        <section class="webinar-speaker-block bg-lightest">
            <div class="container">
                <?php if ( have_rows( 'webinar_speakers' ) ) : ?>
                    <?php while ( have_rows( 'webinar_speakers' ) ) : the_row(); ?>
                        <span class="webinar-subtitle"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
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
    											<?php
					$inline_img_111_src = get_field( 'speaker_image' );
					$inline_img_111_attach_id = $inline_img_111_src ? attachment_url_to_postid( $inline_img_111_src ) : 0;
					if ( $inline_img_111_attach_id ) {
						echo wp_get_attachment_image( $inline_img_111_attach_id, 'full', false, array( 'alt' => get_the_title() ) );
					} elseif ( $inline_img_111_src ) {
						echo '<img src="' . esc_url( $inline_img_111_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
    										</span>
    										<span class="description">
    											<a class="author-link text-black" href="<?php the_permalink(); ?>" target="_self"><span class="speaker-name"><?php echo esc_html( get_the_title() ); ?></span></a>
    											<span class="speaker-role"><?php echo esc_html( get_field('speaker_description') ); ?></span>
    										</span>
    										<div class="textBlock">
                                                <?php
                                                     $text = get_field('speaker_details');
                                                     $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                ?>
                                                <span class="speaker-details-excerpt"><?php echo esc_html( $trimmed_content ); ?></span>
                                                <span class="speaker-details"><?php echo wp_kses_post( get_field('speaker_details') ); ?></span>
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
            
                <?php get_template_part( 'templates/post-components/_slide-preview' ); ?>
            <?php endwhile; ?>
        <?php else : ?>
        <?php endif; ?>
    <?php } else { ?>
        <?php if ( has_term( ['data-insights', 'market-narratives', 'persona-profiles' ], 'filter-types' ) && have_rows( 'preview_module' ) ) { ?>
            <?php if ( have_rows( 'preview_module' ) ) : ?>
                <?php while ( have_rows( 'preview_module' ) ) : the_row(); ?>
                    <?php get_template_part( 'templates/post-components/_slide-preview' ); ?>
                <?php endwhile; ?>
            <?php else : ?>
            <?php endif; ?>
        <?php } else { ?>
            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                <?php get_template_part( 'templates/post-components/_video-preview' ); ?>
                
            <?php } else { ?>
                <?php get_template_part( 'templates/post-components/_article-title' ); ?>
            <?php } ?>
        <?php } ?> 
    <?php } ?>

    <?php 
    $addedBlur = false;
    ?>

    <?php if( has_term( 'expert-presentations', 'filter-types' ) || has_term( 'community-interviews', 'filter-types' ) || has_term( 'workshop-recordings', 'filter-types' )) {  ?>
        <article class="articleWrapper bg-white <?= esc_attr( $advantagePlus ); ?>">
            <div class="container">                
                <div class="column first">
                    <div class="article">                    
                        <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
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
                                <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes' || (function_exists('adapt_content_unlocked') && adapt_content_unlocked()) ) { ?>
                                 <?php echo wp_kses_post( get_field('article_content') ); ?>
                                <?php } else { ?>
                                    <?php if ($previewContent == false){ ?>
                                        <div class="content-trimmed">
                                            <?php
                                            $text = get_the_excerpt();
                                            if($text){?>
                                                <p><?php echo esc_html( $text ); ?></p>
                                                <?php
                                            } else {
                                            } ?>
                                        </div>
                                        <?php if ( have_rows( 'members_only_overlay_cta', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_overlay_cta', 'options' ) ) : the_row(); 
                                        if( $addedBlur ){
                                            continue;
                                        }

                                        $addedBlur = true;
                                    ?>
                                        <div class="blurred-image-cta-container firstblur">
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
                                            <div class="global-preview-cta-container background-black" style="background-image: url(<?php echo esc_url( $background_image_overlay['url'] ); ?>)">                                            
                                                <div class="preview-cta-inner">    
                                                    <div class="preview-cta-content">
                                                        <span class="title"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></span>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                            <?php $buttonCounter = 1; ?>
                                                            <span class="button-container">                                                                                                                   
                                                                <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                    <?php if( get_sub_field( 'button_type' ) == 'link'){ 
                                                                        $theLink = $hasTransformationSubs ? $transformationCTALink : get_sub_field( 'button_link' );
                                                                        ?> 
                                                                        <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo esc_url( $theLink ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                    <?php } else { ?> 
                                                                        <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                        <div style="display: none;">         
                                                                            <div class="preview-cta-form login-form-container" id="globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>">
                                                                                <span class="form-container-inner"><?php echo adapt_render_hubspot_embed( get_sub_field( 'hubspot_embed' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?></span>
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
                                                <span class="preview-cta-bottom-module"><?php echo esc_html( get_sub_field( 'login_text' ) ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo esc_attr( $postID );?>&redirect_to=<?php echo esc_url( $postURL );?>" target="_self">Login here</a></span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                                    <?php } ?>
                                <?php } ?>
                                <!--  -->
                            </div>
                        <?php } else { ?>
                            <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes' || (function_exists('adapt_content_unlocked') && adapt_content_unlocked()) ) { ?>
                            <?php } else { ?>
                                <?php if ($previewContent == false){ ?>
                                    <div class="content-trimmed">
                                        <?php
                                        $text = get_the_excerpt();
                                        if($text){?>
                                            <p><?php echo esc_html( $text ); ?></p>
                                            <?php
                                        } else {
                                        } ?>
                                    </div>
                                    <?php if ( have_rows( 'members_only_overlay_cta', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_overlay_cta', 'options' ) ) : the_row(); 
                                        if( $addedBlur ){
                                            continue;
                                        }

                                        $addedBlur = true;
                                        ?>
                                        <div class="blurred-image-cta-container second-blur">
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
                                            <div class="global-preview-cta-container background-black" style="background-image: url(<?php echo esc_url( $background_image_overlay['url'] ); ?>)">                                            
                                                <div class="preview-cta-inner">   
                                                    <div class="preview-cta-content">
                                                        <span class="title"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></span>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                            <?php $buttonCounter = 1; ?>
                                                            <span class="button-container">                                                                                                                    
                                                                <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                    <?php if( get_sub_field( 'button_type' ) == 'link'){ 
                                                                        $theLink = $hasTransformationSubs ? $transformationCTALink : get_sub_field( 'button_link' );
                                                                        ?> 
                                                                        <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo esc_url( $theLink ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                    <?php } else { ?> 
                                                                        <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                        <div style="display: none;">         
                                                                            <div class="preview-cta-form login-form-container" id="globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>">
                                                                                <span class="form-container-inner"><?php echo adapt_render_hubspot_embed( get_sub_field( 'hubspot_embed' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?></span>
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
                                                <span class="preview-cta-bottom-module"><?php echo esc_html( get_sub_field( 'login_text' ) ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo esc_attr( $postID );?>&redirect_to=<?php echo esc_url( $postURL );?>" target="_self">Login here</a></span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>                        
                        <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                            <?php if ( have_rows( 'content_blocks' ) ): ?>
                            <?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
                                <?php if ( get_row_layout() == 'article_content' ) : ?>
                                   <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="print-only scrollPos singlePost repeatableSingle">
                                       <div class="container">
                                           <div class="post-inner">
                                               <div class="fullWidth article-content">
                                                   <div class="articleWrapper">
                                                       <?php echo wp_kses_post( get_sub_field( 'article_content' ) ); ?>
                                                       <?php if( get_sub_field( 'infogram_image' )) { ?>
                                                           <?php
					$inline_img_112_src = get_sub_field( 'infogram_image' );
					$inline_img_112_attach_id = $inline_img_112_src ? attachment_url_to_postid( $inline_img_112_src ) : 0;
					if ( $inline_img_112_attach_id ) {
						echo wp_get_attachment_image( $inline_img_112_attach_id, 'full', false, array( 'alt' => '', 'class' => 'delete-no', 'style' => 'display: none;' ) );
					} elseif ( $inline_img_112_src ) {
						echo '<img class="delete-no" style="display: none;" src="' . esc_url( $inline_img_112_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( '' ) . '" />';
					}
				?>
                                                       <?php } ?>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                   </section>
                                <?php elseif ( get_row_layout() == 'snapshot' ) : ?>
                                    <?php get_template_part( 'templates/post-components/_snapshot' ); ?>
                                <?php elseif ( get_row_layout() == 'feature_image_or_infogram' ) : ?>
                                   <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only fullImageInfogram members-logged-in">
                                       <div class="container">
                                            <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
                                                <div class="featureBlock">
                                                    <?php
					$inline_img_113_src = get_sub_field( 'image' );
					$inline_img_113_attach_id = $inline_img_113_src ? attachment_url_to_postid( $inline_img_113_src ) : 0;
					if ( $inline_img_113_attach_id ) {
						echo wp_get_attachment_image( $inline_img_113_attach_id, 'full', false, array( 'alt' => '', 'class' => 'featureImage' ) );
					} elseif ( $inline_img_113_src ) {
						echo '<img class="featureImage" src="' . esc_url( $inline_img_113_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( '' ) . '" />';
					}
				?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="infogram-container">
                                                    <?php echo wp_kses_post( get_sub_field( 'infogram' ) ); ?>
                                                </div>
                                                <?php
					$inline_img_114_src = get_sub_field( 'infogram_image' );
					$inline_img_114_attach_id = $inline_img_114_src ? attachment_url_to_postid( $inline_img_114_src ) : 0;
					if ( $inline_img_114_attach_id ) {
						echo wp_get_attachment_image( $inline_img_114_attach_id, 'full', false, array( 'alt' => '', 'class' => 'delete-no', 'style' => 'display: none;' ) );
					} elseif ( $inline_img_114_src ) {
						echo '<img class="delete-no" style="display: none;" src="' . esc_url( $inline_img_114_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( '' ) . '" />';
					}
				?>
                                            <?php } ?>
                                       </div>
                                   </section>
                                <?php elseif ( get_row_layout() == 'image_grid_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="print-only imageGridBlock standard <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                        <div class="container">
                                            <div class="inner">
                                                <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>

                                    			<?php if ( have_rows( 'item' ) ) : ?>
                                                    <div class="gridWrapper">
                                        				<?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <?php if ( get_sub_field( 'image') ) { ?>
                                                                    <div class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'image' ) ); ?>);">
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <hr>
                                            					<span class="title">
                                                                    <?php echo esc_html( get_sub_field( 'title' ) ); ?>
                                                                </span>
                                                                <span class="description">
                                                                    <?php echo esc_html( get_sub_field( 'description' ) ); ?>
                                                                </span>
                                                                <?php if ( get_sub_field( 'logo') ) { ?>
                                                                    <div class="logoContainer">
                                                                        <?php
					$inline_img_115_src = get_sub_field( 'logo' );
					$inline_img_115_attach_id = $inline_img_115_src ? attachment_url_to_postid( $inline_img_115_src ) : 0;
					if ( $inline_img_115_attach_id ) {
						echo wp_get_attachment_image( $inline_img_115_attach_id, 'full', false, array( 'alt' => 'Adapt' ) );
					} elseif ( $inline_img_115_src ) {
						echo '<img src="' . esc_url( $inline_img_115_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( 'Adapt' ) . '" />';
					}
				?>
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
                                                        <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'logo_grid' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="print-only scrollPos logoGrid <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                		<div class="container">
                                			<div class="titleBlock">
                                				<span class="title">
                                					<h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
                                				</span>

                                				<span class="description <?php echo esc_attr( get_sub_field( 'top_right_text_position' ) ); ?>">
                                					<h3><?php echo esc_html( get_sub_field( 'top_right_text' ) ); ?></h3>
                                				</span>
                                			</div>

                                			<?php if ( have_rows( 'logos' ) ) : ?>
                                				<div class="logoBlock">
                                					<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                						<div class="logo">
                                							<span class="logoContainer">
                                								<div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'logo' ) ); ?>);">
                                								</div>
                                							</span>
                                							<span class="logoTitle">
                                								<?php echo esc_html( get_sub_field( 'title' ) ); ?>
                                							</span>
                                						</div>
                                					<?php endwhile; ?>
                                				</div>
                                			<?php endif; ?>

                                			<?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                				<a class="logoBlockLink <?php echo esc_attr( get_sub_field( 'link_style' ) ); ?>" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
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
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only imageGridBlock speakerBlock <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                        <div class="container">
                                            <div class="inner">
                                                <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>

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
                                                                                <div class="image" style="background-image: url(<?php echo esc_url( get_field( 'speaker_image' ) ); ?>);">
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <hr>
                                            							<span class="title"><?php echo esc_html( get_the_title() ); ?></span>
                                                                        <span class="description">
                                                                            <?php echo esc_html( get_field( 'speaker_description' ) ); ?>
                                                                        </span>
                                                                        <?php if ( get_field( 'logo') ) { ?>
                                                                            <div class="logoContainer">
                                                                                <?php
					$inline_img_116_src = get_field( 'logo' );
					$inline_img_116_attach_id = $inline_img_116_src ? attachment_url_to_postid( $inline_img_116_src ) : 0;
					if ( $inline_img_116_attach_id ) {
						echo wp_get_attachment_image( $inline_img_116_attach_id, 'full', false, array( 'alt' => 'Adapt' ) );
					} elseif ( $inline_img_116_src ) {
						echo '<img src="' . esc_url( $inline_img_116_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( 'Adapt' ) . '" />';
					}
				?>
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
                                                    <div class="buttonBlock <?php echo esc_attr( get_sub_field('link_orientation') ); ?>">
                                                        <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
                                        <?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
                                <?php elseif ( get_row_layout() == 'two_column_text_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="print-only scrollPos twoColumnTextBlock <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="titleBlock">
                                                    <h2><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                                                    <hr>

                                                </div>
                                                <div class="textBlock">
                                                    <?php echo esc_html( get_sub_field( 'text_block' ) ); ?>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only textImageBlock <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="title">
                                                    <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
                                                    <hr>
                                                </div>
                                                <?php if ( have_rows( 'item' ) ) : ?>
                                                    <div class="itemsWrapper">
                                                        <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <?php if ( get_sub_field( 'image') ) { ?>
                                                                    <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>" class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'image' ) ); ?>);">
                                                                        </div>
                                                                    </a>
                                                                <?php } ?>
                                                                <span class="title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
                                                                <span class="description">
                                                                    <?php echo esc_html( get_sub_field( 'text' ) ); ?>
                                                                </span>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ( have_rows( 'button_block' ) ) : ?>
                                                <div class="buttonBlock">
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'half_text_half_image' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only halfHalfBlock <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                        <div class="textBlock <?php echo esc_attr( get_sub_field( 'image_position' ) ); ?>">
                                            <div class="v-wrap">
                                                <div class="v-box">
                                                    <h2><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                                                    <hr>
                                                    <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                        <span class="desktopText"><?php echo esc_html( get_sub_field( 'text_block' ) ); ?></span>
                                                    <?php } ?>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink desktop <?php echo esc_attr( get_sub_field( 'link_style' ) ); ?>" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="imageBlock <?php echo esc_attr( get_sub_field( 'image_position' ) ); ?>">
                                            <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'image' ) ); ?>);">
                                            </div>
                                        </div>
                                        <div class="textBlock mobile">
                                            <div class="container">
                                                <div class="inner">
                                                    <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                        <span class="mobileText"><?php echo esc_html( get_sub_field( 'text_block' ) ); ?></span>
                                                    <?php } ?>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink <?php echo esc_attr( get_sub_field( 'link_style' ) ); ?>" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
                                    <?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
                                <?php elseif ( get_row_layout() == 'speaker_quote_carousel' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no speakerQuoteCarousel">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="titleBlock">
                                                    <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                    <?php } ?>
                                                </div>

                                                <?php if ( have_rows( 'item' ) ) : ?>
                                                    <div class="owl-carousel speaker-gallery">
                                                        <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <div class="imageContainer">
                                                                    <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'image' ) ); ?>);">
                                                                    </div>
                                                                </div>
                                                                <div class="textBlock">
                                                                    <div class="v-wrap">
                                                                        <div class="v-box">
                                                                            <span class="quoteBlock">
                                                                                <?php echo esc_html( get_sub_field( 'quote' ) ); ?>
                                                                            </span>
                                                                            <span class="quoteAuthor">
                                                                                <?php echo esc_html( get_sub_field( 'quote_author' ) ); ?>
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
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no quoteBlockNoImage">
                                        <div class="container">
                                            <div class="inner">
                                                <?php if ( have_rows( 'quotes' ) ) : ?>
                                                    <div class="owl-carousel quote">
                                                        <?php while ( have_rows( 'quotes' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <div class="v-wrap">
                                                                    <div class="v-box">
                                                                        <span class="quoteBlock">
                                                                            <?php echo esc_html( get_sub_field( 'quote' ) ); ?>
                                                                        </span>
                                                                        <span class="quoteAuthor">
                                                                            <?php echo esc_html( get_sub_field( 'quote_author' ) ); ?>
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
                                                        <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>
                                    <section id="<?php echo esc_attr( get_sub_field( 'id' ) ); ?>" class="scrollPos imageGridBlock standard logos">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="titleBlock">
                                                    <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                        <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
                                                        <span class="hrWrapper">
                                                            <hr>
                                                        </span>
                                                    <?php } ?>
                                                    <?php if ( get_sub_field ( 'description' ) ) { ?>
                                                        <h3><?php echo esc_html( get_sub_field( 'description' ) ); ?></h3>
                                                    <?php } ?>
                                                </div>

                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                    <div class="gridWrapper">
                                                        <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                            <div class="item">
                                                                <div class="imageContainer">
                                                                    <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'logo' ) ); ?>);">
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
                                                        <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
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
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no pricingBlock">
                                            <div class="container">
                                                <h2>Membership</h2>
                                                <?php if ( have_rows( 'first_pricing_block', 'option' ) ) : ?>
                                                    <div class="pricingBlockItem first">
                                                        <div class="innerWrapper">
                                                            <?php while ( have_rows( 'first_pricing_block', 'option' ) ) : the_row(); ?>
                                                                <span class="title">
                                                                    <?php echo esc_html( get_sub_field( 'title', 'option' ) ); ?>
                                                                    <span class="hrWrapper">
                                                                        <hr>
                                                                    </span>
                                                                </span>
                                                                <span class="priceBlockWrapper">
                                                                    <span class="priceBlock">
                                                                        <span class="dollar">$</span><?php echo esc_html( get_sub_field( 'price_block', 'option' ) ); ?><span class="month">/month</span>
                                                                    </span>
                                                                </span>
                                                                <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                    <div class="features">
                                                                        <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                            <span class="feature"><?php echo esc_html( get_sub_field( 'feature', 'option' ) ); ?></span>
                                                                        <?php endwhile; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <span class="pricingButtonWrapper">
                                                                <a class="small" href="<?php echo esc_url( get_sub_field( 'button_link', 'option' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'button_target', 'option' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text', 'option' ) ); ?></a>
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
                                                                        <?php echo esc_html( get_sub_field( 'title', 'option' ) ); ?>
                                                                        <span class="hrWrapper">
                                                                            <hr>
                                                                        </span>
                                                                    </span>
                                                                    <span class="priceBlockWrapper">
                                                                        <span class="priceBlock">
                                                                            <span class="dollar">$</span><?php echo esc_html( get_sub_field( 'price_block', 'option' ) ); ?><span class="month">/month</span>
                                                                        </span>
                                                                    </span>
                                                                    <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                        <div class="features">
                                                                            <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                <span class="feature"><?php echo esc_html( get_sub_field( 'feature', 'option' ) ); ?></span>
                                                                            <?php endwhile; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <span class="pricingButtonWrapper">
                                                                <a class="small" href="<?php echo esc_url( get_sub_field( 'button_link', 'option' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'button_target', 'option' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text', 'option' ) ); ?></a>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ( have_rows( 'last_pricing_block', 'option' ) ) : ?>
                                                    <div class="pricingBlockItem last">
                                                        <?php while ( have_rows( 'last_pricing_block', 'option' ) ) : the_row(); ?>
                                                            <div class="innerWrapper">
                                                                <span class="title">
                                                                    <?php echo esc_html( get_sub_field( 'title', 'option' ) ); ?>
                                                                    <span class="hrWrapper">
                                                                        <hr>
                                                                    </span>
                                                                </span>
                                                                <span class="priceBlockWrapper">
                                                                    <span class="priceBlock">
                                                                        <span class="dollar">$</span><?php echo esc_html( get_sub_field( 'price_block', 'option' ) ); ?><span class="month">/month</span>
                                                                    </span>
                                                                </span>
                                                                <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                    <div class="features">
                                                                        <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                            <span class="feature"><?php echo esc_html( get_sub_field( 'feature', 'option' ) ); ?></span>
                                                                        <?php endwhile; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <span class="pricingButtonWrapper">
                                                                <a class="small" href="<?php echo esc_url( get_sub_field( 'button_link', 'option' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'button_target', 'option' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text', 'option' ) ); ?></a>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php } ?>
                                <?php elseif ( get_row_layout() == 'two_column_block_with_text_and_featured_quote' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only twoColumnWithTextAndFeaturedQuote">
                                        <div class="container">
                                            <div class="inner">
                                                <div class="column first">
                                                    <h2>
                                                        <?php echo esc_html( get_sub_field( 'title' ) ); ?>
                                                    </h2>
                                                    <div class="textBlock">
                                                        <?php echo esc_html( get_sub_field( 'text_block' ) ); ?>
                                                    </div>
                                                    <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                        <a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                    <?php } ?>
                                                </div>

                                                <div class="column last">
                                                    <div class="item">
                                                        <div class="v-wrap">
                                                            <div class="v-box">
                                                                <span class="quoteBlock">
                                                                    <?php echo esc_html( get_sub_field( 'quote' ) ); ?>
                                                                </span>
                                                                <span class="quoteAuthor">
                                                                    <?php echo esc_html( get_sub_field( 'quote_author' ) ); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'video_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no videoBlock postVideoBlock">
                                        <div class="container">
                                            <?php if( get_sub_field('vimeo_code_popup')){ ?>
                                                <a href="https://vimeo.com/<?php echo esc_attr( get_sub_field('vimeo_code_popup') ); ?>" class="image popup-vimeo">
                                            <?php } else { ?>
                                                <a href="" class="image postPlayBtn">
                                            <?php } ?>
                                                <div class="imageSizeContainer">
                                                    <span class="overlayGradient"></span>
                                                    <div class="bgContainer">
                                                        <?php
					$inline_img_117_src = get_sub_field( 'video_poster_image' );
					$inline_img_117_attach_id = $inline_img_117_src ? attachment_url_to_postid( $inline_img_117_src ) : 0;
					if ( $inline_img_117_attach_id ) {
						echo wp_get_attachment_image( $inline_img_117_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
					} elseif ( $inline_img_117_src ) {
						echo '<img class="desktop" src="' . esc_url( $inline_img_117_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( '' ) . '" />';
					}
				?>
                                                    </div>
                                                    <span class="watchIcon"></span>
                                                    <span class="textContainer">
                                                        <span class="title"><?php echo esc_html( get_the_title() ); ?></span>
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="videoPlayerContainer videoBlock">
                                            <span class="closeVideo"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/close-grey.svg" width="25" height="25" loading="lazy" decoding="async" alt="Close" /></span>
                                            <div class="videoWrapper">
                                                <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                                    <source type="video/mp4" src="<?php echo esc_url( get_sub_field('vimeo_code') ); ?>" />
                                                </video>
                                            </div>
                                        </div>

                                    </section>
                                <?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
                                    <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>
                                <?php elseif ( get_row_layout() == 'full_width_text_editor' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only fullWidthTextEditor<?php if ( get_sub_field( 'font') ) { ?> <?php echo esc_attr( get_sub_field( 'font' ) );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php echo esc_attr( get_sub_field( 'font_colour' ) ); ?><?php } ?>">
                                        <div class="container">
                                            <?php echo wp_kses_post( get_sub_field( 'text_editor' ) ); ?>
                                            <?php if ( have_rows( 'button_block' ) ) : ?>
                                                <div class="buttonBlock">
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </section>
                                <?php elseif ( get_row_layout() == 'form_block' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
                                		<div class="container">
                                			<div class="inner">
                                				<div class="formWrapper register">
                                					<?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                						<h2><?php echo esc_html( get_sub_field('block_title') ); ?></h2>
                                						<?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                							<hr>
                                						<?php } ?>
                                					<?php } ?>
                                					<?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                						<h3><?php echo esc_html( get_sub_field('block_description') ); ?></h3>
                                					<?php } ?>
                                					<?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                						<?php echo wp_kses_post( get_sub_field('form_shortcode') ); ?>
                                					<?php }?>
                                					<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?>
                                						<a class="button popup-modal" href="#<?php echo esc_attr( get_sub_field('form_id') ); ?>"><?php echo esc_html( get_sub_field('button_text') ); ?></a>
                                						<div class="formPopup mfp-hide" id="<?php echo esc_attr( get_sub_field('form_id') ); ?>">
                                							<a class="popup-modal-dismiss"></a>
                                							<?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                								<h2><h2><?php echo esc_html( get_sub_field('block_title') ); ?></h2></h2>
                                							<?php } ?>
                                							<?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                								<h3><?php echo esc_html( get_sub_field('block_description') ); ?></h3>
                                							<?php } ?>
                                								<div class="formWrapper register"><?php echo wp_kses_post( get_sub_field('form_shortcode') ); ?></div>
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
                    <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                        <?php if ( have_rows( 'contributors' ) ) : ?>
                            <div class="authors">
                                <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                    <?php $post_object = get_sub_field( 'contributor_name' ); ?>
        							<?php if ( $post_object ): ?>
        								<?php $post = $post_object; ?>
        								<?php setup_postdata( $post ); ?>
        									<div class="speaker-container-inner">
        										<span class="speaker-image">
                                                    <?php if(get_field('speaker_image')){ ?>
                                                        <?php
					$inline_img_118_src = get_field( 'speaker_image' );
					$inline_img_118_attach_id = $inline_img_118_src ? attachment_url_to_postid( $inline_img_118_src ) : 0;
					if ( $inline_img_118_attach_id ) {
						echo wp_get_attachment_image( $inline_img_118_attach_id, 'full', false, array( 'alt' => get_the_title() ) );
					} elseif ( $inline_img_118_src ) {
						echo '<img src="' . esc_url( $inline_img_118_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
                                                    <?php } else if(get_field('listing_avatar')){ ?>
                                                        <?php $img = get_field('listing_avatar');
                                                        $url = is_array($img) ? $img['url'] : (is_int($img) ? wp_get_attachment_image_url($img, 'full') : $img);
                                                        if ($url): ?>
                                                            <?php
					$inline_img_119_src = $url;
					$inline_img_119_attach_id = $inline_img_119_src ? attachment_url_to_postid( $inline_img_119_src ) : 0;
					if ( $inline_img_119_attach_id ) {
						echo wp_get_attachment_image( $inline_img_119_attach_id, 'full', false, array( 'alt' => get_the_title() ) );
					} elseif ( $inline_img_119_src ) {
						echo '<img src="' . esc_url( $inline_img_119_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
                                                        <?php endif; ?>
                                                   <?php } ?>
        										</span>
        										<span class="description">
                                                    <span class="title"><?php if(get_sub_field('contributors_pre_heading')){ ?><?php echo esc_html( get_sub_field('contributors_pre_heading') ); ?><?php } else { ?>Contributor<?php } ?></span>
        											<a class="author-link text-black" href="<?php the_permalink(); ?>" target="_self"><span class="speaker-name"><?php echo esc_html( get_the_title() ); ?></span></a>
        											<span class="speaker-role">
                                                        <?php if(get_field('speaker_description')){ ?>
                                                            <?php echo esc_html( get_field('speaker_description') ); ?>
                                                        <?php } else if(get_field('role')){ ?>
                                                            <?php echo esc_html( get_field('role') ); ?>
                                                        <?php } ?>
                                                        
                                                    </span>
        										</span>
                                                <div class="textBlock">
                                                    <?php if(get_field('speaker_details')){ ?>
                                                        <?php
                                                            $text = get_field('speaker_details');
                                                            $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                        ?>
                                                        <span class="speaker-details-excerpt"><?php echo esc_html( $trimmed_content ); ?></span>
                                                        <span class="speaker-details">
                                                            <?php echo wp_kses_post( get_field('speaker_details') ); ?>
                                                            <span class="speaker-details-less">Less</span>
                                                        </span>
                                                    <?php } else { ?> 
                                                        <?php
                                                            $text = get_field('listing_excerpt');
                                                            $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                        ?>
                                                        <span class="speaker-details-excerpt"><?php echo esc_html( $trimmed_content ); ?></span>
                                                        <span class="speaker-details">
                                                            <?php echo esc_html( get_field('listing_excerpt') ); ?>
                                                            <span class="speaker-details-less">Less</span>
                                                        </span>
                                                    <?php } ?>
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
                <div class="column second">
                    <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                        <span class="share-save-container dekstop">
                            <span class="saveInsight">
                                <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                                    <?php echo do_shortcode('[favorite_button]'); ?>
                                <?php } ?>
                            </span>
                             <?php if($advantagePlus == "no"){ ?>
                            <span class="shareArticle">
                                <a class="emailShare" href="mailto:?&subject=<?php echo esc_html( get_the_title() ); ?>&body=<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php if($advantageType == 'yes'){ ?>SHARE WITH A COLLEAGUE<?php } else { ?>SHARE THIS ARTICLE<?php } ?>	
                                </a>
                            </span>  
                        <?php } ?>                   
                        </span>
                    <?php } ?>                    
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
                    <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                        <?php if (get_field( 'download' ) == 'yes'){ ?>
                            <?php if ( have_rows( 'membership_ids_for_download', 'options' ) ) : ?>
                                <?php $counter = 0; ?>
                                <?php $members = ''; ?>
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
                                                                <span class="shareText download desktop"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                                            <?php } ?>
                                                            <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                            <?php if ( $preview_image ) { ?>
                                                                <span class="download-image-container <?php echo esc_attr( get_sub_field( 'image_orientation' ) ); ?>">
                                                                    <span class="bg-container">
                                                                        <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, array( 'alt' => $preview_image['alt'] ) ); ?>
                                                                    </span>
                                                                </span>
                                                            <?php } ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText download mobile"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                                            <?php } ?>
                                                            <a id="downloadButton" href="<?php echo esc_url( get_sub_field( 'download_url' ) ); ?>" target="_blank" rel="noopener noreferrer" class="button redOutline"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
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
                                                            <span class="shareText download desktop"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                                        <?php } ?>
                                                        <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                        <?php if ( $preview_image ) { ?>
                                                            <span class="download-image-container <?php echo esc_attr( get_sub_field( 'image_orientation' ) ); ?>">
                                                                <span class="bg-container">
                                                                    <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, array( 'alt' => $preview_image['alt'] ) ); ?>
                                                                </span>
                                                            </span>
                                                        <?php } ?>
                                                        <?php if (get_sub_field( 'text' )) { ?>
                                                            <span class="shareText download mobile"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                                        <?php } ?>
                                                        <a id="downloadButton" href="<?php echo esc_url( get_sub_field( 'download_url' ) ); ?>" target="_blank" rel="noopener noreferrer" class="button redOutline"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
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
                    <?php } ?>
                    <div class="relatedArticles<?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?><?php } else { ?> mobile-hide<?php } ?>">
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
                                                    $postType->name = 'Voice of Customer';
                                                }
                                                ?>
                                                <?php if($postTopic){?>
                                                    <a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topicFilterText"><?php echo esc_html( $postTopic->name ); ?></a>
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
                    </div>
                </div>
            </div>
        </article>
    <?php } else { ?> 
        <?php if ( have_rows( 'preview_module' ) ) { ?>
            <?php include locate_template( '/templates/post-components/_single-post-article-body.php' ); ?>
        <?php } else { ?>
            <?php include locate_template( '/templates/post-components/_single-post-article-body.php' ); ?>
        <?php } ?>
    <?php } ?>
   

    <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
        <?php if( has_term( 'expert-presentations', 'filter-types' ) ) {  ?>
            <?php get_template_part( 'templates/components/_related-articles-portal' ); ?>
        <?php } else if( has_term( 'community-interviews', 'filter-types' )) { ?>
            <?php get_template_part( 'templates/components/_related-articles-portal' ); ?>
        <?php } else if( has_term( 'customer', 'filter-types' )) { ?>
            <?php get_template_part( 'templates/components/_related-articles-portal' ); ?>
        <?php } else if( has_term( 'workshop-recordings', 'filter-types' )) { ?>
            <?php if( has_term( 'replay-post', 'replay' ) ) {  ?>
            <?php } else { ?>
                <?php get_template_part( 'templates/components/_related-articles-portal' ); ?>
             <?php }?>
        <?php } else if( has_term( 'data-insights', 'filter-types' )) { ?>
            <?php if ( have_rows( 'dataset_share' ) ) { ?>
                <?php get_template_part( 'templates/components/_related-articles-portal' ); ?>
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
            <?php echo adapt_render_hubspot_embed( get_field('members_only_request_download_form') ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?>
        </div>
    </div>
<?php } else { ?>
    <div class="preview-cta-form login-form-container mfp-hide" id="requestdownload">
        <div class="form-container-inner">
            <?php echo adapt_render_hubspot_embed( get_field('members_only_request_download_form', 'options') ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?>
        </div>
    </div>
<?php } ?>

<div class="preview-cta-form login-form-container mfp-hide" id="requestdownloadPersona">
    <div class="form-container-inner">
        <?php echo adapt_render_hubspot_embed( get_field('members_only_request_download_form_persona', 'options') ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?>
    </div>
</div>
<div class="preview-cta-form login-form-container mfp-hide" id="requestdownloadSector">
    <div class="form-container-inner">
        <?php echo adapt_render_hubspot_embed( get_field('members_only_request_download_form_sector', 'options') ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?>
    </div>
</div>


