
<section class="instagram">
    <div class="container">
        <div>
            <h3>instagram@<a href="https://www.instagram.com/<?php echo get_field('instagram_username', 'options'); ?>" target="_blank"><?php echo get_field('instagram_username', 'options'); ?></a></h3>
            <ul class="photos" data-macy>
        		<?php
                    //https://instagram.com/oauth/authorize/?client_id=a923810abd4a4472919a791e0f73bd3e&redirect_uri=http://localhost:4100&response_type=token
        			$Instagram = new Instagram(get_field('instagram_api', 'options'), get_field('instagram_photos', 'options'), 'standard_resolution');
                    if(isset($Instagram)) {
        			foreach ($Instagram::$result->data as $photo) {
        			    $img = $photo->images->{$Instagram::$display_size};
        			    $img = $img->url;
        			    $link = $photo->link;
        		?>
        		<li>
        			<a href="<?php echo $link; ?>" target="_blank">
                        <?php
								$img_attach_id = attachment_url_to_postid( $img );
								if ( $img_attach_id ) {
									echo wp_get_attachment_image( $img_attach_id, 'full', false, array( 'alt' => '' ) );
								} else {
									echo '<img src="' . esc_url( $img ) . '" loading="lazy" alt="" />';
								}
							?>
                    </a>
        		</li>
        		<?php } } ?>
            </ul>


            <div class="load">
                <a data-load-more class="button">Load More</a>
            </div>

        </div>

    </div>
</section>
