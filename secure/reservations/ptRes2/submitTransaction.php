<?php
include_once('lib/db/AdminDB.class.php');
include_once('lib/Transponet.class.php');
include_once('lib/Auth.class.php');
include_once('AuthNet.php');
include_once('apts.php');

$r = new Transponet();

if(!isset($_POST['txnSentTo'])) {
	//must be in debug mode
	$_POST = $r->get_test_post_info();
	$_POST['transactionId']++;
}
	
$p = print_r($_POST, true);
$p .= "\n\n";
$serial = serialize($_POST);
$serial .= "\n\n";

//mail_admins('RQ debug');
file_put_contents('sTrans.log', $p, FILE_APPEND);
file_put_contents('sTrans.log2', $serial, FILE_APPEND);


$userdata['fname'] = $_POST['Passenger_FirstName'];
$userdata['lname'] = $_POST['Passenger_LastName'];
$userdata['emailaddress'] = $_POST['Passenger_EmailAddress'];
$userdata['pnr'] = $_POST['Passenger_PNR'];
if (isset($_POST['Passenger_CellPhone']))
	$phone = $_POST['Passenger_CellPhone'];
else if (isset($_POST['Passenger_DayPhone']))
	$phone = $_POST['Passenger_DayPhone'];
else if (isset($_POST['Passenger_EveningPhone']))
	$phone = $_POST['Passenger_EveningPhone'];
else if (isset($_POST['Pickup_Address_PhoneNo']))
	$phone = $_POST['Pickup_Address_PhoneNo'];
else
	$phone = 'not given';

$userdata['phone'] = $phone;

$saturn = 	true;
$type = 	$_POST['txnTypeCode'];
$member = 	$r->get_company($userdata);
$groupid = 	$member['groupid'];
$memberid = 	$member['memberid'];

// "Umbrella" memberid for file locking with no pnr

$umbrella_memberid = $member['umbrella_memberid'];

$scheduleid = 	$member['scheduleid'];
$frmstation = 	$_POST['Pickup_LocationTypeCode']=='T'?1:0;
$tostation = 	$_POST['Dropoff_LocationTypeCode']=='T'?1:0;
$frmStationId = $toStationId = null;
$frmStationZip = $toStationZip = null;

if ($type == 'NR')
	$dcode = 'DC';
else if ($type == 'CR')
	$dcode = 'CN';
else if ($type == 'XL')
	$dcode = 'XN';
else
	$dcode = "";

if ($frmstation) {
	$frmStationLoc 	= get_station($_POST['Pickup_Train_StationCode']);
	$frmStationId 	= $frmStationLoc['machid'];
	$frmStationZip 	= $frmStationLoc['zip'];
} else if ($tostation) {
	$toStationLoc = get_station($_POST['Dropoff_Train_StationCode']);
	$toStationId = $toStationLoc['machid'];
	$toStationZip = $toStationLoc['zip'];
}

$quote = 	$r->get_quote($groupid, $frmStationZip, $toStationZip);


if ($type == 'RQ') {
	$r = "QR\n".(okState($quote['fromState']) || okState($quote['toState']) ? $quote['price'] : 0) ."\n".$quote['type']."\n".$quote['discount'];
	echo $r;
	mail_admins('quote', $r);
	die;		
}

/****** Bottleneck ********************************************/

$pnr = $_POST['Passenger_PNR'] ? $_POST['Passenger_PNR'] : null;

// If no pnr and we have a memberid, use memberid
if (!$pnr && $umbrella_memberid)
	$pnr = $umbrella_memberid;
else if (!$pnr && $memberid)
	$pnr = $memberid;

$dir = "transaction_locks/";
$path = "$dir$pnr";
$logpath = $dir . "wait_log";

if ($pnr && file_exists($path)) {
	/* There is already a transaction processing with this PNR.
	*  Wait for it to finish. It has 12 seconds until die */
	$attempt_time = 3;
	for($i=0; $i <= 15; $i += $attempt_time) {
		// Log the PNR and time we had to wait
		$waittime = $i + $attempt_time;
		file_put_contents($logpath, "$waittime\t$pnr\n", FILE_APPEND);

		// Pause for $wait_time seconds
		sleep($attempt_time);

		// Lock is gone, continue
		if (!file_exists($path)) {
			touch($path);
			break;
		}

		if ($i >= $attempt_time * 4) 
			kill('Execution timed out', $dcode, 'XXXXXX');
	}

} else if ($pnr) {
	touch($path);
}

/**************************************************************/


$pdate = isset($_POST['Pickup_DateTime']) ? $_POST['Pickup_DateTime'] : '08/21/2007 19:30:00';
list($date, $time) = explode(' ', $pdate);
list($day, $mon, $year) = explode('/', $date);
list($hour, $min, $sec) = explode(':', $time);
$start = $hour*60 + $min;
$end = $start + 15;
$bags = 0;



// Reject Carlson reservations
//if ($groupid == 1427) {
//	kill("Vendor $groupid not registered", $dcode, 'XXXXXX');	
//}

$frmapt = $_POST['Pickup_LocationTypeCode']=='A'?1:0;
$toapt = $_POST['Dropoff_LocationTypeCode']=='A'?1:0;


if (!okState($_POST['Pickup_Address_State']))
	kill('Not a serviced state', $dcode, 'XXXXXX');

if ($frmapt) { // Get airport info, match with airport in DB
	$frmid = get_apts($_POST['Pickup_Flight_AirportCode'], $memberid);
	if (!$frmid) kill('Not a serviced airport', $dcode, 'XXXXXX');
	$from = $frmid;
	$flightDets = $_POST['Pickup_Flight_AirlineCode'].'{`}'.$_POST['Pickup_Flight_FlightNumber'].'{`}'.$_POST['Pickup_Flight_ArrivalTime'].' from '.$_POST['Pickup_Flight_OriginAirport'];

} else if ($frmstation) {
	$frmid = $frmStationId;
	if (!$frmid) kill('Not a serviced station', $dcode, 'XXXXXX');
	$from = $frmid;
} else { // Get location info
	$from = do_fromloc($memberid, $scheduleid);
	$flightDets = null;
}
if ($toapt) {
	$toid = get_apts($_POST['Dropoff_Flight_AirportCode'], $memberid); 
	if (!$toid) kill('Not a serviced airport', $dcode, 'XXXXXX');
	$to = $toid;
} else if ($tostation) {
	$toid = $toStationId;
	if (!$toid) kill('Not a serviced station', $dcode, 'XXXXXX');
	$to = $toid;
} else {
	$to = do_toloc($memberid, $scheduleid);
}




//$from = 'glb462e8182899ac';
//$to   = 'glb462e819b10e28';
$summary = get_summary($r, $memberid, $dcode); 
$specialItems = is_mult_stops() ? 'M' : '';
if ($to == 'asDirectedLoc')
	$specialItems = 'MA';
//if ($_POST['Accounting_CorporateID']=='63')
$specialItems .= 'V';
if ($_POST['Ride_VehicleType'] == 'SUV') $specialItems .= 'S';

$resid = get_resid($_POST['tNResNo']);
$declineResid = strtoupper(substr($resid, -6));

if ($type == 'NR') {
	if ($resid) kill('Transaction res num exists', $dcode, 'XXXXXX');
	$showid = $r->add_gt3_res($from, $to, $memberid, $start, $end, 0, $date, 0, 0, $summary, $flightDets, 0, $scheduleid, $specialItems);
	if (!$showid) kill('Invalid time: please call', $dcode, 'XXXXXX');
	insert_attrs($r->id);
	$return_type = 'AC';
} else if ($type == 'CR') {
	if (!$resid) kill('Transaction id not in attrs', $dcode, 'noresid');
	$showid = $r->mod_gt3_res($resid, $from, $to, $date, $start, $end, false, 0, 0, 0, $summary, $flightDets, $bags, $specialItems);
	if (!$showid) kill('Invalid time: please call', $dcode, $declineResid);
	$return_type = 'CY';
} else if ($type == 'XL') {
	if (!$resid) kill('Transaction id not in attrs', 'XN', 'noresid');
	$showid = $r->del_gt3_res($resid);	
	if (!$showid) kill('Invalid time: please call', $dcode, $declineResid);
	$return_type = 'XY';
} else	// We didn't get any of the codes we were looking for; die
	kill('Bad txnTypeCode', 'XX', 'XXXXXX');
/*
* Get airport description if applicable
*/
$aptDesc = '';
if ($frmapt) {
	foreach ($at as $k => $v) {
		if ($k == $_POST['Pickup_Flight_AirportCode'])
			$aptDesc = "\n".$v;
	}
}

if ($showid)
	$aptDesc = "Your PlanetTran reservation number is $showid. ";
else
	$aptDesc = "";

$aptDesc .= "For pickup procedures at this location, please go to http://www.planettran.com/service.php.  While traveling, call 1 888 756 8876 for any assistance you need.";

$full_return =  $return_type."\n".$showid."\n".$quote['price']."\n".$quote['type']."\n".$aptDesc;
mail_admins('success', $full_return);
echo $full_return;

if ($pnr && file_exists($path)) unlink($path);

/**
* Return
*  success: type, resid, price, price type, apt description
*  failure: type, "resid", reason
*  system failure: XX, XXXXXX, reason
*/

function is_mult_stops() {
	if (isset($_POST['Stop_LocationName']) ||
		isset($_POST['Stop_LocationName_1']) ||
		isset($_POST['Stop_Address_City_1']) ||
		isset($_POST['Stop_Address_StreetName_1']) ||
		isset($_POST['Stop_Address_PhoneNo_1']) ||
		isset($_POST['Stop_Address_PostalCode_1']) ||
		isset($_POST['Stop_LocationTypeCode']) 
		)
		return true;
	else
		return false;
}
function mail_admins($m = '', $r = '') {
	//$to = 'seth@planettran.com, msobecky@gmail.com';
	$to = 'msobecky@gmail.com';
	$from = 'From: reservations@planettran.com';
	$subject = 'submitTransaction: reservation '.$m;
	if (isset($_POST['Payment_CC_Number']))
		$_POST['Payment_CC_Number'] = "****";
	$p = print_r($_POST, true);
	$msg = $r."\n".$p;
	mail($to, $subject, $msg, $from);
}
function del_res($resid) {
	$query = "insert into trip_log (resid, dispatch_status, pay_status,
		    pay_type, driver, vehicle, distance, base_fare, discount,
		    tolls, unpaid_tolls, other, total_fare, invoicee,
		    notes, last_mod_time)
		  values($resid,9,28,30,1,4,0,0,0,0,0,'',0,'',NULL, NOW())
		  ON DUPLICATE KEY
		  update dispatch_status=9";
	$qresult = mysql_query($query);
	return strtoupper(substr($resid, -6));
}
function get_resid($transid) {
	$query = "select tableid from attributes where attr_value='$transid'";
	$qresult = mysql_query($query);
	if (mysql_num_rows($qresult)==0) return false;
	else if (mysql_num_rows($qresult) > 1) kill('duplicate entry in attrs', 'XX', 'XXXXXX');
	$row = mysql_fetch_assoc($qresult);
	return $row['tableid'];
}
function get_summary($r, $memberid, $dcode = null) {
	/*
	* GROUP_DEL:
	* name, cell, code, address, address2, cc, exp, notes, email
	*/

	// Skip these keys when dumping POST into notes
	$skip = array(
		'Passenger_Firstname',
		'Passenger_Lastname',
		'Pickup_DateTime',
		'Booker_Notes',
		'Passenger_EmailAddress',
		'Dropoff_Address_City',
   	 	'Dropoff_Address_PhoneNo',
    		'Dropoff_Address_PostalCode',
    		'Dropoff_Address_State',
    		'Dropoff_LocationName',
		'Pickup_Address_City',
   	 	'Pickup_Address_PhoneNo',
    		'Pickup_Address_PostalCode',
    		'Pickup_Address_State',
    		'Pickup_LocationName',
    		'Dropoff_Address_StreetName',
    		'Dropoff_Address_StreetNo',
    		'Pickup_Address_StreetName',
    		'Pickup_Address_StreetNo',
		'Payment_CC_Number',
		'Payment_CC_ExpDate'
		);
	$dump = '';
	$cc = isset($_POST['Accounting_UDF_Value_1']) ? $_POST['Accounting_UDF_Value_1'] : '';
	foreach ($_POST as $k => $v) {
		if (in_array($k, $skip))
			continue;
		
		if (	$k == 'Passenger_CellPhone' ||
			$k == 'Passenger_DayPhone' ||
			$k == 'Passenger_EveningPhone')
			$v = "<b>$v</b>";
		$dump .= ' '. $v;	
		if ($v == 'CostCenter') {
			$index = substr($k, -1);	
			$index = 'Accounting_UDF_Value_'.$index;
			$cc = $_POST[$index];
		}
	}
	if (isset($_POST['Booker_Notes']))
		$dump = $_POST['Booker_Notes'] . $dump;

	if (is_mult_stops()) {
		$dump .= "Stop 1: ".$_POST['Stop_Address_StreetNo_1']." ".$_POST['Stop_Address_StreetName_1'].", ".$_POST['Stop_Address_City_1']." ".$_POST['Stop_Address_State_1']." ".$_POST['Stop_Address_PhoneNo_1'];
	}

	$dump = "<br>**$dump**";

	if (isset($_POST['Passenger_CellPhone']))
		$phone = $_POST['Passenger_CellPhone'];
	else if (isset($_POST['Passenger_DayPhone']))
		$phone = $_POST['Passenger_DayPhone'];
	else if (isset($_POST['Passenger_EveningPhone']))
		$phone = $_POST['Passenger_EveningPhone'];
	else if (isset($_POST['Pickup_Address_PhoneNo']))
		$phone = $_POST['Pickup_Address_PhoneNo'];
	else
		$phone = '';

	
	$creditcard = isset($_POST['Payment_CC_Number']) ? $_POST['Payment_CC_Number'] : '';	
	$exp = isset($_POST['Payment_CC_ExpDate']) ? $_POST['Payment_CC_ExpDate'] : '';	
	//the expiration data comes as MM/DD/YYYY
	$expArray = explode('/', $exp);
	
	$lastFour = substr($creditcard, -4);
	$expDate = $expArray[2] . '-' . $expArray[0];	
	
	//try to find the paymentProfileId
	//for this credit card for this passenger
	$paymentProfileId = $r->get_payment_profile_id($memberid, $lastFour, $expDate);

	if ($_POST['Payment_Method'] == 'DB')
		$paymentProfileId = '0';
	else if(!$paymentProfileId) {
		$cc_values['billAddress'] = "";
		$cc_values['billZip'] = "";
		$cc_values['billCardNumber'] = $creditcard;
		$cc_values['billExpDate'] = $expDate;
		$cc_values['description'] = "TravelAgent";
		$cc_values['defaultCard'] = 0;
		$paymentProfileId = createPaymentProfile($cc_values, $memberid, 'testMode');
	}
	//if the credit card for the passenger can't be found or created, fail
	if($paymentProfileId == "Fail") {
		kill('Invalid payment information', $dcode, 'XXXXXX');
	}

	//set the lastFour and paymentProfileId in the summary string	

	/*
	$summarr = array($_POST['Passenger_FirstName']." ".$_POST['Passenger_LastName'],
		$phone,
		$cc,
		'',
		'',
		'X' . $lastFour,
		$paymentProfileId,
		$dump,
		$_POST['Passenger_EmailAddress']);
	*/

	if ($paymentProfileId) {
		$dump .= "_PPID_".$paymentProfileId."_PPID_";
		$dump .= "_LAST4_X".$lastFour."_LAST4_END";
	}

	$summarr = array($_POST['Passenger_FirstName']." ".$_POST['Passenger_LastName'],
		$phone,
		$cc,
		'',
		'',
		$creditcard,
		$expDate,
		$dump,
		$_POST['Passenger_EmailAddress']);

	$summary = implode('GROUP_DEL', $summarr);
	return $summary;
}

function kill($reason = '', $code = '', $resid = '') {
	global $pnr, $path;
	if ($code)
		echo $code."\n".$resid."\n".$reason;
	else
		echo $reason;
	mail_admins('FAILURE: '.$reason);

	if ($pnr && file_exists($path)) unlink($path);

	die;
}
function get_apts($apt, $memberid) {
	$query = "select machid from resources where machid like 'airport%'";
	$qresult = mysql_query($query);
	$a = array();
	while ($row = mysql_fetch_assoc($qresult)){
		list($x, $acode) = explode('-', $row['machid']);
		if ($acode==$apt) {
			//make sure the member has permissions for the airport
			insert_airport($row['machid'], $memberid);
			return $row['machid'];
		}
	}
	return false;
}

function insert_airport($airportid, $memberid) {
	//check to see if the airport is mapped to the new member
	$query = "select machid from permissions where machid = '" . $machid .
		 "' and memberid = '" . $memberid . "'";
	
	$qresult = mysql_query($query);

	if ($qresult && mysql_num_rows($qresult))
		$row = mysql_fetch_assoc($qresult);
	//if the query returned nothing, insert the mapping
	if (!isset($row['machid'])) {
		$insertquery = "insert into permission (machid, memberid) values ('" . $airportid . "', '" . $memberid . "')";
		$newqresult = mysql_query($insertquery);
	}
}
			
/*
* Return entire station loc, need zip for quote
*/
function get_station($station) {
	$search = "station-".$station;
	$query = "select * from resources where machid='$search'";
	$qresult = mysql_query($query);

	if ($qresult && mysql_num_rows($qresult)) {
		$row = mysql_fetch_assoc($qresult);
		return $row;
	}
	return false;
}
function okState($state) {
	if (!$state) return false;
	$state = strtoupper($state);
	$valid = false;
	$okstates = array('MA', 'NH', 'VT', 'ME', 'CT', 'RI', 'CA');
	foreach ($okstates as $v) {
		if ($state == $v) $valid = true;
	}
	return $valid;
}
/*
* do_fromloc() and do_toloc():
* Check if the location exists. Return the machid if it does.
* If it doesn't, create one and return that machid instead.
*/
function do_fromloc($memberid, $scheduleid) {
	$type = $_POST['Pickup_LocationTypeCode'];
    	//$station = $type == 'T' ? $_POST['Pickup_Train_StationCode'] : null;

	//if ($type == 'T' && $station) $id = get_station($station);
	//if ($id) return $id;
	
	$rs = array(
		'city' => 	$_POST['Pickup_Address_City'],
   	 	'rphone' => 	$_POST['Pickup_Address_PhoneNo'],
    		'zip' =>	$_POST['Pickup_Address_PostalCode'],
    		'state' => 	$_POST['Pickup_Address_State'],
    		'name' =>	''
	);
    	$street = 	$_POST['Pickup_Address_StreetName'];
    	$streetno = 	$_POST['Pickup_Address_StreetNo'];
	$rs['address1'] = "$streetno $street";
	//if ((!$streetno && !$street) || $_POST['Pickup_LocationTypeCode']=='T')
	if ($_POST['Pickup_LocationName'])
    		$rs['name'] = $_POST['Pickup_LocationName'];
	else
		$rs['name'] = $rs['address1'];

	$query = "select machid from resources where
		  scheduleid='$scheduleid' and
		  address1='{$rs['address1']}' and
		  city='{$rs['city']}' and
		  state='{$rs['state']}' and
		  zip='{$rs['zip']}'";
	$qresult = mysql_query($query);
	if ($qresult && mysql_num_rows($qresult)!=0) {
		$row = mysql_fetch_assoc($qresult);
		return $row['machid'];	  	
	}
	return insert_loc($rs, $memberid, $scheduleid, $type, $station);	
}
function do_toloc($memberid, $scheduleid) {
	$type = $_POST['Dropoff_LocationTypeCode'];
    	//$station = $type == 'T' ? $_POST['Dropoff_Train_StationCode'] : null;
	if (isset($_POST['Dropoff_AsDirected'])&&$_POST['Dropoff_AsDirected']=='True')
		return 'asDirectedLoc';
	//if ($type == 'T' && $station) $id = get_station($station);
	//if ($id) return $id;

	$rs = array(
		'city' => 	$_POST['Dropoff_Address_City'],
   	 	'rphone' => 	$_POST['Dropoff_Address_PhoneNo'],
    		'zip' =>	$_POST['Dropoff_Address_PostalCode'],
    		'state' => 	$_POST['Dropoff_Address_State'],
    		'name' =>	''
	);
    	$street = 	$_POST['Dropoff_Address_StreetName'];
    	$streetno = 	$_POST['Dropoff_Address_StreetNo'];
	$rs['address1'] = "$streetno $street";
	//if ((!$streetno && !$street) || $_POST['Dropoff_LocationTypeCode']=='T')
	if ($_POST['Dropoff_LocationName'])
    		$rs['name'] = $_POST['Dropoff_LocationName'];
	else
		$rs['name'] = $rs['address1'];


	$query = "select machid from resources where
		  scheduleid='$scheduleid' and
		  address1='{$rs['address1']}' and
		  city='{$rs['city']}' and
		  state='{$rs['state']}' and
		  zip='{$rs['zip']}'";
	$qresult = mysql_query($query);
	if ($qresult && mysql_num_rows($qresult)!=0) {
		$row = mysql_fetch_assoc($qresult);
		return $row['machid'];	  	
	}
	return insert_loc($rs, $memberid, $scheduleid, $type, $station);	
}
function insert_loc($rs, $memberid, $scheduleid, $type, $station = null) {
	$db = new AdminDB();
	$rs['scheduleid'] = $scheduleid;
	$rs['location'] = $rs['address1']." ".$rs['address2'].", ".$rs['city'].", ".$rs['state']." ".$rs['zip'];
	$rs['notes'] = '';
	$rs['minRes'] = 0;
	$rs['maxRes'] = 0;
	$rs['lat'] = null;
	$rs['lon'] = null;
	if ($type == 'T') {
		$rs['name'] = 'Train Station: '. $station;
		$rs['address1'] = $station;
		$rs['zip'] = null;
	}

	$id = $db->add_resource($rs, $memberid);
	return $id;
}
function insert_attrs($id) {
	$attrs = get_attrids();
	$glue = array();

	foreach ($_POST as $k => $v) {
		foreach($attrs as $ak => $av) {
			if ($k == $av) {
				$glue[] = "('$id', $ak, '$v')"; 
			}
		}
	}
	$qstr = join(', ', $glue);

	$query = "insert into attributes values $qstr";
	$qresult = mysql_query($query);
}
function get_attrids() {
	$return = array();
	$query = "select attrid, attr_name from attr_types";
	$qresult = mysql_query($query);
	while($row = mysql_fetch_assoc($qresult)) {
		$return[$row['attrid']] = $row['attr_name'];
	}
	return $return;
}

function get_company($userdata) {
	//$query = "select l.memberid, l.lname, s.scheduleid
	//	  from login l join schedules s on l.email=s.scheduleTitle
	//	  where l.fname='Saturn'";
	//$qresult = mysql_query($query);

	//$transponet = 'glb462e73465eb6b';
	//$memberid = $transponet;
	$return = array();
	
	//get the "company id" from the Transponet Transaction request
	$cid = $_POST['Accounting_CorporateID'];
	
	//get the PNR from the Transponet Transaction request
	$pid = $_POST['Passenger_PNR'];

	//map the "company id" to a billing group and default user
	//TODO:  this should move to a database table
	if ($cid == 'BIOGEN' || $cid == '22') {
		$return['memberid'] = 'glb46e7fa6552ba5';
		$return['scheduleid'] = 'glb46b88119123fe'; //'Biogen'
		$return['groupid'] = 22;
	} else if ($cid == 'GENZYME') {
		$return['memberid'] = 'glb44bb8fd76444c';
		$return['scheduleid'] = 'glb44bb8fd76dde4';//'Genzyme'
		$return['groupid'] = 1;
	 } else if ($cid == 'BAIN38') {
		$return['memberid'] = 'glb4637557a7cc1e';	
		$return['scheduleid'] = 'glb4637557a7d7d2';//'Bain'
		$return['groupid'] = 38;
	} else if ($cid == 'PACKARD1049') {
		$return['memberid'] = 'glb463755e1ce602';
		$return['scheduleid'] = 'glb463755e1d247f';//'Packard'
		$return['groupid'] = 45;
	} else if ($cid == '61') {
		$return['memberid'] = 'glb469e26423555a';
		$return['scheduleid'] = 'glb469e26423804f';//'Novartis'
		$return['groupid'] = 30;
	} else if ($cid == '63') {
		$return['memberid'] = 'glb469fd584260f3';
		$return['scheduleid'] = 'glb469fd58426caa';//'Millipore'
		$return['groupid'] = 63;
	} else if ($cid == '1108') {
		$return['memberid'] = 'glb469fdb660c8b5';
		$return['scheduleid'] = 'glb469fdb660f793';//'Navigant'
		$return['groupid'] = 1108;
	} else if ($cid == '2') {
		$return['memberid'] = 'glb46b325ee3b311';
		$return['scheduleid'] = 'glb46b325ee4532e';//'Vertex'
		$return['groupid'] = 2;
	} else if ($cid == '34') {
		$return['memberid'] = 'glb46e7fae87a0ea';
		$return['scheduleid'] = 'glb46e7fae88354f';//'Gap'
		$return['groupid'] = 34;
	} else if ($cid == 'REARDENSMB') {
		$return['memberid'] = 'glb47b606e6ae165';
		$return['scheduleid'] = 'glb47b606e6b3b37';//'Rearden'
		$return['groupid'] = 1293;
	} else if ($cid == '1379') {
		$return['memberid'] = 'glb490a66bbdad2d'; //CRAI
		$return['scheduleid'] = 'glb490a66bbe12b6';
		$return['groupid'] = 1379;
	} else if ($cid == 'Roche Palo Alto') {
		$return['memberid'] = 'glb49371087ecc4e';//Roche
		$return['scheduleid'] = 'glb49371088016a4';
		$return['groupid'] = 1010;
	} else if ($cid == '1073') {
		$return['memberid'] = 'glb4941d0b948d42';//Adobe 1073
		$return['scheduleid'] = 'glb4941d0b95064f';
		$return['groupid'] = 1073;
	} else if ($cid == '1397') {
		$return['memberid'] = 'glb4941d5f095f67';// Cubist
		$return['scheduleid'] = 'glb4941d5f09b935';
		$return['groupid'] = 1397;
	} else if ($cid == '1374') {
		$return['memberid'] = 'glb4941d31d2fe8b';//Millenium
		$return['scheduleid'] = 'glb4941d31d36029';
		$return['groupid'] = 1374;
	} else if ($cid == '1000') {
		$return['memberid'] = 'glb4970ded44ea88';  //Biomed
		$return['scheduleid'] = 'glb4970ded463e43';
		$return['groupid'] = 1000;
	} else if ($cid == '1427') {
		$return['memberid'] = 'glb4993448c75d2a'; // Carlson
		$return['scheduleid'] = 'glb4993448c7c692';
		$return['groupid'] = 1427;
	} else if ($cid == '1531') {
		$return['memberid'] = 'glb4a958c54d87ed'; // Golden gate
		$return['scheduleid'] = 'glb4a958c54d9b71';
		$return['groupid'] = 1531;
	} else if ($cid == '1428') {
		$return['memberid'] = 'glb4aea037f1f9dc'; // MIT
		$return['scheduleid'] = 'glb4aea037f28290';
		$return['groupid'] = 1428;
	} else if ($cid == '1606') {
		$return['memberid'] = 'glb4bbe03ff66bad'; //polycom
		$return['scheduleid'] = 'glb4bbe03ff6fc2c';
		$return['groupid'] = 1606;
	} else if ($cid == '1396') {
		$return['memberid'] = 'glb4bc60437b1493'; //Mckinsey
		$return['scheduleid'] = 'glb4bc60437b5ae0';
		$return['groupid'] = 1396;
	} else if ($cid == '1631') {
		$return['memberid'] = 'glb4c769ef14714b'; //Autodesk
		$return['scheduleid'] = 'glb4c769ef14f235';
		$return['groupid'] = 1631;
	} else {
		// Default to Transponet user
		// live
		$return['memberid'] = 'glb46f43f55381db';
		$return['scheduleid'] = 'glb46f43f555fa81';
		$return['groupid'] = 0;
	}

	//if the PNR was set, find the appropriate memberid and scheduleid
	if(isset($pid)) {
		$return = $r->get_individual_user($pid, $return['groupid'], $userdata);
	}
	
		// venus
		//$return['memberid'] = 'glb462e73465eb6b';
		//$return['scheduleid'] = 'glb462e734663950';
		return $return;

}

?>
