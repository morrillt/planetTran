<?php
/*
* Produce XML output from database of coordinates
* Format:
$test = '<markers>';
$test .= '<marker lat="42.3599233" lng="-71.101713"/>';
$test .= '<marker lat="42.3617784" lng="-71.097614"/>';
$test .= '</markers>';
echo $test;
0 id
1 type
2 label
3 lat
4 lng
5 timestamp
*/
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'xml'; /* xml or php */
if ($mode == 'xml') {
	include_once('lib/DBEngine.class.php');
	$d = new DBEngine();
	$xml = get_cars($mode);
	echo $xml;
}
//*********************************************************
function get_cars($mode = 'xml') {
	$id = $_SESSION['currentID'];
	$php = array();
	$query = "SELECT g.id, g.label, g.lat, g.lon, UNIX_TIMESTAMP(g.timestamp) as timestamp, r.lat as locLat, r.lon as locLon, r.name as locName, r.machid as machid 
	FROM geocoding g join codes c 
	on substr(g.label, -2)=substr(c.code, -2)
	join trip_log t on c.id=t.vehicle
	join reservations res on res.resid=t.resid
	join resources r on r.machid=res.machid
	where c.category='vehicle'
	and res.memberid='$id'
	and (t.dispatch_status=227 or t.dispatch_status=355
	  or t.dispatch_status=10  or t.dispatch_status=11)";
	$qresult = mysql_query($query);
	//if (!$qresult || !mysql_num_rows($qresult)) return false;
	$xml = "";
	while ($row = mysql_fetch_assoc($qresult)) {
		if (empty($row['lat']) || empty($row['lon']))
			continue;
		$cmp = time() - $row['timestamp'];
		if ($row['id']!='home'&&$row['id']!='logan') 
			$row['timestamp'] = ($cmp > 120 && $cmp <= 900) ? "true" : ($cmp > 900 ? "kill" : "false");
		if ($mode == 'php') $php[] = $row;
		else {
			if (isset($row['locLat'],$row['locLon'],$row['locName']))			{
				$htmlId = $row['machid'];
				//$row['locName'] = addslashes($row['locName']);
				$xml .= "<marker lat=\"{$row['locLat']}\" lng=\"{$row['locLon']}\" label=\"{$row['locName']}\" warning=\"location\" id=\"$htmlId\"/>";
			}
			$xml .= "<marker lat=\"{$row['lat']}\" lng=\"{$row['lon']}\" label=\"{$row['label']}\" warning=\"{$row['timestamp']}\" id=\"{$row['id']}\"/>";
		}
	}
	if ($mode == 'php') return $php;
	else return '<markers>'.$xml.'</markers>';
}
?>
