
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
                            <span class="accordeon__header_btn btn btn--big">Показать<span class="icon_arrow_double_open"></span></span>

                        </div>
                    </div>
                    <div class="accordeon__body">
                        <div class="container">
                            <!-- Begin tabs -->
                            <div class="tabs">
                                <span class="tabs__label">Год опроса:</span>
                                <ul class="tabs__nav tabs__checkbox flex_inline middle">
                                <?php
                                    $year_per = current($_info);
                                    $set_active = false;
                                    foreach ($year_per as $year => $per):
                                ?>
                                    <li<?php if (!$set_active){ $set_active = true; echo ' class="is_active"';} ?>><?php echo $year; ?></li>
                                <?php endforeach; ?>
                                </ul>
                                <div class="tabs__body">
                                    <div class="tabs__body_item is_active">
                                        <!-- Begin levels -->
                                        <div class="levels">
                                            <div class="levels__list">
                                                <?php foreach ($_info as $answer => $__info): ?>
                                                <div class="levels__item">
                                                    <span class="levels__label"><?php echo $answer; ?></span>
                                                    <?php
                                                    $is_first = true;
                                                    foreach ($__info as $year => $percent):
                                                        if (!$is_first) {
                                                            echo '<span class="levels__label hidden" data-year="'.$year.'"></span>';
                                                        }
                                                    ?>
                                                    <div class="levels__line<?php if (!$is_first){ echo ' hidden';} ?>" data-year="<?php echo $year; ?>">
                                                        <?php
                                                        if (empty($percent)) {
                                                            echo '<div class="levels__width no-question">'.$year.' (В этом году вопрос не задавался)</div>';
                                                        } else {
                                                            echo '<div class="levels__width" style="width: '.$percent.'%">'.$year.' ('.$percent.'%)</div>';
                                                        }
                                                        $is_first = false;
                                                        ?>
                                                    </div>
                                                    <?php endforeach; ?>

                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <!-- End levels -->

                                    </div>
                                </div>
                            </div>
                            <!-- End tabs -->

                        </div>
                    </div>
                </div>
                <!-- End accordeon__item -->
                <?php endforeach; ?>
            </div>
        </div>
        <!-- End accordeon__info -->
        <?php endforeach; ?>

    </div>
    <!-- End accordeon -->