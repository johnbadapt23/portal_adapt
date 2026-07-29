<?php
/**
 * Template Name: Case Study Template
 */

get_header();
?>


<main id="main" role="main" class="main-topic">

    <?php get_template_part( 'templates/components/_case-studies-banner' ); ?>
    <?php get_template_part( 'templates/components/_case-studies-featured-grid' ); ?>
    <?php get_template_part( 'templates/components/_case-studies-get-inspired' ); ?>
    <?php get_template_part( 'templates/components/_case-studies-featured-article-video-portal' ); ?>

</main>

<?php get_footer(); ?>
