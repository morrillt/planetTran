<?php
/**
* Update program for phpScheduleIt
*
* This will populate some fields and fix a bug for version 0.9.3/0.9.9 to 1.0.0
*
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 09-10-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

/**
* Please set this value to a user who has permission to alter tables
*/
$root_user = 'root';
/**
* Please set this value to the password of the user who has permission to alter tables
*/
$root_password = 'password';
/**
* Please set this to true or false depending on if this is a manual update or not
* If set to false, then the $root_user and $root_password do not need to be supplied
*/
$manual = false;

/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/../..');
/**
* Template class
*/
include_once(BASE_DIR . '/lib/Template.class.php');


$t = new Template('phpScheduleIt Update: 0.9.3/0.9.9 to 1.0.0', 2);

// Print HTML headers
$t->printHTMLHeader();

?>
<div align="center">
<?
CmnFns::print_language_pulldown();
?>
</div>
<?
if (!isset($_GET['go'])) {
	CmnFns::do_error_box(translate('This will populate the required fields for phpScheduleIt 1.0.0 and patch a data bug in 0.9.9.')
		. '<br />' . translate('There is no way to undo this action'), '', false); 
	echo '<h4 align="center"><a href="' . $_SERVER['PHP_SELF'] . '?go=y">' . translate('Click to proceed') . '</a></h4>';
}
else {
	updateVersion();
}
	$t->printHTMLFooter();
	
function updateVersion() {
	global $conf;
	global $root_user;
	global $root_password;
	global $manual;
	
	$failed = false;
	
	$dbe = new DBEngine();
	echo '<h5 align="center">Update Log</h5>';
	
	if (!$manual) {
		$dsn = $conf['db']['dbType'] . '://' 
				. $root_user 
				. ':' . $root_password
				. '@' . $conf['db']['hostSpec'] . '/' . $conf['db']['dbName'];
	
		// Make persistant connection to database
		$db = DB::connect($dsn);
	
		// If there is an error, print to browser, print to logfile and kill app
		if (DB::isError($db)) {
			die ('Error connecting to database: ' . $db->getMessage() );
		}
		
		$query = array (
						array ('ALTER TABLE ' . $dbe->get_table('reservations') . ' CHANGE COLUMN is_blackout is_blackout smallint(1) NOT NULL DEFAULT 0',
								'Column is_blackout changed'),
						array ('ALTER TABLE ' . $dbe->get_table('resources') . ' CHANGE COLUMN autoAssign autoAssign smallint(1)',
								'Column autoAssign changed'),
						array ('ALTER TABLE ' . $dbe->get_table('schedules') . ' CHANGE COLUMN usePermissions usePermissions smallint(1)',
								'Column usePermissions changed'),
						array ('ALTER TABLE ' . $dbe->get_table('schedules') . ' CHANGE COLUMN isHidden isHidden smallint(1)',
								'Column isHidden changed'),
						array ('ALTER TABLE ' . $dbe->get_table('schedules') . ' CHANGE COLUMN showSummary showSummary smallint(1)',
								'Column showSummary changed'),
						array ('ALTER TABLE ' . $dbe->get_table('schedules') . ' CHANGE COLUMN isDefault isDefault smallint(1)',
								'Column isDefault changed')
						);
						
		for ($i = 0; $i < count($query); $i++) {
			$result = $db->query($query[$i][0]);
			if (!$dbe->check_for_error($result)) {
				echo "<p>{$query[$i][1]}</p>";
			}
			else {
				$failed = true;
			}
		}
	}
	$count = $dbe->db->getOne('SELECT count(*) FROM schedules');
	if ($count <= 0) {
		echo '<h3>Creating default schedule...</h3>';
		$scheduleid = $dbe->get_new_id();
		$result = $dbe->db->query('INSERT INTO schedules VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($scheduleid,'default',480,1200,30,12,0,7,0,0,1,$conf['app']['adminEmail'],1,0));
		$dbe->check_for_error($result);
		echo '<h3>Success</h3>';
		
		echo '<h3>Assigning all current data to default schedule...</h3>';
		$result = $dbe->db->query('UPDATE reservations SET scheduleid = ?', array($scheduleid));
		$dbe->check_for_error($result);
		$result = $dbe->db->query('UPDATE resources SET scheduleid = ?', array($scheduleid));
		$dbe->check_for_error($result);
		echo '<h3>Success</h3>';
	}

	runPatch();
	
	if (!$failed) {
		echo '<h3>' . translate('Successful update') . '</h3>';
	}
	else {
		echo '<h5>' . translate('There were errors during the install.') . '</h5>';
	}
	
	echo '<br/>' . translate('Thank you for using phpScheduleIt');
}

function runPatch() {
	global $conf;
	$dbe = new DBEngine();
	echo '<h3>0.9.9 data patch</h3>';
	
	// Delete memberids
	$vals = array();
	$sql = 'SELECT p.memberid FROM ' . $dbe->get_table('permission') . ' as p LEFT JOIN ' . $dbe->get_table('login') . ' AS l USING (memberid) WHERE l.memberid IS NULL';
	$result = $dbe->db->query($sql);
	$dbe->check_for_error($result);
	
	while ($rs = $result->fetchRow()) {
		$vals[] = $rs['memberid'];
	} 
	
	$sql = 'DELETE FROM ' . $dbe->get_table('permission') . ' WHERE memberid IN (' . $dbe->make_del_list($vals) . ')';
	$result = $dbe->db->query($sql);
	$dbe->check_for_error($result);

	// Delete machids
	$vals = array();
	$sql = 'SELECT p.machid FROM ' . $dbe->get_table('permission') . ' as p LEFT JOIN ' . $dbe->get_table('resources') . ' AS r USING (machid) WHERE r.machid IS NULL';
	$result = $dbe->db->query($sql);
	$dbe->check_for_error($result);
	
	while ($rs = $result->fetchRow()) {
		$vals[] = $rs['memberid'];
	} 
	
	$sql = 'DELETE FROM ' . $dbe->get_table('permission') . ' WHERE memberid IN (' . $dbe->make_del_list($vals) . ')';
	$result = $dbe->db->query($sql);
	$dbe->check_for_error($result);				
	
	echo '<h3>' . translate('Patch completed successfully') . '</h3>';
}
?>