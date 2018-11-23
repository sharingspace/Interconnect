<?php
$host = "localhost";
$username = "greater7_amin";
$password = "a*sMzfNnV@q{";
$db = "greater7_sms1";
@mysql_connect($host,$username,$password);
mysql_select_db($db);

$date_time=time();
echo $sql_data="UPDATE test_data_1 SET data='$date_time' WHERE id='1'";
if(mysql_query($sql_data))
  echo "success";
else
  echo "failed";
?>