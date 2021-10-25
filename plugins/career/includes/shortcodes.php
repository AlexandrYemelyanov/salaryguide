<?php
// career-employer-select
add_shortcode('career-employer-select', function ()
{
    global $wpdb;

    $jobs = $wpdb->get_results( "SELECT aoe.`id`,job.`name` job_name, aoe.`name` sector_name
                                 FROM `wp_career_job` job, 
                                      `wp_career_category_job` cjob, 
                                      `wp_career_aoe_category` aoecat, 
                                      `wp_career_aoe` aoe
                                 WHERE job.`id` = cjob.`job_id` AND cjob.`category_id` =  aoecat.`category_id` AND 
                                       aoecat.`aoe_id` = aoe.`id` 
                                 ORDER BY job.`name` ASC" );
    $jobs_repack = [];
    foreach($jobs as $item) {
        $jobs_repack[] = [
            'sector_id' => $item->id,
            'job' => $item->job_name,
            'sector' => $item->sector_name
        ];
    }
    ob_start();
    ?>

    <form action="<?= get_page_uri( 245 ); ?>" method="get" class="career-main-select">
        <div class="item">
            <select name="aoe" class="select365" id="employerSelect">
                <option value="">Выберите должность</option>
                <? if( !empty($jobs) ): ?>
                    <? foreach($jobs_repack as $id=>$item): ?>
                        <option value="<?= $item['sector_id']; ?>"><?= $item['job']; ?> (<?= $item['sector']; ?>)</option>
                    <? endforeach; ?>
                <? endif; ?>
            </select>
        </div>
    </form>

    <?php
    return ob_get_clean();
});

// career-company-select
add_shortcode('career-company-select', function () {
    global $wpdb;

    $jobs = $wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_aoe` ORDER BY `name`" );
    $jobs = career_res_repack('id','name',$jobs);

    ob_start();
    ?>
    <form action="<?= get_page_uri( 245 ); ?>" method="get" class="career-main-select">
        <div class="item">
            <select name="aoe" class="select365 career-required" id="companySelect">
                <option value="">Выберите вид деятельности</option>
                <? if( !empty($jobs) ): ?>
                    <? foreach($jobs as $id=>$name): ?>
                        <option value="<?= $id; ?>"><?= $name; ?></option>
                    <? endforeach; ?>
                <? endif; ?>
            </select>
        </div>
    </form>

    <?php
    return ob_get_clean();
});

// career-person-up'
add_shortcode('career-person-up', function ()
{
    global $wpdb;
    $sector = career_get_sector();
    $up = $wpdb->get_results( "SELECT `up` FROM `wp_career_compempl` WHERE `sector_id` =  ".$sector );
    if(!isset($up[0])) return 0;
    return $up[0]->up??'';
});

// career-person-qty
add_shortcode('career-person-qty', function ()
{
    global $wpdb;
    $sector = career_get_sector();
    $qty = $wpdb->get_results( "SELECT `qty` FROM `wp_career_compempl` WHERE `sector_id` =  ".$sector );
    if(!isset($qty[0])) return 0;
    return $qty[0]->qty??'';
});

// career-sector-id
add_shortcode('career-sector-id', function ()
{
    return career_get_sector();
});

// career-sector-link
add_shortcode('career-sector-link', function ()
{
    $link = '';
    if(isset($_GET['aoe'])&&!empty($_GET['aoe'])) {
        $link = '?sector='.career_get_sector();
    }
    return $link;
});

// career-sector-name
add_shortcode('career-sector-name', function ()
{
    $name = '';
    if(isset($_GET['aoe'])&&!empty($_GET['aoe'])) {
        $name = career_get_sector_name(intval($_GET['aoe']));
    }
    return $name;
});

// career-recomended-vacancy
add_shortcode('career-recomended-vacancy', function(){
    global $wpdb;
    $sector = career_get_sector();
    $links = $wpdb->get_results( "SELECT `lplus`,`lminus` FROM `wp_career_compempl` WHERE `sector_id` =  ".$sector );

    if(!isset($links[0])) return '';

    $lplus = explode("\n",$links[0]->lplus);
    $lminus = explode("\n",$links[0]->lminus);
    $lplus_html = $lminus_html = '';

    if(count($lplus)) {
        foreach($lplus as $item) {
            list($name,$link) = explode(':',$item);
			$lplus_html .= '<li><a href="//hays.ru/search/?text='.$link.'">'.$name.'</a></li>';
        }
    }

    if(count($lminus)) {
        foreach($lminus as $item) {
            list($name,$link) = explode(':',$item);
            $lminus_html .= '<li><a href="'.$link.'">'.$name.'</a></li>';
        }
    }
    if(empty($lplus_html)&&empty($lminus_html)) return '';
    ob_start();
    ?>
    <div class="d-flex flex-center recomended-vacancy">
        <? if(!empty($lplus_html)):?>
            <div class="item">
                <h5><span class="dashicons dashicons-plus hays-icons"></span> Популярные вакансии</h5>
                <ul class="list-simple">
                    <?= $lplus_html; ?>
                </ul>
            </div>
        <? endif; ?>
        <? if(!empty($lminus_html)):?>
            <div class="item" style="display:none;">
                <h5><span class="dashicons dashicons-minus hays-icons"></span> Менее популярные<br>вакансии</h5>
                <ul class="list-simple">
                    <?= $lminus_html; ?>
                </ul>
            </div>
        <? endif; ?>
    </div>
    <?
    return ob_get_clean();
});

// career-calculator-widget
add_shortcode('career-calculator-widget', function(){

    if(isset($_GET['min'])) {
        $min = number_format(intval($_GET['min']), 0, ',', ' ');
        $max = number_format(intval($_GET['max']), 0, ',', ' ');
        $cur = number_format(intval($_GET['cur']), 0, ',', ' ');

        $begin = min($min,$max,$cur);
        $finish = $begin*2.5;

        $min_per = career_get_percent($min, $finish);
        $max_per = career_get_percent($max, $finish);
        $cur_per = career_get_percent($cur, $finish);

        if((intval($min_per==100)+intval($max_per==100)+intval($cur_per==100))>1) {
            $finish = max($min,$max,$cur);

            $min_per = career_get_percent($min, $finish);
            $max_per = career_get_percent($max, $finish);
            $cur_per = career_get_percent($cur, $finish);
        }

    }


    /*
        $min = $min??'140 000';
    $max = $max??'250 000';
    $cur = $cur??'170 000';
    */


    ob_start();
    ?>
    <div id="resultado" class="cCalc">
        <div class="oBox">
            <div>
                <h2 class="oResultatTitle">
                </h2>

            </div>

            <div class="cCalc__graph">
                <div class="cCalc__wrap">
                    <? if(isset($min)):?>
                    <div class="cCalc__resultat jsResultat" id="min1" data-val="<?= $min; ?>" style="bottom: <?= $min_per; ?>%;">
                        <span class="cCalc__label">Минимальная</span>
                        <strong><?= $min; ?> &#8381;</strong>
                    </div>
                    <div class="cCalc__resultat jsResultat" id="max1" data-val="<?= $max; ?>" style="bottom: <?= $max_per; ?>%;">
                        <span class="cCalc__label">Максимальная</span>
                        <strong><?= $max; ?> &#8381;</strong>
                    </div>
                    <div class="cCalc__resultat jsResultat cCalc__resultat--tu" id="seleccionat" data-val="<?= $cur; ?>" style="bottom: <?= $cur_per; ?>%;">
                        Текущая <br>
                        <strong><?= $cur; ?> &#8381;</strong>
                    </div>


                    <div id="bar" class="cCalc__bar" style="height: <?= $cur_per; ?>%;"></div>
                    <? endif; ?>
                </div>
                <? if(isset($min)):?>
                <div class="cCalc__bottom"></div>
                <? endif; ?>


            </div>
        </div>
    </div>
    <?
    return ob_get_clean();
});

// career-plan-widget
add_shortcode('career-plan-widget', function(){

    $count = isset($_POST['branch'])&&count($_POST['branch']);

    ob_start();
    ?>

    <ul class="timeline<?if(!$count):?> empty<? endif; ?>">
        <?if($count):?>
            <li class="start"><h4>СТАРТ</h4></li>
        <? foreach($_POST['branch'] as $item): ?>
        <li class="event" data-date="">
            <h3><?= $item['title']; ?></h3>
            <p><?= $item['subtitle']; ?></p>
            <p><a href="//hays.ru/search/?text=<?= $item['title']; ?>" target="_blank">Вакансии</a></p>
        </li>
        <? endforeach; ?>
            <li class="event last" data-date="">
                <p>В данный момент есть несколько способов продолжить вашу карьеру.</p>
                <p><a href="//hays.ru/search/" target="_blank">Посмотреть предложения</a></p>
            </li>
        <? else: ?>
            <li class="event" data-date=""></li>
            <li class="event" data-date=""></li>
            <li class="event" data-date=""></li>
            <li class="event" data-date=""></li>
        <? endif; ?>
    </ul>
    <?
    return ob_get_clean();
});

// career-plan-selects-old
add_shortcode('career-plan-selects-old', function(){

    $aoe = career_get_all_aoe();

    ob_start();
    ?>
    <form action="/" id="form-calculator" method="post">
        <input type="hidden" name="action" value="career_plan_get_widget">
        <div id="calculator-selects">
            <div class="item">
                <label for="aoe">Отрасль<span class="require-sign"></span></label>
                <select id="aoe-plan" name="aoe" class="select-career career-required" autocomplete="off">
                    <option value="">...</option>
                    <? if(count($aoe)): ?>
                        <? foreach($aoe as $id=>$name): ?>
                            <option value="<?= $id; ?>"><?= $name; ?></option>
                        <? endforeach; ?>
                    <? endif; ?>
                </select>
            </div>
            <div class="item">
                <label for="category-plan">Направление<span class="require-sign"></span></label>
                <select id="category-plan" name="category" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="job-plan">Названиие позиции<span class="require-sign"></span></label>
                <select id="job-plan" name="job-plan" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="type-plan">Тип компании<span class="require-sign"></span></label>
                <select id="type-plan" name="type" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <div class="elementor-button-wrapper elementor-align-center">
                    <a href="#" id="calculator-go" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                        <span class="elementor-button-content-wrapper">
                            <span class="elementor-button-text">Проверить результат</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </form>
    
    <?
    return ob_get_clean();
});

// career-plan-selects
add_shortcode('career-plan-selects', function(){

    $aoe = career_get_all_aoe();

    ob_start();
    ?>
    <form action="/" id="form-calculator" method="post">
        <input type="hidden" name="action" value="career_plan_get_widget">
        <div id="calculator-selects">
            <div class="item">
                <label for="aoe">Отрасль<span class="require-sign"></span></label>
                <select id="aoe-plan" name="aoe" class="select-career career-required" autocomplete="off">
                    <option value="">...</option>
                    <? if(count($aoe)): ?>
                        <? foreach($aoe as $id=>$name): ?>
                            <option value="<?= $id; ?>"><?= $name; ?></option>
                        <? endforeach; ?>
                    <? endif; ?>
                </select>
            </div>
            <div class="item">
                <label for="category-plan">Направление<span class="require-sign"></span></label>
                <select id="category-plan" name="category" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="job-plan">Названиие позиции<span class="require-sign"></span></label>
                <select id="job-plan" name="job-plan" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="type-plan">Тип компании<span class="require-sign"></span></label>
                <select id="type-plan" name="type" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <div class="elementor-button-wrapper elementor-align-center">
                    <a href="#" id="calculator-go" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                        <span class="elementor-button-content-wrapper">
                            <span class="elementor-button-text">Проверить результат</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </form>

    <form action="/" id="form-calculator" method="post">
        <input type="hidden" name="action" value="career_calculator_get_widget">
        <div id="calculator-selects">
            <div class="item">
                <label for="aoe">Отрасль<span class="require-sign"></span></label>
                <select id="aoe" name="aoe" class="select-career career-required" autocomplete="off">
                    <option value="">...</option>
                    <? if(count($aoe)): ?>
                        <? foreach($aoe as $id=>$name): ?>
                            <option value="<?= $id; ?>"><?= $name; ?></option>
                        <? endforeach; ?>
                    <? endif; ?>
                </select>
            </div>
            <div class="item">
                <label for="category">Направление<span class="require-sign"></span></label>
                <select id="category" name="category" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="job">Названиие позиции<span class="require-sign"></span></label>
                <select id="job" name="job" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="type">Тип компании<span class="require-sign"></span></label>
                <select id="type" name="type" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="region">Регион<span class="require-sign"></span></label>
                <select id="region" name="region" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
        </div>
    </form>

    <form action="/" id="form-calculator" method="post">
        <input type="hidden" name="action" value="career_calculator_get_widget">
        <div id="calculator-selects">
            <div class="item">
                <label for="aoe">Отрасль<span class="require-sign"></span></label>
                <select id="aoe" name="aoe" class="select-career career-required" autocomplete="off">
                    <option value="">...</option>
                    <? if(count($aoe)): ?>
                        <? foreach($aoe as $id=>$name): ?>
                            <option value="<?= $id; ?>"><?= $name; ?></option>
                        <? endforeach; ?>
                    <? endif; ?>
                </select>
            </div>
            <div class="item">
                <label for="category">Направление<span class="require-sign"></span></label>
                <select id="category" name="category" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="job">Названиие позиции<span class="require-sign"></span></label>
                <select id="job" name="job" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="type">Тип компании<span class="require-sign"></span></label>
                <select id="type" name="type" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <div class="elementor-button-wrapper elementor-align-center">
                    <a href="#" id="calculator-go" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                        <span class="elementor-button-content-wrapper">
                            <span class="elementor-button-text">Проверить результат</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </form>

    <?
    return ob_get_clean();
});

// career-calculator-selects-old
add_shortcode('career-calculator-selects-old', function(){

    $aoe = career_get_all_aoe();

    ob_start();
    ?>
    <form action="/" id="form-calculator" method="post">
        <input type="hidden" name="action" value="career_calculator_get_widget">
        <div id="calculator-selects">
            <div class="item">
                <label for="aoe">Отрасль<span class="require-sign"></span></label>
                <select id="aoe" name="aoe" class="select-career career-required" autocomplete="off">
                    <option value="">...</option>
                    <? if(count($aoe)): ?>
                        <? foreach($aoe as $id=>$name): ?>
                            <option value="<?= $id; ?>"><?= $name; ?></option>
                        <? endforeach; ?>
                    <? endif; ?>
                </select>
            </div>
            <div class="item">
                <label for="category">Направление<span class="require-sign"></span></label>
                <select id="category" name="category" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="job">Названиие позиции<span class="require-sign"></span></label>
                <select id="job" name="job" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="type">Тип компании<span class="require-sign"></span></label>
                <select id="type" name="type" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="region">Регион<span class="require-sign"></span></label>
                <select id="region" name="region" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="salary">Текущая зарплата (₽)<span class="require-sign"></span></label>
                <input type="text" id="salary" name="salary" disabled>
                <span class="error-info">Укажите вашу зарплату</span>
            </div>
            <div class="item">
                <div class="elementor-button-wrapper elementor-align-center">
                    <a href="#" id="calculator-go" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                        <span class="elementor-button-content-wrapper">
                            <span class="elementor-button-text">Проверить результат</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </form>
    <?
    return ob_get_clean();
});

// career-calculator-selects
add_shortcode('career-calculator-selects', function(){

    $aoe = career_get_all_aoe();

    ob_start();
    ?>
    <form action="/" id="form-calculator" method="post">
        <input type="hidden" name="action" value="career_calculator_get_widget">
        <div id="calculator-selects">
            <div class="item">
                <label for="aoe">Отрасль<span class="require-sign"></span></label>
                <select id="aoe" name="aoe" class="select-career career-required" autocomplete="off">
                    <option value="">...</option>
                    <? if(count($aoe)): ?>
                        <? foreach($aoe as $id=>$name): ?>
                            <option value="<?= $id; ?>"><?= $name; ?></option>
                        <? endforeach; ?>
                    <? endif; ?>
                </select>
            </div>
            <div class="item">
                <label for="category">Направление<span class="require-sign"></span></label>
                <select id="category" name="category" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="job">Названиие позиции<span class="require-sign"></span></label>
                <select id="job" name="job" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="type">Тип компании<span class="require-sign"></span></label>
                <select id="type" name="type" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="region">Регион<span class="require-sign"></span></label>
                <select id="region" name="region" class="select-career career-required" disabled autocomplete="off">
                    <option value="">...</option>
                </select>
            </div>
            <div class="item">
                <label for="salary">Текущая зарплата (₽)<span class="require-sign"></span></label>
                <input type="text" id="salary" name="salary" disabled>
                <span class="error-info">Укажите вашу зарплату</span>
            </div>
            <div class="item">
                <div class="elementor-button-wrapper elementor-align-center">
                    <a href="#" id="calculator-go" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                        <span class="elementor-button-content-wrapper">
                            <span class="elementor-button-text">Проверить результат</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </form>

    <?
    return ob_get_clean();
});

// career-sector-list
add_shortcode('career-sector-list', function(){
    $id = $_GET['sector']??0;
    $id = (int)$id;
    $aoe = career_get_all_aoe();

    ob_start();
    ?>
    <form action="<?= get_page_uri( 233 ); ?>" method="get">
    <select id="sector-aoe" name="sector" class="select-career career-required" autocomplete="off">
        <? if(count($aoe)): ?>
            <? foreach($aoe as $_id=>$name):
                $selected = "";
                if($_id == $id) $selected = ' selected';
                ?>
                <option value="<?= $_id; ?>"<?= $selected; ?>><?= $name; ?></option>
            <? endforeach; ?>
        <? endif; ?>
    </select>
    </form>
    <?
    return ob_get_clean();
});

// career-sector-postion
add_shortcode('career-sector-postion', function(){

    $id = $_GET['sector']??0;
    $id = (int)$id;

    if(!$id) {
        $aoe = career_get_all_aoe();
        $id = $aoe[0]->id;
    }

    $sector_info = career_get_sector_info($id);
    if(empty($sector_info['positions'])) return '';

    $links = explode("\n",$sector_info['positions']);
    $links_html = '';

    if(count($links)) {
        foreach($links as $item) {
            list($name,$link) = explode(':',$item);
            $links_html .= '<li><a href="'.$link.'">'.$name.'</a></li>';
        }
    }

    return $links_html;

});

// career-sector-postion2
add_shortcode('career-sector-postion2', function(){

    $id = $_GET['sector']??0;
    $id = (int)$id;

    if(!$id) {
        $aoe = career_get_all_aoe();
        $id = $aoe[0]->id;
    }

    $sector_info = career_get_sector_info($id);
    if(empty($sector_info['positions'])) return '';

    $links = explode("\n",$sector_info['positions']);
    $links_html = '';
    $razdels = [];
    $_razdel = '';

    if(count($links)) {
        foreach($links as $item) {
            if(!empty(trim($item))) {
                if( strpos($item, '|') === false) {
                    $_razdel = str_replace(['.', ':', "\n"], '', $item);
                } else {
                    list($name,$link) = explode('|',$item);
                    $razdels[$_razdel][] = ['name' => $name, 'link' => $link];
                }
            }
        }

        $i = 0;
        foreach ($razdels as $razdel => $info) {
            $links_html .= '    
            <div class="specialists elem'.$i.'">
                <div class="container flex top">
                    <div class="specialists__title">
                        <h2>'.$razdel.'</h2>
                    </div>
                    <ul class="specialists__list">';
            foreach ($info as $item) {
                $links_html .= '<li><a href="'.$item['link'].'">'.$item['name'].'</a></li>';
            }
            $links_html .= ' 
                    </ul>
                </div>
            </div>';
            $i++;
        }
    }

    return $links_html;

});

// carrer-sector-tendence
add_shortcode('carrer-sector-tendence', function(){
    $id = $_GET['sector']??0;
    $id = (int)$id;

    if(!$id) {
        $aoe = career_get_all_aoe();
        $id = $aoe[0]->id;
    }

    $sector_info = career_get_sector_info($id);

    return nl2br($sector_info['tendencies']);
});

// carrer-sector-balance
add_shortcode('carrer-sector-balance', function(){
    $id = $_GET['sector']??0;
    $id = (int)$id;

    if(!$id) {
        $aoe = career_get_all_aoe();
        $id = $aoe[0]->id;
    }

    $sector_info = career_get_sector_info($id);
    return nl2br($sector_info['balance']);
});

// carrer-sector-barplus
add_shortcode('carrer-sector-barplus', function(){
    $id = $_GET['sector']??0;
    $id = (int)$id;

    if(!$id) {
        $aoe = career_get_all_aoe();
        $id = $aoe[0]->id;
    }

    $sector_info = career_get_sector_info($id);

    $links = explode("\n", $sector_info['bar_plus']);
    $links_html = '';

    if(count($links)) {
        foreach($links as $item) {
            if(!empty(trim($item))) {
                list($name,$link) = explode('|',$item);
                $links_html .= '<li><a href="'.$link.'">'.$name.'</a></li>';
            }
        }
    }

    return $links_html;
});

// carrer-sector-barminus
add_shortcode('carrer-sector-barminus', function(){
    $id = $_GET['aoe']??0;
    $id = (int)$id;

    if(!$id) {
        $aoe = career_get_all_aoe();
        $id = $aoe[0]->id;
    }

    $sector_info = career_get_sector_info($id);
    return nl2br($sector_info['bar_minus']);
});