<?php

require 'functions.php';
global $db;
global $deviceTypeArray;
head();

$site = isset($_POST['formSite']) ? $_POST['formSite'] : '';

if (isset($_POST['submit'])){
  array_shift($_POST);
  $sitePost = array_shift($_POST);
  foreach ($_POST as $colName => $key){
    if ($key !== ''){
      //get currect count
      $getCurrCountQ = "SELECT " . $colName . " FROM arrg_STAGING WHERE site LIKE '" . $sitePost . "'";
      $result = $db->query($getCurrCountQ);
      $resultArray = $result->fetch_array(MYSQLI_ASSOC);
      //make change the current count
      $change = $resultArray[$colName] + $key;
      //update DB
      $query = "UPDATE arrg_STAGING SET " . $colName . "=" . $change . " WHERE site LIKE '" . $sitePost . "'";
      $db->query($query);
      //feedback
      echo "Changed ". $colName . " from " .$resultArray[$colName] . " to ". $change . "<br />";
    }
  }
  $_POST['formSite'] = $sitePost;//Must be here so the site selector is happy...
}




//Fixed : )
echo "<h1>" . $site . "</h1>"; //$site is blank until page reload with updated site from siteSelector();
echo " <form action='' method='post'>";
echo "<input type='submit' name='submit'>";
$site = siteSelector(); //Added siteSelector() to form
foreach ($deviceTypeArray as $device){
  overviewMain($site,$device);
        }



echo "</form>";
foot();
?>
