<?php
require_once("conn.php");
//require_once("include/security.php");
require_once("../bll/f_common.php");
require_once("../bll/survey_common.php");

$sql_last_sms_text="SELECT * FROM cron_sms_schedule ORDER BY id DESC LIMIT 0,1";
$last_sms=get_single_row($sql_last_sms_text);
$last_sms_text=$last_sms['sms_text'];

$sql_sms_list="SELECT * FROM cron_sms_schedule WHERE sms_text=\"$last_sms_text\" ORDER BY id";
$q_sms_list=mysql_query($sql_sms_list);
$total_rows=mysql_num_rows($q_sms_list);

$msg="";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Questions SMS Monitoring</title>
<?php require_once("load_jquery_js_css.php");?>
<script>
setTimeout(function(){
   window.location.reload(1);
}, 30000);
</script>
</head>
<body>
<div class="wapper">
<?php require_once("header.php"); ?>
<?php require_once("left_menu.php"); ?>
<div class="contentarea">
  <div style="clear:both;"></div>
  <div id="show_dealer_listing">     
    <!--//**** Top Pagination *******-->    
    <h3><?php echo $total_rows;?> SMS Scheduled to Send</h3>
    <strong>This page will automatically reload in every 30 second.</strong>
    <table width="200" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <td width="7%" align="center">S/N</td>
          <td width="15%">SMS To</td>
          <td width="33%">Text</td>
          <td width="20%">Status</td>
		  <td width="25%">Date & Time</td>
        </tr>
      </thead>
      <?php if(mysql_num_rows($q_sms_list)>0) { $counter=1; ?>
      <?php while ($row=mysql_fetch_array($q_sms_list)) { ?>
      <tr id="arrayorder_<?php echo $counter;?>">
        <td height="20" align="center" valign="top"><?php echo $counter++;?></td>
        <td valign="top"><?php echo $row['sms_to_contact_no'];?></td>
        <td valign="top"><?php echo $row['sms_text'];?></td>
        <td valign="top">
		  <?php if($row['sent_to_twilio']=="0") echo "Sent"; else echo "Waiting for Send";?>
        </td>
		<td valign="top"><?php echo $row['sms_request_sent_at'];?></td>
      </tr>
      <?php } ?>
      <?php }else{ ?>
      <tr>
        <td colspan="4" align="center">Sorry. No SMS is Scheduled to Send.</td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <p>&nbsp;</p>
  <p>&nbsp; </p>
</div>
</div>
<?php require_once("footer.php"); ?>
</body>
</html>
