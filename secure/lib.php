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
	
	
	$url = "http://maps.google.com/maps?" .
	//$url = "http://www.p-tran.com:7071/maps.php?" .
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
	$output = 'xml';

	$querystr = "http://maps.google.com/maps/geo?q=$q&output=$output&key=$key";

	$rdf =& new RDFParser($querystr);
	$return = $rdf->ParseRDF();

	list($return['lat'], $return['lon'], $other) = split(",", $return[1]['coords']);

	return $return;
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

       $fp = fopen($this->_url, "r");
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
               $this->_item['items'][$this->_item['i']]['name'] .= $data;
           }
           if($this->_item['akt']=="COORDINATES") {
               $this->_item['items'][$this->_item['i']]['coords'] .= $data;
           }
	   	   if($this->_item['akt']=="ADDRESS") {
               $this->_item['items'][$this->_item['i']]['addr'] .= $data;
           }
           if($this->_item['akt']=="ADMINISTRATIVEAREANAME") {
		       $this->_item['items'][$this->_item['i']]['state'] .= $data;
           }
           if($this->_item['akt']=="LOCALITYNAME") {
		       $this->_item['items'][$this->_item['i']]['city'] .= $data;
           }
           if($this->_item['akt']=="THOROUGHFARENAME") {
		       $this->_item['items'][$this->_item['i']]['streetaddr'] .= $data;
           }
           if($this->_item['akt']=="POSTALCODENUMBER") {
		       $this->_item['items'][$this->_item['i']]['zip'] .= $data;
           }
           if($this->_item['akt']=="CODE") {
		   	   $this->_item['items'][$this->_item['i']]['code'] .= $data;
           }
       }
   }
}
?>
