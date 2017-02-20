<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'controller.php';
require_once 'smart_schedule.php';

function instrument($callback, $n) {
  echo 'Starting...<br>';
  $start = microtime(true);
  for ($i=0; $i < $n; $i++) {
    echo $i.',';
    $result = $callback();
  }
  $end = microtime(true);
  echo '<br>Finished. Execution time: '.($end - $start).' s.<br>';
  return $result;
}

echo 'Loaded.<br>';

$writing39B = UCI_WebSoc::getCourse('WRITING 39B');
$writing39C = UCI_WebSoc::getCourse('WRITING 39C');
$math2B     = UCI_WebSoc::getCourse('MATH 2B');

echo 'Sizes:<br>39B: ', $writing39B->numCombos(),
     '<br>39C: ', $writing39C->numCombos(),
     '<br>2B: ', $math2B->numCombos(), '<br>';

$courses = array($writing39B, $writing39C, $math2B);
$standardPreferences->changeWeight('mornings', -1);
$standardPreferences->changeWeight('evenings', -2);

$schedules = instrument(function() use ($courses, $standardPreferences) {
  return smartSchedule($courses, $standardPreferences, 10);
}, 1);

echo sizeof($schedules), ' schedules produced.<br>';

?>