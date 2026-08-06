<section class="benchmark-two-column">
    <div class="container">
        <div class="column-container">
            <div class="column link-column">
                <span class="labelXXsmall red-text"><?php echo get_sub_field( 'pre_title' ); ?></span>
                <h2 class="headerXsmall text-bold"><?php echo get_sub_field( 'title' ); ?></h2>
                <?php if ( have_rows( 'links' ) ) : ?>
                    <span class="link-container">
                        <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                            <a class="link" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>">
                                <span class="link-inner-container">
                                    <span class="icon-container">
                                        <?php $icon = get_sub_field( 'icon' ); ?>
                                        <?php if ( $icon ) { ?>
                                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="link-title text-regular medium-weight"><?php echo get_sub_field( 'title' ); ?></span>
                                    <span class="arrow-container">
                                    </span>
                                </span>
                            </a> 
                        <?php endwhile; ?>
                    </span> 
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'button' ) ) : ?>
                    <span class="button-container desktop">
                        <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                <a class="small-button std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>                        
                            <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                <a class="formPopupHubspot download-file-button with-icon small-button std-button red-button" href="#bechamrk_formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="bechamrk_formPopup">
                                        <div class="form-container"><?php echo get_sub_field( 'hubspot_embed_code' ); ?></div>
                                    </div>
                                </div> 
                            <?php } else { ?> 
                                <a class="formPopupHubspot small-button std-button red-button" href="#bechamrk_formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="bechamrk_formPopup">
                                        <div class="form-container"><?php echo get_sub_field( 'hubspot_embed_code' ); ?></div>
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
            <div class="column image-column">
                <?php $image = get_sub_field( 'image' ); ?>
                <?php if ( $image ) { ?>
                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                <?php } ?>
                <?php if ( have_rows( 'button' ) ) : ?>
                    <span class="button-container desktop">
                        <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                                <a class="small-button std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>                        
                            <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                <a class="formPopupHubspot download-file-button with-icon small-button std-button red-button" href="#bechamrk_formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="bechamrk_formPopup">
                                        <div class="form-container"><?php echo get_sub_field( 'hubspot_embed_code' ); ?></div>
                                    </div>
                                </div> 
                            <?php } else { ?> 
                                <a class="formPopupHubspot small-button std-button red-button" href="#bechamrk_formPopup"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <div style="display: none;">         
                                    <div class="preview-cta-form login-form-container" id="bechamrk_formPopup">
                                        <div class="form-container"><?php echo get_sub_field( 'hubspot_embed_code' ); ?></div>
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
    <div>
</section>


