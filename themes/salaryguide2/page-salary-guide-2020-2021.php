<?php
/**
 * The page.
 *
 * @package WordPress
 * @subpackage Salary Guide Hays 2
 * @since Salary Guide 2.0
 */

get_header();
?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <!-- Begin main_xs -->
    <div class="main_xs main_xs--poll">
      <div class="container">
        <div class="main_xs__info">
          <ul class="bradcrumbs bradcrumbs--xs flex middle">
          <!--  <li><a href="/">Главная</a></li> -->
          </ul>
          <h1>ОБЗОР РЫНКА ТРУДА И ЗАРАБОТНЫХ ПЛАТ <br>HAYS SALARY GUIDE 2021</h1>
          <p class="main_xs__text">Примите участие в ежегодном исследовании</p>
        </div>
      </div>
    </div>
  <!-- End main_xs -->

  <!-- Begin remember -->
    <div class="remember">
      <div class="container">
        <h2 class="remember__title">2020 ГОД ПРИНЕС БИЗНЕСУ И СТОЯЩИМ ЗА НИМ ЛЮДЯМ МНОГО ВЫЗОВОВ И ИСПЫТАНИЙ:</h2>
        <img class="remember__img" src="<?php echo get_template_directory_uri();?>/assets/img/main/img2.png" alt="">
       <!-- <p class="remember__label">Вспомним тренды уходящего года:</p> -->
        <div class="remember__list flex top">
          <div class="remember__item">
            <span class="remember__item_label pink">Треть <br>сотрудников</span>
            <p>задумалась о смене работодателя в период самоизоляции.</p>
          </div>
          <div class="remember__item">
            <span class="remember__item_label green">Около <br>40%</span>
            <p>работников, трудясь из дома, испытывали тревогу и сильный стресс.</p>
          </div>
          <div class="remember__item">
            <span class="remember__item_label purple">Каждый <br>второй</span>
            <p>профессионал понял, что их навыков уже недостаточно для работы в современных реалиях.</p>
          </div>
          <div class="remember__item">
            <span class="remember__item_label orange">1/5 <br>руководителей</span>
            <p>переживали за продуктивность команды.</p>
          </div>
        </div>
      </div>
    </div>
  <!-- End remember -->

  <!-- Begin poll -->
    <div class="poll">
      <div class="container flex top">
        <div class="poll__info">
          <h2 class="poll__info_title">Пройдите опрос <span>HAYS&nbsp;SALARY&nbsp;GUIDE&nbsp;2021</span></h2>
          <p>Что  происходит с зарплатами в разных отраслях? Готовы ли сотрудники мириться с переработками в кризис? И как руководители компаний реагируют на вызовы времени? Своим участием в исследовании вы помогаете собирать данные, которые позволяют нам ежегодно готовить объективную аналитику ситуации на российском  рынке труда в разных функциональных областях и индустриях.</p>
        </div>
        <div class="poll__list flex between">
          <div class="poll__list_item flex top">
           <p class="poll__list_title_1">Если вы работодатель — в течение года нанимали персонал (актуально для представителей HR-функции, руководителей компаний и подразделений)</p>
            <a class="poll__list_btn btn btn--big" href="https://surveys.hays.com/cgi-bin/qwebcorporate.dll?idx=BDM4UU">Пройти опрос</a>
            <img class="poll__list_icon" src="<?php echo get_template_directory_uri();?>/assets/img/poll/icon1.svg" alt="">
          </div>
          <div class="poll__list_item flex top">
            <p class="poll__list_title_1">Если вы соискатель или сотрудник компании (актуально для профессионалов всех индустрий и профессиональных областей)</p>
            <a class="poll__list_btn btn btn--big" href="https://surveys.hays.com/cgi-bin/qwebcorporate.dll?idx=SB2D6E">Пройти опрос</a>
            <img class="poll__list_icon" src="<?php echo get_template_directory_uri();?>/assets/img/poll/icon2.svg" alt="">
          </div>
        </div>
      </div>
    </div>
  <!-- End poll -->

  <!-- Begin fund -->
<!--    <div class="fund">
      <div class="container flex middle">
        <img class="fund__logo" src="<?php echo get_template_directory_uri();?>/assets/img/fund/img.png" alt="">
        <div class="fund__info">
          <p>В это непростое время все вместе мы хотим поддержать фонд «Старость в радость» в борьбе с коронавирусом, профилактике его распространения и реабилитации пожилых людей после перенесенного заболевания. За каждую пройденную анкету Hays переведет 10 рублей в Фонд. Когда нас много, даже небольшая сумма может изменить ход событий.</p>
        </div>
        <div class="fund__size">
          <span class="fund__money">49 300</span>
          <span>рублей уже собрано!</span>
        </div>
      </div>
    </div> -->
  <!-- End fund -->

    <div class="container flex middle">
        <?php the_content();  ?>
    </div>

<?php endwhile; ?>
<?php get_footer(); ?>