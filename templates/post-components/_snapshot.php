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
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
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
                            <?php
					$inline_img_194_src = $image['sizes']['medium'];
					$inline_img_194_attach_id = $inline_img_194_src ? attachment_url_to_postid( $inline_img_194_src ) : 0;
					if ( $inline_img_194_attach_id ) {
						echo wp_get_attachment_image( $inline_img_194_attach_id, 'full', false, [ 'alt' => $image['alt'] ] );
					} elseif ( $inline_img_194_src ) {
						echo '<img src="' . esc_url( $inline_img_194_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( $image['alt'] ) . '" />';
					}
				?>
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
                                    <?php
								$url_attach_id = attachment_url_to_postid( $url );
								if ( $url_attach_id ) {
									echo wp_get_attachment_image( $url_attach_id, 'full', false, [ 'alt' => $image['alt'] ] );
								} else {
									echo '<img src="' . esc_url( $url ) . '" loading="lazy" decoding="async" alt="' . esc_attr( $image['alt'] ) . '" />';
								}
							?>

                                    <figcaption class="snapshot-actions">
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode($url); ?>"
                                           target="_blank" rel="noopener noreferrer">Share on LinkedIn</a>

                                        <a href="mailto:?subject=Shared image&amp;body=<?php echo rawurlencode($url); ?>">
                                            Share Via Email
                                        </a>

                                        <a href="<?php echo esc_url( $url ); ?>" download>
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
