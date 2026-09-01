<?php if(get_sub_field( 'background_colour' ) == 'background-true-black'){ ?>
    <?php $textColour = 'text-white'; ?>
<?php } else { ?>
    <?php $textColour = 'text-black'; ?>
<?php }?>
<?php 
    $max_width = get_sub_field('heading_max_width');
    $heading_style = get_sub_field('heading_size');
    $id = get_sub_field('id');
?>

<section class="centered-text-links advisors-centered-text-links <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
    <div class="container">
        <div style="max-width: <?php echo esc_attr( $max_width ); ?>;" class="text-container">
            <?php if ( get_sub_field('title') ) { ?>
                <h2 class="<?php if(get_sub_field( 'background_colour' ) == 'background-true-black'){ ?><?php echo esc_attr( $heading_style );?> <?php } else { ?><?php echo esc_attr( $heading_style );?> <?php } ?><?php echo esc_attr( $textColour ); ?>"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
            <?php } ?>
            <?php if ( get_sub_field('text') ) { ?>
                <span class="text <?php echo esc_attr( $textColour ); ?>"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
            <?php } ?>
            <span class="links-container">
                <?php if ( have_rows( 'links' ) ) : ?>
                    <?php $buttonCounter = 1;?>
                    <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                        <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                            <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                            <?php $file = get_sub_field( 'file' ); ?>
                            <a class="download-file-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) =='download-form') { ?>
                            <a class="formPopupHubspot download-file-button with-icon stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo esc_attr( $id ); ?>_formPopup<?php echo esc_attr( $buttonCounter ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                            <div style="display: none;">         
                                <div class="preview-cta-form login-form-container" id="<?php echo esc_attr( $id ); ?>_formPopup<?php echo esc_attr( $buttonCounter ); ?>">
                                    <div class="form-container"><?php echo get_sub_field( 'form_code' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot form-embed markup requires raw HTML/script output; wp_kses_post() would strip the tags the embed needs to function. ?></div>
                                </div>
                            </div> 
                        <?php } else { ?> 
                            <a class="formPopupHubspot stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo esc_attr( $id ); ?>_formPopup<?php echo esc_attr( $buttonCounter ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                            <div style="display: none;">         
                                <div class="preview-cta-form login-form-container" id="<?php echo esc_attr( $id ); ?>_formPopup<?php echo esc_attr( $buttonCounter ); ?>">
                                    <div class="form-container"><?php echo get_sub_field( 'form_code' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot form-embed markup requires raw HTML/script output; wp_kses_post() would strip the tags the embed needs to function. ?></div>
                                </div>
                            </div> 
                        <?php } ?>                     	
                        <?php $buttonCounter++; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>                
            </span>
        </div>
    </div>
</section>