<section class="form-popup-slider-module background-white">
    <div class="container">
        <div class="title-container">
            <h2 class="bold-red"><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <?php if ( have_rows( 'slide' ) ) : ?>
            <?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                <?php if(get_sub_field( 'form_embed_code' )){ ?> 
                    <span class="form-embed" style="display: none;">                                
                        <?php echo get_sub_field( 'form_embed_code' ); ?>
                    </span>
                <?php } ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        <div class="form-popup-slider-container">
            <div class="form-popup-slider">
                <?php if ( have_rows( 'slide' ) ) : ?>
                    <?php while ( have_rows( 'slide' ) ) : the_row(); ?>
                        <div class="slide">
                            <span class="form-embed" style="display: none;">                                
                                <?php echo get_sub_field( 'form_embed_code' ); ?>
                            </span>
                            <?php if(get_sub_field( 'form_data_id' )){ ?> 
                                <?php $dataForm = get_sub_field( 'form_data_id' ); ?>
                            <?php } ?>                              
                            <?php if(get_sub_field( 'form_button_code' )){ ?> 
                                <?php echo get_sub_field( 'form_button_code' ); ?>
                            <?php } ?>                                                     
                            <span class="form-popup-slide" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>>
                                <span class="logo-arrow-container" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>>
                                    <span class="logo-container" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>>
                                         <?php $title_logo = get_sub_field( 'title_logo' ); ?>
                                        <?php if ( $title_logo ) { ?>
                                            <?php echo wp_get_attachment_image( $title_logo['ID'], 'full', false, array( 'alt' => $title_logo['alt'] ) ); ?>
                                        <?php } ?>
                                    </span>
                                    <span class="arrow-container" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>></span>
                                </span>
                                <span class="slide-image-container">
                                    <span class="bg-container-outer" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>>
                                        <span class="slide-bg-container">
                                            <?php $image_one = get_sub_field( 'image_one' ); ?>
                                            <?php if ( $image_one ) {
                                                $image_one_attrs = array( 'alt' => $image_one['alt'] );
                                                if ( $dataForm ) { $image_one_attrs['data-fc-open'] = $dataForm; }
                                                echo wp_get_attachment_image( $image_one['ID'], 'full', false, $image_one_attrs );
                                            } ?>
                                        </span>
                                        <span class="slide-bg-container">
                                            <?php $image_two = get_sub_field( 'image_two' ); ?>
                                            <?php if ( $image_two ) {
                                                $image_two_attrs = array( 'alt' => $image_two['alt'] );
                                                if ( $dataForm ) { $image_two_attrs['data-fc-open'] = $dataForm; }
                                                echo wp_get_attachment_image( $image_two['ID'], 'full', false, $image_two_attrs );
                                            } ?>
                                        </span>
                                        <span class="slide-bg-container">
                                            <?php $image_three = get_sub_field( 'image_three' ); ?>
                                            <?php if ( $image_three ) {
                                                $image_three_attrs = array( 'alt' => $image_three['alt'] );
                                                if ( $dataForm ) { $image_three_attrs['data-fc-open'] = $dataForm; }
                                                echo wp_get_attachment_image( $image_three['ID'], 'full', false, $image_three_attrs );
                                            } ?>
                                        </span>
                                    </span>
                                </span>                                                            
                            </span>
                            <?php if(get_sub_field( 'form_button_code' )){ ?> 
                                </a>
                            <?php } ?>                            
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
            <span class="progress-bar-outer"><span class="progress-bar-form-popup"></span></span>
        </div>
    </div>
</section>