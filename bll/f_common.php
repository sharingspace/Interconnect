<?php
if($_SERVER["SERVER_NAME"]=="localhost")
{
  $base_client = "http://localhost/sms-bulk-maisam/";
  $base_admin = $base_client."control/";
}
else
{
  //$base_client = "http://gnyservices.com/";
  $base_client = "http://www.digitalhealth.org.ng/survey/";
  $base_admin = $base_client."control/";
}

//Spanish Character List
$sp_ch[0]="Á"; $sp_ch[1]="á"; $sp_ch[2]="É"; $sp_ch[3]="é"; $sp_ch[4]="Í"; $sp_ch[5]="í";
$sp_ch[6]="Ñ"; $sp_ch[7]="ñ"; $sp_ch[8]="Ó"; $sp_ch[9]="ó";
//Unicode Character List
$un_ch[0]="&Aacute;"; $un_ch[1]="&aacute;"; $un_ch[2]="&Eacute;"; $un_ch[3]="&eacute;"; $un_ch[4]="&Iacute;";
$un_ch[5]="&iacute;"; $un_ch[6]="&Ntilde;"; $un_ch[7]="&ntilde;"; $un_ch[8]="&Oacute;"; $un_ch[9]="&oacute;";

/*
  This function will show true/false of a boolian variable
*/
function is_it_true_or_false($var_true_false)
{
  if($var_true_false==true)
    echo "True";
  elseif($var_true_false==false)
    echo "False";
  else
    echo "Neither True or False";
}

/*
  This function will return the starting time of script
  Example of use: 
    $script_exection_start_time=script_exeution_time_start();
    bottom of page call show_execution_time($script_exection_start_time);
*/
function script_exeution_time_start()
{
  $mtime = microtime();
  $mtime = explode(" ",$mtime);
  $starttime = $mtime[1] + $mtime[0];
  return $starttime;
}
/* 
  This function will return end of script execution time
*/ 
function show_execution_time($starttime)
{
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   return "This page was created in ".$totaltime." seconds";
}

/*
  This function will assign value to session
  @example:
    assign_message_to_session($msg_class,$msg);
  @input:
    $msg_class: class of message
	$msg: message
  @return:
    true
*/
function assign_message_to_session($msg_class,$msg)
{
  $_SESSION['msg_class']=$msg_class;
  $_SESSION['msg']=$msg;
  return true;
}

/*
  This function will return session value($msg_class,$msg) and delete session value($msg_class,$msg)
  @input:
  @return:
    an array of $msg_class,$msg	
*/
function get_message_from_session()
{
  $return_value['msg_class']=(isset($_SESSION['msg_class']))?$_SESSION['msg_class']:"";
  $return_value['msg']=(isset($_SESSION['msg']))?$_SESSION['msg']:"";
  unset($_SESSION['msg_class']);
  unset($_SESSION['msg']);
  return $return_value;
}

/*
  This function will process XML Response(XMLHTTP data) and output the data as an Array.
  Only one row at a time.
  @exmple:
    $resp = curl_exec($http);
	$xml_response_data = simplexml_load_string($resp);
	foreach($xml_response_data->call as $key=>$val):
	  $call_details[]=xml2array_one_record($val);
	endforeach;
  @input:
    XML Respnose
  @return:
    an array of response data
*/
function xml2array_one_record($xml)
{
  $arr = array();
  foreach ($xml as $element)
  {
	$tag = $element->getName();
	$e = get_object_vars($element);
	if (!empty($e))
	{
	  $arr[$tag] = $element instanceof SimpleXMLElement ? xml2array_one_record($element) : $e;
	}
	else
	{
	  $arr[$tag] = trim($element);
	}
  }
  return $arr;
}

/*
  This function will return two points distance in mile
  @input:
    $point1: array $point1['lat'], $point1['long']
	$point2: array $point2['lat'], $point2['long']
  @return:
    distane in mile.
*/

function calc_distance($point1, $point2)
{
  $radius      = 3958;      // Earth's radius (miles)
  $deg_per_rad = 57.29578;  // Number of degrees/radian (for conversion)
  $distance = ($radius * pi() * sqrt(
			($point1['lat'] - $point2['lat'])
			* ($point1['lat'] - $point2['lat'])
			+ cos($point1['lat'] / $deg_per_rad)  // Convert these to
			* cos($point2['lat'] / $deg_per_rad)  // radians for cos()
			* ($point1['long'] - $point2['long'])
			* ($point1['long'] - $point2['long'])
	) / 180);
  return $distance;  // Returned using the units used for $radius.
}

/*
  @input:
   an associative array. 
	Example: $arr=array('name'=>'Aminul', 'designation'=>'Programmer', 'salary'=>'1000USD');
  @output:
   comma separated plain text of these values. 
    Example: 'name: Aminul, designation: Programmer, salary: 1000USD
*/
function display_associative_array_data_as_string($input_array)
{
  $return_str=array();
  foreach($input_array as $key=>$val):
    $return_str[]=$key.": ".$val;
  endforeach;
  return implode(", ",$return_str);
}

/*
  This function use urlencode() function and return encoded row
  @input:
    array: an array with general values
  @return:
    array: an array where every item is urlencoded.
*/

function urlencode_an_array($row)
{
  $return_array=array();
  foreach($row as $key=>$val):
    $return_array[$key]=urlencode($val);
  endforeach;
  return $return_array;
}

/*
  @input:
    $input_string: string (including spanish character)
  @output:
    return a string with converted spanish character
  Example:
    input: $input_string=Ámín;
	output: "&Aacute;m&iacute;n";
*/
function convert_spanish_str_to_unicode_string($input_string)
{
  global $sp_ch;
  global $un_ch;
  return str_replace($sp_ch,$un_ch,$input_string);
}

/*
  @input:
    $input_string: string (including spanish character)
  @output:
    return a string with converted spanish character
  Example:
    input: $input_string=Ámín;
	output: "&Aacute;m&iacute;n";
*/
function convert_unicode_str_to_spanish_string($input_string)
{
  global $sp_ch;
  global $un_ch;
  return str_replace($un_ch,$sp_ch,$input_string);
}

/*
  This function is used for upload a single file rather then image.
  @input:
    $FILES: input type file field. and name must be file in caller.
	@destination_dir: destination directory path with name
	@allowedExts: input array of allowed file extensions
	@del_old_file_name: file will delete from destination_dir
  @output:
    return $return array consists of  
	  $return['opertion']: true or false
	  $return['msg']: message.
	  $return['uploaded_file_name']: for successful upload file name.
*/
function upload_document_file($FILES, $destination_dir, $allowedExts = array("doc","docx", "pdf"), $del_old_file_name="")
{
  $return=array();
  $array = explode(".", $_FILES["file"]["name"]);
  $extension = @end($array);
  //Check file extension is allowed or not
  if(in_array($extension, $allowedExts)==false)
  {
    $return['msg']="Sorry, invalid file type";
	$return['operation']=false;
  }
  else
  {
    $target_file_name = img_name_with_timestamp($_FILES["file"]["name"]);
    if(move_uploaded_file($_FILES["file"]["tmp_name"], $destination_dir . $target_file_name))
	{
      $return['uploaded_file_name']=$target_file_name;
	  //Delete old files
	  if($del_old_file_name!="")
	  {
	    @unlink($destination_dir . $del_old_file_name);
	  }
	  $return['operation']=true;
	}
	else
    {
      $return['msg']='Sorry, file not uploaded. <br />Please check file field name=file and make sure $destination_file path is valid or writeble';
	  $return['operation']=false;
    }	
  }
  return $return;
}

/*
  This function is used for upload a single file and resize
  @input:
    $FILES: input type file field. and name must be file_image in caller.
	@destination_dir: destination directory path with name
	$img_resize=array('thumb'=>true, 'thumb_size'=>80, 'midium'=>true, 'midium_size'=>200);
	$del_file_name: file will delete from original, thumbnail & midium folder.
	@file_field_name: if file field name is different then file_image
  @output:
    return $return array consists of  
	  $return['opertion']: true or false
	  $return['msg']: message.
	  $return['uploaded_file_name']: for successful upload file name.
*/
function upload_single_file($FILES, $destination_dir, $img_resize, $del_file_name="", $file_field_name="file_image")  //make sure $_FILES[file_image]
{
  $return=array();
  $extension = @end(explode(".", $_FILES[$file_field_name]["name"]));
  $allowedExts = array("jpg", "JPG", "jpeg", "JPEG", "gif", "GIF", "png", "PNG");
  //Check file extension is allowed or not
  if(!in_array($extension, $allowedExts))
  {
    $return['msg']="Sorry Invalid File Type";
	$return['operation']=false;
  }
  else
  {
    $target_file_name = img_name_with_timestamp($_FILES[$file_field_name]["name"]);
    if(move_uploaded_file($_FILES[$file_field_name]["tmp_name"], $destination_dir."original/".$target_file_name))
	{
      $return['uploaded_file_name']=$target_file_name;
	  $source=$destination_dir."original/".$target_file_name;
	  list($width,$height)=getimagesize($source);
	  if($height>$width)
	  {
		if($img_resize['thumb']==true)
		$img_width_thumb=($width/$height)*$img_resize['thumb_size'];
		$img_width_mid=($width/$height)*$img_resize['midium_size'];
		image_resize($source,$img_width_thumb,$img_resize['thumb_size'],$destination_dir."thumbnail/".$target_file_name);
		image_resize($source,$img_width_mid,$img_resize['midium_size'],$destination_dir."midium/".$target_file_name);
	  }
	  else
	  {
		$img_height_thumb=($height/$width)*$img_resize['thumb_size']; 
		$img_height_mid=($height/$width)*$img_resize['midium_size'];
		image_resize($source,$img_resize['thumb_size'],$img_height_thumb,$destination_dir."thumbnail/".$target_file_name); 
		image_resize($source,$img_resize['midium_size'],$img_height_mid,$destination_dir."midium/".$target_file_name);
	  }
	  
	  //Delete Old Photos
	  if($del_file_name!="")
	  {
	    @unlink($destination_dir."original/".$del_file_name);
	    @unlink($destination_dir."thumbnail/".$del_file_name);
	    @unlink($destination_dir."midium/".$del_file_name);
	  }
	  $return['operation']=true;
	}
	else
    {
      $return['msg']='Sorry image not uploaded. <br />Please check file field name={$file_field_name} and make sure $destination_file path is valid';
	  $return['operation']=false;
    }	
  }
  return $return;
}

/*
  This function will replace all line breaks by @replace_by string
  @input:
    @full_text: full text(containing all <br> \n
  @output:
    replaced full_text by @replace_by
  Example:
    input: 
     @full_text="house: 25<br>road:23, sector:9 \n dhaka"
     @replace_by=" "
    output:
     house: 25 road:23, sector:9 dhaka
*/
function remove_line_breaks($full_text,$replace_by="")
{
  return str_replace(array("\r\n", "\r", "\n"),"$replace_by",$full_text);
}
/*
  Return first $word_limit word.
  @Parameter: 
    $string: any string separated by white space
  @Return/Output: First $word_limit word from $string String.
  @Return/Output: First $word_limit word from $string String.
*/
function limit_words($string, $word_limit)
{
  $words = explode(" ",$string);
  $trailing_dot=(count($words)>$word_limit)?"....":"";
  return implode(" ",array_splice($words,0,$word_limit)).$trailing_dot;
}

/*
  Return one month after a date
  @Parameter: 
    $date: any date
	$return_date_format: return date format
  @Output/Return: After one month date.
*/
function after_one_month_date($date, $return_date_format)
{
  $dateOneMonthAdded = strtotime(@date("Y-m-d", strtotime($date)) . "+1 month");
  switch($return_date_format)
  {
    case 1: $output_date = @date('l dS \o\f F Y', $dateOneMonthAdded); break;
    case 2: $output_date = @date('Y-m-d', $dateOneMonthAdded); break;
  }
  return $output_date;
}

/*
  @input:
    $datetime: format must be "YYYY-mm-dd m:h:s" Ex: 2013-12-25 21:34:54
  @return:
    formated date based on $format_no
*/
function show_formated_date($datetime,$format_no=0)
{
  if($format_no==0)
    return $datetime;
  switch($format_no)
  {
	//Thursday, February 21, 2013 08:02 AM
	case '1': $formated_date=@date("l, F d, Y h:i A", strtotime($datetime));break;
	//Thursday, February 21, 2013 08:02 AM
	case '2': $formated_date=@date("m-d-Y h:i A", strtotime($datetime));break;
	//02-24-2013, February 21, 2013 @$datetime: only date not time.
	case '3': $formated_date=@date("m-d-Y", strtotime($datetime));break;
	//2013-12-24 @$datetime: only date not time.
	case '4': $formated_date=@date("Y-m-d", strtotime($datetime));break;
	//2013-12-24 @$datetime: only date not time.
	case '5': 
		$day=date("D", strtotime($datetime));//Sun, Mon(A textual representation of a day, three letters)
		$spanish_day=spanish_weekday_name($day);
		$month_no=@date("m", strtotime($datetime)); //Numeric representation of a month, with leading zeros
		$spanish_month_name=spanish_month_name($month_no);
		$formated_date=$spanish_day.", ".$spanish_month_name.@date(" d, Y h:i A", strtotime($datetime));
		break;
	//Date: 22 Oct 2014
	case '6': $formated_date=@date("d M Y", strtotime($datetime));break;
	//Date: 12-25-2014 12:30PM
	case '7': $formated_date=@date("m-d-Y h:i A", strtotime($datetime));break;
	default: return $datetime;
  }
  return $formated_date;
}
/*
  @input:
    $start_date_time: format must be "YYYY-mm-dd m:i:s" Ex: 2013-12-25 21:34:54
    $end_date_time: format must be "YYYY-mm-dd m:i:s" Ex: 2013-12-25 23:56:54
  @return:
    no of hour difference between this 2 dates
	  Ex: 0.5, 6.25, 20.0
*/
function hour_between_two_date_time($start_date_time,$end_date_time)
{
  $start_dt_time = strtotime($start_date_time);
  $end_dt_time = strtotime($end_date_time);
  $diffHours = ($end_dt_time - $start_dt_time) / 3600;
  return $diffHours;
}

/*
  input: time in 24 hour format (eg: 11:45, 23:33, 02:00)
  return: 12 hour format time(include AM/PM with time)
*/
function add_am_or_pm_with_time($time_24_hour)
{
  $split=explode(":",$time_24_hour);
  $hour=($split[0]>12)?($split[0]%12):$split[0];
    
  if(strlen($hour)==1)
    $hour="0".$hour;
  $min=$split[1];
  $time_12_hour=$hour.":".$min;
  if($time_24_hour>"11:59")
    return $time_12_hour." PM";
  else
    return $time_12_hour." AM";
}
/*
  input: month number 1 for January, 2 for february and so
  return: Month name in spanish language.
*/
function spanish_month_name($month_no="01")
{
  switch($month_no)
  {
	case "01": $month_name="Enero"; break;
	case "02": $month_name="Febrero"; break;
	case "03": $month_name="Marzo"; break;
	case "04": $month_name="Abril"; break;
	case "05": $month_name="Mayo"; break;
	case "06": $month_name="Junio"; break;
	case "07": $month_name="Julio"; break;
	case "08": $month_name="Agosto"; break;
	case "09": $month_name="Septiembre"; break;
	case "10": $month_name="Octubre"; break;
	case "11": $month_name="Noviembre"; break;
	case "12": $month_name="Diciembre"; break;
	default: $month_name="error";
  }
  return $month_name;
}

/*
  input: day in 3 letter in English
  output: week day name in full.
*/
function spanish_weekday_name($month_name_in_english="Mon")
{
  switch($month_name_in_english)
  {
	case "Mon": $weekday_name="Lunes"; break;
	case "Tue": $weekday_name="Martes"; break;
	case "Wed": $weekday_name="Miércoles"; break;
	case "Thu": $weekday_name="Jueves"; break;
	case "Fri": $weekday_name="Viernes"; break;
	case "Sat": $weekday_name="Sábado"; break;
	case "Sun": $weekday_name="Domingo"; break;
	default: $weekday_name="error";
  }
  return $weekday_name;
}

/*
  @input: 22:34:00, 10:24:43
  @output/Return: Return formatted time(12/24 hour format) including or excluding AM/PM  
*/
function show_formated_time($time,$format_no=0)
{
  if($format_no==0)
    return $time;
  switch($format_no)
  {
	//07:00,21:45
	case '1': $formated_time=substr($time,0,5);break;
	default: return $time;
  }
  return $formated_time;
}

/*
  This function return a date after adding/subtract minutes seconds days months and years
  @Input:
	$base_date_time: base date with time (Example: 2013-03-31 17:30:00)
	$add_sec_min_day_month_year: Ex $add_vars=array('seconds'=>40, 'minutes'=>-20, 'hours'=>4, 'days'=>-20, 'months'=>3, 'years'=>5)
  @Careful About:
	seconds(not second) hours(not hour). always end with s.
  @Output:
	$return_date_time: date after adding all variables(minutes seconds days months and years)
*/
function add_month_day_hour_min_with_a_date($base_date_time, $add_sec_min_day_month_year=array())
{
  $return_date_time=$base_date_time;
  if(count($add_sec_min_day_month_year)>0)
  {
	$add_string="";
	foreach($add_sec_min_day_month_year as $key=>$value):
	  $add_string.="$value $key ";
	endforeach;
  }
  $newtime = strtotime($base_date_time . " $add_string");
  $return_date_time = @date('Y-m-d H:i:s', $newtime);
  return $return_date_time;
}


/*
  Input: $dir: Directory name get_folder_file_list_with_size("./", true);  get_folder_file_list_with_size("./dir_name/", true);  
  Output Show:
    for($i=0;$i<count($dirlist);$i++)
      echo $dirlist[$i]['name']."|<>|".$dirlist[$i]['size']."<br/>";  
*/
function get_folder_file_list_with_size($dir, $recurse=false)
{
    // array to hold return value
    $retval = array();

    // add trailing slash if missing
    if(substr($dir, -1) != "/") $dir .= "/";

    // open pointer to directory and read list of files
    $d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
    while(false !== ($entry = $d->read())) {
      // skip hidden files
      if($entry[0] == ".") continue;
      if(is_dir("$dir$entry")) {
        $retval[] = array(
          "name" => "$dir$entry/",
          "type" => filetype("$dir$entry"),
          "size" => 0,
          "lastmod" => filemtime("$dir$entry")
        );
        if($recurse && is_readable("$dir$entry/")) {
          $retval = array_merge($retval, getFileList("$dir$entry/", true));
        }
      } elseif(is_readable("$dir$entry")) {
        $retval[] = array(
          "name" => "$dir$entry",
          "type" => filetype("$dir$entry"),
          "size" => filesize("$dir$entry"),
          "lastmod" => filemtime("$dir$entry")
        );
      }
    }
    $d->close();

    return $retval;
}


function load_time_drop_down_menu($control_name,$hour_format=12)
{
  $content="<select name='$control_name' id='$control_name'>";
  $content.="<option value='00:00'>00:00 am</option>";
  $am_pm="am";
  for($i=1;$i<=12;$i++)/* 01:00AM - 12:30PM */
  {
	if($i>=12)
	  $am_pm="pm";
	$time_value=(strlen($i)==1)?"0".$i:$i;
    $content.="<option value='$time_value:00'>$time_value:00 $am_pm</option>";
    $content.="<option value='$time_value:30'>$time_value:30 $am_pm</option>";
  }
  if($hour_format<=12) /* 01:00PM - 12:30AM */
  for($i=1;$i<=12;$i++)
  {
	if($i>=12)
	  $am_pm="am";
	//$time_value=($i+12)%24;
	$time_value=($i+12)%24;
	$time_display=(strlen($i)==1)?"0".$i:$i;
    $content.="<option value='$time_value:00'>$time_display:00 $am_pm</option>";
    $content.="<option value='$time_value:30'>$time_display:30 $am_pm</option>";
  }  
  $content.="</select>";
  return $content;
}

/*
  @prerequirement:
    Database must be connected before call this function.
  @input:
    $search_keyword: keyword text
	$tbl_name: name of the table.
  @output:
    return an array of sql query
  @Example:
    $sql_array=sql_generator_keyword('bogra', "location");
	print_r($sql_array);
	[0] => select * from location where name like('%bogra%') OR district like('%bogra%') OR city like('%bogra%')
*/
function sql_generator_keyword($search_keyword, $tbl_name="")
{
    $out = "";
	$out_sql_query=array();

    $sql = "show tables";
    $rs = mysql_query($sql);
    if(mysql_num_rows($rs)>0){
        while($r = mysql_fetch_array($rs)){
			if($tbl_name!="" && $r[0]!=$tbl_name)
			  continue;
            $table = $r[0];
            $out .= $table.";";
            $sql_search = "select * from ".$table." where ";
            $sql_search_fields = Array();
            $sql2 = "SHOW COLUMNS FROM ".$table;
            $rs2 = mysql_query($sql2);
            if(mysql_num_rows($rs2) > 0){
                while($r2 = mysql_fetch_array($rs2)){
                    $colum = $r2[0];
                    $sql_search_fields[] = $colum." like('%".$search_keyword."%')";
                }
            }
            $sql_search .= implode(" OR ", $sql_search_fields);
            $rs3 = mysql_query($sql_search);//echo $sql_search;
            $out .= mysql_num_rows($rs3)."\n";
			$out_sql_query[]=$sql_search;
        }
    }

    //return $out;
	return $out_sql_query;
}

/*
  Return Full URL of Current Page.
  @output/Return: Current Page URL
*/
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
 /* [AMINUL: NOT YET TESTED BUT NEED TO TEST. MAY BE IT'S BETTER]
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        return
            ($https ? 'https://' : 'http://').
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
            ($https && $_SERVER['SERVER_PORT'] === 443 ||
            $_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
            substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
 
 */
}

/*
  This function will return only current page. 
  if url=http://localhost/mydir/project.php?id=5
  return project.php
*/
function curPageName()
{
  return basename($_SERVER['PHP_SELF']);
}

/*
  Make a proper url. Url start with http or https.
  @input: An URL with or without http
  @output: formated URL (with http
*/

  function nice_url($url)
  {
    if(!(strpos($url, "http://") === 0)
	  && !(strpos($url, "https://") === 0)) {
        $url = "http://$url";
	}
    return $url;
  }

/*
  $current_page_name is current php page name without any query string.
*/
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$current_page_name = $parts[count($parts) - 1];
if($_SERVER['QUERY_STRING']!='')
  $current_page_with_query_string=$current_page_name.'?'.$_SERVER['QUERY_STRING'];


/*
  take care all special charecters
  @input  : All POST values
  @output : SQL formated All POST values
  @parameter
    $fields : Array of POST
  @precaution: Don't use this function if you use save_data_into_table() function.
*/
  function prepare_text_for_insert($data)
  {
    foreach($data as $key=>$value):
	  //if get_magic_quotes_gpc is 1/On then mysql_real_escape_string automatically added by server
	  if (get_magic_quotes_gpc() === 1) 
	    $data[$key]=$value;
	  elseif(is_string($value))
	    $data[$key]=mysql_real_escape_string($value); //addslashes(), htmlspecialchars()
	  else
	    continue;
    endforeach;
    return $data;
  }

/*
  This function will save array data into database table.
  @input: 
    @$data: An array containing table column name and value. Ex $data=array("title"=>"test page", "amount"=>"", "content"=>"");
    @$table_name: Name of the table where data will save.
  @output
    Return true for success and false for failure.
  @precaution: Don't use prepare_text_for_insert() function if you use this function
*/
function save_data_into_table($data,$table_name,$show_query=false)
{
  //write_debug_file("amin"); //Write into debug.txt file
  $sql_insert="INSERT INTO ".$table_name;
  foreach($data as $key=>$value):
    //$value=trim($value); $key=trim($key);
    $all_key[]="`".$key."`";
    //if get_magic_quotes_gpc is 1/On then mysql_real_escape_string automatically added by server
    if (get_magic_quotes_gpc() === 1) 

	  $all_value[]="'".$value."'";
    else
	  $all_value[]=($value=="")?"''":"'".mysql_real_escape_string((string)$value)."'"; //addslashes(), htmlspecialchars()
  endforeach;//print_r($all_value);
  $sql_insert.="(".implode(", ",$all_key).") VALUES (".implode(",",$all_value).")";
  if($show_query!=false)
    echo $sql_insert;
  $_SESSION['insert_query'] = $sql_insert;
  return mysql_query($sql_insert)?true:false;
}

/*
  it's a debug helper file.
  debug.txt located at f_common.php file path.
*/
function write_debug_file($somecontent)
{
  $handle = fopen("debug.txt","w");
  fwrite($handle,$somecontent);
  fclose($handle);
}

/*
  This function will Update array data into database table.
  @input: 
    @$data: An array containing table column name and value. Ex $data=array("title"=>"test page", "amount"=>"", "content"=>"");
    @$table_name: Name of the table where data will Update.
	$condition: SQL Where condition(if any)
  @output
    Return true for successful update and false for failure.
*/
function update_data_into_table($data,$table_name,$condition="",$show_query=false)
{
  $sql_update="UPDATE ".$table_name." SET ";
  $update_str=array();
  foreach($data as $key=>$value):
    //if get_magic_quotes_gpc is 1/On then mysql_real_escape_string automatically added by server
    if (get_magic_quotes_gpc() === 1) 
	  $update_str[]="$key='".$value."'";
    else	  
	  $update_str[]="$key='".mysql_real_escape_string($value)."'"; //addslashes(), htmlspecialchars()
  endforeach;
  $sql_update.=implode(", ",$update_str);//print_r($update_str);
  if($condition!="")
    $sql_update.=" WHERE ".$condition;
  if($show_query!=false)
    echo $sql_update;
  
  return mysql_query($sql_update)?true:false;
}

/*
  take care all special charecters
  @input  : All POST values
  @output : Display formated All POST values
  @parameter
    $fields : Array from Database usually.
*/
  function prepare_text_for_display($fields)
  {
    foreach($fields as $key=>$value):
	  $formatted_array[$key]=get_clean_text($value);
	endforeach;
	return $formatted_array;
  }

//return clean text replace\(slash) because when we add text we use addslashes function
  function get_clean_text($text)
  {	
    if(!is_string($text))
	  return $text;
    $text = htmlspecialchars($text);
    $text = nl2br($text);
    $text=str_replace("\\","",$text); // remove \ from string ex \' = '
	return $text;
  }


/*
  This function will return SINGLE row value as array
  @$sql_query: Well formated SQL command. Ex: SELECT * FROM cms WHERE id=5 ORDER BY id DESC LIMIT 0,1
  Return array of data after mysql_fetch_array() or false for non empty result.
*/
function get_single_row($sql_query)
{
  $exec_query=mysql_query($sql_query);
  if(mysql_num_rows($exec_query)>0)
  {
    $row= mysql_fetch_array($exec_query);

	foreach($row as $key=>$val):
	  //$row[$key]=htmlspecialchars($val); //don't use htmlspecialchars(). if use then you get <p> in output text.
	  $row[$key]=nl2br($row[$key]);
	endforeach;
	return $row;
  }
  else
    return false;
}

/*
  This function will return mysql_query() of inputted $sql_query
  @$sql_query: Well formated SQL command. Ex: SELECT * FROM cms WHERE id=5 ORDER BY id DESC LIMIT 0,1
  Return mysql_query() 
*/
function get_multiple_rows_query($sql_query)
{
  return mysql_query(htmlspecialchars($sql_query));
  //while($row=mysql_fetch_array($query))
}

/*
  @input: array of data
  @output: <pre>+print_r(array)+</pre>
*/
function printr($array_data)
{
  echo "<pre>";
  print_r($array_data);
  echo "</pre>";
}
//make formatted text add addslashes() function
  function make_sql_format_text($text)
  {
    $text=trim($text);
    if (get_magic_quotes_gpc() === 1) 
	  $text=$text;
    else
	  $text=mysql_real_escape_string($text); //addslashes(), htmlspecialchars()
    //$text=mysql_real_escape_string($text); // add \ to string ex ' = \'
	return $text;
  }

//rename image name (contact img_name.timestamp)
  function img_name_with_timestamp($img_name)
  {
    return strtolower(substr(basename($img_name),0,strpos(basename($img_name),".")) . '_' . time() . substr(basename($img_name),strpos(basename($img_name),".")));
  }

//return a select boxec html code contain information that is saved at database table on the vase of sql
//if table not found or empty found it will return a message.
function getSelectBoxX($p_sql, $p_sel_name, $p_sel_id='', $p_default='', $p_add_link='', $p_select_blank = false, $p_extra_val=""){
	$return_val = "<small><strong>Error:</strong> SQL statement and Select box name must be passed..!</small>";
	if(strlen($p_sel_id) <= 0) $p_sel_id = $p_sel_name;
	if(strlen($p_sql) > 0 && strlen($p_sel_name) > 0){
		$return_val = "<small>Invalid SQL Statement =&gt;<br>$p_sql</small>";
		$rs = mysql_query($p_sql);
		if($rs){
			$return_val = "<small>No value found for select. Please add some record.";
			if(strlen($p_add_link) > 0) $return_val .= " <a href='" . $p_add_link . "'>Click Me</a> to add";
			$return_val .= "</small>";
			if(mysql_num_rows($rs) > 0){
				$return_val = "<select name=" . $p_sel_name . " size=1 id=" . $p_sel_id . " $p_extra_val>";
				if(strlen(trim($p_default)) <= 0 || $p_select_blank == true) $return_val .= "<Option value=\" \" selected> --&lt; Please Select &gt;-- </option>"; 
				while($rw=mysql_fetch_array($rs)){
					if(strtoupper(trim($rw[0])) == strtoupper(trim($p_default))) $selected = " selected";
					else $selected = "";
					$return_val .= "<Option value=\"" . $rw[0] . "\"" . $selected . ">" . $rw[1] . "</option>"; 
				}
				$return_val .= "</select>";
			}
		}
	}
	return $return_val;
}

//This is to create thumbnail picture
function thumb_image_create($source, $width, $height, $target){

  //$source  = '0.JPG';
  //$target  = '0_thumb.JPG'; 
  //$width   = 125;
  //$height  = 75; 
     $quality = 90; 

     $size = getimagesize($source);
     // scale evenly
    $ratio = $size[0] / $size[1];
     if ($ratio >= 1){
          $scale = $width / $size[0];
     } else {
          $scale = $height / $size[1];
     }
     // make sure its not smaller to begin with!
     if ($width >= $size[0] && $height >= $size[1]){
          $scale = 1;
     }
     $im_in = imagecreatefromjpeg ($source);
     $im_out = imagecreatetruecolor($size[0] * $scale, $size[1] * $scale);
     imagecopyresampled($im_out, $im_in, 0, 0, 0, 0, $size[0] * $scale, $size[1] * $scale, $size[0], $size[1]);
	// imagecopyresampled($im_out, $im_in, 0, 0,0,0,$width,$height,$width, $height);
     imagegif($im_out,$target, $quality);
     imagedestroy($im_out);
     imagedestroy($im_in);
}

//This is to resize or customize a picture size

function image_resize($source,$resize_width,$resize_height,$target){
set_time_limit(0); 

//$resize_width=440;
//$resize_height=330;
$perc = 60; 

//$file_mini = explode('.', $source); 
//$target = $file_mini[0]."_mini.gif"; 
$extension = @end(explode(".", $source));
switch($extension)
{
  case 'jpeg': 
  case 'jpg': $source = imagecreatefromjpeg($source); break;
  case 'png': $source = imagecreatefrompng($source); break;
  case 'gif': $source = imagecreatefromgif($source); break;
}
//$source = imagecreatefromjpeg($source);
$width = imagesx($source); 
$height = imagesy($source); 

$x = $resize_width; 
$y = $resize_height; 

//[Added by Aminul]
if($x>$width || $y>$height)
{
    $x=$width;
    $y=$height;
}//[End Added by Aminul]

$new_image = imagecreatetruecolor($x,$y); 
imagecopyresampled($new_image, $source, 0, 0, 0, 0, $x, $y, $width , $height); 

imagejpeg($new_image, $target, 90); 

imagedestroy($source); 
imagedestroy($new_image); 

}

// Get the uploaded photos dimensions
function watermark($source,$watermark_source,$new_pic){
$photo_file=$source;

$photo_size = getimagesize($photo_file); 
$photo_width = $photo_size[0]; 
$photo_height = $photo_size[1]; 

// Create an image from the uploaded jpg 
$photo_image = imagecreatefromgif($photo_file); 
             
// Turn on Alpha Blending for the uploaded jpg 
ImageAlphaBlending($photo_image, true); 
             
// Check that the uploaded photo's width is larger than the 
// requested fullsize photo width, otherwise use the original width 
/*if ($photo_width > $INFO['max_img_width']) 
{ 
        $pic_width = $INFO['max_img_width']; 
	
} 
else 
{ 
        $pic_width = $photo_width; 
} 
  */           
// Calculate the the fullsize photo height based on the width 
//$pic_height = round($photo_height / ($photo_width / $pic_width)); 

$pic_width=$photo_width; 
$pic_height=$photo_height;
// Create the new image 
$pic_img = imagecreatetruecolor($pic_width,$pic_height); 
imagecopyresized($pic_img,$photo_image,0,0,0,0,$pic_width,$pic_height,$photo_width,$photo_height); 
             
// Define the watermark png file 
//$logo_file = $INFO['server_path']."/".$INFO['gallery_dir']."/htm/watermark.png"; 
 $logo_file=$watermark_source;
// Get the logo dimensions from the file 
$logo_size = getimagesize($logo_file); 
$logo_width = $logo_size[0]; 
$logo_height = $logo_size[1]; 
          
// Create an image from the watermark png file 
$logo_image = ImageCreateFromPNG($logo_file); 
             
// Copy watermark logo image onto the photo image 
ImageCopy($pic_img, $logo_image, 0, 0, 0, 0, $logo_width, $logo_height); 

//$save_as_name='test.gif';
// Define the location of the gif file to be created 
$pic_file = $new_pic; 
       
// Create and store a jpg at the fullsize pic quality from the resized image created 
imagegif($pic_img, $pic_file, $INFO['img_quality']); 


// Clean-up any other left over images 
ImageDestroy($photo_image); 
ImageDestroy($logo_image); 
ImageDestroy($pic_img);
}

function MonthNumber($MonthName){

$MonthName=ucfirst($MonthName);
switch($MonthName)
{
case "January" : return "01";
case "February" : return "02";
case "March" : return "03";
case "April" : return "04";
case "May" : return "05";
case "June" : return "06";
case "July" : return "07";
case "Auguest" : return "08";
case "September": return "09";
case "October" : return "10";
case "November" : return "11";

case "December" : return "12";
default: return "Month Name Not Match";
}
}

//How to get date of next sunday 
$next_sunday=date('Y-m-d', strtotime("Next Sunday"));

//How to get nearest Sunday from a date
$nearest_sunday=date('Y-m-d',strtotime("Sunday", strtotime("2014-01-01")));

	//--------- Update record in banner/banner.xml  ----------
	function xml_banner_image_biju()
	{
		$sql = "SELECT * FROM banner_img
				ORDER BY sorting ASC";
		$res = mysql_query($sql);

		$xml="";

    $content='<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$content.="<gallery>"."\n";
		$content.="  <album>"."\n";
		
		while($row = mysql_fetch_array($res))
		{
      $content.='    <img src="banner/'.$row[img_name].'" />'."\n";
		}
    $content.="  </album>"."\n";
    $content.="</gallery>"."\n";
		$myFile = "../banner/images.xml";
		if(file_exists($myFile)) unlink($myFile);
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $content);

		fclose($fh); 
	}
	
	//--------- Update record in banner/banner.xml  ----------
	function xml_banner_image()
	{
		$sql = "SELECT * FROM banner_img
				ORDER BY img_order ASC";
		$res = mysql_query($sql);

		$xml="";

		//$fh = fopen("../banner/banner.xml", "w");
		//$ok = fwrite($fh, $xml);

		//fclose($fh);
		
		$xml = "";
		$xml .='<?xml version="1.0" encoding="UTF-8"?>\n<gallery>\n  <album>\n';  //"<playlist version=\"1\">\n<trackList>";
		while($row = mysql_fetch_array($res))
		{
			$xml .= '<img src="banner/'.$row['img_name'].'" />\n';
		}
		$xml .= '    </album>\n</gallery>';
		
		/*$xml .="<playlist version=\"1\">\n<trackList>";
		while($row = mysql_fetch_assoc($res))
		{
			$xml .= "\n<track>\n<title>{$row['title']}</title>\n<location>images/video_gallery/{$row['file_name']}</location>\n<annotation>{$row['short_desc']}</annotation>\n</track>";
		}
		$xml .= "\n</trackList>\n</playlist>";*/

		$fh = fopen("../banner/banner.xml", "w");
		$ok = fwrite($fh, $xml);
		fclose($fh);
		
		return $ok;
	}
?>