<?php
require 'functions.php';
session_start();

head();
?>


<div class="reportPage_container">
  <head>
    <div class="reportSelector_containter">
      <form action="" method="POST">

        <h4>Please select Site and Report:</h4>
        <?php reportSelector(); ?>
        <input type="submit" name="submit" value="Submit">
         <!-- <input type="submit" name="export" value="Export"> -->
        <?php if(isset($_POST['submit'])){
          if($_POST['formSite'] && $_POST['siteReport']){


          $_SESSION['formSite'] = $_POST['formSite'];
          $_SESSION['siteReport'] = $_POST['siteReport'];
          echo '<a  href="exportcsv.php" class="button">Export to CSV</a>';
        }
      }  ?>
      </form>
    </div>
  </head>

    <body>
      <div class="reportTable_container">
        <?php
            $formSite = isset($_POST['formSite']) ? $_POST['formSite'] : NULL;
            $siteReport = isset($_POST['siteReport']) ? $_POST['siteReport'] : NULL;

            if(isset($_POST['submit'])){

              if($formSite == NULL || $siteReport == NULL){
                echo 'Select both site and report to run';
              }else{

                reportSelection($formSite, $siteReport);

              }


            }


         ?>
      </div>
    </body>
</div>
<footer>
<?php foot(); ?>
</footer>
