<section class="evr-fundamentals" <?php if( get_sub_field( 'id' )){ ?> id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="container">
        <div class="title-container">
            <h2 class="evr-title"><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <div class="fundamentals-container">
            <?php if ( have_rows( 'fundamentals_row_one' ) ) : ?>
                <div class="fundamentals-row-one">
                    <?php while ( have_rows( 'fundamentals_row_one' ) ) : the_row(); ?>
                        <?php if ( have_rows( 'fundamentals' ) ) : ?>
                            <?php while ( have_rows( 'fundamentals' ) ) : the_row(); ?>
                                <?php $fundamental_term = get_sub_field( 'fundamental' ); ?>
                                <?php if ( $fundamental_term ): ?>
                                    <span class="other-fundamentals-items other-items"><a href="<?php echo esc_url( get_term_link($fundamental_term) ); ?>" target="_self">
                                    <?php $icon = get_field( 'icon', $fundamental_term ); ?>
                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'], 'width' => '24' ) ); ?><?php echo $fundamental_term->name; ?> 
                                    </a></span>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
			<?php if ( have_rows( 'fundamentals_row_two' ) ) : ?>
                <div class="fundamentals-row-two">
                    <?php while ( have_rows( 'fundamentals_row_two' ) ) : the_row(); ?>
                        <?php if ( have_rows( 'fundamentals' ) ) : ?>
                            <?php while ( have_rows( 'fundamentals' ) ) : the_row(); ?>
                                <?php $fundamental_term = get_sub_field( 'fundamental' ); ?>
                                <?php if ( $fundamental_term ): ?>
                                    <span class="other-fundamentals-items other-items"><a href="<?php echo esc_url( get_term_link($fundamental_term) ); ?>" target="_self">
                                        <?php $icon = get_field( 'icon', $fundamental_term ); ?>
                                        <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'], 'width' => '24' ) ); ?>Value from <?php echo $fundamental_term->name; ?> 
                                    </a></span>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </div>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
    </div>
</section>