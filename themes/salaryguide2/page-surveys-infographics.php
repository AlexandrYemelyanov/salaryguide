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

    <!-- Begin main_sm -->
<div class="main_sm" style="background-image: url(/wp-content/themes/salaryguide2/assets/img/main_sm/bg2-2.jpg)">
    <div class="container">
    <!--    <ul class="bradcrumbs flex middle">
            <li><a href="/">Главная</a></li>
        </ul> -->
        <div class="main_sm__info">
            <h1 class="main_sm__title"><?php the_field('title_obzor'); ?></h1>
            <p class="main_sm__text"><?php the_field('description_obzor'); ?></p>

        </div>
    </div>
</div>
<!-- End main_sm -->

<!-- Begin overview -->
<div class="overview">
    <div class="container">
        <div class="overview__list flex">
            <div class="overview__item">
                <div class="overview__info flex">
                    <p class="overview__title"><?php the_field('title_1'); ?></p>
                    <p class="overview__text"><?php the_field('desc_1'); ?></p>

                    <div class="overview__bottom flex bottom">
                        <a class="btn btn--big" href="/surveys-infographics/faq/">Подробнее</a>
                        <img class="overview__icon" src="/wp-content/themes/salaryguide2/assets/img/overview/icon1.svg" alt=""></div>
                </div>
            </div>
            <div class="overview__item">
                <div class="overview__info flex">
                    <p class="overview__title"><?php the_field('title_2'); ?></p>
                    <p class="overview__text"><?php the_field('desc_2'); ?></p>

                    <div class="overview__bottom flex bottom">
                        <a class="btn btn--big" href="/surveys-infographics/faq-company/">Подробнее</a>
                        <img class="overview__icon" src="/wp-content/themes/salaryguide2/assets/img/overview/icon2.svg" alt=""></div>
                </div>
            </div>
            <div class="overview__item">
                <div class="overview__info flex">
                    <p class="overview__title"><?php the_field('title_3'); ?></p>
                    <p class="overview__text"><?php the_field('desc_3'); ?></p>

                    <div class="overview__bottom flex bottom">
                        <a class="btn btn--big" href="/surveys-infographics/conflict-points/">Подробнее</a>
                        <img class="overview__icon" src="/wp-content/themes/salaryguide2/assets/img/overview/icon3.svg" alt=""></div>
                </div>
            </div>
            <div class="overview__item">
                <div class="overview__info flex">
                    <p class="overview__title"><?php the_field('title_4'); ?></p>
                    <p class="overview__text"><?php the_field('desc_4'); ?></p>

                    <div class="overview__bottom flex bottom">
                        <a class="btn btn--big" href="/surveys-infographics/infographics/">Подробнее</a>
                        <img class="overview__icon" src="/wp-content/themes/salaryguide2/assets/img/overview/icon4.svg" alt=""></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End overview -->
<div class="container flex middle">
        <?php the_content();  ?>
    </div>

<?php endwhile; ?>
<?php get_footer(); ?>