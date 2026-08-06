<?php
/**
 * Template Name: Persona Market Narratives Listing Template
 */

get_header();
$persona = $_GET['persona'];
$keyword = $_GET['searchWords'];
?>


<main id="main" role="main" class="home sector-analysis-listing">
<?php if($persona != '' || $keyword != '') { ?>
	<?php $taxonomy_details = get_term_by('slug', $persona, 'persona-mapping'); ?>
	<?php if ( have_rows( 'banner' ) ) : ?>
		<?php while ( have_rows( 'banner' ) ) : the_row(); ?>
			<?php $banner_image = get_sub_field( 'banner_image' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	    <section class="eventsBanner topicBanner sectorBanner" style="background-image:url(<?php echo $banner_image['url']; ?>); background-size: cover; background-position: center;">
	        <div class="container">
	            <span class="back-to-sectors topicFilter">
	                <a href="/market-narratives/persona-mapping/" target="_self">Persona Mapping</a>
	            </span>
	            <h1><?php echo $taxonomy_details->name; ?><?php if(get_field( 'persona_title', $taxonomy_details )){ ?> (<?php echo get_field( 'persona_title', $taxonomy_details ); ?>)<?php } ?></h1>
	        </div>
	    </section>
		<section class="filter margin-bottom">
			<div class="container">
				<div class="formWrapper">
					<form action="" name="postTypesFilter" class="postTypesFilter" method="get"> 
						<span class="searchField">
                            <span class="search">
                                <input class="searchInput" type="text" name="searchWords" id="search" placeholder="Find in Persona Mapping" <?php if($keyword != '') {?>value="<?php echo $keyword;?>"<?php } ?>/>
                                <input class="searchButton" type="image" alt="Search" <?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'workshop-recordings' || $q->slug == 'customer'){ ?>src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify-grey.svg" <?php } else { ?>src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" <?php }?>/>
                            </span>
                        </span>                       
						<span class="filtersButtonMobile">                            
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/filters.svg" width="14" height="14" loading="lazy" alt="Filters" />                            
							<span class="filterButtonText">Filter</span>
						</span>
						<span class="dropDowns">
							<span class="subTopics">
								<label for="filter-topic">Explore Within</label>
								<select name="persona" id="" onchange="this.form.submit()">
									<option value="">All Personas</option>
									<?php
										$argsFilter = array(
											'post_type'      => 'post',
											'posts_per_page' => -1,
											'tax_query'      => array(
												'relation' => 'AND',
												array (
													'taxonomy' => 'filter-types',
													'field' => 'slug',
													'terms'    => 'market-narratives'
												),
												array(
													'taxonomy' => 'market-narratives-subcategories',
													'field' => 'slug',
													'terms'    => 'persona-mapping'
												)
											),
										);
									?>
									<?php $terms = array(); ?>
									<?php $loop = new WP_Query( $argsFilter ); ?>
									<?php if ( $loop->have_posts() ) : ?>
										<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
										<?php
											$topics = get_the_terms( $post->ID, 'persona-mapping' );
											if($topics){
												foreach( $topics as $topic ){
													
													if( ! in_array( $topic, $terms )){
														$terms[] = $topic;
													}
													
												}
											}
										?>
										<?php endwhile; ?>
									<?php else : ?>
									<?php endif; ?>
									<?php wp_reset_query(); 
									// Define the custom order of terms by slug
										$custom_order = array(
											'cio',
											'ciso',
											'cdo-digital',
											'cdo-data',
											'cfo',
											'cloud-architecture-leader',
											'ai-leader',
											'cdao',
											'chro'
										);

										// Custom sorting function
										usort($terms, function ($a, $b) use ($custom_order) {
											$indexA = array_search($a->slug, $custom_order);
											$indexB = array_search($b->slug, $custom_order);

											// If not in custom order, push to end
											$indexA = ($indexA === false) ? PHP_INT_MAX : $indexA;
											$indexB = ($indexB === false) ? PHP_INT_MAX : $indexB;

											return $indexA - $indexB;
										});
									?>
									<?php foreach($terms as $term) { ?>
										<option value="<?php echo $term->slug; ?>" <?php if($persona == '') { } else { if ($term -> slug == $persona ) { ?> selected <?php }}?>><?php echo $term -> name; ?></option>
									<?php } ?>
								</select>
							</span>                            
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
	    <section class="portal postListing topicGrid sector-grid persona-grid subTopic sector-container">
	        <div class="container">
	            <div id="loop" class="gridWrapper">
					<?php
					if($keyword != '') {
						$args = array(
							'post_type'      => 'post',
							'posts_per_page' => -1,
							's' => $keyword,
							'tax_query'      => array(
								'relation' => 'AND',
								array (
									'taxonomy' => 'filter-types',
									'field' => 'slug',
									'terms'    => 'market-narratives'
								)
							),
							array(
								'taxonomy' => 'market-narratives-subcategories',
								'field' => 'slug',
								'terms'    => 'persona-mapping'
							)
						);
					} else {
						$args = array(
							'post_type'      => 'post',
							'posts_per_page' => -1,
							'tax_query'      => array(
								'relation' => 'AND',
								array (
									'taxonomy' => 'filter-types',
									'field' => 'slug',
									'terms'    => 'market-narratives'
								),
								array(
									'taxonomy' => 'market-narratives-subcategories',
									'field' => 'slug',
									'terms'    => 'persona-mapping'
								)								
							),
						);
					}

					if ($persona != '') {
						$args['tax_query'][] = array(
							'taxonomy' => 'persona-mapping',
							'field'    => 'slug',
							'terms'    => $persona
						);
					}
					
					?>
					<?php $posts = new WP_Query( $args );
                    if( $posts->have_posts() ): ?>
                        <?php while( $posts->have_posts() ) : $posts->the_post(); ?>

	                    <?php if(current_user_can('mepr_auth')) {?>
	                        <div class="item">
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
	                                        <span class="hover-container">
	                                            <?php if ($imageCounter) { ?>
	                                                <span class="slide-counter">1 OF <?php echo $imageCounter; ?></span>
	                                            <?php } ?>
	                                        <span>
	                                    <?php else : ?>
	                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
	                                        <span class="hover-container">

	                                        <span>
	                                    <?php endif; ?>
	                                </div>
	                            </a>
	                            <div class="textContainer">
	                                <span class="topicFilter">
	                                     <?php if (yoast_get_primary_term_id('persona-mapping')) {
											$primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
											$personaTerm = get_term( $primary_term_topic_id );
										} else {
											if(get_the_terms( $post->ID, 'persona-mapping' )){
												$terms = get_the_terms( $post->ID, 'persona-mapping' );
												foreach($terms as $term) {
													$personaTerm = $term;
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
										<?php if($persona != '') { ?>
											<a href="/market-narratives/persona-mapping/?persona=<?php echo $persona; ?>" class="topicFilterText"><?php echo $taxonomy_details->name; ?></a>
										<?php } else { ?> 
											<?php if($personaTerm){?>
												<a href="/market-narratives/persona-mapping/?persona=<?php echo $personaTerm->slug; ?>" class="topicFilterText"><?php echo $personaTerm->name; ?></a>
											<?php } ?>
										<?php } ?>
	                                    <?php if($postType){?>
	                                        <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
	                                    <?php } ?>
	                                </span>
	                                <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
									<span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?></span>
	                                <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
	                                <a href="<?php the_permalink(); ?>" class="button data-set-button">View Dataset</a>
	                            </div>
	                        </div>

	                        <?php $counter++; ?>
	                    <?php } ?>

	                <?php endwhile; else : ?>
	                	<h3><?php esc_html_e( 'Sorry, no results found.' ); ?></h3>
	                <?php endif; ?>

	                <?php wp_reset_postdata(); wp_reset_query();?>

	            </div>

	        </div>
	    </section>
<?php } else { ?>
	<?php if ( have_rows( 'banner' ) ) : ?>
		<?php while ( have_rows( 'banner' ) ) : the_row(); ?>
			<?php $banner_image = get_sub_field( 'banner_image' ); ?>
			<section class="topicBanner sector-topic-banner" style="background-image:url(<?php echo $banner_image['url']; ?>);">
				<div class="container">
					<span class="breadcrumb-container">
						<a class="home-link" href="/" target="_self">Home</a>
						<span class="divider">/</span>
						<a class="home-link" href="/market-narratives" target="_self">Market Narratives</a>
						<span class="divider">/</span>
						<span class="title"><?php the_title();?></span>
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
	<section class="filter">
			<div class="container">
				<div class="formWrapper">
					<form action="" name="postTypesFilter" class="postTypesFilter" method="get">                        
						<span class="searchField">
                            <span class="search">
                                <input class="searchInput" type="text" name="searchWords" id="search" placeholder="Find in Persona Mapping" />
                                <input class="searchButton" type="image" alt="Search" <?php if ($q->slug == 'expert-presentations' || $q->slug == 'community-interviews' || $q->slug == 'workshop-recordings' || $q->slug == 'customer'){ ?>src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify-grey.svg" <?php } else { ?>src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" <?php }?>/>
                            </span>
                        </span> 
						<span class="filtersButtonMobile">                            
							<img src="<?php echo get_template_directory_uri(); ?>/assets/images/filters.svg" width="14" height="14" loading="lazy" alt="Filters" />                            
							<span class="filterButtonText">Filter</span>
						</span>
						<span class="dropDowns">
							<span class="subTopics">
								<label for="filter-topic">Explore Within</label>
								<select name="persona" id="" onchange="this.form.submit()">
									<option value="">All Personas</option>
									<?php
										$argsFilter = array(
											'post_type'      => 'post',
											'posts_per_page' => -1,
											'tax_query'      => array(
												'relation' => 'AND',
												array (
													'taxonomy' => 'filter-types',
													'field' => 'slug',
													'terms'    => 'market-narratives'
												),
												array(
													'taxonomy' => 'market-narratives-subcategories',
													'field' => 'slug',
													'terms'    => 'persona-mapping'
												)
											),
										);
									?>
									<?php $terms = array(); ?>
									<?php $loop = new WP_Query( $argsFilter ); ?>
									<?php if ( $loop->have_posts() ) : ?>
										<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
										<?php
											$topics = get_the_terms( $post->ID, 'persona-mapping' );
											if($topics){
												foreach( $topics as $topic ){
													
													if( ! in_array( $topic, $terms )){
														$terms[] = $topic;
													}
													
												}
											}
										?>
										<?php endwhile; ?>
									<?php else : ?>
									<?php endif; ?>
									<?php wp_reset_query(); 
										// Define the custom order of terms by slug
										$custom_order = array(
											'cio',
											'ciso',
											'cdo-digital',
											'cdo-data',
											'cfo',
											'cloud-architecture-leader',
											'ai-leader',
											'cdao',
											'chro'
										);

										// Custom sorting function
										usort($terms, function ($a, $b) use ($custom_order) {
											$indexA = array_search($a->slug, $custom_order);
											$indexB = array_search($b->slug, $custom_order);

											// If not in custom order, push to end
											$indexA = ($indexA === false) ? PHP_INT_MAX : $indexA;
											$indexB = ($indexB === false) ? PHP_INT_MAX : $indexB;

											return $indexA - $indexB;
										});
									?>
									<?php foreach($terms as $term) { ?>
										<option value="<?php echo $term->slug; ?>" <?php if($persona == '') { } else { if ($term -> slug == $persona ) { ?> selected <?php }}?>><?php echo $term -> name; ?></option>
									<?php } ?>
								</select>
							</span>                            
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
	<?php if ( have_rows( 'filter_buttons' ) ) : ?>
		<section class="explore-section persona-explore-section">
			<div class="container">
				<?php while ( have_rows( 'filter_buttons' ) ) : the_row(); ?>
					<h2 class="title"><?php echo get_sub_field( 'title' ); ?></h2>
					<div class="button-container">
						<?php $sectors_terms = get_sub_field( 'personas' ); ?>
						<?php if ( $sectors_terms ): ?>
							<?php foreach ( $sectors_terms as $sectors_term ): ?>
								<a class="sector-button button grey-button" href="/market-narratives/persona-mapping/?persona=<?php echo $sectors_term->slug; ?>" target="_self"><strong><?php echo $sectors_term->name; ?></strong><?php if(get_field( 'persona_title', $sectors_term )){ ?> (<?php echo get_field( 'persona_title', $sectors_term ); ?>)<?php } ?></a>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		</section>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>

	<?php if ( have_rows( 'featured_article_slider' ) ) : ?>
		<section class="featured-article-slider-data featured-article-slider-persona">
			<div class="container">
				<?php while ( have_rows( 'featured_article_slider' ) ) : the_row(); ?>
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
								                                    <?php if (yoast_get_primary_term_id('persona-mapping', $post)) {
							                                        $primary_term_type_id = yoast_get_primary_term_id('persona-mapping', $post);
							                                        $postType = get_term( $primary_term_type_id );
							                                    } else {
							                                        if(get_the_terms( $post->ID, 'persona-mapping' )){
							                                            $termsType = get_the_terms( $post->ID, 'persona-mapping' );
							                                            foreach($termsType as $type) {
							                                                $postType = $type;
							                                            }
							                                        }
							                                    }?>
							                                    <a href="/market-narratives/persona-mapping/" class="topicFilterText">Persona Mapping</a>
							                                    <?php if($postType){?>
							                                        <a href="/market-narratives/persona-mapping/?persona=<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
							                                    <?php } ?>
							                                </span>
							                                <a href="<?php the_permalink(); ?>" class="title"><?php echo get_the_title($post->ID); ?></a>
															<span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?></span>
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
	<?php if ( have_rows( 'persona' ) ) : ?>
		<?php while ( have_rows( 'persona' ) ) : the_row(); ?>
			<?php get_template_part( 'templates/components/_persona-grid-portal-markets' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<section class="portal postListing topicGrid sector-grid persona-grid subTopic sector-container">
		 <div class="container">
			 <div id="loop" class="gridWrapper">
				 <?php $term_m = 'persona-mapping';
				  $terms = get_terms( $term_m, array(
					  'hide_empty' => false,
				  ) );

				  $personas = array();
				  foreach( $terms as $term){
					  $personas[] = $term->slug;
				  } ?>
				 <?php
				 $args = array(
					 'post_type'      => 'post',
					 'posts_per_page' => -1,
					 'tax_query'      => array(
						 'relation' => 'AND',
						 array (
							 'taxonomy' => 'filter-types',
							 'field' => 'slug',
							 'terms'    => 'market-narratives'
						 ),
						 array(
							 'taxonomy' => 'market-narratives-subcategories',
							 'field'    => 'slug',
							 'terms'    => 'persona-mapping'							
						 )
					 ),
				 );
				 ?>
				 <?php $posts = new WP_Query( $args );
					if( $posts->have_posts() ): ?>
						<?php while( $posts->have_posts() ) : $posts->the_post(); ?>

					 <?php if(current_user_can('mepr_auth')) {?>
						 <div class="item">
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
										 <span class="hover-container">
											 <?php if ($imageCounter) { ?>
												 <span class="slide-counter">1 OF <?php echo $imageCounter; ?></span>
											 <?php } ?>
										 <span>
									 <?php else : ?>
										 <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, array( 'alt' => '', 'class' => 'desktop' ) );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" alt="" />';
								}
							?>
										 <span class="hover-container">

										 <span>
									 <?php endif; ?>
								 </div>
							 </a>
							 <div class="textContainer">
								 <span class="topicFilter">
									 <?php if (yoast_get_primary_term_id('persona-mapping')) {
										 $primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
										 $persona = get_term( $primary_term_topic_id );
									 } else {
										 if(get_the_terms( $post->ID, 'persona-mapping' )){
											 $terms = get_the_terms( $post->ID, 'persona-mapping' );
											 foreach($terms as $term) {
												 $persona = $term;
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
									 <a href="/market-narratives/persona-mapping/?persona=<?php echo $persona->slug; ?>" class="topicFilterText"><?php echo $persona->name; ?></a>
									 <?php if($postType){?>
										 <a href="/filter-types/<?php echo $postType->slug; ?>" class="topicFilterText"><?php echo $postType->name; ?></a>
									 <?php } ?>
								 </span>
								 <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
								 <span class="dateReadTime"><?php echo get_the_date('M j, Y'); ?></span>
								 <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
								 <a href="<?php the_permalink(); ?>" class="button data-set-button">View Dataset</a>
							 </div>
						 </div>

						 <?php $counter++; ?>
					 <?php } ?>

				 <?php endwhile; else : ?>
					 <h3><?php esc_html_e( 'Sorry, no results found.' ); ?></h3>
				 <?php endif; ?>

				 <?php wp_reset_postdata(); wp_reset_query();?>

			 </div>

		 </div>
	 </section>
	<?php endif; ?>
<?php } ?>
</main>

<?php get_footer(); ?>
