<?php
/**
 * Template Name: Demo Template
 */

get_header();
?>

<?php

$user_info = get_userdata(1);

$first_name = $user_info->first_name;
$interests = $user_info->mepr_interests;
?>

<main class="main" role="main">

		<section style="padding: 100px 0px;">
			<div class="container">
				<span class="hidden" id="responseText">Text</span>
				<h3>Ajax Test</h3>
				<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="updateUserInterests">
					<?php
					$term_m = 'topic';
					?>
					<?php
					$terms = get_terms( $term_m, array(
						'hide_empty' => false,
						'parent' => 0
					) );
					?>
					<?php foreach($terms as $term) { ?>
						<span class="topic">
							<span class="checkbox-container">
								<input type="checkbox" id="checkbox<?php echo $term->slug;?>" value="on" name="mepr_interests[<?php echo $term->slug; ?>]" <?php if('on'==$interests[$term->slug]) echo 'checked="checked"'; ?>/>
								<label for="checkbox<?php echo $term->slug;?>" class="<?php if('on'==$interests[$term->slug]){ ?>following<?php } else { ?>follow<?php } ?>"><?php if('on'==$interests[$term->slug]){ ?>Following<?php } else { ?>Follow<?php } ?></label>
							</span>
						</span>
					<?php } ?>
					<input type="text" name="project" placeholder="project" value="project" />
					<button style="display:none;">Apply filter</button>
					<input type="hidden" name="action" value="myfilter">
				</form>
				<br />
				<div id="response"></div>

			</div>

		</section><!-- /section -->

	</main><!-- /main -->

<?php get_footer(); ?>
