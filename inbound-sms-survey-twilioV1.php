<?php
require_once("bll/conn.php");
require_once("bll/f_common.php");
require_once("bll/survey_common.php");
// Use the REST API Client to make requests to the Twilio REST API
require 'Twilio/autoload.php';
use Twilio\Rest\Client;

//check for duplicate http post by twilio(for same sms)
if(!isset($_POST['SmsMessageSid']) || $_POST['SmsMessageSid']=="")
  exit();
$sql_dup="SELECT * FROM inbound_sms_twilio WHERE MessageSid='$_POST[SmsMessageSid]'";
$q_dup=mysql_query($sql_dup);
if(mysql_num_rows($q_dup)>1)
{
  mail("aminulsumon@gmail.com","SURVEY Data Duplicate Request","SURVEY Data Duplicate Request for MessageSid = $_POST[SmsMessageSid]");
  exit();
}

$mail_body="";
foreach($_POST as $key=>$val):
  $mail_body.="$key => $val <br />";
  if($key!="SmsMessageSid" && $key!="SmsSid")
    $inbound_data[$key]=$val;
endforeach;

$inbound_data['sms_received_at']=$global_date_time;
/*
  INBOUND NO: +17166713049
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
	FromCountry => BD <br /> --
	To => +17166713049 <br />
	ToZip =>  <br />
	MessageSid => SMd9312bf851a2ae9e06520360421f9198 <br />
	AccountSid => AC62282e0dcbd241c4a14fe4fc7318168e <br />
	From => +8801521328551 <br />
	ApiVersion => 2010-04-01 <br />  
*/
save_data_into_table($inbound_data,"inbound_sms_twilio");

//save data into survey_incoming_sms table
$data_incoming['sms_from_mobile_no']=$inbound_data['From'];
$data_incoming['sms_to_twilio_no']=$inbound_data['To'];
$data_incoming['sms_text']=$inbound_data['Body'];
$data_incoming['sms_received_at']=$global_date_time;
save_data_into_table($data_incoming,"survey_incoming_sms");

$mail_body.="Insert Query: ".$_SESSION['insert_query']."<br />";
$sms_text=strtoupper(trim($inbound_data['Body']));
if($sms_text=="STOP" || $sms_text=="STOP" || $sms_text=="STOPALL" || $sms_text=="END" || $sms_text=="QUIT" || $sms_text=="CANCEL" || $sms_text=="UNSUBSCRIBE")
{
  $inbound_no=(substr($inbound_data['From'],0,2)=="+1")?substr($inbound_data['From'],2):$inbound_data['From'];
  mark_contact_as_stop($inbound_no);
  $mail_body.="mark_contact_as_stop called. inbound no: ".$inbound_data['From'];
  //Save this number in contacts_stop_marked table
  $data_save['stop_marked_contact_no']=$inbound_data['From'];
  $data_save['stop_marked_against_twilio_no']=$inbound_data['To'];
  $data_save['created_at']=$global_date_time;
  $data_save['note']="Mark stopped by ".$inbound_data['From'];
  if(!save_data_into_table($data_save,"contacts_stop_marked"))
    mail("aminulsumon@gmail.com","SURVEY SMS: Stop marked not saved in to contacts_stop_marked table","SURVEY SMS: Stop marked not saved in to  contacts_stop_marked table");  
}
mail("aminulsumon@gmail.com","inbound data receive for Rob Jameson at +17177272667",$mail_body);

$reply_sms_scheduled=1;  //1=Not Send Reply SMS. 0=Send Reply SMS.
$successfully_sent_to_twilio=1;  //1=Reply Not Sent 0=Reply SMS Sent

//SMS Texts are here.(Configurable)
$intro_text="Welcome to the Collective Consciousness Measurement Project. You will be asked to rate your physical, emotional, intellectual, and spiritual power hourly with a single number during the event on a 1-9 scale. 1 means “Poor” and “9” is “amazing!” For example, 3921…. You can also add a letter to let us know where you are. ‘A’ means Amphitheater…”";

//************************* Start Survey Logic *************************
//Check whethere this is first sms from this number or not(check survey_incoming_sms table)
$sql_data="SELECT * FROM survey_incoming_sms WHERE sms_from_mobile_no='$inbound_data[From]' ORDER BY id DESC LIMIT 0,1";
$q_data=mysql_query($sql_data);
if(mysql_num_rows($q_data)==0)
{
  $reply_text=$intro_text;
  $reply_sms_scheduled=0;
}

if($reply_sms_scheduled==0)  //Send Reply SMS using Twilio
{  
    $client = new Client($global_key, $global_secret);
	$sms_to = $inbound_data['From'];
	$sms_from = $inbound_data['To'];	
	try {
	  $sms = $client->messages->create(
		// the number you'd like to send the message to
		$sms_to,
		array(
			// A Twilio phone number you purchased at twilio.com/console
			'from' => $sms_from,
			// the body of the text message you'd like to send
			'body' => $reply_text
		)
	  );
	  $successfully_sent_to_twilio=0;	  //print_r($sms);
    } catch (Exception $e) {
        $mail_body.="SMS Sending Error: " . $e->getMessage();		//print_r($sms);
		mail("aminulsumon@gmail.com","Error while send SMS using Rob Jameson",$mail_body);
    }  
}

if($successfully_sent_to_twilio==0)  //Save Reply SMS text information in database(outbound_sms table)
{
  $outbound_data['sms_sent_to_contact_no']=$sms_to;
  $outbound_data['sender_id']=$sms_from;
  $outbound_data['sms_text']=$reply_text;
  $outbound_data['sms_status']="sent";
  $outbound_data['sms_sent_date_time']=$global_date_time;
  if(!save_data_into_table($outbound_data,"outbound_sms"))
    mail("aminulsumon@gmail.com","SURVEY Data Save Error: outbound_sms table","SURVEY Data Save Error: outbound_sms table");
}
?>