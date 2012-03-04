<?php
/**
* This file prints out a registration or edit profile form
* It will fill in fields if they are available (editing)
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 08-02-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Include Template class
*/
$edit = isset($_GET['edit']);
session_register('classic');
//if ($_SESSION['classic'])
//	include_once('lib/Template.class.php');
//else if ($edit && !$_SESSION['classic'])
//	include_once('lib/Template2.class.php');
//else
include_once('lib/Template.class.php');
$biz = (isset($_GET['biz']) ? $_GET['biz'] : 'www') ;
$prefix = (isset($_GET['prefix']) ? $_GET['prefix'] : $biz);
$groupid = (isset($_GET['groupid']) ? $_GET['groupid'] : 0);
$promo_code = (isset($_GET['promo_code']) ? $_GET['promo_code'] : '');
// Auth included in Template.php
$auth = new Auth();
$t = new Template("Profile Registration");

$msg = '';
$show_form = true;

// Check login status
if ($edit && !$auth->is_logged_in()) {
	$auth->print_login_msg(true);
	$auth->clean();			// Clean out any lingering sessions
}

// If we are editing and have not yet submitted an update
if ($edit && !isset($_POST['update'])) {
	$user = new User($_SESSION['sessionID']);
	$data = $user->get_user_data();
	$data['emailaddress'] = $data['email'];		// Needed to be the same as the form
}
else
	$data = CmnFns::cleanPostVals();

if (isset($_POST['register'])) {	// New registration
	$msg = $auth->do_register_user($data, '');
	$show_form = false;
}

else if (isset($_POST['update'])) {	// Update registration
	$msg = $auth->do_edit_user($data);
	$show_form = false;
}

if ($_SESSION['okphone'] === false)
	$msg .= "<br />Please enter a valid cell phone number to continue.<br />";

// Print HTML headers
$t->printHTMLHeader();
$t->startMain();

// $t->set_title(($edit) ? translate('Modify My Profile') : translate('Register'));

// Print the welcome banner if they are logged in
if ($edit)
	$t->printWelcome();

// Begin main table
//$t->startMain();
//echo '<table style="width="100%" align="center" cellspacing=0 cellpadding=0>';

//set_include_path("../../;../;lib/pear/;C:/AppServ/php5");
//include_once('header_nav.shtml');
//echo '<tr><td colspan=3>';

$u = new User($_SESSION['sessionID']);
$name = $u->get_name();
if ($edit) echo '<div class="titlebar">Profile info for '.$name.'</div>';

// Either this is a fresh view or there was an error, so show the form
if ($show_form || $msg != '') {
	$auth->print_register_form($edit, $data, $msg, 1);
}

// The registration/edit went fine, print the message
if ($msg == '' && $show_form == false) {
	$auth->print_success_box();
}

// End main table

$t->endMain();

// Print HTML footer
$t->printHTMLFooter();
?>
