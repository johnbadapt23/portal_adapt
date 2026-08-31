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
<section class="announcementArticleTextHeader bg-white">
    <div class="container">
        <div class="textContainer">
            <div class="text-container-inner">
                <span class="topicFilter">
                    <a href="/announcements/" class="topicFilterText">Announcements</a>
                </span>
                <span class="title"><?php echo esc_html( get_the_title() ); ?></span>

                <?php $textcontributors = get_field( 'contributors_text_field'); ?>
                <?php if ( have_rows( 'contributors' ) ) : ?>
                    <?php $totalCount = count(get_field('contributors')); ?>
                    <span class="author">by
                        <?php $count = 0; ?>
                        <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                            <?php $post_object = get_sub_field( 'contributor_name' ); ?>
                            <?php if ( $post_object ): ?>
                                <?php $post = $post_object; ?>
                                <?php setup_postdata( $post ); ?>
                                    <span class="authorName"><?php echo esc_html( get_the_title() ); ?><?php if ($count == $totalCount - 1){?> <?php } else { ?><span class="comma"><?php if ($count == $totalCount - 2){?> and <?php } else { ?>, <?php } ?></span><?php } ?></span>
                                <?php endif; ?>
                            <?php wp_reset_postdata(); ?>
                            <?php $count++; ?>
                        <?php endwhile; ?>
                    </span>
                    <?php else : ?>
                    <?php if ( $textcontributors ) { ?>
                        <span class="author">by <span class="authorName"><?php echo esc_html( $textcontributors ); ?></span></span>
                    <?php } ?>
                <?php endif; ?>
                <span class="dateReadTime"><?php echo esc_html( get_the_date('M j, Y') ); ?></span>
            </div>
        </div>
        <?php if ( get_field( 'listing_image') ) { ?>
            <div class="image-container">
                <div class="image-container-inner">
                    <div class="imageSizeContainer">
                        <div class="bgContainer">
                            <?php $image = get_field( 'listing_image'); ?>
                            <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
								}
							?>
                        </div>
                        <?php if ( get_field ( 'image_caption' )) { ?>
                            <div class="caption"><?php echo esc_html( get_field ( 'image_caption' ) ); ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<section class="webinar-article announcement-article contained-article bg-white">
    <div class="container">
        <div class="announcement-content">
            <span class="webinar-content content <?php echo esc_attr( $membershipType ); ?>">
                <?php if ($membershipType == 'advantage') { ?>
                <?php echo wp_kses_post( get_field( 'content' ) ); ?>
                <?php } ?>
                <?php if ($membershipType == 'it-pro') { ?>
                    <?php if( get_field('content_it_pro')){ ?>
                        <?php echo wp_kses_post( get_field( 'content_it_pro' ) ); ?>
                    <?php } else { ?>
                        <?php echo wp_kses_post( get_field( 'content' ) ); ?>
                    <?php } ?>
                <?php } ?>
            </span>
        </div>
    </div>
</section>
<section class="topicGrid relatedArticles portal">
    <div class="container">
        <div class="blockTitle">
            <h2 class="related">Recent Announcements</h2>
            <a href="/announcements" class="viewAll">View All</a>
        </div>
        <div class="gridWrapper">
            <?php $currentPostID = get_queried_object_id(); ?>
            <?php global $displayed_posts; ?>
            <?php $displayed_posts[] = $currentPostID; ?>
            <?php
                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                $args = array(
                    'post_type'      => 'announcement',
                    'posts_per_page' => 3,
                    'paged' => $paged
                );
                ?>

                <?php $loop = new WP_Query( $args);
                if( $loop->have_posts() ): ?>

                    <?php while( $loop->have_posts() ) : $loop->the_post(); ?>
                        <div class="item no-image">
                            <div class="textContainer">
                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
                                <span class="dateReadTime"><?php echo esc_html( get_the_date('M j, Y') ); ?></span>
                                <?php
                                     $text = get_field( 'content' );
                                     $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '...' );
                                ?>
                                <a class="text-dark" href="<?php the_permalink(); ?>"><span class="text-dark excerpt announcement-excerpt"><?php echo esc_html( $trimmed_content ); ?></span></a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif;?>
                <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>
