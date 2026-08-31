<?php 
$partner_type_id = get_sub_field('partner_type'); // single ID
$partner_term = get_term($partner_type_id, 'partner-type');
$partnerslug = ($partner_term && !is_wp_error($partner_term)) ? $partner_term->slug  : '';
$preselected_expertise  = isset($_GET['expertise']) ? sanitize_text_field($_GET['expertise']) : '';
$preselected_capability = isset($_GET['capability']) ? sanitize_text_field($_GET['capability']) : '';
$preselected_industry   = isset($_GET['industry']) ? sanitize_text_field($_GET['industry']) : '';

?>
<section class="speaker-module partner-module background-white" data-page-id="<?php the_ID(); ?>" data-partner-type-id="<?php echo esc_attr($partner_type_id); ?>">
    <div class="filter-container-outer">
        <div class="container">
            <div class="filter-container-inner">
                <div class="filters-wrapper">
                    <span class="filter-label labelSmall text-grey font-bold mobile-hide">Filter By:</span>
                    <div class="mobile-filter-accordion">
                        <div class="mobile-filter-content">
                            <!-- Expertise Filter -->
                           <?php
$expertise_terms = [];

if ($partner_type_id) {
    // Get all partner posts for this type
    $partner_posts = get_posts([
        'post_type'      => 'partners',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'tax_query'      => [[
            'taxonomy' => 'partner-type',
            'field'    => 'term_id',
            'terms'    => $partner_type_id,
        ]],
    ]);

    // Collect all capabilities from these posts
    foreach ($partner_posts as $post_id) {
        $terms = get_the_terms($post_id, 'capabilities');

        if (!empty($terms) && is_array($terms)) {
            foreach ($terms as $term) {
                // Use term ID as key to avoid duplicates
                $expertise_terms[$term->term_id] = $term;
            }
        }
    }

    // Remove any terms with zero count (optional safety)
    $expertise_terms = array_filter($expertise_terms, function($term) {
        return $term->count > 0;
    });

    // Sort terms alphabetically by name
    usort($expertise_terms, function($a, $b) {
        return strcmp($a->name, $b->name);
    });
}
?>

                            <div class="filter-dropdown" data-filter="expertise">
                                <span class="dropdown-title <?php echo ($preselected_expertise || $preselected_capability) ? 'filter-active' : ''; ?>">
                                    <?php echo ($partnerslug === 'advisors') ? 'Expertise' : 'Capabilities'; ?>
                                </span>

                                <div class="dropdown-list">
                                    <a href="#"
                                    class="filter-button <?php echo (!$preselected_expertise && !$preselected_capability) ? 'active' : ''; ?>"
                                    data-value="">
                                    All
                                    </a>

                                    <?php foreach ($expertise_terms as $term) :
                                        $is_active = (
                                            ($partnerslug === 'advisors' && $term->slug === $preselected_expertise) ||
                                            ($partnerslug !== 'advisors' && $term->slug === $preselected_capability)
                                        );
                                    ?>
                                        <a href="#"
                                        class="filter-button <?php echo $is_active ? 'active' : ''; ?>"
                                        data-value="<?php echo esc_attr($term->slug); ?>">
                                            <?php echo esc_html($term->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>


                            <!-- Industry Filter -->
                            <?php
                           
                    $industry_terms_filtered = [];

                    if ($partner_type_id) {
                        // Get all partner posts for this type
                        $partner_posts = get_posts([
                            'post_type'      => 'partners',
                            'posts_per_page' => -1,
                            'fields'         => 'ids',
                            'tax_query'      => [[
                                'taxonomy' => 'partner-type',
                                'field'    => 'term_id',
                                'terms'    => $partner_type_id,
                            ]],
                        ]);

                        // Collect all industries from these posts
                        foreach ($partner_posts as $post_id) {
                            $terms = get_the_terms($post_id, 'industries');

                            if (!empty($terms) && is_array($terms)) {
                                foreach ($terms as $term) {
                                    // Use term ID as key to avoid duplicates
                                    $industry_terms_filtered[$term->term_id] = $term;
                                }
                            }
                        }

                        // Remove any terms with zero count (optional safety)
                        $industry_terms_filtered = array_filter($industry_terms_filtered, function($term) {
                            return $term->count > 0;
                        });

                        // Sort terms alphabetically by name
                        usort($industry_terms_filtered, function($a, $b) {
                            return strcmp($a->name, $b->name);
                        });
                    }
                    ?>

                           
                            <div class="filter-dropdown" data-filter="industry">
                                <span class="dropdown-title <?php echo $preselected_industry ? 'filter-active' : ''; ?>">
                                    Industries
                                </span>

                                <div class="dropdown-list">
                                    <a href="#"
                                    class="filter-button <?php echo !$preselected_industry ? 'active' : ''; ?>"
                                    data-value="">
                                    All
                                    </a>

                                    <?php foreach ($industry_terms_filtered as $term) : ?>
                                        <a href="#"
                                        class="filter-button <?php echo ($term->slug === $preselected_industry) ? 'active' : ''; ?>"
                                        data-value="<?php echo esc_attr($term->slug); ?>">
                                            <?php echo esc_html($term->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <a class="reset-filters-btn mobile-reset-button labelSmall text-grey font-bold desktop-hide">Reset Filters</a>
                        </div>
                        <span class="mobile-filter-dropdown labelSmall text-grey font-bold desktop-hide"><span class="closed-text">Filter by experts and industries</span><span class="open-text">Hide filters</span></span>
                    </div>
                </div>
                <div class="filter-search">
                    <form class="partner-search-form">
                        <input type="text" class="partner-search-input" 
                            <?php if ($partnerslug == 'advisors') : ?>
                                placeholder="Find an Advisor" 
                            <?php else : ?>
                                placeholder="Find a firm" 
                            <?php endif; ?>
                        />
                        <input class="partner-search-submit" type="image" alt="Search" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/magnify-grey.svg" />
                    </form>
                    <a class="reset-filters-btn labelSmall text-grey font-bold mobile-hide">Reset</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">  
        <div class="speakers-container-outer">
            <div class="partners-filter-inner speaker-filter-inner">
                 <div class="sort-pills-container">
                    <div class="results-container">
                        <span class="search-results-label" style="display:none;"></span>
                        <div class="active-filter-pills" style="display:none;"></div>
                    </div>                   
                </div>
                <div class="ajax-loader">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/ajax-loading.gif" width="200" height="200" loading="lazy" decoding="async" alt="Loading..." />
                </div>
                <div class="speakers" id="partners-container">
                    <!-- AJAX results will render here -->
                </div>
                <div class="page-navi-container partner-pagination-container">
                    <a class="load-more-btn std-button red-button small-button" id="partners-load-more" data-page="1">Load More</a>
                </div>
            </div>
        </div>
    </div>
</section>
