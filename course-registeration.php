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
    wp_enqueue_script('asma-main-js', plugin_dir_url( __FILE__) . 'asma-main.js', $deps, $version, $in_footer); 
    wp_enqueue_style( 'asma-main-css', plugin_dir_url( __FILE__) . 'asma-main.css');
    wp_localize_script( 'asma-main-js', 'test', array( 'ajax_url' => admin_url('admin-ajax.php')) );

}
//----------- display courses----------------


add_shortcode('course_list', 'display_courses');
function display_courses(){
  $output = '';
  $args = array(
    'post_type' => 'course',
    'posts_per_page' => 10
    
  );
  $query = new WP_Query($args);
    if($query->have_posts()) :
      while($query->have_posts()) :
        $query->the_post();
        $output = $output . "<h2><a href=" . get_permalink() . ">" . get_the_title() . "</a> </h2>" . "<p>" .  get_field('short_description') . "</p>" ;
      endwhile;
      wp_reset_postdata();
    endif;
    return $output;
}


add_shortcode('course_list_swedish', 'display_swedish_courses');
function display_swedish_courses(){
  $output = '';
  $args = array(
    'post_type' => 'course',
    'posts_per_page' => 10,
    'category_name' => 'svensk_kurs'
  );
  $query = new WP_Query($args);
    if($query->have_posts()) :
      while($query->have_posts()) :
        $query->the_post();
        $output = $output . "<h2><a href=" . get_permalink() . ">" . get_the_title() . "</a> </h2>" . "<p>" .  get_field('short_description') . "</p>" ;
      endwhile;
      wp_reset_postdata();
    endif;
    return $output;
}

add_shortcode('course_list_english', 'display_english_courses');
function display_english_courses(){
  $output = '';
  $args = array(
    'post_type' => 'course',
    'posts_per_page' => 10,
    'category_name' => 'english_course'
  );
  $query = new WP_Query($args);
    if($query->have_posts()) :
      while($query->have_posts()) :
        $query->the_post();
        $output = $output . "<h2><a href=" . get_permalink() . ">" . get_the_title() . "</a> </h2>" . "<p>" .  get_field('short_description') . "</p>" ;
      endwhile;
      wp_reset_postdata();
    endif;
    return $output;
}






add_filter( 'the_content', 'asma_add_content', 1);

//append content to filter
function asma_add_content($content){

  global $post;
  if ($post->post_type === 'course' ) {
    $post_id = $post->ID;
      if(get_field('short_description',$post_id)){
       $short = '<div class="short-desc"><h4></h4>' . get_field('short_description',$post_id) . '</div>'; 
  }
   $full = asma_get_full_description($post);
   $litrature = asma_get_course_litrature($post);
   $hours = asma_get_houres($post);
   $overview = asma_get_start_date($post);
  $end = asma_get_start_date($post);
  $instructor = asma_get_instructor($post);
 // $admin = asma_get_admin($post);
  $enrollment = asma_get_enrollment($post);
  $status = asma_get_status($post);
  $cost = asma_get_cost($post);
  $schema = asma_get_schema($post);
  $target = asma_get_target_group($post);
  $guideline1 = asma_get_guideline($post);
  return  $short . $full . $litrature . $target .  $overview . $schema . $hours . $instructor . $enrollment . $status . $cost   . $guideline1 . $content;
  }
  else {
    return $content; //THIS THE KEY ELEMENT
  }

}

function asma_get_full_description($post){
  $post_id = $post->ID;
  if(get_field('full_description',$post_id)){
    $full = '<div class="full-desc"><h4>Description</h4>' .get_field('full_description',$post_id) . '</div>';
    return $full;
  }
}



function asma_get_course_litrature($post){
  $post_id = $post->ID;
  if(get_field('course_literature',$post_id)){
    $litrature = '<div class="full-desc"><h4>Course literature</h4>' .get_field('course_literature',$post_id) . '</div>';
    return $litrature;
  }
}

function asma_get_start_date($post){
  $post_id=$post->ID;
  if( have_rows('date', $post_id) ){

    while( have_rows('date', $post_id) ){
       the_row();
    $overview = '<div class="start_date"><h4> Course Period </h4>' . get_sub_field('start_date', $post_id) . '    -    ' .  get_sub_field('end_date', $post_id) . '</div>';
   /// $end =   '<div class="end_date"><h4> End Date </h4>' . get_sub_field('end_date', $post_id) . '</div>';
    return $overview;
    
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

function asma_get_schema($post){
  $post_id=$post->ID;
  $schema1 = '';
  
  $rows = get_field('schema', $post_id);
     // $title = '<h4>Schedule</h4>';
      if( have_rows('schedule', $post_id) ) {
         
        
        while( have_rows('schedule', $post_id) ){
          
          foreach ($rows as $row) {
            the_row();
            $schema1 .= '<ul> <li>' . '<B>' . get_sub_field('title', $post_id) . '</B>' . ': ' . get_sub_field('date', $post_id) . ' ,   ' . get_sub_field('start', $post_id) . ' - ' .  get_sub_field('end', $post_id) . '</li></ul> ';
            $schema ='<div class="schema"> <h4> Schedule </h4>'  . $schema1 . '<p>' . get_field('comment_of_the_schedule', $post_id) . '</p></div>';
          }
        }
        return $schema;
        
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
  $instructor1 = '';
  $post_id = $post->ID;
  $users = get_field('instructors', $post_id);
  if($users){
    echo '<ul claass"inst">';
    foreach($users as $user){
    $instructor1  .= '<li>' . $user['display_name'] . '</li>';
    $instructor = '<div class="instructor"> <h4> Instructor(s) </h4>' . $instructor1 . '</div>';
    }
    return $instructor;
     echo'</ul>';
  }  
}

/*
function asma_get_admin($post){
  $post_id = $post->ID;
  if(get_field('admins', $post_id)['display_name']){
  $admin = '<div class="admin"><h4> Admin(s) </h4>' . get_field('admins', $post_id)['display_name'] . '</div>';
  return $admin;
  }
}
*/

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
    $cost = '<div class="instructor"><h4> Cost </h4>' .get_field('cost', $post_id) . ' ' . 'kr' . '</div>';
    return $cost;
  }
}
 
function asma_get_target_group($post){
  $post_id = $post->ID;
  if(get_field('target_group', $post_id)){
    $target = '<div class="target"><h4> Target Group </h4>' .get_field('target_group', $post_id) . '</div>';
    return $target;
  }
}


function asma_get_guideline($post){
  $post_id = $post->ID;
  $guidelines = get_field('contribute_to_suhf:s_guidelines', $post_id);
  if($guidelines){
    foreach($guidelines as $guideline){
      $guideline2 .= ' <ul><li>' . $guideline . '</li></ul>';
      $guideline1 = '<div class="guide"> <h4> Contribute to SUHF:s guidelines </h4>'. $guideline2 . '<a target="_blank" href=" https://inslaget.kau.se/utbildning/universitetspedagogiskt-stod/bedomning-av-pedagogisk-skicklighet/vilka-kurser-bidrar-till ?">More about how UPEs courses contribute to the required learning outcomes!</a></div>';
    } 
      return $guideline1;
  }  
}

//*************************************APPEND COURSE REGISTRATION FORM

function asma_course_content($content) {
  global $post;
   if ($post->post_type === 'course' ) {
       $course_title = get_the_title($post->ID);
       $hours = get_field('houres', $post->ID);
       $instructor = get_field('instructors', $post->ID);
       $content = $content. '_______________________________________________________________________________' . '<Br>' .
       '<h2> Registration Form </h2>' .
       gravity_form(5, false, false, false, array('course_title' => $course_title, 'course_hours' => $hours, 'course_instructor' => $instructor), true, 1, false);
       $student_allowed = get_field('enrollment', $post->ID);
       echo $content . asma_search($course_title, $student_allowed) ;
      }
      else {
        return $content; //THIS THE KEY ELEMENT
      }
}
add_filter('the_content', 'asma_course_content', 1);


//**********************SEARCH TO SEE IF COURSE IS FULL
function asma_search($course_title, $students_allowed){
  $search_criteria = array(
    'status'        => 'active',
    'field_filters' => array(
        'mode' => 'any',
        array(
            'key'   => '38', //PROBABLY DIFFERENT FOR YOU
            'value' => $course_title
        )
    )
  );
  $entries  = GFAPI::get_entries( 5, $search_criteria );

 // print("<pre>".print_r($entries,true)."</pre>");
 // var_dump(count($entries));
    if(count($entries) > $students_allowed){
        return '<p>This class is full. We love you but you are on the waiting list.</p>';
    }
    else{
     //  return ' Great!';
      }

}


//**********************CUSTOM CONFIRMATION
add_filter( 'gform_confirmation', 'custom_confirmation', 1, 4 );
function custom_confirmation( $confirmation, $form, $entry, $ajax ) {  
  global $post;
  $course_title = get_the_title($post->ID);
  //var_dump($course_title);
  $students_allowed = get_field('enrollment', $post->ID);
 // $confirmation = asma_search($course_title, $students_allowed);
 if( $form['id'] == '5' ) {
  if(count($entries) > $students_allowed){
    $confirmation = 'This class is full. We love you but you are on the waiting list';
}else{
  $confirmation = array( 'redirect' => 'https://sola.kau.se/course-registration/confirmation/' );
  }
}
  else{
    $confirmation = 'Thank you! we will contact you soon.';
  }
   return $confirmation;
 }



 //**********************BUILD ENROLLED STUDENT LIST
function asma_find_students_who_enrolled($content){
  global $post;
  if ($post->post_type === 'course' ) {
        $current_user = wp_get_current_user();
        $course_title = get_the_title($post->ID);
        $students_allowed = get_field('enrollment', $post->ID);
        $search_criteria = array(
          'status'        => 'active',
          'field_filters' => array(
              'mode' => 'any',
              array(
                  'key'   => '38', //formerly '38' <------ check this 
                  'value' => $course_title
              )
          )
        );
  $entries  = GFAPI::get_entries( 5, $search_criteria );
  //var_dump($entries);
  if ( ! current_user_can( 'edit_post', $post->ID ) ) {
    //  return '<B> Sorry! You are not allowed to see this!</B>';
    } 
  else{
    echo '<h2>Students Who Enrolled:</h2>';
      if(! $entries){
        return 'No one has enrolled yet!';
      }
      else{
        echo '<ul class="list">';
          foreach ($entries as $key => $value) { 
                  echo '<li> <B> Name: </B>' . $value['1.3'] .' '. $value['1.6'] . ' ' . '<button class="status" data-id= "' . $value['id'] .'"> ' . $value['40'] . '</button>' . '</li>';//<--- check the value of 15 here
          }
       echo'</ul>';
      }
    }
  }
  else {
    return $content; //THIS THE KEY ELEMENT
  }
}

add_filter('the_content', 'asma_find_students_who_enrolled', 1);


//**********************BUILD DATA LIST 
function asma_get_all_data($department, $year){
  $total_count  = 0;
  $sorting = array('key' => '3','direction' => 'ASC' );
  $paging  = array( 'offset' => 0, 'page_size' => 225 );
  
  $search_criteria = array(
  
    'status'    => 'active',
    'field_filters' => array(
      'mode' => 'any',
      array(
          'key'   => '3',
          'value' => $department
      ) ,
      array(
        'key'   => '38',
        'value' => $year
    ) 
      
    )
  );
  $entries  = GFAPI::get_entries( 5, $search_criteria, $sorting, $paging, $total_count);
 // echo count($entries) ; 
   echo ' <li> ' . $department . ',    ' . $year . ',    ' . $total_count . '</li>';
  
  
 // var_dump($entries);
  /*echo '<ul class="list">';
  foreach ($entries as $key => $value) {  
   //var_dump($value);
   echo '<li> ' . $value['1.3'] . '     ' . $value['1.6'] . '  ,   '   . $value['17'] . '  ,  ' . $value['3'] . '</li>';  
    }
 echo'</ul>';*/
}

add_shortcode('show-data', 'asma_grouping');


function asma_grouping(){
  $departments = array('HS', 'HNT', 'Lärarutbildningen');
  $years = array( 2021, 2022, 2023, 2024, 2025);
  
  foreach($departments as $department ) {
    $total_count = 0;
    echo '<B>' . $department .  '</B>';
   
    echo '<ol>';
      foreach( $years as $year ) {
       
        asma_get_all_data( $department, $year);
      }
      echo '</ol>';
  }
 
}


add_action( 'wp_ajax_update_student_status', 'update_student_status' );
 
function update_student_status(){
    $complete =  $_POST['complete'];
    $gf_id = $_POST['gf_id'];    
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
          //write_log($complete);
          
          $entry_id = $gf_id;
          $entry = GFAPI::get_entry( $entry_id );
          write_log($entry);
          $entry['40'] = $complete; // <-----------------------------------------was 40 verify 
          $result = GFAPI::update_entry( $entry );
          
          return $result;
         
          
        }
        die();
}



// not sure what this is . . . . ??????????
add_filter( 'the_content', 'ajax_button', 1 );
 
function ajax_button( $content ) {
    global $post;
    return $content ; //. '<button id="ajax-button" data-gf_id="'.$post->ID.'">click</button>';
}



//$result = GFAPI::update_entry( $entry );<------------------------seems like a stray call?

  //print("<pre>".print_r($result,true)."</pre>");


  add_action( 'pre_get_posts', 'add_my_post_types_to_query' );
 
function add_my_post_types_to_query( $query ) {
    if ( is_home() && $query->is_main_query() )
        $query->set( 'post_type', array( 'post', 'courses' ) );
    return $query;
}



//LOGGER -- like frogger but more useful

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}


//temp <----YOU CAN USE THIS IF YOU WANT TO REMOVE THE CUSTOM FIELDS PLUGIN

//course custom post type

// Register Custom Post Type course
// Post Type Key: course

function create_course_cpt() {

  $labels = array(
    'name' => __( 'Courses', 'Post Type General Name', 'textdomain' ),
    'singular_name' => __( 'Course', 'Post Type Singular Name', 'textdomain' ),
    'menu_name' => __( 'Course', 'textdomain' ),
    'name_admin_bar' => __( 'Course', 'textdomain' ),
    'archives' => __( 'Course Archives', 'textdomain' ),
    'attributes' => __( 'Course Attributes', 'textdomain' ),
    'parent_item_colon' => __( 'Course:', 'textdomain' ),
    'all_items' => __( 'All Courses', 'textdomain' ),
    'add_new_item' => __( 'Add New Course', 'textdomain' ),
    'add_new' => __( 'Add New', 'textdomain' ),
    'new_item' => __( 'New Course', 'textdomain' ),
    'edit_item' => __( 'Edit Course', 'textdomain' ),
    'update_item' => __( 'Update Course', 'textdomain' ),
    'view_item' => __( 'View Course', 'textdomain' ),
    'view_items' => __( 'View Courses', 'textdomain' ),
    'search_items' => __( 'Search Courses', 'textdomain' ),
    'not_found' => __( 'Not found', 'textdomain' ),
    'not_found_in_trash' => __( 'Not found in Trash', 'textdomain' ),
    'featured_image' => __( 'Featured Image', 'textdomain' ),
    'set_featured_image' => __( 'Set featured image', 'textdomain' ),
    'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
    'use_featured_image' => __( 'Use as featured image', 'textdomain' ),
    'insert_into_item' => __( 'Insert into course', 'textdomain' ),
    'uploaded_to_this_item' => __( 'Uploaded to this course', 'textdomain' ),
    'items_list' => __( 'Course list', 'textdomain' ),
    'items_list_navigation' => __( 'Course list navigation', 'textdomain' ),
    'filter_items_list' => __( 'Filter Course list', 'textdomain' ),
  );
  $args = array(
    'label' => __( 'course', 'textdomain' ),
    'description' => __( '', 'textdomain' ),
    'labels' => $labels,
    'menu_icon' => '',
    'supports' => array('title', 'editor', 'revisions', 'author', 'trackbacks', 'custom-fields', 'thumbnail',),
    'taxonomies' => array(),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'exclude_from_search' => false,
    'show_in_rest' => true,
    'publicly_queryable' => true,
    'capability_type' => 'post',
    'menu_icon' => 'dashicons-universal-access-alt',
  );
  register_post_type( 'course', $args );
  
  // flush rewrite rules because we changed the permalink structure
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}
add_action( 'init', 'create_course_cpt', 0 );