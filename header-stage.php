<!doctype html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

<title><?php wp_title(); ?></title>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/main.min.css?ver=1.4">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/skelet-icons-master/style.css">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-16x16.png">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/images/site.webmanifest">
<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#000000">
<meta name="theme-color" content="#000000">
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
    <meta property="og:image" content="<?php echo get_field( 'video_poster' ); ?>" />
<?php } else { ?>
    <meta property="og:image" content="<?php echo get_field( 'featured_image' ); ?>" />
<?php } ?>

<?php get_template_part( 'templates/partials/_icons' ); ?>
<?php wp_head(); ?>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NHF4ZRS');</script>
<!-- End Google Tag Manager -->

<?php $user_info = wp_get_current_user(); ?>
<?php if(current_user_can('mepr-active')){
    $user_name = do_shortcode('[mepr-account-info field="full_name"]');
    $user_ID = $user_info->ID; // get the user ID
    $member = new MeprUser(); // initiate the class
    $member->ID = $user_info->ID; // if using this in admin area, you'll need this to make user id the member id
    $memberships = $member->get_active_subscription_titles(", "); //MeprUser function that gets subscription title
    $interests = $user_info->mepr_interests;
    $registration_date = do_shortcode('[mepr-account-info field="user_registered"]');
    global $wpdb;
    $q = "SELECT COUNT(*) FROM {$wpdb->prefix}mepr_corporate_accounts WHERE user_id = {$user_info->ID}";
    $count = $wpdb->get_var($q);
    if(!$count) {
        $role='Not-Corporate';
    } else {
        $role='Corporate';
    }

    $interests_names = array();
    $term_mTopic = 'topic';
    $termsTopic = get_terms( $term_mTopic, array(
        'hide_empty' => false,
        'parent' => 0
    ) );
    if($interests){
        foreach($termsTopic as $termTopic) {
            if('on'==$interests[$termTopic->slug]){
                $interests_names[] = $termTopic->name;
            }
        }
    }


    if (is_user_logged_in() || (function_exists('adapt_content_unlocked') && adapt_content_unlocked())) {
        $user = wp_get_current_user();
        update_user_meta($user->ID, 'last_action_time', current_time('mysql'));
    }

}
?>
<script>

/* Chameleon script */
!function(d,w){var t="SDSi49K7BwQc7lRGhCOtLp1BzvxlN7VGRZzZvIUNmBePIu-1JQ3jl-Ceb2BabRyazdJyAc",c="chmln",m="identify alias track clear set show on off custom help _data".split(" "),i=d.createElement("script");if(w[c]||(w[c]={}),!w[c].root){w[c].accountToken=t,w[c].location=w.location.href.toString(),w[c].now=new Date;for(var s=0;s<m.length;s++){!function(){var t=w[c][m[s]+"_a"]=[];w[c][m[s]]=function(){t.push(arguments);};}();}i.src="https://fast.trychameleon.com/messo/"+t+"/messo.min.js",i.async=!0,d.head.appendChild(i);}}(document,window);

/* Chameleon - better user onboarding */
// !function(t,n,o){var a="chmln",e="adminPreview",c="setup identify alias track clear set show on off custom help _data".split(" ");if(n[a]||(n[a]={}),n[a][e]&&(n[a][e]=!1),!n[a].root){n[a].accountToken=o,n[a].location=n.location.href.toString(),n[a].now=new Date;for(var s=0;s<c.length;s++)!function(){var t=n[a][c[s]+"_a"]=[];n[a][c[s]]=function(){t.push(arguments)}}();var i=t.createElement("script");i.src="https://fast.trychameleon.com/messo/"+o+"/messo.min.js",i.async=!0,t.head.appendChild(i)}}(document,window,"SDSi49K7BwQc7lRGhCOtLp1BzvxlN7VGRZzZvIUNmBePIu-1JQ3jl-Ceb2BabRyazdJyAc");
// **This is an example script, don't forget to change the PLACEHOLDERS.**
// Please confirm the user properties to be sent with your project owner.

// Required:
chmln.identify(<?php echo $user_ID; ?>, {
  email: '<?php echo $user_info->user_email; ?>',
  name: '<?php echo $user_info->first_name; ?>',
  uid: '<?php echo $user_ID; ?>',
  fullname: '<?php echo $user_name; ?>',
  companyname: '<?php echo $user_info->mepr_company_name; ?>',
  topics: '<?php echo implode(", ", $interests_names); ?>',
  role: '<?php echo $role; ?>',
  last_session: '<?php echo $user_info->last_action_time; ?>',
  memberships: '<?php echo $memberships; ?>',
  registration_date: '<?php echo $registration_date; ?>'
});
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131921908-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-131921908-1');
</script>
<script>
<?php if(current_user_can('mepr-active')){ ?>
    dataLayer.push({'userId': '<?php echo $user_ID; ?>'});
    dataLayer.push({'userName': '<?php echo $user_name; ?>'});
    // gtag('config', 'GA_MEASUREMENT_ID', {
    //   'user_id': '<?php echo $user_ID; ?>'
    // });
<?php } ?>
</script>

</head>
<?php $q = get_queried_object(); ?>
<body <?php body_class(''); ?> <?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews'){ ?>data-theme-style="dark" <?php } ?> rel="<?php if ( is_404() ): echo 'notFound'; endif; ?>" <?php if(current_user_can('mepr-active')) { ?>id="logged-in"<?php } ?>>
    <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NHF4ZRS"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

	<?php get_template_part( 'templates/partials/_header' ); ?>
