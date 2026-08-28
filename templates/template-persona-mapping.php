<?php $filterType = $_GET['type'];
$preview = $_GET['new'];
global $displayed_posts;
$displayed_posts = array ();
$post_types = array ();
?>
<?php $q = get_queried_object(); ?>
<?php if($preview == 'yes') { ?> 
    <?php 
        $persona_title = get_field('persona_title', $q);
        $taxonomy_name = $q->name; 
    ?>
    <section class="topicBanner sector-topic-banner no-image taxonomy-banner">
        <div class="container">
            <span class="breadcrumb-container">
                <a class="home-link" href="/" target="_self">Home</a>
                <span class="divider">/</span>
                <span class="title"><?php echo get_field( 'persona_title', $q ); ?></span>
            </span>
            <span class="title-container">
                <h1 clas="h2-style">
                    <?php 
                        if ($persona_title === $taxonomy_name) {
                            echo get_field( 'persona_title', $q ); 
                        } else {
                            echo get_field( 'persona_title', $q ). ' (' . $q->name . ')';
                        }
                    ?>
                </h1>
            </span>
        </div>
    </section>
    <?php $post_object = get_field( 'featured_article', $q ); ?>
        <?php if ( $post_object ): ?>
            <section class="featured-article-slider-data featured-article-slider-persona">
                <div class="container">
                    <div class="data-article-slider">
                        <?php $post = $post_object; ?>
                        <?php setup_postdata( $post ); ?>
                        <div class="data-slide background-light-grey">
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
                                                                            <?php echo wp_get_attachment_image( $offsetimage['ID'], 'full', false, array( 'alt' => $offsetimage['alt'] ) ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                    <?php } else if ($imageCounter == 1){ ?>
                                                                        <span class="bg-container">
                                                                        <?php $imageSlideOne = get_sub_field( 'image'); ?>
                                                                        <?php if (  $imageSlideOne ) { ?>
                                                                            <?php echo wp_get_attachment_image( $imageSlideOne['ID'], 'full', false, array( 'alt' => '' ) ); ?>
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
                                                                <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '' ) );
								} else {
									echo '<img src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
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
                                                    <?php if (yoast_get_primary_term_id('sector-analysis', $post)) {
                                                    $primary_term_type_id = yoast_get_primary_term_id('sector-analysis', $post);
                                                    $postType = get_term( $primary_term_type_id );
                                                } else {
                                                    if(get_the_terms( $post->ID, 'sector-analysis' )){
                                                        $termsType = get_the_terms( $post->ID, 'sector-analysis' );
                                                        foreach($termsType as $type) {
                                                            $postType = $type;
                                                        }
                                                    }
                                                }?>
                                                <a href="/data-insights/sector-analysis/" class="topicFilterText">Sector Analysis</a>
                                                <?php if($postType){?>
                                                    <a href="/data-insights/sector-analysis/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
                                                <?php } ?>
                                            </span>
                                            <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title($post->ID) ); ?></a>
                                            <span class="dateReadTime"><?php echo esc_html( get_the_date('M j, Y') ); ?></span>
                                            <span class="excerpt">
                                                <?php if ( have_rows( 'preview_module', $post ) ) : ?>
                                                <?php while ( have_rows( 'preview_module', $post ) ) : the_row(); ?>
                                                    <?php echo get_sub_field( 'overview_text' ); ?>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <?php echo esc_html( wp_trim_words( get_the_excerpt($post->ID), 25, '...' ) );?>
                                                <?php endif; ?>
                                            </span>
                                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="button red-button">View Dataset</a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php wp_reset_postdata(); ?>                           
                    </div>
                </div>
            </section>
        <?php wp_reset_postdata(); ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
    <?php if ( have_rows( '6_grid_block', $q ) ) : ?>
        <?php while ( have_rows( '6_grid_block', $q  ) ) : the_row(); ?>
            <?php $sub_topic_grid_term = get_sub_field( 'type' ); ?>
            <?php if ( $sub_topic_grid_term ): ?>
                <?php $topic_term = get_sub_field( 'type' );?>
                <section class="topicGrid portal <?php echo get_sub_field( 'background_colour' );?>">
                    <div class="container">
                        <div class="blockTitle">
                            <h2><?php echo get_sub_field( 'title' ); ?></h2>
                            <a href="<?php echo get_term_link($q); ?>?type=<?php echo $topic_term->slug; ?>" class="viewAll">View All</a>
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
                                            'taxonomy' => 'persona-mapping',
                                            'field' => 'slug',
                                            'terms'    => $q->slug
                                        ),
                                        array(
                                            'taxonomy' => 'filter-types',
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
                                                        <a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topicFilterText"><?php echo esc_html( $postTopic->name ); ?></a>
                                                    <?php } ?>
                                                    <a href="<?php echo esc_url( get_term_link($q) ); ?>" class="topicFilterText"><?php echo esc_html( $q->name ); ?></a>

                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
                                                <?php if ($q->slug == 'workshop-recordings' || $q->slug == 'case-studies' || $q->slug == 'best-practices' || $postType->slug == 'market-narratives'){ ?>
                                                    <span class="dateReadTime"><?php if (get_field( 'read_time' )) { ?><?php echo esc_html( get_field('read_time') ); ?><?php } ?></span>
                                                <?php } else { ?>
                                                    <span class="dateReadTime"><span class="dateRead"><?php echo esc_html( get_the_date('M j, Y') ); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                                <?php } ?>
                                                <span class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) );?></span>
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
        <?php endwhile; ?>
    <?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
<?php } else { ?> 
    <?php if(get_field( 'banner_image', $q )){ ?>
        <?php $banner_image = get_field( 'banner_image', $q ); ?>
    <?php } else { ?>
        <?php $banner_image = get_field( 'persona_mapping_banner_image', 'options' ); ?>
    <?php } ?>
    <section class="eventsBanner topicBanner personaBanner sectorBanner" style="background-image:url(<?php echo $banner_image['url']; ?>); background-size: cover; background-position: center;">
        <div class="container">
            <span class="back-to-sectors topicFilter">
                <a href="/persona-mapping/" target="_self">Persona Mapping</a>
            </span>
            <h1><?php echo get_field( 'persona_title', $q ); ?></h1>
            <p class="persona-description"><?php echo esc_html( $q->description ); ?></span>
        </div>
    </section>
    <?php
    // This query only feeds the facet loop below (get_the_terms per post to
    // build the filter-types dropdown), which never reads title/content/ACF
    // fields, so it doesn't need full WP_Post objects. fields => ids skips
    // that hydration on a query with no result limit.
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'paged'=> $paged,
        'fields' => 'ids',
        'tax_query'      => array(
            'relation' => 'AND',
            array (
                'taxonomy' => 'persona-mapping',
                'field' => 'slug',
                'terms'    => $q->slug
            )
        ),
    ); ?>

    <?php if($filterType != '') {
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
            }
        }
    } ?>

    <?php $posts = new WP_Query( $args );
    if( $posts->have_posts() ): ?>
        <?php foreach ( $posts->posts as $post_id ) : ?>
            <?php if(get_the_terms( $post_id, 'filter-types' )){
                $termsType = get_the_terms( $post_id, 'filter-types' );

                foreach($termsType as $type) {
                    if(!in_array($type,$post_types)){
                        $post_types[] = $type;
                    }
                }
            }
            ?>
        <?php endforeach; else : ?>
    <?php endif; ?>
    <?php wp_reset_query();?>
    <section class="persona-filtering">
        <div class="container">
            <?php
            array_multisort(array_column($post_types, 'name'), SORT_ASC, $post_types);
            $terms = $post_types;
            ?>
            <span class="filter-link-container">
                <a class="persona-filter-link all<?php if($filterType == '') { ?> active<?php } ?>" href="<?php echo esc_url( get_term_link($q) ); ?>">All</a>
                <?php foreach ($terms as $term){ ?>
                    <a class="persona-filter-link<?php if($filterType == $term->slug) { ?> active<?php } ?>" href="<?php echo get_term_link($q); ?>?type=<?php echo $term->slug;?>"><?php echo esc_html( $term->name );?></a>
                <?php } ?>
            </span>
        </div>
    </section>
    <section class="portal postListing topicGrid subTopic sector-container list-style-list">
        <div class="container">
            <div id="loop" class="gridWrapper listWrapper list">
                <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                $args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 9,
                    'paged'=> $paged,
                    'tax_query'      => array(
                        'relation' => 'AND',
                        array (
                            'taxonomy' => 'persona-mapping',
                            'field' => 'slug',
                            'terms'    => $q->slug
                        )
                    ),
                ); ?>

                <?php if($filterType != '') {
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
                            array_push($args['tax_query'],array(
                                    'taxonomy' => 'filter-types',
                                    'field' => 'slug',
                                    'terms' => $filterType,
                                    'operator' => 'IN'
                                )
                            );
                        }
                    }
                } ?>

                <?php $posts = new WP_Query( $args );
                if( $posts->have_posts() ): ?>
                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>

                    <?php if(current_user_can('mepr_auth')) {?>
                        <div class="item list-view">
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
                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => '', 'class' => 'desktop' ) ); ?>
                                    <?php else : ?>
                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div class="textContainer">
                                <span class="topicFilter">
                                    <?php if (yoast_get_primary_term_id('persona-mapping')) {
                                        $primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
                                        $postTopic = get_term( $primary_term_topic_id );
                                    } else {
                                        if(get_the_terms( $post->ID, 'persona-mapping' )){
                                            $terms = get_the_terms( $post->ID, 'persona-mapping' );
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
									<?php if($q){?>
                                        <a href="<?php echo esc_url( get_term_link($q) ); ?>" class="topicFilterText"><?php echo esc_html( $q->name ); ?></a>
                                    <?php } ?>
                                    <?php if($postType){?>
                                            <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
                                    <?php } ?>
                                </span>
                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
								<span class="dateReadTime"><span class="dateRead"><?php echo esc_html( get_the_date('M j, Y') ); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
                                <span class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) );?></span>
                            </div>
                        </div>

                        <?php $counter++; ?>
                    <?php } ?>

                <?php endwhile; else : ?>
                	<h2 class="h3"><?php esc_html_e( 'Sorry, no results found.' ); ?></h2>
                <?php endif; ?>
                <?php wp_pagenavi( array( 'query' => $posts ) ); ?>
                <?php wp_reset_query();?>

            </div>

        </div>
    </section>
<?php } ?>