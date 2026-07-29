<?php
/**
 * Template Name: Ecosystem Partners Listing Template
 */

get_header();
?>

<main id="main" role="main" class="evr ecosystems">

<?php if ( have_rows( 'content' ) ): ?>
	<?php while ( have_rows( 'content' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'title_block' ) : ?>
			<?php get_template_part( 'templates/partners-components/_title-module' ); ?>
		<?php elseif ( get_row_layout() == 'video_introduction' ) : ?>
			<?php get_template_part( 'templates/components/_ecosystem-video' ); ?>
		<?php elseif ( get_row_layout() == 'listing' ) : ?>
			<?php get_template_part( 'templates/partners-components/_speakers-module' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>

</main>

<?php get_footer(); ?>
