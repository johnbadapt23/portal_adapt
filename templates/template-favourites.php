<?php
/**
 * Template Name: Whats New (Favourites)
 */

get_header();

$favorites        = get_user_favorites();
$favouritesCount  = get_user_favorites_count();
$filterMin        = get_field('favourite_filtering_minimum', 'options');

// Rendered here (before the filter dropdowns below), same as every other
// filtering template, via adapt_render_favourite_posts() - NOT
// adapt_render_filter_posts(), which has no favourites-scoping (no
// post__in) and would show unfiltered, site-wide posts on first load
// instead of the user's favourited posts. See adapt_render_favourite_posts()
// in functions.php, which is shared with ajax_load_favourite_posts() so the
// two can never drift apart.
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET search/filter params for a bookmarkable, shareable favourites URL; sanitized before use, no state change.
ob_start();
adapt_render_favourite_posts([
    'search' => sanitize_text_field(wp_unslash($_GET['search'] ?? '')),
    'topic'  => sanitize_text_field(wp_unslash($_GET['topic'] ?? '')),
    'type'   => sanitize_text_field(wp_unslash($_GET['type'] ?? '')),
]);
// phpcs:enable WordPress.Security.NonceVerification.Recommended
$favourite_posts_html = ob_get_clean();
?>

<main id="main" role="main" class="default whats-new">

<section class="title-banner light-theme">
    <div class="container">
        <h1 class="header-large mobile-header-medium"><?php echo esc_html( get_the_title() ); ?></h1>
        <p><?php echo esc_html( get_field('whats_new_subtitle', 'options') ); ?></p>
    </div>
</section>

<?php if ($favorites && $favouritesCount >= $filterMin) : ?>

<section class="whats-new whats-new-module post-filtering-module background-white"
    data-post-type="post"
    data-is-favourites="1">

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
                             * Collect recent favourite posts (last 3 months)
                             */
                            $recent_posts = get_posts([
                                'post_type'      => 'post',
                                'post__in'       => $favorites,
                                'posts_per_page' => -1,
                                'fields'         => 'ids'
                            ]);

                            /**
                             * Topics and Types (parent only), batched via a single
                             * get_terms() per taxonomy instead of calling
                             * get_the_terms() once per post - $recent_posts comes
                             * from a fields=>ids query, which never primes the term
                             * cache, so a per-post loop here would run one
                             * uncached query per post per taxonomy (2N total).
                             * object_ids also dedupes across posts for free, same
                             * as adapt_get_visible_terms() in functions.php.
                             */
                            if (!empty($recent_posts)) {
                                $topic_terms = get_terms([
                                    'taxonomy'   => 'topic',
                                    'object_ids' => $recent_posts,
                                    'parent'     => 0,
                                    'hide_empty' => false,
                                    'orderby'    => 'name',
                                    'order'      => 'ASC',
                                ]);
                                $topic_terms = is_array($topic_terms) ? $topic_terms : [];

                                $type_terms = get_terms([
                                    'taxonomy'   => 'filter-types',
                                    'object_ids' => $recent_posts,
                                    'parent'     => 0,
                                    'hide_empty' => false,
                                    'orderby'    => 'name',
                                    'order'      => 'ASC',
                                ]);
                                $type_terms = is_array($type_terms) ? $type_terms : [];
                            } else {
                                $topic_terms = [];
                                $type_terms  = [];
                            }
                            ?>

                            <!-- Topics -->
                            <div class="filter-dropdown" data-filter="topic">
                                <span class="dropdown-title">Topics</span>
                                <div class="dropdown-list">
                                    <a href="#" class="filter-button active" data-value="">All</a>
                                    <?php foreach ($topic_terms as $term): ?>
                                        <a href="#" class="filter-button" data-value="<?= esc_attr($term->slug); ?>">
                                            <?= esc_html($term->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Types -->
                            <div class="filter-dropdown" data-filter="type">
                                <span class="dropdown-title">Types</span>
                                <div class="dropdown-list">
                                    <a href="#" class="filter-button active" data-value="">All</a>
                                    <?php foreach ($type_terms as $term): ?>
                                        <a href="#" class="filter-button" data-value="<?= esc_attr($term->slug); ?>">
                                            <?= esc_html($term->name); ?>
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
                        <input type="text" class="post-search-input" placeholder="e.g. - State of the Nation">
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

    <!-- Results -->
    <div class="container">
        <div class="whats-new-container-outer">
            <div class="whats-new-filter-inner">
                <div class="ajax-loader" style="display: none;">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/ajax-loading.gif" width="200" height="200" loading="lazy" decoding="async" alt="Loading..." />
                </div>
                <div class="whats-new resources-column-container three-column-container gap-16-40"
                     id="posts-container">
                    <?= $favourite_posts_html; ?>
                    </div>

                <div class="page-navi-container post-pagination-container">
                    <a class="load-more-btn std-button red-button small-button"  <?= $GLOBALS['adapt_has_more_posts']  ? 'style="display: inline;"' : ''; ?>>Load More</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php else : ?>

<section class="container">
    <p>You don’t have enough favourites to filter yet.</p>
</section>

<?php endif; ?>

</main>

<?php get_footer(); ?>
