<?php
// _download-block-three-columns.php is a near-identical component that only
// differs in the column-count class and popup id prefix used below - rather
// than maintain two full copies of this markup, it sets $download_variant
// and includes this file directly.
$download_variant = $download_variant ?? 'two';
$popup_id_prefix = $download_variant === 'three' ? 'Triple' : 'Double';
?>
<section class="download-block" <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?>>
    <div class="container">
        <?php if(get_sub_field( 'block_title' )){ ?>
            <span class="download-block-title"><?php echo get_sub_field( 'block_title' ); ?></span>
        <?php } ?>
        <?php if ( have_rows( 'download_columns' ) ) : ?>
            <?php $counter = 0; ?>
				<?php while ( have_rows( 'download_columns' ) ) : the_row(); ?>
                    <div class="column <?php echo $download_variant; ?>-column <?php echo $counter; ?>">
                        <a class="download-popup-button-multi" href="#downloadPopup<?php echo $popup_id_prefix; ?><?php echo $counter; ?>">
                            <span class="download-image-container">
                                <span class="image" style="background-image: url(<?php echo get_sub_field( 'listing_image' ); ?>);"></span>
                            </span>
                        </a>
                        <?php if(get_sub_field( 'listing_title' )){ ?>
                            <span class="listing-title"><?php echo get_sub_field( 'listing_title' ); ?></span>
                        <?php } ?>
                        <?php if(get_sub_field( 'listing_text' )){ ?>
                            <span class="listing-details"><?php echo get_sub_field( 'listing_text' ); ?></span>
                        <?php } ?>
                        <a class="download-popup-button-multi" href="#downloadPopup<?php echo $popup_id_prefix; ?><?php echo $counter; ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
                        <div class="downloadPopupContainer" style="display: none;">
                            <div class="downloadPopup" id="downloadPopup<?php echo $popup_id_prefix; ?><?php echo $counter; ?>">
                                <div class="container">
                                    <div class="preview-container">
                                        <?php if (get_sub_field('pdf_flip_embed')) { ?>
                                            <?php echo get_sub_field('pdf_flip_embed'); ?>
                                        <?php } else { ?>
                                            <div class="download-image-container">
                                                <div class="image" style="background-image: url(<?php echo get_sub_field( 'listing_image' ); ?>);"></div>
                                            </div>
                                        <?php }?>
                                        <div class="description-container desktop">
                                            <?php if(get_sub_field( 'listing_title' )){ ?>
                                                <span class="listing-title"><?php echo get_sub_field( 'listing_title' ); ?></span>
                                            <?php } ?>
                                            <?php if(get_sub_field( 'listing_text' )){ ?>
                                                <span class="listing-details"><?php echo get_sub_field( 'listing_text' ); ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="download-container">
                                        <span class="download-title">
                                            Download options
                                        </span>
                                        <span class="downloads">
                                            <?php if ( have_rows( 'download' ) ) : ?>
                                                <?php while ( have_rows( 'download' ) ) : the_row(); ?>
                                                    <?php echo get_sub_field( 'download' ); ?>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="description-container mobile">
                                        <?php if(get_sub_field( 'listing_title' )){ ?>
                                            <span class="listing-title"><?php echo get_sub_field( 'listing_title' ); ?></span>
                                        <?php } ?>
                                        <?php if(get_sub_field( 'listing_text' )){ ?>
                                            <span class="listing-details"><?php echo get_sub_field( 'listing_text' ); ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $counter++; ?>
                <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
</section>
