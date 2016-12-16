<?php 

/**
* Represents a particular lecture, lab, etc.
* Assumes that Classes meet on a weekly schedule, and that the meeting time is the same every day.
* Coreq's are classes that *must* be taken with this class. So, discussions have a lecture as a coreq, but not vice-versa.
*/
class Class {

  public $meetingDays;   // array(string)
  public $meetingStart;  // Time
  public $meetingEnd;    // Time
  public $finalDateTime; // DateTime
  public $course;        // string
  public $type;          // string
  public $coreqs;        // array(&Class => true)

  public function __construct(array $meetDays = array(), Time $meetStart = NULL, Time $meetEnd = NULL, DateTime $final = NULL, string $courseName = "", string $meetType = "") {
    if ($meetDays) {
      if (!$meetStart || !$meetEnd) {
        throw new Exception("Class was given 'meetDays', but not 'meetStart' and 'meetEnd'.");
      }
    } else {
      if ($meetStart || $meetEnd) {
        throw new Exception("Class was given 'meetStart' or 'meetEnd', but not 'meetDays'.");
      }
    }

    //Enforce a standard format for meetingDays
    $this->$meetingDays = array(
      'monday'    => false,
      'tuesday'   => false,
      'wednesday' => false,
      'thursday'  => false,
      'friday'    => false,
      'saturday'  => false,
      'sunday'    => false
    );
    foreach ($this->$meetingDays as $day => $meets) {
      if (array_key_exsts($meetDays, $day) && $meetDays[$day]) {
        $this->$meetingDays[$day] = true;
      }
    }

    $this->$meetingStart  = $meetStart;
    $this->$meetingEnd    = $meetEnd;
    $this->$finalDateTime = $final;
    $this->$course        = $courseName;
    $this->$type          = $meetType;
  }

  public function conflictsWith($classOrSchedule) {
    if ($classOrSchedule instanceof Class) {
      return $this->_conflictsWithClass($classOrSchedule);
    } else if ($classOrSchedule instanceof Schedule) {
      return $this->_conflictsWithSchedule($classOrSchedule);
    } else {
      throw new Exception("classOrSchedule must be a Class or Schedule");
    }
  }

  private function _conflictsWithClass(Class $class) {
    foreach ($class->$meetingDays as $day => $meets) {
      if ($meets && $this->$meetingDays[$day]) {
        // Meets on the same day. Need to check the time
        if ($class->$meetingEnd   >= $this->$meetingStart &&
            $class->$meetingStart <= $this->$meetingEnd  ) {
          // There is a conflict. Equivalent condition:
          // !($class->$meetingEnd   < $this->$meetingStart ||
          //   $class->$meetingStart > $this->$meetingEnd)
          return true;
        }
      }
    }
    // All days clear.
    return false;
  }

  private function _conflictsWithSchedule(Schedule $schedule) {
    foreach ($schedule->$classes as $class) {
      if ($this->conflictsWith($class)) return true;
    }
    return false;
  }

  public function addCoreq(Class &$coreq) {
    $this->$coreqs[&$coreq] = true;
  }

  public function duration(string $unit = 'seconds') {
    return $this->$meetingEnd->difference($this->$meetingStart, $unit, true);
  }

  public function daysPerWeek() {
    return array_reduce($this->$meetingDays, function ($sum,$meets) {
      return $sum + ($meets ? 1 : 0);
    }, 0);
  }
}

?>