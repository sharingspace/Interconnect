<?php
require_once("bll/conn.php");
require_once("bll/f_common.php");
require_once("bll/survey_common.php");
require_once("include/function.php");


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

$add_vars=array('seconds'=>0, 'minutes'=>-30, 'hours'=>0, 'days'=>0, 'months'=>0, 'years'=>0);
$back_30_min_date_time=add_month_day_hour_min_with_a_date($global_date_time, $add_vars);
$condition="";
//Uncomment the following line to consider last 30 minutes
//$condition=" AND sms_received_at>={$back_30_min_date_time} ";

$sql_replies="SELECT *, UPPER(SUBSTRING(sms_text,5,1)) as location FROM survey_incoming_sms WHERE CHAR_LENGTH(sms_text)=5 $condition ORDER BY location";
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

<html lang="en">
   <head>
      <meta charset="utf-8">
      <link rel="shortcut icon" href="/favicon.ico">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="theme-color" content="#000000">
      <!--
         manifest.json provides metadata used when your web app is added to the
         homescreen on Android. See https://developers.google.com/web/fundamentals/engage-and-retain/web-app-manifest/
         -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
      <!--
         Notice the use of  in the tags above.
         It will be replaced with the URL of the `public` folder during the build.
         Only files inside the `public` folder can be referenced from the HTML.
         
         Unlike "/favicon.ico" or "favicon.ico", "/favicon.ico" will
         work correctly both with client-side routing and a non-root public URL.
         Learn how to configure a non-root public URL by running `npm run build`.
         -->
      <title>Real Time Collective Consciousness Map</title>
      <style type="text/css">body {
         margin: 0;
         padding: 0;
         font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen",
         "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue",
         sans-serif;
         -webkit-font-smoothing: antialiased;
         -moz-osx-font-smoothing: grayscale;
         }
         code {
         font-family: source-code-pro, Menlo, Monaco, Consolas, "Courier New",
         monospace;
         }
         #time-range p {
            font-family:"Arial", sans-serif;
            font-size:14px;
            color:#333;
         }
         .ui-slider-horizontal {
            height: 8px;
            background: #D7D7D7;
            border: 1px solid #BABABA;
            box-shadow: 0 1px 0 #FFF, 0 1px 0 #CFCFCF inset;
            clear: both;
            margin: 8px 0;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            -ms-border-radius: 6px;
            -o-border-radius: 6px;
            border-radius: 6px;
         }
         .ui-slider {
            position: relative;
            text-align: left;
         }
         .ui-slider-horizontal .ui-slider-range {
            top: -1px;
            height: 100%;
         }
         .ui-slider .ui-slider-range {
            position: absolute;
            z-index: 1;
            height: 8px;
            font-size: .7em;
            display: block;
            border: 1px solid #5BA8E1;
            box-shadow: 0 1px 0 #AAD6F6 inset;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            -khtml-border-radius: 6px;
            border-radius: 6px;
            background: #81B8F3;
            background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
            background-size: 100%;
            background-image: -webkit-gradient(linear, 50% 0, 50% 100%, color-stop(0%, #A0D4F5), color-stop(100%, #81B8F3));
            background-image: -webkit-linear-gradient(top, #A0D4F5, #81B8F3);
            background-image: -moz-linear-gradient(top, #A0D4F5, #81B8F3);
            background-image: -o-linear-gradient(top, #A0D4F5, #81B8F3);
            background-image: linear-gradient(top, #A0D4F5, #81B8F3);
         }
         .ui-slider .ui-slider-handle {
            border-radius: 50%;
            background: #F9FBFA;
            background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
            background-size: 100%;
            background-image: -webkit-gradient(linear, 50% 0, 50% 100%, color-stop(0%, #C7CED6), color-stop(100%, #F9FBFA));
            background-image: -webkit-linear-gradient(top, #C7CED6, #F9FBFA);
            background-image: -moz-linear-gradient(top, #C7CED6, #F9FBFA);
            background-image: -o-linear-gradient(top, #C7CED6, #F9FBFA);
            background-image: linear-gradient(top, #C7CED6, #F9FBFA);
            width: 22px;
            height: 22px;
            -webkit-box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.6), 0 -1px 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 0 1px rgba(255, 255, 255, 0.9) inset;
            -moz-box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.6), 0 -1px 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 0 1px rgba(255, 255, 255, 0.9) inset;
            box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.6), 0 -1px 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 0 1px rgba(255, 255, 255, 0.9) inset;
            -webkit-transition: box-shadow .3s;
            -moz-transition: box-shadow .3s;
            -o-transition: box-shadow .3s;
            transition: box-shadow .3s;
         }
         .ui-slider .ui-slider-handle {
            position: absolute;
            z-index: 2;
            width: 22px;
            height: 22px;
            cursor: default;
            border: none;
            cursor: pointer;
         }
         .ui-slider .ui-slider-handle:after {
            content:"";
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            top: 50%;
            margin-top: -4px;
            left: 50%;
            margin-left: -4px;
            background: #30A2D2;
            -webkit-box-shadow: 0 1px 1px 1px rgba(22, 73, 163, 0.7) inset, 0 1px 0 0 #FFF;
            -moz-box-shadow: 0 1px 1px 1px rgba(22, 73, 163, 0.7) inset, 0 1px 0 0 white;
            box-shadow: 0 1px 1px 1px rgba(22, 73, 163, 0.7) inset, 0 1px 0 0 #FFF;
         }
         .ui-slider-horizontal .ui-slider-handle {
            top: -.5em;
            margin-left: -.6em;
         }
         .ui-slider a:focus {
            outline:none;
         }

         #slider-range {
         width: 90%;
         margin: 0 auto;
         }
         #time-range {
         width: 400px;
         }
      </style>
      <style type="text/css">.progress-bar-vertical {
         width: 20px;
         min-height: 140px;
         display: flex;
         align-items: flex-end;
         margin-right: 20px;
         float: left;
         }
         .progress-bar-vertical .progress-bar {
         width: 100%;
         height: 0;
         transition: height 0.6s ease;
         }
      </style>
   </head>
   <body>
      <noscript>
         You need to enable JavaScript to run this app.
      </noscript>
      <div id="root">
         <div class="App">
            <div class="container mt-3">
               <h1 class="text-muted text-center">Real Time Collective Consciousness Map</h1>
               <div class="row mt-3">
               <?php foreach($location_valus as $loc_short=>$reply_value): 
                  $data = array(
                     'title' =>  $reply_value['Meaning'],
                     'Physical' => floor($reply_value['Physical']/$reply_value['total_reply'])*10, 
                     'Emotion' => floor($reply_value['Emotion']/$reply_value['total_reply'])*10, 
                     'Intelect' => floor($reply_value['Intelect']/$reply_value['total_reply'])*10, 
                     'Spirit' => floor($reply_value['Spirit']/$reply_value['total_reply'])*10, 
                  );
                  echo generate_card($data); 
                  endforeach; ?>
               </div>
            <div class="row">
               <div class="col-12">
                  <div id="time-range mb-5">
                        <p>Time Range: <span class="slider-time">9:00 AM</span> - <span class="slider-time2">5:00 PM</span></p>
                        <div class="sliders_step1">
                           <div id="slider-range"></div>
                        </div>
                     </div>
                  </div>
               </div>

               <h3 class="text-muted text-center mt-3">To Participate, Text 717 727 2667</h3>
            </div>
         </div>
      </div>
      <!--
         This HTML file is a template.
         If you open it directly in the browser, you will see an empty page.
         
         You can add webfonts, meta tags, or analytics to this file.
         The build step will place the bundled scripts into the <body> tag.
         
         To begin the development, run `npm start` or `yarn start`.
         To create a production bundle, use `npm run build` or `yarn build`.
         -->
      <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
      <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
     <script>
      $(document).ready(function(){
         $("#slider-range").slider({
         range: true,
         min: 0,
         max: 1440,
         step: 15,
         values: [540, 1020],
         slide: function (e, ui) {
            var hours1 = Math.floor(ui.values[0] / 60);
            var minutes1 = ui.values[0] - (hours1 * 60);

            if (hours1.length == 1) hours1 = '0' + hours1;
            if (minutes1.length == 1) minutes1 = '0' + minutes1;
            if (minutes1 == 0) minutes1 = '00';
            if (hours1 >= 12) {
                  if (hours1 == 12) {
                     hours1 = hours1;
                     minutes1 = minutes1 + " PM";
                  } else {
                     hours1 = hours1 - 12;
                     minutes1 = minutes1 + " PM";
                  }
            } else {
                  hours1 = hours1;
                  minutes1 = minutes1 + " AM";
            }
            if (hours1 == 0) {
                  hours1 = 12;
                  minutes1 = minutes1;
      }
      $('.slider-time').html(hours1 + ':' + minutes1);

        var hours2 = Math.floor(ui.values[1] / 60);
        var minutes2 = ui.values[1] - (hours2 * 60);

        if (hours2.length == 1) hours2 = '0' + hours2;
        if (minutes2.length == 1) minutes2 = '0' + minutes2;
        if (minutes2 == 0) minutes2 = '00';
        if (hours2 >= 12) {
            if (hours2 == 12) {
                hours2 = hours2;
                minutes2 = minutes2 + " PM";
            } else if (hours2 == 24) {
                hours2 = 11;
                minutes2 = "59 PM";
            } else {
                hours2 = hours2 - 12;
                minutes2 = minutes2 + " PM";
            }
        } else {
            hours2 = hours2;
            minutes2 = minutes2 + " AM";
        }

        $('.slider-time2').html(hours2 + ':' + minutes2);
    }
});
         })
      </script>
   </body>
</html>

