<?php
/**
 * Template Name: Favourites Template
 */

get_header();
?>

<?php
/**
* Get an array of User Favorites
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @return array
*/

?>
<?php

$favorites = get_user_favorites();
$favouritesCount = get_user_favorites_count();
$filterMin = get_field('favourite_filtering_minimum', 'options');

$filterType = $_GET['filterby'];
$keyword = $_GET['searchWords'];
$sortBy = $_GET['orderby'];
$sort = $_GET['order'];

if($sortBy != '') {
    if(empty($sortBy)){
        if(empty($sortPosts)){
            $orderBy = 'menu_order';
        } else {
        }
    } else {
        $orderBy = $sortBy;
    }
} else {
    if(empty($sortPosts)){
        $order = 'ASC';
    }
}

if($sort != '') {
    if(empty($sort)){
        if(empty($sortPosts)){
            $orderBy = 'menu_order';
        } else {
        }
    } else {
        $order = $sort;
    }
} else {
    if(empty($sortPosts)){
        $order = 'ASC';
    }
}

if ($sortPosts != '') {
    if(empty($sortPosts)){
        $orderBy = 'menu_order';
        $order = 'ASC';
    } else {
        if ($sortPosts == 'newest') {
            $orderBy = 'date';
            $order = 'DESC';
        } else if ($sortPosts == 'oldest') {
            $orderBy = 'date';
            $order = 'ASC';
        } else if ($sortPosts == 'titleASC') {
            $orderBy = 'title';
            $order = 'ASC';
        } else if ($sortPosts == 'titleDESC') {
            $orderBy = 'title';
            $order = 'DESC';
        }
    }
} else {
    if(empty($sortBy)){
        $orderBy = 'menu_order';
    } else {
    }

    if(empty($sort)){
        $order = 'ASC';
    } else {
    }
}

if ( $favorites ) : // This is important: if an empty array is passed into the WP_Query parameters, all posts will be returned
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1; // If you want to include pagination
    $favorites_query = new WP_Query(array(
        'post_type' => 'post', // If you have multiple post types, pass an array
        'posts_per_page' => -1,
        'post__in' => $favorites,
        'paged' => $paged // If you want to include pagination, and have a specific posts_per_page set
    ));

    if($keyword != '') {
        $args = array(
            's' => $keyword,
            'post_type' => 'post', // If you have multiple post types, pass an array
            'posts_per_page' => -1,
            'post__in' => $favorites,
            'paged' => $paged // If you want to include pagination, and have a specific posts_per_page set
        );
    } else {
        $args = array(
            'post_type' => 'post', // If you have multiple post types, pass an array
            'posts_per_page' => -1,
            'post__in' => $favorites,
            'paged' => $paged // If you want to include pagination, and have a specific posts_per_page set
        );
    }
else :
// No Favorites
endif;
?>

<main id="main" role="main" class="main-topic saved-posts">
    <section class="topicGrid portal savedInsights">
        <div class="container">
            <div class="blockTitle">
                <h2>Saved Insights</h2><span class="articleCount">(<?php echo do_shortcode( '[user_favorite_count]' ); ?>)</span>
            </div>
        </div>
    </section>
    <?php if($favouritesCount >= $filterMin) { ?>
        <section class="filter bg-white">
        <div class="container">
            <div class="formWrapper">
                <form action="" name="postTopicsFilter" class="postTopicsFilter" method="get">
                    <span class="searchField">
                        <span class="search">
                            <input class="searchInput" type="text" name="searchWords" id="search" placeholder="Find saved Insights" />
                            <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" />
                        </span>
                    </span>
                    <span class="filtersButtonMobile">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/filters.svg" width="14" height="14" loading="lazy" alt="Filters" />
                        <span class="filterButtonText">Filters</span>
                    </span>
                    <span class="dropDowns">
                        <span class="subTopics">
                            <label for="filterby">Filter By</label>
                            <select name="filterby" id="" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                <?php $terms = array(); ?>
                                <?php $loop = new WP_Query( $args ); ?>
                                <?php if ( $loop->have_posts() ) : ?>
                                    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                    <?php
                                        $types = get_the_terms( $post->ID, 'filter-types' );
                                        if($types){
                                            foreach( $types as $type ){
                                                if( ! in_array( $type, $terms )){
                                                    $terms[] = $type;
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
                        <span class="filterBy">
                            <label for="sort-posts">Order By</label>
                            <select class="dropdown-class" name="sort-posts" id="sortbox" onchange="document.location.href=location.href+this.options[this.selectedIndex].value;">
                                <?php
                                if ($sortBy != '') {
                                    if ($sortBy == 'date') {
                                        if ($sort == 'DESC') {
                                            $sortValue = 'Newest';
                                        } else {
                                            $sortValue = 'Oldest';
                                        }
                                    }
                                    if ($sortBy == 'title') {
                                        if ($sort == 'DESC') {
                                            $sortValue = 'Title Z - A';
                                        } else {
                                            $sortValue = 'Title A - Z';
                                        }
                                    }
                                } else {
                                    $sortValue = 'Newest';
                                }
                                ?>
                                <option <?php if ($sortBy == 'date' && $sort == 'DESC') { ?>selected="selected"<?php } ?> value="<?php if ($sortBy != '' || $filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>orderby=date&order=DESC">Newest</option>
                                <option <?php if ($sortBy == 'date' && $sort == 'ASC') { ?>selected="selected"<?php } ?> value="<?php if ($sortBy != '' || $filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>orderby=date&order=ASC">Oldest</option>
                                <option <?php if ($sortBy == 'title' && $sort == 'ASC') { ?>selected="selected"<?php } ?> value="<?php if ($sortBy != '' || $filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>orderby=title&order=ASC">Title A - Z</option>
                                <option <?php if ($sortBy == 'title' && $sort == 'DESC') { ?>selected="selected"<?php } ?> value="<?php if ($sortBy != '' || $filterTopics != '' || $filterType != '' || $keyword != '' ) { ?>&<?php } else if (isset($keyword)) { ?>&<?php } else { ?>?<?php } ?>orderby=title&order=DESC">Title Z - A</option>
                            </select>
                        </span>
                    </span>
                    <span class="submitContainer">
                        <input type="submit" class="button filterButton" value="Filter" style="display: none;"/>
                    </span>
                </form>
            </div>
        </div>
    </section>
    <?php } ?>
    <section class="topicGrid portal savedInsights results">
        <div class="container">
            <?php if($filterType != '') { ?>
                <?php $term = get_term_by('slug', $filterType, 'filter-types'); ?>
                <div class="blockTitle">
                    <h2><?php echo $term->name; ?></h2>
                </div>
            <?php } ?>
            <div class="gridWrapper">
            <?php
                    $favorites = get_user_favorites();

                    if ( $favorites ) : // This is important: if an empty array is passed into the WP_Query parameters, all posts will be returned
                        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1; // If you want to include pagination
                        if($keyword != '') {
                            if($filterType != '') {
                                $args = array(
                                    'post_type' => 'post', // If you have multiple post types, pass an array
                                    'posts_per_page' => -1,
                                    's' => $keyword,
                                    'post__in' => $favorites,
                                    'tax_query' => array(
                                        array (
                                            'taxonomy' => 'filter-types',
                                            'field' => 'slug',
                                            'terms' => $filterType,
                                            'operator' => 'IN'
                                        )
                                    ),
                                    'paged' => $paged, // If you want to include pagination, and have a specific posts_per_page set
                                    'orderby'   => $orderBy,
                                    'order' => $order
                                );
                            } else {
                                $args = array(
                                    'post_type' => 'post', // If you have multiple post types, pass an array
                                    'posts_per_page' => -1,
                                    's' => $keyword,
                                    'post__in' => $favorites,

                                    'paged' => $paged, // If you want to include pagination, and have a specific posts_per_page set
                                    'orderby'   => $orderBy,
                                    'order' => $order
                                );
                            }
                        } else {
                            if($filterType != '') {
                                $args = array(
                                    'post_type' => 'post', // If you have multiple post types, pass an array
                                    'posts_per_page' => -1,
                                    'post__in' => $favorites,
                                    'tax_query' => array(
                                        array (
                                            'taxonomy' => 'filter-types',
                                            'field' => 'slug',
                                            'terms' => $filterType,
                                            'operator' => 'IN'
                                        )
                                    ),
                                    'paged' => $paged, // If you want to include pagination, and have a specific posts_per_page set
                                    'orderby'   => $orderBy,
                                    'order' => $order
                                );
                            } else {
                                $args = array(
                                    'post_type' => 'post', // If you have multiple post types, pass an array
                                    'posts_per_page' => -1,
                                    'post__in' => $favorites,

                                    'paged' => $paged, // If you want to include pagination, and have a specific posts_per_page set
                                    'orderby'   => $orderBy,
                                    'order' => $order
                                );
                            }

                        }

                        if($filterType != '') {
                            if(empty($filterType)){

                            } else {
                                if($filterType == 'all') {
                                    $term_m = 'filter-types';
                                    $terms = get_terms( $term_m, array(
                                        'hide_empty' => false,
                                    ) );

                                    $types = array();
                                    foreach( $terms as $term){
                                        $types[] = $term->slug;
                                    }
                                    array_push($args['tax_query'],array(
                                            'taxonomy' => 'filter-types',
                                            'field' => 'slug',
                                            'terms' => $types,
                                            'operator' => 'IN'
                                        )
                                    );

                                } else {
                                    // print_r($filterType);
                                    array_push($args['tax_query'],array(
                                            'taxonomy' => 'filter-types',
                                            'field' => 'slug',
                                            'terms' => $filterType,
                                            'operator' => 'IN'
                                        )
                                    );
                                }
                            }
                        }
                        $favorites_query= new WP_Query( $args );

                        if ( $favorites_query->have_posts() ) : while ( $favorites_query->have_posts() ) : $favorites_query->the_post(); ?>
                            <div class="item">
                                <div class="imageSizeContainer">
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
                                        <?php } else { ?>
                                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                <?php $image = get_field( 'video_poster'); ?>
                                                <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
                                            <?php } else { ?>
                                                <?php $image = get_field( 'featured_image'); ?>
                                                <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <span class="removePostButton">
                                        <?php echo get_favorites_button(); ?>
                                    </span>
                                </div>
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
                                </div>
                            </div>
                        <?php endwhile;
                        endif; wp_reset_postdata();
                    else :
                    ?>
                    <?php
                    endif;
                ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
