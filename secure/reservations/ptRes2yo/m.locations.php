<?php
/*
*	Use this file as a template for new mobile pages
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

$t = new Template(translate('My Control Panel'));
$db = new DBEngine();
$mode = $_GET['mode'];
$page = $_GET['page'];


pdaheader('Locations');
pdawelcome('locations');


/*
* Do stuff here
*/

// This would be currentID instead of sessionID on the live site. but in
// mobile we can only have one user at a time
/*
// this will move to location view page
$scheduleid = $db->get_user_scheduleid($_SESSION['sessionID']);
$locs = $db->get_user_permissions($scheduleid);

print_pda_location_list($locs);
*/

locmenu();

pdafooter();


?>
