<?php
@define('BASE_DIR', dirname(__FILE__) . '/..');
include_once(BASE_DIR . '/lib/Reservation.class.php');

class Twitter extends Reservation {
	// Required values
	var $twitterUserName	= null;
	var $userType		= null;
	var $fromType		= null;
	var $toName		= null;
	var $timestamp		= null;
	var $startTime;
	var $endTime;

	// Required sometimes
	var $fromLat		= null;
	var $fromLon		= null;

	// Other values
	var $email		= null;
	var $err		= null;
	var $fare		= null;
	var $resDB		= null;
	var $db			= null;

	var $memberid		= null;
	var $discount		= null;

	function Twitter($twitterUserName = null) {

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

		$vals = array($this->twitterUserName);

		if (is_null($this->userType) || $this->userType == 0) {
			$where = "where l.twitter_username=?";
		} else if ($this->userType == 1) {
			$where = "where l.email=?";
		} else if ($this->userType == 2) {
			$where = "where l.phone=?";
		}
			
		$query = "select l.memberid, l.fname, l.lname, l.email,
			 	 l.phone, s.scheduleid, b.discount 
				 from login l join schedules s on l.memberid=s.scheduleTitle
				 left join billing_groups b on b.groupid=l.groupid
				 $where";
		$result = $this->db->getRow($query, $vals);
		
		if (empty($result)) {
			$this->err = "That Twitter username does not match any accounts.";
			$this->print_failure();
		}

		$this->memberid 	= $result['memberid'];
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
		$this->resid		= uniqid('twi');
		$this->id 		= $this->resid;
		$this->is_blackout 	= 	0;
		$this->created 		= time();
		$this->modified 	= null;
		$this->moddedBy 	= null;
		$this->parentid		= $this->resid;
		$this->is_blackout	= 0;
		$this->summary		= '';
		$this->flightDets	= null;
		$this->checkBags	= 0;
		$this->special_items	= null; // special last minute p/u code?
		$this->createdBy	= $this->memberid;		
		$this->moddedBy		= null;
		$this->coupon_code	= null;
		$this->linkid		= null;
		$this->mtype		= 'o';
		$this->hour_estimate	= null;
		$this->origin		= 'i';

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
			'origin'	=> $this->origin
		);

		return $res;
	}

	/*
	* Set default location values for a gps location
	* Return array that can be inserted whole into temp loc table
	*/
	function get_gps_loc_values($loc, $source = 'twitter') {
		// scheduleid set in get_user_info()

		if (strpos($loc['street_number'], '-') !== false) {
			$parts = explode('-', $loc['street_number']);
			$loc['street_number'] = $parts[0];
		}

		$name = $loc['street_number'].' '.$loc['route'];
		$notes = "This address may not be exact. Be sure to call passenger 10 minutes ahead of time so that you can locate them.";
		$location = $name.', ' .$loc['locality'].', '.$loc['state'].' '.$loc['postal_code'];
		$prefix = 'glb';
		if ($source == 'twitter')
			$prefix = 'twi';
		else if ($source == 'hail')
			$prefix = 'hai';

		return array(
			'machid'=>	uniqid('twi'),	
			'scheduleid'=>	$this->scheduleid,
			'name'=>	$name,
			'rphone'=>	$this->phone,
			'notes'=>	$notes,
			'location'=>	$location,
			'status'=>	'a',
			'minRes'=>	0,
			'maxRes'=>	0,
			'state'=>	$loc['state'],
			'zip'=>		$loc['postal_code'],
			'address1'=>	$name,
			'address2'=>	null,
			'city'=>	$loc['locality'],
			'lat'=>		$this->fromLat,
			'lon'=>		$this->fromLon,
			'groupid'=>	-1
			);
	}


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

		if ($this->has_errs()) 
			$this->print_failure();

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

	}

	function check_post_vals() {

		// Values that are required no matter what
		$vals = array(	'twitterUserName',
				'fromType',
				'toType',
				'toName',
				'timestamp');
		foreach ($vals as $k=>$v) {
			if (!array_key_exists($v, $_POST)) {
				$this->err = "$v is required.";
			} else {
				$this->$v = $_POST[$v];
			}
		}

		if ($this->fromType == 0) {

		} else if ($this->fromType == 1) {
			$vals = array ('fromLat', 'fromLon');
			foreach ($vals as $k=>$v) {
				if (!array_key_exists($v, $_POST)) {
					$this->err = "$v is required.";
				} else {
					$this->$v = $_POST[$v];
				}
			}

		} else if ($this->fromType == 2) {
			if (!isset($_POST['fromName']))
				$this->err = "fromName is required.";
			else
				$this->fromName = $_POST['fromName'];
		}

		if (!isset($_POST['toName']))
			$this->err = "toName is required.";
		else
			$this->toName = $_POST['toName'];

		// Values that are required conditionally

		if (isset($_POST['userType']))
			$this->userType = $_POST['userType'];

		if ($this->has_errs()) 
			$this->print_failure();

	}

	function get_quote($from, $to) {

		$fromloc = CmnFns::airport_or_zip($from['machid'], $from['zip']);
		$toloc = CmnFns::airport_or_zip($to['machid'], $to['zip']);
		

		$wrapper = "wrapper.pl";
		exec("perl $wrapper $fromloc $toloc 1", $a, $b);
		$fare = $a[0];


		if ($this->discount) 
			$fare -= $fare * ($this->discount / 100);
	
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
		echo $response;
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
		$vals = array($res['machid']);
		$query = "delete from temp_resources where machid=?";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		DBEngine::check_for_error($result);

		// To location
		$vals = array($res['toLocation']);
		$query = "delete from temp_resources where machid=?";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		DBEngine::check_for_error($result);
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


}
?>
