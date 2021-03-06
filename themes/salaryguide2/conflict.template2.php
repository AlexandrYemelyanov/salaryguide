<!-- wp:html -->
<!-- Begin main_xs -->
<!--<div class="main_xs">
    <div class="container flex middle">
        <div class="main_xs__info">
            <h1>Точки конфликтов</h1>
            <p class="main_xs__text">Основные выводы исследования.</p>
        </div>
        <img class="main_xs__icon" src="/wp-content/themes/salaryguide2/assets/img/overview/icon3.svg" alt="">
    </div>
</div> -->
<div class="main_xs">
    <div class="container flex middle">
        <div class="main_xs__info">
          <!--  <ul class="bradcrumbs bradcrumbs--xs flex middle">
                <li><a href="/">Главная</a></li>
                <li><a href="/surveys-infographics/">Обзор рынка труда</a></li>
            </ul> -->
            <h1>Исследование рынка труда</h1>
            <p class="main_xs__text">Исследование Hays Salary Guide 20/21 рассказывает о планах компаний относительно поиска и подбора персонала, методах мотивации и удержания сотрудников, активности профессионалов на рынке, затрагивает вопросы удаленной работы, изменения дохода, найма временного персонала и другие аспекты рынка труда в России.</p>
        </div>
        <img class="main_xs__icon" src="/wp-content/themes/salaryguide2/assets/img/overview/icon3.svg" alt="">
    </div>
</div>
<!-- End main_xs -->

<!-- Begin accordeon -->
<div class="accordeon">
    <?php foreach ($survey as $category => $info): ?>
    <!-- Begin accordeon__info -->
    <div class="accordeon__info">
        <div class="container">
            <h2 class="accordeon__title" style="text-transform: none;"><?php echo $category; ?></h2>
        </div>
        <div class="accordeon__list">
            <?php foreach ($info as $question => $_info): ?>
            <!-- Begin accordeon__item -->
            <div class="accordeon__item">
                <div class="accordeon__header">
                    <div class="container flex middle">
                        <p class="accordeon__header_text"><?php echo $question; ?></p>
                        <span class="accordeon__header_btn btn btn--big">Показать<span class="icon_arrow_double_open"></span></span>

                    </div>
                </div>
                <div class="accordeon__body">
                    <div class="container">
                        <div class="survey-actions">
                            <div class="year-list">
                                <span class="tabs__label" style="font-size: 16px;">Сравнить ответы респондентов:</span>
                                <ul class="tabs__nav tabs__checkbox flex_inline middle year-list"style="font-size: 16px;">
                                    <?php foreach ($years as $year): ?>
                                        <li data-set-year="<?php echo $year; ?>"<?php if ($year == $last_year) {?> class="is_active"<?php }?>><span><?php echo $year; ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="level-mark">
                                <ul>
                                    <li class="levels__line_soi"><span class="levels__width"></span><span class="levels__line_text" style="font-size: 16px;">- профессионалы</span></li>
                                    <li class="levels__line_rab"><span class="levels__width"></span><span class="levels__line_text" style="font-size: 16px;">- работодатели</span></li>
                                </ul>            
                            </div>
                        </div>    
                   
                        <!-- Begin levels_tiny -->
                        <div class="levels_tiny flex">
                            <?php $noempty = false; foreach ($_info as $answer => $__info): ?>
                            <div class="levels_tiny__item flex">
                                <p class="levels_tiny__title"><?php echo $answer; ?></p>
                                <div class="levels_tiny__info">
                                    <?php foreach ($__info as $year => $___info): ?>
                                    <div class="levels_tiny__lines<?php if ($year != $last_year) {?> line__hide<?php }?>" data-year="<?php echo $year; ?>">
                                        <div class="levels-tiny__year">
                                            <?php echo $year; ?>
                                        </div>

                                        <div class="levels-tiny__side">
                                            <?php if ($___info['soi'] > -1) {?>
                                                <div class="levels__line levels__line_soi">
                                                    <div class="levels__width" style="width: <?php echo $___info['soi']; ?>%"> <?php echo $___info['soi']; ?>%</div>
                                                </div>
                                            <?php } else { $noempty = true; ?>
                                            <div class="levels__line levels__line_soi">
                                                <div class="levels__width no-qustion">*</div>
                                            </div>
                                            <?php }?>
                                        </div>

                                        <div class="levels-tiny__side text-right">
                                            <?php if ($___info['rab'] > -1) { ?>
                                                <div class="levels__line levels__line_rab">
                                                    <div class="levels__width" style="width: <?php echo $___info['rab']; ?>%"> <?php echo $___info['rab']; ?>%</div>
                                                </div>
                                            <?php } else { $noempty = true; ?>
                                                <div class="levels__line levels__line_rab">
                                                    <div class="levels__width no-qustion">*</div>
                                                </div>
                                            <?php }?>
                                        </div>

                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php if ($noempty) {  ?>
                                <span class="survey-comment">*В этом году данный вопрос / вариант ответа не предлагался респондентам.</span>
                            <?php }?>
                        </div>
                        <!-- End levels_tiny -->
                    </div>
                </div>
            </div>
            <!-- End accordeon__item -->
            <?php endforeach; ?>

            <!-- End levels_tiny -->
        </div>

            <!-- End accordeon__item -->

    </div>
    <!-- End accordeon__info -->
    <?php endforeach; ?>
</div>
<!-- End accordeon -->
<!-- /wp:html -->