<span class="events-card column <?php echo esc_attr( $extra_classes ); ?>">
     <?php if (get_field('external_link')) { ?>
        <a href="<?php echo esc_url( get_field('external_link') ); ?>" class="event-link" target="<?php echo esc_attr( get_field('external_link_target') ); ?>">
    <?php } else { ?>
        <a href="<?= esc_url( get_post_type() === 'event' ? '' : get_the_permalink() ); ?>" class="event-link" target="_self">
    <?php }?>
        <span class="events-card-inner">
            <span class="image-container">
                <span class="bg-container">
                    <?php
					$inline_img_151_src = get_field( 'listing_page_grid_image' );
					$inline_img_151_attach_id = $inline_img_151_src ? adapt_attachment_url_to_postid( $inline_img_151_src ) : 0;
					// Only the first card in the events-portal grid should be
					// fetchpriority=high/eager and excluded from lazy-load -
					// it's the one that renders above the fold as the page's
					// LCP element. $hero_fetchpriority_used is set by
					// template-events-portal.php before this loop starts;
					// isset() guards this component being included from
					// somewhere that doesn't declare it.
					$inline_img_151_attrs = array( 'alt' => esc_attr( get_the_title() ) );
					if ( empty( $hero_fetchpriority_used ) ) {
						$inline_img_151_attrs['class'] = 'skip-lazy';
						$inline_img_151_attrs['fetchpriority'] = 'high';
						$inline_img_151_attrs['loading'] = 'eager';
						$hero_fetchpriority_used = true;
					}
					if ( $inline_img_151_attach_id ) {
						echo wp_get_attachment_image( $inline_img_151_attach_id, 'full', false, $inline_img_151_attrs );
					} elseif ( $inline_img_151_src ) {
						$fallback_loading = $inline_img_151_attrs['loading'] ?? 'lazy';
						$fallback_class = isset( $inline_img_151_attrs['class'] ) ? ' class="' . esc_attr( $inline_img_151_attrs['class'] ) . '"' : '';
						$fallback_fetchpriority = isset( $inline_img_151_attrs['fetchpriority'] ) ? ' fetchpriority="' . esc_attr( $inline_img_151_attrs['fetchpriority'] ) . '"' : '';
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $fallback_class and $fallback_fetchpriority are pre-built attribute-string fragments assembled above from esc_attr()-wrapped values; the surrounding markup is static.
						echo '<img' . $fallback_class . ' src="' . esc_url( $inline_img_151_src ) . '" loading="' . esc_attr( $fallback_loading ) . '"' . $fallback_fetchpriority . ' alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
                </span>
            </span>
            <span class="events-text">
                <span class="labelXLarge"><?php echo esc_html( get_the_title() ); ?></span>
                <span class="labelXXsmall date text-red">
                    <span><?php echo esc_html( get_field('event_date') ); ?></span>
                </span>
                <span class="excerpt text-small">
                    <?php echo esc_html( get_field('event_short_description_for_listing') ); ?>
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
