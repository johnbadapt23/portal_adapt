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
require('includes/_instagram.php');

function cc_mime_types($mimes) {
  $mimes['json'] = 'text/plain';
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}

function adapt_is_staging() {
    return (
        wp_get_environment_type() === 'staging' ||
        strpos($_SERVER['HTTP_HOST'] ?? '', 'staging') !== false ||
        strpos($_SERVER['HTTP_HOST'] ?? '', 'devstage') !== false
    );
}

function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyDss6XUuPFsJgunJJ6dZZjzuR9d39WtjRU');
}

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

    $active_subscriptions = $member ? $member->active_product_subscriptions('ids') : [];

    if (array_intersect($membership_ids['adv'], $active_subscriptions)) {
        $membershipType = 'advantage';
    } elseif (array_intersect($membership_ids['it'], $active_subscriptions)) {
        $membershipType = 'it-pro';
    } elseif (array_intersect($membership_ids['free'], $active_subscriptions)) {
        $membershipType = 'free-trial';
    } elseif (array_intersect($membership_ids['kyc'], $active_subscriptions)) {
        $membershipType = 'kyc';
    }

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
    // 9. Apply filters globally (lightweight)
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

}, 20); // priority 20 ensures this runs after membership globals are set


add_action('acf/init', 'my_acf_init');
add_filter('upload_mimes', 'cc_mime_types');

add_image_size( 'gallery-landscape', 1280, 800, true );
add_image_size( 'gallery-portrait', 800, 1280, true );

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
    // Ensure 'mepr_interests' is set and valid
    if (isset($_POST['mepr_interests']) && !empty($_POST['mepr_interests'])) {
        global $current_user;
        $user_id = get_current_user_id();
        // Ensure a valid user
        if ($user_id && class_exists('MeprUser')) {
            $member = new MeprUser($user_id);
            // Check if the user is active and not subscribed to membership 9811
            if (user_can($user_id, 'mepr-active') && !$member->is_already_subscribed_to(9811)) {
                // Update the user meta
                update_user_meta($user_id, 'mepr_interests', $_POST['mepr_interests']);

                // Trigger the profile update action
                // do_action('profile_update', $user_id, $member);
                do_action('adapt_profile_metrics_updated', $user_id);

                // Respond with success
                wp_send_json_success();
            } else {
                // Only update the meta if the user is subscribed to 9811 or inactive
                update_user_meta($user_id, 'mepr_interests', $_POST['mepr_interests']);
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
 global $displayed_posts;
 $query->set('post__not_in', $displayed_posts);
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
            $member = new MeprUser($user_id);
            update_user_meta($user_id, 'mepr_logins', $member->login_count);
            update_user_meta($user_id, 'mepr_subscriptions', $member->get_active_subscription_titles(', '));
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
add_action('send_webhook_batch_cron', 'send_webhook_batch');

if (!wp_next_scheduled('send_webhook_batch_cron')) {
    // Schedule to run every 15 minutes
    wp_schedule_event(time(), 'quarterhour', 'send_webhook_batch_cron');
}


/**
 * Send queued webhooks
 */
function send_webhook_batch() {
    $queued = get_transient('webhook_user_queue') ?: [];
    if (empty($queued)) return;

    $staging_url = 'https://hook.us1.make.com/j92pieoqi5nocmhvaow7vttbgpllnmdm';
    $live_url    = 'https://hook.us1.make.com/w3qklgxu9s32vawmifxhth3qi6ghlm8o';
    $url = adapt_is_staging() ? $staging_url : $live_url;

    foreach ($queued as $key => $user) {
        $response = wp_remote_post($url, [
            'method'  => 'POST',
            'body'    => wp_json_encode($user),
            'headers' => ['Content-Type' => 'application/json'],
            'timeout' => 5,
        ]);

        // Remove successfully sent users from queue
        if (!is_wp_error($response)) {
            unset($queued[$key]);
        } else {
            error_log('Webhook error for user ' . $user['user_id'] . ': ' . $response->get_error_message());
        }
    }

    // Save remaining queue
    set_transient('webhook_user_queue', array_values($queued), 20 * MINUTE_IN_SECONDS);
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

    // Update subscriptions and active status
    if (user_can($user_id, 'mepr-active')) {
        $member = new MeprUser($user_id);
        $subscriptions = $member->get_active_subscription_titles(", ");
        update_user_meta($user_id, 'mepr_subscriptions', $subscriptions);
        update_user_meta($user_id, 'mepr_active_status', 'active');
    } else {
        update_user_meta($user_id, 'mepr_active_status', 'inactive');
    }

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
            if (strpos($domain, $keyword) !== false) {
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


// Ajax filtering for various post types
// Enqueue scripts
function my_enqueue_scripts() {
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
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/assets/js/main.min.js?vers=1.9.66',
        array('jquery'),
        null,
        true
    );

    wp_localize_script('main-js', 'ajaxobject', array(
        'ajax_url' => admin_url('admin-ajax.php')
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

// AJAX: Load Partners
add_action('wp_ajax_load_partners', 'ajax_load_partners');
add_action('wp_ajax_nopriv_load_partners', 'ajax_load_partners');

function ajax_load_partners() {
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
            $eventtype = $has_event_filter ? 'yes' : 'no';
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

    // If it has the 'featured' term, set is_featured = 1, else 0
    if (in_array('featured', $terms)) {
        update_post_meta($post_id, 'is_featured', 1);
    } else {
        update_post_meta($post_id, 'is_featured', 0);
    }

});

function get_membership_type_for_user($user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return '';

    $current_user = wp_get_current_user();
    $member = class_exists('MeprUser') ? new MeprUser($user_id) : null;

    // get active subscription IDs
    $active_subscriptions = $member ? $member->active_product_subscriptions('ids') : [];

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

    $free_ids = $get_membership_ids('free_trial_memberships');
    $adv_ids  = $get_membership_ids('advantage_memberships');
    $it_ids   = $get_membership_ids('it_pro_memberships');
    $kyc_ids  = $get_membership_ids('kyc_memberships');

    if (array_intersect($adv_ids, $active_subscriptions)) return 'advantage';
    if (array_intersect($it_ids, $active_subscriptions)) return 'it-pro';
    if (array_intersect($free_ids, $active_subscriptions)) return 'free-trial';
    if (array_intersect($kyc_ids, $active_subscriptions)) return 'kyc';

    return 'default';
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

// -------------------------
// AJAX function
// -------------------------
function ajax_load_filtered_posts() {
    // -------------------------
    // Detect membership
    // -------------------------
    $membershipType = get_membership_type_for_user();
    $allowed_subscriptions = get_allowed_subscriptions_for_user($membershipType);
    $current_user_id = get_current_user_id();

    $member = class_exists('MeprUser') ? new MeprUser($current_user_id) : null;
    $active_subscriptions = $member ? $member->active_product_subscriptions('ids') : [];
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
        $query = new WP_Query();
        $query->parse_query($args);
        relevanssi_do_query($query);
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
        ],
    ]);
}

add_action('wp_ajax_load_filtered_posts', 'ajax_load_filtered_posts');
add_action('wp_ajax_nopriv_load_filtered_posts', 'ajax_load_filtered_posts');

// Persona and Sector Featured

function ajax_load_featured_post() {

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

}, 10, 4);

add_action('wp_ajax_load_past_sessions_unique', 'load_past_sessions_unique');
add_action('wp_ajax_nopriv_load_past_sessions_unique', 'load_past_sessions_unique');

function load_past_sessions_unique() {
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
?>