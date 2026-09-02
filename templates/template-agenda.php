<?php
/**
 * Template Name: Agenda Template
 */

get_header();
?>

<main id="main" role="main" class="agenda">
    <?php
    	$bannerSlides = get_field('banner_slides');
    	$bannerButtons = get_field('banner_buttons');
    ?>
    <?php if($bannerSlides) { ?>
    	<section class="banner">
    		<ul class="slides">
    			<?php foreach($bannerSlides as $slide) { ?>
    				<li style="background-image:url(<?php echo esc_url( $slide['image'] ); ?>);">
    					<?php if( $slide['dark_overlay'] == 'yes') { ?>
    						<span class="dark-overlay"></span>
    					<?php } ?>
    					<?php
					$slide_image_attach_id = adapt_attachment_url_to_postid( $slide['image'] );
					if ( $slide_image_attach_id ) {
						echo wp_get_attachment_image( $slide_image_attach_id, 'full', false, [
							'alt'   => 'Adapt - ' . get_the_title(),
							'style' => 'visibility:hidden; position:absolute; top:-10000px; left:-10000px;',
						] );
					} else {
						echo '<img src="' . esc_url( $slide['image'] ) . '" style="visibility:hidden; position:absolute; top:-10000px; left:-10000px;" loading="lazy" decoding="async" alt="Adapt - ' . esc_attr( get_the_title() ) . '" />';
					}
				?>
    					<div class="container">
    						<div class="content">
    							<?php if($slide['inset_image']) { ?>
    								<div class="insetImage">
    									<div class="image" style="background-image:url(<?php echo esc_url( $slide['inset_image'] ); ?>);">
    									</div>
    									<?php
					$slide_inset_image_attach_id = adapt_attachment_url_to_postid( $slide['inset_image'] );
					if ( $slide_inset_image_attach_id ) {
						echo wp_get_attachment_image( $slide_inset_image_attach_id, 'full', false, [
							'alt'   => 'Adapt - ' . get_the_title(),
							'style' => 'visibility:hidden; position:absolute; top:-10000px; left:-10000px;',
						] );
					} else {
						echo '<img src="' . esc_url( $slide['inset_image'] ) . '" style="visibility:hidden; position:absolute; top:-10000px; left:-10000px;" loading="lazy" decoding="async" alt="Adapt - ' . esc_attr( get_the_title() ) . '" />';
					}
				?>
    								</div>
    							<?php } ?>
    							<?php if($slide['title']) { ?>
    								<div class="column title">
    									<span class="title"><?php echo esc_html( $slide['title'] ); ?></span>
    								</div>
    							<?php } ?>
    							<?php if($slide['text']) { ?>
    								<div class="column text">
    									<span class="text"><?php echo esc_html( $slide['text'] ); ?></span>
    								</div>
    							<?php } ?>
    							<?php if($slide['video']) { ?>
    								<span class="videoLink">
    									<a href="#" class="playBtn">
    										<span class="icon">
    											<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/play.svg" width="51" height="51" loading="lazy" decoding="async" alt="Play Icon" />
    										</span>
    										<span class="text">
    											<span><?php if($slide['video'][0]['video_button_text']) { ?><?php echo esc_html( $slide['video'][0]['video_button_text'] ); ?><?php } else { ?>Watch Video<?php } ?></span>
    											<span><?php echo esc_html( $slide['video'][0]['duration'] ); ?></span>
    										</span>
    									</a>
    								</span>
    							<?php } ?>
    						</div>
    					</div>
    				</li>
    			<?php } ?>
    			<?php foreach($bannerSlides as $slide) { ?>
    				<?php if($slide['video']) { ?>
    					<div class="videoPlayerContainer">
    						<span class="closeVideo"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/close-grey.svg" width="25" height="25" loading="lazy" decoding="async" alt="close" /></span>
    						<div class="videoWrapper">
    							<video width="100%" id="popupVideo" controls controlsList="nodownload">
    								<source type="video/mp4" src="<?php echo esc_url( $slide['video'][0]['vimeo_code'] ); ?>" />
    							</video>
    						</div>
    					</div>
    				<?php } ?>
    			<?php } ?>
    		</ul>
    	</section>
    <?php } ?>

    <section class="navigation">
        <div class="container">
            <?php if( get_field('pre_event') == 'yes') {
                    if ( have_rows( 'day' ) ) : $counter = 0; ?>
                    <ul>
                        <?php while ( have_rows( 'day' ) ) : the_row(); ?>
                            <li>
                                <a class="scroll-button" href="#day<?php echo esc_attr( $counter ); ?>"><?php if ($counter == 0) {?>Pre Event<?php } else {?>Day <?php echo esc_html( $counter ); }?></a>
                            </li>
                        <?php $counter ++; endwhile;  ?>
                        <li class="register">
                            <a class="popup-modal registerInterest" href="#form">Register Interest</a>
                        </li>
                    </ul>
                    <?php endif;
                } else {
                    if ( have_rows( 'day' ) ) : $counter = 1; ?>
                    <ul>
                        <?php while ( have_rows( 'day' ) ) : the_row(); ?>
                            <li>
                                <a class="scroll-button" href="#day<?php echo esc_attr( $counter ); ?>">Day <?php echo esc_html( $counter ); ?></a>
                            </li>
                        <?php $counter ++; endwhile;  ?>
                        <li class="register">
                            <a class="popup-modal registerInterest" href="#form">Register Interest</a>
                        </li>
                    </ul>
                    <?php endif; ?>
                <?php } ?>
        </div>
    </section>
    <section class="eventShare">
    	<div class="container">
            <div class="inner">
        		<div class="share">
        			<a class="emailShare" href="mailto:?&subject=<?php echo esc_html( get_the_title() ); ?>&body=I%20thought%20you%20might%20be%20interested%20in%20this%20article%20<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/email.svg" width="25" height="25" loading="lazy" decoding="async" alt="Share via Email" /><span>Email</span></a>
                    <a class="liShare" href="https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php echo esc_html( get_the_title() ); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/linkedin-black.svg" width="24" height="24" loading="lazy" decoding="async" alt="Share on LinkedIn" /><span>Share</span></a>
        		</div>
            </div>
    	</div>
    </section>

    <?php if ( have_rows( 'day' ) ) : if( get_field('pre_event') == 'yes') { $counter = 0; } else { $counter = 1; } ?>

        <?php while ( have_rows( 'day' ) ) : the_row(); ?>
            <section id="day<?php echo esc_attr( $counter ); ?>" class="dayWrapper day<?php echo esc_attr( $counter ); ?> active">
                <div class="container">
                    <div class="inner">
                        <div class="titleBlock">
                            <h1 class="pageTitleLine"><?php echo esc_html( get_the_title() ); ?></h1>
                            <div class="top">
                                <span class="left">
                                    <h2><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                                    <hr>
                                </span>
                                <span class="right">
                                    <a class="button popup-modal registerInterest" href="#form">Register Interest</a>
                                    <?php if ( get_field( 'ticket_link' )) { ?>
                                        <a class="button ticket buttonTicket" href="<?php echo esc_url( get_field( 'ticket_link' ) ); ?>" target="_blank" rel="noopener noreferrer">Purchase Tickets</a>
                                    <?php } ?>
                                    <a class="button print buttonPrint" id="print" onclick="window.print()">Print Agenda</a>
                                </span>
                            </div>
                            <div class="bottom">
                                <h3><?php echo esc_html( get_sub_field( 'date' ) ); ?></h3>
                        		<h4><?php echo esc_html( get_sub_field( 'day' ) ); ?></h4>
                                <a class="button popup-modal mobile registerInterest" href="#form">Register Interest</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <?php if ( have_rows( 'itinerary_item' ) ) : ?>
                <section class="itineraryBlock">
					<?php while ( have_rows( 'itinerary_item' ) ) : the_row(); ?>
                        <?php if ( get_sub_field ( 'single_or_double_track' ) == 'single' ) { ?>
                            <div class="agendaBlock single<?php if ( get_sub_field ( 'show_underline' ) == 'noBorderBottom' ) { ?> noBorderBottom<?php } ?>">
        						<div class="item">
        							<div class="container">
        								<div class="inner">
                                            <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">

                                                <div class="time">
                                                    <div class="v-wrap">
                                                        <div class="v-box left">
                                                           <?php echo esc_html( get_sub_field( 'time' ) ); ?>
                                                       </div>
                                                    </div>
                                                </div>

                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                    <div class="logoWrapper">
                                    					<?php while ( have_rows( 'logos' ) ) : the_row(); ?>

                                                            <span class="logoContainer">
                                                                <span class="logo" style="background-image: url(<?php echo esc_url( get_sub_field('logo') ); ?>);">
                                                                </span>
                                                            </span>

                                    					<?php endwhile; ?>
                                                    </div>
                                				<?php endif; ?>
                                                <div class="detailWrap">
                                                    <div class="v-wrap">
                                                        <div class="v-box left">
                        									<span class="title">
                        										<?php echo esc_html( get_sub_field( 'title' ) ); ?>
                        									</span>
                                                            <?php if ( have_rows( 'speakers' ) ) : ?>
                                            					<?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                                            						<?php $post_object = get_sub_field( 'speaker' ); ?>
                                            						<?php if ( $post_object ): ?>
                                            							<?php $post = $post_object; ?>
                                            							<?php setup_postdata( $post ); ?>
                                            								<span class="description"><a  href="<?php the_permalink(); ?>" class="speakerName"><?php echo esc_html( get_the_title() ); ?>&nbsp;-&nbsp;</a><span class="speakerTitle"><?php echo esc_html( get_field('speaker_description') ); ?></span></span>
                                            							<?php wp_reset_postdata(); ?>
                                            						<?php endif; ?>
                                            					<?php endwhile; ?>
                                            				<?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>
                                                <div class="hidden">
                                                    <span class="detail">
                                                        <?php echo wp_kses_post( get_sub_field( 'detailed_text' ) ); ?>
                                                    </span>
                                                </div>
                                                <span class="read-more-container">
                                                    <span class="read-more">+ Read More</span>
                                                </span>
                                            <?php } ?>
        								</div>
        							</div>
        						</div>
                            </div>
                        <?php } else { ?>
                            <div class="agendaBlock double<?php if ( get_sub_field ( 'show_underline' ) == 'noBorderBottom' ) { ?> noBorderBottom<?php } ?>">
                                <?php if ( have_rows( 'double_track_details' ) ) : ?>
                                    <div class="headerBlock">
                                        <div class="container">
                                            <div class="inner">
                                    			<?php while ( have_rows( 'double_track_details' ) ) : the_row(); ?>
                                                    <div class="column">
                                                        <span class="trackTitle">
                            				                <?php echo esc_html( get_sub_field( 'track_title' ) ); ?>
                                                        </span>
                                                        <span class="hrWrapper">
                                                            <hr>
                                                        </span>
                                                        <span class="facilitator">
                                            				<?php echo esc_html( get_sub_field( 'facilitator' ) ); ?>
                                                        </span>
                                                        <span class="facilitatorTitle">
                                                            <?php echo esc_html( get_sub_field( 'facilitator_title' ) ); ?>
                                                        </span>
                                                    </div>
                                    			<?php endwhile; ?>
                                            </div>
                                        </div>
                                    </div>
                        		<?php endif; ?>
                                <div class="item">
        							<div class="container">
        								<div class="inner">

                                            <div class="time">
                                                <div class="v-wrap">
                                                    <div class="v-box left">
                                                       <?php echo esc_html( get_sub_field( 'time' ) ); ?>
                                                   </div>
                                               </div>
                                           </div>

                                            <div class="columnsWrapper">
                                                <div class="left">
                                                    <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">
                                                        <div class="detailWrap">
                                                            <div class="v-wrap">
                                                                <div class="v-box left">
                                									<span class="title">
                                										<?php echo esc_html( get_sub_field( 'title' ) ); ?>
                                									</span>
                                                                    <?php if ( have_rows( 'speakers' ) ) : ?>
                                                    					<?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                                                    						<?php $post_object = get_sub_field( 'speaker' ); ?>
                                                    						<?php if ( $post_object ): ?>
                                                    							<?php $post = $post_object; ?>
                                                    							<?php setup_postdata( $post ); ?>
                                                                                    <span class="description"><a  href="<?php the_permalink(); ?>" class="speakerName"><?php echo esc_html( get_the_title() ); ?>&nbsp;-&nbsp;</a><span class="speakerTitle"><?php echo esc_html( get_field('speaker_description') ); ?></span></span>
                                                    							<?php wp_reset_postdata(); ?>
                                                    						<?php endif; ?>
                                                    					<?php endwhile; ?>
                                                    				<?php endif; ?>

                                                                    <?php if ( have_rows( 'logos' ) ) : ?>
                                                                        <div class="logoWrapper">
                                                        					<?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                                                <span class="logoContainer">
                                                                                    <span class="logo" style="background-image: url(<?php echo esc_url( get_sub_field('logo') ); ?>);">
                                                                                    </span>
                                                                                </span>
                                                        					<?php endwhile; ?>
                                                                        </div>
                                                    				<?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>
                                                        <div class="hidden">
                                                            <span class="detail">
                                                                <?php echo wp_kses_post( get_sub_field( 'detailed_text' ) ); ?>
                                                            </span>
                                                        </div>
                                                        <span class="read-more-container">
                                                            <span class="read-more">+ Read More</span>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                                <div class="right">
                                                    <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">

                                                        <div class="detailWrap">
                                                            <div class="v-wrap">
                                                                <div class="v-box left">
                                									<span class="title">
                                										<?php echo esc_html( get_sub_field( 'title_track_two' ) ); ?>
                                									</span>
                                                                    <?php if ( have_rows( 'speakers_track_two' ) ) : ?>
                                                    					<?php while ( have_rows( 'speakers_track_two' ) ) : the_row(); ?>
                                                    						<?php $post_object = get_sub_field( 'speaker' ); ?>
                                                    						<?php if ( $post_object ): ?>
                                                    							<?php $post = $post_object; ?>
                                                    							<?php setup_postdata( $post ); ?>
                                                                                    <span class="description"><a  href="<?php the_permalink(); ?>" class="speakerName"><?php echo esc_html( get_the_title() ); ?>&nbsp;-&nbsp;</a><span class="speakerTitle"><?php echo esc_html( get_field('speaker_description') ); ?></span></span>
                                                    							<?php wp_reset_postdata(); ?>
                                                    						<?php endif; ?>
                                                    					<?php endwhile; ?>
                                                    				<?php endif; ?>
                                                                    <?php if ( have_rows( 'logos_track_two' ) ) : ?>
                                                                        <div class="logoWrapper">
                                                        					<?php while ( have_rows( 'logos_track_two' ) ) : the_row(); ?>
                                                                                <span class="logoContainer">
                                                                                    <span class="logo" style="background-image: url(<?php echo esc_url( get_sub_field('logo') ); ?>);">
                                                                                    </span>
                                                                                </span>
                                                        					<?php endwhile; ?>
                                                                        </div>
                                                    				<?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>
                                                        <div class="hidden">
                                                            <span class="detail">
                                                                <?php echo wp_kses_post( get_sub_field( 'detailed_text_track_two' ) ); ?>
                                                            </span>
                                                        </div>
                                                        <span class="read-more-container">
                                                            <span class="read-more">+ Read More</span>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            </div>
        								</div>
        							</div>
        						</div>
                            </div>
                        <?php } ?>
					<?php endwhile; ?>
				</section>
			<?php endif; ?>

        <?php $counter ++; endwhile;  ?>

    <?php endif; ?>
</main>

<section class="printContainer">
    <div class="imageHeader"><?php
					$inline_img_137_src = get_field( 'print_header' );
					$inline_img_137_attach_id = $inline_img_137_src ? adapt_attachment_url_to_postid( $inline_img_137_src ) : 0;
					if ( $inline_img_137_attach_id ) {
						echo wp_get_attachment_image( $inline_img_137_attach_id, 'full', false, [ 'alt' => 'Adapt - ' . get_the_title() ] );
					} elseif ( $inline_img_137_src ) {
						echo '<img src="' . esc_url( $inline_img_137_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( 'Adapt - ' . get_the_title() ) . '" />';
					}
				?></div>
    <div class="content">
    <?php if ( have_rows( 'day' ) ) : $counter = 1; ?>

        <?php while ( have_rows( 'day' ) ) : the_row(); ?>

            <div class="titleBlock">
                <div class="container">

                    <h2><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>


                    <h3><?php echo esc_html( get_sub_field( 'day' ) ); ?> <?php echo esc_html( get_sub_field( 'date' ) ); ?></h3>

                </div>

            </div>

            <?php if ( have_rows( 'itinerary_item' ) ) : ?>

                    <?php while ( have_rows( 'itinerary_item' ) ) : the_row(); ?>
                        <?php if ( get_sub_field ( 'single_or_double_track' ) == 'single' ) { ?>
                            <div class="agendaBlock single<?php if ( get_sub_field ( 'show_underline' ) == 'noBorderBottom' ) { ?> noBorderBottom<?php } ?>">
                                <div class="item">
                                    <div class="container">
                                        <div class="wrapperPrint">
                                            <div class="leftColumn">
                                                <div class="timePrint">
                                                   <?php echo esc_html( get_sub_field( 'time' ) ); ?>
                                                </div>
                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                    <div class="logoWrapperPrint">
                                                        <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                            <?php
					$inline_img_138_src = get_sub_field( 'logo' );
					$inline_img_138_attach_id = $inline_img_138_src ? adapt_attachment_url_to_postid( $inline_img_138_src ) : 0;
					if ( $inline_img_138_attach_id ) {
						echo wp_get_attachment_image( $inline_img_138_attach_id, 'full', false, [ 'alt' => 'Adapt', 'width' => '100' ] );
					} elseif ( $inline_img_138_src ) {
						echo '<img src="' . esc_url( $inline_img_138_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( 'Adapt' ) . '" />';
					}
				?>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="rightColumn">
                                                <div class="detailWrapPrint">
                                                    <span class="title">
                                                        <?php echo esc_html( get_sub_field( 'title' ) ); ?>
                                                    </span>
                                                    <?php if ( have_rows( 'speakers' ) ) : ?>
                                                        <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                                                            <?php $post_object = get_sub_field( 'speaker' ); ?>
                                                            <?php if ( $post_object ): ?>
                                                                <?php $post = $post_object; ?>
                                                                <?php setup_postdata( $post ); ?>
                                                                    <span class="description"><span class="speakerName"><?php echo esc_html( get_the_title() ); ?>&nbsp;-&nbsp;</span><span class="speakerTitle"><?php echo esc_html( get_field('speaker_description') ); ?></span></span>
                                                                <?php wp_reset_postdata(); ?>
                                                            <?php endif; ?>
                                                        <?php endwhile; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>
                                                    <span class="detail">
                                                        <?php echo wp_kses_post( get_sub_field( 'detailed_text' ) ); ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="agendaBlock double<?php if ( get_sub_field ( 'show_underline' ) == 'noBorderBottom' ) { ?> noBorderBottom<?php } ?>">
                                <?php if ( have_rows( 'double_track_details' ) ) : ?>
                                    <div class="headerBlock">
                                        <div class="container">

                                            <?php while ( have_rows( 'double_track_details' ) ) : the_row(); ?>
                                                <div class="column">
                                                    <span class="trackTitle">
                                                        <?php echo esc_html( get_sub_field( 'track_title' ) ); ?>
                                                    </span>
                                                    <span class="hrWrapper">
                                                        <hr>
                                                    </span>
                                                    <span class="facilitator">
                                                        <?php echo esc_html( get_sub_field( 'facilitator' ) ); ?>
                                                    </span>
                                                    <span class="facilitatorTitle">
                                                        <?php echo esc_html( get_sub_field( 'facilitator_title' ) ); ?>
                                                    </span>
                                                </div>
                                            <?php endwhile; ?>

                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="item">
                                    <div class="container">

                                        <div class="time">
                                            <div class="v-wrap">
                                                <div class="v-box left">
                                                   <?php echo esc_html( get_sub_field( 'time' ) ); ?>
                                               </div>
                                           </div>
                                       </div>

                                        <div class="columnsWrapper">
                                            <div class="left">
                                                <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">
                                                    <div class="detailWrap">
                                                        <div class="v-wrap">
                                                            <div class="v-box left">
                                                                <span class="title">
                                                                    <?php echo esc_html( get_sub_field( 'title' ) ); ?>
                                                                </span>
                                                                <?php if ( have_rows( 'speakers' ) ) : ?>
                                                                    <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>
                                                                        <?php $post_object = get_sub_field( 'speaker' ); ?>
                                                                        <?php if ( $post_object ): ?>
                                                                            <?php $post = $post_object; ?>
                                                                            <?php setup_postdata( $post ); ?>
                                                                                <span class="description"><span class="speakerName"><?php echo esc_html( get_the_title() ); ?>&nbsp;-&nbsp;</span><span class="speakerTitle"><?php echo esc_html( get_field('speaker_description') ); ?></span></span>
                                                                            <?php wp_reset_postdata(); ?>
                                                                        <?php endif; ?>
                                                                    <?php endwhile; ?>
                                                                <?php endif; ?>

                                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                                    <div class="logoWrapperPrint">
                                                                        <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                                            <?php
					$inline_img_139_src = get_sub_field( 'logo' );
					$inline_img_139_attach_id = $inline_img_139_src ? adapt_attachment_url_to_postid( $inline_img_139_src ) : 0;
					if ( $inline_img_139_attach_id ) {
						echo wp_get_attachment_image( $inline_img_139_attach_id, 'full', false, [ 'alt' => 'Adapt', 'width' => '100' ] );
					} elseif ( $inline_img_139_src ) {
						echo '<img src="' . esc_url( $inline_img_139_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( 'Adapt' ) . '" />';
					}
				?>
                                                                        <?php endwhile; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>

                                                    <span class="detail">
                                                        <?php echo wp_kses_post( get_sub_field( 'detailed_text' ) ); ?>
                                                    </span>

                                                <?php } ?>
                                            </div>
                                            <div class="right">
                                                <div class="wrapper<?php if ( get_sub_field ( 'detailed_text' ) ) { ?> arrow<?php } ?>">

                                                    <div class="detailWrap">
                                                        <div class="v-wrap">
                                                            <div class="v-box left">
                                                                <span class="title">
                                                                    <?php echo esc_html( get_sub_field( 'title_track_two' ) ); ?>
                                                                </span>
                                                                <?php if ( have_rows( 'speakers_track_two' ) ) : ?>
                                                                    <?php while ( have_rows( 'speakers_track_two' ) ) : the_row(); ?>
                                                                        <?php $post_object = get_sub_field( 'speaker' ); ?>
                                                                        <?php if ( $post_object ): ?>
                                                                            <?php $post = $post_object; ?>
                                                                            <?php setup_postdata( $post ); ?>
                                                                                <span class="description"><span class="speakerName"><?php echo esc_html( get_the_title() ); ?>&nbsp;-&nbsp;</span><span class="speakerTitle"><?php echo esc_html( get_field('speaker_description') ); ?></span></span>
                                                                            <?php wp_reset_postdata(); ?>
                                                                        <?php endif; ?>
                                                                    <?php endwhile; ?>
                                                                <?php endif; ?>
                                                                <?php if ( have_rows( 'logos_track_two' ) ) : ?>
                                                                    <div class="logoWrapperPrint">
                                                                        <?php while ( have_rows( 'logos_track_two' ) ) : the_row(); ?>
                                                                            <?php
					$inline_img_140_src = get_sub_field( 'logo' );
					$inline_img_140_attach_id = $inline_img_140_src ? adapt_attachment_url_to_postid( $inline_img_140_src ) : 0;
					if ( $inline_img_140_attach_id ) {
						echo wp_get_attachment_image( $inline_img_140_attach_id, 'full', false, [ 'alt' => 'Adapt', 'width' => '100' ] );
					} elseif ( $inline_img_140_src ) {
						echo '<img src="' . esc_url( $inline_img_140_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( 'Adapt' ) . '" />';
					}
				?>
                                                                        <?php endwhile; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ( get_sub_field ( 'detailed_text' ) ) { ?>

                                                    <span class="detail">
                                                        <?php echo wp_kses_post( get_sub_field( 'detailed_text_track_two' ) ); ?>
                                                    </span>

                                                <?php } ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php endwhile; ?>

            <?php endif; ?>

        <?php $counter ++; endwhile;  ?>

    <?php endif; ?>
    </div>

</section>

<?php get_footer(); ?>
