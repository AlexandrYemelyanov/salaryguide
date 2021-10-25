<?php
function career_sector_page()
{
    global $wpdb;

    $aoe = $wpdb->get_results("SELECT `id`,`name` FROM `wp_career_aoe`");
    $cnt_aoe = count($aoe);
    $_compempl = $wpdb->get_results("SELECT * FROM `wp_career_sector_info`");
    $compempl = [];
    foreach ($_compempl as $item) {
        $compempl[$item->sector_id] = $item;
    }

    echo '<link rel="stylesheet" href="' . CAREER_URL . 'css/style.admin.css" type="text/css" />' . PHP_EOL;
    ob_start();
    ?>
    <style>
        [type="file"] {
            display: none;
        }

        .error, .success {
            color: red;
            font-weight: bold;
            margin: 15px 0 !important;
            padding: 15px !important;
        }

        .success {
            color: green;
            background: #fff;
            border: 1px solid #ccd0d4;
            border-left-width: 4px;
            border-left-color: green;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        }

        .container.aoe {
            width: 100%;
        }

        .container.aoe .row {
            display: flex;
            justify-content: center;
            align-items: center;
            align-content: center;
            flex-wrap: wrap;
            padding: 20px;
        }

        .row .item {
            padding: 15px;
            position: relative;
        }

        .row .item label {

        }

        .row .item input, .row .item textarea {
            width: 100%;
        }

        .row .item textarea {
            height: 100%;
        }

        .text-bold {
            font-weight: bold;
        }

        #wpfooter {
            display: none;
        }

        .col-1 {
            width: 10% !important;
        }

        .col-2 {
            width: 20% !important;
        }

        .col-3 {
            width: 30% !important;
            height: 130px;
        }

        .col-50 {
            width: 50% !important;
            height: 260px;
        }
    </style>
    <link rel="stylesheet" href="<?= CAREER_URL; ?>css/accordion.css" type="text/css" media="all">

    <div class="wrap">
        <h1 class="wp-heading-inline">Информация о секторах</h1>
        <?php if (!empty($_SESSION['career']['error'])): ?>
            <div class="error"><?= $_SESSION['career']['error']; ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['career']['success'])): ?>
            <div class="success"><?= $_SESSION['career']['success']; ?></div>
        <?php endif; ?>

        <p>
            Для обозначения ссылок в разделах Позиции и Барометр +, необходимо использовать символ |<br>
            Например: Дизайнер|htps://hays.ru/jobs/1090554/<br>
            Если в строке не указан символ | , то в разделе Позиции, такая строка будет считаться названием подраздела
        </p>

        <div class="container aoe">
            <? if ($cnt_aoe): ?>
                <form class="tablenav" action="<?= admin_url('admin-post.php'); ?>" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="action" value="career_sector_info_save">
                    <div id="accordion">
                        <? foreach ($aoe as $item): ?>
                            <h3><a><?= $item->name; ?></a><input type="hidden" name="sector[]"
                                                                 value="<?= $item->id; ?>"></h3>
                            <div class="row">
                                <div class="item col-50">
                                    <label for="pos-<?= $item->id; ?>">Позиции</label>
                                    <textarea name="positions[<?= $item->id; ?>]" id="pos-<?= $item->id; ?>"
                                              class="career-editor"><?= $compempl[$item->id]->positions; ?></textarea>
                                </div>
                                <div class="item col-50">
                                    <label for="ten-<?= $item->id; ?>">Основные тенденции отрасли</label>
                                    <textarea name="tendencies[<?= $item->id; ?>]" id="ten-<?= $item->id; ?>"
                                              class="career-editor"><?= $compempl[$item->id]->tendencies; ?></textarea>
                                </div>
                                <div class="item col-50">
                                    <label for="bal-<?= $item->id; ?>">Баланс</label>
                                    <textarea name="balance[<?= $item->id; ?>]" id="bal-<?= $item->id; ?>"
                                              class="career-editor"><?= $compempl[$item->id]->balance; ?></textarea>
                                </div>
                                <div class="item col-50">
                                    <label for="barp-<?= $item->id; ?>">Барометр +</label>
                                    <textarea name="bar_plus[<?= $item->id; ?>]" id="barp-<?= $item->id; ?>"
                                              class="career-editor"><?= $compempl[$item->id]->bar_plus; ?></textarea>
                                </div>
                                <div class="item col-50">
                                    <label for="barm-<?= $item->id; ?>">Описание для главной</label>
                                    <textarea name="bar_minus[<?= $item->id; ?>]" id="barm-<?= $item->id; ?>"
                                              class="career-editor"><?= $compempl[$item->id]->bar_minus; ?></textarea>
                                </div>
                            </div>

                        <? endforeach; ?>
                    </div>
                    <div class="row">
                        <div class="item col-3">
                            <button type="submit" class="nodes-save btn btn-primary"><i
                                        class="dashicons dashicons-yes"></i> Сохранить
                            </button>
                        </div>
                    </div>
                </form>
            <? else: ?>
                <div class="error">В базе нет секторов</div>
            <? endif; ?>
        </div>
    </div>


    <?php
    $_SESSION['career']['error'] = "";
    $_SESSION['career']['success'] = "";
    echo ob_get_clean();
}

function career_plan_page()
{
    global $wpdb;

    // $aoe = $wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_plan_aoe` ORDER BY `name` ASC" );
    $aoe = $wpdb->get_results("SELECT `id`,`name` FROM `wp_career_plan_category` ORDER BY `name` ASC");
    $cnt_aoe = count($aoe);

    $plans_res = $wpdb->get_results("SELECT * FROM `wp_career_plans`");

    $plans = [];
    foreach ($plans_res as $item) {
        $plans[$item->category_id][$item->parent][] = [
            'job_id' => $item->job_id,
            'sector_id' => $item->aoe_id,
            'parent' => $item->parent
        ];
    }
    //  echo "<p><b>plans</b><pre>";print_r($plans);echo "</pre></p>";


    echo '<link rel="stylesheet" href="' . CAREER_URL . 'css/tree/style.min.css" type="text/css" />' . PHP_EOL;
    echo '<link rel="stylesheet" href="' . CAREER_URL . 'css/select2.min.css" type="text/css" />' . PHP_EOL;
    echo '<link rel="stylesheet" href="' . CAREER_URL . 'css/style.admin.css" type="text/css" />' . PHP_EOL;
    ob_start();

    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Карьерные лестницы</h1>
        <div class="success hide">Лестницы сохранены</div>
        <div class="error hide">Дочерняя должность совпадает с родительской</div>

        <? if ($cnt_aoe): ?>
            <div class="action-buttons">
                <button type="button" class="node-create btn btn-success">
                    <i class="dashicons dashicons-plus"></i> Добавить
                </button>
                <button type="button" class="node-delete btn btn-danger"><i class="dashicons dashicons-no"></i> Удалить
                </button>
                <button type="button" class="nodes-save btn btn-primary"><i class="dashicons dashicons-yes"></i>
                    Сохранить
                </button>
                <div class="error-delete hide">Элемент является родителем</div>
            </div>
            <ul id="tree-plan">
                <?
                //    echo "<p><b>plans</b><pre>";print_r($plans);echo "</pre></p>";


                foreach ($aoe as $item): ?>
                    <?
                    if (isset($plans[$item->id])):?>
                        <li class="close">
                            <a href="#" class="parent" data-category="<?= $item->id; ?>"
                               data-id="0"><?= $item->name; ?></a>
                            <? //career_get_tree($plans[$item->id], $item->id, 0 );
                            ?>
                        </li>
                    <? else: ?>
                        <li><a href="#" data-category="<?= $item->id; ?>" data-id="0"><?= $item->name; ?></a></li>
                    <?php endif; ?>
                <? endforeach; ?>
            </ul>

            <!--div id="tree">
            <ul>
                <li>Пример
                    <ul>
                        <li id="child_node_1">Child node 1</li>
                        <li>Child node 2</li>
                    </ul>
                </li>
                <? foreach ($aoe as $item): ?>
                <li><?= $item->name; ?><input type="hidden" name="sector[]" value="<?= $item->id; ?>"></li>
                <? endforeach; ?>
            </ul>

        </div-->
        <?php endif; ?>

    </div>
    <?php
    $_SESSION['career']['error'] = "";
    $_SESSIONSESSION['career']['success'] = "";
    $res = ob_get_clean();

    echo $res;
}

function career_grade_page()
{
    global $wpdb;

    $grades_res = $wpdb->get_results("SELECT `id`, `rate`, `comment`, unix_timestamp(`date`) `dt` FROM `wp_career_grade_site` ORDER BY `date` DESC");

    $grades = [];
    foreach ($grades_res as $item) {
        $grades[] = [
            'id' => $item->id,
            'rate' => $item->rate,
            'comment' => $item->comment,
            'date' => date('d.m.Y', $item->dt)
        ];
    }

    echo '<link rel="stylesheet" href="' . CAREER_URL . 'css/tree/style.min.css" type="text/css" />' . PHP_EOL;
    echo '<link rel="stylesheet" href="' . CAREER_URL . 'css/select2.min.css" type="text/css" />' . PHP_EOL;
    echo '<link rel="stylesheet" href="' . CAREER_URL . 'css/style.admin.css" type="text/css" />' . PHP_EOL;
    ob_start();

    ?>
    <style>
        .text-center {
            text-align: center !important;
        }
        .delete-grade {
            color: #f80404;
            font-weight: bold;
        }
        a.delete-grade:hover, a.delete-grade:focus, a.delete-grade:active {
            color: #a40707 !important;
            border: none;
        }
        .col-1 {
            width: 10% !important;
        }
        .col-9 {
            width: 70% !important;
        }
    </style>
    <script>
        jQuery(function($){
            $('.delete-grade').click(function(e){
                e.preventDefault();
                var obj = $(this),
                    id = obj.data('id');
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
                        action: "career_delete_grade_site",
                        id: id
                    },
                    success: function (data) {
                        obj.closest('tr').remove();
                    }
                });
            });
        });

    </script>
    <div class="wrap">
        <h1 class="wp-heading-inline">Оценки сайта</h1>
        <div class="success hide">Оценки удалены</div>

        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
            <tr>
                <th scope="col" id="title" class="manage-column column-title column-primary text-center col-1"><span>Дата</span></th>
                <th scope="col" id="title" class="manage-column column-title column-primary text-center col-1"><span>Оценка</span></th>
                <th scope="col" id="title" class="manage-column column-title column-primary col-9"><span>Комментарий</span></th>
                <th class="col-1"></th>
            </tr>
            </thead>

            <tbody id="the-list">
            <? foreach ($grades as $item): ?>
            <tr>
                <td class="text-center"><? echo $item['date']; ?></td>
                <td class="text-center"><? echo $item['rate']; ?></td>
                <td><? echo $item['comment']; ?></td>
                <td><a href="#" data-id="<? echo $item['id']; ?>" class="delete-grade"><span class="dashicons dashicons-no"></span></a></td>
            </tr>
            <? endforeach; ?>
            </tbody>

        </table>



    </div>
<?php
    $res = ob_get_clean();

    echo $res;
    }

    function career_comp_empl_page()
    {
    global $wpdb;

    $aoe = $wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_aoe`" );
    $cnt_aoe = count($aoe);
    $_compempl = $wpdb->get_results( "SELECT * FROM `wp_career_compempl`" );
    $compempl = [];
    foreach($_compempl as $item) {
    $compempl[ $item->sector_id ] = $item;
    }

    ob_start();
    ?>
    <style>
        [type="file"] {
            display: none;
        }

        .error, .success {
            color: red;
            font-weight: bold;
            margin: 15px 0 !important;
            padding: 15px !important;
        }

        .success {
            color: green;
            background: #fff;
            border: 1px solid #ccd0d4;
            border-left-width: 4px;
            border-left-color: green;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        }

        .container.aoe {
            width: 100%;
        }

        .container.aoe .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            align-content: center;
            border-bottom: 1px solid grey;
        }

        .row .item {
            padding: 15px;

        }

        .container.aoe .row .item {

        }

        .row .item input, .row .item textarea {
            width: 100%;
        }

        .row .item textarea {
            height: 100%;
        }

        .text-bold {
            font-weight: bold;
        }

        #wpfooter {
            display: none;
        }

        .col-1 {
            width: 10% !important;
        }

        .col-2 {
            width: 20% !important;
        }

        .col-3 {
            width: 30% !important;
            height: 130px;
        }
    </style>
    <div class="wrap">
        <h1 class="wp-heading-inline">Данные для стр Компания/Работник</h1>
        <?php if (!empty($_SESSION['career']['error'])): ?>
            <div class="error"><?= $_SESSION['career']['error']; ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['career']['success'])): ?>
            <div class="success"><?= $_SESSION['career']['success']; ?></div>
        <?php endif; ?>


        <div class="container aoe">
            <? if ($cnt_aoe): ?>
                <form class="tablenav" action="<?= admin_url('admin-post.php'); ?>" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="action" value="career_comp_empl">
                    <? foreach ($aoe as $item): ?>

                        <div class="row">
                            <div class="item text-bold col-2"><?= $item->name; ?><input type="hidden" name="sector[]"
                                                                                        value="<?= $item->id; ?>"></div>
                            <div class="item col-1"><input type="text" name="up[<?= $item->id; ?>]" placeholder="Рост"
                                                           value="<?= $compempl[$item->id]->up; ?>"></div>
                            <div class="item col-1"><input type="text" name="qty[<?= $item->id; ?>]"
                                                           placeholder="Количество"
                                                           value="<?= $compempl[$item->id]->qty; ?>"></div>
                            <div class="item col-3">
                                <textarea name="lplus[<?= $item->id; ?>]"
                                          placeholder="Наиболее популярные вакансии"><?= $compempl[$item->id]->lplus; ?></textarea>
                            </div>
                            <div class="item col-3">
                                <textarea name="lminus[<?= $item->id; ?>]"
                                          placeholder="Менее популярные вакансии"><?= $compempl[$item->id]->lminus; ?></textarea>
                            </div>
                        </div>

                    <? endforeach; ?>
                    <div class="row">
                        <div class="item col-3">
                            <input type="submit" value="загрузить" class="button">
                        </div>
                    </div>
                </form>
            <? else: ?>
                <div class="error">В базе нет секторов</div>
            <? endif; ?>
        </div>
    </div>

    <?php
    $_SESSION['career']['error'] = "";
    $_SESSION['career']['success'] = "";
    echo ob_get_clean();
}

function career_import_page()
{
    ob_start();
    ?>
    <style>
        [type="file"] {
            display: none;
        }

        .error, .success {
            color: red;
            font-weight: bold;
            margin: 15px 0 !important;
            padding: 15px !important;
        }

        .success {
            color: green;
            background: #fff;
            border: 1px solid #ccd0d4;
            border-left-width: 4px;
            border-left-color: green;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        }
    </style>
    <div class="wrap">
        <h1 class="wp-heading-inline">Карьера</h1>
        <?php if (!empty($_SESSION['career']['error'])): ?>
            <div class="error"><?= $_SESSION['career']['error']; ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['career']['success'])): ?>
            <div class="success"><?= $_SESSION['career']['success']; ?></div>
        <?php endif; ?>
        <h2>Барометр</h2>
        <form class="tablenav" action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="career_import">
            <input type="hidden" name="import" value="barometr">
            <div class="alignleft actions bulkactions">

                <input type="file" id="file-2" class="inputfile textfield textfield--plain tooltipstered tooltip-inited"
                       name="data" maxlength="50" placeholder="Реквизиты">
                <label for="file-2" class="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                    </svg>
                    <span>Файл импорта (xlsx)</span></label>

                <input type="submit" value="загрузить" class="button">
            </div>
        </form>
        <h2>Лестницы</h2>
        <form class="tablenav" action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="career_import">
            <input type="hidden" name="import" value="plan">
            <div class="alignleft actions bulkactions">

                <input type="file" id="file-3" class="inputfile textfield textfield--plain tooltipstered tooltip-inited"
                       name="data" maxlength="50" placeholder="Реквизиты">
                <label for="file-3" class="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                    </svg>
                    <span>Файл импорта (xlsx)</span></label>

                <input type="submit" value="загрузить" class="button">
            </div>
        </form>
    </div>

    <?php
    $_SESSION['career']['error'] = "";
    $_SESSION['career']['success'] = "";
    echo ob_get_clean();

}

function career_sector_survey()
{
    ob_start();
    ?>
    <style>
        [type="file"] {
            display: none;
        }

        .error, .success {
            color: red;
            font-weight: bold;
            margin: 15px 0 !important;
            padding: 15px !important;
        }

        .success {
            color: green;
            background: #fff;
            border: 1px solid #ccd0d4;
            border-left-width: 4px;
            border-left-color: green;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
        }
    </style>
    <div class="wrap">
        <h1 class="wp-heading-inline">Импорт опросов</h1>
        <?php if (!empty($_SESSION['career']['error'])): ?>
            <div class="error"><?= $_SESSION['career']['error']; ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['career']['success'])): ?>
            <div class="success"><?= $_SESSION['career']['success']; ?></div>
        <?php endif; ?>
        <h2>Для соискателей</h2>
        <form class="tablenav" action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="career_import">
            <input type="hidden" name="import" value="survey_worker">
            <div class="alignleft actions bulkactions">

                <input type="file" id="file-2" class="inputfile textfield textfield--plain tooltipstered tooltip-inited"
                       name="data" maxlength="50" placeholder="Опросы">
                <label for="file-2" class="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                    </svg>
                    <span>Файл импорта (xlsx)</span></label>

                <input type="submit" value="загрузить" class="button">
            </div>
        </form>
        <h2>Для работодателей</h2>
        <form class="tablenav" action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="career_import">
            <input type="hidden" name="import" value="survey_employer">
            <div class="alignleft actions bulkactions">

                <input type="file" id="file-3" class="inputfile textfield textfield--plain tooltipstered tooltip-inited"
                       name="data" maxlength="50" placeholder="Опросы">
                <label for="file-3" class="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                    </svg>
                    <span>Файл импорта (xlsx)</span></label>

                <input type="submit" value="загрузить" class="button">
            </div>
        </form>
        <h2>Точки конфликтов</h2>
        <form class="tablenav" action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="career_import">
            <input type="hidden" name="import" value="survey_conflict">
            <div class="alignleft actions bulkactions">
                <input type="file" id="file-4" class="inputfile textfield textfield--plain tooltipstered tooltip-inited"
                       name="data" maxlength="50" placeholder="Опросы">
                <label for="file-4" class="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                    </svg>
                    <span>Файл импорта (xlsx)</span></label>

                <input type="submit" value="загрузить" class="button">
            </div>
        </form>
    </div>

    <?php
    $_SESSION['career']['error'] = "";
    $_SESSION['career']['success'] = "";
    echo ob_get_clean();
}