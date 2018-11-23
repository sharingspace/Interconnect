<?php
require_once("bll/conn.php");
require_once("bll/f_common.php");
require_once("bll/survey_common.php");

/*
for example someone reply 7999v. what does it mean?
i understand, v stands for Vaults. which means that this data have impact in Vaults graph. but what's the meaning of 7999 ?

Please note that, i understand the locations are
V - Vaults C - Cafe E - Ceramics A - Amphitheater M - Minds Garden S - Convergence Studio L - Classroom O - Community Room

those numbers represent the vertical bars on the vaults graphic

So vaults graphic would all have those ratings from left to right at the time stamp that the user has chosen on the bottom make it the averages if there is more than one entry per location in 30 minutes
So if two people responded around the same time, it would graph the average of their responses
Like if 2 people text at 8am: 3868v and 5662v then the graph will show 4765 on the bars for the Vaults graph
Please add some text showing what time the slider is showing

*/
$allowed_loc_short=array('V', 'C', 'E', 'A', 'M', 'S', 'L', 'O');
$location_valus['V']=array('Physical'=>0,'Emotion'=>0,'Intelect'=>0, 'Spirit'=>0, 'total_reply'=>0, 'Meaning'=>'Vaults');
$location_valus['C']=array('Physical'=>0,'Emotion'=>0,'Intelect'=>0, 'Spirit'=>0, 'total_reply'=>0, 'Meaning'=>'Cafe');
$location_valus['E']=array('Physical'=>0,'Emotion'=>0,'Intelect'=>0, 'Spirit'=>0, 'total_reply'=>0, 'Meaning'=>'Ceramics');
$location_valus['A']=array('Physical'=>0,'Emotion'=>0,'Intelect'=>0, 'Spirit'=>0, 'total_reply'=>0, 'Meaning'=>'Amphitheater');
$location_valus['M']=array('Physical'=>0,'Emotion'=>0,'Intelect'=>0, 'Spirit'=>0, 'total_reply'=>0, 'Meaning'=>'Minds Garden');
$location_valus['S']=array('Physical'=>0,'Emotion'=>0,'Intelect'=>0, 'Spirit'=>0, 'total_reply'=>0, 'Meaning'=>'Convergence Studio');
$location_valus['L']=array('Physical'=>0,'Emotion'=>0,'Intelect'=>0, 'Spirit'=>0, 'total_reply'=>0, 'Meaning'=>'Classroom');
$location_valus['O']=array('Physical'=>0,'Emotion'=>0,'Intelect'=>0, 'Spirit'=>0, 'total_reply'=>0, 'Meaning'=>'Community Room');


$sql_replies="SELECT *, UPPER(SUBSTRING(sms_text,5,1)) as location FROM survey_incoming_sms WHERE CHAR_LENGTH(sms_text)=5 ORDER BY location";
$q_replies=mysql_query($sql_replies);
if(mysql_num_rows($q_replies)==0)
{
  //There are no good length replies
}
else
{
  while($row=mysql_fetch_array($q_replies))
  {
    //check correct format replies
	$reply_txt=strtoupper($row['sms_text']);
	if(is_numeric($reply_txt[0]) && is_numeric($reply_txt[1]) && is_numeric($reply_txt[2]) && is_numeric($reply_txt[3]) && !is_numeric($reply_txt[4]) && in_array($reply_txt[4],$allowed_loc_short))  //Valid reply: 7868Y
	{
	  $location_valus[$reply_txt[4]]['Physical']=$location_valus[$reply_txt[4]]['Physical']+$reply_txt[0];
	  $location_valus[$reply_txt[4]]['Emotion']=$location_valus[$reply_txt[4]]['Emotion']+$reply_txt[1];
	  $location_valus[$reply_txt[4]]['Intelect']=$location_valus[$reply_txt[4]]['Intelect']+$reply_txt[2];
	  $location_valus[$reply_txt[4]]['Spirit']=$location_valus[$reply_txt[4]]['Spirit']+$reply_txt[3];
	  $location_valus[$reply_txt[4]]['total_reply']=$location_valus[$reply_txt[4]]['total_reply']+1;
	}
	else
	  continue;
  }
}

/*echo "<pre>";
 print_r($location_valus);
echo "</pre>";*/
?>

<?php require_once("control/load_jquery_js_css.php");?>
<div class="wapper">
  <div class="contentarea" style="width:850px;">
    <?php foreach($location_valus as $loc_short=>$reply_value): ?>
     <div style="width:200px; padding-left:5px; float: left;">
	    
	   Physical: <?php echo floor($reply_value['Physical']/$reply_value['total_reply']);?></br>
	   Emotion : <?php echo floor($reply_value['Emotion']/$reply_value['total_reply']);?></br>
	   Intelect: <?php echo floor($reply_value['Intelect']/$reply_value['total_reply']);?></br>
	   Spirit  : <?php echo floor($reply_value['Spirit']/$reply_value['total_reply']);?></br>
	   Total Reply: <?php echo $reply_value['total_reply'];?>
	 </div>
	 <?php endforeach; ?>	 
  </div>
</div>
