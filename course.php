<?php
    
    /**
     * Defines a collection of sections that must be taken to satisfy a course.
     * For example, might include the options for a lecture, lab, and discussion.
     */
    class Course {
        
        public $name; // string
        public $sectionArr; // array(string => array(Section)) : Section->type => {Section}
        //If a section code is a key in the restrictions,
        // then for the type(s) in the returned array, only the listed sectionss can be taken.
        //For example, Lecture A for a course might require Discussions 1-5, while Lec B requires Discussions 6-10.
        
        function __construct($courseName, array $sections = NULL) {
            $sections = is_null($sections) ? array() : $sections;
            
            $this->sectionArr = array();
            foreach ($sections as $section) {
                if (!array_key_exists($section->type, $this->sectionArr)) {
                    $this->sectionArr[$section->type] = array();
                }
                $this->sectionArr[$section->type][] = $section;
            }
            
            $this->name = (string)$courseName;
        }
        
        function addSection(Section $section) {
            if (!array_key_exists($section->type, $this->sectionArr)) {
                $this->sectionArr[$section->type] = array();
            }
            $this->sectionArr[$section->type][] = $section;
        }
        
        /**
         * Builds all possible schedules based on the current schedule,
         * incorporating one of each necessary 'type' of Section for this Course.
         */
        public function buildSchedules(Schedule $currentSchedule = NULL) {
            echo "DEBUG: --- Course::buildSchedules ---<br>"; //DEBUG
            if (is_null($currentSchedule)) {
                $currentSchedule = new Schedule();
            }
            
            echo "DEBUG: currentSchedule:"; var_dump($currentSchedule); //DEBUG
            echo "DEBUG: sectionArr:"; var_dump($sectionArr); //DEBUG
            return $this->_buildSchedules($currentSchedule, $this->sectionArr);
        }
        
        private function _buildSchedules(Schedule $sched, array $sectionArr) {
            echo "DEBUG: --- Course::_buildSchedules ---<br>"; //DEBUG
            echo "DEBUG: sched:"; var_dump($sched); //DEBUG
            echo "DEBUG: sectionArr:"; var_dump($sectionArr); //DEBUG
            echo "DEBUG: !sectionArr:"; var_dump(!$sectionArr); //DEBUG
            if (!$sectionArr) return array($sched);
            
            $sections = array_pop($sectionArr);
            foreach ($sections as $section) {
                if ($sched->hasSection($section)) {
                    // the schedule already contains this 'type' of section, skip the process at this level.
                    return $this->_buildSchedules($sched, $sectionArr);
                }
            }
            
            $output = array();
            // Loop through all sections of one type:
            foreach ($sections as $section) {
                if ($section->conflictsWith($sched)) continue; // simply can't use it.
                // Note: since any section conflicts with itself, this check also stops duplicates (e.g., from coreq additions)
                
                $newSched = new Schedule($sched, $section);
                if (is_array($section->coreqs) || is_object($section->coreqs))
                    foreach ($section->coreqs as $req => $x) {
                        if ($newSched->hasSection($req)) continue; // section already present
                        if ($newSched->hasCourseType($req)) break 2; // has the 'type' of this req for this req's course, but not this one. Excludes req.
                        if ($req->conflictsWith($newSched)) break 2; // break out of coreq loop *and* this section loop.
                        $newSched = new Schedule($newSched, $req); // else, add section to schedule
                    }
                //now we've guaranteed all coreqs have been added.
                
                $output += $this->_buildSchedules($newSched, $sectionArr); //compute next level
            }
            
            return $output;
        }
    }
    
    ?>
