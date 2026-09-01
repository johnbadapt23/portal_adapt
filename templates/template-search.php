<?php
// ------------------------------
// GET PARAMS
// ------------------------------
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET search/filter params for a bookmarkable, shareable search URL; each value is sanitized via sanitize_text_field()/wp_unslash() before use, no state change.
$search  = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$persona = isset($_GET['persona']) ? sanitize_text_field(wp_unslash($_GET['persona'])) : '';
$sectors = isset($_GET['sector']) ? sanitize_text_field(wp_unslash($_GET['sector'])) : '';
$type    = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';
$topic   = isset($_GET['topicType']) ? sanitize_text_field(wp_unslash($_GET['topicType'])) : '';
$event   = isset($_GET['eventType']) ? sanitize_text_field(wp_unslash($_GET['eventType'])) : '';
$themes  = isset($_GET['theme']) ? sanitize_text_field(wp_unslash($_GET['theme'])) : '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended



$current_user = wp_get_current_user();
$member = new MeprUser($current_user->ID);
$advantageType = "no";
global $membershipType;
// Get the active subscriptions for this user
$active_subscriptions = $member->active_product_subscriptions('ids');

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

$membershipType = trim($membershipType);

// ------------------------------
// MEMBERSHIP-ALLOWED TYPES
// ------------------------------
$it_pro_types_ids    = get_field('it_pro_types', 'options') ?: [];
$advantage_types_ids = get_field('advantage_types', 'options') ?: [];

$membership_allowed_ids = [];

if ($membershipType === 'it-pro') {
    $membership_allowed_ids = $it_pro_types_ids;
} elseif ($membershipType === 'advantage') {
    $membership_allowed_ids = $advantage_types_ids;
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



<section class="title-banner filter-title-banner light-theme">
    <div class="container">
        <h1 class="header-large mobile-header-medium">
            Search results
        </h1>
    </div>
</section>

<section class="post-filtering-module background-white"
    data-post-type="post"
    data-search="<?= esc_attr($search); ?>">

<div class="filter-container-outer">
<div class="container">
<div class="filter-container-inner">
<div class="filters-wrapper">

<span class="filter-label labelSmall text-grey font-bold mobile-hide">
    Filter By:
</span>

<?php
// ------------------------------
// TOPICS — ALWAYS ALL
// ------------------------------
$topic_terms = get_terms([
    'taxonomy'   => 'topic',
    'hide_empty' => true,
    'parent'     => 0,
]);
$allowed_topic_slugs = [];

// ------------------------------
// TYPES — ALL, MEMBERSHIP FILTERED
// ------------------------------
$type_terms = get_terms([
    'taxonomy'   => 'filter-types',
    'hide_empty' => true,
    'parent'     => 0,
]);

$allowed_type_slugs = [];

if (!empty($membership_allowed_ids)) {
    $type_terms = array_filter($type_terms, function ($term) use ($membership_allowed_ids) {
        return in_array($term->term_id, $membership_allowed_ids, true);
    });

    $allowed_type_slugs = array_map(fn($t) => $t->slug, $type_terms);
}

// ------------------------------
// TRENDING THEMES — NOT ADVANTAGE
// ------------------------------
$trending_terms = [];
$allowed_trend_slugs = [];

if ($membershipType !== 'advantage') {
    $trending_terms = get_terms([
        'taxonomy'   => 'trending-themes',
        'hide_empty' => true,
        'parent'     => 0,
    ]);
}

// ------------------------------
// SORT TERMS
// ------------------------------
$sort_terms = fn(&$terms) =>
    is_array($terms) ? usort($terms, fn($a, $b) => strcmp($a->name, $b->name)) : null;

$sort_terms($topic_terms);
$sort_terms($type_terms);
$sort_terms($trending_terms);
?>

<div class="mobile-filter-accordion">
<div class="mobile-filter-content">

<!-- TOPICS -->
<?php $has_get_valueTopic = $topic !== ''; ?>
<div class="filter-dropdown" data-filter="topic" data-allowed='<?= esc_attr(wp_json_encode($allowed_topic_slugs)); ?>'>
    <span class="dropdown-title <?= $has_get_valueTopic ? 'filter-active' : ''; ?>">Topics</span>
    <div class="dropdown-list">
        <?php $all_value = '[]'; ?>
        <a href="#" class="filter-button all <?= $topic === '' ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
        <?php foreach ($topic_terms as $term) :
            $is_active = $term->slug === $topic;
            if ($is_active) {
                $active_filter_pills[] = ['filter' => 'topic', 'label' => $term->name];
            }
            $is_visible = in_array($term->slug, $adapt_visible_terms['topic'] ?? [], true);
        ?>
            <a href="#"
               class="filter-button <?= $is_active ? 'active' : ''; ?>"
               data-value="<?= esc_attr($term->slug); ?>"<?= $is_visible ? '' : ' style="display:none;"'; ?>>
                <?= esc_html($term->name); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- TYPES -->
<?php $has_get_valueType = $type !== ''; ?>
<div class="filter-dropdown" data-filter="type" data-allowed='<?= esc_attr(wp_json_encode($allowed_type_slugs)); ?>'>
    <span class="dropdown-title <?= $has_get_valueType ? 'filter-active' : ''; ?>">Types</span>
    <div class="dropdown-list">
        <?php
        $all_value = !empty($allowed_type_slugs)
            ? wp_json_encode($allowed_type_slugs)
            : '[]';
        ?>
        <a href="#" class="filter-button all <?= $type === '' ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
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

<!-- TRENDING THEMES 
<?php if ($membershipType !== 'advantage' && !empty($trending_terms)) : ?>
<?php $has_get_valueTheme = $themes !== ''; ?>
<div class="filter-dropdown" data-filter="trending-themes" data-allowed='<?= esc_attr(wp_json_encode($allowed_trend_slugs)); ?>'>
    <span class="dropdown-title <?= $has_get_valueTheme ? 'filter-active' : ''; ?>">Trending Themes</span>
    <div class="dropdown-list">
        <a href="#" class="filter-button all <?= $themes === '' ? 'active' : ''; ?>" data-value="[]">All</a>
        <?php foreach ($trending_terms as $term) : ?>
            <a href="#"
               class="filter-button <?= $term->slug === $themes ? 'active' : ''; ?>"
               data-value="<?= esc_attr($term->slug); ?>">
                <?= esc_html($term->name); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
-->
<a class="reset-filters-btn mobile-reset-button labelSmall text-grey font-bold desktop-hide">
    Reset Filters
</a>

</div>

<span class="mobile-filter-dropdown labelSmall text-grey font-bold desktop-hide">
    <span class="closed-text">Filter search results</span>
    <span class="open-text">Hide filters</span>
</span>

</div>
</div>

<!-- SEARCH INPUT -->
<div class="filter-search">
<form class="post-search-form">
    <input type="text"
           class="post-search-input"
           value="<?= esc_attr($search); ?>"
           placeholder="<?= esc_attr(get_field('search_help_text')); ?>">
    <input type="image"
           class="post-search-submit"
           src="<?= esc_url( get_template_directory_uri() ); ?>/assets/images/magnify-grey.svg"
           alt="Search">
</form>
<a class="reset-filters-btn labelSmall text-grey font-bold mobile-hide">Reset</a>
</div>

</div>
</div>
</div>

<?php
    // Built from the active-state each dropdown loop above already
    // computed, so the pills can never disagree with which buttons are
    // shown as active.
    $pills_html = '';
    foreach ($active_filter_pills as $pill) {
        $pills_html .= '<button type="button" class="filter-pill" data-filter="' . esc_attr($pill['filter']) . '"><span>' . esc_html($pill['label']) . '</span><span class="pill-close">&times;</span></button>';
    }
?>
<script>window.adaptFilterUIServerRendered = true;</script>
<!-- RESULTS -->
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
        <a href="#" class="filter-button all active" data-value="latest">Latest</a>
        <a href="#" class="filter-button" data-value="featured">Featured</a>
    </div>
</div>
</div>
</div>

<div class="ajax-loader" style="display: none;">
    <img src="<?= esc_url( get_template_directory_uri() ); ?>/assets/images/ajax-loading.gif" width="200" height="200" loading="lazy" decoding="async" alt="Loading">
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

</section>

