<?php
$myfile = fopen("error_log", "r") or die("Unable to open file!");
echo str_replace("[","<br />[",fread($myfile,filesize("error_log")));
fclose($myfile);
?>