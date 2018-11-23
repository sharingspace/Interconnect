<?php
// This is a sample code in case you wish to check the username from a mysql db table
require("conn.php");
require("../bll/f_common.php");
if(isset($_GET['id']))
{
  $sql_del="DELETE FROM contact_list WHERE id='".(int)$_GET['id']."'";
  mysql_query($sql_del);
  header("Location: list_contact.php");
}
