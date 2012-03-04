<?php
/*
*	History (driver feedback) and receipts page
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

$title = $_GET['mode'] == 'feedback' ? 'Driver Feedback' : 'Receipts';
$t = new Template();
$db = new DBEngine();

$res = $db->get_user_receipts($_SESSION['sessionID']);


pdaheader($title);
pdawelcome('history');


print_history($res);

/*
* Do stuff here
*/


pdafooter();


?>
