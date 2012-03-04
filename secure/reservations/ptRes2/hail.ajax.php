<?php
// Return a lat and longitude as xml

include_once('lib/DBEngine.class.php');
include_once('lib/Mobile.class.php');
$lat = $_GET['lat'];
$lon = $_GET['lon'];
$latlng = "$lat,$lon";
$scheduleid = $_GET['scheduleid'];

$override = ($_GET['override'] && $_GET['override'] == "1") ? 1 : 0;

//if (!$override)
//	$loc = match_profile_loc($scheduleid, $lat, $lon);
//else
$loc = false;

$bestmatch = array();

//if ($loc) {
if (0) {
	// Get closest match
	$best = $loc[0];
	for ($i=0; $loc[$i]; $i++) {
		$cur = $loc[$i];
		if ($cur['score'] < $best['score'])
			$best = $cur;
	}
	$loc = $best;
}

$sensor = "true";
$url = "http://maps.google.com/maps/api/geocode/xml?latlng=$latlng&sensor=$sensor";

$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$xml .= "\t<address_parts>\n";


if ($loc) {
	$address_array = array();
	list($num, $street) = explode(" ", $loc['address1'], 2);
	$address_array['name'] = $loc['name'];
	$address_array['machid'] = $loc['machid'];
	$address_array['street_number'] = $num;
	$address_array['route'] = $street;
	$address_array['locality'] = $loc['city'];
	$address_array['state'] = $loc['state'];
	$address_array['postal_code'] = $loc['zip'];

} else {
	
	// $address_array from xml_parser.php
	include('xml_parser.php');

}

$address_array['lat'] = $lat;
$address_array['lon'] = $lon;

foreach ($address_array as $k=>$v) {
	if (!$v)
		continue;
	$xml .= "\t\t<$k>$v</$k>\n";
}

$xml .= "\t</address_parts>\n";
header("Content-type: text/xml");
echo $xml;

/**
* Check profile for similar location
*/
function match_profile_loc($scheduleid, $lat, $lon) {
	$d = 3;
	$ran = 1000;
	$lat = intval($lat * $ran);
	$lon = intval($lon * $ran);
	$db = new DBEngine();
	$vals = array($_SESSION['sessionID']);
	$query = "select r.* from 
		  permission p join resources r on r.machid=p.machid
		  where p.memberid=?";
	$q = $db->db->prepare($query);
	$result = $db->db->execute($q, $vals);

	$return = array();

		//echo "<pre>";
		//echo "lat\tcmplat\tlon\tcmplon\tlatSC\tlonSC\ttotal\tname<br>";
	while($row = $result->fetchRow()) {
		$loclat = $row['lat'];
		$loclon = $row['lon'];

		if (!$loclat || !$loclon) continue;

		$cmplat = intval($loclat * $ran);
		$cmplon = intval($loclon * $ran);

		$row['latscore'] = abs($cmplat - $lat);
		$row['lonscore'] = abs($cmplon - $lon);
		$row['score'] = $row['latscore'] + $row['lonscore'];
		//echo "$lat\t$cmplat\t$lon\t$cmplon\t{$row['latscore']}\t{$row['lonscore']}\t{$row['score']}\t{$row['name']}<br>";

		if ($lat < ($cmplat + $d) && $lat > ($cmplat - $d) &&
		    $lon < ($cmplon + $d) && $lon > ($cmplon - $d)) {
			//echo "above is match<br>";


			$return[] =  $row;
		}
	
	}

	if (!empty($return)) return $return;

	return false;
}
?>
