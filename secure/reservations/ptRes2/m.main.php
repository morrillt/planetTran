<?php
set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('lib/Mobile.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.main.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

$t = new Template();
$db = new DBEngine();


pdaheader('Main', '', false);
pdawelcome('main');
//$_SESSION['alertDone'] = 0;
//CmnFns::diagnose($_SESSION);

$res = $db->get_user_reservations($_SESSION['currentID'], CmnFns::get_value_order($order), CmnFns::get_vert_order(), false);
$showactive = false;
$showlink = false;
$needsalert = false;

if ($res[0]) {
	$time = time() + 60 * 60 * 4;
	$datetime = $res[0]['date'] + $res[0]['pickupTime'] * 60;

	// if res in next 4 hours, we have an active. Show link if we've
	// already alerted.
	if ($datetime < $time) {
		// this shows alert every time we log in.
		if ($_GET['showalert']) $showactive = true;
		$needsalert = true;
		$showlink = true;
	}
}

// if $showactive (we just logged in) OR Alert has not been done and 
// we need one, show alert

if ($showactive || ($needsalert && !$_SESSION['alertDone'])) {
	?>
	<div style="text-align: left;">You have car reserved in the next 4 hours:</div>
	<?

	$_SESSION['alertDone'] = 1;
	$r = new Mobile($res[0]['resid']);
	$r->type = 'v';
	$r->print_pda_res(true);
	//print_pda_res_table($res, $db->get_err(), true);

} else {
	// No alert, print menu options

	$tel = "18887568876";
	?>

	<ul>

	<?

	if ($showlink) echo '<li><a href="m.reserve.php?resid='.$res[0]['resid'].'&type=v">Car Status</a></li>';

	?>
	<li><a href="digital_hail.php?ts=<?=time()?>">Digital Hailing</a></li>
	<li><a href="m.cpanel.php">Reservations</a></li>
	<li><a href="m.history.php?mode=feedback">Driver Feedback</a></li>
	<!--<li>Tools</li>-->
	<li><a href="m.profile.php">My Profile</a></li>
	<li><a href="m.faq.php">FAQ</a></li>
	<li><a href="m.specialoffers.php">Special Offers</a></li>
	<?

	if (Auth::isEmployee())
		echo '<li><a href="m.employeePortal.php">Employee Portal</a></li>';

	?>
	</ul>

	<!--<div style="text-align: center; font-size: small;">
	<a href="tel:<?=$tel?>">Call PlanetTran (888) 756-8876</a>
	</div>-->
	<?
	about_contact('m.main.php');
}
pdafooter();


?>
