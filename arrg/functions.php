<?php
require "db_connect.php";
if(basename($_SERVER["PHP_SELF"]) != 'exportcsv.php'){
  echo '<script type="text/javascript" src="jsscripts.js"></script>';
}
/**********************Globals for All pages***********************************/
/* V $siteArray is an array of sites to be used for function calls or other fun things*/

$deviceTypeArray = array("cb","lt","dt");

$siteArray = array('ALL','Antioch','Baker','Bob Sikes','Best Chance',
              'Crestview','Davidson','Laurel Hill',
              'Northwood','OYA','ORDC','OYDC','Richbourg','Riverside','Southside',
              'Shoalriver','Walker');



/* V $reportArray is an array of reports to be ues */
$reportArray = array('Moves Report','Placement Report','Pull Report','Rolloff Report','Final Destination Report');





/**********************Functions for All pages*********************************/
/* F head loads any header specific code on whatever page it is called on.*/
function head(){ //Header function

  $self = basename($_SERVER["PHP_SELF"]); //navigation text on all pages
  echo "<head><link rel=stylesheet type=text/css href=style.css></head>";
  echo "<title>A.R.R.G. - Asset Refresh Report Generator</title>";
  echo "<!DOCTYPE html>";
if ($self != "index.php"){ //Leaves off the site selector and GO HOME link for index.php page
    echo "<a href=index.php>GO HOME</a><br/>";
  }



}
/* F foot loads any footer specific code on whatever page it is called on.*/
function foot(){ //Footer function
  echo "Verison: 1.1.1";
  echo "</html>";
}

/* F siteSelector creates the dropdown for each site on whatever page it is called on.
-returns the selected site as V $site*/
function siteSelector(){
 global $siteArray;
 array_shift($siteArray); //Shifting array only in this function so that in the reports function, I can include all without repeating the array.
 echo '<select class=siteselector id=reportSiteSelector name=formSite>';
 echo '<option selected=selected>'.$_POST['formSite'].'</option>';
 foreach($siteArray as $value){
  echo "<option value='$value'>$value</option>";
 }
 echo '</select>';
 if(isset($_POST['formSite'])){
  $site = $_POST['formSite'];
  return $site;
 }else {
   echo '';
 }
}

function feedback($status,$msg){
  switch ($status) {
    case "bad":
     echo "<script type='text/javascript'>playError();</script>";
     echo $msg;
    break;
    case "good":
     echo "<script type='text/javascript'>playGood();</script>";
     echo $msg;
    break;
    default:
      echo "something is wrong with feedback function.";
      break;
  }
}


/**********************Functions for Moves.php*********************************/

function insertMoves($site,$assetTag,$newRoom,$machineType,$status,$newBulding){
global $db;



  if($assetTag != NULL){
    $checkAssetTag = "SELECT `tag` FROM arrg_MOVES where `tag` LIKE '$assetTag'";
    $query = $db->query($checkAssetTag);
        if (mysqli_num_rows($query)==0) {
          echo "No asset tags found like '".$assetTag."'";
          //NOTE Leaving the below query and echo statement in case we need to insert an asset into the moves table with the criteria listed.
           $insert = "INSERT INTO arrg_MOVES (site, tag, new_room, device_type, status, new_building) VALUES ('$site', '$assetTag', '$newRoom', '$machineType', '$status','$newBulding')";
           echo "<h3>Successfully moved $machineType: $assetTag, to Room: $newRoom at $site.</h3>";
        }else{
          $insert = "UPDATE arrg_MOVES SET new_building='".$newBulding."',status ='".$status."',new_room='".$newRoom."',site='".$site."' WHERE tag='" . strtoupper($assetTag) . "'";
          echo "<h3>Successfully updated the Move list with $machineType: $assetTag, to Room: $newRoom at $site.</h3>";
        }
        $db->query($insert);
  }

  if($site != NULL){
  moveReport($site);
  }

}




/**********************Functions for MovesReport.php***************************/






/**********************Functions for overviewreport.php************************/
function pullsCounts($site = "all"){
  global $db;
  $pullsCounts[] = null;
  //total pulls
  $query = "SELECT COUNT(*) AS count FROM arrg_PULL_LIST WHERE site LIKE '". $site . "';";
  $pullsCounts['total']=MYSQLCounttoArray($query);
  //total pulled
  $query = "SELECT COUNT(*) AS count FROM arrg_PULL_LIST WHERE site LIKE '". $site . "' AND (status LIKE 'pulled' or status LIKE 'roll_off');";
  $pullsCounts['pulled']=MYSQLCounttoArray($query);
  //total pulls on hold
  $query = "SELECT COUNT(*) AS count FROM arrg_PULL_LIST WHERE site LIKE '". $site . "' AND (status LIKE 'summer_school' OR status LIKE 'wax');";
  $pullsCounts['holds']=MYSQLCounttoArray($query);
  //missing computers
  $query = "SELECT COUNT(*) AS count FROM arrg_PULL_LIST WHERE site LIKE '". $site . "' AND status LIKE 'missing';";
  $pullsCounts['missing']=MYSQLCounttoArray($query);
  return $pullsCounts;
}

function placementCounts($site = "all", $deviceType = "") {
  $placementCounts[] = null;
  //incoming devices
  $query = "SELECT (". $deviceType ."_incoming) AS count FROM arrg_STAGING WHERE site LIKE '". $site . "';";
  $placementCounts[$deviceType.'_incoming']=MYSQLCounttoArray($query);
  //delivered devices
  $query = "SELECT (". $deviceType ."_delivered) AS count FROM arrg_STAGING WHERE site LIKE '". $site . "';";
  $placementCounts[$deviceType.'_delivered']=MYSQLCounttoArray($query);
  //unboxed devices
  $query = "SELECT (". $deviceType ."_unboxed) AS count FROM arrg_STAGING WHERE site LIKE '". $site . "';";
  $placementCounts[$deviceType.'_unboxed']=MYSQLCounttoArray($query);
  //setup devices
  $query = "SELECT (". $deviceType ."_setup) AS count FROM arrg_STAGING WHERE site LIKE '". $site . "';";
  $placementCounts[$deviceType.'_setup']=MYSQLCounttoArray($query);
  //labels on CBs
  if ($deviceType == "cb") {
    $query = "SELECT (". $deviceType ."_labels) AS count FROM arrg_STAGING WHERE site LIKE '". $site . "';";
    $placementCounts[$deviceType.'_labels']=MYSQLCounttoArray($query);
  }

  //count total deviceType that where deployed
  $query = "SELECT COUNT(*) AS count FROM arrg_PLACEMENT WHERE site LIKE '". $site . "' AND device_type LIKE '". $deviceType . "' AND tag IS NOT NULL;";
  $placementCounts[$deviceType.'_deployed']=MYSQLCounttoArray($query);



  return $placementCounts;


}

function MYSQLCounttoArray($query = ""){
  global $db;
  $result = $db->query($query);
  $resultArray = $result->fetch_array(MYSQLI_ASSOC);
  $returnNumber=$resultArray['count'];
  return $returnNumber;
}






/**********************Functions for placements.php****************************/







/**********************Functions for placementsreport.php**********************/






/**********************Functions for pulls.php*********************************/






/**********************Functions for pullsreport.php***************************/






/**********************Functions for Rolloff.php********************************/

/* F rollOffSelector returns a list of rolloffs to the rolloff page as it is called,
the function then sets the site to whatever the user selected. (Currently the only rolloff sites are Davidson and Shoalriver)*/
function rollOffSelector(){

$siteArray = array('Davidson','Shoalriver');

echo '<select class=siteselector id=reportSiteSelector name=formSite> autofocus';

  echo '<option selected=selected>'.$_POST['formSite'].'</option>';

foreach($siteArray as $value){
  echo "<option value='$value'>$value</option>";
  }
echo '</select>';

if(isset($_POST['formSite'])){
  $site = $_POST['formSite'];
  return $site;
  }else {
    echo '';
  }
}

function updateRolloff($site,$assetTag){
  global $db;

    if($assetTag == NULL && $site == NULL){
        echo "Enter site and asset tag";
    }elseif($site != NULL && $assetTag == NULL){
      rolloffReport($site);
    }else{
      $insertQuery = "UPDATE arrg_PULL_LIST SET roll_off= '". $site ."' , status = 'Roll Off' WHERE tag LIKE  '". strtoupper($assetTag) ."' ";
      $insertResult = $db->query($insertQuery);
      if($db->affected_rows > 0){
        echo " Successfully updated ".$assetTag." for rolloff ".$site.". <br> ";
        rolloffReport($site);
      }else{
        echo "Asset tag $assetTag not found or incorrect. Try again";
      }
    }




}


/**********************Functions for reportPage.php****************************/
/* F reportSelectionHelper calls on the report from the reportSelection function.*/
function reportSelection($formSite, $siteReport){

  if($formSite != NULL && $siteReport != NULL){

      if($siteReport == 'Pull Report')
        {
          pullReport($formSite);
      }elseif($siteReport == 'Placement Report')
        {
          placementReport($formSite);
      }elseif($siteReport == 'Moves Report')
        {
          moveReport($formSite);
      }elseif($siteReport == 'Rolloff Report'){
          rolloffReport($formSite);
      }elseif($siteReport == 'Final Destination Report'){
          displayDesReport($formSite);
      }

    }

}




/* F reportSelector adds the two selectors to whatever page its called on. It then passes the selections into the $_POST
variables from the page that called it. */
function reportSelector(){

global $siteArray;
global $reportArray;
  echo '<div class= siteSelector>';
  echo '<select id=reportSiteSelector name=formSite> autofocus';
    echo '<option selected=selected>'.$_POST['formSite'].'</option>';
    foreach($siteArray as $value){
      echo "<option value='$value'>$value</option>";
    }
    echo '</select>';
    echo '</div>';

echo '<div class = siteSelector>';
  echo '<select id=reportsSelector name=siteReport>';
    echo '<option selected=selected>'.$_POST['siteReport'].'</option>';
    foreach($reportArray as $value){
      echo "<option value='$value'>$value</option>";
    }
    echo '</select>';
    echo '</div>';

    if(isset($_POST['formSite']) && isset($_POST['siteReport'])){
      return array($_POST['formSite'],$_POST['siteReport']);
    }else {
      echo '';

    }


  }

/* F exportToCSV takes one array argument and exports that array to a csv file of the users choice.*/
// function to build the needed data then send to F outputCVS which maks the data to csv and sends it to browser
function exportToCSVBuilder($formSite, $siteReport){
  /*Could append to previous write with each call for the ALL sites selection. "$formSite_$siteReport.csv"*/
  global $db;
  $num = 0;
  switch ($siteReport){
  case 'Moves Report':
    $mySQLObj=moveReportQ($formSite);
    while ($row = $mySQLObj->fetch_array(MYSQLI_ASSOC)){
      if ($formSite == "ALL"){
        $array[$num]['site']      = $row['site'];
      }
      $array[$num]['tag']         = $row['tag'];
      $array[$num]['old_room']    = $row['old_room'];
      $array[$num]['new_building']= $row['new_building'];
      $array[$num]['new_room']    = $row['new_room'];
      $array[$num]['device_type'] = $row['device_type'];
      $array[$num]['status']      = $row['status'];
      $num++;
    }
    if ($formSite == "ALL") {
      $tableHeaderArray = array("Move report for all sites");
      $headerArray = array('Site','Tag','Old room','New Building','New room','Device type','Status');
    } else {
      $tableHeaderArray = array("Move report for ". $formSite);
      $headerArray = array('Tag','Old room','New Building','New room','Device type','Status');
    }
    outputCVS($formSite,$tableHeaderArray,$headerArray,$array);
  break;
  case 'Placement Report':
    //TODO ehhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh
    $mySQLObj=placementReportQ($formSite);
    while ($row = $mySQLObj->fetch_array(MYSQLI_ASSOC)){
      if ($formSite == "ALL"){
        $array[$num]['site']      = $row['site'];
      }
      $array[$num]['building']    = $row['building'];
      $array[$num]['room']        = $row['room'];
      $array[$num]['device_type'] = $row['device_type'];
      $array[$num]['count']       = $row['count'];
      $num++;
    }
    if ($formSite == "ALL") {
      $tableHeaderArray = array("Placement report for all sites");
      $headerArray = array('Site','Building','Room','Device type','Count');
    } else {
      $tableHeaderArray = array("Placement report for ". $formSite);
      $headerArray = array('Room','Building','Device type','Count');
    }
    outputCVS($formSite,$tableHeaderArray,$headerArray,$array);
  break;
  case 'Pull Report':
  ob_start();
      $mySQLObj=pullReportQ($formSite);
      while ($row = $mySQLObj->fetch_array(MYSQLI_ASSOC)){
        if ($formSite == "ALL"){
          $array[$num]['site']    = $row['site'];
        }
        $array[$num]['building']  = $row['building'];
        $array[$num]['room']      = $row['room'];
        $array[$num]['tag']       = $row['tag'];
        $array[$num]['model']     = $row['model'];
        $array[$num]['status']    = $row['status'];
        $num++;
      }
      if ($formSite == "ALL") {
        $tableHeaderArray = array("Pull report for all sites");
        $headerArray = array('Site','Building','Room','Tag','Model','Status');
      } else {
        $tableHeaderArray = array("Pull report for ". $formSite);
        $headerArray = array('Room','Building','Tag','Model','Status');
      }
      outputCVS($formSite,$tableHeaderArray,$headerArray,$array);
  break;
  case 'Rolloff Report':
  $mySQLObj=rollOffReportQ($formSite);
  if($mySQLObj == NULL){
     $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
     echo "<a href='$url'> Go Back</a>";
  return;
  }
    while ($row = $mySQLObj->fetch_array(MYSQLI_ASSOC)){
      $array[$num]['site']        = $row['site'];
      $array[$num]['roll_off']    = $row['roll_off'];
      $array[$num]['tag']         = $row['tag'];
      $array[$num]['timestamp']   = $row['timestamp'];
      $num++;
    }
    if ($formSite == "ALL") {
      $tableHeaderArray = array("Rolloff report for all sites");
      $headerArray = array('Site','Roll Off','Tag','Timestamp');
    } else {
      $tableHeaderArray = array("Rolloff report for ". $formSite);
      $headerArray = array('Site','Roll Off','Tag','Timestamp');
    }
    outputCVS($formSite,$tableHeaderArray,$headerArray,$array);
  break;
  case'Final Destination Report':

    $mySQLObj=finalDesReportQ($formSite);
    while ($row = $mySQLObj->fetch_array(MYSQLI_ASSOC)){
      if ($formSite == "ALL"){
        $array[$num]['site']      = $row['site'];
      }
      $array[$num]['building']    = $row['building'];
      $array[$num]['room']        = $row['room'];
      $array[$num]['device_type'] = $row['device_type'];
      $array[$num]['tag']         = $row['tag'];
      $num++;
    }
    if ($formSite == "ALL") {
      $tableHeaderArray = array("Final Destination report for all sites");
      $headerArray = array('Site','Building','Room','Device Type','Tag');
    } else {
      $tableHeaderArray = array("Final Destination report for ". $formSite);
      $headerArray = array('Room','Building','Device Type','Tag');
    }
    outputCVS($formSite,$tableHeaderArray,$headerArray,$array);
  break;
  default:
      echo "Select a site & form to export";
  }
}

function outputCVS($formsite,$tableHeaderArray,$headerArray,$inputArray){
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=data.csv');
  $tempFile = "php://output";
  $out = fopen($tempFile, 'w');
  fputcsv($out, $tableHeaderArray);//table header
  fputcsv($out, $headerArray);//col headers
  foreach ($inputArray as $row ) {
    fputcsv($out, $row);
  }
  fclose($out);


}

// Querys for all pull reports
function pullReportQ($site) {
  global $db;
  $self = basename($_SERVER["PHP_SELF"]);
  if ($self == "pulls.php") {
    $query = "SELECT * FROM arrg_PULL_LIST WHERE site LIKE '" . $site . "' AND (`status` NOT LIKE 'pulled' AND `status` NOT LIKE 'roll_off' OR `status` IS NULL) ORDER BY CAST(`room` AS SIGNED INTEGER) ";
  }
  elseif($self == "reportPage.php" || "exportcsv.php"){
    if($site == 'ALL'){
      $query = "SELECT * FROM arrg_PULL_LIST WHERE (`status` LIKE 'pulled' AND `status` LIKE 'roll_off' OR `status` IS NOT NULL) ORDER BY `site`,`room` ";
    }else{
      $query = "SELECT * FROM arrg_PULL_LIST WHERE `site` LIKE '" . $site . "' AND (`status` LIKE 'pulled' AND `status` LIKE 'roll_off' OR `status` IS NOT NULL) ORDER BY `site`,`room` ";
    }
  }
  $result = $db->query($query);
  return $result;
}
// Querys for all moves reports
function moveReportQ($site) {
  global $db;
  $self = basename($_SERVER["PHP_SELF"]);
  if($self == "moves.php"){
      $query = "SELECT * FROM arrg_MOVES WHERE site LIKE '" . $site . "' AND (`status` NOT LIKE 'Moved' OR `status` IS NULL) ORDER BY `site`,`device_type`,`new_room`";
  }elseif($self == "reportPage.php" || "exportcsv.php") {
    if($site == 'ALL'){
      $query = "SELECT * FROM arrg_MOVES WHERE (`status` LIKE 'Moved') ORDER BY `site`,`device_type`,`new_room`";
    }else{
      $query = "SELECT * FROM arrg_MOVES WHERE site LIKE '" . $site . "' AND (`status` LIKE 'Moved')ORDER BY `site`,`device_type`,`new_room`";
    }
  }
  return  $db->query($query);
}
function placementReportQ($site){
  global $db;
  // TODO these querys tell what rooms need what device and how many. Need to build querys to return where new comptuer went to.
  if($site == 'ALL'){
    $query = "SELECT site,room,device_type,COUNT(*) AS count FROM `arrg_PLACEMENT` WHERE `tag` IS NULL GROUP by `site`, `room`, `device_type` ORDER BY `site`, `room` ";
  }else{
    $query = "SELECT site,room,device_type,COUNT(*) AS count FROM `arrg_PLACEMENT` WHERE `site` LIKE '" . $site . "' AND `tag` IS NULL GROUP by `site`, `room`, `device_type` ORDER BY `room`  ";
  }
  return $db->query($query);
}
function rollOffReportQ($site){
  global $db;
  if($site == 'ALL'){
    $query = "SELECT * FROM arrg_PULL_LIST WHERE (`roll_off` IS NOT NULL) ORDER BY `site`,`timestamp`";
  }else{
    if($site == 'Davidson' || $site == 'Shoalriver'){
      $query = "SELECT * FROM arrg_PULL_LIST WHERE roll_off LIKE '".$site."' ORDER BY `timestamp`";
    }else{//TODO might need to move this so it shows before the export downlards
      echo "Only Davidson and Shoalriver have rolloffs, please select one of the two.";//problem returning site info
      return NULL;
    }
  }
/*TODO Make the ALL selection statement print out each site in one table, make the single select statement print out one table*/
  return $db->query($query);
}
// to return Final Desitnation of all computers
function finalDesReportQ($site){
  global $db;
  if($site == 'ALL'){
    $query = "SELECT `site`,`building`,`room`,`device_type`,`tag` FROM `arrg_PLACEMENT` WHERE `tag` IS NOT NULL UNION SELECT `site`,`new_building` AS `building`,`new_room` AS room,`device_type`,`tag` FROM `arrg_MOVES` WHERE `status` LIKE 'moved'";
  }else{
    $query = "SELECT `site`,`building`,`room`,`device_type`,`tag` FROM `arrg_PLACEMENT` WHERE (`site` LIKE '".$site. "' AND `tag` IS NOT NULL) UNION SELECT `site`,`new_building` AS `building`,`new_room` AS `room`,`device_type`,`tag` FROM `arrg_MOVES` WHERE `status` LIKE 'Moved' AND `site` LIKE '".$site."' ";
  }
  $result = $db->query($query);
  //$resultArray = $result->fetch_array(MYSQLI_ASSOC);
  return $result;
}
/* F pullReport selects the pulls from the database and uses them where the function is called.  */
function pullReport($site){
  $result=pullReportQ($site);
      echo "
      <table class=table>
        <thead>
          <tr>
            <th>Site</th><th>Building</th><th>Room</th><th>Tag</th><th>Model</th><th>Current status</th>
          </tr>
        </thead>
        <tbody>";

        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
          echo "<tr>";
          echo "<td>".$row['site']."</td><td>".$row['building']."</td><td>".$row['room']."</td><td>".$row['tag']."</td><td>".$row['model']."</td><td>".$row['status']."</td>";
          echo "</tr>";
        }

        echo "</tbody></table>";
}
/* F placementReport selects the placements from the database and uses them were the function is called. */
function placementReport($site){
  $result = placementReportQ($site);
    echo "
        <table class=table>
    	  	<thead>
    	    	<tr>
              <th>Site</th>
    	      	<th>Room</th>
    	      	<th>Device Type</th>
    					<th>Count</th>
    	    	</tr>
    	  	</thead>
          <tbody>";

  	while ($row = $result->fetch_array(MYSQLI_ASSOC)){
  		//display placement table data
  		echo "<tr><td>".$row['site']."</td><td>".$row['room']."</td><td>".$row['device_type']."</td><td>".$row['count']."</td></tr>";
  	}

    echo "</tbody>
        </table>";
}
/* F moveReport selectes moves from the database and uses them where the function is called*/
/*TODO Bring the functionality of this function to ALL functions on report page.*/

function moveReport($site){
  $result = moveReportQ($site);
  echo "
  	<table class=table>
  		  <thead>
  		    <tr>
            <th>Site</th><th>Tag</th><th>Old Room</th><th>New Building</th><th>New Room</th><th>Device Type</th><th>Status</th>
  		    </tr>
  		  </thead>
  		  <tbody>";


  if(isset($_POST['formSite'])){
  while ($row = $result->fetch_array(MYSQLI_ASSOC)){
    echo
    "<td>".$row['site']."</td>
    <td>".$row['tag']."</td>
    <td>".$row['old_room']."</td>
    <td>".$row['new_building']."</td>
    <td>".$row['new_room']."</td>
    <td>".$row['device_type']."</td>
    <td>".$row['status']."</td>
    <tr></tr>";
  	}
    echo "</tbody>
        </table>";
  }



}
/* F rolloffReport selects rolloff items from the PULL_LIST table and displays where called*/
function rolloffReport($site){
  $result = rollOffReportQ($site);
  if($result == NULL){
    return;
  }
  echo "
          <table class=table>
            <thead>
              <tr>
                <th>Site</th>
                <th>Roll Off</th>
                <th>Tag</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>";


  if(isset($_POST['formSite'])){
  while ($row = $result->fetch_array(MYSQLI_ASSOC)){
    echo
    "<tr></tr>
    <td>".$row['site']."</td>
    <td>".$row['roll_off']."</td>
    <td>".$row['tag']."</td>
    <td>".$row['timestamp']."</td>
    <tr></tr>";
  	}
    echo "</tbody>
        </table>";
  }
}

//display Final Desitnation report
function displayDesReport($site){
  echo "<table><thead><tr>
    <th>Site</th>
    <th>Building</th>
    <th>Room</th>
    <th>Device Type</th>
    <th>Tag</th>
    </tr></thead><tbody>";
  $reportArray = finalDesReportQ($site);
  while ($row = $reportArray->fetch_array(MYSQLI_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['site'] . "</td>";
    echo "<td>" . $row['building'] . "</td>";
    echo "<td>" . $row['room'] . "</td>";
    echo "<td>" . $row['device_type'] . "</td>";
    echo "<td>" . $row['tag'] . "</td>";
    echo "</tr>";
  }
  echo "</tbody></table>";

//return $result;

}

/**********************Functions for staging.php****************************/

function overviewMain($site = "Where?", $deviceType = ""){
  global $db;
  $query = "SELECT * FROM arrg_STAGING WHERE site LIKE '" . $site . "'" ;
  $result = $db->query($query);
  $resultArray = placementCounts($site, $deviceType);
  //set table header
  if ($deviceType == "cb"){
    $cat = "Chromebook";
  }elseif ($deviceType == "lt"){
    $cat = "Laptop";
  }elseif ($deviceType == "dt"){
    $cat = "Desktop";
  }
  $y = 3;//number of times to run though the current/change loop
  echo "<h2>" . $cat . "</h2>";
  echo "<table><thead><tr>";
  echo "<th colspan='2'>Incoming</th>";
  echo "<th colspan='2'>Delivered</th>";
  echo "<th colspan='2'>Unbox</th>";
  echo "<th colspan='2'>Setup</th>";
  if ($deviceType == "cb") {
    echo "<th colspan='2'>Labels</th>";
    $y++;//add one to current/change loop
  }
  echo "</tr><tr>";
  //current/change loop
  for ($x=0; $x <= $y; $x++){
    echo "<th>Current</th>";
    echo "<th>Change</th>";
  }
  echo "</tr></thead><tbody>";
  echo "<tr>";
  //incoming
  echo "<td>" . $resultArray[$deviceType."_incoming"] . "</td>";
  echo "<td><input class='inputbox tag' type='text' name='".$deviceType."_incoming' ></td>";

  echo "<td>" . $resultArray[$deviceType."_delivered"] . "</td>";
  echo "<td><input class='inputbox tag' type='text' name='".$deviceType."_delivered' ></td>";

  echo "<td>" . $resultArray[$deviceType."_unboxed"] . "</td>";
  echo "<td><input class='inputbox tag' type='text' name='".$deviceType."_unboxed' ></td>";

  echo "<td>" . $resultArray[$deviceType."_setup"] . "</td>";
  echo "<td><input class='inputbox tag' type='text' name='".$deviceType."_setup' ></td>";

  if ($deviceType == "cb"){
    echo "<td>" . $resultArray[$deviceType."_labels"] . "</td>";
  echo "<td><input class='inputbox tag' type='text' name='".$deviceType."_labels' ></td>";
  }
  echo "</tbody></table>";

return $result;

}
