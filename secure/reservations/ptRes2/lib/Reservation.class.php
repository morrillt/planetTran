<?php
set_include_path("../:lib/pear/:/usr/local/php5");
/**
* Reservation class
* Provides access to reservation data
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 08-21-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

/**
* ResDB class
*/
include_once('db/ResDB.class.php');
/**
* User class
*/
include_once('User.class.php');

if(!class_exists('Tools'))
{
  include_once('Tools.class.php');
}
// die(print_r());
/**
* PHPMailer
*/
include_once('PHPMailer.class.php');
/**
* Reservation templates
*/
include_once(BASE_DIR . '/templates/reserve.template.php');


class Reservation {
	var $id 		= null;			//	Properties
	var $date 		= null;			//
	var $start	 	= null;			//
	var $end	 	= null;			//
	var $machid 	= null;				//
	var $toLocation = null;				//
	var $memberid 	= null;				//
	var $created 	= null;				//
	var $modified 	= null;				//
	var $type 		= null;			//
	var $is_repeat	= false;			//
	var $repeat		= null;			//
	var $minRes		= null;			//
	var $maxRes		= null;			//
	var $parentid	= null;				//
	var $is_blackout= false;			//
	var $checkedBags = false;
	var $flightDets	= null;				//
	var $summary	= null;				//
	var $scheduleid	= null;				//
	var $sched		= null;			//
	var $specialItems = null;
 	var $manager = null;
	var $errors     = array();
	var $warnings	= array();
	var $word		= null;
	var $coupon 	= null;
	var $global_coupon = null;
	var $linkid	= null;
	var $mtype	= null;
	var $linked_reservations = array();
	var $day 	= null;
	var $month 	= null;
	var $year 	= null;
	var $hour_estimate = null;
	var $origin = null;
	var $paymentProfileId = null;
	var $authWait	= null;
	var $stops 	= array();
	var $stopLoc	= null;
	var $autoBillOverride;
	var $convertible_seats = null;
	var $booster_seats = null;
	
	var $regionID = null;
	var $vehicle_type = null;
	var $trip_type = null;
	var $passenger_count = null; 
	var $meet_greet = null;
	var $estimate = null;

	var $db;

	/**
	* Reservation constructor
	* Sets id (if applicable)
	* Sets the reservation action type
	* Sets the database reference
	* @param string $id id of reservation to load
	*/
	function Reservation($id = null, $is_blackout = false) {
		if (!empty($id))
			$this->id = $id;
		$this->manager = $_SESSION['sessionID'];

		$this->type = isset($_GET['type']) ? substr($_GET['type'], 0, 1) : null;
		$this->is_blackout = $is_blackout;
		$this->word = $is_blackout ? 'blackout' : 'reservation';
		$this->db = new ResDB();
	}

	/**
	* Loads all reservation properties from the database
	* @param none
	*/
	function load_by_id() {
		$res = $this->db->get_reservation($this->id);	// Get values from DB

		if (!$res)		// Quit if reservation doesnt exist
			CmnFns::do_error_box($this->db->get_err());

		$this->date		= $res['date'];
		$this->day = date("j", $res['date']);
		$this->month = date("n", $res['date']);
		$this->year = date("Y", $res['date']);

		$this->start	= $res['startTime'];
		$this->end		= $res['endTime'];
		$this->machid	= $res['machid'];
		$this->toLocation = $res['toLocation'];
		$this->memberid = $res['memberid'];
		$this->created	= $res['created'];
		$this->modified = $res['modified'];
		$this->parentid = $res['parentid'];
		$this->flightDets = htmlspecialchars($res['flightDets']);
		$this->summary	= htmlspecialchars($res['summary']);
		$this->special	= htmlspecialchars($res['special']);
		$this->scheduleid	= $res['scheduleid'];
		$this->is_blackout	= $res['is_blackout'];
		$this->checkBags	= $res['checkBags'];
		$this->specialItems = $res['special_items'];
		$this->dispNotes = $res['dispNotes']?$res['dispNotes']:null;
		$this->coupon 		= $res['coupon_code'];
		$this->mtype		= $res['mtype'];
		$this->linkid		= $res['linkid'];
		$this->hour_estimate	= $res['hour_estimate'];
		$this->origin		= $res['origin'];
		$this->paymentProfileId	= $res['paymentProfileId'];
		$this->authWait		= $res['authWait'];
		$this->stopLoc		= $res['stopLoc'];
		$this->autoBillOverride		= $res['autoBillOverride'];
		$this->convertible_seats	= $res['convertible_seats'];
		$this->booster_seats	= $res['booster_seats'];

		$this->regionID = $res['regionID'];
		$this->vehicle_type = $res['vehicle_type'];
		$this->trip_type = $res['trip_type'];
		$this->passenger_count = $res['passenger_count'];
		$this->meet_greet = $res['meet_greet'];
		$this->estimate = $res['estimate'];

		$this->sched = $this->db->get_schedule_data($this->scheduleid);

		if ($this->linkid)
			$this->linked_reservations = $this->db->get_linked_reservations($this->linkid, $this->id);

		$this->stops = $this->db->load_stops();
	}

	/**
	* Loads the required reservation properties using
	*  what is passed in from the querystring
	* @param none
	*/
	function load_by_get() {
		$this->machid 	= $_GET['machid'];
		$this->date = time(); //1101186000;//mktime(0,0,0,$dates[0], $dates[1], $dates[2]);//////$_GET['ts'];
		$this->memberid = $_SESSION['currentID'];
		$this->scheduleid = $this->db->get_user_scheduleid($this->memberid);

		$this->sched = $this->db->get_schedule_data($this->scheduleid);
	}

	/**
	* Deletes the current reservation from the database
	* If this is a recurring reservation, it may delete all reservations in group
	* @param boolean $del_recur whether to delete all recurring reservations in this group
	*/
	function del_res($del_recur) {
		$this->load_by_id();
		$this->type = 'd';
		$this->check_times();
		if ($this->has_errors())
			$this->print_all_errors(true);

		$this->is_repeat = $del_recur;

		$this->db->del_res($this->id, $this->parentid, $del_recur, $this->date);

		$user = new User($this->memberid);

		if (!$this->is_blackout)
			$this->send_email('e_del', $user);

		CmnFns::write_log($this->word . ' ' . $this->id . ' deleted.', $this->memberid, $_SERVER['REMOTE_ADDR']);
		if ($this->is_repeat)
			CmnFns::write_log('All ' . $this->word . 's associated with ' . $this->id . ' (having parentid ' . $this->parentid . ') were also deleted', $this->memberid, $_SERVER['REMOTE_ADDR']);
		$this->print_success('deleted');
	}

	/**
	* Add a new reservation to the database
	*  after verifying that user has permission
	*  and the time is available
	* @param string $machid id of resource to reserve
	* @param string $memberid id of member making reservation
	* @param float $start starting time of reservation
	* @param float $end ending time of reservation
	* @param array $repeat repeat reservation values
	* @param int $min minimum reservation time
	* @param int $max maximum reservation time
	* @param string $summary reservation summary
	* @param string $scheduleid id of schedule to make reservation on
	* @param string $specialItems special item flags
	*/
	function add_res(
    $machid, //$_POST['fromLoc']
    $toLocation, //$_POST['toLoc']
    $memberid,//$_SESSION['currentID']
    $start, //$startTime,
    $end, //$startTime + 15
    $repeat,
    $date, //$_POST['date']
    $min, //$_POST['minRes']
    $max, //$_POST['maxRes']
    $summary = null, //stripslashes($_POST['summary'])
    $special = null, //stripslashes($_POST['summary'])
    $flightDets = null, //$flightDets
    $checkBags, //(isset($_POST['checkBags'])?1:0)
    $scheduleid, //$_POST['scheduleid']
    $specialItems,
    $dispNotes, //stripslashes($_POST['dispNotes'])
    $coupon = null, //$_POST['coupon']
    $hour_estimate = null, //null
    $authWait = null,
    $paymentProfileId = null,
    $stopLoc = null,
    $autoBillOverride = null, //(isset($_POST['autoBillOverride'])?1:0)
    $convertible_seats = null,
    $booster_seats = null,
    $regionID,
    $vehicle_type,
    $trip_type,
    $passenger_count,
    $meet_greet,
    $estimate
    )
    {
//echo '<pre>';
//print_r(func_get_args());
//echo '</pre>';
//die();

		$this->machid	= $machid; // location id $_POST['fromLoc']
		$this->toLocation = $toLocation; //
		$this->memberid = $memberid;
		$this->start	= $start;
		$this->end	= $end;
		$this->repeat 	= $repeat;
		$this->hour_estimate = $hour_estimate;
		$this->authWait	= $authWait;
		$this->paymentProfileId = $paymentProfileId;
		$this->stopLoc  = $stopLoc;
		$this->autoBillOverride  = $autoBillOverride;
		$this->convertible_seats = $convertible_seats;
		$this->booster_seats = $booster_seats;

		$this->regionID = $regionID;
		$this->vehicle_type = $vehicle_type;
		$this->trip_type = $trip_type;
		$this->passenger_count = $passenger_count;
		$this->meet_greet = $meet_greet;
		$this->estimate = $estimate;


		$dates_info = split('/', $date);
		$mtype = isset($_POST['mtype']) ? $_POST['mtype'] : null;
		// As Directed checkbox
		if ($_POST['asDirected']) {
			$mtype = 'h';
			$this->toLocation = 'asDirectedLoc';
		} 
		// hailing
		//if ($_POST['fromLoc'] == 'fromgps') {
		//	$this->machid = $this->insert_gps_loc($scheduleid);
		//}

		if (is_null($paymentProfileId) || $paymentProfileId == '')
			$this->add_error('Please add or select a payment option.');

//var_dump(!$this->machid);
//var_dump(!$this->toLocation);
//var_dump(!$date);
//var_dump(!isset($this->start));
//var_dump($this->start == '');

		if (!$this->machid || !$this->toLocation
		 || !$date || !isset($this->start) || $this->start == '') {
			$this->add_error('Please enter a date, time, and locations for all reservations.');
			$this->print_all_errors(true);
		}
		
		$fixdate = mktime(0,0,0,$dates_info[0], $dates_info[1], $dates_info[2]);
		$fixdate -= $fixdate % 100;
		$this->date = $fixdate;
		$date1 = $this->date;
		$this->type     = 'a';

		$this->summary	= $summary;
		$this->special	= $special;
		$this->flightDets = $flightDets;
		$this->checkBags = $checkBags;
		$this->scheduleid = $scheduleid;
		$this->specialItems = $specialItems;
		$dispNotes = trim($dispNotes);
		$this->dispNotes = !empty($dispNotes) ? $dispNotes : null;
		$this->coupon = $coupon ? $coupon : null;

		$dates = array();
		$tmp_valid = false;

		if (!$this->is_blackout) {
			$user = new User($this->memberid);		// Set up a new User object
			//$this->check_perms($user);		// Only need to check once
			//$this->check_min_max($min, $max);
		}

		if ($coupon) 
			$this->check_coupon($coupon, $this->machid, $this->toLocation);


		// Check times
		$this->check_times();

		// Check CC
		$this->check_cc($user);

		// Check phone
		$this->check_phone($user);


		if ($mtype == 'r' && isset($_POST['day2'], $_POST['month2'], $_POST['year2'])) {
			$date2 = mktime(0,0,0, $_POST['month2'], $_POST['day2'], $_POST['year2']);
			if ($date2 < $date1)
				$this->add_error("The return trip can't be before the original trip!");
		}

		// Check voucher
		if (isset($_POST['voucherid']) && !$this->has_errors())
			$this->do_voucher();

		if ($this->has_errors())
			$this->print_all_errors(true);

		$is_parent = $this->is_repeat;		// First valid reservation will be parentid (no parentid if solo reservation)

		/* old add_res logic */

		//for ($i = 0; $i < count($repeat); $i++) {
		//$this->date = $repeat[$i];
		//if ($i == 0) $tmp_date = $this->date;	// Store the first date to use in the email

		$is_valid = $this->check_res();
		
		if ($is_valid) //{
			$tmp_valid = true;							// Only one recurring needs to work
		//$this->id = $this->db->add_res($this, $is_parent);
		//		if (!$is_parent) {
					array_push($dates, $this->date);// Add recurring dates (first date isnt recurring)
		//		}
		//		else {
		//			$this->parentid = $this->id;// The first reservation is the parent id
		//		}
		//		CmnFns::write_log($this->word . ' ' . $this->id . ' added.  machid:' . $this->machid .', date:' . $this->date . ', start:' . $this->start . ', end:' . $this->end, $this->memberid, $_SERVER['REMOTE_ADDR']);
		//	}
		//	$is_parent = false;							// Parent has already been stored
		//}

		// errors were already printed above
		//if ($this->has_errors())
		//	$this->print_all_errors(!$this->is_repeat);

		//$this->date = $tmp_date;// Restore first date for use in email
		//if ($this->is_repeat) array_unshift($dates, $this->date);// Add to list of successful dates

		//sort($dates);

		/*							      
		* All above repeat logic has been discarded.
		* This is the new add_res logic.
		*/							      
		$this->mtype = $mtype;

		if (!$mtype || $mtype == 'o') {
			$this->id = $this->db->add_res($this, $is_parent);
		} else if ($mtype == 'r') {
			// Round trip

			// check that this date and time are after the first
			$start2 = $_POST['ampm2'] == 'pm' ? $_POST['startTime2'] + 720 : $_POST['startTime2'];

			if ($_POST['date2']) {
				$bits = split('/', $_POST['date2']);
				$date2 = mktime(0,0,0, $bits[0], $bits[1], $bits[2]);
			} else
				$date2 = mktime(0,0,0, $_POST['month2'], $_POST['day2'], $_POST['year2']);


			$link = uniqid();
			$this->linkid = $link;

			if (!$this->has_errors())
				$this->id = $this->db->add_res($this, $is_parent);

			$resid1 = $this->id;

			// reverse from and to locations, set new time
			// all other properties are identical
			$this->machid = $toLocation;
			$this->toLocation = $machid;
			$this->start = $start2;
			$this->end = $this->start + 15;
			$this->date = $date2;
			array_push($dates, $this->date);


			if ($_POST['flightnumber2']) {
				$split = '{`}';
				$acode = substr($_POST['flightnumber2'], 0, 2);
				$fnum = substr($_POST['flightnumber2'], 2);
				$flightDets = strtoupper($acode).$split.$fnum.$split;
				$this->flightDets = $flightDets;
			}

			if (!$this->has_errors()) {
				$this->id = $this->db->add_res($this, $is_parent);
				$resid2 = $this->id;
				//echo "r1 $resid1 r2 $resid2 li $link";

				$this->db->add_link($link, $resid1, 'r');
				$this->db->add_link($link, $resid2, 'r');
			}
		} else if ($mtype == 'h') {
			$adb	= new AdminDB();
			$has_asdirected = $adb->get_resource_permissions($this->toLocation, $this->memberid);

			// Add "As Directed" to their list of locations
			if (!$has_asdirected)
				$adb->assign_resource($this->toLocation, $this->memberid);


			if (!$this->has_errors())
				$this->id = $this->db->add_res($this, $is_parent);
		}


		if (!$this->is_blackout) {		// Notify the user if they want (only 1 email)
			$this->sched = $this->db->get_schedule_data($this->scheduleid);
			$this->send_email('e_add', $user, $dates);
		}

		if ($this->has_warnings())
			$this->print_all_warnings('created');

		if (!$this->is_repeat || $tmp_valid) {
			// If they entered a CC and want it to be the default
			if (isset($_POST['ccToAcct'])) 
				$this->db->cc_to_acct();			
			$this->print_success('created', $dates);
		}

	}

	/**
	* If this is the ABC user, add trip info the the voucher notes
	*/
	function do_voucher () {
		$vid = $_POST['voucherid'];
		if (strlen($vid) != 6) { 
			$this->add_error('To make an ABCTMA reservation, please enter the 6 character voucherid.<br>');
			return false;
		}
		$time = date("m/d/Y g:ia", $this->date+$this->start*60);
		$err = $this->db->update_voucher($vid, $this->machid, $this->toLocation, $time);
		if ($err) {
			$this->add_error($err);
			return false;
		}

		$abc_user = $this->db->get_abc_user_details($vid);

		if (!$abc_user) {
			$this->add_error('Invalid ABCTMA user or voucherid. Error code ABC1. Please write this code down and use it when reporting the problem.');
			return false;
		}
		
		// Add voucherid to cost code field
		$notes = $this->db->parseNotes($this->summary, 1);
		$notes[2] = $vid;
		//CmnFns::diagnose($notes);
		$this->summary = implode('GROUP_DEL', $notes);
		//echo "<br>".$this->summary;

		// Success, email user
		$m = new PHPMailer();
		$m->ClearAllRecipients();
		$m->IsHTML(true);
		$m->AddAddress($abc_user['email']);
		$m->AddBCC('DFennell@abettercity.org');
		$m->Subject = "Please complete ABCTMA confirmation report for voucher $vid";
		$m->From = 'noreply@planettran.com';
		$m->FromName = 'ABCTMA';

		include_once('/home/planet/www/abctma/abc_email.php');
		$msg = confirmEmail($abc_user);
		$m->Body = $msg;
		$m->Send();
	}

	/**
	* Modifies a current reservation, setting new start and end times
	*  or deleting it
	* @param int $start new start time
	* @param int $end new end time
	* @param bool $del whether to delete it or not
	* @param int $min minimum reservation time
	* @param int $max maximum reservation time
	* @param boolean $mod_recur whether to modify all recurring reservations in this group
	* @param string $summary reservation summary
	* @param string $specialItems
	*/
	function mod_res($fromLoc, $toLoc, $date, $start, $end, $del, $min, $max, $mod_recur, $summary = null, $flightDets = null, $checkBags, $specialItems, $scheduleid, $dispNotes, $coupon = null, $hour_estimate = null, $authWait = null, $paymentProfileId = null, $stopLoc = null, $autoBillOverride = null, $convertible_seats = null, $booster_seats = null, $regionID, $vehicle_type, $trip_type, $passenger_count, $meet_greet, $estimate) {
		$recurs = array();

		$this->load_by_id();			// Load reservation data

		$oldnotes = $this->db->parseNotes($this->summary);
		$newnotes = $this->db->parseNotes($summary);

		// if there's a CC on the old reservation and not on the new,
		// use the old one
		if ($oldnotes['cc'] && !$newnotes['cc']) {
			$newnotes['cc'] = $oldnotes['cc'];
			$newnotes['exp'] = $oldnotes['exp'];
			$d = 'GROUP_DEL';
			$summary = 	$newnotes['name'].$d.
					$newnotes['cell'].$d.
					$newnotes['code'].$d.
					$newnotes['address'].$d.
					$newnotes['address2'].$d.
					$newnotes['cc'].$d.
					$newnotes['exp'].$d.
					$newnotes['notes'].$d.
					$newnotes['email'];
		}


		$this->machid = $fromLoc;
		$this->toLocation = $toLoc;
		$this->type = 'm';
		$this->summary = $summary;
		$this->flightDets = $flightDets;
		$this->checkBags = $checkBags;
		$this->hour_estimate = $hour_estimate;
		$this->authWait = $authWait;
		$this->paymentProfileId = $paymentProfileId;
		$this->stopLoc	= $stopLoc;
		$this->autoBillOverride	= $autoBillOverride;
		$this->convertible_seats = $convertible_seats;
		$this->booster_seats = $booster_seats;
		
		$this->regionID = $regionID;
		$this->vehicle_type = $vehicle_type;
		$this->trip_type = $trip_type;
		$this->passenger_count = $passenger_count;
		$this->meet_greet = $meet_greet;
		$this->estimate = $estimate;
		

		$dates_info = split('/', $date);
		//$this->date = mktime(0,0,0,$dates_info[0], $dates_info[1], $dates_info[2]);
		$fixdate = mktime(0,0,0,$dates_info[0], $dates_info[1], $dates_info[2]);
		$fixdate -= $fixdate % 100;
		$this->date = $fixdate;

		$this->start = $start;	// Assign new start and end times
		$this->end	 = $end;
		$this->specialItems = $specialItems;
		$dispNotes = trim($dispNotes);
		$this->dispNotes = !empty($dispNotes) ? $dispNotes : null;
		$this->coupon = $coupon ? $coupon : null;

		if ($coupon) 
			$this->check_coupon($coupon, $this->machid, $this->toLocation);

		if ($del) {	// First, check if this should be deleted
			$this->del_res($mod_recur, mktime(0,0,0));
			return;
		}

		if (!$this->is_blackout) {
			$user = new User($this->memberid);		// Set up a User object
			//$this->check_perms($user);				// Check permissions
			//$this->check_min_max($min, $max);		// Check min/max reservation times
		}

		$this->check_times();			// Check valid times

		// Check phone
		$this->check_phone($user);

		$this->is_repeat = $mod_recur;	// If the mod_recur flag is set, it must be a recurring reservation
		$dates = array();

		// First, modify the current reservation

		if ($this->has_errors())			// Print any errors generated above and kill app
			$this->print_all_errors(true);

		$tmp_valid = false;

		if ($this->is_repeat) {		// Check and place all recurring reservations
			$recurs = $this->db->get_recur_ids($this->parentid, mktime(0,0,0));
			for ($i = 0; $i < count($recurs); $i++) {
				$this->id   = $recurs[$i]['resid'];		// Load reservation data
				$this->date = $recurs[$i]['date'];
				$is_valid   = $this->check_res();			// Check overlap (dont kill)

				if ($is_valid) {
					$tmp_valid = true;				// Only one recurring needs to pass
					$this->db->mod_res($this);		// And place the reservation
					array_push($dates, $this->date);
					CmnFns::write_log($this->word . ' ' . $this->id . ' modified.  machid:' . $this->machid .', date:' . $this->date . ', start:' . $this->start . ', end:' . $this->end, $this->memberid, $_SERVER['REMOTE_ADDR']);
				}
			}
		}
		else {
			if ($this->check_res()) {			// Check overlap
				$this->db->mod_res($this);		// And place the reservation
				array_push($dates, $this->date);
			}
		}

		if ($this->has_errors())		// Print any errors generated when adding the reservations
			$this->print_all_errors(!$this->is_repeat);

		if (!$this->is_blackout && isset($_POST['emailModConfirm'])) {
			$this->send_email('e_mod', $user);
		}
		if ($this->has_warnings())
			$this->print_all_warnings('modified');

		if (!$this->is_repeat || $tmp_valid)
			$this->print_success('modified', $dates);
	}

	/**
	* Prints a message nofiying the user that their reservation was placed
	* @param string $verb action word of what kind of reservation process just occcured
	* @param array $dates dates that were added or modified.  Deletions are not printed.
	*/
	function print_success($verb, $dates = array()) {
		$showid = strtoupper(substr($this->id, -6));
		if ($this->paymentProfileId == "00")
			$payinfo = "Direct Bill";
		else {
			$paymentArray = $this->db->getPaymentOptions($this->memberid);
			$payinfo = $paymentArray[$this->paymentProfileId];
		}

		if (isset($this->ismobile) && $this->ismobile) {
			//$endlink = '<a href="m.cpanel.php">Back</a>';
			print_mobile_success($verb, $this);
			return;
		} else
    {
//			$endlink = '<a href="javascript: window.close();">Close</a>';
    }
		echo '<script language="JavaScript" type="text/javascript">' . "\n"
			. 'window.opener.document.location.href = window.opener.document.URL;' . "\n"
			. '</script>';
		$date_text = '';
		for ($i = 0; $i < count($dates); $i++) {
			$date_text .= "Reservation #$showid on ";
			$date_text .= CmnFns::formatDate($dates[$i]) . " $payinfo";
		}
		$fromLocation = mysql_fetch_assoc(mysql_query("SELECT * FROM resources WHERE machid='".$this->machid."'"));
		$toLocation = mysql_fetch_assoc(mysql_query("SELECT * FROM resources WHERE machid='".$this->toLocation."'"));
		$stopLocation = mysql_fetch_assoc(mysql_query("SELECT * FROM resources WHERE machid='".$this->stopLoc."'"));

		$member = mysql_fetch_assoc(mysql_query("SELECT * FROM login WHERE memberid='".$this->memberid."'"));

		if (!$this->word) $this->word = 'reservation';
		// print_r($this);
		/*CmnFns::do_message_box('Your ' . $this->word . ' was successfully ' . $verb
			. (($this->type != 'd') ? ' for the following dates:<br /><br />' : '.')
			. $date_text . '<br/><br/><b>Traveling to the Bay Area?</b><br/>We are now serving <b>SFO, OAK, and SJC airports!</b>  Add these airports to your profile and you can make reservations now!<br/><br/>'
			. $endlink
			, 'width: 90%;');
		*/
		$convertible_seats = max(0,$this->convertible_seats).' ';
		$booster_seats = max(0,$this->booster_seats).' ';
		$variable = array(
		  'address1'  => $fromLocation['location'],
		  'address2'  => $toLocation['location'],
		  'address3'  => $stopLocation['location'],
		  'wait_time' => $this->wait_time,
		  'amenities' => $booster_seats.','.$convertible_seats.','.((int)$this->meet_greet),
		  'memberId'  => $this->memberid,
		  'groupId'   => $this->groupid,
		  'coupon'    => $this->coupon_code,
		  'origin'    => $this->origin,
		  'vehicle_type' => $this->vehicle_type,
		  'trip_type' => $this->trip_type,
		  'region'    => $this->regionID,
		);

		// $estimate = exec('/home/planet/scripts/estimate.pl "'.escapeshellarg(http_build_query($variable)).'"');
		?>

				<div class="content_box_inner">

					<h1 id="hdr_reservation_confirmation"><span class="imagetext"><?php echo translate('Reservation Confirmation') ?></span></h1>

				
					<div class="hr">
						<h2><?php echo translate('Thanks for placing your reservation!') ?></h2>
						<p><?php echo translate('Here is a summary of your trip (which will also be emailed to you shortly):') ?></p>
						<!-- Remove parenthesis above if Email Settings are turned off for this Passenger, or if SM/Admin -->
					</div>

					<div class="confirmation_summary_wrap hr">
						<div class="row group spacious_top highlight">

							<div class="labelish">
								<label for="override_auto_billing"><strong><?php echo translate('Confirmation #') ?></strong></label>
							</div>
							<div class="inputs">
								<strong><?php echo $showid.'' ?></strong>
							</div>
						</div>
						<div class="row group">
							<div class="labelish">
								<label for="override_auto_billing"><?php echo translate('When') ?></label>
							</div>
							<div class="inputs">
								<?php echo date("m/d/Y h:ia", $this->date + $this->start); ?>
							</div>
						</div>
						<div class="row group">
							<div class="labelish">
								<label for="override_auto_billing"><?php echo translate('From') ?></label>
							</div>
							<div class="inputs">
								<?php echo $fromLocation['name'].' '.$fromLocation['location'] ?>
								<!--Logan Int'l Airport<br />
								Delta Flight #123<br />
								3 checked bags<br />
								Time/other details-->
							</div>

						</div>

						<?php if($stopLocation['location']): ?>
							<div class="row group">
								<div class="labelish">
									<label for="override_auto_billing"><?php echo translate('Intermediate Stop') ?></label>
								</div>
								<div class="inputs">
									<?php echo $stopLocation['name'].' '.$stopLocation['location'] ?>
									<!-- 456 Beacon Street, Brighton MA 02445 -->
								</div>
							</div>
						<?php endif ?>

						<div class="row group">
							<div class="labelish">
								<label for="override_auto_billing"><?php echo translate('To') ?></label>
							</div>
							<div class="inputs">
								<?php echo $toLocation['name'].' '.$toLocation['location'] ?>
							</div>
						</div>
						<div class="row group">

							<div class="labelish">
								<label for="override_auto_billing"><?php echo translate('Vehicle') ?></label>
							</div>
							<div class="inputs">
								<?php
								  $t = new Tools();
								  $a = $t->car_select_array();
								  echo $a[$this->vehicle_type.''];
								?>
								<!-- Toyota Highlander SUV -->
							</div>
						</div>
						<div class="row group">
							<div class="labelish">
								<label for="override_auto_billing"><?php echo translate('Passengers') ?></label>
							</div>
							<div class="inputs">
								<?php echo $this->passenger_count.' ' ?>
								<!-- 3 -->
							</div>
						</div>
						<?php if($this->convertible_seats || $this->booster_seats): ?>
						  <div class="row group">
						    <div class="labelish">
						      <label for="override_auto_billing"><?php echo translate('Child Seat') ?></label>
						    </div>
						    <div class="inputs">
						      <?php
							if($convertible_seats > 0) { echo $this->convertible_seats.' '.translate('Convertible seats').' ($'.(15*$convertible_seats).')'; }
							if($booster_seats > 0) { echo $booster_seats.' '.translate('Booster').' ($'.(15*$booster_seats).')'; }
						      ?>
						      <!-- 1 convertible seat ($15), 1 booster ($15) -->
						    </div>
						  </div>
						<?php endif ?>				
						<div class="row group">
							<div class="labelish">
								<label for="override_auto_billing"><?php echo translate('Reservation for') ?></label>
							</div>

							<div class="inputs">
								<?php echo $member['fname'].' '.$member['lname'] ?>
								<!-- John Doe -->
							</div>
						</div>
						<?php if($stopLocation['special']): ?>
							<div class="row group">
								<div class="labelish">
									<label for="override_auto_billing"><?php echo translate('Cost or Project Code') ?></label>
								</div>
								<div class="inputs">

									<!-- Alpha 1 -->
								</div>
							</div>
						<?php endif ?>
						<div class="row group">
							<div class="labelish">
								<label for="override_auto_billing"><?php echo translate('Meet and Greet?') ?></label>
							</div>
							<div class="inputs">
								<input type="checkbox" id="meet_greet" <?php if($this->meet_greet) echo 'checked="checked"' ?> />

							</div>
						</div>
						<?php if($stopLocation['special']): ?>
							<div class="row group">
								<div class="labelish">
									<label for="override_auto_billing"><?php echo translate('Special Instructions') ?></label>
								</div>
								<div class="inputs">
									<!-- hese are some special instructions -->
								</div>
							</div>
						<?php endif ?>

						<div class="row group spacious_top highlight">
							<div class="labelish">
								<label for="override_auto_billing"><strong><?php echo translate('Total estimated fare') ?></strong></label>
							</div>
							<div class="inputs">
								<strong><?php echo $_REQUEST['estimate'] ?></strong>
							</div>
						</div>

					</div><!-- /confirmation_summary_wrap -->
	

					<div id="roundtrip_wrap" class="group spacious_top">	
					  <input type="button" onclick="window.location.href = window.location.href+'&amp;from=<?php echo $this->toLocation ?>&amp;to=<?php echo $this->machid ?>'" id="book_return"    name="" value="<?php echo translate('Book a return trip') ?>" /> <span id="or" class="by"><?php echo translate('OR') ?></span> 
					  <input type="button" onclick="window.location.href = window.location.href+'&amp;from=<?php echo $this->machid ?>&amp;to=<?php echo $this->toLocation ?>'" id="book_duplicate" name="" value="<?php echo translate('Book similar trip at another time') ?>" />
					</div>
				</div>

	  <?php
	  die();
	}
	/**
	* Print a message when a reservation fails because of a bad location
	*/
	function print_fail($check, $scheduleid) {
		echo '<script language="JavaScript" type="text/javascript">' . "\n"
			. 'window.opener.document.location.href = window.opener.document.URL;' . "\n"
			. '</script>';
		CmnFns::do_message_box('There was a problem entering your reservation.<br/>'
					. "Your {$check['reason']} address is invalid.<br/>&nbsp;<br/>"
					. "Please make sure that the<br/> "
					. "<b>Address</b>, <b>City</b>, <b>State</b> and <b>Zip Code</b> <br/>"
					. "are all correct.<br/><br/>"
					. "<a href=\"javascript: locate('m', '{$check['machid']}', '', '', '$scheduleid'); window.close();\">"
					. 'Click here to edit.</a><br/>&nbsp;<br/>'
					, 'width: 90%;');
	}
	/**
	* Print a message when a reservation fails because it has already been dispatched
	*/
	function print_res_fail() {
		echo '<script language="JavaScript" type="text/javascript">' . "\n"
			. 'window.opener.document.location.href = window.opener.document.URL;' . "\n"
			. '</script>';
		CmnFns::do_message_box("Unable to modify that reservation: "
					. "It has already been queued by dispatch.<br/>&nbsp;<br/>Please call 888-PLN-TTRN (888-756-8876) to modify or cancel this reservation."
					, 'color: #C00; border: 1px solid #C00; width: 90%;');
	}

	/*
	* Check that the account has a valid credit card
	*/
	function check_cc(&$user) {
		/*
		$notes = $this->db->parseNotes($this->summary);

		if ($notes) {
			if (!empty($notes['cc'])) {
				if (!Auth::checkCreditCard($notes['cc'])) {
					$this->add_error('The credit card number that was entered in the reservation form is not valid.');
					return;
				}
				if (!Auth::checkCCDate($notes['exp'])) {
					$this->add_error('The expiration date that was entered in the reservation form is not valid. Please check that it is current and in the format MM/YYYY.');
					return;
				}
				return;
			}
		}
		if ($user->groupid) {
			if ($user->groupid == 1596) {
				if (!$notes['code'])
					$this->add_error('Members of this billing group need to enter a billing code to create a reservation.');
			}

			if ($this->db->get_billtype($user->groupid)!='c')
				return;
		}		
		$debug = $user->email."|".$user->other."|".$user->groupid;
		list($cc, $exp, $cvv) = explode("+", $user->other);
		*/

		/*
		if (!Auth::checkCreditCard($cc))
			$this->add_error('The credit card on this account is not valid. Please enter a valid credit card to continue making reservations.');
		*/

		// If it's a direct bill, return, we're good
		if ($this->paymentProfileId == "00") return;

		$curProfile = $this->db->getPaymentProfile($this->paymentProfileId);

		if (!Auth::checkCCDate($curProfile['expdate'], $this->date))
			$this->add_error('The expiration date for your selected credit card ('.$curProfile['expdate'].') is not valid or expires before the reservation date. Please check that it is current.');
	}

	/* return array with moratoriumed times */
	function get_moratoriums() {
		$return = array();

		$return['sfo_moratorium_start'] = mktime(14,0,0,2,28,2011);
		$return['sfo_moratorium_end'] = mktime(17,0,0,2,28,2011);

		$return['sfo_moratorium_start2'] = mktime(19,0,0,2,19,2010);
		$return['sfo_moratorium_end2'] = mktime(22,0,0,2,19,2010);

		$return['bos_moratorium_start'] = mktime(15,0,0,3,2,2011);
		$return['bos_moratorium_end'] = mktime(17,0,0,3,2,2011);

		return $return;
	}

	/**
	* Verify that the user selected appropriate times
	* @return if the time is valid
	*/
	function check_times() {

		$curtime = time();
		$fr = $this->machid;
		$to = $this->toLocation;
		$suv = strrchr($this->specialItems, 'S')===false?false:true;
		$van = strrchr($this->specialItems, 'N')===false?false:true;
		$lux = strrchr($this->specialItems, 'L')===false?false:true;
		$frLoc = $this->db->get_resource_data($fr);
		$toLoc = $this->db->get_resource_data($to);	
		$day = date("D", $this->date);

		// $reason is a string describing why a reservation was blocked
		// $reasonCode is a code for DB lookup
		$reason = "";
		$reasonCode = 100;
		$bypass = $_POST['bypass'] ? 1 : 0;
		
		$is_valid = true;
		$isValidSuv = true;
		$inCA = 0;
		$inCA = (strtoupper($frLoc['state']) == 'CA' || strtoupper($toLoc['state']) == 'CA' ? 1 : 0);
		$area = $inCA ? 'CA' : 'MA';
		$suvLimit = $inCA ? 1 : 2;

		// If after 8pm, the morning cutoff is the time the next
		// morning when we will block a reservation
		$morning_cutoff = $inCA ? 7 : 5;

		if ($inCA && $van) {
			$isValidSuv = false;
			$this->add_error('Vans are not yet available in the California area.<br />');
		//} else if ($van && ($this->start<1080 && $day!='Sat' && $day!='Sun')) {
		//	$isValidSuv = false;
		//	$this->add_error('Vans are currently available on weekdays after 6pm.<br />');
		} else if ($van && $suv) {
			$isValidSuv = false;
			$this->add_error('Please choose either SUV or van, not both, for a single reservation. Took book more than one SUV, or a SUV and a van, please enter multiple reservations.<br />');
		}

		// Check if it has already been dispatched
		/*
		if (!Auth::isAdmin() && ($this->type == 'm' || $this->type == 'd')) {
			$okdisp = $this->db->check_disp_state($this->id);
			if (!$okdisp) {
				$this->add_error('That reservation cannot be altered online because it has already been dispatched. Please call 888-756-8876 to change or delete that reservation.<br>');
				$is_valid = false;
			}
		}
		*/		


		$timestuff = array();
		$timestuff = localtime($curtime, 1);
		$curhour = $timestuff['tm_hour'];
		$difftime = $this->date+$this->start*60 - $curtime + $inCA * 3600 * 3;
		$datetime = $this->date + ($this->start * 60);
		
		$is_super = ($_SESSION['role'] == 'm');
		$is_super_super = Auth::isSuperAdmin();

		//in the past
		if($difftime + 3600*2 < 0 && $is_super && !$is_super_super) {
			$is_valid = false;
			$this->add_error("Not even superusers can make reservations for more than two hours in the past.  Go and double check your information." . "<br/><br/>");
			$reason = "More than 2 hours in the past";
			$reasonCode = 1;
		}
		//in the past
		if($difftime < 0 && !$is_super) {
			$is_valid = false;
//			$this->add_error("We can't take reservations in the past!  Please go back and put in the correct date and time." . "<br/><br/>");
			$this->add_error("We can't take reservations in the past!  Please put in the correct date and time." . "<br/><br/>");
			$reason = "In the past";
			$reasonCode = 2;
		}
		//less than 1 hour
		if($difftime > 0 && $difftime < 1*75*60 && !$is_super && !$bypass) {
			$is_valid = false;
			$this->add_error('Unfortunately, we cannot accept online reservations inside of seventy-five minutes.  Please call 1 888 756 8876 to check availability and book your reservation.' . '<br/><br/>');
			$reason = "Less than 1 hour in advance";
			$reasonCode = 3;
		}
		//after 8p for before 5a the next day 
		if($difftime < 12*3600 && $this->start/60 <= $morning_cutoff && ($curhour >= 20 || $curhour < $morning_cutoff) && !$is_super && !$bypass) {
			$is_valid = false;
			$this->add_error("Thanks for trying to book your car reservation online, but we can't confirm your reservations automatically for tomorrow morning.  However, we should be able to accomodate your needs.  Please call 1 888 756 8876 to check availability and book your reservation." . "<br/><br/>");
			$reason = "After 8pm for before 5am the next day";
			$reasonCode = 4;
		}

		/*************************************
		* Moratoriums
		*************************************/
		
		if($this->db->is_moratoriumed($datetime, $area)) {
			if (!$is_super) {
				$this->add_error('Due to high demand during the time you have requested a reservation, your reservation cannot be confirmed automatically.  Please call 1-888-PLNT-TRN (888-756-8876) to check availability and book your reservation.');
				$is_valid = false;
				$reason = "$area manual moratorium";
				$reasonCode = $area == 'CA' ? 5 : 6;
			} else
				$this->add_warning('This reservation is for a time that has been moratoriumed by an admin. Check with an admin or dispatcher because we may be overbooked.');
		} 

		/*
		* Auto-moratoriums
		*/
		$car = "";
		if ($van && $this->db->van_over_limit($this->start,$this->date, $this->type, $this->id, 1)) {
			if (!$is_super) {
//				$is_valid = false;
//				$this->add_error('Due to high van demand during the time you have requested a reservation, please call 1-888-PLNT-TRN (888-756-8876) to check availability and confirm your reservation.<br /><br />');
				$this->add_warning('Due to high van demand during the time you have requested a reservation, please call 1-888-PLNT-TRN (888-756-8876) to check availability and confirm your reservation.<br /><br />');
				$reason = "VAN auto-moratorium";
				$reasonCode = 7;
			} else
				$this->add_warning('The van is booked during that date and time. Please check with an admin or dispatcher about whether we can take the reservation.');
		 } else if ($lux && $this->db->luxury_over_limit($this->start,$this->date, $this->type, $this->id, 1)) {
			if (!$is_super) {
//				$is_valid = false;
//				$this->add_error('Due to high luxury vehicle demand during the time you have requested a reservation, please call 1-888-PLNT-TRN (888-756-8876) to check availability and confirm your reservation.<br /><br />');
				$this->add_warning('Due to high luxury vehicle demand during the time you have requested a reservation, please call 1-888-PLNT-TRN (888-756-8876) to check availability and confirm your reservation.<br /><br />');
				$reason = "LUXURY auto-moratorium";
				$reasonCode = 7;
			} else
				$this->add_warning('The Luxury Lexus 250h is booked during that date and time. Please check with an admin or dispatcher about whether we can take the reservation.');
		} else if ($suv && $this->db->suv_over_limit($this->start,$this->date, $this->type, $this->id, $suvLimit)){
			if (!$is_super) {
//				$is_valid = false;
//				$this->add_error('Due to high SUV demand during the time you have requested a reservation, please call 1-888-PLNT-TRN (888-756-8876) to check availability and confirm your reservation.<br /><br />');
				$this->add_warning('Due to high SUV demand during the time you have requested a reservation, please call 1-888-PLNT-TRN (888-756-8876) to check availability and confirm your reservation.<br /><br />');
				$reason = "SUV auto-moratorium";
				$reasonCode = 8;
			} else
				$this->add_warning('The SUVs are booked during that date and time. Please check with an admin or dispatcher about whether or not we can take the reservation..');
		} else if (!$van && !$suv && $this->db->cars_over_limit($this->start,$this->date, $this->type, 30, $inCA)){
			$car = "CARLIMIT";
			if (!$is_super) {
//				$is_valid = false;
//				$this->add_error('Due to high demand during the time you have requested a reservation, please call 1-888-PLNT-TRN (888-756-8876) to check availability and confirm your reservation.<br /><br />');
				$this->add_warning('Due to high demand during the time you have requested a reservation, please call 1-888-PLNT-TRN (888-756-8876) to check availability and confirm your reservation.<br /><br />');
				$reason = "Car limit auto moratorium";
				$reasonCode = 9;
			} else
				$this->add_warning('We\'ve passed the reservation limit for that date and time. We may be overbooked! Check with a dispatcher or admin about whether or not we can take this reservation.');
		}

		//if (isset($_POST['bypass']))
		//	$is_valid = true;

		if (!$is_valid || !$isValidSuv) {
			if ($van) $header = "VAN";
			else if ($suv) $header = "SUV";
			else if ($lux) $header = "LUXURY";
			else $header = $car . ($inCA ? '_SF' : '_MA');
			$mailer = new PHPMailer();
			$mailer->ClearAllRecipients();
			$p = print_r($_POST, 1);
			//$mailer->AddAddress('seth@planettran.com', 'Seth Riney');
			$mailer->AddAddress('msobecky@gmail.com', 'Matt Sobecky');	
			$mailer->Subject = $header." ".$this->memberid . " just tried to make a reservation";
			$mailer->Body = ($this->start/60).date(" m/d/Y", $this->date)."\nReason: $reason\n$p";
			$mailer->Send();

			$turnawayCoast = $inCA ? 'CA' : 'MA';
			$this->db->insert_turnaway($reason, $reasonCode, $this->memberid, $turnawayCoast);
		}
		
		if(!$isValidSuv)
			$is_valid = false;
		return $is_valid;
	}

	/**
	* Check to make sure that the reservation falls within the specified reservation length
	* @param int $min minimum reservation length
	* @param int $max maximum reservation length
	* @param boolean $kill whether to kill the process if the check fails
	* @return if the time is valid
	*/
	function check_min_max($min, $max) {
		$this_length = ($this->end - $this->start);
		$is_valid = ($this_length >= ($min)) && (($this_length) <= ($max));
		if (!$is_valid)
			$this->add_error(translate('Reservation length does not fall within this resource\'s allowed length.') . '<br /><br >'
					. translate('Your reservation is') . ' ' . CmnFns::minutes_to_hours($this->end - $this->start) . '<br />'
					. translate('Minimum reservation length') . ' ' . CmnFns::minutes_to_hours($min). '<br />'
					. translate('Maximum reservation length') . ' ' . CmnFns::minutes_to_hours($max)
					);
		return $is_valid;
	}

	/**
	* Checks to see if a time is already reserved
	* @param bool $kill whether to kill the app
	* @return whether the time is reserved or not
	*/
	function check_res() {
		$is_valid = !($this->db->check_res($this));
		if (!$is_valid) {
			$this->add_error(translate('reserved or unavailable', array(translate_date('res_check', $this->date), CmnFns::formatTime($this->start), CmnFns::formatTime($this->end))));
		}
		return $is_valid;
	}

	/**
	* Check if a user has permission to use a resource
	* @param object $user object for this reservations user
	* @param bool whether to kill the app if the user does not have permission
	* @return whether user has permission to use resource
	*/
	function check_perms(&$user, $kill = true) {
		if (Auth::isAdmin())					// Admin always has permission
			return true;

		$has_perm = $user->has_perm($this->machid); // Get user permissions

		if (!$has_perm)
			CmnFns::do_error_box(
					translate('You do not have permission to use this resource.')
					, 'width: 90%;'
					, $kill);

		return $has_perm;
	}

	/**
	* Prints out the reservation table
	*/
	function print_res()
  {
    if($_POST)
    {
      $values = $_POST;
    }
    elseif($this->id){
      $this->load_by_id();
//      echo '<pre>';
//      print_r($this);
//      echo '</pre>';

				$split = '{`}';
        $splitted = explode($split, $this->flightDets);

      $start = ($this->start > 779) ? $this->start - 720 : $this->start;
      $values = array(
        'acode' => $splitted[0],
        'fnum' => $splitted[1],
        'fdets' => $splitted[2],

        'date' => date('m/d/Y', $this->date),
        'start_hour' => floor($start / 60),
        'start_minutes' => floor($start % 60),
        'from_location' => $this->machid,
        'ampm' => ($this->start > 779) ? 'pm' : 'am', // 779 = 12*60+59
//        'wait' => $this->???,
        'to_location' => $this->toLocation,
        'stop' => $this->stopLoc ? 1 : 0,
        'stopLoc' => $this->stopLoc,
        'memberid' => $this->memberid,
        'summary' => $this->summary,
        // 'special' => $this->special,
        'scheduleid' => $this->scheduleid,
        'paymentProfileId' => $this->paymentProfileId,
        'autoBillOverride' => $this->autoBillOverride,
        'convertible_seats' => $this->convertible_seats,
        'booster_seats' => $this->booster_seats,
        'carTypeSelect' => $this->vehicle_type,
        'pax' => $this->passenger_count,
        'ccard' => $this->paymentProfileId,
        'coupon' => $this->coupon,
        'greet' => $this->meet_greet,
      );
//      print_r($values);
    }
    else
    {
      // FIXME: populate values
      $values = array();
      if($_GET['from'])
      {
	$fromLoc = mysql_fetch_assoc(mysql_query("SELECT * FROM resources where machid='".addslashes($_GET['from'])."'"));
	$values['from_name']    = $fromLoc['name'];
	$values['from_address'] = $fromLoc['address1'];
	$values['from_city']    = $fromLoc['city'];
	$values['from_state']   = $fromLoc['state'];
	$values['from_zip']     = $fromLoc['zip'];
      }
      if($_GET['to'])
      {
	$toLoc = mysql_fetch_assoc(mysql_query("SELECT * FROM resources where machid='".addslashes($_GET['to'])."'"));
	$values['to_name']    = $toLoc['name'];
	$values['to_address'] = $toLoc['address1'];
	$values['to_city']    = $toLoc['city'];
	$values['to_state']   = $toLoc['state'];
	$values['to_zip']     = $toLoc['zip'];
      }
    }

    if(!class_exists('Account')) {
	require_once dirname(__FILE__).'/Account.class.php';
    }

?>
<style>
.from_airport_option,
.to_airport_option {
  display: none;
}
</style>
<script>
(function($){

  $.fn.serializeObject = function() {
    if ( !this.length ) { return false; }

    var $el = this,
      data = {},
      lookup = data; //current reference of data

      $el.find(':input[type!="checkbox"][type!="radio"], input:checked').each(function() {
        // data[a][b] becomes [ data, a, b ]
        var named = this.name.replace(/\[([^\]]+)?\]/g, ',$1').split(','),
            cap = named.length - 1,
            i = 0;

        // Ensure that only elements with valid `name` properties will be serialized
        if ( named[ 0 ] ) {
          for ( ; i < cap; i++ ) {
              // move down the tree - create objects or array if necessary
              lookup = lookup[ named[i] ] = lookup[ named[i] ] ||
                  ( named[i+1] == "" ? [] : {} );
          }

          // at the end, psuh or assign the value
          if ( lookup.length != undefined ) {
               lookup.push( $(this).val() );
          }else {
                lookup[ named[ cap ] ] = $(this).val();
          }

          // assign the reference back to root
          lookup = data;

        }
      });

    return data;
  };
})(jQuery);

    var vehicles = {
      <?php
	$tools = new Tools();
	foreach($tools->car_select_details() as $k=>$v): ?>
	<?php echo $k ?>: {name: '<?php echo addslashes($v['name']) ?>', price: <?php echo addslashes($v['price']) ?> },
      <?php endforeach ?>
      X: {}
    };
  
    function refresh_estimate()
    {
      
      $.ajax({
	url:  'ajaxquote.php',
	type: 'POST',
	data: $.extend($("#steps_main").serializeObject(), getAddresses()),
	success: function(response)
	{
	  var data = response.split("|");
	  
	  var content = '';
	  var esubtotal = 0;
	  
	  var base_price = parseFloat(data[4]);
	  esubtotal += base_price;
	  content = content + '<div class="line_item group">'+
	    '<span class="line_description">Estimated fare for Prius Sedan (including applicable tolls):</span>'+
	    '<span class="price" id="total_price">$'+base_price+'</span>'+
	  '</div>';
	  
	  var cts = $("[name=carTypeSelect]:checked");
	  if(cts.val() != "" && cts.val() != "P") {
	    var vehicle = vehicles[cts.val()];
	    if(vehicle) {
	      var vehicle_price = vehicle.price;
	      esubtotal += vehicle_price;
	      
	      content = content + '<div class="line_item group">'+
		'<span class="line_description">Vehicle upgrade ('+vehicle.name+'):</span>'+
		'<span class="price" id="total_price">$'+vehicle_price+'</span>'+
	      '</div>';
	    }
	  }
	  
	  var children_seats = $("#child_seats_outgoing");
	  if(children_seats.val() != "0") {
	    var children_seats_price = 15*parseInt(children_seats.val());
	    esubtotal += children_seats_price;
	    
	    content = content + '<div class="line_item group">'+
	      '<span class="line_description">Children seats ('+children_seats.val()+'):</span>'+
	      '<span class="price" id="total_price">$'+children_seats_price+'</span>'+
	    '</div>';
	  }
	  
	  var booster_seats = $("#booster_seats_outgoing");
	  if(booster_seats.val() != "0") {
	    var booster_seats_price = 15*parseInt(booster_seats.val());
	    esubtotal += booster_seats_price;
	    
	    content = content + '<div class="line_item group">'+
	      '<span class="line_description">Booster seats ('+booster_seats.val()+'):</span>'+
	      '<span class="price" id="total_price">$'+booster_seats_price+'</span>'+
	    '</div>';
	  }

	  var intermediate_stop = $("#intermediate_stop");
	  if(intermediate_stop.is(":checked")) {
	    var intermediate_stop_price = 70;
	    esubtotal += intermediate_stop_price;
	    
	    content = content + '<div class="line_item group">'+
	      '<span class="line_description">One intermediate stop:</span>'+
	      '<span class="price" id="total_price">$'+intermediate_stop_price+'</span>'+
	    '</div>';
	  }

	  content = content + '<div class="line_item group total">'+
	    '<span class="line_description">Estimate subtotal:</span>'+
	    '<span class="price" id="total_price">$'+esubtotal+'</span>'+
	  '</div>';

	  var total_fare_1 = parseFloat(data[0]);
	  if(!total_fare) total_fare = esubtotal;
	  var total_fare = esubtotal - base_price + total_fare_1;

	  if(parseFloat(data[3]))
	  {
	    var coupon = esubtotal - total_fare;
	    content = content + '<div class="line_item group total">'+
	      '<span class="line_description">Coupon:</span>'+
	      '<span class="price" id="total_price">-$'+coupon+'</span>'+
	    '</div>';

	    $("#coupon_code").parent().find('.msg').remove();
	    $('<div class="msg" style="color:#0f0;">Provided coupon code is valid!</div>').insertBefore($("#coupon_code"));
	  } else if($("#coupon_code").val()) {
	    $("#coupon_code").parent().find('.msg').remove();
	    $('<div class="msg" style="color:#f00;">Provided coupon code is wrong!</div>').insertBefore($("#coupon_code"));
	    alert('Provided coupon code is wrong!');
	  }
	  
	  content = content + '<div class="line_item group total">'+
	    '<span class="line_description">Total estimated fare:</span>'+
	    '<span class="price" id="total_price">$'+total_fare+'</span>'+
	  '</div>';
	  
	  $("[name=estimate]").val("$"+total_fare);
	  $('#reservation_summary').html(content);
	}
      });
      
    };

    function getAddresses()
    {
	var fromAddr,fromCity,fromZip,toAddr,toCity,toZip,stopAddr,stopCity,stopZip,airport;
	
	customFrom = $("#saved_locations_from");
	if(customFrom.val() == "") {
	  fromAddr = $("#from_street_address").val();
	  fromCity = $("#from_city").val();
	  fromState = $("#from_state").val();
	  fromZip  = $("#from_zipcode").val();
	} else {
	  customFromO = customFrom.find("option:selected");
	  fromAddr = customFromO.attr("data-addr");
	  fromCity = customFromO.attr("data-city");
	  fromState = customFromO.attr("data-state");
	  fromZip  = customFromO.attr("data-zip");
	}

	customTo = $("#saved_locations_to");
	if(customTo.val() == "") {
	  toAddr = $("#to_street_address").val();
	  toCity = $("#to_city").val();
	  toState = $("#to_state").val();
	  toZip  = $("#to_zipcode").val();
	} else {
	  customToO = customTo.find("option:selected");
	  toAddr = customToO.attr("data-addr");
	  toCity = customToO.attr("data-city");
	  toState = customToO.attr("data-state");
	  toZip  = customToO.attr("data-zip");
	}

	customStop = $("#saved_locations_stop");
	if(customStop.val() == "") {
	  stopAddr  = $("#stop_street_address").val();
	  stopCity  = $("#stop_city").val();
	  stopState = $("#stop_state").val();
	  stopZip   = $("#stop_zipcode").val();
	} else {
	  customStopO = customTo.find("option:selected");
	  stopAddr  = customToO.attr("data-addr");
	  stopCity  = customToO.attr("data-city");
	  stopState = customToO.attr("data-state");
	  stopZip   = customToO.attr("data-zip");
	}

	return {
	  'from_address': fromAddr,
	  'from_city':    fromCity,
	  'from_zip':     fromZip,
	  'from_state':   fromState,
	  'to_address':   toAddr,
	  'to_city':      toCity,
	  'to_state':     toState,
	  'to_zip':       toZip,

	  
	  'stop_addr':   stopAddr,
	  'stop_city':   stopCity,
	  'stop_state':    stopState,
	  'stop_zip':    stopZip,

	  'to_location': '',
	  'from_location': '',
	  'stop_location': ''

	};
    };


    function getCQAddresses()
    {
	$("#get_a_quote_button").attr("disabled", 1);
	
	var fromAddr,fromCity,fromZip,toAddr,toCity,toZip,stopAddr,stopCity,stopZip,airport;
	
	customFrom = $("#quote_saved_locations_from");
	if(customFrom.val() == "") {
	  fromAddr = $("#quote_from_street_address").val();
	  fromCity = $("#quote_from_city").val();
	  fromState = $("#quote_from_state").val();
	  fromZip  = $("#quote_from_zipcode").val();
	} else {
	  customFromO = customFrom.find("option:selected");
	  fromAddr = customFromO.attr("data-addr");
	  fromCity = customFromO.attr("data-city");
	  fromZip  = customFromO.attr("data-zip");
	}

	customTo = $("#quote_saved_locations_to");
	if(customTo.val() == "") {
	  toAddr = $("#quote_to_street_address").val();
	  toCity = $("#quote_to_city").val();
	  toState = $("#quote_to_state").val();
	  toZip  = $("#quote_to_zipcode").val();
	} else {
	  customToO = customTo.find("option:selected");
	  toAddr = customToO.attr("data-addr");
	  toCity = customToO.attr("data-city");
	  toZip  = customToO.attr("data-zip");
	}

	return {
	  'from_address': fromAddr,
	  'from_city': fromCity,
	  'from_zip':  fromZip,
	  'from_state':   fromState,
	  'to_address':   toAddr,
	  'to_city':   toCity,
	  'to_state':   toState,
	  'to_zip':    toZip,
	  'airport':   airport
	};
    };


  $(function() {

    $("#child_seats_outgoing, #booster_seats_outgoing")
      .change(function(e) {
	var cs = $("#child_seats_outgoing");
	var bs = $("#booster_seats_outgoing");
	var sum = parseInt(cs.val()) + parseInt(bs.val());
	
	if(sum > 3) {
	  alert("You cannot choose more than 3 children/booster seats.");
	  $(this).find("option[value=0]").attr('selected','1');
	  return false;
	}
	
	
      });

      $("[name=apts_from],[name=apts_to]")
	.change(function() {
	  var pi = $(this).parents().filter('.half_column');
	  pi.find("[name=from_name],[name=to_name]").val($('option:selected', this).text());
	  pi.find("[name=from_address],[name=to_address]").val($('option:selected', this).attr("data-address"));
	  pi.find("[name=from_zip],[name=to_zip]").val($('option:selected', this).attr("data-zip"));
	  pi.find("[name=from_city],[name=to_city]").val($('option:selected', this).attr("data-city"));
	  pi.find("[name=from_state],[name=to_state]").val($('option:selected', this).attr("data-state"));
	});

    $("#quote_tabs").tabs("#quote_tabs_content > div");
    var api2 = $("#quote_tabs").data("tabs");
    <?php if(!isset($_GET['tab']) || $_GET['tab'] == 2): ?>
      api2.next();
    <?php endif ?>
    
    $("#order_steps").tabs("#steps_main > div");
    var api = $("#order_steps").data("tabs");

    var stepNb = 0;
    <?php if($this->has_warnings()): ?>
      refresh_estimate();
      api.next(); api.next(); api.next();
    <?php endif ?>

    $("input.next").not('[id=get_a_quote_button]').click(function() {
      var nb = parseInt($($(this).parents().filter('[class*=step]')[0]).attr("class").replace("step", ""))+1;
      if(nb == 2)
      { 
	if($("#reservation_date").val() == '') {
	  alert('You have to choose a reservation date!');
	  return;
	}
	$.ajax({
	  url:  'ajaxquote.php',
	  type: 'POST',
	  data: $.extend($("#steps_main").serializeObject(), getAddresses()),
	  success: function(response)
	  {
	    var p = response.split("|");
	    var price = parseFloat(p[0]);
	    if(!price || price == NaN || price == "NaN") {
	      alert("One of the addresses you have provided is wrong!");
	    } else {
	      history.pushState({ isMine:true }, "step"+nb, "reserve.php?type=r&step="+nb);
	      refresh_estimate();
	      api.next();
	    }
	    return;
	  }
	});
      } else {
	history.pushState({ isMine:true }, "step"+nb, "reserve.php?type=r&step="+nb);
	refresh_estimate();
	api.next();
      }
    });

    $("input.prev").not('[id=get_a_quote_button]').click(function(){
      var nb = parseInt($($(this).parents().filter('[class*=step]')[0]).attr("class").replace("step", ""))-1;
      history.pushState({isMine:true}, "step"+nb, "reserve.php?type=r&step="+nb);

      api.prev();
    });

    $("#coupon_code").blur(refresh_estimate);

    $(window).bind("popstate", function(data) {
	var l = data.delegateTarget.location.href;
	if(l.indexOf('step=') == -1) return;
	var step = parseInt(l.split('step=')[1]) - 1;
	api.click(step);
	// console.log(data.delegateTarget.location.href);
	// console.log(data.currentTarget.location.href);
        // if (data.state.isMine)
        //     $.getScript(location.href); 
     });
    var Cx = $('#steps_main');
    $('[name=from_location],[name=from_address],[name=to_address],[name=to_location]', Cx)
      .change(function() {
        var froma = $('[name=from_address]', Cx).val();
	var froml = $('[name=from_location] option:selected', Cx).text();
	
        var toa = $('[name=to_address]', Cx).val();
        var tol = $('[name=to_location] option:selected', Cx).text();
	
	var v1 = froma ? froma : froml;
	var v2 = toa ? toa : tol;
	
        $($('.order_details li:nth-child(2)', Cx)).text('Pickup Location: '+v1).text();
        $($('.order_details li:nth-child(3)', Cx)).text('Drop-off Location: '+v2).text();
      });

    $('label[for=from_zipcode] a,label[for=to_zipcode] a')
      .click(function(e) {
	e.preventDefault();
	
	var f = $($(this).parents().filter('[id*=locations_]')[0]);
	
	var address = f.find('input[name*=address]');
	var city = f.find('input[name*=city]');
	var state =  f.find('input[name*=zip]');
	var zip = f.find('input[name*=zip]');
	
	// console.log(address);
	// console.log(city);
	// console.log(state);
	// console.log(zip);
	zip_lookup(address, city, state, zip);
      });

    $('#get_a_quote_button')
      .click(function() {
	$.ajax({
	  url: 'ajaxquote.php',
	  data: getCQAddresses(),
	  type: 'POST',
	  success: function(response)
	  {
	    $("#get_a_quote_button").removeAttr("disabled");
	    
	    var price = response.split("|");
	    var quickVal = parseFloat(price[0]);
	    if(!quickVal || quickVal == NaN || quickVal == "NaN") {
	      alert("One of the addresses you have provided is wrong!");
	      return;
	    }
	    $("#quote_contents").show().find('.price').html(price[0]+"");
	  }
	});
      });
    
    $('[name=from_location],[name=to_location]')
      .change(function() {
	if($(this).val() == '') {
	  $(this).next().show();
	} else {
	  $(this).next().hide();
	}
      })
      .change();
    
  });
</script>

<h1 id="hdr_reservations"><span class="imagetext">Reservations</span></h1>

<ul class="form_tabs group" id="quote_tabs">
  <li id="tab1">Quick Quote</li>
  <li id="tab2" class="current">Book a Ride</li>
</ul>

<div id="quote_tabs_content">
  
  <div>
    
    <form method="post" action="">

      <input type="hidden" name="id" value="<?php echo !empty($values['id']) ? $values['id'] : uniqid() ?>" />

      <div class="step1"><!-- step1 -->
	<div class="group">
	  <div style="display:none">
	    <ol id="quotes_steps" class="group">
	      <!-- These steps are links for the purposes of development use only.  Links should be removed in production code. -->
	      <li class="current">Where</li>
	      <li>Quote</li>
	    </ol>
	  </div>
	  
	  <div style="width:100%;margin: -15px 0 15px 0;display:none;" id="quote_contents">
	    <h2>Estimated flat-rate fare: $<span class="price"></span></h2>
	    <h3>Additional Information:</h3>
	    <ul style="list-style-type:disc">
		<li>The quote is based on distance and does not include applicable wait time.</li>
		<li>The quote does not include vehicle upgrade charges or charges for infant or booster seats. Fares include tolls. Airport fees are NOT included.</li>
		<li>PlanetTran does not charge fuel surcharges.</li>
		<li>Tips are neither expected nor included in our flat-rate pricing.</li>
	    </ul>  
	  </div>
	  <!-- START LEFT COLUMN -->
	  <fieldset id="pickup" class="half_column">

	    <legend>From</legend>
	    <a href="#" onclick="$(this).next().find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>
	    <select id="quote_saved_locations_from" name="from_location" class="saved_locations">
	      <option value="">Saved locations</option>
	      <?php foreach(Account::getSavedLocations() as $location): ?>
		<option <?php if($location['machid'] == $values['from_location']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
		      data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
		      data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
		  <?php echo $location['name'] ?>
		</option>
	      <?php endforeach; ?>
	    </select>
	  <div id="saved_locations_from_wrap1">

	    <div id="from_address_wrap1" class="from_location_option1">
	      <div class="row group">
		<label for="from_street_address">Street Address</label><br />
		<input name="from_address" type="text" id="quote_from_street_address" value="<?php echo $values['from_address'] ?>" />
	      </div>
	      <div class="row group">
		<label for="from_city">City</label><br />
		<input name="from_city" type="text" id="quote_from_city" value="<?php echo $values['from_city'] ?>" />
	      </div>
	      <div class="row group">
		<label for="from_state">State</label><br />
		<input name="from_state" type="text" id="quote_from_state" value="<?php echo $values['from_state'] ?>" />
	      </div>
	      <div class="row group">
		<label for="from_zipcode">Zip Code <a href="/pop/zip.php" class="popover" title="Zip code lookup">(look up)</a></span></label><br />
		<input name="from_zip" type="text" id="quote_from_zipcode" value="<?php echo $values['from_zip'] ?>" />
	      </div>
	    </div><!-- /from_address -->

	  </div>

	  <div id="from_airport_wrap" class="from_airport_option">
	    <div class="group">
	      <!-- Conditionally shown based on radio selection above -->
	      <div class="row group">
		<select name="apts_from">
		  <option value="">Select an airport</option>
		  <?php echo get_airports_options() ?>
		</select>
	      </div>
	      <div class="row group">
		<select name="acode">
		  <option value="">Select an airline</option>
		  <?php foreach(Account::getAirlines() as $key => $v): ?> 
		    <option value="<?php echo $key ?>" <?php if($key == $values['acode']) echo 'selected="selected"' ?>><?php echo $v ?></option>
		  <?php endforeach; ?>
		</select>
	      </div>
	      <span id="flight_details">
		<div class="row  group">
		  <label for="fnum">Flight #</label>
		  <input name="fnum" value="<?php echo $values['fnum'] ?>" type="text" id="fnum" class="flight_no" />
		</div>
		<div class="row  group">
		  <label for="fdets">Time/Other details</label>
		  <input name="fdets" type="text" value="<?php echo $values['fdets'] ?>" id="fdets" class="flight_details" />
		</div>
	      </span>
	    </div>
	  </div>
	  </fieldset>
	  <!-- END LEFT COLUMN -->


	  <!-- START RIGHT COLUMN -->
	  <fieldset id="dropoff" class="half_column">

	    <legend>To</legend>

	    <a href="#" onclick="$(this).next().find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>
	    <select id="quote_saved_locations_to" name="to_location" class="saved_locations">
	      <option value="">Saved locations</option>
	      <?php foreach(Account::getSavedLocations() as $location): ?>
		<option <?php if($location['machid'] == $values['to_location']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
			data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
			data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
		  <?php echo $location['name'] ?>
		</option>
	      <?php endforeach; ?>
	    </select>
	  <div id="saved_locations_to_wrap1">

	    <div id="to_address_wrap1" class="to_location_option1">
	      <!-- Conditionally shown based on radio selection above -->
	      <div class="row group">
		<label for="to_street_address">Street Address</label><br />
		<input name="to_address" type="text" id="quote_to_street_address" value="<?php echo $values['to_address'] ?>" />
	      </div>
	      <div class="row group">
		<label for="to_city">City</label><br />
		<input name="to_city" type="text" id="quote_to_city" value="<?php echo $values['to_city'] ?>" />
	      </div>
	      <div class="row group">
		<label for="to_state">State</label><br />
		<input name="to_state" type="text" id="quote_to_state" value="<?php echo $values['to_state'] ?>" />
	      </div>
	      <div class="row group">
		<label for="to_zipcode">Zip Code <span class="popover"><a href="#">Look Up</a></span></label><br />
		<input name="to_zip" type="text" id="quote_to_zipcode" value="<?php echo $values['to_zip'] ?>" />
	      </div>
	    </div><!-- /to_address -->
	  </div>

	  <div id="to_airport_wrap" class="to_airport_option">
	    <div class="group">
	      <!-- Conditionally shown based on radio selection above -->
	      <div class="row group">
		<select name="apts_to">
		  <option value="">Select an airport</option>
		  <?php echo get_airports_options() ?>
		</select>
	      </div>
	      <div class="row group">
		<select name="acode">
		  <option value="">Select an airline</option>
		  <?php foreach(Account::getAirlines() as $key => $v): ?> 
		    <option value="<?php echo $key ?>" <?php if($key == $values['acode']) echo 'selected="selected"' ?>><?php echo $v ?></option>
		  <?php endforeach; ?>
		</select>
	      </div>
	      <span id="flight_details">
		<div class="row  group">
		  <label for="fnum">Flight #</label>
		  <input name="fnum" value="<?php echo $values['fnum'] ?>" type="text" id="fnum" class="flight_no" />
		</div>
		<div class="row  group">
		  <label for="fdets">Time/Other details</label>
		  <input name="fdets" type="text" value="<?php echo $values['fdets'] ?>" id="fdets" class="flight_details" />
		</div>
	      </span>
	    </div>
	  </div>

	  </fieldset>

	</div><!-- /group -->

	<div id="step_navigation" class="hr_top">
	  <input type="button" value="Get a quote &raquo;" id="get_a_quote_button" class="button" />
	</div>
      </div><!-- /step1 -->
    </form>
  </div>
  
  <div>
    <div style="display: none">
      <ol id="order_steps" class="group">
	<!-- These steps are links for the purposes of development use only.  Links should be removed in production code. -->
	<li class="current">When/Where</li>
	<li>Vehicle Selection</li>
	<li>Passenger Info</li>
	<li>Payment</li>
      </ol>
    </div>

    <form name="reserve" id="steps_main" class="group" method="post" action="">

      <input type="hidden" name="id" value="<?php echo !empty($values['id']) ? $values['id'] : uniqid() ?>" />

      <div class="step1"><!-- step1 -->
	<fieldset class="group hr pickup_time">
	  <?php if($_POST && !strtotime($_POST['date'])): ?>
	    <span style="color:#f00;">You have to choose a date!</span>
	  <?php endif ?>
	  <legend>Pickup time</legend>
	  <label for="reservation_date">Date</label>
	  <input name="date" id="reservation_date" type="text" class="date_input" value="<?php echo $values['date'] ?>" />
	  <label for="time_hour">Time</label>
	  <select name="start_hour" id="time_hour" class="time_picker">
	    <?php foreach(range(12, 1) as $v): ?>
	      <?php /*<option <?php if($v == date('h', $this->date)) echo 'selected="selected"' ?> value="<?php echo $v ?>"><?php echo sprintf("%02d",$v) ?></option>*/ ?>
	      <option <?php if($v == $values['start_hour']) echo 'selected="selected"' ?> value="<?php echo $v ?>"><?php echo sprintf("%02d",$v) ?></option>
	    <?php endforeach; ?>
	  </select>
	  :
	  <select name="start_minutes" id="time_minutes" class="time_picker">
	    <?php foreach(range(0, 55, 5) as $v): ?>
	      <?php /*<option <?php if($v == date('i', $this->date)) echo 'selected="selected"' ?> value="<?php echo $v ?>"><?php echo sprintf("%02d",$v) ?></option>*/ ?>
	      <option <?php if($v == $values['start_minutes']) echo 'selected="selected"' ?> value="<?php echo $v ?>"><?php echo sprintf("%02d",$v) ?></option>
	    <?php endforeach; ?>
	  </select>
	  <select name="ampm" id="time_am_pm" class="time_picker">
	    <?php /*<option value="AM" <?php if('am' == date('a', $this->date)) echo 'selected="selected"' ?>>AM</option>
	    <option value="PM" <?php if('pm' == date('a', $this->date)) echo 'selected="selected"' ?>>PM</option>*/ ?>
	    <option value="am" <?php if('am' == $values['ampm']) echo 'selected="selected"' ?>>AM</option>
	    <option value="pm" <?php if('pm' == $values['ampm']) echo 'selected="selected"' ?>>PM</option>
	  </select>
	  <label for="check_by_the_hour">
	    <input name="wait" type="checkbox" <?php if($values['wait']) echo 'checked="checked"' ?> id="check_by_the_hour" />Book by the hour <span id="tip1" class="tip">(?)</span>
	  </label>
	  <div class="tooltip tip1">
	    <p>Reserve by the hour (minimum of 90 minutes) to direct your driver for a period of time or to more than one intermediate stop. These trips are billed at the following rates:</p>
	    <p>
	      <strong>Prius:</strong> $60 per hour<br />
	      <strong>SUV:</strong> $70 per hour<br />
	      <strong>Lexus Sedan:</strong> $75 per hour (Mass only)
	    </p>
	    <p>If the passenger is running late, PlanetTran will wait the entire trip time before creating a no-show billing for the reserved trip.</p>
	  </div>
	</fieldset>

	<div class="group">

	  <!-- START LEFT COLUMN -->
	  <fieldset id="pickup" class="half_column">

	    <legend>From</legend>
	    <a href="#" onclick="$(this).next().find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>
	    <select id="saved_locations_from" name="from_location" class="saved_locations">
	      <option value="">Saved locations</option>
	      <?php foreach(Account::getSavedLocations() as $location): ?>
		<option <?php if($location['machid'] == $values['from_location']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
		      data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
		      data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
		<?php echo $location['name'] ?></option>
	      <?php endforeach; ?>
	    </select>
	  <div id="saved_locations_from_wrap">
	    <!-- Conditionally shown instead of select tag above if logged out
	      <a href="#">Log in to view saved locations</a>
	    -->

	    <div class="radio_buttons">
	      <input type="radio" name="from_type" <?php if(empty($values['from_type']) || $values['from'] == 1) echo 'checked="checked"' ?> value="1" id="from_address" class="from_toggle" /><label for="from_address">Address</label>
	      <input type="radio" name="from_type" <?php if($values['from'] == 'from_airport_wrap') echo 'checked="checked"' ?> value="from_airport_wrap" id="from_airport" class="from_toggle" /><label for="from_airport">Airport</label>
	      <?php /*<input type="radio" name="from_type" <?php if($values['from_type'] == 2) echo 'checked="checked"' ?> value="2" id="from_poi" class="from_toggle" /><label for="from_poi">Point of Interest</label> */ ?>
	    </div>

	    <div id="from_address_wrap" class="from_location_option">
	      <!-- Conditionally and dynamically show this based on radio selection above -->
	      <div class="row group">
		<label for="from_name">Nickname</label><br />
		<input name="from_name" type="text" id="from_name" value="<?php echo $values['from_name'] ?>" />
	      </div>
	      <div class="row group">
		<label for="from_street_address">Street Address</label><br />
		<input name="from_address" type="text" id="from_street_address" value="<?php echo $values['from_address'] ?>" />
	      </div>
	      <div class="row group">
		<label for="from_city">City</label><br />
		<input name="from_city" type="text" id="from_city" value="<?php echo $values['from_city'] ?>" />
	      </div>
	      <div class="row group">
		<label for="from_state">State</label><br />
		<input name="from_state" type="text" id="from_state" value="<?php echo $values['from_state'] ?>" />
	      </div>
	      <div class="row group">
		<label for="from_zipcode">Zip Code <a href="/pop/zip.php" class="popover" title="Zip code lookup">(look up)</a></span></label><br />
		<input name="from_zip" type="text" id="from_zipcode" value="<?php echo $values['from_zip'] ?>" />
	      </div>
	    </div><!-- /from_address -->

	    <div id="from_poi_wrap" class="from_location_option">
	      <!-- Conditionally shown based on radio selection above -->
	      <div class="row group">
		<select name="apts_from">
		  <option value="">Select an airport</option>
		  <?php echo get_airports_options() ?>
		</select>
	      </div>
	      <div class="row group">
		<select name="acode_from">
		  <option value="">Select an airline</option>
		  <?php foreach(Account::getAirlines() as $key => $v): ?> 
		    <option value="<?php echo $key ?>" <?php if($key == $values['acode']) echo 'selected="selected"' ?>><?php echo $v ?></option>
		  <?php endforeach; ?>
		</select>
	      </div>
	      <span id="flight_details">
		<div class="row  group">
		  <label for="fnum">Flight #</label>
		  <input name="fnum_from" value="<?php echo $values['fnum'] ?>" type="text" id="fnum" class="flight_no" />
		</div>
		<div class="row  group">
		  <label for="fdets">Time/Other details</label>
		  <input name="fdets_from" type="text" value="<?php echo $values['fdets'] ?>" id="fdets" class="flight_details" />
		</div>
	      </span>
	    </div><!-- /from_poi -->
	  </div>
	  <div class="row group">
	    <!-- Unchecked by default.  Checking this will launch the same popover per the "edit" link below -->
	    <input name="stop" value="1" type="checkbox" id="intermediate_stop" <?php if($values['stop']) echo 'checked="checked"' ?> />
	    <label for="intermediate_stop">Add an intermediate stop <span class="tip">(?)</span></label>
	    <div class="tooltip">Intermediate Stop trips add $15 plus wait time over 10 minutes at your intermediate stop to the cost of the trip. Reserve by the Hour to make more than one Intermediate Stop.</div>

	  <div class="intermediate_stop">
	    The intermediate stop location
	    <div id="stop_wrap">
	      <select id="saved_locations_stop" name="stopLoc" class="saved_locations">
		<option value="">Saved locations</option>
		<?php foreach(Account::getSavedLocations() as $location): ?>
		<option <?php if($location['machid'] == $values['from_location']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
		      data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
		      data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>">
		      <?php echo $location['name'] ?></option>
		<?php endforeach; ?>
	      </select>
	      <div id="stop_address_wrap" class="stop_location_option">
		<!-- Conditionally and dynamically show this based on radio selection above -->
		<div class="row group">
		  <label for="stop_name">Nickname</label><br />
		  <input name="stop_name" type="text" id="stop_name" value="<?php echo $values['stop_name'] ?>" />
		</div>
		<div class="row group">
		  <label for="stop_street_address">Street Address</label><br />
		  <input name="stop_address" type="text" id="stop_street_address" value="<?php echo $values['stop_address'] ?>" />
		</div>
		<div class="row group">
		  <label for="stop_city">City</label><br />
		  <input name="stop_city" type="text" id="stop_city" value="<?php echo $values['stop_city'] ?>" />
		</div>
		<div class="row group">
		  <label for="stop_state">State</label><br />
		  <input name="stop_state" type="text" id="stop_state" value="<?php echo $values['stop_state'] ?>" />
		</div>
		<div class="row group">
		  <label for="stop_zipcode">Zip Code <a href="/pop/zip.php" class="popover" title="Zip code lookup">(look up)</a></span></label><br />
		  <input name="stop_zip" type="text" id="stop_zipcode" value="<?php echo $values['stop_zip'] ?>" />
		</div>
	      </div><!-- /stop_address -->
	    </div>
	  </div>


	</div>
    <?php /*
	      <!-- The intermediate stop location, below, is only shown once the above checkbox is selected and resulting popover is completed -->
	      <p>
		723 Main Street<br />
		Boston, MA 02114<br />
		<a href="/pop/stop.php" class="popover" title="Add an intermediate stop">(Edit)</a><!-- Edit link should launch popover with previously-filled data in it, unlike this demo dialog, which has empty fields -->
	      </p>


		  <?php foreach(array(
		    "Logan Int'l Airport (BOS)",
		    "Manchester Airport (MHT)",
		    "T.F. Green Airport (PVD)",
		  ) as $k=>$v): ?>
		    <option value="<?php echo $v ?>" <?php if($v == $values['to_airport']) echo 'selected="selected"' ?>><?php echo $v ?></option>
		  <?php endforeach; ?>
	      <script>
		$(function() {
		  var c = $('[name=acode]');
		  c
		    .change(function() {
		      var det = $('#flight_details');
		      if(c.val() == "") {
			det.hide();
		      } else {
			det.show();
		      }
		    })
		    .change()
		  ;
		});
	      </script>
    */ ?>

	  </fieldset>
	  <!-- END LEFT COLUMN -->


	  <!-- START RIGHT COLUMN -->
	  <fieldset id="dropoff" class="half_column">

	    <legend>To</legend>

	    <a href="#" onclick="$(this).next().find('option').removeAttr('selected').end().find('option:first-child').attr('selected',true).end().change();return false;">clear</a>
	    <select id="saved_locations_to" name="to_location" class="saved_locations">
	      <option value="">Saved locations</option>
	      <?php foreach(Account::getSavedLocations() as $location): ?>
		<option <?php if($location['machid'] == $values['to_location']) echo 'selected="selected"' ?> value="<?php echo $location['machid'] ?>"
		      data-addr="<?php echo htmlspecialchars($location['address1']) ?>" data-zip="<?php echo htmlspecialchars($location['zip']) ?>"
		      data-city="<?php echo htmlspecialchars($location['city'])?>" data-state="<?php echo htmlspecialchars($location['state']) ?>"><?php echo $location['name'] ?></option>
	      <?php endforeach; ?>
	    </select>
	  <div id="saved_locations_to_wrap">
	    <!-- Conditionally show the following instead of the preceeding select tag if user is currently logged out
	      <a href="#">Log in to view saved locations</a>
	    -->

	    <div class="radio_buttons">
	      <input type="radio" <?php if(empty($values['to_type']) || 1 == $values['to_type']) echo 'checked="checked"' ?> name="to_type"  value="1" id="to_address" class="to_toggle" /><label for="to_address">Address</label>
 	      <input type="radio" <?php if('to_airport_wrap' == $values['to_type']) echo 'checked="checked"' ?> name="to_type" value="2" id="to_airport" class="to_toggle" /><label for="to_airport">Airport</label>
	      <?php /*<input type="radio" <?php if(2 == $values['to_type']) echo 'checked="checked"' ?> name="to_type" value="2" id="to_poi"  class="to_toggle" /><label for="to_poi">Point of Interest</label>*/ ?>
	    </div>

	    <div id="to_address_wrap" class="to_location_option">
	      <!-- Conditionally shown based on radio selection above -->
	      <div class="row group">
		<label for="to_name">Nickname</label><br />
		<input name="to_name" type="text" id="to_name" value="<?php echo $values['to_name'] ?>" />
	      </div>
	      <div class="row group">
		<label for="to_street_address">Street Address</label><br />
		<input name="to_address" type="text" id="to_street_addres" value="<?php echo $values['to_address'] ?>" />
	      </div>
	      <div class="row group">
		<label for="to_city">City</label><br />
		<input name="to_city" type="text" id="to_city" value="<?php echo $values['to_city'] ?>" />
	      </div>
	      <div class="row group">
		<label for="to_state">State</label><br />
		<input name="to_state" type="text" id="to_state" value="<?php echo $values['to_state'] ?>" />
	      </div>
	      <div class="row group">
		<label for="to_zipcode">Zip Code <span class="popover"><a href="#">Look Up</a></span></label><br />
		<input name="to_zip" type="text" id="to_zipcode" value="<?php echo $values['to_zip'] ?>" />
	      </div>
	    </div><!-- /to_address -->

	    <div id="to_poi_wrap" class="to_location_option">
	      <!-- Conditionally shown based on radio selection above -->
	      <div class="row group">
		<select name="apts_to">
		  <option value="">Select an airport</option>
		  <?php echo get_airports_options() ?>
		</select>
	      </div>
	      <div class="row group">
		<select name="acode_to">
		  <option value="">Select an airline</option>
		  <?php foreach(Account::getAirlines() as $key => $v): ?> 
		    <option value="<?php echo $key ?>" <?php if($key == $values['acode']) echo 'selected="selected"' ?>><?php echo $v ?></option>
		  <?php endforeach; ?>
		</select>
	      </div>
	      <span id="flight_details">
		<div class="row  group">
		  <label for="fnum">Flight #</label>
		  <input name="fnum_to" value="<?php echo $values['fnum'] ?>" type="text" id="fnum" class="flight_no" />
		</div>
		<div class="row  group">
		  <label for="fdets">Time/Other details</label>
		  <input name="fdets_to" type="text" value="<?php echo $values['fdets'] ?>" id="fdets" class="flight_details" />
		</div>
	      </span>
	    </div><!-- /to_poi -->
	  </div>


	  <div id="to_airport_wrap" class="to_airport_option">
	    <div class="group">
	      <!-- Conditionally shown based on radio selection above -->
	      <div class="row group">
		<select name="apts_to">
		  <option value="">Select an airport</option>
		  <?php echo get_airports_options() ?>
		</select>
	      </div>
	      <div class="row group">
		<select name="acode">
		  <option value="">Select an airline</option>
		  <?php foreach(Account::getAirlines() as $key => $v): ?> 
		    <option value="<?php echo $key ?>" <?php if($key == $values['acode']) echo 'selected="selected"' ?>><?php echo $v ?></option>
		  <?php endforeach; ?>
		</select>
	      </div>
	      <span id="flight_details">
		<div class="row  group">
		  <label for="fnum">Flight #</label>
		  <input name="fnum" value="<?php echo $values['fnum'] ?>" type="text" id="fnum" class="flight_no" />
		</div>
		<div class="row  group">
		  <label for="fdets">Time/Other details</label>
		  <input name="fdets" type="text" value="<?php echo $values['fdets'] ?>" id="fdets" class="flight_details" />
		</div>
	      </span>
	    </div>
	  </div>

	  </fieldset>
	</div><!-- /group -->

	<div id="step_navigation" class="hr_top">
	  <input type="button" value="Step 2 &raquo;" class="button next" />
	</div>
      </div><!-- /step1 -->
      <div class="step2"><!-- step2 -->
	  <h2>Select a vehicle:</h2>

	  <?php $tools = new Tools();
	      foreach($tools->car_select_details() as $k=>$v): ?>
	    <div class="vehicle_desc group">
	      <label for="vehicle<?php echo $k ?>" style="display:block;float: left;width: 95%;">
		<img src="http://placehold.it/145x60&text=Vehicle" width="145" height="60" alt="<?php echo $v['name'] ?>" />
		<div class="vehicle_details">
		  <label for="vehicle1" class="vehicle_name"><?php echo $v['name'] ?></label><br />
		  Seats: <?php echo $v['seats'] ?> passengers<br />
		  Holds: <?php echo $v['suitcases'] ?> suitcases
		</div>
		<div class="vehicle_price">$<?php echo (60+$v['price']) ?></div>
	      </label>
	      <div class="vehicle_chooser">
		<input type="radio" name="carTypeSelect" id="vehicle<?php echo $k ?>" value="<?php echo $k.'' ?>" <?php if($k == $values['carTypeSelect']) echo 'checked="checked"' ?>/>
	      </div>
	    </div>
	  <?php endforeach ?>


	<strong>Details</strong>

	<ul class="order_details">
	  <li>Fare Type: One way</li>
	  <li>Pickup location: Logan Int'l Airport</li>
	  <li>Drop-off location: Manchester Int'l Airport</li>
	</ul>

	<!--strong>Additional Information</strong>

	<ul class="order_details">
	  <li>Fare Type: One way</li>
	  <li>Pickup location: Logan Int'l Airport</li>
	  <li>Drop-off location: Manchester Int'l Airport</li>
	</ul-->

	<p class="order_note">Prices are based on drop-off and pickup locations, and do not reflect additional options or discounts that are shown when making an actual reservation.</p>


	<div id="step_navigation">
	  <input type="button" value="&laquo; Back to Step 1" class="button prev" />
	  <input type="button" value="Step 3 &raquo;" class="button next" />
	</div>
      </div><!-- /step2 -->
      <div class="step3"><!-- step3 -->
	  <h2>Passenger Info</h2>
	  <div class="row group">
	    <div class="labelish">
	      <label for="passengers_outgoing">Passengers</label>
	    </div>
	    <div class="inputs">
	      <select name="pax" id="passengers_outgoing">
		<?php foreach(range(1,3) as $v): ?>
		  <option value="<?php echo $v ?>" <?php if($v == $values['pax']) echo 'selected="selected"' ?>><?php echo $v ?></option>
		<?php endforeach; ?>
	      </select>
	    </div>
	  </div>
	  <div class="row group">
	    <div class="labelish">
	      <label for="child_seats_outgoing">Child Seats</label>
	    </div>
	    <div class="inputs">
	      <select name="convertible_seats" id="child_seats_outgoing">
		<option value="0">no convertible seats $0</option>
		<option value="1" <?php if(1 == $values['convertible_seats']) echo 'selected="selected"' ?>>1 convertible seat $15</option>
		<option value="2" <?php if(2 == $values['convertible_seats']) echo 'selected="selected"' ?>>2 convertible seats $30</option>
		<option value="3" <?php if(3 == $values['convertible_seats']) echo 'selected="selected"' ?>>3 convertible seats $45</option>
	      </select><br />
	      <select name="booster_seats" id="booster_seats_outgoing">
		<option value="0">no booster seats $0</option>
		<option value="1" <?php if(1 == $values['booster_seats']) echo 'selected="selected"' ?>>1 booster seat $15</option>
		<option value="2" <?php if(2 == $values['booster_seats']) echo 'selected="selected"' ?>>2 booster seats $30</option>
		<option value="3" <?php if(3 == $values['booster_seats']) echo 'selected="selected"' ?>>3 booster seats $45</option>
	      </select>
	    </div>
	  </div>


	  <h2>Reservation For</h2>
	  <div class="row group">
	    <div class="labelish">
	      <label for="passenger_name">Passenger</label>
	    </div>
	    <div class="inputs">
	      <select name="scheduleid" id="passenger_name">
		<option value="<?php echo $this->db->get_user_scheduleid($_SESSION['currentID']) ?>"><?php echo $_SESSION['currentName'] ?></option>
	      </select>
	    </div>
	  </div>
	  <div class="row group">
	    <div class="labelish">
	      <label for="project_code">Cost or project code</label>
	    </div>
	    <div class="inputs">
	      <input name="cccode" type="text" id="project_code" value="<?php echo $values['cccode'] ?>" />
	    </div>
	  </div>
	  <!-- Conditionally show the Meet and Greet only if it applies to the reservation per Step 1 (if Pickup or Stop location = Logan Airport) -->
	  <div class="row group">
	    <div class="inputs">
	      <input name="greet" type="checkbox" id="meet_greet" <?php if($values['greet']) echo 'checked="checked"' ?>/>
	      <label for="meet_greet">Logan airport meet and greet $30 <span class="tip">[?]</span></label>
	      <div class="tooltip">The &ldquo;Meet and greet&rdquo; option means that your PlanetTran driver will enter the airport, meet you at your gate, and take you and your luggage to the vehicle parked outside.</div>
	    </div>
	  </div>
	  <div class="row group">
	    <div class="labelish">
	      <label for="special_instructions">Special Instructions</label>
	    </div>
	    <div class="inputs">
	      <textarea name="special" id="special_instructions"><?php echo $values['special'] ?></textarea>
	    </div>
	  </div>
	  <div class="row group admin">
	    <div class="labelish">
	      <label for="customer_service_notes">Customer Service Notes</label>
	    </div>
	    <div class="inputs">
	      <textarea name="summary" id="customer_service_notes"><?php echo $values['summary'] ?></textarea>
	    </div>
	  </div>

	<div id="step_navigation">
	  <input type="button" value="&laquo; Back to Step 2" class="button prev" />
	  <input type="button" value="Step 4 &raquo;" class="button next" />
	</div>
      </div><!-- /step3 -->
      <div class="step4"><!-- step4 -->

	  <h2>Payment Information</h2>
	  <div class="row admin group">
	    <div class="labelish">
	      <label for="auto_billing">Override auto-billing?</label>
	    </div>
	    <div class="inputs">
	      <input name="autoBillOverride" <?php if($values['autoBillOverride']) echo 'checked="checked"' ?> type="checkbox" id="auto_billing" />
	    </div>
	  </div>
	  <div class="row group billing_toggle">
	    <div class="labelish">
	      <label for="payment_method">Payment method</label>
	    </div>
	    <div class="inputs">
	      <select name="paymentProfileId" id="payment_method">
		<?php echo Account::getCreditCardsOptions(null, $values['ccard']) ?>
	      </select>
	      <!-- <a href="/pop/creditcards.php" class="popover-add" title="Add/modify credit cards">Add/Modify cards</a>-->
	      <div id="payment_links_wrap">
		<a href="AuthGateway.php?js=select&memberid=<?php echo $_SESSION['currentID'] ?>&mode=add" class="popover-add" title="Add Credit Card">Add credit card</a>
		<?php foreach(Account::getCreditCards() as $paymentId => $description): ?>
		  <a style="display: none;" href="AuthGateway.php?js=select&memberid=<?php echo $_SESSION['currentID'] ?>&mode=edit&paymentProfileId=<?php echo $paymentId ?>" class="popover-edit" title="Edit Credit Card">Modify credit card</a>
		<?php endforeach; ?>
	      </div>
	    </div>
	  </div>
	  <div class="row parallel group">
	    <div class="labelish">
	      <label for="coupon_code">Coupon code</label>
	    </div>
	    <div class="inputs">
	      <input name="coupon" type="text" id="coupon_code" value="<?php echo $values['coupon'] ?>" />
	    </div>
	  </div>
	  <div class="row group">
	    <div class="labelish">
	      <label for="confirmEmail">Email copy of reservation to</label>
	    </div>
	    <div class="inputs">
	      <input name="confirmEmail" type="text" id="confirmEmail" value="<?php echo $values['confirmEmail'] ?>" />
	    </div>
	  </div>

	<h2>About your reservation</h2>
	<p  class="spacious_top">Lorem ipsum dolor sit amet, in sapien erat interdum, sem tristique ullamcorper vitae, interdum dolore, sed fringilla eleifend. Ligula faucibus neque pede, in tempor ultrices, fames nulla dignissim nam consequat ut, libero odio tempus elementum. Tortor tellus duis, odio viverra arcu sint ultricies, in sagittis malesuada, arcu elit vel praesent semper elit.</p>

	<!-- div id="reservation_summary">
	  <div class="line_item group">
	    <span class="line_description">Estimated fare for Prius Sedan (including applicable tolls)</span>
	    <span class="price">$70.00</span>
	  </div>
	  <div class="line_item group">
	    <span class="line_description">Vehicle upgrade (SUV)</span>
	    <span class="price">+$30.00</span>
	  </div>
	  <div class="line_item group">
	    <span class="line_description">One child seat</span>
	    <span class="price">+$15.00</span>
	  </div>
	  <div class="line_item group hr">
	    <span class="line_description">One intermediate stop</span>
	    <span class="price">+$15.00</span>
	  </div>
	  <div class="line_item group subtotal">
	    <span class="line_description ">Estimate subtotal</span>
	    <span class="price">$130.00</span>
	  </div>
	  <div class="line_item group hr">
	    <span class="line_description">-20% discount rate (except on $9.50 in tolls)</span>
	    <span class="price">-$24.70</span>
	  </div>
	  <div class="line_item group total">
	    <span class="line_description">Total estimated fare:</span>
	    <span class="price">$105.30</span>
	  </div>
	</div -->

	<div id="reservation_summary">
	</div>

	<div id="step_navigation">
	  <input type="button" value="&laquo; Back to Step 3" class="button prev" />
	  <input type="submit" name="submit" value="Book Reservation" class="button" />
	  <input type="hidden" name="estimate" />
	</div>

      </div><!-- /step4 -->
    </form>
  </div>
</div>

<?php
//		global $conf;
//
//		if (!empty($this->id))
//			$this->load_by_id();
//		else
//			$this->load_by_get();
//		$user = new User($this->memberid);
//		$resend_url = $conf['app']['weburi']."/resend.php?id=".$this->memberid;
//		$resend_url = "<br>&nbsp<br><a href=\"$resend_url\">Click here to send the confirmation email again.</a>";
//
//		// location information
//		$toRs = $this->db->get_resource_data($this->toLocation);
//		$fromRs = $this->db->get_resource_data($this->machid);
//		$rs = $this->db->get_resource_data($this->machid);
//
//		$loclist = $this->db->get_user_permissions($this->db->get_user_scheduleid($this->memberid));
//
//		if ($this->type == 'm' || $this->type == 'd') {
//			$trip = $this->db->get_trip_data($this->id);
//
//			//echo Auth::isSuperAdmin();
//
//			if ( (!Auth::has_permission(DISP_WRITE) && !Auth::isSuperAdmin() ) && $trip['dispatch_status'] != 27){
//				print_viewonly_web($loclist, $this->toLocation, $this->machid, $this, $trip);
//				return;
//			} else if (!Auth::isSuperAdmin() && $trip['dispatch_status'] == 12) {
//				print_viewonly_web($loclist, $this->toLocation, $this->machid, $this, $trip);
//				return;
//			}
//		}
//
//
//		// Block reservations for x and u roles
//		if ($_SESSION['role']=='x') {
//			CmnFns::do_error_box(
//				'The credit card or other billing information on file is no longer valid. Please call 888-PLNTTRN (756-8876) during business hours to update your information.',
//				'',
//				true);
//		} else if ($user->role=='u'&&$_SESSION['role']!='m') {
//			CmnFns::do_error_box(
//				"To be able to make reservations, you must first confirm the email address by either clicking the link in the registration email, or copying/pasting it into your browser's address bar. $resend_url",
//				'',
//				true);
//		} else if ($user->role=='u'&&$_SESSION['role']=='m') {
//			CmnFns::do_error_box(
//				"This user has an unconfirmed corporate account. They need to activate it by clicking the link in their registration email. If necessary, we can send the email again. $resend_url",
//				'',
//				true);
//		}
//
//		if ($user->fname == "Saturn" and $this->type == "r") {
//			CmnFns::do_error_box(
//				'Saturn reservations can only be booked through the Saturn system',
//				'',
//				true);
//		}
//
//		if ($user->role == 'x') {
//			CmnFns::do_error_box(
//				'This user\'s account is locked; no reservations may be made or altered. The customer should call 888-PLNTTRN (756-8876) during business hours and select the billing option to resolve the issue. Any upcoming trips can be canceled from the dispatch screen.',
//				'',
//				true);
//		}
//		if ($this->is_blackout == 1) {
//			CmnFns::do_error_box(
//				'This reservation has been deleted.',
//				'',
//				true);
//		}
//
//
//		begin_reserve_form($this->type == 'r', $this->is_blackout);	// Start form
//
//		start_left_cell();
//
//		print_res_header($this->type, $this->id);
//		if($this->type == 'v' || $this->type == 'd') {
//			print_resource_data($fromRs['name'], $toRs['name']);		// Print resource info
//		} else {
//			$toLocs = $this->db->get_user_permissions($this->db->get_user_scheduleid($this->memberid), true);
//
//			print_location_lists($loclist, $this->toLocation, $this->machid, $this, $toLocs);
//		}
//		print_time_info($this, $rs, !$this->is_blackout,$this->specialItems);	// Print time information
//		if($this->memberid != '41e6d96e8b2ad') {
//			if (!$this->is_blackout)
//				print_user_info($this->type, new User($this->memberid));	// Print user info
//
//			if (!empty($this->id))			// Print created/modified times (if applicable)
//				print_create_modify($this->created, $this->modified);
//
//			print_special($this->specialItems, $this->type, $user->role, $this);
//			//if($user->groupid > 0 && $user->groupid <= 1000000) {
//			if (1) {
//				$billtype = $user->groupid ? $this->db->get_billtype($user->groupid) : null;
//				$paymentArray = $this->db->getPaymentOptions($this->memberid);
//				//echo $billtype;
//				//CmnFns::diagnose($paymentArray);
//				print_group_hack_summary($this->summary, $this->type, $billtype, $this->dispNotes, $paymentArray, $user, $this);
//			} else {
//				print_summary($this->summary, $this->type);
//			}
//
//		} else {
//			print_special($this->specialItems, $this->type);
//			print_hack_summary($this->summary, $this->type);
//		}
//		//if (!empty($this->parentid) && ($this->type == 'm' || $this->type == 'd'))
//			//print_recur_checkbox($this->parentid);
//
//		//if ($this->type == 'm')
//			//print_del_checkbox();
//
//		// $hack is our array from group_hack_summary
//		$hack = $this->db->parseNotes($this->summary);
//
//		print_buttons($this->type, $hack, $this->coupon, $user->groupid, $user->email);
//
//		print_hidden_fields($this);	// Print hidden form fields
//
//		//if (1) {		// Print out repeat reservation box, if applicable
//			//divide_table();
//			//$weeks = $this->create_week_array($conf['app']['recurringWeeks']);
//			//print_repeat_box(date('m', $this->date), date('Y', $this->date));
//		//}
//
//		end_right_cell();
//
//		end_reserve_form($this->id, $this->type);				// End form
	}

	/**
	* Creates an array of unique weeks to display for users to
	*  select recurring reservations for
	* @param array $weekArray week values to split up and put into return array
	* @return array of unique week numbers to use for recurring reservations
	*/
	function create_week_array($weekArray) {
		$weeks = array();
		if (count($weekArray) == 1) {
			for ($i = 1; $i <= $weekArray[0]; $i++) {
				$weeks[] = intval($i);
			}
		}
		else {
			for ($i = 0; $i < count($weekArray); $i++) {
				if (strpos($weekArray[$i], '-') !== false) {
					list($first, $last) = explode('-', $weekArray[$i]);
					for ($j = intval($first); $j <= intval($last); $j++)
						$weeks[] = intval($j);
				}
				else
					$weeks[] = intval($weekArray[$i]);
			}
		}

		$weeks = array_unique($weeks);
		sort($weeks);

		return $weeks;
	}

	/**
	* Sends an email notification to the user
	* This function sends an email notifiying the user
	* of creation/modification/deletion of a reservation
	* @param string $type type of modification made to the reservation
	* @param object $res this reservation object
	* @param array $repeat_dates array of dates reserved on
	* @global $conf
	*/
	function send_email($type, &$user, $repeat_dates = null) {
		global $conf;
		$fname = $user->get_fname();
		$lname = $user->get_lname();
		$useremail = $user->get_email();
		$fr = $this->machid;
		$to = $this->toLocation;
		
		if ($this->paymentProfileId == "00")
			$payinfo = "Direct Bill";
		else {
			$paymentArray = $this->db->getPaymentOptions($this->memberid);
			$payinfo = $paymentArray[$this->paymentProfileId];
		}

		$payblock = '';

		// Only create payblock if match
		if ($payinfo) {
			$payblock = "<tr><td>Payment</td><td>$payinfo</td></tr>\n";


		}


		$link = '';
		$apts = array(
				'41b40be9091cb' => "Boston Logan Int'l Airport",
				'gzm41b48f6ae8d87' => "Boston Logan Int'l Airport",
				'airport-BOS' => "Boston Logan Int'l Airport",
				'airport-MHT' => "Manchester Int'l Airport",
				'airport-PVD' => "TF Green Int'l Airport",
				'airport-SFO' => "San Francisco Int'l Airport",
				'airport-OAK' => "Oakland Int'l Airport",
				'airport-SJC' => "San Jose Int'l Airport");
	
		foreach ($apts as $a => $description)
			if ($a == $fr || $a == $to) {
				$a = substr($a, -3);
				if ($a=='1cb'||$a=='d87')
					$a = 'BOS';	
				$link = 'For information about meeting Planettran at '.$description.', please visit <a href="http://www.planettran.com/'.strtolower($a).'.php">http://www.planettran.com/'.strtolower($a).".php</a>\r\n\r\n<br /><br />";
			}	

		if($_SESSION['sessionID'] != $this->memberid) {
			$user = new User($_SESSION['sessionID']);
		}
		// Dont bother if nobody wants email
		if (!$user->wants_email($type) && !$conf['app']['emailAdmin'])
			return;
		
		$rs = $this->db->get_resource_data($this->machid);
		$toLoc = $this->db->get_resource_data($this->toLocation);

		$emailStopLoc = '';
		if ($this->stopLoc) {
			$sa = $this->db->get_resource_data($this->stopLoc);
			if (!empty($sa))
				$emailStopLoc = "<tr><td>Stopping at</td><td>{$sa['name']} {$sa['address1']}, {$sa['city']}, {$sa['state']} {$sa['zip']}</td></tr>";
		}

		$childSeatWarning = '';
		if ($this->booster_seats || $this->convertible_seats)
			$childSeatWarning = "<br>You have included child seats in this reservation. PlanetTran will bring the child seats and instructions for installation, but the customer is responsible for all child seat installations.";

		$emailFrLoc = $rs['name']." ".$rs['address1'].", ".$rs['city'].", ".$rs['state']." ".$rs['zip'];
		$emailToLoc = $toLoc['name']." ".$toLoc['address1'].", ".$toLoc['city'].", ".$toLoc['state']." ".$toLoc['zip'];
		$showid = strtoupper(substr($this->id, -6));		

		// Email addresses
		$adminEmail = $this->sched['adminEmail'];
		$techEmail  = $conf['app']['techEmail'];
		//$url        = CmnFns::getScriptURL();
		$url = $conf['app']['link'];

		// Format date
		$date   = CmnFns::formatDate($this->get_date());
		$start  = CmnFns::formatTime($this->get_start());
		$end    = CmnFns::formatTime($this->get_end());

		$defs = array(
				translate('Reservation #'),
				'Reserved For:',
				translate('Date'),
				'From:',
				'To:',
				'Pickup Time',
				'Flight Details'
				);

		switch ($type) {
			case 'e_add' : $mod = 'created';
			break;
			case 'e_mod' : $mod = 'modified';
			break;
			case 'e_del' : $mod = 'deleted';
			break;
		}

		$toEmail  = $user->get_email();		// Who to mail to
		$subject= translate("Reservation $mod for", array($date));
		$uname = $user->get_fname();
		$hack = $this->db->parseNotes($this->summary);
		if ($hack['name']) {
			list($paxfname, $paxlname) = explode(" ", $hack['name'], 2);
		} else {
			$paxfname = $fname;
			$paxlname = $lname;
		}

		$rs['location'] = !empty($rs['location']) ? $rs['location'] : translate('N/A');
		$rs['rphone'] = !empty($rs['rphone']) ? $rs['rphone'] : translate('N/A');

		$text = translate_email('reservation_activity_1', $fname, translate($mod), $showid, $date, $start, $end, $rs['name'], $rs['address1']. ',' . $rs['address2'] . ',' . $rs['city'] . ', ' . $rs['state'], translate($mod));

		if ($this->is_repeat && count($repeat_dates) > 1) {
			// Start at index = 1 because at index 0 is the parent date
			$text .= translate_email('reservation_activity_2');
			for ($d = 1; $d < count($repeat_dates); $d++)
				$text .= CmnFns::formatDate($repeat_dates[$d]) . "\r\n<br/>";
			$text .= "\r\n<br/>";
		}

		if ($type != 'e_add' && $this->is_repeat) {
			$text .= translate_email('reservation_activity_3', translate($mod));
		}

		if ($hack['cc']) {
			$ccpattern = '/'.$hack['cc'].'/';
			$pattern = array($ccpattern, '/(\d{15,16})/');
		} else
			$pattern = '/(\d{15,16})/';
		$replace = '****************';
		$shownotes =  preg_replace($pattern, $replace, $this->summary);
		if (!empty($this->summary)) {
			$text .= stripslashes(translate_email('reservation_activity_4', ($shownotes)));
			$shownotes = str_replace("GROUP_DEL", " ", $shownotes);
			$shownotes = str_replace("DELIMITER", " ", $shownotes);
		}

		$text .= translate_email('reservation_activity_5', $conf['app']['title'], $url, $url);

		if (!empty($techEmail)) $text .= translate_email('reservation_activity_6', $techEmail, $techEmail);
		$text = str_replace("GROUP_DEL", " ", $text);
		$text = str_replace("DELIMITER", " ", $text);
		$showDets = str_replace("{`}", " ", $this->flightDets);
		if (!$showDets)
			$showDets = '&nbsp;';
		$text .= $link;

		$sf = '<br/><div align="center"><b>Traveling to the Bay Area?</b><br/>We are now serving <b>SFO, OAK, and SJC airports!</b>  Add these airports to your profile and you can make reservations now!</div><br/>&nbsp;<br/>';
		if ($user->wants_html()) {
			$msg = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <style type="text/css">
	<!--
	body {
		font-size: 11px;
    		font-family: Verdana, Arial, Helvetica, sans-serif;
		background-color: #FFFFFF;
	}
	a {
		color: #104E8B;
		text-decoration: none;
	}
	a:hover {
		color: #474747;
		text-decoration: underline;
	}
	td {
		text-align: left;
	}
	-->
	</style>
</head>

<body>
<a href="http://www.planettran.com/"><img src="http://www.planettran.com/images/planettran_logo_new.jpg" border="0" /></a><br>&nbsp;<br>

Dear $paxfname,
<br>&nbsp;<br>
Thank you for using PlanetTran. You have $mod the following reservation:
<br>&nbsp;<br>

<table width="100%" cellspacing=0 cellpadding=3 style="border: 1px solid #CCC;">
<tr><td width="25%">Reservation #</td><td width="75%">$showid</td></tr>
<tr><td>Reserved For</td><td>$paxfname $paxlname</td></tr>
<tr><td>Date</td><td>$date</td></tr>
<tr><td>Pickup Time</td><td>$start</td></tr>
<tr><td>Flight Details</td><td>$showDets</td></tr>
<tr><td>From</td><td>$emailFrLoc</td></tr>
$emailStopLoc
<tr><td>To</td><td>$emailToLoc</td></tr>
$payblock
</table>
$childSeatWarning
<br>&nbsp;<br>

Other information:<br>
$shownotes

<br>&nbsp;<br>
You can view or modify your reservation at any time by logging into PlanetTran Mobile at <a href="http://m.planettran.com">http://m.planettran.com</a>, or the regular website at <a href="http://reservations.planettran.com">http://reservations.planettran.com</a> with the reservation number above. You can also call us at 1.888.756.8876.

<br>&nbsp;<br>
Please note: if you need to cancel this reservation on the day of travel, you must call us at 888.756.8876 no later than one hour before the scheduled pickup time in order to avoid being charged the full fare.
 
<br>&nbsp;<br>
For information about meeting PlanetTran at Boston Logan International Airport, please visit <a href="http://www.planettran.com/bos.php">http://www.planettran.com/bos.php</a>. Traveling to the Bay Area? We are now serving SFO, OAK, and SJC airports! Add these airports to your profile and you can make reservations now!

<br>&nbsp;<br>
Please note that our drivers are compensated by hourly time, not based on number of trips or tips. Our flat rates do not include tips, and our drivers do not expect tips. Please visit us at <a href="http://www.planettran.com/service.php">http://www.planettran.com/service.php</a> to review all our policies.

<br>&nbsp;<br>
Please add customerservice@planettran.com to your address book to make sure that you receive your receipt email.

<br>&nbsp;<br>
  </body>
</html>
EOT;
		}
		else {
			$text = strip_tags($text);		// Strip out HTML tags
			$msg = $text;

			$fields = array (	// array[x] = [0] => title, [1] => field value, [2] => length
						array($defs[0], $this->id, ((strlen($this->id) < strlen($defs[0])) ? strlen($defs[0]) : strlen($this->id))),
						array($defs[1], $date, ((strlen($date) < strlen($defs[1])) ? strlen($defs[1]) : strlen($date))),
						array($defs[2], $rs['name'], ((strlen($rs['name']) < strlen($defs[2])) ? strlen($defs[2]) : strlen($rs['name']))),
						array($defs[3], $toLoc['name'], ((strlen($toLoc['name']) < strlen($defs[3])) ? strlen($defs[3]) : strlen($toLoc['name']))),
						array($defs[4], $start, ((strlen($start) < strlen($defs[4])) ? strlen($defs[4]) : strlen($start))),
						array($defs[5], $this->flightDets, ((strlen($this->flightDets) < strlen($defs[5])) ? strlen($defs[5]) : strlen($this->flightDets)))
						);
			$total_width = 0;

			foreach ($fields as $a) {	// Create total width by adding all width fields plus the '| ' that occurs before every cell and ' ' after
				$total_width += (2 + $a[2] + 1);
			}
			$total_width++;		// Final '|'

			$divider = '+' . str_repeat('-', $total_width - 2) . '+'; 		// Row dividers

			$msg .= $divider . "\n";
			$msg .= '| ' . translate("Reservation $mod") . (str_repeat(' ', $total_width - strlen(translate("Reservation $mod")) - 4)) . " |\n";
			$msg .= $divider . "\n";
			foreach ($fields as $a) {		// Repeat printing all title fields, plus enough spaces for padding
				$msg .= "| $a[0]" . (str_repeat(' ', $a[2] - strlen($a[0]) + 1));
			}
			$msg .= "|\n";					// Close the row
			$msg .= $divider . "\n";
			foreach ($fields as $a) {		// Repeat printing all field values, plus enough spaces for padding
				$msg .= "| $a[1]" . (str_repeat(' ', $a[2] - strlen($a[1]) + 1));
			}
			$msg .= "|\n";					// Close the row
			$msg .= $divider . "\n";
		
			$msg .= $sf;
		}

		$send = false;


		// Send email using PHPMailer
		$mailer = new PHPMailer();

		// Create a second email if this is an admin booking.
		// this is the "remember you can book online" email
		$m = new PHPMailer();
		$mailer->ClearAllRecipients();
		if($_SESSION['confirmEmail'] != '') {
			// This is the email entered in the form. It's added
			// as both a normal address and a BCC
			$mailer->AddBCC($_SESSION['confirmEmail'], 'Confirmation Recipient');
			$m->AddBCC($_SESSION['confirmEmail'], 'Confirmation Recipient');
			$mailer->AddAddress($_SESSION['confirmEmail'], 'Confirmation Recipient');
			$m->AddAddress($_SESSION['confirmEmail'], 'Confirmation Recipient');
			// echo 'emailed to: ' . $_SESSION['confirmEmail'];
			$_SESSION['confirmEmail'] = '';
		}
		$send = true;
		$mailer->IsHTML($user->wants_html());
		if ($user->wants_email($type)) {
			$send = true;
			$mailer->AddBCC($toEmail, $uname); //email of booker
			//$m->AddBCC($toEmail, $uname); 
			if($this->sched['isHidden'] && $toEmail != $useremail) {
				$mailer->AddAddress($useremail, $fname . " " . $lname);
				$m->AddAddress($useremail, $fname . " " . $lname);
			}

			if ($conf['app']['emailAdmin']) {
				// Add the admin to the CC if they want it
			}
		}
		else if ($conf['app']['emailAdmin']) {
			$send = true;
		}

		$mailer->From = "CustomerService@planettran.com";//$adminEmail;
		$mailer->FromName = "PlanetTran Reservations";//$conf['app']['title'];
		$mailer->Subject = $subject;

		$send_calendar = Auth::isSuperAdmin();
		$send_calendar = false;

		//if ($this->coupon) 
		//	$mailer->AddBCC('couponres@planettran.com');
		

		if($send_calendar) {
			$dstamp = gmdate("Ymd\THis\Z");
			$timestamp = $this->date + $this->start * 60;
			$dstart = gmdate("Ymd\THis\Z", $timestamp);
			$uid = $this->id;	
			$calfile = $uid.".ics";
			$summary = "Planettran reservation, ".$rs['name']." to ".$toLoc['name'];
			$location = $rs['address1'].($rs['address2']?" ".$rs['address2']:"").", ".$rs['city'].", ".$rs['state']." ".$rs['zip'];
			$cal = <<<ICS
BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
PRODID:-//Google Inc//Google Calendar 70.9054//EN
BEGIN:VEVENT
DSTAMP:$dstamp
DTSTART:$dstart
SUMMARY:$summary
LOCATION:$location
ORGANIZER:reservations@planettran.com
CREATED:$dstamp
UID:$uid
TRANSP:OPAQUE
END:VEVENT
END:VCALENDAR
ICS;
			
		//echo "<pre>$cal</pre>";
		//touch($calfile);
		//file_put_contents($calfile, $cal);
		$mailer->AddStringAttachment($cal, $calfile, '7bit',"text/calendar; method=PUBLISH; name=$calfile;\n charset=ISO-8859-1", "inline"); // with new headers
		$mailer->AddStringAttachment($cal, $calfile, '7bit',"text/calendar; method=PUBLISH; name=$calfile;\n charset=ISO-8859-1", "inline"); // with new headers
		}
		$mailer->Body = $msg;

		if ($send) {
		  $mailer->Send();
		}
		// if ($send_calendar) unlink($calfile);
		
		unset($rs, $headers, $msg, $fields);

		
		// Send email for people who booked by phone

		if (Auth::isAdmin() && $type == 'e_add') {
			$body2 = <<<BODY2
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=windows-1252">
	<TITLE>Thank you for using PlanetTran!</TITLE>
	<STYLE TYPE="text/css">
	<!--
		@page { margin: 0.79in }
		P { margin-bottom: 0.08in; direction: ltr; color: #00000a; widows: 2; orphans: 2 }
		P.western { font-family: "Verdana", serif; font-size: 18pt; font-weight: bold }
		P.cjk { font-size: 18pt; font-weight: bold }
		P.ctl { font-size: 18pt }
		A:link { color: #000000; so-language: zxx }
	-->
	</STYLE>
</HEAD>
<BODY LANG="en-US" TEXT="#000000" LINK="#000000" DIR="LTR">
<DIV TYPE=HEADER>
	<TABLE WIDTH=625 BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<COL WIDTH=151>
		<COL WIDTH=474>
		<TR>
			<TD WIDTH=151 HEIGHT=20>
				<P CLASS="western" ALIGN=CENTER STYLE="font-weight: normal">
				</P>
			</TD>
			<TD WIDTH=474>
				<P CLASS="western" ALIGN=RIGHT STYLE="font-weight: normal; page-break-before: always">
				</P>
			</TD>
		</TR>
	</TABLE>
	<P STYLE="margin-bottom: 0in; font-weight: normal">
	</P>
</DIV>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: bold"><FONT FACE="Arial, serif"><FONT SIZE=2>Thank
you for booking your upcoming trip with PlanetTran!</FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: normal"><FONT FACE="Arial, serif"><FONT SIZE=2><FONT COLOR="#e36c0a"><FONT SIZE=4><B>Did
you know that you can book and manage your trips, locations, credit
cards and more online at </B></FONT></FONT><FONT COLOR="#000000"><SPAN LANG="zxx"><U><A HREF="http://www.planettran.com"><FONT COLOR="#e36c0a"><FONT SIZE=4><B>beta.planettran.com</B></FONT></FONT></A></U></SPAN></FONT><FONT COLOR="#e36c0a"><FONT SIZE=4><B>?</B></FONT></FONT><FONT SIZE=4>
 </FONT></FONT></FONT>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: bold"><FONT FACE="Arial, serif"><FONT SIZE=2>Booking
online saves time and lets you manage every facet of your reservation
directly, so book your next trip online today!</FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: bold"><FONT FACE="Arial, serif"><FONT SIZE=2>Forgot your password? <a href="https://secure.planettran.com/reservations/ptRes2/forgot_pwd.php">Click here to reset it.</a></FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: bold"><FONT FACE="Arial, serif"><FONT SIZE=2>At
our website you can also:</FONT></FONT></P>
<UL>
	<LI><FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, serif"><FONT SIZE=2>
	Make new reservations</FONT></FONT></FONT></FONT></LI>
	<LI><FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, serif"><FONT SIZE=2>
	Change trips you've reserved</FONT></FONT></FONT></FONT></LI>
	<LI><FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, serif"><FONT SIZE=2>
	Calculate quotes</FONT></FONT></FONT></FONT></LI>
	<LI><FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, serif"><FONT SIZE=2>
	Find receipts for old trips</FONT></FONT></FONT></FONT></LI>
	<LI><FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, serif"><FONT SIZE=2>
	Check out our mission statement and policies</FONT></FONT></FONT></FONT></LI>
	<LI><FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, serif"><FONT SIZE=2>
	Refer friends</FONT></FONT></FONT></FONT></LI>
	<LI><FONT FACE="Calibri, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, serif"><FONT SIZE=2>and
	much more!</FONT></FONT></FONT></FONT></LI>
</UL>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: normal">
</P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: bold"><FONT FACE="Arial, serif"><FONT SIZE=2>Best
regards,</FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: bold"><FONT FACE="Arial, serif"><FONT SIZE=2>The
PlanetTran Team</FONT></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: normal">
</P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: normal">
</P>
<P CLASS="western" ALIGN=CENTER STYLE="margin-bottom: 0in; font-weight: normal">
<FONT FACE="Arial, serif"><FONT SIZE=2><FONT COLOR="#4f6228">Book
your next trip online at </FONT><FONT COLOR="#000000"><SPAN LANG="zxx"><U><A HREF="http://www.planettran.com"><FONT COLOR="#4f6228">beta.planettran.com</FONT></A></U></SPAN></FONT><FONT COLOR="#4f6228">!</FONT></FONT></FONT></P>
<P CLASS="western" ALIGN=CENTER STYLE="margin-bottom: 0in; font-weight: normal">
<IMG SRC="http://www.planettran.com/images/planettran_logo_new.jpg"></P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: normal">
</P>
<P CLASS="western" STYLE="margin-bottom: 0in; font-weight: normal">
</P>
</BODY>
</HTML>
BODY2;



			$m->From = 	"CustomerService@planettran.com";
			$m->FromName = 	"PlanetTran";
			$m->Subject = 	"Thank you for booking your upcoming trip with PlanetTran!";
			$m->IsHTML(true);
			$m->Body = $body2;
			$m->Send();

		}

	}


	/**
	* Returns the type of this reservation
	* @param none
	* @return string the 1 char reservation type
	*/
	function get_type() {
		return $this->type;
	}

	/**
	* Returns the ID of this reservation
	* @param none
	* @return string this reservations id
	*/
	function get_id() {
		return $this->id;
	}

	/**
	* Returns the start time of this reservation
	* @param none
	* @return int start time (in minutes)
	*/
	function get_start() {
		return $this->start;
	}

	/**
	* Returns the end time of this reservation
	* @param none
	* @return int ending time (in minutes)
	*/
	function get_end() {
		return $this->end;
	}

	/**
	* Returns the timestamp for this reservation's date
	* @param none
	* @return int reservation timestamp
	*/
	function get_date() {
		return $this->date;
	}

	/**
	* Returns the created timestamp of this reservation
	* @param none
	* @return int created timestamp
	*/
	function get_created() {
		return $this->created;
	}

	/**
	* Returns the modified timestamp of this reservation
	* @param none
	* @return int modified timestamp
	*/
	function get_modified() {
		return $this->modified;
	}

	/**
	* Returns the resource id of this reservation
	* @param none
	* @return string resource id
	*/
	function get_machid() {
		return $this->machid;
	}
/**
	* Returns the resource id of this reservation
	* @param none
	* @return string resource id
	*/
	function get_flightDets() {
		return $this->flightDets;
	}
	function get_autoBillOverride() {
		return $this->autoBillOverride;
	}
	function get_checkBags() {
		return $this->checkBags;
	}
	function get_tolocation() {
		return $this->toLocation;
	}
	function get_specialItems() {
		  return $this->specialItems;
	}
	function getManager() {
		return $this->manager;
	}
	/**
	* Returns the member id of this reservation
	* @param none
	* @return string memberid
	*/
	function get_memberid() {
		return $this->memberid;
	}

	/**
	* Returns the User object for this reservation
	* @param none
	* @return User object for this reservation
	*/
	function &get_user() {
		return $this->user;
	}

	/**
	* Returns the id of the parent reservation
	* This will only be set if this is a recurring reservation
	*  and is not the first of the set
	* @param none
	* @return string parentid
	*/
	function get_parentid() {
		return $this->parentid;
	}

	/**
	* Returns the summary for this reservation
	* @param none
	* @return string summary
	*/
	function get_summary() {
		return $this->summary;
	}

	function get_special() {
		return $this->special;
	}

	function get_coupon() {
		return $this->coupon;
	}

	/**
	* Returns the scheduleid for this reservation
	* @param none
	* @return string scheduleid
	*/
	function get_scheduleid() {
		return $this->scheduleid;
	}

	/**
	* Whether there were errors processing this reservation or not
	* @param none
	* @return if there were errors or not processing this reservation
	*/
	function has_errors() {
		return count($this->errors) > 0;
	}

	/* Whether the reservation has warnings */
	function has_warnings() {
		return count($this->warnings) > 0;
	}

	/**
	* Add an error message to the array of errors
	* @param string $msg message to add
	*/
	function add_error($msg) {
		array_push($this->errors, $msg);
	}

	/* Add warning to array of warnings */
	function add_warning($msg) {
		array_push($this->warnings, $msg);
	}

	/**
	* Return the last error message generated
	* @param none
	* @return the last error message
	*/
	function get_last_error() {
		if ($this->has_errors())
			return $this->errors(count($this->errors)-1);
		else
			return null;
	}

	/**
	* Prints out all the error messages in an error box
	* @param boolean $kill whether to kill the app after printing messages
	*/
	function print_all_errors($kill) {
		$mobile = isset($this->ismobile);
		if ($this->has_errors() && !$mobile) {


  ob_start();
  ob_implicit_flush(0);
      $this->print_res();
  $form = ob_get_clean();

			$div = '<hr size="1"/>';
			CmnFns::do_error_box(
//				'<a href="javascript: history.back();">' . translate('Please go back and correct any errors.') . '</a><br /><br />'.
        join($div, $this->errors)
//        . '<br /><br /><a href="javascript: history.back();">' . translate('Please go back and correct any errors.') . '</a>'
				, 'width: 90%;'
//				, $kill);
				, $kill, false, $form);
		} else if ($this->has_errors() && $mobile) {
			$div = '<hr>';
			$back = '<br><a href="m.cpanel.php">Back</a>';
			CmnFns::do_error_box(
				join($div, $this->errors).$back,
				'',
				$kill);

		}
	}

	/* Print all warnings */
	function print_all_warnings($verbed) {

		if ($this->has_warnings()) {
			$div = '<hr size="1"/>';
			CmnFns::do_error_box(
				"Your reservation has been $verbed; however, warnings were generated. <b>Please pay close attention to the following warning(s) and take action if needed:</b><br>&nbsp;<br>" . join($div, $this->warnings) . '<br /><br />'
				, 'width: 90%;'
				, false);
			}
	}

	/* check that either the account or the reservation has a phone */
	function check_phone(&$user) {
		$phone = $user->get_phone();
		$notes = $this->db->parseNotes($this->summary);

		/* return false only if both are missing */
		if (!$phone && !$notes['cell'])
			$this->add_error('A valid contact number is required, either on the account itself (the My Account tab, or the "Change My Profile Information/Password" link), or in the reservation details.'); 
			
	}

	/*
	* 
	*/
	function email_admins() {
		if ($this->type == 'd') return;
		if (isset($this->coupon) && !empty($this->coupon)) {
			$m = new PHPMailer();
			$m->AddAddress('couponres@planettran.com');
			$m->Subject = 'Coupon used: '.$this->coupon;
			$m->Body = 'Coupon used: '.$this->coupon;
			$m->Send();
		}
	}

	/*
	*
	*/
	function check_coupon($coupon, $from, $to) {

		//if(strlen($coupon) == 6) {
		//	return true;
		//}

		$amount = $this->db->get_coupon_amount($coupon);
		if (!$amount) $this->add_error("Invalid coupon code.");

		$used = $this->db->coupon_been_used($this->memberid, $coupon, $this->id, $this->date);
		if ($used) $this->add_error("That coupon is either not valid for that date, or has already been used the maximum number of times for this account.");

		$airport = false;
		if (CmnFns::isAirport($from) || CmnFns::isAirport($to))
			$airport = true;	

		if ($amount['allowed'] == 'p2p' && $airport)
			$this->add_error("That coupon is only valid for non-airport trips.");
		else if ($amount['allowed'] == 'airport' && !$airport)
			$this->add_error("That coupon is only valid for trips to or from an airport. To add an airport to your list of locations, please select one from the dropdown list.");



		if ($amount['max_uses']) { // global coupon
			// only check if no existing coupon
			if (!$_POST['existing_coupon'] && $amount['use_count'] >= $amount['max_uses'])
				$this->add_error("That coupon has exceeded its maximum number of uses.");
			// flag this as a global coupon
			$this->global_coupon = $coupon;
		}
	}

	function insert_gps_loc($scheduleid) {
		$lat = $_POST['lat'];
		$lon = $_POST['lon'];
		$latlng = "$lat,$lon";
		$sensor = "true";
		$url = "http://maps.google.com/maps/api/geocode/xml?latlng=$latlng&sensor=$sensor";

		include_once('Twitter.class.php');
		include_once('Mobile.class.php');
		include_once('db/AdminDB.class.php');
		$adb = new AdminDB();
		$t = new Twitter();
		$t->fromLat = $lat;
		$t->fromLon = $lon;

		// $address_array comes from xml_parser.php
		global $conf;
		$parser = $conf['app']['include_path'].'reservations/ptRes2/xml_parser.php';
    		global $current_tag, $xml_addNum_key, $xml_addState_key, $xml_type_key, $counter, $story_array, $address_array;
		include($parser);
	
		//CmnFns::diagnose($address_array);

		$loc = $t->get_gps_loc_values($address_array);
		$loc['scheduleid'] = $scheduleid;
		//CmnFns::diagnose($loc);
		//die;

		$adb->add_resource($loc, null, $loc['machid']);
		return $loc['machid'];
	}
}

function get_airports_options()
{
  $apts = array('41b40be9091' => array("Boston Logan Int'l Airport", "Boston", "MA", "02128", "1 Harborside Dr"),
		'airport-MHT' => array("Manchester Int'l Airport", "Manchester", "CA", "03103", "1 Airport Road"),
		'airport-PVD' => array("TF Green Int'l Airport", "Warwick", "RI", "02886", "544 Airport Road"),
		'airport-SFO' => array("San Francisco Int'l Airport", "San Francisco", "CA", "94128", "806 South Airport Boulevard"),
		'airport-OAK' => array("Oakland Int'l Airport", "Oakland", "CA", "94621", "1 Airport Drive"),
		'airport-SJC' => array("San Jose Int'l Airport", "San Jose", "CA", "95110", "1701 Airport Boulevard"));
  $options = '';
  foreach($apts as $k=>$v)
  {
    $options .= '<option data-address="'.$v[4].'" data-zip="'.$v[3].'" data-state="'.$v[2].'" data-city="'.$v[1].'">'.$v[0].'</option>';
  }
  return $options;
}
