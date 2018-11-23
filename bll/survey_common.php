<?php
//Add http:// in url
if($_SERVER["SERVER_NAME"]!="localhost")
{
  if(substr($_SERVER['HTTP_HOST'],0,3)!="www")
  {
    $url="http://www.".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("Location: $url");
  }
}
/*
  Some Common/Global Variables
*/
global $global_date_time;
global $global_key;
global $global_secret;
global $global_sms_send_from_mobile_no;
global $global_logged_admin_id;
$add_vars=array('seconds'=>0, 'minutes'=>0, 'hours'=>5, 'days'=>0, 'months'=>0, 'years'=>0); //-4 change to -5
$global_date_time=add_month_day_hour_min_with_a_date(date("Y-m-d H:i:s"), $add_vars);
//$global_logged_admin_id=$_SESSION['admin_id'];
$global_key = "AC329a73e1fc20a11162c5932d8687bb93"; //twilio api sid
$global_secret= "dda59b7c799b21daf96ed98fdee3f7f6"; //twilio api token
$global_sms_send_from_mobile_no="+17177272667";// 17177272667";

/*
  SMS API Mode (LIVE/TEST)
*/
  $global_sms_api_mode = "LIVE"; //LIVE/TEST
  $global_inbound_no = "+17177272667"; //used as sender id as well.

/*
  This function will return twilio crendtials like sid, token from twilio_account_credentials table.
*/
function twilio_account_credentials()
{
  $sql_data="SELECT * FROM twilio_account_credentials ORDER BY id DESC LIMIT 0,1";
  $q_info=mysql_query($sql_info);
  if(mysql_num_rows($q_info)>0)
    return mysql_fetch_array();
  else
    return false;
}


/*
  This function will check whether a contact no is marked stopped or not (search in contacts_stop_marked table)
  @input:
    $contact_no: a contact no
  @return:
    true: if contact already exists in contacts_stop_marked table
	false: not exists
*/
function contact_already_marked_as_stop($contact_no)
{
  $sql_info="SELECT * FROM contacts_stop_marked WHERE stop_marked_contact_no='$contact_no'";
  $q_info=mysql_query($sql_info);
  if(mysql_num_rows($q_info)>0)
    return true;
  else
    return false;
}
  
/*
  This function will return group info by Group ID
  @input:
    $group_id: id of contact_group table
  @return:
    group info if id match
	false if id doesn't match
*/
function group_info_by_id($group_id)
{
  $sql_group="SELECT groups.*, count(contacts.id) as no_of_contact FROM contact_group groups LEFT JOIN contacts ON groups.id=contacts.group_id WHERE groups.id='$group_id' GROUP BY groups.id";  
  return get_single_row($sql_group);
}

/*
  This function will return group id by Group Name for an admin user
  @input:
    $group_name: name of group
	$global_logged_admin_id: admin user id
  @return:
    id of contact_group table(group name matched)
	0 if group name doesn't match
*/
function group_id_by_name($group_name, $global_logged_admin_id)
{
  $sql_group="SELECT * FROM contact_group WHERE group_name='$group_name' AND created_by_admin_id='$global_logged_admin_id'";
  $q_group=mysql_query($sql_group);
  if(mysql_num_rows($q_group)>0)
  {
    $group_info=mysql_fetch_array($q_group);
	return $group_info['id'];
  }
  else
    return 0;
}

/*
  This function will return invalid contact info by contat_no
  @input:
    $contact_no: any contact no
  @return:
    contact info from invalid_contact_no table
	false (if contact_no doesn't match)	
*/
function invalid_contact_info_by_contact_no($contact_no)
{
  $sql_contact_info="SELECT * FROM invalid_contact_no WHERE invalid_contact_no.invalid_contact_no='$contact_no'";
  return get_single_row($sql_contact_info);
}

/*
  This function will return an array of invalid contacts contat_no
  @input:
    
  @return:
    an array of invalid contacts contat_no
*/
function invalid_contact_list($admin_id)
{
  $sql_contact_info="SELECT * FROM invalid_contact_no WHERE created_by_admin_id='$admin_id'";
  $q_contact_info=mysql_query($sql_contact_info);
  $invalid_contact=array();
  if(mysql_num_rows($q_contact_info)>0)
  while($row=mysql_fetch_array($q_contact_info))
  {
	$invalid_contact[]=$row['invalid_contact_no'];
  }
  return get_single_row($sql_contact_info);
}

/*
  This function will return contact detail info by contact no
  @input:
    $contact_no: contacts table contact_no
	$created_by_admin_id: admin id 
  @return:
    an array of contact info (if contact_no match)
	false (if contact_no doesn't match)	
*/
function get_contact_detail_by_contact_no($contact_no,$created_by_admin_id=1)
{
  $sql_contact_info="SELECT contacts.* FROM contacts WHERE contacts.contact_no='$contact_no' AND created_by_admin_id='$created_by_admin_id'";
  return get_single_row($sql_contact_info);  
}

/*
  This function will return contact detail info by id
  @input:
    $contact_id: contacts table id
  @return:
    an array of contact info (if id match)
	false (if id doesn't match)	
*/
function get_contact_detail_by_id($contact_id)
{
  $sql_contact_info="SELECT contacts.* FROM contacts WHERE contacts.id='$contact_id'";
  return get_single_row($sql_contact_info);  
}

/*
  Using www.twilio.com API
  This function will send SMS to $sms_receipent_no
  @input:
    $sms_receipent_no: Mobile number of SMS receipent
	$sender_id: Mobile number of Sender(if receiver reply then SMS will sent to this no),Numeric/Alphanumeric
	$sms_body: SMS Text.
  @return:
    an array of different information after sending SMS through nexmo.com API
	stdClass Object
  @return output[if use print_r(@return)]:
  (
	[messagecount] => 1
	[messages] => Array
	(
	  [0] => stdClass Object
		(
			[to] => 8801771240022
			[messageprice] => 0.00700000
			[status] => 0
			[clientref] => 123
			[messageid] => 0200000005843283
			[remainingbalance] => 7.42600000
			[network] => 47001
		)
	)
	[cost] => 0.007
*/
//function send_sms_using_twilio_api($sms_receipent_no, $sender_id, $sms_body, $sms_outbound_table_id, $send_test_email_to_amin=false)
/*function send_sms_using_twilio_api()
{
  return true;
  //echo $sms_receipent_no."-".$sender_id."-".$sms_body."-".$sms_outbound_table_id;
  global $global_nexmo_api_key;
  global $global_nexmo_api_secret;
  global $global_inbound_no;
  global $global_sms_send_environment;
  if($sender_id=="")
    $sender_id=$global_inbound_no;
	if($_SERVER["SERVER_NAME"]!="localhost" && $send_test_email_to_amin==true)
	  mail("aminulsumon@gmail.com","SMS Sent from bulk sms","Receipent phone no=$sms_receipent_no, Sender phone no=$sender_id, SMS Body=$sms_body, SMS serial no=$sms_outbound_table_id");
	if($sms_receipent_no=="" || $sms_body=="")
	  return false;
	$api_key=$global_nexmo_api_key;  //LIVE ACCOUNT using email: 
	$api_secret=$global_nexmo_api_secret;  //LIVE ACCOUNT using email: 
	
	$nexmo_sms = new NexmoMessage("$api_key", "$api_secret");
	
	// Step 2: Use sendText( $to, $from, $message ) method to send a message. 
	//$info = $nexmo_sms->sendText($_POST['sms_receipent_no'], 'Aminul', $_POST['sms_body'] ); //Straight forward Testing
	//$info = $nexmo_sms->sendText( '+447234567890', 'MyApp', 'Hello!' ); //Straight forward Testing
	
	//key value pair SMS Parameters: https://www.nexmo.com/documentation/#how
	$data['from']=$sender_id;//Required. Sender address could be alphanumeric (Ex: from=MyCompany20), restrictions may apply depending on the destination see our FAQs.
	$data['to']=$sms_receipent_no;//Required. Mobile number in international format and one recipient per request. Ex: to=447525856424 or to=00447525856424 when sending to UK
	$data['type']="text";	//Optional. This can be omitted text (default), unless sending a Binary (binary), WAP Push (wappush), Unicode message (unicode), vcal (vcal) or vcard (vcard).
	$data['text']= urlencode($sms_body); //Required when type='text'. Body of the text message (with a maximum length of 3200 characters), UTF-8 and URL encoded value. Ex: "Déjà vu" content will be "D%c3%a9j%c3%a0+vu"
	$data['status-report-req']="1";	//Optional. Set to 1 if you want to receive a delivery report (DLR) for this request. Make sure to configure your "Callback URL" in your "API Settings"
	$data['client-ref']="$sms_outbound_table_id";	//Optional. Include any reference string for your reference. Useful for your internal reports (40 characters max).
	//printr($data);
	if($_SERVER["SERVER_NAME"]!="localhost" && $global_sms_send_environment=="live")
	{
	  $info = $nexmo_sms->sendRequestCustomByAmin( $data ); //this function is in NexmoMessage.php class file	  
	  //echo 'Output of $info = $nexmo_sms->sendRequest ( $data )';
	  //printr($info);
	  //echo 'Output of '.$nexmo_sms->displayOverview($info);
	  return $info;
	}
	else
	{
	  return array('status'=>'0', 'messageid'=>time());
	}
	
	// Step 3: Display an overview of the message
	//echo 'Output of $nexmo_sms->displayOverview($info):';
	//return $nexmo_sms->displayOverview($info);
}*/


/*
  This function will return member info by email
  @input:
    $member_id: member_basic_info table email
  @return:
    an array of member info (if email match)
	false (if id doesn't match)	
*/
function get_member_info_by_email($member_email)
{
  $sql_member_info="SELECT member.member_email, member.member_username, member.member_password, member.is_featured_member, member.is_active, member.created_at, profile.* FROM member_basic_info member LEFT JOIN member_profile profile ON member.id=profile.member_id WHERE member.member_email='$member_email'";
  return get_single_row($sql_member_info);  
}

/*
  This function will return inbound sms detail by id
  @input:
    $inbound_tbl_id: inbound_sms_twilio table id
  @return:
    an array of inbound sms info (if id match)
	false (if id doesn't match)	
*/
function get_inbound_sms_detail_by_id($inbound_tbl_id)
{
  $sql_inbound_info="SELECT inbound.* FROM inbound_sms_twilio inbound WHERE inbound.id='$inbound_tbl_id'";
  return get_single_row($sql_inbound_info);  
}

/*
  This function will mark contact as stop. will not send any more SMS to this contact.
  @input:
    $contact_no: contact_no of contacts table
  @return:
    true (if contact_no match)
	false (if contact_no doesn't match)	
*/
function mark_contact_as_stop($contact_no)
{
  $contact_no=str_replace("-","",$contact_no);
  $contact_no_with_plus=(substr($contact_no,0,1)=="+")?$contact_no:"+".$contact_no;
  $contact_no_without_plus=str_replace("+","",$contact_no);
  $sql_update="UPDATE contacts SET is_stop='0' WHERE replace(contact_no,'-','')='$contact_no_with_plus' OR replace(contact_no,'-','')='$contact_no_without_plus' ";
  return mysql_query($sql_update);
}
?>