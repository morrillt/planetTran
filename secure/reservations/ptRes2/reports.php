<?php

$time_start = microtime(true);
include_once('lib/DBEngine.class.php');
include_once($conf['app']['include_path'].'/reservations/ptRes2/lib/Tools.class.php');
include_once('lib/Template.class.php');

$d = new DBEngine();
$t = new Tools();

if (!Auth::is_logged_in()) 
    Auth::print_login_msg();
else if ($_SESSION['role'] != 'a' && !Auth::isAdmin())
    Auth::print_login_msg();

$mode = CmnFns::getOrPost('mode');
$export = CmnFns::getOrPost('export');
$group = CmnFns::getOrPost('group');
$res = get_reservation_data();

if($export)
	do_export($res,$mode);

$temp = new Template('Reports', false);
$temp->printHTMLHeader('silo_reservations sn11 mn1');
?>
<!--<div style="margin: 20px;">-->
<!--<div style="text-align: center;"><img src="images/planettran_logo_new.jpg" border=0></div>-->
<?

$temp->printNavReservations();
$temp->startMain();
print_table($res);
print_ccc_table($res);
$temp->endMain();
$temp->printHTMLFooter();

// echo '</div>';

$time_end = microtime(true);
$total_time = round($time_end - $time_start, 3);
//echo "Page printed in $total_time seconds.";

/*********************************************************************/
function print_table($res) {
	global $t;
	$month = CmnFns::getOrPost('month');
	$year = CmnFns::getOrPost('year');
	$group = CmnFns::getOrPost('group');
	$groups = get_groups();
	$exportStr = "";
	$exportStr .= $month ? "&month=$month" : "";
	$exportStr .= $year ? "&year=$year" : "";
	if (Auth::isAdmin())
		$exportStr .= "&group=$group";
	$user = get_user();

	if (Auth::isAdmin())
		$group_name = get_group_name($group);
	else
		$group_name = $user['group_name'];

	$months = get_months();  // just lazily moved this code down to clean up a bit
	$years = get_years();

	?>
	<script type="text/javascript">
		function submitThis() {
			document.groupSel.submit();
		}
	</script>
	<div style="text-align: center; font-size: large; margin-top: 5px;">
	Monthly report for <?=$group_name?>
	</div>
	<div>
	<form name="groupSel" action="<?=$_SERVER['PHP_SELF']?>" method="get" style="margin: 0;">
	Search A Month:
	<?
	$t->print_dropdown($months, $month, 'month', null, 'onChange=submitThis()');  // this function is cool.
	$t->print_dropdown($years, $year, 'year', null, 'onChange=submitThis()');

	if (Auth::isAdmin()) {
		$t->print_dropdown($groups, $group, 'group', null, 'onChange=submitThis()');
		echo '<input type="submit" value="Go">';
	}

	if (!$res) return;  // if no resource, stop printing stuff.  including that annoying export button.
	?>


	</form>
		<a href="reports.php?export=1<?=$exportStr?>">Export Results</a>
	</div>

	<table width="100%" cellspacing=1 cellpadding=2 style="background-color: #EEF;">
	  <tr style="font-weight: bold; background-color: #EFE;"><?

	foreach ($res[0] as $field => $v) {
		$field = str_replace("_", " ", $field);
		$field = ucwords($field);
        if ($field != "CCC" && $field != "Other") // dont echo  CCC or other field for formatting.
		    echo "<td>$field</td>";
	}
    echo "<tr>\n";

	for($i=0; $res[$i]; $i++) {
		$cur = $res[$i];
        $results = array();
        // rebuild array and take out CCC field and other field
        foreach($cur as $key => $value){
            if ($key != "CCC" && $key != "other") {
                if ($key == "total_fare")
                    /* quick note here:
                       money_format("$%.2n",floatval($cur[$key])) should work.  but money_format is dependant on local settings.
                       Idk about international customers so I didn't want to do that.  It seems to always be USD.
                    */
                    $cur[$key] = "$".$cur[$key].".00";
                $results[$key]=$cur[$key];
            }
        }
        $bg = $i % 2 ? 'EEE' : 'FFF';  // doesnt do anything anymore due to CSS.  keeping though.
		echo "<tr style=\"background-color: #$bg;\">";
		foreach ($results as $v)
			echo "<td>$v</td>";
		echo "</tr>\n";
	}
	echo "</table>";
}
function print_ccc_table($res) {
	if (!$res) return;
	$summary = get_ccc_summary($res);
	$month = CmnFns::getOrPost('month');
	$year = CmnFns::getOrPost('year');
	$group = CmnFns::getOrPost('group');
	$exportStr = "";
	$exportStr .= $month ? "&month=$month" : "";
	$exportStr .= $year ? "&year=$year" : "";
	if (Auth::isAdmin())
		$exportStr .= "&group=$group"
	?>
	<div style="text-align: center; font-size: large; margin-top: 5px;">
	CCC Summary
	</div>
	<div>
	<a href="reports.php?export=1&mode=ccc<?=$exportStr?>">Export Results</a>
	</div>
	<table width="50%" cellspacing=1 cellpadding=2 style="background-color: #EEF;">
	  <tr style="font-weight: bold; background-color: #EFE;">
	<?

	foreach ($summary[0] as $field => $v) {
		$field = str_replace("_", " ", $field);
		$field = ucwords($field);
		echo "<td>$field</td>";
	}
	echo "<tr>\n";

	for($i=0; $summary[$i]; $i++) {
		$cur = $summary[$i];

		$bg = $i % 2 ? 'EEE' : 'FFF';

		echo "<tr style=\"background-color: #$bg;\">";
		foreach ($cur as $v)
			echo "<td>$v</td>";
		echo "</tr>\n";
	}

	echo '</table>';
}
function get_ccc_summary($res) {
	$s = $return = array();
	for ($i=0; $res[$i]; $i++) {
		$cur = $res[$i];
		$curcc = $cur['CCC'];
		if (array_key_exists($curcc, $s)) {
			$s[$curcc]['trip_count']++;
			$s[$curcc]['total'] += $cur['total_fare'];
		} else {
			$s[$curcc]['CCC'] = $curcc;
			$s[$curcc]['trip_count'] = 1;
			$s[$curcc]['total'] = $cur['total_fare'];
		}
	}

	foreach ($s as $v) {
		$return[] = $v;
	}

	usort($return, 'cccsort');
	return $return;
}
function cccsort($x, $y) {
	if ($x['total'] == $y['total'])
		return 0;
	else if ($x['total'] < $y['total'])
		return 1;
	else
		return -1;
}


function get_reservation_data() {
		global $d;
		$user = get_user();

		if (Auth::isAdmin())
			$group = CmnFns::getOrPost('group');
		else
			$group = $user['groupid'];

		if (!$group) return array();

		// 1 month range...
		$startDate = $s = get_date();
		$endDate = mktime(0,0,0, date("m", $s), 0, date("Y", $s));
	
		if ($s == 0) $endDate = mktime(0,0,0);

		$query = "SELECT distinct res.resid, res.date,
			res.startTime, res.endTime,
			res.created, res.modified,
			res.summary, res.flightDets,
			rs.address1 as fromAdd1, rs.address2 as fromAdd2,
			rs.city as fromCity, rs.state as fromState,
			rs.lat as fromLat, rs.lon as fromLon,
			rs.name as fromName, rs.zip as fromZip,
			toLoc.address1 as toAdd1,
			toLoc.city as toCity, toLoc.state as toState,
			toLoc.name as toName, toLoc.zip as toZip,
			res.special_items as specialItems,
			trip_log.total_fare, trip_log.cc, 
			l.fname, l.lname, l.position as ccc,
			dispatch.code as dispatch_status,
			pay.code as pay_status
			FROM (reservations res, login l, resources rs, resources toLoc)
			left join trip_log on trip_log.resid = res.resid
			left join codes pay on pay.id = trip_log.pay_status
			left join codes dispatch on dispatch.id = trip_log.dispatch_status
		 	WHERE 
			res.memberid=l.memberid 
			AND res.machid=rs.machid 
			AND res.toLocation = toLoc.machid 
			and l.groupid=?
			and res.date >= ?
			and res.date < ?
			and res.is_blackout=0
			and trip_log.dispatch_status <> 9
			and trip_log.dispatch_status <> 14
			ORDER BY res.date ASC, res.startTime ASC, l.lname";

		//$vals = array($user['groupid'], $startDate, $endDate);
		$vals = array($group, $endDate, $startDate);

		$q = $d->db->prepare($query);
		$result = $d->db->execute($q, $vals);

		$return = array();

		while ($row = $result->fetchRow()) {
			$cur = array();
		
			if (strtoupper($row['lname']) == 'SHUTTLE') continue;
			if (strpos($row['specialItems'], "P") !== false) continue;

			$notes 	= $d->parseNotes($row['summary']);

			//$cur['resid']	= $row['resid'];
			$cur['resid'] = strtoupper(substr($row['resid'], -6));
			$cur['date'] 	= date("m/d/y", $row['date']);
			$cur['total_fare'] = $row['total_fare'];
			
			$cur['CCC']	= $notes['code'] ? $notes['code'] : $row['ccc'];

			$cur['payment_method'] = $row['pay_status'];

			if ($row['pay_status'] == '**INVOICE**')
				$cur['payment_method'] = 'Invoice';
			else if ($row['pay_status'] == 'PAID')
				$cur['payment_method'] = 'Paid by Credit Card';

			$cur['CC'] = $row['cc'] ? '*'.$row['cc'] : '';

			$cur['booker_name'] 	= $row['fname']." ".$row['lname']; 
			$cur['passenger_name_if_different'] 	= $notes['name'] ? $notes['name'] : ''; 

			$cur['from']	= $row['fromName'];
			$cur['from_address'] = $row['fromAdd1'].", ".$row['fromCity']." ".$row['fromState']." ".$row['fromZip'];
			$cur['to']	= $row['toName'];
			$cur['to_address'] = $row['toAdd1'].", ".$row['toCity']." ".$row['toState']." ".$row['toZip'];

			$cur['flight_info'] = str_replace("{`}", " ", $row['flightDets']);
			$cur['other'] = ($notes['cell'] == 'Not given' ? '' : $notes['cell'])." ".$notes['email']." ".$notes['email'];


			$return[] = $cur;
		}

		return $return;
}
function get_user() {
	global $d;
	$memberid = $_SESSION['sessionID'];
	$vals = array($memberid);
	
	$query = "select l.*, bg.group_name
		  from login l join billing_groups bg on l.groupid=bg.groupid
		  where l.memberid=?";
	$row = $d->db->getRow($query, $vals);
	return $row;
}
function get_groups() {
	global $d;

	$query = "select groupid, group_name from billing_groups
		  order by group_name";
	$result = $d->db->query($query);

	$return = array();

	while ($row = $result->fetchRow()) {
		$return[$row['groupid']] = $row['group_name'];
	}

	return $return;
}
function get_group_name($group) {
	global $d;
	if (!$group) return false;

	$query = "select group_name from billing_groups where groupid=?";
	$row = $d->db->getRow($query, array($group));
	return $row['group_name'];
}

function get_months(){
	$range = range(1, 12);  // 12 months ...
	$months = array();

	foreach ($range as $k=>$v) {
		$stamp = mktime(0,0,0,$v,1);
		$months[$v] = date("F", $stamp);
	}
	return $months;
}

function get_years(){
	$years = array();
	for ($i=0; $i<=5; $i++) {
		// store string formatted date as key and value for dropdown and query.  since we're using maketime...
		$years[date("Y")-$i] = date("Y")-$i;
	}
	return $years;
}

function get_date($string = false) {
	$year = CmnFns::getOrPost('year');
	$month = CmnFns::getOrPost('month');

	if(!isset($year))
		$year = 0;

	if(!isset($month))
		$month = 0;

	$unixdate = mktime(0,0,0, $month+1, 0, $year); // last day of month before

	if ($string) return date("m/Y", $unixdate);
	else return $unixdate;
}

function do_export($res, $mode=''){
	if (!$res) {
		header('Location: reports.php');
		return;
	}
	if ($mode == 'ccc') {
		$summary = get_ccc_summary($res);
		CmnFns::export_excel_array($summary);
	} else {
		CmnFns::export_excel_array($res);
	}
	die(0);
}
?>