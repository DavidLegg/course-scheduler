<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Hello, world!<br>PHP is running correctly.";

define('ROOT_PATH',__DIR__.'/');

require_once ROOT_PATH.'time.php';
require_once ROOT_PATH.'section.php';
require_once ROOT_PATH.'course.php';
require_once ROOT_PATH.'schedule.php';
require_once ROOT_PATH.'standardPreferences.php';

echo "<br>All classes loaded correctly.<br>";
// 
// /**
//  * A quick hack to load the test data.
//  * A small amount of this code will be transferrable to classretrieval.
//  */
// function readCSV($file) {
//   $handle = fopen($file, "r");
//   if (!$handle) {
//     throw new Exception("Could not open file.");
//   }

//   $courses = array(); //name => course

//   $dayCodes = array(
//     "M" => "monday",
//     "Tu" => "tuesday",
//     "W" => "wednesday",
//     "Th" => "thursday",
//     "F" => "friday"
//   );
//   while (($line = fgets($handle, 4096)) !== false) {
//     list($course,$type,$shortDays,$times,$final,$code) = explode(',', $line);
    
//     $days = array();
//     foreach ($dayCodes as $short => $long) {
//       $days[$long] = is_int(strpos($shortDays,$short)); //shortDays has short
//     }

//     list($start,$end) = explode('-',$times);
//     $startTime = new Time($start);
//     $endTime   = new Time($end);

//     $final = preg_replace("/-\d{1,2}:\d{2}/","",$final); //trim out ending time
//     $finalDateTime = new DateTime($final);

//     if (!array_key_exists($course, $courses)) {
//       $courses[$course] = new Course($course);
//     }
//     $courses[$course]->addClass(new Class(
//       $days, $startTime, $endTime, $finalDateTime, $course, $type
//     ));
//   }
//   fclose($handle);

//   return $courses;
// }

// $courses = readCSV("./testdata.csv");

// foreach ($courses as $name => $course) {
//   echo "<h2>$name</h2><br>";
//   foreach ($course->classArr as $type => $classes) {
//     echo "<h3>$type</h3><br>";
//     foreach ($classes as $c) {
//       var_dump($c);
//     }
//   }
// }


?>