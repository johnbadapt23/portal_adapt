<footer>
    <section class="footer">
        <a href="#" class="backTop"></a>
        <span class="top">
            <span class="social mobile">
                <?php if(get_field('linkedin_url','options')) { ?>
                    <a href="<?php the_field('linkedin_url','options') ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin.svg" width="33" alt="LinkedIn" />
                    </a>
                <?php } ?>
                <?php if(get_field('youtube_url','options')) { ?>
                    <a href="<?php the_field('youtube_url','options') ?>" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/youtube.svg" alt="YouTube" width="33" />
                    </a>
                <?php } ?>
            </span>
            <div class="container">
                <div class="column">
                    <div><?php the_field('column_one','options'); ?></div>
                    <div><?php the_field('column_two','options'); ?></div>
                    <div><?php the_field('column_three','options'); ?></div>
                </div>
                <div class="column">
                    <span class="logo">
                        <img src="<?php the_field('footer_logo','options'); ?>" width="<?php the_field('footer_logo_width','options'); ?>" />
                    </span>
                    <span class="form">
                        <?php the_field('subscribe_form','options'); ?>
                    </span>
                    <span class="social desktop">
                        <?php if(get_field('linkedin_url','options')) { ?>
                            <a href="<?php the_field('linkedin_url','options') ?>" target="_blank">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin.svg" alt="LinkedIn" width="25" />
                            </a>
                        <?php } ?>
                        <?php if(get_field('youtube_url','options')) { ?>
                            <a href="<?php the_field('youtube_url','options') ?>" target="_blank">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/youtube.svg" alt="YouTube" width="25" />
                            </a>
                        <?php } ?>
                    </span>
                </div>
            </div>
        </span>
        <span class="base">
            <div class="container">
                <div>
                    <span>Copyright &copy; <?php echo date('Y'); ?></span>
                    <?php theme_nav('bottom'); ?>
                </div>
            </div>
        </span>
    </section>
</footer>
