<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once('../controller.php');
    
    switch($_POST['action']){
        case 'coursebydept':
            $courses = getCoursesByDept($_POST['option']);
            //var_dump($_GET['option']);
            //var_dump($courses);
            foreach($courses as $course){
                //    echo $course;
                echo '<option value="',$course->name,'">',$course->name,'</option>';
            }
            break;
        default:
            break;
    }
    
    ?>
