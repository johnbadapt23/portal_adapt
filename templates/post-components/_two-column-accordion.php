
<section class="advantage-two-column-accordion <?php echo esc_attr( get_sub_field( 'background_colour' ) ); ?>">
    <div class="container">
        <div class="column-container <?php echo esc_attr( get_sub_field( 'orientation' ) ); ?>">
            <div class="column link-column">
                <span class="labelXXsmall red-text"><?php echo esc_html( get_sub_field( 'pre_title' ) ); ?></span>
                <h2 class="headerXsmall text-bold"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
                <?php if ( have_rows( 'accordion_items' ) ) : ?>
                    <span class="accordion-container">
                        <?php while ( have_rows( 'accordion_items' ) ) : the_row(); ?>
                                <span class="accordion-item">                                    
                                    <span class="question text-regular medium-weight"><?php echo esc_html( get_sub_field( 'question' ) ); ?></span>
                                    <span class="answer">
                                        <span class="answer-inner">
                                            <?php echo esc_html( get_sub_field( 'answer' ) ); ?>
                                            <?php if (get_sub_field('link')) { ?>
                                                <span class="link-container">
                                                    <a href="<?php echo esc_url( get_sub_field('link') ); ?>" target="_self" class="text-link red-text-link uppercase arrow-link"><?php echo esc_html( get_sub_field('link_text') ); ?></a>
                                                </span>  
                                            <?php } ?>                                            
                                        </span>
                                    </span>
                                </span>
                        <?php endwhile; ?>
                    </span> 
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
                <?php if ( have_rows( 'button' ) ) : ?>
                    <span class="button-container desktop">
                        <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if( get_sub_field( 'link_type' ) == 'link'){ ?>
                                <a class="small-button std-button red-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                            <?php } else { ?>
                                <?php
                                // A hardcoded id here (formerly the same "bechamrk_formPopup"
                                // on every row) meant that on any button repeater with more
                                // than one row, every row's magnificPopup trigger (which
                                // resolves its target via this href="#id", not a relative
                                // DOM lookup) opened the FIRST row's modal. get_row_index()
                                // makes each row's trigger/target pair unique.
                                $popup_id = 'bechamrk_formPopup-link-' . get_row_index();
                                ?>
                                <?php if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                <a class="formPopupHubspot download-file-button with-icon small-button std-button red-button" href="#<?php echo esc_attr( $popup_id ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                <?php } else { ?>
                                <a class="formPopupHubspot small-button std-button red-button" href="#<?php echo esc_attr( $popup_id ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                <?php } ?>
                                <div style="display: none;">
                                    <div class="preview-cta-form login-form-container" id="<?php echo esc_attr( $popup_id ); ?>">
                                        <div class="form-container"><?php echo adapt_render_hubspot_embed( get_sub_field( 'hubspot_embed_code' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?></div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php endwhile; ?>
                    </span>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
            <div class="column image-column">
                <?php $image = get_sub_field( 'image' ); ?>
                <?php if ( $image ) { ?>
                    <?php echo wp_get_attachment_image( $image['ID'], 'full', false, [ 'alt' => $image['alt'] ] ); ?>
                <?php } ?>
                <?php if ( have_rows( 'button' ) ) : ?>
                    <span class="button-container desktop">
                        <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                            <?php if( get_sub_field( 'link_type' ) == 'link'){ ?>
                                <a class="small-button std-button red-button" href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="<?php echo esc_attr( get_sub_field( 'link_target' ) ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                            <?php } else { ?>
                                <?php
                                // Same unique-id fix as the link-column block above - this is
                                // a second render of the same "button" repeater, so it needs
                                // its own prefix ("image" vs "link") to stay unique from that
                                // block's ids too, not just unique within its own loop.
                                $popup_id = 'bechamrk_formPopup-image-' . get_row_index();
                                ?>
                                <?php if( get_sub_field( 'link_type' ) =='download-form') { ?>
                                <a class="formPopupHubspot download-file-button with-icon small-button std-button red-button" href="#<?php echo esc_attr( $popup_id ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                <?php } else { ?>
                                <a class="formPopupHubspot small-button std-button red-button" href="#<?php echo esc_attr( $popup_id ); ?>"><?php echo esc_html( get_sub_field( 'link_text' ) ); ?></a>
                                <?php } ?>
                                <div style="display: none;">
                                    <div class="preview-cta-form login-form-container" id="<?php echo esc_attr( $popup_id ); ?>">
                                        <div class="form-container"><?php echo adapt_render_hubspot_embed( get_sub_field( 'hubspot_embed_code' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-authored HubSpot embed markup requires raw <script> output; wp_kses_post() would strip the tag the embed needs to function. ?></div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php endwhile; ?>
                    </span>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


