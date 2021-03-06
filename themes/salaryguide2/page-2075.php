<?php
/**
 * The page.
 *
 * @package WordPress
 * @subpackage Salary Guide Hays 2
 * @since Salary Guide 2.0
 */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

    <?php

    $survey = career_get_conflict();
    $tmp = current(current(current($survey)));
    $years = array_keys($tmp);
    $count_year = count($years);
    $last_year = $years[ $count_year - 1 ];


    include_once (__DIR__.'/conflict.template2.php');
    the_content();

    ?>

<?php endwhile; ?>
<?php get_footer(); ?>