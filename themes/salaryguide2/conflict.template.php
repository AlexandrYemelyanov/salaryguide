<!-- wp:html -->
<!-- Begin main_xs -->
<div class="main_xs">
    <div class="container flex middle">
        <div class="main_xs__info">
            <ul class="bradcrumbs bradcrumbs--xs flex middle">
                <li><a href="/">Главная</a></li>
                <li><a href="/surveys-infographics/">Обзор рынка труда</a></li>
            </ul>
            <h1>Точки конфликтов</h1>
            <p class="main_xs__text">Основные выводы исследования</p>
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
            <h2 class="accordeon__title"><?php echo $category; ?></h2>
        </div>
        <div class="accordeon__list">
            <?php foreach ($info as $question => $_info): ?>
            <!-- Begin accordeon__item -->
            <div class="accordeon__item">
                <div class="accordeon__header">
                    <div class="container flex middle">
                        <p class="accordeon__header_text"><?php echo $question; ?></p>
                        <span class="accordeon__header_btn btn btn--big btn--compare">Сравнить</span>
                        <span class="accordeon__header_btn btn btn--big">Показать<span class="icon_arrow_double_open"></span></span>
                        <span class="accordeon__header_text m-year">&nbsp;</span><span class="accordeon__header_years">
                            <ul class="year-list">
                                <?php foreach ($years as $year): ?>
                            <li data-set-year="<?php echo $year; ?>"<?php if ($year == $last_year) {?> class="is_active"<?php }?>><span><?php echo $year; ?></span></li>
                                <?php endforeach; ?>
                            </ul>
                        </span>
                    </div>
                </div>
                <div class="accordeon__body">
                    <div class="container">
                        <!-- Begin levels_tiny -->
                        <div class="levels_tiny flex">
                            <?php foreach ($_info as $answer => $__info): ?>
                            <div class="levels_tiny__item flex">
                                <p class="levels_tiny__title"><?php echo $answer; ?></p>
                                <div class="levels_tiny__info">
                                    <?php foreach ($__info as $year => $___info): ?>
                                    <div class="levels_tiny__lines<?php if ($year != $last_year) {?> line__hide<?php }?>" data-year="<?php echo $year; ?>">
                                        <div class="levels-tiny__year">
                                            <?php echo $year; ?>
                                        </div>

                                        <div class="levels-tiny__side levels_tiny__info--pink text-right">
                                            <?php if ($___info['soi'] > -1) {?>
                                            <span class="levels_tiny__label"><span>Профессионалы</span> <?php echo $___info['soi']; ?>%</span>
                                            <div class="levels_tiny__line line-right">
                                                <div class="levels_tiny__width" style="width: <?php echo $___info['soi']; ?>%"></div>
                                            </div>
                                            <?php } else {?>
                                            <span class="levels_tiny__label"><span>Профессионалы</span></span>
                                            <div class="levels_tiny__line">
                                                <div class="levels_tiny__no-question">вопрос не задавался</div>
                                            </div>
                                            <?php }?>
                                        </div>

                                        <div class="levels-tiny__side">
                                            <?php if ($___info['rab'] > -1) {?>
                                                <span class="levels_tiny__label"><span>Работадатель</span> <?php echo $___info['rab']; ?>%</span>
                                                <div class="levels_tiny__line">
                                                    <div class="levels_tiny__width" style="width: <?php echo $___info['rab']; ?>%"></div>
                                                </div>
                                            <?php } else {?>
                                                <span class="levels_tiny__label"><span>Работадатель</span></span>
                                                <div class="levels_tiny__line">
                                                    <div class="levels_tiny__no-question">вопрос не задавался</div>
                                                </div>
                                            <?php }?>
                                        </div>

                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>

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