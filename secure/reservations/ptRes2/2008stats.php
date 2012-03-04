<?php
include_once('lib/DBEngine.class.php');
$d = new DBEngine();

$lo = '2008-12-29';
$hi = '2009-01-04';

$query = "SELECT count( r.date ) AS the_count,
sum( if( fromLoc.state = 'CA'
OR fromLoc.zip LIKE '9%'
OR toLoc.state = 'CA'
OR toLoc.zip LIKE '9%', 1, 0 ) ) AS ca_count,
sum( if( fromLoc.state = 'CA'
OR fromLoc.zip LIKE '9%'
OR toLoc.state = 'CA'
OR toLoc.zip LIKE '9%', tl.total_fare, 0 ) ) AS ca_total,
sum( if( fromLoc.state = 'CA'
OR fromLoc.zip LIKE '9%'
OR toLoc.state = 'CA'
OR toLoc.zip LIKE '9%', 0, 1 ) ) AS ma_count,
sum( if( fromLoc.state = 'CA'
OR fromLoc.zip LIKE '9%'
OR toLoc.state = 'CA'
OR toLoc.zip LIKE '9%', 0, tl.total_fare ) ) AS ma_total,
sum( tl.total_fare )
FROM login l, reservations r
LEFT JOIN resources fromLoc ON r.machid = fromLoc.machid
LEFT JOIN resources toLoc ON r.toLocation = toLoc.machid
LEFT JOIN trip_log tl ON r.resid = tl.resid where r.memberid = l.memberid and
r.date <= unix_timestamp( '$hi' ) and
r.date >= unix_timestamp( '$lo' )
and is_blackout = 0";

$qresult = mysql_query($query);
$row = mysql_fetch_assoc($qresult);
$title = array();
$titlestr = '';
$print = array();
$printstr = '';
$h = fopen("t.txt", "w");
foreach ($row as $k => $v) {
	$title[] = $k;
	$print[] = $v; 
}
$title[] = 'date_lo';
$title[] = 'date_hi';
$print[] = $lo;
$print[] = $hi;
$titlestr = implode("\t", $title) . "\n";
$printstr = implode("\t", $print) . "\n";
//echo $query;
echo "$titlestr<br>$printstr<br>";
fwrite($h, $titlestr);
fwrite($h, $printstr);

for ($i = 0; $i <= 200; $i += 7) {
	$timestamp = mktime(0,0,0,01,05,2009);
	$lostamp = mktime(0,0,0,date("m", $timestamp), date("d", $timestamp)+$i, date("Y", $timestamp));
	$histamp = mktime(0,0,0,date("m", $timestamp), date("d", $timestamp)+($i+6), date("Y", $timestamp));
	$lo = date("Y-m-d", $lostamp);
	$hi = date("Y-m-d", $histamp);
$query = "SELECT count( r.date ) AS the_count,
sum( if( fromLoc.state = 'CA'
OR fromLoc.zip LIKE '9%'
OR toLoc.state = 'CA'
OR toLoc.zip LIKE '9%', 1, 0 ) ) AS ca_count,
sum( if( fromLoc.state = 'CA'
OR fromLoc.zip LIKE '9%'
OR toLoc.state = 'CA'
OR toLoc.zip LIKE '9%', tl.total_fare, 0 ) ) AS ca_total,
sum( if( fromLoc.state = 'CA'
OR fromLoc.zip LIKE '9%'
OR toLoc.state = 'CA'
OR toLoc.zip LIKE '9%', 0, 1 ) ) AS ma_count,
sum( if( fromLoc.state = 'CA'
OR fromLoc.zip LIKE '9%'
OR toLoc.state = 'CA'
OR toLoc.zip LIKE '9%', 0, tl.total_fare ) ) AS ma_total,
sum( tl.total_fare )
FROM login l, reservations r
LEFT JOIN resources fromLoc ON r.machid = fromLoc.machid
LEFT JOIN resources toLoc ON r.toLocation = toLoc.machid
LEFT JOIN trip_log tl ON r.resid = tl.resid where r.memberid = l.memberid and
r.date <= unix_timestamp( '$hi' ) and
r.date >= unix_timestamp( '$lo' )
and is_blackout = 0";

	//echo "$query<br>";
	
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);
	$print = array();
	$printstr = '';
	foreach ($row as $k => $v) {
		$print[] = $v; 
	}
	$print[] = $lo;
	$print[] = $hi;
	$printstr = implode("\t", $print) . "\n";
	fwrite($h, $printstr);
	echo "$printstr<br>";
	
}
?>
