<?php
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');
include_once('lib.php');
$t = new Template('Billing Group Manager');
$d = new DBEngine();
$g = get_groups();

if (isset($_GET['exportGroups'])) {
	CmnFns::export_excel_array($g, 'Billing Groups.xls');
}

if (!Auth::is_logged_in()) 
    Auth::print_login_msg();
else if (!isset($_SESSION['role']) || $_SESSION['role']!='m')
    Auth::doLogout();
$t->printHTMLHeader();
$t->linkbar();
echo '<div align="center">';
show_form();
$addmsg = '';
$mode = isset($_GET['mode'])?$_GET['mode']:(isset($_POST['mode'])?$_POST['mode']:false);
if ($mode == 'add')
	$addmsg = add_group($_POST);
if ($addmsg)
	echo '<div align="center" style="margin-bottom:20px">' . $addmsg . '</div>';

search_form();

if ($mode == 'search')
	do_search($_GET);
else if ($mode == 'manage') {
	edit_schedules();
	show_manager();
} else if ($mode == 'showadmins') {
	show_admins();
}

show_all_groups($g);

echo '</div>';
$t->printHTMLFooter();

//***********************************************************************
function show_admins() {
	$memberid = CmnFns::getOrPost('memberid');
	if (!$memberid) return; // fail more interestingly

	global $d;

	// get scheduleid
	$query = "select scheduleid from schedules where scheduleTitle=?";
	$vals = array($memberid);
	$scheduleid = $d->db->getOne($query, $vals);

	// get admins for this scheduleid

	$query = "select l.* from 
		  login l join schedule_permission sp
		  on sp.memberid=l.memberid
		  where sp.scheduleid=?";
	$vals = array($scheduleid);

	$result = $d->db->query($query, $vals);
	$d->check_for_error($result);

	$return = array();

	while ($row = $result->fetchRow()) {
		$return[] = $row;
	}

	//$return;

	?>
	<div style="width: 50%; margin-left: auto; margin-right: auto; margin-top:10px; margin-bottom:10px; border: 1px solid #EEE;">
	<div style="text-align: center; font-size: 16px; margin-bottom: 10px;">Users with admin access to <?=$memberid?></div>

	<table width="100%" cellspacing=1 cellpadding=2>
	<?

	for ($i=0; $return[$i]; $i++) {
		$cur = $return[$i];

		$name = $cur['fname']." ".$cur['lname'];
		$role = $cur['role'] == 'a' ? 'Admin' : 'Passenger';
		if ($cur['memberid'] == $memberid) $name = "<b>$name</b>";
		echo "<tr>
			<td>$name</td>
			<td>{$cur['email']}</td>
			<td>$role</td>
			</tr>";
	}

	?>
	</table>
	</div>
	<?

}
function show_all_groups($g) {

	?>
	<div style="margin-top: 2em;">
	<div style="text-align: center;">
	<a href="billgroups.php?exportGroups=1">Export list</a>
	</div>
	<table width="100%" cellspacing=1 cellpadding=2>
	<tr>
	<?

	foreach ($g[0] as $k => $v) {
		$display = str_replace("_", " ", $k);
		$display = ucwords($display);
		echo "<td><b>$display</b></td>";
	}
	echo "</tr>";

	for ($i=0; $g[$i]; $i++) {
		$cur = $g[$i];
		$bg = ($i % 2) ? "FFF" : "EEE";

		echo "<tr style=\"background-color: #$bg;\">";
		foreach ($cur as $k => $v) {
			echo "<td>$v</td>";
		}

		echo "</tr>";
	}

	echo "</table></div>";
}
function show_manager() {
	$memberid = isset($_GET['memberid'])?$_GET['memberid']:(isset($_POST['memberid'])?$_POST['memberid']:null);
	$groupid = isset($_GET['groupid'])?$_GET['groupid']:(isset($_POST['groupid'])?$_POST['groupid']:null);
	if ($memberid==null || $groupid==null) return;
	
	$sched = get_schedules($memberid, $groupid);
	$group = get_group_schedules($groupid, $memberid);
	$admin = admin_name($memberid);

	?>
	<div class="titlebar" align="left">Schedules for <?=$admin['fname']." ".$admin['lname']?></div>
	<form name="remScheds" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<input type="hidden" name="memberid" value="<?=$memberid?>">
	<input type="hidden" name="groupid" value="<?=$groupid?>">
	<input type="hidden" name="mode" value="manage">
	<input type="hidden" name="edit" value="remove">
	<table border=0 cellspacing=2 cellpadding=2 width="100%">
		<tr style="font-size: 11px; font-weight: bold;">
			<td width="40%">Name</td>
			<td width="40%">Email</td>
			<td width="10%">
			<input type="submit" class="button" value="Remove"></td>
		</tr>
	<?
	for ($i=0; $sched[$i]; $i++) {
		$cur = $sched[$i];
		$rowcolor = $i % 2 == 0 ? "background-color: #FAFAFA;" : "";
		$disabled = $admin['scheduleid'] == $cur['scheduleid'] ? ' disabled' : '';
		?>
		<tr style="font-size: 11px;<?=$rowcolor?>">
		<td><?=$cur['fname']." ".$cur['lname']?></td>
		<td><?=$cur['email']?></td>
		<td><input type="checkbox" name="<?=$cur['scheduleid']?>"<?=$disabled?>></td>
		</tr>
		<?
	}
	$nogrp = !$admin['groupid']?' No passengers available: This admin is not in a billing group.':'';
	?>
	</table></form>
	<br>
	<div class="titlebar" align="left">Passengers in <?=$admin['group_name']?> (#<?=$admin['groupid']?>)<?=$nogrp?></div>
	<form name="addScheds" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<input type="hidden" name="memberid" value="<?=$memberid?>">
	<input type="hidden" name="groupid" value="<?=$groupid?>">
	<input type="hidden" name="mode" value="manage">
	<input type="hidden" name="edit" value="add">
	<table border=0 cellspacing=2 cellpadding=2 width="100%">
		<tr style="font-size: 11px; font-weight: bold;">
			<td width="40%">Name</td>
			<td width="40%">Email</td>
			<td width="10%">
			<input type="submit" class="button" value="Add"></td>
		</tr>
	<?
	for ($i=0; $group[$i]; $i++) {
		$cur = $group[$i];
		$rowcolor = $i % 2 == 0 ? "background-color: #FAFAFA;" : "";
		?>
		<tr style="font-size: 11px;<?=$rowcolor?>">
		<td><?=$cur['fname']." ".$cur['lname']?></td>
		<td><?=$cur['email']?></td>
		<td><input type="checkbox" name="<?=$cur['scheduleid']?>"></td>
		</tr>
		<?
	}
	echo "</table></form>";
}
function edit_schedules() {
	if (!isset($_POST['edit'])) return;
	$scheds = array();
	foreach ($_POST as $k => $v) 
		if ($v == 'on') $scheds[] = "'$k'";
	if (!count($scheds)) return;
	
	if ($_POST['edit']=='remove')
		rem_scheds($scheds);	
	else if ($_POST['edit']=='add')
		add_scheds($scheds);
}
function rem_scheds($scheds) {
	$str = "(".implode(', ', $scheds).")";
	$query = "delete from schedule_permission
		  where memberid='{$_POST['memberid']}'
		  and scheduleid in $str";
	$qresult = mysql_query($query);	
	$cnt = mysql_affected_rows();
	echo "Removed $cnt schedule".($cnt>1?'s':'').".";
}
function add_scheds($scheds) {
	$qarr = array();
	foreach ($scheds as $v)
		$qarr[] = "($v, '{$_POST['memberid']}')";
	$qstr = implode(', ', $qarr);
	$query = "insert into schedule_permission values $qstr";
	$qresult = mysql_query($query);
	$cnt = mysql_affected_rows();
	echo "Added $cnt schedule".($cnt>1?'s':'').".";
}
function get_schedules($memberid, $groupid) {
	$query = 'SELECT distinct s.scheduleid as scheduleid, '
		.'l.fname as fname, l.lname as lname, '
		.'l.memberid as memberid, l.email as email, '
		.'l.role as role, '
		.'l.groupid as groupid, l.other as other, '
		.'l.institution as institution, l.position as position, l.phone as phone '
		.'FROM (schedule_permission sp, schedules s, login l) '
		.'WHERE l.memberid = s.scheduleTitle '
		.'AND s.scheduleid = sp.scheduleid '
		."AND sp.memberid='$memberid' "
		.'ORDER by lname, fname';

	$qresult = mysql_query($query);
	if (!mysql_num_rows($qresult)) echo "No schedules.";
	$return = array();
	while ($row = mysql_fetch_assoc($qresult)) 
		$return[] = $row;

	return $return;
}
function get_group_schedules($groupid, $memberid) {
	if (!$groupid) return;
	$query = "select distinct s.scheduleid,
		  l.fname, l.lname, l.email
		  from schedules s join login l on l.memberid=s.scheduleTitle 
		  join schedule_permission sp 
			on s.scheduleid=sp.scheduleid 
		  where sp.memberid <> '$memberid' 
		  and sp.scheduleid not in
			(select scheduleid from schedule_permission
			 where memberid='$memberid')
		  and l.groupid=$groupid
		  AND (l.role='p' or l.role='v' or l.role='e')
		  order by l.fname, l.lname"; 
	$qresult = mysql_query($query);
	if (!mysql_num_rows($qresult)) return;
	$return = array();
	while ($row = mysql_fetch_assoc($qresult))
		$return[] = $row;
	return $return;
}
function admin_name($memberid) {
	$query = "select l.*, s.scheduleid, b.group_name 
		  from login l join schedules s on l.memberid=s.scheduleTitle
		  left join billing_groups b on l.groupid=b.groupid 
		  where memberid='$memberid'";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);
	return $row;
}
function domain_exists($domain) {
	if (!$domain) return false;
	$query = "select groupid from billing_groups where domain='$domain'";
	$qresult = mysql_query($query);
	if (mysql_num_rows($qresult) > 0)
		return true;
	
	return false;
}
function name_exists($name) {
	if (!$name) return true;
	$name = mysql_real_escape_string($name);
	$query = "select groupid from billing_groups where group_name='$name'";
	$qresult = mysql_query($query);
	if (mysql_num_rows($qresult) > 0)
		return true;
	
	return false;
}
function show_form() {
	$groups = get_groups();
	?>
	<div align="center">
	<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
	<?
		//if (Auth::isBillingAdmin()) {
		if (1) {
	?>
	<td width="50%" align="center" valign="top">
	<h3 align="center">Add Billing Group</h3>
	<form name="driver" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<table border=0 cellspacing=2 cellpadding=2 width="100%">
	<tr style="font-size:11px;">
		<td width="50%" align="right">Group name:</td>
		<td width="50%" align="left"><input type="text" name="groupname" maxlength=64></td>
	</tr>
	<tr style="font-size:11px;">
		<td width="50%" align="right">Type:</td>
		<td width="50%" align="left">
			<select name="grouptype">
				<option value="c">Credit Card</option>
				<option value="d">Direct Bill</option>
				<option value="e">Umbrella CC + Email</option>
				<option value="p">Comp</option>
				<option value="u">Umbrella Credit Card</option>
				<option value="v">Vendor (deprecated)</option>
			</select>
		</td>
	</tr>
	<tr style="font-size:11px;">
		<td width="50%" align="right">Discount (%):</td>
		<td width="50%" align="left"><input type="text" name="discount" maxlength=3 size=2></td>
	</tr>
	<tr style="font-size:11px;">
		<td width="50%" align="right">Domain (optional; ex: planettran.com):</td>
		<td width="50%" align="left"><input type="text" name="domain"></td>
	</tr>
	<tr>
		<td width="50%" align="right">&nbsp;</td>
		<td width="50%" align="left"><input type="submit" value="Submit"></td>
	</tr>
	</table>
	<input type="hidden" name="mode" value="add">
	</form>
	</td> <?
		}
	?>
	<td width="50%" align="center" valign="top">
	<h3 align="center">Search Groups</h3>
	<form name="searchGroups" method="get" action="<?=$_SERVER['PHP_SELF']?>">
	<input type="text" name="group" value="<?=isset($_GET['group'])?stripslashes($_GET['group']):''?>">
	<input type="submit" value="Search">
	<input type="hidden" name="mode" value="groupsearch">
	</form>
	<? search_groups(); ?>
	</td>
	</tr>
	</table>
	<?
	//if ($_POST['mode']=='getgroup')
	//	show_groupform($_POST['groupid']);
	//print_r($_POST);
	?>	
	</div>
	<?
}
function search_groups() {
	if (!isset($_GET['group']) || empty($_GET['group']))
		return;
	$group = mysql_real_escape_string($_GET['group']);
	$query = "select * from billing_groups
		  where group_name like '%$group%'";
	$qresult = mysql_query($query);
	if (mysql_num_rows($qresult)==0) {
		echo "No results.";
		return;
	}
	?><table width="100%" cellspacing=0 cellpadding=0>
	<tr style="font-size: 11px; font-weight: bold">
	<td>Name</td>
	<td>Type</td>
	<td>Discount</td>
	<td>Domain</td>
	<td>ID</td>
	</tr>
	<?
	global $conf;
	while ($row = mysql_fetch_assoc($qresult)) {
		$path = 'bg_edit.php?groupid='.$row['groupid'];
		$link = "<a href=\"$path\">{$row['group_name']}</a>";
		?>	
		<tr style="font-size: 11px;">
		<td><?=$link?></td>
		<td><?=$row['type']?></td>
		<td><?=$row['discount']?>%</td>
		<td><?=$row['domain']?></td>
		<td><?=$row['groupid']?></td>
		</tr>
		<?
	}
	?></table><?
}
function show_groupform($groupid) {
	$query = "select * from billing_groups where groupid=$groupid";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);
	?>
	<div align="left">
	<form name="modGroup" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<input type="hidden" name="mode" value="groupedit">
	Name: <input type="text" name="group_name" value="<?=$row['group_name']?>">
	<br>
	Type: <select name="grouptype">
	  <option value="d" <?=($row['type']=='d'?'selected':'')?>>d</option>
	  <option value="u" <?=($row['type']=='u'?'selected':'')?>>u</option>
	  <option value="c" <?=($row['type']=='c'?'selected':'')?>>c</option>
	  <option value="g" <?=($row['type']=='g'?'selected':'')?>>g</option>
	</select>
	<br>
	Discount: <input type="text" name="discount" value="<?=$row['discount']?>"maxlength=3 size=2>
	<br>
	Domain: <input type="text" name="domain" value="<?=$row['domain']?>">
	</form>
	</div>
	<?
}
function get_groups() {
	$query = "select b.groupid, b.group_name, b.type, b.discount,
		  b.domain, b.price_type, b.vip_type,
		  concat(l.fname, ' ', l.lname) as created_by,
		  from_unixtime(b.created) as created
	 	  from billing_groups b left join login l on b.createdBy=l.memberid
		  order by b.group_name";
	$qresult = mysql_query($query);
	$return = array();
	while ($row = mysql_fetch_assoc($qresult))
		$return[] = $row;
	return $return;
}
function add_group($got) {
	$msg = '';
	if (!$got['groupname']|| !isset($got['discount']) || !$got['grouptype'])
		return('Missing something. Fill everything in.');
	array_walk($got, 'trim');
	$domain = $got['domain'] ? "'{$got['domain']}'" : 'NULL';
	$createdBy = $_SESSION['sessionID'];
	$created = time();

	if (domain_exists($got['domain']))
		return "That domain already exists.";
	if (name_exists($got['groupname']))
		return "A group with that name already exists.";
	$query = "INSERT INTO billing_groups (groupid, group_name, type, discount, domain, createdBy, created) "
			."VALUES ('', '{$got['groupname']}', '{$got['grouptype']}', '{$got['discount']}', $domain, '$createdBy', $created)";

	$result = mysql_query($query);
	check_for_dberror($result);

	$msg = "Created {$got['groupname']}, type {$got['grouptype']}, with a {$got['discount']}% discount.";
	return($msg);
}
function search_form() {
	?>
	<div align="center">
	<h3 align="center">Search Users</h3>
	<form name="search" method="get" action="<?=$_SERVER['PHP_SELF']?>">
		First name: <input type="text" name="firstname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Last name: <input type="text" name="lastname">
		<input type="submit" value="Search">
		<input type="hidden" name="mode" value="search">
	</form>

	</div>
	<?

}
function do_search($got) {
	if (!$got['firstname'] && !$got['lastname'])
		return;

	$query = "SELECT l.memberid as memberid, l.email as email,
		l.fname as fname, l.lname as lname, 
		l.institution as institution, l.groupid as groupid,
		l.role as role,
		b.discount as discount
		FROM login as l left join billing_groups as b
		on l.groupid=b.groupid 
		WHERE l.fname LIKE '%{$got['firstname']}%'
		AND l.lname LIKE '%{$got['lastname']}%'";

	$result = mysql_query($query);
	check_for_dberror($result);

	?>
	<table border=0 cellspacing=2 cellpadding=2 width="100%">
		<tr style="font-size: 11px; font-weight: bold;">
			<td width="25%">Name</td>
			<td width="25%">Email</td>
			<td width="20%">Institution</td>
			<td width="10%">Group ID</td>
			<td width="10%">Discount</td>
			<td width="10%">Schedules</td>
		</tr>
	<?
	/*
		$url = "edit_billgroup.php?memberid={$row['memberid']}&groupid={$row['groupid']}&name={$row['fname']}+{$row['lname']}&fn=fn";
		<a href="javascript: window.open('$url', 'billedit', 'width=375,height=125,scrollbars=no,location=no,menubar=no,toolbar=no,resizable=yes'); void(0);">
	*/
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$rowcolor = $i % 2 == 0 ? "background-color: #FAFAFA;" : "";
		if ($row['role']=='x') 
			$rowcolor="background-color: #FFB9B9;"; 
		else if ($row['role']=='a') {
			$manage = '<a href="'.$_SERVER['PHP_SELF'].'?memberid='.$row['memberid'].'&groupid='.$row['groupid'].'&mode=manage">Manage passengers</a>';	
		} else if ($row['role']=='p') {
			$manage = '<a href="'.$_SERVER['PHP_SELF'].'?memberid='.$row['memberid'].'&groupid='.$row['groupid'].'&mode=showadmins">Show admins</a>';	
		} else
			$manage = '';
		$url = "edit_billgroup.php?memberid={$row['memberid']}&groupid={$row['groupid']}&name={$row['fname']}+{$row['lname']}&fn=fn";
		?>
		<tr style="font-size: 11px; <?=$rowcolor?>">
		<td><a href="javascript: window.open('<?=$url?>', 'billedit', 'width=375,height=125,scrollbars=no,location=no,menubar=no,toolbar=no,resizable=yes'); void(0);">
			<?=$row['fname']?> <?=$row['lname']?></a></td>
		<td><?=$row['email']?></td>
		<td><?=$row['institution']?></td>
		<td><?=$row['groupid']?></td>
		<td><?=$row['discount']?></td>
		<td><?=$manage?></td>
		</tr>
		<?
		$i++;
	}
	echo '</table>';
}
function coupon_form() {
	?>
	<div align="center">
	<h3 align="center">Create Coupon</h3>
	<form name="coupon" method="get" action="<?=$_SERVER['PHP_SELF']?>">

	</form>
	<?
}
?>
