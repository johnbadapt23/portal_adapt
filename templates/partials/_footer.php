<?php if(current_user_can('memberpress_authorized')) { ?>
<?php global $current_user, $first_name, $last_name, $user_email, $membershipType, $advantageType, $member;
?>
<footer>
    <section class="footer">
        <span class="backTop"></span>
        <div class="container">
            <span class="top">
                <span class="logo">
            		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
            			<?php
					$inline_img_157_src = get_field( 'logo','options' );
					$inline_img_157_attach_id = $inline_img_157_src ? attachment_url_to_postid( $inline_img_157_src ) : 0;
					if ( $inline_img_157_attach_id ) {
						echo wp_get_attachment_image( $inline_img_157_attach_id, 'full', false, array( 'alt' => 'Adapt' ) );
					} elseif ( $inline_img_157_src ) {
						echo '<img src="' . esc_url( $inline_img_157_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( 'Adapt' ) . '" />';
					}
				?>
            		</a>
            	</span>                
            </span>
            <span class="middle">
                <div class="column first">
                    <?php if ( have_rows( 'column_one', 'options' ) ) : ?>
                    	<?php while ( have_rows( 'column_one', 'options' ) ) : the_row(); ?>
                    		<span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                    		<?php if ( have_rows( 'links' ) ) : ?>
                                <ul class="footerLinks">
                        			<?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                        <li>
                                            <a href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" class="footerLink"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                        </li>
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
                <div class="column second">
                    <?php if ($membershipType == 'advantage') { ?>
                        <?php if ( have_rows( 'column_two', 'options' ) ) : ?>
                            <?php while ( have_rows( 'column_two', 'options' ) ) : the_row(); ?>
                                <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                <?php if ( have_rows( 'links' ) ) : ?>
                                    <ul class="footerLinks">
                                        <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                            <li>
                                                <a href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" class="footerLink"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    <?php } else { ?>
                        <?php if ( have_rows( 'column_two_it', 'options' ) ) : ?>
                            <?php while ( have_rows( 'column_two_it', 'options' ) ) : the_row(); ?>
                                <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                <?php if ( have_rows( 'links' ) ) : ?>
                                    <ul class="footerLinks">
                                        <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                            <li>
                                                <a href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" class="footerLink"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    <?php } ?>
                </div>
                <div class="column third">
                    <?php if ( have_rows( 'column_three', 'options' ) ) : ?>
                    	<?php while ( have_rows( 'column_three', 'options' ) ) : the_row(); ?>
                    		<span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                    		<?php if ( have_rows( 'links' ) ) : ?>
                                <ul class="footerLinks">
                        			<?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                        <li>
                                            <a href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" class="footerLink"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                        </li>
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
                <?php if ( have_rows( 'column_four', 'options' ) ) : ?>
                    <div class="column fourth">
                            <?php while ( have_rows( 'column_four', 'options' ) ) : the_row(); ?>
                                <span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                                <?php if ( have_rows( 'links' ) ) : ?>
                                    <ul class="footerLinks">
                                        <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                            <li>
                                                <?php if(get_sub_field( 'link' ) !== '' ){ ?>
                                                    <a href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" class="footerLink"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                <?php } else { ?> 
                                                    <span class="footerLink no-link"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></span>
                                                <?php } ?>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        
                    </div>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <div class="column fifth">
                    <?php if ( have_rows( 'column_five', 'options' ) ) : ?>
                    	<?php while ( have_rows( 'column_five', 'options' ) ) : the_row(); ?>
                    		<span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                    		<?php if ( have_rows( 'links' ) ) : ?>
                                <ul class="footerLinks">
                        			<?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                        <li>
                                            <?php if(get_sub_field( 'link' ) !== '' ){ ?>
                                                <a href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" class="footerLink"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                            <?php } else { ?> 
                                                <span class="footerLink no-link"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></span>
                                            <?php } ?>
                                        </li>
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
                <div class="column sixth">
                    <?php if ( have_rows( 'column_six', 'options' ) ) : ?>
                    	<?php while ( have_rows( 'column_six', 'options' ) ) : the_row(); ?>
                    		<span class="title"><?php echo get_sub_field( 'title' ); ?></span>
                    		<?php if ( have_rows( 'links' ) ) : ?>
                                <ul class="footerLinks">
                        			<?php while ( have_rows( 'links' ) ) : the_row(); ?>
                                        <li>
                                            <a href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" class="footerLink <?php echo get_sub_field( 'class' ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                        </li>
                        			<?php endwhile; ?>
                                </ul>
                    		<?php else : ?>
                    			<?php // no rows found ?>
                    		<?php endif; ?>
                    	<?php endwhile; ?>
                    <?php else : ?>
                    	<?php // no rows found ?>
                    <?php endif; ?>
                    <span class="social desktop">
                        <?php if(get_field('linkedin_url','options')) { ?>
                            <a href="<?php echo esc_url( get_field('linkedin_url','options') ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn (opens in a new tab)">
                                <svg version="1.1" id="Group_193" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                	 viewBox="0 0 14.9 15" style="enable-background:new 0 0 14.9 15;" xml:space="preserve">
                                <style type="text/css">
                                	.st0{fill:#FFFFFF;}
                                </style>
                                <g id="Group_196" transform="translate(5.963 5.923)">
                                	<g id="Group_193-2" transform="translate(0.596 4.149)">
                                		<path id="Path_56" class="st0" d="M-2.9,4.4c0,0.3-0.2,0.5-0.5,0.5h-2.2c-0.3,0-0.5-0.2-0.5-0.5v-9c0-0.3,0.2-0.5,0.5-0.5h2.2
                                			c0.3,0,0.5,0.2,0.5,0.5L-2.9,4.4z"/>
                                	</g>
                                	<g id="Group_194" transform="translate(0 0)">
                                		<ellipse id="Ellipse_12" class="st0" cx="-3.9" cy="-3.9" rx="2" ry="2"/>
                                	</g>
                                	<g id="Group_195" transform="translate(4.23 4.033)">
                                		<path id="Path_57" class="st0" d="M4.7,4.5C4.7,4.8,4.5,5,4.2,5l0,0H1.9C1.6,5,1.4,4.8,1.4,4.5l0,0V0.3c0-0.6,0.2-2.8-1.6-2.8
                                			C-1.6-2.5-1.9-1-2-0.3v4.9C-2,4.8-2.2,5-2.5,5l0,0h-2.2C-5,5-5.2,4.8-5.2,4.5l0,0v-9.1C-5.2-4.8-4.9-5-4.7-5l0,0h2.2
                                			C-2.2-5-2-4.8-2-4.6l0,0v0.8c0.7-1,1.8-1.5,3-1.4c3.7,0,3.7,3.5,3.7,5.3V4.5L4.7,4.5z"/>
                                	</g>
                                </g>
                                </svg>
                                <!-- <img src="<?php echo get_template_directory_uri(); ?>/assets/images/linkedin-nocircle.svg" width="14" height="14" loading="lazy" decoding="async" alt="LinkedIn" /> -->
                            </a>
                        <?php } ?>
                        <?php if(get_field('youtube_url','options')) { ?>
                            <a href="<?php echo esc_url( get_field('youtube_url','options') ); ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube (opens in a new tab)">
                                <svg version="1.1" id="Group_194" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                	 viewBox="0 0 16.6 11.8" style="enable-background:new 0 0 16.6 11.8;" xml:space="preserve">
                                <style type="text/css">
                                	.st0{fill:#FFFFFF;}
                                </style>
                                <g id="Group_200" transform="translate(4.703 6.866)">
                                	<path id="Path_60" class="st0" d="M8.5-6.9h-9.7c-1.9,0-3.5,1.5-3.5,3.5v4.9C-4.7,3.4-3.2,5-1.2,5h9.7C10.4,5,12,3.5,12,1.5v-4.9
                                		C11.9-5.3,10.4-6.9,8.5-6.9z M6.1-0.8L1.6,1.4c-0.1,0-0.2,0-0.2-0.1V1.2v-4.5c0-0.1,0.1-0.2,0.2-0.2h0.1l4.5,2.3
                                		C6.2-1,6.3-0.9,6.1-0.8C6.2-0.8,6.2-0.8,6.1-0.8z"/>
                                </g>
                                </svg>
                                <!-- <img src="<?php echo get_template_directory_uri(); ?>/assets/images/youtube-nocircle.svg" width="16" height="11" loading="lazy" decoding="async" alt="YouTube" /> -->
                            </a>
                        <?php } ?>
                    </span>
                </div>
            </span>
            <span class="base">
                <div>
                    <span class="left">
                        <span>&copy; ADAPT VENTURES PTY LTD <?php echo date('Y'); ?>. All rights reserved</span>
                    </span>
                    <span class="right">
                        <?php theme_nav('bottom'); ?>
                    </span>
                </div>
            </span>
        </div>
    </section>
    </footer>
<?php } ?>

<!-- mobile app popup -->
<?php
    $current_user = wp_get_current_user();
    // echo $current_user;
    if ( 0 == $current_user->ID ) {
        // Not logged in.
    } else {
        // $user_info = get_userdata($current_user);
        $first_name = $current_user->first_name;
        $last_name = $current_user->last_name;
        $user_email = $current_user->user_email;
    }
?>

<!-- Members only popup -->

<?php if (is_single() && ('post' == get_post_type()) || 'kyc' == get_post_type() || has_term('', 'kit-type') || is_search()) { ?>
<?php } else { ?>
    <?php if(current_user_can('memberpress_authorized')) { ?>
    <?php } else { ?>
        <?php if (is_user_logged_in() || (function_exists('adapt_content_unlocked') && adapt_content_unlocked())) { ?>
        <?php } else { ?> 
            <a class="members-content-button" href="#membersPopup" style="display: none;"></a>
            <div style="display: none;">
                <div class="members-popup" id="membersPopup">
                    <div class="members-popup-inner">
                        <div class="members-popup-content">
                            <h3><?php echo get_field( 'members_only_title', 'option' ); ?></h3>
                            <p><?php echo get_field( 'members_only_text', 'option' ); ?></p>
                            <div class="image-content-mobile">
                                <?php $members_only_image_mobile = get_field( 'members_only_image_mobile', 'option' ); ?>
                                <span class="image-container">
                                    <span class="bg-container">
                                        <?php if ( $members_only_image_mobile ) { ?>
                                            <?php echo wp_get_attachment_image( $members_only_image_mobile['ID'], 'full', false, array( 'alt' => $members_only_image_mobile['alt'] ) ); ?>
                                        <?php } ?>
                                    </span>
                                </span>
                            </div>
                            <?php if ( have_rows( 'members_only_button', 'option' ) ) : ?>
                                <?php while ( have_rows( 'members_only_button', 'option' ) ) : the_row(); ?>
                                    <a class="button" href="<?php echo esc_url( get_sub_field( 'button_link' ) ); ?>" target="_self"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                            <span class="already">Already a member? <a class="login" href="/login/" target="_self">Login</a>
                        </div>
                        <div class="members-popup-image-content desktop-image">
                            <?php $members_only_image = get_field( 'members_only_image', 'option' ); ?>
                            <span class="image-container">
                                <span class="bg-container">
                                    <?php if ( $members_only_image ) { ?>
                                        <?php echo wp_get_attachment_image( $members_only_image['ID'], 'full', false, array( 'alt' => $members_only_image['alt'] ) ); ?>
                                    <?php } ?>
                                </span>
                            </span>
                        </div>
                        <img class="absolute-image" src="<?php echo get_template_directory_uri(); ?>/assets/images/A.svg" width="14" height="24" loading="lazy" decoding="async" alt="Adapt" />
                    </div>
                </div>
            </div>
            <style>
            header, main {
                filter: blur(20px);
            }
            </style>
            <script>
                (function($) {
                    $(window).on('load',function (){
                        // retrieved this line of code from http://dimsemenov.com/plugins/magnific-popup/documentation.html#api
                        $.magnificPopup.open({
                            items: {
                                src: '#membersPopup'
                            },
                            type: 'inline',
                            mainClass: 'mfp-members',
                            modal: true

                        // You may add options here, they're exactly the same as for $.fn.magnificPopup call
                        // Note that some settings that rely on click event (like disableOn or midClick) will not work here
                        }, 0);
                    });
                })(jQuery);
                document.addEventListener("contextmenu", function(e){
                    e.preventDefault();
                }, false);
            </script>
        <?php } ?>       
    <?php } ?>
<?php } ?>

<!-- Sub Accounts -->

<?php
// This loops through the current user's Active Transactions
// If one or more of the Transactions belongs to a Parent Corporate Account
// Then it's URL to the "Manage Sub Accounts" link will be output on the page.
$user = MeprUtils::get_currentuserinfo();

if($user !== false) {
  $transactions = $user->active_product_subscriptions('transactions');

  if(!empty($transactions)) {
    foreach($transactions as $txn) {
      if(($sub = $txn->subscription()) !== false) {
        //Recurring subscription
        $ca = MPCA_Corporate_Account::find_corporate_account_by_obj_id($sub->id, 'subscriptions');
      }
      else {
        //Non Recurring subscription
        $ca = MPCA_Corporate_Account::find_corporate_account_by_obj_id($txn->id, 'transactions');
      }

      if(!empty($ca) && isset($ca->id) && !empty($ca->id)) { ?>
          <span class="memberpress-subaccount-url" style="display: none"><?php echo $ca->signup_url(); ?></span>
        <?php
      }
    }
  }
}
?>
