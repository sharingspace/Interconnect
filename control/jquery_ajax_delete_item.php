<?php
// This is a sample code in case you wish to check the username from a mysql db table
require("conn.php");
require("../bll/f_common.php");
if(!isset($_GET['action']))
  $_GET['action']="";
if(isset($_GET['delete']))
{
	if($_GET['table_name']=="member_basic_info")
	{
	  //delete all RELATED records from member_profile
	  $sql_del="DELETE FROM member_profile WHERE member_id='".(int)$_GET['delete']."'";
	  mysql_query($sql_del);
	  //Now delete  member_basic_info
	  $query = 'DELETE FROM  member_basic_info WHERE id = '.(int)$_GET['delete'];
	}
	elseif($_GET['table_name']=="contact_group")
	{
	  //delete current iamge
	  $sql_contacts="SELECT * FROM contacts WHERE group_id=".(int)$_GET['delete'];
	  $q_contacts=mysql_query($sql_contacts);
	  //Now delete contacts from contacts table.
	  $query = 'DELETE FROM contacts WHERE group_id = '.(int)$_GET['delete'];
	  if(mysql_query($query))
	    $query = 'DELETE FROM '.$_GET['table_name'].' WHERE id = '.(int)$_GET['delete'];
	}
	elseif($_GET['table_name']=="banner_image")
	{
	  //delete current iamge
	  $sql_banner_img="SELECT * FROM banner_image WHERE id=".(int)$_GET['delete'];
	  $banner_info=get_single_row($sql_banner_img);
	  //Now delete banner image row
	  if($banner_info['photo_file_name']!="")
	  {	    
		//Delete File
		@unlink("../picture/banner/".$banner_info['photo_file_name']);
		@unlink("../picture/banner/thumbnail/".$banner_info['photo_file_name']);
		@unlink("../picture/banner/medium/".$banner_info['photo_file_name']);
	  }
	  $query = 'DELETE FROM '.$_GET['table_name'].' WHERE id = '.(int)$_GET['delete'];
	}
	elseif($_GET['table_name']=="booking_basic_info")
	{
	  //delete all RELATED records from booking_guest_info
	  $sql_del="DELETE FROM booking_guest_info WHERE booking_id='".(int)$_GET['delete']."'";
	  mysql_query($sql_del);
	  //Now delete booking_basic_info
	  $query = 'DELETE FROM '.$_GET['table_name'].' WHERE id = '.(int)$_GET['delete'];
	}
	elseif($_GET['table_name']=="mark_featured_speaker") //admin_user table have no id. it have adminId as autoincrement.
	{
	  $query = 'UPDATE speaker_basic_info SET is_featured_speaker='.$_GET['is_featured_speaker'].' WHERE id = '.(int)$_GET['speaker_id'];
	}
	elseif($_GET['table_name']=="trip_gallery" && $_GET['action']=='delete_file')
	{
	  $sql_file=mysql_query("SELECT * FROM trip_gallery WHERE id=".(int)$_GET['delete']);
	  if(mysql_num_rows($sql_file)>0)
	  {
	    $row=mysql_fetch_array($sql_file);
		//Delete File
		@unlink("../picture/trip/thumbnail/".$row['photo_file_name']);
		@unlink("../picture/trip/".$row['photo_file_name']);
		@unlink("../picture/trip/medium/".$row['photo_file_name']);
	    $query='DELETE FROM '.$_GET['table_name'].' WHERE id = '.(int)$_GET['delete'];
	    mysql_query($query);
	  }
	  else
	  {
	    echo "Serious error occured.";
		return false;
	  }
	}
 	elseif($_GET['table_name']=="delete_db_file")
	{
	  @unlink("../db_backup/".$_GET['delete']);
	}
	else
	  $query = 'DELETE FROM '.$_GET['table_name'].' WHERE id = '.(int)$_GET['delete'];
	
	echo $query;
	$result = mysql_query($query);
    echo "Your item has been deleted successfully.";

}

  //debug
  /*$handle = fopen("debug.txt","w");
  $somecontent = $query;//."Hello this is a test.";
  fwrite($handle,$somecontent);
  fclose($handle);*/
?>