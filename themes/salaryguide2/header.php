<?php
/**
 * The header.
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
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Hays</title>
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.2/jquery.modal.min.css">

    <?php wp_head(); ?>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MJHHW7J');</script>
<!-- End Google Tag Manager -->
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '607181030258237');
fbq('track', 'PageView');
</script>
<noscript>
<img height="1" width="1"
src="https://www.facebook.com/tr?id=607181030258237&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
</head>
<body>
  <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MJHHW7J"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- Begin wrapper -->
<div class="wrapper">
    <!-- Begin header -->
    <header class="header">
        <!-- Begin header__top -->
        <div class="header__top">
            <div class="container flex middle">
                <a class="header__logo" href="/"><img src="<?php echo get_template_directory_uri();?>/assets/img/header/logo.svg" alt="Hays"></a>
                <div class="header__menu flex middle">
                    <ul class="header__menu_list flex middle">
                        <li><a href="https://hays.ru/about-hays/" target="_blank">О нас</a></li>
                        <li><a href="https://hays.ru/hays-services/">Услуги</a></li>
                        <li><a class="header__menu_download flex_inline middle" href="/salary-guide-2020-2021/">Пройти опрос</a></li>
                        <li><span class="popmake-814"><a class="header__menu_download flex_inline middle" href="#">Скачать Salary Guide<span class="icon_download"></span></a></span></li>
                        <li><a href="/contacts/" style="margin-left: 10px;">Контакты</a></li>
                    </ul>

                </div>
                <div class="header__social flex middle end">
                    <a class="header__social_icon icon_facebook" href="https://www.facebook.com/HaysRussia/"></a>
                    <a class="header__social_icon icon_instagram" href="https://www.instagram.com/hays_russia/"></a>
                    <a class="header__social_icon icon_youtube" href="https://www.youtube.com/channel/UCyAktjM5T5-8wPABFAeV_tg"></a>
                    <a class="header__social_icon icon_linkedin" href="https://www.linkedin.com/groups/8249311/"></a>
                    <a class="header__social_icon icon_telegram" href="http://t.me/haysjobs"></a>
                    <a class="header__social_icon icon-podcast" href="https://podcasts.google.com/feed/aHR0cHM6Ly9mZWVkcy5zaW1wbGVjYXN0LmNvbS9XY2l3UXpvSQ?sa=X&ved=0CAMQ4aUDahcKEwjww4XE3sHwAhUAAAAAHQAAAAAQAQ" style="height: 30px;"></a>
                </div>
                <a class="header__burger icon_menu" href="#nav"></a>
            </div>
        </div>
        <!-- End header__top -->
        <!-- Begin header__nav -->
        <nav class="header__nav" id="nav">
            <a class="header__nav_close icon_close" href="#nav"></a>
            <ul class="header__nav_list flex middle center">
                <li<?php if( strpos(get_permalink(), 'calculator') !== false) echo ' class="is_active"';?>><a href="/calculator/">Зарплатный барометр</a></li>
                <li<?php if( strpos(get_permalink(), 'career-plan') !== false) echo ' class="is_active"';?>><a href="/career-plan/">Карьерный план</a></li>
            <li<?php if( strpos(get_permalink(), 'industry') !== false) echo ' class="is_active"';?>><a href="/industry/">Индустриальные тренды</a></li>
                <li<?php if( strpos(get_permalink(), 'constructor-resume') !== false) echo ' class="is_active"';?>><a href="/constructor-resume/">Конструктор резюме</a></li>
                <li<?php if( strpos(get_permalink(), 'survey-infographics') !== false) echo ' class="is_active"';?>><a href="/survey-infographics/">Исследование рынка труда</a></li>
            </ul>
        </nav>
        <!-- End header__nav -->
    </header>
    <!-- End header -->
