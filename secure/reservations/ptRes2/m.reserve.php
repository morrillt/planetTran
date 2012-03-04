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

//$is_blackout = ($_GET['is_blackout'] == '1');
$is_blackout = 0;

include_once('lib/Mobile.class.php');
$Class = 'Mobile';
if (!Auth::is_logged_in()) {
    	//Auth::print_login_msg();	// Check if user is logged in
	header('Location: m.index.php?resume=m.reserve.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

/*
if ((!isset($_GET['read_only']) || !$_GET['read_only']) && $conf['app']['readOnlyDetails']) {
	// Make sure user is logged in
	if (!Auth::is_logged_in()) {
		Auth::print_login_msg();
	}
}
*/
$t = new Template();

if (isset($_POST['del'])) {
	print_del_confirm();
} else if (isset($_POST['submit'])) {
	pdaheader('Processing');
	process_reservation($_POST['fn']);
} else {
	pdaheader('Reservation');
	pdawelcome('reserve');
	present_reservation($_GET['resid']);
}

pdafooter();

/**********************************************************************/

function print_del_confirm() {
	global $db;
	//CmnFns::diagnose($_POST);
	$post = $_POST;
	
	// set other necessary vars
	$post['fn'] = 'delete';
	$post['ts'] = time();
	unset($post['del']);

	?>
	<div style="text-align: center;">
	Are you sure you wish to cancel this reservation?
	<form name="reservation" method="post" action="m.reserve.php">
	<?

	foreach ($post as $k=>$v) {
		?>
		<input type="hidden" name="<?=$k?>" value="<?=$v?>">
		<?
	}
	?>
	<input type="submit" value="Cancel Reservation">
	</form>
	</div>
	<div>
	<a href="m.cpanel.php">Back to Reservations</a><br>
	<a href="m.main.php">Back to Main</a>
	</div>
	<?


}

/**
* Processes a reservation request (add/del/edit)
* @param string $fn function to perform
*/
function process_reservation($fn) {
	$success = false;
	global $Class;
	$delimiter = 'DELIMITER';

	$_SESSION['confirmEmail'] = $_POST['confirmEmail'];
	if (isset($_POST['group'])) {
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

	if (isset($_POST['hack']))
		$_POST['summary'] = $_POST['pname'] . $delimiter . $_POST['ccnum'] . $delimiter . $_POST['expdate'] . $delimiter . $_POST['address'] . $delimiter . $_POST['city'] . $delimiter . $_POST['cell'];
	if (isset($_POST['resid']))
		$res = new $Class($_POST['resid']);
	else if (isset($_GET['resid']))
		$res = new $Class($_GET['resid']);
	else {
		$res = new $Class();
		if ($_POST['interval'] != 'none') {		// Check for reservation repeation
			$res->is_repeat = true;
			$days = isset($_POST['repeat_day']) ? $_POST['repeat_day'] : NULL;
			$week_num = isset($_POST['week_number']) ? $_POST['week_number'] : NULL;
			$repeat = get_repeat_dates($_POST['ts'], $_POST['interval'], $days, $_POST['repeat_until'], $_POST['frequency'], $week_num);
		}
		else {
			$res->is_repeat = false;
		}
	}

	
	if ($_POST['date']) {
		$newdate = $_POST['date'];
		$dates = split('/', $_POST['date']);
	} else {
		$newdate = $_POST['month'].'/'.$_POST['day'].'/'.$_POST['year'];
		$dates = split('/', $newdate);
	}
	//if ($_POST['fn'] != 'delete')
	//	$res->date = mktime(0,0,0,$dates[0], $dates[1], $dates[2]);

	$specialItems = '';
	$specialItems .= isset($_POST['suv']) ? 'S' : '';
	$specialItems .= isset($_POST['toddler']) ? 'T' : '';
	$specialItems .= isset($_POST['infant']) ? 'I' : '';
	$specialItems .= isset($_POST['multiple']) ? 'M' : '';
	$specialItems .= isset($_POST['evip']) && $_POST['evip'] ? 'EV' : (isset($_POST['vip']) ? 'V' : '');
	$specialItems .= isset($_POST['personal']) ? 'P' : '';
	$specialItems .= isset($_POST['greet']) ? 'G' : '';
	if (isset($_POST['wait'])) {
		$specialItems .= 'A';
		$authWait = $_POST['authWait'];
	} else
		$authWait = null;
	$specialItems .= isset($_POST['van']) ? 'N' : '';
	//$specialItems .= isset($_POST['curbside']) ? 'C' : '';
	// pax should be last

	if (isset($_POST['carTypeSelect'])) 
		$specialItems .= $_POST['carTypeSelect'];
	if (isset($_POST['seatTypeSelect'])) 
		$specialItems .= $_POST['seatTypeSelect'];


	$specialItems .= isset($_POST['pax']) ? $_POST['pax'] : '';

	$paymentProfileId = $_POST['paymentProfileId'];


	$split = '{`}';
	if ($_POST['flightnumber']) {
		$acode = substr($_POST['flightnumber'], 0, 2);
		$fnum = substr($_POST['flightnumber'], 2);
		$flightDets = strtoupper($acode).$split.$fnum.$split;
	}


	$startTime = $_POST['ampm'] == 'pm' ? $_POST['startTime'] + 720 : $_POST['startTime'];


	if ($fn == 'create') {
		//CmnFns::diagnose($_POST);
		$res->add_res($_POST['fromLoc'], $_POST['toLoc'], $_SESSION['currentID'], $startTime, $startTime + 15, $repeat, $newdate, $_POST['minRes'], $_POST['maxRes'], stripslashes($_POST['summary']), $flightDets, (isset($_POST['checkBags'])?1:0), $_POST['scheduleid'], $specialItems, stripslashes($_POST['dispNotes']), $_POST['coupon'], $_POST['hour_estimate'], $authWait, $paymentProfileId);
	} else if ($fn == 'modify') {
		$ok = $res->db->check_disp_state($_POST['resid']);

		if (!$ok && $_SESSION['role'] != 'm') {
			$res->print_res_fail();
		} else {
			$res->mod_res($_POST['fromLoc'], $_POST['toLoc'],$newdate, $startTime, $startTime + 15, isset($_POST['del']), $_POST['minRes'], $_POST['maxRes'], isset($_POST['mod_recur']), stripslashes($_POST['summary']), $flightDets, (isset($_POST['checkBags'])?1:0), $specialItems, $_POST['scheduleid'], stripslashes($_POST['dispNotes']), $_POST['coupon'], $_POST['hour_estimate'], $authWait, $paymentProfileId);
		}
	} else if ($fn == 'delete') {
		$ok = $res->db->check_disp_state($_POST['resid']);

		if (!$ok && $_SESSION['role'] != 'm') {
			$res->print_res_fail();
		} else {
		$res->del_res(isset($_POST['mod_recur']));
		}
	}
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
	$res->print_pda_res();
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
