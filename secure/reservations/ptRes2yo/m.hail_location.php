<?php
/*
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('lib/Twitter.class.php');
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

// create temp location

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

hail_location_view($loc, $scheduleid);


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
function hail_location_view($loc = array(), $scheduleid) {
	$type= $_GET['type'];
	?>
	<div class="paragraph">
	Congratulations! We have acquired your nearest location based on your coordinates. Please double check below that the location is accurate (or close enough), then click "Continue" to create a reservation.
	</div>
	<table width="100%" cellspacing=1 cellpadding=1 class="reservation">
	<tr>
		<td class="title" width="20%">Name</td>
		<td width="80%"><?=$loc['name']?></td>
	</tr>
	<tr>
		<td class="title">Address 1</td>
		<td><?=$loc['address1']?></td>
	</tr>
	<tr>
		<td class="title">Address 2</td>
		<td><?=$loc['address2']?></td>
	</tr>
	<tr>
		<td class="title">City</td>
		<td><?=$loc['city']?></td>
	</tr>
	<tr>
		<td class="title">State</td>
		<td><?=$loc['state']?></td>
	</tr>
	<tr>
		<td class="title">Zip code</td>
		<td><?=$loc['zip']?></td>
	</tr>
	<tr>
		<td class="title">Location phone</td>
		<td><?=$loc['rphone']?></td>
	</tr>
	<tr>
		<td class="title">Location notes</td>
		<td><?=$loc['notes']?></td>
	</tr>
	</table>
	<div>
	<div><a href="m.addLoc.php?type=r&machid=<?=$loc['machid']?>">Continue</a></div>
	</div>
	<?
}
?>
