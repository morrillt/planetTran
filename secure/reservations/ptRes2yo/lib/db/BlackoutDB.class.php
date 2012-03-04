<?php
/**
* ResDB class
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
include_once('ResDB.class.php');

/**
* Provide all access to database to manage reservations
*/
class BlackoutDB extends ResDB {

	/**
	* Return all data about a given reservation
	* @param string $resid reservation id
	* @return array of all reservation data
	*/
	function get_blackout($blackoutid) {
		$return = array();
		
		$result = $this->db->getRow('SELECT * FROM ' . $this->get_table('reservations') . ' WHERE resid=?', array($blackoutid));
		$this->check_for_error($result);

		if (count($result) <= 0) {
			$this->err_msg = translate('That record could not be found.');
			return false;
		}

		return $this->cleanRow($result);
	}
	

	/**
	* Add a new reservation to the database
	* @param Object $res reservation that we are placing
	* @param boolean $is_parent if this is the parent reservation of a group of recurring reservations
	*/
	function add_blackout(&$blackout, $is_parent) {
		$id = $this->get_new_id();
		$values = array (
					$id,
					$blackout->get_machid(),
					$blackout->get_date(),
					$blackout->get_start(),
					$blackout->get_end()
				);
		array_push($values, ($is_parent ? $id : $blackout->get_parentid()));		// Push parentid
		$query = 'INSERT INTO ' . $this->get_table('reservations') . ' VALUES(?, ?, ?, ?, ?, ?)';
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);
		unset($values, $query);
		return $id;
	}


	/**
	* Modify current reservation time
	* If this reservation is part of a recurring group, all reservations in the
	*  group will be modified that havent already passed
	* @param Object $res reservation that we are modifying
	*/
	function mod_blackout(&$blackout) {
		$values = array (
					$blackout->get_start(),
					$blackout->get_end(),
					$blackout->get_id()
				);

		$query = 'UPDATE ' . $this->get_table('blackout')
                . ' SET startTime=?,'
                . ' endTime=?'
                . ' WHERE blackoutid=?';

		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);

		unset($values, $query);
	}

	/**
	* Deletes a reservation from the database
	* If this reservation is part of a recurring group, all reservations
	*  in the group will be deleted that havent already passed
	* @param string $id reservation id
	* @param string $parentid id of parent reservation
	* @param boolean $del_recur whether to delete recurring reservations or not
	* @param int $date timestamp of current date
	*/
	function del_blackout($id, $parentid, $del_recur, $date) {
		$values = array($id);
		$sql = 'DELETE FROM ' . $this->get_table('blackout') . ' WHERE blackoutid=?';
		if ($del_recur) {			// Delete all recurring reservations
			$sql .= ' OR parentid = ? OR blackoutid = ?';
			array_push($values, $parentid, $parentid);
		}
		$sql .= ' AND date >= ?';
		array_push($values, $date);
		$result = $this->db->query($sql, $values);
		$this->check_for_error($result);
	}

	/**
	* Get an array of all blackout ids and dates for a recurring group
	*  of blackouts, including the parent
	* @param string $parentid id of parent blackout for recurring group
	* @param int $date timestamp of current date
	* @return array of all reservation ids and dates
	*/
	function get_recur_ids($parentid, $date) {
		$return = array();

		$sql = 'SELECT blackoutid, date FROM '
				. $this->get_table('blackout')
				. ' WHERE (parentid = ?'
				. ' OR blackoutid = ?)'
				. ' AND date >= ?'
				. ' ORDER BY date ASC';
		$result = $this->db->query($sql, array($parentid, $parentid, $date));

		$this->check_for_error($result);

		if ($result->numRows() <= 0) {
			$this->err_msg = translate('This blackout is not recurring.');
			return false;
		}

		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}

		$result->free();

		return $return;
	}
}
?>