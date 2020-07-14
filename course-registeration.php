<?php 
/*
Plugin Name: Course Registeration
Plugin URI:  https://github.com/
Description: My First Plugin
Version:     1.0
Author:      Asma
Author URI:  http://sola.kau.se
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: my-toolset
*/

defined( 'ABSPATH' ) or die( 'Hey, what are you doing here?' );


add_action('wp_enqueue_scripts', 'asma_load_scripts');

function asma_load_scripts() {                           
    $deps = array('jquery');
    $version= '1.0'; 
    $in_footer = true;    
    wp_enqueue_script('asma-main-js', plugin_dir_url( __FILE__) . 'js/asma-main.js', $deps, $version, $in_footer); 
    wp_enqueue_style( 'asma-main-css', plugin_dir_url( __FILE__) . 'css/asma-main.css');
}



add_filter( 'the_content', 'asma_add_content', 1);

//append content to filter
function asma_add_content($content){
  global $post;
  $post_id = $post->ID;
  if(get_field('short_description',$post_id)){
    $short = '<div class="short-desc"><h4>Short Description</h4>' . get_field('short_description',$post_id) . '</div>'; // get short_description
  }
  $full = asma_get_full_description($post);//get full_description
  $hours = asma_get_houres($post);
  $start = asma_get_start_date($post);
  $end = asma_get_start_date($post);
  $instructor = asma_get_instructor($post);
  $admin = asma_get_admin($post);
  $enrollment = asma_get_enrollment($post);
  $status = asma_get_status($post);
  $cost = asma_get_cost($post);
  return  $full . $short . $start . $end . $hours . $instructor . $admin . $enrollment . $status . $cost . $content;
}



//if things get crowded you can break out elements into their own functions but you need to pass in the whole $post rather than just the ID
function asma_get_full_description($post){
  $post_id = $post->ID;
  if(get_field('full_description',$post_id)){
    $full = '<div class="full-desc"><h4>Full Description</h4>' .get_field('full_description',$post_id) . '</div>';//get full_description
    return $full;
  }
}

function asma_get_start_date($post){
  $post_id=$post->ID;
  if( have_rows('date', $post_id) ){

    while( have_rows('date', $post_id) ){
       the_row();
    $start = '<div class="start_date"><h4> Start Date </h4>' . get_sub_field('start_date', $post_id) . '</div>';
    $end =   '<div class="end_date"><h4> End Date </h4>' . get_sub_field('end_date', $post_id) . '</div>';
    return $start . $end;
    
  }
}
}
function asma_get_houres($post){
  $post_id = $post->ID;
  if(get_field('houres', $post_id)){
    $hours = '<div class="hours"><h4> Hours Of Commitment </h4>' .get_field('houres', $post_id) . '</div>';
    return $hours;
  }
}


function asma_get_enrollment($post){
  $post_id=$post->ID;
  if(get_field('enrollment', $post_id)){
    $enrollment = '<div class="enrollments"><h4> Limit of Enrollment </h4>' .get_field('enrollment', $post_id) . '</div>';
    return $enrollment;
  }
}


function asma_get_instructor($post){
  $post_id = $post->ID;
  if(get_field('instructors', $post_id)['display_name']){
    
    $instructor = '<div class="instructor"><h4> Instructor(s) </h4>' . get_field('instructors', $post_id)['display_name'] . '</div>';
    return $instructor;
  }
}


function asma_get_admin($post){
$post_id = $post->ID;
if(get_field('admins', $post_id)['display_name']){
  $admin = '<div class="admin"><h4> Admin(s) </h4>' . get_field('admins', $post_id)['display_name'] . '</div>';
  return $admin;
}
}


function asma_get_status($post){
  $post_id = $post->ID;
  if(get_field('openclosed', $post_id)){
    $status = '<div class="status"><h4> Status </h4>' .get_field('openclosed', $post_id) . '</div>';
    return $status;
  }
}

function asma_get_cost($post){
  $post_id=$post->ID;
  if(get_field('cost', $post_id)){
    $cost = '<div class="instructor"><h4> Cost </h4>' .get_field('cost', $post_id) . '</div>';
    return $cost;
  }
}

/*function asma_get_gravity_form($post){
  $post_id = $post->ID;

  $gform = '<div class="gravity-form"><h3> Registerera Dig! </h3>' . gravity_form(3) . '</div>';
  return $gform;
}*/


// Add gravity form to post type = course.

function asma_course_content($content) {
  global $post;
   if ($post->post_type === 'course' ) {
      $course_title = get_the_title($post->ID);
      $hours = get_field('houres', $post->ID);
       $content = $content.gravity_form(3, false, false, false, array('course_title' => $course_title, 'course_hours' => $hours), true, 1, false);
   }
     $student_allowed = get_field('enrollment', $post->ID);
     return $content . asma_search($course_title, $student_allowed) ;
}
add_filter('the_content', 'asma_course_content', 1);





function asma_search($course_title, $students_allowed){
  $search_criteria = array(
    'status'        => 'active',
    'field_filters' => array(
        'mode' => 'any',
        array(
            'key'   => '13', //PROBABLY DIFFERENT FOR YOU
            'value' => $course_title
        )
    )
  );
  $entries  = GFAPI::get_entries( 3, $search_criteria );

  //print("<pre>".print_r($entries,true)."</pre>");
 // var_dump(count($entries));
    if(count($entries) > $students_allowed){
        return '<p>This class is full. We love you but you are on the waiting list.</p>';
    }
    else{
       return 'Asma is great!';
      }

}

add_filter( 'gform_confirmation', 'custom_confirmation', 1, 4 );

function custom_confirmation( $confirmation, $form, $entry, $ajax ) {  
  global $post;
  $course_title = get_the_title($post->ID);
  var_dump($course_title);
  $students_allowed = get_field('enrollment', $post->ID);
  $confirmation = asma_search($course_title, $students_allowed);
  return $confirmation;
}

function asma_find_students_who_enrolled(){
  global $post;
  $current_user = wp_get_current_user();
  $course_title = get_the_title($post->ID);
  $students_allowed = get_field('enrollment', $post->ID);
  $search_criteria = array(
    'status'        => 'active',
    'field_filters' => array(
        'mode' => 'any',
        array(
            'key'   => '13', //PROBABLY DIFFERENT FOR YOU
            'value' => $course_title
        )
    )
  );
  $entries  = GFAPI::get_entries( 3, $search_criteria );
  //var_dump($entries);
  foreach ($entries as $key => $value) { 
    if ( ! current_user_can( 'edit_post', $post->ID ) ) {
   
      return 'You are not allowed';
      
    } 
      else{
        return '<ul><li> <B>First Name: </B>' . $value['1.3'] . '</li>' .
             '<li> <B>Last Name: </B>' . $value['1.6'] . '</li></ul>';
      }
     
   }

}

add_filter('the_content', 'asma_find_students_who_enrolled', 1);
















































