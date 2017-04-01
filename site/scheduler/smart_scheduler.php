<?php
class SmartScheduler {
	/**
	 * The default public scheduling method. Will defer to the generally best algorithm below.
	 * 
	 * @param courses The array of Courses to be scheduled.
	 * @param preferences The Preferences object used to score schedules.
	 * @param n The number of top-scoring schedules to be returned.
	 */
	public static function build_schedules(&$courses, &$preferences, $n) {
		return SmartScheduler::fcSchedule($courses, $preferences, $n);
	}

	/**
	 * Brute force algorithm, for comparison or for backup.
	 * 
	 * @param courses The array of Courses to be scheduled.
	 * @param preferences The Preferences object used to score schedules.
	 * @param n The number of top-scoring schedules to be returned.
	 */
	public static function bruteForceSchedule(&$courses, &$preferences, $n) {
		$schedules = array();
		foreach ($courses as &$c) {
			$schedules = $c->buildSchedules($schedules);
		}
		$preferences->sort($schedules);
		return array_slice($schedules, 0, $n);
	}

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
	public static function beamSchedule($courses, &$preferences, $n, $marginFactor = 2) {
		usort($courses, function($c1, $c2) {
			return $c1->numCombos() - $c2->numCombos();
		});

		$schedules = array();
		foreach ($courses as &$course) {
			$schedules = $course->buildSchedules($schedules);
			if (count($schedules) > $n * $marginFactor) {
				// pare down the schedules: this is where we save time.
				$preferences->sort($schedules);
				$schedules = array_slice($schedules, 0, $n * $marginFactor);
			}
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
	 * @param marginFactor The number (as a factor of n) of schedules to consider in intermediate steps. Higher numbers give potentially more accurate, but slower, results.
	 */
	public static function fcSchedule(&$courses, &$preferences, $n, $marginFactor = 2) {
	  // sectionArrs is array(array(Section)). Need to choose one from each level of sections.
	  $sectionArrs = array_reduce($courses, function($sections, $course) {
	    return array_merge($sections, array_values($course->sectionArr)); // ignores keys for class type, benefits merging
	  }, array());

	  $schedules = SmartScheduler::_fcSchedule($sectionArrs, new Schedule(), $preferences, $n * $marginFactor);
	  $preferences->sort($schedules);
	  return array_slice($schedules, 0, $n);
	}

	// (recursive) function to choose the best maxLength schedules that use a section from each category of sectionArrs
	private static function _fcSchedule(&$sectionArrs, &$baseSched, &$preferences, $maxLength) {
		// base case: no options to choose, so give the base schedule back as only possible schedule
		if (empty($sectionArrs)) return array($baseSched);

		// otherwise, choose the section from the first option
		$sectionOptions = array_shift($sectionArrs);
		if (empty($sectionOptions)) return array();

		$schedules = array_reduce($sectionOptions, function($acc, $option) use (&$sectionArrs, &$baseSched, &$preferences, $maxLength) {
			$newSectionArrs = SmartScheduler::forwardChecking($sectionArrs, $option);
			SmartScheduler::MRVsort($newSectionArrs);
			return array_merge($acc, SmartScheduler::_fcSchedule($newSectionArrs, new Schedule($baseSched, $option), $preferences, $maxLength));
		}, array());

		/* This is actually useless: it runs after the recursion,
		 * so it actually lets the algorithm look at every option,
		 * then post-hoc trims off all but the top maxLength, saving no work.
		 * NEXT: find a way to do this breadth-limiting before the recursion, in a useful way.
		 */
		// if (count($schedules) > $maxLength) {
		// 	// trim schedules down to top n * mF
		// 	$preferences->sort($schedules);
		// 	return array_slice($schedules, 0, $maxLength);
		// } else {
		// 	// save the extra fn calls
		// 	return $schedules;
		// }
		return $schedules;
	}

	// filters options by corequirements against choice
	private static function coreqFilter(&$choice, &$options) {
		foreach ($choice->coreqs as &$coreq) {
			if ($coreq->course == $options[0]->course && $coreq->type == $options[0]->type) {
				$options = (in_array($coreq, $options) ? array($coreq) : array());
			}
		}
		// all options are either not mandated by choice or are the coreq mandated by choice.
		return array_filter($options, function($option) use (&$choice) {
			foreach ($option->coreqs as &$coreq) {
				if ($coreq->course == $choice->course && $coreq->type == $choice->type) {
					// option mandates choice's course/type, keep if it mandates choice
					return ($coreq == $choice);
				}
			}
			// option doesn't mandate choice's course/type, keep it
			return true;
		});
	}

	// filters options by time conflicts
	private static function timeFilter(&$choice, &$options) {
		return array_filter($options, function ($option) use (&$choice) {
			return (!$option->conflictsWith($choice));
		});
	}

	// function to do forward-checking: eliminates from sectionArrs all sections that are inconsistent with choice
	private static function forwardChecking(&$sectionArrs, &$choice) {
		return array_map(function ($options) use (&$choice) {
			$options = SmartScheduler::timeFilter($choice, $options);
			return SmartScheduler::coreqFilter($choice, $options);
		}, $sectionArrs);
	}

	// function to sort array of options by most restricted option (MRV)
	private static function MRVsort(&$sectionArr) {
		usort($sectionArr, function (&$options1, &$options2) {
			return count($options1) - count($options2); // returns <0 if options1 shorter than (before) options2, so shortest is last
		});
	}
}
?>