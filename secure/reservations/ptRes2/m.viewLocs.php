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
pdawelcome('locations', 'm.locations.php');


/*
* Do stuff here
*/

// This would be currentID instead of sessionID on the live site. but in
// mobile we can only have one user at a time

$scheduleid = $db->get_user_scheduleid($_SESSION['sessionID']);
//$locs = $db->get_user_permissions($scheduleid);
$list = $db->get_recent_locations($_SESSION['sessionID']);
//CmnFns::diagnose($locs);
//die;

$perpage = 10;

$index1 = 0;
$index2 = $perpage;
$page = $_GET['page'];

if ($page && is_numeric($page)) {
	$index1 += $perpage * $page;
	$index2 = $index1 + $perpage;
	
}
$arr1 = array_slice($list, $index1, $index2);

// true for isRecent
//print_pda_location_list($arr1, null, true);
print_pda_location_list($arr1, null, false);

/********* footer links ****************/

$nextlink = $backlink = '';

if ($index2 < count($list)) {
	$nextpage = $page ? $page + 1 : 1;
	$nextlink = '<a href="m.viewLocs.php?page='.$nextpage.'">More >></a>';
}

if ($index1 >= $perpage) {
	$backpage = $page - 1;
	$backlink = '<a href="m.viewLocs.php?page='.$backpage.'"><< Back</a>';

}


?>

<table width="100%" cellspacing=0 cellpadding=0 border=0>
<tr>
	<td align="left"><?=$backlink?></td>
	<td align="center">&nbsp;</td>
	<td align="right"><?=$nextlink?></td>
</tr>
</table>

<?

pdafooter();


?>
