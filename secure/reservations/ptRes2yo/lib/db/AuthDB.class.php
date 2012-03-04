<?php 
/** 
* AuthDB class
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 07-31-04
* @package DBEngine
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/../..');
/**
* DBEngine class
*/
include_once(BASE_DIR . '/lib/DBEngine.class.php');

/** 
* Provide all database access/manipulation functionality
* @see DBEngine
*/
class AuthDB extends DBEngine {

	/**
	* Returns whether a user exists or not
	* @param string $email users email address
	* @return user's id or false if user does not exist
	*/
	function userExists($uname) {
		$data = array (strtolower($uname));
		$result = $this->db->getRow('SELECT memberid FROM ' . $this->get_table('login') . ' WHERE email=?', $data);
		$this->check_for_error($result);

		return (!empty($result['memberid']) && strlen($uname) > 0) ? $result['memberid'] : false;
	}

	/**
	* Check if a user exists in referrals
	*/
	function referredUserExists($email) {
		$data = array (strtolower($email));
		$result = $this->db->getRow('SELECT email FROM referrals WHERE email=?', $data);
		$this->check_for_error($result);

		return (!empty($result['email'])) ? $result['email'] : false;
	}

	/**
	* Returns whether the password associated with this username
	*  is correct or not
	* @param string $uname user name
	* @param string $pass password
	* @return whether password is correct or not
	*/
	function isPassword($uname, $pass) {
		$password = $this->make_password($pass);
		$data = array (strtolower($uname), $password);
		$result = $this->db->getRow('SELECT count(*) as num FROM ' . $this->get_table('login') . ' WHERE email=? AND password=?', $data);
		$this->check_for_error($result);

		return ($result['num'] > 0 );
	}

	/**
	* Update referrals with date and memberid
	* if this user was referred
	*/
	function update_referral($id, $email) {
		$query = "update referrals set
			  memberid='$id',
			  date=NOW()
			  where email='$email'";
		$qresult = mysql_query($query);
	}
	/**
	* Insert user into referrals table
	*/
	function insert_referral($memberid, $rid, $email) {
		$query = "insert into referrals (referrer, memberid, date, email)
			  values('$rid', '$memberid', NOW())";
		$qresult = mysql_query($query);

		/*
		$curid = $rid; 
		// Check for referrers higher up the tree
		$i = 1;
		while(true) {
			// If referrer shows up in the memberid column,
			// they were referred
			$query = "select referrer from referrals
				  where memberid='$curid'";
			$qresult = mysql_query($query);
			if (mysql_num_rows($qresult) < 1)
				break;

			//echo mysql_error();
			$row = mysql_fetch_assoc($qresult);
			$query = "insert into referrals (referrer, memberid)
				  values ('{$row['referrer']}','$memberid')";
			$qresult = mysql_query($query);
	
			//echo mysql_error();
			$curid = $row['referrer'];
			$i++;
			if ($i > 50) // Infinite loop safeguard
				break;	
		}
		*/
	}

	/**
	* Inserts a new user into the database
	* @param array $data user information to insert
	* @return new users id
	*/
	function insertMember($data, $time = 0) {
		$id = $this->get_new_id();
		if (!$time) $time = time();

		// Put data into a properly formatted array for insertion
		$to_insert = array();
		array_push($to_insert, $id);
		array_push($to_insert, strtolower($data['emailaddress']));
		array_push($to_insert, $this->make_password($data['password']));
		array_push($to_insert, $data['fname']);
		array_push($to_insert, $data['lname']);
		array_push($to_insert, $data['phone']);
		array_push($to_insert, $data['institution']);
		array_push($to_insert, $data['position']);
		array_push($to_insert, 'y');
		array_push($to_insert, 'y');
		array_push($to_insert, 'y');
		array_push($to_insert, 'y');
		$role = (isset($data['role']) ? $data['role'] : 'p');
		array_push($to_insert, $role);
		array_push($to_insert, $data['ccnum'].'+'.$data['expdate'].'+'.$data['cvv2']);
		array_push($to_insert, $data['groupid']);
		array_push($to_insert, $time);
		array_push($to_insert, (isset($data['trip_credit']) ? $data['trip_credit'] : 0));
		array_push($to_insert, (isset($data['promo_code']) ? $data['promo_code'] : null));
		$pricetype = isset($data['price_type'])?$data['price_type']:'z';
		array_push($to_insert, $pricetype);
		$first_res = '';
		$twitter_username = '';
		$permissions = 3;
		$recieve_texts = 1;
		if($data['groupid'] == false)
			$script = 1;
		else
			$script = 0;
		
		array_push($to_insert, $first_res);
		array_push($to_insert, $twitter_username);
		array_push($to_insert, $permissions);
		array_push($to_insert, $recieve_texts);
		array_push($to_insert, $script);

		array_push($to_insert, $data['phone_call']);
		array_push($to_insert, $data['phone_sms']);
//		array_push($to_insert, $data['groupid']);
//		array_push($to_insert, $data['role']);
		array_push($to_insert, $data['profile_notes']);

		$q = $this->db->prepare('INSERT INTO login
			 (memberid, email, password, fname, lname, phone, institution, position, e_add, e_mod, e_del, e_html, role, other, groupid, created, trip_credit, promo_code, price_type, first_res, twitter_username, permissions, recieve_texts, script, phone_call, phone_sms, profile_notes)
			 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		$result = $this->db->execute($q, $to_insert);
		$this->check_for_error($result);

		$attrs = array();
		if (isset($data['notes']) && trim($data['notes'])) {
			$attrs[] = 	array(	'tableid' => $id,
						'attrid' => 7,
					'attr_value' => $data['notes']);
		}
		if ($data['addAdminEmail'] == 'y') {
			$attrs[] = 	array(	'tableid' => $id,
						'attrid' => 2,
					'attr_value' => $data['aEmail']);
		}
		if (count($attrs)) $this->insertAttrs($attrs);
		return $id;
	}
	
	/*
	* Give user permission to use their group's default
	* locations, if any
	*/
	function assignVirtualAdmin($scheduleid, $groupid) {
		$q = "select memberid from login
		     where role='w' and groupid=$groupid";
		$qresult = mysql_query($q);
		if (!$qresult || mysql_num_rows($qresult) < 1)
			return;
		$row = mysql_fetch_assoc($qresult);
		$q = "insert into schedule_permission
		      values ('$scheduleid', '{$row['memberid']}')";
		$result = mysql_query($q);
	}

	function assign_user_schedule($sid, $id) {
		$to_insert = array();
		array_push($to_insert, $sid);
		array_push($to_insert, $id);

		$q = $this->db->prepare('INSERT INTO '. $this->get_table('schedule_permission'). ' VALUES (?, ?)');
		$result = $this->db->execute($q, $to_insert);
		$this->check_for_error($result);
		return;
	}

	/**
	* Updates user data
	* @param string $userid id of user to update
	* @param array $data array of new data
	*/
	function update_user($userid, $data) {
		$to_insert = array();

		array_push($to_insert, strtolower($data['emailaddress']));
		//array_push($to_insert, strtolower($data['emailaddress']));
		array_push($to_insert, $data['fname']);
		array_push($to_insert, $data['lname']);
		array_push($to_insert, $data['phone']);
		array_push($to_insert, $data['institution']);
		array_push($to_insert, $data['position']);

		// If CC and exp were entered, push that onto the array and
		// change sql to reflect that.
		if (!empty($data['ccnum']) && !empty($data['expdate'])) {
		    array_push($to_insert, $data['ccnum'].'+'.$data['expdate'].'+'.$data['cvv2']);
		    $othersql = ', login.other=?';
		} else {
			$othersql = '';
		}

		$sql = 'UPDATE login, schedules '
			. ' SET login.email=?,'
			. ' login.fname=?,'
			. ' login.lname=?,'
			. ' login.phone=?,'
			. ' login.institution=?,'
			. ' login.position=?'
			. $othersql;

		if (isset($data['password']) && !empty($data['password'])) {	// If they are changing passwords
			$sql .= ', login.password=?';
			array_push($to_insert, $this->make_password($data['password']));
		}

		if (isset($data['sendemail'])) {
			array_push($to_insert, ($data['sendemail']=='y'?1:0));
			$sql .= ', schedules.isHidden=?';
		}

		if (isset($data['admingroupid'])) {
			array_push($to_insert, $data['admingroupid']);
			$sql .= ', login.groupid=?';
		}
		else if (isset($data['groupid']))
		{
			if($data['groupid'] == false)
				$gpid = 0;
			else
				$gpid = $data['groupid'];

			array_push($to_insert, $gpid);
			$sql .= ', login.groupid=?';
		}
		if (isset($data['role'])) {
			array_push($to_insert, $data['role']);
			$sql .= ', login.role=?';
		}

		if (isset($data['price_type'])) {
			array_push($to_insert, $data['price_type']);
			$sql .= ', login.price_type=?';
		}


		if (isset($data['role'])) {
			array_push($to_insert, $data['role']);
			$sql .= ', login.role=?';
		}

		if (isset($data['groupid'])) {
			array_push($to_insert, $data['groupid']);
			$sql .= ', login.groupid=?';
		}

		if (isset($data['role'])) {
			array_push($to_insert, $data['role']);
			$sql .= ', login.role=?';
		}

		if (isset($data['profile_notes'])) {
			array_push($to_insert, $data['profile_notes']);
			$sql .= ', login.profile_notes=?';
		}


		if (isset($data['newpassword']) && !empty($data['newpassword'])) {
			array_push($to_insert, md5($data['newpassword']));
			$sql .= ', login.password=?';
		}

		if (isset($data['twitter_username'])) {
			array_push($to_insert, $data['twitter_username']);
			$sql .= ', login.twitter_username=?';
		}
		if (isset($data['recieve_texts'])) {
			array_push($to_insert, ($data['recieve_texts']=='y'?1:0));
			$sql .= ', login.recieve_texts=?';
		}
//		if (isset($data['groupid']) && $data['groupid'] == false)
//			$script = 1;
//		else
//			$script = 0;
//		$sql .= ', login.script=?';
//		array_push($to_insert, $script);
		/* Any extra fields must come before the memberid. **********/
		if (isset($data['manual']))
			array_push($to_insert, $data['memberid']);
		else
			array_push($to_insert, $userid);

		$sql .= ' WHERE login.memberid=? and login.memberid=schedules.scheduleTitle';

		$q = $this->db->prepare($sql);
		$result = $this->db->execute($q, $to_insert);
		$this->check_for_error($result);

		$attrs = array();
		if (isset($data['notes'])) {
			$attrs[] = 	array(	'tableid' => $data['memberid'],
						'attrid' => 7,
					'attr_value' => $data['notes']);
			$this->updateAttrs($attrs);
		}
		if ($data['addAdminEmail'] == 'y') {
			$attrs[] = 	array(	'tableid' => $data['memberid'],
						'attrid' => 2,
					'attr_value' => $data['aEmail']);
			$this->updateAttrs($attrs);
		} else if ($data['addAdminEmail'] == 'n') {
			$this->delAttr($data['memberid'], 2);
		}

	}

	/**
	* Updates user data
	* @param string $userid id of user to update
	* @param array $data array of new data
	*/
	function update_user_prefs($userid, $data) {
		$to_insert = array();

		$to_insert[] = $data['e_add'] ? 1 : 0;
		$to_insert[] = $data['e_mod'] ? 1 : 0;
		$to_insert[] = $data['e_del'] ? 1 : 0;
		$to_insert[] = $data['e_html'] ? 1 : 0;
		$to_insert[] = $data['recieve_texts'] ? 1 : 0;
		$to_insert[] = $data['phone_sms'] ? 1 : 0;
		$to_insert[] = $data['phone_call'] ? 1 : 0;

    $to_insert[] = $userid;


		$sql = $this->db->prepare('UPDATE login SET
      e_add = ?,
      e_mod = ?,
      e_del = ?,
      e_html = ?,
      recieve_texts = ?,
      phone_sms = ?,
      phone_call = ?
    WHERE login.memberid = ?');
    $result = $this->db->execute($sql, $to_insert);
    $this->check_for_error($result);
	}

	/**
	* Checks to make sure the user has a valid ID stored in a cookie
	* @param string $id id to check
	* @return whether the id is valid
	*/
	function verifyID($id) {
		$result = $this->db->getRow('SELECT count(*) as num FROM ' . $this->get_table('login') . ' WHERE memberid=?', array($id));
		$this->check_for_error($result);

		return ($result['num'] > 0 );
	}

	/**
	* Gives full resource permissions to a user upon registration
	* @param string $id id of user to auto assign
	*/
	function auto_assign($id, $groupid) {
		$values = array();
		$resources = $this->db->query('SELECT machid FROM ' . $this->get_table('resources') . ' WHERE groupid = ' . $groupid . ' OR autoAssign=1');
		$this->check_for_error($resources);
		while ($rs = $resources->fetchRow()) {
			array_push($values, array($id, $rs['machid']));
		}

		if (count($values) > 0 ) {
			$q = $this->db->prepare('INSERT INTO ' . $this->get_table('permission') . ' VALUES (?,?)');
			$result = $this->db->executeMultiple($q, $values);

			$this->check_for_error($result);
		}
		$resources->free();
	}

	/**
	* Check whether someone has a billing group and CC
	*
	*/
	function checkBillGroup($user = null, $email = null) {
	    // If logging in with a cookie, $email won't be set
		$condition = !empty($email) ? "email='$email'" : "memberid='$user'";
		$query = "SELECT groupid, other, email, role FROM login WHERE $condition";
		$result = mysql_query($query);
		$this->check_for_error($result);

		$bill = mysql_fetch_assoc($result);

		// Change this to check for superuser role
		if ($bill['role'] == 'm') {
			return true;
		}

		if ($bill['groupid']) {// We have a group ID, user is ok
			return true;
		}
		else {
			$cc = str_replace("+", "", $bill['other']);
			if ($cc) {// We have a CC, user is OK
				return true;
			}
			else	{// No group ID or CC, user not OK
				return false;
			}
		}
	}

	/**
	* Return list of groupids that have a domain attached
	*/
	function get_group_domain_list() {
		$query = "select groupid, domain
			  from billing_groups where domain is not null";
		$qresult = mysql_query($query);
		$return = array();
		while ($row = mysql_fetch_assoc($qresult)) 
			$return[] = $row;	
		return $return;
	}

	/**
	* Check whether group requires email verification (has domain)
	*/
	function requires_verification($groupid) {
		$query = "select domain from billing_groups
			  where groupid=$groupid";
		$qresult = mysql_query($query);
		if (!$qresult || !mysql_num_rows($qresult))
			return false;
		$row = mysql_fetch_assoc($qresult);
		return $row['domain'];

	}

	function insert_log($memberid, $mobile = null) {
		$type = $mobile ? 1 : 2;
		if (!$memberid) return false;
		$browser = substr($_SERVER['HTTP_USER_AGENT'], 0, 254);
		$vals = array($memberid, $type, $browser);
		$query = "insert into user_login_log
				(memberid, date, time, type, browser)
			values
				(?, NOW(), NOW(), ?, ?)";

		$result = $this->db->query($query, $vals);

	}
}
?>
