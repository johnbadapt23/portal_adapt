<?php $partnerType = get_field( 'partner_type' ); ?>
<?php
$resources_array = [];
$listing_icon = get_field('listing_icon');
$listing_avatar = get_field('listing_avatar');
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
<?php if ( have_rows( 'introduction' ) ) : ?>
	<?php while ( have_rows( 'introduction' ) ) : the_row(); ?>
        <section class="advisors-introduction new-partners-introduction background-black">
            <div class="container">
                <div class="column-container-inner">
                    <?php if ($partnerType != 'advisor'){ ?> 
                        <a class="back-button text-medium-grey" href="/marketplace/" target="_self">Back</a> 
                    <?php } else { ?> 
                        <a class="back-button text-medium-grey" href="/executive-advisors/" target="_self">Back</a>
                    <?php } ?>
                    <div class="intro-column column-container">
                        <div class="column first-column image-column">
                            <?php if ($partnerType != 'advisor'){ ?> 
                                <div class="logo-container">
                                    <div class="image-container">
                                        <div class="bg-container">
                                            <?php $head_shot = get_sub_field( 'poster_image' ); ?>
                                            <?php if ( $head_shot ) { ?>
                                                <?php echo wp_get_attachment_image( $head_shot['ID'], 'full', false, array( 'alt' => $head_shot['alt'] ) ); ?>
                                            <?php } else if($listing_icon) { ?>
                                                <img src="<?php echo $listing_icon; ?>" alt="<?php echo get_sub_field( 'title' ); ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?> 
                                <div class="headshot-container">
                                    <div class="image-container">
                                        <div class="bg-container">
                                            <?php $head_shot = get_sub_field( 'head_shot' ); ?>
                                            <?php if ( $head_shot ) { ?>
                                                <?php echo wp_get_attachment_image( $head_shot['ID'], 'full', false, array( 'alt' => $head_shot['alt'] ) ); ?>
                                            <?php } else if($listing_avatar) { ?>
                                                <img src="<?php echo $listing_avatar; ?>" alt="<?php echo get_sub_field( 'title' ); ?>" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>                            
                            <?php } ?>
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
                                                        <?php echo get_sub_field( 'form_embed_code' ); ?>
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

<section class="partners-content-module" id="partnerContent">
    <div class="container">
        <div class="column-container-inner">
            <div class="partners-content-column-container column-container">
                <div class="column first-column">
                    <div class="capabilities">
                        <span class="capability-label labelXXsmall">
                            <?php if ($partnerType !== 'advisor'){ ?>
                                Core Capabilities
                            <?php } else { ?>
                                Expertise 
                            <?php } ?>
                        </span>

                        <div class="tag-container">
                            <?php
                            $capability_terms = get_the_terms(get_the_ID(), 'capabilities');

                            if (!empty($capability_terms) && !is_wp_error($capability_terms)) {

                                // Determine base URL + query key
                                if ($partnerType === 'advisor') {
                                    $base_url  = site_url('/executive-advisors/');
                                    $query_key = 'expertise';
                                } else {
                                    $base_url  = site_url('/marketplace/');
                                    $query_key = 'capability';
                                }

                                foreach ($capability_terms as $capability_term) {

                                    $url = add_query_arg(
                                        $query_key,
                                        $capability_term->slug,
                                        $base_url
                                    );

                                    echo '<a href="' . esc_url($url) . '" class="tag">';
                                    echo esc_html($capability_term->name);
                                    echo '</a>';
                                }
                            }
                            ?>
                        </div>

                    </div>
                    <?php if ($partnerType === 'advisor') { ?>
                        <div class="industries">
                            <span class="industry-label labelXXsmall">
                                Industries
                            </span>

                            <div class="tag-container">
                                <?php
                                $industry_terms = get_the_terms(get_the_ID(), 'industries');

                                if (!empty($industry_terms) && !is_wp_error($industry_terms)) {

                                    // Base URL + query key
                                    $base_url  = site_url('/executive-advisors/');
                                    $query_key = 'industry';

                                    foreach ($industry_terms as $industry_term) {

                                        $url = add_query_arg(
                                            $query_key,
                                            $industry_term->slug,
                                            $base_url
                                        );

                                        echo '<a href="' . esc_url($url) . '" class="tag">';
                                        echo esc_html($industry_term->name);
                                        echo '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ( have_rows( 'who_we_help' ) ) : ?>
                        <?php while ( have_rows( 'who_we_help' ) ) : the_row(); ?>
                            <?php if ( have_rows( 'regions' ) ) : ?>
                                <div class="regions">
                                    <?php while ( have_rows( 'regions' ) ) : the_row(); ?>
                                        <span class="capability-label labelXXsmall">local region's we serve</span>
                                        <?php if ( have_rows( 'region' ) ) : ?>
                                            <div class="tag-container">
                                                <?php while ( have_rows( 'region' ) ) : the_row(); ?>
                                                    <span class="tag"><?php echo get_sub_field( 'region_name' ); ?></span>
                                                <?php endwhile; ?>
                                            </div>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </div>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                         
                </div>
                <div class="column second-column content-column">
                     <div class="menu-item-container">
                        <?php $switchCounter = 1; ?>
                        <?php if ($partnerType != 'advisor'){ ?> 
                            <?php if ( have_rows( 'about_company' ) ) : ?>
                                <?php while ( have_rows( 'about_company' ) ) : the_row(); ?>
                                    <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#aboutCompany">About</a>
                                    <?php $switchCounter++; ?>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                            <?php if ( have_rows( 'team' ) ) : ?>
                                <?php while ( have_rows( 'team' ) ) : the_row(); ?>
                                    <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#teams">Team</a>
                                    <?php $switchCounter++; ?>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>                            
                        <?php } else { ?> 
                            <?php if ( have_rows( 'about_advisor' ) ) : ?>
                                <?php while ( have_rows( 'about_advisor' ) ) : the_row(); ?>
                                    <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#aboutAdvisor">About</a>
                                    <?php $switchCounter++; ?>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>                           
                        <?php } ?>  
                         <?php if ( have_rows( 'resources' ) ) : ?>
                                <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#resourcesAdvisor">Contributed resources</a>
                            <?php while ( have_rows( 'resources' ) ) : the_row(); ?>                                   
                            <?php endwhile; ?>
                                <?php $switchCounter++; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?> 
                        <?php if ($partnerType != 'advisor'){ ?> 
                             <?php if ( have_rows( 'clients_module' ) ) : ?>
                                <?php while ( have_rows( 'clients_module' ) ) : the_row(); ?>
                                    <a class="partners-content-switch-trigger<?php if ($switchCounter == 1){?> active<?php } ?>" href="#clients">Clients</a>
                                    <?php $switchCounter++; ?>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>   
                        <?php } ?>                     
                    </div>
                    <div class="partners-switch-content">
                        <?php $contentCounter = 1; ?>
                        <?php if ($partnerType != 'advisor'){ ?> 
                            <?php if ( have_rows( 'about_company' ) ) : ?>
                                <div class="switch-content about about-company<?php if ($contentCounter == 1){?> active<?php } ?>" id="aboutCompany">
                                    <?php while ( have_rows( 'about_company' ) ) : the_row(); ?>
                                        <h3 class="h3 title overview-title"><?php echo get_sub_field( 'title' ); ?></h3>
                                        <div class="text description-container text-regular">
                                            <?php echo get_sub_field( 'descriptions' ); ?>
                                        </div>                                            
                                    <?php endwhile; ?>
                                    <?php $contentCounter++; ?>
                                </div>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                            <?php if ( have_rows( 'team' ) ) : ?>
                                <div class="switch-content teams<?php if ($contentCounter == 1){?> active<?php } ?>" id="teams">
                                    <?php while ( have_rows( 'team' ) ) : the_row(); ?>                                                                     
                                        <?php $post_objects = get_sub_field( 'team_members' ); ?>
                                            <?php if ( $post_objects ): ?>
                                            <div class="team-container column-container">
                                                <?php 
                                                if ($post_objects && !is_array($post_objects)) {
                                                    $post_objects = array($post_objects);
                                                }
                                                ?>
                                                <?php foreach ($post_objects as $post): ?>
                                                    <?php setup_postdata($post); ?>                                                    
                                                    <span class="speaker column one-half">
                                                        <span class="team-image-container">
                                                            <?php $speaker_image = get_field('speaker_image'); ?>
                                                            <?php if ($speaker_image) { ?>
                                                                <img src="<?php echo $speaker_image; ?>"/>                                                                                                                          
                                                            <?php } ?>
                                                        </span>
                                                        <span class="title-container">
                                                            <span class="labelMedium name"><?php the_title(); ?></span>
                                                            <span class="text-dark-grey text-grey"><?php echo get_field('speaker_description'); ?></span>
                                                        </span>
                                                    </span>                                             
                                                <?php endforeach; ?>
                                                <?php wp_reset_postdata(); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                    <?php $contentCounter++; ?>
                                </div>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>                            
                        <?php } else { ?> 
                            <?php if ( have_rows( 'about_advisor' ) ) : ?>
                                <div class="switch-content about about-advisor<?php if ($contentCounter == 1){?> active<?php } ?>" id="aboutAdvisor">
                                    <?php while ( have_rows( 'about_advisor' ) ) : the_row(); ?>
                                        <h3 class="title overview-title"><?php echo get_sub_field( 'title' ); ?></h3>
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
                        <?php } ?>
                        <?php if ( ! empty( $resources_array ) ) : ?>
                            <div class="switch-content resources<?php if ($contentCounter == 1){ ?> active<?php } ?>" id="resourcesAdvisor">
                                <div class="resources-column-container gap-16-40 two-column-container resources-advisor tablet-one-column"
                                data-page="1"
                                data-per-page="<?php echo $per_page; ?>"
                                data-total="<?php echo count($resources_array); ?>"
                                data-post-id="<?php the_ID(); ?>">

                                    <?php
                                    $per_page = 6; // Number of resources per "page"
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
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ajax-loading.gif" alt="Loading..." />
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php $contentCounter++; ?>

                        <?php endif; ?>     
                        <?php if ($partnerType != 'advisor'){ ?> 
                            <?php if ( have_rows( 'clients_module' ) ) : ?>
                                <div class="switch-content clients<?php if ($contentCounter == 1){?> active<?php } ?>" id="clients">
                                    <?php while ( have_rows( 'clients_module' ) ) : the_row(); ?>
                                        <div class="clients-container column-container">
                                            <?php if ( have_rows( 'clients' ) ) : ?>
                                                <?php while ( have_rows( 'clients' ) ) : the_row(); ?>
                                                    <span class="client column one-half">
                                                        <span class="logo-container">
                                                            <?php $logo = get_sub_field( 'logo' ); ?>
                                                            <?php if ( $logo ) { ?>
                                                                <?php echo wp_get_attachment_image( $logo['ID'], 'full', false, array( 'alt' => $logo['alt'] ) ); ?>
                                                            <?php } ?>
                                                        </span>
                                                        <span class="title-container">
                                                            <span class="labelMedium company-title"><?php the_sub_field( 'company_name' ); ?></span>
                                                        </span>
                                                    </span>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endwhile; ?>
                                     <?php $contentCounter++; ?>
                                </div>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?> 
                        <?php } ?>                    
                    </div>                    
                </div>
            </div>           
        </div>
    </div>
</section>