<?php 
/** 
* This file provides output functions for reserve.php
* No data manipulation is done in this file
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 06-05-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

/**
* Print out information about this resource
* This function prints out a table containing
*  all information about a given resource
* @param array $rs array of resource information
*/
function print_res_header($type, $resid = null) {
	$resid = !empty($resid) ? " #".strtoupper(substr($resid, -6)) : '';
	$header = "View Reservation$resid";
	if($type == 'r') {
		$header = 'New Reservation';
	} else if ($type == 'd') {
		$header = "Delete Reservation$resid";
	} else if ($type == 'm') {
		$header = "Modify Reservation$resid";
}
?>
<tr><td colspan="1">
<h3 align="center">
<?=$header?>
</h3>
		<script type="text/javascript">
		window.onload = function() {
			this.focus();
		}
		</script>
</td></tr>
<?
}

function print_location_lists($loclist, $to_selected, $from_selected, &$res, $toLocs) {
	
	global $conf;
	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
	$t = new Tools();
	$stoplist = array(''=>'No stops');
	$stopDisplay = $res->stopLoc ? 'inline' : 'none';
?>

<tr><td>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr class="tableBorder">
    <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
	  <td colspan="3" class="cellColor">
         <h5 align='center'>
		Please select the Locations for this Reservation.
        </h5></tr>
	 <tr>
        <td class="formNames">From:</td>
        <td class="cellColor"><select name="fromLoc" onChange="suvCheck('from')">
        	<option value="">Please select a location</option>
            <? for ($i = 0; $i < count($loclist); $i++) {
		$stoplist[$loclist[$i]['machid']] = $loclist[$i]['name'];
               echo '<option value="' . $loclist[$i]['machid'] . '"';

              // If this is a modification, select correct loc
                if ( ($from_selected == $loclist[$i]['machid']) )
                    echo ' selected="selected" ';
              echo '>' . $loclist[$i]['name'] . '</option>';
            } ?>
            </select></td>
		</tr>
        <tr>
          <td class="formNames">Intermediate stop:</td>
          <td class="cellColor">
		<input name="stop" type="checkbox" onClick="multStopCheck()" <?=($res->stopLoc ? 'checked' : '')?>> 
		<div id="stopDiv" style="display: <?=$stopDisplay?>;">
            <? 

		$t->print_dropdown($stoplist, $res->stopLoc, 'stopLoc');

		echo '</div>';
          
            ?>
            </td>
        </tr>
        <tr>
          <td class="formNames">To:</td>
          <td class="cellColor"><select name="toLoc" onChange="suvCheck('to')">
          <option value="">Please select a location</option>
            <? for ($i = 0; $i < count($toLocs); $i++) {
               echo '<option value="' . $toLocs[$i]['machid'] . '"';
                // If this is a modification, select correct loc
                if ( ($to_selected == $toLocs[$i]['machid']) )
                    echo ' selected="selected" ';
              echo '>' . $toLocs[$i]['name'] . '</option>';
            } ?>
            </select></td>
        </tr>
	<tr>
	  <td class="formNames">Add Location:</td>
	  <td class="cellColor">
		<? echo "<input type=\"button\" value=\"Create New Location\" onmouseup=\"locate('c','$id','$ts','','".$res->sched['scheduleid']."');\" onmouseover=\"window.status='Create New Location'; return true;\" onmouseout=\"window.status=''; return true;\">"; ?> or
		<br><?=get_apts_rsr()?>
	  </td>
	</tr>
	<tr><td colspan="2" class="default">
	<?
	//if (Auth::isSuperAdmin()) {
	if (1) {
	?>
	<script language="Javascript">
	function getEstimate() {
		
		document.reserve.submit(); 
		
		var fromID = document.reserve.fromLoc.value;
		var toID = document.reserve.toLoc.value;
		//var groupid = "<?=$_SESSION['curGroup']?>";
		var groupid = document.reserve.userGroup.value;
		var response;
		var req = null;

		if(window.XMLHttpRequest) 
			req = new XMLHttpRequest();
		else if (window.ActiveXObject)
			req = new ActiveXObject("Microsoft.XMLHTTP");

		req.open('GET', 'ajaxquote.php?from='+fromID+'&to='+toID+'&groupid='+groupid, false);
		//req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		//req.send('from='+fromID+'&to='+toID);
		req.send(null);
		alert(req.responseText);
	
	}
	</script>
	
<script type='text/javascript'>
$(function() {
  $('#confirm-actuator').click(function() {
    
	var backgroundDiv = "<div id='loading_div' style='background: #FFFFFF;position: absolute; top: 0; left: 0; z-index: 1; width: 100%; vertical-align:middle;'><img style='position:absolute;left:50px; top:100px;' src='loading.gif'></img></div>";
//	$('body').append(backgroundDiv);
//	$('#loading_div').css('height',$(document).height());
	
	var fromID = document.reserve.fromLoc.value;
	var fromLabel = document.reserve.fromLoc[document.reserve.fromLoc.selectedIndex].innerHTML;
	var toID = document.reserve.toLoc.value;
	var toLabel = document.reserve.toLoc[document.reserve.toLoc.selectedIndex].innerHTML;
	var stopChecked = document.reserve.stop.checked;
	var stopID = document.reserve.stopLoc.value;
	var groupid = document.reserve.userGroup.value;
    var hrly=  document.reserve.wait.checked;
    var convertible_seats = document.reserve.convertible_seats.value;
    var booster_seats = document.reserve.booster_seats.value;
    var meet_greet = document.reserve.greet.checked;

    var coupon = document.reserve.coupon.value;
    var vehicle_type='P';

    if(document.reserve.carTypeSelect.value != ""){
        vehicle_type = document.reserve.carTypeSelect.value;
    }
    var trip_type='P';

    if(stopID !=""){
        trip_type = 'I';
    }else if(hrly){
        trip_type = 'H';
    }

	if(document.reserve.stop.checked == false)
	{
			stopID = "";
	}	
   
    if(fromID == '' || toID == '')
    {
    	alert("Please select both a pickup and a dropoff location to get a quote.");
    	return;
    }
    
    $('body').append(backgroundDiv);
	$('#loading_div').css('height',$(document).height());
    
    
    
    //$('#reserve').submit();
    
    sfp = "fromID=" + fromID +
        "&toID=" + toID +
        "&stopID=" + stopID +
        "&groupid=" + groupid +
        "&trip_type=" + trip_type +
        "&vehicle_type=" + vehicle_type +
        "&convertible_seats=" + convertible_seats +
        "&booster_seats=" + booster_seats +
        "&meet_greet=" + meet_greet +
        "&coupon=" + coupon;

    
    $.ajax({
      url: "ajaxquote.php",
      cache: false,
      data: sfp,
      type: "GET",
      dataType: "text",
      error: function(XMLHttpRequest, textStatus, errorThrown){
            
            $('#loading_div').remove();
            alert(textStatus);
            return false;
            
        },

      success: function(data, textStatus, xhr ){
              var data_parts = data.split("|");
              
		    $('#loading_div').remove();
		    
		    if(data_parts[0] > 0){
		    	Boxy.confirm(" <div class='bodytext' align='center'><b style='font-size: large;'>Estimated flat-rate fare: $" + data_parts[0] + "</b>	</div>	<div class='bodytext' align='left'> <br> <b>Details:</b> <ul> <li>Pick-Up Location: " + fromLabel + ", " + data_parts[1] + "</li> <li>Drop-Off Location: " + toLabel + ", " + data_parts[2] + "</li> </ul> Additional Information: <ul> <li>The quote is based on distance and does not include applicable wait time.</li> <li>The quote does not include vehicle upgrade charges or charges for infant or booster seats. Fares include tolls. Airport fees are NOT included.</li> <li>PlanetTran does not charge fuel surcharges.</li> <li>Tips are neither expected nor included in our flat-rate pricing.</li>	</ul>", function() { }, {title: 'Message'});
    			return false; //$('#reserve').submit();
		    }
		    else
		    {
		    	Boxy.confirm("<div class='bodytext' align='center'>We were unable to automatically generate a quote for your locations. Email us at <a href='mailto:customerservice@planettran.com'>customerservice@planettran.com</a>, or call 888-756-8876 (press option 2).  Thanks for your patience and cooperation. </div> ", function() { }, {title: 'Message'});
    			return false; //$('#reserve').submit();		    	
		    }
            
         }
    });
    
   
  });
});

</script>
	
	
	
	<a id='confirm-actuator'  href="#" >Get a fare estimate for your selected locations.</a>
	<?
	}
	?>
	<div style="font-size: 9px;">
		* Please note that our minimum fare is $29 as of March 15th, 2011.
	</div>
	</td></tr>
       </table>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<?
}

/**
* Get list of airports for dropdown
*/
function get_apts_rsr() {
	$query = "SELECT machid, name FROM resources
		WHERE machid like 'airport%'";
	$qresult = mysql_query($query);
	$output = '';	
	$output .= '<select name="apts" id="apts">
		    <option value="">Add an Airport</option>';
	while ($row = mysql_fetch_assoc($qresult)) {
		$output .= "<option value=\"{$row['machid']}\">{$row['name']}</option>";
	}
	$output .= '</select>	
		    <input type="button" value="Add" name="add" onmouseup="addApt();">';

	return $output;
}

function print_resource_data($fromLocation, $toLocation) {
?>

<tr><td>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr class="tableBorder">
    <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
	  <tr>
	  <td colspan="3" class="cellColor">
         <h5 align='center'>
		Reservation Locations
        </h5></tr>
        <tr>
          <td width="100" class="formNames">From:</td>
          <td class="cellColor"><?=$fromLocation?>
          </td>
        </tr>
        <tr>
          <td width="100" class="formNames">To:</td>
          <td class="cellColor"><?=$toLocation?>
          </td>
        </tr>
       </table>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<?
	unset($rs);
}

/**
* Print out available times or current reservation's time
* This function will print out all available times to make
*  a reservation or will print out the selected reservation's time
*  (if this is a view).
* @param object $rs reservation object
* @param array $res resource data array
* @param string $classType reservation or blackout
* @global $conf
*/
function print_time_info(&$rs, &$res, $print_min_max = true, $special = '') {
	global $conf;
	$grt = strrchr($special, 'G');
	$test = Auth::isSuperAdmin() ? 'none' : 'none';

	$type = $rs->get_type();
	$interval = 15;//$rs->sched['timeSpan'];
	$startDay = 0;//$rs->sched['dayStart'];
	$endDay	  = 720;//$rs->sched['dayEnd'];
	list($acode, $fnum, $fdets) = explode("{`}", $rs->get_flightDets());
	$flightDets = $acode . $fnum . " " . $fdets;
?>

	<table width="100%" border="0" cellspacing="0" cellpadding="1">
     <tr class="tableBorder">
      <td>
       <table width="100%" border="0" cellspacing="1" cellpadding="2">
        <tr>
         <td colspan="3" class="cellColor">
         <h5 align='center'>
<?
         $disabled = "";
		 // Print message depending on viewing type
         switch($type) {
            case 'r' : $msg = "Please select the date, pickup time, and flight info";
                break;
            case 'm' : $msg = "Please change the date, pickup time, and flight info";
                break;
            default : $msg = "Reserved date, pickup time, and flight info";$disabled = "disabled=\"true\"";
                break;
        }
        echo $msg;
        // Blank out time field if creating reservation
        if ($type == 'r')
        	$showtime = '';
        else
        	$showtime = strftime('%m/%d/%Y', $rs->date+23);
        	//$showtime = strftime('%m/%d/%Y', $rs->date);
?>
        </h5>
        </td>
       </tr>
      <tr>
       <td width="32%" class="formNames">Date
	   <a href="javascript:cal5.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick the Date"></a>
	   <input <?=$disabled?> type="text" name="date" maxlength="10" size="10" value="<?=$showtime?>"></td>
<?
        // Show reserved time or select boxes depending on type
        if ( ($type == 'r') || ($type == 'm') ) {
        	echo '<td rowspan=2 class="formNames">';
        	echo '<div style="text-align:right;float:right;clear:both;">';
        	acode_dropdown();
        	// replace blank values with DB values
        	?><input name="acode" id="airline-fill" type="text" value="<?=$acode?>" size=2 maxlength=2></div>
		<div><div style="text-align:left;float:left;">&nbsp;&nbsp;Checked bags? <input name="checkBags" type="checkbox" <?=($rs->get_checkBags() ? 'checked' : '')?>></div>
		<div style="text-align:right;float:right;">Flight #: <input name="fnum" style="margin-top: 2px;" type="text" value ="<?=$fnum?>" size=2 maxlength=4></div></div>
		<div style="text-align:right;float:right;clear:both;">Time/Other details:&nbsp;<input name="fdets" style="margin-top: 2px;" type="text" value="<?=$fdets?>" size=16 maxlength=100></div>
		<div style="text-align: right; clear: both;" id="greetDiv">
		Meet and Greet ($30; Logan only)<input name="greet" type="checkbox" <?=($grt ? 'checked' : '')?>>
		<br>
		<span id="greetMsg"></span>
		</div>
		<!-- <script type="text/javascript">
		var a = document.getElementById('greetDiv');
		a.style.display= '<?=$test?>';
		meetGreetCheck('from');
		</script> -->
		</td></tr><tr>
		<?

            // Start time select box
            $start = $rs->get_start() >= 720 ? $rs->get_start() - 720 : $rs->get_start();

            echo '<td class="formNames">'. 'Pickup Time'
                   . "<br/><select name=\"startTime\" class=\"textbox\">\n";

            // If creating or modding, make the first option an empty string
			if ($type == 'r' || $type == 'm')
            	echo '<option value="" selected="selected"></option>';

            // Start at startDay time, end 30 min before endDay
            for ($i = $startDay; $i < $endDay; $i+=$interval) {
                echo '<option value="' . $i . '"';
                // If this is a modification, select correct time
                //if ( ($rs->get_start() == $i) || ($i == 720 && $rs->get_start() == ''))

                if (($start == $i) && ($start != ''))
                    echo ' selected="selected" ';
                echo '>' . CmnFns::formatTime($i, true) . '</option>';
            }
            echo "</select>\n";

            // Start AM/PM box
            //echo '<select name="ampm" class="textbox">';

            // If creating or modding, make the first option an empty string
	//		if ($type == 'r' || $type == 'm')
            //	echo '<option value="" selected="selected"></option>';

            echo '<br>AM<input type="radio" name="ampm" value="am" ';
            if (($rs->get_start() < 720) && ($rs->get_start() != ''))
                    echo 'checked';
	    echo '>';	

            echo '   PM<input type="radio" name="ampm" value="pm" ';
	        if (($rs->get_start() >= 720) && ($rs->get_start() != ''))
	               echo 'checked';
            echo '>';
            echo "</td>\n";

            // End time select box

        } else {
            echo '<td class="formNames">' . 'Pickup Time' . '<br />' . CmnFns::formatTime($rs->get_start()) . "</td>\n";
 			echo '<td class="formNames">Airline, #, Time - Checked Bags?<br><input ' . $disabled . ' name="flightDets" class="textbox" value="'. $flightDets . '" size=25/><input ' . $disabled . ' name="checkBags" type="checkbox" '.($rs->get_checkBags() ? 'checked' : ''). '>';
            echo "</td>\n";
        }
        // Close off table
        echo "</tr>\n</table>\n</td>\n</tr>\n</table>\n<p>&nbsp;</p>\n";
		unset($rs);
}

function print_time_info_read_only(&$rs, &$res, $print_min_max = true, $special = '') {
	global $conf;
	$grt = strrchr($special, 'G');
	$test = Auth::isSuperAdmin() ? 'none' : 'none';

	$type = $rs->get_type();
	$interval = 15;//$rs->sched['timeSpan'];
	$startDay = 0;//$rs->sched['dayStart'];
	$endDay	  = 720;//$rs->sched['dayEnd'];
	list($acode, $fnum, $fdets) = explode("{`}", $rs->get_flightDets());
	$flightDets = $acode . $fnum . " " . $fdets;
?>

	<table width="100%" border="0" cellspacing="0" cellpadding="1">
     <tr class="tableBorder">
      <td>
       <table width="100%" border="0" cellspacing="1" cellpadding="2">
        <tr>
         <td colspan="3" class="cellColor">
         <h5 align='center'>
<?
         $disabled = "";
		 // Print message depending on viewing type
         switch($type) {
            case 'r' : $msg = "Please select the date, pickup time, and flight info";
                break;
            case 'm' : $msg = "Please change the date, pickup time, and flight info";
                break;
            default : $msg = "Reserved date, pickup time, and flight info";$disabled = "disabled=\"true\"";
                break;
        }
        echo $msg;
        // Blank out time field if creating reservation
        if ($type == 'r')
        	$showtime = '';
        else
        	$showtime = strftime('%m/%d/%Y', $rs->date+23);
        	//$showtime = strftime('%m/%d/%Y', $rs->date);
?>
        </h5>
        </td>
       </tr>
      <tr>
       <td width="32%" class="formNames">Date
	   <input <?=$disabled?> type="text" name="date" maxlength="10" size="10" value="<?=$showtime?>"></td>
<?
        // Show reserved time or select boxes depending on type
        if ( ($type == 'r') || ($type == 'm') ) {
        	echo '<td rowspan=2 class="formNames">';
        	echo '<div style="text-align:right;float:right;clear:both;">';
        	acode_dropdown();
        	// replace blank values with DB values
        	?><input name="acode" id="airline-fill" type="text" value="<?=$acode?>" size=2 maxlength=2></div>
		<div><div style="text-align:left;float:left;">&nbsp;&nbsp;Checked bags? <input name="checkBags" type="checkbox" <?=($rs->get_checkBags() ? 'checked' : '')?>></div>
		<div style="text-align:right;float:right;">Flight #: <input name="fnum" style="margin-top: 2px;" type="text" value ="<?=$fnum?>" size=2 maxlength=4></div></div>
		<div style="text-align:right;float:right;clear:both;">Time/Other details:&nbsp;<input name="fdets" style="margin-top: 2px;" type="text" value="<?=$fdets?>" size=16 maxlength=100></div>
		<div style="text-align: right; clear: both;" id="greetDiv">
		Meet and Greet ($30; Logan only)<input name="greet" type="checkbox" <?=($grt ? 'checked' : '')?>>
		<br>
		<span id="greetMsg"></span>
		</div>
		<!-- <script type="text/javascript">
		var a = document.getElementById('greetDiv');
		a.style.display= '<?=$test?>';
		meetGreetCheck('from');
		</script> -->
		</td></tr><tr>
		<?

            // Start time select box
            $start = $rs->get_start() >= 720 ? $rs->get_start() - 720 : $rs->get_start();

            echo '<td class="formNames">'. 'Pickup Time'
                   . "<br/><select name=\"startTime\" class=\"textbox\">\n";

            // If creating or modding, make the first option an empty string
			if ($type == 'r' || $type == 'm')
            	echo '<option value="" selected="selected"></option>';

            // Start at startDay time, end 30 min before endDay
            for ($i = $startDay; $i < $endDay; $i+=$interval) {
                echo '<option value="' . $i . '"';
                // If this is a modification, select correct time
                //if ( ($rs->get_start() == $i) || ($i == 720 && $rs->get_start() == ''))

                if (($start == $i) && ($start != ''))
                    echo ' selected="selected" ';
                echo '>' . CmnFns::formatTime($i, true) . '</option>';
            }
            echo "</select>\n";

            // Start AM/PM box
            //echo '<select name="ampm" class="textbox">';

            // If creating or modding, make the first option an empty string
	//		if ($type == 'r' || $type == 'm')
            //	echo '<option value="" selected="selected"></option>';

            echo '<br>AM<input type="radio" name="ampm" value="am" ';
            if (($rs->get_start() < 720) && ($rs->get_start() != ''))
                    echo 'checked';
	    echo '>';	

            echo '   PM<input type="radio" name="ampm" value="pm" ';
	        if (($rs->get_start() >= 720) && ($rs->get_start() != ''))
	               echo 'checked';
            echo '>';
            echo "</td>\n";

            // End time select box

        } else {
            echo '<td class="formNames">' . 'Pickup Time' . '<br />' . CmnFns::formatTime($rs->get_start()) . "</td>\n";
 			echo '<td class="formNames">Airline, #, Time - Checked Bags?<br><input ' . $disabled . ' name="flightDets" class="textbox" value="'. $flightDets . '" size=25/><input ' . $disabled . ' name="checkBags" type="checkbox" '.($rs->get_checkBags() ? 'checked' : ''). '>';
            echo "</td>\n";
        }
        // Close off table
        echo "</tr>\n</table>\n</td>\n</tr>\n</table>\n<p>&nbsp;</p>\n";
		unset($rs);
}



/**
* Print out information about reservation's owner
* This function will print out information about
*  the selected reservation's user.
* @param string $type viewing type
* @param Object $user User object of this user
*/
function print_user_info($type, &$user) {
	if (!$user->is_valid()) {
		$user->get_error();
	}
	$user = $user->get_user_data();
?>
	<input type="hidden" name="userGroup" value="<?=$user['groupid']?>">
	<input type="hidden" name="first_res" value="<?=$user['first_res']?>">
   <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
        <td colspan="2" class="cellColor"><h5 align="center"><?=($type=='v' || $type=='d') ? translate('Reserved for') : translate('Will be reserved for')?></h5></td></tr>
       <tr>
        <td width="100" class="formNames"><?=translate('Name')?></td>
         <td class="cellColor"><?= $user['fname'] . ' ' . $user['lname']?></td>
          </tr>
          <tr>
           <td width="100" class="formNames"><?=translate('Phone')?></td>
           <td class="cellColor"><?= $user['phone']?></td>
          </tr>
          <tr>
           <td width="100" class="formNames"><?=translate('Email')?></td>
           <td class="cellColor"><?= $user['email']?></td>
          </tr>
        </table>
      </td>
     </tr>
    </table>
    <p>&nbsp;</p>
    <?
	unset($user);
}


/**
* Print out created and modifed times in a table, if they exist
* @param int $c created timestamp
* @param int $m modified stimestamp
*/
function print_create_modify($c, $m) {
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
       <td class="formNames"><?=translate('Created')?></td>
       <td class="cellColor"><?= CmnFns::formatDateTime($c)?></td>
	   </tr>
       <tr>
       <td class="formNames"><?=translate('Last Modified')?></td>
       <td class="cellColor"><?= !empty($m) ? CmnFns::formatDateTime($m) : translate('N/A') ?></td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   <p>&nbsp;</p>
<?
}

/**
* Prints out a checkbox to modify all recurring reservations associated with this one
* @param string $parentid id of parent reservation
*/
function print_recur_checkbox($parentid) {
	?>
	<p align="left"><input type="checkbox" name="mod_recur" value="<?=$parentid?>" /><?=translate('Update all recurring records in group')?></p>
	<?
}

function print_del_checkbox() {
?>
	<p align="left"><input type="checkbox" name="del" value="true" /><?=translate('Delete?')?></p>
<?
}

/**
* Print out form buttons
* This function will prints out form buttons
*  depending on what type needs to be printed out
* @param string $type reservation viewing type
*/
function print_buttons($type, $hack = array('email'=>''), $coupon = '', $groupid = 0, $email = '', $disabledFlag = false) {
	
	$disabled = ($type == 'd' || $disabledFlag) ? 'disabled' : '';
	if (!$email) $email = "None";

	// if it's a modification and already has a coupon, flag for that
	// to check for changing coupon and to know if it should be incremented
	$existing_coupon = (($type=='m'||$type=='d') && $coupon) ? $coupon : 0;
	
	?>
	<script language="Javascript">
	function getCoupon() {
		var coupon = document.reserve.coupon.value;
		//var groupid = document.reserve.userGroup.value;
		var date = document.reserve.date.value;
		var memberid = '<?=$_SESSION['currentID']?>';

		var response;
		var req = null;

		if(window.XMLHttpRequest) 
			req = new XMLHttpRequest();
		else if (window.ActiveXObject)
			req = new ActiveXObject("Microsoft.XMLHTTP");

		req.open('GET', 'couponcheck.php?coupon='+coupon+'&date='+date+'&memberid='+memberid, false);
		//req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		//req.send('from='+fromID+'&to='+toID);
		req.send(null);
		alert(req.responseText);
	
	}
	</script>
	<?

	if($_SESSION['currentID'] != 'glb46685213d6c7d') {
		?>Coupon code:&nbsp;&nbsp;<input type="text" name="coupon" style="margin-bottom: 5px;" size=20 value="<?=$coupon?>" <?=$disabled?>/>
	<?php if (!$disabledFlag) { ?>
	<a href="javascript: getCoupon()">Validate coupon</a>
		<?
	}
	}

	?>
<br />
	Confirmation email: <?=$email?><br>
	Additional email to:&nbsp;<input type="text" name="confirmEmail" size="20" value="<?=$hack['email']?>" <?=$disabled?>/> 

	<?

	if ($existing_coupon) 
		echo '<input type="hidden" name="existing_coupon" value="'.$existing_coupon.'">';



	if ($type=='m')
		echo '<input type="checkbox" name="emailModConfirm">Send email';

	// Print voucherid box for ABCTMA user
	//if($_SESSION['currentID'] == 'glb468468776d21d' && $type=='r') // whose memberid is this?
	if($_SESSION['currentID'] == 'glb46685213d6c7d' && $type=='r') 
		echo '<div style="margin-top: 5px;">Voucherid: <input type="text" name="voucherid" value=""/></div>';

	// Print buttons depending on type
    echo '<p>';
	switch($type) {
  	    case 'm' :
  	    	echo '<input id="real_submit" name ="submit" type="submit" value="' . 'SaveSubmit' . '" class="button" style="display:none;"/>';
            echo '<input type="button" name="test" onclick = "checkFields();" value="' . translate('Modify') . '" class="button" />'
				. '<input type="hidden" name="fn" value="modify" />';
	    break;
        case 'd' :
            echo '<input type="submit" name="submit" value="' . translate('Delete') . '" class="button" />'
					. '<input type="hidden" name="fn" value="delete" />';
	    break;
        case 'v' :
            echo '<input type="button" name="close" value="' . translate('Close Window') . '" class="button" onclick="window.close();" /></p>';
	    break;
        case 'r' :
//            echo '<input type="submit" name="submit" value="' . translate('Save') . '" class="button" />'
//					. '<input type="hidden" name="fn" value="create" />';
					
            echo '<input id="real_submit" name ="submit" type="submit" value="' . 'SaveSubmit' . '" class="button" style="display:none;"/>';
            echo '<input type="button" name="test" onclick = "checkFields();" value="' . translate('Save') . '" class="button" />'
					. '<input type="hidden" name="fn" value="create" />';
					
					
					
        break;
    }
    // Print cancel button as long as type is not "view"
	//if ($type != 'v')
	//	echo '&nbsp;&nbsp;&nbsp;<input type="button" name="close" value="' . translate('Cancel') . '" class="button" onclick="window.close();" /></p>';
}


/**
* Print out the special reservation items
* @param string $summary summary to edit
* @param string $type type of reservation
*/
function print_special($special, $type, $role, &$res) {
	global $conf;
	$suv = strrchr($special, 'S');
	$tod = strrchr($special, 'T');
	$inf = strrchr($special, 'I');
	$bst = strrchr($special, 'O');
	$mul = strrchr($special, 'M');
	$vip = strrchr($special, 'V');
	$psl = strrchr($special, 'P');
	$grt = strrchr($special, 'G');
	$wat = strrchr($special, 'A');
	$van = strrchr($special, 'N');
	$vp2 = strrchr($special, 'E');
	$crb = strrchr($special, 'C');
	$fst = strrchr($special, 'F');
	$lux = strrchr($special, 'L');
	// $pax should be last
	$pax = strpbrk($special, '123456789');
	$pax = $pax ? $pax : 1;

	$carSelected = $suv ? 'S' : ($van ? 'N' : ($lux ? 'L' : ''));
	$seatSelected = $inf ? 'I' : ($tod ? 'T' : ($bst ? 'O' : ''));

	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
	$t = new Tools();
	$carSelect = $t->car_select_array();
	$seatSelect = $t->seat_select_array();
	
	if ($role == 'v')
		$vip = true;
	else if ($role == 'e')
		$vip2 = $vip = true;
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
	    <td class="cellColor"><h5 align="center">Special Requests</h5></td>
		</tr>
		<tr>
		<td class="cellColor" style="text-align: left;">
		<?
		echo '<div align="center">';
		//echo 'SUV (4 pass)<input name="suv" type="checkbox" '.($suv ? 'checked' : ''). ' onChange="suvCheck(\'suv\')">&nbsp;&nbsp;';
		//echo 'Toddler Seat<input name="toddler" type="checkbox" '.($tod ? 'checked' : ''). '>&nbsp;&nbsp;';
		//echo 'Infant Seat<input name="infant" type="checkbox" '.($inf ? 'checked' : ''). '>&nbsp;&nbsp;';
		//echo 'Multiple Stops<input name="multiple" type="checkbox" '.($mul ? 'checked' : ''). '>';
		//echo 'Personal Trip<input name="personal" type="checkbox" '.($psl ? 'checked' : ''). '>&nbsp;&nbsp;';
		//echo 'Van (9 pass)<input name="van" type="checkbox" '.($van ? 'checked' : ''). ' onChange="suvCheck(\'van\')">';
		//if (Auth::isAdmin()) {
		//	echo '&nbsp;&nbsp;VIP<input name="vip" type="checkbox" '.($vip ? 'checked' : ''). '>';
			//echo '&nbsp;&nbsp;Curbside<input name="curbside" type="checkbox" '.($crb ? 'checked' : ''). '>';
		//} else if ($vip) {
		if ($vip)
			echo '<input type="hidden" name="vip" value="true">';
		//}
		echo '<input type="hidden" name="estimate" id="estimate_value">';
		echo ' Reserve by the Hour <input name="wait" type="checkbox" onClick="authWaitCheck()" '.($wat ? 'checked' : ''). '>&nbsp;&nbsp;';

		// Authorized wait select
		$waitvals = $t->get_authWaitTimes();
		$waitDisplay = $wat ? 'inline' : 'none';
		
		echo '<div id="waitDiv" style="display: '.$waitDisplay.';">';
		$t->print_dropdown($waitvals, $res->authWait, 'authWait', null, null, 'authWait');
		echo '</div>';

		// end centered div
		echo '</div>';


		if ($vip2)
			echo '<input type="hidden" name="evip" value="on">';
		if ($vip)
			echo '<input type="hidden" name="vip" value="on">';
		if ($fst)
			echo '<input type="hidden" name="first_mod" value="1">';
		
	?>
	<table width="100%" cellspacing=0 cellpadding=2>
	<tr>
		<td width="30%">
		Number of passengers:
		</td>
		<td width="70%">
		<select name="pax">
		<?
	
		for ($i = 1; $i <= 9; $i++) {
			$selected = $i == $pax ? ' selected' : '';
			echo "<option value=\"$i\"$selected>$i</option>";
		}	

		?>
		</select></td></tr>
		<tr>
		<td>Vehicle type: </td>
		<td>
		<?

		$t->print_dropdown($carSelect, $carSelected, 'carTypeSelect', null, 'onChange="vanCheck()"', 'carTypeSelect');

		?>
		</td></tr>
		<tr>
		<td style="vertical-align: top;">Child seats: </td>
		<td>
		<?

		//$t->print_dropdown($seatSelect, $seatSelected,'seatTypeSelect');
		$seatSelect = array();
		foreach(range(0,3) as $v)
			$seatSelect[$v] = $v;

		//CmnFns::diagnose($res);

		$t->print_dropdown($seatSelect, $res->convertible_seats, 'convertible_seats');
		echo ' Convertible seats<br>';
		$t->print_dropdown($seatSelect, $res->booster_seats, 'booster_seats');
		echo ' Booster seats';
		

		?>
		</td></tr>

		</table>
		</td>
	   </tr>
      </table>
     </td>
    </tr>
   </table>
   <p>&nbsp;</p>
<?
}

function print_special_read_only($special, $type, $role, &$res) {
	global $conf;
	$suv = strrchr($special, 'S');
	$tod = strrchr($special, 'T');
	$inf = strrchr($special, 'I');
	$bst = strrchr($special, 'O');
	$mul = strrchr($special, 'M');
	$vip = strrchr($special, 'V');
	$psl = strrchr($special, 'P');
	$grt = strrchr($special, 'G');
	$wat = strrchr($special, 'A');
	$van = strrchr($special, 'N');
	$vp2 = strrchr($special, 'E');
	$crb = strrchr($special, 'C');
	$fst = strrchr($special, 'F');
	$lux = strrchr($special, 'L');
	// $pax should be last
	$pax = strpbrk($special, '123456789');
	$pax = $pax ? $pax : 1;

	$carSelected = $suv ? 'S' : ($van ? 'N' : ($lux ? 'L' : ''));
	$seatSelected = $inf ? 'I' : ($tod ? 'T' : ($bst ? 'O' : ''));

	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
	$t = new Tools();
	$carSelect = $t->car_select_array();
	$seatSelect = $t->seat_select_array();
	
	if ($role == 'v')
		$vip = true;
	else if ($role == 'e')
		$vip2 = $vip = true;
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
	    <td class="cellColor"><h5 align="center">Special Requests</h5></td>
		</tr>
		<tr>
		<td class="cellColor" style="text-align: left;">
		<?
		echo '<div align="center">';
		//echo 'SUV (4 pass)<input name="suv" type="checkbox" '.($suv ? 'checked' : ''). ' onChange="suvCheck(\'suv\')">&nbsp;&nbsp;';
		//echo 'Toddler Seat<input name="toddler" type="checkbox" '.($tod ? 'checked' : ''). '>&nbsp;&nbsp;';
		//echo 'Infant Seat<input name="infant" type="checkbox" '.($inf ? 'checked' : ''). '>&nbsp;&nbsp;';
		//echo 'Multiple Stops<input name="multiple" type="checkbox" '.($mul ? 'checked' : ''). '>';
		//echo 'Personal Trip<input name="personal" type="checkbox" '.($psl ? 'checked' : ''). '>&nbsp;&nbsp;';
		//echo 'Van (9 pass)<input name="van" type="checkbox" '.($van ? 'checked' : ''). ' onChange="suvCheck(\'van\')">';
		//if (Auth::isAdmin()) {
		//	echo '&nbsp;&nbsp;VIP<input name="vip" type="checkbox" '.($vip ? 'checked' : ''). '>';
			//echo '&nbsp;&nbsp;Curbside<input name="curbside" type="checkbox" '.($crb ? 'checked' : ''). '>';
		//} else if ($vip) {
		if ($vip)
			echo '<input type="hidden" name="vip" value="true">';
		//}
		echo '<input type="hidden" name="estimate" id="estimate_value">';
		echo ' Reserve by the Hour <input name="wait" type="checkbox" onClick="authWaitCheck()" '.($wat ? 'checked' : ''). ' disabled>&nbsp;&nbsp;';

		// Authorized wait select
		$waitvals = $t->get_authWaitTimes();
		$waitDisplay = $wat ? 'inline' : 'none';
		
		echo '<div id="waitDiv" style="display: '.$waitDisplay.';">';
		$t->print_dropdown($waitvals, $res->authWait, 'authWait', null, null, 'authWait');
		echo '</div>';

		// end centered div
		echo '</div>';


		if ($vip2)
			echo '<input type="hidden" name="evip" value="on">';
		if ($vip)
			echo '<input type="hidden" name="vip" value="on">';
		if ($fst)
			echo '<input type="hidden" name="first_mod" value="1">';
		
	?>
	<table width="100%" cellspacing=0 cellpadding=2>
	<tr>
		<td width="30%">
		Number of passengers:
		</td>
		<td width="70%">
		<select name="pax" disabled>
		<?
	
		for ($i = 1; $i <= 9; $i++) {
			$selected = $i == $pax ? ' selected' : '';
			echo "<option value=\"$i\"$selected>$i</option>";
		}	

		?>
		</select></td></tr>
		<tr>
		<td>Vehicle type: </td>
		<td>
		<?

		$t->print_dropdown($carSelect, $carSelected, 'carTypeSelect', null, 'onChange="vanCheck()"', 'carTypeSelect', true);

		?>
		</td></tr>
		<tr>
		<td style="vertical-align: top;">Child seats: </td>
		<td>
		<?

		//$t->print_dropdown($seatSelect, $seatSelected,'seatTypeSelect');
		$seatSelect = array();
		foreach(range(0,3) as $v)
			$seatSelect[$v] = $v;

		//CmnFns::diagnose($res);

		$t->print_dropdown($seatSelect, $res->convertible_seats, 'convertible_seats', null, '', null, true);
		echo ' Convertible seats<br>';
		$t->print_dropdown($seatSelect, $res->booster_seats, 'booster_seats', null, '', null, true);
		echo ' Booster seats';
		

		?>
		</td></tr>

		</table>
		</td>
	   </tr>
      </table>
     </td>
    </tr>
   </table>
   <p>&nbsp;</p>
<?
}

/**
* Print out the reservation summary or a box to add/edit one
* @param string $summary summary to edit
* @param string $type type of reservation
*/
function print_summary($summary, $type, $dispNotes = '', $autoBillOverride = 0, $disabled = false) {
	if ($disabled) {
		$disabledText = " disabled";
	} else {
		$disabledText = "";
	}
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
	    <td class="cellColor"><h5 align="center">Other Reservation Notes</h5></td>
		</tr>
		<tr>
		<td class="cellColor" align="center">
		<input type="hidden" name="billtype" value="none">
		<?
		if ($type == 'r' || $type == 'm') {
			echo '<textarea class="textbox" name="summary" rows="3" cols="60">' . $summary . '</textarea>';
		} else {
			echo (!empty($summary) ? $summary : translate('N/A'));
		}
		if ($_SESSION['role'] == 'm') {
			echo '</td></tr><tr><td class="cellColor">';
	    		echo '<h5 align="center">Customer Service Notes</h5></td></tr>';
			if ($dispNotes) {
				echo '<tr><td class="cellColor">';
				for ($i=0;$dispNotes[$i];$i++) {
					$cur = $dispNotes[$i];
					$time = date("n/j/y g:ia",$cur['time']);
					$name = $cur['name'];
					echo "<b style=\"color: #0000CC;\">$time $name:</b> ".$cur['notes'].($dispNotes[$i+1] ? "<br>" : '');
				}
			}
			echo '<tr><td class="cellColor" align="center">';
			if ($type=='r' || $type=='m') echo 'Add a note<br><textarea class="textbox" name="dispNotes" rows="2" cols="60"></textarea>';
			echo '</td></tr><tr><td><input type="checkbox" name="autoBillOverride"' . ($autoBillOverride ? 'checked' : '') . $disabledText . ' >Override automated billing? (put negotiated amount or other rule in Customer Service Notes)</input>';
			}
		?>
		</td>
	   </tr>
      </table>
     </td>
    </tr>
   </table>
   <p>&nbsp;</p>
<?
}

function print_group_hack_summary($summary,$type,$billtype=null,$dispNotes,$paymentArray, &$user, &$res, $disabled = false) {
        $dets = explode("GROUP_DEL", $summary);
        $pname = $dets[0];
        $cphone = $dets[1];
        $cccode = $dets[2];
        $address = $dets[3];
        $citystzip = $dets[4];
        $ccnum = $dets[5];
        $expdate = $dets[6];
        $notes = $dets[7];
	$cchide = '';
	$showCCfields = false;
	$memberid = $user->userid;
	$billcheck = $user->groupid ? '1' : '';
	global $conf;
	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
	$t = new Tools();

	/*
	if ($type == 'r') {
		$newPaymentArray = array(''=>'Please select a payment option');
		if (count($paymentArray))
			foreach ($paymentArray as $k=>$v) 
				$newPaymentArray[$k] = $v;

		$paymentArray = $newPaymentArray;
	}
	*/

	// pre-populate cost center field in new reservations
	if ($type == 'r') $cccode = $user->position;
	
	if ($address || $cityzip || $ccnum || $expdate) $showCCfields = true;

        if (!$billtype)
        	$billtype = "none";

	if ($ccnum && ($type == 'm' || $type == 'd')) {
		$cchide = "<br>Credit card: *".substr($ccnum, -4);
		$ccnum = '';
	}
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
            <td colspan=3 class="cellColor"><h5 align="center">Details Different from Profile</h5></td>
                </tr>
                <tr>
                <td width="45%" class="formNames" style="text-align: left;">
                <input type="hidden" name="group" value="true"/>
                <input type="hidden" name="billtype" value="<?=$billtype?>">
                <input type="hidden" name="billcheck" value="<?=$billcheck?>">
                <?
                if ($type == 'r' || $type == 'm') {
                        echo 'Passenger Name<br><input size=24 type="text" name="pname" value="' . $pname . '"/></td>';
                        echo '<td width="25%" class="formNames">Cell Phone<br><input size=10 type="text" name="cphone" value="' . $cphone . '"/></td>';
                        echo '<td width="30%" class="formNames">Cost or Project Code<br><input size=10 type="text" name="cccode" value="' . $cccode . '"/></td></tr>';

			if ($showCCfields) {
                       		echo '<tr><td width="70%" colspan=2 class="formNames" style="text-align: left;">';
                    		echo 'Address<br><input size=35 type="text" name="address" value="' . $address . '"/></td>';
                       		echo '<td width="30%" class="formNames">City, State, Zip<br><input size=17 type="text" name="citystzip" value="' . $citystzip . '"/></td></tr>';

                       		echo '<tr><td width="70%" colspan=2 class="formNames" style="text-align: left;">';
                       		echo 'Credit Card<br><input size=20 type="text" name="ccnum" value="' . $ccnum . '"/>';
				echo $cchide;
				if ($type == 'r') 
					echo '<br><input type="checkbox" name="ccToAcct"> Make this your default card?';

				echo '</td>';
                	      	  echo '<td width="30%" class="formNames">Exp. Date (MM/YYYY):<input type="text" size=7 name="expdate" value="' . $expdate . '"/></td></tr>';
			}

                } else {
                        echo 'Passenger Name: ' . $pname . '</td>';
                        echo '<td width="25%" class="formNames">Cell Phone: '. $cphone . '</td>';
                        echo '<td width="30%" class="formNames">Cost or Project Code: ' . $cccode . '</td></tr>';
                        //echo '<tr><td width="70%" colspan=2 class="formNames" style="text-align: left;">';
                        //echo 'Address: ' . $address . '</td>';
                        //echo '<td width="30%" class="formNames">City, State, Zip: ' . $citystzip . '</td></tr>';
                        //echo '<tr><td width="70%" colspan=2 class="formNames" style="text-align: left;">';
                        //echo 'Credit Card: ' . $ccnum . '</td>';
                        //echo '<td width="30%" class="formNames">Exp. Date (MM/YYYY): ' . $expdate . '</td></tr>';
                }
                ?>
                </td>
           </tr>
      </table>
     </td>
    </tr>
   </table>
	<p>&nbsp;</p>
	<?
  	 print_summary($notes, $type, $dispNotes, $res->get_autoBillOverride(), $disabled);


	// Payment area
	?>

   <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
	<td class="cellColor" align="center"><h5>Payment options</h5></td>
       </tr>
       <tr>
	<td class="cellColor"><?
	$t->print_dropdown($paymentArray, $res->paymentProfileId, 'paymentProfileId', null, '', 'paymentProfileId', $disabled);


	//CmnFns::diagnose($paymentArray);
	if (!isset($paymentArray['']) && count($paymentArray) > 0 && (!$disabled)) {
		?>
		<a href="javascript: paymentPopup('<?=$memberid?>', 'edit')">Edit Payment Info</a>|
		<a href="javascript: paymentPopup('<?=$memberid?>', 'delete')">Delete Payment Info</a><br>
		<?
	}
	if (!$disabled) {
	?>
	<a href="javascript: paymentPopup('<?=$memberid?>', 'add')">Add Payment Info</a><br>
	<?php 
	}
	?>
       </td></tr>
      </table>
     </td>
    </tr>
   </table>
	<br>&nbsp;<br>
	<?
}

function print_hack_summary($summary, $type) {
	$dets = preg_split("/DELIMITER/", $summary);
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr class="tableBorder">
     <td>
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
       <tr>
	    <td class="cellColor"><h5 align="center">Reservation Details</h5></td>
		</tr>
		<tr>
		<td class="cellColor" style="text-align: left;">
		<input type="hidden" name="hybridcar" value="true"/>
		<?
		if ($type == 'r' || $type == 'm') {
			echo 'Passenger Name:<input type="text" name="pname" value="' . $dets[0] . '"/><br>';
			echo 'Cell Phone #:<input type="text" name="cell" value="' . $dets[5] . '"/><br>';
			echo '<br>If <b>Other</b> Location:<br>Address:<input type="text" name="address" value="' . $dets[3] . '"/><br>';
			echo 'City:<input type="text" name="city" value="' . $dets[4] . '"/><br>';
			echo '<br>Cost Center or Credit Card #:<br><input type="text" name="ccnum" value="' . $dets[1] . '"/><br>';
			echo 'Exp. Date (MM/YY):<input type="text" name="expdate" value="' . $dets[2] . '"/><br>';

		} else {
			echo (!empty($summary) ? 'Passenger Name: ' . $dets[0] . '<br>Cell#: ' . $dets[5] . '<br>Credit Card #: ' . $dets[1] . '<br>Exp. Date: ' . $dets[2] . '<br>Address: ' . $dets[3] . '<br>City: ' . $dets[4] : translate('N/A'));
		}
		?>
		</td>
	   </tr>
      </table>
     </td>
    </tr>
   </table>
   <p>&nbsp;</p>
<?
}
/**
* Print hidden form fields
* This function will print hidden form fields
*  depending on viewing type
* @param Object $res this reservation
*/
function print_hidden_fields(&$res) {

    if ($res->get_type() == 'r') {
        echo '<input type="hidden" name="ts" value="' . $res->get_date() . '" />' . "\n"
              . '<input type="hidden" name="machid" value="' . $res->get_machid(). '" />' . "\n"
			  . '<input type="hidden" name="scheduleid" value="' . $res->sched['scheduleid'] . '" />' . "\n";
    }
    else {
        echo '<input type="hidden" name="resid" value="' . $res->get_id() . '" />' . "\n";
    }
	unset($res);
	echo '<input type="hidden" name="bypass" value="">';
}


/**
* Opens form for reserve
* @param bool $show_repeat whether to show the repeat box
* @param bool $is_blackout if this is a blackout
*/
function begin_reserve_form($type, $is_blackout = false) {
	echo '<form name="reserve" id="reserve" method="post" action="' . $_SERVER['PHP_SELF'] . '?is_blackout=' . intval($is_blackout) . '" style="margin: 0px;" >' . "\n";
}

/**
* Closes reserve form
* @param none
*/
function end_reserve_form($id = '', $type = 'r') {
	?>
	</form>
	<script language="JavaScript" src="calendar2.js"></script>
	<script language="JavaScript">
		<!-- // 
			var cal5 = new calendar2(document.forms['reserve'].elements['date']);
			cal5.year_scroll = false;
			cal5.time_comp = false;

			function checkDel() {
				return confirm("Are you sure you wish to cancel this reservation?");
			}

			//-->
		</script>

	<?

	if ($type == 'm') {
		?>
	<form name="delres" action="<?=$_SERVER['PHP_SELF']?>" method="post" onSubmit="return checkDel();">
	<input type="hidden" name="resid" value="<?=$id?>">
	<input type="hidden" name="fn" value="delete">
	<input type="hidden" name="type" value="<?=$type?>">
	<input type="hidden" name="submit" value="1">
	<div style="text-align: right;">
	Cancel this reservation <input type="submit" value="Cancel">
	</div>
	</form>
		<?
	}
}

function start_left_cell() {
?>
<table width="100%" cellspacing="0" border="0" cellpadding="0">
<?
}

function divide_table() {
?>
</td><td style="vertical-align: top; padding-left: 15px;">
<?
}

function end_right_cell() {
?>
</td></tr>
</table>
<?
}
function acode_dropdown() {
?>
      <select name="selAl" id="airline-pull" onChange="ftSynchronize(this);">
		<option>Select an airline</option>
<option value="TZ" >ATA Airlines - TZ</option>
<option value="EI" >Aer Lingus - EI</option>
<option value="AM" >Aeromexico - AM</option>
<option value="9A" >Air Atlantic - 9A</option>
<option value="AC" >Air Canada - AC</option>
<option value="CA" >Air China - CA</option>
<option value="AF" >Air France - AF</option>
<option value="IJ" >Air Liberte - IJ</option>
<option value="NZ" >Air New Zealand - NZ</option>
<option value="FL" >Air Tran - FL</option>
<option value="TS" >Air Transat (Canada) - TS</option>
<option value="GB" >Airborne Express - GB</option>
<option value="AS" >Alaska Airlines - AS</option>
<option value="AZ" >Alitalia - AZ</option>
<option value="NH" >All Nippon Airways - NH</option>
<option value="G4" >Allegiant Air - G4</option>
<option value="AQ" >Aloha Airlines - AQ</option>
<option value="HP" >America West Airlines - HP</option>
<option value="AA" >American Airlines - AA</option>
<option value="AN" >Ansett Australia - AN</option>
<option value="AV" >Avianca - AV</option>
<option value="UP" >Bahamasair - UP</option>
<option value="JV" >Bearskin Airlines - JV</option>
<option value="GQ" >Big Sky Airways - GQ</option>
<option value="BU" >Braathens - BU</option>
<option value="BA" >British Airways - BA</option>
<option value="BD" >British Midland - BD</option>
<option value="ED" >CCAir - ED</option>
<option value="C6" >CanJet - C6</option>
<option value="CX" >Cathay Pacific - CX</option>
<option value="MU" >China Eastern Airlines - MU</option>
<option value="CZ" >China Southern Airlines - CZ</option>
<option value="CO" >Continental Airlines - CO</option>
<option value="DL" >Delta Air Lines - DL</option>
<option value="BR" >EVA Airways - BR</option>
<option value="U2" >Easyjet - U2</option>
<option value="LY" >El Al Israel Airlines - LY</option>
<option value="AY" >Finnair - AY</option>
<option value="7F" >First Air - 7F</option>
<option value="RF" >Florida West Airlines - RF</option>
<option value="F9" >Frontier Airlines - F9</option>
<option value="GA" >Garuda - GA</option>
<option value="HQ" >Harmony Airways - HQ</option>
<option value="HA" >Hawaiian Airlines - HA</option>
<option value="IB" >Iberia - IB</option>
<option value="FI" >Icelandair - FI</option>
<option value="IC" >Indian Airlines - IC</option>
<option value="IR" >Iran Air - IR</option>
<option value="JD" >Japan Air System - JD</option>
<option value="JL" >Japan Airlines - JL</option>
<option value="QJ" >Jet Airways - QJ</option>
<option value="B6" >JetBlue Airways - B6</option>
<option value="KL" >KLM Royal Dutch Airlines - KL</option>
<option value="KE" >Korean Air Lines - KE</option>
<option value="WJ" >Labrador Airways LTD - WJ</option>
<option value="LH" >Lufthansa - LH</option>
<option value="MY" >MAXjet - MY</option>
<option value="MH" >Malaysian Airline - MH</option>
<option value="YV" >Mesa Airlines - YV</option>
<option value="MX" >Mexicana - MX</option>
<option value="GL" >Miami Air Intl. - GL</option>
<option value="YX" >Midwest Airlines - YX</option>
<option value="NW" >Northwest Airlines - NW</option>
<option value="OA" >Olympic Airways - OA</option>
<option value="PR" >Philippine Airlines - PR</option>
<option value="PO" >Polar Air - PO</option>
<option value="PD" >Porter Airlines - PD</option>
<option value="QF" >Qantas Airways - QF</option>
<option value="SN" >Sabena - SN</option>
<option value="S6" >Salmon Air - S6</option>
<option value="SV" >Saudi Arabian Airlines - SV</option>
<option value="SK" >Scandinavian Air (SAS) - SK</option>
<option value="YR" >Scenic Airlines - YR</option>
<option value="S5" >Shuttle America - S5</option>
<option value="SQ" >Singapore Airlines - SQ</option>
<option value="5G" >Skyservice - 5G</option>
<option value="SA" >South African Airways - SA</option>
<option value="WN" >Southwest Airlines - WN</option>
<option value="JK" >Spanair - JK</option>
<option value="NK" >Spirit Airlines - NK</option>
<option value="SY" >Sun Country Airlines - SY</option>
<option value="LX" >Swiss Int'l Airllines - LX</option>
<option value="TG" >Thai Airways - TG</option>
<option value="TK" >Turkish Airlines - TK</option>
<option value="US" >US Airways - US</option>
<option value="U5" >USA3000 - U5</option>
<option value="UA" >United Airlines - UA</option>
<option value="VP" >VASP - VP</option>
<option value="RG" >Varig - RG</option>
<option value="VX" >Virgin America - VX</option>
<option value="VS" >Virgin Atlantic - VS</option>
<option value="WS" >WestJet Airlines - WS</option>
<option value="MF" >Xiamen Airlines - MF</option>
<option value="Z4" >Zoom Airlines - Z4</option>

      </select>
<?
}

function print_viewonly_web($loclist, $toloc, $fromloc, &$res, $trip, $isActive = false) {
	$date = CmnFns::formatDate($res->date);
	$time = CmnFns::formatTime($res->start);
	$resid = CmnFns::showid($res->id);
	$driver = CmnFns::driver_shortname($trip['driver']);

	if ($trip['driver'] == 'NONE_ASSIGNED') $driver = 'Awaiting update';
	$car = $trip['carname'] == 'NONE_ASSIGNED' ? 'Awaiting update' : $trip['carname'];

	global $conf;
	$path = $conf['app']['domain'].'/reservations/ptRes2/m.dbwrapper.php';
	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
	$t = new Tools();
	$delayArray = $t->delay_array();
	
	
	//$h = file_get_contents($path);
	//if ($h === false) echo 'was false';
	$vcars = unserialize($h);
	$okstates = array(10, 11, 227, 355, 617, 643);
	$enrouteStates = array(11, 12, 544, 618, 619);
	$isactive = false;
	$enroute = false;

	foreach ($okstates as $v) {
		if ($trip['dispatch_status'] == $v) {
			$isactive = true;
			break;
		}
	}

	foreach ($enrouteStates as $v) {
		if ($trip['dispatch_status'] == $v) {
			$enroute = true;
			break;
		}
	}	

	$carphone = $vcars[$trip['vehicle']];
	$cardesc = $trip['color'].' '.$trip['make'].' '.$trip['model'];

	for ($i=0; $loclist[$i]; $i++) {
		$cur = $loclist[$i];
		if ($cur['machid'] == $fromloc)
			$from = $cur['name'];
		else if ($cur['machid'] == $toloc)
			$to = $cur['name'];
	}
	?>
	<div style="width: 80%; border: 1px solid #0A0; text-align: center; background-color: white; margin-left: auto; margin-right: auto; padding: 5px; margin-bottom: 20px;">
	This reservation has been queued by dispatch and can no longer be altered online. To make changes please call 888-PLN-TTRN (756-8876).
	</div>
	<style type="text/css">
		td.title { font-weight: bold; }
		td { font-size: 1.2em; }
	</style>
	<table width="100%" cellspacing=1 cellpadding=3 style="background-color: white; border: 1px solid #CCC;">
	<tr>
		<td class="title" width="40%">Conf. #</td>
		<td width="60%"><?=$resid?></td>
	</tr>
	<tr>
		<td class="title" width="20%">Date</td>
		<td><?=$date?></td>
	</tr>
	<tr>
		<td class="title" width="20%">Time</td>
		<td><?=$time?></td>
	</tr>
	<tr>
		<td class="title">From</td>
		<td><?=$from?></td>
	</tr>
	<tr>
		<td class="title">To</td>
		<td><?=$to?></td>
	</tr>
	<tr>
		<td class="title">Driver</td>
		<td><?=$driver?></td>
	</tr>
	<tr>
		<td class="title">Car</td>
		<td><?=$car?></td>
	</tr>
	<tr>
		<td class="title">Car Type</td>
		<td><?=$cardesc?></td>
	</tr>
	<?
	if ($isActive) {
		$delay = $trip['delay'] ? $delayArray[$trip['delay']] : 'On Time';
		?>
		<tr>
		<td class="title">Arrival Status</td>
		<td><?=$delay?></td>
		</tr>
		<?
	}

	?>
	<tr>
		<td class="title">Booked Through</td>
		<td><?=CmnFns::reservation_origin($res->origin)?></td>
	</tr>
	</table>
		<div>
	<?
	//CmnFns::diagnose($trip);	
	if ($carphone && $isactive) {
	
		?>
		<a href="tel:<?=$carphone?>">Call Driver</a>
		<?

	} 

	// if state is en route or higher
	// if no oti submitted
	// if feedback time (30 days) time not expired

	if ($enroute && !$trip['issueid'] && $res->date > time()-60*60*24*30){

		?>
		<a href="m.feedback.php?resid=<?=$res->id?>">Leave Feedback</a>
		<?
	}

	// if trip is not yet dispatched we can modify
	if ($trip['dispatch_status'] == 27) {
		?>
		<br>
		<a href="m.reserve.php?type=m&mtype=<?=$res->mtype?>&resid=<?=$res->id?>">Modify this reservation</a>
		<?
	}

	/*
	if (!$isActive) {
		?>
		<br>
		<a href="m.reserve.php?type=r&mtype=<?=$res->mtype?>&machid=<?=$res->machid?>&toLocSelect=<?=$res->toLocation?>">Create a new reservation from these locations</a>
		<?
	}
	*/

	echo '</div>';

}
?>
