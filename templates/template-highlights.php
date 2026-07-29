<?php
/**
 * Template Name: Edge Highlights Template
 */

get_header();
?>


<?php if ($membershipType == 'advantage') { ?>
	<main id="main" role="main" class="home noBanner advantageHome">
		<?php if ( have_rows( 'it_pro_home_content_blocks' ) ): ?>
			<?php while ( have_rows( 'it_pro_home_content_blocks' ) ) : the_row(); ?>
				<?php if ( get_row_layout() == 'featured_posts' ) : ?>
					<?php get_template_part( 'templates/post-components/_highlights-featured-block' ); ?>
				<?php elseif ( get_row_layout() == 'slider_block' ) : ?>
					<?php get_template_part( 'templates/post-components/_keynote-slider' ); ?>
				<?php elseif ( get_row_layout() == 'post_slider' ) : ?>
					<?php get_template_part( 'templates/post-components/_post-slider-highlights' ); ?>
				<?php elseif ( get_row_layout() == 'upcoming_events' ) : ?>
					<?php get_template_part( 'templates/post-components/_events-slider' ); ?>
				<?php elseif ( get_row_layout() == 'featured_post' ) : ?>
					<?php get_template_part( 'templates/post-components/_highlight-post' ); ?>
				<?php elseif ( get_row_layout() == 'title_block' ) : ?>
					<?php get_template_part( 'templates/post-components/_title-block' ); ?>
				<?php endif; ?>
			<?php endwhile; ?>
		<?php else: ?>
			<?php // no layouts found ?>
		<?php endif; ?>
	</main>
<?php } else { ?>
	<main id="main" role="main" class="home noBanner advantageHome professionalHome">
		<?php if ( have_rows( 'it_pro_home_content_blocks' ) ): ?>
			<?php while ( have_rows( 'it_pro_home_content_blocks' ) ) : the_row(); ?>
				<?php if ( get_row_layout() == 'featured_posts' ) : ?>
					<?php get_template_part( 'templates/post-components/_highlights-featured-block' ); ?>
				<?php elseif ( get_row_layout() == 'slider_block' ) : ?>
					<?php get_template_part( 'templates/post-components/_keynote-slider' ); ?>
				<?php elseif ( get_row_layout() == 'post_slider' ) : ?>
					<?php get_template_part( 'templates/post-components/_post-slider-highlights' ); ?>
				<?php elseif ( get_row_layout() == 'upcoming_events' ) : ?>
					<?php get_template_part( 'templates/post-components/_events-slider' ); ?>
				<?php elseif ( get_row_layout() == 'featured_post' ) : ?>
					<?php get_template_part( 'templates/post-components/_highlight-post' ); ?>
				<?php elseif ( get_row_layout() == 'title_block' ) : ?>
					<?php get_template_part( 'templates/post-components/_title-block' ); ?>
				<?php endif; ?>
			<?php endwhile; ?>
		<?php else: ?>
			<?php // no layouts found ?>
		<?php endif; ?>
	</main>
<?php } ?>


<?php get_footer(); ?>
