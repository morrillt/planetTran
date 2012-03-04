<?php
/*
*	Use this file as a template for new mobile pages
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');

/* skip login check
if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] .' '. $_GET['lname'];
	}
}
*/

$t = new Template();
$db = new DBEngine();
$user = new User($_SESSION['sessionID']);
$u = $user->get_user_data();
global $conf;

$back = $_GET['back'] ? $_GET['back'] : 'm.index.php';

pdaheader('Feedback');
pdawelcome('feedback', $back);
//CmnFns::diagnose($u);

/*
* Do stuff here
*/
$return = $conf['app']['weburi']. '/m.generalFeedback.php?submitted=1&back='.$back;
$formAddr = $conf['app']['domain']. '/dispatch/ptRes/feedback.php';

$submitted = (isset($_GET['submitted'])) ? true : false;
$msg = $mobilemsg = '';
if (isset($_GET['mobile'])) {
	$mobilemsg = '<div class="smallparagraph">We are continuously working to improve your experience with our mobile tools and appreciate any feedback. Please let us know about any bugs, feature requests, and/or other comments using this form.</div>';
} else
	$msg = "This form is for general inquiries only. To make, modify, or cancel a reservation, please log into your account or call our toll free number.";

if ($submitted) {
	echo '<div><div style="text-align: center;"><h3 style="text-align: center;" class="page-subheader">Your feedback has been submitted. Thank you!</h3><a href="'.$back.'">Back</a></div>';
} else {

	?>
	<div>
	<style type="text/css">
	input, label, select, textarea {
		margin-bottom: 5px;
	}
	</style>
	<?=$mobilemsg?>
	<form name="feedback" id="feedback" action="<?=$formAddr?>" method="post">
	<table width="100%" cellspacing=0 cellpadding=0 border=0 class="reservation">
	<tr>
		<td width="20%">Name</td><td width="80%"><input type="text" name="name" value="<?=$u['fname'].' '.$u['lname']?>"></td>
	</tr>
	<tr>
		<td>Email</td><td><input type="text" name="email" value="<?=$u['email']?>"></td>
	</tr>
	<tr>
		<td>Phone</td><td><input type="text" name="phone" value="<?=$u['phone']?>"></td>
	</tr>
	<tr>
		<td>Category</td>
		<td>
		<select name="issues">
		<?
		if (isset($_GET['mobile'])) {
			echo '<option value="8">Mobile</option>';
		} else {

			?>
			<option value="1">Sales</option>
			<option value="2">Customer Service</option>
			<option value="4">Billing</option>
			<option value="1073741824">General Inquiry</option>
			<?
		
		}
		
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td>Comments</td><td><textarea name="notes"></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
		<input type="hidden" name="return" value="<?=$return?>">
		<input type="hidden" name="isMobile" value="1">
		<input type="submit" value="Submit" style="color: #00652E;" class="button">
		</td>
	</tr>
	</table>
	</form>
	<div class="smallinfo">
	<?=$msg?>
	</div>
	<?

// end of our big else
}

echo '</div>';

pdafooter();


?>
