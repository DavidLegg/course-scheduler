<?php
ini_set('display_errors', 1);
$baseUrl = 'https://www.reg.uci.edu/perl/WebSoc';
$baseQueryUrl = 'https://www.reg.uci.edu/perl/WebSoc?ShowFinals=1&ShowComments=0';

$html = file_get_contents($baseUrl);
$dom = new DOMDocument;
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

function getDropDownItems($name) {
  global $xpath;
  $allOpts = $xpath->query('//select[@name="'.$name.'"]')[0];
  return $allOpts->getElementsByTagName("option");
}

function appendTerm(){
  $term = getDropDownItems('YearTerm');
  $termId = $term->getAttribute('value');
  $baseQueryUrl .= '&YearTerm='.$termId;
}

appendTerm();



?>
