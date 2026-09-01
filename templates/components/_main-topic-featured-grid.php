<section class="featuredGrid portal mainTopic">
    <div class="container">
        <div class="blockTitle">
            <h2>Featured</h2>
        </div>
        <div class="gridWrapper">
            <div href="<?php the_permalink(); ?>" class="item">
                <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                    <span class="overlayGradient"></span>
                    <div class="bgContainer">
                        <?php $image = get_sub_field('background_image'); ?>
                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'], 'class' => 'desktop' ] ); ?>
                    </div>
                </a>
                <div class="textContainer">
                    <span class="topicFilter">
                        <a href="" class="topicFilterText">Security</a>
                        <a href="" class="topicFilterText">Research</a>
                    </span>
                    <a href="<?php the_permalink(); ?>" class="title">ASX’s Dan Chesterman shares how to measure the success of digital transformation with ADAPT’s Matt Boon</a>
                    <span class="dateReadTime">JUL 10, 2020  |  10 MIN</span>
                </div>
            </div>
            <div href="<?php the_permalink(); ?>" class="item">
                <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                    <div class="bgContainer">
                        <?php $image = get_sub_field('background_image'); ?>
                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'], 'class' => 'desktop' ] ); ?>
                    </div>
                </a>
                <div class="textContainer">
                    <span class="topicFilter">
                        <a href="" class="topicFilterText">Security</a>
                        <a href="" class="topicFilterText">Research</a>
                    </span>
                    <a href="<?php the_permalink(); ?>" class="title">Overcoming digital stress in an ‘always-on’ world: Strategies to support customers through uncertain times</a>
                    <span class="dateReadTime">JUL 10, 2020  |  10 MIN</span>
                </div>
            </div>

        </div>
    </div>
</section>
