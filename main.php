<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    echo "Hello, world!<br>PHP is running correctly.";
    if (!defined('ROOT_PATH')) define('ROOT_PATH',__DIR__.'/');
    require_once  ROOT_PATH.'time.php';
    require_once  ROOT_PATH.'section.php';
    require_once  ROOT_PATH.'course.php';
    require_once  ROOT_PATH.'schedule.php';
    require_once  ROOT_PATH.'standardPreferences.php';
    echo "<br>All classes loaded correctly.<br>";
    /**
     * A quick hack to load the test data.
     * A small amount of this code will be transferrable to classretrieval.
     */
    function readCSV($file) {
        $handle = fopen($file, "r");
        if (!$handle) {
            throw new Exception("Could not open file.");
        }
        $courses = array(); //name => course
        $lastLecture = NULL;
        $dayCodes = array(
                          "M" => "monday",
                          "Tu" => "tuesday",
                          "W" => "wednesday",
                          "Th" => "thursday",
                          "F" => "friday"
                          );
        while (($line = fgets($handle, 4096)) !== false) {
            list($course,$type,$shortDays,$start,$end,$final,$code) = explode(',', $line);
            
            $days = array();
            foreach ($dayCodes as $short => $long) {
                $days[$long] = is_int(strpos($shortDays,$short)); //shortDays has short
            }
            $startTime = new Time($start);
            $endTime   = new Time($end);
            $final = preg_replace("/-\d{1,2}:\d{2}/","",$final); //trim out ending time
            $finalDateTime = new DateTime($final);
            if (!array_key_exists($course, $courses)) {
                $courses[$course] = new Course($course);
            }
            $sec = new Section($days, $startTime, $endTime, $finalDateTime, $course, $type, $code);

            if ($type == 'Lec') {
              $lastLecture = $sec;
            } else if ($type != 'Lab') {
              $sec->addCoreq($lastLecture);
            }

            $courses[$course]->addSection($sec);
        }
        fclose($handle);
        return $courses;
    }
    echo "readCSV function defined.<br>";
    
    echo "<h1>Schedules Test: David</h1>";
    $courses = readCSV(ROOT_PATH."testdata.csv");
    
    $schedules = array();
    foreach ($courses as $name => $course) {
        echo "<h2>$name</h2>";
        foreach ($course->sectionArr as $type => $sections) {
            echo "<h3>$type</h3>";
            foreach ($sections as $s) {
                echo $s,"<br>"; //use default section toString
            }
        }

        $schedules = $course->buildSchedules($schedules); //build all possible schedules
    }

    $standardPreferences->changeWeight('mornings', 5);

    echo "<hr><hr><h2>Schedules (",count($schedules),")</h2>";
    echo "Ranked by:<br>",$standardPreferences,"<br>";
    $standardPreferences->sort($schedules);
    foreach ($schedules as $sched) {
      echo "<hr>Score:",$standardPreferences->score($sched),"<br>",$sched;
    }    
    
    echo "<h1>Schedules Test: Alex</h1>";
    echo "<h3>Writing 39C not included</h3>";
    $courses = readCSV(ROOT_PATH."testdata_air.csv");
    
    $schedules = array();
    foreach ($courses as $name => $course) {
        echo "<h2>$name</h2>";
        foreach ($course->sectionArr as $type => $sections) {
            echo "<h3>$type</h3>";
            foreach ($sections as $s) {
                echo $s,"<br>"; //use default section toString
            }
        }
        
        $schedules = $course->buildSchedules($schedules); //build all possible schedules
    }
    
    echo "<hr><hr><h2>Schedules (",count($schedules),")</h2>";
    foreach ($schedules as $sched) {
        echo "<hr>",$sched;
    }
    

?>
