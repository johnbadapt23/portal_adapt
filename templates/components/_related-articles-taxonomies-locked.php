<section class="relatedArticlesCarousel scrollPos" <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?>>
    <div class="container">
        <div class="inner">
            <h2 class="relatedTitle"><?php echo get_sub_field( 'block_title' ); ?></h2>
                <div class="owl-carousel articlesCarouselTaxonomies">
                    <?php if (get_sub_field( 'taxonomy_type' ) == 'event') { ?>
                        <?php
                        $post_type = 'post';
                        $taxonomy  = 'insights-event';
                        $terms     =  get_sub_field( 'event' );

                        foreach( $terms as $term ) :
                            // orderby => rand forces MySQL to scan and randomly sort every
                            // matching row on every page view of this related-articles carousel -
                            // a well-known slow-at-scale pattern. Pulling a small pool ordered by
                            // date (uses the normal date index) and shuffling in PHP gives the
                            // same genuinely-randomized-per-request selection without the DB-side
                            // random sort.
                            $args = array(
                                'post_type'      => $post_type,
                                'posts_per_page' => 20,
                                'fields'         => 'ids',
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => $taxonomy,
                                        'field'    => 'term_id',
                                        'terms'    => $term,
                                    ),
                                ),
                            );
                            $pool = get_posts( $args );
                            shuffle( $pool );
                            $related_ids = array_slice( $pool, 0, 8 );
                            $posts = $related_ids
                            	? new WP_Query( array(
                            		'post_type'      => $post_type,
                            		'post__in'       => $related_ids,
                            		'orderby'        => 'post__in',
                            		'posts_per_page' => 8,
                            	) )
                            	: new WP_Query( array( 'post__in' => array( 0 ) ) );
                             if( $posts->have_posts() ): ?>
                              <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                  <?php if(current_user_can('mepr_auth')) {?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php echo get_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php echo get_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php echo get_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php echo get_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } else { ?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php echo get_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php echo get_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php echo get_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php echo get_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } ?>
                            <?php endwhile; endif;
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    <?php } else if (get_sub_field( 'taxonomy_type' ) == 'topic') { ?>
                        <?php
                        $post_type = 'post';
                        $taxonomy  = 'topic';
                        $terms     =  get_sub_field( 'topic' );

                        foreach( $terms as $term ) :
                            // orderby => rand forces MySQL to scan and randomly sort every
                            // matching row on every page view of this related-articles carousel -
                            // a well-known slow-at-scale pattern. Pulling a small pool ordered by
                            // date (uses the normal date index) and shuffling in PHP gives the
                            // same genuinely-randomized-per-request selection without the DB-side
                            // random sort.
                            $args = array(
                                'post_type'      => $post_type,
                                'posts_per_page' => 20,
                                'fields'         => 'ids',
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => $taxonomy,
                                        'field'    => 'term_id',
                                        'terms'    => $term,
                                    ),
                                ),
                            );
                            $pool = get_posts( $args );
                            shuffle( $pool );
                            $related_ids = array_slice( $pool, 0, 8 );
                            $posts = $related_ids
                            	? new WP_Query( array(
                            		'post_type'      => $post_type,
                            		'post__in'       => $related_ids,
                            		'orderby'        => 'post__in',
                            		'posts_per_page' => 8,
                            	) )
                            	: new WP_Query( array( 'post__in' => array( 0 ) ) );
                             if( $posts->have_posts() ): ?>
                              <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                  <?php if(current_user_can('mepr_auth')) {?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php echo get_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                          <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php echo get_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php echo get_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php echo get_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } else { ?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php echo get_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php echo get_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php echo get_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php echo get_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } ?>
                            <?php endwhile; endif;
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    <?php } else if (get_sub_field( 'taxonomy_type' ) == 'filter-type') { ?>
                        <?php
                        $post_type = 'post';
                        $taxonomy  = 'filter-types';
                        $terms     =  get_sub_field( 'filter_type' );

                        foreach( $terms as $term ) :
                            // orderby => rand forces MySQL to scan and randomly sort every
                            // matching row on every page view of this related-articles carousel -
                            // a well-known slow-at-scale pattern. Pulling a small pool ordered by
                            // date (uses the normal date index) and shuffling in PHP gives the
                            // same genuinely-randomized-per-request selection without the DB-side
                            // random sort.
                            $args = array(
                                'post_type'      => $post_type,
                                'posts_per_page' => 20,
                                'fields'         => 'ids',
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => $taxonomy,
                                        'field'    => 'term_id',
                                        'terms'    => $term,
                                    ),
                                ),
                            );
                            $pool = get_posts( $args );
                            shuffle( $pool );
                            $related_ids = array_slice( $pool, 0, 8 );
                            $posts = $related_ids
                            	? new WP_Query( array(
                            		'post_type'      => $post_type,
                            		'post__in'       => $related_ids,
                            		'orderby'        => 'post__in',
                            		'posts_per_page' => 8,
                            	) )
                            	: new WP_Query( array( 'post__in' => array( 0 ) ) );
                             if( $posts->have_posts() ): ?>
                              <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                  <?php if(current_user_can('mepr_auth')) {?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php echo get_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php echo get_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php echo get_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php echo get_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } else { ?>
                                  <a class="relatedArticle item" href="<?php the_permalink(); ?>">
                                      <?php setup_postdata( $post ); ?>

                                      <div class="imageContainer">
                                          <?php if ( get_field( 'listing_image') ) { ?>
                                              <div class="image" style="background-image: url('<?php echo get_field( 'listing_image' ); ?>');">
                                                  <?php if( has_term( 'watch', 'article-type' ) ) { ?>
                                                      <span class="watchIcon"></span>
                                                  <?php } ?>
                                              </div>
                                          <?php } else { ?>
                                              <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
                                                  <div class="image" style="background-image: url('<?php echo get_field( 'video_poster' ); ?>');">
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } else { ?>
                                                  <div class="image" <?php if ( get_field( 'listing_page_grid_image' )) { ?>style="background-image: url('<?php echo get_field( 'listing_page_grid_image' ); ?>');" <?php } else { ?>style="background-image: url('<?php echo get_field( 'featured_image' ); ?>');"<?php } ?>>
                                                      <?php if ( get_field ( 'podcast_file' ) ) { ?>
                                                          <span class="podcast">
                                                              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/podcast-white.svg" width="19" height="19" loading="lazy" alt="Podcast Available" />
                                                          </span>
                                                      <?php } ?>
                                                  </div>
                                              <?php } ?>
                                          <?php } ?>
                                      </div>

                                      <span class="postDetails">
                                          <span class="info">
                                              <?php
                                              $term_m = 'topic';
                                              ?>
                                              <?php
                                              $terms = get_the_terms( $post, 'topic' );
                                              ?>
                                              <?php if ( $terms ) { ?>
                                                  <?php $counterTopic = 0; ?>
                                                  <?php $len = count($terms); ?>
                                                  <?php foreach($terms as $term) { ?>
                                                      <span class="topic<?php if ($counterTopic == $len - 1) { ?> last<?php } ?>">
                                                           <?php echo $term -> name; ?>
                                                      </span>
                                                      <?php $counterTopic++; ?>
                                                  <?php } ?>

                                              <?php } else { ?>
                                              <span class="date">
                                                 <?php if( get_field('event_date')) { ?>
                                                    <?php echo get_field('event_date'); ?>
                                                <?php } else { ?>
                                                    <?php echo get_the_date('d.m.Y'); ?>
                                                <?php } ?>
                                             </span>
                                             <span class="readTime">
                                                 <?php echo get_field( 'read_time' ); ?>
                                             </span>
                                              <?php } ?>
                                          </span>

                                          <span class="articleLink"><?php the_title(); ?></span>

                                          <?php
                                              $post_tags = get_the_tags();
                                              $count=0;
                                          ?>
                                          <?php if ( $post_tags ) { ?>
                                              <div class="tags">
                                                  <?php foreach( $post_tags as $tag ) { $count++; ?>
                                                      <?php if ( $count <= 3 ) { ?>
                                                          <span>
                                                              <?php echo '#' . $tag->name  ; ?>
                                                          </span>
                                                      <?php } ?>
                                                  <?php } ?>
                                              </div>
                                          <?php } ?>
                                      </span>
                                  </a>
                              <?php } ?>
                            <?php endwhile; endif;
                        endforeach;
                        wp_reset_postdata();
                        ?>
                    <?php } else { ?>
                        <span>No posts</span>
                    <? }?>
                </div>
            </div>
            <?php if ( have_rows( 'button_block' ) ) : ?>
                <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                    <div class="buttonBlock <?php echo get_sub_field('link_orientation'); ?>">
                        <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo get_sub_field('link_target'); ?>"><?php echo get_sub_field('link_text'); ?></a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </section>
