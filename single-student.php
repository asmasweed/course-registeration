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
$list = '';
$certificates = '';
$list .='<ul>';
echo'<ul>';
foreach ($entries as $key => $value) { 
if($value['40'] ==='Course Completed'){
  $list .='<li><a href="#" class="certificate-button" data-course="'. $key .'">' . $value['38'] . '</a></li>' ;
  '<br><br><hr>';
  $certificates .= '<div class="certificates-list">
    
  <div class="course hide" id="' . $key . '" >
  
  <p>The certificate below will be downloaded to your computer. No information will be stored on the server.</p>
  Entery your personal number here:
<input class="secret"></input>
<hr class ="left" style="border-top: dotted 1px; width:100%;" />
<div id="certificate-content">
      <img src="https://www.arbetsformedlingen.se/rest/arbetsgivare/rest/af/v3/organisation/2021003120/logotyper/logo-200x200.png">
      <h1 class="certificate"><B>Intyg</B></h1>'.
     '<h2>'. $current_user->user_firstname .' '. $current_user->user_lastname .'</h2><br>
     <div class="display-secret"  ></div>
      <br><br><br><br><br><br><br>
     <h3> har genomgått '.' '.'<B>' . $value['38'] .  '.</B></h3><br>
     <p>' . $value['20'] . '</p>
      <lable><B>Karlstad' .', '. date('F Y').'</B></lable><br><br>
      <img src="http://localhost:8888/wordpress/wp-content/uploads/2020/11/signature.png">
      <div><hr class ="left" style="border-top: dotted 1px; width:30%;" /></div><br><br>
  <div>
  <div class="left">
    <em>Jörg Pareigis</em>
  </div>
  <div>
    <em>Head of Centre for Teaching </em>
  </div>
  <div>
   <em>and Learning </em>                                         
  </div>
</div>
</div>
<div class="editor" ></div>
      <button id="cmd">Download PDF</button>
  </div>';
;
}

}
echo $list . '</ul>' . $certificates . '</ul>';

 
  
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