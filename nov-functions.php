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

add_filter("gform_confirmation_anchor", create_function("","return true;"));

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

function ajaxtest_function(){
    global $current_user, $wp_roles;
    update_user_meta( $current_user->ID, 'mepr_interests', $_POST['mepr_interests'] );

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
?>