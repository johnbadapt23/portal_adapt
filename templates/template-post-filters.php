<?php
/**
 * Template Name: Post Type Filter Template
 */

get_header();
global $membershipType;
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET filter params for a bookmarkable, shareable listing URL; each value is sanitized via sanitize_text_field()/wp_unslash() before use, no state change.
$persona = isset($_GET['persona']) ? sanitize_text_field(wp_unslash($_GET['persona'])) : '';
$sectors = isset($_GET['sector']) ? sanitize_text_field(wp_unslash($_GET['sector'])) : '';
$type= isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';
$topic = isset($_GET['topicType']) ? sanitize_text_field(wp_unslash($_GET['topicType'])) : '';
$event = isset($_GET['eventType']) ? sanitize_text_field(wp_unslash($_GET['eventType'])) : '';
$themes = isset($_GET['theme']) ? sanitize_text_field(wp_unslash($_GET['theme'])) : '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended
?>


<main id="main" role="main" class="default whats-new">
<?php 
$membershipType = trim($membershipType);

$it_pro_types_ids    = get_field('it_pro_types', 'options') ?: [];
$advantage_types_ids = get_field('advantage_types', 'options') ?: [];

$membership_allowed_ids = [];

$researchLinkAdv = get_field( 'advantage_research_link', 'options' ); 
$researchLinkIt = get_field( 'it_pro_research_link', 'options' ); 
$researchLink = $researchLinkAdv;
if ($membershipType === 'it-pro') {
    $membership_allowed_ids = $it_pro_types_ids;
    $researchLink = $researchLinkIt;
} elseif ($membershipType === 'advantage') {
    $membership_allowed_ids = $advantage_types_ids;
    $researchLink = $researchLinkAdv;
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
        $type_terms = array_filter($type_terms, function ($term) use ($membership_allowed_ids) {
            return in_array($term->term_id, $membership_allowed_ids, true);
        });
        $allowed_type_slugs = array_map(fn($t) => $t->slug, $type_terms);
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
    // PERSONAS
    // ----------------------------------------
    if ( get_field('persona_filter') == 1 ) { 
        if ( get_field('all_personas') == 1 ) {
            $persona_terms = get_terms(['taxonomy'=>'persona-mapping','hide_empty'=>true,'parent'=>0]);
            $allowed_persona_slugs = [];
        } else {
            $persona_terms = get_field('personas') ?: [];
            $allowed_persona_slugs = array_map(fn($t)=>$t->slug, is_array($persona_terms)?$persona_terms:[]);
        }
    }

    // ----------------------------------------
    // SECTORS
    // ----------------------------------------
    if ( get_field('sector_filter') == 1 ) { 
        if ( get_field('all_sectors') == 1 ) {
            $sector_terms = get_terms(['taxonomy'=>'sector-analysis','hide_empty'=>true,'parent'=>0]);
            $allowed_sector_slugs = [];
        } else {
            $sector_terms = get_field('sectors') ?: [];
            $allowed_sector_slugs = array_map(fn($t)=>$t->slug, is_array($sector_terms)?$sector_terms:[]);
        }
    }

    // ----------------------------------------
    // EVENTS
    // ----------------------------------------
    if ( get_field('events_filter') == 1 ) { 
        if ( get_field('all_events') == 1 ) {
            $events_terms = get_terms(['taxonomy'=>'insights-event','hide_empty'=>true,'parent'=>0]);
            $allowed_events_slugs = [];
        } else {
            $events_terms = get_field('events') ?: [];
            $allowed_events_slugs = array_map(fn($t)=>$t->slug, is_array($events_terms)?$events_terms:[]);
        }
    }

    // ----------------------------------------
    // SORT TERMS
    // ----------------------------------------
    $sort_terms = fn(&$terms)=> is_array($terms) ? usort($terms, fn($a,$b)=>strcmp($a->name,$b->name)) : null;
    $sort_terms($topic_terms); $sort_terms($type_terms); $sort_terms($trending_terms);
    if(isset($persona_terms)) $sort_terms($persona_terms);
    if (isset($sector_terms) && !empty($sector_terms)) {
        usort($sector_terms, fn($a, $b) => strcmp($a->name, $b->name));
        $other_index = null;
        foreach ($sector_terms as $i => $term) if ($term->slug==='other') $other_index=$i;
        if($other_index!==null){
            $other_term = $sector_terms[$other_index];
            unset($sector_terms[$other_index]);
            $sector_terms = array_values($sector_terms);
            $sector_terms[] = $other_term;
        }
    }
    if(isset($events_terms)) $sort_terms($events_terms);
    ?>

    <div class="mobile-filter-accordion">
        <div class="mobile-filter-content">
            <input type="hidden" name="research_type_order" value="<?php echo esc_attr( get_field('research_type_order') ? '1' : '0' ); ?>" />
            <!-- Events -->
            <?php if(!empty($events_terms)) : ?>
            <div class="filter-dropdown" data-filter="event" data-allowed='<?= esc_attr(wp_json_encode($allowed_events_slugs)); ?>'>
                <span class="dropdown-title <?= ($event ?? '') !== '' ? 'filter-active' : ''; ?>">Events</span>
                <div class="dropdown-list">
                    <?php $all_value = !empty($allowed_events_slugs) ? wp_json_encode($allowed_events_slugs) : '[]'; ?>
                    <a href="#" class="filter-button all <?= empty($event) ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All Edge Events</a>
                    <?php foreach($events_terms as $term) :
                        $is_active = ($term->slug === ($event ?? ''));
                        if ($is_active) {
                            $active_filter_pills[] = ['filter' => 'event', 'label' => $term->name];
                        }
                        $is_visible = in_array($term->slug, $adapt_visible_terms['insights-event'] ?? [], true);
                    ?>
                        <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Topics -->
            <div class="filter-dropdown" data-filter="topic" data-allowed='<?= esc_attr(wp_json_encode($allowed_topic_slugs)); ?>'>
                <span class="dropdown-title <?= !empty($topic) ? 'filter-active' : ''; ?>">Topics</span>
                <div class="dropdown-list">
                    <?php $all_value = !empty($allowed_topic_slugs) ? wp_json_encode($allowed_topic_slugs) : '[]'; ?>
                    <a href="#" class="filter-button all <?= empty($topic) ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
                    <?php foreach($topic_terms as $term) :
                        $is_active = ($term->slug === ($topic ?? ''));
                        if ($is_active) {
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
                <span class="dropdown-title <?= !empty($type) ? 'filter-active' : ''; ?>">Types</span>
                <div class="dropdown-list">
                    <?php $all_value = !empty($allowed_type_slugs) ? wp_json_encode($allowed_type_slugs) : '[]'; ?>
                    <a href="#" class="filter-button all <?= empty($type) ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
                    <?php foreach($type_terms as $term) :
                        $is_active = ($term->slug === ($type ?? ''));
                        if ($is_active) {
                            $active_filter_pills[] = ['filter' => 'type', 'label' => $term->name];
                        }
                        $is_visible = in_array($term->slug, $adapt_visible_terms['filter-types'] ?? [], true);
                    ?>
                        <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Personas -->
            <?php if (!empty($persona_terms)) : ?>
            <div class="filter-dropdown" data-filter="persona" data-allowed='<?= esc_attr(wp_json_encode($allowed_persona_slugs)); ?>'>
                <span class="dropdown-title <?= !empty($persona) ? 'filter-active' : ''; ?>">Personas</span>
                <div class="dropdown-list">
                    <?php $all_value = !empty($allowed_persona_slugs) ? wp_json_encode($allowed_persona_slugs) : '[]'; ?>
                    <a href="#" class="filter-button all <?= empty($persona) ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
                    <?php foreach ($persona_terms as $term) :
                        $is_active = ($term->slug === ($persona ?? ''));
                        if ($is_active) {
                            $active_filter_pills[] = ['filter' => 'persona', 'label' => $term->name];
                        }
                        $is_visible = in_array($term->slug, $adapt_visible_terms['persona-mapping'] ?? [], true);
                    ?>
                        <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Sectors -->
            <?php if(!empty($sector_terms)) : ?>
            <div class="filter-dropdown" data-filter="sector" data-allowed='<?= esc_attr(wp_json_encode($allowed_sector_slugs)); ?>'>
                <?php
                // $sectors (not $sector - nothing sets that name) holds the
                // sanitized $_GET['sector'] value parsed at the top of this
                // file; using the undefined $sector here meant a bookmarked
                // ?sector=slug URL never marked the right button active (and
                // "All" was *always* considered active, since empty() on an
                // undefined variable is true).
                ?>
                <span class="dropdown-title <?= !empty($sectors) ? 'filter-active' : ''; ?>">Sectors</span>
                <div class="dropdown-list">
                    <?php $all_value = !empty($allowed_sector_slugs) ? wp_json_encode($allowed_sector_slugs) : '[]'; ?>
                    <a href="#" class="filter-button all <?= empty($sectors) ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
                    <?php foreach($sector_terms as $term) :
                        $is_active = ($term->slug === ($sectors ?? ''));
                        if ($is_active) {
                            $active_filter_pills[] = ['filter' => 'sector', 'label' => $term->name];
                        }
                        $is_visible = in_array($term->slug, $adapt_visible_terms['sector-analysis'] ?? [], true);
                    ?>
                        <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Trending Themes -->
            <?php if($membershipType != 'advantage' && $membershipType != 'it-pro' && !empty($trending_terms)) : ?>
            <div class="filter-dropdown" data-filter="trending-themes" data-allowed='<?= esc_attr(wp_json_encode($allowed_trend_slugs)); ?>'>
                <span class="dropdown-title <?= !empty($themes) ? 'filter-active' : ''; ?>">Trending Themes</span>
                <div class="dropdown-list">
                    <?php $all_value = !empty($allowed_trend_slugs) ? wp_json_encode($allowed_trend_slugs) : '[]'; ?>
                    <a href="#" class="filter-button all <?= empty($themes) ? 'active' : ''; ?>" data-value='<?= esc_attr($all_value); ?>'>All</a>
                    <?php foreach($trending_terms as $term) :
                        $is_active = ($term->slug === ($themes ?? ''));
                        if ($is_active) {
                            $active_filter_pills[] = ['filter' => 'trending-themes', 'label' => $term->name];
                        }
                        $is_visible = in_array($term->slug, $adapt_visible_terms['trending-themes'] ?? [], true);
                    ?>
                        <a href="#" class="filter-button <?= $is_active ? 'active' : ''; ?>" data-value='<?= esc_attr($term->slug); ?>'<?= $is_visible ? '' : ' style="display:none;"'; ?>><?= esc_html($term->name); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
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


</main>

<?php get_footer(); ?>
