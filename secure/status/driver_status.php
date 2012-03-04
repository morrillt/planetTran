<?php
set_include_path('../ptRes/lib/pear');
include_once('../ptRes/lib/DBEngine.class.php');
?>
<html>
<?
$db = new DBEngine();
$return = array();
$timestamp = time();
$values = array();

$query = "SELECT cars.code as car, drivers.code as driver, clog.* from codes cars, codes drivers, car_log clog where clog.endTime = 0 and clog.driverid = drivers.id and clog.vehicleid = cars.id limit 0, 100";

$q = $db->db->prepare($query);
	
$result = $db->db->execute($q, $values);
$db->check_for_error($result);
	
if ($result->numRows() <= 0) {
?>
<h3>No drivers on duty.</h3>
<?
}

while ($rs = $result->fetchRow()) {
	$return[] = $db->cleanRow($rs);	
}

$result->free();

?>
<h3)Enter date to edit log for the following date:</h3>
<style>
tr{font-family:Arial,Tahoma;font-size:14px;}
tr.legend{font-weight:bold;font-family:Tahoma,Arial;font-size:16px;}
</style>
<table cellspacing="0" cellpadding="3">
<tr bgcolor="#F9D447" class="legend"><td>Car</td><td>Driver</td><td>Last Mileage</td><td>Last Cash</td><td>Last Tires</td></tr>
<?
$rownum=1;
//while($staff=mysql_fetch_assoc($result)) {
//echo"
foreach ($return as $staff){
if ($rownum%2==0){$trbg="#ABB8D8";}else{$trbg="93A0C0";}
?>
<tr bgcolor="<?=$trbg?>">
	<td><b><?=$staff['car']?></b></td>
	<td><?=$staff['driver']?></td>
	<td><?=$staff['mileage']?></td>
	<td><?=$staff['cash']?></td>
	<td><?=$staff['tires']?></td>
</tr>
<?
	$rownum++;
}

?>
</html>
