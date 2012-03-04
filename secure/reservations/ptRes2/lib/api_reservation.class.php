<?php
@define('BASE_DIR', dirname(__FILE__) . '/..');
include_once(BASE_DIR . '/lib/Reservation.class.php');

class api_reservation extends Reservation {
	// Required values
	//var $userName	= null;
	//var $userType		= null;
	var $fromlocationid	= null;
	var $tolocationid		= null;
	var $timestamp		= null;
	var $startTime;
	var $endTime;

	// Other values
	var $email		= null;
	var $err		= null;
	var $fare		= null;
	var $resDB		= null;
	var $db			= null;
	var $memberid		= null;
	var $discount		= null;
	var $flightdets	= null;
	

	function api_reservation() {
		$resDB 		= new ResDB();
		$this->db 	= $resDB->db;
		$this->resDB 	= $resDB;
	}

	function set_date_and_time() {
		$d = date("j", $this->timestamp);
		$m = date("n", $this->timestamp);
		$y = date("Y", $this->timestamp);

		$date = mktime(0,0,0,$m, $d, $y);

		$hours = date("G", $this->timestamp);
		$mins = date("i", $this->timestamp);

		$startTime = $hours * 60;
		$startTime += ($mins + 30) - ($mins % 15);
		$endTime = $startTime + 15;

		$this->date = $date;
		$this->startTime = $startTime;
		$this->endTime = $endTime;

		if (!$this->date || !$this->timestamp) {
			$this->err = "The timestamp sent was empty or invalid.";
			$this->print_failure();
		}
	}
	/*
	* Get and set user info in our object
	* SET:
	*	-memberid
	*	-scheduleid
	*	-email
	*
	* Return: array of login info and scheduleid
	*/
	function get_user_info() {
		$vals = array($this->memberid);
		$where = "where l.memberid=?";
		$query = "select l.fname, l.lname, l.email,
			 	 l.phone, s.scheduleid, b.discount 
				 from login l join schedules s on l.memberid=s.scheduleTitle
				 left join billing_groups b on b.groupid=l.groupid
				 $where";
		$result = $this->db->getRow($query, $vals);
		
		if (empty($result)) {
			$this->err = "Unknown user.";
			$this->print_failure();
		}
		//$this->memberid 	= $result['memberid'];
		$this->scheduleid 	= $result['scheduleid'];
		$this->email 		= $result['email'];
		$this->phone		= $result['phone'];
		$this->discount		= $result['discount'];
		return $result;
	}

	/*
	* This will set all default reservation values EXCEPT:
	*	-machid
	*	-toLocation
	*
	*	memberid and scheduleid are set in get_user_info, which is
	*	called first.
	*
	*	Machid and toLocation are set in the location functions, which
	*	are called first.
	*
	* Return an array ready to be inserted into temp_reservations
	*/
	function set_default_res_values() {
		$this->resid		= uniqid('api');
		$this->id 		= $this->resid;
		$this->machid = (isset($this->fromlocationid)) ? $this->fromlocationid : $_POST['fromlocationid'] ;
		$this->is_blackout 	= 	0;
		$this->created 		= time();
		$this->modified 	= null;
		$this->moddedBy 	= null;
		$this->parentid		= $this->resid;
		$this->is_blackout	= 0;
		$this->summary		= '';
		$this->flightDets	= null;
		$this->checkBags	= ((isset($_POST['checkBags']))? $_POST['checkBags'] : (isset($_POST['checkbags'])) ?$_POST['checkbags'] : 0);
		$this->special_items	= null; // special last minute p/u code?
		$this->createdBy	= $this->memberid;		
		$this->moddedBy		= null;
		$this->coupon_code	= null;
		$this->linkid		= null;
		$this->mtype		= 'o';
		$this->hour_estimate	= null;
		$this->origin		= 'i';
		$this->paymentProfileId = null;
        

        if (isset($_POST['flightdetails'])){
          $this->flightDets = "{`}" . $_POST['flightdetails'];
        }
        if (isset($_POST['flightnumber'])){
           $this->flightDets = "{`}" . $_POST['flightnumber']  . $this->flightDets;
        }
        if (isset($_POST['airline'])){
            $this->flightDets =  $_POST['airline']  . $this->flightDets;
        }



		$res = array(
			'resid'		=> $this->resid,
			'machid'	=> $this->machid,
			'toLocation'	=> $this->toLocation,
			'memberid'	=> $this->memberid,
			'scheduleid'	=> $this->scheduleid,
			'date'		=> $this->date,
			'startTime'	=> $this->startTime,
			'endTime'	=> $this->endTime,
			'created'	=> $this->created,
			'modified'	=> $this->modified,
			'parentid'	=> $this->parentid,
			'is_blackout'	=> $this->is_blackout,
			'summary'	=> $this->summary,			
			'flightDets'	=> $this->flightDets,
			'checkBags'	=> $this->checkBags,
			'special_items'	=> $this->special_items,
			'createdBy'	=> $this->createdBy,
			'moddedBy'	=> $this->moddedBy,
			'coupon_code'	=> $this->coupon_code,
			'linkid'	=> $this->linkid,
			'mtype'		=> $this->mtype,
			'hour_estimate'	=> $this->hour_estimate,
			'origin'	=> $this->origin,
			'paymentProfileId'=> $this->paymentProfileId,
            
		);
		return $res;
	}

	/*
	* Set default location values for a gps location
	* Return array that can be inserted whole into temp loc table
	*/
	// function get_gps_loc_values($loc, $source = 'api') {
		//scheduleid set in get_user_info()

		// if (strpos($loc['street_number'], '-') !== false) {
			// $parts = explode('-', $loc['street_number']);
			// $loc['street_number'] = $parts[0];
		// }

		// $name = $loc['street_number'].' '.$loc['route'];
		// $notes = "This address may not be exact. Be sure to call passenger 10 minutes ahead of time so that you can locate them.";
		// $location = $name.', ' .$loc['locality'].', '.$loc['state'].' '.$loc['postal_code'];
		// $prefix = 'glb';
		// if ($source == 'api')
			// $prefix = 'api';
		// else if ($source == 'hail')
			// $prefix = 'hai';

		// return array(
			// 'machid'=>	uniqid('api'),	
			// 'scheduleid'=>	$this->scheduleid,
			// 'name'=>	$name,
			// 'rphone'=>	$this->phone,
			// 'notes'=>	$notes,
			// 'location'=>	$location,
			// 'status'=>	'a',
			// 'minRes'=>	0,
			// 'maxRes'=>	0,
			// 'state'=>	$loc['state'],
			// 'zip'=>		$loc['postal_code'],
			// 'address1'=>	$name,
			// 'address2'=>	null,
			// 'city'=>	$loc['locality'],
			// 'lat'=>		$this->fromLat,
			// 'lon'=>		$this->fromLon,
			// 'groupid'=>	-1
			// );
	// }

	/*
	* get a location array based on the name of a location
	* array can be inserted whole into temp location table
	*/
	function get_loc_from_name($name) {
		$vals = array($this->scheduleid, $name);
		$query = "select * from resources where
			  scheduleid=? and name=?";
		$row = $this->db->getRow($query, $vals);
		if (!$row) 
			$this->err = "The location $name was not found.";

		if ($this->has_errs()){
                    $output['success']=false;
                    
                } 
			//$this->print_failure();

		//$this->toLocation = $row['machid'];
		return $row;
	}

	/*
	* insert location into temp location table
	*/
	function insert_temp_location($loc) {
		$query = "insert into temp_resources set ";
		$arr = array();
		$vals = array();

		foreach ($loc as $k=>$v) {
			$arr[] = "$k=?";
			$vals[] = $v;
		}
		$query .= implode(", ", $arr);
		$result = $this->db->query($query, $vals);
		DBEngine::check_for_error($result);
	}

	function insert_temp_reservation($res) {
		//var_dump($res);
            /*
		$query = "insert into temp_reservations set ";
		$arr = array();
		$vals = array();
		foreach ($res as $k=>$v) {
			$arr[] = "$k=?";
			$vals[] = $v;
		}
		$query .= implode(", ", $arr);
		$result = $this->db->query($query, $vals);
		DBEngine::check_for_error($result);
                */
                $queryFields = array();
		$vals = array();

		foreach($res as $k=>$v) {
			$queryFields[] = "$k=?";
			$vals[] = $v;
		}

        
		$query = "insert into temp_reservations set ".implode(", ", $queryFields);
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		
		DBEngine::check_for_error($result);
                
	}

    function update_reservation($res, $resid) {
	//	var_dump($res);
		$query = "update reservations set ";
		$arr = array();
		$vals = array();
		foreach ($res as $k=>$v) {
            
			$arr[] = "$k=?";
			$vals[] = $v;
		}
		$query .= implode(", ", $arr);
		$vals[] = $resid;
		$query .= " WHERE resid=?";
		//$result = $this->db->query($sessionid, $query, $vals);
		$result = $this->db->query($query, $vals);
		//CmnFns::diagnose($vals);
		DBEngine::check_for_error($result);
        return $result;
	}

	function delete_reservation($resid){

		$query = "DELETE FROM reservations WHERE resid='". $resid . "'";
		$qresult = mysql_query($query);	

		return $qresult;

	}

    function check_mod_vals($id, $res) {
          $fromlocationid = $tolocation = $timestamp = $flightinfo = $flightdets = $checkBags = array();



		if (isset($_POST['fromlocationid'])){

		    $fromlocationid = array('machid' => addslashes($_POST['fromlocationid']));
		}

		if (isset($_POST['timestamp'])){

			$this->timestamp = addslashes($_POST['timestamp']);
            
            $this->set_date_and_time();
            $timestamp =array('timestamp' => $this->timestamp);
		}

		if (isset($_POST['tolocationid'])){

			$tolocation = array('tolocation' => $_POST['tolocationid']);

		}
        if (isset($_POST['checkBags'])){

			$checkBags = array('checkBags' => $_POST['checkBags']);

		}else if (isset($_POST['checkbags'])){
            	$checkBags = array('checkBags' => $_POST['checkbags']);
        }

        //warn: the post values for $flightinfo must be in this order or they don't implode right.
        if (isset($_POST['airline'])){

			$flightinfo['airline'] = htmlspecialchars($_POST['airline']);

		}
        //warn: the post values for $flightinfo must be in this order or they don't implode right.
         if (isset($_POST['flightnumber'])){

			$flightinfo['flightnumber'] = htmlspecialchars($_POST['flightnumber']);

		}
        //warn: the post values for $flightinfo must be in this order or they don't implode right.
        if (isset($_POST['flightdetails'])){

			$flightinfo['flightdetails'] = htmlspecialchars($_POST['flightdetails']);

		}
 
        if(count($flightinfo)!=0)
        {
            $flightdets = array("flightdets" => implode("{`}",$flightinfo));
        }
        
        $return1 = array_merge((array)$fromlocationid, (array)$tolocation, (array)$timestamp, (array) $flightdets,(array) $checkBags);
		//adds member id to arrary

		//echo($id."<br/>");
        
		$this->memberid=$id;

		return $return1;

	}


        
	function check_post_vals($id) {
		// Values that are required no matter what
		$vals = array(
				'fromlocationid',
				'tolocationid',
				'paymentProfileId');
		foreach ($vals as $k=>$v) {
			if (!array_key_exists($v, $_POST)) {
				$this->err = "$v is required.";
			} else {
				$this->$v = addslashes($_POST[$v]);
			}
		}
		//assign from location
		if (!isset($_POST['timestamp'])){
			$date = new DateTime();
			$this->timestamp = $date->getTimestamp();
		} else {
			$this->timestamp = addslashes($_POST['timestamp']);
		}
		
		if (!isset($_POST['tolocationid'])){
			$this->toLocation = 'asDirectedLoc';
		} else {
			$this->toLocation = $_POST['tolocationid'];
		}
        if (isset($_POST['fromlocationid'])){
			$this->fromlocationid = $_POST['fromlocationid'];
		}
      





/*
		$this->fromlocationid = 'glb4b09a8c01daeb';
		$this->tolocationid = 'glb498f7a4496b68';
		$this->paymentProfileId = 20266794;
		$this->toLocation = 'glb498f7a4496b68';
*/


		//adds member id to arrary
		//echo($id."<br/>");
		$this->memberid=$id;
//		if ($this->has_errs()) 
//			$this->print_failure();
	}

	function check_noauth_post_vals($id) {		// Values that are required no matter what		$vals = array(				'fromlocationid',				'tolocationid',				'paymentProfileId');		foreach ($vals as $k=>$v) {			if (!array_key_exists($v, $_GET)) {				$this->err = "$v is required.";			} else {				$this->$v = addslashes($_GET[$v]);			}		}		//assign from location		$this->machid = $this->fromlocationid;				if (!isset($_GET['timestamp'])){			$date = new DateTime();			$this->timestamp = $date->getTimestamp();		} else {			$this->timestamp = addslashes($_GET['timestamp']);		}				if (!isset($_GET['tolocationid'])){			$this->toLocation = 'asDirectedLoc';		} else {			$this->toLocation = $_GET['tolocationid'];		}		//adds member id to arrary		//echo($id."<br/>");		$this->memberid=$id;		//if ($this->has_errs()) 			//$this->print_failure();	}        function check_mod_vals($id, $res) {
		$res = array();
		
                if (isset($_POST['fromlocationid'])){
			$this->fromLocation = addslashes($_POST['fromlocationid']);
                        $this->machid = addslashes($_POST['fromlocationid']);
			$res['resid'] = $this->resid;
			$res['machid'] = $this->machid;
                        //$res = array(
			//'resid'		=> $this->resid,
			//'machid'	=> $this->machid
                        //);
		}
		if (isset($_POST['timestamp'])){
			$this->timestamp = addslashes($_POST['timestamp']);
                        $this->set_date_and_time();

			$res['date'] = $this->date;
			$res['startTime'] = $this->startTime;
			$res['endTime'] = $res['startTime'] + 15;

                        //$res = array(
			//'date'		=> $this->date,
			//'startTime'	=> $this->startTime,
			//);
		}
		
		if (isset($_POST['tolocationid'])){
			$this->toLocation = $_POST['tolocationid'];
			$res['toLocation'] = $this->toLocation;
                        //$res = array(
			//'toLocation'	=> $this->toLocation
                        //);
		}
		
		//adds member id to arrary
		//echo($id."<br/>");
		$this->memberid=$id;
		
		return $res;                
	}
        
	function get_quote($from, $to) {
		//$fromloc = CmnFns::airport_or_zip($from['machid'], $from['zip']);
		//$toloc = CmnFns::airport_or_zip($to['machid'], $to['zip']);
		
		$fromZip = $from['zip'];
		$toZip = $to['zip'];
		
		if($toZip == '02128')
			$toZip = 'BOS';
		elseif($toZip == '03103')   
			$toZip = 'MHT';
		elseif($toZip == '94621')   
			$toZip = 'OAK';
		elseif($toZip == '94128')   
			$toZip = 'SFO';
		elseif($toZip == '95110')   
			$toZip = 'SJC';
		elseif($toZip == '02886')   
			$toZip = 'PVD';

		$wrapper = "/home/planet/www/secure/reservations/ptRes2/wrapper.pl";
		exec("perl $wrapper $fromZip $toZip 1", $a); //, $b);
		$fare = $a[0];
		if ($this->discount) 
			$fare -= $fare * ($this->discount / 100);
		$this->fare = $fare;
		return $fare;
	}
	
	function generate_location($addrID)
	{
		return trim($addrID['address1']). ' ' . trim($addrID['address2']) . ',' . trim($addrID['city']) . ',' . strtoupper(trim($addrID['state'])) . ',' . trim($addrID['zip']);   
	}

	function get_new_quote($from, $to) {

		$location1 = $this->generate_location($from);
		$location2 = $this->generate_location($to);
		
		$variable = 'address1=' . $location1 . '&address2=' . $location2 .'&address3=&wait_time=0&amenities=0,0,0&memberid=&groupid=&coupon=&origin=w&vehicle_type=P&trip_type=P&region=1';

			
		//$pl_str = '\home\planet\scripts\estimate.pl'.' '.EscapeShellArg($variable);
		
		$out = exec('perl /home/planet/scripts/estimate.pl'.' '.EscapeShellArg($variable));
		parse_str($out);

		//echo json_encode($pl_str);


		if(!isset($fare))
			return false;

		$this->fare = $fare;
		
		return $fare;

	}
	
	function has_errs() {

		if ($this->err)

			return $this->err;

		else

			return false;

	}

	function print_failure() {
		$response = '0;'.$this->err;
		if ($this->email)
			$response .= ';'.$this->email;
                $output['success']=false;
                $output['error']=$response;
		echo json_encode($output);
		die;
	}

	function print_success($from, $to) {
		$email 		= $this->email;
		$eta 		= 10;
		$price 		= $this->fare;
		$padd 		= $from['address1'];
		$pcity 		= $from['city'];
		$pstate 	= $from['state'];
		$pzip		= $from['zip'];
		$dadd 	 	= $to['address1'];
		$dcity 		= $to['city'];
		$dstate 	= $to['state'];
		$dzip 		= $to['zip'];
		$date = $this->date;
		$time = $this->startTime;
		$timestamp = $date + $time * 60;
		//CmnFns::diagnose($this);

		$conflink = "https://secure.planettran.com/reservations/ptRes2/twitter_confirm.php?resid=".$this->resid;
		$modlink = "http://m.planettran.com";
	
		$return = "1;$email;$eta;$price;$padd;$pcity;$pstate;$dadd;$dcity;$dstate;$conflink;$modlink;$timestamp;$pzip;$dzip";

		echo $return;
	}


	function get_temp_resource_data($machid) {
		$return = array();

		$result = $this->db->getRow('SELECT * FROM temp_resources WHERE machid=?', array($machid));
		DBEngine::check_for_error($result);

		if (count($result) <= 0)
			return false;
		else
			return $result;

	}

	function get_temp_reservation($resid) {
		$return = array();

		$result = $this->db->getRow('SELECT * FROM temp_reservations WHERE resid=?', array($resid));
		DBEngine::check_for_error($result);

		if (count($result) <= 0) {
			return false;
		}

		$return = $result;
		return $return;
	}

	//returns reservation by id in the production table
	function get_reservation($resid, $userid) {
		$return = array();

		$result = $this->db->getRow('SELECT * FROM reservations WHERE resid=? and memberid=?', array($resid, $userid));
		DBEngine::check_for_error($result);

		if (count($result) <= 0) {
			return false;
		}

		$return = $result;
		return $return;
	}
	
	function insert_twitter_res($res, $from = null, $to = null) {
		$queryFields = array();
		$vals = array();

		foreach($res as $k=>$v) {
			$queryFields[] = "$k=?";
			$vals[] = $v;
		}

		$this->insert_twitter_location($res['machid'], $res);
		$this->insert_twitter_location($res['toLocation'], $res);
		
		$query = "insert into reservations set ".implode(", ", $queryFields);
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		
		DBEngine::check_for_error($result);


		$vals = ($res['resid']);
		$query = "insert into trip_log (resid, dispatch_status, pay_status, pay_type, driver, vehicle, distance, base_fare, discount, tolls, unpaid_tolls, other, total_fare, invoicee, notes, last_mod_time)
			  values
			  (?,27,28,30,1,4,0,0,0,0,0,'',0,'',NULL,NOW())";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		DBEngine::check_for_error($result);

		
		// Clean out our temporary reservation and locations

		// Reservation
		$vals = array($res['resid']);
		$query = "delete from temp_reservations where resid=?";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		DBEngine::check_for_error($result);

		// From location
		// $vals = array($res['machid']);
		// $query = "delete from temp_resources where machid=?";
		// $q = $this->db->prepare($query);
		// $result = $this->db->execute($q, $vals);
		// DBEngine::check_for_error($result);

		// To location
		// $vals = array($res['toLocation']);
		// $query = "delete from temp_resources where machid=?";
		// $q = $this->db->prepare($query);
		// $result = $this->db->execute($q, $vals);
		// DBEngine::check_for_error($result);
	}

	function insert_twitter_location($machid, $res = array()) {

		$loc = $this->get_temp_resource_data($machid);

		// if no temp loc, just return
		if (!$loc || $machid == 'asDirectedLoc') return;

		$queryFields = array();
		$vals = array();

		foreach($loc as $k=>$v) {
			$queryFields[] = "$k=?";
			$vals[] = $v;
		}
		
		$query = "insert into resources set ".implode(", ", $queryFields);
		$query .= " on duplicate key update machid=machid";
		//echo $query;
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		DBEngine::check_for_error($result);

		if ($res['memberid']) {
			$query = "insert into permission (memberid, machid)
				  values (?, ?)";
			$vals = array($res['memberid'], $machid);
			$q = $this->db->prepare($query);
			$result = $this->db->execute($q, $vals);
			DBEngine::check_for_error($result);
		}
	}

	/*
	* copy temp reservation to reservations and delete row in temp_res...
	*/
	function publish_temp_reservation($resid) {
		$query = "insert into reservations select * from temp_reservations tr
			  where tr.resid=?";
		$vals = array($resid);
		$result = $this->db->query($query, $vals);

		$query = "delete from temp_reservations where resid=?";
		$vals = array($resid);
		$result = $this->db->query($query, $vals);
	
	}


}
?>
