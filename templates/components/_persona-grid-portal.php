<?php
// _persona-grid-portal-markets.php is a near-identical component that only
// differs in the data-insights vs market-narratives URL prefix and
// filter-types taxonomy term used below - rather than maintain two full
// copies of this markup, it sets $section and includes this file directly.
$section = $section ?? 'data-insights';
$sector_term = get_sub_field( 'persona' );
?>

<section class="topicGrid portal sector-grid">
    <div class="container">
        <div class="blockTitle">
            <h2><?php echo esc_html( get_sub_field( 'persona_title' ) ); ?></h2>
            <a href="/<?php echo esc_attr( $section ); ?>/persona-mapping/?persona=<?php echo esc_attr( $sector_term->slug ); ?>" class="viewAll">View All</a>
        </div>
        <div class="gridWrapper">
            <?php
                $args = [
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                    'tax_query'      => [
                        'relation'    => 'AND',
                        [
                            'taxonomy' => 'persona-mapping',
                            'field'    => 'slug',
                            'terms'    => $sector_term->slug
                        ],
                        [
                            'taxonomy' => 'filter-types',
                            'field'    => 'slug',
                            'terms'    => $section
                        ],
                    ],
                ];

                $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <div href="<?php the_permalink(); ?>" class="item">
                            <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                <div class="bgContainer">
                                    <?php if ( get_field( 'listing_image') ) { ?>
                                        <?php $image = get_field( 'listing_image'); ?>
                                    <?php } else { ?>
                                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                            <?php $image = get_field( 'video_poster'); ?>
                                        <?php } else { ?>
                                            <?php $image = get_field( 'featured_image'); ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ( have_rows( 'preview_module' ) ) : ?>
                                       <?php while ( have_rows( 'preview_module' ) ) : the_row(); ?>
                                           <?php if ( have_rows( 'slider_images' ) ) : ?>
                                               <?php $imageCounter = 1; ?>
                                               <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                   <?php if($imageCounter == 1){
                                                       $image = get_sub_field( 'image' );
                                                   }
                                                   $imageCounter++; ?>
                                               <?php endwhile; ?>
                                           <?php else : ?>
                                               <?php // no rows found ?>
                                           <?php endif; ?>
                                        <?php endwhile; ?>
                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ] ); ?>
                                        <span class="hover-container">
                                            <?php if ($imageCounter) { ?>
                                                <span class="slide-counter">1 OF <?php echo esc_html( $imageCounter ); ?></span>
                                            <?php } ?>
                                        <span>
                                    <?php else : ?>
                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => esc_attr( get_the_title() ), 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
								}
							?>
                                        <span class="hover-container">
                                        <span>
                                    <?php endif; ?>

                                </div>
                            </a>
                            <div class="textContainer">
                                <span class="topicFilter">
                                    <a href="/<?php echo esc_attr( $section ); ?>/persona-mapping/" class="topicFilterText">Persona Mapping</a>
                                    <a href="/<?php echo esc_attr( $section ); ?>/persona-mapping/?persona=<?php echo esc_attr( $sector_term->slug ); ?>" class="topicFilterText"><?php echo esc_html( $sector_term->name ); ?></a>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
                                <span class="dateReadTime"><?php echo esc_html( get_the_date('M j, Y') ); ?></span>
                                <span class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) );?></span>
                                <a href="<?php the_permalink(); ?>" class="button data-set-button">View Dataset</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif;?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>
