<?php
/**
 * Template Name: Update Interests
 */

get_header();

$user_info = wp_get_current_user();

$first_name = $user_info->first_name;
$interests = $user_info->mepr_interests;
?>

<main id="main" role="main" class="home">
    <section class="updateInterests">
        <div class="container">
            <div class="introductionTextContainer">
                <h1><?php echo get_field( 'title' ); ?></h1>
                <span class="introductionText">
                    <?php echo get_field( 'introduction_text' ); ?>
                </span>
            </div>
            <div class="topicsContainer">
                <span class="hidden" id="responseText"></span>
                <div class="formContainer">
                    <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="updateUserInterests">
                        <?php
                        $term_m = 'topic';
                        ?>
                        <?php
                        $terms = get_terms( $term_m, array(
                            'hide_empty' => false,
                            'parent' => 0
                        ) );
                        ?>
                        <?php foreach($terms as $term) { ?>
                            <span class="topic">
                                <span class="topicTitle"><h2><?php echo $term->name; ?></h2></span>
                                <span class="topicIntroduction"><?php echo $term->description; ?></span>
                                <span class="checkbox-container">
                                    <?php if ($interests){ ?>
                                        <input type="checkbox" id="checkbox<?php echo $term->slug;?>" value="on" name="mepr_interests[<?php echo $term->slug; ?>]" <?php if('on'==$interests[$term->slug]){ ?>checked="checked"<?php }?>/>
                                        <label for="checkbox<?php echo $term->slug;?>" ><?php if('on'==$interests[$term->slug]){ ?>Following<?php } else { ?>Follow<?php } ?></label>
                                    <?php } else { ?>
                                        <input type="checkbox" id="checkbox<?php echo $term->slug;?>" value="on" name="mepr_interests[<?php echo $term->slug; ?>]"/>
                                        <label for="checkbox<?php echo $term->slug;?>">Follow</label>
                                    <?php } ?>
                                </span>
                            </span>
                        <?php } ?>
                        <input type="hidden" name="action" value="myfilter">
                        <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'adapt_ajax_nonce' ) ); ?>">
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>


<?php get_footer(); ?>
