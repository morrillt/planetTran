<?php
@define('BASE_DIR', dirname(__FILE__) . '/..');
include_once(BASE_DIR . '/lib/Reservation.class.php');
include_once('DBEngine.class.php');
include_once('Admin.class.php');
include_once('db/AdminDB.class.php');
class api_location{
	var $Name		= null;
	var $Lat		= null;
	var $Lon		= null;
	var $memberid	= null;
    
	
	function api_location() {
		$resDB 		= new ResDB();
		$this->db 	= $resDB->db;
		$this->resDB 	= $resDB;

        $this->adminDB = new AdminDB();
        $this->dbEngine= new DBEngine();
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
	function get_user_info($memberid) {
	
		if(!isset($this->memberid)){
		$this->memberid=$memberid;
		}
	

        
		$this->scheduleid = $this->dbEngine->get_user_scheduleid($this->memberid);

		
		
		if (empty($this->memberid)) {
			$this->err = "User Look Up Error";
			$this->print_failure();
		}
		return $this->memberid;
	}

	function print_failure() {
        $output['success']=false;
        $output['error']=$this->err;
		echo json_encode($output);
		die;
	}
    function get_scheduleId(){
        return $this->scheduleid;
    }


	/*
	* Set default location values for a gps location
	* Return array that can be inserted whole into temp loc table
	*/
	function get_gps_loc_values($loc, $source = 'api') {
		// scheduleid set in get_user_info()

		if (strpos($loc['street_number'], '-') !== false) {
			$parts = explode('-', $loc['street_number']);
			$loc['street_number'] = $parts[0];
		}

		$name = $loc['street_number'].' '.$loc['route'];
		$notes = "This address may not be exact. Be sure to call passenger 10 minutes ahead of time so that you can locate them.";
		$location = $name.', ' .$loc['locality'].', '.$loc['state'].' '.$loc['postal_code'];
		$prefix = 'glb';
		if ($source == 'api')
			$prefix = 'api';
		else if ($source == 'hail')
			$prefix = 'hai';

		return array(
			'machid'=>	uniqid('api'),	
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
			'lat'=>		$this->lat,
			'lon'=>		$this->lon,
			'groupid'=>	-1
			);
	}

	/*
	* Set default location values for a gps location
	* Return array that can be inserted whole into temp loc table
	*/
	function get_resource_values($lat, $long, $source = 'api') {
		// scheduleid set in get_user_info()

		$name = isset($_POST['name']) ? $_POST['name'] : ''; 	
		$notes = "This address may not be exact. Be sure to call passenger 10 minutes ahead of time so that you can locate them.";
		$location = $_POST['street'] . ", " . $_POST['city'] . ", " . $_POST['state'] . ", " . $_POST['zipcode'];
	
		$prefix = 'glb';
		if ($source == 'api')
			$prefix = 'api';
		else if ($source == 'hail')
			$prefix = 'hai';

		return array(
			'machid'=>	uniqid('api'),	
			'scheduleid'=>	$this->scheduleid,
			'name'=>	$name,
			'rphone'=>	$this->phone,
			'notes'=>	$notes,
			'location'=>	$location,
			'status'=>	'a',
			'minRes'=>	0,
			'maxRes'=>	0,
			'state'=>	$_POST['state'],
			'zip'=>		$_POST['zipcode'],
			'address1'=>	$_POST['street'],
			'address2'=>	'',
			'city'=>	$_POST['city'],
			'lat'=>		$lat,
			'lon'=>		$long,
			'groupid'=>	-1
			);
	}

	/*
	* Set location values and address according to a given long&lat location info
	* Return array that can be inserted whole into temp loc table
	*/
	function get_resource_values_by_lnglat($lat, $long, $address, $source = 'api') {
		// scheduleid set in get_user_info()

		$name = isset($_POST['name']) ? $_POST['name'] : ''; 	
		$notes = "This address may not be exact. Be sure to call passenger 10 minutes ahead of time so that you can locate them.";
		
		list($street, $city, $state_zip) = split(", ", $address); 
	    $state = substr($state_zip, 0, -5);
	    $zip = substr($state_zip, -5);
		
		$location = $street . ", " . $city . ", " . $state . ", " . $zip;
	
		$prefix = 'glb';
		if ($source == 'api')
			$prefix = 'api';
		else if ($source == 'hail')
			$prefix = 'hai';

		return array(
			'machid'=>	uniqid('api'),	
			'scheduleid'=>	$this->scheduleid,
			'name'=>	$name,
			'rphone'=>	$this->phone,
			'notes'=>	$notes,
			'location'=>	$location,
			'status'=>	'a',
			'minRes'=>	0,
			'maxRes'=>	0,
			'state'=>	$state,
			'zip'=>		$zip,
			'address1'=>	$street,
			'address2'=>	'',
			'city'=>	$city,
			'lat'=>		$lat,
			'lon'=>		$long,
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

	/*
	* insert location into location table
	*/

	function insert_location($loc) {

		$return =  $this->adminDB->add_resource($loc, $this->memberid);
        return $return;

	}
    /*
	* update location into location table
	*/
    function update_location($loc) {
        $loc['scheduleid'] = $this->scheduleid;
        
		$return =  $this->adminDB->edit_resource($loc);
        return $return;

	}
	function check_post_vals($id) {

		// Values that are required no matter what
		$vals = array(
				'name',
				'lat',
				'lon');
		foreach ($vals as $k=>$v) {
			if (!array_key_exists($v, $_POST)) {
				
			} else {
				$this->$v = addslashes  ($_POST[$v]);
			}
		}
		//adds member id to arrary
		//echo($id."<br/>");
		$this->memberid=$id;
		
		if ($this->has_errs()) {
		//	echo($this->err.'<br/>');
		}
	}

	function has_errs() {
		if ($this->err)
			return $this->err;
		else
			return false;
	}

	/*
	* get temp location 
	*/
	function get_temp_resource_data($machid) {
		$return = array();
		$result = $this->db->getRow('SELECT * FROM temp_resources WHERE machid=? and scheduleid=?', array($machid, $this->scheduleid));
		DBEngine::check_for_error($result);
		if (count($result) <= 0)
			return false;
		else
			return $result;
	}
	/*
	* get location 
	*/
	function get_resource_data($machid) {

		$returnRes = $this->adminDB->get_resource_data($machid);
		return $returnRes;
	}
    function get_location_list() {
        
		$returnResOut = $this->dbEngine->get_user_permissions($this->scheduleid);
        if($returnResOut==false){
            return array();
        }
		return $returnResOut;
	}
	/*
	* delete location
	*/
	function delete_resource_data($machid){
		$return = array();
		$vals= array($machid,$this->memberid);

		// No true deletes; delete the memberid->machid association from permission instead
		//$query = "DELETE FROM resources WHERE machid=? AND scheduleid=?";
		$query = "delete from permission where machid=? and memberid=?";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		DBEngine::check_for_error($result);
		return $result;
	}
	
//todo figure out whether the following is needed
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