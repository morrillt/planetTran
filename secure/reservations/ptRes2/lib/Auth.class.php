<?php 
/** 
* Authorization and login functionality
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 08-26-04
* @package phpScheduleIt
*
* Copyright C 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');
/**
* Include AuthDB class
*/
include_once('db/AuthDB.class.php');
/**
* Include AuthDB class
*/
include_once('db/AdminDB.class.php');
/**
* Include User class
*/
include_once('User.class.php');
/**
* PHPMailer
*/
include_once('PHPMailer.class.php');
/**
* Include Auth template functions
*/
include_once(BASE_DIR . '/templates/auth.template.php');

require dirname(__FILE__).'/../../../../config/paths.php';

/**
* This class provides all authoritiative and verification
*  functionality, including login/logout, registration,
*  and user verification
*/
class Auth {
	var $is_loggedin = false;
	var	$login_msg = '';
	var $is_attempt = false;
	var $db;
	var $success;

	/**
	* Create a reference to the database class
	*  and start the session
	* @param none
	*/
	function Auth() {
		$this->db = new AuthDB();
	}

	/**
	* Check if user is the administrator
	* This function checks to see if the currently
	*  logged in user is the administrator, granting
	*  them special permissions
	* @param none
	* @return boolean whether the user is the admin
	*/
	function isAdmin() {
		if ($_SESSION['role']=='m')
			return true;
		return false;
	}
	function isEmployee() {
		if ($_SESSION['role']=='m' || $_SESSION['role']=='d')
			return true;
		return false;
	}

	/**
	* Check if user is a billing admin
	*/
	function isBillingAdmin() {
		if ($_SESSION['sessionID'] == 'glb461aff8158a7c' || // marilyn
		    $_SESSION['sessionID'] == 'glb449994bd9c421' || // matt
		    $_SESSION['sessionID'] == 'ssk425bec50e30e9' || // seth
		    $_SESSION['sessionID'] == 'glb4d026425ca3e4' || // lori 
		    $_SESSION['sessionID'] == 'glb4cffa46631103' || // jamie 
		    $_SESSION['sessionID'] == 'glb4bdb13a2b36e5' || // jason 
		    $_SESSION['sessionID'] == 'glb4ca9fce56bde0' || // scott 
		    $_SESSION['sessionID'] == 'glb4a8d560324cd7' || // w.rice 
		    $_SESSION['sessionID'] == 'glb46e866319d129' || //j.hamel 
		    $_SESSION['sessionID'] == 'glb4716506e587ee' || // nancy 
		    $_SESSION['sessionID'] == 'glb4ac35a7de947e')  // Sarah
			return true;
		return false;
	}

	/**
	* Check if user is allowed to book Meet and Greets
	*/
	function isMeetGreetAdmin() {
		if ($_SESSION['sessionID'] == 'glb4716506e587ee' ||
		    $_SESSION['sessionID'] == 'glb449994bd9c421' ||
		    $_SESSION['sessionID'] == 'glb4d026425ca3e4' || // lori 
		    $_SESSION['sessionID'] == 'ssk425bec50e30e9')
			return true;
		return false;
	}

	/**
	* Check if user is a super admin
	*/
	function isSuperAdmin() {
		if ($_SESSION['sessionID'] == 'glb449994bd9c421' || // matt
		    $_SESSION['sessionID'] == 'glb4a8d560324cd7' || // w.rice 
		    $_SESSION['sessionID'] == 'glb4ac35a7de947e' || // Sarah
		    $_SESSION['sessionID'] == 'glb46e866319d129' || //j.hamel 
		    $_SESSION['sessionID'] == 'glb4d026425ca3e4' || // lori 
		    $_SESSION['sessionID'] == 'glb4cffa46631103' || //jknight 
		    $_SESSION['sessionID'] == 'glb4d25d2ff7df55' || //jgiantsi 
		    $_SESSION['sessionID'] == 'glb4e2ef99fba2eb' || //lcoombs
		    $_SESSION['sessionID'] == 'glb4e6fb42d7051d' || //dbroadhead
		    $_SESSION['sessionID'] == 'glb4e7107d96d0e5' || //tris
		    $_SESSION['sessionID'] == 'glb4c4f2c0f2aaf7') // Kat
			return true;
		return false;
	}

	/**
	* Check user login
	* This function checks to see if the user has
	* a valid session set (if they are logged in)
	* @param none
	* @return boolean whether the user is logged in
	*/
	function is_logged_in() {
		return isset($_SESSION['sessionID']);
	}

	/**
	* Logs the user in
	* @param string $uname username
	* @param string $pass password
	* @param string $cookieVal y or n if we are using cookie
	* @param string $isCookie id value of user stored in the cookie
	* @param string $resume page to forward the user to after a login
	* @param string $lang language code to set
	* @return any error message that occured during login
	*/
	function doLogin($uname, $pass, $cookieVal = null, $isCookie = false, $resume = '', $lang = '', $loginAnyway=false, $idAnyway=false, $location=true) {
		global $conf;
		global $mobile;
		global $cookiePath;
		$msg = '';
		if (empty($resume)) {
			if ($mobile) 
				$resume = 'm.main.php';
			else
				$resume = 'reserve.php?type=r';
		} 

		$_SESSION['sessionID'] = null;
		$_SESSION['sessionName'] = null;
		$_SESSION['sessionAdmin'] = null;
		$_SESSION['role'] = null;
		$_SESSION['okphone'] = null;
		$_SESSION['curGroup'] = null;
		$_SESSION['classic'] = null;
		$_SESSION['permissions'] = null;

		$uname = stripslashes($uname);
		$pass = stripslashes($pass);

		$adminEmail = strtolower($conf['app']['adminEmail']);
		if(!$loginAnyway) {
		  if($isCookie !== false){		// Cookie is set
			  $id = $isCookie;
			  if ($this->db->verifyID($id)){
				  $ok_user = $ok_pass = true;
			  }
			  else {
				  setcookie('ID', '', time()-3600, '/', $cookiePath);	// Clear out all cookies
				  $msg .= translate('That cookie seems to be invalid') . '<br/>';
			  }
		  }
		  else {
			  // If we cant find email, set message and flag
			  if ( !$id = $this->db->userExists($uname) ) {
				  $msg .= translate('We could not find that email in our database.') . '<br/>';
				  $ok_user = false;
			  }
			  else
				  $ok_user = true;

			  // If password is incorrect, set message and flag
			  if ($ok_user && !$this->db->isPassword($uname, $pass)) {
				  $msg .= translate('That password did not match the one in our database.') . '<br/>';
				  $ok_pass = false;
			  }
			  else
				  $ok_pass = true;
		  }

		  // If the login failed, notify the user and quit the app
		  if (!$ok_user || !$ok_pass) {
			  if ($mobile) return $msg;
			  $msg .= translate('You can try');
			  return $msg;
		  }
		} else {
		  $id = $idAnyway;
		}

		{
			$this->is_loggedin = true;
			$user = new User($id);	// Get user info
			$role = $user->get_role();

			// If the user wants to set a cookie, set it
			// for their ID and fname.  Expires in 30 days (2592000 seconds)
			if (!empty($cookieVal) && $role != 'm') {
				setcookie('ID', $user->get_id(), time() + 2592000, '/', $cookiePath);
			} else if ($cookieVal && $role == 'm') { // destroy cookies
				setcookie('ID', $user->get_id(), time() + 2592000, '/', $cookiePath);
			}

			 // If it is the admin, set session variable
			if ($user->get_role() == 'm') {
				$_SESSION['sessionAdmin'] = $adminEmail;
			}

			// Set other session variables
			$_SESSION['sessionID'] = $user->get_id();
			$_SESSION['currentID'] = $user->get_id();
			$_SESSION['currentName'] = $user->get_fname() . ' ' . $user->get_lname();
			$_SESSION['currentOrg'] = $user->get_inst();
			$_SESSION['sessionName'] = $user->get_fname();
			$_SESSION['role'] = $user->get_role();
			$_SESSION['curGroup'] = $user->get_groupid();
			$_SESSION['permissions'] = $user->get_permissions();

			if (!$user->get_phone())
				$_SESSION['okphone'] = false;

			if ($lang != '') {
				set_language($lang);
			}

			if ($user->get_role() == 'z') 
				$resume = 'index.php?error=1';

			$this->db->insert_log($id, $mobile);

			//CmnFns::redirect(urldecode($resume));
			if($location == true)
			  header('Location: ' . $resume);
		}
	}


	/**
	* Log the user out of the system
	* @param none
	*/
	function doLogout() {
    global $cookiePath;
		// Check for valid session
		if (!$this->is_logged_in()) {
			$this->print_login_msg();
			die;
		}
		else {
			// Destroy all session variables
			unset($_SESSION['sessionID']);
			unset($_SESSION['sessionName']);
			if (isset($_SESSION['sessionAdmin'])) unset($_SESSION['sessionAdmin']);
			session_destroy();

			// Clear out all cookies
			setcookie('ID', '', time()-3600, '/', $cookiePath);

			// Refresh page
			//CmnFns::redirect($_SERVER['PHP_SELF']);
//			header('Location: ' . $_SERVER['PHP_SELF']);
			header('Location: http://planettran.com/');
			//die;
		}
	}

	/**
	* Register a new user
	* This function will allow a new user to register.
	* It checks to make sure the email does not already
	* exist and then stores all user data in the login table.
	* It will also set a cookie if the user wants
	* @param array $data array of user data
	*/
	function do_register_user($data, $adminid) {
		global $conf;
		global $cookiePath;
		// Verify user data
		if (!empty($adminid))
			$msg = $this->check_all_values($data, false, true);
		else
			$msg = $this->check_all_values($data, false);
			
		if (!empty($msg)) {
			return $msg;
			//$this->print_register_form(false, $data, $msg);
			//return;
		}

		
		$adminEmail = strtolower($conf['app']['adminEmail']);
		$techEmail  = empty($conf['app']['techEmail']) ? translate('N/A') : $conf['app']['techEmail'];
		//$url        = CmnFns::getScriptURL();
		$url = $conf['app']['link'];

		// If not in a billing group, see if they ought to be
		if (!isset($data['groupid']) || !$data['groupid']) {
			if ($groupid=$this->groupid_from_domain($data['emailaddress']))
				$data['groupid'] = $groupid;
		}

		// Only start verification process if registering from
		// the register page
		$verify = isset($data['verify_groupid']) ? true : false;
		if ($verify) 
			$verify = $this->db->requires_verification($data['groupid']);
		// if verify, check that email is in same domain, fail if not
		if ($verify) {
			$msg = $this->verify_group_email($data['emailaddress'], $verify);
			$data['role'] = 'u';
		}
		if (!empty($msg))
			return $msg;

		// promo codes, trip credits

		if ($data['promo_code']) {

			if (strtolower($data['promo_code'])=='gogreen')
				$data['trip_credit'] = 100;
			else if (strtolower($data['promo_code'])=='greenited')
				$data['trip_credit'] = 10;
			else if (strtolower($data['promo_code'])=='summit')
				$data['trip_credit'] = 10;
		}

		// Register the new member
		$time = time();
		$id = $this->db->insertMember($data, $time);
		$schedDB = new AdminDB();
		$sched_data = array();
		$sched_data['scheduleTitle'] = $id;
		$sched_data['adminEmail'] = $adminEmail;
		$sched_data['sendemail'] = $data['sendemail'];
		$sid = $schedDB->add_schedule($sched_data);
		//associate the scheule with the user id of the admin
		$emailuser = 1;
		if($adminid != '' && $_SESSION['role'] != 'm') {
			$this->db->assign_user_schedule($sid, $adminid);
			//changePassword($data['email']);
			$noemail = 0;
		}
		$this->db->assign_user_schedule($sid, $id);
		//$this->db->auto_assign($id, $data['groupid']);		// Give permission on auto-assigned resources

		// Give user default locations, if any
		// admingroupid
		$groupid = isset($data['admingroupid'])?$data['admingroupid']:$data['groupid'];
		$this->db->assignVirtualAdmin($sid, $groupid);

		$mailer = new PHPMailer();
		$mailer->IsHTML(false);

		// Email user informing about successful registration
		$subject = $conf['ui']['welcome'];
		$translatePass = "register" . $data['fname'] . $conf['ui']['welcome'] . $data['fname'] . $data['lname'] .$data['phone'] . $data['institution'] . $data['position'] . $url . $adminEmail;  
		$msg = translate_email('register',
					$data['fname'], $conf['ui']['welcome'],
					$data['fname'], $data['lname'],
					$data['phone'],
					$data['institution'],
					$data['position'],
					$url,
					$adminEmail);

		$mailer->AddAddress($data['emailaddress'], $data['fname'] . ' ' . $data['lname']);
		$mailer->From = $adminEmail;
		$mailer->FromName = $conf['app']['title'];
		$mailer->Subject = $subject;
		$mailer->Body = $msg;
		//echo $msg;
		if($emailuser)
			$mailer->Send();

		// Email the admin informing about new user
		/*
		if ($conf['app']['emailAdmin']) {
			$subject = translate('A new user has been added');
			$msg = translate_email('register_admin',
								$data['emailaddress'],
								$data['fname'], $data['lname'],
								$data['phone'],
								$data['institution'],
								$data['position']);

			$mailer->ClearAllRecipients();
			$mailer->AddAddress($adminEmail);
			$mailer->Subject = $subject;
			$mailer->Body = $msg;
			$mailer->Send();
		}
		*/

		if($adminid == '') {
		// If the user wants to set a cookie, set it
		// for their ID and fname.  Expires in 30 days (2592000 seconds)
		if (isset($data['setCookie'])) {
			setcookie('ID', $id, time() + 2592000, '/', $cookiePath);
		}

		// If it is the admin, set session variable
		if ($data['emailaddress'] == $adminEmail) {
			$_SESSION['sessionAdmin'] = $adminEmail;
		}

		// Set other session variables
		$_SESSION['sessionID'] = $id;
		$_SESSION['sessionName'] = $data['fname'];
		$_SESSION['currentID'] = $id;
		$_SESSION['currentName'] = $data['fname']. ' ' . $data['lname'];
		$_SESSION['currentOrg'] = $data['institution'];
		$_SESSION['role'] = (isset($data['role']) ? $data['role'] : 'p');
		// Referred?
		if ($this->db->referredUserExists($data['emailaddress']))
			$this->db->update_referral($id, $data['emailaddress']);
		else if (isset($data['rid']))
			$this->db->insert_referral($id, $data['rid'], $data['emailaddress']);

		// Write log file
		CmnFns::write_log('New user registered. Data provided: fname- ' . $data['fname'] . ' lname- ' . $data['lname']
						. ' email- '. $data['emailaddress'] . ' phone- ' . $data['phone'] . ' institution- ' . $data['institution']
						. ' position- ' . $data['position'], $id);


		if (!$verify) {
			CmnFns::redirect('ctrlpnl.php?ui=new', 1, false);
			$link = CmnFns::getNewLink();

			$this->success = translate('You have successfully registered') . '<br/>' . $link->getLink('ctrlpnl.php', translate('Continue'));

		} else {
			$this->success = 'You have successfully registered.<br>'.
			'An email verification has been sent to the address you registered with.'.
			'You will need to follow the link in the email before you are able to make online reservations.';
			$subject = $conf['ui']['welcome'];
			$msg = $this->confirm_email($id, $data['fname'],$time,$data['role']);
			$mailer = new PHPMailer();
			$mailer->IsHTML(true);
			$mailer->AddAddress($data['emailaddress'], $data['fname'] . ' ' . $data['lname']);
			$mailer->From = $adminEmail;
			$mailer->FromName = $conf['app']['title'];
			$mailer->Subject = $subject;
			$mailer->Body = $msg;
			$mailer->Send();
		}
                
                return $id;

		}
		
		return "SUCCESS";	                			
		
	}

	function do_update_prefs(){
		global $conf;

    $data = CmnFns::cleanPostVals();

		$editID = isset($data['memberid']) ? $data['memberid'] : $_SESSION['sessionID'];
		$this->db->update_user_prefs($editID, $data);
	}

	/**
	* Edits user data
	* @param array $data array of user data
	*/
	function do_edit_user($data) {
		global $conf;

		// Verify user data
		$msg = $this->check_all_values($data, true);
		//if (!empty($data['ccnum']))
		if (!empty($msg)) {
			return $msg;//print_register_form(true, $data, $msg);
		}

		$editID = isset($data['memberid']) ? $data['memberid'] : $_SESSION['sessionID'];
		$this->db->update_user($editID, $data);

		if (!$data['phone'])
			$_SESSION['okphone'] = false;
		else
			$_SESSION['okphone'] = true;

		//$adminEmail = strtolower($conf['app']['adminEmail']);
		// If it is the admin, set session variable
		//if ($data['emailaddress'] == $adminEmail) {
		//	$_SESSION['sessionAdmin'] = $adminEmail;
		//}

		// Set other session variables
		if (strpos($_SERVER['PHP_SELF'], 'register.php')!==false)
			$_SESSION['sessionName'] = $data['fname'];

		// Write log file
		CmnFns::write_log('User data modified. Data provided: fname- ' . $data['fname'] . ' lname- ' . $data['lname']
						. ' email- '. $data['emailaddress'] . ' phone- ' . $data['phone'] . ' institution- ' . $data['institution']
						. ' position- ' . $data['position'], $_SESSION['sessionID']);
		$link = CmnFns::getNewLink();

		$this->success = translate('Your profile has been successfully updated!') . '<br/>';
//				. $link->getLink('ctrlpnl.php', translate('Please return to My Control Panel'));

	}


	/**
	* Verify that the user entered all data properly
	* @param array $data array of data to check
	* @param boolean $is_edit whether this is an edit or not
	*/
	function check_all_values(&$data, $is_edit, $isadmin = false) {
		$msg = '';

		if (!isset($data['noemail']))
			if (empty($data['emailaddress']) || !preg_match("/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/", $data['emailaddress']))
				$msg .= translate('Valid email address is required.') . '<br/>';
		if (empty($data['fname'])) {
			$msg .= translate('First name is required.') . '<br/>';
		}
		else {
			$data['fname'] = htmlspecialchars($data['fname']);
		}
		if (empty($data['lname'])) {
			$msg .= translate('Last name is required.') . '<br/>';
		}
		else {
			$data['lname'] = htmlspecialchars($data['lname']);
		}
		if (empty($data['phone'])) {
			$msg .= translate('Phone number is required.') . '<br/>';
		}
		else {
			$data['phone'] = htmlspecialchars($data['phone']);
		}
		if (!$isadmin) {
			// If registering without groupid, do all checks
			if(($data['groupid']<= 0||!$data['groupid']) && !$is_edit) {
				/*
				if (empty($data['ccnum'])) { // CC
					$msg .= '- Credit card number is required.<br/>';
				} else if (!$this->checkCreditCard($data['ccnum'])) {
					$msg .= '- That credit card number is not valid.<br/>';
				} else {
					$data['ccnum'] = htmlspecialchars($data['ccnum']);
				}
				if (empty($data['expdate'])) { // Exp date
					$msg .= '- Credit card expiration date is required.<br/>';
				} else if (!$this->checkCCDate($data['expdate'])) {
					$msg .= '- Expiration date must be current and in the format MM/YYYY.<br/>';
				} else {
					$data['expdate'] = htmlspecialchars($data['expdate']);
				}*/
			} else if ($data['groupid']<=0||!$data['groupid']) {
				// If editing without groupid, and a number was
				// entered, check it
				/*
				if (!empty($data['ccnum'])) {
					if (!$this->checkCreditCard($data['ccnum'])) {
						$msg .= '- That credit card number is not valid.<br/>';
					} else {
						$data['ccnum'] = htmlspecialchars($data['ccnum']);
					}

					if (empty($data['expdate'])) { 
						$msg .= '- Credit card expiration date is required.<br/>';
					} else if (!$this->checkCCDate($data['expdate'])) {
						$msg .= '- Expiration date must be current and in the format MM/YYYY.<br/>';
					} else {
						$data['expdate'] = htmlspecialchars($data['expdate']);
					}
				}
				*/

			} 
			/*
			if (empty($data['cvv2'])) { // CV2
				$msg .= '- CVV2 is required.<br/>';
	   		 else 
			*/
		}
	    	if (!empty($data['cvv2'])) {
				$data['cvv2'] = htmlspecialchars($data['cvv2']);
		}

		if (!empty($data['institution'])) {
			$data['institution'] = htmlspecialchars($data['institution']);
		}
		if (!empty($data['position'])) {
			$data['position'] = htmlspecialchars($data['position']);
		}

		// Make sure email isnt in database (and is not current users email)
		if ($is_edit) {
			$editID = isset($data['memberid']) ? $data['memberid'] : $_SESSION['sessionID'];
			$user = new User($editID);
			if ($this->db->userExists($data['emailaddress']) && $data['emailaddress'] != $user->get_email() && !isset($data['noemail']) ) {
				$msg .= translate('That email is taken already.') . '<br/>';
			}

			if (!empty($data['password'])) {
				if (strlen($data['password']) < 6)
					$msg .= translate('Min 6 character password is required.') . '<br/>';
				if ($data['password'] != $data['password2'])
					$msg .= translate('Passwords do not match.') . '<br/>';
			}

			unset($user);
		}
		else {
			if (empty($data['password'])) {
				return $msg;
			}
			if (strlen($data['password']) < 6)
				$msg .= translate('Min 6 character password is required.') . '<br/>';
			if ($data['password'] != $data['password2'])
				$msg .= translate('Passwords do not match.') . '<br/>';
			if ($this->db->userExists($data['emailaddress']) && !empty($data['emailaddress'])) {
				$msg .= translate('That email is taken already.') . '<br/>';
			}
		}

		return $msg;
	}

function checkCCDate($date, $resdate = null) {
	// Dates from the new system have a dash, not a slash
	if (strpos($date, '-') !== false)
		list ($year, $month) = explode("-", $date);
	else
		list ($month, $year) = explode("/", $date);
	if (strlen($year) == 2) $year += 2000;
	$m = date("m");
	$y = date("Y");

	// Check vs the current reservation date
	if ($resdate) {
		
		if($month == 12) {
			$addyear = 1;
			$addmonth = -11;
		} else {
			$addmonth = 1;
			$addyear = 0;
		}
			
		$chkdate = mktime(0,0,0,$month + $addmonth, 0, $year + $addyear);
		if ($chkdate < $resdate) return false;
	}

	if ($year < $y || $year > $y + 20)
		return false;
	else if ($month < 1 || $month > 12)
		return false;
	else if ($year == $y && $month < $m)
		return false;
	else
		return true;
}

function checkCreditCard($cardnumber) {
	  // Remove any spaces from the credit card number
	  //$cardNo = str_replace (' ', '', $cardnumber);
	  $cardNo = preg_replace('/\D/', '', $cardnumber);

	  // Check that the number is numeric and of the right sort of length.
	  if (!eregi('^[0-9]{15,16}$',$cardNo))  {
	     return false;
	  }

	  // Now check the modulus 10 check digit
	    $checksum = 0;                // running checksum total
	    $mychar = "";                 // next char to process
	    $j = 1;                       // takes value of 1 or 2

	    // Process each digit one by one starting at the right
	    for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {

	      // Extract the next digit and multiply by 1 or 2 on alternative digits.
	      $calc = $cardNo{$i} * $j;

	      // If the result is in two digits add 1 to the checksum total
	      if ($calc > 9) {
	        $checksum = $checksum + 1;
	        $calc = $calc - 10;
	      }

	      // Add the units element to the checksum total
	      $checksum = $checksum + $calc;

	      // Switch the value of j
	      if ($j ==1) {$j = 2;} else {$j = 1;};
	    }

	 // All done - if checksum is divisible by 10, it is a valid modulus 10.
	    // If not, report an error.
	    if ($checksum % 10 != 0) {
	     return false;
	    }

	  // Load an array with the valid prefixes for this card
	  $prefix = array(4,34,37,51,52,53,54,55,6011);

	  // Now see if any of them match what we have in the card number
	  $PrefixValid = false;
	  for ($i=0; $i<sizeof($prefix); $i++) {
	    $exp = '^' . $prefix[$i];
	    if (ereg($exp,$cardNo)) {
	      $PrefixValid = true;
	      break;
	    }
	  }

	  if (!$PrefixValid) {
	     return false;
	  }

	  // The credit card is in the required format.
	  return true;
	}

	/**
	* Check the list of billing group domains to see if
	* the email address matches
	*/
	function groupid_from_domain($email) {
		if (!preg_match('/@(.+\..+$)/', $email, $matches))
			return false;
		$groups = $this->db->get_group_domain_list();
		for ($i=0; $groups[$i]; $i++) {
			if ($groups[$i]['domain']==$matches[1])
				return $groups[$i]['groupid'];
		}
		return false;
	}

	/**
	* Match given email domain against group domain
	*/
	function verify_group_email($email, $gmail) {
		list(, $domain) = explode("@", $email, 2);
		if ($domain != $gmail) return "That email address does not match the address of the given billing group.";
		return '';
	}

	/**
	* Returns whether the user is attempting to log in
	* @param none
	* @return whether the user is attempting to log in
	*/
	function isAttempting() {
		return $this->is_attempt;
	}

	/**
	* Kills app
	* @param none
	*/
	function kill() {
		die;
	}

	/**
	* Destroy any lingering sessions
	* @param none
	*/
	function clean() {
		// Destroy all session variables
		unset($_SESSION['sessionID']);
		unset($_SESSION['sessionName']);
		if (isset($_SESSION['sessionAdmin'])) unset($_SESSION['sessionAdmin']);
		session_destroy();
	}

	/**
	* Wrapper function to call template 'print_register_form' function
	* @param boolean $edit whether this is an edit or a new register
	* @param array $data values to auto fill
	*/
	function print_register_form($edit, $data, $msg = '', $new = 0) {
		$favs = $this->db->get_favs($_SESSION['sessionID']);
		$paymentArray = $this->db->getPaymentOptions($_SESSION['sessionID']);
		print_register_form($edit, $data, $msg, $new, $favs, $paymentArray);	
	}


	/**
	* Wrapper function to call template 'printLoginForm' function
	* @param string $msg error messages to display for user
	* @param string $resume page to resume after a login
	*/
	function printLoginForm($msg = '', $resume = '') {
		printLoginForm($msg, $resume);
	}

	/**
	* Prints a message telling the user to log in
	* @param boolean $kill whether to end the program or not
	*/
	function print_login_msg($kill = true) {
		CmnFns::redirect(CmnFns::getScriptURL() . '/index.php?auth=no&resume=' . urlencode($_SERVER['PHP_SELF']) . '?' . urlencode($_SERVER['QUERY_STRING']));
	}

	/**
	* Prints out the latest success box
	* @param none
	*/
	function print_success_box() {
		CmnFns::do_message_box($this->success);
	}

	function has_permission($perm) {
		if ($_SESSION['permissions'] & $perm)
			return true;
		return false;
	}

	function confirm_email($id, $fname, $time, $role = '') {
		global $conf;
		$url = $conf['app']['weburi'] . "/confirm.php?id=$id&c=" . md5($time)."&r=$role";
		$ahref = "<a href=\"$url\">$url</a>";
		$msg = <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css" media="print">.hide{display:none}</style></head><body style="margin:0;padding:0"><div class="hide"></div><div style="margin:1ex; margin-left: 5em; margin-right: 5em; font-family: Verdana, sans-serif; font-size: 12px;">

<div>
<p>Hello %s, <br>
</p>
<p>Thank you for registering at planettran.com, the nation's first hybrid-only transportation service. To complete your registration, click the link below (or copy and paste into your browser's address bar): </p>
<p>
%s
</p>

</div>

</div></body></html>
EOF;
		$return = sprintf($msg, $fname, $ahref);
		return $return;
	}
}

/**
* Seed the random number generator
* @param none
* @return int seed
*/
function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}


/**
* Change user password
* This function creates a new random 8 character password,
*  sets it in the database and emails it to the user
* @return boolean true or false on success of function
* @see make_seed()
*/
function changePassword($emailaddress) {
    global $conf;
    $adminEmail = $conf['app']['adminEmail'];
	$title = $conf['app']['title'];

    // Connect to database
    $db = new DBEngine();
    $emailaddress = (empty($emailaddress)? $_POST['email_address'] : $emailaddress);
    // Check if user exists
    $email = stripslashes(trim($emailaddress));
	$result = $db->db->getRow('SELECT * FROM ' . $db->get_table('login') . ' WHERE email="' . $email . '"');

    // Check if error
    $db->check_for_error($result);

    if (count($result) <= 0) {
        CmnFns::do_error_box(translate('Sorry, we could not find that user in the database.'), '', false);
        return false;
    }

    // Generate new 8 character password by choosing random
    // ASCII characters between 48 and 122
    // (valid password characters)
    $pwd = '';
	$num = 0;

    for ($i = 0; $i < 8; $i++) {
        // Seed random for older versions of PHP
        mt_srand(make_seed());
        if ($i % 2 == 0)
			$num = mt_rand(97, 122);	// Lowercase letters
		else if ($i %3 == 0)
			$num = mt_rand(48, 58);		// Numbers and colon
		else
			$num = mt_rand(63, 90);		// Uppercase letters and '@ ?'
        // Put password together
        $pwd .= chr($num);
    }

    // Set password in database
    $query = 'UPDATE ' . $db->get_table('login') . ' SET password="' . $db->make_password($pwd) . '" WHERE memberid="' . $result['memberid'] . '"';

	$change = $db->db->query($query);

	$db->check_for_error($change);

    // Send email to user
    $sub = translate('Your New Password', array($title));

    $msg = translate_email('new_password', $result['fname'], $conf['app']['title'], $pwd, $conf['app']['link'], $adminEmail);

	// Send email
    $mailer = new PHPMailer();
	$mailer->AddAddress($result['email'], $result['fname']);
	$mailer->AddAddress('zielevitz+test@gmail.com');
	$mailer->AddAddress('kontakt@azielinski.info');
	$mailer->FromName = $conf['app']['title'];
	$mailer->From = $adminEmail;
	$mailer->Subject = $sub;
	$mailer->Body = $msg;
	$mailer->Send();
    return true;
}
?>
