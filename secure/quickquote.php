<?php   
include_once('lib.php');
include_once('reservations/ptRes2/lib/CmnFns.class.php');
//include_once('secure/reservations/ptRes2/lib/DBEngine.class.php');
//include_once('lib/DBEngine.class.php');
$wrapper = "reservations/ptRes2/wrapper.pl";
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
$fromAddr = ucwords($_POST['fromAddr']);
$fromCity = ucwords($_POST['fromCity']);
$fromZip = ucwords($_POST['fromZip']);
$toAddr = ucwords($_POST['toAddr']);
$toCity = ucwords($_POST['toCity']);
$toZip = $_POST['toZip'];

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

$airport = $_POST['airport'];
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
	
//$stats = getRouteStats($from, $to);
$disc = 0;
if($groupid && $groupid != '8')
	$disc = getDiscount($groupid);
$distance = 0.0;

	//$distance = ($distance <= 4.0 && $distance != 0 ? 4.0 : $distance);

$region_from = get_service_region($fromZip, get_state($states, $fromCity), '');
if(isset($_POST['airport']) && $_POST['airport'] != 'P2P')
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
        if(isset($_POST['quoteregion']) && $_POST['quoteregion']==2){
            $quoteregion = 2;
        }*/

        $variable = 'address1=' . $location1 . '&address2=' . $location2 .'&address3=&wait_time=0&amenities=0,0,0&memberid=&groupid=&coupon=&origin=w&vehicle_type=P&trip_type=P&region='. $regioncode;

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

function get_state($states, $address)
{
	$state = strtoupper(substr($address, -3));
	if(in_array($state, $states))
		return $state;
	
	return '';
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>PlanetTran - Eco-Friendly Airport Shuttle Service</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="planettran.css" rel="stylesheet" type="text/css">
<link href="css.css" rel="stylesheet" type="text/css">
<meta name="description" content=""revolutionary airport livery for the greater boston area and beyond."">
<meta name="keywords" content="hybrid, car, taxi, cab, limo, livery, shuttle, service, logan, international, airport, environmentally, friendly, eco, cambridge, boston, climate, protection, PlanetTran, planet, tran">
<style type="text/css">
<!--
.style1 {font-size: 14px}
.style2 {font-size: 16px}
-->
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/navbar_contactroll.gif','images/navbar_hybridroll.gif','images/navbar_aboutroll.gif','images/navbar_partnersroll.gif','images/navbar_specialroll.gif')">
<div id="content-main" align="center">
  <table width="600" border="0" cellpadding="0" cellspacing="0">
   <?   if (strpos($_SERVER['HTTP_REFERER'], 'qq.php')===false) {
		include("header_nav.shtml"); 
	?>
	<tr> 
      <td height="40" colspan="4" valign="top"><img src="images/subhead_reservations.jpg" width="600" height="40"></td>
    </tr>

   <? 
	}
   ?>

<? if ($toLong) { ?>
	  <tr><td colspan="4"><div class="bodytext" align="center"> Unfortunately, we currently do not provide service between <?=$location1 ?> and 
            <b><?=$location2?></b>.
      Perhaps you entered the incorrect address or airport.  Go back to retry the quote.</div></td></tr>
<? } else if ($fare == 0.0) { ?>
<tr><td colspan="4"><div class="bodytext" align="center">We were unable to automatically generate a quote for your locations. Email us at <a href="mailto:customerservice@planettran.com">customerservice@planettran.com</a>, or call 888-756-8876 (press option 2).  Thanks for your patience and cooperation. </div></td></tr>
<? } else { 
	
	$pc = intval($price2004Calc);	

	if ($pc > 0 && $pc < 29)
		$price2004Calc = 29;


	?> 
      <tr>
	<td colspan="4">
	<div class="bodytext" align="center"><b style="font-size: large;">Estimated flat-rate fare: $<?=$fare?></b><br>
	<b><a href="http://reservations.planettran.com">Click here to log in and make a reservation.</a></b>
	</div>
	<div class="bodytext" align="left">
	<br>&nbsp;<br>
	<b>Details:</b>
	<br>
	<ul>
	<li>Fare Type: <?=$fareType?></li>
	<li>Pick-Up Location: <?=$pick_up?></li>
	<li>Drop-Off Location: <?=$drop_off?></li>
	</ul>
	</div>
	</td>
    </tr>
    <tr>
      <td colspan="4" valign="top" class="bodyheader">   <div align="center">   
	<?
	if ($specialfare) {
		echo '<p class="bodytext">'."Nights (after 7pm) and weekends <b>\$$specialfare</b>.</p>";
	}
	?>
        <p class="bodytext">
	<div style="text-align: left;">
	Additional Information:

	<ul>
	<li>The quote is based on distance and does not include applicable wait time.</li>
	<li>The quote does not include vehicle upgrade charges or charges for infant or booster seats. Fares include tolls. Airport fees are NOT included.</li>
	<li>PlanetTran does not charge fuel surcharges.</li>
	<li>Tips are neither expected nor included in our flat-rate pricing.</li>
	</ul>
	</div>

	</p>
        <? } ?>    <td valign="top" class="bodytext">
        </div></td>
      <td>&nbsp;</td>
    </tr>

    <tr> 
      <td height="20" colspan="4" valign="top"><img src="images/footer.gif" width="600" height="20"></td>
    </tr>
  </table>
</div>
</body>
</html>
