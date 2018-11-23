<?php
require_once("conn.php");
//require_once("include/security.php");
require_once("../bll/f_common.php");
require_once("../bll/survey_common.php");

$sql_all_sms="SELECT * FROM inbound_sms_twilio ORDER BY id DESC";
$q_all_sms=mysql_query($sql_all_sms);
$total_sms=mysql_num_rows($q_all_sms);

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
    <h3>Incoming SMS</h3>
    <table width="200" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <td width="7%" align="center">S/N</td>
          <td width="15%">SMS Received</td>
          <td width="20%">SMS From</td>
          <td width="58%" align="center">SMS Text</td>
        </tr>
      </thead>
      <?php if(mysql_num_rows($q_all_sms)>0) { $counter=1; ?>
      <?php while ($row=mysql_fetch_array($q_all_sms)) { ?>
      <tr id="arrayorder_<?php echo $counter;?>">
        <td height="20" align="center" valign="top"><?php echo $counter++;?></td>
        <td valign="top"><?php echo $row['sms_received_at'];?></td>
        <td valign="top" align="center"><?php echo $row['From'];?></td>
        <td valign="top"><?php echo $row['Body'];?></td>
      </tr>
      <?php } ?>
      <?php }else{ ?>
      <tr>
        <td colspan="4" align="center">Sorry. No Incoming SMS.</td>
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
