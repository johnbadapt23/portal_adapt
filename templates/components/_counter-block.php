<section class="logoGrid counter <?php echo get_sub_field( 'background_colour' ); ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <div class="titleBlock">
            <span class="title">
                <h2><?php echo get_sub_field( 'block_title' ); ?></h2>
            </span>

            <span class="description <?php echo get_sub_field( 'top_right_text_position' ); ?>">
                <h3><?php echo get_sub_field( 'top_right_text' ); ?></h3>
            </span>
        </div>

        <?php if ( have_rows( 'numbers' ) ) : ?>
            <div class="logoBlock">
                <?php while ( have_rows( 'numbers' ) ) : the_row(); ?>
                    <div class="logo">
                        <span class="number"><?php echo get_sub_field( 'number' ); ?></span>

                        <span class="logoTitle">
                            <?php echo get_sub_field( 'title' ); ?>
                        </span>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
            <a class="logoBlockLink <?php echo get_sub_field( 'link_style' ); ?>" href="<?php echo get_sub_field( 'link_url' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
        <?php } ?>
    </div>
</section>
