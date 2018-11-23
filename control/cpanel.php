<?php
require_once("conn.php");
require_once("include/security.php");
require_once("../bll/f_common.php");
$today=date("Y-m-d");

if($_SERVER["SERVER_NAME"]!="localhost" && $_SESSION['admin_id']=="")
  echo "<script>window.location.replace('index.php');</script>";
  
//header("Location: file_upload.php");

//Total Inbound SMS Received
$sql_inbound_info="SELECT inbound.* FROM inbound_sms_twilio inbound WHERE Live_or_Test='Live' AND mark_as_read='1' AND sms_for_admin_id='$_SESSION[admin_id]' ORDER BY id DESC";
$q_inbound_info=mysql_query($sql_inbound_info);
$total_uread_inbound_sms=mysql_num_rows($q_inbound_info);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Survey</title>
<?php require_once("load_jquery_js_css.php");?>
</head>

<body>
<div class="wapper">
  <?php require_once("header.php"); ?>
  <?php require_once("left_menu.php"); ?>
  <div class="contentarea">
    <h1>Welcome <?php echo $_SESSION['full_name'];?> To Admin Panel</h1>
    <h2>You are logged in as <?php echo $_SESSION['login_name'];?></h2>
    <p>
        Basically 2 Sections are there<br />
        <strong>1. Contact Management: </strong><br />
          From here you can add new contacts, edit contacts, view all contacts and delete contacts<br /><br />
        <strong>2. Questions/Send SMS: </strong><br />
          Send Questions: Write your question here and click "Schedule to Send SMS" will intantly schedule sms for all your contacts(in database). SMS send every 2 minutes. 30 SMS per minute<br />
          Monitor Questions Sending: you can see last time triggered status of sms sent to contacts.<br />
          Past Questionaries: You can see all scheduled sms here(from the first day)      
    </p>
    <br /><br /><br />

    Not you? <a href="<?php echo $base_admin;?>include/security.php?sign=1">Click</a> here to Log in as different user?    
    <ul>
      <li><a href="change_pass.php">Change Password</a></li>
      <li>Last Login: <?php echo $_SESSION['last_login_date_time'];?><br />
      </li>
    </ul>
  </div>
</div>
<?php require_once("footer.php"); ?>
</body>
</html>
