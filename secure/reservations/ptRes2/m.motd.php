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
$motd = get_motd();

pdaheader('Message of the Day');
pdawelcome('motd', 'm.employeePortal.php');

/*
* Do stuff here
*/

echo '<div style="text-align: center; font-size: large; font-weight: bold;">';
echo $motd;
echo '</div>';

pdafooter();

/************************************************************************/

function get_motd() {
	//return file_get_contents("motd.txt");
	global $conf;
	$file = $conf['app']['include_path'].'dispatch/ptRes/motd.txt';
	$h = fopen($file, "r");
	$contents = filesize($file)?fread($h, filesize($file)):"";
	$contents = stripslashes($contents);
	fclose($h);
	return $contents;
}
?>
