<?php if(get_sub_field('background_colour') == 'background-true-black'){ ?>
    <?php $textcolor = 'white-text' ?>
    <?php $textSecondary = 'white-text' ?>
<?php } else { ?> 
    <?php $textcolor = 'black-text' ?>
    <?php $textSecondary = 'text-secondary' ?>
<?php } ?>
<?php 
    $padding_top = get_sub_field('padding_top');
    $padding_bottom = get_sub_field('padding_bottom');
    $image_drop_shadow = get_sub_field('add_drop_shadow');
?>
<section class="two-column two-column-image-text-customer-events<?php if ( $image_drop_shadow == 'yes' ) { ?> image-shadow<?php } ?> <?php if(get_sub_field('background_colour')){ ?><?php echo esc_attr( get_sub_field('background_colour') ); ?><?php } else { ?>background-white<?php } ?> <?php if($padding_top){ echo esc_attr( $padding_top ); } ?> <?php if($padding_bottom){ echo esc_attr( $padding_bottom ); } ?>">
    <div class="container">
        <div class="column-container <?php echo esc_attr( get_sub_field( 'orientation' ) ); ?>">
            <div class="column one-half image-column">
                <?php $image = get_sub_field( 'image' ); ?>
                <?php if ( $image ) { ?>
                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
                <?php } ?>
            </div>
            <div class="column one-half text-column">
                <div class="text-inner">
                    <h2 class="<?php echo esc_attr( $textcolor ); ?>"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                    <p class="p-medium <?php echo esc_attr( $textSecondary ); ?>"><?php echo esc_html( get_sub_field( 'text' ) ); ?></p>
                    <?php if ( have_rows( 'button' ) ) : ?>
                        <span class="button-container">
                            <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                <?php if( get_sub_field( 'link_type' ) == 'link'){ ?>
                                    <a class="stdBtn std-button red-outline-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                <?php } else if( get_sub_field( 'link_type' ) == 'file') { ?>
                                    <?php $file = get_sub_field( 'file' ); ?>
                                        <a class="file-button std-button download-icon-button red-outline-button" href="<?php echo esc_url( $file['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                <?php } else { ?>
                                    <span style="display: none"><?php echo get_sub_field( 'form_code' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot form-embed markup requires raw HTML/script output; wp_kses_post() would strip the tags the embed needs to function. ?></span>
                                    <span class="form-popup-button-container red-outline-button"><?php echo esc_html( get_sub_field( 'form_button' ) ); ?></span>
                                <?php } ?>
                            <?php endwhile; ?>
                        </span>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
