<?php

/**
* Defines a particular feature that can discriminate between "good" and "bad" schedules.
*/
class PreferenceCategory
{
  public $name;     // string
  public $evaluate; // callable(Schedule) => float

  function __construct($categoryName, $evaluator) {
    $this->name      = (string)$categoryName;
    $this->evalutate = $evaluator;
  }
}

?>