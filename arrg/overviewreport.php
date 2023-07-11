<?php
  require 'functions.php';
  global $db;
  global $deviceTypeArray;
  global $siteArray;
  head();






 ?>

 <table class="table">
   <thead>
     <tr>
       <th></th>
       <th colspan="5">Pulls</th>
       <th colspan="6">Placement - ChromeBooks</th>
       <th colspan="5">Placement - Laptops</th>
       <th colspan="5">Placement - Desktops</th>
     </tr>
     <tr>
       <th>Site</th>
       <th>Total pulls</th>
       <th>Number pulled</th>
       <th>Computers missing</th>
       <th>Pulls left</th>
       <th>Pulls on hold</th>
       <th>New</th>
       <th>Delivered</th>
       <th>Unboxed</th>
       <th>Setup</th>
       <th>Labels</th>
       <th>Deployed</th>
       <th>New</th>
       <th>Delivered</th>
       <th>Unboxed</th>
       <th>Setup</th>
       <th>Deployed</th>
       <th>New</th>
       <th>Delivered</th>
       <th>Unboxed</th>
       <th>Setup</th>
       <th>Deployed</th>
       <th>Site</th>
     </tr>
   </thead>
   <tbody>
     <?php
     //display data in table
      foreach ($siteArray as $site) {
        //run funcation for pulls, returns an array
        $pullCounts = pullsCounts($site);

        //site name
        echo "<tr><td>". $site ."</td>";
        //total pull per site
        echo "<td>" . $pullCounts['total'] . "</td>" ;
        //total pulled per site
        echo "<td>" . $pullCounts['pulled'] . "</td>" ;
        //computers missing per site
        echo "<td>" . $pullCounts['missing'] . "</td>";
        //total left to pullCounts per site NEED TO ADD MISSING COMPUTERS
        echo "<td>" . ($pullCounts['total']-$pullCounts['pulled']-$pullCounts['missing'] ) . "</td>" ;
        //total holds per site
        echo "<td>" . $pullCounts['holds'] . "</td>" ;
        //placment totels
        foreach ($deviceTypeArray as $deviceType ) {
          $placementCounts = placementCounts($site, $deviceType);
          //New $deviceType per site
          echo "<td>" . $placementCounts[$deviceType.'_incoming'] . "</td>";
          //deliverd $deviceType per site
          echo "<td>" . $placementCounts[$deviceType.'_delivered'] . "</td>";
          //unbox count for $deviceType per sites
          echo "<td>" . $placementCounts[$deviceType.'_unboxed'] . "</td>";
          //setup count for $deviceType per sites
          echo "<td>" . $placementCounts[$deviceType.'_setup'] . "</td>";
          //lables place on CB onlys, totel done per site
          if ($deviceType == "cb") {
            echo "<td>" . $placementCounts[$deviceType.'_labels'] . "</td>";
          }
          //Deployed count for $deviceType per sites
          echo "<td>" . $placementCounts[$deviceType.'_deployed'] . "</td>";


        }
        //site name for the end of the table.
        echo "<td>". $site ."</td>";
        echo "</tr>";
      }
      ?>
      <footer>
      <?php foot(); ?>
      </footer>
