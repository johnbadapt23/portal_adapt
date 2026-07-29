<?php
/**
 * Template Name: Portal Flexible Template
 */

get_header();
?>

<style>
.custom-gpt-wrapper {
	
	margin-bottom: 80px;
}
/* Chat box only */
.custom-gpt-main-wrapper {
    position: relative;
    background: #fff;
    border: 1px solid #F2EAEA;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 28px rgba(0, 0, 0, .04);
    max-width: 934px;
    margin: auto;
    height: 425px;
    display: flex;
}
div#customgpt_chat {
    width: 100%;
    display: flex;
    align-items: flex-end;
    padding-top: 484px;
}
/* .custom-gpt-main-wrapper::before{
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(
        90deg,
        #E7534F 0%,
        #F6CACA 45%,
        transparent 100%
    );
    background: #E7534F;
    opacity: .5;
    z-index: 10;
} */
.custom-gpt-wrapper-header {
    text-align: center;
    /* margin-bottom: -240px; */
    position: relative;
    z-index: 2;
    background: #fff;
    padding-top: 0px;
	margin-top: 80px;
}
.custom-gpt-wrapper-header h2 {
    margin-bottom: 5px;
}
.custom-gpt-wrapper-header h2 span {
    color: #E7534F;
}
/* .custom-gpt-wrapper-header{
    position: relative;
    background: #fff;
    padding: 50px 40px 10px;
    border-radius: 18px 18px 0 0;
    border-bottom: 1px solid #F1E6E6;
}

.custom-gpt-wrapper-header::after{
    content: "";
    position: absolute;
    bottom: 0;
    left: 40px;
    right: 40px;
    height: 1px;
    background: #F3E8E8;
} */

body.cgpt-active .custom-gpt-wrapper-header {
    /* position: relative;
    background: #fff;
    padding: 50px 40px 10px;
    border-radius: 18px 18px 0 0;
    border-bottom: 1px solid #f1e6e6; */
	display: none;
}

/* body.cgpt-active .custom-gpt-wrapper-header::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 40px;
    right: 40px;
    height: 1px;
	background: #f3e8e8;
} */
/* body.cgpt-active .cgptcb-embed-chat-box-container {
    padding-top: 260px;
} */
@media (min-width: 1025px){
	.custom-gpt-wrapper-header {
		margin-top: 150px;
	}
}

body:not(.cgpt-active) div#cgptcb-embed-chat-box-header {
    display: none !important;
}
div#cgptcb-embed-chat-box-container {
    position: relative;
}

body:not(.cgpt-active) div#cgptcb-embed-chat-box-container:after {
    content: '';
    position: absolute;
    bottom: 45px;
    left: 0;
    right: 0;
    height: 36px;
    z-index: 1;
    background: #fff;
}
body.cgpt-active .cgptcb-overlay {
    opacity: 1;
    visibility: visible;
    z-index: 2;
}

body.cgpt-active {
    overflow: hidden;
}

body.cgpt-active .custom-gpt-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: 0;
    z-index: 9999999999;
    display: flex;
    align-items: center;
    justify-content: center;
}
body.cgpt-active .custom-gpt-main-wrapper{
    width: 100%;
    height: 700px;
}
body.cgpt-active  div#customgpt_chat{
    padding-top: 0;
}

/* div#cgptcb-embed-chat-box-container:before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 50px;
    background: #fff;
} */
body.template-portal-flexible:not(.cgpt-active) #main{
	padding-top: 0 !important;
}
body.template-portal-flexible:not(.cgpt-active) section.featured-module{
	padding-top: 20px !important; 
}





:root {
  --cgpt-ease: cubic-bezier(.22, 1, .36, 1);
  --cgpt-dur: .55s;
}

/* Smooth the box's size/shape changes between inline <-> modal */
.custom-gpt-main-wrapper {
  transition:
    height var(--cgpt-dur) var(--cgpt-ease),
    width var(--cgpt-dur) var(--cgpt-ease),
    border-radius var(--cgpt-dur) var(--cgpt-ease),
    box-shadow .45s ease,
    transform var(--cgpt-dur) var(--cgpt-ease);
  will-change: transform;
}

div#customgpt_chat {
  transition: padding-top var(--cgpt-dur) var(--cgpt-ease);
}

/* Idle float — resting state only */
body:not(.cgpt-active) .custom-gpt-main-wrapper {
  /* animation: cgpt-float 6s ease-in-out infinite; */
}

@keyframes cgpt-float {
  0%, 100% {
    transform: translateY(0);
    box-shadow: 0 10px 28px rgba(0, 0, 0, .04);
  }
  50% {
    transform: translateY(-8px);
    box-shadow: 0 22px 44px rgba(0, 0, 0, .08);
  }
}

/* Lift a little more on hover */
body:not(.cgpt-active) .custom-gpt-main-wrapper:hover {
  animation-play-state: paused;
  transform: translateY(-10px);
  box-shadow: 0 26px 50px rgba(0, 0, 0, .10);
}

/* Entrance when it goes fullscreen */
body.cgpt-active .custom-gpt-wrapper {
  animation: cgpt-rise .5s var(--cgpt-ease) both;
}

@keyframes cgpt-rise {
  from { opacity: 0; transform: translateY(28px) scale(.96); }
  to   { opacity: 1; transform: none; }
}

/* Fade the backdrop instead of snapping it */
.cgptcb-overlay {
  opacity: 0;
  visibility: hidden;
  transition: opacity .4s ease, visibility 0s linear .4s;
}

body.cgpt-active .cgptcb-overlay {
  transition: opacity .4s ease, visibility 0s;
}

@media (prefers-reduced-motion: reduce) {
  .custom-gpt-main-wrapper,
  body.cgpt-active .custom-gpt-wrapper,
  body:not(.cgpt-active) .custom-gpt-main-wrapper {
    animation: none !important;
    transition-duration: .01ms !important;
  }
}

#cgptcb-embed-chat-box-header {
  position: relative;
}

.cgpt-close-btn {
  /* position: absolute;
  top: 50%;
  right: 16px;
  transform: translateY(-50%); */
  position: relative;
  display: block;
  width: 34px;
  height: 34px;
  /* border-radius: 50%;
  background: rgba(0, 0, 0, .04); */
  cursor: pointer;
  z-index: 10;
  user-select: none;
  transition: background .2s ease, transform .2s ease;
}

.cgpt-close-btn::before,
.cgpt-close-btn::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 14px;
  height: 2px;
  border-radius: 2px;
  background: #fff;
}

.cgpt-close-btn::before { transform: translate(-50%, -50%) rotate(45deg); }
.cgpt-close-btn::after  { transform: translate(-50%, -50%) rotate(-45deg); }
.cgptcb-chat-bubble, .cgptcb-tooltip{
	display: none !important;
}
</style>
<div class="custom-gpt-wrapper-header">
	<h2><span>ADAPT</span> Intelligence</h2>
	<p>Find the insight. Frame your message. All in one place.</p>
</div>
<div class="custom-gpt-wrapper">
	<div class="custom-gpt-main-wrapper">
		<div id="customgpt_chat"></div>
	</div>
</div>

<!-- <script src="https://cdn.customgpt.ai/js/embed.js" defer div_id="customgpt_chat" p_id="98043" p_key="8c7e9ac540d9dd825d6cf4eab0ade038"></script>  -->
<script src="https://cdn.customgpt.ai/js/embed.js" defer div_id="customgpt_chat" p_id="98865" p_key="f12d51cc482847f28a6333cf7f6a5c9d"></script> 

<?php if ($membershipType == 'free-trial') { ?>
	<main id="main" role="main" class="home freeTrial">
		<?php if ( have_rows( 'trial_membership_content_blocks' ) ) : ?>
			<?php $flexibleCounter = 1; ?>
			<?php while ( have_rows( 'trial_membership_content_blocks' ) ) : the_row(); ?>
				<?php $trialMembership = get_sub_field( 'membership_id' ); ?>
				<?php if(current_user_can('mepr-active','memberships:' . $trialMembership)){ ?>
					<?php if ( have_rows( 'membership_content' ) ): ?>
    					<?php while ( have_rows( 'membership_content' ) ) : the_row(); ?>
    						<?php if ( get_row_layout() == 'featured_presentation' ) : ?>
								<?php $presentation = get_sub_field( 'presentation' ); ?>
								<?php if ( $presentation ): ?>
									<?php foreach ( $presentation as $post ):  ?>
										<?php setup_postdata ( $post ); ?>
											<section class="expertPresentationFeatured trial-featured bg-dark">
												<div class="container">
													<h2><?php echo get_sub_field( 'title' ); ?></h2>
													<div class="imageSizeContainer">
														<span class="overlayGradient"></span>
														<a href="<?php the_permalink(); ?>" target="_self" class="bgContainer">
															<?php if ( get_field( 'listing_image') ) { ?>
																<?php $image = get_field( 'listing_image'); ?>
																 <img class="desktop" src="<?php echo $image; ?>" />
															<?php } elseif ( get_field( 'video_image' )){  ?>
																<?php $video_image = get_field( 'video_image' ); ?>
																<img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
															<?php } else { ?>
																<?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
																	<?php $image = get_field( 'video_poster'); ?>
																<?php } else { ?>
																	<?php $image = get_field( 'featured_image'); ?>
																<?php } ?>
																<img class="desktop" src="<?php echo $image; ?>" />
															<?php } ?>
														</a>
														<span class="watchIcon"></span>
														<span class="textContainer">
															<span class="topicFilter">
																<?php if (yoast_get_primary_term_id('topic')) {
																	$primary_term_topic_id = yoast_get_primary_term_id('topic');
																	$postTopic = get_term( $primary_term_topic_id );
																} else {
																	if(get_the_terms( $post->ID, 'topic' )){
																		$terms = get_the_terms( $post->ID, 'topic' );
																		foreach($terms as $term) {
																			$postTopic = $term;
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
																<?php if($postTopic){?>
																	<a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
																<?php } ?>

																	<a href="/filter-types/expert-presentations/" class="topicFilterText">Expert Presentations</a>
															</span>
															<a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
														</span>
													</div>
												</div>
											</section>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php wp_reset_postdata(); ?>
								<?php wp_reset_query(); ?>
							<?php elseif ( get_row_layout() == 'banner' ) : ?>
								<?php if ( $flexibleCounter == 1 ) { ?>
									<section class="topicBanner">
							        <div class="imageSizeContainer">
							            <div class="bgContainer">
							    			<?php $banner_image =  get_sub_field( 'background_image' ); ?>
							                <img class="desktop" src="<?php echo $banner_image['url']; ?>" alt="<?php echo $banner_image['alt']; ?>" />
							            </div>
							            <div class="container">
							                <h1><?php echo get_sub_field( 'title' ); ?></h1>
							                <p><?php echo get_sub_field( 'description' ); ?></p>
							            </div>
							        </div>
							    </section>
								<?php } ?>
    						<?php elseif ( get_row_layout() == 'featured_grid_module' ) : ?>
    							<?php $subscription_term = get_sub_field( 'subscription' ); ?>
    							<?php if ( $subscription_term ): ?>
									<section class="portal topicGrid bg-dark trial-grid">
		                                <div class="container">
		                                    <div class="blockTitle">
		                                        <h2><?php echo get_sub_field( 'title' ); ?></h2>
		                                    </div>
		                                    <div class="gridWrapper">
		                                        <?php
		                                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		                                            $args = array(
		                                                'post_type'      => 'post',
		                                                'posts_per_page' => -1,
		                                                'paged'=> $paged,
		                                                'tax_query'      => array(
		                                                    array(
		                                                        'taxonomy' => 'subscription',
		                                                        'field'    => 'slug',
		                                                        'terms'    => $subscription_term->slug
		                                                    )
		                                                ),
		                                            );

		                                            $posts = new WP_Query( $args );
		                                            if( $posts->have_posts() ): ?>
		                                                <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
															<div class="item">
 		                                                       <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
 		                                                           <div class="bgContainer">
 		                                                               <?php if ( get_field( 'listing_image') ) { ?>
 		                                                                   <?php $image = get_field( 'listing_image'); ?>
 		                                                                    <img class="desktop" src="<?php echo $image; ?>" />
 		                                                               <?php } elseif ( get_field( 'video_image' )){  ?>
 		                                                                   <?php $video_image = get_field( 'video_image' ); ?>
 		                                                                   <img class="desktop" src="<?php echo $video_image['url']; ?>" alt="<?php echo $video_image['alt']; ?>" />
 		                                                               <?php } else { ?>
 		                                                                   <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
 		                                                                       <?php $image = get_field( 'video_poster'); ?>
 		                                                                   <?php } else { ?>
 		                                                                       <?php $image = get_field( 'featured_image'); ?>
 		                                                                   <?php } ?>
 		                                                                   <img class="desktop" src="<?php echo $image; ?>" />
 		                                                               <?php } ?>
 		                                                           </div>
 		                                                       </a>
 		                                                       <div class="textContainer">
 		                                                           <span class="topicFilter">
 		                                                               <?php if (yoast_get_primary_term_id('topic')) {
 		                                                                   $primary_term_topic_id = yoast_get_primary_term_id('topic');
 		                                                                   $postTopic = get_term( $primary_term_topic_id );
 		                                                               } else {
 		                                                                   if(get_the_terms( $post->ID, 'topic' )){
 		                                                                       $terms = get_the_terms( $post->ID, 'topic' );
 		                                                                       foreach($terms as $term) {
 		                                                                           $postTopic = $term;
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
 		                                                               <?php if($postTopic){?>
 		                                                                   <a href="<?php echo get_term_link($postTopic); ?>" class="topicFilterText"><?php echo $postTopic->name; ?></a>
 		                                                               <?php } ?>
 		                                                               <a href="/filter-types/expert-presentations/" class="topicFilterText">Expert Presentations</a>

 		                                                           </span>
 		                                                           <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
 		                                                           <span class="dateReadTime"><span class="dateRead"><?php echo get_the_date('M j, Y'); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo get_field('read_time'); ?><?php } ?></span>
 		                                                           <span class="excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25, '...' );?></span>
 		                                                       </div>
 		                                                   </div>
		                                                <?php endwhile; ?>
		                                            <?php endif;?>
		                                            <?php wp_reset_postdata(); ?>
		                                            <?php wp_reset_query(); ?>
		                                    </div>
		                                </div>
		                            </section>
    							<?php endif; ?>
    						<?php elseif ( get_row_layout() == 'cta_block' ) : ?>
								<?php if ( $flexibleCounter == 1 ) { ?>
									<section class="resources-cta-block">
								    <div class="container">
								        <div class="cta-content">
								            <div class="column text-column one-half">
								        		<span class="cta-title"><?php echo get_sub_field( 'title' ); ?></span>
								        		<span class="text"><?php echo get_sub_field( 'text' ); ?></span>
								        		<?php if ( have_rows( 'button' ) ) : ?>
								                    <span class="button-container">
								            			<?php while ( have_rows( 'button' ) ) : the_row(); ?>
								                            <a class="std-button arrow-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'button_text' ); ?></a>
								            			<?php endwhile; ?>
								                    </span>
								        		<?php else : ?>
								        			<?php // no rows found ?>
								        		<?php endif; ?>
								            </div>
								            <div class="column image-column one-half">
								                <div class="bottom-image-container full-width-image">
								            		<?php $image = get_sub_field( 'image' ); ?>
								                    <div class="main-image-container">
								                		<?php if ( $image ) { ?>
								                			<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
								                		<?php } ?>
								                    </div>
								                    <span class="overlay-image-container">
								                        <?php $overlay_image = get_sub_field( 'overlay_image' ); ?>
								            			<?php if ( $overlay_image ) { ?>
								            				<img src="<?php echo $overlay_image['url']; ?>" alt="<?php echo $overlay_image['alt']; ?>" />
								            			<?php } ?>
								                    </span>
								                </div>
								            </div>
								        </div>
								    </div>
								</section>
								<?php } ?>
    						<?php endif; ?>
    					<?php endwhile; ?>
    				<?php else: ?>
    					<?php // no layouts found ?>
    				<?php endif; ?>
					<?php $flexibleCounter++; ?>
			    <?php } ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php // no rows found ?>
		<?php endif; ?>
	</main>
<?php } else { ?>
	<?php if ($membershipType == 'advantage') { ?>
		<main id="main" role="main" class="home noBanner advantageHome">
			<?php if ( have_rows( 'advantage_home_content_blocks' ) ): ?>
				<?php while ( have_rows( 'advantage_home_content_blocks' ) ) : the_row(); ?>
					<?php if ( get_row_layout() == 'featured_posts' ) : ?>
						<?php get_template_part( 'templates/post-components/_resources-featured-block' ); ?>
					<?php elseif ( get_row_layout() == 'slider_block' ) : ?>
						<?php get_template_part( 'templates/post-components/_keynote-slider' ); ?>
					<?php elseif ( get_row_layout() == 'post_slider' ) : ?>
						<?php get_template_part( 'templates/post-components/_post-slider' ); ?>
					<?php elseif ( get_row_layout() == 'upcoming_events' ) : ?>
						<?php get_template_part( 'templates/post-components/_events-slider' ); ?>
					<?php elseif ( get_row_layout() == 'two_column_accordion' ) : ?>
						<?php get_template_part( 'templates/post-components/_two-column-accordion' ); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php else: ?>
				<?php // no layouts found ?>
			<?php endif; ?>
		</main>
	<?php } else { ?>
		<main id="main" role="main" class="home noBanner advantageHome professionalHome">
			<?php if ( have_rows( 'it_pro_home_content_blocks' ) ): ?>
				<?php while ( have_rows( 'it_pro_home_content_blocks' ) ) : the_row(); ?>
					<?php if ( get_row_layout() == 'featured_posts' ) : ?>
						<?php get_template_part( 'templates/post-components/_resources-featured-block' ); ?>
					<?php elseif ( get_row_layout() == 'slider_block' ) : ?>
						<?php get_template_part( 'templates/post-components/_keynote-slider' ); ?>
					<?php elseif ( get_row_layout() == 'post_slider' ) : ?>
						<?php get_template_part( 'templates/post-components/_post-slider' ); ?>
					<?php elseif ( get_row_layout() == 'advisors_carousel' ) : ?>
						<?php get_template_part( 'templates/post-components/_advisors-carousel' ); ?>
					<?php elseif ( get_row_layout() == 'upcoming_events' ) : ?>
						<?php get_template_part( 'templates/post-components/_events-slider' ); ?>
					<?php elseif ( get_row_layout() == 'benchmarks_module' ) : ?>
						<?php get_template_part( 'templates/post-components/_benchmark-two-column' ); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php else: ?>
				<?php // no layouts found ?>
			<?php endif; ?>
		</main>
	<?php } ?>
<?php } ?>

<?php get_footer(); ?>
<script>
(function () {
    const wrapper = document.querySelector(".custom-gpt-wrapper");

    let manuallyClosed = false;
    let waitingForIframeClick = false;

    /*
     * Shared close routine so the wrapper click and the close
     * button behave identically.
     */
    function closeChat(reason) {
        manuallyClosed = true;
        waitingForIframeClick = true;

        document.body.classList.remove("cgpt-active");

        console.log("CustomGPT closed by " + reason);
    }

    /*
     * Inject the close button into the chat box header.
     * Safe to call repeatedly - it bails if one already exists.
     */
    function injectCloseButton() {
        const header = document.querySelector("#cgptcb-embed-chat-box-header");

        if (!header || header.querySelector(".cgpt-close-btn")) {
            return;
        }

        const btn = document.createElement("span");

        btn.className = "cgpt-close-btn";
        btn.setAttribute("role", "button");
        btn.setAttribute("tabindex", "0");
        btn.setAttribute("aria-label", "Close chat");

        header.appendChild(btn);

        console.log("CustomGPT close button injected");
    }

    injectCloseButton();

    /*
     * The widget renders its header asynchronously, so watch for it.
     */
    const observer = new MutationObserver(function () {
        injectCloseButton();
    });

    observer.observe(document.body, { childList: true, subtree: true });

    /*
     * Delegated - works no matter when the button lands in the DOM.
     */
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".cgpt-close-btn")) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        closeChat("close button");
    });

	document.addEventListener("keydown", function (event) {
        if (event.key !== "Enter" && event.key !== " ") {
            return;
        }

        if (!event.target.closest(".cgpt-close-btn")) {
            return;
        }

        event.preventDefault();
        closeChat("close button");
    });

    /*
     * Clicking directly on the wrapper removes the class.
     * Clicking its child elements does nothing.
     */
    if (wrapper) {
        wrapper.addEventListener("click", function (event) {
            if (event.target !== wrapper) {
                return;
            }

            closeChat("wrapper click");
        });
    }

    /*
     * Detect focus entering the iframe, but only after the user
     * has manually clicked the wrapper to remove the class.
     *
     * This prevents iframe loading/autofocus from activating it.
     */
    window.addEventListener("blur", function () {
        if (!waitingForIframeClick) {
            return;
        }

        setTimeout(function () {
            const iframe = document.querySelector(
                ".cgptcb-embed-chat-box-container iframe"
            );

            if (iframe && document.activeElement === iframe) {
                document.body.classList.add("cgpt-active");

                manuallyClosed = false;
                waitingForIframeClick = false;

                console.log("User clicked inside the CustomGPT iframe");
            }
        }, 100);
    });

    window.addEventListener("message", function (event) {
        if (event.origin !== "https://app.customgpt.ai") {
            return;
        }

        let data = event.data;

        if (typeof data === "string") {
            try {
                data = JSON.parse(data);
            } catch (error) {
                return;
            }
        }

        if (!data || typeof data !== "object") {
            return;
        }

        if (data.action !== "reset-button-visibility") {
            return;
        }

        if (data.showResetButton === true) {
            /*
             * Do not immediately reopen after a direct wrapper click.
             * The next genuine iframe click will reopen it instead.
             */
            if (!manuallyClosed) {
                document.body.classList.add("cgpt-active");
                console.log("CustomGPT conversation started");
            }
        }

        if (data.showResetButton === false) {
            document.body.classList.remove("cgpt-active");

            manuallyClosed = false;
            waitingForIframeClick = false;

            console.log("CustomGPT conversation reset");
        }
    });
})();
</script>