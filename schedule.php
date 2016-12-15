<?php

/**
* Represents a possible schedule, with a set of non-conflicting classes.
*/
class Schedule {

  public $classes; // array(Class)

  public function __construct(array $classArr = array()) {
    $this->$classes = $classArr;
  }

  public function __construct(Schedule &$other, Class $addClass = NULL) {
    $this->$classes = $other->$classes; // copy the classes
    if (!is_null($addClass)) {
      $this->$classes[] = $addClass;
    }
  }

  public function hasClass(Class &$class) {
    return in_array($class, $this->$classes);
  }

  public function hasClass(string &$code) {
    foreach ($this->$classes as $class) {
      if ($class->$code == $code) return true;
    }
    return false;
  }

  public function hasCourseType(Class &$class) {
    foreach ($this->$classes as $c) {
      if ($c->$course == $class->$course &&
          $c->$type   == $class->$type) return true;
    }
    return false;
  }
}

?>