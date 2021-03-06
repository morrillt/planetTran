<?php

/**

* This file is the control panel, or "home page" for logged in users.

* It provides a listing of all upcoming reservations

*  and functionality to modify or delete them. It also

*  provides links to all other parts of the system.

* @author Nick Korbel <lqqkout13@users.sourceforge.net>

* @version 06-24-04

* @package phpScheduleIt

*

* Copyright (C) 2003 - 2004 phpScheduleIt

* License: GPL, see LICENSE

*/

set_include_path("../:lib/pear/:/usr/local/php5");



//ini_set('display_errors', '1');

//session_register("classic");
//
//
//
//if (isset($_GET['ui'])&&$_GET['ui']=='old') {
//
//	$_SESSION['classic']=true;
//
//	setcookie('classic', 'old', time()+3600*24*365*10);
//
//} else if (isset($_GET['ui'])&&$_GET['ui']=='new') {
//
//	$_SESSION['classic']=false;
//
//	setcookie('classic', 'new', time()+3600*24*365*10);
//
//} else {
//
//	$_SESSION['classic'] = $_COOKIE['classic']=='old'?true:false;
//
//}



// if classic cookie doesn't exist, default to classic

//if (!isset($_COOKIE['classic']))

//	$_SESSION['classic'] = true;



// Include correct template

//if ($_SESSION['classic'])

	include_once('lib/Template.class.php');

//else
//
//	include_once('lib/Template2.class.php');

/**

* Include control panel-specific output functions

*/

include_once('templates/cpanel.template.php');



if (!Auth::is_logged_in()) {

    Auth::print_login_msg();	// Check if user is logged in

} else {
    
    if(!empty($_GET['currentId'])) {
      $_SESSION['currentID'] = $_GET['currentId'];
      $_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
    }
    
    if(!empty($_GET['spoofSessionId'])) {
      $_SESSION['old_session'] = array();
      foreach($_SESSION as $k=>$v) {
	if($k == 'old_session') continue;
	$_SESSION['old_session'][$k] = $v;
      }
      
      $auth = new Auth();
      $auth->doLogin(null,null,null,null,null,null,true,$_GET['spoofSessionId']);
    }
    
    if(!empty($_GET['returnSessionId']) && isset($_SESSION['old_session'])) {
      $_SESSION = $_SESSION['old_session'];
      unset($_SESSION['old_session']);
    }
    
}



if ($_SESSION['okphone'] === false) { 

	CmnFns::redirect(urldecode('register.php?edit=true'));

}

//if(isset($_GET['jumpIntoSession']) && $_GET['jumpIntoSession'])
//{
//  
//}

$db = new DBEngine();

//if ($_POST['add'])

if (!empty($_POST['apts']))

	$db->add_apt($_SESSION['currentID'], $_POST['apts']);

else if (!empty($_GET['apts']))

	$db->add_apt($_SESSION['currentID'], $_GET['apts']);


$active = isset($_GET['active']) ? $_GET['active'] : (isset($_POST['active']) ? $_POST['active'] : false);


switch($active)
{
  case 'prefs':
    $t = new Template(translate('My Email/Phone Preferences | PlanetTran'));
    $t->printHTMLHeader('silo_account sn2 mn1');
    $t->printNavAccount();
    break;

  case 'locs':
    $t = new Template(translate('My Locations | PlanetTran'));
    $t->printHTMLHeader('silo_account sn3 mn1');
    $t->printNavAccount();
    break;

  case 'view':
    $t = new Template(translate('Upcoming | Reservations | PlanetTran'), false);
    $t->printHTMLHeader('silo_reservations sn4 mn1');
    $t->printNavReservations();
    break;


  case 'qq':
    $t = new Template(translate('Price Quote | PlanetTran'));
    $t->printHTMLHeader('silo_reservations sn7 mn1');
    $t->printNavReservations();
    break;

  case 'schedules':
    $t = new Template(translate('Home | Reservations | PlanetTran'));
    $t->printHTMLHeader('silo_reservations sn9 mn1');
    $t->printNavReservations();
    break;

  default:
    $t = new Template(translate('Home | Reservations | PlanetTran'));
    $t->printHTMLHeader('silo_reservations sn1');
    $t->printNavReservations();
}

$t->printWelcome();

$t->startMain();

//if (Auth::isSuperAdmin() || stripos($_SESSION['currentName'], 'sobecky') !== false)



if (Auth::isAdmin())

	moratorium_warning();






if (isset($_SESSION['classic']) && $_SESSION['classic']) {

	/************ Classic View ******************************/

	if($_SESSION['role']=='a' || $_SESSION['role']=='m' || $_SESSION['role']=='w') {

		$order = array('date', 'name', 'startTime', 'endTime', 'created', 'modified');

		if($_SESSION['role'] != 'a') {

			echo '<form action="'. $_SERVER['PHP_SELF']. '" method="post">
			<table>
			  <tr>
			    <td>FirstName: <input type="text" name="firstName"/> </td> <td> LastName: <input type="text" name="lastName"/> </td> '.
			   '<td>Email: <input type="text" name="email"></td></tr><tr>';

			$groups = AdminDB::get_grouplist();

			echo '<select name="group">';

			echo '<option value="">Select group</option>';

			foreach ($groups as $k => $v) {

				echo "<option value=\"$k\">$v</option>\n";

			}

			echo '</select></td></tr></table>';



		echo '<input type="submit"/>';

		}

		$res = $db->get_admin_schedules($_SESSION['sessionID'], CmnFns::get_value_order($order), CmnFns::get_vert_order());

		showSchedulesTable($res, $db->get_err());

		printCpanelBr();

	}

	$order = array('date', 'name', 'startTime', 'endTime', 'created', 'modified');

	$res = $db->get_user_reservations($_SESSION['currentID'], CmnFns::get_value_order($order), CmnFns::get_vert_order());



	showReservationTable($res, $db->get_err());// Print out My Reservations



	printCpanelBr();



	if ($conf['app']['use_perms']) {

		$scheduleid = $db->get_user_scheduleid($_SESSION['currentID']);

		showTrainingTable($db->get_user_permissions($scheduleid), $db->get_err(), $scheduleid);	// Print location table 

		printCpanelBr();

		showQuickLinks();

	}

} else { /************* Reservations 2.0 view ********************/

	if ($active == 'schedules') {

		if($_SESSION['role']=='a' || $_SESSION['role']=='m' || $_SESSION['role']=='w' || $_SESSION['role']=='v') {

			$order = array('date', 'name', 'startTime', 'endTime', 'created', 'modified');

				echo '<form action="'. $_SERVER['PHP_SELF']. '?active=schedules" method="post">'.
				'<table>
				    <tr>
				       <td>FirstName: <input type="text" name="firstName"/> </td> <td> LastName: <input type="text" name="lastName"/> </td>
				      <td>Email: <input type="text" name="email"></td></tr>';
				      

			if($_SESSION['role'] != 'a') {
				$groups = AdminDB::get_grouplist();
				echo '<tr><td colspan="4">';
				echo '<select name="group">';
				echo '<option value="">Select group</option>';
				foreach ($groups as $k => $v) {
					echo "<option value=\"$k\">$v</option>\n";
				}
				echo '</select></td></tr>';
			}

				echo '</table>';
				echo '<input type="submit"/>';
			
			$res = $db->get_admin_schedules($_SESSION['sessionID'], CmnFns::get_value_order($order), CmnFns::get_vert_order());

			showSchedulesTable($res, $db->get_err());

		}

	} else if ($active == 'view') {

		$order = array('date', 'name', 'startTime', 'endTime', 'created', 'modified');

		$res = $db->get_user_reservations($_SESSION['currentID'], CmnFns::get_value_order($order), CmnFns::get_vert_order());

//echo '<pre>';
//print_r($res);
//echo '</pre>';
		showReservationTable($res, $db->get_err());// Print reservations

	} else if ($active == 'locs') {
  	if ($conf['app']['use_perms']) {
			$scheduleid = $db->get_user_scheduleid($_SESSION['currentID']);
			showTrainingTable($db->get_user_permissions($scheduleid), $db->get_err(), $scheduleid);	// Print locations
		}
	} else if ($active == 'qq') {

		include('qq_nopopup.php');

	} else if ($active == 'prefs') {


function radio_boolean($name, $value, $id, $label_true = 'Yes', $label_false = 'No')
{
  return sprintf('
    <input type="radio" name="%1$s" id="%2$s_yes" %5$s value="1" /><label for="%2$s_yes">%3$s</label><br/>
    <input type="radio" name="%1$s" id="%2$s_no" %6$s value="0" /><label for="%2$s_no">%4$s</label>
    ', $name, $id, $label_true, $label_false,
    ($value == 1 ? 'checked="checked"' : ''),
    ($value == 0 ? 'checked="checked"' : '')
  );
}
?>

    <h1 id="hdr_my_preferences"><span class="imagetext">My Email/Phone Preferences</span></h1>
<?php

    if(isset($_POST['submit_prefs'])){
      $data = $_POST;

      $auth = new Auth();
      $msg = $auth->do_update_prefs();

      if(!empty($msg)){
?>
<!--  <div class="message">Failed</div>-->
<?php
      } else {
?>
<h2 class="message">Preferences updated successfully!</h2>
<?php
      }
    } else {
      $userDB = new UserDB();
      $data = $userDB->get_user_data($_SESSION['currentID']);
//      print_r($data);
    }
?>
    <form action="" method="post" id="email_phone_prefs">

      <fieldset class="hr">
        <legend>Email Me When</legend>
        <div class="row group">
          <div class="labelish">I place a reservation	</div>
          <div class="inputs">
            <?php echo radio_boolean('e_add', $data['e_add'], 'q1') ?>
          </div>
        </div>
        <div class="row group">
          <div class="labelish">My reservation is modified</div>
          <div class="inputs">
            <?php echo radio_boolean('e_mod', $data['e_mod'], 'q2') ?>
          </div>
        </div>
        <div class="row group">
          <div class="labelish">My reservation is deleted</div>
          <div class="inputs">
            <?php echo radio_boolean('e_del', $data['e_del'], 'q3') ?>
          </div>
        </div>
        <div class="row group">
          <div class="labelish">Preferred Email format</div>
          <div class="inputs">
            <?php echo radio_boolean('e_html', $data['e_html'], 'q4', 'HTML', 'Plain-text') ?>
          </div>
        </div>
        <div class="row group schedule_manager">
          <div class="labelish">CC me on all receipts</div>
          <div class="inputs">
            <?php echo radio_boolean('recieve_texts', $data['recieve_texts'], 'q5') ?>
          </div>
        </div>
      </fieldset>

      <fieldset class="hr">
        <legend>Phone Preferences</legend>
        <div class="row group spacious_bottom">
          <div class="labelish">When my driver is on location</div>
          <div class="inputs">
            <input type="checkbox" name="phone_sms" <?php echo $data['phone_sms'] ? 'checked="checked"' : '' ?> id="q6_text" /><label for="q6_text">Send me a text</label><br />
            <input type="checkbox" name="phone_call" <?php echo $data['phone_call'] ? 'checked="checked"' : '' ?> id="q6_call" class="clearer" /><label for="q6_call">Call me</label>
          </div>
        </div>
      </fieldset>

      <div class="row group">
        <div class="inputs">
          <input name="submit_prefs" type="submit" id="submit" value="Save" />
        </div>
      </div>

    </form>

<?php
	} else {

		echo '<div class="basicText">';

		//if ($_SESSION['currentName'])	

		//	echo "<br>Your current active schedule is <b>".$_SESSION['currentName']."</b>.<br>";

		homeText();

		echo '</div>';

	}

}

printCpanelBr();



$t->endMain();

$t->printHTMLFooter();

// Print_r ($_SESSION);

/******************************************************************/

function homeText() {

	$l = array(	'Home' => 'ctrlpnl.php?active=home',

			'Impact' => 'impact.php',

			'Schedules' => 'ctrlpnl.php?active=schedules',

			'Reservations' => 'ctrlpnl.php?active=view',

			'My Locations'	=> 'ctrlpnl.php?active=locs',

			'History/Receipts' => 'receipts.php',

			'Referrals' => 'referrals.php',

			'My Account' => 'register.php?edit=true',

			'Our Service' => 'info.php?active=service',

			'Price Quote'	=> 'ctrlpnl.php?active=qq'

			//'Mobile' => 'info.php?active=mobile'

			);

	?>
<h1>Home</h1>
<div class="titlebar">

Welcome to the PlanetTran Reservations System!  The following will links

will help you with setting up your account and booking reservations in a

fast, reliable, and convenient manner.</div>



<div class="bold13pt"><a href="<?=$l['Impact']?>">Impact</a></div> 

Get a report on how the usage of PlanetTran by you, your organization, 

and your referral network has decreased carbon emissions and fight

global warming!<br />  <br />

<?

if ($_SESSION['role']=='a' || $_SESSION['role']=='m') { 

?><div class="bold13pt"><a href="<?=$l['Schedules']?>">Schedules</a></div>

Create or change to an existing schedule that you manage.<br /> <br />

<?

}

?>



<div class="bold13pt"><a href="<?=$l['Reservations']?>">Reservations</a></div>

Create or change existing reservations for the current schedule (or,

"your schedule" if not an admin).<br /> <br />



<div class="bold13pt"><a href="<?=$l['My Locations']?>">My Locations</a></div>

Create or change your list of locations, which you choose when making

reservations.<br /> <br />



<div class="bold13pt"><a href="<?=$l['Receipts']?>">Receipts</a></div>

Get PDF receipts for individual trips, or a spreadsheet of activity for

a given time period.<br /> <br />



<div class="bold13pt"><a href="<?=$l['Referrals']?>">Referrals</a></div>

Refer friends and colleagues to PlanetTran, and see how their collective

usage decrease carbon emissions!<br /> <br />



<div class="bold13pt"><a href="<?=$l['My Account']?>">My Account</a></div>

Edit your profile (cell phone, email address, credit card) here.<br /> <br />



<div class="bold13pt"><a href="<?=$l['Our Service']?>">Our Service</a></div>

Get details about policies and procedures of the car service.<br /> <br />



<!--<div class="bold13pt"><a href="<?/*=$l['Price Quote']*/?>">Price Quote</a></div>

Get rates from standard and arbitrary locations.<br /> <br />-->



<!--

<div class="bold13pt"><a href="<?=$l['Mobile']?>">Mobile</a></div>

Learn how to create and modify reservations using any text capable

device! -->



	<?

}



function moratorium_warning() {

	include_once('lib/db/ResDB.class.php');

	$db = new ResDB();

	$time = time() - 60*60*3;

	$warnings = array();

	$dateformat = "l n/j,";

	$timeformat = "g:ia";



	$m = $db->get_moratoriums();



	if (!$m || !count($m)) return;



	$areas = array('MA'=>'Boston', 'CA'=>'San Francisco');



	for ($i=0; $m[$i]; $i++) {

		$cur = $m[$i];



		// skip moratoriums in the past

		if ($cur['end'] < $time) continue;		



		$startdate = date($dateformat, $cur['start']);

		$enddate = date($dateformat, $cur['end']);

		$starttime = date($timeformat, $cur['start']);

		$endtime = date($timeformat, $cur['end']);

		if ($startdate == $enddate) $enddate = "";

		$area = $areas[$cur['area']];



		$warnings[] = "$area on $startdate $starttime to $enddate $endtime"; 

	}

	



	/*

	if ($m['bos_moratorium_end'] > $time) {

		$startdate = date($dateformat, $m['bos_moratorium_start']);

		$enddate = date($dateformat, $m['bos_moratorium_end']);

		$starttime = date($timeformat, $m['bos_moratorium_start']);

		$endtime = date($timeformat, $m['bos_moratorium_end']);

		if ($startdate == $enddate) $enddate = "";

		$warnings[] = "Boston on $startdate $starttime to $enddate $endtime"; 

	}

	if ($m['sfo_moratorium_end'] > $time) {

		$startdate = date($dateformat, $m['sfo_moratorium_start']);

		$enddate = date($dateformat, $m['sfo_moratorium_end']);

		$starttime = date($timeformat, $m['sfo_moratorium_start']);

		$endtime = date($timeformat, $m['sfo_moratorium_end']);

		if ($startdate == $enddate) $enddate = "";

		$warnings[] = "San Francisco on $startdate $starttime to $enddate $endtime"; 

	

	}

	if ($m['sfo_moratorium_end2'] > $time) {

		$startdate = date($dateformat, $m['sfo_moratorium_start2']);

		$enddate = date($dateformat, $m['sfo_moratorium_end2']);

		$starttime = date($timeformat, $m['sfo_moratorium_start2']);

		$endtime = date($timeformat, $m['sfo_moratorium_end2']);

		if ($startdate == $enddate) $enddate = "";

		$warnings[] = "San Francisco on $startdate $starttime to $enddate $endtime"; 

	

	}



	*/



	if (!count($warnings)) return;

	

	echo "<h3>The following dates and times are under moratorium:</h3>";

	echo '<div style="font-size: 12px;">';

	foreach ($warnings as $v) 

		echo "&nbsp;&nbsp;&nbsp;&bull;&nbsp;$v<br>";

	

	echo '</div>';

	echo "&nbsp;<br>Check with a dispatcher or admin before taking any reservations for those times.<br>&nbsp;<br>";

	

}

?>
