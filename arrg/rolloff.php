<?php
require 'functions.php';
head();




 ?>



<div class="rolloff_container">
  <head>
    <form action="" method='POST'>
        <?php
        $_POST['formSite'] = rollOffSelector();
        ?>
        <input type="text" name="assetTag" autofocus>
          <br>
        <input type='submit' name='done' value='Done'>
    </form>
  </head>
  <body>

      <div class="assets_guide_container" id=rolloff_container>
          <?php

                  if(isset($_POST['done'])){

                    $formSite = isset($_POST['formSite']) ? $_POST['formSite'] : NULL;
                    $assetTag = isset($_POST['assetTag']) ? $_POST['assetTag'] : NULL;

                    if(isset($_POST['assetTag'])){
                        updateRolloff($formSite, $assetTag);
                      }

                    }

                    $db->close();
           ?>

      </div>
  </body>
  <footer>
    <br>
    <?php foot();?>
  </footer>
</div>
