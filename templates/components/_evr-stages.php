<section class="evr-stages" <?php if( get_sub_field( 'id' )){ ?> id="<?php echo esc_attr( get_sub_field( 'id' ) ); ?>"<?php } ?>>
    <div class="container">
        <div class="title-container">
            <h2 class="evr-title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
        </div>
        <div class="stages-container">
            <?php if ( have_rows( 'stages' ) ) : ?>
            <?php while ( have_rows( 'stages' ) ) : the_row(); ?>
                <?php $stage_term = get_sub_field( 'stage' ); ?>
                <?php if ( $stage_term ): ?>                    
                    <span class="stages-item">                       
                        <span class="stages-icon-container">
                                <?php $icon = get_field( 'icon', $stage_term ); ?>
                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, [ 'alt' => $icon['alt'], 'width' => '24' ] ); ?> 
                        </span>
                        <span class="stages-title-container">
                            <h3><?php echo esc_html( $stage_term->name ); ?></h3>
                        </span>
                        <span class="stages-text">
                            <p><?php echo esc_html( $stage_term->description ); ?></p>               
                        </span>
                        <?php $stage_term_link = get_term_link( $stage_term ); ?>
                        <?php if ( ! is_wp_error( $stage_term_link ) ) : ?>
                        <span class="stages-button-container">
                            <a class="button data-set-button" href="<?php echo esc_url( $stage_term_link ); ?>" target="_self">View All</a>
                        </span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        </div>
    </div>
</section>



