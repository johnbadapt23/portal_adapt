
<section class="fullWidthTextEditor print-only<?php if ( get_sub_field( 'font') ) { ?> <?php echo get_sub_field( 'font' );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php echo get_sub_field( 'font_colour' ); ?><?php } ?> scrollPos" <?php if( get_sub_field('id')){?>id="<?php echo get_sub_field('id'); ?>"<?php } ?>>
    <div class="container">
        <?php
        $user_info = wp_get_current_user();
        // $user_meta = wp_get_user_meta();
        // $user_corporate = get_mepr_corporate_accounts_meta();
        $user = MeprUtils::get_currentuserinfo();

        // print_r(mepr_corporate_accounts( $user_info->ID));
        // print_r($user_info);
        // print_r($user_corporate);
        global $wpdb;

          $q = "SELECT COUNT(*) FROM {$wpdb->prefix}mepr_corporate_accounts WHERE user_id = {$user_info->ID}";
          $count = $wpdb->get_var($q);



          if(!$count) {
            echo 'not-corporate';
        } else {
            echo 'corporate';
            echo $count;
        }


      ?>
        <?php echo get_sub_field( 'embed' ); ?>
    </div>
</section>
