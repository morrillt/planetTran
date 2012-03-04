<?php
/**
* Setup program for phpScheduleIt
*
* This will allow a user with root database privleges to
* automatically set up the required database and its
* tables.  It will also populate any necessary tables.
*
* It uses PEAR::DB to prepare and execute the queries,
* making them database independent.
*
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 08-11-04
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
* DBEngine class
*/
include_once(BASE_DIR . '/lib/DBEngine.class.php');
/**
* Template class
*/
include_once(BASE_DIR . '/lib/Template.class.php');

@session_start();	// Start the session

$failed = false;

$t = new Template('phpScheduleIt ' . translate('Setup'), 1);
$t->printHTMLHeader();
doPrintHeader();

if (checkConfig()) {

	if (isset($_POST['login'])) {
		setVars();
		doLogin();
	}
	else if (isset($_POST['create'])) {
		$db = dbConnect();
		doCreate();
		doFinish();
	}
	else
		doPrintForm();
}

$t->printHTMLFooter();


/**
* Prints html header
* @param none
*/
function doPrintHeader() {
	global $conf;
?>
<p align="center">
<?CmnFns::print_language_pulldown()?>
</p>
<h3 align="center">phpScheduleIt v<?=$conf['app']['version']?></h3>
<?
}

/**
* Prints out login form
* @param none
*/
function doPrintForm() {
	global $conf;
?>
<h3 align="center"><?=translate('Please log into your database')?></h3>
<form name="login" id="login" method="post" action="<?=$_SERVER['PHP_SELF']?>">
  <table width="60%" border="0" cellspacing="3" cellpadding="0" align="center" style="border: solid 1px #333333; background-color: #fafafa;">
    <tr>
      <td><?=translate('Enter database root username')?></td>
      <td><input type="text" name="user" class="textbox" /></td>
    </tr>
    <tr>
      <td><?=translate('Enter database root password')?></td>
      <td><input type="password" name="password" class="textbox" /></td>
    </tr>
    <tr>
      <td><input type="submit" name="login" value="<?=translate('Login to database')?>" class="button" /></td>
    </tr>
  </table>
  <br />
  <table width="80%" align="center" cellpadding="3" cellspacing="0" border="0" style="font-family: Verdana, Arial; font-size: 12px; background-color: #ffffff; border: solid 1px #DDDDDD">
    <tr>
      <td>
	  <ul>
	  <li><?=translate('Root user is not required. Any database user who has permission to create tables is acceptable.')?></li>
	  <li><?=translate('This will set up all the necessary databases and tables for phpScheduleIt.')?></li>
	  <li><?=translate('It also populates any required tables.')?></li>
	  <? if ($conf['db']['drop_old']) echo '<li>' . translate('Warning: THIS WILL ERASE ALL DATA IN PREVIOUS phpScheduleIt DATABASES!') . '</li>';?>
	  </ul></td>
    </tr>
  </table>
</form>
<?
}

/**
* Checks to make sure necessary fields are set in the config file
* @param none
* @return whether all necessary fields are set
*/
function checkConfig() {
	global $conf;
	switch ($conf['db']['dbType']) {	// Check database type
		case 'mysql' :;
		case 'pgsql' :;
		case 'ibase' :;
		case 'msql' :;
		case 'mssql' :;
		case 'oci8' :;
		case 'odbc' :;
		case 'sybase' :;
		case 'ifx' :;
		case 'fbsql' :;
			break;
		default :
			echo translate('Not a valid database type in the config.php file.');
			return false;
			break;
	}

	if (empty($conf['db']['dbUser'])) {		// Check database user
		echo translate('Database user is not set in the config.php file.');
		return false;
	}

	if (empty($conf['db']['dbPass'])) {		// Check database password
		echo translate('Database user password is not set in the config.php file.');
		return false;
	}

	if (empty($conf['db']['dbName'])) {		// Check database name
		echo translate('Database name not set in the config.php file.');
		return false;
	}

	return true;
}

/**
* Verifies that the user entered information and sets up session variables
* @param none
*/
function setVars() {
	$_SESSION['user'] = stripslashes(trim($_POST['user']));
	$_SESSION['password'] = stripslashes(trim($_POST['password']));
}

/**
* Create a connection to the database using user supplied data
* @param none
*/
function doLogin() {
	global $conf;
    // Data Source Name: This is the universal connection string
    // See http://www.pear.php.net/manual/en/package.database.php#package.database.db
    // for more information on DSN
    $dsn = $conf['db']['dbType'] . '://'
			. $_SESSION['user']
			. ':' . $_SESSION['password']
			. '@' . $conf['db']['hostSpec'];

    // Make connection to database
    $db = DB::connect($dsn);

    // If there is an error, print to browser, print to logfile and kill app
    if (DB::isError($db)) {
        die ('Error connecting to database: ' . $db->getMessage() );
    }
	else {
		echo '<h4 align="center">' . translate('Successfully connected as') . ' ' . $_SESSION['user'] . "</h4>\n"
			. "<form name=\"create\" id=\"create\" method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">\n"
			. "<input type=\"submit\" name=\"create\" value=\"" . translate('Create tables') . "\" class=\"button\" />\n"
			. "</form>\n";
	}
}

/**
* Create and return a connection to the database
* Requires that setVars() has been called by the user
* loggin in
* @param none
*/
function dbConnect() {
	global $conf;
    // Data Source Name: This is the universal connection string
    // See http://www.pear.php.net/manual/en/package.database.php#package.database.db
    // for more information on DSN
    $dsn = $conf['db']['dbType'] . '://'
			. $_SESSION['user']
			. ':' . $_SESSION['password']
			. '@' . $conf['db']['hostSpec'];

    // Make persistant connection to database
    $db = DB::connect($dsn);

    // If there is an error, print to browser, print to logfile and kill app
    if (DB::isError($db)) {
        die ('Error connecting to database: ' . $db->getMessage() );
    }

    return $db;
}


/**
* Create the database and the tables in it
* - Requires an external file with sql commands
* @param none
*/
function doCreate() {
	global $db;
	global $conf;
	
	//$scheduleid = DBEngine::get_new_id();

	$sqls = array (
					// Create new database
					array ("create database {$conf['db']['dbName']}", 'Creating database'),
					// Select it
					array ("use {$conf['db']['dbName']}", 'Selecting database'),
					// Create login table
					array ("create table login (
  							memberid char(16) not null primary key,
							email char(75) not null,
							password char(32) not null,
							fname char(30) not null,
							lname char(30) not null,
							phone char(16) not null,
							institution char(255),
							position char(100),
							e_add char(1) not null default 'y',
							e_mod char(1) not null default 'y',
							e_del char(1) not null default 'y',
							e_html char(1) not null default 'y'
							)", 'Creating login table'),
					// Create login indexes
					array ('create index login_memberid on login (memberid)', 'Creating index'),
					array ('create index login_email on login (email)', 'Creating index'),
					array ('create index login_password on login (password)', 'Creating index'),
					// Create reservations table
					array ('create table reservations (
							resid char(16) not null primary key,
							machid char(16) not null,
							memberid char(16) not null,
							scheduleid char(16) not null,
							date integer not null,
							startTime integer not null,
							endTime integer not null,
							created integer not null,
							modified integer,
							parentid char(16),
							is_blackout smallint(1) not null default 0,
							summary text
							)', 'Creating reservations table'),
					// Create reservations indexes
					array ('create index res_resid on reservations (resid)', 'Creating index'),
					array ('create index res_machid on reservations (machid)', 'Creating index'),
					array ('create index res_memberid on reservations (memberid)', 'Creating index'),
					array ('create index res_scheduleid on reservations (scheduleid)', 'Creating index'),
					array ('create index res_date on reservations (date)', 'Creating index'),
					array ('create index res_startTime on reservations (startTime)', 'Creating index'),
					array ('create index res_endTime on reservations (endTime)', 'Creating index'),
					array ('create index res_created on reservations (created)', 'Creating index'),
					array ('create index res_modified on reservations (modified)', 'Creating index'),
					array ('create index res_parentid on reservations (parentid)', 'Creating index'),
					array ('create index res_isblackout on reservations (is_blackout)', 'Creating index'),
					// Create resources table
					array ("create table resources (
							  machid char(16) not null primary key,
							  scheduleid char(16) not null,
							  name char(75) not null,
							  location char(250),
							  rphone char(16),
							  notes text,
							  status char(1) not null default 'a',
							  minRes integer not null,
							  maxRes integer not null,
							  autoAssign smallint(1)
							  )", 'Creating resources table'),
					// Create resources indexes
					array ('create index rs_machid on resources (machid)', 'Creating index'),
					array ('create index rs_scheduleid on resources (scheduleid)', 'Creating index'),
					array ('create index rs_name on resources (name)', 'Creating index'),
					array ('create index rs_status on resources (status)', 'Creating index'),
					// Create permission table
					array ('create table permission (
							  memberid char(16) not null,
							  machid char(16) not null,
							  primary key(memberid, machid)
							  )', 'Creating permission table'),
					// Create permission indexes
					array ('create index per_memberid on permission (memberid)', 'Creating index'),
					array ('create index per_machid on permission (machid)', 'Creating index'),
					// Create schedule table
					array ("create table schedules (
							scheduleid char(16) not null primary key,
							scheduleTitle char(75),
							dayStart integer not null,
							dayEnd integer not null,
							timeSpan integer not null,
							timeFormat integer not null,
							weekDayStart integer not null,
							viewDays integer not null,
							usePermissions smallint(1),
							isHidden smallint(1),
							showSummary smallint(1),
							adminEmail char(75),
							isDefault smallint(1),
							dayOffset integer
							)", 'Creating table schedules'),
					// Create schedule indexes
					array ('create index sh_scheduleid on schedules (scheduleid)', 'Creating index'),
					array ('create index sh_hidden on schedules (isHidden)', 'Creating index'),
					array ('create index sh_perms on schedules (usePermissions)', 'Creating index'),
					// Create schedule permission tables
					array ("create table schedule_permission (
							scheduleid char(16) not null,
							memberid char(16) not null,
							primary key(scheduleid, memberid)
							)", 'Creating table schedule_permission'),
					// Create schedule permission indexes
					array ('create index sp_scheduleid on schedule_permission (scheduleid)', 'Creating index'),
					array ('create index sp_memberid on schedule_permission (memberid)', 'Creating index'),
					//array ('INSERT INTO schedules VALUES ("' . $scheduleid . ',"default",480,1200,30,12,0,0,0,1,"'. $conf['app']['adminEmail'] .'")', 'Creating default schedule'),
					// Create database user/permission
					array ("grant select, insert, update, delete
							on {$conf['db']['dbName']}.*
							to {$conf['db']['dbUser']}@localhost identified by '{$conf['db']['dbPass']}'", 'Creating database user')
				);

	if ($conf['db']['drop_old'])	// Drop any old database with same name
		array_unshift($sqls, array ("drop database if exists {$conf['db']['dbName']}", 'Dropping database'));

	foreach ($sqls as $sql) {
		echo $sql[1] . '...';
		$result = $db->query($sql[0]);
		check_result($result);
	}
	
	// Create default schedule
	$dbe = new DBEngine();
	echo 'Creating default schedule...';
	$scheduleid = $dbe->get_new_id();
	$result = $dbe->db->query('INSERT INTO schedules VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($scheduleid,'default',480,1200,30,12,0,7,0,0,1,$conf['app']['adminEmail'],1,0));
	check_result($result);
}

/**
* Examine result and print success or failure message to browswer
* @param PEAR::DB $result pear::db result object
*/
function check_result($result) {
	global $failed;
	if (DB::isError($result)) {
		echo '<span style=\"color: #FF0000; font-weight: bold;\">Failed: </span>' . $result->getMessage() . "</span><br/><br/>\n";
		$failed = true;	
	}
	else
		echo "<span style=\"color: #00CD00;\">Success</span><br/><br/>\n";
}

function doFinish() {
	global $failed;
	echo '<h5>';
	if ($failed) {
		echo translate('There were errors during the install.');
	}
	else {
		echo translate('You have successfully finished setting up phpScheduleIt and are ready to begin using it.');
	}
	?>
	<br /><br />
	<?
	echo translate('Thank you for using phpScheduleIt');
	echo '</h5>';
}
?>