<section class="expertPresentationFeatured bg-dark singleResearch">
    <div class="container">
        <div class="imageSizeContainer">
            <span class="overlayGradient"></span>
            <div class="bgContainer">
                <?php $image = get_sub_field('background_image'); ?>
                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'], 'class' => 'desktop' ] ); ?>
            </div>
            <span class="watchIcon"></span>
            <span class="textContainer">
                <span class="title">David Banger: Being Digital in 2020 means getting your hands D.I.R.T.Y.</span>
            </span>
        </div>
        <span class="nextSection">
            <span class="nextSectionText">Read Keynote Summary</span>
        </span>
    </div>
</section>
