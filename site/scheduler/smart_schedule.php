<?php 

/**
 * Builds the (roughly) top n schedules according to the preferences given.
 * Does it's best to minimize computation, and should be used rather than the
 * brute force methods in the Course classes.
 * 
 * Does a beam-search-esque procedure, only considering some number of top schedules at each iteration.
 * 
 * @param courses The array of Courses to be scheduled.
 * @param preferences The Preferences object used to score schedules.
 * @param n The number of top-scoring schedules to be returned.
 * @param marginFactor The number (as a factor of n) of schedules to consider in intermediate steps. Higher numbers give potentially more accurate, but slower, results.
 */
function smartSchedule($courses, &$preferences, $n, $marginFactor = 2) {
  usort($courses, function($c1, $c2) {
    return $c1->numCombos() - $c2->numCombos();
  });
  echo "DEBUG: courses: array("; foreach($courses as $v) echo $v,","; echo ")<br>"; //DEBUG

  $schedules = array();
  foreach ($courses as $course) {
    $schedules = $course->buildSchedules($schedules);
    $preferences->sort($schedules);
    // pare down the schedules: this is where we save time.
    $schedules = array_slice($schedules, 0, $n * $marginFactor);
  }
  return array_slice($schedules, 0, $n);
}

/**
 * Builds the (roughly) top n schedules according to the preferences given.
 * Does it's best to minimize computation, and should be used rather than the
 * brute force methods in the Course classes.
 *
 * Recursive. Uses forward-checking on each step to limit branching factor.
 * 
 * @param courses The array of Courses to be scheduled.
 * @param preferences The Preferences object used to score schedules.
 * @param n The number of top-scoring schedules to be returned.
 */
function smartSchedule2($courses, &$preferences, $n) {
  // TODO: implement this.
  // IDEA: first, separate courses into just a flat array of sections.
  //       Call a recursive function schedule_section on this.
  //       A section is added to a temp schedule, passed to next level of recursion.
  //       At the same time, everything that section conflicts with is pared from the list of choices, so that at any level, everything to choose works with everything already chosen.
  //       Then, we do a greedy selection at that level.
  //       When a schedule is created, look at a global list of top n schedules, with a globally cached "best of worst" score. If over that score, push into list.
  //       Alternatively, just push the first n to 2n onto a list, then terminate.
}

?>