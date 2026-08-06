<section class="evr-stages customer-kit-types" <?php if( get_sub_field( 'id' )){ ?> id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="container">
        <?php if (get_sub_field( 'title' )) { ?> 
            <div class="title-container">
                <h2 class="evr-title"><?php echo get_sub_field( 'title' ); ?></h2>
            </div>
        <?php } ?>        
        <div class="stages-container">
            <?php if ( have_rows( 'types' ) ) : ?>
            <?php while ( have_rows( 'types' ) ) : the_row(); ?>
                <?php $stage_term = get_sub_field( 'type' ); ?>
                <?php if ( $stage_term ): ?>                    
                    <span class="stages-item">   
                        <?php $icon = get_sub_field( 'icon' ); ?>                        
                        <?php if ( $icon ) { ?>
                            <span class="stages-icon-container">                                
                                <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                            </span>
                        <?php } ?>                                              
                        <span class="stages-title-container">
                            <h3><?php echo $stage_term->name; ?> Kits</h3>
                        </span>   
                        <?php if(get_sub_field( 'sub_title' )) { ?> 
                            <span class="stages-text-container">
                                <span class="text-inner">
                                    <?php echo get_sub_field( 'sub_title' ); ?>
                                </span>
                            </span>
                        <?php } ?>                    
                        <span class="stages-button-container">
                            <a class="stdBtn red data-set-button" href="<?php echo get_term_link($stage_term); ?>" target="_self">View All</a>
                        </span>
                    </span>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // no rows found ?>
        <?php endif; ?>
        </div>
    </div>
</section>



