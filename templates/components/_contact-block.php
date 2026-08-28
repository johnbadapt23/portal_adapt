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
    $interests = $current_user->mepr_interests;
}
?>

<section class="getAdvice bg-white">
    <div class="container">
        <div class="textContainer">
            <h1><?php echo esc_html( get_sub_field( 'title' ) ); ?></h1>
            <p><?php echo esc_html( get_sub_field( 'sub_title' ) ); ?></p>
            <span class="howHelp">Hi <?php echo $first_name;?>, how can we help?</span>
            <?php echo get_sub_field( 'form' ); ?>
        </div>
    </div>
    <div class="imageSizeContainer">
        <div class="bgContainer">
            <?php $image = get_sub_field( 'image' ); ?>
			<?php if ( $image ) { ?>
				<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'], 'class' => 'desktop' ) ); ?>
			<?php } ?>
        </div>
    </div>
</section>
