<?php
/**
* Administrative class provides all functions for managing
*  data and settings in phpScheduleIt
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 05-22-04
* @package Admin
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');
/**
* AdminDB class
*/
include_once('db/AdminDB.class.php');
/**
* Auth class
*/
include_once('Auth.class.php');
/**
* PHPMailer
*/
//include_once('PHPMailer.class.php');
/**
* Administrative template functions
*/
include_once(BASE_DIR . '/templates/admin.template.php');


class Admin {
	/*
	Tools array has tool name as index, and array of title and function call as value
	*/
	var $tools = array (
					'schedules'	=> array ('Manage Schedules', 'manageSchedules'),
					'users' 	=> array ('Manage Users', 'manageUsers'),
					'resources'	=> array ('Manage Resources', 'manageResources'),
					'perms'		=> array ('Manage User Training', 'managePerms'),
					'reservations'	=> array ('Manage Reservations', 'manageReservations'),
					'email'		=> array ('Email Users', 'manageEmail'),
					'export'	=> array ('Export Database Data', 'export_data'),
					'pwreset'	=> array ('Reset Password', 'reset_password')
					);
	var $pager;
	var $db;
	var $tool;
	var $is_error = false;
	var $error_msg;
	
	/**
	* Admin class constructor
	* Sets up GUI and gets the current tool
	*/
	function Admin($tool) {
		$this->pager = CmnFns::getNewPager();
		$this->pager->setTextStyle('font-size: 10px;');
		$this->pager->setTbClass('textbox');
		
		$this->db = new AdminDB();
		// Make sure its a proper tool
		if (!isset($this->tools[$tool])) {
			$this->is_error = true;
			$this->error_msg = translate('Could not determine tool. Please return to My Control Panel and try again later.');
		}
		else
			$this->tool = $this->tools[$tool];
	}
	
	/**
	* Returns whether an error occured or not
	* @param none
	* @return boolean whether error occured
	*/	
	function is_error() {
		return $this->is_error;
	}
	
	/**
	* Returns the last error message given
	* @param none
	* @return string last error message
	*/
	function get_error_msg() {
		return $this->error_msg;
	}
	
	/**
	* Execute the proper function based on the tool
	* @param none
	*/
	function execute() {
		eval('$this->' . $this->tool[1] . '();');
	}
	
	/**
	* Interface for managing schedules
	* @param none
	*/
	function manageSchedules() {
		$this->listSchedulesTable();		// List resources and allow deletion
		$this->editScheduleTable();			// Enter/display info about a resource
	}
	
	/**
	* Prints out list of current schedules
	* @param none
	*/
	function listSchedulesTable() {
		$pager = $this->pager;
	
		$num = $this->db->get_num_admin_recs('schedules');	// Get number of records
		
		$pager->setTotRecords($num);				// Pager method calls		
		$orders = array('scheduleTitle');
		
		$schedules = $this->db->get_all_admin_data($pager, 'schedules', $orders, true);
		
		print_manage_schedules($pager, $schedules, $this->db->get_err());	// Print table of resources
				
		$pager->printPages();						// Print pages
	}
	
	
	/**
	* Interface to add or edit schedule information
	* @param none
	*/
	function editScheduleTable() {
		
		$edit = (isset($_GET['scheduleid']));	// Determine if the form should contain values or be blank
	
		$rs = array();
		
		if ($edit)							// Validate machid
			$scheduleid =  trim($_GET['scheduleid']);
			
		if ($edit) {						// If this is an edit, get the resource information from database
			$rs = $this->db->get_schedule_data($scheduleid);
		}
		if (isset($_SESSION['post'])) {
			$rs = $_SESSION['post'];
		}
		
		print_schedule_edit($rs, $edit, $this->pager);
		
		unset($_SESSION['post'], $rs);
	}
	
	/**
	* Interface for managing users
	* Provides interface for viewing user information
	* and deleting users and their reservations from the database
	* @param none
	*/
	function manageUsers() {
		$pager = $this->pager;
		$orders = array('lname', 'email', 'institution');
		
		
		if (isset($_GET['searchUsers'])) {					// Search for users or get all users?
			$fname = trim($_GET['firstName']);
			$lname = trim($_GET['lastName']);
			$num   = $this->db->get_num_search_recs($fname, $lname);
			$pager->setTotRecords($num);
			$users = $this->db->search_users($fname, $lname, $pager, $orders);
		}
		else {		// Default	
			$num = $this->db->get_num_admin_recs('login');	// Get number of records
			$pager->setTotRecords($num);					
			$users = $this->db->get_all_admin_data($pager, 'login', $orders, true);
		}		
		print_manage_users($pager, $users, $this->db->get_err());		// Print table of users
				
		$pager->printPages();						// Print pages
	}
	
	
	/**
	* Interface for managing resources
	* Provides an interface for viewing resource information,
	* adding, modifiying and deleting resource information
	* and associated reservations from database
	* @param none
	*/
	function manageResources() {
			
		$this->listResourcesTable();		// List resources and allow deletion
		$this->editResourceTable();			// Enter/display info about a resource
	}
	
	
	/**
	* Prints out list of current resources
	* @param none
	*/
	function listResourcesTable() {
		$pager = $this->pager;
	
		$num = $this->db->get_num_admin_recs('resources');	// Get number of records
		
		$pager->setTotRecords($num);				// Pager method calls		
		$orders = array('name', 'machID');
		
		$resources = $this->db->get_all_resource_data($pager, $orders);
		
		print_manage_resources($pager, $resources, $this->db->get_err());	// Print table of resources
				
		$pager->printPages();						// Print pages
	}
	
	
	/**
	* Interface to add or edit resource information
	* @param none
	* @see printResourceEdit()
	*/
	function editResourceTable() {
		
		$edit = (isset($_GET['machid']));	// Determine if the form should contain values or be blank
	
		$rs = array();
		
		if ($edit)							// Validate machid
			$machid =  trim($_GET['machid']);
			
		if ($edit) {						// If this is an edit, get the resource information from database
			$rs = $this->db->get_resource_data($machid);
		}
		if (isset($_SESSION['post'])) {
			$rs = $_SESSION['post'];
		}
		
		$scheds = $this->db->get_table_data('schedules', array('scheduleid', 'scheduleTitle'), array('scheduleTitle'));
		
		print_resource_edit($rs, $scheds, $edit, $this->pager);
		
		unset($_SESSION['post'], $rs);
	}
	
	
	/**
	* Interface for managing user training
	* Provide interface for viewing and managing
	*  user training information
	* @param none
	*/
	function managePerms() {
		
		$user = new User($_GET['memberid']);	// User object
		
		$rs = $this->db->get_mach_ids();
		
		print_manage_perms($user, $rs, $this->db->get_err());
		unset($user);
	}
	
	
	/**
	* Interface for managing reservations
	* Provide a table to allow admin to modify or delete reservations
	* @param none
	*/
	function manageReservations() {
		$pager = $this->pager;
			
		$num = $this->db->get_num_admin_recs('reservations');	// Get number of records		
		$pager->setTotRecords($num);							// Pager method calls
		
		$orders = array('date', 'name', 'lname', 'startTime', 'endTime');
		$res = $this->db->get_reservation_data($pager, $orders);
		
		print_manage_reservations($pager, $res, $this->db->get_err());		// Print table of users
			
		$pager->printPages();									// Print pages
	}	
	
	/**
	* Wrapper function to call proper email function
	* @param none
	*/
	function manageEmail() {
		if (isset($_POST['previewEmail'])) {		// Preview email
			$_SESSION['sub'] = !empty($_POST['subject']) ? stripslashes(trim($_POST['subject'])) : 'No subject';
			$_SESSION['msg'] = !empty($_POST['message']) ? stripslashes(trim($_POST['message'])) : 'No message';
			$_SESSION['usr'] = isset($_POST['emailIDs']) ? $_POST['emailIDs'] : array();
			preview_email($_SESSION['sub'], nl2br($_SESSION['msg']), $_SESSION['usr']);
		}
		else if (isset($_POST['sendEmail']))		// Send email
			$this->sendMessage();
		else
			$this->list_email_users();				// Default, pick users/message
	}
	
	/**
	* Prints out GUI list to of email addresses
	* Prints out a table with option to email users,
	*  and prints form to enter subject and message of email
	* @param none
	*/
	function list_email_users() {
		$sub = isset($_SESSION['sub']) ? $_SESSION['sub'] : 'No subject';
		$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : 'No message';
		$usr = isset($_SESSION['usr']) ? $_SESSION['usr'] : array();
		$users = $this->db->get_user_email();
		print_manage_email($users, $sub, $msg, $usr, $this->db->get_err());
	}
	
	/**
	* Send email message to users
	* Loop through array of emails and send HTML mail to each one
	*  printing success or failure message
	* @param none
	*/
	function sendMessage() {
		global $conf;
		$success = $fail = array();
		$isWin32 = strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'win32');
		
		$usr = $_SESSION['usr'];
		$msg = $_SESSION['msg'];
		$sub = $_SESSION['sub'];
		$to = $conf['app']['adminEmail'];
		//print_r($usr);
		//die($usr);
		$mailer = new PHPMailer();
		$mailer->AddAddress($to);
		$mailer->FromName = $conf['app']['title'];
		$mailer->From = $to;
		// If emailAdmin is set to true, put them in cc
		for ($i = 0; $i < count($usr); $i++) {
			if ($isWin32 !== false)
				$mailer->AddBCC($usr[$i]);
			else
				$mailer->AddAddress($usr[$i]);
		}
		$mailer->Subject = $sub;
		$mailer->Body = $msg;
		$mailer->IsHTML(false);
		
		if ($mailer->Send())
			$success = true;
		else
			$success = false;
		
		print_email_results($sub, $msg, $success);
		unset($_SESSION['usr'], $_SESSION['msg'], $_SESSION['sub'], $usr, $sub, $msg);
	}
	
	/**
	* Call the function to show table data or to show the resulting data
	* @param none
	*/
	function export_data() {
		if (is_array($_POST) && isset($_POST['submit'])) {		// The form is submitted, print out the selected data
			$form = $_POST;
			$xml = ($form['type'] == 'xml');					// XML or CSV format
			
			// Build the query for each table to output
			foreach ($form as $key => $val) {
				if ($key == 'table') {		// table[] checkbox
					for ($i = 0; $i < count($form[$key]); $i++) {
						$table_name = $form[$key][$i];
						$query = $this->build_export_query($form, $table_name);
						$data = $this->get_export_data($query);
						start_exported_data($xml, $table_name);
						print_exported_data($data, $xml);
						end_exported_data($xml, $table_name);
					}			
				}
			}
		}
		else {
			$tables = $this->db->db->getListOf('tables');
			for ($i = 0; $i < count($tables); $i++) {
				$result = $this->db->db->getRow('select * from ' . $this->db->get_table($tables[$i]));
				if (count($result) > 0) {
					foreach ($result as $field => $v)
						$fields[$tables[$i]][] = $field;	// Assignment is done in the loop
				}
			}
			show_tables($tables, $fields);
		}
	}

	
	/**
	* Builds the query to retrieve specific data from database
	* @param array $form array of all form data
	* @return the query to execute
	*/
	function build_export_query($form, $table_name) {
								
		//$table_name = $form[$key][$i];
		$query = 'select';
		for ($j = 0; $j < count($form['table,' . $table_name]); $j++) {
			if ($form['table,' . $table_name][$j] == 'all')
				$query .= ' * ';
			else		
				$query .= ' ' . $form['table,' . $table_name][$j] . ',';
		}
		// Trim off last char (it will be a space or a comma)
		$query = substr($query, 0, strlen($query) - 1) . ' from ' . $this->db->get_table($table_name);
		
		return $query;	
	}
	
	/**
	* Returns the data to export in an array
	* @param string $query query to execute
	*/
	function get_export_data($query) {
		$data = array();
		$result = $this->db->db->query($query);
		while ($rs = $result->fetchRow())
			$data[] = $rs;
	
		return $data;
			
	}
	
	/**
	* Prints a form to reset a password for a user
	* @param none
	*/
	function reset_password() {
		$user = new User($_GET['memberid']);	// User object
		
		print_reset_password($user);
	}
}
?>
