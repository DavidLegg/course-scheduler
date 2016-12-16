<?php

/**
* Defines a user's preferences for a schedule, and allows a schedule to be "scored".
*/
class Preferences
{
  protected $categories; // array(string => array(callable, float)) : array(name => array(category, weighting))

  function __construct(array $preferenceCategories = array()) {
    $this->$categories = array();
    foreach ($preferenceCategories as $prefCat) {
      if (array_key_exists($prefCat->$name, $this->$categories)) {
        throw new Exception('PreferenceCategory names were not unique');
      }
      $this->$categories[$prefCat->$name] = array($prefCat->$evaluate, 0.0);
    }
  }

  function changeWeight(string $categoryName, float $weight) {
    if (!array_key_exists($categoryName, $this->$categories)) {
      throw new Exception('Invalid category name');
    }
    $this->$categories[$categoryName][1] = $weight;
  }

  function score(Schedule $schedule) {
    $score = 0.0;
    foreach ($this->$categories as list($evaluate,$weight)) {
      $score += $evaluate($schedule) * $weight;
    }
    return $score;
  }
}


?>