<?php
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');
include_once('lib/PHPMailer.class.php');
if (!Auth::is_logged_in()) 
    Auth::print_login_msg();
else if (!Auth::isAdmin())
    Auth::print_login_msg();

$t = new Template('Turnaway Form');

$t->printHTMLHeader();
if (isset($_POST['send']) && $_POST['send'] == '1')
	send_report();
else
	print_form();
$t->printHTMLFooter();

/*******************************************************/
function print_form() {
	?>
	<h3 align="center">Turnaway Form</h3>
	<div align="center">
	Fill in the passenger name, email if they have a profile, and the reason we couldn't book their trip.
	<form name="massEmail" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<input type="hidden" name="send" value="1">
	<table width="100%" cellspacing=2 cellpadding=0>
	<tr>
	<td width="15%" align="right" valign="top">Name:</td>
	<td width="85%" align="left" valign="top"><input type="text" size=44 name="name"></td>
	</tr>
	<tr>
	<td width="15%" align="right" valign="top">Email:</td>
	<td width="85%" align="left" valign="top"><input type="text" size=44 name="email"></td>
	</tr>
	<tr>
	<td align="right" valign="top">Reason:</td>
	<td align="left" valign="top"><textarea name="reason" cols=38 rows=3></textarea></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="left"><input type="submit" value="Send"></td>
	</form>
	</div>
	<?
}
function send_report() {
	$m = new PHPMailer();
	$d = new DBEngine();
	$user = reporter();
	$userid = $user['memberid'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$reason = $_POST['reason'];

	if (!$name) {
		echo '<div align="center"><b>Name</b> is required.</div>';
		return;
	}

	$msg = "Name: $name\nEmail: $email\nReason: $reason\n\nFiled by ".$user['fname']." ".$user['lname'];

	$m->AddAddress('turnaway@planettran.com');
	$m->AddAddress('msobecky@gmail.com');
	$m->FromName = 'Turnaway Report';
	$m->Subject = 'Turnaway Report: '.$name;
	$m->Body = $msg;
	
	$m->Send();

	$query = "insert into turnaways (name, email, reason, createdBy, autoReason, timestamp)
		  values ('$name', '$email', '$reason', '$userid', 0, NOW())";
	$qresult = mysql_query($query);

	echo '<div align="center">Report sent.</div>';
}
function reporter() {
	$query = "select * from login where memberid='".$_SESSION['sessionID']."'";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);
	return $row;
}
?>
