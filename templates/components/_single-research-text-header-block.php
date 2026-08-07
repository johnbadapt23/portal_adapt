<section class="researchArticleTextHeader bg-white">
    <div class="container">
        <div class="item">
            <div class="imageSizeContainer">
                <div class="bgContainer">
                    <!-- <?php if ( get_field( 'listing_image') ) { ?>
                        <?php $image = get_field( 'listing_image'); ?>
                    <?php } else { ?>
                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                            <?php $image = get_field( 'video_poster'); ?>
                        <?php } else { ?>
                            <?php $image = get_field( 'featured_image'); ?>
                        <?php } ?>
                    <?php } ?> -->
                    <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
                </div>
                <div class="caption">Photo by</div>
            </div>
            <div class="textContainer">
                <?php if(current_user_can('memberpress_authorized')) { ?>
                    <?php if ( has_term( ['sector-outlooks', 'persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                        <?php if( $advantagePlus == 'yes') { ?> 
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
                <span class="topicFilter">
                    <!-- <?php if (yoast_get_primary_term_id('topic')) {
                        $primary_term_topic_id = yoast_get_primary_term_id('topic');
                        $postTopic = get_term( $primary_term_topic_id );
                    } else {
                        $terms = get_the_terms( $post->ID, 'topic' );
                        foreach($terms as $term) {
                            $postTopic = $term;
                        }
                    }?> -->

                    <!-- <?php if (yoast_get_primary_term_id('filter-types')) {
                        $primary_term_type_id = yoast_get_primary_term_id('filter-types');
                        $postType = get_term( $primary_term_type_id );
                    } else {
                        $termsType = get_the_terms( $post->ID, 'filter-types' );
                        foreach($termsType as $type) {
                            $postType = $type;
                        }
                    }?> -->

                    <!-- <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a> -->
                    <!-- <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a> -->
                    <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText">Cloud</a>
                    <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText">Research</a>
                </span>
                <span class="title"><?php the_title(); ?></span>
                <span class="author">by Matt Boon</span>
                <span class="dateReadTime"><span class="dateRead"><?php echo get_the_date('M j, Y'); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
            </div>
        </div>
    </div>
</section>
