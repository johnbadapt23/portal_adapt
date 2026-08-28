<?php
/**
 * Template Name: Contact Template
 */

get_header();
?>
<?php $map = get_field('google_map','options'); ?>
<main id="main" class="main" role="main">
	<section class="contactUs" style="background-image: url(<?php echo get_field( 'background_image' ); ?>);">
		<span class="overlay"></span>
		<span class="inner">
			<span class="v-wrap">
				<span class="v-box">
					<h1>Contact Us</h1>
					<h2><?php echo get_field( 'address', 'option' ); ?></h2>
					<span class="details">
						<p><a class="email hvr-underline-from-left" href="mailto:<?php echo get_field( 'email_address', 'option' ); ?>"><?php echo get_field( 'email_address', 'option' ); ?></a><br />
                        <a class="telephone hvr-underline-from-left" href=""><?php echo get_field( 'phone_number', 'option' ); ?></a></p>
                        <p><a class="directionsLink hvr-underline-from-left" href="<?php echo esc_url( get_field( 'google_map_link', 'option' ) ); ?>" target="_blank" rel="noopener noreferrer">Get Directions</a></p>
					</span>
					<span class="socials">
    					<a href="<?php echo esc_url( get_field( 'facebook_link', 'option' ) ); ?>"><i class="icon-facebook"></i></a>
    					<a href="<?php echo esc_url( get_field( 'twitter_link', 'option' ) ); ?>"><i class="icon-twitter"></i></a>
                        <a href="<?php echo esc_url( get_field( 'trip_advisor_link', 'option' ) ); ?>"><i class="icon-trip-advisor"></i></a>
    					<a href="<?php echo esc_url( get_field( 'instagram_link', 'option' ) ); ?>"><i class="icon-instagram"></i></a>
    				</span>
					<span class="baseBtn map"><a href="#" class="button hvr-underline-from-left white">Show Map</a></span>
				</span>
			</span>
		</span>
		<span class="mapBlock">
			<span class="googleMap acf-map">
                <span class="marker" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>"></span>
            </span>
			<span class="closeMap">
                <i class="icon-close"></i>
			</span>
		</span>
	</section>
</main>

<?php get_footer(); ?>
