<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../controller.php');
$courses = getCoursesByDept($_GET['option']);
//var_dump($_GET['option']);
//var_dump($courses);
foreach($courses as $course){
//    echo $course;
    list($dept, $num, $title) = $course;
  echo '<option value="',$dept, ' ', $num,'">',$dept,' ',$num,' - ',$title,'</option>';
}

?>
