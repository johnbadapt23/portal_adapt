<?php $purchasedargs = array(
    'posts_per_page' => -1,
    'post_type' => 'kyc'
);	
$counter = 0;
$purchasedLoop = new WP_Query( $purchasedargs  );	
if ( $purchasedLoop->have_posts() ) :    
    while ( $purchasedLoop->have_posts() ) : $purchasedLoop->the_post(); ?>
    <?php if(current_user_can('mepr_auth')) {?>
        <?php $counter++; ?>
    <?php } ?>
    <?php endwhile; else : ?>
<?php 
endif; ?>


<section class="two-column-services kyc-video-introduction kyc-landing-introduction landing-video-intro background-white">
    <div class="container">
        <div class="landing-video-intro-columns">
            <div class="column one-half text-column">
                <div class="text-content-inner">                                  
                    <span class="bannerBreadcrumbs">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb">Home</a><span class="divider">/</span><span class="breadcrumb"><?php the_title(); ?></span>
                    </span>              
                    <h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
                    <span class="text"><?php echo get_sub_field( 'text' ); ?></span>
                    <span class="links-container desktop">
                        <?php if ( have_rows( 'button' ) ) : ?>
                            <?php $buttonCounter = 1; ?>
                            <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                                <?php if(get_sub_field( 'link_type' ) == 'scroll-to') { ?>
                                    <a class="scroll-to-button stdBtn <?php if($buttonCounter == 1){?>red red-button<?php } else { ?>red-outline-button<?php } ?>" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } elseif(get_sub_field( 'link_type' ) == 'link')  { ?>
                                    <a class="link stdBtn <?php if($buttonCounter == 1){?>red red-button<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                <?php } else { ?>
                                    <?php if($counter <= 0){ ?> 
                                        <span class="form-popup-button-container stdBtn <?php if($buttonCounter == 1){?>red red-button<?php } else { ?>red-outline-button<?php } ?>">
                                            <?php echo get_sub_field( 'form_button' ); ?>
                                        </span>
                                        <span class="popup-formcrafts"><?php echo get_sub_field( 'form_code' ); ?></span>
                                    <?php } ?>                                    
                                <?php } ?>
                                <?php $buttonCounter++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <div class="column one-half video-column">
                <div class="video-container image-container">
                    <span class="frame"></span>
                    <div class="bg-container">
                        <?php $image = get_sub_field('poster_image'); ?>
                        <?php $poster_image = get_sub_field( 'poster_image' ); ?>
                        <?php if ( $poster_image ) { ?>
                            <?php echo wp_get_attachment_image( $poster_image['ID'], 'full', false, array( 'alt' => $poster_image['alt'] ) ); ?>
                        <?php } ?>  
                        <?php if( get_sub_field( 'vimeo_code' )) { ?>
                            <span class="opacity-overlay"></span>
                            <a class="popup-vimeo" href="https://vimeo.com/<?php echo get_sub_field('vimeo_code'); ?>"></a>
                        <?php } ?>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>

<?php wp_reset_postdata(); ?> 	
