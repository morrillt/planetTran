<?php   

include_once('lib/CmnFns.class.php');
//include_once('secure/reservations/ptRes2/lib/DBEngine.class.php');
//include_once('lib/DBEngine.class.php');
$wrapper = "wrapper.pl";
//$wrapper = "../scripts/wrapper.pl";
//$d = new DBEngine();
$disclaimer = "";
$foundit = false;
$responseString = "";
$responseStr = "";
$number = 0;
$distance = "";
$discount = 1.0;
$fareType = "One-Way";
$fromAddr = ucwords($_GET['fromAddr']);
$fromCity = ucwords($_GET['fromCity']);
$fromZip = ucwords($_GET['fromZip']);
$toAddr = ucwords($_GET['toAddr']);
$toCity = ucwords($_GET['toCity']);
$toZip = $_GET['toZip'];

$states = array(' MA',' NH',' RI',' CA',' ME',' CT', ' NY');
$apts = array (
		"BOS"=>"Logan Int'l (Boston, MA)",
		"PVD"=>"T.F.Green Int'l (Providence, RI)",
		"MHT"=>"Manchester Int'l (Manchester, NH)",
		"SFO"=>"San Francisco Int'l (SF, CA)",
		"SJC"=>"San Jose Int'l (San Jose, CA)",
		"OAK"=>"Oakland Int'l (Oakland, CA)"
	);

foreach ($states as $v) {
	$cur = strtoupper(substr($fromCity, -3));
	if ($cur == $v) {
		$fromCity = substr($fromCity, 0, -3);
		$fromCity .= $cur;
	}

	$cur = strtoupper(substr($toCity, -3));
	if ($cur == $v) {
		$toCity = substr($toCity, 0, -3);
		$toCity .= $cur;
	}
		
}

$airport = $_GET['airport'];
trim($fromAddr);
trim($fromCity);
trim($fromZip);
trim($toAddr);
trim($toCity);
trim($toZip);

$from = $fromZip;
$to = $toZip;
$location1 = $fromAddr . ($fromAddr == "" ? "," : ", ") . $fromCity . "," . $fromZip; 
$location2 = $toAddr . ($toAddr == "" ? "," : ", ") . $toCity . "," . $toZip; 

$pick_up = $fromAddr . ($fromAddr == "" ? "" : ", ") . $fromCity . ($fromCity == "" ? "" : ", ") . $fromZip;
$drop_off = $toAddr . ($toAddr == "" ? "" : ", ") . $toCity . ($toCity == "" ? "" : ", ") . $toZip;


if($airport == "P2P") {

} else {
	$to = $airport;
	$location2 = $airport;

	foreach ($apts as $k=> $v) 
		if ($location2 == $k)
			$location2 = $v;
			
	$drop_off = $location2;
}	
if($airport == "BOS") {
	$toLogan = 1;
} else {
	$toLogan = 0;
}
if($airport == "SFO") {
	$toSFO = 1;
} else {
	$toSFO = 0;
}

$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : 0;

$memberid=(isset($_SESSION['currentID']) ? $_SESSION['currentID'] : $_SESSION['sessionID']);
	
//$stats = getRouteStats($from, $to);
$disc = 0;
if($groupid && $groupid != '8')
	$disc = getDiscount($groupid);
$distance = 0.0;

	//$distance = ($distance <= 4.0 && $distance != 0 ? 4.0 : $distance);

$region_from = get_service_region($fromZip, get_state($states, $fromCity), '');
if(isset($_GET['airport']) && $_GET['airport'] != 'P2P')
{
			$region_to = get_service_region('', '', $airport);
}
else
	$region_to = get_service_region($toZip, get_state($states, $toCity), '');

if($region_from > 0 && $region_from == $region_to)
{
	
	$regioncode = $region_from;
	
	if($_GET['script'] == 0) //users with gourpid will be taken old price policy.
    {
        
        $from = escapeshellarg($from);
	    $to = escapeshellarg($to);
		//echo "perl $wrapper $from $to 1";
   

        exec("perl $wrapper $from $to 1", $a);
	    $fare = $a[0];
		
	    $price2004Calc = round($fare - $fare * $disc);
    } 
    else
    {

        /*$quoteregion = 1;
        if(isset($_GET['quoteregion']) && $_GET['quoteregion']==2){
            $quoteregion = 2;
        }*/

        $variable = 'address1=' . $location1 . '&address2=' . $location2 .'&address3=&wait_time=0&amenities=0,0,0&memberid=' . $memberid .'&groupid=' . $groupid . '&coupon=&origin=w&vehicle_type=P&trip_type=P&region='. $regioncode;

        $out = exec('/home/planet/scripts/estimate.pl'.' '.EscapeShellArg($variable));  
        parse_str($out);
    	    
	    if ($groupid == 1523) 
		    $specialfare = round($fare - $fare * 0.33);
	    else
		    $specialfare = null;
    }
    
	if($newFromAddr == "" && !$useGZ) {
		$disclaimer = "The quote is an estimated " . $fareType . " fare between " . $location1 . " and " . $location2 . ""; 
	} else if ($useCityOnly) {
		$disclaimer = "The address you entered could not be found; this quote is an estimated " . $fareType . " fare based on the center of " . $fromCity;
	
	} else {
		$disclaimer = "The quote is the current " .  $fareType . " fare between " . $location1 . " and " . $location2 . ".";
	}	
}
else
{
	$fare = 0.0;
}

echo $fare . '|' . $pick_up . '|' . $drop_off;
return;


function get_state($states, $address)
{
	$state = strtoupper(substr($address, -3));
	if(in_array($state, $states))
		return $state;
	
	return '';
}

function getDiscount($groupid) {
//	$d = mysql_connect('localhost', 'planet_schedul', 'schedule');
//	$db = mysql_select_db('planet_reservations');	
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
	

function get_service_region($zip_code, $state_code, $airport){
    $state = CmnFns::get_state_from_zip($zip_code);
    if($state==''){
        $state = $state_code;
    }
    
	if($state == '' && $airport == '')
		return -1;

    
    //$airport = apt_or_zip($res['machid'],'');
    
    $region = 1;
    
    if ($state == "CA" ||
            $state == "NV" ||
            $state == "AZ" ||
            $state == "OR" ||
            $airport == "SFO" || $airport=="SJC" || $airport == "OAK"){
          
         $region=2;
    }

    return $region;
}
		
?>