<?php
/**
* This file provides output functions for the admin class
* No data manipulation is done in this file
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 09-07-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

require_once(dirname(__FILE__).'/../lib/CmnFns.class.php');
$link = CmnFns::getNewLink(); // Get Link object

/**
* Return the tool name
* @param none
*/
function getTool() {
	return $_GET['tool'];
}

/**
* Prints out list of current schedules
* @param Object $pager pager object
* @param mixed $schedules array of schedule data
* @param string $err last database error
*/
function print_manage_schedules(&$pager, $schedules, $err) {
	global $link;

?>
<form name="manageSchedule" method="post" action="admin_update.php" onsubmit="return checkAdminForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="7" class="tableTitle">&#8250; <?=translate('All Schedules')?></td>
        </tr>
        <tr class="rowHeaders">
          <td><?=translate('Schedule Title')?></td>
          <td width="8%"><?=translate('Start Time')?></td>
          <td width="8%"><?=translate('End Time')?></td>
          <td width="9%"><?=translate('Time Span')?></td>
		  <td width="11%"><?=translate('Weekday Start')?></td>
          <td width="20%"><?=translate('Admin Email')?></td>
		  <td width="7%"><?=translate('Default')?></td>
		  <td width="5%"><?=translate('Edit')?></td>
          <td width="7%"><?=translate('Delete')?></td>
        </tr>
        <?

	if (!$schedules)
		echo '<tr class="cellColor0"><td colspan="8" style="text-align: center;">' . $err . '</td></tr>' . "\n";

    for ($i = 0; is_array($schedules) && $i < count($schedules); $i++) {
		$cur = $schedules[$i];
        echo "<tr class=\"cellColor" . ($i%2) . "\" align=\"center\">\n"
            . '<td style="text-align:left">' . $cur['scheduleTitle'] . "</td>\n"
            . '<td style="text-align:left">';
        echo CmnFns::formatTime($cur['dayStart']);
		echo "</td>\n"
            . '<td style="text-align:left">';
        echo CmnFns::formatTime($cur['dayEnd']);
        echo "</td>\n"
            . '<td style="text-align:left">';
        echo CmnFns::minutes_to_hours($cur['timeSpan']);
		echo "</td>\n"
		    . '<td style="text-align:left">';
        echo CmnFns::get_day_name($cur['weekDayStart'], 0);
		echo "</td>\n"
		 . '<td style="text-align:left">';
	    echo $cur['adminEmail'];
		echo "</td>\n"
			. '<td><input type="radio" value="' . $schedules[$i]['scheduleid'] . "\" name=\"isDefault\"" . ($schedules[$i]['isDefault'] == 1 ? ' checked="checked"' : '') . ' onclick="javacript: setSchedule(\'' . $schedules[$i]['scheduleid'] . '\');" /></td>'
            . '<td>' . $link->getLink($_SERVER['PHP_SELF'] . '?' . preg_replace("/&scheduleid=[\d\w]*/", "", $_SERVER['QUERY_STRING']) . '&amp;scheduleid=' . $cur['scheduleid'] . ((strpos($_SERVER['QUERY_STRING'], $pager->getLimitVar())===false) ? '&amp;' . $pager->getLimitVar() . '=' . $pager->getLimit() : ''), translate('Edit'), '', '', translate('Edit data for', array($cur['scheduleTitle']))) . "</td>\n"
            . "<td><input type=\"checkbox\" name=\"scheduleid[]\" value=\"" . $cur['scheduleid'] . "\" /></td>\n"
            . "</tr>\n";
    }

    // Close table
    ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?
	echo submit_button(translate('Delete'), 'scheduleid') . hidden_fn('delSchedule');
?>
</form>
<form id="setDefaultSchedule" name="setDefaultSchedule" method="post" action="admin_update.php">
<input type="hidden" name="scheduleid" value=""/>
<input type="hidden" name="fn" value="dfltSchedule"/>
</form>
<?
}


/**
* Interface to add or edit schedule information
* @param mixed $rs array of schedule data
* @param boolean $edit whether this is an edit or not
* @param object $pager Pager object
*/
function print_schedule_edit($rs, $edit, &$pager) {
	global $conf;
    ?>
<form name="addSchedule" method="post" action="admin_update.php" <?= $edit ? "" : "onsubmit=\"return checkAddSchedule();\"" ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td width="200" class="formNames"><?=translate('Schedule Title')?></td>
          <td class="cellColor"><input type="text" name="scheduleTitle" class="textbox" value="<?= isset($rs['scheduleTitle']) ? $rs['scheduleTitle'] : '' ?>" />
          </td>
        </tr>
		<tr>
		  <td class="formNames"><?=translate('Start Time')?></td>
		  <td class="cellColor"><select name="dayStart" class="textbox">
		  <?
		  for ($time = 0; $time <= 1410; $time += 30)
		  	echo '<option value="' . $time . '"' . ((isset($rs['dayStart']) && ($rs['dayStart'] == $time)) ? ' selected="selected"' : '') . '>' . CmnFns::formatTime($time) . '</option>' . "\n";
		  ?>
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="formNames"><?=translate('End Time')?></td>
		  <td class="cellColor"><select name="dayEnd" class="textbox">
		  <?
		  for ($time = 30; $time <= 1440; $time += 30)
		  	echo '<option value="' . $time . '"' . ((isset($rs['dayEnd']) && ($rs['dayEnd'] == $time)) ? (' selected="selected"') : (($time==1440 && !isset($rs['dayEnd'])) ? ' selected="selected"' : '')) . '>' . CmnFns::formatTime($time) . '</option>' . "\n";
		  ?>
		  </select>
		  </td>
		</tr>
        <tr>
          <td class="formNames"><?=translate('Time Span')?></td>
          <td class="cellColor"><select name="timeSpan" class="textbox">
		  <?
		  $spans = array (30, 10, 15, 60, 120, 180, 240);
		  for ($i = 0; $i < count($spans); $i++)
		  	echo '<option value="' . $spans[$i] . '"' . ((isset($rs['timeSpan']) && ($rs['timeSpan'] == $spans[$i])) ? (' selected="selected"') : '') . '>' . CmnFns::minutes_to_hours($spans[$i]) . '</option>' . "\n";
		  ?>
		  </select>
		  </td>
        </tr>
        <tr>
          <td class="formNames"><?=translate('Weekday Start')?></td>
          <td class="cellColor"><select name="weekDayStart" class="textbox">
		  <?
		  for ($i = 0; $i < 7; $i++)
		  	echo '<option value="' . $i . '"' . ( (isset($rs['weekDayStart']) && $rs['weekDayStart'] == $i) ? ' selected="selected"' : '') . '>' . CmnFns::get_day_name($i) . '</option>' . "\n";
		  ?>
		  </select>
		  </td>
        </tr>
        <tr>
          <td class="formNames"><?=translate('Days to Show')?></td>
          <td class="cellColor"><input type="text" name="viewDays" class="textbox" size="2" maxlength="2" value="<?= isset($rs['viewDays']) ? $rs['viewDays'] : '7' ?>" />
          </td>
        </tr>
		<tr>
		  <td class="formNames"><?=translate('Reservation Offset')?></td>
		  <td class="cellColor"><input type="text" name="dayOffset" class="textbox" size="2" maxlength="2" value="<?= isset($rs['dayOffset']) ? $rs['dayOffset'] : '0' ?>" />
          </td>
		</tr>
		<tr>
		  <td class="formNames"><?=translate('Hidden')?></td>
		   <td class="cellColor"><select name="isHidden" class="textbox">
		  <?
		  $yesNo = array(translate('No'), translate('Yes'));
		  for ($i = 0; $i < 2; $i++)
		  	echo '<option value="' . $i . '"' . ((isset($rs['isHidden']) && ($rs['isHidden'] == $i)) ? (' selected="selected"') : '') . '>' . $yesNo[$i]  . '</option>' . "\n";
		  ?>
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="formNames"><?=translate('Show Summary')?></td>
		   <td class="cellColor"><select name="showSummary" class="textbox">
		  <?
		  for ($i = 1; $i >= 0; $i--)
		  	echo '<option value="' . $i . '"' . ((isset($rs['showSummary']) && ($rs['showSummary'] == $i)) ? (' selected="selected"') : '') . '>' . $yesNo[$i]  . '</option>' . "\n";
		  ?>
		  </select>
		  </td>
		</tr>
		<tr>
          <td class="formNames"><?=translate('Admin Email')?></td>
          <td class="cellColor"><input type="text" name="adminEmail" maxlength="75" class="textbox" value="<?= isset($rs['adminEmail']) ? $rs['adminEmail'] : $conf['app']['adminEmail'] ?>" />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
<?
        // Print out correct buttons
        if (!$edit) {
            echo submit_button(translate('Add Schedule'), 'scheduleid') . hidden_fn('addSchedule')
			. ' <input type="reset" name="reset" value="' . translate('Clear') . '" class="button" />' . "\n";
        }
		else {
            echo submit_button(translate('Edit Schedule'), 'scheduleid') . cancel_button($pager) . hidden_fn('editSchedule')
				. '<input type="hidden" name="scheduleid" value="' . $rs['scheduleid'] . '" />' . "\n";
        	// Unset variables
			unset($rs);
		}
		echo "</form>\n";
}

/**
* Prints out the user management table
* @param Object $pager pager object
* @param mixed $users array of user data
* @param string $err last database error
*/
function print_manage_users(&$pager, $users, $err) {
	global $link;

?>

<form name="manageUser" method="post" action="admin_update.php" onsubmit="return checkAdminForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="7" class="tableTitle">&#8250; <?=translate('All Users')?> </td>
        </tr>
        <tr class="rowHeaders">
          <td width="21%"><?=translate('Name')?></td>
          <td width="28%"><?=translate('Email')?></td>
          <td width="15%"><?=translate('Institution')?></td>
          <td width="12%"><?=translate('Phone')?></td>
          <td width="8%"><?=translate('Password')?></td>
          <td width="10%"><?=translate('Permissions')?></td>
          <td width="6%"><?=translate('Delete')?></td>
        </tr>
        <tr class="cellColor0" style="text-align: center;">
          <td><? printDescLink($pager, 'lname', 'last name') ?> &nbsp;&nbsp; <? printAscLink($pager, 'lname', 'last name') ?> </td>
          <td><? printDescLink($pager, 'email', 'email address') ?> &nbsp;&nbsp; <? printAscLink($pager, 'email', 'email address') ?> </td>
          <td><? printDescLink($pager, 'institution', 'institution') ?> &nbsp;&nbsp; <? printAscLink($pager, 'institution', 'institution') ?> </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?

	if (!$users)
		echo '<tr class="cellColor0"><td colspan="7" style="text-align: center;">' . $err . '</td></tr>' . "\n";

	for ($i = 0; is_array($users) && $i < count($users); $i++) {
		$cur = $users[$i];
		$fname = $cur['fname'];
		$lname = $cur['lname'];
		$email = $cur['email'];

		$fname_lname = array($fname, $lname);

		echo "<tr class=\"cellColor" . ($i%2) . "\" align=\"center\">\n"
               . '<td style="text-align:left;">' . $link->getLink("javascript: viewUser('". $cur['memberid'] . "');", $fname . ' ' . $lname, '', '', translate('View information about', $fname_lname)) . "</td>\n"
               . '<td style="text-align:left;">' . $link->getLink("mailto:$email", $email, '', '', translate('Send email to', array($fname, $lname))) . "</td>\n"
               . '<td style="text-align:left;\">' . $cur['institution'] . "</td>\n"
               . '<td style="text-align:left;">' . $cur['phone'] . "</td>\n"
               . '<td>' . $link->getLink("admin.php?tool=pwreset&amp;memberid=" . $cur['memberid'], translate('Reset'), '', '', translate('Reset password for', $fname_lname)) .  "</td>\n"
               . '<td>' . $link->getLink("admin.php?tool=perms&amp;memberid=" . $cur['memberid'], translate('Edit'), '', '', translate('Edit permissions for', $fname_lname)) . "</td>\n"
               . '<td><input type="checkbox" name="memberid[]" value="' . $cur['memberid'] . '" /></td>'. "\n"
              . "</tr>\n";
    }

    // Close users table
    ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?
	echo submit_button(translate('Delete')) . hidden_fn('deleteUsers') . '</form>';
?>
<form name="name_search" action="<?=$_SERVER['PHP_SELF']?>" method="get">
	<p align="center">
	<? print_lname_links(); ?>
	</p>
	<br />
	<p align="center">
	<?=translate('First Name')?> <input type="text" name="firstName" class="textbox" />
	<?=translate('Last Name')?> <input type="text" name="lastName" class="textbox" />
	<input type="hidden" name="searchUsers" value="true" />
	<input type="hidden" name="tool" value="<?=getTool()?>" />
	<input type="hidden" name="<?=$pager->getLimitVar()?>" value="<?=$pager->getLimit()?>" />
	<? if (isset($_GET['order'])) { ?>
		<input type="hidden" name="order" value="<?=$_GET['order']?>" />
	<? } ?>
	<? if (isset($_GET['vert'])) { ?>
		<input type="hidden" name="vert" value="<?=$_GET['vert']?>" />
	<? } ?>
	<input type="submit" name="searchUsersBtn" value="<?=translate('Search Users')?>" class="button" />
	</p>
</form>
<?
}


/**
* Prints out the links to select last names
* @param none
*/
function print_lname_links() {
	global $letters;
	echo '<a href="javascript: search_user_lname(\'\');">' . translate('All Users') . '</a>';
	foreach($letters as $letter) {
		echo '<a href="javascript: search_user_lname(\''. $letter . '\');" style="padding-left: 10px; font-size: 12px;">' . $letter . '</a>';
	}
}

/**
* Prints out list of current resources
* @param Object $pager pager object
* @param mixed $resources array of resource data
* @param string $err last database error
*/
function print_manage_resources(&$pager, $resources, $err) {
	global $link;

?>
<form name="manageResource" method="post" action="admin_update.php" onsubmit="return checkAdminForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="8" class="tableTitle">&#8250; <?=translate('All Resources')?></td>
        </tr>
        <tr class="rowHeaders">
          <td><?=translate('Resource Name')?></td>
          <td width="18%"><?=translate('Location')?></td>
		  <td width="12%"><?=translate('Schedule')?></td>
          <td width="10%"><?=translate('Phone')?></td>
          <td width="25%"><?=translate('Notes')?></td>
          <td width="5%"><?=translate('Edit')?></td>
          <td width="9%"><?=translate('Status')?></td>
          <td width="7%"><?=translate('Delete')?></td>
        </tr>
        <tr class="cellColor" style="text-align: center">
          <td> <? printDescLink($pager, 'name', 'resource name') ?> &nbsp;&nbsp; <? printAscLink($pager, 'name', 'resource name') ?> </td>
          <td> <? printDescLink($pager, 'location', 'location') ?> &nbsp;&nbsp; <? printAscLink($pager, 'location', 'location') ?> </td>
          <td> <? printDescLink($pager, 'scheduleTitle', 'schedule title') ?> &nbsp;&nbsp; <? printAscLink($pager, 'scheduleTitle', 'schedule title') ?> </td>
		  <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?

	if (!$resources)
		echo '<tr class="cellColor0"><td colspan="8" style="text-align: center;">' . $err . '</td></tr>' . "\n";

    for ($i = 0; is_array($resources) && $i < count($resources); $i++) {
		$cur = $resources[$i];
        echo "<tr class=\"cellColor" . ($i%2) . "\" align=\"center\">\n"
            . '<td style="text-align:left">' . $cur['name'] . "</td>\n"
            . '<td style="text-align:left">';
        echo isset($cur['location']) ?  $cur['location'] : '&nbsp;';
		echo "</td>\n"
            . '<td style="text-align:left">' . $cur['scheduleTitle'] . "</td>\n";
        echo '<td style="text-align:left">';
        echo isset($cur['rphone']) ?  $cur['rphone'] : '&nbsp;';
		echo "</td>\n"
            . '<td style="text-align:left">';
        echo isset($cur['notes']) ?  $cur['notes'] : '&nbsp;';
		echo "</td>\n"
            . '<td>' . $link->getLink($_SERVER['PHP_SELF'] . '?' . preg_replace("/&machid=[\d\w]*/", "", $_SERVER['QUERY_STRING']) . '&amp;machid=' . $cur['machid'] . ((strpos($_SERVER['QUERY_STRING'], $pager->getLimitVar())===false) ? '&amp;' . $pager->getLimitVar() . '=' . $pager->getLimit() : ''), translate('Edit'), '', '', translate('Edit data for', array($cur['name']))) . "</td>\n"
            . '<td>' . $link->getLink("admin_update.php?fn=togResource&amp;machid=" . $cur['machid'] . "&amp;status=" . $cur['status'], $cur['status'] == 'a' ? translate('Active') : translate('Inactive'), '', '', translate('Toggle this resource active/inactive')) . "</td>\n"
            . "<td><input type=\"checkbox\" name=\"machid[]\" value=\"" . $cur['machid'] . "\" /></td>\n"
            . "</tr>\n";
    }

    // Close table
    ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?
	echo submit_button(translate('Delete'), 'machid') . hidden_fn('delResource') . '</form>';
}


/**
* Interface to add or edit resource information
* @param mixed $rs array of resource data or machid
*/
//function print_resource_edit($rs, $scheds, $type, &$pager) {
function print_resource_edit($rs = null){
	global $conf;

  require_once dirname(__FILE__).'/../lib/db/AdminDB.class.php';
	$db = new AdminDB();
  $scheduleid = $db->get_user_scheduleid($_SESSION['currentID']);
//  $machid = $rs;

  if(!is_array($rs) && null !== $rs)
  {
    $rs = $db->get_resource_data($rs);
    if(!$rs)
    {
      $rs = array();
      $machid = null;
    }
    else
    {
      $machid = $rs['machid'];
    }
  }
//  print_r($rs);

    ?>
<div class="popover_content">
  <script type="text/javascript">
    $(function(){
      locationSwitcher(".type_toggle", ".location_option");
    });
  </script>
  <form id="add_location" method="post" action="admin_update.php?fn=<?php echo $machid ? 'editResource' : 'addResource' ?>&machid=<?php echo $machid ?>">
    <fieldset>
      <input type="hidden" name="scheduleid" value="<?php echo $scheduleid ?>" />
      <div class="radio_buttons">
        <input value="1" name="type" <?php if(empty($rs['type']) || 1 == $rs['type']) echo 'checked="checked"' ?> type="radio" id="address" class="type_toggle" />
        <label for="address">Address</label>
        <input name="type" value="air" <?php if('air' == $rs['type']) echo 'checked="checked"' ?> type="radio" id="airport" class="type_toggle" />
        <label for="airport">Airport</label>
        <!--input value="2" name="type" <?php if(2 == $rs['type']) echo 'checked="checked"' ?> type="radio" id="poi" class="type_toggle" />
        <label for="poi">Point of Interest</label-->
      </div>

      <div class="location_option hidden">
        <!-- Conditionally shown based on radio selection above -->
        <div class="row">
          <label for="nickname">Nickname</label>
          <input name="name" type="text" id="nickname" value="<?php echo $rs['name'] ?>" />
        </div>
        <div class="row">
          <label for="street_address">Street Address</label>
          <input name="address1" type="text" id="street_addres" value="<?php echo $rs['address1'] ?>" />
        </div>
        <div class="row">
          <label for="city">City</label>
          <input name="city" type="text" id="city" value="<?php echo $rs['city'] ?>" />
        </div>
        <div class="row">
          <label for="state">State</label>
          <input name="state" type="text" id="state" value="<?php echo $rs['state'] ?>" maxlength="2" />
        </div>
        <div class="row">
          <label for="zipcode">Zip Code <span class="popover"><a href="#" onclick="zip_lookup($('#street_addres'),$('#city'),$('#state'), $('#zipcode'))">Look Up</a></span></label>
          <input name="zip" type="text" id="zipcode" value="<?php echo $rs['zip'] ?>" />
        </div>
      </div><!-- /address -->

      <div class="location_option hidden">
        <!-- Conditionally shown based on radio selection above -->
        <div class="row">
          <select name="airport" id="airport_select">
            <option value="">Select an airport</option>
            <?php foreach(array(
              "Logan Int'l Airport (BOS)",
              "Manchester Airport (MHT)",
              "T.F. Green Airport (PVD)",
            ) as $v): ?>
              <option value="<?php echo $v ?>" <?php if($v == $rs['airport']) echo 'selected="selected"' ?>><?php echo $v ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="row">
          <select name="airline" id="airline_select">
            <option value="">Select an airline</option>
            <?php foreach(array(
              'AmericanAirlines',
              'Continental',
              'Delta',
              'JetBlue',
              'United',
            ) as $v): ?>
              <option value="<?php echo $v ?>" <?php if($v == $rs['airline']) echo 'selected="selected"' ?>><?php echo $v ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="row  group">
          <label for="flight_no">Flight #</label>
          <input name="flight" value="<?php echo $rs['flight'] ?>" type="text" id="flight_no" class="flight_no" />
        </div>
        <div class="row  group">
          <label for="flight_details">Time/Other details</label>
          <input name="details" type="text" value="<?php echo $rs['details'] ?>" id="flight_details" class="flight_details" />
        </div>
      </div><!-- /airport -->

      <div class="location_option hidden">
        <!-- Conditionally shown based on radio selection above -->
        <div class="row">
          <label for="point_city">City</label>
          <input name="point_city" type="text" id="point_city" value="<?php echo $rs['point_city'] ?>" />
        </div>
        <div class="row">
          <label for="poi_name">Point of Interest</label>
          <input name="point_name" type="text" id="poi_name" value="<?php echo $rs['point_name'] ?>" />
        </div>
      </div><!-- /poi -->


<!--      <input type="button" value="Save Location" class="spacious_top" />-->
    </fieldset>
  </form>
</div>
<?php
//	$start = 0;
//	$end   = 1440;
//	$mins = array(0, 10, 15, 30);
//	$read_only = '';
//	$admin = Auth::isAdmin() ? 'a' : 'p';
  /*
	if ($type == 'm') {
		$minH = intval($rs['minRes'] / 60);
		$minM = intval($rs['minRes'] % 60);
		$maxH = 24;//intval($rs['maxRes'] / 60);
		$maxM = intval($rs['maxRes'] % 60);
		?>
		<h3 align="center">Modify Location</h3>
		<?
	}
	else if ($type == 'c') {
		$maxH = 24;
		?>
		<h3 align="center">Create Location</h3>
	<?
	} else if ($type == 'd') {
		$maxH = 24;
		$read_only = "disabled = \"true\"";
	?>
		<h3 align="center">Delete Location</h3>
	<?
	}
   */

/*
		<script type="text/javascript">
		window.onload = function() {
			this.focus();
		}
		</script>
<form name="addResource" method="post" action="admin_update.php" <?= ($type == 'd') ? "" : "onsubmit=\"return validateLocForm('$admin');\"" ?>>
<input type="hidden" name="bypass" value="<?=$rs['bypass']?>">
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td width="200" class="formNames"><?=translate('Location Name')?></td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="name" class="textbox" value="<?= isset($rs['name']) ? $rs['name'] : '' ?>" />
          </td>
        </tr>
        <tr>
          <td class="formNames"><?=translate('Address1')?></td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="address1" class="textbox" value="<?= isset($rs['address1']) ? $rs['address1'] : '' ?>" />
          </td>
        </tr>
		<tr>
          <td class="formNames"><?=translate('Address2')?></td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="address2" class="textbox" value="<?= isset($rs['address2']) ? $rs['address2'] : '' ?>" />
          </td>
        </tr>
		<tr>
          <td class="formNames"><?=translate('City')?></td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="city" class="textbox" value="<?= isset($rs['city']) ? $rs['city'] : '' ?>" />
          </td>
        </tr>
		<tr>
          <td class="formNames"><?=translate('State')?></td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="state" class="textbox" size=2 maxlength=2 value="<?= isset($rs['state']) ? $rs['state'] : '' ?>" />
          </td>
        </tr>
		<tr>
          <td class="formNames"><?=translate('Zip')?> (<a href="javascript: checkAddress();">Don't know zip code?</a>)</td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="zip" class="textbox" value="<?= isset($rs['zip']) ? $rs['zip'] : '' ?>" />
          </td>
        </tr>
        <tr>
          <td class="formNames"><?=translate('Location Phone')?></td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="rphone" class="textbox" value="<?= isset($rs['rphone']) ? $rs['rphone'] : '' ?>" />
          </td>
        </tr>
        <tr>
          <td class="formNames"><?=translate('NotesLocation')?></td>
          <td class="cellColor"><textarea <?=$read_only?> name="notes" class="textbox" rows="3" cols="30"><?= isset($rs['notes']) ? $rs['notes'] : '' ?>
</textarea>
          </td>
        </tr>
		<input type="hidden" name="scheduleid" value="<?=$scheds[0]['scheduleid']?>">
		<!--<select name="scheduleid" class="textbox">-->
		<!--<tr>
		  <td class="formNames"><?=translate('Minimum Reservation Time')?></td>
		  <td class="cellColor">
		  <select name="minH" class="textbox">
		  <?
		  for ($h = 0; $h < 25; $h++)
		  	echo '<option value="' . $h . '"' . ((isset($minH) && $minH == $h) ? ' selected="selected"' : '') . '>' . $h . ' ' . translate('hours') . '</option>' . "\n";
		  ?>
		  </select>
		  <select name="minM" class="textbox">
		  <?
		  foreach ($mins as $m)
		  	echo '<option value="' . $m . '"' . ((isset($minM) && $minM == $m) ? ' selected="selected"' : '') . '>' . $m . ' ' . translate('minutes') . '</option>' . "\n";
		  ?>
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="formNames"><?=translate('Maximum Reservation Time')?></td>
		  <td class="cellColor">
		  <select name="maxH" class="textbox">
		  <?
		  for ($h = 0; $h < 25; $h++)
		  	echo '<option value="' . $h . '"' . ((isset($maxH) && $maxH == $h) ? ' selected="selected"' : '') . '>' . $h . ' ' . translate('hours') . '</option>' . "\n";
		  ?>
		  </select>
		  <select name="maxM" class="textbox">
		  <?
		  foreach ($mins as $m)
		  	echo '<option value="' . $m . '"' . ((isset($maxM) && $maxM == $m) ? ' selected="selected"' : '') . '>' . $m . ' ' . translate('minutes') . '</option>' . "\n";
		  ?>
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="formNames"><?=translate('Auto-assign permission')?></td>
		  <td class="cellColor"><input type="checkbox" name="autoAssign" <?=(isset($rs['autoAssign']) && ($rs['autoAssign'] == 1)) ? 'checked="checked"' : ''?>/>
		  </td>
		</tr>-->
      </table>
    </td>
  </tr>
</table>
<br />
<?
        // Print out correct buttons
        if ($type == 'c') {
            echo submit_button(translate('Add Resource'), 'machid') . hidden_fn('addResource')
			. ' <input type="reset" name="reset" value="' . translate('Clear') . '" class="button" />' . "\n";
        }
		else if ($type == 'm') {
            echo submit_button(translate('Edit Resource'), 'machid') . hidden_fn('editResource')
				. '<input type="hidden" name="machid" value="' . $rs['machid'] . '" />' . "\n";
        	// Unset variables
			unset($rs);
		}
		else if ($type == 'd') {
			echo submit_button(translate('Delete Resource'), 'machid') . hidden_fn('delResource')
				. '<input type="hidden" name="machid" value="' . $rs['machid'] . '" />' . "\n";
        	// Unset variables
			unset($rs);
		}
		// Print cancel button as long as type is not "view"
		echo '&nbsp;&nbsp;&nbsp;<input type="button" name="close" value="' . translate('Cancel') . '" class="button" onclick="window.close();" /></p>';

		echo "</form>\n";
 */
}

function print_newschedule_edit($rs, $scheds, $type, &$pager, $login, $bill, $grouplist, $notes = '', $aEmail = null, $paymentArray = array(''=>'')) {
	global $conf;
	$start = 0;
	$end   = 1440;
	$mins = array(0, 10, 15, 30);
	$read_only = '';
	$mode = '';
	$ccprint = '';
	$showgroup = '';
	$memberid = $rs['scheduleTitle'];	
	//CmnFns::diagnose($rs);

	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
	$t = new Tools();

	$billtype = $bill['type'];
	$groupid = isset($rs['groupid']) ? $rs['groupid'] : ($login['groupid'] ? $login['groupid'] : 0); 
	if ($_SESSION['role']=='m') {
		$showgroup = '<tr><td class="formNames">Billing Group</td>
				<td class="cellColor">';
		if ($type=='c' || $type == 'm') {
			$showgroup .=	'<select name="admingroupid">
				<option value="0">None</option>';
			foreach ($grouplist as $k => $v) {
				$selected = ($type=='m' && $k==$groupid) ? 'selected' : '';
				$showgroup .= "<option value=\"$k\" $selected>$v</option>";
			}
			$showgroup .= '</select>';
		} else
			$showgroup .= $groupid ? $grouplist[$groupid] : 'None';

		$showgroup .= '</td></tr>';
	}
	if ($type == 'm') {
		$minH = intval($rs['minRes'] / 60);
		$minM = intval($rs['minRes'] % 60);
		$maxH = 24;//intval($rs['maxRes'] / 60);
		$maxM = intval($rs['maxRes'] % 60);
		$mode = 'e';
		?>
		<h3 align="center">Modify Passenger Schedule</h3>
		<?
	}
	else if ($type == 'c') {
		$mode = 'r';
		$maxH = 24;
		?>
		<h3 align="center">Create Passenger Schedule</h3>
	<?
	} else if ($type == 'd') {
		$maxH = 24;
		$read_only = "disabled = \"true\"";
	?>
		<h3 align="center">Delete Passenger Schedule</h3>
	<?
	} else if ($type == 'v') {
		$maxH = 24;
		$read_only = "disabled = \"true\"";
	?>
		<h3 align="center">View Passenger Schedule</h3>
	<?
	}

	// $ccprint and $ccdb now deprecated
	if ($type == 'v' || $type=='m') {
		list($num, $exp, $cvv2) = explode("+", $rs['other']);

		if ($num == '' || $exp == '') {
			$ccdb = "None";
		} else {
			$last4 = substr($num, -4);
			$ccdb = $_SESSION['role']=='m' ? '************' . $last4 : 'Yes';
		}
		$ccprint = '<tr><td class="formNames">Credit card on file</td>'
	          ."<td class=\"cellColor\">$ccdb $exp</td></tr>";
	}
	if ($_SESSION['role']=='a')
		echo 'Does your passenger already have an account?<br><a href="'.$_SERVER['PHP_SELF'].'?addExisting=1">Click here to add them</a>.';
    ?>
<form name="register" method="post" action="admin_update.php" onSubmit="return checkReg('<?=$mode?>')">
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td width="200" class="formNames">First Name</td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="fname" class="textbox" value="<?= isset($rs['fname']) ? $rs['fname'] : '' ?>" />
          </td>
        </tr>
        <tr>
          <td width="200" class="formNames">Last Name</td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="lname" class="textbox" value="<?= isset($rs['lname']) ? $rs['lname'] : '' ?>" />
          </td>
        </tr>
        <tr>
          <td class="formNames">E-mail Address (optional)</td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="emailaddress" class="textbox" value="<?= isset($rs['email']) ? $rs['email'] : '' ?>" />
	  <input type="hidden" name="noemail" value="on"> 
          </td>
        </tr>
	<?
	if (Auth::isAdmin() && $rs['role'] != 'm') {
		
		?>
	        <tr>
       		   <td class="formNames">Set new password</td>
       		   <td class="cellColor"><input type="text" name="newpassword" class="textbox" value="" />
       		   </td>
        	</tr>
		<?

	}

	?>
        <tr>
          <td class="formNames">Send email confirmations to Passenger?</td>
          <td class="cellColor"><input <?=$read_only?> type="radio" name="sendemail" value="y" <?= $rs['isHidden'] ? 'checked' : '' ?> />
          Yes<input <?=$read_only?> type="radio" name="sendemail" value="n" <?=$rs['isHidden'] ? '' : 'checked' ?> />No
          </td>
        </tr><?
	if ($_SESSION['role'] == 'a') {
       	   ?><tr>
       	   <td class="formNames">CC me on receipts?</td>
           <td class="cellColor"><input <?=$read_only?> type="radio" name="addAdminEmail" value="y" <?= $aEmail ? 'checked' : '' ?> />
           Yes<input <?=$read_only?> type="radio" name="addAdminEmail" value="n" <?=$aEmail ? '' : 'checked' ?> />No
	   <input type="hidden" name="aEmail" value="<?=$login['email']?>">
           </td>
           </tr><?
	}
	?><tr>
          <td class="formNames">Cell Phone Number (required)</td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="phone" class="textbox" value="<?= isset($rs['phone']) ? $rs['phone'] : '' ?>" />
          </td>
        </tr><?
	if (Auth::isAdmin() && ($type == 'c' || $type == 'm')) { ?>
        <tr>
          <td class="formNames">Role</td>
          <td class="cellColor"><?
		if ($type == 'm' && $rs['role'] != 'p' && $rs['role'] != 'a') {
			echo "This person's role can only be changed by the administrator.";
		} else {
			echo '<input type="radio" name="role" value="p" '.($type=='c' || $rs['role'] != 'a' ? 'checked' : '').'> Passenger<br>';
			echo '<input type="radio" name="role" value="a" '.($type=='m' && $rs['role'] == 'a' ? 'checked' : '').'> Schedule Manager';

		}
		?>
		</td></tr>
		<tr><td class="formNames">Price Type</td>
		<td class="cellColor">
		<?
		echo '<input type="radio" name="price_type" value="g" '.($rs['price_type'] == 'g' ? 'checked' : '').'> Granular<br>';
		echo '<input type="radio" name="price_type" value="z" '.($type=='c' || $rs['price_type'] == 'z' ? 'checked' : '').'> Zip Code';
		echo "</td></tr>";

	}
	?><tr>
          <td class="formNames">Dept. Code</td>
          <td class="cellColor"><input <?=$read_only?> type="text" name="position" class="textbox" value="<?= isset($rs['position']) ? $rs['position'] : '' ?>" />
          </td>
        </tr>
	<?
	if ($type=='c' || $type=='m') {
	  ?>
	  <tr bgcolor="#FFFFFF">
		<td>
		  Payment Options
		</td>
		<td>
		<?
		$t->print_dropdown($paymentArray, null, 'paymentProfileId', null, '', 'paymentProfileId');
		?>
		<br>
		<a href="javascript: paymentPopup('<?=$memberid?>', 'add')">Add Payment Info</a><br>
		<?

		if (!isset($paymentArray[''])) {
			?>
		<a href="javascript: paymentPopup('<?=$memberid?>', 'edit')">Edit Payment Info</a><br>
		<a href="javascript: paymentPopup('<?=$memberid?>', 'delete')">Delete Payment Info</a>
			<?
		}

		?>
		<input type="hidden" name="password" value="password">
		<input type="hidden" name="password2" value="password">
		<input type="hidden" name="memberid" value="<?=isset($rs['memberid'])?$rs['memberid']:''?>">
		<input type="hidden" name="manual" value="true">
		</td>
	  </tr>
	<?
	}
        //if ($type=='v' || $type=='m') echo $ccprint;
	echo $showgroup;
	if ($_SESSION['role'] == 'm') {
		echo '<tr><td class="cellColor" colspan=2 align="center">Profile Notes<br>';
		echo '<textarea rows=4 cols=40 name="notes" '.$read_only.'>'.$notes.'</textarea></td></tr>';
	}
	?>
	</table>
    </td>
  </tr>
</table>
<br />
<?
        // Print out correct buttons
        if ($type == 'c') {
	?>
	<input type="hidden" name="groupid" value="<?=$groupid?>">
	<input type="hidden" name="billtype" value="<?=$billtype?>">
	<?
            echo submit_button("Add Schedule", 'machid') . hidden_fn('addSchedule')
			. ' <input type="reset" name="reset" value="' . translate('Clear') . '" class="button" />' . "\n";
        }
		else if ($type == 'm') {
            echo submit_button("Modify Schedule", 'scheduleid') . hidden_fn('editSchedule')
				. '<input type="hidden" name="scheduleid" value="' . $rs['scheduleid'] . '" />' . "\n";
        	// Unset variables
			unset($rs);
		}
		else if ($type == 'd') {
			echo submit_button('Delete Schedule', 'scheduleid') . hidden_fn('delSchedule')
				. '<input type="hidden" name="scheduleid" value="' . $rs['scheduleid'] . '" />' . "\n";
        	// Unset variables
			unset($rs);
		}

		if ($type == 'v') {
			echo '&nbsp;&nbsp;&nbsp;<input type="button" name="close" value="' . 'Close' . '" class="button" onclick="window.close();" /></p>';
		} else {
		// Print cancel button as long as type is not "view"
		echo '&nbsp;&nbsp;&nbsp;<input type="button" name="close" value="' . translate('Cancel') . '" class="button" onclick="window.close();" /></p>';
		}
		echo "</form>\n";
}
/**
* Interface for managing user training
* Provide interface for viewing and managing
*  user training information
* @param object $user User object of user to manage
* @param array $rs list of resources
*/
function print_manage_perms(&$user, $rs, $err) {
	global $link;

	if (!$user->is_valid()) {
		CmnFns::do_error_box($user->get_error() . '<br /><a href="' . $_SERVER['PHP_SELF'] . '?tool=users">' . translate('Back') . '</a>', '', false);
		return;
	}

	echo '<h3>' . $user->get_name() . '</h3>';
    ?>
<form name="train" method="post" action="admin_update.php">
  <table border="0" cellspacing="0" cellpadding="1">
    <tr>
      <td class="tableBorder">
        <table cellspacing="1" cellpadding="2" border="0" width="100%">
          <tr class="rowHeaders">
            <td width="240"><?=translate('Resource Name')?></td>
            <td width="60"><?=translate('Allowed')?></td>
          </tr>
          <?
			if (!$rs) echo '<tr class="cellColor0" style="text-align: center;"><td colspan="2">' . $err . '</td></tr>';

			for ($i = 0; is_array($rs) && $i < count($rs); $i++) {
				echo '<tr class="cellColor"><td>' . $rs[$i]['name'] . '</td><td style="text-align: center;">'
					. '<input type="checkbox" name="machid[]" value="' . $rs[$i]['machid'] . '"';
				if ($user->has_perm($rs[$i]['machid']))
					echo ' checked="checked"';
				echo '/></td></tr>';
		  	}

    // Close off tables/forms.  Print buttons and hidden field
    ?>
          <tr class="cellColor1">
            <td>&nbsp;</td>
            <td style="text-align: center;">
              <input type="checkbox" name="checkAll" onclick="checkAllBoxes(this);" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <input type="hidden" name="memberid" value="<?=$user->get_id()?>" />
  <p style="padding-top: 5px; padding-bottom: 5px;"><input type="checkbox" name="notify_user" value="true" /><?=translate('Notify user')?></p>
  <?= submit_button(translate('Save')) . hidden_fn('editPerms')?>
  <input type="button" name="cancel" value="<?=translate('Manage Users')?>" class="button" onclick="document.location='<?=$_SERVER['PHP_SELF']?>?tool=users';" />
</form>
<?
}


/**
* Interface for managing reservations
* Provide a table to allow admin to modify or delete reservations
* @param Object $pager pager object
* @param mixed $res reservation data
* @param string $err last database error
*/
function print_manage_reservations(&$pager, $res, $err) {
	global $link;

?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="8" class="tableTitle">&#8250; <?=translate('User Reservations')?></td>
        </tr>
        <tr class="rowHeaders">
          <td width="10%"><?=translate('Date')?></td>
          <td width="27%"><?=translate('User')?></td>
          <td width="16%">From</td>
          <td width="16%">To</td>
          <td width="10%">Pickup Time</td>
          <td width="7%"><?=translate('View')?></td>
          <td width="7%"><?=translate('Modify')?></td>
          <td width="7%"><?=translate('Delete')?></td>
        </tr>
        <tr class="cellColor" style="text-align: center">
          <td> <? printDescLink($pager, 'date', 'date') ?> &nbsp;&nbsp; <? printAscLink($pager, 'date', 'date') ?> </td>
          <td> <? printDescLink($pager, 'lname', 'user name') ?> &nbsp;&nbsp; <? printAscLink($pager, 'lname', 'user name') ?> </td>
          <td> <? printDescLink($pager, 'name', 'resource name') ?> &nbsp;&nbsp; <? printAscLink($pager, 'name', 'resource name') ?> </td>
          <td> <? printDescLink($pager, 'startTime', 'start time') ?> &nbsp;&nbsp; <? printAscLink($pager, 'startTime', 'start time') ?> </td>
          <td> <? printDescLink($pager, 'endTime', 'end time') ?> &nbsp;&nbsp; <? printAscLink($pager, 'endTime', 'end time') ?> </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?
	// Write message if they have no reservations
	if (!$res)
		echo '<tr class="cellColor"><td colspan="8" align="center">' . $err . '</td></tr>';

	// For each reservation, clean up the date/time and print it
	for ($i = 0; is_array($res) && $i < count($res); $i++) {
		$cur = $res[$i];
		$fname = $cur['fname'];
		$lname = $cur['lname'];
        echo "<tr class=\"cellColor" . ($i%2) . "\" align=\"center\">\n"
					. '<td>' . CmnFns::formatDate($cur['date']) . '</td>'
					. '<td style="text-align:left">' . $link->getLink("javascript: viewUser('" . $cur['memberid'] . "');", $fname . ' ' . $lname, '', '', translate('View information about', array($fname,$lname))) . "</td>"
                    . '<td style="text-align:left">' . ($cur['fromLoc'] == "" ? $cur['fromName'] : $cur['fromLoc'] . ", " . $cur['fromCity']) . "</td>"
                    . '<td style="text-align:left">' . ($cur['toLoc'] == "" ? $cur['toName'] : $cur['toLoc']. ", " . $cur['toCity']). "</td>"
					. '<td>' . CmnFns::formatTime($cur['startTime']) . '</td>'
                    . '<td>' . $link->getLink("javascript: reserve('v','','','" . $cur['resid']. "');", translate('View')) . '</td>'
					. '<td>' . $link->getlink("javascript: reserve('m','','','" . $cur['resid']. "');", translate('Modify')) . '</td>'
					. '<td>' . $link->getLink("javascript: reserve('d','','','" . $cur['resid']. "');", translate('Delete')) . '</td>'
					. "</tr>\n";
	}
    ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?
}


/**
* Prints out GUI list to of email addresses
* Prints out a table with option to email users,
*  and prints form to enter subject and message of email
* @param array $users user data
* @param string $sub subject of email
* @param string $msg message of email
* @param array $usr users to send to
* @param string $err last database error
*/
function print_manage_email($users, $sub, $msg, $usr, $err) {
	?>
<form name="emailUsers" method="post" action="<?=$_SERVER['PHP_SELF'] . '?tool=' . $_GET['tool']?>">
  <table width="60%" border="0" cellspacing="0" cellpadding="1">
    <tr>
      <td class="tableBorder">
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td colspan="3" class="tableTitle">&#8250; <?=translate('Email Users')?></td>
          </tr>
          <tr class="rowHeaders">
            <td width="15%">&nbsp;</td>
            <td width="40%"><?=translate('User')?></td>
            <td width="45%"><?=translate('Email')?></td>
          </tr>
          <?
	if (!$users)
		echo '<tr class="cellColor0" style="text-align: center;"><td colspan="3">' . $err . '</td></tr>';
    // Print users out in table
    for ($i = 0; is_array($users) && $i < count($users); $i++) {
		$cur = $users[$i];
        echo '<tr class="cellColor' . ($i%2) . "\">\n"
            . '<td style="text-align: center;"><input type="checkbox" ';
		if ( empty($usr) || in_array($cur['email'], $usr) )
			echo 'checked="checked" ';
		echo 'name="emailIDs[]" value="' . $cur['email'] . "\" /></td>\n"
            . '<td>&lt;' . $cur['lname'] . ', ' . $cur['fname'] . '&gt;</td>'
            . '<td>' . $cur['email'] . '</td>'
            . "</tr>\n";
    }
    ?>
          <tr>
            <td class="cellColor0" style="text-align: center;">
              <input type="checkbox" name="checkAll" checked="checked" onclick="checkAllBoxes(this);" />
            </td>
			<td colspan="2" class="cellColor0">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
  <table width="60%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="15%"><p><?=translate('Subject')?></p>
      </td>
      <td><input type="text" name="subject" size="60" class="textbox" value="<?=$sub?>"/>
      </td>
    </tr>
    <tr>
      <td valign="top"><p><?=translate('Message')?></p>
      </td>
      <td><textarea rows="10" cols="60" name="message" class="textbox"><?=$msg?></textarea>
      </td>
    </tr>
  </table>
  <input type="submit" name="previewEmail" value="<?=translate('Next')?> &gt;" class="button" />
</form>
<?
}

/**
* Prints out a preview of the email to be sent
* @param string $sub subject of email
* @param string $msg message of email
* @param array $usr array of users to send the email to
*/
function preview_email($sub, $msg, $usr) {
?>
<table width="60%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td bgcolor="#DEDEDE">
      <table width="100%" cellpadding="3" cellspacing="1" border="0">
        <tr class="cellColor0">
          <td><?=$sub?>
          </td>
        </tr>
        <tr class="cellColor0">
          <td><?=$msg?>
          </td>
        </tr>
		<tr class="cellColor0">
		  <td>
		  <?
		  if (empty($usr)) echo translate('Please select users');
		  foreach ($usr as $email) echo $email . '<br />'
		  ?>
		  </td>
		</tr>
      </table>
    </td>
  </tr>
</table>
<br />
<form action="<?=$_SERVER['PHP_SELF'] . '?tool=' . $_GET['tool']?>" method="post" name="send_email">
<input type="button" name="goback" value="&lt; <?=translate('Back')?>" class="button" onclick="history.back();" />
<input type="submit" name="sendEmail" value="<?=translate('Send Email')?>" class="button" />
</form>
<?
}


/**
* Actually sends the email to all addresses in POST
* @param string $subject subject of email
* @param string $msg email message
* @param array $success array of users that email was successful for
*/
function print_email_results($subject, $msg, $success) {
    if (!$success)
		CmnFns::do_error_box(translate('problem sending email'), '', false);
	else {
		CmnFns::do_message_box(translate('The email sent successfully.'));
	}

    echo '<h4 align="center">' . translate('do not refresh page') . '<br/>'
        . '<a href="' . $_SERVER['PHP_SELF'] . '?tool=email">' . translate('Return to email management') . '</a></h4>';
}

/**
* Prints out a list of tables and all the fields in them
*  with an option to select which tables and fields should be exported
*  and in which format
* @param array $tables array of tables
* @param array $fields array of fields for each table
*/
function show_tables($tables, $fields) {
	echo '<h5>' . translate('Please select which tables and fields to export') . '</h5>'
		. '<form name="get_fields" action="' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '" method="post">' . "\n";
	for ($i = 0; $i < count($tables); $i++) {
		echo '<p><input type="checkbox" name="table[]" value="' . $tables[$i] . '"  checked="checked" onclick="javascript: toggle_fields(this);" />' . $tables[$i] . "</p>\n";

		echo '<select name="table,' . $tables[$i] . '[]" multiple="multiple" size="5" class="textbox">' . "\n";
		echo '<option value="all" selected="selected">' . translate('all fields') . "</option>\n";
		for ($k = 0; $k < count($fields[$tables[$i]]); $k++)
			echo  '<option value="' . $fields[$tables[$i]][$k] . '">' . $fields[$tables[$i]][$k] . '</option>' . "\n";

		echo "</select><br />\n";
	}
	echo '<p><input type="radio" name="type" value="xml" checked="checked" />' . translate('XML')
		. '<input type="radio" name="type" value="csv" />' . translate('CSV')
		. '</p><br /><input type="submit" name="submit" value="' . translate('Export Data') . '" class="button" /></form>';
}

/**
* Begins the line of table data
* @param boolean $xml if this is in XML or not
* @param string $table_name name of this table
*/
function start_exported_data($xml, $table_name) {
	echo '<pre>';
	echo ($xml) ? "&lt;$table_name&gt;\r\n" : '';
}

/**
* Prints out the exported data in XML or CSV format
* @param array $data array of data to print out
* @param boolean $xml whether to print XML or not
*/
function print_exported_data($data, $xml) {
	$first_row = true;
	for ($x = 0; $x < count($data); $x++) {
		echo ($xml) ? "\t&lt;record&gt;\r\n" : '';

		if (!$xml && $first_row) {				// Print out names of fields for first row of CSV
				$keys = array_keys($data[$x]);
				for ($i = 0; $i < count($keys); $i++) {
					echo '"' . $keys[$i] . '"';
					if ($i < count($keys)-1) echo ',';
				}
				echo "\r\n";
		}

		$first_row = false;

		$first_csv = '"';
		foreach ($data[$x] as $k => $v) {
			echo ($xml) ? "\t\t&lt;$k&gt;$v&lt;/$k&gt;\r\n" : $first_csv . addslashes($v) . '"';
			$first_csv = ',"';
		}
		echo ($xml) ? "\t&lt;/record&gt;\r\n" : "\r\n";
	}
}

/**
* Prints out an interface to manage blackout times for this resource
* @param array $resource array of resource data
* @param array $blackouts array of blackout data
*/
function print_blackouts($resource, $blackouts) {
	for ($i = 0; $i < count($resouce); $i++)
		echo $resouce[$i] . '<br />';
}

/**
* Ends the line of table data
* @param boolean $xml if this is in XML or not
* @param string $table_name name of this table
*/
function end_exported_data($xml, $table_name) {
	echo ($xml) ? "&lt;/$table_name&gt;\r\n" : '';
	echo '</pre>';
}

/**
* Prints the form to reset a users password
* @param object $user user object
*/
function print_reset_password(&$user) {
?>
<form name="resetpw" method="post" action="admin_update.php">
  <table border="0" cellspacing="0" cellpadding="1" width="50%">
    <tr>
      <td class="tableBorder">
        <table cellspacing="1" cellpadding="2" border="0" width="100%">
          <tr class="rowHeaders">
		  	<td colspan="2"><?=translate('Reset Password for', array($user->get_name()))?></td>
		  </tr>
		  <tr class="cellColor">
            <td width="15%" valign="top"><?=translate('Password')?></td>
			<td><input type="password" value="" class="textbox" name="password" />
			<br />
			<i><?=translate('If no value is specified, the default password set in the config file will be used.')?></i>
			</td>
		  <tr class="cellColor">
		    <td colspan="2"><input type="checkbox" name="notify_user" value="true" checked="checked"/><?=translate('Notify user that password has been changed?')?></td>
		  </tr>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <input type="hidden" name="memberid" value="<?=$user->get_id()?>" />
  <br />
  <?= submit_button(translate('Save')) . hidden_fn('resetPass')?>
  <input type="button" name="cancel" value="<?=translate('Manage Users')?>" class="button" onclick="document.location='<?=$_SERVER['PHP_SELF']?>?tool=users';" />
</form>
<?
}

/**
* Prints out a link to reorder recordset ascending order
* @param Object $pager pager object
* @param string $order order to sort result set by
* @param string $text link text
* @see print_asc_desc_link()
*/
function printAscLink(&$pager, $order, $text) {
	$text = translate("Sort by ascending $text");
	print_asc_desc_link($pager, $order, $text, 'ASC');
}

/**
* Prints out a link to reorder recordset descending order
* @param Object $pager pager object
* @param string $order order to sort result set by
* @param string $text link text
* @see print_asc_desc_link()
*/
function printDescLink(&$pager, $order, $text) {
	$text = translate("Sort by descending $text");
	print_asc_desc_link($pager, $order, $text, 'DESC');
}

/**
* This function extends the printAscLink and printDescLink, printing out
*  a link to reorder a recordset in a certain order
* This was added to keep the current printAsc/DescLink functions in place, but put
*  all logic into one function
* @param Object $pager pager object
* @param string $order order to sort result set by
* @param string $text link text
* @param string $vert ascending or descending order
*/
function print_asc_desc_link(&$pager, $order, $text, $vert) {
	global $link;

	$tool = getTool();
	$page = $pager->getPageNum();

	$plus_minus = ($vert == 'ASC') ? '[+]' : '[&#8211;]';		// Plus or minus box
	$limit_str = '&amp;' . $pager->getLimitVar() . '=' . $pager->getLimit();
	$page_str  = '&amp;' . $pager->getPageVar() . '=' . $pager->getPageNum();
	$vert_str  = "&amp;vert=$vert";

	// Fix up the query string
	$query =  $_SERVER['QUERY_STRING'];
	if (eregi('(\?|&)' . $pager->getLimitVar() . "=[0-9]*", $query))
		$query = eregi_replace('(\?|&)' . $pager->getLimitVar() . "=[0-9]*", $limit_str, $query);
	else
		$query .= $limit_str;

	if (eregi('(\?|&)' . $pager->getPageVar() . "=[0-9]*", $query))
		$query = eregi_replace('(\?|&)' . $pager->getPageVar() . "=[0-9]*", $page_str, $query);
	else
		$query .= $page_str;

	if (eregi("(\?|&)vert=[a-zA-Z]*", $query))
		$query = eregi_replace("(\?|&)vert=[a-zA-Z]*", $vert_str, $query);
	else
		$query .= $vert_str;

	if (eregi("(\?|&)order=[a-zA-Z]*", $query))
		$query = eregi_replace("(\?|&)order=[a-zA-Z]*", "&amp;order=$order", $query);
	else
		$query .= "&amp;order=$order";

	$link->doLink($_SERVER['PHP_SELF'] . '?' . $query, $plus_minus, '', '', $text);
}

/**
* Returns a button to cancel editing
* @param none
* @return string of html for a cancel button
*/
function cancel_button(&$pager) {
	return '<input type="button" name="cancel" value="' . translate('Cancel') . '" class="button" onclick="javascript: document.location=\'' . $_SERVER['PHP_SELF'] . '?tool=' . $_GET['tool'] . '&amp;' . $pager->getLimitVar() . '=' . $pager->getLimit() . '&amp;' . $pager->getPageVar() . '=' . $pager->getPageNum() . '\';" />' . "\n";
}

/**
* Returns a submit button with $value value
* @param string $value value of button
* @param string $get_value value in the query string for editing an item (ie, to edit a resource its machid)
* @return string of html for a submit button
*/
function submit_button($value, $get_value = '') {
	return '<input type="submit" name="submit" value="' . $value . '" class="button" />' . "\n"
			. '<input type="hidden" name="get" value="' . $get_value  . '" />' . "\n";
}

/**
* Returns a hidden fn field
* @param string $value value of the hidden field
* @return string of html for hidden fn field
*/
function hidden_fn($value) {
	return '<input type="hidden" name="fn" value="'. $value . '" />' . "\n";
}
/*
*
*/
function addExistingForm() {
	?>
	<form name="schedSearch" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	Enter the email address of the existing passenger:<br>
	<br>
	<input type="text" name="schedEmail"> 
	<input type="submit" value="Search">
	<input type="hidden" name="addExisting" value="1">
	<input type="hidden" name="search" value="1">
	</form>
	<br>
	Note: Only passengers from your own billing group will be available to add.
	<?
}
?>
