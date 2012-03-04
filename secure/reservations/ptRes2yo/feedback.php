<?php
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');
$t = new Template('Send Feedback');
$d = new DBEngine($conf['db']['dbName']);
ini_set('display_errors','1');
$t->printHTMLHeader();
$t->startMain();
if(empty($_POST['message']))
	show_form();
else if (!$_SESSION['sessionID'])
	echo '<p align="center">Your session has expired. Please log in again.</p>';
else	{
	send_feedback();
	echo '<p align="center">Your feedback has been sent. Thank you.</p>';
}

$t->endMain();
$t->printHTMLFooter();
/* Functions *************************************************************/
function show_form() {
	?>
	<h3 align="center">Send Feedback</h3>
	<form name="feedback" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
	<table width="100%" border=0 cellspacing=2 cellpadding=2>
	<tr>
		<td style="font-size: 11px;" align="right" width="20%">Subject: </td>
		<td align="left" width="80%">
		<input style="width: 581px;" type="text" name="subject" maxlength="100">
		</td>
	</tr>
	<tr>
		<td style="font-size: 11px;" align="right">Feedback: </td>
		<td align="left">
		<textarea name="message" rows="15" cols="70"></textarea>
		</td>
	</tr>
	<tr>
		<td>&nbsp</td>
		<td align="left">
		<input type="submit" value="Send Feedback">
		</td>
	</tr>
	</table>
	</form>
	<?
}
function send_feedback() {
	$query = "select email, fname, lname, phone, institution
		from login where memberid='".$_SESSION['sessionID']."'";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);

	$to = 'feedback@planettran.com';
	$headers = 'From: '.$row['email'];
	$subject = 'Customer Feedback';
	$innersub = substr($_POST['subject'], 0, 70);
	$message = "Name: ".$row['fname']." ".$row['lname']."\n";
	$message .= "Phone: ".$row['phone']."\n";
	$message .= "Email: ".$row['email']."\n";
	$message .= "Institution: ".$row['institution']."\n\n";
	$message .= "Subject: ".$innersub."\n\n";
	$message .= wordwrap($_POST['message'], 70);
	
	mail($to, $subject, $message, $headers);
}
?>
