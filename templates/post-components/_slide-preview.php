<?php global $current_user, $first_name, $last_name, $user_email, $membershipType, $advantageType, $member;
    $advantagePlus = "no";
    $current_user = wp_get_current_user();
    $member = new MeprUser($current_user->ID);

    // Get the active subscriptions for this user
    $active_subscriptions = $member->active_product_subscriptions('ids');

    if (
        current_user_can('administrator') ||
        ( current_user_can('mepr-active') && (
            in_array(49140, $active_subscriptions) ||
            in_array(9811, $active_subscriptions) ||
            in_array(41272, $active_subscriptions)
        ))
    ) {
        $advantagePlus = "yes";
    }


$allowMarketNarratives = false;
// Data & Insights ID => 15775
if( !current_user_can('memberpress_authorized', get_the_ID()) && has_term(15775, 'filter-types', get_the_ID()) ){
    // market narrative ID  => 16145
    $postType = get_term(16145, 'filter-types');
    $allowMarketNarratives = true;
}
?>
<section class="researchArticleTextHeader preview-module bg-white">
    <div class="container">                        
        <div class="item">
            <?php if(current_user_can('memberpress_authorized')) { ?>
                <?php if ( has_term( ['sector-outlooks', 'cxo-buyer-persona-profiles',  ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                    <?php if( $advantagePlus == 'yes') { ?> 
                        <div class="slide-preview-container advantagePlus-<?php echo esc_attr( $advantagePlus ); ?>">
                            <?php if ( have_rows( 'slider_images' ) ) : ?>
                                <div class="preview-main-slider">
                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                        <span class="main-slide">
                                            <span class="bg-container">
                                                <?php $image = get_sub_field( 'image' ); ?>
                                                <?php if ( $image ) { ?>
                                                    <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    <?php endwhile; ?>
                                </div>
                                <div class="preview-thumbnail-slider">
                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                        <span class="thumbnail-slide">
                                            <span class="bg-container">
                                                <?php $image = get_sub_field( 'image' ); ?>
                                                <?php if ( $image ) { ?>
                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    <?php endwhile; ?>
                                </div>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </div>
                    <?php } else { ?> 
                        <?php if ( has_term( ['cxo-buyer-persona-profiles'], 'filter-types' )){ ?>
                            <div class="slide-preview-container persona">
                                <div class="preview-main-slider">                                        
                                    <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                        <?php $slideMainCounter = 1; ?>                                                  
                                        <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                            <?php if($slideMainCounter == 1){ ?>
                                                <span class="main-slide non-member">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                        <?php } ?>
                                                    </span>
                                                </span>
                                            <?php } else { } ?>
                                        <?php $slideMainCounter++;?>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <?php if ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : ?>                                       
                                        <?php while ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : the_row(); ?>
                                            <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                    <span class="main-slide">
                                                        <span class="bg-container">
                                                            <?php $image = get_sub_field( 'image' ); ?>
                                                            <?php if ( $image ) { ?>
                                                                <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                            <?php } ?>
                                                        </span>
                                                    </span>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>                                              
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </div>
                                <div class="preview-thumbnail-slider">
                                    <?php if ( have_rows( 'slider_images' ) ) : ?>
                                        <?php $slideThumbCounter = 1; ?>   
                                        <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                            <?php if($slideThumbCounter == 1){ ?>
                                                <span class="thumbnail-slide">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                        <?php } ?>
                                                    </span>
                                                </span>
                                            <?php } else { } ?>
                                            <?php $slideThumbCounter++;?>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <?php if ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : ?>                                       
                                        <?php while ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : the_row(); ?>
                                            <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                    <span class="thumbnail-slide">
                                                        <span class="bg-container">
                                                            <?php $image = get_sub_field( 'image' ); ?>
                                                            <?php if ( $image ) { ?>
                                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                            <?php } ?>
                                                        </span>
                                                    </span>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>                                              
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </div>                                   
                            </div>
                            <script>
                                jQuery(document).ready(function() {
                                    jQuery("img").on("contextmenu", function(e) {
                                        return false;
                                    });
                                });
                            </script>
                        <?php } else { ?> 
                            <div class="slide-preview-container sector-preview">
                                <div class="preview-main-slider">                                        
                                    <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                        <?php $slideMainCounter = 1; ?>                                                  
                                        <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                            <?php if($slideMainCounter == 1){ ?>
                                                <span class="main-slide non-member">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                        <?php } ?>
                                                    </span>
                                                </span>
                                            <?php } else { } ?>
                                        <?php $slideMainCounter++;?>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <?php if ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : ?>                                       
                                        <?php while ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : the_row(); ?>
                                            <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                    <span class="main-slide">
                                                        <span class="bg-container">
                                                            <?php $image = get_sub_field( 'image' ); ?>
                                                            <?php if ( $image ) { ?>
                                                                <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                            <?php } ?>
                                                        </span>
                                                    </span>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>                                              
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </div>
                                <div class="preview-thumbnail-slider">
                                    <?php if ( have_rows( 'slider_images' ) ) : ?>
                                        <?php $slideThumbCounter = 1; ?>   
                                        <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                            <?php if($slideThumbCounter == 1){ ?>
                                                <span class="thumbnail-slide">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                        <?php } ?>
                                                    </span>
                                                </span>
                                            <?php } else { } ?>
                                            <?php $slideThumbCounter++;?>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                    <?php if ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : ?>                                       
                                        <?php while ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : the_row(); ?>
                                            <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                                <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                    <span class="thumbnail-slide">
                                                        <span class="bg-container">
                                                            <?php $image = get_sub_field( 'image' ); ?>
                                                            <?php if ( $image ) { ?>
                                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                            <?php } ?>
                                                        </span>
                                                    </span>
                                                <?php endwhile; ?>
                                            <?php else : ?>
                                                <?php // no rows found ?>
                                            <?php endif; ?>                                              
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>
                                </div>                                   
                            </div>
                            <script>
                                jQuery(document).ready(function() {
                                    jQuery("img").on("contextmenu", function(e) {
                                        return false;
                                    });
                                });
                            </script>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <div class="slide-preview-container test">
                        <?php if ( have_rows( 'slider_images' ) ) : ?>
                            <div class="preview-main-slider">
                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                    <span class="main-slide">
                                        <span class="bg-container">
                                            <?php $image = get_sub_field( 'image' ); ?>
                                            <?php if ( $image ) { ?>
                                                <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                            <?php } ?>
                                        </span>
                                    </span>
                                <?php endwhile; ?>
                            </div>
                            <div class="preview-thumbnail-slider">
                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                    <span class="thumbnail-slide">
                                        <span class="bg-container">
                                            <?php $image = get_sub_field( 'image' ); ?>
                                            <?php if ( $image ) { ?>
                                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                            <?php } ?>
                                        </span>
                                    </span>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                            <?php // no rows found ?>
                        <?php endif; ?>
                    </div>
                <?php } ?>                                    
            <?php } else { ?> 
                <?php if ( has_term( ['sector-outlooks', 'cxo-buyer-persona-profiles',  ], 'filter-types' ) && $advantageType == 'yes' ) { ?>                    
                    <?php if ( has_term( ['cxo-buyer-persona-profiles'], 'filter-types' )){ ?>
                        <div class="slide-preview-container persona">
                            <div class="preview-main-slider">                                        
                                <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                    <?php $slideMainCounter = 1; ?>                                                  
                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                        <?php if($slideMainCounter == 1){ ?>
                                            <span class="main-slide non-member">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                    <?php } ?>
                                                </span>
                                                <?php if($advantagePlus != 'yes'){ ?>
                                                    <span class="advantage-overlay">
                                                    </span>
                                                <?php } ?>
                                            </span>
                                        <?php } else { } ?>
                                    <?php $slideMainCounter++;?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                                <?php if ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : ?>                                       
                                    <?php while ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : the_row(); ?>
                                        <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                            <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                <span class="main-slide">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                        <?php } ?>
                                                    </span>
                                                    <?php if($advantagePlus != 'yes'){ ?>
                                                        <span class="advantage-overlay">
                                                        </span>
                                                    <?php } ?>
                                                </span>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>                                              
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </div>
                            <div class="preview-thumbnail-slider">
                                <?php if ( have_rows( 'slider_images' ) ) : ?>
                                    <?php $slideThumbCounter = 1; ?>   
                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                        <?php if($slideThumbCounter == 1){ ?>
                                            <span class="thumbnail-slide">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        <?php } else { } ?>
                                        <?php $slideThumbCounter++;?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                                <?php if ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : ?>                                       
                                    <?php while ( have_rows( 'members_only_blurred_images_persona', 'options' ) ) : the_row(); ?>
                                        <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                            <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                <span class="thumbnail-slide">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                        <?php } ?>
                                                    </span>
                                                </span>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>                                              
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </div>                                   
                        </div>
                        <script>
                            jQuery(document).ready(function() {
                                jQuery("img").on("contextmenu", function(e) {
                                    return false;
                                });
                            });
                        </script>
                    <?php } else { ?> 
                        <div class="slide-preview-container sector-preview">
                            <div class="preview-main-slider">                                        
                                <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                    <?php $slideMainCounter = 1; ?>                                                  
                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                        <?php if($slideMainCounter == 1){ ?>
                                            <span class="main-slide non-member">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        <?php } else { } ?>
                                    <?php $slideMainCounter++;?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                                <?php if ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : ?>                                       
                                    <?php while ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : the_row(); ?>
                                        <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                            <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                <span class="main-slide">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                        <?php } ?>
                                                    </span>
                                                </span>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>                                              
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </div>
                            <div class="preview-thumbnail-slider">
                                <?php if ( have_rows( 'slider_images' ) ) : ?>
                                    <?php $slideThumbCounter = 1; ?>   
                                    <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                        <?php if($slideThumbCounter == 1){ ?>
                                            <span class="thumbnail-slide">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        <?php } else { } ?>
                                        <?php $slideThumbCounter++;?>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                                <?php if ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : ?>                                       
                                    <?php while ( have_rows( 'members_only_blurred_images_sector', 'options' ) ) : the_row(); ?>
                                        <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                            <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                                <span class="thumbnail-slide">
                                                    <span class="bg-container">
                                                        <?php $image = get_sub_field( 'image' ); ?>
                                                        <?php if ( $image ) { ?>
                                                            <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                        <?php } ?>
                                                    </span>
                                                </span>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <?php // no rows found ?>
                                        <?php endif; ?>                                              
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <?php // no rows found ?>
                                <?php endif; ?>
                            </div>                                   
                        </div>
                        <script>
                            jQuery(document).ready(function() {
                                jQuery("img").on("contextmenu", function(e) {
                                    return false;
                                });
                            });
                        </script>
                    <?php } ?>                    
                <?php } else { ?>
                    <div class="slide-preview-container">
                        <div class="preview-main-slider">                                        
                            <?php if ( have_rows( 'slider_images' ) ) : ?>  
                                <?php $slideMainCounter = 1; ?>                                                  
                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                    <?php if($slideMainCounter == 1){ ?>
                                        <span class="main-slide non-member">
                                            <span class="bg-container">
                                                <?php $image = get_sub_field( 'image' ); ?>
                                                <?php if ( $image ) { ?>
                                                    <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    <?php } else { } ?>
                                <?php $slideMainCounter++;?>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                            <?php if ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : ?>                                       
                                <?php while ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : the_row(); ?>
                                    <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                        <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                            <span class="main-slide">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <?php echo wp_get_attachment_image( $image['ID'], 'hero-slide-preview', false, adapt_main_slide_image_attrs( $image['alt'] ) ); ?>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>                                              
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </div>
                        <div class="preview-thumbnail-slider">
                            <?php if ( have_rows( 'slider_images' ) ) : ?>
                                <?php $slideThumbCounter = 1; ?>   
                                <?php while ( have_rows( 'slider_images' ) ) : the_row(); ?>
                                    <?php if($slideThumbCounter == 1){ ?>
                                        <span class="thumbnail-slide">
                                            <span class="bg-container">
                                                <?php $image = get_sub_field( 'image' ); ?>
                                                <?php if ( $image ) { ?>
                                                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                <?php } ?>
                                            </span>
                                        </span>
                                    <?php } else { } ?>
                                    <?php $slideThumbCounter++;?>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                            <?php if ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : ?>                                       
                                <?php while ( have_rows( 'members_only_blurred_slider_images', 'options' ) ) : the_row(); ?>
                                    <?php if ( have_rows( 'blurred_images' ) ) : ?>                                       
                                        <?php while ( have_rows( 'blurred_images' ) ) : the_row(); ?>
                                            <span class="thumbnail-slide">
                                                <span class="bg-container">
                                                    <?php $image = get_sub_field( 'image' ); ?>
                                                    <?php if ( $image ) { ?>
                                                        <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'] ) ); ?>
                                                    <?php } ?>
                                                </span>
                                            </span>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>                                              
                                <?php endwhile; ?>
                            <?php else : ?>
                                <?php // no rows found ?>
                            <?php endif; ?>
                        </div>                                   
                    </div>
                    <script>
                        jQuery(document).ready(function() {
                                jQuery("img").on("contextmenu", function(e) {
                                    return false;
                                });
                        });
                    </script>
                <?php } ?>     
                
            <?php } ?>
            <div class="textContainer">                
                <?php if(current_user_can('memberpress_authorized') || $allowMarketNarratives ) { ?>
                    <?php if ( has_term( ['sector-outlooks', 'cxo-buyer-persona-profiles' ], 'filter-types' ) && $advantageType == 'yes' ) { ?>
                        <?php if( $advantagePlus == 'yes') { ?> 
                            <?php $download = get_sub_field( 'download' ); ?>
                            <?php if ( $download ) { ?>
                                <a class="download button red-button" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( $download['url'] ); ?>">Download</a>                                             
                            <?php } ?>
                        <?php } else { ?> 
                            <?php if ( has_term( ['cxo-buyer-persona-profiles' ], 'filter-types' )){ ?>
                                    <?php $download = get_sub_field( 'download' ); ?>
                                <?php if ( $download ) { ?>    
                                    <a class="download button red-button disabled-button locked-request" href="#requestdownloadPersona">Request Access</a>                                       
                                <?php } ?>
                            <?php } else { ?> 
                                    <?php $download = get_sub_field( 'download' ); ?>
                                <?php if ( $download ) { ?>    
                                    <a class="download button red-button disabled-button locked-request" href="#requestdownloadSector">Request Access</a>                                       
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?> 
                        <?php $download = get_sub_field( 'download' ); ?>
                        <?php if ( $download ) { ?>
                            <a class="download button red-button" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( $download['url'] ); ?>">Download</a>                                             
                        <?php } ?>
                    <?php } ?>                                        
                <?php } else { ?>
                    <?php $download = get_sub_field( 'download' ); ?>
                    <?php if ( $download ) { ?>  
                        <?php $downloadButtonText = get_field('request_download_button_text'); ?>  
                        <a class="download button red-button disabled-button locked-request" href="#requestdownload"><?php if($downloadButtonText){ ?><?php echo esc_html( $downloadButtonText ); ?><?php } else { ?>Request to Download<?php } ?></a>                                       
                    <?php } ?>
                <?php } ?>
                <span class="published labelSmall text-dark-grey">

                    Published <?php echo get_the_date('M j, Y') ?> in
                    
                </span>
                <?php if ( has_term( ['cxo-buyer-persona-profiles' ], 'filter-types' )){ ?>
                    <span class="type-topic labelSmall">
                        <?php if (yoast_get_primary_term_id('persona-mapping')) {
                            $primary_term_topic_id = yoast_get_primary_term_id('persona-mapping');
                            $postTopic = get_term( $primary_term_topic_id );
                        } else {
                            if(get_the_terms( $post->ID, 'persona-mapping' )){
                                $terms = get_the_terms( $post->ID, 'persona-mapping' );
                                foreach($terms as $term) {
                                    $postTopic = $term;
                                }
                            }
                        }?>
                        <?php if ( !empty( $postTopic ) ) { ?>
                            <a href="/persona-insights/?persona=<?php echo esc_attr( $postTopic->slug ); ?>" class="topic-filter red-text"><?php echo esc_html( $postTopic->name ); ?> Buyer Persona Profile</a>
                        <?php } ?>
                    </span>
                <?php } else { ?>
                    <span class="type-topic labelSmall">
                    <?php $topics  = [];

                        // Get all topic terms once
                        $all_topics = get_the_terms($post_id, 'topic');

                        // Bail early if none
                        if ($all_topics && !is_wp_error($all_topics)) {

                            // 1. Try to get Yoast primary
                            $primary_id = yoast_get_primary_term_id('topic');

                            if ($primary_id) {
                                $primary = get_term($primary_id, 'topic');

                                if ($primary && !is_wp_error($primary)) {
                                    $topics[] = $primary;
                                }

                                // 2. Find ONE secondary (first non-primary)
                                foreach ($all_topics as $term) {
                                    if ($term->term_id !== (int) $primary_id) {
                                        $topics[] = $term;
                                        break;
                                    }
                                }

                            } else {
                                // 3. No primary → just take first two
                                $topics = array_slice($all_topics, 0, 2);
                            }
                        }

                        // 4. Output (max 2 guaranteed)
                        foreach ($topics as $topic) : ?>
                            <a href="<?php echo esc_url(get_term_link($topic)); ?>" class="topic-filter red-text">
                                <?php echo esc_html($topic->name); ?>
                            </a>
                        <?php endforeach; ?>

                    <?php if (yoast_get_primary_term_id('filter-types')) {  
                            $primary_term_type_id = yoast_get_primary_term_id('filter-types');
                            $postType = get_term( $primary_term_type_id );
                        } else {
                            if(get_the_terms( $post->ID, 'filter-types' )){
                                $terms = get_the_terms( $post->ID, 'filter-types' );
                                foreach($terms as $term) {
                                    $postType = $term;
                                }
                            }
                        }
                        
                        // Data & Insights ID => 15775
                        if( !current_user_can('memberpress_authorized', get_the_ID()) && has_term(15775, 'filter-types', get_the_ID()) ){
                            // market narrative ID  => 16145
                            $postType = get_term(16145, 'filter-types');
                        }
                    ?>
                    <?php if ( !empty( $postType ) ) { ?>
                        <span class="published labelSmall text-dark-grey tytpe-label">Type</span>
                        <a href="<?php echo esc_url( get_term_link($postType) ); ?>" class="topic-filter red-text"><?php echo esc_html( $postType->name ); ?> </a>
                    <?php } ?>
                </span>
                <?php } ?>            

                                <?php if ( have_rows( 'contributors' ) ) : ?>

    <?php
    $contributors = get_field( 'contributors' );
    $count = 0;

    if ( is_array( $contributors ) ) {
        foreach ( $contributors as $contributor ) {
            if ( ! empty( $contributor['contributor_name'] ) ) {
                $count++;
            }
        }
    }
    ?>

    <span class="contributor-container">
        <span class="contributor-title labelSmall text-dark-grey">
            <?php echo ( $count > 1 ) ? 'Authors' : 'Author'; ?>
        </span>

        <?php while ( have_rows( 'contributors' ) ) : the_row(); ?>
            <?php $post_object = get_sub_field( 'contributor_name' ); ?>
            <?php if ( $post_object ) : ?>
                <?php
                $post = $post_object;
                setup_postdata( $post );
                ?>
                <!-- <a href="<?php the_permalink(); ?>"> -->
                    <span class="contributor labelSmall text-black">
                        
                            <?php echo esc_html( get_the_title() ); ?>
                       
                    </span>
                     <!-- </a> -->
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    </span>

<?php endif; ?>


            </div>
            <span class="share-save-container mobile">
                <span class="saveInsight">
                    <?php if(current_user_can('memberpress_authorized')) { ?>
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
        </div>
    </div>
</section>