<?php $partnerType = get_field( 'partner_type' ); ?>
<?php echo $partnerType; ?>
<?php if ( have_rows( 'introduction' ) ) : ?>
	<?php while ( have_rows( 'introduction' ) ) : the_row(); ?>
        <section class="two-column-services landing-video-intro kyc-video-introduction partners-introduction background-white">
            <div class="container">
                <div class="landing-video-intro-columns">
                    <div class="column one-half text-column">
                        <div class="text-content-inner">
                            <a class="kit-back-button" href="/ecosystem-partners/search/" target="_self">Back</a>   
                            <span class="subtitle text-red"><?php echo get_sub_field( 'sub_title' ); ?></span>              
                            <h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
                            <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                            <span class="links-container">
                                <?php $title = get_sub_field( 'title' ); ?>
                                <?php if ( have_rows( 'buttons' ) ) : ?>
                                <?php $buttonCounter = 1; ?>
                                    <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                        <?php if(get_sub_field( 'button_type' ) == 'scroll-to') { ?>
                                            <a class="scroll-to-button stdBtn <?php if ($buttonCounter == 1){ ?>red red-button<?php } else { ?>red-outline-button<?php } ?>" href="#partnerContent"><?php echo get_sub_field( 'button_text' ); ?></a>
                                        <?php } else if(get_sub_field( 'button_type' ) == 'link')  { ?>
                                            <a class="link stdBtn <?php if ($buttonCounter == 1){ ?>red red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                        <?php } else { ?> 
                                            <a class="formPopupPartners stdBtn <?php if ($buttonCounter == 1){ ?>red red-button<?php } else { ?>red-outline-button<?php } ?>" href="#formPopup<?php echo$buttonCounter; ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                            <span style="display: none;">
                                                <span class="form-popup" id="formPopup<?php echo$buttonCounter; ?>">
                                                    <span class="popup-form-container">
                                                        <span class="popup-form-title">Request an Introduction with <?php echo $title; ?></span>
                                                            <?php echo get_sub_field( 'form_embed_code' ); ?>
                                                        </span>
                                                </span>
                                            </span>
                                        <?php } ?>
                                        <?php $buttonCounter++; ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>                                                         
                            </span>
                        </div>
                        
                    </div>
                    <div class="column one-half video-column">
                        <?php if ($partnerType != 'advisor'){ ?> 
                            <div class="image-container video-container">                            
                                <div class="bg-container">
                                    <?php $poster_image = get_sub_field( 'poster_image' ); ?>
                                    <?php if ( $poster_image ) { ?>
                                        <?php echo wp_get_attachment_image( $poster_image['ID'], 'full', false, array( 'alt' => $poster_image['alt'] ) ); ?>
                                    <?php } ?>                                                                
                                    <?php if( get_sub_field( 'vimeo_code' )) { ?>
                                        <span class="opacity-overlay"></span>
                                        <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                                    <?php } ?>                                
                                </div>
                            </div> 
                        <?php } else { ?> 
                            <div class="headshot-container">
                                <div class="image-container">
                                    <div class="bg-container">
                                        <?php $head_shot = get_sub_field( 'head_shot' ); ?>
                                        <?php if ( $head_shot ) { ?>
                                            <?php echo wp_get_attachment_image( $head_shot['ID'], 'full', false, array( 'alt' => $head_shot['alt'] ) ); ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>                            
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
	<?php endwhile; ?>   
<?php else : ?>
	<?php // no rows found ?>
<?php endif; ?>

<section class="partners-content-module" id="partnerContent">
    <div class="sticky-menu">
        <div class="container">
            <div class="menu-item-container">
                <?php $switchCounter = 1; ?>
                 <?php if ($partnerType != 'advisor'){ ?> 
                    <?php if ( have_rows( 'about_company' ) ) : ?>
                        <?php while ( have_rows( 'about_company' ) ) : the_row(); ?>
                            <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#aboutCompany">About</a>
                            <?php $switchCounter++; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                     <?php if ( have_rows( 'who_we_help' ) ) : ?>
                        <?php while ( have_rows( 'who_we_help' ) ) : the_row(); ?>
                            <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#whoWeHelp">How They Help</a>
                            <?php $switchCounter++; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                <?php } else { ?> 
                    <?php if ( have_rows( 'about_advisor' ) ) : ?>
                        <?php while ( have_rows( 'about_advisor' ) ) : the_row(); ?>
                            <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#aboutAdvisor">About</a>
                            <?php $switchCounter++; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                <?php } ?>
                <?php if ( have_rows( 'capabilities' ) ) : ?>
                    <?php while ( have_rows( 'capabilities' ) ) : the_row(); ?>
                        <?php if ($partnerType != 'advisor'){ ?> 
                            <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#capabilities">Capabilities</a>
                        <?php } else { ?> 
                            <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#capabilities">Expertise</a>
                        <?php } ?>
                            <?php $switchCounter++; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'testimonials' ) ) : ?>
                    <?php while ( have_rows( 'testimonials' ) ) : the_row(); ?>
                        <a class="partners-content-switch-trigger-testimonials partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#testimonials">Testimonials</a>
                        <?php $switchCounter++; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                 <?php if ($partnerType != 'advisor'){ ?> 
                    <?php if ( have_rows( 'team' ) ) : ?>
                        <?php while ( have_rows( 'team' ) ) : the_row(); ?>
                            <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#teams">Team</a>
                            <?php $switchCounter++; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php $listing_icon = get_field( 'listing_icon' ); ?>
    <div class="partners-content-container">
        <div class="container">
            <div class="partners-switch-content">
                 <?php $contentCounter = 1; ?>
                <?php if ($partnerType != 'advisor'){ ?> 
                    <?php if ( have_rows( 'about_company' ) ) : ?>
                        <div class="switch-content about about-company<?php if ($contentCounter == 1){?> active<?php } ?>" id="aboutCompany">
                            <?php while ( have_rows( 'about_company' ) ) : the_row(); ?>
                                <div class="inner-content-column-container">
                                    <div class="column main-column">
                                        <?php if ( $listing_icon ) { ?>
                                            <span class="logo-container">
                                                <?php echo wp_get_attachment_image( $listing_icon['ID'], 'full', false, array( 'alt' => $listing_icon['alt'] ) ); ?>
                                            </span>
                                        <?php } ?>
                                        <span class="title text-black overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                                        <span class="text"><?php echo get_sub_field( 'descriptions' ); ?></span>
                                    </div>
                                    <div class="column sidebar-column">
                                        <?php if ( have_rows( 'quick_facts' ) ) : ?>
                                            <?php while ( have_rows( 'quick_facts' ) ) : the_row(); ?>
                                                <div class="quick-facts">
                                                    <span class="title text-black overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                    <span class="fact-container">
                                                        <?php if ( have_rows( 'fact' ) ) : ?>
                                                            <?php while ( have_rows( 'fact' ) ) : the_row(); ?>
                                                                <span class="fact">
                                                                    <span class="fact-title"><?php echo get_sub_field( 'fact_title' ); ?></span>
                                                                    <span class="fact-text title overview-title"><?php echo get_sub_field( 'fact' ); ?></span>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <?php $contentCounter++; ?>
                        </div>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                    <?php if ( have_rows( 'who_we_help' ) ) : ?>
                        <div class="switch-content who-we-help<?php if ($contentCounter == 1){?> active<?php } ?>" id="whoWeHelp">
                            <?php while ( have_rows( 'who_we_help' ) ) : the_row(); ?>
                                <div class="inner-content-column-container">
                                    <div class="column main-column">
                                        <span class="title text-black overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                                        <span class="text-container">
                                            <div class="textBlock">
                                                <span class="partners-help-excerpt text registration-excerpt">
                                                    <p><?php echo get_sub_field( 'excerpt' ); ?></p>
                                                     <span class="partners-excerpt-readmore small-text-grey read-more">+ Read More</span>
                                                </span>                                               
                                                <span class="partners-help-details text full-text">
                                                    <?php echo get_sub_field( 'full_description' ); ?>	
                                                     <span class="partners-excerpt-less small-text-grey read-less">- Read Less</span>										
                                                </span>
                                            </div>
                                        </span>
                                        <?php if ( have_rows( 'regions' ) ) : ?>
                                            <div class="region-container">
                                                <?php while ( have_rows( 'regions' ) ) : the_row(); ?>
                                                    <span class="title text-black overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                    <?php if ( have_rows( 'region' ) ) : ?>
                                                        <span class="region-tag-container">
                                                            <?php while ( have_rows( 'region' ) ) : the_row(); ?>
                                                                <span class="tag"><?php echo get_sub_field( 'region_name' ); ?></span>
                                                            <?php endwhile; ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </div>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="column sidebar-column">
                                        <?php if ( have_rows( 'side_bar' ) ) : ?>
                                            <?php while ( have_rows( 'side_bar' ) ) : the_row(); ?>
                                                <?php if ( have_rows( 'information_block' ) ) : ?>
                                                    <?php while ( have_rows( 'information_block' ) ) : the_row(); ?>
                                                        <div class="information-block">
                                                            <span class="title overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                            <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                                        </div>                                                                                                                
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
                            <?php endwhile; ?>
                            <?php $contentCounter++; ?>
                        </div>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                <?php } else { ?>
                    <?php if ( have_rows( 'about_advisor' ) ) : ?>
                        <div class="switch-content about about-advisor<?php if ($contentCounter == 1){?> active<?php } ?>" id="aboutAdvisor">
                            <?php while ( have_rows( 'about_advisor' ) ) : the_row(); ?>
                                <span class="title overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                                <?php if( get_sub_field( 'vimeo_code' )) { ?>
                                    <div class="advisor-video">
                                        <div class="image-container video-container">                            
                                            <div class="bg-container">
                                                <?php $poster_image = get_sub_field( 'poster_image' ); ?>
                                                <?php if ( $poster_image ) { ?>
                                                    <?php echo wp_get_attachment_image( $poster_image['ID'], 'full', false, array( 'alt' => $poster_image['alt'] ) ); ?>
                                                <?php } ?>                                                                
                                                <?php if( get_sub_field( 'vimeo_code' )) { ?>
                                                    <span class="opacity-overlay"></span>
                                                    <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                                                <?php } ?>                                
                                            </div>
                                        </div> 
                                    </div>
                                <?php } ?> 
                                <div class="text description-container">
                                    <?php echo get_sub_field( 'descriptions' ); ?>
                                </div>
                                <?php $contentCounter++; ?>
                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                <?php } ?>
                <?php if ( have_rows( 'capabilities' ) ) : ?>
                    <div class="switch-content capabilities<?php if ($contentCounter == 1){?> active<?php } ?>" id="capabilities">
                        <?php while ( have_rows( 'capabilities' ) ) : the_row(); ?>
                        <div class="inner-content-column-container">
                            <div class="column main-column">
                                <span class="title text-black overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                                <?php if ( have_rows( 'capabilities_content' ) ): ?>
                                    <?php while ( have_rows( 'capabilities_content' ) ) : the_row(); ?>
                                        <?php if ( get_row_layout() == 'text_module' ) : ?>
                                            <div class="text-block"<?php if(get_sub_field( 'id' )){ ?> id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
                                                <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                            </div>                                            
                                        <?php elseif ( get_row_layout() == 'image__video_module' ) : ?>
                                            <div class="image-video-block"<?php if(get_sub_field( 'id' )){ ?> id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
                                                <span class="image-video-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                    <?php } ?>
                                                    <?php if( get_sub_field( 'vimeo_code' )) { ?>
                                                        <span class="opacity-overlay"></span>
                                                        <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                                                    <?php } ?>      
                                                </span>                                                
                                            </div>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <?php // no layouts found ?>
                                <?php endif; ?>
                            </div>
                            <div class="column sidebar-column">
                                <?php if ( have_rows( 'capabilities_list' ) ) : ?>
                                    <?php while ( have_rows( 'capabilities_list' ) ) : the_row(); ?>
                                       <span class="title text-black overview-title"> <?php echo get_sub_field( 'title' ); ?></span>
                                        <?php if ( have_rows( 'capability_item' ) ) : ?>
                                            <span class="capability-listing-container">
                                                <?php while ( have_rows( 'capability_item' ) ) : the_row(); ?>
                                                    <?php $capability_term = get_sub_field( 'capability' ); ?>
                                                    <?php if(get_sub_field( 'scroll_to_id' )){ ?> 
                                                        <a class="scroll-to-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>">
                                                    <?php } ?>
                                                    <?php if ( $capability_term ): ?>
                                                        <span class="tag"><?php echo $capability_term->name; ?></span>
                                                    <?php endif; ?>   
                                                    <?php if(get_sub_field( 'scroll_to_id' )){ ?> 
                                                        </a>
                                                    <?php } ?>                                                 
                                                <?php endwhile; ?>
                                            </span>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php $contentCounter++; ?>
                    </div>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'testimonials' ) ) : ?>
                    <div class="switch-content testimonials<?php if ($contentCounter == 1){?> active<?php } ?>" id="testimonials">
                        <?php while ( have_rows( 'testimonials' ) ) : the_row(); ?>
                            <span class="title text-black overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                            <?php if ( have_rows( 'testimonial_content' ) ): ?>
                                <?php while ( have_rows( 'testimonial_content' ) ) : the_row(); ?>                                    
                                    <?php if ( get_row_layout() == 'testimonials_slider' ) : ?>
                                        <div class="quote-slider-module">
                                            <?php if ( have_rows( 'slide' ) ) : ?>
                                                <?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                                                    <div class="quote-slide">
                                                        <div class="quote-slider-inner">
                                                            <h4 class="quote text-black"><?php echo get_sub_field( 'quote' ); ?></h4>
                                                            <span class="quote-title text-black"><?php echo get_sub_field( 'quoter' ); ?></span>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        </div>                                       
                                    <?php elseif ( get_row_layout() == 'column_testimonials' ) : ?>
                                        <div class="column-testimonial">     
                                            <?php if (get_sub_field( 'title' )) { ?> 
                                                <span class="title text-black overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                                            <?php } ?>                                            
                                            <?php $testimonialType = get_sub_field( 'testimonial_type' ); ?>                                       
                                            <div class="testimonial-column-container column-container <?php echo get_sub_field( 'testimonial_amount' ); ?> <?php echo $testimonialType; ?>">
                                                <?php if ( have_rows( 'testimonial' ) ) : ?>
                                                    <?php while ( have_rows( 'testimonial' ) ) : the_row(); ?>
                                                        <div class="column testimonial-column">
                                                            <?php if ($testimonialType == 'video'){ ?>                                                                
                                                                <span class="image-video-container">
                                                                    <span class="image-container">
                                                                        <span class="bg-container">
                                                                            <?php $image = get_sub_field( 'image' ); ?>
                                                                            <?php if ( $image ) { ?>
                                                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                         <?php if( get_sub_field( 'vimeo_id' )) { ?>
                                                                            <span class="opacity-overlay"></span>
                                                                            <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_id'); ?>"></a>
                                                                        <?php } ?>    
                                                                    </span>
                                                                     
                                                                </span>  
                                                                <span class="video-details">
                                                                    <span class="video-title"><?php echo get_sub_field( 'video_title' ); ?></span>
                                                                    <span class="video-excerpt"><?php echo get_sub_field( 'video_excerpt' ); ?></span>
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="testimonial-quote"><?php echo get_sub_field( 'quote' ); ?></span>
                                                            <?php } ?>                                                                                                                                                                                
                                                        </div>                                                        
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>                                                                                                                                                                             
                                        </div> 
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <?php // no layouts found ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                        <?php $contentCounter++; ?>
                    </div>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ($partnerType != 'advisor'){ ?> 
                    <?php if ( have_rows( 'team' ) ) : ?>
                        <div class="switch-content teams<?php if ($contentCounter == 1){?> active<?php } ?>" id="teams">
                            <?php while ( have_rows( 'team' ) ) : the_row(); ?>
                                <span class="title text-black overview-title"><?php echo get_sub_field( 'title' ); ?></span>
                                <?php if (get_sub_field( 'sub_title' )){ ?> 
                                    <span class="sub-title"><?php echo get_sub_field( 'sub_title' ); ?></span>
                                <?php } ?>                                
                                <?php $post_objects = get_sub_field( 'team_members' ); ?>
                               <?php if ( $post_objects ): ?>
                                    <div class="team-member-container">
                                        <?php $counter = 1; ?>
                                        <?php 
                                        // Ensure it's always an array
                                        if ($post_objects && !is_array($post_objects)) {
                                            $post_objects = array($post_objects);
                                        }
                                        ?>

                                        <?php foreach ($post_objects as $post): ?>
                                            <?php setup_postdata($post); ?>
                                            
                                            <span class="speaker one-quarter">
                                                <a class="speaker-popup" href="#speakerPopup-<?php echo $counter; ?>">                                                
                                                    <span class="image-container">
                                                        <?php $speaker_image = get_field('speaker_image'); ?>
                                                        <span class="bg-container<?php if (!$speaker_image) { ?> no-background<?php } ?>">
                                                            <?php if ($speaker_image) { ?>
                                                                <img src="<?php echo $speaker_image; ?>"/>                                                                                                                          
                                                            <?php } ?>
                                                        </span>
                                                        <span class="border-offset"></span>                                                                                                               
                                                    </span>
                                                    <span class="title-container"><?php the_title(); ?></span>
                                                    <span class="job-title"><?php echo get_field('speaker_description'); ?></span>                                            
                                                </a>
                                            </span>

                                            <!-- Inline popup content: use .mfp-hide, no inline display:none wrapper -->
                                            <div class="speaker-popup-container mfp-hide" id="speakerPopup-<?php echo $counter; ?>">
                                                <div class="column white-bg image-column">
                                                    <?php $speaker_image = get_field('speaker_image'); ?>
                                                    <span class="image-container">
                                                        <span class="bg-container">
                                                            <?php if ($speaker_image) { ?>
                                                                <img src="<?php echo $speaker_image; ?>" alt="<?php the_title(); ?>" />
                                                            <?php } ?>
                                                        </span>
                                                        <span class="border-offset"></span>
                                                    </span>
                                                    <h3 class="title">
                                                        <?php the_title(); ?>
                                                        <?php if (get_field('linkedin')) { ?>
                                                            <a class="linkedin-link" href="<?php echo get_field('linkedin');?>" target="_blank">
                                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/round-linkedin.svg" width="20" />
                                                            </a>
                                                        <?php } ?>
                                                    </h3>
                                                    <p class="job-title"><?php echo get_field('speaker_description'); ?></p>
                                                    <?php $company_logo = get_field('company_logo'); ?>
                                                    <?php if ($company_logo) { ?>
                                                        <span class="company-logo">
                                                            <img src="<?php echo $company_logo; ?>" />
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                                <div class="column dark-bg about-column">                                                        
                                                    <div class="about">
                                                        <span class="about-title">About</span>
                                                        <!-- changed from id="aboutContainer" to a class -->
                                                        <span class="about-text text-white"><?php echo get_field('speaker_details'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php $counter++; ?>
                                        <?php endforeach; ?>
                                        <?php wp_reset_postdata(); ?>
                                    </div>
                                <?php endif; ?>
                            <?php endwhile; ?>
                            <?php $contentCounter++; ?>
                        </div>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>