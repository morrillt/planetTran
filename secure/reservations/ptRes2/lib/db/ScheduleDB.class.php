<?php
/**
* ScheduleDB class
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-14-04
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

define('BLACKOUT_ONLY', 1);			// Define constants
define('RESERVATION_ONLY', 2);
define('ALL', 3);
define('READ_ONLY', 4);

/**
* Provide all database access/manipulation functionality
* @see DBEngine
*/
class ScheduleDB extends DBEngine {
	var $scheduleType;
	var $scheduleid;
	
	function ScheduleDB($scheduleid, $scheduleType) {
		$this->DBEngine();				// Call parent constructor
		$this->scheduleType = $scheduleType;
		$this->scheduleid = $scheduleid;
	}
	
	/**
	* Get all reservation data
	* This function gets all reservation data
	* between a given start and end date
	* @param int $firstDay beginning date to return reservations from
	* @param int $lastDay beginning date to return reservations from
	* @param int $s_time start time of this schedules day
	* @param int $e_time end time of this schedules day
	* @return array of reservation data formatted: $array[date|machid][#] = array of data
	*  or an empty array
	*/
	function get_all_res($start, $end, $s_time, $e_time) {
		$return = array();
		
		$sql = 'SELECT res.* FROM ' . $this->get_table('reservations') . ' as res'
			. ' WHERE res.date>=?'
			. ' AND res.date<=?';
		
		if ($this->scheduleType == RESERVATION_ONLY)
			$sql .= ' AND res.is_blackout <> 1 ';
		else if ($this->scheduleType == BLACKOUT_ONLY)
			$sql .= ' AND res.is_blackout = 1 ';
		
		$sql .= ' AND scheduleid = ? ';
		
		//$sql .= ' AND startTime >= ? AND endTime <= ?';
		
		$sql .= ' ORDER BY res.date, res.startTime, res.endTime';

		$values = array($start, $end, $this->scheduleid);

		$p = $this->db->prepare($sql);
		$result = $this->db->execute($p, $values);
		
		$this->check_for_error($result);
		
		while ($rs = $result->fetchRow()) {
			$index = $rs['date'] . '|' . $rs['machid'];
			$return[$index][] = $rs;
		}
		
		$result->free();
		
		return $return;
	}
}
?>