<?php
include_once('lib.php');
include_once('secure/reservations/ptRes2/lib/DBEngine.class.php');
//include_once('lib/DBEngine.class.php');
$wrapper = "secure/reservations/ptRes2/wrapper.pl";
//$wrapper = "../scripts/wrapper.pl";
$d = new DBEngine();
$disclaimer = "";
$foundit = false;
$responseString = "";
$responseStr = "";
$number = 0;
$distance = "";
$discount = 1.0;
$fareType = "One-Way";
$fromAddr = $_POST['fromAddr'];
$fromCity = $_POST['fromCity'];
$fromZip  = $_POST['fromZip'];
$toAddr   = $_POST['toAddr'];
$toCity   = $_POST['toCity'];
$toZip    = $_POST['toZip'];
$airport  = $_POST['airport'];
trim($fromAddr);
trim($fromCity);
trim($fromZip);
trim($toAddr);
trim($toCity);
trim($toZip);

$from = $fromZip;
$to = $toZip;
$location1 = $fromAddr . ($fromAddr == "" ? "" : ", ") . $fromCity . " " . $fromZip; 
$location2 = $toAddr . ($toAddr == "" ? "" : ", ") . $toCity . " " . $toZip; 
if($airport == "P2P") {

} else {
	$to = $airport;
	$location2 = $airport;
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
	
//$stats = getRouteStats($from, $to);
$disc = 1;
if($_GET['groupid'])
	$disc = getDiscount($_GET['groupid']);
$distance = 0.0;

	//$distance = ($distance <= 4.0 && $distance != 0 ? 4.0 : $distance);

	$from = escapeshellarg($from);
	$to = escapeshellarg($to);
	exec("perl $wrapper $from $to", $a);
	$fare = $a[0];

	$price2004Calc = round($fare * $disc);

	if($newFromAddr == "" && !$useGZ) {
		$disclaimer = "The quote is an estimated " . $fareType . " fare between " . $location1 . " and " . $location2 . "."; 
	} else if ($useCityOnly) {
		$disclaimer = "The address you entered could not be found; this quote is an estimated " . $fareType . " fare based on the center of " . $fromCity;
	
	} else {
		$disclaimer = "The quote is the current " .  $fareType . " fare between " . $location1 . " and " . $location2 . ".";
	}	
	//}	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>PlanetTran - Eco-Friendly Airport Shuttle Service</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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

<body leftmargin="0" topmargin="10" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/navbar_contactroll.gif','images/navbar_hybridroll.gif','images/navbar_aboutroll.gif','images/navbar_partnersroll.gif','images/navbar_specialroll.gif')">
<div align="center" id="main-content">
  <table width="600" border="0" cellpadding="0" cellspacing="0" bgcolor="#EDEDED">
   <?   if (strpos($_SERVER['HTTP_REFERER'], 'qq.php')===false) {
		include("header_nav.shtml"); 
	?>
	<tr> 
      <td height="40" colspan="4" valign="top"><img src="images/subhead_reservations.jpg" width="600" height="40"></td>
    </tr>

   <? 
	}
   ?>
    <tr> 
      <td colspan="4" width="600" height="58">&nbsp;</td></tr>
<? if ($toLong) { ?>
	  <tr><td colspan="4"><div class="bodytext" align="center"> Unfortunately, we currently do not provide service between <?=$location1 ?> and 
            <b><?=$location2?></b>.
      Perhaps you entered the incorrect address or airport.  Go back to retry the quote.</div></td></tr>
<? } else if ($price2004Calc == 0.0) { ?>
<tr><td colspan="4"><div class="bodytext" align="center">The quick quote feature is temporarily unavailable.  Check back in a little while, or email us at <a href="mailto:quickquote@planettran.com">quickquote@planettran.com</a>.  Thanks for your patience and cooperation. </div></td></tr>
<!-- 
<tr><td colspan="4"><div class="bodytext" align="center">The quote between <?= $location1 ?> and <?= $location2 ?> could not be generated.  Please check that you have the precise address and correct Zip Code in the quick quote form.  </div></td></tr>-->
<? } else { ?> 
      <tr><td colspan="4"><div class="bodytext" align="center"> Estimated <b><?=$fareType ?></b> Fares* between <?=$location1?> and
            <?=$location2?></div>
    </tr>
    <tr>
      <td colspan="4" valign="top" class="bodyheader">   <div align="center">   
        <p class="style1">$<?=$price2004Calc?></p>
		<a href="http://reservations.planettran.com">Click here to login and make a reservation...</a>
        <p class="style1">&nbsp;</p>
        <p class="bodytext">*<?=$disclaimer ?>.  The quote is all-inclusive (no extra service charges or airport fees), but not bridge tolls.<br>There are NEVER any fuel surcharges with PlanetTran.<br>Call 1 888 PLNT TRN (888-756-8876) or go <a href="http://reservations.planettran.com">here</a> to make a reservation.</p>
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