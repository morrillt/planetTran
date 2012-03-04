<?php
/**
* StatsDB class
* Provides all db functions for stats.php
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

class StatsDB extends DBEngine {

	var $scheduleid = '';
	
	/**
	* Gets the quick stats numbers for a given table
	* @param string $table table to look up
	* @return table record count
	*/
	function get_quick_stats($table) {
		$vals = array();
		$query = 'SELECT COUNT(*) as num FROM ' . $this->get_table($table);
		if ($table == 'reservations') {
			$query .= ' WHERE is_blackout <> 1 AND scheduleid = ?';
			$vals[0] = $this->scheduleid;
		}
		else if ($table == 'resources') {
			$query .= ' WHERE scheduleid = ?';
			$vals[0] = $this->scheduleid;
		}

		$result = $this->db->getRow($query, $vals);

		$this->check_for_error($result);
		
		return $result['num'];
	}
	
	/**
	* Gets all of the reservations for this schedule
	* @param none
	* @return array of all reservatoin data
	*/
	function get_all_stats() {
		$return = array();
		
		$query = 'SELECT res.*'
				. ' FROM ' . $this->get_table('reservations') . ' as res,'
				. $this->get_table('login') . ' as l'
				. ' WHERE res.memberid=l.memberid AND res.is_blackout <> 1 AND res.scheduleid = ?';

		$result = $this->db->query($query, array($this->scheduleid));
		$this->check_for_error($result);
	
		if ($result->numRows() <= 0) {
			$this->err_msg = translate('No results');
			return false;
		}
				
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		
		$result->free();
		
		return $return;
	}
	
	/**
	* Gets the list of resources for this schedule
	* @param none
	* @return array of resource data
	*/
	function get_resources() {
		$return = array();
		
		$query = 'SELECT machid, name FROM ' . $this->get_table('resources') . ' WHERE scheduleid = ? ORDER BY name';
		$result = $this->db->query($query, array($this->scheduleid));
		$this->check_for_error($result);
		
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		
		$result->free();
		
		return $return;
	}
}
?>