<?php
ini_set('display_errors', 1);
require_once('controller.php');
$courses = getCoursesByDept($_GET['option']);
//var_dump($_GET['option']);
//var_dump($courses);
foreach($courses as $course){
  echo '<option value='.substr($course, 0,15).'>'.$course.'</option>';
}

?>
