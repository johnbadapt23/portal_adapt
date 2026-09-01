<section class="two-column two-column-image-slider-events">
    <div class="container">
        <div class="column-container">
            <div class="column one-half text-column">
                <h1 class="white-text"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h1>
                <p class="p-large white-text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
                <?php if ( have_rows( 'list' ) ) : ?>
                    <span class="list-container">
                        <?php while ( have_rows( 'list' ) ) : the_row(); ?>
                            <span class="list-item p-large red-tick-before"><?php echo esc_html( get_sub_field( 'list_item' ) ); ?></span>
                        <?php endwhile; ?>
                    </span>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'buttons' ) ) : ?>
                    <?php $buttonCounter = 1; ?>
                    <span class="button-container">                                                                                                                   
                        <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                            <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                                <a class="stdBtn std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo esc_url( get_sub_field( 'button_link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                            <?php } else if( get_sub_field( 'button_type' ) =='scroll-to') { ?>
                                <a class="scroll-to-button std-button <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo esc_attr( get_sub_field( 'scroll_to_id' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                            <?php } else { ?>
                                <span class="form-popup-button-container <?php if($buttonCounter == 1){ ?>red-button<?php } else { ?>red-outline-button<?php } ?>"><?php echo esc_html( get_sub_field( 'form_button' ) ); ?></span>
                            <?php } ?>                                                                                                                                                                                                                                                                                                                                
                            <?php $buttonCounter++; ?>
                        <?php endwhile; ?>
                    </span>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
            <div class="column one-half slider-column">
                <?php if ( have_rows( 'slider' ) ) : ?>
                    <?php $slideCounter = 1; ?>
                    <div class="customer-events-image-slider">
                        <?php while ( have_rows( 'slider' ) ) : the_row(); ?>
                            <div class="slide events-image-slide">
                                <span class="image-container">
                                    <span class="bg-container">
                                        <?php $image = get_sub_field( 'image' ); ?>
                                        <?php if ( $image ) { ?>
                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
                                        <?php } ?>
                                    </span>
                                </span>
                                <span class="bottom-text-container">
                                    <span class="label-XL white-text"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
                                    <span class="label-Xsmall"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                </span>  
                                <span class="progress">
                                    <span class="progress-inner"></span>
                                </span>                                                              
                            </div>
                            <?php $slideCounter++; ?>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
