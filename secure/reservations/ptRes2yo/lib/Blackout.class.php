<?php
/**
* Reservation class
* Provides access to reservation data
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 04-09-04
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
* ResDB class
*/
include_once('db/ResDB.class.php');
/**
* Reservation parent class
*/
include_once('Reservation.class.php');

class Blackout extends Reservation {

	/**
	* Constructor calls parent constructor, telling it is a blackout
	* @param string $id id of this blackout
	*/
	function Blackout($id = null) {
		$this->Reservation($id, true);
	}

	/**
	* Deletes the current blackout from the database
	* If this is a recurring blackout, it may delete all blackouts in group
	* @param boolean $del_recur whether to delete all recurring blackouts in this group
	*/
	function del_blackout($del_recur) {
		$this->del_res($del_recur);
	}

	/**
	* Add a new blackout to the database
	*  after verifying that the time is available
	* @param string $machid id of resource to reserve
	* @param float $start starting time of reservation
	* @param float $end ending time of reservation
	* @param array $repeat repeat reservation values
	* @param array $scheduleid scheduleid
	*/
	function add_blackout($machid, $start, $end, $repeat, $summary, $scheduleid) {
		$this->add_res($machid, 0, $start, $end, $repeat, null, null, $summary, $scheduleid);
	}

	/**
	* Modifies a current blackout, setting new start and end times
	*  or deleting it
	* @param int $start new start time
	* @param int $end new end time
	* @param bool $del whether to delete it or not
	* @param int $min minimum reservation time
	* @param int $max maximum reservation time
	* @param boolean $mod_recur whether to modify all recurring blackouts in this group
	*/
	function mod_blackout($start, $end, $del, $mod_recur, $summary) {
		$this->mod_res($start, $end, $del, null, null, $mod_recur, $summary);
	}

}
?>