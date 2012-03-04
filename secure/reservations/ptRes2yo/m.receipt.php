<?php
/*
*	Send a receipt
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

$t = new Template();
$db = new DBEngine();
global $conf;

$user = new User($_SESSION['sessionID']);
$u = $user->get_user_data();

pdaheader('Send Receipt');
pdawelcome('receipt');

/*
* Do stuff here
*/



		/*    The email      */
/* line 52, below <div> and above <br>&nbsp;<br> 
<img src="cid:logo" alt="Planettran Logo" border=0>
*/

$msg = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
.hide{display:none}
p {margin-bottom: 1em;}
</style>
</head>
<body style="margin:0;padding:0"><div class="hide"></div><div style="margin:1ex; margin-left: 5em; margin-right: 5em; font-family: Verdana, sans-serif; font-size: 12px;">

<div>

<br>&nbsp;<br>
At PlanetTran, we strive to be the most reliable, convenient, safe, comfortable, and environmentally responsible ground transportation service available. We know we are only as valuable as your last ride, so we encourage you to let us know how it went by <a href="https://secure.planettran.com/reservations/ptRes2/survey.php?resid={$_GET['resid']}">
completing our quick survey</a>.
<br>&nbsp;<br>
We hope that you found the service valuable enough to refer your friends and colleagues, and encourage you to use our referral feature when you log into your profile at <a href="http://reservations.planettran.com">http://reservations.planettran.com</a>.

<br>&nbsp;<br>
Thanks again, we look forward to driving you soon.
<br>&nbsp;<br>
Regards,<br>
Seth Riney, President and Founder<br>
PlanetTran<br>
planettran.com
</div>

</div></body></html>
EOF;

/* 	New receipt 		*/
$filename = "receipt".time().".pdf";
$output = $conf['app']['temp_path'].$filename;

/* We're only sending a receipt, so only read from the DB */

$url = $conf['app']['weburi'].'/newReceipt.php?resid='.$_GET['resid']."&output=$output";
$discard = file($url);

$mailer = new PHPMailer();
$name = $u['fname'].' '.$u['lname'];
$mailer->AddAddress($u['email'], $name);
$mailer->AddAttachment($conf['app']['temp_path'] . $filename);
//$mailer->AddEmbeddedImage('/home/planet/www/secure/reservations/ptRes2/images/planettran_logo_new.jpg', 'logo', 'planettran_logo_new.jpg');
$mailer->IsHTML(true);
$mailer->Subject = "Your PlanetTran Receipt"; 
$mailer->From = "billing@planettran.com"; 
$mailer->FromName = "PlanetTran Billing"; 
$mailer->Body = $msg;
$test = $mailer->Send();
if (!$test) { echo "error: ". $mailer->ErrorInfo; }
unlink($conf['app']['temp_path'] . $filename);	

		/*
		if ($auth == "Y") {
			$result = $this->db->execute($q, $values);
			$this->check_for_error($result);
		}
		*/
?>

<div style="text-align: center">
Your receipt has been sent to <?=$u['email']?>.<br>&nbsp;<br>
<a href="m.history.php?mode=receipt">Return to History</a><br>
<a href="m.main.php">Return to Main</a>

<?

pdafooter();


?>
