<?php
/**
 * The page.
 *
 * @package WordPress
 * @subpackage Salary Guide Hays 2
 * @since Salary Guide 2.0
 */

get_header();

//salaryguideCreatePDF();

?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <!-- Begin main_sm -->
    <div class="main_sm" style="background-image: url(<?php echo get_template_directory_uri();?>/assets/img/main_sm/bg5.jpg)">
        <div class="container">
        <!--    <ul class="bradcrumbs flex middle">
                <li><a href="/">Главная</a></li>
            </ul> -->
            <div class="main_sm__info">
                <h1 class="main_sm__title">Конструктор резюме</h1>
                <p class="main_sm__text">Резюме сегодня  — это демо-версия кандидата, которая позволяет первично познакомиться, понять в какой точке карьерного пути он находится сейчас и предположить его дальнейшее развитие. Наши эксперты посмотрели на структуру резюме глазами рекрутеров и разработали шаблон, который увеличит ваши шансы быть замеченными потенциальными работодателями.</p>
            </div>
        </div>
    </div>
    <!-- End main_sm -->

    <!-- Begin constructor -->
    <div class="constructor">
        <div class="container">
            <div class="constructor__info">
                <div class="constructor__cv flex top center">
                    <img class="constructor__cv_img" src="/wp-content/themes/salaryguide2/assets/img/resume/cv-1.jpg" alt="">
                    <img class="constructor__cv_img" src="/wp-content/themes/salaryguide2/assets/img/resume/cv-2.jpg" alt="">
                    <img class="constructor__cv_img" src="/wp-content/themes/salaryguide2/assets/img/resume/cv-3.jpg" alt="">
                </div>
                <p class="constructor__text">7 простых шагов и вы получите несколько вариантов готового резюме на выбор</p>
                <a class="btn btn--big" href="/create-resume/">Создать резюме</a>
            </div>
        </div>
    </div>
    <!-- End constructor -->

    <div class="container flex middle">
        <?php the_content();  ?>
    </div>

<?php endwhile; ?>
<?php get_footer(); ?>