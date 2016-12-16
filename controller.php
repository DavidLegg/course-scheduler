<?php
ini_set('display_errors', 1);
global $xpath, $baseQueryUrl;
$baseUrl = 'https://www.reg.uci.edu/perl/WebSoc';
$baseQueryUrl = 'https://www.reg.uci.edu/perl/WebSoc?ShowFinals=1&ShowComments=0';

$html = file_get_contents($baseUrl);
$dom = new DOMDocument;
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);


function getDropDownItems($name) {
  global $xpath;
//   var_dump($name);
  $allOpts = $xpath->query('//select[@name="'.$name.'"]')[0];
//   var_dump($allOpts);
  return $allOpts->getElementsByTagName("option");
}

function appendTerm(){
  global $baseQueryUrl;
  $term = getDropDownItems('YearTerm')[0];
  $termId = $term->getAttribute('value');
  $baseQueryUrl .= '&YearTerm='.$termId;
}

appendTerm();



?>
