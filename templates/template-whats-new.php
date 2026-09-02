<?php
/**
 * Template Name: Whats New Template
 */

get_header();
?>

<main id="main" role="main" class="default whats-new">
<?php 
$membershipType = trim($membershipType);

$it_pro_types_ids    = get_field('it_pro_types', 'options') ?: [];
$advantage_types_ids = get_field('advantage_types', 'options') ?: [];

$membership_allowed_ids = [];

$membership_allowed_ids = match ($membershipType) {
    'it-pro'    => $it_pro_types_ids,
    'advantage' => $advantage_types_ids,
    default     => $membership_allowed_ids,
}; ?>
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

// $topic/$type are read by the topic/type dropdowns further down (as the
// "which term is currently selected" comparison) but were never assigned
// anywhere in this file, so every comparison against them silently always
// evaluated false (undefined evaluates as null, and no term slug ever
// equals null) - meaning a deep link like /whats-new/?topicType=x&type=y
// never actually pre-selected anything, contrary to what the dropdown
// markup below is built to do. Sibling templates that render the exact
// same dropdown pattern (template-post-filters.php, template-search.php)
// already read these from $_GET['topicType']/$_GET['type'] - wiring this
// page up the same way instead of just silencing the warning.
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET filter params for a bookmarkable, shareable listing URL; no state change results from reading them.
$topic = isset($_GET['topicType']) ? sanitize_text_field(wp_unslash($_GET['topicType'])) : '';
$type  = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended
?>
<section class="title-banner light-theme">
    <div class="container">
        <h1 class="header-large mobile-header-medium"><?php echo esc_html( get_the_title() ); ?></h1>
        <?php if ($membershipType == 'advantage') { ?>
            <p><?php echo esc_html( get_field( 'whats_new_subtitle', 'options' ) ); ?></p>
        <?php } else { ?>
            <p><?php echo esc_html( get_field( 'whats_new_subtitle_it', 'options' ) ); ?></p>
        <?php } ?>          
    </div>
</section>
<section class="whats-new whats-new-module post-filtering-module background-white"
    data-page-id="<?php the_ID(); ?>"
    data-post-type="post"
    data-date-range="3-months">

    <div class="filter-container-outer">
        <div class="container">
            <div class="filter-container-inner">
                <div class="filters-wrapper">

                    <span class="filter-label labelSmall text-grey font-bold mobile-hide">
                        Filter By:
                    </span>

                    <div class="mobile-filter-accordion">
                        <div class="mobile-filter-content">

                            <?php
                            /**
                             * Base query: posts from last 3 months
                             */
                            $recent_posts = get_posts([
                                'post_type'      => 'post',
                                'posts_per_page' => -1,
                                'fields'         => 'ids',
                                'date_query'     => [[
                                    'after' => wp_date('Y-m-01', strtotime('-2 months')),
                                ]],
                            ]);

                            /**
                             * Collect Topics (parent level only)
                             */
                            $topic_terms = get_terms(['taxonomy'=>'topic','hide_empty'=>true,'parent'=>0]);
                            $allowed_topic_slugs = [];


                            /**
                             * Collect Types (parent level only)
                             */
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

                            /**
                             * Build last 3 months (current + previous 2)
                             */
                            $months = [];
                            for ($i = 0; $i < 3; $i++) {
                                $timestamp = strtotime("-{$i} months");
                                $months[] = [
                                    'label' => wp_date('F Y', $timestamp),
                                    'value' => wp_date('Y-m', $timestamp),
                                ];
                            }

                            // ----------------------------------------
                            // SORT TERMS
                            // ----------------------------------------
                            $sort_terms = fn(&$terms)=> is_array($terms) ? usort($terms, fn($a,$b)=>strcmp($a->name,$b->name)) : null;
                            $sort_terms($topic_terms); $sort_terms($type_terms);
                            ?>

                            <!-- Topics Filter -->
                            <div class="filter-dropdown" data-filter="topic" data-allowed='<?= esc_attr(wp_json_encode($allowed_topic_slugs)); ?>'>
                                <span class="dropdown-title">Topics</span>
                                <div class="dropdown-list">
                                    <?php 
                                    $all_value = !empty($allowed_topic_slugs) ? wp_json_encode($allowed_topic_slugs) : '[]';
                                    $active_found = false;
                                    ?>
                                    <a href="#" class="filter-button all<?= $topic === '' ? ' active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
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

                            <!-- Types Filter -->
<div class="filter-dropdown" data-filter="type" data-allowed='<?= esc_attr(wp_json_encode($allowed_type_slugs)); ?>'>
    <span class="dropdown-title">Types</span>
    <div class="dropdown-list">
        <?php 
        // Rebuild 'All' value as 0-indexed array for JS
        $allowed_type_slugs = array_values($allowed_type_slugs);
        $all_value = !empty($allowed_type_slugs) ? wp_json_encode($allowed_type_slugs) : '[]';
        $active_found = false;
        ?>
        <a href="#" class="filter-button all<?= $type === '' ? ' active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>

        <?php foreach($type_terms as $term) :
            $is_active = $term->slug === $type;
            if($is_active) {
                $active_found = true;
                $active_filter_pills[] = ['filter' => 'type', 'label' => $term->name];
            }
            $is_visible = in_array($term->slug, $adapt_visible_terms['filter-types'] ?? [], true);
        ?>
            <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
        <?php endforeach; ?>

        <?php if($type !== '' && !$active_found) : ?>
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    const dropdown = document.querySelector('.filter-dropdown[data-filter="type"]');
                    if(dropdown){
                        dropdown.querySelector('.filter-button[data-value=' + <?= json_encode($all_value); ?> + ']').classList.add('active');
                    }
                });
            </script>
        <?php endif; ?>
    </div>
</div>



                            <!-- Date Filter -->
                            <div class="filter-dropdown" data-filter="date">
                                <span class="dropdown-title">Date</span>
                                <div class="dropdown-list">
                                    <?php
                                    $last_3_months_values = wp_json_encode(array_map(fn($m) => $m['value'], $months));
                                    ?>
                                    <a href="#"
                                    class="filter-button all active"
                                    data-value='<?php echo esc_attr($last_3_months_values); ?>'>
                                        All (Last 3 months)
                                    </a>
                                    <?php foreach ($months as $month) :
                                        $is_visible = in_array($month['value'], $adapt_visible_terms['date'] ?? [], true);
                                    ?>
                                        <a href="#"
                                           class="filter-button"
                                           data-value="<?php echo esc_attr($month['value']); ?>"<?= $is_visible ? '' : ' style="display:none;"'; ?>>
                                            <?php echo esc_html($month['label']); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <a class="reset-filters-btn mobile-reset-button labelSmall text-grey font-bold desktop-hide">
                                Reset Filters
                            </a>
                        </div>

                        <span class="mobile-filter-dropdown labelSmall text-grey font-bold desktop-hide">
                            <span class="closed-text">Filter by topic, type and date</span>
                            <span class="open-text">Hide filters</span>
                        </span>
                    </div>
                </div>

                <!-- Search -->
                <div class="filter-search">
                    <form class="post-search-form">
                        <input type="text"
                               class="post-search-input"
                               placeholder="e.g. - State of the Nation" />
                        <input class="post-search-submit"
                               type="image"
                               alt="Search"
                               src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/magnify-grey.svg" />
                    </form>
                    <a class="reset-filters-btn labelSmall text-grey font-bold mobile-hide">
                        Reset
                    </a>
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
    <!-- Results -->
    <div class="container">
        <div class="whats-new-container-outer">
            <div class="whats-new-filter-inner">
                <div class="sort-pills-container">
                    <div class="results-container">
                        <span class="search-results-label" style="display:none;"></span>
                        <div class="active-filter-pills"<?= empty($active_filter_pills) ? ' style="display:none;"' : ''; ?>><?= $pills_html; ?></div>
                    </div>
                    <div class="sort-dropdown<?php if ($membershipType === 'advantage') { ?> hide<?php } ?>">
                        <div class="filter-dropdown" data-filter="sort" data-allowed="[]">
                            <span class="dropdown-title">Sort by</span>

                            <div class="dropdown-list">
                                <a href="#"
                                class="filter-button"
                                data-value="latest">
                                    Latest
                                </a>

                                <a href="#"
                                class="filter-button active"
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
                <div class="whats-new resources-column-container three-column-container gap-16-40" id="posts-container">
                    <?= $posts_container_html; ?>
                </div>
                <div class="page-navi-container post-pagination-container">
                    <a class="load-more-btn std-button red-button small-button"  <?= $GLOBALS['adapt_has_more_posts']  ? 'style="display: inline;"' : ''; ?>
                       id="posts-load-more"
                       data-page="1">
                        Load More
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
  
</main>

<?php get_footer(); ?>
