<?php

if (!defined('ROOT_PATH')) define('ROOT_PATH', __DIR__.'/');

require_once ROOT_PATH.'websoc.php';
require_once ROOT_PATH.'time.php';
require_once ROOT_PATH.'section.php';
require_once ROOT_PATH.'course.php';

/**
* Defines an interface with UCI's WebSOC.
*/
class UCI_WebSoc implements WebSocInterface {
  private static const $url = 'https://www.reg.uci.edu/perl/WebSoc';
  private static const $yearTerm = '2017-03'; //TODO: figure out how this number is calculated, and automate it.
  private static const $dayCodes = array(
    "M"  => "monday",
    "Tu" => "tuesday",
    "W"  => "wednesday",
    "Th" => "thursday",
    "F"  => "friday"
  );

  public static function getCourse($name) {
    $name            = (string)$name;
    list($dept,$num) = UCI_WebSoc::_parseCourseName($name);
    $xml             = UCI_WebSoc::_sendCouseRequest($dept,$num);
    return UCI_WebSoc::_makeCourse($xml,$xml->xpath('//department[1]')[0]['dept_case']);
  }

  private static function _parseCourseName($name) {
    preg_match("/(.+)\s*(\w+)$/",$name,$matches);
    if (count($matches) < 3) {
      throw new Exception("Invalid name format");
    }
    list(,$dept,$num) = $matches;
    return array($dept,$num);
  }

  private static function _sendCourseRequest($dept,$num) {
    $data = array( // copied from intercepted request
      'YearTerm'         => UCI_WebSoc::yearTerm,
      // 'ShowComments'     => 'on', //don't need this
      'ShowFinals'       => 'on',
      'Breadth'          => 'ANY',
      'Dept'             => $dept,
      'CourseNum'        => $num,
      'Division'         => 'ANY',
      'CourseCodes'      => '',
      'InstrName'        => '',
      'CourseTitle'      => '',
      'ClassType'        => 'ALL',
      'Units'            => '',
      'Days'             => '',
      'StartTime'        => '',
      'EndTime'          => '',
      'MaxCap'           => '',
      'FullCourses'      => 'ANY',
      'FontSize'         => 100,
      'CancelledCourses' => 'Exclude',
      'Bldg'             => '',
      'Room'             => '',
      'Submit'           => 'Display XML Results' // thanks to Mr. Garr Updegraff, who made UCI WebSOC and told me about this hidden feature
    );

    $options = array(
      'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n", //leave others out for now
        'method'  => 'POST',
        'content' => http_build_query($data)
      )
    );

    $context = stream_context_create($options);
    $result  = file_get_contents(UCI_WebSoc::url, false, $context);
    if ($result === FALSE) { //type-sensitive comparison
      throw new Exception("An error occured while accessing UCI WebSOC");
    }
    return simplexml_load_string($result);
  }

  // Note that this function will also work with multiple courses,
  // by passing in the course as the root node.
  private static function _makeCourse($xml, $deptName) {
    $courseXml  = $xml->xpath('//course[1]')[0]; //assume only one course
    $courseName = $deptName.' '.$courseXml['course_number'].': '.$courseXml['course_title'];

    $course = new Course($courseName);
    $coreqs = array(); //code => coreq's code
    foreach($courseXml->section as $sectionXml) {
      $course->addSection(UCI_WebSoc::_makeSection($sectionXml,$courseName,$coreqs));
    }
    UCI_WebSoc::_addCoreqs($course,$coreqs);
    return $course;
  }

  private static function _makeSection($secXml, $courseName, &$coreqs) {
    list($start,$end) = UCI_WebSoc::_makeTimes((string)($secXml->xpath('//sec_time')[0]);
    $coreqCode = (string)($secXml->xpath('//sec_group_backward_ptr')[0]);
    if ((int)$coreqCode != 0) {
      $coreqs[(string)($secXml->course_code)] = $coreqCode;
    }
    return new Section(
      UCI_WebSoc::_makeDays((string)($secXml->xpath('//sec_days')[0])), // array meetDays
      $start, // Time meetStart
      $end, // Time meetEnd
      UCI_WebSoc::_makeFinal($secXml->xpath('//sec_final')[0]), // DateTime final
      $courseName, // string courseName
      (string)($secXml->sec_type), // string meetType
      (string)($secXml->course_code), // string sectionCode
      UCI_WebSoc::_makeOpenings($secXml->sec_enrollment) // int secOpenings
    );
  }

  private static function _makeDays($dayStr) {
    $days = array();
    foreach ($dayCodes as $short => $long) {
      $days[$long] = is_int(strpos($dayStr,$short)); //dayStr has short
    }
    return $days;
  }

  private static function _makeTimes($timeStr) {
    list($startStr,$endStr) = explode(',', $timeStr);
    $start = new Time($startStr);
    $end   = new Time($endStr);
    if ($end->difference($start, 'hours', true) > 12) {
      // start must actually be in the afternoon
      $start->addition(12, 'hours'); // so change to PM.
    }
    return array($start,$end);
  }

  private static function _makeFinal($finalXml) {
    list($start,$end) = UCI_WebSoc::_makeTimes((string)($finalXml->sec_final_time));
    return new DateTime(((string)$finalXml->sec_final_date).' '.$start);
  }

  private static function _makeOpenings($enrollXml) {
    return (int)$enrollXml->sec_max_enroll -
           (int)$enrollXml->sec_enrolled   -
           (int)$enrollXml->sec_waitlist;
  }

  private static function _addCoreqs(&$course, &$coreqs) {
    foreach ($coreqs as $code => $reqCode) {
      $section = $reqSection = NULL;
      foreach ($course->sectionArr as $sections) {
        foreach ($sections as $s) {
          if ($s->code == $code) {
            $section = $s;
            if (!is_null($reqSection)) break 2;
          } else if ($s->code == $reqCode) {
            $reqSection = $s;
            if (!is_null($section)) break 2;
          }
        }
      }
      // silently ignore bad coreq data:
      if (is_null($section) || is_null($reqSection)) continue;
      $section->addCoreq($reqSection);
    }
  }
}

?>