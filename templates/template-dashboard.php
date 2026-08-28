<?php $current_user = wp_get_current_user();
// echo $current_user;
if ( 0 == $current_user->ID ) {
    header("Location: https://research.adapt.com.au/login/");
    exit;
} ?>
<?php $banner_image = get_field( 'dashboard_banner_image', 'options'); ?>
    <section class="eventsBanner topicBanner dashboardBanner" style="background-image:url(<?php echo $banner_image['url']; ?>); background-size: cover; background-position: center;">
        <div class="container">
            <span class="bannerBreadcrumbs">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb">Home</a><span class="divider">/</span><span class="breadcrumb">Interactive Dashboards</span></a>
            </span>
            <h1><?php echo get_field( 'dashboards_title', 'options' ); ?></h1>
            <p><?php echo get_field( 'dashboards_sub_title', 'options' ); ?></p>
        </div>
    </section>

    <section class="blogWrapper post-events insightsArticleWrapper">
        <div class="container">
            <div id="loop" class="grid">
                <?php $counter = -1; ?>
                <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $args = array(
                        'post_type' => 'dashboard',
                        'posts_per_page' => -1,
                        'paged'=> $paged ,
                        'orderby'=> 'menu_order',
                        'order'=> 'ASC'
                    );

                    $loop = new WP_Query( $args );
                    if ( $loop->have_posts() ) :
                    while ( $loop->have_posts() ) : $loop->the_post();
                ?>
                    <?php if(current_user_can('mepr_auth')) {?>
                        <a href="<?php the_permalink(); ?>" class="postLink" target="_self">
                            <div class="linkWrapper">
                                <span class="blogText">
                                    <span class="top articleTop">
                                        <span class="articleLink"><?php echo esc_html( get_the_title() ); ?></span>
                                    </span>
                                </span>
                                <div class="imageContainer">
                                    <div class="image" style="background-image: url('<?php echo get_field( 'listing_page_grid_image' ); ?>');">
                                    </div>
                                </div>
                                <span class="blogText">
                                    <span class="bottom">
                                        <span class="excerpt">
                                            <?php echo get_field('short_description_for_listing'); ?>
                                        </span>
                                        <span class="viewAll">Learn More</span>
                                    </span>
                                </span>
                            </div>
                        </a>
                        <?php $counter++; ?>
                    <?php } ?>

                <?php endwhile; else : ?>
                	<h2 class="h3"><?php esc_html_e( 'Sorry, no results found.' ); ?></h2>
                <?php endif; ?>

                <?php wp_reset_postdata(); wp_reset_query();?>

            </div>

        </div>
    </section>
</main>

<?php get_footer(); ?>
