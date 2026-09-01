<section class="advantage-home-introduction">
    <div class="container">
        <?php $background_pattern = get_sub_field( 'background_pattern' ); ?>
        <div class="introduction-container" <?php if ( $background_pattern ) { ?>style="background-image: url(<?php echo esc_url( $background_pattern['url'] ); ?>);"<?php } ?>>
            <h1 class="introduction-title"><?php echo esc_html( get_sub_field( 'introduction_text' ) ); ?></h1>
            <?php if ( have_rows( 'link_blocks' ) ) : ?>
                <div class="introduction-link-container">
                    <?php while ( have_rows( 'link_blocks' ) ) : the_row(); ?>
                        <div class="introduction-link column one-third">
                            <a href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>">
                                <span class="link-inner">
                                    <span class="icon-container">
                                        <span class="icon-background-container">
                                            <span class="bg-container">
                                                <?php $icon = get_sub_field( 'icon' ); ?>
                                                <?php if ( $icon ) { ?>
                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, [ 'alt' => $icon['alt'], 'class' => 'image' ] ); ?>
                                                <?php } ?>
                                            </span>
                                            <span class="hover-container">
                                                <?php $hover_icon = get_sub_field( 'hover_icon' ); ?>
                                                <?php if ( $hover_icon ) { ?>
                            						<?php echo wp_get_attachment_image( $hover_icon['ID'], 'full', false, [ 'alt' => $hover_icon['alt'], 'class' => 'hover-image' ] ); ?>
                            					<?php } ?>
                                            </span>
                                        </span>
                                    </span>
                                    <h2 class="link-title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                                    <span class="text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                    <span class="readMore"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></span>
                                </span>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>
