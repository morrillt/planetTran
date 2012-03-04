<?php
/*
*	Add a recommended loc to the user's permissions then forward them
*	to the reservation form
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
//include_once('templates/cpanel.template.php');
//include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] .' '. $_GET['lname'];
	}
}

//$t = new Template();
$db = new DBEngine();

$type = $_GET['type'];
$forwardstr = "m.reserve.php?type=$type";
$memberid = $_SESSION['sessionID'];
$machid = isset($_GET['machid']) ? $_GET['machid'] : $_GET['toLocSelect'];

$vals = array($memberid, $machid);
$query = "insert into permission (memberid, machid) values (?, ?)";
$db->db->query($query, $vals);

if (isset($_GET['machid']))
	$forwardstr .= "&machid=$machid";
else if (isset($_GET['toLocSelect']))
	$forwardstr .= "&toLocSelect=$machid";

header('Location: '.$forwardstr);


?>
