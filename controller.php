<?php
    ini_set('display_errors', 1);
    
    if (!defined('ROOT_PATH')) define('ROOT_PATH', __DIR__.'/');
    
    require_once ROOT_PATH.'uci_websoc.php';
    
    global $xpath, $baseQueryUrl, $addedCourses, $availableCourses;
    $baseUrl = 'https://www.reg.uci.edu/perl/WebSoc';
    $baseQueryUrl = $baseUrl.'?Submit=Display+XML+Results&ShowFinals=1&ShowComments=0';
    
    $html = file_get_contents($baseUrl);
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
   
    
    function getDropDownItems($name) {
        global $xpath;
        //   var_dump($name);
        $allOpts = $xpath->query('//select[@name="'.$name.'"]')[0];
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
        $courses = UCI_WebSoc::getCoursesByDept($dept);
        $_SESSION['availableCourses'] += $courses;
//        var_dump($_SESSION['availableCourses']);
        return $courses;
    }
    
    
    
    ?>
