<?php
/*
* Send a referral through mobile interface
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('lib/PHPMailer.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');
include('referral.lib.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] .' '. $_GET['lname'];
	}
}

$t = new Template();
$db = new DBEngine();
$u = new User($_SESSION['sessionID']);
$id = $_SESSION['sessionID'];
$mobile = 1;

pdaheader('Referrals');
pdawelcome('referrals', 'm.profile.php');

if (isset($_POST['email']) && isset($_POST['memberid']))
	$err = process_referral($id);
else
	pda_referral_form();


/*
* Do stuff here
*/




pdafooter();
/********************************************************************/
function pda_referral_form() {
	global $conf;
	?>
	<div class="smallparagraph">
	<form name="referForm" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
	Spread the word about PlanetTran's uniquely valuable service to friends,
	colleagues, and other people in your network.  Their decrease in
	greenhouse gas emissions will be reflected in your Impact summary.
	</div>
	<div class="smallparagraph">
	Enter the email and name of the person you'd like to refer:
	</div>
	<div class="paragraph" style="text-align: center;">
	<table width="100%"  cellspacing=0 cellpadding=2 border=0>
	<tr>
		<td width="25%">Email:</td>
		<td width="75%"><input type="text" name="email"></td>
	</tr>
	<tr>
		<td>Name:</td>
		<td>
		<input type="text" name="name">
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	    	<td align="left"><input type="submit" value="Send"></td>
	</tr>
	</table>
	</div>
	<input type="hidden" name="memberid" value="<?=$_SESSION['sessionID']?>">
	</form>
	<?
}

?>
