<section class="fullWidthTextEditor print-only<?php if ( get_sub_field( 'font') ) { ?> <?php echo get_sub_field( 'font' );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php echo get_sub_field( 'font_colour' ); ?><?php } ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?>>
    <div class="container">
        <?php echo get_sub_field( 'text_editor' ); ?>
        <?php if ( have_rows( 'button_block' ) ) : ?>
            <div class="buttonBlock">
                <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                    <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
