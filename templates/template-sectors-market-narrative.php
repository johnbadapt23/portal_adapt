<?php
/**
 * Template Name: Sectors Market Narratives Listing Template
 */

get_header();
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET filter/search params for a bookmarkable, shareable listing URL; no state change results from reading them.
$sector = $_GET['sector'];
$keyword = $_GET['searchWords'];
// phpcs:enable WordPress.Security.NonceVerification.Recommended
$q = get_queried_object();
$q_slug = $q->slug ?? '';
?>


<main id="main" role="main" class="home sector-analysis-listing">
<?php if($sector != '' || $keyword != '') { ?>
	<?php $taxonomy_details = get_term_by('slug', $sector, 'sector-analysis'); ?>
	<?php if ( have_rows( 'banner' ) ) : ?>
		<?php while ( have_rows( 'banner' ) ) : the_row(); ?>
			<?php $banner_image = get_sub_field( 'banner_image' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<?php // no rows found ?>
	<?php endif; ?>
	    <section class="eventsBanner topicBanner sectorBanner" style="background-image:url(<?php echo esc_url( $banner_image['url'] ); ?>); background-size: cover; background-position: center;">
	        <div class="container">
	            <span class="back-to-sectors topicFilter">
	                <a href="/market-narratives/sector-analysis/" target="_self">Sector Analysis</a>
	            </span>
	            <h1><?php echo esc_html( $taxonomy_details->name ); ?><?php if(get_field( 'sector_title', $taxonomy_details )){ ?> (<?php echo esc_html( get_field( 'sector_title', $taxonomy_details ) ); ?>)<?php } ?></h1>
	        </div>
	    </section>
		<section class="filter margin-bottom">
			<div class="container">
				<div class="formWrapper">
					<form action="" name="postTypesFilter" class="postTypesFilter" method="get">                        
						<span class="searchField">
                            <span class="search">
                                <input class="searchInput" type="text" name="searchWords" id="search" placeholder="Find in Sector Analysis" <?php if($keyword != '') {?>value="<?php echo esc_attr( $keyword ); ?>"<?php } ?>/>
                                <input class="searchButton" type="image" alt="Search" <?php if (in_array($q_slug, ['expert-presentations', 'community-interviews', 'workshop-recordings', 'customer'], true)){ ?>src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/magnify-grey.svg" <?php } else { ?>src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/magnify.svg" <?php }?>/>
                            </span>
                        </span> 
						<span class="filtersButtonMobile">                            
							<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/filters.svg" width="14" height="14" loading="lazy" decoding="async" alt="Filters" />                            
							<span class="filterButtonText">Filter</span>
						</span>
						<span class="dropDowns">
							<span class="subTopics">
								<label for="filter-topic">Explore Within</label>
								<select name="sector" id="" onchange="this.form.submit()">
									<option value="">All Sectors</option>
									<?php
										$argsFilter = [
											'no_found_rows'  => true,
											'post_type'      => 'post',
											'posts_per_page' => -1,
											'tax_query'      => [
												'relation' => 'AND',
												 [
													'taxonomy' => 'filter-types',
													'field' => 'slug',
													'terms'    => 'market-narratives'
												],
												[
													'taxonomy' => 'market-narratives-subcategories',
													'field' => 'slug',
													'terms'    => 'sector-analysis'
												]
											],
										];
									?>
									<?php $terms = []; ?>
									<?php
									// This loop only tallies distinct terms for a filter dropdown - it
									// never reads title/content/ACF fields, so it doesn't need full
									// WP_Post objects.
									$argsFilter['fields'] = 'ids';
									?>
									<?php $loop = new WP_Query( $argsFilter ); ?>
									<?php if ( $loop->have_posts() ) : ?>
										<?php foreach ( $loop->posts as $result_id ) : ?>
										<?php
											$topics = get_the_terms( $result_id, 'sector-analysis' );
											if($topics){
												foreach( $topics as $topic ){
													
													if( ! in_array( $topic, $terms )){
														$terms[] = $topic;
													}
													
												}
											}
										?>
										<?php endforeach; ?>
									<?php else : ?>
									<?php endif; ?>
									<?php wp_reset_query(); ?>
									<?php foreach($terms as $term) { ?>
										<option value="<?php echo esc_attr( $term->slug ); ?>" <?php if($sector == '') { } else { if ($term -> slug == $sector ) { ?> selected <?php }}?>><?php echo esc_html( $term -> name ); ?></option>
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
						$args = [
							'post_type'      => 'post',
							'posts_per_page' => -1,
							's' => $keyword,
							'tax_query'      => [
								'relation' => 'AND',
								 [
									'taxonomy' => 'filter-types',
									'field' => 'slug',
									'terms'    => 'market-narratives'
								],									
								[
									'taxonomy' => 'market-narratives-subcategories',
									'field' => 'slug',
									'terms'    => 'sector-analysis'
								]							
							],
						];
					} else {
						$args = [
							'no_found_rows'  => true,
							'post_type'      => 'post',
							'posts_per_page' => -1,
							'tax_query'      => [
								'relation' => 'AND',
								 [
									'taxonomy' => 'filter-types',
									'field' => 'slug',
									'terms'    => 'market-narratives'
								],								
								[
									'taxonomy' => 'market-narratives-subcategories',
									'field' => 'slug',
									'terms'    => 'sector-analysis'
								]
							],
						];
					}
					if ($sector != '') {
						$args['tax_query'][] = [
							'taxonomy' => 'sector-analysis',
							'field'    => 'slug',
							'terms'    => $sector
						];
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
	                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => '', 'class' => 'desktop' ] ); ?>
	                                        <span class="hover-container">
	                                            <?php if ($imageCounter) { ?>
	                                                <span class="slide-counter">1 OF <?php echo esc_html( $imageCounter ); ?></span>
	                                            <?php } ?>
	                                        <span>
	                                    <?php else : ?>
	                                        <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => '', 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
								}
							?>
	                                        <span class="hover-container">

	                                        <span>
	                                    <?php endif; ?>
	                                </div>
	                            </a>
	                            <div class="textContainer">
	                                <span class="topicFilter">
	                                    <?php if (yoast_get_primary_term_id('sector-analysis')) {
											$primary_term_topic_id = yoast_get_primary_term_id('persona-analysis');
											$sectorTerm = get_term( $primary_term_topic_id );
										} else {
											if(get_the_terms( $post->ID, 'sector-analysis' )){
												$terms = get_the_terms( $post->ID, 'sector-analysis' );
												foreach($terms as $term) {
													$sectorTerm = $term;
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
										<?php if($sector != '') { ?>
											<a href="/market-narratives/sector-analysis/?sector=<?php echo esc_attr( $sector ); ?>" class="topicFilterText"><?php echo esc_html( $taxonomy_details->name ); ?></a>
										<?php } else { ?> 
											<?php if($sectorTerm){?>
												<a href="/market-narratives/sector-analysis/?sector=<?php echo esc_attr( $sectorTerm->slug ); ?>" class="topicFilterText"><?php echo esc_html( $sectorTerm->name ); ?></a>
											<?php } ?>
										<?php } ?>
	                                    <?php if($postType){?>
	                                        <a href="/filter-types/<?php echo esc_attr( $postType->slug ); ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
	                                    <?php } ?>
	                                </span>
	                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
									<span class="dateReadTime"><?php echo esc_html( get_the_date('M j, Y') ); ?></span>
	                                <span class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) );?></span>
	                                <a href="<?php the_permalink(); ?>" class="button data-set-button">View Dataset</a>
	                            </div>
	                        </div>

	                        <?php $counter++; ?>
	                    <?php } ?>

	                <?php endwhile; else : ?>
	                	<h2 class="h3"><?php esc_html_e( 'Sorry, no results found.', 'portal' ); ?></h2>
	                <?php endif; ?>

	                <?php wp_reset_postdata(); wp_reset_query();?>

	            </div>

	        </div>
	    </section>
<?php } else { ?>
	<?php if ( have_rows( 'banner' ) ) : ?>
		<?php while ( have_rows( 'banner' ) ) : the_row(); ?>
			<?php $banner_image = get_sub_field( 'banner_image' ); ?>
			<section class="topicBanner sector-topic-banner" style="background-image:url(<?php echo esc_url( $banner_image['url'] ); ?>);">
				<div class="container">
					<span class="breadcrumb-container">
						<a class="home-link" href="/" target="_self">Home</a>
						<span class="divider">/</span>
						<a class="home-link" href="/market-narratives" target="_self">Market Narratives</a>
						<span class="divider">/</span>
						<span class="title"><?php echo esc_html( get_the_title() ); ?></span>
					</span>
					<span class="title-container">
						<h1 clas="h2-style"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h1>
						<span class="subtitle"><?php echo esc_html( get_sub_field( 'sub_title' ) ); ?></span>
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
							<input class="searchInput" type="text" name="searchWords" id="search" placeholder="Find in Sector Analysis" <?php if($keyword != '') {?>value="<?php echo esc_attr( $keyword ); ?>"<?php } ?>/>
							<input class="searchButton" type="image" alt="Search" <?php if (in_array($q_slug, ['expert-presentations', 'community-interviews', 'workshop-recordings', 'customer'], true)){ ?>src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/magnify-grey.svg" <?php } else { ?>src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/magnify.svg" <?php }?>/>
						</span>
					</span>                     
					<span class="filtersButtonMobile">                            
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/filters.svg" width="14" height="14" loading="lazy" decoding="async" alt="Filters" />                            
						<span class="filterButtonText">Filter</span>
					</span>
					<span class="dropDowns">
						<span class="subTopics">
							<label for="filter-topic">Explore Within</label>
							<select name="sector" id="" onchange="this.form.submit()">
								<option value="">All Sectors</option>
								<?php
									$argsFilter = [
										'no_found_rows'  => true,
										'post_type'      => 'post',
										'posts_per_page' => -1,
										'tax_query'      => [
											'relation' => 'AND',
											 [
												'taxonomy' => 'filter-types',
												'field' => 'slug',
												'terms'    => 'market-narratives'
											],
											[
												'taxonomy' => 'market-narratives-subcategories',
												'field' => 'slug',
												'terms'    => 'sector-analysis'
											]
										],
									];
								?>
								<?php $terms = []; ?>
								<?php
								// This loop only tallies distinct terms for a filter dropdown - it
								// never reads title/content/ACF fields, so it doesn't need full
								// WP_Post objects.
								$argsFilter['fields'] = 'ids';
								?>
								<?php $loop = new WP_Query( $argsFilter ); ?>
								<?php if ( $loop->have_posts() ) : ?>
									<?php foreach ( $loop->posts as $result_id ) : ?>
									<?php
										$topics = get_the_terms( $result_id, 'sector-analysis' );
										if($topics){
											foreach( $topics as $topic ){
												if( ! in_array( $topic, $terms )){
															$terms[] = $topic;
														}												
											}
										}
									?>
									<?php endforeach; ?>
								<?php else : ?>
								<?php endif; ?>
								<?php wp_reset_query(); ?>
								<?php foreach($terms as $term) { ?>
									<option value="<?php echo esc_attr( $term->slug ); ?>" <?php if($sector == '') { } else { if ($term -> slug == $sector ) { ?> selected <?php }}?>><?php echo esc_html( $term -> name ); ?></option>
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
					<h2 class="title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
					<div class="button-container">
						<?php $sectors_terms = get_sub_field( 'sectors' ); ?>
						<?php if ( $sectors_terms ): ?>
							<?php foreach ( $sectors_terms as $sectors_term ): ?>
								<a class="sector-button button grey-button" href="/market-narratives/sector-analysis/?sector=<?php echo esc_attr( $sectors_term->slug ); ?>" target="_self"><strong><?php echo esc_html( $sectors_term->name ); ?></strong><?php if(get_field( 'sector_title', $sectors_term )){ ?> (<?php echo esc_html( get_field( 'sector_title', $sectors_term ) ); ?>)<?php } ?></a>
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
  																							  <?php echo wp_get_attachment_image( $offsetimage['ID'], 'full', false, [ 'alt' => $offsetimage['alt'] ] ); ?>
  																						  <?php } ?>
  																					  </span>
																					<?php } else if ($imageCounter == 1){ ?>
																						<span class="bg-container">
																						<?php $imageSlideOne = get_sub_field( 'image'); ?>
																						<?php if (  $imageSlideOne ) { ?>
																							<?php echo wp_get_attachment_image( $imageSlideOne['ID'], 'full', false, [ 'alt' => '' ] ); ?>
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
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => '' ] );
								} else {
									echo '<img src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
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
							                                    <a href="/market-narratives/sector-analysis/" class="topicFilterText">Sector Analysis</a>
							                                    <?php if($postType){?>
							                                        <a href="/market-narratives/sector-analysis/?sector=<?php echo esc_attr( $postType->slug ); ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
							                                    <?php } ?>
							                                </span>
							                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title($post->ID) ); ?></a>
															<span class="dateReadTime"><?php echo esc_html( get_the_date('M j, Y') ); ?></span>
							                                <span class="excerpt">
																<?php if ( have_rows( 'preview_module', $post ) ) : ?>
												                   <?php while ( have_rows( 'preview_module', $post ) ) : the_row(); ?>
																	   <?php echo esc_html( get_sub_field( 'overview_text' ) ); ?>
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
	<?php if ( have_rows( 'sector' ) ) : ?>
		<?php while ( have_rows( 'sector' ) ) : the_row(); ?>
			<?php get_template_part( 'templates/components/_sector-grid-portal-markets' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<section class="portal postListing topicGrid sector-grid persona-grid subTopic sector-container">
		 <div class="container">
			 <div id="loop" class="gridWrapper">
				 <?php $term_m = 'sector-analysis';
				  $terms = get_terms( [ 'taxonomy' => $term_m,
					  'hide_empty' => false,
				  ] );

				  $sectors = [];
				  foreach( $terms as $term){
					  $sectors[] = $term->slug;
				  } ?>
				 <?php
				 $args = [
					 'no_found_rows'  => true,
					 'post_type'      => 'post',
					 'posts_per_page' => -1,
					 'tax_query'      => [
						 'relation' => 'AND',
						  [
							 'taxonomy' => 'filter-types',
							 'field' => 'slug',
							 'terms'    => 'market-narratives'
						 ],
						 [
							 'taxonomy' => 'market-narratives-subcategories',
							 'field'    => 'slug',
							 'terms'    => 'sector-analysis'							
						 ]
					 ],
				 ];
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
										 <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => '', 'class' => 'desktop' ] ); ?>
										 <span class="hover-container">
											 <?php if ($imageCounter) { ?>
												 <span class="slide-counter">1 OF <?php echo esc_html( $imageCounter ); ?></span>
											 <?php } ?>
										 <span>
									 <?php else : ?>
										 <?php
								$image_attach_id = attachment_url_to_postid( $image );
								if ( $image_attach_id ) {
									echo wp_get_attachment_image( $image_attach_id, 'full', false, [ 'alt' => '', 'class' => 'desktop' ] );
								} else {
									echo '<img class="desktop" src="' . esc_url( $image ) . '" loading="lazy" decoding="async" alt="" />';
								}
							?>
										 <span class="hover-container">

										 <span>
									 <?php endif; ?>
								 </div>
							 </a>
							 <div class="textContainer">
								 <span class="topicFilter">
									 <?php if (yoast_get_primary_term_id('sector-analysis')) {
										 $primary_term_topic_id = yoast_get_primary_term_id('sector-analysis');
										 $sector = get_term( $primary_term_topic_id );
									 } else {
										 if(get_the_terms( $post->ID, 'sector-analysis' )){
											 $terms = get_the_terms( $post->ID, 'sector-analysis' );
											 foreach($terms as $term) {
												 $sector = $term;
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
									 <a href="/market-narratives/sector-analysis/?sector=<?php echo esc_attr( $sector->slug ); ?>" class="topicFilterText"><?php echo esc_html( $sector->name ); ?></a>
									 <?php if($postType){?>
										 <a href="/filter-types/<?php echo esc_attr( $postType->slug ); ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
									 <?php } ?>
								 </span>
								 <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
								 <span class="dateReadTime"><?php echo esc_html( get_the_date('M j, Y') ); ?></span>
								 <span class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) );?></span>
								 <a href="<?php the_permalink(); ?>" class="button data-set-button">View Dataset</a>
							 </div>
						 </div>

						 <?php $counter++; ?>
					 <?php } ?>

				 <?php endwhile; else : ?>
					 <h2 class="h3"><?php esc_html_e( 'Sorry, no results found.', 'portal' ); ?></h2>
				 <?php endif; ?>

				 <?php wp_reset_postdata(); wp_reset_query();?>

			 </div>

		 </div>
	 </section>
	<?php endif; ?>
<?php } ?>
</main>

<?php get_footer(); ?>
