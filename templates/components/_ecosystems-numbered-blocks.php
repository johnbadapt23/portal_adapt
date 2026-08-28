<section class="evr-stages customer-kit-types ecosystem-numbered-blocks" <?php if( get_sub_field( 'id' )){ ?> id="<?php echo esc_attr( get_sub_field( 'id' ) ); ?>"<?php } ?>>
    <div class="container">
        <div class="title-container">
            <h2 class="evr-title"><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <div class="stages-container">
            <?php if ( have_rows( 'steps' ) ) : ?>
            <?php $counter = 1; ?>
                <?php while ( have_rows( 'steps' ) ) : the_row(); ?>
                    <span class="stages-item"> 
                        <span class="stages-counter"><?php echo $counter; ?></span>                                              
                        <span class="stages-title-container">
                            <h3><?php echo get_sub_field( 'title' ); ?></h3>
                        </span>
                        <span class="stages-text-container">
                            <p><?php echo get_sub_field( 'text' ); ?></p>
                        </span>
                    </span>
                    <?php $counter++; ?>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>



