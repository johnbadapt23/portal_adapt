<section class="caseStudiesFeaturedVideo portal bg-white">
    <div class="container">
        <div class="item">
            <a href="<?php the_permalink(); ?>" class="title mobile">Being Digital in 2020 means getting your hands D.I.R.T.Y.</a>
            <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                <div class="bgContainer">
                    <?php $image = get_sub_field('background_image'); ?>
                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'], 'class' => 'desktop' ) ); ?>
                    <span class="watchIcon"></span>
                </div>
            </a>
            <div class="textContainer">
                <a href="<?php the_permalink(); ?>" class="title desktop">Being Digital in 2020 means getting your hands D.I.R.T.Y.</a>
                <span class="excerpt">At CIO Edge, globally experienced IT veteran David Banger discussed how to grasp the opportunity for technology teams as “technology” and “digital” intersect.</span>
                <a href="<?php the_permalink(); ?>" class="readMore">Watch Video</a>
            </div>
        </div>
    </div>
</section>
