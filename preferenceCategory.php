<?php

/**
* Defines a particular feature that can discriminate between "good" and "bad" schedules.
*/
class PreferenceCategory
{
  public $name;     // string
  public $evaluate; // callable(Schedule) => float

  function __construct(string $categoryName, callable $evaluator) {
    $this->name      = $categoryName;
    $this->evalutate = $evaluator;
  }
}

?>