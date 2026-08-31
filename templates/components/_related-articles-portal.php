<?php
$postTopic = null;

// Get Yoast primary term
$primary_id = yoast_get_primary_term_id('topic');
if ($primary_id) {
    $postTopic = get_term($primary_id);
}

// Fallback if no primary
if (!$postTopic) {
    $terms = get_the_terms(get_the_ID(), 'topic');
    if ($terms && !is_wp_error($terms)) {
        $postTopic = $terms[0];
    }
}
?>

<?php if ($postTopic) : ?>
<section class="topicGrid portal related">
    <div class="container">
        <div class="blockTitle">
            <h2>More in <?php echo esc_html($postTopic->name); ?></h2>
        </div>

        <div class="resources-column-container gap-16-40 three-column-container">

        <?php
        $args = [
            'no_found_rows'  => true,
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => [get_the_ID()],
            'tax_query' => [
                [
                    'taxonomy' => 'topic',
                    'field' => 'term_id',
                    'terms' => $postTopic->term_id
                ]
            ]
        ];

        $posts = new WP_Query($args);

        if ($posts->have_posts()) :
            while ($posts->have_posts()) : $posts->the_post();
                $extra_classes = 'one-third';
                include locate_template('/templates/components/_article-card.php');
            endwhile;
        endif;

        wp_reset_postdata();
        ?>

        </div>
    </div>
</section>
<?php endif; ?>