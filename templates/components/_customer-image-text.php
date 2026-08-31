<section class="customer-kit-image-text <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
    <div class="container">
        <div class="title-container">
            <h3><?php echo esc_html( get_sub_field( 'title' ) ); ?></h3>
        </div>
        <div class="column-container">
            <?php if ( have_rows( 'column' ) ) : ?>
                <?php while ( have_rows( 'column' ) ) : the_row(); ?>
                    <div class="column one-half">
                        <div class="image-column one-third">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <?php if ( $image ) { ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
                            <?php } ?>
                        </div>
                        <div class="text-column two-thirds">
                            <span class="title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
                            <span class="text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>





