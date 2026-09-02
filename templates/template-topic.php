
<?php
$current_user = wp_get_current_user();
$member = new MeprUser($current_user->ID);
$advantageType = "no";
// Get the active subscriptions for this user
$active_subscriptions = $member->active_product_subscriptions('ids');
global $membershipType;
if (
 current_user_can('mepr-active') && (
        in_array(49140, $active_subscriptions) ||
        in_array(3829, $active_subscriptions) ||
        in_array(36884, $active_subscriptions) ||
        in_array(41272, $active_subscriptions)
    )
) {
    $advantageType = "yes";
}
$today = wp_date('Ymd');
$args = [
    'no_found_rows'  => true,
    'post_type' => 'post',
    'meta_key'  => 'replay_event_date',
    'orderby'   => 'meta_value_num',
    'order'     => 'ASC',
    'tax_query' => [
        'relation' => 'AND',
         [
            'taxonomy' => 'replay',
            'field' => 'slug',
            'terms' => 'replay_event_date',
            'operator' => 'IN',
        ],
    ],
    'meta_query' => [
        [
            'key'     => 'replay_event_date',
            'compare' => '<=',
            'value'   => $today,
        ],
    ],
];
global $displayed_posts;
$displayed_posts =  [];
$posts = new WP_Query( $args );
if( $posts->have_posts() ): ?>
    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
        <?php if(current_user_can('mepr_auth')) {?>
        <?php } else { ?>
            <?php $id = get_the_ID(); ?>
            <?php $displayed_posts[] = $id; ?>
        <?php } ?>
    <?php endwhile; ?>
<?php endif;
wp_reset_postdata(); wp_reset_query();?>

<?php $q = get_queried_object(); ?>
<?php

$user_info = wp_get_current_user();

$first_name = $user_info->first_name;
$interests = $user_info->mepr_interests;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET filter/search params for a bookmarkable, shareable listing URL; no state change results from reading them.
$filterType = $_GET['filterby'] ?? '';
$keyword = $_GET['searchWords'] ?? '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended

// $topic/$type/$themes below are read (never assigned) by the "All" button
// active-state check in each filter dropdown further down this file. They
// predate the pill/dimming rewrite (see $active_filter_pills and
// $adapt_visible_terms usage below), which moved active-state tracking to
// $active_found instead - these three were left behind unassigned. Declaring
// them null here preserves the exact current behavior (an undefined
// variable already evaluates as null in each of those comparisons) while
// silencing the PHP warnings Query Monitor was reporting on every load.
$topic  = null;
$type   = null;
$themes = null;
?>
<?php
// A dead $args build used to sit here (an if($keyword)/else block scoped to
// the topic taxonomy, using $paged before it was even defined below) -
// $args was immediately overwritten by the simpler array right after it,
// on the next line, so it was never actually used by any query. Removed -
// the real keyword-filtered query for this page lives further down this
// file where $keyword is actually consumed.
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
<?php
// This loop only reads each post's ID (to populate $displayed_posts) - it
// never touches title/content/ACF fields, so it doesn't need full WP_Post
// objects. fields => ids skips that hydration on a query with no result
// limit.
$args = [
    'post_type' => 'post',
    'posts_per_page' => -1,
    'no_found_rows' => true,
    'fields' => 'ids'
]; ?>
<?php $loop = new WP_Query( $args );
if ( $loop->have_posts() ) :
    foreach ( $loop->posts as $id ) :
?>
<?php if(current_user_can('mepr_auth')) {?>
<?php } else { ?>
    <?php $displayed_posts[] = $id; ?>
<?php }?>

<?php endforeach; else : ?>
<?php endif; ?>
<?php wp_reset_postdata(); wp_reset_query();?>
<?php 
$membershipType = trim($membershipType);

$it_pro_types_ids    = get_field('it_pro_types', 'options') ?: [];
$advantage_types_ids = get_field('advantage_types', 'options') ?: [];

$membership_allowed_ids = [];

if ($membershipType === 'it-pro') {
    $membership_allowed_ids = $it_pro_types_ids;
} elseif ($membershipType === 'advantage') {
    $membership_allowed_ids = $advantage_types_ids;
} ?>

<?php if($q -> parent == 0){ ?>
<section class="title-banner filter-title-banner light-theme">
    <div class="container">
        <h1 class="header-large mobile-header-medium"><?php echo esc_html( $q->name ); ?></h1>
        <p>
            <?php 
            echo wp_kses_post( term_description( $q->term_id ) );
            ?>
        </p>
    </div>
</section>
<?php } ?>
<?php 
global $membershipType, $advantageType, $member;
$membershipType = trim($membershipType);

$it_pro_types_ids    = get_field('it_pro_types', 'options') ?: [];
$advantage_types_ids = get_field('advantage_types', 'options') ?: [];

$membership_allowed_ids = [];

if ($membershipType === 'it-pro') {
    $membership_allowed_ids = $it_pro_types_ids;
} elseif ($membershipType === 'advantage') {
    $membership_allowed_ids = $advantage_types_ids;
} ?>

<?php
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
                                    $topic_terms = get_terms(['taxonomy'=>'topic','hide_empty'=>true,'parent'=>0]);
                                    $allowed_topic_slugs = [];

                                // ----------------------------------------
                                // TYPES
                                // ----------------------------------------
                                
                                    $type_terms = get_terms(['taxonomy'=>'filter-types','hide_empty'=>true,'parent'=>0]);
                                    $allowed_type_slugs = [];
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
                                    $trending_terms = get_terms(['taxonomy'=>'trending-themes','hide_empty'=>true,'parent'=>0]);
                                    $allowed_trend_slugs = [];
                    // ----------------------------------------
                    // SORT TERMS
                    // ----------------------------------------
                    $sort_terms = fn(&$terms) => is_array($terms) ? usort($terms, fn($a,$b)=>strcmp($a->name,$b->name)) : null;
                    $sort_terms($type_terms);
                    $sort_terms($trending_terms);
                    ?>

                    <div class="mobile-filter-accordion">
                        <div class="mobile-filter-content">

                                <!-- Topics -->
                                    <?php $has_get_valueTopic = $q !== ''; ?>
                                    <div class="filter-dropdown" data-filter="topic" data-allowed='<?= esc_attr(wp_json_encode($allowed_topic_slugs)); ?>'>
                                        <span class="dropdown-title <?= $has_get_valueTopic ? 'filter-active' : ''; ?>">Topics</span>
                                        <div class="dropdown-list">
                                            <?php 
                                            $all_value = !empty($allowed_topic_slugs) ? wp_json_encode($allowed_topic_slugs) : '[]';
                                            $active_found = false;
                                            ?>
                                            <a href="#" class="filter-button all <?= $topic === '' ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
                                            <?php foreach($topic_terms as $term) :
                                                $is_active = $term->slug === $q->slug;
                                                if($is_active) {
                                                    $active_found = true;
                                                    $active_filter_pills[] = ['filter' => 'topic', 'label' => $term->name];
                                                }
                                                $is_visible = in_array($term->slug, $adapt_visible_terms['topic'] ?? [], true);
                                            ?>
                                                <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <!-- Types -->
                                    
                                    <div class="filter-dropdown" data-filter="type" data-allowed='<?= esc_attr(wp_json_encode($allowed_type_slugs)); ?>'>
                                        <span class="dropdown-title">Types</span>
                                        <div class="dropdown-list">
                                            <?php 
                                            $all_value = !empty($allowed_type_slugs) ? wp_json_encode($allowed_type_slugs) : '[]';
                                            $active_found = false;
                                            ?>
                                            <a href="#" class="filter-button all" data-value='<?= esc_attr($all_value); ?>'>All</a>
                                            <?php foreach($type_terms as $term) :
                                                $is_visible = in_array($term->slug, $adapt_visible_terms['filter-types'] ?? [], true);
                                            ?>
                                                <a href="#" class="filter-button" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                                            <?php endforeach; ?>

                                            <?php if($type !== '' && !$active_found) : ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function(){
                                                        const dropdown = document.querySelector('.filter-dropdown[data-filter="type"]');
                                                        if(dropdown){
                                                            dropdown.querySelector('.filter-button[data-value=\'<?= esc_js($all_value); ?>\']').classList.add('active');
                                                        }
                                                    });
                                                </script>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                     <?php if($membershipType != 'advantage' && $membershipType != 'it-pro' && !empty($trending_terms)) : ?>
                                        <!-- Trending Themes -->
                                        <?php if(!empty($trending_terms)) : ?>
                                        <div class="filter-dropdown" data-filter="trending-themes" data-allowed='<?= esc_attr(wp_json_encode($allowed_trend_slugs)); ?>'>
                                            <span class="dropdown-title">Trending Themes</span>
                                            <div class="dropdown-list">
                                                <?php 
                                                $all_value = !empty($allowed_trend_slugs) ? wp_json_encode($allowed_trend_slugs) : '[]';
                                                $active_found = false;
                                                ?>
                                                <a href="#" class="filter-button all <?= $themes === '' ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
                                                <?php foreach($trending_terms as $term) :
                                                    $is_active = $term->slug === $themes;
                                                    if($is_active) {
                                                        $active_found = true;
                                                        $active_filter_pills[] = ['filter' => 'trending-themes', 'label' => $term->name];
                                                    }
                                                    $is_visible = in_array($term->slug, $adapt_visible_terms['trending-themes'] ?? [], true);
                                                ?>
                                                    <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    <?php endif; ?>

                            <a class="reset-filters-btn mobile-reset-button labelSmall text-grey font-bold desktop-hide">Reset Filters</a>
                        </div>

                        <span class="mobile-filter-dropdown labelSmall text-grey font-bold desktop-hide">
                            <span class="closed-text">Filter by topic, type and trend</span>
                            <span class="open-text">Hide filters</span>
                        </span>
                    </div>
                </div>

                <!-- SEARCH -->
                <div class="filter-search">
                    <form class="post-search-form">
                        <input type="text"
                            class="post-search-input"
                            placeholder="Search Insights">
                        <input type="image"
                            class="post-search-submit"
                            src="<?= esc_url(get_template_directory_uri() . '/assets/images/magnify-grey.svg'); ?>"
                            alt="Search">
                    </form>
                    <a class="reset-filters-btn labelSmall text-grey font-bold mobile-hide">Reset</a>
                </div>
            </div>


        </div>
    </div>



        <?php
            // Built from the active-state each dropdown loop above already
            // computed, so the pills can never disagree with which buttons
            // are shown as active.
            $pills_html = '';
            foreach ($active_filter_pills as $pill) {
                $pills_html .= '<button type="button" class="filter-pill" data-filter="' . esc_attr($pill['filter']) . '"><span>' . esc_html($pill['label']) . '</span><span class="pill-close">&times;</span></button>';
            }
        ?>
        <script>window.adaptFilterUIServerRendered = true;</script>
        <!-- Results -->
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
                        <a class="load-more-btn std-button red-button small-button" <?= $GLOBALS['adapt_has_more_posts']  ? 'style="display: inline;"' : ''; ?>>Load More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

