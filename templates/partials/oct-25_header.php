<?php
    $current_user = wp_get_current_user();
    // echo $current_user;
    if ( 0 == $current_user->ID ) {
        if ( is_front_page() ) { ?>
            <script type="text/javascript">
                document.location.href="/login/";
            </script>
        <?php }
    } else {
        $user_info = get_userdata($current_user);
        $first_name = $current_user->first_name;
        $last_name = $current_user->last_name;
        $user_email = $current_user->user_email;
        $interests = $current_user->mepr_interests;
    }
    $user_info = wp_get_current_user();
    if(current_user_can('mepr-active','memberships:9811')) {
    } else {
         if(current_user_can('mepr-active')){
            $user_name = do_shortcode('[mepr-account-info field="full_name"]');
            $user_ID = $user_info->ID;
            $member = new MeprUser(); // initiate the class
            $member->ID = $user_info->ID; // if using this in admin area, you'll need this to make user id the member id
            $memberships = $member->get_active_subscription_titles(" & ");
            $userLastLogin = $user_info->last_action_time;
            $userLogins = $member->login_count;
        }

        if (is_user_logged_in() || (function_exists('adapt_content_unlocked') && adapt_content_unlocked())) {
            $user_id = get_current_user_id(); // Get the current user ID

            // Check if we have already updated user meta in this session
            $transient_key = 'mepr_meta_updated_' . $user_id;

            if (false === get_transient($transient_key)) {                
                $login_count = $member->login_count; // Assuming $member object holds the login count
                $subscriptions = $member->get_active_subscription_titles(", ");

                // Update user meta
                update_user_meta($user_id, 'mepr_logins', $login_count);
                update_user_meta($user_id, 'mepr_subscriptions', $subscriptions);

                // Set a transient to mark this session as updated
                set_transient($transient_key, true, DAY_IN_SECONDS); // Transient expires after 1 day
            } else {
                
            }
        }
    }

    $filterType = $_GET['filterby'];
    $keyword = $_GET['searchWords'];
    $filterTopic = $_GET['filter-topic'];
    $filterSubTopic = $_GET['filter-subtopic'];
    $globalKeyword = $_GET['s'];

    $testMemberships = array('281', '263', '262', '261', '222');

 if(current_user_can('mepr-active','memberships:9811')) {
 } else {
     if (in_array( $user_ID, $testMemberships )) {
     } else {
         if ( $keyword != '' || $filterTopic != '' || $filterSubTopic != '' || $filterType != '' || $globalKeyword != '' ) {
             $url =  strtok($_SERVER["REQUEST_URI"], '?');
             $date = date(DateTime::ISO8601);
             $list = array
             (
                 $url.','.$keyword.','.$globalKeyword.','.$filterTopic.','.$filterSubTopic.','.$filterType.','.$date.','.$user_name.','.$memberships
             );

             $file = fopen('search-log.csv','a');  // 'a' for append to file - created if doesn't exit
             // $file = fopen('https://adaptuser:adaptCSVacc3ss@adapt-portal.viewstage.com.au/search-log.csv','a');
             foreach ($list as $line) {
               fputcsv($file,explode(',',$line));
             }

             fclose($file);
         }
     }
 }
$current_user = wp_get_current_user();
$member = new MeprUser($current_user->ID);
$advantageType = "no";
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
?>
<?php global $membershipType; ?>
<?php if(current_user_can('mepr-active','memberships:48815')){
    $membershipType = 'tnc';
} ?>
<?php $kycMemberships = array('49569', '49567', '49565', '49563', '49561', '49559', '49557'); ?>
<?php if(current_user_can('mepr-active','memberships:' .$kycMemberships)){
    $membershipType = 'kyc';
} ?>
<?php if ( have_rows( 'free_trial_memberships', 'options' ) ) : ?>
<?php $counter = 0; ?>
    <?php while ( have_rows( 'free_trial_memberships', 'options' ) ) : the_row(); ?>
        <?php if ( $counter == 0 ) {
           $membersFree = $membersFree . get_sub_field( 'membership_id' );
        } else {
           $membersFree = $membersFree . ',' . get_sub_field( 'membership_id' );
        } ?>
        <?php $counter++; ?>
    <?php endwhile; ?>
    <?php if(current_user_can('mepr-active','memberships:' . $membersFree)){
        $membershipType = 'free-trial';
    } ?>
<?php endif; ?>
<?php if ( have_rows( 'advantage_memberships', 'options' ) ) : ?>
<?php $counter = 0; ?>
    <?php while ( have_rows( 'advantage_memberships', 'options' ) ) : the_row(); ?>
        <?php if ( $counter == 0 ) {
           $members = $members . get_sub_field( 'membership_id' );
        } else {
           $members = $members . ',' . get_sub_field( 'membership_id' );
        } ?>
        <?php $counter++; ?>
    <?php endwhile; ?>
    <?php if(current_user_can('mepr-active','memberships:' . $members)){
        $membershipType = 'advantage';
    } ?>
<?php endif; ?>
<?php if ( have_rows( 'it_pro_memberships', 'options' ) ) : ?>
<?php $counter = 0; ?>
    <?php while ( have_rows( 'it_pro_memberships', 'options' ) ) : the_row(); ?>
        <?php if ( $counter == 0 ) {
           $membersIT = $membersIT . get_sub_field( 'membership_id' );
        } else {
           $membersIT = $membersIT . ',' . get_sub_field( 'membership_id' );
        } ?>
        <?php $counter++; ?>
    <?php endwhile; ?>
    <?php if(current_user_can('mepr-active','memberships:41272')){
        $membershipType = 'advantage';
    } else { ?>
        <?php if(current_user_can('mepr-active','memberships:' . $membersIT)){
            $membershipType = 'it-pro';
        } ?>
    <?php } ?>
<?php endif; ?>
<?php // Get user memberships using the function, if available
// Initiate the MeprUser class
$member = new MeprUser();
$member->ID = $current_user->ID; // Set user ID to the member's ID

// Get the active subscriptions for this user
$active_subscriptions = $member->active_product_subscriptions('ids');

// Initialize an array to hold the subscription IDs
$subscription_ids = [];

foreach ($active_subscriptions as $subscription) {
    $subscription_ids[] = $subscription;
}

if (user_can($current_user, 'administrator')) {
    // Admin can access all memberships, so we directly check if the user has any of the membership IDs
    if (in_array(9811, $subscription_ids)) {
        $membershipType = 'it-pro';
    } elseif (in_array(41272, $subscription_ids)) {
        $membershipType = 'advantage';
    }
} ?>
<span style="display: none;">
<span class="user-info"><?php print_r($user_info); ?></span>
<span class="member-info"><?php print_r($member); ?></span>

    <span class="last-login"><?php echo $userLastLogin; ?></span>
    <span class="login-counts"><?php echo $userLogins; ?></span>
</span>
<header class="header clear<?php if(get_field( 'remove_main_menu' )){ ?> <?php echo get_field( 'remove_main_menu' ); ?><?php } ?>" role="banner">
	<div class="top">
		<div class="container">
			<span class="logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-mobile.svg" width="26" height="46" loading="lazy" alt="Adapt" />
				</a>
				<a class="randalogo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/randa-logo.svg" width="239" height="13" loading="lazy" alt="Research & Advisory" />
				</a>
			</span>
			<span class="headerTopRight">
                <span class="search">
                    <span class="search-toggle"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/search-icon.svg" width="16" height="16" loading="lazy" alt="Search" /></span>
					<form action="/" method="get">
						<input class="searchInput" type="text" name="s" id="search" placeholder="Search..." value="" />
					</form>
				</span>
				<span class="notifications">
					<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 15.6 15.5" style="enable-background:new 0 0 15.6 15.5;" xml:space="preserve">
					<style type="text/css">
						.st0{fill:#838383;}
					</style>
					<path class="st0" d="M5.9,13.5h3.9c0,1.1-0.9,2-2,2C6.7,15.5,5.9,14.6,5.9,13.5z M1,12.6c-0.5,0-1-0.4-1-1c0-0.5,0.4-1,1-1h0.5
						c0.9-0.7,1.4-1.8,1.5-2.9V4.8C2.9,2.2,5.1,0,7.7,0c0,0,0.1,0,0.1,0c2.7,0,4.8,2.1,4.9,4.7c0,0,0,0.1,0,0.1v2.9
						c0.1,1.1,0.6,2.2,1.5,2.9h0.5c0.5,0,1,0.4,1,1c0,0.5-0.4,1-1,1L1,12.6z"/>
					</svg>
				</span>
                <a class="favourites" href="/saved-insights">
    				<span class="saved">
    					<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
    						 viewBox="0 0 11.5 15.1" style="enable-background:new 0 0 11.5 15.1;" xml:space="preserve">
    					<style type="text/css">
    						.st0{fill:#838383;}
    					</style>
    					<g transform="translate(22.5 0.882)">
    						<path class="st0" d="M-11.6-0.9H-22c-0.3,0-0.5,0.2-0.5,0.6v14c0,0.3,0.2,0.5,0.5,0.6c0,0,0,0,0,0l0,0c0.1,0,0.3-0.1,0.4-0.2
    							l4.8-4.8l0.1,0.1l4.7,4.7c0.2,0.2,0.6,0.2,0.8,0c0.1-0.1,0.2-0.2,0.2-0.4v-14C-11-0.6-11.3-0.9-11.6-0.9z"/>
    					</g>
    					</svg>
    				</span>
                </a>
				<span class="help" style="display: none">
					<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 16.7 16.7" style="enable-background:new 0 0 16.7 16.7;" xml:space="preserve">
					<style type="text/css">
						.st0{fill:#838383;}
					</style>
					<path class="st0" d="M8.3,0C3.7,0,0,3.7,0,8.3s3.7,8.3,8.3,8.3s8.3-3.7,8.3-8.3v0C16.7,3.7,13,0,8.3,0z M9.2,12.9H7.4v-1.8h1.8V12.9
						z M10.9,7.8c-0.3,0.3-0.6,0.6-0.9,0.8L9.6,8.9C9.4,9.1,9.2,9.3,9.1,9.6c-0.1,0.2-0.1,0.5-0.1,0.7H7.4c0-0.5,0.1-1,0.2-1.4
						C7.8,8.5,8.1,8.2,8.4,8l0.4-0.3C9,7.5,9.1,7.4,9.2,7.3C9.4,7,9.5,6.8,9.5,6.5c0-0.3-0.1-0.6-0.3-0.8c-0.2-0.3-0.6-0.4-1-0.4
						c-0.4,0-0.8,0.1-1,0.5C7,6,6.9,6.4,6.9,6.7H5.1c0-1,0.4-1.9,1.2-2.5c0.5-0.3,1.1-0.5,1.8-0.5c0.8,0,1.6,0.2,2.3,0.7
						c0.6,0.5,1,1.2,0.9,2C11.3,6.9,11.2,7.4,10.9,7.8L10.9,7.8z"/>
					</svg>
				</span>
                <?php if ( 0 == $current_user->ID ) { ?>
                <?php } else { ?>
                    <span class="user desktop dropdown users-menu">
    					<span class="userIcon"><span><?php echo $first_name[0]; ?></span></span>
    					<span class="userName"><?php echo $first_name; ?></span>
    					<span class="userDropdown"></span>
                        <div class="megaMenu usersMenu" id="users">
                            <div class="userMenuInfo">
                                <span class="userIconMain"><span><?php echo $first_name[0]; ?></span></span>
                                <span class="userNameMain"><span><?php echo $first_name; ?> <?php echo $last_name; ?></span></span>
                                <span class="userEmailMain"><span><?php echo $user_email; ?></span></span>
                            </div>
                            <?php if ($membershipType == 'free-trial') { ?>
                            <?php } else { ?>
                                <?php if ( have_rows( 'menu_links_top', 'option' ) ) : ?>
                                    <div class="userMenuTop">
                                    	<?php while ( have_rows( 'menu_links_top', 'option' ) ) : the_row(); ?>
                                    		<?php if ( have_rows( 'link' ) ) : ?>
                                    			<?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                    				<a class="userMenuLink" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                    			<?php endwhile; ?>
                                    		<?php else : ?>
                                    			<?php // no rows found ?>
                                    		<?php endif; ?>
                                    	<?php endwhile; ?>
                                    </div>
                                <?php else : ?>
                                	<?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                            <?php if ( have_rows( 'menu_links_bottom', 'option' ) ) : ?>
                                <div class="userMenuBottom">
                                	<?php while ( have_rows( 'menu_links_bottom', 'option' ) ) : the_row(); ?>
                                		<?php if ( have_rows( 'link' ) ) : ?>
                                			<?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                <a class="userMenuLink <?php echo get_sub_field( 'link_class' ); ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                			<?php endwhile; ?>
                                		<?php else : ?>
                                			<?php // no rows found ?>
                                		<?php endif; ?>
                                	<?php endwhile; ?>
                                </div>
                            <?php else : ?>
                            	<?php // no rows found ?>
                            <?php endif; ?>
                            <div class="userMenuLogout">
                                <span class="log-out-link"><?php echo do_shortcode("[mepr-login-link]"); ?></span>
                            </div>
                        </div>
    				</span>
                <?php }?>

			</span>
		</div>
	</div>

	<div class="bottom">
		<div class="container">
			<span class="desktopNav">
                <?php if ($membershipType == 'tnc' || $membership == 'kyc') { ?>
                    <ul>
                        <?php if ($membership == 'kyc') { ?>
                            <li>
                                <a href="/kyc/persona/">KYC Kits</a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="/tnc/">The Next Conversation</a>
                        </li>
                    </ul>
                <?php } else { ?> 
                    <ul>                    
                        <li>
                            <?php if ($membershipType == 'free-trial') { ?>
                                <?php if ( have_rows( 'free_trial_memberships', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'free_trial_memberships', 'options' ) ) : the_row(); ?>
                                        <?php $members = get_sub_field( 'membership_id' ); ?>
                                        <?php if(current_user_can('mepr-active','memberships:' . $members)){
                                            $membersNameMenu = get_sub_field( 'title_for_homepage' );
                                        } ?>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            <?php } ?>
                            <?php if ($membershipType == 'free-trial') { ?>
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo $membersNameMenu; ?></a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                            <?php } ?>
                        </li>
                        <li class="dropdown topics-menu">
                            <a>Research</a>
                            <div class="megaMenu topicsMenu" id="topics">
                                <!-- <span class="mobile-menu-title">Research & Advisory</span> -->
                                <div class="container">
                                    <?php if ( have_rows( 'topics_column_one', 'option' ) ) : ?>
                                        <?php while ( have_rows( 'topics_column_one', 'option' ) ) : the_row(); ?>
                                            <div class="column first">
                                                <?php if ( have_rows( 'group' ) ) : ?>
                                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                        <span class="dropDownSection">
                                                            <?php $icon = get_sub_field( 'icon' ); ?>
                                                            <span class="columnTitle">
                                                                <?php if ( $icon ) { ?>
                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'], 'class' => 'topic-icon' ) ); ?>
                                                                <?php } ?>
                                                                <?php echo get_sub_field( 'title' ); ?>
                                                            </span>
                                                            <?php if ( have_rows( 'link' ) ) : ?>
                                                                <ul>
                                                                    <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                        <?php $topic_link_term = get_sub_field( 'topic_link' ); ?>
                                                                        <?php if ( $topic_link_term ): ?>
                                                                            <li>
                                                                                <a href="<?php echo get_term_link($topic_link_term); ?>"><?php echo $topic_link_term->name; ?></a>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                    <?php endwhile; ?>
                                                                </ul>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        </span>

                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <?php if ( have_rows( 'topics_column_two', 'option' ) ) : ?>
                                        <?php while ( have_rows( 'topics_column_two', 'option' ) ) : the_row(); ?>
                                            <div class="column second">
                                                <?php if ( have_rows( 'group' ) ) : ?>
                                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                        <span class="dropDownSection">
                                                            <?php $icon = get_sub_field( 'icon' ); ?>
                                                            <span class="columnTitle">
                                                                <?php if ( $icon ) { ?>
                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'], 'class' => 'topic-icon' ) ); ?>
                                                                <?php } ?>
                                                                <?php echo get_sub_field( 'title' ); ?>
                                                            </span>
                                                            <?php if ( have_rows( 'link' ) ) : ?>
                                                                <ul>
                                                                    <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                        <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                            <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                            <?php if ( $type_link_term ): ?>
                                                                                <?php if ($type_link_term->slug === 'state-nation') { ?>
                                                                                    <?php if ($membershipType == 'it-pro') { ?>
                                                                                         <li>
                                                                                            <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                                        </li>
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                     <?php if ($type_link_term->slug === 'community-interviews' && $advantageType === 'yes') {
                                                                                        $type_link_term->name = 'Voice of Customers';
                                                                                    } ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                                    </li>
                                                                                <?php } ?>
                                                                               
                                                                            <?php endif; ?>
                                                                        <?php } else { ?>
                                                                            <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                            <?php if ( $other_link ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_sub_field( 'other_link' ); ?>" ><?php echo $other_link; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php } ?>
                                                                    <?php endwhile; ?>
                                                                </ul>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <?php if ( have_rows( 'topics_column_three', 'option' ) ) : ?>
                                        <?php while ( have_rows( 'topics_column_three', 'option' ) ) : the_row(); ?>
                                            <div class="column third">
                                                <?php if ( have_rows( 'group' ) ) : ?>
                                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                        <span class="dropDownSection">
                                                            <?php $icon = get_sub_field( 'icon' ); ?>
                                                            <span class="columnTitle">
                                                                <?php if ( $icon ) { ?>
                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'], 'class' => 'topic-icon' ) ); ?>
                                                                <?php } ?>
                                                                <?php echo get_sub_field( 'title' ); ?>
                                                            </span>
                                                            <?php if ( have_rows( 'link' ) ) : ?>
                                                                <ul>
                                                                    <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                        <?php if ( get_sub_field( 'type' ) == 'data'){ ?>
                                                                            <?php if ($membershipType == 'it-pro') { ?>
                                                                                 <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                            <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                            <?php if ( $type_link_term ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php } else { ?>
                                                                            <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                            <?php if ( $other_link ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_sub_field( 'other_link' ); ?>" ><?php echo $other_link; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } else if ( get_sub_field( 'type' ) == 'market') { ?>
                                                                            <?php if ($membershipType == 'advantage') { ?>
                                                                                 <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                            <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                            <?php if ( $type_link_term ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php } else { ?>
                                                                            <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                            <?php if ( $other_link ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_sub_field( 'other_link' ); ?>" ><?php echo $other_link; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } else { ?> 
                                                                            <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                                <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                                <?php if ( $type_link_term ): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                                    </li>
                                                                                <?php endif; ?>
                                                                            <?php } else { ?>
                                                                                <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                                <?php if ( $other_link ): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_sub_field( 'other_link' ); ?>" ><?php echo $other_link; ?></a>
                                                                                    </li>
                                                                                <?php endif; ?>
                                                                            <?php } ?>
                                                                        <?php } ?>                                                                           
                                                                    <?php endwhile; ?>
                                                                </ul>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <?php if ($membershipType == 'advantage') { ?>
                            <li>
                                <a href="/market-narratives/">Market Narratives</a>
                            </li>
                        <?php } ?>
                        <?php if ($membershipType == 'it-pro') { ?>
                            <li>
                                <a href="/ecosystem-partners" target="_self">Ecosystem Partners</a>
                            </li>
                            <!-- <li>
                                <a href="/filter-types/case-studies/">Case Studies</a>
                            </li> -->
                        <?php } ?>  
                        <?php if ($membershipType == 'advantage') { ?> 
                            <li>
                                <a href="/sector-outlooks/" target="_self">Sector Outlooks</a>
                            </li>
                            <li>
                                <a href="/persona-profiles/" target="_self">Persona Profiles</a>
                            </li>
                             <li>
                                <a href="/community/">Community</a>
                            </li>
                        <?php } else { ?>                    
                            <li>
                                <a href="/community/">Community</a>
                            </li>   
                        <?php } ?>                
                        <li class="dropdown events-menu">
                            <a>Events</a>
                            <div class="megaMenu eventsMenu" id="events">
                                <span class="mobile-menu-title">Events</span>
                                <div class="container">
                                    <?php if ( have_rows( 'events_menu', 'option' ) ) : ?>
                                        <ul>
                                            <?php while ( have_rows( 'events_menu', 'option' ) ) : the_row(); ?>
                                                <?php if ( have_rows( 'link' ) ) : ?>
                                                    <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                        <li>
                                                            <a href="<?php echo get_sub_field( 'link' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        </li>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="/whats-new/">What's New</a>
                        </li>
                    </ul>
                <?php } ?>
				
			</span>
			<div class="buttonWrapper">
				<a class="nav">
					<span class="ham"></span>
					<span class="menu">Menu</span>
				</a>
			</div>
            <?php if ( 0 == $current_user->ID ) { ?>
            <?php } else { ?>
    			<span class="user dropdown mobile">
                    <span class="userIcon"><span><?php echo $first_name[0]; ?></span></span>
                    <span class="userName"><?php echo $first_name; ?></span>
                    <span class="userDropdown"></span>
                    <div class="megaMenu usersMenu mobile" id="users-mobile">
                        <div class="userMenuInfo">
                            <span class="userIconMain"><span><?php echo $first_name[0]; ?></span></span>
                            <span class="userNameMain"><span><?php echo $first_name; ?> <?php echo $last_name; ?></span></span>
                            <span class="userEmailMain"><span><?php echo $user_email; ?></span></span>
                        </div>
                        <?php if ($membershipType == 'free-trial') { ?>
                        <?php } else { ?>
                            <?php if ( have_rows( 'menu_links_top', 'option' ) ) : ?>
                                <div class="userMenuTop">
                                    <?php while ( have_rows( 'menu_links_top', 'option' ) ) : the_row(); ?>
                                        <?php if ( have_rows( 'link' ) ) : ?>
                                            <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                <a class="userMenuLink" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </div>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        <?php } ?>
                        <?php if ( have_rows( 'menu_links_bottom', 'option' ) ) : ?>
                            <div class="userMenuBottom">
                                <?php while ( have_rows( 'menu_links_bottom', 'option' ) ) : the_row(); ?>
                                    <?php if ( have_rows( 'link' ) ) : ?>
                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                            <a class="userMenuLink <?php echo get_sub_field( 'link_class' ); ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                        <div class="userMenuLogout">
                            <span class="log-out-link"><?php echo do_shortcode("[mepr-login-link]"); ?></span>
                        </div>
                    </div>
                </span>
                <span class="help mobile" style="display: none">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 16.7 16.7" style="enable-background:new 0 0 16.7 16.7;" xml:space="preserve">
                    <style type="text/css">
                        .st0{fill:#838383;}
                    </style>
                    <path class="st0" d="M8.3,0C3.7,0,0,3.7,0,8.3s3.7,8.3,8.3,8.3s8.3-3.7,8.3-8.3v0C16.7,3.7,13,0,8.3,0z M9.2,12.9H7.4v-1.8h1.8V12.9
                        z M10.9,7.8c-0.3,0.3-0.6,0.6-0.9,0.8L9.6,8.9C9.4,9.1,9.2,9.3,9.1,9.6c-0.1,0.2-0.1,0.5-0.1,0.7H7.4c0-0.5,0.1-1,0.2-1.4
                        C7.8,8.5,8.1,8.2,8.4,8l0.4-0.3C9,7.5,9.1,7.4,9.2,7.3C9.4,7,9.5,6.8,9.5,6.5c0-0.3-0.1-0.6-0.3-0.8c-0.2-0.3-0.6-0.4-1-0.4
                        c-0.4,0-0.8,0.1-1,0.5C7,6,6.9,6.4,6.9,6.7H5.1c0-1,0.4-1.9,1.2-2.5c0.5-0.3,1.1-0.5,1.8-0.5c0.8,0,1.6,0.2,2.3,0.7
                        c0.6,0.5,1,1.2,0.9,2C11.3,6.9,11.2,7.4,10.9,7.8L10.9,7.8z"/>
                    </svg>
                </span>
                <span class="search mobile">
                    <span class="search-toggle"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/search-icon.svg" width="16" height="16" loading="lazy" alt="Search" /></span>
					<form action="/" method="get">
						<input class="searchInput" type="text" name="s" id="search" placeholder="Search..." value="" />
					</form>
                    <span class="search-close-mobile"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/search-close-icon.svg" width="12" height="12" loading="lazy" alt="Close search" /></span>
				</span>
            <?php } ?>

			</span>
		</div>
	</div>

	<nav class="mobileMenu">
		<div class="mobileMenuItems">
			<span class="menuTop">
                 <?php if ($membershipType == 'tnc' || $membership == 'kyc') { ?>
                    <ul>
                        <?php if ($membership == 'kyc') { ?>
                            <li>
                                <a href="/kyc/persona/">KYC Kits</a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="/tnc/">The Next Conversation</a>
                        </li>
                    </ul>
                <?php } else { ?> 
                    <ul>
                        <li>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                        </li>
                        <li>
                            <a href="#">Research</a>
                            <ul class="sub-menu">
                                <?php if ( have_rows( 'topics_column_one', 'option' ) ) : ?>
                                    <?php while ( have_rows( 'topics_column_one', 'option' ) ) : the_row(); ?>
                                        <?php if ( have_rows( 'group' ) ) : ?>
                                            <li class="dropDown topics">
                                            <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                <?php $title = get_sub_field('title'); ?>
                                                <a href="#"><?php echo $title; ?></a>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <ul class="sub-menu">
                                                            <li class="grandparent">Topics</li>
                                                            <li class="parent"><?php echo $title; ?></li>
                                                            <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                <?php $topic_link_term = get_sub_field( 'topic_link' ); ?>
                                                                <?php if ( $topic_link_term ): ?>

                                                                    <li>
                                                                        <a href="<?php echo get_term_link($topic_link_term); ?>"><?php echo $topic_link_term->name; ?></a>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endwhile; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                                <?php if ( have_rows( 'topics_column_two', 'option' ) ) : ?>
                                    <?php while ( have_rows( 'topics_column_two', 'option' ) ) : the_row(); ?>
                                        <?php if ( have_rows( 'group' ) ) : ?>
                                            <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                <li class="dropDown topics">
                                                    <?php $title = get_sub_field('title'); ?>
                                                    <a href="#"><?php echo $title; ?></a>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <ul class="sub-menu">
                                                            <li class="grandparent">Topics</li>
                                                            <li class="parent"><?php echo $title; ?></li>
                                                            <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                    <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                     <?php if ($type_link_term->slug === 'state-nation') { ?>
                                                                        <?php if ($membershipType == 'it-pro') { ?>
                                                                                <li>
                                                                                <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                                </li>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <?php if ( $type_link_term ): ?>
                                                                            <?php if ($type_link_term->slug === 'community-interviews' && $advantageType === 'yes') {
                                                                                $type_link_term->name = 'Voice of Customers';
                                                                            } ?>
                                                                            <li>
                                                                                <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                            </li>                                                                        
                                                                    <?php endif; ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                    <?php if ( $other_link ): ?>
                                                                        <li>
                                                                            <a href="<?php echo get_sub_field( 'other_link' ); ?>" ><?php echo $other_link; ?></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                <?php } ?>
                                                            <?php endwhile; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                                <?php if ( have_rows( 'topics_column_three', 'option' ) ) : ?>
                                    <?php while ( have_rows( 'topics_column_three', 'option' ) ) : the_row(); ?>
                                        <?php if ( have_rows( 'group' ) ) : ?>
                                            <li class="dropDown topics">
                                            <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                <?php $title = get_sub_field('title'); ?>
                                                <a href="#"><?php echo $title; ?></a>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <ul class="sub-menu">
                                                            <li class="grandparent">Topics</li>
                                                            <li class="parent"><?php echo $title; ?></li>
                                                            <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                <?php if ( get_sub_field( 'type' ) == 'data'){ ?>
                                                                    <?php if ($membershipType == 'it-pro') { ?>
                                                                        <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                            <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                            <?php if ( $type_link_term ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php } else { ?>
                                                                            <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                            <?php if ( $other_link ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_sub_field( 'other_link' ); ?>" ><?php echo $other_link; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } else if ( get_sub_field( 'type' ) == 'market') { ?>
                                                                    <?php if ($membershipType == 'advantage') { ?>
                                                                        <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                            <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                            <?php if ( $type_link_term ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php } else { ?>
                                                                            <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                            <?php if ( $other_link ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_sub_field( 'other_link' ); ?>" ><?php echo $other_link; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } else { ?> 
                                                                    <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                        <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                        <?php if ( $type_link_term ): ?>
                                                                            <li>
                                                                                <a href="<?php echo get_term_link($type_link_term); ?>" ><?php echo $type_link_term->name; ?></a>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                    <?php } else { ?>
                                                                        <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                        <?php if ( $other_link ): ?>
                                                                            <li>
                                                                                <a href="<?php echo get_sub_field( 'other_link' ); ?>" ><?php echo $other_link; ?></a>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                    <?php } ?>
                                                                <?php } ?>                                                                
                                                            <?php endwhile; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php if ($membershipType == 'it-pro') { ?>
                            <li>
                                <a href="/ecosystem-partners" target="_self">Ecosystem Partners</a>
                            </li>
                            <!-- <li>
                                <a href="/filter-types/case-studies/">Case Studies</a>
                            </li> -->
                        <?php } ?>                             
                        <!-- <li>
                            <a href="/interactive-dashboards/">Interactive Dashboards</a>
                        </li> -->
                        <?php if ($membershipType == 'advantage') { ?>
                            <li>
                                <a href="/market-narratives/">Market Narratives</a>
                            </li> 
                            <li>
                                <a href="/sector-outlooks/" target="_self">Sector Outlooks</a>
                            </li>
                            <li>
                                <a href="/persona-profiles/" target="_self">Persona Profiles</a>
                            </li>   
                             <li>
                                <a href="/community/">Community</a>
                            </li>                                              
                        <?php } else { ?>
                            <li>
                                <a href="/community/">Community</a>
                            </li>
                        <?php } ?>
                        <li class="dropDown events">
                            <a href="#">Events</a>
                            <?php if ( have_rows( 'events_menu', 'option' ) ) : ?>
                                <ul class="sub-menu">
                                    <?php while ( have_rows( 'events_menu', 'option' ) ) : the_row(); ?>
                                        <li class="parent">Events</li>
                                        <?php if ( have_rows( 'link' ) ) : ?>
                                            <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                <li>
                                                    <a href="<?php echo get_sub_field( 'link' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                </li>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </ul>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </li>
                        <li>
                            <a href="/whats-new/">What's New</a>
                        </li>
                    </ul>
                <?php } ?>
			</span>
		</div>
	</nav>
</header>

<?php if ( is_page_template( 'templates/template-insights.php' ) || is_page_template ( 'templates/template-events.php' ) ||  is_page_template( 'templates/template-agenda.php' ) ) { ?>
	<div class="formPopup mfp-hide" id="form">
		<a class="popup-modal-dismiss"></a>
		<?php if ( get_field ( 'form_title', 'option' ) ) { ?>
			<h2><?php echo get_field( 'form_title', 'option' ); ?></h2>
		<?php } ?>
		<?php if ( get_field ( 'form_subtitle', 'option' ) ) { ?>
			<h3><?php echo get_field( 'form_subtitle', 'option' ); ?></h3>
		<?php } ?>
		<?php if ( get_field ( 'form_shortcode', 'option' ) ) { ?>
			<div class="formWrapper register"><?php echo get_field( 'form_shortcode', 'option' ); ?></div>
		<?php } ?>
	</div>
<?php } ?>
