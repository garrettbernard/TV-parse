<?php

  $doc = new DOMDocument();
  $doc->load('http://feed43.com/6552170434110645.xml');
  $arrFeeds = array();
  foreach ($doc->getElementsByTagName('item') as $node) {
    $itemRSS = array ( 
      'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
      'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
      'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
      'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
      );
    array_push($arrFeeds, $itemRSS);
  }
print_r($arrFeeds);

while ($

?>