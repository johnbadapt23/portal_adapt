<section class="announcementArticleTextHeader dashboardArticleTextHeader bg-white">
    <div class="container">
        <div class="textContainer">
            <div class="text-container-inner">
                <span class="topicFilter">
                    <a href="/interactive-dashboards/" class="topicFilterText">Interactive Dashboards</a>
                </span>
                <span class="title"><?php echo esc_html( get_the_title() ); ?></span>
                <hr>
            </div>
        </div>
    </div>
</section>

<section class="webinar-article announcement-article contained-article dashboard-article bg-white">
    <div class="container">
        <div class="announcement-content">
            <span class="webinar-content content">
                <?php the_content(); ?>
            </span>
            <?php if ( get_field('embed')) { ?>
                <span class="webinar-content content embed-content">
                    <span class="full-screen-scrolldown"><span class="full-screen-icon-container"></span><span class="v-wrap"><span class="v-box">Full Screen mode is recommended <br>for the best experience.</span></span></span>
                    <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- embed is an admin-authored embed field (iframe/script markup) that wp_kses_post() would strip. ?>
                    <?php echo get_field('embed');?>
                    <span class="full-screen-prompt">View on Full Screen</span>
                </span>
            <?php } ?>
        </div>
    </div>
</section>
