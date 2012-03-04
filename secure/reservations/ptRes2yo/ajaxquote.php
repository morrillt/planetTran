<?php  
include_once('lib/db/AdminDB.class.php');
$d = new AdminDB();
$wrapper = "wrapper.pl";
/*
if (!$_GET['fromID'] || !$_GET['toID']) {
	echo "Please select both a pickup and a dropoff location to get a quote.";
	die;
}
*/

$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : 0;

$from = $d->get_resource_data($_GET['fromID']);
$to = $d->get_resource_data($_GET['toID']);
$stop = $d->get_resource_data($_GET['stopID']);
$discount = $d->getDiscount($groupid);
if ($groupid == 8) $discount = 0;
$groupmsg = "";
if ($groupid) $groupmsg = " and includes any discounts on this account";

//$fromquote = apt_or_zip($from['machid'], $from['zip']);
//$toquote = apt_or_zip($to['machid'], $to['zip']);

if($_REQUEST['from_address']) {
  $location1 = $_REQUEST['from_address'].' '.$_REQUEST['from_city'].' '.$_REQUEST['from_state'].', '.$_REQUEST['from_zip'];
  $region_location1 = get_service_region3($_REQUEST['from_state'],$_REQUEST['from_zip']);
} else {
  $location1 = generate_location($from);
  $region_location1 = get_service_region2($from);
}

if($_REQUEST['to_address']) {
  $location2 = $_REQUEST['to_address'].' '.$_REQUEST['to_city'].' '.$_REQUEST['to_state'].', '.$_REQUEST['to_zip'];
  $region_location2 = get_service_region3($_REQUEST['to_state'],$_REQUEST['to_zip']);
} else {
  $location2 = generate_location($to);
  $region_location2 = get_service_region2($to);
}

if($_REQUEST['stop_address']) {
  $location3 = $_REQUEST['stop_address'].' '.$_REQUEST['stop_city'].' '.$_REQUEST['stop_state'].', '.$_REQUEST['stop_zip'];
  $region_location3 = get_service_region3($_REQUEST['stop_state'],$_REQUEST['stop_zip']);
} else {
  $location3 = generate_location($stop);
  $region_location3 = get_service_region2($stop);
}


if(isset($_GET['stopID']) && $_GET['stopID'] !='')
{
	if($region_location1 == $region_location2 && $region_location1 == $region_location3)
	{
		$regioncode = $region_location1;
	}
	else
	{
		$fare = 0;
		echo $fare . '|' . $location1 . '|' . $location2;
		return;
	}
}
elseif($region_location1 == $region_location2)
{
	$regioncode = $region_location1;
}		
else
{
		$fare = 0;
		echo $fare . '|' . $location1 . '|' . $location2;
		return;
}

$air_code = apt_or_zip($from['machid'],'');
if($air_code==''){
    $air_code = apt_or_zip($to['machid'],'');
}

//$regioncode = get_service_region($from['zip'], $to['zip'], $air_code,$from['state'], $to['state']);

$memberid=(isset($_SESSION['currentID']) ? $_SESSION['currentID'] : $_SESSION['sessionID']);
$convertible_seats=0;
$booster_seats=0;
$meet_greet =0;
//Groupid is set above
//$groupid='';
$coupon='';
$vehicle_type='P';
$trip_type='P';

if(isset($_REQUEST['memberid'])&& ! empty($_REQUEST['memberid'])){
	$memberid = $_REQUEST['memberid'];
}
if(isset($_REQUEST['convertible_seats'])&& ! empty($_REQUEST['convertible_seats'])){
	$convertible_seats = $_REQUEST['convertible_seats'];
}
if(isset($_REQUEST['booster_seats'])&& ! empty($_REQUEST['booster_seats'])){
	$booster_seats=$_REQUEST['booster_seats'];
}
if(isset($_REQUEST['meet_greet '])&& ! empty($_REQUEST['meet_greet '])){
	$meet_greet =$_REQUEST['meet_greet'];
}
if(isset($_REQUEST['groupid'])&& ! empty($_REQUEST['groupid'])){
	$groupid = $_REQUEST['groupid'];
}
if(isset($_REQUEST['coupon'])&& ! empty($_REQUEST['coupon'])){
	$coupon = $_REQUEST['coupon'];
}
if(isset($_REQUEST['vehicle_type'])&& ! empty($_REQUEST['vehicle_type'])){
	$vehicle_type = $_REQUEST['vehicle_type'];
}
if(isset($_REQUEST['trip_type'])&& ! empty($_REQUEST['trip_type'])){
	$trip_type = $_REQUEST['trip_type'];
}

$variable = 'address1=' . $location1 . '&address2=' . $location2 .'&address3=' . $location3. '&wait_time=0&amenities='.$convertible_seats.','.$booster_seats.','.$meet_greet.'&memberid='.$memberid.'&groupid='.$groupid.'&coupon='.$coupon.'&origin=w&vehicle_type='.$vehicle_type.'&trip_type='.$trip_type.'&region=' . $regioncode;
// die($variable);

$script = get_script();

if(0 && $script == 0) //users with gourpid will be taken old price policy.
{
    
    $fromZip = trim($from['zip']);
    $toZip = trim($to['zip']);
    
	/*
	if($fromZip == '02128')
		$fromZip = 'BOS';
	elseif($fromZip == '03103')   
		$fromZip = 'MHT';
	elseif($fromZip == '94621')   
		$fromZip = 'OAK';
	elseif($fromZip == '94128')   
		$fromZip = 'SFO';
	elseif($fromZip == '95110')   
		$fromZip = 'SJC';
	elseif($fromZip == '02886')   
		$fromZip = 'PVD';
    */      

	if($toZip == '02128')
		$toZip = 'BOS';
	elseif($toZip == '03103')   
		$toZip = 'MHT';
	elseif($toZip == '94621')   
		$toZip = 'OAK';
	elseif($toZip == '94128')   
		$toZip = 'SFO';
	elseif($toZip == '95110')   
		$toZip = 'SJC';
	elseif($toZip == '02886')   
		$toZip = 'PVD';
		
	   
    //echo "perl $wrapper '$fromZip' '$toZip' 1";
    
    exec("perl $wrapper '$fromZip' '$toZip' 1", $a);
    $fare = $a[0];

    $fare = round($fare - $fare * $discount);

}
else
{
	$out = exec('/home/planet/scripts/estimate.pl'.' '.EscapeShellArg($variable));  
	parse_str($out);
}

if($fare > 0 && $fare < 29)
	$fare = 29;

echo $fare . '|' . $location1 . '|' . $location2 . '|' . ($coupon_amount?$coupon_amount:0) . '|' . ($base_fare?$base_fare:0);

/*
exec("perl wrapper.pl $fromquote $toquote 1", $a, $b);
$fare = $a[0];
$fare = round($fare - $fare * $discount);
*/

/*
if (!$fare)
	echo "We were unable to automatically generate a quote for those two locations. If either are lacking a zip code, please add one and try again. Please call 1-888-PLN-TTRN (756-8876) for assistance.";
else
	echo "Your fare estimate is \$$fare$groupmsg. This figure is based on the distance to your zip code; actual fares may vary.";
 
*/

/***********************************************/
function apt_or_zip($machid, $zip) {
	if (strpos($machid, 'airport') !== false)
		$return = substr($machid, -3);
	else if (stripos($machid, 'logan') !== false)
		$return = 'BOS';
	else if ($machid == '41b40be9091cb')
		$return = 'BOS';
	else
		$return = $zip;

	return escapeshellarg($return);
}

function get_script() {
    $id=(isset($_SESSION['currentID']) ? $_SESSION['currentID'] : $_SESSION['sessionID']);
	$query = "select script from login where memberid='".$id."'";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);
	return $row['script'];
}

function generate_location($addrID)
{
		
	$address1 = trim($addrID['address1']);
	$address2 = trim($addrID['address2']);
	$zip = trim($addrID['zip']);

	$address = '';
	
	if($address1 == '' && $address2 != '')
		$address = $address2;
	elseif($address1 != '' && $address2 == '')
		$address = $address1;
	elseif($address1 != '' && $address2 != '')
		$address = $address1 . " " . $address2;	 
	
	if(trim($addrID['city']) != '')	
		$city = ($address == "" ? "" : ", ") . trim($addrID['city']);
	else
		$city = '';
		
	if(trim($addrID['state']) != '')	
		$state = (($address . $city) == "" ? "" : ", ") . trim($addrID['state']);
	else
		$state = '';
		
	return $address . $city . $state . ", " . $zip;	 

}

function get_region($from, $to){
    if($from['state']=='MA'){
        return 1;
    }
    
}
function get_service_region($from_zip, $to_zip, $airport_code, $from_state, $to_state){
    if($from_state=="MA" || $to_state=="MA"){
        return 1;
    }
    $from_zip = CmnFns::get_state_from_zip($from_zip);
    if($from_zip==''){
        $from_zip =$from_state;
    }
    $to_zip = CmnFns::get_state_from_zip($to_zip);
    if($to_zip==''){
        $to_zip =$to_state;
    }
    $region = 1;
    if ($from_zip == "CA" || $to_zip== "CA" ||
            $from_zip == "NV" || $to_zip== "NV" ||
            $from_zip == "AZ" || $to_zip== "AZ" ||
            $from_zip == "OR" || $to_zip == "OR" ||
            $airport_code == "SFO" || $airport_code=="SJC" || $airport_code == "OAK"){
        $region=2;
    }
    return $region;
}
function get_service_region2($res){
    $state = CmnFns::get_state_from_zip($res['zip']);
    if($state==''){
        $state = $res['state'];
    }
    $airport = apt_or_zip($res['machid'],'');
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

function get_service_region3 ($state,$zip)
{
    $airport = $zip;
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

