<?php
/*
* Functions for the Transponet interface
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');
include_once('Reservation.class.php');
include_once('Auth.class.php');
include_once(BASE_DIR . '/lib.php');

class Transponet extends Reservation {

	function add_gt3_res($machid, $toLocation, $memberid, $start, $end, $repeat, $date, $min, $max, $summary = null, $flightDets = null, $checkBags, $scheduleid, $specialItems) {
		$this->machid	= $machid;
		$this->toLocation = $toLocation;
		$this->memberid = $memberid;
		$this->start	= $start;
		$this->end	= $end;
		$this->repeat 	= $repeat;
		$dates_info = split('/', $date);
		//$this->date = mktime(0,0,0,$dates_info[0], $dates_info[1], $dates_info[2]);
		
		$fixdate = mktime(0,0,0,$dates_info[0], $dates_info[1], $dates_info[2]);
		$fixdate -= $fixdate % 100;
		$this->date = $fixdate;
		$this->type     = 'a';
		$this->summary	= $summary;
		$this->flightDets = $flightDets;
		$this->checkBags = $checkBags;
		$this->scheduleid = $scheduleid;
		$this->specialItems = $specialItems; // add to function definition
		//in submitTransaction, we put the paymentProfileId in the
		//summary string
		$summaryArray = explode('GROUP_DEL', $summary);		
		$this->paymentProfileId = $summaryArray[6];

		if (strpos($summary, '_PPID_') !== false) {
			list($discard1, $ppid, $discard2) = explode('_PPID_', $summary);
			$this->paymentProfileId = $ppid;
		}

	
		$dates = array();
		$tmp_valid = false;

		if (!$this->is_blackout) {
			$user = new User($this->memberid);		// Set up a new User object
		}
		// Check time
		$this->check_times();

		// Check payment info
		//$this->check_cc($user);
	
		if ($this->has_errors()) {
			return 0;	
		}
			//$this->print_all_errors(true);

		$is_parent = $this->is_repeat;		// First valid reservation will be parentid (no parentid if solo reservation)

		for ($i = 0; $i < count($repeat); $i++) {
			//$this->date = $repeat[$i];
			if ($i == 0) $tmp_date = $this->date;	// Store the first date to use in the email
			$is_valid = $this->check_res();

			if ($is_valid) {
				$tmp_valid = true;							// Only one recurring needs to work
				$this->id = $this->db->add_res($this, $is_parent);
				if (!$is_parent) {
					array_push($dates, $this->date);		// Add recurring dates (first date isnt recurring)
				}
				else {
					$this->parentid = $this->id;			// The first reservation is the parent id
				}
				CmnFns::write_log($this->word . ' ' . $this->id . ' added.  machid:' . $this->machid .', date:' . $this->date . ', start:' . $this->start . ', end:' . $this->end, $this->memberid, $_SERVER['REMOTE_ADDR']);
			}
			$is_parent = false;							// Parent has already been stored
		}

		if ($this->has_errors()) {
			return 0;
		}
			//$this->print_all_errors(!$this->is_repeat);

		$this->date = $tmp_date;	
		if ($this->is_repeat) array_unshift($dates, $this->date);		// Add to list of successful dates

		sort($dates);

		if (!$this->is_blackout) {		// Notify the user if they want (only 1 email)
			$this->sched = $this->db->get_schedule_data($this->scheduleid);
			$this->send_email('e_add', $user, $dates);
		}

		if (!$this->is_repeat || $tmp_valid) {
			$showid = strtoupper(substr($this->id, -6));
			return $showid; 
		}
			//$this->print_success('created', $dates);
	}


	function mod_gt3_res($resid, $fromLoc, $toLoc, $date, $start, $end, $del, $min, $max, $mod_recur, $summary = null, $flightDets = null, $checkBags, $specialItems) {
		if (!$this->db->check_disp_state($resid))
			return 0;
		$this->id = $resid;
		$recurs = array();

		$this->load_by_id();			// Load reservation data
		$this->machid = $fromLoc;
		$this->toLocation = $toLoc;
		$this->type = 'm';
		$this->summary = $summary;
		$this->flightDets = $flightDets;
		$this->checkBags = $checkBags;
		$dates_info = split('/', $date);
		//$this->date = mktime(0,0,0,$dates_info[0], $dates_info[1], $dates_info[2]);
		$fixdate = mktime(0,0,0,$dates_info[0], $dates_info[1], $dates_info[2]);
		$fixdate -= $fixdate % 100;
		$this->date = $fixdate;
		$this->start = $start;			// Assign new start and end times
		$this->end	 = $end;
		$this->specialItems = $specialItems;
		
		//in submitTransaction, we put the paymentProfileId in the
		//summary string
		$summaryArray = explode('GROUP_DEL', $summary);		
		$this->paymentProfileId = $summaryArray[6];

		if (strpos($summary, '_PPID_') !== false) {
			list($discard1, $ppid, $discard2) = explode('_PPID_', $summary);
			$this->paymentProfileId = $ppid;
		}

		if ($del) {						// First, check if this should be deleted
			$this->del_res($mod_recur, mktime(0,0,0));
			return;
		}

		if (!$this->is_blackout) {
			$user = new User($this->memberid);		// Set up a User object
			//$this->check_perms($user);				// Check permissions
			//$this->check_min_max($min, $max);		// Check min/max reservation times
		}

		$this->check_times();			// Check valid times

		$this->is_repeat = $mod_recur;	// If the mod_recur flag is set, it must be a recurring reservation
		$dates = array();

		// First, modify the current reservation

		if ($this->has_errors())
			return 0;
			//$this->print_all_errors(true);

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

		if ($this->has_errors())
			return 0;
			//$this->print_all_errors(!$this->is_repeat);

		if (!$this->is_blackout) {		// Notify the user if they want
			$this->send_email('e_mod', $user);
		}

		if (!$this->is_repeat || $tmp_valid)
			return strtoupper(substr($this->id, -6));
			//$this->print_success('modified', $dates);
	}


	function del_gt3_res($resid, $del_recur = 0) {
		if (!$this->db->check_disp_state($resid))
			return 0;
		$db = new ResDB();
		$this->id = $resid;
		$this->load_by_id();
		$this->type = 'd';
		$this->check_times();
		if ($this->has_errors())
			return 0;
			//$this->print_all_errors(true);

		$this->is_repeat = $del_recur;

	$query = "insert into trip_log (resid, dispatch_status, pay_status,
		    pay_type, driver, vehicle, distance, base_fare, discount,
		    tolls, unpaid_tolls, other, total_fare, invoicee,
		    notes, last_mod_time)
		  values('$resid',9,28,30,1,4,0,0,0,0,0,'',0,'',NULL, NOW())
		  ON DUPLICATE KEY
		  update dispatch_status=9";
	$qresult = mysql_query($query);


	$tuser = 'glb46f43f55381db';
	$time = time();
	$query = "update reservations set modified=$time, is_blackout=1, moddedBy='$tuser' where resid='$resid'";
	$qresult = mysql_query($query);

	/************** Log delete **************************/
	$this->db->log_action($resid, 'd');

		//$this->db->del_res($this->id, $this->parentid, $del_recur, mktime(0,0,0));

		$user = new User($this->memberid);		// Set up User object

		if (!$this->is_blackout)		// Mail the user if they want to be notified
			$this->send_email('e_del', $user);

		CmnFns::write_log($this->word . ' ' . $this->id . ' deleted.', $this->memberid, $_SERVER['REMOTE_ADDR']);
		if ($this->is_repeat)
			CmnFns::write_log('All ' . $this->word . 's associated with ' . $this->id . ' (having parentid ' . $this->parentid . ') were also deleted', $this->memberid, $_SERVER['REMOTE_ADDR']);
		return strtoupper(substr($this->id, -6));
		//$this->print_success('deleted');
	}


	function get_quote($groupid = 0, $frmStationZip = null, $toStationZip = null) {
		$discount = $this->db->getDiscount($groupid);
		$hourly = isset($_POST['Ride_HourlyRatesOnly']) && $_POST['Ride_HourlyRatesOnly'] == 'True' ? true : false;
		$carfee = isset($_POST['Ride_VehicleClass']) && $_POST['Ride_VehicleClass'] == 'SUV' ? 30 : 0;
		$hourlyFee = 60;
		$hourlyFee += $carfee;

		$fromAddr = $_POST['Pickup_Address_StreetNo']." ".$_POST['Pickup_Address_StreetName'];
		$fromCity = $_POST['Pickup_Address_City'];
		$fromState = $_POST['Pickup_Address_State'];
		$fromZip = $_POST['Pickup_Address_PostalCode'];
		$toAddr = $_POST['Dropoff_Address_StreetNo']." ".$_POST['Dropoff_Address_StreetName'];
		$toCity = $_POST['Dropoff_Address_City'];
		$toState = $_POST['Dropoff_Address_State'];
		$toZip = $_POST['Dropoff_Address_PostalCode'];
	
		$fromApt = $toApt = false;	
		if ($_POST['Pickup_LocationTypeCode']=='A') {
			$airport = $_POST['Pickup_Flight_AirportCode'];
			$fromApt = true;
		} else if ($_POST['Dropoff_LocationTypeCode']=='A') {
			$airport = $_POST['Dropoff_Flight_AirportCode'];
			$toApt = true;
		} else
			$airport = 'P2P';

		trim($fromAddr);
		trim($fromCity);
		trim($fromState);
		trim($fromZip);
		trim($toAddr);
		trim($toCity);
		trim($toState);
		trim($toZip);

		$from = $fromZip;
		$to = $toZip;

		if ($frmStationZip) $from = $frmStationZip;
		if ($toStationZip) $to = $toStationZip;

		if ($fromApt) $from = $airport; 
		else if ($toApt) $to = $airport; 

		$from = escapeshellarg($from);
		$to = escapeshellarg($to);

		$wrapper = "wrapper.pl";
		exec("perl $wrapper $from $to", $a, $b);
		$fare = $a[0];


		$return = array();
		$return['discount'] = $discount;
		$return['toState'] = $toState;
		$return['fromState'] = $fromState;

		if (!$fare || $_POST['Dropoff_AsDirected']=='Y' || $hourly) {
			$return['type'] = "hourly"; 
			$return['price'] = round($hourlyFee - $hourlyFee * $discount);
		} else {
			$return['type'] = 'direct';	
			$fare += $carfee;
			$fare -= round($fare * $discount);
			$return['price'] = $fare + 5; 	
		}
		return $return;
	}
      
	function get_user_from_pid($pid, $groupid) {
		//see if the user can be found by PNR
	  	$query = "select im.ptMemberId as memberid, " .
		 "s.scheduleid as scheduleid from " .
                 "integration_mapping im, schedules s " .
                 "where s.scheduleTitle = im.ptMemberId and " .
                 "externalSystemId = 'Transponet' and " .
                 "ptBillingGroupId = " . $groupid . " and " .
                 "externalSystemUserId = '" . $pid . "'";
	
        	$qresult = mysql_query($query);

		$row = array();
		
		if ($qresult && mysql_num_rows($qresult))
			$row = mysql_fetch_assoc($qresult);
		
		$returnuser['memberid'] = $row['memberid'];
		$returnuser['scheduleid'] = $row['scheduleid'];
		$returnuser['groupid'] = $groupid;
		return $returnuser;
	}
	
	function get_individual_user($pid, $groupid, $userdata) {
		//see if the user has already been processed
		$returnuser = $this->get_user_from_pid($pid, $groupid);
	
		//user not processed through Transponet before
		if(!isset($returnuser['memberid'])) {
			//need to see if the user exists by email 
			//or create the user
			$auth = new Auth();
			if ($userdata['emailaddress'])
				$memberid = $auth->db->userExists($userdata['emailaddress']);
			if(!$memberid) {
				//need to create a user from scratch
				//insert into the login table

				$userdata['groupid'] = $groupid;

				$memberid =
					$auth->db->insertMember($userdata);
				//insert into the schedule table
				$schedDB = new AdminDB();
				$sched_data = array();
				$sched_data['scheduleTitle'] = $memberid;
                		$sched_data['adminEmail'] =
					"reservations@planettran.com";
                		$sid = $schedDB->add_schedule($sched_data);
				$auth->db->assign_user_schedule($sid, $memberid);			}
			$this->insert_individual_user($pid, $memberid, 
						$groupid);
			//should be able to query the 
			//integration_mapping table now
			$returnuser = $this->get_user_from_pid($pid, $groupid);
		}
		
		return $returnuser;			
	}

	function insert_individual_user($pid, $memberid, $groupid) {
		$query = "insert into integration_mapping (" .
				"ptMemberId, ptBillingGroupId," .
				"externalSystemId,externalSystemUserId) " .
				"values(" .
				"'" . $memberid . "', " .
				"'" . $groupid . "', " .
				"'Transponet', " .
				"'" . $pid . "')";
        	$qresult = mysql_query($query);
	}

	function get_payment_profile_id($memberid, $lastFour, $expDate) {
		 			
		$vals = array($memberid, $lastFour, $expDate);
	
                $query = "select paymentProfileId
                          from paymentProfiles
                          where memberid= '" . $memberid . "' " . "
                          and status='active'
			  and lastFour = '" . $lastFour . "' and expDate = '" . $expDate . "'";
        	$qresult = mysql_query($query);
	
		$row = array();

		if ($qresult && mysql_num_rows($qresult))
			$row = mysql_fetch_assoc($qresult);

		return $row['paymentProfileId'];
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
	$pid = $_POST['Passenger_PNR'] ? $_POST['Passenger_PNR'] : null;

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

	//if the PNR was set, fine the appropriate memberid and scheduleid
	if(isset($pid)) {
		$return = $this->get_individual_user($pid, $return['groupid'], $userdata);
	} else {

		$newpnr = strtoupper(uniqid());
		$newpnr = substr($newpnr, -6);

		$umbrella_memberid = $return['memberid'];
		$return = $this->get_individual_user($newpnr, $return['groupid'], $userdata);
		$return['umbrella_memberid'] = $umbrella_memberid;
	}
	
		// venus
		//$return['memberid'] = 'glb462e73465eb6b';
		//$return['scheduleid'] = 'glb462e734663950';
		return $return;

}
	function get_test_post_info() {
		$time = rand();
		$t = "55";

		$postinfo['txnTypeCode'] = "NR";
		//$postinfo['txnTypeCode'] = "XL";

		$postinfo['transactionId'] = $time;
		$postinfo['tNResNo'] = $time + 10;
		//$postinfo['tNResNo'] = 1230228333;

		$postinfo['txnDateTime'] = "Thu Feb 03 17:46:03 MSK 2011";
		$postinfo['txnSentBy'] = "SATU8000";
		$postinfo['txnSentTo'] = "PLAN1666";
		$postinfo['bookerMemberCode'] = "SATU8000";
		$postinfo['bookerResNo'] = "8126934";
		$postinfo['providerMemberCode'] = "PLAN1665";
		$postinfo['providerResNo'] = "";
		$postinfo['Accounting_CorporateID'] = "GENZYME";
		$postinfo['Accounting_UDF_Desc_1'] = "CustomerAcct";
		$postinfo['Accounting_UDF_Desc_2'] = "EmployeeID";
		$postinfo['Accounting_UDF_Desc_3'] = "CostCenter";
		$postinfo['Accounting_UDF_Desc_4'] = "DeptCode";
		$postinfo['Accounting_UDF_Value_1'] = "2";
		$postinfo['Accounting_UDF_Value_2'] = "CONFER";
		$postinfo['Accounting_UDF_Value_3'] = "5130";
		$postinfo['Accounting_UDF_Value_4'] = "AA999999";
		$postinfo['Booker_ExternalBookingSystem'] = "SABRE";
		$postinfo['Booker_IATA'] = "22518952";
		$postinfo['Caller_Name'] = "STEVE OYANGEN";
		$postinfo['Dropoff_Address_CountryCode'] = "US";
		$postinfo['Dropoff_Address_PhoneNo'] = "617-733-9221";
		$postinfo['Dropoff_Address_PostalCode'] = "02118";
		$postinfo['Dropoff_Address_City'] = "Boston";
		$postinfo['Dropoff_Address_State'] = "MA";
		$postinfo['Dropoff_Address_StreetName'] = "DWIGHT ST 1";
		$postinfo['Dropoff_Address_StreetNo'] = "38";
		$postinfo['Dropoff_AsDirected'] = "False";
		$postinfo['Dropoff_LocationName'] = "RESIDENCE";
		$postinfo['Passenger_CellPhone'] = "857-928-6763";
		$postinfo['Passenger_DayPhone'] = "617-444-7643";
		$postinfo['Passenger_EmailAddress'] = "testuser$t@planettran.com";
		$postinfo['Passenger_EveningPhone'] = "617-733-9221";
		$postinfo['Passenger_FirstName'] = "Reservation";
		$postinfo['Passenger_LastName'] = "Test$t";
		$postinfo['Passenger_PNR'] = "PNRTST$t";
		$postinfo['Payment_CC_ExpDate'] = "12/31/2013";
		//$postinfo['Payment_CC_Number'] = "379663306901008";
		$postinfo['Payment_CC_Number'] = "4147098020095907";
		//$postinfo['Payment_CC_Number'] = "1111111111111111";
		$postinfo['Payment_Method'] = "VI";
		//$postinfo['Payment_Method'] = "DB";
		$postinfo['Pickup_Address_City'] = "Boston";
		$postinfo['Pickup_Address_State'] = "MA";

		$postinfo['Dropoff_LocationTypeCode'] = "O";
		$postinfo['Pickup_LocationTypeCode'] = "T";
		$postinfo['Pickup_Train_StationCode'] = "BOS";

		$postinfo['Pickup_DateTime'] = "04/26/2011 04:26";
		//$postinfo['Pickup_Flight_AirlineCode'] = "AA";
		//$postinfo['Pickup_Flight_AirportCode'] = "BOS";
		//$postinfo['Pickup_Flight_ArrivalTime'] = "16:05";
		//$postinfo['Pickup_Flight_FlightNumber'] = "2340";
		//$postinfo['Pickup_Flight_OriginAirport'] = "ORD";
		//$postinfo['Pickup_Flight_TypeCode'] = "D";
		$postinfo['Pickup_MeetAndGreet'] = "False";
		$postinfo['Pricing_RateType'] = "H";
		$postinfo['Ride_EstimatedMileage'] = "5";
		$postinfo['Ride_MileageType'] = "M";

		
		return $postinfo;
	}

}
?>
