<section class="expertPresentationFeatured bg-dark">
    <div class="container">
        <div href="" class="imageSizeContainer">
            <span class="overlayGradient"></span>
            <div class="bgContainer">
                <?php $image = get_sub_field('background_image'); ?>
                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'], 'class' => 'desktop' ) ); ?>
            </div>
            <span class="watchIcon"></span>
            <span class="textContainer">
                <span class="topicFilter">
                    <a href="" class="topicFilterText">Emerging Technologies</a>
                    <a href="" class="topicFilterText">CIO Edge</a>
                </span>
                <a href="<?php the_permalink(); ?>" class="title">David Banger: Being Digital in 2020 means getting your hands D.I.R.T.Y.</a>
            </span>
        </div>
    </div>
</section>
