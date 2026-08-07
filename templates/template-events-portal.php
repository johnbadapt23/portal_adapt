<?php
/**
 * Template Name: Events Portal Template
 */

get_header();
?>

<main id="main" role="main" class="events">
<?php $keyword = $_GET['searchWords']; ?>
    <section class="title-banner dark-theme">
        <div class="container">
            <h1 class="header-large mobile-header-medium"><?php echo get_field( 'title' ); ?></h1>
            <p><?php echo get_field( 'subtitle' ); ?></p>
        </div>
    </section>

    <section class="post-events dark-theme">
        <div class="container">
            <div class="events-column-container three-column-container gap-16-40">
                <?php $counter = -1; ?>
                <?php

                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    if($keyword != '') {
                        $args = array(
                            'post_type' => 'event',
                            's' => $keyword,
                            'posts_per_page' => -1,
                            'paged'=> $paged ,
                            'orderby'=> 'menu_order',
                            'order'=> 'ASC',
                            'tax_query' => array(
                                'relation' => 'AND',
                                array (
                                    'taxonomy' => 'event-type',
                                    'field' => 'slug',
                                    'terms'    => 'upcoming-events',
                                    'operator' => 'IN'
                                )
                            )
                        );
                    } else {
                        $args = array(
                            'post_type' => 'event',
                            'posts_per_page' => -1,
                            'paged'=> $paged ,
                            'orderby'=> 'menu_order',
                            'order'=> 'ASC',
                            'tax_query' => array(
                                'relation' => 'AND',
                                array (
                                    'taxonomy' => 'event-type',
                                    'field' => 'slug',
                                    'terms'    => 'upcoming-events',
                                    'operator' => 'IN'
                                )
                            )
                        );
                    }

                    $loop = new WP_Query( $args );
                    if ( $loop->have_posts() ) :
                    while ( $loop->have_posts() ) : $loop->the_post();
                ?>
                    <?php 
                        $post_id   = get_the_ID();
                        $post_slug = get_post_field('post_name', $post_id);
                        $extra_classes = 'dark-theme';
                        include locate_template('/templates/components/_event-card.php');
                    ?>
                    <?php $counter++; ?>
                <?php endwhile; else : ?>
                	<h2 class="h3"><?php esc_html_e( 'Sorry, no results found.' ); ?></h2>
                <?php endif; ?>

                <?php wp_reset_postdata(); wp_reset_query();?>

            </div>

        </div>
    </section>
</main>

<?php get_footer(); ?>
