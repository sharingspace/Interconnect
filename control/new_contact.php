<?php
require_once("conn.php");
require_once("../bll/f_common.php");
require_once("include/security.php");
require_once("../bll/survey_common.php");

$head="Add Contact";
$button="form_data_insert";
$lbl=" Save ";
$msg="";

//checking Inserting button Clicked or not
if(isset($_POST['form_data_insert']))
{
  $msg='';
  $msg_class="error"; //success, error, warn
  if($_POST['mobile_no_with_country_code']=="")
    $msg.="Please enter mobile/contact no.";
  if(strlen($_POST['mobile_no_with_country_code'])<10)
    $msg.="Please enter mobile/contact no with country code.";
  $sql = "SELECT * FROM contact_list WHERE mobile_no_with_country_code=\"$_POST[mobile_no_with_country_code]\"";
  $query = mysql_query($sql);
  if(mysql_num_rows($query)>0)
    $msg = "Contact number is already exists.";

  if($msg=='')
  {
    //Inserting data into contact_list table
	unset($_POST['form_data_insert']); 
	$_POST['created_at']=$global_date_time;
    if(save_data_into_table($_POST,"contact_list"))
    {
	  $last_id=mysql_insert_id();
      $msg_class="success"; //success, error, warn
      $msg = "Contact number added successfully.";
	  $_POST = reset_default_values();
    }
    else
    {
      $msg_class="error"; //success, error, warn
      $msg = "Error while save contact number.";
    }
  }
}
elseif(!isset($_REQUEST['eid']))
{
  //default values
  $_POST = reset_default_values();
}

//code to update data
if(isset($_POST['form_data_update']) && strlen($_POST['form_data_update']) > 0 )
{
   $msg_class="error"; //success, error, warn
   if($_POST['mobile_no_with_country_code']=="")
     $msg='One or more field is missed or left blank. Please fill all fields properly and try again.';
  if(strlen($_POST['mobile_no_with_country_code'])<10)
    $msg.="Please enter mobile/contact no with country code.";
   $sql = "SELECT * FROM contact_list WHERE mobile_no_with_country_code='".make_sql_format_text($_POST['mobile_no_with_country_code'])."' AND id!=$_GET[eid]";
   $query = mysql_query($sql);
   if(mysql_num_rows($query)>0)
    $msg = "Contact number is already exists.";
   //update data here
   if($msg=="")
   {
	 unset($_POST['form_data_update']); 
	 $condition="id=$_GET[eid]";
	 $_POST['updated_at']=$global_date_time;
	 if(update_data_into_table($_POST,"contact_list",$condition))
	 {
		 $msg = "Successfully Updated.";
		 $msg_class="success"; //success, error, warn
	  }
	  else
	  {
		  $msg = "Error while update.";
		  $msg_class="error"; //success, error, warn
	   }
   }
   $button="form_data_update";
   $lbl="Update";
}

if(isset($_REQUEST['eid']) && strlen($_REQUEST['eid']) > 0 && !isset($_POST['form_data_update']))
{
 $head="Edit Contact";
 $rs_result=mysql_query("select * from contact_list where id=$_REQUEST[eid]");
 $_POST=mysql_fetch_array($rs_result);
 $button="form_data_update";
 $lbl="Update";
}

//Form default values
function reset_default_values()
{
  $default_values = array('mobile_no_with_country_code'=>'+1');
  return $default_values;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Survey - New Contact</title>
<?php require_once("load_jquery_js_css.php");?>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#frm_admin").validate();
});
</script>
</head>

<body>
<!-- for date time picker this should go after your </body> -->
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
        <label for="Label one">Phone/Contact No(with country code):</label>
        <input type="text" name="mobile_no_with_country_code" id="mobile_no_with_country_code" value="<?php echo $_POST['mobile_no_with_country_code'];?>" class="required" />
        <span class="iconpos"><i class="fa fa-pencil fa-lg"></i></span></p>
      <p>&nbsp;</p>
      <p>
        <input type="submit" name="<?php echo $button;?>" id="button" value="<?php echo $lbl;?>" class="subtn" />
        <a href="list_contact.php"><input type="button" id="btnSearch2" name="btnSearch2" value="Show All Contact" /></a>
      </p>
    </form>
  </div>
</div>
<?php require_once("footer.php"); ?>
</body>
</html>