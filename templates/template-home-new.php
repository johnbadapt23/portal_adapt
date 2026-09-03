<?php
/**
 * Template Name: Home Template New
 */

get_header();
?>


<main id="main" role="main" class="home">

	<?php if ( have_rows( 'content_blocks' ) ): ?>
        <div class="contentBlocks">
        	<?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
        		<?php if ( get_row_layout() == 'featured-slider-portal' ) : ?>
                    <?php get_template_part( 'templates/components/_featured-slider-portal' ); ?>
                <?php elseif ( get_row_layout() == 'featured-grid-portal' ) : ?>
                	<?php get_template_part( 'templates/components/_featured-grid-portal' ); ?>
				<?php elseif ( get_row_layout() == 'topic-grid-portal' ) : ?>
					<?php get_template_part( 'templates/components/_topic-grid-portal' ); ?>
				<?php elseif ( get_row_layout() == 'case-studies-featured-article-text-portal' ) : ?>
					<?php get_template_part( 'templates/components/_case-studies-featured-article-text-portal' ); ?>
				<?php elseif ( get_row_layout() == 'case-studies-featured-article-video-portal' ) : ?>
					<?php get_template_part( 'templates/components/_case-studies-featured-article-video-portal' ); ?>
				<?php elseif ( get_row_layout() == 'event-slider-portal' ) : ?>
					<?php get_template_part( 'templates/components/_event-slider-portal' ); ?>

        		<?php endif; ?>
        	<?php endwhile; ?>
        </div>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
