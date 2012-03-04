<?php
set_include_path("../:lib/pear/:/usr/local/php5");
include_once('lib/Twitter.class.php');

if (isset($_POST['testResponse']) || isset($_GET['testResponse'])) 
	testResponse();
$t = new Twitter();
$t->check_post_vals();

$user = $t->get_user_info();

$lat = $t->fromLat;
$lon = $t->fromLon;
$latlng = "$lat,$lon";
$sensor = "true";

$url = "http://maps.google.com/maps/api/geocode/xml?latlng=$latlng&sensor=$sensor";

$t->set_date_and_time();

/* FROM location handling. It will be either coordinates or a name. **********/

if ($t->fromType == 1) {
	// $address_array comes from xml_parser.php

	include('xml_parser.php');
	$from = $t->get_gps_loc_values($address_array);
	$t->machid = $from['machid'];
	$t->insert_temp_location($from);

} else if ($t->fromType == 2) {
	// get address from database, fail if not found
	$from = $t->get_loc_from_name($t->fromName);
	$t->machid = $from['machid'];
}

/* TO location handling. **************/

if ($t->toType == 1) {
	$t->toLocation = 'asDirectedLoc';

} else {
	$to = $t->get_loc_from_name($t->toName);
	$t->toLocation = $to['machid'];
}

// double check for errors before inserting anything into temp tables
if ($t->has_errs()) $t->print_failure();

$res = $t->set_default_res_values();
$t->insert_temp_reservation($res);
$t->get_quote($from, $to);

if ($t->has_errs()) {
	// clean up temp stuff
	$t->print_failure();
} else
	$t->print_success($from, $to);
		

/***************************************************************************/

function testResponse() {
	$email = 'msobecky@gmail.com';
	$eta = 10;
	$price = 44;
	$padd = "1 Broadway";
	$pcity = "Cambridge";
	$pstate = "MA";
	$dadd = "1 Main St";
	$dcity = "Cambridge";
	$dstate = "MA";
	$conflink = "http://www.planettran.com/DummyConfirmationLink.php";
	$modlink = "http://m.planettran.com";
	
	$mode = isset($_POST['testResponse']) ? $_POST['testResponse'] : (isset($_GET['testResponse']) ? $_GET['testResponse'] : '');
	$return = '';

	if ($mode == "1")
		$return = "1;$email;$padd;$pcity;$pstate;$dadd;$dcity;$dstate;$conflink;$modlink";
	else if ($mode == "0")
		$return = "0;$email;You're ugly";
	echo $return;
	die;
}











?>
