<?php
function career_get_conflict()
{
    global $wpdb;
    $survey = [];
    $type = 'con';

    $res = $wpdb->get_results("SELECT `id`, `name` FROM `wp_career_".$type."_category` ORDER BY `id`");
    $category = [];
    foreach ($res as $row) {
        $category[ $row->id ] = $row->name;
    }

    $res = $wpdb->get_results("SELECT `id`, `category_id`, `name` FROM `wp_career_".$type."_question` ORDER BY `id`");
    $question = [];
    foreach ($res as $row) {
        $question[ $row->category_id ][] = [
            'id' => $row->id,
            'name' => $row->name
        ];
    }

    $res = $wpdb->get_results("SELECT `id`, `question_id`, `name` FROM `wp_career_".$type."_answer` ORDER BY `id`");
    $answer = [];
    foreach ($res as $row) {
        $answer[ $row->question_id ][] = [
            'id' => $row->id,
            'name' => $row->name
        ];
    }

    $res = $wpdb->get_results("SELECT `percent`, `answer_id`, `year`, `type` FROM `wp_career_".$type."_survey` ORDER BY `id`");
    $survey_per = [];
    foreach ($res as $row) {
        $survey_per[ $row->answer_id ][ $row->year ][ $row->type ] = $row->percent;
    }

    foreach ($category as $category_id => $category_name) {
        foreach ($question[ $category_id ] as $_question) {
            foreach ($answer[ $_question['id'] ] as $_answer) {
                foreach ($survey_per[ $_answer['id'] ] as $year => $percent) {
                    $survey[ $category_name ][ $_question['name'] ][ $_answer['name'] ][ $year ] = $percent;
                }         
            }             
        }         
    }

    return $survey;
}

/**
 * @param string $type sw - для соискателей, se - для работодателей
 * @return array
 */
function career_get_survey($type)
{
    global $wpdb;
    $survey = [];

    if (!in_array($type, ['sw', 'se'])) {
        return $survey;
    }

    $res = $wpdb->get_results("SELECT `id`, `name` FROM `wp_career_".$type."_category` ORDER BY `id`");
    $category = [];
    foreach ($res as $row) {
        $category[ $row->id ] = $row->name;
    }

    $res = $wpdb->get_results("SELECT `id`, `category_id`, `name` FROM `wp_career_".$type."_question` ORDER BY `id`");
    $question = [];
    foreach ($res as $row) {
        $question[ $row->category_id ][] = [
            'id' => $row->id,
            'name' => $row->name
        ];
    }

    $res = $wpdb->get_results("SELECT `id`, `question_id`, `name` FROM `wp_career_".$type."_answer` ORDER BY `id`");
    $answer = [];
    foreach ($res as $row) {
        $answer[ $row->question_id ][] = [
            'id' => $row->id,
            'name' => $row->name
        ];
    }

    $res = $wpdb->get_results("SELECT `percent`, `answer_id`, `year` FROM `wp_career_".$type."_survey` ORDER BY `id`");
    $survey_per = [];
    foreach ($res as $row) {
        $survey_per[ $row->answer_id ][ $row->year ] = $row->percent;
    }

    foreach ($category as $category_id => $category_name) {
        foreach ($question[ $category_id ] as $_question) {
            foreach ($answer[ $_question['id'] ] as $_answer) {
                foreach ($survey_per[ $_answer['id'] ] as $year => $percent) {
                    $survey[ $category_name ][ $_question['name'] ][ $_answer['name'] ][ $year ] = $percent;
                }
            }
        }
    }

    return $survey;
}

function career_get_tree($category_arr, $sector_id, $parent_id, $category_id)
{
    static $count = 0;
    $count++;

    if ($count > 150) {
        echo "аваринйная остановка";
        die("150 iteration");
    }

    
    if (isset($category_arr[$parent_id])) {
        $select_data = career_plan_get_elem($sector_id, $category_id);
       // echo "<p><b>select_data</b><pre>";print_r($select_data);echo "</pre></p>";
        
        
        echo '<ul data-parrrent="'.$parent_id.'">';
        foreach ($category_arr[$parent_id] as $value) {
            $select = '<select name="jt" class="job-title">';
            foreach ($select_data as $i => $item) {
                $selected = $value['job_id'] == $i ? ' selected="selected"' : '';
                $select .= '<option value="'.$i.'"'.$selected.'>'.$item.'</option>';
            }
            $select .= '</select>';

            if (isset($category_arr[$value["job_id"]])) {
                echo '<li class="close"><a href="#" class="parent" data-sector="'.$sector_id.'" data-parent="'.$value['parent'].'" data-id="'.$value['job_id'].'">'.$select.'</a>';

                career_get_tree($category_arr, $sector_id, $value["job_id"], $category_id);

            } else {
                echo '<li><a href="#" data-sector="'.$sector_id.'" data-parent="'.$value['parent'].'" data-id="'.$value['job_id'].'">'.$select.'</a>';
            }

            echo '</li>';
        }
        echo '</ul>';
    }
}

function career_get_sector()
{
    static $sector = 0;
    $aoe = intval($_GET['aoe'] ?? 0);
    $job = intval($_GET['job'] ?? 0);

    if ( ! empty($sector)) {
        return $sector;
    }

    if ( ! empty($aoe)) {
        $sector = $aoe;
    }

    if ( ! empty($job)) {
        global $wpdb;
        $jobs = $wpdb->get_results("SELECT asr.`aoe_id` FROM `wp_career_aoe_category` asr, `wp_career_category_job` cjb
                                     WHERE cjb.`job_id` = ".$job." AND cjb.`category_id` = asr.`category_id`");
        $sector = $jobs[0]->aoe_id;
    }

    return $sector;
}

function career_get_sector_name($id)
{
    $name = '';
    if ( ! empty($id)) {
        global $wpdb;
        $res = $wpdb->get_results("SELECT `name` FROM `wp_career_aoe` WHERE `id` = ".$id);
        $name = $res[0]->name;
    }

    return $name;
}

function career_plan_get_elem($sector_id = null, $category_id = null)
{
    global $wpdb;

    $sector = empty($sector_id) ? intval($_POST['sector']) : $sector_id;
    $category = empty($category_id) ? intval($_POST['category']) : $category_id;

        $jobs = $wpdb->get_results("SELECT job.`id`,job.`name` 
                                    FROM `wp_career_plan_map` map, `wp_career_plan_job` job
                                    WHERE map.`aoe` = ".$sector." AND  map.`category` = ".$category." AND map.`job` = job.`id`
                                    ORDER BY job.`name`");

        $jobs = career_res_repack('id', 'name', $jobs);

        $elems_res = $wpdb->get_results("SELECT t2.`id` , t2.`name` `job_name`, t3.`name` `orgtype_name`
                                        FROM `wp_career_plan_map` t1, `wp_career_plan_job` t2, `wp_career_plan_orgtype` t3
                                        WHERE t1.`job`
                                        IN ( ".implode(',', array_keys($jobs))." )
                                        AND t1.`aoe` = ".$sector." AND  t1.`category` = ".$category." 
                                        AND t1.`job` = t2.id
                                        AND t1.`orgtype` = t3.id");

        $elems = [];
        foreach ($elems_res as $item) {
            $elems[$item->id] = $item->job_name.' ('.$item->orgtype_name.')';
        }

    if (empty($sector_id)) {
        echo json_encode($elems);
        die;
    } else {
        return $elems;
    }
}


function career_plan_get_branch()
{
    global $wpdb;

    $parent = intval($_POST['parent']);
    $plan = intval($_POST['plan']);
    $category = intval($_POST['category']);
    $branch = array();

    // $plan = 0;
    $plan_db = $plan ? ' AND `plan_id` = '.$plan : '';

    $res = $wpdb->get_results(
        "SELECT *
        FROM `wp_career_plans`
        WHERE `parent` = ".$parent." AND  `category_id` = ".$category.$plan_db
    );

    foreach ($res as $row) {

        $child = $wpdb->get_results(
            "SELECT id
            FROM `wp_career_plans`
            WHERE `parent` = ".$row->job_id." AND  `category_id` = ".$category.$plan_db
        );

        $branch[] = array(
            'sector' => $row->aoe_id,
            'job' => $row->job_id,
            'child' => intval(count($child)>0),
            'item_id' => $row->id,
            'plan' => $row->plan_id,
        );

    }

    echo json_encode($branch);
    die;
}

function career_plan_get_sector($category_id = null)
{
    global $wpdb;

    $category = empty($category_id) ? intval($_POST['category']) : $category_id;

    //if (!isset($_SESSION['sectors'][$category_id])) {
        $__sectors = $wpdb->get_results("SELECT aoe.`id`,aoe.`name` 
                                FROM `wp_career_plan_aoe_category` aoca, `wp_career_plan_aoe` aoe
                                WHERE aoca.`category_id` = ".$category." AND aoe.`id` = aoca.`aoe_id`
                                ORDER BY aoe.`name`");

        $sectors = career_res_repack('id', 'name', $__sectors);
/*
        $_SESSION['sectors'][$category_id] = $sectors;
    }
    $sectors = $_SESSION['sectors'][$category_id];
*/

    if (empty($category_id)) {
        echo json_encode($sectors);
        die;
    } else {
        return $sectors;
    }
}

function career_get_all_aoe()
{
    global $wpdb;
    static $aoe = [];

    if (count($aoe)) {
        return $aoe;
    }

    $aoe = $wpdb->get_results("SELECT `id`,`name` FROM `wp_career_aoe` ORDER BY `name` ASC");
    if ( ! count($aoe)) {
        return [];
    }

    return career_res_repack('id', 'name', $aoe);
}

function career_plan_get_all_category()
{
    global $wpdb;
    static $category = [];

    if (count($category)) {
        return $category;
    }

    $aoe = $wpdb->get_results("SELECT `id`,`name` FROM `wp_career_plan_category` ORDER BY `name` ASC");
    if ( ! count($aoe)) {
        return [];
    }

    return career_res_repack('id', 'name', $aoe);
}

function career_get_all_category()
{
    global $wpdb;
    static $category = [];

    if (count($category)) {
        return $category;
    }

    $aoe = $wpdb->get_results("SELECT `id`,`name` FROM `wp_career_category` ORDER BY `name` ASC");
    if ( ! count($aoe)) {
        return [];
    }

    return career_res_repack('id', 'name', $aoe);
}

function career_calculator_get_elem()
{
    $options_html = '<option value="" selected>...</option>';
    $access = ["aoe", "category", "job", "aoe-plan", "category-plan", "job-plan", "exp", "type", "region"];

    if ( ! in_array($_POST['type'], $access)) {
        return $options_html;
    }

    $id = (int) $_POST['id'];
    global $wpdb;
    $res = [];

    switch ($_POST['type']) {
/*
        case "aoe-plan":
            $res = $wpdb->get_results("SELECT t1.`id`,t1.`name` FROM `wp_career_category` t1, `wp_career_aoe_category` t2
                                        WHERE t2.`aoe_id` = ".$id." AND t1.`id` = t2.`category_id` ORDER BY t1.`name` ASC");
            break;
*/
        case "category":
            $res = $wpdb->get_results("SELECT t1.`id`,t1.`name` 
                                        FROM `wp_career_job` t1, `wp_career_category_job` t2
                                        WHERE t2.`category_id` = ".$id." AND t1.`id` = t2.`job_id` ORDER BY t1.`name` ASC");
            break;
        case "category-plan":
/*
            $res = $wpdb->get_results("SELECT t1.`id`,t1.`name`
                                        FROM `wp_career_plan_job` t1, `wp_career_plan_category_job` t2
                                        WHERE t2.`category_id` = ".$id." AND t1.`id` = t2.`job_id` ORDER BY t1.`name` ASC");
*/
            $res = $wpdb->get_results("SELECT t1.`id`,t1.`name` 
                                        FROM `wp_career_plan_job` t1, `wp_career_plans` t2
                                        WHERE t2.`category_id` = ".$id." AND t1.`id` = t2.`job_id` ORDER BY t1.`name` ASC");
            break;
        case "job":
            $category = (int) $_POST['added']['category'];
            $res = $wpdb->get_results("SELECT t1.`id`,t1.`name` FROM `wp_career_aoe` t1, `wp_career_map` t2
                                            WHERE t2.`job` = ".$id." AND t2.`category` = ".$category." AND t1.id = t2.aoe ORDER BY t1.`name` ASC");
            break;
        case "job-plan":
            $category = (int) $_POST['added']['category'];
            $res = $wpdb->get_results("SELECT t1.`id`,t1.`name` FROM `wp_career_plan_aoe` t1, `wp_career_plans` t2
                                            WHERE t2.`job_id` = ".$id." AND t2.`category_id` = ".$category." AND t1.id = t2.aoe_id ORDER BY t1.`name` ASC");
            break;
        case "aoe":
            $job = (int) $_POST['added']['job'];
            $category = (int) $_POST['added']['category'];
            $res = $wpdb->get_results("SELECT t1.`id`,t1.`name` FROM `wp_career_orgtype` t1, `wp_career_map` t2
                                            WHERE t2.`aoe` = ".$id." AND t2.`job` = ".$job." AND t2.`category` = ".$category." AND t1.id = t2.orgtype ORDER BY t1.`name` ASC");
            break;
        case "aoe-plan":
            $job = (int) $_POST['added']['job'];
            $category = (int) $_POST['added']['category'];
/*
            $res = $wpdb->get_results("SELECT t1.`id`,t1.`name` FROM `wp_career_plan_orgtype` t1, `wp_career_plans` t2
                                            WHERE t2.`aoe_id` = ".$id." AND t2.`job_id` = ".$job." AND t2.`category_id` = ".$category." AND t1.id = t2.orgtype ORDER BY t1.`name` ASC");

*/
            $res = $wpdb->get_results("SELECT t3.`id` , t3.`name`
                                        FROM `wp_career_plan_map` t1, `wp_career_plan_job` t2, `wp_career_plan_orgtype` t3
                                        WHERE t1.`job` = ".$job."
                                        AND t1.`aoe` = ".$id."
                                        AND t1.`category` = ".$category."
                                        AND t1.`orgtype` = t3.id");
            echo "SELECT t3.`id` , t3.`name`
                                        FROM `wp_career_plan_map` t1, `wp_career_plan_job` t2, `wp_career_plan_orgtype` t3
                                        WHERE t1.`job` = ".$job."
                                        AND t1.`aoe` = ".$id."
                                        AND t1.`category` = ".$category."
                                        AND t1.`orgtype` = t3.id";
            break;
        case "type":
            $job = (int) $_POST['added']['job'];
            $res = $wpdb->get_results("SELECT t1.`id`,t1.`name` FROM `wp_career_region` t1, `wp_career_map` t2
                                        WHERE t2.`job` = ".$job." AND t2.`orgtype` = ".$id." AND t1.id = t2.region ORDER BY t1.`name` ASC");
            break;
    }

    // исключаем позиции для которых не созданы лестницы
/*
    if ($_POST['type'] == 'category-plan' && count($res)) {
        $job_id = [];
        foreach ($res as $item) {
            $job_id[] = $item->id;
        }
        $res2 = $wpdb->get_results("SELECT t2.`job` FROM `wp_career_plans` t1, `wp_career_plan_map` t2
                                        WHERE t1.`job_id` = t2.`id` AND t2.`job` IN (".implode(', ', $job_id).") GROUP BY t2.`job`");

        $allow_job = [];
        $allow_res = [];
        foreach ($res2 as $item) {
            $allow_job[] = $item->job;
        }
        foreach ($res as $item) {
            if (in_array($item->id, $allow_job)) {
                $allow_res[] = $item;
            }
        }

        $res = $allow_res;
    }
*/

    if (count($res)) {
        $res = career_res_repack('id', 'name', $res);

        foreach ($res as $id => $name) {
            $options_html .= '<option value="'.$id.'">'.$name.'</option>';
        }
    }

    echo $options_html;
    die;
}

function career_log($string) {
	$dir = WP_CONTENT_DIR.'/wp-logs';
    if ( ! file_exists($dir) && ! is_dir($dir)) {
        mkdir($dir);
    }

    $date = date('d.m.Y H:i:s');
    $string = "LOG.SALARY [$date]: $string \n";

    file_put_contents("$dir/salary.log", $string, FILE_APPEND | LOCK_EX);
}

function career_calculator_get_json()
{
    $category = (int) $_POST['category'];
    $job = (int) $_POST['job'];
    $type = (int) $_POST['type'];
    $region = (int) $_POST['region'];
    $salary = (int) $_POST['salary'];

    if ($job && $type && $region && $salary) {
        global $wpdb;

        $res = $wpdb->get_results("SELECT `salary_min`,`salary_typ`,`salary_max` FROM `wp_career_map`
                                    WHERE `category` = ".$category." AND `job` = ".$job." AND `orgtype` = ".$type." AND `region` = ".$region." 
                                    LIMIT 0,1");
        $result = [];
        if (isset($res[0])) {
            $min = $res[0]->salary_min;
            $max = $res[0]->salary_max;
            $middle = round(($max - $min) / 2 + $min);


            $begin = min($min,$max,$salary);
            $finish = max($min,$max,$salary);

            $begin2 = round($begin - $begin*.2, -4);

            if($begin2 > $begin) {
                $begin = round($begin - $begin*.3, -4);
            } else {
                $begin = $begin2;
            }

            $finish2 = round($finish + $finish*.1, -4);
            if($finish2 < $finish) {
                $finish = round($finish + $finish*.2, -4);
            } else {
                $finish = $finish2;
            }

            $average = round(($finish - $begin) / 2 + $begin);

            $grade = $finish - $begin;


            $result = [
                'min' =>  career_set_k($min),
                'middle' => career_set_k($middle),
                'max' =>  career_set_k($max),
                'salary' => career_set_k($salary),

                'begin' =>  career_set_k($begin),
                'average' => career_set_k($average),
                'finish' =>  career_set_k($finish),

                'min_per' =>  round( career_get_percent($min - $begin, $grade) ),
                'middle_per' => round( career_get_percent($middle - $begin, $grade) ),
                'max_per' =>  round( career_get_percent($max - $begin, $grade) ),
                'salary_per' =>  round( career_get_percent($salary - $begin, $grade) ),

                'query' => "SELECT `salary_min`,`salary_typ`,`salary_max` FROM `wp_career_map`
                                    WHERE `job` = ".$job." AND `orgtype` = ".$type." AND `region` = ".$region." 
                                    LIMIT 0,1"
            ];



        }

        echo json_encode($result);
        die;
    }
}

function career_calculator_get_widget()
{
    $job = (int) $_POST['job'];
    $type = (int) $_POST['type'];
    $region = (int) $_POST['region'];
    $salary = (int) $_POST['salary'];

    if ($job && $type && $region && $salary) {
        global $wpdb;

        $res = $wpdb->get_results("SELECT `salary_min`,`salary_typ`,`salary_max` FROM `wp_career_map`
                                    WHERE `job` = ".$job." AND `orgtype` = ".$type." AND `region` = ".$region." 
                                    LIMIT 0,1");
        if (isset($res[0])) {
            $_GET['min'] = $res[0]->salary_min;
            $_GET['max'] = $res[0]->salary_max;
            $_GET['cur'] = $salary;

            echo do_shortcode('[career-calculator-widget]');
        }
    }

    $aoeString = (string) $_POST['aoeString'];
    $categoryString = (string) $_POST['categoryString'];
    $jobString = (string) $_POST['jobString'];
    $typeString = (string) $_POST['typeString'];
    $regionString = (string) $_POST['regionString'];

    career_log("$aoeString:$categoryString:$jobString:$typeString:$regionString:$salary");

    die;
}

function career_plan_get_widget()
{
    $job = $_POST['job'] ?? '';
    $type = $_POST['type'] ?? '';

    if ( ! $job) {
        $job = $_POST['job-plan'];
    }
    if ( ! $type) {
        $type = $_POST['type-plan'];
    }

    if ($job && $type) {
        global $wpdb;

        $job = (int) $job;
        $type = (int) $type;
        $aoe = (int) $_POST['aoe-plan'];
        $category = (int) $_POST['category'];

/*
        $res = $wpdb->get_results("SELECT `id` FROM `wp_career_plan_map`
                                    WHERE `job` = ".$job." AND `orgtype` = ".$type);
        if (isset($res[0])) {
            $job_ids = [];

            foreach ($res as $item) {
                $job_ids[] = $item->id;
            }
*/

            if (true) {
                $res = $wpdb->get_results("SELECT `id`, `job_id`, `parent`, `plan_id` FROM `wp_career_plans`
                                            WHERE `category_id` = ".$category." AND `aoe_id` = ".$aoe." AND `job_id` = ".$job);

                $begins = [];
                $current_job = 0;
                foreach ($res as $item) {
                    $begins[$item->id] = [
                        "job"    => $item->job_id,
                        "parent" => $item->parent,
                        "aoe" => $aoe,
                        "category" => $category
                    ];
                    $current_job = $item->job_id;
                }

                $branches = [];
                foreach ($begins as $id => $item) {
                    $branches = array_merge($branches, career_get_branch($item));
                }
                // echo "<p><b>branches</b><pre>";print_r($branches);echo "</pre></p>";
                
                
                $level_brunches_names = [];
                if (count($branches)) {
                    // определяем максимальное количество levels
                    $levels = 0;
                    $max_brunch_pos = 0;
                    foreach ($branches as $i => $item) {
                        $qty = count($item);
                        if ($levels < $qty) {
                            $levels = $qty;
                            $max_brunch_pos = $i;
                        }
                    }

                    // подгоняем все ветки под одну длину по приниипу - одинаковые позиции могут находиться только на одном level
                    $temp_brunches = $branches;
                    $max_len_brunch = $branches[$max_brunch_pos];
                    unset($temp_brunches[$max_brunch_pos]);
                    $start = $levels - 1;

                    for ($i = $start; $i >= 0; $i--) {
                        foreach ($temp_brunches as $j => $brunche) {
                            $item = $max_len_brunch[$i];
                            $key = array_search($item, $brunche);

                            if ($key && $key != $i) {
                                unset($temp_brunches[$j][$key]);
                                $temp_brunches[$j][$i] = $item;
                            }
                        }
                    }

                    // заполняем пустые участки нулями
                    foreach ($temp_brunches as $i => $brunche) {
                        for ($j = 0; $j < count($brunche); $j++) {
                            if ( ! isset($brunche[$j])) {
                                $temp_brunches[$i][$j] = 0;
                            }
                        }
                        ksort($temp_brunches[$i]);
                    }
                    $temp_brunches[] = $max_len_brunch;
                    $branches = $temp_brunches;

                    // назначаем childs
                    $brunches = [];
                    foreach ($branches as $i => $branche) {
                        foreach ($branche as $j => $id) {
                            $brunches[$i][$j]['id'] = $id;

                            if (isset($branche[$j + 1]) && $id !== 0) {
                                $cur_pos = $j;
                                do {
                                    $cur_pos++;
                                    if ($branche[$cur_pos] !== 0) {
                                        $brunches[$i][$j]['childs'][] = $branche[$cur_pos];
                                    }
                                } while (isset($branche[$cur_pos]) && $branche[$cur_pos] === 0);
                            }
                        }
                    }

                    // объединяем по level
                    $level_brunches = [];
                    for ($i = 0; $i < $levels; $i++) {
                        foreach ($brunches as $item) {
                            if (isset($item[$i])) {
                                $level_brunches[$i][] = $item[$i];
                            }
                        }
                    }

                    // объединяем по id
                    $level_brunches_id = [];
                    $empty_index = 0;
                    foreach ($level_brunches as $i => $level) {
                        foreach ($level as $item) {
                            if ($item['id'] === 0) {
                                $empty_index--;
                                $level_brunches_id[$i][$empty_index] = array();
                            } else {
                                if (isset($item['childs'])) {
                                    $current_childs = isset($level_brunches_id[$i][$item['id']]) ? $level_brunches_id[$i][$item['id']] : array();
                                    $level_brunches_id[$i][$item['id']] = array_merge($current_childs, $item['childs']);
                                } else {
                                    $level_brunches_id[$i][$item['id']] = array();
                                }
                                $level_brunches_id[$i][$item['id']] = array_unique($level_brunches_id[$i][$item['id']]);
                            }
                        }
                    }

                    // TODO: сортируем по childs

                    // заполняем названиями
                    //echo "<p><b>branches_info</b><pre>";print_r($branches_info);echo "</pre></p>";

                    foreach ($level_brunches_id as $i => $level) {
                        foreach ($level as $id => $childs) {
                            if (!empty($id)) {
                                $level_brunches_names[$i][$id]['name'] = career_plan_get_job_name(
                                    $aoe,
                                    $category,
                                    $id
                                );
                            }
                            $level_brunches_names[$i][$id]['child'] = $childs;
                        }
                    }

                }
            }

            $branche_names = [];
            foreach ($branches as $id => $item) {
                foreach ($item as $_item) {
                    if (!empty($_item)) {
                        $branche_names[$id][] = career_plan_get_job_name(
                            $aoe,
                            $category,
                            $_item
                        );
                    }
                }
            }

            $pln = count($branche_names) > 1;

            if (true) {
                if (count($level_brunches_names)) {
                    foreach ($level_brunches_names[(count($level_brunches_names) - 1)] as $i => $item) {
                        $level_brunches_names[(count($level_brunches_names) - 1)][$i]['child'] = array(999999999999);
                    }
                }

                $level_brunches_names[][999999999999] = array(
                    'name'  => array(
                        'title'    => '',
                        'subtitle' => 'В данный момент есть несколько способов продолжить вашу карьеру.<br><br>
                                        <a href="//hays.ru/search/" target="_blank">Посмотреть предложения</a>',
                    ),
                    'child' => array()
                );

                ob_start();

                ?>
                <div class="career__list flex">
                    <? foreach ($level_brunches_names as $lev_num => $level) : $lev_num++; ?>
                        <div class="career__item flex center">
                            <? foreach ($level as $id => $item) : ?>
                                <? if ($id > 0): ?>
                                    <div class="career__cell">
                                        <div class="career__step<?php if( $current_job == $id ) { echo " current_position";}?>" id="<?php echo $id; ?>" data-arrows='["<? if (isset($item['child']) && count($item['child'])) {
                                            echo implode('","', $item['child']);
                                        } ?>"]'>
                                            <p class="career__name"><?php
                                                echo $item['name']['title'].'<span>'.$item['name']['subtitle'].'</span>';
                                                $next_lev = career_getLevelById($level_brunches_names, $item['child'][0]);
                                                ?></p>
                                            <div class="career__next">
                                                <? if ($id != 999999999999): ?>
                                                <span class="career__next_label">След. ступень <?php echo $next_lev['id']; ?></span>
                                                <? endif; ?>
                                                <span class="career__next_name"><?php echo $next_lev['name']['title'].'<span>'.$next_lev['name']['subtitle']; ?></span></span>
                                                <? if ($id != 999999999999): ?>
                                                <a class="btn" href="//hays.ru/search/?text=<?php echo $next_lev['name']['title']; ?>">Смотреть вакансии</a>
                                                <? endif; ?>
                                            </div>
                                            <span class="career__label flex middle"><span class="icon_user"></span>Ступень <?php echo $lev_num; ?><span class="icon_arrow_down2"></span></span>
                                        </div>
                                    </div>
                                <? else: ?>
                                    <div class="career__cell">
                                        <div class="career__step career__step--empty"></div>
                                    </div>
                                <? endif; ?>
                            <? endforeach; ?>
                        </div>
                    <? endforeach; ?>
                </div>
                <?

                // старый способ
                if (false) {
                    $current_job_name = career_get_job_name($current_job);
                    $branche_map = $branche_sort = array();
                    $across_level = 0;
                    $max_level = 0;
                    foreach ($branche_names as $branche_id => $branche) {
                        $branche_map[$branche_id]['count_level'] = count($branche);
                        $max_level = max($max_level, count($branche));
                        foreach ($branche as $level => $item) {
                            if ($item['title'] == $current_job_name['title'] && $item['subtitle'] == $current_job_name['subtitle']) {
                                $branche_map[$branche_id]['across_level'] = $level;
                                $across_level = max($across_level, $level);
                            }
                        }
                    }

                    // Самая длинная ветка всегда вторая
                    foreach ($branche_map as $branche_id => $item) {
                        if ($item['count_level'] == $max_level) {
                            $branche_sort[1] = $branche_id;
                        } elseif ( ! isset($branche_sort[1])) {
                            $branche_sort[0] = $branche_id;
                        }
                    }
                    foreach ($branche_map as $branche_id => $item) {
                        if ( ! in_array($branche_id, $branche_sort)) {
                            $branche_sort[] = $branche_id;
                        }
                    }

                    $branche_view = array();
                    foreach ($branche_sort as $row => $branche_id) {
                        for ($i = 0; $i < ($across_level - $branche_map[$branche_id]['across_level']); $i++) {
                            $branche_view[$row][] = array();
                        }

                        $level = 0;
                        foreach ($branche_names[$branche_id] as $item) {
                            if ($row == 1) {
                                $item['direct'] = 'right';
                            } elseif ($row == 0) {
                                if ($level < $branche_map[$branche_id]['across_level']) {
                                    if (($level + 1) == $branche_map[$branche_id]['across_level']) {
                                        $item['direct'] = 'down';
                                    } else {
                                        $item['direct'] = 'right';
                                    }
                                }
                            } else {
                                if ($level < $branche_map[$branche_id]['across_level']) {
                                    if (($level + 1) == $branche_map[$branche_id]['across_level']) {
                                        $item['direct'] = 'up';
                                    } else {
                                        $item['direct'] = 'right';
                                    }
                                }
                            }

                            if ($level < $branche_map[$branche_id]['across_level'] || $row == 1) {
                                if ($item['title'] == $current_job_name['title'] && $item['subtitle'] == $current_job_name['subtitle']) {
                                    $item['current'] = 1;
                                }
                                $branche_view[$row][] = $item;
                            }
                            $level++;
                        }
                    }

                    $branche_view[1][] = array(
                        'title'    => '',
                        'subtitle' => 'В данный момент есть несколько способов продолжить вашу карьеру.<br><br>
                                        <a href="//hays.ru/search/" target="_blank">Посмотреть предложения</a>'
                    );

                    ob_start();
                    ?>
					<div id="branche-wrapper">
                        <? foreach ($branche_view as $branche) : ?>
							<div class="branche-row">
                                <? foreach ($branche as $pos): ?>
									<div class="cell<? if (isset($pos['current'])) {
                                        echo " current";
                                    } ?><? if (isset($pos['direct'])) {
                                        echo " direct direct-".$pos['direct'];
                                    } ?><? if (isset($pos['title'])) {
                                        echo " not-empty";
                                    } ?>">
                                        <? if (isset($pos['title'])): ?>
											<h3><?= $pos['title']; ?></h3>
											<p><?= $pos['subtitle']; ?></p>
                                            <? if ( ! empty($pos['title'])): ?>
												<p><a href="//hays.ru/search/?text=<?= $pos['title']; ?>" target="_blank">Вакансии</a></p>
                                            <? endif; ?>
                                        <? endif; ?>
									</div>
                                <? endforeach; ?>
							</div>
                        <? endforeach; ?>
					</div>
                    <?
                }

            } else {
                $_POST['branch'] = array_shift($branche_names);
                echo do_shortcode('[career-plan-widget]');
            }

    }
    die;
}

function career_getLevelById($source, $id)
{
    foreach ($source as $lev => $item) {
        $lev++;
        foreach ($item as $_id => $info) {
            if( $id == $_id ) {
                return [
                    'id' => $lev,
                    'name' => $info['name']
                ];
            }
        }
    }

    return [ 'id' => '', 'name' => ['title' => '', 'subtitle' => ''] ];
}

function career_get_job_name($map_id)
{
    global $wpdb;
    $name = [
        'title'    => '',
        'subtitle' => ''
    ];

    $res = $wpdb->get_results("SELECT t2.`name` `job_name`, t3.`name` `type_name` 
                                FROM `wp_career_map` t1, `wp_career_job` t2, `wp_career_orgtype` t3
                                WHERE t1.`id` = ".$map_id." AND t1.`job` = t2.`id` AND t1.`orgtype` = t3.`id`");

    if (isset($res[0])) {
        $name = [
            'title'    => $res[0]->job_name,
            'subtitle' => $res[0]->type_name
        ];
    }

    return $name;
}


function career_plan_get_job_name($sector, $category, $job_id)
{
    global $wpdb;
    $name = [
        'title'    => '',
        'subtitle' => ''
    ];

    $res = $wpdb->get_results("SELECT t2.`id` , t2.`name` `job_name`, t3.`name` `orgtype_name`
                                        FROM `wp_career_plan_map` t1, `wp_career_plan_job` t2, `wp_career_plan_orgtype` t3
                                        WHERE t1.`job` = ".$job_id."
                                        AND t1.`aoe` = ".$sector." AND  t1.`category` = ".$category." 
                                        AND t1.`job` = t2.id
                                        AND t1.`orgtype` = t3.id");

    if (isset($res[0])) {
        $name = [
            'title'    => $res[0]->job_name,
            'subtitle' => $res[0]->orgtype_name
        ];
    }

    return $name;
}

function career_get_branch($item)
{
    global $wpdb;

    $branch_up =
    $branches_up =
    $branch_down =
    $queues =
    $queues_parent =
    $branch_info =
    $branch = [];

    $_item = $item;
    $branch_up[] = $_item['job'];

    $branch_info[$_item['job']] = [
        'job' => $_item['job'],
        'aoe' => $_item['aoe'],
        'category' => $_item['category']
    ];

    do {
        if (count($queues)) {
            $branch_up = array_shift($queues);
            $_item['parent'] = array_shift($queues_parent);
        }

        do {
            $res = $wpdb->get_results("SELECT `job_id` job, `parent`, `aoe_id` aoe, `category_id` category FROM `wp_career_plans`
                                                WHERE `aoe_id` = ".$_item['aoe']." AND `category_id` =  ".$_item['category']." AND `job_id` = ".$_item['parent']);

            if (isset($res[0])) {
                $_item = [
                    "aoe"    => $res[0]->aoe,
                    "category"    => $res[0]->category,
                    "job"    => $res[0]->job,
                    "parent" => $res[0]->parent
                ];

                if (count($res) > 1) {
                    $new_brunch = $branch_up;
                    $j = count($queues);
                    for ($i = 1; $i < count($res); $i++) {
                        $queues[$j] = $new_brunch;
                        $queues[$j][] = $res[$i]->job;
                        $queues_parent[$j] = $res[$i]->parent;
                        $j++;
                    }
                }

                $branch_up[] = $_item['job'];
            }
        } while (isset($res[0]) && $item['parent'] != 0);

        $branches_up[] = $branch_up;
    } while (count($queues));

    /*
        $_item = $item;
    do {
        $res = $wpdb->get_results( "SELECT `job_id` job,`parent` FROM `wp_career_plans`
                                            WHERE `parent` = ".$_item['job']);

        if(isset($res[0])) {
            $_item = [
                "job" => $res[0]->job,
                "parent" => $res[0]->parent
            ];

            if($_item['job']) {
                $branch_down[] = $_item['job'];
            }
        }

    } while(isset($res[0])&&$_item['job']);
    */

    //--- down
    $queues =
    $branches_down =
    $branch_down =
    $queues_job = [];

    $_item = $item;

    do {
        if (count($queues)) {
            $branch_down = array_shift($queues);
            $_item['job'] = array_shift($queues_job);
        }

        do {
            $res = $wpdb->get_results("SELECT `job_id` job,`parent`, `aoe_id` aoe, `category_id` category 
                                                FROM `wp_career_plans`
                                                WHERE `aoe_id` = ".$_item['aoe']." AND `category_id` =  ".$_item['category']." AND  `parent` = ".$_item['job']);

            if (isset($res[0])) {
                $_item = [
                    "aoe"    => $res[0]->aoe,
                    "category"  => $res[0]->category,
                    "job"    => $res[0]->job,
                    "parent" => $res[0]->parent
                ];

                if (count($res) > 1) {
                    $new_brunch = $branch_down;
                    $j = count($queues);
                    for ($i = 1; $i < count($res); $i++) {
                        $queues[$j] = $new_brunch;
                        $queues[$j][] = $res[$i]->job;
                        $queues_job[$j] = $res[$i]->job;
                        $j++;
                    }
                }

                $branch_down[] = $_item['job'];
            }
        } while (isset($res[0]));

        $branches_down[] = $branch_down;
    } while (count($queues));

    $result = [];
    foreach ($branches_up as $item__up) {
        $item__up = array_reverse($item__up);
        foreach ($branches_down as $item__down) {
            $result[] = array_merge($item__up, $item__down);
        }
    }

    return $result;
}

function career_get_percent($cnt, $body)
{
    $per = $cnt / $body * 100;
    if ($per > 100) {
        $per = 100;
    }

    return $per;
}

function career_set_k($cnt)
{
    return round( $cnt/1000 ).'K';
}

function career_get_sector_info($sector_id)
{
    global $wpdb;
    static $info = [];

    if (isset($info[$sector_id]) && count($info[$sector_id])) {
        return $info[$sector_id];
    }

    $res = $wpdb->get_results("SELECT * FROM `wp_career_sector_info` WHERE `sector_id` = ".$sector_id);
    $res_obj = $res[0];

    $info[$res_obj->sector_id] = [
        'positions'  => $res_obj->positions,
        'tendencies' => $res_obj->tendencies,
        'balance'    => $res_obj->balance,
        'bar_plus'   => $res_obj->bar_plus,
        'bar_minus'  => $res_obj->bar_minus
    ];

    return $info[$sector_id];
}

function career_resume_get_html($template, $photo_url) {
    $html = [];
    $dir_upload = wp_get_upload_dir();
    $page_num = 1;

    switch( $template ) {
        case "1":
            $page = [
                '' => ['covering_letter','position','about','university','company'],
                '2' => ['awards','course_name','skill']
            ];

            foreach ($page as $templ => $fields) {
                if( career_resume_check_fields($fields) ) {
                    $html[] = career_resume_get_page_html($dir_upload['basedir'].'/resume/template_1/index'.$templ.'.html', $photo_url, $template, $page_num);
                    $page_num++;
                }
            }
            break;
        case "2":
            $page = [
                '' => ['covering_letter','university','company'],
                '2' => ['awards','course_name','skill']
            ];
            foreach ($page as $templ => $fields) {
                if( career_resume_check_fields($fields) ) {
                    $html[] = career_resume_get_page_html($dir_upload['basedir'].'/resume/template_2/index'.$templ.'.html', $photo_url, $template, $page_num);
                    $page_num++;
                }
            }
            break;
        case "3":
            $page = [
                '' => ['covering_letter','about','university','company'],
                '2' => ['awards','course_name','skill']
            ];
            foreach ($page as $templ => $fields) {
                if( career_resume_check_fields($fields) ) {
                    $html[] = career_resume_get_page_html($dir_upload['basedir'].'/resume/template_3/index'.$templ.'.html', $photo_url, $template, $page_num);
                    $page_num++;
                }
            }
            break;
    }

    return $html;

}

function career_resume_get_html_simple($template, $photo_url) {
    $html = [];
    $dir_upload = wp_get_upload_dir();
    $page_num = 1;

    $html[] = career_resume_get_page_html($dir_upload['basedir'].'/resume/template_'.$template.'/main.html', $photo_url, $template, $page_num);

    return $html;

}

function career_resume_check_fields($fields)
{
    $not_empty = false;
     foreach ($fields as $field) {
         if( isset($_POST[$field]) ) {
             if (is_array($_POST[$field])) {
                 $not_empty = !empty($_POST[$field][0]);
             } else {
                $not_empty = !empty($_POST[$field]);
             }
         }
     }
     return $not_empty;
}

function career_resume_get_page_html($file_templ, $photo_url, $temp_num, $page_num)
{
    $html =  file_get_contents($file_templ);
    $func = 'career_resume_'.$temp_num.'_get_elem';

    foreach( $_POST as $name => $val ) {
        if( strpos($html, '['.$name.']') !== false ) {
            $html_elem = $func($name, $val, $_POST);

            if( !empty($html_elem) ) {
                $html = str_replace('['.$name.']', $html_elem, $html);
            }
        }
    }
    $html = str_replace('[photo]', $func('photo', $photo_url), $html);
    $html = str_replace('[url]', get_home_url(), $html);
    $html = str_replace('[page_num]', $page_num, $html);

    return preg_replace('/\[(.+?)\]/xis', '', $html);
}

function career_resume_1_get_elem($name, $val, $source = []) {
    $html = '';

    if( empty($val) ) return $html;
    if( is_array($val) && count($val) == 1 && empty($val[0])  ) return $html;

    switch ($name) {
        case 'photo':
            $html = '<div class="left__foto" style="background-image: url('.$val.');"></div>';
            break;
        case 'phone':
            $html = '
                <li>
                  <div class="left__info_label">Телефон</div>
                  <div>'.sanitize_text_field($val).'</div>
                </li>
            ';
            break;
        case 'email':
            $html = '
                <li>
                  <div class="left__info_label">Эл. почта</div>
                  <div>'.sanitize_text_field($val).'</div>
                </li>
            ';
            break;
        case 'city':
            $html = '
                <li>
                  <div class="left__info_label">Город</div>
                  <div>'.sanitize_text_field($val).'</div>
                </li>
            ';
            break;
        case 'birth':
            $html = '
                <li>
                  <div class="left__info_label">Дата рождения</div>
                  <div>'.str_replace('/','.',sanitize_text_field($val)).'</div>
                </li>
            ';
            break;
        case 'language':
            if( is_array( $val ) ) {
                $html = '<ul class="left__lang">';
                foreach ($val as $i => $item) {
                    $html .= '
                        <li>
                            <div class="left__lang_name">'.sanitize_text_field($item).'</div>
                    ';
                    if( isset( $source['language_level'][$i] ) && !empty( $source['language_level'][$i] ) ) {
                        $html .= '<div class="left__lang_level">'.$source['language_level'][$i].'</div>';
                    }
                    if( isset( $source['language_description'][$i] ) && !empty( $source['language_description'][$i] ) ) {
                        $html .= '<div>'.$source['language_description'][$i].'</div>';
                    }
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
            break;
        case 'surname':
        case 'name':
        case 'middle_name':
            $html = career_mb_ucfirst(mb_strtolower(sanitize_text_field($val)));
            break;
        case 'position':
            $html = sanitize_text_field($val);
            break;
        case 'university':
            if( is_array( $val ) ) {
                $html = '
                    <div class="right__info">
                    <h2 class="right__info_title">Образование</h2>
                    <table class="right__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="right__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="right__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="right__table_list">
                    ';

                    if( isset( $source['academic_degree'][$i] ) && !empty( $source['academic_degree'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Академ. степень:</span> <strong>'.sanitize_text_field($source['academic_degree'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['faculty'][$i] ) && !empty( $source['faculty'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Факультет:</span> <strong>'.sanitize_text_field($source['faculty'][$i]).'</strong></td></tr>';
                    }


                    if( isset( $source['specialization'][$i] ) && !empty( $source['specialization'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Cпециализация:</span> <strong>'.sanitize_text_field($source['specialization'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['gpa'][$i] ) && !empty( $source['gpa'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Средний балл:</span> <strong>'.sanitize_text_field($source['gpa'][$i]).'</strong></td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                        <td class="right__table_year">'.((isset($source['year_entry'][$i]))?sanitize_text_field($source['year_entry'][$i]).' — '.sanitize_text_field($source['year_ending'][$i]):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'company':
            if( is_array( $val ) ) {
                $html = '
                    <div class="right__info">
                    <h2 class="right__info_title">Опыть работы</h2>
                    <table class="right__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="right__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="right__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="right__table_list">
                    ';

                    if( isset( $source['company_position'][$i] ) && !empty( $source['company_position'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Должность:</span> <strong>'.sanitize_text_field($source['company_position'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['department'][$i] ) && !empty( $source['department'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Отдел:</span> <strong>'.sanitize_text_field($source['department'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['responsibilities'][$i] ) && !empty( $source['responsibilities'][$i] ) ) {
                        $html .= '<tr><td>'.sanitize_text_field($source['responsibilities'][$i]).'</td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                        <td class="right__table_year_long">'.((isset($source['started'][$i]))?sanitize_text_field(str_replace('/','.', $source['started'][$i])).' — '.((isset($source['ending'][$i]))?sanitize_text_field(str_replace('/','.', $source['ending'][$i])):'по н.в.'):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'awards':
            if( is_array( $val ) ) {
                $html = '
                    <div class="right__info">
                    <h2 class="right__info_title">Достижения</h2>
                    <table class="right__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="right__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="right__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="right__table_list">
                    ';

                    if( isset( $source['awards_responsibilities'][$i] ) && !empty( $source['awards_responsibilities'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">'.sanitize_text_field($source['awards_responsibilities'][$i]).'</span></td></tr>';
                    }


                    $html .= '
                            </table>
                        </td>
                        <td class="right__table_year">'.((isset($source['awards_year'][$i]))?sanitize_text_field($source['awards_year'][$i]):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'course_name':
            if( is_array( $val ) ) {
                $html = '
                    <div class="right__info">
                    <h2 class="right__info_title">Курсы</h2>
                    <table class="right__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="right__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="right__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="right__table_list">
                    ';

                    if( isset( $source['school_courses'][$i] ) && !empty( $source['school_courses'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Название школы / площадки:</span> <strong>'.sanitize_text_field($source['school_courses'][$i]).'</strong></td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                        <td class="right__table_year">'.((isset($source['course_year'][$i]))?sanitize_text_field($source['course_year'][$i]):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'skill':
            if( is_array( $val ) ) {
                $html = '
                    <div class="right__info">
                    <h2 class="right__info_title">Профессиональные навыки</h2>
                    <table class="right__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="right__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="right__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="right__table_list">
                    ';

                    if( isset( $source['skill_level'][$i] ) && !empty( $source['skill_level'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Уровень владения:</span> <strong>'.sanitize_text_field($source['skill_level'][$i]).'</strong></td></tr>';
                    }

/*
                    if( isset( $source['skill_description'][$i] ) && !empty( $source['skill_description'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">'.sanitize_text_field($source['skill_description'][$i]).'</span></td></tr>';
                    }

*/
                    $html .= '
                            </table>
                        </td>
                    </tr>                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'covering_letter':
            $html = '
                <div class="right__info">
                    <h2 class="right__info_title">Дополнительная информация</h2>
                    <div class="right__letter">'.sanitize_text_field($val).'</div>
                </div>
            ';
            break;
        case 'about':
            $html = '
              <div class="right__about">
                <div class="right__about_label">О себе</div>
                <div>'.sanitize_text_field($val).'</div>
              </div>            
            ';
            break;
    }

    return $html;
}

function career_resume_2_get_elem($name, $val, $source = []) {
    $html = '';
    $url = '';

    if( empty($val) ) return $html;
    if( is_array($val) && count($val) == 1 && empty($val[0])  ) return $html;

    switch ($name) {
        case 'photo':
            $html = '
                  <div class="header__foto">
                    <img src="'.$val.'" alt="">
                  </div>';
            break;
        case 'phone':
            $html = '
              <div class="header__contacts_item">
                <img src="'.$url.'wp-content/uploads/resume/template_2/assets/img/phone.svg" alt="">
                <span>'.sanitize_text_field($val).'</span>
              </div>
            ';
            break;
        case 'email':
            $html = '
                  <div class="header__contacts_item">
                    <img src="'.$url.'wp-content/uploads/resume/template_2/assets/img/email.svg" alt="">
                    <span>'.sanitize_text_field($val).'</span>
                  </div>
            ';
            break;
        case 'city':
            $html = '
                  <li>
                    <div class="right__info_label">Город</div>
                    <div><strong>'.sanitize_text_field($val).'</strong></div>
                  </li>
            ';
            break;
        case 'birth':
            $html = '
              <li>
                <div class="right__info_label">Дата рождения</div>
                <div><strong>'.str_replace('/','.',sanitize_text_field($val)).'</strong></div>
              </li>
            ';
            break;
        case 'language':
            if( is_array( $val ) ) {
                $html = '<ul class="right__lang">';
                foreach ($val as $i => $item) {
                    $html .= '
                        <li>
                            <div><strong>'.sanitize_text_field($item).'</strong></div>
                    ';
                    if( isset( $source['language_level'][$i] ) && !empty( $source['language_level'][$i] ) ) {
                        $html .= '<div class="right__lang_level">'.$source['language_level'][$i].'</div>';
                    }
                    if( isset( $source['language_description'][$i] ) && !empty( $source['language_description'][$i] ) ) {
                        $html .= '<div>'.$source['language_description'][$i].'</div>';
                    }
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
            break;
        case 'surname':
        case 'name':
        case 'middle_name':
            $html = career_mb_ucfirst(mb_strtolower(sanitize_text_field($val)));
            break;
        case 'position':
            $html = sanitize_text_field($val);
            break;
        case 'university':
            if( is_array( $val ) ) {
                $html = '
                <div class="left__info">
                  <h2 class="left__info_title">Образование</h2>
                  <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['academic_degree'][$i] ) && !empty( $source['academic_degree'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Академ. степень:</span> <strong>'.sanitize_text_field($source['academic_degree'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['faculty'][$i] ) && !empty( $source['faculty'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Факультет:</span> <strong>'.sanitize_text_field($source['faculty'][$i]).'</strong></td></tr>';
                    }


                    if( isset( $source['specialization'][$i] ) && !empty( $source['specialization'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Cпециализация:</span> <strong>'.sanitize_text_field($source['specialization'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['gpa'][$i] ) && !empty( $source['gpa'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Средний балл:</span> <strong>'.sanitize_text_field($source['gpa'][$i]).'</strong></td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                        <td class="left__table_year">'.((isset($source['year_entry'][$i]))?sanitize_text_field($source['year_entry'][$i]).' — '.sanitize_text_field($source['year_ending'][$i]):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'company':
            if( is_array( $val ) ) {
                $html = '
                    <div class="left__info">
                    <h2 class="left__info_title">Опыть работы</h2>
                    <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['company_position'][$i] ) && !empty( $source['company_position'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Должность:</span> <strong>'.sanitize_text_field($source['company_position'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['department'][$i] ) && !empty( $source['department'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Отдел:</span> <strong>'.sanitize_text_field($source['department'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['responsibilities'][$i] ) && !empty( $source['responsibilities'][$i] ) ) {
                        $html .= '<tr><td>'.sanitize_text_field($source['responsibilities'][$i]).'</td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                        <td class="left__table_year_long">'.((isset($source['started'][$i]))?sanitize_text_field(str_replace('/','.', $source['started'][$i])).' — '.((isset($source['ending'][$i]))?sanitize_text_field(str_replace('/','.', $source['ending'][$i])):'по н.в.'):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'awards':
            if( is_array( $val ) ) {
                $html = '
                    <div class="left__info">
                    <h2 class="left__info_title">Достижения</h2>
                    <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['awards_responsibilities'][$i] ) && !empty( $source['awards_responsibilities'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">'.sanitize_text_field($source['awards_responsibilities'][$i]).'</span></td></tr>';
                    }


                    $html .= '
                            </table>
                        </td>
                        <td class="left__table_year">'.((isset($source['awards_year'][$i]))?sanitize_text_field($source['awards_year'][$i]):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'course_name':
            if( is_array( $val ) ) {
                $html = '
                    <div class="left__info">
                    <h2 class="left__info_title">Курсы</h2>
                    <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['school_courses'][$i] ) && !empty( $source['school_courses'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Название школы / площадки:</span> <strong>'.sanitize_text_field($source['school_courses'][$i]).'</strong></td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                        <td class="left__table_year">'.((isset($source['course_year'][$i]))?sanitize_text_field($source['course_year'][$i]):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'skill':
            if( is_array( $val ) ) {
                $html = '
                    <div class="left__info">
                    <h2 class="left__info_title">Профессиональные навыки</h2>
                    <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['skill_level'][$i] ) && !empty( $source['skill_level'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Уровень владения:</span> <strong>'.sanitize_text_field($source['skill_level'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['skill_description'][$i] ) && !empty( $source['skill_description'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">'.sanitize_text_field($source['skill_description'][$i]).'</span></td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                    </tr>                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'covering_letter':
            $html = '
                <div class="left__info">
                    <h2 class="left__info_title">Дополнительная информация</h2>
                    <div class="left__letter">'.sanitize_text_field($val).'</div>
                </div>
            ';
            break;
        case 'about':
            $html = '
          <li>
            <div class="right__info_label">О себе</div>
            <div>'.sanitize_text_field($val).'</div>
          </li>           
            ';
            break;
    }

    return $html;
}

function career_resume_3_get_elem($name, $val, $source = []) {
    $html = '';
    $url = '';

    if( empty($val) ) return $html;
    if( is_array($val) && count($val) == 1 && empty($val[0])  ) return $html;

    switch ($name) {
        case 'photo':
            $html = '<div class="header__foto" style="background-image: url('.$val.');"></div>';
            break;
        case 'phone':
            $html = '
                <div class="right__contacts_item">
                  <img src="'.$url.'wp-content/uploads/resume/template_3/assets/img/phone.svg" alt="">
                  <span>'.sanitize_text_field($val).'</span>
                </div>
            ';
            break;
        case 'email':
            $html = '
                <div class="right__contacts_item">
                  <img src="'.$url.'wp-content/uploads/resume/template_3/assets/img/email.svg" alt="">
                  <span>'.sanitize_text_field($val).'</span>
                </div>
            ';
            break;
        case 'city':
            $html = '
                <li>
                  <div class="right__info_label">Город</div>
                  <div>'.sanitize_text_field($val).'</div>
                </li>
            ';
            break;
        case 'birth':
            $html = '
                <li>
                  <div class="right__info_label">Дата рождения</div>
                  <div>'.str_replace('/','.',sanitize_text_field($val)).'</div>
                </li>
            ';
            break;
        case 'language':
            if( is_array( $val ) ) {
                $html = '<ul class="right__lang">';
                foreach ($val as $i => $item) {
                    $html .= '
                        <li>
                            <div><strong>'.sanitize_text_field($item).'</strong></div>
                    ';
                    if( isset( $source['language_level'][$i] ) && !empty( $source['language_level'][$i] ) ) {
                        $html .= '<div class="right__lang_level">'.$source['language_level'][$i].'</div>';
                    }
                    if( isset( $source['language_description'][$i] ) && !empty( $source['language_description'][$i] ) ) {
                        $html .= '<div>'.$source['language_description'][$i].'</div>';
                    }
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
            break;
        case 'surname':
        case 'name':
        case 'middle_name':
            $html = career_mb_ucfirst(mb_strtolower(sanitize_text_field($val)));
            break;
        case 'position':
            $html = sanitize_text_field($val);
            break;
        case 'university':
            if( is_array( $val ) ) {
                $html = '
                <div class="left__info">
                  <h2 class="left__info_title">Образование</h2>
                  <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['academic_degree'][$i] ) && !empty( $source['academic_degree'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Академ. степень:</span> <strong>'.sanitize_text_field($source['academic_degree'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['faculty'][$i] ) && !empty( $source['faculty'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Факультет:</span> <strong>'.sanitize_text_field($source['faculty'][$i]).'</strong></td></tr>';
                    }


                    if( isset( $source['specialization'][$i] ) && !empty( $source['specialization'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Cпециализация:</span> <strong>'.sanitize_text_field($source['specialization'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['gpa'][$i] ) && !empty( $source['gpa'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Средний балл:</span> <strong>'.sanitize_text_field($source['gpa'][$i]).'</strong></td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                        <td class="left__table_year">'.((isset($source['year_entry'][$i]))?sanitize_text_field($source['year_entry'][$i]).' — '.sanitize_text_field($source['year_ending'][$i]):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'company':
            if( is_array( $val ) ) {
                $html = '
                    <div class="left__info">
                    <h2 class="left__info_title">Опыть работы</h2>
                    <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['company_position'][$i] ) && !empty( $source['company_position'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Должность:</span> <strong>'.sanitize_text_field($source['company_position'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['department'][$i] ) && !empty( $source['department'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Отдел:</span> <strong>'.sanitize_text_field($source['department'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['responsibilities'][$i] ) && !empty( $source['responsibilities'][$i] ) ) {
                        $html .= '<tr><td>'.sanitize_text_field($source['responsibilities'][$i]).'</td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                        <td class="left__table_year_long">'.((isset($source['started'][$i]))?sanitize_text_field(str_replace('/','.', $source['started'][$i])).' — '.((isset($source['ending'][$i]))?sanitize_text_field(str_replace('/','.', $source['ending'][$i])):'по н.в.'):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'awards':
            if( is_array( $val ) ) {
                $html = '
                    <div class="left__info">
                    <h2 class="left__info_title">Достижения</h2>
                    <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['awards_responsibilities'][$i] ) && !empty( $source['awards_responsibilities'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">'.sanitize_text_field($source['awards_responsibilities'][$i]).'</span></td></tr>';
                    }


                    $html .= '
                            </table>
                        </td>
                        <td class="left__table_year">'.((isset($source['awards_year'][$i]))?sanitize_text_field($source['awards_year'][$i]):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'course_name':
            if( is_array( $val ) ) {
                $html = '
                    <div class="left__info">
                    <h2 class="left__info_title">Курсы</h2>
                    <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['school_courses'][$i] ) && !empty( $source['school_courses'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Название школы / площадки:</span> <strong>'.sanitize_text_field($source['school_courses'][$i]).'</strong></td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                        <td class="left__table_year">'.((isset($source['course_year'][$i]))?sanitize_text_field($source['course_year'][$i]):'').'</td>
                    </tr>
                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'skill':
            if( is_array( $val ) ) {
                $html = '
                    <div class="left__info">
                    <h2 class="left__info_title">Профессиональные навыки</h2>
                    <table class="left__table">
                ';
                foreach ($val as $i => $item) {
                    $html .= '
                        <tr>
                            <td class="left__table_number">'.($i+1).'.</td>
                            <td>
                              <strong class="left__table_title">'.sanitize_text_field($item).'</strong>
                              <table class="left__table_list">
                    ';

                    if( isset( $source['skill_level'][$i] ) && !empty( $source['skill_level'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">Уровень владения:</span> <strong>'.sanitize_text_field($source['skill_level'][$i]).'</strong></td></tr>';
                    }

                    if( isset( $source['skill_description'][$i] ) && !empty( $source['skill_description'][$i] ) ) {
                        $html .= '<tr><td><span style="color: #808080;">'.sanitize_text_field($source['skill_description'][$i]).'</span></td></tr>';
                    }

                    $html .= '
                            </table>
                        </td>
                    </tr>                    
                    ';

                }
                $html .= '
                        </table>
                    </div>
                ';
            }
            break;
        case 'covering_letter':
            $html = '
              <div class="left__info">
                <h2 class="left__info_title">Дополнительная информация</h2>
                <div class="left__letter">'.sanitize_text_field($val).'</div>
              </div>
            ';
            break;
        case 'about':
            $html = '
              <div class="about">
                <div class="about__qoute"><img src="'.$url.'wp-content/uploads/resume/template_3/assets/img/qoute.svg" alt=""></div>
                <div>'.sanitize_text_field($val).'</div>
              </div>            
            ';
            break;
    }

    return $html;
}

function career_mb_ucfirst($str) {
    $fc = mb_strtoupper(mb_substr($str, 0, 1));
    return $fc.mb_substr($str, 1);
}

function career_plan_get_maxplan()
{
    global $wpdb;
    $res = $wpdb->get_results("SELECT MAX(`plan_id`) plan FROM `wp_career_plans`");
    echo (int)$res[0]->plan;
    die;
}