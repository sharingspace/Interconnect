<?php
require_once("conn.php");
require_once("include/security.php");
require_once("../bll/f_common.php");
require_once("../bll/survey_common.php");

$head="Change Password";
$button="form_update_pass";
$lbl="Update";
$msg="";

if(isset($_POST['form_update_pass']))
{
   $msg_class="error"; //success, error, warn
   $msg="";
   if($_POST['old_password']=='' || $_POST['new_password']=='' || $_POST['confirm_password']=='')
     $msg.='One or more field is missed or left blank. Please fill all field properly and try again.';
   if($_POST['new_password']!=$_POST['confirm_password'])
     $msg.='New and confirm password are not same.';
   if(strlen($_POST['new_password'])<6)
     $msg.='Password must be at least 6 character long';
   if($msg=="")
   {
     $sql = "SELECT * FROM admin_user WHERE adminId='$_SESSION[admin_id]' AND password='".make_sql_format_text($_POST['old_password'])."'";
     $query = mysql_query($sql);
     if(mysql_num_rows($query)==0)
	   $msg="Invalid current password.";
	 else
     {
	   //Update New Password
	   $sql_update="UPDATE admin_user SET password='".make_sql_format_text($_POST['new_password'])."' WHERE adminId='$_SESSION[admin_id]'";
	   if(mysql_query($sql_update))
         $msg = "Password updated successfully.";
	   else
	     $msg = "Error while update new password.";
	 }
   }
}
else
  $_POST=reset_default_values();



//Form default values
function reset_default_values()
{
  $default_values = array('old_password'=>'', 'new_password'=>'', 'confirm_password'=>'');
  return $default_values;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"><head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Pro Admin</title>

<?php require_once("load_jquery_js_css.php");?>

<script>

$(document).ready(function(){

  $("#frm_admin").validate();

});

</script>



<link href="css/main.css" rel="stylesheet" type="text/css" />

</head>



<body>

<div class="wapper">

  <?php require_once("header.php"); ?>

  <?php require_once("left_menu.php"); ?>

  <div class="contentarea">

    <h1><?php echo $head;?></h1>

    <?php if($msg!=""){ ?>

    <div class="msg <?php echo $msg_class;?>"><?php echo $msg;?></div>

    <?php } ?>

    <p>&nbsp;</p>

    

    <form name="frm_admin" id="frm_admin" method="post" action="<?php echo $current_page_name;if(isset($_REQUEST["eid"]) && strlen($_REQUEST["eid"]) > 0) echo "?eid=".$_REQUEST["eid"];?>" enctype="multipart/form-data" class="fields">

      <p>
        <label for="Label one">Current Password:</label>
        <input type="text" name="old_password" id="Label one" value="<?php echo $_POST['old_password'];?>" class="required" />
      </p>
      <p>&nbsp;</p>

      <p>
        <label for="Label two">New Password:</label>
        <input type="text" name="new_password" id="Label two" value="<?php echo $_POST['new_password'];?>" class="required" />
      </p>
      <p>&nbsp;</p>

      <p>
        <label for="Label three">Confirm Password:</label>
        <input type="text" name="confirm_password" id="Label three" value="<?php echo $_POST['confirm_password'];?>" class="required" />
      </p>
      <p>&nbsp;</p>

      <p>
        <input type="submit" name="<?php echo $button;?>" id="button" value="<?php echo $lbl;?>" class="subtn" />
      </p>
      <p>&nbsp;</p>
    </form>
  </div>
</div>
<?php require_once("footer.php"); ?>
</body>
</html>

