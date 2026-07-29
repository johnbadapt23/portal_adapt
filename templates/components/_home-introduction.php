<section class="advantage-home-introduction">
    <div class="container">
        <?php $background_pattern = get_sub_field( 'background_pattern' ); ?>
        <div class="introduction-container" <?php if ( $background_pattern ) { ?>style="background-image: url(<?php echo $background_pattern['url']; ?>);"<?php } ?>>
            <h1 class="introduction-title"><?php echo get_sub_field( 'introduction_text' ); ?></h1>
            <?php if ( have_rows( 'link_blocks' ) ) : ?>
                <div class="introduction-link-container">
                    <?php while ( have_rows( 'link_blocks' ) ) : the_row(); ?>
                        <div class="introduction-link column one-third">
                            <a href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>">
                                <span class="link-inner">
                                    <span class="icon-container">
                                        <span class="icon-background-container">
                                            <span class="bg-container">
                                                <?php $icon = get_sub_field( 'icon' ); ?>
                                                <?php if ( $icon ) { ?>
                                                    <img class="image" src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                                <?php } ?>
                                            </span>
                                            <span class="hover-container">
                                                <?php $hover_icon = get_sub_field( 'hover_icon' ); ?>
                                                <?php if ( $hover_icon ) { ?>
                            						<img class="hover-image" src="<?php echo $hover_icon['url']; ?>" alt="<?php echo $hover_icon['alt']; ?>" />
                            					<?php } ?>
                                            </span>
                                        </span>
                                    </span>
                                    <h2 class="link-title"><?php echo get_sub_field( 'title' ); ?></h2>
                                    <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                                    <span class="readMore"><?php echo get_sub_field( 'link_text' ); ?></span>
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
