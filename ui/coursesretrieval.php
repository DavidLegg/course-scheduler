<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once('../controller.php');
//    //global $addedCourses;
//    
    echo "LOL";
    var_dump( $_GET['_param']);
    switch($_GET['_action']){
        case 'coursebydept':
            $courses = getCoursesByDept($_GET['_param']);
            //var_dump($_GET['option']);
            //var_dump($courses);
            foreach($courses as $name => $course){
                //    echo $course;
                echo '<option value="',$name,'">',$name,'</option>';
            }
            break;
        case 'addcourse':
//            var_dump($_SESSION['addedCourses']);
            $_SESSION['addedCourses'][$_GET['_param']] = $_SESSION['availableCourses'][$_GET['_param']];
            foreach($_SESSION['addedCourses'] as $name => $course){
                echo '<li id="',$name,'">',$name,'</li>';
            }
            break;
        default:
            break;
    }
    
    ?>
