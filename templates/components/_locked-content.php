<?php
	$postID = get_the_ID();
	$postURL = get_permalink();
?>
<section class="registrationOverlay bottom-registration-overlay">
	<div class="container">
	    <div class="inner">
	        <div class="titleBlock">
	            <h3><?php echo get_field( 'members_only_title', 'option' ); ?></h3>
				<p><?php echo get_field( 'members_only_text', 'option' ); ?></p>
				<?php if ( have_rows( 'members_only_button', 'option' ) ) : ?>
					<?php while ( have_rows( 'members_only_button', 'option' ) ) : the_row(); ?>
						<a class="button" href="<?php echo esc_url( get_sub_field( 'button_link' ) ); ?>" target="_self"><?php echo get_sub_field( 'button_text' ); ?></a>
					<?php endwhile; ?>
				<?php else : ?>
					<?php // no rows found ?>
				<?php endif; ?>
				<span class="already">Already a member? <a class="login" href="/login/?mepr-unauth-page=<?php echo $postID;?>&redirect_to=<?php echo $postURL;?>" target="_self">Login</a>
	        </div>
	    </div>
	</div>
</section>
