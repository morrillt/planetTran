<?php

/***************************************************************
* On April 15th Novartis Shuttle moves back to 10am
***************************************************************/

$head = '(resid, machid, toLocation, memberid, scheduleid, date, startTime, endTime, created, modified, parentid, is_blackout, summary, flightDets, checkBags, special_items, createdBy, moddedBy, coupon_code)';

$start = 1;

for ($i = $start; $i <= 60; $i++) {
	$date = mktime(0,0,0,date("m"),date("d")+$i,date("Y"));

	if ($i == $start) echo"Start date is ".date("m/d/Y", $date)."<br>&nbsp;<br>";

	if (date("D", $date)=='Sat'||date("D",$date)=='Sun')
		continue;
	$curtime = time();
	// 420 = 7am

	// new vertex shuttle
	$resid = uniqid('stl');
	$q = "INSERT INTO reservations $head VALUES ('$resid', 'glb4d026718d4035', 'glb4546b310ad586', 'glb4546b2fa00070', 'glb4546b2fa11182', $date, 510, 525, $curtime, 1292502333, 'glb4d093d3f536dc', 0, '', NULL, 0, '1', 'glb4716506e587ee', 'glb4cffa46631103', NULL);";
	echo "$q<br>";

	// Vertex shuttle
	$resid = uniqid('stl');
	$q = "INSERT INTO reservations $head VALUES ('$resid', 'glb4546b326cd152', 'glb4546b310ad586', 'glb4546b2fa00070', 'glb4546b2fa11182', $date, 510, 525, $curtime, NULL, 'glb46944c46ce617', 0, '', NULL, 0, '', 'glb449994bd9c421', NULL, NULL);";
	echo "$q<br>";

	// Vertex shuttle other way
	$resid = uniqid('stl');
	$q = "INSERT INTO reservations $head VALUES ('$resid', 'glb4546b310ad586', 'glb4546b326cd152', 'glb4546b2fa00070', 'glb4546b2fa11182', $date, 510, 525, $curtime, NULL, 'glb46944c46ce617', 0, '', NULL, 0, '', 'glb449994bd9c421', NULL, NULL);";
	echo "$q<br>";

	// MIT shuttle
	$resid = uniqid('stl');
	$q = "INSERT INTO `reservations` $head VALUES ('$resid', 'hmt42d40489668e4', 'glb438b95fa88b9c', 'hmt42d404207a158', 'hmt42d404207ef5f', $date, 420, 435, $curtime, NULL, 'glb469429f46b460', 0, '', NULL, 0, '', 'glb449994bd9c421', NULL, NULL);";
	echo "$q<br>";

	// Novartis
	$resid = uniqid('stl');
	$q = "INSERT INTO `reservations` $head VALUES ('$resid', 'glb4570317fd94b4', 'glb45703193ea623', 'glb457031578d9d7', 'glb4570315793698', $date, 600, 615, $curtime, NULL, 'glb471c9b6fb260a', 0, '', NULL, 0, '', 'glb449994bd9c421', NULL, NULL);";
	echo "$q<br>";


	/* 
	// GZ out until further notice
	// GZ 8am

	// GZ 7:30am
	$resid = uniqid('stl');
	$q = "INSERT INTO `reservations` $head VALUES ('$resid', 'glb4683073583eeb', 'glb4683071b35085', 'glb468306e42fba1', 'glb468306e430757', $date, 450, 465, $curtime, NULL, 'glb46942a0bc558a', 0, '', NULL, 0, '', 'glb449994bd9c421', NULL, NULL);";
	echo "$q<br>";

	$resid = uniqid('stl');
	$q = "INSERT INTO `reservations` $head VALUES ('$resid', 'glb4683073583eeb', 'glb4683071b35085', 'glb468306e42fba1', 'glb468306e430757', $date, 480, 495, $curtime, NULL, 'glb46942a0bc558a', 0, '', NULL, 0, '', 'glb449994bd9c421', NULL, NULL);";
	echo "$q<br>";

	// GZ 8:30am
	$resid = uniqid('stl');
	$q = "INSERT INTO `reservations` $head VALUES ('$resid', 'glb4683073583eeb', 'glb4683071b35085', 'glb468306e42fba1', 'glb468306e430757', $date, 510, 525, $curtime, NULL, 'glb46942a0bc558a', 0, '', NULL, 0, '', 'glb449994bd9c421', NULL, NULL);";
	echo "$q<br>";
	
	// Accenture afternoon shuttle
	$resid = uniqid('stl');
	$q = "INSERT INTO `reservations` $head VALUES ('$resid', 'glb4970e84ccfb39', 'glb4970e8719114c', 'glb4970e48a64586', 'glb4970e48a84ce2', $date, 990, 1005, $curtime, NULL, 'glb4974dab76e10f', 0, 'GROUP_DELGROUP_DELGROUP_DELGROUP_DELGROUP_DELGROUP_DELGROUP_DELThis is a continuous shuttle from 128 Sidney Street, Cambridge to the Central Square T Station on Mass Ave until 6:00 PM Please contact Laurel upon arrival  617-649-9248GROUP_DEL', NULL, 0, '', 'glb449994bd9c421', NULL);";
	echo "$q<br>";
	*/

}

echo"<br>&nbsp;<br>End date is ".date("m/d/Y", $date);

?>
