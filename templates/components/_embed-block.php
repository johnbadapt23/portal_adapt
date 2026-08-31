
<section class="fullWidthTextEditor print-only<?php if ( get_sub_field( 'font') ) { ?> <?php echo esc_attr( get_sub_field( 'font' ) );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php echo esc_attr( get_sub_field( 'font_colour' ) ); ?><?php } ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?>>
    <div class="container">
        <?php
        // Was dumping a leftover corporate-account-count debug value
        // (not-corporate / corporate + a raw integer) directly into the
        // page as visible text ahead of the actual embed, for every page
        // using this layout. $count was never used for anything else, so
        // there was nothing to preserve here - just the embed output below.
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored embed markup requires raw HTML/script output; wp_kses_post() would strip the tags the embed needs to function.
        echo get_sub_field( 'embed' );
        ?>
    </div>
</section>
