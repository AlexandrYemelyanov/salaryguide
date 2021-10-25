<?php
/**
 * The header resume.
 *
 * @package WordPress
 * @subpackage Salary Guide Hays 2
 * @since Salary Guide 2.0
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="keywords" content="Hays">
    <meta name="description" content="Hays">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hays</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <?php wp_head(); ?>
</head>
<body>
<!-- Begin wrapper -->
<div class="wrapper wrapper--resume">
    <!-- Begin header -->
    <header class="header">
        <!-- Begin header__top -->
        <div class="header__top">
            <div class="container flex middle">
                <a class="header__logo" href="/"><img src="<?php echo get_template_directory_uri();?>/assets/img/header/logo.svg" alt="Hays"></a>
                <a class="header__back flex_inline middle" href="/constructor-resume/">Вернуться<span class="icon_arrow_right2"></span></a>
            </div>
        </div>
        <!-- End header__top -->
    </header>
    <!-- End header -->