<?

$frow=$_GET[frow];
$start=$_GET[srt];
$end=$_GET[end];
$drivid=$_GET[did];

function pplTime($stamp){
if ($stamp!=0){
$ta=getdate($stamp);
if (strlen($ta[minutes])==1){$ta[minutes]='0'.$ta[minutes];}
$display="$ta[mday] $ta[month] $ta[year] , <b>$ta[hours]".':'."$ta[minutes]</b>";
}else{$display='Currently on';}
return $display;
}

$dbc=mysql_connect('localhost','root','earth') or die(mysql_error());
mysql_select_db('planet_reservations');

$find=mysql_query("select driver_log.*, codes.code as driver from driver_log,codes where (codes.id=driver_log.driverid) and (driver_log.startTime='$start') and (driver_log.endTime='$end') and (driver_log.driverid='$drivid') ");
$data=mysql_fetch_assoc($find);

$feedback="$frow".'|';

$dispstart=pplTime($start);
$dispend=pplTime($end);

$feedback.='
<form action="update_time.php" method="post">
<table bgcolor="#CB0000" cellpadding="3" cellspacing="0">
<tr><td>Driver:</td><td>'.$data[driver].'</td></tr>';

$feedback.='<tr><td>StartTime</td><td>'.$dispstart.'</td></tr>';

$feedback.='<tr><td>New Start:</td>
<td><input type="checkbox" name="changestart">Change?  HR(24)<input type="text" size="2" maxlength="2" name="newhour" value="">:<input type="text" size="2" maxlength="2" name="newmin">mn</td></tr>';

$feedback.='<tr><td>EndTime</td><td>'.$dispend.'</td></tr>';

$feedback.='<tr><td>New End:</td><td><input type="checkbox" name="changeend">Change?  HR(24)<input type="text" size="2" maxlength="2" name="endhour">:<input type="text" size="2" maxlength="2" name="endmin">mn</td></tr>';

$feedback.='<tr><td colspan="2"><input type="radio" checked="true" onclick="javascript:showform('."'hrhelp'".')">24 Hour Helper (click box)<br>
<input type="checkbox" name="force8">Force EndTime 8hours after (New/Current) Start
</td></tr>';

$feedback.='<tr><td><input type="button" value="Close" onclick="javascript:hideform('."'fixform'".')"></td>
<td><input type="submit" value="Keep Changes" )"></td></tr></table>';

$feedback.='
<input type="hidden" name="curstart" value="'.$start.'">
<input type="hidden" name="curend" value="'.$end.'">
<input type="hidden" name="drivid" value="'.$drivid.'">';

$feedback.='</form>';

echo"$feedback";


?>
