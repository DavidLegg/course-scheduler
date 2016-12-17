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
            $courses[$course]->addSection(new Section(
                                                      $days, $startTime, $endTime, $finalDateTime, $course, $type, $code
                                                      ));
        }
        fclose($handle);
        return $courses;
    }
    echo "readCSV function defined.<br>";
    $courses = readCSV(ROOT_PATH."testdata.csv");
    
    // $schedules = array();
    // foreach ($courses as $name => $course) {
    //     echo "<h2>$name</h2>";
    //     if (empty($schedules))
    //         $schedules = $course->buildSchedules();
    //     else{
    //         $newSchedules = array();
    //         foreach($schedules as $schedule)
    //         {
    //             $newSchedules = array_merge($newSchedules, $course->buildSchedules($schedule));
    //         }
    //         $schedules = $newSchedules;
    //     }
    //     foreach ($course->sectionArr as $type => $sections) {
    //         echo "<h3>$type</h3>";
    //         foreach ($sections as $s) {
    //             print_section($s);
    //             echo "<br>";
    //         }
    //     }
    // }

    foreach ($courses as $name => $course) {
        echo "<h2>$name</h2>";
        foreach ($course->sectionArr as $type => $sections) {
            echo "<h3>$type</h3>";
            foreach ($sections as $s) {
                echo $s,"<br>"; //use default section toString
            }
        }
    }

    echo "<hr><hr><br>";

    $schedules = $courses['Math 9']->buildSchedules();
    // Correctly, schedules has 1 element.
    $schedules = $courses['Soc Sci H1F']->buildSchedules($schedules);
    // Should build 5 schedules
    $schedules = $courses['Stats 67']->buildSchedules($schedules);
    // Should build lots of schedules...

    echo "<h2>Schedules</h2>";
    foreach ($schedules as $sched) {
      echo "<hr>",$sched;
    }
    
    
    
    
    
    ?>
