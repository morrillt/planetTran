<?php
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');

$t = new Template('Registration Confirmed');
$d = new DBEngine();
//if (!$_GET['id'] || !$_GET['c']) die;
$id = mysql_real_escape_string($_GET['id']);
$time = mysql_real_escape_string($_GET['c']);
$role = 'p';
if (isset($_GET['r']) && $_GET['r'] == 'a')
	$role = 'a';

$query = "select created from login where memberid='$id'";
$qresult = mysql_query($query);
$row = mysql_fetch_assoc($qresult);
$created = md5($row['created']);



$t->printHTMLHeader();
$t->startMain();

if ($time != $created) {
	echo '<div align="center">Unable to confirm account. If you reached this page by clicking the link in the confirm email, please try copying and pasting the address into your address bar instead.</div>';
	die;
} 

$query = "update login set role='$role' where memberid='$id'";
$qresult = mysql_query($query);

echo '<div align="center">Thank you! You can now <a href="http://reservations.planettran.com">log in and make reservations</a>.</div>';

$t->endMain();
$t->printHTMLFooter();
?>
