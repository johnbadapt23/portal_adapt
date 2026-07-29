<h1 class="pageTitleLine"><?php echo the_title(); ?></h1>
<section class="default">
    <div class="container <?php echo get_field('content_width'); ?>">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; endif;  ?>
        <?php if ( get_field ( 'register_button_text' )) { ?>
            <span class="register-button-container">
                <a class="button" href="<?php echo get_field('register_button_link');?>" target="_self"><?php echo get_field('register_button_text');?></a>
            </span>
        <?php } ?>
    </div>
</section>
