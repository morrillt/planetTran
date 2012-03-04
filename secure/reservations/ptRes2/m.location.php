<?php
/*
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');
include_once('lib.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

$t = new Template(translate('My Control Panel'));
$db = new AdminDB();


pdaheader('Location');
pdawelcome('location');


/*
* Do stuff here
*/
$mode = $_GET['type'] ? $_GET['type'] : ($_POST['type'] ? $_POST['type'] : null);
$machid = $_GET['machid'] ? $_GET['machid'] : ($_POST['machid'] ? $_POST['machid'] : null);
$fn = $_POST['fn'];
$scheduleid = $db->get_user_scheduleid($_SESSION['sessionID']);
$loc = array();

if ($machid)
	$loc = $db->get_resource_data($machid);

if ($mode == 'v')
	location_view($loc, $scheduleid);
else if ($fn == 'create')
	add_pda_loc();
else if ($fn == 'modify') 
	mod_pda_loc();
else if ($fn == 'delete')
	del_pda_loc();
else
	pda_location_form($loc, $scheduleid, $mode);


pdafooter();


function add_pda_loc() {
	global $db;
	global $conf;

	$resource = check_pda_loc_vals(CmnFns::cleanPostVals());
	//if ($resource['bypass'] != 'bypass') {
	if(1) {
		$location = $resource['address1']
				.", " . $resource['city']
				.", " . $resource['state']
				." " . $resource['zip'];

		$locarray = getGPS($location);
		$resource['lat'] = $locarray['lat'];
		$resource['lon'] = $locarray['lon'];
		$resource['location'] = $resource['address1']." ".$resource['address2'].", ".$resource['city'].", ".$resource['state']." ".$resource['zip'];
	}

	//if ((!$locarray['lat'] || !$locarray['lon']) && $resource['bypass'] != 'bypass') {
	if ($resource['err']) {
		print_pda_loc_fail($resource['err']);
	} else {
		$id = $db->add_resource($resource);

		print_pda_loc_success('created');
	}
}

function mod_pda_loc() {
	global $db;

	$resource = check_pda_loc_vals(CmnFns::cleanPostVals());
	//if ($resource['bypass'] != 'bypass') {
	if(1){
		$location = $resource['address1'] 
				.", " . $resource['city']
				.", " . $resource['state']
				." " . $resource['zip'];

		$locarray = getGPS($location);
		$resource['lat'] = $locarray['lat'];
		$resource['lon'] = $locarray['lon'];
		$resource['location'] = $resource['address1']." ".$resource['address2'].", ".$resource['city'].", ".$resource['state']." ".$resource['zip'];
	}

	//if ((!$locarray['lat'] || !$locarray['lon']) && $resource['bypass'] != 'bypass') {
	if ($resource['err']) {
		print_pda_loc_fail($resource['err']);
	} else {
		$db->edit_resource($resource);
		print_pda_loc_success('modified');
	}
}

function del_pda_loc() {
	global $db;

	$db->del_resource($_POST['machid']);
	print_pda_loc_success('deleted');
}

function check_pda_loc_vals($loc) {
	if (	!$loc['name'] ||
		!$loc['address1'] ||
		!$loc['city'] ||
		!$loc['state'] ||
		!$loc['zip'])
		$loc['err'] = "The Name, Address 1, City, State and Zip Code fields are required.";

	$loc['minRes'] = 0;
	$loc['maxRes'] = 0;
	$loc['autoAssign'] = 0;

	return $loc; 
}

function print_pda_loc_success($verbed) {
	?>
	<div style="text-align: center;">
	Your location has been successfully <?=$verbed?>.<br>
	<a href="m.locations.php">Back</a>
	</div>
	<?
}

function print_pda_loc_fail($msg) {
	$mode = $_GET['type'];
	$machid = $_GET['machid'];
	?>
	<div style="text-align: center;">
	<?=$msg?><br>
	<a href="m.location.php?type=<?=$mode?>&machid=<?=$machid?>">Back</a>
	</div>
	<?
}
?>
