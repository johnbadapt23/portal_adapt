<section class="topicBanner">
    <div class="imageSizeContainer">
        <div class="bgContainer">
            <?php $image = get_sub_field('background_image'); ?>
            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'], 'class' => 'desktop' ] ); ?>
        </div>
        <div class="container">
            <span class="bannerBreadcrumbs">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb">Home</a><span class="divider">/</span><span class="breadcrumb">Cloud & Infrastructure</span></a>
            </span>
            <h1>Cloud & Infrastructure</h1>
            <p>Access research, peer insights and local data to build a path forward.</p>

            <a href="" class="follow">Follow</a>
        </div>
    </div>
</section>
