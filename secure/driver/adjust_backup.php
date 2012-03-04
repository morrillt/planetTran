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
if($greenflag==true){$display='<font color="#108010"><b>'.$display.'</b></font>';}
echo"$display";
}

?>
<style>
table{border-style:solid;border-color:#000000;}
td{font-family:Arial,Tahoma;font-size:14px;border-style:solid;border-color:#000000;}
</style>
<table cellspacing="0" cellpadding="3" border="2">
<tr bgcolor="#F9D447">
<td>Driver</td><td>StartTime</td><td>EndTime</td><td>Work Hours</td><td>Action?</td></tr>
<? $rownum=1;
$q=mysql_query("select driver_log.*, codes.code as driver from driver_log, codes where (codes.id = driver_log.driverid) order by startTime desc limit 30");
while($r=mysql_fetch_assoc($q)){
if ($rownum%2==0){$trbg="#93A0C0";}else{$trbg="#ABB8D8";}
?>
<tr bgcolor="<?=$trbg?>">
<td><?=$r[driver]?></td>
<td><?=pplTime($r[startTime])?></td>
<td><?=pplTime($r[endTime])?></td>
<td><?=worktime($r[startTime],$r[endTime])?></td>
<td>...</td>
</tr>
<?

$rownum++;
}

?></table>

