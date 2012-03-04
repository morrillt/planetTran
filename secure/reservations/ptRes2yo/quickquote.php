<?php
include_once('../../../lib.php');
include_once('lib/DBEngine.class.php');
//include_once('lib/DBEngine.class.php');
$wrapper = "wrapper.pl";
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

<? if ($toLong) { ?>
  <div class="bodytext" align="center"> Unfortunately, we currently do not provide service between <?=$location1 ?> and <b><?=$location2?></b>.
     Perhaps you entered the incorrect address or airport. Go back to retry the quote.
  </div>
<? } else if ($price2004Calc == 0.0) { ?>
  <div class="bodytext" align="center">
    The quick quote feature is temporarily unavailable.  Check back in a little while, or email us at <a href="mailto:quickquote@planettran.com">quickquote@planettran.com</a>.
    Thanks for your patience and cooperation.
  </div>
<? } else { ?> 
  Estimated <b><?=$fareType ?></b> Fares* between <?=$location1?> and <?=$location2?>
  <div align="center">   
    <p class="style1">$<?=$price2004Calc?></p>
      <a href="http://reservations.planettran.com">Click here to login and make a reservation...</a>
    <p class="style1">&nbsp;</p>
    <p class="bodytext">*<?=$disclaimer ?>.  The quote is all-inclusive (no extra service charges or airport fees), but not bridge tolls.<br>There are NEVER any fuel surcharges with PlanetTran.<br>Call 1 888 PLNT TRN (888-756-8876) or go <a href="http://reservations.planettran.com">here</a> to make a reservation.</p>
  </div>
<? } ?>

  