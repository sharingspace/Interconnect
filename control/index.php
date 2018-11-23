<?php
require_once("conn.php");
require_once("../bll/f_common.php");
$msg="";
if(isset($_POST["btn_login"]))
{
  if($_POST["userid"]!="" && $_POST["password"]!="")
  {
  $result=mysql_query("select * from admin_user where userid='$_POST[userid]' and password='$_POST[password]'");
  if(mysql_num_rows($result)>0)
  {
    $row = mysql_fetch_array($result);
  	$_SESSION['full_name']=$_POST["full_name"];
  	$_SESSION['login_name']=$_POST["userid"];
	$_SESSION['admin_type']=$row['admin_type']; //1=admin 2=sub_admin
	if($row['last_login_date_time']=="0000-00-00 00:00:00")
	  $_SESSION['last_login_date_time']="First Time Login";
	else
	  $_SESSION['last_login_date_time']=$row['last_login_date_time'];
  	$_SESSION['admin_id']=$row['id'];
    $_SESSION['admin_photo_file_name']=$row['photo_file_name'];

    //update last login time
    $cur_date_time = date("Y-m-d H:i:s");
    mysql_query("update admin_user set last_login_date_time='".$cur_date_time."' where userid='$_POST[userid]'");
    header("location:cpanel.php");
  }else { 
    $msg="Please Enter Valid Userid and password";
	$msg_class="warn"; //success, error, warn
  }
  }else {
    $msg="Please Enter Userid and password";
	$msg_class="error"; //success, error, warn
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Survey</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link href="css/login.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="login">
  <h1>Login to your account</h1>
  <form class="form" method="post" action="">
    <?php if($msg!=""){ ?>
    <div class="msg <?php echo $msg_class;?>"><?php echo $msg;?></div>
    <?php } ?>
    <p class="field">
      <input type="text" name="userid" id="userid" placeholder="Username or email" required/>
      <i class="fa fa-user"></i> </p>
    <p class="field">
      <input type="password" name="password" id="password" placeholder="Password" required/>
      <i class="fa fa-lock"></i> </p>
    <p class="submit">
      <input type="submit" name="btn_login" value="Login">
    </p>
  </form>
</div>
<div class="copyright">
  <p>Copyright &copy; 2014.</p>
</div>
</body>
</html>
