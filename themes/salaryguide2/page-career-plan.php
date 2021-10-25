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
    <div class="main_sm" style="background-image: url(<?php echo get_template_directory_uri();?>/assets/img/main_sm/bg4.jpg)">
        <div class="container">
        <!--    <ul class="bradcrumbs flex middle">
                <li><a href="/">Главная</a></li>
            </ul> -->
            <div class="main_sm__info">
                <h1 class="main_sm__title">Карьерный план</h1>
                <p class="main_sm__text">Почти каждый второй опрошенный профессионал искал новую работу в 2020 году, а 7% искали возможность подработать. Узнайте больше о возможностях развития своей карьеры.</p>
            </div>
        </div>
    </div>
    <!-- End main_sm -->

    <!-- Begin career -->
    <div class="career">
        <!-- Begin career__form -->
        <div class="career__form">
            <div class="container flex top">
                <!-- Begin career__form_left -->
                <div class="career__form_left">
                    <h2 class="career__form_title">Узнайте как может <span>развиваться ваша&nbsp;карьера</span></h2>
                    <img class="career__form_icon" src="<?php echo get_template_directory_uri();?>/assets/img/career/icon.svg" alt="">
                </div>
                <!-- End career__form_left -->

                <!-- Begin career__form_right -->

                <!-- Begin form -->
                <form class="career__form_right form" action="/" id="form-calculator">
                    <input type="hidden" name="action" value="career_plan_get_widget">
                    <!-- Begin form__row -->
                    <div class="form__row flex top">
                        <label class="form__item form__item--col4">
                            <span class="form__label">Направление <span>*</span></span>
                            <select id="category-plan" class="form__select career-required" name="category">
                                <option value="">...</option>
                                <?php
                                $category = career_plan_get_all_category();
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
                            <select id="job-plan" class="form__select career-required" name="job" disabled>
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
                            <select id="aoe-plan" class="form__select career-required" name="aoe-plan" disabled>
                                <option value="">...</option>
                            </select>
                            <span class="form__error">Поле обязательно к заполнению</span>
                        </label>
                        <label class="form__item form__item--col4">
                            <span class="form__label">Тип компании <span>*</span></span>
                            <select id="type-plan" class="form__select career-required" name="type" disabled>
                                <option value="">...</option>
                            </select>
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

                <!-- End career__form_right -->
            </div>
        </div>
        <!-- End career__form -->
        <!-- Begin career__info -->
        <div class="career__info">
            <div class="career__container container">
                <!-- Begin career__wrap -->
                <div id="career__info-tree" class="career__wrap flex"></div>
                <!-- End career__wrap -->
                <!-- Begin career__nav -->
                <div class="career__nav flex middle">
                    <a class="career__nav_btn btn btn--big" href="//hays.ru/ru/send-resume/">Отправить резюме<span class="icon_arrow_right2"></span></a>
                    <a class="career__nav_btn btn btn--big" href="//hays.ru/career-consultation/">Карьерное консультирование<span class="icon_arrow_right2"></span></a>
                <!--    <p class="career__nav_link"><a class="flex_inline middle" href="#"><span class="icon_download"></span>Скачать карту</a></p> -->
                    <p class="career__nav_link"><a class="flex_inline middle" href="/create-resume/"><span class="icon_resume"></span>Создать резюме</a></p>
                </div>
                <!-- End career__nav -->
            </div>
            <div class="career__mapping">
                <div class="container">
                    <div class="career__mapping_text"><span class="icon_email"></span>Для клиентов: заказать исследование рынка труда под индивидуальный запрос.</div>
                </div>
                <span class="popmake-2079"><a class="career__nav_btn btn btn--big" href="#" style="margin-top: 10px;">Заказать</a></span>
            </div>
        </div>
        <!-- End career__info -->
    </div>
    <!-- End career -->
    <div id="calculator-widget"></div>

    <!-- Begin services -->
    <div class="services">
        <div class="container">
            <h2 class="services__title" style="text-transform: none;">Услуги Hays</h2>
            <!-- Begin services__one -->
            <div class="services__one flex">
                <div class="services__one_info flex top">
                    <span class="services__one_label"></span>
                    <p class="services__one_name"><a href="#">Индивидуальное карьерное консультирование</a></p>
                    <a class="services__one_btn btn btn--big" href="#">Заказать консультацию</a>
                </div>
                <a class="services__one_img" href="#"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img1.jpg" alt=""></a>
            </div>
            <!-- End services__one -->
            <!-- Begin services__tabs -->
            <div class="services__tabs tabs">
                <ul class="tabs__nav tabs__nav--big flex_inline middle">
                    <li class="is_active">Подбор персонала</li>
                    <li>Работа с персоналом</li>
                    <li>Аутсорсинговые услуги</li>
                </ul>
                <div class="tabs__body">
                    <div class="tabs__body_item is_active">
                        <!-- Begin services__list -->
                        <div class="services__list js_slick">
                            <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/podbor/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img2.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Корпоративным клиентам</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/podbor-personala/" target="_blank">Подбор персонала</a></p>
                                    <p class="services__list_excerpt">Услуги по подбору профессионалов на позиции Middle/Top уровня.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/podbor-personala/" target="_blank">Подробнее</a>
                                </div>
                            </div>
                         <!--   <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/junior-mass-recruitment" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img3.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Корпоративным клиентам</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/junior-mass-recruitment" target="_blank">Junior/Mass подбор</a></p>
                                    <p class="services__list_excerpt">Массовый рекрктмент и услуги по подбору персонала на Junior позиции.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/junior-mass-recruitment" target="_blank">Подробнее</a>
                                </div>
                            </div> -->
                            <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/hts-services/autsorsing-rekrutmenta/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img4.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Корпоративным клиентам</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/hts-services/autsorsing-rekrutmenta/" target="_blank">Аутсорсинг рекрутмента</a></p>
                                    <p class="services__list_excerpt">Вид аутсорсинга, при котором компания-клиент пердает процессы посика и подбора персонала.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/hts-services/autsorsing-rekrutmenta/" target="_blank">Подробнее</a>
                                </div>
                            </div>
                            <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/hts-services/implant/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img1.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Корпоративным клиентам</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/hts-services/temp/" target="_blank">Временный персонал</a></p>
                                    <p class="services__list_excerpt">Услуга, в рамках которой мы предоставляем эксперта по поиску и подбору персонала для работы в офисе клиента.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/hts-services/temp/" target="_blank">Подробнее</a>
                                </div>
                            </div>
                        </div>
                        <!-- End services__list -->
                    </div>
                    <div class="tabs__body_item">
                        <!-- Begin services__list -->
                        <div class="services__list js_slick">
                            <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/hays-mapping/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img3.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Работа с персоналом</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/talent-market-mapping/" target="_blank">Исследование рынка</a></p>
                                    <p class="services__list_excerpt">Составление «карты талантов» на основе анализа информации о кандидатах.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/talent-market-mapping/" target="_blank">Подробнее</a>
                                </div>
                            </div>
                            <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/assessment" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img2.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Работа с персоналом</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/hts-services/ldoutsourcing-lnd/" target="_blank">Обучение и развитие персонала</a></p>
                                    <p class="services__list_excerpt">Различные инструменты оценки сотрудников компаний и потенциальных кандидатов в процессе рекрутмента.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/hts-services/ldoutsourcing-lnd/" target="_blank">Подробнее</a>
                                </div>
                            </div>
                            <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/hts-services/ldoutsourcing-lnd/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img1.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Работа с персоналом</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/outplacement-personala/" target="_blank">Аутплейсмент</a></p>
                                    <p class="services__list_excerpt">Проведедение единоразовых тренингов, а также полный аутсорсинг функции обучения компании.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/outplacement-personala/" target="_blank">Подробнее</a>
                                </div>
                            </div>
                        <!--    <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/outplacement/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img4.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Работа с персоналом</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/outplacement/" target="_blank">Аутплейсмент персонала</a></p>
                                    <p class="services__list_excerpt">Услуга по сопровождению и консультированию сотрудников клиента на этапе завершения работы в компании.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/outplacement/" target="_blank">Подробнее</a>
                                </div>
                            </div> -->
                        </div>
                        <!-- End services__list -->
                    </div>
                    <div class="tabs__body_item">
                        <!-- Begin services__list -->
                        <div class="services__list js_slick">
                            <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/hts-services/autsorsing-biznes-processov/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img1.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Аутсорсинговые услуги</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/hts-services/autsorsing-biznes-processov/" target="_blank">Аутсорсинг бизнес-процессов</a></p>
                                    <p class="services__list_excerpt">Выполнение непрофильных офисных задач с ответственностью за результат: подбор персонала, кадровое делопроизводство и т.д.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/hts-services/autsorsing-biznes-processov/" target="_blank">Подробнее</a>
                                </div>
                            </div>
                            <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/hts-services/bpa/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img3.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Аутсорсинговые услуги</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/hts-services/bpa/" target="_blank">Администрирование бизнес-процессов</a></p>
                                    <p class="services__list_excerpt">Вид аутсорсинга, при котором мы берем на себя функции: оформление, выплаты сотрудника и т.д.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/hts-services/bpa/" target="_blank">Подробнее</a>
                                </div>
                            </div>
                            
                        <!--    <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/hts-services/temp/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img4.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Аутсорсинговые услуги</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/hts-services/temp/" target="_blank">Предоставление временного персонала</a></p>
                                    <p class="services__list_excerpt">Услуга по предоставлению сотрудников на краткосрочные и среднесрочные проекты или работы от 1 дня до 9 месяцев.</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/hts-services/temp/" target="_blank">Подробнее</a>
                                </div>
                            </div> -->
                            <div class="services__list_item">
                                <a class="services__list_img" href="https://hays.ru/hays-services/hts-services/msp/" target="_blank"><img src="<?php echo get_template_directory_uri();?>/assets/img/services/img2.jpg" alt=""></a>
                                <div class="services__list_info flex top">
                                    <span class="services__list_label">Аутсорсинговые услуги</span>
                                    <p class="services__list_link"><a href="https://hays.ru/hays-services/hts-services/msp/" target="_blank">Управление поставщиками</a></p>
                                    <p class="services__list_excerpt">Полный цикл работы с поставщиками: подбор, документооборот, заключение договоров, оплата и обработка счетов и т.д.​</p>
                                    <a class="services__list_btn btn btn--big" href="https://hays.ru/hays-services/hts-services/msp/" target="_blank">Подробнее</a>
                                </div>
                            </div>
                        </div>
                        <!-- End services__list -->
                    </div>
                </div>
            </div>
            <!-- End services__tabs -->
            <?php the_content();  ?>

        </div>
    </div>
    <!-- End services -->

<?php endwhile; ?>
<?php get_footer(); ?>