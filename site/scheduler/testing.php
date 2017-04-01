<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'controller.php';
require_once 'smart_scheduler.php';

function instrument($callback, $n = 1, $s = 30) {
  echo 'Starting...<br>';
  $start = microtime(true);
  for ($i=0; $i < $n; $i++) {
    set_time_limit($s);
    if ($n > 1) echo $i, ',';
    $result = $callback();
  }
  $end = microtime(true);
  echo 'Finished. Execution time: ', ($end - $start), ' s.<br>';
  return $result;
}

function standardize_schedule_list($schedules) {
  $score_codes = array_map(function ($schedule) {
    global $standardPreferences;
    $codes = array_map(function ($section) { return $section->code; }, $schedule->sections);
    sort($codes);
    return array($standardPreferences->score($schedule), $codes);
  }, $schedules);
  rsort($score_codes); // since there are the same number of codes in each schedule, this is a lexicographic secondary key
  return $score_codes;
}

echo 'Loaded.<br>';

UCI_WebSoc::setYearTerm('2017-14');

$courses = array(
  UCI_WebSoc::getCourse('WRITING 39B'),
  // UCI_WebSoc::getCourse('WRITING 39C'),
  UCI_WebSoc::getCourse('MATH 2A')
);

$standardPreferences->changeWeight('mornings', -10);
$standardPreferences->changeWeight('evenings', -20);
$standardPreferences->changeWeight('gaps'    , -40);

$n = 20;
$timeLimit = 120;

echo "<table><tr><td>";
echo "--- FC Algorithm ---<br>";

$smart_schedules = instrument(function() use ($courses, $standardPreferences, $n) {
  return SmartScheduler::fcSchedule($courses, $standardPreferences, $n);
},1,$timeLimit);

echo count($smart_schedules), ' schedules produced.<br><br>';

$smart_score_codes = standardize_schedule_list($smart_schedules);

foreach ($smart_score_codes as list($score, $codes)) {
  echo $score, ': ';
  foreach ($codes as $code) {
    echo $code, ',';
  }
  echo '<br>';
}


echo "</td><td>&nbsp;&nbsp;&nbsp;</td><td>";
echo "--- Brute Force algorithm ---<br>";

$brute_schedules = instrument(function() use ($courses, $standardPreferences, $n) {
  return SmartScheduler::bruteForceSchedule($courses, $standardPreferences, $n);
},1,$timeLimit);

echo count($brute_schedules), ' schedules produced.<br><br>';

$brute_score_codes = standardize_schedule_list($brute_schedules);

foreach (array_map(null, $brute_score_codes, $smart_score_codes) as list(list($bscore, $bcodes), list($sscore, $scodes))) {
  echo $bscore, ': ';
  foreach ($bcodes as $bcode) {
    echo $bcode, ',';
  }
  echo ($scodes == $bcodes ? '' : ' -1'), '<br>';
}

echo "</td></tr></table><br>";

?>