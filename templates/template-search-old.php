<?php $keyword = $_GET['s']; ?>
<?php $articleType = $_GET['type']; ?>

<section class="search-filter-container bg-lightest">
    <div class="container">
        <span class="search">
            <span class="search-icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/search-page-search-icon.svg" width="20" height="20" loading="lazy" alt="Search" /></span>
            <form action="/" method="get">
                <input class="searchInput" type="text" name="s" id="search" <?php if($keyword != ''){ ?> placeholder="Search. . ." value="<?php the_search_query(); ?>" <?php } else { ?> placeholder="Search. . ." value=""<?php } ?>/>
                <input class="searchButton stdBtn red" type="submit" value="search" alt="Search"/>
            </form>
            <span class="clear-search"></span>
        </span>
        <span class="article-filtering-container">
            <a class="article-filter-item all-filter<?php if($articleType == ''){ ?> active<?php } ?>" href="/?s=<?php the_search_query(); ?>" target="_self">All</a>
            <?php
                $pageURL .= $_SERVER["REQUEST_URI"];

                $termsInPosts = array();
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                    $terms = get_the_terms( $post->ID, 'article-type' );
                    if ( $terms ) { ?>
                        <?php foreach($terms as $term) { ?>
                            <?php if (!in_array($term->term_id, $termsInPosts))
                                    $termsInPosts[] = $term->term_id;
                            ?>
                        <?php } ?>
                    <?php } ?>
                <?php endwhile; endif;
                $filter_terms = get_terms([
                  'taxonomy' => 'article-type',
                  'include' => $termsInPosts
                ]);?>
                <?php foreach($filter_terms as $filter_term) { ?>
                    <a class="article-filter-item article-filter-icon <?php echo $filter_term->slug;?> <?php if($articleType == $filter_term->slug){ ?> active<?php } ?>" href="<?php echo $pageURL; ?>&type=<?php echo $filter_term->slug;?>" target="_self"><?php echo $filter_term->name;?></a>
                <?php } ?>
        </span>
    </div>
</section>
<section class="portal postListing topicGrid subTopic search-results">
    <div class="container">
        <div class="blockTitle">
            <?php global $wp_query; ?>
            <?php if($articleType != ''){ ?>
                <?php
                $args = array_merge( $wp_query->query_vars,
                    array(
                       'post_type' => 'post',
                       'tax_query' => array(
                           'relation' => 'AND',
                           array (
                               'taxonomy' => 'article-type',
                               'field' => 'slug',
                               'terms' => $articleType,
                           ),
                       )
                   )
                ); ?>
            <?php query_posts( $args ); ?>
            <?php } ?>
            <span class="results-counter"><?php echo $wp_query->found_posts; ?> results found for <span class="search-query"><?php the_search_query(); ?></span></span>
        </div>
        <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
        <div class="gridWrapper" id="loop">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
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
                                <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
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
                                    <?php if($postType->slug == 'workshop-recordings'){ ?>
                                        <span class="topicFilterText"><?php echo $postType->name; ?></span>
                                    <?php } else { ?>
                                        <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
                                    <?php } ?>
                                <?php } ?>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
                            <?php if(get_the_terms( $post->ID, 'article-type' )){
                                $termsType = get_the_terms( $post->ID, 'article-type' );
                                foreach($termsType as $type) {
                                    $articleType = $type;
                                }
                            }?>
                            <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?>  <?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?>
                                <?php if($articleType){?>
                                    <span class="articleType <?php echo $articleType->slug;?>"><?php echo $articleType->name; ?></span>
                                <?php } ?>
                            </span>
                            <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif;?>
            <?php wp_pagenavi(); ?>
        <?php wp_reset_query(); ?>
        </div>
    </div>
</section>
