<span class="events-card column <?php echo $extra_classes; ?>">
     <?php if (get_field('external_link')) { ?>
        <a href="<?php echo get_field('external_link'); ?>" class="event-link" target="<?php echo get_field('external_link_target'); ?>">
    <?php } else { ?>
        <a href="<?= get_post_type() === 'event' ? '' : get_the_permalink(); ?>" class="event-link" target="_self">
    <?php }?>
        <span class="events-card-inner">
            <span class="image-container">
                <span class="bg-container">
                    <?php
					$inline_img_151_src = get_field( 'listing_page_grid_image' );
					$inline_img_151_attach_id = $inline_img_151_src ? attachment_url_to_postid( $inline_img_151_src ) : 0;
					if ( $inline_img_151_attach_id ) {
						echo wp_get_attachment_image( $inline_img_151_attach_id, 'full', false, array( 'alt' => esc_attr( get_the_title() ) ) );
					} elseif ( $inline_img_151_src ) {
						echo '<img src="' . esc_url( $inline_img_151_src ) . '" loading="lazy" alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
                </span>                
            </span>
            <span class="events-text">
                <span class="labelXLarge"><?php echo the_title(); ?></span>
                <span class="labelXXsmall date text-red">
                    <span><?php echo get_field('event_date'); ?></span>
                </span>
                <span class="excerpt text-small">
                    <?php echo get_field('event_short_description_for_listing'); ?>
                </span>
                <?php if( empty(get_field('external_link')) && get_post_type() === 'event' ) : ?>
                <?php else : ?>
                <span class="link-container">
                    <span class="text-link red-text learn-more red-arrow-link arrow-link bold-link uppercase">Learn More</span>
                </span>
                <?php endif; ?>
            </span>
        </span>        
    </a>
</span>
