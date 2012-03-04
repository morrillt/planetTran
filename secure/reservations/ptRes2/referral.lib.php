<?php
function ref_email2($url, $fromName, $fromFname, $toName = '', $reminder = false) {
	$fromFname = ucwords($fromFname);
	$fromName = ucwords($fromName);
	if ($toName) {
		$a = explode(" ", $toName);
		$toName = ucwords($a[0]);
	}

	$msg = <<<EOF
<html>
<head>
</head>
<body>
<table width="675">
<tr><td>
<a href="http://www.planettran.com/"><img src="http://www.planettran.com/images/planettran_logo_new.jpg" border="0" /></a>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td><p>Dear $toName,<br>
<p><b>$fromName thinks that you would be interested in PlanetTran and has asked us to send this referral email to you.</b>  $fromFname uses our service and here's your chance to try us for your first time at <b>50% off!</b>
PlanetTran is the nation's first all <b>hybrid chauffeured car service</b> and is redefining the industry with SmartTransport in Greater Boston and San Francisco Bay Area.</p>
<img align=right src="http://www.planettran.com/img/savingsahead.jpg" width=200>
<p><b>What is PlanetTran SmartTransport?</b>
<ol>
<li><b>Green:</b> The most fuel efficient <b>all hybrid</b> car fleet available</li> 
<li><b>Best Value:</b> Significantly <b>less expensive</b> than traditional car service </li>
<li><b>Service:</b> Highest <b>professional</b> service standards </li>
<li><b>Technology:</b> Free in-Car wireless Internet, online booking, and e-Billing</li>
</ol>
<p>Here&rsquo;s how to take advantage of this offer:
<ol>
<li>Create a profile at <a href="$url">$url</a> </li>
<li>Create your first reservation </li>
<li>Use Promo Coupon Code REFERRED to receive 50% off your first trip</li>
</ol>
<p><b>Promo Coupon Code REFERRED</b> - <span style="font-size:11px;"> Coupon is only valid for first ride for new customers.  Sample flat regular rate from Boston area to Logan is about \$50 (about \$25 with the coupon code).   Visit <a href="http://www.planettran.com">beta.PlanetTran.com</a> to look up exact rates from both <b>Boston and San Francisco Area</b>, setup an account, and book a reservation.  Be sure to enter the promo code to get the introductory discount.  This coupon code is valid for a limited time, is in limited quantity, and is not valid with other discounts.  Feel free to call 888-756-8876 with any questions.</span>
 
<p>Yours truly, </p>
<img src = 'http://www.planettran.com/img/SethSig.JPG'>

<p>Seth Riney, Founder<br>
<b>PlanetTran - <i>Saving the Planet, One Ride at a Time</b></i>
</tr>
</table>
</body>
</html>

EOF;

	return $msg;
}
function do_insert($id, $email) {
	$query = "insert into referrals values
		  ('$id', NULL, '0000-00-00 00:00:00', '$email')"; 
	$qresult = mysql_query($query);
}
function process_referral($id) {
	global $conf;
	$email = addslashes($_POST['email']);
	$notes = substr($_POST['notes'], 0, 1000);
	$notes = str_replace("\n", "<br>", $notes);
	$err = '';
	if (empty($email) || !preg_match("/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/", $email))
		$err = "Please enter a valid email address.";

	$a = new Auth();
	if ($a->db->userExists($email))
		$err = "That user is already registered!";
	else if ($a->db->referredUserExists($email))
		$err = "That email address has already been referred!";
	else if (!$_POST['name'])
		$err = "Please enter a name or nickname for the person being referred.";

	if (!empty($err))
		return $err;

	do_insert($id, $email);

	$u = new User($_SESSION['sessionID']);

	$subject = 'PlanetTran Referral Program';
	$fromName = $u->get_name();
	$fromFname = $u->get_fname();
	$fromEmail = $u->get_email();
	$toName = $_POST['name'];
	$url = $conf['app']['weburi'].'/register2.php?rid='.$_SESSION['sessionID']."&emailaddress=$email";
	$msg = ref_email2($url, $fromName, $fromFname, $toName);

	$p = new PHPMailer();
	$p->ClearAllRecipients();
	$p->AddAddress($email);
	$p->IsHTML(true);
	$p->From = $fromEmail;
	$p->FromName = $fromName;
	$p->Subject = $subject;
	$p->Body = $msg;
	$p->Send();

	global $mobile;

	echo '<div style="text-align: center; font-weight: bold; margin-top: 1em; margin-bottom: 1em;">Referral sent!</div>';

	if ($mobile) {
		?>
		<div style="text-align: center;">
		<a href="m.profile.php">Return to Profile</a><br>
		<a href="m.main.php">Return to Main</a>
		</div>
		<?
	}
}
?>
