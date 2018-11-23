<?php
require_once("conn.php");
require_once("../bll/f_common.php");
require_once("include/security.php");
require_once("../bll/survey_common.php");

$head="Mood SMS(Schedule to Send Instant SMS)";
$button="form_data_insert";
$lbl="Schedule to Send SMS";
$msg="";
$msg_class="success"; //success, error, warn

//checking Inserting button Clicked or not
if(isset($_POST['btnSend']))
{
//    $msg_class="error"; //success, error, warn
//	$msg.="Sorry you don't have any contact to send sms.";
  if(trim($_POST['sms_text'])!="")
  {
	  $msg="";
	  $successfully_scheduled=0;
	  $successfully_sms_scheduled=0;
	  if($_POST['txt_mobile_no_with_country_code']!="")
	  {
		  $phone_no=trim(str_replace("-","",$_POST['txt_mobile_no_with_country_code']));
		  if(substr($phone_no,0,1)!="+")
		    $phone_no="+".$phone_no;
		  //Send SMS to $phone_no using Twilio
		  $data_schedule['sms_to_contact_no']=$phone_no;
		  $data_schedule['sent_from_twilio_no']=$_POST['hid_sender_no_or_name'];
		  $data_schedule['sms_text']=$_POST['sms_text'];
		  $data_schedule['sms_request_sent_at']=$global_date_time;
		  if(save_data_into_table($data_schedule,"cron_sms_schedule"))
		  {
			$successfully_scheduled++;
			$successfully_sms_scheduled++;
			$msg.="SMS scheduled for $phone_no<br />";
			$msg_class="success"; //success, error, warn
			
			//Save this contact number into contact_list table.
			$sql_exist="SELECT * FROM contact_list WHERE mobile_no_with_country_code='$phone_no'";
			$q_exist=mysql_query($sql_exist);
			if(mysql_num_rows($q_exist)>0)
			{
			  $sql_update="UPDATE contact_list SET is_stop='1' WHERE mobile_no_with_country_code='$phone_no'";
			  mysql_query($sql_update);
			}
			else
			{
			  $contact_data=array('mobile_no_with_country_code'=>$phone_no, 'created_at'=>$global_date_time);
			  save_data_into_table($contact_data,"contact_list");
			}			
		  }
	  }
	  if($successfully_sms_scheduled>0)
	  {
		header("Location: success.php");
	    $msg="Total SMS Scheduled: {$successfully_sms_scheduled}<br />".$msg;
	    $_SESSION['msg']=$msg;
	  }
  }
  else
  {
    $msg_class="error"; //success, error, warn
	$msg.="Please enter SMS Text.";
  }
}
//echo $msg_class." ".$msg;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Survey - Schedule Questions to Send via SMS</title>
<?php require_once("load_jquery_js_css.php");?>
<script type="text/javascript">

$(document).ready(function(){
  $("#frm_admin").validate();
  $('#sms_text1').keyup(function() {
	var tot_char, char_left;
	tot_char=this.value.replace(/{.*}/g, '').length;
	char_left = 160-tot_char;
    $('#span_char_count1').text( char_left );
  });
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
    <?php if($msg_class=="error"){ ?>
    <div class="<?php echo $msg_class;?>"> <?php echo $msg;?></div>
    <?php }elseif($msg!=""){ ?>
    <div class="<?php echo $msg_class;?>"> <strong>Total SMS Scheduled: <?php echo $successfully_scheduled;?></strong> <br />
      <br />
      <strong>Detail:</strong><br />
      <?php echo $msg;?> </div>
    <?php } ?>
    <p>&nbsp;</p>
    <form name="frm_admin" id="frm_admin" method="post" action="<?php echo $base_admin.$current_page_name;?>" enctype="multipart/form-data" class="fields">                  
      <p>
        Sender No/Name: <?php echo $global_sms_send_from_mobile_no;?>
        <input type="hidden" name="hid_sender_no_or_name" id="hid_sender_no_or_name" value="<?php echo $global_sms_send_from_mobile_no;?>"/>
      </p>
      <br />
	  <p>
	    Enter mobile number with country code: <input type="text" value="+1" name="txt_mobile_no_with_country_code" id="txt_mobile_no_with_country_code" />
	  </p>
	  <br />
      <p> Write your Question here<br />
        <br />
        <textarea name="sms_text" cols="60" rows="4" id="sms_text1">Right how, how is your physical, emotional, intellectual, and spiritual thriving? And what's your location?</textarea>
        <span id="span_char_count1"><strong>53</strong></span> Character Left <br />
        [Single SMS character limit 160 character] </p>
      <p>
        <input type="submit" name="btnSend" id="btnSend" value="<?php echo $lbl;?>" class="subtn" />
        <br />
        <br />
        <span class="infobar">[Once you press <?php echo $lbl;?> button system will automatically create a schedule to send this question to Entered Mobile number</span> <br /><br />
        <span class="infobar">Please note that system will start sending Question via SMS within 2 mintues.</span> </p>
    </form>
  </div>
</div>
<?php require_once("footer.php"); ?>
</body>
</html>
