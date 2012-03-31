<?php

/**

	To update schedule form:

		-admin.template.php

		-AuthDB insertMember (data is form data)

		-AuthDB update_user (data is form data)

*/

include_once('lib/Template.class.php');
include_once('lib/db/AdminDB.class.php');
include_once('templates/admin.template.php');



if ((!isset($_GET['read_only']) || !$_GET['read_only']) && $conf['app']['readOnlyDetails']) {
	// Make sure user is logged in
	if (!Auth::is_logged_in()) {
		Auth::print_login_msg();
	}
}

$t = new Template("Schedules",false,true);

if (isset($_POST['submit']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF'])) {

	$t->set_title(translate("Processing $Class"));
	$t->printHTMLHeader();
	$t->startMain("full");
}

else if ((isset($_GET['addExisting']) || isset($_POST['addExisting'])) && $_SESSION['role'] == 'a') {

	$t->set_title('Add Existing Schedule');
	$t->printHTMLHeader();
	$t->startMain("full");  // startMain with div class = full.  CSS to work with popup

	$db = new AdminDB();
	$groupid = $_SESSION['curGroup'];
	$memberid = $_SESSION['sessionID'];

	addExistingForm();
	if (isset($_POST['schedEmail'], $_POST['search'])) { /* Search */
		$email = trim($_POST['schedEmail']);
		$email = mysql_real_escape_string($email);
		$scheds = get_group_schedules($groupid, $memberid, $email);
		add_form($scheds, $groupid, $memberid);


	} else if (isset($_POST['scheduleid'], $_POST['add'])) { /* Add */
		add_scheds($memberid);
	}

} else {
	$t->set_title('Schedule Form');
	$t->printHTMLHeader();
	$t->startMain("full");

	$pager = new Pager();
	$scheds = array();
	$db = new AdminDB();

	$scheds[0] = $db->get_schedule_data($_GET['scheduleid']);

//	$loc_data = $db->get_resource_data($_GET['machid']);
	$type = $_GET['type'];
	$login = $db->get_login_data();
	$bill = $db->get_billing_data($login['groupid']);
	$grouplist = $db->get_grouplist();
	$notes = $db->getAttr($scheds[0]['memberid'], 7);
	$aEmail = $db->getAttr($scheds[0]['memberid'], 2);
	$paymentArray = $db->getPaymentOptions($scheds[0]['memberid']);
	print_newschedule_edit($scheds[0],$scheds,$type,&$pager,$login,$bill,$grouplist, $notes, $aEmail, $paymentArray);

}
// End main table
$t->endMain();

// Print HTML footer
$t->printHTMLFooterPopup();
//echo "footer";

/********************************/

function get_group_schedules($groupid, $memberid, $email) {
	if (!$groupid) return false;
	$query = "select s.scheduleid,
		  l.fname, l.lname, l.email
		  from schedules s join login l on l.memberid=s.scheduleTitle
		  join schedule_permission sp
			on s.scheduleid=sp.scheduleid
		  where l.email like '%$email%'
		  and sp.memberid <> '$memberid'
		  and sp.scheduleid not in
			(select scheduleid from schedule_permission
			 where memberid='$memberid')
		  and l.groupid=$groupid
		  AND (l.role='p' or l.role='v' or l.role='e')";
	$qresult = mysql_query($query);
	if (!mysql_num_rows($qresult)) return false;
	$return = array();
	while ($row = mysql_fetch_assoc($qresult))
		$return[] = $row;
	return $return;
}

function add_scheds($memberid) {

	$scheduleid = $_POST['scheduleid'];
	$query = "insert into schedule_permission values ('$scheduleid', '$memberid')";
	$qresult = mysql_query($query);
	$cnt = mysql_affected_rows();
	//echo "Added $cnt schedule".($cnt>1?'s':'').".";
	echo '<div align="center">Added 1 schedule.</div>';

}

function add_form($group, $groupid, $memberid) {

	$msg = !$groupid ? "This feature is only available to admins in registered billing groups." : (!$group ? "No matches." : "Matches:"); 
	?><div class="titlebar" align="left"><?=$msg?></div><?
	if (!$groupid || !$group) return;
	?>

	<form name="addScheds" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<input type="hidden" name="memberid" value="<?=$memberid?>">
	<input type="hidden" name="add" value="1">
	<input type="hidden" name="addExisting" value="1">
	<table border=0 cellspacing=2 cellpadding=2 width="100%">
		<tr style="font-size: 11px; font-weight: bold;">
			<td width="40%">Name</td>
			<td width="40%">Email</td>
			<td width="20%">&nbsp;</td>
		</tr>
	<?

	for ($i=0; $group[$i]; $i++) {
		$cur = $group[$i];
		$rowcolor = $i % 2 == 0 ? "background-color: #FAFAFA;" : "";
		?>
		<tr style="font-size: 11px;<?=$rowcolor?>">
		<td><?=$cur['fname']." ".$cur['lname']?></td>
		<td><?=$cur['email']?></td>
		<td>
		<input type="submit" class="button" name="add" value="Add">
		<input type="hidden" name="scheduleid" value="<?=$cur['scheduleid']?>"></td>
		</tr>
		<?
	}
	echo "</table></form>";
}
?>

