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
		$_SESSION['currentName'] = $_GET['fname'] .' '. $_GET['lname'];
	}
}

$t = new Template();
$db = new DBEngine();


pdaheader('Reservations');
pdawelcome('cpanel');

/*
* Do stuff here
*/
?>
<ul>
	<li><a href="m.motd.php">Message of the Day</a></li>
</ul>

<?
pdafooter();

?>
