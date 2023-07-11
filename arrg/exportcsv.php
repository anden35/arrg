<?php

require 'functions.php';


session_start();
if (isset($_SESSION['formSite']) && isset($_SESSION['siteReport'])) {
  exportToCSVBuilder($_SESSION['formSite'] ,$_SESSION['siteReport']);
  unset($_SESSION['formSite'],$_SESSION['siteReport']);
}else{
  echo "Ummm......what are you trying to do?!?!?! Go back to the report page!";
}
