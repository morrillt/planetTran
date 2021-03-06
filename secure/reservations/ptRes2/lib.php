<?php
//$toLoc = "17 Peters St., Cambridge, MA";
//$from = "PVD";

//header("Content-type: text/html");

//echo var_dump(getRouteStats($from, $toLoc));

//exit;

function getRouteStats($fr, $to) {
	$fr = str_replace(" ", "+", $fr);
	$to = str_replace(" ", "+", $to);

	$stats1 = getOneWay($fr, $to);

	$stats2 = getOneWay($to, $fr);

	if(empty($stats1['dist'])) {
		$stats1 = $stats2;
	}

	if(empty($stats2['dist'])) {
		$stats2 = $stats1;
	}
	
	if($stats1['dist'] > $stats2['dist']) {
		$ret_array['max'] = $stats1;
		$ret_array['min'] = $stats2;
	} else {
		$ret_array['max'] = $stats2;
		$ret_array['min'] = $stats1;
	}
	
	return $ret_array;
}	

function getOneWay($fr, $to) {
	
	
	//$url = "http://www.p-tran.com:7071/maps.php?" .
	$url = "http://maps.google.com/maps?" .
		"saddr=$fr&daddr=$to&hl=en&type=js";

	// ==== Grab the directions XML from google
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$htmlsrc = curl_exec($ch);
	curl_close($ch);
	//$pattern = '/Drive<\/a>:<\/td><td(.*?)>(\d*\.\d*)(.*?)(\(about.*?mins\))/';
	//$pattern = '/Drive<\/a>:<\/td><td(.*?)>(\d*[\.|,]*\d*)(.*?)(\(about.*?(mins|hours)\))/';
	$pattern = '/timedist ul(.*?)(\d*[\.|,]\d*)/';
	//$pattern = '/timedist ul(.*?)(\d*[\.|,]*\d*)/';
	preg_match($pattern, $htmlsrc, $matches);
	$distance = $matches[2];
	$distance = preg_replace('/,/', '', $distance);
	//$time=$matches[4];	
	//echo "<h1>$distance</h1>";
	$pattern = '/.*?\;(\d*) hour/';
	preg_match($pattern, $time, $matches);
	$hour = (strlen($matches[1]) > 2 ? 0 : $matches[1]);
	$pattern = '/.*?(\d*) [min|mins]/';
	preg_match($pattern, $time, $matches);
	$min = $matches[1];
	
	$stats['dist'] = $distance;
	$stats['hour'] = $hour;
	$stats['mins'] = $min;	
	return $stats;
}
function getDiscount($groupid) {
	$d = mysql_connect('localhost', 'planet_schedul', 'schedule');
	$db = mysql_select_db('planet_reservations');	
	$query = "SELECT groupid, type, discount FROM billing_groups
		  WHERE groupid=$groupid";
	$qresult = mysql_query($query);

	if(mysql_num_rows($qresult)==1) {
		$row = mysql_fetch_assoc($qresult);
		$discount = $row['discount']>0 ? $row['discount'] / 100:0;
		return $discount;
	} else {
		return 0;
	}
}
function getGPS ($location) {
	// Try Yahoo
	$yahoo = getYahooGPS($location);
	if (isset($yahoo['pre']) && empty($yahoo['warning']))
		return $yahoo;

	// Try Google
	$q = str_replace(" ", "+", $location);
	$key = 'ABQIAAAA6-o8Z6aZH02DSj3RfkVyPhR9qh0ap0Yue2kiz5r0lIky9wj4khTwfOOiuGUPOmegDIIX_dgU68ZE3w';
	$output = 'json';
	$querystr = "http://maps.google.com/maps/geo?q=$q&output=$output&key=$key";
	$json = json_decode(file_get_contents($querystr));
	// die(print_r($json));

	$p = $json->Placemark[0]->AddressDetails;
	$ll = $json->Placemark[0]->Point->coordinates;
	// return 

	$aa = $p->Country->AdministrativeArea;

	$street = $aa->Locality->Thoroughfare->ThoroughfareName;
	$zip = $aa->Locality->PostalCode->PostalCodeNumber;
	$state = $aa->AdministrativeAreaName;
	$city = $aa->Locality->LocalityName;
	
	if($sa = $aa->SubAdministrativeArea)
	{
	  $street = $sa->Locality->Thoroughfare->ThoroughfareName;
	  $zip = $sa->Locality->PostalCode->PostalCodeNumber;
	  // $state = $sa->AdministrativeAreaName;
	  $city = $sa->Locality->LocalityName;
	}

	return
	/*die*/(/*print_r*/(array (
	  'streetaddr' => $street,
	  'zip' => $zip,
	  'state' => $state,
	  'city' => $city,
	  'lat' => $ll[1],
	  'lon' => $ll[0]
	)));
}
function getYahooGPS($address) {
	$address = urlencode($address);
	$appid = 'p-tran.com';
	$output = 'php';
	$return = array();
	$url = "http://where.yahooapis.com/geocode?appid=$appid&location=$address&output=$output";
			
	$response = @file_get_contents($url);

	if ($response === false)
		return $return;

	$array = unserialize($response);

	$return['pre'] = $array['ResultSet']['Result']['Quality'];
	$return['lat'] = $array['ResultSet']['Result']['Latitude'];
	$return['lon'] = $array['ResultSet']['Result']['Longitude'];

	$return['streetaddr'] = $array['ResultSet']['Result']['Street'];
	$return['streetaddr'] = ucwords(strtolower($return['streetaddr']));
	$return['city'] = $array['ResultSet']['Result']['City'];
	$return['city'] = ucwords(strtolower($return['city']));
	$return['state'] = $array['ResultSet']['Result']['State'];
	$return['zip'] = $array['ResultSet']['Result']['Uzip'];

	return $return;
}
class RDFParser {
   var $_item;
   var $_url;

   function RDFParser($url) {
       $this->_url = $url;
   }

   function ParseRDF() {
       $this->_item = array('i' => 0);

       $parser = xml_parser_create();
       xml_set_object($parser, &$this);
       xml_set_element_handler($parser, "_startElement", "_endElement");
       xml_set_character_data_handler($parser, "_charHandler");

       $fp = @fopen($this->_url, "r");
       while(!feof($fp)) {
           $line = fgets($fp, 4096);
           xml_parse($parser, $line);
       }
       fclose($fp);

       xml_parser_free($parser);

       return($this->_item['items']);
   }


   function _startElement($parser, $name, $attrs)  {
       $this->_item['maychar'] = true;

       if($name=="RESPONSE") {
           $this->_item['mayparse'] = true;
           $this->_item['i']++;
       } elseif($name=="NAME") {
           $this->_item['akt'] = "NAME";
       } elseif($name=="COORDINATES") {
           $this->_item['akt'] = "COORDINATES";
       } elseif($name=="ADDRESS") {
           $this->_item['akt'] = "ADDRESS";
       } elseif($name=="ADMINISTRATIVEAREANAME") {
           $this->_item['akt'] = "ADMINISTRATIVEAREANAME";
       } elseif($name=="LOCALITYNAME") {
	   $this->_item['akt'] = "LOCALITYNAME";
       } elseif($name=="THOROUGHFARENAME") {
	   $this->_item['akt'] = "THOROUGHFARENAME";
       } elseif($name=="POSTALCODENUMBER") {
	   $this->_item['akt'] = "POSTALCODENUMBER";
       } elseif($name=="CODE") {
	   $this->_item['akt'] = "CODE";
       } else {
           $this->_item['maychar'] = false;
       }
   }

   function _endElement($parser, $name) {
       if($name=="RESPONSE") {
           $this->_item['mayparse'] = false;
       } elseif($name=="NAME" || $name=="COORDINATES" || $name=="ADDRESS" || $name=="ADMINISTRATIVEAREANAME" || $name=="LOCALITYNAME" || $name=="THOROUGHFARENAME" || $name=="POSTALCODENUMBER" || $name=="CODE") {
           $this->_item['maychar'] = false;
       }
   }

   function _charHandler($parser, $data) {
       if($this->_item['maychar'] && $this->_item['mayparse']) {
           if($this->_item['akt']=="NAME") {
               $this->_item['items'][$this->_item['i']]['name'] = $data;
           }
           if($this->_item['akt']=="COORDINATES") {
               $this->_item['items'][$this->_item['i']]['coords'] = $data;
           }
	   	   if($this->_item['akt']=="ADDRESS") {
               $this->_item['items'][$this->_item['i']]['addr'] = $data;
           }
           if($this->_item['akt']=="ADMINISTRATIVEAREANAME") {
		       $this->_item['items'][$this->_item['i']]['state'] = $data;
           }
           if($this->_item['akt']=="LOCALITYNAME") {
		       $this->_item['items'][$this->_item['i']]['city'] = $data;
           }
           if($this->_item['akt']=="THOROUGHFARENAME") {
		       $this->_item['items'][$this->_item['i']]['streetaddr'] = $data;
           }
           if($this->_item['akt']=="POSTALCODENUMBER") {
		       $this->_item['items'][$this->_item['i']]['zip'] = $data;
           }
           if($this->_item['akt']=="CODE") {
		   	   $this->_item['items'][$this->_item['i']]['code'] = $data;
           }
       }
   }
}

function checkGPS ($from, $to) { //'
	$retarr = array();
	$query = "SELECT address1,"
			."city, "
			."state, "
			."zip, "
			."lat, "
			."lon "
			."FROM resources WHERE machid='$from'";
	$qresult = mysql_query($query);
	check_for_dberror($qresult);
	$row = mysql_fetch_assoc($qresult);

	if (empty($row['lat']) || empty($row['lon'])) {
		//$fromLoc = $row['address1'].", ".$row['city'].", ".$row['state']." ".$row['zip'];
		$fromLoc = $row['address1'].' '.$row['zip'];
		$fromArr = getGPS($fromLoc); // return arrays have lat, lon

		if (empty($fromArr['lat']) || empty($fromArr['lon'])) {
			$retarr['reason'] = "FROM";
			$retarr['machid'] = $from;
			return $retarr;
		} else {
			update_resource_GPS($fromArr['lat'], $fromArr['lon'], $from);
		}
	}
	$query = "SELECT address1,"
			."city, "
			."state, "
			."zip, "
			."lat, "
			."lon "
			."FROM resources WHERE machid='$to'";
	$qresult = mysql_query($query);
	check_for_dberror($qresult);
	$row = mysql_fetch_assoc($qresult);

	if (empty($row['lat']) || empty($row['lon'])) {
		//$toLoc = $row['address1'].", ".$row['city'].", ".$row['state']." ".$row['zip'];
		$toLoc = $row['address1'].' '.$row['zip'];
		$toArr = getGPS($toLoc);

		if (!$toArr['lat'] || !$toArr['lon']) {
			$retarr['reason'] = "TO";
			$retarr['machid'] = $to;
			return $retarr;
		} else {
			update_resource_GPS($toArr['lat'], $toArr['lon'], $to);
		}
	}

}
function update_resource_GPS($lat, $lon, $machid) {
	$query = "UPDATE resources SET lat='$lat', lon='$lon' WHERE machid='$machid'";
	$qresult = mysql_query($query);
	check_for_dberror($qresult);
}
function add_dynstat($resid, $db) {
	db_hookup3($db);

	$query = 'SELECT res.resid, '
			.'res.startTime, '
			.'rs.address1 as fromLoc, '
			.'rs.city as fromCity, '
			.'rs.lat as fromLat, '
			.'rs.lon as fromlon, '
			.'toLoc.address1 as toLoc, '
			.'toLoc.city as toCity, '
			.'toLoc.lat as toLat, '
			.'toLoc.lon as toLon, '
			.'res.created '
			.'FROM reservations as res, login as l, resources as rs, resources as toLoc '
		    .'WHERE res.memberid=l.memberid AND res.machid=rs.machid '
		    .'AND res.toLocation=toLoc.machid '
		    .'AND res.created+30>=UNIX_TIMESTAMP(NOW())';

	$qresult = mysql_query($query);
	check_for_dberror($qresult);
	$row = mysql_fetch_assoc($qresult);

	db_hookup3("planet_reservations");

		if (!empty($row['fromLat']) && !empty($row['fromLon'])) {
			$fromArr['lat'] = $row['fromLat'];
			$fromArr['lon'] = $row['fromLon'];
		} else {
		   	$fromLoc = trim($row['fromLoc']) . ", " . trim($row['fromCity']) . ", MA";
			$fromArr = getGPS($fromLoc); // return arrays have lat, lon
		}
		if (!empty($row['fromLat']) && !empty($row['fromLon'])) {
			$toArr['lat'] = $row['toLat'];
			$toArr['lon'] = $row['toLon'];
		} else {
			$toLoc = trim($row['toLoc']) . ", " . trim($row['toCity']) . ", MA";
			$toArr = getGPS($toLoc);
		}

		$fromgps = $fromArr['lon'] . "," . $fromArr['lat'];
		$togps = $toArr['lon'] . "," . $toArr['lat'];
		$stats = getOneWay($fromgps, $togps); // stats has dist, hour, mins

		$startTime = $row['startTime'];
		$toTime = $row['startTime'] + ($stats['hour']*60 + $stats['mins']);
		if (!$toTime) $toTime = 0;
		if (!$stats['dist']) $stats['dist'] = 0;

		$query = "INSERT INTO dynstats (resid, fromLat, fromLon, fromTime, toLat, toLon, toTime, distance) ";
		$query = $query . "VALUES('{$row['resid']}', "
					. "'{$fromArr['lat']}', "
					. "'{$fromArr['lon']}', "
					. "$startTime, "
					. "'{$toArr['lat']}', "
					. "'{$toArr['lon']}', "
					. "$toTime, "
					. "{$stats['dist']})";
		$qresult = mysql_query($query);
		check_for_dberror($qresult);
}
function mod_dynstat($resid, $db) { //
	db_hookup3($db);

	$query = 'SELECT res.resid, '
			.'res.startTime, '
			.'rs.address1 as fromLoc, '
			.'rs.city as fromCity, '
			.'rs.lat as fromLat, '
			.'rs.lon as fromlon, '
			.'toLoc.address1 as toLoc, '
			.'toLoc.city as toCity, '
			.'toLoc.lat as toLat, '
			.'toLoc.lon as toLon, '
			.'res.created '
			.'FROM reservations as res, login as l, resources as rs, resources as toLoc '
		    .'WHERE res.memberid=l.memberid AND res.machid=rs.machid '
		    .'AND res.toLocation=toLoc.machid';

	$qresult = mysql_query($query);
	check_for_dberror($qresult);
	db_hookup3("planet_reservations");

		$row = mysql_fetch_assoc($qresult);
	   	$fromLoc = trim($row['fromLoc']) . ", " . trim($row['fromCity']) . ", MA";
		$toLoc = trim($row['toLoc']) . ", " . trim($row['toCity']) . ", MA";

		if (!empty($row['fromLat']) && !empty($row['fromLon'])) {
			$fromArr['lat'] = $row['fromLat'];
			$fromArr['lon'] = $row['fromLon'];
		} else {
		   	$fromLoc = trim($row['fromLoc']) . ", " . trim($row['fromCity']) . ", MA";
			$fromArr = getGPS($fromLoc); // return arrays have lat, lon
		}
		if (!empty($row['fromLat']) && !empty($row['fromLon'])) {
			$toArr['lat'] = $row['toLat'];
			$toArr['lon'] = $row['toLon'];
		} else {
			$toLoc = trim($row['toLoc']) . ", " . trim($row['toCity']) . ", MA";
			$toArr = getGPS($toLoc);
		}

		$fromgps = $fromArr['lon'] . "," . $fromArr['lat'];
		$togps = $toArr['lon'] . "," . $toArr['lat'];

		$stats = getOneWay($fromgps, $togps); // stats has dist, hour, mins
		$startTime = $row['startTime'];
		$toTime = $row['startTime'] + ($stats['hour']*60 + $stats['mins']);
		if (!$toTime) $toTime = 0;
		if (!$stats['dist']) $stats['dist'] = 0;

		$query = "UPDATE dynstats SET ";
		$query = $query . "fromLat='{$fromArr['lat']}', "
					. "fromLon='{$fromArr['lon']}', "
					. "fromTime=$startTime, "
					. "toLat='{$toArr['lat']}', "
					. "toLon='{$toArr['lon']}', "
					. "toTime=$toTime, "
					. "distance={$stats['dist']} "
					. "WHERE resid='$resid'";
		$qresult = mysql_query($query);
		check_for_dberror($qresult);
}
function del_dynstat ($resid) {
	db_hookup3("planet_reservations");
	$query = "DELETE from dynstats WHERE resid='$resid'";
	$qresult = mysql_query($query);
	check_for_dberror($qresult);
}
function db_hookup3($dbname) {
	$db= mysql_connect('localhost', 'root', 'earth');
	if (!$db) {
	   die('Could not connect: ' . mysql_error());
	}

	$curdb = mysql_select_db('planet_reservations', $db);
	if (!$curdb) {
	   die ('Can\'t use '.$dbname.' : ' . mysql_error());
	}
}
function check_for_dberror($qresult) {
	if (!$qresult) {
	   $message  = 'Invalid query: ' . mysql_error() . "<p>\n";
	   $message .= 'Whole query: ' . $query . "<p>\n";
	   die($message);
	}
}

function get_service_region($location_id){

	$query = "SELECT state FROM resources WHERE machid = '" . $location_id . "'";
	$result = mysql_query($query);
	$row  = mysql_fetch_assoc($result);
	$state = $row['state'];

    $region = 1;
    if ($state == "CA" ||
            $state == "NV" ||
            $state == "AZ" ||
            $state == "OR" ){
        $region=2;
    }
    return $region;
}

?>
