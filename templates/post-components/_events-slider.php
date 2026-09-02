<?php if(get_sub_field( 'background_colour' )){ ?>
    <?php if(get_sub_field( 'background_colour' ) == 'light-theme') { 
        $background = 'light-theme bg-white';
    } else {
        $background = 'dark-theme bg-black';
    } ?>
<?php } else { ?>
    <?php $background = 'dark-theme bg-black'; ?>
<?php } ?>

<section class="upcoming-events-slider-section <?php echo esc_attr( $background ); ?>">
    <div class="container">
        <div class="title-slider-container">
            <div class="column title-column">
                <h2 class="headerXsmall text-bold bold-red">
                    <?php echo wp_kses_post( get_sub_field( 'title' ) ); ?>
                </h2>
                <span class="text-regular white-text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                <?php if ( have_rows( 'button' ) ) : ?>
                    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                        <span class="button-container">
                            <a class="std-button white-outline small-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                        </span>														
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
            <div class="column slider-column">
                <span class="leftslideCover"></span>
                <span class="rightslideCover"></span>
                <div class="upcoming-events-slider">
                    <?php if( get_sub_field( 'pick_events' ) == 'pick'){ ?> 
                        <?php if ( have_rows( 'events' ) ) : ?>
                            <?php while ( have_rows( 'events' ) ) : the_row(); ?>
                                <?php $post_object = get_sub_field( 'event' ); ?>
                                <?php if ( $post_object ): ?>
                                    <?php $post = $post_object; ?>
                                    <?php setup_postdata( $post ); ?> 
                                    <?php 
                                        $post_id   = get_the_ID();
                                        $post_slug = get_post_field('post_name', $post_id);
                                        $extra_classes = 'dark-theme';
                                        include locate_template('/templates/components/_event-card-image.php');
                                    ?>
                                    <?php wp_reset_postdata(); ?>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    <?php } else { ?> 
                        <?php
                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                            $args = [
                                'post_type' => 'event',
                                'posts_per_page' => -1,
                                'no_found_rows' => true,
                                'orderby'=> 'menu_order',
                                'order'=> 'ASC',
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
                            

                            $loop = new WP_Query( $args );
                            if ( $loop->have_posts() ) :
                            while ( $loop->have_posts() ) : $loop->the_post();
                        ?>
                            <?php 
                                $post_id   = get_the_ID();
                                $post_slug = get_post_field('post_name', $post_id);
                                $extra_classes = 'dark-theme';
                                include locate_template('/templates/components/_event-card-image.php');
                            ?>
                            <?php $counter++; ?>
                        <?php endwhile; else : ?>
                            <h3><?php esc_html_e( 'Sorry, no results found.', 'portal' ); ?></h3>
                        <?php endif; ?>

                        <?php wp_reset_postdata(); wp_reset_query();?>
                    <?php } ?>
                </div>
                <div class="event-slider-control-container">
                    <div class="event-slider-controls">
                        <div class="event-slider-dots"></div>
                        <div class="event-slider-arrows"></div>            
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

			



