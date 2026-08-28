<?php
/**
 * Template Name: Single Research Article Text Template
 */

get_header();
?>


<main id="main" role="main" class="main">

    <?php get_template_part( 'templates/components/_single-research-text-header-block' ); ?>

    <section class="articleWrapper bg-white">
        <div class="container">
            <div class="column first">
                <span class="saveInsight">
                    <img class="insightIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/save-insight.svg" width="42" height="59" loading="lazy" alt="Save" />
                </span>
            </div>
            <div class="column second">
                <div class="article">

                    <span class="saveInsight mobile">
                        <img class="insightIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/save-insight-landscape.svg" width="69" height="24" loading="lazy" alt="Save" />
                    </span>

                    <span class="introGrab">
                        At CIO Edge, globally experienced IT veteran David Banger discussed how to grasp the opportunity for technology teams as “technology” and “digital” intersect.
                    </span>
                    <span class="smallQuote">
                        “Digital is about customer intimacy and global scale.”
                    </span>
                    <div class="articleWrapper">
                        <h3 class="articleSubTitle">Keynote Focus</h3>
                        <p>The intersect of “technology” and “digital” presents a significant opportunity for a mainstream technology executive and their team. After establishing the foundation to realise the digital potential, every CIO should lead from the front to execute on their strategy. Learn how to grasp the opportunity that lies ahead for technology teams as “technology” and “digital” intersect with globally experienced IT veteran David Banger.</p>
                        <p>This also relates to one of ADAPT’s 12 Core Competencies, Empowering Workforce, which discusses this topic at length.</p>
                        <h3 class="articleSubTitle">Key Findings</h3>
                        <p><strong>Identify task value</strong></p>
                        <p>Identify task value Tasks we carry out today must be focused on directly achieving business outcomes for the business. Although a single job description can encompass a multitude of activities, the business should encourage employees to individually analyse their work, simplify and standardise repetitive tasks. Tasks that drive business outcomes should be incentivised.</p>
                        <p><strong>Diversity for innovation</strong></p>
                        <p>Leadership teams must comprise of executives from sectors, departments, and backgrounds. Employee executives from overseas and enable flexible working. Establishing a digital startup precinct will also create an atmosphere for casual connections to encourage innovation. Ask your employees for suggestions on small innovation projects.</p>
                        <p><strong>Team alignment on transformation</strong></p>
                        <p>Identify where your customers are providing data and create a platform where they can transact on a range of products and services related to this data. Bring your employees on this digital transformation journey by creating not just a vision and mission, but a ‘dream’ like Tesla’s 12-year vision to create a sustainable vehicle for the mass market.</p>
                        <p>
                            <img src="" loading="lazy" alt="" />
                        </p>
                        <h4>Key advice</h4>
                        <ul>
                            <li>Simplify and standardise BAU tasks, and incentivise work that adds value to the business.</li>
                            <li>Enable flexible working to encourage diversity of thought.</li>
                            <li>Align your teams through an organisational dream, along with your vision and mission.</li>
                        </ul>
                        <blockquote>
                            <p>The pandemic has driven a transformational shift in how we work and deliver services and has the potential to reduce the legacy headache once and for all, and Cloud has proved fit for purpose.”</p>
                        </blockquote>
                    </div>
                </div>
                <div class="authors">
                    <div class="contributor writtenBy">
                        <div class="imageSizeContainer">
                            <div class="bgContainer">
                                <img class="desktop" src="" loading="lazy" alt="" />
                            </div>
                        </div>
                        <div class="textContainer">
                            <span class="title">Written By</span>
                            <p><strong>Matt Boon</strong>, Director of Strategic Research at ADAPT</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column third">
                <div class="articleShare">
                    <span class="shareText">Share this article with a colleague</span>
                    <a href="" class="button redOutline">Share</a>
                </div>
                <div class="relatedArticles">
                    <h2 class="related">You may also like</h2>
                    <div class="item">
                        <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                            <div class="bgContainer">
                                <?php $image = get_sub_field('background_image'); ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'], 'class' => 'desktop' ) ); ?>
                            </div>
                        </a>
                        <div class="textContainer">
                            <span class="topicFilter">
                                <a href="" class="topicFilterText">Security</a>
                                <a href="" class="topicFilterText">Research</a>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="title">ASX’s Dan Chesterman shares how to measure the success of digital transformation with ADAPT’s Matt Boon</a>
                        </div>
                    </div>
                    <div class="item">
                        <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                            <div class="bgContainer">
                                <?php $image = get_sub_field('background_image'); ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'], 'class' => 'desktop' ) ); ?>
                            </div>
                        </a>
                        <div class="textContainer">
                            <span class="topicFilter">
                                <a href="" class="topicFilterText">Security</a>
                                <a href="" class="topicFilterText">Research</a>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="title">ASX’s Dan Chesterman shares how to measure the success of digital transformation with ADAPT’s Matt Boon</a>
                        </div>
                    </div>
                    <div class="item">
                        <a href="<?php the_permalink(); ?>" class="imageSizeContainer">
                            <div class="bgContainer">
                                <?php $image = get_sub_field('background_image'); ?>
                                <?php echo wp_get_attachment_image( $image['ID'], 'full', false, array( 'alt' => $image['alt'], 'class' => 'desktop' ) ); ?>
                            </div>
                        </a>
                        <div class="textContainer">
                            <span class="topicFilter">
                                <a href="" class="topicFilterText">Security</a>
                                <a href="" class="topicFilterText">Research</a>
                            </span>
                            <a href="<?php the_permalink(); ?>" class="title">ASX’s Dan Chesterman shares how to measure the success of digital transformation with ADAPT’s Matt Boon</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php get_template_part( 'templates/components/_related-articles-portal' ); ?>


</main>

<?php get_footer(); ?>
