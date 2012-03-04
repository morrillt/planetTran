<?php
session_register('classic');
if ($_SESSION['classic'])
	include_once('lib/Template.class.php');
else
	include_once('lib/Template2.class.php');
include_once('lib/DBEngine.class.php');
include_once('lib/User.class.php');
if (!Auth::is_logged_in()) 
    Auth::print_login_msg();
$t = new Template('Delete Favorite Driver');
$db = new DBEngine();
$t->printHTMLHeader();
$t->startMain();
$t->printWelcome();

$memberid = $_SESSION['sessionID'];
$driverid = $_GET['driverid'];
$user = new User($driverid);
$user->load_by_id();
$u = $user->get_user_data();
$name = $u['fname']." ".strtoupper(substr($u['lname'], 0, 1));

$msg = '';
if (!$u || !$user || !$name)
	$msg = "Invalid driver. Please contact customer support.";
else {
	rem_driver($memberid, $driverid);
	$msg = "$name has been removed from your favorites.";
}

?>
<div class="basicText" style="text-align: center; padding-top: 25px; margin-bottom: 20px;">
<?=$msg?>
</div>
<div class="basicText" style="text-align: center;">
<a href="register.php?edit=true">Back</a>
</div>
<br>
<?
$t->endMain();
$t->printHTMLFooter();

/*****************************************************/
function rem_driver($memberid, $driverid) {
	global $db;
	$db->delete_fave($memberid, $driverid);
}
?>
