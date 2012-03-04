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

$t = new Template();
$db = new DBEngine();
$user = new User($_SESSION['sessionID']);
$u = $user->get_user_data();

pdaheader('Reservations');
pdawelcome('cpanel');


/*
* Do stuff here
*/
?>
<table width="100%" cellspacing=2 cellpadding=0>
<tr>
	<td width="20%"><b>Name</b></td><td width="80%"><?=$u['fname']." ".$u['lname']?></td>
</tr>
<tr>
	<td><b>Email/Login</b></td><td><?=$u['email']?> <?=editlink('email')?></td>
</tr>
<tr>
	<td><b>Cell Phone</b></td><td><?=$u['phone']?> <?=editlink('phone')?></td>
</tr>
<tr>
	<td><b>Organization</b></td><td><?=$u['institution']?> <?=editlink('institution')?></td>
</tr>
<tr>
	<td><b>Dept. Code</b></td><td><?=$u['position']?> <?=editlink('position')?></td>
</tr>
<tr>
	<td><b>PlanetTran Franchise</b></td><td>Edit</td>
</tr>
<tr>
	<td><b>Change Password</b></td><td><?=editlink('password')?></td>
</tr>
<tr>
	<td><b>Driver Notification Settings</b></td><td>Edit</td>
</tr>
<tr>
	<td><b>Twitter Username</b></td><td><?=$u['twitter_username']?> <?=editlink('twitter_username')?></td>
</tr>
<tr>
	<td><b></b></td><td></td>
</tr>
<tr>
	<td><b></b></td><td></td>
</tr>
</table>
<a href="m.favs.php">View/edit your favorite drivers</a>
<br>
<a href="m.refer.php">Refer someone to PlanetTran!</a>
<?
pdafooter();

function editlink($field) {
	return '<a href="m.profileEdit.php?field='.$field.'">Edit</a>';
}
?>
