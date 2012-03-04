<?php/*** Provides interface for making all administrative database changes* @author Nick Korbel <lqqkout13@users.sourceforge.net>* @version 07-01-04* @package Admin** Copyright (C) 2003 - 2004 phpScheduleIt* License: GPL, see LICENSE*//*** Template class*/include_once('lib/Template.class.php');/*** Include Admin class*/include_once('lib/Admin.class.php');include_once('lib/Auth.class.php');/*** Include PHPMailer*///include_once('lib/PHPMailer.class.php');/*** Include User class*/include_once('lib/User.class.php');/*** Geocoding functions*/include_once('lib.php');// Make sure this is the admin and is being called from admin.phpif (false) {// (!Auth::isAdmin()) || (!strstr($_SERVER['HTTP_REFERER'],'admin.php')) ) {    CmnFns::do_error_box(translate('This is only accessable to the administrator') . '<br />'        . '<a href="ctrlpnl.php">' . translate('Back to My Control Panel') . '</a>');	die;}$db = new AdminDB();$tools = array (				'delFavDriver'	=> 'del_fav_driver',				'addFavDriver'	=> 'add_fav_driver',				'delReservation'	=> 'del_reservation',				'deleteUsers' => 'del_users',				'addResource'	=> 'add_resource',				'editResource'	=> 'edit_resource',				'delResource'	=> 'del_resource',				'togResource'	=> 'tog_resource',				'editPerms' =>	'edit_perms',				'resetPass' => 'reset_password',				'addSchedule'	=> 'add_schedule',				'editSchedule'	=> 'edit_schedule',				'delSchedule'	=> 'del_schedule',				'dfltSchedule'	=> 'set_default_schedule'				 );if(!isset($disableEvalHack)){$fn = isset($_POST['fn']) ? $_POST['fn'] : (isset($_GET['fn']) ? $_GET['fn'] : '');	// Set functionif (!isset($tools[$fn]) && !isset($tools[$fn])) {		// Validate tool	CmnFns::do_error_box(translate('Could not determine tool. Please return to My Control Panel and try again later.')				 . '<a href="ctrlpnl.php">' . translate('Back to My Control Panel') . '</a>');	die;}else {	if (isset($tools[$fn]))		eval($tools[$fn] . '();');}unset($fn, $tools);}/*** Adds a schedule to the database* @param none*/function add_schedule() {	global $db;	global $conf;	$msg = '';	//$schedule = check_schedule_data(CmnFns::cleanPostVals());	$auth = new Auth();	$data = CmnFns::cleanPostVals();		if (isset($data['admingroupid']))		$data['groupid'] = $data['admingroupid'];	unset($data['admingroupid']);	unset($data['billtype']);	$data['password'] = strtoupper(substr(uniqid(), -10)); 	$data['password2'] = $data['password'];	if(!empty($_SESSION['currentOrg']) && $_SESSION['role'] != 'm') {		$data['institution'] = $_SESSION['currentOrg'];	} else if ($_SESSION['role'] == 'm') {		$grouplist = $db->get_grouplist();		$data['institution'] = $grouplist[$data['groupid']];	}	$memberid = $auth->db->userExists($data['emailaddress']);	if($memberid != '' && !isset($data['noemail'])) {		print_fail('This user already exists.  Please check the email address');	} else {		$msg = $auth->do_register_user($data, $_SESSION['sessionID']);	}	CmnFns::write_log('Schedule added. ' . $schedule['scheduleTitle'], $_SESSION['sessionID']);	if ($msg)		echo "There was a problem creating your schedule: $msg. Please go back and correct any errors.<br>";	else		print_success_schedule('added');}/*** Edits schedule data* @param none*/function edit_schedule() {	global $db;	$auth = new Auth();	$msg = '';	$schedule = CmnFns::cleanPostVals();	unset($schedule['password'], $schedule['password2']);	//$schedule = check_schedule_data(CmnFns::cleanPostVals());	//$db->edit_schedule($schedule);	$msg = $auth->do_edit_user($schedule);	CmnFns::write_log('Schedule edited. ' . $schedule['scheduleTitle'] . ' ' . $schedule['scheduleid'], $_SESSION['sessionID']);	if(!empty($msg)) {		print_fail($msg);	} else {		print_success_schedule('modified');	}}/*** Deletes a list of resources* @param none*/function del_schedule() {	global $db;	$scheduleid = $_POST['scheduleid'];	// Make sure machids are checked	if (empty($scheduleid))		print_fail(translate('You did not select any schedules to delete.'));	$db->del_schedule(array($scheduleid));	print_success_schedule('deleted');}function set_default_schedule() {	global $db;	$db->set_default_schedule($_POST['scheduleid']);	CmnFns::write_log('Default schedule changed to ' . $_POST['scheduleid'], $_SESSION['sessionID']);	print_success();}/*** Deletes a list of users from the database* @param none*/function del_users() {	global $db;	// Make sure memberids are checked	if (empty($_POST['memberid']))		print_fail(translate('You did not select any members to delete.') . '<br />');	$result = $db->del_users($_POST['memberid']);	CmnFns::write_log('Users deleted. ' . join(', ', $_POST['memberid']), $_SESSION['sessionID']);//  include dirname(__FILE__).'/../../../config/paths.php';  if($_SESSION['sessionID'] == $_POST['memberid']){    session_destroy();//    setcookie('ID', 0, 0, '/', $cookiePath);  }  echo "refresh";//	print_success();}/*** Adds a resource to the database* @param array $resource*/function add_fav_driver(){	global $db;  if($db === null) $db = new AdminDB();  if($db->add_fave($_SESSION['sessionID'], $_POST['memberid'])){    echo "success";  } else {    echo "fail";  }}function del_reservation(){	global $db;  if($db === null) $db = new AdminDB();  if($db->delete_reservation($_POST['resid'])){    echo "success";  } else {    echo "fail";  }}function del_fav_driver(){	global $db;  if($db === null) $db = new AdminDB();  if($db->delete_fave($_SESSION['sessionID'], $_POST['memberid'])){    echo "success";  } else {    echo "fail";  }}function add_resource($resource = array(), $returnId = false){	global $db;  if($db === null) $db = new AdminDB();	global $conf;  if(!$resource) $resource = check_resource_data(CmnFns::cleanPostVals());  else $resource = check_resource_data($resource);	if ($resource['bypass'] != 'bypass') {		$location = $resource['address']				.", " . $resource['city']				.", " . $resource['state']				." " . $resource['zip'];		$locarray = getGPS($location);		$resource['lat'] = $locarray['lat'];		$resource['lon'] = $locarray['lon'];		$resource['location'] = $resource['address']." ".$resource['address2'].", ".$resource['city'].", ".$resource['state']." ".$resource['zip'];	}	/*********	* get memberid from form if possible	**********/	$memberid = null;	if ($_POST['scheduleid']) {		$member = $db->get_schedule_data($_POST['scheduleid']);		if ($member['memberid']) $memberid = $member['memberid'];	}      	if ((!$locarray['lat'] || !$locarray['lon']) && $resource['bypass'] != 'bypass' && !$returnId) {		print_fail_location($resource, 'c');    // HERE    print_resource_edit($resource);	} else {		$id = $db->add_resource($resource, $memberid);		if (isset($resource['autoAssign']))		// Automatically give all users permission to reserve this resource			$db->auto_assign($id);		CmnFns::write_log('Resource added. ' . $resource['name'], $_SESSION['sessionID']);    if($returnId)    {      return $id;    }    echo "success";//		print_success_location('created');	}}/*** Edits resource data* @param none*/function edit_resource() {	global $db;	$resource = check_resource_data(array_merge(CmnFns::cleanPostVals(), array('machid'=>$_REQUEST['machid'])));      	if ($resource['bypass'] != 'bypass') {		$location = $resource['address1'] 				.", " . $resource['city']				.", " . $resource['state']				." " . $resource['zip'];		$locarray = getGPS($location);		$resource['lat'] = $locarray['lat'];		$resource['lon'] = $locarray['lon'];		$resource['location'] = $location;	}	if ((!$locarray['lat'] || !$locarray['lon']) && $resource['bypass'] != 'bypass') {		print_fail_location($resource, 'm');	} else {		$db->edit_resource($resource);		if (isset($resource['autoAssign']))		// Automatically give all users permission to reserve this resource			$db->auto_assign($resource['machid']);		CmnFns::write_log('Resource editied. ' . $resource['name'] . ' ' . $resource['machid'], $_SESSION['sessionID']);    echo "success";//		print_success_location('modified');	}}/*** Deletes a list of resources* @param none*/function del_resource() {	global $db;	// Make sure machids are checked	if (empty($_POST['machid']))		print_fail(translate('You did not select any resources to delete.'));	$db->del_resource($_POST['machid']);	CmnFns::write_log('Resources deleted. ' . $_POST['machid'] .', '. $_SESSION['sessionID']);  echo "success";//	print_success_location('deleted');}/*** Toggles a resource active/inactive* @param none*/function tog_resource() {	global $db;	$db->tog_resource($_GET['machid'], $_GET['status']);	CmnFns::write_log('Resource ' . $_GET['machid'] . ' toggled on/off.', $_SESSION['sessionID']);	print_success();}/*** Validates schedule data* @param array $data array of data to validate* @return validated data*/function check_schedule_data($data) {	$rs = array();	$msg = array();	if (empty($data['scheduleTitle']))		array_push($msg, translate('Schedule title is required.'));	else		$rs['scheduleTitle'] = $data['scheduleTitle'];	if (intval($data['dayStart']) >= intval($data['dayEnd']))		array_push($msg, translate('Invalid start/end times'));	else {		$rs['dayStart']	= $data['dayStart'];		$rs['dayEnd']	= $data['dayEnd'];	}	$rs['weekDayStart']	= $data['weekDayStart'];	$rs['timeSpan'] = $data['timeSpan'];	$rs['isHidden'] = $data['isHidden'];	$rs['showSummary'] = $data['showSummary'];	if (empty($data['viewDays']) || $data['viewDays'] <= 0)		array_push($msg, translate('View days is required'));	else		$rs['viewDays'] = intval($data['viewDays']);	if ($data['dayOffset'] == '' || $data['dayOffset'] < 0)		array_push($msg, translate('Day offset is required'));	else		$rs['dayOffset'] = intval($data['dayOffset']);	if (empty($data['adminEmail']))		array_push($msg, translate('Admin email is required'));	else		$rs['adminEmail']	= $data['adminEmail'];	if (isset($data['scheduleid']))		$rs['scheduleid'] = $data['scheduleid'];	if (!empty($msg))		print_fail($msg, $data);	return $rs;}/*** Validates resource data* @param array $data array of data to validate* @return validated data*/function check_resource_data($data) {	$rs = array();	$msg = array();	$minRes = intval($data['minH'] * 60 + $data['minM']);	$maxRes = intval($data['maxH'] * 60 + $data['maxM']);	$data['minRes']	= $minRes;	$data['maxRes']	= $maxRes;	if (empty($data['name']))		array_push($msg, translate('Resource name is required.'));	else		$rs['name'] = $data['name'];	if (empty($data['scheduleid']))		array_push($msg, translate('Valid schedule must be selected'));	else		$rs['scheduleid'] = $data['scheduleid'];	if (intval($minRes) > intval($maxRes)) {		array_push($msg, translate('Minimum reservation length must be less than or equal to maximum reservation length.'));	}	else {		$rs['minRes']	= $minRes;		$rs['maxRes']	= $maxRes;	}	$rs['rphone']	= $data['rphone'];	$rs['location'] = $data['location'];	$rs['address1'] = isset($data['address1']) ? $data['address1'] : $data['address'];	$rs['address2'] = $data['address2'];	$rs['city'] = $data['city'];	$rs['state'] = $data['state'];	$rs['zip'] = $data['zip'];	$rs['notes']	= $data['notes'];	$rs['bypass'] = $data['bypass'];  $rs['type'] = $data['type'];	if (isset($data['autoAssign']))		$rs['autoAssign'] = $data['autoAssign'];	if (isset($data['machid']))		$rs['machid'] = $data['machid'];	if (!empty($msg))  {		print_fail($msg, $data);  }	return $rs;}/*** Edit user permissions for what resources they can reserve* @param none*/function edit_perms() {	global $db;	$db->clear_perms($_POST['memberid']);	$db->set_perms($_POST['memberid'], isset($_POST['machid']) ? $_POST['machid'] : array());	CmnFns::write_log('Permissions changed for user ' . $_POST['memberid'], $_SESSION['sessionID']);	if (isset($_POST['notify_user']))		send_perms_email($_POST['memberid']);	print_success();}/*** Sends a notification email to the user that thier permissions have been updated* @param string $memberid id of member* @param array $machids array of resource ids that the user now has permission on*/function send_perms_email($memberid) {	global $conf;	$adminEmail = $conf['app']['adminEmail'];	$appTitle = $conf['app']['title'];	$user = new User($memberid);	$perms = $user->get_perms();	$subject = $appTitle . ' ' . translate('Permissions Updated');	$msg = $user->get_fname() . ",\r\n"			. translate('Your permissions have been updated', array($appTitle)) . "\r\n\r\n";	$msg .= (empty($perms)) ? translate('You now do not have permission to use any resources.') . "\r\n" : translate('You now have permission to use the following resources') . "\r\n";	foreach ($perms as $val)		$msg .= $val . "\r\n";	// Add each resource name	$msg .= "\r\n" . translate('Please contact with any questions.', array($adminEmail));	$mailer = new PHPMailer();	$mailer->AddAddress($user->get_email(), $user->get_name());	$mailer->From = $adminEmail;	$mailer->FromName = $conf['app']['title'];	$mailer->Subject = $subject;	$mailer->Body = $msg;	$mailer->Send();}/*** Reset the password for a user* @param none*/function reset_password() {	global $db;	global $conf;	$data = CmnFns::cleanPostVals();	$password = empty( $data['password'] ) ? $conf['app']['defaultPassword'] : stripslashes($data['password']);	$db->reset_password($data['memberid'], $password);	if (isset($data['notify_user']))		send_pwdreset_email($data['memberid'], $password);	CmnFns::write_log('Password reset by admin for user ' . $_POST['memberid'], $_SESSION['sessionID']);	print_success();}/*** Send a notification email that the password has been reset* @param string $memberid id of member* @param string $password new password for user*/function send_pwdreset_email($memberid, $password) {	global $conf;	$adminEmail = $conf['app']['adminEmail'];	$appTitle = $conf['app']['title'];	$user = new User($memberid);	$subject = $appTitle . ' ' . translate('Password Reset');	$msg = $user->get_fname() . ",\r\n"			. translate_email('password_reset', $appTitle, $password, $appTitle, CmnFns::getScriptURL(), $adminEmail);	$mailer = new PHPMailer();	$mailer->AddAddress($user->get_email(), $user->get_name());	$mailer->From = $adminEmail;	$mailer->FromName = $conf['app']['title'];	$mailer->Subject = $subject;	$mailer->Body = $msg;	$mailer->Send();}/*** Prints a page with a message notifying the admin of a successful update* @param none*/function print_success() {//	$t = new Template('Update Successful');//	$t->printHTMLHeader();//	$t->startMain();//	echo '<script language="JavaScript" type="text/javascript">' . "\n"////			. 'window.opener.document.location.href = window.opener.document.URL;' . "\n"////			. '</script>';		$date_text = '';		for ($i = 0; $i < count($dates); $i++) {			$date_text .= CmnFns::formatDate($dates[$i]) . '<br/>';		}		CmnFns::do_message_box(translate('Your ' . $this->word . ' was successfully ' . $verb)					. (($this->type != 'd') ? ' ' . translate('for the follwing dates') . '<br /><br />' : '.')					. $date_text . '<br/><br/>'//					. '<a href="javascript: window.close();">Close</a>'					, 'width: 90%;');//	$t->endMain();//	$t->printHTMLFooter();}function print_success_location($verb) {//	$t = new Template('Update Successful');//	$t->printHTMLHeader();//	$t->startMain();//	echo '<script language="JavaScript" type="text/javascript">' . "\n"////			. 'window.opener.document.location.href = window.opener.document.URL;' . "\n"////			. '</script>';		CmnFns::do_message_box('Your Location was successfully ' . $verb					. '.'					. '<br/><br/>'//					. '<a href="javascript: window.close();">Close</a>'					, 'width: 90%;');//	$t->endMain();//	$t->printHTMLFooter();}function print_fail_location($resource, $type) {//	$t = new Template(translate('Update failed!'));//	$t->printHTMLHeader();//	$t->startMain();//	echo '<script language="JavaScript" type="text/javascript">' . "\n"////			. 'window.opener.document.location.href = window.opener.document.URL;' . "\n"////			. '</script>';//	$url = "location.php?type=$type&machid={$resource['machid']}&ts={$resource['ts']}"////			."&resid={$resource['resid']}&scheduleid={$resource['scheduleid']}"////			."&is_blackout=0&read_only={$resource['read_only']}"////			."&address1={$resource['address1']}&address2={$resource['address2']}"////			."&name={$resource['name']}&city={$resource['city']}"////			."&state={$resource['state']}&zip={$resource['zip']}"////			."&notes={$resource['notes']}&rphone={$resource['rphone']}";////	$bypass = "location.php?type=$type&machid={$resource['machid']}&ts={$resource['ts']}"////			."&resid={$resource['resid']}&scheduleid={$resource['scheduleid']}"////			."&is_blackout=0&read_only={$resource['read_only']}"////			."&address1={$resource['address1']}&address2={$resource['address2']}"////			."&name={$resource['name']}&city={$resource['city']}"////			."&state={$resource['state']}&zip={$resource['zip']}"////			."&notes={$resource['notes']}&rphone={$resource['rphone']}&bypass=bypass";		CmnFns::do_message_box("<center>There was a problem finding:<br/><b>{$resource['location']}</b>.<br/>&nbsp;<br/> "					. 'Please  double check that the <b>Address</b>, <b>City</b>, <b>State</b> and <b>Zip Code</b> are all correct. '					. '<br/>&nbsp;<br/>'//					. "<a href=\"$url\">Back</a><br/>&nbsp;<br/>"//					. "If you are still unsure of the exact address, you may "//					. "<a href=\"$bypass\">"//					. "click here</a> to enter the location and correct it later."					. "</center>"//					, 'width: 90%;'      );//	$t->endMain();//	$t->printHTMLFooter();}function print_success_schedule($verb) {//	$t = new Template('Update Successful');//	$t->printHTMLHeader();//	$t->startMain();//	echo '<script language="JavaScript" type="text/javascript">' . "\n"////			. 'window.opener.document.location.href = window.opener.document.URL;' . "\n"////			. '</script>';		CmnFns::do_message_box('Your Passenger Schedule was successfully ' . $verb					. '.'					. '<br/><br/>'//					. '<a href="javascript: window.close();">Close</a>'					, 'width: 90%;');//	$t->endMain();//	$t->printHTMLFooter();}/*** Prints a page notifiying the admin that the requirest failed.* It will also assign the data passed in to a session variable*  so it can be reinserted into the form that it came from* @param string or array $msg message(s) to print to user* @param array $data array of data to post back into the form*/function print_fail($msg, $data = null) {//  throw new Exception();	if (!is_array($msg))		$msg = array ($msg);	if (!empty($data))		$_SESSION['post'] = $data;//	$t = new Template(translate('Update failed!'));//	$t->printHTMLHeader();//	$t->printWelcome();//	$t->startMain();	CmnFns::do_error_box(translate('There were problems processing your request.') . '<br /><br />'			. '- ' . join('<br />- ', $msg) . '<br />'//			. '<br /><a href="' . $_SERVER['HTTP_REFERER'] . '">' . translate('Please go back and correct any errors.') . '</a>');			. '<br />'.translate('Please correct errors.'), '', false);//	$t->endMain();//	$t->printHTMLFooter();//	die;}?>
