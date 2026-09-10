<?php 
$partner_type_id = get_sub_field('partner_type'); // single ID
$partner_term = get_term($partner_type_id, 'partner-type');
$partnerslug = ($partner_term && !is_wp_error($partner_term)) ? $partner_term->slug  : '';
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET filter params for a bookmarkable, shareable partner-listing URL; each value is sanitized via sanitize_text_field() before use and no state change results.
$preselected_expertise  = isset($_GET['expertise']) ? sanitize_text_field($_GET['expertise']) : '';
$preselected_capability = isset($_GET['capability']) ? sanitize_text_field($_GET['capability']) : '';
$preselected_industry   = isset($_GET['industry']) ? sanitize_text_field($_GET['industry']) : '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended

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

// Shared by both the Expertise and Industry dropdowns below - one
// get_posts() call for this partner type instead of two identical
// ones. fields=>ids never primes the term cache, so the per-taxonomy
// term lookups further down are batched via get_terms(object_ids=>...)
// (one query each) rather than looping get_the_terms() per post.
$partner_posts = [];

if ($partner_type_id) {
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

    if (!empty($partner_posts)) {
        $expertise_terms = get_terms([
            'taxonomy'   => 'capabilities',
            'object_ids' => $partner_posts,
            'hide_empty' => true,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ]);
        $expertise_terms = is_array($expertise_terms) ? $expertise_terms : [];
    }
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
                           
                    // Reuses $partner_posts fetched above for the Expertise
                    // dropdown - same partner type, no need to query again.
                    $industry_terms_filtered = [];

                    if (!empty($partner_posts)) {
                        $industry_terms_filtered = get_terms([
                            'taxonomy'   => 'industries',
                            'object_ids' => $partner_posts,
                            'hide_empty' => true,
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                        ]);
                        $industry_terms_filtered = is_array($industry_terms_filtered) ? $industry_terms_filtered : [];
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

    <?php
        // #partners-container used to start empty with main.js's initial
        // loadPartners()/buildActiveFilterPills() calls left commented out,
        // so it never actually loaded anything until the user touched a
        // filter. Rendered here via PHP instead, mirroring ajax_load_partners()'s
        // query exactly so first paint matches what an AJAX filter change
        // would show for the same expertise/industry selection.
        //
        // Deliberately placed after the search form above (not before the
        // dropdowns, unlike the other filter templates in this theme) -
        // _partner-card.php reassigns $partnerslug per-card as a side
        // effect, which would clobber the "Find an Advisor" vs "Find a
        // firm" placeholder logic above if this query ran any earlier.
        $partners_html = '';
        $partners_has_more = false;

        if ($partner_type_id) {
            $selected_expertise = ($partnerslug === 'advisors') ? $preselected_expertise : $preselected_capability;

            $partner_tax_query = [
                [
                    'taxonomy' => 'partner-type',
                    'field'    => 'term_id',
                    'terms'    => $partner_type_id,
                ],
            ];

            if ($selected_expertise !== '') {
                $partner_tax_query[] = [
                    'taxonomy' => 'capabilities',
                    'field'    => 'slug',
                    'terms'    => $selected_expertise,
                ];
            }

            if ($preselected_industry !== '') {
                $partner_tax_query[] = [
                    'taxonomy' => 'industries',
                    'field'    => 'slug',
                    'terms'    => $preselected_industry,
                ];
            }

            $partners_query = new WP_Query([
                'post_type'        => 'partners',
                'posts_per_page'   => 12,
                'paged'            => 1,
                'post_status'      => 'publish',
                'tax_query'        => $partner_tax_query,
                'orderby'          => [
                    'menu_order' => 'DESC',
                    'ID'         => 'DESC',
                ],
                'suppress_filters' => false,
            ]);

            $partners_has_more = $partners_query->max_num_pages > 1;

            ob_start();
            while ($partners_query->have_posts()) {
                $partners_query->the_post();
                $post_id = get_the_ID();
                include locate_template('/templates/partners-components/_partner-card.php');
            }
            $partners_html = ob_get_clean();
            wp_reset_postdata();
        }
    ?>

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
                    <?= $partners_html; ?>
                </div>
                <div class="page-navi-container partner-pagination-container">
                    <a class="load-more-btn std-button red-button small-button" id="partners-load-more" data-page="1"<?= $partners_has_more ? '' : ' style="display:none;"'; ?>>Load More</a>
                </div>
            </div>
        </div>
    </div>
</section>
