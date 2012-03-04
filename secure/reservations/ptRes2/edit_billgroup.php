<?php
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');
include('lib.php');
$t = new Template('Edit Billing Group');
$t->printHTMLHeader();
$d = new DBEngine();

$memberid = $_GET['memberid'];
$groupid = $_GET['groupid'];
$name = $_GET['name'];
if ($_GET['fn'] == "fn") {
	show_form($memberid, $groupid, $name);
} else if ($_GET['edit'] == "true") {
	edit_billid($_GET);
?>
<table width="100%" height="100%" border=0 cellspacing=0>
<tr><td onClick="javascript: window.close();" width="375" height="125" align="center" valign="middle">
<h4>Settings saved.<br>Click anywhere to close.</h4>
</td></tr></table>
<?
} else {
	//show_form();
	echo "error!";
}
$t->printHTMLFooter();

//**************************************************************************
function edit_billid($got) {
	if (isset($got['ban']))
		$role = ", role='x' ";
	else if (isset($got['unban']))
		$role = ", role='p' ";
	else
		$role = '';
	$query = "UPDATE login SET groupid='{$got['groupedit']}' $role  WHERE memberid='{$got['memberid']}'";
	$qresult = mysql_query($query);
	check_for_dberror($qresult);
}
function show_form($memberid, $groupid, $name) {
	$user = get_user($memberid);
	$name = str_replace("+", " ", $name);
	?>
	<div style="text-align:center;margin-top:10px;">
	Change billing information for: <?=$name?>
	<form name="editbillgroup" action="edit_billgroup.php" method=GET>
	<?=get_billgroups($memberid, $groupid)?>
	<br/>
	<?
	if ($user['role'] == 'x')
		echo '<input type="checkbox" name="unban"> Unlock this user\'s account (allow them to make reservations)?';
	else
		echo '<input type="checkbox" name="ban"> Lock this user\'s account (prevent from making reservations until unlocked)?';
	?>
	<br/>
	<input type="submit" value="Submit">
	<input type="hidden" name="edit" value="true">
	<input type="hidden" name="memberid" value="<?=$memberid?>">
	</form>
	</div>
	<?
}
function get_user($memberid) {
	$query = "select * from login where memberid='$memberid'";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);
	return $row;
}
function get_billgroups($memberid, $groupid) {
	$query = "SELECT groupid, group_name FROM billing_groups ORDER BY group_name asc";
	$qresult = mysql_query($query);
	check_for_dberror($qresult);
	echo '<select name="groupedit"><option value=""></option>';
	while ($row = mysql_fetch_assoc($qresult)) {
		$selected = ($groupid == $row['groupid']) ? "selected" : "";
		echo "<option value=\"{$row['groupid']}\"$selected>{$row['group_name']}</option>";
	}
	echo '</select>';
}
?>
