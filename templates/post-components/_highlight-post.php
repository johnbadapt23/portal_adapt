<?php $post_object = get_sub_field( 'post' ); ?>
<?php if ( $post_object ): ?>
    <?php $post = $post_object; ?>
    <?php setup_postdata( $post ); ?> 
    <section class="highlight-post">
        <div class="container">
            <div class="post-container">
                <div class="column title-column">
                    <h2 class="headerXsmall text-bold">
                        <span class="h2-inner">
                            <?php the_title(); ?>
                        </span>
                    </h2>
                    <span class="text-regular black-text"><?php the_excerpt(); ?></span>
                    <span class="link-container">
                        <a href="<?php the_permalink(); ?>" class="text-link red-text-link uppercase arrow-link">Watch Video</a>
                    </span>
                </div>
                <div class="column video-column">
                    <?php if(current_user_can('memberpress_authorized')) { ?>
                        <?php if( get_field('vimeo_code')){ ?>
                            <a href="https://vimeo.com/<?php echo get_field('vimeo_code'); ?>" class="image popup-vimeo">
                        <?php } else { ?>
                            <a href="" class="image postPlayBtn">
                        <?php }?>
                    <?php } else { ?>

                    <?php }?>
                        <span class="imageSizeContainer">
                            <span class="overlayGradient"></span>
                            <span class="bgContainer">
                                <?php $image = get_field('video_poster'); ?>
                                <img class="desktop" src="<?php echo $image; ?>" alt="" />
                            </span>
                            <?php if(current_user_can('memberpress_authorized')) { ?>
                                <span class="watchIcon"></span>
                            <?php } else { ?>
                                <span class="lockedwatchIcon"></span>
                            <?php } ?>                   
                            </span>
                    <?php if(current_user_can('memberpress_authorized')) { ?>
                        </a>
                    <?php } ?>
                </div>
                
            </div>
        </div>
    </section>
    <?php wp_reset_postdata(); ?>
<?php endif; ?>
