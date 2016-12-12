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
}

?>