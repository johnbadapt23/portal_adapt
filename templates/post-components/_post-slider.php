<?php 
global $membershipType;

$membershipType = trim($membershipType);

$it_pro_types_ids    = get_field('it_pro_types', 'options') ?: [];
$advantage_types_ids = get_field('advantage_types', 'options') ?: [];

$membership_allowed_ids = [];

if ($membershipType === 'it-pro') {
    $membership_allowed_ids = $it_pro_types_ids;
} elseif ($membershipType === 'advantage') {
    $membership_allowed_ids = $advantage_types_ids;
}

/**
 * REUSABLE: Membership tax_query
 * Use this ONLY for automatic WP_Query loops
 */
$membership_tax_query = [];

if ( ! empty( $membership_allowed_ids ) ) {
    $membership_tax_query[] = [
        'taxonomy' => 'filter-types',
        'field'    => 'term_id',
        'terms'    => $membership_allowed_ids,
        'operator' => 'IN',
    ];
}
?>
<section class="portal-post-slider portal <?php echo esc_attr( get_sub_field('background_colour') ); ?>">
    <div class="container">
        <div class="blockTitle">
            <h2 class="headerXsmall text-bold"><?php echo esc_html( get_sub_field( 'title' ) ); ?></h2>
            <?php if(get_sub_field( 'view_all_link' )){ ?> 
                <a href="<?php echo esc_url( get_sub_field( 'view_all_link' ) ); ?>" class="text-link red-text-link uppercase arrow-link">View All</a>
            <?php } ?>            
        </div>
    </div>
    <div class="container">
        <div class="slideContainer">
            
            <span class="leftslideCover"></span>
            <span class="rightslideCover"></span>
            <div class="slider">
                <?php if( get_sub_field( 'choose_posts' ) == 'choose'){ ?>
                    <?php if ( have_rows( 'posts' ) ) : ?>
                        <?php while ( have_rows( 'posts' ) ) : the_row(); ?>
                            <?php $post_object = get_sub_field( 'post' ); ?>
                            <?php if ( $post_object ): ?>
                                <?php $post = $post_object; ?>
                                <?php setup_postdata( $post ); ?>
                                <?php 
                                    $extra_classes = 'slider-item';
                                    include locate_template('/templates/components/_article-card.php'); ?>
                                <?php wp_reset_postdata(); ?>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <?php // no rows found ?>
                    <?php endif; ?>
                <?php } else { ?>
                    <?php if(get_sub_field( 'taxonomy' ) == 'all'){ ?> 
                     <?php
                        $args = [
                            'post_type'      => 'post',
                            'posts_per_page' => 6,
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                        ];

                        // 🔒 Apply membership restriction if it exists
                        if ( ! empty( $membership_tax_query ) ) {
                            $args['tax_query'] = [
                                'relation' => 'AND',
                                $membership_tax_query[0],
                            ];
                        }
                    ?>
                    <?php } else if(get_sub_field( 'taxonomy' ) == 'topics'){ ?> 
                        <?php $topic_term = get_sub_field( 'topic' ); ?>
                        <?php if ( $topic_term ) {

                            $tax_query = [
                                'relation' => 'AND',
                                [
                                    'taxonomy' => 'topic',
                                    'field'    => 'slug',
                                    'terms'    => $topic_term->slug,
                                ],
                            ];

                            // 🔒 Apply membership restriction (automatic loops only)
                            if ( ! empty( $membership_tax_query ) ) {
                                $tax_query[] = $membership_tax_query[0];
                            }

                            $args = [
                                'post_type'      => 'post',
                                'posts_per_page' => 6,
                                'tax_query'      => $tax_query,
                            ];
                        }
                        
                        ?>
                    <?php } else if(get_sub_field( 'taxonomy' ) == 'types'){ ?>
                        <?php $type_term = get_sub_field( 'type' ); ?>
                        <?php if ( $type_term ): 
                            $args = [
                                'post_type'      => 'post',
                                'posts_per_page' => 6,
                                'tax_query'      => [
                                    [
                                        'taxonomy' => 'filter-types',
                                        'field'    => 'slug',
                                        'terms'    => $type_term->slug,
                                    ]
                                ]
                            ];
                        
                        endif; ?>
                    <?php } else { ?> 
                        <?php $edge_event_term = get_sub_field( 'edge_event' ); ?>
                        <?php
                            if ( $edge_event_term ) {

                                $tax_query = [
                                    'relation' => 'AND',
                                    [
                                        'taxonomy' => 'insights-event',
                                        'field'    => 'slug',
                                        'terms'    => $edge_event_term->slug,
                                    ],
                                ];

                                // 🔒 Apply membership restriction (automatic loops only)
                                if ( ! empty( $membership_tax_query ) ) {
                                    $tax_query[] = $membership_tax_query[0];
                                }

                                $args = [
                                    'no_found_rows'  => true,
                                    'post_type'      => 'post',
                                    'posts_per_page' => 6,
                                    'tax_query'      => $tax_query,
                                ];
                            }
                            ?>
                    <?php } ?>
                    
                

                    <?php
                        $posts = new WP_Query( $args );
                        if( $posts->have_posts() ): ?>
                            <?php while( $posts->have_posts() ) : $posts->the_post(); ?>
                                <?php 
                                    $extra_classes = 'slider-item';
                                    include locate_template('/templates/components/_article-card.php'); ?>
                            <?php endwhile; ?>
                        <?php endif;?>
                    <?php wp_reset_postdata(); ?>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="slider-control-container">
        <div class="container">
            <div class="slider-controls">
                <div class="slider-dots"></div>
                <div class="slider-arrows"></div>            
            </div>
        </div>
    </div>
</section>