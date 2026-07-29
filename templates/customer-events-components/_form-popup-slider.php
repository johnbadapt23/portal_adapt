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
                                            <img src="<?php echo $title_logo['url']; ?>" alt="<?php echo $title_logo['alt']; ?>" />
                                        <?php } ?>
                                    </span>
                                    <span class="arrow-container" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>></span>
                                </span>
                                <span class="slide-image-container">
                                    <span class="bg-container-outer" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>>
                                        <span class="slide-bg-container">
                                            <?php $image_one = get_sub_field( 'image_one' ); ?>
                                            <?php if ( $image_one ) { ?>
                                                <img src="<?php echo $image_one['url']; ?>" alt="<?php echo $image_one['alt']; ?>" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>/>
                                            <?php } ?>
                                        </span>
                                        <span class="slide-bg-container">
                                            <?php $image_two = get_sub_field( 'image_two' ); ?>
                                            <?php if ( $image_two ) { ?>
                                                <img src="<?php echo $image_two['url']; ?>" alt="<?php echo $image_two['alt']; ?>" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>/>
                                            <?php } ?>
                                        </span>
                                        <span class="slide-bg-container">
                                            <?php $image_three = get_sub_field( 'image_three' ); ?>
                                            <?php if ( $image_three ) { ?>
                                                <img src="<?php echo $image_three['url']; ?>" alt="<?php echo $image_three['alt']; ?>" <?php if($dataForm){ ?> data-fc-open="<?php echo $dataForm; ?>"<?php } ?>/>
                                            <?php } ?>   
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