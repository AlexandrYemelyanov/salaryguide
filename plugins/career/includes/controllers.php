<?php
function career_process_import()
{
    $_SESSION['career']['error'] = "";
    $_SESSION['career']['success'] = "";

    $page = '';
    $func = '';
    switch($_POST['import']) {
        case 'barometr':
            $page = 'career';
            $func = 'career_import';
            break;
        case 'plan':
            $page = 'career';
            $func = 'career_plan_import';
            break;
        case 'survey_worker':
        case 'survey_employer':
            $page = 'career-survey';
            $func = 'career_survey_import';
            break;
        case 'survey_conflict':
            $page = 'career-survey';
            $func = 'career_survey_conflict_import';
            break;
    }

    if (empty($func)) {
        wp_redirect( admin_url( 'admin.php') );
    }

    if(isset($_FILES['data']['name'])) {
        $user_file_name = $_FILES['data']['name'];
        $file_uloaded = CAREER_DIR.'/temp/import.xlsx';
        if(preg_match('/.(xlsx)$/',$user_file_name)) {
            if(move_uploaded_file($_FILES['data']['tmp_name'], $file_uloaded)) {
                $func($file_uloaded);
                $_SESSION['career']['success'] = "Импорт прошел успешно";
            } else {
                $_SESSION['career']['error'] = "Сбой при загрузке файла";
            }
        } else {
            $_SESSION['career']['error'] = "Формат файла должен быть xlsx";
        }
    } else {
        $_SESSION['career']['error'] = "Выберите файл";
    }

    wp_redirect( admin_url( 'admin.php?page='.$page ) );
}

function career_import($xlsx_file)
{
    global $wpdb;
    require_once (CAREER_DIR.'/classes/SimpleXLSX.php');
    $xlsx = new SimpleXLSX($xlsx_file);
    $data= $xlsx->rows();

    $aoe = $region = $category = $orgtype = $experience = $job = [];
    array_shift ( $data );
    foreach($data as $row) {
        array_walk($row,"trim");
        $aoe       [ $row[0] ] = 1;
        $region    [ $row[1] ] = 1;
        $category  [ $row[2] ] = 1;
        $orgtype   [ $row[3] ] = 1;
        $job       [ $row[4] ] = 1;
    }

    $aoe        = array_keys($aoe);
    $region     = array_keys($region);
    $category   = array_keys($category);
    $orgtype    = array_keys($orgtype);
    $experience = array_keys($experience);
    $job        = array_keys($job);

    sort($aoe);
    sort($region);
    sort($category);
    sort($orgtype);
    sort($experience);
    sort($job);

    $aoe_old        = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_aoe`" ));
    $aoe_new =
    $aoe_not_new = [];

    foreach($aoe as $item) {
        if (!isset($aoe_old[$item])) {
            $aoe_new[] = $item;
        }
    }

    foreach($aoe_old as $item=>$id) {
        if (!in_array($item,$aoe)) {
            $aoe_not_new[] = $id;
        }
    }

    if (count($aoe_not_new)) {
        $wpdb->query("DELETE FROM `wp_career_aoe` WHERE `id` IN (".implode(',',$aoe_not_new).")");
    }

    if (count($aoe_new)) {
        $wpdb->query("INSERT INTO `wp_career_aoe` (`name`) VALUES ('".implode("'),('",$aoe_new)."')");
    }

    $wpdb->query("TRUNCATE TABLE `wp_career_region`");
    $wpdb->query("TRUNCATE TABLE `wp_career_category`");
    $wpdb->query("TRUNCATE TABLE `wp_career_orgtype`");
    $wpdb->query("TRUNCATE TABLE `wp_career_job`");

    $wpdb->query("INSERT INTO `wp_career_region` (`name`) VALUES ('".implode("'),('",$region)."')");
    $wpdb->query("INSERT INTO `wp_career_category` (`name`) VALUES ('".implode("'),('",$category)."')");
    $wpdb->query("INSERT INTO `wp_career_orgtype` (`name`) VALUES ('".implode("'),('",$orgtype)."')");
    $wpdb->query("INSERT INTO `wp_career_job` (`name`) VALUES ('".implode("'),('",$job)."')");

    //--- Создаем зависимости
    // Получаем id
    $aoe        = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_aoe`" ));  // 0
    $category   = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_category`" ));   // 2
    $job        = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_job`" ));  // 4
    $orgtype    = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_orgtype`" ));   //  3
    $region     = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_region`" ));  // 1

    $aoe_category =
    $aoe_category_insert =
    $category_job =
    $category_job_insert =
    $map = [];

    foreach($data as $row) {
        $aoe_category[ $aoe[ $row[0] ] ][ $category[ $row[2] ] ] = 1;
        $category_job[ $category[ $row[2] ] ][ $job[ $row[4] ] ] = 1;

        // Job title, Organisation type, Region, Salary Minimum, Salary Typical, Salary Maximum
        $map[] = '('.$aoe[ $row[0] ].','.$category[ $row[2] ].','.$job[ $row[4] ].','.$orgtype[ $row[3] ].','.$region[ $row[1] ].','.intval($row[5]).','.intval($row[6]).','.intval($row[7]).')';
    }

    foreach($aoe_category as $i=>$item) {
        $ids = array_keys($item);
        foreach($ids as $id) {
            $aoe_category_insert[] = '('.$i.','.$id.')';
        }
    }

    foreach($category_job as $i=>$item) {
        $ids = array_keys($item);
        foreach($ids as $id) {
            $category_job_insert[] = '('.$i.','.$id.')';
        }
    }

    $wpdb->query("TRUNCATE TABLE `wp_career_aoe_category`");
    $wpdb->query("TRUNCATE TABLE `wp_career_category_job`");
    $wpdb->query("TRUNCATE TABLE `wp_career_map`");
    $wpdb->query("INSERT INTO `wp_career_aoe_category` (`aoe_id`,`category_id`) VALUES ".implode(",",$aoe_category_insert));
    $wpdb->query("INSERT INTO `wp_career_category_job` (`category_id`,`job_id`) VALUES ".implode(",",$category_job_insert));
    $wpdb->query("INSERT INTO `wp_career_map` (`aoe`,`category`,`job`,`orgtype`,`region`,`salary_min`,`salary_typ`,`salary_max`) VALUES ".implode(",",$map));

}

function career_survey_import($xlsx_file)
{
    ////////////////////////
    //--- Разбор xlsx
    ////////////////////////
    require_once (CAREER_DIR.'/classes/SimpleXLSX.php');
    $xlsx = new SimpleXLSX($xlsx_file);
    $data= $xlsx->rows();

    $row_year = array_shift ( $data );
    $year_map = [];
    foreach($row_year as $i => $el) {
        $el = trim($el);
        if (!empty($el)) {
            $year_map[$i] = $el;
        }
    }
    $map = [];
    foreach($data as $row) {
        array_walk($row,"trim");

        if (!empty($row[0])) {
            $category = $row[0];
            $survey = '';
        }
        if (!empty($row[1])) {
            $question = $row[1];
            $survey = '';
        }
        if (!empty($row[2])) {
            $survey = $row[2];
        }
        if (!empty($survey)) {
            foreach ($year_map as $i => $item) {
                $map[ $category ][ $question ][ $survey ][ $item ] = $row[ $i ];
            }
        }
    }

    ////////////////////////
    //--- Работа с базой
    ////////////////////////
    global $wpdb;

    // Определение таблиц
    $prefix = $_POST['import'] == 'survey_worker' ? 'sw' : 'se';
    $tables = [
        'category' => 'wp_career_'.$prefix.'_category',
        'question'  => 'wp_career_'.$prefix.'_question',
        'answer' => 'wp_career_'.$prefix.'_answer',
        'survey'   => 'wp_career_'.$prefix.'_survey'
    ];

    foreach ($tables as $table) {
        $wpdb->query("TRUNCATE TABLE `".$table."`");
    }

    $survey = [];
    foreach ($map as $category => $info) {
        $wpdb->insert($tables['category'], ['name' => $category], ['%s']);
        $category_id = $wpdb->insert_id;

        foreach ($info as $question => $_info) {
            $wpdb->insert($tables['question'], ['category_id' => $category_id, 'name' => $question], ['%s']);
            $question_id = $wpdb->insert_id;

            foreach ($_info as $answer => $__info) {
                $wpdb->insert($tables['answer'], ['question_id' => $question_id, 'name' => $answer], ['%s']);
                $answer_id = $wpdb->insert_id;

                foreach ($__info as $year => $percent) {
                    $survey[] = "(".$answer_id.", ".$year.", ".intval($percent).")";
                }
            }
        }
    }
    $wpdb->query("INSERT INTO `".$tables['survey']."` (`answer_id`, `year`, `percent`) VALUES ".implode(",",$survey));
}

function career_survey_conflict_import($xlsx_file)
{

    ////////////////////////
    //--- Разбор xlsx
    ////////////////////////
    require_once (CAREER_DIR.'/classes/SimpleXLSX.php');
    $xlsx = new SimpleXLSX($xlsx_file);
    $data= $xlsx->rows();

    $row_year = array_shift ( $data );
    $year_map = [];
    foreach($row_year as $i => $el) {
        $el = trim($el);
        if (!empty($el)) {
            $year_map[$i] = $el;
        }
    }

    // убираем заголовки
    array_shift ( $data );

    $map = [];
    foreach($data as $num => $row) {
      //  echo "<p><b>row</b><pre>";print_r($row);echo "</pre></p>";
        
        
        array_walk($row,"trim");

        if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
            continue;
        }

        if (!empty($row[0])) {
            $category = $row[0];
            $survey = '';
        }
        if (!empty($row[1])) {
            $question = $row[1];
            $survey = '';
        }
        if (!empty($row[2])) {
            $survey = $row[2];
        }
        if (!empty($survey)) {
            foreach ($year_map as $i => $item) {
                $map[ $category ][ $question ][ $survey ][ $item ]['soi'] = $row[ $i ] == 'na' ? -1 : $row[ $i ];
                $map[ $category ][ $question ][ $survey ][ $item ]['rab'] = $row[ $i+1 ] == 'na' ? -1 : $row[ $i+1 ];
            }
        }
    }

    ////////////////////////
    //--- Работа с базой
    ////////////////////////
    global $wpdb;

    // Определение таблиц
    $prefix = 'con';
    $tables = [
        'category' => 'wp_career_'.$prefix.'_category',
        'question'  => 'wp_career_'.$prefix.'_question',
        'answer' => 'wp_career_'.$prefix.'_answer',
        'survey'   => 'wp_career_'.$prefix.'_survey'
    ];

    foreach ($tables as $table) {
        $wpdb->query("TRUNCATE TABLE `".$table."`");
    }

    $survey = [];
    foreach ($map as $category => $info) {
        $wpdb->insert($tables['category'], ['name' => $category], ['%s']);
        $category_id = $wpdb->insert_id;

        foreach ($info as $question => $_info) {
            $wpdb->insert($tables['question'], ['category_id' => $category_id, 'name' => $question], ['%s']);
            $question_id = $wpdb->insert_id;

            foreach ($_info as $answer => $__info) {
                $wpdb->insert($tables['answer'], ['question_id' => $question_id, 'name' => $answer], ['%s']);
                $answer_id = $wpdb->insert_id;

                foreach ($__info as $year => $percent) {
                    $survey[] = "(".$answer_id.", ".$year.", ".intval($percent['soi']).", 'soi')";
                    $survey[] = "(".$answer_id.", ".$year.", ".intval($percent['rab']).", 'rab')";
                }
            }
        }
    }
    $wpdb->query("INSERT INTO `".$tables['survey']."` (`answer_id`, `year`, `percent`, `type`) VALUES ".implode(",",$survey));
}

function career_trim($str)
{
    return trim($str, " \n\r\t\v\0");
}

function career_plan_import($xlsx_file)
{
    global $wpdb;
    require_once (CAREER_DIR.'/classes/SimpleXLSX.php');
    $xlsx = new SimpleXLSX($xlsx_file);
    $data= $xlsx->rows();

    $aoe = $category = $orgtype = $job = [];
    array_shift ( $data );
    foreach($data as $row) {
        array_walk($row,"career_trim");
        $aoe       [ $row[0] ] = 1;
   //     $region    [ $row[1] ] = 1;
        $category  [ $row[1] ] = 1;
        $orgtype   [ $row[2] ] = 1;
        $job       [ $row[3] ] = 1;
    }

    $aoe        = array_keys($aoe);
 //   $region     = array_keys($region);
    $category   = array_keys($category);
    $orgtype    = array_keys($orgtype);
    $job        = array_keys($job);

    sort($aoe);
//    sort($region);
    sort($category);
    sort($orgtype);
    sort($job);

    $aoe_old = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_plan_aoe`" ));
    $aoe_new =
    $aoe_not_new = [];

    foreach($aoe as $item) {
        if (!isset($aoe_old[$item])) {
            $aoe_new[] = $item;
        }
    }

    foreach($aoe_old as $item=>$id) {
        if (!in_array($item,$aoe)) {
            $aoe_not_new[] = $id;
        }
    }

    if (count($aoe_not_new)) {
        $wpdb->query("DELETE FROM `wp_career_plan_aoe` WHERE `id` IN (".implode(',',$aoe_not_new).")");
    }

    if (count($aoe_new)) {
        $wpdb->query("INSERT INTO `wp_career_plan_aoe` (`name`) VALUES ('".implode("'),('",$aoe_new)."')");
    }

    // $wpdb->query("TRUNCATE TABLE `wp_career_plan_region`");
    $wpdb->query("TRUNCATE TABLE `wp_career_plan_category`");
    $wpdb->query("TRUNCATE TABLE `wp_career_plan_orgtype`");
    $wpdb->query("TRUNCATE TABLE `wp_career_plan_job`");


   // $wpdb->query("INSERT INTO `wp_career_plan_region` (`name`) VALUES ('".implode("'),('",$region)."')");
    $wpdb->query("INSERT INTO `wp_career_plan_category` (`name`) VALUES ('".implode("'),('",$category)."')");
    $wpdb->query("INSERT INTO `wp_career_plan_orgtype` (`name`) VALUES ('".implode("'),('",$orgtype)."')");
    $wpdb->query("INSERT INTO `wp_career_plan_job` (`name`) VALUES ('".implode("'),('",$job)."')");

    //--- Создаем зависимости
    // Получаем id
    $aoe        = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_plan_aoe`" ));  // 0
    $category   = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_plan_category`" ));   // 2
    $job        = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_plan_job`" ));  // 4
    $orgtype    = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_plan_orgtype`" ));   //  3
    //$region     = career_res_repack('name','id',$wpdb->get_results( "SELECT `id`,`name` FROM `wp_career_plan_region`" ));  // 1

    $aoe_category =
    $aoe_category_insert =
    $category_job =
    $category_job_insert =
    $map = [];

    foreach($data as $row) {
        $aoe_category[ $aoe[ $row[0] ] ][ $category[ $row[1] ] ] = 1;
        $category_job[ $category[ $row[1] ] ][ $job[ $row[3] ] ] = 1;

        $map[] = '('.$aoe[ $row[0] ].','.$category[ $row[1] ].','.$job[ $row[3] ].','.$orgtype[ $row[2] ].')';
    }

    foreach($aoe_category as $i=>$item) {
        $ids = array_keys($item);
        foreach($ids as $id) {
            $aoe_category_insert[] = '('.$i.','.$id.')';
        }
    }

    foreach($category_job as $i=>$item) {
        $ids = array_keys($item);
        foreach($ids as $id) {
            $category_job_insert[] = '('.$i.','.$id.')';
        }
    }

    $wpdb->query("TRUNCATE TABLE `wp_career_plan_aoe_category`");
    $wpdb->query("TRUNCATE TABLE `wp_career_plan_category_job`");
    $wpdb->query("TRUNCATE TABLE `wp_career_plan_map`");
    $wpdb->query("TRUNCATE TABLE `wp_career_plans`");
    $wpdb->query("INSERT INTO `wp_career_plan_aoe_category` (`aoe_id`,`category_id`) VALUES ".implode(",",$aoe_category_insert));
    $wpdb->query("INSERT INTO `wp_career_plan_category_job` (`category_id`,`job_id`) VALUES ".implode(",",$category_job_insert));
    $wpdb->query("INSERT INTO `wp_career_plan_map` (`aoe`,`category`,`job`,`orgtype`) VALUES ".implode(",",$map));

}

function career_res_repack($key,$val,$in)
{
    $out = [];
    foreach($in as $item) {
        $out[ $item->$key ] = $item->$val;
    }
    return $out;
}

function career_plan_save_controller()
{
    global $wpdb;
    $insert = $update =[];
    foreach($_POST['plan'] as $item) {
        if ($item['item']) {
            $pos = '('.$item['item'].','.$item['sector'].','.$item['category'].','.$item['id'].','.$item['parent'].','.$item['plan'].')';
            if (!in_array($pos,$update)) $update[] = $pos;
        } else {
            $pos = '('.$item['sector'].','.$item['category'].','.$item['id'].','.$item['parent'].','.$item['plan'].')';
            if (!in_array($pos,$insert)) $insert[] = $pos;
        }
    }

    if (count($update)) {
        $wpdb->query("INSERT INTO `wp_career_plans` (`id`, `aoe_id`, `category_id`, `job_id`, `parent`, `plan_id`) VALUES ".implode(",",$update)." 
        ON DUPLICATE KEY UPDATE aoe_id=VALUES(aoe_id), category_id=VALUES(category_id), job_id=VALUES(job_id), parent=VALUES(parent), plan_id=VALUES(plan_id)");
    }

    if (count($insert)) {
        echo "INSERT INTO `wp_career_plans` (`aoe_id`, `category_id`, `job_id`, `parent`, `plan_id`) VALUES ".implode(",",$insert);
        $wpdb->query("INSERT INTO `wp_career_plans` (`aoe_id`, `category_id`, `job_id`, `parent`, `plan_id`) VALUES ".implode(",",$insert));
    }

    die;
}

function career_plan_delete()
{
    global $wpdb;

    $branch_id = (int)$_POST['item'];
    if ($branch_id) {
        $res = $wpdb->get_results("SELECT `job_id` FROM `wp_career_plans` WHERE `id` = ".$branch_id);
        $res = $wpdb->get_results("SELECT `job_id` FROM `wp_career_plans` WHERE `parent` = ".$res[0]->job_id);
        if (count($res)) {
            die("0");
        }
        $wpdb->query("DELETE FROM `wp_career_plans` WHERE `id` = ".$branch_id);
        die("1");
    } else {
        die("1");
    }

}

function career_plan_branchById($parent, $id)
{
    global $wpdb;
    $out = [];

    if (!$parent) {
        return $out;
    }

    $res = $wpdb->get_results("SELECT `job_id`, `id` FROM `wp_career_plans` WHERE `parent` = ".$parent);
    foreach ($res as $row) {
        $current = [$row->job_id, $row->id];
        if ( !in_array($current, $out) ) {
            $temp = career_plan_branchById($row->job_id, $row->id);
        } else {
            $temp = [];
        }

        if (count($temp)) {
            $out = array_merge($out, $temp);
        }

        $out[] = $current;
    }
    return $out;
}

function career_comp_empl_controller()
{
    global $wpdb;

    $wpdb->query("TRUNCATE TABLE `wp_career_compempl`");
    $query = "INSERT INTO `wp_career_compempl` (`sector_id`,`up`,`qty`,`lplus`,`lminus`) VALUES ";
    $values = [];

    foreach($_POST['sector'] as $sector_id) {
        $values[] = "(".$sector_id.",'".$_POST['up'][$sector_id]."','".$_POST['qty'][$sector_id]."','".$_POST['lplus'][$sector_id]."','".$_POST['lminus'][$sector_id]."')";
    }

    $wpdb->query($query.implode(",",$values));

    $_SESSION['career']['success'] = "Данные сохранены";
    wp_redirect( admin_url( 'admin.php?page=career-comp-empl' ) );
}

function career_sector_save_controller ()
{
    global $wpdb;

    $wpdb->query("TRUNCATE TABLE `wp_career_sector_info`");
    $query = "INSERT INTO `wp_career_sector_info` (`sector_id`,`positions`,`tendencies`,`balance`,`bar_plus`,`bar_minus`) VALUES ";
    $values = [];

    foreach($_POST['sector'] as $sector_id) {
        $values[] = "(".$sector_id.",'".$_POST['positions'][$sector_id]."','".$_POST['tendencies'][$sector_id]."','".$_POST['balance'][$sector_id]."','".$_POST['bar_plus'][$sector_id]."','".$_POST['bar_minus'][$sector_id]."')";
    }

    $wpdb->query($query.implode(",",$values));

    $_SESSION['career']['success'] = "Данные сохранены";
    wp_redirect( admin_url( 'admin.php?page=career-sector' ) );
}

function career_create_resume_controller()
{
    /////////////////////////
    ///--- Запись в базу
    ////////////////////////
    global $wpdb;

    $info = array_map('sanitize_text_field', $_POST);

    $res = $wpdb->get_results("SELECT `id` 
                                FROM `wp_career_resume` 
                                WHERE `name` = '".$info['name']."' AND `surname` = '".$info['surname']."' AND `email` = '".$info['email']."'");
    if (!isset($res[0])) {
        $db_info = [
            'name' => $info['name'],
            'surname' => $info['surname'],
            'email' => $info['email'],
            'phone' => $info['phone'],
            'city' => $info['city'],
            'birth' => $info['birth'],
            'position' => $info['position']
        ];

        if( isset($info['about']) && !empty($info['about']) ) {
            $db_info['about'] = $info['about'];
        }

        if( isset($_POST['language']) && !empty($_POST['language']) ) {
            $db_info['language'] = sanitize_text_field(implode(', ', $_POST['language']));
        }

        if( isset($_POST['skill']) && !empty($_POST['skill']) ) {
            $db_info['skill'] = sanitize_text_field(implode(', ', $_POST['skill']));
        }

        $query = 'INSERT INTO `wp_career_resume` (`'.implode('`, `', array_keys($db_info)).'`) 
                             VALUES ("'.implode('", "', array_values($db_info)).'")';

        $wpdb->query($query);
    }

    //////////////////////////////
    ///--- Формирование PDF файла
    /////////////////////////////
    require_once __DIR__ . '/../vendor/autoload.php';

    $dir_upload = wp_get_upload_dir();

    $template_num = (int)(isset($_POST['resume'])?$_POST['resume']:1);
    if( $template_num < 1 || $template_num > 3 ) {
        $template_num = 1;
    }

    $photo_url = '';
    // Заливка и ресайз фото
    if( strpos( $_FILES['foto']['type'], 'image' ) !== false ) {
        $tmp = explode('.', $_FILES['foto']['name']);
        $ext = array_pop($tmp);
        $file_name_new = md5(strtotime('now')).'_'.md5($_FILES['foto']['tmp_name']).'.'.$ext;
        $full_file_name = $dir_upload['basedir'] .'/resume/'.$file_name_new;

        $image = new Gumlet\ImageResize($_FILES['foto']['tmp_name']);
        $image->quality_jpg = 100;
        $image->quality_webp = 100;
        $image->quality_png = 0;
        switch( $template_num ) {
            case "1":
                $image->resizeToWidth(192);
                break;
            case "2":
                $image->resizeToBestFit(144, 144);
                break;
            case "3":
                $image->resizeToBestFit(147, 147);
                break;
        }

        $image->save($full_file_name);
        $photo_url = 'wp-content/uploads/resume/'.$file_name_new;
        unlink($_FILES['foto']['tmp_name']);
    }

    // Подготовка HTML. Вставка динамики
    $html = career_resume_get_html_simple($template_num, $photo_url);
    if( !count($html) ) return false;

    $mpdf = new \Mpdf\Mpdf([
        'debug' => true,
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => __DIR__ . '/tmp',
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'margin_header' => 0,
        'margin_footer' => 0,
        'curlAllowUnsafeSslRequests' => true,
        'dpi' => 72
    ]);

    $mpdf->img_dpi = 72;
    $mpdf->setBasePath(get_home_url());
    $mpdf->basepathIsLocal = false;

    foreach ($html as $_html) {
        $mpdf->WriteHTML($_html);
    }

    if( $mpdf->page > 1 ) {
        unset($mpdf);

        $html = career_resume_get_html($template_num, $photo_url);
        if( !count($html) ) return false;

        $mpdf = new \Mpdf\Mpdf([
            'debug' => true,
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => __DIR__ . '/tmp',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
            'curlAllowUnsafeSslRequests' => true,
            'dpi' => 72
        ]);

        $mpdf->img_dpi = 72;
        $mpdf->setBasePath(get_home_url());
        $mpdf->basepathIsLocal = false;

        foreach ($html as $_html) {
            $mpdf->WriteHTML($_html);
        }
    }


    // F - сохранить в файл
    // I - выдать в браузер
    // D - выдать в загрузку
    $mpdf->Output('hays-cv.pdf','D');
    $tmp_file_name = __DIR__ . '/tmp/hays-cv-'.time().'.pdf';
    $mpdf->Output($tmp_file_name, 'F');

    // отправка на почту
    $attach[] = $tmp_file_name;
    wp_mail("mahteev@pervee.ru", 'salary-cv', '', [], $attach );

    unlink($tmp_file_name);
    unlink($full_file_name);
    exit;
}

function career_send_resume_controller()
{
    /////////////////////////
    ///--- Запись в базу
    ////////////////////////
    global $wpdb;

    $info = array_map('sanitize_text_field', $_POST);

    $res = $wpdb->get_results("SELECT `id` 
                                FROM `wp_career_resume` 
                                WHERE `name` = '".$info['name']."' AND `surname` = '".$info['surname']."' AND `email` = '".$info['email']."'");
    if (!isset($res[0])) {
        $db_info = [
            'name' => $info['name'],
            'surname' => $info['surname'],
            'email' => $info['email'],
            'phone' => $info['phone'],
            'city' => $info['city'],
            'birth' => $info['birth'],
            'position' => $info['position']
        ];

        if( isset($info['about']) && !empty($info['about']) ) {
            $db_info['about'] = $info['about'];
        }

        if( isset($_POST['language']) && !empty($_POST['language']) ) {
            $db_info['language'] = sanitize_text_field(implode(', ', $_POST['language']));
        }

        if( isset($_POST['skill']) && !empty($_POST['skill']) ) {
            $db_info['skill'] = sanitize_text_field(implode(', ', $_POST['skill']));
        }

        $query = 'INSERT INTO `wp_career_resume` (`'.implode('`, `', array_keys($db_info)).'`) 
                             VALUES ("'.implode('", "', array_values($db_info)).'")';

        $wpdb->query($query);
    }

    //////////////////////////////
    ///--- Формирование PDF файла
    /////////////////////////////
    require_once __DIR__ . '/../vendor/autoload.php';

    $dir_upload = wp_get_upload_dir();

    $template_num = (int)(isset($_POST['resume'])?$_POST['resume']:1);
    if( $template_num < 1 || $template_num > 3 ) {
        $template_num = 1;
    }

    $photo_url = '';
    // Заливка и ресайз фото
    if( strpos( $_FILES['foto']['type'], 'image' ) !== false ) {
        $tmp = explode('.', $_FILES['foto']['name']);
        $ext = array_pop($tmp);
        $file_name_new = md5(strtotime('now')).'_'.md5($_FILES['foto']['tmp_name']).'.'.$ext;
        $full_file_name = $dir_upload['basedir'] .'/resume/'.$file_name_new;

        $image = new Gumlet\ImageResize($_FILES['foto']['tmp_name']);
        $image->quality_jpg = 100;
        $image->quality_webp = 100;
        $image->quality_png = 0;
        switch( $template_num ) {
            case "1":
                $image->resizeToWidth(192);
                break;
            case "2":
                $image->resizeToBestFit(144, 144);
                break;
            case "3":
                $image->resizeToBestFit(147, 147);
                break;
        }

        $image->save($full_file_name);
        $photo_url = 'wp-content/uploads/resume/'.$file_name_new;
        unlink($_FILES['foto']['tmp_name']);
    }

    // Подготовка HTML. Вставка динамики
    $html = career_resume_get_html_simple($template_num, $photo_url);
    if( !count($html) ) return false;

    $mpdf = new \Mpdf\Mpdf([
        'debug' => true,
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => __DIR__ . '/tmp',
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'margin_header' => 0,
        'margin_footer' => 0,
        'curlAllowUnsafeSslRequests' => true,
        'dpi' => 72
    ]);

    $mpdf->img_dpi = 72;
    $mpdf->setBasePath(get_home_url());
    $mpdf->basepathIsLocal = false;

    foreach ($html as $_html) {
        $mpdf->WriteHTML($_html);
    }

    if( $mpdf->page > 1 ) {
        unset($mpdf);

        $html = career_resume_get_html($template_num, $photo_url);
        if( !count($html) ) return false;

        $mpdf = new \Mpdf\Mpdf([
            'debug' => true,
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => __DIR__ . '/tmp',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
            'curlAllowUnsafeSslRequests' => true,
            'dpi' => 72
        ]);

        $mpdf->img_dpi = 72;
        $mpdf->setBasePath(get_home_url());
        $mpdf->basepathIsLocal = false;

        foreach ($html as $_html) {
            $mpdf->WriteHTML($_html);
        }
    }

    // F - сохранить в файл
    // I - выдать в браузер
    // D - выдать в загрузку
    $tmp_file_name = __DIR__ . '/tmp/hays-cv-'.date('d_m_y').'.pdf';
    $mpdf->Output($tmp_file_name, 'F');

    // отправка на почту
    $attach = array($tmp_file_name);
    $industry_email = [
        'Финансы и бухгалтерия'	=> 'Moscow.accountancy_perm@hays.ru',
        'Строительство и недвижимость'	=> 'Moscow.construction_perm@hays.ru',
        'Финансовые институты'	=> 'Moscow.fininstitution_perm@hays.ru',
        'FMCG'	=> 'Moscow.fmcg_perm@hays.ru',
        'Продажи и маркетинг:B2B'	=> 'Moscow.B2B_perm@hays.ru',
        'Административный персонал'	=> 'Moscow.officesupport_perm@hays.ru',
        'Юриспруденция'	=> 'Moscow.legal_perm@hays.ru',
        'Управление персоналом'	=> 'Moscow.hr_perm@hays.ru',
        'Инжиниринг и EPC'	=> 'Moscow.EPCengineering_perm@hays.ru',
        'Автобизнес' => 'Moscow.automotive_perm@hays.ru',
        'Производство'	=> 'Moscow.industry_perm@hays.ru',
        'IT и Телеком'	=> 'Moscow.it_perm@hays.ru',
        'Маркетинг (Фармацевтика)'	=> 'Moscow.LSMarketing_perm@hays.ru',
        'Медицинское и лабораторное оборудование'	=> 'Moscow.medicaldeviced_perm@hays.ru',
        'Ветеринария'	=> 'Moscow.animalhealth_perm@hays.ru',
        'Фармацевтика'	=> 'Moscow.lifesciences_perm@hays.ru',
        'Tоп-менеджемент (Фармацевтика)' => 'Moscow.LSSocialCare_perm@hays.ru',
        'Люкс'	=> 'Moscow.lux_perm@hays.ru',
        'Digital&E-Commerce' => 'Moscow.digital_perm@hays.ru',
        'Медиа'	=> 'Moscow.media_perm@hays.ru',
        'MARCOM PR'	=> 'Moscow.salesmarketing_perm@hays.ru',
        'Нефтегазовый сектор' => 'Moscow.oilgas_perm@hays.ru',
        'Ритейл' => 'Moscow.retail_perm@hays.ru',
        'Логистика и закупки' => 'Moscow.logistics_perm@hays.ru'
    ];

    $job_manager_email_resume = isset($industry_email[ $_POST['industry'] ]) ? $industry_email[ $_POST['industry'] ] : 'Moscow.accountancy_perm@hays.ru';

    $headers[] = 'From: '.$info['name'].' '.$info['surname'].' <'.$info['email'].'>' . "\r\n";
    $headers[] = 'X-Aplitrak-Original-Ref: ' . "\r\n";
    $headers[] = 'X-Aplitrak-Original-Consultant: '. $job_manager_email_resume . "\r\n";

    echo wp_mail("mahteev@pervee.ru", 'Резюме от '.$info['name'].' '.$info['surname'], 'Резюме во вложении', $headers, $attach ) ? 'sended' : 'error';

    unlink($tmp_file_name);
    exit;
}

function career_file_force_download($file) {
    if (file_exists($file)) {
        // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
        // если этого не сделать файл будет читаться в память полностью!
        if (ob_get_level()) {
            ob_end_clean();
        }
        // заставляем браузер показать окно сохранения файла
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        // читаем файл и отправляем его пользователю
        readfile($file);
        exit;
    }
}

function career_send_grade_site()
{
    global $wpdb;
     $rate = (int)$_POST['rate'];
     $comment = strip_tags($_POST['recom']);

     if (!empty($rate) || !empty($comment)) {
         //$mess = !empty($rate) ? '<p>Оценка: '.$rate.'</p>' : '';
         ///$mess .= !empty($comment) ? '<p>Рекомендации: '.$comment.'</p>' : '';

         global $wpdb;
         $wpdb->query("INSERT INTO `wp_career_grade_site` (`rate`, `comment`) VALUES ('".$rate."', '".$comment."')");

         //echo wp_mail("mahteev@pervee.ru", 'Оценка сайта', $mess ) ? 'sended' : 'error';
     }
}
function career_delete_grade_site()
{
    global $wpdb;
    $id = (int)$_POST['id'];

    if($id) {
        $wpdb->query("DELETE FROM `wp_career_grade_site` WHERE `id` = ".$id);
    }
    die;
}

