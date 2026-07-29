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

function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyDss6XUuPFsJgunJJ6dZZjzuR9d39WtjRU');
}

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
                do_action('profile_update', $user_id, $member);

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

add_filter(
  'ep_prepare_meta_data',
  function( $all_meta ) {
    // Change this array to match all meta keys you want to index.
    $allowed_meta = array(
      'author_name',
      'author_description',
      'article_content',
      'contributors_0_contributor_name',
      'contributors_0_contributor_label',
      'contributors_1_contributor_name',
      'contributors_1_contributor_label',
      'contributors_2_contributor_name',
      'contributors_2_contributor_label',
      'contributors_3_contributor_name',
      'contributors_3_contributor_label',
      'contributors_4_contributor_name',
      'contributors_4_contributor_label',
      'content_blocks_0_text_editor',
      'content_blocks_4_text_editor',
      'content_blocks_0_quotes_0_quote',
      'content_blocks_0_quotes_0_quote_author',
      'content_blocks_0_quotes',
      'content_blocks_1_block_title',
      'content_blocks_1_item',
      'content_blocks_2_text_editor',
      'content_blocks_5_block_title',
      'content_blocks_6_text_editor',
      'content_blocks_9_block_title',
      'content_blocks_9_text_editor',
      'author_search_names',
      'content_blocks_2_number_block_2_numbers',
      'content_blocks_2_number_block',
      'content_blocks_9_quotes_0_quote',
      'content_blocks_9_quotes_0_quote_author',
      'content_blocks_9_quotes',
      'content_blocks_10_block_title',
      'content_blocks_3_quotes_0_quote',
      'content_blocks_3_quotes_0_quote_author',
      'content_blocks_3_quotes',
      'content_blocks_4_items_0_video_columns_0_listing_title',
      'content_blocks_4_items_0_video_columns_0_listing_text',
      'content_blocks_4_items_0_video_columns_0_vimeo_code',
      'content_blocks_4_items_0_video_columns_1_listing_title',
      'content_blocks_4_items_0_video_columns_1_listing_text',
      'content_blocks_4_items_1_video_columns_0_listing_title',
      'content_blocks_4_items_1_video_columns_0_listing_text',
      'content_blocks_4_items_1_video_columns_1_listing_title',
      'content_blocks_4_items_1_video_columns_1_listing_text',
      'content_blocks_2_taxonomy_type',
      'content_blocks_2_topic',
      'content_blocks_13_taxonomy_type',
      'content_blocks_13_topic',
      'content_blocks_13_filter_type',
      'content_blocks_1_filter_type',
      'content_blocks_3_taxonomy_type',
      'content_blocks_3_filter_type',
      'content_blocks_3_topic',
      'content_blocks_1_top_right_text',
      'content_blocks_2_listing_text',
      'content_blocks_6_title',
      'content_blocks_6_description',
      'content_blocks_7_items_0',
      'advantage_home_content_blocks_0_introduction_text',
      'advantage_home_content_blocks_0_text',
      'script_text_area',
      'welcome_message',
      'how_to_get_started_link_text',
      'persona',
      'sector',
      'topic_buttons',
      'topic',
      'featured_article',
      'share_title',
      'listing_page_grid',
      'event_short_description_for_listing',
      'show_register',
      'agenda',
      'event_date',
      'hide_main_menu',
      'content_blocks_4_form_id',
      'content_blocks_4_block_description',
      'content_blocks_8_description',
      'content_blocks_8_quotes_0_quote',
      'content_blocks_8_quotes_0_quote_author',
      'content_blocks_8_quotes_1_quote',
      'content_blocks_8_quotes_1_quote_author',
      'content_blocks_8_quotes_2_quote',
      'content_blocks_8_quotes_2_quote_author',
      'content_blocks_8_quotes_3_quote',
      'content_blocks_8_quotes_3_quote_author',
      'content_blocks_8_quotes_4_quote',
      'content_blocks_8_quotes_4_quote_author',
      'content_blocks_8_quotes_5_quote',
      'content_blocks_8_quotes_5_quote_author',
      'content_blocks_9_form_id',
      'speaker_description',
      'linked_in_url',
      'speaker_image',
      'quote_block',
      'quote_subtext',
      'hashtags_0_hashtag',
      'hashtags_1_hashtag',
      'hashtags_2_hashtag',
      'speaker_details',
      'pre_button_text',
      'contributors_text_field',
      'short_description_for_listing',
      'event_start_time',
      'event_end_time',
      'registration_form',
      'registration_form_title',
      'takeaways_0_title',
      'takeaways_0_key_takeaways',
      'takeaways',
      'content_blocks_0_speaker',
      'content_blocks_0_speaker',
      'content_blocks_0_speaker'
    );
    $meta = [];

    foreach ( $allowed_meta as $meta_key ) {
      if ( ! isset( $all_meta[ $meta_key ] ) ) {
        continue;
      }
      $meta[ $meta_key ] = $all_meta[ $meta_key ];
    }

    return $meta;
  }
);

add_filter(
	'ep_default_analyzer_filters',
	function( $filters ) {
		if ( version_compare( EP_VERSION, '5.0.0', '>' ) ) {
			_deprecated_function( __METHOD__, 'EP 5.0.0', 'This function is not needed anymore.' );
			return $filters;
		}

		return array_diff( $filters, [ 'ewp_word_delimiter' ] );
	}
);

add_action('wp_ajax_update_download_counter', 'update_download_counter');
add_action('wp_ajax_nopriv_update_download_counter', 'update_download_counter');
function update_download_counter() {
    if (is_user_logged_in() || (function_exists('adapt_content_unlocked') && adapt_content_unlocked())) {
        $user_id = get_current_user_id(); // Get the current user ID
        if (class_exists('MeprUser')) {
            $member = new MeprUser($user_id);

            // Check if the user is active
            if (!user_can($user_id, 'mepr-active')) {
                return; // Exit if the user is not active
            }

            // Check if the user has membership 9811
            if ($member->is_already_subscribed_to(9811)) {
                return; // Exit if the user is a member of 9811
            }
        }
        
        // Get the current download count from the meta field 'mepr_article_downloads'
        $article_downloads = (int) get_user_meta($user_id, 'mepr_article_downloads', true);
        
        // If the meta field doesn't exist or it's not a number, set it to 1
        if (!is_numeric($article_downloads)) {
            $article_downloads = 1;
        } else {
            // Increment the download count by one
            $article_downloads++;
        }
        
        // Update user meta with the new download count
        update_user_meta($user_id, 'mepr_article_downloads', $article_downloads);

        // Get the current download count array from the meta field 'mepr_downloads_30_days_array'
        $download_array = get_user_meta($user_id, 'mepr_downloads_30_days_array', true);
        
        // If the meta field doesn't exist or it's not an array, initialize an empty array
        if (!is_array($download_array)) {
            $download_array = array();
        }

        // Get the current date in the "Ymd" format
        $current_date = date('Ymd');

        // Increment the download count by one and add the current download with the current date
        $download_array[] = array('date' => $current_date);

        // Remove downloads older than 30 days
        $thirty_days_ago_date = date('Ymd', strtotime('-30 days'));
        $new_download_array = array();
        foreach ($download_array as $download) {
            if ($download['date'] >= $thirty_days_ago_date) {
                $new_download_array[] = $download;
            }
        }

        // Update the meta field 'mepr_downloads_30_days_array' with the new array
        update_user_meta($user_id, 'mepr_downloads_30_days_array', $new_download_array);

        // Update the meta field 'mepr_downloads_thirty_days' with the count
        $download_count_thirty = count($new_download_array);
        update_user_meta($user_id, 'mepr_downloads_thirty_days', $download_count_thirty);
        // Trigger the profile update action
        do_action('profile_update', $user_id, $current_user);
    }
    wp_die(); // This is required to terminate immediately and return a proper response
}

add_action('update_user_activity_info_event', 'update_user_activity_info');
function update_user_activity_info() {
    $users = get_users(); // Get all users

    foreach ($users as $user) {
        $user_id = $user->ID; // Get the user ID
        if (class_exists('MeprUser')) {
            $member = new MeprUser($user_id);

            // Check if the user is active
            if (!user_can($user_id, 'mepr-active')) {
                continue; // Skip inactive users
            }

            // Check if the user has membership 9811
            if ($member->is_already_subscribed_to(9811)) {
                continue; // Skip users with membership 9811
            }
        }
        // 30 day counter posts

        // Get the current post count array from the meta field 'mepr_post_views_30_days_array'
        $post_views_array = get_user_meta($user_id, 'mepr_post_views_30_days_array', true);

        // If the meta field doesn't exist or it's not an array, initialize an empty array
        if (!is_array($post_views_array)) {
            $post_views_array = array();
        }

        // Get the current date in the "Ymd" format
        $current_date = date('Ymd');

        // Calculate the date 30 days ago
        // $thirty_days_ago_date = date('Ymd', strtotime('-30 days'));

        // test with one day

        $thirty_days_ago_date = date('Ymd', strtotime('-30 days'));

        $new_post_views_array = array();
        foreach ($post_views_array as $view) {
            // Check if the view date is within the last 30 days
            if ($view['date'] >= $thirty_days_ago_date) {
                $new_post_views_array[] = $view;
            }
        }

        // Update the meta field 'mepr_post_views_30_days_array' with the new array
        update_user_meta($user_id, 'mepr_post_views_30_days_array', $new_post_views_array);

        // Update the meta field 'mepr_post_views_thirty_days' with the count
        $post_count_thirty = count($new_post_views_array);
        update_user_meta($user_id, 'mepr_post_views_thirty_days', $post_count_thirty);

        // 30 day login counter
        // Get the current login count array from the meta field 'mepr_logins_30_days_array'
        $login_array = get_user_meta($user_id, 'mepr_logins_30_days_array', true);

        // If the meta field doesn't exist or it's not an array, initialize an empty array
        if (!is_array($login_array)) {
            $login_array = array();
        }

        $new_login_array = array();
        foreach ($login_array as $login) {
            // Check if the login date is within the last 30 days
            if ($login['date'] >= $thirty_days_ago_date) {
                $new_login_array[] = $login;
            }
        }

        // Update the meta field 'mepr_logins_30_days_array' with the new array
        update_user_meta($user_id, 'mepr_logins_30_days_array', $new_login_array);

        // Update the meta field 'mepr_logins_thirty_days' with the count
        $login_count_thirty = count($new_login_array);
        update_user_meta($user_id, 'mepr_logins_thirty_days', $login_count_thirty);

        // 30 day counter downloads

        // Get the current download count array from the meta field 'mepr_downloads_30_days_array'
        $download_array = get_user_meta($user_id, 'mepr_downloads_30_days_array', true);

        // If the meta field doesn't exist or it's not an array, initialize an empty array
        if (!is_array($download_array)) {
            $download_array = array();
        }

        // Remove downloads older than 30 days
        $new_download_array = array();
        foreach ($download_array as $download) {
            if ($download['date'] >= $thirty_days_ago_date) {
                $new_download_array[] = $download;
            }
        }

        // Update the meta field 'mepr_downloads_30_days_array' with the new array
        update_user_meta($user_id, 'mepr_downloads_30_days_array', $new_download_array);

        // Update the meta field 'mepr_downloads_thirty_days' with the count
        $download_count_thirty = count($new_download_array);
        update_user_meta($user_id, 'mepr_downloads_thirty_days', $download_count_thirty);

        if (user_can($user_id, 'mepr-active')) {
            $member = new MeprUser(); // Assuming $member object holds the user's membership data
            $member->ID = $user_id; // Set the user ID for the MeprUser object
            $login_count = $member->login_count; // Get the login count
            $subscriptions = $member->get_active_subscription_titles(", "); // Get active subscription titles

            // Update user meta
            update_user_meta($user_id, 'mepr_logins', $login_count);
            update_user_meta($user_id, 'mepr_subscriptions', $subscriptions);
            update_user_meta($user_id, 'mepr_active_status', 'active');
        } else {
            update_user_meta($user_id, 'mepr_active_status', 'inactive');
        }                
    }
}

if (!wp_next_scheduled('update_user_activity_info_hook')) {
    wp_schedule_event(time(), 'daily', 'update_user_activity_info_hook');
}

add_action('update_user_activity_info_hook', 'update_user_activity_info');

// Define a function to track user logins
function track_user_logins($user_login, $user) {
    // Get the user ID
    $user_id = $user->ID;
    if (class_exists('MeprUser')) {
        $member = new MeprUser($user_id);

        // Check if the user is active
        if (!user_can($user_id, 'mepr-active')) {
            return; // Exit if the user is not active
        }

        // Check if the user has membership 9811
        if ($member->is_already_subscribed_to(9811)) {
            return; // Exit if the user is a member of 9811
        }
    }
    // Get the current login count array from the meta field 'mepr_logins_30_days_array'
    $login_array = get_user_meta($user_id, 'mepr_logins_30_days_array', true);

    // If the meta field doesn't exist or it's not an array, initialize an empty array
    if (!is_array($login_array)) {
        $login_array = array();
    }

    // Get the current date in the "Ymd" format
    $current_date = date('Ymd');

    // Calculate the date 30 days ago
    $thirty_days_ago_date = date('Ymd', strtotime('-30 days'));    

    $new_login_array = array();
    foreach ($login_array as $login) {
        // Check if the login date is within the last 30 days
        if ($login['date'] >= $thirty_days_ago_date) {
            $new_login_array[] = $login;
        }
    }

    // Increment the login count by one and add the current login with the current date
    $new_login_array[] = array('date' => $current_date);

    // Update the meta field 'mepr_logins_30_days_array' with the new array
    update_user_meta($user_id, 'mepr_logins_30_days_array', $new_login_array);

    // Update the meta field 'mepr_logins_thirty_days' with the count
    $login_count_thirty = count($new_login_array);
    update_user_meta($user_id, 'mepr_logins_thirty_days', $login_count_thirty);
    do_action('profile_update', $user_id, $current_user);
      
}

// Hook the function to the wp_login action
add_action('wp_login', 'track_user_logins', 10, 2);

function user_profile_update_send_webhook($user_id) {
    // Get the user info
    $user_info = get_userdata($user_id);
    if (class_exists('MeprUser')) {
        $member = new MeprUser($user_id);

        // Check if the user is active
        if (!user_can($user_id, 'mepr-active')) {
            return; // Exit if the user is not active
        }

        // Check if the user has membership 9811
        if ($member->is_already_subscribed_to(9811)) {
            return; // Exit if the user is a member of 9811
        }
    }
    // Prepare data to send
    $data = array(
        'user_id' => $user_id,
        'user_email' => $user_info->user_email,
    );

    // Send the data using wp_remote_post
    $response = wp_remote_post('https://hook.us1.make.com/w3qklgxu9s32vawmifxhth3qi6ghlm8o', array(
        'method'    => 'POST',
        'body'      => json_encode($data),
        'headers'   => array(
            'Content-Type' => 'application/json',
        ),
    ));


    // Stage
    // $response = wp_remote_post('https://hook.us1.make.com/j92pieoqi5nocmhvaow7vttbgpllnmdm', array(
    //     'method'    => 'POST',
    //     'body'      => json_encode($data),
    //     'headers'   => array(
    //         'Content-Type' => 'application/json',
    //     ),
    // ));
    
    // Handle the response or log it
    if (is_wp_error($response)) {
        error_log('Webhook error: ' . $response->get_error_message());
    } else {
        error_log('Webhook sent successfully');
    }
}
add_action('profile_update', 'user_profile_update_send_webhook', 10, 1);

add_action('mepr-event-updated', 'custom_subscription_update_action', 10, 1);
add_action('mepr-account-subs-updated', 'custom_subscription_update_action', 10, 1);

function custom_subscription_update_action($user_id) {
    if ($user_id) {
        if (class_exists('MeprUser')) {
            $member = new MeprUser($user_id);

            // Check if the user is active
            if (!user_can($user_id, 'mepr-active')) {
                return; // Exit if the user is not active
            }

            // Check if the user has membership 9811
            if ($member->is_already_subscribed_to(9811)) {
                return; // Exit if the user is a member of 9811
            }
        }
        // Check if the user has active status and update relevant fields
        if (user_can($user_id, 'mepr-active')) {
            $member = new MeprUser(); // Create a new MeprUser object
            $member->ID = $user_id; // Set the user ID for the MeprUser object
            $subscriptions = $member->get_active_subscription_titles(", "); // Get active subscription titles

            // Update user meta
            update_user_meta($user_id, 'mepr_subscriptions', $subscriptions);
            update_user_meta($user_id, 'mepr_active_status', 'active');
        } else {
            update_user_meta($user_id, 'mepr_active_status', 'inactive');
        }

        // Trigger the profile update action
        $current_user = get_userdata($user_id);
        do_action('profile_update', $user_id, $current_user);
    }
}

add_filter('intermediate_image_sizes_advanced', function($sizes) {
    unset($sizes['medium_large']);       // 768x0
    unset($sizes['1536x1536']);          // 1536x1536
    unset($sizes['2048x2048']);          // 2048x2048
    unset($sizes['gallery-landscape']);  // 1280x800
    unset($sizes['gallery-portrait']);   // 800x1280
    return $sizes;
});

?>