<?php

/* Header and mobile-specific CSS */
function pdaheader($title = '', $js = '', $cache=true) {
$cachestr = $cache ? '' : '<META HTTP-EQUIV="Pragma" CONTENT="no-cache">';
	?>
<html>
<head>
<title><?=$title?></title>
<meta name="viewport" content="width=320" />
<?=$cachestr?>
<?=$js?>
<style type="text/css">
td { 
	vertical-align: top; 
	border: 0;
}
.title {
	font-weight: bold;
}
.button {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: small;
	border: solid 1px #7C7A7A;
	color: white;
	background-color: #74B743;
}
table.header {
	border: 1px solid green;
	background-color: #EEEEEE;
	margin-bottom: 1em;
	font-size: small;
}
table.info {
	border: 1px solid green;
	background-color: #EEEEEE;
	margin-bottom: 1em;
}
table.reservation {
	font-size: small;
}
.centered {
	font-size: small;
	text-align: center;
}
.alert {
	color: red;
	text-align: center;
}
.message {
	text-align: center;
	margin-bottom: .5em;
}
div.main {
	margin: 0 5px 0 5px;
}
div.footer {
	text-align: left;
	font-size: small;
}
div.paragraph {
	margin-bottom: 1em;
}
div.smallparagraph {
	margin-bottom: 1em;
	font-size: small;
}
.smallinfo {
	font-size: small;
}
select.dropdown {
	font-size: small;
}
select.smallselect {
	font-size: small;
}
tr.trGrey {
	background-color: #EEEEEE;	
}
tr.trGreen {
	background-color: #EEFFEE;	
}
img {
	border: 0;
}
</style>
<script type="text/javascript">
	function showhide(id) {
		var e = document.getElementById(id);
		if (e.style.display == 'block')
			e.style.display = 'none';
		else
			e.style.display = 'block';
	}
	function checkedShowHide(c, id, id2) {
		var f = document.getElementById(id);
		var g = document.getElementById(id2);
	
		if (c.checked == true) {	
			f.style.display = 'none';
			g.style.display = 'inline';
		} else {
			f.style.display = 'block';
			g.style.display = 'none';
		}
	}
</script>
</head>
<body style="margin: 0;">
	<?
}
function pdafooter($image = true) {
	$image = false;
	$tel = "18887568876";
?>
<div class="footer">
<?
	if ($image) echo '<img src="images/pt_icon_small.gif" border=0>';
/*
<a href="tel:<?=$tel?>">&bull; Call PlanetTran (888) 756-8876</a>
*/
?>
</div>
</div><!-- end main div -->
</body></html>
	<?
}

/* Header bar 
	-begin main div after header
*/
function pdawelcome($mode = null, $back = null) {
global $conf;
$image = $conf['app']['weburi'].'/images/pt_icon_small.gif';
$welcomestr = '';

if ($mode=='main') {
	$header = '<a href="m.index.php?logout=1">Log Out</a>';
	$welcomestr = 'Welcome '.$_SESSION['currentName'];
} else if ($mode=='faq'||$mode=='offers'||$mode=='reserve'||$mode=='cpanel')
	$header = '<a href="m.main.php">Back</a>';
else if ($mode == 'reserve')
	$header = '<a href="m.cpanel.php">Back</a>';
else if ($mode=='location')
	$header = '<a href="m.locations.php">Back</a>';
else if ($mode=='profileedit')
	$header = '<a href="m.profile.php">Back</a>';
else if ($mode=='rehail')
	$header = '<a href="digital_hail.php">Refresh</a>';
else
	$header = '<a href="m.main.php">Back</a>';

// If $back is set, manually override the default

if ($back == 'back')
	$header = '<a href="#" onClick="history.go(-1); return false;">Back</a>';
else if ($back)
	$header = '<a href="'.$back.'">Back</a>';

if ($mode != 'main' && Auth::is_logged_in()) $header = '<a href="m.main.php">Main Menu</a> | '.$header;


	?>
<table class="header" cellspacing=0 cellpadding=0 width="100%" rules="none">
<tr>
	<td align="left" style="vertical-align: middle;">
	<img align="absmiddle" src="<?=$image?>" border=0> <b>PlanetTran Mobile</b></td>
	<td align="right" style="vertical-align: middle;">
	<?=$header?>
	</td>
</tr>
</table>
<div class="main">
	<?
	
	echo $welcomestr;
}

/*
* Login for for index page
*/
function pdaLoginForm($msg = '', $resume = '') {
	$tel = "18887568876";
	//CmnFns::diagnose($_POST);
	
	$resume = isset($_GET['resume']) ? $_GET['resume'] : "m.main.php";
	
	global $conf;
	$link = CmnFns::getNewLink();

	if (!empty($msg))
		CmnFns::do_error_box($msg, '', false);
?>
<div align="center" style="margin-bottom: 1em;"><img src="images/planettran_logo_pda.gif" border=0></div>
<form name="login" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<table width="100%" cellspacing=0 cellpadding=0>
	<tr>
		<td width="20%">Email</td>
  		<td width="80%"><input type="text" name="email"></td>
	<tr>
		<td>Password</td>
		<td><input type="password" name="password"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" class="button" value="Log in"></td>
	</tr>
	</table>
	
	<input type="hidden" name="setCookie" value="true"> 
	<input type="hidden" name="resume" value="<?=$resume?>" />
	<input type="hidden" name="login" value="1" />
</form>  
<!--
<div style="text-align: center;">
	<a href="tel:<?=$tel?>">Call PlanetTran (888) 756-8876</a>
</div>
-->
<? 
about_contact();
?>
<div style="text-align: center;">
Setup on your handheld home screen
</div>
<table width="100%" cellspacing=0 cellpadding=2 class="centered">
<tr>
<td width="33%">
<a href="m.info.php?mode=bb"><img src="images/planettran-blackberry-small.png"><br>Blackberry</a>
</td>
<td width="34%">
<a href="m.info.php?mode=android"><img src="images/planettran-android-small.png"><br>Android</a>
</td>
<td width="33%">
<a href="m.info.php?mode=iphone"><img src="images/planettran-iphone-small.png"><br>iPhone</a>
</td>
</tr>
</table>
<?
}

function about_contact($back = '') {
	$return = '';
	if ($back) $return = "?back=$back";
	?>
	<div style="font-size: small; text-align: center;">
		<a href="m.about.php<?=$return?>">About</a> |
		<a href="m.contact.php<?=$return?>">Contact</a>
	</div>
	<?
}


function print_hidden_fields_pda(&$res) {
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

/*
* List of reservations on the control panel
* $ismain display special options for the main page
*/
function print_pda_res_table($res, $err, $ismain = false) {
	// If it's the main page and there are no active reservations, return
	if ($ismain && !$res) return;
	$tomorrow = mktime(0,0,0, date("m"), date("d")+1, date("Y"));

	

	if ($ismain) { 
		echo '<div style="font-size: small;">Active reservations</div>';
		$type = 'v';
	} else
		$type = 'm';
	echo '<table width="100%" cellspacing=0 cellpadding=3 class="info">';

	for ($i=0, $x=0; $res[$i]; $i++) {
		$cur = $res[$i];

		//if ($cur['date'] > $tomorrow) continue;

		$resid = strtoupper(substr($cur['resid'], -6));
		$x++;
		$date = CmnFns::formatDate($cur['date']);
		$time = CmnFns::formatTime($cur['pickupTime']);
		$link = '<a href="m.reserve.php?resid='.$cur['resid'].'&type='.$type.'">'.$resid.'</a>';
		echo '<tr>';
		echo '<td width="34%">&bull; '.$link.'</td>';
		echo '<td width="33%">'.$time.'</td>';
		echo '<td width="33%">'.$date.'</td>';
		echo "</tr>\n";
	}
	echo '</table>';
	if ($x == 0) echo '<div class="message">No upcoming reservations.</div>';
	//print_res_options();
}
/*
* print reservations links, create locations etc
*/
function print_res_options() {
	?>
	<ul>
	<li><a href="m.reservations.php">Upcoming Reservations</a></li>
	<!--<li><a href="m.quote.php?type=r&mtype=o">Fare Quote</a></li>-->
	<li><a href="m.newres.php">New Reservation / Quote</a></li>
	<li><a href="m.history.php?mode=receipt">Trip History</a></li>
	<li><a href="m.locations.php">Location Manager</a></li>
	</ul>

	<?
}

function print_viewonly($loclist, $toloc, $fromloc, &$res, $trip, $isActive = false) {
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
	
	
	$h = file_get_contents($path);
	if ($h === false) echo 'was false';
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
	<table width="100%" cellspacing=1 cellpadding=1>
	<tr>
		<td class="title" width="20%">Conf. #</td>
		<td width="80%"><?=$resid?></td>
	</tr>
	<tr>
		<td class="title" width="20%">Date</td>
		<td width="80%"><?=$date?></td>
	</tr>
	<tr>
		<td class="title" width="20%">Time</td>
		<td width="80%"><?=$time?></td>
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

	if (!$isActive) {
		?>
		<br>
		<a href="m.reserve.php?type=r&mtype=<?=$res->mtype?>&machid=<?=$res->machid?>&toLocSelect=<?=$res->toLocation?>">Create a new reservation from these locations</a>
		<?
	}

	echo '</div>';

	$callLink = mobile_tel_link();
	?>
	<a href="m.main.php">Main menu</a><br>
	<?
	echo $callLink;
	//echo '<div class="smallinfo">Booked through '.CmnFns::reservation_origin($res->origin).'</div>';
}

/* Create, modify and delete. */
function print_pda_res($loclist, $to_selected, $from_selected, &$res, $paymentOptions) {
	global $conf;
	$toLocSelect = $_GET['toLocSelect'];

	//if (!$toLocSelect) $toLocSelect = $to_selected;

	$special = $res->specialItems;
	$suv = strrchr($special, 'S');
	$lux = strrchr($special, 'L');
	$tod = strrchr($special, 'T');
	$inf = strrchr($special, 'I');
	$mul = strrchr($special, 'M');
	$vip = strrchr($special, 'V');
	$psl = strrchr($special, 'P');
	$grt = strrchr($special, 'G');
	$wat = strrchr($special, 'A');
	$van = strrchr($special, 'N');
	$vp2 = strrchr($special, 'E');
	$crb = strrchr($special, 'C');
	$fst = strrchr($special, 'F');
	$bst = strrchr($special, 'O');
	$lux = strrchr($special, 'L');

	$pax = strpbrk($special, '123456789');
	$pax = $pax ? $pax : 1;


	$carSelected = $suv ? 'S' : ($van ? 'N' : ($lux ? 'L' : ''));

	$seatSelected = $inf ? 'I' : ($tod ? 'T' : ($bst ? 'O' : ''));

	$timeSelected = $_GET['start'] ? $_GET['start'] : null;
	$dateSelected = $_GET['date'] ? $_GET['date'] : null;

	if ($dateSelected) {
		list($monthSelected, $daySelected, $yearSelected) = explode("-", $dateSelected);

	}
	
	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
	$t = new Tools();

	$carSelect = $t->car_select_array();
	$seatSelect = $t->seat_select_array();
	$waitTimes = $t->get_authWaitTimes();

	$months = $t->get_month_array(6);
	$days = $t->get_day_array();
	$years = $t->get_year_array(2);
	global $quote;
	$today = date("m/d/Y", $res->today);
	$tomorrow = date("m/d/Y", $res->tomorrow);

	$type = $_GET['type'] ? $_GET['type'] : ($res->type ? $res->type : 'r');
	$mtype = $_GET['mtype'];

	// Don't allow an h reservation to become o on modification
	if ($res->mtype == 'h' || $res->mtype == 'o') $mtype = $res->mtype;

	$destination = $quote ? "m.quote.php?type=$type&mtype=$mtype" : $_SERVER['PHP_SELF'];
	if ($type == 'r' && $_GET['coupon']) {
		$coupon = $_GET['coupon'];
	} else
		$coupon = $res->coupon;
	$coupon = $_GET['coupon'];
	$types = array('r'=>	'Quote',
			'm'=>	'Modify',
			'd'=>	'Delete');
	$function = array(	'r'=>	'create',
				'm'=>	'modify',
				'd'=>	'delete');

	$notes = $res->db->parseNotes($res->summary);

	$action = $types[$res->type];
	$fn = $function[$res->type];
	if ($res->id)
		$showid = CmnFns::showid($res->id);
	else
		$showid = '';

	$titlestr = "$action reservation $showid";

        ?>
	<script type="text/javascript">
	function authWaitCheck(a) {
		var authDiv = document.getElementById('waitDiv');

		//if (document.reserve.wait.checked == true) {
		if (a.checked == true) {
			alert("ipsum lorax");
			authDiv.style.display = 'block';
		} else {
			authDiv.style.display = 'none';
		}
	}
	</script>
	<form name="reserve" id="reserve" method="post" action="<?=$destination?>">
	<table width="100%" border=0 cellspacing=0 cellpadding=1 class="reservation">
	<tr>
		<td width="15%">From</td>
		<td width="85%"><select name="fromLoc" class="dropdown">
        	<option value="">Select location</option><?

	//for ($i = 0; $i < count($loclist); $i++) {
	for ($i = 0; $i < count($loclist); $i++) {
		$cur = $loclist[$i];
		if (strlen($cur['name']) >= 50)
			$cur['name'] = substr($cur['name'], 0, 47) . '...';

		echo '<option value="' . $cur['machid'] . '"';

                if ( ($from_selected == $cur['machid']) )
                	echo ' selected="selected" ';
		echo '>' . $cur['name'] . '</option>';
        } 
		?>
		</select></td>
	</tr>
	<tr>
         	<td>To</td>
		<td>
	<?

	// For As Directed, there is no To select; it's asDirectedLoc

	if ($mtype == 'h') {
		echo 'As Directed';
		echo '<input type="hidden" name="toLoc" value="asDirectedLoc">';
	} else {
		?>

		<select name="toLoc" class="dropdown">
	          <option value="">Select location</option>
        	<? 

		for ($i = 0; $i < count($loclist); $i++) {
			$cur = $loclist[$i];
			if (strlen($cur['name']) >= 50)
				$cur['name'] = substr($cur['name'], 0, 47) . '...';
			echo '<option value="' . $cur['machid'] . '"';

       		         if ($to_selected == $cur['machid'])
				echo ' selected="selected" ';
       		         else if ($toLocSelect == $cur['machid'])
				echo ' selected="selected" ';
			echo '>' . $cur['name'] . '</option>';
		} 
		echo '</select>';
	}
	?>
	</td>
	</tr>
	<tr>
		<td>Date</td>
		<td>
		<!--<select name="date">
		<option value="<?=$today?>"<?=($res->date == $res->today ? " selected" : '')?>>Today</option>
		<option value="<?=$tomorrow?>"<?=$res->date == $res->tomorrow ? " selected" : ''?>>Tomorrow</option>
		</select>
		-->
		<?

		$msel = $monthSelected ? $monthSelected : $res->month;
		$dsel = $daySelected ? $daySelected : $res->day;
		$ysel = $yearSelected ? $yearSelected : $res->year;

		$t->print_dropdown($months, $msel, 'month');	
		$t->print_dropdown($days, $dsel, 'day');	
		$t->print_dropdown($years, $ysel, 'year');	

		?>
		</td>
	</tr>
	<tr>
		<td>Time</td>
		<td>
		<?
		// Start time select box
		$interval = 15;//$rs->sched['timeSpan'];
		$startDay = 0;//$rs->sched['dayStart'];
		$endDay	  = 720;//$rs->sched['dayEnd'];
		list($acode, $fnum, $fdets) = explode("{`}", $res->get_flightDets());
		$flightDets = $acode . $fnum . " " . $fdets;
		$start = $res->get_start() >= 720 ? $res->get_start() - 720 : $res->get_start();

		if ($timeSelected)
			$start = $timeSelected >= 720 ? $timeSelected - 720 : $timeSelected;

                echo '<select name="startTime">';

            	// If creating or modding, make the first option an empty string
		if ($res->type == 'r' || $res->type == 'm')
            		echo '<option value=""></option>';

            	// Start at startDay time, end 30 min before endDay
            	for ($i = $startDay; $i < $endDay; $i+=$interval) {
               		 echo '<option value="' . $i . '"';
                	// If this is a modification, select correct time
		
              		if (($start == $i) && ($start != ''))
	                    	echo ' selected="selected" ';
			else if ($timeSelected && $timeSelected == $start)
	                    	echo ' selected="selected" ';
                	echo '>' . CmnFns::formatTime($i, true) . '</option>';
            	}
            	echo "</select>\n";

            	// Start AM/PM box

            	echo '<input type="radio" name="ampm" value="am" ';
            	if (($res->get_start() < 720) && ($res->get_start() != '') || ($timseSelected && $timeSelected < 720))
                    echo 'checked';
	    	echo '>AM';	
		
            	echo '   <input type="radio" name="ampm" value="pm" ';
	        if (($res->get_start() >= 720) && ($res->get_start() != '') ||($timeSelected && $timeSelected >= 720) )
	               echo 'checked';
            	echo '> PM';
		?>
		</td>
	</tr>
	<tr>
		<td>Flight (if applicable)</td>
		<td><input type="text" name="flightnumber" size=4 value="<?=$acode.$fnum?>"> (example: AA100)
		</td>
	</tr>
	<tr>
		<td>Vehicle Type</td>
		<td>
		<?

		$t->print_dropdown($carSelect, $carSelected, 'carTypeSelect', 'smallselect');

		?>
		</td>
	</tr>
	<tr>
		<td>Child Seats</td>
		<td>
		<?

		$t->print_dropdown($seatSelect, $seatSelected, 'seatTypeSelect', 'smallselect');

		?>
		</td>
	</tr>
	<?

	if ($mtype != 'h') { // Skip mult and auth wait for As Directed
	$waitDisplay = $wat ? 'block' : 'none';

		?>
		<tr>
		<td>Special Items</td>
		<td>
		<table width="100%" cellspacing=0 cellpadding=0 border=0 class="reservation">
			<tr>
			<td><input type="checkbox" name="multiple" <?=($mul?'checked':'')?>> Multiple stops</td>
			<td><input type="checkbox" name="wait" onChange="authWaitCheck(this)" <?=($wat?'checked':'')?>> Authorized wait
			<div id="waitDiv" style="display: <?=$waitDisplay?>;">
			<?
			$t->print_dropdown($waitTimes, $res->authWait, 'authWait');
			?>
			</div>
			</td>
			</tr>
		</table>
		</td>
		</tr>
		<?
	
	}
		
	?>	
	<tr>
	<td>Number of passengers:</td>
	<td>
	<?

	echo '<select name="pax">';
	for ($i = 1; $i <= 9; $i++) {
		$selected = $i == $pax ? ' selected' : '';
		echo "<option value=\"$i\"$selected>$i</option>";
	}	
	echo '</select></td></tr>';

	/*
	*  if this is a round trip, print another set of
	*   time/date/flight options				*/
	
	if ($mtype == 'r') {
	?>
	<tr><td colspan=2><b>Return trip</b></td></tr>

	<tr>
		<td>Date</td>
		<td>
		<!--<select name="date2">
		<option value="<?=$today?>"<?=($res->date == $res->today ? " selected" : '')?>>Today</option>
		<option value="<?=$tomorrow?>"<?=$res->date == $res->tomorrow ? " selected" : ''?>>Tomorrow</option>
		</select>
		-->
		<?
		$t->print_dropdown($months, $res->month, 'month2');	
		$t->print_dropdown($days, $res->day, 'day2');	
		$t->print_dropdown($years, $res->year, 'year2');	
		?>
		</td>
	</tr>
	<tr>
		<td>Time</td>
		<td>
		<?
		// Start time select box
		$interval = 15;//$rs->sched['timeSpan'];
		$startDay = 0;//$rs->sched['dayStart'];
		$endDay	  = 720;//$rs->sched['dayEnd'];
		list($acode, $fnum, $fdets) = explode("{`}", $res->get_flightDets());
		$flightDets = $acode . $fnum . " " . $fdets;
		$start = $res->get_start() >= 720 ? $res->get_start() - 720 : $res->get_start();

                echo '<select name="startTime2">';

            	// If creating or modding, make the first option an empty string
		if ($res->type == 'r' || $res->type == 'm')
            		echo '<option value="" selected="selected"></option>';

            	// Start at startDay time, end 30 min before endDay
            	for ($i = $startDay; $i < $endDay; $i+=$interval) {
               		 echo '<option value="' . $i . '"';
                	// If this is a modification, select correct time
		
              		if (($start == $i) && ($start != ''))
                    	echo ' selected="selected" ';
                	echo '>' . CmnFns::formatTime($i, true) . '</option>';
            	}
            	echo "</select>\n";

            	// Start AM/PM box

            	echo '<input type="radio" name="ampm2" value="am" ';
            	if (($res->get_start() < 720) && ($res->get_start() != ''))
                    echo 'checked';
	    	echo '>AM';	
		
            	echo '   <input type="radio" name="ampm2" value="pm" ';
	        if (($res->get_start() >= 720) && ($res->get_start() != ''))
	               echo 'checked';
            	echo '> PM';
		?>
		</td>
	</tr>
	<tr>
		<td>Flight</td>
		<td><input type="text" name="flightnumber2" size=4 value="<?=$acode.$fnum?>"> (if applicable)
		</td>
	</tr>

	<?

	}	


	?>
	<tr>
		<td>Coupon</td>
		<td><input type="text" name="coupon" value="<?=$coupon?>">
		</td>

	</tr>
	<tr>
		<td>Payment</td>
		<td>
		<?

		$t->print_dropdown($paymentOptions, null, 'paymentProfileId');

		?>
		</td>

	</tr>
	<?

	if ($mtype == 'h' || $res->mtype == 'h') {
		$hours_arr = range(0,10);
		$hours_arr = array_slice($hours_arr, 2, 10, true);
		?>
		<tr>
		<td>Trip length</td>
		<td>
		<input type="hidden" name="wait" value="1">
		<?

		$t->print_dropdown($waitTimes, $res->authWait, 'authWait');
		//$t->print_dropdown($hours_arr, $res->hour_estimate, 'hour_estimate');
		?>
		Please estimate how many hours you will use the vehicle.
		</td>
		</tr>
		<?

	}

	?>
	<tr>
		<td>Notes</td>
		<td>
		<textarea name="summary"><?=$notes['notes']?></textarea></td>

	</tr>
	</table>
	<div style="text-align: center;">
	<input type="hidden" name="submit" value="1">
	<input type="hidden" name="mtype" value="<?=$mtype?>">
	<input type="hidden" name="group" value="1">
	<?
	if ($dateSelected && $timeSelected)
		echo '<input type="hidden" name="bypass" value="1">';
	
	if ($quote) echo '<input type="hidden" name="getquote" value="1">';

	?>
	<input type="submit" class="button" value="<?=$types[$type]?>">
	<?
	
	// print delete button
	if ($res->type == 'm') {
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '<input type="submit" class="button" name="del" value="Cancel Reservation">';
	}

	echo '</div>';
	print_hidden_fields_pda($res);
	echo '<input type="hidden" name="fn" value="'.$fn.'">';
	echo '</form>';
}

function print_hail_fail() {
	?>
	<div style="text-align: center;">
	We apologize, but we were unable to determine your GPS location.
	<br>
	You may book a reservation normally by <a href="m.cpanel.php">clicking here</a>.
	<br>&nbsp;<br>
	<a href="m.main.php">Main Menu</a>
	</div>

	<?
}

/*
* special res for for digital hailing
*/
function print_hail_res($loclist, $tiny_loclist = array(), $to_selected, $from_selected, &$res, $idle = array()) {
	$lat = $_GET['lat'];
	$lon = $_GET['lon'];

	// get dist to boston and to CA, use whichever is closest

	$dist1 = CmnFns::homebase_distcalc($lat, $lon, 'MA');
	$dist2 = CmnFns::homebase_distcalc($lat, $lon, 'CA');
	$homebase_dist = $dist2 > $dist1 ? $dist1 : $dist2;

	//echo $homebase_dist;

	global $conf;

	$pax = strpbrk($special, '123456789');
	$pax = $pax ? $pax : 1;

	$day = date("j");
	$mon = date("n");
	$year = date("Y");
	$dateSelected = $_GET['date'] ? $_GET['date'] : null;

	if ($dateSelected) {
		list($monthSelected, $daySelected, $yearSelected) = explode("-", $dateSelected);

	}
	
	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
	$t = new Tools();

	$months = $t->get_month_array(6);
	$days = $t->get_day_array();
	$years = $t->get_year_array(2);

	global $quote;
	$today = date("m/d/Y", $res->today);
	$tomorrow = date("m/d/Y", $res->tomorrow);

	$type = 'r';
	$mtype = 'o';

	// new destination?
	$destination = $quote ? "m.quote.php?type=$type&mtype=$mtype" : $_SERVER['PHP_SELF'];

	$types = array('r'=>	'Quote',
			'm'=>	'Modify',
			'd'=>	'Delete');
	$function = array(	'r'=>	'create',
				'm'=>	'modify',
				'd'=>	'delete');

	$gpsmatch = false;
	$gpsname = $_GET['gpsname'] ? $_GET['gpsname'] : "Your GPS location";
	for ($i = 0; $i < count($tiny_loclist); $i++) {
		$row = $tiny_loclist[$i];
		if (strpos($row['name'], "GPS Match") !== false)
			$gpsmatch = true;
	}

	$prepend = array(	
			'machid'=>'fromgps',
			'name'=>$gpsname,
			'lat'=>$lat,
			'lon'=>$lon);

	if (!$tiny_loclist) $tiny_loclist = array();

	if ($lat && $lon && !$gpsmatch) { 
		array_unshift($tiny_loclist, $prepend);
		//echo '<option value="fromgps">'.$gpsname.'</option>';

	} else if (!$tiny_loclist)
		$tiny_loclist = $prepend;
	 
	if (!$lat || !$lon || !count($tiny_loclist)) {
		print_hail_fail();
		return;
	}

	$mapstr = '';
	$names = array('A','B','C','D');
	for ($i=0; $i<count($tiny_loclist); $i++) {
		$tiny_loclist[$i]['name'] = $names[$i].". ".$tiny_loclist[$i]['name'];
		$tiny_loclist[$i]['label'] = $names[$i];
		$cur = $tiny_loclist[$i];
		$mapstr .= "&markers=color:blue|label:{$names[$i]}|{$cur['lat']},{$cur['lon']}";
		
	}


	$action = $types[$res->type];
	$fn = $function[$res->type];
	if ($res->id)
		$showid = CmnFns::showid($res->id);
	else
		$showid = '';

	$titlestr = "$action reservation $showid";


	$gpsmapname = urlencode(str_replace("(GPS) ", "", $gpsname));

        ?>
	<div class="title" style="text-align: center;">
	Digital Hailing
	</div>
	<div style="text-align: center;">
	<a href="http://maps.google.com/?q=<?=$gpsmapname?>@<?=$lat?>,<?=$lon?>" target="_blank">
	<img src="http://maps.google.com/maps/api/staticmap?size=300x150&maptype=roadmap<?=$mapstr?>&sensor=true">
	</a>
	</div>
	<div class="smallparagraph" style="text-align: center;">
	We've used your GPS to locate you and are at your service. Please select your destination for a quotation.
	</div>
	<form name="reserve" id="reserve" method="post" action="<?=$destination?>">
	<table width="100%" border=0 cellspacing=0 cellpadding=1 class="reservation">
	<tr>
		<td width="15%">From</td>
		<td width="85%"><select name="fromLoc" class="dropdown" onChange="checkForApt(this, 'flightDisplay')">
	<?


	if ($tiny_loclist) {
	 for ($i = 0; $i < count($tiny_loclist); $i++) {
		$cur = $tiny_loclist[$i];
		if (strlen($cur['name']) >= 50)
			$cur['name'] = substr($cur['name'], 0, 47) . '...';

		echo '<option value="' . $cur['machid'] . '"';

                if ( ($from_selected == $cur['machid']) )
                	echo ' selected="selected" ';
		echo '>' . $cur['name'] . '</option>';
       	 } 
	}
	
	
	echo '</select>';
	if ($lat && $lon) {
		echo '<input type="hidden" name="lat" value="'.$lat.'">';
		echo '<input type="hidden" name="lon" value="'.$lon.'">';
	}

		?>
		<div id="flightDisplay" style="display: none;">
		Flight <input type="text" name="flightnumber" size=4 value="<?=$acode.$fnum?>"> (example: AA100)
		</div>
	<script type="text/javascript">
		function checkForApt(id, divId) {
			var a = id; //document.getElementById(id);
			var i = a.selectedIndex;
			var machid = a.options[i].value;

			var d = document.getElementById(divId);

			if (!machid) return;
			var acheck = machid.substring(0,7);
			if (acheck=='airport')	{
				d.style.display = 'block';
			} else if (machid=='41b40be9091cb' || machid=='gzm41b48f6ae8d87' || machid=='twi4c585f6a2a829') {
				d.style.display = 'block';
			} else {
				d.style.display = 'none';
			}
		}
	</script>
		</td>
	</tr>
	<tr>
         	<td>To</td>
		<td>
	<?

	// For As Directed, there is no To select; it's asDirectedLoc

	?>
	<div id="toLocDisplay">
	<select name="toLoc" class="dropdown">
	<option value="">Select location</option>
        <? 

	for ($i = 0; $i < count($loclist); $i++) {
		$cur = $loclist[$i];
		if (strlen($cur['name']) >= 50)
			$cur['name'] = substr($cur['name'], 0, 47) . '...';
		echo '<option value="' . $cur['machid'] . '"';

       	         if ($to_selected == $cur['machid'])
			echo ' selected="selected" ';
       	         else if ($toLocSelect == $cur['machid'])
			echo ' selected="selected" ';
		echo '>' . $cur['name'] . '</option>';
	} 
	?>
	</select> or 
	</div>
	<input type="checkbox" name="asDirected" onChange="checkedShowHide(this, 'toLocDisplay', 'hourEstimateDisplay')"> As Directed <span id="hourEstimateDisplay" style="display: none;">for
	
	<?

	$hours_arr = range(0,10);
	$hours_arr = array_slice($hours_arr, 2, 10, true);

		$t->print_dropdown($hours_arr, $res->hour_estimate, 'hour_estimate');

	?>
	hours</span>
	</td>
	</tr>
	<tr>
		<td>Date</td>
		<td style="font-size: normal;"><?=$mon?>/<?=$day?>/<?=$year?>
		<input type="hidden" name="day" value="<?=$day?>">
		<input type="hidden" name="month" value="<?=$mon?>">
		<input type="hidden" name="year" value="<?=$year?>">
		<input type="hidden" name="hail_quote" value="1">
		<input type="hidden" name="ishail" value="1">
		</td>
	</tr>
	<tr>
		<td>Estimated availability</td>
		<td><?
	

		$times = array('30'=>'30 minutes',
				'45'=>'45 minutes',
				'60'=>'1 hour',
				'75'=>'1 hour 15 minutes',
				'90'=>'1 hour 30 minutes',
				'105'=>'1 hour 45 minutes',
				'120'=>'2 hours');

		// not enough free cars, slice off 30 and 45
		// or greater than x mileage
		if (count($idle) == 2 || ($homebase_dist>10 && $homebase_dist<=25))
			$times = array_slice($times, 1, 100, true);
		else if (count($idle) <= 1 || $homebase_dist > 25)
			$times = array_slice($times, 2, 100, true);
		

		$n = 0;
		foreach ($times as $key=>$val) {
			if ($n > 0) break;
			echo $val;
			$n++;
		}

		?></td>
	</tr>
	<tr>
		<td>Pick up in:</td>
		<td>
		<?


		$hour = date("G");
		$minute = date("i");
		$startTime = $hour * 60 + $minute;
		$startTime -= $startTime % 15;

                echo '<select name="specialTime">';
		foreach ($times as $k=>$v) 
			echo "<option value=\"$k\">$v</option>\n";

		?>
		</select>
		<input type="hidden" name="startTime" value="<?=$startTime?>">
		</td>
	</tr>
	<tr>
	<td>Number of passengers:</td>
	<td>
	<?

	echo '<select name="pax">';
	for ($i = 1; $i <= 9; $i++) {
		$selected = $i == $pax ? ' selected' : '';
		echo "<option value=\"$i\"$selected>$i</option>";
	}	
	echo '</select></td></tr>';


	$hours_arr = range(0,10);
	$hours_arr = array_slice($hours_arr, 2, 10, true);
	?>
	<!--<tr>
	<td>Hours</td>
	<td>
	<?

	$t->print_dropdown($hours_arr, $res->hour_estimate, 'hour_estimate');
	?>
	Please estimate how many hours you will use the vehicle.
	</td>
	</tr>-->
	<?

	?>
	<tr>
		<td>Notes</td>
		<td>
		<textarea name="summary"><?=$notes['notes']?></textarea></td>

	</tr>
	</table>
	<div style="text-align: center;">
	<input type="hidden" name="submit" value="1">
	<input type="hidden" name="mtype" value="<?=$mtype?>">
	<input type="hidden" name="group" value="1">
	<?
	if ($dateSelected && $timeSelected)
		echo '<input type="hidden" name="bypass" value="1">';
	
	if ($quote) echo '<input type="hidden" name="getquote" value="1">';

	?>
	<input type="submit" class="button" value="<?=$types[$type]?>">
	<?
	
	echo '</div>';
	print_hidden_fields_pda($res);
	echo '<input type="hidden" name="fn" value="'.$fn.'">';
	echo '</form>';
}

function acode_dropdown_mobile() {
      ?><select name="airline" class="dropdown">
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
<option value="EK" >Emirates - EK</option>
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

function print_mobile_success($verb, &$res) {
	$msg = '';
	if ($verb == 'deleted') $verb = 'canceled';


	// round trip
	if ($res->mtype == 'r' && ($res->type == 'm' || $res->type == 'd')) 
		$msg = "This reservation is linked with one or more reservations as part of a round trip. To modify or delete the linked reservation, <a href=\"m.reserve.php?type=m&resid=".$res->linked_reservations[0]['resid'].'">Click Here</a><br>';
	?>
	<div style="text-align: center; margin-top: 2em;">
	<img src="images/planettran_logo_pda.gif" border=0>
	<br>
	Your reservation has been <?=$verb?>.<br>
	<?=$msg?>
	<a href="m.cpanel.php">Back to Reservations</a><br>
	<a href="m.main.php">Back to Main</a>
	</div>
	<?
}

function print_pda_location_list($locs = array(), $scheduleid = null, $isRecent = false) {

	// if viewing recent location we print a table, otherwise bullet list
	if ($isRecent)
		echo '<table width="100%" cellspacing=0 cellpadding=2>';
	else
		echo '<ul>';

	for ($i=0; $locs[$i]; $i++) {
		$cur = $locs[$i];

		if ($isRecent) {
			$date = date("m/d/y", $cur['date']);
			echo "<tr><td>&bull; <a href=\"m.location.php?type=v&machid={cur['machid']}\">{$cur['name']}</a></td>";
			echo "<td><a href=\"m.reserve.php?type=v&resid={$cur['resid']}&machid={$cur['machid']}\">$date</a></td></tr>";
		} else
			echo '<li><a href="m.location.php?type=v&machid='.$cur['machid'].'">'.$cur['name'].'</a></li>';
	}
	
	if ($isRecent)
		echo '</table>';
	else
		echo '</ul>';

}
function pda_location_form($loc = array(), $scheduleid, $type = 'c') {
	$name = $loc['name'] ? $loc['name'] : '';
	$add1 = $loc['address1'] ? $loc['address1'] : '';
	$add2 = $loc['address2'] ? $loc['address2'] : '';
	$city = $loc['city'] ? $loc['city'] : '';
	$state = $loc['state'] ? $loc['state'] : '';
	$zip = $loc['zip'] ? $loc['zip'] : '';
	$phone = $loc['rphone'] ? $loc['rphone'] : '';
	$notes = $loc['notes'] ? $loc['notes'] : '';

	if ($type == 'c')
		$fn = 'create';
	else if ($type == 'm')
		$fn = 'modify';
	else if ($type == 'd')
		$fn = 'delete';

	?>
	<form name="locform" action="m.location.php" method="post">
	
	<table width="100%" cellspacing=1 cellpadding=1 class="reservation">
	<tr>
		<td class="title" width="20%">* Name</td>
		<td width="80%">
		<input type="text" name="name" value="<?=$name?>">
		</td>
	</tr>
	<tr>
		<td class="title">* Address 1</td>
		<td>
		<input type="text" name="address1" value="<?=$add1?>">
		</td>
	</tr>
	<tr>
		<td class="title">Address 2</td>
		<td>
		<input type="text" name="address2" value="<?=$add2?>">
		</td>
	</tr>
	<tr>
		<td class="title">* City</td>
		<td>
		<input type="text" name="city" value="<?=$city?>">
		</td>
	</tr>
	<tr>
		<td class="title">* State</td>
		<td>
		<input type="text" name="state" value="<?=$state?>" size=2>
		</td>
	</tr>
	<tr>
		<td class="title">* Zip Code</td>
		<td>
		<input type="text" name="zip" value="<?=$zip?>">
		</td>
	</tr>
	<tr>
		<td class="title">Location Phone</td>
		<td>
		<input type="text" name="rphone" value="<?=$phone?>">
		</td>
	</tr>
	<tr>
		<td class="title">Location Notes</td>
		<td>
		<textarea name="notes"><?=$notes?></textarea>
		</td>
	</tr>
	<tr>
		<td>* required field</td>
		<td><input type="submit" value="Submit"></td>
	</tr>
	</table>
	
	<input type="hidden" name="scheduleid" value="<?=$scheduleid?>">
	<input type="hidden" name="type" value="<?=$type?>">
	<input type="hidden" name="fn" value="<?=$fn?>">
	<input type="hidden" name="machid" value="<?=$loc['machid']?>">
	</form>
	<?
}

function location_view($loc = array(), $scheduleid) {
	$type= $_GET['type'];
	?>
	<table width="100%" cellspacing=1 cellpadding=1 class="reservation">
	<tr>
		<td class="title" width="20%">Name</td>
		<td width="80%"><?=$loc['name']?></td>
	</tr>
	<tr>
		<td class="title">Address 1</td>
		<td><?=$loc['address1']?></td>
	</tr>
	<tr>
		<td class="title">Address 2</td>
		<td><?=$loc['address2']?></td>
	</tr>
	<tr>
		<td class="title">City</td>
		<td><?=$loc['city']?></td>
	</tr>
	<tr>
		<td class="title">State</td>
		<td><?=$loc['state']?></td>
	</tr>
	<tr>
		<td class="title">Zip code</td>
		<td><?=$loc['zip']?></td>
	</tr>
	<tr>
		<td class="title">Location phone</td>
		<td><?=$loc['rphone']?></td>
	</tr>
	<tr>
		<td class="title">Location notes</td>
		<td><?=$loc['notes']?></td>
	</tr>
	</table>
	<div>
	<?

	// Only allow editing if the location belongs to this user;
	// i.e. its scheduleid matches the user's scheduleid
	if ($loc['scheduleid'] == $scheduleid) {
		?>
		<div><a href="m.location.php?type=m&machid=<?=$loc['machid']?>">Edit location</a></div>
		<?
	}
	// All locations except for special Recommended locs
	if ($type == 'v' && $loc['status'] == 'a') {
		?>
		<div><a href="m.reserve.php?type=r&machid=<?=$loc['machid']?>">Create a reservation <b>from</b> this location</a></div>
		<div><a href="m.reserve.php?type=r&toLocSelect=<?=$loc['machid']?>">Create a reservation <b>to</b> this location</a></div>
		<?
	} else if ($type == 'v' && $loc['status'] != 'a') {
	// Recommended Locations
		?>
		<div><a href="m.addLoc.php?type=r&machid=<?=$loc['machid']?>">Create a reservation <b>from</b> this location</a></div>
		<div><a href="m.addLoc.php?type=r&toLocSelect=<?=$loc['machid']?>">Create a reservation <b>to</b> this location</a></div>
		<?
	}

	echo '</div>';
}

function print_profile_edit($user) {
	$field = $_GET['field'];
	if ($field == 'password')
		$curval = '******';
	else
		$curval = $user[$field];

	$display = array('fname'	=>'First Name',
			'lname'		=>'Last Name',
			'institution'=>'Organization',
			'position'=>'Dept. Code',
			'password'=>'Password',
			'phone'=>'Cell Phone',
			'email'=>'Email/Login',
			'twitter_username'=>	'Twitter Username');

	?>
	<form name="mProfileEdit" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	Current <?=$display[$field]?>: <?=$curval?><br>
	New <?=$display[$field]?>: 
	<input type = "text" name="newval">
	<br>
	<input type="hidden" name="memberid" value="<?=$user['memberid']?>">
	<input type="hidden" name="field" value="<?=$field?>">
	<input type="hidden" name="update" value="1">
	<input type="submit" value="Update">
	</form>
	<?
}
function print_history($res) {
	//CmnFns::diagnose($res);
	$mode = $_GET['mode'];
	$total = count($res);
	
	for ($i = 0, $count = 0; is_array($res) && $i < count($res); $i++) {
		$cur = $res[$i];
		if ($cur['feedbackDone']) $count++;
	}

	if ($_GET['mode'] == 'feedback') {
		$s =  ($count && $count == 1) ? '' : 's';
		?>
		<div class="smallparagraph">
		You have submitted feedback for <?=$count?> trip<?=$s?>.
		</div>
		<?

	}

	?>
	<div class="smallinfo">
	We utilize your feedback for <b>each</b> trip to help serve you better. This data helps us tailor your personalized PlanetTran experience. To this end, please take a moment to provide feedback after each trip and be sure that you are commenting on the correct trip and driver.
	</div>
	<?

	if (!$res) {
		?>
		There are no reservations in your history.<br>&nbsp;<br>
		<a href="m.cpanel.php">Return to Reservations</a><br>
		<a href="m.main.php">Return to Main</a>
		<?
		return;
	}

	?><table width="100%" cellspacing=0 cellpadding=2>
	<tr class="title">
		<td width="25%">Date</td>
		<td width="25%">From</td>
		<td width="25%">To</td>
		<td width="25%">&nbsp;</td>
	</tr>
	<?
	for ($i = 0; is_array($res) && $i < count($res); $i++) {
		$rs = $res[$i];
		$driver = '';
		if ($rs['driver']) {
			$driverArr = explode(" ", $rs['driver'], 2);
			$driver = $driverArr[0]." ".substr($driverArr[1], 0, 1);

		}
		if (isset($rs['total'])) {
			//$page = $rs;
			continue;
		}
		// break after 20 iterations for now
		if ($i >= 20) break;

		$class = $i%2 ? 'trGrey' : 'trGreen';
		$showid = CmnFns::showid($rs['resid']);
		$date = CmnFns::formatDate($rs['date']);
		$loc = substr($rs['fromLocationName'], 0, 25);
		$toloc = substr($rs['toLocationName'], 0, 25);

		// only show receipts for PAID trips
		$receipt = $rs['pay_status'] == 25 ? "<a href=\"m.receipt.php?resid={$rs['resid']}\">Email Receipt</a>" : "&nbsp;";

		// No longer showing the fare
		//if ($rs['total_fare'])
		//	$receipt = '$'.$rs['total_fare'].' '.$receipt;

		// only show feedback for trips 30 days or newer
		$feedback = $rs['date'] > (time() - 60*60*24*30) ? "<a href=\"m.feedback.php?resid={$rs['resid']}\">Feedback</a>" : "Feedback Expired";
		if ($rs['feedbackDone']) $feedback = "Survey Completed";

		if ($mode == 'feedback')
			$function = $feedback;
		else if ($mode == 'receipt')
			$function = $receipt;
		else
			$function = $feedback;


		$modified = (isset($rs['modified']) && !empty($rs['modified']))?
			CmnFns::formatDateTime($rs['modified']) : 'N/A';
		echo "<tr class=\"$class\">"
		.'<td><a href="m.reserve.php?type=v&back=m.history.php?mode='.$mode.'&resid='.$rs['resid'].'">'.$date.'</a></td>'
		. "<td>$loc</td>"
		. "<td>$toloc</td>"
		. "<td>$function</td>"
		. "</tr>\n";
	}
	echo '</table>';
	//showPage($page);

	if ($mode == 'feedback') {
		?>
		<div class="smallparagraph" style="margin-top: .5em;">
		<a href="m.generalFeedback.php?back=m.main.php">Leave general feedback</a>
		</div>
		<?
	}
		
}

function locmenu() {
	?>

	<ul>
	<li><a href="m.location.php?type=c&scheduleid=<?=$scheduleid?>">Create new location</a></li>
	<!--<li>Favorite Locations</li>-->
	<li><a href="m.viewLocs.php">Recent Locations</a></li>
	<li><a href="m.allLocs.php">My Locations</a></li>
	<li><a href="m.recLocs.php">Recommended Locations</a></li>
	</ul>

	<?
}

function mobile_tel_link() {
	$tel = "18887568876";
	return "<a href=\"tel:$tel\">Call PlanetTran (888) 756-8876</a>";
}
?>
