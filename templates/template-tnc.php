<?php global $displayed_posts;
$displayed_posts = array (); ?>

<?php

$q = get_queried_object();

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
                'terms'    => 'tnc'
            )
        )
    );
} else {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'paged'=> $paged
    );
}
?>

<section class="filter-title-block ">
    <div class="container">
        <div class="title-container">
            <h1 class="type-title text-black"><?php echo get_field( 'next_conversation_title', 'options' ); ?></h1>
            <span class="type-description text-black"><?php echo get_field( 'next_conversation_text', 'options' ); ?></span>
        </div>
        <div class="topic-button-container-outer">
            <div class="topic-button-container filter-button-container">
                <a class="all filter-button" href="/tnc/">All</a>
                 <?php 
                    $terms = get_terms( array(
                        'post_type' => 'post',
                        'taxonomy' => 'tnc'
                    ) ); 
                ?>
                <?php foreach($terms as $term) { ?>
                    <a href="<?php echo get_term_link( $term );?>" class="filter-button<?php if ($term -> slug == $q -> slug ) { ?> selected<?php } ?>"><?php echo $term -> name; ?></a>
                <?php } ?>
            </div>
        </div>           
    </div>            
</section>

<!-- Featured Post  -->

<?php if ( have_rows( 'featured', $q ) ) : ?>
	<?php while ( have_rows( 'featured', $q ) ) : the_row(); ?>
        <section class="tnc-featured-post">
            <div class="container">
                <?php $post_object = get_sub_field( 'featured_post' ); ?>
                <?php if (get_sub_field( 'featured_or_most_recent' ) == 'featured') { ?>               
                    <?php if ( $post_object ): ?>
                        <?php $post = $post_object; ?>
                        <?php setup_postdata( $post ); ?>
                            <div class="item <?php echo $q->slug; ?> full-width">
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
                                        <?php } elseif ( get_field( 'video_image' )){ ?>
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
                                </div>                                    
                                <span class="item-content-container">
                                    <span class="topic-filter">
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
                                        <?php if($q->slug == 'persona'){ ?> 
                                            <?php if (yoast_get_primary_term_id('persona-mapping')) {
                                                $primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
                                                $postType = get_term( $primary_term_topic_id );
                                            } else {
                                                if(get_the_terms( $post->ID, 'persona-mapping' )){
                                                    $terms = get_the_terms( $post->ID, 'persona-mapping' );
                                                    foreach($terms as $term) {
                                                        $postType = $term;
                                                    }
                                                }
                                            }?>
                                        <?php } ?>
                                        <?php if($q->slug == 'sector'){ ?> 
                                            <?php if (yoast_get_primary_term_id('sector-analysis')) {
                                                $primary_term_topic_id = yoast_get_primary_term_id('sector-analysis');
                                                $postSector = get_term( $primary_term_topic_id );
                                            } else {
                                                if(get_the_terms( $post->ID, 'sector-analysis' )){
                                                    $terms = get_the_terms( $post->ID, 'sector-analysis' );
                                                    foreach($terms as $term) {
                                                        $postSector = $term;
                                                    }
                                                }
                                            }?>
                                        <?php } ?>
                                        <?php if($postType){?>
                                                <a href="/persona-mapping/<?php echo $postType->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postType->name; ?></a>
                                        <?php } ?>
                                        <?php if($postSector){?>
                                                <a href="/data-insights/sector-analysis/<?php echo $postSector->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postSector->name; ?></a>
                                        <?php } ?>                                
                                        <?php if($postTopic){?>
                                            <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text text-black black-text">/ <?php echo $postTopic->name; ?></a>
                                        <?php } ?>
                                    </span>
                                    <a href="<?php the_permalink(); ?>" class="title labelXXLarge text-black"><?php the_title(); ?></a>
                                </span>         
                            </div>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>               
                <?php } else { ?>
                    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
                    <?php
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 1,
                            'paged'=> $paged,
                            'tax_query' => array(
                                'relation' => 'AND',
                                array (
                                    'taxonomy' => 'tnc',
                                    'field' => 'slug',
                                    'terms'    => $q->slug
                                )
                            )
                        );
                        $posts = new WP_Query( $args ); ?>
                        <?php if( $posts->have_posts() ): ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <div class="item <?php echo $q->slug; ?> full-width">
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
                                            <?php } elseif ( get_field( 'video_image' )){ ?>
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
                                    </div>                                    
                                    <span class="item-content-container">
                                        <span class="topic-filter">
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
                                            <?php if($q->slug == 'persona'){ ?> 
                                                <?php if (yoast_get_primary_term_id('persona-mapping')) {
                                                    $primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
                                                    $postType = get_term( $primary_term_topic_id );
                                                } else {
                                                    if(get_the_terms( $post->ID, 'persona-mapping' )){
                                                        $terms = get_the_terms( $post->ID, 'persona-mapping' );
                                                        foreach($terms as $term) {
                                                            $postType = $term;
                                                        }
                                                    }
                                                }?>
                                            <?php } ?>
                                            <?php if($q->slug == 'sector'){ ?> 
                                                <?php if (yoast_get_primary_term_id('sector-analysis')) {
                                                    $primary_term_topic_id = yoast_get_primary_term_id('sector-analysis');
                                                    $postSector = get_term( $primary_term_topic_id );
                                                } else {
                                                    if(get_the_terms( $post->ID, 'sector-analysis' )){
                                                        $terms = get_the_terms( $post->ID, 'sector-analysis' );
                                                        foreach($terms as $term) {
                                                            $postSector = $term;
                                                        }
                                                    }
                                                }?>
                                            <?php } ?>
                                            <?php if($postType){?>
                                                    <a href="/persona-mapping/<?php echo $postType->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postType->name; ?></a>
                                            <?php } ?>
                                            <?php if($postSector){?>
                                                    <a href="/data-insights/sector-analysis/<?php echo $postSector->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postSector->name; ?></a>
                                            <?php } ?>                                
                                            <?php if($postTopic){?>
                                                <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text text-black black-text">/ <?php echo $postTopic->name; ?></a>
                                            <?php } ?>
                                        </span>
                                        <a href="<?php the_permalink(); ?>" class="title labelXXLarge text-black"><?php the_title(); ?></a>
                                    </span>                                    
                                </div>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>                    
                <?php } ?>
            </div>
        </section>
    <?php endwhile; ?>
<?php endif; ?>

<section class="filter-listing">
    <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
    <div class="container">
        <div class="grid-wrapper" id="loop">
            <?php $args = array(
                'post_type' => 'post',
                'posts_per_page' => 9,
                'paged'=> $paged,
                'tax_query' => array(
                    'relation' => 'AND',
                    array (
                        'taxonomy' => 'tnc',
                        'field' => 'slug',
                        'terms'    => $q->slug
                    ),
                )
            );
            $posts = new WP_Query( $args ); ?>
            <?php if( $posts->have_posts() ): ?>
                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                    <div class="item one-third peer-insights-item">
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
                                <?php } elseif ( get_field( 'video_image' )){ ?>
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
                        <span class="item-content-container">
                            <span class="topic-filter">
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
                                <?php if($q->slug == 'persona'){ ?> 
                                     <?php if (yoast_get_primary_term_id('persona-mapping')) {
                                        $primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
                                        $postType = get_term( $primary_term_topic_id );
                                    } else {
                                        if(get_the_terms( $post->ID, 'persona-mapping' )){
                                            $terms = get_the_terms( $post->ID, 'persona-mapping' );
                                            foreach($terms as $term) {
                                                $postType = $term;
                                            }
                                        }
                                    }?>
                                <?php } ?>
                                 <?php if($q->slug == 'sector'){ ?> 
                                     <?php if (yoast_get_primary_term_id('sector-analysis')) {
                                        $primary_term_topic_id = yoast_get_primary_term_id('sector-analysis');
                                        $postSector = get_term( $primary_term_topic_id );
                                    } else {
                                        if(get_the_terms( $post->ID, 'sector-analysis' )){
                                            $terms = get_the_terms( $post->ID, 'sector-analysis' );
                                            foreach($terms as $term) {
                                                $postSector = $term;
                                            }
                                        }
                                    }?>
                                <?php } ?>
                                 <?php if($postType){?>
                                        <a href="/persona-mapping/<?php echo $postType->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postType->name; ?></a>
                                <?php } ?>
                                <?php if($postSector){?>
                                        <a href="/data-insights/sector-analysis/<?php echo $postSector->slug; ?>" class="topic-filter-text text-black black-tex"><?php echo $postSector->name; ?></a>
                                <?php } ?>                                
                                <?php if($postTopic){?>
                                    <a href="<?php echo get_term_link($postTopic); ?>" class="topic-filter-text text-black black-text">/ <?php echo $postTopic->name; ?></a>
                                <?php } ?>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="title labelXLarge text-black"><?php the_title(); ?></a>
                        </span>
                    </div>                               
                <?php endwhile; ?>                        
            <?php endif;?>
        </div>
        <div class="page-navi-container">
            <?php wp_pagenavi( array( 'query' => $posts ) ); ?>
                <?php wp_reset_postdata(); ?>
            <?php wp_reset_query(); ?>
        </div>
    </div>
    
</section>