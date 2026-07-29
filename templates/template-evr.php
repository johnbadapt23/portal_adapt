<?php
/**
 * Template Name: EVR Landing Template
 */

get_header();
?>

<main id="main" role="main" class="evr">

<?php if ( have_rows( 'content' ) ): ?>
	<?php while ( have_rows( 'content' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'video_introduction' ) : ?>
			<?php get_template_part( 'templates/components/_evr-video' ); ?>
		<?php elseif ( get_row_layout() == 'stages' ) : ?>
            <?php get_template_part( 'templates/components/_evr-stages' ); ?>			
		<?php elseif ( get_row_layout() == 'fundamentals' ) : ?>
            <?php get_template_part( 'templates/components/_evr-fundamentals' ); ?>						
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>

</main>

<?php get_footer(); ?>
