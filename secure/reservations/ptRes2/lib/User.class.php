<?php
/**
* This file contains the User class for viewing
*  and manipulating user data
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 11-08-03
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');
/**
* UserDB class
*/
include_once('db/UserDB.class.php');

class User {
	var $userid;		// Properties
	var $email;			//
	var $fname;			//
	var $lname;			//
	var $phone;			//
  	var $inst;			//
  	var $position;		//
	var $perms;			//
	var $emails;		//
	var $groupid;		//
	var $other;
	var $is_valid = false;
	var $err_msg = null;
	var $db;
	var $trip_credit;
	var $twitter_username;
	var $permissions;
	var $recieve_texts;

	/**
	* Sets the userid variable
	* @param string $userid users id
	*/
	function User($userid = null) {
		$this->userid = $userid;
		$this->db = new UserDB();

		if (!empty($this->userid)) {		// Load values
			$this->load_by_id();
		}
	}

	/**
	* Returns all data associated with this user's profile
	*  using their ID as the identifier
	* @param none
	* @return array of user data
	*/
	function load_by_id() {
		$u = $this->db->get_user_data($this->userid);

		if (!$u) {
			$this->err_msg = $this->db->get_err();
			return;
		}
		else
			$this->is_valid = true;

		$this->fname	= $u['fname'];
		$this->lname	= $u['lname'];
		$this->email	= $u['email'];
		$this->phone	= $u['phone'];
		$this->inst		= $u['institution'];
		$this->position	= $u['position'];
		$this->role	= $u['role'];
		$this->groupid	= $u['groupid'];
		$this->other	= $u['other'];
		$this->perms = $this->get_perms();
		$this->emails = $this->get_emails();
		$this->trip_credit = $u['trip_credit'];
		$this->promo_code = $u['promo_code'];
		$this->first_res = $u['first_res'];
		$this->twitter_username = $u['twitter_username'];
		$this->permissions = $u['permissions'];
		$this->recieve_texts	= $u['recieve_texts'];
		unset($u);
	}

	function get_permissions() {
		return $this->permissions;
	}

	/**
	* Returns all permissions for this user
	* @param none
	* @return array of user permissions with the resource id as the key and 1 as the value
	*/
	function get_perms() {
		global $conf;
		return ($conf['app']['use_perms'] ? $this->db->get_user_perms($this->userid) : array());
	}

	/**
	* Checks if the user has permission to use a resource
	* @param string $machid id of resource to check
	* @return boolean whether user has permission or not
	*/
	function has_perm($machid) {
		global $conf;
		return ($conf['app']['use_perms'] ? isset($this->perms[$machid]) : true);
	}

	/**
	* Gets the email contact setup for this user
	* @param none
	* @return array of email settings
	*/
	function get_emails() {
		if (!$emails = $this->db->get_emails($this->userid))
			$this->err_msg = $this->db->get_err();
		return $emails;
	}

	/**
	* Returns whether the user wants the type of email contact or not
	* @param string $type email contact type.
	*  Valid types are 'e_add', 'e_mod', 'e_del' for adding/modifying/deleting reservations, respectively
	* @return boolean whether user wants the email or not
	*/
	function wants_email($type) {
		$return = false;
		$value = $this->emails[$type];
		if ($value == 'y' || $value == '1') {
			$return = true;
		}
		return $return;
	}

	/**
	* Whether the user wants html or plain text emails
	* @param none
	* @return whether they want html email or not
	*/
	function wants_html() {
		$return = false;
		$value = $this->emails['e_html'];
		if ($value == 'y' || $value == '1') {
			$return = true;
		}
		return $return;
	}

	/**
	* Sets the users email preferences
	* @param string $e_add value to set e_add field to
	* @param string $e_mod value to set e_mod field to
	* @param string $e_del value to set e_del field to
	* @param string $e_html value to set e_html field to
	*/
	function set_emails($e_add, $e_mod, $e_del, $e_html) {
		$this->db->set_emails($e_add, $e_mod, $e_del, $e_html, $this->userid);
	}

	/**
	* Return all user data in an array
	* @param none
	* @return assoc array of all user data
	*/
	function get_user_data() {
		return array (
				'memberid' 	=> $this->userid,
				'email'		=> $this->email,
				'fname'		=> $this->fname,
				'lname'		=> $this->lname,
				'phone'		=> $this->phone,
				'institution'=> $this->inst,
				'position'	=> $this->position,
				'perms'		=> $this->perms,
				'role'		=> $this->role,
				'groupid'	=> $this->groupid,
				'other'		=> $this->other,
				'trip_credit'	=> $this->trip_credit,
				'promo_code'	=> $this->promo_code,
				'first_res'	=> $this->first_res,
				'twitter_username'=> $this->twitter_username,
				'recieve_texts'	=> $this->recieve_texts
			);
	}

	/**
	* Returns whether this user is valid or not
	* @param none
	* @return boolean if user is valid or not
	*/
	function is_valid() {
		return $this->is_valid;
	}

	/**
	* Returns the error message generated
	* @param none
	* @return error message as string
	*/
	function get_error() {
		return $this->err_msg;
	}

	/**
	* Return this user's id
	* @param none
	* @return user id
	*/
	function get_id() {
		return $this->userid;
	}

	/**
	* Return the users first name
	* @param none
	* @return user first name
	*/
	function get_fname() {
		return $this->fname;
	}

	/**
	* Return the users last name
	* @param none
	* @return user last name
	*/
	function get_lname() {
		return $this->lname;
	}
	function get_role() {
		return $this->role;
	}
	function get_groupid() {
		return $this->groupid;
	}

	/**
	* Return the user's full name
	* @param none
	* @return the users full name as one string
	*/
	function get_name() {
		return $this->fname . ' ' . $this->lname;
	}

	/**
	* Returns the email address
	* @param none
	* @return email address of this user
	*/
	function get_email() {
		return $this->email;
	}

	/**
	* Returns user's phone
	* @param none
	* @return user's phone number as string
	*/
	function get_phone() {
		return $this->phone;
	}

	/**
	* Returns the users institution
	* @param none
	* @return user's institution
	*/
	function get_inst() {
		return $this->inst;
	}

	/**
	* Returns the user's position
	* @param none
	* @return user's position
	*/
	function get_position() {
		return $this->position;
	}

}
?>
