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
include_once(BASE_DIR . '/lib/DBEngine.class.php');

/**
* Provide all access to database to manage reservations
*/
class ResDB extends DBEngine {

	/**
	* Returns all data about a specific resource
	* @param string $machid id of resource to look up
	* @return array of all resource data
	*/
	function get_resource_data($machid) {
		$return = array();

		$result = $this->db->getRow('SELECT * FROM ' . $this->get_table('resources') . ' WHERE machid=?', array($machid));
		$this->check_for_error($result);
		// die(var_dump(count($result)));
		if (count($result) <= 0)
			$return = translate('That record could not be found.');
		else
			$return = $this->cleanRow($result);

		return $return;
	}

	/**
	* Return all data about a given reservation
	* @param string $resid reservation id
	* @return array of all reservation data
	*/
	function get_reservation($resid) {
		$return = array();

		$result = $this->db->getRow('SELECT * FROM reservations WHERE resid=?', array($resid));
		$this->check_for_error($result);

		if (count($result) <= 0) {
			// try { throw new Exception(); } catch(Exception $e) { print_r($e->getTrace()); die(); }
			$this->err_msg = translate('That record could not be found.');
			return false;
		}

		$return = $this->cleanRow($result);
		if (Auth::isAdmin()) 
			$return['dispNotes'] = $this->getHistory($resid);
		return $return;
	}

	/**
	* Checks to see if a given mach/date/start/end is already booked
	* @param Object $res reservation we are checking
	* @return whether time is taken or not
	*/
	function check_res(&$res) {
		return 0;
		$values = array (
					$res->get_machid(),
					$res->get_date(),
					$res->get_start(), $res->get_start(),
                    $res->get_end(), $res->get_end(),
                    $res->get_start(), $res->get_end(),
					$res->get_scheduleid()
				);

		$query = 'SELECT COUNT(resid) AS num FROM ' . $this->get_table('reservations')
				. ' WHERE machid=?'
				. ' AND date=?'
				. ' AND ( '
						. '( '
							. '(? >= startTime AND ? < endTime)'
							. ' OR '
							. '(? > startTime AND ? <= endTime)'
						. ' )'
						. ' OR '
						. '(? <= startTime AND ? >= endTime)'
				  .   ' )'
				. ' AND scheduleid = ? ';

		$id = $res->get_id();
		if ( !empty($id) ) {		// This is only if we need to check for a modification
			$query .= ' AND resid <> ?';
			array_push($values, $id);
		}
		$result = $this->db->getRow($query, $values);
		$this->check_for_error($result);
		return ($result['num'] > 0);	// Return if there are already reservations
	}

	/*
	* Log all reservation additions, modifications, and deletions
	*/
	function log_action($resid = null, $type = null) {
		$memberid = $_SESSION['sessionID'];
		global $saturn;

		if ($saturn) $memberid = 'glb46f43f55381db';

		if (!$resid || !$memberid || !$type) {
			//echo "res $resid mem $memberid type $type";
			return;

		}

		$time = time();
		$vals = array($resid, $memberid, $time, $type);

		$query = "insert into reservation_log (resid, memberid, timestamp, type)
			  values (?, ?, ?, ?)";

		$result = $this->db->query($query, $vals);
		//CmnFns::diagnose($result);

	}

	/**
	* Add a new reservation to the database
	* @param Object $res reservation that we are placing
	* @param boolean $is_parent if this is the parent reservation of a group of recurring reservations
	*/
	function add_res(&$res, $is_parent) {
		$id = $this->get_new_id();
		$values = array (
					$id,
					$res->get_machid(),
					$res->get_tolocation(),
					$res->get_memberid(),
					$res->get_scheduleid(),
					$res->get_date(),
					$res->get_start(),
					$res->get_end(),
					mktime(),
					null,
					($is_parent ? $id : $res->get_parentid()),
					intval($res->is_blackout),
					$res->get_summary(),
					// $res->get_special(),
					$res->get_flightDets(),
					intval($res->get_checkBags()),
					$res->get_specialItems(),
					$res->getManager(),
					NULL,
					$res->get_coupon(),
					$res->linkid,
					$res->mtype,
					$res->hour_estimate
				);
		global $saturn;
		if (Auth::isAdmin())
			$origin = 'p';
		else if (isset($_POST['ishail']))
			$origin = 'h';
		else if (isset($res->ismobile))
			$origin = 'm';
		else if ($saturn)
			$origin = 't';
		else
			$origin = 'w';

		$values[] = $origin;
		$values[] = $res->paymentProfileId;
		$values[] = $res->authWait;
		$values[] = $res->stopLoc;
		$values[] = intval($res->get_autoBillOverride());
		$values[] = $res->convertible_seats;
		$values[] = $res->booster_seats;

		$values[] = $res->regionID;
		$values[] = $res->vehicle_type;
		$values[] = $res->trip_type;
		$values[] = $res->passenger_count;
		$values[] = $res->meet_greet;
		$values[] = $res->estimate;

		$query = 'INSERT INTO reservations (resid, machid, toLocation, memberid, scheduleid, date, startTime, endTime, created, modified, parentid, is_blackout, summary,  flightDets, checkBags, special_items, createdBy, moddedBy, coupon_code, linkid, mtype, hour_estimate, origin, paymentProfileId, authWait, stopLoc, autoBillOverride, convertible_seats, booster_seats, regionID, vehicle_type, trip_type, passenger_count, meet_greet, estimate)
			  VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

    $q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
//echo '<pre>';
//print_r($result);
//echo '</pre>';
//die();
		$this->check_for_error($result);

		$this->log_action($id, 'r');

		if ($res->dispNotes)
			$this->insertHistory($id,$res->getManager(),$res->dispNotes);

		$values = ($id);
		$query = "insert into trip_log (resid, dispatch_status, pay_status, pay_type, driver, vehicle, distance, base_fare, discount, tolls, unpaid_tolls, other, total_fare, invoicee, notes, last_mod_time)
			  values
			  (?,27,28,30,1,4,0,0,0,0,0,'',0,'',NULL,NOW())";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);

		$this->update_messaging($id,$res->get_date());

		// update login first time user flag
		if (strrchr($res->specialItems, 'F')) {
			$query = "update login set first_res=? where memberid=?";
			$values = array($id, $res->memberid);
			$q = $this->db->prepare($query);
			$result = $this->db->execute($q, $values);
			$this->check_for_error($result);
		}

		if ($res->global_coupon)
			$this->increment_coupon_uses($res->get_coupon());

		unset($values, $query);
		return $id;
	}


	/**
	* Modify current reservation time
	* If this reservation is part of a recurring group, all reservations in the
	*  group will be modified that havent already passed
	* @param Object $res reservation that we are modifying
	* @param int $date todays timestamp
	*/
	function mod_res(&$res) {
		$coupon = !$res->get_coupon() ? null : $res->get_coupon();
		$values = array (
					$res->get_machid(),
					$res->get_tolocation(),
					$res->get_date(),
					$res->get_start(),
					$res->get_end(),
					mktime(),
					$res->get_summary(),
					$res->get_flightDets(),
					$res->get_checkBags(),
					$res->get_specialItems(),
					$res->getManager(),
					$coupon,
					$res->hour_estimate,
					$res->paymentProfileId,
					$res->authWait,
					$res->stopLoc,
					$res->get_autoBillOverride(),
					$res->convertible_seats,
					$res->booster_seats,
					$res->regionID,
					$res->vehicle_type,
					$res->trip_type,
					$res->passenger_count,
					$res->meet_greet,
					$res->estimate,
					$res->get_id()
				);

		$query = 'UPDATE reservations'
                . ' SET machid=?,'
		. ' toLocation=?,'
		. ' date=?,'
		. ' startTime=?,'
                . ' endTime=?,'
                . ' modified=?,'
		. ' summary=?,'
		. ' flightDets=?,'
		. ' checkBags=?,'
		. ' special_items=?,'
		. ' moddedBy=?,'
		. ' coupon_code=?,'
		. ' hour_estimate=?,'
		. ' paymentProfileId=?,'
		. ' authWait=?,'
		. ' stopLoc=?,'
		. ' autoBillOverride=?,'
		. ' convertible_seats=?,'
		. ' booster_seats=?,'
		. ' regionID=?,'
		. ' vehicle_type=?,'
		. ' trip_type=?,'
		. ' passenger_count=?,'
		. ' meet_greet=?,'
		. ' estimate=?'
                . ' WHERE resid=?';

		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);
		unset($values, $query);

		
		$this->log_action($res->get_id(), 'm');

		if ($res->dispNotes)
			$this->insertHistory($res->get_id(),$res->getManager(),$res->dispNotes);

		$this->delete_flight($res->get_id());
		$this->update_messaging($res->get_id(),$res->get_date());

		/* Only increment coupon if it's a global coupon AND
		   there was no existing coupon
		*/
		echo $res->global_coupon;
		if ($res->global_coupon && !$_POST['existing_coupon']) {
			$this->increment_coupon_uses($res->get_coupon());
		}

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
	function del_res($id, $parentid, $del_recur, $date) {
		$today = mktime(0,0,0);
		
		// Only delete if not same day res, otherwise just cancel

		if ($today != $date) {
			$modded = $_SESSION['sessionID'];
			$values = array(mktime(), $modded, $id);
			$sql = 'update reservations set
				is_blackout=1,
				modified=?,
				moddedBy=?
				where resid=?';

			$result = $this->db->query($sql, $values);
			$this->check_for_error($result);
		}

 		$values = array($id);
 		$sql = 'update trip_log set dispatch_status=9, last_mod_time=NOW()
 			where resid=?';
 
 		$result = $this->db->query($sql, $values);
 		$this->check_for_error($result);
		$this->delete_flight($id);


		$this->log_action($id, 'd');

		// only decrement global coupon if we have an existing global
		// coupon (it has already been incremented)

		if ($_POST['existing_coupon'])
			$this->decrement_coupon_uses($_POST['existing_coupon']);
	}

	/*
	*
	*/
	function add_link($linkid, $resid, $type) {
		$query = "insert into Reservation_Link (linkid, resid, type)
			  values (?, ?, ?)";
		$vals = array($linkid, $resid, $type);
		$result = $this->db->query($query, $vals);
		$this->check_for_error($result);
	}

	/*
	* Update the messaging table
	*/

	function update_messaging($resid,$date) {
		$query = "select l.email as profileEmail, admin.email as adminEmail,
		  res.summary as summary,
		  m.last_action_date as mdate, madmin.last_action_date as madmindate
		  from reservations res join login l on res.memberid=l.memberid
		  left join login admin on admin.memberid=res.createdBy
		  and admin.memberid!=res.memberid and admin.email not like '%planettran.com'
		  left join messaging m on m.email=l.email
		  left join messaging madmin on madmin.email=admin.email
		  where res.resid='$resid'
		  and l.email not like '%planettran.com'";


		$row = $this->db->getRow($query);
		//$qresult = mysql_query($query);
		//$row = mysql_fetch_assoc($qresult);
		//CmnFns::diagnose($row);
		$notes = $this->parseNotes($row['summary']);

		$query = "insert into messaging (email,last_action_date,optout)
			  values (?,?,0)
			  ON DUPLICATE KEY UPDATE
			  last_action_date=?";
		$q = $this->db->prepare($query);

		if ($row['profileEmail']) {
			if (!$row['mdate'] || ($row['mdate'] && $row['mdate']<$date))
				$update = $date;
			else
				$update = $row['mdate'];

			$values = array($row['profileEmail'],$date,$update);
			$result = $this->db->execute($q, $values);

			if (Auth::isSuperAdmin()) {
				$this->check_for_error($result);
			}
		}
		if ($row['adminEmail']) {
			if (!$row['madmindate'] || ($row['madmindate'] && $row['madmindate']<$date))
				$update = $date;
			else
				$update = $row['madmindate'];

			$values = array($row['adminEmail'], $date, $update);

			$result = $this->db->execute($q, $values);
		}
		if ($notes['email']) {
			$query = "select last_action_date as notesdate from messaging
				  where email=?";
			$values = array($notes['email']);	
			$row = $this->db->getRow($query, $values);
			if (Auth::isSuperAdmin())
				$this->check_for_error($row);

			if (!$row['notesdate'] || ($row['notesdate'] && $row['notesdate']<$date))
				$update = $date;
			else
				$update = $row['notesdate'];

			$values = array($notes['email'], $date, $update);
			$result = $this->db->execute($q, $values);
		}
		

	}

	/*
	* Delete a flight from the flights table
	*/
	function delete_flight($rsid) {
		$query = "DELETE from flights WHERE rsid='$rsid'";
		$qresult = mysql_query($query);
	}
	/**
	* Return all data needed in the emails
	* @param string $id reservation id to look up
	* @return array of data to be used in an email
	*/
	function get_email_info($id) {
		$query = 'SELECT r.*, rs.name, rs.rphone, rs.location'
            . ' FROM '
			. $this->get_table('resources') . ' as rs, '
			. $this->get_table('reservations') . ' as r'
			. ' WHERE r.resid=?'
			. ' AND rs.machid=r.machid';
		$result = $this->db->getRow($query, array($id));

		$this->check_for_error($result);
		return $this->cleanRow($result);
	}

	/**
	* Get an array of all reservation ids and dates for a recurring group
	*  of reservations, including the parent
	* @param string $parentid id of parent reservation for recurring group
	* @param int $date timestamp of current date
	* @return array of all reservation ids and dates
	*/
	function get_recur_ids($parentid, $date) {
		$return = array();

		$sql = 'SELECT resid, date FROM '
				. $this->get_table('reservations')
				. ' WHERE (parentid = ?'
				. ' OR resid = ?)'
				. ' AND date >= ?'
				. ' ORDER BY date ASC';
		$result = $this->db->query($sql, array($parentid, $parentid, $date));

		$this->check_for_error($result);

		if ($result->numRows() <= 0) {
			$this->err_msg = translate('This reservation is not recurring.');
			return false;
		}

		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}

		$result->free();

		return $return;
	}
	/**
	* Check to make sure the reservation has not been dispatched
	*/
	function check_disp_state($resid) {
		$query = "SELECT dispatch_status FROM trip_log WHERE resid='$resid'";
		$qresult = mysql_query($query);
		$this->check_for_error($qresult);
		$count = mysql_num_rows($qresult);
		$row = mysql_fetch_assoc($qresult);

		if ($count == 0)
			return true;
		else if ($row['dispatch_status'] != 27)
			return false;
		else
			return true;
	}
	/**
	* Get the bill type for users with a billing group
	* param $billid, billing id from billing_groups
	* return $billtype
	*/

	function get_billtype($billid) {
		$query = "SELECT type FROM billing_groups WHERE groupid=$billid";
		$qresult = mysql_query($query);
		$this->check_for_error($qresult);

		$row = mysql_fetch_assoc($qresult);
		return $row['type'];
	}

	/**
	* Abctma voucher queries
	*/
	function update_voucher($vid, $from, $to, $time) {
		global $conf;
		$query = "select location from resources where machid='$from'"; 
		$qresult = mysql_query($query);
		$row = mysql_fetch_assoc($qresult);
		$from = $row['location'];

		$query = "select location from resources where machid='$to'"; 
		$qresult = mysql_query($query);
		$row = mysql_fetch_assoc($qresult);
		$to = $row['location'];

		// switch to abctma database
		$link = mysql_connect('localhost', 'planet_abctma', 'abctma');
		//mysql_select_db('planet_abctma', $link);

		$query = "select status from planet_abctma.abctma_vouchers
			  where voucherid='$vid'";
		$qresult = mysql_query($query, $link);
		if (mysql_num_rows($qresult)==0)
			return "That voucherid did not match any in the database.<br>";
		$row = mysql_fetch_assoc($qresult);
		if ($row['status'] == 'PENDING')
			return "That voucher is still in a pending state.";	
		else if ($row['status'] != 'READY')
			return "That voucher has already been used.";

		// Voucher exists and is READY, update notes

		$time = time();

		$query = "update planet_abctma.abctma_vouchers set 
			  status='USED', time=$time, 
			  fromloc='$from', toloc='$to'
			  where voucherid='$vid'";

		$qresult = mysql_query($query, $link);

		// switch back to normal database
		mysql_close($link);
		//mysql_select_db($conf['db']['dbName']);
		return false;
	}

	function get_abc_user_details($voucherid) {
		$link = mysql_connect('localhost', 'planet_abctma', 'abctma');
		
		$query = "select a.fname, a.lname, a.email
			  from planet_abctma.abctma_vouchers v
			  join planet_abctma.abctma a on a.memberid=v.memberid
			  where v.voucherid='$voucherid'
			  limit 1";

		$qresult = mysql_query($query, $link);
		if (mysql_num_rows($qresult)==0) return false;

		$row = mysql_fetch_assoc($qresult);
		return $row;
	}

	function attrs_by_attrid($attrid) {
		$query = "select attrid, attr_value 
			  from attributes where tableid='$attrid'";
		$qresult = mysql_query($query);
		if (mysql_num_rows($qresult)==0)
			return false;
		$return = array();
		while ($row = mysql_fetch_assoc($qresult))
			$return[] = $row;
		return $return;
	}

	function cc_to_acct() {
		$card = $_POST['ccnum'].'+'.$_POST['expdate'].'+';
		$id = $_SESSION['currentID'];
		$query = "update login set other='$card' where memberid='$id'";
		$qresult = mysql_query($query);
	}	

	/*
	* Vehicle capacity checking functions
	*/
	function van_over_limit($time, $date, $type, $resid='', $limit = 1) {
		$low = $time - 60;
		$hi = $time + 60;
		$exclude = (($type=='m' || $type=='d') && $resid) ? " and resid <> '$resid'" : '';

		// date span
		if ($date >= mktime(0,0,0,5,19,2011) && 
		   $date <= mktime(0,0,0,12,31,2020)) return true;

		// single date
		if ($date == mktime(0,0,0,6,16,2010)) return true;
		
		$query = "select resid from reservations where
			  date=$date
			  and startTime>$low and startTime<$hi
			  and is_blackout <> 1
			  and special_items like '%N%'
			  $exclude";
		$qresult = mysql_query($query);
		$num = mysql_num_rows($qresult);
		//echo "-- $num $type -- $query";
		if (($type == 'a'||$type=='m') && $num)
			return true;

		return false;
	}

	function luxury_over_limit($time, $date, $type, $resid='', $limit = 1) {
		$low = $time - 60;
		$hi = $time + 60;
		$exclude = (($type=='m' || $type=='d') && $resid) ? " and resid <> '$resid'" : '';

		// date span
		if ($date >= mktime(0,0,0,8,14,2009) && 
		   $date <= mktime(0,0,0,5,2,2011)) return true;

		// single date
		if ($date == mktime(0,0,0,3,4,2010)) return true;
		
		$query = "select resid from reservations where
			  date=$date
			  and startTime>$low and startTime<$hi
			  and is_blackout <> 1
			  and special_items like '%L%'
			  $exclude";
		$qresult = mysql_query($query);
		$num = mysql_num_rows($qresult);
		//echo "-- $num $type -- $query";
		if (($type == 'a'||$type=='m') && $num)
			return true;

		return false;
	}

	function suv_over_limit($time, $date, $type, $resid='', $limit = 2) {
		$low = $time - 60;
		$hi = $time + 60;
		$exclude = (($type=='m' || $type=='d') && $resid) ? " and resid <> '$resid'" : '';
		
		// multiple dates
		if ($date >= mktime(0,0,0,3,18,2011) &&
		    $date <= mktime(0,0,0,5,2,2011))
			return true;

		// two single dates
		if ($date == mktime(0,0,0,9,30,2009) ||
		    $date == mktime(0,0,0,10,1,2009))
			$limit = 1; 

		$query = "select resid from reservations where
			  date=$date
			  and startTime>$low and startTime<$hi
			  and is_blackout <> 1
			  and special_items like '%S%'
			  $exclude";
		$qresult = mysql_query($query);
		$num = mysql_num_rows($qresult);
		if (($type == 'a'||$type=='m') && $num >= $limit)
			return true;

		return false;
	}
	
	function cars_over_limit($time, $date, $type, $limit = 25, $CA = false){
		$low = $time - 30;
		$hi = $time + 30;
		$excludeState = 'CA';

		/* If state is CA, we select only CA.
		*  otherwise we allow any state but CA,
		* to grab all new england states. */

		$areaStr = "and r.state <> 'CA'";
		 
		if ($CA) {
			$areaStr = "and r.state='CA'";
			$limit = 8;
			$hi = $time + 45;
		}
		$query ="select res.resid from reservations res join resources r
			  on res.machid=r.machid where
			  res.date=$date
			  and res.startTime>=$low and res.startTime<$hi
			  and res.is_blackout <> 1
			  and res.special_items not like '%N%'
			  and res.special_items not like '%S%'
			  $areaStr";
		$qresult = mysql_query($query);
		$num = mysql_num_rows($qresult);
		if (($type == 'a'||$type=='m') && $num >= $limit)
			return true;

		return false;
	}

	/*
	* Check whether a date is moratoriumed
	*/
	function is_moratoriumed($date, $area) {
		if (!$date || !$area) return false;

		$query = "select id from moratoriums 
			  where start <= ?
			  and end >= ?
			  and area = ?";

		$vals = array($date, $date, $area);

		$result = $this->db->getOne($query, $vals);

		if ($result && !$this->db->isError($result))
			return true;
		
		return false;
	}

	/*
	* Get list of current moratoriums for printing
	*/
	function get_moratoriums($all = false) {
		$today = mktime(0,0,0);
		$allstr = $all ? "" : "where end >= $today";
		$query = "select * from moratoriums $allstr
			  order by area, start";

		$result = $this->db->query($query);

		if (!$result->numRows()) return false;

		$return = array();
		while ($row = $result->fetchRow())
			$return[] = $row;

		return $return;
	}


	function insert_turnaway($reason, $reasonInt=1, $memberid, $coast = 'MA') {
		$query = "select fname, lname, email from login where memberid='$memberid'";
		$qresult = mysql_query($query);

		$user = mysql_fetch_assoc($qresult);
		$fname = $user['fname'];
		$lname = $user['lname'];
		$email = $user['email'];
		if (isset($_SESSION['sessionID'])) $memberid = $_SESSION['sessionID'];

		$query = "insert into turnaways (name, email, reason, createdBy, autoReason, timestamp, coast)
			  values ('$fname $lname', '$email', '$reason', '$memberid', $reasonInt, NOW(), '$coast')";
		$qresult = mysql_query($query);
	}
	
	function get_coupon_amount($coupon_code) {
		$query = "select *, unix_timestamp(begins) as uBegins, unix_timestamp(expires) as uExpires from coupon_codes where coupon_code=?";
		$vals = array($coupon_code);
		$result = $this->db->getRow($query, $vals);
		if ($result) return $result;
		else return null;
	}

	function coupon_been_used($memberid, $coupon_code, $mod_id = false, $date = 0) {
		$recur = 1;
		$query = "select *, unix_timestamp(expires) as exp, unix_timestamp(begins) as begins from coupon_codes where coupon_code=?";
		$vals = array($coupon_code);
		$result = $this->db->getRow($query, $vals);

		if (!$result) return false;

		$recur = $result['recurrence'];

		// Date test
		if ($result['exp']) {
			$expire = $result['exp'];
			$begins = $result['begins'];
			if ($date > $expire) return 1;
			if ($date < $begins) return 1;
		}

		$query = "select resid from reservations where coupon_code=? and memberid=? and is_blackout <> 1";
		$vals = array($coupon_code, $memberid);
		
		// If modifying, exclude the current reservation
		if ($mod_id) {
			$query .= " and resid <> ?";
			$vals[] = $mod_id;
		}

		$result = $this->db->query($query, $vals);

		// coupon limit test
		if ($result) { 
			$this->check_for_error($result);

			$rows = $result->numRows();

			if (!$rows || $rows < $recur) return false;
			else return $rows;
		}
		else return false;
	}

	function increment_coupon_uses($coupon_code) {
		$query = "update coupon_codes set use_count=use_count+1
			  where coupon_code=?";
		$vals = array($coupon_code);
		$result = $this->db->query($query, $vals);
	}
	function decrement_coupon_uses($coupon_code) {
		$query = "update coupon_codes set use_count=use_count-1
			  where coupon_code=?";
		$vals = array($coupon_code);
		$result = $this->db->query($query, $vals);
	}
	function get_linked_reservations($linkid, $resid = null) {
		$query = "select * from Reservation_Link where linkid=?";
		$vals = array($linkid);

		// get ALL reservations in the group by default.
		// If resid is provided, exclude that one.
		if ($resid) {
			$query .= " and resid <> ?";
			$vals[] = $resid;
		}

		$result = $this->db->query($query, $vals);
		$return = array();
		while ($row = $result->fetchRow())
			$return[] = $row;
		return $return;
	}

	function load_stops() {
	
	}

	function add_stops(&$res) {
		global $conf;
		$stops = $conf['app']['max_stops'];
		$resid = $res->id;

		$query = "insert into reservation_stops (resid, machid, stopNo) values";
		$qArr = $vals = array();

		for ($i=0; $i < $stops; $i++) {
			$curname = "hasStop_$i";
			if (!isset($_POST[$curname])) continue;

			$curloc = "stop_$i";
			$machid = $_POST[$curloc];

			$qArr[] = "(?,?,?)";
			array_push($vals, $resid, $machid, $i);
		}

		if (!count($qArr)) return;

		$query .= implode(", ", $qArr);

		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		
	}
	function delete_stops($resid) {
		$vals = array($resid);
		$query = "delete from reservation_stops where resid=?";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
	}

}
?>
