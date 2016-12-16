<?php
require_once('scheduler/controller.php');


$courses = getCoursesByDept($_POST['option']);
foreach($courses as $course){
  echo '<option value='.substr($course, 0,10).'>'.$course.'</option>;
}


?>
