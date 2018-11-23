<?php
require_once("conn.php");
//require_once("include/security.php");
require_once("../bll/f_common.php");
require_once("../bll/survey_common.php");
require_once('../twilio-php/Services/Twilio.php'); // Loads the Twilio SMS API library

$msg="";
$head="Send SMS";
/*
  INBOUND SMS RECEIVED HERE FROM TWILIO.COM
	ToCountry => US <br />ToState => NY <br />
	SmsMessageSid => SMd9312bf851a2ae9e06520360421f9198 <br />
	NumMedia => 0 <br />
	ToCity =>  <br />
	FromZip =>  <br />
	SmsSid => SMd9312bf851a2ae9e06520360421f9198 <br />
	FromState =>  <br />
	SmsStatus => received <br />
	FromCity =>  <br />
	Body =>  Test by sumon bhoot fm <br />
	FromCountry => BD <br />
	To => +17166713049 <br />
	ToZip =>  <br />
	MessageSid => SMd9312bf851a2ae9e06520360421f9198 <br />
	AccountSid => AC62282e0dcbd241c4a14fe4fc7318168e <br />
	From => +8801521328551 <br />
	ApiVersion => 2010-04-01 <br />  
*/

if(isset($_POST['btnSendSMS']))
{
    $phone_nos=explode(",",$_POST['sms_receiver_phone_no']);
    //printr($phone_nos);
    foreach($phone_nos as $phone_no):
      //$contact_detail = get_contact_detail_by_id($contact_id);
	  $phone_no=trim($phone_no);
	  //Send SMS to $contact_no using Twilio
	  $client = new Services_Twilio($global_sid, $global_token);
	  if($client->account->messages->sendMessage($_POST['sender_id'], $phone_no, $_POST['sms_text']))
	    $msg.="SMS sent to $contact_no number successfully.<br />";
	  else
	    $msg.="Error while save data in to outbound_sms table.";
   endforeach;
}

//Get all available numbers
$sql_all_numbers="SELECT * FROM list_inbound_numbers";
$q_all_numbers=mysql_query($sql_all_numbers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Send Simple SMS</title>
<?php require_once("load_jquery_js_css.php");?>
<script type="text/javascript">
jQuery(document).ready(function(){
  $("#frm_send_sms").validate();
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
<div class="wapper">
  <?php require_once("header.php"); ?>
  <?php require_once("left_menu.php"); ?>
  <div class="contentarea">
    <form id="frm_send_sms" name="frm_send_sms" action="" method="post">
    <h1><?php echo $head;?></h1>
    <div style="clear:both;"></div>
    <div style="padding-left:20px;">
      <?php if($msg!=""){ ?>
      <h2><?php echo $msg;?></h2>
      <?php } ?>
      <div>
        SMS Text: <br />
        <textarea name="sms_text" cols="60" class="required" id="sms_text1"></textarea>
        <span id="span_char_count1">160</span> Character Left<br />
      </div>
      <div style="clear:both"></div>
      <div>
        Recipent Phone No(s):<br />
        <input type="text" name="sms_receiver_phone_no" id="sms_receiver_phone_no" class="required" /> <br />
        [Multiple numbers Separated by comma]<br /><br />
        <input type="submit" id="btnSendSMS" name="btnSendSMS" value="Send SMS" />
      </div>
      <div>
        Sender No:<br />
        <?php if(mysql_num_rows($q_all_numbers)>0){ ?>
          <select id="sender_id" name="sender_id">
        <?php while($row=mysql_fetch_array($q_all_numbers)){ ?>
            <option value="<?php echo $row['phone_no'];?>"><?php echo $row['phone_no'];?></option>
        <?php } ?>
          </select>
        <?php } ?>
        <br />
        <input type="submit" id="btnSendSMS" name="btnSendSMS" value="Send SMS" />
      </div>
      
    </div>
    <div style="clear:both;"></div>
    </form>
    <p>&nbsp;</p>
    <p>&nbsp; </p>
  </div>
</div>
<?php require_once("footer.php"); ?>
</body>
</html>
