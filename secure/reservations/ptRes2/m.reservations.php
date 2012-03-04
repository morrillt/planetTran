<?php
/**
*  Mobile cpanel
*/
set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');

/**
* Include control panel-specific output functions
*/
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
    	//Auth::print_login_msg();	// Check if user is logged in
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

$t = new Template(translate('My Control Panel'));
$db = new DBEngine();

/* move this to locations page */
/*
if (!empty($_POST['apts']))
	$db->add_apt($_SESSION['currentID'], $_POST['apts']);
else if (!empty($_GET['apts']))
	$db->add_apt($_SESSION['currentID'], $_GET['apts']);
*/

pdaheader('Reservations');
pdawelcome('reservations', 'm.cpanel.php');

$active = isset($_GET['active']) ? $_GET['active'] : (isset($_POST['active']) ? $_POST['active'] : false);

$order = array('date', 'name', 'startTime', 'endTime', 'created', 'modified');
$res = $db->get_user_reservations($_SESSION['currentID'], CmnFns::get_value_order($order), CmnFns::get_vert_order());

print_pda_res_table($res, $db->get_err());// Print out My Reservations


pdafooter();


?>
