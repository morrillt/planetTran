<?php
/*
*	Mobile new reservation wizard
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


pdaheader('Reservations');
pdawelcome('cpanel');


/*
* Do stuff here
*/

$link = "m.quote.php?type=r&mtype=";
?>

<ul>
<li><a href="<?=$link?>o">One Way</a></li>
<li><a href="<?=$link?>r">Round Trip</a></li>
<li><a href="<?=$link?>h">As Directed (hourly)</a></li>
</ul>

<?
pdafooter();


?>
