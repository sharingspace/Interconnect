<?php
require_once("conn.php");
require_once("../bll/f_common.php");
require_once("../bll/survey_common.php");
if($_SERVER["SERVER_NAME"]!="localhost")
{
  if(!isset($_SESSION['login_name']))
    echo "<script>window.location.replace('index.php');</script>";
  if($_SESSION['login_name']=="")
    echo "<script>window.location.replace('index.php');</script>";
}
$last_login_time = $_SESSION['last_login_date_time'];
$admin_photo_file_name = $_SESSION['admin_photo_file_name'];
?>

<div class="topbar">
  <div class="proname">Current Time: <?php echo show_formated_date($global_date_time,7);?></div>
  <div class="livesite"><a href="<?php echo $base_client;?>" target="_blank">View Site</a></div>
  <div class="topnav">
    <ul>
      <li class="adico sepa">Welcome <?php echo $_SESSION['full_name'];?></li>
      <li class="sepa">Last Login: <?php echo $last_login_time;?></li>
      <li class="settingico sepa"><a href="#">Setting</a></li>
      <li class="logoutico"><a href="include/security.php?sign=1">LogOut</a></li>
    </ul>
  </div>
</div>
