<?php
/**
 * The main template file
 *
 * @package WordPress
 * @subpackage Salary Guide Hays 2
 * @since Salary Guide 2.0
 */

get_header();
?>

<?php
if ( have_posts() ) {

    // Load posts loop.
    while ( have_posts() ) {
        the_post();
        the_content();
        //get_template_part( 'parts/content/content', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ) );
    }


} else {
   // echo "<h1>нет данных</h1>";
    // If no content, include the "No posts found" template.
   // get_template_part( 'parts/content/content-none' );

}

get_footer();
