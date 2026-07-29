<?php
/**
 * Partner card template
 * Variables expected:
 * $post_id
 * $partnerslug
 */
$post_slug   = get_post_field('post_name', $post_id);
$term_slugs = wp_get_post_terms($post_id, 'capabilities', [
    'fields' => 'slugs',
]);

if (is_wp_error($term_slugs) || empty($term_slugs)) {
    $term_slugs = [];
}
$filter_slugs = implode(' ', $term_slugs);
$partner_terms = wp_get_post_terms($post_id, 'partner-type', ['fields' => 'slugs']);
if (is_wp_error($partner_terms) || empty($partner_terms)) {
    $partnerslug = '';
} else {
    $partnerslug = $partner_terms[0];
}
?>

<div class="one-third speaker-item one-quarter column <?php echo esc_attr($partnerslug); ?>"
     data-filter="<?php echo esc_attr($filter_slugs); ?>">

    <a class="slide-out-bio" href="<?php echo get_permalink($post_id); ?>" id="<?php echo esc_attr($post_slug); ?>">

        <?php if ($partnerslug == 'advisors') : ?>

            <span class="image-container">
                <span class="bg-container">
                    <?php
                    $img = get_field('listing_avatar', $post_id);
                    $url = is_array($img) ? $img['url'] : (is_int($img) ? wp_get_attachment_image_url($img, 'full') : $img);
                    if ($url): ?>
                        <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>" />
                    <?php endif; ?>
                </span>

                <span class="text-container mobile-hide">
                    <h5 class="labelMedium"><?php echo get_the_title($post_id); ?></h5>
                    <span class="role labelXSmall"><?php echo esc_html(get_field('role', $post_id)); ?></span>
                    <span class="text-link red-text learn-more red-arrow-link arrow-link bold-link uppercase">Learn More</span>
                </span>
            </span>

            <span class="text-container desktop-hide">
                <span class="labelMedium text-black"><?php echo get_the_title($post_id); ?></span>
                <span class="role labelXSmall text-black"><?php echo esc_html(get_field('role', $post_id)); ?></span>
                <span class="text-link red-text  red-arrow-link arrow-link bold-link uppercase">Learn More</span>
            </span>

        <?php else : ?>

            <span class="image-container">
                <span class="bg-container">
                    <?php
                    $img = get_field('listing_icon', $post_id);
                    $url = is_array($img) ? $img['url'] : (is_int($img) ? wp_get_attachment_image_url($img, 'full') : $img);
                    if ($url): ?>
                        <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>" />
                    <?php endif; ?>
                </span>
                <span class="text-container mobile-hide">
                    <h5 class="labelMedium"><?php echo get_the_title($post_id); ?></h5>
                </span>
            </span>

            <span class="text-container desktop-hide">
                <span class="labelMedium text-black"><?php echo get_the_title($post_id); ?></span>
                <span class="text-link red-text red-arrow-link arrow-link bold-link uppercase">Learn More</span>
            </span>

        <?php endif; ?>

    </a>

</div>
