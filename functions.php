<?php

// Includes
require('includes/_hooks.php');
require('includes/_setup.php');
require('includes/_head.php');
require('includes/_menu.php');
require('includes/_widgets.php');
require('includes/_shortcodes.php');
require('includes/_functions.php');
require('includes/_customisations.php');
require('includes/_welcome-popup.php');
require('includes/_feedback-survey.php');

function cc_mime_types($mimes) {
  $mimes['json'] = 'text/plain';
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}

function adapt_is_staging() {
    return (
        wp_get_environment_type() === 'staging' ||
        str_contains($_SERVER['HTTP_HOST'] ?? '', 'staging') ||
        str_contains($_SERVER['HTTP_HOST'] ?? '', 'devstage')
    );
}

function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyDss6XUuPFsJgunJJ6dZZjzuR9d39WtjRU');
}

/**
 * Crawler content unlock.
 * While adapt_content_unlocked() returns true, grant the MemberPress
 * capabilities so every current_user_can('memberpress_authorized') and
 * current_user_can('mepr-active', ...) check in the theme passes —
 * including for anonymous visitors (e.g. the sitemap crawler).
 */
add_filter('user_has_cap', function ($allcaps, $required_caps, $args, $user) {
    // Re-entry guard: if adapt_content_unlocked() itself calls current_user_can()
    // (or any MemberPress helper that does), this filter would recurse infinitely.
    static $running = false;
    if ($running || !function_exists('adapt_content_unlocked')) {
        return $allcaps;
    }

    // Cache the result for the request so the plugin function runs only once.
    static $unlocked = null;
    if ($unlocked === null) {
        $running  = true;
        $unlocked = (bool) adapt_content_unlocked();
        $running  = false;
    }
    if (!$unlocked) {
        return $allcaps;
    }

    foreach ((array) $required_caps as $cap) {
        if ($cap === 'memberpress_authorized' || str_starts_with($cap, 'mepr-active')) {
            $allcaps[$cap] = true;
        }
    }
    // Also cover direct checks where the requested cap is in $args[0]
    if (isset($args[0]) && ($args[0] === 'memberpress_authorized' || strpos((string) $args[0], 'mepr-active') === 0)) {
        $allcaps[$args[0]] = true;
    }
    return $allcaps;
}, 99, 4);

/**
 * Global Membership Setup
 * Moves all header membership logic to functions.php
 * Provides:
 *  - $membershipType
 *  - $advantageType
 *  - CSV logging for search/filter
 */


// ------------------------------
// Membership Initialization & Global Term Name Override
// -----------------------
// 
// -------

function adjust_term_name_for_membership($term) {

    // Always fail safely
    if (!$term || !isset($term->slug) || !isset($term->taxonomy)) {
        return $term;
    }

    // Guests → no adjustment
    if (!(is_user_logged_in() || (function_exists('adapt_content_unlocked') && adapt_content_unlocked()))) {
        return $term;
    }

    global $membershipType;

    // If membership not set, do nothing
    if (empty($membershipType)) {
        return $term;
    }

    // Only adjust specific taxonomy + slug
    if ($term->taxonomy === 'filter-types' && $term->slug === 'community-interviews') {

        $term = clone $term; // Prevent modifying cached WP object

        if ($membershipType === 'advantage') {
            $term->name = 'Voice of Customer';
        } else {
            $term->name = 'Community Interviews';
        }
    }

    return $term;
}

add_action('wp', function() {

    // ------------------------------
    // 0. Skip admin completely
    // ------------------------------
    if (is_admin()) return;

    // ------------------------------
    // 1. Declare globals for templates
    // ------------------------------
    global $membershipType, $advantageType, $member;
    global $current_user, $first_name, $last_name, $user_email, $interests;

    $membershipType = 'default';
    $advantageType  = 'no';
    $member         = null;
    $current_user   = wp_get_current_user();
    $first_name     = '';
    $last_name      = '';
    $user_email     = '';
    $interests      = '';

    // ------------------------------
    // 2. Redirect non-logged-in users from front page
    // ------------------------------
    if (0 === $current_user->ID) {
        if (is_front_page()) {
            wp_safe_redirect('/login/');
            exit;
        }
        return;
    }

    $user_ID = $current_user->ID;

    // ------------------------------
    // 3. Initialize MemberPress user
    // ------------------------------
    if (class_exists('MeprUser')) {
        $member = new MeprUser($user_ID);
    }

    // ------------------------------
    // 4. Set globals for templates
    // ------------------------------
    $first_name = $current_user->first_name;
    $last_name  = $current_user->last_name;
    $user_email = $current_user->user_email;
    $interests  = $current_user->mepr_interests ?? [];

    // ------------------------------
    // 5. Update user meta once per day
    // ------------------------------
    if ($member) {
        $transient_key = 'mepr_meta_updated_' . $user_ID;
        if (false === get_transient($transient_key)) {
            update_user_meta($user_ID, 'mepr_logins', $member->login_count);
            $subscriptions = $member->get_active_subscription_titles(', ');
            update_user_meta($user_ID, 'mepr_subscriptions', $subscriptions);
            set_transient($transient_key, true, DAY_IN_SECONDS);
        }
    }

    // ------------------------------
    // 6. Membership type determination (cached)
    // ------------------------------
    $membershipType = 'default';

    $membership_ids = get_transient('membership_ids');
    if ($membership_ids === false) {
        $get_membership_ids = function($field) {
            if (!have_rows($field, 'options')) return [];
            $ids = [];
            while (have_rows($field, 'options')) {
                the_row();
                $ids[] = get_sub_field('membership_id');
            }
            return $ids;
        };

        $membership_ids = [
            'free' => $get_membership_ids('free_trial_memberships'),
            'adv'  => $get_membership_ids('advantage_memberships'),
            'it'   => $get_membership_ids('it_pro_memberships'),
            'kyc'  => $get_membership_ids('kyc_memberships'),
        ];

        set_transient('membership_ids', $membership_ids, HOUR_IN_SECONDS);
    }

    $active_subscriptions = $member?->active_product_subscriptions('ids') ?? [];

    $membershipType = match (true) {
        (bool) array_intersect($membership_ids['adv'], $active_subscriptions)  => 'advantage',
        (bool) array_intersect($membership_ids['it'], $active_subscriptions)   => 'it-pro',
        (bool) array_intersect($membership_ids['free'], $active_subscriptions) => 'free-trial',
        (bool) array_intersect($membership_ids['kyc'], $active_subscriptions)  => 'kyc',
        default => 'default',
    };

    // ------------------------------
    // 7. Set advantageType specifically
    // ------------------------------
    $advantageType = 'no';
    if (
        current_user_can('mepr-active') &&
        array_intersect([49140, 3829, 36884, 41272], $active_subscriptions)
    ) {
        $advantageType = 'yes';
    }

    // ------------------------------
    // 8. Memoized term adjustment for membership
    // ------------------------------
    $adjusted_terms_cache = [];
    $adjust_term_cached = function($term) use (&$adjusted_terms_cache) {
        if (!$term) return $term;
        if (isset($adjusted_terms_cache[$term->term_id])) return $adjusted_terms_cache[$term->term_id];
        $adjusted_terms_cache[$term->term_id] = adjust_term_name_for_membership($term);
        return $adjusted_terms_cache[$term->term_id];
    };

    // ------------------------------
    // 9. Apply filters globally (front-end only)
    // ------------------------------
    add_filter('get_term', function($term) use ($adjust_term_cached) {
        return $adjust_term_cached($term);
    }, 10, 1);

    add_filter('get_term_by', function($term) use ($adjust_term_cached) {
        return $adjust_term_cached($term);
    }, 10, 4);

    add_filter('get_the_terms', function($terms) use ($adjust_term_cached) {
        if (!$terms) return $terms;
        foreach ($terms as $key => $term) {
            $terms[$key] = $adjust_term_cached($term);
        }
        return $terms;
    }, 10, 3);

    add_filter('term_link', function($url, $term) use ($adjust_term_cached) {
        $adjust_term_cached($term); // ensures term is cached
        return $url;
    }, 10, 3);

}, 20);


add_action('acf/init', 'my_acf_init');
add_filter('upload_mimes', 'cc_mime_types');

add_image_size( 'gallery-landscape', 1280, 800, true );
add_image_size( 'gallery-portrait', 800, 1280, true );
add_image_size( 'hero-slide-preview', 900, 0 );

add_filter( 'https_ssl_verify', '__return_false' );

add_action('wp_enqueue_scripts', 'my_register_javascript', 100);

function my_register_javascript() {
  wp_register_script('mediaelement', plugins_url('wp-mediaelement.min.js', __FILE__), array('jquery'), '4.8.2', true);
  wp_enqueue_script('mediaelement');
}

function adapt_admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri(). '/assets/css/admin.css');
}
add_action('admin_enqueue_scripts', 'adapt_admin_style');

// Ajax
add_action('wp_ajax_myfilter', 'ajaxtest_function'); // wp_ajax_{ACTION HERE}
add_action('wp_ajax_nopriv_myfilter', 'ajaxtest_function');

function ajaxtest_function() {
    check_ajax_referer( 'adapt_ajax_nonce', 'nonce' );
    // Ensure 'mepr_interests' is set and valid
    if (isset($_POST['mepr_interests']) && !empty($_POST['mepr_interests'])) {
        global $current_user;
        $user_id = get_current_user_id();
        // Ensure a valid user
        if ($user_id && class_exists('MeprUser')) {
            $member = new MeprUser($user_id);

            // $_POST['mepr_interests'] was being stored completely raw - it's
            // consumed in header.php as an associative array keyed by topic
            // term slug with the literal string 'on' as each value (a plain
            // checkbox-name-array submission: mepr_interests[slug]=on), so
            // sanitize to exactly that shape instead of trusting whatever
            // was posted. Anything not matching that pattern is dropped
            // rather than stored.
            $raw_interests = wp_unslash($_POST['mepr_interests']);
            $interests = array();
            if (is_array($raw_interests)) {
                foreach ($raw_interests as $slug => $value) {
                    $slug = sanitize_title($slug);
                    if ($slug && $value === 'on') {
                        $interests[$slug] = 'on';
                    }
                }
            }

            // Check if the user is active and not subscribed to membership 9811
            if (user_can($user_id, 'mepr-active') && !$member->is_already_subscribed_to(9811)) {
                // Update the user meta
                update_user_meta($user_id, 'mepr_interests', $interests);

                // Trigger the profile update action
                // do_action('profile_update', $user_id, $member);
                do_action('adapt_profile_metrics_updated', $user_id);

                // Respond with success
                wp_send_json_success();
            } else {
                // Only update the meta if the user is subscribed to 9811 or inactive
                update_user_meta($user_id, 'mepr_interests', $interests);
                wp_send_json_success('Meta updated, profile not updated');
            }
        } else {
            wp_send_json_error('User not logged in');
        }
    } else {
        wp_send_json_error('Invalid or missing data');
    }

    die();
}


// Don't show duplicate posts in loops
add_filter('post_link', 'track_displayed_posts');
add_action('pre_get_posts','remove_already_displayed_posts');

$displayed_posts = [];

function track_displayed_posts($url) {
  global $displayed_posts;
  $displayed_posts[] = get_the_ID();
  return $url; // don't mess with the url
}

function remove_already_displayed_posts($query) {
 if ( is_admin() ) {
     return;
 }
 global $displayed_posts;
 $query->set('post__not_in', $displayed_posts);
}

// Wire up the category checkbox filter on the insights/blog archive
// (templates/template-post.php submits ?categories[]=slug via GET but
// nothing was applying it to the main query, so the filter never did
// anything).
add_action( 'pre_get_posts', 'adapt_filter_insights_by_category' );
function adapt_filter_insights_by_category( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
        return;
    }
    if ( empty( $_GET['categories'] ) ) {
        return;
    }
    $categories = array_map( 'sanitize_text_field', wp_unslash( (array) $_GET['categories'] ) );
    $categories = array_filter( $categories );
    if ( empty( $categories ) ) {
        return;
    }
    $query->set( 'tax_query', array(
        array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => $categories,
        ),
    ) );
}

/**
 * Adapted MemberPress / User Activity Tracking
 * Optimized for CPU, transients, and safe array handling
 */

/**
 * Update the monthly count meta for users
 */
function mepr_update_monthly_count($user_id, $meta_key_array, $meta_key_total) {
    $current_month = date('Ym'); // e.g., 202510
    $data = get_user_meta($user_id, $meta_key_array, true);

    if (!is_array($data)) $data = [];

    // Increment count for current month
    $found = false;
    foreach ($data as &$month_entry) {
        if ($month_entry['month'] === $current_month) {
            $month_entry['count']++;
            $found = true;
            break;
        }
    }
    unset($month_entry);

    if (!$found) {
        $data[] = ['month' => $current_month, 'count' => 1];
    }

    // Keep only last 12 months
    $twelve_months_ago = date('Ym', strtotime('-12 months'));
    $new_data = array_filter($data, fn($entry) => $entry['month'] >= $twelve_months_ago);

    // Update stored array
    update_user_meta($user_id, $meta_key_array, array_values($new_data));

    // Calculate rolling total
    $total = array_sum(array_column($new_data, 'count'));
    update_user_meta($user_id, $meta_key_total, $total);

    // Clear transient for caching optimization
    delete_transient('user_activity_' . $user_id);
}

/**
 * Ajax handler to increment user download counters
 */
add_action('wp_ajax_update_download_counter', 'update_download_counter');
add_action('wp_ajax_nopriv_update_download_counter', 'update_download_counter');
function update_download_counter() {
    check_ajax_referer( 'adapt_ajax_nonce', 'nonce' );
    if (!is_user_logged_in()) wp_die();

    $user_id = get_current_user_id();

    // Exit if user inactive or in special membership
    if (class_exists('MeprUser')) {
        $member = new MeprUser($user_id);
        if (!user_can($user_id, 'mepr-active') || $member->is_already_subscribed_to(9811)) return wp_die();
    }

    // Article download increment
    $article_downloads = (int) get_user_meta($user_id, 'mepr_article_downloads', true);
    $article_downloads = max(1, $article_downloads + 1);
    update_user_meta($user_id, 'mepr_article_downloads', $article_downloads);

    // 30-day download array
    $download_array = get_user_meta($user_id, 'mepr_downloads_30_days_array', true);
    if (!is_array($download_array)) $download_array = [];

    $current_date = date('Ymd');
    $download_array[] = ['date' => $current_date];

    $thirty_days_ago = date('Ymd', strtotime('-30 days'));
    $download_array = array_filter($download_array, fn($d) => $d['date'] >= $thirty_days_ago);

    update_user_meta($user_id, 'mepr_downloads_30_days_array', array_values($download_array));
    update_user_meta($user_id, 'mepr_downloads_thirty_days', count($download_array));

    // Update 12-month rolling count
    mepr_update_monthly_count($user_id, 'mepr_downloads_12_months_array', 'mepr_downloads_twelve_months');

    // Clear transient to save CPU
    delete_transient('user_activity_' . $user_id);

    do_action('adapt_profile_updated', $user_id, wp_get_current_user());

    wp_die();
}

/**
 * One-time function to add 30-day count to 12-month count
 */
function mepr_add_to_monthly_count($user_id, $meta_key_array, $meta_key_total, $increment = 0) {
    if ($increment <= 0) return;

    $current_month = date('Ym');
    $data = get_user_meta($user_id, $meta_key_array, true);
    if (!is_array($data)) $data = [];

    $found = false;
    foreach ($data as &$month_entry) {
        if ($month_entry['month'] === $current_month) {
            $month_entry['count'] += $increment;
            $found = true;
            break;
        }
    }
    unset($month_entry);

    if (!$found) {
        $data[] = ['month' => $current_month, 'count' => $increment];
    }

    $twelve_months_ago = date('Ym', strtotime('-12 months'));
    $new_data = array_filter($data, fn($entry) => $entry['month'] >= $twelve_months_ago);

    update_user_meta($user_id, $meta_key_array, array_values($new_data));
    update_user_meta($user_id, $meta_key_total, array_sum(array_column($new_data, 'count')));

    delete_transient('user_activity_' . $user_id);
}

/**
 * Trim last year's same month on the 1st day of each month
 */
function mepr_trim_last_year_month($user_id, $meta_key_array, $meta_key_total) {
    if (date('d') !== '01') return;

    $data = get_user_meta($user_id, $meta_key_array, true);
    if (!is_array($data)) return;

    $last_year_month = date('Ym', strtotime('-12 months'));
    $new_data = array_filter($data, fn($entry) => $entry['month'] !== $last_year_month);

    update_user_meta($user_id, $meta_key_array, array_values($new_data));
    update_user_meta($user_id, $meta_key_total, array_sum(array_column($new_data, 'count')));

    delete_transient('user_activity_' . $user_id);
}

/**
 * Cron job: nightly user activity update
 */
add_action('update_user_activity_info_event', 'update_user_activity_info');

function update_user_activity_info() {
    if (adapt_is_staging()) return;

    $users = get_users(['fields' => ['ID']]); // Only fetch IDs to save memory

    foreach ($users as $user) {
        $user_id = $user->ID;
        $member  = null;

        if (class_exists('MeprUser')) {
            $member = new MeprUser($user_id);
            if (!user_can($user_id, 'mepr-active') || $member->is_already_subscribed_to(9811)) continue;
        }

        $thirty_days_ago = date('Ymd', strtotime('-30 days'));

        // Post views
        $post_views_array = get_user_meta($user_id, 'mepr_post_views_30_days_array', true);
        if (!is_array($post_views_array)) $post_views_array = [];
        $post_views_array = array_filter($post_views_array, fn($view) => $view['date'] >= $thirty_days_ago);
        update_user_meta($user_id, 'mepr_post_views_30_days_array', array_values($post_views_array));
        update_user_meta($user_id, 'mepr_post_views_thirty_days', count($post_views_array));

        // Logins
        $login_array = get_user_meta($user_id, 'mepr_logins_30_days_array', true);
        if (!is_array($login_array)) $login_array = [];
        $login_array = array_filter($login_array, fn($l) => $l['date'] >= $thirty_days_ago);
        update_user_meta($user_id, 'mepr_logins_30_days_array', array_values($login_array));
        update_user_meta($user_id, 'mepr_logins_thirty_days', count($login_array));

        // Downloads
        $download_array = get_user_meta($user_id, 'mepr_downloads_30_days_array', true);
        if (!is_array($download_array)) $download_array = [];
        $download_array = array_filter($download_array, fn($d) => $d['date'] >= $thirty_days_ago);
        update_user_meta($user_id, 'mepr_downloads_30_days_array', array_values($download_array));
        update_user_meta($user_id, 'mepr_downloads_thirty_days', count($download_array));

        // Trim last year's same month
        mepr_trim_last_year_month($user_id, 'mepr_logins_12_months_array', 'mepr_logins_twelve_months');
        mepr_trim_last_year_month($user_id, 'mepr_downloads_12_months_array', 'mepr_downloads_twelve_months');
        mepr_trim_last_year_month($user_id, 'mepr_post_views_12_months_array', 'mepr_post_views_twelve_months');

        // Update active membership info
        if (user_can($user_id, 'mepr-active')) {
            // $member was already instantiated above for this same user
            // when the mepr-active check passed - reuse it instead of
            // re-querying MemberPress a second time per user, per run.
            if (!$member) {
                $member = class_exists('MeprUser') ? new MeprUser($user_id) : null;
            }
            if ($member) {
                update_user_meta($user_id, 'mepr_logins', $member->login_count);
                update_user_meta($user_id, 'mepr_subscriptions', $member->get_active_subscription_titles(', '));
            }
            update_user_meta($user_id, 'mepr_active_status', 'active');
        } else {
            update_user_meta($user_id, 'mepr_active_status', 'inactive');
        }

        // Clear transient after all updates
        delete_transient('user_activity_' . $user_id);
    }
}

// Schedule daily cron if not already scheduled
if (!wp_next_scheduled('update_user_activity_info_hook')) {
    wp_schedule_event(time(), 'daily', 'update_user_activity_info_hook');
}
add_action('update_user_activity_info_hook', 'update_user_activity_info');

/**
 * ============================================
 * Track user logins, profile updates, and send webhooks (queued)
 * ============================================
 */

/**
 * Track user login and update 30-day and monthly stats
 */
function track_user_logins($user_login, $user) {
    $user_id = $user->ID;

    if (class_exists('MeprUser')) {
        $member = new MeprUser($user_id);
        // Skip inactive users or users with memberships 9811 or 41272
        if (!user_can($user_id, 'mepr-active') 
            || $member->is_already_subscribed_to(9811)
            || $member->is_already_subscribed_to(41272)) {
            return;
        }
    }

    $today = date('Ymd');
    $thirty_days_ago = date('Ymd', strtotime('-30 days'));

    $login_array = get_user_meta($user_id, 'mepr_logins_30_days_array', true);
    if (!is_array($login_array)) $login_array = [];

    // Keep only last 30 days
    $login_array = array_filter($login_array, fn($l) => $l['date'] >= $thirty_days_ago);

    // Add current login
    $login_array[] = ['date' => $today];
    update_user_meta($user_id, 'mepr_logins_30_days_array', array_values($login_array));
    update_user_meta($user_id, 'mepr_logins_thirty_days', count($login_array));

    // Update 12-month rolling counts
    mepr_update_monthly_count($user_id, 'mepr_logins_12_months_array', 'mepr_logins_twelve_months');

    // Clear transient for cached activity
    delete_transient('user_activity_' . $user_id);

    // Queue webhook for this user instead of sending directly
    queue_webhook_user($user_id);

    // Trigger profile update hook
    do_action('adapt_profile_updated', $user_id, wp_get_current_user());
}
add_action('wp_login', 'track_user_logins', 10, 2);


/**
 * Queue a user for webhook processing
 */
function queue_webhook_user($user_id) {
    $queued = get_transient('webhook_user_queue') ?: [];

    // Avoid duplicates
    if (!in_array($user_id, array_column($queued, 'user_id'))) {
        $user_info = get_userdata($user_id);
        if ($user_info) {
            $queued[] = [
                'user_id'    => $user_id,
                'user_email' => $user_info->user_email,
            ];
            set_transient('webhook_user_queue', $queued, 20 * MINUTE_IN_SECONDS); // keep for 20 mins max
        }
    }
}


/**
 * Add 15-min interval for cron schedules
 */
add_filter('cron_schedules', function($schedules){
    $schedules['quarterhour'] = [
        'interval' => 15 * MINUTE_IN_SECONDS,
        'display'  => 'Every 15 Minutes'
    ];
    return $schedules;
});


/**
 * Cron to send queued webhooks in batches
 */
add_action( 'send_webhook_batch_cron', 'send_webhook_batch' );

if ( ! wp_next_scheduled( 'send_webhook_batch_cron' ) ) {
    wp_schedule_event( time(), 'quarterhour', 'send_webhook_batch_cron' );
}

/**
 * Send queued webhooks efficiently and safely
 */
function send_webhook_batch() {
    // Prevent overlapping cron runs
    if ( get_transient( 'webhook_batch_running' ) ) {
        return;
    }

    set_transient( 'webhook_batch_running', true, 2 * MINUTE_IN_SECONDS );

    $queued = get_transient( 'webhook_user_queue' );

    if ( empty( $queued ) || ! is_array( $queued ) ) {
        delete_transient( 'webhook_batch_running' );
        return;
    }

    $staging_url = 'https://hook.us1.make.com/j92pieoqi5nocmhvaow7vttbgpllnmdm';
    $live_url    = 'https://hook.us1.make.com/w3qklgxu9s32vawmifxhth3qi6ghlm8o';
    $url         = adapt_is_staging() ? $staging_url : $live_url;

    // Process only a small batch per cron run
    $batch_size = 5;
    $batch      = array_slice( $queued, 0, $batch_size, true );

    foreach ( $batch as $key => $user ) {
        $response = wp_remote_post( $url, array(
            'method'      => 'POST',
            'body'        => wp_json_encode( $user ),
            'headers'     => array(
                'Content-Type' => 'application/json',
            ),
            'timeout'     => 2,
            'redirection' => 2,
        ) );

        if ( is_wp_error( $response ) ) {
            error_log( 'Webhook error for user ' . ( $user['user_id'] ?? 'unknown' ) . ': ' . $response->get_error_message() );
            continue;
        }

        $status_code = wp_remote_retrieve_response_code( $response );

        // Only remove from queue if Make.com accepted it
        if ( $status_code >= 200 && $status_code < 300 ) {
            unset( $queued[ $key ] );
        } else {
            error_log( 'Webhook failed for user ' . ( $user['user_id'] ?? 'unknown' ) . '. Status code: ' . $status_code );
        }
    }

    // Keep remaining items longer so important data is not lost
    if ( ! empty( $queued ) ) {
        set_transient( 'webhook_user_queue', array_values( $queued ), DAY_IN_SECONDS );
    } else {
        delete_transient( 'webhook_user_queue' );
    }

    delete_transient( 'webhook_batch_running' );
}


/**
 * Update user profile after metrics update
 */
function user_profile_update_send_webhook($user_id) {
    // Just queue webhook instead of sending directly
    queue_webhook_user($user_id);
}
add_action('adapt_profile_metrics_updated', 'user_profile_update_send_webhook', 10, 1);


/**
 * Update user meta after subscription changes
 */
function custom_subscription_update_action($user_id) {
    if (!$user_id || !class_exists('MeprUser')) return;

    $member = new MeprUser($user_id);
    // Skip inactive or exempt memberships
    if (!user_can($user_id, 'mepr-active')
        || $member->is_already_subscribed_to(9811)
        || $member->is_already_subscribed_to(41272)) {
        return;
    }

    // mepr-active is already confirmed true above (otherwise we'd have
    // returned), so reuse $member instead of re-instantiating MeprUser
    // for the same user in the same request.
    $subscriptions = $member->get_active_subscription_titles(", ");
    update_user_meta($user_id, 'mepr_subscriptions', $subscriptions);
    update_user_meta($user_id, 'mepr_active_status', 'active');

    // Clear transient
    delete_transient('user_activity_' . $user_id);

    // Queue webhook for this user
    queue_webhook_user($user_id);

    // Trigger profile updated hook
    do_action('adapt_profile_updated', $user_id, get_userdata($user_id));
}
add_action('mepr-event-updated', 'custom_subscription_update_action', 10, 1);
add_action('mepr-account-subs-updated', 'custom_subscription_update_action', 10, 1);
add_filter('intermediate_image_sizes_advanced', function($sizes) {
    unset($sizes['medium_large']);       // 768x0
    unset($sizes['1536x1536']);          // 1536x1536
    unset($sizes['2048x2048']);          // 2048x2048
    unset($sizes['gallery-landscape']);  // 1280x800
    unset($sizes['gallery-portrait']);   // 800x1280
    return $sizes;
});

add_action('mepr-validate-signup', function($errors) {
    if (isset($_POST['user_email'])) {
        $email = sanitize_email($_POST['user_email']);
        $domain = strtolower(substr(strrchr($email, "@"), 1));

        // Blocked email keywords
        $blocked_keywords = ['gmail', 'hotmail', 'outlook', 'yahoo', 'icloud'];

        foreach ($blocked_keywords as $keyword) {
            if (str_contains($domain, $keyword)) {
                $errors[] = __("Please use your work email.", 'memberpress');
                break;
            }
        }
    }
    return $errors;
});

// Post publish function to add to contributor
function sync_post_to_contributor_resources_single($post_id) {

    // Prevent autosaves & revisions
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    // Allowed contributor post types
    $allowed_post_types = ['partners', 'speaker', 'consultants'];

    // Get contributors from this post
    $contributors = get_field('contributors', $post_id);
    if (!is_array($contributors)) {
        return;
    }

    foreach ($contributors as $row) {

        if (empty($row['contributor_name'])) {
            continue;
        }

        // Post Object → ID
        $contributor = $row['contributor_name'];
        $contributor_id = is_object($contributor) && isset($contributor->ID)
            ? (int) $contributor->ID
            : (int) $contributor;

        if (!$contributor_id) {
            continue;
        }

        // Only allowed contributor types
        if (!in_array(get_post_type($contributor_id), $allowed_post_types, true)) {
            continue;
        }

        // Get contributor resources
        $resources = get_field('resources', $contributor_id);
        if (!is_array($resources)) {
            $resources = [];
        }

        // Check for existing resource
        $exists = false;

        foreach ($resources as $resource_row) {
            if (empty($resource_row['resource'])) {
                continue;
            }

            $resource = $resource_row['resource'];
            $resource_id = is_object($resource) && isset($resource->ID)
                ? (int) $resource->ID
                : (int) $resource;

            if ($resource_id === (int) $post_id) {
                $exists = true;
                break;
            }
        }

        // Add if missing
        if (!$exists) {
            $resources[] = [
                'resource' => $post_id
            ];
            update_field('resources', $resources, $contributor_id);
        }
    }
}


add_action('save_post', 'sync_post_to_contributor_resources', 20, 3);
function sync_post_to_contributor_resources($post_id, $post, $update) {
    sync_post_to_contributor_resources_single($post_id);
}


/**
 * Cached wrapper around attachment_url_to_postid().
 *
 * Core's attachment_url_to_postid() runs a DB query every time it's called
 * (it tries the URL as-is, then strips any -WIDTHxHEIGHT size suffix and
 * tries again) - fine for a single call, but templates that loop over a
 * list of posts and resolve each one's image URL to an attachment ID (e.g.
 * templates/components/_event-card.php) end up running it once per item on
 * every single page load, for a URL->ID mapping that essentially never
 * changes once the image is uploaded. Wrapping it in a transient turns
 * every load after the first into a cache read instead of a query - uses
 * the object cache automatically if one is configured (Redis/Memcached),
 * otherwise falls back to a wp_options row, either way cheaper than
 * re-running the lookup.
 *
 * @param string $url Attachment URL, as stored in an ACF image/URL field.
 * @return int Attachment post ID, or 0 if it couldn't be resolved (matches
 *             attachment_url_to_postid()'s own return value on failure).
 */
function adapt_attachment_url_to_postid( $url ) {
    if ( empty( $url ) ) {
        return 0;
    }
    $cache_key = 'adapt_a2pid_' . md5( $url );
    $cached    = get_transient( $cache_key );
    if ( false !== $cached ) {
        return (int) $cached;
    }
    $attachment_id = attachment_url_to_postid( $url );
    // Cache misses (0) are cached too, at a shorter TTL - otherwise an
    // external/broken image URL would hit the DB on every single load.
    set_transient( $cache_key, $attachment_id, $attachment_id ? WEEK_IN_SECONDS : HOUR_IN_SECONDS );
    return $attachment_id;
}

/**
 * Cache-busting version string for a theme asset.
 *
 * Uses the file's own last-modified time so the query string changes
 * automatically whenever a built asset changes, instead of relying on
 * someone remembering to bump a hardcoded version number on every deploy.
 * Falls back to the theme's declared version if the file can't be found
 * (e.g. running before the first `gulp _build`).
 *
 * @param string $relative_path Path relative to the theme root, e.g. '/assets/css/global.min.css'.
 * @return string
 */
function adapt_asset_version( $relative_path ) {
    $file = get_template_directory() . $relative_path;
    return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
}

/**
 * Enqueue the theme's CSS bundles, one per page template.
 *
 * The compiled stylesheet is split into several bundles instead of one
 * monolithic file, so each page template only downloads the CSS it
 * actually uses:
 *
 * - global.min.css   Vendor libraries + base/forms/sections/print +
 *                     header/footer partials. Loaded on every page.
 * - core.min.css      Default bundle - every templates/**\/*.scss file
 *                     except the ones split out below. Loaded for any
 *                     template not explicitly special-cased.
 * - tpl-agenda.min.css   Exclusive to templates/template-agenda.php -
 *                        verified zero cross-references from any other
 *                        template (see source/scss/main-tpl-agenda.scss).
 * - tpl-events.min.css   Exclusive to templates/template-events.php. Also
 *                        loaded ALONGSIDE core.min.css (not instead of it)
 *                        for templates/template-events-portal.php, which
 *                        shares events.php's wrapper classes but also pulls
 *                        in components/_event-card.php - see
 *                        source/scss/main-tpl-events.scss.
 * - tpl-flexible.min.css Loaded INSTEAD OF core.min.css for
 *                        templates/template-flexible.php - a verified-safe
 *                        subset of core.min.css's source files, not an
 *                        exclusive file (its classes are still shared with
 *                        other templates, which keep loading core.min.css
 *                        unchanged) - see source/scss/main-tpl-flexible.scss.
 * - tpl-home.min.css     Loaded INSTEAD OF core.min.css for
 *                        templates/template-home.php - same
 *                        verified-safe-subset shape as tpl-flexible.min.css,
 *                        see source/scss/main-tpl-home.scss. NOT the real
 *                        homepage despite the name - see below.
 * - tpl-portal-flexible.min.css Loaded INSTEAD OF core.min.css for
 *                        templates/template-portal-flexible.php - this IS
 *                        the real homepage template (confirmed live via
 *                        body class template-portal-flexible on the
 *                        front page for logged-in advantage/professional/
 *                        free-trial members). Same verified-safe-subset
 *                        shape, see source/scss/main-tpl-portal-flexible.scss.
 * - tpl-single-post.min.css Loaded INSTEAD OF core.min.css for
 *                        templates/single-post.php - gated by
 *                        is_singular('post') rather than is_page_template(),
 *                        since single-post.php isn't a selectable Page
 *                        template; it's WordPress's own single-post-type
 *                        template hierarchy result (see index.php's
 *                        is_single() branch). Same verified-safe-subset
 *                        shape, see source/scss/main-tpl-single-post.scss -
 *                        needs more than the usual six pooled files.
 *
 * Extending this list means adding a new main-tpl-*.scss entry point
 * (compiled via source/gulp/tasks/build/styles.js) and a matching branch
 * below - see source/scss/main-core.scss for the verification a new split
 * needs before it's safe to exclude from core.min.css.
 */
function adapt_enqueue_template_styles() {
    $theme_uri = get_template_directory_uri();

    wp_enqueue_style(
        'adapt-global',
        $theme_uri . '/assets/css/global.min.css',
        array(),
        adapt_asset_version( '/assets/css/global.min.css' )
    );

    if ( is_page_template( 'templates/template-agenda.php' ) ) {
        wp_enqueue_style(
            'adapt-tpl-agenda',
            $theme_uri . '/assets/css/tpl-agenda.min.css',
            array( 'adapt-global' ),
            adapt_asset_version( '/assets/css/tpl-agenda.min.css' )
        );
    } elseif ( is_page_template( 'templates/template-events.php' ) ) {
        wp_enqueue_style(
            'adapt-tpl-events',
            $theme_uri . '/assets/css/tpl-events.min.css',
            array( 'adapt-global' ),
            adapt_asset_version( '/assets/css/tpl-events.min.css' )
        );
    } elseif ( is_page_template( 'templates/template-events-portal.php' ) ) {
        wp_enqueue_style(
            'adapt-core',
            $theme_uri . '/assets/css/core.min.css',
            array( 'adapt-global' ),
            adapt_asset_version( '/assets/css/core.min.css' )
        );
        wp_enqueue_style(
            'adapt-tpl-events',
            $theme_uri . '/assets/css/tpl-events.min.css',
            array( 'adapt-core' ),
            adapt_asset_version( '/assets/css/tpl-events.min.css' )
        );
    } elseif ( is_page_template( 'templates/template-flexible.php' ) ) {
        wp_enqueue_style(
            'adapt-tpl-flexible',
            $theme_uri . '/assets/css/tpl-flexible.min.css',
            array( 'adapt-global' ),
            adapt_asset_version( '/assets/css/tpl-flexible.min.css' )
        );
    } elseif ( is_page_template( 'templates/template-home.php' ) ) {
        wp_enqueue_style(
            'adapt-tpl-home',
            $theme_uri . '/assets/css/tpl-home.min.css',
            array( 'adapt-global' ),
            adapt_asset_version( '/assets/css/tpl-home.min.css' )
        );
    } elseif ( is_page_template( 'templates/template-portal-flexible.php' ) ) {
        wp_enqueue_style(
            'adapt-tpl-portal-flexible',
            $theme_uri . '/assets/css/tpl-portal-flexible.min.css',
            array( 'adapt-global' ),
            adapt_asset_version( '/assets/css/tpl-portal-flexible.min.css' )
        );
    } elseif ( is_singular( 'post' ) ) {
        // Not a Page template, so is_page_template() never matches here -
        // templates/single-post.php is picked up by index.php's own
        // is_single() branch (get_template_part('templates/single-' .
        // $post->post_type)), WordPress's normal single-post-type template
        // hierarchy, not the page-template picker. is_singular('post') is
        // the matching conditional: true for any singular view of the
        // built-in 'post' type, same set of pages that route to
        // templates/single-post.php.
        wp_enqueue_style(
            'adapt-tpl-single-post',
            $theme_uri . '/assets/css/tpl-single-post.min.css',
            array( 'adapt-global' ),
            adapt_asset_version( '/assets/css/tpl-single-post.min.css' )
        );
    } else {
        wp_enqueue_style(
            'adapt-core',
            $theme_uri . '/assets/css/core.min.css',
            array( 'adapt-global' ),
            adapt_asset_version( '/assets/css/core.min.css' )
        );
    }

    // Icon font - was a bare <link> in header.php, moved here for the same
    // reason as the bundles above: one place to find/edit all theme CSS.
    wp_enqueue_style(
        'adapt-skelet-icons',
        $theme_uri . '/assets/fonts/skelet-icons-master/style.css',
        array(),
        adapt_asset_version( '/assets/fonts/skelet-icons-master/style.css' )
    );
}
// Priority 1 (not the default 10): this needs to enqueue - and therefore
// print - before other plugins' wp_enqueue_scripts callbacks (MemberPress,
// Wordfence, jQuery UI, etc. all register their own stylesheets at the
// default priority, and plugins load before the theme, so at equal
// priority they'd win the queue and print first). When these bundles were
// raw <link> tags hardcoded early in header.php - before wp_head() ran at
// all - they always won that race unconditionally; this restores the same
// effective ordering now that they're proper enqueues.
add_action( 'wp_enqueue_scripts', 'adapt_enqueue_template_styles', 1 );

// Ajax filtering for various post types
// Enqueue scripts
function my_enqueue_scripts() {
    wp_enqueue_style('theme-styles', get_template_directory_uri(). '/style.css');


    // GSAP & ScrollTrigger
    wp_enqueue_script(
        'gsap-js',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js',
        array(),
        null,
        true
    );
    wp_enqueue_script(
        'scrolltrigger-js',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/ScrollTrigger.min.js',
        array(),
        null,
        true
    );

    // Main JS
    //
    // The version query string used to be hardcoded (?vers=2.0.0, with $ver
    // passed as null below it so WP wouldn't add its own) - meaning every
    // rebuild of this file kept shipping under the exact same URL forever.
    // Found this live: after deploying a real fix to this file, fetch()ing
    // the URL directly returned the new code (cache: 'no-store' bypasses the
    // browser's cache), but the code jQuery had ACTUALLY loaded and bound on
    // a normal page load - checked via $._data(document, 'events').init[0]
    // .handler.toString() - was still the old, pre-fix version. Cache-
    // Control on this file is max-age=31536000 (1 year), so any browser that
    // had ever loaded main.min.js?vers=2.0.0 before kept serving that exact
    // cached copy for a year, never re-checking the server, no matter how
    // many times the underlying file changed - a plausible contributor to
    // more than one "already fixed but still showing broken" report earlier
    // in this project. adapt_asset_version() (already used for every CSS
    // bundle) generates a version string from the file's own mtime, so the
    // URL itself changes on every rebuild instead of relying on someone
    // remembering to bump ?vers= by hand.
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/assets/js/main.min.js',
        array('jquery'),
        adapt_asset_version( '/assets/js/main.min.js' ),
        true
    );

    wp_localize_script('main-js', 'ajaxobject', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('adapt_ajax_nonce'),
    ));

    // HubSpot deferred loader
    wp_add_inline_script(
        'main-js',
        "
        (function(){
            function loadHubSpot(){
                if(window.hsScriptLoaded) return;
                window.hsScriptLoaded = true;
                
                var s = document.createElement('script');
                s.src = '//js.hs-scripts.com/8336221.js';
                s.async = true;
                s.onload = function(){
                    // Suppress exit-triggered events
                    if(window._hsq){
                        _hsq.push(['setIgnoreExit', true]);
                    }
                };
                document.body.appendChild(s);
            }

            // Load HubSpot after user interaction
            ['click','scroll','mousemove','focus','keydown','touchstart'].forEach(function(evt){
                window.addEventListener(evt, loadHubSpot, {once:true});
            });
        })();
        "
    );
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');

/**
 * Render a raw HubSpot form embed field, de-duplicating the shared
 * forms/embed/v2.js script tag across multiple embeds on the same page.
 *
 * Content editors paste the full HubSpot embed snippet - a
 * <script src=".../forms/embed/v2.js"> tag plus an inline
 * hbspt.forms.create({...}) call - into ACF fields (hubspot_embed,
 * hubspot_embed_code, members_only_request_download_form and its
 * _persona/_sector variants) for every CTA form block. Pages that render
 * more than one of these (templates/single-post.php alone has a dozen
 * call sites, on top of the header/mobile-menu CTA modals that are
 * present on every page) end up loading and executing the exact same
 * external script once per instance. Confirmed live on
 * /ai-innovation/market-narratives/workforce-readiness-will-decide-agentic-ai-scale-in-government,
 * where Lighthouse flagged 5 duplicate loads of the same script (~565 KiB
 * combined, each on a 5-minute cache lifetime we don't control since it's
 * hosted on HubSpot's CDN).
 *
 * This keeps the very first embed's script tag exactly as authored (so
 * load/execution order is unchanged for the first form on the page - by
 * the time any later hbspt.forms.create() call runs, window.hbspt is
 * already defined from that first load) and strips the redundant
 * <script src> tag from every subsequent embed, since hbspt is a global
 * and later forms.create() calls work fine once it exists.
 *
 * @param string|false $raw_html Raw field value from get_field()/get_sub_field().
 * @return string
 */
function adapt_render_hubspot_embed( $raw_html ) {
    static $script_seen = false;

    if ( empty( $raw_html ) || ! is_string( $raw_html ) ) {
        return '';
    }

    if ( ! $script_seen ) {
        // Only mark as seen if this instance actually contains the embed
        // script - an ACF field with just an hbspt.forms.create() call
        // (no script tag) shouldn't count as having loaded it.
        if ( preg_match( '#<script\b[^>]*\bsrc=["\'](?:https?:)?//(?:js|js-ap1)\.hsforms\.net/forms/embed/v2\.js["\']#i', $raw_html ) ) {
            $script_seen = true;
        }
        return $raw_html;
    }

    return preg_replace(
        '#<script\b[^>]*\bsrc=["\'](?:https?:)?//(?:js|js-ap1)\.hsforms\.net/forms/embed/v2\.js["\'][^>]*>\s*</script>#i',
        '',
        $raw_html
    );
}

/**
 * Build the wp_get_attachment_image attr array for a preview-main-slider
 * hero image, applying fetchpriority=high to only the very first slide
 * rendered on the page.
 *
 * _slide-preview.php has several mutually-exclusive branches (member
 * type, persona/sector, locked/blurred previews) that each loop over a
 * slider_images or blurred_images repeater and echo a main-slide image.
 * Only one branch ever executes per request, but several of those loops
 * render more than one slide, and previously every slide in the loop was
 * marked fetchpriority=high - diluting the priority hint across multiple
 * large images instead of flagging just the actual LCP candidate (the
 * first, visibly active slide), which hurt real-world LCP timing once
 * this shipped to production.
 *
 * @param string $alt Image alt text.
 * @return array
 */
function adapt_main_slide_image_attrs( $alt ) {
    static $first_slide_rendered = false;

    $attr = array(
        'alt'   => $alt,
        'sizes' => '(max-width: 767px) 100vw, 812px',
    );

    if ( ! $first_slide_rendered ) {
        $attr['fetchpriority'] = 'high';
        $first_slide_rendered  = true;
    }

    return $attr;
}

/**
 * True the first time it's called in a request, false every time after.
 *
 * Several homepage components (_article-card.php, _highlights-featured-
 * block.php, _resources-featured-block.php, the events-portal card grid)
 * each independently mark their own first image fetchpriority=high as
 * the page's LCP candidate. That works fine when a component only
 * appears once, but _highlights-featured-block.php and
 * _resources-featured-block.php are both ACF flexible-content modules
 * that get included more than once on the same page (see
 * template-highlights.php and template-portal-flexible.php, each of
 * which calls get_template_part() on the same block twice) - each
 * inclusion reset its own local "have I used the priority hint yet"
 * flag, so the page ended up with two or more images marked
 * fetchpriority=high instead of one, diluting the hint. Use this shared,
 * request-scoped flag instead so only the single image that actually
 * renders first on the page gets it, no matter which component or how
 * many times it's included.
 *
 * @return bool
 */
function adapt_is_first_hero_image() {
    static $used = false;

    if ( $used ) {
        return false;
    }

    $used = true;
    return true;
}

/**
 * Load a small set of known interaction-only plugin stylesheets
 * non-render-blocking, using the standard media="print" + onload swap
 * trick (falls back to a <noscript> stylesheet for no-JS visitors).
 *
 * Lighthouse flagged ~170ms of render-blocking CSS on
 * /ai-innovation/market-narratives/workforce-readiness-will-decide-agentic-ai-scale-in-government,
 * most of it MemberPress's own bundled stylesheets (account/login/theme
 * UI, jQuery UI, signup, plans). Those are tied to real membership/
 * locked-content functionality this site relies on (this exact page has
 * hidden request-download popups gated behind login), so they're left
 * alone rather than guessed at - dequeuing the wrong one on a
 * membership-gated site is a real regression risk for a ~170ms gain.
 *
 * These handles are safe to defer regardless of whether they're used on
 * a given page, because each only affects UI that appears after a user
 * interaction, or a widget that isn't part of the page's initial paint
 * at all - nothing that could possibly be needed before the deferred
 * stylesheet has already loaded:
 * - jquery-magnific-popup-css: the popup/lightbox library behind this
 *   site's modals (the request-download forms, image popups, etc.)
 * - jquery-ui-timepicker-addon-css: a jQuery UI datetime picker widget,
 *   not used on a plain article page at all
 * - wp-pagenavi-css: pagination widget styling, irrelevant until a user
 *   scrolls to a list's page-number links
 * - tablepress-default-css: TablePress plugin styling, only relevant on
 *   the specific posts that actually embed a table
 * - wordfenceAJAXcss-css: styling for Wordfence's AJAX-loaded admin
 *   notice box, not part of any visitor-facing page content
 *
 * Homepage Lighthouse audit (2026-08-20) flagged 430ms of render-
 * blocking requests; these three were confirmed present via the live
 * homepage's <link> handles before being added here.
 */
function adapt_defer_noncritical_styles( $html, $handle ) {
    // WordPress's style_loader_tag filter passes the bare registered
    // handle (e.g. "wordfenceAJAXcss"), not the "{$handle}-css" id
    // WordPress prints in the <link id="..."> HTML attribute - these
    // must NOT include the -css suffix or in_array() below never matches
    // and this filter silently no-ops for every handle, which is exactly
    // what was happening in production (confirmed via a cache-bypassed
    // live fetch: none of the 5 handles were getting the onload swap).
    $defer_handles = array(
        'jquery-magnific-popup',
        'jquery-ui-timepicker-addon',
        'wp-pagenavi',
        'tablepress-default',
        'wordfenceAJAXcss',
    );

    if ( ! in_array( $handle, $defer_handles, true ) ) {
        return $html;
    }

    if ( str_contains( $html, 'onload=' ) ) {
        return $html;
    }

    $noscript = '<noscript>' . preg_replace( '/\s+media=["\']print["\']/', '', $html ) . '</noscript>';

    $html = preg_replace( '/media=["\']all["\']/', '', $html );
    $html = str_replace(
        "rel='stylesheet'",
        "rel='stylesheet' media='print' onload=\"this.media='all'\"",
        $html
    );
    $html = str_replace(
        'rel="stylesheet"',
        'rel="stylesheet" media="print" onload="this.media=\'all\'"',
        $html
    );

    return $html . $noscript;
}
add_filter( 'style_loader_tag', 'adapt_defer_noncritical_styles', 10, 2 );

// AJAX: Load Partners
add_action('wp_ajax_load_partners', 'ajax_load_partners');
add_action('wp_ajax_nopriv_load_partners', 'ajax_load_partners');

function ajax_load_partners() {
    check_ajax_referer( 'adapt_ajax_nonce', 'nonce' );
    // Get AJAX POST data
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $partner_type_id = isset($_POST['partner_type_id']) ? intval($_POST['partner_type_id']) : 0;
    $expertise = sanitize_text_field($_POST['expertise'] ?? '');
    $industry  = sanitize_text_field($_POST['industry'] ?? '');
    $search    = sanitize_text_field($_POST['search'] ?? ''); // NEW: search term

    if (!$partner_type_id) {
        wp_send_json_error(['message' => 'Partner type not set']);
    }

    // Build tax_query
    $tax_query = [
        [
            'taxonomy' => 'partner-type',
            'field'    => 'term_id',
            'terms'    => $partner_type_id,
        ]
    ];

    if ($expertise !== '') {
        $tax_query[] = [
            'taxonomy' => 'capabilities',
            'field'    => 'slug',
            'terms'    => $expertise,
        ];
    }

    if ($industry !== '') {
        $tax_query[] = [
            'taxonomy' => 'industries',
            'field'    => 'slug',
            'terms'    => $industry,
        ];
    }

    // Build WP_Query args
    $args = [
        'post_type'      => 'partners',
        'posts_per_page' => 12,
        'paged'          => $page,
        'post_status'    => 'publish',
        'tax_query'      => $tax_query,
        'orderby' => [
            'menu_order' => 'DESC',
            'ID'         => 'DESC', // tiebreaker for stability
        ],

        'suppress_filters' => false,
    ];

    if (!empty($search)) {
        $args['s'] = $search;
    }

    $query = new WP_Query($args);

    // Capture the output of partner cards
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            include locate_template('/templates/partners-components/_partner-card.php');
        }
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success([
        'html'      => $html,
        'max_pages' => $query->max_num_pages,
    ]);
}

// Automatically set is_featured meta based on featured-post taxonomy
add_action('save_post', function($post_id) {

    // Don't run on revisions or autosaves
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;

    // Get the slugs of featured-post terms
    $terms = wp_get_post_terms($post_id, 'featured-post', ['fields' => 'slugs']);

     update_post_meta(
        $post_id,
        'is_featured',
        in_array('featured', $terms, true) ? 1 : 0
    );

    update_post_meta(
        $post_id,
        'is_research_type_order',
        in_array('research-type-order', $terms, true) ? 1 : 0
    );

});

function get_membership_type_for_user($user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return '';

    $member = class_exists('MeprUser') ? new MeprUser($user_id) : null;

    // get active subscription IDs
    $active_subscriptions = $member?->active_product_subscriptions('ids') ?? [];

    // get all membership IDs from ACF
    $get_membership_ids = function($field) {
        if (!have_rows($field, 'options')) return [];
        $ids = [];
        while (have_rows($field, 'options')) {
            the_row();
            $ids[] = get_sub_field('membership_id');
        }
        return $ids;
    };

    $adv_ids  = $get_membership_ids('advantage_memberships');
    $it_ids   = $get_membership_ids('it_pro_memberships');
    $free_ids = $get_membership_ids('free_trial_memberships');
    $kyc_ids  = $get_membership_ids('kyc_memberships');

    return match (true) {
        (bool) array_intersect($adv_ids, $active_subscriptions)  => 'advantage',
        (bool) array_intersect($it_ids, $active_subscriptions)   => 'it-pro',
        (bool) array_intersect($free_ids, $active_subscriptions) => 'free-trial',
        (bool) array_intersect($kyc_ids, $active_subscriptions)  => 'kyc',
        default => 'default',
    };
}

function get_allowed_subscriptions_for_user($membershipType = null) {
    if (!$membershipType) $membershipType = get_membership_type_for_user();

    $advantage = get_field('advantage_subscriptions', 'options') ?: [];
    $it_pro    = get_field('it_pro_subscriptions', 'options') ?: [];

    if ($membershipType === 'advantage') return $advantage;
    if ($membershipType === 'it-pro') return $it_pro;

    return []; // fallback → no access
}

function get_visible_terms_cache_version() {
    $version = get_option('visible_terms_cache_version');
    if (!$version) {
        $version = time();
        update_option('visible_terms_cache_version', $version);
    }
    return $version;
}

// $all_posts = get_posts([
//     'post_type'      => 'post',
//     'posts_per_page' => -1,
//     'fields'         => 'ids',
// ]);

// foreach ($all_posts as $id) {
//     $value = get_post_meta($id, 'is_research_type_order', true);
//     if ($value === '' || $value === false) { // missing or empty
//         update_post_meta($id, 'is_research_type_order', 0);
//     }
// }

// -------------------------
// AJAX function
// -------------------------
function ajax_load_filtered_posts() {
    check_ajax_referer( 'adapt_ajax_nonce', 'nonce' );
    // -------------------------
    // Detect membership
    // -------------------------
    $membershipType = get_membership_type_for_user();
    $allowed_subscriptions = get_allowed_subscriptions_for_user($membershipType);
    $current_user_id = get_current_user_id();

    $member = class_exists('MeprUser') ? new MeprUser($current_user_id) : null;
    $active_subscriptions = $member?->active_product_subscriptions('ids') ?? [];
    // -------------------------
    // Pagination + basic data
    // -------------------------
    $page      = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
    $post_type = sanitize_text_field($_POST['post_type'] ?? 'post');
    $search    = sanitize_text_field($_POST['search'] ?? '');
    $sort      = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'featured';

    // -------------------------
    // Optional filters
    // -------------------------
    $topic           = (array) ($_POST['topic'] ?? []);
    $type            = (array) ($_POST['type'] ?? []);
    $persona         = (array) ($_POST['persona'] ?? []);
    $sector          = (array) ($_POST['sector'] ?? []);
    $trending_themes = (array) ($_POST['trending_themes'] ?? []);
    $event           = (array) ($_POST['event'] ?? []);
    $date            = (array) ($_POST['date'] ?? []);
    $research_type_order = null;
    if (isset($_POST['research_type_order'])) {
        // Treat '1' as true, everything else as false
        $research_type_order = $_POST['research_type_order'] == '1';
    }

    // Allowed lists from JS (for normalizing empty filters)
    $allowed_lists = [
        'topic'           => $_POST['allowed_topic'] ?? [],
        'filter-types'    => $_POST['allowed_type'] ?? [],
        'persona-mapping' => $_POST['allowed_persona'] ?? [],
        'sector-analysis' => $_POST['allowed_sector'] ?? [],
        'trending-themes' => $_POST['allowed_trending'] ?? [],
        'insights-event'  => $_POST['allowed_event'] ?? [],
    ];

    // -------------------------
    // Helper: flatten & sanitize
    // -------------------------
    $flatten_and_sanitize = function ($values) {
        $flat = [];
        foreach ((array) $values as $v) {
            if (is_array($v)) {
                $flat = array_merge($flat, $v);
            } else {
                $flat[] = $v;
            }
        }
        return array_map('sanitize_text_field', $flat);
    };

    foreach (['topic','type','persona','sector','trending_themes','event','date'] as $key) {
        $$key = $flatten_and_sanitize($$key);
    }
    $raw_topic = $topic;
    $has_event_filter = !empty($event);
    // -------------------------
    // Normalize filters with allowed lists
    // -------------------------
    $normalize_filter = function ($values, $allowed) use ($flatten_and_sanitize) {
        $allowed = $flatten_and_sanitize($allowed);
        return empty($values) ? $allowed : $flatten_and_sanitize($values);
    };

    $topic           = $normalize_filter($topic, $allowed_lists['topic']);
    $type            = $normalize_filter($type, $allowed_lists['filter-types']);
    $persona         = $normalize_filter($persona, $allowed_lists['persona-mapping']);
    $sector          = $normalize_filter($sector, $allowed_lists['sector-analysis']);
    $trending_themes = $normalize_filter($trending_themes, $allowed_lists['trending-themes']);
    $event           = $normalize_filter($event, $allowed_lists['insights-event']);

    // -------------------------
    // Normalize date filter
    // -------------------------
    $date_query = [];
    if (isset($_POST['date']) && !empty($_POST['date'])) {
        $date = array_map('sanitize_text_field', (array) $_POST['date']);
        foreach ($date as $d) {
            if (preg_match('/^(\d{4})-(\d{2})$/', $d, $m)) {
                $date_query[] = ['year' => (int)$m[1], 'month' => (int)$m[2]];
            }
        }
    }

    // -------------------------
    // Preserve first topic for card
    // -------------------------
    $has_topic_filter = !empty($_POST['topic']) && count($_POST['topic']) > 0;
    $filtered_topic = $has_topic_filter ? $_POST['topic'][0] : null;

    // -------------------------
    // Base WP_Query args
    // -------------------------
    $args = [
        'post_type'        => $post_type,
        'posts_per_page'   => 12,
        'paged'            => $page,
        'post_status'      => 'publish',
        'suppress_filters' => false,
    ];

    if (!empty($search)) $args['s'] = $search;



    if (!empty($_POST['research_type_order']) && intval($_POST['research_type_order']) === 1) {
        $args['meta_query'] = [
            'research_type_order_clause' => [
                'key'     => 'is_research_type_order',
                'type' => 'EXISTS',
            ],
        ];
        $args['orderby'] = [
            'research_type_order_clause' => 'DESC', 
            'date'                       => 'DESC',
        ];
    } else {
        // Sorting
        if ($sort === 'featured') {
            $args['meta_query'] = [
                'featured_clause' => [
                    'key'     => 'is_featured',
                    'compare' => 'EXISTS',
                ],
            ];
            $args['orderby'] = [
                'featured_clause' => 'DESC',
                'date'            => 'DESC',
            ];
        } else {
            $args['orderby'] = ['date' => 'DESC'];
        }
    }

    // -------------------------
    // Tax query
    // -------------------------
    $tax_query = [];
    $taxonomies = [
        'topic'           => $topic,
        'filter-types'    => $type,
        'trending-themes' => $trending_themes,
        'persona-mapping' => $persona,
        'sector-analysis' => $sector,
        'insights-event'  => $event,
    ];

    foreach ($taxonomies as $taxonomy => $terms) {
        if (!empty($terms)) {
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $terms,
                'operator' => 'IN',
            ];
        }
    }

    // Subscription filter
    if (!empty($allowed_subscriptions)) {
        $tax_query[] = [
            'taxonomy' => 'subscription',
            'field'    => 'term_id',
            'terms'    => $allowed_subscriptions,
            'operator' => 'IN',
        ];
    }

    if (!empty($tax_query)) $args['tax_query'] = array_merge(['relation'=>'AND'], $tax_query);
    if (!empty($date_query)) $args['date_query'] = array_merge(['relation'=>'OR'], $date_query);

    // -------------------------
    // Main query
    // -------------------------
    if (!empty($search)) {
        // Temporarily bypass Relevanssi
        $query = new WP_Query($args);
    } else {
        $query = new WP_Query($args);
    }
    // -------------------------
    // Visible terms caching
    // -------------------------
    $cache_version = get_visible_terms_cache_version();
    $cache_key = 'visible_terms_' . $cache_version . '_' . md5(serialize([
        'args' => $args,
        'membership' => $membershipType,
    ]));

    $visible_terms = get_transient($cache_key);
    if (is_admin()) {
        $visible_terms = false;
    }

    if (false === $visible_terms) {
        $visible_terms = [];
        foreach ($taxonomies as $taxonomy => $_) $visible_terms[$taxonomy] = [];
        $visible_terms['date'] = [];

        // Lightweight ALL posts query (IDs only)
        $all_posts_args = $args;
        unset($all_posts_args['paged']);
        $all_posts_args['fields'] = 'ids';
        $all_posts_args['posts_per_page'] = 1000;
        $all_posts_args['no_found_rows'] = true;
        $all_posts_args['update_post_meta_cache'] = false;
        $all_posts_args['update_post_term_cache'] = false;

        $all_ids_query = new WP_Query($all_posts_args);
        $post_ids = $all_ids_query->posts;

        if (!empty($post_ids)) {
            // Taxonomy terms
            foreach (array_keys($taxonomies) as $taxonomy) {
                $terms = get_terms([
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => true,
                    'object_ids' => $post_ids,
                    'fields'     => 'slugs',
                ]);
                if (!is_wp_error($terms)) $visible_terms[$taxonomy] = $terms;
            }

            // Date terms
            global $wpdb;
            $dates = $wpdb->get_results("
                SELECT DISTINCT YEAR(post_date) as y, MONTH(post_date) as m
                FROM {$wpdb->posts}
                WHERE ID IN (" . implode(',', array_map('intval', $post_ids)) . ")
                ORDER BY y DESC, m DESC
            ");
            foreach ($dates as $d) {
                $visible_terms['date'][] = sprintf('%04d-%02d', $d->y, $d->m);
            }
        }

        set_transient($cache_key, $visible_terms, HOUR_IN_SECONDS);
    }

    wp_reset_postdata();

    // -------------------------
    // Render posts
    // -------------------------
    $card_filtered_topic = $filtered_topic;
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $eventtype = $has_event_filter ? 'yes' : 'no';
            $filtered_topic = $card_filtered_topic;
            include locate_template('/templates/components/_article-card.php');
        }
    } else {
        ?>
        <div class="no-results">
            <h3 class="headerXSmall text-bold">No results found</h3>
            <p>Try adjusting your filters or search.</p>
        </div>
        <?php
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    // -------------------------
    // Return JSON
    // -------------------------
    wp_send_json_success([
        'html'          => $html,
        'max_pages'     => $query->max_num_pages,
        'visible_terms' => $visible_terms,
        'debug' => [
            'user_id' => $current_user_id,
            'membershipType' => $membershipType,
            'active_subscriptions' => $active_subscriptions,
            'allowed_subscriptions' => $allowed_subscriptions,
            'tax_query' => $tax_query,
            'search' => $search,
        ],
    ]);
}

add_action('wp_ajax_load_filtered_posts', 'ajax_load_filtered_posts');
add_action('wp_ajax_nopriv_load_filtered_posts', 'ajax_load_filtered_posts');

// Persona and Sector Featured

function ajax_load_featured_post() {
    check_ajax_referer( 'adapt_ajax_nonce', 'nonce' );

    $type      = sanitize_text_field($_POST['type'] ?? '');
    $term_slug = sanitize_text_field($_POST['term_slug'] ?? '');

    if (!$type || !$term_slug) {
        wp_send_json_success(['has_post' => false, 'html' => '']);
    }

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'tax_query'      => ['relation' => 'AND'],
    ];

    if ($type === 'persona') {
        $args['tax_query'][] = [
            'taxonomy' => 'filter-types',
            'field'    => 'slug',
            'terms'    => 'cxo-buyer-persona-profiles',
        ];
        $args['tax_query'][] = [
            'taxonomy' => 'persona-mapping',
            'field'    => 'slug',
            'terms'    => $term_slug,
        ];
    }

    if ($type === 'sector') {
        $args['tax_query'][] = [
            'taxonomy' => 'filter-types',
            'field'    => 'slug',
            'terms'    => 'sector-outlooks',
        ];
        $args['tax_query'][] = [
            'taxonomy' => 'sector-analysis',
            'field'    => 'slug',
            'terms'    => $term_slug,
        ];
        // Optional: subscription restriction
        $args['tax_query'][] = [
            'taxonomy' => 'subscription',
            'field'    => 'slug',
            'terms'    => 'advantage',
        ];
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            if ($type === 'persona') {
                include locate_template('/templates/components/_featured-article-card.php');
            } else {
                include locate_template('/templates/components/_featured-article-card-sector.php');
            }
        }
        $html = ob_get_clean();
        wp_send_json_success(['has_post' => true, 'html' => $html]);
    } else {
        wp_send_json_success(['has_post' => false, 'html' => '']);
    }

    wp_reset_postdata();
}

add_action('wp_ajax_load_featured_post', 'ajax_load_featured_post');
add_action('wp_ajax_nopriv_load_featured_post', 'ajax_load_featured_post');


add_action('wp_ajax_load_favourite_posts', 'ajax_load_favourite_posts');
add_action('wp_ajax_nopriv_load_favourite_posts', 'ajax_load_favourite_posts');

function ajax_load_favourite_posts() {
    check_ajax_referer( 'adapt_ajax_nonce', 'nonce' );

    $favorites = get_user_favorites();

    // CRITICAL SAFETY CHECK
    if (empty($favorites)) {
        wp_send_json_success([
            'html' => '',
            'max_pages' => 0
        ]);
    }

    $page   = intval($_POST['page'] ?? 1);
    $search = sanitize_text_field($_POST['search'] ?? '');
    $topic  = sanitize_text_field($_POST['topic'] ?? '');
    $type   = sanitize_text_field($_POST['type'] ?? '');
    $args = [
        'post_type'      => 'post',
        'post__in'       => $favorites,
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'paged'          => $page,
    ];

    if ($search) {
        $args['s'] = $search;
    }

    $tax_query = [];

    if ($topic) {
        $tax_query[] = [
            'taxonomy' => 'topic',
            'field'    => 'slug',
            'terms'    => $topic,
        ];
    }

    if ($type) {
        $tax_query[] = [
            'taxonomy' => 'filter-types',
            'field'    => 'slug',
            'terms'    => $type,
        ];
    }

    if ($tax_query) {
        $args['tax_query'] = array_merge(['relation' => 'AND'], $tax_query);
    }

    $query = new WP_Query($args);

    ob_start();

    while ($query->have_posts()) {
        $query->the_post();
        set_query_var('is_favourites', true);
        include locate_template('/templates/components/_article-card.php');
    }

    wp_reset_postdata();

    wp_send_json_success([
        'html'      => ob_get_clean(),
        'max_pages' => $query->max_num_pages,
    ]);
}

add_action('wp_ajax_load_more_resources', 'load_more_resources');
add_action('wp_ajax_nopriv_load_more_resources', 'load_more_resources');

function load_more_resources() {
    check_ajax_referer( 'adapt_ajax_nonce', 'nonce' );

    global $post; // ← THIS is what you're missing

    $page     = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 6;
    $parent_post_id  = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!$parent_post_id) {
        wp_die();
    }

    $resources_array = [];

    if ( have_rows('resources', $parent_post_id) ) :
        while ( have_rows('resources', $parent_post_id) ) : the_row();
            $resource_post_id = get_sub_field('resource');
            if ( $resource_post_id ) {
                $featured = get_field('featured', $resource_post_id);
                $resources_array[] = [
                    'id'       => $resource_post_id,
                    'featured' => $featured ? 1 : 0,
                ];
            }
        endwhile;
    endif;

    usort($resources_array, function($a, $b){
        return $b['featured'] - $a['featured'];
    });

    $offset = $per_page * ($page - 1);
    $resources_page = array_slice($resources_array, $offset, $per_page);

    if (!empty($resources_page)) {

        foreach ($resources_page as $resource) {

            $post = get_post($resource['id']); // overwrite global $post
            setup_postdata($post);

            $post_slug = get_post_field('post_name', $post->ID);
            $extra_classes = 'one-half';

            include locate_template('/templates/components/_article-card.php');
        }

        wp_reset_postdata();
    }

    wp_die();
}


// Sync "featured" taxonomy → is_featured meta (for sorting)
add_action('set_object_terms', function($post_id, $terms, $tt_ids, $taxonomy) {

    if ($taxonomy !== 'featured-post') {
        return;
    }

    $term_slugs = [];

    foreach ($terms as $term) {
        if (is_string($term)) {
            $term_slugs[] = $term;
        } else {
            $t = get_term($term, $taxonomy);
            if ($t && !is_wp_error($t)) {
                $term_slugs[] = $t->slug;
            }
        }
    }

    update_post_meta(
        $post_id,
        'is_featured',
        in_array('featured', $term_slugs, true) ? 1 : 0
    );

    update_post_meta(
        $post_id,
        'is_research_type_order',
        in_array('research-type-order', $term_slugs, true) ? 1 : 0
    );

}, 10, 4);

add_action('wp_ajax_load_past_sessions_unique', 'load_past_sessions_unique');
add_action('wp_ajax_nopriv_load_past_sessions_unique', 'load_past_sessions_unique');

function load_past_sessions_unique() {
    check_ajax_referer( 'adapt_ajax_nonce', 'nonce' );
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $posts_per_page = isset($_POST['perpage']) ? intval($_POST['perpage']) : 18;
    $soft_limit = $posts_per_page * 5; // fetch extra to guarantee 18 visible posts
    $today = date('Ymd');

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $soft_limit,
        'offset'         => $offset,
        'meta_key'       => 'replay_event_date',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'post_status'      => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'filter-types',
                'field'    => 'slug',
                'terms'    => 'analyst-market-briefings',
            ),
        ),
        'meta_query' => array(
            array(
                'key'     => 'replay_event_date',
                'compare' => '<=',
                'value'   => $today,
            ),
        ),
    );

    $query = new WP_Query($args);
    $shown = 0;
    $html = '';

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            $can_access = true;
            if (function_exists('mepr_is_content_protected') && mepr_is_content_protected(get_the_ID())) {
                $can_access = mepr_current_user_can_access_post(get_the_ID());
            }

            if ($can_access) :
                $shown++;
                $extra_classes = 'one-third';
                $eventtype = 'yes';
                ob_start();
                include locate_template('/templates/components/_article-card.php');
                $html .= ob_get_clean();
            endif;

            if ($shown >= $posts_per_page) break;

        endwhile;
    endif;

    wp_reset_postdata();

    echo $html;
    wp_die();
}

/**
 * Invalidate visible terms cache when posts or terms change
 */
function invalidate_visible_terms_cache() {
    global $wpdb;

    // Delete visible_terms_* transients
    $pattern = '_transient_visible_terms_%';
    $transients = $wpdb->get_col(
        "SELECT option_name FROM {$wpdb->options}
         WHERE option_name LIKE '{$pattern}'"
    );

    if ($transients) {
        foreach ($transients as $transient) {
            $key = str_replace('_transient_', '', $transient);
            delete_transient($key);
        }
    }
}

// Clear when posts are saved
add_action('save_post', 'invalidate_visible_terms_cache');

// Clear when terms are edited/created/deleted
add_action('created_term', 'invalidate_visible_terms_cache');
add_action('edited_term', 'invalidate_visible_terms_cache');
add_action('delete_term', 'invalidate_visible_terms_cache');
add_action('mepr-event-transaction-completed', function() {
    delete_transient('membership_ids');
});


add_filter( 'imagify_auto_optimize_attachment', function( $optimize, $attachment_id, $metadata ) {
    if ( ! $optimize ) {
        return false;
    }

    $mime_type = get_post_mime_type( $attachment_id );

    $blocked_mime_types = array(
        'application/pdf',
        'application/vnd.ms-powerpoint', // .ppt
        'application/vnd.openxmlformats-officedocument.presentationml.presentation', // .pptx
    );

    return ! in_array( $mime_type, $blocked_mime_types, true );
}, 10, 3 );





/**
 * Exclude specific JS files from WP Rocket Delay JS only on the homepage.
 */
add_filter( 'rocket_delay_js_exclusions', function( $exclusions ) {

    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? strtok( $_SERVER['REQUEST_URI'], '?' ) : '';

    // Homepage only.
    if ( $request_uri === '/' ) {
        $exclusions[] = 'jquery.min.js';
        $exclusions[] = 'gsap.min.js';
        $exclusions[] = 'mediaelement-and-player.min.js';
        $exclusions[] = '/themes/adapt/assets/js/main.min.js';
    }

    return $exclusions;
} );


add_action( 'after_setup_theme', function() {
    add_image_size( 'article-card', 360, 200, true );
    // Featured/hero article slot (span.video-container) - live Lighthouse audit
    // showed these rendered at ~529x299, but the templates were requesting
    // 'full' size, downloading originals up to 2560px wide for a ~530px slot.
    add_image_size( 'article-hero', 530, 300, true );
} );




// ─────────────────────────────────────────────────────────────────────────────
// adapt_render_filter_posts()
//
// Renders the initial posts grid
// Reads filters from $_GET, detects membership (cached), builds WP_Query,
// and includes the article-card component for each result.
//
// Usage in template: adapt_render_filter_posts();
// ─────────────────────────────────────────────────────────────────────────────
function adapt_render_filter_posts() {
 
    // User 584 is an administrator but should still see posts filtered by
    // their own subscription, not bypass gating like other admins.
    $is_admin = current_user_can('manage_options') && get_current_user_id() != 584;

    // -------------------------
    // Membership detection
    // Cached per user for 5 min — avoids repeated MeprUser DB calls.
    // -------------------------
    $current_user_id = get_current_user_id();
    $mem_cache_key   = 'adapt_render_mem_' . $current_user_id;
    $mem_cache       = get_transient($mem_cache_key);
 
    if ($mem_cache !== false) {
        $membershipType        = $mem_cache['membershipType'];
        $allowed_subscriptions = $mem_cache['allowed_subscriptions'];
        $active_subscriptions  = $mem_cache['active_subscriptions'];
    } else {
        $membershipType        = get_membership_type_for_user();
        $allowed_subscriptions = get_allowed_subscriptions_for_user($membershipType);
        $member                = class_exists('MeprUser') ? new MeprUser($current_user_id) : null;
        $active_subscriptions  = $member?->active_product_subscriptions('ids') ?? [];
 
        set_transient($mem_cache_key, [
            'membershipType'        => $membershipType,
            'allowed_subscriptions' => $allowed_subscriptions,
            'active_subscriptions'  => $active_subscriptions,
        ], 5 * MINUTE_IN_SECONDS);
    }
 
    // -------------------------
    // Membership allowed IDs + allowed type slugs (server-side, same logic as adapt_render_filter_dropdowns)
    // -------------------------
    $q          = get_queried_object();
    $q_slug     = $q->slug ?? '';
    $q_taxonomy = $q->taxonomy ?? '';
 
    $acf_cache_key          = 'filter_types_allowed_ids_' . md5($membershipType);
    $membership_allowed_ids = $is_admin ? [] : get_transient($acf_cache_key);
 
    if (!$is_admin && $membership_allowed_ids === false) {
        $it_pro_types_ids    = get_field('it_pro_types',    'options') ?: [];
        $advantage_types_ids = get_field('advantage_types', 'options') ?: [];
        $membership_allowed_ids = match ($membershipType) {
            'it-pro'    => $it_pro_types_ids,
            'advantage' => $advantage_types_ids,
            default     => [],
        };
        set_transient($acf_cache_key, $membership_allowed_ids, HOUR_IN_SECONDS);
    }
 
    // Apply page_allowed_ids intersection (same as template + dropdown function)
    $page_allowed_ids    = [];
    $grouped_types_terms = get_field('grouped_types', $q);
    if (is_array($grouped_types_terms)) {
        $page_allowed_ids = array_map(function ($term) {
            return is_object($term) && isset($term->term_id) ? (int) $term->term_id : (int) $term;
        }, $grouped_types_terms);
    }
    if (!empty($q) && isset($q->term_id)) {
        $page_allowed_ids[] = (int) $q->term_id;
    }
    $page_allowed_ids = array_unique($page_allowed_ids);
 
    if (!$is_admin) {
        if (!empty($membership_allowed_ids) && !empty($page_allowed_ids)) {
            $membership_allowed_ids = array_values(array_intersect($membership_allowed_ids, $page_allowed_ids));
        } elseif (!empty($page_allowed_ids)) {
            $membership_allowed_ids = $page_allowed_ids;
        }
    }
 
    // -------------------------
    // Load all taxonomy terms (from warm transient when available)
    // Used to validate page-slug fallbacks AND build allowed_type_slugs
    // -------------------------
    $terms_cache_key = 'filter_types_terms_' . md5(serialize($membership_allowed_ids));
    $cached_terms    = get_transient($terms_cache_key);
 
    if ($cached_terms !== false) {
        $type_terms     = $cached_terms['types']    ?? [];
        $topic_terms    = $cached_terms['topic']    ?? [];
        $trending_terms = $cached_terms['trending'] ?? [];
        $persona_terms  = $cached_terms['persona']  ?? [];
        $sector_terms   = $cached_terms['sector']   ?? [];
    } else {
        $type_terms     = get_terms(['taxonomy' => 'filter-types',    'hide_empty' => true, 'parent' => 0]);
        $topic_terms    = get_terms(['taxonomy' => 'topic',           'hide_empty' => true, 'parent' => 0]);
        $trending_terms = get_terms(['taxonomy' => 'trending-themes', 'hide_empty' => true, 'parent' => 0]);
        $persona_terms  = get_terms(['taxonomy' => 'persona-mapping', 'hide_empty' => true, 'parent' => 0]);
        $sector_terms   = get_terms(['taxonomy' => 'sector-analysis', 'hide_empty' => true, 'parent' => 0]);
    }
 
    // Safe slug extractor
    $term_slugs = fn($terms) => (is_array($terms) && !is_wp_error($terms))
        ? array_column($terms, 'slug') : [];
 
    // filter-types: further constrained by membership (skipped for admins)
    $allowed_type_slugs = [];
    if (!$is_admin && !empty($membership_allowed_ids) && is_array($type_terms) && !is_wp_error($type_terms)) {
        $allowed_type_slugs = array_column(
            array_filter($type_terms, fn($t) => in_array($t->term_id, $membership_allowed_ids, true)),
            'slug'
        );
    }
 
    // Valid slugs per taxonomy — used to validate whether the page slug qualifies as a filter
    $valid_topic_slugs    = $term_slugs($topic_terms);
    $valid_trending_slugs = $term_slugs($trending_terms);
    $valid_persona_slugs  = $term_slugs($persona_terms);
    $valid_sector_slugs   = $term_slugs($sector_terms);
 
    // Helper: return [$q_slug] only when the current page's taxonomy matches
    // AND the slug is present in the available filter terms for that taxonomy.
    $page_slug_filter = function ($taxonomy, $valid_slugs) use ($q_taxonomy, $q_slug) {
        if ($q_taxonomy !== $taxonomy) return [];
        // If valid list is empty (terms not loaded), trust the queried object and allow it.
        if (!empty($valid_slugs) && !in_array($q_slug, $valid_slugs, true)) return [];
        return [$q_slug];
    };
 
    // -------------------------
    // Pagination + basic data
    // -------------------------
    $page      = 1;
    $post_type = 'post';
    $search    = '';
    $sort      = 'featured';
 
    // -------------------------
    // Optional filters from GET — fall back to page slug only when it's a valid filter term
    // -------------------------
    $topic           = (array) ($_GET['topicType']      ?? $page_slug_filter('topic',           $valid_topic_slugs));
    $type            = (array) ($_GET['type']            ?? $page_slug_filter('filter-types',    $allowed_type_slugs ?: $term_slugs($type_terms)));
    $persona         = (array) ($_GET['persona']         ?? $page_slug_filter('persona-mapping', $valid_persona_slugs));
    $sector          = (array) ($_GET['sector']          ?? $page_slug_filter('sector-analysis', $valid_sector_slugs));
    $trending_themes = (array) ($_GET['trending_themes'] ?? $page_slug_filter('trending-themes', $valid_trending_slugs));
    $event           = (array) ($_GET['event']           ?? $page_slug_filter('insights-event',  []));
    $date            = (array) ($_GET['date']            ?? []);
 
    $allowed_lists = [
        'topic'           => $_GET['allowed_topic']    ?? [],
        // Only use membership-constrained slugs as the allowed list on filter-types pages.
        // On other pages an empty $type should produce no type constraint, not "all allowed types".
        'filter-types'    => ($q_taxonomy === 'filter-types' && !$is_admin) ? $allowed_type_slugs : ($_GET['allowed_type'] ?? []),
        'persona-mapping' => $_GET['allowed_persona']  ?? [],
        'sector-analysis' => $_GET['allowed_sector']   ?? [],
        'trending-themes' => $_GET['allowed_trending'] ?? [],
        'insights-event'  => $_GET['allowed_event']    ?? [],
    ];
 
    // -------------------------
    // Helper: flatten & sanitize
    // -------------------------
    $flatten_and_sanitize = function ($values) {
        $flat = [];
        foreach ((array) $values as $v) {
            if (is_array($v)) {
                $flat = array_merge($flat, $v);
            } else {
                $flat[] = $v;
            }
        }
        return array_map('sanitize_text_field', $flat);
    };
 
    foreach (['topic', 'type', 'persona', 'sector', 'trending_themes', 'event', 'date'] as $key) {
        $$key = $flatten_and_sanitize($$key);
    }
 
    $raw_topic        = $topic;
    $has_event_filter = !empty($event);
 
    // -------------------------
    // Normalize filters against allowed lists
    // -------------------------
    $normalize_filter = function ($values, $allowed) use ($flatten_and_sanitize) {
        $allowed = $flatten_and_sanitize($allowed);
        return empty($values) ? $allowed : $flatten_and_sanitize($values);
    };
 
    $topic           = $normalize_filter($topic,           $allowed_lists['topic']);
    $type            = $normalize_filter($type,            $allowed_lists['filter-types']);
    $persona         = $normalize_filter($persona,         $allowed_lists['persona-mapping']);
    $sector          = $normalize_filter($sector,          $allowed_lists['sector-analysis']);
    $trending_themes = $normalize_filter($trending_themes, $allowed_lists['trending-themes']);
    $event           = $normalize_filter($event,           $allowed_lists['insights-event']);
 
    // -------------------------
    // Date filter
    // -------------------------
    $date_query = [];
    if (!empty($_GET['date'])) {
        foreach (array_map('sanitize_text_field', (array) $_GET['date']) as $d) {
            if (preg_match('/^(\d{4})-(\d{2})$/', $d, $m)) {
                $date_query[] = ['year' => (int) $m[1], 'month' => (int) $m[2]];
            }
        }
    }
 
    // -------------------------
    // Topic for card rendering
    // -------------------------
    $has_topic_filter    = !empty($_GET['topic']);
    $filtered_topic      = $has_topic_filter ? sanitize_text_field($_GET['topic'][0]) : null;
    $card_filtered_topic = $filtered_topic;
 
    // -------------------------
    // WP_Query args
    // -------------------------
    $args = [
        'post_type'        => $post_type,
        'posts_per_page'   => 13, // fetch 13 to detect whether a next page exists (we only render 12)
        'paged'            => $page,
        'post_status'      => 'publish',
        'suppress_filters' => false,
        'no_found_rows'    => true, // Load More uses JS — skip SQL_CALC_FOUND_ROWS
    ];
 
    if (!empty($search)) $args['s'] = $search;
 
    if ($sort === 'featured') {
        $args['meta_query'] = [
            'featured_clause' => ['key' => 'is_featured', 'compare' => 'EXISTS'],
        ];
        $args['orderby'] = ['featured_clause' => 'DESC', 'date' => 'DESC'];
    } else {
        $args['orderby'] = ['date' => 'DESC'];
    }
 
    // -------------------------
    // Tax query
    // -------------------------
    $tax_query  = [];
    $taxonomies = [
        'topic'           => $topic,
        'filter-types'    => $type,
        'trending-themes' => $trending_themes,
        'persona-mapping' => $persona,
        'sector-analysis' => $sector,
        'insights-event'  => $event,
    ];
 
    foreach ($taxonomies as $taxonomy => $terms) {
        if (!empty($terms)) {
            $tax_query[] = ['taxonomy' => $taxonomy, 'field' => 'slug', 'terms' => $terms, 'operator' => 'IN'];
        }
    }
 
    // Admins bypass subscription gating entirely
    if (!$is_admin && !empty($allowed_subscriptions)) {
        $tax_query[] = ['taxonomy' => 'subscription', 'field' => 'term_id', 'terms' => $allowed_subscriptions, 'operator' => 'IN'];
    }
 
    if (!empty($tax_query))  $args['tax_query']  = array_merge(['relation' => 'AND'], $tax_query);
    if (!empty($date_query)) $args['date_query'] = array_merge(['relation' => 'OR'],  $date_query);
 
    // -------------------------
    // Run query & render cards
    // Temporarily remove the remove_already_displayed_posts hook so $displayed_posts
    // (populated by the template before this function runs) doesn't exclude items
    // that AJAX would correctly return — causing a visible mismatch on page load.
    // -------------------------
    remove_action('pre_get_posts', 'remove_already_displayed_posts');
    if (!empty($search)) {
        $query = new WP_Query($args);
        // $query->parse_query($args);
        // relevanssi_do_query($query);
    } else {
        $query = new WP_Query($args);
    }
    add_action('pre_get_posts', 'remove_already_displayed_posts');
 
    // If we got 13 results, a next page exists — set global for the template to use.
    $GLOBALS['adapt_has_more_posts'] = $query->post_count > 12;
 
    $rendered = 0;
    while ($query->have_posts() && $rendered < 12) {
        $query->the_post();
        $rendered++;
        $eventtype      = $has_event_filter ? 'yes' : 'no';
        $filtered_topic = $card_filtered_topic;
        include locate_template('/templates/components/_article-card.php');
    }
}

function adapt_register_agent_tester_role() {
    if ( ! get_role( 'agent_tester' ) ) {
        add_role( 'agent_tester', 'Agent Tester', array(
            'read' => true,
        ) );
    }
}
add_action( 'init', 'adapt_register_agent_tester_role' );

// One-time bulk role assignment for a fixed list of existing users - already
// ran successfully (agent_tester role added to all 232 users below without
// touching their existing roles). Only the add_action() below is commented
// out, not the function itself - a commented-out add_action() means nothing
// ever calls this function, since hooking it to 'init' was the only thing
// that invoked it in the first place, so this is just as inert as wrapping
// the whole thing in a block comment, but faster to re-enable later (one
// line, no un-nesting a /* */ block). To re-run: uncomment the add_action()
// call below and clear the option flag first via
// delete_option('adapt_agent_tester_role_assigned'), since it already ran
// once.
function adapt_assign_agent_tester_role_once() {
    if ( get_option( 'adapt_agent_tester_role_assigned' ) ) {
        return;
    }

    $user_ids = array(
        1897, 1294, 1896, 1284, 1895, 1438, 1612, 1373, 1573, 1281,
        1379, 1894, 1839, 1893, 1838, 1809, 1892, 1885, 1292, 1370,
        1610, 1296, 1827, 1297, 1371, 1832, 1830, 1833, 1829, 1828,
        1596, 1825, 1824, 1288, 1375, 1306, 1653, 1630, 1286, 1536,
        959,  1021, 973,  967,  962,  960,  899,  151,  1724, 988,
        965,  970,  961,  1611, 1365, 1550, 1566, 1629, 1556, 1564,
        1377, 1554, 1547, 1565, 1558, 1567, 1552, 1283, 1716, 1652,
        1712, 1655, 1654, 1656, 1400, 1572, 1557, 1562, 1553, 1563,
        1561, 1560, 1559, 1551, 1555, 1657, 1659, 1397, 1380, 1392,
        1390, 1389, 1386, 1387, 1385, 1384, 1383, 1374, 1364, 467,
        1366, 1658, 1729, 1393, 1367, 1422, 1011, 1140, 1860, 1590,
        1430, 154,  1183, 1231, 1136, 1141, 1134, 1165, 1150, 1147,
        1146, 1133, 1144, 1137, 1428, 1135, 1010, 344,  1139, 1145,
        1143, 1142, 1138, 1864, 1867, 1172, 1265, 1802, 1723, 1761,
        1680, 1421, 1316, 1372, 1368, 1312, 1308, 1307, 1305, 1300,
        1299, 1298, 1295, 1291, 1290, 1289, 1287, 1681, 1890, 1793,
        1727, 1710, 1722, 1757, 1589, 1726, 1835, 1808, 1788, 1758,
        1591, 1709, 1683, 1704, 1711, 1691, 1705, 1702, 1707, 1701,
        1696, 1706, 1689, 1685, 1717, 1693, 1679, 1725, 1687, 1697,
        1684, 1719, 1713, 1692, 1434, 1433, 1432, 1311, 1240, 1180,
        912,  1047, 1045, 409,  392,  391,  929,  389,  921,  914,
        911,  908,  707,  406,  390,  400,  405,  1700, 1884, 909,
        1682, 910,  1694, 1190, 1784, 1792, 1688, 1686, 1695, 1690,
        913,  1721,
    );

    // $user_ids = array(421, 1811, 1179, 1189, 1317, 1178, 1181, 1171, 1177, 1176, 1175, 1173, 1174);

    foreach ( $user_ids as $user_id ) {
        $user = get_user_by( 'id', $user_id );
        if ( $user ) {
            $user->add_role( 'agent_tester' );
        }
    }

    update_option( 'adapt_agent_tester_role_assigned', 1 );
}
// add_action( 'init', 'adapt_assign_agent_tester_role_once', 20 );

// One-time bulk removal: strips the agent_tester role from the same 232
// users above, using WP_User::remove_role() so only that one role is taken
// off - any other role(s) each user has stay exactly as they are. Same
// pattern as above - the add_action() below is commented out rather than
// the function itself, so it will not run until that line is uncommented.
// Does not unregister the agent_tester role itself, just stops applying it.
function adapt_remove_agent_tester_role_once() {
    if ( get_option( 'adapt_agent_tester_role_removed' ) ) {
        return;
    }

    $user_ids = array(
        1897, 1294, 1896, 1284, 1895, 1438, 1612, 1373, 1573, 1281,
        1379, 1894, 1839, 1893, 1838, 1809, 1892, 1885, 1292, 1370,
        1610, 1296, 1827, 1297, 1371, 1832, 1830, 1833, 1829, 1828,
        1596, 1825, 1824, 1288, 1375, 1306, 1653, 1630, 1286, 1536,
        959,  1021, 973,  967,  962,  960,  899,  151,  1724, 988,
        965,  970,  961,  1611, 1365, 1550, 1566, 1629, 1556, 1564,
        1377, 1554, 1547, 1565, 1558, 1567, 1552, 1283, 1716, 1652,
        1712, 1655, 1654, 1656, 1400, 1572, 1557, 1562, 1553, 1563,
        1561, 1560, 1559, 1551, 1555, 1657, 1659, 1397, 1380, 1392,
        1390, 1389, 1386, 1387, 1385, 1384, 1383, 1374, 1364, 467,
        1366, 1658, 1729, 1393, 1367, 1422, 1011, 1140, 1860, 1590,
        1430, 154,  1183, 1231, 1136, 1141, 1134, 1165, 1150, 1147,
        1146, 1133, 1144, 1137, 1428, 1135, 1010, 344,  1139, 1145,
        1143, 1142, 1138, 1864, 1867, 1172, 1265, 1802, 1723, 1761,
        1680, 1421, 1316, 1372, 1368, 1312, 1308, 1307, 1305, 1300,
        1299, 1298, 1295, 1291, 1290, 1289, 1287, 1681, 1890, 1793,
        1727, 1710, 1722, 1757, 1589, 1726, 1835, 1808, 1788, 1758,
        1591, 1709, 1683, 1704, 1711, 1691, 1705, 1702, 1707, 1701,
        1696, 1706, 1689, 1685, 1717, 1693, 1679, 1725, 1687, 1697,
        1684, 1719, 1713, 1692, 1434, 1433, 1432, 1311, 1240, 1180,
        912,  1047, 1045, 409,  392,  391,  929,  389,  921,  914,
        911,  908,  707,  406,  390,  400,  405,  1700, 1884, 909,
        1682, 910,  1694, 1190, 1784, 1792, 1688, 1686, 1695, 1690,
        913,  1721,
    );

    foreach ( $user_ids as $user_id ) {
        $user = get_user_by( 'id', $user_id );
        if ( $user ) {
            $user->remove_role( 'agent_tester' );
        }
    }

    update_option( 'adapt_agent_tester_role_removed', 1 );
}
// add_action( 'init', 'adapt_remove_agent_tester_role_once', 20 );