<?php
    error_reporting(E_ALL);
    
    ini_set('display_errors', 1);
    
    if (!defined('ROOT_PATH')) define('ROOT_PATH', __DIR__.'/');
    
    require_once ROOT_PATH.'uci_websoc.php';
    require_once  ROOT_PATH.'time.php';
    require_once  ROOT_PATH.'section.php';
    require_once  ROOT_PATH.'course.php';
    require_once  ROOT_PATH.'schedule.php';
    require_once  ROOT_PATH.'standardPreferences.php';
    session_start();
    
    global $xpath, $baseQueryUrl, $addedCourses, $availableCourses;
    $baseUrl = 'https://www.reg.uci.edu/perl/WebSoc';
    $baseQueryUrl = $baseUrl.'?Submit=Display+XML+Results&ShowFinals=1&ShowComments=0';
    
    $html = file_get_contents($baseUrl);
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
//    if (!isset($_SESSION['availableCourses'])) $_SESSION['availableCourses']  = array();
//    if (!isset($_SESSION['addedCourses'])) $_SESSION['addedCourses']  = array();
    
    
   
    
    function getDropDownItems($name) {
        global $xpath;
//           var_dump($name);
        $allOpts = $xpath->query('//select[@name="'.$name.'"]')[0];
//        var_dump($allOpts);
        //   var_dump($allOpts);
        return $allOpts->getElementsByTagName("option");
    }
    
    function appendTerm(){
        global $baseQueryUrl;
        $term = getDropDownItems('YearTerm')[0];
        $termId = $term->getAttribute('value');
        UCI_WebSoc::setYearTerm($termId);
    }
    
    function changeTerm($str, $print = false){
        UCI_WebSoc::setYearTerm($str);
        if ($print) echo UCI_WebSoc::getYearTerm($str);
    }
    
    appendTerm();
    
    function getCoursesByDept($dept, $yearTerm){
//        $courses = array();
        changeTerm($yearTerm);
        list($courses,$len) = UCI_WebSoc::getCoursesByDept($dept,$yearTerm);
        if (!isset($_SESSION['availableCourses'])) $_SESSION['availableCourses']  = array();
        $intersect = array_intersect($_SESSION['availableCourses'], $courses);
//        var_dump($intersect);
//        $_SESSION['availableCourses'] = $_SESSION['availableCourses'] + $courses;
        
//        foreach($courses as $name => $course){
//            $_SESSION['availableCourses'][$name] = $course;
//        }
//        var_dump($_SESSION['availableCourses']);
        if (!empty($intersect))
        {
            return $intersect;
        }
        else
        {
            $_SESSION['availableCourses'] = array_merge($_SESSION['availableCourses'],$courses);
            return $intersect + array_slice($_SESSION['availableCourses'], $len*-1, $len, true);
        }
        
    }
    
    function generateSchedules(){
        $schedules = array();
        if (isset($_SESSION['addedCourses']))
            foreach ($_SESSION['addedCourses'] as $course) {
                $schedules = $course->buildSchedules($schedules); //build all possible schedules
            }
        return $schedules;
    }
    
    function listAddedCourses($first = false){
        if (!isset($_SESSION['addedCourses']) || empty($_SESSION['addedCourses'])){
            echo "<p>Select some courses first. Then they'll appear here.</p>";
            echo '<script>$("#genSched").prop("disabled", true);</script>';
        }
        else{
            foreach($_SESSION['addedCourses'] as $name => $course){
                echo '<li id="',$name,'" style="color:#0039ad;">',$course->name,' <a onclick="delPopCourses('.$name.');" href="javascript:void(0)">[X]</a></li>';
            }
            echo '<script>$("#genSched").prop("disabled", false);</script>';
//            echo '<button type="button" onClick="generateSchedules();" >Schedule classes</button>';
            if ($first) echo '<script> generateSchedules();</script>';
        }
    }
    
    
    ?>
