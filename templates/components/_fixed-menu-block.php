<section class="navigation print-no fixed-menu <?php echo esc_attr( get_field( 'fixed_menu_background_colour' ) ); ?> <?php echo esc_attr( get_field( 'hide_main_menu' ) ); ?>">
	<div class="container">
		<ul>
			<li class="mobile"><a class="activeMenuItem"><?php if ( get_field('fixed_menu_title')){ ?><?php echo esc_html( get_field('fixed_menu_title') ); ?><?php } else {?>SECTION<?php } ?></a></li>
            <?php while ( have_rows( 'fixed_menu' ) ) : the_row(); ?>
				<li>
					<a class="scroll-button" href="#<?php echo esc_attr( get_sub_field( 'section_id' ) ); ?>"><?php echo esc_html( get_sub_field( 'section_name' ) ); ?></a>
				</li>
			<?php endwhile; ?>
		</ul>
		<div class="fixedButtonWrapper">
			<a class="fixednav">
				<span class="ham"></span>
			</a>
		</div>
	</div>
</section>
