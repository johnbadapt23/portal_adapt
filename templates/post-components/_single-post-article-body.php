            <article class="articleWrapper bg-white <?= esc_attr( $advantagePlus ); ?>">
                <div class="container">                   
                    <div class="column first">
                        <div class="article">
                            <?php if ( get_field ( 'featured_image_or_video' ) == 'video' ) { ?>
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
                                    }
                                    if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                        $postType->name = 'Voice of Customer';
                                    }
                                    ?>
                                    <?php if($postTopic){?>
                                        <a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topicFilterText"><?php echo esc_html( $postTopic->name ); ?></a>
                                    <?php } ?>
                                    <?php if($postType){?>
                                        <a href="/filter-types/<?php echo esc_attr( $postType->slug ); ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
                                    <?php } ?>
                                </span>
                                <h1 class="title"><?php echo esc_html( get_the_title() ); ?></h1>
                                <?php if ($postType->slug == 'workshop-recordings' || $postType->slug == 'case-studies' || $postType->slug == 'best-practices' || $postType->slug == 'market-narratives'){ ?>
                                    <span class="dateReadTime"><?php if (get_field( 'read_time' )) { ?><?php echo esc_html( get_field('read_time') ); ?><?php } ?></span>

                                <?php } else { ?>
                                    <span class="dateReadTime"><span class="dateRead"><?php echo esc_html( get_the_date('M j, Y') ); ?>  </span><?php if (get_field( 'read_time' )) { ?>| <?php echo esc_html( get_field('read_time') ); ?><?php } ?></span>
                                <?php } ?>
                            <?php } ?>       
                            <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                            <?php } else { ?> 
                                <?php $previewContent = false; ?>
                                <?php if ( have_rows( 'members_only_preview_content' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_preview_content' ) ) : the_row(); ?>
                                        <?php if( get_sub_field( 'preview_text' )){ ?>
                                            <?php $previewContent = true; ?>
                                            <?php $previewText = get_sub_field( 'preview_text' ); ?>
                                        <?php } ?>                                                                             
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                            <?php if (get_field('article_content')){ ?>
                                <div class="article-content">
                                    <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes' || (function_exists('adapt_content_unlocked') && adapt_content_unlocked()) ) { ?>
                                    <?php echo wp_kses_post( get_field('article_content') ); ?>
                                    <?php } else { ?>
                                        <?php if ($previewContent == false){ ?>
                                    <div class="content-trimmed">
                                        <?php
                                        $text = get_the_excerpt();
                                        if($text){?>
                                            <p><?php echo esc_html( $text ); ?></p>
                                            <?php
                                        } else {
                                        } ?>
                                    </div>
                                    <?php if ( have_rows( 'members_only_overlay_cta', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_overlay_cta', 'options' ) ) : the_row(); 
                                    if( $addedBlur ){
                                        continue;
                                    }

                                    $addedBlur = true;
                                    ?>
                                        <div class="blurred-image-cta-container sixth-blur">
                                            <span class="blur-image-container">
                                                <span class="bg-container"> 
                                                    <p>                                                
                                                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.
                                                    </p>
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>
                                                    <ul>
                                                        <li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>
                                                        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</li>
                                                    </ul>  
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>                                             
                                                </span>
                                            </span>
                                            <?php $background_image_overlay = get_sub_field( 'background_image_overlay' ); ?>
                                            <div class="global-preview-cta-container background-black" style="background-image: url(<?php echo esc_url( $background_image_overlay['url'] ); ?>)">                                            
                                                <div class="preview-cta-inner">   
                                                    <div class="preview-cta-content">
                                                        <span class="title"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></span>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                            <?php $buttonCounter = 1; ?>
                                                            <span class="button-container">                                                                                                                   
                                                                <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                    <?php if( get_sub_field( 'button_type' ) == 'link'){ 
                                                                        $theLink = $hasTransformationSubs ? $transformationCTALink : get_sub_field( 'button_link' );
                                                                        ?> 
                                                                        <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo esc_url( $theLink ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                    <?php } else { ?> 
                                                                        <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                        <div style="display: none;">         
                                                                            <div class="preview-cta-form login-form-container" id="globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>">
                                                                                <span class="form-container-inner"><?php echo adapt_render_hubspot_embed( get_sub_field( 'hubspot_embed' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?></span>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                                                    <?php $buttonCounter++; ?>
                                                                <?php endwhile; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                    $postID = get_the_ID();
                                                    $postURL = get_permalink();
                                                ?>
                                                <span class="preview-cta-bottom-module"><?php echo esc_html( get_sub_field( 'login_text' ) ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo esc_attr( $postID );?>&redirect_to=<?php echo esc_url( $postURL );?>" target="_self">Login here</a></span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                                <?php } ?>
                                    <?php } ?>
                                    <!--  -->
                                </div>
                            <?php } else { ?>
                                <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes' || (function_exists('adapt_content_unlocked') && adapt_content_unlocked()) ) { ?>
                                <?php } else { ?>
                                    <?php if ($previewContent == false){ ?>
                                        <div class="content-trimmed">
                                            <?php
                                            $text = get_the_excerpt();
                                            if($text){?>
                                                <p><?php echo esc_html( $text ); ?></p>
                                                <?php
                                            } else {
                                            } ?>
                                        </div>
                                        <?php if ( have_rows( 'members_only_overlay_cta', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_overlay_cta', 'options' ) ) : the_row(); 
                                    if( $addedBlur ){
                                        continue;
                                    }

                                    $addedBlur = true;
                                    ?>
                                        <div class="blurred-image-cta-container seventh-blur">
                                            <span class="blur-image-container">
                                                <span class="bg-container"> 
                                                    <p>                                                
                                                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.
                                                    </p>
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>
                                                    <ul>
                                                        <li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>
                                                        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</li>
                                                    </ul>  
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>                                             
                                                </span>
                                            </span>
                                            <?php $background_image_overlay = get_sub_field( 'background_image_overlay' ); ?>
                                            <div class="global-preview-cta-container background-black" style="background-image: url(<?php echo esc_url( $background_image_overlay['url'] ); ?>)">                                            
                                                <div class="preview-cta-inner">   
                                                    <div class="preview-cta-content">
                                                        <span class="title"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></span>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                            <?php $buttonCounter = 1; ?>
                                                            <span class="button-container">                                                                                                                   
                                                                <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                    <?php if( get_sub_field( 'button_type' ) == 'link'){ 
                                                                        $theLink = $hasTransformationSubs ? $transformationCTALink : get_sub_field( 'button_link' );
                                                                        ?> 
                                                                        <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo esc_url( $theLink ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                    <?php } else { ?> 
                                                                        <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                        <div style="display: none;">         
                                                                            <div class="preview-cta-form login-form-container" id="globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>">
                                                                                <span class="form-container-inner"><?php echo adapt_render_hubspot_embed( get_sub_field( 'hubspot_embed' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?></span>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                                                    <?php $buttonCounter++; ?>
                                                                <?php endwhile; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                    $postID = get_the_ID();
                                                    $postURL = get_permalink();
                                                ?>
                                                <span class="preview-cta-bottom-module"><?php echo esc_html( get_sub_field( 'login_text' ) ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo esc_attr( $postID );?>&redirect_to=<?php echo esc_url( $postURL );?>" target="_self">Login here</a></span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                            <?php } else { ?> 
                                <?php if ( have_rows( 'members_only_preview_content' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_preview_content' ) ) : the_row(); ?>
                                        <div class="content-trimmed">
                                            <?php echo wp_kses_post( $previewText ); ?>
                                        </div>                                                                      
                                        <?php $image = get_sub_field( 'image' ); ?>                                
                                        <?php if ( $image ) { ?>
                                            <div class="preview-image-container">
                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
                                            </div>
                                        <?php } ?>
                                        <?php if ( have_rows( 'cta' ) ) : ?>
                                            <?php while ( have_rows( 'cta' ) ) : the_row(); ?>
                                                <div class="preview-cta-container background-pink">
                                                    <div class="preview-cta-inner">
                                                        <div class="preview-cta-image-column desktop">
                                                            <span class="image-container">
                                                                <span class="bg-container">
                                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                                    <?php if ( $image ) { ?>
                                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
                                                                    <?php } ?>
                                                                </span>
                                                            </span>                                                    
                                                        </div>
                                                        <div class="preview-cta-content">
                                                            <span class="title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
                                                            <span class="text"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                                            <div class="preview-cta-image mobile">
                                                                <span class="image-container">
                                                                    <span class="bg-container">
                                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                                        <?php if ( $image ) { ?>
                                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                </span>                                                    
                                                            </div>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                                <?php $buttonCounter = 1; ?>
                                                                <span class="button-container">                                                                                                                   
                                                                    <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                        <?php if( get_sub_field( 'button_type' ) == 'link'){ ?> 
                                                                            <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo esc_url( get_sub_field( 'button_link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                        <?php } else { ?> 
                                                                            <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#previewCTA<?php echo esc_attr( $buttonCounter ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                            <div style="display: none;">         
                                                                                <div class="preview-cta-form login-form-container" id="previewCTA<?php echo esc_attr( $buttonCounter ); ?>">                                                                            
                                                                                    <span class="form-container-inner"><?php echo adapt_render_hubspot_embed( get_sub_field( 'hubspot_embed' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?></span>
                                                                                </div>
                                                                            </div>
                                                                        <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                                                        <?php $buttonCounter++; ?>
                                                                    <?php endwhile; ?>
                                                                </span>
                                                            <?php else : ?>
                                                                <?php // no rows found ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        $postID = get_the_ID();
                                                        $postURL = get_permalink();
                                                    ?>
                                                    <span class="preview-cta-bottom-module"><?php echo esc_html( get_sub_field( 'login_text' ) ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo esc_attr( $postID );?>&redirect_to=<?php echo esc_url( $postURL );?>" target="_self">Login here</a></span>
                                                </div>                                        
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                            
                            <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes' || (function_exists('adapt_content_unlocked') && adapt_content_unlocked()) ) { ?>
                                <?php if ( have_rows( 'content_blocks' ) ): ?>
                                <?php while ( have_rows( 'content_blocks' ) ) : the_row(); ?>
                                    <?php if ( get_row_layout() == 'article_content' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="print-only scrollPos singlePost repeatableSingle">
                                        <div class="container">
                                            <div class="post-inner">
                                                <div class="fullWidth article-content">
                                                    <div class="articleWrapper">
                                                        <?php echo wp_kses_post( get_sub_field( 'article_content' ) ); ?>
                                                        <?php if( get_sub_field( 'infogram_image' )) { ?>
                                                            <?php
					$inline_img_128_src = get_sub_field( 'infogram_image' );
					$inline_img_128_attach_id = $inline_img_128_src ? attachment_url_to_postid( $inline_img_128_src ) : 0;
					if ( $inline_img_128_attach_id ) {
						echo wp_get_attachment_image( $inline_img_128_attach_id, 'full', false, [ 'alt' => '', 'class' => 'delete-no', 'style' => 'display: none;' ] );
					} elseif ( $inline_img_128_src ) {
						echo '<img class="delete-no" style="display: none;" src="' . esc_url( $inline_img_128_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( '' ) . '" />';
					}
				?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <?php elseif ( get_row_layout() == 'snapshot' ) : ?>
                                        <?php get_template_part( 'templates/post-components/_snapshot' ); ?>
                                    <?php elseif ( get_row_layout() == 'feature_image_or_infogram' ) : ?>
                                    <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only fullImageInfogram members-logged-in">
                                        <div class="container">
                                                <?php if ( get_sub_field ( 'feature_image_or_infogram' ) == 'image' ) { ?>
                                                    <div class="featureBlock">
                                                        <?php
					$inline_img_129_src = get_sub_field( 'image' );
					$inline_img_129_attach_id = $inline_img_129_src ? attachment_url_to_postid( $inline_img_129_src ) : 0;
					if ( $inline_img_129_attach_id ) {
						echo wp_get_attachment_image( $inline_img_129_attach_id, 'full', false, [ 'alt' => '', 'class' => 'featureImage' ] );
					} elseif ( $inline_img_129_src ) {
						echo '<img class="featureImage" src="' . esc_url( $inline_img_129_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( '' ) . '" />';
					}
				?>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="infogram-container">
                                                        <?php echo wp_kses_post( get_sub_field( 'infogram' ) ); ?>
                                                    </div>
                                                    <?php
					$inline_img_130_src = get_sub_field( 'infogram_image' );
					$inline_img_130_attach_id = $inline_img_130_src ? attachment_url_to_postid( $inline_img_130_src ) : 0;
					if ( $inline_img_130_attach_id ) {
						echo wp_get_attachment_image( $inline_img_130_attach_id, 'full', false, [ 'alt' => '', 'class' => 'delete-no', 'style' => 'display: none;' ] );
					} elseif ( $inline_img_130_src ) {
						echo '<img class="delete-no" style="display: none;" src="' . esc_url( $inline_img_130_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( '' ) . '" />';
					}
				?>
                                                <?php } ?>
                                        </div>
                                    </section>
                                    <?php elseif ( get_row_layout() == 'image_grid_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="print-only imageGridBlock standard <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>

                                                    <?php if ( have_rows( 'item' ) ) : ?>
                                                        <div class="gridWrapper">
                                                            <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <?php if ( get_sub_field( 'image') ) { ?>
                                                                        <div class="imageContainer">
                                                                            <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'image' ) ); ?>);">
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <hr>
                                                                    <span class="title">
                                                                        <?php echo esc_html( get_sub_field( 'title' ) ); ?>
                                                                    </span>
                                                                    <span class="description">
                                                                        <?php echo esc_html( get_sub_field( 'description' ) ); ?>
                                                                    </span>
                                                                    <?php if ( get_sub_field( 'logo') ) { ?>
                                                                        <div class="logoContainer">
                                                                            <?php
					$inline_img_131_src = get_sub_field( 'logo' );
					$inline_img_131_attach_id = $inline_img_131_src ? attachment_url_to_postid( $inline_img_131_src ) : 0;
					if ( $inline_img_131_attach_id ) {
						echo wp_get_attachment_image( $inline_img_131_attach_id, 'full', false, [ 'alt' => 'Adapt' ] );
					} elseif ( $inline_img_131_src ) {
						echo '<img src="' . esc_url( $inline_img_131_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( 'Adapt' ) . '" />';
					}
				?>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'logo_grid' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="print-only scrollPos logoGrid <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                            <div class="container">
                                                <div class="titleBlock">
                                                    <span class="title">
                                                        <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
                                                    </span>

                                                    <span class="description <?php echo esc_attr( get_sub_field( 'top_right_text_position' ) ); ?>">
                                                        <h3><?php echo esc_html( get_sub_field( 'top_right_text' ) ); ?></h3>
                                                    </span>
                                                </div>

                                                <?php if ( have_rows( 'logos' ) ) : ?>
                                                    <div class="logoBlock">
                                                        <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                            <div class="logo">
                                                                <span class="logoContainer">
                                                                    <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'logo' ) ); ?>);">
                                                                    </div>
                                                                </span>
                                                                <span class="logoTitle">
                                                                    <?php echo esc_html( get_sub_field( 'title' ) ); ?>
                                                                </span>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                    <a class="logoBlockLink <?php echo esc_attr( get_sub_field( 'link_style' ) ); ?>" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                <?php } ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'download_block_single' ) : ?>
                                        <?php get_template_part( 'templates/components/_download-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'download_block_double' ) : ?>
                                        <?php get_template_part( 'templates/components/_download-block-two-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'download_block_triple' ) : ?>
                                        <?php get_template_part( 'templates/components/_download-block-three-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'video_grid_block_two_column' ) : ?>
                                        <?php get_template_part( 'templates/components/_video-block-two-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'video_grid_block_three_column' ) : ?>
                                        <?php get_template_part( 'templates/components/_video-block-three-columns' ); ?>
                                    <?php elseif ( get_row_layout() == 'two_column_card_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_two-column-card' ); ?>
                                    <?php elseif ( get_row_layout() == 'speaker_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only imageGridBlock speakerBlock <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>

                                                    <?php if ( have_rows( 'speakers' ) ) : ?>
                                                        <div class="gridWrapper">
                                                            <?php while ( have_rows( 'speakers' ) ) : the_row(); ?>

                                                                <?php $post_object = get_sub_field( 'speaker' ); ?>
                                                                <?php if ( $post_object ): ?>
                                                                    <a href="<?php the_permalink(); ?>" class="item">
                                                                        <?php $post = $post_object; ?>
                                                                        <?php setup_postdata( $post ); ?>
                                                                            <?php if ( get_field( 'speaker_image') ) { ?>
                                                                                <div class="imageContainer">
                                                                                    <div class="image" style="background-image: url(<?php echo esc_url( get_field( 'speaker_image' ) ); ?>);">
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <hr>
                                                                            <span class="title"><?php echo esc_html( get_the_title() ); ?></span>
                                                                            <span class="description">
                                                                                <?php echo esc_html( get_field( 'speaker_description' ) ); ?>
                                                                            </span>
                                                                            <?php if ( get_field( 'logo') ) { ?>
                                                                                <div class="logoContainer">
                                                                                    <?php
					$inline_img_132_src = get_field( 'logo' );
					$inline_img_132_attach_id = $inline_img_132_src ? attachment_url_to_postid( $inline_img_132_src ) : 0;
					if ( $inline_img_132_attach_id ) {
						echo wp_get_attachment_image( $inline_img_132_attach_id, 'full', false, [ 'alt' => 'Adapt' ] );
					} elseif ( $inline_img_132_src ) {
						echo '<img src="' . esc_url( $inline_img_132_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( 'Adapt' ) . '" />';
					}
				?>
                                                                                </div>
                                                                            <?php } ?>
                                                                        <?php wp_reset_postdata(); ?>
                                                                    </a>
                                                                <?php endif; ?>

                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                        <div class="buttonBlock <?php echo esc_attr( get_sub_field('link_orientation') ); ?>">
                                                            <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                        </div>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'speaker_block_button' ) : ?>
                                            <?php get_template_part( 'templates/components/_speaker-cta-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'two_column_text_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="print-only scrollPos twoColumnTextBlock <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="titleBlock">
                                                        <h2><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                                                        <hr>

                                                    </div>
                                                    <div class="textBlock">
                                                        <?php echo wp_kses_post( get_sub_field( 'text_block' ) ); ?>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'text_and_image_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only textImageBlock <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="title">
                                                        <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
                                                        <hr>
                                                    </div>
                                                    <?php if ( have_rows( 'item' ) ) : ?>
                                                        <div class="itemsWrapper">
                                                            <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <?php if ( get_sub_field( 'image') ) { ?>
                                                                        <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>" class="imageContainer">
                                                                            <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'image' ) ); ?>);">
                                                                            </div>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <span class="title"><?php echo esc_html( get_sub_field( 'title' ) ); ?></span>
                                                                    <span class="description">
                                                                        <?php echo esc_html( get_sub_field( 'text' ) ); ?>
                                                                    </span>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'half_text_half_image' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only halfHalfBlock <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
                                            <div class="textBlock <?php echo esc_attr( get_sub_field( 'image_position' ) ); ?>">
                                                <div class="v-wrap">
                                                    <div class="v-box">
                                                        <h2><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                                                        <hr>
                                                        <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                            <span class="desktopText"><?php echo esc_html( get_sub_field( 'text_block' ) ); ?></span>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink desktop <?php echo esc_attr( get_sub_field( 'link_style' ) ); ?>" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="imageBlock <?php echo esc_attr( get_sub_field( 'image_position' ) ); ?>">
                                                <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'image' ) ); ?>);">
                                                </div>
                                            </div>
                                            <div class="textBlock mobile">
                                                <div class="container">
                                                    <div class="inner">
                                                        <?php if ( get_sub_field ( 'text_block' ) ) { ?>
                                                            <span class="mobileText"><?php echo esc_html( get_sub_field( 'text_block' ) ); ?></span>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink <?php echo esc_attr( get_sub_field( 'link_style' ) ); ?>" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'full_width_text_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_full-width-text-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'speaker_quote_carousel' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no speakerQuoteCarousel">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="titleBlock">
                                                        <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                        <?php } ?>
                                                    </div>

                                                    <?php if ( have_rows( 'item' ) ) : ?>
                                                        <div class="owl-carousel speaker-gallery">
                                                            <?php while ( have_rows( 'item' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <div class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'image' ) ); ?>);">
                                                                        </div>
                                                                    </div>
                                                                    <div class="textBlock">
                                                                        <div class="v-wrap">
                                                                            <div class="v-box">
                                                                                <span class="quoteBlock">
                                                                                    <?php echo esc_html( get_sub_field( 'quote' ) ); ?>
                                                                                </span>
                                                                                <span class="quoteAuthor">
                                                                                    <?php echo esc_html( get_sub_field( 'quote_author' ) ); ?>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'quote_block_with_no_image' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no quoteBlockNoImage">
                                            <div class="container">
                                                <div class="inner">
                                                    <?php if ( have_rows( 'quotes' ) ) : ?>
                                                        <div class="owl-carousel quote">
                                                            <?php while ( have_rows( 'quotes' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <div class="v-wrap">
                                                                        <div class="v-box">
                                                                            <span class="quoteBlock">
                                                                                <?php echo esc_html( get_sub_field( 'quote' ) ); ?>
                                                                            </span>
                                                                            <span class="quoteAuthor">
                                                                                <?php echo esc_html( get_sub_field( 'quote_author' ) ); ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'single_level_logo_block' ) : ?>
                                        <section id="<?php echo esc_attr( get_sub_field( 'id' ) ); ?>" class="scrollPos imageGridBlock standard logos">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="titleBlock">
                                                        <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                            <h2><?php echo esc_html( get_sub_field( 'block_title' ) ); ?></h2>
                                                            <span class="hrWrapper">
                                                                <hr>
                                                            </span>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'description' ) ) { ?>
                                                            <h3><?php echo esc_html( get_sub_field( 'description' ) ); ?></h3>
                                                        <?php } ?>
                                                    </div>

                                                    <?php if ( have_rows( 'logos' ) ) : ?>
                                                        <div class="gridWrapper">
                                                            <?php while ( have_rows( 'logos' ) ) : the_row(); ?>
                                                                <div class="item">
                                                                    <div class="imageContainer">
                                                                        <div class="image" style="background-image: url(<?php echo esc_url( get_sub_field( 'logo' ) ); ?>);">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile; ?>
                                                            <div class="item">
                                                                <div class="v-wrap">
                                                                    <div class="v-box">
                                                                        <span class="yourLogoHere">Your Company Here</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'counter_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_counter-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'repeatable_counter_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_repeatable-counter-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'membership_block' ) : ?>
                                        <?php if ( get_sub_field ( 'display_membership_block' ) == 'yes' ) { ?>
                                            <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no pricingBlock">
                                                <div class="container">
                                                    <h2>Membership</h2>
                                                    <?php if ( have_rows( 'first_pricing_block', 'option' ) ) : ?>
                                                        <div class="pricingBlockItem first">
                                                            <div class="innerWrapper">
                                                                <?php while ( have_rows( 'first_pricing_block', 'option' ) ) : the_row(); ?>
                                                                    <span class="title">
                                                                        <?php echo esc_html( get_sub_field( 'title', 'option' ) ); ?>
                                                                        <span class="hrWrapper">
                                                                            <hr>
                                                                        </span>
                                                                    </span>
                                                                    <span class="priceBlockWrapper">
                                                                        <span class="priceBlock">
                                                                            <span class="dollar">$</span><?php echo esc_html( get_sub_field( 'price_block', 'option' ) ); ?><span class="month">/month</span>
                                                                        </span>
                                                                    </span>
                                                                    <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                        <div class="features">
                                                                            <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                <span class="feature"><?php echo esc_html( get_sub_field( 'feature', 'option' ) ); ?></span>
                                                                            <?php endwhile; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <span class="pricingButtonWrapper">
                                                                    <a class="small" href="<?php echo esc_url( get_sub_field( 'button_link', 'option' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'button_target', 'option' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text', 'option' ) ); ?></a>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ( have_rows( 'featured_pricing_block', 'option' ) ) : ?>
                                                        <div class="pricingBlockItem featured">

                                                            <?php while ( have_rows( 'featured_pricing_block', 'option' ) ) : the_row(); ?>
                                                                <div class="innerWrapper">
                                                                    <div class="featuredWrapper">
                                                                        <span class="title">
                                                                            <?php echo esc_html( get_sub_field( 'title', 'option' ) ); ?>
                                                                            <span class="hrWrapper">
                                                                                <hr>
                                                                            </span>
                                                                        </span>
                                                                        <span class="priceBlockWrapper">
                                                                            <span class="priceBlock">
                                                                                <span class="dollar">$</span><?php echo esc_html( get_sub_field( 'price_block', 'option' ) ); ?><span class="month">/month</span>
                                                                            </span>
                                                                        </span>
                                                                        <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                            <div class="features">
                                                                                <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                    <span class="feature"><?php echo esc_html( get_sub_field( 'feature', 'option' ) ); ?></span>
                                                                                <?php endwhile; ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <span class="pricingButtonWrapper">
                                                                    <a class="small" href="<?php echo esc_url( get_sub_field( 'button_link', 'option' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'button_target', 'option' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text', 'option' ) ); ?></a>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php if ( have_rows( 'last_pricing_block', 'option' ) ) : ?>
                                                        <div class="pricingBlockItem last">
                                                            <?php while ( have_rows( 'last_pricing_block', 'option' ) ) : the_row(); ?>
                                                                <div class="innerWrapper">
                                                                    <span class="title">
                                                                        <?php echo esc_html( get_sub_field( 'title', 'option' ) ); ?>
                                                                        <span class="hrWrapper">
                                                                            <hr>
                                                                        </span>
                                                                    </span>
                                                                    <span class="priceBlockWrapper">
                                                                        <span class="priceBlock">
                                                                            <span class="dollar">$</span><?php echo esc_html( get_sub_field( 'price_block', 'option' ) ); ?><span class="month">/month</span>
                                                                        </span>
                                                                    </span>
                                                                    <?php if ( have_rows( 'features', 'option' ) ) : ?>
                                                                        <div class="features">
                                                                            <?php while ( have_rows( 'features', 'option' ) ) : the_row(); ?>
                                                                                <span class="feature"><?php echo esc_html( get_sub_field( 'feature', 'option' ) ); ?></span>
                                                                            <?php endwhile; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <span class="pricingButtonWrapper">
                                                                    <a class="small" href="<?php echo esc_url( get_sub_field( 'button_link', 'option' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'button_target', 'option' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text', 'option' ) ); ?></a>
                                                                </span>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </section>
                                        <?php } ?>
                                    <?php elseif ( get_row_layout() == 'two_column_block_with_text_and_featured_quote' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only twoColumnWithTextAndFeaturedQuote">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="column first">
                                                        <h2>
                                                            <?php echo esc_html( get_sub_field( 'title' ) ); ?>
                                                        </h2>
                                                        <div class="textBlock">
                                                            <?php echo esc_html( get_sub_field( 'text_block' ) ); ?>
                                                        </div>
                                                        <?php if ( get_sub_field ( 'link_url' ) ) { ?>
                                                            <a class="logoBlockLink text" href="<?php echo esc_url( get_sub_field( 'link_url' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="column last">
                                                        <div class="item">
                                                            <div class="v-wrap">
                                                                <div class="v-box">
                                                                    <span class="quoteBlock">
                                                                        <?php echo esc_html( get_sub_field( 'quote' ) ); ?>
                                                                    </span>
                                                                    <span class="quoteAuthor">
                                                                        <?php echo esc_html( get_sub_field( 'quote_author' ) ); ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'video_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no videoBlock postVideoBlock">
                                            <div class="container">
                                                <?php if( get_sub_field('vimeo_code_popup')){ ?>
                                                    <a href="https://vimeo.com/<?php echo esc_attr( get_sub_field('vimeo_code_popup') ); ?>" class="image popup-vimeo">
                                                <?php } else { ?>
                                                    <a href="" class="image postPlayBtn">
                                                <?php } ?>
                                                    <div class="imageSizeContainer">
                                                        <span class="overlayGradient"></span>
                                                        <div class="bgContainer">
                                                            <?php
					$inline_img_133_src = get_sub_field( 'video_poster_image' );
					$inline_img_133_attach_id = $inline_img_133_src ? attachment_url_to_postid( $inline_img_133_src ) : 0;
					if ( $inline_img_133_attach_id ) {
						echo wp_get_attachment_image( $inline_img_133_attach_id, 'full', false, [ 'alt' => '', 'class' => 'desktop' ] );
					} elseif ( $inline_img_133_src ) {
						echo '<img class="desktop" src="' . esc_url( $inline_img_133_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( '' ) . '" />';
					}
				?>
                                                        </div>
                                                        <span class="watchIcon"></span>
                                                        <span class="textContainer">
                                                            <span class="title"><?php echo esc_html( get_the_title() ); ?></span>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="videoPlayerContainer videoBlock">
                                                <span class="closeVideo"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/close-grey.svg" width="25" height="25" loading="lazy" decoding="async" alt="Close" /></span>
                                                <div class="videoWrapper">
                                                    <video width="100%" id="popupVideo" controls controlsList="nodownload">
                                                        <source type="video/mp4" src="<?php echo esc_url( get_sub_field('vimeo_code') ); ?>" />
                                                    </video>
                                                </div>
                                            </div>

                                        </section>
                                    <?php elseif ( get_row_layout() == 'full_width_image_block' ) : ?>
                                        <?php get_template_part( 'templates/components/_full-image-button-block' ); ?>
                                    <?php elseif ( get_row_layout() == 'full_width_text_editor' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-only fullWidthTextEditor<?php if ( get_sub_field( 'font') ) { ?> <?php echo esc_attr( get_sub_field( 'font' ) );?><?php } ?><?php if ( get_sub_field( 'font_colour') ) { ?> <?php echo esc_attr( get_sub_field( 'font_colour' ) ); ?><?php } ?>">
                                            <div class="container">
                                                <?php echo wp_kses_post( get_sub_field( 'text_editor' ) ); ?>
                                                <?php if ( have_rows( 'button_block' ) ) : ?>
                                                    <div class="buttonBlock">
                                                        <?php while ( have_rows( 'button_block' ) ) : the_row(); ?>
                                                            <a href="<?php echo esc_url( get_sub_field('link_url') ); ?>" class="button" target="<?php echo esc_attr( get_sub_field('link_target') ); ?>"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </section>
                                    <?php elseif ( get_row_layout() == 'form_block' ) : ?>
                                        <section <?php if( get_sub_field('id')){?>id="<?php echo esc_attr( get_sub_field('id') ); ?>"<?php } ?> class="scrollPos print-no formBlock<?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?> centered<?php } ?>">
                                            <div class="container">
                                                <div class="inner">
                                                    <div class="formWrapper register">
                                                        <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                            <h2><?php echo esc_html( get_sub_field('block_title') ); ?></h2>
                                                            <?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                                                <hr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                                            <h3><?php echo esc_html( get_sub_field('block_description') ); ?></h3>
                                                        <?php } ?>
                                                        <?php if ( get_sub_field ( 'embed' ) == 'embed' ) { ?>
                                                            <?php echo wp_kses_post( get_sub_field('form_shortcode') ); ?>
                                                        <?php }?>
                                                        <?php if ( get_sub_field ( 'embed' ) == 'popup' ) { ?>
                                                            <a class="button popup-modal" href="#<?php echo esc_attr( get_sub_field('form_id') ); ?>"><?php echo esc_html( get_sub_field('button_text') ); ?></a>
                                                            <div class="formPopup mfp-hide" id="<?php echo esc_attr( get_sub_field('form_id') ); ?>">
                                                                <a class="popup-modal-dismiss"></a>
                                                                <?php if ( get_sub_field ( 'block_title' ) ) { ?>
                                                                    <h2><h2><?php echo esc_html( get_sub_field('block_title') ); ?></h2></h2>
                                                                <?php } ?>
                                                                <?php if ( get_sub_field ( 'block_description' ) ) { ?>
                                                                    <h3><?php echo esc_html( get_sub_field('block_description') ); ?></h3>
                                                                <?php } ?>
                                                                    <div class="formWrapper register"><?php echo wp_kses_post( get_sub_field('form_shortcode') ); ?></div>
                                                            </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php endif; ?>
                            <?php } else { ?>
                                <?php if ( have_rows( 'members_only_overlay_cta', 'options' ) ) : ?>
                                    <?php while ( have_rows( 'members_only_overlay_cta', 'options' ) ) : the_row(); 
                                    if( $addedBlur ){
                                        continue;
                                    }

                                    $addedBlur = true;
                                    ?>
                                        <div class="blurred-image-cta-container eight-blur">
                                            <span class="blur-image-container">
                                                <span class="bg-container"> 
                                                    <p>                                                
                                                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.
                                                    </p>
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>
                                                    <ul>
                                                        <li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>
                                                        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</li>
                                                    </ul>  
                                                    <p>
                                                        Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
                                                    </p>                                             
                                                </span>
                                            </span>
                                            <?php $background_image_overlay = get_sub_field( 'background_image_overlay' ); ?>
                                            <div class="global-preview-cta-container background-black" style="background-image: url(<?php echo esc_url( $background_image_overlay['url'] ); ?>)">                                            
                                                <div class="preview-cta-inner">   
                                                    <div class="preview-cta-content">
                                                        <span class="title"><?php echo wp_kses_post( get_sub_field( 'text' ) ); ?></span>
                                                            <?php if ( have_rows( 'buttons' ) ) : ?>
                                                            <?php $buttonCounter = 1; ?>
                                                            <span class="button-container">                                                                                                                   
                                                                <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
                                                                    <?php if( get_sub_field( 'button_type' ) == 'link'){ 
                                                                        $theLink = $hasTransformationSubs ? $transformationCTALink : get_sub_field( 'button_link' );
                                                                        ?> 
                                                                        <a class="stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="<?php echo esc_url( $theLink ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                    <?php } else { ?> 
                                                                        <a class="formPopupHubspot stdBtn <?php if($buttonCounter == 1){ ?>red<?php } else { ?>red-outline-button<?php } ?>" href="#globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                                        <div style="display: none;">         
                                                                            <div class="preview-cta-form login-form-container" id="globalpreviewCTA<?php echo esc_attr( $buttonCounter ); ?>">
                                                                                <span class="form-container-inner"><?php echo adapt_render_hubspot_embed( get_sub_field( 'hubspot_embed' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?></span>
                                                                            </div>                                                                        
                                                                        </div>
                                                                    <?php } ?>                                                                                                                                                                                                                                                                                                                                
                                                                    <?php $buttonCounter++; ?>
                                                                <?php endwhile; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <?php // no rows found ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php
                                                    $postID = get_the_ID();
                                                    $postURL = get_permalink();
                                                ?>
                                                <span class="preview-cta-bottom-module"><?php echo esc_html( get_sub_field( 'login_text' ) ); ?><a class="login-link"  href="/login/?mepr-unauth-page=<?php echo esc_attr( $postID );?>&redirect_to=<?php echo esc_url( $postURL );?>" target="_self">Login here</a></span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php } ?>
                        </div>
                        <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                            <?php if ( have_rows( 'contributors' ) ) : ?>
                                <div class="authors">
                                    <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
                                        <?php $post_object = get_sub_field( 'contributor_name' ); ?>
                                        <?php if ( $post_object ): ?>
                                            <?php $post = $post_object; ?>
                                            <?php setup_postdata( $post ); ?>
                                                <div class="speaker-container-inner">
        										<span class="speaker-image">
                                                    <?php if(get_field('speaker_image')){ ?>
                                                        <?php
					$inline_img_134_src = get_field( 'speaker_image' );
					$inline_img_134_attach_id = $inline_img_134_src ? attachment_url_to_postid( $inline_img_134_src ) : 0;
					if ( $inline_img_134_attach_id ) {
						echo wp_get_attachment_image( $inline_img_134_attach_id, 'full', false, [ 'alt' => get_the_title() ] );
					} elseif ( $inline_img_134_src ) {
						echo '<img src="' . esc_url( $inline_img_134_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
                                                    <?php } else if(get_field('listing_avatar')){ ?>
                                                        <?php $img = get_field('listing_avatar');
                                                        $url = is_array($img) ? $img['url'] : (is_int($img) ? wp_get_attachment_image_url($img, 'full') : $img);
                                                        if ($url): ?>
                                                            <?php
					$inline_img_135_src = $url;
					$inline_img_135_attach_id = $inline_img_135_src ? attachment_url_to_postid( $inline_img_135_src ) : 0;
					if ( $inline_img_135_attach_id ) {
						echo wp_get_attachment_image( $inline_img_135_attach_id, 'full', false, [ 'alt' => get_the_title() ] );
					} elseif ( $inline_img_135_src ) {
						echo '<img src="' . esc_url( $inline_img_135_src ) . '" loading="lazy" decoding="async" alt="' . esc_attr( get_the_title() ) . '" />';
					}
				?>
                                                        <?php endif; ?>
                                                   <?php } ?>
        										</span>
        										<span class="description">
                                                    <span class="title"><?php if(get_sub_field('contributors_pre_heading')){ ?><?php echo esc_html( get_sub_field('contributors_pre_heading') ); ?><?php } else { ?>Contributor<?php } ?></span>
        											<a class="author-link text-black" href="<?php the_permalink(); ?>" target="_self"><span class="speaker-name"><?php echo esc_html( get_the_title() ); ?></span></a>
        											<span class="speaker-role">
                                                        <?php if(get_field('speaker_description')){ ?>
                                                            <?php echo esc_html( get_field('speaker_description') ); ?>
                                                        <?php } else if(get_field('role')){ ?>
                                                            <?php echo esc_html( get_field('role') ); ?>
                                                        <?php } ?>
                                                        
                                                    </span>
        										</span>
                                                <div class="textBlock">
                                                    <?php if(get_field('speaker_details')){ ?>
                                                        <?php
                                                            $text = get_field('speaker_details');
                                                            $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                        ?>
                                                        <span class="speaker-details-excerpt"><?php echo esc_html( $trimmed_content ); ?></span>
                                                        <span class="speaker-details">
                                                            <?php echo wp_kses_post( get_field('speaker_details') ); ?>
                                                            <span class="speaker-details-less">Less</span>
                                                        </span>
                                                    <?php } else { ?> 
                                                        <?php
                                                            $text = get_field('listing_excerpt');
                                                            $trimmed_content = wp_trim_words( $text, $num_words = 22, $more = '... More' );
                                                        ?>
                                                        <span class="speaker-details-excerpt"><?php echo esc_html( $trimmed_content ); ?></span>
                                                        <span class="speaker-details">
                                                            <?php echo esc_html( get_field('listing_excerpt') ); ?>
                                                            <span class="speaker-details-less">Less</span>
                                                        </span>
                                                    <?php } ?>
                                                </div>
        									</div>
                                            <?php wp_reset_postdata(); ?>
                                        <?php endif; ?>
                                <?php endwhile; ?>
                                </div>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        <?php } ?>
                    </div>
                    <div class="column second">
                        <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                           <span class="share-save-container desktop">
                                <span class="saveInsight">
                                    <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                                        <?php echo do_shortcode('[favorite_button]'); ?>
                                    <?php } ?>
                                </span>
                                 <?php if($advantagePlus == "no"){ ?>
                            <span class="shareArticle">
                                <a class="emailShare" href="mailto:?&subject=<?php echo esc_html( get_the_title() ); ?>&body=<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php if($advantageType == 'yes'){ ?>SHARE WITH A COLLEAGUE<?php } else { ?>SHARE THIS ARTICLE<?php } ?>	
                                </a>
                            </span>  
                        <?php } ?>                  
                            </span>
                        <?php } ?>
                        <?php if ( have_rows( 'preview_module' ) ) : ?>
                            <?php while ( have_rows( 'preview_module' ) ) : the_row(); ?>
                                <?php if ( have_rows( 'slider_images' ) ) : ?>
                                    <?php $imageCounter = 1; ?>
                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                        <?php if($imageCounter == 1){
                                            $image = get_sub_field( 'image' );
                                        } else if ($imageCounter == 2){
                                            $offsetimage = get_sub_field( 'image' );
                                        }
                                        $imageCounter++; ?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                        <?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?>
                           <?php if ( has_term( ['sector-outlooks', 'persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                                <?php if( $advantagePlus == 'yes') { ?> 
                                    <?php if (get_field( 'download' ) == 'yes'){ ?>
                                        <?php if ( have_rows( 'membership_ids_for_download', 'options' ) ) : ?>
                                            <?php $counter = 0; ?>
                                            <?php $members = ''; ?>
                                                <?php while ( have_rows( 'membership_ids_for_download', 'options' ) ) : the_row(); ?>
                                                    <?php if ( $counter == 0 ) {
                                                    $members = $members . get_sub_field( 'membership_id' );
                                                    } else {
                                                    $members = $members . ',' . get_sub_field( 'membership_id' );
                                                    } ?>
                                                    <?php $counter++; ?>
                                                <?php endwhile; ?>
                                                <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                                    <?php if ( have_rows( 'download_link' ) ) : ?>
                                                        <div class="articleShare downloadShareContainer">
                                                            <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                                                <?php if (get_sub_field( 'text' )) { ?>
                                                                    <span class="shareText download desktop"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                                                <?php } ?>
                                                                <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                                <?php if ( $preview_image ) { ?>
                                                                    <span class="download-image-container <?php echo esc_attr( get_sub_field( 'image_orientation' ) ); ?>">
                                                                        <span class="bg-container">
                                                                            <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, [ 'alt' => $preview_image['alt'] ] ); ?>
                                                                        </span>
                                                                    </span>
                                                                <?php } ?>
                                                                <?php if (get_sub_field( 'text' )) { ?>
                                                                    <span class="shareText download mobile"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                                                <?php } ?>
                                                                <a id="downloadButton" href="<?php echo esc_url( get_sub_field( 'download_url' ) ); ?>" target="_blank" rel="noopener noreferrer" class="button redOutline"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                            <?php endwhile; ?>
                                                        </div>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>
                                                <?php } ?>

                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if (get_field( 'download' ) == 'yes'){ ?>
                                    <?php if ( have_rows( 'membership_ids_for_download', 'options' ) ) : ?>
                                        <?php $counter = 0; ?>
                                        <?php $members = ''; ?>
                                            <?php while ( have_rows( 'membership_ids_for_download', 'options' ) ) : the_row(); ?>
                                                <?php if ( $counter == 0 ) {
                                                $members = $members . get_sub_field( 'membership_id' );
                                                } else {
                                                $members = $members . ',' . get_sub_field( 'membership_id' );
                                                } ?>
                                                <?php $counter++; ?>
                                            <?php endwhile; ?>
                                            <?php if(current_user_can('mepr-active','memberships:' . $members)){ ?>
                                                <?php if ( have_rows( 'download_link' ) ) : ?>
                                                    <div class="articleShare downloadShareContainer">
                                                        <?php while ( have_rows( 'download_link' ) ) : the_row(); ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText download desktop"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                                            <?php } ?>
                                                            <?php $preview_image = get_sub_field( 'preview_image' ); ?>
                                                            <?php if ( $preview_image ) { ?>
                                                                <span class="download-image-container <?php echo esc_attr( get_sub_field( 'image_orientation' ) ); ?>">
                                                                    <span class="bg-container">
                                                                        <?php echo wp_get_attachment_image( $preview_image['ID'], 'full', false, [ 'alt' => $preview_image['alt'] ] ); ?>
                                                                    </span>
                                                                </span>
                                                            <?php } ?>
                                                            <?php if (get_sub_field( 'text' )) { ?>
                                                                <span class="shareText download mobile"><?php echo esc_html( get_sub_field( 'text' ) ); ?></span>
                                                            <?php } ?>
                                                            <a id="downloadButton" href="<?php echo esc_url( get_sub_field( 'download_url' ) ); ?>" target="_blank" rel="noopener noreferrer" class="button redOutline"><?php echo esc_html( get_sub_field( 'button_text' ) ); ?></a>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php else : ?>
                                                    <?php // no rows found ?>
                                                <?php endif; ?>
                                            <?php } ?>

                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                <?php } ?>
                            <?php } ?>                            
                        <?php }?>
                        <div class="relatedArticles<?php if(current_user_can('memberpress_authorized') || $advantagePlus == 'yes') { ?><?php } else { ?> mobile-hide<?php } ?>">
                            <h2 class="related">You may also like</h2>
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
                            <?php $type_terms = get_field( 'you_may_also_like' ); ?>
                            <?php $types = []; ?>
                            <?php if ( $type_terms ){ ?>
                                <?php foreach ( $type_terms as $type_term ): ?>
                                    <?php $types[] =  $type_term->slug; ?>
                                <?php endforeach; ?>
                            <?php
                                $args = [
                                    'post_type'      => 'post',
                                    'posts_per_page' => 3,
                                    'post__not_in' => [ $post->ID ],
                                    'tax_query'      => [
                                        'relation' => 'AND',
                                         [
                                            'taxonomy' => 'topic',
                                            'field' => 'slug',
                                            'terms'    => $postTopic->slug
                                        ],
                                        [
                                            'taxonomy' => 'filter-types',
                                            'field'    => 'slug',
                                            'terms' => $types,
                                            'operator' => 'IN',
                                        ]
                                    ]
                                ];?>
                            <?php } else {
                                $args = [
                                    'post_type'      => 'post',
                                    'posts_per_page' => 3,
                                    'post__not_in' => [ $post->ID ],
                                    'tax_query'      => [
                                        'relation' => 'AND',
                                         [
                                            'taxonomy' => 'topic',
                                            'field' => 'slug',
                                            'terms'    => $postTopic->slug
                                        ]
                                    ]
                                ];?>
                            <?php }

                                $posts = new WP_Query( $args );
                                if( $posts->have_posts() ): ?>
                                    <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                        <div class="item">
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
                                                    }
                                                    if (isset($postType->slug) && $postType->slug === 'community-interviews' && $advantageType === 'yes') {
                                                        $postType->name = 'Voice of Customer';
                                                    }
                                                    ?>
                                                    <?php if($postTopic){?>
                                                        <a href="<?php echo esc_url( get_term_link($postTopic) ); ?>" class="topicFilterText"><?php echo esc_html( $postTopic->name ); ?></a>
                                                    <?php } ?>
                                                    <?php if($postType){?>
                                                        <a href="/filter-types/<?php echo esc_attr( $postType->slug ); ?>" class="topicFilterText"><?php echo esc_html( $postType->name ); ?></a>
                                                    <?php } ?>
                                                </span>
                                                <a href="<?php the_permalink(); ?>" class="title"><?php echo esc_html( get_the_title() ); ?></a>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif;?>
                                <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </article>
