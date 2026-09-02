<?php
$date_string = get_field('replay_event_date');
$date = DateTime::createFromFormat('Ymd', $date_string);
?>
<section class="expertPresentationFeatured bg-black singleResearch">
    <div class="container">
        <div class="item">
            <?php if(current_user_can('memberpress_authorized')) { ?>
                <?php if( get_field('replay_vimeo_code')){ ?>
                    <a href="https://vimeo.com/<?php echo esc_attr( get_field('replay_vimeo_code') ); ?>" class="image popup-vimeo">
                <?php } else { ?>
                    <a href="" class="image postPlayBtn">
                <?php }?>
            <?php } else { ?>

            <?php }?>
                <span class="imageSizeContainer">
                    <span class="overlayGradient"></span>
                    <span class="bgContainer">
                    <?php $video_image = get_field( 'video_image' ); ?>
                    <?php if ( $video_image ) { ?>
                        <?php
								$video_image_attach_id = adapt_attachment_url_to_postid( $video_image );
								if ( $video_image_attach_id ) {
									echo wp_get_attachment_image( $video_image_attach_id, 'full', false, [ 'alt' => esc_attr( get_the_title() ) ] );
								} else {
									echo '<img src="' . esc_url( $video_image ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>                        
                    <?php } ?>
                    </span>
                    <?php if(current_user_can('memberpress_authorized')) { ?>
                        <span class="watchIcon"></span>
                    <?php } else { ?>
                        <span class="lockedwatchIcon"></span>
                    <?php } ?>                   
                </span>
            <?php if(current_user_can('memberpress_authorized')) { ?>
                </a>
            <?php } ?>
            <div class="textContainer bg-secondary-black">
               <?php
                    $text = get_the_excerpt();
                    $trimmed_content = wp_trim_words( $text, $num_words = 22 );
                ?>
                <span class="published labelSmall text-dark-grey<?php if ( $download ) { ?><?php } else { ?> no-margin-border<?php } ?>">
                    <?php echo esc_html( $date->format('j F, Y') ); ?>
                </span>
                <span class="type-topic labelSmall">
                    <a href="/events/analyst-market-briefings/" class="topic-filter red-text">Analyst Market Briefings</a>
                </span>
                <?php if ( $trimmed_content ) : ?>
                    <span class="text text-white"><?php echo esc_html( $trimmed_content ); ?></span>
                <?php endif; ?>
                <?php if(current_user_can('memberpress_authorized')) { ?>
                    <span class="replay-button-container">
                        <a class="replay-button popup-vimeo" href="https://vimeo.com/<?php echo esc_attr( get_field('replay_vimeo_code') ); ?>">Watch Replay</a>
                    </span>                                
                <?php } ?>                
                
                                                <?php if ( have_rows( 'contributors' ) ) : ?>

    <?php
    $contributors = get_field( 'contributors' );
    $count = 0;

    if ( is_array( $contributors ) ) {
        foreach ( $contributors as $contributor ) {
            if ( ! empty( $contributor['contributor_name'] ) ) {
                $count++;
            }
        }
    }
    ?>

    <span class="contributor-container">
        <span class="contributor-title labelSmall text-dark-grey">
            <?php echo ( $count > 1 ) ? 'Authors' : 'Author'; ?>
        </span>

        <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
            <?php $post_object = get_sub_field( 'contributor_name' ); ?>
            <?php if ( $post_object ) : ?>
                <?php
                $post = $post_object;
                setup_postdata( $post );
                ?>
                <!-- <a href="<?php the_permalink(); ?>"> -->
                    <span class="contributor labelSmall text-black">
                        
                            <?php echo esc_html( get_the_title() ); ?>
                       
                    </span>
                     <!-- </a> -->
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    </span>

<?php endif; ?>
            </div>
        </div>               
    </div>
</section>