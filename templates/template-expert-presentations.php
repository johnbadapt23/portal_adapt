<?php
/**
 * Template Name: Expert Presentations Template
 */

get_header();
?>


<main id="main" role="main" class="main-topic">

    <?php get_template_part( 'templates/components/_expert-presentations-banner' ); ?>

    <section class="filter bg-dark">
        <div class="container">
            <div class="formWrapper">
                <span class="searchField">
                    <span class="search">
                        <input class="searchInput" type="text" name="searchWords" id="search" placeholder="Find Presentations" />
                        <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify-grey.svg" />
                    </span>
                </span>
                <span class="filtersButtonMobile">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/filters.svg" width="14" height="14" loading="lazy" decoding="async" alt="Filters" />
                    <span class="filterButtonText">Filters</span>
                </span>
                <span class="dropDowns">
                    <span class="subTopics">
                        <label for="subtopics">Explore By</label>
                        <select name="subtopics" id="">
                            <option value="">Events</option>
                            <option value="topics">Topics</option>
                        </select>
                    </span>
                    <span class="filterBy">
                        <label for="filterBy">Filter By</label>
                        <select name="filterby" id="">
                            <option value="">All</option>
                            <option value="one">One</option>
                            <option value="two">Two</option>
                            <option value="three">Three</option>
                            <option value="four">Four</option>
                            <option value="five">Five</option>
                            <option value="six">Six</option>
                        </select>
                    </span>
                </span>
            </div>
        </div>
    </section>

    <?php get_template_part( 'templates/components/_expert-presentation-featured' ); ?>
    <?php get_template_part( 'templates/components/_event-slider-portal' ); ?>
    <?php get_template_part( 'templates/components/_expert-presentation-featured-article-video-portal' ); ?>
    <?php get_template_part( 'templates/components/_event-slider-portal' ); ?>


</main>

<?php get_footer(); ?>
