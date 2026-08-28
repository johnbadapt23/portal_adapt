<?php global $current_user, $first_name, $last_name, $user_email, $membershipType, $advantageType, $member;
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
<section class="researchArticleTextHeader bg-white">
    <div class="container">                        
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
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
                </div>
                <?php if ( get_field ( 'image_caption' )) { ?>
                    <div class="caption"><?php echo get_field ( 'image_caption' ); ?></div>
                <?php } ?>
            </div>
            <div class="textContainer test">                
                <?php
                $overview_text = get_sub_field( 'overview_text' );
                ?>
                <?php $noMargin = 'yes'; ?>
                <?php if ( $overview_text ) : ?>
                    <span class="text"><?php echo wp_kses_post( $overview_text ); ?></span>
                <?php endif; ?>
                <?php if(current_user_can('memberpress_authorized')) { ?>
                    <?php if ( has_term( ['sector-outlooks', 'cxo-buyer-persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                        <?php if( $advantagePlus == 'yes') { ?> 
                            <?php $download = get_sub_field( 'download' ); ?>
                            <?php if ( $download ) { ?>
                                <?php $noMargin = 'no'; ?>
                                <a class="download button red-button" target="_blank" rel="noopener noreferrer" href="<?php echo $download['url']; ?>">Download</a>                                             
                            <?php } ?>
                        <?php } else { ?> 
                            <?php if ( has_term( ['cxo-buyer-persona-profiles' ], 'filter-types' )){ ?>
                                    <?php $download = get_sub_field( 'download' ); ?>
                                <?php if ( $download ) { ?>   
                                    <?php $noMargin = 'no'; ?> 
                                    <a class="download button red-button disabled-button locked-request" href="#requestdownloadPersona">Request Access</a>                                       
                                <?php } ?>
                            <?php } else { ?> 
                                    <?php $download = get_sub_field( 'download' ); ?>
                                <?php if ( $download ) { ?>   
                                    <?php $noMargin = 'no'; ?>
                                    <a class="download button red-button disabled-button locked-request" href="#requestdownloadSector">Request Access</a>                                       
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?> 
                        <?php if ( have_rows( 'download_link' ) ) : ?>
                            <?php $download = 'yes'; ?>
                            <?php $noMargin = 'no'; ?>
                            <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                <a class="download button red-button" target="_blank" rel="noopener noreferrer" href="<?php echo get_sub_field( 'download_url' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a> 
                            <?php endwhile; ?>                            
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>                       
                    <?php } ?>                                        
                <?php } else { ?>

                    <?php $download = get_sub_field( 'download' ); ?>
                    
                    <?php if ( $download ) { ?>  
                        <?php $downloadButtonText = get_field('request_download_button_text'); ?>  
                        <a class="download button red-button disabled-button locked-request" href="#requestdownload"><?php if($downloadButtonText){ ?><?php echo $downloadButtonText; ?><?php } else { ?>Request to Download<?php } ?></a>                                       
                        <?php $noMargin = 'no'; ?>
                    <?php } else { ?> 
                        <?php if ( have_rows( 'download_link' ) ) : ?>
                            <?php $downloadButtonText = get_field('request_download_button_text'); ?> 
                            <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                <a class="download button red-button disabled-button locked-request" href="#requestdownload"><?php if($downloadButtonText){ ?><?php echo $downloadButtonText; ?><?php } else { ?>Request to Download<?php } ?></a>      
                                <?php $noMargin = 'no'; ?>                                 
                            <?php endwhile; ?>                            
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>    
                    <?php } ?>
                <?php } ?>
                <span class="published labelSmall text-dark-grey<?php if ( $noMargin == 'no' ) { ?><?php } else { ?> no-margin-border<?php } ?>">

                        Published <?php echo get_the_date('M j, Y') ?> in
                    
                </span>
                <span class="type-topic labelSmall">
                    <?php $topics  = [];

                        // Get all topic terms once
                        $all_topics = get_the_terms($post_id, 'topic');

                        // Bail early if none
                        if ($all_topics && !is_wp_error($all_topics)) {

                            // 1. Try to get Yoast primary
                            $primary_id = yoast_get_primary_term_id('topic');

                            if ($primary_id) {
                                $primary = get_term($primary_id, 'topic');

                                if ($primary && !is_wp_error($primary)) {
                                    $topics[] = $primary;
                                }

                                // 2. Find ONE secondary (first non-primary)
                                foreach ($all_topics as $term) {
                                    if ($term->term_id !== (int) $primary_id) {
                                        $topics[] = $term;
                                        break;
                                    }
                                }

                            } else {
                                // 3. No primary → just take first two
                                $topics = array_slice($all_topics, 0, 2);
                            }
                        }

                        // 4. Output (max 2 guaranteed)
                        foreach ($topics as $topic) : ?>
                            <a href="<?php echo esc_url(get_term_link($topic)); ?>" class="topic-filter red-text">
                                <?php echo esc_html($topic->name); ?>
                            </a>
                        <?php endforeach; ?>

                                            <?php if (yoast_get_primary_term_id('filter-types')) {
                        $primary_term_type_id = yoast_get_primary_term_id('filter-types');
                        $postType = get_term( $primary_term_type_id );
                        } else {
                            if(get_the_terms( $post->ID, 'filter-types' )){
                                $terms = get_the_terms( $post->ID, 'filter-types' );
                                foreach($terms as $term) {
                                    $postType = $term;
                                }
                            }
                        }?>
                    <?php if ( !empty( $postType ) ) { ?>
                        <span class="published labelSmall text-dark-grey tytpe-label">Type</span>
                        <a href="<?php echo get_term_link($postType); ?>" class="topic-filter red-text"><?php echo $postType->name; ?> </a>
                    <?php } ?>
                </span>


                                                <?php if ( have_rows( 'contributors' ) ) : ?>

    <?php
    $contributors = get_field( 'contributors' );
    $count = 0;

    if ( is_array( $contributors ) ) {
        foreach ( $contributors as $contributor ) {
            if ( ! empty( $contributor['contributor_name'] ) ) {
                $count++;
            }
        }
    }
    ?>

    <span class="contributor-container">
        <span class="contributor-title labelSmall text-dark-grey">
            <?php echo ( $count > 1 ) ? 'Authors' : 'Author'; ?>
        </span>

        <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
            <?php $post_object = get_sub_field( 'contributor_name' ); ?>
            <?php if ( $post_object ) : ?>
                <?php
                $post = $post_object;
                setup_postdata( $post );
                ?>
                <!-- <a href="<?php the_permalink(); ?>"> -->
                    <span class="contributor labelSmall text-black">
                        
                            <?php the_title(); ?>
                       
                    </span>
                     <!-- </a> -->
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    </span>

<?php endif; ?>


            </div>
            <span class="share-save-container mobile">
                <span class="saveInsight">
                    <?php if(current_user_can('memberpress_authorized')) { ?>
                        <?php echo do_shortcode('[favorite_button]'); ?>
                    <?php } ?>
                </span>
                 <?php if($advantagePlus == "no"){ ?>
                    <span class="shareArticle">
                        <a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
                            <?php if($advantageType == 'yes'){ ?>SHARE WITH A COLLEAGUE<?php } else { ?>SHARE THIS ARTICLE<?php } ?>	
                        </a>
                    </span>  
                <?php } ?>                               
            </span>
        </div>
    </div>
</section>