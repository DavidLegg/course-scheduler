<?php
ini_set('display_errors', 1);
global $xpath, $baseQueryUrl;
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
  $baseQueryUrl .= '&YearTerm='.$termId;
}

appendTerm();

function getCoursesByDept($dept){
  global $baseQueryUrl;
  $requestStr=$baseQueryUrl.'&Dept='. urlencode($dept);
//  echo($requestStr);
  $xml_dept = file_get_contents($requestStr);
  $dom_dept = new DOMDocument;
  $dom_dept->loadXML($xml_dept);
  $xpath_dept = new DOMXPath($dom_dept);
  

  $allCoursesQuery = $xpath_dept->query('//course');
//var_dump($allCoursesQuery);
  $allCourses = array();
  
  foreach($allCoursesQuery as $course){
      array_push($allCourses, array($dept,$course->getAttribute('course_number'),$course->getAttribute('course_title')));
  }
  return $allCourses;
  
}



?>
