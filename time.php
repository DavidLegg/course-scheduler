<?php

/**
* A simple extension of the DateTime class for storing, manipulating, and comparing Times.
* Guarantees correct comparison of times by fixing date.
*/
class Time extends DateTime {
  public function __construct(string $s) {
    parent::__construct($s);
    $this->setDate(2000,1,1);
  }

  
  // Dummy function, actually prohibits setting the date.
  public function setDate(int $year, int $month, int $day) {
    throw new Exception("Cannot set Date in a Time object.");
  }

  public function __toString() {
    // The most useful format for this type
    return $this->format("g:i a");
  }
}

?>