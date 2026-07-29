<?php
/**
 * Template Name: Insights Template
 */

get_header();
?>

<main id="main" role="main" class="events">
<?php
$filterCat = $_GET['categories'];
$filterType = $_GET['types'];
$filterEvent = $_GET['events'];
$filterDuration = $_GET['duration'];
$sortBy = $_GET['orderby'];
$sort = $_GET['order'];
?>
    <section class="postHeader">
        <div class="container">
            <div class="headerWrapper">
                <h1><?php echo get_field( 'title_text', 'option' ); ?></h1>
                <span class="subTitle">
                    <?php echo get_field( 'sub_title', 'option' ); ?>
                </span>
                <span class="memberLogin">
                    <span class="title">Members Area</span>
                    <?php if(current_user_can('mepr-active')) { ?>
                        <span class="log-out-link"><?php echo do_shortcode("[mepr-login-link]"); ?></span>
                    <?php } else { ?>
                        <a class="button" href="/members-login" target="_self">Login</a>
                        <a class="text" href="/members" target="_self">Register</a>
                    <?php } ?>
                </span>

            </div>
            <div class="filter">
                <div class="search">
                    <form action="/" method="get">
                        <input class="searchInput" type="text" name="s" id="search" placeholder="SEARCH FOR.. OR FILTER" value="" />
                        <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" />
                        <input type="hidden" value="1" name="sentence" />
                        <input type="hidden" value="post" name="post_type" id="post_type" />
                    </form>
                </div>
                <div class="formContainer">
                    <form action="" name="insightsFilter" class="insightsFilter<?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?> active<?php } ?>" method="get">
                        <span class="spacer"></span>
                        <span class="categories<?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?> active<?php } ?>">
                            <span class="more<?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?> active<?php } ?>"><?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?> Close<?php } else { ?>More<?php } ?></span>
                            <?php
                            $term_m = 'category';
                            ?>
                            <?php
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => true,
                            ) );
                            ?>
                            <?php foreach($terms as $term) { ?>
                                <span class="checkboxButton">
                                    <label>
                                      <input type="checkbox" name="categories[]" <?php if($filterCat == '') { } else { if (in_array( $term -> slug, $filterCat )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                    </label>
                                </span>
                            <?php } ?>
                        </span>
                        <span class="types">
                            <span class="title">Type</span>
                            <?php
                            $term_m = 'article-type';
                            ?>
                            <?php
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => true,
                            ) );
                            ?>
                            <?php foreach($terms as $term) { ?>
                                <span class="checkboxButton <?php echo $term -> slug; ?>">
                                    <label>
                                      <input type="checkbox" name="types[]" <?php if($filterType == '') { } else { if (in_array( $term -> slug, $filterType )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                    </label>
                                </span>
                            <?php } ?>
                        </span>
                        <span class="events">
                            <span class="title">Event</span>
                            <?php
                            $term_m = 'insights-event';
                            ?>
                            <?php
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => true,
                            ) );
                            ?>
                            <?php foreach($terms as $term) { ?>
                                <span class="checkboxButton">
                                    <label>
                                      <input type="checkbox" name="events[]" <?php if($filterEvent == '') { } else { if (in_array( $term -> slug, $filterEvent )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                    </label>
                                </span>
                            <?php } ?>
                        </span>
                        <span class="duration insights-duration">
                            <span class="title">Duration</span>
                            <?php
                            $term_m = 'article-duration';
                            ?>
                            <?php
                            $terms = get_terms( $term_m, array(
                                'hide_empty' => true,
                            ) );
                            ?>
                            <?php foreach($terms as $term) { ?>
                                <span class="checkboxButton">
                                    <label>
                                      <input type="checkbox" name="duration[]" <?php if($filterDuration == '') { } else { if (in_array( $term -> slug, $filterDuration )) { ?> checked <?php }}?> value="<?php echo $term -> slug; ?>"><span class="checkbox-text"><?php echo $term -> name; ?></span>
                                    </label>
                                </span>
                            <?php } ?>
                        </span>
                        <?php
                        if ($sortBy != '') { ?>
                            <span class="hidden" style="visibility: hidden; opacity: 0;">
                                <input type="checkbox" name="orderby" value="<?php echo $sortBy; ?>" checked>
                                <input type="checkbox" name="order" value="<?php echo $sort; ?>" checked>
                            </span>
                        <?php } ?>
                        <span class="submitContainer">
                            <input type="submit" class="button filterButton" value="Filter" />
                            <?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?>
                                <a class="clear" href="/adapt-insights">Clear</a>
                            <?php } ?>
                        </span>
                    </form>
                </div>
                <div class="sortContainer">
                    <div id="sortby">
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
                                $sortValue = 'Sort By:';
                            }
                            ?>
                            <option value=""><?php echo $sortValue ?></option>
                            <?php if ($sortBy == 'date' && $sort == 'DESC') { ?>
                            <?php } else { ?>
                                <option value="<?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?>&<?php } else { ?>?<?php } ?>orderby=date&order=DESC">Newest</option>
                            <?php } ?>
                            <?php if ($sortBy == 'date' && $sort == 'ASC') { ?>
                            <?php } else { ?>
                                <option value="<?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?>&<?php } else { ?>?<?php } ?>orderby=date&order=ASC">Oldest</option>
                            <?php } ?>
                            <?php if ($sortBy == 'title' && $sort == 'ASC') { ?>
                            <?php } else { ?>
                                <option value="<?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?>&<?php } else { ?>?<?php } ?>orderby=title&order=ASC">Title A - Z</option>
                            <?php } ?>
                            <?php if ($sortBy == 'title' && $sort == 'DESC') { ?>
                            <?php } else { ?>
                                <option value="<?php if ($filterCat != '' || $filterEvent != '' || $filterDuration != '' || $filterType != '' ) { ?>&<?php } else { ?>?<?php } ?>orderby=title&order=DESC">Title Z - A</option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php  $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="blogWrapper">
        <div class="container">
            <div id="loop" class="grid">
                <?php $counter = -1; ?>
                <?php
                    if($sortBy != '') {
                        $orderBy = $sortBy;
                    } else {
                        $orderBy = 'date';
                    }

                    if($sort != '') {
                        $order = $sort;
                    } else {
                        $order = 'DESC';
                    }
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 9,
                        'paged'=> $paged,
                        'tax_query' => array(
                            'relation' => 'OR'
                        ),
                        'orderby'   => $orderBy,
                        'order' => $order
                    );

                    if($filterCat != '') {
                        foreach( $filterCat as $filter){
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'category',
                                    'field' => 'slug',
                                    'terms' => $filter,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }

                    if($filterType != '') {
                        foreach( $filterType as $type){
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'article-type',
                                    'field' => 'slug',
                                    'terms' => $type,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }

                    if($filterDuration != '') {
                        foreach( $filterDuration as $duration){
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'article-duration',
                                    'field' => 'slug',
                                    'terms' => $duration,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }

                    if($filterEvent != '') {
                        foreach( $filterEvent as $event){
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'insights-event',
                                    'field' => 'slug',
                                    'terms' => $event,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }

                    $loop = new WP_Query( $args );
                    while ( $loop->have_posts() ) : $loop->the_post();
                ?>

                <?php if(current_user_can('mepr_auth')) {?>
                    <!--  User has access to post -->

                    <span class="postLink layout<?php echo $counter; ?>">
                        <div class="linkWrapper">
                            <span class="iconWrapper">
                                <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                    <span class="podcast"></span>
                                <?php } ?>
                                <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                    <span class="watchIcon"></span>
                                <?php } ?>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="imageContainer">
                                <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                    <div class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
                                    </div>
                                <?php } else { ?>
                                    <div class="image" style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');">
                                    </div>
                                <?php } ?>
                            </a>
                            <span class="blogText">
                                <span class="postDetails">
                                    <span class="info">
                                        <span class="date">
                                            <?php echo get_the_date('d.m.Y'); ?>
                                        </span>
                                        <span class="readTime">
                                            <?php echo get_field( 'read_time' ); ?> read
                                        </span>
                                    </span>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                <span class="excerpt">
                                    <?php echo the_excerpt(); ?>
                                </span>

                                <?php
                                    $post_tags = get_the_tags();
                                ?>

                                <?php if ( $post_tags ) { ?>
                                    <div class="tags">
                                        <?php foreach( $post_tags as $tag ) { ?>
                                            <span>
                                                <?php echo '#' . $tag->name  ; ?>
                                            </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </span>
                        </div>
                    </span>

                <?php } else { ?>
                    <!--  User has no access to post -->
                        <span class="postLink layout<?php echo $counter; ?> memberContentLock">
                            <span class="overlay">
                                <span class="exclusiveContent">
                                    <span class="overlayText"><?php echo get_field('member_content_post_overlay_text', 'option'); ?></span>
                                    <span class="registerLogin">
                                        <a class="registerLink" href="/members">Register</a>
                                        <span>or</span>
                                        <a class="loginLink" href="/members-login">Login</a>
                                    </span>
                                </span>
                            </span>
                            <div class="linkWrapper">
                                <span class="iconWrapper">
                                    <?php if ( get_field ( 'podcast_available' ) == 'yes' ) { ?>
                                        <span class="podcast"></span>
                                    <?php } ?>
                                    <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                        <span class="watchIcon"></span>
                                    <?php } ?>

                                </span>
                                <div class="imageContainer">
                                    <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                        <a href="<?php the_permalink(); ?>" class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php the_permalink(); ?>" class="image" style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');">
                                        </a>
                                    <?php } ?>
                                </div>
                                <span class="blogText">
                                    <span class="postDetails">
                                        <span class="info">
                                            <span class="date">
                                                <?php echo get_the_date('d.m.Y'); ?>
                                            </span>
                                            <span class="readTime">
                                                <?php echo get_field( 'read_time' ); ?> read
                                            </span>
                                        </span>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="articleLink"><?php echo the_title(); ?></a>
                                    <span class="excerpt">
                                        <?php echo the_excerpt(); ?>
                                    </span>

                                    <?php
                                        $post_tags = get_the_tags();
                                    ?>

                                    <?php if ( $post_tags ) { ?>
                                        <div class="tags">
                                            <?php foreach( $post_tags as $tag ) { ?>
                                                <span>
                                                    <?php echo '#' . $tag->name  ; ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </span>
                            </div>
                        </span>

                <?php }?>

                <?php $counter++; ?>

                <?php endwhile; ?>

                </div>

                <?php if( $loop->max_num_pages > 1 ): ?>
                    <span class="pagWrapper">
                        <span id="pagination" class="button-container"><?php next_posts_link( 'Load More', $loop->max_num_pages ); ?></span>
                    </span>
                <?php endif; ?>

            <div class="formTrigger">
                <?php if ( get_field ( 'form_title', 'option' ) ) { ?>
                    <h2><?php echo get_field( 'form_title', 'option' ); ?></h2>
                <?php } ?>
                <?php if ( get_field ( 'form_subtitle', 'option' ) ) { ?>
                    <h3><?php echo get_field( 'form_subtitle', 'option' ); ?></h3>
                <?php } ?>
                <?php if ( get_field ( 'call_to_action_text', 'option' ) ) { ?>
                    <h4><?php echo get_field( 'call_to_action_text', 'option' ); ?></h4>
                <?php } ?>

                <a class="logoBlockLink button popup-modal" href="#form"><?php echo get_field( 'button_text', 'option' ); ?></a>
            </div>

        </div>
    </section>
</main>

<?php get_footer(); ?>
