<?php
include_once('lib/db/ResDB.class.php');
$d = new ResDB();

if (!$_GET['coupon']) {
	echo "Please enter a coupon code to check its validity.";
	die(0);
}

$code = mysql_real_escape_string($_GET['coupon']);

$coupon = $d->get_coupon_amount($code);
if (!$coupon || !$coupon['amount'] || !$coupon['type']) {
	echo "That is not a valid coupon code.";
	die(0);
}

$date = $_GET['date'];
$memberid = $_GET['memberid'];

if (!$date) {
	echo "Please enter a date to check if your coupon is valid for that date.";
	die(0);
} else
	$date = strtotime($date);

if ($coupon['type']=='p') $amount = $coupon['amount'].'%';
else if ($coupon['type']=='d') $amount = '$'.$coupon['amount'];
else $amount = $coupon['amount'];

$name = $coupon['coupon_code'];


if (!$coupon['uExpires']) 
	$expires = "It never expires";
else
	$expires = "It expires on ".date("m/d/Y", $coupon['uExpires']);

//CmnFns::diagnose($coupon);


$restriction = $r2 = '';
if ($coupon['allowed'] == 'p2p') {
	$restriction = "\n\nIt is valid for point to point (non airport) trips only.";
	$r2 = "point to point (non-airport)";
} else if ($coupon['allowed'] == 'airport') {
	$restriction = "\n\nIt is valid for trips to or from an airport only.";
	$r2 = "airport";
} else if ($coupon['allowed'] == 'hourly') {
	$restriction = "\n\nIt is valid for hourly (As Directed) trips only.";
	$r2 = "hourly (as directed)";
} else {
	$r2 = "all";
}

$recur = '';
if ($coupon['recurrence'] > 1)
	$recur = "\n\nIt can be used a total of ".$coupon['recurrence']." times on this account.";

$msg = "Coupon $name is valid for a $amount discount on $r2 trips. $expires.$recur";

// If it's expired, override all other messages
//if ($coupon['uExpires'] && $coupon['uExpires'] < time())
//	$msg = "That coupon is expired.";

$err = coupon_been_used2($memberid, $code, false, $date);

if ($err) $msg = "Coupon $name $err and cannot be used for this trip.";

echo $msg;

/***********************************************/
function coupon_been_used2($memberid, $coupon_code, $mod_id = false, $date = 0){
	global $d;
	$recur = 1;
	$query = "select *, unix_timestamp(expires) as exp, unix_timestamp(begins) as begins from coupon_codes where coupon_code=?";
	$vals = array($coupon_code);
	$result = $d->db->getRow($query, $vals);

	if (!$result) return false;

	$recur = $result['recurrence'];

	// Date test
	if ($result['exp']) {
		$expire = $result['exp'];
		$begins = $result['begins'];
		if ($date > $expire) return "is not valid for this date";
		if ($date < $begins) return "is not valid for this date";
	}

	$query = "select resid from reservations where coupon_code=? and memberid=? and is_blackout <> 1";
	$vals = array($coupon_code, $memberid);
		
	// If modifying, exclude the current reservation
	//if ($mod_id) {
	//	$query .= " and resid <> ?";
	//	$vals[] = $mod_id;
	//}

	$result = $d->db->query($query, $vals);

	// coupon limit test
	if ($result) { 
		$d->check_for_error($result);

		$rows = $result->numRows();

		if (!$rows || $rows < $recur) return false;
		else return "has already been used on this account";
	}
	else return false;
}

?>
