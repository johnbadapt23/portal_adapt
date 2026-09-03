<section class="title-banner dark-theme">
	<div class="container">
		<h1 class="header-large mobile-header-medium">Analyst Market Briefings</h1>
		<p><?php echo esc_html( get_field( 'webinar_listing_banner_subtitle', 'option' ) ); ?></p>
	</div>
</section>
<section class="register-listing dark-theme">
	<div class="filter-outer">
		<div class="container">
			<div class="register-filter">
				<span class="register-toggle all-button active">All</span>
				<span class="register-toggle upcoming-toggle-button">Upcoming</span>
				<span class="register-toggle past-button">Past Sessions</span>
			</div>
		</div>
	</div>
	<div class="container">
		
		<div class="register-listing-container upcoming active">
			<?php
			$today = wp_date('Ymd');
			$args = [
				'no_found_rows'  => true,
				'post_type' => 'registration',
				'meta_key'  => 'event_date',
			    'orderby'   => 'meta_value_num',
			    'order'     => 'ASC',
				'meta_query' => [
					[
						'key'     => 'event_date',
						'compare' => '>=',
						'value'   => $today,
					],
					[
						'key'     => 'button',
						'compare' => '==',
						'value'   => 'register',
					],
				],
			];
			?>
			<div class="upcoming-listing resources-column-container three-column-container gap-16-40">
				<?php $posts = new WP_Query( $args );
				if( $posts->have_posts() ): ?>
					<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
						<?php if ( have_rows( 'membership_ids' ) ) : ?>
						        <?php $counter = 0; ?>
						        <?php while ( have_rows( 'membership_ids' ) ) : the_row(); ?>
						            <?php if ( $counter == 0 ) {
						                $members = $members . get_sub_field( 'membership_id' );
						            } else {
						                $members = $members . ',' . get_sub_field( 'membership_id' );
						            } ?>
						           <?php $counter++; ?>
						       <?php endwhile; ?>
						   <?php endif; ?>
						   <?php if( empty(get_field('membership_ids')) || current_user_can('mepr-active','memberships:' . $members)) { ?>
							   <?php
							   $date_string = get_field('event_date');
							   $date = DateTime::createFromFormat('Ymd', $date_string);

							   ?>
							 <div class="article-column one-third">
								<a class="article-link" href="<?php echo esc_url(get_permalink($post_id)); ?>" id="<?php echo esc_attr($post_slug); ?>">
									<span class="article-inner">
										<span class="article-top">
											<span class="topic cat-tag-text">
												<?php echo esc_html( $date->format('l, j F, Y') ); ?>
											</span>
											<span class="article-title"><?php echo esc_html(get_the_title($post_id)); ?></span>
										</span>
										<span class="article-bottom">
											<span class="image-container">
												<?php $image = get_field( 'listing_image' ); ?>
												<?php if ($image) : ?>
													<span class="bg-container">
														<?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'class' => 'article-image', 'alt' => esc_attr( get_the_title( $post_id ) ) ] ); ?>
													</span>
												<?php endif; ?>
											</span>
											<span class="excerpt-link-container">
											<span class="excerpt">
												<?php $text = get_field( 'sub_title' ); ?>
												<?php echo esc_html($text); ?>
											</span>
											<span class="text-link red-text-link uppercase arrow-link">Register</span>
										</span>
									</span>
								</a>								  
							</div>


						<?php } ?>
						<?php unset($members); ?>
					<?php endwhile; ?>
				<?php endif;
				wp_reset_postdata();
				?>
				<?php
				$today = wp_date('Ymd');
				$args = [
					'no_found_rows'  => true,
					'post_type' => 'registration',
					'meta_key'  => 'event_date',
				    'orderby'   => 'meta_value_num',
				    'order'     => 'ASC',
					'meta_query' => [
						[
							'key'     => 'event_date',
							'compare' => '>=',
							'value'   => $today,
						],
						[
							'key'     => 'button',
							'compare' => '==',
							'value'   => 'upcoming',
						],
					],
				];
				?>
				<?php $posts = new WP_Query( $args );
				if( $posts->have_posts() ): ?>
					<?php while( $posts->have_posts() ) : $posts->the_post(); ?>
						<?php if ( have_rows( 'membership_ids' ) ) : ?>
						        <?php $counter = 0; ?>
						        <?php while ( have_rows( 'membership_ids' ) ) : the_row(); ?>
						            <?php if ( $counter == 0 ) {
						                $members = $members . get_sub_field( 'membership_id' );
						            } else {
						                $members = $members . ',' . get_sub_field( 'membership_id' );
						            } ?>
						           <?php $counter++; ?>
						       <?php endwhile; ?>
						   <?php endif; ?>
						   <?php if(current_user_can('mepr-active','memberships:' . $members)) { ?>
							   <?php
							   $date_string = get_field('event_date');
							   $date = DateTime::createFromFormat('Ymd', $date_string);

							   ?>
							   <div class="article-column one-third">
									<a class="article-link" href="<?php echo esc_url(get_permalink($post_id)); ?>" id="<?php echo esc_attr($post_slug); ?>">
										<span class="article-inner">
											<span class="article-top">
												<span class="topic cat-tag-text">
													<?php echo esc_html( $date->format('l, j F, Y') ); ?>
												</span>
												<span class="article-title"><?php echo esc_html(get_the_title($post_id)); ?></span>
											</span>
											<span class="article-bottom">
												<span class="image-container">
													<?php $image = get_field( 'listing_image' ); ?>
													<?php if ($image) : ?>
														<span class="bg-container">
															<?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'class' => 'article-image', 'alt' => esc_attr( get_the_title( $post_id ) ) ] ); ?>
														</span>
													<?php endif; ?>
												</span>
												<span class="excerpt-link-container">
												<span class="excerpt">
													<?php $text = get_field( 'sub_title' ); ?>
													<?php echo esc_html($text); ?>
												</span>
												<span class="text-link red-text-link uppercase arrow-link">Register</span>
											</span>
										</span>
									</a>								  
							   </div>
						<?php } ?>
						<?php unset($members); ?>
					<?php endwhile; ?>
				<?php endif;
				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php
// fetch past events

$posts_per_page = 18;
$soft_limit     = $posts_per_page * 5; // fetch extra to account for blocked posts
$today = wp_date('Ymd');

// First page load
$paged = 1;
$offset = 0; // first batch starts at 0

$args = [
    'post_type'      => 'post',
    'posts_per_page' => $soft_limit,
    'paged'          => $paged,
    'offset'         => $offset,
    'meta_key'       => 'replay_event_date',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'tax_query'      => [
        [
            'taxonomy' => 'filter-types',
            'field'    => 'slug',
            'terms'    => 'analyst-market-briefings',
        ],
    ],
    'meta_query' => [
        [
            'key'     => 'replay_event_date',
            'compare' => '<=',
            'value'   => $today,
        ],
    ],
];

$query = new WP_Query($args);
$shown = 0;
$visible_count = 0;

// Capture HTML for first batch
ob_start();

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();

        $can_access = true;
        if (function_exists('mepr_is_content_protected') && mepr_is_content_protected(get_the_ID())) {
            $can_access = mepr_current_user_can_access_post(get_the_ID());
        }

        if ($can_access) :
            $visible_count++;
            if ($shown < $posts_per_page) :
                $shown++;
                $extra_classes = 'one-third';
                include locate_template('/templates/components/_article-card.php');
            endif;
        endif;

    endwhile;
endif;

wp_reset_postdata();
$html = ob_get_clean();
?>

<div id="past-sessions-container" class="register-listing-container past-sessions active">
    <div class="upcoming-listing resources-column-container three-column-container gap-16-40">
        <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $html is captured via ob_get_clean() from included template partials (e.g. _article-card.php) that already escape their own output. ?>
        <?php echo $html; ?>
    </div>

    <?php if ($visible_count > $posts_per_page) : ?>
		<div class="past-nav-container page-navi-container post-pagination-container">
			<a class="std-button red-button small-button" id="load-more-past-sessions" data-offset="<?php echo esc_attr( $shown ); ?>" data-perpage="<?php echo esc_attr( $posts_per_page ); ?>">Load More</a>
		</div>
    <?php endif; ?>
</div>





	</div>
</section>
