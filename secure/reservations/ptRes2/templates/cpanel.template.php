<?php
/**
* This file provides output functions for ctrlpnl.php
* No data manipulation is done in this file
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @author Adam Moore
* @version 05-27-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

// Get Link object
$link = CmnFns::getNewLink();

/**
* This function prints out the announcement table
*
* @param none
* @global $conf
*/
function showAnnouncementTable() {
	global $link;
	global $conf;
    ?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td class="tableTitle">
		    <a href="javascript: void(0);" onclick="showHideCpanelTable('announcements');">&#8250; <?=translate('My Announcements')?></a>
		  </td>
          <td class="tableTitle">
            <div align="right">
              <? $link->doLink('javascript: help(\'my_announcements\');', 'Click Here for Help', '', 'color: #FFFFFF;', 'Click Here for Help' . ' - ' . translate('My Announcements')) ?>
            </div>
          </td>
        </tr>
      </table>
      <div id="announcements" style="display: <?= getShowHide('announcements') ?>">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr class="cellColor">
          <td colspan="2"><b><?= translate('Announcements as of', array(CmnFns::formatDate(mktime()))) ?></b>
            <ul style="margin-bottom: 0px; margin-left: 20px; margin-top: 5px">
              <?
				// Cycle through and print out machines
				if (count($conf['ui']['announcement'])<=0) {
					echo '<li>' . translate('There are no announcements.') . "</li>\n";
				}
				for ($i = count($conf['ui']['announcement'])-1; $i >=0; $i--) {
					if (isset($conf['ui']['announcement'][$i]))
						echo '<li>' . htmlspecialchars($conf['ui']['announcement'][$i]) . '</li>';
				}
				?>
            </ul>
          </td>
        </tr>
      </table>
	 </div>
    </td>
  </tr>
</table>
<?
}

if(!function_exists('_wordwrap')) {
  function _wordwrap($text)   {
      $split = explode(" ", $text);
      foreach($split as $key=>$value)   {
	  if (strlen($value) > 6)    {
	      $split[$key] = chunk_split($value, 5, "&#8203;");
	  }
      }
      return implode(" ", $split);
  }
}

function showSchedulesTable($res, $err) {
	global $link;
?>
<style>
  table tr th,
  table tr td {
    border-bottom: 1px solid #CCCCCC;
    border-right: 1px solid #CCCCCC;
    font-size: 0.8em;
    padding: 3px;
    text-align: left;
  }
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="1" class="tableTitle">
		  &#8250; Passenger Schedules
		  </td>
		  <td align="right" class="tableTitle">
		  <input type="button"  value="Create Passenger Schedule" onmouseup="schedule('c','','','','');" onmouseover="window.status='Create Passenger Schedule'; return true;" onmouseout="window.status=''; return true;">
		  <? //$link->doLink("javascript: schedule('c','','','', '');", translate('New Schedule'), '', '', translate('New Schedule', array($name, CmnFns::formatDate($ts))))?>
		</td>
          <!--td class="tableTitle">
            <div align="right">
              <? $link->doLink('javascript: help(\'my_schedule\');', '?', '', 'color: #FFFFFF;', translate('Help') . ' - ' . translate('My Schedules')) ?>
            </div>
          </td>-->
        </tr>
      </table>
      <div id="schedule" style="display: <?= getShowHide('schedule') ?>">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr class="rowHeaders">
          <td width="40%">Passenger</td>
	  <td width="40%">email</td>
          <!-- <td width="20%">Company/Organization</td>-->
          <!-- <td width="8%">Dept. Code</td>-->
          <!-- <td width="11%">Phone</td>-->
          <!--td width="7%">View</td-->
          <td width="10%">Modify</td>
          <td width="10%">Delete</td>
        </tr>
       <!-- <tr class="cellColor" style="text-align: center">
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=name&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending resource name')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=name&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending resource name')) ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=created&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending created time')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=created&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending created time')) ?>
          </td>
          <td>
            <?
			$link->doLink($_SERVER['PHP_SELF'].'?order=modified&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending last modified time')) ?>
			&nbsp;&nbsp;
            <?
			$link->doLink($_SERVER['PHP_SELF'].'?order=modified&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending last modified time')) ?>
          </td>
          <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>-->
        <?

	// Write message if they have no reservations
	if (!$res)
		echo '        <tr class="cellColor"><td colspan="9" align="center">' . $err . '</td></tr>';

	// Print each schedule
	for ($i = 0; is_array($res) && $i < count($res); $i++) {
		$rs = $res[$i];
		$class = 'cellColor' . ($i%2);
		//$b = "..".$rs['group_type']."--";

		if ($rs['group_type']=='c'||!$rs['group_type']) {
			if (!$rs['hascard'])
				$class = 'cellColorYellow';

		}


		if ($rs['role'] == 'x') $class = 'cellColor13';
        $modified = (isset($rs['modified']) && !empty($rs['modified'])) ?
		CmnFns::formatDateTime($rs['modified']) : 'N/A';
        echo "        <tr class=\"$class\" align=\"center\">"
					. '          <td style="text-align:left;"><a href="' . $_SERVER['PHP_SELF'].'?currentId='.$rs['memberid'].'&fname='
								. $rs['fname'] . '&lname=' . $rs['lname'] . '&active=view">'
								. _wordwrap($rs['fname'] . ' ' . $rs['lname']) . '</a></td>'
					. '          <td style="text-align:left;">' . _wordwrap($rs['email']) . '</td>'
					//. '          <td style="text-align:left;">' . _wordwrap($rs['institution']) . '</td>'
					//. '          <td style="text-align:left;">' . _wordwrap($rs['position']) . '</td>'
					//. '          <td style="text-align:left;">' . _wordwrap($rs['phone']) . '</td>'
                    . '          <!--td>' . $link->getLink("javascript: schedule('v','','','','" . $rs['scheduleid'] . "');", translate('View'), '', '', 'View this schedule') . '</td-->'
                    . '          <td>' . $link->getLink("javascript: schedule('m','','','','" . $rs['scheduleid'] . "');", translate('Modify'), '', '', 'Modify this schedule') . '</td>';
	if($rs['memberid'] == $_SESSION['sessionID']) {
		echo '<td>&nbsp;</td>';
	} else {
		echo '          <td>' . $link->getLink("javascript: schedule('d','','','','" . $rs['scheduleid'] . "');", translate('Delete'), '', '', 'Delete this schedule') . '</td>';
	//. ' <td>&nbsp;</td><td>&nbsp;</td>'
	}
	echo "        </tr>\n";
	}
	unset($res);
?>
      </table>
	  </div>
    </td>
  </tr>
</table>
<?
}

/**
* Print table listing upcoming reservations
* This function prints a table of all upcoming
* reservations for the current user.  It also
* provides a way for them to modify and delete
* their reservations
* @param mixed $res array of reservation data
* @param string $err last error message from database
*/
function showReservationTable($res, $err) {
	global $link;

?>
<h1 id="hdr_upcoming"><span class="imagetext">Upcoming Reservations</span></h1>
<h2>Reservations for <?=$_SESSION['currentName']?></h2>
<?php /*
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td colspan="1" class="tableTitle">
            &#8250; <?= translate('My Reservations').' '.stripslashes($_SESSION['currentName']) ?>
          </td>
          <td align="right" class="tableTitle">
            <? //$link->doLink("javascript: reserve('r','','','','');", translate('New Reservation'), '', '', translate('New Reservation', array($name, CmnFns::formatDate($ts))))?>
            <input type="button" value="Create New Reservation" onmouseup="reserve('r','','','','');" onmouseover="window.status='Create New Reservation'; return true;" onmouseout="window.status=''; return true;">
          </td>
            <!--<td class="tableTitle">
              <div align="right">
          <? $link->doLink('javascript: help(\'my_reservations\');', '?', '', 'color: #FFFFFF;', translate('Help').' - '.translate('My Reservations')) ?>
              </div>
            </td>-->
        </tr>
      </table>
      <div id="reservation" style="display: <?= getShowHide('reservation') ?>">
*/ ?>
      <table>
        <thead>
          <tr>
            <th>Res #</th>
            <th>Date & Time</th>
            <th>From</th>
            <th>To</th>
            <th>Flight Info</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($res)): ?>
            <tr><td align="center" colspan="7">You do not have any reservations.</td></tr>
          <?php else: ?>
            <?php foreach($res as $value): ?>
            <?php //echo '<pre>'; print_r($value); echo '</pre>'; ?>
              <tr>
                <td><?php echo substr($value['resid'], strlen($value['resid'])-6) ?></td>
                <td><?php
					/*
					 * $time and date returned as string from CmnFns.
					 * - update... issues with every function I've used.  even manual math.
					 * - I get significantly different results from CmnFns::formatDate and Time
					 * - on the QA server vs my QA.  Need to figure this out...
					 */
					//echo date('m/d/Y', $value['date']) . " " . date("h:i A", 60*$value['pickupTime']);
                	$time = CmnFns::formatTime($value['pickupTime']);
					$date = CmnFns::formatDate($value['date']);
                	echo $date . " " . $time;
				  	?>
				</td>
                <td><?php echo $value['fromLocationName'] ?></td>
                <td><?php echo $value['toLocationName'] ?></td>
                <td></td>
                <td>
                  <a href="reserve.php?type=m&resid=<?php echo $value['resid'] ?>">Edit</a>
                  <!-- Will take user to pre-filled Reservation page (Step 1 of 4) -->
                  | <a title="Delete upcoming reservation?" class="popover-delete parentTr" href="/pop/delete_reservation.php?resid=<?php echo $value['resid'] ?>">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
<?php /*
       <!-- <tr class="cellColor" style="text-align: center">
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=date&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending date')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=date&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending date')) ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=name&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending resource name')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=name&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending resource name')) ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=startTime&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending start time')) ?>
			&nbsp;&nbsp;
            <?
			$link->doLink($_SERVER['PHP_SELF'].'?order=startTime&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending start time')) ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=endTime&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending end time')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=endTime&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending end time')) ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=created&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending created time')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=created&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending created time')) ?>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
		  <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>-->
        <?

	// Write message if they have no reservations
	if (!$res)
		echo '        <tr class="cellColor"><td colspan="10" align="center">' . $err . '</td></tr>';

    // For each reservation, clean up the date/time and print it
	for ($i = 0; is_array($res) && $i < count($res); $i++) {
		$rs = $res[$i];
		list($acode, $fnum, $fdets) = explode("{`}", $rs['flightDets']);
		$flightDets = $acode . $fnum . " " . $fdets;
		$class = 'cellColor' . ($i%2);
		$notes = DBEngine::parseNotes($rs['summary']);
		$name = $notes['name'] ? $notes['name'] : $rs['firstName']." ".$rs['lastName'];
		//. '<td style="text-align:left;">' . $rs['firstName'] . ' ' . $rs['lastName'] . '</td>'
        $modified = (isset($rs['modified']) && !empty($rs['modified'])) ?
		CmnFns::formatDateTime($rs['modified']) : 'N/A';
        echo "        <tr class=\"$class\" align=\"center\">"
		.'<td>'.strtoupper(substr($rs['resid'], -6)).'</td>'
		. '<td>' . $link->getLink("javascript: reserve('v','','','" . $rs['resid']. "');", CmnFns::formatDate($rs['date']), '', '', translate('View this reservation')) . '</td>'
		. '<td style="text-align:left;">' . $name . '</td>'
		. '<td style="text-align:left;">' . $rs['fromLocationName'] . '</td>'
		. '<td style="text-align:left;">' . $rs['toLocationName'] . '</td>'
		. '<td>' . CmnFns::formatTime($rs['pickupTime']) . '</td>'
		. '<td>' . ($rs['checkBags'] ? 'yes' : 'no') . '</td>'
                . '<td style="text-align:left;">' . $flightDets . '</td>'
                . '<td>' . $link->getLink("javascript: reserve('m','','','" . $rs['resid'] . "');", translate('Modify'), '', '', translate('Modify this reservation')) . '</td>'
		. '          <td>' . $link->getLink("javascript: reserve('d','','','" . $rs['resid'] . "');", translate('Delete'), '', '', translate('Delete this reservation')) . '</td>'
		. "</tr>\n";
	}
	unset($res);
*/
?>
      </table>
<?
}
function showPage($page) {
	$upper = $page['total'] > $page['upper'] ? $page['upper'] : $page['total'];
	$baklink = '&lt;&lt; Prev';
	$nexlink = 'Next &gt;&gt;';
	$dates = isset($_GET['monthLow']) ? "&monthLow={$_GET['monthLow']}&yearLow={$_GET['yearLow']}&monthHi={$_GET['monthHi']}&yearHi={$_GET['yearHi']}":'';
	if ($page['total'] > $page['upper'])
		$nexlink = '<a href="'.$_SERVER['PHP_SELF'].'?page='.($page['page']+1).$dates.'">'.$nexlink.'</a>';

	if ($page['lower'] > 0)
		$baklink = '<a href="'.$_SERVER['PHP_SELF'].'?page='.($page['page']-1).$dates.'">'.$baklink.'</a>';

	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr class="pageHead">
		<td align="left" width="25%">Showing results <?=$page['lower']?> - <?=$upper?> of <?=$page['total']?></td>
		<td align="center" width="50%">
		<?=$baklink?>&nbsp;&nbsp;&nbsp;<?=$nexlink?>
		</td width="25%">
		<td>&nbsp;</td>
	</tr>
	</table>
	<?
}
function showReceiptsTable($res, $err) {
	global $conf;
	global $link;
	$page = $res[0];

	$search = array_pop($res);	// Grab the $search flag that was pushed in DBEngine.class.php
//	showPage($page);
/*
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td class="tableTitle">
		    &#8250; Receipts for <?=$_SESSION['currentName']?>
		  </td>
          <!--<td class="tableTitle">
            <div align="right">
              <? $link->doLink('javascript: help(\'my_reservations\');', '?', '', 'color: #FFFFFF;', translate('Help') . ' - ' . translate('My Reservations')) ?>
            </div>
          </td>-->
        </tr>
      </table>
      <div id="reservation" style="display: <?= getShowHide('reservation') ?>">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr class="rowHeaders">
          <td width="10%">Date</td>
	  <td width="17%">Scheduled for</td>
          <td width="18%">from Location</td>
	  <td width="18%">to Location</td>
          <td width="10%">Pickup Time</td>
          <td width="10%">Fare</td>
          <td width="7%">&nbsp;</td>
          <td width="10%">&nbsp;</td>
        </tr>
*/
include dirname(__FILE__).'/../../../../config/paths.php';
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <caption class="group">

    <h2><?php
     if ($search) {
     	echo "Search Results";
     } else {
     	print $_SESSION['currentName'] . "'s Trips";
	}?></h2>

    <?php  // if there is no search, give them their monthly export tab
        if (!$search) { ?>
            <a href="<?php echo $securePrefix ?>/export_receipts.php">Export My Receipts</a>
    <?php } ?>


  </caption>
  <tr>
  	<?php if ($search) { // If a search was made, show a name column ?>
  	<th>Name</th>
  	<?php } ?>
    <th>Reservation</th>
    <th>Date</th>
    <th>Pickup</th>
    <th>Fare</th>
    <th>From</th>
    <th>To</th>
    <th>Receipt</th>
  </tr>
<?php /*
       <!-- <tr class="cellColor" style="text-align: center">
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=date&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending date')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=date&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending date')) ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=name&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending resource name')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=name&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending resource name')) ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=startTime&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending start time')) ?>
			&nbsp;&nbsp;
            <?
			$link->doLink($_SERVER['PHP_SELF'].'?order=startTime&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending start time')) ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=endTime&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending end time')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=endTime&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending end time')) ?>
          </td>
          <td>
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=created&amp;vert=DESC', '[&#8211;]', '', '', translate('Sort by descending created time')) ?>
			&nbsp;&nbsp;
            <? $link->doLink($_SERVER['PHP_SELF'].'?order=created&amp;vert=ASC', '[+]', '', '', translate('Sort by ascending created time')) ?>
          </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
		  <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>-->
*/

	// Write message if they have no receipts
	if (!$res)
		echo '        <tr class="cellColor"><td colspan="9" align="center">You do not have any receipts.</td></tr>';

	/*********************************************************
	* print receipts
	*/
	for ($i = 0; is_array($res) && $i < count($res); $i++) {
		$rs = $res[$i];
		if (isset($rs['total'])) {
			//$page = $rs;
			continue;
		}
		list($acode, $fnum, $fdets) = explode("{`}", $rs['flightDets']);
		$flightDets = $acode . $fnum . " " . $fdets;
		$class = 'cellColor' . ($i%2);

		// only show receipts for PAID trips
		$receipt = $rs['pay_status'] == 25 ? "<a href=\"newReceipt.php?resid={$rs['resid']}\">Receipt</a>" : "&nbsp;";

		// only show feedback for trips 30 days or newer
		$feedback = $rs['date'] > (time() - 60*60*24*30) ? "<a href=\"survey.php?resid={$rs['resid']}\">Feedback</a>" : "&nbsp;";
		if ($rs['feedbackDone']) $feedback = "Feedback sent";

		$modified = (isset($rs['modified']) && !empty($rs['modified']))?
			CmnFns::formatDateTime($rs['modified']) : 'N/A';
//echo '<pre>';
//    print_r($rs);
//echo '</pre>';
//    $hours = floor($rs['startTime'] / 60);
//    $minutes = $rs['startTime'] % 60;
//    $am = ($hours / 12) < 1;
//    var_dump($rs['date']);
//    $hours = date('h', $rs['date']);
//    $minutes = date('i', $rs['date']);
//    $am = ($hours / 12) < 1;


    $member = mysql_fetch_assoc(mysql_query("select * from login where memberid='".$rs['memberid']."'"));
?>
    <tr>
    <?php if ($search) { // If a search was made, show the name of the person the trip was for ?>
      <td><?php echo $rs['firstName'] . " " . $rs['lastName']; ?></td>
    <?php }
    /* <a href="javascript:reserve('v','','','<?php echo $rs['resid'] ?>', '', '1')">View</a> |
     * Temporarily removed the View link from the receipts view.
     */
     ?>

      <td><a href="javascript:pop_survey('<?php echo $rs['resid']?>')">Send Feedback</a></td>
      <? // <td><?php echo date('m/d/Y', $rs['date']) ?>
	  <td><?php echo CmnFns::formatDate($rs['date']) ?></td>
      <td><?php echo CmnFns::formatTime($rs['startTime'])?></td>
      <td>$<?php echo money_format("%(.2n",$res['total_fare'])?></td>
      <td><?php echo $rs['fromLocationName'] ?></td>
      <td><?php echo $rs['toLocationName'] ?></td>
      <td><a href="newReceipt.php?resid=<?php echo $rs['resid'] ?>">PDF</a> |
          <a href="javascript:pop_email('<?php echo $rs['resid']?>', '<?php echo $member['email']?>')">Email</a></td>
    </tr>


<?php
//$_SESSION['sessionAdmin']
/*
		echo "<tr class=\"$class\" align=\"center\">"
		. '<td>' . $link->getLink("javascript: reserve('v','','','" . $rs['resid']. "');", CmnFns::formatDate($rs['date']), '', '', translate('View this reservation')) . '</td>'
		. '<td style="text-align:left;">' . $rs['firstName'] . ' ' . $rs['lastName'] . '</td>'
		. '<td style="text-align:left;">' . $rs['fromLocationName'] . '</td>'
		. '<td style="text-align:left;">' . $rs['toLocationName'] . '</td>'
		. '<td>' . CmnFns::formatTime($rs['pickupTime']) . '</td>'
        	. '<td>$' .$rs['total_fare']. '</td>'
		. "<td>$receipt</td>"
		. "<td>$feedback</td>"
		. "</tr>\n";
 */
	}
	unset($res);
?>
      </table>
<?
//showPage($page);
}

/**
* Print table with all user training information
* @param mixed $per permissions array
* @param string $err last database error
*/
function showTrainingTable($per, $err, $scheduleid) {
	global $link;

//echo '<pre>';
//      print_r($per);
//echo '</pre>';
?>

<script>
 function updateTable(data2)
 {
   var t = $("#locsTable");
   var edit = '<a href="/pop/add_location.php?machid='+data2.machid+'" class="popover-edit" title="Edit Location">Edit</a>'+
              '|'+
	      '<a href="/pop/delete_location.php?machid='+data2.machid+'" class="popover-delete parentTr" title="Delete Location?">Delete</a>';

   t.prepend("<tr><td>"+data2.name+"</td><td>"+data2.address1+"</td>"+
		  "<td>"+data2.city+"</td><td>"+data2.state+"</td>"+
		  "<td>"+data2.zip+"</td><td>"+edit+"</td></tr>");
 }
</script>

  <h1 id="hdr_my_locations"><span class="imagetext">My Locations</span></h1>

  <form name="aptSelect" action="<?=$_SERVER['PHP_SELF']?>" method="GET" style="margin:0;">
    <?php echo getApts() ?>
  </form>
  <p class="align_right"><a href="/pop/add_location.php" class="popover-add" title="Add New Location">+ Add Location</a></p>

  <table id="locsTable" cellpadding="0" cellspacing="0" class='forceWordWrap' width='580px'>
    <thead>
      <tr>
        <th width='150px'><div>Nickname</div></th>
        <th width='135px'><div>Address</div></th>
        <th width='55px'><div>City</div></th>
        <th width='20px'><div>State</div></th>
        <th width='30px'><div>Zip</div></th>
        <th width='60px'><div>Options</div></th>
      </tr>
    </thead>
    <tbody>
    <?php
        // If they have no training, inform them
	// if(!$per) echo '<tr><td colspan="10" class="cellColor" align="center">'.$err.'</td></tr>';

      // Cycle through and print out machines
      for($i = 0; is_array($per) && $i < count($per); $i++):
        $rs = $per[$i];
        $machid = $rs['machid'];
        $class = ' class="alt"'.($i % 2);
        ?>
        <tr>
          <td width='150px'><div><?php echo $rs['name'] ?></div></td>
          <td width='135px'><div><?php echo $rs['address1'].' '.$rs['address2'] ?></div></td>
          <td width='55px'><div><?php echo $rs['city'] ?></div></td>
          <td width='20px'><div><?php echo $rs['state'] ?></div></td>
          <td width='30px'><div><?php echo $rs['zip'] ?></div></td>
          <td width='60px'><div>
            <?php if(!empty($rs['scheduleid'])): ?>
              <a href="/pop/add_location.php?machid=<?php echo $machid ?>" class="popover-edit" title="Edit Location">Edit</a>
              |
            <?php endif; ?>
	    <a href="/pop/delete_location.php?machid=<?php echo $machid ?>" class="popover-delete parentTr" title="Delete Location?">Delete</a>
          </div></td>
        </tr>
      <?php endfor; ?>
    </tbody>
  </table>
<?php /*
<form name="aptSelect" action="<?=$_SERVER['PHP_SELF']?>" method="GET" style="margin:0;">
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td class="tableTitle" colspan="1">
		    &#8250; <?=translate('My Permissions') . ' ' . stripslashes($_SESSION['currentName'])?>
		  </td>
		  <td align="right" valign="middle" class="tableTitle" width="30%">
		  <?
		echo getApts();
		 ?></td><td align="right" class="tableTitle" width="10%"><?
		echo "<input type=\"button\" value=\"Create New Location\" onmouseup=\"locate('c','$id','$ts','','$scheduleid');\" onmouseover=\"window.status='Create New Location'; return true;\" onmouseout=\"window.status=''; return true;\">"; ?>
		</td>
        </tr>
      </table>
      <div id="permissions" style="display: <?= getShowHide('permissions') ?>;">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr class="rowHeaders">
          <td width="10%"><?=translate('Location')?></td>
          <td width="10%"><?=translate('Address1')?></td>
          <td width="10%"><?=translate('Address2')?></td>
          <td width="10%"><?=translate('City')?></td>
		  <td width="5%"><?=translate('State')?></td>
		  <td width="5%"><?=translate('Zip')?></td>
		  <td width="10%"><?=translate('Phone')?></td>
		  <td width="26%"><?=translate('Notes')?></td>
		  <td width="7%"><?=translate('Modify')?></td>
		  <td width="7%"><?=translate('Delete')?></td>
        </tr>
        <?
	// If they have no training, inform them
	if (!$per)
		echo '<tr><td colspan="10" class="cellColor" align="center">' . $err . '</td></tr>';

	// Cycle through and print out machines
    for ($i = 0; is_array($per) && $i < count($per); $i++) {
		$rs = $per[$i];
		$machid = $rs['machid'];
		$class = 'cellColor' . ($i%2);
		echo "<tr class=\"$class\">\n"
            . '<td>' . $rs['name'] . '</td>'
			. '<td>' . $rs['address1'] . '</td>'
			. '<td>' . $rs['address2'] . '</td>'
            . '<td>' . $rs['city'] . '</td>'
			. '<td>' . $rs['state'] . '</td>'
			. '<td>' . $rs['zip'] . '</td>'
			. '<td>' . $rs['rphone'] . '</td>'
			. '<td>' . $rs['notes'] . '</td>';
		if(!$rs['autoAssign'] && $rs['scheduleid'] == $scheduleid) {
		    echo "<td align=\"center\"><a href = \"javascript: locate('m','$machid','','', '$scheduleid');\"/a>" . translate('Modify') . "</td>"
			. "<td align=\"center\"><a href = \"javascript: locate('d','$machid','','', '$scheduleid');\"/a>" . translate('Delete') . "</td>"
			. '</tr>';
		} else {
			echo "<td align=\"center\">" . translate('Modify') . "</td>" . "<td align=\"center\">" . translate('Delete') . "</td>";
		}
	}
	unset($per);
    ?>
      </table>
	  </div>
	  </td>
  </tr>
</table>
</form>

<?
//*/
}

/**
* Get list of airports for dropdown
*/
function getApts() {
	$query = "SELECT machid, name FROM resources
		WHERE machid like 'airport%'";
	$qresult = mysql_query($query);
	$output = '<select name="apts" style="margin:0;">
		    <option value="">Add an Airport</option>';
	while ($row = mysql_fetch_assoc($qresult)) {
		$output .= "<option value=\"{$row['machid']}\">{$row['name']}</option>";
	}
	  $output .= '</select><input type="hidden" name="active" value="locs"><input type="submit" value="Add" name="add" style="margin:0;">';
	$output = chop($output);
	return $output;
}
/**
* Print out a table of links for user or administrator
* This function prints out a table of links to
* other parts of the system.  If the user is an admin,
* it will print out links to administrative pages, also
* @param none
*/
function showQuickLinks() {
	global $conf;
	global $link;
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td class="tableTitle">
		    &#8250; <?=translate('My Quick Links')?>
		  </td>
         <!-- <td class="tableTitle"><div align="right">
              <? $link->doLink("javascript: help('quick_links');", '?', '', 'color: #FFFFFF', translate('Help') . ' - ' . translate('My Quick Links')) ?>
            </div>
          </td>-->
        </tr>
      </table>
      <div id="quicklinks" style="display: <?= getShowHide('quicklinks') ?>;">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr style="padding: 5px" class="cellColor">
          <td colspan="2">
            <p><b>&raquo;</b>
              <a href="http://www.planettran.com">PlanetTran Home</a>
            </p>
		<p><b>&raquo;</b>
               <a href="http://www.planettran.com/service.php">Details about Our Service</a>
             </p>
            <!--<p><b>&raquo;</b>
              <? $link->doLink('schedule.php', translate('Go to the Online Scheduler')) ?>
            </p>-->
            <p><b>&raquo;</b>
              <? $link->doLink('register.php?edit=true', translate('Change My Profile Information/Password')) ?>
            </p>
            <p><b>&raquo;</b>
              <? $link->doLink('my_email.php', translate('Manage My Email Preferences')) ?>
            </p>
            <p><b>&raquo;</b>
             <a href="javascript: window.open('feedback.php', 'feedbackForm', 'width=700, height=425'); void(0);">Send Feedback</a>
            </p>
            <p><b>&raquo;</b>
             <a href="javascript: window.open('qq.php', 'qq', 'width=650, height=400, scrollbars=1'); void(0);">Get a Custom Quote w/ your organization's discount.</a>
            </p>
            <?
		if (Auth::isAdmin() || ($_SESSION['role'] == 'a' && $_SESSION['curGroup'])) {
		$rlink = $conf['app']['weburi'] . '/reports.php';
		?>
            	<p><b>&raquo;</b>
             	<a href="<?=$rlink?>" target="_blank">Monthly Activity Report</a>
            </p>

		<?
		}

		// If it's the admin, print out admin links
		if (Auth::isAdmin()) {
            ?><p><b>&raquo;</b>
             <a href="javascript: window.open('turnaway.php', 'turnaway', 'width=450, height=250, scrollbars=1'); void(0);">Report Turnaway</a>
	    <?
			echo
				  '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=schedules', translate('Manage Schedules')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=resources', translate('Manage Resources')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=users', translate('Manage Users')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=reservations', translate('Manage Reservations')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('blackouts.php', translate('Manage Blackout Times')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=email', translate('Mass Email Users')) . "</p>\n"
                . '<p><b>&raquo;</b> ' .  $link->getLink('usage.php', translate('Search Scheduled Resource Usage')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=export', translate('Export Database Content')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('stats.php', translate('View System Stats')) . "</p>\n";
		}
		?>
            <p><b>&raquo;</b>
              <? $link->doLink('mailto:' . $conf['app']['adminEmail'].'?cc=' . $conf['app']['ccEmail'], 'Send Email Regarding Reservations', '', '', 'Send a non-technical email to the administrator') ?>
            </p>
            <p><b>&raquo;</b>
              <? $link->doLink('index.php?logout=true', translate('Log Out')) ?>
            </p>
          </td>
        </tr>
      </table>
	  </div>
    </td>
  </tr>
</table>
<?
}

/**
* Print out break to be used between tables
* @param none
*/
function printCpanelBr() {
	echo '<p>&nbsp;</p>';
}

/**
* Returns the proper expansion type for this table
*  based on cookie settings
* @param string table name of table to check
* @return either 'block' or 'none'
*/
function getShowHide($table) {
	if (isset($_COOKIE[$table]) && $_COOKIE[$table] == 'hide') {
		return 'none';
	}
	else
		return 'block';
}
?>
