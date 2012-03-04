<?php
set_include_path("../:lib/pear/:/usr/local/php5");
include_once('lib/Twitter.class.php');
include_once('lib/Template.class.php');
include_once('templates/mobile.template.php');


$resume = urlencode("twitter_confirm.php?resid=".$_GET['resid']);
if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume='.$resume);
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] .' '. $_GET['lname'];
	}
}



$resid = isset($_GET['resid']) ? $_GET['resid'] : (isset($_POST['resid']) ? $_POST['resid'] : false);
if (!$resid) die("Reservation ID is required.");

$t = new Twitter();


$res 	= $t->get_temp_reservation($resid);

if (!is_array($res)) header("Location: m.reserve.php?resid=$resid&type=v");

pdaheader();

$from 	= $t->get_temp_resource_data($res['machid']);
$to 	= $t->get_temp_resource_data($res['toLocation']);

if (!$from)
	$from = $t->resDB->get_resource_data($res['machid']);
if (!$to)
	$to = $t->resDB->get_resource_data($res['toLocation']);

if (!is_array($from) || !is_array($to)) die ("Invalid location.");

/** The reservation and both locations should all be valid now.*************/

if (isset($_POST['resid'], $_POST['machid'], $_POST['toLocation'])) {
	$t->insert_twitter_res($res);
	// delete temp locations
	print_thanks();
} else {
	print_confirm_form($res, $from, $to);
}
	
pdafooter();

/**********************************************************************/
function print_thanks() {
	?>
	<div class="paragraph" style="text-align: center;">
	<img src="images/planettran_logo_pda.gif">
	<br>
	Your reservation has been created.
	<br>
	To view the car status or to modify your reservation, please visit <a href="http://m.planettran.com">PlanetTran Mobile</a> or <a href="http://www.planettran.com">PlanetTran Online</a>.
	<br>
	<a href="m.main.php">Main Menu</a>
	</div>
	<?
}
function print_confirm_form($res, $from, $to) {
	?>
	<form name="twitterRes" id="myform" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<?

	foreach($res as $k=>$v) {
		echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
	}

	?>
	</form>
	<script type="text/javascript">
		var myform = document.getElementById("myform");
		myform.submit();
	</script>
	<?
	//CmnFns::diagnose($res);
	//CmnFns::diagnose($from);
	//CmnFns::diagnose($to);
}
