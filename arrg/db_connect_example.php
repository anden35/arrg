<?php
/* add in your infommation and save file as db_connect.php */
$db_host="";
$db_name="";
$db_user="";
$db_pass="";


$db = new mysqli($db_host,$db_user,$db_pass,$db_name);
if ($db->connect_errno){
  echo "YO! CHECK YOUR DB CONNECTION...HERE IS SOME INFO TO HELP:".$db->connect_errno . " " . $db->connect_error;
}
