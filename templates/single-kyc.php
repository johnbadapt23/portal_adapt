<!-- Logic for purchase -->
<?php $purchased = 'no'; ?>
<?php if(current_user_can('mepr_auth')) {?>
    <?php $purchased = 'yes'; ?>
<?php } else { ?> 
    <?php $purchased = 'no'; ?>
<?php } ?>
<?php if ( have_rows( 'introduction' ) ) : ?>
	<?php while ( have_rows( 'introduction' ) ) : the_row(); ?>
        <section class="two-column-services landing-video-intro kyc-video-introduction background-white">
            <div class="container">
                <div class="landing-video-intro-columns">
                    <div class="column one-half text-column">
                         <?php 
                            if(get_the_terms( $post->ID, 'kit-type' )){
                                $terms = get_the_terms( $post->ID, 'kit-type' );
                                foreach($terms as $term) {
                                    if ($term->parent === 0) {
                                        $postTopic = $term;
                                    }
                                }
                            }
                        ?> 
                        <a class="kit-back-button" href="<?php echo get_term_link($postTopic); ?>" target="_self">Back</a>
                        <div class="text-content-inner">
                            
                            <span class="subtitle text-red"><a href="<?php echo get_term_link($postTopic); ?>"><?php echo get_sub_field( 'sub_title' ); ?></a></span>              
                            <h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
                            <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                            <span class="links-container">
                                <!-- Logic for purchased vs non purchased -->
                                <?php if($purchased == 'yes'){ ?>
                                        <a class="scroll-to-button button red-button" href="#kycContent">Get Started</a>
                                <?php } else { ?>  
                                    <?php $vimeoCode = get_sub_field( 'vimeo_code' ); ?>
                                      <?php if ( have_rows( 'buttons' ) ) : ?>
                                        <?php $buttonCounter = 1; ?>
                                        <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                            <?php if(get_sub_field( 'button_type' ) == 'video-button') { ?>
                                                <a class="video-popup popup-vimeo video-link stdBtn red red-button" href="https://vimeo.com/<?php echo $vimeoCode ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                            <?php } elseif(get_sub_field( 'button_type' ) == 'link')  { ?>
                                                <a class="link stdBtn red-outline-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                                            <?php } else { ?>
                                                <span class="form-popup-button-container stdBtn <?php if($buttonCounter == 1){?>red red-button<?php } else { ?>red-outline-button<?php } ?>">
                                                    <a class="form-popup popup-modal" href="#talkform"><?php echo get_sub_field( 'form_button' ); ?></a>
                                                </span>
                                                <div class="formPopup talk-form mfp-hide" id="talkform">
                                                    <a class="popup-modal-dismiss"></a>                                                    
                                                    <div class="formWrapper register"><?php echo get_sub_field( 'form_code' ); ?></div>
                                                </div>
                                            <?php } ?>
                                            <?php $buttonCounter++; ?>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                <?php } ?>                              
                            </span>
                        </div>
                    </div>
                    <div class="column one-half video-column">
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
                </div>
            </div>
        </section>
	<?php endwhile; ?>
<?php else : ?>
	<?php // no rows found ?>
<?php endif; ?>

<section class="customer-kit-content" id="kycContent">
    <div class="container">
        <div class="chapters-container">
            <?php if ( have_rows( 'kit_content' ) ) : ?>  
                <div class="chapters-selector-container">
                    <?php $chapterCounter = 1; ?>
                    <?php while ( have_rows( 'kit_content' ) ) : the_row(); ?>
                        <span class="chapter-selector<?php if ($chapterCounter == 1){ ?> active<?php } ?>">
                            <span class="chapter-title"><?php echo get_sub_field( 'title' ); ?></span>
                        </span>
                        <?php $chapterCounter++; ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            <?php if ( have_rows( 'kit_content' ) ) : ?>   
                <div class="chapters-content-container"> 
                    <?php $contentCounter = 1; ?>
                    <?php while ( have_rows( 'kit_content' ) ) : the_row(); ?>  
                        <div class="chapter-content<?php if ($contentCounter == 1){ ?> active<?php } ?>"> 
                            <?php $poster_image = get_sub_field( 'image' ); ?>
                            <?php if ( $poster_image ) { ?>
                                <div class="top-section">
                                    <div class="image-container video-container">
                                        <span class="frame"></span>
                                        <div class="bg-container">
                                            <?php $poster_image = get_sub_field( 'image' ); ?>
                                            <?php if ( $poster_image ) { ?>
                                                <?php echo wp_get_attachment_image( $poster_image['ID'], 'full', false, array( 'alt' => $poster_image['alt'] ) ); ?>
                                            <?php } ?>
                                            <?php if($purchased == 'yes'){ ?>
                                                <?php if( get_sub_field( 'vimeo_code' )) { ?>
                                                    <span class="opacity-overlay"></span>
                                                    <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div> 
                            <?php } ?>  
                            <div class="bottom-section">  
                                <div class="content-switch-container">
                                    <?php if ( have_rows( 'overview' ) ) : ?>
                                        <?php while ( have_rows( 'overview' ) ) : the_row(); ?>
                                            <span class="overview-switch kyc-switch active">Overview</span>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                    <?php if($purchased == 'yes'){ ?>
                                        <?php if ( have_rows( 'resources' ) ) : ?>
                                            <?php while ( have_rows( 'resources' ) ) : the_row(); ?>
                                                <span class="resource-switch kyc-switch">Resources</span>
                                            <?php endwhile; ?>
                                         <?php endif; ?>
                                    <?php } else { ?> 
                                        <span class="resources-preview">Resources</span>
                                    <?php } ?>
                                </div>     
                                <div class="kyc-chapter-content-container">
                                    <?php if ( have_rows( 'overview' ) ) : ?>
                                        <div class="overview-content kyc-chapter-content active">
                                            <?php while ( have_rows( 'overview' ) ) : the_row(); ?>
                                                <span class="overview-title title text-black"><?php echo get_sub_field( 'title' ); ?></span>
                                                <?php if($purchased == 'yes'){ ?>
                                                    <span class="overview-content"><?php echo get_sub_field( 'overview_content' ); ?></span>
                                                <?php } else { ?> 
                                                    <span class="overview-excerpt"><?php echo get_sub_field( 'excerpt_text' ); ?></span>
                                                <?php } ?>                                                                                        
                                            <?php endwhile; ?>
                                        </div>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <?php if($purchased == 'yes'){ ?>
                                        <?php if ( have_rows( 'resources' ) ) : ?>
                                            <div class="resources-content kyc-chapter-content">
                                                <?php while ( have_rows( 'resources' ) ) : the_row(); ?>
                                                <span class="resources-title title text-black">Resources</span>
                                                    <?php if ( have_rows( 'resource' ) ) : ?>
                                                        <ul class="resources-container">
                                                            <?php while ( have_rows( 'resource' ) ) : the_row(); ?>
                                                                <li>
                                                                    <a class="resource-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_blank"><?php echo get_sub_field( 'title' ); ?></a>                                                                                                                        
                                                                </li>
                                                            <?php endwhile; ?>
                                                        </ul>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </div>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php } ?>
                                </div>                                                         
                            </div>
                        </div>
                        <?php $contentCounter++; ?>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>