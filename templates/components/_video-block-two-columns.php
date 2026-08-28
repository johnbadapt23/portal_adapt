<?php
// _video-block-three-columns.php is a near-identical component that only
// differs in the column-count class used below - rather than maintain two
// full copies of this markup, it sets $video_variant and includes this
// file directly.
$video_variant = $video_variant ?? 'two';
?>
<section class="video-grid-block" <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?>>
    <div class="container">
        <?php if(get_sub_field( 'block_title' )){ ?>
            <span class="video-block-title"><?php echo get_sub_field( 'block_title' ); ?></span>
        <?php } ?>
        <?php if ( have_rows( 'video_columns' ) ) : ?>
            <div class="video-grid-block-container">
                <?php $counter = 0; ?>
				<?php while ( have_rows( 'video_columns' ) ) : the_row(); ?>
                    <div class="column <?php echo $video_variant; ?>-column <?php echo $counter; ?>">
                        <div class="video-image-container">
                            <div class="image" style="background-image: url(<?php echo get_sub_field( 'listing_image' ); ?>);"></div>
                            <span class="videoLink">
                                <?php if( get_sub_field('vimeo_code_popup')){ ?>
                                    <a href="https://vimeo.com/<?php echo get_sub_field('vimeo_code_popup'); ?>" class="image popup-vimeo">
                                <?php } else { ?>
                                    <a href="#" class="playBtnGrid">
                                <?php } ?>                                
                                    <span class="icon">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.svg" width="51" height="51" loading="lazy" alt="Play Icon" />
                                    </span>
                                    <span class="text">
                                        <span><?php echo get_sub_field( 'button_text' ); ?></span>
                                        <span><?php echo get_sub_field( 'duration' ); ?></span>
                                    </span>
                                </a>
                            </span>
                        </div>
                        <?php if(get_sub_field( 'listing_title' )){ ?>
                            <span class="listing-title"><?php echo get_sub_field( 'listing_title' ); ?></span>
                        <?php } ?>
                        <?php if(get_sub_field( 'listing_text' )){ ?>
                            <span class="listing-details"><?php echo get_sub_field( 'listing_text' ); ?></span>
                        <?php } ?>
                    </div>
                    <?php $counter++; ?>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
    </div>
    <?php if ( have_rows( 'video_columns' ) ) : ?>
        <?php $counter = 0; ?>
            <?php while ( have_rows( 'video_columns' ) ) : the_row(); ?>
                <div class="videoPlayerContainerGrid">
                    <span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" width="25" height="25" loading="lazy" alt="Close" /></span>
                    <div class="videoWrapper">
                        <video width="100%" id="popupVideo" controls controlsList="nodownload">
                            <source type="video/mp4" src="<?php echo esc_url( get_sub_field( 'vimeo_code' ) ); ?>" />
                        </video>
                    </div>
                </div>
                <?php $counter++; ?>
            <?php endwhile; ?>
    <?php else : ?>
        <?php // no rows found ?>
    <?php endif; ?>
</section>
