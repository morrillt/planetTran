<?php
/*
*	Use this file as a template for new mobile pages
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.favs.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] .' '. $_GET['lname'];
	}
}

$t = new Template();
$db = new DBEngine();
$memberid = $_SESSION['sessionID'];

pdaheader('Favorite Drivers');
pdawelcome('favs', 'm.profile.php');

/*
* Do stuff here
*/

$favs = $db->get_favs($memberid);

if (!$favs) favdie("You have no favorite drivers!", 'm.profile.php');

if ($_GET['del']) {
	$name = $_GET['name'];
	rem_driver($memberid);
	favdie("$name has been removed from your favorites.", 'm.profile.php');
	$favs = $db->get_favs($memberid);
}

?><table width="100%" cellspacing=0 cellpadding=2><?

for ($i=0; $favs[$i]; $i++) {
	$cur = $favs[$i];
	$lname = strtoupper(substr($cur['lname'], 0, 1));
	$name = $cur['fname']." ".$lname;
	echo "<tr>";
	echo "<td>$name</td>";	
	echo '<td style="text-align: right;"><a href="m.favs.php?del=1&driverid='.$cur['driverid'].'&name='.urlencode($name).'">Remove</a></td>';
	echo "</tr>";
}

echo '</table>';


pdafooter();
/********************************/
function favdie($msg, $back, $die = 0) {
	?><div class="paragraph" style="text-align: center;">
	<?=$msg?>
	<br>
	<a href="<?=$back?>">Back</a>
	</div>
	<?
	pdafooter();
	if ($die) die();
	
}
function rem_driver($memberid) {
	global $db;
	$driverid = $_GET['driverid'];
	$db->delete_fave($memberid, $driverid);
}
?>
