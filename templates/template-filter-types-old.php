<?php

$today = date('Ymd');
$args = array(
    'post_type' => 'post',
    'meta_key'  => 'replay_event_date',
    'orderby'   => 'meta_value_num',
    'order'     => 'ASC',
    'tax_query' => array(
        'relation' => 'AND',
        array (
            'taxonomy' => 'filter-types',
            'field' => 'slug',
            'terms' => 'workshop-recordings',
            'operator' => 'IN',
        ),
    ),
    'meta_query' => array(
        array(
            'key'     => 'replay_event_date',
            'compare' => '<=',
            'value'   => $today,
        ),
    ),
);
global $displayed_posts;
$displayed_posts = array ();

$posts = new WP_Query( $args );
if( $posts->have_posts() ): ?>
    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
        <?php if(current_user_can('mepr_auth')) {?>
        <?php } else { ?>
            <?php $id = get_the_ID(); ?>
            <?php $displayed_posts[] = $id; ?>
        <?php } ?>
    <?php endwhile; ?>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
<?php wp_reset_query(); ?>


<?php
if($keyword != '') {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        's' => $keyword,
        'paged'=> $paged,
        'tax_query' => array(
            'relation' => 'AND',
            array (
                'taxonomy' => 'filter-types',
                'field' => 'slug',
                'terms'    => $q->slug
            )
        )
    );
} else {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'paged'=> $paged,
        'tax_query' => array(
            'relation' => 'AND',
            array (
                'taxonomy' => 'filter-types',
                'field' => 'slug',
                'terms'    => $q->slug
            )
        )
    );
}
?>

<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
<?php $args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'paged'=> $paged
); ?>
<?php $loop = new WP_Query( $args );
if ( $loop->have_posts() ) :
    while ( $loop->have_posts() ) : $loop->the_post();
?>
<?php if(current_user_can('mepr_auth')) {?>
<?php } else { ?>
    <?php $id = get_the_ID(); ?>
    <?php $displayed_posts[] = $id; ?>
<?php }?>

<?php endwhile; else : ?>
<?php endif; ?>
<?php wp_reset_postdata(); wp_reset_query();?>

<?php $q = get_queried_object(); ?>
<?php


$filterPresentationType = $_GET['presentations'];
if ($filterPresentationType != ''){
    if ($filterPresentationType == 'events') {
        $filterSubTopic = $_GET['events'];
    } else {
        $filterTopic = $_GET['filter-topic'];
        if ($q->slug == 'expert-presentations'){
        } else {
            $filterSubTopic = $_GET['filter-subtopic'];
        }
    }
} else {
    $filterTopic = $_GET['filter-topic'];
    if ($q->slug == 'expert-presentations'){
    } else {
        $filterSubTopic = $_GET['filter-subtopic'];
    }
}

$keyword = $_GET['searchWords'];
if ($filterPresentationType == 'events') {
} else {

}
?>

<!-- Main Type Loop -->
<!-- Data and Insights Listing Pages -->
<?php if( $q->slug == 'data-insights') { ?>
    <?php
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'paged'=> $paged,
        'tax_query'      => array(
            'relation' => 'AND',
            array (
                'taxonomy' => 'filter-types',
                'field' => 'slug',
                'terms'    => 'data-insights'
            )
        ),
    ); ?>
    <?php if($filterTopic  != '') {
        if(empty($filterTopic )){
        } else {
            if($filterTopic  == 'all') {
                $term_m = 'topic';
                $terms = get_terms( $term_m, array(
                    'hide_empty' => false,
                ) );

                $types = array();
                foreach( $terms as $term){
                    $types[] = $term->slug;
                }
                array_push($args['tax_query'],array(
                        'taxonomy' => 'topic',
                        'field' => 'slug',
                        'terms' => $types,
                        'operator' => 'IN'
                    )
                );
            } else {
                // print_r($filterType);
                array_push($args['tax_query'],array(
                        'taxonomy' => 'topic',
                        'field' => 'slug',
                        'terms' => $filterType,
                        'operator' => 'IN'
                    )
                );
            }
        }
    } ?>

    <?php $posts = new WP_Query( $args );
    $post_types = array ();
    if( $posts->have_posts() ): ?>
        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
            <?php if(get_the_terms( $post->ID, 'topic' )){
                $termsType = get_the_terms( $post->ID, 'topic' );

                foreach($termsType as $type) {
                    if($type->parent == 0){
                        if(!in_array($type,$post_types)){
                            $post_types[] = $type;
                        }
                    }
                }
            }
            ?>
        <?php endwhile; else : ?>
    <?php endif; ?>
    <?php wp_reset_query();?>
<?php if($filterTopic  != '') { ?>
    <?php if ( have_rows( 'banner', $q  ) ) : ?>
        <?php while ( have_rows( 'banner', $q  ) ) : the_row(); ?>
            <?php $banner_image = get_sub_field( 'banner_image' ); ?>
            <section class="eventsBanner topicBanner sectorBanner" style="background-image:url(<?php echo $banner_image['url']; ?>); background-size: cover; background-position: center;">
                <div class="container">
                    <span class="back-to-sectors topicFilter">
                        <a href="/filter-types/data-insights/" target="_self">Technology Trends</a>
                    </span>
                    <?php $term = get_term_by('slug', $filterTopic, 'topic'); ?>
                    <h1><?php echo $term->name;?></h1>
                </div>
            </section>
        <?php endwhile; ?>
    <?php else : ?>
    <?php // no rows found ?>
    <?php endif; ?>
    <section class="topicGrid portal sector-grid">
        <div class="container">
            <div id="loop" class="gridWrapper">
                <?php
                $args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 12,
                    'paged'=> $paged,
                    'tax_query'      => array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'filter-types',
                            'field' => 'slug',
                            'terms'    => 'data-insights'
                        )
                    ),
                ); ?>
                <?php if($filterTopic  != '') {
                    if(empty($filterTopic )){
                    } else {
                        if($filterTopic  == 'all') {
                            $term_m = 'topic';
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => false,
                            ) );

                            $types = array();
                            foreach( $terms as $term){
                                $types[] = $term->slug;
                            }
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'topic',
                                    'field' => 'slug',
                                    'terms' => $types,
                                    'operator' => 'IN'
                                )
                            );
                        } else {
                            // print_r($filterType);
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'topic',
                                    'field' => 'slug',
                                    'terms' => $filterTopic,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }
                } ?>

                <?php $posts = new WP_Query( $args );
                $post_types = array ();
                if( $posts->have_posts() ): ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <div href="<?php the_permalink(); ?>" class="item">
                            <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                <div class="bgContainer">
                                    <?php if ( get_field( 'listing_image') ) { ?>
                                        <?php $image = get_field( 'listing_image'); ?>
                                    <?php } else { ?>
                                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                            <?php $image = get_field( 'video_poster'); ?>
                                        <?php } else { ?>
                                            <?php $image = get_field( 'featured_image'); ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ( have_rows( 'preview_module' ) ) : ?>
                                       <?php while ( have_rows( 'preview_module' ) ) : the_row(); ?>
                                           <?php if ( have_rows( 'slider_images' ) ) : ?>
                                               <?php $imageCounter = 1; ?>
                                               <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                                   <?php if($imageCounter == 1){
                                                       $image = get_sub_field( 'image' );
                                                   }
                                                   $imageCounter++; ?>
                                               <?php endwhile; ?>
                                           <?php else : ?>
                                               <?php // no rows found ?>
                                           <?php endif; ?>
                                        <?php endwhile; ?>
                                        <img class="desktop" src="<?php echo $image['url']; ?>" />
                                        <span class="hover-container">
                                            <?php if ($imageCounter) { ?>
                                                <span class="slide-counter">1 OF <?php echo $imageCounter; ?></span>
                                            <?php } ?>
                                        <span>
                                    <?php else : ?>
                                        <img class="desktop" src="<?php echo $image; ?>" />
                                        <span class="hover-container">
                                        <span>
                                    <?php endif; ?>

                                </div>
                            </a>
                            <?php if (yoast_get_primary_term_id('topic')) {
                                $primary_term_type_id = yoast_get_primary_term_id('topic');
                                $postType = get_term( $primary_term_type_id );
                            } else {
                                if(get_the_terms( $post->ID, 'topic' )){
                                    $termsType = get_the_terms( $post->ID, 'topic' );
                                    foreach($termsType as $type) {
                                        $postType = $type;
                                    }
                                }
                            }?>
                            <div class="textContainer">
                                <span class="topicFilter">
                                    <a href="/filter-types/data-insights" class="topicFilterText">Technology Trends</a>
                                    <a href="/topic/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title"><?php the_title();?></a>
                                <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                <a href="<?php the_permalink(); ?>" class="button data-set-button">View Dataset</a>
                            </div>
                        </div>
                    <?php endwhile; else : ?>
                        <h3><?php esc_html_e( 'Sorry, no results found.' ); ?></h3>
                <?php endif; ?>
                <?php wp_pagenavi( array( 'query' => $posts ) ); ?>
                <?php wp_reset_query();?>
            </div>
        </div>
    </section>

    <?php } else { ?>
    <?php if ( have_rows( 'banner', $q  ) ) : ?>
		<?php while ( have_rows( 'banner', $q  ) ) : the_row(); ?>
			<?php $banner_image = get_sub_field( 'banner_image' ); ?>
			<section class="topicBanner sector-topic-banner" style="background-image:url(<?php echo $banner_image['url']; ?>);">
				<div class="container">
					<span class="breadcrumb-container">
						<a class="home-link" href="/" target="_self">Home</a>
						<span class="divider">/</span>
						<a class="home-link" href="/data-insights" target="_self">Data & Insights</a>
						<span class="divider">/</span>
						<span class="title">Technology Trends</span>
					</span>
					<span class="title-container">
						<h1 clas="h2-style"><?php echo get_sub_field( 'title' ); ?></h1>
						<span class="subtitle"><?php echo get_sub_field( 'sub_title' ); ?></span>
					</span>
				</div>
			</section>
		<?php endwhile; ?>
	<?php else : ?>
	<?php // no rows found ?>
	<?php endif; ?>
	<section class="explore-section">
		<div class="container">
			<?php if ( have_rows( 'filter_buttons',$q  ) ) : ?>
				<?php while ( have_rows( 'filter_buttons',$q  ) ) : the_row(); ?>
					<h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
					<div class="button-container">
                        <?php
                        $terms = $post_types;
                        ?>
						<?php foreach ( $terms as $term ): ?>
                            <?php if($term->slug == 'strategic-business-initiatives'){ ?>
                                <a class="sector-button button grey-button<?php if($filterTtopic == $term->slug) { ?> active<?php } ?>" href="<?php echo get_term_link($q); ?>?filter-topic=<?php echo $term->slug;?>">Strategic Initiatives</a>
                            <?php } else { ?>
                                <a class="sector-button button grey-button<?php if($filterTtopic == $term->slug) { ?> active<?php } ?>" href="<?php echo get_term_link($q); ?>?filter-topic=<?php echo $term->slug;?>"><?php echo $term->name;?></a>
                            <?php }?>
                        <?php endforeach; ?>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( have_rows( 'featured_article_slider', $q ) ) : ?>
		<section class="featured-article-slider-data technology-trends-slider">
			<div class="container">
				<?php while ( have_rows( 'featured_article_slider', $q ) ) : the_row(); ?>
					<div class="data-article-slider">
						<?php if ( have_rows( 'article' ) ) : ?>
							<?php while ( have_rows( 'article' ) ) : the_row(); ?>
								<?php $post_object = get_sub_field( 'post' ); ?>
								<?php if ( $post_object ): ?>
									<?php $post = $post_object; ?>
										<?php setup_postdata( $post ); ?>
										<div class="data-slide">
											<div class="slide-container">
												<span class="image-column right-column">
													<span class="v-wrap">
														<span class="v-box">
															<span class="slide-image-container">
																<span class="image-container">
																	<?php if ( have_rows( 'preview_module', $post ) ) : ?>
													                   <?php while ( have_rows( 'preview_module', $post ) ) : the_row(); ?>
													                       <?php if ( have_rows( 'slider_images') ) : ?>
													                           <?php $imageCounter = 1; ?>
													                           <?php while ( have_rows( 'slider_images') ) : the_row(); ?>
																				  	<?php if($imageCounter == 2){ ?>
																						<span class="bg-container offset-image-container">
  																						  <?php $offsetimage = get_sub_field( 'image'); ?>
  																						  <?php if ( $offsetimage ) { ?>
  																							  <img src="<?php echo $offsetimage['url']; ?>" alt="<?php echo $offsetimage['alt']; ?>" />
  																						  <?php } ?>
  																					  </span>
																					<?php } else if ($imageCounter == 1){ ?>
																						<span class="bg-container">
																						<?php $imageSlideOne = get_sub_field( 'image'); ?>
																						<?php if (  $imageSlideOne ) { ?>
																							<img src="<?php echo  $imageSlideOne['url']; ?>"/>
																						<?php } ?>
																					</span>
																					<?php } $imageCounter++; ?>
													                           <?php endwhile; ?>
													                       <?php else : ?>
													                       <?php endif; ?>
													                    <?php endwhile; ?>
													                <?php else : ?>
																		<?php if ( get_field( 'listing_image', $post) ) { ?>
										                                    <?php $image = get_field( 'listing_image', $post); ?>
										                                <?php } else { ?>
										                                    <?php if ( get_field ( 'featured_image_or_video', $post ) == 'video' ) { ?>
										                                        <?php $image = get_field( 'video_poster', $post); ?>
										                                    <?php } else { ?>
										                                        <?php $image = get_field( 'featured_image', $post); ?>
										                                    <?php } ?>
										                                <?php } ?>
																		<span class="bg-container">
																			<?php if (  $image ) { ?>
																				<img src="<?php echo  $image; ?>"/>
																			<?php } ?>
																		</span>
													                <?php endif; ?>
																</span>
															</span>
														</span>
													</span>
												</span>
												<div class="textContainer">
													<span class="v-wrap">
														<span class="v-box">
							                                <span class="topicFilter">
								                                    <?php if (yoast_get_primary_term_id('topic', $post)) {
							                                        $primary_term_type_id = yoast_get_primary_term_id('topic', $post);
							                                        $postType = get_term( $primary_term_type_id );
							                                    } else {
							                                        if(get_the_terms( $post->ID, 'topic' )){
							                                            $termsType = get_the_terms( $post->ID, 'topic' );
							                                            foreach($termsType as $type) {
							                                                $postType = $type;
							                                            }
							                                        }
							                                    }?>
							                                    <a href="/filter-types/data-insights" class="topicFilterText">Technology Trends</a>
							                                    <?php if($postType){?>
							                                        <a href="/topic/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
							                                    <?php } ?>
							                                </span>
							                                <a href="<?php the_permalink(); ?>" class="title"><?php echo get_the_title($post->ID); ?></a>
							                                <span class="excerpt">
																<?php if ( have_rows( 'preview_module', $post ) ) : ?>
												                   <?php while ( have_rows( 'preview_module', $post ) ) : the_row(); ?>
																	   <?php echo get_sub_field( 'overview_text' ); ?>
												                    <?php endwhile; ?>
												                <?php else : ?>
												                    <?php echo wp_trim_words( get_the_excerpt($post->ID), 25, '...' );?>
												                <?php endif; ?>
															</span>
															<a href="<?php echo get_permalink(); ?>" class="button red-button">View Dataset</a>
														</span>
													</span>
					                            </div>
											</div>
										</div>
									<?php wp_reset_postdata(); ?>
								<?php endif; ?>
							<?php endwhile; ?>
						<?php else : ?>
							<?php // no rows found ?>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		</section>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	<?php if ( have_rows( 'topic', $q ) ) : ?>
		<?php while ( have_rows( 'topic', $q ) ) : the_row(); ?>
			<?php get_template_part( 'templates/components/_topic-grid-portal-data' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
<?php } ?>
<?php } else { ?>
    <section class="topicBanner">
        <div class="imageSizeContainer">
            <div class="bgContainer">
    			<?php $banner_image = get_field( 'banner_image', $q ); ?>
                <img class="desktop" src="<?php echo $banner_image['url']; ?>" alt="<?php echo $banner_image['alt']; ?>" />
            </div>
            <div class="container">
                <span class="bannerBreadcrumbs">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb">Home</a><span class="divider">/</span><span class="breadcrumb"><?php echo $q->name; ?></span></a>
                </span>
                <h1><?php echo $q->name; ?></h1>
                <p><?php echo $q->description; ?></p>
            </div>
        </div>
    </section>
    <?php if ($q->slug != 'case-studies' && $q->slug != 'workshop-recordings'){ ?>
        <section class="filter<?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'workshop-recordings'){ ?> bg-dark<?php } ?>">
            <div class="container">
                <div class="formWrapper">
                    <form action="" name="postTypesFilter" class="postTypesFilter" method="get">
                        <span class="searchField">
                            <span class="search">
                                <input class="searchInput" type="text" name="searchWords" id="search" placeholder="Find in <?php echo $q->name; ?>" />
                                <input class="searchButton" type="image" alt="Search" <?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'workshop-recordings'){ ?>src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify-grey.svg" <?php } else { ?>src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" <?php }?>/>
                            </span>
                        </span>
                        <span class="filtersButtonMobile">
                            <?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'workshop-recordings'){ ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/filters-white.svg" alt="Filters" />
                            <?php } else { ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/filters.svg" alt="Filters" />
                            <?php } ?>
                            <span class="filterButtonText">Filters</span>
                        </span>
                        <span class="dropDowns">
                            <?php if ($q->slug == 'expert-presentations'){ ?>
                                <span class="subTopics">
                                    <label for="presentations">Explore Within</label>
                                    <select name="presentations" id="" onchange="this.form.submit()">
                                        <option value="topics" <?php if($filterPresentationType == '') { } else { if ($filterPresentationType == 'topics' ) { ?> selected <?php }}?>>Topics</option>
                                        <option value="events" <?php if($filterPresentationType == '') { } else { if ($filterPresentationType == 'events' ) { ?> selected <?php }}?>>Events</option>
                                    </select>
                                </span>
                            <?php } else { ?>
                                <span class="subTopics">
                                    <label for="filter-topic">Explore Within</label>
                                    <select name="filter-topic" id="" onchange="this.form.submit()">
                                        <option value="">All Topics</option>
                                        <?php $terms = array(); ?>
                                        <?php $loop = new WP_Query( $args ); ?>
                                        <?php if ( $loop->have_posts() ) : ?>
                                            <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                            <?php
                                                $topics = get_the_terms( $post->ID, 'topic' );
                                                if($topics){
                                                    foreach( $topics as $topic ){
                                                        if($topic-> parent == 0){
                                                            if( ! in_array( $topic, $terms )){
                                                                $terms[] = $topic;
                                                            }
                                                        } else {

                                                        }
                                                    }
                                                }
                                            ?>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                        <?php endif; ?>
                                        <?php wp_reset_query(); ?>
                                        <?php foreach($terms as $term) { ?>
                                            <option value="<?php echo $term->slug; ?>" <?php if($filterTopic == '') { } else { if ($term -> slug == $filterTopic ) { ?> selected <?php }}?>><?php echo $term -> name; ?></option>
                                        <?php } ?>
                                    </select>
                                </span>
                            <?php }?>

                            <?php if ($q->slug == 'expert-presentations') { ?>
                                <?php if ($filterPresentationType == 'events') { ?>
                                    <span class="filterBy">
                                        <label for="events">Filter By</label>
                                        <select name="events" id="" onchange="this.form.submit()">
                                            <option value="">All Events</option>
                                            <?php $terms = array(); ?>
                                            <?php $loop = new WP_Query( $args ); ?>
                                            <?php if ( $loop->have_posts() ) : ?>
                                                <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                                <?php
                                                    $events = get_the_terms( $post->ID, 'insights-event' );
                                                    if($events){
                                                        foreach( $events as $event ){
                                                            if($event-> parent == 0){
                                                                if( ! in_array( $event, $terms )){
                                                                    $terms[] = $event;
                                                                }
                                                            } else {

                                                            }
                                                        }
                                                    }
                                                ?>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                            <?php endif; ?>
                                            <?php wp_reset_query(); ?>
                                            <?php foreach($terms as $term) { ?>
                                                <option value="<?php echo $term->slug; ?>" <?php if($filterSubTopic == '') { } else { if ($term -> slug == $filterSubTopic ) { ?> selected <?php }}?>><?php echo $term -> name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                <?php } else { ?>
                                    <span class="filterBy">
                                        <label for="filter-topic">Filter By</label>
                                        <select name="filter-topic" id="" onchange="this.form.submit()">
                                            <option value="">All Topics</option>
                                            <?php $terms = array(); ?>
                                            <?php $loop = new WP_Query( $args ); ?>
                                            <?php if ( $loop->have_posts() ) : ?>
                                                <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                                <?php
                                                    $topics = get_the_terms( $post->ID, 'topic' );
                                                    if($topics){
                                                        foreach( $topics as $topic ){
                                                            if($topic-> parent == 0){
                                                                if( ! in_array( $topic, $terms )){
                                                                    $terms[] = $topic;
                                                                }
                                                            } else {

                                                            }
                                                        }
                                                    }
                                                ?>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                            <?php endif; ?>
                                            <?php wp_reset_query(); ?>
                                            <?php foreach($terms as $term) { ?>
                                                <option value="<?php echo $term->slug; ?>" <?php if($filterTopic == '') { } else { if ($term -> slug == $filterTopic ) { ?> selected <?php }}?>><?php echo $term -> name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                <?php } ?>
                            <?php } else { ?>
                                <span class="filterBy">
                                    <?php $subParent = get_term_by('slug', $filterTopic, 'topic' ); ?>
                                    <label for="filter-subtopic">Filter By</label>
                                    <select name="filter-subtopic" id="" onchange="this.form.submit()" <?php if($filterTopic == '') { ?>disabled <?php } ?>>
                                        <option value="">All Subtopics</option>
                                        <?php $terms = array(); ?>

                                        <?php $loop = new WP_Query( $args ); ?>
                                        <?php if ( $loop->have_posts() ) : ?>
                                            <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                            <?php

                                                $subTopics = get_the_terms( $post->ID, 'topic' );
                                                if($subTopics){
                                                    foreach( $subTopics as $subTopic ){

                                                        if($subTopic-> parent == $subParent->term_id ){
                                                            if( ! in_array( $subTopic, $terms )){
                                                                $terms[] = $subTopic;
                                                            }
                                                        }
                                                    }
                                                }
                                            ?>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                        <?php endif; ?>
                                        <?php wp_reset_query(); ?>
                                        <?php foreach($terms as $term) { ?>
                                            <option value="<?php echo $term->slug; ?>" <?php if($filterSubTopic == '') { } else { if ($term -> slug == $filterSubTopic ) { ?> selected <?php }}?>><?php echo $term -> name; ?></option>
                                        <?php } ?>
                                    </select>
                                </span>
                            <?php }?>
                        </span>
                        <span class="submitContainer">
                            <input type="submit" class="button filterButton" value="Filter" style="display: none;"/>
                            <?php if ($filterSubTopic != '' || $keyword != '' || $filterType != '' ){ ?>

                            <?php } ?>
                        </span>
                    </form>
                </div>
            </div>
        </section>
    <?php } ?>
    <?php if ( $keyword != '' || $filterTopic != '' || $filterSubTopic != '' ){ ?>
        <section class="portal postListing topicGrid subTopic<?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'workshop-recordings'){ ?> bg-dark<?php } ?>">
            <div class="container">
                <div class="blockTitle">
                    <h2>
                        <?php if($keyword != '') { ?>
                            Search Results for <span class="search-word"><?php echo $keyword; ?> <a class="clear-search" onclick="document.location.href=location.href+'&searchWords=';"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/reset-search.svg" width="15"/></a></span>
                        <?php } else { ?>
                            <?php if ($filterTopic != '') { ?>
                                <?php $term = get_term_by('slug', $filterTopic, 'topic'); ?>
                                <?php echo $term->name; ?>
                                <?php if ($filterSubTopic != '') { ?>
                                    <?php $term = get_term_by('slug', $filterSubTopic, 'topic'); ?>
                                    <span class="filtertypeTitle"> &nbsp;|&nbsp;&nbsp;<?php echo $term->name; ?></span>
                                <?php } ?>
                            <?php } else { ?>
                                <?php echo $q->name; ?>
                            <?php } ?>
                            <?php if ($q->slug == 'expert-presentations') { ?>
                                <?php if ($filterSubTopic != '') { ?>
                                    <?php if ($filterPresentationType == 'events') { ?>
                                        <?php $term = get_term_by('slug', $filterSubTopic, 'insights-event'); ?>
                                    <?php } else { ?>
                                        <?php $term = get_term_by('slug', $filterSubTopic, 'topic'); ?>
                                    <?php } ?>
                                    <span class="filtertypeTitle"> | <?php echo $term->name; ?></span>
                                <?php  } ?>
                            <?php } ?>
                        <?php } ?>
                    </h2>
                    <div class="gridView">
                        <?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'workshop-recordings'){ ?>
                            <span class="gridIcon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/grid-view-white.svg" alt="Grid View" /></span>
                            <span class="listIcon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/list-view-white.svg" alt="List View" /></span>
                        <?php } else { ?>
                            <span class="gridIcon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/grid-view.svg" alt="Grid View" /></span>
                            <span class="listIcon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/list-view.svg" alt="List View" /></span>
                        <?php }?>
                    </div>
                </div>
                <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                <div class="gridWrapper" id="loop">
                    <?php
                    if($keyword != '') {
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 9,
                            's' => $keyword,
                            'paged'=> $paged,
                            'tax_query' => array(
                                'relation' => 'AND',
                                array (
                                    'taxonomy' => 'filter-types',
                                    'field' => 'slug',
                                    'terms'    => $q->slug
                                ),
                            )
                        );
                    } else {
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 9,
                            'paged'=> $paged,
                            'tax_query' => array(
                                'relation' => 'AND',
                                array (
                                    'taxonomy' => 'filter-types',
                                    'field' => 'slug',
                                    'terms'    => $q->slug
                                ),
                            )
                        );
                    }

                    if($filterTopic != '') {
                        if(empty($filterTopic)){

                        } else {
                            if($filterTopic == 'all') {
                                $term_m = 'topic';
                                $terms = get_terms( $term_m, array(
                                    'hide_empty' => false,
                                ) );

                                $topics = array();
                                foreach( $terms as $term){
                                    $topics[] = $term->slug;
                                }
                                array_push($args['tax_query'],array(
                                        'taxonomy' => 'topic',
                                        'field' => 'slug',
                                        'terms' => $topics,
                                        'operator' => 'IN'
                                    )
                                );

                            } else {
                                // print_r($filterType);
                                array_push($args['tax_query'],array(
                                        'taxonomy' => 'topic',
                                        'field' => 'slug',
                                        'terms' => $filterTopic,
                                        'operator' => 'IN'
                                    )
                                );
                            }
                        }
                    }
                    if ($q->slug == 'expert-presentations'){
                        if($filterSubTopic != '') {
                            if(empty($filterSubTopic)){

                            } else {
                                if($filterSubTopic == 'all') {
                                    $term_m = 'insights-event';
                                    $terms = get_terms( $term_m, array(
                                        'hide_empty' => false,
                                    ) );

                                    $subTopics = array();
                                    foreach( $terms as $term){
                                        $subTopics[] = $term->slug;
                                    }
                                    array_push($args['tax_query'],array(
                                            'taxonomy' => 'insights-event',
                                            'field' => 'slug',
                                            'terms' => $subTopics,
                                            'operator' => 'IN'
                                        )
                                    );

                                } else {
                                    // print_r($filterType);
                                    array_push($args['tax_query'],array(
                                            'taxonomy' => 'insights-event',
                                            'field' => 'slug',
                                            'terms' => $filterSubTopic,
                                            'operator' => 'IN'
                                        )
                                    );
                                }
                            }
                        }
                    } else {
                        if($filterSubTopic != '') {
                            if(empty($filterSubTopic)){

                            } else {
                                if($filterSubTopic == 'all') {
                                    $term_m = 'topic';
                                    $terms = get_terms( $term_m, array(
                                        'hide_empty' => false,
                                    ) );

                                    $subTopics = array();
                                    foreach( $terms as $term){
                                        $subTopics[] = $term->slug;
                                    }
                                    array_push($args['tax_query'],array(
                                            'taxonomy' => 'topic',
                                            'field' => 'slug',
                                            'terms' => $subTopics,
                                            'operator' => 'IN'
                                        )
                                    );

                                } else {
                                    // print_r($filterType);
                                    array_push($args['tax_query'],array(
                                            'taxonomy' => 'topic',
                                            'field' => 'slug',
                                            'terms' => $filterSubTopic,
                                            'operator' => 'IN'
                                        )
                                    );
                                }
                            }
                        }
                    }

                    $posts = new WP_Query( $args );
                    if( $posts->have_posts() ): ?>
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                            <?php if(current_user_can('mepr_auth')) {?>
                                <div href="<?php the_permalink(); ?>" class="item">
                                <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                    <div class="bgContainer">
                                        <?php if ( get_field( 'listing_image') ) { ?>
                                            <?php $image = get_field( 'listing_image'); ?>
                                             <img class="desktop" src="<?php echo $image; ?>" />
                                        <?php } elseif ( get_field( 'video_image' )){  ?>
                                            <?php $video_image = get_field( 'video_image' ); ?>
                                            <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                        <?php } else { ?>
                                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                <?php $image = get_field( 'video_poster'); ?>
                                            <?php } else { ?>
                                                <?php $image = get_field( 'featured_image'); ?>
                                            <?php } ?>
                                            <img class="desktop" src="<?php echo $image; ?>" />
                                        <?php } ?>
                                    </div>
                                </a>
                                <div class="textContainer">
                                    <span class="topicFilter">
                                        <?php if (yoast_get_primary_term_id('topic')) {
                                            $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                            $postTopic = get_term( $primary_term_topic_id );
                                        } else {
                                            if(get_the_terms( $post->ID, 'topic' )){
                                                $terms = get_the_terms( $post->ID, 'topic' );
                                                foreach($terms as $term) {
                                                    $postTopic = $term;
                                                }
                                            }
                                        }?>

                                        <?php if (yoast_get_primary_term_id('filter-types')) {
                                            $primary_term_type_id = yoast_get_primary_term_id('filter-types');
                                            $postType = get_term( $primary_term_type_id );
                                        } else {
                                            if(get_the_terms( $post->ID, 'filter-types' )){
                                                $termsType = get_the_terms( $post->ID, 'filter-types' );
                                                foreach($termsType as $type) {
                                                    $postType = $type;
                                                }
                                            }
                                        }?>
                                        <?php if($postTopic){?>
                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                        <?php } ?>

                                            <a href="<?php echo get_term_link($q); ?>" class="topicFilterText"><?php echo $q->name; ?></a>

                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                    <?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'workshop-recordings'){ ?>
                                        <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?>  <?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                        <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php endwhile; ?>
                    <?php endif;?>

                    <?php wp_pagenavi( array( 'query' => $posts ) ); ?>
                    <?php wp_reset_postdata(); ?>
                <?php wp_reset_query(); ?>
                </div>
            </div>
        </section>
    <?php } else { ?>
        <?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'workshop-recordings'){ ?>
            <?php if ( have_rows( 'case_studies_content', $q ) ): ?>
               <?php while ( have_rows( 'case_studies_content', $q ) ) : the_row(); ?>
            		<?php if ( get_row_layout() == 'case_study_highlight_with_video' ) : ?>
                    <section class="caseStudiesFeaturedVideo portal bg-dark">
                        <div class="container">
                        <?php $post_object = get_sub_field( 'case_study' ); ?>
                        <?php if ( $post_object ): ?>
                    		<?php $post = $post_object; ?>
                    		<?php setup_postdata( $post ); ?>
                            <div class="item">
                                <a href="<?php the_permalink(); ?>" class="title mobile"><?php the_title(); ?></a>
                                <div class="imageSizeContainer">
                                    <a href="" class="postPlayBtn">
                                        <div class="bgContainer">
                                            <?php if ( get_field( 'listing_image') ) { ?>
                                                <?php $image = get_field( 'listing_image'); ?>
                                                 <img class="desktop" src="<?php echo $image; ?>" />
                                            <?php } elseif ( get_field( 'video_image' )){  ?>
                                                <?php $video_image = get_field( 'video_image' ); ?>
                                                <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                            <?php } else { ?>
                                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                    <?php $image = get_field( 'video_poster'); ?>
                                                <?php } else { ?>
                                                    <?php $image = get_field( 'featured_image'); ?>
                                                <?php } ?>
                                                <img class="desktop" src="<?php echo $image; ?>" />
                                            <?php } ?>
                                            <span class="watchIcon"></span>
                                        </div>
                                    </a>
                                </div>
                                <div class="videoPlayerContainer">
                                    <span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                                    <div class="videoWrapper">
                                        <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                            <source type="video/mp4" src="<?php echo get_field('featured_video_vimeo_code'); ?>" />
                                        </video>
                                    </div>
                                </div>
                                <div class="textContainer">
                                    <a href="<?php the_permalink(); ?>" class="title desktop"><?php the_title(); ?></a>
                                    <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                    <a href="<?php the_permalink(); ?>" class="readMore">Watch Video</a>
                                </div>
                            </div>
                            <?php wp_reset_postdata(); wp_reset_query();?>
                    	<?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endwhile; ?>
        <?php else: ?>
            <?php // no layouts found ?>
        <?php endif; ?>
        <?php } ?>
        <?php if ( have_rows( 'main_topic_content', $q ) ): ?>
        	<?php while ( have_rows( 'main_topic_content', $q ) ) : the_row(); ?>
        		<?php if ( get_row_layout() == 'six_grid_block' ) : ?>
                    <?php $sub_topic_grid_term = get_sub_field( 'sub_topic_grid' ); ?>
    	             <?php if ( $sub_topic_grid_term ): ?>
                        <?php $topic_term = get_sub_field( 'sub_topic_grid' );?>
                        <section class="topicGrid portal <?php echo get_sub_field( 'background_colour' );?>">
                            <div class="container">
                                <div class="blockTitle">
                                    <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                    <a href="<?php echo get_term_link($topic_term); ?>?searchWords=&subtopic=&filterby=<?php echo $q->slug; ?>" class="viewAll">View All</a>
                                </div>
                                <div class="gridWrapper">
                                    <?php
                                        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                                        $args = array(
                                            'post_type'      => 'post',
                                            'posts_per_page' => 6,
                                            'offset' => 0,
                                            'paged'=> $paged,
                                            'tax_query'      => array(
                                                'relation' => 'AND',
                                                array (
                                                    'taxonomy' => 'filter-types',
                                                    'field' => 'slug',
                                                    'terms'    => $q->slug
                                                ),
                                                array(
                                                    'taxonomy' => 'topic',
                                                    'field'    => 'slug',
                                                    'terms'    => $topic_term->slug
                                                )
                                            ),
                                        );

                                        $posts = new WP_Query( $args );
                                        if( $posts->have_posts() ): ?>
                                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                                <div class="item">
                                                    <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                                        <div class="bgContainer">
                                                            <?php if ( get_field( 'listing_image') ) { ?>
                                                                <?php $image = get_field( 'listing_image'); ?>
                                                                 <img class="desktop" src="<?php echo $image; ?>" />
                                                            <?php } elseif ( get_field( 'video_image' )){  ?>
                                                                <?php $video_image = get_field( 'video_image' ); ?>
                                                                <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                                            <?php } else { ?>
                                                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                                    <?php $image = get_field( 'video_poster'); ?>
                                                                <?php } else { ?>
                                                                    <?php $image = get_field( 'featured_image'); ?>
                                                                <?php } ?>
                                                                <img class="desktop" src="<?php echo $image; ?>" />
                                                            <?php } ?>
                                                        </div>
                                                    </a>
                                                    <div class="textContainer">
                                                        <span class="topicFilter">
                                                            <?php if (yoast_get_primary_term_id('topic')) {
                                                                $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                $postTopic = get_term( $primary_term_topic_id );
                                                            } else {
                                                                if(get_the_terms( $post->ID, 'topic' )){
                                                                    $terms = get_the_terms( $post->ID, 'topic' );
                                                                    foreach($terms as $term) {
                                                                        $postTopic = $term;
                                                                    }
                                                                }
                                                            }?>

                                                            <?php if (yoast_get_primary_term_id('filter-types')) {
                                                                $primary_term_type_id = yoast_get_primary_term_id('filter-types');
                                                                $postType = get_term( $primary_term_type_id );
                                                            } else {
                                                                if(get_the_terms( $post->ID, 'filter-types' )){
                                                                    $termsType = get_the_terms( $post->ID, 'filter-types' );
                                                                    foreach($termsType as $type) {
                                                                        $postType = $type;
                                                                    }
                                                                }
                                                            }?>
                                                            <?php if($postTopic){?>
                                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                                            <?php } ?>
                                                            <a href="<?php echo get_term_link($q); ?>" class="topicFilterText"><?php echo $q->name; ?></a>

                                                        </span>
                                                        <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                                        <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?>  <?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                                        <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php endif;?>
                                    <?php wp_reset_postdata(); ?>
                                    <?php wp_reset_query(); ?>
                                </div>
                            </div>
                        </section>
        	             <?php endif; ?>
                     <?php elseif ( get_row_layout() == 'feature_article' ) : ?>
                         <section class="caseStudiesFeaturedText portal <?php echo get_sub_field( 'background_colour' ); ?>">
                             <?php $post_object = get_sub_field( 'article' ); ?>
                             <div class="container">
                                 <div class="blockTitle<?php if(get_sub_field('title')){?> margin-top<?php } else { ?> no-border<?php }?>">
                                     <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                 </div>
                                 <?php if ( $post_object ): ?>
                             		<?php $post = $post_object; ?>
                             		<?php setup_postdata( $post ); ?>
                                     <div class="item">
                                         <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                             <div class="bgContainer">
                                                 <?php if ( get_field( 'listing_image') ) { ?>
                                                     <?php $image = get_field( 'listing_image'); ?>
                                                      <img class="desktop" src="<?php echo $image; ?>" />
                                                 <?php } elseif ( get_field( 'video_image' )){  ?>
                                                     <?php $video_image = get_field( 'video_image' ); ?>
                                                     <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                                 <?php } else { ?>
                                                     <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                         <?php $image = get_field( 'video_poster'); ?>
                                                     <?php } else { ?>
                                                         <?php $image = get_field( 'featured_image'); ?>
                                                     <?php } ?>
                                                     <img class="desktop" src="<?php echo $image; ?>" />
                                                 <?php } ?>
                                             </div>
                                         </a>
                                         <div class="textContainer">
                                             <span class="topicFilter">
                                                 <?php if (yoast_get_primary_term_id('topic')) {
                                                     $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                     $postTopic = get_term( $primary_term_topic_id );
                                                 } else {
                                                     if(get_the_terms( $post->ID, 'topic' )){
                                                         $terms = get_the_terms( $post->ID, 'topic' );
                                                         foreach($terms as $term) {
                                                             $postTopic = $term;
                                                         }
                                                     }
                                                 }?>

                                                 <?php if (yoast_get_primary_term_id('filter-types')) {
                                                     $primary_term_type_id = yoast_get_primary_term_id('filter-types');
                                                     $postType = get_term( $primary_term_type_id );
                                                 } else {
                                                     if(get_the_terms( $post->ID, 'filter-types' )){
                                                         $termsType = get_the_terms( $post->ID, 'filter-types' );
                                                         foreach($termsType as $type) {
                                                             $postType = $type;
                                                         }
                                                     }
                                                 }?>
                                                 <?php if($postTopic){?>
                                                     <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                                 <?php } ?>
                                             </span>
                                             <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                             <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?>  <?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                             <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                             <a href="<?php the_permalink(); ?>" class="readMore">Read More</a>
                                         </div>
                                     </div>
                                     <?php wp_reset_postdata(); ?>
                                 <?php endif; ?>
                             </div>
                         </section>

            		<?php elseif ( get_row_layout() == 'featured_grid_portal' ) : ?>
            			<?php $sub_topic_term = get_sub_field( 'sub_topic' ); ?>
            			<?php if ( $sub_topic_term ): ?>
                            <?php $topic_term = get_sub_field( 'sub_topic' );?>
                            <section class="featuredGrid portal mainTopic <?php echo get_sub_field( 'background_colour' );?>">
                                <div class="container">
                                    <div class="blockTitle">
                                        <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                        <a href="<?php echo get_term_link($topic_term); ?>?searchWords=&subtopic=&filterby=<?php echo $q->slug; ?>" class="viewAll">View All</a>
                                    </div>
                                    <div class="gridWrapper">
                                        <?php
                                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                                            $args = array(
                                                'post_type'      => 'post',
                                                'posts_per_page' => 2,
                                                'paged'=> $paged,
                                                'tax_query'      => array(
                                                    'relation' => 'AND',
                                                    array (
                                                        'taxonomy' => 'filter-types',
                                                        'field' => 'slug',
                                                        'terms'    => $q->slug
                                                    ),
                                                    array(
                                                        'taxonomy' => 'topic',
                                                        'field'    => 'slug',
                                                        'terms'    => $topic_term->slug
                                                    )
                                                ),
                                            );

                                            $posts = new WP_Query( $args );
                                            if( $posts->have_posts() ): ?>
                                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                                    <div href="<?php the_permalink(); ?>" class="item">
                                                        <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                                            <span class="overlayGradient"></span>
                                                            <div class="bgContainer">
                                                                <?php if ( get_field( 'listing_image') ) { ?>
                                                                    <?php $image = get_field( 'listing_image'); ?>
                                                                     <img class="desktop" src="<?php echo $image; ?>" />
                                                                <?php } elseif ( get_field( 'video_image' )){  ?>
                                                                    <?php $video_image = get_field( 'video_image' ); ?>
                                                                    <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                                                <?php } else { ?>
                                                                    <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                                        <?php $image = get_field( 'video_poster'); ?>
                                                                    <?php } else { ?>
                                                                        <?php $image = get_field( 'featured_image'); ?>
                                                                    <?php } ?>
                                                                    <img class="desktop" src="<?php echo $image; ?>" />
                                                                <?php } ?>
                                                            </div>
                                                        </a>
                                                        <div class="textContainer">
                                                            <span class="topicFilter">
                                                                <?php if (yoast_get_primary_term_id('topic')) {
                                                                    $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                    $postTopic = get_term( $primary_term_topic_id );
                                                                } else {
                                                                    if(get_the_terms( $post->ID, 'topic' )){
                                                                        $terms = get_the_terms( $post->ID, 'topic' );
                                                                        foreach($terms as $term) {
                                                                            $postTopic = $term;
                                                                        }
                                                                    }
                                                                }?>

                                                                <?php if (yoast_get_primary_term_id('filter-types')) {
                                                                    $primary_term_type_id = yoast_get_primary_term_id('filter-types');
                                                                    $postType = get_term( $primary_term_type_id );
                                                                } else {
                                                                    if(get_the_terms( $post->ID, 'filter-types' )){
                                                                        $termsType = get_the_terms( $post->ID, 'filter-types' );
                                                                        foreach($termsType as $type) {
                                                                            $postType = $type;
                                                                        }
                                                                    }
                                                                }?>
                                                                <?php if($postTopic){?>
                                                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                                                <?php } ?>

                                                                <a href="<?php echo get_term_link($q); ?>" class="topicFilterText"><?php echo $q->name; ?></a>

                                                            </span>
                                                            <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                                            <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?>  <?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                                            <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php endif;?>
                                            <?php wp_reset_postdata(); ?>
                                            <?php wp_reset_query(); ?>
                                    </div>
                                </div>
                            </section>
            			<?php endif; ?>
            		<?php endif; ?>
        	<?php endwhile; ?>
        <?php else: ?>
        	<?php // no layouts found ?>
        <?php endif; ?>
        <?php if ( have_rows( 'expert_presentation_landing_content', $q ) ): ?>
        	<?php while ( have_rows( 'expert_presentation_landing_content', $q ) ) : the_row(); ?>
                <?php if ($filterPresentationType == 'events') { ?>
                    <?php if ( get_row_layout() == 'events_landing' ) : ?>
                        <?php if ( have_rows( 'content' ) ): ?>
                            <?php while ( have_rows( 'content' ) ) : the_row(); ?>
                                <?php if ( get_row_layout() == 'event_presentation_slider' ) : ?>
                                <?php $topic_term = get_sub_field( 'event' ); ?>
                                <?php
                                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                                    $args = array(
                                        'post_type'      => 'post',
                                        'posts_per_page' => 6,
                                        'paged'=> $paged,
                                        'tax_query'      => array(
                                            'relation' => 'AND',
                                            array (
                                                'taxonomy' => 'insights-event',
                                                'field' => 'slug',
                                                'terms'    => $topic_term->slug
                                            ),
                                            array(
                                                'taxonomy' => 'filter-types',
                                                'field'    => 'slug',
                                                'terms'    => 'expert-presentations'
                                            )
                                        )
                                    );

                                    $posts = new WP_Query( $args );
                                    if( $posts->have_posts() ): ?>
                                    <section class="eventSlider portal bg-dark">
                                        <div class="container">
                                            <div class="blockTitle">
                                                <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                                <a href="?searchWords=&presentations=events&events=<?php echo $topic_term->slug ?>" class="viewAll">View All</a>
                                            </div>
                                        </div>

                                        <div class="slideContainer">
                                            <span class="leftslideCover"></span>
                                            <span class="rightslideCover"></span>
                                            <div class="slider">

                                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                                            <div class="item">
                                                                <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                                                    <div class="bgContainer">
                                                                        <?php if ( get_field( 'listing_image') ) { ?>
                                                                            <?php $image = get_field( 'listing_image'); ?>
                                                                             <img class="desktop" src="<?php echo $image; ?>" />
                                                                        <?php } elseif ( get_field( 'video_image' )){  ?>
                                                                            <?php $video_image = get_field( 'video_image' ); ?>
                                                                            <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                                                        <?php } else { ?>
                                                                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                                                <?php $image = get_field( 'video_poster'); ?>
                                                                            <?php } else { ?>
                                                                                <?php $image = get_field( 'featured_image'); ?>
                                                                            <?php } ?>
                                                                            <img class="desktop" src="<?php echo $image; ?>" />
                                                                        <?php } ?>
                                                                    </div>
                                                                    <span class="watchIcon"></span>
                                                                </a>
                                                                <div class="textContainer">
                                                                    <span class="topicFilter">
                                                                        <?php if (yoast_get_primary_term_id('topic')) {
                                                                            $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                            $postTopic = get_term( $primary_term_topic_id );
                                                                        } else {
                                                                            if(get_the_terms( $post->ID, 'topic' )){
                                                                                $terms = get_the_terms( $post->ID, 'topic' );
                                                                                foreach($terms as $term) {
                                                                                    $postTopic = $term;
                                                                                }
                                                                            }
                                                                        }?>
                                                                        <?php if (yoast_get_primary_term_id('insights-event')) {
                                                                            $primary_term_type_id = yoast_get_primary_term_id('insights-event');
                                                                            $postEvent = get_term( $primary_term_type_id );
                                                                        } else {
                                                                            if(get_the_terms( $post->ID, 'insights-event' )){
                                                                                $termsType = get_the_terms( $post->ID, 'insights-event' );
                                                                                foreach($termsType as $type) {
                                                                                    $postEvent = $type;
                                                                                }
                                                                            }
                                                                        }?>
                                                                        <?php if($postTopic){?>
                                                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                                                        <?php } ?>
                                                                        <?php if($postEvent){?>
                                                                            <a href="<?php echo get_term_link($postEvent); ?>" class="topicFilterText"><?php echo $postEvent->name; ?></a>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                                                </div>
                                                            </div>
                                                        <?php endwhile; ?>
                                            </div>
                                        </div>
                                    </section>
                                <?php endif;?>
                                <?php wp_reset_postdata(); ?>
                                <?php wp_reset_query(); ?>
                                <?php elseif ( get_row_layout() == 'two_column_featured_presentation' ) : ?>
                                    <?php $presentation = get_sub_field( 'presentation' ); ?>
                                    <?php if ( $presentation ): ?>
                                        <?php foreach ( $presentation as $post ):  ?>
                                            <?php setup_postdata ( $post ); ?>
                                            <section class="caseStudiesFeaturedVideo portal bg-white">
                                                <div class="container">
                                                    <div class="item">
                                                        <a href="<?php the_permalink(); ?>" class="title mobile"><?php the_title();?></a>
                                                        <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                                            <span class="overlayGradient"></span>
                                                            <div class="bgContainer">
                                                                <?php if ( get_field( 'listing_image') ) { ?>
                                                                    <?php $image = get_field( 'listing_image'); ?>
                                                                     <img class="desktop" src="<?php echo $image; ?>" />
                                                                <?php } elseif ( get_field( 'video_image' )){  ?>
                                                                    <?php $video_image = get_field( 'video_image' ); ?>
                                                                    <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                                                <?php } else { ?>
                                                                    <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                                        <?php $image = get_field( 'video_poster'); ?>
                                                                    <?php } else { ?>
                                                                        <?php $image = get_field( 'featured_image'); ?>
                                                                    <?php } ?>
                                                                    <img class="desktop" src="<?php echo $image; ?>" />
                                                                <?php } ?>
                                                                <span class="watchIcon"></span>
                                                            </div>
                                                        </a>
                                                        <div class="videoPlayerContainer">
                                                            <span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                                                            <div class="videoWrapper">
                                                                <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                                                    <source type="video/mp4" src="<?php echo get_field('featured_video_vimeo_code'); ?>" />
                                                                </video>
                                                            </div>
                                                        </div>
                                                        <div class="textContainer">
                                                            <a href="<?php the_permalink(); ?>" class="title desktop"><?php the_title();?></a>
                                                            <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                                            <a href="<?php the_permalink(); ?>" class="readMore">Watch Video</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        <?php endforeach; ?>
                                        <?php wp_reset_postdata(); ?>
                                        <?php wp_reset_query(); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <?php // no layouts found ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php } else { ?>
                    <?php if ( get_row_layout() == 'topics_landing' ) : ?>
            			<?php if ( have_rows( 'content' ) ): ?>
            				<?php while ( have_rows( 'content' ) ) : the_row(); ?>
            					<?php if ( get_row_layout() == 'featured_presentation' ) : ?>
            						<?php $presentation = get_sub_field( 'presentation' ); ?>
            						<?php if ( $presentation ): ?>
            							<?php foreach ( $presentation as $post ):  ?>
            								<?php setup_postdata ( $post ); ?>
                                                <section class="expertPresentationFeatured bg-dark">
                                                    <div class="container">
                                                        <div class="imageSizeContainer">
                                                            <span class="overlayGradient"></span>
                                                            <a href="<?php the_permalink(); ?>" target="_self" class="bgContainer">
                                                                <?php if ( get_field( 'listing_image') ) { ?>
                                                                    <?php $image = get_field( 'listing_image'); ?>
                                                                     <img class="desktop" src="<?php echo $image; ?>" />
                                                                <?php } elseif ( get_field( 'video_image' )){  ?>
                                                                    <?php $video_image = get_field( 'video_image' ); ?>
                                                                    <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                                                <?php } else { ?>
                                                                    <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                                        <?php $image = get_field( 'video_poster'); ?>
                                                                    <?php } else { ?>
                                                                        <?php $image = get_field( 'featured_image'); ?>
                                                                    <?php } ?>
                                                                    <img class="desktop" src="<?php echo $image; ?>" />
                                                                <?php } ?>
                                                            </a>
                                                            <span class="watchIcon"></span>
                                                            <span class="textContainer">
                                                                <span class="topicFilter">
                                                                    <?php if (yoast_get_primary_term_id('topic')) {
                                                                        $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                        $postTopic = get_term( $primary_term_topic_id );
                                                                    } else {
                                                                        if(get_the_terms( $post->ID, 'topic' )){
                                                                            $terms = get_the_terms( $post->ID, 'topic' );
                                                                            foreach($terms as $term) {
                                                                                $postTopic = $term;
                                                                            }
                                                                        }
                                                                    }?>

                                                                    <?php if (yoast_get_primary_term_id('filter-types')) {
                                                                        $primary_term_type_id = yoast_get_primary_term_id('filter-types');
                                                                        $postType = get_term( $primary_term_type_id );
                                                                    } else {
                                                                        if(get_the_terms( $post->ID, 'filter-types' )){
                                                                            $termsType = get_the_terms( $post->ID, 'filter-types' );
                                                                            foreach($termsType as $type) {
                                                                                $postType = $type;
                                                                            }
                                                                        }
                                                                    }?>
                                                                    <?php if($postTopic){?>
                                                                        <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                                                    <?php } ?>

                                                                        <a href="<?php echo get_term_link($q); ?>" class="topicFilterText"><?php echo $q->name; ?></a>

                                                                </span>
                                                                <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </section>
            							<?php endforeach; ?>

            						<?php endif; ?>
                                    <?php wp_reset_postdata(); ?>
                                    <?php wp_reset_query(); ?>
            					<?php elseif ( get_row_layout() == 'topic_presentation_slider' ) : ?>
                                    <section class="eventSlider portal bg-dark">
                                        <div class="container">
                                            <div class="blockTitle">
                                                <?php $topic_term = get_sub_field( 'topic' ); ?>
                                                <h2><?php echo get_sub_field( 'title' ); ?></h2>
                                                <a href="/topic/<?php echo $topic_term->slug ?>?searchWords=&subtopic=&filterby=<?php echo $q->slug; ?>" class="viewAll">View All</a>
                                            </div>
                                        </div>

                                        <div class="slideContainer">
                                            <span class="leftslideCover"></span>
                                            <span class="rightslideCover"></span>
                                            <div class="slider">
                                                <?php
                                                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                                                    $args = array(
                                                        'post_type'      => 'post',
                                                        'posts_per_page' => 6,
                                                        'paged'=> $paged,
                                                        'tax_query'      => array(
                                                            'relation' => 'AND',
                                                            array (
                                                                'taxonomy' => 'topic',
                                                                'field' => 'slug',
                                                                'terms'    => $topic_term->slug
                                                            ),
                                                            array(
                                                                'taxonomy' => 'filter-types',
                                                                'field'    => 'slug',
                                                                'terms'    => 'expert-presentations'
                                                            )
                                                        )
                                                    );

                                                    $posts = new WP_Query( $args );
                                                    if( $posts->have_posts() ): ?>
                                                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                                            <div class="item">
                                                                <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                                                    <div class="bgContainer">
                                                                        <?php if ( get_field( 'listing_image') ) { ?>
                                                                            <?php $image = get_field( 'listing_image'); ?>
                                                                             <img class="desktop" src="<?php echo $image; ?>" />
                                                                        <?php } elseif ( get_field( 'video_image' )){  ?>
                                                                            <?php $video_image = get_field( 'video_image' ); ?>
                                                                            <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                                                        <?php } else { ?>
                                                                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                                                <?php $image = get_field( 'video_poster'); ?>
                                                                            <?php } else { ?>
                                                                                <?php $image = get_field( 'featured_image'); ?>
                                                                            <?php } ?>
                                                                            <img class="desktop" src="<?php echo $image; ?>" />
                                                                        <?php } ?>
                                                                    </div>
                                                                    <span class="watchIcon"></span>
                                                                </a>
                                                                <div class="textContainer">
                                                                    <span class="topicFilter">
                                                                        <?php if (yoast_get_primary_term_id('topic')) {
                                                                            $primary_term_topic_id = yoast_get_primary_term_id('topic');
                                                                            $postTopic = get_term( $primary_term_topic_id );
                                                                        } else {
                                                                            if(get_the_terms( $post->ID, 'topic' )){
                                                                                $terms = get_the_terms( $post->ID, 'topic' );
                                                                                foreach($terms as $term) {
                                                                                    $postTopic = $term;
                                                                                }
                                                                            }
                                                                        }?>
                                                                        <?php if (yoast_get_primary_term_id('insights-event')) {
                                                                            $primary_term_type_id = yoast_get_primary_term_id('insights-event');
                                                                            $postEvent = get_term( $primary_term_type_id );
                                                                        } else {
                                                                            if(get_the_terms( $post->ID, 'insights-event' )){
                                                                                $termsType = get_the_terms( $post->ID, 'insights-event' );
                                                                                foreach($termsType as $type) {
                                                                                    $postEvent = $type;
                                                                                }
                                                                            }
                                                                        }?>
                                                                        <?php if($postTopic){?>
                                                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
                                                                        <?php } ?>
                                                                        <?php if($postEvent){?>
                                                                            <a href="<?php echo get_term_link($postEvent); ?>" class="topicFilterText"><?php echo $postEvent->name; ?></a>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                                                </div>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    <?php endif;?>
                                                    <?php wp_reset_postdata(); ?>
                                                    <?php wp_reset_query(); ?>
                                            </div>
                                        </div>
                                    </section>
            					<?php elseif ( get_row_layout() == 'two_column_featured_presentation' ) : ?>
            						<?php $presentation = get_sub_field( 'presentation' ); ?>
            						<?php if ( $presentation ): ?>
            							<?php foreach ( $presentation as $post ):  ?>
            								<?php setup_postdata ( $post ); ?>
                                            <section class="caseStudiesFeaturedVideo portal bg-white">
                                                <div class="container">
                                                    <div class="item">
                                                        <a href="<?php the_permalink(); ?>" class="title mobile"><?php the_title();?></a>
                                                        <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                                            <span class="overlayGradient"></span>
                                                            <div class="bgContainer">
                                                                <?php if ( get_field( 'listing_image') ) { ?>
                                                                    <?php $image = get_field( 'listing_image'); ?>
                                                                     <img class="desktop" src="<?php echo $image; ?>" />
                                                                <?php } elseif ( get_field( 'video_image' )){  ?>
                                                                    <?php $video_image = get_field( 'video_image' ); ?>
                                                                    <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
                                                                <?php } else { ?>
                                                                    <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                                        <?php $image = get_field( 'video_poster'); ?>
                                                                    <?php } else { ?>
                                                                        <?php $image = get_field( 'featured_image'); ?>
                                                                    <?php } ?>
                                                                    <img class="desktop" src="<?php echo $image; ?>" />
                                                                <?php } ?>
                                                                <span class="watchIcon"></span>
                                                            </div>
                                                        </a>
                                                        <div class="videoPlayerContainer">
                                                            <span class="closeVideo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close-grey.svg" alt="Close" width="25"/></span>
                                                            <div class="videoWrapper">
                                                                <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                                                    <source type="video/mp4" src="<?php echo get_field('featured_video_vimeo_code'); ?>" />
                                                                </video>
                                                            </div>
                                                        </div>
                                                        <div class="textContainer">
                                                            <a href="<?php the_permalink(); ?>" class="title desktop"><?php the_title();?></a>
                                                            <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                                            <a href="<?php the_permalink(); ?>" class="readMore">Watch Video</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
            							<?php endforeach; ?>

            						<?php endif; ?>
                                    <?php wp_reset_postdata(); ?>
                                    <?php wp_reset_query(); ?>
            					<?php endif; ?>
            				<?php endwhile; ?>
            			<?php else: ?>
            				<?php // no layouts found ?>
            			<?php endif; ?>
                    <?php endif; ?>
                <?php } ?>
        	<?php endwhile; ?>
        <?php else: ?>
        	<?php // no layouts found ?>
        <?php endif; ?>
    <?php } ?>
<?php } ?>
