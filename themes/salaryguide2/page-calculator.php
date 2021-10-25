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
    <div class="main_sm" style="background-image: url(<?php echo get_template_directory_uri();?>/assets/img/main_sm/bg1.jpg)">
        <div class="container">
        <!--    <ul class="bradcrumbs flex middle">
                <li><a href="#">Главная</a></li>
                <li>Зарплатный барометр</li>
            </ul> -->
            <div class="main_sm__info">
                <h1 class="main_sm__title">Зарплатный барометр</h1>
                <p class="main_sm__text">Хотите узнать, как увеличить свою зарплату, получить большую зону ответственности или сменить работу? Наши эксперты предоставляют услугу индивидуального карьерного консультирования.</p>
            </div>
        </div>
    </div>
    <!-- End main_sm -->

    <!-- Begin salary -->
    <div class="salary">
        <div class="container flex top">
            <!-- Begin salary__left -->
            <div class="salary__left">
                <h2 class="salary__title">Узнайте сколько <span>получают специалисты</span></h2>
                <img class="salary__icon" src="<?php echo get_template_directory_uri();?>/assets/img/salary/icon.svg" alt="">
            </div>
            <!-- End salary__left -->
            <!-- Begin salary__right -->
            <div class="salary__right">
                <p class="salary__text">Заполните поля ниже и узнайте, сколько получают специалисты разных сфер. Наш барометр покажет максимальную, минимальную и среднюю заработную плату в отрасли</p>
                <!-- Begin form -->
                <form class="salary__form form" action="/" id="form-calculator">
                    <input type="hidden" name="action" value="career_calculator_get_json">
                    <!-- Begin form__row -->
                    <div class="form__row flex top">
                        <label class="form__item form__item--col4">
                            <span class="form__label">Направление <span>*</span></span>
                            <select id="category" class="form__select career-required" name="category">
                                <option value="">...</option>
                                <?php
                                $category = career_get_all_category();
                                if(count($category)): ?>
                                    <?php foreach($category as $id=>$name): ?>
                                        <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <span class="form__error">Поле обязательно к заполнению</span>
                        </label>
                        <label class="form__item form__item--col4">
                            <span class="form__label">Название позиции <span>*</span></span>
                            <select id="job" class="form__select career-required" name="job" disabled>
                                <option value="">...</option>
                            </select>
                            <span class="form__error">Поле обязательно к заполнению</span>
                        </label>
                    </div>
                    <!-- End form__row -->
                    <!-- Begin form__row -->
                    <div class="form__row flex top">
                        <label class="form__item form__item--col4">
                            <span class="form__label">Отрасль <span>*</span></span>
                            <select id="aoe" class="form__select career-required" name="aoe" disabled>
                                <option value="">...</option>
                            </select>
                            <span class="form__error">Поле обязательно к заполнению</span>
                        </label>
                        <label class="form__item form__item--col4">
                            <span class="form__label">Тип компании <span>*</span></span>
                            <select id="type" class="form__select career-required" name="type" disabled>
                                <option value="">...</option>
                            </select>
                            <span class="form__error">Поле обязательно к заполнению</span>
                        </label>
                    </div>
                    <!-- End form__row -->
                    <!-- Begin form__row -->
                    <div class="form__row flex top">
                        <label class="form__item form__item--col4">
                            <span class="form__label">Регион <span>*</span></span>
                            <select id="region" class="form__select career-required" name="region" disabled>
                                <option value="">...</option>
                            </select>
                            <span class="form__error">Поле обязательно к заполнению</span>
                        </label>
                        <label class="form__item form__item--col4">
                            <span class="form__label">Текущая зарплата, руб. без учета бонусов <span>*</span></span>
                            <input id="salary" class="form__input career-required" type="text" name="salary" disabled>
                            <span class="form__error">Поле обязательно к заполнению</span>
                        </label>
                    </div>
                    <!-- End form__row -->
                    <!-- Begin form__row -->
                    <div class="form__row flex top">
                        <div class="form__item form__item--submit">
                            <button id="calculator-go" class="form__btn btn btn--big" type="button">Проверить результат</button>
                        </div>
                    </div>
                    <!-- End form__row -->
                </form>
                <!-- End form -->
            </div>
            <!-- End salary__right -->
            <!-- Begin salary__info -->
            <div class="salary__info">
                <div class="flex top">
                    <!-- Begin salary__info_left -->
                    <div class="salary__info_left levels">
                        <div class="levels__list">
                            <div class="levels__item" id="lev__salary">
                                <span class="levels__label"><span>Ваша</span> <span class="lev__value">100К</span></span>
                                <div class="levels__line">
                                    <div class="levels__width levels__width--white" style="width: 10%"></div>
                                </div>
                            </div>
                            <div class="levels__item" id="lev__min">
                                <span class="levels__label"><span>Минимальная</span> <span class="lev__value">200К</span></span>
                                <div class="levels__line">
                                    <div class="levels__width" style="width: 63%"></div>
                                </div>
                            </div>
                            <div class="levels__item" id="lev__middle">
                                <span class="levels__label"><span>Средняя</span> <span class="lev__value">230К</span></span>
                                <div class="levels__line">
                                    <div class="levels__width" style="width: 78%"></div>
                                </div>
                            </div>
                            <div class="levels__item" id="lev__max">
                                <span class="levels__label"><span>Максимальная</span> <span class="lev__value">250К</span></span>
                                <div class="levels__line">
                                    <div class="levels__width" style="width: 89%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="levels__range flex middle between">
                            <span id="lev__begin">80К</span>
                            <span id="lev__average">180К</span>
                            <span id="lev__finish">270К</span>
                        </div>
                       <!-- <p class="levels__download"><a id="sgdownload" class="flex_inline middle" href="#"><span class="icon_download"></span>Скачать уровень зарплат</a></p> -->

                        <div class="elementor-element elementor-element-33e3763 elementor-align-center sgdownload elementor-widget elementor-widget-button pum-trigger" data-id="33e3763" data-element_type="widget" id="sgdownload" data-widget_type="button.default" style="cursor: pointer;">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper">
                                    <a class="elementor-button elementor-size-sm" role="button" id="calculator-write-us">
						<span class="elementor-button-content-wrapper">
					<!--	<span class="elementor-button-text">Скачать Salary Guide</span> -->
                   <!-- <span class="popmake-814"><p class="salary__info_btn" style="margin-top: 10px;"><a class="btn btn--big" href="">Скачать Salary Guide<span class="icon_arrow_right2"></span></a></p></span>-->
		</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End salary__info_left -->
                    <!-- Begin salary__info_right -->
                    <div class="salary__info_right">
                        <p class="salary__info_text">Хотите узнать, как увеличить свою зарплату? <br>Наши специалисты предоставляют услугу индивидуального карьерного консультирования.<br>Обращайтесь, мы раскажем как поднять свою зарплату.</p>
                        <p class="salary__info_btn"><a class="btn btn--big" href="https://hays.ru/career-
consultation/">Заказать консультацию<span class="icon_arrow_right2"></span></a></p>
                        <p class="salary__info_btn"><a class="btn btn--big" href="https://hays.ru/send-resume">Отправить резюме<span class="icon_arrow_right2"></span></a></p>
                        <!--<a class="salary__info_link flex_inline middle" href="#"><span class="icon_email"></span>CTA для клиентов</a>-->
                    </div>
                    <!-- End salary__info_right -->
                </div>
            </div>
            <!-- End salary__info -->
            <?php the_content();  ?>

        </div>
    </div>
    <!-- End salary -->
    <div class="career__mapping">
                <div class="container">
                    <div class="career__mapping_text"><span class="icon_email"></span>Для компаний: заказать подбор персонала</div>
                </div>
                <span class="popmake-2088"><a class="career__nav_btn btn btn--big" href="#" style="margin-top: 10px;">Заказать</a></span>
            </div>

<?php endwhile; ?>
<?php get_footer(); ?>