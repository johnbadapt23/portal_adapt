<?php $partner_type_ids = get_sub_field( 'partner_type' ); ?>  
<?php if ( $partner_type_ids ) {
    $term = get_term( $partner_type_ids, 'partner-type' ); 

    if ( ! is_wp_error( $term ) ) {
        $partnerslug = $term->slug;      
    }
} ?>
<section class="speaker-module partner-module background-white" <?php if(get_sub_field('id')){ ?> id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
     <div class="filter-container-outer">
        <div class="container">
            <!-- FILTERS -->
            <div class="filters-wrapper">

                <!-- Expertise Dropdown -->
                <?php
                    $expertise_terms = [];

                    if ($partner_type_ids) {

                        // STEP 1: Get all partners belonging to the selected partner types
                        $partner_posts = get_posts([
                            'post_type'      => 'partners',
                            'posts_per_page' => -1,
                            'fields'         => 'ids',
                            'tax_query'      => [
                                [
                                    'taxonomy' => 'partner-type',
                                    'field'    => 'term_id',
                                    'terms'    => $partner_type_ids,
                                ]
                            ]
                        ]);

                        // STEP 2: Collect capability terms attached to these partners
                        foreach ($partner_posts as $post_id) {
                            $terms = get_the_terms($post_id, 'capabilities');
                            if (is_array($terms)) {
                                foreach ($terms as $t) {
                                    $expertise_terms[$t->term_id] = $t; // store unique terms by ID
                                }
                            }
                        }

                        // SORT alphabetically
                        usort($expertise_terms, function($a, $b) {
                            return strcmp($a->name, $b->name);
                        });
                    }
                    ?>

                    <div class="filter-dropdown" data-filter="expertise">
                        <span class="dropdown-title">Expertise</span>
                        <div class="dropdown-list"> 
                            <a href="#" class="filter-button active" data-value="">All</a>
                            <?php if (!empty($expertise_terms)) : ?>
                                <?php foreach ($expertise_terms as $term) : ?>
                                    <a href="#" class="filter-button" data-value="<?php echo esc_attr($term->slug); ?>">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                    </div>

                <!-- Industries Dropdown -->
                <?php
                $industry_terms_filtered = [];

                if ($partner_type_ids) {

                    // STEP 1: Get all partners belonging to selected partner types
                    $partner_posts = get_posts([
                        'post_type'      => 'partners',
                        'posts_per_page' => -1,
                        'fields'         => 'ids',
                        'tax_query'      => [
                            [
                                'taxonomy' => 'partner-type',
                                'field'    => 'term_id',
                                'terms'    => $partner_type_ids,
                            ]
                        ]
                    ]);

                    // STEP 2: Collect industries used by these partners
                    foreach ($partner_posts as $post_id) {
                        $terms = get_the_terms($post_id, 'industries');
                        if (is_array($terms)) {
                            foreach ($terms as $t) {
                                $industry_terms_filtered[$t->term_id] = $t; // store unique
                            }
                        }
                    }

                    // Sort alphabetically
                    usort($industry_terms_filtered, function($a, $b) {
                        return strcmp($a->name, $b->name);
                    });
                }
                ?>

                <!-- Industries Dropdown -->
                <div class="filter-dropdown" data-filter="industry">
                    <span class="dropdown-title">Industries</span>
                    <div class="dropdown-list">
                        <a href="#" class="filter-button active" data-value="">All</a>

                        <?php if (!empty($industry_terms_filtered)) : ?>
                            <?php foreach ($industry_terms_filtered as $term) : ?>
                                <a href="#" class="filter-button" data-value="<?php echo esc_attr($term->slug); ?>">
                                    <?php echo esc_html($term->name); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>


            </div>
        </div>   
    </div>
    <div class="container">  
         
        <div class="speakers-container-outer">
           

            <div class="partners-filter-inner speaker-filter-inner">
                <div class="speakers" id="partners-container">                                      
                    <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        if ( $partner_type_ids ) {
                            // Set up the query arguments
                            $args = array(
                                'post_type'      => 'partners',
                                'posts_per_page' => 12,
                                'paged'         => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => 'partner-type',
                                        'field'    => 'term_id',
                                        'terms'    => $partner_type_ids,
                                        'operator' => 'IN',
                                    ),
                                )                                                          
                            );

                            // Run the query
                            $speakers_query = new WP_Query( $args );

                            // Check if there are posts
                            if ( $speakers_query->have_posts() ) {
                                while ( $speakers_query->have_posts() ) {
                                    $speakers_query->the_post();
                                    $post_slug = get_post_field( 'post_name', get_post() );
                                    $term_slugs = wp_get_post_terms(get_the_ID(), 'capabilities', array('fields' => 'slugs'));
                                    $filter_slugs = implode(' ', $term_slugs);
                                    ?>
                                    <div class="one-third speaker-item one-quarter column <?php echo $partnerslug; ?>" data-filter="<?php echo esc_attr( $filter_slugs ); ?>">
                                        <a class="slide-out-bio" href="<?php the_permalink(); ?>" id="<?php echo $post_slug; ?>">
                                            <?php if($partnerslug == 'executive-advisors'){ ?>
                                                <span class="image-container">
                                                    <span class="bg-container">
                                                        <?php 
                                                            $team_member_image = get_field('listing_avatar');
                                                            $image_url = '';

                                                            if ($team_member_image) {
                                                                if (is_array($team_member_image) && isset($team_member_image['url'])) {
                                                                    $image_url = $team_member_image['url'];
                                                                } elseif (is_string($team_member_image)) {
                                                                    $image_url = $team_member_image; // Already a URL
                                                                } elseif (is_int($team_member_image)) {
                                                                    $image_url = wp_get_attachment_image_url($team_member_image, 'full');
                                                                }
                                                            }

                                                            if ($image_url) : ?>
                                                                <?php
					$inline_img_170_src = $image_url;
					$inline_img_170_attach_id = $inline_img_170_src ? attachment_url_to_postid( $inline_img_170_src ) : 0;
					if ( $inline_img_170_attach_id ) {
						echo wp_get_attachment_image( $inline_img_170_attach_id, 'full', false, array( 'alt' => '<?php the_title(); ?>' ) );
					} elseif ( $inline_img_170_src ) {
						echo '<img src="' . esc_url( $inline_img_170_src ) . '" loading="lazy" alt="' . esc_attr( '<?php the_title(); ?>' ) . '" />';
					}
				?>
                                                            <?php endif; ?>
                                                    </span>
                                                    <span class="text-container mobile-hide">
                                                        <h5 class="labelMedium"><?php the_title(); ?></h5>  
                                                        <span class="role labelXSmall"><?php echo get_field('role'); ?></span> 
                                                        <span class="text-link red-text external-link underline-link red-underline-link learn-more">Learn More</span>                                                  
                                                    </span>
                                                </span>  
                                                <span class="text-container desktop-hide">
                                                    <span class="p-small"><?php the_title(); ?></span> 
                                                    <span class="text-link red-text external-link underline-link">Learn More</span>                                                   
                                                </span>   
                                            <?php } else { ?> 
                                                <span class="image-container">
                                                    <span class="bg-container">
                                                        <?php $team_member_image = get_field( 'listing_icon' ); ?>
                                                        <?php $image_url = '';

                                                        if ($team_member_image) {
                                                            if (is_array($team_member_image) && isset($team_member_image['url'])) {
                                                                $image_url = $team_member_image['url'];
                                                            } elseif (is_string($team_member_image)) {
                                                                $image_url = $team_member_image; // Already a URL
                                                            } elseif (is_int($team_member_image)) {
                                                                $image_url = wp_get_attachment_image_url($team_member_image, 'full');
                                                            }
                                                        }

                                                        if ($image_url) : ?>
                                                            <?php
					$inline_img_171_src = $image_url;
					$inline_img_171_attach_id = $inline_img_171_src ? attachment_url_to_postid( $inline_img_171_src ) : 0;
					if ( $inline_img_171_attach_id ) {
						echo wp_get_attachment_image( $inline_img_171_attach_id, 'full', false, array( 'alt' => '<?php the_title(); ?>' ) );
					} elseif ( $inline_img_171_src ) {
						echo '<img src="' . esc_url( $inline_img_171_src ) . '" loading="lazy" alt="' . esc_attr( '<?php the_title(); ?>' ) . '" />';
					}
				?>
                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="text-container mobile-hide">
                                                        <h5 class="labelMedium"><?php the_title(); ?></h5>                                                    
                                                    </span>
                                                </span>  
                                                <span class="text-container desktop-hide">
                                                    <span class="p-small"><?php the_title(); ?></span> 
                                                </span>  
                                            <?php } ?>                                                                                                                                                             
                                        </a>                                        
                                    </div>
                                    <?php
                                }
                            } 

                            // Restore original post data
                            wp_reset_postdata();
                        }
                        ?>

                </div>   
                <div class="page-navi-container partner-pagination-container">
                    <div class="container">
                        <?php wp_pagenavi(array(
                            'query' => $speakers_query,
                            'prev_text' => 'Previous', // Set custom text for "Previous" link
                            'next_text' => 'Next',     // Set custom text for "Next" link
                        )); ?>
                        <?php wp_reset_postdata(); ?>
                        <?php wp_reset_query(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>  
            