<?php
/**
 * Template Name: Welcome Template
 */

get_header();
?>

<script>
    <?php echo get_field('script_text_area'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored raw JavaScript output inside a <script> tag; esc_html()/wp_kses_post() would corrupt the script instead of escaping HTML. ?>
</script>

<?php
    $current_user = wp_get_current_user();
    if ( 0 == $current_user->ID ) {
        // Not logged in.
    } else {
        // $user_info = get_userdata($current_user);
        $first_name = $current_user->first_name;
        $last_name = $current_user->last_name;
        $user_email = $current_user->user_email;
        $interests = $current_user->mepr_interests;
    }
?>

<main id="main" role="main" class="welcome">
	<section class="welcome">
        <div class="container">
            <div class="right">
                <?php if( get_field('vimeo_code')){ ?>
                    <a href="https://vimeo.com/<?php echo esc_attr( get_field('vimeo_code') ); ?>" class="image popup-vimeo">
                <?php } else { ?>
                    <a href="#" class="image <?php if (get_field('video_url')){ ?>postPlayBtn<?php } ?>">
                <?php } ?>
                    <div class="imageSizeContainer">
                        <span class="overlayGradient"></span>
                        <div class="bgContainer">
                            <?php $image = get_field('video_thumbnail'); ?>
                            <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => '', 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
								}
							?>
                        </div>
                        <span class="watchIcon"></span>
                    </div>
                </a>
            </div>
            <div class="left">
                <span class="welcome-message">
                    Welcome, <br/><?php echo esc_html( $first_name ); ?>
                </span>
                <span class="message">
                    <?php echo esc_html( get_field('welcome_message') ); ?>
                </span>
                <span class="button-block">
                    <?php if(get_field('button_link')){ ?>
                    <a <?php if(get_field('button_link')){ ?>href="<?php echo esc_url( get_field('button_link') ); ?>"<?php } ?> class="stdBtn red" target="_self"><?php echo esc_html( get_field('button_text') ); ?></a>
                    <?php } ?>
                    <?php if( get_field('how_to_get_started_link_text')){ ?>
                        <a href="https://vimeo.com/<?php echo esc_attr( get_field('vimeo_code') ); ?>" class="popup-vimeo stdBtn red" target="_self"><?php echo esc_html( get_field('how_to_get_started_link_text') ); ?></a>
                    <?php } ?>
                </span>
            </div>
            <?php if (get_field('video_url')){ ?>
                <div class="videoPlayerContainer print-no">
                    <span class="closeVideo"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/close-grey.svg" width="25" height="25" loading="lazy" decoding="async" alt="Close" /></span>
                    <div class="videoWrapper">
                        <video width="100%" id="popupVideo" controls controlsList="nodownload">
                            <source type="video/mp4" src="<?php echo esc_url( get_field('video_url') ); ?>" />
                        </video>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
