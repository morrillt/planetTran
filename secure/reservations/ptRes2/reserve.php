<?php
/** 
* Interface form for placing/modifying/viewing a reservation
* This file will present a form for a user to
*  make a new reservation or modify/delete an old one.
* It will also allow other users to view this reservation.
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 08-04-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Template class
*/
include_once('lib/Template.class.php');
include_once('lib.php');

//include_once('lib/Reservation.class.php');

$is_blackout = ($_GET['is_blackout'] == '1');
if ($is_blackout) {
	/**
	* Blackout class
	*/
	include_once('lib/Blackout.class.php');
	$Class = 'Blackout';
	$_POST['minRes'] = $_POST['maxRes'] = null;
}
else {
	/**
	* Reservation class
	*/
	include_once('lib/Reservation.class.php');
	$Class = 'Reservation';
}

if ((!isset($_GET['read_only']) || !$_GET['read_only']) && $conf['app']['readOnlyDetails']) {
	// Make sure user is logged in
	if (!Auth::is_logged_in()) {
		Auth::print_login_msg();
	}
}

$t = new Template();

if (isset($_POST['submit']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF'])) {
//	$t->set_title(translate("Processing $Class"));
//	$t->printHTMLHeader();
//	$t->startMain();

  $res_info = getResInfo();
	$t->set_title($res_info['title']);
	$t->printHTMLHeader('silo_reservations sn3 mn1');
  $t->printNavReservations();
	$t->startMain();

	switch($_GET['type']){
		case 'm':
      $fn = 'modify';
			break;
		case 'd':
      $fn = 'delete';
			break;
		default:
      $fn = 'create';
	}
	$values = process_reservation($fn);

	present_reservation($values);
}
else {
	$res_info = getResInfo();
	$t->set_title($res_info['title']);
	$t->printHTMLHeader('silo_reservations sn3 mn1');
	$t->printNavReservations();
	$t->startMain();
	present_reservation($res_info['resid']);
}

// End main table
$t->endMain();

// Print HTML footer
$t->printHTMLFooter();

/**
* Processes a reservation request (add/del/edit)
* @param string $fn function to perform
*/
function process_reservation($fn) {
	$success = false;
	global $Class;
	$delimiter = 'DELIMITER';

	$_SESSION['confirmEmail'] = $_POST['confirmEmail'];
	if (isset($_POST['group']) || ($_POST['cphone']) || ($_POST['pname'])) {
			$delimiter = "GROUP_DEL";
			$_POST['summary'] =
	                        $_POST['pname'] . $delimiter .
	                        $_POST['cphone'] . $delimiter .
	                        $_POST['cccode'] . $delimiter .
	                        $_POST['address'] . $delimiter .
	                        $_POST['citystzip'] . $delimiter .
	                        $_POST['ccnum'] . $delimiter .
	                        $_POST['expdate'] . $delimiter .
	                        $_POST['summary'] . $delimiter .
				$_POST['confirmEmail'];
		}
		if (isset($_POST['hybridcar']))
			$_POST['summary'] =
				$_POST['pname'] . $delimiter .
				$_POST['ccnum'] . $delimiter .
				$_POST['expdate'] . $delimiter .
				$_POST['address'] . $delimiter .
				$_POST['city'] . $delimiter .
			$_POST['cell'];

	

	if (isset($_POST['hack']))
		$_POST['summary'] = $_POST['pname'] . $delimiter . $_POST['ccnum'] . $delimiter . $_POST['expdate'] . $delimiter . $_POST['address'] . $delimiter . $_POST['city'] . $delimiter . $_POST['cell'];
	
	if (isset($_POST['resid']))
		$res = new $Class($_POST['resid']);
	else if (isset($_GET['resid']))
		$res = new $Class($_GET['resid']);
	else {
		$res = new $Class();
		if ($_POST['interval'] != 'none') {
		// Check for reservation repeation
			$res->is_repeat = true;
			$days = isset($_POST['repeat_day']) ? $_POST['repeat_day'] : NULL;
			$week_num = isset($_POST['week_number']) ? $_POST['week_number'] : NULL;
			$repeat = get_repeat_dates($_POST['ts'], $_POST['interval'], $days, $_POST['repeat_until'], $_POST['frequency'], $week_num);
		}
		else {
			$res->is_repeat = false;
		}
	}

  if($_REQUEST['resid'])
    $res->load_by_id();


//	$startTime = $_POST['ampm'] == 'pm' ? $_POST['startTime'] + 720 : $_POST['startTime'];
  $startMinutes = $_POST['start_hour'] * 60 + $_POST['start_minutes'];
	$startTime = $_POST['ampm'] == 'pm' ? $startMinutes + 720 : $startMinutes;

	$dates = split('/', $_POST['date']);
	if ($_POST['fn'] != 'delete')
		$res->date = mktime($_POST['start_hour'],$_POST['start_minutes'],0,$dates[0], $dates[1], $dates[2]);//array($_POST['ts']);

	$specialItems = '';
	$specialItems .= isset($_POST['suv']) ? 'S' : '';
	$specialItems .= isset($_POST['toddler']) ? 'T' : '';
	$specialItems .= isset($_POST['infant']) ? 'I' : '';
	$specialItems .= isset($_POST['multiple']) ? 'M' : '';
	$specialItems .= isset($_POST['evip']) && $_POST['evip'] ? 'EV' : (isset($_POST['vip']) ? 'V' : '');
	$specialItems .= isset($_POST['personal']) ? 'P' : '';
	$specialItems .= isset($_POST['greet']) ? 'G' : '';

	if (isset($_POST['wait']) || $_POST['carTypeSelect'] == 'N') {
		$specialItems .= 'A';
		$authWait = $_POST['authWait'];
	} else
		$authWait = null;

	$specialItems .= isset($_POST['van']) ? 'N' : '';
	$specialItems .= isset($_POST['curbside']) ? 'C' : '';

	if (isset($_POST['carTypeSelect'])) 
		$specialItems .= $_POST['carTypeSelect'];
	if (isset($_POST['seatTypeSelect'])) 
		$specialItems .= $_POST['seatTypeSelect'];

  require_once dirname(__FILE__).'/lib/db/AdminDB.class.php';
  $db = new AdminDB();
  $disableEvalHack = true;
  require_once dirname(__FILE__).'/admin_update.php';

  //user id
  $scheduleid = $_POST['scheduleid'];

  if($_POST){
    if($_POST['stop'] == 1){
      if($_POST['stopLoc']){
        $stopLoc = $_POST['stopLoc'];
      }
      else{
        $stopLocation = array();
        $stopLocation['type'] = 1; // it is required
        $stopLocation['name'] = $_POST['stop_name'];
        $stopLocation['city'] = $_POST['stop_city'];
        $stopLocation['address'] = $_POST['stop_address'];
        $stopLocation['scheduleid'] = $scheduleid;
        $stopLocation['state'] = $_POST['stop_state'];
        $stopLocation['zip'] = $_POST['stop_zip'];

        $stopLoc = add_resource($stopLocation, true);
      }
    } else {
      $stopLoc = null;
    }
  } else {
    $stopLoc = $res->stopLoc;
  }

	// if first_res is null, it's their first trip, and if first_mod is set
	// it's a mod of their first trip
	if ($_POST['first_mod'] || !$_POST['first_res'])
		$specialItems .= 'F';
//	$stopLoc = $_POST['stopLoc'] && $_POST['stop'] ? $_POST['stopLoc'] : null;
	if ($stopLoc) $specialItems .= "M";

	$convertible_seats = isset($_POST['convertible_seats']) && $_POST['convertible_seats'] > 0 ? $_POST['convertible_seats'] : null;
	$booster_seats = isset($_POST['booster_seats']) && $_POST['booster_seats'] > 0 ? $_POST['booster_seats'] : null;

	$specialItems .= $convertible_seats ? 'T' : '';
	$specialItems .= $booster_seats ? 'O' : '';

	// pax must be last!
	$specialItems .= isset($_POST['pax']) ? $_POST['pax'] : '';
	$paymentProfileId = $_POST['paymentProfileId'];

	$split = '{`}';
	if ($_POST['acode'] || $_POST['fnum'] || $_POST['fdets']) {
		$flightDets = strtoupper($_POST['acode']) . $split
					. $_POST['fnum'] . $split
					. $_POST['fdets'];
	}

	$regionID = get_service_region($_POST['fromLoc']);
	$vehicle_type = ($_POST['carTypeSelect'] == '') ? 'P' : $_POST['carTypeSelect'];
	
	if(isset($authWait) || $_POST['toLoc'] == 'asDirectedLoc')
		$trip_type = 'H';
	elseif(isset($stopLoc))
		$trip_type = 'I';
	else
		$trip_type = 'P';

	$passenger_count = $_POST['pax'];
	$meet_greet = ($_POST['greet'] == true) ? 1:0;
	$estimate = $_POST['estimate'];
	$estimate = 0; // FIXME: this function actually never worked

  if($_POST['from_location']){
    $fromLoc = $_POST['from_location'];
  } else {

    $fromLocation = array();
    $fromLocation['type'] = $_POST['from_type'];
    $fromLocation['scheduleid'] = $scheduleid;

    $fromLocation['name'] = $_POST['from_name'];
    $fromLocation['city'] = $_POST['from_city'];
    $fromLocation['address'] = $_POST['from_address'];
    $fromLocation['state'] = $_POST['from_state'];
    $fromLocation['zip'] = $_POST['from_zip'];

    $fromLoc = add_resource($fromLocation, true);
  }

  if($_POST['to_location']){
    $toLoc = $_POST['to_location'];
  } else {

    $toLocation = array();
    $toLocation['type'] = $_POST['to_type'];
    $toLocation['scheduleid'] = $scheduleid;

    $toLocation['name'] = $_POST['to_name'];
    $toLocation['address'] = $_POST['to_address'];
    $toLocation['city'] = $_POST['to_city'];
    $toLocation['state'] = $_POST['to_state'];
    $toLocation['zip'] = $_POST['to_zip'];

    $toLoc = add_resource($toLocation, true);
  }

  

  if($fn == 'create'){
	  $res->add_res($fromLoc, $toLoc, $_SESSION['currentID'], $startTime, $startTime + 15, $repeat, $_POST['date'], $_POST['minRes'], $_POST['maxRes'], stripslashes($_POST['summary']), stripslashes($_POST['special']), $flightDets, (isset($_POST['checkBags'])?1:0), $scheduleid, $specialItems, stripslashes($_POST['dispNotes']), $_POST['coupon'], null, $authWait, $paymentProfileId, $stopLoc, (isset($_POST['autoBillOverride'])?1:0), $convertible_seats, $booster_seats, $regionID, $vehicle_type, $trip_type, $passenger_count, $meet_greet, $estimate);
//		$res->add_res($_POST['fromLoc'], $_POST['toLoc'], $_SESSION['currentID'], $startTime, $startTime + 15, $repeat, $_POST['date'], $_POST['minRes'], $_POST['maxRes'], stripslashes($_POST['summary']), $flightDets, (isset($_POST['checkBags'])?1:0), $_POST['scheduleid'], $specialItems, stripslashes($_POST['dispNotes']), $_POST['coupon'], null, $authWait, $paymentProfileId, $stopLoc, (isset($_POST['autoBillOverride'])?1:0), $convertible_seats, $booster_seats, $regionID, $vehicle_type, $trip_type, $passenger_count, $meet_greet, $estimate);
  } else if ($fn == 'modify') {
//		$ok = $res->db->check_disp_state($_POST['resid']);
	  $ok = $res->db->check_disp_state($_GET['resid']);

	  if (!$ok && $_SESSION['role'] != 'm') {
		  $res->print_res_fail();
	  } else {
		  //$check = checkGPS($_POST['fromLoc'], $_POST['toLoc']);
		  //if ($check['reason'])
		  //	$res->print_fail($check, $_POST['scheduleid']);
		  //else {
			  $res->mod_res($fromLoc, $toLoc,$_POST['date'], $startTime, $startTime + 15, isset($_POST['del']), $_POST['minRes'], $_POST['maxRes'], isset($_POST['mod_recur']), stripslashes($_POST['summary']), $flightDets, (isset($_POST['checkBags'])?1:0), $specialItems, $_POST['scheduleid'], stripslashes($_POST['dispNotes']), $_POST['coupon'], null, $authWait, $paymentProfileId, $stopLoc,(isset($_POST['autoBillOverride'])?1:0), $convertible_seats, $booster_seats, $regionID, $vehicle_type, $trip_type, $passenger_count, $meet_greet, $estimate);
			  //mod_dynstat($_POST['resid'], "planet_reservations");
		  //}
	  }
  } else if ($fn == 'delete') {
//		$ok = $res->db->check_disp_state($_POST['resid']);
	  $ok = $res->db->check_disp_state($_GET['resid']);

	  if (!$ok && $_SESSION['role'] != 'm') {
		  $res->print_res_fail();
	  } else {
	  $res->del_res(isset($_POST['mod_recur']));
	  //del_dynstat($_POST['resid']);
	  }
  }

//  return $res;
}

/**
* Prints out reservation info depending on what parameters
*  were passed in through the query string
* @param none
*/
function present_reservation($resid) {
	global $Class;

	// Get info about this reservation
	$res = new $Class($resid);
	$res->print_res();
}


/**
* Return array of data from query string about this reservation
*  or about a new reservation being created
* @param none
*/
function getResInfo() {
	$res_info = array();
	global $Class;

	// Determine title and set needed variables
	$res_info['type'] = $_GET['type'];
	switch($res_info['type']) {
		case 'r' :
			$res_info['title'] = "New $Class";
			$res_info['resid']	= null;
			break;
		case 'm' :
			$res_info['title'] = "Modify $Class";
			$res_info['resid'] = $_GET['resid'];
			break;
		case 'd' :
			$res_info['title'] = "Delete $Class";
			$res_info['resid'] = $_GET['resid'];
			break;
		default : $res_info['title'] = "View $Class";
			$res_info['resid'] = $_GET['resid'];
			break;
	}

	return $res_info;
}

/**
* Returns an array of all timestamps for repeat reservations
* @param string $initial_ts timestamp of first reservation
* @param string $interval interval of reservation recurrances
* @param array $days days of week to repeat on
* @param string $until final date of recurrance
* @param int $frequency frequency of interval
* @param string $week_number week of month number (for reserve by day of month)
*/
function get_repeat_dates($initial_ts, $interval, $days, $until, $frequency, $week_number) {
	$res_dates = array();
	$initial_date = getdate($initial_ts);
	list($last_m, $last_d, $last_y) = explode('/', $until);
	//$last_ts = mktime(0,0,0,$last_m, $last_d, $last_y);
	$last_ts = -1;
	
	$last_date = getdate($last_ts);

	$day_of_week = $initial_date['wday'];
	$day_of_month = $initial_date['mday'];

	$ts = $initial_ts;

	if ($initial_ts > $last_ts)		// Recurring date is in the past
		return array($ts);

	switch ($interval) {
		case 'day' :
			for ($i = $frequency; $ts <= $last_ts; $i += $frequency) {
				$res_dates[] = $ts;
				$ts = mktime(0,0,0, $initial_date['mon'], $i + $initial_date['mday'], $initial_date['year']);
			}
		break;
		case 'week' :
			$additional_days = 0;
			$res_dates[] = $ts;		// Add initial reservation

			while ($ts <= $last_ts) {
				for ($i = 0; $i < count($days); $i++) {					// Repeat for all days selected
					$days_between = ($days[$i] - $day_of_week) + $additional_days;
					// If the day of week is less than reservation day of week, move ahead one week
					if ($days[$i] <= $day_of_week) {
						$days_between += 7;
					}
					$ts = mktime(0,0,0,$initial_date['mon'], $initial_date['mday'] + $days_between, $initial_date['year']);

					if ($ts <= $last_ts)
						$res_dates[] = $ts;
				}
				$additional_days += $frequency * 7;	// Move ahead week
			}
		break;
		case 'month_date' :
			$next_month = $initial_date['mon'];
			$res_dates[] = $ts;			// Add initial reservation

			while ($ts <= $last_ts) {
				$next_month += $frequency;
				if (date('t',mktime(0,0,0, $next_month, 1, $initial_date['year'])) >= $initial_date['mday']) {		// Make sure month has enough days
					$ts = mktime(0,0,0,$next_month, $initial_date['mday'], $initial_date['year']);
					if ($ts <= $last_ts)
						$res_dates[] = $ts;
				}
			}
		break;
		case 'month_day' :
			$res_dates[] = $ts;		// Add initial reservation

			$days_in_month = date('t', mktime(0,0,0, $initial_date['mon'], $initial_date['mday'], $initial_date['year']));
			$next_month = $initial_date['mon'];

			// Fill in all months
			while ($ts <= $last_ts) {

				$days_in_month = date('t', mktime(0,0,0, $next_month, 1, $initial_date['year']));
				$first_day_of_month = date('w', mktime(0,0,0, $next_month, 1, $initial_date['year']));
				$last_day_of_month = date('w', mktime(0,0,0, $next_month, $days_in_month, $initial_date['year']));

				if ($week_number != 'last') {
					$offset_date = ($week_number - 1) * 7 + 1; 		// Starting date
					$day_of_week = $first_day_of_month;				// Day of week
				}
				else {
					$offset_date = $days_in_month - 6;
					$day_of_week = $last_day_of_month + 1;
				}

				// Repeat on chosen days for this week
				for ($i = 0; $i < count($days); $i++) {					// Repeat for all days selected
					$days_between = ($days[$i] - $day_of_week);

					// If the day of week is less than reservation day of week, move ahead one week
					if ($days[$i] < $day_of_week) {
						$days_between += 7;
					}

					$current_date = $offset_date + $days_between;

					$need_to_add = ( ($current_date <= $days_in_month) && ($next_month > $initial_date['mon'] || ($current_date >= $initial_date['mday'] && $next_month >= $initial_date['mon'])) );

					if ($need_to_add)
						$ts = mktime(0,0,0, $next_month, $current_date, $initial_date['year']);

					if ( $ts <= $last_ts && $need_to_add && $ts != $initial_ts)// && ($current_date <= $days_in_month) && ($current_date >= $initial_date['mday'] && $next_month >= $initial_date['mon']) )
						$res_dates[] = $ts;
				}

				$next_month += $frequency;
			}
		break;
	}
	return $res_dates;
}
?>
