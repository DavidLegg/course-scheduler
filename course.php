<?php

/**
* Defines a collection of classes that must be taken to satisfy a course.
* For example, might include the options for a lecture, lab, and discussion.
*/
class Course {
  
  public $classArr; // array(string => array(Class))

  function __construct(array $classes = array()) {
    $this->$classArr = array();
    foreach ($classes as $class) {
      if (!array_key_exists($class->$type, $this->$classArr)) {
        $this->$classArr[$class->$type] = array();
      }
      $this->$classArr[$class->$type][] = $class;
    }
  }

  /**
   * Builds all possible schedules based on the current schedule,
   * incorporating one of each necessary Class for this Course.
   */
  public function buildSchedules(schedule $currentSchedule = NULL) {
    if (is_null($currentSchedule)) {
      $currentSchedule = new Schedule();
    }

    return Course::_buildSchedules($currentSchedule, $this->$classArr);
  }

  private static function _buildSchedules(Schedule $sched, array $classArr) {
    if (!$classArr) return array($sched);

    $output = array();

    // Loop through all classes of one type:
    foreach (array_pop($classArr) as $class) {
      // if we can add this course to our current schedule...
      if (!$class->conflictsWith($sched)) {
        // do so, and recursively try to add the other classes
        $output += Course::_buildSchedules(new Schedule($sched, $class), $classArr);
      }
    }

    return $output;
  }
}

?>