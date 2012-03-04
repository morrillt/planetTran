<?php
include_once('lib/Template2.class.php');
include_once('/home/planet/www/secure/reservations/ptRes2/lib/Tools.class.php');
$t = new Template('Customer Service Form');
$d = new DBEngine();
if (!Auth::is_logged_in()) 
    Auth::print_login_msg();

$fields = display_array();

$t->printHTMLHeader();
$t->startMain();
echo '<div style="margin-left: auto; margin-right: auto; width: 50%;">';

if($_POST['do_report'])
	insert_issues($d);
else
	print_form($resid, $fields);

echo '</div>';
$t->endMain();
$t->printHTMLFooter();
/**********************************************************************/
function print_form($resid, $fields) {
	//$displayid = strtoupper(substr($resid, -6));
	
	?>
	<h3 style="font-size: 1.5em; margin-bottom: 1em; margin-top: .7em;">Customer Service report</h3>
	<form name="issues" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	Select issues to report:<br>&nbsp;<br>
	<table width="100%" cellspacing=0 cellpadding=0>
	<?
	$i = 1;
	foreach ($fields as $k => $v) {
		if ($i % 2) echo "<tr>";
		echo "<td><input type=\"checkbox\" name=\"$k\">$v</td>";
		if (!($i % 2)) echo "</tr>\n";
			
		$i++;	
	}
	// if there's an odd number of issues, end with a blank cell
	if (!($i % 2)) {
		echo "<td>&nbsp;</td></tr>";
	}
	echo '</table>';

	?>
	<br>Description of issue:<br>
	<textarea name="comments" rows=4 cols=40></textarea>
	<br><input type="submit" value="Submit">
	<input type="hidden" name="do_report" value="1">
	</form>
	<?
}
function insert_issues(&$d) {
	$issues_list = issues_array();
	$issues = 0;
	foreach ($_POST as $k => $v) {
		if (isset($issues_list[$k])) $issues += $issues_list[$k];
	}
	$comments = ($_POST['comments']) ? $_POST['comments'] : 'NULL';
	//$driver = ($_POST['driver']) ? $_POST['driver'] : 'NULL';
	$managerid = $_SESSION['sessionID'];
	$category = 'feedback';
	$fromsystem = 'feedback';
	
	$vals = array($managerid, $issues, $comments, $category,$fromsystem);
	$query = "insert into oti (resid,managerid,driver,issues,notes,time,open,closedby,category,fromsystem,assignedTo) 
		  values
		  (NULL,?,NULL,?,?,NOW(),1,NULL,?,?,NULL)";

	/*
	CmnFns::diagnose($_POST);
	echo $query;
	return;
	*/

	$q = $d->db->prepare($query);
	$result = $d->db->execute($q, $vals);
	$d->check_for_error($result);

	?><div style="text-align: center; padding: 2em; font-size: large;">
	Thank you! Your issue has been submitted to customer service.
	</div><?

	//if($qresult) echo "Issue submitted, issue ID ". mysql_insert_id();
	//else echo mysql_error();
	//mail_report($issues, $issues_list, $managerid);
}
function mail_report($issues, $issues_list, $managerid) {
	$display = display_array();
	$manager = Tools::get_name($managerid);
	$msg = "The following issues were reported:\n\n";
	
	foreach ($issues_list as $k => $v) {
		if ($v & $issues) $msg .= $display[$k]."\n";
	}

	$msg .= "\nComments: ".$_POST['comments']."\n\nCreated by $manager";

	include_once('lib/PHPMailer.class.php');
	$m = new PHPMailer();
	$m->AddAddress('seth@planettran.com');
	$m->AddAddress('matt@planettran.com');
	$m->Subject = "New OTI issue";
	$m->FromName = "OTI System";
	$m->Body = $msg;
	$m->Send();
}
function issues_array() {
	return Tools::get_cs_issues_array();
}
function display_array() {
	return Tools::get_cs_display_array();
}
function has_comments($resid) {
	if ($resid == 'all') return 0;
	$query = "select resid from oti where resid='$resid'";
	$qresult = mysql_query($query);
	return mysql_num_rows($qresult);
}
