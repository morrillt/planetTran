<?php
/*
*	mobile profile edit page
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
$user = new User($_SESSION['sessionID']);
$u = $user->get_user_data();

$field = $_GET['field'];

pdaheader('Profile Edit');
pdawelcome('profileedit');


/*
* Do stuff here
*/

if (isset($_POST['update'])) {
	update_profile();
	print_profile_success();
	// show success or fail page
} else {
	print_profile_edit($u);
}


pdafooter();

/**************************************************************************/
function update_profile() {
	global $db;
	//CmnFns::diagnose($_POST);
	$field = mysql_real_escape_string($_POST['field']);
	$value = $_POST['newval'];
	if ($field == 'password')
		$value = md5($value);
	$memberid = $_POST['memberid'];
	$vals = array($value, $memberid);
	
	$query = "update login set $field=? where memberid=?";
	$result = $db->db->query($query, $vals);
}
function print_profile_success(){
	?>
	<div style="text-align: center;">
	Your profile has been updated.<br>&nbsp;<br>
	<a href="m.profile.php">Back to profile</a><br>
	<a href="m.main.php">Back to main menu</a>
	</div>
	<?
}
?>
