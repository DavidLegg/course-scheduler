<?php
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once('../controller.php');
    session_start();
    
    if (isset($_GET['_action'])){
//        if ($_GET['_action'] == 'coursebydept')
//        {
//            $courses = getCoursesByDept($_GET['_param']);
//            foreach($courses as $name => $course){
//                echo '<option value="',$name,'">',$name,'</option>';
//            }
//        }
//        else if ($_GET['_action'] == 'addcourse')
//        {
//            $_SESSION['addedCourses'][$_GET['_param']] = $_SESSION['availableCourses'][$_GET['_param']];
//            foreach($_SESSION['addedCourses'] as $name => $course){
//                echo '<li id="',$name,'" style="color:#0039ad;">',$name,'</li>';
//            }
//        }
//        else if ($_GET['_action'] == 'schedule')
//        {
//            $schedules = generateSchedules();
//            echo "Course Selection: ";
//            $courseList = '';
//            foreach ($_SESSION['addedCourses'] as $name => $course){
//                $courseList .= $name . ", ";
//            }
//            echo '<span style="color:#0039ad;">',substr($courseList, 0, -2), "</span><br/>";
//            foreach ($schedules as $sched) {
//                echo $sched,"<hr>";
//            }
//        }

        switch($_GET['_action']){
            case 'coursebydept':
                $courses = getCoursesByDept($_GET['_param']);
//                var_dump($courses);
                foreach($courses as $name => $course){
                    echo '<option value="',$name,'">',$course->name,'</option>';
                }
                var_dump($_SESSION['availableCourses']);
                break;
            case 'addcourse':
                $_SESSION['addedCourses'][$_GET['_param']] = $_SESSION['availableCourses'][$_GET['_param']];
                foreach($_SESSION['addedCourses'] as $name => $course){
                    echo '<li id="',$name,'" style="color:#0039ad;">',$course->name,'</li>';
                }
                break;
            case 'schedule':
                $schedules = generateSchedules();
                echo "Course Selection: ";
                $courseList = '';
                foreach ($_SESSION['addedCourses'] as $course){
                    $courseList .= $course->name . ", ";
                }
                echo '<span style="color:#0039ad;">',substr($courseList, 0, -2), "</span><br/>";
                foreach ($schedules as $sched) {
                    echo $sched,"<hr>";
                }
                break;
            default:
                break;
        }
    }
    ?>
