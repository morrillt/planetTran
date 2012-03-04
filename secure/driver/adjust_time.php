<?

$dbc=mysql_connect('localhost','root','earth');
mysql_select_db('planet_reservations');

function pplTime($stamp){
if ($stamp!=0){
$ta=getdate($stamp);
if(strlen($ta[minutes])==1){$ta[minutes]='0'.$ta[minutes];}
$display="$ta[mday] $ta[month] $ta[year] , $ta[hours]".':'.$ta[minutes];
              }else{
$display='Currently on';}
echo"$display";
}

function worktime($in, $out){
if ($out ==0){$out=date(U);$greenflag=true;}
$timeon=$out-$in;$hrs=0;$mns=0;
while($timeon > 3600){$timeon-=3600;$hrs++;}
while($timeon > 60){$timeon-=60;$mns++;}
if (strlen($mns)==1){$mns='0'."$mns";}
if (strlen($timeon)==1){$timeon='0'."$timeon";}
$display="$hrs:$mns:$timeon";
if($greenflag==true){$display='<font color="#107010"><b>'.$display.'+</b></font>';}
echo"$display";
}
if(!$_GET[limitOffset]){$limitOffset=0;}
else{$limitOffset=$_GET[limitOffset];}
if ($limitOffset<0){$limitOffset=0;}

?>
<script language="javascript">
function showform(did){
document.getElementById(did).style.visibility = "visible";}
function hideform(did){
document.getElementById(did).style.visibility = "hidden";}
<?include('adjustreqobj.bib');?>
</script>
<style>
table{border-style:solid;border-color:#000000;}
td{font-family:Arial,Tahoma;font-size:12px;border-style:solid;border-color:#000000;}
#fixform{position:absolute;top:65px;left:65px;visibility:hidden;}
#hrhelp{position:absolute;top:75px;left:100px;visibility:hidden;}
</style>

<table cellspacing="0" cellpadding="3" border="2">
<tr bgcolor="#F9D447">
<td>Driver</td><td>StartTime</td><td>EndTime</td><td>Work Hours</td><td>Action?</td></tr>
<? $rownum=1;
$q=mysql_query("select driver_log.*, codes.code as driver from driver_log, codes where (codes.id = driver_log.driverid) order by startTime desc limit $limitOffset, 25");
while($r=mysql_fetch_assoc($q)){
if ($rownum%2==0){$trbg="#93A0C0";}else{$trbg="#ABB8D8";}
?>
<tr bgcolor="<?=$trbg?>">
<td><?=$r[driver]?></td>
<td><?=pplTime($r[startTime])?></td>
<td><?=pplTime($r[endTime])?></td>
<td><?=worktime($r[startTime],$r[endTime])?></td>
<td><a href=# onclick="javascript:showform('fixform');sndReq('fixform','<?=$r[driverid]?>','<?=$r[startTime]?>','<?=$r[endTime]?>');">show</a></td>
</tr>
<?

$rownum++;
}

?></table>View Records:
<?
$sets[nxflor]=$limitOffset+25;
$sets[nxceil]=$sets[nxflor]+25;
$sets[pvflor]=$limitOffset-25;
$sets[pvceil]=$sets[pvflor]+25;
$sets[myceil]=$limitOffset+25;
if ($limitOffset==0){
echo"<b>0-25</b> | <a href=adjust_time.php?limitOffset=$sets[nxflor]>$sets[nxflor] - $sets[nxceil]</a>";
  } else {
echo"<a href=adjust_time.php?limitOffset=$sets[pvflor]>$sets[pvflor] - $sets[pvceil]</a> | <b>$limitOffset - $sets[myceil]</b> | <a href=adjust_time.php?limitOffset=$sets[nxflor]>$sets[nxflor] - $sets[nxceil]</a>";}
?>

<div id="fixform">
The Way is a void.
Used but never filled.
         -Lao Tzu
</div>

<div id="hrhelp" onclick="javascript:hideform('hrhelp')">
<table bgcolor="#ABCDEF" cellpadding="2" cellspacing="0">
<tr><td>1 pm</td><td>13:00</td></tr>
<tr><td>2 pm</td><td>14:00</td></tr>
<tr><td>3 pm</td><td>15:00</td></tr>
<tr><td>4 pm</td><td>16:00</td></tr>
<tr><td>5 pm</td><td>17:00</td></tr>
<tr><td>6 pm</td><td>18:00</td></tr>
<tr><td>7 pm</td><td>19:00</td></tr>
<tr><td>8 pm</td><td>20:00</td></tr>
<tr><td>9 pm</td><td>21:00</td></tr>
<tr><td>10pm</td><td>22:00</td></tr>
<tr><td>11pm</td><td>23:00</td></tr>
<tr><td>12am</td><td>00:00</td></tr>
<tr><td colspan=2>Click to close</td></tr>
</table></div>

