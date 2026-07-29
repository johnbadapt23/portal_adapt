<nav class="mobileMenu">
		<div class="mobileMenuItems">
			<span class="menuTop">
                 <?php if ($membershipType == 'tnc' || $membership == 'kyc') { ?>
                    <ul>
                        <?php if ($membership == 'kyc') { ?>
                            <li>
                                <a href="/kyc/persona/">KYC Kits</a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="/tnc/">The Next Conversation</a>
                        </li>
                    </ul>
                <?php } else { ?> 
                    <ul>
                        <li>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                        </li>
                        <?php if ($membershipType == 'it-pro') { ?>
                            <li class="dropDown research with-sub-menu">
                                <a href="#">Research</a>
                                <ul class="sub-menu">
                                    <li class="parent">Research</li>
                                    <span class="sub-menu-inner">
                                        <?php if ( have_rows( 'topics_column_one', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'topics_column_one', 'option' ) ) : the_row(); ?>
                                                <div class="column full">
                                                    <?php if ( have_rows( 'group' ) ) : ?>
                                                        <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                            <span class="dropDownSection">
                                                                <?php $icon = get_sub_field( 'icon' ); ?>
                                                                <span class="columnTitle">
                                                                    <?php if ( $icon ) { ?>
                                                                        <img class="topic-icon" src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                                                    <?php } ?>
                                                                    <?php echo get_sub_field( 'title' ); ?>
                                                                </span>
                                                                <?php if ( have_rows( 'link' ) ) : ?>
                                                                    <ul>
                                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                            <?php $topic_link_term = get_sub_field( 'topic_link' ); ?>
                                                                            <?php if ( $topic_link_term ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_term_link($topic_link_term); ?>"><?php echo $topic_link_term->name; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php endwhile; ?>
                                                                    </ul>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?>
                                                            </span>

                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                        <?php if ( have_rows( 'topics_column_two', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'topics_column_two', 'option' ) ) : the_row(); ?>
                                                <div class="column full">
                                                    <?php if ( have_rows( 'group' ) ) : ?>
                                                        <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                            <span class="dropDownSection">
                                                                <?php $icon = get_sub_field( 'icon' ); ?>
                                                                <span class="columnTitle">                                                                    
                                                                    <?php echo get_sub_field( 'title' ); ?>
                                                                </span>
                                                                <?php if ( have_rows( 'link' ) ) : ?>
                                                                    <ul>
                                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                            <?php $icon = get_sub_field( 'icon' ); ?>						
                                                                            <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                                <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                                <?php if ( $type_link_term ): ?>                                                                                    
                                                                                    <li>
                                                                                        <a href="<?php echo get_term_link($type_link_term); ?>" >
                                                                                            <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $type_link_term->name; ?></span>
                                                                                        </a>
                                                                                    </li>                                                                                                                                                                    
                                                                                <?php endif; ?>
                                                                            <?php } else { ?>
                                                                                <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                                <?php if ( $other_link ): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_sub_field( 'other_link' ); ?>" >
                                                                                                <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $other_link; ?></span>
                                                                                        </a>
                                                                                    </li>
                                                                                <?php endif; ?>
                                                                            <?php } ?>
                                                                        <?php endwhile; ?>
                                                                    </ul>
                                                                <?php else : ?>
                                                                    <?php // no rows found ?>
                                                                <?php endif; ?>
                                                            <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                        <?php if ( have_rows( 'topics_column_three', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'topics_column_three', 'option' ) ) : the_row(); ?>
                                                <div class="column full">
                                                    <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                                    <?php if ( $post_object ): ?>
                                                        <?php $post = $post_object; ?>
                                                        <?php setup_postdata( $post ); ?> 
                                                        <a href="<?php the_permalink(); ?>">
                                                            <span class="menu-featured-post">
                                                                <span class="image-container">
                                                                    <?php
                                                                    $image = null;
                                                                    $video = 'no';
                                                                    if ( has_term('replay-post', 'replay') ) { 
                                                                        if(get_field('video_image')) {
                                                                            $image = get_field('video_image');
                                                                            $video = 'yes';
                                                                        } else {
                                                                            $image = get_field('featured_image');
                                                                        } 
                                                                    } else {
                                                                        if (get_field('listing_image')) {
                                                                            $image = get_field('listing_image');                        
                                                                        } else {
                                                                            if (get_field('featured_image_or_video') === 'video') {
                                                                                $video = 'yes';
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                } else {
                                                                                    $image = get_field('featured_image');
                                                                                }                                                      
                                                                            } else {
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                
                                                                                } else {
                                                                                    $image = get_field('featured_image');                                                                            
                                                                                }  
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                    ?>
                                                                    <?php if ($image) : ?>
                                                                        <span class="bg-container">
                                                                            <img class="article-image" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>">
                                                                            <?php if($video == 'yes'){ ?>
                                                                                <span class="video-icon"></span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <span class="text-container">
                                                                    <span class="labelSmall text-bold text-black"><?php the_title(); ?></span>
                                                                    <span class="link-container">
                                                                        <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </a>                                                            
                                                        <?php wp_reset_postdata(); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </span>
                                </ul>
                            </li>
                            <li class="dropdown with-sub-menu">
                                <a href="#">Community Insights</a>
                                <ul class="sub-menu">
                                    <li class="parent">Community Insights</li>
                                    <span class="sub-menu-inner">
                                        <?php if ( have_rows( 'community_insights_menu', 'option' ) ) : ?>
                                            <ul>
                                                <?php while ( have_rows( 'community_insights_menu', 'option' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                            <li>
                                                                <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                    <?php $icon = get_sub_field( 'icon' ); ?>
                                                                    <span class="icon-container">
                                                                        <?php if ( $icon ) { ?>
                                                                            <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text">
                                                                        <span class="link-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                                        <span class="link-text-text"><?php echo get_sub_field( 'text' ); ?></span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </span>
                                </ul>
                            </li>
                             <li class="dropdown with-sub-menu">
                                <a href="#">Marketplace</a>
                                <ul class="sub-menu">
                                    <li class="parent">Marketplace</li>
                                    <span class="sub-menu-inner">
                                        <?php if ( have_rows( 'marketplace_menu', 'option' ) ) : ?>
                                            <ul>
                                                <?php while ( have_rows( 'marketplace_menu', 'option' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                            <li>
                                                                <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                    <?php $icon = get_sub_field( 'icon' ); ?>
                                                                    <span class="icon-container">
                                                                        <?php if ( $icon ) { ?>
                                                                            <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text">
                                                                        <span class="link-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                                        <span class="link-text-text"><?php echo get_sub_field( 'text' ); ?></span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </span>
                                </ul>
                            </li>
                            <li>
                                <a href="/ecosystem-partners/executive-advisors/" target="_self">Executive Advisors</a>
                            </li>                            
                            <li class="dropdown with-sub-menu">
                                <a href="#">Events</a>
                                <ul class="sub-menu">
                                    <li class="parent">Events</li>
                                    <span class="sub-menu-inner">
                                        <?php if ( have_rows( 'events_menu_it', 'option' ) ) : ?>
                                            <ul>
                                                <?php while ( have_rows( 'events_menu_it', 'option' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                            <li>
                                                                <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                    <?php $icon = get_sub_field( 'icon' ); ?>
                                                                    <span class="icon-container">
                                                                        <?php if ( $icon ) { ?>
                                                                            <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text">
                                                                        <?php echo get_sub_field( 'link_text' ); ?>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                    <?php if ( have_rows( 'events_calendar' ) ) : ?>
                                                        <span class="events-calendar">                                                            
                                                            <?php while ( have_rows( 'events_calendar' ) ) : the_row(); ?>
                                                                <span class="events-calendar-container">
                                                                    <span class="image-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text-container">
                                                                        <span class="link-title"><?php echo get_sub_field( 'text' ); ?></span>
                                                                        <span class="link-container">
                                                                            <?php if(get_sub_field( 'link_type' ) == 'link'){ ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">Download</a>
                                                                            <?php } else { ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link formPopupHubspot" href="#downloadCalendarLink" target="_self">Download</a>
                                                                                    <span style="display: none;">         
                                                                                        <span class="preview-cta-form login-form-container" id="downloadCalendarLink">
                                                                                            <span class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                            <?php } ?>                                                                                
                                                                        </span> 
                                                                    </span>                                                                   
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </span>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($membershipType == 'advantage') { ?>
                            <li>
                                <a href="/market-narratives/">Market Narratives</a>
                            </li> 
                            <li>
                                <a href="/sector-outlooks/" target="_self">Sector Outlooks</a>
                            </li>
                            <li>
                                <a href="/persona-profiles/" target="_self">Persona Profiles</a>
                            </li>   
                             <li>
                                <a href="/community/">Community</a>
                            </li>                                              
                        <?php } else { ?>
                            <li>
                                <a href="/community/">Community</a>
                            </li>
                        <?php } ?>
                        <li class="dropDown events with-sub-menu">
                            <a href="#">Events</a>
                            <?php if ( have_rows( 'events_menu', 'option' ) ) : ?>
                                <ul class="sub-menu">
                                    <?php while ( have_rows( 'events_menu', 'option' ) ) : the_row(); ?>
                                        <li class="parent">Events</li>
                                        <?php if ( have_rows( 'link' ) ) : ?>
                                            <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                <li>
                                                    <a href="<?php echo get_sub_field( 'link' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                </li>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </ul>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </li>
                        <li>
                            <a href="/whats-new/">What's New</a>
                        </li>
                    </ul>
                <?php } ?>
			</span>
		</div>
	</nav>