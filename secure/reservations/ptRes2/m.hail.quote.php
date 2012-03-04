<?php
/*
*	Use this file as a template for new mobile pages
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('lib/Mobile.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');
include_once('lib.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.main.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] .' '. $_GET['lname'];
	}
}

$t = new Template();
$db = new AdminDB();
$m = new Mobile();
$quote = 1;
global $quote;

$type = $_GET['type'] ? $_GET['type'] : ($_POST['type'] ? $_POST['type'] : $m->type);
$mtype = $_GET['mtype'] ? $_GET['mtype'] : ($_POST['mtype'] ? $_POST['mtype'] : $m->mtype);

pdaheader('Fare Quote');
pdawelcome('rehail');

/*
* Do stuff here
*/

if ($_POST['getquote']) {
	// Reservation has been submitted; print page with hidden post vals
	// and quote. Submitting will go to reserve.php

	$groupid = $_SESSION['curGroup'];
	$discount = $db->getDiscount($groupid);
	if ($groupid == 8) $discount = 0;


	$from = $db->get_resource_data($_POST['fromLoc']);
	$to = $db->get_resource_data($_POST['toLoc']);
	$fromquote = apt_or_zip($from['machid'], $from['zip']);
	$toquote = apt_or_zip($to['machid'], $to['zip']);


	$groupmsg = "";
	if ($discount) $groupmsg = ", and includes any discounts on this account";

	$carType = $_POST['carTypeSelect'];
	$seatType = $_POST['seatTypeSelect'];

	if ($mtype == 'h') {
		$baserate = 60;
		if ($carType == 'V') $baserate = 80;
		else if ($carType == 'S') $baserate = 75;
		$fare = $baserate * $_POST['hour_estimate'];
		//CmnFns::diagnose($_POST);
	} else {

		$wrapper = "wrapper.pl";
		$execstr = "perl $wrapper $fromquote $toquote 1";
		exec($execstr, $a, $b);
		$fare = $a[0];

		// if we didn't get fare don't add SUV or van
		if ($fare) {
			if ($carType == 'V') $fare += 50;
			else if ($carType == 'S' || $carType == 'L') $fare += 30;
		}
	}

	if ($fare) {
		if (	$seatType == 'I' ||
			$seatType == 'T' ||
			$seatType == 'O')
			$fare += 5;
	}

	$fare = round($fare - $fare * $discount);

	if (!$fare && $mtype != 'h') {
		quote_fail();
		pdafooter();
		die();
	}



	print_quote($fare, $mtype, $groupmsg);
	// printing res gives extra baggage like "leave feedback" link
	//$m->print_pda_res();
	print_quote_form($type, $mtype);
	
} else {
	// Print reservation form
	$m->lat = $_GET['lat'];
	$m->lon = $_GET['lon'];

	$m->print_hail_res('quote');
}




pdafooter();

/*************************************************/
// Quote text
function quote_fail() {
	?>
	<div class="paragraph">
	We were unable to generate a quote from between those locations. Please check that all location fields are filled in correctly.
	</div>
	<ul>
		<li><a href="#" onClick="history.go(-1)">Back</a></li>
		<li><a href="m.quote.php">Return to Quotes</a></li>
		<li><a href="m.cpanel.php">Return to Reservations</a></li>
		<li><a href="m.main.php">Return to Main</a></li>
	</ul>
	<?
}
function print_quote($fare, $mtype, $groupmsg = '') {
	$hourmsg = $rtmsg = '';
	$rtfare = $fare * 2;

	if ($mtype == 'h') {
		$hours = $_POST['hour_estimate'];
		$hourmsg = " for $hours hours";
		$msg = "Hourly rates are $60/hr for a sedan, $75/hr for SUV, and $80/hr for van or Lexus SUV. ";
	} else if ($mtype == 'r') {
		$msg = "This is an <b>estimated</b> fare based on the distance to the center of the selected zip codes. Actual fare may vary based on mileage and time. If you have multiple reservations (round trip), the fare will be applied to each reservation individually.";
		$rtmsg = '(one way), $'.$rtfare.' (round trip)'; 
	} else 
		$msg = "This is an <b>estimated one-way</b> fare based on the distance to the center of the selected zip codes. Actual fare may vary based on mileage and time."; 

	$msg .= "<br>Tips are not included and are not expected by drivers. PlanetTran drivers are compensated by hourly time, not based on number of trips or tips, which we feel results in the best customer experience.";

	?>
	<div class="title" style="text-align: center;">
	Your estimated fare is $<?=$fare?><?=$hourmsg?><?=$rtmsg?>.
	</div>
	<?
	

	?>
	<div class="smallparagraph" style="text-align: center;">
	<?=$msg?>
	</div>
	<div class="smallparagraph" style="text-align: center;">
	This amount is subject to wait time, cancellation fee or additional amenities selected<?=$groupmsg?>. Please call PlanetTran at 888-756-8876 with any questions.
	</div>
	<?
}

// our form with all of our hidden reservation variables
function print_quote_form($type, $mtype) {
	global $db;
	$post = $_POST;
	unset ($post['resid']);
	
	// set other necessary vars
	$post['type'] = $type;
	$post['mtype'] = $mtype;
	$post['fn'] = 'create';
	$post['ts'] = time();
	$scheduleid = $db->get_user_scheduleid($_SESSION['sessionID']);
	$post['scheduleid'] = $scheduleid;

	echo '<div style="text-align: center;">';
	echo '<form name="reservation" method="post" action="m.reserve.php">';

	foreach ($post as $k=>$v) {
		?>
		<input type="hidden" name="<?=$k?>" value="<?=$v?>">
		<?
	}
	?>
	<input type="submit" value="Create Reservation">
	</form>
	</div>
	<div>
	<a href="m.cpanel.php">Back to Reservations</a><br>
	<a href="m.main.php">Back to Main</a>
	</div>
	
	<?
}

/*
* determine whether a location is an airport, for lookup in the DB
* - this function also escapes the shell args
*/
function apt_or_zip($machid, $zip) {
	if (strpos($machid, 'airport') !== false)
		$return = substr($machid, -3);
	else if (stripos($machid, 'logan') !== false)
		$return = 'BOS';
	else if ($machid == '41b40be9091cb')
		$return = 'BOS';
	else
		$return = $zip;

	return escapeshellarg($return);
}

/*
*  Add extra for toddler seat, infant, ect
*/
function add_extras() {

}

function returnlinks() {
	?>


	<?
}

?>
