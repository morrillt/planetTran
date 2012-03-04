<?php
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');
$t = new Template('Billing Special');
$d = new DBEngine();
if (!Auth::is_logged_in() || !Auth::isAdmin()) 
    Auth::print_login_msg();
$t->printHTMLHeader();

group_select();

if (isset($_GET['group'])) {
	show_special_rates();
}

$t->printHTMLFooter();

/***************************************************************/
function group_select() {
	$groups = get_groups();
	$group = $_GET['group'];
	?>
	<script type="text/javascript">
	function changePage(e) {
		var index = e.selectedIndex;
		var group = e.options[index].value;
		var ahref = "billing_special.php?group=" + group;
		document.location.href = ahref;
	}
	</script>
	<div align="center">
	<select name="groupselect" id="groupselect" onChange="changePage(this)">
	<option value="">Select group</option>
	<?
	for ($i=0; $groups[$i]; $i++) {
		$cur = $groups[$i];
		$selected = $cur['groupid'] == $group ? ' selected' : '';
		echo "<option value=\"{$cur['groupid']}\"$selected>{$cur['group_name']}</option>\n";
	}
	echo '</select></div>';

}
function show_special_rates() {
	$g = get_special_rates();

	if (!$g) {
		echo '<h3 align="center">No special rates.</h3>';
		return;
	}

	?>
	<h3 align='center'>Special Rates for <?=$g[0]['groupName']?></h3>
	<table align='center' width='50%'>
	<tr class='bold'><td width='35%'>&nbsp;</td>
	<td width='20%'>&nbsp</td>
	<td width='35%'>&nbsp;</td>
	<td width='10%'>&nbsp;</td></tr>
	<?

	for ($i=0; $g[$i]; $i++) {
		$row = $g[$i];
		echo '<tr class="cellColor'.($i%2).'">';
		echo "<td>" . $row['fromName'] . "</td>";
		echo "<td class='bold'>From/To</td>";
		//echo "<td>" . $row['fromAddress'] . "</td>";
		//echo "<td>" . $row['fromCity'] . "</td>";
	//	echo "<td>" . $row['fromState'] . "</td>";
		echo "<td>" . $row['toName'] . "</td>";
	//	echo "<td>" . $row['toAddress'] . "</td>";
	//	echo "<td>" . $row['toCity'] . "</td>";
	//	echo "<td>" . $row['toState'] . "</td>";
		echo "<td>$" . $row['fare'] . "</td>";
		echo "</tr>\n";
	}
	echo '</table>';

}
function get_special_rates() {
	$group = $_GET['group'];
	$query = "select bg.group_name as groupName, fl.name as fromName, fl.address1 as fromAddress, fl.city as fromCity, fl.state as fromState, tl.name as toName, tl.address1 as toAddress, tl.city as toCity, tl.state as toState, bs.fare as fare 
		  from resources fl, resources tl, billing_special bs, billing_groups bg 
		  where bs.fromLoc = fl.machid and bs.toLoc = tl.machid 
		  and bs.groupid=bg.groupid
		  and bg.groupid=$group";
	$qresult = mysql_query($query);
	if (!mysql_num_rows($qresult))
		return false;
	$return = array();
	while ($row = mysql_fetch_assoc($qresult))
		$return[] = $row;

	return $return;
}
function get_groups() {
	$query = "select * from billing_groups order by group_name asc";
	$qresult = mysql_query($query);
	$return = array();
	while ($row = mysql_fetch_assoc($qresult))
		$return[] = $row;
	return $return;
}

?>
