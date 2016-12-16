<?php
require_once('scheduler/controller.php');
$courses = getCoursesByDept($_GET['option']);
var_dump($_POST['option']);
var_dump($courses);
foreach($courses as $course){
  echo '<option value='.substr($course, 0,10).'>'.$course.'</option>';
}

?>
