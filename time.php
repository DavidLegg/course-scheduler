<?php

/**
* A simple extension of the DateTime class for storing, manipulating, and comparing Times.
* Guarantees correct comparison of times by fixing date.
*/
class Time extends DateTime {
  public function __construct($hoursOrString, int $minutes = 0, int $seconds = 0) {
    if (is_string($hoursOrString)) {
      parent::__construct("1/1/2000 ".$hoursOrString); //let the DateTime constructor handle parsing
    } else {
      parent::__construct(); //get a default time object
      parent::setDate(2000,1,1); //load a standard date
      parent::setTime($hoursOrString, $minutes, $seconds); //parse the time passed
    }
  }
  
  // Dummy function, actually prohibits setting the date.
  public function setDate($year, $month, $day) {
    throw new Exception("Cannot set Date in a Time object.");
  }

  public function __toString() {
    // The most useful format for this type
    return $this->format("g:i a");
  }

  public function difference(Time $other, string $unit = "seconds", bool $absolute = false) {
    $inl = $this->diff($other, $absolute); //DateInterval

    switch (strtolower($unit)) {
      case 's':
      case 'sec':
      case 'secs':
      case 'second':
      case 'seconds':
        $conv = array(
          'd' => 86400,
          'h' => 3600,
          'i' => 60,
          's' => 1);
        break;
      case 'm':
      case 'min':
      case 'mins':
      case 'minute':
      case 'minutes':
        $conv = array(
          'd' => 1440,
          'h' => 60,
          'i' => 1,
          's' => 1/60);
        break;
      case 'h':
      case 'hr':
      case 'hrs':
      case 'hour':
      case 'hours':
        $conv = array(
          'd' => 24,
          'h' => 1,
          'i' => 1/60,
          's' => 1/3600);
        break;
      default:
        throw new Exception("Unrecognized unit of time.");
    }

    return (($inl->$d * $conv['d']) +
            ($inl->$h * $conv['h']) +
            ($inl->$i * $conv['i']) +
            ($inl->$s * $conv['s'])) * ($inl->$invert ? -1 : 1);

  }
}

?>