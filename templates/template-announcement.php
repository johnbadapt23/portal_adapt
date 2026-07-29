<?php $current_user = wp_get_current_user();
// echo $current_user;
if ( 0 == $current_user->ID ) {
    header("Location: https://research.adapt.com.au/login/");
} ?>

<?php
global $displayed_posts;
$posts = new WP_Query( $args );
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
<?php $args = array(
    'post_type' => 'announcement',
    'posts_per_page' => -1,
    'paged'=> $paged,
    'orderby' => 'date',
    'order' => 'DESC'
); ?>
<?php $loop = new WP_Query( $args );
if ( $loop->have_posts() ) :
    while ( $loop->have_posts() ) : $loop->the_post();
?>
        <?php if ( have_rows( 'membership_ids' ) ) : ?>
        <?php $counter = 0; ?>
            <?php while ( have_rows( 'membership_ids' ) ) : the_row(); ?>
                <?php if ( $counter == 0 ) {
                    $members = $members . get_sub_field( 'membership_id' );
                } else {
                    $members = $members . ',' .get_sub_field( 'membership_id' );
                } ?>
                <?php $counter++; ?>
            <?php endwhile; ?>
        <?php endif; ?>
        <?php if(current_user_can('mepr-active','memberships:' . $members)) { ?>
        <?php } else { ?>
            <?php $id = get_the_ID(); ?>
            <?php $displayed_posts[] = $id; ?>
        <?php } ?>
        <?php unset($members); ?>
    <?php endwhile; ?>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
<?php wp_reset_query(); ?>
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
<section class="announcementsContainer">
    <div class="container">
        <div class="introductionTextContainer">
            <h1><?php echo get_field( 'announcements_title', 'option' ); ?></h1>
            <span class="introductionText">
                <?php echo get_field( 'announcements_introduction_text', 'option' ); ?>
            </span>
        </div>
        <div class="announcements-loop">
            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
            <?php $args = array(
                'post_type' => 'announcement',
                'posts_per_page' => -1,
                'paged'=> $paged,
                'orderby' => 'date',
                'order' => 'DESC'
            ); ?>
            <?php $loop = new WP_Query( $args );
            if ( $loop->have_posts() ) :
                while ( $loop->have_posts() ) : $loop->the_post();
            ?>
            <?php if(current_user_can('mepr_auth')) {?>
               <a class="announcement-link" href="<?php the_permalink();?>" target="_self">
                   <span class="post-item">
                       <span class="item-top">
                           <span class="date"><?php echo get_the_date('F j, Y'); ?></span>
                           <h3 class="h3-style"><?php the_title();?></h3>
                       </span>
                       <span class="small-text">
                           <?php
                                $text = get_field( 'content' );
                                $trimmed_content = wp_trim_words( $text, $num_words = 40, $more = '...' );
                           ?>
                           <?php echo $trimmed_content; ?>
                       </span>
                       <span class="read-more">Read More</span>
                       <?php if ( have_rows( 'contributors' ) ) : ?>
                           <?php $totalCount = count(get_field('contributors')); ?>
                           <span class="author">Published by:
                               <?php $count = 0; ?>
                               <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                   <?php $post_object = get_sub_field( 'contributor_name' ); ?>
                                   <?php if ( $post_object ): ?>
                                       <?php $post = $post_object; ?>
                                       <?php setup_postdata( $post ); ?>
                                           <span class="authorName"><?php the_title(); ?><?php if ($count == $totalCount - 1){?> <?php } else { ?><span class="comma"><?php if ($count == $totalCount - 2){?> and <?php } else { ?>, <?php } ?></span><?php } ?></span>
                                       <?php endif; ?>
                                   <?php wp_reset_postdata(); ?>
                                   <?php $count++; ?>
                               <?php endwhile; ?>
                           </span>
                       <?php endif; ?>
                   </span>
               </a>
            <?php } ?>

            <?php endwhile; else : ?>
                <p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
            <?php endif; ?>
            <?php wp_reset_postdata(); wp_reset_query();?>
        </div>
    </div>
</section>
