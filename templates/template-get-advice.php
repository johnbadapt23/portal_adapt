<?php
/**
 * Template Name: Get Advice Template
 */

get_header();
?>

<main id="main" role="main" class="home">
    <?php if ( have_rows( 'content_blocks' ) ): ?>
        <div class="contentBlocks">
            <?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
                <?php if ( get_row_layout() == 'featured_slider_portal' ) : ?>
                    <?php get_template_part( 'templates/components/_featured-slider-portal' ); ?>
                <?php elseif ( get_row_layout() == 'featured_grid_portal' ) : ?>
                    <?php get_template_part( 'templates/components/_featured-grid-portal' ); ?>
                <?php elseif ( get_row_layout() == 'featured_topic' ) : ?>
                    <?php get_template_part( 'templates/components/_topic-grid-portal' ); ?>
                <?php elseif ( get_row_layout() == 'case_study_highlight' ) : ?>
                    <?php get_template_part( 'templates/components/_case-studies-featured-article-text-portal' ); ?>
                <?php elseif ( get_row_layout() == 'case_study_highlight_with_video' ) : ?>
                    <?php get_template_part( 'templates/components/_case-studies-featured-article-video-portal' ); ?>
                <?php elseif ( get_row_layout() == 'expert_presentations_slider' ) : ?>
                    <?php get_template_part( 'templates/components/_event-slider-portal' ); ?>
                <?php elseif ( get_row_layout() == 'contact_block' ) : ?>
                    <?php get_template_part( 'templates/components/_contact-block' ); ?>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
