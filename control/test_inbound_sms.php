<?php

require_once("conn.php");

require_once("../bll/f_common.php");

require_once("include/security.php");

require_once("../bll/survey_common.php");



$head="Send SMS to Inbound No";

$msg="";



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

if(isset($_POST['btnSendInboundSMS']))

{

  unset($_POST['btnSendInboundSMS']);

  $_POST['Live_or_Test']='Test';

  if(save_data_into_table($_POST,"inbound_sms_twilio"))

  {

    $msg="Inbound SMS received and saved successfully.";

	$msg_class="success"; //success, error, warn

  }

  else

  {

    $msg="Error while save inbound SMS.";

	$msg_class="error"; //success, error, warn

  }

}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>SMS</title>

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

    <p>

    <form id="frm_send_sms" name="frm_send_sms" method="post" action="">

      <table width="50%" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td width="18%">sms_received_at</td>

          <td width="82%"><input type="text" name="sms_received_at" id="sms_received_at" value="<?php echo $global_date_time;?>"  readonly="readonly" /></td>

        </tr>

        <tr>

          <td>MessageSid</td>

          <td><input type="text" name="MessageSid" id="text" value="SMd<?php echo time();?>" readonly />

            [Provided by Twilio]</td>

        </tr>

        <tr>

          <td>AccountSid</td>

          <td><input type="text" name="AccountSid" id="text" value="AC<?php echo time();?>" readonly />

            [Provided by Twilio]</td>

        </tr>

        <tr>

          <td>SMS To</td>

          <td><input name="TO" type="text" id="TO" value="+17166713049" readonly />

            (Fixed, Twilio Inbound No) </td>

        </tr>

        <tr>

          <td>SmsStatus</td>

          <td><input name="SmsStatus" type="text" id="SmsStatus" value="received" readonly="readonly" /></td>

        </tr>

        <tr>

          <td>SMS Sent From Mobile No</td>

          <td><input type="text" name="From" id="From" class="required" value="+8801771240022" />

            (Sender Mobile No)</td>

        </tr>

        <tr>

          <td valign="top">Message</td>

          <td><textarea name="Body" cols="40" rows="3" id="sms_text1" class="required"></textarea>

            (SMS Text)<br />

            <span style="padding-left:255px;" id="span_char_count1">000</span> Character </td>

        </tr>

        <tr>

          <td>&nbsp;</td>

          <td><input type="submit" name="btnSendInboundSMS" id="btnSendInboundSMS" value="Send SMS to Twilio Inbound No" /></td>

        </tr>

      </table>

    </form>

    </p>

    <p>&nbsp;</p>

  </div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>