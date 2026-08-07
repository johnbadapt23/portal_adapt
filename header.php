<!doctype html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1">

<title><?php wp_title(); ?></title>

<?php
    // CSS: global.min.css (base styles, forms, vendor libraries,
    // header/footer partials) loads on every page. Everything that used to
    // be bundled into one main.min.css combining every template's styles is
    // now split - most templates get core.min.css (the same combined styles
    // as before, minus the templates verified safe to split out below), and
    // the templates below get their own small exclusive bundle instead, so
    // that page only downloads the CSS it actually needs. See
    // source/scss/main-core.scss for which templates have been verified
    // safe to exclude from core.min.css and why - extending this list means
    // adding a new main-tpl-*.scss entry (source/gulp/tasks/build/styles.js)
    // and a new branch here.
    $css_ver = '4.0.0';
    $theme_uri = get_template_directory_uri();
?>
<link rel="stylesheet" href="<?php echo $theme_uri; ?>/assets/css/global.min.css?ver=<?php echo $css_ver; ?>">
<?php if ( is_page_template( 'templates/template-agenda.php' ) ) : ?>
    <link rel="stylesheet" href="<?php echo $theme_uri; ?>/assets/css/tpl-agenda.min.css?ver=<?php echo $css_ver; ?>">
<?php elseif ( is_page_template( 'templates/template-events.php' ) ) : ?>
    <link rel="stylesheet" href="<?php echo $theme_uri; ?>/assets/css/tpl-events.min.css?ver=<?php echo $css_ver; ?>">
<?php elseif ( is_page_template( 'templates/template-events-portal.php' ) ) : ?>
    <?php // Shares template-events.php's wrapper classes and also pulls in
    // components/_event-card.php, which isn't covered by tpl-events.min.css
    // alone - load both rather than assume exclusivity. ?>
    <link rel="stylesheet" href="<?php echo $theme_uri; ?>/assets/css/core.min.css?ver=<?php echo $css_ver; ?>">
    <link rel="stylesheet" href="<?php echo $theme_uri; ?>/assets/css/tpl-events.min.css?ver=<?php echo $css_ver; ?>">
<?php elseif ( is_page_template( 'templates/template-flexible.php' ) ) : ?>
    <?php // See source/scss/main-tpl-flexible.scss for the verification -
    // this is a subset of core.min.css (flexible/portal-modules/post/
    // post-new/single-events/single-post only), confirmed to cover every
    // class this template and its component partials actually use. Loaded
    // INSTEAD OF core.min.css, not alongside it. ?>
    <link rel="stylesheet" href="<?php echo $theme_uri; ?>/assets/css/tpl-flexible.min.css?ver=<?php echo $css_ver; ?>">
<?php else : ?>
    <link rel="stylesheet" href="<?php echo $theme_uri; ?>/assets/css/core.min.css?ver=<?php echo $css_ver; ?>">
<?php endif; ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/skelet-icons-master/style.css">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-16x16.png">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/images/site.webmanifest">
<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#000000">
<meta name="theme-color" content="#000000">
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js" defer></script>
<script src="https://unpkg.com/@lottiefiles/lottie-interactivity@latest/dist/lottie-interactivity.min.js" defer></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
<?php
    // Falls back to the site icon when a page has no featured_image/video_poster
    // ACF field set (e.g. the homepage) - previously this echoed an empty
    // content="" attribute, which most social platforms treat as a broken
    // image rather than "no image", instead of falling back to their own
    // default. The site icon is a poor substitute for a real 1200x630 social
    // share image though - worth having the content team upload a proper one.
    $og_image = ( get_field( 'featured_image_or_video' ) == 'video' )
        ? get_field( 'video_poster' )
        : get_field( 'featured_image' );
    if ( ! $og_image ) {
        $og_image = get_template_directory_uri() . '/assets/images/apple-touch-icon.png';
    }
    ?>
    <meta property="og:image" content="<?php echo esc_url( $og_image ); ?>" />

<?php get_template_part( 'templates/partials/_icons' ); ?>
<?php wp_head(); ?>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NHF4ZRS');

</script>
<!-- End Google Tag Manager -->

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-95ZTKV3MB2"></script> 
<script>   
    window.dataLayer = window.dataLayer || [];   
    function gtag(){dataLayer.push(arguments);}   
    gtag('js', new Date());   
    gtag('config', 'G-95ZTKV3MB2'); 
</script>

<?php

// Only run for logged-in active members
if ( (is_user_logged_in() || (function_exists('adapt_content_unlocked') && adapt_content_unlocked())) && current_user_can('mepr-active') ) :

// Get current user info
$user_info = wp_get_current_user();
$user_ID   = $user_info->ID;

// ----- Cache heavy user data per user -----
$cache_key = "header_user_data_{$user_ID}";
$user_data = get_transient($cache_key);

if ($user_data === false) {
    $user_name = $user_info->first_name . ' ' . $user_info->last_name;
    $registration_date = $user_info->user_registered;

    $member      = new MeprUser($user_ID);
    $memberships = $member->get_active_subscription_titles(", ");
    $login_count = $member->login_count;

    global $wpdb;
    $count = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}mepr_corporate_accounts WHERE user_id = %d",
            $user_ID
        )
    );
    $role = $count ? 'Corporate' : 'Not-Corporate';

    $interests_names = [];
    $interests = $user_info->mepr_interests ?? [];

    if ($interests) {
        $terms = get_terms([
            'taxonomy'   => 'topic',
            'hide_empty' => false,
            'parent'     => 0
        ]);

        foreach ($terms as $term) {
            if (!empty($interests[$term->slug]) && $interests[$term->slug] === 'on') {
                $interests_names[] = $term->name;
            }
        }
    }

    // Prepare secure UID hash
    $secret = 'VD1gPpnwTbsGm2DjfTJewSTJJksS-1JWuTR-Ceb2BabRyazdJyAc';
    $now = time();
    $uid_hash = hash_hmac('sha256', $user_ID . '-' . $now, $secret) . '-' . $now;

    $last_session = get_user_meta($user_ID, 'last_action_time', true);

    $user_data = compact(
        'user_ID',
        'user_name',
        'registration_date',
        'memberships',
        'role',
        'interests_names',
        'uid_hash',
        'last_session',
        'login_count'
    );

    // Cache for 1 hour
    set_transient($cache_key, $user_data, HOUR_IN_SECONDS);
}
?>
<?php if(current_user_can('mepr-active')){ ?>
    <script>
    /* Chameleon script */
    !function(d,w){var t="SDSi49K7BwQc7lRGhCOtLp1BzvxlN7VGRZzZvIUNmBePIu-1JQ3jl-Ceb2BabRyazdJyAc",c="chmln",m="identify alias track clear set show on off custom help _data".split(" "),i=d.createElement("script");if(w[c]||(w[c]={}),!w[c].root){w[c].accountToken=t,w[c].location=w.location.href.toString(),w[c].now=new Date;for(var s=0;s<m.length;s++){!function(){var t=w[c][m[s]+"_a"]=[];w[c][m[s]]=function(){t.push(arguments);};}();}i.src="https://fast.trychameleon.com/messo/"+t+"/messo.min.js",i.async=!0,d.head.appendChild(i);}}(document,window);

    /* Chameleon - better user onboarding */
    // !function(t,n,o){var a="chmln",e="adminPreview",c="setup identify alias track clear set show on off custom help _data".split(" ");if(n[a]||(n[a]={}),n[a][e]&&(n[a][e]=!1),!n[a].root){n[a].accountToken=o,n[a].location=n.location.href.toString(),n[a].now=new Date;for(var s=0;s<c.length;s++)!function(){var t=n[a][c[s]+"_a"]=[];n[a][c[s]]=function(){t.push(arguments)}}();var i=t.createElement("script");i.src="https://fast.trychameleon.com/messo/"+o+"/messo.min.js",i.async=!0,t.head.appendChild(i)}}(document,window,"SDSi49K7BwQc7lRGhCOtLp1BzvxlN7VGRZzZvIUNmBePIu-1JQ3jl-Ceb2BabRyazdJyAc");
    // **This is an example script, don't forget to change the PLACEHOLDERS.**
    // Please confirm the user properties to be sent with your project owner.

    // Required:
    chmln.identify(<?php echo $user_data['user_ID']; ?>, {
        uid_hash: '<?php echo $user_data['uid_hash']; ?>',
        email: '<?php echo esc_js($user_info->user_email); ?>',
        name: '<?php echo esc_js($user_data['user_name']); ?>',
        companyname: '<?php echo esc_js($user_info->mepr_company_name); ?>',
        topics: '<?php echo esc_js(implode(", ", $user_data['interests_names'])); ?>',
        role: '<?php echo $user_data['role']; ?>',
        memberships: '<?php echo esc_js($user_data['memberships']); ?>',
        registration_date: '<?php echo $user_data['registration_date']; ?>',
        last_session: '<?php echo $user_data['last_session']; ?>',
        logins: '<?php echo $user_data['login_count']; ?>'
    });
    </script>
<?php } ?>
<script>

// ----- Lazy-load GoNative / OneSignal tags -----
function initGoNativeTags() {
    if (window.gonative_loaded) return;
    window.gonative_loaded = true;

    if (navigator.userAgent.indexOf('gonative') > -1) {
        setTimeout(function() {
            var tags = {
                name: '<?php echo esc_js($user_data['user_name']); ?>',
                email: '<?php echo esc_js($user_info->user_email); ?>',
                user_id: '<?php echo $user_data['user_ID']; ?>',
                subscription: '<?php echo esc_js($user_data['memberships']); ?>',
                topics: '<?php echo esc_js(implode(", ", $user_data['interests_names'])); ?>',
                last_session: '<?php echo esc_js($user_data['last_session']); ?>',
                account_type: '<?php echo esc_js($user_data['role']); ?>',
                registration_date: '<?php echo esc_js($user_data['registration_date']); ?>'
            };
            window.location.href = 'gonative://onesignal/tags/set?tags=' + encodeURIComponent(JSON.stringify(tags));
        }, 1000);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', initGoNativeTags, { once: true });
    document.addEventListener('scroll', initGoNativeTags, { once: true });
});
</script>

<?php endif; ?>

</head>
<?php $q = get_queried_object(); ?>
<body <?php body_class(''); ?> <?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'customer' ){ ?>data-theme-style="dark" <?php } ?> rel="<?php if ( is_404() ): echo 'notFound'; endif; ?>" <?php if(current_user_can('mepr-active')) { ?>id="logged-in"<?php } ?>>
    <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NHF4ZRS"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

	<?php get_template_part( 'templates/partials/_header' ); ?>
