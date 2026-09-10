<span class="events-card-image column <?php echo esc_attr( $extra_classes ); ?>">
     <?php if (get_field('external_link')) { ?>
        <a href="<?php echo esc_url( get_field('external_link') ); ?>" class="event-link" target="<?php echo esc_attr( get_field('external_link_target') ); ?>">
    <?php } else { ?>
        <a href="<?php the_permalink(); ?>" class="event-link" target="_self">
    <?php }?>
        <span class="events-card-inner">
            <span class="image-container">
                <span class="bg-container">
                    <?php
					$inline_img_150_src = get_field( 'listing_page_grid_image' );
					$inline_img_150_attach_id = $inline_img_150_src ? adapt_attachment_url_to_postid( $inline_img_150_src ) : 0;
					if ( $inline_img_150_attach_id ) {
						echo wp_get_attachment_image( $inline_img_150_attach_id, 'full', false, [ 'alt' => esc_attr( get_the_title() ) ] );
					} elseif ( $inline_img_150_src ) {
						echo '<img src="' . esc_url( $inline_img_150_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
                </span>
                <span class="events-text">
                    <span class="labelXLarge"><?php echo esc_html( get_the_title() ); ?></span>
                    <span class="labelXXsmall date text-red">
                        <span><?php echo esc_html( get_field('event_date') ); ?></span>
                    </span>
                    <span class="excerpt">
                        <?php echo esc_html( get_field('event_short_description_for_listing') ); ?>
                    </span>
                    <span class="link-container">
                        <span class="text-link red-text learn-more red-arrow-link arrow-link bold-link uppercase">Learn More</span>
                    </span>
                </span>
            </span>
        </span>        
    </a>
</span>
