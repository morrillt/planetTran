<?php
include_once('lib/Template.class.php');
$t = new Template();
$d = new DBEngine();

$id = $_GET['id'];
$msg = '';
if (!$member = get_member($id)) $msg = "There was an error trying to access this user's account. Please contact the administrator.";

$t->printHTMLHeader();
$t->startMain();

if ($msg) die($msg);
send_email($member);

echo '<div align="center">';
echo "A confirmation email has been sent to ".$member['email'].". Please follow the link in the email to complete registration.";
echo '</div>';

$t->endMain();
$t->printHTMLFooter();
/****************************************/
function send_email($data) {
	global $conf;
	$subject = 'Please confirm PlanetTran registration';
	$msg = Auth::confirm_email($data['id'],$data['fname'],$data['created']);
	$mailer = new PHPMailer();
	$mailer->IsHTML(true);
	$mailer->AddAddress($data['email'],$data['fname'].' '.$data['lname']);
	$mailer->AddBCC('matt@planettran.com');
	$mailer->From = $conf['app']['adminEmail'];
	$mailer->FromName = $conf['app']['title'];
	$mailer->Subject = $subject;
	$mailer->Body = $msg;
	$mailer->Send();
}
function get_member($id) {
	$query = "select email, fname, lname, created from login where memberid='$id'";
	$qresult = mysql_query($query);
	if (!$qresult || !mysql_num_rows($qresult))
		return false;
	if (mysql_num_rows($qresult) > 1)
		return false;
	$member = mysql_fetch_assoc($qresult);
	$member['id'] = $id;
	return $member;
}

?>
