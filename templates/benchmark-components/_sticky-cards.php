<section class="benchmarking-sticky-cards background-light-grey">
    <div class="container">
        <div class="top-content">
			<h2 class="text-black bold-red"><?php echo wp_kses_post( get_sub_field( 'title' ) ); ?></h2>
			<span class="link-container desktop">
                <?php if ( have_rows( 'button' ) ) : ?>
                    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                        <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                            <a class="stdBtn std-button red-outline-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) == 'scroll-to'){ ?> 
                            <a class="stdBtn std-button scroll-to-button red-outline-button" href="#<?php echo esc_attr( get_sub_field( 'scroll_to_id' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                            <?php $file = get_sub_field( 'file' ); ?>
                            <a class="download-file-button std-button red-outline-button" href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                        <?php } else { ?>
                            <?php
                            // A hardcoded id here (formerly the same id on every row) meant
                            // that with more than one button row, every row's magnificPopup
                            // trigger (which resolves its target via this href="#id", not a
                            // relative DOM lookup) opened the FIRST row's modal.
                            // get_row_index() makes each row's trigger/target pair unique;
                            // the "top" prefix keeps it unique from the mobile button loop
                            // further down this same file too.
                            $popup_id = 'stickyCardFormPopup-top-' . get_row_index();
                            ?>
                            <?php if( get_sub_field( 'link_type' ) =='download-form') { ?>
                            <a class="formPopupHubspot download-file-button stdBtn std-button red-outline-button" href="#<?php echo esc_attr( $popup_id ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                            <?php } else { ?>
                            <a class="formPopupHubspot stdBtn std-button red-outline-button" href="#<?php echo esc_attr( $popup_id ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                            <?php } ?>
                            <div style="display: none;">
                                <div class="preview-cta-form login-form-container" id="<?php echo esc_attr( $popup_id ); ?>">
                                    <div class="form-container"><?php echo get_sub_field( 'form_code' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot form-embed markup requires raw HTML/script output; wp_kses_post() would strip the tags the embed needs to function. ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
			</span>
		</div>
       <div class="sticky-columns-wrapper desktop">
            <!-- TEXT COLUMN -->
            <div class="text-column">
                <?php if ( have_rows( 'sticky_content' ) ) : ?>
                    <?php while ( have_rows( 'sticky_content' ) ) : the_row(); ?>
                        <div class="text-block-wrapper">
                            <div class="text-block">
                                <h3 class="header-medium"><?php echo wp_kses_post( get_sub_field( 'title' ) ); ?></h3>
                                <p class="p-large text-dark-grey"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <!-- IMAGE COLUMN -->
            <div class="image-column">
                <?php if ( have_rows( 'sticky_content' ) ) : ?>
                    <?php $i = 0; ?>
                    <?php while ( have_rows( 'sticky_content' ) ) : the_row(); ?>
                        <?php $image = get_sub_field( 'image' ); ?>
                        <?php if ( $image ) { ?>
                            <div class="sticky-image-container" style="--i: <?php echo esc_attr( $i ); ?>;">
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
                            </div>
                        <?php } ?>
                        <?php $i++; ?>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="columns-wrapper mobile">
            <?php if ( have_rows( 'sticky_content' ) ) : ?>
                <?php while ( have_rows( 'sticky_content' ) ) : the_row(); ?>
                    <div class="mobile-column">
                        <div class="text-block-wrapper">
                            <div class="text-block">
                                <h3 class="header-medium"><?php echo wp_kses_post( get_sub_field( 'title' ) ); ?></h3>
                                <p class="p-medium text-dark-grey"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></p>
                            </div>
                        </div>
                        <?php $image = get_sub_field( 'image' ); ?>
                        <?php if ( $image ) { ?>
                            <div class="image-container-full">
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>     
            <span class="link-container">
                <?php if ( have_rows( 'button' ) ) : ?>
                    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                        <?php if( get_sub_field( 'link_type' ) == 'link'){ ?> 
                            <a class="stdBtn std-button red-outline-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) == 'scroll-to'){ ?> 
                            <a class="stdBtn std-button scroll-to-button red-outline-button" href="#<?php echo esc_attr( get_sub_field( 'scroll_to_id' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                        <?php } else if( get_sub_field( 'link_type' ) =='file') { ?> 
                            <?php $file = get_sub_field( 'file' ); ?>
                            <a class="download-file-button std-button red-outline-button" href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                        <?php } else { ?>
                            <?php
                            // Same unique-id fix as the desktop button loop above - "bottom"
                            // prefix keeps this mobile loop's ids unique from that one too.
                            $popup_id = 'stickyCardFormPopup-bottom-' . get_row_index();
                            ?>
                            <?php if( get_sub_field( 'link_type' ) =='download-form') { ?>
                            <a class="formPopupHubspot download-file-button stdBtn std-button red-outline-button" href="#<?php echo esc_attr( $popup_id ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                            <?php } else { ?>
                            <a class="formPopupHubspot stdBtn std-button red-outline-button" href="#<?php echo esc_attr( $popup_id ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                            <?php } ?>
                            <div style="display: none;">
                                <div class="preview-cta-form login-form-container" id="<?php echo esc_attr( $popup_id ); ?>">
                                    <div class="form-container"><?php echo get_sub_field( 'form_code' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot form-embed markup requires raw HTML/script output; wp_kses_post() would strip the tags the embed needs to function. ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
			</span>
        </div>
    </div>
</section>
<script>
function syncTextHeights(attempt = 1) {
    const textWrappers = document.querySelectorAll('.text-block-wrapper');
    const images = document.querySelectorAll('.sticky-image-container');

    let needsRetry = false;

    textWrappers.forEach((wrapper, index) => {
        const img = images[index]?.querySelector('img');
        if (!img) return;

        const h = img.offsetHeight;
        
        if (h === 0) {
            needsRetry = true;
        } else {
            wrapper.style.height = h + 'px';
        }
    });

    // Retry up to 20 times with a short delay
    if (needsRetry && attempt < 20) {
        setTimeout(() => syncTextHeights(attempt + 1), 100);
    }
}

// Run once DOM is ready
document.addEventListener('DOMContentLoaded', () => syncTextHeights());

// Debounced resize
window.addEventListener('resize', () => {
    clearTimeout(window.syncTextTimeout);
    window.syncTextTimeout = setTimeout(() => syncTextHeights(), 100);
});
</script>
