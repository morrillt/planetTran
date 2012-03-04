<?php
@define('BASE_DIR', dirname(__FILE__) . '/../..');
include_once(BASE_DIR . '/lib/Reservation.class.php');
include_once(BASE_DIR . '/templates/mobile.template.php');


class Mobile extends Reservation {
	var $lat 		= null;
	var $lon 		= null;

	function Mobile ($id = null) {
		if (!empty($id))
			$this->id = $id;
		$this->manager = $_SESSION['sessionID'];

		$this->type = isset($_GET['type']) ? substr($_GET['type'], 0, 1) : null;
		$this->db = new ResDB();
		$this->today = mktime(0,0,0);
		$this->tomorrow = mktime(0,0,0,date("m"), date("d")+1, date("Y"));	
		$this->ismobile = true;
	}

	function check_roles() {
		// Block reservations for x and u roles 
		if ($_SESSION['role']=='x') {
			CmnFns::do_error_box(
				'The credit card or other billing information on file is no longer valid. Please call 888-PLNTTRN (756-8876) during business hours to update your information.',
				'',
				true);
		} else if ($user->role=='u'&&$_SESSION['role']!='m') { 
			CmnFns::do_error_box(
				"To be able to make reservations, you must first confirm the email address by either clicking the link in the registration email, or copying/pasting it into your browser's address bar. $resend_url",
				'',
				true);
		} else if ($user->role=='u'&&$_SESSION['role']=='m') { 
			CmnFns::do_error_box(
				"This user has an unconfirmed corporate account. They need to activate it by clicking the link in their registration email. If necessary, we can send the email again. $resend_url",
				'',
				true);
		}

		if ($user->role == 'x') {
			CmnFns::do_error_box(
				'This account is locked; no reservations may be made or altered. The customer should call 888-PLNTTRN (756-8876) during business hours and select the billing option to resolve the issue. Any upcoming trips can be canceled from the dispatch screen.',
				'',
				true);
		}
		if ($this->is_blackout == 1) {
			CmnFns::do_error_box(
				'This reservation has been deleted.',
				'',
				true);
		}

	}

	function print_hail_res($type = null) {
		global $conf;
		$this->check_roles();

		if (!empty($this->id))
			$this->load_by_id();
		else
			$this->load_by_get();
		$user = new User($this->memberid);

		/*
		// the 1-2 locs
		// if the loc exists in the database, it's at top of list
		// GPS loc is there either way. 
		*/

		$from = $this->db->get_resource_data($this->machid);
		$scheduleid = $this->db->get_user_scheduleid($_SESSION['sessionID']);
		$loclist = $this->db->get_user_permissions($scheduleid);
		$tiny_loclist = array($from);

		$three_nearest = $this->get_3_nearest_locs($scheduleid, $this->lat, $this->lon, $from);

		$idle = $this->db->get_cur_nondispatched('MA');

		print_hail_res($loclist, $three_nearest, null, $this->machid, $this, $idle);
	}

	function print_pda_res($isAlert = false) {
		global $conf;

		if (!empty($this->id))
			$this->load_by_id();
		else
			$this->load_by_get();
		$user = new User($this->memberid);
		$resend_url = $conf['app']['weburi']."/resend.php?id=".$this->memberid;
		$resend_url = "<br>&nbsp<br><a href=\"$resend_url\">Click here to send the confirmation email again.</a>";

		//CmnFns::diagnose($this);
		//echo "-- ".$this->machid." ".$this->toLocation."<br>";

		$this->check_roles();

		$toRs = $this->db->get_resource_data($this->toLocation);
		$fromRs = $this->db->get_resource_data($this->machid);
		//$rs = $this->db->get_resource_data($this->machid);
		//$loclist = $this->db->get_user_permissions($this->db->get_user_scheduleid($this->memberid));
		$scheduleid = $this->db->get_user_scheduleid($_SESSION['sessionID']);
		$loclist = $this->db->get_user_permissions($scheduleid);
		$paymentOptions = $this->db->getPaymentOptions($this->memberid);

		if ($this->type == 'v') {
			$trip = $this->db->get_trip_data($this->id);
			print_viewonly($loclist, $this->toLocation, $this->machid, $this, $trip, $isAlert);
		} else
			print_pda_res($loclist, $this->toLocation, $this->machid, $this, $paymentOptions);

	}

	function pdalogout() {
			header('Location: m.index.php?logout=1');
	}


	function get_3_nearest_locs($scheduleid, $lat, $lon, $from) {
		
		$limit = 3;
		$db = new DBEngine();

		$query = "SELECT distinct rs.machid, rs.* FROM 
			permission pm, resources rs,
			schedule_permission sp
			WHERE pm.memberid = sp.memberid
			AND sp.scheduleid = ?
			AND pm.machid=rs.machid
			ORDER BY rs.name";

		$vals = array($scheduleid);
		$q = $db->db->prepare($query);
		$result = $db->db->execute($q, $vals);

		$return = array();

		//echo "<pre>";
		//echo "lat\tcmplat\tlon\tcmplon\tlatSC\tlonSC\ttotal\tname<br>";

		$frommatch = 0;

		$count = 0;

		while($row = $result->fetchRow()) {
			$prefix = substr($row['resid'], 0, 3);

			$loclat = $row['lat'];
			$loclon = $row['lon'];

			if (!$loclat || !$loclon) continue;

			$distance = CmnFns::gps_distcalc($lat, $lon, $loclat, $loclon);
			//$distance = round($distance, 2);
			$row['distance'] = $distance;
			if ($loclat == $lat && $loclon == $lon)
				$row['name'] = "(GPS Match) ".$row['name'];
			//else
			//	$row['name'] = $row['distance']." ".$row['name'];
		
			//echo "$shortlat\t$shortcmplat\t$shortlon\t$shortcmplon\t{$row['latscore']}\t{$row['lonscore']}\t{$row['score']}\t{$row['name']}<br>";

			// Match
			//if ($lat < ($cmplat + $d) && $lat > ($cmplat - $d) &&
			//    $lon < ($cmplon + $d) && $lon > ($cmplon - $d)) {
			if ($distance < .75) {

				$return[] =  $row;
				$count++;
			}

	
		}

		if (!$count) return false;

		function compare($x, $y) {
			if ($x['distance']==$y['distance'])
				return 0;
			else if ($x['distance']<$y['distance'])
				return -1;
			else
				return 1;	
		}

		if ($count > 0)
			usort($return, 'compare');

		// limit is 3 if no hail loc was found, 4 if one was 
		if ($count > $limit)
			$return = array_slice($return, 0, $limit);

		if (!empty($return)) return $return;

		return false;
	}

}
?>
