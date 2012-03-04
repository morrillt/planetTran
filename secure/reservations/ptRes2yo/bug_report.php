<?php
/**
* Form to submit a bug report to the admins
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 11-08-03
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Template class
*/
include_once('lib/Template.class.php');
/**
* Bug report template functions
*/
include_once('templates/bug.template.php');

if (!Auth::is_logged_in()) {	// Make sure user is logged in
    Auth::print_login_msg();
}

$t = new Template('Submit a bug report');

$t->printHTMLHeader();

$t->startMain();

if (isset($_POST['sendBug']))
	sendBug();
else
	printBugForm();

$t->endMain();

$t->printHTMLFooter();


/**
* Get POST data and send bug report
* @param none
*/
function sendBug() {
	global $conf;
	
	$user = new User($_SESSION['sessionID']);
	$email = empty($_POST['email']) ? $user->get_email() : stripslashes(trim($_POST['email']));
	
	// Email user informing about successful registration
    $subject = 'System Bug Report: ' . date('Y-m-d H:i:s');
    $msg = 'Bug information: <br /><br/>'
		. 'Error description: <br/>' . nl2br(stripslashes($_POST['error_desc'])) . '<br/><br/>'
		. 'User activity at time of bug: <br/>' . nl2br(stripslashes($_POST['doing'])) . '<br/><br/>'
        . 'Error codes/messages: <br/>' . nl2br(stripslashes($_POST['error_msg'])) . '<br/><br/>'
		. 'Additional comments: <br/>' . nl2br(stripslashes($_POST['comments'])) . '<br/><br/>'
		. '===============================================<br/><br/>'
		. 'User information:<br/><br/>'
		. 'Memberid: ' . $user->get_id() . '<br/>'
		. $user->get_name() . '<br/>'
		. $user->get_email() . '<br/>'
		. $user->get_position() . ' @ ' . $user->get_inst() . '<br/>'
		. $user->get_phone() . '<br/><br/>'
		. '===============================================<br/><br/>'
		. 'System information:<br/><br/>'
		. 'Script name: ' . $_SERVER['PHP_SELF'] . '<br/>'
		. 'IP: ' . $_SERVER['REMOTE_ADDR'] . '<br/>';
	
	// Try to use get_browser() to get info
	//  if it is not available, just use user agent data
	$browser = @get_browser();

	if (is_array($browser)) {
		foreach ($browser as $name => $value) {
			$msg .= "$name: $value<br />\n";
		}
	}
	$msg .= 'Browser: ' . $_SERVER['HTTP_USER_AGENT'] . '<br />';
   
    // Send HTML email
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= 'Cc: ' . $conf['app']['adminEmail'] . ',' . $conf['app']['ccEmail'] . "\r\n";
    $headers .= 'From: ' . "$email\r\n";         
            
	@mail($conf['app']['techEmail'], $subject, $msg, $headers);
	
	print_thank_you();
}
?>