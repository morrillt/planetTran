<?php
/**
* This functions common to most pages
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 09-01-04
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
* Include configuration file
**/
include_once(BASE_DIR . '/config/config.php');
/**
* Include Link class
*/
include_once('Link.class.php');
/**
* Include Pager class
*/
include_once('Pager.class.php');

/**
* Provides functions common to most pages
*/
class CmnFns {

	/**
	* Convert minutes to hours
	* @param double $time time to convert in minutes
	* @return string time in 12 hour time
	*/
	function formatTime($time, $simple = null) {
		global $conf;

		// Set up time array with $timeArray[0]=hour, $timeArray[1]=minute
		// If time does not contain decimal point
		// then set time array manually
		// else explode on the decimal point
		if($time >= 1440) $time -= 1440; // this is a dumb hack to double check that time isnt greater than a full day...
		$hour = intval($time / 60);
		$min = $time % 60;
		if ($conf['app']['timeFormat'] == 24) {
			$a = '';									// AM/PM does not exist
			if ($hour < 10) $hour = '0' . $hour;
		} else if ($simple == true) {
			$a = '';
			if ($hour == 0) $hour = 12;
		} else {
			$a = ($hour < 12 || $hour == 24) ? translate('am') : translate('pm');			// Set am/pm
			if ($hour > 12) $hour = $hour - 12;			// Take out of 24hr clock
			if ($hour == 0) $hour = 12;					// Don't show 0hr, show 12 am
		}
		// Set proper minutes (the same for 12/24 format)
		if ($min < 10) $min = 0 . $min;
		// Put into a string and return
		return $hour . ':' . $min . $a;
	}


	/**
	* Convert timestamp to date format
	* @param string $date timestamp
	* @param string $format format to put datestamp into
	* @return string date as $format or as default format
	*/
	function formatDate($date, $format = '') {
		global $dates;

		if (empty($format)) $format = $dates['general_date'];
		return strftime($format, $date+23);
	}


	/**
	* Convert UNIX timestamp to datetime format
	* @param string $ts MySQL timestamp
	* @param string $format format to put datestamp into
	* @return string date/time as $format or as default format
	*/
	function formatDateTime($ts, $format = '') {
		global $conf;
		global $dates;

		if (empty($format))
			$format = $dates['general_datetime'] . ' ' . (($conf['app']['timeFormat'] ==24) ? '%H' : '%I') . ':%M:%S' . (($conf['app']['timeFormat'] == 24) ? '' : ' %p');
		return strftime($format, $ts+23);
	}


	/**
	* Convert minutes to hours/minutes
	* @param int $minutes minutes to convert
	* @return string version of hours and minutes
	*/
	function minutes_to_hours($minutes) {
		if ($minutes == 0)
			return '0 ' . translate('hours');

		$hours = (intval($minutes / 60) != 0) ? intval($minutes / 60) . ' ' . translate('hours') : '';
		$min = (intval($minutes % 60) != 0) ? intval($minutes % 60) . ' ' . translate('minutes') : '';
		return ($hours . ' ' . $min);
	}

	/**
	* Return the current script URL directory
	* @param none
	* @return url url of curent script directory
	*/
	function getScriptURL() {
		global $conf;
		$uri = $conf['app']['weburi'];
		return (strrpos($uri, '/') === false) ? $uri : substr($uri, 0, strlen($uri));
	}


	/**
	* Prints an error message box and kills the app
	* @param string $msg error message to print
	* @param string $style inline CSS style definition to apply to box
	* @param boolean $die whether to kill the app or not
	*/
	function do_error_box($msg, $style='', $die = true, $mobile = false, $code = '') {
		global $conf;

		echo '<table border="0" cellspacing="0" cellpadding="0" align="center" class="alert" style="' . $style . '"><tr><td>' . $msg . '</td></tr></table>';
		if ($mobile) echo '<a href="m.cpanel.php">Back</a>';

		if ($die) {
//			echo '</td></tr></table>';		// endMain() in Template
			//echo '<p align="center"><a href="http://phpscheduleit.sourceforge.net">phpScheduleIt v' . $conf['app']['version'] . '</a></p>
//			echo '</body></html>';	// printHTMLFooter() in Template

      echo $code;

		 	die();
		}

    echo $code;
	}

	/**
	* Prints out a box with notification message
	* @param string $msg message to print out
	* @param string $style inline CSS style definition to apply to box
	*/
	function do_message_box($msg, $style='') {
		echo '<table border="0" cellspacing="0" cellpadding="0" align="center" class="message" style="' . $style . '"><tr><td>' . $msg . '</td></tr></table>';
	}

	/**
	* Returns a reference to a new Link object
	* Used to make HTML links
	* @param none
	* @return Link object
	*/
	function getNewLink() {
		return new Link();
	}

	/**
	* Returns a reference to a new Pager object
	* Used to iterate over limited recordesets
	* @param none
	* @return Pager object
	*/
	function getNewPager() {
		return new Pager();
	}

	/**
	* Strip out slahses from POST values
	* @param none
	* @return array of cleaned up POST values
	*/
	function cleanPostVals() {
		$return = array();

		foreach ($_POST as $key => $val)
			$return[$key] = stripslashes(trim($val));

		return $return;
	}

	/**
	* Strip out slahses from an array of data
	* @param none
	* @return array of cleaned up data
	*/
	function cleanVals($data) {
		$return = array();

		foreach ($data as $key => $val)
			$return[$key] = stripslashes($val);

		return $return;
	}

	/**
	* Verifies vertical order and returns value
	* @param string $vert value of vertical order
	* @return string vertical order
	*/
	function get_vert_order($get_name = 'vert') {
		// If no vertical value is specified, use ASC
		$vert = isset($_GET[$get_name]) ? $_GET[$get_name] : 'ASC';

		// Validate vert value, default to DESC if invalid
		switch($vert) {
			case 'DESC';
			case 'ASC';
			break;
			default :
				$vert = 'DESC';
			break;
		}

		return $vert;
	}

	/**
	* Verifies and returns the order to list recordset results by
	* If none of the values are valid, it will return the 1st element in the array
	* @param array $orders all valid order names
	* @return string order of recorset
	*/
	function get_value_order($orders = array(), $get_name = 'order') {
		if (empty($orders))		// Return null if the order array is empty
			return NULL;

		// Set default order value
		// If a value is specifed in GET, use that.  Else use the first element in the array
		$order = isset($_GET[$get_name]) ? $_GET[$get_name] : $orders[0];

		if (in_array($order, $orders))
			$order = $order;
		else
			$order = $orders[0];

		return $order;
	}


	/**
	* Opposite of php's nl2br function.
	* Subs in a newline for all brs
	* @param string $subject line to make subs on
	* @return reformatted line
	*/
	function br2nl($subject) {
		return str_replace('<br />', "\n", $subject);
	}

	/**
	* Writes a log string to the log file specified in config.php
	* @param string $string log entry to write to file
	* @param string $userid memeber id of user performing the action
	* @param string $ip ip address of user performing the action
	*/
	function write_log($string, $userid = NULL, $ip = NULL) {
		global $conf;
		$delim = "\t";
		$file = $conf['app']['logfile'];
		$values = '';

		if (!$conf['app']['use_log'])	// Return if we aren't going to log
			return;

		if (empty($ip))
			$ip = $_SERVER['REMOTE_ADDR'];

		clearstatcache();				// Clear cached results

		if (!is_dir(dirname($file)))
			mkdir(dirname($file), 0777);		// Create the directory

		if (!touch($file))
			return;					// Return if we cant touch the file

		if (!$fp = fopen($file, 'a'))
			return;					// Return if the fopen fails

		flock($fp, LOCK_EX);		// Lock file for writing
		if (!fwrite($fp, '[' . date('D, d M Y H:i:s') . ']' . $delim . $string . $delim . $userid . $delim . $ip . "\r\n"))	// Write log entry
        	return;					// Return if we cant write to the file
		flock($fp, LOCK_UN);		// Unlock file
		fclose($fp);
	}

	/**
	* Returns the day name
	* @param int $day_of_week day of the week
	* @param int $type how to return the day name (0 = full, 1 = one letter, 2 = two letter, 3 = three letter)
	*/
	function get_day_name($day_of_week, $type = 0) {
		global $days_full;
		global $days_abbr;
		global $days_letter;
		global $days_two;

		$names = array (
			$days_full, $days_letter, $days_two, $days_letter
			/*
			array ('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
			array ('S', 'M', 'T', 'W', 'T', 'F', 'S'),
			array ('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'),
			array ('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat')
			*/
			);

		return $names[$type][$day_of_week];
	}

	/**
	* Redirects a user to a new location
	* @param string $location new http location
	* @param int $time time in seconds to wait before redirect
	*/
	function redirect($location, $time = 0, $die = true) {
		header("Refresh: $time; URL=$location");
		if ($die) exit;
	}

	/**
	* Prints out the HTML to choose a language
	* @param none
	*/
	function print_language_pulldown() {
		global $conf;
		?>
		<select name="language" class="textbox" onchange="changeLanguage(this);">
		<?
			$languages = get_language_list();
			foreach ($languages as $lang => $settings) {
				echo '<option value="' . $lang . '"'
					. ((determine_language() == $lang) ? ' selected="selected"' : '' )
					. '>' . $settings[3] . ($lang == $conf['app']['defaultLanguage'] ? ' ' . translate('(Default)') : '') . "</option>\n";
			}
		?>
		</select>
		<?
	}

	/**
	* Print out an array neatly
	*/
	function diagnose($a) {
		echo "<pre>";
		print_r($a);
		echo "</pre>";
	}

	/*
	*/
	function showid($resid) {
		return strtoupper(substr($resid, -6));
	}

	/*
	* Return true if location is an airport
	*/
	function isAirport($machid) {
		if (strpos($machid, 'airport') !== false)
			return true;
		else if ($machid == '41b40be9091cb' ||
			 $machid == 'gzm41b48f6ae8d87')
			return true;

		return false;
	}

	/*
	* Return a string with driver's first name and last initial
	*/
	function driver_shortname($name) {
		$driverArr = explode(" ", $name, 2);
		return $driverArr[0]." ".substr($driverArr[1], 0, 1);
	}

	function reservation_origin($code) {
		$return = "PlanetTran ";
		if ($code == 'w')
			$return .= "Online";
		else if ($code == 'm')
			$return .= "Mobile";
		else if ($code == 'p')
			$return .= "Representative";
		else if ($code == 't')
			$return = "Travel Agent";
		else if ($code == 'i')
			$return = "Tweet My Ride";
		else if ($code == 'h')
			$return = "Digital Hailing";
		else
			return '';
		return $return;
	}
	/*
	* determine whether a location is an airport, for lookup in the DB
	* - this function also escapes the shell args
	*/
	function airport_or_zip($machid, $zip) {
		if (strpos($machid, 'airport') !== false)
			$return = substr($machid, -3);
		else if (stripos($machid, 'logan') !== false)
			$return = 'BOS';
		else if ($machid == '41b40be9091cb')
			$return = 'BOS';
		else
			$return = $zip;

		return escapeshellarg($return);
	}

	/*
	 Calculate distance between two coordinates
	 $format is return unit
	 3963.1 = miles
	 6378 = km
	 20925524.9 = feet
	*/
	function gps_distcalc($loc1_lat, $loc1_lon, $loc2_lat, $loc2_lon) { 
		$format= 3963.1;
		if (!$loc1_lat || !$loc1_lon || !$loc2_lat || !$loc2_lon) return 0;
	

		$lat1 = $loc1_lat * pi()/180;
		$lat2 = $loc2_lat * pi()/180;
		$lon1 = $loc1_lon * pi()/180;
		$lon2 = $loc2_lon * pi()/180;

		$theta = $lon1 - $lon2;
		$rawdistance = sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta);
		$distance = $format * acos($rawdistance);
		$distance = round($distance, 2);
		if (!$distance) $distance = 0;
		return $distance;
	}

	/*
	 Calculate distance from home base. units same as above
	*/
	function homebase_distcalc($loc1_lat, $loc1_lon, $state = 'MA', $format= 3963.1) {
		if (!$loc1_lat || !$loc1_lon) return 1;
		
		$loc2_lat = $state == 'CA' ? 37.614800 : 42.399900;
		$loc2_lon = $state == 'CA' ? -122.391400 : -71.063173; 

		$lat1 = $loc1_lat * pi()/180;
		$lat2 = $loc2_lat * pi()/180;
		$lon1 = $loc1_lon * pi()/180;
		$lon2 = $loc2_lon * pi()/180;

		$theta = $lon1 - $lon2;
		$rawdistance = sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta);
		$distance = intval($format * acos($rawdistance));
		if (!$distance) $distance = 1;
		return $distance;
	}

	/*
	*  Create an array with keys that are the same as values
	*/
	function copy_vals_to_keys($array) {
		$return = array();
		foreach($array as $v)
			$return[$v] = $v;
		return $return;
	}

	/*
	* Check if a variable is being passed by either GET or POST
	* return null if not
	*/
	function getOrPost($x) {
		if (isset($_GET[$x]))
			return $_GET[$x];
		else if (isset($_POST[$x]))
			return $_POST[$x];
		else
			return null;
	}
	/*
	* 	Export result set as Excel spreadsheet
	*	$result DB result object
	*	$title title of page, defaults to current timestamp
	*	$stream whether to stream the file immediately (must be done before
	*		any output on the parent page)
	*/
	function export_excel_db($result, $title = '', $stream = true) {
		$reports = 0;
		if (!$title) $title = time() . ".xls";


		$count = mysql_num_fields($result);
		$colnames = '';
		for($i=0;$i<$count;$i++){
			$field = mysql_field_name($result, $i);
			$field = str_replace("_", " ", $field);
			$field = ucwords($field);
			$colnames .= "$field\t";
			//$colnames .= mysql_field_name($result, $i) . "\t";
		}


		$data = '';

		while ($row = mysql_fetch_assoc($qresult)) {
			$line = '';
			foreach ($row as $v) {
				if(!isset($v) || $v == "")
					$v = "\t";
				else {
					// quote properly
					$v = str_replace('"', '""', $v);
					$v = '"'.$v.'"'."\t";
				}
				$line .= $v;
			}
			
			$data .= trim($line) . "\n";
		}
		$data = str_replace("\r", "", $data);
	
		////// Stream file //////
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=$title");
		header("Expires: 0");
		header("Cache-Control: private");
		header("Pragma: cache");
		print "$colnames\n$data";
	}

	function export_excel_array($array, $title = '', $stream = true) {
		$reports = 0;
		if (!$title) $title = time() . ".xls";


		$head = $array[0];

		$colnames = '';
		foreach ($head as $k => $v) {
	
			$field = $k;
			$field = str_replace("_", " ", $field);
			$field = ucwords($field);
			$colnames .= "$field\t";
		}

		$data = '';

		for ($i=0; $array[$i]; $i++) {
			$row = $array[$i];
			$line = '';
			foreach ($row as $v) {
				if(!isset($v) || $v == "")
					$v = "\t";
				else {
					// quote properly
					$v = str_replace('"', '""', $v);
					$v = '"'.$v.'"'."\t";
				}
				$line .= $v;
			}
			
			$data .= trim($line) . "\n";
		}
		$data = str_replace("\r", "", $data);
	
		////// Stream file //////
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=$title");
		header("Expires: 0");
		header("Cache-Control: private");
		header("Pragma: cache");
		print "$colnames\n$data";
	}
    function get_state_from_zip($zip){
        if($zip >= '99501' &&  $zip<= '99950'){
             return 'AK' ;
        } else if($zip >= '35004' &&  $zip<= '36925'){
             return 'AL' ;
        } else if($zip >= '71601' &&  $zip<= '72959'){
             return 'AR' ;
        } else if($zip >= '75502' &&  $zip<= '75502'){
             return 'AR' ;
        } else if($zip >= '85001' &&  $zip<= '86556'){
             return 'AZ' ;
        } else if($zip >= '90001' &&  $zip<= '96162'){
             return 'CA' ;
        } else if($zip >= '80001' &&  $zip<= '81658'){
             return 'CO' ;
        } else if($zip >= '06001' &&  $zip<= '06389'){
             return 'CT' ;
        } else if($zip >= '06401' &&  $zip<= '06928'){
             return 'CT' ;
        } else if($zip >= '20001' &&  $zip<= '20039'){
             return 'DC' ;
        } else if($zip >= '20042' &&  $zip<= '20599'){
             return 'DC' ;
        } else if($zip >= '20799' &&  $zip<= '20799'){
             return 'DC' ;
        } else if($zip >= '19701' &&  $zip<= '19980'){
             return 'DE' ;
        } else if($zip >= '32004' &&  $zip<= '34997'){
             return 'FL' ;
        } else if($zip >= '30001' &&  $zip<= '31999'){
             return 'GA' ;
        } else if($zip >= '39901' &&  $zip<= '39901'){
             return 'GA' ;
        } else if($zip >= '96701' &&  $zip<= '96898'){
             return 'HI' ;
        } else if($zip >= '50001' &&  $zip<= '52809'){
             return 'IA' ;
        } else if($zip >= '68119' &&  $zip<= '68120'){
             return 'IA' ;
        } else if($zip >= '83201' &&  $zip<= '83876'){
             return 'ID' ;
        } else if($zip >= '60001' &&  $zip<= '62999'){
             return 'IL' ;
        } else if($zip >= '46001' &&  $zip<= '47997'){
             return 'IN' ;
        } else if($zip >= '66002' &&  $zip<= '67954'){
             return 'KS' ;
        } else if($zip >= '40003' &&  $zip<= '42788'){
             return 'KY' ;
        } else if($zip >= '70001' &&  $zip<= '71232'){
             return 'LA' ;
        } else if($zip >= '71234' &&  $zip<= '71497'){
             return 'LA' ;
        } else if($zip >= '01001' &&  $zip<= '02791'){
             return 'MA' ;
        } else if($zip >= '05501' &&  $zip<= '05544'){
             return 'MA' ;
        } else if($zip >= '20331' &&  $zip<= '20331'){
             return 'MD' ;
        } else if($zip >= '20335' &&  $zip<= '20797'){
             return 'MD' ;
        } else if($zip >= '20812' &&  $zip<= '21930'){
             return 'MD' ;
        } else if($zip >= '03901' &&  $zip<= '04992'){
             return 'ME' ;
        } else if($zip >= '48001' &&  $zip<= '49971'){
             return 'MI' ;
        } else if($zip >= '55001' &&  $zip<= '56763'){
             return 'MN' ;
        } else if($zip >= '63001' &&  $zip<= '65899'){
             return 'MO' ;
        } else if($zip >= '38601' &&  $zip<= '39776'){
             return 'MS' ;
        } else if($zip >= '71233' &&  $zip<= '71233'){
             return 'MS' ;
        } else if($zip >= '59001' &&  $zip<= '59937'){
             return 'MT' ;
        } else if($zip >= '27006' &&  $zip<= '28909'){
             return 'NC' ;
        } else if($zip >= '58001' &&  $zip<= '58856'){
             return 'ND' ;
        } else if($zip >= '68001' &&  $zip<= '68118'){
             return 'NE' ;
        } else if($zip >= '68122' &&  $zip<= '69367'){
             return 'NE' ;
        } else if($zip >= '03031' &&  $zip<= '03897'){
             return 'NH' ;
        } else if($zip >= '07001' &&  $zip<= '08989'){
             return 'NJ' ;
        } else if($zip >= '87001' &&  $zip<= '88441'){
             return 'NM' ;
        } else if($zip >= '88901' &&  $zip<= '89883'){
             return 'NV' ;
        } else if($zip >= '06390' &&  $zip<= '06390'){
             return 'NY' ;
        } else if($zip >= '10001' &&  $zip<= '14975'){
             return 'NY' ;
        } else if($zip >= '43001' &&  $zip<= '45999'){
             return 'OH' ;
        } else if($zip >= '73001' &&  $zip<= '73199'){
             return 'OK' ;
        } else if($zip >= '73401' &&  $zip<= '74966'){
             return 'OK' ;
        } else if($zip >= '97001' &&  $zip<= '97920'){
             return 'OR' ;
        } else if($zip >= '15001' &&  $zip<= '19640'){
             return 'PA' ;
        } else if($zip >= '02801' &&  $zip<= '02940'){
             return 'RI' ;
        } else if($zip >= '29001' &&  $zip<= '29948'){
             return 'SC' ;
        } else if($zip >= '57001' &&  $zip<= '57799'){
             return 'SD' ;
        } else if($zip >= '37010' &&  $zip<= '38589'){
             return 'TN' ;
        } else if($zip >= '73301' &&  $zip<= '73301'){
             return 'TX' ;
        } else if($zip >= '75001' &&  $zip<= '75501'){
             return 'TX' ;
        } else if($zip >= '75503' &&  $zip<= '79999'){
             return 'TX' ;
        } else if($zip >= '88510' &&  $zip<= '88589'){
             return 'TX' ;
        } else if($zip >= '84001' &&  $zip<= '84784'){
             return 'UT' ;
        } else if($zip >= '20040' &&  $zip<= '20041'){
             return 'VA' ;
        } else if($zip >= '20040' &&  $zip<= '20167'){
             return 'VA' ;
        } else if($zip >= '20042' &&  $zip<= '20042'){
             return 'VA' ;
        } else if($zip >= '22001' &&  $zip<= '24658'){
             return 'VA' ;
        } else if($zip >= '05001' &&  $zip<= '05495'){
             return 'VT' ;
        } else if($zip >= '05601' &&  $zip<= '05907'){
             return 'VT' ;
        } else if($zip >= '98001' &&  $zip<= '99403'){
             return 'WA' ;
        } else if($zip >= '53001' &&  $zip<= '54990'){
             return 'WI' ;
        } else if($zip >= '24701' &&  $zip<= '26886'){
             return 'WV' ;
        } else if($zip >= '82001' &&  $zip<= '83128'){
             return 'WY' ;
        }
            return '';
    }
}
?>