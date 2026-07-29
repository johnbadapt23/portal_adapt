<?php
/**
 * Template Name: Ecosystem Partners Landing Template
 */

get_header();
?>

<main id="main" role="main" class="evr ecosystems">

<?php if ( have_rows( 'content' ) ): ?>
	<?php while ( have_rows( 'content' ) ) : the_row(); ?>
		<?php if ( get_row_layout() == 'video_introduction' ) : ?>
			<?php get_template_part( 'templates/components/_ecosystem-video' ); ?>
		<?php elseif ( get_row_layout() == 'types' ) : ?>
            <?php get_template_part( 'templates/components/_ecosystems-types' ); ?>			
		<?php elseif ( get_row_layout() == 'capabilitites' ) : ?>
            <?php get_template_part( 'templates/components/_ecosystem-capabilities' ); ?>	
		<?php elseif ( get_row_layout() == 'numbered_steps' ) : ?>
			<?php get_template_part( 'templates/components/_ecosystems-numbered-blocks' ); ?>
		<?php elseif ( get_row_layout() == 'testimonials_slider' ) : ?>	
			<?php get_template_part( 'templates/components/_ecosystem-testimonials' ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php else: ?>
	<?php // no layouts found ?>
<?php endif; ?>

</main>

<?php get_footer(); ?>
