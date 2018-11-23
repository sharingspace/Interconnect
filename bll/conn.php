<?php
@session_start();
if($_SERVER["SERVER_NAME"]=="localhost")  //Local Host
{
  $host = "localhost";
  $username = "root";
  $password = "";
  $db = "desk_survey";
}
else  //Server
{
  $host = "localhost";
  $username = "digitalh_aminul";
  $password = "W))Cia1aplk6";
  $db = "digitalh_survey";
}
@mysql_connect($host,$username,$password);
mysql_select_db($db);
?>