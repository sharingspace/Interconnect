<?php
require_once("bll/conn.php");
require_once("bll/f_common.php");
require_once("bll/survey_common.php");

$mail_body="SMS Received for AMIN-Survey at +17177271318 \n";
foreach($_POST as $key=>$val):
  $mail_body.="$key => $val <br />";
endforeach;

$sql_dup="SELECT * FROM aminul_inbound WHERE MessageSid='$_POST[MessageSid]'";
$q_dup=mysql_query($sql_dup);
save_data_into_table(array("php_page_name"=>"inbound-sms-test-amin.php", "log_data"=>"dup: ".$sql_dup, "created_at"=>$global_date_time),"debug_log");
if(mysql_num_rows($q_dup)>0)
{
  $log_data = "Inbound-Amin Data Duplicate Request for MessageSid = $_POST[MessageSid]";
  save_data_into_table(array("php_page_name"=>"inbound-sms-test-amin.php", "log_data"=>$log_data, "created_at"=>$global_date_time),"debug_log");
  exit();
}

save_data_into_table(array("php_page_name"=>"inbound-sms-survey-twilio.php", "log_data"=>$mail_body, "created_at"=>$global_date_time, "MessageSid"=>$_POST['MessageSid']),"aminul_inbound");



?>