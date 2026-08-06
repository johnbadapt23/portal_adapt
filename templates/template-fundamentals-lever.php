
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
            'taxonomy' => 'replay',
            'field' => 'slug',
            'terms' => 'replay_event_date',
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
<?php endif;
wp_reset_postdata(); wp_reset_query();?>

<?php $q = get_queried_object(); ?>
<?php

$user_info = wp_get_current_user();

$first_name = $user_info->first_name;
$interests = $user_info->mepr_interests;

$filterType = $_GET['filterby'];
$keyword = $_GET['searchWords'];
?>
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
                'taxonomy' => 'fundamentals-lever',
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
                'taxonomy' => 'fundamentals-lever',
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

<div class="other-dropdown fundamentals-dropdown">
    <div class="container">
        <?php
        $term_m = 'fundamentals-lever';
        ?>
        <?php
        $terms = get_terms( $term_m, array(
            'hide_empty' => false,
            'parent' => 0
        ) );
        ?>
        <?php foreach($terms as $term) { ?>
            <span class="other-fundamentals-items other-items"><a href="<?php echo get_term_link($term); ?>" target="_self">
            <?php $icon = get_field( 'icon', $term ); ?>
            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'], 'width' => '24' ) ); ?><?php echo $term->name; ?> 
            </a></span>
        <?php } 
        ?>
    </div>
    <span class="close-dropdown"></span>
</div>

<!-- Main Topic Loop -->
<section class="topicBanner">
    <div class="imageSizeContainer">
        <div class="bgContainer">
            <?php $banner_image = get_field( 'banner_image', $q ); ?>
            <?php echo wp_get_attachment_image( $banner_image['ID'], 'full', false, array( 'alt' => $banner_image['alt'], 'class' => 'desktop' ) ); ?>
        </div>
        <div class="container">
            <span class="bannerBreadcrumbs">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="breadcrumb">Home</a><span class="divider">/</span><a href="<?php echo esc_url( home_url( '/' ) ); ?>evr" class="breadcrumb">EVR</a><span class="divider">/</span><span class="breadcrumb"><?php echo $q->name; ?></span></a>
            </span>
            <span class="title-filter-container"><h1><?php echo $q->name; ?></h1><span class="dropdown-button">Other fundamentals</span></span>
            <p><?php echo $q->description; ?></p>               
        </div>
    </div>
</section>
<section class="filter">
    <div class="container">
        <div class="formWrapper">
            <form action="" name="postTopicsFilter" class="postTopicsFilter" method="get">
                <span class="searchField">
                    <span class="search">
                        <input class="searchInput" type="text" name="searchWords" id="search" placeholder="Find in <?php echo $q->name; ?>" />
                        <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" />
                    </span>
                </span>
                <span class="filtersButtonMobile">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/filters.svg" alt="Filters" />
                    <span class="filterButtonText">Filters</span>
                </span>
                <span class="dropDowns">                        
                    <span class="filterBy">
                        <label for="filterby">Explore By</label>
                        <select name="filterby" id="" onchange="this.form.submit()">
                            <option value="">All Stages</option>
                            <?php $terms = array(); ?>
                            <?php $loop = new WP_Query( $args ); ?>
                            <?php if ( $loop->have_posts() ) : ?>
                                <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                <?php
                                    $types = get_the_terms( $post->ID, 'evr-maturity-stage' );
                                    if($types){
                                        foreach( $types as $type ){
                                            if( $type-> parent == 0){
                                                if( ! in_array( $type, $terms )){
                                                    $terms[] = $type;
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
                                <option value="<?php echo $term->slug; ?>" <?php if($filterType == '') { } else { if ($term -> slug == $filterType ) { ?> selected <?php }}?>><?php echo $term -> name; ?></option>
                            <?php } ?>
                        </select>
                    </span>
                </span>
                <span class="submitContainer">
                    <input type="submit" class="button filterButton" value="Filter" style="display: none;"/>
                    <?php if ( $keyword != '' || $filterType != '' ){ ?>

                    <?php } ?>
                </span>
            </form>
        </div>
    </div>
</section>
<?php if ( $keyword != '' || $filterType != '' ){ ?>
    <section class="portal postListing topicGrid evr-grid filteredTopics">
        <div class="container">
            <div class="blockTitle">
                <h2>
                    <?php if($keyword != '') { ?>
                        Search Results for <span class="search-word"><?php echo $keyword; ?> <a class="clear-search" onclick="document.location.href=location.href+'&searchWords=';"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/reset-search.svg" width="15"/></a></span>
                    <?php } else { ?>
                        <?php if ($filterType != '') { ?>
                            <?php $term = get_term_by('slug', $filterType, 'evr-maturity-stage'); ?>
                            <?php $icon = get_field( 'icon', $term ); ?>
                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'], 'width' => '24' ) ); ?><?php echo $term->name; ?>                                
                        <?php } else { ?>
                            <?php $icon = get_field( 'icon', $q ); ?>
                            <?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'], 'width' => '24' ) ); ?><?php echo $q->name; ?>
                        <?php } ?>
                    <?php } ?>
                </h2>
                <?php if($keyword != '') { ?>
                <?php } else { ?>
                    <?php if ($filterType != '') { ?>
                        <?php $term = get_term_by('slug', $filterType, 'evr-maturity-stage'); ?>
                        <p><?php echo $term->description; ?></p>                                
                    <?php } else { ?>
                        <p><?php echo $q->description; ?></p> 
                    <?php } ?>
                <?php } ?>                
                <div class="gridView">
                    <span class="gridIcon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/grid-view.svg" alt="Grid View" /></span>
                    <span class="listIcon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/list-view.svg" alt="List View" /></span>
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
                                'taxonomy' => 'fundamentals-lever',
                                'field' => 'slug',
                                'terms'    => $q->slug
                            )
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
                                'taxonomy' => 'fundamentals-lever',
                                'field' => 'slug',
                                'terms'    => $q->slug
                            )
                        )
                    );
                }

                if($filterType != '') {
                    if(empty($filterType)){

                    } else {
                        if($filterType == 'all') {
                            $term_m = 'evr-maturity-stage';
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => false,
                            ) );

                            $types = array();
                            foreach( $terms as $term){
                                $types[] = $term->slug;
                            }
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'evr-maturity-stage',
                                    'field' => 'slug',
                                    'terms' => $types,
                                    'operator' => 'IN'
                                )
                            );

                        } else {
                            // print_r($filterType);
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'evr-maturity-stage',
                                    'field' => 'slug',
                                    'terms' => $filterType,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }
                }

                $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                        <div href="<?php the_permalink(); ?>" class="item">
                            <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                <div class="bgContainer">
                                    <?php if ( get_field( 'listing_image') ) { ?>
                                        <?php $image = get_field( 'listing_image'); ?>
                                            <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
                                    <?php } elseif ( get_field( 'video_image' )){  ?>
                                        <?php $video_image = get_field( 'video_image' ); ?>
                                        <?php echo wp_get_attachment_image( $video_image['ID'], 'full', false, array( 'alt' => $video_image['alt'], 'class' => 'desktop' ) ); ?>
                                    <?php } else { ?>
                                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                            <?php $image = get_field( 'video_poster'); ?>
                                        <?php } else { ?>
                                            <?php $image = get_field( 'featured_image'); ?>
                                        <?php } ?>
                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
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
                                    <?php if($postType){?>

                                            <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>

                                    <?php } ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                <span class="dateReadTime"><span class="dateRead"><?php echo get_the_date('M j, Y'); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif;?>
                <?php wp_pagenavi( array( 'query' => $posts ) ); ?>
            <?php wp_reset_query(); ?>
            </div>
        </div>
    </section>
<?php } else { ?>
    <?php if ( have_rows( 'main_topic_content', $q ) ): ?>
        <?php while ( have_rows( 'main_topic_content', $q ) ) : the_row(); ?>
            <?php if ( get_row_layout() == 'three_grid_block' ) : ?>
                <?php $topic_term = get_sub_field( 'stages' );?>
                <section class="topicGrid evr-grid portal <?php echo get_sub_field( 'background_colour' );?>">
                    <div class="container">
                        <div class="blockTitle">
                            <?php $icon = get_field( 'icon', $topic_term); ?>
                            <h2><?php echo wp_get_attachment_image( $icon['ID'], 'full', false, array( 'alt' => $icon['alt'], 'width' => '24' ) ); ?><?php echo get_sub_field( 'title' ); ?></h2>
                            <p><?php echo $topic_term->description; ?></p>
                            <a href="<?php echo get_term_link($topic_term); ?>" class="viewAll">View All</a>
                        </div>
                        <div class="gridWrapper">
                            <?php
                                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                                $args = array(
                                    'post_type'      => 'post',
                                    'posts_per_page' => 3,
                                    'offset' => 0,
                                    'paged'=> $paged,
                                    'tax_query'      => array(
                                        'relation' => 'AND',
                                        array (
                                            'taxonomy' => 'fundamentals-lever',
                                            'field' => 'slug',
                                            'terms'    => $q->slug
                                        ),
                                        array(
                                            'taxonomy' => 'evr-maturity-stage',
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
                                                            <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
                                                    <?php } elseif ( get_field( 'video_image' )){  ?>
                                                        <?php $video_image = get_field( 'video_image' ); ?>
                                                        <?php echo wp_get_attachment_image( $video_image['ID'], 'full', false, array( 'alt' => $video_image['alt'], 'class' => 'desktop' ) ); ?>
                                                    <?php } else { ?>
                                                        <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                            <?php $image = get_field( 'video_poster'); ?>
                                                        <?php } else { ?>
                                                            <?php $image = get_field( 'featured_image'); ?>
                                                        <?php } ?>
                                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
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
                                                <span class="dateReadTime"><span class="dateRead"><?php echo get_the_date('M j, Y'); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
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
            <?php elseif ( get_row_layout() == 'feature_article' ) : ?>
                <section class="caseStudiesFeaturedText evr-featured portal <?php echo get_sub_field( 'background_colour' ); ?>">
                    <?php $post_object = get_sub_field( 'article' ); ?>
                    <div class="container">                        
                        <?php if ( $post_object ): ?>
                        <?php $post = $post_object; ?>
                        <?php setup_postdata( $post ); ?>
                            <div class="item">
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
                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText red-text"><?php echo $postTopic->name; ?></a>
                                        <?php } ?>
                                         <?php if($postType){?>
                                            <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText red-text"><?php echo $postType->name; ?></a>
                                        <?php } ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                                    <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                                    <a href="<?php the_permalink(); ?>" class="stdBtn red red-button">Read More</a>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                                    <div class="bgContainer">
                                        <?php if ( get_field( 'listing_image') ) { ?>
                                            <?php $image = get_field( 'listing_image'); ?>
                                            <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
                                        <?php } elseif ( get_field( 'video_image' )){  ?>
                                            <?php $video_image = get_field( 'video_image' ); ?>
                                            <?php echo wp_get_attachment_image( $video_image['ID'], 'full', false, array( 'alt' => $video_image['alt'], 'class' => 'desktop' ) ); ?>
                                        <?php } else { ?>
                                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                <?php $image = get_field( 'video_poster'); ?>
                                            <?php } else { ?>
                                                <?php $image = get_field( 'featured_image'); ?>
                                            <?php } ?>
                                            <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
                                        <?php } ?>
                                    </div>
                                </a>                               
                            </div>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                </section> 
            <?php endif; ?>              
        <?php endwhile; ?>
    <?php else: ?>
        <?php // no layouts found ?>
    <?php endif; ?>
<?php } ?>
