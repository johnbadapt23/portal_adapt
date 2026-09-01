<?php
/**
 * Template Name: Sectors Filter Template
 */

get_header();
global $membershipType;
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET filter params for a bookmarkable, shareable sector-listing URL; each value is sanitized via sanitize_text_field()/wp_unslash() before use, no state change.
$persona = isset($_GET['persona']) ? sanitize_text_field(wp_unslash($_GET['persona'])) : '';
$type= isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';
$topic = isset($_GET['topicType']) ? sanitize_text_field(wp_unslash($_GET['topicType'])) : '';
$events = isset($_GET['theme']) ? sanitize_text_field(wp_unslash($_GET['theme'])) : '';
$sector = isset($_GET['sector']) ? sanitize_text_field(wp_unslash($_GET['sector'])) : '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended
$has_persona_get = ($persona !== '');
$persona_term = null;

if ($persona !== '') {
    $persona_term = get_term_by('slug', $persona, 'persona-mapping');
}

$has_sector_get = ($sector !== '');
$sector_term = null;

if ($sector !== '') {
    $sector_term = get_term_by('slug', $sector, 'sector-analysis');
}

// Rendered here (before the filter dropdowns below) rather than inline in
// the Results section further down, so adapt_render_filter_posts()'s
// visible-terms data is available in time to bake empty-filter dimming
// directly into the dropdown buttons, and so the active-filter pills
// reflect the exact same state instead of relying on main.js reading it
// back out of the DOM after the fact (see buildActiveFilterPills() /
// hideEmptyFilters() in main.js - both are now first-paint no-ops here).
$active_filter_pills = [];
ob_start();
adapt_render_filter_posts();
$posts_container_html = ob_get_clean();
wp_reset_postdata();
$adapt_visible_terms = $GLOBALS['adapt_visible_terms'] ?? [];
?>

<main id="main" role="main" class="default whats-new">
<?php 
$membershipType = trim($membershipType);

$it_pro_types_ids    = get_field('it_pro_types', 'options') ?: [];
$advantage_types_ids = get_field('advantage_types', 'options') ?: [];

$membership_allowed_ids = [];

$researchLinkAdv = get_field( 'advantage_research_link', 'options' ); 
$researchLinkIt = get_field( 'it_pro_research_link', 'options' ); 
$researchLink = '$researchLinkAdv';
if ($membershipType === 'it-pro') {
    $membership_allowed_ids = $it_pro_types_ids;
    $researchLink = $researchLinkIt;
} elseif ($membershipType === 'advantage') {
    $membership_allowed_ids = $advantage_types_ids;
    $researchLink = $researchLinkAdv;
} ?>
<section class="title-banner filter-title-banner light-theme">
    <div class="container">
        <h1 class="header-large mobile-header-medium"><?php echo esc_html( get_field( 'title' ) ); ?></h1>
        <p><?php echo esc_html( get_field( 'subtitle' ) ); ?></p>
    </div>
</section>

<section class="post-filtering-module background-white"
    data-post-type="post">

<div class="filter-container-outer">
    <div class="container">
        <div class="filter-container-inner">
            <div class="filters-wrapper">

                <span class="filter-label labelSmall text-grey font-bold mobile-hide">
                    Filter By:
                </span>

                <?php
                // get_allowed_slugs() now lives in includes/_functions.php
                // (shared by the persona/sector/topic/post filter templates).

                // ----------------------------------------
                // TOPICS
                // ----------------------------------------
                if ( get_field('all_topics') == 1 ) {
                    $topic_terms = get_terms(['taxonomy'=>'topic','hide_empty'=>true,'parent'=>0]);
                    $allowed_topic_slugs = [];
                } else {
                    $topic_terms = get_field('topics') ?: [];
                    $allowed_topic_slugs = array_map(fn($t)=>$t->slug, is_array($topic_terms)?$topic_terms:[]);
                }

                 // ----------------------------------------
                // SECTORS
                // ----------------------------------------
                if ( get_field('sector_filter') == 1 ) { 
                    if ( get_field('all_sectors') == 1 ) {

                        $sector_terms = get_terms([
                            'taxonomy'   => 'sector-analysis',
                            'hide_empty' => true,
                            'parent'     => 0,
                            'orderby'    => 'menu_order',
                            'order'      => 'ASC',
                        ]);

                        $allowed_sector_slugs = [];

                    } else {

                        $sector_terms = get_field('sector') ?: [];

                        // Sort selected sectors by menu_order
                        if (is_array($sector_terms)) {
                            usort($sector_terms, function ($a, $b) {
                                return ($a->menu_order ?? 0) <=> ($b->menu_order ?? 0);
                            });
                        }

                        $allowed_sector_slugs = array_map(
                            fn($t) => $t->slug,
                            is_array($sector_terms) ? $sector_terms : []
                        );
                    }

                    $sector_from_get = null;

                    if ( $sector !== '' ) {
                        $sector_from_get = get_term_by( 'slug', $sector, 'sector-analysis' );
                    }
                }

                // ----------------------------------------
                // TYPES
                // ----------------------------------------
                if ( get_field('all_types') == 1 ) {
                    $type_terms = get_terms(['taxonomy'=>'filter-types','hide_empty'=>true,'parent'=>0]);
                    $allowed_type_slugs = [];
                } else {
                    $type_terms = get_field('types') ?: [];
                    $allowed_type_slugs = array_map(fn($t)=>$t->slug, is_array($type_terms)?$type_terms:[]);
                }
                if (!empty($membership_allowed_ids)) {

                    // Filter terms first
                    $type_terms = array_filter($type_terms, function ($term) use ($membership_allowed_ids) {
                        return in_array($term->term_id, $membership_allowed_ids, true);
                    });

                    // Rebuild slugs for AJAX filters
                    $allowed_type_slugs = array_map(
                        fn($t) => $t->slug,
                        $type_terms
                    );
                }


                // ----------------------------------------
                // TRENDING THEMES
                // ----------------------------------------
                if ( get_field('all_trends') == 1 ) {
                    $trending_terms = get_terms(['taxonomy'=>'trending-themes','hide_empty'=>true,'parent'=>0]);
                    $allowed_trend_slugs = [];
                } else {
                    $trending_terms = get_field('trends') ?: [];
                    $allowed_trend_slugs = array_map(fn($t)=>$t->slug, is_array($trending_terms)?$trending_terms:[]);
                }


                // ----------------------------------------
                // SORT TERMS
                // ----------------------------------------
                $sort_terms = fn(&$terms)=> is_array($terms) ? usort($terms, fn($a,$b)=>strcmp($a->name,$b->name)) : null;
                $sort_terms($topic_terms); $sort_terms($type_terms); $sort_terms($trending_terms);
                if(isset($persona_terms)) $sort_terms($persona_terms);
                
                if(isset($events_terms)) $sort_terms($events_terms);
                ?>

                <div class="mobile-filter-accordion">
                    <div class="mobile-filter-content">

                        <!-- Topics -->
                        <?php if ( get_field( 'topics_filter' ) == 1 ) { ?>
                            <div class="filter-dropdown" data-filter="topic" data-allowed='<?= esc_attr(wp_json_encode($allowed_topic_slugs)); ?>'>
                                <span class="dropdown-title">Topics</span>
                                <div class="dropdown-list">
                                    <?php 
                                    $all_value = !empty($allowed_topic_slugs) ? wp_json_encode($allowed_topic_slugs) : '[]';
                                    $active_found = false;
                                    ?>
                                    <a href="#" class="filter-button all <?= $topic === '' ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
                                    <?php foreach($topic_terms as $term) :
                                        $is_active = $term->slug === $topic;
                                        if($is_active) {
                                            $active_found = true;
                                            $active_filter_pills[] = ['filter' => 'topic', 'label' => $term->name];
                                        }
                                        $is_visible = in_array($term->slug, $adapt_visible_terms['topic'] ?? [], true);
                                    ?>
                                        <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                                    <?php endforeach; ?>

                                    <?php if($topic !== '' && !$active_found) : ?>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function(){
                                                const dropdown = document.querySelector('.filter-dropdown[data-filter="topic"]');
                                                if(dropdown){
                                                    dropdown.querySelector('.filter-button[data-value=\'<?= esc_js($all_value); ?>\']').classList.add('active');
                                                }
                                            });
                                        </script>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Types -->

                            <!-- TYPE (normal behaviour) -->
                            <?php $has_get_value = $type !== ''; ?>
                            <div class="filter-dropdown"
                                data-filter="type"
                                data-allowed='<?= esc_attr(wp_json_encode($allowed_type_slugs)); ?>'>

                                <span class="dropdown-title">
                                    Types
                                </span>

                                <div class="dropdown-list">
                                    <?php 
                                    $all_value = !empty($allowed_type_slugs)
                                        ? wp_json_encode($allowed_type_slugs)
                                        : '[]';
                                    ?>
                                    <a href="#"
                                    class="filter-button all <?= $type === '' ? 'active' : ''; ?>"
                                    data-value='<?= esc_attr($all_value); ?>'>
                                        All
                                    </a>

                                    <?php foreach ($type_terms as $term) :
                                        $is_active = $term->slug === $type;
                                        if ($is_active) {
                                            $active_filter_pills[] = ['filter' => 'type', 'label' => $term->name];
                                        }
                                        $is_visible = in_array($term->slug, $adapt_visible_terms['filter-types'] ?? [], true);
                                    ?>
                                        <a href="#"
                                        class="filter-button <?= $is_active ? 'active' : ''; ?>"
                                        data-value="<?= esc_attr($term->slug); ?>"<?= $is_visible ? '' : ' style="display:none;"'; ?>>
                                            <?= esc_html($term->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        

<?php if (!empty($sector_terms)) : ?>
    <?php
    $has_sector_get = ($sector_from_get && !is_wp_error($sector_from_get));

    // Build allowed slugs array
    $allowed_sector_slugs = array_map(fn($t) => $t->slug, is_array($sector_terms) ? $sector_terms : []);
    ?>

    <div class="filter-dropdown filter-sector"
         data-filter="sector"
         data-allowed='<?= esc_attr(wp_json_encode($allowed_sector_slugs)); ?>'>

        <span class="dropdown-title <?= $has_sector_get ? 'filter-active' : ''; ?>">
            Sectors
        </span>

        <div class="dropdown-list">



                <?php
                $all_value = !empty($allowed_sector_slugs) ? wp_json_encode($allowed_sector_slugs) : '[]';
                $sector_active_found = false;
                ?>

                <!-- All button -->
                <a href="#"
                   class="filter-button all <?= $sector === '' ? 'active' : ''; ?>"
                   data-value='<?= esc_attr($all_value); ?>'>
                    All
                </a>

                <!-- Individual sectors -->
                <?php foreach ($sector_terms as $term) :
                    $is_active = ($term->slug === $sector);
                    if ($is_active) {
                        $sector_active_found = true;
                        $active_filter_pills[] = ['filter' => 'sector', 'label' => $term->name];
                    }
                    $is_visible = in_array($term->slug, $adapt_visible_terms['sector-analysis'] ?? [], true);
                ?>
                    <a href="#"
                       class="filter-button <?= $is_active ? 'active' : ''; ?>"
                       data-value="<?= esc_attr($term->slug); ?>"<?= $is_visible ? '' : ' style="display:none;"'; ?>>
                        <?= esc_html($term->name); ?>
                    </a>
                <?php endforeach; ?>

                <?php
                // Fallback: if GET value does not exist in allowed terms, default "All" active
                if ($sector !== '' && !$sector_active_found) :
                ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const dropdown = document.querySelector('.filter-dropdown.filter-sector');
                            if (dropdown) {
                                dropdown.querySelector('.filter-button.all').classList.add('active');
                            }
                        });
                    </script>
                <?php endif; ?>

       

        </div>
    </div>
<?php endif; ?>




                        <!-- Trending Themes -->
                        <?php if($membershipType != 'advantage' && $membershipType != 'it-pro' && !empty($trending_terms)) : ?>
                            <?php if(!empty($trending_terms)) : ?>
                            
                            <div class="filter-dropdown" data-filter="trending-themes" data-allowed='<?= esc_attr(wp_json_encode($allowed_trend_slugs)); ?>'>
                                <span class="dropdown-title">Trending Themes</span>
                                <div class="dropdown-list">
                                    <?php 
                                    $all_value = !empty($allowed_trend_slugs) ? wp_json_encode($allowed_trend_slugs) : '[]';
                                    $active_found = false;
                                    ?>
                                    <?php // $events (not $themes - nothing sets that name) holds the sanitized
                                    // $_GET['theme'] value parsed at the top of this file; using the
                                    // undefined $themes here meant a bookmarked ?theme=slug URL never
                                    // marked the right trending-themes button active on first paint. ?>
                                    <a href="#" class="filter-button all <?= $events === '' ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
                                    <?php foreach($trending_terms as $term) :
                                        $is_active = $term->slug === $events;
                                        if($is_active) {
                                            $active_found = true;
                                            $active_filter_pills[] = ['filter' => 'trending-themes', 'label' => $term->name];
                                        }
                                        $is_visible = in_array($term->slug, $adapt_visible_terms['trending-themes'] ?? [], true);
                                    ?>
                                        <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                                    <?php endforeach; ?>

                                    <?php if($events !== '' && !$active_found) : ?>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function(){
                                                const dropdown = document.querySelector('.filter-dropdown[data-filter="trending-themes"]');
                                                if(dropdown){
                                                    dropdown.querySelector('.filter-button[data-value=\'<?= esc_js($all_value); ?>\']').classList.add('active');
                                                }
                                            });
                                        </script>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <a class="research-link" href="<?php echo esc_url( $researchLink ); ?>" target="_self">All research</a>
                        <a class="reset-filters-btn mobile-reset-button labelSmall text-grey font-bold desktop-hide">Reset Filters</a>
                    </div>

                    <span class="mobile-filter-dropdown labelSmall text-grey font-bold desktop-hide">
                        <span class="closed-text">Filter by topic, type and trend</span>
                        <span class="open-text">Hide filters</span>
                    </span>
                </div>
            </div>

            <!-- Search -->
            <div class="filter-search">
                <form class="post-search-form">
                    <input type="text" class="post-search-input" placeholder="<?php echo esc_attr( get_field('search_help_text') ); ?>">
                    <input type="image" class="post-search-submit" src="<?= esc_url( get_template_directory_uri() ); ?>/assets/images/magnify-grey.svg" alt="Search">
                </form>
                <a class="reset-filters-btn labelSmall text-grey font-bold mobile-hide">Reset</a>
            </div>
        </div>
    </div>
</div>



    <?php
        // Built from the active-state each dropdown loop above already
        // computed, so the pills can never disagree with which buttons are
        // shown as active - the same failure mode this session's earlier
        // adapt_render_filter_posts()/ajax_load_filtered_posts() parity work
        // was about, just for pills instead of card contents.
        $pills_html = '';
        foreach ($active_filter_pills as $pill) {
            $pills_html .= '<button type="button" class="filter-pill" data-filter="' . esc_attr($pill['filter']) . '"><span>' . esc_html($pill['label']) . '</span><span class="pill-close">&times;</span></button>';
        }
    ?>
    <script>window.adaptFilterUIServerRendered = true;</script>
    <!-- Results -->
    <?php
        // Featured sector post is rendered server-side on first load (was
        // previously computed here and discarded, then re-fetched a moment
        // later via an unconditional loadFeaturedPostsIfNeeded() AJAX call
        // in main.js - see the INITIAL LOAD section there). The AJAX path
        // still runs on subsequent filter changes; see main.js.
        $featured_post_html = '';

        if ($sector_term && !is_wp_error($sector_term)) {

            // remove_already_displayed_posts (hooked globally on
            // pre_get_posts) would exclude this post if an earlier
            // component on the page already linked to it via
            // get_permalink(), populating $displayed_posts. The AJAX
            // version of this query (ajax_load_featured_post()) never sees
            // that exclusion since it runs as its own request with no
            // prior $displayed_posts, so disable the hook here too so this
            // query isn't the only one of the two subject to it.
            remove_action('pre_get_posts', 'remove_already_displayed_posts');
            $featured_query = new WP_Query([
                'no_found_rows'  => true,
                'post_type'      => 'post',
                'posts_per_page' => 1,
                'post_status'    => 'publish',
                'tax_query'      => [
                    'relation' => 'AND',
                    [
                        'taxonomy' => 'filter-types',
                        'field'    => 'slug',
                        'terms'    => 'sector-outlooks',
                    ],
                    [
                        'taxonomy' => 'sector-analysis',
                        'field'    => 'slug',
                        'terms'    => $sector_term->slug,
                    ],
                    [
                        'taxonomy' => 'subscription',
                        'field'    => 'slug',
                        'terms'    => 'advantage',
                    ],
                ],
            ]);
            add_action('pre_get_posts', 'remove_already_displayed_posts');

            if ($featured_query->have_posts()) {
                ob_start();
                while ($featured_query->have_posts()) {
                    $featured_query->the_post();
                    include locate_template('/templates/components/_featured-article-card-sector.php');
                }
                $featured_post_html = ob_get_clean();
                wp_reset_postdata();
            }
        }
        ?>
<?php if ($sector_term && !is_wp_error($sector_term)) : ?>
    <!-- Featured Persona Post -->
        <section class="featured-persona-post" id="featured-post-sector"<?= $featured_post_html === '' ? ' style="display:none;"' : ''; ?>>
            <div class="container"><?= $featured_post_html; ?></div>
        </section>
    <div class="market-narratives-filter-outer">
        <div class="container">
            <div class="whats-new-container-outer">
                <!-- Persona Title -->
                <h2 class="headerXsmall text-bold market-narrative-title" style="display: none;">
                    <?= esc_html($sector_term->name); ?>
                </h2>
                <div class="whats-new-filter-inner">
                    <div class="sort-pills-container">
                        <div class="results-container">
                            <span class="search-results-label" style="display:none;"></span>
                            <div class="active-filter-pills"<?= empty($active_filter_pills) ? ' style="display:none;"' : ''; ?>><?= $pills_html; ?></div>
                        </div>
                        <div class="sort-dropdown">
                            <div class="filter-dropdown" data-filter="sort" data-allowed="[]">
                                <span class="dropdown-title">Sort by</span>

                                <div class="dropdown-list">
                                    <a href="#"
                                    class="filter-button all active"
                                    data-value="latest">
                                        Latest
                                    </a>

                                    <a href="#"
                                    class="filter-button"
                                    data-value="featured">
                                        Featured
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ajax-loader" style="display: none;">
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/ajax-loading.gif" width="200" height="200" loading="lazy" decoding="async" alt="Loading..." />
                    </div>
                    <div class="whats-new resources-column-container three-column-container gap-16-40"
                        id="posts-container">
                        <?= $posts_container_html; ?>
                    </div>

                    <div class="page-navi-container post-pagination-container">
                        <a class="load-more-btn std-button red-button small-button" <?= $GLOBALS['adapt_has_more_posts'] ? 'style="display: inline;"' : ''; ?>>Load More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="container">
        <div class="whats-new-container-outer">
            <div class="whats-new-filter-inner">
                <div class="sort-pills-container">
                    <div class="results-container">
                        <span class="search-results-label" style="display:none;"></span>
                        <div class="active-filter-pills"<?= empty($active_filter_pills) ? ' style="display:none;"' : ''; ?>><?= $pills_html; ?></div>
                    </div>
                    <div class="sort-dropdown">
                        <div class="filter-dropdown" data-filter="sort" data-allowed="[]">
                            <span class="dropdown-title">Sort by</span>

                            <div class="dropdown-list">
                                <a href="#"
                                class="filter-button all active"
                                data-value="latest">
                                    Latest
                                </a>

                                <a href="#"
                                class="filter-button"
                                data-value="featured">
                                    Featured
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ajax-loader" style="display: none;">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/ajax-loading.gif" width="200" height="200" loading="lazy" decoding="async" alt="Loading..." />
                </div>
                <div class="whats-new resources-column-container three-column-container gap-16-40"
                     id="posts-container">
                    <?= $posts_container_html; ?>
                    </div>

                <div class="page-navi-container post-pagination-container">
                    <a class="load-more-btn std-button red-button small-button"  <?= $GLOBALS['adapt_has_more_posts']  ? 'style="display: inline;"' : ''; ?>>Load More</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>


</main>

<?php get_footer(); ?>
