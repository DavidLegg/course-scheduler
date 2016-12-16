<?php

require_once './preferenceCategory.php';
require_once './preferences.php';
require_once './time.php';

$StandardPreferences = (function() {
  function daysPerWeek(Class $c) {
    return array_reduce($c->$meetingDays, function ($sum,$meets) {
      return $sum + ($meets ? 1 : 0);
    }, 0);
  }

  return Preferences(array(
    PreferenceCategory('mornings', function($sched) {
      $cutoff = Time(11,00);
      $score  = 0.0;
      foreach ($sched->$classes as $c) {
        if ($c->$startTime <= $cutoff) {
          $score += ($cutoff->difference($c->$startTime, 'hours', true) * daysPerWeek($c));
        }
      }
      // score should be in the 0-20 range, which is acceptable.
      return $score;
    })
  ));
})();

?>