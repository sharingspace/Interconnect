<?php
session_start();
//print_r($_SESSION);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SMS Campaign</title>
<?php require_once("load_jquery_js_css.php");?>
</head>
<body>
<!-- for date time picker this should go after your </body> -->
<div class="wapper">
<?php require_once("header.php"); ?>
<?php require_once("left_menu.php"); ?>
<div class="contentarea">
  <h1>Successfully Scheduled</h1>
  <span class="infobar">You can monitor sms from <a href="monitor_campaign_sms.php">here</a></span><br /><br />
  <h3><?php echo $_SESSION['msg'];?></h3>
  <br />
</div>
</div>
<?php require_once("footer.php"); ?>
</body>
</html>