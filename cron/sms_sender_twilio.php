<?php
$host = "localhost";
$username = "digitalh_aminul";
$password = "W))Cia1aplk6";
$db = "digitalh_survey";
@mysql_connect($host,$username,$password);
@mysql_select_db($db);

require_once("/home2/digitalh/public_html/survey/bll/f_common.php");
require_once("/home2/digitalh/public_html/survey/bll/survey_common.php");
require_once('/home2/digitalh/public_html/survey/Twilio/autoload.php'); // Loads the Twilio SMS API library
use Twilio\Rest\Client;

$sql_unsent_list="test sql query is here.";
$msg="";
$total_successful_sms_sent=0;
//Send SMS to scheduled numbers(campaign_detail table)

//SMS Will Send Per Minute: 100 (its safe)
// Cron will run in every 2 minutes so, 60 sms per shot.
$cron_run_in=2; //cron run in every 2 minute
$per_min_max_sms=30;
$limit_sms=$cron_run_in*$per_min_max_sms;

//Read and send SMS
$sql_unsent_list="SELECT * FROM cron_sms_schedule WHERE is_processed='1' AND sent_to_twilio='1' LIMIT 0,{$limit_sms}";
$q_unsent_list=mysql_query($sql_unsent_list);
if(mysql_num_rows($q_unsent_list)>0)
{
  
  //Send SMS to $contact_no using Twilio  
  $client = new Client($global_key, $global_secret);
  while($row=mysql_fetch_array($q_unsent_list))
  {
    //Mark it as processed
	$sql_update="UPDATE cron_sms_schedule SET is_processed='0' WHERE id='$row[id]'";
    if(!mysql_query($sql_update))
	  $msg.="Error while execte query: $sql_update <br />";
	
    //Send SMS
    $sms_send_from_mobile_no=$row['sent_from_twilio_no'];

    if($global_sms_api_mode=="LIVE")  //$global_sms_api_mode="LIVE/TEST";
    {
	  //>>>>>>>>>>>>>>>>> Send SMS <<<<<<<<<<<<<<<<<
		$sms_to = $row['sms_to_contact_no'];
		$sms_from = $row['sent_from_twilio_no'];
		$reply_text = $row['sms_text'];
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
	  //sleep(1);

	  $total_successful_sms_sent++;
	  $sql_update="UPDATE cron_sms_schedule SET sent_to_twilio='0', sms_request_sent_at='$global_date_time' WHERE id='$row[id]'";
	  if(!mysql_query($sql_update))
	  $msg.="Error while execte query: $sql_update <br />";
	}
    $counter_twilio_number++;
  }
  $email_body="Cron Executed Successfully at {$global_date_time} - Total SMS Sent: $total_successful_sms_sent <br />Error: $msg";
  save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>$email_body, "created_at"=>$global_date_time),"debug_log");
}
echo $email_body;
?>

