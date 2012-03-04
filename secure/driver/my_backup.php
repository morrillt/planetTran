<?php
set_include_path("../ptRes/lib/pear");
include_once('../ptRes/lib/DBEngine.class.php');

?>
<html>



<script>
function checkCI(dform){
var msg='';

if (document.getElementById("carId").value==0){msg+="No car selected<br>";}
if (document.getElementById("mileage").value==''){msg+="No mileage posted<br>";}
if (document.getElementById("cash").value==''){msg+="No cash posted<br>";}
if (document.getElementById("tires").value==''){msg+="No Tire posted";}
if (isNaN(document.getElementById("mileage").value)){msg+="Miles not numeric<br>";}
if (isNaN(document.getElementById("cash").value)){msg+="Cash not numeric<br>";}

if (msg){document.getElementById("alerts").innerHTML=msg;}
  else {

document.getElementById("dlog").submit();}


}




</script>

<form action="driver_log.php" method="post" name="driverlog" id="dlog" >
<font color="red"><b><div id=alerts></div></b></font>

<?
$cars = array();
$cars[5] = "PT003";
$cars[6] = "PT004";
$cars[7] = "PT006";
$cars[8] = "PT007";
$cars[43] = "PT008";
$cars[65] = "PT009";
$cars[66] = "PT010";
$cars[83] = "PT011";
$cars[84] = "PT012";
$cars[85] = "PT013";

$username = $_POST['username'];
$password = $_POST['password'];
$action = $_POST['action'];
$driverId = $_POST['driverId'];
$carId = $_POST['carId'];

$dateformat = "D M j, Y, G:i:s T";
$timestamp = time();
$date = date($dateformat, $timestamp);
$authenticated = false;

$actionmessage = $_POST['actionmessage'];
if($action == '') {
	show_login();
} else {
	$db = new DBEngine();
if ($action == 'Login') {
	//authenticate uname and password against table dinfo
	$driverInfo = array();
	$driverInfo = authenticate($db, $username, $password);
	if(sizeof($driverInfo) == 0) {
		$actionmessage = $username . " is not a valid user.";
	} elseif(strcmp($driverInfo['password'], $password) != 0) {
		$actionmessage = $username . ", the password is not correct.  Try again.";
	} else {
		
		$driverId = $driverInfo['id'];	
		$driverName = $driverInfo['code'];	
		$authenticated = true;
		$actionmessage = $driverName . ", you have successfully logged in. "; 
	}
} elseif($action == 'ClockIn') {










		$authenticated = true;
	$actionmessage = clock_in($db, $timestamp, $driverId, $carId);
	$_POST['clockedIn'] = 1;

} elseif($action == 'ClockOut') {
		$authenticated = true;
	$actionmessage = clock_out($db, $driverId, $timestamp);
	$_POST['clockedIn'] = 0;
} elseif($action == 'ChangeCar') {
		$authenticated = true;
	$actionmessage = check_car($db, $driverId, $carId, $timestamp);
	$_POST['clockedIn'] = 1;
} elseif($action == '') {
	show_login();
}

show_action_status($date, $username, $driverId, $carId, $actionmessage);

//check driver status driver_log table
//(ie, if there is a row that startTime exists but endTime doesn't)

//if clocked out, then display clock in 
$clockedInTime = get_clocked_in_time($db, $driverId, $timestamp);
if($clockedInTime) {
	//if clocked in, then display clock out
	show_clockout_button();
	show_clocked_in($username, $clockedInTime);
	//if in car display which car
} else { 
	if($authenticated) {
		//not clocked in, so show clockin button
		
?>
Clock in with
<?
		show_clockin_button();
	} else {
		show_login();
	}
}
}

function show_action_status($date, $username, $driverId, $carId, $actionmessage) {
?>
<input type="hidden" name="driverId" value="<?= $driverId ?>">
<input type="hidden" name="username" value="<?= $username ?>">

<h3><?= $date ?></h3>
<h3><?= $actionmessage ?></h3>
<?
}

function get_clocked_in_time($db, $driverId, $timestamp) {
	$dvalues = array($driverId);
	$return = array();
	
	$driverselq = "SELECT startTime from driver_log where driverId = ? and endTime = 0"; 
	$dq = $db->db->prepare($driverselq);
	$dresult = $db->db->execute($dq, $dvalues);
	$db->check_for_error($dresult);
	
	if ($dresult->numRows() <= 0) {
		return 0;
	}
	
	while ($rs = $dresult->fetchRow()) {
		$return[] = $db->cleanRow($rs);	
	}
	
	$dresult->free();
	return 1; 
}

function show_clockin_button() {
show_car_select();
?>
<br>
<input type="hidden" name="ClockedIn" value="1">
<input id="InButton" type="button"  value="ClockIn" onclick="javascript:checkCI('driverlog')"  > 
<input type="hidden" name="action" value="ClockIn">
<?
$_SESSION['clockedIn'] = 1;
}

function show_car_select() {
?>
car: <select name="carId" id="carId">
<option value="0">Select a car</option>
<option value="5">PT003</option>
<option value="6">PT004</option>
<option value="7">PT006</option>
<option value="8">PT007</option>
<option value="43">PT008</option>
<option value="65">PT009</option>
<option value="66">PT010</option>
<option value="83">PT011</option>
<option value="84">PT012</option>
<option value="85">PT013</option>
</select>
<br>Mileage:<input type="text" name="mileage" value="" size="6" maxlength="6" id="mileage">
Cash:<input type="text" name="cash" value="" size="3" maxlength="3" id="cash">
<br>Tires:<input type="text" name="tires" value="df=40dr=40pf=40pr=40" size="20" maxlength="20" id="tires"><br>
<br>Damage:<textarea name="damage" value="" rows=2 cols=20></textarea>
<br>Opitems:<textarea name="opitems" value="" rows=2 cols=20></textarea>
<?
}

function show_clockout_button() {
?>
<input type="hidden" name="ClockedIn" value="0">
<input name="action" type="submit" value="ClockOut">
<?
}

function show_clocked_in($username, $clockedInTime) {
?>
<input type="hidden" name="ClockedIn" value="1">
or: <br>Change to
<?
show_car_select();
?>
<br><input name="action" type="submit" value="ChangeCar">
<?
}

function clock_out($db, $driverId, $timestamp) {
	$dvalues = array($timestamp, $driverId, $timestamp);
	$cvalues = array($timestamp, $driverId, $timestamp);	
	$driverupq = "UPDATE driver_log SET endTime = ? where driverId = ? and startTime < ? and endTime = 0"; 
	$carupq = "UPDATE car_log SET endTime = ? where driverId = ? and endTime = 0 and startTime < ?"; 
	$dq = $db->db->prepare($driverupq);
	$dresult = $db->db->execute($dq, $dvalues);
	$db->check_for_error($dresult);
	$cq = $db->db->prepare($carupq);
	$cresult = $db->db->execute($cq, $cvalues);
	$db->check_for_error($cresult);
	return "you have successfully clocked out.";

}

function clock_in($db, $timestamp, $driverId, $carId) {
?>
<?
	$dvalues = array($driverId, $timestamp);
	$cvalues = array($carId, $driverId, $timestamp, $_POST['mileage'], $_POST['tires'], $_POST['damage'], $_POST['opitems'], $_POST['cash']);	
	$driverinq = "INSERT into driver_log (driverid, startTime) VALUES (?, ?)"; 
	$carinq = "INSERT into car_log (vehicleid, driverid, startTime, mileage, tires, damage, opitems, cash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; 
	
	$dq = $db->db->prepare($driverinq);
	$dresult = $db->db->execute($dq, $dvalues);
	$db->check_for_error($dresult);
	$cq = $db->db->prepare($carinq);
	$cresult = $db->db->execute($cq, $cvalues);
	$db->check_for_error($cresult);
	
	return "you have successfully clocked in and checked out car " . $cars[$carId] . ".";

}

function check_car($db, $driverId, $carId, $timestamp) {
	$cvalues = array($timestamp, $driverId, $timestamp);	
	$carupq = "UPDATE car_log SET endTime = ? where driverId = ? and endTime = 0 and startTime < ?"; 
	$cq = $db->db->prepare($carupq);
	$cresult = $db->db->execute($cq, $cvalues);
	$db->check_for_error($cresult);
	$cvalues = array($carId, $driverId, $timestamp, $_POST['mileage'], $_POST['tires'], $_POST['damage'], $_POST['opitems'], $_POST['cash']);	
	$carinq = "INSERT into car_log (vehicleid, driverid, startTime, mileage, tires, damage, opitems, cash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; 
	$cq = $db->db->prepare($carinq);
	$cresult = $db->db->execute($cq, $cvalues);
	$db->check_for_error($cresult);
	return "you have successfully checked out car " . $cars[$carId] . ".";
}

function authenticate($db, $username, $password) {
	//select from the dinfo table the id of the driver
	$values = array($username);
	$return = array();
	$query = "SELECT dinfo.password as password, dinfo.id as id, codes.code as code from dinfo, codes"
		." WHERE dinfo.id = codes.id and dinfo.username = ?";
	$q = $db->db->prepare($query);
	
	$result = $db->db->execute($q, $values);
	
	$db->check_for_error($result);
	
	if ($result->numRows() <= 0) {
		return $return;
	}
	
	while ($rs = $result->fetchRow()) {
		$return[] = $db->cleanRow($rs);	
	}
	
	$result->free();
	
	return $return[0];	
}

function show_login() {
?>
<h3>Username: <input type="text" name="username" value="" maxlength="8" size="8"></h3>
<h3>Password: <input type="password" name="password" value="" maxlength="8" size="8"></h3>
<input name="action" type="submit" value="Login">
<?
}
?>
</form>
</html>
