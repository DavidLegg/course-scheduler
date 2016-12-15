<?php

/**
* Defines a collection of classes that must be taken to satisfy a course.
* For example, might include the options for a lecture, lab, and discussion.
*/
class Course {
  
  public $name; // string
  protected $classArr; // array(string => array(Class)) : Class->type => {Class}
  //If a class code is a key in the restrictions,
  // then for the type(s) in the returned array, only the listed classes can be taken.
  //For example, Lecture A for a course might require Discussions 1-5, while Lec B requires Discussions 6-10.

  function __construct(string $courseName = "", array $classes = array()) {
    $this->$classArr = array();
    foreach ($classes as $class) {
      if (!array_key_exists($class->$type, $this->$classArr)) {
        $this->$classArr[$class->$type] = array();
      }
      $this->$classArr[$class->$type][] = $class;
    }

    $this->$name = $courseName
  }

  /**
   * Builds all possible schedules based on the current schedule,
   * incorporating one of each necessary 'type' of Class for this Course.
   */
  public function buildSchedules(Schedule $currentSchedule = NULL) {
    if (is_null($currentSchedule)) {
      $currentSchedule = new Schedule();
    }

    return $this->_buildSchedules($currentSchedule, $this->$classArr);
  }

  private function _buildSchedules(Schedule $sched, array $classArr) {
    if (!$classArr) return array($sched);

    $classes = array_pop($classArr);
    // if (!is_empty(array_intersect($classes,$sched->$classes))) {
    foreach ($classes as $class) {
      if ($schedule.hasClass($class)) {
        // the schedule already contains this 'type' of class, skip the process at this level.
        return $this->_buildSchedules($sched, $classArr);
      }
    }

    $output = array();
    // Loop through all classes of one type:
    foreach ($classes as $class) {
      if ($class->conflictsWith($sched)) continue; // simply can't use it.
      // Note: since any class conflicts with itself, this check also stops duplicates (e.g., from coreq additions)

      $newSched = new Schedule($sched, $class);
      foreach ($class->$coreqs as $req => $x) {
        if ($newSched->hasClass($req)) continue; // class already present
        if ($newSched->hasCourseType($req)) break 2; // has the 'type' of this req for this req's course, but not this one. Excludes req.
        if ($req->conflictsWith($newSched)) break 2; // break out of coreq loop *and* this class loop.
        $newSched = new Schedule($newSched, $req); // else, add class to schedule
      }
      //now we've guaranteed all coreqs have been added.

      $output += $this->_buildSchedules($newSched, $classArr); //compute next level
    }

    return $output;
  }
}

?>