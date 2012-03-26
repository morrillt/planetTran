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
include_once('lib/Reservation.class.php');
$Class = 'Reservation';

if ((!isset($_GET['read_only']) || !$_GET['read_only']) && $conf['app']['readOnlyDetails']) {
	// Make sure user is logged in
	if (!Auth::is_logged_in()) {
		Auth::print_login_msg();
	}
}

// If read_only is not 1, you're viewing the wrong page.
if ($_GET['read_only'] == 1) {
	$read_only = true;
} else {
	die();
}

$res_info = getResInfo();
echo '<link href="css.css" media="screen" rel="stylesheet" type="text/css" />';
echo '<link href="boxy.css" media="screen" rel="stylesheet" type="text/css" />';
echo '<title>View Reservation</title>';

present_reservation($res_info['resid']);

/**
* Prints out reservation info depending on what parameters
*  were passed in through the query string
* @param none
*/
function present_reservation($resid) {
	global $Class;

	// Get info about this reservation
	$res = new $Class($resid);
	
	$res->print_res_read_only();
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
		default : 
			$res_info['title'] = "View $Class";
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
