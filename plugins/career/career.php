<?php
/**
 * Plugin Name: Карьера
 * Description: Система связанных полей
 * Version:     1.0
 * Author:      Alexandr Yemelyanov
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('CAREER_DIR', plugin_dir_path(__FILE__));
define('CAREER_URL', plugin_dir_url(__FILE__));

// add_filter( 'c2c_add_admin_js_files', 'career_admin_js_files' );

add_action( 'admin_enqueue_scripts', 'career_backend_scripts' );

add_action('admin_menu', 'career_menu' );

add_action('admin_post_career_import', 'career_process_import');
add_action('admin_post_career_comp_empl', 'career_comp_empl_controller');

add_action('admin_post_career_import', 'career_process_import');
add_action('admin_post_career_comp_empl', 'career_comp_empl_controller');

add_action('admin_post_career_sector_info_save', 'career_sector_save_controller');

add_action('admin_post_career_create_resume', 'career_create_resume_controller');
add_action('admin_post_nopriv_career_create_resume', 'career_create_resume_controller');

add_action('wp_ajax_career_plan_get_elem', 'career_plan_get_elem');
add_action('wp_ajax_nopriv_career_plan_get_elem', 'career_plan_get_elem');

add_action('wp_ajax_career_delete_grade_site', 'career_delete_grade_site');
add_action('wp_ajax_nopriv_career_delete_grade_site', 'career_delete_grade_site');

add_action('wp_ajax_career_send_grade_site', 'career_send_grade_site');
add_action('wp_ajax_nopriv_career_send_grade_site', 'career_send_grade_site');

add_action('wp_ajax_career_send_resume', 'career_send_resume_controller');
add_action('wp_ajax_nopriv_career_send_resume', 'career_send_resume_controller');

add_action('wp_ajax_career_send_resume', 'career_send_resume_controller');
add_action('wp_ajax_nopriv_career_send_resume', 'career_send_resume_controller');

add_action('wp_ajax_career_plan_get_sector', 'career_plan_get_sector');
add_action('wp_ajax_career_plan_get_branch', 'career_plan_get_branch');
add_action('wp_ajax_career_plan_save', 'career_plan_save_controller');
add_action('wp_ajax_career_plan_delete', 'career_plan_delete');
add_action('wp_ajax_career_plan_get_maxplan', 'career_plan_get_maxplan');
add_action('wp_ajax_career_calculator_get_elem', 'career_calculator_get_elem');
add_action('wp_ajax_nopriv_career_calculator_get_elem', 'career_calculator_get_elem');
add_action('wp_ajax_career_calculator_get_widget', 'career_calculator_get_widget');
add_action('wp_ajax_nopriv_career_calculator_get_widget', 'career_calculator_get_widget');
add_action('wp_ajax_career_calculator_get_json', 'career_calculator_get_json');
add_action('wp_ajax_nopriv_career_calculator_get_json', 'career_calculator_get_json');
add_action('wp_ajax_career_plan_get_widget', 'career_plan_get_widget');
add_action('wp_ajax_nopriv_career_plan_get_widget', 'career_plan_get_widget');

//add_action('wp_ajax_career_create_resume', 'career_create_resume_controller');
//add_action('wp_ajax_nopriv_career_create_resume', 'career_create_resume_controller');

add_action( 'wp_enqueue_scripts', 'career_frontend_scripts' );


add_action('init', function () {
    if (session_id() == '') {
        session_start();
    }
});

function career_include()
{
    echo '<link rel="stylesheet" href="'.CAREER_URL.'css/style.css" type="text/css" />'.PHP_EOL;
    echo '<script src="'.CAREER_URL.'js/script.js" type="text/javascript"></script>'.PHP_EOL;
}

function career_backend_scripts()
{
    wp_enqueue_script( 'career-jstree-script', CAREER_URL.'js/jstree.min.js');
    wp_enqueue_script( 'career-select2-script', CAREER_URL.'js/select2.min.js');
    wp_enqueue_script( 'career-accordion-script', '/wp-includes/js/jquery/ui/accordion.min.js');
    wp_enqueue_script( 'career-jstree-init-script', CAREER_URL.'js/jstree.init.js');
}

function career_frontend_scripts()
{
    wp_enqueue_style('career-text-animate', CAREER_URL.'css/animate.css');
    wp_enqueue_style('career-select2', CAREER_URL.'css/select2.min.css');
    wp_enqueue_style('career-calculator', CAREER_URL.'css/calculator.css');
    wp_enqueue_style('career-main-style', CAREER_URL.'css/main.css');

    wp_enqueue_script( 'jquery' );

    // animate
    wp_register_script( 'career-lettering-script', CAREER_URL.'js/jquery.lettering.js');
    wp_register_script( 'career-textillate-script', CAREER_URL.'js/jquery.textillate.js');
    wp_register_script( 'career-cookie-script', CAREER_URL.'js/jquery.cookie.js');
//    wp_register_script( 'career-parallax-script', CAREER_URL.'js/jquery.parallax.min.js');

//    wp_register_script( 'career-select2-script', CAREER_URL.'js/select2.min.js');
    wp_register_script( 'career-main-script', CAREER_URL.'js/main.js');

    wp_enqueue_script( 'career-lettering-script' );
    wp_enqueue_script( 'career-textillate-script' );
    wp_enqueue_script( 'career-cookie-script' );
//    wp_enqueue_script( 'career-parallax-script' );

    wp_enqueue_script( 'career-select2-script' );
    wp_enqueue_script( 'career-main-script' );
}

function career_menu()
{
    add_menu_page('Импорт данных о карьере', 'Карьера', 'manage_options', 'career', 'career_import_page', 'dashicons-edit', "27.3" );

    add_submenu_page( 'career', 'Данные для стр Компания/Работник', 'Компания/Работник', 'manage_options', 'career-comp-empl', 'career_comp_empl_page');

    add_submenu_page( 'career', 'Построение карьерных лестниц', 'Лестницы', 'manage_options', 'career-plan', 'career_plan_page');

    add_submenu_page( 'career', 'Информация о секторах', 'О секторе', 'manage_options', 'career-sector', 'career_sector_page');

    add_submenu_page( 'career', 'Импорт опросов', 'Импорт опросов', 'manage_options', 'career-survey', 'career_sector_survey');

    add_submenu_page( 'career', 'Оценки сайта', 'Оценки сайта', 'manage_options', 'career-grade', 'career_grade_page');
}

function career_admin_js_files( $files ) {
    $files[] = CAREER_URL.'js/jstree.min.js';
    $files[] = CAREER_URL.'js/select2.min.js';
    $files[] = '/wp-includes/js/jquery/ui/accordion.min.js';
    $files[] = CAREER_URL.'js/jstree.init.js';

  //  echo "<p><b>files</b><pre>";print_r($files);echo "</pre></p>";


    return $files;
}

require_once(CAREER_DIR.'includes/pages.php');
require_once(CAREER_DIR.'includes/controllers.php');
require_once(CAREER_DIR.'includes/getters.php');
require_once(CAREER_DIR.'includes/shortcodes.php');