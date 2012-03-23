<?php
include_once('lib/Auth.class.php');
include_once('lib/DBEngine.class.php');
include_once('lib/Template.class.php');
include_once('lib/PHPMailer.class.php');
include('referral.lib.php');
if (!Auth::is_logged_in()) 
    Auth::print_login_msg();
$t = new Template(translate('Referrals | Reservations | PlanetTran'));
$t->printHTMLHeader('silo_reservations sn6');
$t->printNavReservations();
$t->startMain();

$d = new DBEngine();
$id = $_SESSION['sessionID'];
//$t->printWelcome();
$u = new User($_SESSION['sessionID']);
$name = $u->get_name();
echo '<div class="titlebar">Referrals for '.$name.'</div>';
if (isset($_POST['email']) && isset($_POST['memberid']))
	$err = process_referral($id);
else if (isset($_POST['reminder']))
	send_reminder($id);
else if (isset($_POST['delete']))
	delete_referral($id);
if (!empty($err))
	echo '<div align="center">'.$err.'</div>';
?>	<table width="100%" cellspacing=0 cellpadding=0>
	<tr class="tableBorder"><td> 
	<table width="100%" cellspacing=1 cellpadding=0>
	<tr><td width="100%" valign="top">
<?
show_referral_form();

if ($d->has_referrals($id)) {
	$rFares = $d->get_referred_fares($id);
	$coSaved = "The people you referred have saved ".$rFares['total_fare']." pounds of carbon so far by using PlanetTran instead of a normal taxi service!";
	$names = $d->get_referred_names($id);
} else
	$coSaved = "There is no one in your referral network yet.";

echo $coSaved;
echo '</td></tr><tr><td width="100%" valign="top">';

if ($d->has_referrals($id))
	show_refer_text($rFares['total_fare'], $names);
else
	echo '<div class="bold13pt" align="center">Your Referral Network</div>';
?>
</td></tr></table>
</td></tr></table>
<!--
  * Referrals must be made to a new customer without an existing PlanetTran account and must be made through the Referral Tool form shown above. Promo Coupons cannot be combined with any other codes or discounts. Promotion is only valid until April 30, 2010 and is subject to limited availablity. All referral coupons must be used by August 31st, 2010. You will receive your referral coupon once the new customer has completed a first trip. Referrals are only valid for new customers and the referred new customer must register their PlanetTran profile account using the link in the email generated by your referral.
-->
<?
$t->endMain();
$t->printHTMLFooter();

/*****************************************************************/
function show_refer_text($fare, $names) {
	?>
	<div class="basicText">
	<div class="bold13pt" align="center">Your Referral Network</div>
	<table width="100%" border=0 cellspacing=0 cellpadding=2>
	<tr style="font-weight: bold; background-color: #FEFEFE;">
		<td width="45%">User</td>
		<td width="20%">Status</td>
		<td width="20%">&nbsp;</td>
		<td width="15%">&nbsp;</td>
	</tr>
	<?
	for ($i = 0; $names[$i]; $i++) {
		$row = $names[$i];
		$fullname = $row['fname'] && $row['lname'] ? $row['fname']." ".$row['lname'] : $row['email'];
		$link = !$row['fname'] && !$row['lname'] ? '<form name="test" action="referrals.php" method="post"><input type="hidden" name="reminder" value="'.$row['email'].'"><div class="buttons"><button type="submit">Send Reminder</button></div></form>' : '&nbsp;';
		$status = $row['date'] ? '<span style="color: green;">Registered</span>' : '<span style="color: red;">Unregistered</span>';
		$delete = !$row['fname'] && !$row['lname'] ? '<form name="test" action="referrals.php" method="post"><input type="hidden" name="delete" value="'.$row['email'].'"><div class="buttons"><button type="submit">Delete</button></div></form>' : '&nbsp;';
		
		echo '<tr>';
		echo "<td>$fullname</td>
			<td>$status</td>
			<td>$link</td>
			<td align=\"right\">$delete</td>";
		echo "</tr>";	
	}
	
	echo '</table></div>';
}
function show_referral_form() {
	/* Old personal message box
	<tr>
	<td align="right">Optional personal message (1,000 characters max)</td>
	<td align="left">
	<textarea name="notes" rows=4 cols=36></textarea>
	</td>
	</tr>
	*/
	global $conf;
	?>
	<div class="basicText">
	<form name="referForm" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
	
	Spread the word about PlanetTran's uniquely valuable service to friends,
	colleagues, and other people in your network.  Their decrease in
 	greenhouse gas emissions will be reflected in your Impact summary.
 	<!-- You will receive a $10 PlanetTran coupon* for each person you refer!-->
	<br>&nbsp;<br>
	<div align="center">
	<table width="100%" border=0>
	<tr>
	<td align="right">Enter email: </td>
	<td align="left"><input type="text" name="email"></td>
	</tr>
	<tr>
	<td align="right">Name: </td>
	<td align="left">
		<input type="text" name="name">
	</td>
	</tr>
	<tr><td>&nbsp;</td>
	    <td align="left"><input type="submit" value="Send"></td>
	</tr>
	</table>
	</div>
	<input type="hidden" name="memberid" value="<?=$_SESSION['sessionID']?>">
	</form>
	<?
}
function delete_referral($id) {
	$email = $_POST['delete'];
	$query = "delete from referrals where email='$email'";
	$qresult = mysql_query($query);
}
function send_reminder($id) {
	global $conf;
	$email = $_POST['reminder'];
	$u = new User($_SESSION['sessionID']);

	$subject = 'Planettran Referral Program Reminder';
	$fromName = $u->get_name();
	$fromEmail = $u->get_email();
	$url = $conf['app']['weburi'].'/register2.php?rid='.$_SESSION['sessionID']."&emailaddress=$email";
	$msg = ref_email($url, $fromName, false, true);

	$p = new PHPMailer();
	$p->ClearAllRecipients();
	$p->AddAddress($email);
	$p->IsHTML(true);
	$p->From = $fromEmail;
	$p->FromName = $fromName;
	$p->Subject = $subject;
	$p->Body = $msg;
	$p->Send();

	
	echo '<div style="text-align: center; font-weight: bold; margin-top: 1em; margin-bottom: 1em;">An email reminder has been sent to '.$email.'.</div>';
}


function ref_email($url, $fromName, $notes = '', $reminder = false) {
	// If it's a reminder, set text 
	if ($reminder) {
		$notes = "<br /> <br />$fromName would like to remind you that they're a member of the Planettran referral program, and they'd like you to check it out!";
	} else if ($notes) {
	// If not, check for user-provided notes
		$notes = "<br /> <br />$fromName attached the following personal note:".'<div class="quote">'.$notes.'</div>';
	} else {
	// If no notes, send default message
		$notes = "<br /> <br />$fromName would like to invite you to participate in the Planettran referral program.";
	}
	$msg = <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <style type="text/css">
	<!--
	body {
		font-size: 11px;
    	font-family: Verdana, Arial, Helvetica, sans-serif;
		background-color: #F0F0F0;
	}
	a {
		color: #104E8B;
		text-decoration: none;
	}
	a:hover {
		color: #474747;
		text-decoration: underline;
	}
	table tr.header td {
		padding-top: 2px;
		padding-bottom: 2px;
		background-color: #CCCCCC;
		color: #000000;
		font-weight: bold;
		font-size: 10px;
		padding-left: 10px;
		padding-right: 10px;
		border-bottom: solid 1px #000000;
	}
	table tr.values td {
		padding-top: 2px;
		padding-bottom: 2px;
		border-bottom: solid 1px #000000;
		padding-left: 10px;
		padding-right: 10px;
		font-size: 10px;
	}
	.quote {
		margin: 10px;
		padding: 5px;
		color: #36648B;
		background-color: #FFFFFF;
	}
	-->
	</style>
</head>
<body>
This message was sent through the Planettran referral system by %s.
%s
<br/ > <br />
PlanetTran is the nation's first public auto service to utilize ultra fuel efficient hybrid vehicles exclusively. We provide individuals and organizations with scheduled car service in hybrid cars as an alternative to gaz-guzzling taxis and limousines.
<br /> <br />
To visit planettran.com and sign up, go to <a href="%s">%s</a>
</body>
</html>
EOF;

	$return = sprintf($msg, $fromName, $notes, $url, $url);
	return $return;
}
?>
