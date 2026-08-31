<?php
/**
 * Template Name: Events Portal Template
 */

get_header();
?>

<main id="main" role="main" class="events">
<?php // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only GET search param for a bookmarkable, shareable events-listing URL, used only as a WP_Query 's' search term below; no state change. ?>
<?php $keyword = $_GET['searchWords']; ?>
    <section class="title-banner dark-theme">
        <div class="container">
            <h1 class="header-large mobile-header-medium"><?php echo esc_html( get_field( 'title' ) ); ?></h1>
            <p><?php echo esc_html( get_field( 'subtitle' ) ); ?></p>
        </div>
    </section>

    <section class="post-events dark-theme">
        <div class="container">
            <div class="events-column-container three-column-container gap-16-40">
                <?php $counter = -1; ?>
                <?php
                    // Read by templates/components/_event-card.php: only the
                    // first card in this grid (the one rendered above the
                    // fold, at the top of this section) gets
                    // fetchpriority=high/eager and skips lazy-load - it's
                    // this page's LCP element.
                    $hero_fetchpriority_used = false;
                ?>
                <?php

                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    // no_found_rows: this loop never paginates (posts_per_page
                    // is -1 and nothing here reads $loop->max_num_pages or
                    // found_posts), so the SQL_CALC_FOUND_ROWS query
                    // WP_Query runs by default to support pagination is pure
                    // overhead on every load - skip it.
                    if($keyword != '') {
                        $args = [
                            'post_type' => 'event',
                            's' => $keyword,
                            'posts_per_page' => -1,
                            'paged'=> $paged ,
                            'orderby'=> 'menu_order',
                            'order'=> 'ASC',
                            'no_found_rows' => true,
                            'tax_query' => [
                                'relation' => 'AND',
                                 [
                                    'taxonomy' => 'event-type',
                                    'field' => 'slug',
                                    'terms'    => 'upcoming-events',
                                    'operator' => 'IN'
                                ]
                            ]
                        ];
                    } else {
                        $args = [
                            'post_type' => 'event',
                            'posts_per_page' => -1,
                            'paged'=> $paged ,
                            'orderby'=> 'menu_order',
                            'order'=> 'ASC',
                            'no_found_rows' => true,
                            'tax_query' => [
                                'relation' => 'AND',
                                 [
                                    'taxonomy' => 'event-type',
                                    'field' => 'slug',
                                    'terms'    => 'upcoming-events',
                                    'operator' => 'IN'
                                ]
                            ]
                        ];
                    }

                    $loop = new WP_Query( $args );
                    // locate_template() does a filesystem file_exists() check
                    // per call (across every registered theme in the stack) -
                    // resolving it once outside the loop instead of once per
                    // event avoids repeating that lookup on every iteration.
                    $event_card_template = locate_template( '/templates/components/_event-card.php' );
                    if ( $loop->have_posts() ) :
                    while ( $loop->have_posts() ) : $loop->the_post();
                ?>
                    <?php
                        $post_id   = get_the_ID();
                        $post_slug = get_post_field('post_name', $post_id);
                        $extra_classes = 'dark-theme';
                        include $event_card_template;
                    ?>
                    <?php $counter++; ?>
                <?php endwhile; else : ?>
                	<h2 class="h3"><?php esc_html_e( 'Sorry, no results found.', 'portal' ); ?></h2>
                <?php endif; ?>

                <?php wp_reset_postdata(); wp_reset_query();?>

            </div>

        </div>
    </section>
</main>

<?php get_footer(); ?>
