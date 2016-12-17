<?php

/**
* Represents a possible schedule, with a set of non-conflicting classes.
*/
class Schedule {

  public $classes; // array(Class)

  public function __construct($classSource = NULL /*array(Class) or Schedule*/, Class $addClass = NULL) {
    $classSource = is_null($classSource) ? array() : $classSource;

    if ($classSource instanceof Schedule) {
      $this->$classes = $other->$classes; // copy the classes
    } else if (is_array($classSource)) {
      $this->$classes = array();
      foreach ($classSource as $class) {
        if ($c instanceof Class) {
          $this->$classes[] = $c;
        } else {
          throw new Exception("classSource must be an array of Class objects");
        }
      }
    }
    if (!is_null($addClass)) {
      $this->$classes[] = $addClass;
    }
  }

  public function hasClass(&$classOrCode /*Class or string*/) {
    if ($classOrCode instanceof Class) {
      return $this->_hasClass($classOrCode);
    } else {
      return $this->_hasClassCode((string)$classOrCode);
    }
  }

  private function _hasClass(Class &$class) {
    return in_array($class, $this->$classes);
  }

  private function _hasClassCode(string &$code) {
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