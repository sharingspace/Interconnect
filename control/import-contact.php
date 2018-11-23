<?php
require_once("conn.php");
require_once("../bll/f_common.php");

  //Read csv file
  $msg="";
  $successfully_saved=0;
 $skip_first_row=0;
  if (($handle = fopen("../src/new-contact-list1.csv", "r")) !== FALSE) 
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
  if(isset($data[3]) && trim($data[3])!="")
  {
	  $skip_first_row++;
	  if($skip_first_row==1)
	    continue;
	  $data_save['name']=$data[0];
	  $data_save['email']=$data[1];
	  $data_save['group_name']=$data[2];
	  $data_save['mobile_no_with_country_code']=$data[3];
	  $data_save['zip_code']=$data[4];
	  $data_save['country']=$data[5];
	  $data_save['state_province']=$data[6];
	  $data_save['customer_since']=$data[7];
	  if(save_data_into_table($data_save,"contact_list"))
		$successfully_saved++;
  }
  echo "Successfully data saved: ".$successfully_saved;
?>

