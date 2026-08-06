<section id="snapshot" class="print-only scrollPos singlePost repeatableSingle">
    <div class="container">
        <div class="post-inner">
            <div class="snapshot-container">

            <?php if (have_rows('snapshots')) : ?>

                <!-- ================= MAIN SLIDER ================= -->
                <div class="snapshot-slider-main">
                    <?php
                    $i = 0;
                    while (have_rows('snapshots')) : the_row();
                        $image = get_sub_field('snapshot_image');
                        if (!$image) continue;
                    ?>
                        <div class="snapshot-slide">
                            <a href="#"
                               class="snapshot-popup-trigger"
                               data-index="<?php echo esc_attr($i); ?>">
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                            </a>
                        </div>
                    <?php
                        $i++;
                    endwhile;
                    ?>
                </div>

                <!-- ================= THUMBS ================= -->
                <div class="snapshot-slider-thumbs">
                    <?php while (have_rows('snapshots')) : the_row();
                        $image = get_sub_field('snapshot_image');
                        if (!$image) continue;
                    ?>
                        <div class="snapshot-thumb">
                            <img src="<?php echo esc_url($image['sizes']['medium']); ?>">
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- ================= POPUP CONTENT ================= -->
                <div id="snapshot-popup" class="mfp-hide">
                    <div class="snapshot-popup-slider">

                        <?php while (have_rows('snapshots')) : the_row();
                            $image = get_sub_field('snapshot_image');
                            if (!$image) continue;
                            $url = esc_url($image['url']);
                        ?>
                            <div class="snapshot-popup-slide">
                                <figure>
                                    <img src="<?php echo $url; ?>">

                                    <figcaption class="snapshot-actions">
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($url); ?>"
                                           target="_blank">Share on LinkedIn</a>

                                        <a href="mailto:?subject=Shared image&amp;body=<?php echo urlencode($url); ?>">
                                            Share Via Email
                                        </a>

                                        <a href="<?php echo $url; ?>" download>
                                            Download Image
                                        </a>
                                    </figcaption>
                                </figure>
                            </div>
                        <?php endwhile; ?>

                    </div>
                </div>

            <?php endif; ?>

            </div>
        </div>
    </div>
</section>
