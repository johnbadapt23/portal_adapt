<section class="home-cta">
    <div class="container">
        <div class="cta-content-container">
            <div class="image-column column one-half <?php echo esc_attr( get_sub_field( 'image_orientation' ) ); ?>">
                <?php $image = get_sub_field( 'image' ); ?>
                <?php if ( $image ) { ?>
                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'], 'class' => 'desktop-image' ) ); ?>
                <?php } ?>
                <?php $image_mobile = get_sub_field( 'image_mobile' ); ?>
    			<?php if ( $image_mobile ) { ?>
    				<?php echo wp_get_attachment_image( $image_mobile['ID'], 'full', false, array( 'alt' => $image_mobile['alt'], 'class' => 'mobile-image' ) ); ?>
    			<?php } ?>
            </div>
            <div class="text-column column one-half <?php echo esc_attr( get_sub_field( 'image_orientation' ) ); ?>">
                <div class="v-wrap">
                    <div class="v-box">
                        <span class="information-container">
                            <h2 class="info-title white-text"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                            <span class="text white-text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                            <?php if ( have_rows( 'button' ) ) : ?>
                                <span class="button-container">
                                    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                        <a class="button std-button red-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                    <?php endwhile; ?>
                                </span>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
