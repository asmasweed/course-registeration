<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action('wp_enqueue_scripts', 'asma_load_scripts');
function certificate_load_scripts(){
	wp_enqueue_script('student-certificate-js', plugin_dir_url( __FILE__) . 'student-certificate-js', $deps, $version, $in_footer);
	wp_localize_script( 'student-certificate-js', 'test', array( 'ajax_url' => admin_url('admin-ajax.php')) );
}
get_header(); ?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>
	

<?php endif ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>
		
<div id="primary" class="content-area">
	<main id="main" class="site-main" role=“main”>
	
		<?php 
		if (have_posts()) : ?>
			<?php while (have_posts()) :   the_post(); ?>
			<?php endwhile; ?>
			<B><?php the_content(); ?></B>
		<?php endif; ?> 
		
<!-- Student page content
		<?php
function asma_find_student_courses(){
  global $post;
        $current_user = wp_get_current_user();
        $search_criteria = array(
          'status'        => 'active',
          'field_filters' => array(
              'mode' => 'any',
              array(
                  'key'   => '1.3', 
                  'value' => $current_user->user_firstname
              )
          )
		);
		
  $entries  = GFAPI::get_entries( 5, $search_criteria );
  //var_dump($entries);
 echo '<h3>My courses:</h3>';
 echo '<ul class="list">';
 foreach ($entries as $key => $value) { 
	if($value['40'] ==='Course Completed'){
         echo '<div><li>' . $value['38']  . '</li></div>';
 }

echo'</ul>';
   }
}
add_filter('the_content', 'asma_find_student_courses', 1);
?>

<?php
add_filter('single_template', 'my_custom_template');
 function my_custom_template($single) {

    global $post;

    /* Checks for single template by post type */
    if ( $post->post_type === 'student' ) {
        if ( file_exists( 'course-registration' . '/single-student.php' ) ) {
            return 'course-registration' . '/single-student.php';
            return asma_find_student_courses();
        }
    }

    return $single;

} 

?>
     <?php astra_content_loop(); ?>
		<?php astra_primary_content_bottom(); ?>
		

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>
<?php get_footer(); ?>
<?php 






?>