<?php
require 'functions.php';
head();
$currentRoom = "";
$currentBuilding ="";
if(isset($_POST["submit"])){
	$currentRoom = $_POST['newRoom'];
	$currentBuilding = $_POST['newBulding'];
}
?>


<div class="moves_container">

	<form action="" method="POST">
<head>

		<?php $_POST['formSite'] = siteselector();
		?>

		<br>
</head>

<body>
<div class="input_box_container">
  	Building:
  	<input type="text" name="newBulding" value="<?php echo $currentBuilding;?>">
  	<br>
		New Room:
		<input type="text" name="newRoom" value="<?php echo $currentRoom;?>">
		<br>
		Asset Tag:
		<input type="text" name="assetTag" autofocus>
		<br>
		<br>
		<input type="submit" name="submit" value="Move">
	</form>
</div>

</div>

	<?php
	if(isset($_POST["submit"])){

		$machineType = isset($_POST['machineType']) ? $_POST['machineType'] : '';
		$formSite = isset($_POST['formSite']) ? $_POST['formSite'] : '';
		$assetTag = isset($_POST['assetTag']) ? $_POST['assetTag'] : '';
		$newRoom = isset($_POST['newRoom']) ? $_POST['newRoom'] : '';
		$newBulding = isset($_POST['newBulding']) ? $_POST['newBulding'] : '';
		$status = "Moved";



			if($formSite != NULL && $assetTag != NULL && $newRoom != NULL && $newBulding != NULL){
				insertMoves($formSite,$assetTag,$newRoom,$machineType,$status,$newBulding);
			}else{
				echo "<h2>All form data must be complete before move is recorded.</h2>";
				if($formSite != NULL){
				moveReport($formSite);
				}
			}
		}

	$db->close();


		?>
</body>

<footer>
<?php foot(); ?>
</footer>
