<?php
/**
* This file contains the database class to work with the User class
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 06-17-04
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
* Provide functionality for getting and setting user data
*/
class UserDB extends DBEngine {

	/**
	* Return all data associated with this userid
	* @param string $userid id of user to find
	* @return array of user data
	*/
	function get_user_data($userid) {
		$result = $this->db->getRow('SELECT * FROM ' . $this->get_table('login') . ' WHERE memberid=?', array($userid));
		$this->check_for_error($result);
		
		if (count($result) <= 0) {
			$this->err_msg = translate('That record could not be found.');
			return false;
		}

		return $this->cleanRow($result);
	}

	function get_user_favorite_drivers($userid){
    /* @var $x DB_mysql */

		$query = "SELECT DISTINCT * FROM login
      WHERE memberid IN (SELECT driver FROM favorite_drivers WHERE memberid='%s')
    ";
    $query = sprintf($query, addslashes($userid));
		$result = mysql_query($query);

		$values = array();
		while ($row = mysql_fetch_assoc($result)){
			$values[] = $row;
    }

		return $values;
	}

  // FIXME: this should not be here
	function get_all_drivers(){
    /* @var $x DB_mysql */

//		$result = $this->db->query('SELECT login.* FROM login
//      RIGHT JOIN dinfo ON (login.memberid = dinfo.memberid)
//      ORDER BY login.fname, login.lname');
//		$this->check_for_error($result);

		$query = "SELECT login.* FROM login
      RIGHT JOIN dinfo ON (login.memberid = dinfo.memberid)
      ORDER BY login.fname, login.lname";
		$result = mysql_query($query);

		$values = array();
		while ($row = mysql_fetch_assoc($result)){
			$values[] = $row;
    }

		return $values;
	}

	/**
	* Return an array of this users permissions
	* If the user has permission to use a resource
	*  it's id will be an index in the array
	* @param string $userid id of user to look up
	* @return array of user permissions
	*/
	function get_user_perms($userid) {
		$return = array();
		
		$result = $this->db->query('SELECT p.*, m.name FROM ' . $this->get_table('permission') . ' as p, ' . $this->get_table('resources') . ' as m WHERE memberid=? AND p.machid=m.machid limit 1000', array($userid));
		$this->check_for_error($result);
		
		while ($rs = $result->fetchRow())
			$return[$rs['machid']] = $rs['name'];
		
		$result->free();
		
		return $return;
	
	}
	
	/**
	* Returns an array of email settings for a user
	* @param string $userid id of member to look up
	* @return array of settings for user email contacts
	*/
	function get_emails($userid) {
		$result = $this->db->getRow('SELECT e_add, e_mod, e_del, e_html FROM ' . $this->get_table('login') . ' WHERE memberid=?', array($userid));
		$this->check_for_error($result);
		
		if (count($result) <= 0) {
			$this->err_msg = translate('That record could not be found.');
			return false;
		}
		
		return $result;			
	}
	
	/**
	* Sets the user email preferences in the database
	* @param string $e_add email on new reservation creation
	* @param string $e_mod email on reservation modification
	* @param string $e_del email on reservation delete
	* @param string $e_html send email in html or plain text
	* @param string $userid userid who we are managing
	*/
	function set_emails($e_add, $e_mod, $e_del, $e_html, $userid) {
		$result = $this->db->query('UPDATE ' . $this->get_table('login')
						. ' SET e_add=?, '
						. 'e_mod=?, '
						. 'e_del=?, '
						. 'e_html=? '
						. 'WHERE memberid=?', array($e_add, $e_mod, $e_del, $e_html, $userid));
		
		$this->check_for_error($result);
	}
}
?>