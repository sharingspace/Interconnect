<?php
require_once("conn.php");
//require_once("include/security.php");
require_once("../bll/f_common.php");
require_once("../bll/survey_common.php");

$search_condition="";
if(isset($_POST['btnSearch']))
{
  if($_POST['full_name']!="") $search_condition.=" AND incoming.sms_from_mobile_no LIKE '%$_POST[sms_from_mobile_no]%'";  
}

$sql_info="SELECT incoming.* FROM survey_incoming_sms incoming WHERE 1 $search_condition ORDER BY incoming.id DESC";
$msg="";
$head="All SMS";
//=====================[PAGING CODING STARTS]=====================
$l=(isset($_GET['l'])&& $_GET['l']!="")?$_GET['l']:0;
$i=(isset($_GET['i']) && $_GET['i']!="")?$_GET['i']:0;
$i=$i+$l+1;

$offset=(isset($_GET['offset']) && $_GET['offset']!="")?$_GET['offset']:0;
$k=$l+1; //for page no count   
$row_per_page=10000; //display number of row per page.
$sql_all_data=$sql_info; //Assign your sql query($sql_all_data_for_this_page).
$sql_for_this_page_data = $sql_all_data. " LIMIT $l, $row_per_page ";
$rs = mysql_query($sql_all_data);
$total_rows = mysql_num_rows($rs);
$result_for_this_page_data = mysql_query($sql_for_this_page_data);
$_GET['l']=isset($_GET['l'])?$_GET['l']+1:"1";

$this_page_name=$current_page_name; //Ex: result.php or show-result[if you use htaccess]
//Previous Page Link

$previous_page_link=($l>=$row_per_page)?$this_page_name."?l=".($l-$row_per_page):"#";

//Next Page Link

$next_page_link=(($total_rows>$row_per_page && $l==0) || (($total_rows - $l)>$row_per_page))?$this_page_name."?l=".($l+$row_per_page):"#";

//=====================[PAGING CODING END]=====================

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Survey - List of Contacts</title>
<?php require_once("load_jquery_js_css.php");?>
<script type="text/javascript">
var table_name="survey_incoming_sms";
$(document).ready(function(){ 	
var table_name="survey_incoming_sms";
  function slideout(){
    setTimeout(function(){
      $("#response").slideUp("slow", function () {
      });
    }, 2000);
  }


    $("#response").hide();
	$(function() {
	  $("#list ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&update=update'; 
			$.post("jquery_ajax_update_sorting.php?table="+table_name, order, function(theResponse){
				$("#response").html(theResponse);
				$("#response").slideDown('slow');
				slideout();
			});
		}								  
  	  });
	});

	/*$('a.sbtn_red').click(function(e) {
	  if(confirm('Are you sure want to delete?')){ 
		e.preventDefault();
		var parent = $(this).parent().parent();
       
		$.ajax({
			type: 'get',
			url: 'jquery_ajax_delete_item.php?table_name='+table_name,
			data: 'ajax=1&delete=' + parent.attr('id').replace('arrayorder_',''),
			beforeSend: function() {
				parent.animate({'backgroundColor':'#fb6c6c'},300);
			},
			success: function() {
				parent.slideUp(300,function() {
					parent.remove();
				});
			}
		});
	  }else return false;
	});*/
});
</script>
</head>
<body>
<div class="wapper">
  <?php require_once("header.php"); ?>
  <?php require_once("left_menu.php"); ?>
  <div class="contentarea">
    <h1><?php echo $head;?></h1>
    <div style="clear:both;"></div>
    <div>
      <form id="frm_send_sms" name="frm_send_sms" action="" method="post">
        <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="19%">Phone No(SMS From):</td>
            <td width="31%"><input type="text" name="sms_from_mobile_no" id="sms_from_mobile_no" /></td>
            <td width="23%"></td>
            <td width="27%">
			  <span style="padding-left:20px;">
              <input type="submit" id="btnSearch" name="btnSearch" value="Search" />
              </span>
			</td>
          </tr>          
        </table>
      </form>
    </div>
    <div style="clear:both;"></div>
    <div id="show_dealer_listing">
      <!--//**** Top Pagination *******-->
      <h3><?php echo $total_rows;?> SMS found</h3>
      <ul class="pagination fr">
        <?php $page_no = 1; ?>
        <?php if ($total_rows > $row_per_page) { $page_no = 1; //Generate Page Numbers with Link ?>
        <li><a href="<?php echo $previous_page_link;?>">Prev</a></li>
        <?php for($k=0; $k<= $total_rows-1; $k++) { ?>
        <?php if ($k == $l){ //current page no need hyperlink?>
        <li><a class="selected" href="#"><?php echo $page_no;?></a></li>
        <?php }else{ ?>
        <li><a href="?l=<?php echo $k;?>"><?php echo $page_no;?></a></li>
        <?php } ?>
        <?php $k = $k + $row_per_page - 1; $page_no = $page_no + 1; ?>
        <?php } //End of for loop } ?>
        <li><a href="<?php echo $next_page_link;?>">Next</a></li>
        <?php } //end of if ?>
      </ul>
      <table width="30%" border="0" cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <td width="10%" align="center">S/N</td>
            <td width="15%">Contact No</td>
			<td width="55%">Text</td>
			<td width="20%">Time</td>
          </tr>
        </thead>
        <?php if(mysql_num_rows($result_for_this_page_data)>0) { $counter=$_GET['l']; ?>
        <?php while ($row=mysql_fetch_array($result_for_this_page_data)) { ?>
        <tr id="arrayorder_<?php echo $row['id'];?>">
          <td align="center"><?php echo $counter++;?></td>
          <td><?php echo $row['sms_from_mobile_no'];?></td>
		  <td><?php echo $row['sms_text'];?></td>
		  <td><?php echo $row['sms_received_at'];?></td>
        </tr>
        <?php } ?>
        <?php }else{ ?>
        <tr>
          <td colspan="6" align="center">Sorry. No SMS found.</td>
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
