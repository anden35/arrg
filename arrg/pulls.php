<?php

require 'functions.php';
global $db;
head();
$site = "";
?>
<form action="" method="POST">

<?php
$_POST['formSite'] = siteSelector();
?>


<?php

if(isset($_POST['submit']))  {
	if ($_POST['formSite'] == ""){
		echo "No site selected";
	} else {
	 $site = $_POST['formSite'];
	 $_POST = array_slice($_POST, 2);
	 $set_query = null;
	 if (empty($_POST)){//if there is no changes to the db
	 		 echo "Here you go:";
	 	 } else {
	 		 foreach($_POST as $tag => $status){//updates the db with pull changes
				 $set_query .= "UPDATE arrg_PULL_LIST SET status ='" . $status . "' WHERE tag = '" .$tag ."'; " ;
			 }
			 if ($db->multi_query($set_query)){
				 do {
				 } while ($db->more_results() && $db->next_result());
				 echo "Yo, I updated the DB for you, Dawg";
		 }
 	 }
 }
}

$result = pullReportQ($site);
$db->close();

?>


<h1>Pull List </h1>
<table class="table">
  <thead>
    <tr>
			<th>Building</th>
      <th>Room</th>
      <th>Tag</th>
      <th>Model</th>
      <th>Current status</th>
      <th>Change status</th>
    </tr>
  </thead>
  <tbody>
 <form action="" method="post">
<?php

while ($row = $result->fetch_array(MYSQLI_ASSOC)){
  echo "<tr>";
	echo "<td>" . $row['building'] . "</td>";
	echo "<td>" . $row['room'] . "</td>";
	echo "<td>" . $row['tag'] . "</td>";
	echo "<td>" . $row['model'] . "</td>";
	echo "<td>" . $row['status'] . "</td>";
  echo "<td>
	<input type='radio' class='buttons_radio' name='".$row['tag']."' value='no_change'> No Change
  <input type='radio' class='buttons_radio' name='".$row['tag']."' value='pulled'> Pulled
  <input type='radio' class='buttons_radio' name='".$row['tag']."' value='summer_school'> Summer School
  <input type='radio' class='buttons_radio' name='".$row['tag']."' value='wax'> Wax
	<input type='radio' class='buttons_radio' name='".$row['tag']."' value='missing'> Missing";;
  echo "</td></tr>";
}



?>
 <input type="submit" name="submit">
 </form>
</tbody>
</table>

<footer>
<?php foot(); ?>
</footer>
