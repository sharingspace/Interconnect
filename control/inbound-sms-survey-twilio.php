<?php
require_once("bll/conn.php");
require_once("bll/f_common.php");
require_once("bll/survey_common.php");
// Use the REST API Client to make requests to the Twilio REST API
require 'Twilio/autoload.php';
use Twilio\Rest\Client;

//check for duplicate http post by twilio(for same sms)
if(!isset($_POST['SmsMessageSid']) || $_POST['SmsMessageSid']=="")
{
  save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>"SmsMessageSid is blank.", "created_at"=>$global_date_time),"debug_log");
  exit();
}	

$sql_dup="SELECT * FROM inbound_sms_twilio WHERE MessageSid='$_POST[MessageSid]'";
$q_dup=mysql_query($sql_dup);
save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>"dup: ".$sql_dup, "created_at"=>$global_date_time),"debug_log");
if(mysql_num_rows($q_dup)>0)
{
  $log_data = "SURVEY Data Duplicate Request for MessageSid = $_POST[MessageSid]";
  save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>$log_data, "created_at"=>$global_date_time),"debug_log");
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

$mail_body.="Insert Query: ".$_SESSION['insert_query']."<br />";
//$log_data = "inbound data receive for Rob Jameson at +17177272667 \n mail_body=$mail_body";
//save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>$log_data, "created_at"=>$global_date_time),"debug_log");
//mail("aminulsumon@gmail.com","inbound data receive for Rob Jameson at +17177272667",$mail_body);

$reply_sms_scheduled=1;  //1=Not Send Reply SMS. 0=Send Reply SMS.
$successfully_sent_to_twilio=1;  //1=Reply Not Sent 0=Reply SMS Sent

//SMS Texts are here.(Configurable)
$intro_text="Welcome to the Collective Consciousness Measurement Project. You will be asked to rate your physical, emotional, intellectual, and spiritual power hourly with a single number during the event on a 1-9 scale. 1 means 'Poor' and '9' is 'amazing!' For example, 3921…. You can also add a letter to let us know where you are. 'A' means Amphitheater…'";
$help_text = "This is an experiment to measure the coherence at the event... To learn more visit arco.life.";

//************************* Start Survey Logic *************************
if(strtoupper($inbound_data['Body'])=="HELPS")
{
  $reply_text=$help_text;
  $reply_sms_scheduled=0;
  save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>"HELP reply_sms_scheduled=0 set", "created_at"=>$global_date_time),"debug_log");
}
	
//Checks if this phone number is in the database. If not, it sends Intro Text .(check contact_list table)
if($reply_sms_scheduled=="1")
{
  $sql_data="SELECT * FROM contact_list WHERE mobile_no_with_country_code='$inbound_data[From]' LIMIT 0,1";
  //save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>"sql_data = ".$sql_data, "created_at"=>$global_date_time),"debug_log");
  $q_data=mysql_query($sql_data);
  if(mysql_num_rows($q_data)==0)
  {
    $reply_text=$intro_text;
    $reply_sms_scheduled=0;
	//Save this contact no into contact_list table.
	save_data_into_table(array("mobile_no_with_country_code"=>$inbound_data['From'], "created_at"=>$global_date_time),"contact_list");	  
  }
}

//Save incoming SMS into inbound_sms_twilio table.
save_data_into_table($inbound_data,"inbound_sms_twilio");
$sql_query="Insert Query: ".$_SESSION['insert_query']."<br />";
save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>"insert data into inbound_sms_twilio table query = ".$sql_query, "created_at"=>$global_date_time),"debug_log");

//save data into survey_incoming_sms table
$data_incoming['sms_from_mobile_no']=$inbound_data['From'];
$data_incoming['sms_to_twilio_no']=$inbound_data['To'];
$data_incoming['sms_text']=$inbound_data['Body'];
$data_incoming['sms_received_at']=$global_date_time;
save_data_into_table($data_incoming,"survey_incoming_sms");

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
		$log_data = "Error while send SMS using Rob Jameson \n mail_body=$mail_body";
		save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>$log_data, "created_at"=>$global_date_time),"debug_log");		
		//mail("aminulsumon@gmail.com","Error while send SMS using Rob Jameson",$mail_body);
    }  
}

if($successfully_sent_to_twilio=="0")  //Save Reply SMS text information in database(outbound_sms table)
{
  $outbound_data['sms_sent_to_contact_no']=$sms_to;
  $outbound_data['sender_id']=$sms_from;
  $outbound_data['sms_text']=$reply_text;
  $outbound_data['sms_status']="sent";
  $outbound_data['sms_sent_date_time']=$global_date_time;
  if(!save_data_into_table($outbound_data,"outbound_sms"))
	save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>"SURVEY Data Save Error: outbound_sms table. Insert Query: ".$_SESSION['insert_query']."<br />", "created_at"=>$global_date_time),"debug_log");
    //mail("aminulsumon@gmail.com","SURVEY Data Save Error: outbound_sms table","SURVEY Data Save Error: outbound_sms table");
}
?>
