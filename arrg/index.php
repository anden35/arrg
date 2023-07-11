

<?php
require 'functions.php';
head();
 ?>
<html>
<body>
  <div class="parent_container">
<div class = "Action_buttons_div">
	<label class="labels">Actions</label>

  <form method="link" action="staging.php">
    <input class="buttons" type="submit" value="Staging">
  </form>

	<form method="link" action="placements.php">
		<input class="buttons" type="submit" value="Placements">
	</form>

	<form method="link" action="pulls.php">
		<input class="buttons" type="submit" value="Pulls">
	</form>

	<form method="link" action="moves.php">
		<input class="buttons" type="submit" value="Moves">
	</form>

	<form method="link" action="rolloff.php">
		<input class="buttons" type="submit" value="Rolloff">
	</form>

	</div>

<div class = "Report_buttons_div">
	<label class="labels">Reports</label>

	<form method="link" action="overviewreport.php">
		<input class="buttons" type="submit" value="Overview Report">
	</form>

  <form method="link" action="reportPage.php">
    <input class="buttons" type="submit" value="Reports">
  </form>

	</div>

</div>

</body>

<footer>
<?php foot(); ?>
</footer>
</html>
