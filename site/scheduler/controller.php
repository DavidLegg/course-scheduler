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
    
    global $xpath, $baseQueryUrl, $addedCourses, $availableCourses;
    $baseUrl = 'https://www.reg.uci.edu/perl/WebSoc';
    $baseQueryUrl = $baseUrl.'?Submit=Display+XML+Results&ShowFinals=1&ShowComments=0';
    
    $html = file_get_contents($baseUrl);
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    if (!isset($_SESSION['availableCourses'])) $_SESSION['availableCourses']  = array();
    if (!isset($_SESSION['addedCourses'])) $_SESSION['addedCourses']  = array();
    
   
    
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
    
    appendTerm();
    
    function getCoursesByDept($dept){
//        $courses = array();
        list($courses,$len) = UCI_WebSoc::getCoursesByDept($dept);
        $_SESSION['availableCourses'] = array_merge($_SESSION['availableCourses'],$courses);
//        foreach($courses as $name => $course){
//            $_SESSION['availableCourses'][$name] = $course;
//        }
//        var_dump($_SESSION['availableCourses']);
        return array_slice($_SESSION['availableCourses'], $len*-1, $len, true);
    }
    
    function generateSchedules(){
        $schedules = array();
        foreach ($_SESSION['addedCourses'] as $course) {
            $schedules = $course->buildSchedules($schedules); //build all possible schedules
        }
        return $schedules;
    }
    
    
    ?>
