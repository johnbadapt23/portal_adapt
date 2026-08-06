<section class="parallax-image-module">
    <div class="parallax-image-container">
        <?php $desktop_image = get_sub_field( 'desktop_image' ); ?>
        <?php if ( $desktop_image ) { ?>
            <?php echo wp_get_attachment_image( $desktop_image['ID'], 'full', false, array( 'alt' => $desktop_image['alt'], 'class' => 'desktop-image mobile-hide' ) ); ?>
        <?php } ?>
        <?php $mobile_image = get_sub_field( 'mobile_image' ); ?>
        <?php if ( $mobile_image ) { ?>
            <?php echo wp_get_attachment_image( $mobile_image['ID'], 'full', false, array( 'alt' => $mobile_image['alt'], 'class' => 'mobile-image desktop-hide' ) ); ?>
        <?php } ?>
    </div>
</section>
