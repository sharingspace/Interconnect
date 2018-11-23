<?php
require_once("conn.php");
require_once("../bll/f_common.php");
require_once("include/security.php");
require_once("../bll/survey_common.php");

$head="Upload Contact CSV File";
$button="form_data_insert";
$lbl=" Upload ";
$msg="";
$msg_class="success"; //success, error, warn

//checking Inserting button Clicked or not
if(isset($_POST['btnSend']))
{
    if($_FILES["file_image"]["name"]!="")
	{
		  $extension = @end(explode(".", $_FILES["file_image"]["name"]));
		  $allowedExts = array("csv");
		  //Check file extension is allowed or not
		  if(!in_array($extension, $allowedExts))
			$msg.="Sorry Invalid File Type. <br />Your file type is <strong>{$extension}</strong>";
		  else
		  {
			 $target_file_name = img_name_with_timestamp($_FILES["file_image"]["name"]);
			 move_uploaded_file($_FILES["file_image"]["tmp_name"], "../csv-contact-files/" . $target_file_name);
			 //echo "successfully uploaded";
			 $msg.="CSV File successfully uploaded.<br />";
			 $msg_class="success"; //success, error, warn
		  }
	}
	else
	{
	   $msg_class="error"; //success, error, warn
	   $msg.="Please select a csv file";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SMS</title>
<?php require_once("load_jquery_js_css.php");?>
<script type="text/javascript">
$(document).ready(function(){
  $("#frm_admin").validate();
  
  $('#sms_text1').keyup(function() {
	var tot_char, char_left;
	tot_char=this.value.replace(/{.*}/g, '').length;
	char_left = 160-tot_char;
    $('#span_char_count1').text( char_left );
  });
});
</script>
</head>
<body>
<!-- for date time picker this should go after your </body> -->
<div class="wapper">
  <?php require_once("header.php"); ?>
  <?php require_once("left_menu.php"); ?>
  <div class="contentarea">
    <h1><?php echo $head;?></h1>
	<?php if($msg!=""){ ?>
	  <div class="<?php echo $msg_class;?>">
	    <?php echo $msg;?>
	  </div>
    <?php } ?>
    
    <p>&nbsp;</p>
    <form name="frm_admin" id="frm_admin" method="post" action="<?php echo $current_page_name;?>" enctype="multipart/form-data" class="fields">
      <p>
        <label for="Label one">CSV File:</label><br />
		<br /><br />

        <input type="file" name="file_image" />
        <span class="iconpos"><i class="fa fa-pencil fa-lg"></i></span> </p>
      <p>      
	    CSV File Format: 1 columns.  Phone number
        <br />
      </p>
	  <br />
      <p>
        <input type="submit" name="btnSend" id="btnSend" value="<?php echo $lbl;?>" class="subtn" />
      </p>
    </form>
  </div>
</div>
<?php require_once("footer.php"); ?>
</body>
</html>