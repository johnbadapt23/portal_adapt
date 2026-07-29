<?php
/**
 * Template Name: Main Topic Template
 */

get_header();
?>


<main id="main" role="main" class="main-topic">

    <?php get_template_part( 'templates/components/_main-topic-banner' ); ?>

    <section class="filter">
        <div class="container">
            <div class="formWrapper">
                <span class="searchField">
                    <span class="search">
                        <input class="searchInput" type="text" name="searchWords" id="search" placeholder="Find in Cloud" />
                        <input class="searchButton" type="image" alt="Search" src="<?php echo get_template_directory_uri(); ?>/assets/images/magnify.svg" />
                    </span>
                </span>
                <span class="filtersButtonMobile">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/filters.svg" alt="Filters" />
                    <span class="filterButtonText">Filters</span>
                </span>
                <span class="dropDowns">
                    <span class="subTopics">
                        <label for="subtopics">Explore Within</label>
                        <select name="subtopics" id="">
                            <option value="">All Subtopics</option>
                            <option value="one">One</option>
                            <option value="two">Two</option>
                            <option value="three">Three</option>
                            <option value="four">Four</option>
                            <option value="five">Five</option>
                            <option value="six">Six</option>
                        </select>
                    </span>
                    <span class="filterBy">
                        <label for="filterBy">Filter By</label>
                        <select name="filterby" id="">
                            <option value="">All Types</option>
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

    <?php get_template_part( 'templates/components/_main-topic-featured-grid' ); ?>
    <?php get_template_part( 'templates/components/_main-sub-topic-grid' ); ?>

</main>

<?php get_footer(); ?>
