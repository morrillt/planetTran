<html>
<title>PlanetTran Driver Calendar</title>
<body>
<script language="JavaScript">
<?include('reqobj.bib');?>
</script>


<?
$dbc=mysql_connect('localhost','planet_schedul','schedule');
mysql_select_db('planet_reservations');

if (!$_GET[weekview]){$weekview=14;}else{
$weekview= ($_GET[weekview] * 7)+14;}
$stamp=(date(U)-(86400*$weekview));
$ctime=getdate($stamp);

//make midnight, on monday of this week
$offset=0;
if ($ctime[hours]>0){$offset+=($ctime[hours]*60*60);}
if ($ctime[minutes]>0){$offset+=($ctime[minutes]*60);}
if ($ctime[seconds]>0){$offset+=($ctime[seconds]);}
if ($ctime[wday]>1){$offset+=($ctime[wday]*86400);}
$stamp-=$offset;
$ctime=getdate($stamp);
$today=getdate();

?>

<style>
td{border:1px;border-style:solid;
border-color:#000000;font-family:Arial,Tahoma;
font-size:11px;vertical-align:top;}
#more{visibility:hidden;}
</style>
<table cellpadding="3" cellspacing="0"><tr bgcolor="#EEEE00">
<td rowspan="10" valign="top" align="left">
 <u>Select a driver</u><br><br>

<?
$driverlist=mysql_query("select * from codes where category='driver' and status='active' order by code asc");
while($dlist=mysql_fetch_assoc($driverlist)){
if ($_GET[driver]==$dlist[id]){echo"<b>>>";}
echo"<a href=cale.php?driver=$dlist[id]>$dlist[code]</a></b><br>";
}
?>

</td>
<td height="15">Sunday</td>
<td>Monday</td>
<td>Tuesday</td>
<td>Wednesday</td>
<td>Thurday</td>
<td>Friday</td>
<td>Saturday</td>
</tr>
<tr>
<?
$timeforeach=array(0=>'0','0','0','0','0','0','0');
$weekcol=1;
$loop=21+$ctime[wday];


while($loop>0){
$loop--;$onday=0;
if (($ctime[mday]==$today[mday]) && ($ctime[month]==$today[month])){
$color='bgcolor="#EEEEFF"';}else{$color='bgcolor="#ABCDEF"';}
echo"<td $color><u>$ctime[mday] $ctime[month] $ctime[year]</u><br><br>";

if($_GET[driver]){
$driver=$_GET[driver];
$ceil=$stamp+86400;
$q=mysql_query("select * from driver_log where (driverid='$driver') and (startTime >= '$stamp') and (startTime <= '$ceil') order by startTime asc");
$diddrive=mysql_num_rows($q);
if($diddrive>0){
 while($row=mysql_fetch_assoc($q)){
$st=getdate($row[startTime]);
if(strlen($st[minutes])==1){$st[minutes]='0'.$st[minutes];}
if($row[endTime] !=0){$et=getdate($row[endTime]);
}else{
$et=getdate(date(U));$green='<font color="#008800">';$greenoff='+</font>';
$row[endTime]=date(U);}
if(strlen($et[minutes])==1){$et[minutes]='0'.$et[minutes];}
$timeon=($row[endTime]-$row[startTime]);
$timeforeach[$st[wday]]=$timeon;
$onhours=0;$onmins=0;$onday+=$timeon;
while($timeon >3600){$timeon-=3600;$onhours+=1;}
while($timeon >60){$timeon-=60;$onmins+=1;}
$ontime="$onhours hrs, $onmins mns";

?>
<b onclick="javascript:sndReq('editform','<?=$driver?>','<?=$row[startTime]?>','0','<?=$row[endTime]?>','0')" > 
<? echo"$st[hours]:$st[minutes] - $green $et[hours]:$et[minutes] $greenoff</b>
<br>($ontime)<br>";

 }//while record
$dayhrs=0;$daymins=0;
while ($onday>3600){$onday-=3600;$dayhrs+=1;}
while ($onday>60){$onday-=60;$daymins+=1;}
$timeonday="<b>== $dayhrs hrs, $daymins mns</b>";
echo"<u>$timeonday</u>";
}//is a record
}//if driver


$stamp+=86400;
$ctime=getdate($stamp);
$weekcol++;
if($weekcol==8){
$timeforweek=0;
foreach ($timeforeach as $value){
$timeforweek+=$value;}
$whrs=0;$wmns=0;
while($timeforweek>3600){$timeforweek-=3600;$whrs++;}
while($timeforweek>60){$timeforweek-=60;$wmns++;}
$timeforweek="$whrs hours, $wmns min, $timeforweek sec";
echo"<hr>Total for week:<br><b>$green $timeforweek $greenoff</b></td>";
unset($timeforeach);$timeforeach=array(0=>'0','0','0','0','0','0','0');
$weekcol=1;echo"</tr><tr>";} else {echo"</td>";}
}


//detail and edit area
?>
<tr><td colspan=6 bgcolor="CCCC00">
<div id="editform">Select a driver, then click on a bold faced time contained within the calendar day to edit the times.</div>
</td>

<td bgcolor="EEEE00">
<?
$prevweek=$_GET[weekview]+2; 
$nextweek=$_GET[weekview]-2;
echo"<a href=cale.php?weekview=$prevweek&driver=$driver>Prior Two Weeks</a><br>
<a href=cale.php?weekview=$nextweek&driver=$driver>Next Two Weeks</a><br>
<a href=cale.php?weekview=0&driver=$driver>Current</a><br>";


?>
</td></tr>
</table>


