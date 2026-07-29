<section class="evr-fundamentals ecosystem-capabilities" <?php if( get_sub_field( 'id' )){ ?> id="<?php echo get_sub_field( 'id' ); ?>"<?php } ?>>
    <div class="container">
        <div class="title-container">
            <h2 class="evr-title capability-title"><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <div class="fundamentals-container capabilities-container">
            <?php if ( have_rows( 'capabilitites_row' ) ) : ?>
                <div class="fundamentals-row-one capabilities-row">
                    <?php while ( have_rows( 'capabilitites_row' ) ) : the_row(); ?>
                        <?php if ( have_rows( 'capabilitites' ) ) : ?>
                            <?php while ( have_rows( 'capabilitites' ) ) : the_row(); ?>
                                <?php $fundamental_term = get_sub_field( 'capabilitity' ); ?>
                                <?php if ( $fundamental_term ): ?>
                                    <span class="other-fundamentals-items capabilities-items other-items">
                                        <a href="/ecosystem-partners/search/?capabilities[]=<?php echo $fundamental_term->slug;?>" target="_self">
                                            <?php $icon = get_field( 'icon', $fundamental_term ); ?>
                                            <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" width="24"/><?php echo $fundamental_term->name; ?> 
                                        </a>
                                    </span>
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