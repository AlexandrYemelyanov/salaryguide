<?php
/**
 * The page.
 *
 * @package WordPress
 * @subpackage Salary Guide Hays 2
 * @since Salary Guide 2.0
 */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <!-- Begin main_xs -->
    <div class="main_xs">
        <div class="container flex middle">
            <div class="main_xs__info">
                <ul class="bradcrumbs bradcrumbs--xs flex middle">
                    <li><a href="/">Главная</a></li>
                    <li><a href="/surveys-infographics/">Обзор рынка труда</a></li>
                </ul>
                <h1>Ответы на вопросы соискателей</h1>
            </div>
            <img class="main_xs__icon" src="/wp-content/themes/salaryguide2/assets/img/overview/icon1.svg" alt="">

        </div>
    </div>
    <!-- End main_xs -->

    <?php

    $survey = career_get_survey('sw');
    include_once (__DIR__.'/survey.template.php');
    the_content();

    ?>

<?php endwhile; ?>
<?php get_footer(); ?>