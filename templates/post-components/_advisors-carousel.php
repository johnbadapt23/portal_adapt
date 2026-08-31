
			
		

<section class="advisors-carousel dark-theme bg-black">
    <div class="container">
        <div class="title-text-container">
            <h2 class="headerXsmall text-bold white-text"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
            <span class="text text-regular white-text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
            <?php if ( have_rows( 'button' ) ) : ?>
				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                    <span class="button-container desktop">
                        <a class="std-button red-button small-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                    </span>														
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
            
        </div>
    </div>
        <?php $partner_type_id = get_sub_field( 'partner_type' ); ?>
        <?php 
        $carousel_posts = []; // Store all posts for output & width calculation

        // ---------- LOOP 1: Checked adapt_analyst ----------
        $checked_args = [
            'no_found_rows'  => true,
            'post_type'      => 'partners',
            'posts_per_page' => -1,
            'tax_query'      => [[
                'taxonomy' => 'partner-type',
                'field'    => 'term_id',
                'terms'    => $partner_type_id,
            ]],
            'orderby' => 'menu_order',
            'order'   => 'ASC',
        ];

        $checked_query = new WP_Query($checked_args);
        if ($checked_query->have_posts()):
            $carousel_posts = array_merge($carousel_posts, $checked_query->posts);
        endif;
        wp_reset_postdata();


        // ---------- CALCULATE CAROUSEL WIDTH & ANIMATION ----------
        $speakers_count = count($carousel_posts);
        $carousel_width = $speakers_count * 260; // adjust width per item
        $animation_duration = $speakers_count * 5; // seconds

        // ---------- OUTPUT CAROUSEL ----------
        if ($speakers_count > 0): ?>
            <div class="carousel-wrapper" style="overflow: hidden;">
                <div class="carousel-container" 
                     style="width: <?php echo esc_attr( $carousel_width ); ?>px; animation-duration: <?php echo esc_attr( $animation_duration ); ?>s;">
                        <?php foreach ($carousel_posts as $post) : ?>
                            <?php
                            setup_postdata($post);

                            $post_id = $post->ID;
                            include locate_template('/templates/partners-components/_partner-card.php');
                            ?>
                        <?php endforeach; wp_reset_postdata(); ?>

                </div>
            </div>
        <?php endif; ?>
        <div class="container">
            <?php if ( have_rows( 'button' ) ) : ?>
				<?php while ( have_rows( 'button' ) ) : the_row(); ?>
                    <span class="button-container mobile">
                        <a class="std-button red-button small-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                    </span>														
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
        </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carouselContainer = document.querySelector('.carousel-container');
        const carouselItems = carouselContainer.innerHTML;

        // Duplicate items to create the infinite effect
        carouselContainer.innerHTML += carouselItems;

        // Adjust speed if necessary based on total width
        const totalWidth = carouselContainer.offsetWidth;
        const speed = totalWidth / 30; // Adjust as necessary for speed
        carouselContainer.style.animationDuration = `${speed}s`;
    });
</script>