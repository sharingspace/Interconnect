<?php
include("../bll/conn.php");
include("../bll/f_common.php");
include("../bll/survey_common.php");
$phone_number = urlencode($_GET['phone_number']);
$rss_data.='<?xml version="1.0" encoding="UTF-8" ?>'."\n";
$rss_data.='<rss version="2.0">'."\n";
$rss_data.='<sms-response>'."\n";
$rss_data.='  <title>SMS from '.$phone_number.'</title>'."\n";
$rss_data.='  <link>http://digitalhealth.org.ng/survey/rss/rss-against-contact-no.php?phone_number='.$phone_number.'</link>'."\n";
$rss_data.='  <description>All SMS Response from '.$phone_number.' Number</description>'."\n";

$sms_from_mobile_no=$phone_number;

$sql_data="SELECT * FROM survey_incoming_sms WHERE sms_from_mobile_no='$sms_from_mobile_no' ORDER BY id DESC";
$q_data=mysql_query($sql_data);
if(mysql_num_rows($q_data)>0)
{
$rss_data.='  <total-reply>Total Reply='.mysql_num_rows($q_data).'</total-reply>'."\n";
  while($row=mysql_fetch_array($q_data))
  {
	$rss_data.='  <item>'."\n";
	$rss_data.='    <title>'.$row['sms_from_mobile_no'].'</title>'."\n";
	$rss_data.='    <description>'.$row['sms_text'].'</description>'."\n";
	$rss_data.='	<time>'.$row['sms_received_at'].'</time>'."\n";
	$rss_data.='  </item>'."\n";
  }
}
$rss_data.='</sms-response>'."\n";
$rss_data.='</rss>'."\n";


echo $rss_data;
//echo json_encode($rss_data);


?>