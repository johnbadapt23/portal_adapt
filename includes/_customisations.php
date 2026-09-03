<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

// theme_pagination
function custom_theme_pagination() {
	$prev_arrow = is_rtl() ? '&gt;' : '&lt;';
	$next_arrow = is_rtl() ? '&lt;' : '&gt;';

	global $wp_query;
	$total = $wp_query->max_num_pages;
	$big = 999999999;
	if( $total > 1 )  {
		 if( !$current_page = get_query_var('paged') )
			 $current_page = 1;
		 if( get_option('permalink_structure') ) {
			 $format = 'page/%#%/';
		 } else {
			 $format = '&paged=%#%';
		 }
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- paginate_links() is a core WP function that returns its own pre-escaped HTML.
		echo '<span class="current">PAGE </span>' . paginate_links([
			'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'		=> $format,
			'current'		=> max( 1, get_query_var('paged') ),
			'total' 		=> $total,
			'mid_size'		=> 3,
			'type' 			=> '',//list
			'prev_text'		=> $prev_arrow,
			'next_text'		=> $next_arrow,
		]);
	}
}

// custom_excerpt_link
function custom_excerpt_link($more){
    global $post;
    return '...';
}

// custom_excerpt_length
function custom_excerpt_length( $length ) {
	return 34;
}

// remove_style_type
function custom_remove_style_type($tag) {
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// remove_thumbnail_dimensions
function custom_remove_thumbnail_dimensions( $html ) {
    return preg_replace('/(width|height)=\"\d*\"\s/', '', $html);
}


// clean navigation
function custom_wp_nav_menu($var) {
  return is_array($var) ? array_intersect($var, [
		'current_page_item',
		'current_page_parent',
		'current_page_ancestor',
		'menu-item-has-children',
		'first',
		'last',
		'vertical',
		'horizontal'
		]
	) : '';
}

function custom_current_to_active($text){
	/*$replace = array(
		'current_page_item' => 'active',
		'current_page_parent' => 'active',
		'current_page_ancestor' => 'active',
		'menu-item-has-children' => 'sub',
	);
	$text = str_replace(array_keys($replace), $replace, $text);*/
	return $text;
}

function custom_strip_empty_classes($menu) {
    //$menu = preg_replace('/ class=""| class="sub-menu"/','',$menu);
    return $menu;
}

function custom_nav_id_filter( $id, $item ) {
	//return strtolower( str_replace( ' ','-',$item->title ) );
}

function custom_add_parent_url_menu_class( $classes = [], $item = false ) {
	$current_url = current_url();

	if( is_front_page() ) {
		return $classes;
	}

	if ( get_post_type() != 'post' &&  get_post_type() != 'page' ){
		//unset($classes[array_search('current_page_parent',$classes)]);
		if ( isset($item->url) )
			if ( strstr( $current_url, $item->url) )
				$classes[] = 'active';
	}

	return $classes;
}

// body classes
function custom_body_classs($classes) {
    global $post;
	$classes = [];

    if (is_home()) {
		array_push($classes, 'page');
		array_push($classes, 'blog');
		//array_push($classes, 'index-post');
    } else if ( is_singular( 'post' ) ) {
		array_push($classes, $post->post_type);
		array_push($classes, sanitize_html_class($post->post_name));
		//array_push($classes, 'single-post');
    } else {
		if($post) {
			array_push($classes, $post->post_type);
			array_push($classes, sanitize_html_class($post->post_name));
			if ( strstr(get_post_meta( $post->ID, '_wp_page_template', true ), '/') && !is_singular( 'post' ) ) {

				$value = explode('/', str_replace('.php', '', get_post_meta( $post->ID, '_wp_page_template', true )));
				if(isset($value)) {
					array_push($classes, $value[1]);
				}
			} else {
				array_push($classes, str_replace('.php', '', get_post_meta( $post->ID, '_wp_page_template', true )));
			}
		}
    }

    return $classes;
}

// querystring vars
function custom_add_query_vars_filter( $vars ){
	$vars[] = "type";
	$vars[] = "select";
	$vars[] = "id";
	return $vars;
}

// disable emojicons
function custom_disable_wp_emojicons() {
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  //add_filter( 'tiny_mce_plugins', 'custom_disable_wp_emojicons' );
}

// disable wp embeds
function custom_disable_embeds() {

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
}

// remove admin menu items
function custom_remove_menus(){
  //remove_menu_page( 'index.php' );                  //Dashboard
  //remove_menu_page( 'edit.php' );                   //Posts
  //remove_menu_page( 'upload.php' );                 //Media
  //remove_menu_page( 'edit.php?post_type=page' );    //Pages
  remove_menu_page( 'edit-comments.php' );          //Comments
  //remove_menu_page( 'themes.php' );                 //Appearance
  //remove_menu_page( 'plugins.php' );                //Plugins
  //remove_menu_page( 'users.php' );                  //Users
  //remove_menu_page( 'tools.php' );                  //Tools
  //remove_menu_page( 'options-general.php' );        //Setting
}

// menu account links
function custom_account_links( $items, $args ) {
   if ((is_user_logged_in() || (function_exists('adapt_content_unlocked') && adapt_content_unlocked())) && $args->theme_location == 'main-menu') {
	   $items .= '<li id="my-account"><a href="'. get_permalink( woocommerce_get_page_id( 'myaccount' ) ) .'">My Account</a></li>';
       $items .= '<li id="cart"><a href="'. get_permalink( woocommerce_get_page_id( 'checkout' ) ) .'">Cart</a></li>';
       $items .= '<li id="logout"><a href="'. get_permalink( woocommerce_get_page_id( 'logout' ) ) .'">Log Out</a></li>';
   } else {
       $items .= '<li id="login"><a href="' . get_permalink( woocommerce_get_page_id( 'myaccount' ) ) . '">Log In</a></li>';
       $items .= '<li id="register"><a href="' . get_permalink( woocommerce_get_page_id( 'myaccount' ) ) . '">Register</a></li>';
   }
   return $items;
}


// gravityforms error message
function custom_form_error_message( $message, $form ) {
    return "<div class='validation_error'>Looks like one or more fields are missing.</div>";
}

// change logo on wp login
function custom_wp_login_logo() {
    echo '<style  type="text/css"> h1 a { display:block !important; width: 100% !important; height: 108px !important; background-size: 90% !important; background-image:url(' . esc_url( get_bloginfo('template_directory') ) . '/assets/images/logo-admin.png)  !important; } </style>';
}

// change url on wp login
function custom_wp_login_url() {
    return get_option('siteurl');
}

 // change title on wp login
function custom_wp_login_title() {
    return get_option('blogname');
}

// change wp admin footer
function custom_footer_admin() {
    // echo '<span id="footer-thankyou">Developed by <a href="https://dotdev.com.au" target="_blank">DotDev</a></span>';
}

// remove wp version footer
function custom_remove_footer() {
    remove_filter( 'update_footer', 'core_update_footer' );
}

// remove default jquery migrate
function custom_remove_jquery_migrate( &$scripts) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        // Surgically drop the jquery-migrate dependency instead of
        // re-registering jquery under a hardcoded, long-outdated version
        // string (was '1.11.1', from ~2014) - this keeps WordPress core's
        // actual bundled jQuery version and its deps array intact.
        $script = $scripts->registered['jquery'];
        $script->deps = array_diff( $script->deps, [ 'jquery-migrate' ] );
    }
}

// remove default jquery
function custom_remove_jquery() {
   if (!is_admin()) {
      //wp_deregister_script('jquery');
      //wp_register_script('jquery', '', false, '1.8.3');
      //wp_enqueue_script('jquery');
   }
}

// disable json api
function custom_disable_json_api () {

  // Filters for WP-API version 1.x
  add_filter('json_enabled', '__return_false');
  add_filter('json_jsonp_enabled', '__return_false');

  // Filters for WP-API version 2.x
  // Note: rest_enabled/rest_jsonp_enabled were filters from the old
  // standalone JSON REST API plugin era. Once the REST API merged into
  // WP core (4.7) these two stopped having any effect - core's REST API
  // cannot be switched off this way, so despite the function name the
  // REST API has been fully active this whole time. Left in place since
  // removing them changes nothing either way, but see
  // custom_restrict_rest_user_enumeration() below for the hardening that
  // actually does something: it blocks the one part of the REST API
  // (the user list/user detail endpoints) that is genuinely worth
  // restricting, without touching the endpoints wp-admin/Gutenberg need.
  add_filter('rest_enabled', '__return_false');
  add_filter('rest_jsonp_enabled', '__return_false');

}

// Block anonymous access to the REST API user endpoints. Unauthenticated,
// these return every user's display name and slug (wp/v2/users), which is
// a standard first step in enumerating usernames for a credential-stuffing
// or brute-force attempt - a real concern on a membership site where every
// visitor with an account is a valid REST API user. Logged-in requests
// still work as normal (editors/admins picking a post author, etc.).
function custom_restrict_rest_user_enumeration( $result ) {
	if ( ! empty( $result ) ) {
		return $result;
	}

	if ( is_user_logged_in() ) {
		return $result;
	}

	if ( ! empty( $GLOBALS['wp']->query_vars['rest_route'] ) &&
		preg_match( '#^/wp/v2/users(/|$)#', $GLOBALS['wp']->query_vars['rest_route'] ) ) {
		return new WP_Error(
			'rest_forbidden',
			__( 'Sorry, you are not allowed to do that.', 'portal' ),
			[ 'status' => rest_authorization_required_code() ]
		);
	}

	return $result;
}

// Author archives redirect ?author=N to the matching /author/username/
// URL, which leaks a valid username to anyone willing to try a few IDs.
// Send logged-out probes to the homepage instead of letting that redirect
// happen; logged-in users (who already know who they are) are unaffected.
function custom_block_author_enumeration() {
	if ( is_user_logged_in() ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only GET probe check used only to redirect anonymous author-ID enumeration attempts; no state change, and requiring a nonce here would defeat the purpose (a real anonymous visitor won't have one).
	if ( isset( $_GET['author'] ) && preg_match( '/^\d+$/', wp_unslash( $_GET['author'] ) ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}

// A conservative set of response headers with no compatibility risk (they
// do not touch script/style loading the way a Content-Security-Policy
// would, so they will not fight with the HubSpot/Vimeo/GSAP/Google Maps
// embeds already used throughout the theme).
//
// Site Health (Tools > Site Health > Status) flags three more of these as
// missing: Strict-Transport-Security, X-XSS-Protection, and the
// "Upgrade Insecure Requests" CSP directive. All three are added below,
// still within the same no-compatibility-risk philosophy as the three
// already here:
// - Strict-Transport-Security / upgrade-insecure-requests only make sense
//   (and are only sent) once a request has actually arrived over HTTPS -
//   is_ssl() guards both, so this can never tell a browser to force HTTPS
//   on a site, or a specific pageview, that isn't already serving it.
// - upgrade-insecure-requests is sent via the Content-Security-Policy
//   header, but it is the one CSP directive that does not restrict what
//   scripts/styles/frames are allowed to load - it only auto-upgrades an
//   accidental http:// sub-resource reference to https://, so it carries
//   none of the third-party-embed breakage risk a real CSP would.
// - X-XSS-Protection is deprecated in current browser engines (Chromium
//   and Firefox both ignore it), but it is still one of the specific
//   headers WordPress Core's own Site Health check looks for, it is
//   harmless to send, and older/other browsers may still honour it.
function custom_security_headers() {
	if ( headers_sent() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'X-XSS-Protection: 1; mode=block' );

	if ( is_ssl() ) {
		header( 'Strict-Transport-Security: max-age=31536000' );
		header( 'Content-Security-Policy: upgrade-insecure-requests' );
	}
}

// remove rest api
function custom_remove_json_api () {
    // Remove the REST API lines from the HTML Header
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

    // Remove the REST API endpoint.
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );

    // Turn off oEmbed auto discovery.
    add_filter( 'embed_oembed_discover', '__return_false' );

    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );

	// Remove all embeds rewrite rules.
	//add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

}
add_action('after_setup_theme', 	'custom_disable_json_api');
add_action('after_setup_theme', 	'custom_remove_json_api');

// acf google maps
function custom_acf_init() {
   acf_update_setting('google_api_key', 'AIzaSyDss6XUuPFsJgunJJ6dZZjzuR9d39WtjRU');
}


/* add_action( 'ninja_forms_display_after_fields', 'custom_enquiry_value' );
function custom_enquiry_value($formid){
	if ($formid == 9) {
?>
	<input type="hidden" name="ninja_forms_field_10" value="<?php echo esc_attr( get_sub_field('enquiry_email') ); ?>">
<?php
}} */
