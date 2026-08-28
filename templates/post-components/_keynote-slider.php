<section class="keynote-slider-module">
	<div class="container">
        <div class="blockTitle">
            <h2 class="headerXsmall text-bold"><?php echo get_sub_field( 'title' ); ?></h2>
            <a href="<?php echo esc_url( get_sub_field( 'view_all_link' ) ); ?>" class="text-link red-text-link uppercase arrow-link">View All</a>
        </div>
    </div>
	<div class="container">
		<div class="keynote-slider">
			<?php if ( have_rows( 'slides' ) ) : ?>
				<?php $counter=1;?>
				<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
					<div class="slide">
						<div class="slide-inner">
							<div class="slide-image-container">
								<span class="image-container">
									<span class="bg-container">
										<?php $image = get_sub_field( 'image' ); ?>
										<?php if ( $image ) { ?>
											<?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
										<?php } ?>
									</span>
									<span class="hover-container background-black">
										<span class="hover-text"><?php echo get_sub_field( 'hover_text' ); ?></span>
										<?php if (get_sub_field( 'link' )) { ?>
											<a class="text-link red-text-link uppercase arrow-link" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
										<?php } ?>
									</span>
								</span>
							</div>
							<span class="slide-details">
								<span class="name text-black"><?php echo get_sub_field( 'title' ); ?></span>
								<span class="name-title text-black"><?php echo get_sub_field( 'sub_title' ); ?></span>
							</span>
						</div>
					</div>
					<?php $counter ++; ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php // no rows found ?>
			<?php endif; ?>
		</div>		
		<div class="keynote-control-container">
			<div class="container">
				<div class="keynote-controls">
					<div class="keynote-slider-dots"></div>
					<div class="keynote-slider-arrows"></div>            
				</div>
			</div>
		</div>
	</div>
</section>
