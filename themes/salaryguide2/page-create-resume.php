<?php
/**
 * The page.
 *
 * @package WordPress
 * @subpackage Salary Guide Hays 2
 * @since Salary Guide 2.0
 */

/*get_header('resume'); ?>*/
get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <!-- Begin resume -->
    <div class="resume">
        <div class="container">
            <!-- Begin resume__steps -->
            <ul class="resume__steps flex_inline middle">
                <li class="is_active"><span>1</span>О вас</li>
                <li><span>2</span>Образование</li>
                <li><span>3</span>Опыт работы</li>
                <li><span>4</span>Курсы</li>
                <li><span>5</span>Языки</li>
                <li><span>6</span>Навыки</li>
                <li><span>7</span>Другое</li>
                <li><span class="icon_download"></span>Скачать</li>
            </ul>
            <!-- End resume__steps -->
            <!-- Begin resume__form -->
            <form class="resume__form form" action="<?php echo admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="career_create_resume">
                <!-- Begin resume__list -->
                <div class="resume__list js_slick">
                    <div class="resume__item">
                        <!-- Begin resume__header -->
                        <div class="resume__header flex middle between">
                            <p class="resume__title">Базовая информация</p>
                            <span class="resume__step">Шаг 1</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <div class="form__base flex top between">
                                <!-- Begin form__base_info -->
                                <div class="form__base_info">
                                    <!-- Begin form__row -->
                                    <div class="form__row">
                                        <label class="form__item">
                                            <span class="form__label">Фамилия <span>*</span></span>
                                            <input class="form__input is_required" type="text" name="surname">
                                            <span class="form__error">Поле заполнено некорректно</span>
                                        </label>
                                    </div>
                                    <!-- End form__row -->
                                    <!-- Begin form__row -->
                                    <div class="form__row flex top">
                                        <label class="form__item form__item--col4">
                                            <span class="form__label">Имя <span>*</span></span>
                                            <input class="form__input is_required" type="text" name="name">
                                            <span class="form__error">Поле заполнено некорректно</span>
                                        </label>
                                        <label class="form__item form__item--col4">
                                            <span class="form__label">Отчество</span>
                                            <input class="form__input" type="text" name="middle_name">
                                            <span class="form__error">Поле заполнено некорректно</span>
                                        </label>
                                    </div>
                                    <!-- End form__row -->
                                    <!-- Begin form__row -->
                                    <div class="form__row flex top">
                                        <label class="form__item form__item--col4">
                                            <span class="form__label">Эл.почта <span>*</span></span>
                                            <input class="form__input is_required" type="text" name="email">
                                            <span class="form__error">Поле заполнено некорректно</span>
                                        </label>
                                        <label class="form__item form__item--col4">
                                            <span class="form__label">Номер телефона <span>*</span></span>
                                            <input class="form__input is_required" autocomplete="off" type="text" name="phone" data-mask="+7 (000) 000-00-00" data-mask-clearifnotmatch="true" placeholder="+7 (___) ___-__-__">
                                            <span class="form__error">Поле заполнено некорректно</span>
                                        </label>
                                    </div>
                                    <!-- End form__row -->
                                    <!-- Begin form__row -->
                                    <div class="form__row flex top">
                                        <label class="form__item form__item--col4">
                                            <span class="form__label">Город <span>*</span></span>
                                            <input class="form__input is_required" type="text" name="city">
                                            <span class="form__error">Поле заполнено некорректно</span>
                                        </label>
                                        <label class="form__item form__item--col4">
                                            <span class="form__label">Дата рождения <span>*</span></span>
                                            <input class="form__input is_required" type="text" name="birth" data-mask="00/00/0000" data-mask-clearifnotmatch="true" placeholder="дд/мм/гггг">
                                            <span class="form__error">Поле заполнено некорректно</span>
                                        </label>
                                    </div>
                                    <!-- End form__row -->
                                </div>
                                <!-- End form__base_info -->
                                <!-- Begin form__base_foto -->
                                <div class="form__base_foto">
                                    <!-- Begin form__row -->
                                    <div class="form__row">
                                        <label class="form__item form__item--file">
                                            <span class="form__label">Ваше фото</span>
                                            <input class="form__file" type="file" name="foto" accept="image/*">
                                            <div class="form__preview flex middle center"><img src="<?php echo get_template_directory_uri();?>/assets/img/resume/preview.svg" alt="Preview"></div>
                                            <span class="form__upload flex_inline middle">Загрузить<span class="icon_upload"></span></span>
                                            <span class="form__error">Фото не выбрано</span>
                                        </label>
                                    </div>
                                    <!-- End form__row -->
                                </div>
                                <!-- End form__base_foto -->
                            </div>
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Желаемая должность <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="position">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>

                            <!-- End form__row -->
                            <!-- Begin form__row -->
                         <div class="form__row">
                              <label class="form__item">
                                <?php

                                $industry_str = file_get_contents(TEMPLATEPATH.'/industry.txt');
                                $industry = explode("\n", $industry_str);
                                ?>

                                <div class="wj-form__field">
                                    <label class="form__label" for="industry">Индустрия <span>*</span></label>
                                    <select name="industry" id="industry" class="form__input is_required">
                                        <option value="">Выберите индустрию</option>
                                        <?php foreach ($industry as $i=>$item) : ?>
                                            <option value="<?php echo $item; ?>"><?php echo $item; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="form__error">Выберите индустрию</span>
                                </div>
                              </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Пару слов о себе</span>
                                    <textarea class="form__textarea" name="about"></textarea>
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                    </div>
                    <div class="resume__item">
                        <!-- Begin resume__header -->
                        <div class="resume__header flex middle between">
                            <p class="resume__title">Образование</p>
                            <span class="resume__step">Шаг 2</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Уровень <span>*</span></span>
                                    <select name="academic_degree[]">
                                        <option value="Среднее">Среднее</option>
                                        <option value="Среднее специальное">Среднее специальное</option>
                                        <option value="Неоконченное высшее">Неоконченное высшее</option>
                                        <option value="Высшее">Высшее</option>
                                        <option value="Бакалавр">Бакалавр</option>
                                        <option value="Магистр">Магистр</option>
                                        <option value="Кандидат наук">Кандидат наук</option>
                                        <option value="Доктор наук">Доктор наук</option>
                                    </select>
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Название учебного заведения <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="university[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item form__item--col2">
                                    <span class="form__label">Год окончания <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="year_ending[]" data-mask="0000" data-mask-clearifnotmatch="true" placeholder="гггг">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col3">
                                    <span class="form__label">Факультет <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="faculty[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col3">
                                    <span class="form__label">Специализация <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="specialization[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                        <!-- Begin resume__add -->
                        <div class="resume__add">
                            <a class="resume__add_btn flex_inline middle" href="#"><span class="icon_plus"></span>Добавить учебное заведение</a>
                        </div>
                        <!-- End resume__add -->
                    </div>
                    <div class="resume__item">
                        <!-- Begin resume__header -->
                        <div class="resume__header flex middle between">
                            <p class="resume__title">Опыть работы</p>
                            <span class="resume__step">Шаг 3</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__remove -->
                        <div class="resume__remove">
                            <label class="form__checkbox flex_inline middle">
                                <input class="form__checkbox_input" type="checkbox" name="no_experience" value="Нет опыта">
                                <span class="form__checkbox_check icon_check"></span>
                                <span>Нет опыта</span>
                            </label>
                        </div>
                        <!-- End resume__remove -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Название компании <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="company[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item">
                                    <span class="form__label">Должность <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="company_position[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item form__item--col2">
                                    <span class="form__label">Начало работы <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="started[]" data-mask="00/0000" data-mask-clearifnotmatch="true" placeholder="мм/гггг">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col2">
                                    <span class="form__label">Окончание работы <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="ending[]" data-mask="00/0000" data-mask-clearifnotmatch="true" placeholder="мм/гггг">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <div class="form__item form__item--col4 form__item--checkbox">
                                    <label class="form__checkbox flex_inline middle">
                                        <input class="form__checkbox_input" type="checkbox" name="until_now[]" value="По настоящее время">
                                        <span class="form__checkbox_check icon_check"></span>
                                        <span>По настоящее время</span>
                                    </label>
                                </div>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Опишите свои обязанности и достижения</span>
                                    <textarea class="form__textarea" name="responsibilities[]"></textarea>
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                        <!-- Begin resume__add -->
                        <div class="resume__add">
                            <a class="resume__add_btn flex_inline middle" href="#"><span class="icon_plus"></span>Добавить место работы</a>
                        </div>
                        <!-- End resume__add -->
                    </div>
                    <div class="resume__item">
                        <!-- Begin resume__header -->
                        <div class="resume__header flex middle between">
                            <p class="resume__title">Дополнительные курсы</p>
                            <span class="resume__step">Шаг 4</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Где проходили обучение?</span>
                                    <input class="form__input" type="text" name="school_courses[]" placeholder="Например, Яндекс Практикум или SkillBox...">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item form__item--col7">
                                    <span class="form__label">Название курса</span>
                                    <input class="form__input" type="text" name="course_name[]" placeholder="Например, менеджмент проектов или продуктовый дизайн">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col1">
                                    <span class="form__label">Год</span>
                                    <input class="form__input" type="text" name="course_year[]" data-mask="0000" data-mask-clearifnotmatch="true" placeholder="гггг">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                        <!-- Begin resume__add -->
                        <div class="resume__add">
                            <a class="resume__add_btn flex_inline middle" href="#"><span class="icon_plus"></span>Добавить курс</a>
                        </div>
                        <!-- End resume__add -->
                    </div>
                    <div class="resume__item">
                        <!-- Begin resume__header -->
                        <div class="resume__header flex middle between">
                            <p class="resume__title">Языки</p>
                            <span class="resume__step">Шаг 5</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item form__item--col4">
                                    <span class="form__label">Язык</span>
                                    <input class="form__input" type="text" name="language[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col4">
                                    <span class="form__label">Уровень владения</span>
                                    <select name="language_level[]">
                                        <option value="А1 - начальный">А1 - начальный</option>
                                        <option value="А2 - Элементарный">А2 - Элементарный</option>
                                        <option value="В1 - Средний">В1 - Средний</option>
                                        <option value="В2 - Средний продвинутый">В2 - Средний продвинутый</option>
                                        <option value="С1 - Продвинутый">С1 - Продвинутый</option>
                                        <option value="С2 - Свободное владение">С2 - Свободное владение</option>
                                    </select>
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                        <!-- Begin resume__add -->
                        <div class="resume__add">
                            <a class="resume__add_btn flex_inline middle" href="#"><span class="icon_plus"></span>Добавить язык</a>
                        </div>
                        <!-- End resume__add -->
                    </div>
                    <div class="resume__item">
                        <!-- Begin resume__header -->
                        <div class="resume__header flex middle between">
                            <p class="resume__title">Профессиональные навыки</p>
                            <span class="resume__step">Шаг 6</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Перечислите свои ключевые навыки, которые считаете важными в своей работе</span>
                                    <textarea class="form__textarea form__textarea--big" name="skill[]" placeholder="Например, копирайтинг, Photoshop, коммуникабельность, критическое мышление..."></textarea>
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                        <!-- Begin resume__add -->
                        <div class="resume__add">
                            <a class="resume__add_btn flex_inline middle" href="#"><span class="icon_plus"></span>Добавить навык</a>
                        </div>
                        <!-- End resume__add -->
                    </div>
                    <div class="resume__item">
                        <!-- Begin resume__header -->
                        <div class="resume__header flex middle between">
                            <p class="resume__title">Дополнительная информация</p>
                            <span class="resume__step">Шаг 7</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Здесь можно написать о себе всё, что не вошло в предыдущие разделы, но важно знать о вас</span>
                                    <textarea class="form__textarea form__textarea--big" name="covering_letter" placeholder="Опишите свои особенности и необычные навыки, которые будут полезны потенциальному работодателю."></textarea>
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                    </div>
                    <div class="resume__item">
                        <!-- Begin resume__header -->
                        <div class="resume__header flex middle between">
                            <p class="resume__title">Ваше резюме создано!</p>
                            <span class="resume__step">Готово</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <span class="form__label">Выберите дизайн своего резюме</span>
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <div class="form__item form__item--resume">
                                    <label class="form__checkbox form__checkbox--resume">
                                        <input class="form__checkbox_input" type="radio" name="resume" value="1">
                                        <span class="form__checkbox_check icon_check"></span>
                                        <img class="form__checkbox_img" src="<?php echo get_template_directory_uri();?>/assets/img/resume/resume1.jpg" alt="resume1">
                                    </label>
                                </div>
                                <div class="form__item form__item--resume">
                                    <label class="form__checkbox form__checkbox--resume">
                                        <input class="form__checkbox_input" type="radio" name="resume" value="2">
                                        <span class="form__checkbox_check icon_check"></span>
                                        <img class="form__checkbox_img" src="<?php echo get_template_directory_uri();?>/assets/img/resume/resume2.jpg" alt="resume2">
                                    </label>
                                </div>
                                <div class="form__item form__item--resume">
                                    <label class="form__checkbox form__checkbox--resume">
                                        <input class="form__checkbox_input" type="radio" name="resume" value="3">
                                        <span class="form__checkbox_check icon_check"></span>
                                        <img class="form__checkbox_img" src="<?php echo get_template_directory_uri();?>/assets/img/resume/resume3.jpg" alt="resume3">
                                    </label>
                                </div>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                    </div>
                </div>
                <!-- End resume__list -->
                <!-- Begin resume__nav -->
                <div class="resume__nav flex middle">
                    <div class="resume__back"><a class="btn btn--big btn--white" href="#">Назад</a></div>
                    <div class="resume__next"><a class="btn btn--big" href="#">Далее</a></div>
                    <div class="resume__submit"><button class="btn btn--big send-resume" type="button" data-action="career_create_resume">Скачать резюме</button></div>
                    <div class="resume__submit"><button class="btn btn--big send-resume" type="button" data-action="career_send_resume">Отправить резюме <span class="icon_arrow_right2"></span></button>
                    </div>
                </div>
                <!-- End resume__nav -->
            </form>
            <!-- End resume__form -->
        </div>
    </div>
    <!-- End resume -->

    <div class="container flex middle">
        <?php the_content();  ?>
    </div>

<?php endwhile; ?>
<?php get_footer('resume'); ?>
