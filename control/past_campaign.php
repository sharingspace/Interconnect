<?php
require_once("conn.php");
//require_once("include/security.php");
require_once("../bll/f_common.php");
require_once("../bll/survey_common.php");

$sql_past_campaign="SELECT count(cron.sms_text)as total_sms, cron.sms_request_sent_at, cron.sms_text FROM cron_sms_schedule cron GROUP BY cron.sms_text ORDER BY cron.sms_request_sent_at DESC";
$q_past_campaign=mysql_query($sql_past_campaign);
$total_campaign=mysql_num_rows($q_past_campaign);

$msg="";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SMS Monitoring</title>
<?php require_once("load_jquery_js_css.php");?>
</head>
<body>
<div class="wapper">
<?php require_once("header.php"); ?>
<?php require_once("left_menu.php"); ?>
<div class="contentarea">
  <div style="clear:both;"></div>
  <div id="show_dealer_listing">     
    <!--//**** Top Pagination *******-->    
    <h3>Total Campaign: <?php echo $total_campaign;?></h3>
    <table width="200" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <td width="7%" align="center">S/N</td>
          <td width="21%">Date</td>
          <td width="54%">SMS Text</td>
          <td width="18%" align="center">SMS Sent</td>
        </tr>
      </thead>
      <?php if(mysql_num_rows($q_past_campaign)>0) { $counter=1; ?>
      <?php while ($row=mysql_fetch_array($q_past_campaign)) { ?>
      <?php $sql_tot_sms_sent="SELECT COUNT(*) AS total_sms_sent FROM cron_sms_schedule WHERE is_processed='0' AND sent_to_twilio='0' AND sms_text=\"$row[sms_text]\"";?>
      <?php $tot_sms_sent=mysql_fetch_array(mysql_query($sql_tot_sms_sent));?>
      <tr id="arrayorder_<?php echo $counter;?>">
        <td height="20" align="center" valign="top"><?php echo $counter++;?></td>
        <td valign="top"><?php echo $row['sms_request_sent_at'];?></td>
        <td valign="top"><?php echo $row['sms_text'];?></td>
        <td valign="top" align="center"><?php echo $tot_sms_sent['total_sms_sent'];?></td>
      </tr>
      <?php } ?>
      <?php }else{ ?>
      <tr>
        <td colspan="4" align="center">Sorry. No Campaign.</td>
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
