<?php
require 'functions.php';
global $db;
$lastRoom = "";
$lastBuilding ="";
head();
echo "<h1>Placement List</h1>";
?>
<form action="" method="POST">


<?php
$_POST['formSite'] = siteSelector();
?>

<?php
function updatePlacement() {
  echo "<br />";
  global $lastRoom ;
  global $lastBuilding ;
  global $db;
  $lastRoom = $_POST['room'];//room last used
  $lastBuilding = $_POST['building'];//building last used
  if (!$_POST['building']){
    feedback("bad","Missing building");
    return false;
  }
  if (!$_POST['room']) {
    feedback("bad","Missing room number");
    return false;
  }
  if (!$_POST['tag']){
    feedback("bad","No tag!");
    return false;
  }
  $roomCheck = $db->query("SELECT * FROM arrg_PLACEMENT WHERE tag='". $_POST['tag'] .  "'");
  if ($db->affected_rows == 0) {//if there is no tag, update DB
     $lastRoom = $_POST['room'];
     $db->query("UPDATE arrg_PLACEMENT SET tag='" . strtoupper($_POST['tag']) . "', building ='" . $_POST['building'] . "' WHERE site = '" . $_POST['formSite'] . "' AND room = '" . $_POST['room'] . "' AND tag IS NULL LIMIT 1");
     $affected = $db->affected_rows;
     if ($affected == 0){
       feedback("bad","Room not found");
     } else {
       echo "Good to go!";
     }
   } else { //tag found already in DB,
     while ($row = $roomCheck->fetch_array(MYSQLI_ASSOC)){
       $roomCheckArray = $row;
     }
     feedback("bad","Tag " . $roomCheckArray['tag'] . " has been recorded as deployed in room ". $roomCheckArray['room']);
   }
}

if(isset($_POST['submit'])){
  updatePlacement();
}
//main query for page
$query = "SELECT site,room,building,device_type,COUNT(*) AS count FROM `arrg_PLACEMENT` WHERE `site` LIKE '" . $_POST['formSite'] . "' AND `tag` IS NULL GROUP by `site`, `room`, `device_type` ORDER BY `room`";
$result = $db->query($query);
$db->close();
?>



<div class="confor2cols">

	<div class="leftcol">
		<!-- Input box for placement -->
		<form action="" method="POST" autocomplete="off">
      Building: <input class="inputbox building" type="text" name="building" value="<?php echo $lastBuilding ?>"> <br />
      Room:   <input class="inputbox room" type="text" name="room" value="<?php echo $lastRoom ?>"> <br />
      Asset Tag: <input class="inputbox tag" type="text" name="tag" autofocus> <br />
	  	<input type="submit" name="submit">
		</form>
	</div>
	<div class="rightcol">
		<!-- Display placement table -->
		<table class="table">
	  	<thead>
	    	<tr>
          <th>Building</th>
	      	<th>Room</th>
	      	<th>Device Type</th>
					<th>Count</th>
	    	</tr>
	  	</thead>
	  	<tbody>
<?php
	while ($row = $result->fetch_array(MYSQLI_ASSOC)){
		//display placement table data
		echo "<tr>";
    echo "<td>" . $row['building'] . "</td>";
    echo "<td>" . $row['room'] . "</td>";
    echo "<td>" . $row['device_type'] . "</td>";
    echo "<td>" . $row['count'] . "</td>";
    echo "</tr>";
	}

	?>

	 </form>
	</tbody>
	</table>
</div>
</div>

<footer>
<?php foot(); ?>
</footer>
