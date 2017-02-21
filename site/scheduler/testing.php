<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'controller.php';
require_once 'smart_schedule.php';

function instrument($callback, $n) {
  echo 'Starting...<br>';
  $start = microtime(true);
  for ($i=0; $i < $n; $i++) {
    echo $i, ',';
    $result = $callback();
  }
  $end = microtime(true);
  echo '<br>Finished. Execution time: ', ($end - $start), ' s.<br>';
  return $result;
}

echo 'Loaded.<br>';

$courses = array(
  UCI_WebSoc::getCourse('MUSIC H80'),
  UCI_WebSoc::getCourse('MATH 9'),
  UCI_WebSoc::getCourse('PHYSICS 7E'),
  UCI_WebSoc::getCourse('MATH 130A'),
);

$standardPreferences->changeWeight('mornings', -10);
$standardPreferences->changeWeight('evenings', -20);
$standardPreferences->changeWeight('gaps', -40);

$n = 20;


echo "<table><tr><td>";
echo "--- Smart Algorithm ---<br>";

$smart_schedules = instrument(function() use ($courses, $standardPreferences, $n) {
  return smartSchedule($courses, $standardPreferences, $n);
}, 1);

echo sizeof($smart_schedules), ' schedules produced.<br><br>';

foreach ($smart_schedules as $sched) {
  foreach ($sched->sections as $s) {
    echo $s->code, ',';
  }
  echo '<br>';
}


echo "</td><td>";
echo "--- Brute Force algorithm ---<br>";

$brute_schedules = instrument(function() use ($courses, $standardPreferences, $n) {
  return bruteSchedule($courses, $standardPreferences, $n);
}, 1);

echo sizeof($brute_schedules), ' schedules produced.<br><br>';

$i = 0;
$all_same = True;
foreach ($brute_schedules as $sched) {
  $same = True;
  $smart = $smart_schedules[$i++]->sections;
  foreach ($sched->sections as $s) {
    echo $s->code, ',';
    $same = $same && in_array($s,$smart);
  }
  echo ($same ? '' : ' ---!!!'), '<br>';
  $all_same = $all_same && $same;
}

echo "</td></tr></table><br>";

echo ($all_same) ? 'All same.' : 'Some different.' ;

?>