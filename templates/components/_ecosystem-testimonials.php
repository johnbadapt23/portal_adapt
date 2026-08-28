
<section class="ecosystem-quote-slider">
	<div class="container">
		<div class="quote-slider-module">
			<?php if ( have_rows( 'slide' ) ) : ?>
				<?php while ( have_rows( 'slide' ) ) : the_row(); ?>
					<div class="quote-slide">
						<div class="quote-slider-inner">
							<h4 class="quote text-black"><?php echo esc_html( get_sub_field( 'quote' ) ); ?></h4>
							<span class="quote-title text-black"><?php echo get_sub_field( 'quoter' ); ?></span>
						</div>
					</div>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>
	</div>
</section>
