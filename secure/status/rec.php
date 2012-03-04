<?

$dbc=mysql_connect('localhost','planet_schedul','schedule');
mysql_select_db('planet_reservations');

$output=$_GET[elem].'|';
$driver=$_GET[driver];
$oldtime=$_GET[old];
$changeby=$_GET[changeby];
$endtime=$_GET[endtime];
$endchange=$_GET[endchange];


$find=mysql_query("select driver_log.*, codes.code as code from driver_log, codes where (codes.id=driver_log.driverid) and (driver_log.startTime='$oldtime') limit 1");
$d=mysql_fetch_assoc($find);

$output.='<form action="updatetime.php" method=post>
<input type="hidden" name="driver" value="'.$driver.'">
<input type="hidden" name="oldtime" value="'.$oldtime.'">
<input type="hidden" name="changeby" value="'.$changeby.'">
<input type="hidden" name="oldend" value="'.$d[endTime].'">
<input type="hidden" name="endchange" value="'.$endchange.'">

Driver: '.$d[code].'</br>
<table><tr><td>';

$st=getdate($oldtime);
if(strlen($st[minutes])==1){$st[minutes]='0'.$st[minutes];}
$output.='Start Time: '." $st[month] $st[mday] , $st[hours]:$st[minutes] ($oldtime)<br>";

$newtime=($oldtime + $changeby);
$nt=getdate($newtime);
if(strlen($nt[minutes])==1){$nt[minutes]='0'.$nt[minutes];}
if ($newtime==$oldtime){$flag="(will not change)";}else{$flag='(<font color="red">WILL CHANGE</font>)';}

$output.="Change To: $nt[month] $nt[mday] , $nt[hours]:$nt[minutes] $flag<br>";

$upminute=$changeby+60;$eupmin=$endchange+60;
$downminute=$changeby-60;$edownmin=$endchange-60;
$uphour=$changeby+3600;$euphour=$endchange+3600;
$downhour=$changeby-3600;$edownhour=$endchange-3600;
$uphalf=$changeby+1800;$euphalf=$endchange+1800;
$downhalf=$changeby-1800;$edownhalf=$endchange-1800;
$match=(0-$oldtime)+($endtime+$endchange);
$output.='<div style="color:#0000EE;font-weight:bold;">
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$upminute','$endtime','$endchange')".'">Add 1 Minute</b> - 
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$uphalf','$endtime','$endchange')".'">Add 30 Minutes</b> -
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$uphour','$endtime','$endchange')".'">Add 1 Hour</b> <br>
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$downminute','$endtime','$endchange')".'">Sub 1 Minute</b> - 
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$downhalf','$endtime','$endchange')".'">Sub 30 Minutes</b> -
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$downhour','$endtime','$endchange')".'">Sub 1 Hour</b> <br>
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','0','$endtime','$endchange')".'">Reset</b> -
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$match','$endtime','endchange')".'">Match New End Time </b>
</div></td><td>';

$et=getdate($endtime);
if(strlen($et[minutes])==1){$et[minutes]='0'.$et[minutes];}
$output.="End Time: $et[month] $et[mday] , $et[hours]:$et[minutes] ($endtime)<br>";

$eight=(0 - $endtime)+($oldtime+$changeby)+(3600*8)+1;
$newend=($endtime + $endchange);
$ne=getdate($newend);
if (strlen($ne[minutes])==1){$ne[minutes]='0'.$ne[minutes];}
if ($newend==$endtime){$eflag="(will not change)";}
else{$eflag='(<font color="red">WILL CHANGE</font>)';}

$output.="Change To: $ne[month] $ne[mday] , $ne[hours]:$ne[minutes] $eflag<br>";

$output.='<div style="color:#0000EE;font-weight:bold;">
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$changeby','$endtime','$eupmin')".'">Add 1 Minute</b> -
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$changeby','$endtime','$euphalf')".'">Add 30 Minutes</b> - 
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$changeby','$endtime','$euphour')".'">Add 1 Hour </b><br>
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$changeby','$endtime','$edownmin')".'">Sub 1 Minute </b>- 
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$changeby','$endtime','$edownhalf')".'">Sub 30 Minutes </b>-
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$changeby','$endtime','$edownhour')".'">Sub 1 Hour </b><br>
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$changeby','$endtime','0')".'">Reset</b> - 
<b onclick="javascript:sndReq'."('editform','$driver','$oldtime','$changeby','$endtime','$eight')".'">(Eight Hours from New Start)</b><br>
';


$oldtimein=($endtime-$oldtime);
$newtimein=(($endtime+$endchange)-($oldtime+$changeby));
$oth=0;$otm=0;$nth=0;$ntm=0;
while($oldtimein>3600){$oldtimein-=3600;$oth++;}
while($oldtimein>60){$oldtimein-=60;$otm++;}
while($newtimein>3600){$newtimein-=3600;$nth++;}
while($newtimein>60){$newtimein-=60;$ntm++;}
$output.='<td>
<u>Times in</u><br>
Current:<br>'."
<b>$oth hrs, $otm mins</b><br>
ChangesTo:<br>
<b>$nth hrs, $ntm mins</b>
</td>";

$output.='</td></tr></table>
<input type="submit" value="Apply Changes">';



echo"$output";

?>
