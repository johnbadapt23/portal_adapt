<section class="topicBanner">
    <div class="imageSizeContainer">
        <div class="bgContainer">
            <?php $image = get_sub_field('background_image'); ?>
            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'], 'class' => 'desktop' ) ); ?>
        </div>
        <div class="container">
            <span class="bannerBreadcrumbs">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb">Home</a><span class="divider">/</span><span class="breadcrumb"><?php the_title(); ?></span></a>
            </span>
            <h1><?php the_title(); ?></h1>
            <p>The collective intelligence and experience of a network of local and global experts.</p>
        </div>
    </div>
</section>
