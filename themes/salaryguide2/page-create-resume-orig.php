<?php
/**
 * The page.
 *
 * @package WordPress
 * @subpackage Salary Guide Hays 2
 * @since Salary Guide 2.0
 */

get_header('resume'); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <!-- Begin resume -->
    <div class="resume">
        <div class="container">
            <!-- Begin resume__steps -->
            <ul class="resume__steps flex_inline middle">
                <li class="is_active"><span>1</span>О вас</li>
                <li><span>2</span>Образование</li>
                <li><span>3</span>Опыт работы</li>
                <li><span>4</span>Достижения</li>
                <li><span>5</span>Курсы</li>
                <li><span>6</span>Языки</li>
                <li><span>7</span>Навыки</li>
                <li><span>8</span>Письмо</li>
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
                                            <input class="form__input is_required" type="text" name="phone" data-mask="+7 (000) 000-00-00" data-mask-clearifnotmatch="true" placeholder="+7 (___) ___-__-__">
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
                                            <span class="form__label">Ваше фото <span>*</span></span>
                                            <input class="form__file is_required" type="file" name="foto" accept="image/*">
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
                                    <span class="form__label">Название университета <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="university[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item form__item--col2">
                                    <span class="form__label">Год поступления <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="year_entry[]" data-mask="0000" data-mask-clearifnotmatch="true" placeholder="____">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col2">
                                    <span class="form__label">Год окончания <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="year_ending[]" data-mask="0000" data-mask-clearifnotmatch="true" placeholder="____">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col4">
                                    <span class="form__label">Академическая степень <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="academic_degree[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item form__item--col4">
                                    <span class="form__label">Факультет <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="faculty[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col3">
                                    <span class="form__label">Специализация <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="specialization[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col1">
                                    <span class="form__label">Ср.балл <span>*</span></span>
                                    <input class="form__input is_required" type="text" name="gpa[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                        <!-- Begin resume__add -->
                        <div class="resume__add">
                            <a class="resume__add_btn flex_inline middle" href="#"><span class="icon_plus"></span>Добавить университет</a>
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
                                <label class="form__item form__item--col4">
                                    <span class="form__label">Должность</span>
                                    <input class="form__input" type="text" name="company_position[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col4">
                                    <span class="form__label">Отдел</span>
                                    <input class="form__input" type="text" name="department[]">
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
                                    <span class="form__label">Опишите ваши обязанности и достижений</span>
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
                            <p class="resume__title">Достижения</p>
                            <span class="resume__step">Шаг 4</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item form__item--col7">
                                    <span class="form__label">Название нагарды / мероприятия / достижения</span>
                                    <input class="form__input" type="text" name="awards[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col1">
                                    <span class="form__label">Год</span>
                                    <input class="form__input" type="text" name="awards_year[]" data-mask="0000" data-mask-clearifnotmatch="true" placeholder="____">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Опишите ваши обязанности и достижений</span>
                                    <textarea class="form__textarea" name="awards_responsibilities[]"></textarea>
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                        </div>
                        <!-- End resume__body -->
                        <!-- Begin resume__add -->
                        <div class="resume__add">
                            <a class="resume__add_btn flex_inline middle" href="#"><span class="icon_plus"></span>Добавить достижение</a>
                        </div>
                        <!-- End resume__add -->
                    </div>
                    <div class="resume__item">
                        <!-- Begin resume__header -->
                        <div class="resume__header flex middle between">
                            <p class="resume__title">Курсы</p>
                            <span class="resume__step">Шаг 5</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Название школы / площадки</span>
                                    <input class="form__input" type="text" name="school_courses[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item form__item--col7">
                                    <span class="form__label">Название курса</span>
                                    <input class="form__input" type="text" name="course_name[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col1">
                                    <span class="form__label">Год</span>
                                    <input class="form__input" type="text" name="course_year[]" data-mask="0000" data-mask-clearifnotmatch="true" placeholder="____">
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
                            <span class="resume__step">Шаг 6</span>
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
                                    <input class="form__input" type="text" name="language_level[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Описание</span>
                                    <textarea class="form__textarea" name="language_description[]"></textarea>
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
                            <span class="resume__step">Шаг 7</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row flex top">
                                <label class="form__item form__item--col4">
                                    <span class="form__label">Навык</span>
                                    <input class="form__input" type="text" name="skill[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                                <label class="form__item form__item--col4">
                                    <span class="form__label">Уровень владения</span>
                                    <input class="form__input" type="text" name="skill_level[]">
                                    <span class="form__error">Поле заполнено некорректно</span>
                                </label>
                            </div>
                            <!-- End form__row -->
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Описание</span>
                                    <textarea class="form__textarea" name="skill_description[]"></textarea>
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
                            <p class="resume__title">Сопроводительное письмо</p>
                            <span class="resume__step">Шаг 8</span>
                        </div>
                        <!-- End resume__header -->
                        <!-- Begin resume__body -->
                        <div class="resume__body">
                            <!-- Begin form__row -->
                            <div class="form__row">
                                <label class="form__item">
                                    <span class="form__label">Текст сопроводительного письма</span>
                                    <textarea class="form__textarea form__textarea--big" name="covering_letter"></textarea>
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
                            <span class="form__label">Выберите дизайн вашего резюме</span>
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
                    <div class="resume__submit"><button class="btn btn--big" type="submit">Скачать резюме</button></div>
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