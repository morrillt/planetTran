<?php
/**
* DBEngine class
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 08-11-04
* @package DBEngine
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');
/**
* CmnFns class
*/
include_once('CmnFns.class.php');
include_once('Receipt.class.php');
/**
* Pear::DB
*/
include_once('pear/DB.php');
/**
* Provide all database access/manipulation functionality
*/
class DBEngine {
	var $db;							// Reference to the database object
	var $dbs = array();					// List of database names to use.  This will be used if more than one database is created and different tables are associated with multiple databases
	var $table_to_database = array();	// Array associating tables to databases
	var $prefix;						// Prefix to prepend to all primary keys
	var $err_msg = '';
	/**
	* DBEngine constructor to initialize object
	* @param none
	*/
	function DBEngine() {
		$this->prefix = $GLOBALS['conf']['db']['pk_prefix'];
		$this->dbs = array ($GLOBALS['conf']['db']['dbName']);
		$this->db_connect();
		$this->define_tables();
	}
	/**
	* Create a persistent connection to the database
	* @param none
	* @global $conf
	*/
	function db_connect() {
		global $conf;
		/***********************************************************
		/ This uses PEAR::DB
		/ See http://www.pear.php.net/manual/en/package.database.php#package.database.db
		/ for more information and syntax on PEAR::DB
		/**********************************************************/
		// Data Source Name: This is the universal connection string
		// See http://www.pear.php.net/manual/en/package.database.php#package.database.db
		// for more information on DSN
		$dsn = $conf['db']['dbType'] . '://' . $conf['db']['dbUser'] . ':' . $conf['db']['dbPass'] . '@' . $conf['db']['hostSpec'] . '/' . $this->dbs[0];
		// Make persistant connection to database
		$db = DB::connect($dsn, true);
		// If there is an error, print to browser, print to logfile and kill app
		if (DB::isError($db)) {
			die ('Error connecting to database: ' . $db->getMessage() );
		}
		// Set fetch mode to return associatve array
		$db->setFetchMode(DB_FETCHMODE_ASSOC);
		$this->db = $db;
	}
	/////////////////////////////////////////////////////
	// Common functions
	/////////////////////////////////////////////////////
	/**
	* Defines the $table_to_database array
	* This array will relate each table to a database name,
	*  making it very easy to change all table associations
	*  if additional databases are added
	* @param none
	*/
	function define_tables() {
		$this->table_to_database = array (
						'login' 		=> $this->dbs[0],
						'reservations'	=> $this->dbs[0],
						'resources'		=> $this->dbs[0],
						'permission'	=> $this->dbs[0],
						'schedules'		=> $this->dbs[0],
						'schedule_permission' => $this->dbs[0]
														);
	}
	/**
	* Returns the database and table name in form: database.table
	* @param string $table table to return
	* @return fully qualified table name in form: database.table
	*/
	function get_table($table) {
		return $table;
		//return $this->table_to_database[$table] . '.' . $table;
	}
	/**
	* Assigns a table to a database for SQL statements
	* @param string $table name of table to change
	* @param strin $database name of database that this table belongs to
	* @return success of assignment
	*/
	function set_table($table, $database) {
		if (!isset($this->table_to_database[$table]))
			return false;
		else
			$this->table_to_database[$table] = $database;
		return true;
	}
	/**
	* Generic database query function.
	* This will return specified fields from one table in a specified order
	* @param string $table name of table to return from
	* @param array $fields array of field values to return
	* @param string $order sql order string
	* @param int $limit limit of query
	* @param int $offset offset of limit
	* @return mixed all data found in query
	*/
	function get_table_data($table, $fields = array('*'), $orders = array(), $limit = NULL, $offset = NULL) {
		$return = array();
		$order = CmnFns::get_value_order($orders);		// Get main order value
		$vert = CmnFns::get_vert_order();				// Get vertical order
		$query = 'SELECT ' . join(', ', $fields)
			. ' FROM ' . $this->get_table($table)
			. (!empty($order) ? " ORDER BY $order $vert" : '');
		// Append any other sorting constraints
		for ($i = 1; $i < count($orders); $i++)
			$query .= ', ' . $orders[$i];
		//die('lim' . $query);
		if (!is_null($limit) && !is_null($offset))		// Limit query
			$result = $this->db->limitQuery($query, $offset, $limit);
		else										// Standard query
			$result = $this->db->query($query);
		$this->check_for_error($result);
		if ($result->numRows() <= 0) {		// Check if any records exist
			$this->err_msg = translate('There are no records in the table.', array($table));
			return false;
		}
		while ($rs = $result->fetchRow())
			$return[] = $this->cleanRow($rs);
		$result->free();
		return $return;
	}
	/**
	* Deletes a list of rows from the database
	* @param string $table table name to delete rows from
	* @param string $field field name that items are in
	* @param array $to_delete array of items to delete
	*/
	function deleteRecords($table, $field, $to_delete) {
		// Put into string, quoting each value
		$delete = join('","', $to_delete);
		$delete = '"'. $delete . '"';
		$result = $this->db->query('DELETE FROM ' . $this->get_table($table) . ' WHERE ' . $field . ' IN (' . $delete . ')');
		$this->check_for_error($result);		// Check for an error
		return true;
	}
	function get_admin_schedules($id, $order, $vert) {
		$return = array();
		$query_string = "";
		$sp = "";
		$values = array();
		if(!Auth::isAdmin()) {
		    $query_string =
			'AND s.scheduleid = sp.scheduleid '
			.'AND sp.memberid = ? ';
			$sp = "schedule_permission sp, ";
			$values = array($id);
		}
		if(isset($_POST['firstName']) && !empty($_POST['firstName'])) 
			$query_string .= "AND l.fname like '%" . $_POST['firstName'] . "%' ";
		if(isset($_POST['lastName']) && !empty($_POST['lastName'])) 
			$query_string .= "AND l.lname like '%" . $_POST['lastName'] . "%' ";
		if(isset($_POST['group']) && !empty($_POST['group']))
			$query_string .= "AND l.groupid='".$_POST['group']."' ";
		if(isset($_POST['email']) && !empty($_POST['email']))
		{
		  $query_string .= "AND l.email like ? ";
		  $values[] = '%'.$_POST['email'].'%';
		}
		if($query_string == '') {
			return;
			$query_string = "AND sp.memberid = 'sjlkfd'";
		}
		$query = 'SELECT distinct s.scheduleid as scheduleid, '
			.'l.fname as fname, l.lname as lname, '
			.'l.memberid as memberid, l.email as email, '
			.'l.role as role, '
			.'l.groupid as groupid, l.other as other, '
			.'b.type as group_type, sum(pp.expdate) as hascard, '
			.'l.institution as institution, l.position as position, l.phone as phone '
			."FROM ($sp schedules s, login l) "
			.'LEFT JOIN billing_groups b on l.groupid=b.groupid '
			.'LEFT JOIN paymentProfiles pp on l.memberid=pp.memberid '
			.'WHERE l.memberid = s.scheduleTitle '
			. $query_string
			.'group by s.scheduleid '
			.'ORDER by lname, fname';

		// Prepare query
		$q = $this->db->prepare($query);
		// Execute query
		// var_dump($query);
		// die(print_r($values));
		$result = $this->db->execute($q, $values);//$values);
		// Check if error
		$this->check_for_error($result);
		if ($result->numRows() <= 0) {
			$this->err_msg = translate('You do not have any schedules.');
			return false;
		}
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		$result->free();
		
		return $return;
	}
	/**
	* Return all reservations associated with a user
	* @param string $id user id
	* @return array of reservation data
	*/
	function get_user_reservations($id, $order, $vert, $active = false) {
		$return = array();
		if ($active) {
			$states = " and t.dispatch_status in (10,355,617) ";
		} else {
			$states =  ' and t.dispatch_status <> 9'
			. ' and t.dispatch_status <> 13'
			. ' and t.dispatch_status <> 14';
		}
			
		$query = 'SELECT res.resid as resid, res.date as date, sched.scheduleTitle as schedTitle, '
			. 'login.fname as firstName, login.lname as lastName, '
			. 'loc1.name as fromLocationName, '
			. 'loc2.name as toLocationName, res.startTime as pickupTime, res.endTime as schedTime, res.special_items as specialItems, t.dispatch_status, '
			. 'res.summary as summary, res.flightDets as flightDets, res.checkBags as checkBags FROM '
			. $this->get_table('reservations') . ' as res '
			. 'left join trip_log t on t.resid=res.resid ,'
			. $this->get_table('resources') . ' as loc1,'
			. $this->get_table('resources') . ' as loc2,'
			. $this->get_table('schedules') . ' as sched,'
			. $this->get_table('login') . ' as login'
			. ' WHERE res.memberid=?'
			. ' AND login.memberid=res.memberid'
			. ' AND loc1.machid=res.machid'
			. ' AND loc2.machid=res.toLocation'
			. ' AND sched.scheduleid=res.scheduleid'
			. ' AND res.date>=?'
			. ' AND res.is_blackout <> 1'
			. $states 
			. " ORDER BY date, pickupTime";
		//echo $query;
		//$values = array($id, mktime(0,0,0));
		$moddate = mktime(0,0,0);
		$moddate -= $moddate % 100;
		$values = array($id, $moddate);
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
		$this->check_for_error($result);
		if ($result->numRows() <= 0) {
			$this->err_msg = translate('You do not have any reservations scheduled.');
			return false;
		}
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		$result->free();
		return $return;
	}
	function get_user_receipts($id, $page = null) {
		$return = array();
		$start = isset($_GET['monthLow'])&&isset($_GET['yearLow'])?mktime(0,0,0,$_GET['monthLow'],0,$_GET['yearLow']):false;
		$end = isset($_GET['monthHi'])&&isset($_GET['yearHi'])?mktime(0,0,0,$_GET['monthHi']+1,0,$_GET['yearHi']):false;
		$dateLo = $start ? " AND res.date >= $start " : '';
		$dateHi = $end ?   " AND res.date <= $end " : '';
		if (isset($_GET['page']) && is_numeric($_GET['page'])) {
			$int = intval($_GET['page']);
			$lowerLimit = $int * 30;
			$upperLimit = $lowerLimit + 30;
		} else {
			$int = 0;
			$lowerLimit = 0;
			$upperLimit = 30;
		}
		$limit = ($page == 'ALL') ? '' : " LIMIT $lowerLimit, 30";
		$query = "SELECT SQL_CALC_FOUND_ROWS res.resid as resid, res.date as date, sched.scheduleTitle as schedTitle, 
		   	t.total_fare as total_fare, t.pay_status as pay_status, 
			c.code as driver,
			login.fname as firstName, login.lname as lastName, 
			res.*,
			loc1.name as fromLocationName, 
			loc2.name as toLocationName, res.startTime as pickupTime, res.endTime as schedTime, res.special_items as specialItems, 
			res.summary as summary, res.flightDets as flightDets, res.checkBags as checkBags,
			oti.issueid as feedbackDone
			FROM reservations res left join trip_log t on res.resid=t.resid
			join resources loc1 on loc1.machid=res.machid
			join resources loc2 on loc2.machid=res.toLocation
			join schedules sched on sched.scheduleid=res.scheduleid
			join login on login.memberid=res.memberid
			left join codes c on t.driver=c.id
			left join oti on oti.resid=res.resid and fromsystem='survey'
					WHERE res.memberid=?
					AND t.pay_status in (23, 25) 
					AND t.pay_type <> 36 
					AND res.is_blackout <> 1
					$dateLo $dateHi
					ORDER BY date DESC, pickupTime ASC
					$limit";
					//AND login.fname <> 'Saturn' 
		//$values = array($id, mktime(0,0,0));
		$moddate = mktime(0,0,0);
		$moddate -= $moddate % 100;
		$values = array($id);
		// Prepare query
		$q = $this->db->prepare($query);
		// Execute query
		$result = $this->db->execute($q, $values);
		$query = "SELECT FOUND_ROWS() as total";
		$qresult = mysql_query($query);
		$row = mysql_fetch_assoc($qresult);
		$row['upper'] = $upperLimit;
		$row['lower'] = $lowerLimit;
		$row['page'] = $int;
		// Check if error
		$this->check_for_error($result);
		if ($result->numRows() <= 0) {
			$this->err_msg = translate('You do not have any reservations scheduled.');
			return false;
		}
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		array_unshift($return, $row);
		$result->free();
		return $return;
	}
	
	function get_receipt_data2($resid) {
		$query = 'SELECT res.resid as resid, res.date as date, '
			.'res.summary as notes, '
			.'login.fname as firstName, login.lname as lastName, '
			.'loc1.address1 as fromAddress, loc1.city as fromCity, '
			.'loc1.state as fromState, loc1.zip as fromZip, '
			.'loc2.address1 as toAddress, loc2.city as toCity, '
			.'loc2.state as toState, loc2.zip as toZip, '
			.'res.startTime as startTime, ' 
			.'login.other as cc, t.total_fare as total_fare '
			.'from reservations res left join trip_log t on res.resid=t.resid, '
			.'resources as loc1, '
			.'resources as loc2, '
			.'login as login '
			.'WHERE res.resid=? '
			.'AND login.memberid=res.memberid '
			.'AND loc1.machid=res.machid '
			.'AND loc2.machid=res.toLocation';
		$values = array($resid);
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
		$res = $result->fetchRow();
		$result->free();
		$res['paxname'] = null;
		$notes = $this->parseNotes($res['notes']);
		if ($notes['name'])
			$res['paxname'] = $notes['name'];
		return $res;
	}
	/**
	* Gets all the resources that the user has permission to reserve
	* @param string $schid schedule id
	* @return array or resource data
	*/
	function get_user_permissions($schid, $to = false) {
		if (empty($schid)) {
			$this->err_msg = 'Invalid $schid.';
			return false;
		}
		$return = array();
		$sql = "SELECT distinct rs.machid, rs.*
			FROM permission pm, resources rs,
			schedule_permission sp
			WHERE pm.memberid = sp.memberid
			AND sp.scheduleid = ?
			AND pm.machid=rs.machid
			ORDER BY rs.name";
		// Execute query
		$result = $this->db->query($sql, array($schid));
		// Check if error
		$this->check_for_error($result);
		if ($result->numRows() <= 0) {
			$this->err_msg = translate('You do not have permission to use any resources.');
			return false;
		} else if ($result->numRows() >= 1000) {
			/* get last 100 inserted rows ***/
			$max = $result->numRows();
			$min = $max - 100;
			$query = "SELECT distinct rs.machid, rs.*
				FROM permission pm, resources rs,
				schedule_permission sp
				WHERE pm.memberid = sp.memberid
				AND sp.scheduleid = ?
				AND pm.machid=rs.machid
				limit $min, $max";
			$result3 = $this->db->query($query, array($schid));
			/*** add to haystack ***/
			$haystack = array();
			while ($row = $result3->fetchRow())
				$return[] = $row;
			$result3->free();
			/*** get last 3 months even though it's names last2 **/
			$last2months = time() - 60*60*24*90;
			$query2 = "select machid, toLocation
				   from reservations res
				   where res.scheduleid=?
				   and res.date > ?";
			$vals = array($schid, $last2months);
			$result2 = $this->db->query($query2, $vals);
			/*** add to haystack ***/
			while ($row = $result2->fetchRow()) {
				$haystack[$row['machid']] = 1;
				$haystack[$row['toLocation']] = 1;
			}
			while ($rs = $result->fetchRow()) {
				if (array_key_exists($rs['machid'], $haystack))
					$return[] = $this->cleanRow($rs);
			}
		
			if (!function_exists('zcompare')) {
				function zcompare($x, $y) {
					return strcmp($x['name'], $y['name']);
				}
			}
			usort($return, 'zcompare');
			//echo $result2->numRows();
			$result2->free();
		} else {
			while ($rs = $result->fetchRow())
            {
                
                $return[] = $this->cleanRow($rs);
            }
		}
		$result->free();
		if ($to) {
			$query = "select * from resources 
				  where machid='asDirectedLoc'";
			$return[] = $this->db->getRow($query);
		}
		return $return;
	}
	/*
	* Return array of recent locations
	* $limit max number to return
	*/
	function get_recent_locations($memberid, $limit = 0) {
		$query = "select fromloc.machid as fromid, fromloc.name as fromname,
				 toloc.machid as toid, toloc.name as toname,
			  max(r.created) as created, max(r.date) as rdate,
			  r.resid
			  from reservations r join resources fromloc on r.machid=fromloc.machid
			  join resources toloc on r.toLocation = toloc.machid
			  where r.memberid=?
			  group by fromloc.machid, toloc.machid
			  order by r.created desc";
		
		//echo $query;
		$result = $this->db->query($query, array($memberid));
		if ($result->numRows() <= 0) 
			return false;
		$locs = array();
		while ($rs = $result->fetchRow()) {
			$locs[] = $this->cleanRow($rs);
		}
		$result->free();
		function lcompare($x, $y) {
			if ($x['created']==$y['created'])
				return 0;
			else if ($x['created']>$y['created'])
				return -1;
			else
				return 1;	
		}
		usort($locs, 'lcompare');
		//CmnFns::diagnose($locs);
		$cmp = array();		// Comparison array for duplicates
		$temp = array();	// Temporary array
		$list = array();	// Final array
		$return = array();
		for ($i=0; $locs[$i]; $i++) {
			$cur = $locs[$i];
			// this loc is already in list, skip it
			if (!array_key_exists($cur['fromid'], $cmp)) {
				$cmp[$cur['fromid']] = 1;
				$temp['machid'] = $cur['fromid'];
				$temp['name'] = $cur['fromname'];
	
				// resid won't be accurate because of grouping
				//$temp['resid'] = $cur['resid'];
				//$temp['date'] = $cur['rdate'];
				array_push($list, $temp);
			}
			if (!array_key_exists($cur['toid'], $cmp)) {
				$cmp[$cur['toid']] = 1;
				$temp['machid'] = $cur['toid'];
				$temp['name'] = $cur['toname'];
				//$temp['resid'] = $cur['resid'];
				//$temp['date'] = $cur['rdate'];
				array_push($list, $temp);
			}
		}
		if ($limit) {
			$n = 0;
			foreach ($list as $k=>$v) {
				$n++;
				if ($n > $limit) break;
				$return[$k] = $v;
			}
		} else
			$return = $list;
		return $return;
	}
	/**
	* Gets all the resources that the user has permission to reserve
	* @param string $userid user id
	* @return array or resource data
	*/
	function get_user_scheduleid($userid) {
		$return = array();
		$sql = 'SELECT s.scheduleid FROM '
			.'schedules s, login l '
			."where l.memberid=? "
			."and l.memberid=s.scheduleTitle";
		// Execute query
		$result = $this->db->query($sql, array($userid));
		// Check if error
		$this->check_for_error($result);
		if ($result->numRows() <= 0) {
			$this->err_msg = translate('You do not have permission to use any resources.');
			return false;
		}
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		$result->free();
		return $return[0]['scheduleid']	;
	}
	/**
	* Get associative array with machID, resource name, and status
	* This function loops through all resources
	*  and constructs an associative array with the
	*  resource's machID, name and status as
	*  $array[x] => ('machid' => 'this_resource_id', 'name' => 'Resource Name', 'status' => 'a')
	* @param none
	* @return array of machID, resource name, status
	*/
	function get_mach_ids($scheduleid = null) {
		$return = array();
		$values = array();
		$sql = 'SELECT machid, name, status FROM ' . $this->get_table('resources');
		if ($scheduleid != null) {
			$sql .= ' WHERE scheduleid = ?';
			$values = array($scheduleid);
		}
		$sql .= ' ORDER BY name';
		$result = $this->db->query($sql, $values);
		$this->check_for_error($result);
		if ($result->numRows() <= 0) {
			$this->err_msg = translate('No resources in the database.');
			return false;
		}
		while ($rs = $result->fetchRow()) {
			$return[] = $this->cleanRow($rs);
		}
		$result->free();
		return $return;
	}
	/**
	* Gets the default scheduleid
	* @param none
	* @return string scheduleid of default schedule
	*/
	function get_default_id() {
		$result = $this->db->getOne('SELECT scheduleid FROM ' . $this->get_table('schedules') . ' WHERE isDefault = 1 AND isHidden = 0');
		$this->check_for_error($result);
		if (empty($result)) {	// If default is hidden
			$result = $this->db->getOne('SELECT scheduleid FROM ' . $this->get_table('schedules') . ' WHERE isHidden = 0');
			$this->check_for_error($result);
		}
		return $result;
	}
	/**
	* Checks to see if the scheduleid is valid
	* @param none
	* @return whether it is valid or not
	*/
	function check_scheduleid($scheduleid) {
		$result = $this->db->getOne('SELECT COUNT(scheduleid) AS num FROM ' . $this->get_table('schedules') . ' WHERE scheduleid = ? AND isHidden <> 1', array($scheduleid));
		$this->check_for_error($result);
		return (intval($result) > 0);
	}
	/**
	* Gets all data for a given schedule
	* @param string $scheduleid id of schedule
	* @param array of schedule data
	*/
	function get_schedule_data($scheduleid) {
		$result = $this->db->getRow('SELECT * FROM ' . $this->get_table('schedules') . ' WHERE scheduleid = ?', array($scheduleid));
		$this->check_for_error($result);
		return $result;
	}
	/**
	* Gets the list of available schedules
	* @param none
	*/
	function get_schedule_list() {
		$return = array();
		$result = $this->db->query('SELECT scheduleid, scheduleTitle FROM ' . $this->get_table('schedules') . ' WHERE isHidden = 0');
		$this->check_for_error($result);
		while ($rs = $result->fetchRow())
			$return[] = $this->cleanRow($rs);
		return $return;
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/**
	* Checks to see if there was a database error and die if there was
	* @param object $result result object of query
	*/
	function check_for_error($result) {
		//CmnFns::diagnose($result);
		if (DB::isError($result))
			CmnFns::do_error_box(translate('There was an error executing your query') . '<br />'
				. $result->getMessage()
//				. '<br />' . '<a href="javascript: history.back();">' . translate('Back') . '</a>'
        );
		return false;
	}
	function check_for_dispatch_error($result) {
		if (DB::isError($result))
			return false;
		return true;
	}
	/**
	* Generates a new random id for primary keys
	* @param string $prefix string to prefix to id
	* @return random id string
	*/
	function get_new_id($prefix = '') {
		// Use the passed in prefix, if it exists
		if (!empty($prefix))
			$this->prefix = $prefix;
		// Only use first 3 letters
		$this->prefix = strlen($this->prefix) > 3 ? substr($this->prefix, 0, 3) : $this->prefix;
		return uniqid($this->prefix);
	}
	/**
	* Enodes a string into an encrypted password string
	* @param string $pass password to encrypt
	* @return encrypted password
	*/
	function make_password($pass) {
		return md5($pass);
	}
	/**
	* Strips out slashes for all data in the return row
	* - THIS MUST ONLY BE ONE ROW OF DATA -
	* @param array $data array of data to clean up
	* @return array with same key => value pairs (except slashes)
	*/
	function cleanRow($data) {
		$return = array();
		foreach ($data as $key => $val)
			$return[$key] = stripslashes($val);
		return $return;
	}
	/**
	* Makes an array of ids in to a comma seperated string of values
	* @param array $data array of data to convert
	* @return string version of the array
	*/
	function make_del_list($data) {
		//$c = join('","', $data);
		//return '"' . $c . '"';
		$c = join('\',\'', $data);
		return "'" . $c . "'";
	}
	/**
	* Returns the last database error message
	* @param none
	* @return last error message generated
	*/
	function get_err() {
		return $this->err_msg;
	}
	/*
	* Insert an airport when people use the "add airport" button
	*/
	function add_apt($scheduleid, $machid) {
		$query = "INSERT INTO permission VALUES
			  ('$scheduleid','$machid')";
		$qresult = mysql_query($query);
	}
	/**
	* Convert fares to co2 for impact and referrals functions
	* $f fare, $a average
	*/
	function coConvert($f, $a) {
		return round(($f/85*4*20), 2);
		//if ($f <= 0 || !$f || $a <= 0 || !$a) return 0;
		//else if ($a < 40) return round($f / 5);
		//else if ($a >= 40 && $a <= 60) return round($f / 4);
		//else return round($a / 3); 
	}
	/**
	* Get total fares of everyone this person has referred
	*/
	function get_referred_fares($id) {
		$query = "select sum(t.total_fare) as total_fare, 'xxx' as x,
			  avg(t.total_fare) as avg_fare,
			 r.memberid as memberid, a.referrer as referrer 
			 from 
			referrals a left join reservations r on 
			a.memberid = r.memberid
			 left join trip_log t
			 on r.resid=t.resid
			where a.referrer = '$id'
			and t.total_fare is not null
			group by x";
		$qresult = mysql_query($query);
		echo mysql_error();
	
		$row = mysql_fetch_assoc($qresult); 
		$return = array('total_fare' => $row['total_fare'],
				'avg_fare' => $row['avg_fare']);
		$return['total_fare'] = $this->coConvert($row['total_fare'], $row['avg_fare']);
		return $return;
	}
	/**
	* Function to check whether a member has any referrals
	*/
	function has_referrals($id) {
		$query = "select memberid from referrals where referrer='$id'";
		$qresult = mysql_query($query);
	
		if (mysql_num_rows($qresult) < 1)
			return false;
		else
			return true;
	}
	/**
	* Returns the names and memberids of referees
	*/
	function get_referred_names($id) {
		$query = "select n.fname, n.lname, r.email,
			  unix_timestamp(r.date) as date
			  from login l join referrals r
			  on r.referrer = l.memberid
			  left join login n on r.memberid=n.memberid
			  where r.referrer='$id'";
		$qresult = mysql_query($query);
		if (mysql_num_rows($qresult) == 0)
			return false;
		$return = array();
		while ($row = mysql_fetch_assoc($qresult)) {
			$return[] = $row;
		}
		return $return;
	}
	/**
	* Parse the notes for a reservation
	* return an array with all fields and values
	* numeric_index return as an indexed array instead of associative
	*/
	function parseNotes($notes, $numeric_index = false) {
		$return = array();
		if (strpos($notes, 'GROUP_DEL') !== false) {
			if ($numeric_index) return explode('GROUP_DEL', $notes);
			list($name, $cell, $return['code'], $return['address'], $return['address2'], $return['cc'], $return['exp'], $return['notes'], $return['email']) = explode('GROUP_DEL', $notes);
		} else
			return false;
		//if (!$name && !$cell)
		//	return false;
		//$cell = ($name || $cell) ? $cell : $user['phone'];
		//if (!$cell)
		//	$cell = 'Not given';
		//$cell = (strpos($cell, "877-ECO-TAXI") === false) ? $cell : "CHECK NOTES";
		//$name = $name ? $name : $user['fname'].' '.$user['lname'];
		$return['name'] = $name;
		$return['cell'] = $cell;
		return $return;
	}
	/**
	* Insert attributes
	* array $attrs(tableid, attrid, attr_value)
	*/
	function insertAttrs($attrs) {
		$pieces = array();
		for ($i=0; $attrs[$i]; $i++) {
			$row = $attrs[$i];
			if (!isset($row['tableid'])||!isset($row['attrid'])
				|| !isset($row['attr_value']))
				return false;
			$row['attr_value'] = mysql_real_escape_string($row['attr_value']);
			$pieces[] = "('{$row['tableid']}', {$row['attrid']}, '{$row['attr_value']}')";
		}
		$qstr = implode(", ", $pieces);
		$query = "insert into attributes values $qstr";
		$qresult = mysql_query($query);
		//if (mysql_affected_rows($qresult) == 0)
		//	return false;
		return true;
		
	}
	/**
	* Update Attrs
	* array $attrs(tableid, attrid, attr_value)
	*/
	function updateAttrs($attrs) {
		$pieces = array();
		for ($i=0; $attrs[$i]; $i++) {
			$row = $attrs[$i];
			if (!isset($row['tableid'])||!isset($row['attrid'])
				|| !isset($row['attr_value']))
				return false;
			$row['attr_value'] = mysql_real_escape_string($row['attr_value']);
	
			$query = "select attrid from attributes
				  where tableid='{$row['tableid']}'
				  and attrid={$row['attrid']}";
			$qresult = mysql_query($query);
			if (mysql_num_rows($qresult)==0) {
				$query = "insert into attributes (tableid,attrid,attr_value) values
					('{$row['tableid']}', {$row['attrid']}, '{$row['attr_value']}')";
				
			} else {
			$query ="update attributes set attr_value='{$row['attr_value']}'
				  where tableid='{$row['tableid']}'
				  and attrid={$row['attrid']}";
			}
		$qresult = mysql_query($query);
		}
		//if (mysql_affected_rows($qresult) == 0)
		//	return false;
		return true;
		
	}
	/**
	* Delete a single attribute
	*/
	function delAttr($tableid, $attrid) {
		$query = "delete from attributes where
			  tableid='$tableid' and attrid='$attrid'";
		$qresult = mysql_query($query);
	}
	/**
	* Get one attribute value by tableid
	* attrid optional
	*/
	function getAttr($tableid, $attrid = '') {
		if ($attrid) $attrid = " AND attrid='$attrid'";
		$query = "SELECT attr_value FROM attributes
			  WHERE tableid='$tableid' $attrid";
		$qresult = mysql_query($query);
		if ($qresult && mysql_num_rows($qresult)) {
			$row = mysql_fetch_assoc($qresult);
			return $row['attr_value'];
		}
	}
	/**
	* Get array of all attributes by tableid
	*/
	function getAttrs($tableid) {
		$query = "SELECT attrid, attr_value FROM attributes
			  WHERE tableid='$tableid'";
		$qresult = mysql_query($query);
		if (!$qresult || !mysql_num_rows($qresult)) return false; 
		$return = array();
		while ($row = mysql_fetch_assoc($qresult)) {
			$return[] = $row;
		}
		return $return;
	}
	/**
	* Insert 1 row into the history table
	*/
	function insertHistory($tableid, $memberid, $notes='') {
		$time = time();
		$notes = !$notes ? 'NULL' : "'".addslashes($notes)."'";
		$query = "insert into history (tableid, memberid, notes, time)
			  values('$tableid', '$memberid', $notes, $time)"; 
		$qresult = mysql_query($query);
	}
	/**
	* Return history by tableid
	* optionally return notes only
	*/
	function getHistory($tableid, $notes=1) {
		$nstr = $notes ? ' AND notes is not null' : '';
		$query = "select h.*, concat_ws(' ', l.fname, l.lname) as name 
			  from history h left join login l on h.memberid=l.memberid 
			  where h.tableid='$tableid' $nstr
			  order by h.time asc";
		$qresult = mysql_query($query);
		if (!$qresult || !mysql_num_rows($qresult)) return;
		$return = array();
		while ($row = mysql_fetch_assoc($qresult)) 
			$return[] = $row;
		return $return;	
	}
	/**
	* Get distance to a zip code/location 
	*/
	function getDistance($zip, $loc2) {
		$query = "select distance from zips where 
			  (zip='$zip' and loc2='$loc2') or
			  (zip='$loc2' and loc2='$zip')";
		$qresult = mysql_query($query);
		if (!$qresult || !mysql_num_rows($qresult))
			return false;
		$row = mysql_fetch_assoc($qresult);
		return $row['distance'];
	}
	function get_receipt_data($resid) {
		$query = "select res.resid as resid,
				res.summary as notes,
				res.special_items as special_items,
				res.date as date,
				res.startTime as startTime,
				res.machid as fromMachid,
				res.toLocation as toMachid,
				tl.total_fare as total_fare,
				tl.pay_status as pay_status,
				tl.pay_type as pay_type,
				tl.unpaid_tolls as wait_time,
				tl.other as other,
				tl.base_fare as base_fare,
				tl.authorization as authorization,
				tl.cc as cc,
				l.fname as fname,
				l.lname as lname,
				l.email as email,
				l.phone as phone,
				l.groupid as groupid,
				fromLoc.name as fromName,
				fromLoc.address1 as fromAddress,
				fromLoc.city as fromCity,
				fromLoc.state as fromState,
				fromLoc.zip as fromZip,
				toLoc.name as toName,
				toLoc.address1 as toAddress,
				toLoc.city as toCity,
				toLoc.state as toState,
				toLoc.zip as toZip,
				bg.group_name as group_name
				from reservations res join trip_log tl on res.resid=tl.resid
				join login l on res.memberid=l.memberid
				join resources fromLoc on fromLoc.machid=res.machid
				join resources toLoc on toLoc.machid=res.toLocation
				left join billing_groups bg on bg.groupid=l.groupid
				where res.resid=?";
		$values = array($resid);
		$res = $this->db->getRow($query, $values);
		$res['accountName'] = $res['fname']." ".$res['lname'];
		$res['paxname'] = null;
		$notes = $this->parseNotes($res['notes']);
		if ($notes['name'])
			$res['paxname'] = $notes['name'];
		else
			$res['paxname'] = $res['fname']." ".$res['lname'];
		
		return $res;
	}
	function getDiscount($groupid = 0) {
		if (!$groupid) return 0;
		$query = "select discount from billing_groups where groupid=?";
		$values = array($groupid);
		$result = $this->db->getOne($query, $values);
		if (!$result) return 0;
		$discount = $result / 100;
		$discount = round($discount, 2);
		return $discount;
	}
	/*
	* Return summary of trip
	*/
	function get_trip_data($resid) {
		$query = "select res.resid, res.date, res.startTime, res.memberid,
			  toloc.name as toname, fromloc.name as fromname,
			  l.fname, l.lname, c.code as driver, t.vehicle,
			  car.code as carname, t.dispatch_status, t.delay,
			  cl.color, cl.type as model, cl.make, o.issueid,
			  dinfo.memberid as driverid
			  from reservations res join login l on res.memberid=l.memberid
			  join resources toloc on toloc.machid=res.toLocation
			  join resources fromloc on fromloc.machid=res.machid
			  join trip_log t on t.resid=res.resid
			  join codes c on c.id=t.driver
			  join codes car on car.id=t.vehicle
			  left join car_log cl on cl.vehicleid=t.vehicle
			  left join oti o on o.resid=res.resid
			  left join dinfo on dinfo.id=c.id
			  where res.resid=?";
		$vals = array($resid);
		$result = $this->db->getRow($query, $vals);
		return $result;
	}
	/*
	* Get last 5 trips
	*/
	function get_last_5($memberid, $resid, $name) {
		if ($name == 'Saturn') return false;
		$today = mktime(0,0,0);
		$last30 = $today - 60*60*24*30;
		$query = "select res.resid, res.date,
			  toloc.name as toname, fromloc.name as fromname
			  from reservations res 
			  join trip_log t on t.resid=res.resid
			  join resources toloc on toloc.machid=res.toLocation
			  join resources fromloc on fromloc.machid=res.machid
			  where res.memberid=?
			  and res.resid <> ?
			  and res.is_blackout <> 1
			  and res.date > $last30
			  AND t.pay_status in (23, 25) 
			  AND t.pay_type not in (30, 36) 
			  order by res.date desc, res.startTime asc
			  limit 5";
		$q = $this->db->prepare($query);
		$vals = array($memberid, $resid);
		$result = $this->db->execute($q, $vals);
		if ($result->numRows() == 0) return false;
		$return = array();
		while ($row = $result->fetchRow())
			$return[] = $row;
		return $return;
	}
	/*
	* Get user's favorite drivers
	*/
	function get_favs($memberid) {
		$vals = array($memberid);
		$query = "select f.memberid, f.driver as driverid, 
			  l.fname, l.lname
	  		  from favorite_drivers f join login l on f.driver=l.memberid
			  where f.memberid=?";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		if ($result->numRows() == 0) return false;
		$return = array();
		while ($row = $result->fetchRow())
			$return[] = $row;
		return $return;
	}
/**
	* Get array of clocked in drivers with codes ID as key
	* $main keeps the car string out of the menu for mainpage dispatch
	*/
	function get_active_drivers($main = false, $clockedin = false, $coast = true) {
		if ($_SESSION['coast']=='ca')
			$area = " AND c.area<>'MA' ";
		else if ($_SESSION['coast']=='ma')
			$area = " AND c.area<>'CA' ";
		else
			$area = '';
		if (!$coast) $area = '';
		if ($_SESSION['driver_sort'] == 'name')
			$sort = ' order by c.code';
		else if ($_SESSION['driver_sort'] == 'rank')
			$sort = ' order by d.rank DESC';
		else if ($_SESSION['driver_sort'] == 'car' && $_SESSION['driver_show'] == 'clockedin')
			$sort = ' order by dcar.code';
		else
			$sort = '';
		$carstr = '';
		if ($_SESSION['driver_show'] == 'clockedin' || $clockedin) {
			$query = "select c.id, c.code, d.rank, dcar.code as carstr
			  from codes c join dinfo d on c.id=d.id
			  join driver_log dl on d.memberid=dl.driverid
			  join codes dcar on dl.car=dcar.id
			  where c.status='active' and c.category='driver' $area
			  and dl.clockout is null
			  and dl.last_state not in (10,11,227,355,617,618,642,643)
			  $sort";
		} else {
			$query = "select c.id, c.code, d.rank
				  from codes c
				  join dinfo d on c.id=d.id
			  where c.status='active' and c.category='driver'
			  $area $sort";
		}
		$result = mysql_query($query);
		$return = array('1'=>'  NONE_ASSIGNED');
		while ($row = mysql_fetch_assoc($result)) {
			if (!$main && $row['carstr']) $carstr = ' - '.$row['carstr'];
			$return[$row['id']] = $row['code'].$carstr.' - '.$row['rank'];
		}
		return $return;
	}
	function delete_fave($memberid, $driverid) {
		$vals = array($memberid, $driverid);
		$query = "delete from favorite_drivers
			  where memberid=? and driver=?";
		$q = $this->db->prepare($query);
		return $this->check_for_dispatch_error($this->db->execute($q, $vals));
	}
	function add_fave($memberid, $driverid) {
		$vals = array($memberid, $driverid);
		$query = "INSERT INTO favorite_drivers (memberid, driver) VALUES (?, ?)";
		$q = $this->db->prepare($query);
		return $this->check_for_dispatch_error($this->db->execute($q, $vals));
	}
	function delete_reservation($resid){
		$vals = array($resid);
		$query = "delete from reservations
			  where resid=?";
		$q = $this->db->prepare($query);
		return $this->check_for_dispatch_error($this->db->execute($q, $vals));
	}
	function get_cur_nondispatched($area = 'MA') {
		$vals = array($area);
		$vals = array('MA');
		$query =  "select d.clockin as clockin, di.id as id,
		  c.code as car, c.id as carid,
		  l.fname, l.lname
		  from driver_log d join codes c on d.car=c.id
		  and c.area = ? 
		  and c.id <> 458
		  and d.clockout is null
		  join dinfo di on di.memberid=d.driverid
		  join login l on l.memberid=d.driverid";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		//CmnFns::diagnose($result);
		$return = array();
		if ($result->numRows() == 0) return $return; 
		while ($row = $result->fetchRow())
			$return[] = $row;
		return $return;
	}
	function getPaymentOptions($memberid) {
		// Check for billing group
		$vals = array($memberid);
		$query = "select l.groupid, b.group_name, b.type
			  from login l left join billing_groups b
			  on l.groupid=b.groupid
			  where l.memberid=?";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		$row = $result->fetchRow();
		//$return = array(''=>'Please select a payment option');
		$bgtype = $row['type'];
		$query = "select paymentProfileId, description, 
			  isDefault, lastFour
			  from paymentProfiles
			  where memberid=?
			  and status='active'
			  order by isDefault desc";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		while ($row = $result->fetchRow())
			$return[$row['paymentProfileId']] = $row['description'].' *'.$row['lastFour'];
		// Add direct bill to array
		if ($bgtype == 'd') $return['00'] = 'Direct Bill '.$row['group_name'];
		//if (!count($return))
		//	$return[''] = '';
		return $return;
	}
	function getPaymentProfile($paymentProfileId) {
		$query = "select * from paymentProfiles where paymentProfileId=?";
		$vals = array($paymentProfileId);
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		if (!$result->numRows()) return;
		
		return $result->fetchRow();
	}
	
	function update_dispatch($post) {
		// Determine which car this driver is in
		// driverid is memberid
		// Can we only do this query if we're showing clocked?
		$query = "select dl.car, dl.driverid
			  from driver_log dl join dinfo d on dl.driverid=d.memberid
			  where d.id=" . $post['driver'] . "
			  and dl.clockout is null";
		
		$qresult = mysql_query($query);
		$row = mysql_fetch_assoc($qresult);
		$driverid = $row['driverid'];	
		//echo $query;
		// Only override the car if we're showing clocked in, because
		// then we won't have one
		if ($_SESSION['driver_show']=='clockedin') {
			if ($row['car']) $post['vehicle'] = $row['car'];
		}
		if ($driverid) {
			$query = "update driver_log set last_state=? where driverid=? and clockout is null";
			$values = array($post['dispatch_status'], $driverid);
			$q = $this->db->prepare($query);
			$result = $this->db->execute($q, $values);
			$this->check_for_dispatch_error($result);
		}
		$query = 'update trip_log set resid=?,last_mod_time=NOW()';
		$values = array($post['resid']);
		
		foreach($post as $key => $val) {
			if($key != 'SMSText' && $key != 'emailDriver' && $key != 'resid' && $key != 'fn' && $key != 'submit' && $key != 'custSmsPhone' && $key != 'smsCustomer') {
				$query = $query . ', ' . $key . '=?';
				array_push($values, $val);
			}
		}
		$query = $query . ' where resid = ? ';
		array_push($values, $post['resid']);
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $values);
		$this->check_for_dispatch_error($result);
		/*
		* add to log
		*/
		$this->log_touch($post, 'd');
		// If one of the 4 cancel states
		if ($post['dispatch_status']==9 || ($post['dispatch_status'] >= 13 && $post['dispatch_status'] <= 15)) {
			$query = "update reservations set moddedBy='".$_SESSION['sessionID']."', modified=".time()." where resid='".$post['resid']."'";
			$result = mysql_query($query);
			$this->insertHistory($post['resid'], $_SESSION['sessionID'], '', $post['dispatch_status']);
		}
		if ($post['dispatch_status']==9 || ($post['dispatch_status'] >= 12 && $post['dispatch_status'] <= 15)) {
			$query = "delete from flights where rsid='".$post['resid']."'";
			$result = mysql_query($query);
		}
       		$cars = $this->get_cars_array();
       		$vcars = $this->get_vcars_array();
		$acct = 'CI00015446'; 
		$email = urlencode('msobecky@gmail.com'); 
		$pass = '27changeme';
		if($post['emailDriver'] == 'on') {
			$carsnames = $this->get_carsnames_array();
			//$res = new Reservation($post['resid']);
			//$res->load_by_id();
			//if($post['vehicle'] < 498) {
			//echo $driverEmail;
			$mailer = new PHPMailer();
           		$mailer->ClearAllRecipients();
           		$mailer->AddBCC('dispatch@planettran.com', 'Dispatch');
           		$vcarid = $post['vehicle'];
			$scarid = $post['vehicle'];
			$vehicle = $carsnames[$vcarid];
		
			//send verizon fones text messages for texting
			//if($scarid == 622) {
			if(0) {
				$driverEmail = $vcars[$scarid] . "@messaging.sprintpcs.com";
				$driverEmail1 = "9783756446" . "@messaging.sprintpcs.com";
				$driverEmail2 = "6177564554" . "@messaging.sprintpcs.com";
				$mailer->AddAddress($driverEmail, $vehicle);
				$mailer->AddAddress($driverEmail2, $vehicle);
				$mailer->AddAddress($driverEmail1, $vehicle);
			}
			$driverEmail = $vcars[$vcarid] . "@vtext.com";
			//if ($vcarid == 173)
			//	$driverEmail = $vcars[$vcarid]."@messaging.sprintpcs.com";
			$mailer->AddAddress($driverEmail, $vehicle);
	
			$mailer->FromName = $post['resid'];
       		    	$mailer->From = "mobile@planettran.com";
        	   	//$mailer->Subject = "Planettran Billing";
           		$mailer->Body = $post['SMSText'];
           		$mailer->Send();
		
			/* Send driver texts through red oxygen */
			/* uncomment the next 6 lines to activate */
			/*
			
			$driverPhone = $vcars[$vcarid];
			$driverMessage = urlencode($post['SMSText']);
			$url = "http://sms1.redoxygen.net/sms.dll?Action=SendSMS&Accountid=$acct&Email=$email&Password=$pass&Recipient=$driverPhone&Message=$driverMessage"; 
			$h = fopen($url, "r");
			fclose($h);
			*/		
			// if ($post['vehicle'] >= 365 && $post['vehicle'] <= 367) {
			if (false) {
				$lines = explode("\n", $post['SMSText']);
				$lines = array_slice($lines, 4);
				$msg = "<RID>".$post['resid']."<RID>\n";
				$msg .= implode("\n", $lines);
				$mailer->Body = $msg;	
           			$mailer->Send();
			}
	   	 }
		$phone = preg_replace('/[^0-9]/','',$post['custSmsPhone']);
		if($post['smsCustomer'] == 'on' && is_numeric($phone)) {
			$driverPhone = $vcars[$post['vehicle']];
			$driverPhone = substr($driverPhone, 0, 3)."-".substr($driverPhone, 3, 3)."-".substr($driverPhone, 6, 4);
			$message = "Dear PlanetTran passenger, your driver is waiting for you in the limo lot. Please call $driverPhone or dispatch at 888 756 8876 when you are clear with bags.";
			$message = urlencode($message); 
			$url = "http://sms1.redoxygen.net/sms.dll?Action=SendSMS&Accountid=$acct&Email=$email&Password=$pass&Recipient=$phone&Message=$message"; 
			
			$h = fopen($url, "r");
			fclose($h);
		}
		if ($post['notes']) 
			$this->insertHistory($post['resid'],$_SESSION['sessionID'],$post['notes']);
	    return true;
	}
	
	function log_touch($trip = array(), $type = 'u') {
		//CmnFns::diagnose($trip);
		//return;
	
		$createdBy = null;
		if ($_SESSION['sessionID']) $createdBy = $_SESSION['sessionID'];
		$vals = array(	$type,
				$createdBy,
				$trip['resid'],
				$trip['dispatch_status'],
				$trip['pay_status'],
				$trip['pay_type'],
				$trip['driver'],
				$trip['vehicle'],
				$trip['distance'],
				$trip['base_fare'],
				$trip['discount'],
				$trip['tolls'],
				$trip['unpaid_tolls'],
				$trip['other'],
				$trip['total_fare'],
				$trip['invoicee'],
				$trip['notes'],
				$trip['cc'],
				$trip['authorization'],
				$trip['delay']
				);
		//array_unshift($vals, $type, $createdBy);
		$query = "insert into trip_log_log
				(type, createdBy, resid, dispatch_status, pay_status, pay_type, driver, vehicle, distance, base_fare, discount, tolls, unpaid_tolls, other, total_fare, invoicee, notes, cc, authorization, delay)
				values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$q = $this->db->prepare($query);
		$result = $this->db->execute($q, $vals);
		//CmnFns::diagnose($trip);
		//CmnFns::diagnose($vals);
		//$this->check_for_error($result);
	}
	
	function get_cars_array() {
		$cars = array();
		$cars['44'] = "6177788985";
		$cars['45'] = "6176714346";
		$cars['252'] = "6176714347";
		$cars['253'] = "6177788245";
		$cars['283'] = "6177788630";
		$cars['284'] = "6177788275";
		$cars['285'] = "6176714345";
		$cars['65'] = "6173078994";
		$cars['354'] = "6179183702";
		$cars['83'] = "6176714120";
		$cars['172'] = "8578293802";
		$cars['110'] = "6177784090";
		$cars['115'] = "6177783921";
		$cars['116'] = "6177784041";
		$cars['136'] = "6177783601";
		$cars['137'] = "6177783632";
		$cars['173'] = "6177784003";
		$cars['181'] = "6177784083";
		$cars['182'] = "6177784092";
		$cars['214'] = "6177784307"; 
                $cars['215'] = "6177784321";
                $cars['216'] = "6177784325";
                $cars['245'] = "6177783663";
                $cars['246'] = "6177783789";
                $cars['247'] = "6177784001";
                $cars['217'] = "6177784024";
                $cars['218'] = "6177784195";
                $cars['219'] = "6177784283";
                $cars['220'] = "6177783850";
                $cars['221'] = "6177784170";
                $cars['303'] = "6177783875";
                $cars['304'] = "6177783817";
                $cars['305'] = "6177783844";
                $cars['287'] = "6505378141";
                $cars['288'] = "6505378124";
                $cars['365'] = "6177783660";
                $cars['366'] = "6177783740";
                $cars['367'] = "6177783748";
                #$cars['390'] = "6505378190";
                $cars['390'] = "4152796779";
                $cars['391'] = "6177783582";
		$cars['482'] = "6176714668";
		$cars['483'] = "6176714120";
		$cars['487'] = "6177783720";
		//$cars['498'] = "8579988502";
		//$cars['499'] = "8579988601";
                $cars['498'] = "6177784325";
                $cars['499'] = "6177783663";
		$cars['502'] = "6177783865";
		$cars['503'] = "6179183702";
		return $cars;
	}
	
	function get_vcars_array() {
		$vcars = array();
		$vcars['354'] = "6179998654";//PTV01
		$vcars['109'] = "6176997398";//PT014
                $vcars['110'] = "6179996170";//PT015
                //$vcars['110'] = "6179998664";//PT015
		$vcars['116'] = "6179998646";//PT017
		$vcars['136'] = "6179998633";//PT018
		$vcars['137'] = "6175996632";//PT019
		$vcars['173'] = "6179998637";//PT022 // Matt's dispatch phone
		//$vcars['173'] = "6177564554";//PT022 // sprint test number
                $vcars['303'] = "6179998662";//PT036
                //$vcars['304'] = "6179998655";//PT037
                //$vcars['305'] = "6179998661";//PT038
                $vcars['365'] = "6179998635";//PT039
                $vcars['366'] = "6179998652";//PT040
                #$vcars['367'] = "6179998638";//PT041
                $vcars['367'] = "6179998622";//PT041
		$vcars['482'] = "6179998636";//PT042
		$vcars['483'] = "6179998634";//PT043
		$vcars['487'] = "6179998658";//PT044
                $vcars['498'] = "6179998336";//PT045
                $vcars['499'] = "6179998339";//PT046
		$vcars['502'] = "6179998343";//PT047
		$vcars['172'] = "6179998645";//PT021
		//$vcars['172'] = "6178721573";//PT021
		$vcars['508'] = "6179998623";//PT049
		$vcars['528'] = "6179998639";//PT050
		$vcars['529'] = "6179998663";//PT051
                $vcars['536'] = "6179998651";//PT052
		$vcars['537'] = "6179998653";//PT053
		$vcars['538'] = "6179998624";//PT054
		$vcars['556'] = "6179998659";//PT055
		$vcars['557'] = "6179998633";//PT056
		$vcars['558'] = "6179998657";//PT057
		$vcars['559'] = "6179998648";//PT058
		$vcars['560'] = "6179998649";//PT059
		$vcars['565'] = "6179998647";//PT060
		$vcars['566'] = "6179998644";//PT061
		$vcars['570'] = "6179998650";//PT062
		$vcars['683'] = "6179998655";//PT063
                $vcars['713'] = "6179998661";//PT064
		$vcars['714'] = "6179998630";//PT065
                $vcars['731'] = "6179998664";//PT066
		$vcars['744'] = "6179998638";//PT067
		$vcars['745'] = "6179998669";//PT068
		$vcars['748'] = "6176807035";//TEST1
		$vcars['749'] = "6179395743";//TEST2
		$vcars['622'] = "6179998656";//PT250
                
		//SFcars
                $vcars['217'] = "6179998341";//SF001
                $vcars['218'] = "6179998631";//SF002
                //$vcars['219'] = "6179998633";//SF003
                //$vcars['220'] = "6179998665";//SF004
                $vcars['221'] = "6179998627";//SF005
                $vcars['287'] = "6179998631";//SF006
                $vcars['288'] = "6179998632";//SF007
                $vcars['390'] = "6179998626";//SF009
		$vcars['391'] = "6179998628";//SF010
		$vcars['684'] = "6179998627";//SF011
		$vcars['685'] = "6179998641";//SF012
		$vcars['723'] = "6179998640";//SF013
		$vcars['724'] = "6179998625";//SF014
                $vcars['738'] = "6179998665";//SF015
                $vcars['739'] = "6179998629";//SF016
                $vcars['740'] = "6179998667";//SF017
		return $vcars;
	}
	function get_values($id, $category)
	{
		$query = "SELECT code FROM codes WHERE id=" . $id . " AND category='" . $category . "'";
		$result = mysql_query($query);
		$total = mysql_num_rows($result);
		if($total > 0)
		{
			$row = mysql_fetch_assoc($result);
			return $row['code'];
		}
	
		return false;	
	}
	
	function get_dispatch_driver($resid)
	{
		$query = "SELECT * FROM trip_log WHERE resid = '" . $resid . "'";
		$result = mysql_query($query);
		$total = mysql_num_rows($result);
		
		if($total > 0)
		{
			$row = mysql_fetch_assoc($result);
			$row['dispatch_status'] = $this->get_values($row['dispatch_status'], 'dispatch_status');
			$row['pay_status'] = $this->get_values($row['pay_status'], 'pay_status');
			$row['pay_type'] = $this->get_values($row['pay_type'], 'pay_type');
			$row['driver'] = $this->get_values($row['driver'], 'driver');
			$row['vehicle'] = $this->get_values($row['vehicle'], 'vehicle');
		
			return $row;
		}
		
		return false;		 
		
	}
}
?>
