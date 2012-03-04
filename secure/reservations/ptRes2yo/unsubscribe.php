<?php
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');

$t = new Template();
$d = new DBEngine();

$t->printHTMLHeader();
?>
<div align="center">
<table width=600 border=0 cellpadding=0 cellspacing=0>
<?
include('header_nav.shtml');
echo '<td colspan=3><div style="margin: 3em; text-align: center;">';
unsubscribe();
echo '</div></td></tr>';
include('footer.shtml');
$t->printHTMLFooter();

function unsubscribe() {
	$email = mysql_real_escape_string($_GET['user']);
	$query = "update messaging set optout=1 where email='$email'";
	$qresult = mysql_query($query);
	$rows = mysql_affected_rows();

	if (!$qresult || !$rows) echo "There was an error; $email is not in our records, or has already been unsubscribed.";
	else {
		echo "$email has been unsubscribed.";
	}
}

//<div style="font-face: Arial; font-size: 1em; text-align: center; width: 600px; margin-left: auto; margin-right: auto;">
?>
