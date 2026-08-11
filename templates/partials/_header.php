
<?php 
global $current_user, $first_name, $last_name, $user_email, $membershipType, $advantageType, $member;


// Get user memberships using the function, if available
// Initiate the MeprUser class
// $member = new MeprUser();
// $member->ID = $current_user->ID; // Set user ID to the member's ID
// Get the active subscriptions for this user
if ($member) {
    $active_subscriptions = $member->active_product_subscriptions('ids');

    // Initialize an array to hold the subscription IDs
    $subscription_ids = [];

    foreach ($active_subscriptions as $subscription) {
        $subscription_ids[] = $subscription;
    }
}

if (user_can($current_user, 'administrator')) {
    // Admin can access all memberships, so we directly check if the user has any of the membership IDs
    if (in_array(9811, $subscription_ids)) {
        $membershipType = 'it-pro';
    } elseif (in_array(41272, $subscription_ids)) {
        $membershipType = 'advantage';
    }
} 

$user = wp_get_current_user();
$is_agent_tester = in_array( 'agent_tester', (array) $user->roles, true ) || current_user_can('administrator');


?>
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
                    <?php if ( get_field( 'logo', 'options') ) { ?>
                    <?php 
                    $attachment_id = attachment_url_to_postid( get_field( 'logo', 'options') );

                    if ( $attachment_id ) {
                        echo wp_get_attachment_image(
                            $attachment_id,
                            'medium', // or 'large', 'medium', custom image size, etc.
                            false,
                            array(
                                'alt'   => 'Adapt',
                            )
                        );
                    } else {
                        // Fallback if the URL is not found in the media library.
                        ?>
                         <?php
					$inline_img_158_src = get_field( 'logo', 'options' );
					$inline_img_158_attach_id = $inline_img_158_src ? attachment_url_to_postid( $inline_img_158_src ) : 0;
					if ( $inline_img_158_attach_id ) {
						echo wp_get_attachment_image( $inline_img_158_attach_id, 'full', false, array( 'alt' => 'Adapt', 'width' => '300' ) );
					} elseif ( $inline_img_158_src ) {
						echo '<img src="' . esc_url( $inline_img_158_src ) . '" loading="lazy" alt="' . esc_attr( 'Adapt' ) . '" />';
					}
				?>
                        <?php
                    }
                    ?>
                      
                    <?php } ?>
				</a>
			</span>
			<span class="headerTopRight">
                <?php if( $is_agent_tester ) : ?>
                <span class="customgpt-toggle desktop">
                    <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M25.5042 22.3373C25.5051 22.4946 25.4573 22.6483 25.3673 22.7773C25.2773 22.9064 25.1496 23.0043 25.0016 23.0577L22.5138 23.9768L21.5974 26.4661C21.5431 26.6135 21.4449 26.7407 21.3161 26.8305C21.1872 26.9204 21.0339 26.9686 20.8768 26.9686C20.7197 26.9686 20.5664 26.9204 20.4375 26.8305C20.3087 26.7407 20.2105 26.6135 20.1562 26.4661L19.234 23.9768L16.7443 23.0606C16.5969 23.0064 16.4696 22.9082 16.3798 22.7794C16.2899 22.6506 16.2417 22.4973 16.2417 22.3402C16.2417 22.1831 16.2899 22.0298 16.3798 21.901C16.4696 21.7722 16.5969 21.674 16.7443 21.6198L19.234 20.6978L20.1504 18.2086C20.2047 18.0612 20.3029 17.9339 20.4317 17.8441C20.5606 17.7542 20.7139 17.7061 20.871 17.7061C21.0281 17.7061 21.1814 17.7542 21.3103 17.8441C21.4391 17.9339 21.5373 18.0612 21.5916 18.2086L22.5138 20.6978L25.0035 21.614C25.1516 21.6679 25.2793 21.7664 25.369 21.896C25.4586 22.0256 25.5059 22.1798 25.5042 22.3373Z" fill="#E7534F"/>
                        <path d="M19.3335 16.4839L18.0786 19.6499H11.7075L10.1782 24.2964H8.0083L13.9751 6.8999H16.0454L19.3335 16.4839ZM12.397 17.561H17.6255L15.0103 9.73389L12.397 17.561Z" fill="white"/>
                    </svg>
                    <!-- <span>Ask AI</span> -->
                </span>
                <?php endif; ?>
                <span class="search">
                    <span class="search-toggle"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/search-icon.svg" width="16" height="16" loading="lazy" alt="search icon" /></span>
					<form action="/" method="get">
						<input class="searchInput" type="text" name="s" id="search" aria-label="Search" placeholder="Search..." value="" />
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
                <a class="favourites" href="/saved-insights" aria-label="Saved insights">
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
                                                    <?php if(get_sub_field( 'link_type' ) == 'advantage' ){ ?>
                                                        <?php if ($membershipType == 'advantage') { ?>
                                                            <?php if(get_sub_field( 'link' ) !== '' ){ ?>                                                            
                                                                <a class="userMenuLink" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                            <?php } else { ?> 
                                                                <span class="userMenuLink no-link"><?php echo get_sub_field( 'link_text' ); ?></span>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } else if(get_sub_field( 'link_type' ) == 'it-pro' ){ ?>
                                                        <?php if ($membershipType == 'it-pro') { ?>
                                                            <?php if(get_sub_field( 'link' ) !== '' ){ ?>
                                                                <a class="userMenuLink" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                            <?php } else { ?> 
                                                                <span class="userMenuLink no-link"><?php echo get_sub_field( 'link_text' ); ?></span>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } else { ?> 
                                                        <?php if(get_sub_field( 'link' ) !== '' ){ ?> 
                                                            <a class="userMenuLink" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } else { ?> 
                                                            <span class="userMenuLink no-link"><?php echo get_sub_field( 'link_text' ); ?></span>
                                                        <?php } ?>
                                                    <?php } ?>                                                    
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
                                            <?php $userLinkCounter = 1 ?>
                                			<?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                <?php if(get_sub_field('link_type') == 'form-popup'){ ?> 
                                                    <a class="userMenuLink formPopupHubspot <?php echo get_sub_field( 'link_class' ); ?>" href="#userForm<?php echo $userLinkCounter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                    <div style="display: none;">         
                                                        <div class="preview-cta-form login-form-container" id="userForm<?php echo $userLinkCounter; ?>">
                                                            <span class="form-container"><?php echo get_sub_field( 'form_embed_code' ); ?></span>
                                                        </div>
                                                    </div>                                                
                                                <?php } else { ?> 
                                                    <a class="userMenuLink <?php echo get_sub_field( 'link_class' ); ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                <?php } ?>
                                                <?php $userLinkCounter++ ?>
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
                        <?php if ($membershipType == 'it-pro') { ?>
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
                                                                <ul>
                                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                            <?php $topic_link_term = get_sub_field( 'topic_link' ); ?>
                                                                            <?php if ( $topic_link_term ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_term_link($topic_link_term); ?>"><?php echo $topic_link_term->name; ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php endwhile; ?>
                                                                    <?php else : ?>
                                                                        <?php // no rows found ?>
                                                                    <?php endif; ?>
                                                                    <?php if(get_sub_field('view_all_link')){ ?>
                                                                        <span class="link-container">
                                                                            <a href="<?php echo get_sub_field('view_all_link'); ?>" target="_self" class="text-link red-text-link uppercase arrow-link">View all</a>
                                                                        </span> 
                                                                    <?php } ?>
                                                                </ul>
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
                                                                    <?php echo get_sub_field( 'title' ); ?>
                                                                </span>
                                                                <ul>
                                                                    <?php if ( have_rows( 'link' ) ) : ?>                                                                    
                                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                            <?php $icon = get_sub_field( 'icon' ); ?>						
                                                                            <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                                <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                                <?php if ( $type_link_term ): ?>                                                                                    
                                                                                    <li>
                                                                                        <a href="<?php echo get_term_link($type_link_term); ?>" >
                                                                                            <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $type_link_term->name; ?></span>
                                                                                        </a>
                                                                                    </li>                                                                                                                                                                    
                                                                                <?php endif; ?>
                                                                            <?php } else { ?>
                                                                                <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                                <?php if ( $other_link ): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_sub_field( 'other_link' ); ?>" >
                                                                                             <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $other_link; ?></span>
                                                                                        </a>
                                                                                    </li>
                                                                                <?php endif; ?>
                                                                            <?php } ?>
                                                                        <?php endwhile; ?>
                                                                    <?php else : ?>
                                                                        <?php // no rows found ?>
                                                                    <?php endif; ?>
                                                                    <?php if(get_sub_field('view_all_link')){ ?>
                                                                        <span class="link-container">
                                                                            <a href="<?php echo get_sub_field('view_all_link'); ?>" target="_self" class="text-link red-text-link uppercase arrow-link">View all</a>
                                                                        </span> 
                                                                    <?php } ?>
                                                                </ul>
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
                                        <?php if ( have_rows( 'topics_column_three', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'topics_column_three', 'option' ) ) : the_row(); ?>
                                                <div class="column third">
                                                    <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                                    <?php if ( $post_object ): ?>
                                                        <?php $post = $post_object; ?>
                                                        <?php setup_postdata( $post ); ?> 
                                                        <a href="<?php the_permalink(); ?>">
                                                            <span class="menu-featured-post">
                                                                <span class="image-container">
                                                                    <?php
                                                                    $image = null;
                                                                    $video = 'no';
                                                                    if ( has_term('replay-post', 'replay') ) { 
                                                                        if(get_field('video_image')) {
                                                                            $image = get_field('video_image');
                                                                            $video = 'yes';
                                                                        } else {
                                                                            $image = get_field('featured_image');
                                                                        } 
                                                                    } else {
                                                                        if (get_field('listing_image')) {
                                                                            $image = get_field('listing_image');                        
                                                                        } else {
                                                                            if (get_field('featured_image_or_video') === 'video') {
                                                                                $video = 'yes';
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                } else {
                                                                                    $image = get_field('featured_image');
                                                                                }                                                      
                                                                            } else {
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                
                                                                                } else {
                                                                                    $image = get_field('featured_image');                                                                            
                                                                                }  
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                    ?>
                                                                    <?php if ($image) : ?>
                                                                        <span class="bg-container">
                                                                            <?php
					$inline_img_159_src = $image;
					$inline_img_159_attach_id = $inline_img_159_src ? attachment_url_to_postid( $inline_img_159_src ) : 0;
					if ( $inline_img_159_attach_id ) {
						echo wp_get_attachment_image( $inline_img_159_attach_id, 'article-card', false, array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' ) );
					} elseif ( $inline_img_159_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_159_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                            <?php if($video == 'yes'){ ?>
                                                                                <span class="video-icon"></span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <span class="text-container">
                                                                    <span class="labelSmall text-bold text-black"><?php the_title(); ?></span>
                                                                    <span class="link-container">
                                                                        <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </a>                                                            
                                                        <?php wp_reset_postdata(); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ($membershipType == 'advantage' || current_user_can('administrator')) { ?>
                            <li class="dropdown topics-menu">
                                <a>Research</a>
                                <div class="megaMenu topicsMenu" id="topics">
                                    <!-- <span class="mobile-menu-title">Research & Advisory</span> -->
                                    <div class="container">
                                        <?php if ( have_rows( 'market_insights_column_one', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'market_insights_column_one', 'option' ) ) : the_row(); ?>
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
                                                                        <?php if(get_sub_field('view_all_link')){ ?>
                                                                            <span class="link-container">
                                                                                <a href="<?php echo get_sub_field('view_all_link'); ?>" target="_self" class="text-link red-text-link uppercase arrow-link">View all</a>
                                                                            </span> 
                                                                        <?php } ?>
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
                                        <?php if ( have_rows( 'market_insights_column_two', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'market_insights_column_two', 'option' ) ) : the_row(); ?>
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
                                                                <ul>
                                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                            <?php $icon = get_sub_field( 'icon' ); ?>						
                                                                            <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                                <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                                <?php if ( $type_link_term ): ?>                                                                                    
                                                                                    <li>
                                                                                        <a href="<?php echo get_term_link($type_link_term); ?>" >
                                                                                            <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $type_link_term->name; ?></span>
                                                                                        </a>
                                                                                    </li>                                                                                                                                                                    
                                                                                <?php endif; ?>
                                                                            <?php } else { ?>
                                                                                <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                                <?php if ( $other_link ): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_sub_field( 'other_link' ); ?>" >
                                                                                                <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $other_link; ?></span>
                                                                                        </a>
                                                                                    </li>
                                                                                <?php endif; ?>
                                                                            <?php } ?>
                                                                        <?php endwhile; ?>
                                                                    <?php else : ?>
                                                                        <?php // no rows found ?>
                                                                    <?php endif; ?>
                                                                    <?php if(get_sub_field('view_all_link')){ ?>
                                                                        <span class="link-container">
                                                                            <a href="<?php echo get_sub_field('view_all_link'); ?>" target="_self" class="text-link red-text-link uppercase arrow-link">View all</a>
                                                                        </span> 
                                                                    <?php } ?>
                                                                </ul>
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
                                        <?php if ( have_rows( 'market_insights_column_three', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'market_insights_column_three', 'option' ) ) : the_row(); ?>
                                                <div class="column third">
                                                    <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                                    <?php if ( $post_object ): ?>
                                                        <?php $post = $post_object; ?>
                                                        <?php setup_postdata( $post ); ?> 
                                                        <a href="<?php the_permalink(); ?>">
                                                            <span class="menu-featured-post">
                                                                <span class="image-container">
                                                                    <?php
                                                                    $image = null;
                                                                    $video = 'no';
                                                                    if ( has_term('replay-post', 'replay') ) { 
                                                                        if(get_field('video_image')) {
                                                                            $image = get_field('video_image');
                                                                            $video = 'yes';
                                                                        } else {
                                                                            $image = get_field('featured_image');
                                                                        } 
                                                                    } else {
                                                                        if (get_field('listing_image')) {
                                                                            $image = get_field('listing_image');                        
                                                                        } else {
                                                                            if (get_field('featured_image_or_video') === 'video') {
                                                                                $video = 'yes';
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                } else {
                                                                                    $image = get_field('featured_image');
                                                                                }                                                      
                                                                            } else {
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                
                                                                                } else {
                                                                                    $image = get_field('featured_image');                                                                            
                                                                                }  
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                    ?>
                                                                    <?php if ($image) : ?>
                                                                        <span class="bg-container">
                                                                            <?php
					$inline_img_160_src = $image;
					$inline_img_160_attach_id = $inline_img_160_src ? attachment_url_to_postid( $inline_img_160_src ) : 0;
					if ( $inline_img_160_attach_id ) {
						echo wp_get_attachment_image( $inline_img_160_attach_id, 'article-card', false, array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' ) );
					} elseif ( $inline_img_160_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_160_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                            <?php if($video == 'yes'){ ?>
                                                                                <span class="video-icon"></span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <span class="text-container">
                                                                    <span class="labelSmall text-bold text-black"><?php the_title(); ?></span>
                                                                    <span class="link-container">
                                                                        <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </a>                                                            
                                                        <?php wp_reset_postdata(); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                            <li class="no-dropdown">
                                <a href="/filter-types/market-narratives/">Market Narratives</a>
                            </li>
                            <li class="dropdown topics-menu">
                                <a>Personas</a>
                                <div class="megaMenu topicsMenu personasMenu" id="personas">
                                    <!-- <span class="mobile-menu-title">Research & Advisory</span> -->
                                    <div class="container">
                                        <?php if ( have_rows( 'personas_column_one', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'personas_column_one', 'option' ) ) : the_row(); ?>
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
                                                                            <li>                                                                                				
                                                                                <a href="<?php echo get_sub_field( 'persona_link' ); ?>"><?php echo get_sub_field( 'persona_link_text' ); ?></a>
                                                                            </li>                                                                            
                                                                        <?php endwhile; ?>
                                                                        <?php if(get_sub_field('view_all_link')){ ?>
                                                                            <span class="link-container">
                                                                                <a href="<?php echo get_sub_field('view_all_link'); ?>" target="_self" class="text-link red-text-link uppercase arrow-link">View all</a>
                                                                            </span> 
                                                                        <?php } ?>
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
                                        <?php if ( have_rows( 'personas_column_two', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'personas_column_two', 'option' ) ) : the_row(); ?>
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
                                                                            <?php $icon = get_sub_field( 'icon' ); ?>						
                                                                            <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                                <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                                <?php if ( $type_link_term ): ?>                                                                                    
                                                                                    <li>
                                                                                        <a href="<?php echo get_term_link($type_link_term); ?>" >
                                                                                            <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $type_link_term->name; ?></span>
                                                                                        </a>
                                                                                    </li>                                                                                                                                                                    
                                                                                <?php endif; ?>
                                                                            <?php } else { ?>
                                                                                <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                                <?php if ( $other_link ): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_sub_field( 'other_link' ); ?>" >
                                                                                             <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $other_link; ?></span>
                                                                                        </a>
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
                                        <?php if ( have_rows( 'personas_column_three', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'personas_column_three', 'option' ) ) : the_row(); ?>
                                                <div class="column third">
                                                    <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                                    <?php if ( $post_object ): ?>
                                                        <?php $post = $post_object; ?>
                                                        <?php setup_postdata( $post ); ?> 
                                                        <a href="<?php the_permalink(); ?>">
                                                            <span class="menu-featured-post">
                                                                <span class="image-container">
                                                                    <?php
                                                                    $image = null;
                                                                    $video = 'no';
                                                                    if ( has_term('replay-post', 'replay') ) { 
                                                                        if(get_field('video_image')) {
                                                                            $image = get_field('video_image');
                                                                            $video = 'yes';
                                                                        } else {
                                                                            $image = get_field('featured_image');
                                                                        } 
                                                                    } else {
                                                                        if (get_field('listing_image')) {
                                                                            $image = get_field('listing_image');                        
                                                                        } else {
                                                                            if (get_field('featured_image_or_video') === 'video') {
                                                                                $video = 'yes';
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                } else {
                                                                                    $image = get_field('featured_image');
                                                                                }                                                      
                                                                            } else {
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                
                                                                                } else {
                                                                                    $image = get_field('featured_image');                                                                            
                                                                                }  
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                    ?>
                                                                    <?php if ($image) : ?>
                                                                        <span class="bg-container">
                                                                            <?php
					$inline_img_161_src = $image;
					$inline_img_161_attach_id = $inline_img_161_src ? attachment_url_to_postid( $inline_img_161_src ) : 0;
					if ( $inline_img_161_attach_id ) {
						echo wp_get_attachment_image( $inline_img_161_attach_id, 'article-card', false, array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' ) );
					} elseif ( $inline_img_161_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_161_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                            <?php if($video == 'yes'){ ?>
                                                                                <span class="video-icon"></span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <span class="text-container">
                                                                    <span class="labelSmall text-bold text-black"><?php the_title(); ?></span>
                                                                    <span class="link-container">
                                                                        <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </a>                                                            
                                                        <?php wp_reset_postdata(); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown topics-menu">
                                <a>Sectors</a>
                                <div class="megaMenu topicsMenu sectorsMenu" id="sectors">
                                    <!-- <span class="mobile-menu-title">Research & Advisory</span> -->
                                    <div class="container">
                                        <?php if ( have_rows( 'sectors_column_one', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'sectors_column_one', 'option' ) ) : the_row(); ?>
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
                                                                            <li>                                                                                				
                                                                                <a href="<?php echo get_sub_field( 'sector_link' ); ?>"><?php echo get_sub_field( 'sector_link_text' ); ?></a>
                                                                            </li> 
                                                                        <?php endwhile; ?>
                                                                        <?php if(get_sub_field('view_all_link')){ ?>
                                                                        <span class="link-container">
                                                                            <a href="<?php echo get_sub_field('view_all_link'); ?>" target="_self" class="text-link red-text-link uppercase arrow-link">View all</a>
                                                                        </span> 
                                                                <?php } ?>
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
                                        <?php if ( have_rows( 'sectors_column_two', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'sectors_column_two', 'option' ) ) : the_row(); ?>
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
                                                                            <?php $icon = get_sub_field( 'icon' ); ?>						
                                                                            <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                                <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                                <?php if ( $type_link_term ): ?>                                                                                    
                                                                                    <li>
                                                                                        <a href="<?php echo get_term_link($type_link_term); ?>" >
                                                                                            <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $type_link_term->name; ?></span>
                                                                                        </a>
                                                                                    </li>                                                                                                                                                                    
                                                                                <?php endif; ?>
                                                                            <?php } else { ?>
                                                                                <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                                <?php if ( $other_link ): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_sub_field( 'other_link' ); ?>" >
                                                                                             <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $other_link; ?></span>
                                                                                        </a>
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
                                        <?php if ( have_rows( 'sectors_column_three', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'sectors_column_three', 'option' ) ) : the_row(); ?>
                                                <div class="column third">
                                                    <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                                    <?php if ( $post_object ): ?>
                                                        <?php $post = $post_object; ?>
                                                        <?php setup_postdata( $post ); ?> 
                                                        <a href="<?php the_permalink(); ?>">
                                                            <span class="menu-featured-post">
                                                                <span class="image-container">
                                                                    <?php
                                                                    $image = null;
                                                                    $video = 'no';
                                                                    if ( has_term('replay-post', 'replay') ) { 
                                                                        if(get_field('video_image')) {
                                                                            $image = get_field('video_image');
                                                                            $video = 'yes';
                                                                        } else {
                                                                            $image = get_field('featured_image');
                                                                        } 
                                                                    } else {
                                                                        if (get_field('listing_image')) {
                                                                            $image = get_field('listing_image');                        
                                                                        } else {
                                                                            if (get_field('featured_image_or_video') === 'video') {
                                                                                $video = 'yes';
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                } else {
                                                                                    $image = get_field('featured_image');
                                                                                }                                                      
                                                                            } else {
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                
                                                                                } else {
                                                                                    $image = get_field('featured_image');                                                                            
                                                                                }  
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                    ?>
                                                                    <?php if ($image) : ?>
                                                                        <span class="bg-container">
                                                                            <?php
					$inline_img_162_src = $image;
					$inline_img_162_attach_id = $inline_img_162_src ? attachment_url_to_postid( $inline_img_162_src ) : 0;
					if ( $inline_img_162_attach_id ) {
						echo wp_get_attachment_image( $inline_img_162_attach_id, 'article-card', false, array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' ) );
					} elseif ( $inline_img_162_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_162_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                            <?php if($video == 'yes'){ ?>
                                                                                <span class="video-icon"></span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <span class="text-container">
                                                                    <span class="labelSmall text-bold text-black"><?php the_title(); ?></span>
                                                                    <span class="link-container">
                                                                        <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </a>                                                            
                                                        <?php wp_reset_postdata(); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown events-menu">
                                <a>Events</a>
                                <div class="megaMenu eventsMenu it-events" id="events">
                                    <div class="container">
                                        <ul>
                                            <?php if ( have_rows( 'events_menu', 'option' ) ) : ?>                                    
                                                <?php while ( have_rows( 'events_menu', 'option' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                            <li>
                                                                <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                    <span class="icon-container">
                                                                        <?php $icon = get_sub_field( 'icon' ); ?>
                                                                        <?php if ( $icon ) { ?>
                                                                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text">
                                                                        <?php echo get_sub_field( 'link_text' ); ?>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                    <?php if ( have_rows( 'events_calendar' ) ) : ?>
                                                        <span class="events-calendar">                                                            
                                                            <?php while ( have_rows( 'events_calendar' ) ) : the_row(); ?>
                                                                <span class="events-calendar-container">
                                                                    <span class="image-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text-container">
                                                                        <span class="link-title"><?php echo get_sub_field( 'text' ); ?></span>
                                                                        <span class="link-container">
                                                                            <?php if(get_sub_field( 'link_type' ) == 'link'){ ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">Download</a>
                                                                            <?php } else { ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link formPopupHubspot" href="#downloadCalendarLink" target="_self">Download</a>
                                                                                    <span style="display: none;">         
                                                                                        <span class="preview-cta-form login-form-container" id="downloadCalendarLink">
                                                                                            <span class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                            <?php } ?>                                                                                
                                                                        </span> 
                                                                    </span>                                                                   
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>
                                            
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if ($membershipType == 'it-pro' || current_user_can('administrator')) { ?>
                            <?php if ( have_rows( 'community_insights_menu', 'option' ) ) : ?>
                            <li class="dropdown events-menu">
                                <a>Community Insights</a>
                                <div class="megaMenu eventsMenu community-insights" id="insights">
                                    <div class="container">
                                    
                                        <ul>
                                            <?php while ( have_rows( 'community_insights_menu', 'option' ) ) : the_row(); ?>
                                                <?php if ( have_rows( 'link' ) ) : ?>
                                                    <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                        <li>
                                                            <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                <?php $icon = get_sub_field( 'icon' ); ?>
                                                                <span class="icon-container">
                                                                    <?php if ( $icon ) { ?>
                                                                        <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                                <span class="link-text">
                                                                    <span class="link-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                                    <span class="link-text-text"><?php echo get_sub_field( 'text' ); ?></span>
                                                                </span>
                                                            </a>
                                                        </li>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                                <?php if(get_sub_field('view_all_link')){ ?>
                                                    <span class="link-container">
                                                        <a href="<?php echo get_sub_field('view_all_link'); ?>" target="_self" class="text-link red-text-link uppercase arrow-link">View all</a>
                                                    </span> 
                                                <?php } ?>
                                            <?php endwhile; ?>
                                        </ul>
                                    
                                    </div>
                                </div>
                            </li>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                             <li>
                                <a href="/marketplace/">Marketplace</a>
                                <?php if ( have_rows( 'marketplace_menu', 'option' ) ) : ?>
                                <div class="megaMenu eventsMenu marketplace" id="marketplace">
                                    <div class="container">                                        
                                        <ul>
                                            <?php while ( have_rows( 'marketplace_menu', 'option' ) ) : the_row(); ?>
                                                <?php if ( have_rows( 'link' ) ) : ?>
                                                    <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                        <li>
                                                            <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                <?php $icon = get_sub_field( 'icon' ); ?>
                                                                <span class="icon-container">
                                                                    <?php if ( $icon ) { ?>
                                                                        <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                                <span class="link-text">
                                                                    <span class="link-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                                    <span class="link-text-text"><?php echo get_sub_field( 'text' ); ?></span>
                                                                </span>
                                                            </a>
                                                        </li>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            <?php endwhile; ?>
                                        </ul>
                                       
                                    </div>
                                </div>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </li>
                            <li>
                                <a href="/executive-advisors/" target="_self">Executive Advisors</a>
                            </li>                            
                            <li class="dropdown events-menu">
                                <a>Events</a>
                                <div class="megaMenu eventsMenu it-events" id="events">
                                    <div class="container">
                                        <?php if ( have_rows( 'events_menu_it', 'option' ) ) : ?>
                                            <ul>
                                                <?php while ( have_rows( 'events_menu_it', 'option' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                            <li>
                                                                <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                    <?php $icon = get_sub_field( 'icon' ); ?>
                                                                    <span class="icon-container">
                                                                        <?php if ( $icon ) { ?>
                                                                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text">
                                                                        <?php echo get_sub_field( 'link_text' ); ?>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                    <?php if ( have_rows( 'events_calendar' ) ) : ?>
                                                        <span class="events-calendar">                                                            
                                                            <?php while ( have_rows( 'events_calendar' ) ) : the_row(); ?>
                                                                <span class="events-calendar-container">
                                                                    <span class="image-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text-container">
                                                                        <span class="link-title"><?php echo get_sub_field( 'text' ); ?></span>
                                                                        <span class="link-container">
                                                                            <?php if(get_sub_field( 'link_type' ) == 'link'){ ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">Download</a>
                                                                            <?php } else { ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link formPopupHubspot" href="#downloadCalendarLink" target="_self">Download</a>
                                                                                    <span style="display: none;">         
                                                                                        <span class="preview-cta-form login-form-container" id="downloadCalendarLink">
                                                                                            <span class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                            <?php } ?>                                                                                
                                                                        </span> 
                                                                    </span>                                                                   
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </span>
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
                        <?php } ?>                
                        
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
                                                <?php if(get_sub_field( 'link_type' ) == 'advantage' ){ ?>
                                                    <?php if ($membershipType == 'advantage') { ?>
                                                        <?php if(get_sub_field( 'link' ) !== '' ){ ?>                                                            
                                                            <a class="userMenuLink" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } else { ?> 
                                                            <span class="userMenuLink no-link"><?php echo get_sub_field( 'link_text' ); ?></span>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } else if(get_sub_field( 'link_type' ) == 'it-pro' ){ ?>
                                                    <?php if ($membershipType == 'it-pro') { ?>
                                                        <?php if(get_sub_field( 'link' ) !== '' ){ ?>
                                                            <a class="userMenuLink" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                        <?php } else { ?> 
                                                            <span class="userMenuLink no-link"><?php echo get_sub_field( 'link_text' ); ?></span>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } else { ?> 
                                                    <?php if(get_sub_field( 'link' ) !== '' ){ ?> 
                                                        <a class="userMenuLink" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                    <?php } else { ?> 
                                                        <span class="userMenuLink no-link"><?php echo get_sub_field( 'link_text' ); ?></span>
                                                    <?php } ?>
                                                <?php } ?>         
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
                                        <?php $userLinkCounter = 1 ?>
                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                            <?php if(get_sub_field('link_type') == 'form-popup'){ ?> 
                                                <a class="userMenuLink formPopupHubspot <?php echo get_sub_field( 'link_class' ); ?>" href="#userForm<?php echo $userLinkCounter; ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                                <div style="display: none;">         
                                                    <div class="preview-cta-form login-form-container" id="userForm<?php echo $userLinkCounter; ?>">
                                                        <span class="form-container"><?php echo get_sub_field( 'form_embed_code' ); ?></span>
                                                    </div>
                                                </div>
                                            
                                            <?php } else { ?> 
                                                <a class="userMenuLink <?php echo get_sub_field( 'link_class' ); ?>" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                                            <?php } ?>
                                            <?php $userLinkCounter++ ?>
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
						<input class="searchInput" type="text" name="s" id="search-mobile" aria-label="Search" placeholder="Search..." value="" />
					</form>
                    <span class="search-close-mobile"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/search-close-icon.svg" width="12" height="12" loading="lazy" alt="Close search" /></span>
				</span>
                <?php if( $is_agent_tester ) : ?>
                <span class="customgpt-toggle mobile">
                    <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M25.5042 22.3373C25.5051 22.4946 25.4573 22.6483 25.3673 22.7773C25.2773 22.9064 25.1496 23.0043 25.0016 23.0577L22.5138 23.9768L21.5974 26.4661C21.5431 26.6135 21.4449 26.7407 21.3161 26.8305C21.1872 26.9204 21.0339 26.9686 20.8768 26.9686C20.7197 26.9686 20.5664 26.9204 20.4375 26.8305C20.3087 26.7407 20.2105 26.6135 20.1562 26.4661L19.234 23.9768L16.7443 23.0606C16.5969 23.0064 16.4696 22.9082 16.3798 22.7794C16.2899 22.6506 16.2417 22.4973 16.2417 22.3402C16.2417 22.1831 16.2899 22.0298 16.3798 21.901C16.4696 21.7722 16.5969 21.674 16.7443 21.6198L19.234 20.6978L20.1504 18.2086C20.2047 18.0612 20.3029 17.9339 20.4317 17.8441C20.5606 17.7542 20.7139 17.7061 20.871 17.7061C21.0281 17.7061 21.1814 17.7542 21.3103 17.8441C21.4391 17.9339 21.5373 18.0612 21.5916 18.2086L22.5138 20.6978L25.0035 21.614C25.1516 21.6679 25.2793 21.7664 25.369 21.896C25.4586 22.0256 25.5059 22.1798 25.5042 22.3373Z" fill="#E7534F"/>
                        <path d="M19.3335 16.4839L18.0786 19.6499H11.7075L10.1782 24.2964H8.0083L13.9751 6.8999H16.0454L19.3335 16.4839ZM12.397 17.561H17.6255L15.0103 9.73389L12.397 17.561Z" fill="white"/>
                    </svg>
                    <!-- <span>Ask AI</span> -->
                </span>
                <?php endif; ?>
            <?php } ?>

			</span>
		</div>
        <?php if ( is_single() ) { ?>
            <?php 
            $videoType = 'no';
            if ( get_field ( 'featured_image_or_video' ) == 'video' ) { 
                $videoType = 'yes';
            } 
            ?>
			<div class="single-post-sticky <?php if($videoType == 'yes'){ ?>bg-black<?php } else { ?>bg-white<?php } ?>">
				<div class="container">					
					<span class="title-container">
						<span class="labelSmall"><?php the_title(); ?></span>
					</span>
					<span class="right-container">
						<span class="share-container">
							<span class="share-title">Share</span>
							<span class="share-links-container">
								<span class="copy-link share">
		                            <input type="text" value="<?php echo the_permalink(); ?>" id="postLink" style="display: none;">
		                            <a onclick="copyJobLink()">
										<span class="image-icon-container">
		                                    <img class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link.svg" width="33" height="32" loading="lazy" alt="Copy link" />
		                                    <img class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/copy-link-hover.svg" width="24" height="24" loading="lazy" alt="Copy link" />
											<span class="job-link-text"></span>
										</span>
		                            </a>
		                        </span>
		                        <script>
		                            function copyJobLink() {
		                        		// Get the text field
		                        		var copyText = document.getElementById("postLink");

		                        		// Select the text field
		                        		copyText.select();
		                        		copyText.setSelectionRange(0, 99999); // For mobile devices

		                        		// Copy the text inside the text field
		                        		navigator.clipboard.writeText(copyText.value);
		                                jQuery('.copy-link .job-link-text').html('Copied');
		                                jQuery('.copy-link .job-link-text').addClass('text-red');
		                        	}
		                        </script>
								<span class="share-linked-in share">
									<a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&summary=<?php the_excerpt(); ?>" target="_blank">
										<span class="image-icon-container">
	                                        <img class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-black.svg" width="24" height="24" loading="lazy" alt="Share on LinkedIn" />
											<img class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/linked-in-hover.svg" width="24" height="24" loading="lazy" alt="Share on LinkedIn" />
										</span>
									</a>
								</span>
								<span class="share-twitter share">
									<a class="twitterShare" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&text=<?php the_excerpt(); ?>" target="_blank">
										<span class="image-icon-container">
	                                        <img class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-black.svg" width="24" height="24" loading="lazy" alt="Tweet" />
											<img class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-hover.svg" width="24" height="24" loading="lazy" alt="Tweet" />
										</span>
									</a>
								</span>
								<span class="share-email share">
									<a class="emailShare" href="mailto:?&subject=<?php the_title(); ?>&body=<?php echo the_permalink(); ?>" target="_blank">
										<span class="image-icon-container">
                                        <img class="standard" src="<?php echo get_template_directory_uri(); ?>/assets/images/job-email.svg" width="32" height="32" loading="lazy" alt="Share via Email" />
                                        <img class="hover" src="<?php echo get_template_directory_uri(); ?>/assets/images/email-red-hover.svg" width="24" height="24" loading="lazy" alt="Email" />
									</a>
								</span>
							</span>
						</span>
					</span>
				</div>
				<div class="progress-container">
				  <span class="progress-bar"></span>
				</div>
			</div>
		<?php } ?>
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
                    <?php if ($membershipType == 'it-pro') { ?>
                        <li class="dropDown research with-sub-menu">
                            <a href="#">Research</a>
                            <ul class="sub-menu">
                                <li class="parent">Research</li>
                                <span class="sub-menu-inner">
                                    <?php if ( have_rows( 'topics_column_one', 'option' ) ) : ?>
                                        <?php while ( have_rows( 'topics_column_one', 'option' ) ) : the_row(); ?>
                                            <div class="column full">
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
                                            <div class="column full">
                                                <?php if ( have_rows( 'group' ) ) : ?>
                                                    <?php while ( have_rows( 'group' ) ) : the_row(); ?>
                                                        <span class="dropDownSection">
                                                            <?php $icon = get_sub_field( 'icon' ); ?>
                                                            <span class="columnTitle">                                                                    
                                                                <?php echo get_sub_field( 'title' ); ?>
                                                            </span>
                                                            <?php if ( have_rows( 'link' ) ) : ?>
                                                                <ul>
                                                                    <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                                        <?php $icon = get_sub_field( 'icon' ); ?>						
                                                                        <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                            <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                            <?php if ( $type_link_term ): ?>                                                                                    
                                                                                <li>
                                                                                    <a href="<?php echo get_term_link($type_link_term); ?>" >
                                                                                        <span class="icon-container">
                                                                                            <?php if ( $icon ) { ?>
                                                                                                <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                            <?php } ?>
                                                                                        </span>
                                                                                        <span class="link-text text-black"><?php echo $type_link_term->name; ?></span>
                                                                                    </a>
                                                                                </li>                                                                                                                                                                    
                                                                            <?php endif; ?>
                                                                        <?php } else { ?>
                                                                            <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                            <?php if ( $other_link ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_sub_field( 'other_link' ); ?>" >
                                                                                            <span class="icon-container">
                                                                                            <?php if ( $icon ) { ?>
                                                                                                <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                            <?php } ?>
                                                                                        </span>
                                                                                        <span class="link-text text-black"><?php echo $other_link; ?></span>
                                                                                    </a>
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
                                            <div class="column full">
                                                <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                                <?php if ( $post_object ): ?>
                                                    <?php $post = $post_object; ?>
                                                    <?php setup_postdata( $post ); ?> 
                                                    <a href="<?php the_permalink(); ?>">
                                                        <span class="menu-featured-post">
                                                            <span class="image-container">
                                                                <?php
                                                                $image = null;
                                                                $video = 'no';
                                                                if ( has_term('replay-post', 'replay') ) { 
                                                                    if(get_field('video_image')) {
                                                                        $image = get_field('video_image');
                                                                        $video = 'yes';
                                                                    } else {
                                                                        $image = get_field('featured_image');
                                                                    } 
                                                                } else {
                                                                    if (get_field('listing_image')) {
                                                                        $image = get_field('listing_image');                        
                                                                    } else {
                                                                        if (get_field('featured_image_or_video') === 'video') {
                                                                            $video = 'yes';
                                                                            if(get_field('video_poster')){
                                                                                $image = get_field('video_poster');
                                                                            } else if(get_field('video_image')) {
                                                                                $image = get_field('video_image');
                                                                            } else {
                                                                                $image = get_field('featured_image');
                                                                            }                                                      
                                                                        } else {
                                                                            if(get_field('video_poster')){
                                                                                $image = get_field('video_poster');
                                                                            } else if(get_field('video_image')) {
                                                                                $image = get_field('video_image');
                                                                            
                                                                            } else {
                                                                                $image = get_field('featured_image');                                                                            
                                                                            }  
                                                                        }
                                                                    }
                                                                }
                                                                
                                                                ?>
                                                                <?php if ($image) : ?>
                                                                    <span class="bg-container">
                                                                        <?php
					$inline_img_163_src = $image;
					$inline_img_163_attach_id = $inline_img_163_src ? attachment_url_to_postid( $inline_img_163_src ) : 0;
					if ( $inline_img_163_attach_id ) {
						echo wp_get_attachment_image( $inline_img_163_attach_id, 'article-card', false, array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' ) );
					} elseif ( $inline_img_163_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_163_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                        <?php if($video == 'yes'){ ?>
                                                                            <span class="video-icon"></span>
                                                                        <?php } ?>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </span>
                                                            <span class="text-container">
                                                                <span class="labelSmall text-bold text-black"><?php the_title(); ?></span>
                                                                <span class="link-container">
                                                                    <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </a>                                                            
                                                    <?php wp_reset_postdata(); ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </span>
                            </ul>
                        </li>
                        <?php if ( have_rows( 'community_insights_menu', 'option' ) ) : ?>
                            <li class="dropDown with-sub-menu">
                                <a href="#">Community Insights</a>
                                <ul class="sub-menu">
                                    <li class="parent">Community Insights</li>
                                    <span class="sub-menu-inner">
                                        <span class="column full">
                                        
                                            <ul>
                                                <?php while ( have_rows( 'community_insights_menu', 'option' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                            <li>
                                                                <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                    <?php $icon = get_sub_field( 'icon' ); ?>
                                                                    <span class="icon-container">
                                                                        <?php if ( $icon ) { ?>
                                                                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text">
                                                                        <span class="link-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                                        <span class="link-text-text"><?php echo get_sub_field( 'text' ); ?></span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </ul>
                                    
                                        </span>
                                    </span>
                                </ul>
                            </li>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                        <li>
                            <a href="/marketplace/">Marketplace</a>
                            <ul class="sub-menu" style="display: none;">
                                <li class="parent">Marketplace</li>
                                <span class="sub-menu-inner">
                                    <span class="column full">
                                        <?php if ( have_rows( 'marketplace_menu', 'option' ) ) : ?>
                                            <ul>
                                                <?php while ( have_rows( 'marketplace_menu', 'option' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                            <li>
                                                                <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                    <?php $icon = get_sub_field( 'icon' ); ?>
                                                                    <span class="icon-container">
                                                                        <?php if ( $icon ) { ?>
                                                                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text">
                                                                        <span class="link-title"><?php echo get_sub_field( 'title' ); ?></span>
                                                                        <span class="link-text-text"><?php echo get_sub_field( 'text' ); ?></span>
                                                                    </span>
                                                                </a>
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
                                    </span>
                                </span>
                            </ul>
                        </li>
                        <li>
                            <a href="/executive-advisors/" target="_self">Executive Advisors</a>
                        </li>                            
                        <li class="dropDown with-sub-menu">
                            <a href="#">Events</a>
                            <ul class="sub-menu">
                                <li class="parent">Events</li>
                                <span class="sub-menu-inner">
                                    <span class="column full">
                                        <?php if ( have_rows( 'events_menu_it', 'option' ) ) : ?>
                                            <ul>
                                                <?php while ( have_rows( 'events_menu_it', 'option' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                            <li>
                                                                <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                    <?php $icon = get_sub_field( 'icon' ); ?>
                                                                    <span class="icon-container">
                                                                        <?php if ( $icon ) { ?>
                                                                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text">
                                                                        <?php echo get_sub_field( 'link_text' ); ?>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                    <?php if ( have_rows( 'events_calendar' ) ) : ?>
                                                        <span class="events-calendar">                                                            
                                                            <?php while ( have_rows( 'events_calendar' ) ) : the_row(); ?>
                                                                <span class="events-calendar-container">
                                                                    <span class="image-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text-container">
                                                                        <span class="link-title"><?php echo get_sub_field( 'text' ); ?></span>
                                                                        <span class="link-container">
                                                                            <?php if(get_sub_field( 'link_type' ) == 'link'){ ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">Download</a>
                                                                            <?php } else { ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link formPopupHubspot" href="#downloadCalendarLink" target="_self">Download</a>
                                                                                    <span style="display: none;">         
                                                                                        <span class="preview-cta-form login-form-container" id="downloadCalendarLink">
                                                                                            <span class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                            <?php } ?>                                                                                
                                                                        </span> 
                                                                    </span>                                                                   
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </span>
                                </span>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($membershipType == 'advantage') { ?>
                        <li class="dropDown with-sub-menu">
                            <a href="#">Market Insights</a>
                            <ul class="sub-menu">
                                <li class="parent">Market Insights</li>
                                <span class="sub-menu-inner">
                                    <?php if ( have_rows( 'market_insights_column_one', 'option' ) ) : ?>
                                        <?php while ( have_rows( 'market_insights_column_one', 'option' ) ) : the_row(); ?>
                                            <div class="column full">
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
                                    <?php if ( have_rows( 'market_insights_column_two', 'option' ) ) : ?>
                                        <?php while ( have_rows( 'market_insights_column_two', 'option' ) ) : the_row(); ?>
                                            <div class="column full">
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
                                                                        <?php $icon = get_sub_field( 'icon' ); ?>						
                                                                        <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                            <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                            <?php if ( $type_link_term ): ?>                                                                                    
                                                                                <li>
                                                                                    <a href="<?php echo get_term_link($type_link_term); ?>" >
                                                                                        <span class="icon-container">
                                                                                            <?php if ( $icon ) { ?>
                                                                                                <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                            <?php } ?>
                                                                                        </span>
                                                                                        <span class="link-text text-black"><?php echo $type_link_term->name; ?></span>
                                                                                    </a>
                                                                                </li>                                                                                                                                                                    
                                                                            <?php endif; ?>
                                                                        <?php } else { ?>
                                                                            <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                            <?php if ( $other_link ): ?>
                                                                                <li>
                                                                                    <a href="<?php echo get_sub_field( 'other_link' ); ?>" >
                                                                                            <span class="icon-container">
                                                                                            <?php if ( $icon ) { ?>
                                                                                                <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                            <?php } ?>
                                                                                        </span>
                                                                                        <span class="link-text text-black"><?php echo $other_link; ?></span>
                                                                                    </a>
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
                                    <?php if ( have_rows( 'market_insights_column_three', 'option' ) ) : ?>
                                        <?php while ( have_rows( 'market_insights_column_three', 'option' ) ) : the_row(); ?>
                                            <div class="column full">
                                                <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                                <?php if ( $post_object ): ?>
                                                    <?php $post = $post_object; ?>
                                                    <?php setup_postdata( $post ); ?> 
                                                    <a href="<?php the_permalink(); ?>">
                                                        <span class="menu-featured-post">
                                                            <span class="image-container">
                                                                <?php
                                                                $image = null;
                                                                $video = 'no';
                                                                if ( has_term('replay-post', 'replay') ) { 
                                                                    if(get_field('video_image')) {
                                                                        $image = get_field('video_image');
                                                                        $video = 'yes';
                                                                    } else {
                                                                        $image = get_field('featured_image');
                                                                    } 
                                                                } else {
                                                                    if (get_field('listing_image')) {
                                                                        $image = get_field('listing_image');                        
                                                                    } else {
                                                                        if (get_field('featured_image_or_video') === 'video') {
                                                                            $video = 'yes';
                                                                            if(get_field('video_poster')){
                                                                                $image = get_field('video_poster');
                                                                            } else if(get_field('video_image')) {
                                                                                $image = get_field('video_image');
                                                                            } else {
                                                                                $image = get_field('featured_image');
                                                                            }                                                      
                                                                        } else {
                                                                            if(get_field('video_poster')){
                                                                                $image = get_field('video_poster');
                                                                            } else if(get_field('video_image')) {
                                                                                $image = get_field('video_image');
                                                                            
                                                                            } else {
                                                                                $image = get_field('featured_image');                                                                            
                                                                            }  
                                                                        }
                                                                    }
                                                                }
                                                                
                                                                ?>
                                                                <?php if ($image) : ?>
                                                                    <span class="bg-container">
                                                                        <?php
					$inline_img_164_src = $image;
					$inline_img_164_attach_id = $inline_img_164_src ? attachment_url_to_postid( $inline_img_164_src ) : 0;
					if ( $inline_img_164_attach_id ) {
						echo wp_get_attachment_image( $inline_img_164_attach_id, 'article-card', false, array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' ) );
					} elseif ( $inline_img_164_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_164_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                        <?php if($video == 'yes'){ ?>
                                                                            <span class="video-icon"></span>
                                                                        <?php } ?>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </span>
                                                            <span class="text-container">
                                                                <span class="labelSmall text-bold text-black"><?php the_title(); ?></span>
                                                                <span class="link-container">
                                                                    <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                                                                </span>
                                                            </span>
                                                        </span>
                                                    </a>                                                            
                                                    <?php wp_reset_postdata(); ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </span>
                            </ul>
                        </li>
                        <li>
                            <a href="/filter-types/market-narratives/">Market Narratives</a>
                        </li>
                        <li class="dropDown with-sub-menu">
                            <a href="#">Personas</a>
                            <ul class="sub-menu">
                                <li class="parent">Personas</li>
                                <span class="sub-menu-inner">
                                    <span class="column full">
                                        <?php if ( have_rows( 'personas_column_one', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'personas_column_one', 'option' ) ) : the_row(); ?>
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
                                                                            <li>                                                                                				
                                                                                <a href="<?php echo get_sub_field( 'persona_link' ); ?>"><?php echo get_sub_field( 'persona_link_text' ); ?></a>
                                                                            </li>                                                                            
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
                                        <?php if ( have_rows( 'personas_column_two', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'personas_column_two', 'option' ) ) : the_row(); ?>
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
                                                                            <?php $icon = get_sub_field( 'icon' ); ?>						
                                                                            <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                                <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                                <?php if ( $type_link_term ): ?>                                                                                    
                                                                                    <li>
                                                                                        <a href="<?php echo get_term_link($type_link_term); ?>" >
                                                                                            <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $type_link_term->name; ?></span>
                                                                                        </a>
                                                                                    </li>                                                                                                                                                                    
                                                                                <?php endif; ?>
                                                                            <?php } else { ?>
                                                                                <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                                <?php if ( $other_link ): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_sub_field( 'other_link' ); ?>" >
                                                                                             <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $other_link; ?></span>
                                                                                        </a>
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
                                        <?php if ( have_rows( 'personas_column_three', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'personas_column_three', 'option' ) ) : the_row(); ?>
                                                <div class="column third">
                                                    <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                                    <?php if ( $post_object ): ?>
                                                        <?php $post = $post_object; ?>
                                                        <?php setup_postdata( $post ); ?> 
                                                        <a href="<?php the_permalink(); ?>">
                                                            <span class="menu-featured-post">
                                                                <span class="image-container">
                                                                    <?php
                                                                    $image = null;
                                                                    $video = 'no';
                                                                    if ( has_term('replay-post', 'replay') ) { 
                                                                        if(get_field('video_image')) {
                                                                            $image = get_field('video_image');
                                                                            $video = 'yes';
                                                                        } else {
                                                                            $image = get_field('featured_image');
                                                                        } 
                                                                    } else {
                                                                        if (get_field('listing_image')) {
                                                                            $image = get_field('listing_image');                        
                                                                        } else {
                                                                            if (get_field('featured_image_or_video') === 'video') {
                                                                                $video = 'yes';
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                } else {
                                                                                    $image = get_field('featured_image');
                                                                                }                                                      
                                                                            } else {
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                
                                                                                } else {
                                                                                    $image = get_field('featured_image');                                                                            
                                                                                }  
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                    ?>
                                                                    <?php if ($image) : ?>
                                                                        <span class="bg-container">
                                                                            <?php
					$inline_img_165_src = $image;
					$inline_img_165_attach_id = $inline_img_165_src ? attachment_url_to_postid( $inline_img_165_src ) : 0;
					if ( $inline_img_165_attach_id ) {
						echo wp_get_attachment_image( $inline_img_165_attach_id, 'article-card', false, array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' ) );
					} elseif ( $inline_img_165_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_165_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                            <?php if($video == 'yes'){ ?>
                                                                                <span class="video-icon"></span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <span class="text-container">
                                                                    <span class="labelSmall text-bold text-black"><?php the_title(); ?></span>
                                                                    <span class="link-container">
                                                                        <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </a>                                                            
                                                        <?php wp_reset_postdata(); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </span>
                                </span>
                            </ul>
                        </li>
                        <li class="dropDown with-sub-menu">
                            <a href="#">Sectors</a>
                            <ul class="sub-menu">
                                <li class="parent">Sectors</li>
                                <span class="sub-menu-inner">
                                    <span class="column full">
                                        <?php if ( have_rows( 'sectors_column_one', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'sectors_column_one', 'option' ) ) : the_row(); ?>
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
                                                                            <li>                                                                                				
                                                                                <a href="<?php echo get_sub_field( 'sector_link' ); ?>"><?php echo get_sub_field( 'sector_link_text' ); ?></a>
                                                                            </li> 
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
                                        <?php if ( have_rows( 'sectors_column_two', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'sectors_column_two', 'option' ) ) : the_row(); ?>
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
                                                                            <?php $icon = get_sub_field( 'icon' ); ?>						
                                                                            <?php if ( get_sub_field( 'type_or_other_link' ) == 'type'){ ?>
                                                                                <?php $type_link_term = get_sub_field( 'type_link' ); ?>
                                                                                <?php if ( $type_link_term ): ?>                                                                                    
                                                                                    <li>
                                                                                        <a href="<?php echo get_term_link($type_link_term); ?>" >
                                                                                            <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $type_link_term->name; ?></span>
                                                                                        </a>
                                                                                    </li>                                                                                                                                                                    
                                                                                <?php endif; ?>
                                                                            <?php } else { ?>
                                                                                <?php $other_link = get_sub_field( 'other_link_text' ); ?>
                                                                                <?php if ( $other_link ): ?>
                                                                                    <li>
                                                                                        <a href="<?php echo get_sub_field( 'other_link' ); ?>" >
                                                                                             <span class="icon-container">
                                                                                                <?php if ( $icon ) { ?>
                                                                                                    <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                            <span class="link-text text-black"><?php echo $other_link; ?></span>
                                                                                        </a>
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
                                        <?php if ( have_rows( 'sectors_column_three', 'option' ) ) : ?>
                                            <?php while ( have_rows( 'sectors_column_three', 'option' ) ) : the_row(); ?>
                                                <div class="column third">
                                                    <?php $post_object = get_sub_field( 'featured_post' ); ?>
                                                    <?php if ( $post_object ): ?>
                                                        <?php $post = $post_object; ?>
                                                        <?php setup_postdata( $post ); ?> 
                                                        <a href="<?php the_permalink(); ?>">
                                                            <span class="menu-featured-post">
                                                                <span class="image-container">
                                                                    <?php
                                                                    $image = null;
                                                                    $video = 'no';
                                                                    if ( has_term('replay-post', 'replay') ) { 
                                                                        if(get_field('video_image')) {
                                                                            $image = get_field('video_image');
                                                                            $video = 'yes';
                                                                        } else {
                                                                            $image = get_field('featured_image');
                                                                        } 
                                                                    } else {
                                                                        if (get_field('listing_image')) {
                                                                            $image = get_field('listing_image');                        
                                                                        } else {
                                                                            if (get_field('featured_image_or_video') === 'video') {
                                                                                $video = 'yes';
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                } else {
                                                                                    $image = get_field('featured_image');
                                                                                }                                                      
                                                                            } else {
                                                                                if(get_field('video_poster')){
                                                                                    $image = get_field('video_poster');
                                                                                } else if(get_field('video_image')) {
                                                                                    $image = get_field('video_image');
                                                                                
                                                                                } else {
                                                                                    $image = get_field('featured_image');                                                                            
                                                                                }  
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                    ?>
                                                                    <?php if ($image) : ?>
                                                                        <span class="bg-container">
                                                                            <?php
					$inline_img_166_src = $image;
					$inline_img_166_attach_id = $inline_img_166_src ? attachment_url_to_postid( $inline_img_166_src ) : 0;
					if ( $inline_img_166_attach_id ) {
						echo wp_get_attachment_image( $inline_img_166_attach_id, 'article-card', false, array( 'alt' => esc_attr(get_the_title($post_id)), 'class' => 'article-image' ) );
					} elseif ( $inline_img_166_src ) {
						echo '<img class="article-image" src="' . esc_url( $inline_img_166_src ) . '" loading="lazy" alt="' . esc_attr( esc_attr(get_the_title($post_id)) ) . '" />';
					}
				?>
                                                                            <?php if($video == 'yes'){ ?>
                                                                                <span class="video-icon"></span>
                                                                            <?php } ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </span>
                                                                <span class="text-container">
                                                                    <span class="labelSmall text-bold text-black"><?php the_title(); ?></span>
                                                                    <span class="link-container">
                                                                        <span class="text-link red-text-link uppercase arrow-link">Read Report</span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                        </a>                                                            
                                                        <?php wp_reset_postdata(); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </span>
                                </span>
                            </ul>
                        </li>
                        <li class="dropDown with-sub-menu">
                            <a href="#">Events</a>
                            <ul class="sub-menu">
                                <li class="parent">Events</li>
                                <span class="sub-menu-inner">
                                    <span class="column full">
                                        <?php if ( have_rows( 'events_menu', 'option' ) ) : ?>
                                            <ul>
                                                <?php while ( have_rows( 'events_menu', 'option' ) ) : the_row(); ?>
                                                    <?php if ( have_rows( 'link' ) ) : ?>
                                                        <?php while ( have_rows( 'link' ) ) : the_row(); ?>
                                                            <li>
                                                                <a href="<?php echo get_sub_field( 'link' ); ?>">
                                                                    <span class="icon-container">
                                                                        <?php $icon = get_sub_field( 'icon' ); ?>
                                                                        <?php if ( $icon ) { ?>
                                                                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text">
                                                                        <?php echo get_sub_field( 'link_text' ); ?>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                    <?php if ( have_rows( 'events_calendar' ) ) : ?>
                                                        <span class="events-calendar">                                                            
                                                            <?php while ( have_rows( 'events_calendar' ) ) : the_row(); ?>
                                                                <span class="events-calendar-container">
                                                                    <span class="image-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <span class="link-text-container">
                                                                        <span class="link-title"><?php echo get_sub_field( 'text' ); ?></span>
                                                                        <span class="link-container">
                                                                            <?php if(get_sub_field( 'link_type' ) == 'link'){ ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link" href="<?php echo get_sub_field( 'link' ); ?>" target="_self">Download</a>
                                                                            <?php } else { ?> 
                                                                                <a class="text-link red-text-link uppercase arrow-link formPopupHubspot" href="#downloadCalendarLink" target="_self">Download</a>
                                                                                    <span style="display: none;">         
                                                                                        <span class="preview-cta-form login-form-container" id="downloadCalendarLink">
                                                                                            <span class="form-container"><?php echo get_sub_field( 'hubspot_embed' ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                            <?php } ?>                                                                                
                                                                        </span> 
                                                                    </span>                                                                   
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    </span>
                                </span>
                            </ul>
                        </li>                                
                    <?php } ?>                    
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

<?php
// if (is_user_logged_in()) {
//     $user_id = get_current_user_id();

//     // Get all relevant user meta
//     $logins_30_array      = get_user_meta($user_id, 'mepr_logins_30_days_array', true);
//     $logins_30_total      = get_user_meta($user_id, 'mepr_logins_thirty_days', true);
//     $logins_12_array      = get_user_meta($user_id, 'mepr_logins_12_months_array', true);
//     $logins_12_total      = get_user_meta($user_id, 'mepr_logins_twelve_months', true);

//     $downloads_30_array   = get_user_meta($user_id, 'mepr_downloads_30_days_array', true);
//     $downloads_30_total   = get_user_meta($user_id, 'mepr_downloads_thirty_days', true);
//     $downloads_12_array   = get_user_meta($user_id, 'mepr_downloads_12_months_array', true);
//     $downloads_12_total   = get_user_meta($user_id, 'mepr_downloads_twelve_months', true);

//     $views_30_array       = get_user_meta($user_id, 'mepr_post_views_30_days_array', true);
//     $views_30_total       = get_user_meta($user_id, 'mepr_post_views_thirty_days', true);
//     $views_12_array       = get_user_meta($user_id, 'mepr_post_views_12_months_array', true);
//     $views_12_total       = get_user_meta($user_id, 'mepr_post_views_twelve_months', true);

//     echo '<pre style="background:#fff;color:#000;padding:10px;border:1px solid #000;z-index:9999; margin-top: 150px; display: none">';
//     echo '<strong>User ID: ' . $user_id . '</strong><br><br>';

//     echo "<strong>Logins 30 Days Array:</strong>\n"; print_r($logins_30_array);
//     echo "<strong>Logins 30 Days Total:</strong> $logins_30_total\n";
//     echo "<strong>Logins 12 Months Array:</strong>\n"; print_r($logins_12_array);
//     echo "<strong>Logins 12 Months Total:</strong> $logins_12_total\n\n";

//     echo "<strong>Downloads 30 Days Array:</strong>\n"; print_r($downloads_30_array);
//     echo "<strong>Downloads 30 Days Total:</strong> $downloads_30_total\n";
//     echo "<strong>Downloads 12 Months Array:</strong>\n"; print_r($downloads_12_array);
//     echo "<strong>Downloads 12 Months Total:</strong> $downloads_12_total\n\n";

//     echo "<strong>Post Views 30 Days Array:</strong>\n"; print_r($views_30_array);
//     echo "<strong>Post Views 30 Days Total:</strong> $views_30_total\n";
//     echo "<strong>Post Views 12 Months Array:</strong>\n"; print_r($views_12_array);
//     echo "<strong>Post Views 12 Months Total:</strong> $views_12_total\n";

//     echo '</pre>';
// }
?>

<div class="menu-overlay"></div>