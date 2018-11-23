<?php
@session_start();
require_once(dirname(__FILE__) . "/../../bll/f_common.php");
//if(!isset($_SESSION['admin_id'])) header("Location: ".$base_admin);
if(isset($_REQUEST['sign']) && $_REQUEST['sign']==1){
$_SESSION['admin_type'] = "";
$_SESSION['login_name'] = "";
$_SESSION['last_login_date_time'] = "";
$_SESSION['admin_id'] = "";
session_destroy();
//$url=$base_client;//"http://www.amysallnaturals.com/admin";
//header("Location: ".$base_admin);
header("Location: $base_admin");
}
?>