<?php
/**
 * The page.
 *
 * @package WordPress
 * @subpackage Salary Guide Hays 2
 * @since Salary Guide 2.0
 */

get_header();
?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <!-- Begin main_sm -->
    <div class="main_sm" style="background-image: url(<?php echo get_template_directory_uri();?>/assets/img/main_sm/bg3.jpg)">
        <div class="container">
            <ul class="bradcrumbs flex middle">
                <li><a href="/">Главная</a></li>
            </ul>
            <div class="main_sm__info">
                <h1 class="main_sm__title">Информация о секторе</h1>
                <p class="main_sm__text">Посмотрите самые интересные результаты исследования рынка труда в инфографике. Лучший способ продемонстрировать приверженность нашим клиентам и кандидатам - это наши амбиции по отношению к ним. Их успех - это наш успех, поэтому мы не сдерживаемся.</p>
            </div>
        </div>
    </div>
    <!-- End main_sm -->

    <!-- Begin trends -->
    <div class="trends">
        <!-- Begin trends__select -->
        <div class="trends__select">
            <div class="container flex top">
                <div class="trends__select_left">
                    <h2>Что нового <span>на рынке труда?</span></h2>
                </div>
                <div class="trends__select_right">
                    <p class="trends__select_text">Выберите интересующую отрасль, и мы расскажем об основных&nbsp;трендах.</p>
                    <!-- Begin form -->
                    <?php
                    $id = $_GET['sector']??0;
                    $id = (int)$id;
                    $aoe = career_get_all_aoe();
                    ?>

                    <form class="trends__select_form form flex top" action="/info-sector/" method="get">
                        <label class="form__trends">
                            <select class="form__select" id="sector-aoe" name="sector" >
                                <? if(count($aoe)): ?>
                                    <? foreach($aoe as $_id=>$name):
                                        $selected = "";
                                        if($_id == $id) $selected = ' selected';
                                        ?>
                                        <option value="<?= $_id; ?>"<?= $selected; ?>><?= $name; ?></option>
                                    <? endforeach; ?>
                                <? endif; ?>
                            </select>
                            <span class="form__error">Поле обязательно к заполнению</span>
                        </label>
                        <button class="form__btn btn btn--big" type="submit">Показать тренды</button>
                    </form>
                    <!-- End form -->
                </div>
            </div>
        </div>
        <!-- End trends__select -->
        <!-- Begin trends__info -->
        <div class="trends__info">
            <div class="container flex top">
                <div class="trends__info_left">
                    <h2>Тренды</h2>
                </div>
                <div class="trends__info_right text">
                    <?php echo do_shortcode( '[carrer-sector-tendence]' ); ?>
                </div>
            </div>
        </div>
        <!-- End trends__info -->
    </div>
    <!-- End trends -->

    <!-- Begin specialists -->
    <?php echo do_shortcode( '[career-sector-postion2]' ); ?>
    <!-- End specialists -->

    <!-- Begin top_positions -->
    <div class="top_positions">
        <div class="container flex top">
            <div class="top_positions__title">
                <h2>Топ 5 позиций отрасли</h2>
            </div>
            <ul class="top_positions__list">
                <?php echo do_shortcode( '[carrer-sector-barplus]' ); ?>
            </ul>
        </div>
    </div>
    <!-- End top_positions -->

    <!-- Begin single_link -->
    <div class="single_link">
        <div class="container flex middle">
            <img class="single_link__icon" src="<?php echo get_template_directory_uri();?>/assets/img/salary/icon.svg" alt="">
            <div class="single_link__info">
                <h2 class="single_link__title">Зарплатный барометр</h2>
                <p class="single_link__text">Узнайте сколько получают специалисты</p>
                <a class="btn btn--big" href="/calculator/">Перейти в зарплатный барометр</a>
            </div>
        </div>
    </div>
    <!-- End single_link -->

    <div class="container flex middle">
        <?php the_content();  ?>
    </div>

<?php endwhile; ?>
<?php get_footer(); ?>