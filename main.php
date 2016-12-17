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
            list($course,$type,$shortDays,$times,$final,$code) = explode(',', $line);
            
            $days = array();
            foreach ($dayCodes as $short => $long) {
                $days[$long] = is_int(strpos($shortDays,$short)); //shortDays has short
            }
            list($start,$end) = explode('-',$times);
            $startTime = new Time($start);
            $endTime   = new Time($end);
            $final = preg_replace("/-\d{1,2}:\d{2}/","",$final); //trim out ending time
            //      var_dump($final);
            $finalDateTime = new DateTime($final);
            if (!array_key_exists($course, $courses)) {
                $courses[$course] = new Course($course);
            }
            $courses[$course]->addSection(new Section(
                                                      $days, $startTime, $endTime, $finalDateTime, $course, $type
                                                      ));
        }
        fclose($handle);
        return $courses;
    }
    echo "readCSV function defined.<br>";
    $courses = readCSV(ROOT_PATH."testdata.csv");

    function print_section(Section $section) {
      echo $section->course," ",$section->type,": ";
      foreach ($section->days as $day => $meets) {
        if ($meets) echo substr($day,0,2)," ";
      }
      echo $section->start," - ",$section->end,".";
    }
    
    $schedules = array();
    
    foreach ($courses as $name => $course) {
        echo "<h2>$name</h2>";
        if (empty($schedules))
            $schedules = $course->buildSchedules();
        else{
            $newSchedules = array();
            foreach($schedules as $schedule)
            {
                $newSchedules = array_merge($newSchedules, $course->buildSchedules($schedule));
            }
            $schedules = $newSchedules;
        }
        foreach ($course->sectionArr as $type => $sections) {
            echo "<h3>$type</h3>";
            foreach ($sections as $s) {
                // var_dump($c);
                print_section($s);
            }
        }
    }
    
    echo "<br/><br/>";
    echo "<h2>Schedules</h2><br>";
    var_dump($schedules);
    
    
    
    
    
    ?>
