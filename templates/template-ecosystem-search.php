<?php
/**
 * Template Name: Ecosystem Partners Listing + Search Template
 */

get_header();
?>
<?php
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only GET filter/search params for a bookmarkable, shareable partner-listing URL; each value is only used for checkbox pre-check comparisons or echoed via esc_attr(), with no state change.
if (isset($_GET['capabilities'])) {
    $capabilities = $_GET['capabilities'];
} else {

}

if (isset($_GET['partner-types'])) {
    $partnerTypes= $_GET['partner-types'];
} else {

}

if (isset($_GET['partner-industries'])) {
    $partnerIndustries= $_GET['partner-industries'];
} else {

}

if (isset($_GET['partner-search'])) {
    $searchTerms = $_GET['partner-search'];
} else {
    $searchTerms = '';
}
// phpcs:enable WordPress.Security.NonceVerification.Recommended

?>


<main id="main" role="main" class="partners partners-search">
	<section class="data-links ecosystems-search-banner">
		<div class="container">
			<span class="breadcrumb-container">
				<a class="home-link" href="/" target="_self">Home</a>
				<span class="divider">/</span>
				<span class="title">Ecosystem Partners</span>
			</span>
			<span class="title-container">
				<h1 clas="h2-style">Ecosystem Partners</h1>
			</span>
		</div>
	</section>
	<section class="ecosystems-search-template">
		<div class="container">			
			<div class="filters-container">
				<div class="filter-sidebar">
					<span class="mobile-filter-trigger">Filters</span>
					<form class="partners-form">
						<span class="searchField">
                            <span class="search">
								<input class="searchInput" type="text" name="partner-search" id="search" value="<?php echo esc_attr( $searchTerms ); ?>" placeholder="Search"/>
								<input type="hidden" value="1" name="sentence" />
								<input class="searchButton" type="image" alt="Search" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/magnify.svg"/>
							</span>
                        </span>
						<span class="partner-filter-group types">
							<?php 
								$terms = get_terms( [
									'post_type' => 'partners',
									'taxonomy' => 'partner-type',
									'parent' => '0',
									'hide_empty' => false
								] ); 
							?>
							<span class="filter-group-title">Type</span>						
							<span class="filter-group-listing button-group">									
								<?php 
									foreach ($terms as $term) { ?>
										<label class="category-checkbox partner-type"><?php echo esc_html( $term->name ); ?>
											<input type="checkbox" name="partner-types[]" class="category-filter" value="<?php echo esc_attr( $term->slug ); ?>" <?php if (!empty($partnerTypes)) { if (in_array($term->slug, $partnerTypes)) { ?> checked <?php } } ?>>
											<span class="checkbox"></span>
										</label> 
									<?php }
								?>
								<?php if (count($terms) > 4) { ?>
									<span class="opacity-layer"></span>
								<?php } ?>
							</span>															
						</span>
						<span class="partner-filter-group capabilities">
							<?php 
								$terms = get_terms( [
									'post_type' => 'partners',
									'taxonomy' => 'capabilities',
									'parent' => '0',
									'hide_empty' => false
								] ); 
							?>
							<span class="filter-group-title">Capabilities</span>						
							<span class="filter-group-listing button-group">									
								<?php 
									foreach ($terms as $term) { ?>
										<label class="category-checkbox partner-capability"><?php echo esc_html( $term->name ); ?>
											<input type="checkbox" name="capabilities[]" class="category-filter" value="<?php echo esc_attr( $term->slug ); ?>" <?php if (!empty($capabilities)) { if (in_array($term->slug, $capabilities)) { ?> checked <?php } } ?>>
											<span class="checkbox"></span>
										</label> 
									<?php }
								?>
								<?php if (count($terms) > 4) { ?>
									<span class="opacity-layer"></span>
								<?php } ?>
							</span>															
						</span>
						<span class="partner-filter-group industries">
							<?php 
								$terms = get_terms( [
									'post_type' => 'partners',
									'taxonomy' => 'industries',
									'parent' => '0',
									'hide_empty' => false
								] ); 
							?>
							<span class="filter-group-title">Industries</span>						
							<span class="filter-group-listing button-group">									
								<?php 
									foreach ($terms as $term) { ?>
										<label class="category-checkbox partner-industries"><?php echo esc_html( $term->name ); ?>
											<input type="checkbox" name="partner-industries[]" class="category-filter" value="<?php echo esc_attr( $term->slug ); ?>" <?php if (!empty($partnerIndustries)) { if (in_array($term->slug, $partnerIndustries)) { ?> checked <?php } } ?>>
											<span class="checkbox"></span>
										</label> 
									<?php }
								?>
								<?php if (count($terms) > 4) { ?>
									<span class="opacity-layer"></span>
								<?php } ?>
							</span>															
						</span>
						<input class="filter-submit" type="submit" value="Filter"/>

					</form>
				</div>
				<div class="partners-listing">
					<?php 
					$args = [
						'no_found_rows'  => true,
						'posts_per_page' => -1,
						'post_type' => 'partners',
						 's' => $searchTerms,
						'tax_query' => [
							'relation' => 'AND', 
						]
					];	

					// Add Partner Capabilities tax query
					if (!empty($capabilities)) {
						$args['tax_query'][] = [
							'taxonomy' => 'capabilities',
							'field' => 'slug',
							'terms' => $capabilities,
							'operator' => 'IN', // Use 'IN' operator to match any of the selected terms within a taxonomy
						];
					}

					// Add Partner Type tax query
					if (!empty($partnerTypes)) {
						$args['tax_query'][] = [
							'taxonomy' => 'partner-type',
							'field' => 'slug',
							'terms' => $partnerTypes,
							'operator' => 'IN', // Use 'IN' operator to match any of the selected terms within a taxonomy
						];
					}

					// Add Partner Industries tax query
					if (!empty($partnerIndustries)) {
						$args['tax_query'][] = [
							'taxonomy' => 'industries',
							'field' => 'slug',
							'terms' => $partnerIndustries,
							'operator' => 'IN', // Use 'IN' operator to match any of the selected terms within a taxonomy
						];
					}

					$loop = new WP_Query( $args  );	
					if ( $loop->have_posts() ) :
						$counter = 0;
						while ( $loop->have_posts() ) : $loop->the_post(); ?>
							<?php $partnerType = get_field( 'partner_type' ); ?>
							<span class="one-third partner-item">
								<span class="partner-inner background-white ">
									<?php if ($partnerType != 'advisor'){ ?> 
										<?php $listing_icon = get_field('listing_icon'); ?>
										<?php if ( $listing_icon ) { ?>
											<span class="logo-container">
												<?php echo wp_get_attachment_image( $listing_icon['ID'], 'full', false, [ 'alt' => $listing_icon['alt'] ] ); ?>
											</span>
										<?php } ?>
									<?php } else { ?>
										<?php $listing_avatar = get_field('listing_avatar'); ?>
										<?php if ( $listing_avatar ) { ?>
											<span class="avatar-container logo-container">
												<?php echo wp_get_attachment_image( $listing_avatar['ID'], 'full', false, [ 'alt' => $listing_avatar['alt'] ] ); ?>
											</span>
										<?php } ?>
									<?php }	?>
									<span class="listing-title"><?php echo esc_html( get_field( 'listing_title' ) ); ?></span>										
									<span class="excerpt-container">
										<?php echo esc_html( get_field( 'listing_excerpt' ) ); ?>
									</span>
									<span class="capabilities-container">
										<span class="capabilities-overflow-container">
											<?php 
												if(get_the_terms( $post->ID, 'capabilities' )){
													$terms = get_the_terms( $post->ID, 'capabilities' );
													foreach($terms as $term) { ?>
														<span class="tag capability"><?php echo esc_html( $term->name ); ?></span>
													<?php }
												}
											?> 
										</span>
									</span>
									<span class="button-container">
										<a class="stdBtn black-outline-button" href="<?php the_permalink(); ?>" target="_self">See more</a>
									</span>
								</span>
							</span>
						<?php 
						endwhile; else : ?>
					<?php 
					endif; ?>
					<?php wp_reset_postdata(); ?> 	
				</div>
			</div>			
		</div>
	</section>	
</main>

<?php get_footer(); ?>
