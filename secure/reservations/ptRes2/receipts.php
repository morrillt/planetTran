<?php
//ini_set('display_errors', '0');
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');
include_once('templates/cpanel.template.php');


if (!Auth::is_logged_in()) { 
    Auth::print_login_msg();
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

$t = new Template(translate('History/Receipts | Reservations | PlanetTran'), false);
$t->printHTMLHeader('silo_reservations sn5');
$t->printNavReservations();
$db = new DBEngine();

$t->startMain();
//$t->printWelcome();

//printCpanelBr();
?>
<h1 id="hdr_history"><span class="imagetext">Previous Trips</span></h1>
<?php

//show_form();
if($_SESSION['role']=='a' || $_SESSION['role']=='m' || $_SESSION['role']=='w' || $_SESSION['role']=='v') {
	// If role is appropriate, allow for searching
	echo '<form action="'. $_SERVER['PHP_SELF']. '" method="post">
	<table>
	  <tr>
	    <td>First Name: <input type="text" name="firstName"/> </td> <td> Last Name: <input type="text" name="lastName"/> </td> '.
	   '<td>Email: <input type="text" name="email"></td></tr><tr>';
	
	$groups = AdminDB::get_grouplist();

	echo '<select name="group">';
	echo '<option value="">Select group</option>';
	foreach ($groups as $k => $v) {
		echo "<option value=\"$k\">$v</option>\n";
	}
	echo '</select></td></tr></table>';
	echo '<input type="submit"/>';
}
$res = $db->get_user_receipts($_SESSION['currentID']);

showReceiptsTable($res, $db->get_err());

printCpanelBr();

$t->endMain();
$t->printHTMLFooter();
/**************************************************************/
function show_form() {
	$mon = range(1,12);
	$yr = range(2003,date("Y"));
	$curmon = date("n");
	$curyear = date("Y");
	?>
	<table width="100%" cellspacing=0 cellpadding=2>
	<tr><td align="left">
	<form name="receipts" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
	Display receipts from 
	<?
	echo '<select name="monthLow">';
	foreach ($mon as $m) {
		$selected = $m == $_GET['monthLow'] ? 'selected' : '';
		echo "<option value=\"$m\" $selected>$m</option>";
	}
	echo '</select>';

	echo '<select name="yearLow">';
	foreach ($yr as $y) {
		$selected = $y == $_GET['yearLow'] ? 'selected' : '';
		echo "<option value=\"$y\" $selected>$y</option>";
	}
	echo '</select>';

	echo ' to ';
	echo '<select name="monthHi">';
	foreach ($mon as $m) {
		$selected = $m == $_GET['monthHi'] ? 'selected' : ($m == $curmon ? 'selected' : '');
		echo "<option value=\"$m\" $selected>$m</option>";
	}
	echo '</select>';

	echo '<select name="yearHi">';
	foreach ($yr as $y) {
		$selected = $y == $_GET['yearHi'] ? 'selected' : ($y == $curyear ? 'selected' : '');
		echo "<option value=\"$y\" $selected>$y</option>";
	}
	echo '</select>';
	if (isset($_GET['page']))
		echo '<input type="hidden" name="page" value="'.$_GET['page'].'">';
?>
 <input type="submit" class="button" value="Search">
</form></td>
<td align="right" valign="top">
<a href="export_receipts.php">Download results <img src="img/excel.gif" border=0></a>
</td>
</tr></table>
<?
}
?>
