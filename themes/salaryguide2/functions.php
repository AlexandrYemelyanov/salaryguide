<?php

add_action('wp_footer', 'add_scripts');
if (!function_exists('add_scripts')) {
    function add_scripts() {
        if(is_admin()) return false;
        wp_enqueue_script('main', get_template_directory_uri().'/assets/js/main.js','','',true);
    }
}

add_action('wp_print_styles', 'add_styles');
if (!function_exists('add_styles')) {
    function add_styles() {
        if(is_admin()) return false;
        wp_enqueue_style( 'dashicons' );
        wp_enqueue_style( 'main', get_template_directory_uri().'/assets/css/main.min.css' );
        wp_enqueue_style( 'custom', get_template_directory_uri().'/assets/css/custom.css' );
    }
}


add_shortcode( 'theme-url' , 'salaryguide_get_theme_url' );
function salaryguide_get_theme_url() {
    return get_template_directory_uri();
}

add_filter('the_content', function($content) {
    return str_replace('[theme-url]', get_template_directory_uri(), $content);
});

/* Disable auto-add tags in the post */
remove_filter( 'the_content', 'wpautop' );