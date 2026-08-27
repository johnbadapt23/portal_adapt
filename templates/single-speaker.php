
<?php if ( have_rows( 'introduction' ) ) : ?>
	<?php while ( have_rows( 'introduction' ) ) : the_row(); ?>
        <section class="advisors-introduction new-partners-introduction background-black">
            <div class="container">
                <div class="column-container-inner">
                    <div class="intro-column column-container">
                        <div class="column first-column image-column">
                            <div class="headshot-container">
                                <div class="image-container">
                                    <div class="bg-container">
                                        <?php $head_shot = get_sub_field( 'head_shot' ); ?>
                                        <?php if ( $head_shot ) { ?>
                                            <?php echo wp_get_attachment_image( $head_shot['ID'], 'full', false, array( 'alt' => $head_shot['alt'] ) ); ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>                            
                            <div class="mobile-title-container">
                                 <span class="advisor-title text-white header-medium"><?php echo get_sub_field( 'title' ); ?></span>
                                <span class="subtitle text-white labelMedium"><?php echo get_sub_field( 'sub_title' ); ?></span> 
                            </div>
                            <span class="links-container">
                                <?php $title = get_sub_field( 'title' ); ?>
                                <?php if ( have_rows( 'buttons' ) ) : ?>
                                    <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                        <a class="formPopupPartners stdBtn red red-button" href="#formPopupAdvisor">Request an Introduction</a>
                                        <span style="display: none;">
                                            <span class="form-popup" id="formPopupAdvisor">
                                                <span class="popup-form-container">
                                                    <span class="popup-form-title">Request an Introduction with <?php echo $title; ?></span>
                                                        <?php echo adapt_render_hubspot_embed( get_sub_field( 'form_embed_code' ) ); ?>
                                                    </span>
                                            </span>
                                        </span>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>                                                         
                            </span>
                        </div>
                        <div class="column second-column text-column">
                            <div class="text-content-inner">
                                <h1 class="advisor-title text-white header-large"><?php echo get_sub_field( 'title' ); ?></h1>
                                <span class="subtitle text-white labelMedium"><?php echo get_sub_field( 'sub_title' ); ?></span>                                              
                                <span class="text regular-text text-light-grey"><?php echo get_sub_field( 'text' ); ?></span>                               
                            </div>                            
                        </div>                        
                    </div>
                </div>
            </div>
        </section>
	<?php endwhile; ?>   
<?php else : ?>
	<?php // no rows found ?>
<?php endif; ?>
<?php
$resources_array = [];

// First: collect valid resources only
if ( have_rows( 'resources' ) ) :
    while ( have_rows('resources') ) : the_row();
        $resource_post_id = get_sub_field('resource'); // post ID
        if ( $resource_post_id ) {
            $featured = get_field('featured', $resource_post_id); // true/false
            $resources_array[] = [
                'id'       => $resource_post_id,
                'featured' => $featured ? 1 : 0,
            ];
        }
    endwhile;
endif;
?>
<section class="partners-content-module" id="partnerContent">
    <div class="container">
        <div class="column-container-inner">
            <div class="partners-content-column-container column-container">
                <div class="column first-column">                    
                    <?php if ( get_field ( 'linked_in_url' ) ) { ?>
                        <a class="linkedIn" href="<?php echo get_field('linked_in_url'); ?>" target="_blank"></a>
                    <?php } ?>                  
                </div>
                <div class="column second-column content-column">
                     <div class="menu-item-container">
                        <?php $switchCounter = 1; ?>
                        <?php if ( have_rows( 'about_advisor' ) ) : ?>
                            <?php while ( have_rows( 'about_advisor' ) ) : the_row(); ?>
                                <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#aboutAdvisor">About</a>
                                <?php $switchCounter++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>                           
                         <?php if ( ! empty( $resources_array ) ) : ?>
                                <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#resourcesAdvisor">Contributed resources</a>                            
                                <?php $switchCounter++; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>                   
                    </div>
                    <div class="partners-switch-content">
                        <?php $contentCounter = 1; ?>
                        <?php if ( have_rows( 'about_advisor' ) ) : ?>
                            <div class="switch-content about about-advisor<?php if ($contentCounter == 1){?> active<?php } ?>" id="aboutAdvisor">
                                <?php while ( have_rows( 'about_advisor' ) ) : the_row(); ?>
                                    <h2 class="title overview-title"><?php echo get_sub_field( 'title' ); ?></h2>
                                    <?php if( get_sub_field( 'vimeo_code' )) { ?>
                                        <div class="advisor-video">
                                            <div class="image-container video-container">                            
                                                <div class="bg-container">
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
                                    <?php } ?> 
                                    <div class="text description-container text-regular">
                                        <?php echo get_sub_field( 'descriptions' ); ?>
                                    </div>
                                    <?php $contentCounter++; ?>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>                        
                        <?php if ( ! empty( $resources_array ) ) : ?>
                            <?php $per_page = 6; // Number of resources per "page" ?>
                            <div class="switch-content resources<?php if ($contentCounter == 1){ ?> active<?php } ?>" id="resourcesAdvisor">
                                <div class="resources-column-container gap-16-40 two-column-container resources-advisor tablet-one-column"
                                data-page="1"
                                data-per-page="<?php echo $per_page; ?>"
                                data-total="<?php echo count($resources_array); ?>"
                                data-post-id="<?php the_ID(); ?>">

                                    <?php
                                    $page = 1; // Initial page
                                    // Sort featured first
                                    usort($resources_array, function($a, $b){
                                        return $b['featured'] - $a['featured'];
                                    });
                                    $initial_resources = array_slice($resources_array, 0, $per_page);

                                    // Output each resource
                                    foreach ($initial_resources as $resource) {
                                            $post_id = $resource['id'];

                                            $post = get_post($post_id);
                                            setup_postdata($post);

                                            $post_slug = get_post_field('post_name', $post_id);
                                            $extra_classes = 'one-half';

                                            include locate_template('/templates/components/_article-card.php');
                                        }

                                        wp_reset_postdata();
                                    ?>

                                </div>
                                <?php if (count($resources_array) > $per_page): ?>
                                    <a class="resources-load-more std-button red-button small-button">Load More</a>
                                    <div class="ajax-loader" style="display: none;">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ajax-loading.gif" width="200" height="200" loading="lazy" alt="Loading..." />
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php $contentCounter++; ?>

                        <?php endif; ?>                                       
                    </div>                    
                </div>
            </div>           
        </div>
    </div>
</section>